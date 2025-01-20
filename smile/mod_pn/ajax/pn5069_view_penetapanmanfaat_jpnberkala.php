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
    <fieldset><legend>Manfaat Pensiun Berkala</legend>
      <table id="tblrincian1" width="100%" class="table-data2">
        <thead>													
          <tr class="hr-double-bottom">
            <th style="text-align:center;">Program</th>
            <th style="text-align:center;">Bulan ke-</th>
            <th style="text-align:center;">Bulan</th>
            <th style="text-align:right;">Berjalan</th>
            <th style="text-align:right;">Rapel</th>
            <th style="text-align:right;">Kompensasi</th>
            <th style="text-align:right;">Jumlah Berkala</th>
						<th style="text-align:center;">Batal</th>
            <th style="text-align:center;width:150px;">Action</th>
          </tr>
        </thead>			
        <tbody>			
          <?							
          if ($_POST["DATA_MANFAAT"])
          {			      														
            $ln_dtl =0;	
            $ln_tot_d_nom_kompensasi  = 0;
            $ln_tot_d_nom_rapel = 0;
            $ln_tot_d_nom_berjalan = 0;
            $ln_tot_d_nom_berkala = 0;
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_MANFAAT"]);$i++)
            {
              $ls_mnf_nm_prg 				 = $_POST["DATA_MANFAAT"][$i]["NM_PRG"] 				=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NM_PRG"];
              $ln_mnf_no_proses 		 = $_POST["DATA_MANFAAT"][$i]["NO_PROSES"] 			=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NO_PROSES"];
              $ld_mnf_blth_proses 	 = $_POST["DATA_MANFAAT"][$i]["BLTH_PROSES"] 		=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["BLTH_PROSES"];
              $ln_mnf_nom_berjalan 	 = $_POST["DATA_MANFAAT"][$i]["NOM_BERJALAN"] 	=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NOM_BERJALAN"];
              $ln_mnf_nom_rapel 		 = $_POST["DATA_MANFAAT"][$i]["NOM_RAPEL"] 			=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NOM_RAPEL"];
              $ln_mnf_nom_kompensasi = $_POST["DATA_MANFAAT"][$i]["NOM_KOMPENSASI"] =="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NOM_KOMPENSASI"];
              $ln_mnf_nom_berkala 	 = $_POST["DATA_MANFAAT"][$i]["NOM_BERKALA"] 		=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NOM_BERKALA"];
              $ln_mnf_kode_klaim 	 	 = $_POST["DATA_MANFAAT"][$i]["KODE_KLAIM"] 		=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["KODE_KLAIM"];
              $ln_mnf_no_konfirmasi  = $_POST["DATA_MANFAAT"][$i]["NO_KONFIRMASI"] 	=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["NO_KONFIRMASI"];
              $ln_mnf_kd_prg 	 	 		 = $_POST["DATA_MANFAAT"][$i]["KD_PRG"] 				=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["KD_PRG"];
							$ls_mnf_status_batal	 = $_POST["DATA_MANFAAT"][$i]["STATUS_BATAL"] 	=="T" 	 ? "" : $_POST["DATA_MANFAAT"][$i]["STATUS_BATAL"];
							$ld_mnf_tgl_batal	 		 = $_POST["DATA_MANFAAT"][$i]["TGL_BATAL"] 			=="null" ? "" : $_POST["DATA_MANFAAT"][$i]["TGL_BATAL"]; 
							$ls_mnf_flag_batal 		 = $ls_mnf_status_batal === "Y" ? ("<img src=../../images/file_cancel.gif>"." ".$ld_mnf_tgl_batal) : " ";                	
              ?>
              <tr>
                <td style="text-align:center;"><?=$ls_mnf_nm_prg;?></td>
                <td style="text-align:center;"><?=$ln_mnf_no_proses;?></td>
                <td style="text-align:center;"><?=$ld_mnf_blth_proses;?></td>
                <td style="text-align:right;"><?=number_format((float)$ln_mnf_nom_berjalan,2,".",",");?></td>
                <td style="text-align:right;"><?=number_format((float)$ln_mnf_nom_rapel,2,".",",");?></td>
                <td style="text-align:right;"><?=number_format((float)$ln_mnf_nom_kompensasi,2,".",",");?></td>
                <td style="text-align:right;"><?=number_format((float)$ln_mnf_nom_berkala,2,".",",");?></td>
								<td style="text-align:center;"><?=$ls_mnf_flag_batal;?></td>																		       																			        											
                <td align="center">
                	<a href="#" onClick="fl_js_tap_mnf_rinci_berkala('<?=$ln_mnf_kode_klaim;?>', '<?=$ln_mnf_no_konfirmasi;?>', '<?=$ln_mnf_no_proses;?>', '<?=$ln_mnf_kd_prg;?>', '<?=$ld_mnf_blth_proses;?>');"><img src="../../images/indent_right.gif" border="0" alt="Ubah Divisi" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
                </td>
              </tr>
              <?								    							
              //$i++;//iterasi i
              $ln_tot_d_nom_kompensasi  += $ls_mnf_status_batal == "Y" ? 0 : (float)($ln_mnf_nom_kompensasi);
              $ln_tot_d_nom_rapel  			+= $ls_mnf_status_batal == "Y" ? 0 : (float)($ln_mnf_nom_rapel);
              $ln_tot_d_nom_berjalan  	+= $ls_mnf_status_batal == "Y" ? 0 : (float)($ln_mnf_nom_berjalan);
              $ln_tot_d_nom_berkala  		+= $ls_mnf_status_batal == "Y" ? 0 : (float)($ln_mnf_nom_berkala);
            }	//end while
            $ln_dtl=$i;
          }						
          ?>									             																
        </tbody>
        <tfoot>
          <tr>
            <td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i>
              <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
            </td>	  		
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_berjalan,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_rapel,2,".",",");?></td>							
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_kompensasi,2,".",",");?></td>
            <td style="text-align:right;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
            <td></td>										        
          </tr>
        </tfoot>																
      </table>
    </fieldset>
  </div>
