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
    <fieldset><legend>Biaya dan Santunan</legend>
  		<table id="tblrincian1" width="100%" class="table-data2">
        <thead>													
          <tr class="hr-double-bottom">
            <th style="text-align:center;">Manfaat</th>
            <th style="text-align:center;">Program</th>
            <th style="text-align:center;">Tgl Diajukan</th>
            <th style="text-align:right;">Nilai Manfaat (Netto)</th>
            <th style="text-align:center;">Penerima Manfaat</th>
            <th style="text-align:center;">Action</th>					    							
          </tr>
        </thead>				
        <tbody>			
          <?		
					//kondisi sumber JKP
					$ls_data_sumber = $_POST['DATA_SUMBER'];
					// var_dump($ls_data_sumber);
					//end kondisi					
          if ($_POST["DATA_MANFAAT"])
          {			
            $ln_dtl =0;	
            $ln_tot_d_mnf_nom_manfaat_netto = 0;
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_MANFAAT"]);$i++)
            {					
            	$ls_mnf_kode_klaim 	 					= $_POST["DATA_MANFAAT"][$i]["KODE_KLAIM"] 						=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["KODE_KLAIM"];
							$ls_mnf_kd_prg 			 					= $_POST["DATA_MANFAAT"][$i]["KD_PRG"] 								=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["KD_PRG"];
							$ls_mnf_nm_prg 			 					= $_POST["DATA_MANFAAT"][$i]["NM_PRG"] 								=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NM_PRG"];
							$ld_mnf_tgl_diajukan 	 				= $_POST["DATA_MANFAAT"][$i]["TGL_DIAJUKAN"] 					=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["TGL_DIAJUKAN"];
							$ln_mnf_nom_manfaat_netto 		= $_POST["DATA_MANFAAT"][$i]["NOM_MANFAAT_NETTO"] 		=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NOM_MANFAAT_NETTO"];
							$ls_mnf_tipe_penerima_manfaat = $_POST["DATA_MANFAAT"][$i]["TIPE_PENERIMA_MANFAAT"] =="null" ? "" : $_POST["DATA_MANFAAT"][$i]["TIPE_PENERIMA_MANFAAT"];
							$ls_mnf_url_path 							= $_POST["DATA_MANFAAT"][$i]["URL_PATH"] 							=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["URL_PATH"];
							$ls_mnf_kode_manfaat 					= $_POST["DATA_MANFAAT"][$i]["KODE_MANFAAT"] 					=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["KODE_MANFAAT"];
							$ls_mnf_nama_manfaat 					= $_POST["DATA_MANFAAT"][$i]["NAMA_MANFAAT"] 					=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NAMA_MANFAAT"];
							?>
              <tr>
                <td style="text-align:center;"><?=$ls_mnf_nama_manfaat;?></td>
  							<td style="text-align:center;"><?=$ls_mnf_nm_prg;?></td>
  							<td style="text-align:center;"><?=$ld_mnf_tgl_diajukan;?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_mnf_nom_manfaat_netto,2,".",",");?></td>																		       																			        											
                <td style="text-align:center;white-space:normal;word-wrap:break-word;"><?=$ls_mnf_tipe_penerima_manfaat;?></td>
  							<td style="text-align:center;">
  								<?
              		if ($ls_mnf_kode_manfaat=="25" && $ls_mnf_kd_prg=="4") //manfaat jp lumpsum
              		{
              		 	$ls_mnf_url_path = 'pn5069_penetapanmanfaat_jpnlumpsumrinci.php'; 
              		}
		
									if ($ls_mnf_url_path!="")
  								{
									 	$ls_path = "pn5069_view".substr($ls_mnf_url_path,6); 
										 if($ls_data_sumber == 'JKP')
										 	{
												?>
												<a href="#" onClick="fl_js_tap_mnf_rinci('<?=$ls_mnf_kode_klaim;?>', '<?=$ls_mnf_kode_manfaat;?>', 'pn5069_view_penetapanmanfaat_jkp.php');"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
												<?php
											 }else{
												 ?>
												 <a href="#" onClick="fl_js_tap_mnf_rinci('<?=$ls_mnf_kode_klaim;?>', '<?=$ls_mnf_kode_manfaat;?>', '<?=$ls_path;?>');"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
												 <?php
											 }
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
              //$i++;//iterasi i
  						$ln_tot_d_mnf_nom_manfaat_netto += (float)$ln_mnf_nom_manfaat_netto;
            }	//end while
            $ln_dtl=$i;
					
  					if ($i == 0)
  					{
  					 	?>
  						<tr class="nohover-color">
  							<td colspan="6" style="text-align: center;">-- belum ada data manfaat --</td>
  						</tr>
  						<?
  					}
          }						
          ?>									             																
  			</tbody>
        <tfoot>
					<tr>
            <td style="text-align:right;" colspan="3"><i>Total Biaya dan Santunan :<i>
              <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
            </td>	  									
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_mnf_nom_manfaat_netto,2,".",",");?></td>
            <td colspan="2"></td>										        
          </tr>
  			</tfoot>															
      </table>
  		</br></br>
    </fieldset> 
	</div>
