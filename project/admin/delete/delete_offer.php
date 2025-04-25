<?php require_once "/xampp/htdocs/Project/config/admincheck.php"; ?>
<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>
<?php
// Check if the offer ID is provided in the POST request
if (isset($_POST['id']) && is_numeric($_POST['id'])) {
    $offerId = $_POST['id'];

    // Prepare the SQL DELETE query
    $sql = "DELETE FROM offer WHERE OFFER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $offerId);

    // Execute the query
    if ($stmt->execute()) {
        header("Location: /Project/admin/module/offer.php"); // Redirect to the offers list page
        exit();
    } else {
        echo "Error deleting offer: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Offer ID not provided.";
}

$conn->close();
?>
