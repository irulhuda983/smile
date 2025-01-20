<?php
session_start();
if($_SESSION["STATUS"] != "LOGIN"){
	echo "<script>window.location='/login.bpjs?error=Silahkan Login ulang';</script>";
}
include('includes/connsql.php');
//ini_set('display_errors', 1);
$wsnotifikasi = new SoapClient(WSNOTIFIKASI, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
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
	if(isset($_REQUEST["kodeFungsi"])){
		//$kode_fungsi  		= $_REQUEST["kodeFungsi"];
		$kode_fungsi  		= 1;
		$kode_menu  		= $_REQUEST["kodeMenu"];
		$con1 		=  $wsnotifikasi->execute(array('RequestInfo'=>
									array('RequestID'=>$chId,
										  'RequestSource'=>$chId,
										  'RequestDate'=>date('Y-m-d'),
										  'RequestUser'=>$_SESSION["USER"]),
										  'Input' => array('KdFungsi'=>$kode_fungsi, 'KdUser'=>$_SESSION["USER"])));
		$getData 	= get_object_vars($con1);
		//print_r($getData);		
		
		if(count($getData['Output']->ListMenu->Menu) > 0){
			$link = $getData['Output']->ListMenu->Menu->Path;
		} else {
			$link = 'notfound.bpjs';	
		}
		echo '<iframe src="'.$coreform.''.$link.'?mid='.$kode_menu.'" height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>';		
			/*
			for ($i = 0; $i < count($getData['Output']->ListMenu->Menu); $i++) {
				if($getData['Output']->ListMenu->Menu[$i]->KodeMenu == $kode_menu){
					if($getData['Output']->ListMenu->Menu[$i]->Path != ''){
						$link = $getData['Output']->ListMenu->Menu[$i]->Path;
					} else {
						$link = 'notfound.bpjs';
					}
					echo '<iframe src="'.$coreform.''.$link.'?mid='.$kode_menu.'" height="100%" width="100%" frameborder="0"  style="border:0; height:100%; width:100%;"></iframe>';
				}
			}
			*/
	}
?>