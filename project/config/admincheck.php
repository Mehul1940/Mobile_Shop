<?php
// Start the session
session_start();

// Check if the user is logged in as admin
if (!isset($_SESSION['userType']) || $_SESSION['userType'] != 'admin') {
    // If the user is not admin, redirect to login page or error page
    header("Location: index.php"); // Replace with the appropriate login page
    exit();
}
?>