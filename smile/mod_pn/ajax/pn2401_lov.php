<?php
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$action= isset($_GET['f'])?$_GET['f']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';
$key2= isset($_GET['key2'])?$_GET['key2']:'';

$action= isset($_POST['f'])?$_POST['f']:$action;
$key1= isset($_POST['key1'])?$_POST['key1']:$key1;
$key2= isset($_POST['key2'])?$_POST['key2']:$key2;
$schema='sijstk';
//print_r($_REQUEST);
if($action=='getStatus')
{  
    $sql = "select KODE_STATUS,NAMA_STATUS from  {$schema}.TC_KODE_STATUS where STATUS_NONAKTIF='T' or KODE_STATUS='{$key1}' order by kode_status";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\">Semua Status</option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_STATUS"]==$key2?" selected ":"") . " value=\"{$row["KODE_STATUS"]}\">{$row["NAMA_STATUS"]}</option>";

}
else if($action=='getJenis')
{  
    $sql = "select KODE_JENIS,NAMA_JENIS from {$schema}.TC_KODE_JENIS where (status_nonaktif='T' and KODE_TIPE='{$key1}') or KODE_JENIS='{$key2}'   order by KODE_JENIS";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    echo "<option  value=\"\">--Pilih Jenis Faskes--</option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_JENIS"]==$key2?" selected ":"") . " value=\"{$row["KODE_JENIS"]}\">{$row["NAMA_JENIS"]}</option>";
}
if($action=='getJenisDetil')
{  
    $sql = "select KODE_JENIS_DETIL,NAMA_JENIS_DETIL from {$schema}.tc_kode_jenis_detil where (status_nonaktif='T' and KODE_JENIS='{$key1}') or KODE_JENIS_DETIL='{$key2}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\">--Pilih Jenis Detil Faskes--</option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_JENIS_DETIL"]==$key2?" selected ":"") . " value=\"{$row["KODE_JENIS_DETIL"]}\">{$row["NAMA_JENIS_DETIL"]}</option>";

}
?>