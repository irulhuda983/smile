<?
$pagetype="report";
$gs_pagetitle = "PN5030 - RINCIAN MANFAAT -JKP";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5041_penetapanmanfaat_jkp.php

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
  $sql = "select kode_segmen, nvl(flag_partial,'T') flag_partial, kode_sebab_klaim, nvl(status_submit_penetapan,'T') status_submit_penetapan from sijstk.pn_klaim a ".
         "where a.kode_klaim = '$ls_kode_klaim'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status_submit_penetapan	= $row['STATUS_SUBMIT_PENETAPAN'];
	$ls_kode_sebab_klaim				= $row['KODE_SEBAB_KLAIM'];
	$ls_flag_partial						= $row['FLAG_PARTIAL'];
	$ls_kode_segmen							= $row['KODE_SEGMEN'];
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
  
  // <!-- ====================== JKP 26/10/2021 ======================== -->
	
 	// $gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat;	 		  	 
 	$gs_pagetitle = "PN5030 - RINCIAN MANFAAT - JKP";	 		  
   
  //  <!-- ====================== JKP 26/10/2021 ======================== -->
   
}

if($btn_task=="hitung_manfaat")
{
  //cek kelayakan ----------------------------------------------------------
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_JHTRINCI('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	
  
  $msg = "Proses Hitung Manfaat selesai, session dilanjutkan...";
  $task = "edit";   		
  
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid&msg=$msg');";
  echo "alert('$msg')";
  echo "</script>";		
}

if($btn_task=="simpan")
{
  $ls_keterangan = $_POST['keterangan'];
	$ln_nom_biaya_diajukan = str_replace(',','',$_POST['nom_biaya_diajukan']);
	
  $sql = "update sijstk.pn_klaim_manfaat_detil set keterangan = '$ls_keterangan', nom_biaya_diajukan= '$ln_nom_biaya_diajukan' ".
         "where kode_klaim = '$ls_kode_klaim' and kode_manfaat = '$ls_kode_manfaat' ".
				 "and no_urut = 1";         
  $DB->parse($sql);
  $DB->execute();	

  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_JHTRINCI('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	
											
  $msg = "Update Data berhasil, session dilanjutkan...";
  $task = "edit";   		
  
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid&msg=$msg');";
  echo "alert('$msg')";
  echo "</script>";		
}

//query data ------------------------------------------------------------------
$sql = "SELECT A.KODE_KLAIM,
          A.STATUS_PKWT,
          TO_CHAR(A.TGL_MULAI_PERJANJIAN_KERJA,'DD-MM-YYYY') TGL_MULAI_PERJANJIAN_KERJA,
          TO_CHAR(A.TGL_AKHIR_PERJANJIAN_KERJA,'DD-MM-YYYY') TGL_AKHIR_PERJANJIAN_KERJA,
          TO_CHAR(A.BLTH_AWAL_KEPESERTAAN_JKP,'MM-YYYY') BLTH_AWAL_KEPESERTAAN_JKP,
          TO_CHAR(A.BLTH_NA_JKP,'MM-YYYY') BLTH_NA_JKP,
          TO_CHAR(A.TGL_PHK,'DD-MM-YYYY') TGL_PHK,
          A.KODE_TIPE_KASUS_PHK,
          (SELECT B.KETERANGAN
            FROM SIAPKERJA.SK_MS_LOOKUP@TO_EC B
            WHERE B.KODE = A.KODE_TIPE_KASUS_PHK)
            KASUS_PHK,
          A.KODE_SEBAB_PHK,
          (SELECT B.NAMA_SEBAB_PHK
            FROM SIAPKERJA.SK_KODE_SEBAB_PHK@TO_EC B
            WHERE B.KODE_SEBAB_PHK = A.KODE_SEBAB_PHK)
            SEBAB_PHK,
          'JUMLAH BULAN IUR JKP' JML_BULAN_IUR_JKP,
          A.FLAG_ENAM_BULAN_BERTURUT,
          A.TAHAP_JKP,
          A.NOM_UPAH_PELAPORAN,
          TO_CHAR(A.BLTH_UPAH_PELAPORAN,'MM-YYYY') BLTH_UPAH_PELAPORAN,
          A.NOM_UPAH_TERHITUNG,
          A.BULAN_MANFAAT_KE,
          TO_CHAR(A.BLTH_MANFAAT,'MM-YYYY') BLTH_MANFAAT,
          A.TARIF_UPAH_TERHITUNG,
          A.NOM_MANFAAT,
          A.KETERANGAN
          FROM PN.PN_KLAIM_MANFAAT_DETIL_PHKJKP A
        WHERE A.KODE_KLAIM = '$ls_kode_klaim'";

//  var_dump($sql);die();

$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_status_pkwt                 = $row["STATUS_PKWT"];
$ls_tgl_mulai_perjanjuan_kerja  = $row["TGL_MULAI_PERJANJIAN_KERJA"];
$ls_tgl_akhir_perjanjuan_kerja  = $row["TGL_AKHIR_PERJANJIAN_KERJA"];
$ls_blth_awal_kepesertaan_jkp   = $row["BLTH_AWAL_KEPESERTAAN_JKP"];
$ls_blth_na_jkp 	 				 			= $row["BLTH_NA_JKP"];
$ls_tgl_phk					            = $row["TGL_PHK"];
$ls_kode_tipe_kasus_phk         = $row["KODE_TIPE_KASUS_PHK"];
$ls_kasus_phk				            = $row["KASUS_PHK"];
$ls_kode_sebab_phk	            = $row["KODE_SEBAB_PHK"];
$ls_sebab_phk				            = $row["SEBAB_PHK"];
$ls_status_kelayakan_klaim_jkp	= $row["STATUS_KELAYAKAN_KLAIM_JKP"];
$ls_jumlah_bulan_iur_jkp				= $row["JUMLAH_BULAN_IUR_JKP"];
$ls_flag_enam_bulan_berturut		= $row["FLAG_ENAM_BULAN_BERTURUT"];
$ls_tahap_jkp				            = $row["TAHAP_JKP"];
$ls_nom_upah_pelaporan					= $row["NOM_UPAH_PELAPORAN"];
$ls_blth_upah_pelaporan	        = $row["BLTH_UPAH_PELAPORAN"];
$ls_nom_upah_terhitung		      = $row["NOM_UPAH_TERHITUNG"];
$ls_bulan_manfaat_ke	          = $row["BULAN_MANFAAT_KE"];	
$ls_blth_manfaat	              = $row["BLTH_MANFAAT"];
$ls_tarif_upah_terhitung        = $row["TARIF_UPAH_TERHITUNG"];
$ls_nom_manfaat                 = $row["NOM_MANFAAT"];
$ls_keterangan                  = $row["KETERANGAN"];

$ls_tarif_upah_terhitung_persen = 100*$ls_tarif_upah_terhitung.'%';

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
    if(form.kode_tipe_penerima.value==""){
      alert('Tipe Penerima kosong, harap lengkapi data input...!!!');
      form.kode_tipe_penerima.focus();	      
    }else
    {
      form.btn_task.value="hitung_manfaat";
      form.submit();
    }
  }

  function doSimpan() {
    var form = document.adminForm;
    if(form.kode_tipe_penerima.value==""){
      alert('Tipe Penerima kosong, harap lengkapi data input...!!!');
      form.kode_tipe_penerima.focus();	      
    }else
    {
      form.btn_task.value="simpan";
      form.submit();
    }
  }
	var x = $("#recordsIuran").val();
  $("#bulan_manfaat_ke").val(x);
  console.log(x,"xx");
  function refreshParent() 
  {					
    console.log("test");
    window.open('','_self').close();																	
    <?php	
    if($ls_form_penetapan!=''){			
    	echo "window.location.replace('$ls_form_penetapan?task=Edit&sender=pn5002.php&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim');";	
    }
    ?>	
  }
	function getBLTH(val){
    tglNaskahFormat = new Date(val);
    var dd = String(tglNaskahFormat.getDate()).padStart(2, '0');
    var mm = String(tglNaskahFormat.getMonth() + 1).padStart(2, '0');
    var yyyy = tglNaskahFormat.getFullYear();

    tglNaskahFormat = mm + '-' + yyyy;
		return val == null ? '-' : tglNaskahFormat;
	}
  function getPercent(val){
    percent = new Number(val);
    percent = percent*100
    percent = percent + '%';
		return val == null ? '-' : percent;
	}
  function getTanggal(val){
    tglNaskahFormat = new Date(val);
    var dd = String(tglNaskahFormat.getDate()).padStart(2, '0');
    var mm = String(tglNaskahFormat.getMonth() + 1).padStart(2, '0');
    var yyyy = tglNaskahFormat.getFullYear();

    tglNaskahFormat = dd + '-' + mm + '-' + yyyy;
		return val == null ? '-' : tglNaskahFormat;
	}
  function getValue(val){
		return val == null ? '-' : val;
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
						 
		<fieldset style="width:900px;"><legend >Jaminan Kehilangan Pekerjaan</legend>    

    <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Status PKWT </label>
        <!-- <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_kode_klaim;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					 -->
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_status_pkwt;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tanggal Mulai Perjanjian Kerja </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tgl_mulai_perjanjuan_kerja;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tanggal Berakhir Perjanjian Kerja </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tgl_akhir_perjanjuan_kerja;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Awal Kepesertaan JKP </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_blth_awal_kepesertaan_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Non Aktif JKP </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_blth_na_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tanggal PHK </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tgl_phk;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tipe Kasus PHK </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_kode_tipe_kasus_phk.' - '.$ls_kasus_phk;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Sebab PHK </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_kode_sebab_phk.' - '.$ls_sebab_phk;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div><br>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Jumlah Bulan Iur JKP </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_jumlah_bulan_iur_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Terdapat Enam Bulan Upah Berturut-turut </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_flag_enam_bulan_berturut;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div><br>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tahap JKP </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tahap_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Upah Dilaporkan </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?='Rp '.number_format((float)$ls_nom_upah_pelaporan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Upah Dilaporkan </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_blth_upah_pelaporan;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Upah Terhitung</label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?='Rp '.number_format((float)$ls_nom_upah_terhitung,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div>   
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Bulan ke-</label>
        <input type="text" id="bulan_manfaat_ke" name="bulan_manfaat_ke" value="<?=$ls_bulan_manfaat_ke?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div> 
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Manfaat</label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_blth_manfaat;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div> 
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">% Upah Terhitung</label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tarif_upah_terhitung_persen?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div> 
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Nilai Manfaat</label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?='Rp '.number_format((float)$ls_nom_manfaat,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																						
    	<div class="clear"></div> <br>

      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" value="<?=$ls_keterangan;?>" size="40" maxlength="300" readonly class="disabled" style="text-align:right;">                					
      </div>																																																
    	<div class="clear"></div>
			
		</fieldset>				 
       
    <? 					
    // if(!empty($ls_kode_manfaat) && $ls_task!="view")
    // {
    ?>			 	
      <!-- <div id="buttonbox" style="width:900px;text-align:center;">
      <input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
      </div>							 			  -->
    <? 					
    // }
    ?>

		</br>
		
    <!-- <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Klik tombol <font color="#ff0000"> HITUNG MANFAAT </font> untuk menghitung ulang nilai manfaat JHT. Tgl Pengembangan akan dihitung sampai dengan hari ini</li>
      <li style="margin-left:15px;">Apabila pengambilan klaim sebagain (10% atau 30%) maka nilai pengambilan yang diajukan oleh TK dapat diinput pada kolom Jumlah Diajukan kemudian Klik tombol <font color="#ff0000"> SIMPAN </font> </li>
		</div> -->
		
		</br>
									      			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

