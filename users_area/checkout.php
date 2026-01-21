<?php
include('../includes/connect.php');
// include('../functions/common_functions.php');
@session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Checkout Page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>

<body>
    <!-- <div class="landing"> -->
    <!-- <div class="container"> -->
    <div class="container_b">

        <div class="row m-0">
            <?php
            if (!isset($_SESSION['username'])) {
                include('user_login.php');
            } else {
                include('payment.php');
            }
            ?>
        </div>
    </div>
    <!-- </div> -->
    <!-- </div> -->
    <!-- End Landing Section -->
    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>