<?php

require_once 'class/users/Auth.php';

$obj = new Auth(); //instantiate Auth

$stateId = $_GET['stateId'];

$lgas = $obj->getLgas($stateId);

echo $lgas;
