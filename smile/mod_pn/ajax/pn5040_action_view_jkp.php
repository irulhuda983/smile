<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB   = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$KD_KANTOR  = $_SESSION['kdkantorrole'];
$USER       = $_SESSION["USER"];
$KODE_ROLE  = $_SESSION['regrole'];



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
    $output         = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key     = 'WS-SERVICE-KEY';
    $secret_iv      = 'WS-SERVICE-VALUE';
    // hash
    $key            = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv             = substr(hash('sha256', $secret_iv), 0, 16);
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
$ls_type              = $_POST["types"];
$ls_kode_klaim        = $_POST['kode_klaim'];


if ($ls_type == "query")
{
  $ls_page            = $_POST["page"];
  $ls_page_item       = $_POST["page_item"];
  $ls_page            = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item       = is_numeric($ls_page_item) ? $ls_page_item : "10";
  $start              = (($ls_page -1) * $ls_page_item) + 1;
  $end                = $start + $ls_page_item - 1;


  $sql = "SELECT rownum no,a.* FROM 
            (
              SELECT KPJ,
                    NPP,
                    KODE_DIVISI,
                    TO_CHAR (BLTH_IURAN, 'MM-YYYY') BLTH_IURAN,
                    TO_CHAR (TGL_BAYAR, 'DD-MM-YYYY') TGL_BAYAR,
                    NOM_IUR_REKOMPOSISI_JKK,
                    NOM_IUR_REKOMPOSISI_JKM,
                    NOM_SUBSIDI_PEMERINTAH,
                    NOM_IUR_TOTAL
                FROM PN.PN_KLAIM_TKIURJKP WHERE KODE_KLAIM = :P_KODE_KLAIM
              ORDER BY BLTH_IURAN ASC
            ) A";
  // var_dump($sql);
  $sql_count = "select count(1) rn from ($sql) where 1=1";
  $sql_query = "select * from ($sql) where 1=1 and no between ".$start." and ".$end . " ";

  $proc = $DB->parse($sql_count);
  oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);

  $DB->execute();
  $row = $DB->nextrow();
  $recordsTotal = (float) $row["RN"];
  
  $pages = ceil($recordsTotal / $ls_page_item);
  $proc = $DB->parse($sql_query);
  oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
  oci_bind_by_name($proc, ':P_START', $start);
  oci_bind_by_name($proc, ':P_END', $end);

  if($DB->execute()){ 
    $i      = 0;
    $itotal = 0;
    $jdata  = array();
    while($data = $DB->nextrow()){
      $data["NO"]                       = $start + $i;
      $data["NOM_IUR_REKOMPOSISI_JKK"]  = number_format((float)$data["NOM_IUR_REKOMPOSISI_JKK"],2,".",",");
      $data["NOM_IUR_REKOMPOSISI_JKM"]  = number_format((float)$data["NOM_IUR_REKOMPOSISI_JKM"],2,".",",");
      $data["NOM_SUBSIDI_PEMERINTAH"]   = number_format((float)$data["NOM_SUBSIDI_PEMERINTAH"],2,".",",");
      $data["NOM_IUR_TOTAL"]            = number_format((float)$data["NOM_IUR_TOTAL"],2,".",",");
      
      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"]              = "1";
    $jsondata["start"]            = $start;
    $jsondata["end"]              = $end;
    $jsondata["page"]             = $ls_page;
    $jsondata["recordsTotal"]     = $recordsTotal;
    $jsondata["recordsFiltered"]  = $itotal;
    $jsondata["pages"]            = $pages;
    $jsondata["data"]             = $jdata;
    $jsondata["msg"]              = "SUKSES";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"]              = "-1";
    $jsondata["start"]            = "0";
    $jsondata["end"]              = "0";
    $jsondata["page"]             = "0";
    $jsondata["recordsTotal"]     = "0";
    $jsondata["recordsFiltered"]  = "0";
    $jsondata["pages"]            = "0";
    $jsondata["msg"]              = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else if ($ls_type == "query_info_manfaat")
{
  $ls_kode_profile_tk_jkp = $_POST["kode_profile_tk_jkp"];
  $ls_tahap_jkp           = $_POST["tahap_jkp"];

  $param_bv=[
    ':P_KODE_PROFILE_TK_JKP' => $ls_kode_profile_tk_jkp,
    ':P_TAHAP_JKP' => $ls_tahap_jkp,

  ];
  $sql = "select * from table 
            (
              pn.p_pn_siapkerja2pn.f_get_informasi_manfaat_tk_jkp
              (
                :P_KODE_PROFILE_TK_JKP,
                :P_TAHAP_JKP
              )
            )";

  $proc = $DB->parse($sql);
  foreach($param_bv as $key => $val) {
     oci_bind_by_name($proc, $key, $param_bv[$key]);
  }
  
  if($DB->execute()){ 
    $i      = 0;
    $itotal = 0;
    $jdata  = array();
    while($data = $DB->nextrow()){
      $data["NO"]                      = $start + $i;
      // if($data["BULAN_MANFAAT_KE"]==null){
      //   $data["BULAN_MANFAAT_KE"] = '-';
      // }
      // if($data["BLTH_MANFAAT"]==null){
      //   $data["BLTH_MANFAAT"] = '-';
      // }
      // if($data["NOM_UPAH_PELAPORAN"]==null){
      //   $data["NOM_UPAH_PELAPORAN"] = '<center>-</center>';
      // }
      // if($data["BLTH_UPAH_PELAPORAN"]==null){
      //   $data["BLTH_UPAH_PELAPORAN"] = '-';
      // }
      // if($data["NOM_UPAH_TERHITUNG"]==null){
      //   $data["NOM_UPAH_TERHITUNG"] = '<center>-</center>';
      // }
      // if($data["TARIF_UPAH_TERHITUNG"]==null){
      //   $data["TARIF_UPAH_TERHITUNG"] = '-';
      // }
      // if($data["NOM_MANFAAT"]==null){
      //   $data["NOM_MANFAAT"] = '<center>-</center>';
      // }
      // if($data["STATUS_PENGAJUAN"]==null){
      //   $data["STATUS_PENGAJUAN"] = '-';
      // }
      // if($data["TGL_BAYAR"]==null){
      //   $data["TGL_BAYAR"] = '-';
      // }
     
      
      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"]              = "1";
    $jsondata["data"]             = $jdata;
    $jsondata["msg"]              = "SUKSES";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"]              = "-1";
    $jsondata["msg"]              = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else if ($ls_type == "query_daftar_lamaran")
{
  $ls_kode_klaim        = $_POST['kode_klaim'];

  $sql = "SELECT A.NAMA_PERUSAHAAN_LAMARAN,
              A.TGL_LAMARAN,
              A.POSISI_LAMARAN,
              A.STATUS_LAMARAN_KERJA,
              A.STATUS_INTERVIEW
            FROM PN.PN_KLAIM_JKP_LAMARAN_KERJA A
          WHERE A.KODE_KLAIM = :P_KODE_KLAIM";

  $proc = $DB->parse($sql);
  oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
  
  if($DB->execute()){ 
    $i      = 0;
    $itotal = 0;
    $jdata  = array();
    while($data = $DB->nextrow()){
      $data["NO"]                       = $start + $i;

      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"]              = "1";
    $jsondata["data"]             = $jdata;
    $jsondata["msg"]              = "SUKSES";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"]              = "-1";
    $jsondata["msg"]              = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else if ($ls_type == "query_daftar_konseling")
{
  $ls_kode_klaim        = $_POST['kode_klaim'];

  $sql = "SELECT A.TGL_KONSELING, A.REKOMENDASI_AKTIVITAS, A.NAMA_KONSELOR
            FROM PN.PN_KLAIM_JKP_KONSELING A
          WHERE A.KODE_KLAIM = :P_KODE_KLAIM";

  $proc = $DB->parse($sql);
  oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);

  if($DB->execute()){ 
    $i      = 0;
    $itotal = 0;
    $jdata  = array();
    while($data = $DB->nextrow()){
      $data["NO"]                       = $start + $i;

      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"]              = "1";
    $jsondata["data"]             = $jdata;
    $jsondata["msg"]              = "SUKSES";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"]              = "-1";
    $jsondata["msg"]              = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else if ($ls_type == "query_daftar_pelatihan_kerja")
{
  $ls_kode_klaim        = $_POST['kode_klaim'];

  $sql = "SELECT A.NAMA_BLK,
            A.JENIS_BLK,
            A.JENIS_PELATIHAN,
            A.NAMA_MATERI,
            A.TGL_AWAL_PELATIHAN,
            A.TGL_AKHIR_PELATIHAN,
            A.PERSENTASI_ABSENSI,
            A.STATUS_LULUS
          FROM PN.PN_KLAIM_JKP_PELATIHAN_KERJA A
        WHERE KODE_KLAIM = :P_KODE_KLAIM";

  $proc = $DB->parse($sql);
  oci_bind_by_name($proc, ':P_KODE_KLAIM', $ls_kode_klaim);
  
  if($DB->execute()){ 
    $i      = 0;
    $itotal = 0;
    $jdata  = array();
    while($data = $DB->nextrow()){
      $data["NO"]                 = $start + $i;

      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"]              = "1";
    $jsondata["data"]             = $jdata;
    $jsondata["msg"]              = "SUKSES";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"]              = "-1";
    $jsondata["msg"]              = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else if ($ls_type == "query_daftar_wawancara")
{
  $ls_kode_klaim        = $_POST['kode_klaim'];

  $sql = "SELECT A.POSISI,
            A.NAMA_PERUSAHAAN,
            A.TGL_INTERVIEW,
            A.HASIL_WAWANCARA
          FROM PN.PN_KLAIM_JKP_INTERVIEW A
        WHERE KODE_KLAIM = '{$ls_kode_klaim}'";

  $DB->parse($sql);
  
  if($DB->execute()){ 
    $i      = 0;
    $itotal = 0;
    $jdata  = array();
    while($data = $DB->nextrow()){
      $data["NO"]                 = $start + $i;

      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"]              = "1";
    $jsondata["data"]             = $jdata;
    $jsondata["msg"]              = "SUKSES";
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"]              = "-1";
    $jsondata["msg"]              = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else{
	echo '{"ret":-1,"msg":"Tidak Ada Tipe Yang Dipilih!"}';
}

?>

