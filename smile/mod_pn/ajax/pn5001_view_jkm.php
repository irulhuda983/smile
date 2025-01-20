    <?
    $ld_jkm_tgl_kematian  = $ld_tgl_kematian;
    $ls_jkm_ket_tambahan	= $ls_ket_tambahan;
    ?>
    <div id="formKiri" style="width:960px;">
      <fieldset><legend>Data Klaim JKM</legend>
    		</br>
    																	
        <div class="form-row_kiri">
        <label style = "text-align:right;">Tgl Kematian &nbsp;</label>
          <input type="text" id="jkm_tgl_kematian" name="jkm_tgl_kematian" value="<?=$ld_jkm_tgl_kematian;?>" tabindex="32" size="31" maxlength="10" onblur="convert_date(jkm_tgl_kematian);" readonly class="disabled">      	
    		</div>    		
    		<div class="clear"></div>
    
        <div class="form-row_kiri">
        <label style = "text-align:right;">Keterangan &nbsp;</label>
    			<textarea cols="255" rows="1" id="jkm_ket_tambahan" name="jkm_ket_tambahan" tabindex="33" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:250px;background-color:#F5F5F5;\"" : " style=\"width:250px;background-color:#ffff99\"";?>><?=$ls_jkm_ket_tambahan;?></textarea>	   							
        </div>
    		<div class="clear"></div>
    				
    		</br> 	    																						  
      </fieldset>
    							
    </div>	