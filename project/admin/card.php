<?php require_once "/xampp/htdocs/Project/config/db.php" ?>
<?php
// Query for Daily View (total customers added today)
$dailyViewQuery = "
    SELECT COUNT(*) AS daily_view
    FROM customer
";

// Check if the query was executed successfully
$dailyViewResult = $conn->query($dailyViewQuery);
if ($dailyViewResult === false) {
    die("Error executing query: " . $conn->error);  // Output the error message if the query fails
}

$dailyView = $dailyViewResult->fetch_assoc()['daily_view'];

// Query for Sales (completed orders count)
$salesQuery = "
    SELECT COUNT(*) AS sales
    FROM orders
    WHERE DELIVERY_STATUS = 'Delivered'
";
$salesResult = $conn->query($salesQuery);
if ($salesResult === false) {
    die("Error executing query: " . $conn->error);
}

$sales = $salesResult->fetch_assoc()['sales'];

// Query for Comments (number of feedback entries)
$commentsQuery = "
    SELECT COUNT(*) AS DETAILS
    FROM feedback_complaints
";
$commentsResult = $conn->query($commentsQuery);
if ($commentsResult === false) {
    die("Error executing query: " . $conn->error);
}

$comments = $commentsResult->fetch_assoc()['DETAILS'];

// Query for Total Bookings
$bookingsQuery = "
    SELECT COUNT(*) AS total_bookings
    FROM service_bookings
";
$bookingsResult = $conn->query($bookingsQuery);
if ($bookingsResult === false) {
    die("Error executing query: " . $conn->error);
}

$bookings = $bookingsResult->fetch_assoc()['total_bookings'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Cards</title>
    <!-- Style -->
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <link rel="stylesheet" href="/Project/assets/css/card.css">
    <script src="/Project/assets/js/main.js" defer></script>
</head>
<body>
    <div class="cardBox">
        <!-- Daily View Card -->
        <div class="card" style="--i:0">
            <div>
                <div class="numbers"><?php echo number_format($dailyView); ?></div>
                <div class="cardName">Total Customer</div>
            </div>
            <div class="iconBx">
                <ion-icon name="person-add-outline"></ion-icon>
            </div>
        </div>

        <!-- Sales Card -->
        <div class="card" style="--i:1">
            <div>
                <div class="numbers"><?php echo number_format($sales); ?></div>
                <div class="cardName">Sales</div>
            </div>
            <div class="iconBx">
                <ion-icon name="cart-outline"></ion-icon>
            </div>
        </div>

        <!-- Comments Card -->
        <div class="card" style="--i:2">
            <div>
                <div class="numbers"><?php echo number_format($comments); ?></div>
                <div class="cardName">Comments</div>
            </div>
            <div class="iconBx">
                <ion-icon name="chatbubble-outline"></ion-icon>
            </div>
        </div>

        <!-- Bookings Card -->
        <div class="card" style="--i:3">
            <div>
                <div class="numbers"><?php echo number_format($bookings); ?></div>
                <div class="cardName">Total Bookings</div>
            </div>
            <div class="iconBx">
                <ion-icon name="calendar-outline"></ion-icon>
            </div>
        </div>
    </div>

    <?php require_once "../chart.php" ?>
    
    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>
</html>