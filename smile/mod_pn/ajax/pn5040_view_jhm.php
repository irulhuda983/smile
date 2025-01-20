<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JHT/JKM
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)			 						 
-----------------------------------------------------------------------------*/
$sql = "select
            kode_manfaat, no_urut, to_char(a.tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun,
            a.nom_saldo_awaltahun,
            to_char(a.tgl_pengembangan_estimasi,'dd/mm/yyyy') tgl_pengembangan_estimasi,
            a.nom_pengembangan_estimasi, a.nom_manfaat_sudahdiambil, a.pengambilan_ke
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = :P_KODE_KLAIM
        and kode_manfaat = '18'
        and no_urut = 1 ";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
$DB->execute();
$row = $DB->nextrow();
$ls_jhminput_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_jhminput_no_urut 	 				 					= $row["NO_URUT"];
$ld_jhminput_tgl_saldo_awaltahun 				= $row["TGL_SALDO_AWALTAHUN"];
$ln_jhminput_nom_saldo_awaltahun 	 			= $row["NOM_SALDO_AWALTAHUN"];
$ld_jhminput_tgl_pengembangan_estimasi 	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ln_jhminput_nom_pengembangan_estimasi 	= $row["NOM_PENGEMBANGAN_ESTIMASI"];
$ln_jhminput_nom_manfaat_sudahdiambil 	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];	
$ln_jhminput_pengambilan_ke 						= $row["PENGAMBILAN_KE"];	 	 

$sql = "select 
            a.kode_klaim, a.kode_tipe_penerima, a.kode_hubungan, a.ket_hubungan_lainnya, a.no_urut_keluarga, 
            a.nomor_identitas, a.nama_pemohon, a.tempat_lahir, to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin,a.golongan_darah, 
            a.alamat, a.rt, a.rw, 
            a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan) nama_kelurahan,
            a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan,
            a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten,
            a.kode_pos, a.telepon_area, a.telepon, 
            a.telepon_ext, a.handphone, a.email, a.npwp
        from sijstk.pn_klaim_penerima_manfaat a
        where kode_klaim = :P_KODE_KLAIM
        and exists
        (
            select null from sijstk.pn_klaim_manfaat_detil
            where kode_klaim = a.kode_klaim
            and kode_tipe_penerima = a.kode_tipe_penerima
            and kode_manfaat = :P_JHMINPUT_KODE_MANFAAT    
        )
        and rownum = 1";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
oci_bind_by_name($proc, ':P_JHMINPUT_KODE_MANFAAT', $ls_jhminput_kode_manfaat);
$DB->execute();
$row = $DB->nextrow();
$ls_jhminput_kode_tipe_penerima 	= $row["KODE_TIPE_PENERIMA"];
$ls_jhminput_kode_hubungan 				= $row["KODE_HUBUNGAN"];
$ls_jhminput_ket_hubungan_lainnya	= $row["KET_HUBUNGAN_LAINNYA"];
$ls_jhminput_no_urut_keluarga 		= $row["NO_URUT_KELUARGA"];
$ls_jhminput_nomor_identitas 			= $row["NOMOR_IDENTITAS"];
$ls_jhminput_nama_pemohon 				= $row["NAMA_PEMOHON"];
$ls_jhminput_tempat_lahir 				= $row["TEMPAT_LAHIR"];
$ld_jhminput_tgl_lahir 						= $row["TGL_LAHIR"];
$ls_jhminput_jenis_kelamin 				= $row["JENIS_KELAMIN"];
$ls_jhminput_golongan_darah				= $row["GOLONGAN_DARAH"];
$ls_jhminput_alamat 							= $row["ALAMAT"];
$ls_jhminput_rt 									= $row["RT"];
$ls_jhminput_rw 									= $row["RW"];
$ls_jhminput_kode_kelurahan 			= $row["KODE_KELURAHAN"];
$ls_jhminput_nama_kelurahan 			= $row["NAMA_KELURAHAN"];
$ls_jhminput_kode_kecamatan 			= $row["KODE_KECAMATAN"];
$ls_jhminput_nama_kecamatan 			= $row["NAMA_KECAMATAN"];
$ls_jhminput_kode_kabupaten 			= $row["KODE_KABUPATEN"];
$ls_jhminput_nama_kabupaten 			= $row["NAMA_KABUPATEN"];
$ls_jhminput_kode_pos 						= $row["KODE_POS"];
$ls_jhminput_telepon_area 				= $row["TELEPON_AREA"];
$ls_jhminput_telepon 							= $row["TELEPON"];
$ls_jhminput_telepon_ext 					= $row["TELEPON_EXT"];
$ls_jhminput_handphone 						= $row["HANDPHONE"];
$ls_jhminput_email 								= $row["EMAIL"];
$ls_jhminput_npwp 								= $row["NPWP"];

