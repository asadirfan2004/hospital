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


        <form class="loginform" method="post" action="signup1.php">

            <div class="title">
                <h1>Sign Up</h1>
            </div>

            <div>
                <h2>Name</h2>
                <input type="text" name="name" required>
            </div>
            <div>
                <h2>Mobile</h2>
                <input type="text" name="mobile" required>
            </div>
            <div>
                <h2>Email</h2>
                <input type="text" name="email" required>
            </div>
            <div>
                <h2>Password</h2>
                <input type="text" name="password" required>
            </div>
            <div>
                <h2>Confirm Password</h2>
                <input type="password" name="confirm_password" required>
            </div>


            <div class="title1">
                <p></p>
                <button type="submit">Submit</button>
            </div>

            <p></p>
            <p>Already Have an Account?<a href="login.php">Login</a></p>
            <p></p>
        </form>
    </div>
    <?php
    include 'footer.php';
    ?>

</body>

</html>