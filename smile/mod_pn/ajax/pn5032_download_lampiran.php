<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$dataid	      = $_POST["dataid"] . $_GET['dataid'];
$no           = $_POST["no"] . $_GET['no'];

$sql = "SELECT NAMA_FILE,DOC_FILE,length(DOC_FILE) UKURAN
FROM sijstk.PN_RTW_LAMPIRAN 
where KODE_RTW_KLAIM='{$dataid}' and NO_URUT='{$no}'";

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