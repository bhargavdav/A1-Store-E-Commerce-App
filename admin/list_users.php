<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Users Page</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css" />
    <link rel="stylesheet" href="../assets/css/main.css" />
</head>

<body>
    <div class="container">
        <div class="categ-header d-flex justify-content-between align-items-center">
            <div class="sub-title">
                <span class="shape"></span>
                <h2>All Users</h2>
            </div>
            <!-- Add User Button -->
            <a href="add_user.php" class="btn btn-primary">+ Add User</a>
        </div>

        <div class="table-data mt-4">
            <table class="table table-bordered table-hover table-striped text-center">
                <thead class="table-dark">
                    <?php
                    include("../includes/connect.php");
                    $get_user_query = "SELECT * FROM `user_table`";
                    $get_user_result = mysqli_query($con, $get_user_query);
                    $row_count = mysqli_num_rows($get_user_result);

                    if ($row_count != 0) {
                        echo "
                        <tr>
                            <th>User No.</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Image</th>
                            <th>Address</th>
                            <th>Mobile</th>
                            <th>Delete</th>
                        </tr>
                        ";
                    }
                    ?>
                </thead>
                <tbody>
                    <?php
                    if ($row_count == 0) {
                        echo "<h2 class='text-center text-light p-2 bg-dark'>No users yet</h2>";
                    } else {
                        $id_number = 1;
                        while ($row_fetch_users = mysqli_fetch_array($get_user_result)) {
                            $user_id = $row_fetch_users['user_id'];
                            $username = $row_fetch_users['username'];
                            $user_email = $row_fetch_users['user_email'];
                            $user_image = $row_fetch_users['user_image'];
                            $user_address = $row_fetch_users['user_address'];
                            $user_mobile = $row_fetch_users['user_mobile'];

                            echo "
                            <tr>
                                <td>$id_number</td>
                                <td>$username</td>
                                <td>$user_email</td>
                                <td>
                                    <img src='../users_area/user_images/$user_image' alt='$username photo' class='img-thumbnail' width='100px'/>
                                </td>
                                <td>$user_address</td>
                                <td>$user_mobile</td>
                                <td>
                                    <a href='index.php?delete_user=$user_id' class='btn btn-danger btn-sm' data-bs-toggle='modal' data-bs-target='#deleteModal_$user_id'>
                                        Delete
                                    </a>
                                    <!-- Delete Confirmation Modal -->
                                    <div class='modal fade' id='deleteModal_$user_id' tabindex='-1' aria-labelledby='deleteModal_$user_id.Label' aria-hidden='true'>
                                        <div class='modal-dialog modal-dialog-centered'>
                                            <div class='modal-content'>
                                                <div class='modal-body text-center'>
                                                    <h4 class='text-danger'>Are you sure?</h4>
                                                    <p>Do you really want to delete <strong>$username</strong>? This action cannot be undone.</p>
                                                    <div class='d-flex justify-content-center gap-3'>
                                                        <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancel</button>
                                                        <a href='index.php?delete_user=$user_id' class='btn btn-danger'>Delete</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            ";
                            $id_number++;
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="../assets/js/bootstrap.bundle.js"></script>
</body>

</html>