<?php
$pagetype = "form";
$gs_pagetitle = "Profil Ketenagakerjaan";
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
require_once "../../includes/fungsi.php";
include "../../includes/class_database.php"; 
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$gs_style = "style.new.css?ver=1.2";

$mid = $_REQUEST["mid"]; 
$php_file_name="pn2405_preview";
$schema="sijstk";


$ls_kd_kantor=isset($_REQUEST['kd_kantor'])?strtoupper($_REQUEST['kd_kantor']):$_SESSION['kdkantorrole'];

$arr_kantor=array();

if($ls_kd_kantor==="0" && $ls_kd_kantor!=="000")
{
    $sql="select kode_kantor, nama_kantor,kode_tipe,lpad(kode_kantor,5,'0') a from ms.ms_kantor where kode_tipe=1 order by a";
    $DB->parse($sql);
    if($DB->execute())
        while($rk = $DB->nextrow())
            $arr_kantor[]=array("KODE_KANTOR"=>$rk['KODE_KANTOR'],"NAMA_KANTOR"=>$rk['NAMA_KANTOR'],"KODE_TIPE"=>$rk['KODE_TIPE'])   ;
}
else
{
    $sql="select kode_kantor, nama_kantor,kode_tipe from ms.ms_kantor where kode_kantor='{$ls_kd_kantor}'";
    $DB->parse($sql);
    if($DB->execute())
        while($rk = $DB->nextrow())
            $arr_kantor[]=array("KODE_KANTOR"=>$rk['KODE_KANTOR'],"NAMA_KANTOR"=>$rk['NAMA_KANTOR'],"KODE_TIPE"=>$rk['KODE_TIPE'])   ;
}



?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <link rel="stylesheet" type="text/css" href="../../style/style.new.css?ver=1.2" />
  <style type="text/css">
    .gw{margin:auto;width:900px;}
    .gw_title{clear:both;text-align:center;font-weight: bold;font-size:1.6em;margin-bottom:5px;padding:4px;text-transform: uppercase;}
    .gw_subtitle{clear:both;text-align:left;margin:0;padding:6px;color:#ffffff;font-weight: bold;font-size:1.3em;background-color: green;text-transform: uppercase;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    .gw_content{font-size:1em;margin:0;padding:4px;color:#585858;position:relative;text-align:left;}
    .gw_foto{position: absolute; padding: 2px;right:4px; top: 10px; border:1px solid #C0C0FF; width:100px;height:120px;}
    .gw_foto img {
        width: 100%;
        height: 100%;
    }
    .nbar{float:left;clear:left;margin-bottom:2px;text-align:left;}
    .bar{float:left;margin-bottom:2px;text-align:left;}

    .gw_tbl{border-collapse: collapse;}
    .gw_tbl table, .gw_tbl th, .gw_tbl td {border: 1px solid #C0C0C0;}
    .gw_tbl th {background-color:#F0F0F0;}


    .gw_tbl_noborder{border-collapse: collapse;}
    .gw_tbl_noborder th {background-color:#F0F0F0;}

    .f_0 input:readonly,textarea:readonly, select:readonly  {
            color: #3e3724;
            background: #F5F5F5;
            border: 1px solid #aaaaaa;
            -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
            box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
            padding:2px;
            font-size:10px;
            font-family: verdana, arial, tahoma, sans-serif;		
    }     
    .f_1{width:150px;text-align:right;float:left;clear:left;margin-bottom:2px;}
    .f_2{width:300px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }   

    </style>

</head>
<body>
<?php
foreach ($arr_kantor as $key) {
        $title = $key['KODE_TIPE']=='1'?"{$key['KODE_KANTOR']} - {$key['NAMA_KANTOR']}":"";
?>
<div class="gw">
    <div class="gw_title">DAFTAR FASILITAS KESEHATAN/ BALAI LATIHAN KERJA<br /><?=$title;?></div>
    <?php
        $search='';
        $kdkantor='----';
        $nrow=1;
        if($key['KODE_TIPE']=='1')
            $search=" and kode_kantor in
            (
                select kode_kantor from ms.ms_kantor 
                START WITH kode_kantor='{$key['KODE_KANTOR']}'
                CONNECT BY PRIOR kode_kantor = kode_kantor_induk
            )";
        else 
            $search=" and kode_kantor = '{$key['KODE_KANTOR']}'";
        $sql="select a.kode_faskes,a.nama_faskes,b.nama_tipe,a.alamat,c.nama_kecamatan,d.nama_kabupaten,a.handphone_pic,e.nama_jenis,
                    kode_kantor,(select nama_kantor from ms.ms_kantor where kode_kantor=a.kode_kantor) nama_kantor,
                    lpad(kode_kantor,5,'0') order_kantor
                from tc.tc_faskes a
                    left outer join tc.tc_kode_tipe b on a.kode_tipe=b.kode_tipe
                    left outer join ms.ms_kecamatan c on a.kode_kecamatan=c.kode_kecamatan  
                    left outer join ms.ms_kabupaten d on a.kode_kabupaten=d.kode_kabupaten
                    left outer join tc.tc_kode_jenis e on a.kode_jenis=e.kode_jenis
                where a.kode_status='ST3' {$search}
                order by order_kantor,a.nama_faskes ";
        $DB->parse($sql);
        if($DB->execute())
        {
            while($row = $DB->nextrow())
            {
                $nrow++;
                if($kdkantor!=$row['ORDER_KANTOR'])
                {
                    if($kdkantor!='----') echo "</table></div>";
                    $kdkantor=$row['ORDER_KANTOR'];
                    $nrow=1;
    ?>
    <div class="gw_subtitle"><?=$row['KODE_KANTOR'];?> - <?=$row['NAMA_KANTOR'];?></div>
    <div class="gw_content">
        <table class="gw_tbl" width="100%">
            <tr>
                <th>No.</th>
                <th>Nama Faskes</th>
                <th>Tipe Faskes</th>
                <th>Jenis Faskes</th>
                <th>Phone</th>
                <th>Alamat</th>
                <th>Kecamatan</th>
                <th>Kabupaten</th>
            </tr>
    <?php       }?>

            <tr>
                <td><?=$nrow;?></td>
                <td><?=$row['NAMA_FASKES'];?></td>
                <td><?=$row['NAMA_TIPE'];?></td>
                <td><?=$row['NAMA_JENIS'];?></td>
                <td><?=$row['HANDPHONE_PIC'];?></td>
                <td><?=$row['ALAMAT'];?></td>
                <td><?=$row['NAMA_KECAMATAN'];?></td>
                <td><?=$row['NAMA_KABUPATEN'];?></td>
            </tr>
    <?php   }
        }
    ?>
        </table>
    </div>
</div>
<?php }?>
</body>
</html>