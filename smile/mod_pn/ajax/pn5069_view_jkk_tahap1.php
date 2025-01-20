<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Data Klaim JKK Tahap I
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)		
			- 04/11/2019 : pengalihaan query sql ke webservice						 						 
-----------------------------------------------------------------------------*/
$ls_jkk1_tipe_negara_kejadian		 		= $_POST["TIPE_NEGARA_KEJADIAN"]  				 	=="null" ? "" : $_POST["TIPE_NEGARA_KEJADIAN"];
$ls_jkk1_nama_tipe_negara_kejadian 	= $_POST["NAMA_TIPE_NEGARA_KEJADIAN"]  			=="null" ? "" : $_POST["NAMA_TIPE_NEGARA_KEJADIAN"];										 
$ld_jkk1_tgl_kecelakaan					 		= $_POST["TGL_KECELAKAAN"]  								=="null" ? "" : $_POST["TGL_KECELAKAAN"];
$ls_jkk1_kode_jam_kecelakaan		 		= $_POST["KODE_JAM_KECELAKAAN"]  						=="null" ? "" : $_POST["KODE_JAM_KECELAKAAN"];
$ls_jkk1_nama_jam_kecelakaan		 		= $_POST["NAMA_JAM_KECELAKAAN"]  						=="null" ? "" : $_POST["NAMA_JAM_KECELAKAAN"];
$ls_jkk1_kode_jenis_kasus				 		= $_POST["KODE_JENIS_KASUS"]  							=="null" ? "" : $_POST["KODE_JENIS_KASUS"];
$ls_jkk1_nama_jenis_kasus				 		= $_POST["NAMA_JENIS_KASUS"]  							=="null" ? "" : $_POST["NAMA_JENIS_KASUS"];
$ls_jkk1_kode_lokasi_kecelakaan  		= $_POST["KODE_LOKASI_KECELAKAAN"]  				=="null" ? "" : $_POST["KODE_LOKASI_KECELAKAAN"];
$ls_jkk1_nama_lokasi_kecelakaan  		= $_POST["NAMA_LOKASI_KECELAKAAN"]  				=="null" ? "" : $_POST["NAMA_LOKASI_KECELAKAAN"];
$ls_jkk1_nama_tempat_kecelakaan	 		= $_POST["NAMA_TEMPAT_KECELAKAAN"]  				=="null" ? "" : $_POST["NAMA_TEMPAT_KECELAKAAN"];
$ls_jkk1_ket_tambahan						 		= $_POST["KET_TAMBAHAN"]  									=="null" ? "" : $_POST["KET_TAMBAHAN"];
$ls_jkk1_kode_segmen						 		= $_POST["KODE_SEGMEN"]  										=="null" ? "" : $_POST["KODE_SEGMEN"];
?>						
<fieldset><legend>Informasi Kecelakaan pada Laporan JKK Tahap I</legend>
		</br>
		<span id="jkk1_span_tipe_negara_kejadian" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Negara Kejadian *</label>		 	    				
        <input type="text" id="jkk1_nama_tipe_negara_kejadian" name="jkk1_nama_tipe_negara_kejadian" value="<?=$ls_jkk1_nama_tipe_negara_kejadian;?>" style="width:270px;" readonly class="disabled"> 
				<input type="hidden" id="jkk1_tipe_negara_kejadian" name="jkk1_tipe_negara_kejadian" value="<?=$ls_jkk1_tipe_negara_kejadian;?>">
				<input type="hidden" id="jkk1_kode_segmen" name="jkk1_kode_segmen" value="<?=$ls_jkk1_kode_segmen;?>">	
      </div>																																												
    	<div class="clear"></div>		
		</span>
																	
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Kecelakaan *</label>
      <input type="text" id="jkk1_tgl_kecelakaan" name="jkk1_tgl_kecelakaan" value="<?=$ld_jkk1_tgl_kecelakaan;?>" style="width:270px;" readonly class="disabled">      	   							
    </div>	
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jam Kecelakaan &nbsp; *</label>
			<input type="text" id="jkk1_nama_jam_kecelakaan" name="jkk1_nama_jam_kecelakaan" value="<?=$ls_jkk1_nama_jam_kecelakaan;?>" style="width:270px;" readonly class="disabled"> 
			<input type="hidden" id="jkk1_kode_jam_kecelakaan" name="jkk1_kode_jam_kecelakaan" value="<?=$ls_jkk1_kode_jam_kecelakaan;?>">
    </div>																																												
  	<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jenis Kasus &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>
			<input type="text" id="jkk1_nama_jenis_kasus" name="jkk1_nama_jenis_kasus" value="<?=$ls_jkk1_nama_jenis_kasus;?>" style="width:270px;" readonly class="disabled"> 
			<input type="hidden" id="jkk1_kode_jenis_kasus" name="jkk1_kode_jenis_kasus" value="<?=$ls_jkk1_kode_jenis_kasus;?>">	
    </div>																																											
  	<div class="clear"></div>

		<span id="jkk1_span_lokasi_kecelakaan" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Lokasi Kecelakaan &nbsp; *</label>
  			<input type="text" id="jkk1_nama_lokasi_kecelakaan" name="jkk1_nama_lokasi_kecelakaan" value="<?=$ls_jkk1_nama_lokasi_kecelakaan;?>" style="width:270px;" readonly class="disabled"> 
  			<input type="hidden" id="jkk1_kode_lokasi_kecelakaan" name="jkk1_kode_lokasi_kecelakaan" value="<?=$ls_jkk1_kode_lokasi_kecelakaan;?>">
      </div>
			<div class="clear"></div>		
		</span>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Tempat Kecelakaan</label>		 	    				
      <input type="text" id="jkk1_nama_tempat_kecelakaan" name="jkk1_nama_tempat_kecelakaan" value="<?=$ls_jkk1_nama_tempat_kecelakaan;?>" style="width:254px;" readonly class="disabled"> 
    </div>
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Keterangan</label>		 	    				
			<textarea cols="255" rows="1" id="jkk1_ket_tambahan" name="jkk1_ket_tambahan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:270px;background-color:#F5F5F5"><?=$ls_jkk1_ket_tambahan;?></textarea>    	
		</div>
		<div class="clear"></div>										    																						  
</fieldset>
	
<script language="javascript">
  function fl_js_jkk1_kode_jenis_kasus() 
  { 
  	var v_kode_jenis_kasus = window.document.getElementById('jkk1_kode_jenis_kasus').value;
  	
  	if (v_kode_jenis_kasus =="KS01") //kecelakaan kerja --------------------------
    {    
  		window.document.getElementById("jkk1_span_lokasi_kecelakaan").style.display = 'block';	
    }else if (v_kode_jenis_kasus =="KS02") //penyakit akibat kerja ---------------
    {
  	  window.document.getElementById("jkk1_span_lokasi_kecelakaan").style.display = 'none';
    } 	
  }
  
  //update 23/01/2019 tambahan terkait TKI ---------------------------------------
  function fl_js_jkk1_tipe_negara_kejadian() 
  { 
  	var v_kode_segmen = window.document.getElementById("jkk1_kode_segmen").value;
  	
  	if (v_kode_segmen =="TKI")
    {    
  		window.document.getElementById("jkk1_span_tipe_negara_kejadian").style.display = 'block';
    }else
    {
  	 	window.document.getElementById("jkk1_span_tipe_negara_kejadian").style.display = 'none';	 
    }
  }
  
  $(document).ready(function() {
  	fl_js_jkk1_kode_jenis_kasus();
  	fl_js_jkk1_tipe_negara_kejadian();													 		
  });
</script>
