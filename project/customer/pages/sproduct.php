<?php
// Connect to the database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "project";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
session_start();

// Handle review form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
  if (!isset($_SESSION['CUSTOMER_ID'])) {
    $_SESSION['message'] = "Please log in to submit a review.";
  } else {
    $product_name = $conn->real_escape_string($_POST['product_name']);
    $user_name = $conn->real_escape_string($_POST['user_name']);
    $rating = intval($_POST['rating']);
    $comment = $conn->real_escape_string($_POST['comment']);

    if ($rating < 1 || $rating > 5) {
      $_SESSION['message'] = "Rating must be between 1 and 5.";
    } else {
      $sql_insert_review = "INSERT INTO product_reviews (product_name, user_name, rating, comment) 
                            VALUES (?, ?, ?, ?)";
      $stmt_insert_review = $conn->prepare($sql_insert_review);
      $stmt_insert_review->bind_param("ssis", $product_name, $user_name, $rating, $comment);

      if ($stmt_insert_review->execute()) {
        $_SESSION['message'] = "Review submitted successfully! Redirecting to homepage...";
        echo "<script>
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000);
              </script>";
      } else {
        $_SESSION['message'] = "Error submitting review: " . $stmt_insert_review->error;
      }
      $stmt_insert_review->close();
    }
  }
}
// Get the product ID from the URL parameter
$product_id = $_GET['id'];

// Fetch the product details from the database
$sql = "SELECT p.*, b.BRAND_NAME FROM product p
        LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
        WHERE p.PRODUCT_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$product = $result->fetch_assoc();

// Fetch product details (images and description)
$sql_details = "SELECT * FROM product_details WHERE PRODUCT_ID = ?";
$stmt_details = $conn->prepare($sql_details);
$stmt_details->bind_param("i", $product_id);
$stmt_details->execute();
$result_details = $stmt_details->get_result();
$product_details = $result_details->fetch_assoc();

// Fetch product variants
$sql_variants = "SELECT * FROM product_variants WHERE PRODUCT_ID = ?";
$stmt_variants = $conn->prepare($sql_variants);
$stmt_variants->bind_param("i", $product_id);
$stmt_variants->execute();
$result_variants = $stmt_variants->get_result();
$product_variants = $result_variants->fetch_all(MYSQLI_ASSOC);

// Check if product has variants
$has_variants = count($product_variants) > 0;

// Fetch related products with their images from product_details and offer details
$sql_related = "SELECT p.*, b.BRAND_NAME, pd.IMG, o.DISCOUNT_RATE
                FROM product p
                LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
                LEFT JOIN product_details pd ON p.PRODUCT_ID = pd.PRODUCT_ID
                LEFT JOIN offer o ON p.PRODUCT_ID = o.PRODUCT_ID AND CURDATE() BETWEEN o.START_DATE AND o.END_DATE
                WHERE p.CATEGORY_ID = ? AND p.PRODUCT_ID != ? LIMIT 4";
$stmt_related = $conn->prepare($sql_related);
$stmt_related->bind_param("ii", $product['CATEGORY_ID'], $product_id);
$stmt_related->execute();
$related_result = $stmt_related->get_result();
$related_products = $related_result->fetch_all(MYSQLI_ASSOC);

// Fetch product reviews
$sql_reviews = "SELECT * FROM product_reviews WHERE product_name = ? ORDER BY review_date DESC";
$stmt_reviews = $conn->prepare($sql_reviews);
$stmt_reviews->bind_param("s", $product['PRODUCT_NAME']);
$stmt_reviews->execute();
$result_reviews = $stmt_reviews->get_result();
$reviews = $result_reviews->fetch_all(MYSQLI_ASSOC);

