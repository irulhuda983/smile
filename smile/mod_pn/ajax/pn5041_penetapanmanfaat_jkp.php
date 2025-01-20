<?
$pagetype="report";
$gs_pagetitle = "PN5002 - RINCIAN MANFAAT -JKP";
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
$ls_root_sender			 = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_task      			 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'].' style="background-color:#F5F5F5;"';


	
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
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat;	 		  
   
  //  <!-- ====================== JKP 26/10/2021 ======================== -->
   
}

if($btn_task=="hitung_manfaat")
{
  //cek kelayakan ----------------------------------------------------------
  $ls_nom_upah_pelaporan = $_POST['nom_upah_pelaporan_after'];
  $ls_blth_upah_pelaporan = $_POST['blth_upah_pelaporan_after'];
  $ls_tahap_jkp = $_POST['tahap_jkp'];
  $ls_bulan_manfaat_ke = $_POST['bulan_manfaat_ke'];

  $qry = "BEGIN PN.P_PN_SIAPKERJA2PN.X_HITUNG_MNF_JKPPHK('$ls_kode_klaim',TO_DATE('$ls_blth_upah_pelaporan','DD/MM/YYYY'),'$ls_nom_upah_pelaporan','$ls_tahap_jkp',$ls_bulan_manfaat_ke,'$username',:p_sukses,:p_mess);END;";											 	
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
  $ls_root_sender = $_POST['root_sender'];
	
  $qry = "UPDATE PN.PN_KLAIM_MANFAAT_DETIL_PHKJKP SET    
            keterangan          = '$ls_keterangan', 
            petugas_ubah        = '$USER',
            tgl_ubah            = SYSDATE                        
          WHERE KODE_KLAIM = '$ls_kode_klaim'";         
  $DB->parse($qry);
  $DB->execute();	
											
  $msg = "Data berhasil disimpan..."; 		
  
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid&msg=$msg&root_sender=$ls_root_sender');";
  echo "alert('$msg')";
  echo "</script>";		
}

//query data ------------------------------------------------------------------
$sql = "SELECT A.KODE_KLAIM,
          A.STATUS_PKWT,
          to_char(A.TGL_MULAI_PERJANJIAN_KERJA, 'DD-MM-YYYY') TGL_MULAI_PERJANJIAN_KERJA,
          to_char(A.TGL_AKHIR_PERJANJIAN_KERJA, 'DD-MM-YYYY') TGL_AKHIR_PERJANJIAN_KERJA,
          to_char(A.BLTH_AWAL_KEPESERTAAN_JKP, 'MM-YYYY') BLTH_AWAL_KEPESERTAAN_JKP,
          to_char(A.BLTH_NA_JKP, 'MM-YYYY') BLTH_NA_JKP,
          to_char(A.TGL_PHK, 'DD-MM-YYYY') TGL_PHK,
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
          A.FLAG_ENAM_BULAN_BERTURUT,
          A.TAHAP_JKP,
          A.NOM_UPAH_PELAPORAN,
          to_char(A.BLTH_UPAH_PELAPORAN, 'MM-YYYY') BLTH_UPAH_PELAPORAN,
          A.NOM_UPAH_TERHITUNG,
          A.BULAN_MANFAAT_KE,
          to_char(A.BLTH_MANFAAT, 'MM-YYYY') BLTH_MANFAAT,
          A.TARIF_UPAH_TERHITUNG,
          A.NOM_MANFAAT,
          A.KETERANGAN,
          A.FLAG_HITUNG_MANFAAT,
          (SELECT KODE_SEGMEN FROM PN.PN_KLAIM B WHERE A.KODE_KLAIM=B.KODE_KLAIM) KODE_SEGMEN,
          (SELECT KODE_PERUSAHAAN FROM PN.PN_KLAIM B WHERE A.KODE_KLAIM=B.KODE_KLAIM) KODE_PERUSAHAAN,
          (SELECT KODE_TK FROM PN.PN_KLAIM B WHERE A.KODE_KLAIM=B.KODE_KLAIM) KODE_TK,
          (SELECT STATUS_KLAIM FROM PN.PN_KLAIM B WHERE A.KODE_KLAIM=B.KODE_KLAIM) STATUS_KLAIM,
          TO_CHAR(A.BLTH_NA_JKP,'DD/MM/YYYY') TGL_NA
          FROM PN.PN_KLAIM_MANFAAT_DETIL_PHKJKP A
        WHERE A.KODE_KLAIM = '$ls_kode_klaim'";

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
$ls_kode_segmen                 = $row["KODE_SEGMEN"];
$ls_kode_perusahaan             = $row["KODE_PERUSAHAAN"];
$ls_kode_tk                     = $row["KODE_TK"];
$ls_tgl_na                      = $row["TGL_NA"];
$ls_flag_hitung_manfaat         = $row["FLAG_HITUNG_MANFAAT"];
$ls_status_klaim                = $row["STATUS_KLAIM"];



