<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: login.php");
    exit();
}

$order_id = $_GET['order_id'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<?php require_once "../header.php"; ?>

<section id="confirmation" class="text-center py-5">
    <h2>Thank You for Your Order!</h2>
    <p>Your order has been placed successfully. Your order ID is <strong><?= $order_id ?></strong>.</p>
    <a href="/project/customer/pages/index.php" class="btn btn-primary">Continue Shopping</a>
</section>

<?php require_once "../footer.php"; ?>

</body>
</html>