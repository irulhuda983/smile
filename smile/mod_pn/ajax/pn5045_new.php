<?
if ($ls_kode_klaim!="" && $ln_no_konfirmasi_induk!="")
{
  $sql = "select ".
          "     b.kode_klaim, b.no_urut_keluarga, b.kode_hubungan, (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan=b.kode_hubungan) nama_hubungan, ".
          "		  b.nama_lengkap, b.no_kartu_keluarga, b.nomor_identitas, ". 
          "     b.tempat_lahir, to_char(b.tgl_lahir,'dd/mm/yyyy') tgl_lahir, b.jenis_kelamin, b.golongan_darah, b.status_kawin, ".
          "		 (select keterangan from sijstk.ms_lookup where tipe = 'JNSKELAMIN' and kode = b.jenis_kelamin) nama_jenis_kelamin, ".
          "     b.alamat||' RT '||b.rt||' RW '||rw||' KELURAHAN '|| ".
          "     		(select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = b.kode_kelurahan and kode_kecamatan=b.kode_kecamatan and kode_pos = b.kode_pos and rownum=1) ".
          "     		||' KECAMATAN '||(select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = b.kode_kecamatan) ".
          "     		||' KABUPATEN '||(select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = b.kode_kabupaten) ".
          "     		||' PROPINSI '||(select y.nama_propinsi from sijstk.ms_kabupaten x, sijstk.ms_propinsi y where x.kode_propinsi = y.kode_propinsi and kode_kabupaten = b.kode_kabupaten) ".
          "     		||' KODE POS '||b.kode_pos alamat_lengkap,  ".
          "     b.telepon_area, b.telepon, b.telepon_ext,  ".
          "     b.fax_area, b.fax, handphone,  ".
          "     b.email, b.npwp, b.nama_penerima, ". 
          "     b.bank_penerima, b.no_rekening_penerima, b.nama_rekening_penerima, ". 
          "     b.kode_bank_pembayar, b.kpj_tertanggung, b.pekerjaan, ". 
          "     b.kode_kondisi_terakhir, b.tgl_kondisi_terakhir, b.status_layak, ". 
          "     b.kode_penerima_berkala, b.keterangan, ".
  				"	    case when a.kode_penerima_berkala like 'A%' then ".
          "          case when to_char(add_months(b.tgl_lahir,23*12),'yyyymmdd') <= to_char(sysdate,'yyyymmdd') then ".
          "              'KA13' ".
          "          else ".
          "              null ".
          "          end ".
          "      end kode_kondisi_anak_usia23, ".
          "      case when a.kode_penerima_berkala like 'A%' then ".
          "          case when to_char(add_months(b.tgl_lahir,23*12),'yyyymmdd') <= to_char(sysdate,'yyyymmdd') then ".
          "              to_char(add_months(b.tgl_lahir,23*12),'dd/mm/yyyy') ".
          "          else ".
          "              null ".
          "          end ".
          "      end tgl_kondisi_anak_usia23 ".    
          "from sijstk.pn_klaim_berkala a, sijstk.pn_klaim_penerima_berkala b ".
          "where a.kode_klaim = b.kode_klaim(+) and a.kode_penerima_berkala = b.kode_penerima_berkala(+) ".
          "and a.kode_klaim = '$ls_kode_klaim' and a.no_konfirmasi = '$ln_no_konfirmasi_induk' ".
          "and rownum = 1 ";
  $DB->parse($sql);
  $DB->execute();
  $data = $DB->nextrow();
  $ls_kode_hubungan			 	  = $data["KODE_HUBUNGAN"];
  $ls_nama_hubungan			 	  = $data["NAMA_HUBUNGAN"];
  $ls_kode_penerima_berkala	= $data["KODE_PENERIMA_BERKALA"];	
  $ls_kode_penerima_berkala_induk	= $data["KODE_PENERIMA_BERKALA"];
	$ls_kode_kondisi_anak_usia23		= $data["KODE_KONDISI_ANAK_USIA23"];
	$ld_tgl_kondisi_anak_usia23			= $data["TGL_KONDISI_ANAK_USIA23"];
  $ln_no_urut_keluarga	 		= $data["NO_URUT_KELUARGA"];
  $ls_nama_lengkap					= $data["NAMA_LENGKAP"];
  $ls_no_kartu_keluarga			= $data["NO_KARTU_KELUARGA"];
  $ls_nomor_identitas				= $data["NOMOR_IDENTITAS"];
  $ls_tempat_lahir					= $data["TEMPAT_LAHIR"];
  $ld_tgl_lahir							= $data["TGL_LAHIR"];
  $ls_jenis_kelamin					= $data["JENIS_KELAMIN"];
  $ls_nama_jenis_kelamin		= $data["NAMA_JENIS_KELAMIN"];
  $ls_golongan_darah				= $data["GOLONGAN_DARAH"];
  $ls_alamat_lengkap				= $data["ALAMAT_LENGKAP"];
  $ls_telepon_area					= $data["TELEPON_AREA"];
  $ls_telepon								= $data["TELEPON"];
  $ls_telepon_ext						= $data["TELEPON_EXT"];
  $ls_handphone							= $data["HANDPHONE"];
  $ls_email 								= $data["EMAIL"];
  $ls_npwp									= $data["NPWP"];
  $ls_keterangan 						= $data["KETERANGAN"];
  $ls_kpj_tertanggung				= $data["KPJ_TERTANGGUNG"];
  $ls_bank_penerima					= $data['BANK_PENERIMA'];	  
  $ls_no_rekening_penerima	= $data['NO_REKENING_PENERIMA'];
  $ls_nama_rekening_penerima	= $data['NAMA_REKENING_PENERIMA'];
  $ls_kode_bank_pembayar		= $data['KODE_BANK_PEMBAYAR'];
  
  $sql = "select ".
          "   a.kode_klaim, a.no_klaim, a.kode_kantor, a.kode_segmen, a.kode_perusahaan, a.kode_divisi, a.kode_tk, a.nama_tk, ".
          "   to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, a.no_penetapan,a.petugas_penetapan, ". 
          "   a.nama_tk||' (No.Referensi: '||a.kpj||' | NIK: '||a.nomor_identitas||' | NPP: '||(select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||' - '||(select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan)||')' ket_nama_tk	".																	
          "from sijstk.pn_klaim a	".
          "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_no_penetapan							= $row['NO_PENETAPAN'];
  $ls_ket_klaim_atasnama				= $row['KET_NAMA_TK'];
	
  //jika ada sudah memasuki usia 23 maka otomatis set kondisi akhir -----------
  if ($ls_kode_kondisi_anak_usia23!="")
  {
   	 $ls_rg_kondisi_terakhir_induk = "B"; 	 
  }										 
}							
?>

