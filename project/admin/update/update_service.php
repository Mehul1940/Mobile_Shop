<?php
require_once "/xampp/htdocs/Project/config/db.php";

// Check if form data is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $serviceId = $_POST['SERVICE_ID'] ?? null;
    $serviceName = $_POST['SERVICE_NAME'] ?? null;
    $description = $_POST['DESCRIPTION'] ?? null;

    // Validate inputs
    if ($serviceId && $serviceName && $description) {
        // Prepare and execute the update query
        $query = "UPDATE services SET SERVICE_NAME = ?, DESCRIPTION = ? WHERE SERVICE_ID = ?";
        $stmt = $conn->prepare($query);

        if ($stmt) {
            $stmt->bind_param("ssi", $serviceName, $description, $serviceId);
            if ($stmt->execute()) {
                // Success: Redirect back to the services page
                header("Location: /Project/admin/module/service.php?update=success");
                exit();
            } else {
                // Query execution error
                $error = "Failed to update service. Please try again.";
            }
            $stmt->close();
        } else {
            // Query preparation error
            $error = "Failed to prepare the update query. Please try again.";
        }
    } else {
        // Validation error
        $error = "All fields are required. Please fill out the form completely.";
    }

    // If an error occurred, redirect with the error message
    header("Location: /Project/admin/module/services.php?update=error&message=" . urlencode($error));
    exit();
} else {
    // Invalid request method
    header("Location: /Project/admin/module/services.php?update=error&message=" . urlencode($error));
    exit();
}
