<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$dataid	      = $_REQUEST['kd'];
$f          = $_REQUEST['f'];
$i          = $_REQUEST['i'];

$sql = "SELECT NAMA_FILE,DOC_FILE,length(DOC_FILE) UKURAN
FROM sijstk.TC_IKS_LAMPIRAN 
where KODE_LAMPIRAN='{$dataid}' and kode_faskes='{$f}' and kode_iks='{$i}' ";

$DB->parse($sql); //echo $sql;
$DB->execute();
if($row = $DB->nextrow())
{
    header('Content-Type: application/pdf',true);
    header("Content-Length: {$row['UKURAN']}");
    $file_name = str_replace(array('"', "'", ' ', ','), '_', $row['NAMA_FILE']);
    header("Content-disposition: attachment;filename=\"{$row['NAMA_FILE']}\"");
    echo $row['DOC_FILE']->load();
} 
?>