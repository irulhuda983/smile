<?
$pagetype="report";
$gs_pagetitle = "PN5002 - RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5002_penetapanmanfaat_jht.php

Deskripsi:
-----------
File ini dipergunakan untuk penetapan manfaat jht

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
28/07/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_pointer_asal  = !isset($_GET['kode_pointer_asal']) ? $_POST['kode_pointer_asal'] : $_GET['kode_pointer_asal'];
$ls_id_pointer_asal  = !isset($_GET['id_pointer_asal']) ? $_POST['id_pointer_asal'] : $_GET['id_pointer_asal'];
$ls_kode_realisasi	 = !isset($_GET['kode_realisasi']) ? $_POST['kode_realisasi'] : $_GET['kode_realisasi'];
$ls_kode_manfaat	 	 = !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_form_penetapan	 = !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 					 	 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ln_no_level				 = !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];

if ($ls_kode_klaim!="")
{
  $sql = "select nvl(flag_partial,'T') flag_partial, kode_sebab_klaim, nvl(status_submit_penetapan,'T') status_submit_penetapan, kode_klaim_induk ".
			 	 "from sijstk.pn_klaim a ".
         "where a.kode_klaim = '$ls_kode_klaim'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status_submit_penetapan	= $row['STATUS_SUBMIT_PENETAPAN'];
	$ls_kode_sebab_klaim				= $row['KODE_SEBAB_KLAIM'];
	$ls_flag_partial						= $row['FLAG_PARTIAL'];
	$ls_kode_klaim_induk				= $row['KODE_KLAIM_INDUK'];
}
	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat from sijstk.pn_kode_manfaat a ".
         "where a.kode_manfaat = '$ls_kode_manfaat'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat	= $row['NAMA_MANFAAT'];
	
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat;	 		  	 
}

