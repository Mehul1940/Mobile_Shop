<?php
// update_status.php

// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Database connection
$con = mysqli_connect("localhost", "root", "", "project") or die("Couldn't connect");

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $bookingId = $_POST['booking_id'];
    $action = $_POST['action'];

    if ($action === 'Reject') {
        // Update the status to "Rejected" and explicitly set date and time to NULL
        $stmt = $con->prepare("UPDATE service_bookings SET status = 'Rejected', booking_date = NULL, booking_time = NULL WHERE booking_id = ?");
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $stmt->close();
    } elseif ($action === 'Accept') {
        // Validate and update the status to "Accepted" with new date and time
        if (isset($_POST['new_booking_date']) && isset($_POST['new_booking_time'])) {
            $newDate = $_POST['new_booking_date'];
            $newTime = $_POST['new_booking_time'];

            // Validate date and time
            $currentDate = date("Y-m-d");
            $selectedDate = new DateTime($newDate);
            $dayOfWeek = $selectedDate->format("w"); // 0 = Sunday, 1 = Monday, etc.

            if ($newDate < $currentDate) {
                die("Error: Past dates are not allowed.");
            }
            if ($dayOfWeek == 0) {
                die("Error: Sundays are not allowed.");
            }
            if ($newTime < "10:00" || $newTime > "21:00") {
                die("Error: Time must be between 10:00 AM and 9:00 PM.");
            }

            // Update the status, date, and time
            $stmt = $con->prepare("UPDATE service_bookings SET status = 'Accepted', booking_date = ?, booking_time = ? WHERE booking_id = ?");
            $stmt->bind_param("ssi", $newDate, $newTime, $bookingId);
            $stmt->execute();
            $stmt->close();
        } else {
            die("Error: Date and time are required.");
        }
    }

    // Redirect back to the view_bookings.php page
    header("Location: view_booking.php");
    exit();
}
?>