</div>					
  
<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">	 
    <fieldset><legend>Penerima Manfaat Biaya dan Santunan</legend>
      <table id="tblrincian1" width="100%" class="table-data2">
        <thead>																		
          <tr class="hr-double-bottom">
            <th style="text-align:center;">Tipe</th>
            <th style="text-align:center;">Nama</th>
            <th style="text-align:center;">NPWP</th>
						<th style="text-align:center;">Mekanisme Pembayaran</th>
						<th style="text-align:right;">Nominal</th>
						<th style="text-align:center;width:170px;">Action</th>					    							
          </tr>
        </thead>					
        <tbody>
          <?							
          if ($_POST["DATA_PENERIMA"])
          {			
            $ln_dtl =0;	
            $ln_tot_d_mnftipepenerima_nom_manfaat_netto = 0;
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_PENERIMA"]);$i++)
            {
             	$ls_pnrm_kode_klaim 	 					= $_POST["DATA_PENERIMA"][$i]["KODE_KLAIM"] 					 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["KODE_KLAIM"];
							$ls_pnrm_kode_tipe_penerima 		= $_POST["DATA_PENERIMA"][$i]["KODE_TIPE_PENERIMA"] 	 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["KODE_TIPE_PENERIMA"];
							$ls_pnrm_nama_tipe_penerima 		= $_POST["DATA_PENERIMA"][$i]["NAMA_TIPE_PENERIMA"] 	 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_TIPE_PENERIMA"];
							$ls_pnrm_nama_penerima 	 				= $_POST["DATA_PENERIMA"][$i]["NAMA_PENERIMA"] 				 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_PENERIMA"];
							$ls_pnrm_npwp 									= $_POST["DATA_PENERIMA"][$i]["NPWP"] 								 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NPWP"];
							$ls_pnrm_kode_cara_bayar 				= $_POST["DATA_PENERIMA"][$i]["KODE_CARA_BAYAR"] 			 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["KODE_CARA_BAYAR"];
							$ls_pnrm_nama_cara_bayar 				= $_POST["DATA_PENERIMA"][$i]["NAMA_CARA_BAYAR"] 			 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_CARA_BAYAR"];
							$ls_pnrm_bank_penerima 					= $_POST["DATA_PENERIMA"][$i]["BANK_PENERIMA"] 				 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["BANK_PENERIMA"];
							$ls_pnrm_no_rekening_penerima 	= $_POST["DATA_PENERIMA"][$i]["NO_REKENING_PENERIMA"]  =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NO_REKENING_PENERIMA"];
							$ls_pnrm_nama_rekening_penerima = $_POST["DATA_PENERIMA"][$i]["NAMA_REKENING_PENERIMA"]=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_REKENING_PENERIMA"];
							$ls_pnrm_handphone 							= $_POST["DATA_PENERIMA"][$i]["HANDPHONE"] 						 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["HANDPHONE"];
							$ls_pnrm_is_verified_hp 				= $_POST["DATA_PENERIMA"][$i]["IS_VERIFIED_HP"] 			 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["IS_VERIFIED_HP"];
							$ln_pnrm_nom_manfaat_netto 			= $_POST["DATA_PENERIMA"][$i]["NOM_MANFAAT_NETTO"] 		 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NOM_MANFAAT_NETTO"];
							$ls_pnrm_status_lunas 					= $_POST["DATA_PENERIMA"][$i]["STATUS_LUNAS"] 				 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["STATUS_LUNAS"];
							$ls_pnrm_is_verified_hp 				= $_POST["DATA_PENERIMA"][$i]["IS_VERIFIED_HP"] 			 =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["IS_VERIFIED_HP"];
							
			  			$ls_pnrm_ket_dibayar_ke = "";
							if ($ls_pnrm_kode_cara_bayar=="V")
							{
							 	 $ls_pnrm_ket_dibayar_ke = $ls_pnrm_nama_cara_bayar." - NOMOR VA DIKIRIMKAN KE NO.HP ".$ls_pnrm_handphone;
							}else
							{
  							 if ($ls_pnrm_kode_cara_bayar!="")
  							 {
  							 		$ls_pnrm_nama_cara_bayar_set = $ls_pnrm_nama_cara_bayar;
  							 }else
  							 {
  							 		$ls_pnrm_nama_cara_bayar_set = "TRANSFER";	
  							 }
  							 $ls_pnrm_ket_dibayar_ke = $ls_pnrm_nama_cara_bayar_set." KE ".$ls_pnrm_bank_penerima." NO.REK ".$ls_pnrm_no_rekening_penerima." A/N ".$ls_pnrm_nama_rekening_penerima;
							}							
							?>
              <tr>
  							<td style="text-align:center;"><?=$ls_pnrm_nama_tipe_penerima;?></td>
  							<td style="text-align:center;"><?=$ls_pnrm_nama_penerima;?></td>
  							<td style="text-align:center;"><?=$ls_pnrm_npwp;?></td>
								<td style="text-align:center;white-space:pre-wrap; word-wrap:break-word;"><?=$ls_pnrm_ket_dibayar_ke;?></td>
  							<td style="text-align:right;"><?=number_format((float)$ln_pnrm_nom_manfaat_netto,2,".",",");?></td>																		       																			        																												       																			        											
                <td style="text-align:center;">
									<a href="#" onClick="fl_js_tap_penerima_rinci('<?=$ls_pnrm_kode_klaim;?>', '<?=$ls_pnrm_kode_tipe_penerima;?>');"><img src="../../images/user_go.png" border="0" alt="View Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																
                </td>
              </tr>
              <?								    							
              //$i++;//iterasi i
  						$ln_tot_d_mnftipepenerima_nom_manfaat_netto += (float)$ln_pnrm_nom_manfaat_netto;
            }	//end while
            $ln_dtl=$i;
					
  					if ($i == 0)
  					{
  					 	?>
  						<tr class="nohover-color">
  							<td colspan="6" style="text-align: center;">-- belum ada data penerima manfaat --</td>
  						</tr>
  						<?
  					}							
          }						
          ?>									             																
        </tbody>
				<tfoot>
    			<tr>
            <td style="text-align:right" colspan="4"><i>Total Diterima :<i>
              <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
            </td>
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_mnftipepenerima_nom_manfaat_netto,2,".",",");?></td>
    				<td></td>				
          </tr>	
				</tfoot>																	
      </table>
  		</br></br>
    </fieldset>
	</div>
