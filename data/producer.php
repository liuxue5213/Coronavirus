<?php

require_once './common/QueryList.php';
require_once './common/phpQuery.php';
require_once './common/config.php';
require_once './common/RabbitMQCommand.php';
require_once './common/redis.php';

use QL\QueryList;

/**
 * 生产
 */
class Producer
{
    //调整数字格式
    public static function checkNum($num, $key)
    {
        if (!in_array($key, array('country', 'country_url', 'ost_case'))) {
            $num = $num ? str_replace(',', '', $num): '';
        }
        
        return $num;
    }

    //coronaInfo
    public function worldInfo()
    {
        $url = 'https://www.worldometers.info/coronavirus';
        $rules = array(
            'last_updated' => ['.content-inner div:eq(1)', 'text'],
            'arr' => ['#maincounter-wrap .maincounter-number', 'text']
        );
        $rang = '.container .col-md-8';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;

        if (isset($data[0])) {
            $rabbitConfig = (new Config())->rabbitConfig();
            $exchange_name = 'ex_corona';
            $queue_name = 'q_corona';
            $route_key = 'key_corona';
            $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);

            $tmpArr = array_values(array_filter(explode("\n", $data[0]['arr'])));
            $sRows['name'] = 'coronaInfo';
            $sRows['data'][0]['last_updated'] = str_replace('Last updated:', '', $data[0]['last_updated']).'(格林尼治标准时间)';
            $sRows['data'][0]['cases'] = $tmpArr[0];
            $sRows['data'][0]['deaths'] = $tmpArr[1];
            $sRows['data'][0]['recovered'] = $tmpArr[2];
            $ra->send(serialize($sRows));
        }

        return true;
    }

    public function countryInfo()
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
            'callback' => array('Producer', 'checkNum')
        );
        $rang = '#nav-tabContent tbody tr';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;
        
        if ($data) {
            $rabbitConfig = (new Config())->rabbitConfig();
            $exchange_name = 'ex_corona';
            $queue_name = 'q_corona';
            $route_key = 'key_corona';
            $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);

            $country = $world = $tmpArr = array();
            foreach ($data as $key => $val) {
                $tmpCountry = isset($val['country']) && $val['country'] ? $val['country'] : '';
                if (!$tmpCountry) {
                    continue;
                }
                //过滤出世界汇总的数据 2020-04-12
                if (in_array($tmpCountry, array('Total:','World', 'Europe', 'North America', 'Asia', 'South America', 'Africa', 'Oceania', 'Diamond Princess', 'MS Zaandam'))) {
                    $world['data'][] = $val;
                } else {
                    //城市
                    $country['data'][] = $tmpCountry;

                    if (isset($val['country_url']) && $val['country_url']) {
                        $tmpK = explode('/', $val['country_url']);
                        $val['name'] = isset($tmpK[1]) ? $tmpK[1] : '';

                        //详情
                        // if ($val['name']) {
                        //     $detail = $this->detail($val['name']);
                        //     if ($detail) {
                        //         $ra->send(serialize($detail));
                        //         var_dump('refresh detail '.$tmpCountry.' success:'.date('Y-m-d H:i:s'));
                        //     }
                        // }
                    }
                    $tmpArr['data'][] = $val;
                }
                var_dump('refresh '.$tmpCountry.' success:'.date('Y-m-d H:i:s'));
            }

            //世界信息
            if (count($world['data'])) {
                $world['name'] = 'world';
                $ra->send(serialize($world));
            }

            //城市列表
            if (count($country['data'])) {
                $country['name'] = 'country';
                $ra->send(serialize($country));
            }

            //所有城市数据
            if (count($tmpArr['data'])) {
                $tmpArr['name'] = 'corona';
                $ra->send(serialize($tmpArr));
            }
        }

        return true;
    }

    public function detail($tmpKey)
    {
        $res = array();
        $url = 'https://www.worldometers.info/coronavirus/country/'.$tmpKey;
        $rules = array(
            'news_date' => array('.news_date h4', 'text'),
            'cont' => array('.news_ul', 'html')
        );
        $hj = QueryList::Query($url, $rules, '#news_block');
        $data = $hj->data;

        if (isset($data[0])) {
            //拆分日期
            $tmpDateArr = array_values(array_filter(explode('(GMT)', $data[0]['news_date'])));
            $count = count($tmpDateArr);

            if ($count) {
                //拆分内容
                $tmpCont = str_replace('<img alt="alert" src="/images/alert.png" style="width: 16px;">', '', $data[0]['cont']);
                $tmpContArr = array_values(array_filter(explode('<li class="news_li">', $tmpCont)));
                // print_r($tmpContArr);
                for ($i=0; $i <= $count; $i++) {
                    if (isset($tmpDateArr[$i])) {
                        $res['name'] = $tmpKey;
                        $res['data'][][$tmpDateArr[$i]] = rtrim($tmpContArr[$i], '</li>');
                    }
                }
                
            }
        }

        return $res;
    }


    // public function detail22222($name = '')
    // {
    //     $rows = $this->getRedisData();
    //     if ($rows) {
    //         $newArr = array_flip(array_combine(array_column($rows, 'country_url'), array_column($rows, 'country')));
    //         foreach ($newArr as $country => $val) {
    //             if ($val) {
    //                 $this->getCountryData($val);
    //                 var_dump('refresh '.$val.' success:'.date('Y-m-d H:i:s'));
    //                 sleep(1);
    //             }
    //         }
    //     }
    // }




    // public function ttt() {
    //     $conn_args = array(
    //         'host'=>'127.0.0.1',
    //         'port'=>5672,
    //         'login'=>'test',
    //         'password'=>'123456',
    //         'vhost'=>'/'
    //     );
    //     $e_name = 'e_demo';
    //     $q_name = 'q_demo';
    //     $k_route = 'key_1';
    //     $conn = new AMQPConnection($conn_args);
    //     if(!$conn->connect()){
    //         die('Cannot connect to the broker');
    //     }
    //     $channel = new AMQPChannel($conn);
    //     $ex = new AMQPExchange($channel);
    //     $ex->setName($e_name);
    //     $ex->setType(AMQP_EX_TYPE_DIRECT);
    //     $ex->setFlags(AMQP_DURABLE);
    //     $q = new AMQPQueue($channel);
    //     // var_dump($q);
    //     $q->setName($q_name);
    //     $q->bind($e_name, $k_route);

    //     $arr = $q->get(AMQP_AUTOACK);
    //     $res = $q->ack($arr->getDeliveryTag());
    //     // $msg = $arr->getBody();
    //     var_dump($arr);
    //     $conn->disconnect();
    // }
}