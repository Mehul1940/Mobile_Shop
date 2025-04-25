<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  <link rel="stylesheet" href="/Project/customer/style/help.css" />
  <title>Help - Nilkanth Mobiles</title>
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link rel="stylesheet" href="/project/customer/style/style.css">
  <style>
    .help-section {
      padding: 60px 20px;
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }

    .help-section h1 {
      font-size: 5rem;
      text-align: center;
      margin-bottom: 30px;
      color: #222;
      font-weight: bold;
    }

    .help-section p {
      font-size: 2.2rem;
      line-height: 1.8;
      color: #555;
      margin-bottom: 30px;
      text-align: center;
    }

    .help-section .faq,
    .contact-support,
    .resources {
      margin: 30px 0;
      padding: 30px;
      background: #fff;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
      border-radius: 10px;
    }

    .help-section h2 {
      font-size: 2rem;
      color: #444;
      margin-bottom: 20px;
    }
    .help-section ul {
      list-style-type: none;
      padding: 0;
    }

    .help-section ul li {
      margin-bottom: 20px;
      font-size: 20px;
    }

    .help-section ul li strong {
      display: block;
      color: #333;
      font-size: 20px;
      margin-bottom: 10px;
    }

    .help-section ul li p {
      margin: 0;
      font-size: 16px;
      color: #666;
    }

    .help-section .resources ul li a {
      text-decoration: none;
      color: #007bff;
      font-weight: 500;
      transition: color 0.3s;
    }

    .help-section .resources ul li a:hover {
      color: #0056b3;
    }

    .help-section .contact-support ul li {
      margin-bottom: 15px;
      font-size: 2.1rem;
    }
  </style>
</head>

<body>
  <!-- Header Section -->
    <?php require_once "../header.php"?>

  <!-- Help Section -->
  <section class="help-section">
    <div class="box">
      <h1>Help & Support</h1>
      <p>
        Welcome to Nilkanth Mobiles help page. Here, you'll find answers to
        common questions and ways to reach us for further support.
      </p>

      <div class="faq">
        <h2><center>Frequently Asked Questions (FAQs)</center></h2>
        <ul>
          <li>
            <strong><center>Q: How can I place an order?</center></strong>
            <p>
              A: Browse our products, add items to your cart, and proceed to
              checkout.
            </p>
          </li>
          <li>
            <strong><center>Q: What are the accepted payment methods?</center></strong>
            <p>
              A: We accept credit cards, debit cards, UPI, net banking, and
              cash on delivery.
            </p>
          </li>
          <li>
            <strong><center>Q: Can I watch my order?</center></strong>
            <p>
              A: Yes, log in to your account and visit the "Orders"
                 In your Profile.
            </p>
          </li>
          <li>
            <strong><center>Q: What is your return policy?</center></strong>
            <p>
              A: We offer a 7-day return policy on eligible items. 
            </p>
          </li>
        </ul>
      </div>

      <div class="contact-support">
        <h2>Contact Support</h2>
        <p>If you need additional help, reach out to us:</p>
        <ul>
          <li><strong>Phone:</strong> (+91) 9974311445 / (+91) 9974311447</li>
          <li><strong>Email:</strong> support@nilkanthmobiles.com</li>
          <li>
            <strong>Address:</strong> G/3 Vihak Park-2, Nr Shree Ram Sweets,
            Maheshwari Nagar Road, Odhav, Ahmedabad-382415
          </li>
        </ul>
      </div>

      <div class="resources">
        <h2>Additional Resources</h2>
        <ul>
          <li><a href="/Project/customer/pages/privacyp.php">Privacy Policy</a></li>
          <li><a href="/Project/customer/pages/tc.php">Terms and Conditions</a></li>
          <!-- <li><a href="return-policy.html">Return Policy</a></li> -->
        </ul>
      </div>
    </div>
  </section>

  <!-- footer -->
  <?php require_once "../footer.php" ?>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>