$ls_display_tarif_upah_terhitung = 100*$ls_tarif_upah_terhitung.'%';

if($ls_kode_tipe_kasus_phk!=null || $ls_kode_tipe_kasus_phk!= ''){
  $ls_display_kasus_phk = $ls_kode_tipe_kasus_phk.' - '.$ls_kasus_phk;
}

if($ls_kode_sebab_phk!=null || $ls_kode_sebab_phk!= ''){
  $ls_display_sebab_phk = $ls_kode_sebab_phk.' - '.$ls_sebab_phk;
}


$sql_count = "select count(1) rn from ($sql) where 1=1";
$DB->parse($sql_count);
$DB->execute();
$row = $DB->nextrow();
$ls_jumlah_bulan_iur_jkp = (float) $row["RN"];

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

  function doSimpan() {
    var form = document.adminForm;
    if(form.keterangan.value==""){
      alert('Kolom keterangan masih kosong, harap lengkapi data input');
      form.keterangan.focus();	      
    }else if(form.flag_hitung_manfaat.value=="T"){
      alert('Harap melakukan pemilihan dasar upah untuk perhitungan manfaat bulan ke-1 terlebih dahulu');
      form.keterangan.focus();
    }else
    {
      form.btn_task.value="simpan";
      form.submit();
    }
  }

  function doHitungManfaat() {
    var form = document.adminForm;
    if(form.nom_upah_pelaporan_after.value==""){
      alert('Silahkan ubah data upah terlebih dahulu');
      form.nom_upah_pelaporan_after.focus();	      
    }else
    {
      form.btn_task.value="hitung_manfaat";
      form.submit();
    }
  }
  			
  function refreshParent() 
  {																						
    <?php	
    if($ls_form_penetapan!=''){			
    	echo "window.location.replace('$ls_form_penetapan?task=Edit&sender=pn5002.php&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&root_sender=$ls_root_sender');";	
    }
    ?>	
  }

  function lovUpahTerakhir(){

    let kode_perusahaan = "<?php echo $ls_kode_perusahaan; ?>";
    let kode_segmen = "<?php echo $ls_kode_segmen; ?>";
    let kode_divisi = "<?php echo $ls_kode_divisi; ?>";
    let kode_tk = "<?php echo $ls_kode_tk; ?>";
    let tgl_na = "<?php echo $ls_tgl_na; ?>";
    let kode_klaim = "<?php echo $ls_kode_klaim; ?>";

    NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkp_upahterakhir.php?p=pn5041_penetapanjkp.php&a=adminForm&b=nom_upah_pelaporan&c='+kode_perusahaan+'&d='+kode_segmen+'&e='+kode_divisi+'&f='+kode_tk+'&g='+tgl_na+'&h='+kode_klaim+'&j=blth_upah_pelaporan&k=nom_upah_pelaporan_after&l=blth_upah_pelaporan_after','',900,580,1);

 }
						
