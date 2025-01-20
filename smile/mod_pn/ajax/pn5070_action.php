<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser, $gs_DBPass, $gs_DBName);
//update 19/11/2019 - peralihan query ke webservices ---------------------------
$chId              = "SMILE";
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_role    = $_SESSION['regrole'];
$gs_kode_user    = $_SESSION["USER"];
$ls_type          = $_POST["tipe"];

//get data grid ----------------------------------------------------------------
if ($ls_type == "MainDataGrid") {
  function handleError($errno, $errstr, $error_file, $error_line)
  {
    if ($errno == 2) {
      $errorMsg = $errstr;
      if (strpos($errstr, 'failed to open stream:') !== false) {
        $errorMsg = 'Terdapat masalah dengan koneksi WebService.';
      } elseif (strpos($errstr, 'oci_connect') !== false) {
        $errorMsg = 'Terdapat masalah dengan koneksi database.';
      } else {
        $errorMsg = $errstr;
      }
      echo '{
                  "success":false,
                  "msg":"<b>Error:</b> ' . $errorMsg . '"
              }';
      die();
    }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition = "";

  //get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by    = $_POST["search_by"];
  $ls_search_txt    = $_POST["search_txt"];
  $ls_search_by2    = $_POST["search_by2"];
  $ls_search_txt2a = $_POST["search_txt2a"];
  $ls_search_txt2b = $_POST["search_txt2b"];
  $ls_search_txt2c = $_POST["search_txt2c"];
  $ls_search_txt2d = $_POST["search_txt2d"];
  $ls_order_by      = $_POST["order_by"];
  $ls_order_type    = $_POST["order_type"];

  $ls_page          = $_POST["page"];
  $ls_page_item    = $_POST["page_item"];

  $ln_page          = is_numeric($ls_page) ? $ls_page : "1";
  $ln_length        = is_numeric($ls_page_item) ? $ls_page_item : "10";

  $ln_start        = (($ln_page - 1) * $ln_length) + 1;
  $ln_end          = $ln_start + $ln_length - 1;

  //penanganan untuk filter data -----------------------------------------------				
  $ls_colname  = "";
  $ls_colval    = "";

  if ($ls_search_by != '') {
    if (($ls_search_txt != '') && ($ls_search_txt != 'null')) {
      $ls_colname = $ls_search_by;
      $ls_colval = $ls_search_txt;
    }
  }

  if ($ls_search_by2 != '') {
    if (($ls_search_txt2a != '') && ($ls_search_txt2a != 'null')) {
      $ls_colname2 = $ls_search_by2;
      $ls_colval2  = $ls_search_txt2a;
    } else {
      if (($ls_search_txt2b != '') && ($ls_search_txt2b != 'null')) {
        $ls_colname2 = $ls_search_by2;
        $ls_colval2  = $ls_search_txt2b;
      } else {
        if (($ls_search_txt2c != '') && ($ls_search_txt2c != 'null')) {
          $ls_colname2 = $ls_search_by2;
          $ls_colval2  = $ls_search_txt2c;
        } else {
          if (($ls_search_txt2d != '') && ($ls_search_txt2d != 'null')) {
            $ls_colname2 = $ls_search_by2;
            $ls_colval2  = $ls_search_txt2d;
          } else {
            $ls_colname2 = "";
            $ls_colval2  = "";
          }
        }
      }
    }
  }

  //get data from WS -----------------------------------------------------------
  global $wsIp;
  $ipDev  = $wsIp . "/JSPN5050/DataGridApproval";
  $url    = $ipDev;

  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type' => 'application/json',
    'Accept' => 'application/json',
    'X-Forwarded-For' => $ipfwd,
  );

  // set POST params -----------------------------------------------------------
  $data = array(
    'chId'         => $chId,
    'reqId'         => $gs_kode_user,
    'KODE_KANTOR'   => $gs_kantor_aktif,
    'PAGE'         => (int)$ln_page,
    'NROWS'         => (int)$ln_length,
    'C_COLNAME'     => $ls_colname,
    'C_COLVAL'     => $ls_colval,
    'C_COLNAME2'   => $ls_colname2,
    'C_COLVAL2'     => $ls_colval2,
    'O_COLNAME'     => $ls_order_by,
    'O_MODE'       => $ls_order_type
  );

  // Open connection -----------------------------------------------------------
  $ch = curl_init();

  // Set the url, number of POST vars, POST data -------------------------------
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  // Execute post --------------------------------------------------------------
  $result = curl_exec($ch);
  $resultArray = json_decode(utf8_encode($result));

  for ($i = 0; $i < ExtendedFunction::count($resultArray->DATA); $i++) {
    $resultArray->DATA[$i]->KODE_KONFIRMASI = $resultArray->DATA[$i]->KODE_KLAIM . "-" . $resultArray->DATA[$i]->NO_KONFIRMASI;
    $resultArray->DATA[$i]->ACTION = '<input type="hidden" name="KODE[' . $i . ']" id="KODE_' . $i . '" value="' . $resultArray->DATA[$i]->KODE_KONFIRMASI . '"><a href="#" onClick="doGridTask(\'edit\',\'' . $resultArray->DATA[$i]->KODE_KLAIM . '\',\'' . $resultArray->DATA[$i]->NO_KONFIRMASI . '\');"><img alt="img" src="../../images/user_go.png" border="0" alt="Approval Konfirmasi JP Berkala" align="absmiddle" /><span style="color:#009999"> Persetujuan</span></a>';
    //$resultArray->DATA[$i]->KODE_KONFIRMASI_DISPLAY = '<a href="#" onClick="doGridView(\'view\',\''.$resultArray->DATA[$i]->KODE_KLAIM.'\',\''.$resultArray->DATA[$i]->NO_KONFIRMASI.'\');"><span style="color:#009999">'.$resultArray->DATA[$i]->KODE_KONFIRMASI.'</span></a>';																																										
  }

  if ($resultArray->TOTAL_REC == 0) {
    $jsondata["ret"] = "-1";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";
    $jsondata["recordsTotal"] = "0";
    $jsondata["recordsFiltered"] = "0";
    $jsondata["pages"] = "0";
    $jsondata["data"] = "";
    $jsondata["msg"] = "Data tidak ditemukan..";
    echo json_encode($jsondata);
  } else {
    if ($ln_length > 0) {
      $ln_pages = ceil($resultArray->TOTAL_REC / $ln_length);
    } else {
      $ln_pages = 0;
    }

    $jsondata["ret"] = "1";
    $jsondata["start"] = $ln_start;
    $jsondata["end"] = $ln_end;
    $jsondata["page"] = $ln_page;
    $jsondata["recordsTotal"] = $resultArray->TOTAL_REC;
    $jsondata["recordsFiltered"] = $resultArray->TOTAL_REC;
    $jsondata["pages"] = $ln_pages;
    $jsondata["data"] = $resultArray->DATA;
    $jsondata["msg"] = "Sukses";
    echo json_encode($jsondata);
  }
}
//end get data grid ------------------------------------------------------------

