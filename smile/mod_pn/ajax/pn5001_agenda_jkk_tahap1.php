<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Data Klaim JKK Tahap I
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ld_jkk1_tgl_kecelakaan					 = $ld_tgl_kecelakaan;
$ls_jkk1_kode_jam_kecelakaan		 = $ls_kode_jam_kecelakaan;
$ls_jkk1_kode_jenis_kasus				 = $ls_kode_jenis_kasus;
$ls_jkk1_kode_lokasi_kecelakaan  = $ls_kode_lokasi_kecelakaan;  										
$ls_jkk1_nama_tempat_kecelakaan	 = $ls_nama_tempat_kecelakaan;
$ls_jkk1_ket_tambahan						 = $ls_ket_tambahan;
?>
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
    window.document.getElementById("jkk1_kode_lokasi_kecelakaan").value = "";
  } 	
}	
</script>
						
<div id="formKiri" style="width:870px;">
  <fieldset><legend><b><i><font color="#009999">Informasi Kecelakaan pada Laporan JKK Tahap I</font></i></b></legend>
		</br>													
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Kecelakaan *</label>
      <input type="text" id="jkk1_tgl_kecelakaan" name="jkk1_tgl_kecelakaan" value="<?=$ld_jkk1_tgl_kecelakaan;?>" tabindex="31" style="width:270px;" maxlength="10" onblur="convert_date(jkk1_tgl_kecelakaan);" readonly class="disabled">      	   							
    </div>	
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jam Kecelakaan &nbsp; *</label>		 	    				
      <select size="1" id="jkk1_kode_jam_kecelakaan" name="jkk1_kode_jam_kecelakaan" value="<?=$ls_jkk1_kode_jam_kecelakaan;?>" tabindex="32" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:276px;background-color:#F5F5F5\"" : " style=\"width:276px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
  			if ($ls_status_submit_agenda =="Y")
  			{
					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and kode='$ls_jkk1_kode_jam_kecelakaan' order by seq";
        }else
  			{
          $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and nvl(aktif,'T')='Y' order by seq";										
  			}        				
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE"]==$ls_jkk1_kode_jam_kecelakaan && strlen($ls_jkk1_kode_jam_kecelakaan)==strlen($row["KODE"])){ echo " selected"; }
          echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
      ?>
      </select>		
    </div>																																												
  	<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jenis Kasus &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>		 	    				
      <select size="1" id="jkk1_kode_jenis_kasus" name="jkk1_kode_jenis_kasus" value="<?=$ls_jkk1_kode_jenis_kasus;?>" tabindex="33" class="select_format" onchange="fl_js_jkk1_kode_jenis_kasus(this.value);" <?=($ls_status_submit_agenda =="Y")? " style=\"width:276px;background-color:#F5F5F5\"" : " style=\"width:276px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <?
  			if ($ls_status_submit_agenda =="Y")
  			{
					$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus='$ls_jkk1_kode_jenis_kasus'";
        }else
  			{
				 	if ($ls_kode_segmen == "TKI")
					{
					 	 $sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_tipe_klaim = '$ls_kode_tipe_klaim' and nvl(status_nonaktif,'T')='T' and kode_jenis_kasus <> 'KS02' order by no_urut";	
					}else
					{	 
          	 $sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_tipe_klaim = '$ls_kode_tipe_klaim' and nvl(status_nonaktif,'T')='T' order by no_urut";									
  				}
				} 			         
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE_JENIS_KASUS"]==$ls_jkk1_kode_jenis_kasus && strlen($ls_jkk1_kode_jenis_kasus)==strlen($row["KODE_JENIS_KASUS"])){ echo " selected"; }
          echo " value=\"".$row["KODE_JENIS_KASUS"]."\">".$row["NAMA_JENIS_KASUS"]."</option>";
        }
      ?>
      </select>	
    </div>																																											
  	<div class="clear"></div>

		<span id="jkk1_span_lokasi_kecelakaan" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Lokasi Kecelakaan &nbsp; *</label>		 	    				
        <select size="1" id="jkk1_kode_lokasi_kecelakaan" name="jkk1_kode_lokasi_kecelakaan" value="<?=$ls_jkk1_kode_lokasi_kecelakaan;?>" tabindex="34" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:260px;background-color:#F5F5F5\"" : " style=\"width:260px;background-color:#ffff99\"";?>>
        <option value="">-- Pilih --</option>
        <? 
    			if ($ls_status_submit_agenda =="Y")
    			{
						$sql = "select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where kode_lokasi_kecelakaan='$ls_jkk1_kode_lokasi_kecelakaan'";
          }else
    			{
            $sql = "select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where nvl(status_nonaktif,'T')='T' order by no_urut";									
    			} 				          
          $DB->parse($sql);
          $DB->execute();
          while($row = $DB->nextrow())
          {
            echo "<option ";
            if ($row["KODE_LOKASI_KECELAKAAN"]==$ls_jkk1_kode_lokasi_kecelakaan && strlen($ls_jkk1_kode_lokasi_kecelakaan)==strlen($row["KODE_LOKASI_KECELAKAAN"])){ echo " selected"; }
            echo " value=\"".$row["KODE_LOKASI_KECELAKAAN"]."\">".$row["NAMA_LOKASI_KECELAKAAN"]."</option>";
          }
        ?>
        </select>	
      </div>
			<div class="clear"></div>		
		</span>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Tempat Kecelakaan</label>		 	    				
      <input type="text" id="jkk1_nama_tempat_kecelakaan" name="jkk1_nama_tempat_kecelakaan" value="<?=$ls_jkk1_nama_tempat_kecelakaan;?>" tabindex="35" style="width:254px;"maxlength="300" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " ";?>>
    </div>
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Keterangan</label>		 	    				
			<textarea cols="255" rows="1" id="jkk1_ket_tambahan" name="jkk1_ket_tambahan" tabindex="36" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;\"";?>><?=$ls_jkk1_ket_tambahan;?></textarea>    	
		</div>
		<div class="clear"></div>
		
    <?
    echo "<script type=\"text/javascript\">fl_js_jkk1_kode_jenis_kasus();</script>";
    ?>											    																						  
  </fieldset>
	
</div>
