<?php
session_start(); // Start the session

// Database Connection
$con = mysqli_connect("localhost", "root", "", "project");

// Check if connection is successful
if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

// Display success message for booking
if (isset($_GET['booking_success']) && $_GET['booking_success'] == 1) {
    echo "<script>alert('Booking request submitted successfully! You will receive an estimate soon.');</script>";
}

// Display success message for review
if (isset($_GET['review_success']) && $_GET['review_success'] == 1) {
    echo "<script>alert('Review submitted successfully!');</script>";
}

// Fetch service data (Selecting SERVICE_NAME and PRICE)
$query = "SELECT SERVICE_NAME, PRICE FROM services";
$result = mysqli_query($con, $query);
$services = [];

while ($row = mysqli_fetch_assoc($result)) {
    $services[] = $row;
}

// Fetch all services for the review form dropdown
$query_services = "SELECT SERVICE_NAME FROM services";
$result_services = mysqli_query($con, $query_services);
$service_names = [];

while ($row = mysqli_fetch_assoc($result_services)) {
    $service_names[] = $row['SERVICE_NAME'];
}

// Fetch user's name, email, and address from the session
$customer_name = "";
$customer_email = "";
$customer_address = "";

if (isset($_SESSION['CUSTOMER_ID'])) {
    $customer_id = $_SESSION['CUSTOMER_ID'];
    $query_user = "SELECT CUSTOMER_NAME, EMAIL, ADDRESS FROM customer WHERE CUSTOMER_ID = ?";
    $stmt = $con->prepare($query_user);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $result_user = $stmt->get_result();
    $user_data = $result_user->fetch_assoc();
    $customer_name = $user_data['CUSTOMER_NAME'];
    $customer_email = $user_data['EMAIL'];
    $customer_address = $user_data['ADDRESS']; // Fetch address from the database
    $stmt->close();
}

// Handle Booking Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_booking'])) {
    // Check if user is logged in
    if (!isset($_SESSION['CUSTOMER_ID'])) {
        echo "<script>alert('Please login first to book a service.');</script>";
        echo "<script>setTimeout(function() { window.location.href = '/project/customer/login.php'; }, 3000);</script>";
        exit();
    }

    $address = $_POST['address']; // Added address field
    $service = $_POST['service'];
    $notes = $_POST['notes'];

    if (!empty($customer_name) && !empty($customer_email) && !empty($address) && !empty($service)) {
        // Prepare SQL Statement (Including the address field)
        $stmt = $con->prepare("INSERT INTO service_bookings (customer_name, customer_email, address, service_type, notes, status) VALUES (?, ?, ?, ?, ?, 'Pending')");

        // Check if prepare() failed
        if (!$stmt) {
            die("SQL Error: " . $con->error);
        }

        // Bind Parameters (Including the address field)
        $stmt->bind_param("sssss", $customer_name, $customer_email, $address, $service, $notes);

        // Execute and Check for Errors
        if ($stmt->execute()) {
            echo "<script>alert('Booking request submitted successfully! You will receive an estimate soon.'); window.location.href='index.php';</script>";
        } else {
            echo "<script>alert('Error submitting booking. Please try again.');</script>";
        }

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

        $stmt->close();
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}

// Handle Review Form Submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_review'])) {
    // Check if user is logged in
    if (!isset($_SESSION['CUSTOMER_ID'])) {
        echo "<script>alert('Please login first to submit a review.');</script>";
        echo "<script>setTimeout(function() { window.location.href = '/project/customer/login.php'; }, 1000);</script>";
        exit();
    }

    $service_name = $_POST['service_name'];
    $user_name = $_POST['user_name'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    if (!empty($service_name) && !empty($user_name) && !empty($rating) && !empty($comment)) {
        // Prepare SQL Statement
        $stmt = $con->prepare("INSERT INTO service_reviews (service_name, user_name, rating, comment) VALUES (?, ?, ?, ?)");

        // Check if prepare() failed
        if (!$stmt) {
            die("SQL Error: " . $con->error);
        }

        // Bind Parameters
        $stmt->bind_param("ssis", $service_name, $user_name, $rating, $comment);

        // Execute and Check for Errors
        if ($stmt->execute()) {
            // Redirect to prevent form resubmission
            header("Location: index.php?review_success=1");
            exit();
        } else {
            die("Execution Error: " . $stmt->error);
        }

        $stmt->close();
    } else {
        echo "<script>alert('All fields are required.');</script>";
    }
}

