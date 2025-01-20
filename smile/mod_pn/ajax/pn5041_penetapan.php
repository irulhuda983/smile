<?
$pagetype="report";
$gs_pagetitle = "PN5002 - PENETAPAN KLAIM";
require_once "../../includes/header_app.php";
include_once '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_root_form = "pn5041.php";
$ls_current_form = "pn5041_penetapan.php";
$ls_form_penetapan = "pn5041_penetapan.php";

/*--------------------- Form History -------------------------------------------
File: pn5041_penetapan.php

Deskripsi:
-----------
File ini dipergunakan untuk entry penetapan klaim

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
$ls_rg_kategori	 = !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
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
	
	//update no,tgl dan petugas penetapan jika masih kosong ----------------------
	if ($ls_status_klaim=="PENETAPAN" && $ls_no_penetapan=="")
	{
  		$sql = "update sijstk.pn_klaim ".
  				 	 "set no_penetapan = '$ls_set_no_penetapan', ".
  					 "		tgl_penetapan = sysdate, ".
  					 "		petugas_penetapan = '$username', ".
  					 "		tgl_ubah = sysdate, ".
  					 "		petugas_ubah = '$username' ".
      	 	 	 "where kode_klaim = '$ls_kode_klaim' ";
      $DB->parse($sql);
      $DB->execute();		 	 
	}		
  $sql_jkp = "SELECT A.KODE_PENGAJUAN_SIAPKERJA FROM PN.PN_KLAIM_MANFAAT_DETIL_PHKJKP A WHERE A.KODE_KLAIM = '{$ls_kode_klaim}'";
  $DB->parse($sql_jkp);
  $DB->execute();
  $row_jkp = $DB->nextrow();
  $ls_kode_pengajuan_siapkerja = $row_jkp['KODE_PENGAJUAN_SIAPKERJA'];
}

if ($ls_jenis_klaim=="JHT")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN HARI TUA (JHT)";  	  	 
}else if ($ls_jenis_klaim=="JHM")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JHT/JKM";  	  	 
}else if ($ls_jenis_klaim=="JKK")
{
 	if ($ls_status_klaim	== "PENGAJUAN_TAHAP_I")
	{
	 	$gs_pagetitle = "PN5002 - PENGAJUAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)";	
 	}else
	{
	 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)"; 
	}		  	 
}else if ($ls_jenis_klaim=="JKM")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN KEMATIAN (JKM)";  	  	 
}else if ($ls_jenis_klaim=="JPN")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN PENSIUN (JP)";  	  	 
}else if ($ls_jenis_klaim=="JKP")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN KEHILANGAN PEKERJAAN (JKP)";  	  	 
}

// ------------- end KLAIM PROFILE ---------------------------------------------

include_once "../ajax/pn5041_js.php";
include_once "../ajax/pn5041_actionbutton.php";

?>
<script>
$(document).ready(function(){

	$('#btntapsubmit').attr("disabled", true);
	$('#btntaptolak').attr("disabled", true);
	$('#butsimpanajujkk1').attr("disabled", true);
	$('#btnsubmitajujkk1').attr("disabled", true);
	$("#btntapsubmit").removeClass('btn green');
	$("#btntaptolak").removeClass('btn green');	
	$("#butsimpanajujkk1").removeClass('btn green');	
	$("#btnsubmitajujkk1").removeClass('btn green');	
	
	$("#flag_disclaimer").change(function() {
		if(this.checked) {
		  $('#btntapsubmit').attr("disabled", false);
		  $('#btntaptolak').attr("disabled", false);
		  $('#butsimpanajujkk1').attr("disabled", false);
		  $('#btnsubmitajujkk1').attr("disabled", false);
		  $("#btntapsubmit").addClass('btn green');
		  $("#btntaptolak").addClass('btn green');
		  $("#butsimpanajujkk1").addClass('btn green');
		  $("#btnsubmitajujkk1").addClass('btn green');
		}
		else{
		  $('#btntapsubmit').attr("disabled", true);
		  $('#btntaptolak').attr("disabled", true);
		  $('#butsimpanajujkk1').attr("disabled", true);
		  $('#btnsubmitajujkk1').attr("disabled", true);
		  $("#btntapsubmit").removeClass('btn green');
		  $("#btntaptolak").removeClass('btn green');
		  $("#butsimpanajujkk1").removeClass('btn green');	
		  $("#btnsubmitajujkk1").removeClass('btn green');
		}
	});
});
</script>
<table class="captionfoxrm">
  <caption></caption>
  <tr><th colspan="3"></th></tr>
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
		<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
		<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="mid" name="mid" value="<?=$ls_mid;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
		<input type="hidden" id="rg_kategori" name="rg_kategori" value="<?=$ls_rg_kategori;?>">	
		<input type="hidden" id="btn_task" name="btn_task" value="">
		<input type="hidden" name="trigersubmit" value="0">

		<?
    if ($ls_jenis_klaim=="JHT")
    {
     	include_once "../ajax/pn5041_penetapan_jht.php";	  	 
    }else if ($ls_jenis_klaim=="JHM")
    {
     	include_once "../ajax/pn5041_penetapan_jhm.php";  	  	 
    }else if ($ls_jenis_klaim=="JKK")
    {
     	if ($ls_status_klaim	== "PENGAJUAN_TAHAP_I")
    	{
    	 	include_once "../ajax/pn5041_pengajuan_jkk.php";
     	}else
    	{
    	 	include_once "../ajax/pn5041_penetapan_jkk.php";
    	}		  	 
    }else if ($ls_jenis_klaim=="JKM")
    {
     	include_once "../ajax/pn5041_penetapan_jkm.php";  	  	 
    }else if ($ls_jenis_klaim=="JPN")
    {
     	include_once "../ajax/pn5041_penetapan_jpn.php";  	 
    }else if ($ls_jenis_klaim=="JKP")
    {
     	include_once "../ajax/pn5041_penetapan_jkp.php";  	 
    }
		?>
	<?php
	if ($ls_kode_klaim!="" && ($ls_status_klaim=="PERSETUJUAN" || $ls_status_klaim=="PENETAPAN" || $ls_status_klaim=="PENGAJUAN_TAHAP_I" ||$ls_root_sender=="pn5041.php") && $ls_status_submit_penetapan=="T")		
	{
	?>
	<div style="width:930px; text-align:center;">
		<br>   
		<input type="checkbox" id="flag_disclaimer" name="flag_disclaimer">
		<strong style="font-size: 12px;">Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload</strong>  
	</div>
	<?php
	}
	?>
    <div id="buttonbox" style="width:960px; text-align:center;">
      <?php
      if ($ls_kode_klaim!="" && ($ls_status_klaim=="PERSETUJUAN" || $ls_status_klaim=="PENETAPAN" || $ls_status_klaim=="PENGAJUAN_TAHAP_I" ||$ls_root_sender=="pn5041.php"))		
      {
	  ?>
				<?php
				if ($ls_status_submit_penetapan=="Y")
				{ 			
				?>
    			<input type="button" class="btn green" id="btntapcetak" name="btntapcetak" value="             CETAK PENETAPAN            " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_cetak.php?kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Cetak Penetapan Klaim',700,420,'no')"/>
  				<?
				}
				?>  			

				<?
				if ($ls_jenis_klaim=="JKK" && $ls_status_klaim	== "PENGAJUAN_TAHAP_I")
				{
				 	if ($ls_status_submit_pengajuan=="T")
					{ 
    				?>
      			<input type="button" class="btn green" id="butsimpanajujkk1" name="butsimpanajujkk1" value="       PROSES PENGAJUAN JKK TAHAP I       " onclick="if(confirm('Apakah anda yakin akan melakukan penyimpan data Pengajuan JKK Tahap I ..?')) fl_js_simpandataAjuJKK1();" />
      			&nbsp;
						<?
						//cek apakah kolom mandatory sudah diisi semuanya ------------------
						if ($ls_tapjkk1_kode_jenis_kasus=="KS01" && $ls_tapjkk1_kode_akibat_diderita!="") //kecelakaan kerja --------------------------
						{
  						?>
  						<input type="button" class="btn green" id="btnsubmitajujkk1" name="btnsubmitajujkk1" onclick="if(confirm('Apakah anda yakin akan mensubmit data ke Agenda Tahap II..?')) doSubmitAjuJKK1();"  value="          PROSES KE AGENDA TAHAP II         " />
      				<?
						}elseif ($ls_tapjkk1_kode_jenis_kasus=="KS02" && $ls_tapjkk1_kode_penyakit_timbul!="") //penyakit akibat kerja ---------------
						{
  						?>
  						<input type="button" class="btn green" id="btnsubmitajujkk1" name="btnsubmitajujkk1" onclick="if(confirm('Apakah anda yakin akan mensubmit data ke Agenda Tahap II..?')) doSubmitAjuJKK1();"  value="          PROSES KE AGENDA TAHAP II         " />
      				<?
						}
					}
				}else
				{
  				if ($ls_status_submit_penetapan=="T")
					{
					$ls_redaksi_jkk_jkm_pmi = "T";	
  					//cek apakah sudah ada input manfaat/penerima manfaat sudah diisikan
            			if ($ls_kode_tipe_klaim !="JPN01" && $ls_kode_tipe_klaim !="JKP01")
						{
  						$sql = "select count(*) as v_jml from sijstk.pn_klaim_penerima_manfaat a ".
            			 	 "where kode_klaim = '$ls_kode_klaim' ";
              $DB->parse($sql);
              $DB->execute();
              $row = $DB->nextrow();		
              $ln_cnt_penerima = $row['V_JML'];

			  //cek JKM PMI
			  $sql2 = "select a.kode_klaim, a.kode_segmen, a.kode_tipe_klaim,
			  (select count(1) from sijstk.pn_klaim_manfaat b 
			  where b.kode_klaim = a.kode_klaim and kode_manfaat in ('7','8','43') and nom_manfaat_netto is null) check_semua_manfaat,
			  (select count(1) from sijstk.pn_klaim_manfaat b 
			  where b.kode_klaim = a.kode_klaim and kode_manfaat in ('8','43') and nom_manfaat_netto is null) NULL_SANTUNAN_KEMATIAN,
			   (select count(1) from sijstk.pn_klaim_manfaat b 
			  where b.kode_klaim = a.kode_klaim and kode_manfaat = '7' and nom_manfaat_netto is null) NULL_BIAYA_PEMAKAMAN,
			  (select count(1) from sijstk.pn_klaim_manfaat_detil b 
			  where b.kode_klaim = a.kode_klaim and b.kode_manfaat in ('7','8','43') and nom_manfaat_netto is not null and kode_tipe_penerima='AW') check_penerima_aw
			  from pn.pn_klaim a
			  where a.kode_klaim='$ls_kode_klaim'";
				$DB->parse($sql2);
				$DB->execute();
				$row = $DB->nextrow();		
				$kodeKlaim = $row['KODE_KLAIM'];
				$kodeSegmen = $row['KODE_SEGMEN'];
				$kodeTipeKlaim =  $row['KODE_TIPE_KLAIM'];
				$cekSemuaManfaat =  $row['CHECK_SEMUA_MANFAAT'];
				$cekPenerimaAw =  $row['CHECK_PENERIMA_AW'];
				$ls_null_santunan_kematian = $row['NULL_SANTUNAN_KEMATIAN'];
				$ls_null_biaya_pemakaman = $row['NULL_BIAYA_PEMAKAMAN'];
							
							if ($ln_cnt_penerima=="0")
							{
							 	 $ls_disable_submit = "Y";
							}else if($kodeSegmen=="TKI" && ($kodeTipeKlaim=="JKM01" || "JKK01") && $cekSemuaManfaat > 0 && $cekPenerimaAw==1){
								 $ls_disable_submit = "Y";
								 $ls_redaksi_jkk_jkm_pmi = "Y";
							}else
							{
                // kode_hubungan dan nomor identitas smentara di remark per tgl 25-07-2019 untuk melewatkan kode hubungan dan nomor identitas yang null
    						// sambil menunggu pengecekan kode hubungan dan nomor identitas agar proses klaim bsa berjalan
                $sql = "select count(*) as v_jml2 from sijstk.pn_klaim_penerima_manfaat a ".
              			 	 "where kode_klaim = '$ls_kode_klaim' ".
											 "and (nama_rekening_penerima is null  or nama_penerima is null or kode_pos is null or npwp is null or kode_bank_pembayar is null) ";

                // $sql = "select count(*) as v_jml2 from sijstk.pn_klaim_penerima_manfaat a ".
                //        "where kode_klaim = '$ls_kode_klaim' ".
                //        "and (nama_rekening_penerima is null or nomor_identitas is null or nama_penerima is null or kode_pos is null or npwp is null or kode_bank_pembayar is null) ";
                       
                $DB->parse($sql);
                $DB->execute();
                $row = $DB->nextrow();		
                $ln_cnt_penerima_null = $row['V_JML2'];	
								
								if ($ln_cnt_penerima_null>="1")
								{
								 	$ls_disable_submit = "Y"; 
								}else
								{
								 	$ls_disable_submit = "T";	 
								}
							}
						}else if($ls_kode_tipe_klaim =="JKP01"){
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

							
							if($ls_flag_hitung_manfaat=="T" && $ls_bulan_manfaat_ke=="1"){
							$ls_disable_submit = "Y";
							}else{
								$sql = "select count(*) as v_jml from sijstk.pn_klaim_penerima_manfaat a ".
								"where kode_klaim = '$ls_kode_klaim' ";
								$DB->parse($sql);
								$DB->execute();
								$row = $DB->nextrow();		
								$ln_cnt_penerima = $row['V_JML'];
											
											if ($ln_cnt_penerima=="0")
											{
													$ls_disable_submit = "Y";
											}else
											{
								// kode_hubungan dan nomor identitas smentara di remark per tgl 25-07-2019 untuk melewatkan kode hubungan dan nomor identitas yang null
											// sambil menunggu pengecekan kode hubungan dan nomor identitas agar proses klaim bsa berjalan
								$sql = "select count(*) as v_jml2 from sijstk.pn_klaim_penerima_manfaat a ".
												"where kode_klaim = '$ls_kode_klaim' ".
															"and (nama_rekening_penerima is null  or nama_penerima is null or kode_pos is null or npwp is null or kode_bank_pembayar is null) ";
				
								// $sql = "select count(*) as v_jml2 from sijstk.pn_klaim_penerima_manfaat a ".
								//        "where kode_klaim = '$ls_kode_klaim' ".
								//        "and (nama_rekening_penerima is null or nomor_identitas is null or nama_penerima is null or kode_pos is null or npwp is null or kode_bank_pembayar is null) ";
										
								$DB->parse($sql);
								$DB->execute();
								$row = $DB->nextrow();		
								$ln_cnt_penerima_null = $row['V_JML2'];	
												
												if ($ln_cnt_penerima_null>="1")
												{
													$ls_disable_submit = "Y"; 
												}else
												{
													$ls_disable_submit = "T";	 
												}
											}
							} 
						}else
						{
						 	$ls_disable_submit = "T";		 
						}
						
						if ($ls_disable_submit=="Y")
						{	if($ls_kode_tipe_klaim =="JKP01"){					
  						?>
					<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="alert('Data penetapan klaim belum dapat disubmit, harap melakukan pemilihan dasar upah untuk perhitungan manfaat bulan ke-1 terlebih dahulu dan informasi penerima manfaat..!!!');"  value="                 PROSES                 " />
						<? } else if(($ls_kode_tipe_klaim =="JKK01" || $ls_kode_tipe_klaim =="JKM01") && $ls_redaksi_jkk_jkm_pmi == "Y" && $ls_null_santunan_kematian >= 1 ) { ?>
      				<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="alert('Penetapan manfaat santunan kematian belum dilakukan.');"  value="                 PROSES                 " />
					  <? } else if(($ls_kode_tipe_klaim =="JKK01" || $ls_kode_tipe_klaim =="JKM01") && $ls_redaksi_jkk_jkm_pmi == "Y" && $ls_null_biaya_pemakaman >= 1 ) { ?>
      				<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="alert('Penetapan manfaat biaya pemakaman belum dilakukan.');"  value="                 PROSES                 " />
						<? } else { ?>
							<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="alert('Data penetapan klaim belum dapat disubmit, harap isikan manfaat yg diterima dan informasi penerima manfaat..!!!');"  value="                 PROSES                 " />
						<?
						}
						}else
						{
  						?>
      				<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) doSubmitPenetapanTanpaOtentikasi();"  value="                 PROSES                 " />
    					<?						
						}
					}				
				}
				?>
			<?php
  		 	if (($ls_jenis_klaim=="JKK" && ($ls_status_submit_pengajuan=="T" || $ls_status_submit_penetapan=="T")) || ($ls_jenis_klaim!="JKK" && $ls_status_submit_penetapan=="T"))
  			{ 			
         	?>
    			<input type="button" class="btn green" id="btntaptolak" name="btntaptolak" onclick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapan_tolak.php?&kode_klaim=<?=$ls_kode_klaim;?>&root_sender=pn5041.php&sender=pn5041_penetapan.php&sender_mid=<?=$ls_sender_mid;?>&kode_pengajuan_siapkerja=<?=$ls_id_pointer_asal;?>','Penolakan',950,450,'no')" value="                 TOLAK                 " />
			<?
			}
			?>
      	<?
			}
      ?>
  		<input type="button" class="btn green" id="btntutup" name="btntutup" onclick="refreshRootForm();"  value="                 TUTUP                 " /> 
			    
		</div>	<!--end buttonbox-->		

  		<?
      if (isset($msg))		
      {
        ?>
          <fieldset style="width:1000px;">
		  <legend></legend>
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
include_once "../../includes/footer_app.php";
?>