$ld_jhminput_tgl_kematian  = $ld_tgl_kematian;
$ls_jhminput_ket_tambahan	= $ls_ket_tambahan;
?>
<script language="JavaScript">    
  function fl_js_val_numeric_jht(v_field_id)
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
	
  function fl_js_jhminput_kode_tipe_penerima() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhminput_kode_tipe_penerima').value;
							
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_jhminput_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_jhminput_kode_hubungan").style.display = 'none';
      window.document.getElementById("jhminput_kode_hubungan").value = "T";	
			window.document.getElementById("jhminput_ket_hubungan_lainnya").value = "";
			window.document.getElementById("jhminput_no_urut_keluarga").value = "";	
    }
  }
	
  function fl_js_jhminput_ahliwaris_lain() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhminput_kode_tipe_penerima').value;
		var	v_kode_hubungan = window.document.getElementById('jhminput_kode_hubungan').value;
    
    if (v_kode_tipe_penerima =="AW" && v_kode_hubungan=="L") //Ahli Waris Lainnya ---------
    {
		 	window.document.getElementById("span_jhminput_ket_hubungan_lainnya").style.display = 'block'; 
    }else
    {
      window.document.getElementById("span_jhminput_ket_hubungan_lainnya").style.display = 'none'; 	
    } 	
  }						
</script>	

<script type="text/javascript">
	var curr_jhminput_kode_hubungan =<?php echo ($ls_jhminput_kode_hubungan=='') ? 'false' : "'".$ls_jhminput_kode_hubungan."'"; ?>;
</script>	
		
