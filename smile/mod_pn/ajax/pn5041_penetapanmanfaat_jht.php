<?
$pagetype="report";
$gs_pagetitle = "PN5002 - RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include_once '../../includes/fungsi_rpt.php';
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
	
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat;	 		  	 
}

if($btn_task=="hitung_manfaat")
{
  //cek kelayakan ----------------------------------------------------------
  // $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_JHTRINCI('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
  // $proc = $DB->parse($qry);				
  // oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  // oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  // $DB->execute();				
  // $ls_sukses = $p_sukses;
  // $ls_mess = $p_mess;	
  
  $headers = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );

  $data = array(
    'chId'          => 'SMILE',
    'reqId'         => $username,
    "KODE_KLAIM"    => $ls_kode_klaim
  );


  $url=$wsIp."/JSPN5041/HitungManfaatJht";
  $ch     = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

  $result = curl_exec($ch);
  $result = json_decode($result);

  // var_dump($result);die;
  if($result->ret=="0"){ 
    $msg = "Proses Hitung Manfaat selesai, session dilanjutkan...";  		
  } else {
    $msg = "Gagal Hitung Manfaat, session dilanjutkan...";
  }    

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
// 21-12-2021, query disesuaikan untuk sum nilai konvensional dan syariah
/*
$sql = "select 
          kode_klaim, kode_manfaat, no_urut, kategori_manfaat, kode_tipe_penerima, kd_prg, nom_biaya_diajukan, nom_biaya_disetujui, 
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
        and kode_manfaat = '$ls_kode_manfaat'
        and no_urut = 1 ";
		*/
$sql = "select 
          kode_klaim, kode_manfaat, max(no_urut)no_urut, kategori_manfaat, kode_tipe_penerima, kd_prg, 
          sum(nvl(nom_biaya_diajukan,0))nom_biaya_diajukan, sum(nvl(nom_biaya_disetujui,0))nom_biaya_disetujui, 
          max(pengambilan_ke)pengambilan_ke, sum(nvl(nom_manfaat_sudahdiambil,0))nom_manfaat_sudahdiambil, sum(nvl(nom_diambil_thnberjalan,0))nom_diambil_thnberjalan, sum(nvl(nom_pengembangan_estimasi,0))nom_pengembangan_estimasi, 
          max(tgl_pengembangan_estimasi)tgl_pengembangan_estimasi, to_char(max(tgl_pengembangan),'dd/mm/yyyy') tgl_pengembangan, 
		  to_char(max(tgl_saldo_awaltahun),'dd/mm/yyyy') tgl_saldo_awaltahun, 
          sum(nvl(nom_saldo_awaltahun,0))nom_saldo_awaltahun, sum(nvl(nom_saldo_pengembangan,0))nom_saldo_pengembangan, sum(nvl(nom_saldo_total,0))nom_saldo_total, 
          sum(nvl(nom_iuran_thnberjalan,0))nom_iuran_thnberjalan, sum(nvl(nom_iuran_pengembangan,0))nom_iuran_pengembangan, sum(nvl(nom_iuran_total,0))nom_iuran_total, sum(nvl(nom_saldo_iuran_total,0))nom_saldo_iuran_total, 
          max(persentase_pengambilan)persentase_pengambilan, sum(nvl(nom_manfaat_maxbisadiambil,0))nom_manfaat_maxbisadiambil, sum(nvl(nom_manfaat_diambil,0))nom_manfaat_diambil, 
          sum(nvl(nom_manfaat_utama,0))nom_manfaat_utama, sum(nvl(nom_manfaat_tambahan,0))nom_manfaat_tambahan, sum(nvl(nom_manfaat_gross,0))nom_manfaat_gross, 
          sum(nvl(nom_ppn,0))nom_ppn, sum(nvl(nom_pph,0))nom_pph, 
          case
            when mod(nvl(nvl(sum(nvl(nom_manfaat_gross,0)),0)-nvl(sum(nvl(nom_pph,0)),0),0),10) = 0 then 0 
            else
            (10-(mod(nvl(nvl(sum(nvl(nom_manfaat_gross,0)),0)-nvl(sum(nvl(nom_pph,0)),0),0),10)))
          end nom_pembulatan,
          (
            nvl(nvl(sum(nvl(nom_manfaat_gross,0)),0)-nvl(sum(nvl(nom_pph,0)),0),0) + nvl(case
            when mod(nvl(nvl(sum(nvl(nom_manfaat_gross,0)),0)-nvl(sum(nvl(nom_pph,0)),0),0),10) = 0 then 0 
            else
              (10-(mod(nvl(nvl(sum(nvl(nom_manfaat_gross,0)),0)-nvl(sum(nvl(nom_pph,0)),0),0),10)))
            end,0)
          ) nom_manfaat_netto,
          max(kode_pajak_ppn)kode_pajak_ppn, max(kode_pajak_pph)kode_pajak_pph, 
          max(keterangan)keterangan
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = '$ls_kode_klaim'
		  and kode_manfaat = '$ls_kode_manfaat'
		  and no_urut in( 1,2)
        group by kode_klaim, kode_manfaat, kategori_manfaat, kode_tipe_penerima, kd_prg";
