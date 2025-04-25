<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="x-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <title>Delivery Information - Nilkanth Mobiles</title>
    <link
      rel="stylesheet"
      href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        /* Delivery Information Section */
.delivery-info {
  background-color: #f9f9f9;
  padding: 40px 20px;
  margin-top: 20px;
}

.delivery-info .box {
  max-width: 900px;
  margin: 0 auto;
  padding: 30px;
  background-color: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 8px;
}

.delivery-info h1 {
  font-size: 36px;
  color: #333;
  text-align: center;
  margin-bottom: 20px;
  font-weight: 600;
}

.delivery-info p {
  font-size: 16px;
  line-height: 1.6;
  margin-bottom: 20px;
  color: #555;
}

.delivery-info h2 {
  font-size: 28px;
  color: #333;
  margin-top: 20px;
  font-weight: 500;
}

.delivery-info ul {
  list-style-type: none;
  padding-left: 20px;
}

.delivery-info ul li {
  font-size: 16px;
  margin-bottom: 10px;
  color: #444;
}

.delivery-info ul li::before {
  content: "✔️";
  margin-right: 10px;
}

.delivery-info ul li:hover {
  background-color: #f0f0f0;
  padding: 8px;
  border-radius: 5px;
}

/* Important Notes Section */
.delivery-info p strong {
  font-weight: bold;
  color: #333;
}

.delivery-info .important-notes {
  background-color: #fff3cd;
  border-left: 5px solid #ffbc00;
  padding: 10px 20px;
  margin-top: 20px;
  font-size: 16px;
  color: #333;
  border-radius: 5px;
}

.delivery-info .important-notes ul {
  margin-top: 10px;
}

.delivery-info .important-notes ul li {
  font-size: 16px;
  margin-bottom: 5px;
}

/* Media Queries for Responsiveness */
@media (max-width: 768px) {
  .delivery-info .box {
    padding: 20px;
  }

  .delivery-info h1 {
    font-size: 28px;
  }

  .delivery-info h2 {
    font-size: 24px;
  }

  .delivery-info ul li {
    font-size: 14px;
  }
}

    </style>
  </head>
  <body>
   <!-- Header Section -->
    <?php require_once "../header.php"?>

    <!-- Delivery Information Section -->
    <section class="delivery-info section-p1">
      <div class="box">
        <h1>Delivery Information</h1>
        <p>
          At Nilkanth Mobiles, we aim to provide a seamless delivery experience
          for all our customers. Below, you'll find detailed information about
          our delivery policies and processes.
        </p>

        <h2>Delivery Timeframe</h2>
        <p>
          Orders are typically processed within 1-2 business days. Delivery times
          vary depending on your location:
        </p>
        <ul>
          <li>Local (Ahmedabad): 1-2 business days</li>
          <li>Regional: 3-5 business days</li>
          <li>National: 5-7 business days</li>
        </ul>

        <h2>Shipping Charges</h2>
        <p>
          Shipping charges are calculated based on the order total and the
          delivery location:
        </p>
        <ul>
          <li>Orders above ₹5000: Free delivery</li>
          <li>Orders below ₹5000: ₹50 - ₹200 depending on the location</li>
        </ul>

        <h2>Tracking Your Order</h2>
        <p>
          Once your order is shipped, we will provide a tracking number via
          email or SMS. You can use this number to track your order on our
          delivery partner's website.
        </p>

        <h2>Delivery Partners</h2>
        <p>
          We work with trusted delivery partners to ensure your orders arrive
          safely and on time. Some of our partners include:
        </p>
        <ul>
          <li>Blue Dart</li>
          <li>Delhivery</li>
          <li>India Post</li>
        </ul>

        <h2>Important Notes</h2>
        <p>
          - Please ensure your address and contact information are accurate at
          the time of checkout.<br />
          - If you face any issues with your delivery, contact our support team
          at (+91)9974311445.
        </p>
      </div>
    </section>

   <!-- footer -->
   <?php require_once "../footer.php"?>

    <script src="/project/customer/script/script.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
  </body>
</html>
