<?php
session_start();

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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nilkanth Mobiles</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/project/customer/style/style.css" />
  <link rel="stylesheet" href="/project/customer/style/shop.css" />
  <style>
    .carousel-inner .carousel-caption {
      position: absolute;
      right: 2%;
      bottom: 11.25rem;
      left: -50%;
      padding-top: 1.25rem;
      padding-bottom: 1.25rem;
      color: #fff;
      text-align: center;
    }

    .carousel-caption h4,
    .carousel-caption h2,
    .carousel-caption h1 {
      color: white;
    }

    .carousel-caption .btn {
      background-color: midnightblue;
    }

    .carousel-item img {
      height: 100%;
      object-fit: cover;
    }

    .carousel-item h1 {
      font-size: 60px;
    }

    .carousel-item p {
      font-size: 16px;
      color: burlywood;
    }

    /* CODE FOR SEARCH */
    /* Search Bar Styling */
    #search-wrapper {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem;
      position: relative;
    }

    #search-input {
      width: 200px;
      height: 35px;
      border: 1px solid #ccc;
      border-radius: 20px;
      padding: 0 1rem;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
    }

    #product1 .pro img {
      width: 100%;
      height: 200px;
      object-fit: contain;
      border-radius: 10px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    #search-input:focus {
      width: 300px;
      border-color: midnightblue;
      box-shadow: 0 0 5px rgba(0, 0, 255, 0.5);
    }

    #search-button {
      background-color: midnightblue;
      color: white;
      border: none;
      border-radius: 50%;
      width: 35px;
      height: 35px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      margin-left: -40px;
      transition: background-color 0.3s ease;
    }

    #search-button:hover {
      background-color: navy;
    }

    #search-button i {
      font-size: 16px;
    }

    .offer-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: red;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 12px;
      z-index: 1;
    }
  </style>
</head>

<body>
  <?php require_once "../HEADER.PHP"; ?>

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

  <!-- HERO Section -->
  <div id="carouselExampleCaptions" class="carousel slide carousel-fade" data-bs-ride="carousel">
    <div class="carousel-indicators">
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active"
        aria-current="true" aria-label="Slide 1"></button>
      <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1"
        aria-label="Slide 2"></button>

    </div>
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="/project/customer/assets/banners/vv.jpg" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h4>Trade-in-offer</h4>
          <h2>Super value deals</h2>
          <h1>On all products</h1>
          <p>And many more</p>
          <a href="/project/customer/pages/shop.php" class="btn btn-lg btn-primary">Shop Now</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="/project/customer/assets/banners/VVVVV.jpg" class="d-block w-100" alt="...">
        <div class="carousel-caption d-none d-md-block">
          <h4>Expert Repair Services</h4>
          <h2>Affordable Solutions</h2>
          <h1>For Mobile Issues</h1>
          <p>Get Your Phone Fixed Today!</p>
          <a href="/project/customer/pages/service.php#about" class="btn btn-lg btn-primary">Book Now</a>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>

  <!-- Features Section -->
  <section id="feature" class="section-p1">
    <div class="fe-box">
      <img src="/project/customer/assets/features/f1.png" alt="Fre shipping" />
      <h6>Free Shipping in nearby area</h6>
    </div>
    <div class="fe-box">
      <img src="/project/customer/assets/features/f2.png" alt="Online Order" />
      <h6>Online Order</h6>
    </div>
    <div class="fe-box">
      <img src="/project/customer/assets/features/f3.png" alt="Save Money" />
      <h6>Save Money</h6>
    </div>
    <div class="fe-box">
      <img src="/project/customer/assets/features/f4.png" alt="Offer" />
      <h6>Offers</h6>
    </div>
    <div class="fe-box">
      <img src="/project/customer/assets/features/f5.png" alt="Happy Sell" />
      <h6>Happy Sell</h6>
    </div>
    <div class="fe-box">
      <img src="/project/customer/assets/features/f6.png" alt="24/7 Support" />
      <h6>24/7 Support</h6>
    </div>
  </section>


  <!-- Product Listing -->
  <section id="product1" class="section-p1">
    <h2>Products</h2>
    <p>Collection of Latest Products</p>
    <div class="pro-container">
      <?php foreach ($products as $product) { ?>
        <div class="pro" onclick="window.location.href='sproduct.php?id=<?php echo $product['PRODUCT_ID']; ?>'">
          <?php if (!is_null($product['DISCOUNT_RATE'])): ?>
            <div class="offer-badge"><?php echo $product['DISCOUNT_RATE']; ?>% OFF</div>
          <?php endif; ?>
          <img src="<?php echo htmlspecialchars($product['P_IMG']); ?>" alt="<?php echo htmlspecialchars($product['PRODUCT_NAME']); ?>" />
          <div class="des">
            <span><?php echo htmlspecialchars($product['BRAND_NAME']); ?></span>
            <h5><?php echo htmlspecialchars($product['PRODUCT_NAME']); ?></h5>
            <h4>₹<?php echo number_format($product['PRICE'], 2); ?></h4>
          </div>
          <a href="sproduct.php?id=<?php echo $product['PRODUCT_ID']; ?>'">
            <i class="fas fa-shopping-cart cart"></i>
          </a>
        </div>
      <?php } ?>
    </div>
  </section>

  <?php include "../footer.php"; ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>