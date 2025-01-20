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
	
<div id="formKiri" style="width:1020px;">					
  <fieldset style="width:1000px;"><legend>Pembayaran Manfaat Pensiun Berkala</legend>
    <table id="tblrincian1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="11"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>									
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kode Pembayaran</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ktr</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tgl Bayar</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Gross</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">PPh</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Pembulatan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Netto</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jumlah Dibayar</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Melalui</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kepada</th>
        </tr>
				<tr>
					<th colspan="11"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "   a.kode_pembayaran, a.kode_klaim, a.no_konfirmasi, ". 
                 "   a.no_proses, a.kd_prg, to_char(a.blth_proses,'mm/yyyy') blth_proses,  ".
                 "   b.kode_penerima_berkala, ".
                 "   (select nama_lengkap from pn.pn_klaim_penerima_berkala where kode_klaim = a.kode_klaim and kode_penerima_berkala = b.kode_penerima_berkala) nama_penerima_berkala, ".
                 "   a.nom_berkala, a.nom_pph, a.nom_pembulatan,  ".
                 "   a.nom_manfaat_netto, a.nom_pembayaran, a.kode_pajak_pph, ". 
                 "   a.kode_kantor, to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, a.kode_cara_bayar,  ".
                 "   a.kode_bank, a.kode_buku, a.no_kwitansi,  ".
                 "   a.no_cek, a.keterangan_cek, a.flag_buktipotong_pph,  ".
                 "   a.keterangan, a.no_pointer, a.status_batal,  ".
                 "   a.tgl_batal, a.petugas_batal ".
                 "from sijstk.pn_klaim_pembayaran_berkala a, sijstk.pn_klaim_berkala b ".
                 "where a.kode_klaim = b.kode_klaim(+) ".
                 "and a.no_konfirmasi = b.no_konfirmasi(+) ".
                 "and a.kode_klaim = '$ls_kode_klaim'  ".
                 "and nvl(a.status_batal,'T')='T' ".
								 "order by a.kode_klaim, a.no_konfirmasi,a.no_proses ";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
          $ln_tot_d_nom_berkala  = 0;
          $ln_tot_d_nom_pph = 0;
          $ln_tot_d_nom_pembulatan = 0;
          $ln_tot_d_nom_manfaat_netto = 0;	
					$ln_tot_d_nom_pembayaran = 0;						
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_PEMBAYARAN'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_KANTOR'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_PROSES'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['TGL_PEMBAYARAN'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_PPH'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_PEMBULATAN'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT_NETTO'],2,".",",");?></td>	
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_PEMBAYARAN'],2,".",",");?></td>																		       																			        											
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_BUKU'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PENERIMA_BERKALA'];?></td>
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
						$ln_tot_d_nom_pph  		 += $row["NOM_PPH"];
						$ln_tot_d_nom_pembulatan += $row["NOM_PEMBULATAN"];
						$ln_tot_d_nom_manfaat_netto  += $row["NOM_MANFAAT_NETTO"];
						$ln_tot_d_nom_pembayaran  += $row["NOM_PEMBAYARAN"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="11"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="4"><i>Total Keseluruhan :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  		
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_pph,2,".",",");?></td>							
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_pembulatan,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_manfaat_netto,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_pembayaran,2,".",",");?></td>		
				<td colspan="2"></td>							        
      </tr>																
    </table>
  </fieldset>
	
	</br>

  <fieldset style="width:1000px;"><legend>Kompensasi atas Kelebihan/Kekurangan Pembayaran Manfaat Pensiun Berkala</legend>
    <table id="tblrincian1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Dikompensasi ke Bulan Berikutnya</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
        </tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3"><hr/></th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
        </tr>																	
        <tr>
          <!--<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">ID</th>-->
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:60px;">Bulan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Mnf Berkala</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Jml Dibayar</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Kompensasi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Alokasi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Sisa</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Keterangan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
        </tr>
				<tr>
					<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				
        <?							
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "     a.kode_kompensasi, a.kode_klaim, a.no_konfirmasi, a.no_proses, to_char(a.blth_proses,'mm/yyyy') blth_proses, ". 
                 "     a.kd_prg, a.no_urut, ".
                 "     ( ".
                 "         select sum(nvl(nom_berkala,0)) from sijstk.pn_klaim_berkala_rekap ".
                 "         where kode_klaim = a.kode_klaim and no_konfirmasi = a.no_konfirmasi ".
                 "         and no_proses = a.no_proses ".
                 "         and nvl(status_batal,'T')='T' ". 
                 "     ) nom_manfaat, ".
                 "     ( ".
                 "         select sum(nvl(nom_pembayaran,0)) from sijstk.pn_klaim_pembayaran_berkala ".
                 "         where kode_klaim = a.kode_klaim and no_konfirmasi = a.no_konfirmasi ".
                 "         and no_proses = a.no_proses ".
                 "         and nvl(status_batal,'T')='T'  ".
                 "     ) nom_dibayar, ".
                 "     a.nom_berkala nom_dikompensasi,  ".
                 "     (   select sum(nvl(nom_berkala,0)) from sijstk.pn_klaim_berkala_detil_kmpsasi ".
                 "         where kode_klaim = a.kode_klaim and kode_kompensasi = a.kode_kompensasi ".
                 "         and nvl(status_batal,'T')='T' ".
                 "     ) nom_alokasi_kompensasi, ".
                 "     replace(replace(a.keterangan,'KOMPENSASI KE PERIODE BERKALA BULAN BERIKUTNYA ATAS ',''),'PERIODE PEMBAYARAN','PERIODE') keterangan ".
                 "from sijstk.pn_klaim_berkala_kompensasi a ".
                 "where a.kode_klaim = '$ls_kode_klaim'  ".
                 "and nvl(a.status_batal,'T')='T' ".
                 "order by kode_kompensasi ";
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
          $ln_tot_d_nom_manfaat  = 0;
          $ln_tot_d_nom_dibayar = 0;
          $ln_tot_d_nom_dikompensasi = 0;
          $ln_tot_d_nom_alokasi_kompensasi = 0;	
					$ln_tot_d_nom_sisa_kompensasi = 0;						
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <!--<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KODE_KOMPENSASI'];?></td>-->
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_PROSES'];?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_MANFAAT'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_DIBAYAR'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_DIKOMPENSASI'],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_ALOKASI_KOMPENSASI'],2,".",",");?></td>	
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_SISA_ALOKASI'],2,".",",");?></td>																		       																			        											
              <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['KETERANGAN'];?></td>
              <td align="center">
              	<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_view_jpn_alokasikompensasi.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_kompensasi=<?=$row["KODE_KOMPENSASI"];?>&sender=pn5040_view_jpn_manfaatberkalarekap.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Alokasi',1120,500,'no')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN ALOKASI </font></a>
  						</td>							
            </tr>
            <?								    							
            $i++;//iterasi i
						$ln_tot_d_nom_manfaat  += $row["NOM_MANFAAT"];
						$ln_tot_d_nom_dibayar  		 += $row["NOM_DIBAYAR"];
						$ln_tot_d_nom_dikompensasi += $row["NOM_DIKOMPENSASI"];
						$ln_tot_d_nom_alokasi_kompensasi  += $row["NOM_ALOKASI_KOMPENSASI"];
						$ln_tot_d_nom_sisa_kompensasi  += $row["NOM_SISA_ALOKASI"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="1"><i>Total :<i>
          <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
				</td>	  		
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_manfaat,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_dibayar,2,".",",");?></td>							
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_dikompensasi,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_alokasi_kompensasi,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_sisa_kompensasi,2,".",",");?></td>		
				<td colspan="2"></td>							        
      </tr>																
    </table>
  </fieldset>	
</div>	
