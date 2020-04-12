<?php
require_once 'data/get.php';
require_once 'data/corona.php';
require_once 'data/other.php';
require_once 'baidu/get.php';

//liuxue5213.github.io
$corona = new Corona();
$corona->index();
var_dump('data refresh success');
echo '1';die;

$ci = new CoronaInfo();
$ci->index();
var_dump('info refresh success');

$oc = new OtherCountry();
$data = $oc->index();
var_dump('info refresh success');

$baidu = new Baidu();
$data = $baidu->index(1);
var_dump('baidu refresh success');