<?
$pagetype="report";
$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_root_form = "pn5003.php";
$ls_current_form = "pn5003_approval.php";

/*--------------------- Form History -------------------------------------------
File: pn5003_approval.php

Deskripsi:
-----------
File ini dipergunakan untuk approval klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
24/07/2017 - Tim SIJSTK
Pembuatan Form
-------------------- End Form History ----------------------------------------*/

// ------------- KLAIM PROFILE ------------------------------------------------
$ls_kode_klaim	 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_level	 	 = !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];
$ls_root_sender  = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 			 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_mid 			 	 = !isset($_GET['mid']) ? $_POST['mid'] : $_GET['mid'];
$ls_task 				 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_activetab  	 = !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];
if ($ls_activetab=="")
{
 $ls_activetab = "2";
}

if ($ls_kode_klaim!="")
{
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];
	$ls_jenis_klaim				= $row['JENIS_KLAIM'];
	
	//ambil level maksimum -----------------------------------------------------
  $sql = "select max(no_level) into v_max_level from sijstk.pn_klaim_approval ".
  	 	 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ln_no_level_max = $row['V_MAX_LEVEL'];			
}

if ($ls_jenis_klaim=="JHT")
{
 	$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM JAMINAN HARI TUA (JHT)";  	  	 
}else if ($ls_jenis_klaim=="JHM")
{
 	$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM JHT/JKM";  	  	 
}else if ($ls_jenis_klaim=="JKK")
{
	$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)";	  	 
}else if ($ls_jenis_klaim=="JKM")
{
 	$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM KEMATIAN (JKM)";  	  	 
}else if ($ls_jenis_klaim=="JPN")
{
 	$gs_pagetitle = "PN5004 - PEMBAYARAN KLAIM PENSIUN (JP)";  	  	 
}

// ------------- end KLAIM PROFILE ---------------------------------------------

include "../ajax/pn5003_js.php";
include "../ajax/pn5003_actionbutton.php";

?>

<!--
<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>

<div id="container-popup">
<div id="clearie6"></div>
-->

<table class="captionfoxrm">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>
	
<div id="formframe">
	<div id="formKiri" style="width:900px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
		<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
		<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
		<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="mid" name="mid" value="<?=$ls_mid;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
		<input type="hidden" id="btn_task" name="btn_task" value="">
		<input type="hidden" name="trigersubmit" value="0">

		<?
    if ($ls_jenis_klaim=="JHT")
    {
     	include "../ajax/pn5042_approval_jht.php";	  	 
    }else if ($ls_jenis_klaim=="JHM")
    {
     	include "../ajax/pn5042_approval_jhm.php";  	  	 
    }else if ($ls_jenis_klaim=="JKK")
    {
     	include "../ajax/pn5042_approval_jkk.php";		  	 
    }else if ($ls_jenis_klaim=="JKM")
    {
     	include "../ajax/pn5042_approval_jkm.php";  	  	 
    }else if ($ls_jenis_klaim=="JPN")
    {
     	include "../ajax/pn5042_approval_jpn.php";  	 
    }
		?>

    <div id="buttonbox" style="width:930px; text-align:center;">

			<input type="button" class="btn green" id="btntutup" name="btntutup" onclick="window.close();"  value="            TUTUP          " />
			 
		</div>	<!--end buttonbox-->		

		<?
    if (isset($msg))		
    {
      ?>
        <fieldset style="width:930px;">
          <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
          <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
        </fieldset>		
      <?
    }
    ?>
							
	</div> <!-- end formKiri -->
</div>	<!-- end formframe -->

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>
