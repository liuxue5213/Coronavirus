<?php
require_once 'data/get.php';
require_once 'data/other.php';

//header("Access-Control-Allow-Origin：http://34.80.195.241");
$tmpArr = $info = array();
$country = isset($_REQUEST['country']) && $_REQUEST['country']? $_REQUEST['country'] : '';
$name = isset($_REQUEST['name']) && $_REQUEST['name']? $_REQUEST['name'] : '';
//获取国家最新信息
if ($country) {
	$co = new Corona();
	$info = $co->getCountry($country);
}
if ($name) {
	$oc = new OtherCountry();
	$rows = $oc->index($name);
	if ($rows) {
		$i = 0;
		foreach ($rows as $key => $val) {
			$tmpArr[$i]['ctime'] = strtotime($key);
			$tmpArr[$i]['date'] = $key;
			$tmpArr[$i]['cont'] = $val;
			$i++;
		}
		$tmpCases = array_column($tmpArr, 'ctime');
		array_multisort($tmpCases, SORT_DESC, $tmpArr);
    }
}
?>

<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>COVID-19 COUNTRY DETAIL</title>
        <link rel="Shortcut Icon" href="../favicon.ico" type="image/x-icon" />
        <style>
            .title{font-size: 23px;}
            .center{text-align: center;}
            .case{color: #696969}
            .death{color: #FF0000}
        </style>
    </head>
    <body style="padding: 20px 20px;">
        <h2 class="center">COVID-19 INFOS FOR <?php echo empty($info['country']) ? strtoupper($name): $info['country'];?></h2>
        <p class="title"><b>Total Coronavirus Cases：</b><?php echo empty($info['total_cases']) ? 0 : $info['total_cases'];?></p>
        <p class="title"><b>New Cases：</b><span class="case"><?php echo empty($info['new_cases']) ? 0 : $info['new_cases'];?></span></p>
        <p class="title"><b>Total Deaths：</b><?php echo empty($info['total_deaths']) ? 0 : $info['total_deaths'];?></p>
        <p class="title"><b>New Deaths：</b><span class="death"><?php echo empty($info['new_deaths']) ? 0 : $info['new_deaths'];?></span></p>
        <p class="title"><b>Total Recovered：</b><?php echo empty($info['total_recovered']) ? 0 : $info['total_recovered'];?></p>
        <p class="title"><b>Active Cases：</b><?php echo empty($info['active_cases']) ? 0 : $info['active_cases'];?></p>
        <p class="title"><b>Serious Critical：</b><?php echo empty($info['serious_critical']) ? 0 : $info['serious_critical'];?></p>

        <h2 class="center">Latest News Updates</h2>

        <?php foreach ($tmpArr as $key => $val) {
			$val['cont'] = str_replace('/coronavirus/country/'.$name.'/','#', $val['cont']);
			$val['cont'] = str_replace('[<a href="#" target="_blank">sources</a>]','', $val['cont']);
            ?>
            <h2><?php echo $val['date'];?></h2>
            <p><?php echo $val['cont'];?></p>
        <?php } ?>
  </body>
</html>










