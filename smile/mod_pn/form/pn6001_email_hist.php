<?php
$pagetype = "form";
$gs_pagetitle = "Histori Faskes";
require_once "../../includes/header_app_nosql.php";    
$mid = $_REQUEST["mid"]; 
$php_file_name="pn6001_email_hist";
$ls_kode_agenda       = isset($_REQUEST['kode_agenda'])?$_REQUEST['kode_agenda']:'';

$sql = "SELECT      B.KODE_FASKES,
                    A.KODE_TIPE,
                    (SELECT NAMA_TIPE FROM TC.TC_KODE_TIPE 
                        WHERE KODE_TIPE = A.KODE_TIPE
                    ) NAMA_TIPE,
                    A.KODE_KANTOR,
                    (SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR
                        WHERE KODE_KANTOR = A.KODE_KANTOR
                    ) NAMA_KANTOR,
                    A.KODE_PEMBINA,
                    A.NAMA_FASKES,
                    A.ALAMAT,
                    A.RT,
                    A.RW,
                    A.KODE_KELURAHAN,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN
                    )NAMA_KELURAHAN,
                    A.KODE_KECAMATAN,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN
                    )NAMA_KECAMATAN,
                    A.KODE_KABUPATEN,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN
                    )NAMA_KABUPATEN,
                    A.KODE_POS,
                    A.NAMA_PIC,
                    A.HANDPHONE_PIC,
                    A.TGL_NONAKTIF,
                    A.TGL_AKTIF,
                    A.KODE_STATUS,
                    (SELECT NAMA_STATUS FROM TC.TC_KODE_STATUS
                        WHERE KODE_STATUS = A.KODE_STATUS
                    ) NAMA_STATUS,
                    A.KODE_JENIS,
                    (SELECT NAMA_JENIS FROM TC.TC_KODE_JENIS
                        WHERE KODE_JENIS = A.KODE_JENIS
                    ) NAMA_JENIS,
                    A.KODE_JENIS_DETIL,
                    (SELECT NAMA_JENIS_DETIL FROM TC.TC_KODE_JENIS_DETIL
                        WHERE KODE_JENIS_DETIL = A.KODE_JENIS_DETIL
                    ) NAMA_JENIS_DETIL,
                    A.NO_IJIN_PRAKTEK,
                    A.NPWP,
                    A.MAX_TERTANGGUNG,
                    A.FLAG_UMUM,
                    A.FLAG_GIGI,
                    A.FLAG_SALIN,
                    A.FLAG_REG_WEBSITE,
                    A.KETERANGAN,
                    A.NAMA_PEMILIK,
                    A.ALAMAT_PEMILIK,
                    A.RT_PEMILIK,
                    A.RW_PEMILIK,
                    A.KODE_KELURAHAN_PEMILIK,
                    (SELECT NAMA_KELURAHAN FROM SIJSTK.MS_KELURAHAN
                        WHERE KODE_KELURAHAN = A.KODE_KELURAHAN_PEMILIK
                    )NAMA_KELURAHAN_PEMILIK,
                    A.KODE_KECAMATAN_PEMILIK,
                    (SELECT NAMA_KECAMATAN FROM SIJSTK.MS_KECAMATAN
                        WHERE KODE_KECAMATAN = A.KODE_KECAMATAN_PEMILIK
                    )NAMA_KECAMATAN_PEMILIK,
                    A.KODE_KABUPATEN_PEMILIK,
                    (SELECT NAMA_KABUPATEN FROM SIJSTK.MS_KABUPATEN
                        WHERE KODE_KABUPATEN = A.KODE_KABUPATEN_PEMILIK
                    )NAMA_KABUPATEN_PEMILIK,
                    A.KODE_POS_PEMILIK,
                    A.TELEPON_AREA_PEMILIK,
                    A.TELEPON_PEMILIK,
                    A.TELEPON_EXT_PEMILIK,
                    A.FAX_AREA_PEMILIK,
                    A.FAX_PEMILIK,
                    A.EMAIL_PEMILIK,
                    A.KODE_KEPEMILIKAN,
                     (SELECT NAMA_KEPEMILIKAN FROM TC.TC_KODE_KEPEMILIKAN
                        WHERE KODE_KEPEMILIKAN = A.KODE_KEPEMILIKAN
                    ) NAMA_KEPEMILIKAN,
                    A.KODE_METODE_PEMBAYARAN,
                    (SELECT NAMA_METODE_PEMBAYARAN FROM TC.TC_KODE_METODE_PEMBAYARAN
                        WHERE KODE_METODE_PEMBAYARAN = A.KODE_METODE_PEMBAYARAN
                    ) NAMA_METODE_PEMBAYARAN,
                    A.KODE_BANK_PEMBAYARAN,
                    (SELECT NAMA_BANK FROM SIJSTK.MS_BANK
                        WHERE KODE_BANK = A.KODE_BANK_PEMBAYARAN
                    ) NAMA_BANK,
                    A.NAMA_REKENING_PEMBAYARAN,
                    A.NO_REKENING_PEMBAYARAN,
                    A.TELEPON_AREA_PIC,
                    A.TELEPON_PIC,
                    A.TELEPON_EXT_PIC,
                    A.NO_FASKES,
                    A.KELAS_PERAWATAN,
                    A.BAGIAN_PIC,
                    A.TGL_SUBMIT,
                    A.PETUGAS_SUBMIT,
                    A.TGL_REKAM,
                    A.PETUGAS_REKAM,
                    A.TGL_UBAH,
                    B.EMAIL_FASKES,
                    B.HANDPHONE,
                    B.STATUS_AKTIF
            FROM TC.TC_FASKES A, TC.TC_REG_FASKES_HIST B
            WHERE B.KODE_AGENDA = '$ls_kode_agenda'
            AND A.KODE_FASKES = B.KODE_FASKES";
//echo $sql;
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
    <h3>PN6001-FASKES HIST - INFORMASI FASILITAS KESEHATAN/BLK</h3>
</div>
<div style="margin-top: 50px;"></div>
<br>
<fieldset>
<legend>Informasi Account Email</legend>
<table width="99%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff"  >
    <tr>
        <td width="40%" valign="top">
            <div class="l_frm">Email :</div><div class="r_frm"><?=$row_info['EMAIL_FASKES'];?></div>
        </td>
        <td width="30%" valign="top">
            <div class="l_frm">No. Handphone :</div><div class="r_frm"><?=$row_info['HANDPHONE'];?></div>
        </td>
        <td width="30%" valign="top">
            <div class="l_frm">Status Aktif :</div><div class="r_frm"><?=$row_info['STATUS_AKTIF'];?></div>
        </td>
    </tr>
</table>                       
</fieldset>             <br />
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
</fieldset>
</div>
<?php
include "../../includes/footer_app_nosql.php";
?>
