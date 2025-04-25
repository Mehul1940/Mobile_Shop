<?php
include("php/config.php"); // Include the database configuration file

// Initialize variables for error and success messages
$error_message = "";
$success_message = "";

// Process the form after submission
if (isset($_POST['submit'])) {
    // Retrieve form data
    $customer_name = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = $_POST['password'];

    // Validate input
    if (empty($customer_name) || empty($email) || empty($phone) || empty($address) || empty($password)) {
        $error_message = "All fields are required!";
    } else {
        // Check if the email already exists in the database
        $verify_query = $con->prepare("SELECT EMAIL FROM customer WHERE EMAIL = ?");
        $verify_query->bind_param("s", $email);
        $verify_query->execute();
        $result = $verify_query->get_result();

        if ($result->num_rows > 0) {
            $error_message = "This email is already in use. Please try another!";
        } else {
            // Hash the password before storing it
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user data into the customer table using prepared statements
            $insert_query = $con->prepare("INSERT INTO customer (CUSTOMER_NAME, EMAIL, PHONE, ADDRESS, PASSWORD) 
                                           VALUES (?, ?, ?, ?, ?)");
            $insert_query->bind_param("sssss", $customer_name, $email, $phone, $address, $hashed_password);

            if ($insert_query->execute()) {
                $success_message = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error_message = "An error occurred while registering. Please try again!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="lstyle/register.css">
</head>

<body>
    <div class="container">
        <div class="image-container">
            <img src="./assets/imgs/2.jpg" alt="Shopping"><br>
            <h2>Create Your Account</h2><br>
            <p>Join us for a seamless shopping experience</p>
        </div>
        <div class="register-container">
            <div class="register-header">
                <h2>Welcome!</h2>
                <p>Please fill in the details to create an account</p>
            </div>
            <form id="registerForm" method="post">
                <div class="form-group">
                    <label for="username">Full Name</label>
                    <div class="input-wrapper">
                        <input type="text" id="username" name="username" placeholder="Enter your full name" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number</label>
                    <div class="input-wrapper">
                        <input type="text" id="phone" name="phone" placeholder="Enter your phone number" maxlength="10" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="address">Address</label>
                    <div class="input-wrapper">
                        <input type="text" id="address" name="address" placeholder="Enter your Address" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="form-group">
                    <br>
                    Already a member? <a href="login.php">Sign In</a>
                </div>
                <button type="submit" class="submit-btn" name="submit">Register</button>
                <br/>
                <?php
                // Show error or success messages
                if (!empty($error_message)) {
                    echo "<div class='error-message'>" . htmlspecialchars($error_message) . "</div>";
                }
                if (!empty($success_message)) {
                    echo "<div class='success-message'>" . $success_message . "</div>";
                }
                ?>
            </form>
        </div>
    </div>

</body>
<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        if (passwordField.type === "password") {
            passwordField.type = "text"; // Show password
        } else {
            passwordField.type = "password"; // Hide password
        }
    }
</script>

</html>