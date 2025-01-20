<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Gagal Berangkat
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 22/01/2018 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_gagal_berangkat			 = $ld_tgl_kejadian;
$ls_jkk_ket_tambahan						 = $ls_ket_tambahan;				
?>

<div id="formKiri" style="width:870px;">
  <fieldset><legend>Informasi Gagal Berangkat</legend>
		</br>												
    <div class="form-row_kiri">
    <label style = "text-align:right;">Penyebab Gagal *</label>		 	    				
      <select size="1" id="jkk_ket_tambahan" name="jkk_ket_tambahan" value="<?=$ls_jkk_ket_tambahan;?>" tabindex="32" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
        $param_bv=[];
  			if ($ls_status_submit_agenda =="Y")
  			{
					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKIGGLBRKT' and kode= :P_JKK_KET_TAMBAHAN order by seq";
          $param_bv[':P_JKK_KET_TAMBAHAN']=$ls_jkk_ket_tambahan;
        }else
  			{
          $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKIGGLBRKT' and nvl(aktif,'T')='Y' order by seq";										
  			}        				
        $proc = $DB->parse($sql);
        foreach($param_bv as $key => $val) {
          oci_bind_by_name($proc, $key, $param_bv[$key]);
        }
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE"]==$ls_jkk_ket_tambahan && strlen($ls_jkk_ket_tambahan)==strlen($row["KODE"])){ echo " selected"; }
          echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
      ?>
      </select>		
    </div>																																												
  	<div class="clear"></div>		

    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Gagal Berangkat</label>
      <input type="text" id="jkk_tgl_gagal_berangkat" name="jkk_tgl_gagal_berangkat" value="<?=$ld_jkk_tgl_gagal_berangkat;?>" size="37" maxlength="10" onblur="convert_date(jkk_tgl_gagal_berangkat);" readonly class="disabled">      	   							
    </div>	
		<div class="clear"></div>
				
		</br></br></br></br></br></br>																								  
  </fieldset>						
	</br></br>	
</div>
