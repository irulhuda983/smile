<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Pemulangan TKI Bermasalah
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 22/01/2018 : Pembuatan Form (Tim SMILE)								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_pemulangan = $_POST["TGL_KEJADIAN"] =="null" ? "" : $_POST["TGL_KEJADIAN"];
$ls_jkk_ket_tambahan	 = $_POST["KET_TAMBAHAN"]	=="null" ? "" : $_POST["KET_TAMBAHAN"];		
?>
<fieldset><legend>Informasi Pemulangan PMI Bermasalah</legend>
  </br>												
  
	<div class="form-row_kiri">
  <label style = "text-align:right;">Sebab Pemulangan *</label>		 	    				
  	<textarea cols="255" rows="1" id="jkk_ket_tambahan" name="jkk_ket_tambahan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:270px;background-color:#F5F5F5"><?=$ls_jkk_ket_tambahan;?></textarea>    	
  </div>
  <div class="clear"></div>		
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Tgl Pemulangan</label>
  	<input type="text" id="jkk_tgl_pemulangan" name="jkk_tgl_pemulangan" value="<?=$ld_jkk_tgl_pemulangan;?>" style="width:270px;" readonly class="disabled">      	   							
  </div>	
  <div class="clear"></div>
  
  </br>																							  
</fieldset>						
