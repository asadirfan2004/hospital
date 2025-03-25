<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../config.php'; // Ensure correct path

if (!isset($_GET['appointment_id'])) {
    die("Appointment ID is missing.");
}

$appointment_id = $_GET['appointment_id'];

// Always set the amount to ₹500
$total_cost = 500;
$amount_in_paise = $total_cost * 100;

// Include the Razorpay PHP library
require('razorpay-php/Razorpay.php');
use Razorpay\Api\Api;

$api_key = '';
$api_secret = '';
$api = new Api($api_key, $api_secret);

// Create a Razorpay order
$order = $api->order->create([
    'amount' => $amount_in_paise,
    'currency' => 'INR',
    'receipt' => 'order_' . $appointment_id
]);

$order_id = $order->id;

// Set callback URL
$callback_url = "success.php?appointment_id=" . $appointment_id . "&order_id=" . $order_id;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Processing Payment</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>

<body>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var options = {
                key: "<?php echo $api_key; ?>",
                amount: <?php echo $order->amount; ?>,
                currency: "<?php echo $order->currency; ?>",
                name: "Hospital",
                description: "Payment for Appointment ID: <?php echo $appointment_id; ?>",
                image: "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
                order_id: "<?php echo $order_id; ?>",
                theme: {
                    "color": "#738276"
                },
                handler: function (response) {
                    window.location.href = "<?php echo $callback_url; ?>";
                },
                modal: {
                    escape: false,
                    ondismiss: function () {
                        window.location.href = "../index.php"; // Redirect on cancel
                    }
                }
            };

            var rzp = new Razorpay(options);
            rzp.open();
        });
    </script>
</body>

</html>