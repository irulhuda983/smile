<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Pemulangan TKI Bermasalah
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 22/01/2018 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_pemulangan = $ld_tgl_kejadian;
$ls_jkk_ket_tambahan	 = $ls_ket_tambahan;				
?>

<div id="formKiri" style="width:870px;">
  <fieldset><legend>Informasi Pemulangan PMI Bermasalah</legend>
		</br>												
    <div class="form-row_kiri">
    <label style = "text-align:right;">Sebab Pemulangan *</label>		 	    				
			<textarea cols="255" rows="1" id="jkk_ket_tambahan" name="jkk_ket_tambahan" tabindex="36" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;;background-color:#ffff99;\"";?>><?=$ls_jkk_ket_tambahan;?></textarea>    	
		</div>
		<div class="clear"></div>		

    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Pemulangan</label>
      <input type="text" id="jkk_tgl_pemulangan" name="jkk_tgl_pemulangan" value="<?=$ld_jkk_tgl_pemulangan;?>" size="37" maxlength="10" onblur="convert_date(jkk_tgl_pemulangan);" readonly class="disabled">      	   							
    </div>	
		<div class="clear"></div>
				
		</br></br></br></br></br></br>																								  
  </fieldset>						
	</br></br>	
</div>
