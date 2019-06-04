<?php
namespace    TheSaturn\BranchAndBound;
spl_autoload_register(function ($class)
{
    $class = substr($class, strrpos($class, "\\") + 1);
    include 'lib/' . $class . '.php';
});
$messages = new    Messages;
BranchAndBound::$messages = $messages;
Node::$messages = $messages;
$t1 = microtime(true);
$tableBranchAndBound = new    TableBranchAndBound;
$root = new    Node($tableBranchAndBound->table);
$t2 = microtime(true);
$googleRows = new    RowsGoogleCharts($root);

?><!DOCTYPE html>
<html lang="ru">
<head>
    <base href="http://<?= $_SERVER['HTTP_HOST'] ?>"/>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Метод ветвей и границ</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="user.css" rel="stylesheet">
    <!-- Yandex.Metrika counter -->
<script type="text/javascript">
(function (d, w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter27657369 = new Ya.Metrika({id:27657369,
                    webvisor:true,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true});
        } catch(e) { }
    });

    var n = d.getElementsByTagName("script")[0],
        s = d.createElement("script"),
        f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src = (d.location.protocol == "https:" ? "https:" : "http:") + "//mc.yandex.ru/metrika/watch.js";

    if (w.opera == "[object Opera]") {
        d.addEventListener("DOMContentLoaded", f, false);
    } else { f(); }
})(document, window, "yandex_metrika_callbacks");
</script>
<noscript><div><img src="//mc.yandex.ru/watch/27657369" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
    <script src="template/default/js/jquery-2.1.1.min.js"></script>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        var prevId = '';
        function myReadyHandler() {
            $('tr.google-visualization-orgchart-noderow-medium > td').click(function () {
                var id = $(this).find('val').text();
                //console.log($(this).find('val').text());
                $('.page').each(function () {
                    $(this).hide("fast");
                    if (prevId != id && $(this).data('id') == id) {
                        $(this).toggle("medium");
                    }
                });
                prevId = id;
            });
        }
        google.load("visualization", "1", {packages: ["orgchart", "corechart"]});
        google.setOnLoadCallback(drawChart);
        function drawChart() {
            var data = new google.visualization.DataTable();
            data.addColumn('string', 'Name');
            data.addColumn('string', 'Manager');
            data.addColumn('string', 'ToolTip');

            data.addRows([
                <?=$googleRows?>
            ]);
            var chart = new google.visualization.OrgChart(document.getElementById('chart_div'));
            google.visualization.events.addListener(chart, 'ready', myReadyHandler);
            chart.draw(data, {allowHtml: true});



        }
    </script>
</head>
<body>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h2>Таблица длин маршрутов</h2>

            <form method="POST" class="form-inline">
                <p>
                    <input type="number" class="form-control" placeholder="Размерность" id="amount" name="amount">
                </p>
                <?= $tableBranchAndBound ?>
                <button class="btn btn-primary" type="submit">Посчитать!</button>
            </form>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
           <h2><?= Node::$answer; ?></h2>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            $messages->printt();
            ?>
        </div>
    </div>

</div>
</body>
</html>
