<?PHP
session_start(); 
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE       = $_POST['formregact'];
$kode_faskes= $_POST['h_kode_faskes'];
$kode_iks   =$_POST['h_kode_iks'];
$kode_iks_lamp   =$_POST['h_kode_iks_lamp'];
$no_iks     =$_POST['i_no_iks'];
$tgl_awal   =$_POST['i_tgl_awal'];
$tgl_akhir  =$_POST['i_tgl_akhir'];

if ($TYPE=="uplamp")
{ 
    if($_FILES['fname']['tmp_name']!='')
    {
        $maks_file_size = 5; // in MB;
        $maks_file_name_length = 75;
        if( round($_FILES['fname']['size'] / 1024 / 1024, 2 ) > $maks_file_size ) {
            echo "Gagal, ukuran file melebihi batas maksimal {$maks_file_size}MB";
        } else if(strlen($_FILES['fname']['name'])>$maks_file_name_length){
            echo "Gagal, Panjang nama file maksimal {$maks_file_name_length} character ";
        }else {
            $DOC_FILE = file_get_contents($_FILES['fname']['tmp_name']);
            if ($DOC_FILE) { 
                $sql = "INSERT INTO sijstk.TC_IKS_LAMPIRAN (
                        KODE_LAMPIRAN,
                        KODE_FASKES,
                        KODE_IKS,
                        NO_IKS,
                        TGL_AWAL_IKS,
                        TGL_AKHIR_IKS,
                        NAMA_FILE,
                        DOC_FILE,
                        TGL_REKAM,
                        PETUGAS_REKAM)
                        VALUES(
                        nvl( (select max(kode_lampiran)+1 JML from sijstk.TC_IKS_LAMPIRAN where kode_faskes ='{$kode_faskes}' and kode_iks='{$kode_iks}'), 1),
                        '{$kode_faskes}',
                        '{$kode_iks}',
                        '{$no_iks}',
                        TO_DATE('{$tgl_awal}','DD/MM/YYYY'),
                        TO_DATE('{$tgl_akhir}','DD/MM/YYYY'),
                        '{$_FILES['fname']['name']}',
                        EMPTY_BLOB(),
                        sysdate,
                        '{$_SESSION['USER']}'
                        ) RETURNING DOC_FILE  INTO :LOB_A"; //echo $sql;
                if(!$DB->insertBlob($sql, 'Insert Lampiran iks', ':LOB_A', $DOC_FILE))
                    echo "Gagal upload data dokumen";
            }
        }
    }
}else if($TYPE=="delLamp")
{
    $sql = "begin tc.p_tc_faskes.X_IKS_LAMPIRAN_DEL('{$kode_faskes}','{$kode_iks}','{$kode_iks_lamp}',:P_OUT);end;";
        $proc = $DB->parse($sql); //echo $sql; die;
        $p_out=0;
        oci_bind_by_name($proc,":P_OUT",$p_out,10);
        if(!$DB->execute()){   
            echo "Gagal penghapusan lampiran IKS!";
        }else
        {
            if($p_out=='0')
                echo "Gagal  penghapusan lampiran IKS!";   
        }
}

?>