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
    $ls_status_nonaktif         = strtoupper($_POST["status_nonaktif"]);
    $ls_status_nonaktif_old     = strtoupper($_POST["status_nonaktif_old"]);
}
if($action=='New')
{  
    if($action_no=='Jenis'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.TC_KODE_JENIS (KODE_JENIS,NAMA_JENIS,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.TC_KODE_JENIS (KODE_JENIS,NAMA_JENIS,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='JenisDetil'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.TC_KODE_JENIS_DETIL (KODE_JENIS_DETIl,NAMA_JENIS_DETIL,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM ,KODE_JENIS)
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}','{$ls_kode_parent}')";
        else
            $sql = "INSERT INTO {$schema}.TC_KODE_JENIS_DETIL (KODE_JENIS_DETIl,NAMA_JENIS_DETIL,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,KODE_JENIS )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}','{$ls_kode_parent}')";
    }else if($action_no=='Tipe'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.TC_KODE_Tipe (KODE_TIPE,NAMA_TIPE,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM)
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.TC_KODE_TIPE (KODE_TIPE,NAMA_TIPE,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='Kepemilikan'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.TC_KODE_KEPEMILIKAN (KODE_KEPEMILIKAN,NAMA_KEPEMILIKAN,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM)
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.TC_KODE_KEPEMILIKAN (KODE_KEPEMILIKAN,NAMA_KEPEMILIKAN,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='Pembayaran'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.TC_KODE_METODE_PEMBAYARAN (KODE_METODE_PEMBAYARAN,NAMA_METODE_PEMBAYARAN,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM)
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.TC_KODE_METODE_PEMBAYARAN (KODE_METODE_PEMBAYARAN,NAMA_METODE_PEMBAYARAN,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='Bank'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.TC_KODE_BANK_PEMBAYARAN (KODE_BANK_PEMBAYARAN,NAMA_BANK_PEMBAYARAN,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM)
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.TC_KODE_BANK_PEMBAYARAN (KODE_BANK_PEMBAYARAN,NAMA_BANK_PEMBAYARAN,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data,cek kode tipe sudah ada atau ulangi lagi proses save!";
    }
}else if($action=='Edit')
{  
    if($action_no=='Jenis'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.TC_KODE_JENIS set NAMA_JENIS='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where kode_jenis='{$ls_kode}'";
        else
            $sql = "update {$schema}.TC_KODE_JENIS set NAMA_JENIS='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where kode_jenis='{$ls_kode}'";
    }else if($action_no=='JenisDetil'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.TC_KODE_JENIS_DETIL set NAMA_JENIS_DETIL='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,KODE_JENIS='{$ls_kode_parent}'
                    where kode_jenis_detil='{$ls_kode}'";
        else
            $sql = "update {$schema}.TC_KODE_JENIS_DETIL set NAMA_JENIS_DETIL='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,kode_jenis='{$ls_kode_parent}'
                where kode_jenis_detil='{$ls_kode}'";
    }else if($action_no=='Tipe'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.TC_KODE_TIPE set NAMA_TIPE='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where kode_tipe='{$ls_kode}'";
        else
            $sql = "update {$schema}.TC_KODE_TIPE set NAMA_TIPE='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where kode_tipe='{$ls_kode}'";
    }else if($action_no=='Kepemilikan'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.TC_KODE_KEPEMILIKAN set NAMA_KEPEMILIKAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where kode_kepemilikan='{$ls_kode}'";
        else
            $sql = "update {$schema}.TC_KODE_KEPEMILIKAN set NAMA_KEPEMILIKAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where kode_kepemilikan='{$ls_kode}'";
    }else if($action_no=='Pembayaran'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.TC_KODE_METODE_PEMBAYARAN set NAMA_METODE_PEMBAYARAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where kode_METODE_PEMBAYARAN='{$ls_kode}'";
        else
            $sql = "update {$schema}.TC_KODE_METODE_PEMBAYARAN set NAMA_METODE_PEMBAYARAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where kode_METODE_PEMBAYARAN='{$ls_kode}'";
    }else if($action_no=='Bank'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.TC_KODE_BANK_PEMBAYARAN set NAMA_BANK_PEMBAYARAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where kode_BANK_PEMBAYARAN='{$ls_kode}'";
        else
            $sql = "update {$schema}.TC_KODE_BANK_PEMBAYARAN set NAMA_BANK_PEMBAYARAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where kode_BANK_PEMBAYARAN='{$ls_kode}'";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}else if($action=='Delete')
{  
    if($action_no=='Jenis'){
        $sql = "delete from {$schema}.TC_KODE_JENIS  where kode_jenis='{$ls_kode}'";
    }else if($action_no=='JenisDetil'){
        $sql = "delete from {$schema}.TC_KODE_JENIS_DETIL  where kode_jenis_detil='{$ls_kode}'";
    }else if($action_no=='Tipe'){
        $sql = "delete from {$schema}.TC_KODE_TIPE  where kode_tipe='{$ls_kode}'";
    }else if($action_no=='Kepemilikan'){
        $sql = "delete from {$schema}.TC_KODE_KEPEMILIKAN  where kode_kepemilikan='{$ls_kode}'";
    }else if($action_no=='Pembayaran'){
        $sql = "delete from {$schema}.TC_KODE_METODE_PEMBAYARAN  where kode_metode_pembayaran='{$ls_kode}'";
    }else if($action_no=='Bank'){
        $sql = "delete from {$schema}.TC_KODE_BANK_PEMBAYARAN  where kode_bank_pembayaran='{$ls_kode}'";
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