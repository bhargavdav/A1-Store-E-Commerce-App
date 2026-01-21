<?php
include('./includes/connect.php');
@session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please login to submit a review.'); window.location.href='users_area/user_login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['product_id'] ?? 0;

// Check if user has purchased this product
$check_purchase_query = "SELECT * FROM order_products WHERE user_id='$user_id' AND product_id='$product_id'";
$purchase_result = mysqli_query($con, $check_purchase_query);

if (mysqli_num_rows($purchase_result) === 0) {
    echo "<script>alert('You can only review products you have purchased.'); window.location.href='index.php';</script>";
    exit();
}

// Prevent duplicate review
$check_review_query = "SELECT * FROM reviews WHERE user_id='$user_id' AND product_id='$product_id'";
$review_result = mysqli_query($con, $check_review_query);
if (mysqli_num_rows($review_result) > 0) {
    echo "<script>alert('You have already reviewed this product.'); window.location.href='product.php?product_id=$product_id';</script>";
    exit();
}

// Submit review
if (isset($_POST['submit_review'])) {
    $rating = $_POST['rating'];
    $review_text = mysqli_real_escape_string($con, $_POST['review_text']);

    $insert_review = "INSERT INTO reviews (user_id, product_id, rating, review_text)
                      VALUES ('$user_id', '$product_id', '$rating', '$review_text')";

    if (mysqli_query($con, $insert_review)) {
        echo "<script>alert('Review submitted successfully!'); window.location.href='product.php?product_id=$product_id';</script>";
    } else {
        echo "<script>alert('Error submitting review.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Submit Review</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>

<body>
    <div class="container mt-5">
        <h2>Submit Your Review</h2>
        <form method="post">
            <div class="form-group mb-3">
                <label>Rating (1 to 5):</label><br>
                <?php for ($i = 5; $i >= 1; $i--): ?>
                    <input type="radio" name="rating" value="<?= $i ?>" required> <?= $i ?> ⭐
                <?php endfor; ?>
            </div>
            <div class="form-group mb-3">
                <label>Your Review:</label>
                <textarea name="review_text" class="form-control" required></textarea>
            </div>
            <input type="submit" name="submit_review" value="Submit Review" class="btn btn-danger">
        </form>
    </div>
</body>

</html>