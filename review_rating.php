<?php
include('./includes/connect.php');
include('./functions/common_functions.php');
@session_start();

// Redirect if not logged in
if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login to submit a review.'); window.location.href='./users_area/user_login.php';</script>";
    exit();
}

$user_username = $_SESSION['username'];

// Fetch user ID
$user_query = "SELECT user_id FROM `user_table` WHERE username='$user_username'";
$user_result = mysqli_query($con, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['user_id'];

$product_id = isset($_GET['product_id']) ? $_GET['product_id'] : 0;

// Check if the user has purchased the product
$order_query = "
    SELECT * FROM `user_orders` u
    JOIN `order_items` oi ON u.order_id = oi.order_id
    WHERE u.user_id='$user_id' AND oi.product_id='$product_id'";
$order_result = mysqli_query($con, $order_query);
$order_count = mysqli_num_rows($order_result);

if ($order_count == 0) {
    echo "<script>alert('You can only review products you have purchased.'); window.location.href='index.php';</script>";
    exit();
}

// Insert review into database
if (isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $review_text = mysqli_real_escape_string($con, $_POST['review_text']);

    $insert_query = "INSERT INTO `reviews` (user_id, product_id, rating, review_text) VALUES ('$user_id', '$product_id', '$rating', '$review_text')";
    if (mysqli_query($con, $insert_query)) {
        echo "<script>alert('Review submitted successfully!'); window.location.href='product.php?product_id=$product_id';</script>";
    } else {
        echo "<script>alert('Failed to submit review.');</script>";
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Product</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: flex-start;
            min-height: 100vh;
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .container {
            max-width: 500px;
            background: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ced4da;
            font-size: 16px;
        }

        .star-rating {
            display: flex;
            flex-direction: row-reverse;
            justify-content: center;
            font-size: 2rem;
            gap: 5px;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            cursor: pointer;
            color: #ccc;
            transition: color 0.3s ease, transform 0.2s ease;
        }

        .star-rating input:checked~label,
        .star-rating label:hover,
        .star-rating label:hover~label {
            color: gold;
            transform: scale(1.1);
        }

        .btn-primary {
            background: #dc3545;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            width: 100%;
            text-align: center;
            color: white;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #bb2d3b;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <h2 class="text-center mb-4">Submit Your Review</h2>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <form action="" method="post" id="review-form">
                    <!-- Star Rating -->
                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <div class="star-rating">
                            <input type="radio" name="rating" value="5" id="star5"><label for="star5">&#9733;</label>
                            <input type="radio" name="rating" value="4" id="star4"><label for="star4">&#9733;</label>
                            <input type="radio" name="rating" value="3" id="star3"><label for="star3">&#9733;</label>
                            <input type="radio" name="rating" value="2" id="star2"><label for="star2">&#9733;</label>
                            <input type="radio" name="rating" value="1" id="star1"><label for="star1">&#9733;</label>
                        </div>
                    </div>

                    <!-- Review Text -->
                    <div class="mb-3">
                        <label for="review_text" class="form-label">Your Review</label>
                        <textarea name="review_text" id="review_text" class="form-control" rows="4" required></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <input type="submit" name="submit_review" value="Submit Review" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#review-form").validate({
                rules: {
                    rating: {
                        required: true
                    },
                    review_text: {
                        required: true,
                        minlength: 10
                    }
                },
                messages: {
                    rating: {
                        required: "Please select a star rating"
                    },
                    review_text: {
                        required: "Please enter your review",
                        minlength: "Review must be at least 10 characters long"
                    }
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") == "rating") {
                        error.insertAfter(".star-rating");
                    } else {
                        error.insertAfter(element);
                    }
                    error.addClass("text-danger mt-1");
                }
            });
        });
    </script>
</body>

</html>