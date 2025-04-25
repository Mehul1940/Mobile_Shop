<?php
session_start();

// Database connection
$con = new mysqli('localhost', 'root', '', 'project');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['CUSTOMER_ID'];

// Get the most recent order for the customer
$sql = "SELECT o.ORDER_ID, o.TOTAL_AMT, o.DELIVERY_ADDRESS, o.ORDER_DATE, o.DELIVERY_STATUS 
        FROM orders o 
        WHERE o.CUSTOMER_ID = ? 
        ORDER BY o.ORDER_DATE DESC LIMIT 1";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$order_result = $stmt->get_result();
$order = $order_result->fetch_assoc();
$stmt->close();

// Get the order items for the most recent order
$order_items = [];
$sql = "SELECT oi.PRODUCT_ID, p.PRODUCT_NAME, oi.QUANTITY, oi.PRICE 
        FROM order_items oi
        JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
        WHERE oi.ORDER_ID = ?";

$stmt = $con->prepare($sql);
$stmt->bind_param("i", $order['ORDER_ID']);
$stmt->execute();
$order_items_result = $stmt->get_result();

while ($item = $order_items_result->fetch_assoc()) {
    $order_items[] = $item;
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #order-success {
            background-color: white;
            margin: 30px auto;
            padding: 30px;
            max-width: 900px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #order-success h2 { text-align: center; font-size: 2rem; margin-bottom: 20px; }
        #order-success table { width: 100%; margin-bottom: 20px; }
        #order-success table th, #order-success table td { padding: 10px; text-align: left; }
        .order-info, .order-items { margin-bottom: 20px; }
        .order-items {
                      margin-bottom: 20px;
                      font-size:15px;
                        }
        .text-center a{
            font-size:17px;
            background-color:midnightblue;
        }
    </style>
</head>
<body>

<?php require_once "../header.php"; ?>

<section id="order-success">
    <h2>Order Confirmation</h2>

    <div class="order-info">
        <h4>Order #<?= $order['ORDER_ID'] ?></h4>
        <p><strong>Total Amount:</strong> ₹<?= number_format($order['TOTAL_AMT'], 2) ?></p>
        <p><strong>Delivery Address:</strong> <?= htmlspecialchars($order['DELIVERY_ADDRESS']) ?></p>
        <p><strong>Order Date:</strong> <?= date("F j, Y, g:i a", strtotime($order['ORDER_DATE'])) ?></p>
        <p><strong>Delivery Status:</strong> <?= htmlspecialchars($order['DELIVERY_STATUS']) ?></p>
    </div>

    <div class="order-items">
        <h4>Items in Your Order</h4>
        <table border="1">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['PRODUCT_NAME']) ?></td>
                        <td>₹<?= number_format($item['PRICE'], 2) ?></td>
                        <td><?= $item['QUANTITY'] ?></td>
                        <td>₹<?= number_format($item['PRICE'] * $item['QUANTITY'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="text-center">
        <a href="index.php" class="btn btn-primary">Continue Shopping</a>
    </div>
</section>

<?php require_once "../footer.php"; ?>

</body>
</html>
