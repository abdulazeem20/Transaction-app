<?php

//Require classes
require_once 'class/users/Auth.php';

$obj = new Auth(); //instantiate Auth
//if (isset($_POST['state_id'])) {
$email = $_GET['email'];
//}
$check = $obj->checkEmail($email);

echo $check;