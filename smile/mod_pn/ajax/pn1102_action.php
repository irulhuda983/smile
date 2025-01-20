<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE	        = $_POST["TYPE"] . $_GET['TYPE'];
$USER         = $_SESSION["USER"];
$KODE_KANTOR  = $_SESSION['KDKANTOR']; 

$action= isset($_POST['formregact'])?$_POST['formregact']:'';
$action_no= isset($_POST['taskno'])?$_POST['taskno']:'';

$schema="sijstk";             
/*****get parameter***********/
if($action=='New' || $action=='Edit' || $action=='Delete')
{
    $ls_kode     = strtoupper($_POST["kode"]);
    $ls_nama     = strtoupper($_POST["nama"]);
    $ls_kode_parent = strtoupper($_POST["kode_parent"]);
    $ls_nourut     = strtoupper($_POST["nourut"]);
    $ls_bobot     = strtoupper($_POST["bobot"]);
    $ls_status_nonaktif         = strtoupper($_POST["status_nonaktif"]);
    $ls_status_nonaktif_old     = strtoupper($_POST["status_nonaktif_old"]);
}
if($action=='New')
{ 
    $s_tmp= array('APenAwal'=>"RTWTEMP01",'APenLink'=>"RTWTEMP02",'ApenTempat'=>"RTWTEMP03");
    if($action_no=='APenAwal' || $action_no=='APenLink' || $action_no=='ApenTempat'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_RTW_KODE_PENILAIAN_DETIL (KODE_TEMPLATE_PENILAIAN,KODE_ASSESMENT,NAMA_ASSESMENT,BOBOT,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,PETUGAS_NONAKTIF )
                values('{$s_tmp[$action_no]}','{$ls_kode}','{$ls_nama}','{$ls_bobot}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}','{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_RTW_KODE_PENILAIAN_DETIL (KODE_TEMPLATE_PENILAIAN,KODE_ASSESMENT,NAMA_ASSESMENT,BOBOT,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$s_tmp[$action_no]}','{$ls_kode}','{$ls_nama}','{$ls_bobot}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='TipeEvaluasi'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_RTW_KODE_EVALUASI (KODE_TIPE_EVALUASI,NAMA_TIPE_EVALUASI,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_RTW_KODE_EVALUASI (KODE_TIPE_EVALUASI,NAMA_TIPE_EVALUASI,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='KuisEvaluasi'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_RTW_KODE_EVALUASI_DETIL (KODE_KUSIONER,NAMA_KUSIONER,KODE_TIPE_EVALUASI,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_RTW_KODE_EVALUASI_DETIL (KODE_KUSIONER,NAMA_KUSIONER,KODE_TIPE_EVALUASI,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }
    $DB->parse($sql);// echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data,cek kode tipe sudah ada atau ulangi lagi proses save!";
    }
}else if($action=='Edit')
{  
    $s_tmp= array('APenAwal'=>"RTWTEMP01",'APenLink'=>"RTWTEMP02",'ApenTempat'=>"RTWTEMP03");
    if($action_no=='APenAwal' || $action_no=='APenLink' || $action_no=='ApenTempat'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_RTW_KODE_PENILAIAN_DETIL set NAMA_ASSESMENT='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',BOBOT='{$ls_bobot}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,PETUGAS_NONAKTIF='{$_SESSION["USER"]}'
                    where KODE_ASSESMENT='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_RTW_KODE_PENILAIAN_DETIL set NAMA_ASSESMENT='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',BOBOT='{$ls_bobot}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where KODE_ASSESMENT='{$ls_kode}'";
    }else if($action_no=='TipeEvaluasi'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_RTW_KODE_EVALUASI set NAMA_TIPE_EVALUASI='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where KODE_TIPE_EVALUASI='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_RTW_KODE_EVALUASI set NAMA_TIPE_EVALUASI='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where KODE_TIPE_EVALUASI='{$ls_kode}'";
    }else if($action_no=='KuisEvaluasi'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_RTW_KODE_EVALUASI_DETIL set NAMA_KUSIONER='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,KODE_TIPE_EVALUASI='{$ls_kode_parent}'
                    where KODE_KUSIONER='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_RTW_KODE_EVALUASI_DETIL set NAMA_KUSIONER='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,KODE_TIPE_EVALUASI='{$ls_kode_parent}'
                where KODE_KUSIONER='{$ls_kode}'";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}else if($action=='Delete')
{  
    if($action_no=='APenAwal' || $action_no=='APenLink' || $action_no=='ApenTempat'){
        $sql = "delete from {$schema}.PN_RTW_KODE_PENILAIAN_DETIL  where KODE_ASSESMENT='{$ls_kode}'";
    }else if($action_no=='TipeEvaluasi'){
        $sql = "delete from {$schema}.PN_RTW_KODE_EVALUASI  where KODE_TIPE_EVALUASI='{$ls_kode}'";
    }else if($action_no=='KuisEvaluasi'){
        $sql = "delete from {$schema}.PN_RTW_KODE_EVALUASI_DETIL  where KODE_KUSIONER='{$ls_kode}'";
    }
    $DB->parse($sql);// echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}
else{
    echo 'Tidak action dilakukan!';
}
?>