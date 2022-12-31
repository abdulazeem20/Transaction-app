<?php

require_once 'public/header.php';

if (isset($_SESSION['loggedIn']) && isset($_SESSION['user_id'])) {
    header('location: dashboard.php');
} elseif (isset($_SESSION['loggedIn']) && isset($_SESSION['admin_id'])) {
    header('location: adminDashboard.php');
}

$email = $password = $err = '';
if (isset($_POST['submit'])) {
    $password = sanitizeInputs($_POST['pwrd']);
    $email = sanitizeInputs($_POST['email']);
    $log = $obj_Auth->login($email, $password);
    if ($log) {
        $_SESSION['loggedIn'] = true;
        $_SESSION['user_id'] = $log['id'];
        //   echo $_SESSION['user_id'];
        header('location: dashboard.php');
    } else {
        $err = 'Invalid Login Details';
    }
}

?>

<body class='bg-gradient-primary'>
    <!-- <nav class = 'nav nav-pills bg-gradient-light'>
<li class = 'nav-item'>
<a class = 'nav-link active' href = 'login.php'>User Login</a>
</li>
<li class = 'nav-item'>
<a class = 'nav-link ' href = 'adminLogin.php' tabindex = '-1' aria-disabled = 'true'>Admin Login</a>
</li>
</nav> -->

    <div class='container'>

        <!-- Outer Row -->
        <div class='row justify-content-center'>
            <div class='col-xl-10 col-lg-12 col-md-9'>
                <div class='card o-hidden border-0 shadow-lg my-5'>
                    <div class='card-body p-0'>
                        <!-- Nested Row within Card Body -->
                        <div class='row'>
                            <div class='col-lg-6 d-none d-lg-block bg-login-image'></div>
                            <div class='col-lg-6'>
                                <div class='p-5'>
                                    <div class='text-center'>
                                        <h1 class='h4 text-gray-900 mb-4'>Welcome Back User!</h1>
                                    </div>
                                    <?php if (isset($_POST['submit']) && $err) { ?>
                                    <div class='alert alert-danger alert-dismissible fade show' role='alert'>
                                        <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                                            <span aria-hidden='true'>&times;
                                            </span>
                                            <span class='sr-only'>Close</span>
                                        </button>
                                        <strong><?= $err; ?>!</strong>
                                    </div>
                                    <?php } ?>
                                    <form class='user' action="<?= $_SERVER['PHP_SELF']; ?>" method='POST'>
                                        <div class='form-group'>
                                            <input type='email' name='email' class='form-control form-control-user' id='exampleInputEmail' aria-describedby='emailHelp' placeholder='Enter Email Address...'>
                                        </div>
                                        <div class='form-group'>
                                            <input type='password' name='pwrd' class='form-control form-control-user' id='exampleInputPassword' placeholder='Password'>
                                        </div>
                                        <div class='form-group'>
                                            <div class='custom-control custom-checkbox small'>
                                                <input type='checkbox' class='custom-control-input' id='customCheck'>
                                                <label class='custom-control-label' for='customCheck'>Remember
                                                    Me</label>
                                            </div>
                                        </div>
                                        <button type='submit' name='submit' class='btn btn-primary btn-user btn-block'>
                                            Login
                                        </button>
                                        <hr>
                                        <div class='text-center'>
                                            <a class='small' href='register.php'>Create an Account!</a>
                                        </div>
                                </div>
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
    document.title = 'Sign-In Page';
    </script>