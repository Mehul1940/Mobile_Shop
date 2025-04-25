<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

// Handle form submission for adding a new service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_service'])) {
    $serviceName = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    // Insert the new service into the database
    $insertQuery = "INSERT INTO services (SERVICE_NAME, DESCRIPTION, PRICE) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("ssd", $serviceName, $description, $price);

    if ($stmt->execute()) {
        header("Location: /Project/admin/module/service.php"); // Redirect to services list page
        exit();
    } else {
        echo "Error adding service.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Service</title>
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
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #45a049;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .submit-btn:hover {
            background: #45a049;
        }

        .error-message {
            color: red;
            margin-top: 10px;
        }

        .cancel-btn {
            display: inline-block;
            margin-top: 10px;
            padding: 10px 15px;
            background: #ff3b3b;
            color: white;
            font-weight: bold;
            border-radius: 5px;
            text-decoration: none;
            transition: 0.3s;
        }

        .cancel-btn:hover {
            background: #d32f2f;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="details">
            <h2>Add New Service</h2>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="service_name">Service Name</label>
                    <input type="text" id="service_name" name="service_name" required>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" required>
                </div>
                <div class="form-group">
                    <label for="price">Price</label>
                    <input type="number" id="price" name="price" step="0.01" required>
                </div>
                <button type="submit" name="add_service" class="submit-btn">Add Service</button>
                <a href="/Project/admin/module/service.php" class="cancel-btn">Cancel</a>
            </form>
        </div>
    </div>
    </div>
</body>

</html>