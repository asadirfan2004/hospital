<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config.php'; // Database connection

session_start(); // Ensure session is started

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

$user_id = $_SESSION['user_id'];

// Fetch user details from the users table
$sql = "SELECT name, phone_number FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("Error: User details not found.");
}

$patient_name = $user['name'];
$patient_contact = $user['phone_number']; // Updated to match your column

// Fetch form data
$doctor_id = isset($_POST['doctor_id']) ? intval($_POST['doctor_id']) : null;
$appointment_date = isset($_POST['event_date']) ? $_POST['event_date'] : null;
$timing = isset($_POST['timing']) ? $_POST['timing'] : null;

// Validate required fields
if (!$doctor_id || !$appointment_date || !$timing) {
    die("Error: Missing required fields.");
}

// Insert booking into appointment table
$sql = "INSERT INTO appointments (user_id, patient_name, patient_contact, doctor_id, appointment_date, timing, status, created_at)
        VALUES (?, ?, ?, ?, ?, ?, 'Pending', NOW())";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ississ", $user_id, $patient_name, $patient_contact, $doctor_id, $appointment_date, $timing);

if ($stmt->execute()) {
    $appointment_id = $stmt->insert_id; // Get the last inserted appointment ID
    header("Location: payment/index.php?appointment_id=" . $appointment_id);
    exit();
} else {
    echo "Error: " . $stmt->error;
}
?>