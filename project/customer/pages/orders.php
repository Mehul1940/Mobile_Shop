<?php
session_start();
require_once "../header.php";
$con = new mysqli('localhost', 'root', '', 'project');

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: /login.php");
    exit;
}

$customer_id = $_SESSION['CUSTOMER_ID'];

// Fetch orders and order items along with product name from the database
$query = "SELECT o.ORDER_ID, o.DELIVERY_ADDRESS, o.DELIVERY_STATUS, o.ORDER_DATE, p.PRODUCT_NAME, oi.QUANTITY, oi.PRICE 
          FROM orders o
          LEFT JOIN order_items oi ON o.ORDER_ID = oi.ORDER_ID
          LEFT JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID
          WHERE o.CUSTOMER_ID = ?";
$stmt = $con->prepare($query);

// Check if the query was prepared successfully
if (!$stmt) {
    die("Error preparing the query: " . $con->error);
}

$stmt->bind_param("i", $customer_id);
$stmt->execute();
$orders_result = $stmt->get_result();

// Fetch service bookings including the address
$query_services = "SELECT booking_id, customer_name, service_type, booking_date, booking_time, estimate, status, address 
                   FROM service_bookings 
                   WHERE customer_email = (SELECT email FROM customer WHERE CUSTOMER_ID = ? )";
$stmt_services = $con->prepare($query_services);

if (!$stmt_services) {
    die("Error preparing the query for services: " . $con->error);
}

