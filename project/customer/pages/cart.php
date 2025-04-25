<?php
session_start();

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

// Check if the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
  $_SESSION['message'] = "Please login to add products to your cart.";
  header("Location: /project/customer/login.php");
  exit();
}

// Handle adding product to cart
if (isset($_GET['add_to_cart'])) {
  $product_id = (int)$_GET['add_to_cart'];
  $quantity = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;
  $variant_id = isset($_GET['variant_id']) ? (int)$_GET['variant_id'] : null;

  // Fetch product details including variant (if exists) and offer
  if ($variant_id) {
    $sql = "SELECT p.*, b.BRAND_NAME, v.PRICE as VARIANT_PRICE, v.COLOR, v.RAM, v.STORAGE, o.DISCOUNT_RATE 
            FROM product p
            LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
            LEFT JOIN product_variants v ON p.PRODUCT_ID = v.PRODUCT_ID
            LEFT JOIN offer o ON p.PRODUCT_ID = o.PRODUCT_ID AND CURDATE() BETWEEN o.START_DATE AND o.END_DATE
            WHERE p.PRODUCT_ID = ? AND v.VARIANT_ID = ?";
  } else {
    $sql = "SELECT p.*, b.BRAND_NAME, p.PRICE as VARIANT_PRICE, o.DISCOUNT_RATE 
            FROM product p
            LEFT JOIN brand b ON p.BRAND_ID = b.BRAND_ID
            LEFT JOIN offer o ON p.PRODUCT_ID = o.PRODUCT_ID AND CURDATE() BETWEEN o.START_DATE AND o.END_DATE
            WHERE p.PRODUCT_ID = ?";
  }

  $stmt = $conn->prepare($sql);
  if ($variant_id) {
    $stmt->bind_param("ii", $product_id, $variant_id);
  } else {
    $stmt->bind_param("i", $product_id);
  }
  $stmt->execute();
  $result = $stmt->get_result();
  $product = $result->fetch_assoc();

  if ($product) {
    // Calculate discounted price if offer exists
    $discounted_price = $product['VARIANT_PRICE'];
    if ($product['DISCOUNT_RATE']) {
      $discounted_price = $product['VARIANT_PRICE'] * (1 - $product['DISCOUNT_RATE'] / 100);
    }

    // Add product to cart session
    $cart_item = [
      'product_id' => $product_id,
      'variant_id' => $variant_id,
      'product_name' => $product['PRODUCT_NAME'],
      'brand_name' => $product['BRAND_NAME'],
      'price' => $product['VARIANT_PRICE'],
      'discounted_price' => $discounted_price,
      'quantity' => $quantity,
      'color' => $variant_id ? $product['COLOR'] : 'Default',
      'ram' => $variant_id ? $product['RAM'] : 'N/A',
      'storage' => $variant_id ? $product['STORAGE'] : 'N/A',
      'discount_rate' => $product['DISCOUNT_RATE']
    ];

    if (!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = [];
    }

    // Check if the product variant is already in the cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
      if ($item['product_id'] == $product_id && $item['variant_id'] == $variant_id) {
        $item['quantity'] += $quantity;
        $found = true;
        break;
      }
    }

    if (!$found) {
      $_SESSION['cart'][] = $cart_item;
    }

    // Insert into the database
    $customer_id = $_SESSION['CUSTOMER_ID'];
    $status = 'Active';

    // Check if the user already has a cart
    $cart_sql = "SELECT CART_ID FROM cart WHERE CUSTOMER_ID = ? AND STATUS = 'Active'";
    $cart_stmt = $conn->prepare($cart_sql);
    $cart_stmt->bind_param("i", $customer_id);
    $cart_stmt->execute();
    $cart_result = $cart_stmt->get_result();

    if ($cart_result->num_rows > 0) {
      // Use the existing cart
      $cart_row = $cart_result->fetch_assoc();
      $cart_id = $cart_row['CART_ID'];
    } else {
      // Create a new cart
      $cart_sql = "INSERT INTO cart (CUSTOMER_ID, STATUS) VALUES (?, ?)";
      $cart_stmt = $conn->prepare($cart_sql);
      $cart_stmt->bind_param("is", $customer_id, $status);
      $cart_stmt->execute();
      $cart_id = $cart_stmt->insert_id;
    }

    // Insert into cart_details table
    $cart_details_sql = "INSERT INTO cart_details (CART_ID, PRODUCT_ID, QUANTITY, PRICE, STATUS) VALUES (?, ?, ?, ?, ?)";
    $cart_details_stmt = $conn->prepare($cart_details_sql);
    $cart_details_stmt->bind_param("iiids", $cart_id, $product_id, $quantity, $discounted_price, $status);
    $cart_details_stmt->execute();

    $_SESSION['message'] = "Product added to cart successfully.";
  } else {
    $_SESSION['message'] = "Product not found.";
  }

  // Redirect back to the product page or cart page
  header("Location: cart.php");
  exit();
}

