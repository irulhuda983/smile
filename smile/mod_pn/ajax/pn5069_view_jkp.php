<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$chId 	 	 			 = "SMILE";
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_role 	 = $_SESSION['regrole'];
$gs_kode_user 	 = $_SESSION["USER"];
$ls_type	 			 = $_POST["tipe"];
$ls_kode_klaim   = $_POST['KODE_KLAIM'];
/* =============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JHT
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)			 
			- 22/10/2019 : Sentralisasi Rekening dan Mengalihkan direct conn ke ws
									 	 ws JSPN5040/GetDataByKodeKlaim DATA_INPUTJHT			 
------------------------------------------------------------------------------*/

//get data input jht -----------------------------------------------------------
// $ls_jhtinput_kode_manfaat 	 				 		= $_POST["KODE_MANFAAT"]  						 	=="null" ? "" : $_POST["KODE_MANFAAT"];
// $ls_jhtinput_no_urut 	 				 					= $_POST["NO_URUT_MANFAAT"]  						=="null" ? "" : $_POST["NO_URUT_MANFAAT"];
// $ld_jhtinput_tgl_saldo_awaltahun 				= $_POST["TGL_SALDO_AWALTAHUN"]  				=="null" ? "" : $_POST["TGL_SALDO_AWALTAHUN"];
// $ln_jhtinput_nom_saldo_awaltahun 	 			= $_POST["NOM_SALDO_AWALTAHUN"]  				=="null" ? "" : $_POST["NOM_SALDO_AWALTAHUN"];
// $ld_jhtinput_tgl_pengembangan_estimasi 	= $_POST["TGL_PENGEMBANGAN_ESTIMASI"]  	=="null" ? "" : $_POST["TGL_PENGEMBANGAN_ESTIMASI"];
// $ln_jhtinput_nom_pengembangan_estimasi 	= $_POST["NOM_PENGEMBANGAN_ESTIMASI"]  	=="null" ? "" : $_POST["NOM_PENGEMBANGAN_ESTIMASI"];
// $ln_jhtinput_nom_manfaat_sudahdiambil 	= $_POST["NOM_MANFAAT_SUDAHDIAMBIL"]  	=="null" ? "" : $_POST["NOM_MANFAAT_SUDAHDIAMBIL"];	
// $ln_jhtinput_pengambilan_ke 						= $_POST["PENGAMBILAN_KE"]  						=="null" ? "" : $_POST["PENGAMBILAN_KE"];
// $ls_jhtinput_kode_tipe_penerima 				= $_POST["KODE_TIPE_PENERIMA"]  				=="null" ? "" : $_POST["KODE_TIPE_PENERIMA"];
// $ls_jhtinput_nama_tipe_penerima 				= $_POST["NAMA_TIPE_PENERIMA"]					=="null" ? "" : $_POST["NAMA_TIPE_PENERIMA"];
// $ls_jhtinput_kode_hubungan 							= $_POST["KODE_HUBUNGAN"]  						 	=="null" ? "" : $_POST["KODE_HUBUNGAN"];
// $ls_jhtinput_nama_hubungan 							= $_POST["NAMA_HUBUNGAN"]								=="null" ? "" : $_POST["NAMA_HUBUNGAN"];
// $ls_jhtinput_ket_hubungan_lainnya				= $_POST["KET_HUBUNGAN_LAINNYA"]  			=="null" ? "" : $_POST["KET_HUBUNGAN_LAINNYA"];
// $ls_jhtinput_no_urut_keluarga 					= $_POST["NO_URUT_KELUARGA"]  					=="null" ? "" : $_POST["NO_URUT_KELUARGA"];
// $ls_jhtinput_nomor_identitas 						= $_POST["NOMOR_IDENTITAS"]  						=="null" ? "" : $_POST["NOMOR_IDENTITAS"];
// $ls_jhtinput_nama_pemohon 							= $_POST["NAMA_PEMOHON"]  						 	=="null" ? "" : $_POST["NAMA_PEMOHON"];
// $ls_jhtinput_tempat_lahir 							= $_POST["TEMPAT_LAHIR"]  						 	=="null" ? "" : $_POST["TEMPAT_LAHIR"];
// $ld_jhtinput_tgl_lahir 									= $_POST["TGL_LAHIR"]  						 			=="null" ? "" : $_POST["TGL_LAHIR"];
// $ls_jhtinput_jenis_kelamin 							= $_POST["JENIS_KELAMIN"]  						 	=="null" ? "" : $_POST["JENIS_KELAMIN"];
// $ls_jhtinput_golongan_darah							= $_POST["GOLONGAN_DARAH"]  						=="null" ? "" : $_POST["GOLONGAN_DARAH"];
// $ls_jhtinput_alamat 										= $_POST["ALAMAT"]  						 				=="null" ? "" : $_POST["ALAMAT"];
// $ls_jhtinput_rt 												= $_POST["RT"]  						 						=="null" ? "" : $_POST["RT"];
// $ls_jhtinput_rw 												= $_POST["RW"]  						 						=="null" ? "" : $_POST["RW"];
// $ls_jhtinput_kode_kelurahan 						= $_POST["KODE_KELURAHAN"]  						=="null" ? "" : $_POST["KODE_KELURAHAN"];
// $ls_jhtinput_nama_kelurahan 						= $_POST["NAMA_KELURAHAN"]  						=="null" ? "" : $_POST["NAMA_KELURAHAN"];
// $ls_jhtinput_kode_kecamatan 						= $_POST["KODE_KECAMATAN"]  						=="null" ? "" : $_POST["KODE_KECAMATAN"];
// $ls_jhtinput_nama_kecamatan 						= $_POST["NAMA_KECAMATAN"]  						=="null" ? "" : $_POST["NAMA_KECAMATAN"];
// $ls_jhtinput_kode_kabupaten 						= $_POST["KODE_KABUPATEN"]  						=="null" ? "" : $_POST["KODE_KABUPATEN"];
// $ls_jhtinput_nama_kabupaten 						= $_POST["NAMA_KABUPATEN"]  						=="null" ? "" : $_POST["NAMA_KABUPATEN"];
// $ls_jhtinput_kode_pos 									= $_POST["KODE_POS"]  						 			=="null" ? "" : $_POST["KODE_POS"];
// $ls_jhtinput_telepon_area 							= $_POST["TELEPON_AREA"]  						 	=="null" ? "" : $_POST["TELEPON_AREA"];
// $ls_jhtinput_telepon 										= $_POST["TELEPON"]  						 				=="null" ? "" : $_POST["TELEPON"];
// $ls_jhtinput_telepon_ext 								= $_POST["TELEPON_EXT"]  						 		=="null" ? "" : $_POST["TELEPON_EXT"];
// $ls_jhtinput_handphone 									= $_POST["HANDPHONE"]  						 			=="null" ? "" : $_POST["HANDPHONE"];
// $ls_jhtinput_email 											= $_POST["EMAIL"]  						 					=="null" ? "" : $_POST["EMAIL"];
// $ls_jhtinput_npwp 											= $_POST["NPWP"]  						 					=="null" ? "" : $_POST["NPWP"];
//get data klaim jkp
$sql_phkjkp = "SELECT A.KODE_KLAIM,
A.STATUS_PKWT,
to_char(A.TGL_MULAI_PERJANJIAN_KERJA,'dd-mm-yyyy') TGL_MULAI_PERJANJIAN_KERJA,
to_char(A.TGL_AKHIR_PERJANJIAN_KERJA,'dd-mm-yyyy') TGL_AKHIR_PERJANJIAN_KERJA,
to_char(A.BLTH_AWAL_KEPESERTAAN_JKP,'mm-yyyy') BLTH_AWAL_KEPESERTAAN_JKP,
to_char(A.BLTH_NA_JKP,'mm-yyyy') BLTH_NA_JKP,
to_char(A.TGL_PHK,'dd-mm-yyyy') TGL_PHK,
(select to_char(max(tgl_awal_pelatihan),'dd-mm-yyyy') from pn.pn_klaim_jkp_pelatihan_kerja where kode_klaim = A.kode_klaim) TGL_PELATIHAN,
(select to_char(max(tgl_interview),'dd-mm-yyyy') from pn.pn_klaim_jkp_interview where kode_klaim = A.kode_klaim) TGL_ASSESMENT,
(select to_char(max(tgl_rekam),'dd-mm-yyyy') from siapkerja.sk_klaim_pengajuan@to_ec where kode_profile_tk_jkp = A.kode_profile_tk_jkp and bulan_manfaat_ke = '1') TGL_SIAPKERJA,
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
A.TAHAP_JKP,
A.BULAN_MANFAAT_KE,
A.FLAG_ENAM_BULAN_BERTURUT,
A.KODE_PROFILE_TK_JKP
FROM PN.PN_KLAIM_MANFAAT_DETIL_PHKJKP A
WHERE A.KODE_KLAIM = '$ls_kode_klaim'";
$DB->parse($sql_phkjkp);
$DB->execute();
$row_jkp = $DB->nextrow();

