<?php
require_once "/xampp/htdocs/Project/config/db.php";

// Get offer data from POST request
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $OFFER_ID = $_POST['OFFER_ID'];
    $OFFER_NAME = $_POST['OFFER_NAME'];
    $PRODUCT_ID = $_POST['PRODUCT_ID'];
    $START_DATE = $_POST['START_DATE'];
    $END_DATE = $_POST['END_DATE'];
    $DISCOUNT_RATE = $_POST['DISCOUNT_RATE'];
    $DESCRIPTION = $_POST['DESCRIPTION'];

    // Initialize image path (will be updated only if a new image is uploaded)
    $imagePath = $_POST['current_image']; // Use current image if no new image is uploaded

    // Check if a new image is uploaded
    if (isset($_FILES['OFFER_IMG']) && $_FILES['OFFER_IMG']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/Project/uploads/";
        $filename = basename($_FILES['OFFER_IMG']['name']);
        $target_file = $target_dir . $filename;
        $relative_path = "/Project/uploads/" . $filename; // Save relative path in DB
        $image_file_type = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Validate the file type
        $allowed_types = ["jpg", "jpeg", "png", "gif"];
        if (in_array($image_file_type, $allowed_types)) {
            if (move_uploaded_file($_FILES['OFFER_IMG']['tmp_name'], $target_file)) {
                $imagePath = $relative_path; // Set the image path to the newly uploaded image
            } else {
                die("Error uploading image.");
            }
        } else {
            die("Invalid file type.");
        }
    }

    // Update the offer in the database
    $query = "UPDATE offer SET 
                OFFER_NAME = ?, 
                PRODUCT_ID = ?, 
                START_DATE = ?, 
                END_DATE = ?, 
                DISCOUNT_RATE = ?, 
                DESCRIPTION = ?, 
                OFFER_IMG = ? 
              WHERE OFFER_ID = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param("sisssssi", $OFFER_NAME, $PRODUCT_ID, $START_DATE, $END_DATE, $DISCOUNT_RATE, $DESCRIPTION, $imagePath, $OFFER_ID);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        // Redirect after successful update
        header("Location: /Project/admin/module/offer.php");
        exit();
    } else {
        die("Failed to update offer.");
    }
}
?>
