<?php
session_start();
// If the session doesn't exist OR the role is not 'user', kick them out
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'user') {
    header("Location: login_form.php?error=unauthorized");
    exit();
}
?>