<?php
// ============================================================
//  FILE: view_dashboard.php
//  PURPOSE: Fetches and displays all ACTIVE incidents along
//           with their assigned rescue teams and casualty counts.
//           This is the main operations dashboard.
//
//  HOW TO USE:
//    Your teammate can either:
//      a) Include this whole file in their page: <?php include 'view_dashboard.php'; 
//      b) Or just copy the HTML table block into their layout.
//
//  THE QUERY EXPLAINED:
//    We do a 3-table JOIN:
//      INCIDENT → ASSIGNED_TO → RESCUE_TEAM
//    Plus a subquery to count casualties (the DERIVED ATTRIBUTE).
//    LEFT JOIN is used so incidents with NO team assigned
//    still appear (Team column just shows "None Assigned").
// ============================================================

require_once 'db_connect.php';

// ─────────────────────────────────────────────────────────────
//  THE DASHBOARD QUERY
//  This single query pulls everything needed for the dashboard:
//    - Incident details
//    - The team assigned (if any)
//    - Total casualty count (Derived Attribute — calculated live)
// ─────────────────────────────────────────────────────────────
$sql = "
    SELECT
        i.Incident_ID,
        i.Type            AS Incident_Type,
        i.Severity,
        i.City,
        i.Status,
        i.Reported_At,
        rt.Name           AS Team_Name,
        rt.Status         AS Team_Status,
        -- Subquery to COUNT casualties — this is the DERIVED ATTRIBUTE.
        -- It's calculated here, NOT stored as a column in the DB.
        (
            SELECT COUNT(*)
            FROM CASUALTY c
            WHERE c.Incident_ID = i.Incident_ID
        ) AS Total_Casualties
    FROM INCIDENT i
    -- LEFT JOIN means: show all Active incidents even if no team is assigned
    LEFT JOIN ASSIGNED_TO a  ON i.Incident_ID = a.Incident_ID
    LEFT JOIN RESCUE_TEAM rt ON a.Team_ID     = rt.Team_ID
    -- Filter: only show Active incidents on the main dashboard
    WHERE i.Status = 'Active'
    ORDER BY i.Severity DESC, i.Reported_At ASC
";

// No user input in this query, so no prepared statement needed.
// We use query() for simple SELECT statements with no external input.
$result = $conn->query($sql);

