<!DOCTYPE html>
<html lang="en">
<head>
    <title>Plant Management Login</title>
</head>
<body>
    <h2>Login to Plant Portal</h2>
    <form action="/plantdb/private/login.php" method="POST">
        <div>
            <label>Username:</label>
            <input type="text" name="username" required>
        </div>
        <br>
        <div>
            <label>Password:</label>
            <input type="password" name="password" required>
        </div>
        <br>
        <button type="submit">Login</button>
    </form>
</body>
</html>