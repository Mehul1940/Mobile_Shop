<?php
require_once "/xampp/htdocs/Project/config/db.php";

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplierID = $_POST['SUPPLIER_ID'] ?? null;
    $supplierName = $_POST['SUPPLIER_NAME'] ?? null;
    $email = $_POST['EMAIL'] ?? null;
    $phone = $_POST['PHONE'] ?? null;
    $address = $_POST['ADDRESS'] ?? null;

    // Validate inputs
    if ($supplierID && $supplierName && $email && $phone && $address) {
        $query = "UPDATE supplier 
                  SET SUPPLIER_NAME = ?, EMAIL = ?, PHONE = ?, ADDRESS = ? 
                  WHERE SUPPLIER_ID = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssssi", $supplierName, $email, $phone, $address, $supplierID);

            if ($stmt->execute()) {
                // Success: Redirect to suppliers page
                header("Location: /Project/admin/module/supplier.php");
                exit();
            } else {
                $error = "Failed to update supplier. Please try again.";
            }
            $stmt->close();
        } else {
            $error = "Failed to prepare update query.";
        }
    } else {
        $error = "All fields are required.";
    }

    // Redirect back with error message
    header("Location: /Project/admin/module/supplier.php");
    exit();
}
?>
