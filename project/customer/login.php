<?php
session_start();
include("php/config.php");

$error_message = "";

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);

    // Use prepared statement for SELECT query
    $query = "SELECT * FROM customer WHERE EMAIL=?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['PASSWORD'])) {
            // Set session variables
            $_SESSION['CUSTOMER_ID'] = $row['CUSTOMER_ID'];
            $_SESSION['CUSTOMER_NAME'] = $row['CUSTOMER_NAME'];
            $_SESSION['EMAIL'] = $row['EMAIL'];

            // Update status using prepared statement
            $updateQuery = "UPDATE customer SET STATUS = 'active' WHERE CUSTOMER_ID = ?";
            $updateStmt = $con->prepare($updateQuery);
            $updateStmt->bind_param("i", $row['CUSTOMER_ID']);
            
            if ($updateStmt->execute()) {
                header("Location: /project/customer/pages/index.php");
                exit();
            } else {
                $error_message = "Error updating user status";
            }
        } else {
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        $error_message = "No user found with this email. Please try again.";
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
    <link rel="stylesheet" href="lstyle/login.css">
</head>

<body>
    <div class="container">
        <div class="image-container">
            <img src="./assets/imgs/2.jpg" alt="Shopping"><br>
            <h2>Welcome to Our Platform</h2><br>
            <p>Experience seamless shopping with our secure login system</p>
        </div>
        <div class="login-container">
            <div class="login-header">
                <h2>Welcome Back!</h2>
                <p>Please sign in to access your account</p>
            </div>
            <form id="loginForm" method="post" action="">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                <button type="submit" class="submit-btn" name="submit">Sign In</button>
                <?php
                if (!empty($error_message)) {
                    echo "<div class='error-message'>" . htmlspecialchars($error_message) . "</div>";
                }
                ?>
            </form>
        </div>
    </div>
</body>

</html>
