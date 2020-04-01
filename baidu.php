<?php
// header("Access-Control-Allow-Origin：http://34.80.195.241");
require_once 'baidu/get.php';
$nav = isset($_REQUEST['nav']) ? $_REQUEST['nav']: '';
$baidu = new Baidu();
$data = $baidu->index();
$trumpet = isset($data['trumpet']) ? $data['trumpet'] : array();
// print_r($data);
// die;


?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>COVID-19 CORONAVIRUS PANDEMIC</title>
        <link rel="Shortcut Icon" href="./images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            .title{font-size: 26px;}
            .t1{padding-left: 65px;}
            .bgc{background-color: #445175;}
            .center{text-align: center;}
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
            </span>
            <a class="navbar-brand text-white" href="index.php" style="padding-left:5px;">JohnScott</a>
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
        <h1 style="text-align: center; padding: 15px; font-weight: bolder;">
            <img src="https://mms-res.cdn.bcebos.com/voicefe/captain/images/1b9ddd53f65d1b3a4faeca959e15d425c8d85d2f?120*40">
            <span style="margin-top: 5px;color:#F23F40">百度抗击肺炎专题</span>
        </h1>

        <!-- <h3>数据来自官方通报 全国与各省通报数据可能存在差异</h3> -->
        <h3>数据更新至：<?php echo $data['mapLastUpdatedTime'];?></h3>

        <!-- 公告消息 -->
        <div>
            <h2 class="center">公告消息</h2>
            <?php if ($trumpet) { 
                foreach ($trumpet as $val) { ?>
                <h4><?php echo $val['title'];?></h4>
                <p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $val['content'];?>
                </p>
            <?php } } ?>
        </div>

        
  </body>
</html>










