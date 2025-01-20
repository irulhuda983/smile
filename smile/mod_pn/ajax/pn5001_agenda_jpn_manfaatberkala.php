<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Manfaat JP Berkala
Hist: - 18/09/2017 : Pembuatan Form (Tim SIJSTK)								 						 
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
	
<div id="formKiri">					
  <fieldset style="width:930px;"><legend>Manfaat Pensiun Berkala</legend>
    <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Program</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan ke-</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jumlah Berkala</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:150px;">Action</th>
        </tr>
				<tr>
					<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select a.kode_klaim, a.no_konfirmasi, a.kd_prg, b.nm_prg, a.no_proses, to_char(a.blth_proses,'mm/yyyy') blth_proses, ".
							 	 "			 a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala ".
                 "from sijstk.pn_klaim_berkala_rekap a, sijstk.ms_prg b ".
                 "where a.kd_prg = b.kd_prg  ".
                 "and a.kode_klaim = '$ls_kode_klaim' ".
                 "and a.no_konfirmasi = '0' ".
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
              <td align="center">
              	<a href="#" onClick="NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_agenda_jpn_manfaatberkalarinci.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5001_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',1100,700,'no')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
  						</td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
						$ln_tot_d_nom_rapel  += $row["NOM_RAPEL"];
						$ln_tot_d_nom_berjalan  += $row["NOM_BERJALAN"];
						$ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
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
				<td></td>										        
      </tr>																
    </table>
  </fieldset>
	
	</br>
	
  <fieldset style="width:930px;"><legend>Penerima Manfaat Berkala</legend>
    <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
				<tr>
					<th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Hubungan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">A/N</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:150px;">Action</th>
        </tr>
				<tr>
					<th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "     a.kode_klaim, a.no_konfirmasi, ". 
                 "     a.kode_tipe_penerima, (select nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima = a.kode_tipe_penerima) nama_tipe_penerima, ".
                 "     a.kode_penerima_berkala, decode(a.kode_penerima_berkala,'TK','TENAGA KERJA','JD','JANDA/DUDA','A1', 'ANAK I','A2', 'ANAK II','OT', 'ORANG TUA', a.kode_penerima_berkala) nama_kode_penerima_berkala, ".
								 "		 b.nama_lengkap, b.npwp, ". 
                 "     b.bank_penerima, b.no_rekening_penerima, b.nama_rekening_penerima, ".
                 "     a.nom_berkala ". 
                 "from sijstk.pn_klaim_berkala a, sijstk.pn_klaim_penerima_berkala b ".
                 "where a.kode_klaim = b.kode_klaim (+) ". 
                 "and a.kode_penerima_berkala = b.kode_penerima_berkala(+) ".
                 "and a.kode_klaim = '$ls_kode_klaim' ".
                 "and a.no_konfirmasi = '0' ". 								 
                 "order by b.no_urut_keluarga";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
					$ln_tot_d_jpnbkala_nom_berkala =0;								
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_KODE_PENERIMA_BERKALA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_LENGKAP'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_REKENING_PENERIMA'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>																		       																			        																												       																			        											
              <td align="center">
								<?
								if ($ls_status_klaim == "AGENDA" || $ls_status_klaim == "AGENDA_TAHAP_I" || $ls_status_klaim == "AGENDA_TAHAP_II" || $ls_status_klaim == "PENETAPAN")
								{
								 	$ls_task_pnrm = "edit"; 
								}else
								{
								 	$ls_task_pnrm = "view";
								}
								?>										
             		<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_agenda_jpn_manfaatberkalapenerima.php?&task=<?=$ls_task_pnrm;?>&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&kode_penerima_berkala=<?=$row["KODE_PENERIMA_BERKALA"];?>&root_sender=pn5001.php&sender=pn5001.php&sender_activetab=5&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																				
              </td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_jpnbkala_nom_berkala  += $row["NOM_BERKALA"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right" colspan="7"><i>Total Diterima :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_jpnbkala_nom_berkala,2,".",",");?></td>
				<td>				
				</td>				
      </tr>																		
    </table>
  </fieldset>
		
</div>	
