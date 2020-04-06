<?php
$nav = isset($_REQUEST['nav']) ? $_REQUEST['nav']: '';
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
        </style>
    </head>
    <body>
        <!--    nav begin    -->
		<?php include_once ('views/layout/nav.php');?>
        <!--    nav end    -->

        <h2 style="text-align: center;">COVID-19 CORONAVIRUS PANDEMIC</h2>
        <h4>This map is ran by BNO News, however I am working on a new map that will be out by the end of the week! Thank you for waiting, the new version will be synced with the data page and allow for more information to be displayed. - Avi</h4>
        <p>需要可以访问google map 才可以正常加载</p>
        <iframe id="mainContent" width="100%" height="850px;" src="https://www.google.com/maps/d/embed?mid=1a04iBi41DznkMaQRnICO40ktROfnMfMx" allowfullscreen></iframe>
        <script>
            // reSetSize();
            // window.onresize = reSetSize;
            // function reSetSize() {
            //     var windowsHeight = window.innerHeight;
            //     document.getElementById("mainContent").style.height = (windowsHeight-框架顶部高度) + "px";
            // }
        </script>
    </body>
</html>










