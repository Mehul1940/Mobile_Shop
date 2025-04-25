<?php

// Include database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle add to cart action
if (isset($_GET['product_id'])) {
    if (!isset($_SESSION['CUSTOMER_ID'])) {
        // Set a session message for unauthorized users
        $_SESSION['message'] = "Please log in to add items to the cart.";
        header("Location: index.php"); // Redirect back to the homepage or current page
        exit();
    }

    $customer_id = $_SESSION['CUSTOMER_ID'];
    $product_id = intval($_GET['product_id']);

    // Ensure there's a cart for the customer
    $get_cart_id = "SELECT CART_ID FROM cart WHERE CUSTOMER_ID = ?";
    $stmt = $conn->prepare($get_cart_id);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $cart_result = $stmt->get_result();

    if ($cart_result->num_rows > 0) {
        $cart_row = $cart_result->fetch_assoc();
        $cart_id = $cart_row['CART_ID'];
    } else {
        // Create a new cart
        $insert_cart = "INSERT INTO cart (CUSTOMER_ID) VALUES (?)";
        $stmt = $conn->prepare($insert_cart);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $cart_id = $stmt->insert_id;
    }

    // Get product price
    $get_price = "SELECT PRICE FROM product WHERE PRODUCT_ID = ?";
    $stmt = $conn->prepare($get_price);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $price_result = $stmt->get_result();
    $price_row = $price_result->fetch_assoc();
    $price = $price_row['PRICE'];

    // Check if product already exists in cart
    $check_cart = "SELECT C_ID, QUANTITY FROM cart_details WHERE CART_ID = ? AND PRODUCT_ID = ?";
    $stmt = $conn->prepare($check_cart);
    $stmt->bind_param("ii", $cart_id, $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Update quantity if product exists
        $row = $result->fetch_assoc();
        $new_quantity = $row['QUANTITY'] + 1;
        $update_cart = "UPDATE cart_details SET QUANTITY = ? WHERE C_ID = ?";
        $stmt = $conn->prepare($update_cart);
        $stmt->bind_param("ii", $new_quantity, $row['C_ID']);
        $stmt->execute();
    } else {
        // Insert new product into cart_details
        $insert_cart_details = "INSERT INTO cart_details (CART_ID, PRODUCT_ID, QUANTITY, PRICE) VALUES (?, ?, 1, ?)";
        $stmt = $conn->prepare($insert_cart_details);
        $stmt->bind_param("iid", $cart_id, $product_id, $price);
        $stmt->execute();
    }

    // Redirect to cart page
    header("Location: cart.php");
    exit();
}

// Fetch products with active offers
$sql_offers = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRICE, 
                      b.BRAND_NAME, pd.IMG AS P_IMG, pd.SMALL_IMG, pd.DES AS P_DES,
                      o.DISCOUNT_RATE
               FROM product p
               LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
               LEFT JOIN product_details pd ON p.PRODUCT_ID = pd.PRODUCT_ID
               INNER JOIN offer o ON p.PRODUCT_ID = o.PRODUCT_ID 
                   AND CURDATE() BETWEEN o.START_DATE AND o.END_DATE
               WHERE o.DISCOUNT_RATE IS NOT NULL";
$result_offers = $conn->query($sql_offers);

$offered_products = [];
if ($result_offers && $result_offers->num_rows > 0) {
    $offered_products = $result_offers->fetch_all(MYSQLI_ASSOC);
}

// Fetch additional random products to make up to 8 products
$total_products_needed = 8 - count($offered_products);
if ($total_products_needed > 0) {
    $sql_random = "SELECT p.PRODUCT_ID, p.PRODUCT_NAME, p.PRICE, 
                          b.BRAND_NAME, pd.IMG AS P_IMG, pd.SMALL_IMG, pd.DES AS P_DES,
                          o.DISCOUNT_RATE
                   FROM product p
                   LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
                   LEFT JOIN product_details pd ON p.PRODUCT_ID = pd.PRODUCT_ID
                   LEFT JOIN offer o ON p.PRODUCT_ID = o.PRODUCT_ID AND CURDATE() BETWEEN o.START_DATE AND o.END_DATE
                   WHERE p.PRODUCT_ID NOT IN (SELECT PRODUCT_ID FROM offer WHERE CURDATE() BETWEEN START_DATE AND END_DATE)
                   ORDER BY RAND()
                   LIMIT $total_products_needed";

    $result_random = $conn->query($sql_random);

    if ($result_random && $result_random->num_rows > 0) {
        $random_products = $result_random->fetch_all(MYSQLI_ASSOC);
        $products = array_merge($offered_products, $random_products);
    } else {
        $products = $offered_products;
    }
} else {
    $products = $offered_products;
}


