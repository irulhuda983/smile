<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                       = $_POST['formregact'];

$ls_kode_klaim							= $_POST["kode_klaim"];
$ls_dst_kantor							= $_POST["kode_dst_kantor"];
$ls_keterangan					    = $_POST["keterangan"];


//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $DATAID != '')
{
  //query data --------------------------------------------------------		
}
else if ($TYPE=="New")
{		
  //Cek kode klaim sudah di agenda rtwkan
  $sql = 	"select count(kode_RTW_KLAIM) as JML from sijstk.pn_RTW_KLAIM where kode_klaim='{$ls_kode_klaim}'";
  $DB->parse($sql);
  $DB->execute();
  $ls_ada = 0;
  if($row = $DB->nextrow())
    $ls_ada=$row['JML'];
  
  if($ls_ada<=0)
  {
    $ls_kode_prs = '';
    $ls_kode_tk = '';
    $sql = 	"select KODE_PERUSAHAAN,KODE_TK from sijstk.pn_KLAIM where kode_klaim='{$ls_kode_klaim}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
    {
      $ls_kode_prs = $row['KODE_PERUSAHAAN'];
      $ls_kode_tk = $row['KODE_TK'];
    }

    $sql = 	"select count(kode_RTW_KLAIM) as JML from sijstk.pn_RTW_KLAIM where kode_klaim='{$ls_kode_klaim}'";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    if($row = $DB->nextrow())
      $ls_ada=$row['JML'];

    //generate kode klaim --------------------------------------------------------
    $sql = 	"select sijstk.p_pn_genid.f_gen_kodertw as v_kode_rtw from dual ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_kode_rtw = $row["V_KODE_RTW"];
        
        //insert data ----------------------------------------------------	 
    $sql = "insert into sijstk.pn_RTW_KLAIM (
            KODE_RTW_KLAIM,
            KODE_KLAIM,
            KODE_KEPESERTAAN_PRS,
            KODE_TK,
            KODE_KANTOR_RTW,
            KETERANGAN,
            TGL_AGENDA_RTW,
            PETUGAS_AGENDA_RTW,
            STATUS_SUBMIT_AGENDA_RTW,
            TGL_SUBMIT_AGENDA_RTW,
            PETUGAS_SUBMIT_AGENDA_RTW,
            TGL_REKAM,
            PETUGAS_REKAM,STATUS )
            values (
            '{$ls_kode_rtw}',
            '{$ls_kode_klaim}',
            '{$ls_kode_prs}',
            '{$ls_kode_tk}',
            '{$ls_dst_kantor}',
            '{$ls_keterangan}',
            to_date(sysdate,'DD/MM/YYYY'),
            '{$_SESSION['USER']}',
            'Y',
            to_date(sysdate,'DD/MM/YYYY'),
            '{$_SESSION['USER']}',
            to_date(sysdate,'DD/MM/YYYY'),
            '{$_SESSION['USER']}',
            'AGENDA'
            ) ";				 
            
    $DB->parse($sql);//echo $sql;
    if($DB->execute())
    {
      $sql = 	"update pn_klaim set flag_rtw='Y' where kode_klaim='{$ls_kode_klaim}'";
      $DB->parse($sql);
      $DB->execute();
      echo '{"ret":0,"msg":"Sukses, Data agenda berhasil disimpan, session dilanjutkan..","DATAID":"'.$ls_kode_rtw.'"}';		
    }
    else 
        echo '{"ret":-1,"msg":"Proses gagal, data gagal ditambahkan...!!!"}';
  } else echo '{"ret":-1,"msg":"Proses gagal, data klaim sudah di agendakan...!!!"}';
}
?>
