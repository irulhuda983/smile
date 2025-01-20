<?php
session_start();
include('includes/connsql.php');
$wscom = new SoapClient(WSCOM, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
if(isset($_SESSION["USER"])){
	$con1 			=  $wscom->usrPassLogout(array('chId' => $chId, 'jsonData' => '{"KODE_USER":"'.$_SESSION["USER"].'", "LOGOUT_BY":"U"}'));
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