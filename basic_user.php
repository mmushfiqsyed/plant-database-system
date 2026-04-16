<?php
require "private/db_connect.php";

$basicUser = 'User';
$basicEmail = 'basicuser@plantdb.com';
$basicPass = 'Password123';

//Argon2 hash generation
$options = [
    'memory_cost' => 19456,
    'time_cost'   => 2,
    'threads'     => 1,
];
$hashedPass = password_hash($basicPass, PASSWORD_ARGON2ID, $options);

//Insert the data into USERS table
$sqlUser = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
//Conventional prepare statement setup:
$stmt = $conn->prepare($sqlUser);
//Attach variables to SQL placeholders
$stmt->bind_param("sss",$basicUser,$basicEmail,$hashedPass);

if($stmt->execute())
{
    $last_id = $conn->insert_id;
    echo "<h2>Success!</h2>";
    echo "<p>User created with ID: ".$last_id."</p>";
    echo "<p>You can now login with the password: <b> $basicPass </b>";
}
else{
    echo "Error: ".$conn->error;
}


$conn->close();
?>