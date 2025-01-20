<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JHT
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)			 						 
-----------------------------------------------------------------------------*/
$sql = "select
            kode_manfaat, no_urut, to_char(a.tgl_saldo_awaltahun,'dd/mm/yyyy') tgl_saldo_awaltahun,
            a.nom_saldo_awaltahun,
            to_char(a.tgl_pengembangan_estimasi,'dd/mm/yyyy') tgl_pengembangan_estimasi,
            a.nom_pengembangan_estimasi, a.nom_manfaat_sudahdiambil, a.pengambilan_ke
        from sijstk.pn_klaim_manfaat_detil a
        where kode_klaim = '$ls_kode_klaim'
        and kode_manfaat = '18'
        and no_urut = 1 ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_jhtinput_kode_manfaat 	 				 		= $row["KODE_MANFAAT"];
$ls_jhtinput_no_urut 	 				 					= $row["NO_URUT"];
$ld_jhtinput_tgl_saldo_awaltahun 				= $row["TGL_SALDO_AWALTAHUN"];
$ln_jhtinput_nom_saldo_awaltahun 	 			= $row["NOM_SALDO_AWALTAHUN"];
$ld_jhtinput_tgl_pengembangan_estimasi 	= $row["TGL_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_pengembangan_estimasi 	= $row["NOM_PENGEMBANGAN_ESTIMASI"];
$ln_jhtinput_nom_manfaat_sudahdiambil 	= $row["NOM_MANFAAT_SUDAHDIAMBIL"];	
$ln_jhtinput_pengambilan_ke 						= $row["PENGAMBILAN_KE"];	 	 

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
        where kode_klaim = '$ls_kode_klaim'
        and exists
        (
            select null from sijstk.pn_klaim_manfaat_detil
            where kode_klaim = a.kode_klaim
            and kode_tipe_penerima = a.kode_tipe_penerima
            and kode_manfaat = '$ls_jhtinput_kode_manfaat'    
        )
        and rownum = 1";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_jhtinput_kode_tipe_penerima 	= $row["KODE_TIPE_PENERIMA"];
$ls_jhtinput_kode_hubungan 				= $row["KODE_HUBUNGAN"];
$ls_jhtinput_ket_hubungan_lainnya	= $row["KET_HUBUNGAN_LAINNYA"];
$ls_jhtinput_no_urut_keluarga 		= $row["NO_URUT_KELUARGA"];
$ls_jhtinput_nomor_identitas 			= $row["NOMOR_IDENTITAS"];
$ls_jhtinput_nama_pemohon 				= $row["NAMA_PEMOHON"];
$ls_jhtinput_tempat_lahir 				= $row["TEMPAT_LAHIR"];
$ld_jhtinput_tgl_lahir 						= $row["TGL_LAHIR"];
$ls_jhtinput_jenis_kelamin 				= $row["JENIS_KELAMIN"];
$ls_jhtinput_golongan_darah				= $row["GOLONGAN_DARAH"];
$ls_jhtinput_alamat 							= $row["ALAMAT"];
$ls_jhtinput_rt 									= $row["RT"];
$ls_jhtinput_rw 									= $row["RW"];
$ls_jhtinput_kode_kelurahan 			= $row["KODE_KELURAHAN"];
$ls_jhtinput_nama_kelurahan 			= $row["NAMA_KELURAHAN"];
$ls_jhtinput_kode_kecamatan 			= $row["KODE_KECAMATAN"];
$ls_jhtinput_nama_kecamatan 			= $row["NAMA_KECAMATAN"];
$ls_jhtinput_kode_kabupaten 			= $row["KODE_KABUPATEN"];
$ls_jhtinput_nama_kabupaten 			= $row["NAMA_KABUPATEN"];
$ls_jhtinput_kode_pos 						= $row["KODE_POS"];
$ls_jhtinput_telepon_area 				= $row["TELEPON_AREA"];
$ls_jhtinput_telepon 							= $row["TELEPON"];
$ls_jhtinput_telepon_ext 					= $row["TELEPON_EXT"];
$ls_jhtinput_handphone 						= $row["HANDPHONE"];
$ls_jhtinput_email 								= $row["EMAIL"];
$ls_jhtinput_npwp 								= $row["NPWP"];
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
	
  function fl_js_jhtinput_kode_tipe_penerima() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
							
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_jhtinput_kode_hubungan").style.display = 'none';
      window.document.getElementById("jhtinput_kode_hubungan").value = "T";	
			window.document.getElementById("jhtinput_ket_hubungan_lainnya").value = "";
			window.document.getElementById("jhtinput_no_urut_keluarga").value = "";	
    }
  }
	
  function fl_js_jhtinput_ahliwaris_lain() 
  { 
		var	v_kode_tipe_penerima = window.document.getElementById('jhtinput_kode_tipe_penerima').value;
		var	v_kode_hubungan = window.document.getElementById('jhtinput_kode_hubungan').value;
    
    if (v_kode_tipe_penerima =="AW" && v_kode_hubungan=="0") //Ahli Waris Lainnya ---------
    {
		 	window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'block'; 
    }else
    {
      window.document.getElementById("span_jhtinput_ket_hubungan_lainnya").style.display = 'none'; 	
    } 	
  }						
