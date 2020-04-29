<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
}
require_once BASE_PATH.'./common/QueryList.php';
require_once BASE_PATH.'./common/phpQuery.php';
require_once BASE_PATH.'./common/common.php';
require_once BASE_PATH.'./common/RabbitMQCommand.php';

use QL\QueryList;

/**
 * 生产
 */
class Producer
{
    //调整数字格式
    public static function checkNum($num, $key)
    {
        if (!in_array($key, array('country', 'country_url', 'ost_case'))) {
            $num = $num ? str_replace(',', '', $num): '';
        }
        
        return $num;
    }

    //coronaInfo
    public function worldInfo()
    {
        $url = 'https://www.worldometers.info/coronavirus';
        $rules = array(
            'last_updated' => ['.content-inner div:eq(1)', 'text'],
            'arr' => ['#maincounter-wrap .maincounter-number', 'text']
        );
        $rang = '.container .col-md-8';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;

        if (isset($data[0])) {
            $rabbitConfig = (new CommonConfig())->rabbitConfig();
            $exchange_name = 'ex_corona';
            $queue_name = 'q_corona';
            $route_key = 'key_corona';
            $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);

            $tmpArr = array_values(array_filter(explode("\n", $data[0]['arr'])));
            $sRows['name'] = 'coronaInfo';
            $sRows['data'][0]['tmp_key'] = 'title';
            $sRows['data'][0]['last_updated'] = str_replace('Last updated:', '', $data[0]['last_updated']).'(格林尼治标准时间)';
            $sRows['data'][0]['cases'] = $tmpArr[0];
            $sRows['data'][0]['deaths'] = $tmpArr[1];
            $sRows['data'][0]['recovered'] = $tmpArr[2];
            $ra->send(serialize($sRows));
        }

