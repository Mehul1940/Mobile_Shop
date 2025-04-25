<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Get selected month from request (default is current month)
$selectedMonth = isset($_GET['month']) ? $_GET['month'] : date('Y-m');

// Fetch customers registered in the selected month
$customers = [];
$sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME, EMAIL, PHONE, ADDRESS, STATUS, 
        DATE_FORMAT(REGISTER_DATE, '%Y-%m') AS CUSTOMER_MONTH 
        FROM customer
        WHERE DATE_FORMAT(REGISTER_DATE, '%Y-%m') = ?";
        
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $selectedMonth);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Report</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <script src="/Project/assets/js/main.js" defer></script>
</head>
<body>
    <div class="container">
        <?php require_once "../Nav.php"; ?>
        <div class="main">
            <?php require_once "../main.php"; ?>

            <div class="details">
                <div class="recentOrders">
                    <div class="cardHeader">
                        <h2>Recent Customers</h2>

                        <!-- Month Filter -->
                        <div class="filter-container">
                            <label for="month-filter">Select Month:</label>
                            <input type="month" id="month-filter" value="<?= htmlspecialchars($selectedMonth) ?>">
                            <button id="filter-btn" class="btn btn-primary">Filter</button>
                        </div>

                        <div class="mt-4 text-center">
                            <button id="print-btn" class="btn btn-warning">
                                <i class="fas fa-print"></i> Print Report
                            </button>
                        </div>
                    </div>

                    <!-- Customer Table -->
                    <table id="customer-table">
                        <thead>
                            <tr>
                                <td>CUSTOMER ID</td>
                                <td>CUSTOMER NAME</td>
                                <td>EMAIL</td>
                                <td>PHONE</td>
                                <td>ADDRESS</td>
                                <td>STATUS</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($customers)): ?>
                                <?php foreach ($customers as $row): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($row['CUSTOMER_ID']) ?></td>
                                        <td><?= htmlspecialchars($row['CUSTOMER_NAME']) ?></td>
                                        <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                                        <td><?= htmlspecialchars($row['PHONE']) ?></td>
                                        <td><?= htmlspecialchars($row['ADDRESS']) ?></td>
                                        <td><?= ($row["STATUS"] == 'active') ? 'Active' : 'Inactive' ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6">No customers found for this month</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Month Filter -->
    <script>
        document.getElementById('filter-btn').addEventListener('click', () => {
            const selectedMonth = document.getElementById('month-filter').value;
            if (!selectedMonth) {
                alert("Please select a month.");
                return;
            }
            window.location.href = `customer.php?month=${selectedMonth}`;
        });

        // Print Functionality
        document.getElementById('print-btn').addEventListener('click', () => {
            const printWindow = window.open('', '_blank');

            printWindow.document.write(`
            <html>
            <head>
                <title>Customer Report</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h1 { text-align: center; margin-bottom: 20px; }
                    table { width: 100%; border-collapse: collapse; margin: 20px 0; }
                    table th, table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    table th { background-color: #f2f2f2; font-weight: bold; }
                    .report-footer { margin-top: 30px; font-size: 0.9em; color: #666; text-align: right; }
                </style>
            </head>
            <body>
                <h1>Customer Report - ${document.getElementById('month-filter').value}</h1>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Address</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($customers as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['CUSTOMER_ID']) ?></td>
                                <td><?= htmlspecialchars($row['CUSTOMER_NAME']) ?></td>
                                <td><?= htmlspecialchars($row['EMAIL']) ?></td>
                                <td><?= htmlspecialchars($row['PHONE']) ?></td>
                                <td><?= htmlspecialchars($row['ADDRESS']) ?></td>
                                <td><?= ($row["STATUS"] == 'active') ? 'Active' : 'Inactive' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <div class="report-footer">
                    <p>Report generated on: <?= date('d M Y, h:i A') ?></p>
                </div>
            </body>
            </html>
            `);

            printWindow.document.close();
            setTimeout(() => {
                printWindow.print();
            }, 500);
        });
    </script>

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>