<div id="formKiri" style="width:955px;">
	<table width="955px;" border="0">
		<tr>
			<td width="47%" valign="top"">
				<table border="0">
					<tr>
						<td>
							<fieldset style="height:120px;width:450px;"><legend><b><i><font color="#009999">Input Data Klaim JHT/JKM :</font></i></b></legend>
                </br></br>
								
								<div class="form-row_kiri">
                <label style = "text-align:right;">Tgl Kematian &nbsp;</label>
                  <input type="text" id="jhminput_tgl_kematian" name="jhminput_tgl_kematian" value="<?=$ld_jhminput_tgl_kematian;?>" tabindex="1" style="width:230px;" maxlength="10" onblur="convert_date(jhminput_tgl_kematian);" readonly class="disabled">      	
            		</div> 	
            		<div class="clear"></div>
            		
                <div class="form-row_kiri">
                <label style = "text-align:right;">Keterangan &nbsp;</label>
            			<textarea cols="255" rows="1" id="jhminput_ket_tambahan" name="jhminput_ket_tambahan" tabindex="2" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:230px;background-color:#F5F5F5;\"" : " style=\"width:230px;background-color:#ffff99\"";?>><?=$ls_jhminput_ket_tambahan;?></textarea>	   							
                </div>			
            		<div class="clear"></div>							
							</fieldset>	
						</td>	
					</tr>
					
					<tr>
						<td>
							<fieldset style="height:246px;"><legend><b><i><font color="#009999">Saldo JHT :</font></i></b></legend>
                </br></br>
								
								<div class="form-row_kiri">
                <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
                  <input type="text" id="jhminput_tgl_saldo_awaltahun" name="jhminput_tgl_saldo_awaltahun" value="<?=$ld_jhminput_tgl_saldo_awaltahun;?>" style="width:70px;" readonly class="disabled">
                  <input type="text" id="jhminput_nom_saldo_awaltahun" name="jhminput_nom_saldo_awaltahun" value="<?=number_format((float)$ln_jhminput_nom_saldo_awaltahun,2,".",",");?>" maxlength="20" readonly class="disabled" style="width:150px;text-align:right;">                					
                </div>			
                <div class="clear"></div>
            
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Saldo JHT Estimasi &nbsp;</label>
                  <input type="text" id="jhminput_tgl_pengembangan_estimasi" name="jhminput_tgl_pengembangan_estimasi" value="<?=$ld_jhminput_tgl_pengembangan_estimasi;?>" style="width:70px;" readonly class="disabled">
                  <input type="text" id="jhminput_nom_pengembangan_estimasi" name="jhminput_nom_pengembangan_estimasi" value="<?=number_format((float)$ln_jhminput_nom_pengembangan_estimasi,2,".",",");?>" maxlength="20" readonly class="disabled" style="width:150px;text-align:right;">                					
                </div>			
                <div class="clear"></div>
            		
								</br></br>
									
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Saldo JHT sdh Diambil</label>
                  <input type="text" id="jhminput_nom_manfaat_sudahdiambil" name="jhminput_nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_jhminput_nom_manfaat_sudahdiambil,2,".",",");?>" maxlength="20" readonly class="disabled" style="width:200px;text-align:right;">                					
                </div>				
                <div class="clear"></div>
            
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Pengambilan JHT ke- &nbsp;</label>
                  <input type="text" id="jhminput_pengambilan_ke" name="jhminput_pengambilan_ke" value="<?=number_format((float)$ln_jhminput_pengambilan_ke,0,".",",");?>" maxlength="20" readonly class="disabled" style="width:200px;text-align:right;">                					
                </div>		
                <div class="clear"></div>								
							</fieldset>	
						</td>					
					</tr>		 
				</table>	
			</td>
			
			<td width="53%" valign="top"">
				<fieldset><legend><b><i><font color="#009999">Penerima Manfaat :</font></i></b></legend>
          
					<div class="form-row_kiri">
          <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
      			<select size="1" id="jhminput_kode_tipe_penerima" name="jhminput_kode_tipe_penerima" value="<?=$ls_jhminput_kode_tipe_penerima;?>" tabindex="1" class="select_format" onChange="fl_js_jhminput_kode_tipe_penerima();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
            <option value="">- Tipe Penerima --</option>
            <?
      			if ($ls_status_submit_agenda=="T")
      			{
              $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from sijstk.pn_kode_manfaat_eligibility a, sijstk.pn_kode_tipe_penerima b ".
                     "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
                     "and a.kode_manfaat = '$ls_jhminput_kode_manfaat' ".
                     "and nvl(a.status_nonaktif,'T')= 'T' and a.kode_tipe_penerima <> 'TK' ".
                     "order by b.no_urut";			
      			}else
      			{
              $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from sijstk.pn_kode_manfaat_eligibility a, sijstk.pn_kode_tipe_penerima b ".
                     "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
                     "and a.kode_manfaat = '$ls_jhminput_kode_manfaat' ".
      							 "and a.kode_tipe_penerima = '$ls_jhminput_kode_tipe_penerima' ";
      			}			 
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if (($row["KODE_TIPE_PENERIMA"]==$ls_jhminput_kode_tipe_penerima && strlen($row["KODE_TIPE_PENERIMA"])==strlen($ls_jhminput_kode_tipe_penerima))) { echo " selected"; }
            echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
            }
            ?>
            </select>
      			<input type="hidden" id="jhminput_kode_tipe_penerima_old" name="jhminput_kode_tipe_penerima_old" value="<?=$ls_jhminput_kode_tipe_penerima;?>">
      			<input type="hidden" id="jhminput_kode_manfaat" name="jhminput_kode_manfaat" value="<?=$ls_jhminput_kode_manfaat;?>">
      			<input type="hidden" id="jhminput_no_urut" name="jhminput_no_urut" value="<?=$ls_jhminput_no_urut;?>"> 
      		</div>				
      		<div class="clear"></div>
      		
          <span id="span_jhminput_kode_hubungan" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Ahli Waris *</label>		 	    				
        			<select size="1" id="jhminput_kode_hubungan" name="jhminput_kode_hubungan" value="<?=$ls_jhminput_kode_hubungan;?>" tabindex="2" class="select_format" onChange="fl_js_jhminput_ahliwaris_lain(); f_ajax_jhminput_val_kode_hubungan();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
              <option value="">-- Pilih --</option>
              <?
        			if ($ls_status_submit_agenda=="T")
        			{
                $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where nvl(aktif,'T') = 'Y' and kode_hubungan <> 'T' ".
      							 	 "and kode_hubungan <> decode((select jenis_kelamin from kn.vw_kn_tk where kode_tk='$ls_kode_tk' and rownum=1),'L','S','P','I','') ".
      								 "order by urutan";			
        			}else
        			{
                $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan='$ls_jhminput_kode_hubungan'";
        			}
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["KODE_HUBUNGAN"]==$ls_jhminput_kode_hubungan && strlen($ls_jhminput_kode_hubungan)==strlen($row["KODE_HUBUNGAN"])){ echo " selected"; }
                echo " value=\"".$row["KODE_HUBUNGAN"]."\">".$row["NAMA_HUBUNGAN"]."</option>";
              }
              ?>
              </select>
      				<input type="hidden" id="jhminput_no_urut_keluarga" name="jhminput_no_urut_keluarga" value="<?=$ls_jhminput_no_urut_keluarga;?>" size="2" maxlength="3" readonly class="disabled">        				
            </div>																																									
            <div class="clear"></div>
          </span>
      
      		<span id="span_jhminput_ket_hubungan_lainnya" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">&nbsp; *</label>		
      				<input type="text" id="jhminput_ket_hubungan_lainnya" name="jhminput_ket_hubungan_lainnya" value="<?=$ls_jhminput_ket_hubungan_lainnya;?>" tabindex="3" placeholder="-- isikan jenis kekerabatan lainya -- " maxlength="300" <?=($ls_status_submit_agenda =="Y")? " style=\"width:250px;background-color:#F5F5F5\"" : " style=\"width:250px;background-color:#ffff99\"";?>>
            </div>																																									
            <div class="clear"></div>			
      		</span>
      
      		<div class="form-row_kiri">
          <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
      			<input type="text" id="jhminput_nama_pemohon" name="jhminput_nama_pemohon" value="<?=$ls_jhminput_nama_pemohon;?>" tabindex="4" size="46" maxlength="100" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
          </div>										
          <div class="clear"></div>												
      							
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tempat, Tgl Lahir &nbsp; *</label>	    				
            <input type="text" id="jhminput_tempat_lahir" name="jhminput_tempat_lahir" value="<?=$ls_jhminput_tempat_lahir;?>" tabindex="5" maxlength="50" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:162px\"" : " style=\"width:162px;background-color:#ffff99\"";?>>
            <input type="text" id="jhminput_tgl_lahir" name="jhminput_tgl_lahir" value="<?=$ld_jhminput_tgl_lahir;?>" tabindex="6" maxlength="10" onblur="convert_date(jhminput_tgl_lahir);" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled style=\"width:80px\"" : " style=\"width:80px;background-color:#ffff99\"";?>>
            <?
            if ($ls_status_submit_agenda=="T")
            {
            ?>      								
            <input id="btn_jhminput_tgl_lahir" type="image" align="top" onclick="return showCalendar('jhminput_tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />							
            <?
            }
            ?> 			
          </div>																																													
        	<div class="clear"></div>
      
      		
          <div class="form-row_kiri">
          <label style = "text-align:right;">Jenis Kelamin *</label>		 	    				
      			<select size="1" id="jhminput_jenis_kelamin" name="jhminput_jenis_kelamin" value="<?=$ls_jhminput_jenis_kelamin;?>" tabindex="7" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:105px;background-color:#F5F5F5\"" : " style=\"width:105px;background-color:#ffff99\"";?>>
            <option value="">-- Pilih --</option>
            <?
      			if ($ls_status_submit_agenda=="T")
      			{
             	$sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'JNSKELAMIN' and nvl(aktif,'T')='Y' order by seq";			
      			}else
      			{
              $sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'JNSKELAMIN' and kode='$ls_jhminput_jenis_kelamin'";
      			}
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
              echo "<option ";
              if ($row["KODE"]==$ls_jhminput_jenis_kelamin && strlen($ls_jhminput_jenis_kelamin)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>
      			&nbsp;
          	Gol. Darah	 	    				
      			<select size="1" id="jhminput_golongan_darah" name="jhminput_golongan_darah" value="<?=$ls_jhminput_golongan_darah;?>" tabindex="7" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:103px;background-color:#F5F5F5\"" : " style=\"width:103px;\"";?>>
            <option value="">-- Pilih --</option>
            <?
      			if ($ls_status_submit_agenda=="T")
      			{
             	$sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'GOLDARAH' and nvl(aktif,'T')='Y' order by seq";			
      			}else
      			{
              $sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'GOLDARAH' and kode='$ls_jhminput_jenis_kelamin'";
      			}
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
              echo "<option ";
              if ($row["KODE"]==$ls_jhminput_golongan_darah && strlen($ls_jhminput_golongan_darah)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>			
      		</div>																																									
          <div class="clear"></div>
      												
      		</br>
      												
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;&nbsp;*</label>		 	    				
            <input type="text" id="jhminput_alamat" name="jhminput_alamat" value="<?=$ls_jhminput_alamat;?>" tabindex="8" size="46" maxlength="300" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
      		</div>																																										
        	<div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhminput_rt" name="jhminput_rt" value="<?=$ls_jhminput_rt;?>" tabindex="9" size="15" maxlength="5" onblur="fl_js_val_numeric_jht('jhminput_rt');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
            /
            <input type="text" id="jhminput_rw" name="jhminput_rw" value="<?=$ls_jhminput_rw;?>" tabindex="10" size="20" maxlength="5" onblur="fl_js_val_numeric_jht('jhminput_rw');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>		
          </div>																																										
        	<div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
            <input type="text" id="jhminput_kode_pos" name="jhminput_kode_pos" value="<?=$ls_jhminput_kode_pos;?>" tabindex="11" size="35" maxlength="10" readonly <?=($ls_status_submit_agenda =="Y")? " class=disabled" : " style=\"background-color:#ffff99\"";?>>
      			<?
      			if ($ls_status_submit_agenda=="T")
      			{
      			?>      								
      				<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_lov_pos.php?p=pn5040_tabdataklaim_jht.php&a=formreg&b=jhminput_kode_kelurahan&c=jhminput_nama_kelurahan&d=jhminput_kode_kecamatan&e=jhminput_nama_kecamatan&f=jhminput_kode_kabupaten&g=jhminput_nama_kabupaten&h=jhminput_kode_propinsi&j=jhminput_nama_propinsi&k=jhminput_kode_pos','',800,500,1)">							
            <?
      			}
      			?>
      			<img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
      		</div>																																																	
          <div class="clear"></div>
      			
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhminput_nama_kelurahan" name="jhminput_nama_kelurahan" value="<?=$ls_jhminput_nama_kelurahan;?>" size="40" readonly class="disabled">
      			<input type="hidden" id="jhminput_kode_kelurahan" name="jhminput_kode_kelurahan" value="<?=$ls_jhminput_kode_kelurahan;?>" size="8" maxlength="20" readonly class="disabled">      
      		</div>																																																	
          <div class="clear"></div>
      			
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="jhminput_nama_kecamatan" name="jhminput_nama_kecamatan" value="<?=$ls_jhminput_nama_kecamatan;?>" size="40" readonly class="disabled">
            <input type="hidden" id="jhminput_kode_kecamatan" name="jhminput_kode_kecamatan" value="<?=$ls_jhminput_kode_kecamatan;?>" size="8" maxlength="10">			
      		</div>
      		<div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="text" id="jhminput_nama_kabupaten" name="jhminput_nama_kabupaten" value="<?=$ls_jhminput_nama_kabupaten;?>" size="40" readonly class="disabled">
      			<input type="hidden" id="jhminput_kode_kabupaten" name="jhminput_kode_kabupaten" value="<?=$ls_jhminput_kode_kabupaten;?>" size="25" maxlength="20">      
            <input type="hidden" id="jhminput_kode_propinsi" name="jhminput_kode_propinsi" value="<?=$ls_jhminput_kode_propinsi;?>" size="8" readonly class="disabled">
            <input type="hidden" id="jhminput_nama_propinsi" name="jhminput_nama_propinsi" value="<?=$ls_jhminput_nama_propinsi;?>" size="32" readonly class="disabled">
      		</div>
          <div class="clear"></div>		
      																										
      		</br>
         			
          <div class="form-row_kiri">
          <label style = "text-align:right;">Email &nbsp;&nbsp;</label>		 	    				
      			<input type="text" id="jhminput_email" name="jhminput_email" value="<?=$ls_jhminput_email;?>" tabindex="12" size="46" maxlength="200" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      		</div>
      		<div class="clear"></div>
      	
          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Telp &nbsp;</label>	    				
            <input type="text" id="jhminput_telepon_area" name="jhminput_telepon_area" tabindex="13" value="<?=$ls_jhminput_telepon_area;?>" size="5" maxlength="5" onblur="fl_js_val_numeric_jht('jhminput_telepon_area');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
            <input type="text" id="jhminput_telepon" name="jhminput_telepon" tabindex="14" value="<?=$ls_jhminput_telepon;?>" size="24" maxlength="20" onblur="fl_js_val_numeric_jht('jhminput_telepon');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
            &nbsp;ext.
            <input type="text" id="jhminput_telepon_ext" name="jhminput_telepon_ext" tabindex="15" value="<?=$ls_jhminput_telepon_ext;?>" size="5" maxlength="5" onblur="fl_js_val_numeric_jht('jhminput_telepon_ext');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      		</div>
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">Handphone &nbsp;&nbsp;</label>		 	    				
          	<input type="text" id="jhminput_handphone" name="jhminput_handphone" tabindex="16" value="<?=$ls_jhminput_handphone;?>" size="46" maxlength="15" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      		</div>																																																																																														
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Identitas &nbsp;</label>
            <input type="text" id="jhminput_nomor_identitas" name="jhminput_nomor_identitas" value="<?=$ls_jhminput_nomor_identitas;?>" size="46" maxlength="30" tabindex="17" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      		</div>																																									
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">NPWP &nbsp; *</label>
            <input type="text" id="jhminput_npwp" name="jhminput_npwp" value="<?=$ls_jhminput_npwp;?>" size="41" maxlength="15" tabindex="18" onblur="fl_js_val_numeric_jht('jhminput_npwp');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
      		</div>																																									
          <div class="clear"></div>
      
          <?
            echo "<script type=\"text/javascript\">fl_js_jhminput_kode_tipe_penerima();</script>";
            echo "<script type=\"text/javascript\">fl_js_jhminput_ahliwaris_lain();</script>";
          ?>						
      		</br>					
				</fieldset>	
			</td>	
		</tr>		 	 
	</table>
</div>
