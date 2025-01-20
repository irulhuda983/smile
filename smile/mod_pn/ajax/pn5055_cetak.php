<?php
$pagetype = "report";
$gs_pagetitle = "PN5055 - PENCETAKAN TANDA TERIMA";
require_once "../../includes/header_app.php";
include '../../includes/conf_global.php';
include '../../includes/fungsi_newrpt.php';

//include '../../includes/fungsi_rpt.php';

/* --------------------- Form History -----------------------------------------
  File: kb9001.php

  Deskripsi:
  -----------
  File ini dipergunakan untuk Pencetakan Laporan klaim

  Author:
  --------
  Tim SIJSTK

  Histori Perubahan:
  --------------------
  25/09/2017 - TIM SIJSTK
  Pembuatan Form

  -------------------- End Form History -------------------------------------- */

$ls_kode_klaim = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];

if (isset($_POST["st_dokumen_administrasi"])) {
    $ls_st_dokumen_administrasi = $_POST['st_dokumen_administrasi'];

    if ($ls_st_dokumen_administrasi == "on" || $ls_st_dokumen_administrasi == "ON" || $ls_st_dokumen_administrasi == "Y") {
        $ls_st_dokumen_administrasi = "Y";
    } else {
        $ls_st_dokumen_administrasi = "T";
    }
}

if (isset($_POST["btncetak"])) {
    /* ---------Kirim Parameter---------------------------------------------------- */
    $ls_user_param = "";
    $ls_user_param .= " QUSER='$username'";

    //CETAK KELENGKAPAN ADMINISTRASI ---------------------------------------------
    if ($ls_st_dokumen_administrasi == "Y") {
		
		$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
		$ls_nama_rpt = "PNR901001.rdf";
		
        //generate aktivitas klaim -----------------------------------------------
		$sql = "select nvl(max(no_urut),0)+1 as v_nourut from sijstk.pn_klaim_aktivitas " .
				"where kode_klaim = '$ls_kode_klaim' ";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$ln_no_urut = $row["V_NOURUT"];

		$sql = "insert into sijstk.pn_klaim_aktivitas ( " .
				"	kode_klaim, no_urut, kode_aktivitas, tgl_mulai, tgl_akhir, status_aktivitas, keterangan, tgl_rekam, petugas_rekam) " .
				"values ( " .
				"	'$ls_kode_klaim', '$ln_no_urut', 'UPDATE', sysdate, sysdate, 'TERBUKA', substr(upper('$ls_ket_submit'),1,300), sysdate, '$username' " .
				") ";
		$DB->parse($sql);
		$DB->execute();

		$sql = "update sijstk.pn_klaim_aktivitas a set status_aktivitas = 'SELESAI',tgl_akhir = sysdate,tgl_ubah = sysdate,petugas_ubah='$username' " .
				"where kode_klaim = '$ls_kode_klaim' " .
				"and no_urut in " .
				"( " .
				"     select max(no_urut) from sijstk.pn_klaim_aktivitas " .
				"     where kode_klaim = a.kode_klaim " .
				"     and no_urut < '$ln_no_urut' " .
				"     ) ";
		$DB->parse($sql);
		$DB->execute();
		//end generate aktivitas klaim -------------------------------------------
		//cetak laporan ----------------------------------------------------------
		$ls_modul = 'pn';
		$tipe = 'PDF';
		exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
    }

    $ls_user_param = "";
}

//--------------------- end button action ------------------------------------
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>

<script language="JavaScript">
    function fl_js_set_st_dokumen_administrasi()
    {
        var form = document.adminForm;
        if (form.st_dokumen_administrasi.checked)
        {
            form.st_dokumen_administrasi.value = "Y";
        } else
        {
            form.st_dokumen_administrasi.value = "T";
        }
    }		

</script>		
<?	
//--------------------- end fungsi lokal javascript --------------------------	
?>

<div id="header-popup">	
    <h3><?= $gs_pagetitle; ?></h3>
</div>

<div id="container-popup">
    <!--[if lte IE 6]>
    <div id="clearie6"></div>
    <![endif]-->	

    <?	
    //Nilai Default --------------------------------------------------------------													
    //End Nilai Default ----------------------------------------------------------
    ?>				
    <div id="formframe" style="width:300px;">
        <span id="dispError1" style="display:none;color:red;width:250px;"></span>
        <input type="hidden" id="st_errval1" name="st_errval1">
        <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?= $ls_kode_klaim; ?>">
        <div id="formKiri" style="width:300px;">
            <fieldset style="width:300px;"><legend>Jenis Laporan</legend>	
				<div class="form-row_kiri">
                      <!-- <label style = "text-align:right;">&nbsp;</label>						 -->
                      <? $ls_st_dokumen_administrasi = isset($ls_st_dokumen_administrasi) ? $ls_st_dokumen_administrasi : "T";?>					
                      <input type="checkbox" id="st_dokumen_administrasi" name="st_dokumen_administrasi" class="cebox" onclick="fl_js_set_st_dokumen_administrasi();" <?= $ls_st_dokumen_administrasi == "Y" || $ls_st_dokumen_administrasi == "ON" || $ls_st_dokumen_administrasi == "on" ? "checked" : ""; ?>>
                      <i><font color="#009999"><b>Tanda Terima Dokumen Kelengkapan Administrasi</b></font></i>	
                  </div>											
                  <div class="clear"></div>
            </fieldset>
            </br>
			<fieldset style="width:300px;text-align:center;"><legend></legend>
				<input type="submit" class="btn green" id="btncetak" name="btncetak" value="       CETAK LAPORAN       " title="Klik Untuk Cetak Laporan">
			</fieldset>

            <?
            if (isset($msg))		
            {
            ?>
            <fieldset style="width:250px;">
<?= $ls_error == 1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>"; ?>
<?= $ls_error == 1 ? "<font color=#ff0000>" . $msg . "</font>" : "<font color=#007bb7>" . $msg . "</font>"; ?>
            </fieldset>		
            <?
            }
            ?>

        </div>
    </div>			
    <div id="clear-bottom"></div>
    <?
    include "../../includes/footer_app.php";
    ?>