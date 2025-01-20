<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Penggantian Biaya Promotif/Preventif
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>	
<div id="formKiri">					
  <fieldset style="width:930px;"><legend>Penggantian Biaya Kegiatan Promotif/Preventif :</legend>
  	</br>
    <table id="tblrincian1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="19"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.9), rgba(0,0,0,0));"/></th>	
				</tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:40px;">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:200px;">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:50px;">&nbsp;</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="7">Pengajuan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="7">Disetujui</th>							
        </tr>
				<tr>
					<th colspan="2"><th>
					<th colspan="17"><hr/></th>	
				</tr>													
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Perusahaan</th>
          <th style="text-align:left;font: 12px Arial, Helvetica, sans-serif;">TK Aktif</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Barang</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Jasa</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Barang</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Jasa</th>													
        </tr>
				<tr>
					<th colspan="2"><th>
					<th colspan="17"><hr/></th>	
				</tr>				
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">&nbsp;</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Paket</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Orang</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Paket</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Orang</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">|</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>												
        </tr>					
				<tr>
					<th colspan="19"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
        <?						
        if ($ls_kode_klaim!="")
        {			
          $sql = "select ".
                 "    a.kode_realisasi, a.kode_promotif, b.kode_perusahaan, b.nama_perusahaan,a.jml_tk_aktif, ". 
                 "    a.jml_diajukan_barang, a.jml_diajukan_jasa, a.jml_disetujui_barang, ". 
                 "    a.jml_disetujui_jasa, a.nom_diajukan_barang, a.nom_diajukan_jasa, ". 
                 "    a.nom_disetujui_barang, a.nom_disetujui_jasa, a.nom_diajukan, a.nom_disetujui, ".
								 "		a.nom_ppn, a.nom_pph, ".
								 "		a.nom_gross, a.nom_netto ".
                 "from sijstk.pn_promotif_realisasi_rekap a, sijstk.pn_promotif b ".
                 "where a.kode_promotif = b.kode_promotif(+) ".
                 "and a.kode_realisasi = '$ls_kode_realisasi' ".
								 "order by a.kode_promotif";          
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;	
					$n=1;
          $ln_tot_diajukan_barang   =0;
          $ln_tot_diajukan_jasa     =0;
          $ln_tot_disetujui_barang  =0;
          $ln_tot_disetujui_jasa    =0;
          $ln_tot_diajukan  			  =0;
          $ln_tot_disetujui 			  =0;	
					$ln_tot_ppn 							=0;
					$ln_tot_pph 			  			=0;
					$ln_tot_netto 			  		=0;																			
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$n;?></td> 																
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_PERUSAHAAN'];?></td> 
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["JML_TK_AKTIF"],0,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["JML_DIAJUKAN_BARANG"],0,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_DIAJUKAN_BARANG"],2,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>																	       																			        											
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["JML_DIAJUKAN_JASA"],0,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_DIAJUKAN_JASA"],2,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>							
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["JML_DISETUJUI_BARANG"],0,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_DISETUJUI_BARANG"],2,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>																	       																			        											
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["JML_DISETUJUI_JASA"],0,".",",");?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">|</td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_DISETUJUI_JASA"],2,".",",");?></td>							
            </tr>
            <?								    							
            $i++;//iterasi i
						$n++;//iterasi i
						$ln_tot_diajukan_barang   += $row["NOM_DIAJUKAN_BARANG"];
						$ln_tot_diajukan_jasa     += $row["NOM_DIAJUKAN_JASA"];
						$ln_tot_disetujui_barang  += $row["NOM_DISETUJUI_BARANG"];
						$ln_tot_disetujui_jasa    += $row["NOM_DISETUJUI_JASA"];
						$ln_tot_diajukan  			  += $row["NOM_DIAJUKAN"];
						$ln_tot_disetujui 			  += $row["NOM_DISETUJUI"];
						$ln_tot_ppn 							+= $row["NOM_PPN"];
						$ln_tot_pph 			 				+= $row["NOM_PPH"];
						$ln_tot_netto 			 			+= $row["NOM_NETTO"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
      <tr>
        <td style="text-align:center" colspan="19"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></b>
          <input type="hidden" id="d_gantibiaya_kounter_dtl" name="d_gantibiaya_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_gantibiaya_count_dtl" name="d_gantibiaya_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_gantibiaya_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>				
      </tr>	
      <tr>
        <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="6">
          <i>Total Keseluruhan:</i>				
        </td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_diajukan_barang,2,".",",");?></td>
				<td colspan="3"></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_diajukan_jasa,2,".",",");?></td>
				<td colspan="3"></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_disetujui_barang,2,".",",");?></td>
				<td colspan="3"></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_disetujui_jasa,2,".",",");?></td>
      </tr>
  		<tr>
  			<th colspan="19"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.9), rgba(0,0,0,0));"/></th>	
  		</tr>	
      <tr>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="6">
          <i>Total Diajukan (Barang + Jasa):</i>				
        </td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_diajukan,2,".",",");?></td>
				<td colspan="12"></td>				
      </tr>
      <tr>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="6">
          <i>Total Disetujui (Barang + Jasa):</i>				
        </td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_disetujui,2,".",",");?></td>
				<td colspan="12"></td>				
      </tr>
      <tr>
				<?
          $sql = "select x.no_faktur, x.kode_komponen_biaya||' -' ||y.nama_komponen as v_ket_komponen ".
							 	 "from sijstk.pn_promotif_realisasi x, sijstk.vw_pn_promotif_komponen y ".
								 "where x.kode_realisasi = '$ls_kode_realisasi' ".
								 "and x.kode_komponen_biaya = y.kode_komponen ".
								 "and rownum = 1 ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ls_ket_komponen_biaya = $row['V_KET_KOMPONEN'];
					$ls_no_faktur = $row['NO_FAKTUR'];					
				?>			
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="6">
          <i>Pajak Pertambahan Nilai (PPN):</i>				
        </td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_ppn,2,".",",");?></td>
				<td colspan="12">
					<?
					if ($ln_tot_ppn>"0")
					{
					?>	
  					&nbsp;|&nbsp;
  					<input type="text" id="no_faktur" name="no_faktur" value="<?=$ls_no_faktur;?>" size="20" maxlength="30" <?=($ls_status_klaim !="AGENDA")? " readonly class=disabled style=\"background-color:#F5F5F5\"" : " style=\"background-color:#ffff99\"";?>>	
  					(* isikan nomor faktur
					<?
					}else
					{
					?>
						<input type="hidden" id="no_faktur" name="no_faktur">
					<? 
					}
					?>
				</td>				
      </tr>				
      <tr>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="6">
          <i>Pajak Penghasilan (PPh):</i>				
        </td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_pph,2,".",",");?></td>
				<td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="12" >&nbsp;|&nbsp;<?=$ls_ket_komponen_biaya;?></td>				
      </tr>
      <tr>
				<td colspan="5"></td>	
        <td colspan="2"><hr/></td>				
      </tr>
      <tr>
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;" colspan="6">
          <i>Total Penggantian :</i>				
        </td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_netto,2,".",",");?></td>			
      </tr>																																								
    </table>
    </br>
  </fieldset>
</div>	
