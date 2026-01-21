<?php
// @include("../includes/connect.php");
// @include("../functions/common_functions.php");
// session_start();

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = $_POST['username'];
//     $email = $_POST['email'];
//     $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
//     $user_address = $_POST['user_address'];
//     $user_mobile = $_POST['user_mobile'];
//     $user_ip = $_SERVER['REMOTE_ADDR']; // Get user IP

//     // Upload user image
//     $user_image = $_FILES['user_image']['name'];
//     $image_tmp = $_FILES['user_image']['tmp_name'];
//     $image_folder = "../uploads/" . $user_image;

//     if (move_uploaded_file($image_tmp, $image_folder)) {
//         // Insert into database
//         $query = "INSERT INTO user_table (username, user_email, user_password, user_image, user_ip, user_address, user_mobile) 
//                   VALUES ('$username', '$email', '$password', '$user_image', '$user_ip', '$user_address', '$user_mobile')";

//         if (mysqli_query($conn, $query)) {
//             echo "<script>alert('User added successfully!'); window.location.href='admin_add_user.php';</script>";
//         } else {
//             echo "<script>alert('Error adding user: " . mysqli_error($conn) . "');</script>";
//         }
//     } else {
//         echo "<script>alert('Image upload failed!');</script>";
//     }
// }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />

    <!-- jQuery and jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <style>
        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <!-- upper-nav -->
    <?php //include('../includes/admin_header.php'); 
    ?>
    <!-- End NavBar -->

    <!-- Start Add User -->
    <div class="all-prod">
        <div class="container">
            <div class="sub-container pt-4 pb-4">
                <div class="categ-header">
                    <div class="sub-title">
                        <span class="shape"></span>
                        <span class="title">Admin Panel</span>
                    </div>
                    <h2>Add New User</h2>
                </div>
                <div class="row mx-0 justify-content-center">
                    <div class="col-md-6">
                        <form id="userForm" action="" method="post" enctype="multipart/form-data">
                            <!-- Username -->
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                            </div>

                            <!-- User Image -->
                            <div class="mb-3">
                                <label for="user_image" class="form-label">User Image</label>
                                <input type="file" class="form-control" id="user_image" name="user_image" required>
                            </div>

                            <!-- Address -->
                            <div class="mb-3">
                                <label for="user_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="user_address" name="user_address" required>
                            </div>

                            <!-- Mobile -->
                            <div class="mb-3">
                                <label for="user_mobile" class="form-label">Mobile</label>
                                <input type="text" class="form-control" id="user_mobile" name="user_mobile" required>
                            </div>

                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add User -->

    <script>
        $(document).ready(function() {
            $("#userForm").validate({
                rules: {
                    username: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#password"
                    },
                    user_image: {
                        required: true,
                        extension: "jpg|jpeg|png|gif"
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
                    username: {
                        required: "Please enter a username",
                        minlength: "Username must be at least 3 characters"
                    },
                    email: {
                        required: "Please enter an email",
                        email: "Enter a valid email address"
                    },
                    password: {
                        required: "Please enter a password",
                        minlength: "Password must be at least 6 characters"
                    },
                    confirm_password: {
                        required: "Please confirm your password",
                        equalTo: "Passwords do not match"
                    },
                    user_image: {
                        required: "Please upload an image",
                        extension: "Only jpg, jpeg, png, and gif formats are allowed"
                    },
                    user_address: {
                        required: "Please enter your address"
                    },
                    user_mobile: {
                        required: "Please enter your mobile number",
                        digits: "Only numbers allowed",
                        minlength: "Mobile number must be at least 10 digits",
                        maxlength: "Mobile number can't be more than 15 digits"
                    }
                }
            });
        });
    </script>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>