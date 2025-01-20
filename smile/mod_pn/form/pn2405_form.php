<?php
$pagetype = "form";
$gs_pagetitle = "Cetak Dokumen BPU";
require_once "../../includes/header_app_nosql.php";    
$mid = $_REQUEST["mid"]; 
$php_file_name="kn50405";
$ls_kode       = isset($_REQUEST['kd'])?$_REQUEST['kd']:'';
$sql="select a.*,
(select nama_tipe from tc.tc_kode_tipe where kode_tipe=a.kode_tipe) nama_tipe,
(select nama_kantor from ms.ms_kantor where kode_kantor=a.kode_kantor) nama_kantor,
(select nama_jenis from tc.tc_kode_jenis where kode_jenis=a.kode_jenis) nama_jenis,
(select nama_jenis_detil from tc.tc_kode_jenis_detil where kode_jenis_detil=a.kode_jenis_detil) nama_jenis_detil,
(select nama_kepemilikan from tc.tc_kode_kepemilikan where kode_kepemilikan=a.kode_kepemilikan) nama_kepemilikan,
 (select nama_kelurahan from ms.ms_kelurahan where kode_kelurahan=a.kode_kelurahan) nama_kelurahan,
 (select nama_kecamatan from ms.ms_kecamatan where kode_kecamatan=a.kode_kecamatan) nama_kecamatan,
 (select nama_kabupaten from ms.ms_kabupaten where kode_kabupaten=a.kode_kabupaten) nama_kabupaten,
 (select nama_kelurahan from ms.ms_kelurahan where kode_kelurahan=a.kode_kelurahan_pemilik) nama_kelurahan_pemilik,
 (select nama_kecamatan from ms.ms_kecamatan where kode_kecamatan=a.kode_kecamatan_pemilik) nama_kecamatan_pemilik,
 (select nama_kabupaten from ms.ms_kabupaten where kode_kabupaten=a.kode_kabupaten_pemilik) nama_kabupaten_pemilik,
 LATITUDE,LONGITUDE,
 (SELECT KODE_KANTOR FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES) || ' - ' || (SELECT NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = (SELECT KODE_KANTOR FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES)) AS KODE_NAMA_KACAB,
 KODE_PIC_CABANG,
 KODE_PIC_CABANG || ' - ' || (SELECT NAMA_USER FROM MS.SC_USER A WHERE KODE_PIC_CABANG = A.KODE_USER) AS KODE_USER_NAMA,
 KONTAK_PIC_CABANG
from tc.tc_faskes a
where kode_faskes='{$ls_kode}'";
if($DB->parse($sql))
    if($DB->execute())
        $row_info = $DB->nextrow();
            
