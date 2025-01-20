<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = $_POST['noform'];

$ls_kode_rtw 					        = $_POST["kode_rtw"];
$ls_tgl1    					        = $_POST["tgl_kunjungan"];
$ls_dis_fisik_fungsi				    = strtoupper($_POST["dis_fisik_fungsi"]);
$ls_dis_fisik_anatomi				    = strtoupper($_POST["dis_fisik_anatomi"]);
$ls_gangguan_fisik  				    = strtoupper($_POST["gangguan_psikis"]);
$ls_tin_medis_operatif			        = strtoupper($_POST["tind_medis_operatif"]);
$ls_tin_medis_nonoperatif	            = strtoupper($_POST["tind_medis_nonoperatif"]);
$ls_rehab_medis					        = strtoupper($_POST["rehab_medis"]);
$ls_rehab_vocational					= strtoupper($_POST["rehab_vocational"]);
$ls_rehab_mentalfisik				    = strtoupper($_POST["rehab_mentalpsikis"]);
$ls_diagnosa_awal				        = strtoupper($_POST["diagnosa_awal"]);
$ls_diagnosa_akhir				        = strtoupper($_POST["diagnosa_akhir"]);
$ls_tenaga_medis_faskes				    = strtoupper($_POST["tenaga_medis_faskes"]);
$ls_keterangan				            = strtoupper($_POST["keterangan"]);
$ls_no_urut				            = strtoupper($_POST["nourut"]);

$kode_del = $_POST['f'];
$key1 = $_POST['key1'];
$key2 = $_POST['key2'];
//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw_agenda != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		

    $sql = 	"select max(no_urut) as JML from sijstk.pn_RTW_monitoring where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML']+1;
    else
        $ls_ada=1;
    
    //if($ls_ada<=0)
    //{
    if($TYPE=="Edit")
        $sql="update sijstk.pn_rtw_monitoring set
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
        where KODE_RTW_KLAIM='{$ls_kode_rtw}' and NO_URUT='{$ls_no_urut}'";
    else
    $sql = 	"insert into sijstk.pn_rtw_monitoring(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            NO_URUT,
            TGL_KUNJUNGAN,
            DIS_FISIK_FUNGSI,
            DIS_FISIK_ANATOMIS,
            GANGGUAN_PSIKIS,
            TIN_MEDIS_OPERATIF,
            TIN_MEDIS_NONOPERATIF,
            REHAB_MEDIS,
            REHAB_VOCATIONAL,
            REHAB_MENTALPSIKIS,
            DIAGNOSA_AWAL,
            DIAGNOSA_AKHIR,
            TENAGA_MEDIS_FASKES,
            KETERANGAN,
            TGL_REKAM,
            PETUGAS_REKAM)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            '{$ls_ada}',
            to_date('{$ls_tgl1}','dd/mm/yyyy'),
            '{$ls_dis_fisik_fungsi}',
            '{$ls_dis_fisik_anatomi}',
            '{$ls_gangguan_fisik}',
            '{$ls_tin_medis_operatif}',
            '{$ls_tin_medis_nonoperatif}',
            '{$ls_rehab_medis}',
            '{$ls_rehab_vocational}',
            '{$ls_rehab_mentalfisik}',
            '{$ls_diagnosa_awal}',
            '{$ls_diagnosa_akhir}',
            '{$ls_tenaga_medis_faskes}',
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
}else if($kode_del=="delMonitoring")
{
    $sql = 	"delete from  sijstk.pn_rtw_monitoring
    where KODE_RTW_KLAIM='{$_POST['key1']}'   and NO_URUT='{$_POST['key2']}'";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}

?>