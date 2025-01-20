<tr>
	<td colspan="10">	
  <table width="1150px" border="0">														
    <tr>		 
    <? 
//------------- informasi antrian ------------------------------------
						include "../ajax/pn5046_tabinfoantrian_penetapan_ulang.php";
						// -------- end informasi antrian ------------------------------------
            ?>	
      <!-- Informasi Penetapan Klaim Sebelumnya ------------------------------->	
      <td width="50%" valign="top" align="center">
        <fieldset><legend><b><i><font color="#009999">Informasi Penetapan Klaim Terakhir</font></i></b></legend>

          <table id="tblrincian1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <tbody>	
      				<tr>
      					<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
      				</tr>									
              <tr>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Manfaat</th>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prg</th>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Sebelumnya</th>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Penetapan Terakhir</th>
      					<!--<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Penerima Manfaat</th>-->
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:80px;">Action</th>
              </tr>
      				<tr>
      					<th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
      				</tr>				
              <?							
              if ($ls_kode_klaim_induk!="")
              {			
                $sql = "select ".
                       "    a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.kd_prg, c.nm_prg, ". 
      								 "		to_char(a.tgl_diajukan,'dd/mm/yyyy') tgl_diajukan, ". 
                       "    a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
      								 "		a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan, replace(b.url_path,'pn5002','pn5041') url_path, ".
      								 "		nvl(a.status_lunas,'T') status_lunas, to_char(a.tgl_lunas,'dd/mm/yyyy') tgl_lunas, ".
      								 "		(   select sum(nvl(x.nom_manfaat_netto,0)) ".
                       "         from sijstk.pn_klaim_manfaat x, sijstk.pn_klaim y ".
                       "         where x.kode_klaim = y.kode_klaim ". 
                       "         and nvl(y.status_batal,'T')='T' ".
                       "         and x.kode_manfaat = a.kode_manfaat ".
                       "         and y.kode_klaim in ".
                       "         ( ".
                       "             select kode_klaim from sijstk.pn_klaim where kode_klaim <> '$ls_kode_klaim_induk' ".
                       "             start with kode_klaim = '$ls_kode_klaim_induk' and nvl(status_batal,'T')='T' ". 
                       "             connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ".             
                       "         ) ".
                       "     ) nom_manfaat_netto_sebelumnya ".
                       "from sijstk.pn_klaim_manfaat a, sijstk.pn_kode_manfaat b, sijstk.ms_prg c ".
                       "where a.kode_manfaat = b.kode_manfaat(+) and a.kd_prg = c.kd_prg ".
      								 "and a.kode_klaim='$ls_kode_klaim_induk' ".
                       "order by a.kode_manfaat";
                //echo $sql;
      					$DB->parse($sql);
                $DB->execute();							              					
                $i=0;		
                $ln_dtl =0;	
      					$ln_tot_d_mnf_nom_manfaat_netto = 0;									
                while ($row = $DB->nextrow())
                {
                ?>
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_MANFAAT'];?></td>
      							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
      							<!--<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_DIAJUKAN'];?></td>-->
      							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO_SEBELUMNYA'],2,".",",");?></td>
										<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?></td>																		       																			        											
                    <td align="center">
      								<?
      								if ($row["URL_PATH"]!="")
      								{
      								?>
                    		<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?task=view&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&root_sender=pn5046.php&form_penetapan=../form/pn5046.php&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',950,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">INFO</font></a>
      								<?
      								}else
      								{
      								?>
      									<a href="#"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">INFO</font></a>
      								<?
      								}
      								?>																																																												
                    </td>
                  </tr>
                  <?								    							
                  $i++;//iterasi i
      						$ln_tot_d_mnf_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
                }	//end while
                $ln_dtl=$i;
              }						
              ?>									             																
            </tbody>
      			<tr><td colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
            <tr>
              <td style="text-align:right;" colspan="3"><i>Total Biaya dan Santunan :<i>
                <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
      				</td>	  									
              <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnf_nom_manfaat_netto,2,".",",");?></td>
      				<td></td>										        
            </tr>																
          </table>
														        																
        </fieldset>	 					
      </td>
      
      <!-- Informasi Penetapan Ulang ------------------------------------------>	
      <td width="50%" valign="top">
        <fieldset ><legend><b><i><font color="#009999">Informasi Penetapan Ulang</font></i></b></legend>

          <table id="tblrincian1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <tbody>
              <tr>
      					<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
      				</tr>									
              <tr>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Manfaat</th>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Program</th>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Diajukan</th>
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nilai Manfaat (Netto)</th>
      					<!--<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Penerima Manfaat</th>-->
                <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:80px;">Action</th>
              </tr>
      				<tr>
      					<th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
      				</tr>				
              <?							
              if ($ls_kode_klaim_induk!="")
              {			
                $sql = "select ".
                       "    a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.kd_prg, c.nm_prg, ". 
      								 "		to_char(a.tgl_diajukan,'dd/mm/yyyy') tgl_diajukan, ". 
                       "    a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
      								 "		a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan, ".
											 "		case when a.kd_prg = '1' then 'pn5046_penetapanulang_jhm_jhtrinci.php' else replace(b.url_path,'pn5002','pn5041') end url_path, ".
      								 "		nvl(a.status_lunas,'T') status_lunas, to_char(a.tgl_lunas,'dd/mm/yyyy') tgl_lunas ".
                       "from sijstk.pn_klaim_manfaat a, sijstk.pn_kode_manfaat b, sijstk.ms_prg c ".
                       "where a.kode_manfaat = b.kode_manfaat(+) and a.kd_prg = c.kd_prg ".
      								 "and a.kode_klaim='$ls_kode_klaim' ".
                       "order by a.kode_manfaat";
                //echo $sql;
      					$DB->parse($sql);
                $DB->execute();							              					
                $i=0;		
                $ln_dtl =0;	
      					$ln_tot_d_mnf_nom_manfaat_netto = 0;									
                while ($row = $DB->nextrow())
                {
                ?>
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
                    <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_MANFAAT'];?></td>
      							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
      							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_DIAJUKAN'];?></td>
      							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?></td>																		       																			        											
                    <!--<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TIPE_PENERIMA_MANFAAT'];?></td>-->
      							<td align="center">
      								<?
      								if ($row["URL_PATH"]!="")
      								{
      								 	//manfaat yg boleh dilakukan penetapan ulang:
                        //- Biaya perawatan dan pengobatan		11	--> bebas --> ok 			 				
                        //- Biaya angkut atau transportasi dapat ditetapkan berkali-kali asalkan belum melebihi plafon.	9		--> rumus
                        //- STMB pada peserta BPU			13 --> bebas --> ok
                        //- Santunan cacat	12	--> rumus ---> ok
                        //- Biaya rehabilitasi	17 --> bebas --> ok					 		 
                        //- Orthose Protehese		10 --->rumus --> ok
												
												//Kalau peserta PU, stmb hanya dapat dibayar pada saat terbentuk kondisi akhir sembuh cacat dan meninggal. 
												//Kalau peserta PU penetapan ulangnya masih dalam pengobatan, maka stmb nya disable
												
												//if ($row['KODE_MANFAAT'] == "9" || $row['KODE_MANFAAT'] == "10" || $row['KODE_MANFAAT'] == "11" || $row['KODE_MANFAAT'] == "12" || ($row['KODE_MANFAAT'] == "13") || $row['KODE_MANFAAT'] == "17")
												//{
												?>
                    			<!-- <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?task=edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&root_sender=pn5046.php&form_penetapan=../form/pn5046.php&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',950,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">RINCIAN</font></a>
      									 -->
                        
                         <a href="#" onClick="entry_rincian('<?=$row["URL_PATH"];?>','<?=$row["KODE_KLAIM"];?>','<?=$row["KODE_MANFAAT"];?>','<?=$mid;?>')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">RINCIAN</font></a>
      									
                         
                        <?
												//}
												?>
											<?
      								}else
      								{
      								?>
      									<a href="#"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">RINCIAN</font></a>
      								<?
      								}
      								?>																																																												
                    </td>
                  </tr>
                  <?								    							
                  $i++;//iterasi i
      						$ln_tot_d_mnf_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
                }	//end while
                $ln_dtl=$i;
              }						
              ?>									             																
            </tbody>
      			<tr><td colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
            <tr>
              <td style="text-align:right;" colspan="3"><i>Total Biaya dan Santunan :<i>
                <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
      				</td>	  									
              <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnf_nom_manfaat_netto,2,".",",");?></td>
      				<td></td>										        
            </tr>																
          </table>
					     				  
        </fieldset>										
    	</td>
  	</tr>
		<!--
		<tr><td colspan="10">&nbsp;</td></tr>
		-->
    <tr>
    	<td colspan="10">
      <fieldset style="width:1150px;"><legend><b><i><font color="#009999">Penerima Manfaat Biaya dan Santunan</font></i></b></legend>
        <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
          <tbody>
    				<!--
						<tr>
    					<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
    				</tr>	
						-->								
            <tr>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">A/N</th>
    					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
              <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:170px;">Action</th>
            </tr>
    				<tr>
    					<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
    				</tr>
            <?							
            if ($ls_kode_klaim!="")
            {			
              $sql = "select ".
                     "     a.kode_klaim, a.kode_tipe_penerima, b.nama_tipe_penerima, a.kode_hubungan, ". 
                     "     a.ket_hubungan_lainnya, a.nomor_identitas, a.nama_pemohon, ". 
                     "     a.tempat_lahir, a.tgl_lahir, a.jenis_kelamin, ". 
                     "     a.alamat, a.rt, a.rw, ". 
                     "     a.kode_kelurahan, a.kode_kecamatan, a.kode_kabupaten, ". 
                     "     a.kode_pos, a.telepon_area, a.telepon, ". 
                     "     a.telepon_ext, a.handphone, a.email, ". 
                     "     a.npwp, a.nama_penerima, a.bank_penerima, ". 
                     "     a.no_rekening_penerima, a.nama_rekening_penerima, ".
    								 "		 a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
    								 "		 a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, ". 
                     "     a.keterangan, a.status_lunas, a.tgl_lunas, ". 
                     "     a.petugas_lunas, decode(nvl(a.status_lunas,'T'),'Y',' (* sudah dibayar','') ket_byr ".
                     "from sijstk.pn_klaim_penerima_manfaat a, sijstk.pn_kode_tipe_penerima b ".
                     "where a.kode_tipe_penerima = b.kode_tipe_penerima(+) ".
                     "and a.kode_klaim = '$ls_kode_klaim' ".								 
                     "order by b.no_urut";
              //echo $sql;
    					$DB->parse($sql);
              $DB->execute();							              					
              $i=0;		
              $ln_dtl =0;	
    					$ln_tot_d_mnftipepenerima_nom_manfaat_netto =0;								
              while ($row = $DB->nextrow())
              {
              ?>
                <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_REKENING_PENERIMA'];?></td>
    							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?><i><?=$row['KET_BYR'];?></i></td>																		       																			        																												       																			        											
                  <td align="center">
                 		<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=pn5046.php&sender=../form/pn5046.php&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',950,520,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																		
                  </td>
                </tr>
                <?								    							
                $i++;//iterasi i
    						$ln_tot_d_mnftipepenerima_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
              }	//end while
              $ln_dtl=$i;
            }						
            ?>									             																
          </tbody>
    			<tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
          <tr>
            <td style="text-align:right" colspan="6"><i>Total Diterima :<i>
              <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
            </td>
            <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnftipepenerima_nom_manfaat_netto,2,".",",");?></td>
    				<td>				
    				</td>				
          </tr>																		
        </table>
      </fieldset>	
    	</td>	
    </tr>

		<tr>
			<td colspan="10">
          <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;">
            <span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
            <li style="margin-left:15px;">Klik <font color="#ff0000"> RINCIAN </font> dari manfaat <font color="#ff0000"> PERINCIAN JHT </font> kemudian klik tombol <font color="#ff0000"> HITUNG MANFAAT </font> untuk menghitung ulang sisa manfaat JHT yang belum terbayarkan. Tgl Pengembangan akan dihitung sampai dengan hari ini</li>
          </div>	
			</td>	
		</tr>				
	</table>
		
	</td>
</tr>




								
