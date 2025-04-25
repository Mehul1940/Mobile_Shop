<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Main</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
    <script src="/Project/assets/js/main.js" defer></script>
    <style>
        /* Flexbox styles for aligning image and text */
        .user-info {
            display: flex;
            align-items: center;
            /* Vertically align items */
        }

        .user-info img {
            width: 40px;
            /* Adjust image size */
            height: 40px;
            border-radius: 50%;
            /* Circular image */
            margin-right: 10px;
            /* Space between image and text */
        }

        .user-info h5 {
            margin: 0;
            font-size: 16px;
            /* Adjust font size as needed */
        }
    </style>
</head>

<body>
    <div class="topbar">
        <div class="toggle">
            <ion-icon name="menu-outline"></ion-icon>
        </div>
        <div>
            DASHBOARD
        </div>
        <div class="user-info">
            <div class="user">
                <img src="/Project/assets/imgs/1.png" />
            </div>
            <h5>
                <!-- Display logged-in user's email -->
                <?php
                if (isset($_SESSION['user_email'])) {
                    echo htmlspecialchars($_SESSION['user_email']);
                } else {
                    echo "Guest";
                }
                ?>
            </h5>
        </div>
    </div>

    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>