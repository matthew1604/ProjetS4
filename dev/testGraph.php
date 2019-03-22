<?php
$time_start = microtime(true);

$testGraph = true;
require_once('Algo.php');
$al = new Algo();
$al->startSimulation($tmax, 0);
$populationOnceAWeek = Utils::lagonPopSorted_array('../output/lagonPopulationSortedOnceAWeek.ini');
$i = 0;
foreach ($populationOnceAWeek as $v){
    if (!empty($v)) {

        $lagon1_line[$i] = array(
            "y" => $v[2],
            "label" => $v[1]
        );

        $lagon2_line[$i] = array(
            "y" => $v[3],
            "label" => $v[1]
        );

        $lagon3_line[$i] = array(
            "y" => $v[4],
            "label" => $v[1]
        );

        $lagon4_line[$i] = array(
            "y" => $v[4],
            "label" => $v[1]
        );

        $lagon5_line[$i] = array(
            "y" => $v[6],
            "label" => $v[1]
        );

        $lagon6_line[$i] = array(
            "y" => $v[7],
            "label" => $v[1]
        );

        $lagon7_line[$i] = array(
            "y" => $v[8],
            "label" => $v[1]
        );
    }

    $i++;
}
?>
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {
 
var chart = new CanvasJS.Chart("chartContainer", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Lagons population"
	},
	axisX: {
		title: "Time in years"
	},
	axisY: {
		title: "Population"
	},
	data: [{
		type: "line",		showInLegend: true,         name: "lagon1",        legendText: "Lagon 1",
		yValueFormatString: "#, population ##0.##",
		dataPoints: <?php echo json_encode($lagon1_line, JSON_NUMERIC_CHECK); ?>
	},	{		type: "line",		showInLegend: true,         name: "lagon2",        legendText: "Lagon 2",		yValueFormatString: "#, population ##0.##",		dataPoints: <?php echo json_encode($lagon2_line, JSON_NUMERIC_CHECK); ?>	},	{		type: "line",		showInLegend: true,         name: "lagon3",        legendText: "Lagon 3",		yValueFormatString: "#, population ##0.##",		dataPoints: <?php echo json_encode($lagon3_line, JSON_NUMERIC_CHECK); ?>	},	{		type: "line",		showInLegend: true,         name: "lagon4",        legendText: "Lagon 4",		yValueFormatString: "#, population ##0.##",		dataPoints: <?php echo json_encode($lagon4_line, JSON_NUMERIC_CHECK); ?>	},	{		type: "line",		showInLegend: true,         name: "lagon5",        legendText: "Lagon 5",		yValueFormatString: "#, population ##0.##",		dataPoints: <?php echo json_encode($lagon5_line, JSON_NUMERIC_CHECK); ?>	},	{		type: "line",		showInLegend: true,         name: "lagon6",        legendText: "Lagon 6",		yValueFormatString: "#, population ##0.##",		dataPoints: <?php echo json_encode($lagon6_line, JSON_NUMERIC_CHECK); ?>	},	{		type: "line",		showInLegend: true,         name: "lagon7",        legendText: "Lagon 7",		yValueFormatString: "#, population ##0.##",		dataPoints: <?php echo json_encode($lagon7_line, JSON_NUMERIC_CHECK); ?>	}	]
});

chart.render();
}
</script>
</head>
<body>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
</body>
</html>
<?php 
$time_end = microtime(true);
$time = $time_end - $time_start;
echo "<br><br>__________Process Time: {$time} seconds take to simulate $tmax year(s)________________";
$nbDead = 0;
foreach ($al->get('anemones') as $a){
	if($a->getActualCapacity() <= 0){
		$nbDead++;
	}
}

echo("<br><br>--- Nombre de d'anemone morte : $nbDead<br>");

?>