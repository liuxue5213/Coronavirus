<?php
define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
include BASE_PATH.'data/consumer.php';
include BASE_PATH.'common/config.php';
include BASE_PATH.'common/RabbitMQCommand.php';

$rabbitConfig = (new Config())->rabbitConfig();
$exchange_name = 'ex_corona';
$queue_name = 'q_corona';
$route_key = 'key_corona';
$ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);
$cons = new Consumer();
$res = $ra->run(array($cons, 'processMessage'), false);



