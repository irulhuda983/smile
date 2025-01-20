<?php // content="text/plain; charset=utf-8"
require_once ('../jpgraph.php');
require_once ('../jpgraph_bar.php');


$datay=array(140,110,100,100);
$datay2=array(2,3,4,5);
$datazero=array(0,0,0,0);
// Setup the graph
	$graph = new Graph(500,300,'auto');
			$graph->img->SetMargin(80,30,30,40);
			$graph->SetMarginColor('white');

$graph->legend->Pos(0.02,0.15);

$graph->SetScale('textlin');
$graph->title->Set('Using multiple Y-axis');
$graph->title->SetFont(FF_ARIAL,FS_NORMAL,14);

$graph->SetScale("textlin");
$graph->SetY2Scale("lin");
$graph->yaxis->scale->SetGrace(30);
$graph->y2axis->scale->SetGrace(30);

//$graph->SetYScale(0,'lin');
//$graph->SetYScale(1,'lin');
/*$graph->SetYScale(2,'lin');
*/
$bplotzero = new BarPlot($datazero);

$p1 = new BarPlot($datay);

$p2 = new BarPlot($datay2);
$p1->SetFillColor('orange@0.4');
$p2->SetFillColor('brown@0.4');

$p1->SetLegend('Label 1');
$p2->SetLegend('Label 2');

$ybplot = new GroupBarPlot(array($p1,$bplotzero));
$y2bplot = new GroupBarPlot(array($bplotzero,$p2));

$graph->Add($ybplot);
$graph->AddY2($y2bplot);
// Output line
$graph->Stroke();
?>


