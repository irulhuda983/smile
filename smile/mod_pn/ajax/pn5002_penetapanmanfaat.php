<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Biaya dan Santunan
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>
<script language="javascript">
function NewWindow4(mypage,myname,w,h,scroll){
		var openwin = window.parent.Ext.create('Ext.window.Window', {
		title: myname,
		collapsible: true,
		animCollapse: true,

		maximizable: true,
		width: w,
		height: h,
		minWidth: 600,
		minHeight: 400,
		layout: 'fit',
		html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
		dockedItems: [{
  			xtype: 'toolbar',
  			dock: 'bottom',
  			ui: 'footer',
  			items: [
  				{ 
  					xtype: 'button',
  					text: 'Tutup',
  					handler : function(){
  						openwin.close();
  					}
  				}
  			]
  		}]
	});
	openwin.show();
}	
</script>
	
<div id="formKiri" style="width:1050px;">					
  <fieldset style="width:1000px;"><legend><b><i><font color="#009999">Biaya dan Santunan</font></i></b></legend>
    <table id="tblrincian1" width="85%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Manfaat</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Program</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Diajukan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nilai Manfaat (Netto)</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Penerima Manfaat</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:170px;">Action</th>
        </tr>
				<tr>
					<th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "    a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.kd_prg, c.nm_prg, ". 
								 "		to_char(a.tgl_diajukan,'dd/mm/yyyy') tgl_diajukan, ". 
                 "    a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, ".
								 "		a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan, b.url_path, ".
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
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TIPE_PENERIMA_MANFAAT'];?></td>
							<td align="center">
								<?
								if ($row["URL_PATH"]!="")
								{
								?>
              		<!--<a href="#" onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&sender=pn5001_tabmanfaat.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',800,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>-->
									<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&root_sender=<?=$ls_root_sender;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',800,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
									<!--<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$row["URL_PATH"];?>?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_manfaat=<?=$row["KODE_MANFAAT"];?>&root_sender=<?=$ls_root_sender;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender_activetab=2&sender_mid=<?=$mid;?>','Rincian Biaya dan Santunan',1000,580,'no')"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>-->
								<?
								}else
								{
								?>
									<a href="#"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
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
			<tr><td colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="4"><i>Total Biaya dan Santunan :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  									
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_mnf_nom_manfaat_netto,2,".",",");?></td>
				<td></td>										        
      </tr>																
    </table>
  </fieldset>
	
	</br>
	
  <fieldset style="width:1000px;"><legend><b><i><font color="#009999">Penerima Manfaat Biaya dan Santunan</font></i></b></legend>
    <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
				<tr>
					<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
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
					<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
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
             		<!--<a href="#" onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_penerima.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&sender=pn5002_penetapanmanfaat.php&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',950,520,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>-->
								<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_penerima.php?&task=Edit&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&root_sender=<?=$ls_root_sender;?>&sender=<?=$ls_form_penetapan;?>&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',950,520,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																		
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
			<tr><td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
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
		
</div>	
