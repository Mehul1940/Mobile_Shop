<?php
session_start();

// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$product_id = $_POST['product_id'];
$user_name = $_POST['user_name'];
$rating = $_POST['rating'];
$comment = $_POST['comment'];

// Fetch product name using product_id
$sql_product = "SELECT PRODUCT_NAME FROM product WHERE PRODUCT_ID = ?";
$stmt_product = $conn->prepare($sql_product);
$stmt_product->bind_param("i", $product_id);
$stmt_product->execute();
$result_product = $stmt_product->get_result();
$product = $result_product->fetch_assoc();
$product_name = $product['PRODUCT_NAME'];

// Insert review into the database
$sql = "INSERT INTO reviews (product_name, user_name, rating, comment) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssis", $product_name, $user_name, $rating, $comment);

if ($stmt->execute()) {
    $_SESSION['message'] = "Review submitted successfully!";
} else {
    $_SESSION['message'] = "Error submitting review. Please try again.";
}

$stmt->close();
$conn->close();

// Redirect back to the product page
header("Location: sproduct.php?id=" . $product_id);
exit();
?>