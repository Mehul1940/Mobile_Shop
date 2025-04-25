<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

// Check if the supplier_id is provided
if (isset($_POST['supplier_id'])) {
    $supplier_id = $_POST['supplier_id'];

    // Delete query
    $deleteQuery = "DELETE FROM supplier WHERE SUPPLIER_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $supplier_id);

    if ($stmt->execute()) {
        header("Location: /Project/admin/module/supplier.php"); // Redirect to the supplier list page
        exit();
    } else {
        echo "Error deleting supplier: " . $stmt->error;
    }
} else {
    echo "No supplier ID provided!";
    exit();
}

// Close connection
$conn->close();
