<?php
$testGraph = true;
require_once('algo.php');
$al = new algo();

foreach ($al->get('anemones') as $a) {
    
    $dataPoints[(int)($a->get('id') -1)] = array(
        "y" => (int)$a->get('actualCapacity'),
        "label" => (int)$a->get('id')
    );
}

$al->startSimulation(1, 0);

foreach ($al->get('anemones') as $a) {
    $cap = (int)$a->get('actualCapacity');
    if($cap < 0) $cap = 0;
    $dataPoints2[(int)($a->get('id') -1)] = array(
        "y" => $cap,
        "label" => (int)$a->get('id')
    );
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
		text: "Anemones population before simulation"
	},
	axisY: {
		title: "Population"
	},
	data: [{
		type: "column",
		yValueFormatString: "#, population ##0.##",
		dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
	}]
});

var chart2 = new CanvasJS.Chart("chartContainer2", {
	animationEnabled: true,
	theme: "light2",
	title:{
		text: "Anemones population after simulation"
	},
	axisY: {
		title: "Population"
	},
	data: [{
		type: "column",
		yValueFormatString: "#, population ##0.##",
		dataPoints: <?php echo json_encode($dataPoints2, JSON_NUMERIC_CHECK); ?>
	}]
});
chart.render();
chart2.render();

}
</script>
</head>
<body>
	<div id="chartContainer" style="height: 370px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
	
	<div id="chartContainer2" style="height: 370px; width: 100%;"></div>
	<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>
<br><br>
<?php 
