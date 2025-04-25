<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

$error_message = ''; // Initialize error message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $supplier_name = isset($_POST['supplier_name']) ? htmlspecialchars(trim($_POST['supplier_name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $address = isset($_POST['address']) ? htmlspecialchars(trim($_POST['address'])) : '';

    // Basic validation for required fields (server-side)
    if (empty($supplier_name) || empty($email) || empty($phone) || empty($address)) {
        $error_message = "All fields are required.";
    } else {
        // Insert query
        $insertQuery = "INSERT INTO supplier (SUPPLIER_NAME, EMAIL, PHONE, ADDRESS) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);

        if ($stmt === false) {
            die("Error preparing the statement: " . $conn->error);
        }

        // Bind parameters (all are strings)
        $stmt->bind_param("ssss", $supplier_name, $email, $phone, $address);

        if ($stmt->execute()) {
            header("Location: /Project/admin/module/supplier.php"); // Redirect to the supplier list page after insertion
            exit();
        } else {
            $error_message = "Error: " . $stmt->error;
        }
    }
}

// Close connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Supplier</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .supplier-form-container {
            max-width: 600px;
            margin: 40px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 30px;
            font-size: 2em;
            color: #007BFF;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            font-size: 1em;
            border-radius: 5px;
            border: 1px solid #ddd;
        }

        .submit-btn {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #45a049;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .error-message {
            color: red;
            font-size: 1em;
            text-align: center;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .supplier-form-container {
                padding: 15px;
            }

            .form-input {
                font-size: 0.9em;
            }
        }
    </style>
</head>

<body>
    <div class="supplier-form-container">
        <h1>Add Supplier</h1>
        <form action="/Project/admin/add/add_supplier.php" method="POST">
            <div class="form-group">
                <label for="supplier_name">Supplier Name:</label>
                <input type="text" id="supplier_name" name="supplier_name" class="form-input" placeholder="Enter supplier name" required value="<?php echo $supplier_name ?? ''; ?>">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-input" placeholder="Enter supplier email" required value="<?php echo $email ?? ''; ?>">
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" id="phone" name="phone" class="form-input" placeholder="Enter supplier phone number" pattern="^[0-9]{10}$" required value="<?php echo $phone ?? ''; ?>">
                <small>Phone number must be 10 digits.</small>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <textarea id="address" name="address" class="form-input" placeholder="Enter supplier address" rows="4" required><?php echo $address ?? ''; ?></textarea>
            </div>

            <button type="submit" class="submit-btn">Add Supplier</button>
        </form>
    </div>

</body>

</html>