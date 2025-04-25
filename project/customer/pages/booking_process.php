<?php
$con = new mysqli('localhost', 'root', '', 'project');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];
    $notes = $_POST['notes'];

    if (!empty($name) && !empty($email) && !empty($service) && !empty($date) && !empty($time)) {
        $stmt = $con->prepare("INSERT INTO service_bookings (customer_name, customer_email, service_type, booking_date, booking_time, notes) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $name, $email, $service, $date, $time, $notes);

        if ($stmt->execute()) {
            echo "<script>alert('Booking request submitted successfully! You will receive an estimate soon.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error submitting booking. Please try again.');</script>";
        }
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
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
$subject = "A new order has been placed";
$message = "A new order has been placed. Check your dashboard";
$headers = "From: familybokade176@gmail.com" . "\r\n" .
           "Reply-To: " . $customer_email . "\r\n" .
           "X-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $message, $headers)) {
    echo "Email sent successfully!";
} else {
    echo "Failed to send email.";
}

?>
