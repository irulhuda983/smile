<?php
header('Content-Type: application/json');
include('includes/connsql.php');

$con1 		=  $wskdkantor->execute(array('RequestInfo'=>
                            array('RequestID'=>'0',
								  'RequestSource'=>$chId,
								  'RequestDate'=>date('Y-m-d'),
								  'RequestUser'=>$_SESSION["USER"]), 'Input' => array('KdKantor' => '0')));
$getData 	= get_object_vars($con1);
if($getData['Status']->Status == 'SUCCESS'){
	//print_r($getData['Output']->ListMsKantor->MsKantor);
	$data = array(
		'mycombo' => $getData['Output']->ListMsKantor->MsKantor
	);
	echo json_encode($data);
} else {
	$getData['Status']->ErrorDescription;
}
?>