// Fetch 3 Random Reviews from Database
$query = "SELECT * FROM service_reviews ORDER BY RAND() LIMIT 3";
$result = mysqli_query($con, $query);
$reviews = [];

while ($row = mysqli_fetch_assoc($result)) {
    $reviews[] = $row;
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Nilkanth Mobiles Repair Service</title>
   <link rel="stylesheet" href="/project/customer/style/style2.css">
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <style>
     .booking {
          background: #f5f5f5;
          padding: 50px 0;
          text-align: center;
      }
      .booking .container {
          margin: 0 auto;
          background: #fff;
          padding: 30px;
          border-radius: 10px;
          box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
      }
      .booking-form {
          display: flex;
          flex-direction: column;
          gap: 15px;
          width: 100%;
          max-width: 500px;
          margin: auto;
      }
      .booking-form input,
      .booking-form select,
      .booking-form textarea {
          width: 100%;
          padding: 12px;
          border: 1px solid #ccc;
          border-radius: 5px;
          font-size: 16px;
      }
      .booking-form button {
          background: midnightblue;
          color: #fff;
          padding: 12px;
          border: none;
          border-radius: 5px;
          font-size: 18px;
          cursor: pointer;
          transition: background 0.3s ease;
      }
      .booking-form button:hover {
          background: #191970;
      }
      h6 {
          font-weight: 700;
          font-size: 12px;
          color: red;
      }
      .review-form .heading {
   text-align: center;
   font-size: 3rem;
   color: #333;
   padding: 1rem;
   margin: 2rem 0;
}

.review-form .heading span {
   color: #191970;
}

.review-form .row {
   display: flex;
   flex-wrap: wrap;
   gap: 2rem;
}

.review-form .row .map {
   flex: 1 1 45rem;
   width: 100%;
   height: 450px;
   border: 1px solid #ccc;
   border-radius: 10px;
}

.review-form .row form {
   flex: 1 1 45rem;
   padding: 2rem;
   border: 1px solid #ccc;
   border-radius: 10px;
   background: #f9f9f9;
}

.review-form .row form h3 {
   font-size: 2.5rem;
   color: #333;
   margin-bottom: 1rem;
}

.review-form .row form .inputBox {
   display: flex;
   flex-wrap: wrap;
   gap: 1rem;
}

.review-form .row form .inputBox input,
.review-form .row form .inputBox select {
   flex: 1 1 20rem;
   padding: 1rem;
   border: 1px solid #ccc;
   border-radius: 5px;
   font-size: 1.6rem;
   margin-bottom:17px;
}

.review-form .row form textarea {
   width: 100%;
   padding: 1rem;
   border: 1px solid #ccc;
   border-radius: 5px;
   font-size: 1.6rem;
   margin-top: 1rem;
   resize: none;
}

.review-form .row form .btn {
   display: inline-block;
   margin-top: 1rem;
   padding: 1rem 3rem;
   font-size: 1.8rem;
   color: #fff;
   background: #191970;
   border: none;
   border-radius: 5px;
   cursor: pointer;
}

.review-form .row form .btn:hover {
   background: #191970;
}
   </style>
</head>
<body>

<!-- Header -->
<?php require_once "../header.php"; ?>

<!-- Home Section -->
<section class="home" id="home">
   <div class="image">
      <img src="/project/customer/assets/service/IMG_20250113_092450.jpg" alt="">
   </div>
   <div class="content">
      <h3>Why fix it yourself? Leave it to the pros.</h3>
      <p>We specialize in bringing your devices back to life. From cracked screens to battery issues, we've got you covered!</p>
      <a href="#about" class="btn">Continue</a>
   </div>
</section>

<!-- Fun Fact Section -->
<section class="fun-fact">
   <div class="box">
      <img src="/project/customer/assets/service/fun-fact-icon-1.svg" alt="">
      <div class="info">
         <h3>150</h3>
         <p>repairs done</p>
      </div>
   </div>
   <div class="box">
      <img src="/project/customer/assets/service/fun-fact-icon-2.svg" alt="">
      <div class="info">
         <h3>5</h3>
         <p>awards won</p>
      </div>
   </div>
   <div class="box">
      <img src="/project/customer/assets/service/fun-fact-icon-3.svg" alt="">
      <div class="info">
         <h3>145</h3>
         <p>happy clients</p>
      </div>
   </div>
   <div class="box">
      <img src="/project/customer/assets/service/fun-fact-icon-4.svg" alt="">
      <div class="info">
         <h3>5</h3>
         <p>active workers</p>
      </div>
   </div>
</section>

<!-- About Section -->
<section class="about" id="about">
   <div class="content">
      <h3>best quality Repair services</h3>
      <p>Trust us to restore your devices with precision and care. Whether it's a cracked screen, water damage, or software issues, we deliver top-notch solutions every time.</p>
      <p>Our expert technicians use high-quality parts and advanced tools to ensure your device performs like new. Your satisfaction is our guarantee!</p>
      <a href="#meow" class="btn">Book Now</a>
   </div>
   <div class="image">
      <img src="/project/customer/assets/service/about-img.svg" alt="">
   </div>
</section>

<!-- Gallery Section -->
<section class="gallery" id="gallery">
   <h1 class="heading"> our <span>gallery</span> </h1>
   <div class="gallery-container">
      <a class="box" href="/project/customer/assets/service/g-img-4.jpg"><img src="/project/customer/assets/service/g-img-4.jpg" alt=""></a>
      <a class="box" href="/project/customer/assets/service/g-img-6.jpg"><img src="/project/customer/assets/service/g-img-6.jpg" alt=""></a>
   </div>
</section>

<!-- Facilities Section -->
<section class="facilities">
   <h1 class="heading"> why <span>choose us?</span> </h1>
   <div class="box-container">
      <div class="box">
         <img src="/project/customer/assets/service/why-choose-icon-1.svg" alt="">
         <h3>Dedicated Customer Support</h3>
         <p>Our team is always ready to assist you with any queries or concerns. Whether it's a repair update or a troubleshooting issue, we're here to help you every step of the way.</p>
      </div>
      <div class="box">
         <img src="/project/customer/assets/service/why-choose-icon-2.svg" alt="">
         <h3>Quality Service</h3>
         <p>We prioritize top-notch quality in every repair. From using premium parts to ensuring precise fixes, we guarantee that your device will perform like new, every time.</p>
      </div>
      <div class="box">
         <img src="/project/customer/assets/service/why-choose-icon-3.svg" alt="">
         <h3>Quick Repair</h3>
         <p>We understand the urgency of getting your device back up and running. Our skilled technicians work efficiently to provide fast and reliable repairs, so you're never without your device for long.</p>
      </div>
   </div>
</section>

<!-- Booking Section -->
<section id="meow" class="booking">
   <div class="container">
      <h2>Book Service</h2>
      <form class="booking-form" method="POST" action="">
          <input type="hidden" name="name" value="<?= htmlspecialchars($customer_name) ?>">
          <input type="hidden" name="email" value="<?= htmlspecialchars($customer_email) ?>">
          <input type="text" name="name" placeholder="Your Name" value="<?= htmlspecialchars($customer_name) ?>" readonly>
          <input type="email" name="email" placeholder="Your Email" value="<?= htmlspecialchars($customer_email) ?>" readonly>
          <select id="service" name="service" required>
              <option value="">Select Service</option>
              <?php foreach ($services as $service) : ?>
                  <option value="<?= $service['SERVICE_NAME'] ?>" data-price="<?= $service['PRICE'] ?>">
                      <?= $service['SERVICE_NAME'] ?>
                  </option>
              <?php endforeach; ?>
          </select>
          <input type="text" id="base-price" placeholder="Base Price" readonly>
          <textarea name="notes" placeholder="Give Description Of Your Mobile" required></textarea>
          <input type="text" name="address" placeholder="Your Address" value="<?= htmlspecialchars($customer_address) ?>" required> <!-- Pre-fill address -->
          <button type="submit" name="submit_booking" class="btn">Submit Booking</button>
          <?php if (isset($_SESSION['CUSTOMER_ID'])) : ?>
              <button type="button" onclick="window.location.href='view_booking.php';" class="btn">View Bookings</button>
          <?php else : ?>
              <p>Please <a href="/project/customer/login.php">login</a> to view your bookings.</p>
          <?php endif; ?>
          <h6><strong>Note:</strong> As per your phone, prices may vary</h6>
      </form>
   </div>
</section>

<!-- Reviews Section -->
<section class="reviews" id="reviews">
   <h1 class="heading"> clients <span>reviews</span> </h1>
   <div class="box-container">
      <?php if (!empty($reviews)) : ?>
         <?php foreach ($reviews as $review) : ?>
            <div class="box">
               <div class="star">
                  <?php
                  // Display stars based on rating
                  for ($i = 1; $i <= 5; $i++) {
                      if ($i <= $review['rating']) {
                          echo '<i class="fas fa-star"></i>';
                      } else {
                          echo '<i class="far fa-star"></i>';
                      }
                  }
                  ?>
               </div>
               <div class="text">
                  <i class="fas fa-quote-right"></i>
                  <p><?= htmlspecialchars($review['comment']) ?></p>
               </div>
               <div class="user">
                  <h3><?= htmlspecialchars($review['user_name']) ?></h3>
                  <p><?= htmlspecialchars($review['service_name']) ?></p>
                  <small><?= date("F j, Y, g:i a", strtotime($review['review_date'])) ?></small>
               </div>
            </div>
         <?php endforeach; ?>
      <?php else : ?>
         <div class="box">
            <p>No reviews found.</p>
         </div>
      <?php endif; ?>
   </div>
   <button onclick="window.location.reload();" class="btn">Refresh Reviews</button>
</section>

<!-- Review Form Section -->
<section class="review-form" id="review-form">
   <h1 class="heading"> submit a <span>review</span> </h1>

   <div class="row">
      <iframe class="map" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d58739.736143969836!2d72.57963284034254!3d23.051899225930136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e876792d5d1c7%3A0xb929d3f94b3f6c2e!2sNeelkanth%20mobile!5e0!3m2!1sen!2sin!4v1736619952158!5m2!1sen!2sin"
         width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
         referrerpolicy="no-referrer-when-downgrade"></iframe>

      <?php if (isset($_SESSION['CUSTOMER_ID'])) : ?>
         <form method="POST" action="">
            <h3>share your experience</h3>
            <div class="inputBox">
               <select name="service_name" required>
                  <option value="">Select Service</option>
                  <?php foreach ($service_names as $service_name) : ?>
                     <option value="<?= htmlspecialchars($service_name) ?>"><?= htmlspecialchars($service_name) ?></option>
                  <?php endforeach; ?>
               </select>
               <input type="text" name="user_name" placeholder="Your Name" required>
            </div>
            <div class="inputBox">
               <select name="rating" required>
                  <option value="">Select Rating</option>
                  <option value="1">1 Star</option>
                  <option value="2">2 Stars</option>
                  <option value="3">3 Stars</option>
                  <option value="4">4 Stars</option>
                  <option value="5">5 Stars</option>
               </select>
            </div>
            <textarea name="comment" placeholder="Your Review" cols="30" rows="10" required></textarea>
            <input type="submit" name="submit_review" value="Submit Review" class="btn">
         </form>
      <?php else : ?>
         <p>Please <a href="/project/customer/login.php">login</a> to submit a review.</p>
      <?php endif; ?>
   </div>
</section>

<!-- Footer -->
<?php require_once "../footer.php"; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Update base price when service is selected
    document.getElementById("service").addEventListener("change", function() {
        var selectedOption = this.options[this.selectedIndex];
        var price = selectedOption.getAttribute("data-price"); // Get the price from the selected option
        document.getElementById("base-price").value = price ? price : ""; // Update the base price field
    });
});
</script>
<script>
      document.addEventListener("DOMContentLoaded", function() {
          // Check if the URL contains the review_success parameter
          const urlParams = new URLSearchParams(window.location.search);
          if (urlParams.has('review_success') && urlParams.get('review_success') === '1') {
              alert('Review submitted successfully!');
          }
      });
   </script>
</body>
</html>