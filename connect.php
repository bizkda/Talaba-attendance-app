<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$servername = "localhost"; // or 127.0.0.1
$username = "root"; // replace with your MySQL username
$password = ""; // replace with your MySQL password
$dbname = "tahfid"; // replace with your database name

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
} 
?>





