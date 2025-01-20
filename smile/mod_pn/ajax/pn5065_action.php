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
  $ld_tgl_awal     = $_POST["tgl_awal"];
  $ld_tgl_akhir     = $_POST["tgl_akhir"];

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
  $ipDev  = $wsIp . "/JSPN5050/DataGrid";
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
    'TGL_AWAL'     => $ld_tgl_awal,
    'TGL_AKHIR'     => $ld_tgl_akhir,
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
    $resultArray->DATA[$i]->KODE_KONFIRMASI_DISPLAY = '<a href="#" onClick="doGridView(\'view\',\'' . $resultArray->DATA[$i]->KODE_KLAIM . '\',\'' . $resultArray->DATA[$i]->NO_KONFIRMASI . '\');"><span style="color:#009999">' . $resultArray->DATA[$i]->KODE_KONFIRMASI . '</span></a>';
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

//get data post lov penetapan --------------------------------------------------
else if ($ls_type == "fjq_ajax_val_postlovpenetapan") {
  $ls_kode_klaim            = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi_induk = $_POST["v_no_konfirmasi_induk"];

  //panggil ws utk get data konfirmasi jp berkala ------------------------------
  if ($ls_kode_klaim != "" && $ln_no_konfirmasi_induk != "") {
    global $wsIp;
    $ipDev  = $wsIp . "/JSPN5050/GetDataKonfirmasiInduk";
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
      'NO_KONFIRMASI' => (int)$ln_no_konfirmasi_induk
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
    $result_NoKonfInduk = curl_exec($ch);
    $resultArray_NoKonfInduk = json_decode(utf8_encode($result_NoKonfInduk));

    if ($resultArray_NoKonfInduk->ret == '0') {
      //--------------- ambil data pembayaran jp berkala induk -----------------
      $ipDev  = "";
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
        'NO_KONFIRMASI' => (int)$ln_no_konfirmasi_induk
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
      $result_BlnBklInduk = curl_exec($ch);
      $resultArray_BlnBklInduk = json_decode(utf8_encode($result_BlnBklInduk));
      //--------------- end ambil data pembayaran jp berkala induk -------------

      //get list Kondisi Terakhir ----------------------------------------------
      $ipDev    = "";
      global $wsIp;
      $ipDev    = $wsIp . "/JSPN5040/LovKodeKondisiAkhir";
      $url      = $ipDev;

      // set HTTP header ------------------------------------------------
      $headers = array(
        'Content-Type' => 'application/json',
        'X-Forwarded-For' => $ipfwd,
      );

      // set POST params ------------------------------------------------
      $data = array(
        'chId'        => $chId,
        'reqId'        => $gs_kode_user,
        'KODE_TIPE_KLAIM'  => "JPN01",
        'PAGE'        => 1,
        'NROWS'        => 10000,
        'C_COLNAME'    => "",
        'C_COLVAL'    => "",
        'C_COLNAME2'  => "",
        'C_COLVAL2'    => "",
        'O_COLNAME'    => "NO_URUT",
        'O_MODE'      => "ASC"
      );

      // Open connection ------------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data --------------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post ----------------------------------------------------
      $result_LovAWrKondisiAkhir = curl_exec($ch);
      $resultArray_LovAWrKondisiAkhir = json_decode(utf8_encode($result_LovAWrKondisiAkhir));
      //end get list Kondisi Terakhir ------------------------------------------

      //return data ke UI ------------------------------------------------------
      $result_data_combine = array();
      $result_data_combine["dataKonfInduk"]   = $resultArray_NoKonfInduk->DATA;
      $result_data_combine["dataBlnBklInduk"] = $resultArray_BlnBklInduk->DATA;
      $result_data_combine["dataListKondisiAkhir"] = $resultArray_LovAWrKondisiAkhir->DATA;

      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_NoKonfInduk->ret;
      $result_data_final["msg"]  = $resultArray_NoKonfInduk->msg;
      $result_data_final["data"] = $result_data_combine;

      echo json_encode($result_data_final);
    } else {
      $ls_mess = $resultArray_NoKonfInduk->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
    }
  } else {
    $ls_mess = "Data Klaim JP Berkala yang akan dilakukan konfirmasi ulang tidak boleh kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"' . $ls_mess . '"
    }';
  }
}
//end get data post lov penetapan ----------------------------------------------

