<?
$pagetype="report";
$gs_pagetitle = "PN5001 - ENTRY RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5002_penetapanmanfaat_uraiancacat.php

Deskripsi:
-----------
File ini dipergunakan untuk entry manfaat biaya prothese/orthese

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
28/07/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	 	 = !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_form_penetapan	 = !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 			 			 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat from sijstk.pn_kode_manfaat a ".
         "where a.kode_manfaat = '$ls_kode_manfaat'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat	= $row['NAMA_MANFAAT'];
	
 	$gs_pagetitle = "PN5001 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat;	 		  	 
}

if(isset($_GET['delete_manfaatrinci']))
{
  $qry = "BEGIN SIJSTK.P_PN_PN5001.X_POST_DELETE_MNF_DETIL ('".$_GET['kode_klaim']."','".$_GET['kode_manfaat']."','".$_GET['no_urut']."', '$username',:p_sukses,:p_mess);END;";												 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_mess = $p_mess;	
				
	$msg = "Data sudah terhapus..";
	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&task=edit&form_penetapan=$ls_form_penetapan&sender=$ls_sender&msg=$msg');";
	echo "</script>";	
}

//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
<script language="javascript">
  function NewWindow4(mypage,myname,w,h,scroll){
    var openwin = window.parent.Ext.create('Ext.window.Window', {
      title: myname,
      collapsible: true,
      animCollapse: true,
      
      maximizable: true,
      width: w,
      height: h,
      minWidth: 450,
      minHeight: 250,
      layout: 'fit',
      html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    });
    openwin.show();
  }	
  
  function confirmDelete(delUrl) {
  	if (confirm("Are you sure you want to delete this record")) {
  	document.location = delUrl;
  	}
  }	
</script>
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionentry">
<tr> 
<td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
</tr>
</table>

<div id="formframe">
  <div id="formKiri" style="width:950px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
						 
    <table id="mydata" cellspacing="0" style="text-align:center;width:90%;">
      <tbody>						
        <tr>
        <th style="text-align:center;width:10px;">No</th>
        <th style="text-align:center;width:90px;">Tipe Penerima</th>
				<th style="text-align:center;">Keadaan</th>
				<th style="text-align:center;">Uraian Cacat</th>
				<th style="text-align:center;">% Dokter</th>
				<th style="text-align:center;">% Table</th>
        <th style="text-align:center;width:100px;">Biaya Disetujui</th>
        <th style="text-align:center;width:50px;">Catatan</th>
        <th style="text-align:center;width:100px;">Action</th>						
        </tr>              					
        <?
        if ($ls_kode_manfaat != "")
        {			
          $sql = "select 
                      a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.no_urut, a.kode_manfaat_detil, 
											(	select nama_manfaat_detil from sijstk.pn_kode_manfaat_detil 
												where kode_manfaat = a.kode_manfaat
												and kode_manfaat_detil = a.kode_manfaat_detil
												) nama_manfaat_detil, 
											a.kategori_manfaat, a.kode_tipe_penerima, c.nama_tipe_penerima, a.kd_prg, 
											a.nom_biaya_diajukan, a.nom_biaya_disetujui, 
                      a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, 
                      a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan,
											a.cacat_kode_keadaan,
											(select keterangan from sijstk.ms_lookup where tipe='KLMKEADCCT' and kode = a.cacat_kode_keadaan) cacat_nama_keadaan,
											a.cacat_persen_dokter, a.cacat_persen_table
                  from pn.pn_klaim_manfaat_detil a, sijstk.pn_kode_manfaat b, sijstk.pn_kode_tipe_penerima c
                  where a.kode_manfaat = b.kode_manfaat(+) 
                  and a.kode_tipe_penerima = c.kode_tipe_penerima(+)
                  and a.kode_klaim = '$ls_kode_klaim'
                  and a.kode_manfaat = '$ls_kode_manfaat'
                  order by a.no_urut ";								               
          $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
					$ln_tot_biaya_diajukan  =0;
					$ln_tot_biaya_disetujui =0;						
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["NO_URUT"];?></td>
              <td style="text-align:center"><?=$row["NAMA_TIPE_PENERIMA"];?></td>
							<td style="text-align:center"><?=$row["CACAT_NAMA_KEADAAN"];?></td>
							<td style="text-align:center"><?=$row["NAMA_MANFAAT_DETIL"];?></td>
							<td style="text-align:right"><?=number_format((float)$row["CACAT_PERSEN_DOKTER"],2,".",",");?></td>
							<td style="text-align:right"><?=number_format((float)$row["CACAT_PERSEN_TABLE"],2,".",",");?></td>  									
              <td style="text-align:right"><?=number_format((float)$row["NOM_BIAYA_DISETUJUI"],2,".",",");?></td>
              <td style="text-align:left"><?=$row["KETERANGAN"];?></td>
              <td>
							<?
							if ($ls_task=="view")
							{
							?>
              <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_uraiancacatentry.php?task=view&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5002_penetapanmanfaat_uraiancacat.php','Entry Rincian Manfaat',850,600,'no')"><img src="../../images/check.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;View </a>
              <?
							}else
							{
							?>
              <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_uraiancacatentry.php?task=edit&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5002_penetapanmanfaat_uraiancacat.php','Entry Rincian Manfaat',850,600,'no')"><img src="../../images/check.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;Edit </a>	|
              <a href="javascript:confirmDelete('?delete_manfaatrinci=DEL&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5002_penetapanmanfaat_uraiancacat.php')"><img src="../../images/minus.png" alt="Delete" border="0" align="absmiddle" title="Delete">delete</a>								
							<?							
							}
							?>																	
              </td>								
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_biaya_diajukan  += $row["NOM_BIAYA_DIAJUKAN"];
						$ln_tot_biaya_disetujui += $row["NOM_BIAYA_DISETUJUI"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
      <tr>
        <td style="text-align:right;" colspan="5"><i><b>Total</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>
        <td style="text-align:right"><?=number_format((float)$ln_tot_biaya_diajukan,2,".",",");?></td>  									
        <td style="text-align:right"><?=number_format((float)$ln_tot_biaya_disetujui,2,".",",");?></td>				
				<td></td>
				<td style="text-align:center;">
					<?
    			if ($ls_task!="view")
    			{
      			?>
               <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapanmanfaat_uraiancacatentry.php?task=new&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5002_penetapanmanfaat_uraiancacat.php','Entry Rincian Manfaat',850,600,'no');" href="javascript:void(0);"><img src="../../images/plus.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Entry</a>
            <?
    			}
    			?>								
        </td>																					
      </tr>	
			<tr>
				<td style="text-align:center;" colspan="9">&nbsp;				
				</td>
			</tr>				
			<tr>
				<td style="text-align:center;" colspan="9">	
  				<?
    			if ($ls_task!="view")
    			{
      			?>
               <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$ls_form_penetapan;?>?task=Edit&sender=pn5002.php&kode_klaim=<?=$ls_kode_klaim;?>&dataid=<?=$ls_kode_klaim;?>','Penetapan Manfaat',850,600,'no');" href="javascript:void(0);"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Kembali Ke Penetapan Manfaat</a>
            <?
    			}else
					{
      			?>
               <a href="#" onClick="window.close();"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Kembali Ke Penetapan Manfaat</a>
            <?					
					}
    			?>
				</td>
			</tr>																
    </table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

