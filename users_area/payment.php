<?php
include('../includes/connect.php');
include('../functions/common_functions.php');

// session_start();
// Get user info
$user_ip = getIPAddress();
$get_user_query = "SELECT * FROM `user_table` WHERE user_ip='$user_ip'";
$get_user_result = mysqli_query($con, $get_user_query);
$fetch_user = mysqli_fetch_array($get_user_result);
$user_id = $fetch_user['user_id'];

// Also store user ID if not already stored
$_SESSION['user_id'] = $user_id;

$total_price = total_cart_price(); // You must return this from your function
$_SESSION['cart_total'] = $total_price;

$cart_query = "SELECT * FROM card_details WHERE ip_address='$user_ip'";
$cart_result = mysqli_query($con, $cart_query);
$cart_items = [];
while ($row = mysqli_fetch_assoc($cart_result)) {
    $cart_items[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity']
    ];
}
$_SESSION['cart_items'] = $cart_items;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Payment Page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

</head>

<body>
    <!-- upper-nav -->
    <div class="upper-nav primary-bg p-2 px-3 text-center text-break">
        <span>Summer Sale For All Swim Suits And Free Express Delivery - OFF 50%! <a>Shop Now</a></span>
    </div>
    <!-- upper-nav -->
    <!-- Start NavBar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">A1</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="../index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../products.php">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../review_rating.php">Rating Review</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../contact.php">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./user_registration.php">Register</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-primary" type="submit">Search</button>
                </form>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="./cart.php"><svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 27C11.5523 27 12 26.5523 12 26C12 25.4477 11.5523 25 11 25C10.4477 25 10 25.4477 10 26C10 26.5523 10.4477 27 11 27Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M25 27C25.5523 27 26 26.5523 26 26C26 25.4477 25.5523 25 25 25C24.4477 25 24 25.4477 24 26C24 26.5523 24.4477 27 25 27Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M3 5H7L10 22H26" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M10 16.6667H25.59C25.7056 16.6667 25.8177 16.6267 25.9072 16.5535C25.9966 16.4802 26.0579 16.3782 26.0806 16.2648L27.8806 7.26479C27.8951 7.19222 27.8934 7.11733 27.8755 7.04552C27.8575 6.97371 27.8239 6.90678 27.7769 6.84956C27.73 6.79234 27.6709 6.74625 27.604 6.71462C27.5371 6.68299 27.464 6.66661 27.39 6.66666H8" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <sup>
                                <?php
                                cart_item();
                                ?>
                            </sup>
                            <span class="d-none">
                                Total Price is:
                                <?php
                                total_cart_price();
                                ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" class="d-flex align-items-center gap-1" href="#">
                            <svg width="28" height="28" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M24 27V24.3333C24 22.9188 23.5224 21.5623 22.6722 20.5621C21.8221 19.5619 20.669 19 19.4667 19H11.5333C10.331 19 9.17795 19.5619 8.32778 20.5621C7.47762 21.5623 7 22.9188 7 24.3333V27" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M16.5 14C18.9853 14 21 11.9853 21 9.5C21 7.01472 18.9853 5 16.5 5C14.0147 5 12 7.01472 12 9.5C12 11.9853 14.0147 14 16.5 14Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <?php
                            if (!isset($_SESSION['username'])) {
                                echo "<span>
                                    Welcome guest
                                </span>";
                            } else {
                                echo "<span>
                                    Welcome " . $_SESSION['username'] . "</span>";
                            }
                            ?>
                        </a>
                    </li>
                    <?php
                    if (!isset($_SESSION['username'])) {
                        echo "<li class='nav-item'>
                        <a class='nav-link' href='./user_login.php'>
                            Login
                        </a>
                    </li>";
                    } else {
                        echo "<li class='nav-item'>
                        <a class='nav-link' href='./logout.php'>
                            Logout
                        </a>
                    </li>";
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End NavBar -->
    <!-- php code to access user id  -->
    <?php
    $user_ip = getIPAddress();
    $get_user_query = "SELECT * FROM `user_table` WHERE user_ip='$user_ip'";
    $get_user_result = mysqli_query($con, $get_user_query);
    $fetch_user = mysqli_fetch_array($get_user_result);
    $user_id = $fetch_user['user_id'];

    ?>
    <?php
    // session_start();
    $total_price = isset($_SESSION['cart_total']) ? $_SESSION['cart_total'] : 0;
    $total_price_in_paise = $total_price * 100;
    ?>

    <!-- php code to access user id  -->
    <!-- Start Landing Section -->
    <div class="landing">
        <div class="container">
            <h1 class="text-center mt-2 mb-5">Payment options</h1>
            <div class="row m-0 align-items-center justify-content-center">
                <div class="col-md-6 d-flex justify-content-center align-items-center gap-2">
                    <button id="razorpay-button" class="btn btn-outline-primary">Pay with Razorpay</button>
                </div>
            </div>
        </div>
    </div>

    <!-- End Landing Section -->
    <script>
        document.getElementById('razorpay-button').onclick = function(e) {
            var options = {
                "key": "your razorpay key", // Enter the Key ID generated from Razorpay Dashboard
                "amount": "<?php echo $total_price_in_paise; ?>", // Amount is in currency subunits
                "currency": "INR",
                "name": "A1 Store",
                "description": "Test Transaction",
                "image": "https://yourdomain.com/logo.png",
                "handler": function(response) {
                    // Send response.razorpay_payment_id to server for verification
                    alert("Payment successful. Payment ID: " + response.razorpay_payment_id);
                    // You can send this response to your server for verification
                    // and then redirect
                    console.log(response); // for debugging

                    // Redirect after successful payment
                    window.location.href = "payment_success.php?payment_id=" + response.razorpay_payment_id;
                },
                "prefill": {
                    "name": "<?php echo $_SESSION['username'] ?? 'Guest'; ?>",
                    "email": "?php echo $_SESSION['user_email']; ?>",
                    "contact": "?php echo $_SESSION['user_mobile']; ?>"
                },
                "theme": {
                    "color": "#3399cc"
                }
            };
            var rzp1 = new Razorpay(options);
            rzp1.open();
            e.preventDefault();
        }
    </script>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>