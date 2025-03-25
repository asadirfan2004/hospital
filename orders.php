<?php

include 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access Denied. Please log in.");
}

$user_id = $_SESSION['user_id']; // Get logged-in user's ID

// Fetch appointments only for the logged-in user
$query = "SELECT 
    a.id AS appointment_id, 
    a.appointment_date, 
    a.timing, 
    a.status, 
    a.order_id,
    d.name AS doctor_name,
    a.patient_name, 
    a.patient_contact,
    a.created_at
FROM appointments a
JOIN doctors d ON a.doctor_id = d.doctor_id
WHERE a.user_id = ? 
ORDER BY a.created_at DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'header.php'; ?>

    <div class="container5">
        <h1>My Appointments</h1>

        <?php
        if ($result->num_rows > 0) {
            echo '<div class="container1">';

            while ($row = $result->fetch_assoc()) {
                // Determine card background color based on appointment status
                $is_paid = ($row['status'] === 'Paid');
                $status_text = $is_paid ? "Paid" : "Failed"; // Show "Failed" if not Paid
                $card_class = $is_paid ? 'card paid' : 'card failed';

                echo '<div class="' . $card_class . '">';
                echo '<p><strong>Doctor:</strong> ' . htmlspecialchars($row['doctor_name']) . '</p>';
                echo '<p><strong>Patient Name:</strong> ' . htmlspecialchars($row['patient_name']) . '</p>';
                echo '<p><strong>Contact:</strong> ' . htmlspecialchars($row['patient_contact']) . '</p>';
                echo '<p><strong>Appointment Date:</strong> ' . htmlspecialchars($row['appointment_date']) . '</p>';
                echo '<p><strong>Timing:</strong> ' . htmlspecialchars($row['timing']) . '</p>';
                echo '<p><strong>Status:</strong> ' . htmlspecialchars($status_text) . '</p>';

                // Show Order ID only if the status is Paid
                if ($is_paid) {
                    echo '<p><strong>Order ID:</strong> ' . htmlspecialchars($row['order_id']) . '</p>';
                }

                echo '</div>';
            }

            echo '</div>';
        } else {
            echo "<p>No appointments found.</p>";
        }

        $conn->close();
        ?>

    </div>

    <?php include 'footer.php'; ?>

    <style>
        .container {
            width: 90%;
            margin: 20px auto;
            text-align: center;
        }

        .container1 {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin: 15px;
            width: 300px;
            text-align: center;
            border: 2px solid #ccc;
            transition: background-color 0.3s ease-in-out;
        }

        .card p {
            font-size: 16px;
            font-weight: bold;
            color: black !important;
        }

        /* Status-based card background colors */
        .paid {
            background-color: #d4edda;
            /* Light green */
            border-color: green;
        }

        .failed {
            background-color: #f8d7da;
            /* Light red */
            border-color: red;
        }
    </style>

</body>

</html>