<?php
require_once "/xampp/htdocs/Project/config/admincheck.php";
require_once "/xampp/htdocs/Project/config/db.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['id'];

    $query = "DELETE FROM categories WHERE CATEGORY_ID = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        header("Location: /Project/admin/module/category.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}
?>
