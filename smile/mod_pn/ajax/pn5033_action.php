<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['TYPE'];

$ls_kode_rtw							= $_POST["kode"];


if ($TYPE=="Approve")
{
    $sukses=0;
    $sql = 	"select STATUS_RTW_KLAIM,STATUS from sijstk.pn_rtw_klaim where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    if($row = $DB->nextrow())
    {
        if($row['STATUS']=='PUTUS')
        {
            $sql = 	"Update sijstk.PN_RTW_KLAIM set STATUS_RTW_KLAIM='SR3',STATUS='IMPLEMENTASI',
            TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION['USER']}'
            where KODE_RTW_KLAIM='{$ls_kode_rtw}' ";
            $DB->parse($sql);
            if($DB->execute())
                $sukses=1;
        }else if($row['STATUS']=='BATAL')
        {
            $sql = 	"Update sijstk.PN_RTW_KLAIM set STATUS_RTW_KLAIM='SR4', STATUS_BATAL='Y',TGL_BATAL=sysdate,STATUS='IMPLEMENTASI',
            PETUGAS_BATAL='{$_SESSION['USER']}',TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION['USER']}'
            where KODE_RTW_KLAIM='{$ls_kode_rtw}' ";
            $DB->parse($sql);
            if($DB->execute())
                $sukses=1;
        }
    }
    
}
elseif ($TYPE=="Reject")
{
    $sukses=0;
    $sql = 	"Update sijstk.PN_RTW_KLAIM set STATUS='IMPLEMENTASI',
    TGL_UBAH=sysdate,PETUGAS_UBAH='{$_SESSION['USER']}'
    where KODE_RTW_KLAIM='{$ls_kode_rtw}' ";
    $DB->parse($sql);
    if($DB->execute())
        $sukses=1;
    
}

if($sukses==1)
    echo '{"ret":0,"msg":"Sukses approval/ reject, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
else 
    echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
?>
