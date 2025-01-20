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
$reqid_arsip = $_SESSION["USER"];
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

if ($ls_tipe == "select")
{ 
  $ls_search_by = $_POST["search_by"];
  $ls_search_txt = $_POST["search_txt"];
  $ls_page = $_POST["page"];
  $ls_kode_layanan = $_POST["kode_layanan"];
  $ls_page_item = $_POST["page_item"];

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

  if ($ls_search_by != "sc_id_dokumen") {
    $jsondata["ret"] = "-3";
    $jsondata["msg"] = "Masukkan pencarian berdasarkan Kode Klaim";
    echo json_encode($jsondata);
    die();
  }
  if ($ls_search_by == "" || $ls_search_txt == "") {
    $jsondata["ret"] = "-3";
    $jsondata["msg"] = "Masukkan pencarian berdasarkan Kode Klaim";
    echo json_encode($jsondata);
    die();
  }
  
  $sql_kode_kantor= "
  select  kode_kantor
  from    ms.ms_kantor
  where   aktif = 'Y'
          and status_online = 'Y'
          and kode_tipe not in ('1', '2')
  start with kode_kantor = '{$KD_KANTOR}'
  connect by prior kode_kantor = kode_kantor_induk ";
$DB->parse($sql_kode_kantor);
$DB->execute();
$i = 0;
$data_kode_kantor=array();
$temp = "";
while($data = $DB->nextrow()){
  $data_kode_kantor[$i]=$data["KODE_KANTOR"];
  $temp .= "'".$data["KODE_KANTOR"]."',";
  $i++;
}

  $headers = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );
  $arr_order = array( array("key"=> "ID_DOKUMEN", "value"=> "ASC"),array("key"=> "NO_URUT_DOKUMEN", "value"=> "ASC"));
  $arr_search = array();
  if ($search_by == "sc_id_dokumen") {
    if($ls_search_txt !=""){
    $search_id_dokumen = array("key" => "ID_DOKUMEN", "opr" => "=", "value" => "$ls_search_txt");
    array_push($arr_search, $search_id_dokumen);
    
    }
  } else {
    $arr_search = array();
  }
 
  if($ls_kode_layanan !=""){
    $search_kode_layanan = array("key" => "KODE_LAYANAN", "opr" => "=", "value" => "$ls_kode_layanan");
    array_push($arr_search, $search_kode_layanan);
  }
  $search_kd_kantor = array("key" => "KODE_KANTOR", "opr" => "IN", "value" => $data_kode_kantor);
    array_push($arr_search, $search_kd_kantor);
 

    $data = array(
    'chId'      => 'POSTMAN',
    'reqId'     => $USER,
    'pageSize'  => (int)$ls_page_item,
    'pageNumber' => (int)$ls_page,
    'search' => $arr_search,
    'order' => $arr_order
  );
  //var_dump(json_encode($data));

