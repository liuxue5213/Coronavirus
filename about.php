<?php
header("Access-Control-Allow-Origin：http://34.80.195.241");
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
            .bgc{background-color: #445175;}
        </style>
    </head>
    <body>
        <!--    nav begin    -->
	    <?php include_once ('scripts/nav.php');?>
        <!--    nav end    -->

        <h1 style="font-size: 45px; text-align: center; padding-top: 15px; font-weight: bolder;">
            <svg style="width: 35px; padding-bottom: 10px;" aria-hidden="true" focusable="false" data-prefix="fas"
                 data-icon="id-card" class="svg-inline--fa fa-id-card fa-w-18" role="img"
                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512">
                <path fill="currentColor"
                      d="M528 32H48C21.5 32 0 53.5 0 80v16h576V80c0-26.5-21.5-48-48-48zM0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V128H0v304zm352-232c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zm0 64c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16c0 4.4-3.6 8-8 8H360c-4.4 0-8-3.6-8-8v-16zM176 192c35.3 0 64 28.7 64 64s-28.7 64-64 64-64-28.7-64-64 28.7-64 64-64zM67.1 396.2C75.5 370.5 99.6 352 128 352h8.2c12.3 5.1 25.7 8 39.8 8s27.6-2.9 39.8-8h8.2c28.4 0 52.5 18.5 60.9 44.2 3.2 9.9-5.2 19.8-15.6 19.8H82.7c-10.4 0-18.8-10-15.6-19.8z">
                </path>
            </svg>
            About
        </h1>
        <div class="container--wrap center">
            <h2 style="font-size: 35px; text-align: center; padding-top: 15px;">
                <svg style="width: 25px; padding-bottom: 6px" aria-hidden="true" focusable="false"
                     data-prefix="fas" data-icon="question-circle"
                     class="svg-inline--fa fa-question-circle fa-w-16" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor"
                          d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z">
                    </path>
                </svg>
                <b>Who made this website?</b>
            </h2>
            <p style="font-size: 25px;text-align: center;">
                This site was created by
                <strong><a href="http://johnscott1989.club/" target="_blank">JohnScott</a></strong>(click me open my blog)
            </p>
        </div>
        <div class="container--wrap center">
            <h2 style="font-size: 35px; text-align: center; padding-top: 15px;">
                <svg style="width: 25px; padding-bottom: 6px;" aria-hidden="true" focusable="false"
                     data-prefix="fas" data-icon="question-circle"
                     class="svg-inline--fa fa-question-circle fa-w-16" role="img"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                    <path fill="currentColor"
                          d="M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zM262.655 90c-54.497 0-89.255 22.957-116.549 63.758-3.536 5.286-2.353 12.415 2.715 16.258l34.699 26.31c5.205 3.947 12.621 3.008 16.665-2.122 17.864-22.658 30.113-35.797 57.303-35.797 20.429 0 45.698 13.148 45.698 32.958 0 14.976-12.363 22.667-32.534 33.976C247.128 238.528 216 254.941 216 296v4c0 6.627 5.373 12 12 12h56c6.627 0 12-5.373 12-12v-1.333c0-28.462 83.186-29.647 83.186-106.667 0-58.002-60.165-102-116.531-102zM256 338c-25.365 0-46 20.635-46 46 0 25.364 20.635 46 46 46s46-20.636 46-46c0-25.365-20.635-46-46-46z">
                    </path>
                </svg> Data Sources</h2>
            <div style="text-align: center; padding-bottom: 25px;">
                <a style=" font-size: 20px;" href="https://bnonews.com/index.php/2020/01/the-latest-coronavirus-cases/"><u>BNO
                        News</u></a><br>
                <a style=" font-size: 20px;" href="https://www.who.int/"><u>WHO</u></a><br>
                <a style=" font-size: 20px;" href="https://www.worldometers.info/coronavirus"><u>WorldOmeters</u></a><br>
                <a style=" font-size: 20px;" href="https://ncov2019.live/about"><u>Avi Schiffmann's Website</u></a><br>
            </div>
        </div>
  </body>
</html>










