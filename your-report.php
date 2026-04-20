<?php 
    include "private/db_connect.php";
    include "private/login_check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reports</title>
    <style>
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: lightgrey; }
        nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
        nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
        #no-logs{text-align: center;}
    </style>
</head>
<body>
    <nav>
        <h2>Your Reports</h2>
        <a href="index.php">Return to Dashboard</a>
    </nav>
    <?php 
    // Get current user_id
    $current_user_id = intval($_SESSION['user_id']);

    // Select rows which are from current logged in user:
    $user_rows = $conn->prepare("SELECT pr.plant_name_suggested, pr.`description`, pr.`status`, r.region_name FROM plant_reports AS pr JOIN region AS r ON pr.region_id = r.region_id WHERE submitted_by = ?");
    $user_rows->bind_param('i', $current_user_id);
    $user_rows->execute();
    $result = $user_rows->get_result();
    ?>

    <table>
        <thead>
            <tr>
                <th>Report ID</th>
                <th>Location</th>
                <th>Plant Name</th>
                <th>Description</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Loop through each report row that is made by this user and also show report number.
            $counter = 1;
            if ($result->num_rows > 0): 
            ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($counter); $counter++;?></td>
                        <td><?php echo htmlspecialchars($row['region_name'] ?? 'Unknown'); ?></td>
                        <td><?php echo htmlspecialchars($row['plant_name_suggested']); ?></td>
                        <td><?php echo htmlspecialchars($row['description']); ?></td>
                        <td><?php echo htmlspecialchars($row['status']); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr><td colspan="5" id="no-logs">You have yet to submit a report.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
    <?php $conn->close(); ?>
</body>
</html>