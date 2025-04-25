<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

// Validate the Product ID from the query string
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid Product ID");
}

$product_id = intval($_GET['id']);

// Fetch product details
$product_query = "SELECT * FROM product WHERE PRODUCT_ID = ?";
$product_stmt = $conn->prepare($product_query);
if (!$product_stmt) {
    die("Product query preparation failed: " . $conn->error);
}
$product_stmt->bind_param("i", $product_id);
$product_stmt->execute();
$product_result = $product_stmt->get_result();
$product = $product_result->fetch_assoc();

if (!$product) {
    die("Product not found");
}

// Fetch reviews for the product
$review_query = "SELECT * FROM product_reviews WHERE id = ? ORDER BY rating ASC";
$review_stmt = $conn->prepare($review_query);
if (!$review_stmt) {
    die("Review query preparation failed: " . $conn->error);
}
$review_stmt->bind_param("i", $product_id);
$review_stmt->execute();
$reviews_result = $review_stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
    <link rel="stylesheet" href="/Project/assets/css/preview.css">
</head>

<body>
    <!-- Include Navigation -->
    <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

    <div class="main">
        <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>
        <div class="container">
            <h1>Product Reviews</h1>

            <!-- Display Product Details -->
            <h2>Product Details</h2>
            <p><strong>Product ID:</strong> <?php echo htmlspecialchars($product["PRODUCT_ID"]); ?></p>
            <p><strong>Product Name:</strong> <?php echo htmlspecialchars($product["PRODUCT_NAME"]); ?></p>
            <p><strong>Price:</strong> ₹<?php echo number_format((float)$product["PRICE"], 2); ?></p>

            <!-- Display Reviews -->
            <h2>Reviews</h2>
            <?php if ($reviews_result->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Customer ID</th>
                            <th>Rating</th>
                            <th>Review</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($review = $reviews_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($review["user_name"]); ?></td>
                                <td><?php echo htmlspecialchars($review["rating"]); ?></td>
                                <td><?php echo htmlspecialchars($review["comment"]); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="no-reviews">No reviews available for this product.</p>
            <?php endif; ?>
        </div>
    </div>
</body>

</html>