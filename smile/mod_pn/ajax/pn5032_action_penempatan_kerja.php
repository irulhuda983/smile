<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                   = $_POST['noform'];

$ls_kode_rtw        					= $_POST["kode_rtw"];
$ls_bekerja							    = isset($_POST["pk_work"])?$_POST["pk_work"]:'T';	
$ls_u_bekerja		                    = isset($_POST["ubekerja"])?$_POST["ubekerja"]:'';
$ls_inormasi		                    = isset($_POST["infotbh"])?$_POST["infotbh"]:'';
$ls_u_bekerja = $ls_bekerja!='Y'?'':$ls_u_bekerja;
$ls_inormasi = $ls_bekerja!='Y'?'':strtoupper($ls_inormasi);


//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw_agenda != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New" || $TYPE=="Edit")
{		
   
    $sql = 	"select count(*) as JML from sijstk.pn_RTW_penempatan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
        $ls_ada=$row['JML'];
        
    if($ls_ada<=0)
    {
    $sql = 	"insert into sijstk.pn_rtw_penempatan(
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            NO_URUT,
            STATUS_PASCA_PENGOBATAN,
            KODE_PASCA_PENGOBATAN,
            KETERANGAN,
            TGL_REKAM,
            PETUGAS_REKAM)
        values(
            '{$ls_kode_rtw}',
            (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
            '1',
            '{$ls_bekerja}',
            '{$ls_u_bekerja}',
            '{$ls_inormasi}',
            sysdate,
            '{$_SESSION['USER']}')";
    }else{
        $sql = 	"update sijstk.pn_rtw_penempatan set            
            STATUS_PASCA_PENGOBATAN='$ls_bekerja',
            KODE_PASCA_PENGOBATAN='$ls_u_bekerja',
            KETERANGAN='$ls_inormasi',
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
