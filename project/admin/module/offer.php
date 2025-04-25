<?php require_once "/xampp/htdocs/Project/config/admincheck.php"; ?>
<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>
<?php
// Fetch all offers from the database
$sql = "
    SELECT 
        o.*, 
        p.PRODUCT_NAME 
    FROM 
        offer o
    LEFT JOIN 
        product p 
    ON 
        o.PRODUCT_ID = p.PRODUCT_ID
    ORDER BY 
        o.START_DATE ASC";

$result = $conn->query($sql);

// Check if the query was successful
if (!$result) {
    die("Error executing query: " . $conn->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offer</title>
    <link rel="stylesheet" href="/Project/assets/css/product.css">
    <link rel="stylesheet" href="/Project/assets/css/modal.css">
    <script src="/Project/assets/js/main.js" defer></script>
    <script src="/Project/assets/js/updateOffer.js" defer></script>
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
                        <h2>Offers</h2>
                        <a href="/Project/admin/add/add_offer.php" class="btn2">Add New Offer</a>
                    </div>

                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Offer ID</th>
                                <th>Offer Name</th>
                                <th>Product Name</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Discount Rate</th>
                                <th>Description</th>
                                <th>Offer Image</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row["OFFER_ID"]); ?></td>
                                        <td><?php echo htmlspecialchars($row['OFFER_NAME']); ?></td>
                                        <td><?php echo htmlspecialchars($row['PRODUCT_NAME'] ?? 'Unknown Product'); ?></td>
                                        <td><?php echo htmlspecialchars($row['START_DATE']); ?></td>
                                        <td><?php echo htmlspecialchars($row['END_DATE']); ?></td>
                                        <td><?php echo htmlspecialchars($row['DISCOUNT_RATE']); ?>%</td>
                                        <td><?php echo htmlspecialchars($row['DESCRIPTION']); ?></td>
                                        <td>
                                            <?php if (!empty($row["OFFER_IMG"])): ?>
                                                <img src="<?php echo htmlspecialchars($row["OFFER_IMG"]); ?>" alt="<?php echo htmlspecialchars($row["OFFER_NAME"]); ?>">
                                            <?php else: ?>
                                                <span>No Image</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <button class="btn" onclick="handleUpdateButtonClick(
                                                '<?php echo $row["OFFER_ID"]; ?>', 
                                                '<?php echo addslashes($row["OFFER_NAME"]); ?>', 
                                                '<?php echo $row["PRODUCT_ID"]; ?>', 
                                                '<?php echo $row["START_DATE"]; ?>', 
                                                '<?php echo $row["END_DATE"]; ?>', 
                                                '<?php echo $row["DISCOUNT_RATE"]; ?>', 
                                                '<?php echo addslashes($row["DESCRIPTION"]); ?>', 
                                                '<?php echo addslashes($row["OFFER_IMG"]); ?>')">Update</button>

                                            <form action="/Project/admin/delete/delete_offer.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this offer?');">
                                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($row["OFFER_ID"]); ?>">
                                                <button class="btn" type="submit" style="background-color: red;">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9">No offers found</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal" id="updateModal">
        <div class="modal-content">
            <div class="modal-header">
                <span class="modal-close" onclick="closeModal()">&times;</span>
                <h2>Update Offer</h2>
            </div>

            <form action="/Project/admin/update/update_offer.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" id="OFFER_ID" name="OFFER_ID">

                <div class="form-group">
                    <label for="OFFER_NAME">Offer Name:</label>
                    <input type="text" id="OFFER_NAME" name="OFFER_NAME" required>
                </div>

                <div class="form-group">
                    <label for="PRODUCT_ID">Product Name:</label>
                    <input type="number" id="PRODUCT_ID" name="PRODUCT_ID" required>
                </div>

                <div class="form-group">
                    <label for="START_DATE">Start Date:</label>
                    <input type="date" id="START_DATE" name="START_DATE" required>
                </div>

                <div class="form-group">
                    <label for="END_DATE">End Date:</label>
                    <input type="date" id="END_DATE" name="END_DATE" required>
                </div>

                <div class="form-group">
                    <label for="DISCOUNT_RATE">Discount Rate:</label>
                    <input type="number" id="DISCOUNT_RATE" name="DISCOUNT_RATE" required>
                </div>

                <div class="form-group">
                    <label for="DESCRIPTION">Description:</label>
                    <textarea id="DESCRIPTION" name="DESCRIPTION" required></textarea>
                </div>

                <div class="form-group">
                    <label for="OFFER_IMG">Offer Image:</label>
                    <input type="file" id="OFFER_IMG" name="OFFER_IMG">
                </div>

                <div class="form-group">
                    <label>Current Image:</label>
                    <img id="OFFER_IMG_PREVIEW" src="" alt="Current Image" style="max-width: 200px; display: none;">
                </div>

                <button type="submit" class="btn submit">Update Offer</button>
            </form>
        </div>
    </div>

</body>

<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>

</html>

<?php
$conn->close();
?>