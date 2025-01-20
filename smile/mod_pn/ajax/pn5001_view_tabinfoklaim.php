<script language="javascript">
  function fl_js_input_kpj() 
  { 
  	var v_kode_pointer_asal = window.document.getElementById('kode_pointer_asal').value;
  	var v_kode_segmen = window.document.getElementById('kode_segmen').value;
  		
  	if (v_kode_segmen =="JAKON")
  	{
      window.document.getElementById("span_proyek").style.display = 'block';
      window.document.getElementById("span_kpj").style.display = 'none';
      window.document.getElementById("span_kegiatan_tambahan").style.display = 'none';
      window.document.getElementById("kode_tk").value = "";
      window.document.getElementById("nama_tk").value = "";
      window.document.getElementById("nomor_identitas").value = "";
      window.document.getElementById("jenis_identitas").value = "";
      window.document.getElementById("kpj").value = "";			 
      window.document.getElementById("npp").value = "";
      window.document.getElementById("nama_perusahaan").value = "";
      window.document.getElementById("kode_perusahaan").value = "";
      window.document.getElementById("kode_divisi").value = "";
      window.document.getElementById("nama_divisi").value = "";
      window.document.getElementById("kode_kantor_tk").value = "";			 
		}else
		{
		 if (v_kode_pointer_asal !="") //data bersumber dari modul lain
		 {
        window.document.getElementById("span_proyek").style.display = 'none';
        window.document.getElementById("span_kpj").style.display = 'none';
        window.document.getElementById("span_kegiatan_tambahan").style.display = 'block';	
	 	 }else
		 {
        window.document.getElementById("span_proyek").style.display = 'none';
        window.document.getElementById("span_kpj").style.display = 'block';
        window.document.getElementById("span_kegiatan_tambahan").style.display = 'none';
        window.document.getElementById("kode_proyek").value = "";
        window.document.getElementById("no_proyek").value = "";
        window.document.getElementById("nama_proyek").value = "";						 				 		 
		 }			 		 
		} 	
  }	

  function fl_js_tgl_kejadian() 
  {     			
  	var v_jenis_klaim = window.document.getElementById('jenis_klaim').value;
  	var v_flag_meninggal = window.document.getElementById('flag_meninggal').value;
  				
  	//tgl kejadian hanya bisa diisi hanya utk klaim JKK/Meninggal ------------
  	if (v_jenis_klaim == "JKK" || v_jenis_klaim == "JKM" || v_jenis_klaim == "JHM" || v_flag_meninggal == "Y")
    {
      window.document.getElementById("span_tgl_kejadian").style.display = 'block';
  	}else
  	{
  	 	window.document.getElementById("span_tgl_kejadian").style.display = 'none';	 
  	}	
  	
  	//utk klaim jkk/jkm tampilkan masa perlindungan --------------------------
  	if (v_jenis_klaim == "JKK" || v_jenis_klaim == "JKM")
  	{
  		window.document.getElementById("span_status_kepesertaan").style.display = 'block';
  		window.document.getElementById("span_kode_perlindungan").style.display = 'block';
  		window.document.getElementById("span_masa_perlindungan").style.display = 'block';		
  	}else
    {
  		window.document.getElementById("span_status_kepesertaan").style.display = 'none';
  		window.document.getElementById("span_kode_perlindungan").style.display = 'none';
  		window.document.getElementById("span_masa_perlindungan").style.display = 'none';   
    }	
  }
			
</script>		
	
