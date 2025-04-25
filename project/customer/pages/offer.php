<?php
session_start();
require_once "../header.php"; // Include header
$con = new mysqli('localhost', 'root', '', 'project'); // Database connection

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Fetch offers from the database
$query = "SELECT o.OFFER_ID, o.PRODUCT_ID, p.PRODUCT_NAME, o.OFFER_NAME, o.START_DATE, o.END_DATE, 
                 o.DISCOUNT_RATE, o.DESCRIPTION, o.OFFER_IMG 
          FROM offer o 
          LEFT JOIN product p ON o.PRODUCT_ID = p.PRODUCT_ID";
$result = $con->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Offers - Nilkanth Mobiles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .offer-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .offer-card img {
            width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .offer-details {
            padding: 10px;
        }
        .offer-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .offer-description {
            font-size: 14px;
            color: #666;
        }
        .offer-date {
            font-size: 14px;
            color: #888;
        }
        .discount {
            font-size: 18px;
            color: #d9534f;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <br>
 <?php require_once"../footer.php"?>
</body>
</html>

<?php $con->close(); ?>
