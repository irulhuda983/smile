<?php
$ip = $_SERVER['SERVER_ADDR']; 
$version        = "1.0";
$appname        = "SMILE - Sistem Informasi Perlindungan Pekerja (".substr($ip,11,2)." )";
$tagline	= "BPJS Ketenagakerjaan";
$developer	= "http://bpjsketenagakerjaan.go.id";
$copy		= "Copyright BPJS Ketenagakerjaan 2018. All Rights Reserved";
$port		= "";
$host		= "http://172.28.208.61/smile";
//$host		= "http://172.28.200.15:1802/smile";
//$coreform	= "http://172.28.200.15:1802/smile";
$coreform	= "http://172.28.208.61/smile";
$path		= $_SERVER["DOCUMENT_ROOT"];
$chId		= 'SMILE';

$dbuser  	= "sijstk";	
$dbpass  	= "jmo1";
$dbname		= "172.28.208.80:1521/dboltp";

$user["agent"] 	= $_SERVER['HTTP_USER_AGENT'];
$user["ip"] 	= getenv('HTTP_X_FORWARDED_FOR');

$display_errors = 0;
$log_errors	= 1;
ini_set('soap.wsdl_cache_enabled', '0'); 
ini_set('soap.wsdl_cache_ttl', '0');
ini_set('session.gc-maxlifetime', 30);

global $root;
global $conn;

// $wsIp = 'http://wsaxis.bpjsketenagakerjaan.go.id:2014';
//$wsIp = 'http://172.28.101.51:2014';
// $wsIp = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
$wsIp = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
//$wsIp = 'http://172.28.200.81:2014';

define("WSCOM", $wsIp."/WSCom/services/Main");
define("WSMENU2", $wsIp."/JSCoreSys/GetMenu/");
/* ip login */
$wsIpUser = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
define("WSCOMUSER", $wsIpUser."/WSCom/services/Main");

// Add HTTP HEADER to pass IP FWD to WS By GoEnZ 03-03/2014 10:36
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";

include('fungsi.php');