$ls_jkpkode_klaim                 = $row_jkp['KODE_KLAIM'];
$ls_jkpstatus_pkwt                = $row_jkp['STATUS_PKWT'];
$ls_jkptgl_mulai_perjanjian_kerja = $row_jkp['TGL_MULAI_PERJANJIAN_KERJA'];
$ls_jkptgl_akhir_perjanjian_kerja = $row_jkp['TGL_AKHIR_PERJANJIAN_KERJA'];
$ls_jkpblth_awal_keps_jkp         = $row_jkp['BLTH_AWAL_KEPESERTAAN_JKP'];
$ls_jkpblth_na_jkp                = $row_jkp['BLTH_NA_JKP'];
$ls_jkptgl_phk                    = $row_jkp['TGL_PHK'];
$ls_jkpkode_tipe_kasus_phk        = $row_jkp['KODE_TIPE_KASUS_PHK'];
$ls_jkpkasus_phk                  = $row_jkp['KASUS_PHK'];
$ls_jkpkode_sebab_phk             = $row_jkp['KODE_SEBAB_PHK'];
$ls_jkpsebab_phk                  = $row_jkp['SEBAB_PHK'];
$ls_jkptahap_jkp                  = $row_jkp['TAHAP_JKP'];
$ls_jkpbulan_manfaat_ke           = $row_jkp['BULAN_MANFAAT_KE'];
$ls_jkpflag_enam_bulan_berturut   = $row_jkp['FLAG_ENAM_BULAN_BERTURUT'];
$ls_jkpkode_profile_tk_jkp        = $row_jkp['KODE_PROFILE_TK_JKP'];
$ls_tgl_siapkerja                 = $row_jkp['TGL_SIAPKERJA'];
$ls_tgl_pelatihan                 = $row_jkp['TGL_PELATIHAN'];
$ls_tgl_assesment                 = $row_jkp['TGL_ASSESMENT'];



