<?php
include('../includes/connect.php');
include('../functions/common_functions.php'); // Ensure getIPAddress() is available
session_start();

// Check if payment ID exists
if (!isset($_GET['payment_id'])) {
    echo "Payment ID not found!";
    exit;
}

$user_id = $_SESSION['user_id'] ?? null;
// echo "14" . $user_id . $_SESSION['username'];
if ($user_id && isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $get_user_query = "SELECT user_id FROM user_table WHERE username='$username'";
    $result_user = mysqli_query($con, $get_user_query);
    if ($result_user && mysqli_num_rows($result_user) > 0) {
        $row_user = mysqli_fetch_assoc($result_user);
        $user_id = $row_user['user_id'];
        $_SESSION['user_id'] = $user_id;
    } else {
        echo "User not found!";
        exit;
    }
}

$amount_due = $_SESSION['cart_total'] ?? 0;
$cart_items = $_SESSION['cart_items'] ?? [];
$invoice_number = 'INV' . rand(10000, 999999); // Generate random invoice number
$total_products = count($cart_items);
$order_date = date("Y-m-d H:i:s");
$order_status = 'Complete'; // Payment success

// Debug: Check if invoice number is generated correctly
echo "Generated Invoice Number: $invoice_number"; // Debugging line
echo "<br>";

// Check if all necessary data exists
if (!$user_id || $amount_due == 0 || $total_products == 0) {
    echo "Missing order data. Please contact support.";
    exit;
}

// Insert the order into user_orders table
$insert_order = "INSERT INTO user_orders (user_id, amount_due, invoice_number, total_products, order_date, order_status)
                 VALUES ('$user_id', '$amount_due', '$invoice_number', '$total_products', '$order_date', '$order_status')";

$run_insert_order = mysqli_query($con, $insert_order);

if (!$run_insert_order) {
    die("Order Insertion Failed: " . mysqli_error($con));
}

$order_id = mysqli_insert_id($con);

// Insert each cart item into order_products table
foreach ($cart_items as $item) {
    $product_id = $item['product_id'];
    $quantity = $item['quantity'];
    $insert_product = "INSERT INTO order_products (order_id, product_id, user_id, quantity)
                       VALUES ('$order_id', '$product_id', '$user_id', '$quantity')";
    $run_product = mysqli_query($con, $insert_product);

    if (!$run_product) {
        echo "Failed to insert product $product_id: " . mysqli_error($con);
    }
}

// Clean up cart
$user_ip = getIPAddress();
$delete_cart_query = "DELETE FROM card_details WHERE ip_address='$user_ip'";
$run_delete_cart = mysqli_query($con, $delete_cart_query);

if (!$run_delete_cart) {
    echo "Failed to clean up cart: " . mysqli_error($con);
}

// Clear session data related to cart
unset($_SESSION['cart_items']);
unset($_SESSION['cart_total']);

// Redirect with success message
echo "<script>alert('Payment successful! Your order has been placed.');</script>";
echo "<script>window.location.href='profile.php?my_orders';</script>";
