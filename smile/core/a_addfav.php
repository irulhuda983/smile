<?php
header('Content-Type: application/json');
include('includes/connsql.php');
$wsaddfav = new SoapClient(WSADDFAV, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
$username 	= $_SESSION['USER'];
//$role		= $_REQUEST["role"]; edit 19-10-15 robby
$role = explode('|',$_REQUEST['role']);
$regrole = $role[0];
$kdkantorrole = $role[1];
$menuid		= explode('?mid=',$_REQUEST["menuid"]);
//$menuid			= str_replace('FM-','', $menuid); // testing backup core tgl 14.05/2024 14.54
$com		= $_REQUEST["com"];
$con1 		=  $wsaddfav->process(array('RequestInfo'=>
					array('RequestID'=>$chId,
						  'RequestSource'=>$chId,
						  'RequestDate'=>date('Y-m-d'),
						  'RequestUser'=>$_SESSION["USER"]),
						  'Input' => array("AddMenuFavoriteList" => array("AddMenuFavorite" =>
						  			 array('KdFungsi'=>$regrole, 'KdMenu'=>$mnid,'KdUser'=>$username,'Status'=>$com)))));
$getData 	= get_object_vars($con1);
//print_r($getData);
if($getData['Status']->Status == 'SUCCESS'){
	echo '{"success": true, "pesan":"'.$regrole.'|'.$mnid.'|'.$username.'|'.$com.'"}';
} else {
	echo '{"success": false, "errors": "Tidak ada role yang dipilih!" }';
}
?>