// Handle product removal from cart
if (isset($_GET['remove'])) {
  $remove_index = (int)$_GET['remove'];
  if (isset($_SESSION['cart'][$remove_index])) {
    unset($_SESSION['cart'][$remove_index]); // Remove the item from the cart array
    $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
    $_SESSION['message'] = "Product removed from cart.";
  } else {
    $_SESSION['message'] = "Invalid product index.";
  }
  header("Location: cart.php");
  exit();
}

// Handle quantity update
if (isset($_POST['update_quantity'])) {
  $index = (int)$_POST['index'];
  $new_quantity = (int)$_POST['quantity'];

  if (isset($_SESSION['cart'][$index])) {
    if ($new_quantity > 0) {
      $_SESSION['cart'][$index]['quantity'] = $new_quantity;
      $_SESSION['message'] = "Quantity updated successfully.";
    } else {
      unset($_SESSION['cart'][$index]); // Remove the item if quantity is 0
      $_SESSION['cart'] = array_values($_SESSION['cart']); // Re-index the array
      $_SESSION['message'] = "Product removed from cart.";
    }
  } else {
    $_SESSION['message'] = "Invalid product index.";
  }
  header("Location: cart.php");
  exit();
}

// Fetch cart items from session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Nilkanth Mobiles - Cart</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
  <link rel="stylesheet" href="/project/customer/style/style.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    /* Enhanced Cart Styles with Animations */
    :root {
      --primary-color: #4a90e2;
      --secondary-color: #28a745;
      --text-color: #333;
      --background-color: #f4f4f6;
      --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    body {
      font-family: 'Inter', 'Arial', sans-serif;
      background-color: var(--background-color);
      color: var(--text-color);
      line-height: 1.6;
      transition: background-color 0.3s ease;
    }

    .cart-container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 20px;
      animation: fadeIn 0.5s ease-out;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .cart-table {
      width: 100%;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
      background-color: white;
      transition: all 0.3s ease;
    }

    .cart-table thead {
      background-color: var(--primary-color);
      color: white;
    }

    .cart-table tr {
      transition: background-color 0.2s ease;
    }

    .cart-table tr:hover {
      background-color: rgba(74, 144, 226, 0.05);
    }

    .quantity-input {
      width: 70px;
      padding: 6px;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-align: center;
      transition: all 0.3s ease;
    }

    .quantity-input:focus {
      outline: none;
      border-color: var(--primary-color);
      box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    }

    .remove-item {
      color: #dc3545;
      transition: transform 0.2s ease, color 0.2s ease;
    }

    .remove-item:hover {
      color: #bd2130;
      transform: scale(1.2) rotate(5deg);
    }

    .old-price {
      color: #6c757d;
      margin-right: 10px;
      font-size: 0.9em;
    }

    .total-price {
      background-color: white;
      border-radius: 12px;
      padding: 20px;
      box-shadow: var(--card-shadow);
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-top: 20px;
      animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
      from {
        opacity: 0;
        transform: translateY(20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    .checkout-btn {
      background-color: var(--secondary-color);
      color: white;
      padding: 12px 25px;
      border-radius: 8px;
      text-decoration: none;
      transition: all 0.3s ease;
      display: inline-block;
      position: relative;
      overflow: hidden;
    }

    .checkout-btn::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(120deg, transparent, rgba(255, 255, 255, 0.3), transparent);
      transition: all 0.5s ease;
    }

    .checkout-btn:hover::before {
      left: 100%;
    }

    .checkout-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 4px 10px rgba(40, 167, 69, 0.3);
    }

    .alert {
      margin: 20px 0;
      padding: 15px;
      border-radius: 8px;
      animation: slideDown 0.4s ease-out;
    }

    @keyframes slideDown {
      from {
        opacity: 0;
        transform: translateY(-20px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    /* Responsive Design */
    @media (max-width: 768px) {
      .cart-table {
        font-size: 14px;
      }

      .total-price {
        flex-direction: column;
        align-items: stretch;
      }

      .checkout-btn {
        margin-top: 15px;
        width: 100%;
        text-align: center;
      }
    }
  </style>
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

  <!-- Cart Items Section -->
  <section id="cart" class="section-p1">
    <h2>Your Cart</h2>
    <div class="cart-container">
      <?php if (empty($cart_items)): ?>
        <p>Your cart is empty.</p>
      <?php else: ?>
        <table class="table cart-table">
          <thead>
            <tr>
              <th>Product</th>
              <th>Variant</th>
              <th>Price</th>
              <th>Quantity</th>
              <th>SubTotal</th>
              <th>Remove</th>
            </tr>
          </thead>
          <tbody>
            <?php $subtotal = 0; ?>
            <?php foreach ($cart_items as $index => $item): ?>
              <tr>
                <td><?php echo $item['product_name']; ?></td>
                <td><?php echo $item['color'] . ' - ' . $item['ram'] . ' - ' . $item['storage']; ?></td>
                <td>
                  <?php if ($item['discount_rate']): ?>
                    <span class="old-price">₹<?php echo number_format($item['price'], 2); ?></span>
                    <span>₹<?php echo number_format($item['discounted_price'], 2); ?></span>
                  <?php else: ?>
                    <span>₹<?php echo number_format($item['price'], 2); ?></span>
                  <?php endif; ?>
                </td>
                <td>
                  <form method="post" action="cart.php" style="display:inline;">
                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                    <!-- <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" style="width: 60px;"> -->
                    <!-- <button type="submit" name="update_quantity" class="btn btn-sm btn-primary">Update</button> -->
                    <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input" data-index="<?php echo $index; ?>" data-price="<?php echo $item['discounted_price']; ?>">

                  </form>
                </td>
                <!-- <td>₹<?php echo number_format($item['discounted_price'] * $item['quantity'], 2); ?></td> -->
                <td id="total-<?php echo $index; ?>" class="item-total">₹<?php echo number_format($item['discounted_price'] * $item['quantity'], 2); ?></td>

                <td><a href="cart.php?remove=<?php echo $index; ?>" class="remove-item"><i class="fas fa-times-circle"></i></a></td>
              </tr>
              <?php $subtotal += $item['discounted_price'] * $item['quantity']; ?>
            <?php endforeach; ?>
          </tbody>
        </table>

        <!-- Cart Subtotal -->
        <!-- <div class="total-price">
        <p>Subtotal: ₹<?php echo number_format($subtotal, 2); ?></p>
        <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
      </div> -->
        <div class="total-price">
          <p id="subtotal">Total: ₹<?php echo number_format($subtotal, 2); ?></p>
          <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>

      <?php endif; ?>
    </div>
  </section>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const quantityInputs = document.querySelectorAll('.quantity-input');
      const subtotalElement = document.getElementById('subtotal'); // Ensure this ID matches your subtotal element

      quantityInputs.forEach(input => {
        input.addEventListener('input', function() {
          const index = this.getAttribute('data-index');
          const newQuantity = parseInt(this.value);
          const price = parseFloat(this.getAttribute('data-price')); // Assuming each input has a data-price attribute

          if (newQuantity > 0) {
            // Update the total for this item
            const totalElement = document.getElementById(`total-${index}`);
            const newTotal = (price * newQuantity).toFixed(2);
            totalElement.textContent = `₹${newTotal}`;

            // Recalculate the subtotal
            let newSubtotal = 0;
            document.querySelectorAll('.item-total').forEach(item => {
              newSubtotal += parseFloat(item.textContent.replace('₹', ''));
            });
            subtotalElement.textContent = `Subtotal: ₹${newSubtotal.toFixed(2)}`;

            // Optionally, send the updated quantity to the server using AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'update_cart.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
              if (xhr.readyState === 4 && xhr.status === 200) {
                console.log('Cart updated successfully');
              }
            };
            xhr.send(`index=${index}&quantity=${newQuantity}`);
          }
        });
      });
    });
  </script>
  <!-- Footer -->
  <?php require_once "../footer.php"; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>