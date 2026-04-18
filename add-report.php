<?php 
    include "private/db_connect.php";
    include "private/login_check.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Report</title>
    <style>
        .add-report{ 
            box-sizing: border-box; 
            display:flex; 
            justify-content:space-between; 
            background:#f9f9f9;
            border: 2px solid #ddd;
            border-radius:20px;
            inline-size: fit-content;
            padding: 20px 50px 20px 30px;
        }
        .form-element{ padding-block-start: 20px;}
        nav { display:flex; box-sizing: border-box; justify-content: space-between; align-items:center;}
        nav a{ text-decoration:none; border: 1px solid #000; padding:12px;}
    </style>
</head>
<body>
    <?php $regions_query = $conn->query("SELECT * FROM region ORDER BY region_name ASC");?>
    <nav>
        <h2>Add Report</h2>
        <a href="index.php">Return to Dashboard</a>
    </nav>
    <div class="add-report">
        <form method="POST">
            <h3>Add New Report</h3>
            <div class="form-element">
                <label>Plant name: </label>
                    <input name="name" type="text">
            </div>
            <br>
            <div class="form-element">
                <label>Plant description:<label><br>
                    <textarea name="description" rows="10" cols="30"></textarea>
            </div>
            <br>
            <div class="form-element">
                <label>Location:</label>
                    <select name="region_id" required>
                        <option value="">-- Select Region --</option>
                        <?php while($reg = $regions_query->fetch_assoc()): ?>
                            <option value="<?php echo $reg['region_id']; ?>"><?php echo htmlspecialchars($reg['region_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
            </div>
            <br>
            <button type="submit" name="add_report" style="padding:10px 20px; cursor:pointer;">Submit</button>
        </form>
    </div>
</body>
</html>