//echo $sql;				
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_no_urut 	 				 				= $row["NO_URUT"];
$ls_kategori_manfaat					= $row["KATEGORI_MANFAAT"];
$ls_kode_tipe_penerima				= $row["KODE_TIPE_PENERIMA"];
$ls_kd_prg										= $row["KD_PRG"];
$ln_nom_biaya_diajukan				= $row["NOM_BIAYA_DIAJUKAN"];
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

<table aria-describedby="mydesc" class="captionentry">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width:98%;height:27px;background:-webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 1px 1px 1px;height:23px;border-bottom:1px solid #6997ff;padding-left:1px;border-top-right-radius:1px;border-top-left-radius:1px;}
  </style>
  <tr><th></th></tr>
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
						 
		<fieldset style="width:900px;"><legend >Jaminan Hari Tua</legend>      
			<div class="form-row_kanan">
      <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
  			<select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:240px;background-color:#F5F5F5">
        <option value="">- Tipe Penerima --</option>
        <?
        $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from pn.pn_kode_manfaat_eligibilitas a, pn.pn_kode_tipe_penerima b ".
               "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
               "and a.kode_manfaat = '$ls_kode_manfaat' ".
							 "and a.kode_tipe_penerima = '$ls_kode_tipe_penerima' ".
							 "and a.kode_segmen = '$ls_kode_segmen' ".
               "order by b.no_urut";	 
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if (($row["KODE_TIPE_PENERIMA"]==$ls_kode_tipe_penerima && strlen($row["KODE_TIPE_PENERIMA"])==strlen($ls_kode_tipe_penerima))) { echo " selected"; }
        echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
        }
        ?>
        </select>
  			<input type="hidden" id="kode_tipe_penerima_old" name="kode_tipe_penerima_old" value="<?=$ls_kode_tipe_penerima;?>">
				<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
  			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ls_no_urut;?>"> 
  		</div>		    																																				
    	<div class="clear"></div>	

      <div class="form-row_kiri">
      <label  style = "text-align:right;">Pengambilan Sblmnya</label>
        <input type="text" id="nom_manfaat_sudahdiambil" name="nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_nom_manfaat_sudahdiambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>						
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Persentase Pengambilan &nbsp;</label>
  			<select size="1" id="persentase_pengambilan" name="persentase_pengambilan" value="<?=$ln_persentase_pengambilan;?>" tabindex="2" class="select_format" style="width:240px;background-color:#F5F5F5">
        <option value="">- Persentase Pengambilan --</option>
        <?
        $sql = "select persen_pengambilan_maksimum from sijstk.pn_kode_sebab_klaim ".
               "where kode_sebab_klaim = '$ls_kode_sebab_klaim' ";
				$DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if (($row["PERSEN_PENGAMBILAN_MAKSIMUM"]==$ln_persentase_pengambilan && strlen($row["PERSEN_PENGAMBILAN_MAKSIMUM"])==strlen($ln_persentase_pengambilan))) { echo " selected"; }
        echo " value=\"".$row["PERSEN_PENGAMBILAN_MAKSIMUM"]."\">".$row["PERSEN_PENGAMBILAN_MAKSIMUM"]."</option>";
        }
        ?>
        </select>
  		</div>		    																																				
    	<div class="clear"></div>
			
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Jumlah Diajukan</label>
        <input type="text" id="nom_biaya_diajukan" name="nom_biaya_diajukan" value="<?=number_format((float)$ln_nom_biaya_diajukan,2,".",",");?>" size="40" maxlength="20" onblur="this.value=format_uang(this.value);" style="text-align:right;" <?=($ls_flag_partial =="Y")? " " : " readonly class=disabled";?>>                					
      </div>																																																											
      <div class="clear"></div>	
			
      <div class="form-row_kiri">
      <label style = "text-align:right;"><i><span style="color:#009999;">Saldo JHT :</span></i></label>	    				
      </div>																																																														
      <div class="clear"></div>	
						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
        <input type="text" id="tgl_saldo_awaltahun" name="tgl_saldo_awaltahun" value="<?=$ld_tgl_saldo_awaltahun;?>" size="11" readonly class="disabled">
        <input type="text" id="nom_saldo_awaltahun" name="nom_saldo_awaltahun" value="<?=number_format((float)$ln_nom_saldo_awaltahun,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Pengambilan Thn Bjalan</label>
        <input type="text" id="nom_diambil_thnberjalan" name="nom_diambil_thnberjalan" value="<?=number_format((float)$ln_nom_diambil_thnberjalan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																																
    	<div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right;">Saldo Pengembangan &nbsp;</label>
        <input type="text" id="tgl_pengembangan" name="tgl_pengembangan" value="<?=$ld_tgl_pengembangan;?>" size="11" readonly class="disabled">
        <input type="text" id="nom_saldo_pengembangan" name="nom_saldo_pengembangan" value="<?=number_format((float)$ln_nom_saldo_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Max Pengambilan</label>
        <input type="text" id="nom_manfaat_maxbisadiambil" name="nom_manfaat_maxbisadiambil" value="<?=number_format((float)$ln_nom_manfaat_maxbisadiambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																															
    	<div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right;"><i>Total Saldo</i> &nbsp;</label>
        <input type="text" id="nom_saldo_total" name="nom_saldo_total" value="<?=number_format((float)$ln_nom_saldo_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Jumlah Diambil</label>
        <input type="text" id="nom_manfaat_diambil" name="nom_manfaat_diambil" value="<?=number_format((float)$ln_nom_manfaat_diambil,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																															
    	<div class="clear"></div>
			
			</br>
									
      <div class="form-row_kiri">
      <label style = "text-align:right;"><i><span style="color:#009999;">Iuran JHT :</span></i></label>	    				
      </div>												
      <div class="clear"></div>	
						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Iuran Tambahan &nbsp;</label>
        <input type="text" id="nom_iuran_thnberjalan" name="nom_iuran_thnberjalan" value="<?=number_format((float)$ln_nom_iuran_thnberjalan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">PPh Pasal 21</label>
				<input type="text" id="kode_pajak_pph" name="kode_pajak_pph" value="<?=$ls_kode_pajak_pph;?>" size="10" maxlength="10" readonly class="disabled" style="text-align:center;">			
        <input type="text" id="nom_pph" name="nom_pph" value="<?=number_format((float)$ln_nom_pph,2,".",",");?>" size="26" maxlength="20" readonly class="disabled" style="text-align:right;">				                					
      </div>																																															
    	<div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right;">Iuran Pengembangan &nbsp;</label>
        <input type="text" id="tgl_pengembangan2" name="tgl_pengembangan2" value="<?=$ld_tgl_pengembangan2;?>" size="11" readonly class="disabled">
        <input type="text" id="nom_iuran_pengembangan" name="nom_iuran_pengembangan" value="<?=number_format((float)$ln_nom_iuran_pengembangan,2,".",",");?>" size="25" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Pembulatan</label>
        <input type="text" id="nom_pembulatan" name="nom_pembulatan" value="<?=number_format((float)$ln_nom_pembulatan,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																														
    	<div class="clear"></div>

      <div class="form-row_kiri">
      <label  style = "text-align:right;"><i>Total Iuran</i> &nbsp;</label>
        <input type="text" id="nom_iuran_total" name="nom_iuran_total" value="<?=number_format((float)$ln_nom_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Jumlah Dibayar</label>
        <input type="text" id="nom_manfaat_netto" name="nom_manfaat_netto" value="<?=number_format((float)$ln_nom_manfaat_netto,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>																																																
    	<div class="clear"></div>
			
      <div class="form-row_kiri">
      <label  style = "text-align:right;"><i>Total Saldo+Iuran</i> &nbsp;</label>
        <input type="text" id="nom_saldo_iuran_total" name="nom_saldo_iuran_total" value="<?=number_format((float)$ln_nom_saldo_iuran_total,2,".",",");?>" size="40" maxlength="20" readonly class="disabled" style="text-align:right;">                					
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Keterangan</label>
        <input type="text" id="keterangan" name="keterangan" value="<?=$ls_keterangan;?>" size="40" maxlength="300">                					
      </div>																																																
    	<div class="clear"></div>
			
		</fieldset>				 
       
    <? 					
    if(!empty($ls_kode_manfaat) && $ls_task!="view")
    {
    ?>			 	
      <div id="buttonbox" style="width:900px;text-align:center;">
			<input type="button" class="btn green" id="btnhitung" name="btnhitung" value="           HITUNG MANFAAT           " onClick="if(confirm('Apakah anda yakin akan melakukan perhitungan Manfaat..?')) doHitungManfaat();">        			 
      <input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="if(confirm('Apakah anda yakin akan melakukan update keterangan..?')) doSimpan();"> 
      <input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
      </div>							 			 
    <? 					
    }
    ?>

		</br>
		
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <ul>
        <li style="margin-left:15px;">Klik tombol <span style="color:#ff0000"> HITUNG MANFAAT </span> untuk menghitung ulang nilai manfaat JHT. Tgl Pengembangan akan dihitung sampai dengan hari ini</li>
        <li style="margin-left:15px;">Apabila pengambilan klaim sebagain (10% atau 30%) maka nilai pengambilan yang diajukan oleh TK dapat diinput pada kolom Jumlah Diajukan kemudian Klik tombol <span style="color:#ff0000"> SIMPAN </font> </li>
      </ul>
    </div>
		
		</br>
									      			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include_once "../../includes/footer_app.php";
?>

