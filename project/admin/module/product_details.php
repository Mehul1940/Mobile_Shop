<?php
require_once "/xampp/htdocs/Project/config/db.php";
require_once "/xampp/htdocs/Project/config/admincheck.php";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $ppid = isset($_POST['ppid']) ? filter_input(INPUT_POST, 'ppid', FILTER_VALIDATE_INT) : null;
        $product_id = filter_input(INPUT_POST, 'product_id', FILTER_VALIDATE_INT);
        $description = htmlspecialchars($_POST['description'], ENT_QUOTES, 'UTF-8');

        // Validate product ID exists
        $check_product = $conn->prepare("SELECT PRODUCT_ID FROM product WHERE PRODUCT_ID = ?");
        $check_product->bind_param("i", $product_id);
        $check_product->execute();
        if (!$check_product->get_result()->num_rows > 0) {
            throw new Exception("Invalid Product ID");
        }

        // Directory configuration
        $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/project/imgs/product/';
        $webPath = '/project/imgs/product/';

        // Create directory if not exists
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Initialize paths
        $main_image_path = $_POST['existing_main_image'] ?? '';
        $existing_small_images = json_decode($_POST['existing_small_images'] ?? '[]', true) ?? [];

        // Handle main image upload
        if (!empty($_FILES['main_image']['name'])) {
            $main_image_name = basename($_FILES['main_image']['name']);
            $target_file = $uploadDir . $main_image_name;

            // Validate image
            $check = getimagesize($_FILES['main_image']['tmp_name']);
            if ($check === false) {
                throw new Exception("Main file is not an image.");
            }

            if (!move_uploaded_file($_FILES['main_image']['tmp_name'], $target_file)) {
                throw new Exception("Failed to upload main image.");
            }
            $main_image_path = $webPath . $main_image_name;
        }

        // Handle small images upload
        $new_small_images = [];
        if (!empty($_FILES['small_images']['name'][0])) {
            foreach ($_FILES['small_images']['name'] as $key => $name) {
                if ($_FILES['small_images']['error'][$key] !== UPLOAD_ERR_OK) continue;

                $check = getimagesize($_FILES['small_images']['tmp_name'][$key]);
                if ($check === false) continue;

                $filename = basename($name);
                $target_file = $uploadDir . $filename;

                if (move_uploaded_file($_FILES['small_images']['tmp_name'][$key], $target_file)) {
                    $new_small_images[] = $webPath . $filename;
                }
            }
        }

        // Combine existing and new small images
        $small_images = array_merge($existing_small_images, $new_small_images);

        if ($ppid) {
            // Update existing record
            $stmt = $conn->prepare("UPDATE product_details SET 
                                  PRODUCT_ID = ?, 
                                  IMG = ?, 
                                  SMALL_IMG = ?, 
                                  DES = ?
                                  WHERE PPID = ?");
            $stmt->bind_param("isssi", $product_id, $main_image_path, json_encode($small_images), $description, $ppid);
        } else {
            // Insert new record
            if (empty($small_images)) {
                throw new Exception("At least one valid small image is required");
            }
            $stmt = $conn->prepare("INSERT INTO product_details 
                                  (PRODUCT_ID, IMG, SMALL_IMG, DES) 
                                  VALUES (?, ?, ?, ?)");
            $stmt->bind_param("isss", $product_id, $main_image_path, json_encode($small_images), $description);
        }

        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }

        $_SESSION['message'] = "Product details " . ($ppid ? "updated" : "added") . " successfully!";
        header("Location: product_details.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: product_details.php");
        exit();
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    try {
        $ppid = filter_input(INPUT_GET, 'ppid', FILTER_VALIDATE_INT);
        if (!$ppid) throw new Exception("Invalid product detail ID");

        // Get existing images
        $stmt = $conn->prepare("SELECT IMG, SMALL_IMG FROM product_details WHERE PPID = ?");
        $stmt->bind_param("i", $ppid);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();

        // Delete images from server (optional)
        // unlink($_SERVER['DOCUMENT_ROOT'] . $result['IMG']);
        // array_map('unlink', json_decode($result['SMALL_IMG'], true));

        // Delete from database
        $stmt = $conn->prepare("DELETE FROM product_details WHERE PPID = ?");
        $stmt->bind_param("i", $ppid);
        $stmt->execute();

        $_SESSION['message'] = "Product details deleted successfully!";
        header("Location: product_details.php");
        exit();
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: product_details.php");
        exit();
    }
}

