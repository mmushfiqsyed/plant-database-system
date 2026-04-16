<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
</head>
<body>
    <h2>Registration Form</h2>
    <?php
    include "private/db_connect.php";
    ?>
    <form action="/plantdb/private/register.php" method=POST>
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <br>
        <div>
            <label>Email:</label>
            <input type="text" name="email" required>
        </div>
        <br>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Sign Up</button>
    </form>
</body>
</html>