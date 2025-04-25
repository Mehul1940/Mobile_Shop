<?php
// Start session and database connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: /login.php");
    exit();
}

$currentUserId = $_SESSION['CUSTOMER_ID'];

// Fetch current user data
$sql = "SELECT CUSTOMER_NAME, EMAIL, PASSWORD FROM CUSTOMER WHERE CUSTOMER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Initialize variables
$email = $user['EMAIL'];
$errors = [];
$successMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $inputEmail = trim($_POST['email']);
    $oldPassword = trim($_POST['old_password']);
    $newPassword = trim($_POST['new_password']);
    $confirmPassword = trim($_POST['confirm_password']);

    // Validate form input
    if (empty($inputEmail) || empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        $errors[] = "All fields are required.";
    } elseif ($inputEmail !== $email) {
        $errors[] = "Email does not match our records.";
    } elseif (!password_verify($oldPassword, $user['PASSWORD'])) {
        $errors[] = "Old password is incorrect.";
    } elseif (strlen($newPassword) < 8) {
        $errors[] = "New password must be at least 8 characters long.";
    } elseif ($newPassword !== $confirmPassword) {
        $errors[] = "New password and confirmation password do not match.";
    }

    // Update password if no errors
    if (empty($errors)) {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $updateSql = "UPDATE CUSTOMER SET PASSWORD = ? WHERE CUSTOMER_ID = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("si", $hashedPassword, $currentUserId);

        if ($updateStmt->execute()) {
            $successMessage = "Password updated successfully.";
        } else {
            $errors[] = "Failed to update password. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nilkanth Mobiles</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <style>
         .container {
        display: flex;
        max-width: 1200px;
        margin: 2rem auto;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
      }

      .sidebar {
        width: 25%;
        background: #f8f8f8;
        padding: 1.5rem;
        border-right: 1px solid #ddd;
      }

      .profile-info {
        text-align: center;
        margin-bottom: 2rem;
      }

      .sidebar .profile-picture {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 1rem;
        border: 2px dashed midnightblue;
      }

      .sidebar .profile-info h2 {
        font-size: 2.7rem;
        margin-bottom: -0.5rem;
      }
      .sidebar .profile-info p {
        font-size: 17px;
      }

      .menu {
        list-style: none;
      }

      .menu li {
        margin: 1rem 0;
      }

      .sidebar .menu li a {
        text-decoration: none;
        color: #333;
        font-size: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .sidebar .menu li a:hover,
      .menu li.active a {
        color: #fff;
        background-color: midnightblue;
        padding: 0.5rem;
        border-radius: 4px;
      }
      .sidebar .menu li.active a {
        background-color: midnightblue;
      }

      .badge {
        background: midnightblue;
        color: #fff;
        padding: 0.2rem 0.5rem;
        border-radius: 50%;
        font-size: 0.8rem;
      }

      .main-content {
        flex: 1;
        padding: 2rem;
      }

      .container .main-content h1 {
        margin-bottom: 1.5rem;
        font-size: 2.5rem;
      }

      .profile-form {
        max-width: 600px;
      }

      .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
      }

      .form-group {
        flex: 1;
      }

      .profile-form .form-group label {
        display: block;
        font-size: 1.9rem;
        margin-bottom: 0.5rem;
        color: #666;
      }

      .profile-form .form-group input {
        width: 100%;
        padding: 1.3rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 15px;
      }

      .profile-form .update-button {
        background: midnightblue;
        color: #fff;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 1rem;
        font-size: 20px;
      }

      .profile-form .update-button:hover {
        background: rgb(14, 14, 123);
      }
      .alert {
        transition: opacity 1s ease-out;
    }
    </style>
</head>
<body>
<!-- Header Section -->
<?php require_once "../header.php" ?>
<!-- Hero -->
<div class="container">
    <?php include "../sidebar.php" ?>
    <main class="main-content">
        <h1>Change Password</h1>
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" id="error-message">
                <ul>
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php elseif (!empty($successMessage)): ?>
            <div class="alert alert-success" id="success-message"><?= htmlspecialchars($successMessage) ?></div>
        <?php endif; ?>

        <form class="profile-form" method="POST" action="">
            <div class="form-row">
                <div class="form-group">
                    <label for="email">E-Mail</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Enter E-Mail" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="old_password">Old Password</label>
                    <input type="password" id="old_password" name="old_password" placeholder="Enter Old Password" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="new_password">New Password</label>
                    <input type="password" id="new_password" name="new_password" placeholder="Enter New Password" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm New Password" required>
                </div>
            </div>
            <button type="submit" class="update-button">Update Profile</button>
        </form>
    </main>
</div>
<!-- Footer -->
<?php require_once "../footer.php" ?>
<script src="/Project/customer/script/script.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Function to hide messages after a delay
    function hideMessages() {
        const errorMessage = document.getElementById('error-message');
        const successMessage = document.getElementById('success-message');

        if (errorMessage) {
          setTimeout(() => {
          errorMessage.style.opacity = '0';
          }, 3000); // Fade out after 5 seconds
        }

        if (successMessage) {
          setTimeout(() => {
          errorMessage.style.opacity = '0';
          }, 3000); // Fade out after 5 seconds
        }
    }

    // Call the function when the page loads
    window.onload = hideMessages;
</script>
</body>
</html>