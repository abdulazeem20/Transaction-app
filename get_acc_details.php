<?php

    session_start();
    require_once 'class/users/Auth.php';

    $obj = new Auth(); //instantiate Auth

    $search = $_GET['search'];

    $users = $obj->getAccountDetails($search, $_SESSION['user_id']);

    echo $users;
        ?>
    

