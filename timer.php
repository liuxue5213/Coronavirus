<?php
define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
include_once BASE_PATH.'data/Producer.php';
include_once BASE_PATH.'baidu/prod.php';

//liuxue5213.github.io
$prod = new Producer();
$prod->worldInfo();
var_dump('world refresh success');

$prod->countryInfo();
var_dump('country refresh success');

$baidu = new BaiduProd();
$data = $baidu->index();
var_dump('baidu refresh success');
 
// $prod->detail('italy');
// var_dump('country refresh success');


