<?php
// session_start();
if (session_status() == PHP_SESSION_NONE) {
	session_start();
 }
include('includes/connsql.php');
require_once "includes/class_database.php";
$DB = new Database($dbuser,$dbpass,$dbname); //connect to db
$wscom = new SoapClient(WSCOMUSER."?wsdl", array("location"=>WSCOMUSER,"exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));

if(isset($_SESSION["USER"])){
	//Set Logout Log
	//===
	
	$sql = "insert into sijstk.sc_audit_akses_form ( ".
				 "	kode_user, tgl_akses, kode_form, ".
				 "	inisial_fungsi, kantor_fungsi, ip_server, ". 
				 "	nama_app_server, nama_host, ip_client, ".
				 "	tgl_logout, status_logout, logout_by, ".
				 "	tgl_rekam, petugas_rekam) ".
							 "values ( ".
							 "	'".$_SESSION["USER"]."', sysdate, 'LOGOUT', ".
							 "	'".$_SESSION['regrole']."', '".$_SESSION['KDKANTOR']."', '".$_SERVER["SERVER_ADDR"]."', ".
							 "	'".$_SERVER["HTTP_HOST"]."', '".gethostname()."', '".$_SESSION['IP']."', ".
							 "	sysdate, 'Y', '".$_SESSION["USER"]."', ".
							 "	sysdate, 'SMILE'".
							 ")";								 

	$con1 			=  $wscom->usrPassLogout(array('chId' => $chId, 'jsonData' => '{"KODE_USER":"'.$_SESSION["USER"].'", "LOGOUT_BY":"U"}'));
	$getData 		= get_object_vars($con1);
	
	if($getData["return"]->ret == 0){
		$DB->parse($sql);
		$DB->execute();
		$DB->close();
	}
}

$_SESSION = array();

if (ini_get("session.use_cookies")) {
	$params = session_get_cookie_params();
	setcookie(session_name(), '', time() - 42000,
		$params["path"], $params["domain"],
		$params["secure"], $params["httponly"]
	);
}

// Finally, destroy the session.
session_unset();
session_destroy();
header("Location: index.php");

?>