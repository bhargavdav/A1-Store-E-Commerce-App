<?php
include('includes/connect.php');
include('functions/common_functions.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us Page</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css" />
    <link rel="stylesheet" href="assets/css/main.css" />
</head>

<body>

    <div class="register">
        <div class="container py-3">
            <h2 class="text-center mb-4">Contact Us</h2>
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <form id="contact_form" action="" method="post" class="d-flex flex-column gap-4">
                        <div class="form-outline">
                            <label for="user_username" class="form-label">Username</label>
                            <input type="text" placeholder="Enter your username" autocomplete="off" required="required" name="user_username" id="user_username" class="form-control">
                        </div>
                        <div class="form-outline">
                            <label for="user_password" class="form-label">Email</label>
                            <input type="email" placeholder="Enter your Email" autocomplete="off" required="required" name="user_password" id="user_password" class="form-control">
                        </div>
                        <div class="form-outline">
                            <label for="user_message" class="form-label">Message</label>
                            <textarea autocomplete="off" required="required" name="user_message" id="user_message" class="form-control" placeholder="Enter your Message"></textarea>
                        </div>
                        <div>
                            <input type="submit" value="Submit" class="btn btn-primary mb-2" name="contact_submit">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./assets//js/bootstrap.bundle.js"></script>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script>
    $(document).ready(function() {
        $("#contact_form").validate({
            rules: {
                user_username: {
                    required: true,
                    minlength: 3
                },
                user_password: {
                    required: true,
                    email: true
                },
                user_message: {
                    required: true,
                    minlength: 10
                }
            },
            messages: {
                user_username: {
                    required: "Please enter your username.",
                    minlength: "Username must be at least 3 characters long."
                },
                user_password: {
                    required: "Please enter your email address.",
                    email: "Please enter a valid email address."
                },
                user_message: {
                    required: "Please enter your message.",
                    minlength: "Your message must be at least 10 characters long."
                }
            },
            submitHandler: function(form) {
                form.submit();
            },
            errorPlacement: function(error, element) {
                error.insertAfter(element);
                error.addClass("text-danger mt-1");
            }
        });
    });
</script>

</html>