<?php
session_start();
// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";
$conn = new mysqli($servername, $username, $password, $dbname);

// Cart Item Count
$cart_count = 0;
if (isset($_SESSION['CUSTOMER_ID'])) {
    $customer_id = $_SESSION['CUSTOMER_ID'];
    $cart_query = "SELECT SUM(cd.QUANTITY) as total 
                   FROM cart c 
                   JOIN cart_details cd ON c.CART_ID = cd.CART_ID 
                   WHERE c.CUSTOMER_ID = $customer_id";
    $result = $conn->query($cart_query);
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $cart_count = $row['total'] ?? 0;
    }
}

// Handle Search
if (isset($_GET['search'])) {
    $search_term = $conn->real_escape_string($_GET['search']);
    header("Location: search.php?q=" . urlencode($search_term));
    exit();
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nilkanth Mobiles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
</head>

<body>
    <?php require_once "nav1.php" ?>
    <?php require_once "banner.php" ?>
    <?php require_once "card.php" ?>
    <?php require_once "p.php" ?>
    <?php require_once "footer.php" ?>
</body>

</html>