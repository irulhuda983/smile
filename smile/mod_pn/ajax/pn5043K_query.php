<?PHP
require_once "../../includes/conf_global.php";
require_once "../../includes/fungsi.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

session_start();
//
/* error_reporting(E_ALL);
ini_set('display_errors', 1);  */

$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];


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

set_error_handler("DefaultGlobalErrorHandler");
$ls_tipe      = $_POST["tipe"];


if ($ls_tipe == "select")
{

  $ls_search_by = $_POST["search_by"];
  $ls_search_txt = $_POST["search_txt"];
  $ls_kode_kantor = $_POST["kode_kantor"];
  $ls_kode_user = $_POST["kode_user"];
  $ls_tglakhirdisplay = $_POST['tglakhirdisplay'];
  $ls_tglawaldisplay = $_POST['tglawaldisplay'];
  $ls_page = $_POST["page"];
  $ls_page_item = $_POST["page_item"];

  $fl_st_transfer = $_POST["fl_st_transfer"];
  if($fl_st_transfer!="SEMUA") {
    $fl_st_transfer_1 = "INFO_STATUS_TRANSFER";
  }

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";

  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;



  // CALL API
  $url1 = $wsIp ."/JSPN5043/DataGridMonitoringPLKK";
  $headers1 = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );
  $data1 = array(
    "chId"  => "SMILE",
    "reqId"  =>  $ls_kode_user,
    "KODE_KANTOR"  => $ls_kode_kantor,
    "TGL_AWAL" => $ls_tglawaldisplay,
    "TGL_AKHIR" => $ls_tglakhirdisplay,
    "PAGE" => (int)$ls_page,
    "NROWS" => (int)$ls_page_item,
    "C_COLNAME"=> $search_by,
    "C_COLVAL"=> $search_txt,
    "C_COLNAME2" => $fl_st_transfer_1,
    "C_COLVAL2" => $fl_st_transfer
  );
  $result1 = api_json_call($url1, $headers1, $data1);
  if ($result1->ret == "0"){
    $jsondata["ret"] = "1";
    $jsondata["start"] = $start;
    $jsondata["end"] = $end;
    $jsondata["page"] = $ls_page;
    $jsondata["recordsTotal"] = $result1->TOTAL_REC;
    $jsondata["recordsFiltered"] = $result1->TOTAL_REC;
    $jsondata["pages"] = $result1->NUM_OF_PAGE;
    $jsondata["data"] = $result1->DATA;
    $jsondata["msg"] = $result1->msg;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";
    $jsondata["recordsTotal"] = "0";
    $jsondata["recordsFiltered"] = "0";
    $jsondata["pages"] = "0";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }
}

else if ($ls_tipe == "act_getstatus")
{
  // CALL API
  $url1 = $wsIp ."/JSPN5043/GetTransferStatusPLKK";
  $headers1 = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );
  $data1 = array(
    "chId"  => "SMILE",
    "reqId"  =>  $USER,
    "KODE_TRANSFER" => $_POST["KODE_TRANSFER"],
    "KODE_TIPE_PENERIMA" => $_POST["KODE_TIPE_PENERIMA"],
    "KD_PRG" => (int)$_POST["KD_PRG"],
    "NOREK_TUJUAN" => $_POST["NOREK_TUJUAN"],
    "NO_REF_TRF_PLKK" => $_POST["NO_REF_TRF_PLKK"]
  );

  $result1 = api_json_call($url1, $headers1, $data1);
  if ($result1->ret == "0"){
    $jdata = array();
    $jsondata["ret"] = "0";
    $jsondata["data"] = $result1->DATA;
    $jsondata["msg"] = $result1->msg;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = $result1->ret;
    $jsondata["msg"] = $result1->msg;
    echo json_encode($jsondata);
  }
}

