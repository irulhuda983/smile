<?
$pagetype="report";
$gs_pagetitle = "PN55040 - RINCIAN ALOKASI KOMPENSASI SELISIH PEMBAYARAN JP BERKALA";
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
$ls_kode_kompensasi	 = !isset($_GET['kode_kompensasi']) ? $_POST['kode_kompensasi'] : $_GET['kode_kompensasi'];
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_sender_activetab = !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];

$gs_pagetitle = $gs_pagetitle;

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
		<input type="hidden" id="kode_kompensasi" name="kode_kompensasi" value="<?=$ln_kode_kompensasi;?>">
		
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
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bln Bayar</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Keterangan</th>					
        </tr>
				<tr>
					<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
				</tr>				              					
        <?
        if ($ls_kode_kompensasi != "")
        {			
          $sql = "select 
                      a.kode_kompensasi, a.kode_klaim, b.kode_manfaat, b.kd_prg, c.nm_prg, 
											(select nama_manfaat from sijstk.pn_kode_manfaat where kode_manfaat=b.kode_manfaat) nama_manfaat,
                      to_char(b.blth_berkala,'mm/yyyy') blth_berkala, to_char(b.blth_proses,'mm/yyyy') blth_proses, 
											a.nom_berkala nom_kompensasi, a.keterangan 
                  from sijstk.pn_klaim_berkala_detil_kmpsasi a, sijstk.pn_klaim_berkala_detil b, sijstk.ms_prg c
                  where a.kode_klaim = b.kode_klaim and a.no_konfirmasi = b.no_konfirmasi and b.kd_prg = c.kd_prg
                  and a.no_detil = b.no_detil
                  and a.kode_kompensasi = '$ls_kode_kompensasi' 
									order by b.no_konfirmasi, b.no_detil";	
					//echo $sql;											               
          $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
          $ln_tot_d_nom_kompensasi  = 0;						
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_MANFAAT"];?></td>
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NM_PRG"];?></td>
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["BLTH_BERKALA"];?></td> 
							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["BLTH_PROSES"];?></td>  								
              <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row["NOM_KOMPENSASI"],2,".",",");?></td>
              <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["KETERANGAN"];?></td>														
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
          }	//end while
          $ln_dtl=$i;
        }						
        ?>									             																
      </tbody>
			<tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
      <tr>
        <td style="text-align:right;" colspan="4"><i><b>Total Keseluruhan</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>        									
				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_kompensasi,2,".",",");?></td>				
				<td colspan="1"></td>																					
      </tr>
			<tr><td colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>													
    	<tr>
				<td colspan="10" style="text-align:center;">
					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="               TUTUP               " />			
				</td>	
			</tr>
		</table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

