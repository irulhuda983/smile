<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//------------------------------------------------------------------------------
// Menu untuk pembayaran klaim melalui sentralisasi rekening kantor pusat
// dibuat tgl : 13/08/2020
//------------------------------------------------------------------------------
//set parameter ----------------------------------------------------------------
$pagetype 									= "form";
$gs_kodeform 					 			= "PN5004";
$chId 	 	 			 			 			= "SMILE";
$gs_pagetitle 				 			= "PEMBAYARAN KLAIM SENTRALISASI";												 
$gs_kantor_aktif			 			= $_SESSION['kdkantorrole'];
$gs_kode_user					 			= $_SESSION["USER"];
$gs_kode_role					 			= $_SESSION['regrole'];
$task 								 			= $_POST["task"];
$editid 							 			= $_POST['editid'];
$ls_kode_klaim 				 			= $_POST['kode_klaim'];
$ln_no_konfirmasi 				 	= $_POST['no_konfirmasi'];
$ln_no_proses 				 			= $_POST['no_proses'];
$ls_kd_prg 				 					= $_POST['kd_prg'];
$ls_status_rekening_sentral	= "Y";
$ls_rg_jenis_pembayaran 		= !isset($_POST['rg_jenis_pembayaran']) ? $_GET['rg_jenis_pembayaran'] : $_POST['rg_jenis_pembayaran'];
$ls_rg_jenis_pembayaran			= $ls_rg_jenis_pembayaran=="" ? "LUMPSUM" : $ls_rg_jenis_pembayaran;
$ls_rg_status_siapbayar 		= !isset($_POST['rg_status_siapbayar']) ? $_GET['rg_status_siapbayar'] : $_POST['rg_status_siapbayar'];
$ls_rg_status_siapbayar 		= $ls_rg_status_siapbayar=="" ? "1" : $ls_rg_status_siapbayar;
$ls_rg_blthjt 							= !isset($_POST['rg_blthjt']) ? $_GET['rg_blthjt'] : $_POST['rg_blthjt'];
$ls_rg_blthjt 							= $ls_rg_blthjt=="" ? "B1" : $ls_rg_blthjt;
$ls_tmp_jenis_pembayaran 		= !isset($_POST['tmp_jenis_pembayaran']) ? $_GET['tmp_jenis_pembayaran'] : $_POST['tmp_jenis_pembayaran'];
$ls_tmp_status_siapbayar 		= !isset($_POST['tmp_status_siapbayar']) ? $_GET['tmp_status_siapbayar'] : $_POST['tmp_status_siapbayar'];
$ls_tmp_blthjt 							= !isset($_POST['tmp_blthjt']) ? $_GET['tmp_blthjt'] : $_POST['tmp_blthjt'];
$ld_tmp_tglawaldisplay 			= !isset($_POST['tmp_tglawaldisplay']) ? $_GET['tmp_tglawaldisplay'] : $_POST['tmp_tglawaldisplay'];
$ld_tmp_tglakhirdisplay 		= !isset($_POST['tmp_tglakhirdisplay']) ? $_GET['tmp_tglakhirdisplay'] : $_POST['tmp_tglakhirdisplay'];
$ls_activetab  				 			= $_POST['activetab'];
$ls_activetab 							= $ls_activetab=="" ? "2" : $ls_activetab;
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
 
<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
		
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>

