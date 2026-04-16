<?php
//These are default data for XAMPP
$servername = "localhost";
$username = "root";
$password = "";

//Tell php which database to connect to.
$dbname = "plant_db";

//Pass the declared variables into a connection function:
try{
    $conn = mysqli_connect($servername, $username, $password, $dbname);
}
catch(mysqli_sql_exception){
    echo "Could not connect! <br>";
}

?>