<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JKK Tahap II
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)		
			- 04/11/2019 : pengalihaan query sql ke webservice						 						 
-----------------------------------------------------------------------------*/
$ls_jkk2_kode_kondisi_terakhir = $_POST["KODE_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["KODE_KONDISI_TERAKHIR"];
$ls_jkk2_nama_kondisi_terakhir = $_POST["NAMA_KONDISI_TERAKHIR"] =="null" ? "" : $_POST["NAMA_KONDISI_TERAKHIR"];
$ld_jkk2_tgl_kondisi_terakhir  = $_POST["TGL_KONDISI_TERAKHIR"]  =="null" ? "" : $_POST["TGL_KONDISI_TERAKHIR"];
?>
<fieldset><legend>Perkembangan Laporan Kondisi TK</legend>
  </br>
  <div class="form-row_kiri">
  <label style = "text-align:right;">Kondisi Terakhir &nbsp;</label>
    <input type="text" id="jkk2_nama_kondisi_terakhir" name="jkk2_nama_kondisi_terakhir" value="<?=$ls_jkk2_nama_kondisi_terakhir;?>" style="width:270px;" readonly class="disabled"> 
    <input type="hidden" id="jkk2_kode_kondisi_terakhir" name="jkk2_kode_kondisi_terakhir" value="<?=$ls_jkk2_kode_kondisi_terakhir;?>">
  </div>																																									
  <div class="clear"></div>
																	
  <div class="form-row_kiri">
  <label style = "text-align:right;">Tgl Kondisi TK &nbsp;</label>
    <input type="text" id="jkk2_tgl_kondisi_terakhir" name="jkk2_tgl_kondisi_terakhir" value="<?=$ld_jkk2_tgl_kondisi_terakhir;?>" style="width:250px;" readonly class="disabled">
  </div>    		
  <div class="clear"></div>
  
  </br> 	    																						  
</fieldset>
