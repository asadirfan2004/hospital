<?php
include 'config.php';

session_destroy(); // Destroy session
header("Location: index.php"); // Redirect to home
exit();
?>
