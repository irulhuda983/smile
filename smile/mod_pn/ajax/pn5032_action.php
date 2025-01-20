<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);


$TYPE                   = $_POST['formregact'];

$ls_kode_rtw						= $_POST["kd"];
$ls_dst_kantor					= $_POST["kode_dst_kantor"];	

if ($TYPE=="delegate")
{	
  foreach($ls_kode_rtw as $xitem1)
  {
    $sql = 	"update sijstk.pn_rtw_klaim set 
          kode_kantor_rtw='{$ls_dst_kantor}',
          tgl_delegasi=sysdate,
          petugas_delegasi='{$_SESSION['USER']}',
          kode_kantor_awal_delegasi='{$gs_kantor_aktif}'
          where kode_rtw_klaim='{$xitem1}'"; //echo $sql;
    $DB->parse($sql);
    $DB->execute();
  }
  echo '{"ret":0,"msg":"Sukses"}';	
}
?>
