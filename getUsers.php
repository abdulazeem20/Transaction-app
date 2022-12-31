<?php
require_once 'class/admin/adminFetch.php';
$obj = new Fetch();

$user_id = $_GET['user_id'];
$userDetail = $obj->getUsers($user_id);
echo json_encode($userDetail);
