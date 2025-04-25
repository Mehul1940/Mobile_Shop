<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Nilkanth Mobiles</title>
    <link
      rel="stylesheet"
      href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="/project/customer/style/style.css" />
    <link
      rel="stylesheet"
      href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
    />
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <style>
      .container {
        display: flex;
        max-width: 1200px;
        margin: 2rem auto;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
        overflow: hidden;
      }

      .sidebar {
        width: 25%;
        background: #f8f8f8;
        padding: 1.5rem;
        border-right: 1px solid #ddd;
      }

      .profile-info {
        text-align: center;
        margin-bottom: 2rem;
      }

      .sidebar .profile-picture {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 50%;
        margin-bottom: 1rem;
        border: 2px dashed midnightblue;
      }

      .sidebar .profile-info h2 {
        font-size: 2.7rem;
        margin-bottom: -0.5rem;
      }
      .sidebar .profile-info p {
        font-size: 17px;
      }

      .menu {
        list-style: none;
      }

      .menu li {
        margin: 1rem 0;
      }

      .sidebar .menu li a {
        text-decoration: none;
        color: #333;
        font-size: 2rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
      }

      .sidebar .menu li a:hover,
      .menu li.active a {
        color: #fff;
        background-color: midnightblue;
        padding: 0.5rem;
        border-radius: 4px;
      }
      .sidebar .menu li.active a {
        background-color: midnightblue;
      }

      .badge {
        background: midnightblue;
        color: #fff;
        padding: 0.2rem 0.5rem;
        border-radius: 50%;
        font-size: 0.8rem;
      }

      .main-content {
        flex: 1;
        padding: 2rem;
      }

      .container .main-content h1 {
        margin-bottom: 1.5rem;
        font-size: 2.5rem;
      }

      .profile-form {
        max-width: 600px;
      }

      .form-row {
        display: flex;
        gap: 1rem;
        margin-bottom: 1rem;
      }

      .form-group {
        flex: 1;
      }

      .profile-form .form-group label {
        display: block;
        font-size: 1.9rem;
        margin-bottom: 0.5rem;
        color: #666;
      }

      .profile-form .form-group input {
        width: 100%;
        padding: 1.3rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 12px;
      }

      .profile-form .update-button {
        background: midnightblue;
        color: #fff;
        padding: 0.8rem 1.5rem;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        margin-top: 1rem;
        font-size: 20px;
      }

      .profile-form .update-button:hover {
        background: rgb(14, 14, 123);
      }
    </style>
  </head>
  <body>
    <?php
    // Database connection
    $conn = new mysqli('localhost', 'root', '', 'project');

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Assume the current user's ID is stored in a session variable
    // session_start();
    $currentUserId = $_SESSION['CUSTOMER_ID'];

    // Fetch current user data
    $sql = "SELECT CUSTOMER_NAME, EMAIL FROM CUSTOMER WHERE CUSTOMER_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $currentUserId);
    $stmt->execute();
    $result = $stmt->get_result();

    $user = $result->fetch_assoc();
    ?>
    <aside class="sidebar">
      <div class="profile-info">
        <h2><?php echo htmlspecialchars($user['CUSTOMER_NAME']); ?></h2>
        <p><?php echo htmlspecialchars($user['EMAIL']); ?></p>
      </div>
      <ul class="menu">
        <li><a href="/project/customer/pages/orders.php" class="sidebar-link">Orders</a></li>
        <li><a href="/project/customer/pages/profile.php" class="sidebar-link">Profile</a></li>
        <li><a href="/project/customer/pages/changepassword.php" class="sidebar-link">Change Password</a></li>
        <li><a href="/Project/customer/logout.php" class="sidebar-link">Sign Out</a></li>
      </ul>
    </aside>

    <script src="/project/customer/script/script.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
    ></script>
    
    <script>
      document.addEventListener("DOMContentLoaded", function () {
        // Get all sidebar links
        const sidebarLinks = document.querySelectorAll(".sidebar-link");

        // Get the current page URL
        const currentUrl = window.location.pathname;

        // Remove 'active' from all links, then add 'active' to the matching one
        sidebarLinks.forEach((link) => {
          if (link.getAttribute("href") === currentUrl) {
            link.parentElement.classList.add("active");
          } else {
            link.parentElement.classList.remove("active");
          }
        });
      });
    </script>
  </body>
</html>