</script>
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionentry">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
    input[type=text] {
   border-radius:5px;
  }
  textarea {
   border-radius:5px;
  }
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
		<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
						 
		<fieldset style="width:900px;"><legend >Jaminan Kehilangan Pekerjaan</legend>    

    <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Status PKWT </label>
        <!-- <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_kode_klaim;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					 -->
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_status_pkwt;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>
      <div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tanggal Mulai Perjanjian Kerja </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tgl_mulai_perjanjuan_kerja;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tanggal Berakhir Perjanjian Kerja </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tgl_akhir_perjanjuan_kerja;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Awal Kepesertaan JKP </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_blth_awal_kepesertaan_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Non Aktif JKP </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_blth_na_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tanggal PHK </label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=$ls_tgl_phk;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tipe Kasus PHK </label>
        <textarea cols="255" rows="1" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" readonly style="width:252px;height:25px;background-color:#F5F5F5"><?=$ls_display_kasus_phk;?></textarea>
      </div>
      <div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Sebab PHK </label>
        <textarea cols="255" rows="1" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" readonly style="width:252px;height:25px;background-color:#F5F5F5"><?=$ls_display_sebab_phk;?></textarea>
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Jumlah Bulan Iur JKP </label>
        <input type="text" id="jumlah_bulan_iur_jkp" name="jumlah_bulan_iur_jkp" value="<?=$ls_jumlah_bulan_iur_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Terdapat Enam Bulan Upah Berturut-turut </label>
        <input type="text" id="flag_enam_bulan_berturut" name="flag_enam_bulan_berturut" value="<?=$ls_flag_enam_bulan_berturut;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div>
      
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Tahap JKP </label>
        <input type="text" id="tahap_jkp" name="tahap_jkp" value="<?=$ls_tahap_jkp;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Upah Dilaporkan </label>
        <input type="hidden" id="nom_upah_pelaporan_before" name="nom_upah_pelaporan_before" value="<?='Rp '.number_format((float)$ls_nom_upah_pelaporan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">
        <input type="text" id="nom_upah_pelaporan" name="nom_upah_pelaporan" value="<?='Rp '.number_format((float)$ls_nom_upah_pelaporan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">
        <input type="hidden" id="nom_upah_pelaporan_after" name="nom_upah_pelaporan_after" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">
        <input type="hidden" id="tapjkp_kode_klaim" name="tapjkp_kode_klaim" value="<?=$ls_kode_klaim;?>">
        <?php if($ls_bulan_manfaat_ke=="1" && $ls_status_klaim=="PENETAPAN"){ ?>
        <a href="#" onclick="lovUpahTerakhir();">
        <img src="../../images/help.png" alt="Cari Faskes" border="0" align="absmiddle"></a>
        <?php } ?>		               					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Upah Dilaporkan </label>
        <input type="hidden" id="blth_upah_pelaporan_before" name="blth_upah_pelaporan_before" value="<?=$ls_blth_upah_pelaporan;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;"> 
        <input type="text" id="blth_upah_pelaporan" name="blth_upah_pelaporan" value="<?=$ls_blth_upah_pelaporan;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">
        <input type="hidden" id="blth_upah_pelaporan_after" name="blth_upah_pelaporan_after" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">
        <input type="hidden" id="flag_hitung_manfaat" name="flag_hitung_manfaat" value="<?=$ls_flag_hitung_manfaat;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">               					
      </div>																																						
    	<div class="clear"></div>
						
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Upah Terhitung</label>
        <input type="text" id="nom_upah_terhitung" name="nom_upah_terhitung" value="<?='Rp '.number_format((float)$ls_nom_upah_terhitung,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div>   
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Bulan ke-</label>
        <input type="text" id="bulan_manfaat_ke" name="bulan_manfaat_ke" value="<?=$ls_bulan_manfaat_ke;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div> 
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">BLTH Manfaat</label>
        <input type="text" id="blth_manfaat" name="blth_manfaat" value="<?=$ls_blth_manfaat;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div> 
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">% Upah Terhitung</label>
        <input type="text" id="tarif_upah_terhitung" name="tarif_upah_terhitung" value="<?=$ls_display_tarif_upah_terhitung;?>" size="40" maxlength="20" readonly class="disabled" style="text-align:left;">                					
      </div>																																						
    	<div class="clear"></div> 
      			
      <div class="form-row_kiri">
      <label  style = "text-align:right; width:170px;">Nilai Manfaat</label>
        <input type="text" id="nom_manfaat" name="nom_manfaat" value="<?='Rp '.number_format((float)$ls_nom_manfaat,2,".",",");?>" maxlength="20" readonly class="disabled" style="text-align:left; width:252px; font-weight:bold;">                					
      </div>																																						
    	<div class="clear"></div> <br>

      <form action="">
        <div class="form-row_kiri">
        <label  style = "text-align:right; width:170px;">Keterangan</label>
          <input type="text" id="keterangan" name="keterangan" <?=$ls_task?> value="<?=$ls_keterangan;?>" size="40" maxlength="300">                					
        </div>																																																
        <div class="clear"></div>
      </form>
		</fieldset>				 
       
    			 	
    <div id="buttonbox" style="width:900px;text-align:center;">
    <? 					
    if(!isset($ls_task))
    { if($ls_bulan_manfaat_ke=="1"){
    ?>
      <input type="button" class="btn green" id="btnhitung" name="btnhitung" value="           HITUNG MANFAAT           " onClick="if(confirm('Apakah anda yakin akan melakukan perhitungan Manfaat..?')) doHitungManfaat();">
      <?php } ?>
      <input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="if(confirm('Apakah anda yakin akan melakukan update keterangan..?')) doSimpan();"> 
    <? 					
    }
    ?>  
      <input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
      </div>							 			 
    

		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Klik tombol <font color="#ff0000"> HITUNG MANFAAT </font> untuk menghitung ulang nilai manfaat JKP khusus manfaat bulan ke-1.</li>
      <li style="margin-left:15px;">Klik tombol <font color="#ff0000"> SIMPAN </font> setelah melengkapi keterangan.</li>
		</div>
		
		</br>
									      			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

