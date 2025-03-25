<?php
// Enable error reporting
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Ensure correct database connection

// Check if appointment_id and order_id are passed
if (!isset($_GET['appointment_id']) || !isset($_GET['order_id'])) {
    die("Invalid request. Appointment ID or Order ID missing.");
}

$appointment_id = $_GET['appointment_id'];
$order_id = $_GET['order_id'];

// Update the appointment status to 'Paid' and store order_id
$update_query = "UPDATE appointments SET status = 'Paid', order_id = ? WHERE id = ?";
$stmt = $conn->prepare($update_query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("si", $order_id, $appointment_id);
$stmt->execute();
$stmt->close();

// Fetch updated appointment details
$query = "SELECT a.id, a.user_id, a.patient_name, a.patient_contact, d.name AS doctor_name, a.appointment_date, a.timing, a.status, a.order_id
          FROM appointments a
          JOIN doctors d ON a.doctor_id = d.doctor_id
          WHERE a.id = ?";

$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("i", $appointment_id);
$stmt->execute();
$result = $stmt->get_result();
$appointment = $result->fetch_assoc();
$stmt->close();
$conn->close();

if (!$appointment) {
    die("Appointment details not found.");
}

// Fixed payment amount
$total_amount = 500;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Success</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            text-align: center;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
            padding: 40px;
            max-width: 400px;
            width: 100%;
        }

        .icon {
            color: #4caf50;
            font-size: 60px;
            margin-bottom: 20px;
        }

        h1 {
            color: #4caf50;
            margin-bottom: 20px;
        }

        p {
            color: #555;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .button {
            background-color: #4caf50;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }

        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1>Payment Successful!</h1>
        <p><strong>Appointment ID:</strong> <?php echo htmlspecialchars($appointment['id']); ?></p>
        <p><strong>Patient Name:</strong> <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
        <p><strong>Contact:</strong> <?php echo htmlspecialchars($appointment['patient_contact']); ?></p>
        <p><strong>Doctor:</strong> <?php echo htmlspecialchars($appointment['doctor_name']); ?></p>
        <p><strong>Appointment Date:</strong> <?php echo htmlspecialchars($appointment['appointment_date']); ?></p>
        <p><strong>Timing:</strong> <?php echo htmlspecialchars($appointment['timing']); ?></p>
        <p><strong>Amount Paid:</strong> ₹<?php echo htmlspecialchars($total_amount); ?></p>
        <p><strong>Order ID:</strong> <?php echo htmlspecialchars($appointment['order_id']); ?></p>
        <p><strong>Status:</strong> <?php echo htmlspecialchars($appointment['status']); ?></p>
        <a href="../index.php" class="button">Back to Home</a>
    </div>
</body>

</html>