<?php require_once "/xampp/htdocs/Project/config/admincheck.php"; ?>
<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>

<?php
// Fetch all categories
$query = "SELECT * FROM categories ORDER BY CATEGORY_ID ASC";
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
    <title>Categories</title>
    <link rel="stylesheet" href="/Project/assets/css/modal.css">
    <link rel="stylesheet" href="/Project/assets/css/cat.css">
    <script src="/Project/assets/js/main.js" defer></script>
    <script src="/Project/assets/js/updateCategory.js" defer></script>
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
                        <h2>Categories</h2>
                        <a href="/Project/admin/add/add_category.php" class="btn2">Add New Category</a>
                    </div>

                    <table class="category-table">
                        <thead>
                            <tr>
                                <th>Category ID</th>
                                <th>Category Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row["CATEGORY_ID"]); ?></td>
                                        <td><?php echo htmlspecialchars($row["CATEGORY_NAME"]); ?></td>
                                        <td>
                                            <button class="btn" type="button" onclick="openModal(<?php echo htmlspecialchars(json_encode($row)); ?>)">Update</button>

                                            <form action="/Project/admin/delete/delete_category.php" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row["CATEGORY_ID"]); ?>">
                                                <button class="btn" type="submit" style="background-color: red;">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="no-products">No categories found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Category Update -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="modal-close" onclick="closeModal()">&times;</span>
            <div class="modal-header">Update Category</div>
            <form id="updateForm" action="/Project/admin/update/update_category.php" method="post">
                <input type="hidden" name="CATEGORY_ID" id="CATEGORY_ID">
                <div class="form-group">
                    <label for="CATEGORY_NAME">Category Name</label>
                    <input type="text" name="CATEGORY_NAME" id="CATEGORY_NAME" required>
                </div>
                <button type="submit" class="btn2">Update</button>
            </form>
        </div>
    </div>

</body>

</html>