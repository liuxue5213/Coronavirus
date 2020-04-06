<?php
require_once 'data/corona.php';
$nav = isset($_REQUEST['nav']) ? $_REQUEST['nav']: '';
//$info = file_get_contents('http://34.80.195.241/Coronavirus/2.php');
//$info = json_decode($info, true);
$corona = new CoronaInfo();
$info = $corona->index(1);
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>COVID-19 CORONAVIRUS PANDEMIC</title>
        <link rel="Shortcut Icon" href="./images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="js/bootstrap-table-master/dist/foundation.min.css" integrity="sha256-xpOKVlYXzQ3P03j397+jWFZLMBXLES3IiryeClgU5og= sha384-gP4DhqyoT9b1vaikoHi9XQ8If7UNLO73JFOOlQV1RATrA7D0O7TjJZifac6NwPps sha512-AKwIib1E+xDeXe0tCgbc9uSvPwVYl6Awj7xl0FoaPFostZHOuDQ1abnDNCYtxL/HWEnVOMrFyf91TDgLPi9pNg==" crossorigin="anonymous">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <link rel="stylesheet" href="js/bootstrap-table-master/dist/themes/foundation/bootstrap-table-foundation.min.css">
        <style>
            .title{font-size: 26px;}
            .t1{padding-left: 65px;}
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

        <h2 style="text-align: center;">COVID-19 CORONAVIRUS PANDEMIC</h2>
        <div class="title">
            <span class="t1">Coronavirus Cases：<?php echo empty($info['cases']) ? 0 : $info['cases'];?></span>
            <span class="t1">Deaths：<?php echo empty($info['deaths']) ? 0 : $info['deaths'];?></span>
            <span class="t1">Recovered：<?php echo empty($info['recovered']) ? 0 : $info['recovered'];?></span>
        </div>
        <div class='title t1'><span>Last Updated Time: <?php echo $info['last_updated'];?></span></div>
        <div class='title t1'><span>May heaven have no coronavirus--JohnScott（愿天堂没有冠状病毒--超级帽子戏法）</span></div>
        <table
              id="table"
              data-show-refresh="true"
              data-auto-refresh="true"
              data-pagination="false"
              data-url="http://34.80.195.241/Coronavirus/1.php"
              data-side-pagination="server"
              data-show-print="true"
              data-header-style="headerStyle"
              data-search="false">
        <thead>
        <tr>
            <th data-field="country" data-sortable="true" data-formatter="formatter" data-events="events">城市</th>
              <th data-field="total_cases" data-sortable="true">确诊数</th>
              <th data-field="new_cases" data-sortable="true" data-cell-style="casesStyle">新增</th>
              <th data-field="total_deaths" data-sortable="true">累计死亡</th>
              <th data-field="new_deaths" data-sortable="true" data-cell-style="deathsStyle">新增死亡</th>
              <th data-field="total_recovered" data-sortable="true">治愈数量</th>
              <th data-field="active_cases" data-sortable="true">现存确诊</th>
              <th data-field="serious_critical" data-sortable="true">重症病例</th>
              <th data-field="tot_cases_1m" data-sortable="true">每百万确诊数</th>
              <th data-field="tot_deaths_1m" data-sortable="true">每百万死亡数</th>
              <th data-field="ost_case" data-sortable="true">首例时间</th>
          </tr>
          </thead>
        </table>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="js/base/js/bootstrap.min.js"></script>
        <script src="js/bootstrap-table-master/dist/bootstrap-table.min.js"></script>
        <script src="js/bootstrap-table-master/dist/foundation.min.js" integrity="sha256-/PFxCnsMh+nTuM0k3VJCRch1gwnCfKjaP8rJNq5SoBg= sha384-9ksAFjQjZnpqt6VtpjMjlp2S0qrGbcwF/rvrLUg2vciMhwc1UJJeAAOLuJ96w+Nj sha512-UMSn6RHqqJeJcIfV1eS2tPKCjzaHkU/KqgAnQ7Nzn0mLicFxaVhm9vq7zG5+0LALt15j1ljlg8Fp9PT1VGNmDw==" crossorigin="anonymous"></script>
        <script src="js/bootstrap-table-master/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
        <script src="js/bootstrap-table-master/dist/themes/foundation/bootstrap-table-foundation.js"></script>
        <script src="js/bootstrap-table-master/dist/extensions/print/bootstrap-table-print.min.js"></script>
      <script>
          $(function() {
              $('#table').bootstrapTable()
          });

          function formatter(value, row, index) {
              if (row.country_url) {
                  return '<a class="detail" target="_blank" href="/Coronavirus/detail.php?country='+row.country+'&name='+row.name+'" title="'+value+'">'+value+'</a>';
              } else {
                  return value;
              }
          }

          window.events = {
              'click .detail': function (e, value, row, index) {
                  // alert('You click like action, row: ' + JSON.stringify(row))
              }
          };
          
          function casesStyle(value, row, index) {
              if (value) {
                  return {
                      css: {
                          background: '#FFDC35'
                      }
                  }
              } else{
                  return {}
              }
          }

          function deathsStyle(value, row, index) {
              if (value) {
                  return {
                      css: {
                          background: '#FF0000'
                      }
                  }
              } else{
                  return {}
              }
          }

          function headerStyle(column) {
              return {
                  new_cases: {
                      css: {background: '#FFDC35'}
                  },
                  new_deaths: {
                      css: {background: '#FF0000'}
                  }
              }[column.field]
          }
      </script>
  </body>
</html>










