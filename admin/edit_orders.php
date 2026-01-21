<?php
include('../includes/connect.php');
include('../functions/common_functions.php');

$message = ""; // To store success/error message

// Fetch order data
if (isset($_GET['edit_order'])) {
    $edit_id = $_GET['edit_order'];
    $get_data_query = "SELECT * FROM `user_orders` WHERE order_id = $edit_id";
    $get_data_result = mysqli_query($con, $get_data_query);
    $row_fetch_data = mysqli_fetch_array($get_data_result);

    $order_id = $row_fetch_data['order_id'];
    $amount_due = $row_fetch_data['amount_due'];
    $invoice_number = $row_fetch_data['invoice_number'];
    $total_products = $row_fetch_data['total_products'];
    $order_status = $row_fetch_data['order_status'];
}

// Update order
if (isset($_POST['update_order'])) {
    $amount_due = $_POST['amount_due'];
    $invoice_number = $_POST['invoice_number'];
    $total_products = $_POST['total_products'];
    $order_status = $_POST['order_status'];

    if (!empty($amount_due) && !empty($invoice_number) && !empty($total_products) && !empty($order_status)) {
        $update_order_query = "UPDATE `user_orders` SET 
            amount_due='$amount_due', 
            invoice_number='$invoice_number', 
            total_products='$total_products', 
            order_status='$order_status' 
            WHERE order_id = $edit_id";

        $update_order_result = mysqli_query($con, $update_order_query);

        if ($update_order_result) {
            // $message = "<div class='alert alert-success'>Order updated successfully.</div>";
            echo "<script>window.alert('Order updated successfully');</script>";
            echo "<script>window.open('./index.php?list_orders','_self');</script>";
        } else {
            // $message = "<div class='alert alert-danger'>Failed to update order. Try again.</div>";
            echo "<script>window.alert('Failed to update order. Try again.');</script>";
            echo "<script>window.open('./index.php?list_orders','_self');</script>";
        }
    } else {
        $message = "<div class='alert alert-warning'>Please fill in all fields.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Order</title>
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
                <h1 class="text-center mb-4">Edit Order</h1>

                <!-- Success/Error Message -->
                <?php echo $message; ?>

                <form id="order_form" action="" method="post" enctype="multipart/form-data" class="d-flex flex-column gap-3 mb-3">

                    <!-- Amount Due -->
                    <div class="form-outline">
                        <label for="amount_due" class="form-label">Due Amount</label>
                        <input type="text" name="amount_due" id="amount_due" class="form-control" value="<?php echo $amount_due; ?>">
                        <span class="text-danger error-msg"></span>
                    </div>

                    <!-- Invoice Number -->
                    <div class="form-outline">
                        <label for="invoice_number" class="form-label">Invoice Number</label>
                        <input type="text" name="invoice_number" id="invoice_number" class="form-control" value="<?php echo $invoice_number; ?>">
                        <span class="text-danger error-msg"></span>
                    </div>

                    <!-- Total Products -->
                    <div class="form-outline">
                        <label for="total_products" class="form-label">Total Products</label>
                        <input type="number" name="total_products" id="total_products" class="form-control" value="<?php echo $total_products; ?>">
                        <span class="text-danger error-msg"></span>
                    </div>

                    <!-- Order Status -->
                    <div class="form-outline">
                        <label for="order_status" class="form-label">Order Status</label>
                        <select name="order_status" id="order_status" class="form-control">
                            <option value="pending" <?php if ($order_status == 'pending') echo 'selected'; ?>>Pending</option>
                            <option value="paid" <?php if ($order_status == 'paid') echo 'selected'; ?>>Paid</option>
                        </select>
                        <span class="text-danger error-msg"></span>
                    </div>

                    <!-- Submit Button -->
                    <div class="form-outline text-center">
                        <input type="submit" value="Update Order" class="btn btn-primary" name="update_order">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- jQuery Validation Script -->
    <script>
        $(document).ready(function() {
            $("#order_form").validate({
                rules: {
                    amount_due: {
                        required: true,
                        number: true,
                        min: 0
                    },
                    invoice_number: {
                        required: true,
                        minlength: 3
                    },
                    total_products: {
                        required: true,
                        number: true,
                        min: 1
                    },
                    order_status: {
                        required: true
                    }
                },
                messages: {
                    amount_due: {
                        required: "Please enter the due amount.",
                        number: "Please enter a valid number.",
                        min: "Amount must be a positive value."
                    },
                    invoice_number: {
                        required: "Please enter the invoice number.",
                        minlength: "Invoice number must be at least 3 characters long."
                    },
                    total_products: {
                        required: "Please enter the total products.",
                        number: "Only numbers are allowed.",
                        min: "Total products must be at least 1."
                    },
                    order_status: {
                        required: "Please select the order status."
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