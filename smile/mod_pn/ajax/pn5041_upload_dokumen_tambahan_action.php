<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

//include '../../includes/fungsi_newrpt.php';

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);




//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER = $_SESSION["USER"];
//$USER = null;
$KODE_ROLE = $_SESSION['regrole'];

  function encrypt_decrypt($action, $string)
 {
     /* =================================================
      * ENCRYPTION-DECRYPTION
      * =================================================
      * ENCRYPTION: encrypt_decrypt('encrypt', $string);
      * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
      */
     $output = false;
     $encrypt_method = "AES-256-CBC";
     $secret_key = 'WS-SERVICE-KEY';
     $secret_iv = 'WS-SERVICE-VALUE';
     // hash
     $key = hash('sha256', $secret_key);
     // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
     $iv = substr(hash('sha256', $secret_iv), 0, 16);
     if ($action == 'encrypt') {
         $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
     } else {
         if ($action == 'decrypt') {
             $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
         }
     }
     return $output;
 }



  function api_json_call($apiurl, $header, $data) {
    $curl = curl_init();

    curl_setopt_array(
      $curl, 
      array(
        CURLOPT_URL => $apiurl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode($data),
        CURLOPT_HTTPHEADER => $header,
      )
    );
  
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
  
    if ($err) {
      $jdata["ret"] = -1;
      $jdata["msg"] = "cURL Error #:" . $err;
      $result = $jdata;
    } else {
      $result = json_decode($response);
    }
  
    return $result;
  }

  function api_json_call_get($apiurl) {
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => $apiurl,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
    ));
  
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
  
    if ($err) {
      $jdata["ret"] = -1;
      $jdata["msg"] = "cURL Error #:" . $err;
      $result = $jdata;
    } else {
      $result = json_decode($response);
    }
  
    return $result;
  }


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
    echo '{ "ret":-1, "msg":"<b>Error:</b> '.$errorMsg.'" }';
    die();
  }
}
set_error_handler("DefaultGlobalErrorHandler");


$ls_tipe      = !isset($_GET['tipe']) ? $_POST["tipe"] : $_GET["tipe"];

$ls_kode_klaim=$_POST["kode_klaim"];

