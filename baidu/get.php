<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-03 14:25:50
 */
require_once './common/phpQuery.php';
require_once './common/QueryList.php';
require_once './common/redis.php';
require_once './common/curl.php';
use QL\QueryList;

class Baidu
{
    protected $key = 'baidu';
    private $url = 'https://voice.baidu.com/act/newpneumonia/newpneumonia/?from=osari_pc_1';
    private $realUrl = 'https://opendata.baidu.com/data/inner?tn=reserved_all_res_tn&dspName=iphone&from_sf=1&dsp=iphone&resource_id=28565&alr=1&query=%s&cb=jsonp_%s_39356';
    private $hotwordUrl = 'https://opendata.baidu.com/api.php?query=%s&resource_id=39258&tn=wisetpl&format=json&sa=osari_hotword_tab&cb=jsonp_%s_69146';


    public function getData($refresh = false, $column = '')
    {
        $result = array();
        $config = array(
            'host' => '127.0.0.1',
            'port' => '6379'
        );
        $redis = new Predis($config);
        if ($refresh) {
            $redis->del($this->key);
        }
        if ($redis->exists($this->key)) {
            //指定column查询
            if ($column) {
                $result = $redis->hGet($this->key, $column);
            } else {
                $result = $this->redisGetAll($redis);
            }
        } else {
            $redis->del($this->key);
            $curl = new Curl();
            $data = $curl->curlPost($this->url);
            if ($data) {
                //正则提取json数据
                preg_match_all('/id=\"captain-config\">(.*?)<\/script>+/', $data, $tmpArr);
                $tmpArr = isset($tmpArr[1][0]) ? $tmpArr[1][0] : array();
                if ($tmpArr) {
                    //page component version bundle
                    $tmpArr = json_decode($tmpArr, true);

                    //通知公告
                    if ($tmpArr['component'][0]['trumpet']) {
                        $redis->hSet($this->key, 'trumpet', serialize($tmpArr['component'][0]['trumpet']));
                    }
                    
                    //最近更新时间
                    if ($tmpArr['component'][0]['mapLastUpdatedTime']) {
                        $redis->hSet($this->key, 'mapLastUpdatedTime', serialize($tmpArr['component'][0]['mapLastUpdatedTime']));
                    }
                    if ($tmpArr['component'][0]['foreignLastUpdatedTime']) {
                        $redis->hSet($this->key, 'foreignLastUpdatedTime', serialize($tmpArr['component'][0]['foreignLastUpdatedTime']));
                    }

                    //国内情况
                    if ($tmpArr['component'][0]['summaryDataIn']) {
                        $redis->hSet($this->key, 'summaryDataIn', serialize($tmpArr['component'][0]['summaryDataIn']));
                    }

                    //国外情况
                    if ($tmpArr['component'][0]['summaryDataOut']) {
                        $redis->hSet($this->key, 'summaryDataOut', serialize($tmpArr['component'][0]['summaryDataOut']));
                    }

                    //实时新闻
                    $redis->hSet($this->key, 'realtime_data', serialize($this->getRealtimeData(sprintf($this->realUrl, '肺炎', time()*1000))));
                    $redis->hSet($this->key, 'foreign_realtime_data', serialize($this->getRealtimeData(sprintf($this->realUrl, '新冠肺炎国外疫情', time()*1000))));

                    //全国疫情图片
                    $redis->hSet($this->key, 'mapSrc', serialize($tmpArr['component'][0]['mapSrc']));

                    //全国热搜
                    $redis->hSet($this->key, 'hotwords', serialize($this->getRealtimeData(sprintf($this->hotwordUrl, '全国', time()*1000))));
                    
                    $result = $this->redisGetAll($redis);
                } else {
                    var_dump('get json data error');
                }
            } else {
                var_dump('get baidu data error');
            }
        }

        return $result;
    }

    //是否刷新redis
    public function index($refresh = false)
    {
        return $this->getData($refresh);
    }

    //实时国内外新冠疫情新闻
    public function getRealtimeData($realUrl)
    {
        $result = array();
        if ($realUrl) {
            $curl = new Curl();
            $data = $curl->curlGet($realUrl, false);
            $data = iconv('gbk', 'utf-8', $data);

            //正则提取json数据
            preg_match_all('/(?:\{)(.*)(?:\})/i', rtrim($data, ';'), $tmpArr);
            $data = isset($tmpArr[0][0]) ? $tmpArr[0][0] : array();
            if ($data) {
                $data = json_decode($data, true);
                // $ret = json_last_error();
                if (isset($data['data'][0]['list'])) {
                    $result = $data['data'][0]['list'];
                } else {
                    $result = isset($data['Result'][0]['DisplayData']['result']['items']) ? $data['Result'][0]['DisplayData']['result']['items'] : array();
                }
            }
        }
        
        return $result;
    }

    public function redisGetAll($redis)
    {
        $rows = array();
        //缓存的数据
        if ($redis->exists($this->key)) {
            $rows = $redis->hGetAll($this->key);
            if ($rows) {
                foreach ($rows as $key => $val) {
                    if ($val) {
                        $rows[$key] = unserialize($val);
                    }
                }
            } else {
                $this->getData();
            }
        }

        return $rows;
    }

    //全国迁徙城市热门
    function migration()
    {
        //https://qianxi.baidu.com/?from=shoubai#city=0
        // 更新至2020.3.31
       // https://huiyan.baidu.com/openapi/v1/migration/rank?type=move&ak=kgD2HiDnLdUhwzd3CLuG5AWNfX3fhLYe&adminType=country&name=%E5%85%A8%E5%9B%BD
    }


    public function redisDel($redis, $key)
    {
        if ($redis->exists($key)) {
            $redis->del($key);
        }
    }
}