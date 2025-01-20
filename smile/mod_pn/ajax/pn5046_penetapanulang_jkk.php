
<tr>
	<td colspan="10">	
  <table width="1150px" border="0">														
    <tr>		 
      <!-- Informasi Penetapan Klaim Sebelumnya ------------------------------->
      <?
//------------- informasi antrian ------------------------------------
						include "../ajax/pn5046_tabinfoantrian_penetapan_ulang.php";
						// -------- end informasi antrian ------------------------------------
            ?>	
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
                       "             select kode_klaim from pn.pn_klaim where kode_klaim <> '$ls_kode_klaim_induk' ".
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
								<th colspan="5">Kondisi Terakhir TK &nbsp;:&nbsp;
  								<input type="text" id="nama_kondisi_terakhir" name="nama_kondisi_terakhir" value="<?=$ls_nama_kondisi_terakhir;?>" style="width:180px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
									Per:
									<input type="text" id="tgl_kondisi_terakhir" name="tgl_kondisi_terakhir" value="<?=$ld_tgl_kondisi_terakhir;?>" style="width:80px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
									<input type="hidden" id="kode_kondisi_terakhir" name="kode_kondisi_terakhir" value="<?=$ls_kode_kondisi_terakhir;?>">
									<?
                    //KA01	SEMBUH
                    //KA02	MASIH PENGOBATAN
                    //KA03	CACAT SEBAGIAN FUNGSI
                    //KA04	CACAT SEBAGIAN ANATOMIS
                    //KA05	CACAT TOTAL TETAP
                    //KA06	MENINGGAL DUNIA
										
										//update 26/02/2021 Untuk kondisi akhir Cacat anatomis, cacat fungsi, cacat total tetap, sembuh dan meninggal dunia ----
										//di JKK kondisinya atau status akhir tidak bisa diubah menjadi status yang lain ---------------------------------------
										//KA04	CACAT SEBAGIAN ANATOMIS
										//KA03	CACAT SEBAGIAN FUNGSI
										//KA05	CACAT TOTAL TETAP
										//KA01	SEMBUH
                    //KA06	MENINGGAL DUNIA
										$ls_flag_hide_btn_ubahstatus = "T";
										
										if ($ls_kode_kondisi_terakhir == "KA04" || 
											  $ls_kode_kondisi_terakhir == "KA03" || 
												$ls_kode_kondisi_terakhir == "KA05" || 
												$ls_kode_kondisi_terakhir == "KA01" ||
												$ls_kode_kondisi_terakhir == "KA06"
												)
										{ 
										 	$ls_flag_hide_btn_ubahstatus = "Y";	
										}else
										{
										 	$ls_flag_hide_btn_ubahstatus = "T";
										}
										
										if ($ls_status_submit_penetapan=="T")
        						{	
          						if ($ls_flag_hide_btn_ubahstatus == "Y")
											{
  											?>
                				&nbsp;|&nbsp;
      									<a href="#" onClick="alert('Kondisi akhir CACAT SEBAGIAN ANATOMIS, CACAT SEBAGIAN FUNGSI, CACAT TOTAL TETAP, SEMBUH dan MENINGGAL DUNIA tidak dapat dilakukan perubahan ke status kondisi akhir yang lain..!');"><img src="../../images/refreshx.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Ubah Status</b></a>
              					<?											
											}else
											{
  											?>
                				&nbsp;|&nbsp;
      									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5046_ubahkondisi_tk.php?sender=../form/pn5046.php&kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Ubah Kondisi Terakhir TK',700,420,'no')"><img src="../../images/refreshx.png" border="0" alt="Tambah" align="absmiddle" style="height:18px;"/><b>&nbsp;Ubah Status</b></a>
              					<?
											}						
        						}
									?>									
								</th>							
              </tr>			
											
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
                
                //control data JKK JKM
                $sql_control_data_jkk_jkm=" select nomor_identitas, to_char(tgl_lahir,'DD/MM/RRRR') tgl_lahir, to_char(tgl_kejadian,'DD/MM/RRRR') tgl_kejadian, nama_tk, kode_tipe_klaim from pn.pn_klaim where kode_klaim='$ls_kode_klaim'";
                $DB->parse($sql_control_data_jkk_jkm);
                $DB->execute();
                $row = $DB->nextrow();		
                $ls_nomor_identitas_cd = $row['NOMOR_IDENTITAS'];
                $ls_tgl_lahir_cd = $row['TGL_LAHIR'];
                $ls_tgl_kejadian_cd = $row['TGL_KEJADIAN'];
                $ls_nama_tk_cd = $row['NAMA_TK']; 
                $ls_kode_tipe_klaim_cd = $row['KODE_TIPE_KLAIM'];

                $ls_cek_klaim_jkm_cd="";
                
                if($ls_kode_tipe_klaim_cd == 'JKK01'){
      
                  $sql_control_data_jkk_jkm="select count(*) cek_klaim_jkm from pn.pn_klaim a
                  where nomor_identitas = '$ls_nomor_identitas_cd'
                  and utl_match.edit_distance_similarity (nama_tk, '$ls_nama_tk_cd') >= 75
                  and tgl_lahir = TO_DATE('$ls_tgl_lahir_cd','DD/MM/RRRR')
                  and tgl_kejadian = TO_DATE('$ls_tgl_kejadian_cd','DD/MM/RRRR')
                  and substr(kode_tipe_klaim,1,3) = 'JKM'
                  and nvl(status_batal,'T')='T'
                  and kode_klaim <> nvl('$ls_kode_klaim','XXX')";
      
                  $DB->parse($sql_control_data_jkk_jkm);
                  $DB->execute();
                  $row = $DB->nextrow();		
                  $ls_cek_klaim_jkm_cd = $row['CEK_KLAIM_JKM'];

                  
      
      
                  $sql_control_data_jkk_jkm="select kode_klaim from pn.pn_klaim a
                  where nomor_identitas = '$ls_nomor_identitas_cd'
                  and utl_match.edit_distance_similarity (nama_tk, '$ls_nama_tk_cd') >= 75
                  and tgl_lahir = TO_DATE('$ls_tgl_lahir_cd','DD/MM/RRRR')
                  and tgl_kejadian = TO_DATE('$ls_tgl_kejadian_cd','DD/MM/RRRR')
                  and substr(kode_tipe_klaim,1,3) = 'JKM'
                  and nvl(status_batal,'T')='T'
                  and kode_klaim <> nvl('$ls_kode_klaim','XXX')
                  and rownum=1";
      
                  $DB->parse($sql_control_data_jkk_jkm);
                  $DB->execute();
                  $row = $DB->nextrow();		
                  $ls_kode_klaim_jkm = $row['KODE_KLAIM'];
                
      
                }
                

                $sql = "select ".
                       "    a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.kd_prg, c.nm_prg, ". 
      								 "		to_char(a.tgl_diajukan,'dd/mm/yyyy') tgl_diajukan, ". 
                       "    a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
      								 "		a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan, replace(b.url_path,'pn5002','pn5041') url_path, ".
      								 "		nvl(a.status_lunas,'T') status_lunas, to_char(a.tgl_lunas,'dd/mm/yyyy') tgl_lunas, ".
      								 "		( ".
                       "     select listagg(y.nama_tipe_penerima,', ') within group (order by y.nama_tipe_penerima) tipe_penerima_manfaat ".
                       "     from pn.pn_klaim_manfaat_detil x, pn.pn_kode_tipe_penerima y ".
                       "     where x.kode_tipe_penerima = y.kode_tipe_penerima ".
                       "     and x.kode_klaim = a.kode_klaim ".
                       "     and x.kode_manfaat = a.kode_manfaat ".
                       "     group by x.kode_manfaat ".
                       "		) tipe_penerima_manfaat ".
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

                        <? if ($ls_cek_klaim_jkm_cd > 0 && $ls_kode_tipe_klaim_cd =="JKK01" && ($row['KODE_MANFAAT']=="7" || $row['KODE_MANFAAT']=="8" || $row['KODE_MANFAAT']=="6" || $row['KODE_MANFAAT']=="2")) { ?>

                        <a href="#" onclick="alert('Peserta dengan NIK <?= $ls_nomor_identitas_cd ?> telah dilakukan klaim JKM-nya dengan kode klaim <?= $ls_kode_klaim_jkm ?>. Silakan lakukan verifikasi/pengecekan kasus kembali terkait penyebab meninggal dunia peserta karena kecelakaan kerja atau bukan')">
                        <img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>

                        <? } else { ?>
                    			<!-- <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?task=edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&token=\"+$(#'token_antrian').val()\"+"&root_sender=pn5046.php&form_penetapan=../form/pn5046.php&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',950,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">RINCIAN</font></a>
      									 -->
                         <a href="#" onClick="entry_rincian('<?=$row["URL_PATH"];?>','<?=$row["KODE_KLAIM"];?>','<?=$row["KODE_MANFAAT"];?>','<?=$mid;?>')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">RINCIAN</font></a>
                         <? } ?>
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
                 		<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=pn5046.php&sender=../form/pn5046.php&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',950,520,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																		
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
		
	</table>	
	</td>
</tr>


