<?php require_once "/xampp/htdocs/Project/config/db.php"; 

// Check if service_id is passed
if (isset($_POST['service_id'])) {
    $service_id = $_POST['service_id'];

    // Delete query
    $deleteQuery = "DELETE FROM services WHERE SERVICE_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $service_id);

    if ($stmt->execute()) {
        header("Location: /Project/admin/module/service.php"); // Redirect to services list after deletion
        exit();
    } else {
        echo "Error deleting service: " . $stmt->error;
    }
}
?>