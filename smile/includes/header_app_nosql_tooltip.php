<?php
include "../../mod_sc/sc_session.php";
require_once "../../includes/conf_global.php";
require_once "../../includes/fungsi.php";
include "../../includes/class_database.php"; 
//$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$pagetitle = isset($pagetitle) ? $pagetitle : $gs_pagetitle;
$gs_style = "style.new.css?ver=1.2";

//Set Form Log
// Add by Robby Mar 15, 2017 22:57
//===========================================
$KD_FORM = explode('/',$_SERVER['REQUEST_URI']);
$KD_FORM = explode('.',$KD_FORM[4]);
$KD_FORM = strtoupper($KD_FORM[0]);
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName); //connect to db
$sql = "insert into sijstk.sc_audit_akses_form ( ".
       "	kode_user, tgl_akses, kode_form, ".
       "	inisial_fungsi, kantor_fungsi, ip_server, ". 
       "	nama_app_server, nama_host, ip_client, ".
       "	tgl_rekam, petugas_rekam) ".
			 "values ( ".
			 "	'".$_SESSION["USER"]."', sysdate, '".$KD_FORM."', ".
			 "	'".$_SESSION['regrole']."', '".$_SESSION['kdkantorrole']."', '".$_SERVER['HTTP_HOST']."', ".
			 "	'".$_SERVER['HTTP_HOST']."', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['REMOTE_ADDR']."' ".
			 "	sysdate, 'SIJSTK' ".
			 ")";			 					
$DB->parse($sql);
$DB->execute();
//========================
//ENDSet Form Log
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/<?=$gs_style;?>" />
  <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/jquery-ui.min.css" />
  <script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/appform.js"></script>
    <script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/common.new.js"></script>
    <script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
    <script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){	
	setTimeout(function(){
		$('#loading').hide();
		$('#loading-mask').hide();
	}, 250);
});
function alertError(msg){
	window.parent.Ext.MessageBox.show({
	   title: 'Perhatian',
	   msg: msg,
	   buttons: window.parent.Ext.MessageBox.OK,
	   icon: 'x-message-box-error'
   });	
}
function alert(msg){
	window.parent.Ext.MessageBox.alert('Perhatian', msg);
}
function alertMsg(msg){
	window.parent.Ext.MessageBox.alert('Informasi', msg);
}
</script>
</head>
<?php
if((isset($task)) && ($task=="print")) { ?>
<body class="appcontent" onload="window.print();window.close()">
<?php } else { ?>
<body class="appcontent">
<?php } ?>