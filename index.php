<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventCraft</title>
    <link rel="icon" href="images/location.png">
    <link rel="stylesheet" href="style.css">
    <script src="include.js" defer></script>
</head>

<body>


    <?php
    session_start(); // Ensure session is started
    include 'header.php'; ?>



    <div class="img-container">
        <div class="text">QuickMedi – Fast, Easy, and Hassle-Free Doctor Appointments<br> at Your Fingertips!</div>
    </div>






    <?php
    // Database connection
    $servername = "localhost"; // Update with your database server
    $username = "root"; // Update with your database username
    $password = ""; // Update with your database password
    $dbname = "asathias"; // Update with your database name
    
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch data from the database
    $sql = "SELECT id, title, description, img FROM products"; // Add 'id' to your query
    $result = $conn->query($sql);

    // Check if there are results
    if ($result->num_rows > 0) {
        // Start of container
        echo '<div class="container1">';

        // Loop through the results
        while ($row = $result->fetch_assoc()) {
            echo '<div class="card">';
            echo '<img src="images/' . $row['img'] . '" alt="">'; // Assuming 'img' column stores image file name
            echo '<p>' . $row['title'] . '</p>';

            // Button to explore, passing the id in the URL
            echo '<a href="explore.php?id=' . $row['id'] . '"><button>Explore</button></a>';

            echo '</div>';
        }

        // End of container
        echo '</div>';
    } else {
        echo "0 results found";
    }

    // Close the connection
    $conn->close();
    ?>


    <?php
    include 'footer.php';
    ?>

</body>

</html>