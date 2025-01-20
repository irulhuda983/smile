<?
$pagetype="report";
$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT";
require_once "../../includes/header_app.php";
include_once '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5041_penetapanmanfaat_uraiancacat.php

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
	
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}

if(isset($_GET['delete_manfaatrinci']))
{

  $qry1 = "BEGIN
  delete from pn.pn_klaim_manfaat_detil_stmb
  where kode_klaim = '".$_GET['kode_klaim']."'
  and kode_manfaat = '".$_GET['kode_manfaat']."'
  and 1=1;

  delete from pn.pn_klaim_manfaat_detil
  where kode_klaim = '".$_GET['kode_klaim']."'
  and kode_manfaat = '".$_GET['kode_manfaat']."'
  and 1=1;

  END;";
  
  $DB->parse($qry1);
  if($DB->execute()){ 

    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_REFRESH_NILAI_MANFAAT ('".$_GET['kode_klaim']."','$username',:p_sukses,:p_mess);END;";												 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_mess = $p_mess;	
          
    $msg = "Data sudah terhapus..";
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&task=edit&form_penetapan=$ls_form_penetapan&sender=$ls_sender&msg=$msg');";
    echo "</script>";	
   
          
  }else{
    $msg = "Data gagal dihapus..";
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&task=edit&form_penetapan=$ls_form_penetapan&sender=$ls_sender&msg=$msg');";
    echo "</script>";	
  } 



 
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
  	if (confirm("Tombol refresh akan menghilangkan semua uraian cacat yang telah di entry sebelumya pada klaim ini, Apakah anda yakin akan merefresh ?")) {
  	document.location = delUrl;
  	}
  }	
</script>
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionfoxrm" aria-describedby="captionfoxrmdesc">
  <tr><th></th></tr>
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>

<div id="formframe">
  <div id="formKiri" style="width:1200px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
						 
    <table id="mydata2" cellspacing="0" style="text-align:center;width:90%;" aria-describedby="mydata2desc">
      <tbody>	

				<tr>
        	<th scope="col" colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.80), rgba(0,0,0,0));"/></th>	
        </tr>	

        <tr>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="5">&nbsp;</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3">Penetapan Nilai Manfaat</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="1"></th>
        </tr>	
				
        <tr>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="5">&nbsp;</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="3"><hr/></th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;" colspan="1">&nbsp;</th>
        </tr>
										
        <tr>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:50px;">No</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:150px;">Tipe Penerima</th>
					<th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:150px;">Keadaan</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:200px;">Uraian Cacat</th>
					<th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:70px;">% Dokter</th>
					<th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:70px;">% Table</th>
					<th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Biaya Disetujui</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Catatan</th>
          <th scope="col" style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Action</th>
        </tr>

        <tr>
        <th scope="col" colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
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
              <td style="text-align:center;"><?=$row["NO_URUT"];?></td>
              <td style="text-align:center;"><?=$row["NAMA_TIPE_PENERIMA"];?></td>
							<td style="text-align:center;"><?=$row["CACAT_NAMA_KEADAAN"];?></td>
							<td style="text-align:center;"><?=$row["NAMA_MANFAAT_DETIL"];?></td>
							<td style="text-align:center;"><?=number_format((float)$row["CACAT_PERSEN_DOKTER"],2,".",",");?></td>
							<td style="text-align:center;"><?=number_format((float)$row["CACAT_PERSEN_TABLE"],2,".",",");?></td>  									
              <td style="text-align:right;"><?=number_format((float)$row["NOM_BIAYA_DISETUJUI"],2,".",",");?></td>
              <td style="text-align:center;"><?=$row["KETERANGAN"];?></td>
              <td>
							<?
							if ($ls_task=="view")
							{
							?>
              <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_uraiancacatentry.php?task=view&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_uraiancacat.php','Entry Rincian Manfaat',850,600,'no')"><img src="../../images/check.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;View </a>
              <?
							}else
							{
							?>
              <!-- <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_uraiancacatentry.php?task=edit&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_uraiancacat.php','Entry Rincian Manfaat',850,600,'no')"><img src="../../images/check.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;Edit </a>	|
              <a href="javascript:confirmDelete('?delete_manfaatrinci=DEL&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_uraiancacat.php')"><img src="../../images/minus.png" alt="Delete" border="0" align="absmiddle" title="Delete">delete</a>								 -->
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
            echo '<td colspan="9" style="text-align:center">-- Belum Ada Rincian Manfaat --</td>';
            echo '</tr>';
          }						
        }						
        ?>									             																
      </tbody>

      <tr>
      	<th scope="col" colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.50), rgba(0,0,0,0));"/></th>	
      </tr>
						
      <tr>
        <td style="text-align:right;" colspan="5"><em><strong>Total</strong></em>
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
               <a href="#" onClick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_uraiancacatentry.php?task=new&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_uraiancacat.php','Entry Rincian Manfaat',850,600,'no');" href="javascript:void(0);"><img src="../../images/plus.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;Entry</a> 	|
               <a href="javascript:confirmDelete('?delete_manfaatrinci=DEL&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&no_urut=<?=$row["NO_URUT"];?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_uraiancacat.php')"><img src="../../images/refreshx.png" height="10" weight="10" alt="Delete" border="0" align="absmiddle" title="Delete">Refresh</a>								
            <?
    			}
    			?>								
        </td>																					
      </tr>	

      <tr>
      	<th scope="col" colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.50), rgba(0,0,0,0));"/></th>	
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
include_once "../../includes/footer_app.php";
?>

