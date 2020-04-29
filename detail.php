<?php
define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
include_once './data/getData.php';

$tmpArr = $info = array();
$country = isset($_REQUEST['country']) && $_REQUEST['country']? $_REQUEST['country'] : '';
$name = isset($_REQUEST['name']) && $_REQUEST['name']? $_REQUEST['name'] : '';
//获取国家最新信息
$dt = new DataInfo();
if ($country) {
    $info = $dt->CoronaInfo('corona', $country);
}

if ($name) {
    $rows = $dt->CoronaInfo($name, '', '');
	if ($rows) {
		for ($i = 0; $i < count($rows); $i++) {
            $tmpArr[$i] = array(
                'ctime' => strtotime($rows[$i]['tmp_key']),
                'date' => $rows[$i]['tmp_key'],
                'cont' => $rows[$i]['content'],
            );
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
        <link rel="Shortcut Icon" href="./images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            .title{font-size: 23px;}
            .center{text-align: center;}
            .case{color: #696969;}
            .death{color: #FF0000;}
            .info{padding: 20px 20px;}
        </style>
    </head>
    <body>
        <!--    nav begin    -->
        <?php include_once ('views/layout/nav.php');?>
        <!--    nav end    -->
        
        <div class="info">
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
        </div>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/project/nav.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    </body>
</html>










