<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once __DIR__ . "../../logs.php";

$DB = new Database($gs_DBUser, $gs_DBPass, $gs_DBName);
$ECDB = new Database($EC_DBUser, $EC_DBPass, $EC_DBName);

$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];

$logs_pn5078 = new Logs();
$logs_pn5078->setFunctionName('mod_pn/ajax/pn5078_action');
$logs_pn5078->setEventName('PN5078 - Approval Potensi Klaim Fiktif');
$logs_pn5078->start();

function encrypt_decrypt_pn5078($action, $string)
{
  /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt_pn5078('encrypt', $string);
     * DECRYPTION: encrypt_decrypt_pn5078('decrypt', $string) ;
     */
  $output_pn5078 = false;
  $encrypt_method_pn5078 = "AES-256-CBC";
  $secret_key_pn5078 = 'WS-SERVICE-KEY';
  $secret_iv_pn5078 = 'WS-SERVICE-VALUE';
  // hash
  $key = hash('sha256', $secret_key_pn5078);
  // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $iv = substr(hash('sha256', $secret_iv_pn5078), 0, 16);
  if ($action == 'encrypt') {
    $output_pn5078 = base64_encode(openssl_encrypt($string, $encrypt_method_pn5078, $key, 0, $iv));
  } else {
    if ($action == 'decrypt') {
      $output_pn5078 = openssl_decrypt(base64_decode($string), $encrypt_method_pn5078, $key, 0, $iv);
    }
  }
  return $output_pn5078;
}

if (!function_exists('get_start_pn5078')) {
  /**
   * 
   * @param string $page
   * @param string $limit
   * 
   * @return number
   */
  function get_start_pn5078($page, $limit)
  {

    $start = ($page * $limit) - $limit;

    return $start + 1;
  }
}

if (!function_exists('clean_pn5078')) {
  /**
   * Trim whitespace, hapus tag php/html, hapus unicode NO-BREAK SPACE/nbsp (U+00a0),
   * convert special character jadi karakter biasa
   * 
   * @param string $string
   * @param bool   $stripTags
   * 
   * @return string
   */
  function clean_pn5078($string, $stripTags = true)
  {
    if ($string) {
      $res = htmlspecialchars(trim(preg_replace('/\xc2\xa0/', '', $string)));
      return $stripTags ? strip_tags($res) : $res;
    }
    return NULL;
  }
}

