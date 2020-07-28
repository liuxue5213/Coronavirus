<?php
/**
 * @Author: anchen
 * @Date:   2020-07-21 14:29:11
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-07-22 10:42:51
 */
require_once './common/phpQuery.php';
require_once './common/QueryList.php';
require_once './common/curl.php';
// require_once BASE_PATH.'./common/common.php';
// require_once BASE_PATH.'./common/logger.php';
// require_once BASE_PATH.'./common/RabbitMQCommand.php';

use QL\QueryList;

// $url = 'https://ks.wjx.top/jq/%s.aspx';

// $i = 85305285;
// for ($i = 85305285; $i <= 99999999; $i++) {
//     $url = sprintf($url, $i);
//     $tmp = file_get_contents($url);
//     print_r($tmp);
//     die;
// }

$url = 'https://ks.wjx.top/jq/85916726.aspx';
$rules = array(
    // 'last_updated' => ['.div_title_question div:eq(1)', 'text'],
    'title' => ['.div_title_question', 'text'],
    // 'title2' => ['.div_title_question_all', 'text'],
    // 'arr' => ['#maincounter-wrap .maincounter-number', 'text']
);
$rang = '#ctl00_ContentPlaceHolder1_JQ1_surveyContent .div_question .div_title_question_all';
$data = QueryList::Query($url, $rules, $rang,'ISO-8859-1','UTF-8')->data;
//$data = QueryList::Query($url, $rules, $rang)->data;
if ($data) {
    $rows = file_get_contents('C:\Users\Administrator\Desktop\Coronavirus\ci.md');
	// print_r($rows);
	// die;
    foreach ($data as $key => $val) {
        $tit = preg_replace("/[0-9]/", "", $val['title']);
        $tit = ltrim($tit, '.');
        $tit = rtrim($tit, '。');
        
//		$val['title'] = mb_convert_encoding($val['title'],'utf-8','gbk');
        // $tit = iconv('gb2312', 'utf8', $val['title']);
        print_r($tit);
        die;
    }
}


