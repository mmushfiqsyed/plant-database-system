<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: lightgrey; }
        .action-INSERT { color: green; font-weight: bold; }
        .action-UPDATE { color: orange; font-weight: bold; }
        .action-DELETE { color: red; font-weight: bold; }
        nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
        nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
        #no-logs{text-align: center;}
    </style>
</head>
<body>
    <nav>
        <h2>Audit Log</h2>
        <a href="admin_dashboard.php">Return to Dashboard</a>
    </nav>
    <?php
    require "private/admin_check.php"; 
    require "private/db_connect.php";

    $query = "SELECT a.*, u.username FROM audit_log AS a LEFT JOIN users u ON a.admin_id = u.user_id ORDER BY a.changed_at DESC";

    $result = $conn->query($query);
    ?>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Table Name</th>
                <th>Action Taken</th>
                <th>Record ID</th>
                <th>Admin ID</th>
                <th>Admin Name</th>
                <th>Log Details</th>
                <th>Changed At</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['log_id']; ?></td>
                        <td><?php echo $row['table_name']; ?></td>
                        <td class="action-<?php echo $row['action_type']; ?>"><?php echo $row['action_type']; ?></td>
                        <td><?php echo $row['record_id']; ?></td>
                        <td><?php echo ($row['admin_id'] ?? 'System'); ?></td>
                        <td><?php echo ($row['username'] ?? 'Automated'); ?></td>
                        <td><?php echo $row['log_details']; ?></td>
                        <td><?php echo $row['changed_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="8" id="no-logs">No Logs Found.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php $conn->close(); ?>
</body>
</html>