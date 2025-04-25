<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Nilkanth Mobiles</title>
  <!-- CSS Links -->
  <link rel="stylesheet" href="/project/customer/style/style.css" />
  <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .about-section {
      background-color: #f8f9fa;
      padding: 60px 0;
    }

    .about-image {
      max-width: 100%;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .about-text {
      color: #333;
    }

    #page-header h2 {
      font-size: 46px;
      line-height: 56px;
    }
    #page-header p {
    font-size: 16px;
    margin: 15px 0 20px 0;
}
  </style>

  </style>
</head>

<body>
  <!-- Header Section -->
   <?php require_once "../header.php"?>

  <!-- Hero Section -->
  <section id="page-header" class="about-header">
    <h2>#KnowUs</h2>
    <p>Discover Our Story, Embrace Our Values</p>
  </section>

  <!-- About Us Section -->
  <div class="about-section section-p1">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-6">
          <img src="/project/customer/assets/about/IMG_20250111_230455.png" alt="About Nilkanth Mobiles" class="about-image" />
        </div>
        <div class="col-md-6">
          <h2>About Us</h2>
          <p class="about-text">
            Welcome to Nilkanth Mobiles, your one-stop destination for the
            latest smartphones, smartwatches, and accessories. With years of
            experience in the mobile retail industry, we are dedicated to
            offering our customers high-quality products at competitive
            prices.
          </p>
          <p class="about-text">
            At Nilkanth Mobiles, we believe in providing exceptional customer
            service and a seamless shopping experience. Whether you're looking
            for the latest gadgets or reliable after-sales support, our team
            is here to help.
          </p>
          <p class="about-text">
            Thank you for choosing Nilkanth Mobiles. We look forward to
            serving you!
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- footer -->
  <?php require_once "../footer.php"?>

  

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
</body>

</html>