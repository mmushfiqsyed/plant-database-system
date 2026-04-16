<?php
session_start();
include 'db_connect.php';

if($_SERVER["REQUEST_METHOD"]=="POST"){
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $checkEmail = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $checkEmail->bind_param('s',$email);
    $checkEmail->execute();
    $chkEmailres=$checkEmail->get_result();

    if($chkEmailres->num_rows >0){
        die("This email is already registered!");
    }

    $checkUser = $conn->prepare("SELECT username FROM users WHERE username = ?");
    $checkUser->bind_param('s',$username);
    $checkUser->execute();
    $chkUserres=$checkUser->get_result();

    if($chkUserres->num_rows >0){
        die("A user under this username is already registered!");
    }
    
    //Argon2 hash generation
    $options = ['memory_cost' => 19456, 'time_cost' => 2,'threads' => 1];
    $hashedPass = password_hash($password, PASSWORD_ARGON2ID, $options);

    $stmt = $conn->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
    //Attach variables to SQL placeholders
    $stmt->bind_param("sss",$username,$email,$password);

    if($stmt->execute())
    {
        // Registration success! Send them to login.
        header("Location: /plantdb/login_form.php?msg=registered");
        exit();
    }
    else{
        echo "Error: ".$conn->error;
    }
}

$conn->close();
?>