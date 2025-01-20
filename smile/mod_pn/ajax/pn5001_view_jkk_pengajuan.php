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
$ls_tapjkk1_kode_lama_bekerja 		= $ls_kode_lama_bekerja;
$ls_tapjkk1_kode_penyakit_timbul	= $ls_kode_penyakit_timbul;
$ls_tapjkk1_nama_penyakit_timbul	= $ls_nama_penyakit_timbul;		
?>

<script language="javascript">
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
		
		<div id="formKiri" style="width:1100px;">

			<table width="1050px" border="0">
				<tr>		 
          <!-- Informasi Pengajuan Klaim JKK Tahap I -------------------------->	
          <td width="55%" valign="top">
            <fieldset style="height:210px;"><legend><b><i><font color="#009999">Informasi Pengajuan Klaim JKK Tahap I &nbsp;<?=$ls_nama_tk;?></font></i></b></legend>
							</br></br>				
              <span id="span_kecelakaan_kerja" style="display:none;">						
                <div class="form-row_kiri">
                <label style = "text-align:right;">Tindakan Bahaya &nbsp;</label>		 	    				
                  <select size="1" id="tapjkk1_kode_tindakan_bahaya" name="tapjkk1_kode_tindakan_bahaya" value="<?=$ls_tapjkk1_kode_tindakan_bahaya;?>" tabindex="1" class="select_format" style="width:330px;background-color:#F5F5F5;" >
                  <option value="">-- pilih --</option>
                  <? 
									$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMTINDBHY' and kode = '$ls_tapjkk1_kode_tindakan_bahaya'";
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
                <label style = "text-align:right;">Kondisi Bahaya &nbsp;</label>		 	    				
                  <select size="1" id="tapjkk1_kode_kondisi_bahaya" name="tapjkk1_kode_kondisi_bahaya" value="<?=$ls_tapjkk1_kode_kondisi_bahaya;?>" tabindex="2" class="select_format" style="width:330px;background-color:#F5F5F5;" >
                  <option value="">-- pilih --</option>
                  <? 
                  $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMKONDBHY' and kode = '$ls_tapjkk1_kode_kondisi_bahaya'";
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
                <label style = "text-align:right;">Corak &nbsp;</label>
                  <input type="text" id="tapjkk1_kode_corak" name="tapjkk1_kode_corak" value="<?=$ls_tapjkk1_kode_corak;?>" tabindex="3" style="width:40px;" maxlength="10" readonly class="disabled">
                  <input type="text" id="tapjkk1_nama_corak" name="tapjkk1_nama_corak" value="<?=$ls_tapjkk1_nama_corak;?>" style="width:250px;" readonly class="disabled">
                  <img src="../../images/help.png" alt="LOV Corak" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Sumber Cedera &nbsp;</label>
                  <input type="text" id="tapjkk1_kode_sumber_cedera" name="tapjkk1_kode_sumber_cedera" value="<?=$ls_tapjkk1_kode_sumber_cedera;?>" tabindex="4" style="width:40px;" maxlength="10" readonly class="disabled">
                  <input type="text" id="tapjkk1_nama_sumber_cedera" name="tapjkk1_nama_sumber_cedera" value="<?=$ls_tapjkk1_nama_sumber_cedera;?>" style="width:250px;" readonly class="disabled">
                  <img src="../../images/help.png" alt="LOV Sumber Cedera" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Bagian yang Sakit &nbsp;</label>
                  <input type="text" id="tapjkk1_kode_bagian_sakit" name="tapjkk1_kode_bagian_sakit" value="<?=$ls_tapjkk1_kode_bagian_sakit;?>" tabindex="5" style="width:40px;" maxlength="10" readonly class="disabled">
                  <input type="text" id="tapjkk1_nama_bagian_sakit" name="tapjkk1_nama_bagian_sakit" value="<?=$ls_tapjkk1_nama_bagian_sakit;?>" style="width:250px;" readonly class="disabled">
                  <img src="../../images/help.png" alt="LOV Bagian yang Sakit" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Akibat yg Diderita *</label>
                  <input type="text" id="tapjkk1_kode_akibat_diderita" name="tapjkk1_kode_akibat_diderita" value="<?=$ls_tapjkk1_kode_akibat_diderita;?>" tabindex="6" style="width:40px;" maxlength="10" readonly  class="disabled">
                  <input type="text" id="tapjkk1_nama_akibat_diderita" name="tapjkk1_nama_akibat_diderita" value="<?=$ls_tapjkk1_nama_akibat_diderita;?>" style="width:210px;" readonly class="disabled">
                  <img src="../../images/help.png" alt="LOV Akibat yg Diderita" border="0" align="absmiddle"></a>													
                </div>																																										
                <div class="clear"></div>																																																	
              </span>	<!-- end span "span_kecelakaan_kerja" -->	
              
              <span id="span_penyakit_akibat_kerja" style="display:none;">
								</br></br>		
                <div class="form-row_kiri">
                <label style = "text-align:right;">Lama Bekerja (Thn) &nbsp;</label>		 	    				
                  <select size="1" id="tapjkk1_kode_lama_bekerja" name="tapjkk1_kode_lama_bekerja" value="<?=$ls_tapjkk1_kode_lama_bekerja;?>" tabindex="7" class="select_format" style="width:330px;background-color:#F5F5F5;" >
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
                  <input type="text" id="tapjkk1_kode_penyakit_timbul" name="tapjkk1_kode_penyakit_timbul" value="<?=$ls_tapjkk1_kode_penyakit_timbul;?>" tabindex="8" style="width:40px;" maxlength="10" readonly  class="disabled">
                  <input type="text" id="tapjkk1_nama_penyakit_timbul" name="tapjkk1_nama_penyakit_timbul" value="<?=$ls_tapjkk1_nama_penyakit_timbul;?>"style="width:234px;" readonly class="disabled">
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
                      <select size="1" id="tapjkk1_kode_tempat_perawatan" name="tapjkk1_kode_tempat_perawatan" value="<?=$ls_tapjkk1_kode_tempat_perawatan;?>" tabindex="9" class="select_format" style="width:230px;background-color:#F5F5F5;" >
                      <option value="">-- pilih --</option>
                      <? 
                      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMTMPTRWT' and kode = '$ls_tapjkk1_kode_tempat_perawatan' ";
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
                      <select size="1" id="tapjkk1_kode_berobat_jalan" name="tapjkk1_kode_berobat_jalan" value="<?=$ls_tapjkk1_kode_berobat_jalan;?>" tabindex="10" class="select_format" style="width:230px;background-color:#F5F5F5;" >
                      <option value="">-- pilih --</option>
                      <? 
											$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMBOBTJLN' and kode='$ls_tapjkk1_kode_berobat_jalan'";
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
                        <input type="text" id="tapjkk1_kode_ppk" name="tapjkk1_kode_ppk" value="<?=$ls_tapjkk1_kode_ppk;?>" tabindex="11" maxlength="20" readonly class="disabled" style="width:50px;" >
                        <input type="text" id="tapjkk1_nama_ppk" name="tapjkk1_nama_ppk" value="<?=$ls_tapjkk1_nama_ppk;?>" style="width:145px;" readonly class="disabled">
                        <img src="../../images/help.png" alt="Cari Faskes" border="0" align="absmiddle"></a>
                      </div>	
                      <div class="clear"></div>
                    </span>
                    
                    <span id="span_reimburse" style="display:none;">						
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Reimburse</label>		 	    				
                        <input type="text" id="tapjkk1_nama_faskes_reimburse" name="tapjkk1_nama_faskes_reimburse" value="<?=$ls_tapjkk1_nama_faskes_reimburse;?>" tabindex="12" maxlength="100" readonly class="disabled" style="width:215px;" placeholder="(* isikan Nama RS/Puskesmas/Poli/dll)">
                      </div>																																														
                      <div class="clear"></div>
                    </span>
                    
                    <script language="javascript">
                    function fl_js_tapjkk1_kode_tempat_perawatan() 
                    { 
											var v_tempat_perawatan = window.document.getElementById('tapjkk1_kode_tempat_perawatan').value;
                      
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
                  ?>
                  <script language="javascript">
                    function fl_js_jkk1_set_field_upahtk(v_kode_segmen) 
                    { 
                      if (v_kode_segmen =="TKI") //TKI tidak ada input upah --------------
                      {    
                        window.document.getElementById("tapjkk1_span_upah_tk").style.display = 'none';
                        window.document.getElementById("tapjkk1_kode_tipe_upah").value = "";
                        window.document.getElementById("tapjkk1_nom_upah_terakhir").value = "";								
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
                        <select size="1" id="tapjkk1_kode_tipe_upah" name="tapjkk1_kode_tipe_upah" value="<?=$ls_tapjkk1_kode_tipe_upah;?>" tabindex="45" class="select_format" style="width:230px;background-color:#F5F5F5;" >
                        <option value="">-- pilih --</option>
                        <? 
                        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TPUPA'||'$ls_kode_segmen' and kode='$ls_tapjkk1_kode_tipe_upah' ";
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
                        <img src="../../images/help.png" alt="Cari Faskes" border="0" align="absmiddle"></a>		
                      </div>																																											
                      <div class="clear"></div>
                      
                      <?
                      if ($ls_kode_segmen=="JAKON")
                      {
                        ?>
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">&nbsp;</label>		 	    				
                        <i><font color="#009999">(* Upah harian sudah dikali dg jumlah hari</font></i>		
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
                      	<input type="text" id="tapjkk1_dtldiag_nama_tenaga_medis<?=$i;?>" name="tapjkk1_dtldiag_nama_tenaga_medis<?=$i;?>" size="25" maxlength="100" value="<?=$row['NAMA_TENAGA_MEDIS'];?>" style="border-width: 1;text-align:left" readonly class="disabled"> 
                      </td>					
                      <td>
                        <input type="text" id="tapjkk1_dtldiag_kode_diagnosa_detil<?=$i;?>" name="tapjkk1_dtldiag_kode_diagnosa_detil<?=$i;?>" size="5" style="border-width: 1;text-align:left;" maxlength="10" value="<?=$row['KODE_DIAGNOSA_DETIL'];?>" readonly class="disabled">
                        <input type="text" id="tapjkk1_dtldiag_nama_diagnosa_detil<?=$i;?>" name="tapjkk1_dtldiag_nama_diagnosa_detil<?=$i;?>" value="<?=$row['NAMA_DIAGNOSA_DETIL'];?>" size="55" readonly class="disabled">
                        <img src="../../images/help.png" alt="Cari Data Diagnosa" border="0" align="absmiddle"></a>            			 
                      </td>
                      <td>
                      	<input type="text" id="tapjkk1_dtldiag_keterangan<?=$i;?>" name="tapjkk1_dtldiag_keterangan<?=$i;?>" size="15" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left" readonly class="disabled">
                      </td> 										       																			        											
                      <td style="text-align:center;">
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
                  </td>									              
                </tr>											 							
              </table>  																						  
            </fieldset>					
					</td>									 
				</tr>	
				
				<tr>		
          <!-- Diagnosa ------------------------------------------------------->	
          <td valign="top" colspan="2">		
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
              					<input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_tgl_aktivitas<?=$i;?>" name="tapjkk1_dtlaktvpelap_tgl_aktivitas<?=$i;?>" size="10" maxlength="10" value="<?=$row['TGL_AKTIVITAS'];?>" style="border-width: 1;text-align:left"> 
                      </td>					
                      <td>
                        <input type="text" id="tapjkk1_dtlaktvpelap_nama_aktivitas<?=$i;?>" name="tapjkk1_dtlaktvpelap_nama_aktivitas<?=$i;?>" value="<?=$row['NAMA_AKTIVITAS'];?>" size="40" readonly class="disabled" maxlength="100">            			 
                      </td>
                      <td>
                        <input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_nama_sumber<?=$i;?>" name="tapjkk1_dtlaktvpelap_nama_sumber<?=$i;?>" size="15" maxlength="100" value="<?=$row['NAMA_SUMBER'];?>" style="border-width: 1;text-align:left">
                      </td>
                      <td>
                        <input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_profesi_sumber<?=$i;?>" name="tapjkk1_dtlaktvpelap_profesi_sumber<?=$i;?>" size="15" maxlength="100" value="<?=$row['PROFESI_SUMBER'];?>" style="border-width: 1;text-align:left">
                      </td>
                      <td>
                        <input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_alamat<?=$i;?>" name="tapjkk1_dtlaktvpelap_alamat<?=$i;?>" size="20" maxlength="100" value="<?=$row['ALAMAT'];?>" style="border-width: 1;text-align:left">
                      </td>	
                      <td>
                        <input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_telepon_area<?=$i;?>" name="tapjkk1_dtlaktvpelap_telepon_area<?=$i;?>" size="2" maxlength="5" value="<?=$row['TELEPON_AREA'];?>" style="border-width: 1;text-align:left">
          							<input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_telepon<?=$i;?>" name="tapjkk1_dtlaktvpelap_telepon<?=$i;?>" size="12" maxlength="20" value="<?=$row['TELEPON'];?>" style="border-width: 1;text-align:left">        							
                      </td>
                      <td>
                        <input type="text" readonly class="disabled" id="tapjkk1_dtlaktvpelap_keterangan<?=$i;?>" name="tapjkk1_dtlaktvpelap_keterangan<?=$i;?>" size="6" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left">
                      </td>																																			 										       																			        											
                      <td>
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
  								<td style="text-align:center" colspan="1"></td>
                </tr>							 
              </table>  																						  
            </fieldset>											
					</td>									 
				</tr>	
														
			</table>		
      									
		</div>	 	