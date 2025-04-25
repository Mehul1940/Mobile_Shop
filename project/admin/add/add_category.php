<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryName = $_POST['CATEGORY_NAME'];

    // Prepare the SQL insert query
    $query = "INSERT INTO categories (CATEGORY_NAME) VALUES (?)";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $categoryName);

        if ($stmt->execute()) {
            // Redirect after successful insertion
            header("Location: /Project/admin/module/category.php?success=Category added successfully");
        } else {
            // Error message if insertion fails
            header("Location: /Project/admin/module/category.php?error=Failed to add category");
        }

        $stmt->close();
    } else {
        header("Location: /Project/admin/module/category.php?error=Query preparation failed");
    }
} else {
    // If the form is not submitted yet, display the add category form
?>

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Add Category</title>
        <link rel="stylesheet" href="/Project/assets/css/style.css">
        <style>
            body {
                font-family: Arial, sans-serif;
                background-color: #f9f9f9;
                margin: 0;
                padding: 0;
            }

            .container {
                max-width: 60%;
                margin: 30px auto;
                background-color: #fff;
                padding: 20px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }

            h2 {
                text-align: center;
                color: #333;
                margin-bottom: 20px;
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

            button[type="submit"] {
                width: 100%;
                padding: 12px;
                background-color: #4CAF50;
                color: white;
                border: none;
                font-size: 16px;
                border-radius: 5px;
                cursor: pointer;
                transition: background-color 0.3s;
            }
        </style>
    </head>

    <body>
        <div class="container">


            <div class="details">
                <div class="cardHeader">
                    <h2>Add New Category</h2>
                </div>

                <form action="add_category.php" method="POST">
                    <div class="form-group">
                        <label for="CATEGORY_NAME">Category Name</label>
                        <input type="text" name="CATEGORY_NAME" id="CATEGORY_NAME" required>
                    </div>

                    <button type="submit" class="btn submit">Add Category</button>
                </form>
            </div>

        </div>
    </body>

    </html>

<?php
}

$conn->close();
?>