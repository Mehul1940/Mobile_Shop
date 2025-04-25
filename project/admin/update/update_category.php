<?php
require_once "/xampp/htdocs/Project/config/db.php";

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoryId = $_POST['CATEGORY_ID'];
    $categoryName = $_POST['CATEGORY_NAME'];

    // Prepare the SQL update query
    $query = "UPDATE categories SET CATEGORY_NAME = ? WHERE CATEGORY_ID = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("si", $categoryName, $categoryId);
        
        if ($stmt->execute()) {
            // Redirect after successful update
            header("Location: /Project/admin/module/category.php?success=Category updated successfully");
        } else {
            // Error message if update fails
            header("Location: /Project/admin/module/category.php?error=Failed to update category");
        }

        $stmt->close();
    } else {
        header("Location: /Project/admin/module/category.php?error=Query preparation failed");
    }
} else {
    header("Location: /Project/admin/module/category.php?error=Invalid request method");
}

$conn->close();
?>
