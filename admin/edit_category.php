<?php
include_once('../includes/connect.php');

// Fetch category data
if (isset($_GET['edit_category'])) {
    $edit_id = $_GET['edit_category'];
    $get_data_query = "SELECT * FROM `categories` WHERE category_id = $edit_id";
    $get_data_result = mysqli_query($con, $get_data_query);
    $row_fetch_data = mysqli_fetch_array($get_data_result);
    $category_id = $row_fetch_data['category_id'];
    $category_title = $row_fetch_data['category_title'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_category'])) {
    $category_title = trim($_POST['category_title']);

    if (!empty($category_title)) {
        // Update query
        $update_category_query = "UPDATE `categories` SET category_title='$category_title' WHERE category_id = $edit_id";
        $update_category_result = mysqli_query($con, $update_category_query);

        if ($update_category_result) {
            $success_message = "Category updated successfully!";
        } else {
            $error_message = "Failed to update category.";
        }
    } else {
        $error_message = "Please enter a category title.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>

<body>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h1 class="text-center mb-4">Edit Category</h1>

                <?php if (!empty($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php elseif (!empty($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <script>
                        setTimeout(function() {
                            window.location.href = './index.php?view_categories';
                        }, 2);
                    </script>
                <?php endif; ?>

                <form id="edit_category_form" action="" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3 mb-3">
                    <div class="form-outline">
                        <label for="category_title" class="form-label">Category Title</label>
                        <input type="text" name="category_title" id="category_title" class="form-control" value="<?php echo htmlspecialchars($category_title); ?>">
                    </div>
                    <div class="form-outline text-center">
                        <input type="submit" value="Update Category" class="btn btn-primary" name="update_category">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery Validation Script -->
    <script>
        $(document).ready(function() {
            $("#edit_category_form").validate({
                rules: {
                    category_title: {
                        required: true,
                        minlength: 2
                    }
                },
                messages: {
                    category_title: {
                        required: "Please enter a category title.",
                        minlength: "Category title must be at least 2 characters."
                    }
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                    error.addClass("text-danger mt-1");
                }
            });
        });
    </script>

</body>

</html>