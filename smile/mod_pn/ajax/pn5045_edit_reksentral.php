<tr>
  <td style="text-align:center;">
    <fieldset style="height:55px;"><legend><i><font color="#009999">Dibayar Melalui :</font></i></legend>
      <div class="form-row_kiri">
      <label style = "text-align:right;">Bank *</label>	 	    				
        <select size="1" id="kode_bank_pembayar" name="kode_bank_pembayar" value="<?=$ls_kode_bank_pembayar;?>" onchange="fl_js_val_kode_bank_pembayar();" tabindex="24" class="select_format" style="width:220px;background-color:#ffff99;" >
        <option value="">-- Pilih --</option>
        <? 
        if ($ls_status_rekening_sentral=="Y")
        {
        $sql = "select distinct a.kode_bank, b.nama_bank from ms.ms_rekening_detil a, ms.ms_bank b ".
        "where a.kode_bank = b.kode_bank ".
        "and a.tipe_rekening='36' ".
        "and a.kode_bank = '020' ". //sementara batasi bank BNI, nanti diaktifkan lg bank yg lain
        "and a.kode_kantor = 'ATP' ".
        "order by a.kode_bank";
        }else
        {
        if ($ls_kode_pointer_asal=="SPO")
        {
        $sql = "select a.kode_bank,b.nama_bank from sijstk.monitor_klaim_jspo a, ms.ms_bank b ". 
        "where a.kode_bank = b.kode_bank ".
        "and a.no_agenda = '$ls_kode_klaim' ";													
        }else
        {
        $sql = "select distinct a.kode_bank, c.nama_bank ".
        "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
        "where a.kode_kantor = b.kode_kantor(+) ".  
        "and a.kode_bank = b.kode_bank(+) ".  
        "and a.kode_rekening = b.kode_rekening(+) ".  
        "and a.kode_buku = b.kode_buku(+) ".  
        "and a.kode_bank = c.kode_bank ".   
        "and a.kode_kantor = '$ls_kantor_pembayar' ". 
        "and nvl(a.aktif,'T')='Y' ".
        "and b.tipe_rekening in ('13','14','15','16') ". 
        "order by a.kode_bank";
        }			 													
        }			 
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if ($row["KODE_BANK"]==$ls_kode_bank_pembayar && strlen($ls_kode_bank_pembayar)==strlen($row["KODE_BANK"])){ echo " selected"; }
        echo " value=\"".$row["KODE_BANK"]."\">".$row["NAMA_BANK"]."</option>";
        }
        ?>
        </select>
        <input type="hidden" id="id_bank_opg" name="id_bank_opg" value="<?=$ls_id_bank_opg;?>">
        <input type="hidden" id="nama_bank_pembayar" name="nama_bank_pembayar" value="<?=$ls_nama_bank_pembayar;?>">
      </div>																																																																																																									
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">&nbsp;</label>	 	    				
        <input type="hidden" id="status_rekening_sentral" name="status_rekening_sentral" value="<?=$ls_status_rekening_sentral;?>">
        <? $ls_status_rekening_sentral = isset($ls_status_rekening_sentral) ? $ls_status_rekening_sentral : "T"; ?>					
        <input type="checkbox" id="cb_status_rekening_sentral" name="cb_status_rekening_sentral" disabled class="cebox" <?=$ls_status_rekening_sentral=="Y" ||$ls_status_rekening_sentral=="ON" ||$ls_status_rekening_sentral=="on" ? "checked" : "";?>><i><font color="#009999">Sentralisasi Rekening</font></i>
        <input type="hidden" id="kantor_rekening_sentral" name="kantor_rekening_sentral" value="<?=$ls_kantor_rekening_sentral;?>">
      </div>																																																																																																									
      <div class="clear"></div>	
    </fieldset>														
  </td>	 												
</tr>

<tr>
  <td style="text-align:center;">
    <fieldset style="height:95px;"><legend><i><font color="#009999">Ditransfer ke :</font></i>	 	    				
      <? 
      switch($ls_rg_metode_transfer)
      {
      case 'ATR' : $selATR="checked"; break;
      case 'ATB' : $selATB="checked"; break;
      }
      ?>
      <input type="radio" name="rg_metode_transfer" onchange="fl_js_val_rg_metode_transfer();" value="ATR" <?=$selATR;?>><font color="#009999"><b>Antar Rekening (dalam satu bank)</b></font>&nbsp; | &nbsp;
      <input type="radio" name="rg_metode_transfer" onchange="fl_js_val_rg_metode_transfer();" value="ATB" <?=$selATB;?>><font color="#009999"><b>Antar Bank</b></font>
      <input type="hidden" id="metode_transfer" name="metode_transfer" value="<?=$ls_metode_transfer;?>">
    </legend>									          							
    
      <div class="form-row_kiri">
      <label style = "text-align:right;">Bank *</label> 
        <input type="text" id="nama_bank_penerima" name="nama_bank_penerima" value="<?=$ls_nama_bank_penerima;?>" readonly style="width:293px;background-color:#ffff99;">
        <input type="hidden" id="kode_bank_penerima" name="kode_bank_penerima" value="<?=$ls_kode_bank_penerima;?>"style="width:100px;">
        <input type="hidden" id="id_bank_penerima" name="id_bank_penerima" value="<?=$ls_id_bank_penerima;?>"style="width:100px;">
        <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lov_bankpenerima.php?p=pn5045_edit.php&a=formreg&b=nama_bank_penerima&c=kode_bank_penerima&d=id_bank_penerima&e=metode_transfer','',800,500,1)">							
        <img src="../../images/help.png" alt="Cari Bank" border="0" align="absmiddle"></a>				           											
      </div>																																																	
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">No Rekening *</label>
        <input type="text" id="no_rekening_penerima" name="no_rekening_penerima" onblur="cekValidasiRekening();" value="<?=$ls_no_rekening_penerima;?>" tabindex="22" maxlength="30" style="width:100px;background-color:#ffff99;">
        <input type="text" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();">
        <input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" class="cebox" onclick="copyNamaRekeningPenerima()" <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>><i><font color="#009999">Valid</font></i>	
      </div>																																																																																															
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">A/N *</label>
      	<input type="text" id="nama_rekening_penerima" name="nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" tabindex="23" maxlength="100" readonly class="disabled" style="width:270px;">
      </div>																																																
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">&nbsp;</label>	 	    				
        <i><font color="#009999">Note : Tickmark Valid jika NO REKENING sudah sesuai. </font></i>
        <input type="hidden" id="status_valid_rekening_penerima" name="status_valid_rekening_penerima" value="<?=$ls_status_valid_rekening_penerima;?>">
        <? $ls_status_valid_rekening_penerima = isset($ls_status_valid_rekening_penerima) ? $ls_status_valid_rekening_penerima : "T"; ?>					
      </div>																																																																																																								
      <div class="clear"></div>												  																											
    </fieldset>														
  </td>	 												
</tr>	
