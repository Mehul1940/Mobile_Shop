<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Fetch categories and brands for select dropdowns
$categoryQuery = "SELECT CATEGORY_ID, CATEGORY_NAME FROM categories";
$categories = $conn->query($categoryQuery);

$brandQuery = "SELECT BRAND_ID, BRAND_NAME FROM brand";
$brand = $conn->query($brandQuery);

// Fetch all products with category and brand names
$query = "
    SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRICE, p.CATEGORY_ID, p.BRAND_ID, p.P_IMG, c.CATEGORY_NAME, b.BRAND_NAME
    FROM product p
    LEFT JOIN categories c ON p.CATEGORY_ID = c.CATEGORY_ID
    LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
    ORDER BY p.PRODUCT_ID ASC
";

$result = $conn->query($query);

if (!$result) {
    die("Query Failed: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="/Project/assets/css/product.css">
    <link rel="stylesheet" href="/Project/assets/css/modal.css">
    <script src="/Project/assets/js/main.js" defer></script>
    <script src="/Project/assets/js/updateProduct.js" defer></script>
</head>

<body>
    <div class="container">
        <!-- Include Navigation -->
        <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

        <div class="main">
            <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>

            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Products</h2>
                        <a href="/Project/admin/add/add_product.php" class="btn2">Add New Product</a>
                    </div>

                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Category Name</th>
                                <th>Brand Name</th>
                                <th>Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row["PRODUCT_ID"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["PRODUCT_NAME"]); ?></td>
                                        <td><?php echo number_format((float)$row["PRICE"], 2); ?></td>
                                        <td><?php echo htmlspecialchars($row["CATEGORY_NAME"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["BRAND_NAME"]); ?></td>
                                        <td>
                                            <?php if (!empty($row["P_IMG"])): ?>
                                                <img src="<?php echo "../../" . htmlspecialchars($row["P_IMG"]); ?>" alt="<?php echo htmlspecialchars($row["PRODUCT_NAME"]); ?>" style="max-width: 100px;">
                                            <?php else: ?>
                                                <span>No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="action-container">
                                                <!-- Main "More Actions" Icon -->
                                                <div class="more-actions-icon">
                                                    <ion-icon name="ellipsis-vertical" icon-i></ion-icon>
                                                </div>
                                                <!-- Hidden Action Buttons -->
                                                <div class="action-buttons">
                                                    <a href="/Project/admin/update/update_product.php?id=<?php echo $row['PRODUCT_ID']; ?>" class="icon-u" title="Update">
                                                        <ion-icon name="create-outline" class="icon-u"></ion-icon>
                                                    </a>
                                                    <form action="/Project/admin/delete/delete_product.php" method="post" onsubmit="return confirm('Are you sure?');">
                                                        <input type="hidden" name="id" value="<?php echo $row['PRODUCT_ID']; ?>">
                                                        <button type="submit" class="icon-i" title="Delete">
                                                            <ion-icon name="trash-outline" class="icon-i"></ion-icon>
                                                        </button>
                                                    </form>
                                                    <a href="/Project/admin/module/product_variants.php?id=<?php echo $row['PRODUCT_ID']; ?>" class="icon-w" title="Variants">
                                                        <ion-icon name="eye-outline" class="icon-w"></ion-icon>
                                                    </a>
                                                    <a href="/Project/admin/module/product_details.php?id=<?php echo $row['PRODUCT_ID']; ?>" class="icon-d" title="Details">
                                                        <ion-icon name="aperture-outline" class="icon-d"></ion-icon>
                                                    </a>
                                                    <form action="product_review.php" method="get" class="icon-form">
                                                        <input type="hidden" name="id" value="<?php echo $row['PRODUCT_ID']; ?>">
                                                        <button type="submit" class="icon-c" title="Reviews">
                                                            <ion-icon name="chatbubble-ellipses-outline" class="icon-c"></ion-icon>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="no-products">No products found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>