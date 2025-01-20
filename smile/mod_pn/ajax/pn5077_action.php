<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once __DIR__ . "../../logs.php";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];

$logs_pn5077 = new Logs();
$logs_pn5077->setFunctionName('mod_pn/ajax/pn5077_action');
$logs_pn5077->setEventName('PN5077 - Verifikasi Potensi Klaim Fiktif');
$logs_pn5077->start();

function api_json_call_get_pn5077($apiurl) {
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

function encrypt_decrypt_pn5077($action, $string)
{
    /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt_pn5077('encrypt', $string);
     * DECRYPTION: encrypt_decrypt_pn5077('decrypt', $string) ;
     */
    $output_pn5077 = false;
    $encrypt_method_pn5077 = "AES-256-CBC";
    $secret_key_pn5077 = 'WS-SERVICE-KEY';
    $secret_iv_pn5077 = 'WS-SERVICE-VALUE';
    // hash
    $key = hash('sha256', $secret_key_pn5077);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv_pn5077), 0, 16);
    if ($action == 'encrypt') {
        $output_pn5077 = base64_encode(openssl_encrypt($string, $encrypt_method_pn5077, $key, 0, $iv));
    } else {
        if ($action == 'decrypt') {
            $output_pn5077 = openssl_decrypt(base64_decode($string), $encrypt_method_pn5077, $key, 0, $iv);
        }
    }
    return $output_pn5077;
}

if (!function_exists('get_start_pn5077')) {
  /**
   * 
   * @param string $page
   * @param string $limit
   * 
   * @return number
   */
  function get_start_pn5077($page, $limit) {
      
    $start = ($page * $limit) - $limit;

    return $start + 1;
  }
}

if (!function_exists('clean_pn5077')) {
  /**
   * Trim whitespace, hapus tag php/html, hapus unicode NO-BREAK SPACE/nbsp (U+00a0),
   * convert special character jadi karakter biasa
   * 
   * @param string $string
   * @param bool   $stripTags
   * 
   * @return string
   */
  function clean_pn5077($string, $stripTags = true)
  {
    if ($string) {
      $res = htmlspecialchars(trim(preg_replace('/\xc2\xa0/', '', $string)));
      return $stripTags ? strip_tags($res) : $res;
    }
    return NULL;
  }
}

if (!function_exists('setSearch_pn5077')) {
  /**
   * 
   * 
   * @param string $q | inputan
   * @param string $key | keyword
   * @param string $status_proses
   * 
   * @return string query
   */
  function setSearch_pn5077($q, $key, $status_proses)
  {
    $tmp = NULL;
    if ($q) {
      $belum = ["KODE_FIKTIF_KLAIM","ID_POINTER_ASAL","TGL_REKAM_PENGAJUAN","PETUGAS_REKAM_PENGAJUAN","KODE_KANTOR_PENGAJUAN","TGL_SLA1_VERIFIKASI_KASUS","KODE_STATUS_PENGAJUAN"];
      $sudah = ["KODE_FIKTIF_KLAIM","ID_POINTER_ASAL","TGL_REKAM_PENGAJUAN","PETUGAS_REKAM_PENGAJUAN","KODE_KANTOR_PENGAJUAN","TGL_SLA1_VERIFIKASI_KASUS","TGL_VERIFIKASI_KASUS","KODE_STATUS_PENGAJUAN", "TGL_APPROVAL"];
      $kolom_tgl = ["TGL_REKAM_PENGAJUAN", "TGL_SLA1_VERIFIKASI_KASUS", "TGL_VERIFIKASI_KASUS", "TGL_APPROVAL"];
      $field = $status_proses == 'belum' ? $belum : $sudah;
      if (!empty($key) && in_array($key, $field)) {
        $q = (in_array($key, $kolom_tgl) ? str_replace('/', '-', $q) : $q);
        $tmp = "AND $key LIKE '%$q%'";
      } else {
        $tmp = "AND (";
        for ($i=0; $i < count($field) ; $i++) { 
          $f = $field[$i];
          if ($i == (count($field) - 1)) {
            $tmp .= "$f LIKE '%$q%'";
          } else {
            $tmp .= "$f LIKE '%$q%' OR ";
          }
        }
        $tmp .= ")";
      }
    }

    return $tmp;
  }
}