//get data list ahli waris jp ---------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatalistahliwarisjp") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];

  //panggil ws utk get data konfirmasi jp berkala ------------------------------
  if ($ls_kode_klaim != "") {
    //ambil penetapan ahli waris jp --------------------------------
    $ipDev    = "";
    global $wsIp;
    $ipDev    = $wsIp . "/JSPN5040/ListDataAhliWarisJP";
    $url      = $ipDev;

    // set HTTP header ---------------------------------------------
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    // set POST params ---------------------------------------------
    $data = array(
      'chId'        => $chId,
      'reqId'        => $gs_kode_user,
      'KODE_KLAIM'  => $ls_kode_klaim
    );

    // Open connection ---------------------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data -----------------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post ------------------------------------------------
    $result_ListJpnAhliwaris = curl_exec($ch);
    $resultArray_ListJpnAhliwaris = json_decode(utf8_encode($result_ListJpnAhliwaris));
    //end ambil penetapan ahli waris jp ----------------------------		

    if ($resultArray_ListJpnAhliwaris->ret == '0') {
      //return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_ListJpnAhliwaris->ret;
      $result_data_final["msg"]  = $resultArray_ListJpnAhliwaris->msg;
      $result_data_final["data"] = $resultArray_ListJpnAhliwaris;

      echo json_encode($result_data_final);
    } else {
      $ls_mess = $resultArray_ListJpnAhliwaris->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
    }
  } else {
    $ls_mess = "Kode Klaim tidak boleh kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"' . $ls_mess . '"
    }';
  }
}
//end get data list ahli waris jp ----------------------------------------------

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

      //----------------------- flag aktif layanan sms -------------------------
      global $wsIp;
      $ipDev  = $wsIp . "/MS1001/LovMsLookup";
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
        'TIPE' => "KLMLYNSMS",
        'PAGE' => 1,
        'NROWS' => 10,
        'C_COLNAME' => "KODE",
        'C_COLVAL' => "FLAGAKTIF",
        'C_COLNAME2' => "",
        'C_COLVAL2' => "",
        'O_COLNAME' => "SEQ",
        'O_MODE' => "ASC"
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
      $result_FlagLynSms = curl_exec($ch);
      $resultArray_FlagLynSms = json_decode(utf8_encode($result_FlagLynSms));
      //----------------------- end flag aktif layanan sms ---------------------

      //return data ke UI ------------------------------------------------------
      $result_data_combine = array();
      $result_data_combine["dataKonf"]   = $resultArray_DtKonf->DATA;
      $result_data_combine["dataBlnBkl"] = $resultArray_BlnBkl->DATA;
      $result_data_combine["dataDok"]    = $resultArray_Dok->DATA;
      $result_data_combine["dataFlagLynSms"]    = $resultArray_FlagLynSms->DATA;
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
//do simpan perubahan data konfirmasi berkala ----------------------------------
else if ($ls_type == "fjq_ajax_val_save_konfirmasi_berkala") {
  $ls_kode_klaim                   = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi                = $_POST["v_no_konfirmasi"];
  $ln_no_urut_keluarga            = $_POST["v_no_urut_keluarga"];
  $ls_nomor_identitas              = str_replace("'", "''", $_POST["v_nomor_identitas"]);
  $ls_alamat                      = str_replace("'", "''", $_POST["v_alamat"]);
  $ls_rt                          = $_POST["v_rt"];
  $ls_rw                          = $_POST["v_rw"];
  $ls_kode_pos                    = $_POST["v_kode_pos"];
  $ls_kode_kelurahan              = $_POST["v_kode_kelurahan"];
  $ls_kode_kecamatan              = $_POST["v_kode_kecamatan"];
  $ls_kode_kabupaten              = $_POST["v_kode_kabupaten"];
  $ls_telepon_area                = $_POST["v_telepon_area"];
  $ls_telepon                      = $_POST["v_telepon"];
  $ls_telepon_ext                  = $_POST["v_telepon_ext"];
  $ls_handphone                    = $_POST["v_handphone"];
  $ls_email                        = str_replace("'", "''", $_POST["v_email"]);
  $ls_npwp                        = $_POST["v_npwp"];
  $ls_nomor_identitas              = $_POST["v_nomor_identitas"];
  $ls_nama_penerima                = str_replace("'", "''", $_POST["v_nama_penerima"]);
  $ls_nama_bank_penerima          = str_replace("'", "''", $_POST["v_nama_bank_penerima"]);
  $ls_kode_bank_penerima          = $_POST["v_kode_bank_penerima"];
  $ls_id_bank_penerima            = $_POST["v_id_bank_penerima"];
  $ls_no_rekening_penerima        = $_POST["v_no_rekening_penerima"];
  $ls_nama_rekening_penerima      = str_replace("'", "''", $_POST["v_nama_rekening_penerima"]);
  $ls_st_valid_rekening_penerima  = $_POST["v_st_valid_rekening_penerima"];
  $ls_kode_bank_pembayar          = $_POST["v_kode_bank_pembayar"];
  $ls_status_rekening_sentral      = $_POST["v_status_rekening_sentral"];
  $ls_kantor_rekening_sentral      = $_POST["v_kantor_rekening_sentral"];
  $ls_metode_transfer               = $_POST["v_metode_transfer"];

  $ls_inkd_nama_lengkap           = $_POST["v_inkd_nama_lengkap"];
  $ls_inkd_no_hp                  = $_POST["v_inkd_no_hp"];
  $ls_inkd_alamat                 = $_POST["v_inkd_alamat"];
  $ls_inkd_hub_keluarga           = $_POST["v_inkd_hub_keluarga"];
  $ls_ind_kode_penerima_berkala   = $_POST["v_ind_kode_penerima_berkala"];




  $sql = "UPDATE  PN.PN_KLAIM_PENERIMA_BERKALA SET NAMA_KONTAK_DARURAT = '" . $ls_inkd_nama_lengkap . "', NO_HP_KONTAK_DARURAT = '" . $ls_inkd_no_hp . "', ALAMAT_KONTAK_DARURAT = '" . $ls_inkd_alamat . "', KODE_HUBUNGAN_KONTAK_DARURAT = '" . $ls_inkd_hub_keluarga . "' WHERE KODE_PENERIMA_BERKALA = '" . $ls_ind_kode_penerima_berkala  . "' AND KODE_KLAIM = '" . $ls_kode_klaim . "'";
  $DB->parse($sql);
  $DB->execute();

  global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type' => 'application/json',
    'X-Forwarded-For' => $ipfwd,
  );

  $url = $wsIp . '/JSPN5050/UpdateKonfirmasiBerkala';

  $dataKlaim = array(
    array(
      "KODE_KLAIM"                        => $ls_kode_klaim,
      "NO_URUT_KELUARGA"                  => (int)$ln_no_urut_keluarga,
      "KODE_HUBUNGAN"                      => "",
      "NAMA_LENGKAP"                      => "",
      "NO_KARTU_KELUARGA"                  => "",
      "NOMOR_IDENTITAS"                    => $ls_nomor_identitas,
      "TEMPAT_LAHIR"                      => "",
      "TGL_LAHIR"                          => "",
      "JENIS_KELAMIN"                      => "",
      "GOLONGAN_DARAH"                    => "",
      "STATUS_KAWIN"                      => "",
      "ALAMAT"                            => $ls_alamat,
      "RT"                                => $ls_rt,
      "RW"                                => $ls_rw,
      "KODE_KELURAHAN"                    => (int)$ls_kode_kelurahan,
      "KODE_KECAMATAN"                    => (int)$ls_kode_kecamatan,
      "KODE_KABUPATEN"                    => (int)$ls_kode_kabupaten,
      "KODE_POS"                          => $ls_kode_pos,
      "TELEPON_AREA"                      => $ls_telepon_area,
      "TELEPON"                            => $ls_telepon,
      "TELEPON_EXT"                        => $ls_telepon_ext,
      "FAX_AREA"                          => "",
      "FAX"                                => "",
      "HANDPHONE"                          => $ls_handphone,
      "EMAIL"                              => $ls_email,
      "NPWP"                              => $ls_npwp,
      "NAMA_PENERIMA"                      => $ls_nama_penerima,
      "BANK_PENERIMA"                      => $ls_nama_bank_penerima,
      "NO_REKENING_PENERIMA"              => $ls_no_rekening_penerima,
      "NAMA_REKENING_PENERIMA"            => $ls_nama_rekening_penerima,
      "KODE_BANK_PEMBAYAR"                => $ls_kode_bank_pembayar,
      "KPJ_TERTANGGUNG"                    => "",
      "PEKERJAAN"                          => "",
      "KODE_KONDISI_TERAKHIR"              => "",
      "TGL_KONDISI_TERAKHIR"              => "",
      "STATUS_LAYAK"                      => "",
      "KODE_PENERIMA_BERKALA"              => "",
      "KETERANGAN"                        => "",
      "JENIS_IDENTITAS"                    => "",
      "STATUS_VALID_IDENTITAS"            => "",
      "KODE_BANK_PENERIMA"                => $ls_kode_bank_penerima,
      "ID_BANK_PENERIMA"                  => $ls_id_bank_penerima,
      "STATUS_VALID_REKENING_PENERIMA"  => $ls_st_valid_rekening_penerima,
      "STATUS_REKENING_SENTRAL"            => $ls_status_rekening_sentral,
      "KANTOR_REKENING_SENTRAL"            => $ls_kantor_rekening_sentral,
      "METODE_TRANSFER"                    => $ls_metode_transfer,
      "KODE_NEGARA"                        => "",
      "IS_VERIFIED_HP"                    => "",
      "TGL_VERIFIED_HP"                    => "",
      "IS_VERIFIED_EMAIL"                  => "",
      "TGL_VERIFIED_EMAIL"                => "",
      "PETUGAS_VERIFIED_HP"                => "",
      "STATUS_REG_NOTIFIKASI"              => "",
      "TGL_REG_NOTIFIKASI"                => "",
      "PETUGAS_REG_NOTIFIKASI"            => "",
      "PATH_FILE_DOK_NOTIF"                => "",
      "KODE_OTP_SMS"                      => "",
      "KODE_OTP_SMS_VERIFIED"              => "",
      "STATUS_CEK_LAYANAN"                => "",
      "KODE_OTP_EMAIL"                    => "",
      "KODE_OTP_EMAIL_VERIFIED"            => "",
      "PETUGAS_VERIFIED_EMAIL"            => ""
    )
  );

  $data = array(
    "chId"  => $chId,
    "reqId" => $USER,
    "dataKlaim" => $dataKlaim
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

  if ($resultArray->ret == '0') {
    $ls_mess = "SIMPAN PERUBAHAN DATA KONFIRMASI JP BERKALA BERHASIL, SESSION DILANJUTKAN..";
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
}
//end do simpan perubahan data konfirmasi berkala ------------------------------

//do submit konfirmasi jp berkala ----------------------------------------------
else if ($ls_type == "fjq_ajax_val_submit_konfirmasi_berkala") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi = $_POST["v_no_konfirmasi"];

  if ($ls_kode_klaim != "" && $ln_no_konfirmasi != "") {
    global $wsIp;
    $USER = $_SESSION["USER"];

    // set HTTP header
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    $url = $wsIp . '/JSPN5050/SubmitKonfirmasiBerkala';

    $data = array(
      "chId"  => "CORE",
      "reqId" => $USER,
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
      $ls_mess = "SUBMIT DATA KONFIRMASI JP BERKALA BERHASIL, SESSION DILANJUTKAN..";
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
    $ls_mess = "SUBMIT DATA KONFIRMASI JP BERKALA GAGAL, KODE KLAIM ATAU NO_KONFIRMASI KOSONG, HARAP DICOBA KEMBALI..";
    echo '{
          "ret":-1,
          "success":false,
          "msg":"' . $ls_mess . '"
      }';
  }
}
//end do submit konfirmasi jp berkala ------------------------------------------

