<?php
session_start(); // Start session for login system

// Database credentials
$servername = "localhost"; // Change if hosted elsewhere
$username = "root"; // Your DB username
$password = ""; // Your DB password
$dbname = "asathias"; // Your database name

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Database Connection Failed: " . $conn->connect_error);
}
?>