$ls_tipe  = $_POST["tipe"] ?? 'select';
$ls_status_proses   = $_POST["status_proses"] ?? NULL;
$comment_if_pn5077 = null;

$where_flag = ($ls_status_proses == 'belum' ? "AND KLM.STATUS_VERIFIKASI_KASUS = 'T'" : "AND KLM.STATUS_VERIFIKASI_KASUS != 'T'");
$order_flag = ($ls_status_proses == 'belum' ? "ORDER BY KLM.TGL_REKAM_PENGAJUAN ASC" : "ORDER BY KLM.TGL_VERIFIKASI_KASUS DESC");

# query live
$sql = "SELECT (KODE_KANTOR_PENGAJUAN || ' - ' || NAMA_KANTOR) KANTOR, (PETUGAS_REKAM_PENGAJUAN || ' - ' || NAMA_PETUGAS) PETUGAS, A.* FROM (SELECT KLM.KODE_FIKTIF_KLAIM, KLM.NOMOR_IDENTITAS_ANTRIAN, TO_CHAR( KLM.TGL_REKAM_PENGAJUAN, 'DD-MM-YYYY' ) TGL_REKAM_PENGAJUAN, KLM.PETUGAS_REKAM_PENGAJUAN, ( SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = KLM.PETUGAS_REKAM_PENGAJUAN ) NAMA_PETUGAS, KLM.KODE_KANTOR_PENGAJUAN, ( SELECT KTR.NAMA_KANTOR FROM MS.MS_KANTOR KTR WHERE KTR.KODE_KANTOR = KLM.KODE_KANTOR_PENGAJUAN ) NAMA_KANTOR, TO_CHAR( KLM.TGL_SLA1_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_SLA1_VERIFIKASI_KASUS, TO_CHAR( KLM.TGL_SLA2_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_SLA2_VERIFIKASI_KASUS, KLM.STATUS_APPROVAL, TO_CHAR( KLM.TGL_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_VERIFIKASI_KASUS, TO_CHAR( NVL(KLM.TGL_VERIFIKASI_KASUS,sysdate), 'YYYY-MM-DD' ) TGL_VERIFIKASI_KASUS_FORMAT, TO_CHAR( KLM.TGL_SLA1_VERIFIKASI_KASUS, 'YYYY-MM-DD' ) TGL_SLA1_VERIFIKASI_KASUS_FORMAT, TO_CHAR( KLM.TGL_APPROVAL, 'DD-MM-YYYY' ) TGL_APPROVAL, TO_CHAR( KLM.TGL_REKAM_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_REKAM_VERIFIKASI_KASUS, KLM.FLAG_PERPANJANGAN_SLA, KLM.KETERANGAN_VERIFIKASI, KLM.KODE_VERIFIKASI, KLM.FLAG_CEK_KASUS, KLM.KODE_STATUS_PENGAJUAN, (SELECT NAMA_PENGAJUAN FROM PN.PN_FIKTIF_KODE_PENGAJUAN FKP WHERE FKP.KODE_STATUS_PENGAJUAN = KLM.KODE_STATUS_PENGAJUAN AND ROWNUM <= 1 ) NAMA_STATUS_PENGAJUAN, KLM.PETUGAS_APPROVAL, (SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = KLM.PETUGAS_APPROVAL ) NAMA_PETUGAS_APPROVAL, KLM.KETERANGAN_APPROVAL, KLM.ID_POINTER_ASAL, KLM.KODE_POINTER_ASAL, KLM.PETUGAS_VERIFIKASI_KASUS, (SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = KLM.PETUGAS_VERIFIKASI_KASUS ) NAMA_PETUGAS_VERIFIKASI_KASUS, KLM.KODE_TIPE_PELAPOR, (SELECT TP.NAMA_TIPE_PENERIMA FROM PN.PN_KODE_TIPE_PENERIMA TP WHERE NVL(TP.STATUS_NONAKTIF,'X') = 'T' AND TP.KODE_TIPE_PENERIMA = KLM.KODE_TIPE_PELAPOR) STATUS_PELAPOR FROM PN.PN_FIKTIF_KLAIM KLM WHERE KLM.KODE_KANTOR_PENGAJUAN = '$KD_KANTOR' $where_flag $order_flag)A";

