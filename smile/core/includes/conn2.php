<?php
$version 	= "1.0";
$appname	= "BPJS Ketenagakerjaan :: Iuran Online";
$tagline	= "BPJS Ketenagakerjaan";
$developer	= "http://www.jamsostek.co.id";
$copy		= "Copyright BPJS Ketenagakerjaan 2014. All Rights Reserved";
$port		= "";
$host		= "http://".$_SERVER["SERVER_NAME"].''.$port."/cekiuran"; 
$path		= $_SERVER["DOCUMENT_ROOT"];
$chId		= 'PTSP';
$reqId		= 'PTS001';

$display_errors	= 1;
$log_errors	= 1;
define("LINK_WS", "http://172.26.0.65:2014/WSPayment/services/Main?wsdl");


global $root;
global $conn;


// Add HTTP HEADER to pass IP FWD to WS By GoEnZ 03-03/2014 10:36
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";
$conn = new SoapClient(LINK_WS, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
?>
