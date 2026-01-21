<?php
include('../includes/connect.php');
include('../functions/common_functions.php');
include('../includes/mailer.php');
@session_start();

$error_message = "";
$update_stmt = null;

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email'])) {
    $email = trim($_POST['email']);

    // Use prepared statement to prevent SQL injection
    $stmt = $con->prepare("SELECT * FROM `user_table` WHERE user_email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $row_count = $result->num_rows;

    if ($row_count > 0) {
        $token = bin2hex(random_bytes(50));
        // Update the user table with the reset token
        $update_stmt = $con->prepare("UPDATE `user_table` SET reset_token = ? WHERE user_email = ?");
        $update_stmt->bind_param("ss", $token, $email);
        $update_stmt->execute();

        $reset_link = "http://localhost/E-Commerce-1-PHP/users_area/reset_password.php?token=$token";

        // Send email with reset link
        $emailOptions = [
            'from_email' => 'sample@gmail.com',
            'from_name' => 'A1 STORE',
            'use_smtp' => true,
            'smtp_host' => 'your smtp server',        // Gmail SMTP server
            'smtp_port' => 587,                     // TLS port for Gmail
            'smtp_secure' => 'tls',                 // Use TLS
            'smtp_auth' => true,                    // Authentication required
            'smtp_username' => 'sample@gmail.com',  
            'smtp_password' => 'your smtp password',        
            'is_html' => true
        ];

        $subject = "Password Reset Request";
        $message = "Click the link below to reset your password: <br><a href='$reset_link'>$reset_link</a>";
        // $message = "Click the link below to reset your password: <br><a href='$reset_link'>$reset_link</a>";
        if (!$emailOptions['is_html']) {
            $message = "Click the link below to reset your password: \n$reset_link";
        }

        $result = sendEmail($email, $subject, $message, $emailOptions);

        if ($result['success']) {
            echo "<script>alert('A password reset link has been sent to your email.');</script>";
        } else {
            $error_message = "Failed to send email: " . $result['error'];
        }
    } else {
        $error_message = "No account found with this email.";
    }

    $stmt->close();
    if ($update_stmt !== null) {
        $update_stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/jquery.validation/1.19.3/jquery.validate.min.js"></script>
</head>

<body>
    <div class="container py-5">
        <h2 class="text-center">Forgot Password</h2>
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <form action="" method="post" id="forgotPasswordForm">
                    <div class="mb-3">
                        <label for="email" class="form-label">Enter Your Email</label>
                        <input type="email" name="email" id="email" class="form-control" required>
                        <span id="email-error" class="text-danger"></span>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                </form>
                <?php if (!empty($error_message)) : ?>
                    <p class="text-danger mt-2"><?php echo $error_message; ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#forgotPasswordForm").validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    }
                },
                messages: {
                    email: {
                        required: "Please enter your email address.",
                        email: "Please enter a valid email address."
                    }
                },
                errorPlacement: function(error, element) {
                    error.appendTo("#" + element.attr("id") + "-error");
                },
                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</body>

</html>