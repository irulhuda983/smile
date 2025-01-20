<?
/* ============================================================================
Ket : Form ini digunakan untuk tab Update Status Kelengkapan Administrasi
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/	
?>		
<div id="formKiri">					
  <fieldset style="width:935px;"><legend><b><i><font color="#009999">Update Status Kelengkapan Administrasi :</font></i></b></legend>
  	</br>
    <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>	
				<tr>
					<th colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Penyerahan Dokumen</th>					    							
        </tr>	
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3"></th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3"><hr/></th>					    							
        </tr>												
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:40px;">No</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:350px;">Nama Dokumen</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Mandatory</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama File</th>					    							
        </tr>
				<tr>
					<th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>
        <?						
        if ($ls_kode_klaim!="")
        {			
          $sql = "select a.kode_klaim, a.kode_dokumen, a.no_urut, b.nama_dokumen, to_char(a.tgl_diserahkan,'dd/mm/yyyy') tgl_diserahkan, ".
                 "    a.ringkasan, a.url, a.keterangan, nvl(a.status_diserahkan,'T') status_diserahkan, ".
								 "		nvl(a.flag_mandatory,'T') flag_mandatory, a.syarat_tahap_ke, a.nama_file ".
                 "from sijstk.pn_klaim_dokumen a, sijstk.pn_kode_dokumen b ".
                 "where a.kode_dokumen = b.kode_dokumen(+) ".
                 "and a.kode_klaim = '$ls_kode_klaim' ".
                 "order by a.no_urut ";          
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;										
          while ($row = $DB->nextrow())
          {
          ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;">	
								<?=$row['NO_URUT'];?>											
              	<input type="hidden" id="d_adm_no_urut<?=$i;?>" name="d_adm_no_urut<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$row['NO_URUT'];?>" readonly class="disabled">    									 
              </td> 																
              <td style="text-align:left; font: 10px Verdana, Arial, Helvetica, sans-serif;">
								<?=$row['NAMA_DOKUMEN'];?>											
								<input type="hidden" id="d_adm_kode_dokumen<?=$i;?>" name="d_adm_kode_dokumen<?=$i;?>" size="2" style="border-width: 1;text-align:center" value="<?=$row['KODE_DOKUMEN'];?>" readonly class="disabled">											
              	<input type="hidden" id="d_adm_nama_dokumen<?=$i;?>" name="d_adm_nama_dokumen<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$row['NAMA_DOKUMEN'];?>" readonly class="disabled">    									 
              </td> 
              <td align="center">
              	<?=($row["FLAG_MANDATORY"]=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
								<input type="hidden" id="d_adm_flag_mandatory<?=$i;?>" name="d_adm_flag_mandatory<?=$i;?>" size="50" style="border-width: 1;text-align:left" value="<?=$row['FLAG_MANDATORY'];?>" readonly class="disabled">																																																													
              </td>						
              <td align="center">
								<input type="checkbox" disabled class="cebox" id="dcb_adm_status_diserahkan<?=$i;?>" name="dcb_adm_status_diserahkan<?=$i;?>" value="<?=$row['STATUS_DISERAHKAN'];?>" <?=$row['STATUS_DISERAHKAN']=="Y" || $row['STATUS_DISERAHKAN']=="ON" || $row['STATUS_DISERAHKAN']=="on" ? "checked" : "";?>>
								<input type="hidden" id="d_adm_status_diserahkan<?=$i;?>" name="d_adm_status_diserahkan<?=$i;?>" value="<?=$row['STATUS_DISERAHKAN'];?>">
							</td>
							<td align="center">
								<?=$row['TGL_DISERAHKAN'];?>	
              	<input type="hidden" id="d_adm_tgl_diserahkan<?=$i;?>" name="d_adm_tgl_diserahkan<?=$i;?>" size="10" style="border-width: 0;text-align:center" value="<?=$row['TGL_DISERAHKAN'];?>" readonly>    									 
              </td> 	
              <td align="center">
                <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_download_dok.php?&kode_klaim=<?=$ls_kode_klaim;?>&kode_dokumen=<?=$row["KODE_DOKUMEN"];?>&TASK=DOWNLOAD_DOK','upload_tk',970,600,'yes')" href="javascript:void(0);">
								<?=$row['NAMA_FILE'];?></a>
								<input type="hidden" id="d_adm_nama_file<?=$i;?>" name="d_adm_nama_file<?=$i;?>" size="10" style="border-width: 1;text-align:left" value="<?=$row['NAMA_FILE'];?>">
              </td>																									       																			        											
            </tr>
            <?								    							
            $i++;//iterasi i
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
      <tr>
        <td style="text-align:center" colspan="6"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></b>
          <input type="hidden" id="d_adm_kounter_dtl" name="d_adm_kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="d_adm_count_dtl" name="d_adm_count_dtl" value="<?=$ln_countdtl;?>">
          <input type="hidden" name="d_adm_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
        </td>
      </tr>																					
    </table>
    </br>
  </fieldset>
	</br>
	</br>
</div>	