</div>

<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">
    <fieldset>
      <legend>Dokumen Tambahan</legend>
      <table id="tblrincianDokTambahan" width="100%" class="table-data2">
        <thead>												
          <tr class="hr-double-bottom">
            <th>No</th>
            <th>Nama Dokumen</th>
            <th>Keterangan</th>
            <th>Download</th>
            <th>Aksi</th>				    							
          </tr>
        </thead>
        <tbody>
          <?						
          if ($_POST["DATA_DOKUMEN_TAMBAHAN"])
          {									              					
            $i=0;		
            $ln_admtb_dtl =0;
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_DOKUMEN_TAMBAHAN"]);$i++)										
            {
						 	$ls_admtb_kode_klaim 		= $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["KODE_KLAIM"] 					=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["KODE_KLAIM"];
							$ln_admtb_no_urut 			= $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["NO_URUT"] 							=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["NO_URUT"];
							$ls_admtb_kode_dokumen 	= $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["KODE_DOKUMEN"] 				=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["KODE_DOKUMEN"];		
							$ls_admtb_nama_dokumen 	= $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["NAMA_DOKUMEN_TAMBAHAN"]=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["NAMA_DOKUMEN_TAMBAHAN"];
							$ls_admtb_keterangan_dokumen = $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["KETERANGAN_DOKUMEN_TAMBAHAN"] 			=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["KETERANGAN_DOKUMEN_TAMBAHAN"];
							$ls_admtb_path_url 			= $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["PATH_URL"] 						=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["PATH_URL"];
							$ls_admtb_mime_type			= $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["MIME_TYPE"] 						=="null" ? "" : $_POST["DATA_DOKUMEN_TAMBAHAN"][$i]["MIME_TYPE"];																													
            ?>
              <tr>
                <td style="text-align:center;">	
                  <?=$ln_admtb_no_urut;?>											
                  <input type="hidden" id="d_admtb_no_urut<?=$i;?>" name="d_admtb_no_urut<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$ln_admtb_no_urut;?>" readonly class="disabled">    									 
                </td> 																
                <td style="text-align:left;white-space:normal;word-wrap:break-word;">
                  <?=$ls_admtb_nama_dokumen;?>										
                  <input type="hidden" id="d_admtb_kode_dokumen<?=$i;?>" name="d_admtb_kode_dokumen<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$ls_admtb_kode_dokumen;?>" readonly class="disabled">											
                  <input type="hidden" id="d_admtb_nama_dokumen<?=$i;?>" name="d_admtb_nama_dokumen<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$ls_admtb_nama_dokumen;?>" readonly class="disabled">    									 
                </td> 
                <td style="text-align:left;white-space:normal;word-wrap:break-word;">
                  <?=$ls_admtb_keterangan_dokumen;?>										
                  <input type="hidden" id="d_admtb_keterangan_dokumen<?=$i;?>" name="d_admtb_keterangan_dokumen<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$ls_admtb_keterangan_dokumen;?>" readonly class="disabled">											
                </td>
								<td style="text-align:left;">
                  <a href="#" onClick="fl_js_tap_DownloadDok('<?=$ls_admtb_path_url;?>');" href="javascript:void(0);">
                  <?=$ls_admtb_mime_type;?></a>
                </td> 	
                <td style="text-align:left;"></td>																									       																			        											
              </tr>
              <?								    							
              //$i++;//iterasi i
            }	//end while
            $ln_admtb_dtl=$i;
						
  					if ($i == 0)
  					{
  					 	?>
  						<tr class="nohover-color">
  							<td colspan="5" style="text-align: center;">-- tidak ada dokumen tambahan --</td>
  						</tr>
  						<?
  					}								
          }						
          ?>									             																
        </tbody>
				<tfoot>
    			<tr>
    				<td colspan="5">
              <input type="hidden" id="d_admtb_kounter_dtl" name="d_admtb_kounter_dtl" value="<?=$ln_admtb_dtl;?>">
              <input type="hidden" id="d_admtb_count_dtl" name="d_admtb_count_dtl" value="<?=$ln_admtb_countdtl;?>">
              <input type="hidden" name="d_admtb_showmessage" style="border-width: 0;text-align:right" readonly size="5">						
						</td>				
          </tr>	
				</tfoot>							
      </table>
      </br>
    </fieldset>
  </div>
</div>

<script language="javascript">
	function fl_js_tap_mnf_rinci(p_kode_klaim, p_kode_manfaat, p_url_path)
	{		
		var c_mid = '<?=$mid;?>';		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+p_url_path+'?kode_klaim='+p_kode_klaim+'&kode_manfaat='+p_kode_manfaat+'&mid='+c_mid+'','',980,610,'yes');
	}

	function fl_js_tap_penerima_rinci(p_kode_klaim, p_kode_tipe_penerima)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_penerima.php?kode_klaim='+p_kode_klaim+'&kode_tipe_penerima='+p_kode_tipe_penerima+'&mid='+c_mid+'','',980,630,'yes');
	}

	function fl_js_tap_DownloadDok(v_url)
	{
		let ipStorage = "<?php echo $wsIpStorage; ?>";
		NewWindow(ipStorage+v_url,'',1000,620,'no');
	}		
</script>	