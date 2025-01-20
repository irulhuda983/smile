<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk display data konfirmasi jp berkala
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)
			- 22/10/2019 : Sentralisasi Rekening dan Mengalihkan direct conn ke ws								 						 
-----------------------------------------------------------------------------*/	
?>
<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">
    <fieldset><legend>Pembayaran Manfaat Pensiun Berkala</legend>
      <table id="tblListByrBerkala" width="100%" class="table-data2">
        <thead>																					
          <tr class="hr-double-bottom">
						<th style="text-align:center;">No</th>	
            <th style="text-align:center;">Kode Pembayaran</th>
  					<th style="text-align:center;">Kantor</th>
            <th style="text-align:center;">Bulan</th>
            <th style="text-align:center;">Tgl Bayar</th>
  					<th style="text-align:right;">Gross</th>
  					<th style="text-align:right;">PPh</th>
  					<th style="text-align:right;">Pembulatan</th>
  					<th style="text-align:right;">Netto</th>
  					<th style="text-align:right;">Jumlah Dibayar</th>
  					<th style="text-align:center;">Melalui</th>
  					<th style="text-align:center;">Kepada</th>
          </tr>
				</thead>	
				<tbody>					
          <?							
          if ($_POST["DATA_BYR_BERKALA"])
          {				
            $ln_dtl =0;
						$ln_no =0;	
						$ln_tot_d_nom_berkala = 0;
						$ln_tot_d_nom_pph = 0;
						$ln_tot_d_nom_pembulatan = 0;
						$ln_tot_d_nom_manfaat_netto = 0;
						$ln_tot_d_nom_pembayaran = 0;		
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_BYR_BERKALA"]);$i++)
            {
             	$ln_no++;
							$ls_byrbkl_kode_pembayaran 			 = $_POST["DATA_BYR_BERKALA"][$i]["KODE_PEMBAYARAN"] 			 =="null" ? "" : $_POST["DATA_BYR_BERKALA"][$i]["KODE_PEMBAYARAN"];
							$ls_byrbkl_kode_kantor 					 = $_POST["DATA_BYR_BERKALA"][$i]["KODE_KANTOR"] 					 =="null" ? "" : $_POST["DATA_BYR_BERKALA"][$i]["KODE_KANTOR"];
							$ls_byrbkl_blth_proses 					 = $_POST["DATA_BYR_BERKALA"][$i]["BLTH_PROSES"] 					 =="null" ? "" : $_POST["DATA_BYR_BERKALA"][$i]["BLTH_PROSES"];
							$ld_byrbkl_tgl_pembayaran 			 = $_POST["DATA_BYR_BERKALA"][$i]["TGL_PEMBAYARAN"] 			 =="null" ? "" : $_POST["DATA_BYR_BERKALA"][$i]["TGL_PEMBAYARAN"];
							$ln_byrbkl_nom_berkala					 = $_POST["DATA_BYR_BERKALA"][$i]["NOM_BERKALA"] 					 =="null" ? "0": $_POST["DATA_BYR_BERKALA"][$i]["NOM_BERKALA"];
							$ln_byrbkl_nom_pph 							 = $_POST["DATA_BYR_BERKALA"][$i]["NOM_PPH"] 							 =="null" ? "0": $_POST["DATA_BYR_BERKALA"][$i]["NOM_PPH"];
							$ln_byrbkl_nom_pembulatan				 = $_POST["DATA_BYR_BERKALA"][$i]["NOM_PEMBULATAN"] 			 =="null" ? "0": $_POST["DATA_BYR_BERKALA"][$i]["NOM_PEMBULATAN"];
							$ln_byrbkl_nom_manfaat_netto		 = $_POST["DATA_BYR_BERKALA"][$i]["NOM_MANFAAT_NETTO"] 		 =="null" ? "0": $_POST["DATA_BYR_BERKALA"][$i]["NOM_MANFAAT_NETTO"];
							$ln_byrbkl_nom_pembayaran				 = $_POST["DATA_BYR_BERKALA"][$i]["NOM_PEMBAYARAN"] 			 =="null" ? "0": $_POST["DATA_BYR_BERKALA"][$i]["NOM_PEMBAYARAN"];
							$ls_byrbkl_kode_buku 						 = $_POST["DATA_BYR_BERKALA"][$i]["KODE_BUKU"] 						 =="null" ? "" : $_POST["DATA_BYR_BERKALA"][$i]["KODE_BUKU"];
							$ls_byrbkl_nama_penerima_berkala = $_POST["DATA_BYR_BERKALA"][$i]["NAMA_PENERIMA_BERKALA"] =="null" ? "" : $_POST["DATA_BYR_BERKALA"][$i]["NAMA_PENERIMA_BERKALA"];
							?>
              <tr>
                <td style="text-align:center;"><?=$ln_no;?></td>
								<td style="text-align:center;"><?=$ls_byrbkl_kode_pembayaran;?></td>
  							<td style="text-align:center;"><?=$ls_byrbkl_kode_kantor;?></td>
  							<td style="text-align:center;"><?=$ls_byrbkl_blth_proses;?></td>
  							<td style="text-align:center;"><?=$ld_byrbkl_tgl_pembayaran;?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_byrbkl_nom_berkala,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_byrbkl_nom_pph,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_byrbkl_nom_pembulatan,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_byrbkl_nom_manfaat_netto,2,".",",");?></td>	
  							<td style="text-align:right;"><?=number_format((float)$ln_byrbkl_nom_pembayaran,2,".",",");?></td>																		       																			        											
                <td style="text-align:center;"><?=$ls_byrbkl_kode_buku;?></td>
  							<td style="text-align:center;"><?=$ls_byrbkl_nama_penerima_berkala;?></td>
              </tr>
              <?
              $ln_tot_d_nom_berkala  += (float)$ln_byrbkl_nom_berkala;
              $ln_tot_d_nom_pph  		 += (float)$ln_byrbkl_nom_pph;
              $ln_tot_d_nom_pembulatan += (float)$ln_byrbkl_nom_pembulatan;
              $ln_tot_d_nom_manfaat_netto  += (float)$ln_byrbkl_nom_manfaat_netto;
              $ln_tot_d_nom_pembayaran  += (float)$ln_byrbkl_nom_pembayaran;
            }	//end while
            $ln_dtl=$i;
          }						
          ?>									             																
        </tbody>
				<tfoot>
    			<tr>
            <td style="text-align:right;" colspan="5"><i>Total Keseluruhan :<i>
              <input type="hidden" id="d_byrbkl_kounter_dtl" name="d_byrbkl_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_byrbkl_count_dtl" name="d_byrbkl_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_byrbkl_showmessage" style="border-width: 0;text-align:right" readonly size="5">
    				</td>	  		
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_pph,2,".",",");?></td>							
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_pembulatan,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_manfaat_netto,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_pembayaran,2,".",",");?></td>
    				<td colspan="2"></td>										        
          </tr>
				</tfoot>																
      </table>
    </fieldset>
		
  	</br>
  
    <fieldset><legend>Kompensasi atas Kelebihan/Kekurangan Pembayaran Manfaat Pensiun Berkala</legend>
      <table id="tblKompensasiJPbkl" width="100%" class="table-data2">
        <thead>	
          <tr>
            <th style="text-align:center;" colspan="5">&nbsp;</th>
  					<th style="text-align:center;" colspan="3" class="hr-single-bottom">Dikompensasi ke Bulan Berikutnya</th>
  					<th style="text-align:center;" colspan="2">&nbsp;</th>
          </tr>																	
          <tr>
						<th style="text-align:center;">No</th>	
            <th style="text-align:center;">Kode Kompensasi</th>
  					<th style="text-align:center;">Bulan</th>
  					<th style="text-align:right;">Mnf Berkala</th>
            <th style="text-align:right;">Jml Dibayar</th>
  					<th style="text-align:right;">Kompensasi</th>
  					<th style="text-align:right;">Alokasi</th>
  					<th style="text-align:right;">Sisa</th>
  					<th style="text-align:center;">Keterangan</th>
  					<th style="text-align:center;">Action</th>
          </tr>
				</thead>	
				<tbody>				
          <?							
          if ($_POST["DATA_KOMPENSASI_BERKALA"])
          {			
            $ln_dtl =0;
						$ln_no =0;	
            $ln_tot_d_kmpbkl_nom_manfaat  = 0;
            $ln_tot_d_kmpbkl_nom_dibayar = 0;
            $ln_tot_d_kmpbkl_nom_dikompensasi = 0;
            $ln_tot_d_kmpbkl_nom_alokasi_kompensasi = 0;	
  					$ln_tot_d_kmpbkl_nom_sisa_kompensasi = 0;						
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_KOMPENSASI_BERKALA"]);$i++)
            {
             	$ln_no++;
							$ls_kmpbkl_kode_klaim 						= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["KODE_KLAIM"] 			 =="null" ? "" : $_POST["DATA_KOMPENSASI_BERKALA"][$i]["KODE_KLAIM"];
							$ls_kmpbkl_kode_kompensasi 				= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["KODE_KOMPENSASI"] 			 =="null" ? "" : $_POST["DATA_KOMPENSASI_BERKALA"][$i]["KODE_KOMPENSASI"];
							$ld_kmpbkl_blth_proses 						= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["BLTH_PROSES"] 			 		 =="null" ? "" : $_POST["DATA_KOMPENSASI_BERKALA"][$i]["BLTH_PROSES"];
							$ln_kmpbkl_nom_manfaat 						= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_MANFAAT"] 			 		 =="null" ? "0": $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_MANFAAT"];
							$ln_kmpbkl_nom_dibayar 						= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_DIBAYAR"] 			 		 =="null" ? "0": $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_DIBAYAR"];
							$ln_kmpbkl_nom_dikompensasi				= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_DIKOMPENSASI"] 		 =="null" ? "0": $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_DIKOMPENSASI"];
							$ln_kmpbkl_nom_alokasi_kompensasi = $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_ALOKASI_KOMPENSASI"]=="null" ? "0": $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_ALOKASI_KOMPENSASI"];
							$ln_kmpbkl_nom_sisa_alokasi 			= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_SISA_ALOKASI"] 		 =="null" ? "0": $_POST["DATA_KOMPENSASI_BERKALA"][$i]["NOM_SISA_ALOKASI"];
							$ls_kmpbkl_keterangan 						= $_POST["DATA_KOMPENSASI_BERKALA"][$i]["KETERANGAN"] 			 		 =="null" ? "" : $_POST["DATA_KOMPENSASI_BERKALA"][$i]["KETERANGAN"];
							?>
              <tr>	
                <td style="text-align:center; "><?=$ln_no;?></td>
								<td style="text-align:center; "><?=$ls_kmpbkl_kode_kompensasi;?></td>
  							<td style="text-align:center;"><?=$ld_kmpbkl_blth_proses;?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_kmpbkl_nom_manfaat,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_kmpbkl_nom_dibayar,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_kmpbkl_nom_dikompensasi,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_kmpbkl_nom_alokasi_kompensasi,2,".",",");?></td>	
  							<td style="text-align:right;"><?=number_format((float)$ln_kmpbkl_nom_sisa_alokasi,2,".",",");?></td>																		       																			        											
                <td style="text-align:left;white-space:normal; word-wrap:break-word;"><?=$ls_kmpbkl_keterangan;?></td>
                <td align="center">
                	<a href="#" onClick="fl_js_jpn_view_alokasikompensasi('<?=$ls_kmpbkl_kode_klaim;?>','<?=$ls_kmpbkl_kode_kompensasi;?>');"><img src="../../images/indent_right.gif" border="0" alt="Rincian Aokasi Kompensasi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN ALOKASI </font></a>
    						</td>							
              </tr>
              <?								    							
  						$ln_tot_d_kmpbkl_nom_manfaat  += (float)$ln_kmpbkl_nom_manfaat;
  						$ln_tot_d_kmpbkl_nom_dibayar  		 += (float)$ln_kmpbkl_nom_dibayar;
  						$ln_tot_d_kmpbkl_nom_dikompensasi += (float)$ln_kmpbkl_nom_dikompensasi;
  						$ln_tot_d_kmpbkl_nom_alokasi_kompensasi  += (float)$ln_kmpbkl_nom_alokasi_kompensasi;
  						$ln_tot_d_kmpbkl_nom_sisa_kompensasi  += (float)$ln_kmpbkl_nom_sisa_alokasi;
            }	//end while
            $ln_dtl=$i;
          }						
          ?>									             																
        </tbody>
  			<tfoot>
  				<tr>
            <td style="text-align:right;" colspan="3"><i>Total :<i>
              <input type="hidden" id="d_kmpbkl_kounter_dtl" name="d_kmpbkl_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_kmpbkl_count_dtl" name="d_kmpbkl_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_kmpbkl_showmessage" style="border-width: 0;text-align:right" readonly size="5">
    				</td>	  		
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_kmpbkl_nom_manfaat,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_kmpbkl_nom_dibayar,2,".",",");?></td>							
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_kmpbkl_nom_dikompensasi,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_kmpbkl_nom_alokasi_kompensasi,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_kmpbkl_nom_sisa_kompensasi,2,".",",");?></td>		
    				<td colspan="2"></td>							        
          </tr>
				</tfoot>																
      </table>
    </fieldset>						 
	</div>
</div>

<script language="javascript">
	function fl_js_jpn_view_alokasikompensasi(p_kode_klaim, p_kode_kompensasi)
	{		
		var c_mid = '<?=$mid;?>';		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_alokasikompensasi.php?kode_klaim='+p_kode_klaim+'&kode_kompensasi='+p_kode_kompensasi+'&mid='+c_mid+'','',980,610,'yes');
	}	
</script>