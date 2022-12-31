<?php
require_once 'public/header.php';

// $page = 'dashboard';
if (!isset($_SESSION['admin_id'])) {
    header('location: adminLogin.php');
}

if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    $adminDetails = $obj_adminAuth->getAdminDetails($admin_id);

    //getting states and local Government
    $statesAndLga = $objAdminFetch->getUsersStatesAndLga($admin_id);
}
$states = $obj_Auth->getStates();
$success = $err = '';
if (isset($_POST['update'])) {
    $firstName = sanitizeInputs($_POST['fname']);
    $otherName = sanitizeInputs($_POST['oname']);
    $email = sanitizeInputs($_POST['email']);
    $phone_no = sanitizeInputs($_POST['phoneNumber']);
    $state = sanitizeInputs($_POST['state']);
    $lga = sanitizeInputs($_POST['lga']);

    if (empty($firstName) || empty($otherName) || empty($email) || empty($phone_no) || empty($state) || empty($lga)) {
        $err = 'field can\'t be empty';
    } else {
        $update = $objAdminFetch->update($admin_id, $firstName, $otherName, $email, $phone_no, $state, $lga);
        if ($update) {
            $success = 'Profile Sucessfully Updated';
        } else {
            $err = 'Profle Update Failed';
        }
    }
}

?>
<script>
document.title = "Admin's Profile";
</script>

<div id="page-top">

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

                <div class="container">
                    <div class="row">
                        <div class=" offset-xl-1 offset-md-1 col-xl-10 col-md-10 col-sm-12 mt-5">
                            <div class="card shadow ">
                                <div class="card-header ">
                                    <h2 class="text-center"><?= $adminDetails['firstname']; ?>'s Profile (Admin)</h2>
                                </div>
                                <div class="card-body offset-1">
                                    <?php if ($success) { ?>
                                    <div class="row">
                                        <div class=" offset-3 col-6">
                                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <strong><?= $success; ?> !!!</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <?php  } elseif ($err) { ?>
                                    <div class="row">
                                        <div class=" offset-3 col-6">
                                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    <span class="sr-only">Close</span>
                                                </button>
                                                <strong><?= $err; ?> !!!</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <div class="row">
                                        <div class="col">
                                            <h4 class="card-title">
                                                <strong>Name:
                                                </strong><?= $adminDetails['firstname'] . ' ' . $adminDetails['othername']; ?>
                                            </h4>
                                            <h4 class="card-title"><strong>Phone Number:
                                                </strong><?= $adminDetails['phone_no']; ?>
                                            </h4>
                                            <h4 class="card-title"><strong>E-mail:
                                                </strong><?= $adminDetails['email']; ?>
                                            </h4>
                                            </h4>
                                            <h4 class="card-title"><strong>State:
                                                </strong><?= $statesAndLga['state']; ?>
                                            </h4>
                                            <h4 class="card-title"><strong>Local Government:
                                                </strong><?= $statesAndLga['lga']; ?> </h4>
                                        </div>
                                    </div>
                                    <!-- <div class="col-6">
                                        <img src="" alt="">
                                    </div> -->
                                </div>
                                <div class="card-footer ">
                                    <div class="row justify-content-center">
                                        <div class="col-3">
                                            <!-- Button trigger modal edit profile -->
                                            <button type="button" class="btn btn-primary btn-lg " data-toggle="modal" data-target="#modelId">
                                                Edit Profile
                                            </button>
                                            <!-- Button trigger modal edit profile -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal edit profile -->
        <div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><?= $adminDetails['firstname']; ?>'s profile update (Admin)</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12">
                                    <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                                        <div class="form-group">
                                            <label for="fname">First Name: </label>
                                            <input type="text" class="form-control" name="fname" id="fname" aria-describedby="helpId" placeholder="" value="<?= $adminDetails['firstname']; ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="oname">Other Name: </label>
                                            <input type="text" class="form-control" name="oname" id="oname" aria-describedby="helpId" placeholder="" value="<?= $adminDetails['othername']; ?> ">
                                        </div>
                                        <div class="form-group">
                                            <label for="phoneNumber">Phone Number: </label>
                                            <input type="text" class="form-control" name="phoneNumber" id="phoneNumber" aria-describedby="helpId" placeholder="" value="<?= $adminDetails['phone_no']; ?> ">
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Email: </label>
                                            <input type="email" class="form-control" name="email" id="email" aria-describedby="helpId" placeholder="" value="<?= $adminDetails['email']; ?>">
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <select class="form-control " name="state" id="state">
                                                    <option value="" selected="">-- Select Your State --</option>
                                                    <?php foreach ($states as $state) { ?>
                                                    <option value="<?= $state['id']; ?>"><?= $state['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <select class="form-control " name="lga" id="lga">
                                                    <option value="" selected="">-- Select local Government --</option>

                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-2 offset-9 ml-auto">
                                                <button type="submit" name="update" class="btn btn-primary">Update</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal edit profile -->
    </div>

    <?php

    require_once 'public/footer.php';

    ?>
    <script>
    $(document).ready(function() {
        $('#exampleModal').on('show.bs.modal', event => {
            var button = $(event.relatedTarget);
            var modal = $(this);
            // Use above variables to manipulate the DOM

        });
        $('#state').change(function() {
            var stateId = $(this).val()
            // console.log(stateId);
            $.ajax({
                method: "GET",
                contentType: false,
                data: {
                    "stateId": stateId
                },
                url: 'check_lga.php',
                dataType: "Json",
                success: function(response) {
                    var options = '';
                    $.each(response, function(i, value) {
                        options += "<option value = " + value.id + " > " + value
                            .name + "</option>";
                        $('#lga').html(options);
                    })
                }
            })
        })
    });
    </script>