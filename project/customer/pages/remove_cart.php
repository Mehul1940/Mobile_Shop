<?php
session_start();
$con = new mysqli('localhost', 'root', '', 'project');

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['c_id'])) {
    $c_id = $_POST['c_id'];

    $query = "DELETE FROM cart_details WHERE C_ID = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $c_id);
    if ($stmt->execute()) {
        echo "Item Removed";
    } else {
        echo "Error removing item";
    }
}
?>
