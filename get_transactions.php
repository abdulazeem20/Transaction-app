<?php
    session_start();
    require_once 'class/users/Auth.php';

    $obj = new Auth(); //instantiate Auth

    $user_id = $_SESSION['user_id'];

    $transactions = $obj->getAllTransactions($user_id);

    echo $transactions;