//get data konfirmasi jp berkala -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatakonfirmasijpberkala") {
  $ls_kode_klaim     = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi = $_POST["v_no_konfirmasi"];

  //panggil ws utk get data konfirmasi jp berkala ------------------------------
  if ($ls_kode_klaim != "" && $ln_no_konfirmasi != "") {
    global $wsIp;
    $ipDev  = $wsIp . "/JSPN5050/ViewDataKonfirmasiBerkala";
    $url    = $ipDev;

    // set HTTP header -----------------------------------------------------
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    // set POST params -----------------------------------------------------
    $data = array(
      'chId' => $chId,
      'reqId' => $gs_kode_user,
      'KODE_KLAIM' => $ls_kode_klaim,
      'NO_KONFIRMASI' => (int)$ln_no_konfirmasi
    );

    // Open connection -----------------------------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data -------------------------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post --------------------------------------------------------
    $result_DtKonf = curl_exec($ch);
    $resultArray_DtKonf = json_decode(utf8_encode($result_DtKonf));

    if ($resultArray_DtKonf->ret == '0') {
      //------------------- ambil ListPeriodeBerkalaRekap ----------------------		
      global $wsIp;
      $ipDev  = $wsIp . "/JSPN5050/ListPeriodeBerkalaRekap";
      $url    = $ipDev;

      // set HTTP header -----------------------------------------------------
      $headers = array(
        'Content-Type' => 'application/json',
        'X-Forwarded-For' => $ipfwd,
      );

      // set POST params -----------------------------------------------------
      $data = array(
        'chId' => $chId,
        'reqId' => $gs_kode_user,
        'KODE_KLAIM' => $ls_kode_klaim,
        'NO_KONFIRMASI' => (int)$ln_no_konfirmasi
      );

      // Open connection -----------------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data -------------------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post --------------------------------------------------------
      $result_BlnBkl = curl_exec($ch);
      $resultArray_BlnBkl = json_decode(utf8_encode($result_BlnBkl));
      //------------------- end ambil ListPeriodeBerkalaRekap ------------------

      //---------------- ambil Dokumen Kelengkapan Administrasi ----------------
      global $wsIp;
      $ipDev  = $wsIp . "/JSPN5050/ListDokumenBerkala";
      $url    = $ipDev;

      // set HTTP header -----------------------------------------------------
      $headers = array(
        'Content-Type' => 'application/json',
        'X-Forwarded-For' => $ipfwd,
      );

      // set POST params -----------------------------------------------------
      $data = array(
        'chId' => $chId,
        'reqId' => $gs_kode_user,
        'KODE_KLAIM' => $ls_kode_klaim,
        'NO_KONFIRMASI' => (int)$ln_no_konfirmasi
      );

      // Open connection -----------------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data -------------------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post --------------------------------------------------------
      $result_Dok = curl_exec($ch);
      $resultArray_Dok = json_decode(utf8_encode($result_Dok));
      //---------------- end ambil Dokumen Kelengkapan Administrasi ------------

      //return data ke UI ------------------------------------------------------
      $result_data_combine = array();
      $result_data_combine["dataKonf"]   = $resultArray_DtKonf->DATA;
      $result_data_combine["dataBlnBkl"] = $resultArray_BlnBkl->DATA;
      $result_data_combine["dataDok"]    = $resultArray_Dok->DATA;
      $result_data_combine["dataKontakDarurat"] = getKontakDarurat($resultArray_DtKonf->DATA->IND_KODE_PENERIMA_BERKALA, $ls_kode_klaim, $DB);

      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtKonf->ret;
      $result_data_final["msg"]  = $resultArray_DtKonf->msg;
      $result_data_final["data"] = $result_data_combine;

      echo json_encode($result_data_final);
    } else {
      $ls_mess = $resultArray_DtKonf->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
    }
  } else {
    $ls_mess = "Data Konfirmasi JP Berkala kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"' . $ls_mess . '"
    }';
  }
}
//end get data konfirmasi jp berkala -------------------------------------------

