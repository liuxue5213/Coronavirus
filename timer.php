<?php
require_once './data/get.php';
require_once './data/corona.php';
require_once './data/other.php';
require_once 'baidu/get.php';

// $corona = new Corona();
// $corona->index();
// var_dump('data refresh success');

// $ci = new CoronaInfo();
// $ci->index();
// var_dump('info refresh success');

// $oc = new OtherCountry();
// $data = $oc->index();
// var_dump('info refresh success');

$baidu = new Baidu();
$data = $oc->baidu();
var_dump('info refresh success');