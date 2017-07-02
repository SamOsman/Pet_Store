<?php 

$servername = "localhost";
$username = "root";
$password = "";
$dbName = "pet_store_546";



$conn = mysqli_connect($servername, $username, $password, $dbName);

if (!$conn) {
    $connection_test = die("Connection Failed: ".mysqli_connect_error()."<br/>");
} else {
    $connection_test = "Connection is succesful <br/>";
}


?>