$stmt_services->bind_param("i", $customer_id);
$stmt_services->execute();
$services_result = $stmt_services->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilkanth Mobiles - Orders & Services</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/project/customer/style/style.css">
    <style>
        body {
            background-color: #f9f9f9;
            color: #333;
            font-family: Arial, sans-serif;
        }
       body .container {
            display: flex;
            max-width:1310px;
            margin: 2rem auto;
            background: #fff;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 20px;
        }
        .mb-3 {
            margin-bottom: 1rem !important;
            font-size: 20px;
            gap: 12px;
            padding-left:20px;
        }
        input[type="radio"] {
            margin-right: 8px;
            transform: scale(1.2);
        }
        label {
            font-size: 18px;
            font-weight: 500;
            cursor: pointer;
            margin-right: 16px;
        }
        .table>thead {
            vertical-align: bottom;
            font-size: 18px;
            background-color: #191970;
            color: white;
        }
        .table>tr {
            vertical-align: bottom;
            font-size: 18px;
        }
        .orders-table {
                 width: 100%;
                 border-collapse: collapse;
                 padding-left: 20px;
                  margin-left: 20px;
                }
        #ordersTable h2{
            padding-left:20px;
        }
        #servicesTable h2{
            padding-left:20px;
        }
        .orders-table th, .orders-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .orders-table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .orders-table .table .table-bordered{
                padding-left:20px;
        }
        .status {
            font-weight: bold;
        }
        .status.Completed {
            color: green;
        }
        .status.In-Progress {
            color: blue;
        }
        .status.Delayed {
            color: orange;
        }
        .status.Canceled {
            color: red;
        }
        .cancel-btn {
            background: midnightblue;
            color: white;
            padding: 8px 12px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            font-size: 15px;
            margin-top: 15px;
            margin-left: 20px;
            padding-left:20px;
        }
        .cancel-btn:disabled {
            background: gray;
            cursor: not-allowed;
        }
        .cancel-btn:hover {
            background: #003366;
        }
        .no-data-message {
            font-size: 18px;
            color: #555;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-4">
        <?php require_once "../sidebar.php"; ?>
        <div class="content">
            <div class="mb-3">
                <input type="radio" id="orders" name="viewOption" value="orders" checked>
                <label for="orders">Orders</label>
                <input type="radio" id="services" name="viewOption" value="services">
                <label for="services">Services</label>
            </div>
            
            <!-- Orders Table -->
            <div id="ordersTable">
                <h2>Your Orders</h2>
                <?php if ($orders_result->num_rows > 0) { ?>
                <table class="orders-table table table-bordered">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Date Purchased</th>
                            <th>Delivery Address</th>
                            <th>Status</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        while ($row = $orders_result->fetch_assoc()) { ?>
                            <tr>
                                <td><?= $row['ORDER_ID']; ?></td>
                                <td><?= date("F d, Y", strtotime($row['ORDER_DATE'])); ?></td>
                                <td><?= htmlspecialchars($row['DELIVERY_ADDRESS']); ?></td>
                                <td class="status <?= htmlspecialchars($row['DELIVERY_STATUS']); ?>">
                                    <?= htmlspecialchars($row['DELIVERY_STATUS']); ?>
                                </td>
                                <td><?= htmlspecialchars($row['PRODUCT_NAME']); ?></td> <!-- Show product name -->
                                <td><?= $row['QUANTITY']; ?></td>
                                <td>₹<?= number_format($row['PRICE'] * $row['QUANTITY'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
                <button class="cancel-btn" id="cancelOrderBtn">Cancel Order</button>
                <?php } else { ?>
                <p class="no-data-message">No orders found.</p>
                <?php } ?>
            </div>
 <!-- Services Table -->
<div id="servicesTable" style="display: none;">
    <h2>Your Service Bookings</h2>
    <?php if ($services_result->num_rows > 0) { ?>
    <table class="orders-table table table-bordered">
        <thead>
            <tr>
                <th>Booking ID</th>
                <th>Customer Name</th>
                <th>Address</th> <!-- Address column moved here -->
                <th>Service Type</th>
                <th>Booking Date</th>
                <th>Booking Time</th>
                <th>Estimate</th> <!-- New column for estimate -->
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $services_result->fetch_assoc()) { ?>
                <tr>
                    <td><?= $row['booking_id']; ?></td>
                    <td><?= htmlspecialchars($row['customer_name']); ?></td>
                    <td><?= htmlspecialchars($row['address']); ?></td> <!-- Address displayed here -->
                    <td><?= htmlspecialchars($row['service_type']); ?></td>
                    <td>
                        <?php
                        // Display booking_date only if status is not "Rejected"
                        if ($row['status'] !== 'Rejected') {
                            echo date("F d, Y", strtotime($row['booking_date']));
                        } else {
                            echo ''; // Show nothing if status is "Rejected"
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        // Display booking_time only if status is not "Rejected"
                        if ($row['status'] !== 'Rejected') {
                            echo $row['booking_time'];
                        } else {
                            echo ''; // Show nothing if status is "Rejected"
                        }
                        ?>
                    </td>
                    <td>₹<?= number_format($row['estimate'], 2); ?></td> <!-- Display estimate -->
                    <td class="status <?= htmlspecialchars($row['status']); ?>">
                        <?= htmlspecialchars($row['status']); ?>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
    <button class="cancel-btn" id="cancelServiceBtn">Cancel Service</button>
    <?php } else { ?>
    <p class="no-data-message">No services found.</p>
    <?php } ?>
</div>
           
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="viewOption"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                document.getElementById('ordersTable').style.display = this.value === 'orders' ? 'block' : 'none';
                document.getElementById('servicesTable').style.display = this.value === 'services' ? 'block' : 'none';
            });
        });

        document.getElementById('cancelOrderBtn').addEventListener('click', function () {
            if (confirm("Are you sure you want to cancel your order?")) {
                // Redirect to ordercancel.php
                window.location.href = "ordercancel.php";
            }
        });

        document.getElementById('cancelServiceBtn').addEventListener('click', function () {
            if (confirm("Are you sure you want to cancel your service booking?")) {
                // Redirect to ordercancel.php (or another page for service cancellation)
                window.location.href = "ordercancel.php";
            }
        });
    </script>

    <?php require_once "../footer.php"; ?>
</body>
</html>

<?php 
$con->close();
?>