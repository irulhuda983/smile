<?php 
  if(!empty($_GET['dataid'])){
    $dataid = $_GET['dataid'];
    //var_dump($dataid);
    $sql = "select * from PN.PN_AGENDA_PERNYATAAN A left join PN.PN_AGENDA_PERNYATAAN_KEPS b on A.kode_agenda_pernyataan = b.kode_agenda_pernyataan where A.kode_agenda_pernyataan = '$dataid' ";
    $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow()){
        $ls_kode_kantor = $row["KODE_KANTOR"];
        $ld_tgl_lapor = $row["TGL_LAPOR"];
        $ls_kode_tk = $row["KODE_TK"];
        $ls_kpj =  $row["KPJ"];
        $ls_nama_tk =  $row["NAMA_TK"];
        $ls_nomor_identitas = $row["NOMOR_IDENTITAS"];
        $ls_npp = $row["KODE_PERUSAHAAN"];
        $ls_nama_perusahaan =$row["NAMA_PERUSAHAAN"];
        $ls_kode_divisi =$row["KODE_DIVISI"];
        $ls_nama_divisi =$row["NAMA_DIVISI"]; 
        $ls_tempat_lahir = $row["TEMPAT_LAHIR"];
        $ls_tgl_lahir = $row["TGL_LAHIR"];
        $ls_alamat = $row["ALAMAT_TK"];
        $ls_nomor_telepon= $row["NO_TELEPON_TK"];
        $ls_keterangan = $row["KETERANGAN_PERNYATAAN"];
        $ls_kode_cetak_pernyataan = $row["KODE_AGENDA_PERNYATAAN"];
        $ld_tgl_klaim = $row["TGL_CETAK_PERNYATAAN"];
        $ls_status_submit = $row["STATUS_SUBMIT_PERNYATAAN"];
        $ls_petugas_cetak = $row["PETUGAS_CETAK_PERNYATAAN"];
      }

  }
