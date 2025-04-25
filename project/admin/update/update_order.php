<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Order</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
</head>
<body>
<?php require_once "/xampp/htdocs/Project/config/db.php" ?>
<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Get form data
    $order_id = $_POST['order_id'];
    $customer_id = $_POST['customer_id'];
    $total_amt = $_POST['total_amt'];
    $delivery_address = $_POST['delivery_address'];
    $delivery_status = $_POST['delivery_status'];

    // Update query
    $sql = "UPDATE orders SET CUSTOMER_ID = ?, TOTAL_AMT = ?, DELIVERY_ADDRESS = ?, DELIVERY_STATUS = ? WHERE ORDER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $customer_id, $total_amt, $delivery_address, $delivery_status, $order_id);

    if ($stmt->execute()) {
        // Redirect to order.php after a successful update
        header("Location: /Project/admin/module/order.php");
        exit();
    } else {
        echo "Error updating order: " . $stmt->error;
    }

    // Close statement and connection
    $stmt->close();
    $conn->close();
}
?>
<script src="/Project/assets/js/main.js"></script>
</body>
</html>
