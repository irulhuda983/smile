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

$logs = new Logs();
$logs->setFunctionName('mod_pn/ajax/pn5075_action');
$logs->setEventName('PN5075 - Monitoring Koreksi Pembayaran Klaim Return');
$logs->start();

function api_json_call_new($apiurl, $header, $data) {
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

function encode_arr($data) {
  return base64_encode(serialize($data));
}

function decode_arr($data) {
	return unserialize(base64_decode($data));
}

if (!function_exists('getResult')) {
  /**
   * 
   * @param array $db | connection db
   * @param string $sql | query data
   * 
   * @return array
   */
  function getResult($logs, $db, $sql, $berhasil=null, $gagal=null)
  {
    $result = array();
    $db->parse($sql);
    if($db->execute()){ 
      while($row = $db->nextrow()) {
        array_push($result, $row);
      }
      $logs->info(json_encode($sql), json_encode($result), $berhasil);
    } else {
      $logs->error(json_encode($sql), json_encode($db), $gagal);
    }
    return $result;
  }
}

if (!function_exists('get_start')) {
  /**
   * 
   * @param string $page
   * @param string $limit
   * 
   * @return number
   */
  function get_start($page, $limit) {
      
    $start = ($page * $limit) - $limit;

    return $start + 1;
  }
}

if (!function_exists('getRow')) {
  /**
   * 
   * @param array $db | connection db
   * @param string $sql | query data
   * 
   * @return array
   */
  function getRow($logs, $db, $sql, $berhasil=null, $gagal=null)
  {
    $row = array();
    $db->parse($sql);
    if($db->execute()){ 
      $row = $db->nextrow();
      $logs->info(json_encode($sql), json_encode($row), $berhasil);
    } else {
      $logs->error(json_encode($sql), json_encode($db), $gagal);
    }
    return $row;
  }
}

if (!function_exists('clean')) {
  /**
   * Trim whitespace, hapus tag php/html, hapus unicode NO-BREAK SPACE/nbsp (U+00a0),
   * convert special character jadi karakter biasa
   * 
   * @param string $string
   * @param bool   $stripTags
   * 
   * @return string
   */
  function clean($string, $stripTags = true)
  {
    if ($string) {
      $res = htmlspecialchars(trim(preg_replace('/\xc2\xa0/', '', $string)));
      return $stripTags ? strip_tags($res) : $res;
    }
    return NULL;
  }
}

if (!function_exists('setSearch')) {
  /**
   * 
   * 
   * @param string $q | inputan
   * @param string $key | keyword
   * @param string $status_proses
   * 
   * @return string query
   */
  function setSearch($q, $key, $status_proses)
  {
    $tmp = NULL;
    if ($q) {
      $belum = ['KODE_KLAIM','NOMOR_IDENTITAS','NAMA_TK','KPJ','TGL_KLAIM','KODE_BANK_PENERIMA','NO_REKENING_PENERIMA','NAMA_TK'];
      $sudah = ['KODE_AGENDA_KOREKSI','KODE_KLAIM','NOMOR_IDENTITAS','NAMA_TK','KPJ','TGL_KLAIM','KODE_BANK_PENERIMA','NO_REKENING_PENERIMA','NAMA_TK','TGL_AGENDA_KOREKSI','PETUGAS_KOREKSI','KODE_KANTOR_KOREKSI','STATUS_APPROVAL'];
      $field = $status_proses == 'belum' ? $belum : $sudah;
      if (!empty($key) && in_array($key, $field)) {
        $q = ($key == 'TGL_KLAIM' || $key == 'TGL_AGENDA_KOREKSI' ? str_replace('/', '-', $q) : $q);
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

if (!function_exists('getData')) {
  /**
   * 
   * 
   * @param string $DB | database yg digunakan
   * @param string $sql | query yg digunakan
   * @param string $ECDB | tidak wajib
   * 
   * @return string array
   */
  function getData($logs, $DB, $sql, $ECDB)
  {
    $ls_tipe            = $_POST["tipe"] ?? 'select';
    $ls_page            = $_POST["page"] ?? 1;
    $ls_page_item       = $_POST["page_item"] ?? 10;
    $ls_search_by       = isset($_POST["search_by"]) ? strtoupper($_POST["search_by"]) : NULL;
    $ls_search_txt      = isset($_POST["search_txt"]) ? strtoupper(clean($_POST["search_txt"])) : NULL;
    $ls_status_proses   = $_POST["status_proses"] ?? NULL;
    // untuk filter detail
    $ls_kode_klaim      = isset($_POST['kode_klaim']) ? encrypt_decrypt('decrypt', $_POST['kode_klaim']) : NULL;
    $ls_kode_agenda      = isset($_POST['kode_agenda']) ? encrypt_decrypt('decrypt', $_POST['kode_agenda']) : NULL;
    $ls_kpj             = isset($_POST['kpj']) ? encrypt_decrypt('decrypt', $_POST['kpj']) : NULL;
    $ls_nomor_identitas = isset($_POST['nomor_identitas']) ? encrypt_decrypt('decrypt', $_POST['nomor_identitas']) : NULL;


    $count = 0;
    if ($ls_tipe == 'select') {
        # list
        $start = get_start($ls_page, $ls_page_item); 
        $search = $ls_search_txt ? setSearch($ls_search_txt, $ls_search_by, $ls_status_proses) : NULL;
        // echo $search;die;
        $limit = $ls_page * $ls_page_item;
        $sqlResult = "SELECT * FROM ( SELECT a.*, ROWNUM rnum FROM ($sql) a WHERE ROWNUM <= $limit) WHERE rnum >= $start $search";
        $data = getResult($logs, $DB, $sqlResult, "List data monitoring $ls_status_proses diproses", "Gagal mendapatkan data monitoring $ls_status_proses diproses");
        foreach ($data as $no => $r) {
            $data[$no]['params'] = encrypt_decrypt('encrypt', encode_arr(array(
            'kode_klaim' => $r['KODE_KLAIM'],
            'kode_agenda' => ($ls_status_proses == 'sudah' ? $r['KODE_AGENDA_KOREKSI'] : NULL),
            'search_by' => $ls_search_by,
            'search_txt' => $ls_search_txt,
            'status_proses' => $ls_status_proses,
            )));
            $data[$no]['NOM_MANFAAT'] = number_format($r['NOM_MANFAAT'], 0, ',', '.');
        }

        $searchCount = str_replace('AND', 'WHERE', $search);
        $sqlCount = "SELECT * FROM ($sql) $searchCount";
        $count = getResult($logs, $DB, $sqlCount, "Menghitung jumlah data monitoring $ls_status_proses diproses", "Gagal mendapatkan menghitung jumlah data monitoring $ls_status_proses diproses");
    } else if ($ls_tipe == 'form_detil') {
        # detail
        if($ls_status_proses == 'belum') {
          $sql = "SELECT * FROM ($sql) WHERE KODE_KLAIM = '$ls_kode_klaim'";
        } else if ($ls_status_proses == 'sudah') {
          $sql = "SELECT * FROM ($sql) WHERE KODE_KLAIM = '$ls_kode_klaim' AND KODE_AGENDA_KOREKSI = '$ls_kode_agenda'";
          // $sql = "SELECT * FROM ($sql) WHERE KODE_KLAIM = '$ls_kode_klaim'";
        }
        
        $data = getRow($logs, $DB, $sql, "Detail data monitoring $ls_status_proses diproses", "Gagal mendapatkan detail data monitoring $ls_status_proses diproses");

        if(!empty($data['KODE_BANK_PEMBAYAR'])) {
          $KODE_BANK_PEMBAYAR = $data['KODE_BANK_PEMBAYAR'];
          $sql_bank_pembayaran = "SELECT KODE_BANK, NAMA_BANK FROM SPO.SPO_BANK WHERE OPG_STATUS = 'Y' AND OPG_MODE IS NOT NULL AND KODE_BANK = '$KODE_BANK_PEMBAYAR'";
          $bank_pembayar = getRow($logs, $ECDB, $sql_bank_pembayaran, "Nama bank pembayar lama", "Gagal mendapatkan nama bank pembayar lama");
          $data['NAMA_BANK_PEMBAYAR'] = $bank_pembayar['NAMA_BANK'];
        }

        $data['NOM_MANFAAT'] = $data['NOM_MANFAAT'] ? number_format($data['NOM_MANFAAT'], 0, ',', '.') : 0;
        if ($ls_status_proses == 'sudah') {
            $KODE_AGENDA_KOREKSI = $data['KODE_AGENDA_KOREKSI'];
            $sql_persetujuan_koreksi = "SELECT
            APP.KODE_JABATAN,
            (SELECT NAMA_JABATAN FROM MS.MS_JABATAN A WHERE A.KODE_JABATAN = APP.KODE_JABATAN AND ROWNUM <= 1) PENJABAT,
            APP.KODE_KANTOR, 
            ( SELECT KTR.NAMA_KANTOR FROM MS.MS_KANTOR KTR WHERE KTR.KODE_KANTOR = APP.KODE_KANTOR AND ROWNUM <= 1) NAMA_KANTOR,
            APP.STATUS_APPROVAL,
            TO_CHAR( APP.TGL_APPROVAL, 'DD-MM-YYYY' ) TGL_APPROVAL,
            APP.PETUGAS_APPROVAL,
            (SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = APP.PETUGAS_APPROVAL) NAMA_PETUGAS_APPROVAL,
            KETERANGAN_APPROVAL
          FROM
            PN.PN_AGENDA_KOREKSI_REKENING_APPROVAL APP
          WHERE KODE_AGENDA_KOREKSI = '$KODE_AGENDA_KOREKSI'";
            $persetujuaKoreksi = getResult($logs, $DB, $sql_persetujuan_koreksi, "list data approval dari detail data monitoring $ls_status_proses diproses", "Gagal mendapatkan list data approval dari detail data monitoring $ls_status_proses diproses");
            $data['persetujuaKoreksi'] = $persetujuaKoreksi;
            
          if(!empty($data['KODE_BANK_PEMBAYAR_BARU'])) {
            $KODE_BANK_PEMBAYAR_BARU = $data['KODE_BANK_PEMBAYAR_BARU'];
            $sql_bank_pembayaran = "SELECT KODE_BANK, NAMA_BANK FROM SPO.SPO_BANK WHERE OPG_STATUS = 'Y' AND OPG_MODE IS NOT NULL AND KODE_BANK = '$KODE_BANK_PEMBAYAR_BARU'";
            $bank_pembayar = getRow($logs, $ECDB, $sql_bank_pembayaran, "Nama bank pembayar baru", "Gagal mendapatkan nama bank pembayar baru");
            $data['NAMA_BANK_PEMBAYAR_BARU'] = $bank_pembayar['NAMA_BANK'];
          }
        }
    }
    if ($data) {
        $return = array(
            'ret' => 0,
            'data' => $data,
            'msg' => 'Berhasil',
            'limit' => $limit ?? NULL,
            'start' => $start ?? NULL,
        );
        if ($ls_tipe == 'select') {
            $return = array_merge($return, ['recordsTotal'=> count($count)]);
        }
    } else {
        $return = array(
            'ret' => -2,
            'data' => $data,
            'msg' => 'Data tidak ditemukan',
            'limit' => $limit ?? NULL,
            'start' => $start ?? NULL,
        );
    }
    return json_encode($return);
  }
}

if (!function_exists('submitData')) {
  /**
   * 
   * 
   * @param string $DB | database yg digunakan
   * 
   * @return string array
   */
  function submitData($logs, $DB)
  {
    $KD_KANTOR = $_SESSION['kdkantorrole'];
    $USER = $_SESSION["USER"];

    $kode_klaim = isset($_POST['id']) ? encrypt_decrypt('decrypt', $_POST['id']) : NULL;
    $no_konfirmasi = (isset($_POST['no_konfirmasi']) ? clean($_POST['no_konfirmasi']) : NULL);
    $no_proses = (isset($_POST['no_proses']) ? clean($_POST['no_proses']) : NULL);
    $kd_prg = (isset($_POST['kd_prg']) ? clean($_POST['kd_prg']) : NULL);
    
    $kode_bank = (isset($_POST['kode_bank_penerima']) ? clean($_POST['kode_bank_penerima']) : NULL);
    $nama_bank = (isset($_POST['bank_penerima']) ? clean($_POST['bank_penerima']) : NULL);

    $kode_bank_penerima = (isset($_POST['kode_bank_baru']) ? clean($_POST['kode_bank_baru']) : NULL);
    $nama_bank_penerima = (isset($_POST['nama_bank_baru']) ? clean($_POST['nama_bank_baru']) : NULL);
    $nomor_rekening_penerima = (isset($_POST['no_rekening_penerima_baru']) ? clean($_POST['no_rekening_penerima_baru']) : NULL);
    $nama_rekening_penerima = (isset($_POST['nama_penerima_baru']) ? clean($_POST['nama_penerima_baru']) : NULL);
    $kode_bank_pembayar = (isset($_POST['kode_bank_pembayar_baru']) ? clean($_POST['kode_bank_pembayar_baru']) : NULL);
    $keterangan_koreksi = (isset($_POST['keterangan_koreksi_baru']) ? clean($_POST['keterangan_koreksi_baru']) : NULL);
    $kode_kantor_koreksi = $KD_KANTOR;
    $petugas_koreksi = $USER;
    
    $sukses = $mess = $kode_agenda = NULL ;
  
    $sql = "BEGIN PN.P_PN_PN5075.X_INSERT_AGENDA_KOREKSI (:p_kode_klaim,:p_no_konfirmasi,:p_no_proses,:p_kd_prg,:p_kode_bank,:p_nama_bank,:p_kode_bank_penerima,:p_nama_bank_penerima,:p_nomor_rekening_penerima,:p_nama_rekening_penerima,:p_kode_bank_pembayar,:p_keterangan_koreksi,:p_kode_kantor_koreksi,:p_petugas_koreksi,:p_kode_agenda_koreksi, :p_sukses,:p_mess); END;";
  
    $proc = $DB->parse($sql);

    oci_bind_by_name($proc, ':p_kode_klaim', $kode_klaim, 100);
    oci_bind_by_name($proc, ':p_no_konfirmasi', $no_konfirmasi, 100);
    oci_bind_by_name($proc, ':p_no_proses', $no_proses, 100);
    oci_bind_by_name($proc, ':p_kd_prg', $kd_prg, 100);
    oci_bind_by_name($proc, ':p_kode_bank', $kode_bank, 50);
    oci_bind_by_name($proc, ':p_nama_bank', $nama_bank, 100);
    oci_bind_by_name($proc, ':p_kode_bank_penerima', $kode_bank_penerima, 50);
    oci_bind_by_name($proc, ':p_nama_bank_penerima', $nama_bank_penerima, 100);
    oci_bind_by_name($proc, ':p_nomor_rekening_penerima', $nomor_rekening_penerima, 100);
    oci_bind_by_name($proc, ':p_nama_rekening_penerima', $nama_rekening_penerima, 100);
    oci_bind_by_name($proc, ':p_kode_bank_pembayar', $kode_bank_pembayar, 100);
    oci_bind_by_name($proc, ':p_keterangan_koreksi', $keterangan_koreksi, 1000);
    oci_bind_by_name($proc, ':p_kode_kantor_koreksi', $kode_kantor_koreksi, 20);
    oci_bind_by_name($proc, ':p_petugas_koreksi', $petugas_koreksi, 20);
    oci_bind_by_name($proc, ':p_kode_agenda_koreksi', $kode_agenda, 100);
    oci_bind_by_name($proc, ':p_sukses', $sukses, 10);
    oci_bind_by_name($proc, ':p_mess', $mess, 1000);
  
    if($DB->execute()) {
      if ($sukses == 1) {
        $return = array(
            'ret' => $sukses,
            'msg' => 'Data Berhasil Disubmit',
            'param' => encrypt_decrypt('encrypt', encode_arr(array(
              'kode_klaim' => $kode_klaim,
              'kode_agenda' => $kode_agenda,
              'search_by' => NULL,
              'search_txt' => NULL,
              'status_proses' => 'sudah',
            ))),
        );
        $logs->info(json_encode($sql), json_encode($return), 'Submit koreksi rekening menggunakan prosedure');
      } else {
        http_response_code(400);
        $return = array(
          'ret' => $sukses,
          'msg' => 'Data Gagal Disubmit Dengan Error: '. $mess,
        );
        $logs->error(json_encode($sql), json_encode($return), 'Gagal submit koreksi rekening menggunakan prosedure');
      }
    } else {
      http_response_code(400);
      $return = array(
        'ret' => $sukses,
        'msg' => 'Data Gagal Disubmit Dengan Error: '. $mess,
      );
      $logs->error(json_encode($sql), json_encode($return), 'Gagal submit koreksi rekening menggunakan prosedure');
    }

    return json_encode($return);
  }
}

if (!function_exists('cariBankPembayaran')) {
  /**
   * 
   * 
   * @param string $wsIp | id ws
   * @param string $ipfwd | ip
   * 
   * @return string array
   */
  function cariBankPembayaran($logs, $wsIp, $ipfwd)
  {
    $USER  	= $_SESSION["USER"];

    $resultBank = api_json_call_new($wsIp . "/JSOPG/GetListBank", array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
      ) ,array(
        'chId'=>'CORE',
        'reqId'=>$USER
    ));	

    $listBankPilih = array();
    if($resultBank->ret == "0"){ 

      $adaBankTujuan = false;
      for($i = 0; $i < $resultBank->numrows; $i++)
      {
        if($resultBank->data[$i]->KODE_BANK_ATB == $_POST['kodeBankTujuan']){
          $adaBankTujuan = true;
          array_push($listBankPilih, array(
            'KODE_BANK' => $resultBank->data[$i]->KODE_BANK,
            'NAMA_BANK' => $resultBank->data[$i]->BANK,
            'KODE_BANK_ATB' => $resultBank->data[$i]->KODE_BANK_ATB
          ));
        }
      }

      if(!$adaBankTujuan){
        for($i = 0; $i < $resultBank->numrows; $i++){
          $trfJenis = false;
          for($j = 0; $j < ExtendedFunction::count($resultBank->data[$i]->METODE_TRANSFER);$j++){
            if($resultBank->data[$i]->METODE_TRANSFER[$j]->KODE == 'RTGS' || $resultBank->data[$i]->METODE_TRANSFER[$j]->KODE == 'SKN'){
              $trfJenis = true;
            }
          }

          if($trfJenis){
            array_push($listBankPilih, array(
              'KODE_BANK' => $resultBank->data[$i]->KODE_BANK,
              'NAMA_BANK' => $resultBank->data[$i]->BANK,
              'KODE_BANK_ATB' => $resultBank->data[$i]->KODE_BANK_ATB
            ));
          }
        }
      }
      

      $jsondata["ret"] = "0";
      $jsondata["data"] = $listBankPilih;
      $jsondata["msg"] = "Sukses";
      $logs->info(json_encode($wsIp . "/JSOPG/GetListBank"), json_encode($jsondata), 'Mencari bank pembayar berdasarkan bank tujuan');
      return json_encode($jsondata);
    } else {
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
      $logs->error(json_encode($wsIp . "/JSOPG/GetListBank"), json_encode($jsondata), 'Gagal saat mencari bank pembayar berdasarkan bank tujuan');
      return json_encode($jsondata);
    } 
  }
}

if (!function_exists('cariBank')) {
  /**
   * 
   * 
   * @param string $DB | database yg digunakan
   * @param string $sql | query yg digunakan
   * 
   * @return string array
   */
  function cariBank($logs, $DB, $sql)
  {
    $kode_bank_baru   = $_POST["kode_bank_baru"] ?? NULL;
    if ($kode_bank_baru) {
      $sql = "SELECT * FROM ($sql) WHERE KODE_BANK = '$kode_bank_baru' AND ROWNUM <= 1";
      $row = getRow($logs, $DB, $sql, 'Pencarian bank tujuan', 'Gagal pencarian bank tujuan');
      return json_encode($row);
    } else {
      $search      = isset($_POST["searchTerm"]) ? strtoupper(clean($_POST["searchTerm"])) : NULL;
      $sql = "SELECT * FROM ($sql) WHERE (KODE_BANK LIKE '%$search%' or NAMA_BANK LIKE '%$search%') AND ROWNUM <= 12 ORDER BY NAMA_BANK ASC";
      $result = getResult($logs, $DB, $sql, 'Pencarian bank tujuan', 'Gagal pencarian bank tujuan');
      $data = array();
      foreach ($result as $r ) {
          $data[] = array("id"=>$r['KODE_BANK'], "text"=>$r['KODE_BANK'] .' - '. $r['NAMA_BANK'], "nama_bank_attribute" => $r['NAMA_BANK']);
      }
      return json_encode($data);
    }
  }
}

if (!function_exists('cekValidasiRekening')) {
  /**
   * 
   * 
   * @param string $wsIp | id ws
   * @param string $ipfwd | ip
   * 
   * @return string array
   */
  function cekValidasiRekening($logs, $wsIp, $ipfwd)
  {
    $USER  	= $_SESSION["USER"];
		$norek  = $_POST['NOREK'];
		$bank   = $_POST['BANK'];
    $nama_bank = $_POST['NAMA_BANK'];
    $kode_bank = $_POST['KODE_BANK'];
		
		$url = $wsIp.'/JSOPG/GetAccountInfo';
		
		// set HTTP header
    $headers = array(
      'Content-Type'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );
    
   // set POST params
    if($kode_bank=='009'){
      $data = array(
        'chId'         => 'CORE',
        'reqId'        => $USER,
        'bank'         => 'BNI',
        'NOREK_TUJUAN' => $norek
      );
    }else{
      $data = array(
        'chId'=>'CORE',
        'reqId'=>$USER,   
        'bank'=>$nama_bank,    
        'KODE_BANK_ATB'=>$kode_bank, 
        //'NOREK_ASAL'=>'3389898974',       // 16/07/2021-----Penyesuaian setelah implementasi Midtrans
        'NOREK_TUJUAN'=>$norek
      );
    }
    // Open connection
    $ch = curl_init();
    
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute post
    $result = curl_exec($ch);
    $resultArray = json_decode($result);
    if ($result === false) {
      $logs->error(json_encode($url), json_encode($resultArray), 'Gagal proses pengecekan validasi rekening');
    } elseif (strpos(strtolower($result), 'error') || strpos(strtolower($result), 'page not found')) {
      $logs->error(json_encode($url), json_encode($resultArray), 'Gagal proses pengecekan validasi rekening');
    }
		return $result;
  }
}


$fn                 = isset($_POST['fn']) ? encrypt_decrypt('decrypt', $_POST['fn']) : NULL;
$ls_status_proses   = $_POST["status_proses"] ?? NULL;

# query live

$sql_belum = "SELECT 
  TT.KODE_KLAIM,
  TT.NO_URUT_KELUARGA,
  TT.NO_KONFIRMASI,
  TT.NO_PROSES,
  TT.KD_PRG,
  TT.BLTH_PROSES_BERKALA,
  TT.KODE_TIPE_PENERIMA,
  TT.KODE_PENERIMA_BERKALA,
  TT.NOMOR_IDENTITAS,
  TT.NAMA_TK,
  TT.KPJ,
  TO_CHAR( TT.TGL_KLAIM, 'DD-MM-YYYY' ) TGL_KLAIM,
  TT.NOM_MANFAAT_NETTO NOM_MANFAAT,
  TT2.KODE_BANK_PENERIMA,
  TT2.BANK_PENERIMA,
  TT2.NO_REKENING_PENERIMA,
  TT.NO_REF_PAYMENT KETERANGAN_RETUR,
  TT2.KODE_BANK_PEMBAYAR,
  TT2.NAMA_PENERIMA NAMA_PENERIMA_MANFAAT,
  TT2.NAMA_REKENING_PENERIMA,
  TO_CHAR( TT2.TGL_LAHIR, 'DD-MM-YYYY' ) TGL_LAHIR
  FROM 
  ( 
    SELECT A.KODE_KLAIM,
    C.NO_KONFIRMASI,
    C.NO_PROSES,
    C.KD_PRG,
    TO_CHAR (C.BLTH_PROSES, 'DD/MM/YYYY')
      BLTH_PROSES,
    C.BLTH_PROSES BLTH_PROSES_BERKALA,
    A.KODE_KANTOR,
    A.KODE_TK,
    A.KPJ,
    A.NAMA_TK,
    A.NOMOR_IDENTITAS,
    A.TGL_KLAIM,
    CASE
      WHEN NVL (
                (SELECT KTR.KODE_TIPE
                  FROM MS.MS_KANTOR KTR
                  WHERE KTR.KODE_KANTOR =
                        A.KODE_KANTOR),
                '3') =
            '5'
      THEN
          (SELECT KTR.KODE_KANTOR_INDUK
              FROM MS.MS_KANTOR KTR
            WHERE KTR.KODE_KANTOR =
                  A.KODE_KANTOR)
      ELSE
          A.KODE_KANTOR
    END
      KODE_KANTOR_PEMBAYARAN,
    B.KODE_KANTOR_KONFIRMASI,
    B.KODE_TIPE_PENERIMA,
    B.KODE_PENERIMA_BERKALA,
    (SELECT BKL.NO_URUT_KELUARGA
      FROM PN.PN_KLAIM_PENERIMA_BERKALA BKL
    WHERE     BKL.KODE_KLAIM = A.KODE_KLAIM
          AND BKL.KODE_PENERIMA_BERKALA =
              B.KODE_PENERIMA_BERKALA
          AND NVL (BKL.STATUS_LAYAK, 'T') =
              'Y'
          AND ROWNUM = 1)
      NO_URUT_KELUARGA,
    C.NOM_KOMPENSASI,
    C.NOM_RAPEL,
    C.NOM_BERJALAN,
    C.NOM_PPH,
    C.NOM_PEMBULATAN,
    C.NOM_MANFAAT_NETTO,
    C.NO_REF_PAYMENT,
    TO_CHAR (C.BLTH_PROSES, 'MM-YYYY') KET_BLTH_PROSES
                      FROM PN.PN_KLAIM                A,
                          PN.PN_KLAIM_BERKALA        B,
                          PN.PN_KLAIM_BERKALA_REKAP  C
                    WHERE A.KODE_KLAIM = B.KODE_KLAIM
                          AND B.KODE_KLAIM = C.KODE_KLAIM
                          AND B.NO_KONFIRMASI = C.NO_KONFIRMASI
                          AND A.KODE_TIPE_KLAIM LIKE 'JPN%'
                          AND NVL (A.STATUS_APPROVAL, 'T') = 'Y'
                          AND NVL (A.STATUS_BATAL, 'T') = 'T'
                          AND NVL (B.STATUS_APPROVAL, 'T') = 'Y'
                          AND B.STATUS_BATAL = 'T'
                          AND C.KD_PRG = 4
                          AND C.STATUS_LUNAS = 'T'
                          AND C.STATUS_BATAL = 'T'
                          AND NVL (C.STATUS_SIAPBAYAR, 'T') = 'Y'
                          -- AND C.NO_PROSES IN (2,3)
                          AND C.STATUS_TRANSFER = 'K'
  ) TT,
  PN.PN_KLAIM_PENERIMA_BERKALA  TT2
  WHERE TT.KODE_KANTOR_PEMBAYARAN = '$KD_KANTOR'
  AND TT.KODE_KLAIM = TT2.KODE_KLAIM
  AND TT.NO_URUT_KELUARGA = TT2.NO_URUT_KELUARGA
  AND NOT EXISTS
  (
      SELECT * FROM PN.PN_AGENDA_KOREKSI_REKENING KOR
      WHERE KOR.KODE_KLAIM = TT.KODE_KLAIM
      AND KOR.STATUS_BATAL = 'T'
      AND KOR.STATUS_APPROVAL = 'T'
      
  )
";

$sql_sudah = "SELECT AR.KODE_AGENDA_KOREKSI, AR.KODE_KANTOR_KOREKSI, (SELECT K.NAMA_KANTOR FROM MS.VW_MS_KANTOR K WHERE K.KODE_KANTOR = AR.KODE_KANTOR_KOREKSI) NAMA_KANTOR, TO_CHAR( AR.TGL_AGENDA_KOREKSI, 'DD-MM-YYYY' ) TGL_AGENDA_KOREKSI, AR.PETUGAS_KOREKSI, (SELECT U.NAMA_USER FROM MS.SC_USER U WHERE U.KODE_USER = AR.PETUGAS_KOREKSI) NAMA_PETUGAS, AR.KETERANGAN_KOREKSI, AR.KODE_KLAIM, AR.NO_KONFIRMASI, AR.NO_PROSES, AR.KD_PRG, AR.BLTH_PROSES, AR.NOMOR_IDENTITAS, AR.NAMA_TK, AR.KPJ, TO_CHAR( AR.TGL_KLAIM, 'DD-MM-YYYY' ) TGL_KLAIM, AR.NOM_MANFAAT, AR.KODE_BANK_PENERIMA, AR.BANK_PENERIMA, AR.NO_REKENING_PENERIMA, AR.NAMA_REKENING_PENERIMA, AR.KETERANGAN_RETUR, AR.KODE_BANK_PENERIMA_BARU, AR.BANK_PENERIMA_BARU, AR.NO_REKENING_PENERIMA_BARU, AR.NAMA_REKENING_PENERIMA_BARU, AR.STATUS_APPROVAL, AR.STATUS_BATAL, AR.PETUGAS_BATAL, AR.TGL_BATAL, AR.KETERANGAN_BATAL, AR.TGL_REKAM, AR.PETUGAS_REKAM, AR.TGL_UBAH, AR.PETUGAS_UBAH, AR.KODE_BANK_PEMBAYAR, AR.KODE_BANK_PEMBAYAR_BARU, AR.NAMA_PENERIMA_MANFAAT, TO_CHAR( AR.TGL_LAHIR_PENERIMA_MANFAAT, 'DD-MM-YYYY' ) TGL_LAHIR FROM PN.PN_AGENDA_KOREKSI_REKENING AR WHERE KODE_KANTOR_KOREKSI = '$KD_KANTOR' ORDER BY TGL_REKAM ASC";

$sql_bank = "SELECT
  metode_transfer,
  nama_bank,
  kode_bank 
  FROM
  (
  SELECT
    'ATB' metode_transfer,
    nama_bank,
    kode_bank 
  FROM
    sijstk.spo_tx_kode_bank a 
  WHERE
    opg_bank = 'BNI' 
    AND status = 'Y' 
    AND tipe_trf = 'ATB' 
    AND kode_bank <> '009' UNION ALL
  SELECT
    'ATR' metode_transfer,
    nama_bank,
    kode_bank 
  FROM
    sijstk.spo_tx_kode_bank a 
  WHERE
    opg_bank = 'BNI' 
    AND status = 'Y' 
    AND kode_bank = '009' 
  ) tt 
  WHERE
  1 = 1
";

$sql_bank_pembayaran = "SELECT KODE_BANK, NAMA_BANK FROM SPO.SPO_BANK WHERE OPG_STATUS = 'Y' AND OPG_MODE IS NOT NULL";

if ($fn == 'getData') {
    # memanggil function get data
    if ($ls_status_proses == 'belum') {
        $sql = $sql_belum;
    } else {
        $sql = $sql_sudah;
    }
    echo getData($logs, $DB, $sql, $ECDB);
} else if ($fn == 'submitData') {
    # memanggil function submit
    echo submitData($logs, $DB);
} else if ($fn == 'cariBank') {
  # memanggil function cari bank
  echo cariBank($logs, $DB, $sql_bank);
} else if ($fn == 'cariBankPembayaran') {
  # memanggil function cari bank
  echo cariBankPembayaran($logs, $wsIp, $ipfwd);
} else if ($fn == 'cekValidasiRekening') {
  echo cekValidasiRekening($logs, $wsIp, $ipfwd);
}

$logs->stop('-', '-', 'Stopping	PN5075');
?>