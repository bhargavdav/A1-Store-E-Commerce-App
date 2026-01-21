<?php
include('../includes/connect.php');

// Initialize variables and error messages
$product_category = '';
$product_brand = '';
$product_title = '';
$product_description = '';
$product_keywords = '';
$product_price = '';
$error_messages = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $product_title = isset($_POST['product_title']) ? $_POST['product_title'] : '';
    $product_description = isset($_POST['product_description']) ? $_POST['product_description'] : '';
    $product_keywords = isset($_POST['product_keywords']) ? $_POST['product_keywords'] : '';
    $product_category = isset($_POST['product_category']) ? $_POST['product_category'] : '';
    $product_brand = isset($_POST['product_brand']) ? $_POST['product_brand'] : '';
    $product_price = isset($_POST['product_price']) ? $_POST['product_price'] : '';

    // Form validation (PHP side)
    if ($product_title == '') {
        $error_messages['product_title'] = "Product title is required.";
    }

    if ($product_description == '') {
        $error_messages['product_description'] = "Product description is required.";
    }

    if ($product_keywords == '') {
        $error_messages['product_keywords'] = "Product keywords are required.";
    }

    if ($product_category == '') {
        $error_messages['product_category'] = "Please select a category.";
    }

    if ($product_brand == '') {
        $error_messages['product_brand'] = "Please select a brand.";
    }

    if (empty($product_price) || !is_numeric($product_price) || $product_price <= 0) {
        $error_messages['product_price'] = "Product price is required and must be a positive number.";
    }

    // If no validation errors, proceed with form submission
    if (empty($error_messages)) {
        // Access image files
        $product_image_one = $_FILES['product_image_one']['name'];
        $product_image_two = $_FILES['product_image_two']['name'];
        $product_image_three = $_FILES['product_image_three']['name'];

        // Access image tmp names
        $temp_image_one = $_FILES['product_image_one']['tmp_name'];
        $temp_image_two = $_FILES['product_image_two']['tmp_name'];
        $temp_image_three = $_FILES['product_image_three']['tmp_name'];

        // Move uploaded images to respective folder
        move_uploaded_file($temp_image_one, "./product_images/$product_image_one");
        move_uploaded_file($temp_image_two, "./product_images/$product_image_two");
        move_uploaded_file($temp_image_three, "./product_images/$product_image_three");

        // Insert into the database
        $insert_query = "INSERT INTO products (product_title, product_description, product_keywords, category_id, brand_id, product_image_one, product_image_two, product_image_three, product_price, date, status) 
                         VALUES ('$product_title', '$product_description', '$product_keywords', '$product_category', '$product_brand', '$product_image_one', '$product_image_two', '$product_image_three', '$product_price', NOW(), 'true')";

        $insert_result = mysqli_query($con, $insert_query);
        if ($insert_result) {
            echo "<script>console.log('Product Inserted Successfully');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Products - Admin Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
    <!-- Include jQuery and jQuery Validation Plugin -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
</head>

<body>
    <div class="container py-4 px-2">
        <div class="categ-header text-center mb-4">
            <h2>Insert Products</h2>
        </div>
        <form id="product_form" action="" method="post" enctype="multipart/form-data">
            <div class="row justify-content-center">
                <div class="col-md-5">
                    <!-- title -->
                    <div class="form-outline mb-4">
                        <label for="product_title" class="form-label">Product Title</label>
                        <input type="text" placeholder="Enter Product Title" name="product_title" id="product_title" class="form-control" autocomplete="off">
                    </div>
                    <!-- description -->
                    <div class="form-outline mb-4">
                        <label for="product_description" class="form-label">Product Description</label>
                        <input type="text" placeholder="Enter Product Description" name="product_description" id="product_description" class="form-control" autocomplete="off">
                    </div>
                    <!-- keywords -->
                    <div class="form-outline mb-4">
                        <label for="product_keywords" class="form-label">Product Keywords</label>
                        <input type="text" placeholder="Enter Product Keywords" name="product_keywords" id="product_keywords" class="form-control" autocomplete="off">
                    </div>
                    <!-- categories -->
                    <div class="form-outline mb-4">
                        <select class="form-select" name="product_category" id="product_category">
                            <option selected disabled>Select a Category</option>
                            <?php
                            $select_query = 'SELECT * FROM categories';
                            $select_result = mysqli_query($con, $select_query);
                            while ($row = mysqli_fetch_assoc($select_result)) {
                                $category_title = $row['category_title'];
                                $category_id = $row['category_id'];
                                echo "<option value='$category_id'>$category_title</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- brands -->
                    <div class="form-outline mb-4">
                        <select class="form-select" name="product_brand" id="product_brand">
                            <option selected disabled>Select a Brand</option>
                            <?php
                            $select_query = 'SELECT * FROM brands';
                            $select_result = mysqli_query($con, $select_query);
                            while ($row = mysqli_fetch_assoc($select_result)) {
                                $brand_title = $row['brand_title'];
                                $brand_id = $row['brand_id'];
                                echo "<option value='$brand_id'>$brand_title</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <!-- Image one -->
                    <div class="form-outline mb-4">
                        <label for="product_image_one" class="form-label">Product Image One</label>
                        <input type="file" name="product_image_one" id="product_image_one" class="form-control">
                    </div>
                    <!-- Image two -->
                    <div class="form-outline mb-4">
                        <label for="product_image_two" class="form-label">Product Image Two</label>
                        <input type="file" name="product_image_two" id="product_image_two" class="form-control">
                    </div>
                    <!-- Image three -->
                    <div class="form-outline mb-4">
                        <label for="product_image_three" class="form-label">Product Image Three</label>
                        <input type="file" name="product_image_three" id="product_image_three" class="form-control">
                    </div>
                    <!-- Price -->
                    <div class="form-outline mb-4">
                        <label for="product_price" class="form-label">Product Price</label>
                        <input type="number" placeholder="Enter Product Price" name="product_price" id="product_price" class="form-control" autocomplete="off">
                    </div>
                    <!-- Submit Button -->
                    <div class="form-outline mb-4">
                        <input type="submit" value="Insert Product" name="insert_product" class="btn btn-primary">
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- jQuery Validation Script -->
    <script>
        $(document).ready(function() {
            $("#product_form").validate({
                rules: {
                    product_title: {
                        required: true
                    },
                    product_description: {
                        required: true
                    },
                    product_keywords: {
                        required: true
                    },
                    product_category: {
                        required: true
                    },
                    product_brand: {
                        required: true
                    },
                    product_image_one: {
                        required: true,
                        extension: "jpg|jpeg|png|gif"
                    },
                    product_image_two: {
                        required: true,
                        extension: "jpg|jpeg|png|gif"
                    },
                    product_image_three: {
                        required: true,
                        extension: "jpg|jpeg|png|gif"
                    },
                    product_price: {
                        required: true,
                        number: true,
                        min: 1
                    }
                },
                messages: {
                    product_title: {
                        required: "Please enter the product title."
                    },
                    product_description: {
                        required: "Please enter the product description."
                    },
                    product_keywords: {
                        required: "Please enter product keywords."
                    },
                    product_category: {
                        required: "Please select a product category."
                    },
                    product_brand: {
                        required: "Please select a product brand."
                    },
                    product_image_one: {
                        required: "Please upload the first product image.",
                        extension: "Allowed file types are: jpg, jpeg, png, gif."
                    },
                    product_image_two: {
                        required: "Please upload the second product image.",
                        extension: "Allowed file types are: jpg, jpeg, png, gif."
                    },
                    product_image_three: {
                        required: "Please upload the third product image.",
                        extension: "Allowed file types are: jpg, jpeg, png, gif."
                    },
                    product_price: {
                        required: "Please enter the product price.",
                        number: "Please enter a valid number.",
                        min: "Price must be greater than zero."
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