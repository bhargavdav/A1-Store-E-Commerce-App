<?php
include('./includes/connect.php');
session_start();

if (!isset($_SESSION['username'])) {
    echo "<script>alert('Please log in to manage your wishlist.'); window.location.href='./users_area/user_login.php';</script>";
    exit();
}

// Get user_id
$username = $_SESSION['username'];
$user_query = "SELECT user_id FROM user_table WHERE username='$username'";
$user_result = mysqli_query($con, $user_query);
$user_data = mysqli_fetch_assoc($user_result);
$user_id = $user_data['user_id'];

// Action: add/remove
$action = $_GET['action'] ?? '';
$product_id = $_GET['product_id'] ?? 0;

if ($action === 'add') {
    $check_query = "SELECT * FROM wishlist WHERE user_id=$user_id AND product_id=$product_id";
    $check_result = mysqli_query($con, $check_query);
    if (mysqli_num_rows($check_result) == 0) {
        $insert_query = "INSERT INTO wishlist (user_id, product_id) VALUES ($user_id, $product_id)";
        mysqli_query($con, $insert_query);
        echo "<script>alert('Product added to wishlist!'); window.history.back();</script>";
    } else {
        echo "<script>alert('Product already in wishlist!'); window.history.back();</script>";
    }
} elseif ($action === 'remove') {
    $delete_query = "DELETE FROM wishlist WHERE user_id=$user_id AND product_id=$product_id";
    mysqli_query($con, $delete_query);
    echo "<script>alert('Product removed from wishlist.'); window.history.back();</script>";
} else {
    echo "<script>alert('Invalid action.'); window.history.back();</script>";
}
?>
