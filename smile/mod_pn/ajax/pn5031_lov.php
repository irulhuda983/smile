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

if($action=='getDiagnosa')
{  
    $sql = "select KODE_DIAGNOSA,NAMA_DIAGNOSA from {$schema}.pn_kode_diagnosa where KODE_GROUP_ICD='{$key1' order bya KODE_DIAGNOSA";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    echo "<option value=\"\"></option>";
    while($row = $DB->nextrow())
        echo "<option  value=\"{$row["KODE_DIAGNOSA"]}\">{$row["NAMA_DIAGNOSA"]}</option>";

}elseif($action=='getDiagnosaDetil')
{  
    $sql = "select KODE_DIAGNOSA_DETIL,NAMA_DIAGNOSA_DETIL from {$schema}.pn_kode_diagnosa_detil where KODE_DIAGNOSA='{$key1' order bya KODE_DIAGNOSA_DETIL";
        $DB->parse($sql);
        $DB->execute();//echo $sql;
        echo "<option value=\"\"></option>";
        while($row = $DB->nextrow())
            echo "<option  value=\"{$row["KODE_DIAGNOSA_DETIL"]}\">{$row["NAMA_DIAGNOSA_DETIL"]}</option>";
}
?>