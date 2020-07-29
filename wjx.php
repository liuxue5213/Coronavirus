<?php
require_once './common/phpQuery.php';
require_once './common/QueryList.php';
require_once './common/curl.php';
use QL\QueryList;

// $url = 'https://ks.wjx.top/jq/%s.aspx';
// $i = 85305285;
// for ($i = 85305285; $i <= 99999999; $i++) {
//     $url = sprintf($url, $i);
//     $tmp = file_get_contents($url);
//     print_r($tmp);
//     die;
// }
function check($rows, $str)
{
    $res = array();
    $tmpOne = explode('_', $str);
    $tmpOne = array_filter(array_unique($tmpOne));
    $tmpRow = array_filter(explode(PHP_EOL, $rows));

    foreach ($tmpOne as $val) {
        $arr = preg_split("/(?=[A-Za-z])/", $val);
        foreach ($arr as $v) {
            $v = preg_replace('|[a-zA-Z]+|', '', $v);
            foreach($tmpRow as $dict) {
                if ($v && strstr($dict, $v) != false) {
                    $dict = preg_replace("/[0-9]/", '', $dict);
                    array_push($res, rtrim(ltrim($dict, '.'), '。'));
                }
            }
        }
    }

    return $res;
}


$url = 'https://ks.wjx.top/jq/85916726.aspx';
$rules = array(
    // 'last_updated' => ['.div_title_question div:eq(1)', 'text'],
    'title' => ['.div_title_question', 'text'],
    // 'title2' => ['.div_title_question_all', 'text'],
);
$rang = '#ctl00_ContentPlaceHolder1_JQ1_surveyContent .div_question .div_title_question_all';
$data = QueryList::Query($url, $rules, $rang,'ISO-8859-1','UTF-8')->data;
// print_r($data);die;

if ($data) {
    $result = array();
    $rows = file_get_contents('C:\Users\Administrator\Desktop\Coronavirus\ccccc.md');
    foreach ($data as $key => $val) {
        $tit = rtrim(ltrim(preg_replace("/[0-9]/", "", $val['title']), '.'), '。');
        if (strstr($tit, ',') != false) {
            $tmpOne = explode(',', $tit);
            foreach ($tmpOne as $v) {
                $result = array_merge($result, check($rows, $v));
            }
        } else {
            $tmpRes = check($rows, $tit);
            if ($tmpRes) {
                $result = array_merge($result, $tmpRes);
            } else {
                $result = array_merge($result, check($rows, mb_substr($tit, 0, mb_strlen($tit, 'UTF-8') / 2, 'utf-8')));
            }
        }
        // print_r(array_unique($result));
		// $val['title'] = mb_convert_encoding($val['title'],'utf-8','gbk');
        // $tit = iconv('gb2312', 'utf8', $val['title']);
    }

    print_r(array_unique($result));
}