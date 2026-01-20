<?php
include('./includes/connect.php');
// include('./includes/header.php');
session_start();

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please login to view your wishlist.'); window.location.href='./users_area/user_login.php';</script>";
    exit();
}

$username = $_SESSION['username'];
$user_query = "SELECT user_id FROM user_table WHERE username='$username'";
$user_result = mysqli_query($con, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['user_id'];

// Fetch wishlist items
$wishlist_query = "SELECT p.* FROM wishlist w JOIN products p ON w.product_id = p.product_id WHERE w.user_id = $user_id";
$wishlist_result = mysqli_query($con, $wishlist_query);

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Please log in to manage your wishlist.'); window.location.href='./users_area/user_login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Get the product_id to remove
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Remove the product from the wishlist
    $delete_query = "DELETE FROM wishlist WHERE user_id = $user_id AND product_id = $product_id";
    mysqli_query($con, $delete_query);

    echo "<script>alert('Product removed from wishlist.'); window.location.href='my_wishlist.php';</script>";
}
?>

<head>
    <title>My Wishlist</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom Styling -->
    <style>
        .card {
            border-radius: 10px;
        }

        .card-title {
            font-size: 1rem;
            font-weight: 600;
        }

        .card-body {
            padding: 0.8rem;
        }

        .card img {
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
    </style>
    <link rel="stylesheet" href="./assets/css/bootstrap.css" />
    <link rel="stylesheet" href="./assets/css/main.css" />


</head>


<h2 class="text-center my-4">My Wishlist</h2>
<div class="row">
    <?php
    while ($row = mysqli_fetch_assoc($wishlist_result)) {
        $product_id = $row['product_id'];
        $product_title = $row['product_title'];
        $product_price = $row['product_price'];
        $product_image = $row['product_image_one'];

        echo '
        <div class="col-md-3 col-sm-6 mb-4">
            <div class="card h-100 shadow-sm">
                <img src="./admin/product_images/' . $product_image . '" 
                     class="card-img-top img-fluid" 
                     style="height: 180px; object-fit: cover;" 
                     alt="' . htmlspecialchars($product_title) . '">
                <div class="card-body p-3">
                    <h6 class="card-title mb-2 text-truncate">' . htmlspecialchars($product_title) . '</h6>
                    <p class="card-text mb-2 text-muted" style="font-size: 0.9rem;">$' . $product_price . '</p>
                    <a href="product_details.php?product_id=' . $product_id . '" class="btn btn-sm btn-primary me-2">View</a>
                    <a href="wishlist.php?action=remove&product_id=' . $product_id . '" class="btn btn-sm btn-danger">Remove</a>
                </div>
            </div>
        </div>';
    }
    ?>
</div>