?>
<style>
    :root {
        --primary-color: #6c5ce7;
        --secondary-color: #ff4757;
        --accent-color: #00b894;
        --text-dark: #2d3436;
        --text-light: #636e72;
        --border-radius: 16px;
        --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        --shadow-sm: 0 10px 30px rgba(0, 0, 0, 0.08);
        --shadow-lg: 0 20px 50px rgba(0, 0, 0, 0.15);
    }

    /* Card Container with Improved Grid Layout */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2.8rem;
        padding: 2.5rem;
        max-width: 1500px;
        margin: 0 auto;
    }

    /* Modern Card Design with Enhanced Effects */
    .pro {
        background: #ffffff;
        border-radius: var(--border-radius);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        position: relative;
        isolation: isolate;
        transform: translateY(30px);
        opacity: 0;
        will-change: transform, opacity;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-dark);
        text-align: center;
        margin-bottom: 0.5rem;
    }

    .section-subtitle {
        font-size: 1.1rem;
        font-weight: 400;
        text-align: center;
        color: var(--text-light);
        margin-bottom: 3rem;
        /* Add some space between the subtitle and products */
    }

    /* Card Entrance Animation with Staggered Timing */
    @keyframes cardEntrance {
        0% {
            opacity: 0;
            transform: translateY(50px) scale(0.9);
        }

        100% {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .pro {
        animation: cardEntrance 0.8s forwards cubic-bezier(0.13, 0.82, 0.32, 1);
    }

    .pro:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: var(--shadow-lg);
    }

    /* Enhanced Image Container */
    .pro-img-container {
        position: relative;
        padding: 2rem;
        background: linear-gradient(145deg, #f5f7fa 0%, #eef2f7 100%);
        overflow: hidden;
        transition: var(--transition);
    }

    .pro-img-container::after {
        content: '';
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at center,
                rgba(255, 255, 255, 0) 70%,
                rgba(0, 0, 0, 0.05) 100%);
        z-index: 1;
        opacity: 0;
        transition: var(--transition);
    }

    .pro:hover .pro-img-container::after {
        opacity: 1;
    }

    .pro img {
        width: 100%;
        height: 240px;
        object-fit: contain;
        transition: var(--transition);
        transform-origin: center;
        filter: drop-shadow(0 10px 15px rgba(0, 0, 0, 0.05));
    }

    .pro:hover img {
        transform: scale(1.12) translateY(-5px);
    }

    /* Enhanced Product Details Section */
    .product-details {
        padding: 1.8rem;
        position: relative;
        background: white;
        transition: var(--transition);
    }

    .pro:hover .product-details {
        background: linear-gradient(180deg, rgba(255, 255, 255, 1) 0%, rgba(250, 250, 255, 1) 100%);
    }

    .brand-name {
        font-size: 0.85rem;
        color: var(--text-light);
        letter-spacing: 0.8px;
        margin-bottom: 0.6rem;
        opacity: 0.8;
        font-weight: 500;
        text-transform: uppercase;
        transition: var(--transition);
    }

    .pro:hover .brand-name {
        color: var(--primary-color);
        opacity: 1;
    }

    .product-title {
        font-size: 1.2rem;
        color: var(--text-dark);
        margin-bottom: 1rem;
        line-height: 1.4;
        min-height: 3.2em;
        font-weight: 600;
        transition: var(--transition);
    }

    .pro:hover .product-title {
        color: #000;
    }

    /* Modernized Price Container */
    .price-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 1.8rem;
        transition: var(--transition);
    }

    .current-price {
        font-size: 1.5rem;
        color: var(--primary-color);
        font-weight: 700;
        position: relative;
        transition: var(--transition);
    }

    .pro:hover .current-price {
        transform: scale(1.05);
    }

    .original-price {
        font-size: 0.95rem;
        color: var(--text-light);
        text-decoration: line-through;
        margin-right: 0.75rem;
        transition: var(--transition);
    }

    /* Enhanced Cart Button Animation */
    .cart-btn {
        position: absolute;
        bottom: 1.5rem;
        right: 1.5rem;
        background: var(--primary-color);
        color: white;
        width: 48px;
        height: 48px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition);
        opacity: 0;
        transform: translateY(15px) scale(0.9);
        overflow: hidden;
        z-index: 2;
    }

    .cart-btn::before {
        content: '';
        position: absolute;
        inset: 0;
        background: var(--accent-color);
        transform: translateY(100%);
        transition: transform 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        z-index: -1;
    }

    .pro:hover .cart-btn {
        opacity: 1;
        transform: translateY(0) scale(1);
    }

    .cart-btn:hover {
        transform: translateY(-5px) scale(1.1);
        box-shadow: 0 10px 25px rgba(108, 92, 231, 0.4);
    }

    .cart-btn:hover::before {
        transform: translateY(0);
    }

    .cart-btn i {
        transition: var(--transition);
    }

    .cart-btn:hover i {
        transform: scale(1.2);
    }

    /* Improved Offer Badge with Animation */
    .offer-badge {
        position: absolute;
        top: 1.5rem;
        left: -4rem;
        background: var(--secondary-color);
        color: white;
        padding: 0.6rem 4rem;
        font-size: 0.9rem;
        font-weight: 600;
        transform: rotate(-45deg);
        box-shadow: 0 5px 15px rgba(255, 71, 87, 0.25);
        z-index: 5;
    }

    @keyframes badgePulse {

        0%,
        100% {
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.25);
            transform: rotate(-45deg) scale(1);
        }

        50% {
            box-shadow: 0 8px 20px rgba(255, 71, 87, 0.4);
            transform: rotate(-45deg) scale(1.05);
        }
    }

    .pro:hover .offer-badge {
        animation: badgePulse 2s infinite ease-in-out;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
        .product-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
    }

    @media (max-width: 992px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .product-grid {
            grid-template-columns: repeat(2, 1fr);
            padding: 1.5rem;
            gap: 1.5rem;
        }

        .pro img {
            height: 200px;
        }

        .product-title {
            font-size: 1.1rem;
            min-height: 2.8em;
        }

        .product-details {
            padding: 1.5rem;
        }
    }

    @media (max-width: 576px) {
        .product-grid {
            grid-template-columns: 1fr;
            max-width: 350px;
            margin: 0 auto;
        }
    }

    /* Staggered Animation for Cards */
    .pro:nth-child(1) {
        animation-delay: 0.1s;
    }

    .pro:nth-child(2) {
        animation-delay: 0.2s;
    }

    .pro:nth-child(3) {
        animation-delay: 0.3s;
    }

    .pro:nth-child(4) {
        animation-delay: 0.4s;
    }

    .pro:nth-child(5) {
        animation-delay: 0.5s;
    }

    .pro:nth-child(6) {
        animation-delay: 0.6s;
    }

    .pro:nth-child(7) {
        animation-delay: 0.7s;
    }

    .pro:nth-child(8) {
        animation-delay: 0.8s;
    }
