<?php
// Include database connection
$con = new mysqli('localhost', 'root', '', 'project');

// Initialize message variables
$success_message = "";
$error_message = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $name = mysqli_real_escape_string($con, $_POST['name']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $message = mysqli_real_escape_string($con, $_POST['message']);

    // Insert data into the database
    $sql = "INSERT INTO review_shop (name, email, message) VALUES ('$name', '$email', '$message')";

    if (mysqli_query($con, $sql)) {
        // Redirect to avoid form resubmission on refresh
        header("Location: contact.php?success=1");
        exit();
    } else {
        $error_message = "Error: " . mysqli_error($con);
    }
}

// Show success message only if redirected with ?success=1
if (isset($_GET['success']) && $_GET['success'] == 1) {
    $success_message = "Your message has been sent successfully!";
}

// Fetch three random reviews from the database
$random_reviews_query = "SELECT name, email, message, submitted_at FROM review_shop ORDER BY RAND() LIMIT 3";
$random_reviews_result = mysqli_query($con, $random_reviews_query);
$random_reviews = [];
if ($random_reviews_result) {
    while ($row = mysqli_fetch_assoc($random_reviews_result)) {
        $random_reviews[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us</title>

  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/project/customer/style/style.css">

  <style>
    .mb-3 {
      margin-bottom: 1rem !important;
      width: 700px;
    }
    #form-details form button {
      background-color: midnightblue;
      color: #fff;
      font-size: 20px;
    }
    .success-message {
      color: green;
      font-weight: bold;
      margin-top: 10px;
    }
    .error-message {
      color: red;
      font-weight: bold;
      margin-top: 10px;
    }
    #form-details form input, #form-details form textarea {
    width: 100%;
    padding: 12px 15px;
    outline: none;
    margin-bottom: 20px;
    border: 1px solid #e1e1e1;
    font-size: large;
    }
    .review-item {
      background-color: #f9f9f9;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 8px;
    }
    .review-item h3 {
      font-size: 1.7rem;
    }
    .review-item p {
      font-size: 1.5rem;
    }
    
  </style>
</head>

<body>

  <!-- Header Section -->
  <?php require_once "../header.php"; ?>

  <!-- Hero Section -->
  <section id="page-header" class="contact-header">
    <h2>#Let's_talk</h2>
    <p>Leave A Message. We Love To Hear From You</p>
  </section>

  <section id="contact-details" class="section-p1">
    <div class="details">
      <span>Get In Touch</span>
      <h2>Call Or Visit Our Shop Today</h2>
      <h3> Shop </h3>
      <li>
        <i class="fal fa-map"></i>
        <p> G/3 Vihak Park-2, Nr Shree Ram Sweets, Maheshwari Nagar Road, Odhav, Ahmedabad-382415</p>
      </li>
      <li>
        <i class="fal fa-envelope"></i>
        <p>nilkanthmobiles@gmail.com</p>
      </li>
      <li>
        <i class="fal fa-phone-alt"></i>
        <p>(+91)9974311445 / (+91)9974311447</p>
      </li>
      <li>
        <i class="fal fa-clock"></i>
        <p>Monday to Saturday, 10:00am - 8:00pm</p>
      </li>
    </div>
    <div class="map">
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58739.736143969836!2d72.57963284034254!3d23.051899225930136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e876792d5d1c7%3A0xb929d3f94b3f6c2e!2sNeelkanth%20mobile!5e0!3m2!1sen!2sin!4v1736619952158!5m2!1sen!2sin"
        width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    </div>
  </section>

  <section id="form-details">
    <form action="" method="POST">
      <h2>Leave a Message</h2>
      
      <?php if (!empty($success_message)) : ?>
        <p class="success-message"><?php echo $success_message; ?></p>
      <?php endif; ?>

      <?php if (!empty($error_message)) : ?>
        <p class="error-message"><?php echo $error_message; ?></p>
      <?php endif; ?>

      <div class="mb-3">
        <label for="name" class="form-label">Your Name</label>
        <input type="text" class="form-control" id="name" name="name" required>
      </div>
      <div class="mb-3">
        <label for="email" class="form-label">Your Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
      </div>
      <div class="mb-3">
        <label for="message" class="form-label">Your Message</label>
        <textarea class="form-control" id="message" name="message" rows="4" required></textarea>
      </div>
      <button type="submit" class="btn btn-primary">Send Message</button>
    </form>

    
    <div class="reviews-list">
    <h3>Recent Reviews</h3>
      <?php if (count($random_reviews) > 0): ?>
        <?php foreach ($random_reviews as $review): ?>
          <div class="review-item">
            <h3><?php echo htmlspecialchars($review['name']); ?> <small>(<?php echo htmlspecialchars($review['email']); ?>)</small></h3>
            <p><?php echo nl2br(htmlspecialchars($review['message'])); ?></p>
            <small><em>Submitted on: <?php echo date('F j, Y, g:i a', strtotime($review['submitted_at'])); ?></em></small>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>No reviews found.</p>
      <?php endif; ?>
    </div>
  </section>

  <!-- Footer -->
  <?php require_once "../footer.php"; ?>

  <script src="/project/customer/script/script.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
