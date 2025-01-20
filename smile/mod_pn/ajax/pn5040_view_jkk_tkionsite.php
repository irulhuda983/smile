<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input jkk Klaim JKK TKI Onsite
      (tanpa melalui pengajuan dan jkk tahap 2, langsung ke penetapan)
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$ld_jkk_tgl_kecelakaan					 = $ld_tgl_kecelakaan;
$ls_jkk_kode_jam_kecelakaan		 	 = $ls_kode_jam_kecelakaan;
$ls_jkk_kode_jenis_kasus				 = $ls_kode_jenis_kasus;
$ls_jkk_kode_lokasi_kecelakaan   = $ls_kode_lokasi_kecelakaan;  										
$ls_jkk_nama_tempat_kecelakaan	 = $ls_nama_tempat_kecelakaan;
$ls_jkk_ket_tambahan						 = $ls_ket_tambahan;

$ls_jkk_kode_tindakan_bahaya 		 = $ls_kode_tindakan_bahaya;
$ls_jkk_kode_kondisi_bahaya	 		 = $ls_kode_kondisi_bahaya;
$ls_jkk_kode_corak					 		 = $ls_kode_corak;
$ls_jkk_nama_corak					 		 = $ls_nama_corak;
$ls_jkk_kode_sumber_cedera	 		 = $ls_kode_sumber_cedera;
$ls_jkk_nama_sumber_cedera	 		 = $ls_nama_sumber_cedera;
$ls_jkk_kode_bagian_sakit		 		 = $ls_kode_bagian_sakit;
$ls_jkk_nama_bagian_sakit		 		 = $ls_nama_bagian_sakit;
$ls_jkk_kode_akibat_diderita 		 = $ls_kode_akibat_diderita;
$ls_jkk_nama_akibat_diderita 		 = $ls_nama_akibat_diderita;		
$ls_jkk_kode_lama_bekerja 			 = $ls_kode_lama_bekerja;
$ls_jkk_kode_penyakit_timbul		 = $ls_kode_penyakit_timbul;

$ls_jkk_kode_tempat_perawatan 	 = $ls_kode_tempat_perawatan;
$ls_jkk_kode_berobat_jalan			 = $ls_kode_berobat_jalan;
$ls_jkk_kode_ppk								 = $ls_kode_ppk;
$ls_jkk_nama_ppk								 = $ls_nama_ppk;
$ls_jkk_nama_faskes_reimburse		 = $ls_nama_faskes_reimburse;

$ls_jkk_kode_tipe_upah 					 = $ls_kode_tipe_upah;
$ln_jkk_nom_upah_terakhir 			 = $ln_nom_upah_terakhir;

$ls_jkk_kode_kondisi_terakhir 	 = $ls_kode_kondisi_terakhir;
$ld_jkk_tgl_kondisi_terakhir  	 = $ld_tgl_kondisi_terakhir;

if ($ld_jkk_tgl_kondisi_terakhir=="")
{
  $sql = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ld_jkk_tgl_kondisi_terakhir = $row["TGL"];	 	 
} 					
?>

<script language="javascript">
function fl_js_jkk_kode_jenis_kasus() 
{ 
	var	v_kode_jenis_kasus = window.document.getElementById('jkk_kode_jenis_kasus').value;
	
	if (v_kode_jenis_kasus =="KS01") //kecelakaan kerja --------------------------
  {    
		window.document.getElementById("jkk_span_lokasi_kecelakaan").style.display = 'block';
		window.document.getElementById("jkk_span_kecelakaan_kerja").style.display = 'block';	
		window.document.getElementById("jkk_span_penyakit_akibat_kerja").style.display = 'none';
		window.document.getElementById("jkk_kode_lama_bekerja").value = "";
 		window.document.getElementById("jkk_kode_penyakit_timbul").value = "";
		window.document.getElementById("jkk_nama_penyakit_timbul").value = "";		
  }else if (v_kode_jenis_kasus =="KS02") //penyakit akibat kerja ---------------
  {
	  window.document.getElementById("jkk_span_lokasi_kecelakaan").style.display = 'none';
    window.document.getElementById("jkk_kode_lokasi_kecelakaan").value = "";
		window.document.getElementById("jkk_span_kecelakaan_kerja").style.display = 'none';	
		window.document.getElementById("jkk_span_penyakit_akibat_kerja").style.display = 'block';
    window.document.getElementById("jkk_kode_tindakan_bahaya").value = "";
		window.document.getElementById("jkk_kode_kondisi_bahaya").value = "";
		window.document.getElementById("jkk_kode_corak").value = "";
		window.document.getElementById("jkk_kode_sumber_cedera").value = "";
		window.document.getElementById("jkk_kode_bagian_sakit").value = "";
		window.document.getElementById("jkk_kode_akibat_diderita").value = "";
		window.document.getElementById("jkk_nama_corak").value = "";
		window.document.getElementById("jkk_nama_sumber_cedera").value = "";
		window.document.getElementById("jkk_nama_bagian_sakit").value = "";
		window.document.getElementById("jkk_nama_akibat_diderita").value = "";							 
  } 	
}

