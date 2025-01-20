<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                 = $_POST['noform'];

$ls_kode_rtw 					        = $_POST["kode_rtw"];
$ls_status				            = strtoupper($_POST["f_status_rtw"]);
$ls_keterangan				            = strtoupper($_POST["f_keterangan"]);

//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="Edit")
{		
    if($ls_status=='S')
        $sql = "update sijstk.PN_RTW_KLAIM set STATUS_RTW_KLAIM='SR2',
        TGL_SELESAI=sysdate,
        PETUGAS_SELESAI='{$_SESSION['USER']}',
        STATUS_SELESAI='Y',
        KETERANGAN='{$ls_keterangan}'
        where KODE_RTW_KLAIM='{$ls_kode_rtw}' and STATUS_RTW_KLAIM='SR1'";
    else if($ls_status=='P')
        $sql = "update sijstk.PN_RTW_KLAIM set STATUS='PUTUS',
        KETERANGAN='{$ls_keterangan}'
        where KODE_RTW_KLAIM='{$ls_kode_rtw}' and STATUS_RTW_KLAIM='SR1'";
    else if($ls_status=='B')
        $sql = "update sijstk.PN_RTW_KLAIM set STATUS='BATAL',
        KET_BATAL='{$ls_keterangan}',
        KETERANGAN='{$ls_keterangan}'
        where KODE_RTW_KLAIM='{$ls_kode_rtw}' and (STATUS_RTW_KLAIM='SR2' or STATUS='PUTUS')";
    else $sql="";
   
    $DB->parse($sql);echo $sql;
    if(!$DB->execute())
    {
    echo '{"ret":0,"msg":"Sukses, Data finalisasi berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
    }
    else 
        echo '{"ret":-1,"msg":"Gagal penyimpanan data,  ulangi beberapa saat lagi!...!!!"}';
}
?>