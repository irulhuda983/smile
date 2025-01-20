<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$action= $_REQUEST['formregact'];
$key1= isset($_POST['key1'])?$_POST['key1']:'';
$key2= isset($_POST['key2'])?$_POST['key2']:'T';

$schema="sijstk";             //print_r($_REQUEST);
/*if($action=='ApproveUnNonAktif')
{ 
    
    $sql = "update {$schema}.TC_FASKES set KODE_STATUS=case when '{$key2}'='Y' then 'ST2' else KODE_STATUS end,STATUS_APPROVE_REQUEST='{$key2}',
    TGL_APPROVE_REQUEST=sysdate, PETUGAS_APPROVE_REQUEST='{$_SESSION['USER']}',STATUS_REQUEST=''
    where KODE_FASKES='{$key1}'";
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data,cek kode tipe sudah ada atau ulangi lagi proses save!";
    }
}     
else */
if($action=='ApproveNonAktif')
{ 
    
    $sql = "update {$schema}.TC_FASKES set KODE_STATUS=case when nvl(STATUS_APPROVE_REQUEST,'x')='Y' and '{$key2}'='Y' then 'ST6' else KODE_STATUS end,
        STATUS_APPROVE_REQUEST= case when nvl(STATUS_APPROVE_REQUEST,'x')<>'Y' then '{$key2}' else STATUS_APPROVE_REQUEST end,
        STATUS_APPROVE_REQUEST1= case when nvl(STATUS_APPROVE_REQUEST,'x')='Y' then '{$key2}' else STATUS_APPROVE_REQUEST1 end,
    TGL_APPROVE_REQUEST=sysdate, PETUGAS_APPROVE_REQUEST='{$_SESSION['USER']}',
    STATUS_REQUEST=case when nvl(STATUS_APPROVE_REQUEST,'x')='Y'  then '' else STATUS_REQUEST end
    where KODE_FASKES='{$key1}'";
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){    
        echo "Gagal penyimpanan data,cek kode tipe sudah ada atau ulangi lagi proses save!";
    }
}
else{
    echo 'Tidak ada action dilakukan!';
}
?>