if($ls_tipe=="savedokumenlain"){

  $ls_nama_dokumen_lainnya        =$_POST["nama_dokumen_lainnya"];
  $ls_keterangan_dokumen_lainnya  =$_POST["keterangan_dokumen_lainnya"];

  $ls_path_url_dokumen_lainnya    =$_POST["path_url_dokumen_lainnya"];
  $mime_type_file_dokumen_lainnya =$_POST["mime_type_file_dokumen_lainnya"];

  // $maxsize    = 6097152;

  $sql_no_urut = "SELECT MAX(NO_URUT) AS NO_URUT_MAX FROM PN.PN_KLAIM_DOKUMEN_TAMBAHAN WHERE KODE_KLAIM='$ls_kode_klaim'";
  $DB->parse($sql_no_urut);
  if($DB->execute()){
    if($row=$DB->nextrow()){
      $cek_no_urut=$row['NO_URUT_MAX']; 
    }
  }

  $ls_no_urut = $cek_no_urut+1;



  // if($_FILES['fileketerangan']['size'] != 0 && $_FILES['fileketerangan']['size'] <= $maxsize){

    
    // $curl = curl_init();
  
    //       curl_setopt_array($curl, array(
    //         CURLOPT_URL => $wsIpStorage."/put-object",
    //         CURLOPT_RETURNTRANSFER => true,
    //         CURLOPT_ENCODING => "",
    //         CURLOPT_MAXREDIRS => 10,
    //         CURLOPT_TIMEOUT => 30,
    //         CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //         CURLOPT_CUSTOMREQUEST => "POST",
    //         CURLOPT_POSTFIELDS => array(
    //                             'file' =>
    //                                 '@'            . $ls_file_dokumen_lainnya['tmp_name']
    //                                 . ';filename=' . $ls_file_dokumen_lainnya['name']
    //                                 . ';type='     . $ls_file_dokumen_lainnya['type'],
    //                                 'namaBucket' => 'lapakasik',
    //                                 'namaFolder' => $KD_KANTOR.'/'.date("Ym")
    //                           ),
    //         CURLOPT_HTTPHEADER => array(
    //           "Accept: /",
    //           "Cache-Control: no-cache",
    //           "Connection: keep-alive",
    //           "Content-Type: multipart/form-data"
    //         ),
    //       ));
  
    //       $response = curl_exec($curl);
    //       $err = curl_error($curl);
  
    //       curl_close($curl);
  
    //       if ($err) {
    //         echo "cURL Error #:" . $err;
    //       } else {
    //         $result = json_decode($response);
          

    //       $ls_path_url_dokumen_lainnya= $result->path;

    $tipe_file = substr($ls_path_url_dokumen_lainnya,-3);
  
    if($tipe_file=="pdf"){
  
        $ls_path_url_dokumen_lainnya_encrypt=encrypt_decrypt('encrypt',$ls_path_url_dokumen_lainnya);
        $result = api_json_call_get($wsIpLapakAsikOnline."/upload/validPdf?data=".$ls_path_url_dokumen_lainnya_encrypt);
        $result_status=$result->status;
    }else{
        $result_status = true;
    }

        if($result_status){
          $sql = "insert into PN.PN_KLAIM_DOKUMEN_TAMBAHAN (kode_klaim,kode_dokumen,no_urut,syarat_tahap_ke,nama_dokumen_tambahan,keterangan_dokumen_tambahan,mime_type,path_url,tgl_upload,tgl_rekam,petugas_rekam) values 
          ('$ls_kode_klaim','D222',' $ls_no_urut', '2','$ls_nama_dokumen_lainnya','$ls_keterangan_dokumen_lainnya','$mime_type_file_dokumen_lainnya','$ls_path_url_dokumen_lainnya',sysdate,sysdate,'$USER')";
          $DB->parse($sql); 
          if(!$DB->execute()){  
              $jsondata["ret"] = "-1";
              $jsondata["msg"] = "Terjadi kesalahan pada server!";
              echo json_encode($jsondata);
          }
          else{
            $jsondata["ret"] = "0";
            $jsondata["data"] = $sql;
                $jsondata["msg"] = "Sukses";
                echo json_encode($jsondata);
          }
        }else{
          
          $str_array = explode("/", $ls_path_url_dokumen_lainnya);
          $namaBucket = $str_array[1];
          $namaFolder = $str_array[2]."/".$str_array[3];
          $namaFile =  $str_array[5];

          $headers = array(
            'Content-Type: application/json',
            'X-Forwarded-For: ' . $ipfwd
          );

            $data = array(
            'namaBucket'      =>  $namaBucket,
            'namaFolder'     =>  $namaFolder,
            'file'      => $namaFile
          );
            
          $result_delete = api_json_call($wsIpStorage."/object/delete", $headers, $data);

          $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Silahkan pastikan dokumen tidak rusak/ corrupt ataupun memiliki password terlebih dahulu sebelum mengupload.";
          echo json_encode($jsondata);
        }  

        //}
        
  
  // }else{

  //   $jsondata["ret"] = "-1";
  //   $jsondata["msg"] = "Proses gagal, file dokumen tidak ada atau file dokumen besar dari 6 MB";
  //   echo json_encode($jsondata);

  // }



}else if($ls_tipe=='delete_data_dokumen'){

  $ls_kode_klaim=$_POST["kode_klaim"];
  $ls_nama_dokumen_lainnya=$_POST["nama_dokumen_lainnya"];
  $ls_no_urut=$_POST["no_urut"];
  
   if(isset($USER) && isset($KD_KANTOR)){

    $sql2="SELECT PATH_URL FROM PN.PN_KLAIM_DOKUMEN_TAMBAHAN WHERE KODE_KLAIM='$ls_kode_klaim' AND NAMA_DOKUMEN_TAMBAHAN='$ls_nama_dokumen_lainnya'";

    $DB->parse($sql2);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_path_url = $row["PATH_URL"];
    
    $str_array = explode("/", $ls_path_url);
    $namaBucket = $str_array[1];
    $namaFolder = $str_array[2]."/".$str_array[3];
    $namaFile =  $str_array[5];

    $headers = array(
      'Content-Type: application/json',
      'X-Forwarded-For: ' . $ipfwd
    );

      $data = array(
      'namaBucket'      =>  $namaBucket,
      'namaFolder'     =>  $namaFolder,
      'file'      => $namaFile
    );

    $url=$wsIpStorage."/object/delete";
    $ch     = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

    $result = curl_exec($ch);
    $err = curl_error($ch);

    curl_close($ch);

  
      $sql1="DELETE FROM PN.PN_KLAIM_DOKUMEN_TAMBAHAN WHERE KODE_KLAIM='$ls_kode_klaim' AND NAMA_DOKUMEN_TAMBAHAN='$ls_nama_dokumen_lainnya'";
    
  
      $DB->parse($sql1);
          if($DB->execute()){ 
            $jsondata["ret"] = "0";
            $jsondata["msg"] = "Sukses";
            $jsondata["kode_klaim"]=$ls_kode_klaim; 
            echo json_encode($jsondata); 
                  
        }else{
          $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Terjadi kesalahan pada server, silahkan coba beberapa saat lagi.";
          echo json_encode($jsondata);  
        } 
  
  
        
    }else{
      $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Terjadi kesalahan pada server, silahkan coba beberapa saat lagi.";
          echo json_encode($jsondata);
    }
  
}else if($ls_tipe=='simpan_catatan'){

  $ls_kode_klaim=$_POST["kode_klaim"];
  $ls_keterangan=$_POST["keterangan"];
  
   if(isset($USER) && isset($KD_KANTOR)){

    $sql2="SELECT NVL(COUNT(1),0)+1 as CEK FROM PN.PN_KLAIM_CATATAN WHERE KODE_KLAIM= '$ls_kode_klaim' AND KODE_CATATAN = '2' ORDER BY NO_URUT ASC";

    $DB->parse($sql2);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_cek = $row["CEK"];
    
    if($ls_cek=="1"){

      $sql1="INSERT INTO PN.PN_KLAIM_CATATAN (KODE_KLAIM, NO_URUT, KODE_CATATAN, KETERANGAN, TGL_REKAM, PETUGAS_REKAM) VALUES ('$ls_kode_klaim', '$ls_cek', '2', '$ls_keterangan', SYSDATE, '$USER')";

    }else{

      $sql1="UPDATE PN.PN_KLAIM_CATATAN SET KETERANGAN='$ls_keterangan', TGL_UBAH=SYSDATE, PETUGAS_UBAH='$USER' WHERE KODE_KLAIM='$ls_kode_klaim'  AND KODE_CATATAN='2' ";

    }
  
      $DB->parse($sql1);
          if($DB->execute()){ 
            $jsondata["ret"] = "0";
            $jsondata["msg"] = "Sukses";
            $jsondata["kode_klaim"]=$ls_kode_klaim; 
            echo json_encode($jsondata); 
                  
        }else{
          $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Terjadi kesalahan pada server, silahkan coba beberapa saat lagi.";
          echo json_encode($jsondata);  
        } 
  
  
        
    }else{
      $jsondata["ret"] = "-1";
          $jsondata["msg"] = "Terjadi kesalahan pada server, silahkan coba beberapa saat lagi.";
          echo json_encode($jsondata);
    }
  
}