</div>
  
</br>
  
<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">	 					
    <fieldset><legend>Penerima Manfaat Berkala</legend>
      <table id="tblrincian2" width="100%" class="table-data2">
        <thead>																		
          <tr class="hr-double-bottom">
            <th style="text-align:center;">Tipe</th>
            <th style="text-align:center;">Hubungan</th>
            <th style="text-align:center;">Nama</th>
            <th style="text-align:center;">NPWP</th>
            <th style="text-align:center;">Bank</th>
            <th style="text-align:center;">No.Rek</th>
            <th style="text-align:center;">A/N</th>
            <th style="text-align:right;">Nominal</th>
            <th style="text-align:center;">Layanan Notifikasi</th>
            <th style="text-align:center; width:100px;">Action</th>
          </tr>
        </thead>
        <tbody>	
          <?							
          if ($_POST["DATA_PENERIMA"])
          {			
            $ln_dtl =0;	
            $ln_tot_d_jpnbkala_nom_berkala =0;	
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_PENERIMA"]);$i++)
            {					
              $ls_pnrm_nama_tipe_penerima 				= $_POST["DATA_PENERIMA"][$i]["NAMA_TIPE_PENERIMA"] 		 		=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_TIPE_PENERIMA"];
              $ls_pnrm_kode_penerima_berkala 			= $_POST["DATA_PENERIMA"][$i]["KODE_PENERIMA_BERKALA"] 			=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["KODE_PENERIMA_BERKALA"];
              $ls_pnrm_nama_kode_penerima_berkala = $_POST["DATA_PENERIMA"][$i]["NAMA_KODE_PENERIMA_BERKALA"] =="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_KODE_PENERIMA_BERKALA"];
              $ls_pnrm_nama_lengkap 							= $_POST["DATA_PENERIMA"][$i]["NAMA_LENGKAP"]								=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_LENGKAP"];
              $ls_pnrm_npwp 											= $_POST["DATA_PENERIMA"][$i]["NPWP"] 											=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NPWP"];
              $ls_pnrm_bank_penerima 							= $_POST["DATA_PENERIMA"][$i]["BANK_PENERIMA"] 							=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["BANK_PENERIMA"];
              $ls_pnrm_no_rekening_penerima 			= $_POST["DATA_PENERIMA"][$i]["NO_REKENING_PENERIMA"] 			=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NO_REKENING_PENERIMA"];
              $ls_pnrm_nama_rekening_penerima 		= $_POST["DATA_PENERIMA"][$i]["NAMA_REKENING_PENERIMA"] 		=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NAMA_REKENING_PENERIMA"];
              $ln_pnrm_nom_berkala 								= $_POST["DATA_PENERIMA"][$i]["NOM_BERKALA"] 								=="null" ? "" : $_POST["DATA_PENERIMA"][$i]["NOM_BERKALA"];
              $ls_pnrm_status_cek_layanan 				= $_POST["DATA_PENERIMA"][$i]["STATUS_CEK_LAYANAN"] 				=="null" ? "T" : $_POST["DATA_PENERIMA"][$i]["STATUS_CEK_LAYANAN"];
              $ls_pnrm_status_reg_notifikasi 			= $_POST["DATA_PENERIMA"][$i]["STATUS_REG_NOTIFIKASI"] 			=="null" ? "T" : $_POST["DATA_PENERIMA"][$i]["STATUS_REG_NOTIFIKASI"];
              ?>
              <tr>
                <td style="text-align:center;"><?=$ls_pnrm_nama_tipe_penerima;?></td>
                <td style="text-align:center;"><?=$ls_pnrm_nama_kode_penerima_berkala;?></td>
                <td style="text-align:center;"><?=$ls_pnrm_nama_lengkap;?></td>
                <td style="text-align:center;"><?=$ls_pnrm_npwp;?></td>
                <td style="text-align:center;"><?=$ls_pnrm_bank_penerima;?></td>
                <td style="text-align:center;"><?=$ls_pnrm_no_rekening_penerima;?></td>
                <td style="text-align:center;"><?=$ls_pnrm_nama_rekening_penerima;?></td>
                <td style="text-align:right;"><?=number_format((float)$ln_pnrm_nom_berkala,2,".",",");?></td>																		       																			        																												       																			        											
                <td style="text-align:center;">
                  <?
                  if ($ls_pnrm_status_cek_layanan=="Y")
                  {
                    ?>	
                    <?=($ls_pnrm_status_reg_notifikasi=="Y" ? "YA" : "TIDAK")?>
                    <? 
                  }else
                  {
                    ?>	
                    <font color="#ff0000">-</font>
                    <?
                  }
                  ?>
                </td>
                <td align="center">									
                	<a href="#" onClick="fl_js_tap_penerima_rinci_berkala('<?=$ln_mnf_kode_klaim;?>', '<?=$ls_pnrm_kode_penerima_berkala;?>');"><img src="../../images/user_go.png" border="0" alt="Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																				
                </td>
              </tr>
              <?								    							
              //$i++;//iterasi i
              $ln_tot_d_jpnbkala_nom_berkala  += (float)$ln_pnrm_nom_berkala;
            }	//end while
            $ln_dtl=$i;
          }						
          ?>									             																
        </tbody>
        <tfoot>
          <tr>
            <td style="text-align:right" colspan="7"><i>Total Diterima :<i>
              <input type="hidden" id="d_pnrm_kounter_dtl" name="d_pnrm_kounter_dtl" value="<?=$ln_dtl;?>">
              <input type="hidden" id="d_pnrm_count_dtl" name="d_pnrm_count_dtl" value="<?=$ln_countdtl;?>">
              <input type="hidden" name="d_pnrm_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
            </td>
            <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_jpnbkala_nom_berkala,2,".",",");?></td>
            <td>				
            </td>
          </tr>					
        </tfoot>																
      </table>
    </fieldset>
  </div>
</div>

<script language="javascript">
	function fl_js_tap_mnf_rinci_berkala(p_kode_klaim, p_no_konfirmasi, p_no_proses, p_kd_prg, p_blth_proses)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalarinci.php?kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&no_proses='+p_no_proses+'&kd_prg='+p_kd_prg+'&blth_proses='+p_blth_proses+'&mid='+c_mid+'','',980,610,'yes');
	}
	function fl_js_tap_penerima_rinci_berkala(p_kode_klaim, p_kode_penerima_berkala)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalapenerima.php?kode_klaim='+p_kode_klaim+'&kode_penerima_berkala='+p_kode_penerima_berkala+'&mid='+c_mid+'','',980,640,'yes');
	}	
</script>	