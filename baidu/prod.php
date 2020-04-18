<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-18 14:34:49
 */
require_once './common/phpQuery.php';
require_once './common/QueryList.php';
require_once './common/curl.php';
require_once './common/common.php';
require_once './common/logger.php';
require_once './common/RabbitMQCommand.php';

use QL\QueryList;

class BaiduProd
{
    protected $key = 'baidu';
    private $url = 'https://voice.baidu.com/act/newpneumonia/newpneumonia/?from=osari_pc_1';
    private $realUrl = 'https://opendata.baidu.com/data/inner?tn=reserved_all_res_tn&dspName=iphone&from_sf=1&dsp=iphone&resource_id=28565&alr=1&query=%s&cb=jsonp_%s_39356';
    private $hotwordUrl = 'https://opendata.baidu.com/api.php?query=%s&resource_id=39258&tn=wisetpl&format=json&sa=osari_hotword_tab&cb=jsonp_%s_69146';

    public function index()
    {
        $curl = new Curl();
        $data = $curl->curlPost($this->url);
        if ($data) {
            //正则提取json数据
            preg_match_all('/id=\"captain-config\">(.*?)<\/script>+/', $data, $tmpArr);
            $tmpArr = isset($tmpArr[1][0]) ? $tmpArr[1][0] : array();
            if ($tmpArr) {
                $i = 0;
                $sRows = array();
                $rabbitConfig = (new CommonConfig())->rabbitConfig();
                $exchange_name = 'ex_corona';
                $queue_name = 'q_corona';
                $route_key = 'key_corona';
                $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);
                
                //page component version bundle
                $tmpArr = json_decode($tmpArr, true);

                //通知公告
                if ($tmpArr['component'][0]['trumpet']) {
                    $sRows['data'][$i]['tmp_key'] = 'trumpet';
                    $sRows['data'][$i]['content'] = $tmpArr['component'][0]['trumpet'];
                    $i++;
                }

                //最近更新时间
                if ($tmpArr['component'][0]['mapLastUpdatedTime']) {
                    $sRows['data'][$i]['tmp_key'] = 'mapLastUpdatedTime';
                    $sRows['data'][$i]['content'] = $tmpArr['component'][0]['mapLastUpdatedTime'];
                    $i++;
                }
                if ($tmpArr['component'][0]['foreignLastUpdatedTime']) {
                    $sRows['data'][$i]['tmp_key'] = 'foreignLastUpdatedTime';
                    $sRows['data'][$i]['content'] = $tmpArr['component'][0]['foreignLastUpdatedTime'];
                    $i++;
                }

                //国内情况
                if ($tmpArr['component'][0]['summaryDataIn']) {
                    $sRows['data'][$i]['tmp_key'] = 'summaryDataIn';
                    $sRows['data'][$i]['content'] = $tmpArr['component'][0]['summaryDataIn'];
                    $i++;
                }

                //国外情况
                if ($tmpArr['component'][0]['summaryDataOut']) {
                    $sRows['data'][$i]['tmp_key'] = 'summaryDataOut';
                    $sRows['data'][$i]['content'] = $tmpArr['component'][0]['summaryDataOut'];
                    $i++;
                }

                //实时新闻
                $sRows['data'][$i]['tmp_key'] = 'realtime_data';
                $sRows['data'][$i]['content'] = $this->getRealtimeData(sprintf($this->realUrl, '肺炎', time()*1000));
                $i++;

                $sRows['data'][$i]['tmp_key'] = 'foreign_realtime_data';
                $sRows['data'][$i]['content'] = $this->getRealtimeData(sprintf($this->realUrl, '新冠肺炎国外疫情', time()*1000));
                $i++;
                
                //全国疫情图片
                $sRows['data'][$i]['tmp_key'] = 'mapSrc';
                $sRows['data'][$i]['content'] = $tmpArr['component'][0]['mapSrc'];
                $i++;

                //全国热搜
                $sRows['data'][$i]['tmp_key'] = 'hotwords';
                $sRows['data'][$i]['content'] = $this->getRealtimeData(sprintf($this->hotwordUrl, '全国', time()*1000));
                $i++;

                if (count($sRows)) {
                    $sRows['name'] = $this->key;
                    $ra->send(serialize($sRows));
                }
            } else {
                $msg = "<百度>正则提取json数据失败:".date('Y-m-d h:i:s')."\r\n";
                logger::addlog($msg);
            }
        } else {
            $msg = "<百度>curl获取数据失败:".date('Y-m-d h:i:s')."\r\n";
            logger::addlog($msg);
        }
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

    //全国迁徙城市热门
    public function migration()
    {
        //https://qianxi.baidu.com/?from=shoubai#city=0
        // 更新至2020.3.31
       // https://huiyan.baidu.com/openapi/v1/migration/rank?type=move&ak=kgD2HiDnLdUhwzd3CLuG5AWNfX3fhLYe&adminType=country&name=%E5%85%A8%E5%9B%BD
    }
}