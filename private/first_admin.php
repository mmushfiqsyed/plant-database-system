<?php
//THIS FILE IS USED TO MAKE THE FIRST DEFAULT ADMIN
include 'db_connect.php';

//Admin data
$adminUser = 'Admin';
$adminEmail = 'admin@plantdb.com';
$adminPass = 'Password444';

//Argon2 hash generation
$options = [
    'memory_cost' => 19456,
    'time_cost'   => 2,
    'threads'     => 1
];
$hashedPass = password_hash($adminPass, PASSWORD_ARGON2ID, $options);

$sqlUser = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sqlUser);
//Attach variables to SQL placeholders
$stmt->bind_param("sss",$adminUser,$adminEmail,$hashedPass);

if($stmt->execute()){
    //Get last created userid for creating our admin table insertion statement:
    $last_id = $conn->insert_id;

    //Promote created user to admin table:
    $sqlAdmin = "INSERT INTO admin (user_id, is_super_admin) VALUES (?, TRUE)";
    //Same setup as before to insert into SQL Statement
    $stmtAdmin = $conn->prepare($sqlAdmin);
    //Attach variables like before:
    $stmtAdmin->bind_param("s", $last_id);
    
    if($stmtAdmin->execute())
    {
        echo "<h2>Success!</h2>";
        echo "<p>User created with ID: ".$last_id."</p>";
        echo "<p>You can now login with the password: <b> $adminPass </b>";
    }
    else{
        echo "Error: ".$conn->error;
    }
}

$conn->close();
?>