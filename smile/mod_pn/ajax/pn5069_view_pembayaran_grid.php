<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk display tab penetapan manfaat
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)
			- 22/10/2019 : Sentralisasi Rekening dan Mengalihkan direct conn ke ws								 						 
-----------------------------------------------------------------------------*/	
?>
<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">	 
    <fieldset><legend>Informasi Pembayaran Klaim</legend>
      <table id="tblListByrLumpsum" width="100%" class="table-data2">
        <thead>		
          <tr>
            <th style="text-align:center;" colspan="6">&nbsp;</th>
            <th style="text-align:center;" colspan="3" class="hr-single-bottom"><font color="#009999">Jumlah Pembayaran</font></th>
            <th style="text-align:center;">&nbsp;</th>												
          </tr>								
          <tr class="hr-double-bottom">
            <th style="text-align:center;">No</th>
            <th style="text-align:center;">Tipe</th>
            <th style="text-align:center;">Prog</th>
            <th style="text-align:center;">Nama</th>
            <th style="text-align:center;">NPWP</th>
            <th style="text-align:center;">Mekanisme Pembayaran</th>
            <th style="text-align:center;">Netto</th>
            <th style="text-align:center;">Sudah Dibayar</th>
            <th style="text-align:center;">Sisa</th>
            <th style="text-align:center;width:150px;">Action</th>				    							
          </tr>
        </thead>
        <tbody id="data_list_ByrMnfLumsump">												
          <?							
          if ($_POST["DATA_BYR"])
          {			
            $ln_dtl =0;	
            $ln_tot_d_byr_nom_manfaat_netto = 0;
						$ln_tot_d_byr_nom_sudah_bayar = 0;
						$ln_tot_d_byr_nom_sisa = 0;
						$ln_byr_no =0;
						
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_BYR"]);$i++)
            {
             	$ln_byr_no++;
							$ls_byr_kode_klaim 						 = $_POST["DATA_BYR"][$i]["KODE_KLAIM"] 						=="null" ? "" : $_POST["DATA_BYR"][$i]["KODE_KLAIM"];
							$ls_byr_nama_tipe_penerima 		 = $_POST["DATA_BYR"][$i]["NAMA_TIPE_PENERIMA"] 		=="null" ? "" : $_POST["DATA_BYR"][$i]["NAMA_TIPE_PENERIMA"];
							$ls_byr_nm_prg 								 = $_POST["DATA_BYR"][$i]["NM_PRG"] 								=="null" ? "" : $_POST["DATA_BYR"][$i]["NM_PRG"];
							$ls_byr_nama_penerima 				 = $_POST["DATA_BYR"][$i]["NAMA_PENERIMA"] 					=="null" ? "" : $_POST["DATA_BYR"][$i]["NAMA_PENERIMA"];
							$ls_byr_npwp 									 = $_POST["DATA_BYR"][$i]["NPWP"] 									=="null" ? "" : $_POST["DATA_BYR"][$i]["NPWP"];
							$ls_byr_kode_cara_bayar 			 = $_POST["DATA_BYR"][$i]["KODE_CARA_BAYAR"] 				=="null" ? "" : $_POST["DATA_BYR"][$i]["KODE_CARA_BAYAR"];
							$ls_byr_nama_cara_bayar 			 = $_POST["DATA_BYR"][$i]["NAMA_CARA_BAYAR"] 				=="null" ? "" : $_POST["DATA_BYR"][$i]["NAMA_CARA_BAYAR"];
							$ls_byr_bank_penerima 				 = $_POST["DATA_BYR"][$i]["BANK_PENERIMA"] 					=="null" ? "" : $_POST["DATA_BYR"][$i]["BANK_PENERIMA"];
							$ls_byr_no_rekening_penerima 	 = $_POST["DATA_BYR"][$i]["NO_REKENING_PENERIMA"] 	=="null" ? "" : $_POST["DATA_BYR"][$i]["NO_REKENING_PENERIMA"];
							$ls_byr_nama_rekening_penerima = $_POST["DATA_BYR"][$i]["NAMA_REKENING_PENERIMA"] =="null" ? "" : $_POST["DATA_BYR"][$i]["NAMA_REKENING_PENERIMA"];
							$ls_byr_handphone 						 = $_POST["DATA_BYR"][$i]["HANDPHONE"] 							=="null" ? "" : $_POST["DATA_BYR"][$i]["HANDPHONE"];
							$ls_byr_is_verified_hp 				 = $_POST["DATA_BYR"][$i]["IS_VERIFIED_HP"] 				=="null" ? "" : $_POST["DATA_BYR"][$i]["IS_VERIFIED_HP"];
							$ln_byr_nom_manfaat_netto 		 = $_POST["DATA_BYR"][$i]["NOM_MANFAAT_NETTO"] 			=="null" ? "" : $_POST["DATA_BYR"][$i]["NOM_MANFAAT_NETTO"];
							$ln_byr_nom_sudah_bayar 			 = $_POST["DATA_BYR"][$i]["NOM_SUDAH_BAYAR"] 				=="null" ? "" : $_POST["DATA_BYR"][$i]["NOM_SUDAH_BAYAR"];
							$ln_byr_nom_sisa 							 = $_POST["DATA_BYR"][$i]["NOM_SISA"] 							=="null" ? "" : $_POST["DATA_BYR"][$i]["NOM_SISA"];
							$ls_byr_kode_pembayaran 			 = $_POST["DATA_BYR"][$i]["KODE_PEMBAYARAN"] 				=="null" ? "" : $_POST["DATA_BYR"][$i]["KODE_PEMBAYARAN"];
							
			  			$ls_byr_ket_dibayar_ke = "";
							if ($ls_byr_kode_cara_bayar=="V")
							{
							 	 $ls_byr_ket_dibayar_ke = $ls_byr_nama_cara_bayar." - NOMOR VA DIKIRIMKAN KE NO.HP ".$ls_byr_handphone;
							}else
							{
  							 if ($ls_byr_kode_cara_bayar!="")
  							 {
  							 		$ls_byr_nama_cara_bayar_set = $ls_byr_nama_cara_bayar;
  							 }else
  							 {
  							 		$ls_byr_nama_cara_bayar_set = "TRANSFER";	
  							 }
  							 $ls_byr_ket_dibayar_ke = $ls_byr_nama_cara_bayar_set." KE ".$ls_byr_bank_penerima." NO.REK ".$ls_byr_no_rekening_penerima." A/N ".$ls_byr_nama_rekening_penerima;
							}	
							?>
              <tr>
  							<td style="text-align:center;"><?=$ln_byr_no;?></td>
  							<td style="text-align:center;"><?=$ls_byr_nama_tipe_penerima;?></td>
  							<td style="text-align:center;"><?=$ls_byr_nm_prg;?></td>
								<td style="text-align:center;"><?=$ls_byr_nama_penerima;?></td>
  							<td style="text-align:center;"><?=$ls_byr_npwp;?></td>
  							<td style="text-align:center;white-space:pre-wrap; word-wrap:break-word;"><?=$ls_byr_ket_dibayar_ke;?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_byr_nom_manfaat_netto,2,".",",");?></td>
								<td style="text-align:right;"><?=number_format((float)$ln_byr_nom_sudah_bayar,2,".",",");?></td>
								<td style="text-align:right;"><?=number_format((float)$ln_byr_nom_sisa,2,".",",");?></td>																		       																			        																												       																			        											
                <td style="text-align:center;">
									<?
									if ($ls_byr_kode_pembayaran!="")
									{
  									?>
  									<a href="#" onClick="fl_js_byr_view_detil('<?=$ls_byr_kode_klaim;?>', '<?=$ls_byr_kode_pembayaran;?>');"><img src="../../images/check.png" border="0" alt="View Pembayaran" align="absmiddle"/>&nbsp;View </a>
										&nbsp;|&nbsp;
										<a href="#" onClick="fl_js_byr_cetak_detil('<?=$ls_byr_kode_klaim;?>', '<?=$ls_byr_kode_pembayaran;?>');"><img src="../../images/printx.png" border="0" alt="Cetak Pembayaran" align="absmiddle" style="height:18px;"/>&nbsp;Cetak </a>
  									<?
									}
									?>																																															
                </td>
              </tr>
              <?								    							
              //$i++;//iterasi i
  						$ln_tot_d_byr_nom_manfaat_netto += (float)$ln_byr_nom_manfaat_netto;
							$ln_tot_d_byr_nom_sudah_bayar += (float)$ln_byr_nom_sudah_bayar;
							$ln_tot_d_byr_nom_sisa += (float)$ln_byr_nom_sisa;
            }	//end while
            $ln_dtl=$i;
					
  					if ($i == 0)
  					{
  					 	?>
  						<tr class="nohover-color">
  							<td colspan="10" style="text-align: center;">-- belum ada data penerima manfaat --</td>
  						</tr>
  						<?
  					}							
          }						
          ?>				             																
        </tbody>
        <tfoot>
          <tr>
            <td style="text-align:right;" colspan="6"><i>Total Keseluruhan :<i></td>
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_byr_nom_manfaat_netto,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_byr_nom_sudah_bayar,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_byr_nom_sisa,2,".",",");?></span></td>
            <td></td>										        
          </tr>
        </tfoot>															
      </table>
      </br></br></br>
    </fieldset>														 
  </div>
</div>

<script language="javascript">
	function fl_js_byr_cetak_detil(p_kode_klaim, p_kode_pembayaran)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5029_cetak.php?kode_klaim='+p_kode_klaim+'&kode_pembayaran='+p_kode_pembayaran+'&mid='+c_mid+'','',980,550,'no');
	}
	
	function fl_js_byr_view_detil(p_kode_klaim, p_kode_pembayaran)
	{		
		var c_mid = '<?=$mid;?>';
		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_pembayaran_detil.php?kode_klaim='+p_kode_klaim+'&kode_pembayaran='+p_kode_pembayaran+'&mid='+c_mid+'','',980,640,'yes');
	}	
</script>
