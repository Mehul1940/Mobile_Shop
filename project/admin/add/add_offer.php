<?php require_once "/xampp/htdocs/Project/config/admincheck.php"; ?>
<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>
<?php
// Handle form submission to add a new offer
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $offerName = $_POST['offer_name'];
    $productId = $_POST['product_id'];
    $startDate = $_POST['start_date'];
    $endDate = $_POST['end_date'];
    $discountRate = $_POST['discount_rate'];
    $description = $_POST['description'];

    // Handle image upload
    $offerImg = null;
    if (isset($_FILES['offer_imag']) && $_FILES['offer_imag']['error'] == 0) {
        $targetDir = "../../uploads/";  // Ensure this directory exists in the same folder as the script

        $fileName = $_FILES['offer_imag']['name'];

        // Get the file extension
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);

        $fileName = rand() . "." . pathinfo($fileName, PATHINFO_EXTENSION);

        $targetFile = $targetDir . $fileName;

        // Check if uploads folder exists, if not, create it
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);  // Create uploads directory with write permissions
        }

        if (move_uploaded_file($_FILES['offer_imag']['tmp_name'], $targetFile)) {
            $offerImg = $targetFile;
        } else {
            echo "Error uploading the image.";
        }
    }

    // Insert the offer into the database
    $sql = "INSERT INTO offer (OFFER_NAME, PRODUCT_ID, START_DATE, END_DATE, DISCOUNT_RATE, DESCRIPTION, OFFER_IMG) 
            VALUES ('$offerName', '$productId', '$startDate', '$endDate', '$discountRate', '$description', '$offerImg')";

    if ($conn->query($sql) === TRUE) {
        echo "New offer added successfully!";
        header("Location: /Project/admin/module/offer.php"); // Redirect to the offers list page
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}

// Fetch products for the dropdown menu
$productQuery = "SELECT PRODUCT_ID, PRODUCT_NAME FROM product";  // Modify the table name if needed
$productResult = $conn->query($productQuery);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Offer</title>
    <link rel="stylesheet" href="/Project/assets/css/styles.css">
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
        .form-group select,
        .form-group textarea {
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
        <h1>Add New Offer</h1>
        <form action="/Project/admin/add/add_offer.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="offer_name">Offer Name:</label>
                <input type="text" id="offer_name" name="offer_name" required>
            </div>

            <div class="form-group">
                <label for="product_id">Product ID:</label>
                <select id="product_id" name="product_id" required>
                    <option value="">Select product</option>
                    <?php
                    // Check if the query returns results
                    if ($productResult->num_rows > 0) {
                        while ($product = $productResult->fetch_assoc()) {
                            echo '<option value="' . $product['PRODUCT_ID'] . '">' . htmlspecialchars($product['PRODUCT_NAME']) . '</option>';
                        }
                    } else {
                        echo '<option value="">No products available</option>';
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>

            <div class="form-group">
                <label for="discount_rate">Discount Rate (%):</label>
                <input type="number" id="discount_rate" name="discount_rate" min="0" max="100" required>
            </div>

            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <div class="form-group">
                <label for="offer_imag">Offer Image:</label>
                <input type="file" id="offer_imag" name="offer_imag">
            </div>

            <button type="submit" class="submit-btn">Add Offer</button>
        </form>
    </div>
</body>

</html>

<?php
$conn->close();
?>