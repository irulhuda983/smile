<?PHP
session_start(); 
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$TYPE                                   = $_POST['formregact'];
$noform                                   = $_POST['noform'];

$ls_kode_rtw        					= $_POST["lamp_kode_rtw"];
$kode_del = $_POST['f'];
$key1 = $_POST['key1'];
$key2 = $_POST['key2'];
//print_r($_FILES);
//print_r($_FILES);  echo $_FILES['lamp_file']['name']['a'];
//VIEW -------------------------------------------------------------------------
if ($TYPE=="View" && $ls_kode_rtw_agenda != '')
{ echo "tes";
  //query data --------------------------------------------------------		
}
else if ($TYPE=="uploaddoc")
{ 
    $sql = 	"select KODE from sijstk.ms_lookup where tipe='RTWLAMP' order by seq";
    $DB->parse($sql);
    $DB->execute();
    $ls_ada = 0;
    $ls_array_file=array();
    while($row = $DB->nextrow())
        $ls_array_file[]=$row['KODE'];
    foreach($ls_array_file as $xitem)
    {
        if($_FILES['lamp_file']['tmp_name'][$xitem]!='')
        {
            $DOC_FILE = file_get_contents($_FILES['lamp_file']['tmp_name'][$xitem]);
            $ls_ket = $_POST['lamp_ket_'.$xitem];
            if ($DOC_FILE) { 
                $sql = 	"select max(no_urut) as JML from sijstk.pn_RTW_lampiran where kode_rtw_klaim='{$ls_kode_rtw}'";
                $DB->parse($sql);
                $DB->execute();
                $ls_ada = 0;
                if($row = $DB->nextrow())
                    $ls_ada=$row['JML']+1;
                else
                    $ls_ada=1;

                $sql = "INSERT INTO sijstk.PN_RTW_LAMPIRAN (
                        KODE_RTW_KLAIM,
                        KODE_KLAIM,
                        NO_URUT,
                        KODE_JENIS_FILE,
                        NAMA_FILE,
                            DOC_FILE,
                            KETERANGAN,
                        TGL_REKAM,PETUGAS_REKAM)
                        VALUES(
                        '{$ls_kode_rtw}',
                        (select KODE_KLAIM from sijstk.PN_RTW_KLAIM where kode_RTW_KLAIM='{$ls_kode_rtw}'),
                        '{$ls_ada}',
                        '{$xitem}',
                        '{$_FILES['lamp_file']['name'][$xitem]}',
                        EMPTY_BLOB(),
                        '{$ls_ket}',sysdate,
                        '{$_SESSION['USER']}'
                        ) RETURNING DOC_FILE  INTO :LOB_A"; //echo $sql;
                if(!$DB->insertBlob($sql, 'Insert Lampiran RTW', ':LOB_A', $DOC_FILE))
                    echo "Gagal upload data dokumen";
               /* $stmt = oci_parse($DB->conn, $sql);
                //$STATUS=false;
        
                $myLOB = oci_new_descriptor($DB->conn, OCI_D_LOB);
                oci_bind_by_name($stmt, ":LOB_A", $myLOB, -1, OCI_B_BLOB);
                oci_execute($stmt, OCI_DEFAULT)
                    or die ("Unable to execute query\n");
                if ( !$myLOB->save($DOC_FILE.date('H:i:s',time())) ) {
                    $STATUS_UPLOAD=false;
                    oci_rollback($DB->conn);
                } else {
                    $STATUS_UPLOAD=oci_commit($DB->conn);
                }
                //die;
                // Free resources
                oci_free_statement($stmt);
                $myLOB->free();*/
            }
        }
    }
}else if($kode_del=="delLampiran")
{
    $sql = 	"delete from  sijstk.pn_rtw_lampiran
    where KODE_RTW_KLAIM='{$_POST['key1']}'   and NO_URUT='{$_POST['key2']}'";
    $DB->parse($sql);//echo $sql;
    if(!$DB->execute()) 
        echo "Proses gagal, data gagal ditambahkan...!!!";
}

?>