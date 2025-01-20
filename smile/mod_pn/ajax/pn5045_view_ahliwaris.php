<?
$pagetype="report";
$gs_pagetitle = "PN5006 - DATA AHLI WARIS";
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
$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_sender_activetab = !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];

$gs_pagetitle = $gs_pagetitle." - KODE KLAIM ".$ls_kode_klaim;
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
  <div id="formKiri" style="width:1000px;">
  	<fieldset><legend>Daftar Ahli Waris</legend>
      <table id="mydata1" width="100%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
        <tbody>
          <tr>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="4">&nbsp;</th>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="2">Kondisi Akhir</th>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
          </tr>
          <tr>
          	<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Hubungan</th>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:250px;">Nama Lengkap</th>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Lahir</th>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Jns Kelamin</th>
  					<th colspan="2"><hr></hr></th>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:50px;">Eligible</th>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:150px;">Action</th>
          </tr>																										
          <tr>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;" colspan="4">&nbsp;</th>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">Status</th>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Sejak</th>
  					<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">&nbsp;</th>
            <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:150px;">&nbsp;</th>
          </tr>
          <tr>
          <th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
          </tr>													
          <?		
          $sql = "select 
                      a.kode_klaim, a.kode_penerima_berkala,a.kode_hubungan, 
                      (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan = a.kode_hubungan) nama_hubungan,
                      a.no_urut_keluarga, a.nama_lengkap, a.no_kartu_keluarga, a.nomor_identitas, 
                      a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin, 
                      decode(a.jenis_kelamin,'P','PEREMPUAN','L','LAKI-LAKI',a.jenis_kelamin) nama_jenis_kelamin,
                      a.golongan_darah, a.status_kawin, a.alamat, 
                      a.rt, a.rw, a.kode_kelurahan, 
                      a.kode_kecamatan, a.kode_kabupaten, a.kode_pos, 
                      a.telepon_area, a.telepon, a.telepon_ext, 
                      a.fax_area, a.fax, a.handphone, 
                      a.email, a.npwp, a.nama_penerima, 
                      a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, 
                      a.kode_bank_pembayar, a.kpj_tertanggung, a.pekerjaan, 
                      a.kode_kondisi_terakhir, 
                      (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir,
                      to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, 
                      nvl(a.status_layak,'T') status_layak, a.keterangan
                  from sijstk.pn_klaim_penerima_berkala a
                  where a.kode_klaim = '$ls_kode_klaim'
  								and a.kode_hubungan <> 'T'
                  order by a.no_urut_keluarga ";
          //echo $sql;
          $DB->parse($sql);
          $DB->execute();
          $i=0;
          $ln_dtl = 0;
          while ($row = $DB->nextrow())
          {
          ?>
          <?echo "<tr bgcolor=#".($i%2 ? "ffffff" : "f3f3f3").">";?>								
            <td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_HUBUNGAN"];?></td>	
            <td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_LENGKAP"];?></td>
            <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_LAHIR"];?></td>
            <td style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_JENIS_KELAMIN"];?></td>
            <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_KONDISI_TERAKHIR"];?></td>
  					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_KONDISI_TERAKHIR"];?></td>
  					<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;">
  							<?=($row["STATUS_LAYAK"]=="Y" ? "<img src=../../images/file_apply.gif>" : "")?>
  					</td>
            <td style="text-align:center;">
              <a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_ahliwarisentry.php?task=view&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&no_urut_keluarga=<?=$row["NO_URUT_KELUARGA"];?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=2&sender_mid=<?=$mid;?>','Ubah Data Keluarga',880,620,'no');"><img src="../../images/app_form_edit.png" border="0" alt="Ubah Divisi" align="absmiddle" />&nbsp;View</a>
            </td>
          </tr>
          <?
          //hitung total									    							
          $i++;//iterasi i							
          }	//end while
          $ln_dtl=$i;				
          ?>									             																
        </tbody>
        <tr>
        	<td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>	
        </tr>											
        <tr>
          <td colspan="2"style="text-align:left;" >
  					<!--<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_agenda_jpn_ahliwarisentry.php?task=new&kode_tk=<?=$ls_kode_tk;?>&kode_klaim=<?=$ls_kode_klaim;?>&root_sender=pn5040.php&sender=pn5040.php&sender_activetab=2&sender_mid=<?=$mid;?>','Entry Data Ahli Waris',880,620,'no');"><img src="../../images/plus.png" border="0" alt="Tambah" align="absmiddle" />&nbsp;<b>Tambah Ahli Waris - Anak Usia dibawah 300 Hari</b></a>-->
  				</td>
  				<td style="text-align:left" colspan="5"><b><i>&nbsp;<i></b>
            <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
            <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
            <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">					
          </td>
          <td style="text-align:center">	
          </td>											
        </tr>
      </table>					
  	</fieldset>	 
  </div> 
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

