<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Data TK Jakon
Hist: - 18/03/2018 : Pembuatan Form (Tim SIJSTK)			 						 
-----------------------------------------------------------------------------*/
$sql = "select 
            a.kode_klaim, a.kode_segmen, a.kode_perusahaan, a.kode_divisi, a.kode_proyek, 
            a.kode_tk, a.nama_tk, a.kpj, a.nomor_identitas, a.jenis_identitas, a.alamat_domisili, a.kode_pekerjaan, 
            (select nama_pekerjaan from sijstk.jn_pekerjaan_proyek where kode_pekerjaan = a.kode_pekerjaan) nama_pekerjaan,
						to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir
        from sijstk.pn_klaim a
        where kode_klaim = '$ls_kode_klaim'";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_tkjakon_kode_klaim	 	 		= $row["KODE_KLAIM"];
$ls_tkjakon_kode_proyek 	 	  = $row["KODE_PROYEK"];
$ls_tkjakon_kode_tk 					= $row["KODE_TK"];
$ls_tkjakon_nama_tk 	 				= $row["NAMA_TK"];
$ls_tkjakon_kpj 							= $row["KPJ"];
$ls_tkjakon_nomor_identitas		= $row["NOMOR_IDENTITAS"];
$ls_tkjakon_jenis_identitas		= $row["JENIS_IDENTITAS"];	
$ls_tkjakon_alamat_domisili		= $row["ALAMAT_DOMISILI"];	
$ld_tkjakon_tgl_lahir					= $row["TGL_LAHIR"]; 	 
$ls_tkjakon_kode_pekerjaan		= $row["KODE_PEKERJAAN"];	
$ls_tkjakon_nama_pekerjaan		= $row["NAMA_PEKERJAAN"];	

?>
<script language="JavaScript">    
  function fl_js_val_numeric_tkjakon(v_field_id)
  {
    var c_val = window.document.getElementById(v_field_id).value;
    var number=/^[0-9]+$/;
    
    if ((c_val!='') && (!c_val.match(number)))
    {
      document.getElementById(v_field_id).value = '';				 
      window.document.getElementById(v_field_id).focus();
      alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");         
      return false; 				 
    }		
  }
	
  function fl_js_val_nomor_identitas()
  {
    var v_jenis_identitas = window.document.getElementById('tkjakon_jenis_identitas').value;
    var v_nomor_identitas = window.document.getElementById('tkjakon_nomor_identitas').value;
    var number=/^[0-9]+$/;
    var v_error = "0";
	
    if (v_jenis_identitas=='KTP')
    {
      if (v_nomor_identitas!='')
      {
        if (v_nomor_identitas.length!=16)
        { 
          v_error = "1";
          document.getElementById('tkjakon_nomor_identitas').value = '';				 
          window.document.getElementById('tkjakon_nomor_identitas').focus();
          curr_nomor_identitas = '';
          alert("Untuk KTP, Nomor Identitas harus 16 karakter...!!!");         
          return false;
        }else
        {
          if (!v_nomor_identitas.match(number))
          { 
            v_error = "1";
            document.getElementById('tkjakon_nomor_identitas').value = '';				 
            window.document.getElementById('tkjakon_nomor_identitas').focus();
            curr_nomor_identitas = '';
            alert("Untuk KTP, Nomor Identitas tidak boleh berisikan selain angka...!!!");         
            return false;
          }					 
        }
      }
    }
  }				
</script>	

