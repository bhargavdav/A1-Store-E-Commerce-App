<?php
include('./includes/connect.php');
@session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Login first'); window.location.href='users_area/user_login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Get purchased products by the user
$query = "SELECT DISTINCT p.product_id, p.product_title, p.product_image_one
          FROM products p
          JOIN order_products op ON p.product_id = op.product_id
          WHERE op.user_id = '$user_id'";
$result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>My Reviews</title>
    <link rel="stylesheet" href="assets/css/bootstrap.css">
</head>

<body>
    <div class="container mt-5">
        <h3>Your Purchased Products</h3>
        <div class="row">
            <?php while ($row = mysqli_fetch_assoc($result)) {
                $product_id = $row['product_id'];
                $check_review_query = "SELECT * FROM reviews WHERE user_id='$user_id' AND product_id='$product_id'";
                $review_result = mysqli_query($con, $check_review_query);
                $already_reviewed = mysqli_num_rows($review_result) > 0;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src='./admin/product_images/<?php echo $row['product_image_one']; ?>' class="card-img-top img-fluid" style="height: 200px; object-fit: cover;" alt="Product Image" alt='Product Image'>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $row['product_title']; ?></h5>
                            <?php if ($already_reviewed): ?>
                                <button class="btn btn-secondary" disabled>Already Reviewed</button>
                            <?php else: ?>
                                <a href="submit_review.php?product_id=<?php echo $product_id; ?>" class="btn btn-danger">Give Review</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</body>

</html>