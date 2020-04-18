<?php
define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
include_once 'data/consumer.php';
include_once 'common/common.php';
include_once 'common/RabbitMQCommand.php';

$rabbitConfig = (new CommonConfig())->rabbitConfig();
$exchange_name = 'ex_corona';
$queue_name = 'q_corona';
$route_key = 'key_corona';
$ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);
$cons = new Consumer();
$res = $ra->run(array($cons, 'processMessage'), false);



