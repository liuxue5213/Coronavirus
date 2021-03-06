<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
}
include_once BASE_PATH.'./common/redis.php';
include_once BASE_PATH.'./common/common.php';

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

    //查询缓存的数据
    public function CoronaInfo($keyName, $field = '', $isSort = 'total_cases')
    {
        $res = array();
        $config = (new CommonConfig())->redisConfig();
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
                if ($isSort) {
                    $tmpCases = array_column($res, $isSort);
                    array_multisort($tmpCases, SORT_DESC, $res);
                }
            }
        }

        return $res;
    }

    public function redisDel($redis, $key)
    {
        if ($redis->exists($key)) {
            $redis->del($key);
        }
    }
}