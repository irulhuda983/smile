<?
$pagetype="report";
$gs_pagetitle = "PN5001 - DAFTAR CATATAN VERIFIKASI KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5040_daftaraktivitas.php

Deskripsi:
-----------
File ini dipergunakan untuk daftar aktivitas klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
28/07/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_sender 			= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 	= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionentry">
<tr> 
<td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
</tr>
</table>

<table class="captionform">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
  </style>
  <tr>
		<td id="header-caption2" colspan="3">
  		<h3><?=$gs_pagetitle;?></h3>
		</td>
	</tr>	
  <tr><td colspan="3"></td></tr>	
</table>
	
<div id="formframe">
  <div id="formKiri" style="width:880px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">		
	<?php 
	if ($ls_kode_klaim != "")
	{		
		$sql = "select keterangan_wawancara,keterangan_approval, petugas_approval, to_char(tgl_approval,'dd-mm-yyyy') tgl_approval from antrian.atr_booking_hist 
				where kode_klaim_approval = '$ls_kode_klaim'
				and rownum=1
				union
				select keterangan_wawancara,keterangan_approval, petugas_approval, to_char(tgl_approval,'dd-mm-yyyy') tgl_approval from bpjstku.vas_klaim_hist 
				where kode_klaim_approval = '$ls_kode_klaim'
				and rownum=1
				union
				select keterangan keterangan_wawancara,'' keterangan_approval, petugas_rekam petugas_approval, to_char(tgl_rekam,'dd-mm-yyyy') tgl_rekam from pn.pn_klaim_catatan@to_kn
				where kode_klaim = '$ls_kode_klaim'
				and kode_catatan = '1' 
				and rownum=1
				";								               
			//echo $sql;
			$ECDB->parse($sql);
			$ECDB->execute();
			$row = $ECDB->nextrow();
			$ls_keterangan_wawancara = $row["KETERANGAN_WAWANCARA"];
			$ls_keterangan_approval = $row["KETERANGAN_APPROVAL"];
			$ls_kode_user_cso = $row["PETUGAS_APPROVAL"];
			$ls_tgl_proses_cso = $row["TGL_APPROVAL"];
			
		// kode catatan = 2 -> PMP	
		$sql_pmp = "select keterangan, petugas_rekam, to_char(tgl_rekam,'dd-mm-yyyy') tgl_rekam from pn.pn_klaim_catatan 
				where kode_klaim = '$ls_kode_klaim'
				and kode_catatan = '2' 
				and rownum=1
				";								               
			//echo $sql_pmp;
			$DB->parse($sql_pmp);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_hasil_elaborasi = $row["KETERANGAN"];
			$ls_kode_user_pmp = $row["PETUGAS_REKAM"];
			$tgl_proses_pmp = $row["TGL_REKAM"];	
	}
	?>
	<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetMataAnggaran2" width="880px">
          <legend>Catatan Verifikasi oleh Customer Service (CSO)</legend>
          <div class="row">
            <div>
				<div class="l_frm"><label for="lab_kode_user_cso">Nama/Kode User: </label></div>
				<div class="r_frm">
					<input type="text" id="kode_user_cso" name="kode_user_cso" value="<?php echo $ls_kode_user_cso; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
				<div class="l_frm"><label for="lab_tgl_proses_cso">Tanggal Proses: </label></div>
				<div class="r_frm">
					<input type="text" id="tgl_proses_cso" name="tgl_proses_cso" value="<?php echo $ls_tgl_proses_cso; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
				<br>
				<div class="l_frm"><label for="lab_keterangan_wawancara">Hasil Wawancara:</label></div>
				<div class="r_frm">
					<textarea id="keterangan_wawancara" name="keterangan_wawancara" style="width : 100%; color:#000;" readonly disabled><?php echo $ls_keterangan_wawancara; ?></textarea>
				</div>
				<div class="clear"></div>
				<div class="l_frm"><label for="lab_keterangan">Keterangan Persetujuan:</label></div>
				<div class="r_frm">
					<textarea id="keterangan" name="keterangan" style="width : 100%; color:#000;" readonly disabled><?php echo $ls_keterangan_approval; ?></textarea>
				</div>
				<div class="clear"></div>
				<div class="l_frm">
					<label for=""></label></div>
				<div class="r_frm">
				</div>
            </div>
          </div>
    </fieldset>
	<br><br>
	<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetMataAnggaran2" width="880px">
          <legend>Catatan Verifikasi oleh Penata Madya Pelayanan (PMP)</legend>
          <div class="row">
            <div>
				<div class="l_frm"><label for="lab_kode_user_pmp">Nama/Kode User: </label></div>
				<div class="r_frm">
					<input type="text" id="kode_user_pmp" name="kode_user_pmp" value="<?php echo $ls_kode_user_pmp; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
				<div class="l_frm"><label for="lab_tgl_proses_pmp">Tanggal Proses: </label></div>
				<div class="r_frm">
					<input type="text" id="tgl_proses_pmp" name="tgl_proses_pmp" value="<?php echo $tgl_proses_pmp; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
				<br>
				<div class="l_frm"><label for="lab_hasil_elaborasi">Hasil Elaborasi:</label></div>
				<div class="r_frm">
					<textarea id="hasil_elabarasi" name="hasil_elaborasi" style="width : 100%; color:#000;" readonly disabled><?php echo $ls_hasil_elaborasi; ?></textarea>
				</div>
				<div class="clear"></div>
            </div>
          </div>
    </fieldset>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

