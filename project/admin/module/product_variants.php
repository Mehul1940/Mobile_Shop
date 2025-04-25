<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Ensure 'id' is passed in the URL
if (!isset($_GET['id'])) {
    header("Location: product_list.php"); // Redirect if no ID is provided
    exit();
}

$product_id = $_GET['id'];

// Fetch product variants based on the product ID
$variantQuery = "SELECT * FROM product_variants WHERE PRODUCT_ID = ?";
$stmt = $conn->prepare($variantQuery);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$variants = $stmt->get_result();

// Fetch product details 
$productQuery = "SELECT * FROM product WHERE PRODUCT_ID = ?";
$productStmt = $conn->prepare($productQuery);
$productStmt->bind_param("i", $product_id);
$productStmt->execute();
$product = $productStmt->get_result()->fetch_assoc();

// Handle the addition of variants
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_variant'])) {
    $color = $_POST['color'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $price = $_POST['price'];

    $insertVariantQuery = "INSERT INTO product_variants (PRODUCT_ID, COLOR, RAM, STORAGE, PRICE) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($insertVariantQuery);
    $stmt->bind_param("isssd", $product_id, $color, $ram, $storage, $price);

    if ($stmt->execute()) {
        header("Location: product_variants.php?id=" . $product_id);
        exit();
    } else {
        echo "<script>alert('Error adding variant.');</script>";
    }
}

// Handle the update of variants
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_variant'])) {
    $variant_id = $_POST['variant_id'];
    $color = $_POST['color'];
    $ram = $_POST['ram'];
    $storage = $_POST['storage'];
    $price = $_POST['price'];

    $updateVariantQuery = "UPDATE product_variants SET COLOR = ?, RAM = ?, STORAGE = ?, PRICE = ? WHERE VARIANT_ID = ?";
    $stmt = $conn->prepare($updateVariantQuery);
    $stmt->bind_param("sssdi", $color, $ram, $storage, $price, $variant_id);

    if ($stmt->execute()) {
        header("Location: product_variants.php?id=" . $product_id);
        exit();
    } else {
        echo "<script>alert('Error updating variant.');</script>";
    }
}

// Handle the deletion of variants
if (isset($_GET['delete_id'])) {
    $variant_id = $_GET['delete_id'];
    $deleteVariantQuery = "DELETE FROM product_variants WHERE VARIANT_ID = ?";
    $stmt = $conn->prepare($deleteVariantQuery);
    $stmt->bind_param("i", $variant_id);

    if ($stmt->execute()) {
        header("Location: product_variants.php?id=" . $product_id);
        exit();
    } else {
        echo "<script>alert('Error deleting variant.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Variants</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <link rel="stylesheet" href="/Project/assets/css/smodal.css">
</head>

<body>
    <div class="container">
        <!-- Include Navigation -->
        <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

        <div class="main">
            <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>

            <div class="details">
                <h2>Product Variants for <?php echo htmlspecialchars($product['PRODUCT_NAME']); ?></h2>

                <!-- Add Variant Form -->
                <form method="POST" action="">
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" id="color" name="color" required>
                    </div>
                    <div class="form-group">
                        <label for="ram">RAM</label>
                        <input type="text" id="ram" name="ram">
                    </div>
                    <div class="form-group">
                        <label for="storage">Storage</label>
                        <input type="text" id="storage" name="storage">
                    </div>
                    <div class="form-group">
                        <label for="price">Price</label>
                        <input type="number" id="price" name="price" step="0.01" required>
                    </div>
                    <button type="submit" name="add_variant">Add Variant</button>
                </form>

                <!-- Existing Variants Table -->
                <h3>Existing Variants</h3>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Color</th>
                            <th>RAM</th>
                            <th>Storage</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($variant = $variants->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($variant['VARIANT_ID']); ?></td>
                                <td><?php echo htmlspecialchars($variant['COLOR']); ?></td>
                                <td><?php echo htmlspecialchars($variant['RAM']); ?></td>
                                <td><?php echo htmlspecialchars($variant['STORAGE']); ?></td>
                                <td><?php echo number_format((float)$variant['PRICE'], 2); ?></td>
                                <td>
                                    <!-- Update Button -->
                                    <button onclick="openUpdateModal(<?php echo $variant['VARIANT_ID']; ?>, '<?php echo htmlspecialchars($variant['COLOR']); ?>', '<?php echo htmlspecialchars($variant['RAM']); ?>', '<?php echo htmlspecialchars($variant['STORAGE']); ?>', <?php echo $variant['PRICE']; ?>)">Update</button>
                                    <!-- Stock Button -->
                                    <a href="/Project/admin/add/add_stock.php?variant_id=<?php echo $variant['VARIANT_ID']; ?>" class="stock-anchor">Add Stock</a>
                                    <!-- Delete Button -->
                                    <a href="?id=<?php echo $product_id; ?>&delete_id=<?php echo $variant['VARIANT_ID']; ?>" class="delete-anchor" onclick="return confirm('Are you sure you want to delete this variant?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Updating Variant -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h3>Update Variant</h3>
            <form method="POST" action="">
                <input type="hidden" id="variant_id" name="variant_id">
                <div class="form-group">
                    <label for="update_color">Color</label>
                    <input type="text" id="update_color" name="color" required>
                </div>
                <div class="form-group">
                    <label for="update_ram">RAM</label>
                    <input type="text" id="update_ram" name="ram">
                </div>
                <div class="form-group">
                    <label for="update_storage">Storage</label>
                    <input type="text" id="update_storage" name="storage">
                </div>
                <div class="form-group">
                    <label for="update_price">Price</label>
                    <input type="number" id="update_price" name="price" step="0.01" required>
                </div>
                <button type="submit" name="update_variant">Update Variant</button>
                <button type="button" class="cancel-btn" onclick="closeUpdateModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // Open the update modal with pre-filled data
        function openUpdateModal(variantId, color, ram, storage, price) {
            document.getElementById("update_color").value = color;
            document.getElementById("update_ram").value = ram;
            document.getElementById("update_storage").value = storage;
            document.getElementById("update_price").value = price;
            document.getElementById("variant_id").value = variantId;
            document.getElementById("updateModal").style.display = "block";
        }

        // Close the update modal
        function closeUpdateModal() {
            document.getElementById("updateModal").style.display = "none";
        }
    </script>
</body>

</html>