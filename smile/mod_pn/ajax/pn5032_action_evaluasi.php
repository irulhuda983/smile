<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = $_POST['noform'];

$ls_kode_rtw 					        = $_POST["kode_rtw"];
$ls_tgl1    					        = $_POST["eval_tgl1"];
$ls_tipe				                = $_POST["eval_tipe"];
$ls_tipe_detil				            = $_POST["eval_tipe_detil"];
$ls_keterangan				            = strtoupper($_POST["eval_keterangan"]);
$ls_no_urut				            = $_POST["nourut"];

$kode_del = $_POST['f'];
$key1 = $_POST['key1'];
$key2 = $_POST['key2'];
//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		

    $sql = 	"select max(no_urut) as JML from sijstk.pn_RTW_evaluasi where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML']+1;
    else
        $ls_ada=1;
    
    //if($ls_ada<=0)
    //{
    if($TYPE=='Edit')
        $sql = "update set
        TGL_DIPERBAHARUI-to_date('{$ls_tgl1}','dd/mm/yyyy'),
        KODE_TIPE_EVALUASI-'{$ls_tipe}',
        KODE_KUSIONER-'{$ls_tipe_detil}',
        KETERANGAN_JAWABAN-'{$ls_keterangan}',
        TGL_UBAH-sysdate,
        PETUGAS_UBAH-'{$_SESSION['USER']}'
        where KODE_RTW_KLAIM='{$ls_kode_rtw}' and NO_URUT='{$ls_no_urut}'";
    else
    $sql = 	"insert into sijstk.pn_RTW_evaluasi(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            NO_URUT,
            TGL_DIPERBAHARUI,
            KODE_TIPE_EVALUASI,
            KODE_KUSIONER,
            KETERANGAN_JAWABAN,
            TGL_REKAM,
            PETUGAS_REKAM)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            '{$ls_ada}',
            to_date('{$ls_tgl1}','dd/mm/yyyy'),
            '{$ls_tipe}',
            '{$ls_tipe_detil}',
            '{$ls_keterangan}',
            sysdate,
            '{$_SESSION['USER']}')";
   /* }else{
        $sql = 	"update sijstk.pn_rtw_monitoring set            
                TGL_KUNJUNGAN=to_date('{$ls_tgl1}','dd/mm/yyyy'),
                DIS_FISIK_FUNGSI='{$ls_dis_fisik_fungsi}',
                DIS_FISIK_ANATOMIS='{$ls_dis_fisik_anatomi}',
                GANGGUAN_PSIKIS='{$ls_gangguan_fisik}',
                TIN_MEDIS_OPERATIF='{$ls_tin_medis_operatif}',
                TIN_MEDIS_NONOPERATIF='{$ls_tin_medis_nonoperatif}',
                REHAB_MEDIS='{$ls_rehab_medis}',
                REHAB_VOCATIONAL='{$ls_rehab_vocational}',
                REHAB_MENTALPSIKIS='{$ls_rehab_mentalfisik}',
                DIAGNOSA_AWAL='{$ls_diagnosa_awal}',
                DIAGNOSA_AKHIR='{$ls_diagnosa_akhir}',
                TENAGA_MEDIS_FASKES='{$ls_tenaga_medis_faskes}',
                KETERANGAN='{$ls_keterangan}',
                TGL_UBAH=sysdate,
                PETUGAS_UBAH='{$_SESSION['USER']}'
                where KODE_RTW_KLAIM='{$ls_kode_rtw}'";
    }         */
    $DB->parse($sql);//echo $sql;
    if($DB->execute())
    {
        //echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
    }
    else 
        echo 'Gagal penyimpanan data, periksa inputan atau ulangi beberapa saat lagi! ';
}else if($kode_del=="delEvaluasi")
{
    $sql = 	"delete from  sijstk.pn_rtw_evaluasi
    where KODE_RTW_KLAIM='{$_POST['key1']}'   and NO_URUT='{$_POST['key2']}'";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}

?>