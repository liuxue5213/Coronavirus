<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 17:06:30
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-01 09:11:11
 */
require_once './common/phpQuery.php';
require_once './common/QueryList.php';
require_once './common/redis.php';
require_once './common/curl.php';
use QL\QueryList;

//https://mms-res.cdn.bcebos.com/voicefe/captain/images/be3a8f01a533fc60dcb457d60fda3fec479281d3?160*50 logo white
//https://mms-res.cdn.bcebos.com/voicefe/captain/images/1b9ddd53f65d1b3a4faeca959e15d425c8d85d2f?117*38  red-blue logo
class Baidu
{
    public function index($return = false)
    {
        $result = array();
        $url = 'https://voice.baidu.com/act/newpneumonia/newpneumonia/?from=osari_pc_1';
        $curl = new Curl();
        $data = $curl->curl_post($url);

        //正则提取json数据
        // $data = 1;
        if ($data) {
            preg_match_all('/id=\"captain-config\">(.*?)<\/script>+/', $data, $tmpArr);
            $tmpArr = isset($tmpArr[1][0]) ? $tmpArr[1][0] : array();
            // $config = array(
            //     'host' => '127.0.0.1',
            //     'port' => '6379'
            // );
            // $redis = new Predis($config);
            // $redis->set('baidu', $tmpArr[1][0]);
            // $tmpArr = $redis->get('baidu');
            // die;
            if ($tmpArr) {
                //page component version bundle
                $tmpArr = json_decode($tmpArr, true);
                //通知公告
                $result['trumpet'] = isset($tmpArr['component'][0]['trumpet']) ? $tmpArr['component'][0]['trumpet'] : '';
                //最近更新时间
                $result['mapLastUpdatedTime'] = isset($tmpArr['component'][0]['mapLastUpdatedTime']) ? $tmpArr['component'][0]['mapLastUpdatedTime'] : '';
                
                // print_r(array_keys($tmpArr['component'][0]));
                // print_r($tmpArr['component'][0]);
                // die;
                // print_r($tmpArr['component'][0]['mapLastUpdatedTime']);

                //hotwords
// [type] => 0
// [query] => 顺丰回应截获口罩
// [url] => https://m.baidu.com/s?word=顺丰回应截获口罩&sa=osari_hotword
// [degree] => 57100

                // summaryDataIn
// [confirmed] => 82691
// [died] => 3321
// [cured] => 76440
// [asymptomatic] => 1367
// [asymptomaticRelative] => 130
// [unconfirmed] => 172
// [relativeTime] => 1585584000
// [confirmedRelative] => 86
// [unconfirmedRelative] => 26
// [curedRelative] => 190
// [diedRelative] => 7
// [icu] => 466
// [icuRelative] => -62
// [overseasInput] => 806
// [unOverseasInputCumulative] => 81825
// [overseasInputRelative] => 35
// [unOverseasInputNewAdd] => 51
// [curConfirm] => 2930
// [curConfirmRelative] => -111
// [icuDisable] => 1

                // summaryDataOut
// [confirmed] => 803333
// [died] => 41014
// [curConfirm] => 652370
// [cured] => 109949
// [confirmedRelative] => 57568
// [curedRelative] => 10019
// [diedRelative] => 3295
// [curConfirmRelative] => 44254
// [relativeTime] => 1585584000


//knowledges
// [query] => 新型肺炎自查手册
// [type] => 0
// [degree] => 3420900
// [url] => https://m.baidu.com/s?word=新型肺炎自查手册&sa=osari_fangyi

//gossips
// [query] => 超市买的东西必须消毒
// [type] => 7
// [url] => https://m.baidu.com/s?word=超市买的东西必须消毒&sa=osari_yaoyan
// [degree] => 7224

//foreignLastUpdatedTime
//mapSrc
//https://mms-res.cdn.bcebos.com/mms-res/voicefe/captain/images/179c88c21e03aa351b8be66eed098e5f.png?size=1050*803     

//cooperation           
//pcCooperation
//kingData                
            }
        }
        return $result;
            // $config = array(
            //     'host' => '127.0.0.1',
            //     'port' => '6379'
            // );
            // $redis = new Predis($config);
            // $this->redisDel($redis, $tmpKey);
            // $redis->hSet($tmpKey, $data[$i]['country'], serialize($data[$i]));
            // return $this->redisGetAll($redis, $key);
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