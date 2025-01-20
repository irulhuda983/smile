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
    <fieldset><legend>Konfirmasi Berkala</legend>
      <table id="tblListKonfBerkala" width="100%" class="table-data2">
        <thead>
          <tr>
            <th style="text-align:center;" colspan="3">&nbsp;</th>
            <th style="text-align:center;" colspan="2" class="hr-single-bottom">Penerima Manfaat Sebelumnya</th>
            <th style="text-align:center;" colspan="2" class="hr-single-bottom">Penerima Manfaat Berikutnya</th>
  					<th style="text-align:center;" colspan="4" class="hr-single-bottom">Manfaat Berkala</th>
  					<th style="text-align:center;">&nbsp;</th>
          </tr>																					
          <tr class="hr-double-bottom">
            <th style="text-align:center;">No</th>
            <th style="text-align:center;">Tgl Konf</th>
  					<th style="text-align:center;">Ktr Konf</th>
            <th style="text-align:center;">Perubahan Kondisi Akhir</th>
            <th style="text-align:center;">Sejak</th>
  					<th style="text-align:center;">Tipe</th>
  					<th style="text-align:center;">Nama Penerima</th>
  					<th style="text-align:center;">Periode Pmbyaran</th>
  					<th style="text-align:right;">Total Berkala</th>
  					<th style="text-align:right;">Sudah Dibayar</th>
  					<th style="text-align:right;">Dikompensasi</th>
            <th style="text-align:center;">Action</th>
          </tr>
				</thead>	
				<tbody>					
          <?							
          if ($_POST["DATA_KLAIM_BERKALA"])
          {				
            $ln_dtl =0;	
            $ln_tot_d_nom_kompensasi = 0;
            $ln_tot_d_nom_rapel = 0;
            $ln_tot_d_nom_berjalan = 0;
            $ln_tot_d_nom_berkala = 0;		
  					$ln_tot_d_nom_dibayar = 0;
										
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_KLAIM_BERKALA"]);$i++)
            {
             	$ln_konfbkl_no_konfirmasi  							= $_POST["DATA_KLAIM_BERKALA"][$i]["NO_KONFIRMASI"] 							=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["NO_KONFIRMASI"];
							$ld_konfbkl_tgl_konfirmasi 							= $_POST["DATA_KLAIM_BERKALA"][$i]["TGL_KONFIRMASI"] 							=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["TGL_KONFIRMASI"];
							$ls_konfbkl_kode_kantor_konfirmasi 			= $_POST["DATA_KLAIM_BERKALA"][$i]["KODE_KANTOR_KONFIRMASI"] 			=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["KODE_KANTOR_KONFIRMASI"];
							$ls_konfbkl_nama_kondisi_terakhir_induk = $_POST["DATA_KLAIM_BERKALA"][$i]["NAMA_KONDISI_TERAKHIR_INDUK"] =="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["NAMA_KONDISI_TERAKHIR_INDUK"];
							$ld_konfbkl_tgl_kondisi_terakhir_induk  = $_POST["DATA_KLAIM_BERKALA"][$i]["TGL_KONDISI_TERAKHIR_INDUK"] 	=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["TGL_KONDISI_TERAKHIR_INDUK"];
							$ls_konfbkl_status_berhenti_manfaat  		= $_POST["DATA_KLAIM_BERKALA"][$i]["STATUS_BERHENTI_MANFAAT"] 		=="null" ? "T": $_POST["DATA_KLAIM_BERKALA"][$i]["STATUS_BERHENTI_MANFAAT"];
							$ln_konfbkl_nom_berkala  								= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_BERKALA"] 								=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_BERKALA"];
							$ls_konfbkl_kode_penerima_berkala  			= $_POST["DATA_KLAIM_BERKALA"][$i]["KODE_PENERIMA_BERKALA"] 			=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["KODE_PENERIMA_BERKALA"];
							$ls_konfbkl_nama_penerima_berkala  			= $_POST["DATA_KLAIM_BERKALA"][$i]["NAMA_PENERIMA_BERKALA"] 			=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["NAMA_PENERIMA_BERKALA"];
							$ls_konfbkl_ket_berhenti_manfaat  			= $_POST["DATA_KLAIM_BERKALA"][$i]["KET_BERHENTI_MANFAAT"] 				=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["KET_BERHENTI_MANFAAT"];
							$ld_konfbkl_blth_awal  									= $_POST["DATA_KLAIM_BERKALA"][$i]["BLTH_AWAL"] 									=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["BLTH_AWAL"];
							$ld_konfbkl_blth_akhir  								= $_POST["DATA_KLAIM_BERKALA"][$i]["BLTH_AKHIR"] 									=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["BLTH_AKHIR"];
							$ln_konfbkl_nom_berkala  								= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_BERKALA"] 								=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_BERKALA"];
							$ln_konfbkl_nom_dibayar  								= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_DIBAYAR"] 								=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_DIBAYAR"];
							$ln_konfbkl_nom_dikompensasi  					= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_DIKOMPENSASI"] 						=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_DIKOMPENSASI"];
							$ln_konfbkl_nom_kompensasi  						= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_KOMPENSASI"] 							=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_KOMPENSASI"];
							$ln_konfbkl_nom_rapel  									= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_RAPEL"] 									=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_RAPEL"];
							$ln_konfbkl_nom_berjalan  							= $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_BERJALAN"] 								=="null" ? "0": $_POST["DATA_KLAIM_BERKALA"][$i]["NOM_BERJALAN"];
							$ls_konfbkl_kode_klaim  								= $_POST["DATA_KLAIM_BERKALA"][$i]["KODE_KLAIM"] 									=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["KODE_KLAIM"];
							$ln_konfbkl_no_proses  									= $_POST["DATA_KLAIM_BERKALA"][$i]["NO_PROSES"] 									=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["NO_PROSES"];
							$ls_konfbkl_kd_prg  										= $_POST["DATA_KLAIM_BERKALA"][$i]["KD_PRG"] 											=="null" ? "" : $_POST["DATA_KLAIM_BERKALA"][$i]["KD_PRG"];
							?>
              <tr>	
                <td style="text-align:center;">
									<a href="#" onClick="fl_js_jpn_viwkonfirmasijpberkala('<?=$ls_konfbkl_kode_klaim;?>','<?=$ln_konfbkl_no_konfirmasi;?>');"><img src="../../images/document.gif" border="0" alt="View Detil Konfirmasi JP Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"></font></a>	
									<?=$ln_konfbkl_no_konfirmasi;?>
								</td>
  							<td style="text-align:center;"><?=$ld_konfbkl_tgl_konfirmasi;?></td>
  							<td style="text-align:center;"><?=$ls_konfbkl_kode_kantor_konfirmasi;?></td>
  							<td style="text-align:center;"><?=$ls_konfbkl_nama_kondisi_terakhir_induk;?></td>
  							<td style="text-align:center;"><?=$ld_konfbkl_tgl_kondisi_terakhir_induk;?></td>
  
  							<?
  							if ($ls_konfbkl_status_berhenti_manfaat=="Y")
  							{
  							 	if ($ln_konfbkl_nom_berkala>0)
  								{ 
  								?>
      							<td style="text-align:center;"><?=$ls_konfbkl_kode_penerima_berkala;?></td>
      							<td style="text-align:center;"><?=$ls_konfbkl_nama_penerima_berkala;?>
    									(<font color="#ff0000"><?=$ls_konfbkl_ket_berhenti_manfaat;?></font>)	
      							</td>
  								<?
  								}else
  								{
  								?>
      							<td style="text-align:center;"></td>
      							<td style="text-align:center;">
    									<font color="#ff0000"><?=$ls_konfbkl_ket_berhenti_manfaat;?></font>
      							</td>
  								<?
  								}
  								?>								
  							<?							
  							}else
  							{
  							?>
    							<td style="text-align:center;"><?=$ls_konfbkl_kode_penerima_berkala;?></td>
    							<td style="text-align:center;"><?=$ls_konfbkl_nama_penerima_berkala;?></td>								
  							<?
  							}
  							?>	
  							<td style="text-align:center;"><?=$ld_konfbkl_blth_awal;?>&nbsp;s/d&nbsp;<?=$ld_konfbkl_blth_akhir;?></td>						
  							<td style="text-align:right;"><?=number_format((float)$ln_konfbkl_nom_berkala,2,".",",");?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_konfbkl_nom_dibayar,2,".",",");?></td>	
  							<td style="text-align:right;"><?=number_format((float)$ln_konfbkl_nom_dikompensasi,2,".",",");?></td>																		       																			        											
                <td align="center">	
									<a href="#" onClick="fl_js_jpn_manfaatberkalarekap('<?=$ls_konfbkl_kode_klaim;?>','<?=$ln_konfbkl_no_konfirmasi;?>','<?=$ln_konfbkl_no_proses;?>','<?=$ls_konfbkl_kd_prg;?>');"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCI</font></a>
									<a href="#" onClick="fl_js_jpn_dokumenberkala('<?=$ls_konfbkl_kode_klaim;?>','<?=$ln_konfbkl_no_konfirmasi;?>');"><img src="../../images/ico_document.jpg" style="height:25px;" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DOK</font></a>
    						</td>
              </tr>
              <?								    							
  						$ln_tot_d_nom_kompensasi  += (float)$ln_konfbkl_nom_kompensasi;
  						$ln_tot_d_nom_rapel  += (float)$ln_konfbkl_nom_rapel;
  						$ln_tot_d_nom_berjalan  += (float)$ln_konfbkl_nom_berjalan;
  						$ln_tot_d_nom_berkala  += (float)$ln_konfbkl_nom_berkala;
  						$ln_tot_d_nom_dibayar  += (float)$ln_konfbkl_nom_dibayar;
  						$ln_tot_d_nom_dikompensasi  += (float)$ln_konfbkl_nom_dikompensasi;
            }	//end while
            $ln_dtl=$i;
          }						
          ?>									             																
        </tbody>
				<tfoot>
    			<tr>
            <td style="text-align:right;" colspan="8"><i>Total Keseluruhan :<i>
              <input type="hidden" id="d_konfbkl_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_konfbkl_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_konfbkl_showmessage" style="border-width: 0;text-align:right" readonly size="5">
    				</td>	  		
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_dibayar,2,".",",");?></td>
    				<td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_dikompensasi,2,".",",");?></td>
    				<td></td>										        
          </tr>
				</tfoot>																
      </table>
    </fieldset>			 
	</div>
</div>

<script language="javascript">
	function fl_js_jpn_viwkonfirmasijpberkala(p_kode_klaim, p_no_konfirmasi)
	{		
		var c_mid = '<?=$mid;?>';		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_konfirmasiberkalaview.php?kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&mid='+c_mid+'','',1150,610,'yes');
	}
	
	function fl_js_jpn_manfaatberkalarekap(p_kode_klaim, p_no_konfirmasi, p_no_proses, p_kd_prg)
	{		
		var c_mid = '<?=$mid;?>';		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_manfaatberkalarekap.php?kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&no_proses='+p_no_proses+'&kd_prg='+p_kd_prg+'&mid='+c_mid+'','',980,610,'yes');
	}
	
	function fl_js_jpn_dokumenberkala(p_kode_klaim, p_no_konfirmasi)
	{		
		var c_mid = '<?=$mid;?>';		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_konfirmasiberkaladok.php?kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&mid='+c_mid+'','',980,610,'yes');
	}
</script>