//------------------------------- BUTTON TASK ----------------------------------
//do approval konfirmasi jp berkala --------------------------------------------
else if ($ls_type == "fjq_ajax_val_approval_konfirmasi_berkala") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi = $_POST["v_no_konfirmasi"];

  if ($ls_kode_klaim != "" && $ln_no_konfirmasi != "") {
    global $wsIp;

    // set HTTP header
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    $url = $wsIp . '/JSPN5050/ApprovalKonfirmasiBerkala';

    $data = array(
      "chId" => $chId,
      "reqId" => $gs_kode_user,
      "KODE_KLAIM" => $ls_kode_klaim,
      "NO_KONFIRMASI" => (int)$ln_no_konfirmasi
    );

    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post
    $result = utf8_encode(curl_exec($ch));
    $resultArray = json_decode($result);

    if ($resultArray->ret == "0") {
      $ls_mess = "PERSETUJUAN DATA KONFIRMASI JP BERKALA BERHASIL, SESSION DILANJUTKAN..";
      echo  '{
        "ret":0,
        "success":true,
        "msg":"' . $ls_mess . '"
      }';
    } else {
      if ($resultArray->P_SUKSES == "-1" || $resultArray->P_SUKSES == "-2") {
        $ls_mess = $resultArray->P_MESS;
      } else {
        $ls_mess = $resultArray->msg;
      }
      echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
    }
  } else {
    $ls_mess = "PERSETUJUAN DATA KONFIRMASI JP BERKALA GAGAL, KODE KLAIM ATAU NO_KONFIRMASI KOSONG, HARAP DICOBA KEMBALI..";
    echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
  }
}
//end do approval konfirmasi jp berkala ------------------------------------------

