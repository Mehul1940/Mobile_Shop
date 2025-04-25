<?php require_once "/xampp/htdocs/Project/config/admincheck.php" ?>
<?php
require_once "/xampp/htdocs/Project/config/db.php";

// Fetch all services
$serviceQuery = "SELECT * FROM services";
$serviceResult = $conn->query($serviceQuery);

// Handle delete action
if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];
    $deleteQuery = "DELETE FROM services WHERE SERVICE_ID = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $deleteId);
    $stmt->execute();
    header("Location: service.php"); // Redirect after deletion
    exit();
}

// Handle update action
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_service'])) {
    $serviceId = $_POST['service_id'];
    $serviceName = $_POST['service_name'];
    $description = $_POST['description'];
    $price = $_POST['price'];

    $updateQuery = "UPDATE services SET SERVICE_NAME = ?, DESCRIPTION = ?, PRICE = ? WHERE SERVICE_ID = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->bind_param("ssdi", $serviceName, $description, $price, $serviceId);
    $stmt->execute();
    header("Location: service.php"); // Redirect after updating
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Services</title>
    <link rel="stylesheet" href="/Project/assets/css/smodal.css">
</head>

<body>
    <div class="container">
        <!-- Include Navigation -->
        <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

        <div class="main">
            <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>

            <div class="details">
                <h2>Services</h2>
                <div>
                    <a href="/Project/admin/add/add_service.php" class="btn">Add Services</a>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>Service Name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($service = $serviceResult->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($service['SERVICE_NAME']); ?></td>
                                <td><?php echo htmlspecialchars($service['DESCRIPTION']); ?></td>
                                <td><?php echo number_format((float)$service['PRICE'], 2); ?></td>
                                <td>
                                    <!-- Update Button -->
                                    <button onclick="openUpdateModal(<?php echo $service['SERVICE_ID']; ?>, '<?php echo htmlspecialchars($service['SERVICE_NAME']); ?>', '<?php echo htmlspecialchars($service['DESCRIPTION']); ?>', <?php echo $service['PRICE']; ?>)">Update</button>
                                    <!-- Delete Button -->
                                    <a href="?delete_id=<?php echo $service['SERVICE_ID']; ?>" class="delete-anchor" onclick="return confirm('Are you sure you want to delete this service?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal for Updating Service -->
    <div id="updateModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
            <h3>Update Service</h3>
            <form method="POST" action="">
                <input type="hidden" id="service_id" name="service_id">
                <div class="form-group">
                    <label for="update_service_name">Service Name</label>
                    <input type="text" id="update_service_name" name="service_name" class="inp" required>
                </div>
                <div class="form-group">
                    <label for="update_description">Description</label>
                    <input type="text" id="update_description" name="description" class="inp"  required>
                </div>
                <div class="form-group">
                    <label for="update_price">Price</label>
                    <input type="number" id="update_price" name="price" step="0.01" class="inp"  required>
                </div>
                <button type="submit" name="update_service">Update Service</button>
                <button type="button" class="cancel-btn" onclick="closeUpdateModal()">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // Open the update modal with pre-filled data
        function openUpdateModal(serviceId, serviceName, description, price) {
            document.getElementById("update_service_name").value = serviceName;
            document.getElementById("update_description").value = description;
            document.getElementById("update_price").value = price;
            document.getElementById("service_id").value = serviceId;
            document.getElementById("updateModal").style.display = "block";
        }

        // Close the update modal
        function closeUpdateModal() {
            document.getElementById("updateModal").style.display = "none";
        }
    </script>
</body>

</html>