if($btn_task=="hitung_manfaat")
{
  //cek kelayakan ----------------------------------------------------------
	$qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNFULANG_JHTRINCI('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	
  
  $msg = "Proses Hitung Manfaat selesai, session dilanjutkan...";
  $task = "edit";   		
  
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid');";
  echo "alert('$msg')";
  echo "</script>";		
}

//query data penetapan sebelumnya ----------------------------------------------
$sql = "select 
          kode_klaim, kode_manfaat, no_urut, kategori_manfaat, kode_tipe_penerima, kd_prg, nom_biaya_disetujui, 
          pengambilan_ke, nom_manfaat_sudahdiambil, nom_diambil_thnberjalan, nom_pengembangan_estimasi, 
          tgl_pengembangan_estimasi, to_char(tgl_pengembangan,'dd/mm/yyyy') tgl_pengembangan, 
					to_char(tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun, 
          nom_saldo_awaltahun, nom_saldo_pengembangan, nom_saldo_total, 
          nom_iuran_thnberjalan, nom_iuran_pengembangan, nom_iuran_total,nom_saldo_iuran_total, 
          persentase_pengambilan, nom_manfaat_maxbisadiambil, nom_manfaat_diambil, 
          nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
          nom_ppn, nom_pph, nom_pembulatan, nom_manfaat_netto, kode_pajak_ppn, kode_pajak_pph, 
          keterangan
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = '$ls_kode_klaim_induk'
        and kode_manfaat = '18'
        and no_urut = 1 ";
//echo $sql;				
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_induk_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_induk_no_urut 	 				 				= $row["NO_URUT"];
$ls_induk_kategori_manfaat					= $row["KATEGORI_MANFAAT"];
$ls_induk_kode_tipe_penerima				= $row["KODE_TIPE_PENERIMA"];
$ls_induk_kd_prg										= $row["KD_PRG"];
$ln_induk_nom_biaya_disetujui				= $row["NOM_BIAYA_DISETUJUI"];
$ln_induk_pengambilan_ke						= $row["PENGAMBILAN_KE"];
$ln_induk_nom_manfaat_sudahdiambil	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];
$ln_induk_nom_diambil_thnberjalan		= $row["NOM_DIAMBIL_THNBERJALAN"];
$ln_induk_nom_pengembangan_estimasi	= $row["NOM_PENGEMBANGAN_ESTIMASI"];	
$ld_induk_tgl_pengembangan_estimasi	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ld_induk_tgl_pengembangan					= $row["TGL_PENGEMBANGAN"];
$ld_induk_tgl_saldo_awaltahun			= $row["TGL_SALDO_AWALTAHUN"];
$ln_induk_nom_saldo_awaltahun				= $row["NOM_SALDO_AWALTAHUN"];
$ln_induk_nom_saldo_pengembangan		= $row["NOM_SALDO_PENGEMBANGAN"];
$ln_induk_nom_saldo_total						= $row["NOM_SALDO_TOTAL"];
$ln_induk_nom_iuran_thnberjalan			= $row["NOM_IURAN_THNBERJALAN"];
$ln_induk_nom_iuran_pengembangan		= $row["NOM_IURAN_PENGEMBANGAN"];
$ln_induk_nom_iuran_total						= $row["NOM_IURAN_TOTAL"];
$ln_induk_nom_saldo_iuran_total			= $row["NOM_SALDO_IURAN_TOTAL"];
$ln_induk_persentase_pengambilan		= $row["PERSENTASE_PENGAMBILAN"];
$ln_induk_nom_manfaat_maxbisadiambil	= $row["NOM_MANFAAT_MAXBISADIAMBIL"];
$ln_induk_nom_manfaat_diambil				= $row["NOM_MANFAAT_DIAMBIL"];	
$ln_induk_nom_manfaat_utama					= $row["NOM_MANFAAT_UTAMA"];
$ln_induk_nom_manfaat_tambahan			= $row["NOM_MANFAAT_TAMBAHAN"];
$ln_induk_nom_manfaat_gross					= $row["NOM_MANFAAT_GROSS"];
$ln_induk_nom_ppn										= $row["NOM_PPN"];
$ln_induk_nom_pph										= $row["NOM_PPH"];
$ln_induk_nom_pembulatan						= $row["NOM_PEMBULATAN"];
$ln_induk_nom_manfaat_netto					= $row["NOM_MANFAAT_NETTO"];
$ls_induk_kode_pajak_ppn						= $row["KODE_PAJAK_PPN"];
$ls_induk_kode_pajak_pph						= $row["KODE_PAJAK_PPH"];
$ls_induk_keterangan								= $row["KETERANGAN"];	

//query data penetapan ulang ----------------------------------------------
$sql = "select 
          kode_klaim, kode_manfaat, no_urut, kategori_manfaat, kode_tipe_penerima, kd_prg, nom_biaya_disetujui, 
          pengambilan_ke, nom_manfaat_sudahdiambil, nom_diambil_thnberjalan, nom_pengembangan_estimasi, 
          tgl_pengembangan_estimasi, to_char(tgl_pengembangan,'dd/mm/yyyy') tgl_pengembangan, 
					to_char(tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun, 
          nom_saldo_awaltahun, nom_saldo_pengembangan, nom_saldo_total, 
          nom_iuran_thnberjalan, nom_iuran_pengembangan, nom_iuran_total,nom_saldo_iuran_total, 
          persentase_pengambilan, nom_manfaat_maxbisadiambil, nom_manfaat_diambil, 
          nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
          nom_ppn, nom_pph, nom_pembulatan, nom_manfaat_netto, kode_pajak_ppn, kode_pajak_pph, 
          keterangan
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = '$ls_kode_klaim'
        and kode_manfaat = '18'
        and no_urut = 1 ";
//echo $sql;				
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_no_urut 	 				 				= $row["NO_URUT"];
$ls_kategori_manfaat					= $row["KATEGORI_MANFAAT"];
$ls_kode_tipe_penerima				= $row["KODE_TIPE_PENERIMA"];
$ls_kd_prg										= $row["KD_PRG"];
$ln_nom_biaya_disetujui				= $row["NOM_BIAYA_DISETUJUI"];
$ln_pengambilan_ke						= $row["PENGAMBILAN_KE"];
$ln_nom_manfaat_sudahdiambil	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];
$ln_nom_diambil_thnberjalan		= $row["NOM_DIAMBIL_THNBERJALAN"];
$ln_nom_pengembangan_estimasi	= $row["NOM_PENGEMBANGAN_ESTIMASI"];	
$ld_tgl_pengembangan_estimasi	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ld_tgl_pengembangan					= $row["TGL_PENGEMBANGAN"];
$ld_tgl_saldo_awaltahun			= $row["TGL_SALDO_AWALTAHUN"];
$ln_nom_saldo_awaltahun				= $row["NOM_SALDO_AWALTAHUN"];
$ln_nom_saldo_pengembangan		= $row["NOM_SALDO_PENGEMBANGAN"];
$ln_nom_saldo_total						= $row["NOM_SALDO_TOTAL"];
$ln_nom_iuran_thnberjalan			= $row["NOM_IURAN_THNBERJALAN"];
$ln_nom_iuran_pengembangan		= $row["NOM_IURAN_PENGEMBANGAN"];
$ln_nom_iuran_total						= $row["NOM_IURAN_TOTAL"];
$ln_nom_saldo_iuran_total			= $row["NOM_SALDO_IURAN_TOTAL"];
$ln_persentase_pengambilan		= $row["PERSENTASE_PENGAMBILAN"];
$ln_nom_manfaat_maxbisadiambil	= $row["NOM_MANFAAT_MAXBISADIAMBIL"];
$ln_nom_manfaat_diambil				= $row["NOM_MANFAAT_DIAMBIL"];	
$ln_nom_manfaat_utama					= $row["NOM_MANFAAT_UTAMA"];
$ln_nom_manfaat_tambahan			= $row["NOM_MANFAAT_TAMBAHAN"];
$ln_nom_manfaat_gross					= $row["NOM_MANFAAT_GROSS"];
$ln_nom_ppn										= $row["NOM_PPN"];
$ln_nom_pph										= $row["NOM_PPH"];
$ln_nom_pembulatan						= $row["NOM_PEMBULATAN"];
$ln_nom_manfaat_netto					= $row["NOM_MANFAAT_NETTO"];
$ls_kode_pajak_ppn						= $row["KODE_PAJAK_PPN"];
$ls_kode_pajak_pph						= $row["KODE_PAJAK_PPH"];
$ls_keterangan								= $row["KETERANGAN"];	

if ($ls_kode_manfaat =="")
{
 	 $ls_kode_manfaat	 	 = !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
}
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
<script language="javascript">
  function NewWindow4(mypage,myname,w,h,scroll){
    var openwin = window.parent.Ext.create('Ext.window.Window', {
      title: myname,
      collapsible: true,
      animCollapse: true,
      
      maximizable: true,
      width: w,
      height: h,
      minWidth: 450,
      minHeight: 250,
      layout: 'fit',
      html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    });
    openwin.show();
  }	
  
  function confirmDelete(delUrl) {
  	if (confirm("Are you sure you want to delete this record")) {
  	document.location = delUrl;
  	}
  }

  function doHitungManfaat() {
    var form = document.adminForm;

      form.btn_task.value="hitung_manfaat";
      form.submit();
  }

  function refreshParent() 
  {																						
    <?php	
    if($ls_form_penetapan!=''){			
    	echo "window.location.replace('$ls_form_penetapan?task=Edit&sender=pn5002.php&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim');";	
    }
    ?>	
  }
						
</script>
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionentry">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
  </style>
  <tr>
		<td id="header-caption2" colspan="3">
			<h3><?=$gs_pagetitle;?>&nbsp;&nbsp;</h3>    
		</td>
  </tr>
	<tr><td colspan="3"></br></td></tr>
</table>

<div id="formframe">
  <div id="formKiri" style="width:880px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>">
		<input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>">
		<input type="hidden" id="kode_realisasi" name="kode_realisasi" value="<?=$ls_kode_realisasi;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
		<input type="hidden" id="btn_task" name="btn_task" value="">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
		<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
		<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
		<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
						 
    <table id="mydata2" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
        <tr>
        	<td colspan="10">	
          <table width="960px" border="0">														
            <tr>		 
              <!-- Informasi Penetapan Klaim Sebelumnya ------------------------------->	
              <td width="50%" valign="top" align="center">
                <fieldset><legend><b><i><font color="#009999">Informasi Penetapan Klaim Sebelumnya</font></i></b></legend>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Pengambilan Sblmnya</label>
                    <input type="text" id="induk_nom_manfaat_sudahdiambil" name="induk_nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_induk_nom_manfaat_sudahdiambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>
        					<div class="clear"></div>
        							
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"><i><font color="#009999">Saldo JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
                  </div>									
                  <div class="clear"></div>	       
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
                    <input type="text" id="induk_tgl_saldo_awaltahun" name="induk_tgl_saldo_awaltahun" value="<?=$ld_induk_tgl_saldo_awaltahun;?>" size="11" readonly class="disabled">
                    <input type="text" id="induk_nom_saldo_awaltahun" name="induk_nom_saldo_awaltahun" value="<?=number_format((float)$ln_induk_nom_saldo_awaltahun,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																															
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Saldo Pengembangan &nbsp;</label>
                    <input type="text" id="induk_tgl_pengembangan" name="induk_tgl_pengembangan" value="<?=$ld_induk_tgl_pengembangan;?>" size="11" readonly class="disabled">
                    <input type="text" id="induk_nom_saldo_pengembangan" name="induk_nom_saldo_pengembangan" value="<?=number_format((float)$ln_induk_nom_saldo_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																														
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;"><i>Total Saldo</i> &nbsp;</label>
                    <input type="text" id="induk_nom_saldo_total" name="induk_nom_saldo_total" value="<?=number_format((float)$ln_induk_nom_saldo_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																														
                	<div class="clear"></div>
        
            			<!--</br>-->
            									
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"><i><font color="#009999">Iuran JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
                  </div>												
                  <div class="clear"></div>	
            						
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Iuran Tambahan &nbsp;</label>
                    <input type="text" id="induk_nom_iuran_thnberjalan" name="induk_nom_iuran_thnberjalan" value="<?=number_format((float)$ln_induk_nom_iuran_thnberjalan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																															
                	<div class="clear"></div>
            
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Iuran Pengembangan &nbsp;</label>
                    <input type="text" id="induk_tgl_pengembangan2" name="induk_tgl_pengembangan2" value="<?=$ld_induk_tgl_pengembangan2;?>" size="11" readonly class="disabled">
                    <input type="text" id="induk_nom_iuran_pengembangan" name="induk_nom_iuran_pengembangan" value="<?=number_format((float)$ln_induk_nom_iuran_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																													
                	<div class="clear"></div>
            
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;"><i>Total Iuran</i> &nbsp;</label>
                    <input type="text" id="induk_nom_iuran_total" name="induk_nom_iuran_total" value="<?=number_format((float)$ln_induk_nom_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																															
                	<div class="clear"></div>
            			
            			</br>
            			
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;"><i>Total Manfaat JHT</i> &nbsp;</label>
                    <input type="text" id="induk_nom_saldo_iuran_total" name="induk_nom_saldo_iuran_total" value="<?=number_format((float)$ln_induk_nom_saldo_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																																
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">PPh Pasal 21</label>
            				<input type="text" id="induk_kode_pajak_pph" name="induk_kode_pajak_pph" value="<?=$ls_induk_kode_pajak_pph;?>" size="10" maxlength="10" readonly class="disabled" style="text-align:center;">			
                    <input type="text" id="induk_nom_pph" name="induk_nom_pph" value="<?=number_format((float)$ln_induk_nom_pph,2,".",",");?>" size="26" maxlength="20" readonly class="disabled" style="text-align:right;">				                					
                  </div>	
        					<div class="clear"></div>
        					
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Pembulatan</label>
                    <input type="text" id="induk_nom_pembulatan" name="induk_nom_pembulatan" value="<?=number_format((float)$ln_induk_nom_pembulatan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																														
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Jumlah Dibayar</label>
                    <input type="text" id="induk_nom_manfaat_netto" name="induk_nom_manfaat_netto" value="<?=number_format((float)$ln_induk_nom_manfaat_netto,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																																
                	<div class="clear"></div>
        														        																
                </fieldset>	 					
              </td>
              
              <!-- Informasi Penetapan Ulang ------------------------------------------>	
              <td width="50%" valign="top">
                <fieldset ><legend><b><i><font color="#009999">Informasi Penetapan Ulang</font></i></b></legend>
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Pengambilan Sblmnya</label>
                    <input type="text" id="nom_manfaat_sudahdiambil" name="nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_nom_manfaat_sudahdiambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">
        					</div>
        					<div class="clear"></div>
        							
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"><i><font color="#009999">Saldo JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
                  </div>									
                  <div class="clear"></div>	       
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
                    <input type="text" id="tgl_saldo_awaltahun" name="tgl_saldo_awaltahun" value="<?=$ld_tgl_saldo_awaltahun;?>" size="11" readonly class="disabled">
                    <input type="text" id="nom_saldo_awaltahun" name="nom_saldo_awaltahun" value="<?=number_format((float)$ln_nom_saldo_awaltahun,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																															
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Saldo Pengembangan &nbsp;</label>
                    <input type="text" id="tgl_pengembangan" name="tgl_pengembangan" value="<?=$ld_tgl_pengembangan;?>" size="11" readonly class="disabled">
                    <input type="text" id="nom_saldo_pengembangan" name="nom_saldo_pengembangan" value="<?=number_format((float)$ln_nom_saldo_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																														
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;"><i>Total Saldo</i> &nbsp;</label>
                    <input type="text" id="nom_saldo_total" name="nom_saldo_total" value="<?=number_format((float)$ln_nom_saldo_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																														
                	<div class="clear"></div>
        
            			<!--</br>-->
            									
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"><i><font color="#009999">Iuran JHT &nbsp;&nbsp;&nbsp;:</font></i></label>	    				
                  </div>												
                  <div class="clear"></div>	
            						
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Iuran Tambahan &nbsp;</label>
                    <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=number_format((float)$ln_nom_iuran_thnberjalan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																															
                	<div class="clear"></div>
            
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Iuran Pengembangan &nbsp;</label>
                    <input type="text" id="tgl_pengembangan2" name="tgl_pengembangan2" value="<?=$ld_tgl_pengembangan2;?>" size="11" readonly class="disabled">
                    <input type="text" id="nom_iuran_pengembangan" name="nom_iuran_pengembangan" value="<?=number_format((float)$ln_nom_iuran_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																													
                	<div class="clear"></div>
            
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;"><i>Total Iuran</i> &nbsp;</label>
                    <input type="text" id="nom_iuran_total" name="nom_iuran_total" value="<?=number_format((float)$ln_nom_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																															
                	<div class="clear"></div>
            			
            			</br>
            			
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;"><i>Total Manfaat JHT</i> &nbsp;</label>
                    <input type="text" id="nom_saldo_iuran_total" name="nom_saldo_iuran_total" value="<?=number_format((float)$ln_nom_saldo_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																																
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">PPh Pasal 21</label>
            				<input type="text" id="kode_pajak_pph" name="kode_pajak_pph" value="<?=$ls_kode_pajak_pph;?>" size="10" maxlength="10" readonly class="disabled" style="text-align:center;">			
                    <input type="text" id="nom_pph" name="nom_pph" value="<?=number_format((float)$ln_nom_pph,2,".",",");?>" size="26" maxlength="20" readonly class="disabled" style="text-align:right;">				                					
                  </div>	
        					<div class="clear"></div>
        					
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Pembulatan</label>
                    <input type="text" id="nom_pembulatan" name="nom_pembulatan" value="<?=number_format((float)$ln_nom_pembulatan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																														
                	<div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label  style = "text-align:right;">Sisa JHT Dibayar</label>
                    <input type="text" id="nom_manfaat_netto" name="nom_manfaat_netto" value="<?=number_format((float)$ln_nom_manfaat_netto,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
                  </div>																																																
                	<div class="clear"></div>        				  
                </fieldset>										
            	</td>
          	</tr>
        		
        	</table>	
        	</td>
        </tr>						 
      </tbody>
    </table>			 
       
    <? 					
    if(!empty($ls_kode_manfaat) && $ls_task!="view")
    {
    ?>			 	
      <div id="buttonbox" style="width:900px;text-align:center;">
  			<input type="button" class="btn green" id="btnhitung" name="btnhitung" value="           HITUNG MANFAAT           " onClick="if(confirm('Apakah anda yakin akan melakukan perhitungan Manfaat..?')) doHitungManfaat();">        			 
        <input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
      </div>							 			 
    <? 					
    }
    ?>

		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Klik tombol <font color="#ff0000"> HITUNG MANFAAT </font> untuk menghitung ulang sisa manfaat JHT yang belum terbayarkan. Tgl Pengembangan akan dihitung sampai dengan hari ini</li>
    </div>
		
		</br>
														      			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

