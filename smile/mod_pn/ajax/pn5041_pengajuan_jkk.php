<ul id="nav"">					
  <li><a href="#tab1" id="t1">&nbsp;&nbsp;&nbsp;Informasi Agenda Klaim JKK Tahap I&nbsp;&nbsp;&nbsp;</a></li>	
	<li><a href="#tab2" id="t2">&nbsp;&nbsp;&nbsp;Informasi Pengajuan JKK Tahap I&nbsp;&nbsp;&nbsp;</a></li>
	<li><a href="#tab3" id="t3">&nbsp;&nbsp;&nbsp;Aktivitas Pelaporan/Cek Kasus&nbsp;&nbsp;&nbsp;</a></li>	
</ul>

<div style="display: none;" id="tab1" class="tab_konten">
  <div id="konten">
  <?
  //------------- informasi klaim ----------------------------------------------
  include "../ajax/pn5040_view_tabinfoklaim.php";
  include "../ajax/pn5040_view_jkk_tahap1.php";
  include "../ajax/pn5040_view_tabadministrasi.php";
  ?>											
  </div>
</div>

<?
$ls_tapjkk1_kode_jenis_kasus 		 = $ls_kode_jenis_kasus;
$ls_tapjkk1_kode_tindakan_bahaya = $ls_kode_tindakan_bahaya;
$ls_tapjkk1_kode_kondisi_bahaya	 = $ls_kode_kondisi_bahaya;
$ls_tapjkk1_kode_corak					 = $ls_kode_corak;
$ls_tapjkk1_nama_corak					 = $ls_nama_corak;
$ls_tapjkk1_kode_sumber_cedera	 = $ls_kode_sumber_cedera;
$ls_tapjkk1_nama_sumber_cedera	 = $ls_nama_sumber_cedera;
$ls_tapjkk1_kode_bagian_sakit		 = $ls_kode_bagian_sakit;
$ls_tapjkk1_nama_bagian_sakit		 = $ls_nama_bagian_sakit;
$ls_tapjkk1_kode_akibat_diderita = $ls_kode_akibat_diderita;
$ls_tapjkk1_nama_akibat_diderita = $ls_nama_akibat_diderita;		
$ls_tapjkk1_kode_lama_bekerja 	 = $ls_kode_lama_bekerja;
$ls_tapjkk1_kode_penyakit_timbul = $ls_kode_penyakit_timbul;
$ls_tapjkk1_nama_penyakit_timbul = $ls_nama_penyakit_timbul;
$ls_tapjkk1_flag_rtw             = $ls_flag_rtw;	
if ($ls_tapjkk1_kode_penyakit_timbul!="" && $ls_tapjkk1_nama_penyakit_timbul=="")
{
  $sql = "select keterangan from sijstk.ms_lookup a where tipe='KLMPNYKIT' and kode = '$ls_tapjkk1_kode_penyakit_timbul' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_tapjkk1_nama_penyakit_timbul = $row['KETERANGAN']; 	 
}	
?>

<script language="javascript">
  
function choose_rtw(val)
{
  if(val=='Y'){
    document.getElementById("notif_rtw").style.display = "block";
  } else {
    document.getElementById("notif_rtw").style.display = "none";
  }
}
function fl_js_kode_jenis_kasus() 
{ 
	var v_kode_jenis_kasus = window.document.getElementById('tapjkk1_kode_jenis_kasus').value;
	
	if (v_kode_jenis_kasus =="KS01") //kecelakaan kerja --------------------------
  {
    window.document.getElementById("span_kecelakaan_kerja").style.display = 'block';
    window.document.getElementById("span_penyakit_akibat_kerja").style.display = 'none';
		window.document.getElementById("tapjkk1_kode_lama_bekerja").value = "";
		window.document.getElementById("tapjkk1_kode_penyakit_timbul").value = "";		
  }else if (v_kode_jenis_kasus =="KS02") //penyakit akibat kerja ---------------
  {
    window.document.getElementById("span_kecelakaan_kerja").style.display = 'none';
    window.document.getElementById("span_penyakit_akibat_kerja").style.display = 'block';
    window.document.getElementById("tapjkk1_kode_tindakan_bahaya").value = "";
		window.document.getElementById("tapjkk1_kode_kondisi_bahaya").value = "";
		window.document.getElementById("tapjkk1_kode_corak").value = "";
		window.document.getElementById("tapjkk1_kode_sumber_cedera").value = "";
		window.document.getElementById("tapjkk1_kode_bagian_sakit").value = "";
		window.document.getElementById("tapjkk1_kode_akibat_diderita").value = "";	
  } 	
}		
</script>
		
