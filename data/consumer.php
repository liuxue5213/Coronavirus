<?php
require_once './common/Common.php';
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
            $config = (new Common())->redisConfig();
            $redis = new Predis($config);
            foreach ($rows['data'] as $key => $val) {
                $tmpKey = isset($val['tmp_key']) && $val['tmp_key'] ? $val['tmp_key'] : '';
                $redis->hSet($rows['name'], $tmpKey, serialize($val));
            }
        }
        $envelopeID = $envelope->getDeliveryTag();
        var_dump($envelopeID);
        // $pid = posix_getpid();
        // file_put_contents("log{$pid}.log", $msg.'|'.$envelopeID.''."\r\n",FILE_APPEND);
        $queue->ack($envelopeID);
    }

    // public function countryInfo()
    // {
    //     $count = count($data);
    //     if ($count) {
    //         $key = 'corona';
    //         $j = 0;

    //         for ($i = 0; $i <= $count; $i++) {
    //             $tmpKey = '';
    //             if ($i % 100 == 0) {
    //                 $j++;
    //                 $tmpKey = $key.$j;
    //                 $this->redisDel($redis, $tmpKey);
    //             }
    //             $tmpCountry = isset($data[$i]['country']) && $data[$i]['country'] ? $data[$i]['country'] : '';
    //             //过滤出世界汇总的数据 2020-04-12
    //             if (in_array($tmpCountry, array('Total:','World', 'Europe', 'North America', 'Asia', 'South America', 'Africa', 'Oceania', 'Diamond Princess', 'MS Zaandam'))) {
    //                 $redis->hSet('world', $tmpCountry, serialize($data[$i]));
    //             } else {
    //                 if (isset($data[$i]['country_url']) && $data[$i]['country_url']) {
    //                     $tmpK = explode('/', $data[$i]['country_url']);
    //                     $data[$i]['name'] = isset($tmpK[1]) ? $tmpK[1] : '';
    //                 }
    //                 if (isset($data[$i])) {
    //                     $redis->hSet($tmpKey, $tmpCountry, serialize($data[$i]));
    //                 }
    //             }
    //         }

    //         return true;
    //     }
    // }
}