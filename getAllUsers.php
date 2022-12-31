<?php

require_once 'class/admin/adminFetch.php';

$obj = new Fetch(); //instantiate Auth

$users = $obj->getAllUsers();
    echo json_encode($users);
