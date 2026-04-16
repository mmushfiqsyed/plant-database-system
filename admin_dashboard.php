<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
</head>
<body>
    <?php 
    include "private/db_connect.php";
    include "private/admin_check.php";
    ?>
    <h1>Welcome to the Bangladesh Plant Portal</h1>

    <?php if (isset($_SESSION['username'])): ?>
        <p>Currently logged in as: <?php echo $_SESSION['username']; ?></p>
    <?php endif; ?>
    <nav>
    <h3>M</h3>
    <ul>
        <li><a href="/audit_log.php">Audit Logs</a></li>
        <li><a href="/reports.php">Check Reports</a></li>
        <li><a href="/records.php">Check Records</a></li>
        <li><a href="/admin_register.php">Add Admin or SuperAdmin</a></li>
    <ul>
    </nav>
</body>
</html>