<div id="formKiri" style="width:820px;">
	<fieldset><legend><b><i><font color="#009999">Informasi Agenda Klaim </font></i></b><b><?=$ls_status_batal=="Y" ? "<font color=#ff0000> (* Data Klaim Sudah Dibatalkan *)</font>" : "";?> </b></legend>
    <div class="form-row_kiri">
    <label style = "text-align:right;">Kantor &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>
      <select id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" style="width:300px;background-color:#F5F5F5;">
      <option value="">-- Pilih --</option>
      <?
      $sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
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
    <label style = "text-align:right;">Kode Klaim</label>					
      <input type="text" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>" style="width:150px;" maxlength="20" readonly class="disabled">																																	 								
    </div>				
    <div class="clear"></div>	
		
    <div class="form-row_kiri">
    <label style = "text-align:right;">Segmentasi Keps &nbsp; *</label>
      <select id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>" style="width:300px;background-color:#F5F5F5;">
      <option value="">-- Pilih --</option>
      <?
      $sql = "select kode_segmen, nama_segmen from sijstk.kn_kode_segmen where kode_segmen = '$ls_kode_segmen'";									                       
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
    </div>
    <div class="form-row_kanan">
    <label  style = "text-align:right;">Tgl Klaim</label>			
    	<input type="text" id="tgl_klaim" name="tgl_klaim" value="<?=$ld_tgl_klaim;?>" style="width:150px;" maxlength="10" readonly class="disabled">
    </div>						
    <div class="clear"></div>	

    <span id="span_kpj" style="display:block;">						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">No Referensi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="kpj" name="kpj" value="<?=$ls_kpj;?>" style="width:80px;" maxlength="30" readonly class="disabled">
        <input type="text" id="nama_tk" name="nama_tk" value="<?=$ls_nama_tk;?>" style="width:191px;" readonly class="disabled">
        <input type="hidden" id="kode_tk" name="kode_tk" value="<?=$ls_kode_tk;?>">
        <input type="hidden" id="kode_kantor_tk" name="kode_kantor_tk" value="<?=$ls_kode_kantor_tk;?>">
      </div>																																								
      <div class="clear"></div>
			
      <div class="form-row_kiri">
      <label  style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" style="width:280px;" readonly class="disabled">
				<input type="hidden" id="jenis_identitas" name="jenis_identitas" value="<?=$ls_jenis_identitas;?>">
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Tgl Penetapan</label>			
      	<input type="text" id="tgl_penetapan" name="tgl_penetapan" value="<?=$ld_tgl_penetapan;?>" style="width:150px;" maxlength="10" readonly class="disabled">
      </div>																																									
      <div class="clear"></div>									
    </span>

    <span id="span_proyek" style="display:none;">						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">No Proyek &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="no_proyek" name="no_proyek" value="<?=$ls_no_proyek;?>" style="width:80px;" maxlength="30" readonly class="disabled">
        <input type="text" id="nama_proyek" name="nama_proyek" value="<?=$ls_nama_proyek;?>" style="width:180px;" readonly class="disabled">                   											
        <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>		
        <input type="hidden" id="kode_proyek" name="kode_proyek" value="<?=$ls_kode_proyek;?>">			
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Tgl Penetapan</label>			
      	<input type="text" id="tgl_penetapan2" name="tgl_penetapan2" value="<?=$ld_tgl_penetapan;?>" style="width:150px;" maxlength="10" readonly class="disabled">
      </div>																																										
      <div class="clear"></div>						
    </span>

    <span id="span_kegiatan_tambahan" style="display:none;">						
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Pelaksana Kegiatan &nbsp;</label>
        <input type="text" id="nama_pelaksana_kegiatan" name="nama_pelaksana_kegiatan" value="<?=$ls_nama_pelaksana_kegiatan;?>" style="width:280px;" readonly class="disabled">
        <input type="hidden" id="tipe_pelaksana_kegiatan" name="tipe_pelaksana_kegiatan" value="<?=$ls_tipe_pelaksana_kegiatan;?>">
      </div>
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Tgl Penetapan</label>			
      	<input type="text" id="tgl_penetapan3" name="tgl_penetapan3" value="<?=$ld_tgl_penetapan;?>" style="width:150px;" maxlength="10" readonly class="disabled">
      </div>																																									
      <div class="clear"></div>	
      
      <div class="form-row_kiri">
      <label  style = "text-align:right;">Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
        <input type="text" id="nama_kegiatan" name="nama_kegiatan" value="<?=$ls_nama_kegiatan;?>" style="width:280px;" readonly class="disabled">	
        <input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>" readonly class="disabled">
        <input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>" readonly class="disabled">
      </div>																																									
      <div class="clear"></div>
    </span>

    <div class="form-row_kiri">
    <label  style = "text-align:right;">NPP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
      <input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" style="width:80px;" readonly class="disabled">
      <input type="text" id="nama_perusahaan" name="nama_perusahaan" value="<?=$ls_nama_perusahaan;?>" style="width:175px;" readonly class="disabled">
      <input type="hidden" id="kode_perusahaan" name="kode_perusahaan" value="<?=$ls_kode_perusahaan;?>" readonly class="disabled">				
    </div>
    <div class="form-row_kanan">
    <label style = "text-align:right;">Petugas Penetapan&nbsp;</label>
    	<input type="text" id="petugas_penetapan" name="petugas_penetapan" value="<?=$ls_petugas_penetapan;?>" style="width:150px;" maxlength="30" readonly class="disabled">   					
    </div>																		
    <div class="clear"></div>
    
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Unit &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>
      <input type="text" id="kode_divisi" name="kode_divisi" value="<?=$ls_kode_divisi;?>" style="width:80px;" readonly class="disabled">
      <input type="text" id="nama_divisi" name="nama_divisi" value="<?=$ls_nama_divisi;?>" style="width:160px;" readonly class="disabled">				
    </div>
    <div class="form-row_kanan">
    <label  style = "text-align:right;">No. Penetapan</label>			
    	<input type="text" id="no_penetapan" name="no_penetapan" value="<?=$ls_no_penetapan;?>" style="width:150px;" maxlength="10" readonly class="disabled">
    </div>																											
    <div class="clear"></div>
		
    </br>
    
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Tipe Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
      <input type="text" id="kode_tipe_klaim" name="kode_tipe_klaim" value="<?=$ls_kode_tipe_klaim;?>" style="width:80px;" maxlength="30" readonly class="disabled">
      <input type="text" id="nama_tipe_klaim" name="nama_tipe_klaim" value="<?=$ls_nama_tipe_klaim;?>" style="width:160px;" readonly class="disabled">                 										
      <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>											
      <input type="hidden" id="jenis_klaim" name="jenis_klaim" value="<?=$ls_jenis_klaim;?>" maxlength="30">	
    </div>
    <span id="span_status_kepesertaan" style="display:none;">
    <div class="form-row_kanan">
    <label  style = "text-align:right;">Status Keps</label>			
    	<input type="text" id="status_kepesertaan" name="status_kepesertaan" value="<?=$ls_status_kepesertaan;?>" style="width:150px;" maxlength="30" readonly class="disabled">
    </div>
    </span>																																																		
    <div class="clear"></div>	
    
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Sebab Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
      <input type="text" id="kode_sebab_klaim" name="kode_sebab_klaim" value="<?=$ls_kode_sebab_klaim;?>" style="width:80px;" maxlength="30" readonly class="disabled">
      <input type="text" id="keyword_sebab_klaim" name="keyword_sebab_klaim" value="<?=$ls_keyword_sebab_klaim;?>" style="width:30px;" readonly class="disabled">
			<input type="text" id="nama_sebab_klaim" name="nama_sebab_klaim" value="<?=$ls_nama_sebab_klaim;?>" style="width:121px;" readonly class="disabled">
      <input type="hidden" id="flag_meninggal" name="flag_meninggal" value="<?=$ls_flag_meninggal;?>" readonly class="disabled">    																	
      <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>			
    </div>
    <span id="span_kode_perlindungan" style="display:none;">
    <div class="form-row_kanan">
    <label  style = "text-align:right;">Perlindungan</label>			
    	<input type="text" id="kode_perlindungan" name="kode_perlindungan" value="<?=$ls_kode_perlindungan;?>" style="width:150px;" maxlength="30" readonly class="disabled">
    </div>
    </span>																																																					
    <div class="clear"></div>
    
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Lapor *</label>
      <input type="text" id="tgl_lapor" name="tgl_lapor" value="<?=$ld_tgl_lapor;?>" style="width:230px;" maxlength="10" onblur="convert_date(tgl_lapor);" readonly class="disabled">                											   							
    </div>
    <span id="span_masa_perlindungan" style="display:none;">
      <div class="form-row_kanan">
      <label  style = "text-align:right;">Periode</label>
        <input type="text" id="ket_masa_perlindungan" name="ket_masa_perlindungan" value="<?=$ls_ket_masa_perlindungan;?>" style="width:150px;" readonly class="disabled">						
        <input type="hidden" id="tgl_awal_perlindungan" name="tgl_awal_perlindungan" value="<?=$ld_tgl_awal_perlindungan;?>" maxlength="30" readonly class="disabled">
        <input type="hidden" id="tgl_akhir_perlindungan" name="tgl_akhir_perlindungan" value="<?=$ld_tgl_akhir_perlindungan;?>" maxlength="30" readonly class="disabled">
      </div>
    </span>
    <div class="clear"></div>																																																																											

    <span id="span_tgl_kejadian" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Tgl Kejadian *</label>	 
        <input type="text" id="tgl_kejadian" name="tgl_kejadian" value="<?=$ld_tgl_kejadian;?>" style="width:230px;" maxlength="10" onblur="convert_date(tgl_kejadian);" readonly class="disabled">                    												   							
      </div>																																																								
      <div class="clear"></div>																																																	
    </span>
			    
    <div class="form-row_kiri">
    <label style = "text-align:right;">Keterangan&nbsp;</label>
    	<textarea cols="255" rows="1" id="keterangan" name="keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly class="disabled" style="width:250px;background-color:#F5F5F5;"><?=$ls_keterangan;?></textarea>   					
    </div>
    <div class="form-row_kanan">
    <label style = "text-align:right;">Status&nbsp;</label>
    	<input type="text" id="status_klaim" name="status_klaim" value="<?=$ls_status_klaim;?>" style="width:150px;" maxlength="30" readonly class="disabled">   					
    </div>																																																						
    <div class="clear"></div>

    <div class="form-row_kiri">
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
      <input type="checkbox" id="cb_flag_spo" name="cb_flag_spo" class="cebox" disabled <?=$ls_flag_spo=="Y" ? "checked" : "";?>>
      <i><font color="#009999"><b>SPO</b></font></i>	
    </div>																																																						
    <div class="clear"></div>
		    
    <?
    echo "<script type=\"text/javascript\">fl_js_input_kpj();</script>";
    echo "<script type=\"text/javascript\">fl_js_tgl_kejadian();</script>";
    ?>
																																																																								
	</fieldset>
									 