<div id="formKiri" style="width:955px;">
				<fieldset><legend><b><i><font color="#009999">Tenaga Kerja Jasa Konstruksi :</font></i></b></legend>
					</br>
					
      		<div class="form-row_kiri">
          <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
      			<input type="text" id="tkjakon_nama_tk" name="tkjakon_nama_tk" value="<?=$ls_tkjakon_nama_tk;?>" tabindex="1" maxlength="100" <?=($ls_status_submit_agenda =="Y")? " style=\"width:270px;\" readonly class=disabled" : " style=\"width:270px;background-color:#ffff99\"";?>>
						<input type="hidden" id="tkjakon_kode_tk" name="tkjakon_kode_tk" value="<?=$ls_tkjakon_kode_tk;?>">
          </div>	
					<div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Identitas &nbsp;</label>
						<?
						if ($ls_status_submit_agenda =="T" && $ls_tkjakon_jenis_identitas=="")
						{
						 	$ls_tkjakon_jenis_identitas = "KTP"; 
						}
						?>		 
            <select size="1" id="tkjakon_jenis_identitas" name="tkjakon_jenis_identitas" value="<?=$ls_tkjakon_jenis_identitas;?>" onchange="fl_js_val_nomor_identitas();" tabindex="2" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:53px;background-color:#F5F5F5\"" : " style=\"width:53px;background-color:#ffff99\"";?>>
              <option value="">---</option>
              <? 
        			if ($ls_status_submit_agenda=="Y")
        			{
                $sql = "select kode from sijstk.ms_lookup where tipe='JID' and kode='$ls_tkjakon_jenis_identitas' and nvl(aktif,'T') = 'Y' order by seq";			
        			}else
        			{
                $sql = "select kode from sijstk.ms_lookup where tipe='JID' and nvl(aktif,'T') = 'Y' order by seq";	
        			}
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
              echo "<option ";
              if ($row["KODE"]==$ls_tkjakon_jenis_identitas && strlen($ls_tkjakon_jenis_identitas)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
              }
              ?>
            </select>					
            <input type="text" id="tkjakon_nomor_identitas" name="tkjakon_nomor_identitas" value="<?=$ls_tkjakon_nomor_identitas;?>" onblur="fl_js_val_nomor_identitas();" maxlength="30" tabindex="3" <?=($ls_status_submit_agenda =="Y")? " style=\"width:214px;\" readonly class=disabled" : " style=\"width:214px;background-color:#ffff99\"";?>>
      		</div>																																									
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Lahir</label>	 
            <input type="text" id="tkjakon_tgl_lahir" name="tkjakon_tgl_lahir" value="<?=$ld_tkjakon_tgl_lahir;?>" maxlength="10" tabindex="3" onblur="convert_date(tkjakon_tgl_lahir);" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:250px;\"" : " style=\"width:250px;\"";?>>
            <?
            if ($ls_status_submit_agenda!="Y")
            {
              ?>      								
              <input id="btn_tkjakon_tgl_lahir" type="image" align="top" onclick="return showCalendar('tkjakon_tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />							
              <?
            }
            ?>                      												   							
          </div>																																																								
          <div class="clear"></div>
								
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alamat Domisili &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <textarea cols="255" rows="1" id="tkjakon_alamat_domisili" name="tkjakon_alamat_domisili" tabindex="4" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;\"";?>><?=$ls_tkjakon_alamat_domisili;?></textarea> 
					</div>																																										
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jenis Pekerjaan &nbsp;</label>
      			<select size="1" id="tkjakon_kode_pekerjaan" name="tkjakon_kode_pekerjaan" value="<?=$ls_tkjakon_kode_pekerjaan;?>" tabindex="5" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;\"";?>>
            <option value="">- Pilih --</option>
            <?
      			if ($ls_status_submit_agenda=="Y")
      			{
              $sql = "select a.kode_pekerjaan, a.nama_pekerjaan from sijstk.jn_pekerjaan_proyek a ".
                     "where a.kode_pekerjaan = '$ls_tkjakon_kode_pekerjaan' ".
                     "order by a.kode_pekerjaan";			
      			}else
      			{
              $sql = "select a.kode_pekerjaan, a.nama_pekerjaan from sijstk.jn_pekerjaan_proyek a ".
      							 "order by a.kode_pekerjaan";	
      			}			 
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if (($row["KODE_PEKERJAAN"]==$ls_tkjakon_kode_pekerjaan && strlen($row["KODE_PEKERJAAN"])==strlen($ls_tkjakon_kode_pekerjaan))) { echo " selected"; }
            echo " value=\"".$row["KODE_PEKERJAAN"]."\">".$row["NAMA_PEKERJAAN"]."</option>";
            }
            ?>
            </select>
      		</div>		    																																				
        	<div class="clear"></div>
					
					</br>
					</br>
					</br>			 
				</fieldset>					
</div>