<div style="display: none;" id="tab2" class="tab_konten">
  <div id="konten">
		<div id="formKiri" style="width:1100px;">

			<table width="1050px" border="0">
				<tr>		 
          <!-- Informasi Pengajuan Klaim JKK Tahap I -------------------------->	
          <td width="55%" valign="top">
            <fieldset style="height:210px;"><legend><b><i><font color="#009999">Informasi Pengajuan Klaim JKK Tahap I &nbsp;<?=$ls_nama_tk;?></font></i></b></legend>
							</br></br>				
              <span id="span_kecelakaan_kerja" style="display:none;">						
                <div class="form-row_kiri">
                <label style = "text-align:right;">Tindakan Bahaya &nbsp; <span style="color:red;">*</span></label>		 	    				
                  <select size="1" id="tapjkk1_kode_tindakan_bahaya" name="tapjkk1_kode_tindakan_bahaya" value="<?=$ls_tapjkk1_kode_tindakan_bahaya;?>" tabindex="1" class="select_format" style="width:330px;background-color:#ffff99;" >
                  <option value="">-- pilih --</option>
                  <? 
                  $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMTINDBHY' and nvl(aktif,'T')='Y' order by seq";
                  $DB->parse($sql);
                  $DB->execute();
                  while($row = $DB->nextrow())
                  {
                  echo "<option ";
                  if ($row["KODE"]==$ls_tapjkk1_kode_tindakan_bahaya && strlen($ls_tapjkk1_kode_tindakan_bahaya)==strlen($row["KODE"])){ echo " selected"; }
                  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                  }
                  ?>
                  </select>
          				<input type="hidden" id="tapjkk1_kode_jenis_kasus" name="tapjkk1_kode_jenis_kasus" value="<?=$ls_tapjkk1_kode_jenis_kasus;?>">		
                </div>																																										
                <div class="clear"></div>							
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Kondisi Bahaya &nbsp;<span style="color:red;">*</span></label>		 	    				
                  <select size="1" id="tapjkk1_kode_kondisi_bahaya" name="tapjkk1_kode_kondisi_bahaya" value="<?=$ls_tapjkk1_kode_kondisi_bahaya;?>" tabindex="2" class="select_format" style="width:330px;background-color:#ffff99;" >
                  <option value="">-- pilih --</option>
                  <? 
                  $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMKONDBHY' and nvl(aktif,'T')='Y' order by seq";
                  $DB->parse($sql);
                  $DB->execute();
                  while($row = $DB->nextrow())
                  {
                  echo "<option ";
                  if ($row["KODE"]==$ls_tapjkk1_kode_kondisi_bahaya && strlen($ls_tapjkk1_kode_kondisi_bahaya)==strlen($row["KODE"])){ echo " selected"; }
                  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                  }
                  ?>
                  </select>		
                </div>																																										
                <div class="clear"></div>
                
								</br>
                <div class="form-row_kiri">
                <label style = "text-align:right;">Corak &nbsp; <span style="color:red;">*</span></label>
                  <input type="text" id="tapjkk1_kode_corak" name="tapjkk1_kode_corak" value="<?=$ls_tapjkk1_kode_corak;?>" tabindex="3" style="width:40px;background-color:#ffff99;" maxlength="10" readonly>
                  <input type="text" id="tapjkk1_nama_corak" name="tapjkk1_nama_corak" value="<?=$ls_tapjkk1_nama_corak;?>" style="width:250px;" readonly class="disabled">
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_corak.php?p=pn5041.php&a=adminForm&b=tapjkk1_kode_corak&c=tapjkk1_nama_corak','',800,500,1)">
                  <img src="../../images/help.png" alt="LOV Corak" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Sumber Cedera &nbsp; <span style="color:red;">*</span></label>
                  <input type="text" id="tapjkk1_kode_sumber_cedera" name="tapjkk1_kode_sumber_cedera" value="<?=$ls_tapjkk1_kode_sumber_cedera;?>" tabindex="4" style="width:40px;background-color:#ffff99;" maxlength="10" readonly>
                  <input type="text" id="tapjkk1_nama_sumber_cedera" name="tapjkk1_nama_sumber_cedera" value="<?=$ls_tapjkk1_nama_sumber_cedera;?>" style="width:250px;" readonly class="disabled">
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_sumbercedera.php?p=pn5041.php&a=adminForm&b=tapjkk1_kode_sumber_cedera&c=tapjkk1_nama_sumber_cedera','',800,500,1)">
                  <img src="../../images/help.png" alt="LOV Sumber Cedera" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Bagian yang Sakit &nbsp; <span style="color:red;">*</span></label>
                  <input type="text" id="tapjkk1_kode_bagian_sakit" name="tapjkk1_kode_bagian_sakit" value="<?=$ls_tapjkk1_kode_bagian_sakit;?>" tabindex="5" style="width:40px;background-color:#ffff99;" maxlength="10" readonly>
                  <input type="text" id="tapjkk1_nama_bagian_sakit" name="tapjkk1_nama_bagian_sakit" value="<?=$ls_tapjkk1_nama_bagian_sakit;?>" style="width:250px;" readonly class="disabled">
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_bagiansakit.php?p=pn5041.php&a=adminForm&b=tapjkk1_kode_bagian_sakit&c=tapjkk1_nama_bagian_sakit','',800,500,1)">
                  <img src="../../images/help.png" alt="LOV Bagian yang Sakit" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Akibat yg Diderita <span style="color:red;">*</span></label>
                  <input type="text" id="tapjkk1_kode_akibat_diderita" name="tapjkk1_kode_akibat_diderita" value="<?=$ls_tapjkk1_kode_akibat_diderita;?>" tabindex="6" style="width:40px;background-color:#ffff99;" maxlength="10" readonly>
                  <input type="text" id="tapjkk1_nama_akibat_diderita" name="tapjkk1_nama_akibat_diderita" value="<?=$ls_tapjkk1_nama_akibat_diderita;?>" style="width:210px;" readonly class="disabled">
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_akibatdiderita.php?p=pn5041.php&a=adminForm&b=tapjkk1_kode_akibat_diderita&c=tapjkk1_nama_akibat_diderita&d='+adminForm.kode_segmen.value+'','',800,500,1)">
                  <img src="../../images/help.png" alt="LOV Akibat yg Diderita" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>																																																	
              </span>	<!-- end span "span_kecelakaan_kerja" -->	

              <?php if($ls_tapjkk1_kode_jenis_kasus == "KS01" && $ls_kode_segmen == "PU" && ($ls_kanal_pelayanan == 'KANAL03' || $ls_kanal_pelayanan == '66')){ ?>
              <div class="form-row_kiri">
                <label style = "text-align:right;">Mengikuti RTW <span style="color:red;">*</span></label>
                <select size="1" id="tapjkk1_flag_rtw" name="tapjkk1_flag_rtw" value="<?=$ls_tapjkk1_flag_rtw;?>" tabindex="2" class="select_format" onchange="choose_rtw(this.value)" style="width:330px;background-color:#ffff99;" >		
				        <option value="">-- pilih --</option>			
				        <option value="Y" <?php if ($ls_tapjkk1_flag_rtw == "Y") { echo ' selected="selected"'; } ?>>YA</option>
				        <option value="T" <?php if ($ls_tapjkk1_flag_rtw == "T") { echo ' selected="selected"'; } ?>>TIDAK</option>
	              </select>		
                <div id="notif_rtw" style="margin-left:135px; color:red; display:none;">Peserta mengikuti RTW, silakan melakukan aktifitas tahapan RTW melalui  menu Pelayanan->JKK RTW->PN5033-Implementasi JKK RTW.</div>										
                </div>																																										
                <div class="clear"></div>																																																	
              </span>	
              <?php } else { ?>
                      <input type="hidden" id="tapjkk1_flag_rtw" value="T">
              <?php } ?>
              
              <span id="span_penyakit_akibat_kerja" style="display:none;">
								</br></br>		
                <div class="form-row_kiri">
                <label style = "text-align:right;">Lama Bekerja (Thn) &nbsp;</label>		 	    				
                  <select size="1" id="tapjkk1_kode_lama_bekerja" name="tapjkk1_kode_lama_bekerja" value="<?=$ls_tapjkk1_kode_lama_bekerja;?>" tabindex="7" class="select_format" style="width:330px;background-color:#ffff99;" >
                  <option value="">-- pilih --</option>
                  <? 
                  $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMLAMAKRJ' and nvl(aktif,'T')='Y' order by seq";
                  $DB->parse($sql);
                  $DB->execute();
                  while($row = $DB->nextrow())
                  {
                  echo "<option ";
                  if ($row["KODE"]==$ls_tapjkk1_kode_lama_bekerja && strlen($ls_tapjkk1_kode_lama_bekerja)==strlen($row["KODE"])){ echo " selected"; }
                  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                  }
                  ?>
                  </select>		
                </div>																																										
                <div class="clear"></div>								
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Penyakit yg Timbul &nbsp;</label>
                  <input type="text" id="tapjkk1_kode_penyakit_timbul" name="tapjkk1_kode_penyakit_timbul" value="<?=$ls_tapjkk1_kode_penyakit_timbul;?>" tabindex="8" style="width:40px;background-color:#ffff99;" maxlength="10" readonly>
                  <input type="text" id="tapjkk1_nama_penyakit_timbul" name="tapjkk1_nama_penyakit_timbul" value="<?=$ls_tapjkk1_nama_penyakit_timbul;?>"style="width:245px;" readonly class="disabled">
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_penyakittimbul.php?p=pn5041.php&a=adminForm&b=tapjkk1_kode_penyakit_timbul&c=tapjkk1_nama_penyakit_timbul','',800,500,1)">
                  <img src="../../images/help.png" alt="LOV Bagian yang Sakit" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>										
              </span>
              
              <?
              echo "<script type=\"text/javascript\">fl_js_kode_jenis_kasus();</script>";
              ?>																	
            </fieldset>	 					
					</td>

          <!-- Tempat Tenaga Kerja Dirawat ------------------------------------>	
          <td width="45%" valign="top">
            <?
            $ls_tapjkk1_kode_tempat_perawatan = $ls_kode_tempat_perawatan;
            $ls_tapjkk1_kode_berobat_jalan		= $ls_kode_berobat_jalan;
            $ls_tapjkk1_kode_ppk							= $ls_kode_ppk;
            $ls_tapjkk1_nama_ppk							= $ls_nama_ppk;
            $ls_tapjkk1_nama_faskes_reimburse	= $ls_nama_faskes_reimburse;												 
            ?>
      			<table>
							<tr>
								<td>
                  <fieldset style="height:100px;" ><legend><b><i><font color="#009999">Tempat Tenaga Kerja Dirawat</font></i></b></legend>
										</br>				
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Tempat Perawatan &nbsp;</label>		 	    				
                      <select size="1" id="tapjkk1_kode_tempat_perawatan" name="tapjkk1_kode_tempat_perawatan" value="<?=$ls_tapjkk1_kode_tempat_perawatan;?>" tabindex="9" class="select_format" style="width:230px;background-color:#ffff99;" onchange="fl_js_tapjkk1_kode_tempat_perawatan(this.value);" >
                      <option value="">-- pilih --</option>
                      <? 
                      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMTMPTRWT' and nvl(aktif,'T')='Y' order by seq";
                      $DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE"]==$ls_tapjkk1_kode_tempat_perawatan && strlen($ls_tapjkk1_kode_tempat_perawatan)==strlen($row["KODE"])){ echo " selected"; }
                      echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                      }
                      ?>
                      </select>		
                    </div>																																											
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Berobat Jalan &nbsp;</label>		 	    				
                      <select size="1" id="tapjkk1_kode_berobat_jalan" name="tapjkk1_kode_berobat_jalan" value="<?=$ls_tapjkk1_kode_berobat_jalan;?>" tabindex="10" class="select_format" style="width:230px;background-color:#ffff99;" >
                      <option value="">-- pilih --</option>
                      <? 
                      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMBOBTJLN' and nvl(aktif,'T')='Y' order by seq";
                      $DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE"]==$ls_tapjkk1_kode_berobat_jalan && strlen($ls_tapjkk1_kode_berobat_jalan)==strlen($row["KODE"])){ echo " selected"; }
                      echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                      }
                      ?>
                      </select>		
                    </div>																																												
                    <div class="clear"></div>			
                    
                    <span id="span_faskes" style="display:none;">
                      <div class="form-row_kiri">
                      <label  style = "text-align:right;">Fasilitas Kesehatan</label>
                        <input type="text" id="tapjkk1_kode_ppk" name="tapjkk1_kode_ppk" value="<?=$ls_tapjkk1_kode_ppk;?>" tabindex="11" maxlength="20" readonly style="background-color:#ffff99;width:50px;" >
                        <input type="text" id="tapjkk1_nama_ppk" name="tapjkk1_nama_ppk" value="<?=$ls_tapjkk1_nama_ppk;?>" style="width:145px;" readonly class="disabled">
                        <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_faskes.php?p=pn5041.php&a=adminForm&b=tapjkk1_kode_ppk&c=tapjkk1_nama_ppk','',800,500,1)">
                        <img src="../../images/help.png" alt="Cari Faskes" border="0" align="absmiddle"></a>
                      </div>	
                      <div class="clear"></div>
                    </span>
                    
                    <span id="span_reimburse" style="display:none;">						
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Reimburse</label>		 	    				
                        <input type="text" id="tapjkk1_nama_faskes_reimburse" name="tapjkk1_nama_faskes_reimburse" value="<?=$ls_tapjkk1_nama_faskes_reimburse;?>" tabindex="12" maxlength="100" style="background-color:#ffff99;width:215px;" placeholder="(* isikan Nama RS/Puskesmas/Poli/dll)">
                      </div>																																														
                      <div class="clear"></div>
                    </span>
                    
                    <script language="javascript">
                    function fl_js_tapjkk1_kode_tempat_perawatan() 
                    { 
                      var form = document.adminForm;
                      var	v_tempat_perawatan = form.tapjkk1_kode_tempat_perawatan.value;
                      
                      if (v_tempat_perawatan =="TR01") //faskes/trauma center
                      {
                        window.document.getElementById("span_faskes").style.display = 'block';
                        window.document.getElementById("span_reimburse").style.display = 'none';
                        window.document.getElementById("tapjkk1_nama_faskes_reimburse").value = "";
                      }else
                      {
                        window.document.getElementById("span_reimburse").style.display = 'block';
                        window.document.getElementById("span_faskes").style.display = 'none';            			 
                        window.document.getElementById("tapjkk1_kode_ppk").value = "";	
                        window.document.getElementById("tapjkk1_nama_ppk").value = "";	 									 		 
                      } 	
                    }
                    </script>						
                    <?
                    echo "<script type=\"text/javascript\">fl_js_tapjkk1_kode_tempat_perawatan();</script>";
                    ?>							  
                  </fieldset>										
								</td>	
							</tr>
							
							<tr>
								<!-- Upah yang Diterima oleh Tenaga Kerja --------------------->	
								<td width="45%" >
									
									<?
                  $ls_tapjkk1_kode_tipe_upah 		= $ls_kode_tipe_upah;
                  $ln_tapjkk1_nom_upah_terakhir = $ln_nom_upah_terakhir;
									$ld_tapjkk1_blth_upah_terakhir = $ld_blth_upah_terakhir;
                  ?>
                  <script language="javascript">
                    function fl_js_jkk1_set_field_upahtk(v_kode_segmen) 
                    { 
                      if (v_kode_segmen =="TKI") //TKI tidak ada input upah --------------
                      {    
                        window.document.getElementById("tapjkk1_span_upah_tk").style.display = 'none';
                        window.document.getElementById("tapjkk1_kode_tipe_upah").value = "";
                        window.document.getElementById("tapjkk1_nom_upah_terakhir").value = "";	
												window.document.getElementById("tapjkk1_blth_upah_terakhir").value = "";								
                      }else // selain TKI ----------------
                      {
                       	window.document.getElementById("tapjkk1_span_upah_tk").style.display = 'block';
                      } 	
                    }	
                  </script>
                  
                  <span id="tapjkk1_span_upah_tk" style="display:none;">
                    <fieldset style="height:80px;"><legend><b><i><font color="#009999">Upah yang Diterima oleh Tenaga Kerja</font></i></b></legend>								 										 
                      </br>
											<div class="form-row_kiri">
                      <label style = "text-align:right;">Tipe Upah &nbsp;</label>		 	    				
                        <select size="1" id="tapjkk1_kode_tipe_upah" name="tapjkk1_kode_tipe_upah" value="<?=$ls_tapjkk1_kode_tipe_upah;?>" tabindex="45" class="select_format" style="width:230px;background-color:#ffff99;" >
                        <option value="">-- pilih --</option>
                        <? 
                        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TPUPA'||'$ls_kode_segmen' and nvl(aktif,'T')='Y' order by seq";
                        $DB->parse($sql);
                        $DB->execute();
                        while($row = $DB->nextrow())
                        {
                        echo "<option ";
                        if ($row["KODE"]==$ls_tapjkk1_kode_tipe_upah && strlen($ls_tapjkk1_kode_tipe_upah)==strlen($row["KODE"])){ echo " selected"; }
                        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                        }
                        ?>
                        </select>		
                      </div>																																											
                      <div class="clear"></div>
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Upah Terakhir&nbsp; *</label>		 	    				
                        <input type="text" id="tapjkk1_nom_upah_terakhir" name="tapjkk1_nom_upah_terakhir" value="<?=number_format((float)$ln_tapjkk1_nom_upah_terakhir,2,".",",");?>" tabindex="46" style="width:205px;" maxlength="20" onblur="this.value=format_uang(this.value);" <?=($ls_kode_segmen =="JAKON")? " style=\"background-color:#ffff99\"" : " readonly class=disabled ";?>>
												<input type="hidden" id="tapjkk1_blth_upah_terakhir" name="tapjkk1_blth_upah_terakhir" value="<?=$ld_tapjkk1_blth_upah_terakhir;?>">
												<input type="hidden" id="tapjkk1_kode_klaim" name="tapjkk1_kode_klaim" value="<?=$ls_kode_klaim;?>">
                        <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lovtapjkk1_upahterakhir.php?p=pn5041_penetapanjkk1.php&a=adminForm&b=tapjkk1_nom_upah_terakhir&c='+adminForm.kode_perusahaan.value+'&d='+adminForm.kode_segmen.value+'&e='+adminForm.kode_divisi.value+'&f='+adminForm.kode_tk.value+'&g='+adminForm.tgl_kejadian.value+'&h='+adminForm.tapjkk1_kode_klaim.value+'&j=tapjkk1_blth_upah_terakhir','',900,580,1)">
                        <img src="../../images/help.png" alt="Cari Faskes" border="0" align="absmiddle"></a>		
                      </div>																																											
                      <div class="clear"></div>
                      
                      <?
                      if ($ls_kode_segmen=="JAKON")
                      {
                        ?>
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">&nbsp;</label>		 	    				
                        <!--<i><font color="#009999">(* Upah harian sudah dikali dg jumlah hari</font></i>-->
												<i><font color="#009999">(* Utk Jakon Upah Bulanan = Upah Harian x 25 hari</font></i>		
                        </div>																																											
                        <div class="clear"></div>		
                        <?
                      }
                      ?>
                      
                      <?
                      echo "<script type=\"text/javascript\">fl_js_jkk1_set_field_upahtk('$ls_kode_segmen');</script>";
                      ?>									  
                    </fieldset>
                  </span>											
								</td>								
							</tr>		 
						</table>
									
					</td>							
				</tr>
			
				<tr>		
          <!-- Diagnosa ------------------------------------------------------->	
          <td valign="top" colspan="2">

						<script language="javascript">
              function bkwindow(mypage,myname,w,h,scroll){
                var winl = (screen.width-w)/2;
                var wint = (screen.height-h)/2;
                var settings  ='height='+h+',';
                settings +='width='+w+',';
                settings +='top='+wint+',';
                settings +='left='+winl+',';
                settings +='scrollbars='+scroll+',';
                settings +='resizable=1';
                win=window.open(mypage,myname,settings);
                if(parseFloat(navigator.appVersion) >= 4){win.window.focus();}
              }
              
              function fl_js_lov_diagnosa(i)
              {		 	
              	bkwindow("../ajax/pn5041_lovtapjkk1_diagnosa.php?p=pn5041.php&a=adminForm&b=tapjkk1_dtldiag_kode_diagnosa_detil"+i+"&c=tapjkk1_dtldiag_nama_diagnosa_detil"+i+"","",1000,500,1);		 
              }	
              
              function addRowInnerHTML2(tblId)
              {
                var form = document.adminForm;
                var tblBody = document.getElementById('tblrincian2').tBodies[0];
                var lastRow = tblBody.rows.length;
                var iteration = lastRow;
                
                var iteration = parseFloat(window.document.getElementById('tapjkk1_dtldiag_kounter_dtl').value);
                
                //create cell baru
                document.getElementById('tapjkk1_dtldiag_kounter_dtl').value = iteration+1;
                var newRow = tblBody.insertRow(-1);
                var newCell0 = newRow.insertCell(0);
                var newCell1 = newRow.insertCell(1);
                var newCell2 = newRow.insertCell(2);
                var newCell3 = newRow.insertCell(3);		
                
                newCell0.innerHTML = '<input type="text" id='+'tapjkk1_dtldiag_nama_tenaga_medis'+iteration+' name='+'tapjkk1_dtldiag_nama_tenaga_medis'+iteration+' size="25" maxlength="100" style="border-width: 1;text-align:left">';
                newCell1.innerHTML = '<input type="text" readonly id='+'tapjkk1_dtldiag_kode_diagnosa_detil'+iteration+' name='+'tapjkk1_dtldiag_kode_diagnosa_detil'+iteration+' size="5" style="border-width: 1;text-align:left;" maxlength="10">&nbsp;<input type="text" id='+'tapjkk1_dtldiag_nama_diagnosa_detil'+iteration+' name='+'tapjkk1_dtldiag_nama_diagnosa_detil'+iteration+' readonly class=\'disabled\' size="55"><a href="#" onclick="fl_js_lov_diagnosa('+iteration+');"><img src="../../images/help.png" alt="Cari Data Diagnosa" border="0" align="absmiddle"></a>';
                newCell2.innerHTML = '<input type="text" id='+'tapjkk1_dtldiag_keterangan'+iteration+' name='+'tapjkk1_dtldiag_keterangan'+iteration+' size="15" maxlength="300" style="border-width: 1;text-align:left">'; 
                newCell3.innerHTML = '<a href="#" onclick="removeperbrs2(this.parentNode.parentNode.rowIndex)" id="link_delete"><img src="../../images/file_cancel.gif" border="0" align="absmiddle"> Delete</a>';		
              }
              
              function deleteRow2(i)
              {
                alert(i);
                document.getElementById('tblrincian2').deleteRow(i);
              }
              
              function removeperbrs2(obj)
              {
                if (confirm("Anda Yakin untuk menghapus baris ini?")) 
                { 
                	document.getElementById('tblrincian2').deleteRow(obj);
                }else
                {
                 	//alert('nda yakin bah');
                }
              }				
            </script>
            
            <fieldset style="width:1050px;"><legend><b><i><font color="#009999">Diagnosa</font></i></b></legend>						 
              <table id="tblrincian2" width="70%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
                <tbody>
                  <tr>
                  	<th colspan="4"><hr /></th>	
                  </tr>						
                  <tr>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Tenaga Medis</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Detil Diagnosa</th>
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Keterangan</th>						
                    <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>							
                  </tr>
                  <tr>
                  	<th colspan="4"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                  </tr>
                  <?
                  if ($ln_dtl=="")
                  {
                   	$ln_dtl = "0";
                  }		
                  if ($ls_kode_klaim!="")
                  {
                    $sql = "select a.kode_klaim, a.no_urut, a.nama_tenaga_medis, a.kode_diagnosa_detil, ".
                           "   (select nama_diagnosa_detil from sijstk.pn_kode_diagnosa_detil where kode_diagnosa_detil = a.kode_diagnosa_detil) nama_diagnosa_detil, ".
                           "   a.keterangan ". 
                           "from sijstk.pn_klaim_diagnosa a ".
                           "where a.kode_klaim = '$ls_kode_klaim' ".
                           "order by a.no_urut";
                    $DB->parse($sql);
                    $DB->execute();							              					
                    $i=0;		
                    $ln_dtl =0;										
                    $ln_tot_mutasi = 0;
                    while ($row = $DB->nextrow())
                    {
                      ?>		
                      <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
                      <td>
                      	<input type="text" id="tapjkk1_dtldiag_nama_tenaga_medis<?=$i;?>" name="tapjkk1_dtldiag_nama_tenaga_medis<?=$i;?>" size="25" maxlength="100" value="<?=$row['NAMA_TENAGA_MEDIS'];?>" style="border-width: 1;text-align:left"> 
                      </td>					
                      <td>
                        <input type="text" id="tapjkk1_dtldiag_kode_diagnosa_detil<?=$i;?>" name="tapjkk1_dtldiag_kode_diagnosa_detil<?=$i;?>" size="5" style="border-width: 1;text-align:left;" maxlength="10" value="<?=$row['KODE_DIAGNOSA_DETIL'];?>" readonly>
                        <input type="text" id="tapjkk1_dtldiag_nama_diagnosa_detil<?=$i;?>" name="tapjkk1_dtldiag_nama_diagnosa_detil<?=$i;?>" value="<?=$row['NAMA_DIAGNOSA_DETIL'];?>" size="55" readonly class="disabled">
                        <a href="#" onclick="fl_js_lov_diagnosa(<?=$i;?>);"><img src="../../images/help.png" alt="Cari Data Diagnosa" border="0" align="absmiddle"></a>            			 
                      </td>
                      <td>
                      	<input type="text" id="tapjkk1_dtldiag_keterangan<?=$i;?>" name="tapjkk1_dtldiag_keterangan<?=$i;?>" size="15" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left">
                      </td> 										       																			        											
                      <td style="text-align:center;">
                      	<a href="#" onclick="removeperbrs2(this.parentNode.parentNode.rowIndex);" id="link_delete"><img src="../../images/file_cancel.gif" border="0" align="absmiddle"> Delete </a>
                      </td>									
                      </tr>
                      <?				    							
                      $i++;//iterasi i
                    }	//end while
                    $ln_dtl=$i;
                  }
                  ?>																																				 
                </tbody>
                <tr>
                  <td colspan="3"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>	
                  <td style="text-align:right" colspan="1">
                    <input type="hidden" id="tapjkk1_dtldiag_kounter_dtl" name="tapjkk1_dtldiag_kounter_dtl" value="<?=$ln_dtl;?>">
                    <input type="hidden" id="tapjkk1_dtldiag_count_dtl" name="tapjkk1_dtldiag_count_dtl" value="<?=$ln_countdtl;?>">
                    <input type="hidden" name="tapjkk1_dtldiag_showmessage" style="border-width: 0;text-align:right" readonly size="30">
                    <a href="#" onclick="addRowInnerHTML2('tblrincian2');"><img src="../../images/plus.png" border="0" align="absmiddle"> Entry</a>							
                  </td>									              
                </tr>
      					<tr>
      						<td>
      								<font color="#ff0000"><i>Disarankan untuk diisi..</i></font>
      						</td>	
      					</tr>												 							
              </table>  																						  
            </fieldset>					
					</td>									 
				</tr>				
			</table>		
      									
		</div>	 
  </div>
</div>

<div style="display: none;" id="tab3" class="tab_konten">
  <div id="konten">
        <script language="javascript">
          function fl_js_lov_nama_aktivitas_pelaporan(i)
          {		 	
          	bkwindow("../ajax/pn5041_lovtapjkk1_aktivitaslap.php?p=pn5041.php&a=adminForm&b=tapjkk1_dtlaktvpelap_nama_aktivitas"+i+"","",800,450,1);		 
          }
          
          function fl_js_lov_status_aktivitas_pelaporan(i)
          {		 	
          	bkwindow("../ajax/pn5001_lov_statusaktivlap.php?p=pn5041.php&a=adminForm&b=tapjkk1_dtlaktvpelap_status"+i+"","",800,450,1);		 
          }
  				
          function addRowInnerHTML3(tblId)
          {
            var form = document.formreg;
            var tblBody = document.getElementById('tblrincian3').tBodies[0];
            var lastRow = tblBody.rows.length;
            var iteration = lastRow;
            
            var iteration = parseFloat(window.document.getElementById('tapjkk1_dtlaktvpelap_kounter_dtl').value);
            
            //create cell baru
            document.getElementById('tapjkk1_dtlaktvpelap_kounter_dtl').value = iteration+1;
            var newRow = tblBody.insertRow(-1);
            var newCell0 = newRow.insertCell(0);
            var newCell1 = newRow.insertCell(1);
            var newCell2 = newRow.insertCell(2);
            var newCell3 = newRow.insertCell(3);		
            var newCell4 = newRow.insertCell(4);
            var newCell5 = newRow.insertCell(5);
            var newCell6 = newRow.insertCell(6);
            var newCell7 = newRow.insertCell(7);
            
            newCell0.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_tgl_aktivitas'+iteration+' name='+'tapjkk1_dtlaktvpelap_tgl_aktivitas'+iteration+' size="10" maxlength="10" style="border-width: 1;text-align:left" onblur="convert_date(tapjkk1_dtlaktvpelap_tgl_aktivitas'+iteration+');">&nbsp;<input id='+'btn_tapjkk1_dtlaktvpelap_tgl_aktivitas'+iteration+' type="image" align="top" onclick="return showCalendar(\'tapjkk1_dtlaktvpelap_tgl_aktivitas'+iteration+'\', \'dd-mm-y\');" src="../../images/calendar.gif" />';
            newCell1.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_nama_aktivitas'+iteration+' name='+'tapjkk1_dtlaktvpelap_nama_aktivitas'+iteration+' size="40" readonly class=\'disabled\' maxlength="100"><a href="#" onclick="fl_js_lov_nama_aktivitas_pelaporan('+iteration+');"><img src="../../images/help.png" alt="Cari Data Nama Aktivitas Pelaporan" border="0" align="absmiddle"></a>';
            newCell2.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_nama_sumber'+iteration+' name='+'tapjkk1_dtlaktvpelap_nama_sumber'+iteration+' size="15" maxlength="100" style="border-width: 1;text-align:left">';
            newCell3.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_profesi_sumber'+iteration+' name='+'tapjkk1_dtlaktvpelap_profesi_sumber'+iteration+' size="15" maxlength="100" style="border-width: 1;text-align:left">';
            newCell4.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_alamat'+iteration+' name='+'tapjkk1_dtlaktvpelap_alamat'+iteration+' size="20" maxlength="100" style="border-width: 1;text-align:left">';
            newCell5.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_telepon_area'+iteration+' name='+'tapjkk1_dtlaktvpelap_telepon_area'+iteration+' size="2" maxlength="5" style="border-width: 1;text-align:left" onblur="fl_js_val_numeric(\'tapjkk1_dtlaktvpelap_telepon_area'+iteration+'\');">&nbsp;<input type="text" id='+'tapjkk1_dtlaktvpelap_telepon'+iteration+' name='+'tapjkk1_dtlaktvpelap_telepon'+iteration+' size="12" maxlength="20" style="border-width: 1;text-align:left" onblur="fl_js_val_numeric(\'tapjkk1_dtlaktvpelap_telepon'+iteration+'\');">';
            newCell6.innerHTML = '<input type="text" id='+'tapjkk1_dtlaktvpelap_keterangan'+iteration+' name='+'tapjkk1_dtlaktvpelap_keterangan'+iteration+' size="6" maxlength="300" style="border-width: 1;text-align:left">';		
            newCell7.innerHTML = '<a href="#" onclick="removeperbrs3(this.parentNode.parentNode.rowIndex)" id="link_delete"><img src="../../images/file_cancel.gif" border="0" align="absmiddle"> Del</a>';		
          }
          
          function deleteRow3(i)
          {
            alert(i);
            document.getElementById('tblrincian3').deleteRow(i);
          }
          
          function removeperbrs3(obj)
          {
            if (confirm("Anda Yakin untuk menghapus baris ini?")) 
            { 
            	document.getElementById('tblrincian3').deleteRow(obj);
            }else
            {
             	//alert('nda yakin bah');
            }
          }							
        </script>
				
				<div id="formKiri" style="width:1100px;">					
          <fieldset><legend><b><i><font color="#009999">Aktivitas Pelaporan</font></i></b></legend>						 
        		<table id="tblrincian3" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <tbody>
						<tr>
							<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
						</tr>						
            <tr>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Aktivitas</th>
							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Narasumber</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Profesi</th>
							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Alamat</th>
							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No Telp</th>
							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ket</th>						
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>																					
            </tr>
						<tr>
							<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
						</tr>
              <?
        			if ($ln_dtl=="")
        			{
        			 $ln_dtl = "0";
        			}		
              if ($ls_kode_klaim!="")
              {
                $sql2 = "select ".
                       "     a.kode_klaim, a.no_urut, to_char(a.tgl_aktivitas,'dd/mm/yyyy') tgl_aktivitas,  ". 
                       "     a.nama_aktivitas, a.nama_sumber, a.profesi_sumber, a.alamat, a.rt, a.rw, ". 
                       "     a.kode_kelurahan, a.kode_kecamatan, a.kode_kabupaten, a.kode_pos, ". 
                       "     a.telepon_area, a.telepon, a.telepon_ext, a.handphone, a.email, ". 
                       "     a.npwp, a.status, a.hasil_tindak_lanjut, a.keterangan ".
                       "from sijstk.pn_klaim_aktivitas_pelaporan a ".
                       "where a.kode_klaim = '$ls_kode_klaim' ".
                       "order by a.no_urut";
                $DB->parse($sql2);
                $DB->execute();							              					
                $i=0;		
                $ln_dtl =0;										
                $ln_tot_mutasi = 0;
                while ($row = $DB->nextrow())
                {
                  ?>																				
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
                    <td>
            					<input type="text" readonly id="tapjkk1_dtlaktvpelap_tgl_aktivitas<?=$i;?>" name="tapjkk1_dtlaktvpelap_tgl_aktivitas<?=$i;?>" size="10" maxlength="10" value="<?=$row['TGL_AKTIVITAS'];?>" style="border-width: 1;text-align:left"> 
                    </td>					
                    <td>
                      <input type="text" id="tapjkk1_dtlaktvpelap_nama_aktivitas<?=$i;?>" name="tapjkk1_dtlaktvpelap_nama_aktivitas<?=$i;?>" value="<?=$row['NAMA_AKTIVITAS'];?>" size="40" readonly class="disabled" maxlength="100">            			 
                    </td>
                    <td>
                      <input type="text" readonly id="tapjkk1_dtlaktvpelap_nama_sumber<?=$i;?>" name="tapjkk1_dtlaktvpelap_nama_sumber<?=$i;?>" size="15" maxlength="100" value="<?=$row['NAMA_SUMBER'];?>" style="border-width: 1;text-align:left">
                    </td>
                    <td>
                      <input type="text" readonly id="tapjkk1_dtlaktvpelap_profesi_sumber<?=$i;?>" name="tapjkk1_dtlaktvpelap_profesi_sumber<?=$i;?>" size="15" maxlength="100" value="<?=$row['PROFESI_SUMBER'];?>" style="border-width: 1;text-align:left">
                    </td>
                    <td>
                      <input type="text" readonly id="tapjkk1_dtlaktvpelap_alamat<?=$i;?>" name="tapjkk1_dtlaktvpelap_alamat<?=$i;?>" size="20" maxlength="100" value="<?=$row['ALAMAT'];?>" style="border-width: 1;text-align:left">
                    </td>	
                    <td>
                      <input type="text" readonly id="tapjkk1_dtlaktvpelap_telepon_area<?=$i;?>" name="tapjkk1_dtlaktvpelap_telepon_area<?=$i;?>" size="2" maxlength="5" value="<?=$row['TELEPON_AREA'];?>" style="border-width: 1;text-align:left">
        							<input type="text" readonly id="tapjkk1_dtlaktvpelap_telepon<?=$i;?>" name="tapjkk1_dtlaktvpelap_telepon<?=$i;?>" size="12" maxlength="20" value="<?=$row['TELEPON'];?>" style="border-width: 1;text-align:left">        							
                    </td>
                    <td>
                      <input type="text" readonly id="tapjkk1_dtlaktvpelap_keterangan<?=$i;?>" name="tapjkk1_dtlaktvpelap_keterangan<?=$i;?>" size="6" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left">
                    </td>																																			 										       																			        											
                    <td>
											<a href="#" onclick="removeperbrs3(this.parentNode.parentNode.rowIndex);" id="link_delete"><img src="../../images/file_cancel.gif" border="0" align="absmiddle"> Del </a>	
										</td>									
                  </tr>
                  <?				    							
                  $i++;//iterasi i
                }	//end while
                $ln_dtl=$i;
              }
              ?>																																				 
              </tbody>
  						<tr>	
  							<th colspan="6">
										<hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/>
								</th>	  						
                <td colspan="1">
								  <input type="hidden" id="tapjkk1_dtlaktvpelap_kounter_dtl" name="tapjkk1_dtlaktvpelap_kounter_dtl" value="<?=$ln_dtl;?>">
                  <input type="hidden" id="tapjkk1_dtlaktvpelap_count_dtl" name="tapjkk1_dtlaktvpelap_count_dtl" value="<?=$ln_countdtl;?>">
                  <input type="hidden" name="tapjkk1_dtlaktvpelap_showmessage" style="border-width: 0;text-align:right" readonly size="30">								
								</td>
								<td style="text-align:center" colspan="1"><a href="#" onclick="addRowInnerHTML3('tblrincian3');"><img src="../../images/plus.png" border="0" align="absmiddle"> Entry</a></td>
              </tr>							 
            </table>  																						  
          </fieldset>																
				</div> <!-- end div id="formKiri" --> 
  </div>
</div>		