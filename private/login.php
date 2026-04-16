<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    //Prepared statement to prevent SQL Injection
    $stmt = $connection->prepare("SELECT user_id, password_hash FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        //Verification function
        if (password_verify($password, $row['password_hash'])) {
            
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['username'] = $username;

            //Check if this user is an Admin
            $admin_stmt = $connection->prepare("SELECT is_super_admin FROM admin WHERE user_id = ?");
            $admin_stmt->bind_param("i", $row['user_id']);
            $admin_stmt->execute();
            $admin_res = $admin_stmt->get_result();

            if ($admin_res->num_rows > 0) {
                $_SESSION['role'] = 'admin';
                header("Location: /plantdb/admin_dashboard.php"); // Redirect to Admin area
            } else {
                $_SESSION['role'] = 'user';
                header("Location: /plantdb/index.php"); // Redirect to home
            }
            exit();
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that username.";
    }
}
?>