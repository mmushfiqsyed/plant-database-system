<?php
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

?>