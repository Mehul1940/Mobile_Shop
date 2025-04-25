<?php
// Start session and database connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Initialize variables
$email = '';
$errors = [];
$successMessage = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);

    // Validate email
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    // Check if the email exists in the database
    if (empty($errors)) {
        $sql = "SELECT CUSTOMER_ID, CUSTOMER_NAME FROM CUSTOMER WHERE EMAIL = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            // Generate a unique reset token and save it in the database
            $resetToken = bin2hex(random_bytes(16)); // Generate a secure random token
            $expiryTime = date("Y-m-d H:i:s", strtotime("+1 hour"));

            $updateSql = "UPDATE CUSTOMER SET RESET_TOKEN = ?, RESET_EXPIRY = ? WHERE EMAIL = ?";
            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param("sss", $resetToken, $expiryTime, $email);

            if ($updateStmt->execute()) {
                // Send the password reset link to the user's email
                $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $resetToken;

                $subject = "Password Reset Request";
                $message = "Hello, \n\nTo reset your password, click the link below:\n\n" . $resetLink;
                $headers = "From: no-reply@yourwebsite.com";

                if (mail($email, $subject, $message, $headers)) {
                    $successMessage = "A password reset link has been sent to your email address.";
                } else {
                    $errors[] = "Failed to send the reset email. Please try again later.";
                }
            } else {
                $errors[] = "Failed to process the reset request. Please try again.";
            }
        } else {
            $errors[] = "Email not found in our records.";
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
    <title>Forget Password</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <style>
        /* Add custom styles for the forget password page */
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
      /* Style for form-group */
.profile-form .form-group {
    margin-bottom: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

/* Label styling */
.profile-form .form-group label {
    font-size: 1.6rem;
    color: #333;
    font-weight: bold;
}

/* Input field styling */
.profile-form .form-group input {
    padding: 1rem;
    font-size: 1.2rem;
    border: 1px solid #ccc;
    border-radius: 5px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    outline: none;
    transition: border-color 0.3s ease;
}

/* Input field focus effect */
.profile-form .form-group input:focus {
    border-color: #5c6bc0; /* Focus color */
    box-shadow: 0 0 5px rgba(92, 107, 192, 0.4);
}

/* Submit button styling */
.profile-form .update-button {
    background-color: midnightblue;
    color: white;
    padding: 0.8rem 2rem;
    border: none;
    border-radius: 5px;
    font-size: 1.6rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

/* Submit button hover effect */
.profile-form .update-button:hover {
    background-color: #303f9f; /* Darker shade on hover */
}
    </style>
</head>
<body>
<!-- Header Section -->
<?php require_once "../header.php" ?>


<!-- Forget Password Form -->
<div class="container">
<?php require_once "../sidebar.php" ?>
    <h2>Forget Password</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php elseif (!empty($successMessage)): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Enter Your Email</label>
            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="Email" required />
        </div>

        <button type="submit">Send Reset Link</button>
    </form>
</div>

<!-- Footer -->
<?php require_once "../footer.php" ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
