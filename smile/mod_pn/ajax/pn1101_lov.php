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
else if($action=='getTipeKlaim')
{  
    $sql = "select KODE_TIPE_KLAIM,NAMA_TIPE_KLAIM from {$schema}.PN_KODE_TIPE_KLAIM where status_nonaktif='T' or KODE_TIPE_KLAIM='{$key1}'  order by KODE_TIPE_KLAIM";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_TIPE_KLAIM"]==$key1?" selected ":"") . " value=\"{$row["KODE_TIPE_KLAIM"]}\">{$row["NAMA_TIPE_KLAIM"]}</option>";
}
else if($action=='getGroupICD')
{  
    $sql = "select KODE_GROUP_ICD,NAMA_GROUP_ICD from {$schema}.PN_KODE_GROUP_ICD where status_nonaktif='T' or KODE_GROUP_ICD='{$key1}'  order by KODE_GROUP_ICD";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_GROUP_ICD"]==$key1?" selected ":"") . " value=\"{$row["KODE_GROUP_ICD"]}\">{$row["NAMA_GROUP_ICD"]}</option>";
}
else if($action=='getDiagnosa')
{  
    $s1='';
    if($key2!='') $s1= " and KODE_GROUP_ICD='{$key2}'";
    $sql = "select KODE_DIAGNOSA,NAMA_DIAGNOSA from {$schema}.PN_KODE_DIAGNOSA where (status_nonaktif='T' or KODE_DIAGNOSA='{$key1}') {$s1}  order by KODE_DIAGNOSA";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_DIAGNOSA"]==$key1?" selected ":"") . " value=\"{$row["KODE_DIAGNOSA"]}\">{$row["NAMA_DIAGNOSA"]}</option>";
}
else if($action=='getSebabKlaim')
{  
     $sql = "select KODE_SEBAB_KLAIM,NAMA_SEBAB_KLAIM from {$schema}.PN_KODE_SEBAB_KLAIM where (status_nonaktif='T' or KODE_SEBAB_KLAIM='{$key1}')  order by KODE_SEBAB_KLAIM";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_SEBAB_KLAIM"]==$key1?" selected ":"") . " value=\"{$row["KODE_SEBAB_KLAIM"]}\">{$row["NAMA_SEBAB_KLAIM"]}</option>";
}
else if($action=='getSegmen')
{  
    $sql = "select KODE_SEGMEN,NAMA_SEGMEN from {$schema}.KN_KODE_SEGMEN where (status_nonaktif='T' or KODE_SEGMEN='{$key1}')  order by KODE_SEGMEN";
    $DB->parse($sql); 
    $DB->execute();//echo $sql;
    while($row = $DB->nextrow())
        echo "<option  ". ($row["KODE_SEGMEN"]==$key1?" selected ":"") . " value=\"{$row["KODE_SEGMEN"]}\">{$row["NAMA_SEGMEN"]}</option>";
}
?>