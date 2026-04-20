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
    <br>
    <p>Alternatively <a href="register_form.php">sign up here</a></p>

    <?php if (isset($_GET['msg'])): ?>
        <script>
            const messages = {
                'registered': 'Registration successful! You can now log in.',
                'error': 'Invalid username or password.',
            };

            const msgKey = "<?php echo htmlspecialchars($_GET['msg']); ?>";

            if (messages[msgKey]) {
                alert(messages[msgKey]);
            }
            
            const cleanUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.replaceState({}, document.title, cleanUrl);
        </script>
    <?php endif; ?>
</body>
</html>