</script>	

<script type="text/javascript">
	var curr_jhtinput_kode_hubungan =<?php echo ($ls_jhtinput_kode_hubungan=='') ? 'false' : "'".$ls_jhtinput_kode_hubungan."'"; ?>;
</script>	
		
<div id="formKiri" style="width:955px;">
  <fieldset><legend>Input Data Klaim JHT</legend>															
    <div class="form-row_kiri">
    <label style = "text-align:right;"><i><font color="#009999">Saldo JHT :</font></i></label>	    				
    </div>
    <div class="form-row_kanan">
    <label style = "text-align:right;"><i><font color="#009999">Penerima Manfaat :</font></i></label>				
      <input type="text" name="temp1" size="45" style="border-width: 0;text-align:left" readonly>
    </div>											
    <div class="clear"></div>

    <div class="form-row_kanan">
    <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
			<select size="1" id="jhtinput_kode_tipe_penerima" name="jhtinput_kode_tipe_penerima" value="<?=$ls_jhtinput_kode_tipe_penerima;?>" tabindex="1" class="select_format" onChange="fl_js_jhtinput_kode_tipe_penerima();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
      <option value="">- Tipe Penerima --</option>
      <?
			if ($ls_status_submit_agenda=="T")
			{
        $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from sijstk.pn_kode_manfaat_eligibility a, sijstk.pn_kode_tipe_penerima b ".
               "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
               "and a.kode_manfaat = '$ls_jhtinput_kode_manfaat' ".
               "and nvl(a.status_nonaktif,'T')= 'T' ".
               "order by b.no_urut";			
			}else
			{
        $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima from sijstk.pn_kode_manfaat_eligibility a, sijstk.pn_kode_tipe_penerima b ".
               "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
               "and a.kode_manfaat = '$ls_jhtinput_kode_manfaat' ".
							 "and a.kode_tipe_penerima = '$ls_jhtinput_kode_tipe_penerima' ";
			}			 
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
      echo "<option ";
      if (($row["KODE_TIPE_PENERIMA"]==$ls_jhtinput_kode_tipe_penerima && strlen($row["KODE_TIPE_PENERIMA"])==strlen($ls_jhtinput_kode_tipe_penerima))) { echo " selected"; }
      echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
      }
      ?>
      </select>
			<input type="hidden" id="jhtinput_kode_tipe_penerima_old" name="jhtinput_kode_tipe_penerima_old" value="<?=$ls_jhtinput_kode_tipe_penerima;?>">
			<input type="hidden" id="jhtinput_kode_manfaat" name="jhtinput_kode_manfaat" value="<?=$ls_jhtinput_kode_manfaat;?>">
			<input type="hidden" id="jhtinput_no_urut" name="jhtinput_no_urut" value="<?=$ls_jhtinput_no_urut;?>"> 
		</div>		    																																				
  	<div class="clear"></div>			

    <span id="span_jhtinput_kode_hubungan" style="display:none;">
      <div class="form-row_kanan">
      <label style = "text-align:right;">Ahli Waris *</label>		 	    				
  			<select size="1" id="jhtinput_kode_hubungan" name="jhtinput_kode_hubungan" value="<?=$ls_jhtinput_kode_hubungan;?>" tabindex="2" class="select_format" onChange="fl_js_jhtinput_ahliwaris_lain(); f_ajax_jhtinput_val_kode_hubungan();" <?=($ls_status_submit_agenda =="Y")? " style=\"width:275px;background-color:#F5F5F5\"" : " style=\"width:275px;background-color:#ffff99\"";?>>
        <option value="">-- Pilih --</option>
        <?
  			if ($ls_status_submit_agenda=="T")
  			{
          $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where nvl(aktif,'T') = 'Y' and kode_hubungan <> 'T' ".
							 	 "and kode_hubungan <> decode((select jenis_kelamin from kn.vw_kn_tk where kode_tk='$ls_kode_tk' and rownum=1),'L','S','P','I','') ".
								 "order by urutan";			
  			}else
  			{
          $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan='$ls_jhtinput_kode_hubungan'";
  			}
        $DB->parse($sql);
        $DB->execute();
        while($row = $DB->nextrow())
        {
          echo "<option ";
          if ($row["KODE_HUBUNGAN"]==$ls_jhtinput_kode_hubungan && strlen($ls_jhtinput_kode_hubungan)==strlen($row["KODE_HUBUNGAN"])){ echo " selected"; }
          echo " value=\"".$row["KODE_HUBUNGAN"]."\">".$row["NAMA_HUBUNGAN"]."</option>";
        }
        ?>
        </select>
				<input type="hidden" id="jhtinput_no_urut_keluarga" name="jhtinput_no_urut_keluarga" value="<?=$ls_jhtinput_no_urut_keluarga;?>" size="2" maxlength="3" readonly class="disabled">        				
      </div>																																									
      <div class="clear"></div>
    </span>

		<span id="span_jhtinput_ket_hubungan_lainnya" style="display:none;">
      <div class="form-row_kanan">
      <label style = "text-align:right;">Ket Lain-lain *</label>		
				<input type="text" id="jhtinput_ket_hubungan_lainnya" name="jhtinput_ket_hubungan_lainnya" value="<?=$ls_jhtinput_ket_hubungan_lainnya;?>" tabindex="3" size="46" maxlength="300" <?=($ls_status_submit_agenda =="Y")? " style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>
      </div>																																									
      <div class="clear"></div>			
		</span>
											
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
      <input type="text" id="jhtinput_tgl_saldo_awaltahun" name="jhtinput_tgl_saldo_awaltahun" value="<?=$ld_jhtinput_tgl_saldo_awaltahun;?>" size="10" readonly class="disabled">
      <input type="text" id="jhtinput_nom_saldo_awaltahun" name="jhtinput_nom_saldo_awaltahun" value="<?=number_format((float)$ln_jhtinput_nom_saldo_awaltahun,2,".",",");?>" size="30" maxlength="20" readonly class="disabled" style="text-align:right;">                					
    </div>								
		<div class="form-row_kanan">
    <label style = "text-align:right;">Nama &nbsp;&nbsp;*</label>
			<input type="text" id="jhtinput_nama_pemohon" name="jhtinput_nama_pemohon" value="<?=$ls_jhtinput_nama_pemohon;?>" tabindex="4" size="46" maxlength="100" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
    </div>																																													
  	<div class="clear"></div>

    <div class="form-row_kiri">
    <label  style = "text-align:right;">Saldo JHT Estimasi &nbsp;</label>
      <input type="text" id="jhtinput_tgl_pengembangan_estimasi" name="jhtinput_tgl_pengembangan_estimasi" value="<?=$ld_jhtinput_tgl_pengembangan_estimasi;?>" size="10" readonly class="disabled">
      <input type="text" id="jhtinput_nom_pengembangan_estimasi" name="jhtinput_nom_pengembangan_estimasi" value="<?=number_format((float)$ln_jhtinput_nom_pengembangan_estimasi,2,".",",");?>" size="30" maxlength="20" readonly class="disabled" style="text-align:right;">                					
    </div>		
    <div class="form-row_kanan">
    <label style = "text-align:right;">Tempat, Tgl Lahir &nbsp; *</label>	    				
      <input type="text" id="jhtinput_tempat_lahir" name="jhtinput_tempat_lahir" value="<?=$ls_jhtinput_tempat_lahir;?>" tabindex="5" size="26" maxlength="50" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>,&nbsp;
      <input type="text" id="jhtinput_tgl_lahir" name="jhtinput_tgl_lahir" value="<?=$ld_jhtinput_tgl_lahir;?>" tabindex="6" size="12" maxlength="10" onblur="convert_date(jhtinput_tgl_lahir);" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
      <?
      if ($ls_status_submit_agenda=="T")
      {
      ?>      								
      <input id="btn_jhtinput_tgl_lahir" type="image" align="top" onclick="return showCalendar('jhtinput_tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />							
      <?
      }
      ?> 			
    </div>																													
    <div class="clear"></div>

    <div class="form-row_kanan">
    <label style = "text-align:right;">Jenis Kelamin *</label>		 	    				
			<select size="1" id="jhtinput_jenis_kelamin" name="jhtinput_jenis_kelamin" value="<?=$ls_jhtinput_jenis_kelamin;?>" tabindex="7" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:105px;background-color:#F5F5F5\"" : " style=\"width:105px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <?
			if ($ls_status_submit_agenda=="T")
			{
       	$sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'JNSKELAMIN' and nvl(aktif,'T')='Y' order by seq";			
			}else
			{
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'JNSKELAMIN' and kode='$ls_jhtinput_jenis_kelamin'";
			}
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
        echo "<option ";
        if ($row["KODE"]==$ls_jhtinput_jenis_kelamin && strlen($ls_jhtinput_jenis_kelamin)==strlen($row["KODE"])){ echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      }
      ?>
      </select>
			&nbsp;
    	Gol. Darah	 	    				
			<select size="1" id="jhtinput_golongan_darah" name="jhtinput_golongan_darah" value="<?=$ls_jhtinput_golongan_darah;?>" tabindex="7" class="select_format" <?=($ls_status_submit_agenda =="Y")? " style=\"width:103px;background-color:#F5F5F5\"" : " style=\"width:103px;background-color:#ffff99\"";?>>
      <option value="">-- Pilih --</option>
      <?
			if ($ls_status_submit_agenda=="T")
			{
       	$sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'GOLDARAH' and nvl(aktif,'T')='Y' order by seq";			
			}else
			{
        $sql = "select kode, keterangan from sijstk.ms_lookup where tipe = 'GOLDARAH' and kode='$ls_jhtinput_jenis_kelamin'";
			}
      $DB->parse($sql);
      $DB->execute();
      while($row = $DB->nextrow())
      {
        echo "<option ";
        if ($row["KODE"]==$ls_jhtinput_golongan_darah && strlen($ls_jhtinput_golongan_darah)==strlen($row["KODE"])){ echo " selected"; }
        echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
      }
      ?>
      </select>			
		</div>																																									
    <div class="clear"></div>
												
		</br>
												
    <div class="form-row_kiri">
    <label  style = "text-align:right;">Saldo JHT sdh Diambil</label>
      <input type="text" id="jhtinput_nom_manfaat_sudahdiambil" name="jhtinput_nom_manfaat_sudahdiambil" value="<?=number_format((float)$ln_jhtinput_nom_manfaat_sudahdiambil,2,".",",");?>" size="43" maxlength="20" readonly class="disabled" style="text-align:right;">                					
    </div>		
    <div class="form-row_kanan">
    <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;&nbsp;*</label>		 	    				
      <input type="text" id="jhtinput_alamat" name="jhtinput_alamat" value="<?=$ls_jhtinput_alamat;?>" tabindex="8" size="46" maxlength="300" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
		</div>																																										
  	<div class="clear"></div>

    <div class="form-row_kiri">
    <label  style = "text-align:right;">Pengambilan JHT ke- &nbsp;</label>
      <input type="text" id="jhtinput_pengambilan_ke" name="jhtinput_pengambilan_ke" value="<?=number_format((float)$ln_jhtinput_pengambilan_ke,0,".",",");?>" size="43" maxlength="20" readonly class="disabled" style="text-align:right;">                					
    </div>
    <div class="form-row_kanan">
    <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp&nbsp;&nbsp;&nbsp;</label>		 	    				
      <input type="text" id="jhtinput_rt" name="jhtinput_rt" value="<?=$ls_jhtinput_rt;?>" tabindex="9" size="15" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_rt');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      /
      <input type="text" id="jhtinput_rw" name="jhtinput_rw" value="<?=$ls_jhtinput_rw;?>" tabindex="10" size="20" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_rw');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>		
    	&nbsp;<input type="text" name="temp1" size="2" style="border-width: 0;text-align:left" readonly>
		</div>																																										
  	<div class="clear"></div>

			
    <div class="form-row_kanan">
    <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label> 	    				
      <input type="text" id="jhtinput_kode_pos" name="jhtinput_kode_pos" value="<?=$ls_jhtinput_kode_pos;?>" tabindex="11" size="35" maxlength="10" readonly <?=($ls_status_submit_agenda =="Y")? " class=disabled" : " style=\"background-color:#ffff99\"";?>>
			<?
			if ($ls_status_submit_agenda=="T")
			{
			?>      								
				<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_lov_pos.php?p=pn5001_tabdataklaim_jht.php&a=formreg&b=jhtinput_kode_kelurahan&c=jhtinput_nama_kelurahan&d=jhtinput_kode_kecamatan&e=jhtinput_nama_kecamatan&f=jhtinput_kode_kabupaten&g=jhtinput_nama_kabupaten&h=jhtinput_kode_propinsi&j=jhtinput_nama_propinsi&k=jhtinput_kode_pos','',800,500,1)">							
      <?
			}
			?>
			<img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
			&nbsp;&nbsp;<input type="text" name="temp1" size="3" style="border-width: 0;text-align:left" readonly>				           											
    </div>																																																	
    <div class="clear"></div>
			
    <div class="form-row_kanan">
    <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
      <input type="text" id="jhtinput_nama_kelurahan" name="jhtinput_nama_kelurahan" value="<?=$ls_jhtinput_nama_kelurahan;?>" size="40" readonly class="disabled">
			<input type="hidden" id="jhtinput_kode_kelurahan" name="jhtinput_kode_kelurahan" value="<?=$ls_jhtinput_kode_kelurahan;?>" size="8" maxlength="20" readonly class="disabled">      
			&nbsp;<input type="text" name="temp1" size="2" style="border-width: 0;text-align:left" readonly>
    </div>																																																	
    <div class="clear"></div>
				
    <div class="form-row_kanan">
    <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;&nbsp;</label>		 	    				
      <input type="text" id="jhtinput_nama_kecamatan" name="jhtinput_nama_kecamatan" value="<?=$ls_jhtinput_nama_kecamatan;?>" size="40" readonly class="disabled">
      <input type="hidden" id="jhtinput_kode_kecamatan" name="jhtinput_kode_kecamatan" value="<?=$ls_jhtinput_kode_kecamatan;?>" size="8" maxlength="10">			
			&nbsp;<input type="text" name="temp1" size="2" style="border-width: 0;text-align:left" readonly>
    </div>
		<div class="clear"></div>

    <div class="form-row_kanan">
    <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
      <input type="text" id="jhtinput_nama_kabupaten" name="jhtinput_nama_kabupaten" value="<?=$ls_jhtinput_nama_kabupaten;?>" size="40" readonly class="disabled">
			<input type="hidden" id="jhtinput_kode_kabupaten" name="jhtinput_kode_kabupaten" value="<?=$ls_jhtinput_kode_kabupaten;?>" size="25" maxlength="20">      
      <input type="hidden" id="jhtinput_kode_propinsi" name="jhtinput_kode_propinsi" value="<?=$ls_jhtinput_kode_propinsi;?>" size="8" readonly class="disabled">
      <input type="hidden" id="jhtinput_nama_propinsi" name="jhtinput_nama_propinsi" value="<?=$ls_jhtinput_nama_propinsi;?>" size="32" readonly class="disabled">
			&nbsp;<input type="text" name="temp1" size="2" style="border-width: 0;text-align:left" readonly>											
    </div>
    <div class="clear"></div>		
																										
		</br>
		
    <div class="form-row_kanan">
    <label style = "text-align:right;">Email &nbsp;&nbsp;</label>		 	    				
			<input type="text" id="jhtinput_email" name="jhtinput_email" value="<?=$ls_jhtinput_email;?>" tabindex="12" size="46" maxlength="200" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
			<!--&nbsp;<input type="text" name="temp1" size="2" style="border-width: 0;text-align:left" readonly>-->
    </div>
		<div class="clear"></div>

    <div class="form-row_kanan">
    <label style = "text-align:right;">No. Telp &nbsp;</label>	    				
      <input type="text" id="jhtinput_telepon_area" name="jhtinput_telepon_area" tabindex="13" value="<?=$ls_jhtinput_telepon_area;?>" size="5" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_telepon_area');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      <input type="text" id="jhtinput_telepon" name="jhtinput_telepon" tabindex="14" value="<?=$ls_jhtinput_telepon;?>" size="24" maxlength="20" onblur="fl_js_val_numeric_jht('jhtinput_telepon');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
      &nbsp;ext.
      <input type="text" id="jhtinput_telepon_ext" name="jhtinput_telepon_ext" tabindex="15" value="<?=$ls_jhtinput_telepon_ext;?>" size="5" maxlength="5" onblur="fl_js_val_numeric_jht('jhtinput_telepon_ext');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
			<!--&nbsp;<input type="text" name="temp1" size="2" style="border-width: 0;text-align:left" readonly>--> 						
    </div>
    <div class="clear"></div>

    <div class="form-row_kanan">
    <label style = "text-align:right;">Handphone &nbsp;&nbsp;</label>		 	    				
    	<input type="text" id="jhtinput_handphone" name="jhtinput_handphone" tabindex="16" value="<?=$ls_jhtinput_handphone;?>" size="46" maxlength="15" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
			<!--&nbsp;<input type="text" name="temp1" size="1" style="border-width: 0;text-align:left" readonly>-->
    </div>																																																																																														
    <div class="clear"></div>

    <div class="form-row_kanan">
    <label style = "text-align:right;">No. Identitas &nbsp;</label>
      <input type="text" id="jhtinput_nomor_identitas" name="jhtinput_nomor_identitas" value="<?=$ls_jhtinput_nomor_identitas;?>" size="46" maxlength="30" tabindex="17" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : "";?>>
		</div>																																									
    <div class="clear"></div>

    <div class="form-row_kanan">
    <label style = "text-align:right;">NPWP &nbsp; *</label>
      <input type="text" id="jhtinput_npwp" name="jhtinput_npwp" value="<?=$ls_jhtinput_npwp;?>" size="41" maxlength="15" tabindex="18" onblur="fl_js_val_numeric_jht('jhtinput_npwp');" <?=($ls_status_submit_agenda =="Y")? " readonly class=disabled" : " style=\"background-color:#ffff99\"";?>>
			&nbsp;<input type="text" name="temp1" size="1" style="border-width: 0;text-align:left" readonly>
    </div>																																									
    <div class="clear"></div>

    <?
      echo "<script type=\"text/javascript\">fl_js_jhtinput_kode_tipe_penerima();</script>";
      echo "<script type=\"text/javascript\">fl_js_jhtinput_ahliwaris_lain();</script>";
    ?>						
		</br>										 	    																						  
  </fieldset>						
</div>
