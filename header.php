<nav>
    <div>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="">Services</a></li>
            <li><a href="">Contact Us</a></li>

            <?php
            if (isset($_SESSION['user_id'])) {
                echo '<li><a href="orders.php">Appointments</a></li>';
                echo '<li><a href="logout.php">Logout (' . $_SESSION["user_name"] . ')</a></li>';
            } else {
                echo '<li><a href="login.php">Login</a></li>';
            }
            ?>
        </ul>
    </div>
</nav>