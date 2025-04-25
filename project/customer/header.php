<?php
// Start the session only if it's not already active
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$is_logged_in = isset($_SESSION['CUSTOMER_ID']);  // Check if the user is logged in

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <title>Nilkanth Mobiles</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <style>
        /* Style for the search input field with the icon inside */
        #search-wrapper {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        #search-input {
            width: 100%;
            padding: 10px 35px 10px 15px;
            /* Adjust padding to make space for icon */
            border-radius: 30px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        #search-button {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            border: none;
            background: none;
            cursor: pointer;
        }

        #search-button i {
            font-size: 18px;
            color: #888;
        }

        #search-button {
            background-color: midnightblue;
            color: #fff;
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            margin-left: -31px;
            right: 24px;
            transition: background-color 0.3s ease;
        }
    </style>
</head>

<body>

    <!-- Header Section -->
    <header id="header">
        <a href="/Project/customer/pages/index.php">
            <img src="/project/customer/assets/logo/1.png" alt="Nilkanth Mobiles Logo" class="logo" />
        </a>
        <div>
            <div>
                <ul id="navbar">
                    <li><a class="<?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="/project/customer/pages/index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu">
                            <?php
                            $con = mysqli_connect("localhost", "root", "", "project") or die("Couldn't connect");
                            $query = "SELECT CATEGORY_ID, CATEGORY_NAME FROM categories";
                            $result = mysqli_query($con, $query);

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<li><a class="dropdown-item" href="/project/customer/pages/shop.php?category=' . $row['CATEGORY_ID'] . '">' . htmlspecialchars($row['CATEGORY_NAME']) . '</a></li>';
                            }
                            ?>
                        </ul>
                    </li>
                    <li><a class="<?php echo ($current_page == 'shop.php') ? 'active' : ''; ?>" href="/project/customer/pages/shop.php">Shop</a></li>
                    <li><a class="<?php echo ($current_page == 'service.php') ? 'active' : ''; ?>" href="/project/customer/pages/service.php">Services</a></li>
                    <li><a class="<?php echo ($current_page == 'about.php') ? 'active' : ''; ?>" href="/project/customer/pages/about.php">AboutUs</a></li>
                    <li><a class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="/project/customer/pages/contact.php">Contact</a></li>

                    <?php if ($is_logged_in) { ?>
                        <li><a class="<?php echo ($current_page == 'cart.php') ? 'active' : ''; ?>" href="/project/customer/pages/cart.php"><i class="fal fa-shopping-cart"></i></a></li>
                        <li><a class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>" href="/project/customer/pages/profile.php"><i class="fal fa-user"></i></a></li>
                    <?php } ?>

                    <li id="search-wrapper">
                        <!-- Search Form -->
                        <form action="/project/customer/pages/search.php" method="get">
                            <input type="text" id="search-input" name="query" placeholder="Search products..." />
                            <button id="search-button" type="submit"><i class="fas fa-search"></i></button>
                        </form>

                    </li>

                    <?php if (!$is_logged_in) { ?>
                        <li>
                            <a class="<?php echo ($current_page == 'register.php') ? 'active' : ''; ?>" href="/project/customer/register.php"><i class="fal fa-user-plus"></i></a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </header>

    <script src="/project/customer/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>

</html>