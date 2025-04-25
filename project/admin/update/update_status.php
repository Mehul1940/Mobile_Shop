<?php
require_once "/xampp/htdocs/Project/config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customerId = $_POST['customerId'];
    $status = $_POST['status'];

    // Update the status for the customer
    $sql = "UPDATE customer SET STATUS = ? WHERE CUSTOMER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $status, $customerId);
    if ($stmt->execute()) {
        echo "Status updated successfully";
    } else {
        echo "Error updating status";
    }

    $stmt->close();
    $conn->close();
}
?>
