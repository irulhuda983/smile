<?
session_start();
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);


$KODE_KLAIM	  = $_GET["kode_klaim"];
$TASK					= $_GET["TASK"];
$KODE_DOKUMEN	= $_GET["kode_dokumen"];



$KD_KANTOR  = $_SESSION['kdkantorrole'];
$USER 		  = $_SESSION["USER"];




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
set_error_handler("DefaultGlobalErrorHandler");
		

 

if ($TASK=='DOWNLOAD_DOK'){

  $sql_SelectBlob="SELECT NAMA_FILE, DOC_FILE FROM SIJSTK.PN_KLAIM_DOKUMEN WHERE kode_klaim='$KODE_KLAIM' and kode_dokumen='$KODE_DOKUMEN'"; 

  $statement=oci_parse($DB->conn,$sql_SelectBlob); 
  oci_execute($statement) or die($sql_SelectBlob.'<hr>'); 


   while ($row = oci_fetch_array($statement, OCI_ASSOC | OCI_RETURN_LOBS)) {
   
      $x=file_put_contents( '../../temp_download/'.$row['NAMA_FILE'], $row['DOC_FILE']);
      
      $file = '../../temp_download/'.$row['NAMA_FILE']; 
      
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