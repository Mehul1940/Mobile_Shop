<?php
session_start();
require_once "/xampp/htdocs/Project/config/db.php"; // Inc
$id=  $_SESSION['CUSTOMER_ID'];
// Destroy the customer session
if (isset($id)) {
    // Update customer status to inactive
    $updateQuery = "UPDATE customer SET STATUS = 'inactive' WHERE customer_id = $id";
    $updateStmt = $conn->prepare($updateQuery);
    $updateStmt->execute();

    unset($_SESSION['customer_id']);
    session_destroy();
}
    
// Redirect to the customer login page
header("Location: /project/customer/login.php");
exit();
