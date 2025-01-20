<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                   = $_POST['noform'];

$ls_kode_rtw        					= $_POST["pk_kode_rtw"];
$ls_impairement					        = $_POST["sk_impairement"];
$ls_impairement_lain					= $ls_impairement=='RTWST04'?$_POST["sk_impairement_lain"]:'';
$ls_endanger							= isset($_POST["sk_endanger"])?$_POST["sk_endanger"]:'T';	
$ls_toleransi							= isset($_POST["sk_toleransi"])?$_POST["sk_toleransi"]:'T';
$ls_kelaikan							= isset($_POST["sk_kelaikan"])?$_POST["sk_kelaikan"]:'T';
$ls_startingwork					    = isset($_POST["sk_startingwork"])?$_POST["sk_startingwork"]:'T';
$ls_startingwork_drop					= isset($_POST["sk_startingwork_drop"])?$_POST["sk_startingwork_drop"]:'T';
$ls_work_medic_problem					= isset($_POST["sk_work_medic_problem"])?$_POST["sk_work_medic_problem"]:'T';
$ls_work_comunity						= isset($_POST["sk_work_comunity"])?$_POST["sk_work_comunity"]:'T';
$ls_nowork_temp							= isset($_POST["sk_nowork_temp"])?$_POST["sk_nowork_temp"]:'T';
$ls_work_other						    = isset($_POST["sk_work_other"])?$_POST["sk_work_other"]:'T';	
$ls_work_other_i			 		    = $ls_work_other=='Y'?strtoupper($_POST["sk_work_other_i"]):'';
$ls_ref_kerja_i							= strtoupper($_POST["sk_ref_kerja_i"]);
$ls_ref_prs_i						    = strtoupper($_POST["sk_ref_prs_i"]);
$ls_keterangan				            = strtoupper($_POST["sk_keterangan"]);
//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw_agenda != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		
    //Cek kode klaim sudah di agenda rtwkan

    $sql = 	"select count(*) as JML from sijstk.pn_RTW_kecacatan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML'];
    if($ls_ada<=0)
    {
    $sql = 	"insert into sijstk.pn_rtw_kecacatan(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            NO_URUT,
            KODE_STATUS_KECACATAN,
            STATUS_KECACATAN_LAINNYA,
            FLG_BAHAYA,
            FLG_TOLERANSI,
            FLG_KELAYAKAN,
            FLG_PEKERJAANSEMULA,
            FLG_EFEKTIVITAS,
            FLG_KONDISIMEDIS,
            FLG_RESIKO,
            FLG_FISIKMENTAL,
            FLG_LAINNYA,
            KETERANGAN_FLG_LAINNYA,
            REF_TK,
            REF_PERUSAHAAN,
            KETERANGAN,
            TGL_REKAM,
            PETUGAS_REKAM)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            '{$ls_ada}',
            '{$ls_impairement}',
            '{$ls_impairement_lain}',
            '{$ls_endanger}',
            '{$ls_toleransi}',
            '{$ls_kelaikan}',
            '{$ls_startingwork}',
            '{$ls_startingwork_drop}',
            '{$ls_work_medic_problem}',
            '{$ls_work_comunity}',
            '{$ls_nowork_temp}',
            '{$ls_work_other}',
            '{$ls_work_other_i}',
            '{$ls_ref_kerja_i}',
            '{$ls_ref_prs_i}',
            '{$ls_keterangan}',
            sysdate,
            '{$_SESSION['USER']}')";
    }else{
        $sql = 	"update sijstk.pn_rtw_kecacatan set            
            KODE_STATUS_KECACATAN='{$ls_impairement}',
            STATUS_KECACATAN_LAINNYA='{$ls_impairement_lain}',
            FLG_BAHAYA='{$ls_endanger}',
            FLG_TOLERANSI='{$ls_toleransi}',
            FLG_KELAYAKAN='{$ls_kelaikan}',
            FLG_PEKERJAANSEMULA='{$ls_startingwork}',
            FLG_EFEKTIVITAS='{$ls_startingwork_drop}',
            FLG_KONDISIMEDIS='{$ls_work_medic_problem}',
            FLG_RESIKO='{$ls_work_comunity}',
            FLG_FISIKMENTAL='{$ls_nowork_temp}',
            FLG_LAINNYA='{$ls_work_other}',
            KETERANGAN_FLG_LAINNYA='{$ls_work_other_i}',
            REF_TK='{$ls_ref_kerja_i}',
            REF_PERUSAHAAN='{$ls_ref_prs_i}',
            KETERANGAN='{$ls_keterangan}',            
            TGL_UBAH=sysdate,
            PETUGAS_UBAH='{$_SESSION['USER']}'
            where KODE_RTW_KLAIM='{$ls_kode_rtw}'";
    }         
    $DB->parse($sql);//echo $sql;
    if($DB->execute())
    {
        echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
    }
    else 
        echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
}


?>