if (!function_exists('setSearch_pn5078')) {
  /**
   * 
   * 
   * @param string $q | inputan
   * @param string $key | keyword
   * @param string $status_proses
   * 
   * @return string query
   */
  function setSearch_pn5078($q, $key, $status_proses)
  {
    $tmp = NULL;
    if ($q) {
      $kolomBelum = ["KODE_FIKTIF_KLAIM", "ID_POINTER_ASAL", "TGL_REKAM_PENGAJUAN", "PETUGAS_REKAM_PENGAJUAN",
      "KODE_KANTOR_PENGAJUAN", "TGL_SLA1_VERIFIKASI_KASUS", "KODE_STATUS_PENGAJUAN"];
      $kolomSudah = ["KODE_FIKTIF_KLAIM", "ID_POINTER_ASAL", "TGL_REKAM_PENGAJUAN", "PETUGAS_REKAM_PENGAJUAN",
      "KODE_KANTOR_PENGAJUAN", "TGL_SLA1_VERIFIKASI_KASUS", "TGL_VERIFIKASI_KASUS", "KODE_STATUS_PENGAJUAN", "TGL_APPROVAL"];
      $colomDate = ["TGL_REKAM_PENGAJUAN", "TGL_SLA1_VERIFIKASI_KASUS", "TGL_VERIFIKASI_KASUS", "TGL_APPROVAL"];
      $field = $status_proses == 'belum' ? $kolomBelum : $kolomSudah;
      if (!empty($key) && in_array($key, $field)) {
        $q = (in_array($key, $colomDate) ? str_replace('/', '-', $q) : $q);
        $tmp = "AND $key LIKE '%$q%'";
      } else {
        $tmp = "AND (";
        for ($i = 0; $i < count($field); $i++) {
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
$ls_status_proses   = $_POST["status_proses"] ?? null;
$comment_if = null;
$where_flag = "AND KLM.STATUS_VERIFIKASI_KASUS = 'Y'";
$ls_status_proses_pop_up   = $_POST["status_proses_pop_up"] ?? false;

if (!$ls_status_proses_pop_up) {
  $where_flag = $where_flag . " " . ($ls_status_proses == 'belum' ? "AND KLM.STATUS_APPROVAL = 'T'" : "AND KLM.STATUS_APPROVAL !=  'T'");
}
$order_flag = ($ls_status_proses == 'belum' ? "ORDER BY KLM.TGL_VERIFIKASI_KASUS ASC" : "ORDER BY KLM.TGL_APPROVAL DESC");

$where_gabungan = NULL;
if (!$ls_status_proses_pop_up) {
  $where_gabungan = "WHERE KLM.KODE_KANTOR_PENGAJUAN = '$KD_KANTOR' $where_flag $order_flag"; // dibuat untuk mengurangi kendala di sonarqube DUPLICATED LINES
}
$sql = "SELECT
          CONCAT( CONCAT( KODE_KANTOR_PENGAJUAN, ' - ' ), NAMA_KANTOR ) KANTOR,
          CONCAT( CONCAT( PETUGAS_REKAM_PENGAJUAN, ' - ' ), NAMA_PETUGAS ) PETUGAS,
          A.*
        FROM
          (
          SELECT
            KLM.KODE_FIKTIF_KLAIM,
            KLM.NOMOR_IDENTITAS_ANTRIAN,
            TO_CHAR ( KLM.TGL_REKAM_PENGAJUAN, 'DD-MM-YYYY' ) TGL_REKAM_PENGAJUAN,
            KLM.PETUGAS_REKAM_PENGAJUAN,
            ( SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = KLM.PETUGAS_REKAM_PENGAJUAN ) NAMA_PETUGAS,
            KLM.KODE_KANTOR_PENGAJUAN,
            ( SELECT KTR.NAMA_KANTOR FROM MS.MS_KANTOR KTR WHERE KTR.KODE_KANTOR = KLM.KODE_KANTOR_PENGAJUAN ) NAMA_KANTOR,
            TO_CHAR ( KLM.TGL_SLA1_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_SLA1_VERIFIKASI_KASUS,
            TO_CHAR ( KLM.TGL_SLA2_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_SLA2_VERIFIKASI_KASUS,
            KLM.STATUS_APPROVAL,
            TO_CHAR ( KLM.TGL_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_VERIFIKASI_KASUS,
            TO_CHAR ( NVL ( KLM.TGL_VERIFIKASI_KASUS, sysdate ), 'YYYY-MM-DD' ) TGL_VERIFIKASI_KASUS_FORMAT,
            TO_CHAR ( KLM.TGL_SLA1_VERIFIKASI_KASUS, 'YYYY-MM-DD' ) TGL_SLA1_VERIFIKASI_KASUS_FORMAT,
            TO_CHAR ( KLM.TGL_APPROVAL, 'DD-MM-YYYY' ) TGL_APPROVAL,
            TO_CHAR ( KLM.TGL_REKAM_VERIFIKASI_KASUS, 'DD-MM-YYYY' ) TGL_REKAM_VERIFIKASI_KASUS,
            KLM.FLAG_PERPANJANGAN_SLA,
            KLM.KETERANGAN_VERIFIKASI,
            KLM.KODE_VERIFIKASI,
            KLM.FLAG_CEK_KASUS,
            KLM.KODE_STATUS_PENGAJUAN,
            ( SELECT NAMA_PENGAJUAN FROM PN.PN_FIKTIF_KODE_PENGAJUAN FKP WHERE FKP.KODE_STATUS_PENGAJUAN = KLM.KODE_STATUS_PENGAJUAN AND ROWNUM <= 1 ) NAMA_STATUS_PENGAJUAN,
            KLM.PETUGAS_APPROVAL,
            ( SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = KLM.PETUGAS_APPROVAL ) NAMA_PETUGAS_APPROVAL,
            KLM.KETERANGAN_APPROVAL,
            KLM.ID_POINTER_ASAL,
            KLM.KODE_POINTER_ASAL,
            KLM.PETUGAS_VERIFIKASI_KASUS,
            ( SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = KLM.PETUGAS_VERIFIKASI_KASUS ) NAMA_PETUGAS_VERIFIKASI_KASUS,
            KLM.KODE_TIPE_PELAPOR,
            (
            SELECT
              TP.NAMA_TIPE_PENERIMA
            FROM
              PN.PN_KODE_TIPE_PENERIMA TP
            WHERE
              NVL ( TP.STATUS_NONAKTIF, 'X' ) = 'T'
              AND TP.KODE_TIPE_PENERIMA = KLM.KODE_TIPE_PELAPOR
            ) STATUS_PELAPOR
          FROM
          PN.PN_FIKTIF_KLAIM KLM
          $where_gabungan
          )A
      ";


if ($ls_tipe == "select") {
  $comment_if .= '[if ($ls_tipe == "select")]';
  # get list data

  $ls_page            = $_POST["page"] ?? 1;
  $ls_page_item       = $_POST["page_item"] ?? 10;
  $ls_search_by       = isset($_POST["search_by"]) ? strtoupper($_POST["search_by"]) : NULL;
  $ls_search_txt      = isset($_POST["search_txt"]) ? strtoupper(clean_pn5078($_POST["search_txt"])) : NULL;

  $start = get_start_pn5078($ls_page, $ls_page_item);
  $search = $ls_search_txt ? setSearch_pn5078($ls_search_txt, $ls_search_by, $ls_status_proses) : NULL;
  $limit = $ls_page * $ls_page_item;

  $sqlResult = "SELECT * FROM ( SELECT a.*, ROWNUM rnum FROM ($sql) a WHERE ROWNUM <= $limit) WHERE rnum >= $start"; 
  if ($search) {
    $sqlResult = "SELECT * FROM ( SELECT a.*, ROWNUM rnum FROM ($sql) a) WHERE KODE_FIKTIF_KLAIM IS NOT NULL $search";
  }

  $data = array();
  $DB->parse($sqlResult);
  if ($DB->execute()) {
    while ($row = $DB->nextrow()) {
      array_push($data, $row);
    }
    $logs_pn5078->info(json_encode($sqlResult), json_encode($data), "List data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  } else {
    $logs_pn5078->error(json_encode($sqlResult), json_encode($DB), "Gagal mendapatkan data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  }

  $searchCount = str_replace('AND', 'WHERE', $search);
  $sqlCount = "SELECT * FROM ($sql) $searchCount";
  $count = array();
  $DB->parse($sqlCount);
  if ($DB->execute()) {
    while ($row = $DB->nextrow()) {
      array_push($count, $row);
    }
    $logs_pn5078->info(json_encode($sqlCount), json_encode($count), "Menghitung jumlah data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  } else {
    $logs_pn5078->error(json_encode($sqlCount), json_encode($DB), "Gagal mendapatkan menghitung jumlah data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  }

  if ($data) {
    $return = array(
      'ret' => 0,
      'data' => $data,
      'msg' => 'Berhasil',
      'limit' => $limit ?? NULL,
      'start' => $start ?? NULL,
      'recordsTotal' => count($count)
    );
  } else {
    $return = array(
      'ret' => -2,
      'data' => $data,
      'msg' => 'Data tidak ditemukan',
      'limit' => $limit ?? NULL,
      'start' => $start ?? NULL,
    );
  }
  echo json_encode($return);
} else if ($ls_tipe == "select_detail") {
  $comment_if .= '[if ($ls_tipe == "select_detail")]';
  # get data detail

  $ls_kode_fiktif_klaim      = isset($_POST['kode_fiktif_klaim']) ? encrypt_decrypt_pn5078('decrypt', $_POST['kode_fiktif_klaim']) : NULL;
  $sql = "SELECT * FROM ($sql) WHERE KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim'";

  $data = array();
  $DB->parse($sql);
  if ($DB->execute()) {
    $data = $DB->nextrow();
    $logs_pn5078->info(json_encode($sql), json_encode($data), "Detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  } else {
    $logs_pn5078->error(json_encode($sql), json_encode($DB), "Gagal mendapatkan detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  }

  $sqlLapakAsik =  "SELECT
                    kode_booking,
                    jenis_antrian,
                    kode_tipe_penerima,
                    kode_layanan
                  FROM
                    antrian.atr_booking
                  WHERE
                    kode_booking = '" . $data[' ID_POINTER_ASAL '] . "' UNION ALL
                  SELECT
                    kode_booking,
                    jenis_antrian,
                    kode_tipe_penerima,
                    kode_layanan
                  FROM
                    antrian.atr_booking_hist
                  WHERE
                    kode_booking = '" . $data[' ID_POINTER_ASAL '] . "'";

  $dataLapakAsik = array();
  $ECDB->parse($sqlLapakAsik);
  if ($ECDB->execute()) {
    $dataLapakAsik = $ECDB->nextrow();
    $logs_pn5078->info(json_encode($sqlLapakAsik), json_encode($dataLapakAsik), "Detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  } else {
    $logs_pn5078->error(json_encode($sqlLapakAsik), json_encode($ECDB), "Gagal mendapatkan detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
  }
  $dataLapakAsik = ($dataLapakAsik) ? $dataLapakAsik : [];
  $data = array_merge($data, $dataLapakAsik);

  if ($data) {
    $comment_if .= '[if ($data)]';
    $data['dataKpj'] = $data['dataKodeVerifikasi'] = $data['dataHubungan'] = $data['dataDokumen'] = array();
    $data['TGL_VERIFIKASI_KASUS'] = (empty($data['TGL_VERIFIKASI_KASUS']) ? date('d-m-Y') : $data['TGL_VERIFIKASI_KASUS']);
    if ($data['TGL_VERIFIKASI_KASUS_FORMAT'] > $data['TGL_SLA1_VERIFIKASI_KASUS_FORMAT']) {
      $data['FLAG_PERPANJANGAN_SLA'] = 'Y';
    } else {
      $data['FLAG_PERPANJANGAN_SLA'] = 'T';
    }

    if (isset($data['KODE_FIKTIF_KLAIM']) && !empty($data['KODE_FIKTIF_KLAIM'])) {
      $comment_if .= '[if (isset($data["KODE_FIKTIF_KLAIM"]) && !empty($data["KODE_FIKTIF_KLAIM"]))]';
      $dataKpj = array();
      $sql_kpj = "SELECT KLMP.KODE_FIKTIF_KLAIM, KLMP.KODE_TK, KLMP.KPJ, KLMP.NAMA_LENGKAP NAMA_TK, KLMP.NOMOR_IDENTITAS NIK, TO_CHAR( KLMP.TGL_LAHIR, 'DD-MM-YYYY' ) TGL_LAHIR, KLMP.KODE_DIVISI, KLMP.KODE_PERUSAHAAN NPP, KLMP.NAMA_PERUSAHAAN FROM PN.PN_FIKTIF_KLAIM_KEPESERTAAN KLMP WHERE KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim'";
      $DB->parse($sql_kpj);
      if ($DB->execute()) {
        while ($row = $DB->nextrow()) {
          array_push($dataKpj, $row);
        }
        $logs_pn5078->info(json_encode($sql_kpj), json_encode($dataKpj), "Detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
      } else {
        $logs_pn5078->error(json_encode($sql_kpj), json_encode($DB), "Gagal mendapatkan detail data Verifikasi Potensi Klaim Fiktif $ls_status_proses diproses : $comment_if");
      }
      $data['dataKpj'] = $dataKpj;
    }

    $sql_kode = "SELECT KODE_VERIFIKASI, NAMA_VERIFIKASI FROM PN.PN_FIKTIF_KODE_VERIFIKASI WHERE STATUS_NONAKTIF = 'T' ORDER BY NO_URUT";
    $DB->parse($sql_kode);
    $dataKodeVerifikasi = array();
    if ($DB->execute()) {
      while ($row = $DB->nextrow()) {
        array_push($dataKodeVerifikasi, $row);
      }
      $logs_pn5078->info(json_encode($sql_kode), json_encode($dataKodeVerifikasi), "List data Kode verifikasi $ls_status_proses diproses : $comment_if");
    } else {
      $logs_pn5078->error(json_encode($sql_kode), json_encode($DB), "Gagal mendapatkan List data Kode verifikasi $ls_status_proses diproses : $comment_if");
    }
    $data['dataKodeVerifikasi'] = $dataKodeVerifikasi;

    $sql_dokumen = "SELECT FKD.KODE_FIKTIF_KLAIM, FKD.KODE_DOKUMEN, FKD.NAMA_DOKUMEN, ( SELECT KD.NAMA_DOKUMEN FROM PN.PN_KODE_DOKUMEN KD WHERE KD.KODE_DOKUMEN = FKD.KODE_DOKUMEN ) NAMA_KODE_DOKUMEN, FKD.PATH_URL, TO_CHAR( FKD.TGL_UPLOAD, 'DD-MM-YYYY' ) TGL_UPLOAD, FKD.KODE_FUNGSI, (SELECT FG.INISIAL_FUNGSI FROM MS.SC_FUNGSI FG WHERE FG.KODE_FUNGSI = FKD.KODE_FUNGSI) SUMBER, FKD.KETERANGAN FROM PN.PN_FIKTIF_KLAIM_DOKUMEN FKD WHERE FKD.KODE_FIKTIF_KLAIM = '$ls_kode_fiktif_klaim' ORDER BY TGL_REKAM ASC";

    $DB->parse($sql_dokumen);
    $dataDokumen = array();
    if ($DB->execute()) {
      while ($row = $DB->nextrow()) {
        array_push($dataDokumen, $row);
      }
      $logs_pn5078->info(json_encode($sql_dokumen), json_encode($dataDokumen), "List data dokumen $ls_status_proses diproses : $comment_if");
    } else {
      $logs_pn5078->error(json_encode($sql_dokumen), json_encode($DB), "Gagal mendapatkan List data dokumen $ls_status_proses diproses : $comment_if");
    }
    $data['dataDokumen'] = $dataDokumen;
  }

  if ($data) {
    $return = array(
      'ret' => 0,
      'data' => $data,
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
  $comment_if .= '[if ($ls_tipe == "submitData")]';
  # kirim data ke prosedure

  $kode_fiktif_klaim = isset($_POST['id']) ? encrypt_decrypt_pn5078('decrypt', $_POST['id']) : NULL;
  $status_approval = (isset($_POST['status_persetujuan']) ? clean_pn5078($_POST['status_persetujuan']) : NULL);
  $tgl_mulai_cek_kasus = (isset($_POST['tgl_verifikasi_kasus']) ? $_POST['tgl_verifikasi_kasus'] : NULL);
  $kode_hasil_verifikasi = (isset($_POST['hasil_verifikasi_kasus']) ? clean_pn5078($_POST['hasil_verifikasi_kasus']) : NULL);
  $keterangan_verifikasi = (isset($_POST['keterangan_persetujuan']) ? clean_pn5078($_POST['keterangan_persetujuan']) : NULL);
  $petugas_approval = $USER;
  $sukses = $mess = NULL;

  $sql = "BEGIN pn.p_pn_pn5077.x_approval_fiktif_klaim (:p_kode_fiktif_klaim, :p_status_approval, :p_petugas_approval, :p_keterangan_approval, :p_sukses, :p_mess); END;";
  $proc = $DB->parse($sql);

  oci_bind_by_name($proc, ':p_kode_fiktif_klaim', $kode_fiktif_klaim, 100);
  oci_bind_by_name($proc, ':p_status_approval', $status_approval, 10);
  oci_bind_by_name($proc, ':p_petugas_approval', $petugas_approval, 30);
  oci_bind_by_name($proc, ':p_keterangan_approval', $keterangan_verifikasi, 100);
  oci_bind_by_name($proc, ':p_sukses', $sukses, 10);
  oci_bind_by_name($proc, ':p_mess', $mess, 1000);

  if ($DB->execute()) {
    if ($sukses == 1) {
      $comment_if .= '[if ($sukses == 1)]';
      $return = array(
        'ret' => $sukses,
        'msg' => 'Data Berhasil Disubmit',
        'kode_fiktif_klaim' => $kode_fiktif_klaim,
      );
      $logs_pn5078->info(json_encode($sql), json_encode($return), "Submit Verifikasi Potensi Klaim Fiktif menggunakan prosedure : $comment_if");
    } else {
      $comment_if .= '[else]';
      http_response_code(400);
      $return = array(
        'ret' => $sukses,
        'msg' => 'Data Gagal Disubmit Dengan Error: ' . $mess,
      );
      $logs_pn5078->error(json_encode($sql), json_encode($return), "Gagal submit Verifikasi Potensi Klaim Fiktif menggunakan prosedure : $comment_if");
    }
  } else {
    http_response_code(400);
    $return = array(
      'ret' => $sukses,
      'msg' => 'Data Gagal Disubmit Dengan Error: ' . $mess,
    );
    $logs_pn5078->error(json_encode($sql), json_encode($return), "Gagal submit Verifikasi Potensi Klaim Fiktif menggunakan prosedure : $comment_if");
  }

  echo json_encode($return);
} else if ($ls_tipe == "status_pengajuan") {
  $comment_if .= '[if ($ls_tipe == "status_pengajuan")]';
  # get data untuk status pengajuan

  $sql_pengajuan = "SELECT KODE_STATUS_PENGAJUAN, NAMA_PENGAJUAN FROM PN.PN_FIKTIF_KODE_PENGAJUAN WHERE STATUS_NONAKTIF = 'T' ORDER BY NO_URUT";
  $DB->parse($sql_pengajuan);
  $dataKodePengajuan = array();
  if ($DB->execute()) {
    while ($row = $DB->nextrow()) {
      array_push($dataKodePengajuan, $row);
    }
    $logs_pn5078->info(json_encode($sql_pengajuan), json_encode($dataKodePengajuan), "List data Kode pengajuan $ls_status_proses diproses : $comment_if");
  } else {
    $logs_pn5078->error(json_encode($sql_pengajuan), json_encode($DB), "Gagal mendapatkan List data Kode pengajuan $ls_status_proses diproses : $comment_if");
  }
  echo json_encode($dataKodePengajuan);
} else if ($ls_tipe == "downloadFileSmile") {

  $ls_path_url = $_POST['path_url'];
  $ls_path_url_encrypt = encrypt_decrypt_pn5078('encrypt', $ls_path_url);

  $jsondata["pathUrlEncrypt"] = $ls_path_url_encrypt;

  echo json_encode($jsondata);
}

$logs_pn5078->stop('-', '-', 'Stopping	PN5078 - Approval Potensi Klaim Fiktif');