//do batal konfirmasi jp berkala -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_batal_konfirmasi_berkala") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi = $_POST["v_no_konfirmasi"];

  if ($ls_kode_klaim != "" && $ln_no_konfirmasi != "") {
    global $wsIp;

    // set HTTP header
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    $url = $wsIp . '/JSPN5050/BatalKonfirmasiBerkala';

    $data = array(
      "chId" => $chId,
      "reqId" => $gs_kode_user,
      "KODE_KLAIM" => $ls_kode_klaim,
      "NO_KONFIRMASI" => (int)$ln_no_konfirmasi
    );

    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post
    $result = utf8_encode(curl_exec($ch));
    $resultArray = json_decode($result);

    if ($resultArray->ret == "0") {
      $ls_mess = "PEMBATALAN DATA KONFIRMASI JP BERKALA BERHASIL, SESSION DILANJUTKAN..";
      echo  '{
        "ret":0,
        "success":true,
        "msg":"' . $ls_mess . '"
      }';
    } else {
      if ($resultArray->P_SUKSES == "-1" || $resultArray->P_SUKSES == "-2") {
        $ls_mess = $resultArray->P_MESS;
      } else {
        $ls_mess = $resultArray->msg;
      }
      echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
    }
  } else {
    $ls_mess = "PEMBATALAN DATA KONFIRMASI JP BERKALA GAGAL, KODE KLAIM ATAU NO_KONFIRMASI KOSONG, HARAP DICOBA KEMBALI..";
    echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
  }
}
//end do batal konfirmasi jp berkala -------------------------------------------

//do tolak konfirmasi jp berkala -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_tolak_konfirmasi_berkala") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
  $ls_alasan_penolakan = $_POST["v_alasan_penolakan"];

  if ($ls_kode_klaim != "" && $ln_no_konfirmasi != "") {
    global $wsIp;

    // set HTTP header
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    $url = $wsIp . '/JSPN5050/TolakKonfirmasiBerkala';

    $data = array(
      "chId" => $chId,
      "reqId" => $gs_kode_user,
      "KODE_KLAIM" => $ls_kode_klaim,
      "NO_KONFIRMASI" => (int)$ln_no_konfirmasi,
      "ALASAN_PENOLAKAN" => $ls_alasan_penolakan
    );

    // Open connection
    $ch = curl_init();

    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post
    $result = utf8_encode(curl_exec($ch));
    $resultArray = json_decode($result);

    if ($resultArray->ret == "0") {
      $ls_mess = "PENOLAKAN DATA KONFIRMASI JP BERKALA BERHASIL, SESSION DILANJUTKAN..";
      echo  '{
        "ret":0,
        "success":true,
        "msg":"' . $ls_mess . '"
      }';
    } else {
      if ($resultArray->P_SUKSES == "-1" || $resultArray->P_SUKSES == "-2") {
        $ls_mess = $resultArray->P_MESS;
      } else {
        $ls_mess = $resultArray->msg;
      }
      echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
    }
  } else {
    $ls_mess = "PENOLAKAN DATA KONFIRMASI JP BERKALA GAGAL, KODE KLAIM ATAU NO_KONFIRMASI KOSONG, HARAP DICOBA KEMBALI..";
    echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
  }
}
function getKontakDarurat($penerimaBerkala, $kodeKlaim, $DB)
{
  $data = [];
  if ($kodeKlaim) {
    $sql = "select NAMA_KONTAK_DARURAT, NO_HP_KONTAK_DARURAT, ALAMAT_KONTAK_DARURAT, KODE_HUBUNGAN_KONTAK_DARURAT FROM PN.PN_KLAIM_PENERIMA_BERKALA WHERE KODE_PENERIMA_BERKALA = '" . $penerimaBerkala  . "' AND KODE_KLAIM = '$kodeKlaim' ";

    $DB->parse($sql);
    $DB->execute();
    $data = $DB->nextrow();
  }

  return $data;
}
//end do tolak konfirmasi jp berkala -------------------------------------------

//---------------------------- END BUTTON TASK ---------------------------------
