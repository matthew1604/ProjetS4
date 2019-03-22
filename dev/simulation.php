<?php 

$tmax = (int) $_GET['year'];
$typeSimulation = $_GET['typeSimu'];

switch ($typeSimulation){
    case 'graphique':
        require 'testGraph.php';
        break;
    case 'detaillee':
        $testGraph = false;
        require 'Algo.php';
        break;
}
?>