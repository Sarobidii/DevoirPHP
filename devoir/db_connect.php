<?php
$servername = "localhost";
$username = "sarobidy";
$password = "azertyuiop"; 
$dbname = "gestion_compte";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
?>


