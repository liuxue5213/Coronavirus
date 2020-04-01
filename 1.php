<?php
/**
 * @Author: anchen
 * @Date:   2020-03-27 11:02:44
 * @Last Modified by:   anchen
 * @Last Modified time: 2020-04-01 10:52:01
 */
require_once 'data/get.php';

$config = array(
    'host' => '127.0.0.1',
    'port' => '6379'
);
$redis = new Predis($config);
// $redis->flushDB();
$key = 'corona';
$corona = new Corona();
if ($redis->exists('corona1')) {
    $data = $corona->redisGetAll($redis, $key);
} else {
    $data = $corona->index(1);
}

print_r(json_encode(array(
	'total' => count($data),
	'totalNotFiltered' => count($data),
	'rows' => $data
)));

//回调函数1
// function callfun1($content,$key)
// {
//     return '回调函数1：'.$key.'-'.$content;
// }

// class HJ{
//     //回调函数2
//     static public function callfun2($content,$key)
//     {
//         return '回调函数2：'.$key.'-'.$content;
//     }
// }
// // 采集该页面[正文内容]中所有的图片
// $data = QueryList::get('http://cms.querylist.cc/bizhi/453.html')->find('.post_content img')->attrs('src');
//打印结果
// print_r($data->all());

// $hj = QueryList::Query('http://mobile.csdn.net/',array("title"=>array('.unit h1','text')));
// print_r($hj->data);

// $rules = array(
//     //采集id为one这个元素里面的纯文本内容
//     'text' => array('#one','text'),
//     //采集class为two下面的超链接的链接
//     'link' => array('.two>a','href'),
//     //采集class为two下面的第二张图片的链接
//     'img' => array('.two>img:eq(1)','src'),
//     //采集span标签中的HTML内容
//     'other' => array('span','html')
// );
// 'title'=>array('h1','text','','callfun1'), //获取纯文本格式的标题,并调用回调函数1                   
// 'summary'=>array('.summary','text','-input strong'), //获取纯文本的文章摘要，但保strong标签并去除input标签
// 'content'=>array('.news_content','html','div a -.copyright'),    //获取html格式的文章内容，但过滤掉div和a标签,去除类名为copyright的元素
// 'callback'=>array('HJ','callfun2')      //调用回调函数2作为全局回调函数
// 'last_udpate_time' => ['.content-inner div:eq(1)', 'text'],



