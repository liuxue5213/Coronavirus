<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-01 17:47:41
 */
require_once '../common/QueryList.php';
require_once '../common/phpQuery.php';
require_once '../common/redis.php';

// require 'QueryList/src/QueryList.php';
// require 'vendor/autoload.php';
use QL\QueryList;
// composer require jaeger/querylist:V3.2.1

class Corona {
    public function index($return = false)
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
            'ost_case' => ['td:eq(10)', 'text'],
            'callback' => array('Corona', 'checkNum')
        );
        $rang = '#nav-tabContent tbody tr';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;

        $count = count($data);
        if ($count) {
            $config = array(
                'host' => '127.0.0.1',
                'port' => '6379'
            );
            $redis = new Predis($config);
            $key = 'corona';
            $j = 0;
            for ($i = 0; $i <= $count; $i++) {
                if ($i % 100 == 0) {
                    $j++;
                    $tmpKey = $key.$j;
                    $this->redisDel($redis, $tmpKey);
                }
                if (isset($data[$i]['country']) && $data[$i]['country'] != 'Total:') {
                	if (isset($data[$i]['country_url']) && $data[$i]['country_url']) {
                		$tmpK = explode('/', $data[$i]['country_url']);
						$data[$i]['name'] = isset($tmpK[1]) ? $tmpK[1] : '';
					}
                    $redis->hSet($tmpKey, $data[$i]['country'], serialize($data[$i]));
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
                        array_push($tmpArrs, $country);
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