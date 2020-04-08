<?php
// header("Access-Control-Allow-Origin：http://34.80.195.241");
require_once 'baidu/get.php';
$nav = isset($_REQUEST['nav']) ? $_REQUEST['nav']: '';
$baidu = new Baidu();
$data = $baidu->getData();
$nowDay = date('Y-m-d');
$trumpet = isset($data['trumpet']) ? $data['trumpet'] : array();
$rtData = isset($data['realtime_data']) ? $data['realtime_data'] : array();
$frtData = isset($data['foreign_realtime_data']) ? $data['foreign_realtime_data'] : array();
$hotwords = isset($data['hotwords']) ? $data['hotwords'] : array();
//print_r($hotwords[0]['item']);
//die;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>COVID-19 CORONAVIRUS PANDEMIC</title>
        <link rel="Shortcut Icon" href="./images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <style>
            @media (max-width: 575.98px) {
                .tab-span-forg{width: 480px;}
            }
            @media (min-width: 576px) and (max-width: 767.98px) {
                .tab-span-forg{width: 400px;}
            }

            @media (min-width: 768px) and (max-width: 991.98px) {
                .tab-span-forg{width: 370px;}
            }

            @media (min-width: 992px) and (max-width: 1199.98px) {
                .tab-span-forg{width: 370px;}
            }

            @media (min-width: 1200px) {
                .tab-span-forg{width: 420px;}
            }
            .center{text-align: center;}
            .summary-data{display: flex;width: 95%;text-align: center;font-size: 22px;padding:20px 5px;}
            /*.summary-data{display: flex;width: 95%;text-align: center;font-size: 22px;padding:20px 5px;}*/
            .confirm{color: #ff6a57}
            .confirmed{color: #e83132}
            .cured{color: #10aeb5}
            .died{color: #4d5054}
            .asymptomatic{color: #e86d48}
            .unconfirmed{color: #ec9217}
            .icu{color: #545499}
            .overseasInput{color: #476da0}
            .other{font-size: 24px;padding-left: 20%;}
            .degree{font-size: 16px;color: #999;}
            .other-d{font-size: 24px;padding-left: 40%;}
        </style>
    </head>
    <body>
        <!--    nav begin    -->
	    <?php include_once ('views/layout/nav.php');?>
        <!--    nav end    -->

        <h1 style="text-align: center; padding: 15px; font-weight: bolder;">
<!--            <img src="https://mms-res.cdn.bcebos.com/voicefe/captain/images/1b9ddd53f65d1b3a4faeca959e15d425c8d85d2f?120*40">-->
            <span style="margin-top: 5px;color:#F23F40">Baidu抗击肺炎专题</span>
        </h1>
        <!-- <h3>数据来自官方通报 全国与各省通报数据可能存在差异</h3> -->
        <h2 class="center">公告消息</h2>
        <h3>数据更新时间(北京时间)：<?php echo $data['mapLastUpdatedTime'];?>
            <?php
                if ($data['mapLastUpdatedTime'] != $data['foreignLastUpdatedTime']) {
				    echo sprintf('<h3>国际数据更新时间：%s</h3>', $data['foreignLastUpdatedTime']);
                }
            ?>
        </h3>

        <!-- 公告消息 -->
        <?php if ($trumpet) {
            foreach ($trumpet as $val) { ?>
                <h4><?php echo $val['title'];?></h4>
                <p>
                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $val['content'];?>
                </p>
        <?php } } ?>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">国内疫情</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="foreign-tab" data-toggle="tab" href="#foreign" role="tab" aria-controls="foreign" aria-selected="false">国外疫情</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="hot-tab" data-toggle="tab" href="#hot" role="tab" aria-controls="hot" aria-selected="false">全民热搜</a>
            </li>
<!--            <li class="nav-item">-->
<!--                <a class="nav-link" id="big-tab" data-toggle="tab" href="#big" role="tab" aria-controls="big" aria-selected="false">数据统计</a>-->
<!--            </li>-->
        </ul>
        <div class="container tab-content" id="myTabContent">
            <div class="row tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                <!-- 国内疫情情况 -->
                <div class="summary-data">
                    <div class="tab-span-forg">现有确诊
                        <span class="confirm"><?php echo $data['summaryDataIn']['curConfirm'];?></span><br>
                        昨日<span class="confirm"><?php echo intval($data['summaryDataIn']['curConfirmRelative']) > 0 ? '+'.$data['summaryDataIn']['curConfirmRelative']:$data['summaryDataIn']['curConfirmRelative'];?></span>
                    </div>

                    <div class="tab-span-forg">无症状
                        <span class="asymptomatic"><?php echo $data['summaryDataIn']['asymptomatic'];?></span><br>
                        昨日<span class="asymptomatic"><?php echo intval($data['summaryDataIn']['asymptomaticRelative']) > 0 ? '+'.$data['summaryDataIn']['asymptomaticRelative']:$data['summaryDataIn']['asymptomaticRelative'];?></span>
                    </div>

                    <div class="tab-span-forg">现有疑似
                        <span class="unconfirmed"><?php echo $data['summaryDataIn']['unconfirmed'];?></span><br>
                        昨日<span class="unconfirmed"><?php echo intval($data['summaryDataIn']['unconfirmedRelative']) > 0 ? '+'.$data['summaryDataIn']['unconfirmedRelative']:$data['summaryDataIn']['unconfirmedRelative'];?></span>
                    </div>

                    <div class="tab-span-forg">现有重症
                        <span class="icu"><?php echo $data['summaryDataIn']['icu'];?></span><br>
                        昨日<span class="icu"><?php echo intval($data['summaryDataIn']['icuRelative']) > 0 ? '+'.$data['summaryDataIn']['icuRelative']:$data['summaryDataIn']['icuRelative'];?></span>
                    </div>
                </div>

                <div class="summary-data">
                    <span class="tab-span-forg">累计确诊
                        <span class="confirmed"><?php echo $data['summaryDataIn']['confirmed'];?></span><br>
                        昨日<span class="confirmed"><?php echo intval($data['summaryDataIn']['confirmedRelative']) > 0 ? '+'.$data['summaryDataIn']['confirmedRelative']:$data['summaryDataIn']['confirmedRelative'];?></span>
                    </span>

                    <span class="tab-span-forg">境外输入
                        <span class="overseasInput"><?php echo $data['summaryDataIn']['overseasInput'];?></span><br>
                        昨日<span class="overseasInput"><?php echo intval($data['summaryDataIn']['overseasInputRelative']) > 0 ? '+'.$data['summaryDataIn']['overseasInputRelative']:$data['summaryDataIn']['overseasInputRelative'];?></span>
                    </span>

                    <span class="tab-span-forg">累计治愈
                        <span class="cured"><?php echo $data['summaryDataIn']['cured'];?></span><br>
                        昨日<span class="cured"><?php echo intval($data['summaryDataIn']['curedRelative']) > 0 ? '+'.$data['summaryDataIn']['curedRelative']:$data['summaryDataIn']['curedRelative'];?></span>
                    </span>

                    <span class="tab-span-forg">累计死亡
                        <span class="died"><?php echo $data['summaryDataIn']['died'];?></span><br>
                        昨日<span class="died"><?php echo intval($data['summaryDataIn']['diedRelative']) > 0 ? '+'.$data['summaryDataIn']['diedRelative']:$data['summaryDataIn']['diedRelative'];?></span>
                    </span>
                </div>
                
                <!-- 国内资讯 -->
                <div>
                    <h2 class="center">国内资讯</h2>
                    <?php if ($rtData) {
                        $rti = 1;
                        foreach ($rtData as $val) { ?>
                                <div class="hideRt" style="display: <?php echo $rti > 10 ? 'none' : '';?>" >
                                    <h3>
                                        <a target="_blank" href="<?php echo $val['eventUrl'];?>" style="text-decoration:none;color: #4d5054;">
                                            <?php echo $rti.'、'.$val['eventDescription'];?>
                                        </a>
                                    </h3>
                                    <p>
                                    <span>
                                        <?php
                                        echo date('Y-m-d H:i', $val['eventTime']);
                                        if ($nowDay == date('Y-m-d', $val['eventTime'])) {
                                            echo '(<span style="color:#F23F40;">新</span>)';
                                        }
                                        ?>
                                    </span>
                                        <span>信息来源：<a target="_blank" href="<?php echo $val['homepageUrl'];?>" style="text-decoration:none;"><?php echo $val['siteName'];?></a></span>
                                    </p>
                                </div>
                    <?php $rti++;} ?>
                        <div id="hideRt"><a class="text-success other" onclick="showRt()">点击查看更多</a></div>
                    <?php } ?>
                </div>
            </div>
            <div class="tab-pane fade" id="foreign" role="tabpanel" aria-labelledby="foreign-tab">
                <!-- 国外疫情情况 -->
                <div class="summary-data">
                    <span class="tab-span-forg">现有确诊
                        <span class="confirm"><?php echo $data['summaryDataOut']['curConfirm'];?></span><br>
                        昨日<span class="confirm"><?php echo intval($data['summaryDataOut']['curConfirmRelative']) > 0 ? '+'.$data['summaryDataOut']['curConfirmRelative']:$data['summaryDataOut']['curConfirmRelative'];?></span>
                    </span>

                    <span class="tab-span-forg">累计确诊
                        <span class="confirmed"><?php echo $data['summaryDataOut']['confirmed'];?></span><br>
                        昨日<span class="confirmed"><?php echo intval($data['summaryDataOut']['curedRelative']) > 0 ? '+'.$data['summaryDataOut']['confirmedRelative']:$data['summaryDataOut']['curedRelative'];?></span>
                    </span>

                    <span class="tab-span-forg">累计治愈
                        <span class="cured"><?php echo $data['summaryDataOut']['cured'];?></span><br>
                        昨日<span class="cured"><?php echo intval($data['summaryDataOut']['curedRelative']) > 0 ? '+'.$data['summaryDataOut']['curedRelative']:$data['summaryDataOut']['curedRelative'];?></span>
                    </span>

                    <span class="tab-span-forg">累计死亡
                        <span class="died"><?php echo $data['summaryDataOut']['died'];?></span><br>
                        昨日<span class="died"><?php echo intval($data['summaryDataOut']['diedRelative']) > 0 ? '+'.$data['summaryDataOut']['diedRelative']:$data['summaryDataOut']['diedRelative'];?></span>
                    </span>
                </div>

                <!-- 国外疫情 -->
                <div>
                    <h2 class="center">国外疫情</h2>
					<?php if ($frtData) {
						$frti = 1;
						foreach ($frtData as $val) { ?>
                            <div class="hideFrt" style="display: <?php echo $frti > 15 ? 'none' : '';?>" >
                                <h3>
                                    <a target="_blank" href="<?php echo $val['eventUrl'];?>" style="text-decoration:none;color: #4d5054;">
										<?php echo $frti.'、'.$val['eventDescription'];?>
                                    </a>
                                </h3>
                                <p>
                                    <span>
                                        <?php
										echo date('Y-m-d H:i', $val['eventTime']);
										if ($nowDay == date('Y-m-d', $val['eventTime'])) {
											echo '(<span style="color:#F23F40;">新</span>)';
										}
										?>
                                    </span>
                                    <span>信息来源：<a target="_blank" href="<?php echo $val['homepageUrl'];?>" style="text-decoration:none;"><?php echo $val['siteName'];?></a></span>
                                </p>
                            </div>
							<?php $frti++;} ?>
                        <div id="hideFrt"><a class="text-success other" onclick="showFrt()">点击查看更多</a></div>
					<?php } ?>
                </div>
            </div>
            <div class="tab-pane fade" id="hot" role="tabpanel" aria-labelledby="hot-tab">
                <!-- 今日疫情热搜 -->
                <div>
                    <h2 class="center">今日疫情热搜(全国)</h2>
                    <?php if (isset($hotwords[0]['item'])) {
                        $hti = 1;
                        foreach ($hotwords[0]['item'] as $val) { ?>
                            <div class="hideHw" style="display: <?php echo $hti > 8 ? 'none' : '';?>" >
                                <h3>
                                    <a target="_blank" href="<?php echo $val['url'];?>" style="text-decoration:none;color: #4d5054;">
                                        <?php echo $hti.'、'.$val['query'];?>
                                    </a>
                                    <span class="degree">
                                        <?php echo $val['degree'];?>
                                    </span>
                                    <span>
                                        <?php
										if (1 === intval($val['type'])) {
											echo '(<span style="color:#F23F40;">热</span>)';
										}
										?>
                                    </span>
                                </h3>
                            </div>
                            <?php $hti++;} ?>
                        <div id="hideHw"><a class="text-success other-d" onclick="showHw()">点击查看更多</a></div>
                    <?php } ?>
                </div>

                <!-- 复工复课热搜 -->
                <div style="padding-top:20px;">
                    <h2 class="center">复工复课热搜</h2>
                    <?php if (isset($hotwords[3]['item'])) {
                        $htwi = 1;
                        foreach ($hotwords[3]['item'] as $val) { ?>
                            <div class="hideHwi" style="display: <?php echo $htwi > 8 ? 'none' : '';?>" >
                                <h3>
                                    <a target="_blank" href="<?php echo $val['url'];?>" style="text-decoration:none;color: #4d5054;">
                                        <?php echo $htwi.'、'.$val['query'];?>
                                    </a>
                                    <span class="degree">
                                        <?php echo $val['degree'];?>
                                    </span>
                                    <span>
                                        <?php
										if (1 === intval($val['type'])) {
											echo '(<span style="color:#F23F40;">热</span>)';
										}
										?>
                                    </span>
                                </h3>
                            </div>
                            <?php $htwi++;} ?>
                        <div id="hideHwi"><a class="text-success other-d" onclick="showHwi()">点击查看更多</a></div>
                    <?php } ?>
                </div>


            </div>
            <div class="tab-pane fade" id="big" role="tabpanel" aria-labelledby="big-tab">
                <!-- 数据统计 -->
                完成中...
            </div>
        </div>

        <script>
            // $(function() {
            // });
            function showRt() {
                $('.hideRt').show();
                $('#hideRt').hide();
            }
            function showFrt() {
                $('.hideFrt').show();
                $('#hideFrt').hide();
            }
            function showHw() {
                $('.hideHw').show();
                $('#hideHw').hide();
            }
            function showHwi() {
                $('.hideHwi').show();
                $('#hideHwi').hide();
            }
        </script>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="js/project/nav.js"></script>
  </body>
</html>










