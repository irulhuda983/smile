<?
session_start();
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$USER 			= $_SESSION["USER"];
$TYPE       = $_REQUEST["TYPE"];

function handleError($errno, $errstr,$error_file,$error_line) {
    if($errno == 2){
        $errorMsg = $errstr;
        if (strpos($errstr, 'failed to open stream:') !== false) {
            $errorMsg = 'Terdapat masalah dengan koneksi WebService.';
        } elseif(strpos($errstr, 'oci_connect') !== false) {
            $errorMsg = 'Terdapat masalah dengan koneksi database.';
        } else {
            $errorMsg = $errstr;
        }
      echo '{
                "ret":-1,
                "msg":"<b>Error:</b> '.$errorMsg.'"
            }';
    die();
    }
}
// set_error_handler("DefaultGlobalErrorHandler");


if ($TYPE=='download'){

  $kode_agenda  = $_REQUEST['kode_agenda'];

  $sql_SelectBlob="SELECT NAMA_FILE, DOC_FILE FROM PN.PN_AGENDA_KOREKSI_KLAIM_UPAH where KODE_AGENDA='$kode_agenda'"; 
   // print_r($sql_SelectBlob);
   // die();

  $statement=oci_parse($DB->conn,$sql_SelectBlob); 
  oci_execute($statement) or die($sql_SelectBlob.'<hr>'); 

   while ($row = oci_fetch_array($statement, OCI_ASSOC | OCI_RETURN_LOBS)) {
   
      $x=file_put_contents( '../../temp_download/'.$row['NAMA_FILE'], $row['DOC_FILE']);
      
      $file = '../../temp_download/'.$row['NAMA_FILE'];
      // print_r($x);
      // die(); 
      
      header('Content-Description: File Transfer'); 
      //header('Content-type: application/pdf'); 
      header('Content-disposition: attachment;filename='.$row['NAMA_FILE']);

      $handle = fopen($file, "rb");
      $contents = '';
      while (!feof($handle)) {
        $contents .= fread($handle, 65536);
      }

      echo $contents;
      fclose($handle);
 }

 if(unlink($file)){
    $delete_temp='0';
  }else{
    $delete_temp='-1';
  }

}


?>