//  $url=$wsIp."/JSDS/GetListDokumenSummary";
$url=$wsIpDocument."/JSDS/GetListDokumenSummary";

  $ch     = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");

  $result = curl_exec($ch);

  if ($result) {
      $ECDB->parse($sql);
     if($ECDB->execute()){ 
       
      echo utf8_encode($result);
    }else{
       $jsondata["ret"] = "-3";
        $jsondata["msg"] = "Terjadi kesalahan, silahkan coba beberapa saat lagi";
      echo json_encode($jsondata);
    }
  } else {
    $jsondata["ret"] = "-3";
    $jsondata["msg"] = "Terjadi kesalahan, silahkan coba beberapa saat lagi";
    echo json_encode($jsondata);
  } 
}else if ($ls_tipe == "generate") {
  $headers = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd
  );
  $id_dokumen_arsip = $_POST['kodeKlaim'];
  
  // bundle & stamp documents
  $sql = "
  SELECT  A.KODE_KLAIM,
          A.KODE_KANTOR,
          A.NO_PENETAPAN,
          A.NAMA_TK,
          A.KPJ,
          A.NOMOR_IDENTITAS,
          A.KODE_TIPE_KLAIM,
          C.BANK_PENERIMA,
          C.NO_REKENING_PENERIMA,
          C.NAMA_REKENING_PENERIMA,
          (SELECT AA.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NAMA_PERUSAHAAN,
          (SELECT AA.NPP FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NPP,
          (SELECT SUM(NOM_PEMBAYARAN) FROM PN.PN_KLAIM_PEMBAYARAN AA WHERE AA.KODE_KLAIM = A.KODE_KLAIM) NOM_PEMBAYARAN,
          (SELECT COUNT(1) FROM PN.PN_ARSIP_DOKUMEN AA WHERE AA.ID_DOKUMEN = A.KODE_KLAIM) JML_DOKUMEN       
  FROM    
          SMILE.PN_KLAIM                  A,
          SMILE.MS_KANTOR                 B,
          SMILE.PN_KLAIM_PENERIMA_MANFAAT C
  WHERE   A.KODE_KANTOR = B.KODE_KANTOR
          AND A.KODE_KLAIM = C.KODE_KLAIM
          AND C.KODE_TIPE_PENERIMA = C.KODE_TIPE_PENERIMA
          AND A.KODE_KLAIM = :P_KODE_KLAIM";

  $proc = $DB->parse($sql);
  oci_bind_by_name($proc, ":P_KODE_KLAIM", $id_dokumen_arsip, 100);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_attr_kode_kantor      			= $row["KODE_KANTOR"];
  $ls_attr_kode_klaim      				= $row["KODE_KLAIM"];
  $ls_attr_no_penetapan    				= $row["NO_PENETAPAN"];
  $ls_attr_nomor_identitas 				= $row["NOMOR_IDENTITAS"];
  $ls_attr_kpj             				= $row["KPJ"];
  $ls_attr_nama_tk         				= $row["NAMA_TK"];
  $ls_attr_bank 				   				= $row["BANK_PENERIMA"];
  $ls_attr_no_rekening 		 				= $row["NO_REKENING_PENERIMA"];
  $ls_attr_nama_pemilik_rekening 	= $row["NAMA_REKENING_PENERIMA"];
  $ls_attr_pph21 					 				= "";
  $ls_attr_npp             				= $row["NPP"];
  $ls_attr_nama_perusahaan 				= $row["NAMA_PERUSAHAAN"];
  $ls_attr_nom_pembayaran  				= $row["NOM_PEMBAYARAN"];
  $ls_attr_jml_berkas 						= $row["JML_DOKUMEN"];
  $ls_attr_keterangan 					  = "";
  $ls_attr_kode_klasifikasi 			= "KU 03.01";
  $ls_attr_kode_transaksi_voucher = "";
  $ls_attr_kode_tipe_klaim      			= $row["KODE_TIPE_KLAIM"];

  $sql_gl_voucher = "
  select  decode (
          a.status_posting, 'Y', 
          (to_char (a.tgl_trans, 'DD-MM-YYYY') || ' ' || a.kode_buku || ' ' || lpad (a.nomor_trans, 8, 0)),
          (to_char (a.tgl_trans, 'DD-MM-YYYY') || ' ' || a.id_dokumen)
          ) kd_trans
  from    lk.gl_voucher a
  where   a.id_dokumen_induk = :p_kode_klaim
          and a.kode_buku = 'T0018'";
  $proc = $DB->parse($sql_gl_voucher);
  oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_attr_kode_transaksi_voucher = $row["KD_TRANS"];
  
  if ($ls_attr_kode_tipe_klaim == "JHT01")
		{
			$ls_kodeJenisArsipSystem = "A00000001";
		}
		else if ($ls_attr_kode_tipe_klaim == "JKM01")
		{
			$ls_kodeJenisArsipSystem = "A00000003";
		}
		else if ($ls_attr_kode_tipe_klaim == "JPN01")
		{
			$ls_kodeJenisArsipSystem = "A00000002";
		}
    else if ($ls_attr_kode_tipe_klaim == "JKP01")
		{
			$ls_kodeJenisArsipSystem = "A00000004";
		}

  $data_stamp = array(
    "chId" => "SMILE",
    "reqId" => $reqid_arsip,
    "idDokumen" => $id_dokumen_arsip,
    "kodePointerArsipSystem" => "PA0001",
    "kodeJenisArsipSystem" => $ls_kodeJenisArsipSystem,
    "attributes" => array(
      array("nama" => "KODE_KANTOR", "value" => "$ls_attr_kode_kantor"),
      array("nama" => "KODE_KLAIM", "value" => "$ls_attr_kode_klaim"),
      array("nama" => "NO_PENETAPAN", "value" => "$ls_attr_no_penetapan"),
      array("nama" => "NIK", "value" => "$ls_attr_nomor_identitas"),
      array("nama" => "KPJ", "value" => "$ls_attr_kpj"),
      array("nama" => "NAMA_TK", "value" => "$ls_attr_nama_tk"),
      array("nama" => "BANK", "value" => "$ls_attr_bank"),
      array("nama" => "NO_REKENING", "value" => "$ls_attr_no_rekening"),
      array("nama" => "NAMA_PEMILIK_REKENING", "value" => "$ls_attr_nama_pemilik_rekening"),
      array("nama" => "PPH21", "value" => "$ls_attr_pph21"),
      array("nama" => "NPP", "value" => "$ls_attr_npp"),
      array("nama" => "NAMA_PERUSAHAAN", "value" => "$ls_attr_nama_perusahaan"),
      array("nama" => "JML_BAYAR", "value" => "$ls_attr_nom_pembayaran"),
      array("nama" => "JML_BERKAS", "value" => "$ls_attr_jml_berkas"),
      array("nama" => "KETERANGAN", "value" => "$ls_attr_keterangan"),
      array("nama" => "KODE_KLASIFIKASI", "value" => "$ls_attr_kode_klasifikasi"),
      array("nama" => "KODE_TRANSAKSI_VOUCHER", "value" => "$ls_attr_kode_transaksi_voucher"),
      array("nama" => "TINGKAT_PERKEMBANGAN", "value" => "ASLI")
    )
  );
  
  
  $result_stamp = api_json_call($wsIpDocument . "/JSDS/StampDocument", $headers, $data_stamp);

  $result_stamp_ret = $result_stamp->ret;
  $result_stamp_msg = $result_stamp->msg;
  if ($result_stamp_ret != "0") {
    $sql = "
    select  distinct 
            (select nama_dokumen from pn.pn_arsip_kode_dokumen aa where aa.kode_dokumen = a.kode_dokumen) nama_dokumen,
            a.keterangan
    from    pn.pn_arsip_dokumen_sign a 
    where   a.id_user_sign is null
            and id_arsip in (
            select  id_arsip
            from    pn.pn_arsip_dokumen aa
            where   aa.id_dokumen = :p_id_dokumen_arsip
            )";
    $proc = $DB->parse($sql);
    oci_bind_by_name($proc, ":p_id_dokumen_arsip", $id_dokumen_arsip, 100);
    $DB->execute();
    $msg_arsip_sign = "";
    while($row = $DB->nextrow()) {
      $msg_arsip_sign .= "<br/>LENGKAPI SETUP " . $row["KETERANGAN"] . " UNTUK DOKUMEN " . $row["NAMA_DOKUMEN"];
    }
    $ln_error_arsip = 1;
    $msg_arsip = $result_stamp_msg . $msg_arsip_sign;
  }
  if ($result_stamp->ret === "0") {
    $jsondata["ret"] = "0";
    $jsondata["msg"] = "Berhasil";
    echo json_encode($jsondata);
  }else{
    $jsondata["ret"] = "-3";
    $jsondata["msg"] = $msg_arsip;
    echo json_encode($jsondata);
  }
  
}




// * END: TAMBAHAN UNTUK PROSES PENGARSIPAN

?>