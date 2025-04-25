<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Ensure 'variant_id' is passed in the URL
if (!isset($_GET['variant_id'])) {
    header("Location: /Project/admin/module/stock.php"); // Redirect if no variant ID is provided
    exit();
}

$variant_id = $_GET['variant_id'];

// Fetch variant details
$variantQuery = "SELECT * FROM product_variants WHERE VARIANT_ID = ?";
$stmt = $conn->prepare($variantQuery);
$stmt->bind_param("i", $variant_id);
$stmt->execute();
$variant = $stmt->get_result()->fetch_assoc();

// Fetch stock entries for the selected variant
$stockQuery = "SELECT * FROM stock WHERE variant_id = ?";
$stmt = $conn->prepare($stockQuery);
$stmt->bind_param("i", $variant_id);
$stmt->execute();
$stockEntries = $stmt->get_result();

// Handle the addition of serial numbers
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_serial'])) {
    $serial_number = $_POST['serial_number'];

    $insertStockQuery = "INSERT INTO stock (serial_number, variant_id) VALUES (?, ?)";
    $stmt = $conn->prepare($insertStockQuery);
    $stmt->bind_param("si", $serial_number, $variant_id);

    if ($stmt->execute()) {
        header("Location: /Project/admin/module/stock.php");
        exit();
    } else {
        echo "<script>alert('Error adding serial number.');</script>";
    }
}

// Handle the deletion of stock entries
if (isset($_GET['delete_id'])) {
    $stock_id = $_GET['delete_id'];
    $deleteStockQuery = "DELETE FROM stock WHERE stock_id = ?";
    $stmt = $conn->prepare($deleteStockQuery);
    $stmt->bind_param("i", $stock_id);

    if ($stmt->execute()) {
        header("Location: /Project/admin/module/stock.php");
        exit();
    } else {
        echo "<script>alert('Error deleting stock entry.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .details {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            animation: fadeIn 0.5s ease-in-out;
        }

        h2,
        h3 {
            color: #444;
        }

        /* Form Styles */
        form {
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #007bff;
            outline: none;
        }

        button[type="submit"],
        .cancel-btn,
        .delete-anchor {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button[type="submit"] {
            background-color: navy;
            color: #fff;
        }

        button[type="submit"]:hover {
            background-color: navy;
            transform: translateY(-2px);
        }

        .cancel-btn {
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }

        .cancel-btn:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }

        .delete-anchor {
            background-color: #dc3545;
            color: #fff;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
        }

        .delete-anchor:hover {
            background-color: #c82333;
            transform: translateY(-2px);
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            animation: slideIn 0.5s ease-in-out;
        }

        table th,
        table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        table th {
            background-color: navy;
            color: #fff;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Back Button */
        .back-to-product {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #6c757d;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .back-to-product:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="details">
            <h2>Stock Management for Variant: <?php echo htmlspecialchars($variant['COLOR']); ?></h2>

            <!-- Add Serial Number Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="serial_number">Serial Number</label>
                    <input type="text" id="serial_number" name="serial_number" required>
                </div>
                <button type="submit" name="add_serial">Add Serial Number</button>
                <a href="/project/admin/module/product.php" class="cancel-btn">Back To Product</a>
            </form>

            <!-- Existing Stock Entries Table -->
            <h3>Existing Stock Entries</h3>
            <table>
                <thead>
                    <tr>
                        <th>Stock ID</th>
                        <th>Variant ID</th>
                        <th>Serial Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($stock = $stockEntries->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($stock['stock_id']); ?></td>
                            <td><?php echo htmlspecialchars($stock['variant_id']); ?></td>
                            <td><?php echo htmlspecialchars($stock['serial_number']); ?></td>
                            <td>
                                <!-- Delete Button -->
                                <a href="?variant_id=<?php echo $variant_id; ?>&delete_id=<?php echo $stock['stock_id']; ?>" class="delete-anchor" onclick="return confirm('Are you sure you want to delete this stock entry?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>