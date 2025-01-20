<?
$pagetype="report";
$gs_pagetitle = "PN5002 - HISTORI PENERIMAAN MANFAAT BEASISWA";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5041_penetapanmanfaat_beasiswatkihistory.php

Deskripsi:
-----------
File ini dipergunakan untuk history penerimaan manfaat beasiswa

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
29/01/2019 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	 		 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	 	 = !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_nik_penerima		 = !isset($_GET['nik_penerima']) ? $_POST['nik_penerima'] : $_GET['nik_penerima'];
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
	
 	$gs_pagetitle = "PN5002 "." - HISTORI PENERIMAAN ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")";	 		  	 
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
  <div id="formKiri" style="width:1050px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
    <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
						 
    <table id="mydata2" cellspacing="0" style="text-align:center;width:100%;">
      <tbody>		

				<tr>
        	<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.80), rgba(0,0,0,0));"/></th>	
        </tr>	
		
        <tr>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">NIK Penerima</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:200px;">Nama Penerima</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:80px;">Tgl Lahir</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:150px;">Jenis</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Jenjang</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:50px;">Tingkat</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:80px;">Thn Ajaran</th>
					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:80px;">Mnf Beasiswa</th>
				  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:120px;">Kode Klaim</th>
          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;width:100px;">Tgl Penetapan</th>
        </tr>

        <tr>
        <th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
								             					
        <?
        if ($ls_kode_klaim != "" && $ls_kode_manfaat != "")
        {			
          $sql = "select 
                      kode_klaim, beasiswa_nik_penerima, beasiswa_penerima, to_char(beasiswa_tgllahir_penerima,'dd/mm/yyyy') beasiswa_tgllahir_penerima,  
											to_char(tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, 
                      jenis, (select keterangan from sijstk.ms_lookup where tipe='KLMJNSBEAS' and kode = tt.jenis) nama_jenis,
                      jenjang, (select keterangan from sijstk.ms_lookup where tipe='TKSKOLAH' and kode = tt.jenjang) nama_jenjang,
                      tingkat, tahun_ajaran, nom_beasiswa 
                  from
                  (
                      select 
                          a.kode_klaim, a.beasiswa_nik_penerima, a.beasiswa_penerima, a.beasiswa_tgllahir_penerima,
                          (select nvl(tgl_penetapan,tgl_klaim) from sijstk.pn_klaim where kode_klaim = a.kode_klaim) tgl_penetapan,
                          beasiswa_jenis jenis, beasiswa_jenjang_pendidikan jenjang, beasiswa_kini_tingkat tingkat, 
                          beasiswa_kini_thn tahun_ajaran, beasiswa_kini_nom nom_beasiswa 
                      from sijstk.pn_klaim_manfaat_detil a
                      where kode_manfaat = '$ls_kode_manfaat'
                      and nvl(nom_biaya_disetujui,0)<>0
                      and nvl(beasiswa_nik_penerima,'AbC') like nvl('$ls_nik_penerima','%')
                      and beasiswa_kini_thn is not null
                      and kode_klaim in
                      ( 
                          select kode_klaim from sijstk.pn_klaim 
                          start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T' 
                          connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' 
                      )
                      UNION ALL
                      select 
                          a.kode_klaim, a.beasiswa_nik_penerima, a.beasiswa_penerima, a.beasiswa_tgllahir_penerima,
                          (select nvl(tgl_penetapan,tgl_klaim) from sijstk.pn_klaim where kode_klaim = a.kode_klaim) tgl_penetapan,
                          beasiswa_rapel_jenis jenis, beasiswa_rapel_jenjang jenjang, beasiswa_rapel_tingkat tingkat,  
                          beasiswa_rapel_thn tahun_ajaran, beasiswa_rapel_nom nom_beasiswa
                      from sijstk.pn_klaim_manfaat_detil a
                      where kode_manfaat = '$ls_kode_manfaat'
                      and nvl(nom_biaya_disetujui,0)<>0
                      and nvl(beasiswa_nik_penerima,'AbC') like nvl('$ls_nik_penerima','%')
                      and beasiswa_rapel_thn is not null
                      and kode_klaim in
                      ( 
                          select kode_klaim from sijstk.pn_klaim 
                          start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T' 
                          connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' 
                      )
                  ) tt
                  order by beasiswa_nik_penerima, tahun_ajaran desc";
					//echo $sql;								 							               
          $DB->parse($sql);
          $DB->execute();							              					
          $i=0;		
          $ln_dtl =0;
					$ln_tot_beasiswa  =0;			
          while ($row = $DB->nextrow())
          {
            ?>
            <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
              <td style="text-align:center"><?=$row["BEASISWA_NIK_PENERIMA"];?></td>
							<td style="text-align:center"><?=$row["BEASISWA_PENERIMA"];?></td>
							<td style="text-align:center"><?=$row["BEASISWA_TGLLAHIR_PENERIMA"];?></td>
							<td style="text-align:center"><?=$row["NAMA_JENIS"];?></td>
							<td style="text-align:center"><?=$row["NAMA_JENJANG"];?></td>
							<td style="text-align:center"><?=$row["TINGKAT"];?></td>
							<td style="text-align:center"><?=$row["TAHUN_AJARAN"];?></td>
              <td style="text-align:right"><?=number_format((float)$row["NOM_BEASISWA"],2,".",",");?></td>		
							<td style="text-align:center"><?=$row["KODE_KLAIM"];?></td>
              <td style="text-align:center"><?=$row["TGL_PENETAPAN"];?></td>			
            </tr>
            <?							    							
            $i++;//iterasi i
						$ln_tot_beasiswa  += $row["NOM_BEASISWA"];
          }	//end while
          $ln_dtl=$i;
					
          if ($i == 0) {
            echo '<tr bgcolor="'.($i%2 ? "#f3f3f3" : "#ffffff").'" style="height: 26px;">';
            echo '<td colspan="10" style="text-align:center">-- Belum Ada Rincian Manfaat --</td>';
            echo '</tr>';
          }
										
        }						
        ?>									             																
      </tbody>
			
      <tr>
      <th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
      </tr>
							
      <tr>
        <td style="text-align:right;" colspan="7"><i><b>Total</b></i>
          <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
          <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
          <input type="text" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
				</td>								
        <td style="text-align:right"><?=number_format((float)$ln_tot_beasiswa,2,".",",");?></td>	
				<td colspan="2"></td>																											
      </tr>	
			
      <tr>
      	<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.50), rgba(0,0,0,0));"/></th>	
      </tr>
																	
    </table>
            			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

