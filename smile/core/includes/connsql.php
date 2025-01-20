<?php
$version 	= "1.0";
$appname	= "SIJSTK Core System";
$tagline	= "BPJS Ketenagakerjaan";
$developer	= "http://www.jamsostek.co.id";
$copy		= "Copyright BPJS Ketenagakerjaan 2015. All Rights Reserved";
$port		= "";
$host		= "http://172.26.0.60/core"; 
$coreform	= "http://172.26.0.60/core123";
$path		= $_SERVER["DOCUMENT_ROOT"];
$chId		= 'CORE';

$dbuser 	= 'CORE_SIJSTK';
$dbpass		= 'soa123';
$dbname		= '172.28.202.70:1521/dbdevelop';

$user["agent"] 	= $_SERVER['HTTP_USER_AGENT'];
$user["ip"] 	= getenv('HTTP_X_FORWARDED_FOR');

$display_errors = 0;
$log_errors	= 1;
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0'); 
//ini_set('session.gc_maxlifetime', 1);
//ini_set('session.cookie_lifetime',10);
function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
	$error['success'] = false;
    switch ($errno) {
    case E_USER_ERROR:
		$error['errors']['msg'] = "$errfile($errno) - $errstr";
		echo json_encode($error);
        exit(1);
        break;

    case E_USER_WARNING:
		$error['errors']['msg'] = "$errfile($errno) - $errstr";
		echo json_encode($error);
        break;

    case E_USER_NOTICE:
        $error['errors']['msg'] = "$errfile($errno) - $errstr";
		echo json_encode($error);
        break;

    default:
        $error['errors']['msg'] = "$errfile($errno) - $errstr";
		echo json_encode($error);
        break;
    }
    return true;
}
$old_error_handler = set_error_handler("DefaultGlobalErrorHandler");

global $root;
global $conn;

//define("WSCOM", "http://172.28.101.49:2014/WSCom/services/Main?wsdl");
define("WSCOM", "http://172.28.201.78:2014/WSCom/services/Main?wsdl");
define("WSMENUFAV", "http://172.28.201.159:8100/soa-infra/services/core_sijstk_server1/Lov/FavoriteMS_ep?WSDL");
define("WSNOTIFIKASI", "http://172.28.201.159:8100/soa-infra/services/default/Lov/NotifikasiMS_ep?WSDL");
//define("WSPATHFORM", "http://172.28.201.159:8100/soa-infra/services/default/Lov/GetPathMS_ep?WSDL");
//define("WSMENU2", "http://172.28.201.159:8100/soa-infra/services/core_sijstk_server1/Lov/ListMenuJsonMS_ep?WSDL");
define("WSMENU2", "http://172.28.201.159:8100/soa-infra/services/core_sijstk_server1/Lov/ListMenuJsonMS_ep?WSDL");
define("WSADDFAV", "http://172.28.201.159:8100/soa-infra/services/core_sijstk_server1/Lov/addmenufavorite_client_ep?WSDL");

// Add HTTP HEADER to pass IP FWD to WS By GoEnZ 03-03/2014 10:36
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";
/*
$wscom = new SoapClient(WSCOM, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
$wsmenufav = new SoapClient(WSMENUFAV, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
$wsnotifikasi = new SoapClient(WSNOTIFIKASI, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
$wspathform = new SoapClient(WSPATHFORM, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
$wsmenu2 = new SoapClient(WSMENU2, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
$wsaddfav = new SoapClient(WSADDFAV, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
*/
include('functions.php');
