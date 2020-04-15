<?php

require_once './common/QueryList.php';
require_once './common/phpQuery.php';
require_once './common/config.php';
require_once './common/RabbitMQCommand.php';
require_once './common/redis.php';

use QL\QueryList;

/**
 * 消费
 */
class Consumer
{
    public function processMessage($envelope, $queue) {
        $msg = $envelope->getBody();
        $rows = unserialize($msg);
        if ($rows) {
            $config = (new Config())->redisConfig();
            $redis = new Predis($config);
            // foreach ($rows as $key => $val) {
                

            // }
            echo "<pre>";
            print_r($rows);
            echo "</pre>";
            die;

        }

        
        // $envelopeID = $envelope->getDeliveryTag();
        // $pid = posix_getpid();


        // file_put_contents("log{$pid}.log", $msg.'|'.$envelopeID.''."\r\n",FILE_APPEND);

        $queue->ack($envelopeID);
    }


    public function countryInfo()
    {
        
        $count = count($data);
        if ($count) {
            $key = 'corona';
            $j = 0;

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

            return true;
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

    
}