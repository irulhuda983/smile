<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Data Klaim JKK Tahap I
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ls_jkk1_tipe_negara_kejadian		 = $ls_tipe_negara_kejadian;																							 
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
		
		//update 28/12/2019 - PP82 utk PAK -----------------------------------------
		window.document.getElementById('span_label_jkk1_tgl_kecelakaan').innerHTML = 'Tgl Kecelakaan *';
		window.document.getElementById("btn_jkk1_tgl_kecelakaan").style.display = 'none';
		window.document.getElementById('jkk1_tgl_kecelakaan').readOnly = true;
    window.document.getElementById('jkk1_tgl_kecelakaan').style.backgroundColor='#F5F5F5';
		//end update 28/12/2019 - PP82 utk PAK -------------------------------------
  }else if (v_kode_jenis_kasus =="KS02") //penyakit akibat kerja ---------------
  {
	  window.document.getElementById("jkk1_span_lokasi_kecelakaan").style.display = 'none';
    window.document.getElementById("jkk1_kode_lokasi_kecelakaan").value = "";
		//update 28/12/2019 - PP82 utk PAK -----------------------------------------
		window.document.getElementById('span_label_jkk1_tgl_kecelakaan').innerHTML = 'Tgl Diagnosis PAK *';
		window.document.getElementById("btn_jkk1_tgl_kecelakaan").style.display = '';
		window.document.getElementById('jkk1_tgl_kecelakaan').readOnly = false;
    window.document.getElementById('jkk1_tgl_kecelakaan').style.backgroundColor='#ffff99';
		//end update 28/12/2019 - PP82 utk PAK -------------------------------------
  } 	
}

//update 23/01/2019 tambahan terkait TKI ---------------------------------------
function fl_js_jkk1_tipe_negara_kejadian() 
{ 
	var v_kode_segmen = window.document.getElementById("kode_segmen").value;
	
	if (v_kode_segmen =="TKI")
  {    
		window.document.getElementById("jkk1_span_tipe_negara_kejadian").style.display = 'block';
  }else
  {
	 	window.document.getElementById("jkk1_span_tipe_negara_kejadian").style.display = 'none';	 
	  window.document.getElementById("jkk1_tipe_negara_kejadian").value = "I";
  }
}	
</script>
						
<div id="formKiri" style="width:870px;">
  <fieldset><legend><b><i><font color="#009999">Informasi Kecelakaan pada Laporan JKK Tahap I</font></i></b></legend>
		</br>
		<span id="jkk1_span_tipe_negara_kejadian" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Negara Kejadian *</label>		 	    				
        <select size="1" id="jkk1_tipe_negara_kejadian" name="jkk1_tipe_negara_kejadian" value="<?=$ls_jkk1_tipe_negara_kejadian;?>" tabindex="30" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
        <option value="">-- Pilih --</option>
        <? 
          $param_bv = [];
    			if ($ls_status_submit_agenda =="Y")
    			{
  					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and kode=:p_kode order by seq";
            $param_bv[':p_kode'] = $ls_jkk1_tipe_negara_kejadian;
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
            if ($row["KODE"]==$ls_jkk1_tipe_negara_kejadian && strlen($ls_jkk1_tipe_negara_kejadian)==strlen($row["KODE"])){ echo " selected"; }
            echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
          }
        ?>
        </select>		
      </div>																																												
    	<div class="clear"></div>		
		</span>
																	
    <div class="form-row_kiri">
    <label style = "text-align:right;"><span id="span_label_jkk1_tgl_kecelakaan">Tgl Kecelakaan *</span></label>
      <input type="text" id="jkk1_tgl_kecelakaan" name="jkk1_tgl_kecelakaan" value="<?=$ld_jkk1_tgl_kecelakaan;?>" tabindex="31" style="width:252px;" maxlength="10" onblur="convert_date(jkk1_tgl_kecelakaan);" readonly class="disabled">      	   							
    	<input style="display:none;" id="btn_jkk1_tgl_kecelakaan" type="image" align="top" onclick="return showCalendar('jkk1_tgl_kecelakaan', 'dd-mm-y');" src="../../images/calendar.gif" />
		</div>	
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jam Kecelakaan &nbsp; *</label>		 	    				
      <select size="1" id="jkk1_kode_jam_kecelakaan" name="jkk1_kode_jam_kecelakaan" value="<?=$ls_jkk1_kode_jam_kecelakaan;?>" tabindex="32" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:276px;background-color:#F5F5F5\"" : " style=\"width:276px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
        $param_bv = [];
  			if ($ls_status_submit_agenda =="Y")
  			{
					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and kode=:p_kode order by seq";
          $param_bv[':p_kode'] = $ls_jkk1_kode_jam_kecelakaan;
        }else
  			{
          $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and nvl(aktif,'T')='Y' order by seq";										
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
        $param_bv=[];
  			if ($ls_status_submit_agenda =="Y")
  			{
					$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus=:p_kode";
          $param_bv[':p_kode'] = $ls_jkk1_kode_jenis_kasus;
        }else
  			{
				 	if ($ls_kode_segmen == "TKI")
					{
					 	 $sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_tipe_klaim = :p_kode_tipe_klaim and nvl(status_nonaktif,'T')='T' and kode_jenis_kasus <> 'KS02' order by no_urut";	
             $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
					}else
					{	 
          	 //update 28/12/2019 - PP82 utk PAK --------------------------------
						 if ($ls_kode_sebab_klaim == "SKK01") //kecelakaan kerja
						 {
						 		$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus ".
										 	 "where kode_tipe_klaim = :p_kode_tipe_klaim ".
											 "and kode_jenis_kasus = 'KS01' ".
											 "and nvl(status_nonaktif,'T')='T' ".
											 "order by no_urut";
                $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
						 }elseif ($ls_kode_sebab_klaim == "SKK02") //penyakit akibat kerja
						 {
						 		$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus ".
										 	 "where kode_tipe_klaim = :p_kode_tipe_klaim ".
											 "and kode_jenis_kasus = 'KS02' ".
											 "and nvl(status_nonaktif,'T')='T' ".
											 "order by no_urut";
                $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
						 }else
						 {
						 		$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus ".
										 	 "where kode_tipe_klaim = :p_kode_tipe_klaim ".
											 "and nvl(status_nonaktif,'T')='T' ".
											 "order by no_urut";
                $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
  					 }
						 //end update 28/12/2019 - PP82 utk PAK ----------------------------
					}
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
          $param_bv = [];
    			if ($ls_status_submit_agenda =="Y")
    			{
						$sql = "select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where kode_lokasi_kecelakaan=:p_kode_lokasi_kecelakaan";
            $param_bv[':p_kode_lokasi_kecelakaan'] = $ls_jkk1_kode_lokasi_kecelakaan;
          }else
    			{
            $sql = "select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where nvl(status_nonaktif,'T')='T' order by no_urut";									
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
		echo "<script type=\"text/javascript\">fl_js_jkk1_tipe_negara_kejadian();</script>";
    echo "<script type=\"text/javascript\">fl_js_jkk1_kode_jenis_kasus();</script>";
    ?>										    																						  
  </fieldset>
	
	</br></br></br>
	
</div>
