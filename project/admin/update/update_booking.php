<?php
// Include required files for database connection and admin authentication
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect data from the POST request
    $booking_id = $_POST['booking_id'] ?? null;
    $estimate_id = $_POST['estimate_id'] ?? null;
    $booking_date = $_POST['booking_date'] ?? null;
    $address = $_POST['address'] ?? null;
    $status = $_POST['status'] ?? null;
    $pickup_status = $_POST['pickup_status'] ?? null;
    $delivery_status = $_POST['delivery_status'] ?? null;

    // Validate that all required fields are provided
    if (!$booking_id || !$booking_date || !$address || !$status || !$pickup_status || !$delivery_status) {
        echo "<script>
            alert('All fields are required!');
            window.location.href = '/Project/admin/module/booking.php';
        </script>";
        exit;
    }

    // Prepare the SQL query to update the booking
    $update_sql = "UPDATE booking SET 
        ESTIMATE_ID = ?,
        BOOKING_DATE = ?,
        ADDRESS = ?,
        STATUS = ?,
        PICKUP_STATUS = ?,
        DELIVERY_STATUS = ?
        WHERE BOOKING_ID = ?";

    if ($stmt = $conn->prepare($update_sql)) {
        // Bind the parameters to the SQL query
        $stmt->bind_param(
            "isssssi",
            $estimate_id,
            $booking_date,
            $address,
            $status,
            $pickup_status,
            $delivery_status,
            $booking_id
        );

        // Execute the statement and check if the update was successful
        if ($stmt->execute()) {
            // Redirect to booking.php after a successful update
            header("Location: /Project/admin/module/booking.php");
            exit();
        } else {
            echo "Error updating Booking: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing the update query: " . $conn->error;
    }

    // Close the database connection
    $conn->close();
} else {
    // If accessed directly without form submission, redirect back to booking page
    header("Location: /Project/admin/module/booking.php");
    exit;
}
?>
