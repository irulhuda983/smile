<?php
define("WSMENU2", "http://172.28.201.159:8100/soa-infra/services/core_sijstk_server1/Lov/ListMenuJsonMS_ep?WSDL");
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";
$wsmenu2 = new SoapClient(WSMENU2, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
?>
<style type="text/css">
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
}
</style>
<?PHP
	//if(isset($_REQUEST["kodeFungsi"])){
		$kode_fungsi  		= 1;
		$kode_menu  		= $_REQUEST["kodeMenu"];
		$con1 		=  $wsmenu2->execute(array('RequestInfo'=>
									array('RequestID'=>$chId,
										  'RequestSource'=>$chId,
										  'RequestDate'=>date('Y-m-d'),
										  'RequestUser'=>$_SESSION["USER"]),
										  'Input' => array('KdFungsi'=>$kode_fungsi, 'KdMenu'=>'%', 'KdMenuInduk'=>'%')));
		$getData 	= get_object_vars($con1);
		//print_r($getData);		
		
		if(count($getData['Output']->ListMenuJson->Menu) > 0){
			for ($i = 0; $i < count($getData['Output']->ListMenuJson->Menu); $i++) {
				$menu .= $getData['Output']->ListMenuJson->Menu[$i]->MnuJson."\n";
			}
			echo $menu;
		} 
	//}
?>