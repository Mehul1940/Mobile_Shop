<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";
// Query to fetch all suppliers
$supplierQuery = "SELECT SUPPLIER_ID, SUPPLIER_NAME, EMAIL, PHONE, ADDRESS FROM supplier";
$supplierResult = $conn->query($supplierQuery);

// Check if the query was successful
if (!$supplierResult) {
    echo "Error executing query: " . $conn->error;
    exit();
}

// Close the connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suppliers</title>
    <link rel="stylesheet" href="/Project/assets/css/supplier.css">
    <link rel="stylesheet" href="/Project/assets/css/slmodal.css">
    <script src="/Project/assets/js/main.js"defer></script>
    <script src="/Project/assets/js/updateSupplier.js"defer></script>
</head>

<body>
    <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

    <div class="main">
        <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>
        <div class="supplier-container">
            <h1>Suppliers</h1>

            <!-- Add New Supplier Form -->
            <div class="add-supplier-form">
                <a href="/Project/admin/add/add_supplier.php" class="add-btn">Add New Supplier</a>
            </div>

            <?php if ($supplierResult->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Supplier ID</th>
                            <th>Supplier Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $supplierResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['SUPPLIER_ID']); ?></td>
                                <td><?php echo htmlspecialchars($row['SUPPLIER_NAME']); ?></td>
                                <td><?php echo htmlspecialchars($row['EMAIL']); ?></td>
                                <td><?php echo htmlspecialchars($row['PHONE']); ?></td>
                                <td><?php echo htmlspecialchars($row['ADDRESS']); ?></td>
                                <td>
                                    <div class="action-btn-container">
                                        <button class="action-btn update-btn" onclick='openModal(<?php echo json_encode($row); ?>)'>
                                            Update
                                        </button>
                                        <form action="/Project/admin/delete/delete_supplier.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this supplier?');">
                                            <button type="submit" name="supplier_id" value="<?php echo $row['SUPPLIER_ID']; ?>" class="action-btn delete-btn">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #666;">No suppliers found.</p>
            <?php endif; ?>
        </div>
    </div>
    <!-- Update Modal -->
    <div class="modal" id="updateModal">
        <div class="modal-content">
            <h2>Update Supplier</h2>
            <form id="updateForm" method="POST" action="/Project/admin/update/update_supplier.php">
                <input type="hidden" name="SUPPLIER_ID" id="modalSupplierID">
                <input type="text" name="SUPPLIER_NAME" id="modalSupplierName" placeholder="Supplier Name" required>
                <input type="email" name="EMAIL" id="modalEmail" placeholder="Email" required>
                <input type="text" name="PHONE" id="modalPhone" placeholder="Phone" required>
                <textarea name="ADDRESS" id="modalAddress" placeholder="Address" required></textarea>
                <button type="submit" class="save-btn">Save Changes</button>
                <button type="button" class="close-btn" onclick="closeModal()">Close</button>
            </form>
        </div>
    </div>
</body>

</html>