<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_kodeform;?> - <?= $gs_pagetitle;?></span>	
			<input type="radio" id="rg_jenis_pembayaran_lumpsum" name="rg_jenis_pembayaran" value="LUMPSUM" onclick="fl_js_showHideRadioButtonSiapBayar();fl_js_load_form();" checked><span id='rg_jenis_pembayaran_lumpsum_label'>&nbsp;LUMPSUM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<input type="radio" id="rg_jenis_pembayaran_berkala" name="rg_jenis_pembayaran" value="BERKALA" onclick="fl_js_showHideRadioButtonSiapBayar();fl_js_load_form();"><span id='rg_jenis_pembayaran_berkala_label'>&nbsp;JP BERKALA</span>
		</div>
		<div class="item" style="float: right; padding: 2px;">		
			<input type="radio" id="rg_status_siapbayar1" name="rg_status_siapbayar" value="1" onclick="fl_js_set_status_siapbayar('1');" checked><span id='rg_status_siapbayar1_label'>&nbsp;<font color="#ffffff">VERIFIKASI</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_status_siapbayar3" name="rg_status_siapbayar" value="3" onclick="fl_js_set_status_siapbayar('3');"><span id='rg_status_siapbayar3_label'>&nbsp;<font color="#ffffff">PEMBAYARAN</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_status_siapbayar5" name="rg_status_siapbayar" value="5" onclick="fl_js_set_status_siapbayar('5');"><span id='rg_status_siapbayar5_label'>&nbsp;<font color="#ffffff">MONITORING</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <!--ditutup
			<input type="radio" id="rg_status_siapbayar4" name="rg_status_siapbayar" value="4" onclick="fl_js_set_status_siapbayar('4');"><span id='rg_status_siapbayar4_label'>&nbsp;<font color="#ffffff">RETUR</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
			-->
		</div>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div class="div-header-form">
		<div class="div-row">
			<div class="div-col">
				<input type="radio" id="rg_blthjt_b0" name="rg_blthjt" value="B0" onclick="fl_js_jpbklverif_filter();"><span id='rg_blthjt_b0_label'>&nbsp;<font color="#009999" size="1;" face="Arial,Verdana"><b>JATUH TEMPO S/D BLN LALU</b></font>&nbsp;&nbsp;</span>
				<input type="radio" id="rg_blthjt_b1" name="rg_blthjt" value="B1" onclick="fl_js_jpbklverif_filter();" checked><span id='rg_blthjt_b1_label'>&nbsp;<font color="#009999" size="1;" face="Arial,Verdana"><b>JATUH TEMPO BLN BERJALAN</b></font>&nbsp;&nbsp;</span>
      	<input type="radio" id="rg_blthjt_b2" name="rg_blthjt" value="B2" onclick="fl_js_jpbklverif_filter();"><span id='rg_blthjt_b2_label'>&nbsp;<font  color="#009999" size="1;" face="Arial,Verdana"><b>JATUH TEMPO BLN BERIKUTNYA</b></font>&nbsp;&nbsp;</span>
  			<input type="radio" id="rg_blthjt_b3" name="rg_blthjt" value="B3" onclick="fl_js_jpbklverif_filter();"><span id='rg_blthjt_b3_label'>&nbsp;<font  color="#009999" size="1;" face="Arial,Verdana"><b>SEMUA BULAN</b></font></span>
			</div>
		</div>
	</div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="order_by" name="order_by" value="">
      <input type="hidden" id="order_type" name="order_type" value="">
      <input type="hidden" id="tipe" name="tipe" value="">
      <input type="hidden" id="task_detil" name="task_detil" value="<?=$task_detil;?>">
      <input type="hidden" id="tmp_status_rekening_sentral" name="tmp_status_rekening_sentral" value="<?=$ls_status_rekening_sentral;?>">
			<input type="hidden" id="tmp_jenis_pembayaran" name="tmp_jenis_pembayaran" value="<?=$ls_tmp_jenis_pembayaran;?>">
			<input type="hidden" id="tmp_status_siapbayar" name="tmp_status_siapbayar" value="<?=$ls_tmp_status_siapbayar;?>">
			<input type="hidden" id="tmp_blthjt" name="tmp_blthjt" value="<?=$ls_tmp_blthjt;?>">
			<input type="hidden" id="tmp_tglawaldisplay" name="tmp_tglawaldisplay" value="<?=$ld_tmp_tglawaldisplay;?>">
			<input type="hidden" id="tmp_tglakhirdisplay" name="tmp_tglakhirdisplay" value="<?=$ld_tmp_tglakhirdisplay;?>">
			<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
			<input type="hidden" id="no_proses" name="no_proses" value="<?=$ln_no_proses;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
			
			<div id="div_container" class="div-container"></div>
		</form>
	</form>
</div>

<?php
include "../ajax/pn5063_js.php";
include "../../includes/footer_app_nosql.php";
?>

