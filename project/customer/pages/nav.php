<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Responsive Navbar</title>
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <style>
      .navbar {
        background-color: navy;
      }

      .navbar-toggler {
        background-color: white;
      }

      .nav-link,
      .dropdown-item {
        color: white !important;
        font-size: 16px;
        transition: color 0.3s ease;
      }

      .nav-link:hover,
      .dropdown-item:hover {
        color: #d1e0ff !important;
        text-decoration: underline;
      }

      .dropdown-menu {
        background-color: navy;
        border: none;
      }

      .btn-outline-light {
        color: white;
        border-color: white;
        font-size: 16px;
        padding: 5px 10px;
        margin: 5px 0;
        transition: background-color 0.3s ease, color 0.3s ease;
      }

      .btn-outline-light:hover {
        background-color: white;
        color: navy;
      }

      #search-wrapper {
        display: flex;
        align-items: center;
        padding: 0.5rem;
      }

      #search-input {
        width: 200px;
        height: 35px;
        border: 1px solid #ccc;
        border-radius: 20px;
        padding: 0 1rem;
        font-size: 14px;
        outline: none;
        transition: all 0.3s ease;
      }

      #search-input:focus {
        width: 250px;
        border-color: midnightblue;
        box-shadow: 0 0 5px rgba(0, 0, 255, 0.5);
      }

      #search-button {
        background-color: midnightblue;
        color: white;
        border: none;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-left: -40px;
        transition: background-color 0.3s ease;
      }

      #search-button:hover {
        background-color: navy;
      }

      #search-button i {
        font-size: 16px;
      }

      @media (max-width: 768px) {
        #search-input {
          width: 150px;
        }

        #search-input:focus {
          width: 200px;
        }

        .btn-outline-light {
          font-size: 14px;
        }

        .navbar-nav .nav-item {
          text-align: center;
          margin: 5px 0;
        }
      }

      @media (max-width: 576px) {
        #search-input {
          width: 120px;
        }

        #search-input:focus {
          width: 180px;
        }

        .btn-outline-light {
          font-size: 12px;
        }

        .navbar-nav {
          flex-direction: column;
        }
      }
    </style>
  </head>
  <body>
    <nav class="navbar navbar-expand-lg">
      <div class="container-fluid">
        <a href="index.html">
          <img
            src="/project/customer/assets/logo/1.png"
            alt="Nilkanth Mobiles Logo"
            class="logo"
          />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarContent"
          aria-controls="navbarContent"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" href="index.html">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                href="#"
                id="categoriesDropdown"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                Categories
              </a>
              <ul class="dropdown-menu" aria-labelledby="categoriesDropdown">
                <li><a class="dropdown-item" href="#">Smartphones</a></li>
                <li><a class="dropdown-item" href="#">Headphones</a></li>
                <li><a class="dropdown-item" href="#">Earbuds</a></li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li><a class="dropdown-item" href="#">SmartWatches</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="shop.html">Shop</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="service.html">Services</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="about.html">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="contact.html">Contact</a>
            </li>
          </ul>
          <div class="d-flex">
            <div id="search-wrapper">
              <input
                type="text"
                id="search-input"
                placeholder="Search products..."
              />
              <button id="search-button">
                <i class="fas fa-search"></i>
              </button>
            </div>
            <a href="cart.html" class="btn btn-outline-light">
              <i class="fas fa-shopping-cart"></i> Cart
            </a>
            <a href="profile.html" class="btn btn-outline-light">
              <i class="fas fa-user"></i> Profile
            </a>
            <a href="login.html" class="btn btn-outline-light">
              <i class="fas fa-user-plus"></i> Login
            </a>
            <a href="register.html" class="btn btn-outline-light">
              <i class="fas fa-user-plus"></i> Register
            </a>
          </div>
        </div>
      </div>
    </nav>
    <script src="/project/cu/project/customer/assets/script/script.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