?>
<style>
.gw_center{text-align:center}
/* Gw - 03/11/2017 */
.btn_g  {border: 1px solid #5492D6;color:#FFFFFF !important;/*cursor: pointer;*/padding: 2px 5px;font-size: 10px;font-family: verdana, arial, tahoma, sans-serif;
    border-radius: 2px;background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);background: -o-linear-gradient(#5DBBF6, #2788E0); 
    background: -moz-linear-gradient(#5DBBF6, #2788E0);background: linear-gradient(#5DBBF6, #2788E0);margin-left:3px;}
  .btn_g:hover{background:#C0FFFF;color:#000000 !important;}
  .gw_tr {cursor:pointer;}
  .gw_tr:hover {background-color:#CADDE6;}
  .gw_btn {
      cursor:pointer;
    background: #3b8fc7;
    background-image: -webkit-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: -moz-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: -ms-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: -o-linear-gradient(top, #3b8fc7, #2980b9);
    background-image: linear-gradient(to bottom, #3b8fc7, #2980b9);
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    font-family: Arial;
    color: #ffffff;
    font-size: 12px;
    padding: 2px 4px 2px 4px;
    text-decoration: none;
  }
  
  .gw_btn:hover {
    background: #3cb0fd;
    background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
    background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
    text-decoration: none;
  }
  .gw_btn_noaktif {
      background: #abbcc7;
      background-image: -webkit-linear-gradient(top, #abbcc7, #6f8896);
      background-image: -moz-linear-gradient(top, #abbcc7, #6f8896);
      background-image: -ms-linear-gradient(top, #abbcc7, #6f8896);
      background-image: -o-linear-gradient(top, #abbcc7, #6f8896);
      background-image: linear-gradient(to bottom, #abbcc7, #6f8896);
      -webkit-border-radius: 5px;
      -moz-border-radius: 5px;
      border-radius: 5px;
      font-family: Arial;
      color: #ffffff;
      font-size: 12px;
      padding: 2px 4px 2px 4px;
      text-decoration: none;
  }
  .gw_tab_title {
    background: #6093CA;
    -webkit-border-top-right-radius: 10px;
    -moz-border-radius-top-right: 10px;
    border-top-right-radius: 10px;
    -webkit-border-top-left-radius: 5px;
    -moz-border-radius-top-left: 5px;
    border-top-left-radius: 5px;
    margin-bottom:0px;
    padding:5px 15px;
    font-size:12px;
    font-weight:bold;
    color:#ffffff;
    cursor:pointer;  
    color:#ffffff;  
  }
  .gw_tab_title_active {
    background: #D2E0F0;
    -webkit-border-top-right-radius: 10px;
    -moz-border-radius-top-right: 10px;
    border-top-right-radius: 10px;
    -webkit-border-top-left-radius: 5px;
    -moz-border-radius-top-left: 5px;
    border-top-left-radius: 5px;
    margin-bottom:0px;
    padding:5px 15px;
    font-size:12px;
    font-weight:bold;
    color:#6093CA;
  }
  .gw_header{
  background: #3cb0fd;
    background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
    background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
    background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
    background-image: url("../../images/application_view_columns.png") ;
    background-repeat: no-repeat;
    text-decoration: none;
    width: 100%;
    margin: 0px;
    padding: 7px;
  }
  .f_0{}
.f_0 form fieldset legend {
    font-size: 100%;
    font-weight: bold;
    color : #157fcc;
    font-family: verdana, arial, tahoma, sans-serif;         
  }
.gw_tbl{border-collapse: collapse;}
.gw_tbl table, .gw_tbl th, .gw_tbl td {border: 1px solid #C0C0C0;}
.gw_tbl th{background-color: #F0F0F0; } 

.gw_tr1{border-collapse: collapse;border-bottom:1px solid #E0E0E0;}
.gw_tr1 td {border-bottom:1px solid #E0E0E0;}
.gw_tr1:hover{background-color:#DDDDFF}  
.gw_lbl {display: block;width: 130px;}
.l_frm{width: 135px; clear: left; float: left;margin-bottom: 2px;text-align: right; margin-right: 2px;}
.r_frm{float: left;margin-bottom: 2px;}
</style>
<iframe name="iframe_cetak_individu" id="iframe_cetak_individu"  src="#" style="postion:fixed;left:-9999;top:-9999;display:none"></iframe>
<div id="header-popup"> 
    <h3>PN2405 - INFORMASI FASILITAS KESEHATAN/BLK</h3>
</div>
<div style="margin-top: 50px;"></div>
<fieldset>
<legend>Informasi Fasilitas</legend>
<table width="99%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff"  >
    <tr>
        <td width="60%" valign="top">
            <div class="l_frm">Nama Faskes/BLK :</div><div class="r_frm"><?=$row_info['NAMA_FASKES'];?></div>
            <div class="l_frm">Nomor Faskes/BLK :</div><div class="r_frm"><?=$row_info['NO_FASKES'];?></div>
            <div class="l_frm">Tipe Faskes/BLK :</div><div class="r_frm"><?=$row_info['NAMA_TIPE'];?></div>
            <div class="l_frm">Kantor :</div><div class="r_frm"><?=$row_info['KODE_KANTOR'];?> - <?=$row_info['NAMA_KANTOR'];?></div>
            <div class="l_frm">Alamat Lengkap :</div><div class="r_frm"><?=$row_info['ALAMAT'];?></div>
            <div class="l_frm">RT/RW :</div><div class="r_frm"><?=$row_info['RT'];?>/<?=$row_info['RW'];?></div>
            <div class="l_frm">Kode Pos :</div><div class="r_frm"><?=$row_info['KODE_POS'];?></div>
            <div class="l_frm">Kelurahan :</div><div class="r_frm"><?=$row_info['NAMA_KELURAHAN'];?></div>
            <div class="l_frm">Kecamatan :</div><div class="r_frm"><?=$row_info['NAMA_KECAMATAN'];?></div>
            <div class="l_frm">Kabupaten :</div><div class="r_frm"><?=$row_info['NAMA_KABUPATEN'];?></div>
            <div class="l_frm">Latitude :</div><div class="r_frm"><?=$row_info['LATITUDE'];?></div>
            <div class="l_frm">Longitude :</div><div class="r_frm"><?=$row_info['LONGITUDE'];?></div>
        </td>
        <td width="40%" valign="top">
            <div class="l_frm">Nomor Telepon :</div><div class="r_frm"><?=$row_info['TELEPON_AREA_PIC'];?> <?=$row_info['TELEPON_PIC'];?></div>
            <div class="l_frm">Extension :</div><div class="r_frm"><?=$row_info['TELEPON_EXT_PIC'];?></div>
            <div class="l_frm">Nama PIC :</div><div class="r_frm"><?=$row_info['NAMA_PIC'];?></div>
            <div class="l_frm">Bagian PIC :</div><div class="r_frm"><?=$row_info['BAGIAN_PIC'];?></div>
            <div class="l_frm">Handphone PIC :</div><div class="r_frm"><?=$row_info['HANDPHONE_PIC'];?></div>
            <div class="l_frm">No. Ijin Praktek :</div><div class="r_frm"><?=$row_info['NO_IJIN_PRAKTEK'];?></div>
            <div class="l_frm">NPWP :</div><div class="r_frm"><?=$row_info['NPWP'];?></div>
            <div class="l_frm">Jenis Fasilitas :</div><div class="r_frm"><?=$row_info['NAMA_JENIS'];?></div>
            <div class="l_frm">Sub Jenis Fasilitas :</div><div class="r_frm"><?=$row_info['NAMA_JENIS_DETIL'];?></div>
            <div class="l_frm">Kelas Perawatan :</div><div class="r_frm"><?php echo ($row_info['KODE_TIPE']!='F"')?"":$row_info['KELAS_PERAWATAN'];?></div>
            <div class="l_frm">Keterangan :</div><div class="r_frm"><?=$row_info['KETERANGAN'];?></div>
        </td>
    </tr>
</table>                       
</fieldset> <br />
<fieldset><legend><b>PIC Kantor Cabang</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
            <!-- <div class="l_frm">Kantor Cabang:</div><div class="r_frm"><?=$row_info['KODE_NAMA_KACAB'];?></div> -->
            <div class="l_frm">Nama PIC Kantor Cabang:</div><div class="r_frm"><?=$row_info['KODE_USER_NAMA'];?></div>
            <div class="l_frm">Handphone PIC Cabang:</div><div class="r_frm"><?=$row_info['KONTAK_PIC_CABANG'];?></div>
            </td>
        </tr>
    </table>
</fieldset><br />
<fieldset>
<legend>Informasi Pemilik</legend>
<table width="99%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff"  >
    <tr>
        <td width="60%" valign="top">
            <div class="l_frm">Nama :</div><div class="r_frm"><?=$row_info['NAMA_PEMILIK'];?></div>
            <div class="l_frm">Alamat Lengkap :</div><div class="r_frm"><?=$row_info['ALAMAT_PEMILIK'];?></div>
            <div class="l_frm">RT/RW :</div><div class="r_frm"><?=$row_info['RT_PEMILIK'];?>/<?=$row_info['RW_PEMILIK'];?></div>
            <div class="l_frm">Kode Pos :</div><div class="r_frm"><?=$row_info['KODE_POS_PEMILIK'];?></div>
            <div class="l_frm">Kelurahan :</div><div class="r_frm"><?=$row_info['NAMA_KELURAHAN_PEMILIK'];?></div>
            <div class="l_frm">Kecamatan :</div><div class="r_frm"><?=$row_info['NAMA_KECAMATAN_PEMILIK'];?></div>
            <div class="l_frm">Kabupaten :</div><div class="r_frm"><?=$row_info['NAMA_KABUPATEN_PEMILIK'];?></div>
        </td>
        <td width="40%" valign="top">
            <div class="l_frm">Nomor Telepon :</div><div class="r_frm"><?=$row_info['TELEPON_AREA_PEMILIK'];?> <?=$row_info['TELEPON_PEMILIK'];?></div>
            <div class="l_frm">Extension :</div><div class="r_frm"><?=$row_info['TELEPON_EXT_PEMILIK'];?></div>
            <div class="l_frm">Fax :</div><div class="r_frm"><?=$row_info['FAX_PEMILIK'];?></div>
            <div class="l_frm">Alamat Email :</div><div class="r_frm"><?=$row_info['EMAIL_PEMILIK'];?></div>
            <div class="l_frm">Tipe Pemilik :</div><div class="r_frm"><?=$row_info['NAMA_KEPEMILIKAN'];?></div>
        </td>
    </tr>
</table>                       
</fieldset>             <br />
<fieldset>
<legend>Informasi IKS</legend>
<table bgcolor="#000000" border="0" width="100%">
    <tr bgcolor="#F0F0F0">
        <th width="30" align="center">No.</th>
        <th>No IKS</th>
        <th>Tgl Awal</th>
        <th>Tgl Akhir</th>
        <th>Status Approval 1</th>
        <th>Status Approval 2</th>
    </tr>
<?php
$sql="select case WHEN STATUS_IKS1a = 'MENUNGGU APPROVAL' AND lamp_iks = 0 THEN 'BARU'
      ELSE status_iks1a end STATUS_IKS1 ,a.* from
        (select rownum NO_URUT ,A.KODE_FASKES,A.KODE_IKS,A.NO_IKS,TO_CHAR(A.TGL_AWAL_IKS) TGL_AWAL_IKS,to_char(A.TGL_AKHIR_IKS) TGL_AKHIR_IKS,
        case
            WHEN A.KODE_STATUS_IKS='1' then 'BARU'
            WHEN A.KODE_STATUS_IKS='2' then 'MENUNGGU APPROVAL'
            WHEN A.KODE_STATUS_IKS='3' then 'DISETUJUI'
            WHEN A.KODE_STATUS_IKS='4' then 'DITOLAK'
            WHEN A.KODE_STATUS_IKS='5' then 'NON-AKTIF'
            WHEN A.KODE_STATUS_IKS='6' then 'DIBATALKAN'
        end STATUS_IKS1a,
        case
            WHEN A.KODE_STATUS_IKS1='1' then 'BARU'
            WHEN A.KODE_STATUS_IKS1='2' then 'MENUNGGU APPROVAL'
            WHEN A.KODE_STATUS_IKS1='3' then 'DISETUJUI'
            WHEN A.KODE_STATUS_IKS1='4' then 'DITOLAK'
            WHEN A.KODE_STATUS_IKS1='5' then 'NON-AKTIF'
            WHEN A.KODE_STATUS_IKS1='6' then 'DIBATALKAN'
        end STATUS_IKS2,A.KODE_STATUS_IKS,
        A.STATUS_NA,nvl(A.ALASAN_NA,' ') ALASAN_NA,nvl(B.NO_IKS,' ') NO_ADDENDUM,
        case 
            when A.KODE_STATUS_IKS='3' and A.KODE_STATUS_IKS1='3' then 'Y' 
            else 'T'
        end CAN_ADENDUM,
        nvl((select count(1) from sijstk.tc_iks_lampiran where kode_faskes=a.kode_faskes and  kode_iks=a.kode_iks),0) lamp_iks
         from sijstk.TC_IKS A left outer join sijstk.TC_IKS B on (A.KODE_FASKES=B.KODE_FASKES and A.KODE_ADDENDUM=B.KODE_IKS)
         where a.kode_faskes='{$ls_kode}'
        ) a order by tgl_awal_iks";  $irow=0;// echo $sql;
if($DB->parse($sql))
    if($DB->execute())
        while($row_iks = $DB->nextrow())
        {
?>
    <tr bgcolor="#FFFFFF">
        <td align="center"><?=++$irow;?></td>
        <td><?=$row_iks['NO_IKS'];?></td>
        <td><?=$row_iks['TGL_AWAL_IKS'];?></td>
        <td><?=$row_iks['TGL_AKHIR_IKS'];?></td>
        <td><?=$row_iks['STATUS_IKS1'];?></td>
        <td><?=$row_iks['STATUS_IKS2'];?></td>
    </tr>
<?php } ?>
</table>
</fieldset>
<?php
include "../../includes/footer_app_nosql.php";
?>