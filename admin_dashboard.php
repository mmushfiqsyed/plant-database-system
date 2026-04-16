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
    <?php else: ?>
        <p><a href="login">Please Login</a></p>
    <?php endif; ?>
    <div>
    <button type="submit">Check reports</button>
    <button type="submit">Modify users</button>
    </div>
</body>
</html>