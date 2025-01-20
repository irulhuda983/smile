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
	
<div id="formKiri" style="width:1070px;">					
  <fieldset style="width:1050px;"><legend>Konfirmasi Berkala</legend>
    <table id="tblrincian1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>
				<!--		 	
				<tr>
					<th colspan="13"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>
				-->
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">Penerima Manfaat Sebelumnya</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">Penerima Manfaat Berikutnya</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="4">Manfaat Berkala</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">&nbsp;</th>
        </tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2"><hr/></th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2"><hr/></th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="4"><hr/></th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">&nbsp;</th>
        </tr>																				
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ktr</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Perubahan Kondisi Akhir</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Per</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Penerima</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Periode</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Total Berkala</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Sudah Dibayar</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Dikompensasi</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:120px;">Action</th>
        </tr>
				<tr>
					<th colspan="12"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "    a.kode_klaim, a.no_konfirmasi, to_char(a.tgl_konfirmasi,'dd/mm/yyyy') tgl_konfirmasi, a.kode_tipe_penerima, a.kode_penerima_berkala, ". 
                 "    (select nama_lengkap from pn.pn_klaim_penerima_berkala where kode_klaim = a.kode_klaim and kode_penerima_berkala = a.kode_penerima_berkala) nama_penerima_berkala, ".
                 "    a.jml_bulan, to_char(a.blth_awal,'mm/yyyy') blth_awal, to_char(a.blth_akhir,'mm/yyyy') blth_akhir, ".
								 "		a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, ". 
                 "    a.keterangan, a.status_konfirmasi, a.no_konfirmasi_induk, a.kode_kondisi_terakhir_induk, ". 
                 "    nvl((select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir_induk),'-') nama_kondisi_terakhir_induk,  ".
                 "    to_char(a.tgl_kondisi_terakhir_induk,'dd/mm/yyyy') tgl_kondisi_terakhir_induk,  ".
                 "    a.kode_kantor_konfirmasi, a.petugas_konfirmasi, nvl(a.status_berhenti_manfaat,'T') status_berhenti_manfaat,  ".
                 "    decode(nvl(a.status_berhenti_manfaat,'T'),'Y','MANFAAT DIHENTIKAN','') ket_berhenti_manfaat, nvl(a.flag_manfaat_turunan,'T') flag_manfaat_turunan, ".
								 "		(select sum(nvl(nom_pembayaran,0)) from sijstk.pn_klaim_pembayaran_berkala where kode_klaim = a.kode_klaim and no_konfirmasi=a.no_konfirmasi and nvl(status_batal,'T')='T') nom_dibayar, ".
								 "		(select sum(nvl(nom_berkala,0)) from sijstk.pn_klaim_berkala_kompensasi where kode_klaim = a.kode_klaim and no_konfirmasi=a.no_konfirmasi and nvl(status_batal,'T')='T') nom_dikompensasi ".
                 "from sijstk.pn_klaim_berkala a ".
                 "where kode_klaim = '$ls_kode_klaim' ".
								 "and a.no_konfirmasi <> 0 ".
                 "and nvl(status_batal,'T')='T' ".
                 "and nvl(status_konfirmasi,'T')='Y' ".
                 "order by a.no_konfirmasi";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
          $ln_tot_d_nom_kompensasi  = 0;
          $ln_tot_d_nom_rapel = 0;
          $ln_tot_d_nom_berjalan = 0;
          $ln_tot_d_nom_berkala = 0;		
					$ln_tot_d_nom_dibayar = 0;				
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_KONFIRMASI'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_KONFIRMASI'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_KANTOR_KONFIRMASI'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_KONDISI_TERAKHIR_INDUK'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_KONDISI_TERAKHIR_INDUK'];?></td>

							<?
							if ($row['STATUS_BERHENTI_MANFAAT']=="Y")
							{
							 	if ($row['NOM_BERKALA']>0)
								{ 
								?>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_PENERIMA_BERKALA'];?></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA_BERKALA'];?>
  									(<font color="#ff0000"><?=$row['KET_BERHENTI_MANFAAT'];?></font>)	
    								<?$ls_task_pnrm="edit";?>	
    								<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/form/pn5045.php?&task=View&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=5&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"></font></a>
    							</td>
								<?
								}else
								{
								?>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"></td>
    							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">
  									<font color="#ff0000"><?=$row['KET_BERHENTI_MANFAAT'];?></font>
    							</td>
								<?
								}
								?>								
							<?							
							}else
							{
							?>
  							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_PENERIMA_BERKALA'];?></td>
  							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA_BERKALA'];?>
  								<?$ls_task_pnrm="edit";?>	
  								<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/form/pn5045.php?&task=View&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=5&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"></font></a>
  							</td>								
							<?
							}
							?>	
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_AWAL'];?>&nbsp;s/d&nbsp;<?=$row['BLTH_AKHIR'];?></td>						
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_DIBAYAR'],2,".",",");?></td>	
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_DIKOMPENSASI'],2,".",",");?></td>																		       																			        											
              <td align="center">	
              	<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_view_jpn_manfaatberkalarekap.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5040_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',1050,500,'no')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN BERKALA </font></a>
  						</td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
						$ln_tot_d_nom_rapel  += $row["NOM_RAPEL"];
						$ln_tot_d_nom_berjalan  += $row["NOM_BERJALAN"];
						$ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
						$ln_tot_d_nom_dibayar  += $row["NOM_DIBAYAR"];
						$ln_tot_d_nom_dikompensasi  += $row["NOM_DIKOMPENSASI"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="12"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="8"><i>Total Keseluruhan :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  		
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_dibayar,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_dikompensasi,2,".",",");?></td>
				<td></td>										        
      </tr>																
    </table>
  </fieldset>

</div>	
