<?php
$pagetype = "form";
$gs_pagetitle = "Implementasi JKK RTW";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"]; 
//$gs_kode_segmen = "PU";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data potensi faskes/blk.
Hist: - 03/11/2017 : Update Form (Tim SIJSTK) - GW
-----------------------------------------------------------------------------*/
?>
<?php /*****LOCAL JAVASCRIPTS*************************************/ ?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<?php /*****end LOCAL JAVASCRIPTS********************************/ ?>
<?php /*****LOCAL CSS********************************************/ ?>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<style>
.errorField{border: solid #fe8951 1px !important;background: rgba(254, 145, 81, 0.24);}
.dataValid{background: #09b546;padding: 2px;color: #FFF;border-radius: 5px;}
input.file{box-shadow:0 0 !important;border:0 !important;}
input[disabled].file{background:#FFF !important;}
input.file::-webkit-file-upload-button {background: -webkit-linear-gradient(#5DBBF6, #2788E0);border: 1px solid #5492D6;border-radius:2px;color:#FFF;cursor:pointer;}
input[disabled].file::-webkit-file-upload-button {background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);border: 1px solid #ABABAB;cursor:no-drop;}
input.file::-webkit-file-upload-button:hover {background: linear-gradient(#157fcc, #2a6d9e);}
input[disabled].file::-webkit-file-upload-button:hover {background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);}
#mydata th {border-right: 1px solid silver; border-bottom: 0.5pt solid silver !important;border-top: 0.5pt solid silver !important;text-align: center !important;}
#listdata td {text-align: left !important;}
.dataTables_length {margin-bottom: 10px;}
.dataTables_wrapper{position: relative;clear: both;zoom: 1;background: #ebebeb;padding-top: 10px;padding-bottom: 5px;border: 1px solid #dddddd;}
#mylistdata_wrapper thead tr th {padding-top: 2px;padding-bottom: 2px;}
#mydata td {font-size: 10px;text-align: center;border-right: 0px solid rgb(221, 221, 221);border-bottom: 1px solid rgb(221, 221, 221);padding-top: 2px;padding-bottom: 2px;}
#mydata {text-align: center;}
#simple-table{font-size:11px;font-weight:normal;}
#simple-table>tbody>tr>td{font-size:11px;font-weight:normal;text-align:left;}
/* Gw - 03/11/2017 */
.btn_g  {border: 1px solid #5492D6;color:#FFFFFF !important;/*cursor: pointer;*/padding: 2px 5px;font-size: 10px;font-family: verdana, arial, tahoma, sans-serif;
  border-radius: 2px;background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);background: -o-linear-gradient(#5DBBF6, #2788E0); 
  background: -moz-linear-gradient(#5DBBF6, #2788E0);background: linear-gradient(#5DBBF6, #2788E0);margin-left:3px;}
.btn_g:hover{background:#C0FFFF;color:#000000 !important;}
</style>
<?php /*****end LOCAL CSS****************************************/ ?>
<?php /*****VALIDATOR & AJAX*************************************/ ?>
<script type="text/javascript" src="../../javascript/validator.js"></script>
<script type="text/javascript" src="../../javascript/ajax.js"></script>
<script type="text/javascript">
//Create validator object
var validator = new formValidator();
var ajax = new sack();
var dataid ='';
window.dataid='';
</script>
<?php /*****end VALIDATOR & AJAX*********************************/ ?>
<?php /*****LOCAL GET/POST PARAMETER*****************************/ 
$task_code=isset($_REQUEST['task'])?$_REQUEST['task']:'';
if($task_code == "New")
{			 										 			
}else
{								 		
}	
      /*****end LOCAL GET/POST PARAMETER*************************/ ?>
<?php /*****ACTION MENU******************************************/  
require_once("pn5032_action_menu.php");  
      /*****end ACTION MENU**************************************/ ?>
<?php /*****TASK CONTENT*****************************************/ 
if($task_code == "New")
{		
    require_once("pn5032_form.php");	 										 			
}else if($task_code == "Edit")
{      
    require_once("pn5032_form.php");                                            
}else if($task_code == "View")
{      
    require_once("pn5032_form.php");                                            
} else
{
  require_once("pn5032_view.php");
}	   
      /*****end TASK CONTENT*************************************/ ?>
<?php
include "../../includes/footer_app_nosql.php";
?>