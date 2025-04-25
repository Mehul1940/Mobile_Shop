<?php require_once "/xampp/htdocs/Project/config/admincheck.php" ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEELKANTH MOBILE ADMIN DASHBOARD</title>
    <link rel="stylesheet" href="/Project/assets/css/style.css">
</head>

<body>
    <!-- Navigation  -->
    <div class="container">
        <?php require_once  "../Nav.php" ?>
        <!-- Main -->
        <div class="main">
            <?php require_once "../main.php" ?>
            <!-- Cards -->
            <?php require_once "../card.php" ?>
        </div>
    </div>
    <!-- Scripts -->
    <script src="/Project/assets/css/style.css"></script>
    <!-- Icons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- BootStrap -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js" integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous"></script>
</body>

</html>