if ($ls_tipe == "select") {
  $comment_if_pn5077 .= '[if ($ls_tipe == "select")]';
  # get list data

  $ls_page            = $_POST["page"] ?? 1;
  $ls_page_item       = $_POST["page_item"] ?? 10;
  $ls_search_by       = isset($_POST["search_by"]) ? strtoupper($_POST["search_by"]) : NULL;
  $ls_search_txt      = isset($_POST["search_txt"]) ? strtoupper(clean_pn5077($_POST["search_txt"])) : NULL;
  
  $start = get_start_pn5077($ls_page, $ls_page_item); 
  $search = $ls_search_txt ? setSearch_pn5077($ls_search_txt, $ls_search_by, $ls_status_proses) : NULL;
  $limit = $ls_page * $ls_page_item;

  $sqlResult = "SELECT * FROM ( SELECT a.*, ROWNUM rnum FROM ($sql) a WHERE ROWNUM <= $limit) WHERE rnum >= $start"; 
  if ($search) {
    $sqlResult = "SELECT * FROM ( SELECT a.*, ROWNUM rnum FROM ($sql) a) WHERE KODE_FIKTIF_KLAIM IS NOT NULL $search";
  }
  
  $data = array();
  $DB->parse($sqlResult);
  if($DB->execute()){
    while($row = $DB->nextrow()) {
      array_push($data, $row);
    }
    $logs_pn5077->info(json_encode($sqlResult), json_encode($data), "List data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
  } else {
    $logs_pn5077->error(json_encode($sqlResult), json_encode($DB), "Gagal mendapatkan data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
  }

  $searchCount = str_replace('AND', 'WHERE', $search);
  $sqlCount = "SELECT * FROM ($sql) $searchCount";
  $count = array();
  $DB->parse($sqlCount);
  if($DB->execute()){
    while($row = $DB->nextrow()) {
      array_push($count, $row);
    }
    $logs_pn5077->info(json_encode($sqlCount), json_encode($count), "Menghitung jumlah data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
  } else {
    $logs_pn5077->error(json_encode($sqlCount), json_encode($DB), "Gagal mendapatkan menghitung jumlah data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
  }

  if ($data) {
    $return = array(
        'ret' => 0,
        'data' => $data,
        'msg' => 'Berhasil',
        'limit' => $limit ?? NULL,
        'start' => $start ?? NULL,
        'recordsTotal'=> count($count)
    );
  } else {
    $return = array(
        'ret' => -2,
        'data' => $data,
        'msg' => 'Data tidak ditemukan',
        'limit' => $limit ?? NULL,
        'start' => $start ?? NULL,
        'recordsTotal'=> count($count)
    );
  }
  echo json_encode($return);
  
} else if ($ls_tipe == "select_detail") {
  $comment_if_pn5077 .= '[if ($ls_tipe == "select_detail")]';
  # get data detail

  $ls_kode_fiktif_klaim      = isset($_POST['kode_fiktif_klaim']) ? encrypt_decrypt_pn5077('decrypt', $_POST['kode_fiktif_klaim']) : NULL;
  $sql = "SELECT * FROM ($sql) WHERE KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim'";

  $data = array();
  $DB->parse($sql);
  if($DB->execute()){ 
    $data = $DB->nextrow();
    $logs_pn5077->info(json_encode($sql), json_encode($data), "Detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
  } else {
    $logs_pn5077->error(json_encode($sql), json_encode($DB), "Gagal mendapatkan detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
  }

  if ($data) {
    $comment_if_pn5077 .= '[if ($data)]';
    $data['dataKpj'] = $data['dataKodeVerifikasi'] = $data['dataHubungan'] = $data['dataDokumen'] = array();
    $data['TGL_VERIFIKASI_KASUS'] = (empty($data['TGL_VERIFIKASI_KASUS']) ? date('d-m-Y') : $data['TGL_VERIFIKASI_KASUS']);
    // if ($ls_status_proses == 'belum') {
      if ($data['TGL_VERIFIKASI_KASUS_FORMAT'] > $data['TGL_SLA1_VERIFIKASI_KASUS_FORMAT']){
        $data['FLAG_PERPANJANGAN_SLA'] = 'Y';
      } else {
        $data['FLAG_PERPANJANGAN_SLA'] = 'T';
      }
    // }

    if (isset($data['KODE_FIKTIF_KLAIM']) && !empty($data['KODE_FIKTIF_KLAIM'])) {
      $comment_if_pn5077 .= '[if (isset($data["KODE_FIKTIF_KLAIM"]) && !empty($data["KODE_FIKTIF_KLAIM"]))]';
      $dataKpj = array();
      $sql_kpj = "SELECT KLMP.KODE_FIKTIF_KLAIM, KLMP.KODE_TK, KLMP.KPJ, KLMP.NAMA_LENGKAP NAMA_TK, KLMP.NOMOR_IDENTITAS NIK, TO_CHAR( KLMP.TGL_LAHIR, 'DD-MM-YYYY' ) TGL_LAHIR, KLMP.KODE_DIVISI, KLMP.KODE_PERUSAHAAN NPP, KLMP.NAMA_PERUSAHAAN FROM PN.PN_FIKTIF_KLAIM_KEPESERTAAN KLMP WHERE KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim' ORDER BY TGL_REKAM ASC";
      $DB->parse($sql_kpj);
      if($DB->execute()){
        while($row = $DB->nextrow()) {
          array_push($dataKpj, $row);
        }
        $logs_pn5077->info(json_encode($sql_kpj), json_encode($dataKpj), "Detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
      } else {
        $logs_pn5077->error(json_encode($sql_kpj), json_encode($DB), "Gagal mendapatkan detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if_pn5077");
      }
      $data['dataKpj'] = $dataKpj;
    }

    $sql_kode = "SELECT KODE_VERIFIKASI, NAMA_VERIFIKASI FROM PN.PN_FIKTIF_KODE_VERIFIKASI WHERE STATUS_NONAKTIF = 'T' ORDER BY NO_URUT";
    $DB->parse($sql_kode);
    $dataKodeVerifikasi = array();
    if($DB->execute()){
      while($row = $DB->nextrow()) {
        array_push($dataKodeVerifikasi, $row);
      }
      $logs_pn5077->info(json_encode($sql_kode), json_encode($dataKodeVerifikasi), "List data Kode verifikasi $ls_status_proses diproses : $comment_if_pn5077");
    } else {
      $logs_pn5077->error(json_encode($sql_kode), json_encode($DB), "Gagal mendapatkan List data Kode verifikasi $ls_status_proses diproses : $comment_if_pn5077");
    }
    $data['dataKodeVerifikasi'] = $dataKodeVerifikasi; 

    $sql_dokumen = "SELECT FKD.KODE_FIKTIF_KLAIM, FKD.KODE_DOKUMEN, FKD.NAMA_DOKUMEN, ( SELECT KD.NAMA_DOKUMEN FROM PN.PN_KODE_DOKUMEN KD WHERE KD.KODE_DOKUMEN = FKD.KODE_DOKUMEN ) NAMA_KODE_DOKUMEN, FKD.PATH_URL, TO_CHAR( FKD.TGL_UPLOAD, 'DD-MM-YYYY' ) TGL_UPLOAD, FKD.KODE_FUNGSI, (SELECT FG.INISIAL_FUNGSI FROM MS.SC_FUNGSI FG WHERE FG.KODE_FUNGSI = FKD.KODE_FUNGSI) SUMBER, FKD.KETERANGAN FROM PN.PN_FIKTIF_KLAIM_DOKUMEN FKD WHERE FKD.KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim' ORDER BY TGL_REKAM ASC";
    $DB->parse($sql_dokumen);
    $dataDokumen = array();
    if($DB->execute()){
      while($row = $DB->nextrow()) {
        array_push($dataDokumen, $row);
      }
      $logs_pn5077->info(json_encode($sql_dokumen), json_encode($dataDokumen), "List data dokumen $ls_status_proses diproses : $comment_if_pn5077");
    } else {
      $logs_pn5077->error(json_encode($sql_dokumen), json_encode($DB), "Gagal mendapatkan List data dokumen $ls_status_proses diproses : $comment_if_pn5077");
    }
    $data['dataDokumen'] = $dataDokumen;


    if (isset($data['ID_POINTER_ASAL']) && !empty($data['ID_POINTER_ASAL'])) {
      $id_pointer_asal = $data['ID_POINTER_ASAL'];
      $sql_lapak_asik = "SELECT KODE_BOOKING, JENIS_ANTRIAN, KODE_TIPE_PENERIMA, KODE_LAYANAN FROM ANTRIAN.ATR_BOOKING WHERE KODE_BOOKING='$id_pointer_asal' UNION ALL SELECT KODE_BOOKING, JENIS_ANTRIAN, KODE_TIPE_PENERIMA, KODE_LAYANAN FROM ANTRIAN.ATR_BOOKING_HIST WHERE KODE_BOOKING='$id_pointer_asal'";
      $ECDB->parse($sql_lapak_asik);
      $rowLapakAsik = array();
      if($ECDB->execute()){
        $rowLapakAsik = $ECDB->nextrow();
        $logs_pn5077->info(json_encode($sql_lapak_asik), json_encode($rowLapakAsik), "get data dari lapak asik : $comment_if_pn5077");
      } else {
        $logs_pn5077->error(json_encode($sql_lapak_asik), json_encode($DB), "Gagal get data dari lapak asik : $comment_if_pn5077");
      }
      $data = array_merge($data, ($rowLapakAsik ? $rowLapakAsik : array()) );
    }

  }

  if ($data) {
    $return = array(
        'ret' => 0,
        'data' => $data,
        'rowLapakAsik' => $rowLapakAsik,
        'msg' => 'Berhasil',
    );
  } else {
    $return = array(
        'ret' => -2,
        'data' => $data,
        'msg' => 'Data tidak ditemukan',
    );
  }
  echo json_encode($return);
  
} else if ($ls_tipe == "submitData") {
  $comment_if_pn5077 .= '[if ($ls_tipe == "submitData")]';
  # kirim data ke prosedure

  $kode_fiktif_klaim = isset($_POST['id']) ? encrypt_decrypt_pn5077('decrypt', $_POST['id']) : NULL;
  $flag_cek_kasus = (isset($_POST['pengecekan_kasus']) ? clean_pn5077($_POST['pengecekan_kasus']) : NULL);
  $tgl_mulai_cek_kasus = (isset($_POST['tgl_verifikasi_kasus']) ? $_POST['tgl_verifikasi_kasus'] : NULL);
  $kode_hasil_verifikasi = (isset($_POST['hasil_verifikasi_kasus']) ? clean_pn5077($_POST['hasil_verifikasi_kasus']) : NULL);
  $keterangan_verifikasi = (isset($_POST['keterangan_verifikasi_kasus']) ? clean_pn5077($_POST['keterangan_verifikasi_kasus']) : NULL);
  $petugas_koreksi = $USER;
  $sukses = $mess = NULL ;

  $sql = "BEGIN PN.P_PN_PN5077.X_VERIFIKASI_FIKTIF_KLAIM(:p_kode_fiktif_klaim,:p_flag_cek_kasus,to_date('{$tgl_mulai_cek_kasus}','dd/mm/yyyy'),:p_kode_hasil_verifikasi,:p_keterangan_verifikasi, :p_petugas_verifikasi,:p_sukses,:p_mess); END;";

  $proc = $DB->parse($sql);

  oci_bind_by_name($proc, ':p_kode_fiktif_klaim', $kode_fiktif_klaim, 100);
  oci_bind_by_name($proc, ':p_flag_cek_kasus', $flag_cek_kasus, 10);
  oci_bind_by_name($proc, ':p_kode_hasil_verifikasi', $kode_hasil_verifikasi, 30);
  oci_bind_by_name($proc, ':p_keterangan_verifikasi', $keterangan_verifikasi, 100);
  oci_bind_by_name($proc, ':p_petugas_verifikasi', $petugas_koreksi, 100);
  oci_bind_by_name($proc, ':p_sukses', $sukses, 10);
  oci_bind_by_name($proc, ':p_mess', $mess, 1000);

  if($DB->execute()) {
    if ($sukses == 1) {
      $comment_if_pn5077 .= '[if ($sukses == 1)]';
      $return = array(
          'ret' => $sukses,
          'msg' => 'Data Berhasil Disubmit',
          'msg' => 'Data Berhasil Disubmit',
          'kode_fiktif_klaim' => $kode_fiktif_klaim,
      );
      $logs_pn5077->info(json_encode($sql), json_encode($return), "Submit Verifikasi Potensi Klaim Fiktif menggunakan prosedure : $comment_if_pn5077");
    } else {
      $comment_if_pn5077 .= '[else]';
      http_response_code(400);
      $return = array(
        'ret' => $sukses,
        'msg' => 'Data Gagal Disubmit Dengan Error: '. $mess,
      );
      $logs_pn5077->error(json_encode($sql), json_encode($return), "Gagal submit Verifikasi Potensi Klaim Fiktif menggunakan prosedure : $comment_if_pn5077");
    }
  } else {
    http_response_code(400);
    $return = array(
      'ret' => $sukses,
      'msg' => 'Data Gagal Disubmit Dengan Error: '. $mess,
    );
    $logs_pn5077->error(json_encode($sql), json_encode($return), "Gagal submit Verifikasi Potensi Klaim Fiktif menggunakan prosedure : $comment_if_pn5077");
  }

  echo json_encode($return);
  
} else if ($ls_tipe == "status_pengajuan") {
  $comment_if_pn5077 .= '[if ($ls_tipe == "status_pengajuan")]';
  # get data untuk status pengajuan
  
  $sql_pengajuan = "SELECT KODE_STATUS_PENGAJUAN, NAMA_PENGAJUAN FROM PN.PN_FIKTIF_KODE_PENGAJUAN WHERE STATUS_NONAKTIF = 'T' ORDER BY NO_URUT";
  $DB->parse($sql_pengajuan);
  $dataKodePengajuan = array();
  if($DB->execute()){
    while($row = $DB->nextrow()) {
      array_push($dataKodePengajuan, $row);
    }
    $logs_pn5077->info(json_encode($sql_pengajuan), json_encode($dataKodePengajuan), "List data Kode pengajuan $ls_status_proses diproses : $comment_if_pn5077");
  } else {
    $logs_pn5077->error(json_encode($sql_pengajuan), json_encode($DB), "Gagal mendapatkan List data Kode pengajuan $ls_status_proses diproses : $comment_if_pn5077");
  }
  echo json_encode($dataKodePengajuan);
} else if ($ls_tipe == "downloadFileSmile") {
  $ls_path_url=$_POST['path_url'];
  $ls_path_url_encrypt = encrypt_decrypt_pn5077('encrypt',$ls_path_url);

  $jsondata["pathUrlEncrypt"] = $ls_path_url_encrypt;

  echo json_encode($jsondata);
  
} else if ($ls_tipe == "savedokumenlain"){
  $comment_if_pn5077 .= '[if ($ls_tipe == "savedokumenlain")]';
  $ls_kode_fiktif_klaim           = htmlspecialchars($_POST["kode_fiktif_klaim"]);
  $ls_nama_dokumen_lainnya        = htmlspecialchars($_POST["nama_dokumen_lainnya"]);
  $ls_keterangan_dokumen_lainnya  = htmlspecialchars($_POST["keterangan_dokumen_lainnya"]);
  $ls_path_url_dokumen_lainnya    = (isset($_POST["path_url_dokumen_lainnya"]) && !empty($_POST["path_url_dokumen_lainnya"]) ? $_POST["path_url_dokumen_lainnya"] : NULL);
  $mime_type_file_dokumen_lainnya = (isset($_POST["mime_type_file_dokumen_lainnya"]) && !empty($_POST["mime_type_file_dokumen_lainnya"]) ? $_POST["mime_type_file_dokumen_lainnya"] : NULL);

  $tipe_file = substr($ls_path_url_dokumen_lainnya,-3);
  
    
  $sql = "INSERT INTO PN.PN_FIKTIF_KLAIM_DOKUMEN (KODE_FIKTIF_KLAIM, KODE_DOKUMEN, MIME_TYPE, PATH_URL, TGL_UPLOAD, KETERANGAN, TGL_REKAM, PETUGAS_REKAM, KODE_FUNGSI, NAMA_DOKUMEN, FLAG_UPLOAD) VALUES ('$ls_kode_fiktif_klaim','D224','$mime_type_file_dokumen_lainnya','$ls_path_url_dokumen_lainnya',sysdate,'$ls_keterangan_dokumen_lainnya',sysdate,'$USER','$KODE_ROLE','$ls_nama_dokumen_lainnya','Y')";
  
  $jsondata = array();
  $DB->parse($sql); 
  if(!$DB->execute()){
    $jsondata["ret"] = "1-";
    $jsondata["msg"] = "Terjadi kesalahan pada server!";
    $logs_pn5077->error(json_encode($sql), json_encode($jsondata), "Gagal Insert data dokumen lainnya gagal execute : $comment_if_pn5077");
  } else {
    $jsondata["ret"] = "0";
    $jsondata["msg"] = "Sukses";
    $logs_pn5077->info(json_encode($sql), json_encode($jsondata), "Berhasil Insert data dokumen lainnya : $comment_if_pn5077");
  }
  echo json_encode($jsondata);
} else if ($ls_tipe == 'deleteDokumenLain'){
  $comment_if_pn5077 .= '[if ($ls_tipe == "deleteDokumenLain")]';
    $ls_kode_fiktif_klaim    = (isset($_POST["kode_fiktif_klaim"]) && !empty($_POST["kode_fiktif_klaim"]) ? $_POST["kode_fiktif_klaim"] : NULL);
    $ls_kode_dokumen    = (isset($_POST["kode_dokumen"]) && !empty($_POST["kode_dokumen"]) ? $_POST["kode_dokumen"] : NULL);
    $ls_nama_dokumen    = (isset($_POST["nama_dokumen"]) && !empty($_POST["nama_dokumen"]) ? $_POST["nama_dokumen"] : NULL);
    $ls_path_url    = (isset($_POST["path_url"]) && !empty($_POST["path_url"]) ? $_POST["path_url"] : NULL);
    if(!empty($ls_path_url)) {
      $url = explode('/', $ls_path_url);

      $namaBucket = $url[1];
      $namaFolder = $url[2].'/'.$url[3].'/'.$url[4];
      $file = $url[6];

      # HAPUS FILE DI STOREAGE
      $param = array(
        "namaBucket" => $namaBucket,
        "namaFolder" => $namaFolder,
        "file" => $file,
      );
      // $response = api_json_call_new( $wsIpStorage.'/object/delete',$header, $param);
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL =>  $wsIpStorage.'/object/delete',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($param),
        CURLOPT_HTTPHEADER => array(
          'X-Forwarded-For: ',
          'Content-Type: application/json',
          'Cookie: fd127c8eca88a94a3a7254d6c3f59cc4=7734f5533b7448eab6fc2e4e640521b7'
        ),
      ));

      $response = curl_exec($curl);
      $err = curl_error($curl);
      curl_close($curl);
      if ($err) {
        $comment_if_pn5077 .= '[if ($err)]';
        $jdata["ret"] = -1;
        $jdata["msg"] = "cURL Error #:" . $err;
        $logs_pn5077->error(json_encode( $wsIpStorage.'/object/delete'), json_encode($jdata), "Gagal hapus file dengan ws : $comment_if_pn5077");
        $result = $jdata;
      } else {
        $response = json_decode($response);
        if (isset($response->ret) && $response->ret == 0) {
          $comment_if_pn5077 .= '[if (isset($response->ret) && $response->ret == 0)]';
          # CEK FILE BERHASIL DIHAPUS SECARA MANUAL
          $url_cek =  $wsIpStorage . $ls_path_url;
          $cek = api_json_call_get_pn5077($url_cek);
          if (isset($cek->ret) && $cek->ret == -1 && $cek->msg == "File not found") {
            $comment_if_pn5077 .= '[if (isset($cek->ret) && $cek->ret == -1 && $cek->msg == "File not found")]';
            # HAPUS FILE DI DB
            $sql = "DELETE FROM PN.PN_FIKTIF_KLAIM_DOKUMEN WHERE KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim' AND NAMA_DOKUMEN = '$ls_nama_dokumen' AND KODE_DOKUMEN = '$ls_kode_dokumen' AND PATH_URL = '$ls_path_url'";
        
            $DB->parse($sql); 
            if($DB->execute()){  
              $jsondata["ret"] = 0;
              $jsondata["data"] = $ls_kode_fiktif_klaim;
              $jsondata["msg"] = "File berhasil dihapus";
              $logs_pn5077->info(json_encode($sql), json_encode($jsondata), "Berhasil hapus file : $comment_if_pn5077");
              echo json_encode($jsondata);
            } else {
              $jsondata["ret"] = -1;
              $jsondata["msg"] = "Terjadi kesalahan pada server!";
              $logs_pn5077->error(json_encode($sql), json_encode($jsondata), "Gagal hapus file, karena query tidak berhasil di execute : $comment_if_pn5077");
              echo json_encode($jsondata);
            }
          } else {
            $jsondata["ret"] = -1;
            $jsondata["msg"] = "File gagal dihapus";
            $logs_pn5077->error(json_encode($url_cek), json_encode($jsondata), "Gagal hapus file setelah dicek manual file masih ada : $comment_if_pn5077");
            echo json_encode($jsondata);
          }
        } else {
          $jdata["ret"] = -1;
          $jdata["msg"] = "cURL Error #:" . (isset($response->msg) && !empty($response->msg) ? $response->msg : NULL);
          $logs_pn5077->error(json_encode($response), json_encode($jdata), "Gagal hapus file karena return dari ws tidak 0 : $comment_if_pn5077");
          echo json_encode($jdata);
        }
      }
    } else {
      $jsondata["ret"] = -1;
      $jsondata["msg"] = "Path url kosong";
      $logs_pn5077->error(json_encode($ls_path_url), json_encode($jsondata), "Gagal hapus file karena ls_path_url kosong : $comment_if_pn5077");
      echo json_encode($jsondata);
    }


    
}

$logs_pn5077->stop('-', '-', 'Stopping	PN5077 - Verifikasi Potensi Klaim Fiktif');
?>