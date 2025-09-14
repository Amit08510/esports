<?php
$servername = "localhost";   // or 127.0.0.1
$username   = "root";        // default for XAMPP
$password   = "";            // default is empty in XAMPP
$database   = "database1";  // change this to your DB name

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
