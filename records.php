<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Records</title>
        <style>
            nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
            nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
            .container { display: flex; justify-content:space-between; align-items:center; }
            .main-entry { background: #f9f9f9; border: 2px solid #ddd; border-radius:20px; padding:20px; padding-left:50px; padding-right:20vw;}
            .reference-sidebar { background: #f9f9f9; border: 2px solid #ddd; border-radius:20px; padding: 15px; padding-right:10vw; }
            .hint-card { border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; background: white; font-size: 0.9em; }
        </style>
</head>
<body>
    <nav>
        <h2>Plant Records</h2>
        <a href="admin_dashboard.php">Return to Dashboard</a>
    </nav>
    <?php
    require "private/admin_check.php"; 
    require "private/db_connect.php";
    // Fetching the "Clues" for the sidebar
    $approved_reports = $conn->query("SELECT * FROM plant_reports WHERE status = 'approved' ORDER BY region_id DESC");

    // Handle the official entry into the main 'plants' table
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_official_record'])) {
        $name = $_POST['plant_name'];
        $region = $_POST['region_id'];
        $desc = $_POST['description'];

        $stmt = $conn->prepare("INSERT INTO plants (name, region_id, description) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $name, $region, $desc);
        
        if($stmt->execute()) {
            // Optional: If you provided a report_id, mark that report as 'finalized'
            if(isset($_POST['source_report_id'])) {
                $conn->query("UPDATE plant_reports SET status = 'finalized' WHERE report_id = " . intval($_POST['source_report_id']));
            }
            header("Location: records.php?success=1");
            exit();
        }
    }
    ?>

    <div class="container">
        <div class="main-entry">
            <h3>Add Official Plant Record</h3>
            <form method="POST">
                <div><input type="text" name="plant_name" placeholder="Official Plant Name" required></div>
                <br>
                <div>
                <select name="region_id">
                    <option value="1">Dhaka Division</option>
                </select>
                </div>
                <br>
                <div>
                <textarea name="description" placeholder="Verified Scientific Description"></textarea>
                <br>
                </div>
                <br>
                <div>
                <button type="submit" name="add_official_record">Add to Official Records</button>
                </div>
            </form>
        </div>

        <div class="reference-sidebar">
            <h4>Approved Reports (Reference)</h4>
            <?php if ($approved_reports->num_rows > 0): ?>
                <?php while($report = $approved_reports->fetch_assoc()): ?>
                    <div class="hint-card">
                        <strong><?php echo htmlspecialchars($report['plant_name_suggested']); ?></strong><br>
                        <small>Region ID: <?php echo $report['region_id']; ?></small><br>
                        <p><?php echo htmlspecialchars($report['description']); ?></p>
                        </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No new approved reports to process.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>