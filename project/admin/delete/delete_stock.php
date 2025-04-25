<?php require_once "/xampp/htdocs/Project/config/db.php" ?>
<?php
// Check if stock ID is set via POST
if (isset($_POST['id'])) {
    $stock_id = $_POST['id'];

    // Prepare delete statement
    $deleteQuery = "DELETE FROM stock WHERE stock_id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $stock_id);

    // Execute the statement
    if ($stmt->execute()) {
        // Redirect to the inventory page
        header("Location: /Project/admin/module/stock.php");
        exit();
    } else {
        echo "Error deleting inventory: " . $stmt->error;
    }
} else {
    echo "Stock ID is missing!";
}

$conn->close();
?>
