<?php
require_once 'data/consumer.php';
// require_once 'baidu/get.php';
require_once 'common/config.php';
require_once 'common/RabbitMQCommand.php';

$rabbitConfig = (new Config())->rabbitConfig();
$exchange_name = 'ex_corona';
$queue_name = 'q_corona';
$route_key = 'key_corona';
$ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);
$cons = new Consumer();
$res = $ra->run(array($cons, 'processMessage'), false);



