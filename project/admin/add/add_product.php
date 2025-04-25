<?php require_once "/xampp/htdocs/Project/config/admincheck.php"; ?>
<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>
<?php
// Fetch categories for dropdown
$categoryQuery = "SELECT * FROM categories";
$categories = $conn->query($categoryQuery);

// Fetch brands for dropdown (correct the query and variable name)
$brandQuery = "SELECT * FROM brand";
$brands = $conn->query($brandQuery); // Renamed from $bookings to $brands

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $brand_id = $_POST['BRAND_ID'];
    $category_id = $_POST['category_id'];

    // Handle file upload
    $target_dir = "imgs/product/"; // Relative path
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Ensure the directory exists and is writable
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); // Create the directory if it doesn't exist
    }

    // File validation: only allow certain image formats
    if (isset($_FILES["product_image"]) && $_FILES["product_image"]["error"] == 0) {
        $check = getimagesize($_FILES["product_image"]["tmp_name"]);
        if ($check !== false) {
            // Allow certain file formats
            $allowed_types = ["jpg", "jpeg", "png", "gif"];
            if (in_array($imageFileType, $allowed_types)) {
                // Try to upload file
                if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
                    $sql = "INSERT INTO product (PRODUCT_NAME, PRICE, CATEGORY_ID, BRAND_ID, P_IMG) VALUES (?, ?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("sdiss", $product_name, $price, $brand_id, $category_id, $target_file);

                    if ($stmt->execute()) {
                        // Redirect to products page after successful insertion
                        header("Location: /Project/admin/module/product.php");
                        exit();
                    } else {
                        $error_message = "Error: " . $stmt->error;
                    }
                } else {
                    $error_message = "Sorry, there was an error uploading your file.";
                }
            } else {
                $error_message = "Only JPG, JPEG, PNG & GIF files are allowed.";
            }
        } else {
            $error_message = "File is not an image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 30px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .form-group input[type="file"] {
            padding: 5px;
        }

        .form-group select {
            height: 40px;
        }

        .form-group .error-message {
            color: red;
            font-size: 14px;
            margin-top: 5px;
        }

        .submit-btn {
            display: block;
            width: 100%;
            padding: 15px;
            background-color: #4CAF50;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Add New Product</h2>
        <!-- Display error messages -->
        <?php if (isset($error_message)): ?>
            <div class="error-message">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="product_name">Product Name</label>
                <input type="text" id="product_name" name="product_name" required>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select Category</option>
                    <?php while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['CATEGORY_ID']; ?>">
                            <?php echo htmlspecialchars($category['CATEGORY_NAME']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="BRAND_ID">Brand</label>
                <select id="BRAND_ID" name="BRAND_ID" required>
                    <option value="">Select Brand</option>
                    <?php while ($brand = $brands->fetch_assoc()): ?> <!-- Changed $booking to $brand -->
                        <option value="<?php echo $brand['BRAND_ID']; ?>">
                            <?php echo htmlspecialchars($brand['BRAND_NAME']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="price">Price</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>

            <div class="form-group">
                <label for="product_image">Product Image</label>
                <input type="file" id="product_image" name="product_image" required>
            </div>

            <button type="submit" class="submit-btn">Add Product</button>
        </form>
    </div>


    <script src="/Project/assets/js/main.js"></script>
    <!-- IonIcons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>

</html>