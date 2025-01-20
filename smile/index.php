<?php
//=====================
//PAGE LIST
//=====================
if (!array_key_exists('name', $_REQUEST)) {
    header('Location: login.bpjs');
}
// ob_start(); // Memulai buffering
if($_REQUEST["name"] == "main"){
	include('p_main.php');

} else if($_REQUEST["name"] == "login"){
	include('p_login.php');	
	
} else if($_REQUEST["name"] == "logout"){
	include('p_logout.php');
	
} else if($_REQUEST["name"] == "form"){
	include('f.php');			
} else {
	header('Location: login.bpjs');
}
// ob_end_flush();
// exit;
?>