// Fetch existing details for editing
$editData = [];
if (isset($_GET['edit'])) {
    $ppid = filter_input(INPUT_GET, 'ppid', FILTER_VALIDATE_INT);
    if ($ppid) {
        $stmt = $conn->prepare("SELECT * FROM product_details WHERE PPID = ?");
        $stmt->bind_param("i", $ppid);
        $stmt->execute();
        $editData = $stmt->get_result()->fetch_assoc();
    }
}

// Fetch all product details
$stmt = $conn->prepare("SELECT * FROM product_details ORDER BY PPID ASC");
$stmt->execute();
$allDetails = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Product Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-input-group {
            margin-bottom: 10px;
            position: relative;
        }

        .remove-btn {
            position: absolute;
            right: -35px;
            top: 50%;
            transform: translateY(-50%);
        }

        .add-more-btn {
            margin-top: 10px;
        }

        .alert {
            margin-top: 20px;
        }

        .preview-img {
            max-width: 150px;
            margin: 10px;
        }

        table {
            margin-top: 20px;
        }

        .existing-images img {
            width: 100px;
            margin: 5px;
        }
    </style>
</head>

<body>
    <!-- Include Navigation -->
    <?php require_once "/xampp/htdocs/Project/admin/Nav.php"; ?>

    <div class="main">
        <?php require_once "/xampp/htdocs/Project/admin/main.php"; ?>
        <div class="container mt-5">
            <h2><?= isset($editData['PPID']) ? 'Edit' : 'Add' ?> Product Details</h2>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success"><?= htmlspecialchars($_SESSION['message']) ?></div>
                <?php unset($_SESSION['message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="post" enctype="multipart/form-data">
                <input type="hidden" name="ppid" value="<?= $editData['PPID'] ?? '' ?>">
                <input type="hidden" name="existing_main_image" value="<?= $editData['IMG'] ?? '' ?>">
                <input type="hidden" name="existing_small_images" value="<?= htmlspecialchars($editData['SMALL_IMG'] ?? '[]') ?>">

                <div class="mb-3">
                    <label>Product ID</label>
                    <input type="number" name="product_id" class="form-control" required min="1"
                        value="<?= htmlspecialchars($editData['PRODUCT_ID'] ?? '') ?>">
                </div>

                <div class="mb-3">
                    <label>Main Image (Required)</label>
                    <?php if (isset($editData['IMG'])): ?>
                        <div class="existing-images">
                            <img src="<?= htmlspecialchars($editData['IMG']) ?>" class="preview-img">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="main_image" class="form-control" accept="image/*" <?= isset($editData['IMG']) ? '' : 'required' ?>>
                </div>

                <div class="mb-3">
                    <label>Small Images</label>
                    <?php if (isset($editData['SMALL_IMG'])): ?>
                        <div class="existing-images">
                            <?php foreach (json_decode($editData['SMALL_IMG'], true) as $img): ?>
                                <img src="<?= htmlspecialchars($img) ?>" class="preview-img">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div id="small-images-container">
                        <div class="image-input-group">
                            <input type="file" name="small_images[]" class="form-control" accept="image/*">
                            <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.remove()">×</button>
                        </div>
                    </div>
                    <button type="button" class="btn btn-secondary add-more-btn" onclick="addImageField()">
                        Add More Images
                    </button>
                </div>

                <div class="mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($editData['DES'] ?? '') ?></textarea>
                </div>

                <button type="submit" class="btn btn-primary"><?= isset($editData['PPID']) ? 'Update' : 'Submit' ?></button>
                <?php if (isset($editData['PPID'])): ?>
                    <a href="product_details.php" class="btn btn-secondary">Cancel</a>
                <?php endif; ?>
            </form>

            <h3 class="mt-5">Existing Product Details</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>PPID</th>
                        <th>Product ID</th>
                        <th>Main Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($allDetails as $detail): ?>
                        <tr>
                            <td><?= $detail['PPID'] ?></td>
                            <td><?= $detail['PRODUCT_ID'] ?></td>
                            <td><img src="<?= htmlspecialchars($detail['IMG']) ?>" width="50"></td>
                            <td>
                                <a href="product_details.php?delete&ppid=<?= $detail['PPID'] ?>"
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure you want to delete this?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function addImageField() {
            const container = document.getElementById('small-images-container');
            const newInput = document.createElement('div');
            newInput.className = 'image-input-group';
            newInput.innerHTML = `
                <input type="file" name="small_images[]" class="form-control" accept="image/*">
                <button type="button" class="btn btn-danger btn-sm remove-btn" onclick="this.parentElement.remove()">×</button>
            `;
            container.appendChild(newInput);
        }
    </script>
</body>

</html>