</div> <!-- end div formKiri -->

<div id="formKanan">
<?php 					
if ($ls_kode_klaim!="")
{
  ?>	
  <fieldset>   				              									
    <input type="button" class="rightbutton btn green" id="btncetak" name="btncetak" value=" CETAK " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_cetak.php?kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Cetak Agenda Klaim',800,620,'no')"><br />
    <input type="button" class="rightbutton btn green" id="btndaftarkelayakan" name="btndaftarkelayakan" value=" KELAYAKAN " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_daftarcekkelayakan.php?&kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Daftar Hasil Cek Kelayakan',900,550,'no')"><br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />
    <br />	
    <br />																    									      						          						
    <input type="button" class="rightbutton btn green" id="btnaktivitas" name="btnaktivitas" value=" AKTIVITAS " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_daftaraktivitas.php?&kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Daftar Aktivitas Klaim',800,550,'yes')"><br />
    <input type="button" class="rightbutton btn green" id="btnapproval" name="btnapproval" value=" APPROVAL " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_daftarapproval.php?&kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Daftar Persetujuan Klaim',800,550,'no')"><br />
    <!--<input type="button" class="rightbutton btn green" id="btninfokeps" name="btninfokeps" value=" KEPESERTAAN " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_kn/ajax/kb5010_detil.php?task=View&dataid=<?=$ls_kode_tk;?>&kode_tk=<?=$ls_kode_tk;?>&kode_perusahaan=<?=$ls_kode_perusahaan;?>&kode_divisi=<?=$ls_kode_divisi;?>&kode_segmen=<?=$ls_kode_segmen;?>&mid=<?=$mid;?>','KB5008 - DETIL INFORMASI TENAGA KERJA',1100,650,'no')"><br />-->
		<br />
    <br />
		<br />
		<br />																									
  </fieldset>
<?php
}
?>	
</div> <!-- end div formKanan -->
