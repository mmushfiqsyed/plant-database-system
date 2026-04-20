<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <style>
        li{ text-decoration: none;}
        .header{ display: flex; box-sizing: border-box; justify-content: space-between; align-items: center;}
        .sign-out{ text-decoration:none; color:red; border: 1px solid #000; padding:12px;}
    </style>
</head>
<body>
    <?php 
    include "private/db_connect.php";
    include "private/login_check.php";
    ?>
    <div class="header">
        <h1>Welcome to the User Dashboard!</h1>
        <div>
            <a class="sign-out" href=login_form.php>Sign Out</a>
        </div>
    </div>
    <p>Currently logged in as: <?php echo $_SESSION['username']?> </p>
    <h3>Options:</h3>
    <nav>
        <ul>
            <li><a href="your-report.php">Your reports</a></li>
            <li><a href="add-report.php">Add report</a></li>
            <li><a href="browse-records.php">Browse records</a></li>
        </ul>
    </nav>
    <?php $conn->close(); ?>
</body>
</html>