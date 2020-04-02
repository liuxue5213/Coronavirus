<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-02 18:23:29
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

    public function getData($column = '')
    {
        $result = array();
        $config = array(
            'host' => '127.0.0.1',
            'port' => '6379'
        );
        $redis = new Predis($config);
        if ($redis->exists($this->key)) {
            //指定column查询
            if ($column) {
                $result = $redis->hGet($this->key, $column);
            } else {
                $result = $this->redisGetAll($redis);
            }
        } else {
            $curl = new Curl();
            $data = $curl->curlPost($this->url);
            if ($data) {
                //正则提取json数据
                preg_match_all('/id=\"captain-config\">(.*?)<\/script>+/', $data, $tmpArr);
                $tmpArr = isset($tmpArr[1][0]) ? $tmpArr[1][0] : array();
                if ($tmpArr) {
                    //page component version bundle
                    $tmpArr = json_decode($tmpArr, true);

                    $redis->del($this->key);

                    //通知公告
                    if ($tmpArr['component'][0]['trumpet']) {
                        $redis->hSet($this->key, 'trumpet', serialize($tmpArr['component'][0]['trumpet']));
                    }
                    // $result['trumpet'] = isset($tmpArr['component'][0]['trumpet']) ? $tmpArr['component'][0]['trumpet'] : '';
                    
                    //最近更新时间
                    if ($tmpArr['component'][0]['mapLastUpdatedTime']) {
                        $redis->hSet($this->key, 'mapLastUpdatedTime', serialize($tmpArr['component'][0]['mapLastUpdatedTime']));
                    }
                    if ($tmpArr['component'][0]['foreignLastUpdatedTime']) {
                        $redis->hSet($this->key, 'foreignLastUpdatedTime', serialize($tmpArr['component'][0]['foreignLastUpdatedTime']));
                    }
                    // $result['mapLastUpdatedTime'] = isset($tmpArr['component'][0]['mapLastUpdatedTime']) ? $tmpArr['component'][0]['mapLastUpdatedTime'] : '';
                    // $result['foreignLastUpdatedTime'] = isset($tmpArr['component'][0]['foreignLastUpdatedTime']) ? $tmpArr['component'][0]['foreignLastUpdatedTime'] : '';

                    //国内情况
                    if ($tmpArr['component'][0]['summaryDataIn']) {
                        $redis->hSet($this->key, 'summaryDataIn', serialize($tmpArr['component'][0]['summaryDataIn']));
                    }
                    // $result['summaryDataIn'] = isset($tmpArr['component'][0]['summaryDataIn']) ? $tmpArr['component'][0]['summaryDataIn'] : '';

                    //国外情况
                    if ($tmpArr['component'][0]['summaryDataOut']) {
                        $redis->hSet($this->key, 'summaryDataOut', serialize($tmpArr['component'][0]['summaryDataOut']));
                    }
                    // $result['summaryDataOut'] = isset($tmpArr['component'][0]['summaryDataOut']) ? $tmpArr['component'][0]['summaryDataOut'] : '';

                    //实时新闻
                    $redis->hSet($this->key, 'realtime_data', serialize($this->getRealtimeData(sprintf($this->realUrl, '肺炎', time()*1000))));
                    // $result['realtime_data'] = $this->getRealtimeData(sprintf($this->realUrl, '肺炎', time()*1000));
                    $redis->hSet($this->key, 'foreign_realtime_data', serialize($this->getRealtimeData(sprintf($this->realUrl, '新冠肺炎国外疫情', time()*1000))));
                    // $result['foreign_realtime_data'] = $this->getRealtimeData(sprintf($this->realUrl, '新冠肺炎国外疫情', time()*1000));

                    //mapSrc  https://mms-res.cdn.bcebos.com/mms-res/voicefe/captain/images/179c88c21e03aa351b8be66eed098e5f.png?size=1050*803
                    $redis->hSet($this->key, 'mapSrc', serialize($tmpArr['component'][0]['mapSrc']));
                    
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

    public function index()
    {
        return $this->getData();
    }

    //实时国内外新冠疫情新闻
    public function getRealtimeData($realUrl)
    {
        $result = array();
        if ($realUrl) {
            $curl = new Curl();
            $data = $curl->curlGet($realUrl, false);
            //正则提取json数据
            preg_match_all('/(?:\{)(.*)(?:\})/i', $data, $tmpArr);
            $data = isset($tmpArr[0][0]) ? $tmpArr[0][0] : array();
            if ($data) {
                $data = json_decode($data, true);
                $result = isset($data['Result'][0]['DisplayData']['result']['items']) ? $data['Result'][0]['DisplayData']['result']['items'] : array();
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