<?php
$pagetype = "form";
$gs_pagetitle = "Fasilitas Kesehatan/ BLK";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"]; 
$php_file_name="pn2403";
//print_r($_SESSION);
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
.gw_center{text-align:center}
/* Gw - 03/11/2017 */
.btn_g  {border: 1px solid #5492D6;color:#FFFFFF !important;/*cursor: pointer;*/padding: 2px 5px;font-size: 10px;font-family: verdana, arial, tahoma, sans-serif;
    border-radius: 2px;background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);background: -o-linear-gradient(#5DBBF6, #2788E0); 
    background: -moz-linear-gradient(#5DBBF6, #2788E0);background: linear-gradient(#5DBBF6, #2788E0);margin-left:3px;}
  .btn_g:hover{background:#C0FFFF;color:#000000 !important;}
  .gw_tr {cursor:pointer;}
  .gw_tr:hover {background-color:#CADDE6;}
  .gw_btn {
      cursor:pointer;
    background: #3b8fc7;
    background-image: -webkit-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: -moz-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: -ms-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: -o-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: linear-gradient(to bottom, #3b8fc7, #2980b9);
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    font-family: Arial;
    color: #ffffff;
    font-size: 12px;
    padding: 2px 4px 2px 4px;
    text-decoration: none;
  }
  
  .gw_btn:hover {
    background: #3cb0fd;
    background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
    background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
    text-decoration: none;
  }
  .gw_btn_noaktif {
      background: #abbcc7;
      background-image: -webkit-linear-gradient(top, #abbcc7, #6f8896);
      background-image: -moz-linear-gradient(top, #abbcc7, #6f8896);
      background-image: -ms-linear-gradient(top, #abbcc7, #6f8896);
      background-image: -o-linear-gradient(top, #abbcc7, #6f8896);
      background-image: linear-gradient(to bottom, #abbcc7, #6f8896);
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      font-family: Arial;
      color: #ffffff;
      font-size: 12px;
      padding: 2px 4px 2px 4px;
      text-decoration: none;
  }
  .gw_tab_title {
    background: #3498db;
    -webkit-border-top-right-radius: 10px;
    -moz-border-radius-top-right: 10px;
    border-top-right-radius: 10px;
    -webkit-border-top-left-radius: 5px;
    -moz-border-radius-top-left: 5px;
    border-top-left-radius: 5px;
    margin-bottom:0px;
    padding:5px 10px;
    font-size:12px;
    font-weight:bold;
    color:#ffffff;
  }
  .gw_tab_konten{
      min-width:800px;
      margin-top:5px;
      border:1px solid #3498db;
      padding:10px;
      background-color:#FcFcFc;
  }
  .gw_tbl{border-collapse: collapse;}
  .gw_tbl table, .gw_tbl th, .gw_tbl td {border: 1px solid #C0C0C0;}
  .gw_tbl tr:nth-child(even) {background-color: #ebebeb;}
  
  
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
$task_no=isset($_REQUEST['taskno'])?$_REQUEST['taskno']:'Faskes';
$dataid=isset($_REQUEST['dataid'])?$_REQUEST['dataid']:'';
$task_no=$task_code==''?'Faskes':$task_no;
$tab_title = array(
    "Faskes"=>"Approval Perubahan Status Fasilitas Kesehatan/ BLK",
    "IKS"=>"Data IKS",
    "Lamp"=>"Lampiran IKS"  
);
      /*****end LOCAL GET/POST PARAMETER*************************/ ?>

<div id="actmenu" style="padding-top:10px;">
    <div id="actbutton">
    <div style="float:left;">
        <?php if($task_code=='View') { ?>
        <div style="float:left;"><div class="icon">
            <a id="btn_close" href="<?=$php_file_name;?>.php?taskno=<?=$task_no;?>&mid=<?=$mid;?>&s=<?=$_GET['s'];?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
        </div></div>
        <?php } ?>
    </div>
    </div>
</div><br />
<?php 
if($task_code == "View") {
    $new=false;
    $url2="{$php_file_name}.php?task={$task_code}&dataid={$dataid}&taskno=";
    $btn1_aktif=$task_no=="Faskes"?"gw_btn_noaktif":"gw_btn";
    $btn2_aktif=$task_no=="IKS"?"gw_btn_noaktif":"gw_btn";
    $btn3_aktif=$task_no=="Lamp"?"gw_btn_noaktif":"gw_btn";

    $btn1_disable=$task_no=="Faskes"?"disabled":"gw_tn";
    $btn2_disable=$task_no=="IKS"?"disabled":"";
    $btn3_disable=$task_no=="Lamp"?"disabled":"";
?>
    <div align="left">
        <input type="button" id="myBtn1" class="<?=$btn1_aktif;?>" value="Data Faskes/ BLK" onclick="window.location='<?=$url2;?>Faskes';" <?=$btn1_disable;?> />
        <input type="button" id="myBtn2" class="<?=$btn2_aktif;?>" value="Data IKS"  onclick="window.location='<?=$url2;?>IKS';" <?=$btn2_disable;?> />
        <input type="button" id="myBtn3" class="<?=$btn3_aktif;?>" value="Dokumen IKS"  onclick="window.location='<?=$url2;?>Lamp';" <?=$btn3_disable;?> />
    </div>
    <br />
<?php }?>

<div>
<span class="gw_tab_title"  id="t1" ><?=$tab_title[$task_no];?></span>
</div>
<div class="gw_tab_konten">
    <div id="tab1" class="f_0" > 
    <?php /*****TASK CONTENT*****************************************/ 
if($task_code == "View")
{		
    require_once("{$php_file_name}_form.php");	 										 			
}else
{
  require_once("{$php_file_name}_view.php");
}	   
      /*****end TASK CONTENT*************************************/ ?>

    </div>
</div>
<script language="javascript">
var skey='<?=$_GET['s'];?>';
$(document).ready(function(){
    
});
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>