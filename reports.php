<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Reports</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: lightgrey; }
        nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
        nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
        #no-logs{text-align: center;}
    </style>
</head>
<?php
require "private/admin_check.php"; 
require "private/db_connect.php";

// Handle Status Updates
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['report_id'], $_POST['new_status'])) {
    $report_id = intval($_POST['report_id']);
    $new_status = $_POST['new_status']; // Should be 'approved' or 'rejected'

    $stmt = $conn->prepare("UPDATE plant_reports SET status = ? WHERE report_id = ?");
    $stmt->bind_param("si", $new_status, $report_id);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to clear the POST data and refresh the list
    header("Location: reports.php");
    exit();
}

// Fetch only pending reports to keep the workspace clean
$query = "SELECT plr.*, r.region_name, u.username, u.email 
          FROM plant_reports AS plr 
          LEFT JOIN region AS r ON plr.region_id = r.region_id 
          LEFT JOIN users AS u ON plr.submitted_by = u.user_id 
          WHERE plr.status = 'pending' 
          ORDER BY plr.region_id DESC";
$result = $conn->query($query);
?>

<body>
    <nav>
        <h2>Plant Reports</h2>
        <a href="admin_dashboard.php">Return to Dashboard</a>
    </nav>

    <table>
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Location</th>
                <th>Plant Name</th>
                <th>Description</th>
                <th>Submitted by</th>
                <th>Email</th>
                <th>Actions</th> </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['report_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['region_name'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($row['plant_name_suggested']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['username'] ?? 'System'); ?></td>
                        <td><?php echo htmlspecialchars($row['email'] ?? 'N/A'); ?></td>
                        <td>
                            <form method="POST" style="display:inline;">
                                <input type="hidden" name="report_id" value="<?php echo $row['report_id']; ?>">
                                <button type="submit" name="new_status" value="approved" style="background:lightgreen; cursor:pointer;">Approve</button>
                                <button type="submit" name="new_status" value="rejected" style="background:lightcoral; cursor:pointer;">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="7" id="no-logs">All caught up! No pending reports.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>