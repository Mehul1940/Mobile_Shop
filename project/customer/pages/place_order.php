<?php
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: login.php");
    exit();
}

// Database connection
$con = new mysqli('localhost', 'root', '', 'project');
if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Retrieve form data
$customer_id = $_SESSION['CUSTOMER_ID'];
$address_option = $_POST['address_option']; // Get the selected address option
$delivery_address = $_POST['delivery_address']; // New address (if provided)
$cart_total = $_POST['cart_total'];
$cart_items = unserialize(base64_decode($_POST['cart_items']));

// Fetch the customer's profile address if "Profile Address" is selected
if ($address_option === 'profile_address') {
    $sql = "SELECT ADDRESS FROM customer WHERE CUSTOMER_ID = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $customer_data = $result->fetch_assoc();
    $delivery_address = $customer_data['ADDRESS']; // Use the profile address
    $stmt->close();
}

// Get customer's email from database
$sql = "SELECT EMAIL FROM customer WHERE CUSTOMER_ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer_data = $result->fetch_assoc();
$customer_email = $customer_data['EMAIL'];
$stmt->close();

// Insert order into the database (hardcode PAYMENT_METHOD as 'COD')
$sql = "INSERT INTO orders (CUSTOMER_ID, DELIVERY_ADDRESS, PAYMENT_METHOD, TOTAL_AMT) 
        VALUES (?, ?, 'COD', ?)";

$stmt = $con->prepare($sql);
if (!$stmt) {
    die("Error in SQL query: " . $con->error);
}

// MAIL NOTIFICATION TO ADMIN WITH CUSTOMER EMAIL IN REPLY-TO
$to = "bokdemehul870@gmail.com";
$subject = "A new order has been placed";
$message = "A new order has been placed. Check your dashboard";
$headers = "From: familybokade176@gmail.com" . "\r\n" .
           "Reply-To: " . $customer_email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}

$stmt->bind_param("isd", $customer_id, $delivery_address, $cart_total);

if (!$stmt->execute()) {
    die("Error executing query: " . $stmt->error);
}

$order_id = $stmt->insert_id;
$stmt->close();

// Insert order items with offered price
foreach ($cart_items as $item) {
    $sql = "INSERT INTO order_items (ORDER_ID, PRODUCT_ID, VARIANT_ID, QUANTITY, PRICE) 
            VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($sql);

    // Use discounted_price instead of price
    $offered_price = isset($item['discounted_price']) ? $item['discounted_price'] : $item['price'];

    $stmt->bind_param("iiiid", $order_id, $item['product_id'], $item['variant_id'], $item['quantity'], $offered_price);
    $stmt->execute();
    $stmt->close();
}

// Clear the cart
unset($_SESSION['cart']);

// Redirect to confirmation
header("Location: order_confirm.php?order_id=$order_id");
exit();
?>