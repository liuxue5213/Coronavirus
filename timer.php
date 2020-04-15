<?php
require_once 'data/Producer.php';
// require_once 'data/other.php';
// require_once 'baidu/get.php';
// require_once 'common/config.php';
// require_once 'common/RabbitMQCommand.php';

//liuxue5213.github.io
$prod = new Producer();
// var_dump('world refresh start');
// $prod->worldInfo();
// var_dump('world refresh end');

var_dump('country refresh start');
$prod->countryInfo();
var_dump('country refresh end');

// var_dump('detail refresh start');
// $prod->detail();
// var_dump('detail refresh end');


// require_once 'common/redis.php';
// require_once 'common/checkIp.php';
// require_once 'data/corona.php';

// $config = array(
//     'host' => '127.0.0.1',
//     'port' => '6379'
// );
// $redis = new Predis($config);
// if ($redis->exists('coronaInfo')) {
//     $info = $redis->hGetAll('coronaInfo');
// } else {
//     $corona = new CoronaInfo();
//     $info = $corona->index(1);
// }
// print_r(json_encode($info));


// $configs = array('host'=>'127.0.0.1','port'=>5672,'username'=>'test','password'=>'123456','vhost'=>'/');
// $exchange_name = 'ex_email';
// $queue_name = 'q_email';
// $route_key = 'key_email';
// $ra = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
// for($i=0;$i<=100;$i++){
//     $ra->send(date('Y-m-d H:i:s',time()));
// }
// 

// $configs = array('host'=>'127.0.0.1','port'=>5672,'username'=>'test','password'=>'123456','vhost'=>'/');
// $exchange_name = 'ex_email';
// $queue_name = 'q_email';
// $route_key = 'key_email';
// $ra = new RabbitMQCommand($configs,$exchange_name,$queue_name,$route_key);
// class A{
//     function processMessage($envelope, $queue) {
//         $msg = $envelope->getBody();
//         $envelopeID = $envelope->getDeliveryTag();
//         $pid = posix_getpid();
//         file_put_contents("log{$pid}.log", $msg.'|'.$envelopeID.''."\r\n",FILE_APPEND);
//         $queue->ack($envelopeID);
//     }
// }
// $a = new A();
// $s = $ra->run(array($a,'processMessage'),false);







// $ci = new CoronaInfo();
// $ci->index();
// var_dump('info refresh success');

// $oc = new OtherCountry();
// $data = $oc->index();
// var_dump('info refresh success');

// $baidu = new Baidu();
// $data = $baidu->index(1);
// var_dump('baidu refresh success');