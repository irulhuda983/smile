<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Kerugian atas tindakan pihak lain/kehilangan 
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 22/01/2018 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_kehilangan = $ld_tgl_kejadian;
$ls_jkk_ket_tambahan	 = $ls_ket_tambahan;	
$ls_jkk_tipe_negara_kejadian = $ls_tipe_negara_kejadian;			
?>

<div id="formKiri" style="width:870px;">
  <fieldset><legend>Informasi Kehilangan</legend>
		</br>												
  	
    <div class="form-row_kiri">
    <label style = "text-align:right;">Negara Kejadian *</label>		 	    				
      <select size="1" id="jkk_tipe_negara_kejadian" name="jkk_tipe_negara_kejadian" value="<?=$ls_jkk_tipe_negara_kejadian;?>" tabindex="35" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
        $param_bv = [];
  			if ($ls_status_submit_agenda =="Y")
  			{
					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and kode=:p_kode order by seq";
          $param_bv[':p_kode'] = $ls_jkk_tipe_negara_kejadian;
        }else
  			{
          $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and nvl(aktif,'T')='Y' order by seq";										
  			}        				
        $proc = $DB->parse($sql);
        foreach ($param_bv as $key => $value) {
          oci_bind_by_name($proc, $key, $param_bv[$key]);
        }
        // 20-03-2024 penyesuaian bind variable
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE"]==$ls_jkk_tipe_negara_kejadian && strlen($ls_jkk_tipe_negara_kejadian)==strlen($row["KODE"])){ echo " selected"; }
          echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
      ?>
      </select>		
    </div>																																												
  	<div class="clear"></div>
	
    <div class="form-row_kiri">
    <label style = "text-align:right;">Lokasi Kehilangan *</label>		 	    				
			<textarea cols="255" rows="1" id="jkk_ket_tambahan" name="jkk_ket_tambahan" tabindex="36" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;;background-color:#ffff99;\"";?>><?=$ls_jkk_ket_tambahan;?></textarea>    	
		</div>
		<div class="clear"></div>		

    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Kehilangan</label>
      <input type="text" id="jkk_tgl_kehilangan" name="jkk_tgl_kehilangan" value="<?=$ld_jkk_tgl_kehilangan;?>" size="37" maxlength="10" onblur="convert_date(jkk_tgl_kehilangan);" readonly class="disabled">      	   							
    </div>	
		<div class="clear"></div>
				
		</br></br></br></br></br></br>																								  
  </fieldset>						
	</br></br>	
</div>
