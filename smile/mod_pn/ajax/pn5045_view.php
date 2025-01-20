<?
if ($ls_kode_klaim!="" && $ln_no_konfirmasi!="")
{
  $sql = "select ".
          "     b.kode_klaim, b.no_urut_keluarga, b.kode_hubungan, (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan=b.kode_hubungan) nama_hubungan, ".
          "		  b.nama_lengkap, b.no_kartu_keluarga, b.kpj_tertanggung, b.pekerjaan, b.nomor_identitas, b.jenis_identitas, nvl(b.status_valid_identitas,'T') status_valid_identitas,  ". 
          "     b.tempat_lahir, to_char(b.tgl_lahir,'dd/mm/yyyy') tgl_lahir, b.jenis_kelamin, b.golongan_darah, b.status_kawin, ".
          "		 (select keterangan from sijstk.ms_lookup where tipe = 'JNSKELAMIN' and kode = b.jenis_kelamin) nama_jenis_kelamin, ".
          "			b.alamat, b.rt, b.rw, b.kode_pos, ".
					"     (		'KELURAHAN '|| ".
          "     		(select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = b.kode_kelurahan and kode_kecamatan=b.kode_kecamatan and kode_pos = b.kode_pos and rownum=1) ".
          "     		||' KECAMATAN '||(select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = b.kode_kecamatan) ".
          "     		||' KABUPATEN '||(select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = b.kode_kabupaten) ".
          "     		||' PROPINSI '||(select y.nama_propinsi from sijstk.ms_kabupaten x, sijstk.ms_propinsi y where x.kode_propinsi = y.kode_propinsi and kode_kabupaten = b.kode_kabupaten) ".
          "     ) ket_alamat_lanjutan,  ".
					"     b.telepon_area, b.telepon, b.telepon_ext, b.fax_area, b.fax, handphone, b.email, b.npwp, ".
          "     b.nama_penerima, b.bank_penerima, b.kode_bank_penerima, b.id_bank_penerima, b.no_rekening_penerima, b.nama_rekening_penerima, ". 
          "     nvl(b.status_valid_rekening_penerima,'T') status_valid_rekening_penerima, b.kode_bank_pembayar,  ". 
					"     nvl(b.status_rekening_sentral,'T') status_rekening_sentral, b.kantor_rekening_sentral, b.metode_transfer, b.kode_negara, ".
          "     b.kode_kondisi_terakhir, b.tgl_kondisi_terakhir, b.status_layak, ". 
          "     b.kode_penerima_berkala, b.keterangan, to_char(a.blth_awal,'mm/yyyy') blth_awal, to_char(a.blth_akhir,'mm/yyyy') blth_akhir ".    
          "from sijstk.pn_klaim_berkala a, sijstk.pn_klaim_penerima_berkala b ".
          "where a.kode_klaim = b.kode_klaim(+) and a.kode_penerima_berkala = b.kode_penerima_berkala(+) ".
          "and a.kode_klaim = '$ls_kode_klaim' and a.no_konfirmasi = '$ln_no_konfirmasi' ".
          "and rownum = 1 ";
  $DB->parse($sql);
  $DB->execute();
  $data = $DB->nextrow();
  $ls_kode_hubungan			 	  = $data["KODE_HUBUNGAN"];
  $ls_nama_hubungan			 	  = $data["NAMA_HUBUNGAN"];
  $ls_kode_penerima_berkala	= $data["KODE_PENERIMA_BERKALA"];	
  $ls_kode_penerima_berkala_induk	= $data["KODE_PENERIMA_BERKALA"];	
  $ln_no_urut_keluarga	 		= $data["NO_URUT_KELUARGA"];
  $ls_nama_lengkap					= $data["NAMA_LENGKAP"];
  $ls_no_kartu_keluarga			= $data["NO_KARTU_KELUARGA"];
  $ls_tempat_lahir					= $data["TEMPAT_LAHIR"];
  $ld_tgl_lahir							= $data["TGL_LAHIR"];
  $ls_jenis_kelamin					= $data["JENIS_KELAMIN"];
  $ls_nama_jenis_kelamin		= $data["NAMA_JENIS_KELAMIN"];
  $ls_golongan_darah				= $data["GOLONGAN_DARAH"];
	$ls_kpj_tertanggung				= $data["KPJ_TERTANGGUNG"];
	$ls_nomor_identitas				= $data["NOMOR_IDENTITAS"];
	$ls_jenis_identitas				= $data["JENIS_IDENTITAS"];
	$ls_status_valid_identitas = $data["STATUS_VALID_IDENTITAS"];
	
	$ls_alamat								= $data["ALAMAT"];
	$ls_rt										= $data["RT"];
	$ls_rw										= $data["RW"];
	$ls_kode_pos							= $data["KODE_POS"];
	$ls_ket_alamat_lanjutan		= $data["KET_ALAMAT_LANJUTAN"];
  $ls_telepon_area					= $data["TELEPON_AREA"];
  $ls_telepon								= $data["TELEPON"];
  $ls_telepon_ext						= $data["TELEPON_EXT"];
  $ls_handphone							= $data["HANDPHONE"];
  $ls_email 								= $data["EMAIL"];
  $ls_npwp									= $data["NPWP"];
  $ls_keterangan 						= $data["KETERANGAN"];

	$ls_nama_penerima					= $data['NAMA_PENERIMA'];	
  $ls_nama_bank_penerima		= $data['BANK_PENERIMA'];	 
	$ls_kode_bank_penerima		= $data['KODE_BANK_PENERIMA'];	 
	$ls_id_bank_penerima			= $data['ID_BANK_PENERIMA'];	 
  $ls_no_rekening_penerima	= $data['NO_REKENING_PENERIMA'];
  $ls_nama_rekening_penerima	= $data['NAMA_REKENING_PENERIMA'];
	$ls_status_valid_rekening_penerima	= $data['STATUS_VALID_REKENING_PENERIMA'];
  $ls_kode_bank_pembayar		= $data['KODE_BANK_PEMBAYAR'];	
	$ls_status_rekening_sentral		= $data['STATUS_REKENING_SENTRAL'];
	$ls_kantor_rekening_sentral		= $data['KANTOR_REKENING_SENTRAL'];
	$ls_metode_transfer						= $data['METODE_TRANSFER'];
	$ls_kode_negara						= $data['KODE_NEGARA'];
	$ld_blth_awal							= $data['BLTH_AWAL'];
	$ld_blth_akhir						= $data['BLTH_AKHIR'];
	
	//ambil kantor yang melakukan pembayaran -------------------------------------
  $sql = "select ".
         "		case when a.kode_tipe = '5' then a.kode_kantor_induk ".
         "    when a.kode_tipe = '4' then a.kode_kantor ".
         "    else ".
         "        a.kode_kantor ".
         "    end kantor_pembayar ".
         "from sijstk.ms_kantor a ". 
         "where kode_kantor = '$ls_kode_kantor' ";
  $DB->parse($sql);
  $DB->execute();
  $data = $DB->nextrow();
  $ls_kantor_pembayar = $data["KANTOR_PEMBAYAR"];	
	
	//cek apakah pembayaran menggunakan rekening sentralisasi --------------------
  $sql = "select nvl(status_rekening_sentral,'T') as status_rekening_sentral from sijstk.ms_kantor ".
  		 	 "where kode_kantor = '$ls_kantor_pembayar'";
  $DB->parse($sql);
  $DB->execute();
  $data = $DB->nextrow();
  $ls_status_rekening_sentral	= $data["STATUS_REKENING_SENTRAL"];		 
}							
?>

