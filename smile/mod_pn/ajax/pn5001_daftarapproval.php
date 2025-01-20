<?
$pagetype="report";
$gs_pagetitle = "PN5001 - DAFTAR PERSETUJUAN KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5001_daftarapproval.php

Deskripsi:
-----------
File ini dipergunakan untuk daftar persetujuan klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
28/07/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_sender 			= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 	= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
	
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
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
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">		
						 
    <table id="mydata" cellspacing="0" style="text-align:center;width:80%;">
      <tbody>						
        <tr>
        <th style="text-align:center;width:30px;">No</th>
        <th style="text-align:center;">Pejabat</th>
        <th style="text-align:center;width:80px;">Kantor</th>
        <th style="text-align:center;width:120px;">Status Persetujuan</th>
				<th style="text-align:center;width:120px;">Tgl Persetujuan</th>
				<th style="text-align:center;width:80px;">Petugas</th>					
        </tr>              					
        <?
        if ($ls_kode_klaim != "")
        {			
          $sql = "select a.kode_klaim, a.no_level, a.kode_jabatan, (select nama_jabatan from sijstk.ms_jabatan where kode_jabatan = a.kode_jabatan) nama_jabatan,
                      a.kode_kantor, nvl(a.status_approval,'T') status_approval, to_char(a.tgl_approval,'dd/mm/yyyy') tgl_approval, a.petugas_approval
                  from sijstk.pn_klaim_approval a
                  where a.kode_klaim = '$ls_kode_klaim'
                  order by a.no_level ";								               
          //echo $sql;
					$DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;				
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["NO_LEVEL"];?></td>
              <td style="text-align:left"><?=$row["NAMA_JABATAN"];?></td>  									
							<td style="text-align:center"><?=$row["KODE_KANTOR"];?></td>
							<td style="text-align:center"><input type="checkbox" disabled class="cebox" id="dcb_status_approval<?=$i;?>" name="dcb_status_approval<?=$i;?>" value="<?=$row['STATUS_APPROVAL'];?>" <?=$row['STATUS_APPROVAL']=="Y" || $row['STATUS_APPROVAL']=="ON" || $row['STATUS_APPROVAL']=="on" ? "checked" : "";?>></td>
              <td style="text-align:center"><?=$row["TGL_APPROVAL"];?></td>
							<td style="text-align:center"><?=$row["PETUGAS_APPROVAL"];?></td>							
            </tr>
            <?							    							
            $i++;//iterasi i
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
      <tr>
        <td style="text-align:right;" colspan="5"><i><b>&nbsp;</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>        																													
      </tr>												
    </table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

