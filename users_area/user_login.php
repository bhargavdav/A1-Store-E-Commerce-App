<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
@session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce User Login Page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <!-- Include jQuery and jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <script>
        $(document).ready(function() {
            $("form").validate({
                rules: {
                    user_username: {
                        required: true,
                        minlength: 3
                    },
                    user_password: {
                        required: true,
                        minlength: 6
                    }
                },
                messages: {
                    user_username: {
                        required: "Username is required",
                        minlength: "Username must be at least 3 characters"
                    },
                    user_password: {
                        required: "Password is required",
                        minlength: "Password must be at least 6 characters"
                    }
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                    error.addClass("text-danger mt-1");
                }
            });
        });
    </script>

</head>

<body>

    <div class="register">
        <div class="container py-3">
            <h2 class="text-center mb-4">User Login</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="" method="post" class="d-flex flex-column gap-4">
                        <!-- username field  -->
                        <div class="form-outline">
                            <label for="user_username" class="form-label">Username</label>
                            <input type="text" placeholder="Enter your username" autocomplete="off" name="user_username" id="user_username" class="form-control">
                        </div>
                        <!-- password field  -->
                        <div class="form-outline">
                            <label for="user_password" class="form-label">Password</label>
                            <input type="password" placeholder="Enter your password" autocomplete="off" name="user_password" id="user_password" class="form-control">
                        </div>
                        <div><a href="forget_password.php" class="text-decoration-underline">Forget your password?</a></div>
                        <div>
                            <input type="submit" value="Login" class="btn btn-primary mb-2" name="user_login">
                            <p>
                                Don't have an account? <a href="user_registration.php" class="text-primary text-decoration-underline"><strong>Register</strong></a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets//js/bootstrap.bundle.js"></script>
</body>

</html>
<?php
if (isset($_POST['user_login'])) {
    $user_username = $_POST['user_username'];
    $user_password = $_POST['user_password'];
    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username'";
    $select_result = mysqli_query($con, $select_query);
    $row_data = mysqli_fetch_assoc($select_result);
    $row_count = mysqli_num_rows($select_result);
    $user_ip = getIPAddress();

    // Check if the user exists
    if ($row_count > 0) {
        // Verify the password
        if (password_verify($user_password, $row_data['user_password'])) {
            // Store user information in session
            $_SESSION['username'] = $user_username;
            $_SESSION['user_id'] = $row_data['user_id'];  // Store user_id in session

            // Check if user has items in the cart
            $select_cart_query = "SELECT * FROM `card_details` WHERE ip_address='$user_ip'";
            $select_cart_result = mysqli_query($con, $select_cart_query);
            $row_cart_count = mysqli_num_rows($select_cart_result);

            if ($row_cart_count == 0) {
                // Redirect to profile page if no cart items
                echo "<script>alert('Login Successfully');</script>";
                echo "<script>window.open('profile.php','_self');</script>";
            } else {
                // Redirect to payment page if cart items exist
                echo "<script>alert('Login Successfully');</script>";
                echo "<script>window.open('payment.php','_self');</script>";
            }
        } else {
            echo "<script>alert('Invalid Credentials')</script>";
        }
    } else {
        echo "<script>alert('Invalid Credentials')</script>";
    }
}
?>