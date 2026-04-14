<?php
//THIS FILE IS USED TO MAKE THE FIRST DEFAULT ADMIN
//These are default data for XAMPP
$servername = "localhost";
$username = "root";
$password = "";

//Tell php which database to connect to.
$dbname = "plant_db";

//Pass the declared variables into a connection function:
$conn = mysqli_connect($servername, $username, $password, $dbname);

//Check connection
if(!$conn)
{
    die("Connection failed: ".mysqli_connect_error());
}

//Admin data
$adminUser = 'Admin';
$adminEmail = 'admin@plantdb.com';
$adminPass = 'Password444';

//Argon2 hash generation
$options = [
    'memory_cost' => 19456,
    'time_cost'   => 2,
    'threads'     => 1,
];
$hashedPass = password_hash($adminPass, PASSWORD_ARGON2ID, $options);

//Insert the data into USERS table
$sqlUser = "INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)";
//Conventional prepare statement setup:
$stmt = $conn->prepare($sqlUser);
//Attach variables to SQL placeholders
$stmt->bind_param("sss",$adminUser,$adminEmail,$adminPass);

if($stmt->execute()){
    //Get last created userid for creating our admin table insertion statement:
    $last_id = $conn->insert_id;

    //Promote created user to admin table:
    $sqlAdmin = "INSERT INTO admin (user_id, is_super_admin) VALUES (?, TRUE)"
    //Same setup as before to insert into SQL Statement
    $stmtAdmin = $conn->prepare($sqlAdmin);
    //Attach variables like before:
    $stmtAdmin->bind_param("s", $last_id);
    
    if($stmtAdmin->execute())
    {
        echo "<h2>Success!</h2>";
        echo "<p>Admin created with ID: ".$last_id."</p>";
        echo "<p>You can now login with the password: <b> $adminPass </b>";
    }
    else{
        echo "Error: ".$conn->error;
    }
}

$conn->close();
?>