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

    public function index()
    {
        $rabbitConfig = (new Config())->rabbitConfig();

        // print_r($rabbitConfig);die;

        $exchange_name = 'ex_corona';
        $queue_name = 'q_corona';
        $route_key = 'key_corona';
        $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);
        for ($i = 0; $i <= 5; $i++) {
            $ra->send(date('Y-m-d H:i:s',time()));
        }
        die;
        
        // $url = 'https://www.worldometers.info/coronavirus';
        // $rules = array(
        //     'last_updated' => ['.content-inner div:eq(1)', 'text'],
        //     'arr' => ['#maincounter-wrap .maincounter-number', 'text']
        // );
        // // $rang = '#maincounter-wrap .maincounter-number';
        // $rang = '.container .col-md-8';
        // $hj = QueryList::Query($url, $rules, $rang);
        // $data = $hj->data;

        // $tmpKey = 'coronaInfo';
        // if (isset($data[0])) {
            // $tmpArr = array_values(array_filter(explode("\n", $data[0]['arr'])));
            // $this->redisDel($redis, $tmpKey);
            // $redis->hSet($tmpKey, 'last_updated', str_replace('Last updated:', '', $data[0]['last_updated']).'(格林尼治标准时间)');
            // $redis->hSet($tmpKey, 'cases', $tmpArr[0]);
            // $redis->hSet($tmpKey, 'deaths', $tmpArr[1]);
            // $redis->hSet($tmpKey, 'recovered', $tmpArr[2]);
        // }

        // return $redis->hGetAll($tmpKey);
    }

    public function index2($return = false)
    {
        $url = 'https://www.worldometers.info/coronavirus';
        $rules = array(
            'country' => ['td:eq(0)', 'text'],
            'country_url' => ['td:eq(0)>a', 'href', 'text'],
            'total_cases' => ['td:eq(1)', 'text'],
            'new_cases' => ['td:eq(2)', 'text'],
            'total_deaths' => ['td:eq(3)', 'text'],
            'new_deaths' => ['td:eq(4)', 'text'],
            'total_recovered' => ['td:eq(5)', 'text'],
            'active_cases' => ['td:eq(6)', 'text'],
            'serious_critical' => ['td:eq(7)', 'text'],
            'tot_cases_1m' => ['td:eq(8)', 'text'],
            'tot_deaths_1m' => ['td:eq(9)', 'text'],
            'tot_test' => ['td:eq(10)', 'text'],
            'tot_test_1m' => ['td:eq(11)', 'text'],
            'callback' => array('Corona', 'checkNum')
        );
        $rang = '#nav-tabContent tbody tr';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;

        echo '<pre>';
        print_r($data);
        echo '</pre>';
        die;

        $count = count($data);
        if ($count) {
            $config = array(
                'host' => '127.0.0.1',
                'port' => '6379'
            );
            $redis = new Predis($config);
            $key = 'corona';
            $j = 0;

            // echo '<pre>';
            // print_r($count);
            // echo '</pre>';

            for ($i = 0; $i <= $count; $i++) {
                $tmpKey = '';
                if ($i % 100 == 0) {
                    $j++;
                    $tmpKey = $key.$j;
                    $this->redisDel($redis, $tmpKey);
                }
                $tmpCountry = isset($data[$i]['country']) && $data[$i]['country'] ? $data[$i]['country'] : '';
                //过滤出世界汇总的数据 2020-04-12
                if (in_array($tmpCountry, array('Total:','World', 'Europe', 'North America', 'Asia', 'South America', 'Africa', 'Oceania', 'Diamond Princess', 'MS Zaandam'))) {
                    $redis->hSet('world', $tmpCountry, serialize($data[$i]));
                } else {
                    if (isset($data[$i]['country_url']) && $data[$i]['country_url']) {
                        $tmpK = explode('/', $data[$i]['country_url']);
                        $data[$i]['name'] = isset($tmpK[1]) ? $tmpK[1] : '';
                    }
                    if (isset($data[$i])) {
                        $redis->hSet($tmpKey, $tmpCountry, serialize($data[$i]));
                    }
                }
            }

            if ($return) {
                return $this->redisGetAll($redis, $key);
            } else {
                var_dump('refresh data success:'.date('Y-m-d H:i:s'));
            }
        }
    }

    public static function checkNum($num, $key)
    {
        if (!in_array($key, array('country', 'country_url', 'ost_case'))) {
            $num = $num ? str_replace(',', '', $num): '';
        }
        
        return $num;
    }

    public function redisGetAll($redis, $key)
    {
        $i = 1;
        $res = $tmpArrs = array();
        while ($i) {
            $tmpKey = $key.$i;
            if ($redis->exists($tmpKey)) {
                //缓存的数据
                $rows = $redis->hGetAll($tmpKey);
                //城市
                $keys = $redis->hKeys($tmpKey);
                foreach ($keys as $country) {
                    if (isset($rows[$country]) && !in_array($country, $tmpArrs)) {
                        array_push($res, unserialize($rows[$country]));
                        // array_push($tmpArrs, $country);
                    }
                }
                $i++;
            } else {
                $i = '';
            }
        }
        if ($res) {
            $tmpCases = array_column($res, 'total_cases');
            array_multisort($tmpCases, SORT_DESC, $res);
        }

        return $res;
    }

    public function redisDel($redis, $key)
    {
        if ($redis->exists($key)) {
            $redis->del($key);
        }
    }

    public  function getCountry($country, $key = 'corona')
    {
        $res = array();
        if ($country) {
            $i = 1;
            $config = array(
                'host' => '127.0.0.1',
                'port' => '6379'
            );
            $redis = new Predis($config);
            while ($i) {
                $tmpKey = $key.$i;
                if ($redis->exists($tmpKey)) {
                    //缓存的数据
                    $res = $redis->hGet($tmpKey, $country);
                    if ($res) {
                        $res = unserialize($res);
                        $i = '';
                    } else {
                        $i++;
                    }
                } else {
                    $i = '';
                }
            }
        }
        return $res;
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