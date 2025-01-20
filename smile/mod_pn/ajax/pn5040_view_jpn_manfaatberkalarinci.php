<?
$pagetype="report";
$gs_pagetitle = "PN5001 - RINCIAN MANFAAT PENSIUN BERKALA";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5040_tabdataklaim_jpnbkalarinci.php

Deskripsi:
-----------
File ini dipergunakan untuk rincian manfaat pensiun berkala

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
18/09/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_konfirmasi	 	 = !isset($_GET['no_konfirmasi']) ? $_POST['no_konfirmasi'] : $_GET['no_konfirmasi'];
$ln_no_proses	 			 = !isset($_GET['no_proses']) ? $_POST['no_proses'] : $_GET['no_proses'];
$ls_kd_prg	 			 	 = !isset($_GET['kd_prg']) ? $_POST['kd_prg'] : $_GET['kd_prg'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_sender_activetab = !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];

$sql = "select to_char(blth_proses,'mm/yyyy') blth_proses, ". 
		 	 " 			 (select keterangan||' '||to_char(blth_proses,'yyyy') from sijstk.ms_lookup where tipe='BULAN' and kode = to_char(blth_proses,'mm')) ket_blth ".
		 	 "from pn.pn_klaim_berkala_rekap ".
       "where kode_klaim = '$ls_kode_klaim' ".
       "and no_konfirmasi = '$ln_no_konfirmasi' ".
       "and no_proses =  '$ln_no_proses' ".
       "and kd_prg =  '$ls_kd_prg' ";	 
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_blth_proses = $row["BLTH_PROSES"];
$ls_ket_blth_proses = $row["KET_BLTH"];

$gs_pagetitle = $gs_pagetitle." YANG AKAN DIBAYARKAN BULAN ".$ls_ket_blth_proses;

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

  function refreshParent() 
  {																							
    <?php		
    	echo "window.location.replace('../ajax/$ls_sender?&kode_klaim=$ls_kode_klaim&no_konfirmasi=$ln_no_konfirmasi');";
    ?>	
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

<table class="captionfoxrm">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>
		
<div id="formframe">
  <div id="formKiri" style="width:1100px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
		<input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">	
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
		<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
		<input type="hidden" id="no_proses" name="no_proses" value="<?=$ln_no_proses;?>">
		<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
				 
    <!--<table id="mydata" cellspacing="0" style="text-align:center;width:95%;">-->
		<table id="tblrincian1" width="95%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
      <tbody>		
				<tr>
					<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
				</tr>								
        <tr>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Manfaat</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prg</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bln Berkala</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jml Berkala</th>
					<!--<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Diproses Bulan ke-</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan Proses</th>-->
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Keterangan</th>					
        </tr>
				<tr>
					<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				              					
        <?
        if ($ln_no_proses != "")
        {			
          $sql = "select 
                      a.kode_klaim, a.no_konfirmasi, a.no_detil, to_char(a.blth_berkala,'mm/yyyy') blth_berkala, 
											to_char(a.blth_proses,'mm/yyyy') blth_proses, a.no_proses, 
                      a.kode_manfaat, b.nama_manfaat, a.kd_prg, c.nm_prg, a.nom_kompensasi, 
                      a.nom_rapel, a.nom_berjalan, a.nom_berkala, 
                      a.keterangan
                  from sijstk.pn_klaim_berkala_detil a, sijstk.pn_kode_manfaat b, sijstk.ms_prg c
                  where a.kode_manfaat = b.kode_manfaat and a.kd_prg = c.kd_prg
                  and a.kode_klaim = '$ls_kode_klaim'
                  and a.no_konfirmasi = '$ln_no_konfirmasi'
                  and a.no_proses = '$ln_no_proses'
									and nvl(a.status_batal,'T') = 'T'
                  order by a.no_detil ";	
					//echo $sql;											               
          $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
          $ln_tot_d_nom_kompensasi  = 0;
          $ln_tot_d_nom_rapel = 0;
          $ln_tot_d_nom_berjalan = 0;
          $ln_tot_d_nom_berkala = 0;							
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_MANFAAT"];?></td>
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NM_PRG"];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["BLTH_BERKALA"];?></td>  								
              <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_BERJALAN"],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_RAPEL"],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_KOMPENSASI"],2,".",",");?></td>
							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_BERKALA"],2,".",",");?></td>
							<!--<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_PROSES'];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_PROSES'];?></td>-->
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["KETERANGAN"];?></td>														
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
						$ln_tot_d_nom_rapel  += $row["NOM_RAPEL"];
						$ln_tot_d_nom_berjalan  += $row["NOM_BERJALAN"];
						$ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="3"><i><b>Total Keseluruhan</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>        									
        <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berjalan,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_rapel,2,".",",");?></td>
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_kompensasi,2,".",",");?></td>	
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>				
				<td colspan="1"></td>																					
      </tr>
			<tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>													
    	<tr>
				<td colspan="10" style="text-align:center;">
					<input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />			
				</td>	
			</tr>
		</table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

