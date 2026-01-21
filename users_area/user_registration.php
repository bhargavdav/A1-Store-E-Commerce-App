<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce User Registeration Page</title>
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
                    user_email: {
                        required: true,
                        email: true
                    },
                    user_image: {
                        required: true,
                        extension: "jpg|jpeg|png|gif"
                    },
                    user_password: {
                        required: true,
                        minlength: 6
                    },
                    conf_user_password: {
                        required: true,
                        equalTo: "#user_password"
                    },
                    user_address: {
                        required: true
                    },
                    user_mobile: {
                        required: true,
                        digits: true,
                        minlength: 10,
                        maxlength: 15
                    }
                },
                messages: {
                    user_username: {
                        required: "Username is required",
                        minlength: "Username must be at least 3 characters"
                    },
                    user_email: {
                        required: "Email is required",
                        email: "Enter a valid email address"
                    },
                    user_image: {
                        required: "Please upload an image",
                        extension: "Only JPG, JPEG, PNG, or GIF files are allowed"
                    },
                    user_password: {
                        required: "Password is required",
                        minlength: "Password must be at least 6 characters"
                    },
                    conf_user_password: {
                        required: "Confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    user_address: {
                        required: "Address is required"
                    },
                    user_mobile: {
                        required: "Mobile number is required",
                        digits: "Only numbers are allowed",
                        minlength: "Mobile number must be at least 10 digits",
                        maxlength: "Mobile number cannot exceed 15 digits"
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
            <h2 class="text-center mb-4">New User Registration</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form action="" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-4">
                        <!-- username field  -->
                        <div class="form-outline">
                            <label for="user_username" class="form-label">Username</label>
                            <input type="text" placeholder="Enter your username" autocomplete="off" name="user_username" id="user_username" class="form-control">
                        </div>
                        <!-- email field  -->
                        <div class="form-outline">
                            <label for="user_email" class="form-label">Email</label>
                            <input type="email" placeholder="Enter your email" autocomplete="off" name="user_email" id="user_email" class="form-control">
                        </div>
                        <!-- image field  -->
                        <div class="form-outline">
                            <label for="user_image" class="form-label">User Image</label>
                            <input type="file" name="user_image" id="user_image" class="form-control">
                        </div>
                        <!-- password field  -->
                        <div class="form-outline">
                            <label for="user_password" class="form-label">Password</label>
                            <input type="password" placeholder="Enter your password" autocomplete="off" name="user_password" id="user_password" class="form-control">
                        </div>
                        <!-- confirm password field  -->
                        <div class="form-outline">
                            <label for="conf_user_password" class="form-label">Confirm Password</label>
                            <input type="password" placeholder="Confirm your password" autocomplete="off" name="conf_user_password" id="conf_user_password" class="form-control">
                        </div>
                        <!-- address field  -->
                        <div class="form-outline">
                            <label for="user_address" class="form-label">Address</label>
                            <input type="text" placeholder="Enter your address" autocomplete="off" name="user_address" id="user_address" class="form-control">
                        </div>
                        <!-- mobile field  -->
                        <div class="form-outline">
                            <label for="user_mobile" class="form-label">Mobile</label>
                            <input type="text" placeholder="Enter your mobile" autocomplete="off" name="user_mobile" id="user_mobile" class="form-control">
                        </div>
                        <div>
                            <input type="submit" value="Register" class="btn btn-primary mb-2" name="user_register">
                            <p>
                                Already have an account? <a href="user_login.php" class="text-primary text-decoration-underline"><strong>Login</strong></a>
                            </p>
                        </div>
                    </form>

                    <!-- Add span elements to display error messages -->
                </div>
            </div>
        </div>
    </div>
    <script src="./assets//js/bootstrap.bundle.js"></script>
</body>

</html>
<!-- php code  -->
<?php
if (isset($_POST['user_register'])) {
    $user_username = $_POST['user_username'];
    $user_email = $_POST['user_email'];
    $user_password = $_POST['user_password'];
    $hash_password = password_hash($user_password, PASSWORD_DEFAULT);
    $conf_user_password = $_POST['conf_user_password'];
    $user_address = $_POST['user_address'];
    $user_mobile = $_POST['user_mobile'];
    $user_image = $_FILES['user_image']['name'];
    $user_image_tmp = $_FILES['user_image']['tmp_name'];
    $user_ip = getIPAddress();
    // check if user exist or not
    $select_query = "SELECT * FROM `user_table` WHERE username='$user_username' OR user_email='$user_email'";
    $select_result = mysqli_query($con, $select_query);
    $rows_count = mysqli_num_rows($select_result);
    if ($rows_count > 0) {
        echo "<script>window.alert('Username | Email already exist');</script>";
    } else if ($user_password != $conf_user_password) {
        echo "<script>window.alert('Passwords are not match');</script>";
    } else {
        // insert query
        move_uploaded_file($user_image_tmp, "./user_images/$user_image");
        $insert_query = "INSERT INTO `user_table` (username,user_email,user_password,user_image,user_ip,user_address,user_mobile) VALUES ('$user_username','$user_email','$hash_password','$user_image','$user_ip','$user_address','$user_mobile')";
        $insert_result = mysqli_query($con, $insert_query);
        if ($insert_result) {
            echo "<script>window.alert('User added successfully');</script>";
        } else {
            die(mysqli_error($con));
        }
    }
    // //select cart items check if items in cart go to checkout !| go to index.php
    // $select_cart_items = "SELECT * FROM `card_details` WHERE ip_address='$user_ip'";
    // $select_cart_items_result = mysqli_query($con,$select_cart_items);
    // $rows_count_cart_items = mysqli_num_rows($select_cart_items_result);
    // if($rows_count_cart_items > 0 ){
    //     $_SESSION['username'] = $user_username;
    //     echo "<script>window.alert('You have items in your cart');</script>";
    //     echo "<script>window.open('checkout.php','_self');</script>";
    // }else{
    //     echo "<script>window.open('../index.php','_self');</script>";
    // }
}
?>