function fl_js_jkk_kode_tempat_perawatan() 
{ 
	var	v_tempat_perawatan = window.document.getElementById('jkk_kode_tempat_perawatan').value;
	
  if (v_tempat_perawatan =="TR01") //faskes/trauma center
  {
    window.document.getElementById("jkk_span_faskes").style.display = 'block';
    window.document.getElementById("jkk_span_reimburse").style.display = 'none';
    window.document.getElementById("jkk_nama_faskes_reimburse").value = "";
  }else
  {
    window.document.getElementById("jkk_span_reimburse").style.display = 'block';
    window.document.getElementById("jkk_span_faskes").style.display = 'none';            			 
    window.document.getElementById("jkk_kode_ppk").value = "";	
    window.document.getElementById("jkk_nama_ppk").value = "";	 									 		 
  } 	
}	

function fl_js_jkk_set_field_upahtk() 
{ 
  var	v_kode_segmen = window.document.getElementById('kode_segmen').value;
	if (v_kode_segmen =="TKI") //TKI tidak ada input upah --------------
  {    
    window.document.getElementById("jkk_span_upah_tk").style.display = 'none';
    window.document.getElementById("jkk_kode_tipe_upah").value = "";
    window.document.getElementById("jkk_nom_upah_terakhir").value = "";								
  }else // selain TKI ----------------
  {
   	window.document.getElementById("jkk_span_upah_tk").style.display = 'block';
  } 	
}	

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

function fl_js_jkk_lov_diagnosa(i)
{		 	
	bkwindow("../ajax/pn5041_lovtapjkk1_diagnosa.php?p=pn5040_tabdataklaim_jkktkionsite.php&a=formreg&b=jkk_dtldiag_kode_diagnosa_detil"+i+"&c=jkk_dtldiag_nama_diagnosa_detil"+i+"","",800,500,1);		 
}	

