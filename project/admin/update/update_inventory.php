<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Inventory</title>
</head>
<body>
<?php require_once "/xampp/htdocs/Project/config/db.php" ?>
<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $stockId = $_POST['id'];
    $stockName = $_POST['stockName'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $product = $_POST['product'];
    $currentUnits = $_POST['currentUnits'];
    $unitsRequired = $_POST['unitsRequired'];

    // Update the inventory
    $sql = "UPDATE inventory SET STOCK_NAME=?, CATEGORY_ID=?, BRAND_ID=?, PRODUCT_ID=?, CURRENT_UNITS=?, UNITS_REQUIRED=? WHERE STOCK_ID=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sssssss', $stockName, $category, $brand, $product, $currentUnits, $unitsRequired, $stockId);
    if ($stmt->execute()) {
        echo "Inventory updated successfully";
        header('Location: /Project/admin/module/inventory.php'); // Redirect to inventory page after successful update
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
  
</body>
</html>