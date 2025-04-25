<?php
// Start session and database connection
session_start();
$conn = new mysqli('localhost', 'root', '', 'project');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Ensure CUSTOMER_ID exists in the session
if (!isset($_SESSION['CUSTOMER_ID'])) {
    die("Unauthorized access. Please log in.");
}

// Fetch current user data
$currentUserId = $_SESSION['CUSTOMER_ID'];
$sql = "SELECT CUSTOMER_NAME, EMAIL, PHONE, ADDRESS FROM CUSTOMER WHERE CUSTOMER_ID = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentUserId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found.");
}

// Initialize messages
$successMessage = "";
$errorMessage = "";

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // Validate email
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format.";
    }

    // Validate phone number
    if (!empty($phone) && (strlen($phone) != 10 || !ctype_digit($phone))) {
        $errorMessage = "Phone number must be exactly 10 digits.";
    }

    // If there are no validation errors, proceed with the update
    if (empty($errorMessage)) {
        // Initialize an array to hold the fields to update
        $updateFields = [];
        $params = [];
        $types = '';

        // Check each field and add to the update array if it's not empty
        if (!empty($name)) {
            $updateFields[] = "CUSTOMER_NAME = ?";
            $params[] = $name;
            $types .= 's';
        }
        if (!empty($email)) {
            $updateFields[] = "EMAIL = ?";
            $params[] = $email;
            $types .= 's';
        }
        if (!empty($phone)) {
            $updateFields[] = "PHONE = ?";
            $params[] = $phone;
            $types .= 's';
        }
        if (!empty($address)) {
            $updateFields[] = "ADDRESS = ?";
            $params[] = $address;
            $types .= 's';
        }

        // If there are fields to update, proceed with the update
        if (!empty($updateFields)) {
            $updateSql = "UPDATE CUSTOMER SET " . implode(", ", $updateFields) . " WHERE CUSTOMER_ID = ?";
            $params[] = $currentUserId;
            $types .= 'i';

            $updateStmt = $conn->prepare($updateSql);
            $updateStmt->bind_param($types, ...$params);

            if ($updateStmt->execute()) {
                $successMessage = "Profile updated successfully.";
                // Refresh user data
                if (!empty($name)) $user['CUSTOMER_NAME'] = $name;
                if (!empty($email)) $user['EMAIL'] = $email;
                if (!empty($phone)) $user['PHONE'] = $phone;
                if (!empty($address)) $user['ADDRESS'] = $address;
            } else {
                $errorMessage = "Failed to update profile. Please try again.";
            }
            $updateStmt->close();
        } else {
            $errorMessage = "No fields to update.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="x-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <title>Nilkanth Mobiles</title>
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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

      /* Fade-out animation */
      .alert {
          transition: opacity 1s ease-out;
      }

      .alert.fade-out {
          opacity: 0;
      }
    </style>
  </head>
  <body>
    <!-- Include header -->
    <?php 
    if (file_exists("../header.php")) {
        require_once "../header.php";
    } else {
        echo "<div class='alert alert-warning'>Header not found!</div>";
    }
    ?>

    <div class="container">
      <!-- Include sidebar -->
      <?php 
      if (file_exists("../sidebar.php")) {
          require_once "../sidebar.php";
      } else {
          echo "<div class='alert alert-warning'>Sidebar not found!</div>";
      }
      ?>

      <main class="main-content">
        <h1>Profile</h1>

        <!-- Display success or error messages -->
        <?php if (!empty($successMessage)): ?>
          <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
          <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form class="profile-form" method="POST" action="">
          <div class="form-row">
            <div class="form-group">
              <label for="name">Name</label>
              <input
                type="text"
                id="name"
                name="name"
                value="<?php echo htmlspecialchars($user['CUSTOMER_NAME'] ?? ''); ?>"
                placeholder="Full Name"
              />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="email">Email address</label>
              <input
                type="email"
                id="email"
                name="email"
                value="<?php echo htmlspecialchars($user['EMAIL'] ?? ''); ?>"
                placeholder="Email address"
              />
            </div>
            <div class="form-group">
              <label for="phone">Phone number</label>
              <input
                type="text"
                id="phone"
                name="phone"
                value="<?php echo htmlspecialchars($user['PHONE'] ?? ''); ?>"
                placeholder="Phone number"
                oninput="validatePhoneInput(event)"
                maxlength="10"
              />
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="address">Address</label>
              <input
                type="text"
                id="address"
                name="address"
                value="<?php echo htmlspecialchars($user['ADDRESS'] ?? ''); ?>"
                placeholder="Address"
              />
            </div>
          </div>
          <button type="submit" class="update-button">Update Profile</button>
        </form>
      </main>
    </div>

    <!-- Include footer -->
    <?php 
    if (file_exists("../footer.php")) {
        require_once "../footer.php";
    } else {
        echo "<div class='alert alert-warning'>Footer not found!</div>";
    }
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
     <script>
      // JavaScript to restrict input to numbers and limit to 10 digits
      function validatePhoneInput(event) {
        const input = event.target;
        // Remove any non-numeric characters
        input.value = input.value.replace(/\D/g, '');
        // Limit input to 10 digits
        if (input.value.length > 10) {
          input.value = input.value.slice(0, 10);
        }
      }

      // JavaScript to fade out and hide messages after 3 seconds
      function hideMessages() {
        const successMessage = document.querySelector('.alert-success');
        const errorMessage = document.querySelector('.alert-danger');

        // Function to fade out an element
        function fadeOut(element) {
            if (element) {
                element.classList.add('fade-out'); // Add fade-out class
                setTimeout(() => {
                    element.style.display = 'none'; // Hide after fade-out
                }, 1000); // Wait for the transition to complete (1 second)
            }
        }

        // Fade out success message
        if (successMessage) {
            setTimeout(() => {
                fadeOut(successMessage);
            }, 3000); // Start fade-out after 3 seconds
        }

        // Fade out error message
        if (errorMessage) {
            setTimeout(() => {
                fadeOut(errorMessage);
            }, 3000); // Start fade-out after 3 seconds
        }
      }

      // Run the hideMessages function when the page loads
      window.onload = hideMessages;
    </script>
  </body>
</html>