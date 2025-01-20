<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
/* ============================================================================
Ket : Form ini digunakan untuk tab Update Status Kelengkapan Administrasi
Hist: - 20/07/2017 : Pembuatan Form (Tim SMILE)
			- 22/10/2019 : Sentralisasi Rekening dan Mengalihkan direct conn ke ws								 						 
-----------------------------------------------------------------------------*/	
?>		
<div class="div-row">
  <div class="div-col" style="width:100%; max-height: 100%;">
    <fieldset>
      <legend>Status Kelengkapan Dokumen Administrasi</legend>
      <table id="tblrincianDok" width="100%" class="table-data">
        <thead>	
					<tr>
            <th colspan="3">&nbsp;</th>
            <th colspan="3" style="text-align: center" class="hr-double-bottom"><font color="#009999">Penyerahan Dokumen</font></th>					    							
          </tr>												
          <tr class="hr-double-bottom">
            <th style="width:3%;">No</th>
            <th style="width:60%;">Nama Dokumen</th>
            <th>Mandatory</th>
            <th>Status</th>
            <th>Tanggal</th>
            <th>Nama File</th>					    							
          </tr>
        </thead>
        <tbody>
          <?						
          if ($_POST["DATA_DOKUMEN"])
          {									              					
            $i=0;		
            $ln_dtl =0;
            for($i=0;$i<ExtendedFunction::count($_POST["DATA_DOKUMEN"]);$i++)										
            {
						 	$ls_kode_klaim 				= $_POST["DATA_DOKUMEN"][$i]["KODE_KLAIM"] 				=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["KODE_KLAIM"];
							$ln_no_urut 					= $_POST["DATA_DOKUMEN"][$i]["NO_URUT"] 					=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["NO_URUT"];
							$ls_kode_dokumen 			= $_POST["DATA_DOKUMEN"][$i]["KODE_DOKUMEN"] 			=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["KODE_DOKUMEN"];		
							$ls_nama_dokumen 			= $_POST["DATA_DOKUMEN"][$i]["NAMA_DOKUMEN"] 			=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["NAMA_DOKUMEN"];
							$ls_flag_mandatory 		= $_POST["DATA_DOKUMEN"][$i]["FLAG_MANDATORY"] 		=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["FLAG_MANDATORY"];
							$ls_status_diserahkan = $_POST["DATA_DOKUMEN"][$i]["STATUS_DISERAHKAN"] =="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["STATUS_DISERAHKAN"];		
							$ld_tgl_diserahkan 		= $_POST["DATA_DOKUMEN"][$i]["TGL_DISERAHKAN"] 		=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["TGL_DISERAHKAN"];
							$ls_kode_dokumen 			= $_POST["DATA_DOKUMEN"][$i]["KODE_DOKUMEN"] 			=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["KODE_DOKUMEN"];
							$ls_nama_file 				= $_POST["DATA_DOKUMEN"][$i]["NAMA_FILE"] 				=="null" ? "" : $_POST["DATA_DOKUMEN"][$i]["NAMA_FILE"];																															
            ?>
              <tr>
                <td style="text-align:center;">	
                  <?=$ln_no_urut;?>											
                  <input type="hidden" id="d_adm_no_urut<?=$i;?>" name="d_adm_no_urut<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$ln_no_urut;?>" readonly class="disabled">    									 
                </td> 																
                <td style="text-align:left;white-space:normal;word-wrap:break-word;">
                  <?=$ls_nama_dokumen;?>										
                  <input type="hidden" id="d_adm_kode_dokumen<?=$i;?>" name="d_adm_kode_dokumen<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$ls_kode_dokumen;?>" readonly class="disabled">											
                  <input type="hidden" id="d_adm_nama_dokumen<?=$i;?>" name="d_adm_nama_dokumen<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$ls_nama_dokumen;?>" readonly class="disabled">    									 
                </td> 
                <td style="text-align:left;">
                  <?=($ls_flag_mandatory=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
                  <input type="hidden" id="d_adm_flag_mandatory<?=$i;?>" name="d_adm_flag_mandatory<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$ls_flag_mandatory;?>" readonly class="disabled">																																																													
                </td>						
                <td style="text-align:left;">
                  <input type="checkbox" disabled class="cebox" id="dcb_adm_status_diserahkan<?=$i;?>" name="dcb_adm_status_diserahkan<?=$i;?>" value="<?=$ls_status_diserahkan;?>" <?=$ls_status_diserahkan=="Y" || $ls_status_diserahkan=="ON" || $ls_status_diserahkan=="on" ? "checked" : "";?>>
                  <input type="hidden" id="d_adm_status_diserahkan<?=$i;?>" name="d_adm_status_diserahkan<?=$i;?>" value="<?=$ls_status_diserahkan;?>">
                </td>
                <td style="text-align:left;">
									<?=$ld_tgl_diserahkan;?>	
                  <input type="hidden" id="d_adm_tgl_diserahkan<?=$i;?>" name="d_adm_tgl_diserahkan<?=$i;?>" size="10" style="border-width: 0;text-align:center" value="<?=$ld_tgl_diserahkan;?>" readonly>    									 
                </td> 	
                <td style="text-align:left;">
                  <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_download_dok.php?&kode_klaim=<?=$ls_kode_klaim;?>&kode_dokumen=<?=$ls_kode_dokumen;?>&TASK=DOWNLOAD_DOK','upload_dok',980,580,'yes')" href="javascript:void(0);">
                  <?=$ls_nama_file;?></a>
                  <input type="hidden" id="d_adm_nama_file<?=$i;?>" name="d_adm_nama_file<?=$i;?>" size="10" style="border-width: 1;text-align:left" value="<?=$ls_nama_file;?>">
                </td>																									       																			        											
              </tr>
              <?								    							
              //$i++;//iterasi i
            }	//end while
            $ln_dtl=$i;
          }						
          ?>									             																
        </tbody>
        <input type="hidden" id="d_adm_kounter_dtl" name="d_adm_kounter_dtl" value="<?=$ln_dtl;?>">
        <input type="hidden" id="d_adm_count_dtl" name="d_adm_count_dtl" value="<?=$ln_countdtl;?>">
        <input type="hidden" name="d_adm_showmessage" style="border-width: 0;text-align:right" readonly size="5">
      </table>
      </br>
    </fieldset>
  </div>
</div>
