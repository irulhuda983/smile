<?php
include('includes/connsql.php');
$wscom = new SoapClient(WSCOM, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
if ($user["ip"]=='') $user["ip"] = getenv('REMOTE_ADDR');
if(isset($_REQUEST["login"])){
	$sestime = ini_get("session.gc_maxlifetime")/180;
	$con1 			=  $wscom->getLoginUsrPass(array('chId' => $chId, 'username' => strtoupper(trim($_REQUEST["login"])), 'password' => $_REQUEST["password"], 'avl' => '{"SESSION_HEADER":"'.$user["agent"].'","IP":"'.$user["ip"].'","CONCURRENT":"false", "SESSION_EXPIRED":"'.$sestime.'"}'));
	
	$getData 		= get_object_vars($con1);
	if($getData["return"]->ret == 0){
		$con1 			=  $wscom->getUserLoginInfo(array('chId' => $chId, 'kodeUser' => strtoupper(trim($_REQUEST["login"]))));
		$getData 		= get_object_vars($con1);
		if($getData["return"]->ret == 0){
			$response['success'] = true;
			$_SESSION['LOGIN_AT'] = time();
			$_SESSION["USER"]		= strtoupper(trim($_REQUEST["login"]));
			$_SESSION['NAMA'] 		= $getData["return"]->namaUser;
			$_SESSION['KDKANTOR']		= $getData["return"]->kodeKantor;
			$_SESSION['KANTOR']		= $getData["return"]->namaKantor;
			$_SESSION['NPK']		= $getData["return"]->npk;
			$_SESSION['EMAIL']		= $getData["return"]->email;
			$_SESSION['IP']			= $_SERVER['REMOTE_ADDR'];
			$_SESSION["STATUS"] 	= "LOGIN";
		} else {
			$response['success'] = false;
			$error = $getData["return"]->msg;
		}
	} else {
		$response['success'] = false;
		$error = $getData["return"]->msg;
	}
} else {
	$response['success'] = false;
	$error = 'User/Password harus diisi';
}
///$response['success'] = true;	
if ($response['success'] == false) {
	$response['errors']['msg'] = $error;
} else {
	$response['logininfo'] = array('redirect' => 'main.bpjs');
}
echo json_encode($response);
?>