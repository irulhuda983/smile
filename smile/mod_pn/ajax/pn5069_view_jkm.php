<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";

$ld_jkm_tgl_kematian  			 			= $_POST["TGL_KEMATIAN"]  						=="null" ? "" : $_POST["TGL_KEMATIAN"];
$ls_jkm_ket_tambahan				 			= $_POST["KET_TAMBAHAN"]  						=="null" ? "" : $_POST["KET_TAMBAHAN"];
$ls_jkm_tipe_negara_kejadian 			= $_POST["TIPE_NEGARA_KEJADIAN"]  		=="null" ? "" : $_POST["TIPE_NEGARA_KEJADIAN"];
$ls_jkm_nama_tipe_negara_kejadian = $_POST["NAMA_TIPE_NEGARA_KEJADIAN"] =="null" ? "" : $_POST["NAMA_TIPE_NEGARA_KEJADIAN"];
$ls_jkm_kode_segmen 							= $_POST["KODE_SEGMEN"] 							=="null" ? "" : $_POST["KODE_SEGMEN"];
?>
<fieldset><legend>Input Data Klaim JKM</legend>
	</br>
	
  <span id="jkm_span_tipe_negara_kejadian" style="display:none;">
    <div class="form-row_kiri">
    <label style = "text-align:right;">Negara Kejadian *</label>		
      <input type="text" id="jkm_nama_tipe_negara_kejadian" name="jkm_nama_tipe_negara_kejadian" value="<?=$ls_jkm_nama_tipe_negara_kejadian;?>" style="width:270px;" readonly class="disabled"> 
      <input type="hidden" id="jkm_tipe_negara_kejadian" name="jkm_tipe_negara_kejadian" value="<?=$ls_jkm_tipe_negara_kejadian;?>">
			<input type="hidden" id="jkm_kode_segmen" name="jkm_kode_segmen" value="<?=$ls_jkm_kode_segmen;?>">
    </div>																																												
    <div class="clear"></div>
  </span>
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Tgl Kematian &nbsp;</label>
  	<input type="text" id="jkm_tgl_kematian" name="jkm_tgl_kematian" value="<?=$ld_jkm_tgl_kematian;?>" style="width:270px;" readonly class="disabled">      	
  </div>    		
  <div class="clear"></div>
  
  <div class="form-row_kiri">
  <label style = "text-align:right;">Keterangan &nbsp;</label>
  	<textarea cols="255" rows="1" id="jkm_ket_tambahan" name="jkm_ket_tambahan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:270px;background-color:#F5F5F5;"><?=$ls_jkm_ket_tambahan;?></textarea>	   							
  </div>
  <div class="clear"></div>
  
  </br> 	    																						  
</fieldset>								

<script language="javascript">
  //update 23/01/2019 tambahan terkait TKI -------------------------------------
  function fl_js_jkm_tipe_negara_kejadian() 
  { 
    var v_kode_segmen = window.document.getElementById("jkm_kode_segmen").value;
    
    if (v_kode_segmen =="TKI")
    {    
    	window.document.getElementById("jkm_span_tipe_negara_kejadian").style.display = 'block';
    }else
    {
      window.document.getElementById("jkm_span_tipe_negara_kejadian").style.display = 'none';	 
    }
  }
	
	$(document).ready(function(){
		fl_js_jkm_tipe_negara_kejadian();
	});		
</script>
