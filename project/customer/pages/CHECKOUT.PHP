<?php
session_start();
$con = new mysqli('localhost', 'root', '', 'project'); // Database connection

// Ensure the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: login.php");
    exit();
}

// Fetch customer details from the database
$customer_id = $_SESSION['CUSTOMER_ID'];
$query = "SELECT * FROM customer WHERE CUSTOMER_ID = $customer_id";
$stmt = $con->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Fetch cart items from session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Calculate the subtotal from the cart session
$subtotal = 0;
foreach ($cart_items as $item) {
    $subtotal += $item['discounted_price'] * $item['quantity'];
}

// Store the subtotal in the session
$_SESSION['subtotal'] = $subtotal;

// Fetch cart items from session
$cart_items = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
$cart_total = 0;

foreach (array_keys($cart_items) as $key) {
    $id = $cart_items[$key]['product_id'];
    $query = "SELECT * FROM offer WHERE PRODUCT_ID = $id";
    $stmt = $con->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $offer = $result->fetch_assoc();
        $discount_rate = $offer['DISCOUNT_RATE'];

        $discount_amount = ($cart_items[$key]['price'] * $discount_rate) / 100;
        $cart_items[$key]['discount'] = $discount_amount;
        $cart_items[$key]['total'] = $cart_items[$key]['price'] - $discount_amount;
    } else {
        $cart_items[$key]['discount'] = 0;
        $cart_items[$key]['total'] = $cart_items[$key]['price'];
    }
}

// Calculate the total price of the cart
foreach ($cart_items as $item) {
    $cart_total += $item['price'] * $item['quantity'] - $item['discount'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #checkout {
            background-color: white;
            margin: 30px auto;
            padding: 30px;
            max-width: 800px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        #checkout h2 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
            color: #333;
        }

        #checkout table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        #checkout table th,
        #checkout table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        #checkout table th {
            background-color: #f4f4f4;
        }

        #checkout .form-group {
            margin-bottom: 20px;
        }

        #checkout .form-group label {
            display: block;
            font-size: 1rem;
            margin-bottom: 0.5rem;
            color: #666;
        }

        #checkout .btn-primary {
            width: 100%;
            font-size: 1.2rem;
            padding: 12px;
            background-color: midnightblue;
            border: none;
        }

        #checkout .btn-primary:hover {
            background-color: #0056b3;
        }

        .total-price {
            font-size: 1.5rem;
            font-weight: bold;
            text-align: right;
            margin-top: 10px;
        }

        .address-options {
            margin-bottom: 20px;
        }

        .address-options label {
            margin-right: 20px;
        }
    </style>

</head>

<body>

    <?php require_once "../header.php"; ?>

    <section id="checkout">
        <h2>Checkout</h2>
        <form action="place_order.php" method="POST">
            <h3>Order Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Variant</th>
                        <th>Price</th>
                        <th>Discount</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name']) ?></td>
                            <td><?= htmlspecialchars($item['color'] . ' - ' . $item['ram'] . ' - ' . $item['storage']) ?></td>
                            <td>₹<?= number_format($item['price'], 2) ?></td>
                            <td>₹<?= number_format($item['discount'], 2) ?></td>
                            <td><?= number_format($item['quantity']) ?></td>
                            <td>₹<?= number_format($item['total'] * $item['quantity'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <h3 class="total-price">Total: ₹<?= number_format($_SESSION['subtotal'], 2) ?></h3>

            <div class="address-options">
                <label>
                    <input type="radio" name="address_option" value="profile_address" checked onchange="toggleAddressField()"> Profile Address
                </label>
                <label>
                    <input type="radio" name="address_option" value="new_address" id="new_address" onchange="toggleAddressField()"> New Address
                </label>
            </div>

            <div id="profile_address_field">
                <div class="form-group">
                    <label for="profile_address">Profile Address</label>
                    <textarea id="profile_address" name="profile_address" class="form-control" rows="3" readonly><?= htmlspecialchars($customer['ADDRESS']) ?></textarea>
                </div>
            </div>

            <div id="delivery_address_field" style="display: none;">
                <div class="form-group">
                    <label for="delivery_address">Delivery Address</label>
                    <textarea id="delivery_address" name="delivery_address" class="form-control" rows="3"></textarea>
                </div>
            </div>

            <input type="hidden" name="cart_total" value="<?= $_SESSION['subtotal'] ?>">
            <input type="hidden" name="cart_items" value="<?= base64_encode(serialize($cart_items)) ?>">

            <button type="submit" class="btn btn-primary">Place Order</button>
        </form>
    </section>

    <?php require_once "../footer.php"; ?>
    <script>
        function toggleAddressField() {
            var newAddressRadio = document.getElementById('new_address');
            var deliveryAddressField = document.getElementById('delivery_address_field');
            var profileAddressField = document.getElementById('profile_address_field');

            if (newAddressRadio.checked) {
                deliveryAddressField.style.display = 'block';
                profileAddressField.style.display = 'none';
            } else {
                deliveryAddressField.style.display = 'none';
                profileAddressField.style.display = 'block';
            }
        }

        // Initialize the address fields based on the default selected option
        window.onload = function() {
            toggleAddressField();
        };
    </script>
</body>

</html>