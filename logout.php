<?php

session_start();


if ((isset($_SESSION['loggedIn'])) && (isset($_SESSION['user_id']))) {
    session_destroy();
    header('Location: login.php');
} elseif ((isset($_SESSION['loggedIn'])) && (isset($_SESSION['admin_id']))) {
    session_destroy();
    header('Location: adminLogin.php');
} else {
    header('Location: register.php');
}
