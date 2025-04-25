<?php
// Start session
session_start();
?>
<?php require_once "./config/db.php" ?>
<?php
// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["Email"];
    $password = $_POST["Password"];

    // Query to check login credentials
    $sql = "SELECT * FROM login WHERE email='" . $email . "' AND Password='" . $password . "'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($result);

    // If login is successful, set session variables and redirect
    if ($row) {
        $_SESSION['user_email'] = $row["email"]; // Store email in session
        $_SESSION['userType'] = $row["userType"]; // Store user type in session

        if ($row["userType"] == "user") {
            header("Location: user-dashboard.php");
            exit();
        }
        if ($row["userType"] == "admin") {
            header("Location: admin/module/dashboard.php");
            exit();
        }
    } else {
        $error_message = "Invalid email or password";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/project/assets/css/index.css">
</head>
<body>
    <div class="container">
        <div class="image-container">
            <img src="assets/imgs/2.jpg" alt="Shopping"><br>
            <h2>Welcome to Our Platform</h2><br>
            <p>Experience seamless shopping with our secure login system</p>
        </div>
        <div class="login-container">
            <div class="login-header">
                <h2>Welcome</h2>
                <p>Please sign in to access your account</p>
            </div>
            <form id="loginForm" method="post">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="Email" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="Password" placeholder="Enter your password" required>
                    </div>
                </div>
                <button type="submit" class="submit-btn">Sign In</button>
                <?php if (!empty($error_message)) : ?>
                    <div class="error-message"><?= htmlspecialchars($error_message) ?></div>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
</html>