<tr>
  <td colspan="10">
    <table width="1200px" border="0">
      <tr>
        <!-- Informasi Penerima Manfaat Sebelumnya ------------------------------->	
        <td width="40%" valign="top" align="center" >
          <fieldset style="height:400px;"><legend><b><i><font color="#009999">Informasi Penerima Manfaat Periode Berkala <?=$ld_blth_awal;?> s/d <?=$ld_blth_akhir;?></font></i></b></legend>
            </br>
						
						<div class="form-row_kiri">
            <label style = "text-align:right;">Nama Lengkap</label>
            	<input type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$ls_nama_lengkap;?>" style="width:250px;" readonly class="disabled">
							<input type="hidden" id="no_urut_keluarga" name="no_urut_keluarga" value="<?=$ln_no_urut_keluarga;?>">
            </div>																																																		
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Tempat dan Tgl Lahir</label>
              <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" style="width:160px;" readonly class="disabled">
              <input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" style="width:90px;" readonly class="disabled">
            </div>
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Jns Klmin-Hub-No.KK</label>
              <input type="text" id="nama_jenis_kelamin" name="nama_jenis_kelamin" value="<?=$ls_nama_jenis_kelamin;?>" style="width:70px;" readonly class="disabled">
              <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>">
							<input type="text" id="nama_hubungan" name="nama_hubungan" value="<?=$ls_nama_hubungan;?>" style="width:80px;" readonly class="disabled">
              <input type="hidden" id="kode_hubungan" name="kode_hubungan" value="<?=$ls_kode_hubungan;?>">
              <input type="text" id="no_kartu_keluarga" name="no_kartu_keluarga" value="<?=$ls_no_kartu_keluarga;?>" style="width:83px;" readonly class="disabled">
            </div>
            <div class="clear"></div>
						
						</br>
									
            <div class="form-row_kiri">
            <label style = "text-align:right;">Alamat *</label>
        			<input type="text" id="alamat" name="alamat" value="<?=$ls_alamat;?>" tabindex="1" maxlength="300" style="background-color:#ffff99;width:250px;">	
            </div>																																									
          	<div class="clear"></div>
  
            <div class="form-row_kiri">
            <label style = "text-align:right;">RT/RW - Kode Pos</label>		 	    				
              <input type="text" id="rt" name="rt" value="<?=$ls_rt;?>" tabindex="2" maxlength="5" onblur="fl_js_val_numeric('rt');" style="width:50px;">
              /
              <input type="text" id="rw" name="rw" value="<?=$ls_rw;?>" tabindex="3" maxlength="5" onblur="fl_js_val_numeric('rw');" style="width:50px;">
							-
							<input type="text" id="kode_pos" name="kode_pos" value="<?=$ls_kode_pos;?>" tabindex="4" maxlength="10" readonly  style="background-color:#ffff99;width:90px;">
            </div>																																																	
            <div class="clear"></div>

            <div class="form-row_kiri">
            <label style = "text-align:right;">&nbsp;</label>
            	<textarea cols="255" rows="1" style="width:250px;height:28px;background-color:#F5F5F5;" id="ket_alamat_lanjutan" name="ket_alamat_lanjutan" readonly onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_ket_alamat_lanjutan;?></textarea>   					
            	<input type="hidden" id="nama_kelurahan" name="nama_kelurahan" value="<?=$ls_nama_kelurahan;?>" readonly class="disabled" style="width:250px;">
            	<input type="hidden" id="kode_kelurahan" name="kode_kelurahan" value="<?=$ls_kode_kelurahan;?>">
							<input type="hidden" id="nama_kecamatan" name="nama_kecamatan" value="<?=$ls_nama_kecamatan;?>" readonly class="disabled" style="width:115px;">
							<input type="hidden" id="kode_kecamatan" name="kode_kecamatan" value="<?=$ls_kode_kecamatan;?>">
							<input type="hidden" id="nama_kabupaten" name="nama_kabupaten" value="<?=$ls_nama_kabupaten;?>" readonly class="disabled" style="width:110px;">
							<input type="hidden" id="kode_kabupaten" name="kode_kabupaten" value="<?=$ls_kode_kabupaten;?>">
        			<input type="hidden" id="kode_propinsi" name="kode_propinsi" value="<?=$ls_kode_propinsi;?>">
              <input type="hidden" id="nama_propinsi" name="nama_propinsi" value="<?=$ls_nama_propinsi;?>">
						</div>																	
            <div class="clear"></div>
			
            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Telp</label>	    				
              <input type="text" id="telepon_area" name="telepon_area" tabindex="5" value="<?=$ls_telepon_area;?>" maxlength="5" onblur="fl_js_val_numeric('telepon_area');" style="width:30px;">
              <input type="text" id="telepon" name="telepon" tabindex="6" value="<?=$ls_telepon;?>" maxlength="20" onblur="fl_js_val_numeric('telepon');" style="width:120px;">
              &nbsp;ext.
              <input type="text" id="telepon_ext" name="telepon_ext" tabindex="7" value="<?=$ls_telepon_ext;?>" maxlength="5" onblur="fl_js_val_numeric('telepon_ext');" style="width:30px;"> 						
            </div>																																																				
        		<div class="clear"></div>
        		
            <div class="form-row_kiri">
            <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
        			<input type="text" id="handphone" name="handphone" tabindex="8" value="<?=$ls_handphone;?>" onblur="fl_js_val_numeric('handphone');" maxlength="15" style="width:230px;">
            </div>																																																																																																		
            <div class="clear"></div>
  
            <div class="form-row_kiri">
            <label style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
        			<input type="text" id="email" name="email" tabindex="9" value="<?=$ls_email;?>" onblur="this.value=this.value.toLowerCase();fl_js_val_email('email');" maxlength="200" style="width:230px;">
            </div>																																																																																																				
            <div class="clear"></div>
  										
            <div class="form-row_kiri">
            <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
        			<input type="text" id="npwp" name="npwp" tabindex="10" value="<?=$ls_npwp;?>" onblur="fl_js_val_npwp('npwp');" maxlength="15" style="background-color:#ffff99;width:220px;">
            </div>																																																																																																												
            <div class="clear"></div>					

            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Identitas </label>
              <input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" maxlength="30" tabindex="12" style="width:210px;">
  						<input type="hidden" id="status_valid_identitas" name="status_valid_identitas" value="<?=$ls_status_valid_identitas;?>"> 	
							<input type="hidden" id="jenis_identitas" name="jenis_identitas" value="<?=$ls_jenis_identitas;?>">
							<input type="hidden" id="kpj_tertanggung" name="kpj_tertanggung" value="<?=$ls_kpj_tertanggung;?>">
						</div>																																																																																							
          	<div class="clear"></div>
					  
            <div class="form-row_kiri">
            <label style = "text-align:right;">Keterangan &nbsp;</label>
            	<textarea cols="255" rows="1" style="width:200px;height:14px;" id="keterangan" name="keterangan" tabindex="13" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
            </div>																	
            <div class="clear"></div>
            
            </br>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">&nbsp; </label>
              <?
              if ($ls_nomor_identitas=="")
              {
              ?>
              <input id="tk_foto" name="tk_foto" type="image" src="../../images/nopic.png" style="text-align:center;height: 75px !important; width: 70px !important;"/>
              <?
              }else
              {
              ?>
              <img id="tk_foto" src="../../mod_kn/ajax/kngetfoto.php?dataid=<?=$ls_nomor_identitas;?>" style="height: 75px !important; width: 70px !important;"/>
              <?
              }
              ?>
            </div>																																																																																							
            <div class="clear"></div>
          																																				
          </fieldset>	 					
        </td>	
        
        <!-- Informasi Penerima Manfaat Sebelumnya ------------------------------->	
        <td width="60%" valign="top">
          <table width="700" border="0">
            <tr>
              <td>
                <fieldset style="height:165px;"><legend><b><i><font color="#009999">Daftar Pembayaran JP Berkala Periode <?=$ld_blth_awal;?> s/d <?=$ld_blth_akhir;?></font></i></b></legend>
                  <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
                    <tbody>	
                      <tr>
                      	<th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
                      </tr>									
                      <tr>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prg</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ke-</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jml Berkala</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Sudah Dibayar</th>
                        <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
                      </tr>
                      <tr>
                      	<th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                      </tr>				
                      <?							
                      if ($ls_kode_klaim!="")
                      {			
                        $sql = "select a.kode_klaim, a.no_konfirmasi, a.kd_prg, b.nm_prg, a.no_proses, to_char(a.blth_proses,'mm/yyyy') blth_proses, ".
                                "			 a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala, ".
                                "			 (select sum(nvl(nom_pembayaran,0)) from sijstk.pn_klaim_pembayaran_berkala where kode_klaim = a.kode_klaim and no_konfirmasi=a.no_konfirmasi and no_proses = a.no_proses and nvl(status_batal,'T')='T') nom_dibayar ".
                                "from sijstk.pn_klaim_berkala_rekap a, sijstk.ms_prg b ".
                                "where a.kd_prg = b.kd_prg  ".
                                "and a.kode_klaim = '$ls_kode_klaim' ".
                                "and a.no_konfirmasi = '$ln_no_konfirmasi' ".
                                "order by a.no_proses";
                                //echo $sql;
                        $DB->parse($sql);
                        $DB->execute();							              					
                        $i=0;		
                        $ln_dtl =0;	
                        $ln_tot_d_nom_kompensasi  = 0;
                        $ln_tot_d_nom_rapel = 0;
                        $ln_tot_d_nom_berjalan = 0;
                        $ln_tot_d_nom_berkala = 0;						
                        while ($row = $DB->nextrow())
                        {
                         	?>
                          <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
                          <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
                          <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_PROSES'];?></td>
                          <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_PROSES'];?></td>
                          <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERJALAN'],2,".",",");?></td>
                          <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_RAPEL'],2,".",",");?></td>
                          <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_KOMPENSASI'],2,".",",");?></td>
                          <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>	
                          <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_DIBAYAR'],2,".",",");?></td>																		       																			        											
                          <td align="center">
                          <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_manfaatberkalarinci.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5040_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',1100,500,'no')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN </font></a>
                          </td>
                          </tr>
                          <?								    							
                          $i++;//iterasi i
                          $ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
                          $ln_tot_d_nom_rapel  += $row["NOM_RAPEL"];
                          $ln_tot_d_nom_berjalan  += $row["NOM_BERJALAN"];
                          $ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
                          $ln_tot_d_nom_dibayar  += $row["NOM_DIBAYAR"];
                        }	//end while
                        $ln_dtl=$i;
                      }						
                      ?>									             																
                    </tbody>
                    <tr><td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.15), rgba(0,0,0,0));"/></td></tr>
                    <tr>
                      <td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i>
                      <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                      <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                      <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
                      </td>	  		
                      <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berjalan,2,".",",");?></td>
                      <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_rapel,2,".",",");?></td>							
                      <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_kompensasi,2,".",",");?></td>
                      <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
                      <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_dibayar,2,".",",");?></td>
                      <td></td>										        
                    </tr>
									</table>																			
                </fieldset>	
              </td>	
            </tr>

						<?
						if ($ls_status_rekening_sentral=="Y")
						{
						 	 include "../ajax/pn5045_edit_reksentral.php";
						}else
						{
						 	 include "../ajax/pn5045_edit_rekkacab.php";
						}
						?>						
											            
          </table>
        </td>																				
      </tr>
    </table>	
  </td>	
</tr>
<!--
<tr>
  <td colspan="10" style="text-align:center;" valign="top">
		<fieldset style="height:40px;width:1150px;"><legend>&nbsp;</legend>	
		<a href="#" onClick="if(confirm('Apakah anda yakin akan melakukan Submit Data Penerima Manfaat Berkala..?')) doSubmitInformasiPenerima();"><img src="../../images/folder_exec2.png" border="0" valign="top" alt="Tambah" align="absmiddle" style="height:25px;"/>&nbsp;<b>Submit Perubahan Data</b></a> &nbsp;| &nbsp;	
    <a href="#" onClick="refreshParent();"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>&nbsp;<b>Kembali Ke Daftar</b></a>													  																											
  	</fieldset>
	</td>	
</tr>
-->
<?
if (isset($msg))		
{
?>
  <tr>
    <td colspan="10" style="text-align:center;" valign="top">
    <fieldset style="width:1150px;">
    <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
    <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
    </fieldset>	
  	</td>	
  </tr>		
<?
}
?>	