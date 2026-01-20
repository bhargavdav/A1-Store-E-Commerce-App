<?php
include('./includes/connect.php');
include('./functions/common_functions.php');
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ecommerce Cart Details Page</title>
    <link rel="stylesheet" href="./assets/css/bootstrap.css" />
    <link rel="stylesheet" href="./assets/css/main.css" />
</head>

<body>
    <!-- upper-nav -->
    <?php
    include('./includes/header.php');
    ?>
    <!-- End NavBar -->

    <!-- Start Table Section -->
    <div class="landing">
        <div class="container">
            <div class="row py-5 m-0">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                    <table class="table table-bordered table-hover table-striped table-group-divider text-center">

                        <!-- display data in cart  -->
                        <?php
                        $getIpAddress = getIPAddress();
                        
                        $total_price = 0;
                        $cart_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress'";
                        $cart_result = mysqli_query($con, $cart_query);
                        $result_count = mysqli_num_rows($cart_result);
                        if ($result_count > 0) {
                            echo "
                                <thead>
                                    <tr class='d-flex flex-column d-md-table-row '>
                                        <th>Product Title</th>
                                        <th>Product Image</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Remove</th>
                                        <th colspan='2'>Operations</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";
                            while ($row = mysqli_fetch_array($cart_result)) {
                                $product_id = $row['product_id'];
                                $product_quantity = $row['quantity'];
                                $select_product_query = "SELECT * FROM `products` WHERE product_id=$product_id";
                                $select_product_result = mysqli_query($con, $select_product_query);
                                while ($row_product_price = mysqli_fetch_array($select_product_result)) {
                                    $product_price = array($row_product_price['product_price']);
                                    $price_table = $row_product_price['product_price'];
                                    $product_id = $row_product_price['product_id'];
                                    $product_title = $row_product_price['product_title'];
                                    $product_image_one = $row_product_price['product_image_one'];
                                    $product_values = array_sum($product_price);
                                    $total_price += $product_values * $product_quantity;
                                    $_SESSION['cart_total'] = $total_price;

                        ?>
                                    <!-- display data in cart  -->
                                    <tr class="d-flex flex-column d-md-table-row ">
                                        <td>
                                            <?php echo $product_title; ?>
                                        </td>
                                        <td><img src="./admin/product_images/<?php echo $product_image_one; ?>" class="img-thumbnail" alt="<?php echo $product_title; ?>"></td>
                                        <td>
                                            <input type="number" class="form-control w-50 mx-auto" min="1" value="1" name="qty_<?php echo $product_id; ?>">
                                        </td>
                                        <?php
                                        // $total_price += $product_values * $product_quantity;
                                        // echo "<h1>total_priceafter: $total_price</h1><br/>";
                                        $getIpAddress = getIPAddress();
                                        if (isset($_POST['update_cart'])) {
                                            $itemsOfProduct = 'qty_' . $product_id;
                                            $quantities = $_POST[$itemsOfProduct];
                                            if (!empty($quantities)) {
                                                $update_cart_query = "UPDATE `card_details` SET quantity = $quantities WHERE ip_address='$getIpAddress' AND product_id=$product_id;";
                                                // $update_cart_query = "UPDATE `card_details` SET quantity = $quantities WHERE ip_address='$getIpAddress' AND product_id=$product_id;";
                                                $update_cart_result = mysqli_query($con, $update_cart_query);
                                            }
                                            // echo "<script>location.reload(1);</script>";
                                            // header('Location: '.$_SERVER['REQUEST_URI']);
                                            // header("refresh: 2");
                                            // echo "<h1>total_price after: $total_price</h1><br/>";
                                            echo "<script>window.open('cart.php','_self');</script>";
                                        }
                                        ?>
                                        <td>
                                            <?php echo $price_table; ?>
                                        </td>
                                        <td><input type="checkbox" name="removeitem[]" value="<?php echo $product_id ?>"></td>
                                        <td>

                                            <input type="submit" value="Update" class="btn btn-dark" name="update_cart">
                                        </td>
                                        <td>
                                            <input type="submit" value="Remove" class="btn btn-primary" name="remove_cart">
                                        </td>
                                    </tr>
                        <?php   }
                            }
                        } else {
                            echo "<h2 class='text-center text-danger'>Cart is empty</h2>";
                        }
                        ?>
                        </tbody>
                    </table>
                    <!-- SubTotal -->
                    <div class="d-flex align-items-center gap-4 flex-wrap">
                        <?php
                        $getIpAddress = getIPAddress();
                        $cart_query = "SELECT * FROM `card_details` WHERE ip_address='$getIpAddress'";
                        $cart_result = mysqli_query($con, $cart_query);
                        $result_count = mysqli_num_rows($cart_result);
                        if ($result_count > 0) {
                            echo "
                        <h4>Sub-Total: <strong class='text-2'> $total_price</strong></h4>
                        
                        <button class='btn btn-dark'><a class='text-light' href='./index.php'>Continue Shopping</a></button>
                        <button class='btn btn-dark'><a class='text-light' href='./users_area/checkout.php'>Checkout</a></button>
                        ";
                        } else {
                            echo "<input type='button' value='Continue Shopping' class='btn btn-dark' name='continue_shopping'>";
                        }
                        if (isset($_POST['continue_shopping'])) {
                            echo "<script>window.open('index.php','_self');</script>";
                            unset($_POST['continue_shopping']);
                        } else if (isset($_POST['checkout'])) {
                            unset($_POST['checkout']);
                            echo "<script>window.open('./users_area/checkout.php','_self');</script>";
                        }
                        ?>
                    </div>
                    <!-- SubTotal -->
                </form>
                <!-- function to remove items  -->
                <?php
                function remove_cart_item()
                {
                    global $con;
                    if (isset($_POST['remove_cart'])) {
                        foreach ($_POST['removeitem'] as $remove_id) {
                            $delete_query = "DELETE FROM `card_details` WHERE product_id=$remove_id";
                            $delete_run_result = mysqli_query($con, $delete_query);
                            if ($delete_run_result) {
                                echo "<script>window.open('cart.php','_self');</script>";
                            }
                        }
                    }
                }
                echo $remove_item = remove_cart_item();
                ?>
                <!-- function to remove items  -->
            </div>
        </div>
        <!-- put it here -->
    </div>

    <!-- End Table Section -->













    <!-- divider  -->
    <!-- <div class="container">
        <div class="divider"></div>
    </div> -->
    <!-- divider  -->




    <!-- Start Footer -->
    <!-- <div class="upper-nav primary-bg p-2 px-3 text-center text-break">
        <span>All CopyRight &copy;2023</span>
    </div> -->
    <!-- End Footer -->

    <script src="./assets//js/bootstrap.bundle.js"></script>
</body>

</html>