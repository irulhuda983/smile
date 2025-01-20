<?php 
session_start();
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$action= isset($_GET['formregact'])?$_GET['formregact']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';
$key1= isset($_GET['key1'])?$_GET['key1']:'';

$action= isset($_POST['formregact'])?$_POST['formregact']:$action;
$key1= isset($_POST['key1'])?$_POST['key1']:$key1;
$key2= isset($_POST['key1'])?$_POST['key1']:$key2;
$schema="tc";
/*****get parameter***********/
if ($action=='Approve')
{
    $ls_kode_faskes     = strtoupper($_POST["kode_faskes"]); 
    $ls_kode_iks        = strtoupper($_POST["kode_iks"]);                                         
    $ls_act_approve     = (isset($_POST["act_approve"])) ? $_POST["act_approve"]:'4';
    $alasan_approval    = strtoupper($_POST["alasan_approval"]);
    
    $sql = "begin {$schema}.p_tc_faskes.X_IKS_APPROVAL('{$ls_kode_faskes}','{$ls_kode_iks}','{$ls_act_approve}','{$alasan_approval}','{$_SESSION["USER"]}',:P_OUT);end;";
    $proc = $DB->parse($sql); //echo $sql; die;
    oci_bind_by_name($proc,":P_OUT",$p_query,10);
    
    if(!$DB->execute()){   
        echo "Gagal Approval, ulangi lagi proses save!";
    }
}

?>