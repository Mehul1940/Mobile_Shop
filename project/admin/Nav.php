<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sidebar</title>
    <link rel="stylesheet" href="/project/assets/css/nav.css">
</head>

<body>
    <div class="nav">
        <ul>
            <li class="logo-container">
                <img src="/project/customer/assets/logo/1.png" alt="Nilkanth Mobiles Logo" class="logo" />
                <hr class="nav-divider">
            </li>
            <li>
                <a href="dashboard.php"><span class="icon"><ion-icon name="home-outline"></ion-icon></span><span class="icon">DashBoard</span></a>
            </li>

            <!-- Admin-specific menu items -->
            <?php if (isset($_SESSION['userType']) && $_SESSION['userType'] == 'admin'): ?>
                <li>
                    <a href="category.php"><span class="icon"><ion-icon name="options-outline"></ion-icon></span><span class="icon">Category</span></a>
                </li>
                <li>
                    <a href="product.php"><span class="icon"><ion-icon name="cube-outline"></ion-icon></span><span class="icon">Product</span></a>
                </li>
                <li>
                    <a href="stock.php"><span class="icon"><ion-icon name="layers-outline"></ion-icon></span><span class="icon">Stock</span></a>
                </li>
                <li>
                    <a href="offer.php"><span class="icon"><ion-icon name="pricetags-outline"></ion-icon></span><span class="icon">Offer</span></a>
                </li>
                <li>
                    <a href="service.php"><span class="icon"><ion-icon name="settings-outline"></ion-icon></span><span class="icon">Services</span></a>
                </li>
                <li>
                    <a href="customer.php"><span class="icon"><ion-icon name="people-circle-outline"></ion-icon></span><span class="icon">Customer</span></a>
                </li>
                <li>
                    <a href="supplier.php"><span class="icon"><ion-icon name="swap-horizontal-outline"></ion-icon></span><span class="icon">Supplier</span></a>
                </li>
                <li>
                    <a href="booking.php"><span class="icon"><ion-icon name="calendar-outline"></ion-icon></span><span class="icon">Booking</span></a>
                </li>
                <li>
                    <a href="order.php"><span class="icon"><ion-icon name="cart-outline"></ion-icon></span><span class="icon">Orders</span></a>
                </li>
                <li>
                    <a href="complaint.php"><span class="icon"><ion-icon name="chatbubbles-outline"></ion-icon></span><span class="icon">Complaint</span></a>
                </li>
            <?php endif; ?>
            <hr>
            <li>
                <a href="/Project/logout.php"><span class="icon"><ion-icon name="log-out-outline"></ion-icon></span><span class="icon">Log Out</span></a>
            </li>
        </ul>
    </div>
</body>

</html>