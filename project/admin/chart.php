<?php require_once "/xampp/htdocs/Project/config/db.php"; ?> 

<?php
// Query to get the number of orders for each month
$orderQuery = "
    SELECT 
        MONTH(ORDER_DATE) AS month, 
        COUNT(*) AS order_count
    FROM 
        orders 
    WHERE 
        DELIVERY_STATUS = 'Delivered'
    GROUP BY 
        MONTH(ORDER_DATE)
    ORDER BY 
        MONTH(ORDER_DATE) ASC
";
$orderResult = $conn->query($orderQuery);

// Prepare data for Recent Orders chart
$months = array_fill(1, 12, 0); // Array with all months initialized to 0

if ($orderResult) {
    if ($orderResult->num_rows > 0) {
        while ($row = $orderResult->fetch_assoc()) {
            $months[(int)$row['month']] = $row['order_count']; // Replace counts for existing months
        }
    } else {
        echo "No order data found.";
    }
} else {
    echo "Error in executing order query: " . $conn->error;
}

// Query to get booking count by status
$bookingQuery = "
    SELECT 
        STATUS, 
        COUNT(*) AS booking_count
    FROM 
        service_bookings
    GROUP BY 
        STATUS
";
$bookingResult = $conn->query($bookingQuery);

// Prepare data for Bookings by Status chart
$bookingStatuses = [];
$bookingCounts = [];

if ($bookingResult) {
    if ($bookingResult->num_rows > 0) {
        while ($row = $bookingResult->fetch_assoc()) {
            $bookingStatuses[] = $row['STATUS'];
            $bookingCounts[] = $row['booking_count'];
        }
    } else {
        echo "No booking data found.";
    }
} else {
    echo "Error in executing booking query: " . $conn->error;
}

// Close the connection
$conn->close();

// Month labels for display
$monthLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

// Order counts as values
$orderCounts = array_values($months);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders and Bookings Charts</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="/project/assets/css/chart.css">
</head>
<body>
    <div class="chart-wrapper">
        <!-- Recent Orders Bar Chart -->
        <div class="chart-container">
            <h3>Recent Orders</h3>
            <canvas id="recentOrdersChart"></canvas>
        </div>

        <!-- Bookings by Status Bar Chart -->
        <div class="chart-container">
            <h3>Bookings by Status</h3>
            <canvas id="bookingsByStatusChart"></canvas>
        </div>
    </div>

    <script>
        // Prepare data for the Recent Orders chart
        const months = <?php echo json_encode($monthLabels); ?>; // Full month names
        const orderCounts = <?php echo json_encode($orderCounts); ?>; // Orders for all months

        // Configuration for the Recent Orders Bar Chart
        const recentOrdersConfig = {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Number of Orders',
                    data: orderCounts,
                    backgroundColor: 'rgba(54, 162, 235, 0.5)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    barThickness: 30
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => `Orders: ${context.raw}`
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Orders'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Months'
                        }
                    }
                }
            }
        };

        // Prepare data for the Bookings by Status chart
        const bookingStatuses = <?php echo json_encode($bookingStatuses); ?>;
        const bookingCounts = <?php echo json_encode($bookingCounts); ?>;

        // Configuration for the Bookings by Status Bar Chart
        const bookingsByStatusConfig = {
            type: 'bar',
            data: {
                labels: bookingStatuses,
                datasets: [{
                    label: 'Number of Bookings',
                    data: bookingCounts,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)', // Pending
                        'rgba(255, 206, 86, 0.6)', // Estimated
                        'rgba(75, 192, 192, 0.6)', // Accepted
                        'rgba(255, 99, 132, 0.6)', // Rejected
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1,
                    barThickness: 50
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: (context) => `Bookings: ${context.raw}`
                        }
                    },
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Number of Bookings'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Booking Status'
                        }
                    }
                }
            }
        };

        // Render the Recent Orders Bar Chart
        const recentOrdersChartCtx = document.getElementById('recentOrdersChart').getContext('2d');
        new Chart(recentOrdersChartCtx, recentOrdersConfig);

        // Render the Bookings by Status Bar Chart
        const bookingsByStatusChartCtx = document.getElementById('bookingsByStatusChart').getContext('2d');
        new Chart(bookingsByStatusChartCtx, bookingsByStatusConfig);
    </script>
</body>
</html>
