<?php
//------------------------------------------------------------------------------
// Menu untuk daftar klaim (sentralisasi rekening)
// dibuat tgl : 20/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 							= "form";
$gs_kodeform 						= "PN5030";
$chId 	 	 							= "SMILE";
$gs_pagetitle 					= "DAFTAR KLAIM";
$gs_kantor_aktif				= $_SESSION['kdkantorrole'];
$gs_kode_user						= $_SESSION["USER"];
$gs_kode_role						= $_SESSION['regrole'];
// $task 									= $_POST["task"];
$task 									= !isset($_POST['task']) ? $_GET['task'] : $_POST['task'];
$kode_klaim 						    = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$task_detil							= $_POST["task_detil"];
$editid 								= $_POST['editid'];
$ls_kode_klaim 					= $_POST['kode_klaim'];
$ld_tmp_tglawaldisplay 	= $_POST['tmp_tglawaldisplay'];
$ld_tmp_tglakhirdisplay = $_POST['tmp_tglakhirdisplay'];
$ls_tmp_rg_kategori 		= $_POST['tmp_rg_kategori'];
$ls_activetab  					= $_POST['activetab'] == "" ? "1" : $_POST['activetab'];
$ld_tglawaldisplay 			= !isset($_POST['tglawaldisplay']) ? $_GET['tglawaldisplay'] : $_POST['tglawaldisplay'];
$ld_tglakhirdisplay 		= !isset($_POST['tglakhirdisplay']) ? $_GET['tglakhirdisplay'] : $_POST['tglakhirdisplay'];
$ls_rg_kategori					= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
$ls_rg_kategori					=	$ls_rg_kategori == "" ? "1" : $ls_rg_kategori;
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_kodeform;?> - <?= $gs_pagetitle;?></span></div>
  	<?php
  	if ($task == "")
  	{
  		?>
  		<div class="item" style="float: right; padding: 2px;">
  			<?
        switch($ls_rg_kategori)
        {
          case '1' : $sel1="checked"; break;
          case '2' : $sel2="checked"; break;
          case '3' : $sel3="checked"; break;
        }
        ?>
        <input type="radio" name="rg_kategori" value="1" onclick="filter();"  <?=$sel1;?>>&nbsp;<font  color="#ffffff">PENETAPAN KANTOR CABANG</font>&nbsp;
        <input type="radio" name="rg_kategori" value="2" onclick="filter();"  <?=$sel2;?>>&nbsp;<font  color="#ffffff">KANTOR CABANG LAIN</font> &nbsp;
        <input type="radio" name="rg_kategori" value="3" onclick="filter();"  <?=$sel3;?>>&nbsp;<font  color="#ffffff">SEMUA KANTOR</font>
  		</div>
			<?
		}
		?>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="order_by" name="order_by" value="">
			<input type="hidden" id="order_type" name="order_type" value="">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="task_detil" name="task_detil" value="<?=$task_detil;?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
			<input type="hidden" id="tmp_tglawaldisplay" name="tmp_tglawaldisplay" value="<?=$ld_tmp_tglawaldisplay;?>">
      <input type="hidden" id="tmp_tglakhirdisplay" name="tmp_tglakhirdisplay" value="<?=$ld_tmp_tglakhirdisplay;?>">
      <input type="hidden" id="tmp_rg_kategori" name="tmp_rg_kategori" value="<?=$ls_tmp_rg_kategori;?>">

			<?php
			if ($task == "view")
			{
			 	//action task edit, view -----------------------------------------------
				?>
				<div id="div_container" class="div-container">
					<div id="div_body" class="div-body">
            <ul id="nav" style="width:99%;">
              <li><a href="#tab1" id="tabid1" style="display:block"><span style="vertical-align: middle;" id="span_judul_tab1"></span></a></li>
              <li><a href="#tab2" id="tabid2" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab2"></span></a></li>
              <li><a href="#tab3" id="tabid3" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab3"></span></a></li>
							<li><a href="#tab4" id="tabid4" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab4"></span></a></li>
							<li><a href="#tab5" id="tabid5" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab5"></span></a></li>
							<li><a href="#tab6" id="tabid6" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab6"></span></a></li>
            </ul>

						<div style="display: none;" id="tab1" class="tab_konten">
							<div id="konten" style="width:98%;">
								<div id="formtab1a"></div>
								<div id="formtab1b"></div>
								<div id="formtab1c"></div>
								<div id="formtab1d"></div>
								<div id="formtab1e"></div>
								<div id="formtab1f"></div>
								<div id="formtab1g"></div>
								<div id="formtab1h"></div>
								<div id="formtab1i"></div>
								<div id="formtab1j"></div>
							</div>
						</div>

						<div style="display: none;" id="tab2" class="tab_konten">
							<div id="konten" style="width:98%;">
								<div id="formtab2a"></div>
								<div id="formtab2b"></div>
								<div id="formtab2c"></div>
								<div id="formtab2d"></div>
								<div id="formtab2e"></div>
							</div>
						</div>

						<div style="display: none;" id="tab3" class="tab_konten">
							<div id="konten" style="width:98%;">
								<div id="formtab3a"></div>
								<div id="formtab3b"></div>
								<div id="formtab3c"></div>
								<div id="formtab3d"></div>
								<div id="formtab3e"></div>
							</div>
						</div>

						<div style="display: none;" id="tab4" class="tab_konten">
							<div id="konten" style="width:98%;">
								<div id="formtab4a"></div>
								<div id="formtab4b"></div>
								<div id="formtab4c"></div>
								<div id="formtab4d"></div>
								<div id="formtab4e"></div>
							</div>
						</div>

						<div style="display: none;" id="tab5" class="tab_konten">
							<div id="konten" style="width:98%;">
								<div id="formtab5a"></div>
								<div id="formtab5b"></div>
								<div id="formtab5c"></div>
								<div id="formtab5d"></div>
								<div id="formtab5e"></div>
							</div>
						</div>

						<div style="display: none;" id="tab6" class="tab_konten">
							<div id="konten" style="width:98%;">
								<div id="formtab6a"></div>
								<div id="formtab6b"></div>
								<div id="formtab6c"></div>
								<div id="formtab6d"></div>
								<div id="formtab6e"></div>
							</div>
						</div>
					</div>

					<div id="div_footer" class="div-footer">
						<span id="span_button_utama" style="display:none;">
  						<div class="div-footer-form">
  							<div class="div-row">
									<span id="span_button_tutup" style="display:none;">
    								<div class="div-col">
    									<div class="div-action-footer">
    										<!-- <div class="icon">
    											<a id="btn_doBack2Grid" href="#" onClick="fl_js_doBack2Grid();">
    												<img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
    												<span>TUTUP</span>
    											</a>
    										</div> -->
    									</div>
    								</div>
									</span>
  							</div>
  						</div>

              <div style="padding-top:6px;width:99%;">
    						<div class="div-footer-content">
    							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
    							<li style="margin-left:15px;">Klik tombol <font color="#ff0000">X</font> pada pojok kanan atas untuk menutup form dan kembali ke menu utama..</li>
    						</div>
              </div>
						</span><!--end span_button_utama-->
					</div>
					<!--end div_footer-->
				</div>
				<?
				//end action task edit, view -------------------------------------------
			}
			?>
		</form>
	</div>
</div>

<script language="javascript">
	$(document).ready(function(){
        $('#task').val('<?= $task ?>');
        <?php if(!empty($kode_klaim)){ ?>
            $('#kode_klaim').val('<?= $kode_klaim ?>');
        <?php } ?>
	});
</script>

<?php
include "../ajax/pn5069_js.php";
include "../../includes/footer_app_nosql.php";
?>

