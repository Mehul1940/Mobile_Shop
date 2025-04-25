<?php
session_start();

// Connect to your database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Allow unregistered users to see the shop page
$customer_id = isset($_SESSION['CUSTOMER_ID']) ? $_SESSION['CUSTOMER_ID'] : null;

// Redirect unauthorized users to the login page when they try to access the cart
if (isset($_GET['view_cart']) && !isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: project/customer/login.php");
    exit();
}

// Add to Cart Functionality
if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);

    // If the user is not logged in, set a session message and redirect
    if (!$customer_id) {
        $_SESSION['message'] = "Please log in to add items to the cart.";
        header("Location: shop.php"); // Redirect back to the shop page
        exit();
    }

    // If the user is logged in, get or create the cart for them
    if ($customer_id) {
        // Check if the customer has an active cart
        $cart_query = "SELECT CART_ID FROM cart WHERE CUSTOMER_ID = ?";
        $stmt = $conn->prepare($cart_query);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $cart_result = $stmt->get_result();

        if ($cart_result->num_rows > 0) {
            $cart_row = $cart_result->fetch_assoc();
            $cart_id = $cart_row['CART_ID'];
        } else {
            // Create a new cart for the user
            $insert_cart = "INSERT INTO cart (CUSTOMER_ID) VALUES (?)";
            $stmt = $conn->prepare($insert_cart);
            $stmt->bind_param("i", $customer_id);
            $stmt->execute();
            $cart_id = $stmt->insert_id;
        }
    }

    // Get product price
    $price_query = "SELECT PRICE FROM product WHERE PRODUCT_ID = ?";
    $stmt = $conn->prepare($price_query);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $price_result = $stmt->get_result();
    $price_row = $price_result->fetch_assoc();
    $price = $price_row['PRICE'];

    // Check if the product is already in the cart
    $check_query = "SELECT C_ID, QUANTITY FROM cart_details WHERE CART_ID = ? AND PRODUCT_ID = ?";
    $stmt = $conn->prepare($check_query);
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Update quantity if product already exists
        $row = $check_result->fetch_assoc();
        $new_quantity = $row['QUANTITY'] + 1;
        $update_query = "UPDATE cart_details SET QUANTITY = ? WHERE C_ID = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ii", $new_quantity, $row['C_ID']);
        $stmt->execute();
    } else {
        // Insert the product into cart_details
        $insert_details = "INSERT INTO cart_details (CART_ID, PRODUCT_ID, QUANTITY, PRICE) VALUES (?, ?, 1, ?)";
        $stmt = $conn->prepare($insert_details);
        $stmt->bind_param("iid", $cart_id, $product_id, $price);
        $stmt->execute();
    }

    header("Location: cart.php");
    exit();
}

// Get filter parameters
$selected_category = isset($_GET['category']) ? intval($_GET['category']) : null;
$selected_brand = isset($_GET['brand']) ? intval($_GET['brand']) : null;

// Base SQL query
$sql = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.CATEGORY_ID, p.BRAND_ID, p.PRICE, 
               d.IMG, d.SMALL_IMG, d.DES, b.BRAND_NAME, o.DISCOUNT_RATE
        FROM product p
        LEFT JOIN product_details d ON p.PRODUCT_ID = d.PRODUCT_ID
        LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
        LEFT JOIN offer o ON p.PRODUCT_ID = o.PRODUCT_ID 
            AND CURDATE() BETWEEN o.START_DATE AND o.END_DATE";

// Build WHERE conditions
$conditions = [];
$params = [];
$types = '';

if ($selected_category) {
    $conditions[] = "p.CATEGORY_ID = ?";
    $params[] = $selected_category;
    $types .= 'i';
}