// Close connections
$stmt->close();
$stmt_details->close();
$stmt_variants->close();
$stmt_related->close();
$stmt_reviews->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nilkanth Mobiles - Product Details</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
  <link rel="stylesheet" href="/project/customer/style/style.css">
  <link rel="stylesheet" href="/project/customer/style/product.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

  <!-- Header Section -->
  <?php require_once "../header.php"; ?>

  <!-- Display session message if set -->
  <?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-warning">
      <?php echo $_SESSION['message']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
  <?php endif; ?>

  <!-- Product Details Section -->
  <section id="prodetails" class="section-p1">
    <div class="single-pro-img">
      <img src="<?php echo $product_details['IMG']; ?>" id="mainimg" alt="Main Product Image">

      <div class="small-img-group">
        <?php
        // Decode the JSON string from the database
        $small_imgs = json_decode($product_details['SMALL_IMG'], true);

        if (!empty($small_imgs) && is_array($small_imgs)) {
          foreach ($small_imgs as $small_img) {
            // Use the full path directly from the database
            $clean_img = htmlspecialchars(trim($small_img));
            echo '<div class="small-img-col">
                    <img src="' . $clean_img . '" 
                         class="small-img" 
                         alt="Thumbnail Image"
                         onerror="this.src=\'/project/default-product.jpg\'">
                  </div>';
          }
        } else {
          echo '<p>No additional images available</p>';
        }
        ?>
      </div>
    </div>

    <div class="single-pro-details">
      <h4><b>PRODUCT NAME:</b> <?php echo $product['PRODUCT_NAME']; ?> <br><br> <b>BRAND NAME:</b> <?php echo $product['BRAND_NAME']; ?></h4>
      <h2 id="product-price"><b></b> ₹<?php echo number_format($product['PRICE'], 2); ?></h2>

      <!-- Quantity Input -->
      <div class="quantity-selector mt-3">
        <label for="quantity"><strong>Quantity:</strong></label>
        <input type="number" value="1" min="1" id="quantity" class="form-control" />
      </div>

      <!-- Product Variants Section (only if variants exist) -->
      <?php if ($has_variants): ?>
        <div class="product-variants mt-3">
          <h4>Available Variants</h4>
          <div class="variants-container">
            <?php foreach ($product_variants as $variant) { ?>
              <div class="variant">
                <label>
                  <input type="radio" name="variant" value="<?php echo $variant['VARIANT_ID']; ?>"
                    data-price="<?php echo $variant['PRICE']; ?>" required>
                  <?php echo $variant['COLOR'] . ' - ' . $variant['RAM'] . ' - ' . $variant['STORAGE']; ?>
                </label>
              </div>
            <?php } ?>
          </div>
        </div>
      <?php endif; ?>

      <!-- Add to Cart Button -->
      <button class="btn btn-primary mt-3" onclick="handleAddToCart()">Add To Cart</button>

      <h4>Product Details</h4>
      <p><?php echo $product_details['DES']; ?></p>
    </div>
  </section>

  <!-- Related Products Section -->
  <section id="product1" class="section-p1">
    <h2>Related Products</h2>
    <p>Collection Of The Best Products And Accessories</p>
    <div class="pro-container">
      <?php foreach ($related_products as $rel_product) { ?>
        <div class="pro" onclick="window.location.href='sproduct.php?id=<?php echo $rel_product['PRODUCT_ID']; ?>'">
          <?php if (!is_null($rel_product['DISCOUNT_RATE'])): ?>
            <div class="offer-badge"><?php echo $rel_product['DISCOUNT_RATE']; ?>% OFF</div>
          <?php endif; ?>
          <img src="<?php echo $rel_product['IMG']; ?>" alt="<?php echo $rel_product['PRODUCT_NAME']; ?>" />
          <div class="des">
            <span><?php echo $rel_product['BRAND_NAME']; ?></span>
            <h5><?php echo $rel_product['PRODUCT_NAME']; ?></h5>
            <div class="star">
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
              <i class="fas fa-star"></i>
            </div>
            <h4>₹<?php echo number_format($rel_product['PRICE'], 2); ?></h4>
          </div>
          <a href="#" onclick="handleCartIconClick(event, <?php echo $rel_product['PRODUCT_ID']; ?>)">
            <i class="fas fa-shopping-cart cart"></i>
          </a>
        </div>
      <?php } ?>
    </div>
  </section>
  <!-- Review Form Section -->
  <section class="review-form">
    <h3>Write a Review</h3>
    <form method="POST" action="">
      <input type="hidden" name="product_name" value="<?php echo $product['PRODUCT_NAME']; ?>">
      <div class="form-group">
        <label for="user_name">Your Name:</label>
        <input type="text" id="user_name" name="user_name" required>
      </div>
      <div class="form-group">
        <label for="rating">Rating:</label>
        <select id="rating" name="rating" required>
          <option value="1">1 Star</option>
          <option value="2">2 Stars</option>
          <option value="3">3 Stars</option>
          <option value="4">4 Stars</option>
          <option value="5">5 Stars</option>
        </select>
      </div>
      <div class="form-group">
        <label for="comment">Comment:</label>
        <textarea id="comment" name="comment" rows="5" required></textarea>
      </div>
      <button type="submit" name="submit_review">Submit Review</button>
    </form>
  </section>

  <!-- Review Display Section -->
  <section class="review-section">
    <h2>Customer Reviews</h2>
    <?php if (empty($reviews)): ?>
      <p>No reviews yet. Be the first to review this product!</p>
    <?php else: ?>
      <?php foreach ($reviews as $review): ?>
        <div class="review-item">
          <div class="user-name"><?php echo $review['user_name']; ?></div>
          <div class="rating">
            <?php for ($i = 0; $i < $review['rating']; $i++): ?>
              <i class="fas fa-star"></i>
            <?php endfor; ?>
          </div>
          <div class="comment"><?php echo $review['comment']; ?></div>
          <div class="review-date"><?php echo date('F j, Y', strtotime($review['review_date'])); ?></div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
  <!-- Footer -->
  <?php require_once "../footer.php"; ?>

  <script>
    // Image Switching
    const mainimg = document.getElementById("mainimg");
    const smallimgs = document.getElementsByClassName("small-img");

    for (let i = 0; i < smallimgs.length; i++) {
      smallimgs[i].onclick = function() {
        mainimg.src = smallimgs[i].src;
      };
    }

    // Price Calculation
    const quantityInput = document.getElementById("quantity");
    const productPriceElement = document.getElementById("product-price");
    const basePrice = <?php echo $product['PRICE']; ?>;
    const variantInputs = document.querySelectorAll('input[name="variant"]');
    const hasVariants = <?php echo $has_variants ? 'true' : 'false'; ?>;

    let selectedVariantPrice = basePrice;

    // Handle variant selection (only if variants exist)
    if (hasVariants) {
      variantInputs.forEach(variantInput => {
        variantInput.addEventListener('change', function() {
          selectedVariantPrice = parseFloat(this.getAttribute('data-price'));
          updatePrice();
        });
      });
    }

    // Update price display
    function updatePrice() {
      const quantity = parseInt(quantityInput.value);
      const totalPrice = selectedVariantPrice * quantity;
      productPriceElement.textContent = `₹${totalPrice.toFixed(2)}`;
    }

    // Quantity change listener
    quantityInput.addEventListener("input", updatePrice);

    // Handle Add to Cart Button Click
    function handleAddToCart() {
      const isLoggedIn = <?php echo isset($_SESSION['CUSTOMER_ID']) ? 'true' : 'false'; ?>;

      if (!isLoggedIn) {
        window.location.href = "/project/customer/login.php";
        return;
      }

      let variantId = null;
      if (hasVariants) {
        const selectedVariant = document.querySelector('input[name="variant"]:checked');
        if (!selectedVariant) {
          alert("Please select a variant before adding to cart.");
          return;
        }
        variantId = selectedVariant.value;
      }

      const quantity = document.getElementById("quantity").value;
      let url = `cart.php?add_to_cart=<?php echo $product_id; ?>&quantity=${quantity}`;

      if (hasVariants) {
        url += `&variant_id=${variantId}`;
      }

      window.location.href = url;
    }

    // Handle Cart Icon Click in Related Products
    function handleCartIconClick(event, productId) {
      event.preventDefault();
      const isLoggedIn = <?php echo isset($_SESSION['CUSTOMER_ID']) ? 'true' : 'false'; ?>;

      if (!isLoggedIn) {
        window.location.href = "/project/customer/login.php";
      } else {
        window.location.href = `cart.php?add_to_cart=${productId}&quantity=1`;
      }
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>