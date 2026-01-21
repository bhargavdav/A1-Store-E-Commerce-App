<?php
include('../includes/connect.php');
include_once('../functions/common_functions.php');


$message = ""; // To store success/error message

// Fetch brand data
if (isset($_GET['edit_brand'])) {
    $edit_id = $_GET['edit_brand'];
    $get_data_query = "SELECT * FROM `brands` WHERE brand_id = $edit_id";
    $get_data_result = mysqli_query($con, $get_data_query);
    $row_fetch_data = mysqli_fetch_array($get_data_result);

    $brand_id = $row_fetch_data['brand_id'];
    $brand_title = $row_fetch_data['brand_title'];
}

// Update brand
if (isset($_POST['update_brand'])) {
    $brand_title = trim($_POST['brand_title']);

    if (!empty($brand_title)) {
        $update_brand_query = "UPDATE `brands` SET brand_title='$brand_title' WHERE brand_id = $edit_id";
        $update_brand_result = mysqli_query($con, $update_brand_query);

        if ($update_brand_result) {
            $message = "<div class='alert alert-success'>Brand updated successfully. Redirecting...</div>";
            echo "<script>setTimeout(() => { window.location.href = './index.php?view_brands'; }, 2000);</script>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to update brand. Try again.</div>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please enter a brand title.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Brand</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <style>
        .container {
            margin-top: 50px;
        }

        .form-outline {
            margin-bottom: 15px;
        }

        .btn-primary {
            width: 100%;
            background-color: #DB4444;
        }

        .error {
            color: red;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Edit Brand</h1>

                <!-- Success/Error Message -->
                <?php echo $message; ?>

                <form id="brand_form" action="" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3 mb-3">

                    <!-- Brand Title -->
                    <div class="form-outline">
                        <label for="brand_title" class="form-label">Brand Title</label>
                        <input type="text" name="brand_title" id="brand_title" class="form-control" value="<?php echo $brand_title; ?>">
                        <span class="text-danger error-msg"></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-outline text-center">
                        <input type="submit" value="Update Brand" class="btn btn-primary" name="update_brand">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery Validation Script -->
    <script>
        $(document).ready(function() {
            $("#brand_form").validate({
                rules: {
                    brand_title: {
                        required: true,
                        minlength: 3
                    }
                },
                messages: {
                    brand_title: {
                        required: "Please enter the brand title.",
                        minlength: "Brand title must be at least 3 characters long."
                    }
                },
                errorPlacement: function(error, element) {
                    element.next(".error-msg").html(error);
                }
            });
        });
    </script>
</body>

</html>