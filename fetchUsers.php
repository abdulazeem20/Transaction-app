<?php
require_once 'public/header.php';
$page = 'details';
if (!isset($_SESSION['admin_id'])) {
    header('location: adminLogin.php');
}

$totalUsers = '';
if (isset($_SESSION['admin_id'])) {
    $id = $_SESSION['admin_id'];
    $adminDetails = $obj_adminAuth->getAdminDetails($id);
}
?>
<script>
    document.title = "Display Users";
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
                        <h1 class="h3 mb-0 text-gray-800">USERS</h1>
                        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate Report</a>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-12 col-sm-12">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th width="25%">Name</th>
                                                <th width="15%">E-mail</th>
                                                <th width="25%">Phone Number </th>
                                                <th width="15%">Account Number</th>
                                                <th width="10%">Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>Name</th>
                                                <th>E-mail</th>
                                                <th>Phone Number </th>
                                                <th>Account Number</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                    </table>
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

    <!-- Modal view users -->
    <div class="modal fade" id="viewUsers" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="modalTitle">'s Detail</h5>
                    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span> -->
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <h3><strong>Name: </strong><span id="name"></span></h3>
                        <h3><strong>Phone Number: </strong><span id="phone_no"></span></h3>
                        <h3><strong>E-Mail: </strong><span id="email"></span></h3>
                        <h3><strong>Account Number: </strong><span id="account_no"></span></h3>
                        <h3><strong>State: </strong><span id="state"></span></h3>
                        <h3><strong>Local Government: </strong><span id="lga"></span></h3>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <!-- <button type="button" class="btn btn-primary">Save</button> -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal view users -->

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title " id="exampleModalLabel">Ready to Leave?</h5>
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
                    url: "getAllUsers.php",
                    dataSrc: ""

                },
                "columns": [{
                        data: "id",
                        render: function(data, type, row, meta) {
                            return row.firstname + " " + row.othername;
                        }
                    },
                    {
                        data: "email",
                    },
                    {
                        data: "phone_no"
                    },
                    {
                        data: "account_no"
                    },
                    {
                        data: "id",
                        render: function(data, type, row, meta) {
                            // Button trigger for modal view users
                            return "  <input type=\"button\" id = \" modalUsersToggler\" data-value=\"" +
                                row.id +
                                "\" data-toggle=\"modal\" data-target=\"#viewUsers\" class=\"btn btn-primary\" value=\"view users\">"
                            // Button trigger for modal view users
                        }

                    }
                ]
            });
            // $('#modalUsersToggler').click(function() {
            $(document).on('click', 'input', function() {
                var user_id = $(this).data('value');
                // console.log(user_id);
                $.ajax({
                    method: "GET",
                    contentType: false,
                    data: {
                        "user_id": user_id
                    },
                    url: "getUsers.php",
                    dataType: "Json",
                    success: function(response) {
                        $('#modalTitle').html(response.firstname + "'s Details");
                        $('#name').html(response.firstname + " " + response.othername);
                        $('#phone_no').html(response.phone_no);
                        $('#email').html(response.email);
                        $('#account_no').html(response.account_no);
                        $('#state').html(response.state);
                        $('#lga').html(response.lga);
                    }
                })
            })
            // script for  modal view users
            $('#exampleModal').on('show.bs.modal', event => {
                var button = $(event.relatedTarget);
                var modal = $(this);
                // Use above variables to manipulate the DOM
            });
            // script for  modal view users
        });
    </script>