//do batal konfirmasi jp berkala -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_batal_konfirmasi_berkala") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi = $_POST["v_no_konfirmasi"];

  if ($ls_kode_klaim != "" && $ln_no_konfirmasi != "") {
    global $wsIp;
    $USER = $_SESSION["USER"];

    // set HTTP header
    $headers = array(
      'Content-Type' => 'application/json',
      'X-Forwarded-For' => $ipfwd,
    );

    $url = $wsIp . '/JSPN5050/BatalKonfirmasiBerkala';

    $data = array(
      "chId"  => "CORE",
      "reqId" => $USER,
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

else if ($ls_type == 'hapus_qrcode') {
  $filepath = $_POST['FILE'];
  $path     = '../../temp_qrcode/';
  if (!unlink($path . $filepath)) {
    echo ("Error deleting qrcode $filepath");
  } else {
    echo ("Deleted qrcode $filepath");
  }
}

//do insert antrian ----------------------------------------------
else if ($ls_type == "fjq_ajax_insert_antrian") {
  $ls_kode_klaim = $_POST["v_kode_klaim"];
  // Parameter Antrian---------------------------------------------------------------------\
  $ls_token_sisla          = $_POST["v_token_antrian"];
  $ls_kode_jenis_antrian  = $_POST["v_kode_jenis_antrian"];
  $ls_kode_status_antrian  = $_POST["v_kode_status_antrian"];
  $ls_kode_sisla          = $_POST["v_kode_sisla"];
  $ls_kode_kantor_antrian  = $_POST["v_kode_kantor_antrian"];
  $ls_nomor_antrian        = $_POST["v_nomor_antrian"];
  $ld_tgl_ambil_antrian    = $_POST["v_tgl_ambil_antrian"];
  $ld_tgl_panggil_antrian  = $_POST["v_tgl_panggil_antrian"];
  $ls_kode_petugas_antrian = $_POST["v_kode_petugas_antrian"];
  $ls_nomor_identitas_antrian  = $_POST["v_nomor_identitas"];
  $ls_no_hp_antrian        = $_POST["v_no_hp_antrian"];
  $ls_email_antrian        = $_POST["v_email_antrian"];
  $ls_kode_jenis_antrian_detil = $_POST["v_kode_jenis_antrian_detil"];

  $ls_kode_antrian = "";
  $ls_kode_pointer_asal = "PN5006";
  $qry = "
	begin 
		pn.p_pn_antrian.x_insert_antrian
		(
			:p_kode_jenis_antrian        ,
			:p_kode_status_antrian       , 
			:p_token_antrian             ,
			:p_kode_sisla                ,
			:p_kode_kantor_antrian       ,
			:p_no_antrian                ,
			:p_tgl_ambil                 ,
			:p_tgl_panggil               ,
			:p_kode_petugas_antrian      ,
			:p_nomor_identitas           ,  
			:p_no_hp                     ,
			:p_email                     ,
			:p_kode_klaim                ,
			:p_kode_jenis_antrian_detil  ,
			:p_keterangan                , 
      :p_kode_pointer_asal         ,     
			:p_user                      , 
			:p_sukses                    , 
			:p_mess                      ,
			:p_kode_antrian               
		); 
	end;
	";
  $proc = $DB->parse($qry);
  oci_bind_by_name($proc, ":p_token_antrian", $ls_token_sisla, 500);
  oci_bind_by_name($proc, ":p_kode_jenis_antrian", $ls_kode_jenis_antrian, 10);
  oci_bind_by_name($proc, ":p_kode_sisla", $ls_kode_sisla, 100);
  oci_bind_by_name($proc, ":p_no_antrian", $ls_nomor_antrian, 30);
  oci_bind_by_name($proc, ":p_kode_kantor_antrian", $ls_kode_kantor_antrian, 10);
  oci_bind_by_name($proc, ":p_tgl_ambil", $ld_tgl_ambil_antrian, 50);
  oci_bind_by_name($proc, ":p_tgl_panggil", $ld_tgl_panggil_antrian, 50);
  oci_bind_by_name($proc, ":p_kode_petugas_antrian", $ls_kode_petugas_antrian, 30);
  oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas_antrian, 20);
  oci_bind_by_name($proc, ":p_no_hp", $ls_no_hp_antrian, 20);
  oci_bind_by_name($proc, ":p_email", $ls_email_antrian, 50);
  oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 30);
  oci_bind_by_name($proc, ":p_kode_status_antrian", $ls_kode_status_antrian, 10);
  oci_bind_by_name($proc, ":p_kode_jenis_antrian_detil", $ls_kode_jenis_antrian_detil, 10);
  oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 1000);
  oci_bind_by_name($proc, ":p_kode_pointer_asal", $ls_kode_pointer_asal, 50);
  oci_bind_by_name($proc, ":p_user", $username, 1000);
  oci_bind_by_name($proc, ":p_sukses", $p_sukses, 2);
  oci_bind_by_name($proc, ":p_mess", $p_mess, 1000);
  oci_bind_by_name($proc, ":p_kode_antrian", $p_kode_antrian, 30);
  $DB->execute();
  $ls_sukses_antrian = $p_sukses;
  $ls_mess_antrian = $p_mess;
  $ls_kode_antrian = $p_kode_antrian;

  echo  '{
    "ret":0,
    "success":true,
    "msg":"' . $ls_mess_antrian . '",
    "kode_antrian" :"' . $ls_kode_antrian . '"
  }';
}
//end do insert antrian ------------------------------------------

//---------------------------- END BUTTON TASK ---------------------------------
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
