<?
$pagetype="report";
$gs_pagetitle = "PN5029 - VIEW PEMBAYARAN KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_root_form = "pn5029.php";
$ls_current_form = "pn5029_view_pembayaran.php";
$ls_form_penetapan = "pn5029_view_pembayaran.php";

/*--------------------- Form History -------------------------------------------
File: pn5002_penetapan.php

Deskripsi:
-----------
File ini dipergunakan untuk view pembayaran klaim

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
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, status_klaim, kode_pointer_asal, id_pointer_asal, ".
			 	 "			 no_penetapan, nvl(a.status_submit_penetapan,'T') status_submit_penetapan, nvl(a.status_submit_pengajuan,'T') status_submit_pengajuan, ".
				 "			 case when substr(kode_tipe_klaim,1,3) = 'JPN' then ".
				 "			 			'JP'||a.kode_kantor||to_char(sysdate,'mmyyyy')||a.no_klaim ".
				 "			 else ".
				 "			 			substr(kode_tipe_klaim,1,3)||a.kode_kantor||to_char(sysdate,'mmyyyy')||a.no_klaim ".
				 "			 end set_no_penetapan ".
				 "from sijstk.pn_klaim a ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];
	$ls_jenis_klaim				= $row['JENIS_KLAIM'];
	$ls_no_penetapan			= $row['NO_PENETAPAN'];
	$ls_status_submit_penetapan	= $row['STATUS_SUBMIT_PENETAPAN'];
	$ls_status_submit_pengajuan	= $row['STATUS_SUBMIT_PENGAJUAN'];
	$ls_set_no_penetapan	= $row['SET_NO_PENETAPAN'];	
}

if ($ls_jenis_klaim=="JHT")
{
 	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM JAMINAN HARI TUA (JHT)";  	  	 
}else if ($ls_jenis_klaim=="JHM")
{
 	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM JHT/JKM";  	  	 
}else if ($ls_jenis_klaim=="JKK")
{
	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)";   	 
}else if ($ls_jenis_klaim=="JKM")
{
 	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM JAMINAN KEMATIAN (JKM)";  	  	 
}else if ($ls_jenis_klaim=="JPN")
{
 	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM JAMINAN PENSIUN (JP)";  	  	 
}else if ($ls_jenis_klaim=="JKP")
{
 	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM JAMINAN KEHILANGAN PEKERJAAN (JKP)";  	  	 
}else
{
 	$gs_pagetitle = "PN5029 - PEMBAYARAN KLAIM";
}

// ------------- end KLAIM PROFILE ---------------------------------------------

include "../ajax/pn5049_js.php";
include "../ajax/pn5049_actionbutton.php";

?>

<table class="captionfoxrm">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
</table>
		
<div id="formframe">
	<div id="formKiri" style="width:900px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
		<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
		<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="mid" name="mid" value="<?=$ls_mid;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
		<input type="hidden" id="btn_task" name="btn_task" value="">
		<input type="hidden" name="trigersubmit" value="0">

		<?
  	if ($_REQUEST["task"] == "View")
  	{			 	  	 
  	 	if(!empty($_GET['dataid']))
  		{
  		 	if ($ls_jenis_klaim=="JHT")
  			{
  			 	include "../ajax/pn5049_daftarklaim_jht.php"; 
  			}elseif ($ls_jenis_klaim=="JHM")
  			{
  				include "../ajax/pn5049_daftarklaim_jhm.php";									 
  			}elseif ($ls_jenis_klaim=="JKK")
  			{
  			 	include "../ajax/pn5049_daftarklaim_jkk.php";
  			}elseif ($ls_jenis_klaim=="JKM")
  			{
  			 	include "../ajax/pn5049_daftarklaim_jkm.php";			
  			}elseif ($ls_jenis_klaim=="JPN")
  			{
              include "../ajax/pn5049_daftarklaim_jpn.php";						 				
  			}elseif ($ls_jenis_klaim=="JKP")
  			{
              include "../ajax/pn5049_daftarklaim_jkp.php";						 				
  			}																  													 
  		}
  	}
  	?>
							
	</div> <!-- end formKiri -->
</div>	<!-- end formframe -->

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>
