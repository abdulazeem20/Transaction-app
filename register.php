<?php

require_once 'public/header.php';

if (isset($_SESSION['loggedIn'])) {
    header('location: dashboard.php');
}

$states = $obj_Auth->getStates();

$acc_num = $length = $account_no = $firstName = $otherName = $email = $phone_no = $state = $lga = $password = $password2 = $err = $success = '';

function getRandom()
{
    $num = rand(0, 5000);
    $length = 10;
    $gen_random = str_pad($num, $length, '235', STR_PAD_LEFT);

    return $gen_random;
}
$acc_num = getRandom();

// $acc_num = $obj_Auth->countID();
// $length = 10;
// $acc_num = str_pad($acc_num, $length, '0', STR_PAD_LEFT);

if (isset($_POST['submit'])) {
    $account_no = sanitizeInputs($_POST['accNum']);
    $firstName = sanitizeInputs($_POST['firstName']);
    $otherName = sanitizeInputs($_POST['otherName']);
    $email = sanitizeInputs($_POST['email']);
    $phone_no = sanitizeInputs($_POST['phoneNumber']);
    $state = sanitizeInputs($_POST['state']);
    $lga = sanitizeInputs($_POST['lga']);
    $password = sanitizeInputs($_POST['pwrd']);
    $password2 = sanitizeInputs($_POST['pwrd2']);

    $countAccountNum = $obj_Auth->checkAccountNo($account_no);
    if (empty($firstName) || empty($otherName) || empty($email) || empty($phone_no) || empty($password) || empty($password2)) {
        $err = 'input all fields';
    } elseif ($countAccountNum > 0) {
        // $account_no = getRandom();
        $err = 'Kindly Refresh this page!!';
    } elseif (strlen($password) < 7 || strlen($password) > 10) {
        $err = 'Password Must be between 7 & 10 inclusive';
    } elseif ($password !== $password2) {
        $err = 'Password Mismatch';
    } else {
        // saving into database
        $register = $obj_Auth->register($firstName, $otherName, $email, $password, $phone_no, $state, $lga, $account_no);

        // register($firstname, $othername, $email,  $password, $phone, $state, $lga, $account_no)

        $log = $obj_Auth->login($email, $password);

        // setting session
        $_SESSION['user_id'] = $log['id'];
        $_SESSION['loggedIn'] = true;
        // $_SESSION['user_id'] = $user['id'];

        //Initializing balance for user
        $obj_Auth->initializeBalance($_SESSION['user_id'], $account_no);

        if (isset(($_SESSION['loggedIn'])) && (isset($_SESSION['user_id']))) {
            header('Location: dashboard.php');
        }
        // $success = "Sucessfully Validated";
    }
}

?>

<body class="bg-gradient-primary">

    <div class="container">

        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <!-- Nested Row within Card Body -->
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                    <div class="col-lg-7">
                        <div class="p-5">
                            <div class="text-center">
                                <h1 class="h4 text-gray-900 mb-4">Create an Account!</h1>
                            </div>
                            <?php if (isset($_POST['submit']) && $err) { ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <strong><?= $err; ?>!</strong>
                                </div>
                            <?php } elseif (isset($_POST['submit']) && $success) { ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        <span class="sr-only">Close</span>
                                    </button>
                                    <strong><?= $success; ?>!</strong>
                                </div>
                            <?php } ?>
                            <form class="user" action="<?= $_SERVER['PHP_SELF']; ?>" method="POST">
                                <div class="form-group">
                                    <input type="text" class="form-control form-control-user" name="accNum" value="<?= $acc_num; ?>" id="exampleInputAccNum" readonly>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="firstName" id="exampleFirstName" placeholder="First Name" value="<?= $firstName; ?>">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control form-control-user" name="otherName" id="exampleOtherName" placeholder="Other Name" value="<?= $otherName; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="email" class="form-control form-control-user" name="email" id="email" placeholder="Email Address" value="<?php
                                                                                                                                                                if (isset($_POST['submit'])) {
                                                                                                                                                                    echo $email;
                                                                                                                                                                }
                                                                                                                                                                ?>">
                                        <p class="text-danger pl-2"><small id="error"></small></p>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="text" class="form-control form-control-user" name="phoneNumber" id="exampleInputPhone" placeholder="Phone Number" value="<?= $phone_no; ?>">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-control rounded-pill " name="state" id="state">
                                            <option value="" selected="">Choose your state</option>
                                            <?php foreach ($states as $state) { ?>
                                                <option value="<?= $state['id']; ?>"><?= $state['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <select class="form-control rounded-pill" name="lga" id="lga">
                                            <option value="" selected="">Choose your Local Government</option>

                                        </select>
                                    </div>

                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-6 mb-3 mb-sm-0">
                                        <input type="password" class="form-control form-control-user" name="pwrd" id="exampleInputPassword" placeholder="Password">
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="password" class="form-control form-control-user" name="pwrd2" id="exampleRepeatPassword" placeholder="Repeat Password">
                                    </div>
                                </div>
                                <button type="submit" id="submit" name="submit" class="btn btn-primary btn-user btn-block">
                                    Register Account
                                </button>
                                <!-- <hr>
                <a href="index.html" class="btn btn-google btn-user btn-block">
                  <i class="fab fa-google fa-fw"></i> Register with Google
                </a>
                <a href="index.html" class="btn btn-facebook btn-user btn-block">
                  <i class="fab fa-facebook-f fa-fw"></i> Register with Facebook
                </a>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="forgot-password.html">Forgot Password?</a>
              </div> -->
                                <div class="text-center">
                                    <a class="small" href="login.php">Already have an account? Login!</a>
                                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <?php

    require_once 'public/footer.php';

    ?>

    <script>
        $(document).ready(function() {
            $('#email').keyup(function() {
                var email = $(this).val()
                $.ajax({
                    method: "GET",
                    contentType: false,
                    data: {
                        'email': email
                    },
                    url: 'check_email.php',
                    dataType: "json",
                    success: function(response) {
                        var error = '';
                        console.log(response.email)
                        if (response.no_email > 0) {
                            error = 'Email already exist'
                            $('#error').text(error);
                            $('#submit').attr('disabled', 'disabled')
                        } else {
                            $('#error').text(error)
                            $('#submit').attr('disabled', false)
                        }
                    }
                })
            })
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
        })
        document.title = 'Sign-Up Page'
    </script>