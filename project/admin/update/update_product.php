<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['PRODUCT_ID'];
    $product_name = $conn->real_escape_string($_POST['PRODUCT_NAME']);
    $price = $_POST['PRICE'];
    $category_id = $_POST['CATEGORY_ID'];
    $brand_id = $_POST['BRAND_ID'];
    $image_path = $_POST['CURRENT_IMAGE'];

    // Handle file upload for image change
    if (isset($_FILES['P_IMG']) && $_FILES['P_IMG']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/Project/imgs/product/";
        $filename = basename($_FILES['P_IMG']['name']);
        $target_file = $target_dir . $filename;
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        if (in_array($image_file_type, ['jpg', 'jpeg', 'png', 'gif'])) {
            if (move_uploaded_file($_FILES['P_IMG']['tmp_name'], $target_file)) {
                $image_path = "imgs/product/" . $filename;
            }
        }
    }

    $query = "UPDATE product SET 
              PRODUCT_NAME = '$product_name', 
              PRICE = '$price', 
              CATEGORY_ID = '$category_id', 
              BRAND_ID = '$brand_id', 
              P_IMG = '$image_path' 
              WHERE PRODUCT_ID = '$product_id'";

    if ($conn->query($query)) {
        header("Location: /Project/admin/module/product.php");
    } else {
        die("Error: " . $conn->error);
    }
} else {
    $product_id = $_GET['id'];
    $query = "SELECT * FROM product WHERE PRODUCT_ID = $product_id";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Product</title>
    <link rel="stylesheet" href="/Project/assets/css/product.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f7f7f7;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* Header Styles */
        h2 {
            text-align: center;
            font-size: 36px;
            color: #333;
            margin-bottom: 30px;
            font-weight: 600;
        }

        /* Form Styles */
        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Label Styles */
        label {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        /* Input and Select Styles */
        input[type="text"],
        input[type="number"],
        select,
        input[type="file"] {
            padding: 12px 16px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 8px;
            width: 100%;
            box-sizing: border-box;
            transition: border-color 0.3s ease;
        }

        /* Focus State */
        input[type="text"]:focus,
        input[type="number"]:focus,
        select:focus,
        input[type="file"]:focus {
            border-color: #4CAF50;
            outline: none;
        }

        /* Select Box Styling */
        select {
            height: 45px;
        }

        /* Image Preview Styles */
        img {
            margin-top: 15px;
            border-radius: 8px;
            max-width: 100px;
            border: 1px solid #ddd;
        }

        /* Submit Button Styles */
        button.submit {
            padding: 14px 20px;
            font-size: 18px;
            color: white;
            background-color: #4CAF50;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            margin-top: 20px;
        }

        button.submit:hover {
            background-color: #45a049;
        }

        /* Error Message */
        .error-message {
            color: red;
            font-size: 14px;
            margin-top: 10px;
        }

        /* Mobile Responsiveness */
        @media (max-width: 768px) {
            .container {
                width: 90%;
                padding: 20px;
            }

            h2 {
                font-size: 28px;
            }

            form {
                gap: 15px;
            }

            button.submit {
                font-size: 16px;
                padding: 12px 18px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="details">
            <div class="cardHeader">
                <h2>Update Product</h2>
            </div>

            <form action="/Project/admin/update/update_product.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="PRODUCT_ID" value="<?php echo $product['PRODUCT_ID']; ?>">

                <label for="PRODUCT_NAME">Product Name</label>
                <input type="text" name="PRODUCT_NAME" value="<?php echo htmlspecialchars($product['PRODUCT_NAME']); ?>" required>

                <label for="PRICE">Price</label>
                <input type="number" name="PRICE" value="<?php echo $product['PRICE']; ?>" step="0.01" required>

                <label for="CATEGORY_ID">Category</label>
                <select name="CATEGORY_ID" required>
                    <option value="">Select Category</option>
                    <?php
                    // Fetch categories for selection
                    $categories = $conn->query("SELECT CATEGORY_ID, CATEGORY_NAME FROM categories");
                    while ($category = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $category['CATEGORY_ID']; ?>" <?php echo ($category['CATEGORY_ID'] == $product['CATEGORY_ID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($category['CATEGORY_NAME']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="BRAND_ID">Brand</label>
                <select name="BRAND_ID" required>
                    <option value="">Select Brand</option>
                    <?php
                    // Fetch brands for selection
                    $brands = $conn->query("SELECT BRAND_ID, BRAND_NAME FROM brand");
                    while ($brand = $brands->fetch_assoc()): ?>
                        <option value="<?php echo $brand['BRAND_ID']; ?>" <?php echo ($brand['BRAND_ID'] == $product['BRAND_ID']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($brand['BRAND_NAME']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="P_IMG">Product Image</label>
                <input type="file" name="P_IMG" accept="image/*">
                <img src="../../<?php echo $product['P_IMG']; ?>" alt="Current Product Image" style="max-width: 100px;">

                <input type="hidden" name="CURRENT_IMAGE" value="<?php echo $product['P_IMG']; ?>">

                <button type="submit" class="btn submit">Update Product</button>
            </form>
        </div>
    </div>

</body>

</html>