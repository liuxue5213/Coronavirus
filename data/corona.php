<?php

require_once './common/QueryList.php';
require_once './common/phpQuery.php';
require_once './common/config.php';
require_once './common/RabbitMQCommand.php';
// require_once './common/redis.php';

use QL\QueryList;

/**
 * 获取数据加入队列
 */
class CoronaInfo {

    public function index($return = false)
    {
        $url = 'https://www.worldometers.info/coronavirus';
        $rules = array(
            'last_updated' => ['.content-inner div:eq(1)', 'text'],
            'arr' => ['#maincounter-wrap .maincounter-number', 'text']
        );
        // $rang = '#maincounter-wrap .maincounter-number';
        $rang = '.container .col-md-8';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;

        $tmpKey = 'coronaInfo';
        if (isset($data[0])) {
            // $tmpArr = array_values(array_filter(explode("\n", $data[0]['arr'])));
            // $this->redisDel($redis, $tmpKey);
            // $redis->hSet($tmpKey, 'last_updated', str_replace('Last updated:', '', $data[0]['last_updated']).'(格林尼治标准时间)');
            // $redis->hSet($tmpKey, 'cases', $tmpArr[0]);
            // $redis->hSet($tmpKey, 'deaths', $tmpArr[1]);
            // $redis->hSet($tmpKey, 'recovered', $tmpArr[2]);
        }

        return $redis->hGetAll($tmpKey);
    }

    public function redisDel($redis, $key)
    {
        if ($redis->exists($key)) {
            $redis->del($key);
        }
    }

    public function ttt() {
        $conn_args = array(
            'host'=>'127.0.0.1',
            'port'=>5672,
            'login'=>'test',
            'password'=>'123456',
            'vhost'=>'/'
        );
        $e_name = 'e_demo';
        $q_name = 'q_demo';
        $k_route = 'key_1';
        $conn = new AMQPConnection($conn_args);
        if(!$conn->connect()){
            die('Cannot connect to the broker');
        }
        $channel = new AMQPChannel($conn);
        $ex = new AMQPExchange($channel);
        $ex->setName($e_name);
        $ex->setType(AMQP_EX_TYPE_DIRECT);
        $ex->setFlags(AMQP_DURABLE);
        $q = new AMQPQueue($channel);
        // var_dump($q);
        $q->setName($q_name);
        $q->bind($e_name, $k_route);

        $arr = $q->get(AMQP_AUTOACK);
        $res = $q->ack($arr->getDeliveryTag());
        // $msg = $arr->getBody();
        var_dump($arr);
        $conn->disconnect();
    }
}