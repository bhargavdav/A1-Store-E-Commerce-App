<?php
include("./includes/connect.php");
include("./functions/common_functions.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Products</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.css" />
    <link rel="stylesheet" href="./assets/css/main.css" />
</head>

<body>
    <?php
    include('./includes/header.php');
    ?>
    <!-- End NavBar -->


    <!-- Start Product details  -->
    <div class="prod-details">
        <div class="container">
            <div class="sub-container pt-4 pb-4">

                <?php
                viewDetails();
                ?>
            </div>
        </div>
    </div>
    <!-- End Product details  -->

    <!-- Start Products  -->
    <div class="products">
        <div class="container">
            <div class="categ-header">
                <div class="sub-title">
                    <span class="shape"></span>
                    <span class="title">Related Products</span>
                </div>
                <h2>Discover More Products</h2>
            </div>
            <div class="row mb-3">
                <?php
                getProduct(3);
                cart();
                ?>
            </div>
            <div class="view d-flex justify-content-center align-items-center">
                <button onclick="location.href='./products.php'">View More Products</button>
            </div>
        </div>
    </div>
    <!-- End Products  -->

    <?php
    $product_id = $_GET['product_id'];
    $review_query = "SELECT r.*, u.username FROM reviews r JOIN user_table u ON r.user_id = u.user_id WHERE r.product_id='$product_id'";
    $review_result = mysqli_query($con, $review_query);

    echo "<h4 class='mt-5'>Customer Reviews</h4>";
    while ($review = mysqli_fetch_assoc($review_result)) {
        echo "<div class='border p-3 mb-2'>";
        echo "<strong>" . $review['username'] . "</strong> ";
        echo "<span>Rating: " . str_repeat("⭐", $review['rating']) . "</span><br>";
        echo "<p>" . $review['review_text'] . "</p>";
        echo "</div>";
    }
    ?>










    <!-- divider  -->
    <!-- <div class="container">
        <div class="divider"></div>
    </div> -->
    <!-- divider  -->




    <!-- Start Footer -->
    <!-- <div class="upper-nav primary-bg p-2 px-3 text-center text-break">
        <span>All CopyRight &copy;2023</span>
    </div> -->
    <!-- End Footer -->

    <script src="./assets//js/bootstrap.bundle.js"></script>
    <script src="./assets//js/script.js"></script>
</body>

</html>