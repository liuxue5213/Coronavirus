<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 11:02:44
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-01 14:51:15
 */
require_once '../common/redis.php';
require_once '../common/checkIp.php';
require_once 'corona.php';

$config = array(
	'host' => '127.0.0.1',
	'port' => '6379'
);
$redis = new Predis($config);
if ($redis->exists('coronaInfo')) {
	$info = $redis->hGetAll('coronaInfo');
} else {
	$corona = new CoronaInfo();
	$info = $corona->index(1);
}

// $ip = new Ip();
// $info['is_cn'] = $ip->index();

print_r(json_encode($info));
