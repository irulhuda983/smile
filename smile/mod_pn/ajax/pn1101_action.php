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
    if($action_no=='TipeKlaim'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_TIPE_KLAIM (KODE_TIPE_KLAIM,NAMA_TIPE_KLAIM,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,PETUGAS_NONAKTIF )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}','{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_TIPE_KLAIM (KODE_TIPE_KLAIM,NAMA_TIPE_KLAIM,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='TipePenerima'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_TIPE_PENERIMA (KODE_TIPE_PENERIMA,NAMA_TIPE_PENERIMA,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_TIPE_PENERIMA (KODE_TIPE_PENERIMA,NAMA_TIPE_PENERIMA,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='GroupICD'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_GROUP_ICD (KODE_GROUP_ICD,NAMA_GROUP_ICD,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_GROUP_ICD (KODE_GROUP_ICD,NAMA_GROUP_ICD,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='LokasiKecelakaan'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_LOKASI_KECELAKAAN (KODE_LOKASI_KECELAKAAN,NAMA_LOKASI_KECELAKAAN,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_LOKASI_KECELAKAAN (KODE_LOKASI_KECELAKAAN,NAMA_LOKASI_KECELAKAAN,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='KondisiTerakhir'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_KONDISI_TERAKHIR (KODE_KONDISI_TERAKHIR,NAMA_KONDISI_TERAKHIR,KODE_TIPE_KLAIM,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_KONDISI_TERAKHIR (KODE_KONDISI_TERAKHIR,NAMA_KONDISI_TERAKHIR,KODE_TIPE_KLAIM,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='JenisKasus'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_JENIS_KASUS (KODE_JENIS_KASUS,NAMA_JENIS_KASUS,KODE_TIPE_KLAIM,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_JENIS_KASUS (KODE_JENIS_KASUS,NAMA_JENIS_KASUS,KODE_TIPE_KLAIM,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='Akibat'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_AKIBAT_DIDERITA (KODE_AKIBAT_DIDERITA,NAMA_AKIBAT_DIDERITA,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_AKIBAT_DIDERITA (KODE_AKIBAT_DIDERITA,NAMA_AKIBAT_DIDERITA,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='Diagnosa'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_DIAGNOSA (KODE_DIAGNOSA,NAMA_DIAGNOSA,KODE_GROUP_ICD,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
                values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}',99)";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_DIAGNOSA(KODE_DIAGNOSA,NAMA_DIAGNOSA,KODE_GROUP_ICD,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
            values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}',99)";
    }else if($action_no=='DiagnosaDetil'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_DIAGNOSA_DETIL (KODE_DIAGNOSA_DETIL,NAMA_DIAGNOSA_DETIL,KODE_DIAGNOSA,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
                values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}',99)";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_DIAGNOSA_DETIL (KODE_DIAGNOSA_DETIL,NAMA_DIAGNOSA_DETIL,KODE_DIAGNOSA,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
            values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}',99)";
    }else if($action_no=='Dokumen'){
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_DOKUMEN (KODE_DOKUMEN,NAMA_DOKUMEN,STATUS_NONAKTIF,TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
                values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}',999)";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_DOKUMEN (KODE_DOKUMEN,NAMA_DOKUMEN,STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
            values('{$ls_kode}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}',999)";
    }else if($action_no=='SebabKlaim'){
        $ls_f_meninggal = ($_POST['fmeninggal'] == null || $_POST['fmeninggal'] == '') ? 'T' : $_POST['fmeninggal'];
        $ls_f_partial =  ($_POST['fpartial'] == null || $_POST['fpartial'] == '') ? 'T' : $_POST['fpartial'];
        $ls_prosen  = $_POST['persen'];
        $ls_keyword = $_POST['keyword'];
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_SEBAB_KLAIM (KODE_SEBAB_KLAIM,NAMA_SEBAB_KLAIM,KODE_TIPE_KLAIM,FLAG_MENINGGAL,FLAG_PARTIAL,PERSEN_PENGAMBILAN_MAKSIMUM ,KEYWORD,
                STATUS_NONAKTIF, TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT)
                values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_f_meninggal}','{$ls_f_partial}','{$ls_prosen}','{$ls_keyword}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}',999)";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_SEBAB_KLAIM (KODE_SEBAB_KLAIM,NAMA_SEBAB_KLAIM,KODE_TIPE_KLAIM,FLAG_MENINGGAL,FLAG_PARTIAL,PERSEN_PENGAMBILAN_MAKSIMUM,KEYWORD,
                STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
            values('{$ls_kode}','{$ls_nama}','{$ls_kode_parent}','{$ls_f_meninggal}','{$ls_f_partial}','{$ls_prosen}','{$ls_keyword}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}',999)";
    }else if($action_no=='SebabSegmen'){
        $ls_kode_sebab_klaim = $_POST['kode_parent'];
        $ls_kode_segmen = $_POST['kode_parent1'];
        $sql="select count(*) as JML from {$schema}.PN_KODE_SEBAB_SEGMEN where
                KODE_SEGMEN='{$ls_kode_segmen}' and KODE_SEBAB_KLAIM='{$ls_kode_sebab_klaim}'";
        $ls_jumlah=0;
        $DB->parse($sql); //echo $sql;
        if($DB->execute()) 
            if($row = $DB->nextrow())
                $ls_jumlah=$row['JML'];
        
        if($ls_jumlah>0){
            echo "Tidak bisa menambah data, Sudah ada data untuk kode segmen dan kode sebab klaim tersebut!";
            exit(0);
        }
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_SEBAB_SEGMEN (KODE_SEBAB_KLAIM,KODE_SEGMEN,KETERANGAN,
                STATUS_NONAKTIF, TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM)
                values('{$ls_kode_sebab_klaim}','{$ls_kode_segmen}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}')";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_SEBAB_SEGMEN (KODE_SEBAB_KLAIM,KODE_SEGMEN,KETERANGAN,
                STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM )
                values('{$ls_kode_sebab_klaim}','{$ls_kode_segmen}','{$ls_nama}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}')";
    }else if($action_no=='Manfaat'){
        $ls_kategori = $_POST['kategori'];
        $ls_jenis = $_POST['jenis'];
        $ls_tipe = $_POST['tipe'];
        $ls_keterangan = $_POST['keterangan'];
        $ls_f_berkala = $_POST['fberkala']=="Y"?"Y":"T";
        if($ls_status_nonaktif=='Y')
            $sql = "INSERT INTO {$schema}.PN_KODE_MANFAAT (KODE_MANFAAT,NAMA_MANFAAT,KATEGORI_MANFAAT,JENIS_MANFAAT,TIPE_MANFAAT,FLAG_BERKALA,KETERANGAN,
                STATUS_NONAKTIF, TGL_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT)
                values('{$ls_kode}','{$ls_nama}','{$ls_kategori}','{$ls_jenis}','{$ls_tipe}','{$ls_f_berkala}','{$ls_keterangan}','{$ls_status_nonaktif}',sysdate,sysdate,'{$_SESSION["USER"]}',999)";
        else
            $sql = "INSERT INTO {$schema}.PN_KODE_MANFAAT (KODE_MANFAAT,NAMA_MANFAAT,KATEGORI_MANFAAT,JENIS_MANFAAT,TIPE_MANFAAT,FLAG_BERKALA,KETERANGAN,
                STATUS_NONAKTIF,TGL_REKAM,PETUGAS_REKAM,NO_URUT )
            values('{$ls_kode}','{$ls_nama}','{$ls_kategori}','{$ls_jenis}','{$ls_tipe}','{$ls_f_berkala}','{$ls_keterangan}','{$ls_status_nonaktif}',sysdate,'{$_SESSION["USER"]}',999)";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data,cek kode tipe sudah ada atau ulangi lagi proses save!";
    }
}else if($action=='Edit')
{  
    if($action_no=='TipeKlaim'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_TIPE_KLAIM set NAMA_TIPE_KLAIM='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,PETUGAS_NONAKTIF='{$_SESSION["USER"]}'
                    where KODE_TIPE_KLAIM='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_TIPE_KLAIM set NAMA_TIPE_KLAIM='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where KODE_TIPE_KLAIM='{$ls_kode}'";
    }else if($action_no=='TipePenerima'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_TIPE_PENERIMA set NAMA_TIPE_PENERIMA='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                    where KODE_TIPE_PENERIMA='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_TIPE_PENERIMA set NAMA_TIPE_PENERIMA='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where KODE_TIPE_PENERIMA='{$ls_kode}'";
    }else if($action_no=='GroupICD'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_GROUP_ICD set NAMA_GROUP_ICD='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}'
                    where KODE_GROUP_ICD='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_GROUP_ICD set NAMA_GROUP_ICD='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where KODE_GROUP_ICD='{$ls_kode}'";
    }else if($action_no=='LokasiKecelakaan'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_LOKASI_KECELAKAAN set NAMA_LOKASI_KECELAKAAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}'
                    where KODE_LOKASI_KECELAKAAN='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_LOKASI_KECELAKAAN set NAMA_LOKASI_KECELAKAAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' 
                where KODE_LOKASI_KECELAKAAN='{$ls_kode}'";
    }else if($action_no=='KondisiTerakhir'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_KONDISI_TERAKHIR set NAMA_KONDISI_TERAKHIR='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_TIPE_KLAIM='{$ls_kode_parent}'
                    where KODE_KONDISI_TERAKHIR='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_KONDISI_TERAKHIR set NAMA_KONDISI_TERAKHIR='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,KODE_TIPE_KLAIM='{$ls_kode_parent}'
                where KODE_KONDISI_TERAKHIR='{$ls_kode}'";
    // }else if($action_no=='Jenis'){
    }else if($action_no=='JenisKasus'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_JENIS_KASUS set NAMA_JENIS_KASUS='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_TIPE_KLAIM='{$ls_kode_parent}'
                    where KODE_JENIS_KASUS='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_JENIS_KASUS set NAMA_JENIS_KASUS='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}' ,KODE_TIPE_KLAIM='{$ls_kode_parent}'
                where KODE_JENIS_KASUS='{$ls_kode}'";
    }else if($action_no=='Akibat'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_AKIBAT_DIDERITA set NAMA_AKIBAT_DIDERITA='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}'
                    where KODE_AKIBAT_DIDERITA='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_AKIBAT_DIDERITA set NAMA_AKIBAT_DIDERITA='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}'
                where KODE_AKIBAT_DIDERITA='{$ls_kode}'";
    }else if($action_no=='Diagnosa'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_DIAGNOSA set NAMA_DIAGNOSA='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_GROUP_ICD='{$ls_kode_parent}'
                    where KODE_DIAGNOSA='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_DIAGNOSA set NAMA_DIAGNOSA='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_GROUP_ICD='{$ls_kode_parent}'
                where KODE_DIAGNOSA='{$ls_kode}'";
    }else if($action_no=='DiagnosaDetil'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_DIAGNOSA_DETIL set NAMA_DIAGNOSA_DETIL='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_DIAGNOSA='{$ls_kode_parent}'
                    where KODE_DIAGNOSA_DETIL='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_DIAGNOSA_DETIL set NAMA_DIAGNOSA_DETIL='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_DIAGNOSA='{$ls_kode_parent}'
                where KODE_DIAGNOSA_DETIL='{$ls_kode}'";
    }else if($action_no=='Dokumen'){
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_DOKUMEN set NAMA_DOKUMEN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}'
                    where KODE_DOKUMEN='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_DOKUMEN set NAMA_DOKUMEN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}'
                where KODE_DOKUMEN='{$ls_kode}'";
    }else if($action_no=='SebabKlaim'){
        // $ls_f_meninggal = $_POST['fmeninggal'];
        // $ls_f_partial =  $_POST['fpartial'];
        $ls_f_meninggal = ($_POST['fmeninggal'] == null || $_POST['fmeninggal'] == '') ? 'T' : $_POST['fmeninggal'];
        $ls_f_partial =  ($_POST['fpartial'] == null || $_POST['fpartial'] == '') ? 'T' : $_POST['fpartial'];
        $ls_prosen  = $_POST['persen'];
        $ls_keyword = $_POST['keyword'];
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_SEBAB_KLAIM set NAMA_SEBAB_KLAIM='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',FLAG_MENINGGAL='{$ls_f_meninggal}',
                    FLAG_PARTIAL='{$ls_f_partial}',PERSEN_PENGAMBILAN_MAKSIMUM='{$ls_prosen}',KODE_TIPE_KLAIM='{$ls_kode_parent}',
                    KEYWORD='{$ls_keyword}'
                    where KODE_SEBAB_KLAIM='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_SEBAB_KLAIM set NAMA_SEBAB_KLAIM='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',FLAG_MENINGGAL='{$ls_f_meninggal}',
                FLAG_PARTIAL='{$ls_f_partial}',PERSEN_PENGAMBILAN_MAKSIMUM='{$ls_prosen}',KODE_TIPE_KLAIM='{$ls_kode_parent}',
                KEYWORD='{$ls_keyword}'
                where KODE_SEBAB_KLAIM='{$ls_kode}'";
    }else if($action_no=='SebabSegmen'){
        $ls_kode_sebab_klaim = $_POST['kode_parent'];
        $ls_kode_segmen = $_POST['kode_parent1'];
        $ls_kode_sebab_klaim_old = $_POST['kode_parent_old'];
        $ls_kode_segmen_old = $_POST['kode_parent1_old'];
        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_SEBAB_SEGMEN set KETERANGAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_SEBAB_KLAIM='{$ls_kode_sebab_klaim}',
                    KODE_SEGMEN='{$ls_kode_sebab_klaim}'
                    where KODE_SEBAB_KLAIM='{$ls_kode_sebab_klaim_old}' and KODE_SEGMEN='{$ls_kode_segmen_old}'";
        else
            $sql = "update {$schema}.PN_KODE_SEBAB_SEGMEN set KETERANGAN='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KODE_SEBAB_KLAIM='{$ls_kode_sebab_klaim}',
                KODE_SEGMEN='{$ls_kode_segmen}'
                where KODE_SEBAB_KLAIM='{$ls_kode_sebab_klaim_old}' and KODE_SEGMEN='{$ls_kode_segmen_old}'";
    }else if($action_no=='Manfaat'){
        $ls_kategori = $_POST['kategori'];
        $ls_jenis = $_POST['jenis'];
        $ls_tipe = $_POST['tipe'];
        $ls_keterangan = $_POST['keterangan'];
        $ls_f_berkala = $_POST['fberkala']=="Y"?"Y":"T";

        if(strtoupper($ls_status_nonaktif)=='Y' && (strtoupper($ls_status_nonaktif_old)=='T' || trim($ls_status_nonaktif_old)==''))
            $sql = "update {$schema}.PN_KODE_MANFAAT set NAMA_MANFAAT='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                    TGL_NONAKTIF=sysdate,TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KETERANGAN='{$ls_keterangan}',
                    KATEGORI_MANFAAT='{$ls_kategori}',JENIS_MANFAAT='{$ls_jenis}',TIPE_MANFAAT='{$ls_tipe}',FLAG_BERKALA='{$ls_f_berkala}'
                    where KODE_MANFAAT='{$ls_kode}'";
        else
            $sql = "update {$schema}.PN_KODE_MANFAAT set NAMA_MANFAAT='{$ls_nama}',STATUS_NONAKTIF='{$ls_status_nonaktif}',
                TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION["USER"]}',KETERANGAN='{$ls_keterangan}',
                KATEGORI_MANFAAT='{$ls_kategori}',JENIS_MANFAAT='{$ls_jenis}',TIPE_MANFAAT='{$ls_tipe}',FLAG_BERKALA='{$ls_f_berkala}'
                where KODE_MANFAAT='{$ls_kode}'";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}else if($action=='Delete')
{  
    if($action_no=='TipeKlaim'){
        $sql = "delete from {$schema}.PN_KODE_TIPE_KLAIM  where KODE_TIPE_KLAIM='{$ls_kode}'";
    }else if($action_no=='TipePenerima'){
        $sql = "delete from {$schema}.PN_KODE_TIPE_PENERIMA  where KODE_TIPE_PENERIMA='{$ls_kode}'";
    }else if($action_no=='GroupICD'){
        $sql = "delete from {$schema}.PN_KODE_GROUP_ICD  where KODE_GROUP_ICD='{$ls_kode}'";
    }else if($action_no=='LokasiKecelakaan'){
        $sql = "delete from {$schema}.PN_KODE_LOKASI_KECELAKAAN  where KODE_LOKASI_KECELAKAAN='{$ls_kode}'";
    }else if($action_no=='KondisiTerakhir'){
        $sql = "delete from {$schema}.PN_KODE_KONDISI_TERAKHIR where KODE_KONDISI_TERAKHIR='{$ls_kode}'";
    }else if($action_no=='JenisKasus'){
        $sql = "delete from {$schema}.PN_KODE_JENIS_KASUS where KODE_JENIS_KASUS='{$ls_kode}'";
    }else if($action_no=='Akibat'){
        $sql = "delete from {$schema}.PN_KODE_AKIBAT_DIDERITA where KODE_AKIBAT_DIDERITA='{$ls_kode}'";
    }else if($action_no=='Diagnosa'){
        $sql = "delete from {$schema}.PN_KODE_DIAGNOSA where KODE_DIAGNOSA='{$ls_kode}'";
    }else if($action_no=='DiagnosaDetil'){
        $sql = "delete from {$schema}.PN_KODE_DIAGNOSA_DETIL where KODE_DIAGNOSA_DETIL='{$ls_kode}'";
    }else if($action_no=='Dokumen'){
        $sql = "delete from {$schema}.PN_KODE_DOKUMEN where KODE_DOKUMEN='{$ls_kode}'";
    }else if($action_no=='SebabKlaim'){
        $sql = "delete from {$schema}.PN_KODE_SEBAB_KLAIM where KODE_SEBAB_KLAIM='{$ls_kode}'";
    }else if($action_no=='SebabSegmen'){
        list($dataid1,$dataid2)=explode("_",$ls_kode);
        $sql = "delete from {$schema}.PN_KODE_SEBAB_SEGMEN where KODE_SEBAB_KLAIM='{$dataid1}' and KODE_SEGMEN='{$dataid2}'";
    }else if($action_no=='Manfaat'){
        $sql = "delete from {$schema}.PN_KODE_MANFAAT where KODE_MANFAAT='{$ls_kode}'";
    }
    $DB->parse($sql); //echo $sql;
    if(!$DB->execute()){	
        echo "Gagal penyimpanan data, ulangi lagi proses save!";
    }
}
else{
    echo 'Tidak action dilakukan!';
}
?>