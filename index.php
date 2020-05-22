<?php
define('BASE_PATH', str_replace('\\','/',realpath(dirname(__FILE__).'/'))."/");
include_once BASE_PATH.'./data/getData.php';
//$nav = isset($_REQUEST['nav']) ? $_REQUEST['nav']: '';
$dt = new DataInfo();
$info = $dt->CoronaInfo('coronaInfo', 'title');

?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>COVID-19 CORONAVIRUS PANDEMIC</title>
        <link rel="Shortcut Icon" href="./images/favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
        <link rel="stylesheet" href="js/bootstrap-table-master/dist/foundation.min.css">
        <link rel="stylesheet" href="js/bootstrap-table-master/dist/themes/foundation/bootstrap-table-foundation.min.css">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
        <style>
            .t1{padding-left: 4%;}
            .title{font-size: 26px;}
            /*.navbar-light .navbar-toggler-icon {*/
            /*    background-image: url("data:image/svg xml,<svg xmlns='http://www.w3.org/2000/svg' width='30' height='30' viewBox='0 0 30 30'><path stroke='rgba(0, 100, 0, 0.5)' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/></svg>");*/
            /*}*/
        </style>
    </head>
    <body>
        <!--    nav begin    -->
		<?php include_once ('views/layout/nav.php');?>
        <!--    nav end    -->

        <h2 style="text-align: center;">COVID-19 CORONAVIRUS PANDEMIC</h2>
        <div class="title">
            <span class="t1">Coronavirus Cases：<?php echo empty($info['cases']) ? 0 : $info['cases'];?></span>
            <span class="t1">Deaths：<?php echo empty($info['deaths']) ? 0 : $info['deaths'];?></span>
            <span class="t1">Recovered：<?php echo empty($info['recovered']) ? 0 : $info['recovered'];?></span>
        </div>
        <div class='title t1'><span>Last Updated Time: <?php echo $info['last_updated'];?></span></div>
        <div class='title t1'><span>May heaven have no coronavirus--JohnScott（愿天堂没有冠状病毒--超级帽子戏法）</span></div>
        <table id="table"
              data-show-refresh="true"
              data-auto-refresh="true"
              data-pagination="false"
              data-url="http://34.80.231.31/Coronavirus/1.php"
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
<!--                    <th data-field="tot_cases_1m" data-sortable="true">每百万确诊数</th>-->
<!--                    <th data-field="tot_deaths_1m" data-sortable="true">每百万死亡数</th>-->
<!--                    <th data-field="ost_case" data-sortable="true">首例时间</th>-->
                </tr>
            </thead>
        </table>
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/base/js/bootstrap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="js/bootstrap-table-master/dist/bootstrap-table.min.js"></script>
        <script src="js/bootstrap-table-master/dist/foundation.min.js"></script>
        <script src="js/bootstrap-table-master/dist/themes/foundation/bootstrap-table-foundation.js"></script>
        <script src="js/bootstrap-table-master/dist/extensions/auto-refresh/bootstrap-table-auto-refresh.min.js"></script>
        <script src="js/bootstrap-table-master/dist/extensions/print/bootstrap-table-print.min.js"></script>
        <script src="js/project/nav.js"></script>
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










