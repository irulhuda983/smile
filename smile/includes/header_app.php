<?php
include "../../mod_sc/sc_session.php";
require_once "../../includes/conf_global.php";
require_once "../../includes/fungsi.php";
include "../../includes/class_database.php"; 
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = isset($pagetitle) ? $pagetitle : $gs_pagetitle;
$gs_style = "style.new.css?ver=1.2";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/<?=$gs_style;?>" />
  <script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/appform.js"></script>
	<script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/common.new.js"></script>
    <script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
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

<form name="adminForm" id="adminForm" action="<?=$PHP_SELF;?>" method="post" enctype="multipart/form-data">
<?php 
if($pagetype=="popup")  // popup tidak ada action menu
{
?>
<div id="header-popup"><h3><?=$gs_pagetitle;?></h3></div>
<div id="container-popup">
<!--[if lte IE 6]><div id="clearie6"></div><![endif]-->
<?
}
elseif($pagetype=="report")
{

}
elseif($pagetype=="editdetil")
{
 
    include "../../includes/actionmenu.php"; 
}
else
{
    if(isset($gs_hideactionbar)){} else {
    	if(isset($mid)){
           include "../../includes/actionmenu.php"; 
        }
	}
}
//$task="new";
?>