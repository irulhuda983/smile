<?
$pagetype="report";
$gs_pagetitle = "PN5002 - DETIL RINCIAN MANFAAT STMB";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5002_penetapanmanfaat_stmb.php

Deskripsi:
-----------
File ini dipergunakan untuk entry manfaat biaya transportasi

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
$ln_no_urut	 	 			 = !isset($_GET['no_urut']) ? $_POST['no_urut'] : $_GET['no_urut'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat from sijstk.pn_kode_manfaat a ".
         "where a.kode_manfaat = '$ls_kode_manfaat'";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat	= $row['NAMA_MANFAAT'];
	
 	$gs_pagetitle = "PN5002 "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")";	 		  	 
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
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
						 
    <table id="mydata2" cellspacing="0" style="text-align:center;width:70%;">
      <tbody>		

				<tr>
        	<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.80), rgba(0,0,0,0));"/></th>	
        </tr>	
		
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:50px;">Periode Ke</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:70px;">Persen (%)</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:70px;">Jml Hari</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Upah Harian</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Nilai STMB</th>
        </tr>

        <tr>
        <th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
								             					
        <?
        if ($ls_kode_manfaat != "")
        {			
          $sql = "select 
                     kode_klaim, kode_manfaat, no_urut, periode_ke, persen, jml_hari, nom_upah_harian, nom_stmb
                  from sijstk.pn_klaim_manfaat_detil_stmb a
                  where a.kode_klaim = '$ls_kode_klaim'
                  and a.kode_manfaat = '$ls_kode_manfaat'
									and a.no_urut = '$ln_no_urut'
                  order by a.periode_ke ";								               
          $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
					$ln_jml_hari =0;
					$ln_nom_stmb  =0;			
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["PERIODE_KE"];?></td>
              <td style="text-align:center"><?=$row["PERSEN"];?></td>
              <td style="text-align:center"><?=number_format((float)$row["JML_HARI"],0,".",",");?></td> 
							<td style="text-align:right"><?=number_format((float)$row["NOM_UPAH_HARIAN"],2,".",",");?></td>  									
              <td style="text-align:right"><?=number_format((float)$row["NOM_STMB"],2,".",",");?></td>							
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_jml_hari  += $row["JML_HARI"];
						$ln_tot_stmb += $row["NOM_STMB"];
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
      <th colspan="5"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
      </tr>
							
      <tr>
        <td style="text-align:right;" colspan="2"><i><b>Total</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>
        <td style="text-align:center"><?=number_format((float)$ln_tot_jml_hari,0,".",",");?></td>
				<td style="text-align:right"></td>  									
        <td style="text-align:right"><?=number_format((float)$ln_tot_stmb,2,".",",");?></td>																												
      </tr>	
			
      <tr>
      	<th colspan="5"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.50), rgba(0,0,0,0));"/></th>	
      </tr>
																	
    </table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

