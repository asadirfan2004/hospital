<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST["name"]);
    $mobile = trim($_POST["mobile"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $confirm_password = trim($_POST["confirm_password"]);

    // Check if passwords match
    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.history.back();</script>";
        exit;
    }



    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO users (name, phone_number, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $mobile, $email, $password);

    if ($stmt->execute()) {
        echo "<script>alert('Signup successful! Redirecting to home.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error: Could not register.'); window.location.href='signup.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
