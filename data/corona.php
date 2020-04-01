<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-01 10:52:15
 */
require_once '../common/QueryList.php';
require_once '../common/phpQuery.php';
require_once '../common/redis.php';

use QL\QueryList;

class CoronaInfo {
    public function index($return = false)
    {
        $tmpKey = 'coronaInfo';
        $url = 'https://www.worldometers.info/coronavirus';
        $rules = array(
            'last_updated' => ['.content-inner div:eq(1)', 'text'],
            'arr' => ['#maincounter-wrap .maincounter-number', 'text']
        );
        // $rang = '#maincounter-wrap .maincounter-number';
        $rang = '.container .col-md-8';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;

        if (isset($data[0])) {
            $tmpArr = array_values(array_filter(explode("\n", $data[0]['arr'])));
            $config = array(
                'host' => '127.0.0.1',
                'port' => '6379'
            );
            $redis = new Predis($config);
            $this->redisDel($redis, $tmpKey);
            $redis->hSet($tmpKey, 'last_updated', str_replace('Last updated:', '', $data[0]['last_updated']).'(格林尼治标准时间)');
            $redis->hSet($tmpKey, 'cases', $tmpArr[0]);
            $redis->hSet($tmpKey, 'deaths', $tmpArr[1]);
            $redis->hSet($tmpKey, 'recovered', $tmpArr[2]);
        }

        if ($return) {
            return $redis->hGetAll($tmpKey);
        } else {
            var_dump('refresh info success:'.date('Y-m-d H:i:s'));
        }
    }

    public function redisDel($redis, $key)
    {
        if ($redis->exists($key)) {
            $redis->del($key);
        }
    }
}