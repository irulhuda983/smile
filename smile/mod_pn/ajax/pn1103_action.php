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
    $ls_keterangan     = strtoupper($_POST["keterangan"]);
    $ls_status_nonaktif         = strtoupper($_POST["status_nonaktif"]);
    $ls_status_nonaktif_old     = strtoupper($_POST["status_nonaktif_old"]);

    $ls_tgl_awal        = strtoupper($_POST["tgl_awal"]);
    $ls_tgl_akhir       = strtoupper($_POST["tgl_akhir"]);
    $ls_btk_kegiatan    = strtoupper($_POST["btkkegiatan"]);
    $ls_regulasi        = strtoupper($_POST["regulasi"]);
}
if($action=='New')
{  
    if($action_no=='Kegiatan'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_PROMOTIF_KEGIATAN (KODE_KEGIATAN,NAMA_KEGIATAN,NO_URUT,KETERANGAN,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_nourut}','{$ls_keterangan}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_PROMOTIF_KEGIATAN (KODE_KEGIATAN,NAMA_KEGIATAN,NO_URUT,KETERANGAN,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_nourut}','{$ls_keterangan}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='SubKegiatan'){
        $sql = "INSERT INTO {$schema}.PN_KODE_PROMOTIF_SUB (KODE_KEGIATAN,KODE_SUB_KEGIATAN,NAMA_SUB_KEGIATAN,KETERANGAN,TGL_EFEKTIF,TGL_AKHIR,BENTUK_KEGIATAN,REGULASI,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode_parent}','{$ls_kode}','{$ls_nama}','{$ls_keterangan}',to_date('{$ls_tgl_awal}','dd/mm/yyyy'),to_date('{$ls_tgl_akhir}','dd/mm/yyyy'),'{$ls_btk_kegiatan}','{$ls_regulasi}',sysdate,'{$_SESSION["USER"]}')";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data,cek kode  sudah ada atau ulangi lagi proses save!";
    }
}else if($action=='Edit')
{  
    if($action_no=='Kegiatan'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_PROMOTIF_KEGIATAN set NAMA_KEGIATAN='{$ls_nama}',NO_URUT='{$ls_nourut}',KETERANGAN='{$ls_keterangan}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,
                    where kode_kegiatan='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_PROMOTIF_KEGIATAN set NAMA_KEGIATAN='{$ls_nama}',NO_URUT='{$ls_nourut}',KETERANGAN='{$ls_keterangan}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where kode_kegiatan='{$ls_kode}'";
    }else if($action_no=='SubKegiatan'){
        $sql = "update {$schema}.PN_KODE_PROMOTIF_SUB set NAMA_SUB_KEGIATAN='{$ls_nama}',TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,
            KODE_KEGIATAN='{$ls_kode_parent}',TGL_EFEKTIF=to_date('{$ls_tgl_awal}','dd/mm/yyyy'),TGL_AKHIR=to_date('{$ls_tgl_akhir}','dd/mm/yyyy'),
            BENTUK_KEGIATAN='{$ls_btk_kegiatan}',REGULASI='{$ls_regulasi}',KETERANGAN='{$ls_keterangan}'
                where KODE_SUB_KEGIATAN='{$ls_kode}'";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}else if($action=='Delete')
{  
    if($action_no=='Kegiatan'){
        $sql = "delete from {$schema}.PN_KODE_PROMOTIF_KEGIATAN  where kode_kegiatan='{$ls_kode}'";
    }else if($action_no=='SubKegiatan'){
        $sql = "delete from {$schema}.PN_KODE_PROMOTIF_SUB  where kode_sub_kegiatan='{$ls_kode}'";
    }
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}
else{
    echo 'Tidak action dilakukan!';
}
?>