// Fetch all rows into a PHP array at once
// MYSQLI_ASSOC means each row is an associative array: $row['column_name']
$incidents = $result->fetch_all(MYSQLI_ASSOC);

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>SOS Dashboard — Active Incidents</title>
    <style>
        /* ── Basic clean styling ── */
        body        { font-family: Arial, sans-serif; background: #f4f4f4;
                      margin: 0; padding: 20px; color: #333; }
        h1          { color: #CC2200; border-bottom: 3px solid #CC2200;
                      padding-bottom: 8px; }
        .meta       { font-size: 0.85rem; color: #777; margin-bottom: 20px; }

        /* Status alert banner — shown when redirected from a form */
        .alert      { padding: 12px 18px; border-radius: 4px; margin-bottom: 16px;
                      font-weight: bold; }
        .alert.ok   { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert.err  { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

        /* Incident table */
        table       { width: 100%; border-collapse: collapse; background: #fff;
                      box-shadow: 0 1px 4px rgba(0,0,0,0.12); }
        th          { background: #CC2200; color: #fff; padding: 11px 14px;
                      text-align: left; font-size: 0.82rem; letter-spacing: 0.05em;
                      text-transform: uppercase; }
        td          { padding: 10px 14px; border-bottom: 1px solid #eee;
                      font-size: 0.9rem; vertical-align: middle; }
        tr:hover td { background: #fff5f5; }
        .no-data td { text-align: center; color: #888; padding: 30px; }

        /* Severity badge — color changes based on value 1–5 */
        .sev        { display: inline-block; padding: 2px 8px; border-radius: 3px;
                      font-weight: bold; font-size: 0.85rem; }
        .sev-5, .sev-4 { background: #ffdddd; color: #cc0000; }
        .sev-3         { background: #fff3cd; color: #856404; }
        .sev-2, .sev-1 { background: #d4edda; color: #155724; }

        /* Triage casualty count badge */
        .cas-count  { background: #333; color: #fff; border-radius: 12px;
                      padding: 2px 9px; font-size: 0.8rem; }

        /* Team status dots */
        .dot        { display: inline-block; width: 9px; height: 9px;
                      border-radius: 50%; margin-right: 5px; }
        .dot-dep    { background: #e74c3c; }
        .dot-avail  { background: #2ecc71; }
        .dot-off    { background: #aaa; }

        .no-team    { color: #aaa; font-style: italic; }
    </style>
</head>
<body>

<h1>🚨 SOS — Active Incidents Dashboard</h1>
<p class="meta">
    Showing all <strong>Active</strong> incidents with assigned teams.
    Last loaded: <strong><?= date('d M Y, H:i:s') ?></strong>
    &nbsp;|&nbsp;
    <a href="view_dashboard.php">↻ Refresh</a>
    &nbsp;|&nbsp;
    <a href="add_hospital.php">🏥 View Available Hospitals</a>
</p>

<?php
// ── Show a status message if redirected from a form action ──
// When a form redirects here with ?status=success or ?status=error,
// we display a brief alert. This is the standard pattern used
// across all pages in this system.
if (!empty($_GET['status'])) {
    if ($_GET['status'] === 'success') {
        echo '<div class="alert ok">✅ Operation completed successfully.</div>';
    } elseif ($_GET['status'] === 'error') {
        $msg = htmlspecialchars($_GET['msg'] ?? 'An unknown error occurred.');
        echo '<div class="alert err">❌ Error: ' . $msg . '</div>';
    }
}
?>

<table>
    <thead>
        <tr>
            <th>Incident #</th>
            <th>Type</th>
            <th>City</th>
            <th>Severity</th>
            <th>Assigned Team</th>
            <th>Team Status</th>
            <th>Total Casualties</th>
            <th>Reported At</th>
        </tr>
    </thead>
    <tbody>
    <?php if (empty($incidents)): ?>
        <!-- No active incidents — show a message row -->
        <tr class="no-data">
            <td colspan="8">✅ No active incidents at this time.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($incidents as $row): ?>
        <tr>
            <!-- Incident ID -->
            <td><strong>#<?= htmlspecialchars($row['Incident_ID']) ?></strong></td>

            <!-- Incident Type -->
            <td><?= htmlspecialchars($row['Incident_Type']) ?></td>

            <!-- City -->
            <td><?= htmlspecialchars($row['City']) ?></td>

            <!-- Severity: badge color changes with PHP logic -->
            <td>
                <span class="sev sev-<?= (int)$row['Severity'] ?>">
                    <?= (int)$row['Severity'] ?>/5
                </span>
            </td>

            <!-- Team Name: show 'None Assigned' if LEFT JOIN returned NULL -->
            <td>
                <?php if (!empty($row['Team_Name'])): ?>
                    <?= htmlspecialchars($row['Team_Name']) ?>
                <?php else: ?>
                    <span class="no-team">None Assigned</span>
                <?php endif; ?>
            </td>

            <!-- Team Status with color dot -->
            <td>
                <?php if (!empty($row['Team_Status'])): ?>
                    <?php
                        // Choose dot color based on team status
                        $dot_class = match($row['Team_Status']) {
                            'Deployed'  => 'dot-dep',
                            'Available' => 'dot-avail',
                            default     => 'dot-off'
                        };
                    ?>
                    <span class="dot <?= $dot_class ?>"></span>
                    <?= htmlspecialchars($row['Team_Status']) ?>
                <?php else: ?>
                    <span class="no-team">—</span>
                <?php endif; ?>
            </td>

            <!-- Total Casualties (DERIVED ATTRIBUTE — counted by subquery) -->
            <td>
                <span class="cas-count">
                    <?= (int)$row['Total_Casualties'] ?> victims
                </span>
            </td>

            <!-- Reported At timestamp -->
            <td><?= htmlspecialchars($row['Reported_At']) ?></td>
        </tr>
        <?php endforeach; ?>
    <?php endif; ?>
    </tbody>
</table>

</body>
</html>
