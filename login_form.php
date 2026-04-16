<!DOCTYPE html>
<html lang="en">
<head>
    <title>Plant Management Login</title>
</head>
<body>
    <h2>Login to Plant Portal</h2>
    <?php
    if (isset($_GET['msg'])) {
        $msg = $_GET['msg'];

        if ($msg == 'registered') {
            $displayMsg = "Success! Account created. Please log in.";
            $color = "#5ff800ff";
        } elseif ($msg == 'loggedout') {
            $displayMsg = "You have been successfully logged out.";
            $color = "#ffc400ff";
        } elseif ($msg == 'error') {
            $displayMsg = "Invalid credentials. Please try again.";
            $color = "#e90013ff";
        }

        if (isset($displayMsg)) {
            echo "<div style='background-color: $color; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid;'>
                    <strong>Notification:</strong> $displayMsg
                </div>";
        }
    }
    ?>
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
</body>
</html>