// var_dump('xxxx',$ls_jkpblth_awal_kepesertaan_jkp);

//end get data input jht -------------------------------------------------------
?>

<div class="div-row">
  <div class="div-col" style="width:49%; max-height: 100%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset style="height:395px;"><legend><b><i><font color="#009999">Manfaat JKP :</font></i></b></legend>
            <div class="form-row_kiri">
            <input type="hidden" id="kode_profil_tk_jkp" name="blth_awal_keps_jkp" value="<?=$ls_jkpkode_profile_tk_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">
            <input type="hidden" id="flag_berturut" name="blth_awal_keps_jkp" value="<?=$ls_jkpflag_enam_bulan_berturut;?>" maxlength="20" readonly class="disabled" style="width:270px;">
            <label  style = "text-align:right; width:160px;">Status PKWT &nbsp;</label>
                <input type="text" id="blth_awal_keps_jkp" name="blth_awal_keps_jkp" value="<?=$ls_jkpstatus_pkwt;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>																																																				
            <div class="clear"></div>
            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Mulai Perjanjian Kerja &nbsp;</label>
                <input type="text" id="blth_awal_keps_jkp" name="blth_awal_keps_jkp" value="<?=$ls_jkptgl_mulai_perjanjian_kerja;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>																																																				
            <div class="clear"></div>
            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Akhir Perjanjian Kerja &nbsp;</label>
                <input type="text" id="blth_awal_keps_jkp" name="blth_awal_keps_jkp" value="<?=$ls_jkptgl_akhir_perjanjian_kerja;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>																																																				
            <div class="clear"></div>
            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">BLTH Awal Keps JKP &nbsp;</label>
                <input type="text" id="blth_awal_keps_jkp" name="blth_awal_keps_jkp" value="<?=$ls_jkpblth_awal_keps_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>																																																				
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">BLTH Non Aktif JKP &nbsp;</label>
                <input type="text" id="blth_na_jkp" name="blth_na_jkp" value="<?=$ls_jkpblth_na_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal PHK &nbsp;</label>
                <input type="text" id="tgl_na" name="tgl_na" value="<?=$ls_jkptgl_phk;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tipe Kasus &nbsp;</label>
            <textarea cols="42" rows="2" id="status_layak_klaim" name="status_layak_klaim" style="width:280px;background-color:#F5F5F5" maxlength="20" readonly class="disabled"><?=$ls_jkpkode_tipe_kasus_phk.'-'.$ls_jkpkasus_phk;?></textarea>     					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Sebab Klaim</label>
            <textarea cols="42" rows="2" id="jumlah_bulan_iur_jkp" style="width:280px;background-color:#F5F5F5" name="jumlah_bulan_iur_jkp" value="" maxlength="20" readonly class="disabled"><?=$ls_jkpkode_sebab_phk.'-'.$ls_jkpsebab_phk;?></textarea>              					
            </div>	
            <div class="clear"></div> 
          
            <!-- <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Flag Enam Bulan Berturut &nbsp;</label>
                <input type="text" id="flag_enam_bulan_berturut" name="flag_enam_bulan_berturut" value="<?=$ls_jkpflag_enam_bulan_berturut;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	 -->
            <div class="clear"></div>

            <!-- </br>
            </br> -->

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tahap JKP &nbsp;</label>
                <input type="text" id="tahap_jkp" name="tahap_jkp" value="<?=$ls_jkptahap_jkp;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>


            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Bulan Manfaat &nbsp;</label>
                <input type="text" id="bulan_ke" name="bulan_ke" value="<?=$ls_jkpbulan_manfaat_ke;?>" maxlength="20" readonly class="disabled" style="width:270px;">                					
            </div>	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Siapkerja&nbsp;&nbsp;&nbsp;</label>
              <input type="text" value="<?=$ls_tgl_siapkerja;?>" id="tgl_siapkerja" name="tgl_siapkerja" style="width:200px;" readonly class="disabled">
            </div>																																								
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Assesment&nbsp;&nbsp;&nbsp;</label>
              <input type="text" value="" id="rgl_assesment" name="rgl_assesment" style="width:200px;" readonly class="disabled">
            </div>																																								
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label  style = "text-align:right; width:160px;">Tanggal Pelatihan&nbsp;&nbsp;&nbsp;</label>
              <input type="text" value="<?=$ls_tgl_pelatihan;?>" id="tgl_pelatihan" name="tgl_pelatihan" style="width:200px;" readonly class="disabled">
            </div>																																								
            <div class="clear"></div>


            <div class="clear"></div>
            <div class="clear"></div>
            <div class="clear"></div>
            <div class="clear"></div>
																									 
				</fieldset>	
      </div>
    </div>
  </div>
  <div class="div-col" style="width:1%;">
  </div>

