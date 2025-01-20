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

if($action=='getMSLookup')
{  
    $sql = "select KODE,KETERANGAN from  {$schema}.ms_lookup where tipe='{$key1}' and (aktif='Y'  or kode='{$key2}') order by seq";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE"]==$key2?" selected ":"") . " value=\"{$row["KODE"]}\">{$row["KETERANGAN"]}</option>";

}
else if($action=='getSubKegiatan')
{  
    $sql = "select KODE_KEGIATAN,NAMA_KEGIATAN from {$schema}.pn_kode_promotif_kegiatan where status_nonaktif='T' or KODE_KEGIATAN='{$key1}'  order by KODE_KEGIATAN";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_KEGIATAN"]==$key1?" selected ":"") . " value=\"{$row["KODE_KEGIATAN"]}\">{$row["NAMA_KEGIATAN"]}</option>";
}

?>