function addRowInnerHTML2(tblId)
{
  var form = document.formreg;
  var tblBody = document.getElementById('tblrincian2').tBodies[0];
  var lastRow = tblBody.rows.length;
  var iteration = lastRow;
  
  var iteration = parseFloat(window.document.getElementById('jkk_dtldiag_kounter_dtl').value);
  
  //create cell baru
  document.getElementById('jkk_dtldiag_kounter_dtl').value = iteration+1;
  var newRow = tblBody.insertRow(-1);
  var newCell0 = newRow.insertCell(0);
  var newCell1 = newRow.insertCell(1);
  var newCell2 = newRow.insertCell(2);
  var newCell3 = newRow.insertCell(3);		
  
  newCell0.innerHTML = '<input type="text" id='+'jkk_dtldiag_nama_tenaga_medis'+iteration+' name='+'jkk_dtldiag_nama_tenaga_medis'+iteration+' size="15" maxlength="100" style="border-width: 1;text-align:left">';
  newCell1.innerHTML = '<input type="text" readonly id='+'jkk_dtldiag_kode_diagnosa_detil'+iteration+' name='+'jkk_dtldiag_kode_diagnosa_detil'+iteration+' size="5" style="border-width: 1;text-align:left;" maxlength="10">&nbsp;<input type="text" id='+'jkk_dtldiag_nama_diagnosa_detil'+iteration+' name='+'jkk_dtldiag_nama_diagnosa_detil'+iteration+' readonly class=\'disabled\' size="30"><a href="#" onclick="fl_js_jkk_lov_diagnosa('+iteration+');"><img src="../../images/help.png" alt="Cari Data Diagnosa" border="0" align="absmiddle"></a>';
  newCell2.innerHTML = '<input type="text" id='+'jkk_dtldiag_keterangan'+iteration+' name='+'jkk_dtldiag_keterangan'+iteration+' size="15" maxlength="300" style="border-width: 1;text-align:left">'; 
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

function fl_js_lov_jkk_nama_aktivitas_pelaporan(i)
{		 	
	bkwindow("../ajax/pn5041_lovtapjkk1_aktivitaslap.php?p=pn5040_tabdataklaim_jkktkionsite.php&a=formreg&b=jkk_dtlaktvpelap_nama_aktivitas"+i+"","",800,450,1);		 
}

function fl_js_lov_status_aktivitas_pelaporan(i)
{		 	
	bkwindow("../ajax/pn5040_lov_statusaktivlap.php?p=pn5040_tabdataklaim_jkktkionsite.php&a=formreg&b=jkk_dtlaktvpelap_status"+i+"","",800,450,1);		 
}
function addRowInnerHTML3(tblId)
{
  var form = document.formreg;
  var tblBody = document.getElementById('tblrincian3').tBodies[0];
  var lastRow = tblBody.rows.length;
  var iteration = lastRow;
  
  var iteration = parseFloat(window.document.getElementById('jkk_dtlaktvpelap_kounter_dtl').value);
  
  //create cell baru
  document.getElementById('jkk_dtlaktvpelap_kounter_dtl').value = iteration+1;
  var newRow = tblBody.insertRow(-1);
  var newCell0 = newRow.insertCell(0);
  var newCell1 = newRow.insertCell(1);
  var newCell2 = newRow.insertCell(2);
  var newCell3 = newRow.insertCell(3);		
  var newCell4 = newRow.insertCell(4);
  var newCell5 = newRow.insertCell(5);
  var newCell6 = newRow.insertCell(6);
  var newCell7 = newRow.insertCell(7);
  
  newCell0.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_tgl_aktivitas'+iteration+' name='+'jkk_dtlaktvpelap_tgl_aktivitas'+iteration+' size="9" maxlength="10" style="border-width: 1;text-align:left">&nbsp;<input id='+'btn_jkk_dtlaktvpelap_tgl_aktivitas'+iteration+' type="image" align="top" onclick="return showCalendar(\'jkk_dtlaktvpelap_tgl_aktivitas'+iteration+'\', \'dd-mm-y\');" src="../../images/calendar.gif" />';
  newCell1.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_nama_aktivitas'+iteration+' name='+'jkk_dtlaktvpelap_nama_aktivitas'+iteration+' size="15" readonly class=\'disabled\' maxlength="100"><a href="#" onclick="fl_js_lov_jkk_nama_aktivitas_pelaporan('+iteration+');"><img src="../../images/help.png" alt="Cari Data Nama Aktivitas Pelaporan" border="0" align="absmiddle"></a>';
  newCell2.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_nama_sumber'+iteration+' name='+'jkk_dtlaktvpelap_nama_sumber'+iteration+' size="12" maxlength="100" style="border-width: 1;text-align:left">';
  newCell3.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_profesi_sumber'+iteration+' name='+'jkk_dtlaktvpelap_profesi_sumber'+iteration+' size="10" maxlength="100" style="border-width: 1;text-align:left">';
  newCell4.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_alamat'+iteration+' name='+'jkk_dtlaktvpelap_alamat'+iteration+' size="12" maxlength="100" style="border-width: 1;text-align:left">';
  newCell5.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_telepon_area'+iteration+' name='+'jkk_dtlaktvpelap_telepon_area'+iteration+' size="2" maxlength="5" style="border-width: 1;text-align:left">&nbsp;<input type="text" id='+'jkk_dtlaktvpelap_telepon'+iteration+' name='+'jkk_dtlaktvpelap_telepon'+iteration+' size="9" maxlength="20" style="border-width: 1;text-align:left">';
  newCell6.innerHTML = '<input type="text" id='+'jkk_dtlaktvpelap_keterangan'+iteration+' name='+'jkk_dtlaktvpelap_keterangan'+iteration+' size="6" maxlength="300" style="border-width: 1;text-align:left">';		
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
						
<div id="formKiri" style="width:870px;">
  <fieldset><legend>Informasi Kecelakaan</legend>
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Kecelakaan *</label>
      <input type="text" id="jkk_tgl_kecelakaan" name="jkk_tgl_kecelakaan" value="<?=$ld_jkk_tgl_kecelakaan;?>" tabindex="31" size="37" maxlength="10" onblur="convert_date(jkk_tgl_kecelakaan);" readonly class="disabled">      	   							
    </div>	
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jam Kecelakaan &nbsp; *</label>		 	    				
      <select size="1" id="jkk_kode_jam_kecelakaan" name="jkk_kode_jam_kecelakaan" value="<?=$ls_jkk_kode_jam_kecelakaan;?>" tabindex="32" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
        $param_bv = [];
  			if ($ls_status_submit_agenda =="Y")
  			{
          $param_bv[':p_tipe'] = 'KLMJAMKERJ';
          $param_bv[':p_kode'] = $ls_jkk_kode_jam_kecelakaan;
					$sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and kode=:p_kode order by seq";
        }else
  			{
          $param_bv[':p_tipe'] = 'KLMJAMKERJ';
          $param_bv[':p_status'] = 'Y';
          $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and nvl(aktif,'T')=:p_status order by seq";										
  			}        				
        $proc = $DB->parse($sql);
        foreach ($param_bv as $key => $value) {
         oci_bind_by_name($proc, $key, $param_bv[$key]);
        }       
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE"]==$ls_jkk_kode_jam_kecelakaan && strlen($ls_jkk_kode_jam_kecelakaan)==strlen($row["KODE"])){ echo " selected"; }
          echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
      ?>
      </select>		
    </div>																																												
  	<div class="clear"></div>		

    <div class="form-row_kiri">
    <label style = "text-align:right;">Jenis Kasus &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; *</label>		 	    				
      <select size="1" id="jkk_kode_jenis_kasus" name="jkk_kode_jenis_kasus" value="<?=$ls_jkk_kode_jenis_kasus;?>" tabindex="33" class="select_format" onchange="fl_js_jkk_kode_jenis_kasus();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:260px;background-color:#F5F5F5\"" : " style=\"width:260px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <?
        $param_bv = [];
  			if ($ls_status_submit_agenda =="Y")
  			{
          $param_bv[':p_kode_jenis_kasus'] = $ls_jkk_kode_jenis_kasus;
					$sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus=:p_kode_jenis_kasus";
        }else
  			{
				 	if ($ls_kode_segmen == "TKI")
					{
            $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
            $param_bv[':p_status'] = 'T';
            $param_bv[':p_jenis_kasus'] = 'KS02';
            $sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_tipe_klaim = :p_kode_tipe_klaim and nvl(status_nonaktif,'T')=:p_status and kode_jenis_kasus <> :p_jenis_kasus order by no_urut";	
					}else
					{	 
            $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
            $param_bv[':p_status'] = 'T';
          	 $sql = "select kode_jenis_kasus, nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_tipe_klaim = :p_kode_tipe_klaim and nvl(status_nonaktif,'T')=:p_status order by no_urut";									
  				}
				}  			         
        $proc = $DB->parse($sql);
        foreach ($param_bv as $key => $value) {
         oci_bind_by_name($proc, $key, $param_bv[$key]);
        }       
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE_JENIS_KASUS"]==$ls_jkk_kode_jenis_kasus && strlen($ls_jkk_kode_jenis_kasus)==strlen($row["KODE_JENIS_KASUS"])){ echo " selected"; }
          echo " value=\"".$row["KODE_JENIS_KASUS"]."\">".$row["NAMA_JENIS_KASUS"]."</option>";
        }
      ?>
      </select>	
    </div>																																											
  	<div class="clear"></div>
		
		<span id="jkk_span_lokasi_kecelakaan" style="display:none;">
      <div class="form-row_kiri">
      <label style = "text-align:right;">Lokasi Kecelakaan &nbsp; *</label>		 	    				
        <select size="1" id="jkk_kode_lokasi_kecelakaan" name="jkk_kode_lokasi_kecelakaan" value="<?=$ls_jkk_kode_lokasi_kecelakaan;?>" tabindex="34" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:260px;background-color:#F5F5F5\"" : " style=\"width:260px;background-color:#ffff99\"";?>>
        <option value="">-- Pilih --</option>
        <? 
          $param_bv = [];
    			if ($ls_status_submit_agenda =="Y")
    			{
            $param_bv[':p_kode_lokasi_kecelakaan'] = $ls_jkk_kode_lokasi_kecelakaan;
						$sql = "select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where kode_lokasi_kecelakaan=:p_kode_lokasi_kecelakaan";
          }else
    			{
            $param_bv[':p_status'] = 'T';
            $sql = "select kode_lokasi_kecelakaan, nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where nvl(status_nonaktif,'T')=:p_status order by no_urut";									
    			} 				          
          $proc = $DB->parse($sql);
          foreach ($param_bv as $key => $value) {
           oci_bind_by_name($proc, $key, $param_bv[$key]);
          }
          $DB->execute();
          while($row = $DB->nextrow())
          {
            echo "<option ";
            if ($row["KODE_LOKASI_KECELAKAAN"]==$ls_jkk_kode_lokasi_kecelakaan && strlen($ls_jkk_kode_lokasi_kecelakaan)==strlen($row["KODE_LOKASI_KECELAKAAN"])){ echo " selected"; }
            echo " value=\"".$row["KODE_LOKASI_KECELAKAAN"]."\">".$row["NAMA_LOKASI_KECELAKAAN"]."</option>";
          }
        ?>
        </select>	
      </div>
			<div class="clear"></div>		
		</span>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Tempat Kecelakaan</label>		 	    				
      <input type="text" id="jkk_nama_tempat_kecelakaan" name="jkk_nama_tempat_kecelakaan" value="<?=$ls_jkk_nama_tempat_kecelakaan;?>" tabindex="35" size="35" maxlength="300" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " ";?>>
    </div>
		<div class="clear"></div>

    <div class="form-row_kiri">
    <label style = "text-align:right;">Keterangan</label>		 	    				
			<textarea cols="255" rows="1" id="jkk_ket_tambahan" name="jkk_ket_tambahan" tabindex="36" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:270px;background-color:#F5F5F5\"" : " style=\"width:270px;\"";?>><?=$ls_jkk_ket_tambahan;?></textarea>    	
		</div>
		<div class="clear"></div>																										  
  </fieldset>						
	
	<span id="jkk_span_kecelakaan_kerja" style="display:none;">
		<fieldset><legend>Kecelakaan Kerja</legend>
      <div class="form-row_kiri">
      <label style = "text-align:right;">Tindakan Bahaya &nbsp;</label>		 	    				
        <select size="1" id="jkk_kode_tindakan_bahaya" name="jkk_kode_tindakan_bahaya" value="<?=$ls_jkk_kode_tindakan_bahaya;?>" tabindex="1" class="select_format" style="width:380px;background-color:#F5F5F5;" >
        <option value="">-- Pilih Tindakan Bahaya --</option>
        <? 
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and kode = :p_kode";
        $proc = $DB->parse($sql);
        $param_bv = [':p_tipe' => 'KLMTINDBHY',':p_kode' => $ls_jkk_kode_tindakan_bahaya];
        foreach ($param_bv as $key => $value) {
         oci_bind_by_name($proc, $key, $param_bv[$key]);
        }
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if ($row["KODE"]==$ls_jkk_kode_tindakan_bahaya && strlen($ls_jkk_kode_tindakan_bahaya)==strlen($row["KODE"])){ echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
        ?>
        </select>		
      </div>																																										
      <div class="clear"></div>							
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Kondisi Bahaya &nbsp;</label>		 	    				
        <select size="1" id="jkk_kode_kondisi_bahaya" name="jkk_kode_kondisi_bahaya" value="<?=$ls_jkk_kode_kondisi_bahaya;?>" tabindex="2" class="select_format" style="width:380px;background-color:#F5F5F5;" >
        <option value="">-- Pilih Kondisi Bahaya --</option>
        <? 
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and kode = :p_kode";
        $proc = $DB->parse($sql);
        $param_bv = [':p_tipe' => 'KLMKONDBHY',':p_kode' => $ls_jkk_kode_kondisi_bahaya];
        foreach ($param_bv as $key => $value) {
         oci_bind_by_name($proc, $key, $param_bv[$key]);
        }
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if ($row["KODE"]==$ls_jkk_kode_kondisi_bahaya && strlen($ls_jkk_kode_kondisi_bahaya)==strlen($row["KODE"])){ echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
        ?>
        </select>		
      </div>																																										
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Corak &nbsp;</label>
        <input type="text" id="jkk_kode_corak" name="jkk_kode_corak" value="<?=$ls_jkk_kode_corak;?>" tabindex="3" size="7" maxlength="10" readonly class="disabled">
        <input type="text" id="jkk_nama_corak" name="jkk_nama_corak" value="<?=$ls_jkk_nama_corak;?>" size="50" readonly class="disabled">
        <img src="../../images/help.png" alt="LOV Corak" border="0" align="absmiddle"></a>													
      </div>																																										
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Sumber Cedera &nbsp;</label>
        <input type="text" id="jkk_kode_sumber_cedera" name="jkk_kode_sumber_cedera" value="<?=$ls_jkk_kode_sumber_cedera;?>" tabindex="4" size="7" maxlength="10" readonly class="disabled">
        <input type="text" id="jkk_nama_sumber_cedera" name="jkk_nama_sumber_cedera" value="<?=$ls_jkk_nama_sumber_cedera;?>" size="50" readonly class="disabled">
        <img src="../../images/help.png" alt="LOV Sumber Cedera" border="0" align="absmiddle"></a>													
      </div>																																										
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Bagian yang Sakit &nbsp;</label>
        <input type="text" id="jkk_kode_bagian_sakit" name="jkk_kode_bagian_sakit" value="<?=$ls_jkk_kode_bagian_sakit;?>" tabindex="5" size="7" maxlength="10" readonly class="disabled">
        <input type="text" id="jkk_nama_bagian_sakit" name="jkk_nama_bagian_sakit" value="<?=$ls_jkk_nama_bagian_sakit;?>" size="47" readonly class="disabled">
      	<img src="../../images/help.png" alt="LOV Bagian yang Sakit" border="0" align="absmiddle"></a>													
      </div>																																										
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Akibat yg Diderita &nbsp; *</label>
        <input type="text" id="jkk_kode_akibat_diderita" name="jkk_kode_akibat_diderita" value="<?=$ls_jkk_kode_akibat_diderita;?>" tabindex="6" size="7" maxlength="10" readonly  class="disabled">
        <input type="text" id="jkk_nama_akibat_diderita" name="jkk_nama_akibat_diderita" value="<?=$ls_jkk_nama_akibat_diderita;?>" size="44" readonly class="disabled">
        <img src="../../images/help.png" alt="LOV Akibat yg Diderita" border="0" align="absmiddle"></a>													
      </div>																																										
      <div class="clear"></div>			
		</fieldset>		
	</span>
	
	<span id="jkk_span_penyakit_akibat_kerja" style="display:none;">
		<fieldset><legend>Penyakit Akibat Kerja</legend>
      <div class="form-row_kiri">
      <label style = "text-align:right;">Lama Bekerja (Thn) &nbsp;</label>		 	    				
      <select size="1" id="jkk_kode_lama_bekerja" name="jkk_kode_lama_bekerja" value="<?=$ls_jkk_kode_lama_bekerja;?>" tabindex="7" class="select_format" style="width:455px;background-color:#F5F5F5;" >
      <option value="">-- Pilih --</option>
      <? 
      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and kode = :p_kode";
      $proc = $DB->parse($sql);
      $param_bv = [':p_tipe' => 'KLMLAMAKRJ',':p_kode' => $ls_jkk_kode_lama_bekerja];
      foreach ($param_bv as $key => $value) {
       oci_bind_by_name($proc, $key, $param_bv[$key]);
      }
      $DB->execute();
      while($row = $DB->nextrow())
      {
      echo "<option ";
      if ($row["KODE"]==$ls_jkk_kode_lama_bekerja && strlen($ls_jkk_kode_lama_bekerja)==strlen($row["KODE"])){ echo " selected"; }
      echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      }
      ?>
      </select>		
      </div>																																										
      <div class="clear"></div>								
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Penyakit yg Timbul &nbsp;</label>
        <input type="text" id="jkk_kode_penyakit_timbul" name="jkk_kode_penyakit_timbul" value="<?=$ls_jkk_kode_penyakit_timbul;?>" tabindex="8" size="7" maxlength="10" readonly class="disabled">
        <input type="text" id="jkk_nama_penyakit_timbul" name="jkk_nama_penyakit_timbul" value="<?=$ls_jkk_nama_penyakit_timbul;?>" size="50" readonly class="disabled">
        <img src="../../images/help.png" alt="LOV Bagian yang Sakit" border="0" align="absmiddle"></a>													
      </div>																																										
      <div class="clear"></div>			
		</fieldset>			
	</span>
	
  <fieldset><legend>Tempat Tenaga Kerja Dirawat</legend>
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tempat Perawatan &nbsp;</label>		 	    				
      <select size="1" id="jkk_kode_tempat_perawatan" name="jkk_kode_tempat_perawatan" value="<?=$ls_jkk_kode_tempat_perawatan;?>" tabindex="9" class="select_format" style="width:350px;background-color:#F5F5F5;">
      <option value="">-- Pilih --</option>
      <? 
      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and kode = :p_kode";
      $proc = $DB->parse($sql);
      $param_bv = [':p_tipe' => 'KLMTMPTRWT',':p_kode' => $ls_jkk_kode_tempat_perawatan];
      foreach ($param_bv as $key => $value) {
       oci_bind_by_name($proc, $key, $param_bv[$key]);
      }
      $DB->execute();
      while($row = $DB->nextrow())
      {
      echo "<option ";
      if ($row["KODE"]==$ls_jkk_kode_tempat_perawatan && strlen($ls_jkk_kode_tempat_perawatan)==strlen($row["KODE"])){ echo " selected"; }
      echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      }
      ?>
      </select>		
    </div>																																											
    <div class="clear"></div>
    
    <div class="form-row_kiri">
    <label style = "text-align:right;">Berobat Jalan &nbsp;</label>		 	    				
      <select size="1" id="jkk_kode_berobat_jalan" name="jkk_kode_berobat_jalan" value="<?=$ls_jkk_kode_berobat_jalan;?>" tabindex="10" class="select_format" style="width:350px;background-color:#F5F5F5;" >
      <option value="">-- Pilih --</option>
      <? 
      $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe and kode = :p_kode";
      $param_bv = [':p_tipe' => 'KLMBOBTJLN',':p_kode' => $ls_jkk_kode_berobat_jalan];
      foreach ($param_bv as $key => $value) {
       oci_bind_by_name($proc, $key, $param_bv[$key]);
      }
      $DB->execute();
      while($row = $DB->nextrow())
      {
      echo "<option ";
      if ($row["KODE"]==$ls_jkk_kode_berobat_jalan && strlen($ls_jkk_kode_berobat_jalan)==strlen($row["KODE"])){ echo " selected"; }
      echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      }
      ?>
      </select>		
    </div>																																												
    <div class="clear"></div>			
    
    <span id="jkk_span_faskes" style="display:none;">
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Fasilitas Kesehatan</label>
      <input type="text" id="jkk_kode_ppk" name="jkk_kode_ppk" value="<?=$ls_jkk_kode_ppk;?>" tabindex="11" size="7" maxlength="20" readonly class="disabled">
      <input type="text" id="jkk_nama_ppk" name="jkk_nama_ppk" value="<?=$ls_jkk_nama_ppk;?>" size="35" readonly class="disabled">
      <img src="../../images/help.png" alt="Cari Faskes" border="0" align="absmiddle"></a>
    </div>	
    <div class="clear"></div>
    </span>
    
    <span id="jkk_span_reimburse" style="display:none;">						
      <div class="form-row_kiri">
      <label style = "text-align:right;">Reimburse</label>		 	    				
      <input type="text" id="jkk_nama_faskes_reimburse" name="jkk_nama_faskes_reimburse" value="<?=$ls_jkk_nama_faskes_reimburse;?>" tabindex="12" size="40" maxlength="100" readonly class="disabled">
      (* isikan Nama RS/Puskesmas/Poliklinik/dll)
      </div>																																														
    <div class="clear"></div>
    </span>

    <?
		echo "<script type=\"text/javascript\">fl_js_jkk_kode_jenis_kasus();</script>";
    echo "<script type=\"text/javascript\">fl_js_jkk_kode_tempat_perawatan();</script>";
    ?>	
    
	</fieldset>
		
  <fieldset><legend>Diagnosa</legend>						 
    <table id="tblrincian2" width="75%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
      <tr>
      	<th colspan="4"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
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
               "where a.kode_klaim = :p_kode_klaim ".
               "order by a.no_urut";
        $proc = $DB->parse($sql);
        oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
        $DB->execute();							              					
        $i=0;		
        $ln_dtl =0;										
        $ln_tot_mutasi = 0;
        while ($row = $DB->nextrow())
        {
        ?>		
          <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
          <td>
          	<input type="text" id="jkk_dtldiag_nama_tenaga_medis<?=$i;?>" name="jkk_dtldiag_nama_tenaga_medis<?=$i;?>" size="15" maxlength="100" value="<?=$row['NAMA_TENAGA_MEDIS'];?>" style="border-width: 1;text-align:left"> 
          </td>					
          <td>
            <input type="text" id="jkk_dtldiag_kode_diagnosa_detil<?=$i;?>" name="jkk_dtldiag_kode_diagnosa_detil<?=$i;?>" size="5" style="border-width: 1;text-align:left;" maxlength="10" value="<?=$row['KODE_DIAGNOSA_DETIL'];?>" readonly>
            <input type="text" id="jkk_dtldiag_nama_diagnosa_detil<?=$i;?>" name="jkk_dtldiag_nama_diagnosa_detil<?=$i;?>" value="<?=$row['NAMA_DIAGNOSA_DETIL'];?>" size="30" readonly class="disabled">
            <a href="#" onclick="fl_js_jkk_lov_diagnosa(<?=$i;?>);"><img src="../../images/help.png" alt="Cari Data Diagnosa" border="0" align="absmiddle"></a>            			 
          </td>
          <td>
          	<input type="text" id="jkk_dtldiag_keterangan<?=$i;?>" name="jkk_dtldiag_keterangan<?=$i;?>" size="15" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left">
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
        <input type="hidden" id="jkk_dtldiag_kounter_dtl" name="jkk_dtldiag_kounter_dtl" value="<?=$ln_dtl;?>">
        <input type="hidden" id="jkk_dtldiag_count_dtl" name="jkk_dtldiag_count_dtl" value="<?=$ln_countdtl;?>">
        <input type="hidden" name="jkk_dtldiag_showmessage" style="border-width: 0;text-align:right" readonly size="30">						
      </td>									              
    </tr>							 							
    </table>	  																						  
  </fieldset>

  <span id="jkk_span_upah_tk" style="display:none;">
    <fieldset><legend>Upah TK</legend>								 										 
      <div class="form-row_kiri">
      <label style = "text-align:right;">Tipe Upah &nbsp;</label>		 	    				
        <select size="1" id="jkk_kode_tipe_upah" name="jkk_kode_tipe_upah" value="<?=$ls_jkk_kode_tipe_upah;?>" tabindex="45" class="select_format" style="width:260px;" >
        <option value="">-- Pilih --</option>
        <? 
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe=:p_tipe||:p_kode_segmen and nvl(aktif,'T')=:p_status order by seq";
        $proc = $DB->parse($sql);
        $param_bv = [':p_tipe' => 'TPUPA', ':p_kode_segmen' => $ls_kode_segmen,':p_status' => 'Y'];
        foreach ($param_bv as $key => $value) {
         oci_bind_by_name($proc, $key, $param_bv[$key]);
        }
        $DB->execute();
        while($row = $DB->nextrow())
        {
        echo "<option ";
        if ($row["KODE"]==$ls_jkk_kode_tipe_upah && strlen($ls_jkk_kode_tipe_upah)==strlen($row["KODE"])){ echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
        }
        ?>
        </select>		
      </div>																																											
      <div class="clear"></div>
      
      <div class="form-row_kiri">
      <label style = "text-align:right;">Upah Terakhir&nbsp; *</label>		 	    				
      	<input type="text" id="jkk_nom_upah_terakhir" name="jkk_nom_upah_terakhir" value="<?=number_format((float)$ln_jkk_nom_upah_terakhir,2,".",",");?>" tabindex="46" size="34" maxlength="20" onblur="this.value=format_uang(this.value);" <?=($ls_kode_segmen =="JAKON")? " style=\"background-color:#ffff99\"" : " readonly class=disabled ";?>>		
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
      echo "<script type=\"text/javascript\">fl_js_jkk1_set_field_upahtk();</script>";
      ?>									  
    </fieldset>
  </span>	
	
  <fieldset><legend>Aktivitas Pelaporan</legend>						 
    <table id="tblrincian3" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
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
                  "where a.kode_klaim = :p_kode_klaim ".
                  "order by a.no_urut";
          $proc = $DB->parse($sql2);
          oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;										
          $ln_tot_mutasi = 0;
          while ($row = $DB->nextrow())
          {
          ?>																				
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
            <td>
            	<input type="text" readonly id="jkk_dtlaktvpelap_tgl_aktivitas<?=$i;?>" name="jkk_dtlaktvpelap_tgl_aktivitas<?=$i;?>" size="9" maxlength="10" value="<?=$row['TGL_AKTIVITAS'];?>" style="border-width: 1;text-align:left"> 
            </td>					
            <td>
            	<input type="text" id="jkk_dtlaktvpelap_nama_aktivitas<?=$i;?>" name="jkk_dtlaktvpelap_nama_aktivitas<?=$i;?>" value="<?=$row['NAMA_AKTIVITAS'];?>" size="15" readonly class="disabled" maxlength="100">            			 
            </td>
            <td>
            	<input type="text" readonly id="jkk_dtlaktvpelap_nama_sumber<?=$i;?>" name="jkk_dtlaktvpelap_nama_sumber<?=$i;?>" size="12" maxlength="100" value="<?=$row['NAMA_SUMBER'];?>" style="border-width: 1;text-align:left">
            </td>
            <td>
            	<input type="text" readonly id="jkk_dtlaktvpelap_profesi_sumber<?=$i;?>" name="jkk_dtlaktvpelap_profesi_sumber<?=$i;?>" size="10" maxlength="100" value="<?=$row['PROFESI_SUMBER'];?>" style="border-width: 1;text-align:left">
            </td>
            <td>
            	<input type="text" readonly id="jkk_dtlaktvpelap_alamat<?=$i;?>" name="jkk_dtlaktvpelap_alamat<?=$i;?>" size="12" maxlength="100" value="<?=$row['ALAMAT'];?>" style="border-width: 1;text-align:left">
            </td>	
            <td>
              <input type="text" readonly id="jkk_dtlaktvpelap_telepon_area<?=$i;?>" name="jkk_dtlaktvpelap_telepon_area<?=$i;?>" size="2" maxlength="5" value="<?=$row['TELEPON_AREA'];?>" style="border-width: 1;text-align:left">
              <input type="text" readonly id="jkk_dtlaktvpelap_telepon<?=$i;?>" name="jkk_dtlaktvpelap_telepon<?=$i;?>" size="9" maxlength="20" value="<?=$row['TELEPON'];?>" style="border-width: 1;text-align:left">        							
            </td>
            <td>
            	<input type="text" readonly id="jkk_dtlaktvpelap_keterangan<?=$i;?>" name="jkk_dtlaktvpelap_keterangan<?=$i;?>" size="6" maxlength="300" value="<?=$row['KETERANGAN'];?>" style="border-width: 1;text-align:left">
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
        <th colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	  						
        <td colspan="1">
          <input type="hidden" id="jkk_dtlaktvpelap_kounter_dtl" name="jkk_dtlaktvpelap_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="jkk_dtlaktvpelap_count_dtl" name="jkk_dtlaktvpelap_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="jkk_dtlaktvpelap_showmessage" style="border-width: 0;text-align:right" readonly size="30">								
        </td>
        <td style="text-align:center" colspan="1"></td>
      </tr>							 
    </table>  																						  
  </fieldset>
	
  <fieldset><legend>Kondisi Terakhir TK</legend>
		</br>
		
    <div class="form-row_kiri">
    <label style = "text-align:right;">Kondisi Terakhir &nbsp;</label>		 	    				
      <select size="1" id="jkk_kode_kondisi_terakhir" name="jkk_kode_kondisi_terakhir" value="<?=$ls_jkk_kode_kondisi_terakhir;?>" tabindex="31" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:225px;background-color:#F5F5F5\"" : " style=\"width:225px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <? 
      $param_bv = [];
				if ($ls_status_submit_agenda =="Y")  	
  			{
          $param_bv[':p_kode_kondisi_terakhir'] = $ls_jkk_kode_kondisi_terakhir;
				  $sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = :p_kode_kondisi_terakhir"; 			
				}else
				{
          $param_bv[':p_kode_tipe_klaim'] = $ls_kode_tipe_klaim;
          $param_bv[':p_status'] = 'T';
				 	$sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_tipe_klaim = :p_kode_tipe_klaim and nvl(status_nonaktif,'T')=:p_status order by no_urut";	 
				}			        
        $proc = $DB->parse($sql);
        foreach ($param_bv as $key => $value) {
         oci_bind_by_name($proc, $key, $param_bv[$key]);
        }
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE_KONDISI_TERAKHIR"]==$ls_jkk_kode_kondisi_terakhir && strlen($ls_jkk_kode_kondisi_terakhir)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
          echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
        }
      ?>
      </select>	
    </div>																																									
  	<div class="clear"></div>
																	
    <div class="form-row_kiri">
    <label style = "text-align:right;">Tgl Kondisi TK &nbsp;</label>
      <input type="text" id="jkk_tgl_kondisi_terakhir" name="jkk_tgl_kondisi_terakhir" value="<?=$ld_jkk_tgl_kondisi_terakhir;?>" tabindex="32" size="27" maxlength="10" onblur="convert_date(jkk_tgl_kondisi_terakhir);"  <?=($ls_status_submit_agenda =="Y")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
  		<?
				if ($ls_status_submit_agenda !="Y")  	
  			{
				 	?> 
     			<input id="btn_jkk_tgl_kondisi_terakhir" type="image" align="top" onclick="return showCalendar('jkk_tgl_kondisi_terakhir', 'dd-mm-y');" src="../../images/calendar.gif" />									
					<?  			
				}
			?>
		</div>    		
		<div class="clear"></div>

		</br> 	    																						  
  </fieldset>				
</div>
