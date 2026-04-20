<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <style>
        li { text-decoration: none;}
        .header{ display: flex; box-sizing: border-box; justify-content: space-between; align-items: center;}
        .sign-out{ text-decoration:none; color:red; border: 1px solid #000; padding:12px;}
    </style>
</head>
<body>
    <?php 
    include "private/db_connect.php";
    include "private/admin_check.php";
    ?>
    <div class="header">
        <h1>Welcome to the Bangladesh Plant Portal</h1>
        <div>
            <a class="sign-out" href=login_form.php>Sign Out</a>
        </div>
    </div>
    <?php if (isset($_SESSION['username'])): ?>
        <p>Currently logged in as: <?php echo $_SESSION['username']; ?></p>
    <?php endif; ?>
    <nav>
    <h3>Options:</h3>
    <ul>
        <li><a href="audit_log.php">Audit Logs</a></li>
        <li><a href="reports.php">Check Reports</a></li>
        <li><a href="records.php">Check Records</a></li>
        <li><a href="admin_register.php">Add Admin or SuperAdmin</a></li>
        <li><a href="add-plants.php">Add Plants</a></li>
    <ul>
    </nav>
    <?php $conn->close(); ?>
</body>
</html>