<tr>
  <td colspan="10">
    <table width="1200px" border="0">
      <tr>
        <!-- Informasi Penerima Manfaat Sebelumnya ------------------------------->	
        <td width="40%" valign="top" align="center" >
          <fieldset style="height:365px;"><legend><b><i><font color="#009999">Informasi Penerima Manfaat Sebelumnya</font></i></b></legend>
            </br>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Nama Lengkap</label>
            	<input type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$ls_nama_lengkap;?>" style="width:250px;" readonly class="disabled">
            </div>																																																		
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Tempat dan Tgl Lahir</label>
              <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" style="width:160px;" readonly class="disabled">
              <input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" style="width:81px;" readonly class="disabled">
            </div>
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Jenis Kelamin</label>
              <input type="text" id="nama_jenis_kelamin" name="nama_jenis_kelamin" value="<?=$ls_nama_jenis_kelamin;?>" style="width:220px;" readonly class="disabled">
              <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>">
            </div>
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Hubungan - No. KK</label>
              <input type="text" id="nama_hubungan" name="nama_hubungan" value="<?=$ls_nama_hubungan;?>" style="width:90px;" readonly class="disabled">
              <input type="hidden" id="kode_hubungan" name="kode_hubungan" value="<?=$ls_kode_hubungan;?>">
              <input type="text" id="no_kartu_keluarga" name="no_kartu_keluarga" value="<?=$ls_no_kartu_keluarga;?>" style="width:151px;" readonly class="disabled"> 
            </div>																																																		
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Alamat Lengkap</label>
            	<textarea cols="255" rows="1" style="width:230px;background-color:#F5F5F5;" id="alamat_lengkap" name="alamat_lengkap" readonly onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_alamat_lengkap;?></textarea>	
            </div>																																									
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Telp</label>	    				
              <input type="text" id="telepon_area" name="telepon_area" value="<?=$ls_telepon_area;?>" style="width:30px;" readonly class="disabled">
              <input type="text" id="telepon" name="telepon" value="<?=$ls_telepon;?>" style="width:100px;" readonly class="disabled">
              &nbsp;ext.
              <input type="text" id="telepon_ext" name="telepon_ext" value="<?=$ls_telepon_ext;?>" style="width:30px;" readonly class="disabled"> 						
            </div>																																																					
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
            	<input type="text" id="handphone" name="handphone" value="<?=$ls_handphone;?>" style="width:220px;" readonly class="disabled">
            </div>																																																																																																		
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            	<input type="text" id="email" name="email" value="<?=$ls_email;?>" style="width:220px;" readonly class="disabled">
            </div>																																																																																																			
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
            	<input type="text" id="npwp" name="npwp" value="<?=$ls_npwp;?>" style="width:200px;" readonly class="disabled">
            </div>																																																																																																												
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Identitas </label>
            	<input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" style="width:180px;" readonly class="disabled">
            </div>																																																																																							
            <div class="clear"></div>
            
            </br>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;">&nbsp; </label>
              <?
              if ($ls_nomor_identitas=="")
              {
              ?>
              <input id="tk_foto" name="tk_foto" type="image" src="../../images/nopic.png" style="text-align:center;height: 90px !important; width: 85px !important;"/>
              <?
              }else
              {
              ?>
              <img id="tk_foto" src="../../mod_kn/ajax/kngetfoto.php?dataid=<?=$ls_nomor_identitas;?>" style="height: 90px !important; width: 85px !important;"/>
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
                <fieldset style="height:220px;"><legend><b><i><font color="#009999">Daftar Pembayaran JP Berkala</font></i></b></legend>
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
                                "and a.no_konfirmasi = '$ln_no_konfirmasi_induk' ".
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
                    <tr><td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
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
                    <tr><td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.25), rgba(0,0,0,0));"/></td></tr>																
                  </table>																			
                </fieldset>	
              </td>	
            </tr>
            
            <?
            //cek apakah masih ada bulan berkala yg belum dibayarkan ----------------------------------------------
            $sql = "select count(*)	as v_jml from sijstk.pn_klaim_berkala_rekap a	".
                    "where kode_klaim = '$ls_kode_klaim' ".
                    "and no_konfirmasi = '$ln_no_konfirmasi_induk' ".
                    "and nvl(a.status_lunas,'T')='T' ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ln_cnt_blmbayar = $row['V_JML'];
            if ($ln_cnt_blmbayar==""){$ln_cnt_blmbayar="0";}
            ?>
            
            <?
            if ($ln_cnt_blmbayar>"0")
            {
            ?>
              <tr>
                <td style="text-align:center;">
                  <fieldset style="height:115px;"><legend>&nbsp;</legend>
                    
                    </br>
                    
                    <b><font color="#ff0000">Masih ada Periode Berkala yang belum dibayarkan, Konfirmasi belum dapat dilakukan..!!! </font></b>
                    
                    </br></br></br>
                    
                    <a href="#" onClick="refreshParent();"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>&nbsp;<b>Kembali Ke Daftar</b></a>													  																											
                  </fieldset>														
                </td>	 												
              </tr>													
            <?													
            }else
            {
            ?>
              <tr>
                <td style="text-align:center;">
                  <fieldset style="height:115px;"><legend><b><i><font color="#009999">Kondisi Terakhir dari </font><font color="#ff0000"><?=$ls_nama_lengkap;?></font></i></b></legend>
										<?	
                    //jika ada sudah memasuki usia 23 maka otomatis set kondisi akhir -----------
                    if ($ls_kode_kondisi_anak_usia23!="")
                    {
                     	 $ls_rg_kondisi_terakhir_induk 	 = "B";
											 $ls_kode_kondisi_terakhir_induk = $ls_kode_kondisi_anak_usia23;
											 $ld_tgl_kondisi_terakhir_induk	 = $ld_tgl_kondisi_anak_usia23; 	 
                    }	                   
                    ?>
										                  
                    <? 
                    switch($ls_rg_kondisi_terakhir_induk)
                    {
                      case 'A' : $selA="checked"; break;
                      case 'B' : $selB="checked"; break;
                    }
                    ?>
                    <?
										if ($ls_kode_kondisi_anak_usia23=="")
                    {
											 ?>
											 <input type="radio" name="rg_kondisi_terakhir_induk" onchange="fl_js_val_rg_kondisi_terakhir_induk();" value="A" <?=$selA;?>><font color="#009999"><b>Kondisi Sama Dengan Sebelumnya</b></font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    	 <?
										}
										?>
										<input type="radio" name="rg_kondisi_terakhir_induk" onchange="fl_js_val_rg_kondisi_terakhir_induk();" value="B" <?=$selB;?>><font color="#009999"><b>Ada Perubahan Status (Menikah/Usia23,dll)</b></font>
                    
                    </br>
                    
                    <span id="span_perubahan_kondisi_terakhir" style="display:none;">
                      </br>
                      <select size="1" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk" value="<?=$ls_kode_kondisi_terakhir_induk;?>" tabindex="1" class="select_format" style="background-color:#ffff99;text-align:center;width:250px;height:28px;">
                        <option value="">------ pilih kondisi akhir ------</option>
                        <?
                        if ($ls_kode_penerima_berkala_induk=="OT")
                        {
                          $ls_filter_kondisi_akhir = "and kode_kondisi_terakhir = 'KA11' "; 
                        }else if ($ls_kode_penerima_berkala_induk=="JD")
                        {
                         	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir in ('KA11','KA12') "; 
                        }else if ($ls_kode_penerima_berkala_induk=="TK")
                        {
                         	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir = 'KA11' "; 													
                        }else
                        {
                         	$ls_filter_kondisi_akhir = "";
                        }						 	 
                        $sql = "select kode_kondisi_terakhir,nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir ".
                        		 	 "where kode_tipe_klaim =  'JPN01' ".
                               $ls_filter_kondisi_akhir.
                               "order by kode_kondisi_terakhir";		 
                        $DB->parse($sql);
                        $DB->execute();
                        while($row = $DB->nextrow())
                        {
                          echo "<option ";
                          if ($row["KODE_KONDISI_TERAKHIR"]==$ls_kode_kondisi_terakhir_induk && strlen($ls_kode_kondisi_terakhir_induk)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
                          echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
                        }
                        ?>
                      </select>
                      <input type="text" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" value="<?=$ld_tgl_kondisi_terakhir_induk;?>" maxlength="10" tabindex="2" onblur="convert_date(tgl_kondisi_terakhir_induk);" style="background-color:#ffff99;text-align:center;width:100px;height:22px;" placeholder="-- sejak --">
                      <input id="btn_tgl_kondisi_terakhir_induk" type="image" align="top" onclick="return showCalendar('tgl_kondisi_terakhir_induk', 'dd-mm-y');" src="../../images/calendar.gif" style="height:21px;"/>                     												   																																																						
                    	<input type="hidden" id="kode_kondisi_anak_usia23" name="kode_kondisi_anak_usia23" value="<?=$ls_kode_kondisi_anak_usia23;?>">
										</span>	
										
										<?	
                    //jika ada sudah memasuki usia 23 maka otomatis set kondisi akhir -----------
                    if ($ls_kode_kondisi_anak_usia23!="")
                    {
											 echo "<script type=\"text/javascript\">fl_js_val_rg_kondisi_terakhir_induk();</script>"; 
											 
											 //jika usia 23 adalah anak pertama maka cek apakah ada anak ke-2 yg eligible ----------
											 if ($ls_kode_penerima_berkala_induk=="A1")
											 {
                          $sql = "select count(*)	as v_jml from sijstk.pn_klaim_penerima_berkala	". 
                                 "where kode_klaim = '$ls_kode_klaim'	".
                                 "and kode_penerima_berkala = 'A2'	".
                                 "and nvl(status_layak,'T')='Y'	";
                          $DB->parse($sql);
                          $DB->execute();
                          $row = $DB->nextrow();
                          $ln_cnt_anak2 = $row['V_JML'];
                          if ($ln_cnt_anak2==""){$ln_cnt_anak2="0";}
													
													if ($ln_cnt_anak2=="0")
													{
													 	echo "<script type=\"text/javascript\">alert('Penerima Berkala - ANAK - saat ini SUDAH MENCAPAI USIA 23 TAHUN, manfaat berkala untuk klaim penetapan nomor $ls_no_penetapan akan dihentikan karena TIDAK ADA PENERIMA MANFAAT SELANJUTNYA, Harap Submit Data Konfirmasi untuk penghentian manfaat berkala...!');</script>"; 
													}else
													{
													 	echo "<script type=\"text/javascript\">alert('Penerima Berkala - ANAK - saat ini SUDAH MENCAPAI USIA 23 TAHUN, manfaat akan dihentikan untuk anak tersebut dan akan dilanjutkan ke anak berikutnya, Harap Submit Data Konfirmasi dan melengkapi informasi penerima manfaat selajutnya...!');</script>";	 
													}									 
											 }else
											 {
											 		echo "<script type=\"text/javascript\">alert('Penerima Berkala - ANAK - saat ini SUDAH MENCAPAI USIA 23 TAHUN, manfaat berkala untuk klaim penetapan nomor $ls_no_penetapan akan dihentikan karena TIDAK ADA PENERIMA MANFAAT SELANJUTNYA, Harap Submit Data Konfirmasi untuk penghentian manfaat berkala...!');</script>";
											 }	 
                    }	                   
                    ?>
                    
										</br>
                      
                    <a href="#" onClick="if(confirm('Apakah anda yakin akan melakukan Submit Data Konfrimasi..?')) doSubmitKonfirmasi();"><img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>&nbsp;<b>Submit Data Konfirmasi</b></a> &nbsp;| &nbsp;	
                    <a href="#" onClick="refreshParent();"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>&nbsp;<b>Kembali Ke Daftar</b></a>													  																											
                  </fieldset>														
                </td>	 												
              </tr>
            <?
            }
            ?>	
          </table>
        </td>																				
      </tr>
    </table>	
  </td>	
</tr>

<tr>
  <td colspan="10" style="align:center;">
    <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;width:1100px;">
      <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
      <li style="margin-left:15px;">Pilih Kondisi Terakhir dari penerima manfaat berkala, jika tidak ada perubahan maka klik <font color="#ff0000"> Kondisi Sama Dengan Sebelumnya</font>. Apabila ada perubahan status maka klik <font color="#ff0000"> Ada Perubahan Status (Menikah/Usia23,dll)</font></li>
      <li style="margin-left:15px;">Apabila ada perubahan status kondisi terakhir dari penerima manfaat berkala maka pilihan <font color="#ff0000"> Status Kondisi Akhir </font> dan <font color="#ff0000"> Tanggal Kejadian (Sejak) </font> wajib diisi.</li>	
      <li style="margin-left:15px;">Setelah pilihan ditentukan maka lanjutkan dengan klik tombol <font color="#ff0000"> Submit Data Konfirmasi </font></li>
      <li style="margin-left:15px;">Setelah proses Submit maka akan ditampilkan jadwal pembayaran JP Berkala untuk periode berikutnya. User dapat melakukan perubahan informasi seperti <font color="#ff0000">Informasi Penerima</font> maupun<font color="#ff0000"> Rekening Pembayaran</font></li>
    </div>								
  </td>	
</tr>		