        return true;
    }

    public function countryInfo()
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
            'tot_test' => ['td:eq(10)', 'text'],
            'tot_test_1m' => ['td:eq(11)', 'text'],
            'callback' => array('Producer', 'checkNum')
        );
        $rang = '#nav-tabContent tbody tr';
        $hj = QueryList::Query($url, $rules, $rang);
        $data = $hj->data;
        
        if ($data) {
            $rabbitConfig = (new CommonConfig())->rabbitConfig();
            $exchange_name = 'ex_corona';
            $queue_name = 'q_corona';
            $route_key = 'key_corona';
            $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);

            $country = $world = $tmpArr = array();
            foreach ($data as $key => $val) {
                $tmpCountry = isset($val['country']) && $val['country'] ? $val['country'] : '';
                if (!$tmpCountry) {
                    continue;
                }
                $val['tmp_key'] = $tmpCountry;
                //过滤出世界汇总的数据 2020-04-12
                if (in_array($tmpCountry, array('Total:','World', 'Europe', 'North America', 'Asia', 'South America', 'Africa', 'Oceania', 'Diamond Princess', 'MS Zaandam'))) {
                    $world['data'][] = $val;
                } else {
                    //城市
                    $country['data'][0]['tmp_key'] = 'country';
                    $country['data'][0][] = $tmpCountry;

                    if (isset($val['country_url']) && $val['country_url']) {
                        $tmpK = explode('/', $val['country_url']);
                        $val['name'] = isset($tmpK[1]) ? $tmpK[1] : '';

                        //详情
                        if ($val['name']) {
                            // $detail = $this->detail($val['name']);
                            // if ($detail) {
                            //     $ra->send(serialize($detail));
                            // }
                        }
                    }
                    $tmpArr['data'][] = $val;
                }
                var_dump($tmpCountry.' '.date('Y-m-d H:i:s'));
            }

            //世界信息
            if (count($world['data'])) {
                $world['name'] = 'world';
                $ra->send(serialize($world));
            }

            //城市列表
            if (count($country['data'])) {
                $country['name'] = 'coronaInfo';
                $ra->send(serialize($country));
            }

            //所有城市数据
            if (count($tmpArr['data'])) {
                $tmpArr['name'] = 'corona';
                $ra->send(serialize($tmpArr));
            }
        }

        return true;
    }

    public function detail($tmpKey)
    {
        $res = array();
        $url = 'https://www.worldometers.info/coronavirus/country/'.ltrim($tmpKey, 'country/');
        $rules = array(
            'news_date' => array('.news_date h4', 'text'),
            'btn_date' => array('.date-btn', 'text'),
            'cont' => array('.news_ul', 'html')
        );
        $hj = QueryList::Query($url, $rules, '#news_block');
        $data = $hj->data;

        if (isset($data[0])) {
            //起始日期
            $tmpDateArr[] = $data[0]['news_date'];
            $tmpArr = explode(' ', $data[0]['btn_date']);
            for ($i=0; $i < count($tmpArr); $i+=2) {
                if (isset($tmpArr[$i]) && isset($tmpArr[$i+1])) {
                    $tmpDateArr[] = $tmpArr[$i].' '.$tmpArr[$i+1];
                }
            }

            //拆分内容
            $tmpCont = str_replace('<img alt="alert" src="/images/alert.png" style="width: 16px;">', '', $data[0]['cont']);
            $tmpContArr = array_values(array_filter(explode('<li class="news_li">', $tmpCont)));
            
            for ($i=0; $i <= count($tmpDateArr); $i++) {
                if (!empty($tmpContArr[$i]) && !empty($tmpDateArr[$i])) {
                    $res['name'] = $tmpKey;
                    $res['data'][$i]['tmp_key'] = $tmpDateArr[$i];
                    $res['data'][$i]['content'] = rtrim($tmpContArr[$i], '</li>');
                }
            }
        }

        return $res;
    }

    

    // public function countryInfo2()
    // {
    //     $url = 'https://www.worldometers.info/coronavirus';
    //     $rules = array(
    //         'country' => ['td:eq(0)', 'text'],
    //         'country_url' => ['td:eq(0)>a', 'href', 'text'],
    //         'total_cases' => ['td:eq(1)', 'text'],
    //         'new_cases' => ['td:eq(2)', 'text'],
    //         'total_deaths' => ['td:eq(3)', 'text'],
    //         'new_deaths' => ['td:eq(4)', 'text'],
    //         'total_recovered' => ['td:eq(5)', 'text'],
    //         'active_cases' => ['td:eq(6)', 'text'],
    //         'serious_critical' => ['td:eq(7)', 'text'],
    //         'tot_cases_1m' => ['td:eq(8)', 'text'],
    //         'tot_deaths_1m' => ['td:eq(9)', 'text'],
    //         'tot_test' => ['td:eq(10)', 'text'],
    //         'tot_test_1m' => ['td:eq(11)', 'text'],
    //         'callback' => array('Producer', 'checkNum')
    //     );
    //     $rang = '#nav-tabContent tbody tr';
    //     $hj = QueryList::Query($url, $rules, $rang);
    //     $data = $hj->data;

    //     $table = QueryList::html($html)->find('table');
    //     // 采集表头
    //     $tableHeader = $table->find('tr:eq(0)')->find('td')->texts();
    //     // 采集表的每行内容
    //     $tableRows = $table->find('tr:gt(0)')->map(function($row){
    //         return $row->find('td')->texts()->all();
    //     });
    //     print_r($tableHeader->all());
    //     print_r($tableRows->all());
    //     // 
        
    //     if ($data) {
    //         $rabbitConfig = (new Config())->rabbitConfig();
    //         $exchange_name = 'ex_corona';
    //         $queue_name = 'q_corona';
    //         $route_key = 'key_corona';
    //         $ra = new RabbitMQCommand($rabbitConfig, $exchange_name, $queue_name, $route_key);

    //         $country = $world = $tmpArr = array();
    //         foreach ($data as $key => $val) {
    //             $tmpCountry = isset($val['country']) && $val['country'] ? $val['country'] : '';
    //             if (!$tmpCountry) {
    //                 continue;
    //             }
    //             $val['tmp_key'] = $tmpCountry;
    //             //过滤出世界汇总的数据 2020-04-12
    //             if (in_array($tmpCountry, array('Total:','World', 'Europe', 'North America', 'Asia', 'South America', 'Africa', 'Oceania', 'Diamond Princess', 'MS Zaandam'))) {
    //                 $world['data'][] = $val;
    //             } else {
    //                 //城市
    //                 $country['data'][0]['tmp_key'] = 'country';
    //                 $country['data'][0][] = $tmpCountry;

    //                 if (isset($val['country_url']) && $val['country_url']) {
    //                     $tmpK = explode('/', $val['country_url']);
    //                     $val['name'] = isset($tmpK[1]) ? $tmpK[1] : '';
    //                 }
    //                 $tmpArr['data'][] = $val;
    //             }
    //             var_dump($tmpCountry.' '.date('Y-m-d H:i:s'));
    //         }

    //         //世界信息
    //         if (count($world['data'])) {
    //             $world['name'] = 'world';
    //             $ra->send(serialize($world));
    //         }

    //         //城市列表
    //         if (count($country['data'])) {
    //             $country['name'] = 'coronaInfo';
    //             $ra->send(serialize($country));
    //         }

    //         //所有城市数据
    //         if (count($tmpArr['data'])) {
    //             $tmpArr['name'] = 'corona';
    //             $ra->send(serialize($tmpArr));
    //         }
    //     }

    //     return true;
    // }
}