<!-- GET DATA INFO PENERIMA MANFAAT -->
<?php
$sql = "select
kode_manfaat, no_urut, to_char(a.tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun,
a.nom_saldo_awaltahun,
to_char(a.tgl_pengembangan_estimasi,'dd/mm/yyyy') tgl_pengembangan_estimasi,
a.nom_pengembangan_estimasi, a.nom_manfaat_sudahdiambil, a.pengambilan_ke
from sijstk.pn_klaim_manfaat_detil a
where kode_klaim = '$ls_kode_klaim'
and kode_manfaat = '46'
and no_urut = 1 ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_jhtinput_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_jhtinput_no_urut 	 				 					= $row["NO_URUT"];
$ld_jhtinput_tgl_saldo_awaltahun 				= $row["TGL_SALDO_AWALTAHUN"];
$ln_jhtinput_nom_saldo_awaltahun 	 			= $row["NOM_SALDO_AWALTAHUN"];
$ld_jhtinput_tgl_pengembangan_estimasi 	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_pengembangan_estimasi 	= $row["NOM_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_manfaat_sudahdiambil 	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];	
$ln_jhtinput_pengambilan_ke 						= $row["PENGAMBILAN_KE"];	 	 

$sql = "select 
a.kode_klaim, a.kode_tipe_penerima, (select nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima = a.kode_tipe_penerima) nama_tipe_penerima,a.kode_hubungan, a.ket_hubungan_lainnya, a.no_urut_keluarga, 
a.nomor_identitas, a.nama_pemohon, a.tempat_lahir, to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin,a.golongan_darah, 
a.alamat, a.rt, a.rw, 
a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan and kode_kecamatan=a.kode_kecamatan and kode_pos =a.kode_pos) nama_kelurahan,
a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan,
a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten,
(SELECT NAMA_PROPINSI
          FROM MS.MS_PROPINSI
         WHERE KODE_PROPINSI = (SELECT KODE_PROPINSI
                                  FROM MS.MS_KABUPATEN
                                 WHERE KODE_KABUPATEN = A.KODE_KABUPATEN)
        )
           NAMA_PROPINSI,
a.kode_pos, a.telepon_area, a.telepon, 
a.telepon_ext, a.handphone, a.email, a.npwp
from sijstk.pn_klaim_penerima_manfaat a
where kode_klaim = '$ls_kode_klaim'
and exists
(
select null from sijstk.pn_klaim_manfaat_detil
where kode_klaim = a.kode_klaim
and kode_tipe_penerima = a.kode_tipe_penerima
and kode_manfaat = '$ls_jhtinput_kode_manfaat'    
)
and rownum = 1";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_jhtinput_kode_tipe_penerima 	= $row["KODE_TIPE_PENERIMA"];
$ls_jhtinput_nama_tipe_penerima 	= $row["NAMA_TIPE_PENERIMA"];
$ls_jhtinput_kode_hubungan 				= $row["KODE_HUBUNGAN"];
$ls_jhtinput_ket_hubungan_lainnya	= $row["KET_HUBUNGAN_LAINNYA"];
$ls_jhtinput_no_urut_keluarga 		= $row["NO_URUT_KELUARGA"];
$ls_jhtinput_nomor_identitas 			= $row["NOMOR_IDENTITAS"];
$ls_jhtinput_nama_pemohon 				= $row["NAMA_PEMOHON"];
$ls_jhtinput_tempat_lahir 				= $row["TEMPAT_LAHIR"];
$ld_jhtinput_tgl_lahir 						= $row["TGL_LAHIR"];
$ls_jhtinput_jenis_kelamin 				= $row["JENIS_KELAMIN"];
$ls_jhtinput_golongan_darah				= $row["GOLONGAN_DARAH"];
$ls_jhtinput_alamat 							= $row["ALAMAT"];
$ls_jhtinput_rt 									= $row["RT"];
$ls_jhtinput_rw 									= $row["RW"];
$ls_jhtinput_kode_kelurahan 			= $row["KODE_KELURAHAN"];
$ls_jhtinput_nama_kelurahan 			= $row["NAMA_KELURAHAN"];
$ls_jhtinput_kode_kecamatan 			= $row["KODE_KECAMATAN"];
$ls_jhtinput_nama_kecamatan 			= $row["NAMA_KECAMATAN"];
$ls_jhtinput_kode_kabupaten 			= $row["KODE_KABUPATEN"];
$ls_jhtinput_nama_kabupaten 			= $row["NAMA_KABUPATEN"];
$ls_jhtinput_kode_pos 						= $row["KODE_POS"];
$ls_jhtinput_telepon_area 				= $row["TELEPON_AREA"];
$ls_jhtinput_telepon 							= $row["TELEPON"];
$ls_jhtinput_telepon_ext 					= $row["TELEPON_EXT"];
$ls_jhtinput_handphone 						= $row["HANDPHONE"];
$ls_jhtinput_email 								= $row["EMAIL"];
$ls_jhtinput_npwp 								= $row["NPWP"];
$ls_jhtinput_nama_propinsi        = $row["NAMA_PROPINSI"];

if ($ls_jhtinput_jenis_kelamin == "L")
{
 	$ls_jhtinput_nama_jenis_kelamin = "LAKI-LAKI";   	 
}else if ($ls_jhtinput_jenis_kelamin == "P")
{
 	$ls_jhtinput_nama_jenis_kelamin = "PEREMPUAN";   	 
}else
{
 	$ls_jhtinput_nama_jenis_kelamin = "";   	 
}

?>
  <div class="div-col-right" style="width:50%;">
    <div class="div-row">
      <div class="div-col" style="width: 100%">
        <fieldset>
          <legend>Penerima Manfaat</legend>
          <div class="form-row_kiri">
            <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
						<input type="text" id="jhtinput_nama_tipe_penerima" name="jhtinput_nama_tipe_penerima" value = "<?=$ls_jhtinput_nama_tipe_penerima;?>" readonly class="disabled" style="width:270px;">
						<input type="hidden" id="jhtinput_kode_tipe_penerima" name="jhtinput_kode_tipe_penerima" value = "<?=$ls_jhtinput_kode_tipe_penerima;?>">
						<input type="hidden" id="jhtinput_kode_tipe_penerima_old" name="jhtinput_kode_tipe_penerima_old" value="<?=$ls_jhtinput_kode_tipe_penerima;?>">
            <input type="hidden" id="jhtinput_kode_manfaat" name="jhtinput_kode_manfaat" value="<?=$ls_jhtinput_kode_manfaat;?>">
            <input type="hidden" id="jhtinput_no_urut" name="jhtinput_no_urut" value="<?=$ls_jhtinput_no_urut;?>">
          </div>		    																																				
          <div class="clear"></div>
          
          <span id="span_jhtinput_kode_hubungan" style="display:none;">
            <div class="form-row_kiri">
              <label style = "text-align:right;">Ahli Waris *</label>
							<input type="text" id="jhtinput_nama_hubungan" name="jhtinput_nama_hubungan" value = "<?=$ls_jhtinput_nama_hubungan;?>" readonly class="disabled" style="width:270px;">
							<input type="hidden" id="jhtinput_kode_hubungan" name="jhtinput_kode_hubungan" value = "<?=$ls_jhtinput_kode_hubungan;?>">
            	<input type="hidden" id="jhtinput_no_urut_keluarga" name="jhtinput_no_urut_keluarga" value="<?=$ls_jhtinput_no_urut_keluarga;?>">        				
            </div>																																									
            <div class="clear"></div>
          </span>
      
          <span id="span_jhtinput_ket_hubungan_lainnya" style="display:none;">
            <div class="form-row_kiri">
              <label style = "text-align:right;">&nbsp; *</label>
							<input type="text" id="jhtinput_ket_hubungan_lainnya" name="jhtinput_ket_hubungan_lainnya" value="<?=$ls_jhtinput_ket_hubungan_lainnya;?>" readonly class="disabled" style="width:270px;">
						</div>																																									
            <div class="clear"></div>			
          </span>				
          
          <div class="form-row_kiri">
            <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
            <input type="text" id="jhtinput_nama_pemohon" name="jhtinput_nama_pemohon" value="<?=$ls_jhtinput_nama_pemohon;?>" style="width:270px;" readonly class="disabled">
          </div>	
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Tempat & Tgl Lahir &nbsp; *</label>
						<input type="text" id="jhtinput_tempat_lahir" name="jhtinput_tempat_lahir" value="<?=$ls_jhtinput_tempat_lahir;?>" style="width:164px;" readonly class="disabled">
            <input type="text" id="jhtinput_tgl_lahir" name="jhtinput_tgl_lahir" value="<?=$ld_jhtinput_tgl_lahir;?>" style="width:82px;" readonly class="disabled">		
          </div>																													
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Jenis Kelamin *</label>
						<input type="text" id="jhtinput_nama_jenis_kelamin" name="jhtinput_nama_jenis_kelamin" value="<?=$ls_jhtinput_nama_jenis_kelamin;?>" style="width:100px;" readonly class="disabled">
						&nbsp;
            Gol. Darah
						<input type="text" id="jhtinput_golongan_darah" name="jhtinput_golongan_darah" value="<?=$ls_jhtinput_golongan_darah;?>" style="width:60px;" readonly class="disabled">
          </div>																																									
          <div class="clear"></div>
				
          </br>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;&nbsp;*</label>		 	    				
            <textarea cols="255" id="jhtinput_alamat" name="jhtinput_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:239px;height:18px;background-color:#F5F5F5;"><?=$ls_jhtinput_alamat;?></textarea>
					</div>																																										
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_rt" name="jhtinput_rt" value="<?=$ls_jhtinput_rt;?>" style="width:133px;" readonly class="disabled">
            /
            <input type="text" id="jhtinput_rw" name="jhtinput_rw" value="<?=$ls_jhtinput_rw;?>" style="width:80px;" readonly class="disabled">		
          </div>																																										
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kelurahan" name="jhtinput_nama_kelurahan" value="<?=$ls_jhtinput_nama_kelurahan;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kelurahan" name="jhtinput_kode_kelurahan" value="<?=$ls_jhtinput_kode_kelurahan;?>">      
          </div>																																																	
          <div class="clear"></div>
          <div class="form-row_kiri">
            <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kecamatan" name="jhtinput_nama_kecamatan" value="<?=$ls_jhtinput_nama_kecamatan;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kecamatan" name="jhtinput_kode_kecamatan" value="<?=$ls_jhtinput_kode_kecamatan;?>">			
          </div>
          <div class="clear"></div>
              
          <div class="form-row_kiri">
            <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="text" id="jhtinput_nama_kabupaten" name="jhtinput_nama_kabupaten" value="<?=$ls_jhtinput_nama_kabupaten;?>" style="width:239px;" readonly class="disabled">
            <input type="hidden" id="jhtinput_kode_kabupaten" name="jhtinput_kode_kabupaten" value="<?=$ls_jhtinput_kode_kabupaten;?>">      
            <input type="hidden" id="jhtinput_kode_propinsi" name="jhtinput_kode_propinsi" value="<?=$ls_jhtinput_kode_propinsi;?>">
            <input type="hidden" id="jhtinput_nama_propinsi" name="jhtinput_nama_propinsi" value="<?=$ls_jhtinput_nama_propinsi;?>">
          </div>
          <div class="clear"></div>	
					
          <div class="form-row_kiri">
            <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
            <input type="text" id="jhtinput_kode_pos" name="jhtinput_kode_pos" value="<?=$ls_jhtinput_kode_pos;?>" style="width:206px;" readonly class="disabled">
          </div>																																																	
          <div class="clear"></div>
          <div class="form-row_kiri">
            <label style = "text-align:right;">Provinsi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
            <input type="text" id="jhtinput_kode_pos" name="jhtinput_kode_pos" value="<?=$ls_jhtinput_nama_propinsi;?>" style="width:206px;" readonly class="disabled">
          </div>																																																	
          <div class="clear"></div>                                                         
          </br>

          <div class="form-row_kiri">
            <label style = "text-align:right;">Email &nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_email" name="jhtinput_email" value="<?=$ls_jhtinput_email;?>" style="width:270px;" readonly class="disabled">
          </div>
          <div class="clear"></div>

          <div class="form-row_kiri">
            <label style = "text-align:right;">No. Telp &nbsp;</label>	    				
            <input type="text" id="jhtinput_telepon_area" name="jhtinput_telepon_area" value="<?=$ls_jhtinput_telepon_area;?>" style="width:40px;" readonly class="disabled">
            <input type="text" id="jhtinput_telepon" name="jhtinput_telepon" value="<?=$ls_jhtinput_telepon;?>" style="width:148px;" readonly class="disabled">
            &nbsp;ext.
            <input type="text" id="jhtinput_telepon_ext" name="jhtinput_telepon_ext" value="<?=$ls_jhtinput_telepon_ext;?>" style="width:40px;" readonly class="disabled">
          </div>
          <div class="clear"></div>					          
      
          <div class="form-row_kiri">
            <label style = "text-align:right;">Handphone &nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhtinput_handphone" name="jhtinput_handphone" value="<?=$ls_jhtinput_handphone;?>" style="width:270px;" readonly class="disabled">
          </div>																																																																																														
          <div class="clear"></div>
					
          <div class="form-row_kiri">
            <label style = "text-align:right;">No. Identitas &nbsp;</label>
            <input type="text" id="jhtinput_nomor_identitas" name="jhtinput_nomor_identitas" value="<?=$ls_jhtinput_nomor_identitas;?>" style="width:270px;" readonly class="disabled">
          </div>																																									
          <div class="clear"></div>
          <div class="form-row_kiri">
            <label style = "text-align:right;">NPWP &nbsp; </label>
            <input type="text" id="jhtinput_npwp" name="jhtinput_npwp" value="<?=$ls_jhtinput_npwp;?>" style="width:240px;" readonly class="disabled">
          </div>																																									
          <div class="clear"></div>      
						
          <br></br>																					
        </fieldset>	
      </div>
    </div>
  </div>
</div>

<script language="JavaScript">    
  function fl_js_jhtinput_kode_tipe_penerima() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
							
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'none';
			window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'none';	
    }
  }
	
  function fl_js_jhtinput_ahliwaris_lain() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
		var	v_kode_hubungan = window.document.getElementById('jhtinput_kode_hubungan').value;
    
    if (v_kode_tipe_penerima =="AW" && v_kode_hubungan=="L") //Ahli Waris Lainnya ---------
    {
		 	window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'block'; 
    }else
    {
      window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'none'; 	
    } 	
  }
  function fl_js_jkplist_manfaat() 
  { 
		var	v_bulan_manfaat = window.document.getElementById('bulan_ke').value;
    
    if (v_bulan_manfaat == 1) //menampilkan tahap jkp <> I
    {
		 	window.document.getElementById("fieldset_aktifitas_jkp").style.display = 'none'; 
    }else
    {
      window.document.getElementById("fieldset_aktifitas_jkp").style.display = 'block'; 	
    } 	
  }
	
	$(document).ready(function(){
    // fl_js_jkplist_manfaat();
		fl_js_jhtinput_kode_tipe_penerima();
		fl_js_jhtinput_ahliwaris_lain();
	});							
</script>
