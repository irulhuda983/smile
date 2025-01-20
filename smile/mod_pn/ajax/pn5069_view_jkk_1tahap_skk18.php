<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Kerugian atas tindakan pihak lain/kehilangan 
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 22/01/2018 : Pembuatan Form (Tim SMILE)			
			- 04/11/2019 : pemindahan query sql ke webservice								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_kehilangan 						= $_POST["TGL_KEJADIAN"]  						=="null" ? "" : $_POST["TGL_KEJADIAN"];
$ls_jkk_ket_tambahan	 						= $_POST["KET_TAMBAHAN"]							=="null" ? "" : $_POST["KET_TAMBAHAN"];
$ls_jkk_tipe_negara_kejadian		 	= $_POST["TIPE_NEGARA_KEJADIAN"]  		=="null" ? "" : $_POST["TIPE_NEGARA_KEJADIAN"];
$ls_jkk_nama_tipe_negara_kejadian = $_POST["NAMA_TIPE_NEGARA_KEJADIAN"] =="null" ? "" : $_POST["NAMA_TIPE_NEGARA_KEJADIAN"];	
?>

<fieldset><legend>Informasi Kehilangan</legend>
  </br>												
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Negara Kejadian *</label>		
    <input type="text" id="jkk_nama_tipe_negara_kejadian" name="jkk_nama_tipe_negara_kejadian" value="<?=$ls_jkk_nama_tipe_negara_kejadian;?>" style="width:270px;" readonly class="disabled"> 
    <input type="hidden" id="jkk_tipe_negara_kejadian" name="jkk_tipe_negara_kejadian" value="<?=$ls_jkk_tipe_negara_kejadian;?>">
  </div>																																												
  <div class="clear"></div>
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Lokasi Kehilangan *</label>		 	    				
  	<textarea cols="255" rows="1" id="jkk_ket_tambahan" name="jkk_ket_tambahan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:270px;background-color:#F5F5F5"><?=$ls_jkk_ket_tambahan;?></textarea>    	
  </div>
  <div class="clear"></div>		
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Tgl Kehilangan</label>
  	<input type="text" id="jkk_tgl_kehilangan" name="jkk_tgl_kehilangan" value="<?=$ld_jkk_tgl_kehilangan;?>" style="width:250px;" readonly class="disabled">    	   							
  </div>	
  <div class="clear"></div>
  
  </br>																							  
</fieldset>						
