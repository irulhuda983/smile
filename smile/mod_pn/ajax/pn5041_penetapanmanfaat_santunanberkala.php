<?
$pagetype="report";
$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5041_penetapanmanfaat_santunanberkala.php

Deskripsi:
-----------
File ini dipergunakan untuk entry manfaat santunan berkala yg dibayarkan sekaligus

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
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}

if(isset($_GET['delete_manfaatrinci']))
{
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_DELETE_MNF_DETIL ('".$_GET['kode_klaim']."','".$_GET['kode_manfaat']."','".$_GET['no_urut']."', '$username',:p_sukses,:p_mess);END;";												 	
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

<table class="captionfoxrm">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>

<div id="formframe">
  <div id="formKiri" style="width:880px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
						 
    <table id="mydata2" cellspacing="0" style="text-align:center;width:95%;">
      <tbody>	
				<tr>
        	<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.80), rgba(0,0,0,0));"/></th>	
        </tr>	

        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">Penetapan Nilai Manfaat</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="1"></th>
        </tr>	
				
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2">&nbsp;</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="2"><hr/></th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="1">&nbsp;</th>
        </tr>
										
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:50px;">No</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:150px;">Tipe Penerima</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Biaya Disetujui</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Catatan</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Action</th>
        </tr>

        <tr>
        <th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
             					
        <?
        if ($ls_kode_manfaat != "")
        {			
          $sql = "select 
                      a.kode_klaim, a.kode_manfaat, b.nama_manfaat, a.no_urut, a.kode_manfaat_detil, a.kategori_manfaat, 
                      a.kode_tipe_penerima, c.nama_tipe_penerima, a.kd_prg, a.nom_biaya_diajukan, a.nom_biaya_disetujui, 
                      a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, 
                      a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan
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
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["NO_URUT"];?></td>
              <td style="text-align:center"><?=$row["NAMA_TIPE_PENERIMA"];?></td>  									
              <td style="text-align:right"><?=number_format((float)$row["NOM_BIAYA_DISETUJUI"],2,".",",");?></td>
							<td style="text-align:center"><?=$row["KETERANGAN"];?></td>
              <td>
							<?
							if ($ls_task=="view")
							{
							?>
              <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_santunanberkalaentry.php?task=view&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_santunanberkala.php','Entry Rincian Manfaat',850,600,'no')"><img src="../../images/check.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;View </a>
              <?
							}else
							{
							?>
              <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_santunanberkalaentry.php?task=edit&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_santunanberkala.php','Entry Rincian Manfaat',850,600,'no')"><img src="../../images/check.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;Edit </a>	|
              <a href="javascript:confirmDelete('?delete_manfaatrinci=DEL&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_santunanberkala.php')"><img src="../../images/minus.png" alt="Delete" border="0" align="absmiddle" title="Delete">delete</a>									
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
					
          if ($i == 0) {
            echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
            echo '<td colspan="5" style="text-align:center">-- Belum Ada Rincian Manfaat --</td>';
            echo '</tr>';
          }						
        }						
        ?>									             																
      </tbody>
			
      <tr>
      	<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.20), rgba(0,0,0,0));"/></th>	
      </tr>
						
      <tr>
        <td style="text-align:right;" colspan="2"><i><b>Total</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>        									
        <td style="text-align:right"><?=number_format((float)$ln_tot_biaya_disetujui,2,".",",");?></td>				
				<td colspan="1"></td>
				<td style="text-align:center;">
					<?
    			if ($ls_task!="view")
    			{
      			?>
               <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_santunanberkalaentry.php?task=new&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_santunanberkala.php','Entry Rincian Manfaat',850,600,'no');" href="javascript:void(0);"><img src="../../images/plus.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Entry</a>	
            <?
    			}
    			?>			
        </td>																					
      </tr>
			
      <tr>
      	<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.20), rgba(0,0,0,0));"/></th>	
      </tr>
						
			<tr>
				<td style="text-align:center;" colspan="5">&nbsp;				
				</td>
			</tr>		
					
			<tr>
				<td style="text-align:center;" colspan="5">	
  				<?
    			if ($ls_task!="view")
    			{
      			?>
               <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/<?=$ls_form_penetapan;?>?task=Edit&sender=pn5041.php&kode_klaim=<?=$ls_kode_klaim;?>&dataid=<?=$ls_kode_klaim;?>','Penetapan Manfaat',850,600,'no');" href="javascript:void(0);"><img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Kembali Ke Penetapan Manfaat</a>
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

