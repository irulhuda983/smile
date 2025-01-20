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
  <fieldset style="width:1000px;"><legend>Pembayaran Klaim</legend>
    <table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="11"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="4">Dibayarkan Kepada</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Jumlah Pembayaran</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">&nbsp;</th>												
        </tr>
				<tr>
					<th colspan="2"><th>
					<th colspan="4"><hr/></th>
					<th colspan="3"><hr/></th>
					<th></th>	
				</tr>														
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prog</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Netto</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Sdh Dibayar</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Sisa</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:100px;">Action</th>
        </tr>
				<tr>
					<th colspan="11"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "    kode_klaim, kode_tipe_penerima, nama_tipe_penerima, kd_prg, nm_prg, ". 
                 "    nama_penerima, bank_penerima, no_rekening_penerima, npwp, ".
                 "    nom_manfaat_gross, nom_pph, nom_pembulatan, nom_netto, nom_sudah_bayar, nom_sisa, a.kode_pembayaran ". 
                 "from sijstk.vw_pn_pembayaran_klaim_detil a ".
								 "where a.kode_klaim='$ls_kode_klaim' ".
                 "order by a.kode_tipe_penerima";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
					$n=1;
          $ln_dtl =0;	
					$ln_tot_d_netto = 0;
					$ln_tot_d_sdhbyr = 0;
					$ln_tot_d_sisa = 0;									
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$n;?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_NETTO'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_SUDAH_BAYAR'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_SISA'],2,".",",");?></td>																		       																			        											
							<td>
  							<?
  							if ($row['NOM_SISA']>0)
  							{
  							?>
                <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5004_pembayaran_entry.php?task=New&kode_klaim=<?=$ls_kode_klaim;?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&kd_prg=<?=$row["KD_PRG"];?>&form_root=<?=$ls_form_root;?>&root_sender=pn5004.php&sender=pn5004_pembayaran.php&id_pointer_asal=<?=$ls_id_pointer_asal;?>&kode_pointer_asal=<?=$ls_kode_pointer_asal;?>&tglawaldisplay=<?=$ld_tglawaldisplay;?>&tglakhirdisplay=<?=$ld_tglakhirdisplay;?>','Entry Pembayaran Klaim',850,600,'no')"><img src="../../images/check.png" border="0" alt="Entry Pembayaran" align="absmiddle" />&nbsp;Entry </a>									
  							<?
  							}
  							?>
  							<?
  							if ($row['KODE_PEMBAYARAN']!="")
  							{
    							?>
                  |
									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5004_pembayaran_entry.php?task=view&kode_pembayaran=<?=$row["KODE_PEMBAYARAN"];?>&form_root=<?=$ls_form_root;?>&root_sender=pn5004.php&sender=pn5004_pembayaran.php&id_pointer_asal=<?=$ls_id_pointer_asal;?>&kode_pointer_asal=<?=$ls_kode_pointer_asal;?>&tglawaldisplay=<?=$ld_tglawaldisplay;?>&tglakhirdisplay=<?=$ld_tglakhirdisplay;?>','View Pembayaran Klaim',850,600,'no')"><img src="../../images/check.png" border="0" alt="Entry Pembayaran" align="absmiddle" />&nbsp;View </a>
									<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5029_cetak.php?kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_pembayaran=<?=$row["KODE_PEMBAYARAN"];?>&mid=<?=$mid;?>&tglawaldisplay=<?=$ld_tglawaldisplay;?>&tglakhirdisplay=<?=$ld_tglakhirdisplay;?>','Cetak Penetapan Klaim',700,480,'no')"><img src="../../images/printx.png" border="0" alt="Entry Pembayaran" align="absmiddle" style="height:20px;"/>&nbsp;Cetak </a>									
    							<?
  							}
  							?>								
              </td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$n++;//iterasi n
						$ln_tot_d_netto  += $row["NOM_NETTO"];
						$ln_tot_d_sdhbyr  += $row["NOM_SUDAH_BAYAR"];
						$ln_tot_d_sisa  += $row["NOM_SISA"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="11"><hr style="border:0; border-top:3px double #8c8c8c;"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="7"><i>Total Keseluruhan :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  									
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_netto,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_sdhbyr,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_sisa,2,".",",");?></td>
				<td></td>										        
      </tr>	
			<tr><td colspan="11"><hr/></td></tr>															
    </table>
		</br></br></br>
  </fieldset>				
</div>	
