<?php
require_once 'public/header.php';
$page = 'transaction';

if (!isset($_SESSION['user_id'])) {
    header('location: login.php');
}

$id = '';
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    //getting the current user details
    $userDetails = $obj_Auth->getUserDetails($user_id);
}

?>
<script>
document.title = "<?= $userDetails['firstname']; ?>'s Dashboard";
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
                        <h1 class="h3 mb-0 text-gray-800">My Transactions</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>

                    <!-- Content Row -->
                    <div class="row">

                        <!-- Earnings (Monthly) Card Example -->
                        <div class="col-12 col-sm-12">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="15%">Transaction Type</th>
                                                <th width="60%">Transaction Details</th>
                                                <th width="25%">Date</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Transaction Type</th>
                                                <th>Transaction Details</th>
                                                <th>Date</th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>



                    </div>
                    <!-- /.container-fluid -->

                </div>
                <!-- End of Main Content -->

            </div>

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Abdul-Azeem 2020</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>

    </div>
    <?php

    require_once 'public/footer.php';

    ?>

    <script>
    $(document).ready(function() {
        $('#example').DataTable({
            "processing": true,
            "ajax": {
                url: "get_transactions.php",
                dataSrc: ""

            },
            "columns": [{
                    data: "transact_type",
                    render: function(data, type, row, meta) {
                        var text = "";
                        //transfer received
                        if (row.transact_type == 'Debit' && row.transfer == 1 && row.recipent_id == <?php echo $user_id; ?>) {
                            text = "Credit";
                        } else {
                            text = row.transact_type;
                        }
                        return text;
                    }
                },
                {
                    data: "creator_id",
                    render: function(data, type, row, meta) {
                        var text = ""
                        // deposit
                        if (row.transact_type == "Credit" && row.transfer == 0) {
                            text = "You deposited a sum of ₦" + row.amount + " into your account"
                        }

                        //transfer sent
                        if (row.transact_type == 'Debit' && row.transfer == 1 && row.creator_id == <?php echo $user_id; ?>) {
                            text = "You transfered the sum of ₦" + row.amount + " to " + row.recipentFirstName + " " + row.recipentLastName;
                        }

                        //transfer received
                        if (row.transact_type == 'Debit' && row.transfer == 1 && row.recipent_id == <?php echo $user_id; ?>) {
                            text = "A sum of ₦" + row.amount + " was sent to you from " + row.creatorFirstName + " " + row.creatorLastName;
                        }
                        return text
                    }
                },
                {
                    data: "created_at",
                    render: function(data, type, row, meta) {
                        return "On " + row.created_at + " at " + row.time
                    }
                },
            ]
        });
    });
    </script>