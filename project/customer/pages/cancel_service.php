<?php
session_start();
require_once "../header.php";
$con = new mysqli('localhost', 'root', '', 'project');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: /login.php");
    exit;
}

$service_id = $_GET['service_id'];
$customer_id = $_SESSION['CUSTOMER_ID'];

// Update the service booking status to 'Canceled'
$query = "UPDATE service_bookings SET status = 'Canceled' WHERE booking_id = ? AND customer_email = (SELECT email FROM customer WHERE CUSTOMER_ID = ?)";
$stmt = $con->prepare($query);
if (!$stmt) {
    die("Error preparing the query: " . $con->error);
}

$stmt->bind_param("ii", $service_id, $customer_id);
$stmt->execute();

// Insert a notification for the admin
$notification_query = "INSERT INTO notifications (customer_id, service_id, message) VALUES (?, ?, ?)";
$notification_stmt = $con->prepare($notification_query);
if (!$notification_stmt) {
    die("Error preparing the notification query: " . $con->error);
}

$notification_message = "Service booking ID: $service_id has been canceled by the customer.";
$notification_stmt->bind_param("iis", $customer_id, $service_id, $notification_message);
$notification_stmt->execute();

// Redirect back to the services page
header("Location: services.php?status=canceled");
exit;
?>