</style>

<div class="product-container">
    <section id="products">
        <h2 class="section-title">Featured Products</h2>
        <p class="section-subtitle">Explore our latest collection</p>
        <div class="product-grid">
            <?php foreach ($products as $index => $product): ?>
                <div class="pro" style="animation-delay: <?= $index * 0.1 ?>s">
                    <?php if ($product['DISCOUNT_RATE'] > 0): ?>
                        <div class="offer-badge"><?= $product['DISCOUNT_RATE'] ?>% OFF</div>
                    <?php endif; ?>

                    <div class="pro-img-container">
                        <img src="<?= htmlspecialchars($product['P_IMG']) ?>"
                            alt="<?= htmlspecialchars($product['PRODUCT_NAME']) ?>">
                    </div>

                    <div class="product-details">
                        <span class="brand-name"><?= htmlspecialchars($product['BRAND_NAME']) ?></span>
                        <h3 class="product-title"><?= htmlspecialchars($product['PRODUCT_NAME']) ?></h3>

                        <div class="price-container">
                            <div>
                                <?php if ($product['DISCOUNT_RATE'] > 0): ?>
                                    <span class="original-price">
                                        ₹<?= number_format($product['PRICE'], 2) ?>
                                    </span>
                                <?php endif; ?>
                                <span class="current-price">
                                    ₹<?= number_format($product['PRICE'] * (1 - $product['DISCOUNT_RATE'] / 100), 2) ?>
                                </span>
                            </div>
                        </div>
                    </div>

                    <a href="add_to_cart.php?product_id=<?= $product['PRODUCT_ID'] ?>"
                        class="cart-btn"
                        aria-label="Add to Cart">
                        <i class="fas fa-shopping-cart"></i>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</div>