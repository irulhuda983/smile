<?
$pagetype="report";
$gs_pagetitle = "PN5002 - RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5002_penetapanmanfaat_obatrawat.php

Deskripsi:
-----------
File ini dipergunakan untuk daftar manfaat promotif/preventif

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
28/07/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_pointer_asal  = !isset($_GET['kode_pointer_asal']) ? $_POST['kode_pointer_asal'] : $_GET['kode_pointer_asal'];
$ls_id_pointer_asal  = !isset($_GET['id_pointer_asal']) ? $_POST['id_pointer_asal'] : $_GET['id_pointer_asal'];
$ls_kode_realisasi	 = !isset($_GET['kode_realisasi']) ? $_POST['kode_realisasi'] : $_GET['kode_realisasi'];
$ls_kode_manfaat	 	 = !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_form_penetapan	 = !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 					 	 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ln_no_level				 = !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];

	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat from sijstk.pn_kode_manfaat a ".
         "where a.kode_manfaat = '$ls_kode_manfaat'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat	= $row['NAMA_MANFAAT'];
	
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat;	 		  	 
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
  <div id="formKiri" style="width:880px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>">
		<input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>">
		<input type="hidden" id="kode_realisasi" name="kode_realisasi" value="<?=$ls_kode_realisasi;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
						 
    <table id="mydata" cellspacing="0" style="text-align:center;width:95%;">
      <tbody>						
        <tr>
        <th style="text-align:center;width:40px;">No</th>
        <th style="text-align:center;width:120px;">Tipe Penerima</th>
        <th style="text-align:center;width:120px;">Biaya Diajukan</th>
        <th style="text-align:center;width:120px;">Biaya Disetujui</th>
				<th style="text-align:center;width:120px;">PPN</th>
				<th style="text-align:center;width:120px;">PPh</th>
				<th style="text-align:center;width:120px;">Biaya Netto</th>
        <th style="text-align:center;">Catatan</th>					
        </tr>              					
        <?
        if ($ls_kode_manfaat != "")
        {			
          $sql = "select 
                      a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.no_urut, a.kode_manfaat_detil, a.kategori_manfaat, 
                      a.kode_tipe_penerima, c.nama_tipe_penerima, a.kd_prg, a.nom_biaya_diajukan, a.nom_biaya_disetujui, 
                      a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, 
                      a.nom_ppn, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan
                  from sijstk.pn_klaim_manfaat_detil a, sijstk.pn_kode_manfaat b, sijstk.pn_kode_tipe_penerima c
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
					$ln_tot_ppn =0;
					$ln_tot_pph =0;
					$ln_tot_biaya_netto =0;						
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["NO_URUT"];?></td>
              <td style="text-align:center"><?=$row["NAMA_TIPE_PENERIMA"];?></td>
              <td style="text-align:right"><?=number_format((float)$row["NOM_BIAYA_DIAJUKAN"],2,".",",");?></td>  									
              <td style="text-align:right"><?=number_format((float)$row["NOM_BIAYA_DISETUJUI"],2,".",",");?></td>
							<td style="text-align:right"><?=number_format((float)$row["NOM_PPN"],2,".",",");?></td>
							<td style="text-align:right"><?=number_format((float)$row["NOM_PPH"],2,".",",");?></td>
							<td style="text-align:right"><?=number_format((float)$row["NOM_MANFAAT_NETTO"],2,".",",");?></td>
              <td style="text-align:left"><?=$row["KETERANGAN"];?></td>							
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_biaya_diajukan  += $row["NOM_BIAYA_DIAJUKAN"];
						$ln_tot_biaya_disetujui += $row["NOM_BIAYA_DISETUJUI"];
						$ln_tot_ppn 						+= $row["NOM_PPN"];
						$ln_tot_pph 						+= $row["NOM_PPH"];
						$ln_tot_biaya_netto 		+= $row["NOM_MANFAAT_NETTO"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
      <tr>
        <td style="text-align:right;" colspan="2"><i><b>Total</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>
        <td style="text-align:right"><?=number_format((float)$ln_tot_biaya_diajukan,2,".",",");?></td>  									
        <td style="text-align:right"><?=number_format((float)$ln_tot_biaya_disetujui,2,".",",");?></td>
				<td style="text-align:right"><?=number_format((float)$ln_tot_ppn,2,".",",");?></td>
				<td style="text-align:right"><?=number_format((float)$ln_tot_pph,2,".",",");?></td>
				<td style="text-align:right"><?=number_format((float)$ln_tot_biaya_netto,2,".",",");?></td>				
				<td></td>																				
      </tr>
			<tr>
				<td style="text-align:center;" colspan="8">&nbsp;				
				</td>
			</tr>				
			<tr>
				<td style="text-align:center;" colspan="8">	
					<a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$ls_form_penetapan;?>?task=Edit&sender=pn5002.php&id_pointer_asal=<?=$ls_id_pointer_asal;?>&kode_pointer_asal=<?=$ls_kode_pointer_asal;?>&kode_realisasi=<?=$ls_kode_realisasi;?>&task=<?=$ls_task;?>&no_level=<?=$ln_no_level;?>&kode_klaim=<?=$ls_kode_klaim;?>&dataid=<?=$ls_kode_klaim;?>&activetab=2','Penetapan Manfaat',850,600,'no');" href="javascript:void(0);"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Kembali Ke Penetapan Manfaat</a>
				</td>
			</tr>																
    </table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

