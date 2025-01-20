<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];
require_once __DIR__ . "../../logs.php";

// set_error_handler("DefaultGlobalErrorHandler");
$ls_tipe      = $_POST["tipe"];

$logs = new Logs();
$logs->setFunctionName('mod_pn/ajax/pn5076_action');
$logs->setEventName('PN5076 - Approval Koreksi Rekening Pembayaran Klaim Return');
$logs->start();

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

if ($ls_tipe == "select")
{
  $ls_search_by       = $_POST["search_by"];
  $ls_search_txt      = $_POST["search_txt"];
  $ls_search_tgl      = $_POST["search_tgl"];
  $ls_search_status      = $_POST["search_status"];
  $ls_page            = $_POST["page"];
  $ls_page_item       = $_POST["page_item"];
  $ls_page            = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item       = is_numeric($ls_page_item) ? $ls_page_item : "10";
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

  if ($ls_search_txt=="" && $ls_search_tgl !="") {
    $ls_search_txt = $ls_search_tgl;
  } else if ($ls_search_txt == "" && $ls_search_tgl == "" && $ls_search_status) {
    $ls_search_txt = $ls_search_status;
  }

  $ls_jenis_data = $_POST["jenis_data"];

  $conditions = [];
  $searchCriteria = [
    'kodeAgendaKoreksi' => 'tt2.kode_agenda_koreksi',
    'kodeKlaim' => 'tt2.kode_klaim',
    'nomorIdentitas' => 'tt2.nomor_identitas',
    'namaPeserta' => 'tt2.nama_tk',
    'KPJ' => 'tt2.kpj',
    'tanggalKlaim' => 'tt2.tgl_klaim',
    'namaBankPenerima' => 'tt2.bank_penerima',
    'nomorRekeningPenerima' => 'tt2.no_rekening_penerima',
    'namaRekeningPenerima' => 'tt2.nama_rekening_penerima',
    'tanggalKoreksi' => 'tt2.tgl_agenda_koreksi',
    'petugasKoreksi' => 'tt2.petugas_koreksi',
    'kantorKoreksi' => 'tt2.kantor_koreksi',
    'statusKoreksi'=> 'tt2.status_approval'
  ];
  
  if (!empty($ls_search_txt) && array_key_exists($ls_search_by, $searchCriteria)) {
    $condition = $searchCriteria[$ls_search_by];

    if ($condition) {
      if (strpos($ls_search_by, "tanggal") !== false) {
        $conditions[] = "TO_DATE({$condition},'dd/mm/rrrr') = TO_DATE('{$ls_search_txt}','dd/mm/rrrr')";
      } else {
        $conditions[] = "$condition LIKE '%" . trim(addslashes($ls_search_txt)) . "%'";
      }
    }
  }

  if (!empty($conditions)) {
    $conditionClause = " AND " . implode(" AND ", $conditions);
  } else {
    $conditionClause = " AND 1=1";
  }

  $filter_kode_kantor = " AND tt2.kode_kantor_approval = '" . $KD_KANTOR . "'";

  if ($ls_jenis_data == "belum") {
    $sql = "SELECT rownum NO, a.* FROM ( select tt2.kode_klaim, tt2.nomor_identitas, tt2.nama_tk, tt2.kpj, tt2.tgl_klaim, tt2.nom_manfaat, tt2.bank_penerima, tt2.no_rekening_penerima, tt2.nama_rekening_penerima, tt2.keterangan_retur, tt2.kode_agenda_koreksi, tt2.kode_kantor_koreksi, ( SELECT nvl(z.nama_kantor, '') FROM MS.MS_KANTOR z WHERE z.kode_kantor = tt2.kode_kantor_koreksi) nama_kantor_koreksi, tt2.tgl_agenda_koreksi, tt2.petugas_koreksi, ( SELECT nvl(z.nama_user, '') FROM ms.sc_user z WHERE z.kode_user = tt2.petugas_koreksi) nama_petugas_koreksi, tt2.keterangan_koreksi, tt2.status_approval, tt2.kode_bank_penerima_baru, tt2.bank_penerima_baru, tt2.no_rekening_penerima_baru, tt2.nama_rekening_penerima_baru, tt2.no_level, tt2.kode_jabatan, tt2.kode_kantor_approval, tt3.kode_user from ( select tt.kode_klaim, tt.nomor_identitas, tt.nama_tk, tt.kpj, tt.tgl_klaim, tt.nom_manfaat, tt.bank_penerima, tt.no_rekening_penerima, tt.nama_rekening_penerima, tt.keterangan_retur, tt.kode_agenda_koreksi, tt.kode_kantor_koreksi, tt.tgl_agenda_koreksi, tt.petugas_koreksi, tt.keterangan_koreksi, tt.status_approval_koreksi, tt.kode_bank_penerima_baru, tt.bank_penerima_baru, tt.no_rekening_penerima_baru, tt.nama_rekening_penerima_baru, tt.no_level, tt.kode_jabatan, tt.kode_kantor_approval, tt.status_approval from ( select a.kode_klaim, a.nomor_identitas, a.nama_tk, a.kpj, a.tgl_klaim, a.nom_manfaat, a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, a.keterangan_retur, a.kode_agenda_koreksi, a.kode_kantor_koreksi, a.tgl_agenda_koreksi, a.petugas_koreksi, a.keterangan_koreksi, a.status_approval status_approval_koreksi, a.kode_bank_penerima_baru, a.bank_penerima_baru, a.no_rekening_penerima_baru, a.nama_rekening_penerima_baru, b.no_level, b.kode_jabatan, b.kode_kantor kode_kantor_approval, b.status_approval, ( select max (c.no_level) from pn.pn_agenda_koreksi_rekening_approval c where c.kode_agenda_koreksi = b.kode_agenda_koreksi) max_level, ( select count (*) from pn.pn_agenda_koreksi_rekening_approval c where c.kode_agenda_koreksi = b.kode_agenda_koreksi and c.no_level < b.no_level and nvl(c.status_approval, 'T') = 'T' ) count_blmapproval_sblmnya from pn.pn_agenda_koreksi_rekening a, pn.pn_agenda_koreksi_rekening_approval b where nvl(a.status_approval,'T') = 'T' and nvl(a.status_batal,'T') = 'T' and a.kode_agenda_koreksi = b.kode_agenda_koreksi ) tt where nvl (tt.count_blmapproval_sblmnya,0) = 0 ) tt2, ms.ms_pejabat_kantor tt3 where tt2.kode_jabatan = tt3.kode_jabatan and tt2.kode_kantor_approval = tt3.kode_kantor and tt3.kode_user = '$USER' and tt2.status_approval = 'T' $filter_kode_kantor $conditionClause) a";
  } else {
    $sql = "SELECT rownum NO, a.* FROM( SELECT distinct tt2.kode_klaim, tt2.nomor_identitas, tt2.nama_tk, tt2.kpj, tt2.tgl_klaim, tt2.nom_manfaat, tt2.bank_penerima, tt2.no_rekening_penerima, tt2.nama_rekening_penerima, tt2.keterangan_retur, tt2.kode_agenda_koreksi, tt2.kode_kantor_koreksi, ( SELECT nvl(z.nama_kantor, '') FROM MS.MS_KANTOR z WHERE z.kode_kantor = tt2.kode_kantor_koreksi) nama_kantor_koreksi, tt2.tgl_agenda_koreksi, tt2.petugas_koreksi, ( SELECT nvl(z.nama_user, '') FROM ms.sc_user z WHERE z.kode_user = tt2.petugas_koreksi) nama_petugas_koreksi, tt2.keterangan_koreksi, tt2.status_approval_koreksi status_approval, tt2.kode_bank_penerima_baru, tt2.bank_penerima_baru, tt2.no_rekening_penerima_baru, tt2.nama_rekening_penerima_baru, tt2.kode_kantor_approval, tt2.petugas_approval FROM ( SELECT tt.kode_klaim, tt.nomor_identitas, tt.nama_tk, tt.kpj, tt.tgl_klaim, tt.nom_manfaat, tt.bank_penerima, tt.no_rekening_penerima, tt.nama_rekening_penerima, tt.keterangan_retur, tt.kode_agenda_koreksi, tt.kode_kantor_koreksi, tt.tgl_agenda_koreksi, tt.petugas_koreksi, tt.keterangan_koreksi, tt.status_approval_koreksi, tt.kode_bank_penerima_baru, tt.bank_penerima_baru, tt.no_rekening_penerima_baru, tt.nama_rekening_penerima_baru, tt.no_level, tt.kode_jabatan, tt.kode_kantor_approval, tt.petugas_approval, tt.status_approval FROM ( SELECT a.kode_klaim, a.nomor_identitas, a.nama_tk, a.kpj, a.tgl_klaim, a.nom_manfaat, a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, a.keterangan_retur, a.kode_agenda_koreksi, a.kode_kantor_koreksi, a.tgl_agenda_koreksi, a.petugas_koreksi, a.keterangan_koreksi, b.status_approval status_approval_koreksi, a.kode_bank_penerima_baru, a.bank_penerima_baru, a.no_rekening_penerima_baru, a.nama_rekening_penerima_baru, b.no_level, b.kode_jabatan, b.kode_kantor kode_kantor_approval, b.petugas_approval, b.status_approval, ( select max (c.no_level) from pn.pn_agenda_koreksi_rekening_approval c where c.kode_agenda_koreksi = b.kode_agenda_koreksi) max_level, ( select count (*) from pn.pn_agenda_koreksi_rekening_approval c where c.kode_agenda_koreksi = b.kode_agenda_koreksi and c.no_level <= b.no_level and nvl(c.status_approval, 'T') in ('Y','R') ) count_sudahapproval_sblmnya from pn.pn_agenda_koreksi_rekening a, pn.pn_agenda_koreksi_rekening_approval b where 1=1 and nvl(a.status_batal,'T') = 'T' and a.kode_agenda_koreksi = b.kode_agenda_koreksi ) tt where nvl (tt.count_sudahapproval_sblmnya,0) > 0 ) tt2, ms.ms_pejabat_kantor tt3 where tt2.kode_jabatan = tt3.kode_jabatan and tt2.kode_kantor_approval = tt3.kode_kantor and tt2.petugas_approval = '$USER' $filter_kode_kantor $conditionClause) a"; 
  }
  
  $sql_count = "select count(1) rn from ($sql) where 1=1";
  $sql_query = "select * from ($sql) where 1=1 and no between ".$start." and ".$end . " ";

  $DB->parse($sql_count);
  $DB->execute();
  $row = $DB->nextrow();
  $recordsTotal = (float) $row["RN"];

  $pages = ceil($recordsTotal / $ls_page_item);
  $DB->parse($sql_query);

  if ($DB->execute()) {
    $i = 0;
    $itotal = 0;
    $jdata = array();

    while($data = $DB->nextrow()){
      $data["NO"] = $start + $i;
      $jdata[] = $data;
      $i++;
      $itotal++;
    }
  }

  if ($jdata) {
    $jsondata["ret"] = "1";
    $jsondata["start"] = $start;
    $jsondata["end"] = $end;
    $jsondata["page"] = $ls_page;
    $jsondata["recordsTotal"] = $recordsTotal;
    $jsondata["recordsFiltered"] = $itotal;
    $jsondata["pages"] = $pages;
    $jsondata["data"] = $jdata;
    $jsondata["msg"] = "Sukses";
    $logs->info(json_encode($sql), json_encode($jdata), 'Berhasil');
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-2";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";
    $jsondata["pages"] = "0";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    $logs->error(json_encode($sql), json_encode($DB), 'Gagal');
    echo json_encode($jsondata);
  }
} else if ($ls_tipe == "form_detil") {

  $ls_kode_klaim = trim($_POST['kode_klaim']);
  $ls_kode_agenda_koreksi = trim($_POST['kode_agenda_koreksi']);
  $ls_no_level = trim($_POST['no_level']);
  $ls_kode_jabatan = trim($_POST['kode_jabatan']);
  $error = 0;

  if ($ls_no_level && $ls_kode_jabatan) {
    $filter = " AND tt2.kode_klaim = '" . $ls_kode_klaim . "'" . " AND tt2.kode_agenda_koreksi = '" . $ls_kode_agenda_koreksi . "'" . "AND tt2.kode_jabatan = '" . $ls_kode_jabatan . "'" . "AND tt2.no_level = '" . $ls_no_level . "'";
    $ket = "belum";
  } else {
    $filter = " AND tt2.kode_klaim = '" . $ls_kode_klaim . "'" . " AND tt2.kode_agenda_koreksi = '" . $ls_kode_agenda_koreksi . "'";
    $ket = "sudah";
  }
  
   $sql = "SELECT tt2.tgl_lahir_penerima_manfaat, tt2.nama_penerima_manfaat, tt2.kode_bank_pembayar, tt2.kode_bank_pembayar_baru, tt2.kode_klaim, tt2.nomor_identitas, tt2.nama_tk, tt2.kpj, tt2.tgl_klaim, tt2.nom_manfaat, tt2.bank_penerima, tt2.kode_bank_penerima, tt2.no_rekening_penerima, tt2.nama_rekening_penerima, tt2.keterangan_retur, tt2.kode_agenda_koreksi, tt2.kode_kantor_koreksi, tt2.tgl_agenda_koreksi, tt2.petugas_koreksi, tt2.keterangan_koreksi, tt2.status_approval, tt2.kode_bank_penerima_baru, tt2.bank_penerima_baru, tt2.no_rekening_penerima_baru, tt2.nama_rekening_penerima_baru, tt2.kode_kantor_approval,( SELECT nvl(z.nama_kantor, '') FROM MS.MS_KANTOR z WHERE z.kode_kantor = tt2.kode_kantor_koreksi) nama_kantor_koreksi, ( SELECT nvl(z.nama_user, '') FROM ms.sc_user z WHERE z.kode_user = tt2.petugas_koreksi) nama_petugas_koreksi,	tt2.status_approval_koreksi, tt2.no_konfirmasi, tt2.no_proses, tt2.kd_prg, tt2.kode_jabatan, tt2.no_level FROM ( SELECT tt.tgl_lahir_penerima_manfaat, tt.nama_penerima_manfaat, tt.kode_klaim, tt.nomor_identitas, tt.nama_tk, tt.kpj, tt.tgl_klaim, tt.nom_manfaat, tt.bank_penerima, tt.kode_bank_penerima, tt.no_rekening_penerima, tt.nama_rekening_penerima, tt.keterangan_retur, tt.kode_agenda_koreksi, tt.kode_kantor_koreksi, tt.tgl_agenda_koreksi, tt.petugas_koreksi, tt.keterangan_koreksi, tt.status_approval_koreksi, tt.kode_bank_penerima_baru, tt.bank_penerima_baru, tt.no_rekening_penerima_baru, tt.nama_rekening_penerima_baru, tt.no_level, tt.kode_jabatan, tt.kode_kantor_approval, tt.status_approval, tt.no_konfirmasi, tt.no_proses, tt.kd_prg, tt.kode_bank_pembayar, tt.kode_bank_pembayar_baru FROM ( SELECT a.tgl_lahir_penerima_manfaat, a.nama_penerima_manfaat, a.kode_bank_pembayar, a.kode_bank_pembayar_baru, a.kode_klaim, a.nomor_identitas, a.nama_tk, a.kpj, a.tgl_klaim, a.nom_manfaat, a.bank_penerima, a.kode_bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, a.keterangan_retur, a.kode_agenda_koreksi, a.kode_kantor_koreksi, a.tgl_agenda_koreksi, a.petugas_koreksi, a.keterangan_koreksi, a.status_approval status_approval_koreksi, a.kode_bank_penerima_baru, a.bank_penerima_baru, a.no_rekening_penerima_baru, a.nama_rekening_penerima_baru, b.no_level, b.kode_jabatan, b.kode_kantor kode_kantor_approval, b.status_approval, a.no_konfirmasi, a.no_proses, a.kd_prg, ( SELECT max (c.no_level) FROM pn.pn_agenda_koreksi_rekening_approval c WHERE c.kode_agenda_koreksi = b.kode_agenda_koreksi) max_leve FROM pn.pn_agenda_koreksi_rekening a, pn.pn_agenda_koreksi_rekening_approval b WHERE a.kode_agenda_koreksi = b.kode_agenda_koreksi) tt ) tt2, ms.ms_pejabat_kantor tt3 WHERE tt2.kode_jabatan = tt3.kode_jabatan AND tt2.kode_kantor_approval = tt3.kode_kantor $filter";

  $DB->parse($sql);

  if($DB->execute()) {
    $data = $DB->nextrow();
    $logs->info(json_encode($sql), json_encode($data), 'Berhasil');
  } else {
    $error++;
    $logs->error(json_encode($sql), json_encode($DB), 'Gagal');    
  }

  if ($error < 1) {
    $nama_bank_pembayar = '';

    if ($data['KODE_BANK_PEMBAYAR']) {
      $filter_nama_bank = " where kode_bank ='" . $data['KODE_BANK_PEMBAYAR'] . "'";
      $sql_bank_pembayar = "SELECT nama_bank FROM SPO.SPO_BANK $filter_nama_bank";
  
      $ECDB->parse($sql_bank_pembayar);
      
      if ($ECDB->execute()) {
        $dataBank = $ECDB->nextrow();
        $nama_bank_pembayar = $dataBank["NAMA_BANK"];
        $logs->info(json_encode($sql_bank_pembayar), json_encode($nama_bank_pembayar), 'Berhasil');
      } else {
        $error++;
        $logs->error(json_encode($sql_bank_pembayar), json_encode($ECDB), 'Gagal');    
      }
    }
  }

  if ($error < 1) {
    $nama_bank_pembayar_baru = '';
    
    if ($data['KODE_BANK_PEMBAYAR_BARU']) {
      $filter_nama_bank_baru = " where kode_bank ='" . $data['KODE_BANK_PEMBAYAR_BARU'] . "'";
      $sql_bank_pembayar_baru = "SELECT nama_bank FROM SPO.SPO_BANK $filter_nama_bank_baru";
  
      $ECDB->parse($sql_bank_pembayar_baru);
      
      if ($ECDB->execute()) {
        $dataBankBaru = $ECDB->nextrow();
        $nama_bank_pembayar_baru = $dataBankBaru["NAMA_BANK"];
        $logs->info(json_encode($sql_bank_pembayar_baru), json_encode($nama_bank_pembayar_baru), 'Berhasil');
      } else {
        $error++;
        $logs->error(json_encode($sql_bank_pembayar_baru), json_encode($ECDB), 'Gagal');    
      }
    }
  }

  if ($error < 1) {
    $filterApproval = " WHERE a.kode_agenda_koreksi = '" . $data["KODE_AGENDA_KOREKSI"] . "'";
    
    $sql = "SELECT a.kode_agenda_koreksi, a.keterangan_approval, a.kode_agenda_koreksi, a.kode_jabatan,( SELECT nvl(z.nama_jabatan, '') FROM ms_jabatan z WHERE z.kode_jabatan = a.kode_jabatan) nama_jabatan, a.kode_kantor, (SELECT nvl(z.nama_kantor, '') FROM MS.MS_KANTOR z WHERE z.kode_kantor = a.kode_kantor) nama_kantor, a.status_approval, a.tgl_approval, a.petugas_approval, ( SELECT nvl(z.nama_user, '') FROM ms.sc_user z WHERE z.kode_user = a.petugas_approval) nama_petugas_approval, a.no_level FROM pn.PN_AGENDA_KOREKSI_REKENING_APProval a $filterApproval order by a.no_level";

    $DB->parse($sql);
    $dataApproval = array();

    if ($DB->execute()) {  
      $jdataApproval = array();

      while($dataApproval = $DB->nextrow()) {
        $jdataApproval[] = $dataApproval;
      }
      $logs->info(json_encode($sql), json_encode($jdataApproval), 'Berhasil');
    } else {
      $error++;
      $logs->error(json_encode($sql), json_encode($DB), 'Gagal');    
    }
  }

  if ($error > 0) { 
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "0";
    $jsondata["data"] = $data;
    $jsondata["dataApproval"] = $jdataApproval;
    $jsondata["namaBankPembayar"] = $nama_bank_pembayar;
    $jsondata["namaBankPembayarBaru"] = $nama_bank_pembayar_baru;
    $jsondata["ket"] = $ket;
    $jsondata["msg"] = "Sukses";
    echo json_encode($jsondata);
  }

} else if ($ls_tipe == "submit_data") {
  $p_kode_agenda_koreksi    = $_POST["kode_agenda_koreksi"];
  $p_no_level               = $_POST["no_level"];
  $p_kode_jabatan           = $_POST["kode_jabatan"];
  $p_status_approval        = $_POST["statusPersetujuan"];
  $p_keterangan_approval    = $_POST["keteranganPersetujuan"];
  $error = 0; 

	$sql = "BEGIN pn.p_pn_pn5075.x_approval_agenda_koreksi(:p_kode_agenda_koreksi, :p_no_level , :p_kode_jabatan, :p_kode_kantor_approval, :p_petugas_approval, :p_status_approval, :p_keterangan_approval, :p_sukses , :p_mess); END;";

  $proc = $DB->parse($sql);
  oci_bind_by_name($proc, ':p_kode_agenda_koreksi', $p_kode_agenda_koreksi, 100);
  oci_bind_by_name($proc, ':p_no_level', $p_no_level, 30);
  oci_bind_by_name($proc, ':p_kode_jabatan', $p_kode_jabatan, 50);
  oci_bind_by_name($proc, ':p_kode_kantor_approval', $KD_KANTOR, 50);
  oci_bind_by_name($proc, ':p_petugas_approval', $USER, 30);
  oci_bind_by_name($proc, ':p_status_approval', $p_status_approval, 30);
  oci_bind_by_name($proc, ':p_keterangan_approval', $p_keterangan_approval, 1000);
  oci_bind_by_name($proc, ':p_sukses', $ls_sukses, 10);
  oci_bind_by_name($proc, ':p_mess', $ls_mess, 1000);

  if(!$DB->execute()) {
    $error++;
  }
  
  if($error > 0) {  
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Terjadi kesalahan, gagal menyimpan data dengan error: " . $ls_mess;
    $logs->error(json_encode($sql), json_encode('pn.p_pn_pn5075.x_approval_agenda_koreksi'), 'gagal');
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "0";
    $jsondata["data"] = $p_kode_agenda_koreksi;
    $jsondata["msg"] = "Sukses";
    $logs->info(json_encode($sql), json_encode($p_kode_agenda_koreksi), 'Berhasil');
    echo json_encode($jsondata);
  }
}

$logs->stop('-', '-', 'Stopping	PN5076');

?>