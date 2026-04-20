<?php 
    include "private/db_connect.php";
    include "private/login_check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Official Records</title>
    <style>
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            th { background-color: lightgrey; }
            nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
            nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
    </style>
</head>
<body>
    <nav>
        <h2>Official Records</h2>
        <a href="index.php">Return to Dashboard</a>
    </nav>
    <div>
        <table>
            <thead>
                <tr>
                    <th>Record ID</th>
                    <th>Region</th>
                    <th>Area (sqm)</th>
                    <th>Date Added</th>
                    <th>Managed By</th>
                    <th>Plants in Area</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Complex query to group the many-to-many plants into one string
                $view_query = "SELECT pr.*, r.region_name, u.username, 
                            GROUP_CONCAT(p.plant_name SEPARATOR ', ') as plant_list
                            FROM plant_record pr
                            JOIN region r ON pr.region_id = r.region_id
                            JOIN users u ON pr.managed_by = u.user_id
                            LEFT JOIN record_plants rp ON pr.record_id = rp.record_id
                            LEFT JOIN plant p ON rp.plant_id = p.plant_id
                            GROUP BY pr.record_id
                            ORDER BY pr.date_added DESC";
                
                $view_result = $conn->query($view_query);

                while($v_row = $view_result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $v_row['record_id']; ?></td>
                        <td><?php echo htmlspecialchars($v_row['region_name']); ?></td>
                        <td><?php echo $v_row['area_size']; ?></td>
                        <td><?php echo $v_row['date_added']; ?></td>
                        <td><?php echo htmlspecialchars($v_row['username']); ?></td>
                        <td><small><?php echo htmlspecialchars($v_row['plant_list'] ?? 'None'); ?></small></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <?php $conn->close(); ?>
</body>
</html>