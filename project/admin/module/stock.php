<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Fetch stock data along with product name and variant color
$stockQuery = "
    SELECT 
        stock.stock_id, 
        stock.serial_number, 
        stock.product_id, 
        product.PRODUCT_NAME, 
        product_variants.COLOR 
    FROM 
        stock
    LEFT JOIN 
        product_variants ON stock.variant_id = product_variants.VARIANT_ID
    LEFT JOIN 
        product ON product_variants.PRODUCT_ID = product.PRODUCT_ID
";
$result = $conn->query($stockQuery);

if (!$result) {
    die("Error fetching stock data: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Information</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <link rel="stylesheet" href="/Project/assets/css/stock.css">
</head>

<body>
    <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

    <div class="main">
        <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>
        <h2>Stock Details</h2>

        <table>
            <thead>
                <tr>
                    <th>Stock ID</th>
                    <th>Serial Number</th>
                    <th>Product Name</th>
                    <th>Color</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['stock_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['serial_number']); ?></td>
                        <td><?php echo htmlspecialchars($row['PRODUCT_NAME']); ?></td>
                        <td><?php echo htmlspecialchars($row['COLOR']); ?></td>
                        <td>
                            <form action="/Project/admin/delete/delete_stock.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row["stock_id"]); ?>">
                                <button class="btn" type="submit" style="background-color: red;">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    </div>
</body>

</html>