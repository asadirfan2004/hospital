<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $mobile = $_POST['mobile'];
    $mail = $_POST['mail'];
    $date = $_POST['date'];
    $event = $_POST['options'];
    $description = $_POST['description'];

    // Database connection
    $conn = new mysqli("localhost", "event", "event@1234", "eventcraft");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO enquiries (name, mobile, mail, appointment_date, event_type, description) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $mobile, $mail, $date, $event, $description);

   // Execute the statement
    if ($stmt->execute()) {
        echo "<script>
                alert('Enquiry Submitted Successfully!');
                window.location.href = 'index.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: " . $stmt->error . "');
                window.location.href = 'index.php';
              </script>";
    }

    // Close connections
    $stmt->close();
    $conn->close();
}
?>