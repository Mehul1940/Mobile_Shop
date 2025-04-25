<?php
session_start();
require_once "../header.php";
$con = mysqli_connect("localhost", "root", "", "project") or die("Couldn't connect");

// Ensure the user is logged in
if (!isset($_SESSION['CUSTOMER_ID'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['CUSTOMER_ID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $cancellation_type = $_POST['cancellation_type']; // 'order' or 'service'
    $order_id = !empty($_POST['order_id']) ? $_POST['order_id'] : NULL;
    $service_id = !empty($_POST['service_id']) ? $_POST['service_id'] : NULL;
    $mobile_number = $_POST['mobile_number'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $reason = $_POST['reason'];
    
    // Insert cancellation request into the database
    $query = "INSERT INTO cancellations (CUSTOMER_ID, CANCELLATION_TYPE, ORDER_ID, SERVICE_ID, MOBILE_NUMBER, EMAIL, ADDRESS, REASON) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("isisssss", $customer_id, $cancellation_type, $order_id, $service_id, $mobile_number, $email, $address, $reason);
    
    if ($stmt->execute()) {
        echo "<script>alert('Your cancellation request has been submitted successfully.'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error submitting cancellation request. Please try again.');</script>";
    }
// Retrieve customer email from the database
$sql = "SELECT EMAIL FROM customer WHERE CUSTOMER_ID = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer_data = $result->fetch_assoc();
$customer_email = $customer_data['EMAIL'];
$stmt->close();
// MAIL NOTIFICATION TO ADMIN WITH CUSTOMER EMAIL IN REPLY-TO
$to = "bokdemehul870@gmail.com";
$subject = "A new order/service cancellation has been requested";
$message = "A new order/service cancellation has been requested. Check your dashboard";
$headers = "From: familybokade176@gmail.com" . "\r\n" .
           "Reply-To: " . $customer_email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}
 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cancellation Form</title>
    <style>
        body .container {
            display:flex;
            align-items:center;
            max-width:750px;
            justify-content:center;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 1000px;
            text-align: center;
        }
        h2 {
            margin-bottom: 20px;
            text-align:center;
        }
        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        label {
            margin-top: 10px;
            font-size:20px;
        }
        .container input, textarea, button {
            padding: 8px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            font-size:20px
        }
        .container textarea{
            font-size:15px;
        }
        .container button {
            background-color: #dc3545;
            color: white;
            cursor: pointer;
            width: 100%;
            font-size:20px
        }
        button:hover {
            background-color: #c82333;
        }
        .radio-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .radio-buttons input {
            margin-right: 15px;
            width: auto;
        }
        .radio-buttons input {
    margin-right: 4px;
    width: auto;
}
.radio-buttons {
    display: flex;
    justify-content: center;
    margin-bottom: 20px;
    gap: 20px;
}
    </style>
</head>
<body>
    <h2>Cancel Order / Service</h2>
    <div class="container">
        <form method="POST">
            <div class="radio-buttons">
                <label><input type="radio" name="cancellation_type" value="order" required> Order</label>
                <label><input type="radio" name="cancellation_type" value="service" required> Service</label>
            </div>
            
            <div id="order_input" style="display:none;">
                <label>Order ID:</label>
                <input type="text" name="order_id" placeholder="Enter Order ID">
            </div>
            
            <div id="service_input" style="display:none;">
                <label>Service ID:</label>
                <input type="text" name="service_id" placeholder="Enter Service ID">
            </div>
            
            <label>Email:</label>
            <input type="email" name="email" required>
            
            <label>Phone:</label>
            <input type="text" name="mobile_number" required>
            
            <label>Address:</label>
            <textarea name="address" required></textarea>
            
            <label>Reason:</label>
            <textarea name="reason" required></textarea>
            <br>
            <button type="submit">Submit Cancellation</button>
        </form>
    </div>

    <script>
        // Show/hide inputs based on the selected radio button
        const radioButtons = document.querySelectorAll('input[name="cancellation_type"]');
        const orderInput = document.getElementById('order_input');
        const serviceInput = document.getElementById('service_input');

        radioButtons.forEach(button => {
            button.addEventListener('change', function() {
                if (this.value === 'order') {
                    orderInput.style.display = 'block';
                    serviceInput.style.display = 'none';
                } else {
                    orderInput.style.display = 'none';
                    serviceInput.style.display = 'block';
                }
            });
        });
    </script>
</body>
</html>

<?php require_once "../footer.php"; ?>
