<?php
// Start the session at the top of the page (only once)
session_start();

// Database connection
$con = mysqli_connect("localhost", "root", "", "project");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Initialize success message variable
$successMessage = '';
$errorMessage = '';

// Handle the feedback submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $feedbackType = mysqli_real_escape_string($con, $_POST['feedbackType']);
    $type = mysqli_real_escape_string($con, $_POST['type']);
    $serviceOrOrderID = mysqli_real_escape_string($con, $_POST['serviceOrOrderID']);
    $name = mysqli_real_escape_string($con, $_POST['Name']);
    $contactMethod = mysqli_real_escape_string($con, $_POST['contactMethod']);
    $phone = mysqli_real_escape_string($con, $_POST['phone']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $details = mysqli_real_escape_string($con, $_POST['details']);
    $outcomes = mysqli_real_escape_string($con, $_POST['outcomes']);

    // Initialize SERVICE_ID and ORDER_ID
    $serviceID = null;
    $orderID = null;

    // Determine if the input is for SERVICE_ID or ORDER_ID based on the selected type
    if ($type === 'service') {
        $serviceID = $serviceOrOrderID;
        $orderID = null;
    } else if ($type === 'product') {
        $orderID = $serviceOrOrderID;
        $serviceID = null;
    }

    // Prepare and bind SQL query without CUSTOMER_ID
    $sql = "INSERT INTO feedback_complaints (FEEDBACK_TYPE, TYPE, NAME, CONTACT_METHOD, PHONE, EMAIL, DETAILS, OUTCOMES, SERVICE_ID, ORDER_ID) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    // Use prepared statements to prevent SQL injection
    $stmt = mysqli_prepare($con, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssii", $feedbackType, $type, $name, $contactMethod, $phone, $email, $details, $outcomes, $serviceID, $orderID);

        if (mysqli_stmt_execute($stmt)) {
            // On successful insertion, set the success message
            $successMessage = "Feedback submitted successfully!";
        } else {
            $errorMessage = "Error: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);
    } else {
        $errorMessage = "Error: " . mysqli_error($con);
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
       body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }

        .form-container {
            background: #ffffff;
            border: 1px solid #dcdcdc;
            border-radius: 8px;
            padding: 20px;
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333333;
        }

        .form-container p {
            margin: 0 0 20px;
            font-size: 14px;
            color: #555555;
        }

        .form-container label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
            color: #333333;
        }

        .form-container input,
        .form-container textarea,
        .form-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #dcdcdc;
            border-radius: 4px;
            font-size: 14px;
            color: #333333;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background: midnightblue;
            border: none;
            border-radius: 4px;
            color: #ffffff;
            font-size: 16px;
            cursor: pointer;
        }

        .form-container button:hover {
            background: #218838;
        }

        .form-container .radio-group {
            display: flex;
            justify-content: space-evenly;
            margin-bottom: 15px;
        }

        .form-container .radio-group div {
            display: flex;
            align-items: center;
        }

        .form-container .radio-group label {
            margin-left: 5px;
        }

        .error-message {
            color: red;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }

        .success-message {
            color: green;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <?php require_once "../header.php"?>

    <div class="form-container">
        <?php
        // Show the success message if the feedback was successfully submitted
        if ($successMessage) {
            echo "<div class='success-message'>$successMessage</div>";
        }

        // If there is an error, display the message
        if ($errorMessage) {
            echo "<div class='error-message'>$errorMessage</div>";
        }
        ?>

        <h2>Complaints and Feedback</h2>
        <p>We would love to hear your thoughts, concerns, or problems so we can improve or continue providing great service!</p>

        <!-- Form will be visible to everyone, but submission will be restricted to logged-in users -->
        <form method="POST" action="">
            <div class="radio-group">
                <div>
                    <input type="radio" id="complaint" name="feedbackType" value="Complaint" required>
                    <label for="complaint">Complaint</label>
                </div>
                <div>
                    <input type="radio" id="general" name="feedbackType" value="General Feedback">
                    <label for="general">Feedback</label>
                </div>
            </div>

            <label>Is this related to a Service or Product? *</label>
            <div class="radio-group">
                <div>
                    <input type="radio" id="service" name="type" value="service" required onclick="toggleServiceOrderField()">
                    <label for="service">Service</label>
                </div>
                <div>
                    <input type="radio" id="product" name="type" value="product" onclick="toggleServiceOrderField()">
                    <label for="product">Product</label>
                </div>
            </div>

            <label for="serviceOrOrderID" id="serviceOrderLabel">Service/Order ID *</label>
            <input type="text" id="serviceOrOrderID" name="serviceOrOrderID" placeholder="Enter Service or Order ID" required>

            <label for="Name">Name</label>
            <input type="text" id="Name" name="Name" placeholder="First Name">

            <label for="contactMethod">Best Contact Method for us to respond</label>
            <select id="contactMethod" name="contactMethod">
                <option value="">Please Select</option>
                <option value="Phone">Phone</option>
                <option value="Email">Email</option>
            </select>

            <label for="phone">Phone Number</label>
            <input type="text" id="phone" name="phone" placeholder="Phone Number">

            <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="example@domain.com">

            <label for="details">Please provide Details *</label>
            <textarea id="details" name="details" rows="4" required></textarea>

            <label for="outcomes">Are there any outcomes you would like to see happen regarding this?</label>
            <textarea id="outcomes" name="outcomes" rows="4"></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>

    <script>
        function toggleServiceOrderField() {
            const serviceRadio = document.getElementById('service');
            const label = document.getElementById('serviceOrderLabel');
            const input = document.getElementById('serviceOrOrderID');

            if (serviceRadio.checked) {
                label.textContent = 'Service ID *';
                input.placeholder = 'Enter Service ID';
            } else {
                label.textContent = 'Order ID *';
                input.placeholder = 'Enter Order ID';
            }
        }
    </script>

    <?php require_once "../footer.php"?>
</body>
</html>