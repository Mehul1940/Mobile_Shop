<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>
<?php
// Check if product ID is set via POST
if (isset($_POST['id'])) {
    $product_id = $_POST['id'];

    // Prepare delete statement
    $deleteQuery = "DELETE FROM product WHERE PRODUCT_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $product_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the product list page
        header("Location: /Project/admin/module/product.php");
        exit();
    } else {
        echo "Error deleting product: " . $stmt->error;
    }
} else {
    echo "Product ID is missing!";
}

$conn->close();
?>
