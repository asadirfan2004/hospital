<?php
include 'config.php'; // Database connection & session start
include 'header.php'; // Navigation bar

// Ensure user is logged in for booking
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=booking_page.php");
    exit();
}

// Fetch all unique specializations
$specializations = [];
$sql = "SELECT DISTINCT specialization FROM doctors";
$result = $conn->query($sql);
while ($row = $result->fetch_assoc()) {
    $specializations[] = $row['specialization'];
}

// Handle AJAX Requests for Doctors
if (isset($_POST['specialization']) && isset($_POST['day'])) {
    $specialization = $_POST['specialization'];
    $day = $_POST['day'];

    // Fix: Use LIKE for partial matching instead of FIND_IN_SET
    $stmt = $conn->prepare("SELECT doctor_id, name FROM doctors WHERE specialization = ? AND available_days LIKE CONCAT('%', ?, '%') AND status = 'Active'");
    $stmt->bind_param("ss", $specialization, $day);
    $stmt->execute();
    $result = $stmt->get_result();
    
    echo "<option value=''>Select a Doctor</option>";
    while ($row = $result->fetch_assoc()) {
        echo "<option value='" . $row['doctor_id'] . "'>" . $row['name'] . "</option>";
    }
    $stmt->close();
    exit();
}

// Handle AJAX Requests for Timings
if (isset($_POST['doctor_id'])) {
    $doctor_id = $_POST['doctor_id'];

    $stmt = $conn->prepare("SELECT available_time FROM doctors WHERE doctor_id = ?");
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $stmt->bind_result($available_time);
    $stmt->fetch();
    
    echo "<option value='" . $available_time . "'>" . $available_time . "</option>";
    $stmt->close();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Appointment - Hospital</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container5">
    <form class="joinus" name="bookingForm" method="POST" action="process_booking.php">
        <div class="title">
            <h1>Book an Appointment</h1>
        </div>

        <input type="hidden" name="user_id" value="<?php echo $_SESSION['user_id']; ?>">

        <div class="name">
            <h2>Select Specialization</h2>
            <select id="specialization" name="specialization" required>
                <option value="">Select Specialization</option>
                <?php foreach ($specializations as $spec) { ?>
                    <option value="<?php echo $spec; ?>"><?php echo $spec; ?></option>
                <?php } ?>
            </select>
        </div>

        <div class="name">
            <h2>Select Date</h2>
            <input type="date" id="eventDate" name="event_date" required>
        </div>

        <div class="name">
            <h2>Select Doctor</h2>
            <select id="doctor" name="doctor_id" required>
                <option value="">Select a Doctor</option>
            </select>
        </div>

        <div class="name">
            <h2>Available Timings</h2>
            <select id="timing" name="timing" required>
                <option value="">Select Time</option>
            </select>
        </div>

        <div class="title1">
            <button type="submit">Book Now</button>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>

<script>
// Restrict date to today or later
let today = new Date().toISOString().split('T')[0];
document.getElementById("eventDate").setAttribute("min", today);

$(document).ready(function () {
    // Fetch doctors based on specialization and selected date
    $('#specialization, #eventDate').on('change', function () {
        let specialization = $('#specialization').val();
        let selectedDate = new Date($('#eventDate').val());
        let dayOfWeek = selectedDate.toLocaleString('en-us', { weekday: 'long' });

        if (specialization && $('#eventDate').val()) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { specialization: specialization, day: dayOfWeek },
                success: function (response) {
                    $('#doctor').html(response);
                    $('#timing').html('<option value="">Select Time</option>'); // Reset timing
                }
            });
        }
    });

    // Fetch available timings based on selected doctor
    $('#doctor').on('change', function () {
        let doctorId = $(this).val();
        if (doctorId) {
            $.ajax({
                url: '',
                type: 'POST',
                data: { doctor_id: doctorId },
                success: function (response) {
                    $('#timing').html(response);
                }
            });
        }
    });
});
</script>
</body>
</html>
