<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = strtoupper($_POST['noform']);

$ls_kode_rtw        					= strtoupper($_POST["kode_rtw"]);
$ls_kode_faskes					        = strtoupper($_POST["faskes_kode"]);
$ls_riwayat_sakit				        = strtoupper($_POST["riwayat_pkt"]);
$ls_progosis				            = strtoupper($_POST["prognosis"]);
$ls_yg_terkena					        = strtoupper($_POST["ygterkena"]);
$ls_kondisi_tk					        = strtoupper($_POST["kondisitk"]);
$ls_tindakan_medis			            = strtoupper($_POST["tdkmedis"]);
$ls_rehab					            = strtoupper($_POST["rehab"]);
$ls_dukungan				            = strtoupper($_POST["dukkeluarga"]);
$ls_jobdesc				                = strtoupper($_POST["jobdesc"]);
$ls_bebankerja				            = strtoupper($_POST["bebankerja"]);
$ls_tugas				                = strtoupper($_POST["tugas"]);
$ls_link_kerja				            = strtoupper($_POST["linkkerja"]);
$ls_jbt				                    = strtoupper($_POST["jbt"]);
$ls_upah				                = strtoupper($_POST["upah"]);
$ls_pendidikan				            = strtoupper($_POST["pddk"]);
$ls_job_desc				            = strtoupper($_POST["jobdesc"]);
$ls_tugas				                = strtoupper($_POST["tugas"]);
$ls_kode_beban				            = strtoupper($_POST["bebankerja"]);
$ls_kode_pak				            = strtoupper($_POST["linkkerja"]);


//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		
    
    $sql = 	"select count(kode_RTW_KLAIM) as JML from sijstk.pn_RTW_infodasar where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML'];
    
    if($ls_ada<=0)
    {
    $sql = 	"insert into sijstk.pn_rtw_infodasar(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            KODE_RTW_INFODASAR,
            KODE_FASKES,
            JABATAN_TK,
            KODE_UPAH_TK,
            KODE_PENDIDIKAN_TK,
            RIWAYAT_PENYAKIT,
            PROGNOSIS,
            BAGIAN_TUBUH,
            KODE_KONDISI,
            KODE_TINDAKAN_MEDIS,
            KODE_REHABILITASI,
            KODE_DUKUNGAN_KEL,
            TGL_REKAM,
            PETUGAS_REKAM,
            JOB_DESC,
            TUGAS,
            KODE_BEBAN,
            KODE_PAK)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            'ID{$ls_kode_rtw}',
            '{$ls_kode_faskes}',
            '{$ls_jbt}',
            '{$ls_upah}',
            '{$ls_pendidikan}',
            '{$ls_riwayat_sakit}',
            '{$ls_progosis}',
            '{$ls_yg_terkena}',
            '{$ls_kondisi_tk}',
            '{$ls_tindakan_medis}',
            '{$ls_rehab}',
            '{$ls_dukungan}',
            sysdate,
            '{$_SESSION['USER']}',
            '{$ls_job_desc}',
            '{$ls_tugas}',
            '{$ls_kode_beban}',
            '{$ls_kode_pak}')";
    }else{
        $sql = 	"update sijstk.pn_rtw_infodasar set            
        KODE_FASKES='{$ls_kode_faskes}',
        JABATAN_TK='{$ls_jbt}',
        KODE_UPAH_TK='{$ls_upah}',
        KODE_PENDIDIKAN_TK='{$ls_pendidikan}',
        RIWAYAT_PENYAKIT='{$ls_riwayat_sakit}',
        PROGNOSIS='{$ls_progosis}',
        BAGIAN_TUBUH='{$ls_yg_terkena}',
        KODE_KONDISI='{$ls_kondisi_tk}',
        KODE_TINDAKAN_MEDIS='{$ls_tindakan_medis}',
        KODE_REHABILITASI='{$ls_rehab}',
        KODE_DUKUNGAN_KEL='{$ls_dukungan}',
        TGL_UBAH=sysdate,
        PETUGAS_UBAH='{$_SESSION['USER']}',
        JOB_DESC='{$ls_job_desc}',
        TUGAS='{$ls_tugas}',
        KODE_BEBAN='{$ls_kode_beban}',
        KODE_PAK='{$ls_kode_pak}'
        where kode_RTW_KLAIM='{$ls_kode_rtw}'";
    }         
    $DB->parse($sql);//echo $sql;
    if($DB->execute())
    {
        //Cek kode klaim sudah di agenda rtwkan
        $sql = 	"update sijstk.pn_rtw_klaim set status_rtw_klaim='SR1' , status='IMPLEMENTASI'
            where  kode_rtw_klaim='{$ls_kode_rtw}' and STATUS='AGENDA'";
        $DB->parse($sql);
        $DB->execute();
        echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
    }
    else 
        echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
}
elseif($TYPE=="NewInfo")
{
    $ls_hambatan		= strtoupper($_POST["hambatan"]);
    $ls_strategi		= strtoupper($_POST["strategi"]);
    $ls_mulai   		= strtoupper($_POST["tgl_mulai"]);
    $ls_selesai 		= strtoupper($_POST["tgl_selesai"]);
    $ls_biaya 		    = strtoupper($_POST["biaya"]);
    $ls_status		    = strtoupper($_POST["status"]);
    $ls_keterangan		= strtoupper($_POST["keterangan"]);
    //Cek kode klaim sudah di agenda rtwkan
    $sql = 	"insert into sijstk.pn_rtw_perencanaan_info(
        KODE_RTW_PERENCANAAN_INFO,
        KODE_RTW_AGENDA,
        HAMBATAN,
        STRATEGI_PENYELESAIAN,
        MULAI,
        SELESAI,
        ESTIMASI_BIAYA,
        STATUS_INFO,
        KETERANGAN,
        TGL_REKAM,
        PETUGAS_REKAM)
    values(
        sijstk.SEQ_PN_KODE_RTW_RENCANA_INFO.nextval,
        '{$ls_kode_rtw_agenda}',
        '{$hambatan}',
        '{$ls_strategi}',
        to_date('{$ls_mulai}','dd/mm/yyyy'),
        to_date('{$ls_selesai}','dd/mm/yyyy'),
        '{$ls_biaya}',
        '{$ls_status}',
        '{$ls_keterangan}',
        sysdate,
        '{$_SESSION['USER']}')";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}


?>