if ($selected_brand) {
    $conditions[] = "p.BRAND_ID = ?";
    $params[] = $selected_brand;
    $types .= 'i';
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Execute query
if (!empty($params)) {
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}

// Fetch filter options
$brands = $conn->query("SELECT BRAND_ID, BRAND_NAME FROM brand")->fetch_all(MYSQLI_ASSOC);
$categories = $conn->query("SELECT CATEGORY_ID, CATEGORY_NAME FROM categories")->fetch_all(MYSQLI_ASSOC);

$products = $result->num_rows > 0 ? $result->fetch_all(MYSQLI_ASSOC) : [];
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Nilkanth Mobiles - Shop</title>
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <link rel="stylesheet" href="/project/customer/style/shop.css" />
</head>

<body>
    <?php require_once "../header.php"; ?>

    <!-- Display session message if set -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-warning text-center">
            <?php echo $_SESSION['message']; ?>
        </div>
        <?php unset($_SESSION['message']); // Clear the message after displaying 
        ?>
        <script>
            // Redirect to login page after 3 seconds
            setTimeout(function() {
                window.location.href = "/project/customer/login.php";
            }, 3000); // 3000 milliseconds = 3 seconds
        </script>
    <?php endif; ?>

    <!-- Hero Section -->
    <section id="page-header">
        <h2 class="text-white">#UpgradeWithNilkanth</h2>
        <p class="text-white">Explore Our Wide Range Of Smartphones And Accessories</p>
    </section>

    <!-- Filter Section -->
    <form action="shop.php" method="get" class="frm">
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['CATEGORY_ID'] ?>" <?= $selected_category == $category['CATEGORY_ID'] ? 'selected' : '' ?>>
                    <?= $category['CATEGORY_NAME'] ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="brand">
            <option value="">All Brands</option>
            <?php foreach ($brands as $brand): ?>
                <option value="<?= $brand['BRAND_ID'] ?>" <?= $selected_brand == $brand['BRAND_ID'] ? 'selected' : '' ?>>
                    <?= $brand['BRAND_NAME'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit">Filter</button>
    </form>

    <!-- Product Listing -->
    <section id="product1" class="section-p1">
        <button class="scroll-btn scroll-left">
            <i class="fas fa-chevron-left"></i>
        </button>
        <div class="pro-container">
            <?php foreach ($products as $product) { ?>
                <div class="pro" onclick="window.location.href='sproduct.php?id=<?php echo $product['PRODUCT_ID']; ?>'">
                    <?php if (!is_null($product['DISCOUNT_RATE'])): ?>
                        <div class="offer-badge"><?php echo $product['DISCOUNT_RATE']; ?>% OFF</div>
                    <?php endif; ?>
                    <img src="<?php echo $product['IMG']; ?>" alt="<?php echo $product['PRODUCT_NAME']; ?>" />
                    <div class="des">
                        <span><?php echo $product['BRAND_NAME']; ?></span>
                        <h5><?php echo $product['PRODUCT_NAME']; ?></h5>
                        <h4>₹<?php echo number_format($product['PRICE'], 2); ?></h4>
                    </div>
                    <a href="sproduct.php?id=<?php echo $product['PRODUCT_ID']; ?>'">
                        <i class="fas fa-shopping-cart cart"></i>
                    </a>
                </div>
            <?php } ?>
        </div>
        <button class="scroll-btn scroll-right">
            <i class="fas fa-chevron-right"></i>
        </button>
    </section>

    <?php require_once "../footer.php"; ?>

    <script>
        // Scroll functionality for product section
        let currentIndex = 0;
        const products = document.querySelectorAll(".pro");
        const productsPerScroll = 8;

        function showProducts(index) {
            products.forEach((product) => {
                product.style.display = "none";
            });

            for (let i = index; i < index + productsPerScroll && i < products.length; i++) {
                products[i].style.display = "block";
            }
        }

        document.querySelector(".scroll-left").addEventListener("click", () => {
            if (currentIndex > 0) {
                currentIndex -= productsPerScroll;
            } else {
                currentIndex = 0;
            }
            showProducts(currentIndex);
        });

        document.querySelector(".scroll-right").addEventListener("click", () => {
            if (currentIndex + productsPerScroll < products.length) {
                currentIndex += productsPerScroll;
            } else {
                currentIndex = Math.floor(products.length / productsPerScroll) * productsPerScroll;
            }
            showProducts(currentIndex);
        });

        showProducts(currentIndex);
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/project/customer/script/script.js"></script>
</body>

</html>