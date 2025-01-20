<?php
require_once ("../includes/conf_global.php");

$version 	= "1.0";
$appname	= "Virtual Account System";
$tagline	= "BPJS Ketenagakerjaan";
$developer	= "http://www.jamsostek.co.id";
$copy		= "Copyright JAMSOSTEK 2013. All Rights Reserved";
$host		= "http://".$_SERVER["SERVER_NAME"]."/va";
$addhost	= "http://".$_SERVER["SERVER_NAME"]."/va/report/";
$reporthost	= "http://".$_SERVER["SERVER_NAME"]."/va/report/";
$path		= $_SERVER["DOCUMENT_ROOT"];

$display_errors	= 1;
$log_errors		= 1;

ini_set('soap.wsdl_cache_enabled',0);

global $root;
global $conn;

//$swdl = "http://172.28.101.51:2014/WSVirAcct/services/Main?wsdl";
// $swdl = "http://wsaxis.bpjsketenagakerjaan.go.id:2014/WSVirAcct/services/Main?wsdl";
//$swdl = "http://172.28.101.52:2014/WSVirAcct/services/Main?wsdl";
//$swdl = "http://ws-smile.bpjsketenagakerjaan.go.id/WSVirAcct/services/Main?wsdl";
//$swdl = "http://wsaxis.bpjsketenagakerjaan.go.id:2018/WSVirAcct/services/Main?wsdl";
$swdl = $CONFIG->WS_VIRACCT;
//$swdl = "http://172.28.200.82:2014/WSVirAcct/services/Main?wsdl";
//$swdl = "http://wstest.bpjsketenagakerjaan.go.id:2014/WSVirAcct/services/Main?wsdl";


$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";
$conn = new SoapClient($swdl, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
//$conn = new SoapClient($swdl);

include('functions.php');

?>
