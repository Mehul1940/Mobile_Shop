<?php require_once "/xampp/htdocs/Project/config/db.php" ?>
<?php require_once "/xampp/htdocs/Project/config/admincheck.php";
// Validate and sanitize date inputs
$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : null;

// Prepare SQL query with parameter placeholders
$sql = "SELECT o.ORDER_ID, o.CUSTOMER_ID, o.TOTAL_AMT, 
                o.DELIVERY_ADDRESS, o.DELIVERY_STATUS, o.ORDER_DATE, 
                c.CUSTOMER_NAME,
                GROUP_CONCAT(CONCAT(p.PRODUCT_NAME, ' (', oi.QUANTITY, ')') SEPARATOR ', ') AS products
         FROM orders o
         LEFT JOIN customer c ON o.CUSTOMER_ID = c.CUSTOMER_ID
         LEFT JOIN order_items oi ON o.ORDER_ID = oi.ORDER_ID
         LEFT JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID";

$params = [];
$types = '';

// Add date filter if provided
if ($start_date && $end_date) {
    $sql .= " WHERE o.ORDER_DATE BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= 'ss';
}

$sql .= " GROUP BY o.ORDER_ID";

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>
<?php require_once "/xampp/htdocs/Project/config/db.php" ?>
<?php require_once "/xampp/htdocs/Project/config/admincheck.php";
// Validate and sanitize date inputs
$start_date = !empty($_GET['start_date']) ? $_GET['start_date'] : null;
$end_date = !empty($_GET['end_date']) ? $_GET['end_date'] : null;

// Prepare SQL query with parameter placeholders
$sql = "SELECT o.ORDER_ID, o.CUSTOMER_ID, o.TOTAL_AMT, 
                o.DELIVERY_ADDRESS, o.DELIVERY_STATUS, o.ORDER_DATE, 
                c.CUSTOMER_NAME,
                GROUP_CONCAT(CONCAT(p.PRODUCT_NAME, ' (', oi.QUANTITY, ')') SEPARATOR ', ') AS products
         FROM orders o
         LEFT JOIN customer c ON o.CUSTOMER_ID = c.CUSTOMER_ID
         LEFT JOIN order_items oi ON o.ORDER_ID = oi.ORDER_ID
         LEFT JOIN product p ON oi.PRODUCT_ID = p.PRODUCT_ID";

$params = [];
$types = '';

// Add date filter if provided
if ($start_date && $end_date) {
    $sql .= " WHERE o.ORDER_DATE BETWEEN ? AND ?";
    $params[] = $start_date;
    $params[] = $end_date;
    $types .= 'ss';
}

$sql .= " GROUP BY o.ORDER_ID";

$stmt = $conn->prepare($sql);

if ($params) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();
$orders = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order</title>
    <link rel="stylesheet" href="/project/assets/css/modal.css">
    <link rel="stylesheet" href="/project/assets/css/style.css">
    <script src="/Project/assets/js/updateOrder.js" defer></script>
    <script src="/Project/assets/js/main.js" defer></script>
</head>

