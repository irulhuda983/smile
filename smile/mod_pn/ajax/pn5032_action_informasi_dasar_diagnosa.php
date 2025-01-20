<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = strtoupper($_POST['noform']);

$ls_kode_rtw        					= strtoupper($_POST["kode_rtw"]);
$ls_nama_medis					        = strtoupper($_POST["nm_medis"]);
$ls_diagnosa_detil				        = strtoupper($_POST["diagnosa_detil"]);
$ls_keterangan				            = strtoupper($_POST["keterangan"]);
$ls_no_urut                             = strtoupper($_POST['no_urut']);

$kode_del = $_POST['f'];
$key1 = $_POST['key1'];
$key2 = $_POST['key2'];
//VIEW -------------------------------------------------------------------------
if ($TYPE=="NewInfo" || $TYPE=="EditInfo")
{		
    if ($ls_diagnosa_detil == '' )
    {
      echo "Kode Detil Diagnosa tidak boleh kosong!";
      exit;
    }
    $sql = 	"select max(no_urut) as JML from sijstk.pn_RTW_infodasar_diagnosa where kode_RTW_INFODASAR='ID{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML']+1;
    else
        $ls_ada=1;
    if($TYPE=="EditInfo")
        $sql = 	"update sijstk.pn_rtw_infodasar_diagnosa set
            NAMA_TENAGA_MEDIS='{$ls_nama_medis}',
            KODE_DIAGNOSA_DETIL='{$ls_diagnosa_detil}',
            KETERANGAN='{$ls_keterangan}',
            TGL_UBAH=sysdate,
            PETUGAS_UBAH='{$_SESSION['USER']}'
            where KODE_RTW_INFODASAR='ID{$ls_kode_rtw}' and NO_URUT='{$ls_no_urut}'";
    else
        $sql = 	"insert into sijstk.pn_rtw_infodasar_diagnosa(
                KODE_RTW_INFODASAR,
                NO_URUT,
                NAMA_TENAGA_MEDIS,
                KODE_DIAGNOSA_DETIL,
                KETERANGAN,
                TGL_REKAM,
                PETUGAS_REKAM)
            values(
                'ID{$ls_kode_rtw}',
                '{$ls_ada}',
                '{$ls_nama_medis}',
                '{$ls_diagnosa_detil}',
                '{$ls_keterangan}',
                sysdate,
                '{$_SESSION['USER']}')";
        
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute())
        echo "Gagal menyimpan data, ulangi beberapa saat lagi!";
}else if ($kode_del=="delDiagnosa")
{		
    
    $sql = 	"delete from sijstk.pn_RTW_infodasar_diagnosa where kode_RTW_INFODASAR='ID{$key1}' and NO_URUT='{$key2}'"; 
    $DB->parse($sql);
    $DB->execute();
}
?>