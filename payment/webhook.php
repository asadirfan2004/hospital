<?php
require('razorpay-php/Razorpay.php');
include '../config.php'; // Database connection

use Razorpay\Api\Api;

$api_key = '';
$api_secret = '';
$api = new Api($api_key, $api_secret);

// Read Razorpay webhook payload
$input = file_get_contents("php://input");
$event = json_decode($input, true);

if (!$event) {
    die("Invalid Webhook Data");
}

if ($event['event'] == 'payment.authorized') {
    $payment_id = $event['payload']['payment']['entity']['id'];
    $order_id = $event['payload']['payment']['entity']['order_id'];
    $amount_paid = $event['payload']['payment']['entity']['amount'] / 100; // Convert paise to INR

    // Extract booking_id from order_id (assuming format: "order_123")
    preg_match('/order_(\d+)/', $order_id, $matches);
    if (!isset($matches[1])) {
        die("Invalid Booking ID.");
    }
    $booking_id = $matches[1];

    // Update booking status to 'paid' and store transaction ID
    $query = "UPDATE bookings SET status = 'paid', notes = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $payment_id, $booking_id);
    $stmt->execute();
    $stmt->close();

    // Log success
    error_log("Payment Verified: Booking ID $booking_id, Amount ₹$amount_paid, Transaction ID $payment_id");
}
?>