?>
<div id="formKiri" >
	<fieldset><legend><b><i><font color="#009999">Entry Agenda Cetak Pernyataan</font></i></b> <b><?=$ls_status_batal=="Y" ? "<font color=#ff0000> (* Data Klaim Sudah Dibatalkan *)</font>" : "";?> </b></legend>
    <div class="form-row_kiri">
    <label style = "text-align:right;">Kantor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
      <select id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" tabindex="1" onchange="fl_js_input_kantor();" class="select_format" <?=($ls_status_submit =="Y")? " readonly class=disabled style=\"width:290px;background-color:#F5F5F5\"" : " style=\"width:290px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
      if ($ls_status_kelayakan=="Y")
      {
       	$sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
      }else
      {
        $sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".
               "where kode_tipe not in ('0','1') ".							     									 	 
               "start with kode_kantor = '$gs_kantor_aktif' ".
               "connect by prior kode_kantor = kode_kantor_induk";											
      }
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
        echo "<option ";
        if ($row["KODE_KANTOR"]==$ls_kode_kantor && strlen($ls_kode_kantor)==strlen($row["KODE_KANTOR"])){ echo " selected"; }
        echo " value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
      }
      ?>
      </select>																				 								
    </div>				
    <div class="form-row_kanan">
    <label style = "text-align:right;">Kode Cetak Pernyataann</label>
    <input type="hidden" id="status_submit" name="status_submit" value="<?=$ls_status_submit;?>">					
      <input type="text" id="kode_cetak_pernyataan" name="kode_cetak_pernyataan" value="<?=$ls_kode_cetak_pernyataan;?>" style="width:150px;" maxlength="20" readonly class="disabled">																																	 								
    </div>				
    <div class="clear"></div>	
		
    <div class="form-row_kiri">
    <label style = "text-align:right;">Segmentasi Keps&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
	  <?php 
		$ls_kode_segmen = "PU";
	  ?>
      <select id="kode_segmen_list" name="kode_segmen_list" value="<?=$ls_kode_segmen;?>" tabindex="2" class="select_format" onchange="fl_js_input_kode_segmen();" <?=($ls_status_submit =="Y")? " readonly class=disabled style=\"width:290px;background-color:#F5F5F5\"" : " style=\"width:290px;background-color:#ffff99\"";?>  >
      <option value="">-- Pilih --</option>
      <?
      if ($ls_kode_klaim!="")
      {
       	$sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen where kode_segmen = '$ls_kode_segmen'";
      }else
      {
       	$sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen where kode_segmen = '$ls_kode_segmen' order by no_urut";											
      }											                       
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
        echo "<option ";
        if ($row["KODE_SEGMEN"]==$ls_kode_segmen && strlen($ls_kode_segmen)==strlen($row["KODE_SEGMEN"])){ echo " selected"; }
        echo " value=\"".$row["KODE_SEGMEN"]."\">".$row["NAMA_SEGMEN"]."</option>";
      }
      ?>
      </select>
			<input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>">																				 								
    </div>
    <div class="form-row_kanan">
    <label  style = "text-align:right;">Tgl Cetak Pernyataan</label>			
    	<input type="text" id="tgl_klaim" name="tgl_klaim" value="<?=$ld_tgl_klaim;?>" style="width:150px;" maxlength="10" readonly class="disabled">
    </div>						
    <div class="clear"></div>	
    <div class="form-row_kanan">
    <label  style = "text-align:right;">Petugas Cetak</label>			
    	<input type="text" id="petugas_cetak" name="petugas_cetak" value="<?=$ls_petugas_cetak;?>" style="width:150px;" maxlength="10" readonly class="disabled">
    </div>						
   
    <!-- <div class="form-row_kiri" >
    <label  style = "text-align:right;">Tipe Kelayakan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
      <input type="text" id="kode_tipe_klaim" name="kode_tipe_klaim" value="<?=$ls_kode_tipe_klaim;?>" tabindex="3" maxlength="30" onblur="f_ajax_val_kode_tipe_klaim();" <?=($ls_status_kelayakan =="Y" || $ls_status_kelayakan =="Y")? " readonly class=disabled style=\"width:60px;\"" : " style=\"background-color:#ffff99;width:60px;\"";?>>
      <input type="text" id="nama_tipe_klaim" name="nama_tipe_klaim" value="<?=$ls_nama_tipe_klaim;?>" style="width:205px;" readonly class="disabled">
      <?
      if ($ls_status_kelayakan!="Y" && $ls_status_kelayakan !="Y")
      {
        ?>      								
        <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_tipeklaim.php?p=pn5037.php&a=formreg&b=kode_tipe_klaim&c=nama_tipe_klaim&d='+formreg.kode_segmen.value+'','',800,500,1)">							
        <?
      }
      ?>                    										
      <img src="../../images/help.png" alt="Cari Tipe Klaim" border="0" align="absmiddle"></a>											
      <input type="hidden" id="jenis_klaim" name="jenis_klaim" value="<?=$ls_jenis_klaim;?>" maxlength="30">	
    </div>			 -->
	

    
    
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Lapor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
      <input type="text" id="tgl_lapor" name="tgl_lapor" value="<?=$ld_tgl_lapor;?>" maxlength="10" tabindex="5" onblur="convert_date(tgl_lapor);f_ajax_val_tgl_lapor();" <?=($ls_status_submit =="Y")? " readonly class=disabled style=\"width:290px;background-color:#F5F5F5\"" : " style=\"width:220px;background-color:#ffff99\"";?>>
      <?
      if ($ls_status_kelayakan!="Y" && $ls_status_kelayakan !="Y")
      {
      ?>      								
      <input id="btn_tgl_lapor" type="image" align="top" onclick="return showCalendar('tgl_lapor', 'dd-mm-y');" src="../../images/calendar.gif" />							
      <?
      }
      ?>                    											   							
    </div>	
    <div class="clear"></div>																																																																											

    <span id="span_tgl_kejadian" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Tgl Kejadian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>	 
        <input type="text" id="tgl_kejadian" name="tgl_kejadian" value="<?=$ld_tgl_kejadian;?>" maxlength="10" tabindex="6" onblur="convert_date(tgl_kejadian);f_ajax_val_tgl_kejadian();" <?=($ls_status_kelayakan =="Y" || $ls_status_kelayakan =="Y")? " readonly class=disabled style=\"width:220px;\"" : " style=\"background-color:#ffff99;width:220px;\"";?>>
        <?
        if ($ls_status_kelayakan!="Y" && $ls_status_kelayakan !="Y")
        {
          ?>      								
          <input id="btn_tgl_kejadian" type="image" align="top" onclick="return showCalendar('tgl_kejadian', 'dd-mm-y');" src="../../images/calendar.gif" />							
          <?
        }
        ?>                      												   							
      </div>																																																								
      <div class="clear"></div>																																																	
    </span>
		
    <span id="span_kpj" style="display:block;">						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">No Referensi&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="kpj" name="kpj" value="<?=$ls_kpj;?>" tabindex="7" maxlength="30" <?=($ls_status_kelayakan =="Y" || $ls_status_kelayakan =="Y")? " readonly class=disabled style=\"width:80px;\"" : " style=\"width:80px;\"";?> readonly class="disabled">
        <input type="text" id="nama_tk" name="nama_tk" value="<?=$ls_nama_tk;?>" style="width:180px;" readonly class="disabled">
        <input type="hidden" id="kode_tk" name="kode_tk" value="<?=$ls_kode_tk;?>">
        <input type="hidden" id="kode_kantor_tk" name="kode_kantor_tk" value="<?=$ls_kode_kantor_tk;?>">
        <?
        if ($ls_status_submit!="Y")
        {
        ?>
        	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_lov_kpj.php?p=pn5037.php&a=formreg&b=kode_tk&c=kpj&d=nama_tk&e=kode_perusahaan&f=nama_perusahaan&g=kode_divisi&h=nama_divisi&j='+formreg.kode_segmen.value+'&k=npp&l=nomor_identitas&t=tempat_lahir&u=tgl_lahir&m=jenis_identitas&n=kode_kantor_tk&r='+formreg.tgl_kejadian.value+'','',1000,500,1)">					
  				<img src="../../images/help.png" alt="Cari Data TK" border="0" align="absmiddle"></a>			       
				<?
        }
        ?>
      </div>																																											
      <div class="clear"></div>
			
      <div class="form-row_kiri">
      <label  style = "text-align:right;">NIK&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" style="width:290px;" readonly class="disabled">
				<input type="hidden" id="jenis_identitas" name="jenis_identitas" value="<?=$ls_jenis_identitas;?>">
      </div>
      <span id="span_negara_penempatan" style="display:none;">
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Negara Penempatan</label>			
      	<input type="text" id="negara_penempatan" name="negara_penempatan" value="<?=$ls_negara_penempatan;?>" style="width:150px;" maxlength="100" readonly class="disabled">
  		</div>
      </span>																																										
      <div class="clear"></div>									
    </span>

    <span id="span_proyek" style="display:none;">						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">No Proyek&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="no_proyek" name="no_proyek" value="<?=$ls_no_proyek;?>" tabindex="8" maxlength="30" readonly <?=($ls_status_kelayakan =="Y" || $ls_status_kelayakan =="Y")? " style=\"width:85px;\" class=disabled" : " style=\"width:85px;background-color:#ffff99\"";?>>
        <input type="text" id="nama_proyek" name="nama_proyek" value="<?=$ls_nama_proyek;?>" style="width:175px;" readonly class="disabled">
        <?
        if ($ls_status_kelayakan!="Y" && $ls_status_kelayakan !="Y")
        {
        ?>      								
        	<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5037_lov_proyek.php?p=pn5037.php&a=formreg&b=kode_proyek&c=no_proyek&d=nama_proyek&e=kode_perusahaan&f=nama_perusahaan&g=npp','',975,550,1)">							
        <?
        }
        ?>                      											
        <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>		
        <input type="hidden" id="kode_proyek" name="kode_proyek" value="<?=$ls_kode_proyek;?>">			
      </div>																																										
      <div class="clear"></div>						
    </span>	
		
    <span id="span_kegiatan_tambahan" style="display:none;">						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Pelaksana Kegiatan &nbsp;</label>
        <input type="text" id="nama_pelaksana_kegiatan" name="nama_pelaksana_kegiatan" value="<?=$ls_nama_pelaksana_kegiatan;?>" style="width:290px;" class="disabled">
        <input type="hidden" id="tipe_pelaksana_kegiatan" name="tipe_pelaksana_kegiatan" value="<?=$ls_tipe_pelaksana_kegiatan;?>">
      </div>																																									
      <div class="clear"></div>	
      
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="<?=$ls_nama_kegiatan;?>" style="width:290px;" readonly class="disabled">			
        <input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>" readonly class="disabled">
        <input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>" readonly class="disabled">
      </div>																																									
      <div class="clear"></div>
    </span>	
		
    <div class="form-row_kiri">
    <label  style = "text-align:right;">NPP&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" style="width:80px;" readonly class="disabled">
      <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="<?=$ls_nama_perusahaan;?>" style="width:180px;" readonly class="disabled">
      <input type="hidden" id="kode_perusahaan" name="kode_perusahaan" value="<?=$ls_kode_perusahaan;?>">				
    </div>
    <span id="span_status_kepesertaan" style="display:none;">
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Perlindungan</label>			
      	<input type="hidden" id="status_kepesertaan" name="status_kepesertaan" value="<?=$ls_status_kepesertaan;?>" style="width:150px;" maxlength="30" readonly class="disabled">
      	<input type="hidden" id="kode_perlindungan" name="kode_perlindungan" value="<?=$ls_kode_perlindungan;?>" style="width:150px;" maxlength="30" readonly class="disabled">
  			<input type="text" id="nama_perlindungan" name="nama_perlindungan" value="<?=$ls_nama_perlindungan;?>" style="width:150px;" maxlength="30" readonly class="disabled">
  		</div>
    </span>																		
    <div class="clear"></div>
    
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Unit&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
      <input type="text" id="kode_divisi" name="kode_divisi" value="<?=$ls_kode_divisi;?>" style="width:60px;" readonly class="disabled">
      <input type="text" id="nama_divisi" name="nama_divisi" value="<?=$ls_nama_divisi;?>" style="width:180px;" readonly class="disabled">				
    </div>
    <div class="clear"></div>
    <div class="form-row_kiri">
      <label  style = "text-align:right;">Tempat Lahir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" style="width:290px;" readonly class="disabled">
				
      </div>
      <div class="clear"></div>
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Tgl Lahir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ls_tgl_lahir;?>" style="width:290px;" readonly class="disabled">
				
      </div>
      <div class="clear"></div>
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Alamat&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
				<textarea cols="255" rows="2" id="alamat_tk" name="alamat_tk" tabindex="31" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit =="Y")? " readonly class=disabled style=\"width:290px;background-color:#F5F5F5\"" : " style=\"width:290px;background-color:#ffff99\"";?>><?=$ls_alamat;?></textarea> 
      </div>
      <div class="clear"></div>
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Nomor Telepon&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="nomor_telepon_tk" tabindex="31" name="nomor_telepon_tk" value="<?=$ls_nomor_telepon;?>" <?=($ls_status_submit =="Y")? " readonly class=disabled style=\"width:290px;background-color:#F5F5F5\"" : " style=\"width:290px;background-color:#ffff99\"";?> class="" maxlength="13" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');">
      </div>
    <span id="span_masa_perlindungan" style="display:none;">
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Periode</label>
        <input type="text" id="ket_masa_perlindungan" name="ket_masa_perlindungan" value="<?=$ls_ket_masa_perlindungan;?>" style="width:150px;" readonly class="disabled">						
        <input type="hidden" id="tgl_awal_perlindungan" name="tgl_awal_perlindungan" value="<?=$ld_tgl_awal_perlindungan;?>"  maxlength="30" readonly class="disabled">
        <input type="hidden" id="tgl_akhir_perlindungan" name="tgl_akhir_perlindungan" value="<?=$ld_tgl_akhir_perlindungan;?>" maxlength="30" readonly class="disabled">
      </div>
    </span>																														
    <div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Keterangan Pernyataan&nbsp;*</label>
    	<textarea cols="255" rows="2" id="keterangan_pernyataan" name="keterangan_pernyataan" tabindex="31" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit =="Y")? " readonly class=disabled style=\"width:290px;background-color:#F5F5F5\"" : " style=\"width:290px;background-color:#ffff99\"";?>><?=$ls_keterangan;?></textarea>   					
    </div>								
    <div class="clear"></div>
    <br/>																																													
    <div class="form-row_kiri">
    <?php if($ls_status_submit == "T")
    {
    ?>
<input type="button" class="btn green" id="btn_batal" name="batal" value="BATAL">
    <input type="button" class="btn green" id="btn_ubah" name="ubah" value="UBAH">
    
    <input type="button" class="btn green" id="btn_simpan" name="simpan" value="SUBMIT">
    <?php } ?>
    <?php if($ls_status_submit == "Y")
    {
    ?>
    <input type="button" class="btn green" id="btn_cetak" name="cetak" value="Cetak"  onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5058_cetak.php?task=View&root_sender=pn5058.php&sender=pn5058.php&sender_mid=<?=$mid?>&kode_tk=<?=$ls_kode_tk?>&kode_agenda_pernyataan=<?=$dataid?>&kode_kantor=<?=$ls_kode_kantor?>&kode_segmen=<?=$ls_kode_segmen?>','',600,400,1)" >
    <?php } ?>
      </div>
    <div class="form-row_kiri" style="display:none;">
      <label  style = "text-align:right;"> &nbsp;</label>
        <input type="text" id="nama_pelaksana_kegiatan" name="nama_pelaksana_kegiatan" value="<?=$ls_nama_pelaksana_kegiatan;?>" style="width:290px;" class="disabled">
        <input type="hidden" id="tipe_pelaksana_kegiatan" name="tipe_pelaksana_kegiatan" value="<?=$ls_tipe_pelaksana_kegiatan;?>">
      </div>																																									
      <div class="clear"></div>	
    <div class="form-row_kiri" style="display:none;">
    <label  style = "text-align:right;">&nbsp;</label>						
      <? 
			if ($ls_kode_pointer_asal == "SPO")
			{
				 $ls_flag_spo = "Y";
			}else
			{
				 $ls_flag_spo = "T";
			}
			?>					
      <input type="checkbox" id="cb_flag_spo" name="cb_flag_spo" class="cebox" disabled <?=$ls_flag_spo=="Y" ? "checked" : "";?> >
      <i><font color="#009999"><b>SPO</b></font></i>	
    </div>																																																						
    <div class="clear"></div>

    <!-- RIFF--------------------------------------------------id nya dipake lov cb_flag_vok - -->
      <div class="form-row_kiri" style="display:none;">
      <label  style = "text-align:right;">&nbsp;&nbsp;</label>
      <? $ls_flag_vokasi = isset($ls_flag_vokasi) ? $ls_flag_vokasi : "1"; ?>              
      <input type="checkbox" id="flag_vokasi" name="flag_vokasi" class="cebox"  onclick="fl_js_set_stvokasi();" 
      <?=$ls_flagX_vokasi=="1" ||$ls_flagX_vokasi=="ON" ||$ls_flagX_vokasi=="on" ? "checked" : "";?>>
      <i><font color="#009999"><b>mengikuti vokasional</b></font></i>  
      </div>                                                                                                            
      <div class="clear"></div>

      

      <script language="JavaScript">
        function fl_js_set_stvokasi()
        {
          var form = document.adminForm;
          if (form.flag_vokasi.checked)
          {
            form.flag_vokasi.value = Y;
          }
          else
          {
            form.flag_vokasi.value = T;
          }
        }
      </script>
      
    <!-- ------------------------------------------------------------RIFF -->
		    
    <?
		echo "<script type=\"text/javascript\">fl_js_input_kode_segmen();</script>";
    echo "<script type=\"text/javascript\">fl_js_input_kpj();</script>";
    echo "<script type=\"text/javascript\">fl_js_tgl_kejadian();</script>";
    ?>																
	</fieldset>	 

  <?php
  if ($ls_kode_klaim!="")
  {
	  $ls_root_form = $_REQUEST["root_form"];
  ?>	
    <div id="buttonbox" style="width:930px">
  
   		<input type="submit" class="btn green" id="butentry" name="butentry" value="     ENTRY AGENDA KELAYAKAN    " />
  			 	 
    	<?php
      if ($ls_kode_klaim!="" && $ls_root_form == "pn5037" && $_REQUEST["task"] == "Edit")
      {
        ?>
          
          <?
    			// --------------------- button cek kelayakan ----------------------------
          //cek apakah sudah input data klaim ----------------------
          	$sql = "select count(*) as v_jml from pn.pn_agenda_klaim_kelayakan a ".
                   "where kode_agenda_kelayakan = '$ls_kode_klaim' ".
                   "and nvl(status_cek_kelayakan,'T') = 'T' ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ln_cnt_agenda_kelayakan 	= $row["V_JML"];  									 	
          
            if ($ln_cnt_agenda_kelayakan>"0" && $ls_status_kelayakan=="T") //agenda belum disubmit
            { 
              ?>
              <input type="submit" class="btn green" id="butcekkelayakan" name="butcekkelayakan" value="        CEK AGENDA KELAYAKAN        " onclick="if(confirm('Apakah anda yakin akan melakukan Cek Agenda Kelayakan..?')) fl_js_cek_kelayakan();" />
              <?
            }
    			// --------------------- end button cek kelayakan ------------------------
          ?>
          
    			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        
        <?
      }
      ?>
   	</div>	<!-- end div buttonbox -->	
  <?
  }
  ?>	
</div> <!-- end div formKiri -->
 
<script type="text/javascript">
  
</script>