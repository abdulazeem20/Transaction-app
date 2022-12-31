<?php
require_once 'public/header.php';
$page = 'save';

if (!isset($_SESSION['loggedIn']) && !isset($_SESSION['user_id'])) {
    header('location: login.php');
}
if (isset($_SESSION['user_id'])) {
    $id = $_SESSION['user_id'];
    $userDetails = $obj_Auth->getUserDetails($id);
}
$match = "/^[\d,.]*[\d]+$/i";
$id = $err = $success = $save = '';
if (isset($_POST['submit'])) {
    $userId = $userDetails['id'];
    $amount = $_POST['amount'];
    if (empty($amount)) {
        $err = 'fill in the amount';
    } else if (strlen($amount) > 7) {
        $err = "Amount must be max of 7 character";
    } elseif (!(preg_match_all($match, $amount))) {
        $err = 'Kindly input a valid amount';
    } else {
        //Saving into SAVINGS TABLE
        $save = $obj_Auth->getCredit($userId, $amount);
        $success = 'Transaction sucessful';

        //Saving into TRANSACTION TABLE
        $transact_type = 'Credit';
        $obj_Auth->saveIntoTransaction($_SESSION['user_id'], $transact_type, $_SESSION['user_id'], $amount);

        //Updating BALANCE table
        $userBal = $obj_Auth->getBalance($userId); // Getting user current balance before this transaction
        $newBal = $userBal + $amount;
        $obj_Auth->updateBalance($_SESSION['user_id'], $newBal);
    }
}

?>
<script>
    document.title = "<?= $userDetails['firstname']; ?> - Save Money";
</script>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php require_once 'public/include/sidebar.php'; ?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php require_once 'public/include/navbar.php'; ?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Save Money</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                            <i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-xl-6 col-md-9 mb-4 ml-5 justify-content-center">
                            <div class="card border-left-primary shadow ">
                                <div class="card-body m-5  ">
                                    <form action="" method="POST" class="form">
                                        <?php if ($err) { ?>
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <?= $err; ?>!!.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php } elseif ($success) { ?>
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <?= $success; ?>!!.
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                        <?php } ?>
                                        <div class="form-group row ">
                                            <label class="col-md-4" for="">Account Number </label>
                                            <div class="col-md-8">
                                                <input type="text" name="accountNumber" class="form-control" id="accountNumber" value="<?php echo $userDetails['account_no']; ?>" readonly='readonly'>
                                            </div>
                                        </div>
                                        <div class="form-group row ">
                                            <label class="col-md-4 " for="">Amount</label>
                                            <div class="col-md-8">
                                                <input type="text" oninput="this.value = this.value.replace(/[^0-9\.]+/g, '').replace(/(\..*)\./g, '$1');" name="amount" class="form-control amount" id="amount">
                                                <span class="text-danger " id="error"></span>
                                            </div>

                                        </div>

                                        <div class="form-group row ">
                                            <div class="offset-md-9 col-md-3">
                                                <button type="submit" name="submit" class="btn btn-primary" style="width: 100%;">Save</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>

                    </div>


                    <!-- Content Row -->
                    <div class="row">
                        <div class="co-12 p-5">
                            Lorem ipsum dolor sit amet consectetur adipisicing elit. Numquam omnis corporis at quis
                            beatae accusantium, ab, voluptatem ratione sed aperiam doloremque non ducimus minima culpa
                            recusandae incidunt ex cupiditate et!
                        </div>
                    </div>


                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <?php

    require_once 'public/footer.php';

    ?>
    <script>
        $(document).ready(function() {
            $('#amount').keyup(function() {
                var amount = $(this).val();
                var expression = /^[\d,.]*[\d]+$/gi;
                if (!(amount.match(expression))) {
                    var error = "*Kindly Input a valid amount";
                    $('#error').html(error);
                } else if (amount.match(expression)) {
                    $('#error').html('');
                }
            })
        })
    </script>