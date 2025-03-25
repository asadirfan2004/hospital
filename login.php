<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuickMedi</title>
    <link rel="icon" href="images/location.png">
    <link rel="stylesheet" href="style.css">
    <script src="include.js" defer></script>
</head>

<body>


    <?php
    include 'config.php';  // Database connection and session start
    include 'header.php';   // Navigation menu with login/logout
    ?>




    <div class="container3">


        <form class="loginform" method="post" action="">

            <div class="title">
                <h1>User Login</h1>
            </div>
            <div>
                <h2>Mobile or Email</h2>
                <input type="text" name="user" required>
            </div>
            <div>
                <h2>Password</h2>
                <input type="password" name="password" required>
            </div>


            <div class="title1">
                <p></p>
                <button type="submit">Submit</button>
            </div>

            <p></p>

            <p>Don't Have an Account?<a href="signup.php">Sign Up</a></p>
            <p></p>
        </form>
    </div>
    <?php
    include 'footer.php';
    ?>

</body>

</html>



<?php

require 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['user'];
    $password = $_POST['password'];

    // Query to check credentials
    $sql = "SELECT id, name, email, password FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Direct password check (since passwords are not hashed)
        if ($password === $user['password']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_email'] = $user['email'];

            header("Location: index.php"); // Redirect to homepage
            exit();
        } else {
            echo "<script>alert('Invalid password!'); window.location.href='login.php';</script>";
        }
    } else {
        echo "<script>alert('No user found!'); window.location.href='login.php';</script>";
    }
}
?>