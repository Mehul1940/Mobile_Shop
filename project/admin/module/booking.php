<?php require_once "/xampp/htdocs/Project/config/admincheck.php"; ?>
<?php require_once "/xampp/htdocs/Project/config/db.php"; ?>
<?php
// Handle status updates or estimate changes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['booking_id'], $_POST['status'])) {
    $booking_id = intval($_POST['booking_id']);
    $status = $_POST['status'];
    $estimate = isset($_POST['estimate']) && $_POST['estimate'] !== '' ? floatval($_POST['estimate']) : null;

    // Update query
    $sql = "UPDATE service_bookings SET status = ?, estimate = ? WHERE booking_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdi", $status, $estimate, $booking_id);

    if ($stmt->execute()) {
        $message = "Booking updated successfully!";
    } else {
        $message = "Error updating booking: " . $stmt->error;
    }
}

// Fetch all bookings
$sql = "SELECT * FROM service_bookings ORDER BY created_at ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Service Bookings</title>
    <link rel="stylesheet" href="/Project/assets/css/booking.css">
    <style>
        h2{
            text-align: center;
            margin-top: 3rem;
            margin-bottom: 3rem;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Include Navigation -->
        <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

        <div class="main">
            <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>
            <h2 class="text-center my-4">Admin Service Bookings</h2>

            <?php if (isset($message)): ?>
                <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-dark table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Email</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Notes</th>
                            <th>Estimate</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= $row['booking_id'] ?></td>
                                <td><?= htmlspecialchars($row['customer_name']) ?></td>
                                <td><?= htmlspecialchars($row['customer_email']) ?></td>
                                <td><?= htmlspecialchars($row['service_type']) ?></td>
                                <td><?= $row['booking_date'] ?></td>
                                <td><?= $row['booking_time'] ?></td>
                                <td><?= htmlspecialchars($row['notes']) ?></td>
                                <td><?= $row['estimate'] !== null ? "₹" . number_format($row['estimate'], 2) : "Not set" ?></td>
                                <td><?= htmlspecialchars($row['status']) ?></td>
                                <td>
                                    <form method="POST" class="d-flex">
                                        <input type="hidden" name="booking_id" value="<?= $row['booking_id'] ?>">
                                        <select name="status" class="form-select form-select-sm me-2">
                                            <option value="Pending" <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                            <option value="Estimated" <?= $row['status'] == 'Estimated' ? 'selected' : '' ?>>Estimated</option>
                                            <option value="Accepted" <?= $row['status'] == 'Accepted' ? 'selected' : '' ?>>Accepted</option>
                                            <option value="Rejected" <?= $row['status'] == 'Rejected' ? 'selected' : '' ?>>Rejected</option>
                                        </select>
                                        <input type="number" step="0.01" name="estimate" placeholder="₹ Estimate" class="form-control form-control-sm me-2" value="<?= $row['estimate'] ?? '' ?>">
                                        <button type="submit" class="btn btn-primary btn-sm">Update</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>