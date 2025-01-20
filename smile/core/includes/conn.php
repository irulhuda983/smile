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
$dbuser 	= 'KIT';
$dbpass		= 'CHANNEL7#';
$dbname		= '172.28.102.66:1521/jmsprod';
$dbhost		= 'localhost';
$dbtype		= 'oracle'; //mysql

$display_errors	= 1;
$log_errors	= 1;

global $root;
global $conn;

include('functions.php');