<body>
    <div class="container">
        <?php require_once "/xampp/htdocs/Project/admin/Nav.php" ?>

        <div class="main">
            <?php require_once "/xampp/htdocs/Project/admin/main.php" ?>

            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Order Management</h2>
                        <div class="filter-section">
                            <form method="GET" action="">
                                <label for="start_date">Start Date:</label>
                                <input type="date" id="start_date" name="start_date"
                                    value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">

                                <label for="end_date">End Date:</label>
                                <input type="date" id="end_date" name="end_date"
                                    value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">

                                <button type="submit" class="btn btn-primary">Filter</button>
                                <button type="button" id="reset-filter" class="btn btn-secondary">Reset</button>
                            </form>
                        </div>

                        <div class="mt-4 text-center">
                            <button id="print-btn" class="btn btn-warning">
                                <i class="fas fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>

                    <table>
                        <thead>
                            <tr>
                                <th>Order Id</th>
                                <th>Customer Name</th>
                                <th>Delivery Address</th>
                                <th>Order Date</th>
                                <th>Delivery Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($orders)) {
                                foreach ($orders as $row) {
                                    echo '<tr>
                                <td>' . htmlspecialchars($row["ORDER_ID"]) . '</td>
                                <td>' . htmlspecialchars($row["CUSTOMER_NAME"] ?? 'N/A') . '</td>
                                <td>' . htmlspecialchars($row["DELIVERY_ADDRESS"]) . '</td>
                                <td>' . htmlspecialchars($row["ORDER_DATE"]) . '</td>
                                <td>
                                    <span class="status-badge ' . (strtolower($row['DELIVERY_STATUS']) === 'completed' ? 'status-success' : 'status-warning') . '">
                                        ' . htmlspecialchars(ucfirst(strtolower($row['DELIVERY_STATUS']))) . '
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm update-btn"
                                            data-id="' . $row["ORDER_ID"] . '"
                                            data-customer="' . $row["CUSTOMER_ID"] . '"
                                            data-amount="' . $row["TOTAL_AMT"] . '"
                                            data-address="' . $row["DELIVERY_ADDRESS"] . '"
                                            data-status="' . $row["DELIVERY_STATUS"] . '">
                                        Update
                                    </button>
                                    
                                    <a href="/Project/admin/module/order_detail.php?order_id=' . $row["ORDER_ID"] . '" class="btn btn-primary view-details-btn">
                                        View Detail
                                    </a>
                                </td>
                            </tr>';
                                }
                            } else {
                                echo '<tr><td colspan="6" class="text-center">No orders found</td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Update Order Modal -->
            <div id="updateOrderModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Update Order</h2>
                        <span class="close-btn" id="closeModal">&times;</span>
                    </div>
                    <hr style=" margin-bottom: 15px;">
                    <form id="updateOrderForm" action="/Project/admin/update/update_order.php" method="post">
                        <div class="form-group">
                            <label for="order_id">Order ID</label>
                            <input type="text" id="order_id" name="order_id" readonly>
                        </div>
                        <div class="form-group">
                            <label for="customer_id">Customer ID</label>
                            <input type="text" id="customer_id" name="customer_id" readonly>
                        </div>
                        <div class="form-group" hidden>
                            <label for="total_amt">Total Amount</label>
                            <input type="number" id="total_amt" name="total_amt" readonly>
                        </div>
                        <div class="form-group">
                            <label for="delivery_address">Delivery Address</label>
                            <input type="text" id="delivery_address" name="delivery_address">
                        </div>
                        <div class="form-group">
                            <label for="delivery_status">Delivery Status</label>
                            <select id="delivery_status" name="delivery_status" required>
                                <option value="Pending">Pending</option>
                                <option value="Shipped">Shipped</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Print functionality
        document.getElementById('print-btn').addEventListener('click', () => {
            const printWindow = window.open('', '_blank');
            const startDate = document.getElementById('start_date').value;
            const endDate = document.getElementById('end_date').value;

            printWindow.document.write(`
                <html>
                <head>
                    <title>Order Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        h1 { color: #2a4c8d; border-bottom: 2px solid #eee; padding-bottom: 10px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f5f5f5; }
                        .status-badge { padding: 4px 8px; border-radius: 4px; font-size: 0.9em; }
                        .pending { background-color: #fff3cd; color: #856404; }
                        .processing { background-color: #cce5ff; color: #004085; }
                        .shipped { background-color: #d4edda; color: #155724; }
                        .delivered { background-color: #d1ecf1; color: #0c5460; }
                        .cancelled { background-color: #f8d7da; color: #721c24; }
                    </style>
                </head>
                <body>
                    <h1>Order Report</h1>
                    <p><strong>Date Range:</strong> ${startDate || 'All time'} - ${endDate || 'Present'}</p>
                    <p><strong>Generated:</strong> ${new Date().toLocaleString()}</p>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Customer</th>
                                <th>Products</th>
                                <th>Total</th>
                                <th>Address</th>
                                <th>Order Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${(() => {
                                let rows = '';
                                const orders = <?= json_encode($orders) ?>;
                                
                                orders.forEach(order => {
                                    rows += `
                                        <tr>
                                            <td>${order.ORDER_ID}</td>
                                            <td>${order.CUSTOMER_NAME || 'N/A'}</td>
                                            <td>${order.products || 'N/A'}</td>
                                            <td>${order.TOTAL_AMT}</td>
                                            <td>${order.DELIVERY_ADDRESS}</td>
                                            <td>${order.ORDER_DATE}</td>
                                            <td>
                                                <span class="status-badge ${order.DELIVERY_STATUS.toLowerCase()}">
                                                    ${order.DELIVERY_STATUS}
                                                </span>
                                            </td>
                                        </tr>
                                    `;
                                });
                                
                                return rows || '<tr><td colspan="7">No orders found</td></tr>';
                            })()}
                        </tbody>
                    </table>
                </body>
                </html>
            `);

            printWindow.document.close();
            setTimeout(() => printWindow.print(), 500);
        });

        // Reset filter
        document.getElementById('reset-filter').addEventListener('click', () => {
            window.location.href = window.location.pathname;
        });
    </script>
</body>

</html>