<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-17 16:12:22
 */
require_once './common/redis.php';
require_once './common/config.php';

use QL\QueryList;

class DataInfo
{
    //首页列表数据
    public function indexData($redis, $keyName)
    {
        $res = array();
        $res = $redis->hGetAll($keyName);
        if ($res) {
            foreach ($res as $key => &$val) {
                $val = unserialize($val);
            }
            $res = array_values($res);
            if ($res) {
                $tmpCases = array_column($res, 'total_cases');
                array_multisort($tmpCases, SORT_DESC, $res);
            }
        }

        return $res;
    }

    public function CoronaInfo($keyName, $field)
    {
        $res = array();
        $config = (new Config())->redisConfig();
        $redis = new Predis($config);
        if ($field) {
            $res = $redis->hget($keyName, $field);
            $res = unserialize($res);
        } else {
            $res = $redis->hGetAll($keyName);
            if ($res) {
                foreach ($res as $key => &$val) {
                    $val = unserialize($val);
                }
                $res = array_values($res);
                $tmpCases = array_column($res, 'total_cases');
                array_multisort($tmpCases, SORT_DESC, $res);
            }
        }

        return $res;
    }




    public function index($name = '')
    {
        if ($name) {
            $config = array(
                'host' => '127.0.0.1',
                'port' => '6379'
            );
            $redis = new Predis($config);
            if ($redis->exists($name)) {
                $data = $redis->hGetAll($name);
            } else {
                // country/italy/
                $data = $this->getCountryData('country/'.$name.'/', 1);
            }
			return $data;
		} else {
			$rows = $this->getRedisData();
			if ($rows) {
				$newArr = array_flip(array_combine(array_column($rows, 'country_url'), array_column($rows, 'country')));
				foreach ($newArr as $country => $val) {
					if ($val) {
						$this->getCountryData($val);
						var_dump('refresh '.$val.' success:'.date('Y-m-d H:i:s'));
						sleep(1);
					}
				}
			}
		}
    }

    // public function getRedisData()
    // {
    //     $config = array(
    //         'host' => '127.0.0.1',
    //         'port' => '6379'
    //     );
    //     $redis = new Predis($config);
    //     // $redis->flushDB();
    //     $key = 'corona';
    //     $corona = new Corona();
    //     if ($redis->exists('corona1')) {
    //         $data = $corona->redisGetAll($redis, $key);
    //     } else {
    //         $data = $corona->index(1);
    //     }

    //     return $data;
    // }

    // public function getCountryData($uri, $return = false)
    // {
    //     $url = 'https://www.worldometers.info/coronavirus/'.$uri;
    //     $rules = array(
    //         'news_date' => array('.news_date h4', 'text'),
    //         'cont' => array('.news_ul', 'html')
    //     );
    //     $hj = QueryList::Query($url, $rules, '#news_block');
    //     $data = $hj->data;

    //     if (isset($data[0])) {
    //         //拆分日期
    //         $tmpDateArr = array_values(array_filter(explode('(GMT)', $data[0]['news_date'])));
    //         $count = count($tmpDateArr);
    //         if ($count) {
    //         	$tmpK = explode('/', $uri);
				// $uri = $tmpK[1];

    //             //拆分内容
    //             $tmpCont = str_replace('<img alt="alert" src="/images/alert.png" style="width: 16px;">', '', $data[0]['cont']);
    //             $tmpContArr = array_values(array_filter(explode('<li class="news_li">', $tmpCont)));
    //             $config = array(
    //                 'host' => '127.0.0.1',
    //                 'port' => '6379'
    //             );
    //             $redis = new Predis($config);
    //             $this->redisDel($redis, $uri);
    //             // print_r($tmpContArr);
    //             for ($i=0; $i <= $count; $i++) {
    //                 if (isset($tmpDateArr[$i])) {
    //                     $redis->hSet($uri, $tmpDateArr[$i], rtrim($tmpContArr[$i], '</li>'));
    //                 }
    //             }
                
    //         }
    //     }

    //     if ($return) {
    //         return $redis->hGetAll($uri);
    //     }
    // }

    


    // public  function getCountry($country, $key = 'corona')
    // {
    //     $res = array();
    //     if ($country) {
    //         $i = 1;
    //         $config = array(
    //             'host' => '127.0.0.1',
    //             'port' => '6379'
    //         );
    //         $redis = new Predis($config);
    //         while ($i) {
    //             $tmpKey = $key.$i;
    //             if ($redis->exists($tmpKey)) {
    //                 //缓存的数据
    //                 $res = $redis->hGet($tmpKey, $country);
    //                 if ($res) {
    //                     $res = unserialize($res);
    //                     $i = '';
    //                 } else {
    //                     $i++;
    //                 }
    //             } else {
    //                 $i = '';
    //             }
    //         }
    //     }
    //     return $res;
    // }


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

    public function redisDel($redis, $key)
    {
        if ($redis->exists($key)) {
            $redis->del($key);
        }
    }
}