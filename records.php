<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plant Records</title>
        <style>
            .hidden {display: none;}
            .tab-container { border-bottom:1px solid #ddd;}
            .tab-btn { 
                padding: 10px 20px; 
                cursor: pointer; 
                border: 1px solid #ccc; 
                border-bottom: 1px solid #fff; 
                background: #f4f4f4; 
                margin-right: 5px;
                border-radius: 5px 5px 0 0;
            }
            .tab-btn.active { 
                background: #fff; 
                border-bottom: 2px solid #fff; 
                font-weight: bold; 
                position: relative;
                top: 2px;
            }
            table { width: 100%; border-collapse: collapse; margin-top: 20px; }
            th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
            th { background-color: lightgrey; }
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

    // Fetch Regions for the dropdown
    $regions_query = $conn->query("SELECT * FROM region ORDER BY region_name ASC");

    // Fetch existing Plants for the selection
    $plants_query = $conn->query("SELECT plant_id, plant_name, species FROM plant ORDER BY plant_name ASC");

    // Fetch Approved Reports for the sidebar
    $approved_reports = $conn->query("SELECT plr.*, r.region_name FROM plant_reports plr JOIN region r ON plr.region_id = r.region_id WHERE plr.status = 'approved' ORDER BY plr.report_id DESC");

    if (isset($_GET['clear_report'])) {
        $hid = intval($_GET['clear_report']);
        $conn->query("UPDATE plant_reports SET status = 'finalized' WHERE report_id = $hid");
        header("Location: records.php");
        exit();
    }
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_official_record'])) {
        $area_size = $_POST['area_size'];
        $region_id = $_POST['region_id'];
        $managed_by = $_SESSION['user_id']; // The Admin currently logged in
        $plant_ids = $_POST['plant_ids']; // This will be an array of selected plants

        // Insert into plant_records
        $stmt1 = $conn->prepare("INSERT INTO plant_record (area_size, date_added, managed_by, region_id) VALUES (?, NOW(), ?, ?)");
        $stmt1->bind_param("dii", $area_size, $managed_by, $region_id);
        
        if($stmt1->execute()) {
            $new_record_id = $conn->insert_id; // Get the ID of the record we just made

            // Insert into record_plants (The Junction Table)
            $stmt2 = $conn->prepare("INSERT INTO record_plants (record_id, plant_id) VALUES (?, ?)");
            foreach ($plant_ids as $p_id) {
                $stmt2->bind_param("ii", $new_record_id, $p_id);
                $stmt2->execute();
            }

            if(!empty($_POST['source_report_id'])) {
                $sid = intval($_POST['source_report_id']);
                $conn->query("UPDATE plant_reports SET status = 'finalized' WHERE report_id = $sid");
            }

            header("Location: records.php?success=record_created");
            exit();
        }
    }
    ?>
    <div class="tab-container">
        <button class="tab-btn" onclick="showTab(event, 'view')">View Official Records</button>
        <button class="tab-btn active" onclick="showTab(event, 'add')">Create New Record</button>
    </div>
    <div id="add-section" class="tab-content">
        <div class="container">
            <div class="main-entry">
                <h3>Add Plant Record</h3>
                <form method="POST">
                    <div>
                        <label>Area Size (sq meters):</label><br>
                        <input type="number" step="0.01" name="area_size" required style="width:100%; padding:8px;">
                    </div>
                    <br>
                    <div>
                        <label>Region:</label><br>
                            <select name="region_id" required style="width:100%; padding:8px;">
                            <option value="">-- Select Region --</option>
                            <?php while($reg = $regions_query->fetch_assoc()): ?>
                                <option value="<?php echo $reg['region_id']; ?>"><?php echo htmlspecialchars($reg['region_name']); ?></option>
                            <?php endwhile; ?>
                            </select>
                    </div>
                    <br>
                    <div>
                        <label>Select Plants in this Area:</label><br>
                        <select name="plant_ids[]" multiple required style="width:100%; height:120px; padding:8px;">
                        <?php while($plt = $plants_query->fetch_assoc()): ?>
                            <option value="<?php echo $plt['plant_id']; ?>">
                                <?php echo htmlspecialchars($plt['plant_name'] . " (" . $plt['species'] . ")"); ?>
                            </option>
                        <?php endwhile; ?>
                        </select>
                    </div>
                    <br>
                    <div>
                        <label>Source Report ID (Optional - clears report on submit):</label><br>
                        <input type="number" name="source_report_id" placeholder="e.g. 12" style="width:100%; padding:8px;">
                    </div>
                    <br>
                    <button type="submit" name="add_official_record" style="padding:10px 20px; cursor:pointer;">Finalize</button>
                </form>
            </div>
            <div class="reference-sidebar">
                <h4>Approved Reports</h4>
                <?php if ($approved_reports->num_rows > 0): ?>
                    <?php while($report = $approved_reports->fetch_assoc()): ?>
                        <div class="hint-card">
                            <span style="float:right; color:gray;">#<?php echo $report['report_id']; ?></span>
                            <strong><?php echo htmlspecialchars($report['plant_name_suggested']); ?></strong><br>
                            <small>Region ID: <?php echo $report['region_id']; ?></small><br>
                            <p><?php echo htmlspecialchars($report['description']); ?></p>

                            <hr style="border:0; border-top:1px solid #eee;">
                                <a href="?clear_report=<?php echo $report['report_id']; ?>" 
                                onclick="return confirm('Mark this report as finalized?');"
                                style="box-sizing:content-box; border-radius:2px; background:red; color:white; text-decoration:none; font-size:0.8em; padding: 5px;">
                                Clear from list
                                </a>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>No new approved reports to process.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div id="view-section" class="tab-content hidden">
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
    <script>
        function showTab(evt, tabName) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Remove active class from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show the selected content
            document.getElementById(tabName + '-section').classList.remove('hidden');
            
            // Set the button that was clicked to active
            evt.currentTarget.classList.add('active');
        }
    </script>
</body>
</html>