<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "project") or die("Couldn't connect");

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Check for duplicate review
    $checkQuery = "SELECT * FROM review_shop WHERE name='$name' AND email='$email' AND message='$message'";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) == 0) {
        // Insert review
        $query = "INSERT INTO review_shop(name, email, message) VALUES ('$name', '$email', '$message')";
        if (mysqli_query($con, $query)) {
            echo json_encode(["success" => true, "message" => "Thank you for your feedback!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Something went wrong. Please try again."]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "You have already submitted this review."]);
    }
}
?>