else if ($ls_tipe == "act_geterror")
{
  $ls_kode_kantor = $KD_KANTOR;
  $ls_kode_user = $USER;
  $ls_tglakhirdisplay = $_POST['tglakhirdisplay'];
  $ls_tglawaldisplay = $_POST['tglawaldisplay'];
  $norekpenerima = $_POST['norekpenerima'];
  $kodetransfer = $_POST['kodetransfer'];
  $ls_page = $_POST["page"];
  $ls_page_item = $_POST["page_item"];
  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

  // CALL API
  $url1 = $wsIp ."/JSPN5043/DataGridMonitoringPLKK";
  $headers1 = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );
  $data1 = array(
    "chId"  => "SMILE",
    "reqId"  =>  $ls_kode_user,
    "KODE_KANTOR"  => $ls_kode_kantor,
    "TGL_AWAL" => $ls_tglawaldisplay,
    "TGL_AKHIR" => $ls_tglakhirdisplay,
    "PAGE" => (int)$ls_page,
    "NROWS" => (int)$ls_page_item,
    "C_COLNAME" => "NO_REKENING_PENERIMA",
    "C_COLVAL" => (string)$norekpenerima,
    "C_COLNAME2" => "KODE_TRANSFER",
    "C_COLVAL2" => (string)$kodetransfer,
  );



  $result1 = api_json_call($url1, $headers1, $data1);
  if ($result1->ret == "0"){
    $jdata = array();
    $jsondata["ret"] = "1";
    $jsondata["start"] = $start;
    $jsondata["end"] = $end;
    $jsondata["page"] = $ls_page;
    $jsondata["recordsTotal"] = $result1->TOTAL_REC;
    $jsondata["recordsFiltered"] = $result1->TOTAL_REC;
    $jsondata["pages"] = $result1->NUM_OF_PAGE;
    $jsondata["data"] = $result1->DATA;
    $jsondata["msg"] = $result1->msg;

    //filter
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";
    $jsondata["recordsTotal"] = "0";
    $jsondata["recordsFiltered"] = "0";
    $jsondata["pages"] = "0";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }
}

else if ($ls_tipe == "act_submitkoreksi")
{
  $ls_kode_kantor = $KD_KANTOR;
  $ls_kode_user = $USER;

  $kodetransfer = $_POST['kodetransfer'];
  $norekprev = $_POST['norekprev'];
  $kodetipepenerima = $_POST['kodetipepenerima'];
  $kdprg = $_POST['kdprg'];
  $namapenerima = $_POST['namapenerima'];
  $bankpenerima = $_POST['bankpenerima'];
  $norekpenerima = $_POST['norekpenerima'];
  $namarekpenerima = $_POST['namarekpenerima'];
  $kodebankrekpenerima = $_POST['kodebankpenerima'];
  $idbankrekpenerima = $_POST['idbankpenerima'];
  $noreftrfplkk = $_POST['noreftrfplkk'];

  // CALL API
  $url1 = $wsIp ."/JSPN5043/SubmitKoreksiPLKK";
  $headers1 = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );
  $data1 = array(
    "chId"  => "SMILE",
    "reqId"  =>  $ls_kode_user,
    // "KODE_TRANSFER" => $kodetransfer,
    // "NO_REKENING_PREV" =>  $norekprev,
    // "KODE_TIPE_PENERIMA" => $kodetipepenerima,
    // "KD_PRG" => $kdprg,
    // "NAMA_PENERIMA" => $namapenerima,
    "BANK_PENERIMA" =>  $bankpenerima,
    "NO_REKENING_PENERIMA" => $norekpenerima,
    "NAMA_REKENING_PENERIMA" => $namarekpenerima,
    "KODE_BANK_PENERIMA" => $kodebankpenerima,
    // "ID_BANK_PENERIMA" => $idbankpenerima,
    "NO_REF_TRF_PLKK" => $noreftrfplkk
  );

  $result1 = api_json_call($url1, $headers1, $data1);
  if ($result1->ret == "0"){
    $jdata = array();
    $jsondata["ret"] = "0";
    $jsondata["data"] = $result1->DATA;
    $jsondata["msg"] = $result1->msg;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = $result1->ret;
    $jsondata["msg"] = $result1->msg;
    echo json_encode($jsondata);
  }
}

?>