<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Check if ORDER_ID is passed in the URL
if (!isset($_GET['order_id']) || empty($_GET['order_id'])) {
    die("Order ID is missing in the URL.");
}

$order_id = intval($_GET['order_id']);

// Fetch order details with product names from the database
$sql = "SELECT order_items.ORDER_ITEM_ID, order_items.ORDER_ID, order_items.PRODUCT_ID, product.PRODUCT_NAME, 
               order_items.QUANTITY, order_items.PRICE
        FROM order_items
        LEFT JOIN product ON order_items.PRODUCT_ID = product.PRODUCT_ID
        WHERE order_items.ORDER_ID  = ?";

// Debugging: Check if the query can be prepared
if (!$stmt = $conn->prepare($sql)) {
    die("Query Preparation Failed: " . $conn->error);
}

$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

// Debugging: Check if the result is valid
if (!$result) {
    die("Query Execution Failed: " . $conn->error);
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="stylesheet" href="/project/assets/css/styles.css">
    <link rel="stylesheet" href="/project/assets/css/detail.css">
</head>

<body>
    <!-- Include Navigation -->
    <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

    <div class="main">
        <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>

        <div class="container">
            <h2>Order Details for Order ID: <?php echo htmlspecialchars($order_id); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Item ID</th>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo '<tr>
                            <td>' . htmlspecialchars($row["ORDER_ITEM_ID"]) . '</td>
                            <td>' . htmlspecialchars($row["ORDER_ID"]) . '</td>
                            <td>' . htmlspecialchars($row["PRODUCT_NAME"] ?? 'Unknown') . '</td>
                            <td>' . htmlspecialchars($row["QUANTITY"]) . '</td>
                            <td>' . htmlspecialchars($row["PRICE"]) . '</td>
                        </tr>';
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align:center;">No order items found for this order.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
            <a href="/Project/admin/module/order.php">Back to Orders</a>
        </div>
    </div>
</body>

</html>

<?php
$stmt->close();
$conn->close();
?>