<?php
require_once 'data/getData.php';

$tmpArr = $info = array();
$country = isset($_REQUEST['country']) && $_REQUEST['country']? $_REQUEST['country'] : '';
$name = isset($_REQUEST['name']) && $_REQUEST['name']? $_REQUEST['name'] : '';
//获取国家最新信息
$dt = new DataInfo();
if ($country) {
    $info = $dt->CoronaInfo('corona', $country);
}

if ($name) {
    $rows = $dt->CoronaInfo($name, 'detail');
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
        <nav class="navbar navbar-expand-lg navbar-light  bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <span class="text-white" style="padding-bottom: 3px;">
              <svg style="width: 25px;" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="globe" class="svg-inline--fa fa-globe fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512">
                <path fill="currentColor" d="M336.5 160C322 70.7 287.8 8 248 8s-74 62.7-88.5 152h177zM152 256c0 22.2 1.2 43.5 3.3 64h185.3c2.1-20.5 3.3-41.8 3.3-64s-1.2-43.5-3.3-64H155.3c-2.1 20.5-3.3 41.8-3.3 64zm324.7-96c-28.6-67.9-86.5-120.4-158-141.6 24.4 33.8 41.2 84.7 50 141.6h108zM177.2 18.4C105.8 39.6 47.8 92.1 19.3 160h108c8.7-56.9 25.5-107.8 49.9-141.6zM487.4 192H372.7c2.1 21 3.3 42.5 3.3 64s-1.2 43-3.3 64h114.6c5.5-20.5 8.6-41.8 8.6-64s-3.1-43.5-8.5-64zM120 256c0-21.5 1.2-43 3.3-64H8.6C3.2 212.5 0 233.8 0 256s3.2 43.5 8.6 64h114.6c-2-21-3.2-42.5-3.2-64zm39.5 96c14.5 89.3 48.7 152 88.5 152s74-62.7 88.5-152h-177zm159.3 141.6c71.4-21.2 129.4-73.7 158-141.6h-108c-8.8 56.9-25.6 107.8-50 141.6zM19.3 352c28.6 67.9 86.5 120.4 158 141.6-24.4-33.8-41.2-84.7-50-141.6h-108z"></path>
              </svg>
              <a class="navbar-brand text-white" href="index.php" style="padding-left:5px;">JohnScott</a>
            </span>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                    <li class="nav-item <?php echo empty($nav) || $nav == 'index' ? 'active': '';?>">
                        <a class="nav-link text-white" href="index.php?nav=index"><b>Data</b></a>
                    </li>
                    <li class="nav-item <?php echo $nav == 'map' ? 'active': '';?>">
                        <a class="nav-link text-white" href="map.php?nav=map"><b>Map</b></a>
                    </li>
                    <li class="nav-item <?php echo $nav == 'map' ? 'active': '';?>">
                        <a class="nav-link text-white" href="wiki.php?nav=wiki"><b>Wiki</b></a>
                    </li>
                    <li class="nav-item <?php echo $nav == 'baidu' ? 'active': '';?>">
                        <a class="nav-link text-white" href="baidu.php?nav=baidu"><b>Baidu</b></a>
                    </li>
                    <li class="nav-item <?php echo $nav == 'about' ? 'active': '';?>">
                        <a class="nav-link text-white" href="about.php?nav=about"><b>About</b></a>
                    </li>
                </ul>
            </div>
        </nav>
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










