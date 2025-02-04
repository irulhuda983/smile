<?php
//17032022 - Permasalahan Berulang
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";

$DB   = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);

/*ini_set("display_errors", "on");
error_reporting(E_ALL);*/

$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER      = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];
$username  = $USER;

if ($USER=="") {
  $json_data["ret"] = "-1";
  $json_data["msg"] = "Session Anda Telah habis silakan login kembali";

  echo json_encode($json_data);
  die();
}

function encrypt_decrypt($action, $string) {
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
function check_path_exist($apiurl) {
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
      CURLOPT_CUSTOMREQUEST => "GET"
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
  return $result->ret;
}

function get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param) {
  global $ipReportServerDocument;
	global $nc_rpt_user_arsip;
	global $nc_rpt_pass_arsip;
	global $nc_rpt_path;
	global $nc_rpt_sid;

  $path = $nc_rpt_path . $ls_modul;
  
  $report["link"]     = $ipReportServerDocument;//$ipReportServer;
	$report["user"]     = $nc_rpt_user_arsip;
	$report["password"] = $nc_rpt_pass_arsip;
	$report["sid"]      = $nc_rpt_sid;
	$report["path"]     = urlencode($path);
	$report["file"]     = $ls_nama_rpt;
	$report["param"]    = str_replace(" ","%26",$ls_user_param);
  $report["param"]    = str_replace("=","%3D",$report["param"]);
  $tipe               = 'PDF';
  
  $link_rpt_server = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
  //  var_dump($link_rpt_server);die();
	return $link_rpt_server;
}

function get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param) {
  global $ipReportServerDocument;
  global $nc_rpt_user_arsip;
  global $nc_rpt_pass_arsip;
  global $nc_rpt_path;
  global $nc_rpt_sid;

  $path = $nc_rpt_path . $ls_modul;
  
  $report["link"]     = "https://rptserver2.bpjsketenagakerjaan.go.id";//$ipReportServer;
  $report["user"]     = "smile";
  $report["password"] = "smilekanharimu";
  $report["sid"]      = "dboltpdrc";
  $report["path"]     = urlencode($path);
  $report["file"]     = $ls_nama_rpt;
  $report["param"]    = str_replace(" ","%26",$ls_user_param);
  $report["param"]    = str_replace("=","%3D",$report["param"]);
  $tipe               = 'PDF';
  
  $link_rpt_server = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
  //  var_dump($link_rpt_server);die();
  return $link_rpt_server;
}

function get_rpt_url_ncreport_jkp($ls_modul, $ls_nama_rpt, $ls_user_param) {
  global $ipReportServerDocument;
	global $nc_rpt_user_arsip;
	global $nc_rpt_pass_arsip;
	global $nc_rpt_path;
	global $nc_rpt_sid;
  global $EC_DBUser;
  global $EC_DBUser;
  global $EC_DBPass;
  global $EC_DBName;

  $path = $nc_rpt_path . $ls_modul;
  
  $report["link"]     = $ipReportServerDocument;//$ipReportServer;
	$report["user"]     = $EC_DBUser;
	$report["password"] = $EC_DBPass;
	$report["sid"]      = $EC_DBName;
	$report["path"]     = urlencode($path);
	$report["file"]     = $ls_nama_rpt;
	$report["param"]    = str_replace(" ","%26",$ls_user_param);
  $report["param"]    = str_replace("=","%3D",$report["param"]);
  $tipe               = 'PDF';
  
  $link_rpt_server = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
  //var_dump($link_rpt_server);die();
	return $link_rpt_server;
}

function get_rpt_url_ncreport_jkp_penetapan($ls_modul, $ls_nama_rpt, $ls_user_param,$ls_id_pointer_asal) {
  global $ipReportServerDocument;
	global $nc_rpt_user;
	global $nc_rpt_pass;
	global $nc_rpt_path;
	global $nc_rpt_sid;
  global $EC_DBUser;
  global $EC_DBPass;
  global $EC_DBName;
  $ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);


  $sql="SELECT bulan_manfaat_ke
         FROM siapkerja.sk_klaim_pengajuan
        WHERE kode_pengajuan = '$ls_id_pointer_asal'";
                  // var_dump($sql); 
                  $ECDB->parse($sql);
                  $ECDB->execute();
                  $row = $ECDB->nextrow();		
                  $bulan_manfaat = $row['BULAN_MANFAAT_KE'];
  if($bulan_manfaat == "1"){
    $ls_nama_rpt = "PNR901115.rdf";
  }else{
    $ls_nama_rpt = "PNR902115.rdf";
  }
  $path = $nc_rpt_path . $ls_modul;
  
  $report["link"]     = $ipReportServerDocument;//$ipReportServer;
	$report["user"]     = $nc_rpt_user;
	$report["password"] = $nc_rpt_pass;
	$report["sid"]      = $nc_rpt_sid;
	$report["path"]     = urlencode($path);
	$report["file"]     = $ls_nama_rpt;
	$report["param"]    = str_replace(" ","%26",$ls_user_param);
  $report["param"]    = str_replace("=","%3D",$report["param"]);
  $tipe               = 'PDF';
  
  $link_rpt_server = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
  //  var_dump($link_rpt_server);die();
	return $link_rpt_server;
}

function get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param) {
  global $ipReportServerDocument;
	global $antrian_rpt_user;
	global $antrian_rpt_pass;
	global $antrian_rpt_path;
	global $antrian_rpt_sid;

  $path = $antrian_rpt_path . $ls_modul;
  
  $report["link"]     = $ipReportServerDocument;
	$report["user"]     = $antrian_rpt_user;
	$report["password"] = $antrian_rpt_pass;
	$report["sid"]      = $antrian_rpt_sid;
	$report["path"]     = urlencode($path);
	$report["file"]     = $ls_nama_rpt;
	$report["param"]    = str_replace(" ","%26",$ls_user_param);
  $report["param"]    = str_replace("=","%3D",$report["param"]);
  $tipe               = 'PDF';
  
  $link_rpt_server = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
  
  // var_dump($link_rpt_server);
	return $link_rpt_server;
}

function get_rpt_url_verifikasi_jkp($ls_modul, $ls_nama_rpt, $ls_user_param) {
  global $ipReportServerDocument;
	global $antrian_rpt_user;
	global $antrian_rpt_pass;
	global $antrian_rpt_path;
	global $antrian_rpt_sid;

  global $EC_DBUser;
  global $EC_DBPass;
  global $EC_DBName;

  $path = $antrian_rpt_path . $ls_modul;
  
  $report["link"]     = $ipReportServerDocument;
	$report["user"]     = $EC_DBUser;
	$report["password"] = $EC_DBPass;
	$report["sid"]      = $EC_DBName;
	$report["path"]     = urlencode($path);
	$report["file"]     = $ls_nama_rpt;
	$report["param"]    = str_replace(" ","%26",$ls_user_param);
  $report["param"]    = str_replace("=","%3D",$report["param"]);
  $tipe               = 'PDF';
  
  $link_rpt_server = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
  
  // var_dump($link_rpt_server);
	return $link_rpt_server;
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

function get_spec_dokumen($kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, $kodeJenisDokumen, $kodeDokumen, $isDocs, $docs, $url){
  global $USER;
  $data_merged = array();

  if($isDocs == 'Y'){
     $data_merged = array(
            "chId" => "SMILE", 
            "reqId" => $USER, 
            "idDokumen" => $kodeKlaim, 
            "kodeJenisDokumen" => $kodeJenisDokumen, 
            "kodeDokumen" => $kodeDokumen, 
            "namaBucketTujuan" => $ls_nama_bucket_storage, 
            "namaFolderTujuan" => $ls_nama_folder_storage, 
            "docs" => $docs
          );
  }else{
     $data_merged = array(
            "chId" => "SMILE", 
            "reqId" => $USER, 
            "idDokumen" => $kodeKlaim, 
            "kodeJenisDokumen" => $kodeJenisDokumen, 
            "kodeDokumen" => $kodeDokumen, 
            "urlDokumen" => $url,
            "namaBucketTujuan" => $ls_nama_bucket_storage, 
            "namaFolderTujuan" => $ls_nama_folder_storage
          );
  }
  //  var_dump($data_merged);
    // die();
   return $data_merged;
}

function dashesToCamelCase($string) {
  $str = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($string))));
  if (true) {
    $str[0] = strtolower($str[0]);
  }
  return $str;
}

function get_dokumen_merge_bpjstku($ls_kode_booking){
  global $wsIpStorage;
  global $ECDB;
  global $USER;

  $sql="
          SELECT CASE
          WHEN A.KODE_DOKUMEN = 'D084'
          THEN
                 NAMA
              || ', '
              || 'Status Valid Identitas : '
              || STATUS_VALID_IDENTITAS
          ELSE
              NAMA
          END
              PEMILIK,
          A.MIME_TYPE,
          A.NAMA_DOKUMEN,
          '$wsIpStorage' || PATH_URL
              AS URL_DOKUMEN,
            '$USER'
          || '/'
          || KODE_KANTOR
          || '/'
          || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
              INFO_CETAK
          FROM (  SELECT KODE_BOOKING,
                (SELECT KODE_KANTOR
                   FROM BPJSTKU.VAS_KLAIM Z
                  WHERE Z.KODE_BOOKING = Y.KODE_BOOKING
                 UNION
                 SELECT KODE_KANTOR
                   FROM BPJSTKU.VAS_KLAIM_HIST W
                  WHERE W.KODE_BOOKING = Y.KODE_BOOKING)
                    KODE_KANTOR,
                (SELECT NAMA
                   FROM BPJSTKU.VAS_KLAIM Z
                  WHERE Z.KODE_BOOKING = Y.KODE_BOOKING
                 UNION
                 SELECT NAMA
                   FROM BPJSTKU.VAS_KLAIM_HIST W
                  WHERE W.KODE_BOOKING = Y.KODE_BOOKING)
                    NAMA,
                KODE_DOKUMEN,
                MIME_TYPE,
                (SELECT NAMA_DOKUMEN
                   FROM BPJSTKU.VAS_KODE_DOKUMEN X
                  WHERE X.KODE_DOKUMEN = Y.KODE_DOKUMEN)
                    NAMA_DOKUMEN,
                (SELECT NO_URUT
                   FROM BPJSTKU.VAS_KODE_DOKUMEN X
                  WHERE X.KODE_DOKUMEN = Y.KODE_DOKUMEN)
                    NO_URUT,
                PATH_URL,
                (SELECT STATUS_SUBMIT_DOKUMEN
                   FROM BPJSTKU.VAS_KLAIM Z
                  WHERE Z.KODE_BOOKING = Y.KODE_BOOKING
                 UNION
                 SELECT STATUS_SUBMIT_DOKUMEN
                   FROM BPJSTKU.VAS_KLAIM_HIST W
                  WHERE W.KODE_BOOKING = Y.KODE_BOOKING AND ROWNUM = 1)
                    STATUS_SUBMIT_DOKUMEN,
                DECODE ((SELECT STATUS_VALID_IDENTITAS
                           FROM BPJSTKU.VAS_KLAIM Z
                          WHERE Z.KODE_BOOKING = Y.KODE_BOOKING
                         UNION
                         SELECT STATUS_VALID_IDENTITAS
                           FROM BPJSTKU.VAS_KLAIM_HIST W
                          WHERE W.KODE_BOOKING = Y.KODE_BOOKING),
                        'Y', 'YA',
                        'TIDAK')
                    STATUS_VALID_IDENTITAS
           FROM BPJSTKU.VAS_KLAIM_DOKUMEN Y
          WHERE KODE_BOOKING = '$ls_kode_booking'
          ORDER BY NO_URUT ASC) A
          WHERE A.PATH_URL IS NOT NULL";
          
          
                            
          $ECDB->parse($sql);
          if($ECDB->execute()){ 
            $i = 0;
            $itotal = 0;
            $jdata = array();
            while($data = $ECDB->nextrow()){
              $hasil = array();
              foreach( $data as $key => $value ){
              $hasil[dashesToCamelCase($key)] = $value;
                // echo $value;
            } 
              $jdata[] = $hasil;
              $i++;
              $itotal++;
            } 
          }

    return $jdata;
}

function get_dokumen_merge_siapkerja($ls_kodeKlaim){
  global $wsIpStorage;
  global $ECDB;
  global $USER;

  $sql="
  SELECT  a.nama as
  pemilik,
  a.mime_type,
  a.nama_dokumen,
  '$wsIpStorage' || path_url
  AS url_dokumen,
  '$USER'
  || '/'
  || kode_kantor
  || '/'
  || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
  info_cetak
  FROM (  SELECT kode_pengajuan,
    (SELECT kode_kantor
      FROM siapkerja.sk_klaim_pengajuan z
      WHERE z.kode_pengajuan = y.kode_pengajuan)
      kode_kantor,
    (SELECT nama_tk
      FROM siapkerja.sk_klaim_pengajuan z
      WHERE z.kode_pengajuan = y.kode_pengajuan)
      nama,
    kode_dokumen,
    mime_type,
    (SELECT nama_dokumen
      FROM siapkerja.sk_kode_dokumen_klaim x
      WHERE x.kode_dokumen = y.kode_dokumen)
      nama_dokumen,
    path_url
  FROM  siapkerja.sk_klaim_pengajuan_dokumen y
  WHERE kode_pengajuan in(select kode_pengajuan from siapkerja.sk_klaim_pengajuan where kode_klaim ='$ls_kodeKlaim')) a
  WHERE a.path_url IS NOT NULL";
          
          
                            
          $ECDB->parse($sql);
          if($ECDB->execute()){ 
            $i = 0;
            $itotal = 0;
            $jdata = array();
            while($data = $ECDB->nextrow()){
              $hasil = array();
              foreach( $data as $key => $value ){
              $hasil[dashesToCamelCase($key)] = $value;
                // echo $value;
            } 
              $jdata[] = $hasil;
              $i++;
              $itotal++;
            } 
          }
    return $jdata;
}

function get_dokumen_merge_tambahan($ls_kodeKlaim){
  global $wsIpStorage;
  global $DB;
  global $USER;
            $sql_dokumen_elaborasi = " 
                  SELECT 
                  'DOKUMEN ELABORASI' PEMILIK,
                  MIME_TYPE,
                  trim(NAMA_DOKUMEN_TAMBAHAN) NAMA_DOKUMEN, 
                  '$wsIpStorage' || PATH_URL AS URL_DOKUMEN,
                  '$USER'
                  || '/'
                  || (SELECT KODE_KANTOR FROM PN.PN_KLAIM A WHERE A.KODE_KLAIM = KODE_KLAIM AND ROWNUM=1) 
                  || '/'
                  || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
                    INFO_CETAK
                  from PN.PN_KLAIM_DOKUMEN_TAMBAHAN
                  WHERE KODE_KLAIM = '$ls_kodeKlaim'
                  ORDER BY NO_URUT ASC
              ";
              $DB->parse($sql_dokumen_elaborasi);
              if($DB->execute()){ 
                $i = 0;
                $itotal = 0;
                $jdata = array();
                while($data = $DB->nextrow()){
                $hasil = array();
                foreach( $data as $key => $value ){
                $hasil[dashesToCamelCase($key)] = $value;
                //  echo $hasil;
                //  die();
                } 
                $jdata[] = $hasil;
                $i++;
                $itotal++;
                } 
              }
              // echo $jdata;
              //    die();
  return $jdata;
}

function get_dokumen_merge_lapakasik($ls_kode_booking){
  global $wsIpStorage;
  global $ECDB;

   $sql_lainnya="SELECT CASE
   WHEN a.kode_dokumen = 'D084'
   THEN
          nama
       || ' '
       || ', Status Valid Identitas : '
       || status_valid_identitas
   ELSE
       nama
END
   pemilik,
a.mime_type,
a.nama_dokumen,
'$wsIpStorage' || path_url
   AS url_dokumen,
  '$USER'
|| '/'
|| kode_kantor
|| '/'
|| TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
   info_cetak
FROM (  SELECT kode_booking,
         (SELECT kode_kantor
            FROM antrian.atr_booking z
           WHERE z.kode_booking = y.kode_booking
          UNION
          SELECT kode_kantor
            FROM antrian.atr_booking_hist w
           WHERE w.kode_booking = y.kode_booking)
             kode_kantor,
         (SELECT nama
            FROM antrian.atr_booking z
           WHERE z.kode_booking = y.kode_booking
          UNION
          SELECT nama
            FROM antrian.atr_booking_hist w
           WHERE w.kode_booking = y.kode_booking)
             nama,
         DECODE ((SELECT status_valid_identitas
                    FROM antrian.atr_booking z
                   WHERE z.kode_booking = y.kode_booking
                  UNION
                  SELECT status_valid_identitas
                    FROM antrian.atr_booking_hist w
                   WHERE w.kode_booking = y.kode_booking),
                 'Y', 'YA',
                 'TIDAK')
             status_valid_identitas,
         kode_dokumen,
         mime_type,
         (SELECT nama_dokumen
            FROM antrian.atr_kode_dokumen x
           WHERE x.kode_dokumen = y.kode_dokumen)
             nama_dokumen,
         (SELECT no_urut
            FROM antrian.atr_kode_dokumen x
           WHERE x.kode_dokumen = y.kode_dokumen)
             no_urut,
         path_url,
         (SELECT status_submit_dokumen
            FROM antrian.atr_booking z
           WHERE z.kode_booking = y.kode_booking
          UNION
          SELECT status_submit_dokumen
            FROM antrian.atr_booking_hist w
           WHERE w.kode_booking = y.kode_booking AND ROWNUM = 1)
             status_submit_dokumen
    FROM antrian.atr_booking_dokumen y
   WHERE kode_booking = '$ls_kode_booking'
ORDER BY no_urut ASC) a
WHERE a.path_url IS NOT NULL
UNION ALL
SELECT a.pemilik,
a.mime_type,
a.nama_dokumen,
'$wsIpStorage' || a.path_url
   AS url_dokumen,
  '$USER'
|| '/'
|| kode_kantor
|| '/'
|| TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
   info_cetak
FROM (SELECT mime_type,
       (SELECT nama
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT nama
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           pemilik,
       (SELECT nama_dokumen
          FROM antrian.atr_kode_dokumen x
         WHERE x.kode_dokumen = y.kode_dokumen)
           nama_dokumen,
       path_url,
       (SELECT kode_kantor
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT kode_kantor
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           kode_kantor
  FROM antrian.atr_booking_kartulain_dokumen y
 WHERE path_url IS NOT NULL AND kode_booking = '$ls_kode_booking' AND length(petugas_rekam) > 8 order by kode_dokumen asc) a
UNION ALL
SELECT a.pemilik,
a.mime_type,
a.nama_dokumen,
'$wsIpStorage' || a.path_url
   AS url_dokumen,
  '$USER'
|| '/'
|| kode_kantor
|| '/'
|| TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
   info_cetak
FROM (SELECT mime_type,
       (SELECT nama
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT nama
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           pemilik,
       nama_dokumen_lainnya
           nama_dokumen,
       path_url,
       (SELECT kode_kantor
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT kode_kantor
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           kode_kantor
  FROM antrian.atr_booking_dokumen_lain y
 WHERE path_url IS NOT NULL AND kode_booking = '$ls_kode_booking' AND length(petugas_rekam) > 8 order by kode_dokumen asc) a
 UNION ALL
SELECT a.pemilik,
a.mime_type,
a.nama_dokumen,
'$wsIpStorage' || a.path_url
   AS url_dokumen,
  '$USER'
|| '/'
|| kode_kantor
|| '/'
|| TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
   info_cetak
FROM (SELECT mime_type,
       (SELECT nama
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT nama
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           pemilik,
       (SELECT nama_dokumen
          FROM antrian.atr_kode_dokumen x
         WHERE x.kode_dokumen = y.kode_dokumen)
           nama_dokumen,
       path_url,
       (SELECT kode_kantor
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT kode_kantor
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           kode_kantor
  FROM antrian.atr_booking_kartulain_dokumen y
 WHERE path_url IS NOT NULL AND kode_booking = '$ls_kode_booking' AND length(petugas_rekam) <= 8 order by kode_dokumen asc) a
 UNION ALL
SELECT a.pemilik,
a.mime_type,
a.nama_dokumen,
'$wsIpStorage' || a.path_url
   AS url_dokumen,
  '$USER'
|| '/'
|| kode_kantor
|| '/'
|| TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
   info_cetak
FROM (SELECT mime_type,
       (SELECT nama
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT nama
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           pemilik,
       nama_dokumen_lainnya
           nama_dokumen,
       path_url,
       (SELECT kode_kantor
          FROM antrian.atr_booking z
         WHERE z.kode_booking = y.kode_booking
        UNION
        SELECT kode_kantor
          FROM antrian.atr_booking_hist w
         WHERE w.kode_booking = y.kode_booking)
           kode_kantor
  FROM antrian.atr_booking_dokumen_lain y
 WHERE path_url IS NOT NULL AND kode_booking = '$ls_kode_booking' AND length(petugas_rekam) <= 8  order by kode_dokumen asc) a";
  // var_dump($sql_lainnya);die();
          $ECDB->parse($sql_lainnya);
          if($ECDB->execute()){ 
            $i = 0;
            $itotal = 0;
            $jdata = array();
            while($data = $ECDB->nextrow()){
              $hasil = array();
              foreach( $data as $key => $value ){
              $hasil[dashesToCamelCase($key)] = $value;
                // echo $value;
            } 
              $jdata[] = $hasil;
              $i++;
              $itotal++;
            } 
          }
    return $jdata;
}

function get_dokumen_merge_smile($ls_kodeKlaim){
  global $wsIpStorage;
  global $DB;
  global $USER;

   $sql_lainnya="
							select
							PEMILIK,
							MIME_TYPE,
							NAMA_DOKUMEN,
							URL_DOKUMEN,
              INFO_CETAK,
              FLAG_MANDATORY,
              FLAG_EMPTY
							from
							(
							  select 
							  1 JENIS_DOKUMEN,
							  a.NO_URUT,
							  (
								select b.NAMA_TK from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
							  ) PEMILIK,
							  a.MIME_TYPE,
							  (
								select c.NAMA_DOKUMEN from PN.PN_KODE_DOKUMEN c
								where c.KODE_DOKUMEN = a.KODE_DOKUMEN
								and rownum = 1
							  ) NAMA_DOKUMEN,
							  ('$wsIpStorage' || a.URL) URL_DOKUMEN,
							  '$USER' || '/' || 
							  (
								select b.KODE_KANTOR from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
                ) || '/' || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR') INFO_CETAK,
                case when a.URL is null then 'Y' else 'T' end FLAG_EMPTY,
                a.flag_mandatory
							  from PN.PN_KLAIM_DOKUMEN a
							  where SYARAT_TAHAP_KE = '1' 
							  and KODE_KLAIM = '$ls_kodeKlaim' and url is not null
							  UNION ALL
							  select 
							  2 JENIS_DOKUMEN,
							  a.NO_URUT,
							  (
								select b.NAMA_TK from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
							  ) PEMILIK,
							  a.MIME_TYPE,
							  a.NAMA_DOKUMEN_TAMBAHAN NAMA_DOKUMEN,
							  ('$wsIpStorage' || a.PATH_URL) URL_DOKUMEN,
							  '$USER' || '/' || 
							  (
								select b.KODE_KANTOR from PN.PN_KLAIM b
								where b.kode_klaim = a.kode_klaim
                ) || '/' || TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR') INFO_CETAK,
                'T' flag_mandatory,
                case when a.PATH_URL is null then 'Y' else 'T' end FLAG_EMPTY
							  from PN.PN_KLAIM_DOKUMEN_TAMBAHAN a
							  where SYARAT_TAHAP_KE = '1' 
							  and KODE_KLAIM = '$ls_kodeKlaim'
              )
              where   FLAG_MANDATORY = 'Y' or FLAG_EMPTY = 'T'  
							order by JENIS_DOKUMEN asc, NO_URUT asc
						";
            //  var_dump($sql_lainnya);die();
          $DB->parse($sql_lainnya);
          if($DB->execute()){ 
            $i = 0;
            $itotal = 0;
            $jdata = array();
            while($data = $DB->nextrow()){
              $hasil = array();
              foreach( $data as $key => $value ){
              $hasil[dashesToCamelCase($key)] = $value;
                // echo $value;
            } 
              $jdata[] = $hasil;
              $i++;
              $itotal++;
            } 
          }
    return $jdata;
}

function isMergeDoc($ls_kodeDokumen){
  if($ls_kodeDokumen == 'JD105-D1001' || $ls_kodeDokumen == 'JD105-D1012'  || $ls_kodeDokumen == 'JD101-D1001'  || $ls_kodeDokumen == 'JD101-D1012' ||$ls_kodeDokumen == 'JD102-D1008' || $ls_kodeDokumen == 'JD102-D1009' || $ls_kodeDokumen == 'JD103-D1012' || $ls_kodeDokumen == 'JD103-D1011' || $ls_kodeDokumen == 'JD104-D1012' || $ls_kodeDokumen == 'JD104-D1013' || $ls_kodeDokumen == 'JD107-D1008'){
    return true;
  }else{
    return false;
  }
}

function does_url_exists($url) {
  $ch = curl_init($url);
  curl_setopt($ch, CURLOPT_NOBODY, true);
  curl_exec($ch);
  $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

  if ($code == 200) {
      $status = true;
  } else {
      $status = false;
  }
  curl_close($ch);
  return $status;
}

set_error_handler("DefaultGlobalErrorHandler");

$ls_tipe = !isset($_GET['tipe']) ? $_POST["tipe"] : $_GET["tipe"];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if ($ls_tipe == "select") {
    $ls_search_by  = $_POST["search_by"];
    $ls_search_txt = $_POST["search_txt"];
    $ls_page       = $_POST["page"];
    $ls_page_item  = $_POST["page_item"];

    $ls_page      = is_numeric($ls_page) ? $ls_page : "1";
    $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
    
    $start  = (($ls_page -1) * $ls_page_item) + 1;
    $end    = $start + $ls_page_item - 1;
    
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
    connect by prior kode_kantor = kode_kantor_induk
    ";
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
    }
    
    $search_kd_kantor = array("key" => "KODE_KANTOR", "opr" => "IN", "value" => $data_kode_kantor);
    array_push($arr_search, $search_kd_kantor);

      $data = array(
      'chId'        => 'POSTMAN',
      'reqId'       => $USER,
      'pageSize'    => (int)$ls_page_item,
      'pageNumber'  => (int)$ls_page,
      'search'      => $arr_search,
      'order'       => $arr_order
    );

    $url = $wsIpDocument."/JSDS/GetListDokumen";
    $ch = curl_init();
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
        
        $resultData = json_decode($result);
        $newDatas   = $resultData->data;
        if(ExtendedFunction::count($newDatas) > 0){
          $newDataPush=array();
          foreach($newDatas as $newData){
            $urlPathDok                              = $newData->urlPathDok;
            $urlPathDokSigned                        = $newData->urlPathDokSigned;
            $newData->pathUrlEncrypturlPathDok       = encrypt_decrypt('encrypt',$urlPathDok);
            $newData->pathUrlEncrypturlPathDokSigned = encrypt_decrypt('encrypt',$urlPathDokSigned);
            array_push($newDataPush,$newData);
          }
         
        }else{
          $newDataPush = $resultData->data;
        };
        $jsondata["ret"]          = "0";
        $jsondata["msg"]          = ExtendedFunction::count($newDatas);
        $jsondata["data"]         = $newDataPush;
        $jsondata["recordsTotal"] = $resultData->recordsTotal;
        $jsondata["start"]        = $resultData->start;
        $jsondata["end"]          = $resultData->end;
        $jsondata["pages"]        = $resultData->pages;
        echo json_encode($jsondata);
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
  } else if($ls_tipe=="generate") {  
    $ls_kodeJenisDokumen = $_POST["kodeJenisDokumen"];
    $ls_kodeDokumen      = $_POST["kodeDokumen"];
    $ls_kodeKlaim        = $_POST["kodeKlaim"];

    $headers = array(
      'Content-Type: application/json',
      'X-Forwarded-For: ' . $ipfwd
    );

    // start - get info dokumen klaim
    $sql = "
    select 
            a.kode_klaim, a.no_klaim, a.kode_kantor, a.kode_segmen, a.kode_perusahaan, 
            (select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) nama_perusahaan,
            (select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) npp, 
            a.kode_divisi, (select nama_divisi from sijstk.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_segmen = a.kode_segmen and kode_divisi=a.kode_divisi) nama_divisi,
            a.kode_proyek, (select nama_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek) nama_proyek, 
            (select no_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek) no_proyek, 
            a.kode_tk, a.nama_tk, a.kpj,
            a.nomor_identitas, a.jenis_identitas, a.kode_kantor_tk, substr(a.kode_tipe_klaim,1,3) jenis_klaim, 
            a.kode_tipe_klaim, (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim) nama_tipe_klaim, 
            a.kode_sebab_klaim, (select nama_sebab_klaim from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) nama_sebab_klaim,
            (select keyword from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) keyword_sebab_klaim,
            (select nvl(flag_meninggal,'T') from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) flag_meninggal,
            (select nvl(flag_agenda_12,'T') from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) flag_agenda_12,
            to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
            to_char(a.tgl_lapor,'dd/mm/yyyy') tgl_lapor,     
            a.kanal_pelayanan, a.flag_rtw, a.keterangan,               
            a.kode_pelaporan, nvl(a.flag_rtw,'T') flag_rtw,
            to_char(a.tgl_kecelakaan,'dd/mm/yyyy') tgl_kecelakaan, 
            a.kode_jam_kecelakaan, (select keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and kode = a.kode_jam_kecelakaan) nama_jam_kecelakaan, 
            a.kode_jenis_kasus, (select nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus=a.kode_jenis_kasus) nama_jenis_kasus, 
            a.kode_lokasi_kecelakaan, (select nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where kode_lokasi_kecelakaan=a.kode_lokasi_kecelakaan) nama_lokasi_kecelakaan,
            a.nama_tempat_kecelakaan, 
            a.kode_tindakan_bahaya, 
            a.kode_kondisi_bahaya, 
            a.kode_corak, (select keterangan from sijstk.ms_lookup where tipe='KLMCORAK' and kode=a.kode_corak) nama_corak,
            a.kode_sumber_cedera, (select keterangan from sijstk.ms_lookup where tipe='KLMSMBRCDR' and kode=a.kode_sumber_cedera) nama_sumber_cedera,
            a.kode_bagian_sakit, (select keterangan from sijstk.ms_lookup where tipe='KLMBGSAKIT' and kode=a.kode_bagian_sakit) nama_bagian_sakit,
            a.kode_akibat_diderita, (select nama_akibat_diderita from sijstk.pn_kode_akibat_diderita where kode_akibat_diderita=a.kode_akibat_diderita) nama_akibat_diderita,
            a.kode_lama_bekerja, 
            a.kode_penyakit_timbul, 
            a.kode_tipe_upah, 
            a.nom_upah_terakhir, to_char(a.blth_upah_terakhir,'dd/mm/yyyy') blth_upah_terakhir, a.kode_tempat_perawatan, a.kode_berobat_jalan, 
            a.kode_ppk, (select nama_faskes from sijstk.tc_faskes where kode_faskes = a.kode_ppk) nama_ppk,
            a.nama_faskes_reimburse, a.kode_kondisi_terakhir, 
            to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, 
            to_char(a.tgl_kematian,'dd/mm/yyyy') tgl_kematian, 
            to_char(a.tgl_mulai_pensiun,'dd/mm/yyyy') tgl_mulai_pensiun, 
            a.status_pernikahan, a.ket_tambahan,
            nvl(a.status_kelayakan,'B') status_kelayakan, a.ket_kelayakan,
            nvl(a.status_submit_agenda,'T') status_submit_agenda, tgl_submit_agenda, petugas_submit_agenda, 
            nvl(a.status_submit_pengajuan,'T') status_submit_pengajuan, tgl_submit_pengajuan, petugas_submit_pengajuan, 
            nvl(a.status_submit_agenda2,'T') status_submit_agenda2, tgl_submit_agenda2, petugas_submit_agenda2, 
            nvl(a.status_submit_penetapan,'T') status_submit_penetapan, tgl_submit_penetapan, petugas_submit_penetapan,
            to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, a.no_penetapan,a.petugas_penetapan, 
            nvl(a.status_approval,'T') status_approval, tgl_approval, petugas_approval,							  
            nvl(a.status_batal,'T') status_batal, a.tgl_batal, a.petugas_batal, a.ket_batal, 
            nvl(a.status_lunas,'T') status_lunas, a.tgl_lunas, a.petugas_lunas,
            a.kode_klaim_induk, a.kode_klaim_anak,
            to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, a.status_kepesertaan, 
            a.kode_perlindungan, to_char(a.tgl_awal_perlindungan,'dd/mm/yyyy') tgl_awal_perlindungan, 
            to_char(a.tgl_akhir_perlindungan,'dd/mm/yyyy') tgl_akhir_perlindungan,
            to_char(a.tgl_awal_perlindungan,'dd/mm/yyyy')||' s.d '||to_char(a.tgl_akhir_perlindungan,'dd/mm/yyyy') ket_masa_perlindungan,									
            a.status_klaim, a.kode_pointer_asal, a.id_pointer_asal, a.tipe_pelaksana_kegiatan, a.nama_pelaksana_kegiatan,
            case when a.kode_pointer_asal = 'PROMOTIF' then
                (   select x.kode_kegiatan||'-'||x.nama_sub_kegiatan||'-'||x.nama_detil_kegiatan from sijstk.pn_promotif_realisasi x
                    where kode_realisasi = a.id_pointer_asal          
                ) 
            else
                'KLAIM'
            end nama_kegiatan,
            (
              select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
              where x.kode_klaim = a.kode_klaim
              and x.kode_manfaat = y.kode_manfaat
              and nvl(y.flag_berkala,'T')='Y'
              and nvl(x.nom_biaya_disetujui,0)<>0
            ) cnt_berkala,
            (
              select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
              where x.kode_klaim = a.kode_klaim
              and x.kode_manfaat = y.kode_manfaat
              and nvl(y.flag_berkala,'T')='T'
              and nvl(x.nom_biaya_disetujui,0)<>0
            ) cnt_lumpsum,
            (
              select count(*) from sijstk.pn_klaim_penerima_berkala x
              where x.kode_klaim = a.kode_klaim
            ) cnt_penerima_berkala,
            nvl(status_cek_amalgamasi,'T') status_cek_amalgamasi,
            a.negara_penempatan, a.tipe_negara_kejadian 																		
      from  sijstk.pn_klaim a
      where kode_klaim = '$ls_kodeKlaim'
    ";

      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_kode_kantor 							= $row['KODE_KANTOR'];
      $ls_kode_klaim 						 		= $row['KODE_KLAIM'];		
      $ls_no_klaim 									= $row['NO_KLAIM'];
      $ls_kode_kantor 							= $row['KODE_KANTOR'];
      $ls_kode_segmen 							= $row['KODE_SEGMEN'];
      $ls_kode_perusahaan 					= $row['KODE_PERUSAHAAN'];
      $ls_nama_perusahaan						= $row['NAMA_PERUSAHAAN'];
      $ls_npp 											= $row['NPP'];
      $ls_kode_divisi 							= $row['KODE_DIVISI'];
      $ls_nama_divisi 							= $row['NAMA_DIVISI'];
      $ls_kode_proyek 							= $row['KODE_PROYEK'];
      $ls_nama_proyek  							= $row['NAMA_PROYEK'];
			$ls_no_proyek  								= $row['NO_PROYEK'];
      $ls_kode_tk 									= $row['KODE_TK'];
      $ls_kpj 											= $row['KPJ'];
      $ls_nama_tk 									= $row['NAMA_TK'];
      $ls_nomor_identitas 					= $row['NOMOR_IDENTITAS'];
      $ls_jenis_identitas 					= $row['JENIS_IDENTITAS'];
      $ls_kode_kantor_tk 						= $row['KODE_KANTOR_TK'];
      $ls_kode_tipe_klaim 					= $row['KODE_TIPE_KLAIM'];
      $ls_nama_tipe_klaim						= $row['NAMA_TIPE_KLAIM'];
      $ls_kode_sebab_klaim 					= $row['KODE_SEBAB_KLAIM'];
      $ls_nama_sebab_klaim 					= $row['NAMA_SEBAB_KLAIM'];
			$ls_keyword_sebab_klaim				= $row['KEYWORD_SEBAB_KLAIM'];
			$ls_flag_agenda_12 						= $row['FLAG_AGENDA_12'];
  		$ls_jenis_klaim 							= $row['JENIS_KLAIM'];
      $ld_tgl_klaim 								= $row['TGL_KLAIM'];
      $ld_tgl_lapor 								= $row['TGL_LAPOR'];
  		$ld_tgl_kejadian							= $row['TGL_KEJADIAN'];
  		$ls_status_kepesertaan 				= $row['STATUS_KEPESERTAAN'];
    	$ls_kode_perlindungan					= $row['KODE_PERLINDUNGAN'];
  		$ld_tgl_awal_perlindungan			= $row['TGL_AWAL_PERLINDUNGAN'];
  		$ld_tgl_akhir_perlindungan 		= $row['TGL_AKHIR_PERLINDUNGAN'];
  		$ls_ket_masa_perlindungan	 		= $row['KET_MASA_PERLINDUNGAN'];
			$ls_negara_penempatan	 				= $row['NEGARA_PENEMPATAN'];
			$ls_tipe_negara_kejadian			= $row['TIPE_NEGARA_KEJADIAN'];		
      $ls_keterangan 								= $row['KETERANGAN'];	    
      $ls_kode_pelaporan 						= $row['KODE_PELAPORAN'];
      $ls_kanal_pelayanan 					= $row['KANAL_PELAYANAN'];
      $ls_flag_rtw 									= $row['FLAG_RTW'];
      $ls_kode_klaim_induk 					= $row['KODE_KLAIM_INDUK'];
      $ls_kode_klaim_anak 					= $row['KODE_KLAIM_ANAK'];
      $ld_tgl_kecelakaan						= $row['TGL_KECELAKAAN'];
  		$ls_kode_jam_kecelakaan 			= $row['KODE_JAM_KECELAKAAN'];
			$ls_nama_jam_kecelakaan 			= $row['NAMA_JAM_KECELAKAAN'];
      $ls_kode_jenis_kasus				  = $row['KODE_JENIS_KASUS'];
			$ls_nama_jenis_kasus				  = $row['NAMA_JENIS_KASUS'];
  		$ls_kode_lokasi_kecelakaan		= $row['KODE_LOKASI_KECELAKAAN'];
			$ls_nama_lokasi_kecelakaan		= $row['NAMA_LOKASI_KECELAKAAN'];
  		$ls_nama_tempat_kecelakaan 		= $row['NAMA_TEMPAT_KECELAKAAN'];	
      $ls_kode_tindakan_bahaya			= $row['KODE_TINDAKAN_BAHAYA'];
  		$ls_kode_kondisi_bahaya				= $row['KODE_KONDISI_BAHAYA'];
  		$ls_kode_corak 								= $row['KODE_CORAK'];	
			$ls_nama_corak 								= $row['NAMA_CORAK'];
      $ls_kode_sumber_cedera				= $row['KODE_SUMBER_CEDERA'];
			$ls_nama_sumber_cedera				= $row['NAMA_SUMBER_CEDERA'];
  		$ls_kode_bagian_sakit					= $row['KODE_BAGIAN_SAKIT'];
			$ls_nama_bagian_sakit					= $row['NAMA_BAGIAN_SAKIT'];
  		$ls_kode_akibat_diderita 			= $row['KODE_AKIBAT_DIDERITA'];
			$ls_nama_akibat_diderita 			= $row['NAMA_AKIBAT_DIDERITA'];	
      $ls_kode_lama_bekerja					= $row['KODE_LAMA_BEKERJA'];
  		$ls_kode_penyakit_timbul			= $row['KODE_PENYAKIT_TIMBUL'];
  		$ls_kode_tipe_upah 						= $row['KODE_TIPE_UPAH'];
      $ln_nom_upah_terakhir					= $row['NOM_UPAH_TERAKHIR'];
			$ld_blth_upah_terakhir				= $row['BLTH_UPAH_TERAKHIR'];
  		$ls_kode_tempat_perawatan			= $row['KODE_TEMPAT_PERAWATAN'];
  		$ls_kode_berobat_jalan 				= $row['KODE_BEROBAT_JALAN'];
      $ls_kode_ppk									= $row['KODE_PPK'];
			$ls_nama_ppk									= $row['NAMA_PPK'];
  		$ls_nama_faskes_reimburse			= $row['NAMA_FASKES_REIMBURSE'];
  		$ls_kode_kondisi_terakhir 		= $row['KODE_KONDISI_TERAKHIR'];
      $ld_tgl_kondisi_terakhir			= $row['TGL_KONDISI_TERAKHIR'];
      $ld_tgl_kematian							= $row['TGL_KEMATIAN'];
      $ld_tgl_mulai_pensiun 				= $row['TGL_MULAI_PENSIUN'];
      $ls_status_pernikahan					= $row['STATUS_PERNIKAHAN'];
  		$ls_ket_tambahan    					= $row['KET_TAMBAHAN'];
  		$ls_status_kelayakan 					= $row['STATUS_KELAYAKAN'];
      $ls_ket_kelayakan 						= $row['KET_KELAYAKAN'];
      $ls_status_submit_agenda			= $row['STATUS_SUBMIT_AGENDA'];
  		$ld_tgl_submit_agenda					= $row['TGL_SUBMIT_AGENDA'];
  		$ls_petugas_submit_agenda 		= $row['PETUGAS_SUBMIT_AGENDA'];
      $ls_status_submit_pengajuan		= $row['STATUS_SUBMIT_PENGAJUAN'];
  		$ld_tgl_submit_pengajuan			= $row['TGL_SUBMIT_PENGAJUAN'];
  		$ls_petugas_submit_pengajuan 	= $row['PETUGAS_SUBMIT_PENGAJUAN'];
      $ls_status_submit_agenda2			= $row['STATUS_SUBMIT_AGENDA2'];
  		$ld_tgl_submit_agenda2				= $row['TGL_SUBMIT_AGENDA2'];
  		$ls_petugas_submit_agenda2 		= $row['PETUGAS_SUBMIT_AGENDA2'];
      $ls_status_submit_penetapan		= $row['STATUS_SUBMIT_PENETAPAN'];
  		$ld_tgl_submit_penetapan			= $row['TGL_SUBMIT_PENETAPAN'];
  		$ls_petugas_submit_penetapan 	= $row['PETUGAS_SUBMIT_PENETAPAN'];
			$ld_tgl_penetapan							= $row['TGL_PENETAPAN'];
			$ls_no_penetapan							= $row['NO_PENETAPAN'];
			$ls_petugas_penetapan					= $row['PETUGAS_PENETAPAN'];
      $ls_status_approval						= $row['STATUS_APPROVAL'];
  		$ld_tgl_approval							= $row['TGL_APPROVAL'];
  		$ls_petugas_approval					= $row['PETUGAS_APPROVAL'];		
  	 	$ls_status_batal 							= $row['STATUS_BATAL'];
      $ls_tgl_batal 								= $row['TGL_BATAL'];
      $ls_petugas_batal 						= $row['PETUGAS_BATAL'];
      $ls_ket_batal 								= $row['KET_BATAL'];    
      $ls_status_lunas 							= $row['STATUS_LUNAS'];
      $ld_tgl_lunas 								= $row['TGL_LUNAS'];
      $ls_petugas_lunas 						= $row['PETUGAS_LUNAS'];
  		$ls_status_klaim 							= $row['STATUS_KLAIM'];
  		$ls_kode_pointer_asal					= $row['KODE_POINTER_ASAL'];
  		$ls_id_pointer_asal						= $row['ID_POINTER_ASAL'];
  		$ls_tipe_pelaksana_kegiatan		= $row['TIPE_PELAKSANA_KEGIATAN'];
  		$ls_nama_pelaksana_kegiatan		= $row['NAMA_PELAKSANA_KEGIATAN'];
  		$ls_nama_kegiatan							= $row['NAMA_KEGIATAN'];
			$ls_flag_meninggal						= $row['FLAG_MENINGGAL'];	
			$ln_cnt_berkala								= $row['CNT_BERKALA'];
			$ln_cnt_lumpsum								= $row['CNT_LUMPSUM'];
			$ln_cnt_penerima_berkala			= $row['CNT_PENERIMA_BERKALA'];
			$ls_status_cek_amalgamasi 		= $row['STATUS_CEK_AMALGAMASI'];
      
      if ($ls_kode_perlindungan=="PRA"){
  		 	$ls_nama_perlindungan = "SEBELUM BEKERJA";
  		}elseif ($ls_kode_perlindungan=="ONSITE"){
  		 	$ls_nama_perlindungan = "SELAMA BEKERJA";
  		}elseif ($ls_kode_perlindungan=="PURNA"){
  		 	$ls_nama_perlindungan = "SETELAH BEKERJA";
  		}
									
			if($ln_cnt_berkala>"0"){
			 	 $ls_flag_berkala = "Y";
			}else{
				$ls_flag_berkala = "T";
			}	
			
			if ($ln_cnt_lumpsum>"0"){
			 	 $ls_flag_lumpsum = "Y";
			}else{
				$ls_flag_lumpsum = "T";
      }
      

      // 08102020, penambahan kondisi nvl(status_submit_agenda,'T') = 'Y'  yg bisa membentuk data dokumen digital
			$qry_cek_klaim="
			select kode_perusahaan, kode_segmen, kode_tk, kpj, id_pointer_asal, to_char(tgl_klaim,'YYYY') tahun_saldo_jht, kanal_pelayanan,
			(select count(1) from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') and kode_klaim='$ls_kode_klaim') lapakasik,
			kode_tipe_klaim
			 from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') and kode_klaim='$ls_kode_klaim'
			 and nvl(status_submit_agenda,'T') = 'Y' 
			 "
			;
			$DB->parse($qry_cek_klaim);
			if($DB->execute()){
				if($row=$DB->nextrow()){
					$cek_klaim=$row['LAPAKASIK']; 
					$ls_kode_perusahaan = $row['KODE_PERUSAHAAN'];
					$ls_kode_segmen = $row['KODE_SEGMEN'];
					$ls_kode_tk = $row['KODE_TK'];  
					$ls_kpj_klaim = $row['KPJ'];  
					$ls_tahun_saldo = $row['TAHUN_SALDO_JHT']; 
					$ls_kode_booking = $row['ID_POINTER_ASAL']; 
					$ls_kode_tipe_klaim = $row['KODE_TIPE_KLAIM'];
					$ls_kanal_pelayanan = $row['KANAL_PELAYANAN'];
					
					// 08102020, mengisi ulang data klaim sesuai data terakhir pada klaim 
					$ls_kpj = $ls_kpj_klaim;
				}
			}

      

      $sql = 	"select 'LUMPSUM' jns_pembayaran, ".
              "    c.kode_tipe_klaim, a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, d.nama_tipe_penerima, a.kd_prg, ".  
              "    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ".
              "    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".  
              "from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c, sijstk.pn_kode_tipe_penerima d ".
              "where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima ". 
              "and a.kode_klaim = c.kode_klaim and a.kode_tipe_penerima = d.kode_tipe_penerima ".
              "and a.kode_klaim = '$ls_kodeKlaim' ".
              "UNION ALL ".
              "select 'BERKALA' jns_pembayaran, ". 
              "    c.kode_tipe_klaim, a.kode_pembayaran, a.kode_klaim, d.kode_penerima_berkala kode_tipe_penerima, d.kode_penerima_berkala nama_tipe_penerima, a.kd_prg, ".   
              "    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ". 
              "    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".   
              "from sijstk.pn_klaim_pembayaran_berkala a, sijstk.pn_klaim_penerima_berkala b, sijstk.pn_klaim c, sijstk.pn_klaim_berkala d ". 
              "where a.kode_klaim = c.kode_klaim and a.kode_klaim = d.kode_klaim and a.no_konfirmasi = d.no_konfirmasi ".
              "and b.kode_klaim = d.kode_klaim and b.kode_penerima_berkala = d.kode_penerima_berkala ".
              "and a.kode_klaim = '$ls_kodeKlaim' ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_kode_tipe_klaim			= $row["KODE_TIPE_KLAIM"];
      $ls_jns_pembayaran			= $row["JNS_PEMBAYARAN"];
      $ls_kode_pembayaran			= $row["KODE_PEMBAYARAN"];
      $ls_kode_klaim 					= $row["KODE_KLAIM"];
      $ls_kode_tipe_penerima	= $row["KODE_TIPE_PENERIMA"];	
      $ls_tipe_penerima				= $row["NAMA_TIPE_PENERIMA"];
      $ls_kd_prg							= $row["KD_PRG"];
      $ls_nm_rek_penerima			= $row["NAMA_REKENING_PENERIMA"];
      $ls_bank_penerima				= $row["BANK_PENERIMA"];
      $ls_no_rek_penerima			= $row["NO_REKENING_PENERIMA"];
      $ls_no_penetapan				=	$row["NO_PENETAPAN"]; 
      $ls_no_pointer					=	$row["NO_POINTER"];  
      $ls_flag_pph_progresif	=	$row["FLAG_PPH_PROGRESIF"];
      // end - get info dokumen klaim
      
      // start - get info bucket ceph
      $sqlbucket="
      select 	to_char(sysdate, 'yyyymm') blth,
              (select kode_kantor from pn.pn_klaim where kode_klaim = '$ls_kodeKlaim') kode_kantor,
              (select kanal_pelayanan from pn.pn_klaim where kode_klaim = '$ls_kodeKlaim') kanal_pelayanan,
              (select kode_tipe_klaim from pn.pn_klaim where kode_klaim = '$ls_kodeKlaim') kode_tipe_klaim
		  from 		dual";

      $DB->parse($sqlbucket);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_blth                = $row["BLTH"];
      $ls_kode_kantor_bucket  = $row["KODE_KANTOR"];
      $ls_kanal_pelayanan     = $row["KANAL_PELAYANAN"];
      $ls_kode_tipe_klaim     = $row["KODE_TIPE_KLAIM"];
      $ls_nama_bucket_storage = "arsip";
      $ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";
      // end - get info bucket ceph

       // build url report
      $sql = 	"select to_char(sysdate,'yyyy') tahun_sekarang,  to_char(sysdate,'yyyymmdd') as vtgl, replace('$ls_tipe_penerima',' ','XXX') tipe_penerima, ".
              "		to_char(to_date('$ld_blth_proses','dd/mm/yyyy'),'yyyymmdd') as vblth_proses ".	
              "from dual ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_lap_tipe_penerima = $row["TIPE_PENERIMA"];
      $ls_lap_blth_proses = $row["VBLTH_PROSES"];
      $ls_tahun_sekarang = $row["TAHUN_SEKARANG"];
      
      $ls_user_param .= " qkode_pembayaran='$ls_kode_pembayaran'";
      $ls_user_param .= " qkode_klaim='$ls_kodeKlaim'";
      $ls_user_param .= " qtipe_penerima='$ls_lap_tipe_penerima'";
      $ls_user_param .= " qtgl='$ld_tglcetak'";
      $ls_user_param .= " qblth_proses='$ls_lap_blth_proses'";
            
  
      if($ls_kanal_pelayanan == '25'){  
            $data_storedocument = array();
            switch($ls_kodeDokumen){
              case 'JD105-D1001' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1001', 'Y', get_dokumen_merge_bpjstku($ls_kode_booking), ''); break; 
              case 'JD105-D1002' : 
                    $ls_modul = "channel";
                    $ls_nama_rpt = "SIR0011.rdf";
                    $ls_user_param = " P_KODE_BOOKING='$ls_kode_booking'";
                    $ls_user_param .= " P_KPJ='$ls_kpj'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1002', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1003' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900114.rdf";
                    $ls_user_param = " QUSER='$username'";
                    $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1003', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
               case 'JD105-D1004' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900117.rdf";
                    $ls_user_param = " P_KODE_PERUSAHAAN='$ls_kode_perusahaan'";
                    $ls_user_param .= " P_KODE_SEGMEN='$ls_kode_segmen'";
                    $ls_user_param .= " P_KODE_TK='$ls_kode_tk'";
                    $ls_user_param .= " P_TAHUN='$ls_tahun_saldo'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1004', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1005' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900116.rdf";
                    $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";
                    $ls_user_param .= " P_KODE_KANTOR='$ls_kode_kantor'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1005', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1006' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900118.rdf";
                    $ls_user_param = " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1006', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1007' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt .= "PNR502901.rdf";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1007', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1008' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt = "PNR502902.rdf";	

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1008', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1009' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    if ($ls_jns_pembayaran=="LUMPSUM"){
                      $ls_user_param = " qiddokumen_induk='$ls_kodeKlaim'"; 
                    }
                    $ls_user_param .= " qpointer='PN01'"; 
                    $ls_user_param .= " qiddokumen='$ls_no_pointer'";
                    $ls_user_param .= " quser_cetak='$username'";
                    $ls_nama_rpt = "GLR800001.rdf";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1009', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1010' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    $ls_user_param .= " qkodepointer_asal='JM09'";
                    $ls_user_param .= " qidpointer_asal='$ls_kode_pembayaran'";
                    if ($ls_flag_pph_progresif=="Y") {
                      $ls_nama_rpt = "TAXR301408.rdf";
                    } else {
                      $ls_nama_rpt = "TAXR301407.rdf";
                    }

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1010', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1011' : 
                    $ls_modul = "kn";
                    $ls_nama_rpt = "KNR3315.rdf";
                    $ls_user_param = " P_KODE_SEGMEN='$ls_kode_segmen'";
                    $ls_user_param .= " P_TAHUN='$ls_tahun_sekarang'";
                    $ls_user_param .= " P_KODE_PERUSAHAAN='$ls_kode_perusahaan'";
                    $ls_user_param .= " P_USER='$username'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1011', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD105-D1012' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1012', 'Y', get_dokumen_merge_tambahan($ls_kodeKlaim), ''); break; 
              case 'JD105-D1014' : 
                    $ls_modul = "channel";
                    $ls_nama_rpt = "SIR0014.rdf";
                    $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";
                    
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD105', 'JD105-D1014', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
            }

            // if (isMergeDoc($ls_kodeDokumen)) {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
            // }else {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            // } 

            if (isMergeDoc($ls_kodeDokumen)) {
              //  var_dump(count ($data_storedocument['docs']));die();
              if(ExtendedFunction::count($data_storedocument['docs'] > 0)){
                foreach ($data_storedocument['docs'] as $value){
                  $result = check_path_exist($value['urlDokumen']);
                  // var_dump($result);die();
                  if($result == '-1'){
                    break;
                  }
                }
                if ($result == "-1" && $ls_kanal_pelayanan != '30') {
                  $result_storedocument->ret = "-6";
                }else{
                  $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
                }
              }else{
                $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
              } 
            } else {
              $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            }
      }else{
        if($ls_kode_tipe_klaim == "JHT01"){
           $data_storedocument = array();
            switch($ls_kodeDokumen){
              case 'JD101-D1001' : 
                    // var_dump($ls_kanal_pelayanan);die();
                    if($ls_kanal_pelayanan == '30'){
                      $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1001', 'Y', get_dokumen_merge_smile($ls_kodeKlaim), '');
                    }else{
                      $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1001', 'Y', get_dokumen_merge_lapakasik($ls_kode_booking), '');
                    }
                    break; 
              //DOKUMEN F5
              case 'JD101-D1002' : 
                    if($ls_kanal_pelayanan == '30'){
                      $ls_modul = "channel";
                      $ls_nama_rpt = "SIR0011a.rdf";
                      $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";
                      
                      $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1002', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
                    }else{
                      $ls_modul = "channel";
                      $ls_nama_rpt = "SIR0011.rdf";
                      $ls_user_param = " P_KODE_BOOKING='$ls_kode_booking'";
                      $ls_user_param .= " P_KPJ='$ls_kpj'";

                      $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1002', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
                    }
              //TANDA TERIMA AGENDA JHT
              case 'JD101-D1003' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900101.rdf";
                    $ls_user_param = " QUSER='$username'";
                    $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1003', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //HISTORI SALDO
              case 'JD101-D1004' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900117.rdf";
                    $ls_user_param = " P_KODE_PERUSAHAAN='$ls_kode_perusahaan'";
                    $ls_user_param .= " P_KODE_SEGMEN='$ls_kode_segmen'";
                    $ls_user_param .= " P_KODE_TK='$ls_kode_tk'";
                    $ls_user_param .= " P_TAHUN='$ls_tahun_saldo'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1004', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //SURAT PERNYATAAN NPWP
              case 'JD101-D1005' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900116.rdf";
                    $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";
                    $ls_user_param .= " P_KODE_KANTOR='$ls_kode_kantor'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1005', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD101-D1006' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900118.rdf";
                    $ls_user_param = " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1006', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              
              case 'JD101-D1007' : 
                      $ls_modul = "PN";
                      $ls_user_param = $ls_user_param;
                      $ls_nama_rpt .= "PNR502901.rdf";	
  
                      $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1007', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //SURAT PERINTAH BAYAR
              case 'JD101-D1008' : 
                $ls_modul = "PN";
                $ls_user_param = $ls_user_param;
                $ls_nama_rpt = "PNR502902.rdf";	

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1008', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD101-D1009' : 
                  $ls_modul = "LK";
                  $ls_user_param = $ls_user_param;
                  if ($ls_jns_pembayaran=="LUMPSUM"){
                    $ls_user_param = " qiddokumen_induk='$ls_kodeKlaim'"; 
                  }
                  $ls_user_param .= " qpointer='PN01'"; 
                  $ls_user_param .= " qiddokumen='$ls_no_pointer'";
                  $ls_user_param .= " quser_cetak='$username'";
                  $ls_nama_rpt = "GLR800001.rdf";

                  $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1009', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD101-D1010' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    $ls_user_param .= " qkodepointer_asal='JM09'";
                    $ls_user_param .= " qidpointer_asal='$ls_kode_pembayaran'";
                    if ($ls_flag_pph_progresif=="Y") {
                      $ls_nama_rpt = "TAXR301408.rdf";
                    } else {
                      $ls_nama_rpt = "TAXR301407.rdf";
                    }

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1010', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //RINCIAN SALDO
              case 'JD101-D1011' : 
                    $ls_modul = "kn";
                    $ls_nama_rpt = "KNR3315.rdf";
                    $ls_user_param = " P_KODE_SEGMEN='$ls_kode_segmen'";
                    $ls_user_param .= " P_TAHUN='$ls_tahun_sekarang'";
                    $ls_user_param .= " P_KODE_PERUSAHAAN='$ls_kode_perusahaan'";
                    $ls_user_param .= " P_KODE_TK='$ls_kode_tk'";
                    $ls_user_param .= " P_USER='$username'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1011', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break;
              //DOKUMEN CATATAN VERIFIKASI
              case 'JD101-D1014' : 
                $ls_modul = "channel";
                $ls_nama_rpt = "SIR0014.rdf";
                $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1014', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break;  
              case 'JD101-D1012' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD101', 'JD101-D1012', 'Y', get_dokumen_merge_tambahan($ls_kodeKlaim), ''); break;
            } 
                   
            // print_r($data_storedocument);  
            if (isMergeDoc($ls_kodeDokumen)) {
              //  var_dump(count ($data_storedocument['docs']));die();
              if(ExtendedFunction::count($data_storedocument['docs'] > 0)){
                foreach ($data_storedocument['docs'] as $value){
                  $result = check_path_exist($value['urlDokumen']);
                  // var_dump($result);die();
                  if($result == '-1'){
                    break;
                  }
                }
                if ($result == "-1" && $ls_kanal_pelayanan != '30') {
                  $result_storedocument->ret = "-6";
                }else{
                  $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
                }
              }else{
                $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
              } 
            } else {
              $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            } 
        }else if($ls_kode_tipe_klaim == "JKM01"){
           $data_storedocument = array();
            switch($ls_kodeDokumen){
              case 'JD102-D1001' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    if ($ls_jns_pembayaran=="LUMPSUM"){
                      $ls_user_param = " qiddokumen_induk='$ls_kodeKlaim'"; 
                    }
                    $ls_user_param .= " qpointer='PN01'"; 
                    $ls_user_param .= " qiddokumen='$ls_no_pointer'";
                    $ls_user_param .= " quser_cetak='$username'";
                    $ls_nama_rpt = "GLR800001.rdf";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1001', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1002' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt .= "PNR502901.rdf";	

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1002', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1003' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt = "PNR502902.rdf";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1003', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1004' : 
                    $ls_modul = "LK";
                    $ls_user_paraml = $ls_user_param;
                    $ls_user_paraml .= " qkodepointer_asal='JM09'";
                    $ls_user_paraml .= " qidpointer_asal='$ls_kode_pembayaran'";
                    if ($ls_flag_pph_progresif=="Y") {
                      $ls_nama_rpt = "TAXR301408.rdf";
                    } else {
                      $ls_nama_rpt = "TAXR301407.rdf";
                    }
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1004', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1005' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900115.rdf";
                    $ls_user_param = " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1005', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1006' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900114.rdf";
                    $ls_user_param = " QUSER='$username'";
                    $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1006', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1007' : 
                    $ls_modul = "channel";
                    $ls_nama_rpt = "SIR0012.rdf";
                    $ls_user_param = " P_KODE_BOOKING='$ls_kode_booking'";
                    $ls_user_param .= " P_KPJ='$ls_kpj'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1007', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD102-D1008' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1008', 'Y', get_dokumen_merge_lapakasik($ls_kode_booking), ''); break; 
              case 'JD102-D1009' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1009', 'Y', get_dokumen_merge_tambahan($ls_kode_klaim), ''); break; 
              case 'JD102-D1010' : 
                    $ls_modul = "channel";
                    $ls_nama_rpt = "SIR0014.rdf";
                    $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD102', 'JD102-D1010', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
            }

            // if (isMergeDoc($ls_kodeDokumen)) {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
            // }else {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            // } 
            //check kondisi path ceph exist
            if (isMergeDoc($ls_kodeDokumen)) {
              //  var_dump(count ($data_storedocument['docs']));die();
              if(ExtendedFunction::count($data_storedocument['docs'] > 0)){
                foreach ($data_storedocument['docs'] as $value){
                  $result = check_path_exist($value['urlDokumen']);
                  // var_dump($result);die();
                  if($result == '-1'){
                    break;
                  }
                }
                if ($result == "-1" && $ls_kanal_pelayanan != '30') {
                  $result_storedocument->ret = "-6";
                }else{
                  $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
                }
              }else{
                $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
              } 
            } else {
              $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            }
        }else if($ls_kode_tipe_klaim == "JPN01"){
          
          if($ln_cnt_lumpsum > 0){     
            switch($ls_kodeDokumen){
                //VOUCHER
              case 'JD103-D1001' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    if ($ls_jns_pembayaran=="LUMPSUM"){
                      $ls_user_param = " qiddokumen_induk='$ls_kodeKlaim'"; 
                    }
                    $ls_user_param .= " qpointer='PN01'"; 
                    $ls_user_param .= " qiddokumen='$ls_no_pointer'";
                    $ls_user_param .= " quser_cetak='$username'";
                    $ls_nama_rpt = "GLR800001.rdf";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1001', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //KWITANSI
              case 'JD103-D1002' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt .= "PNR502901.rdf";	

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1002', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //SURAT PERINTAH BAYAR
              case 'JD103-D1003' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt = "PNR502902.rdf";	

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1003', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //BUKTI POTONG PPH21
              case 'JD103-D1004' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    $ls_user_param .= " qkodepointer_asal='JM09'";
                    $ls_user_param .= " qidpointer_asal='$ls_kode_pembayaran'";
                    if ($ls_flag_pph_progresif=="Y") {
                      $ls_nama_rpt = "TAXR301408.rdf";
                    } else {
                      $ls_nama_rpt = "TAXR301407.rdf";
                    }

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1004', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD103-D1005' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900106.rdf";
                    $ls_user_param = " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1005', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //TANDA TERIMA AGENDA JPN
              case 'JD103-D1006' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900101.rdf";
                    $ls_user_param = " QUSER='$username'";
                    $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1006', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //RINCIAN IURAN JPN
              case 'JD103-D1007' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900104_ITK.rdf";
                    $ls_user_param = " QKODETK='$ls_kode_tk'";
                    $ls_user_param .= " QUSER='$username'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1007', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //HISTORI IURAN JPN
              case 'JD103-D1008' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900103_ITK.rdf";
                    $ls_user_param = " QKODETK='$ls_kode_tk'";
                    $ls_user_param .= " QUSER='$username'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1008', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD103-D1009' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900116.rdf";
                    $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";
                    $ls_user_param .= " P_KODE_KANTOR='$ls_kode_kantor'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1009', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //DOKUMEN F7
              case 'JD103-D1010' : 
                    $ls_modul = "channel";
                    $ls_nama_rpt = "SIR0013.rdf";
                    $ls_user_param = " P_KODE_BOOKING='$ls_kode_booking'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1010', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              // /DOKUMEN KELENGKAPAN PERSYARATAN
              case 'JD103-D1011' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1011', 'Y', get_dokumen_merge_lapakasik($ls_kode_booking), ''); break; 
              //DOKUMEN ELABORASI PMP
              case 'JD103-D1012' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1012', 'Y', get_dokumen_merge_tambahan($ls_kode_booking), ''); break; 
            }

            //check ceph file
            if (isMergeDoc($ls_kodeDokumen)) {
              //  var_dump(count ($data_storedocument['docs']));die();
              if(ExtendedFunction::count($data_storedocument['docs'] > 0)){
                foreach ($data_storedocument['docs'] as $value){
                  $result = check_path_exist($value['urlDokumen']);
                  // var_dump($result);die();
                  if($result == '-1'){
                    break;
                  }
                }
                if ($result == "-1" && $ls_kanal_pelayanan != '30') {
                  $result_storedocument->ret = "-6";
                }else{
                  $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
                }
              }else{
                $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
              } 
            } else {
              $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            } 
          }
          
          if($ln_cnt_berkala > 0){
            switch($ls_kodeDokumen){
             //VOUCHER
              case 'JD104-D1001' : 
                $ls_modul = "LK";
                $ls_user_param = $ls_user_param;
                if ($ls_jns_pembayaran=="LUMPSUM"){
                  $ls_user_param = " qiddokumen_induk='$ls_kodeKlaim'"; 
                }
                $ls_user_param .= " qpointer='PN01'"; 
                $ls_user_param .= " qiddokumen='$ls_no_pointer'";
                $ls_user_param .= " quser_cetak='$username'";
                $ls_nama_rpt = "GLR800001.rdf";

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1001', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //KWITANSI
              case 'JD104-D1002' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt .= "PNR502901.rdf";	

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD14-D1002', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //SURAT PERINTAH BAYAR
              case 'JD104-D1003' : 
                    $ls_modul = "PN";
                    $ls_user_param = $ls_user_param;
                    $ls_nama_rpt = "PNR502902.rdf";	

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1003', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //BUKTI POTONG PPH21
              case 'JD104-D1004' : 
                    $ls_modul = "LK";
                    $ls_user_param = $ls_user_param;
                    $ls_user_param .= " qkodepointer_asal='JM09'";
                    $ls_user_param .= " qidpointer_asal='$ls_kode_pembayaran'";
                    if ($ls_flag_pph_progresif=="Y") {
                      $ls_nama_rpt = "TAXR301408.rdf";
                    } else {
                      $ls_nama_rpt = "TAXR301407.rdf";
                    }

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD103', 'JD103-D1004', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //SURAT PENETAPAN KLAIM
              case 'JD104-D1005' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900105.rdf";
                    $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1005', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //TANDA TERIMA AGENDA JPN
              case 'JD104-D1006' : 
                    $ls_modul = "pn";
                    $ls_nama_rpt = "PNR900101.rdf";
                    $ls_user_param = " QUSER='$username'";
                    $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1006', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //SURAT PERNYATAAN KLAIM JPN BERKALa
              case 'JD104-D1007' : 
                $ls_modul = "pn";
                $ls_nama_rpt = "PNR900102.rdf";
                $ls_user_param = " QKODEKLAIM='$ls_kodeKlaim'";
                $ls_user_param .= " QNOKONFIRMASI='0'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1007', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD104-D1008' : 
                $ls_modul = "pn";
                $ls_nama_rpt = "PNR900104_ITK.rdf";
                $ls_user_param = " QKODETK='$ls_kode_tk'";
                $ls_user_param .= " QUSER='$username'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1008', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD104-D1009' : 
                $ls_modul = "pn";
                $ls_nama_rpt = "PNR900103_ITK.rdf";
                $ls_user_param .= " QKODETK='$ls_kode_tk'";
                $ls_user_param .= " QUSER='$username'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1009', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD104-D1010' : 
                $ls_modul = "pn";
                $ls_nama_rpt = "PNR900116.rdf";
                $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";
                $ls_user_param .= " P_KODE_KANTOR='$ls_kode_kantor'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1010', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD104-D1011' : 
                $ls_modul = "channel";
                $ls_nama_rpt = "SIR0013.rdf";
                $ls_user_param = " P_KODE_BOOKING='$ls_kode_booking'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1011', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              case 'JD104-D1012' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1012', 'Y', get_dokumen_merge_lapakasik($ls_kode_booking), ''); break; 
              case 'JD104-D1013' : 
                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1013', 'Y', get_dokumen_merge_tambahan($ls_kode_booking), ''); break; 
              case 'JD104-D1014' : 
                    $ls_modul = "channel";
                    $ls_nama_rpt = "SIR0011.rdf";
                    $ls_user_param = " P_KODE_BOOKING='$ls_kode_booking'";
                    $ls_user_param .= " P_KPJ='$ls_kpj'";

                    $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD104', 'JD104-D1014', 'T', '', get_rpt_url_antrian($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
            }

            // if (isMergeDoc($ls_kodeDokumen)) {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
            // }else {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            // } 
            if (isMergeDoc($ls_kodeDokumen)) {
              //  var_dump(count ($data_storedocument['docs']));die();
              if(ExtendedFunction::count($data_storedocument['docs'] > 0)){
                foreach ($data_storedocument['docs'] as $value){
                  $result = check_path_exist($value['urlDokumen']);
                  // var_dump($result);die();
                  if($result == '-1'){
                    break;
                  }
                }
                if ($result == "-1" && $ls_kanal_pelayanan != '30') {
                  $result_storedocument->ret = "-6";
                }else{
                  $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
                }
              }else{
                $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
              } 
            } else {
              $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            }  
          }

        }else if($ls_kode_tipe_klaim == "JKP01"){
          $data_storedocument = array();
            switch($ls_kodeDokumen){
              //VOUCHER
              case 'JD107-D1001' : 
                  $ls_modul = "LK";
                  $ls_user_param = $ls_user_param;
                  if ($ls_jns_pembayaran=="LUMPSUM"){
                    $ls_user_param = " qiddokumen_induk='$ls_kodeKlaim'"; 
                  }
                  $ls_user_param .= " qpointer='PN01'"; 
                  $ls_user_param .= " qiddokumen='$ls_no_pointer'";
                  $ls_user_param .= " quser_cetak='$username'";
                  $ls_nama_rpt = "GLR800001.rdf";

                  $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1001', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              //KWITANSI
              case 'JD107-D1002' : 
                  $ls_modul = "PN";
                  $ls_user_param = $ls_user_param;
                  $ls_nama_rpt .= "PNR502901.rdf";	

                  $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1002', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break;
              //SURAT PERINTAH BAYAR
              case 'JD107-D1003' : 
                $ls_modul = "PN";
                $ls_user_param = $ls_user_param;
                $ls_nama_rpt = "PNR502902.rdf";	

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1003', 'T', '', get_rpt_url_ncreport_drc($ls_modul, $ls_nama_rpt, $ls_user_param)); break;
              //SURAT PENETAPAN KLAIM
              case 'JD107-D1005' : 
                $ls_modul = "pn";
                $ls_nama_rpt = "";
                $ls_user_param = " QKODEKLAIM='$ls_kodeKlaim'";

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1005', 'T', '', get_rpt_url_ncreport_jkp_penetapan($ls_modul, $ls_nama_rpt, $ls_user_param,$ls_id_pointer_asal)); break; 
                  
              //TANDA TERIMA AGENDA JKP
              case 'JD107-D1006' : 
                $ls_modul = "pn";
                $ls_nama_rpt = "PNR900101.rdf";
                $ls_user_param = " QUSER='$username'";
                $ls_user_param .= " QKODEKLAIM='$ls_kodeKlaim'";

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1006', 'T', '', get_rpt_url_ncreport($ls_modul, $ls_nama_rpt, $ls_user_param)); break;
              //DOKUMEN F6
              case 'JD107-D1007' : 
                $ls_modul = "channel";
                $ls_nama_rpt = "PNR902226.rdf";
                $ls_user_param = " P_KODE_PENGAJUAN='$ls_kode_booking'";
                
                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1007', 'T', '', get_rpt_url_ncreport_jkp($ls_modul, $ls_nama_rpt, $ls_user_param)); break; 
              // /DOKUMEN KELENGKAPAN PERSYARATAN
              case 'JD107-D1008' : 
              $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1008', 'Y', get_dokumen_merge_siapkerja($ls_kodeKlaim), ''); break;
              //DOKUMEN CATATAN VERIFIKASI
              case 'JD107-D1010' : 
                $ls_modul = "channel";
                $ls_nama_rpt = "SIR0014.rdf";
                $ls_user_param = " P_KODE_KLAIM='$ls_kodeKlaim'";

                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1010', 'T', '', get_rpt_url_verifikasi_jkp($ls_modul, $ls_nama_rpt, $ls_user_param)); break;
              //DOKUMEN ELABORASI
              case 'JD107-D1009' : 
                $data_storedocument = get_spec_dokumen($ls_kodeKlaim, $ls_nama_bucket_storage, $ls_nama_folder_storage, 'JD107', 'JD107-D1009', 'Y', get_dokumen_merge_tambahan($ls_kode_booking), ''); break;
            }        
            // print_r($data_storedocument);  
            // if (isMergeDoc($ls_kodeDokumen)) {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
            // }else {
            //   $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            // }
            //check path ceph
            if (isMergeDoc($ls_kodeDokumen)) {
              //  var_dump(count ($data_storedocument['docs']));die();
              if(ExtendedFunction::count($data_storedocument['docs'] > 0)){
                foreach ($data_storedocument['docs'] as $value){
                  $result = check_path_exist($value['urlDokumen']);
                  // var_dump($result);die();
                  if($result == '-1'){
                    break;
                  }
                }
                if ($result == "-1" && $ls_kanal_pelayanan != '30') {
                  $result_storedocument->ret = "-6";
                }else{
                  $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
                }
              }else{
                $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument);
              } 
            } else {
              $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
            }
        }
        
      }
    // var_dump($result_storedocument -> ret);die();
    if ($result_storedocument->ret === "0") {
      $jsondata["ret"] = "0";
      $jsondata["msg"] = "Berhasil";
      echo json_encode($jsondata);
    }else if ($result_storedocument -> ret === '-1'){
      $jsondata["ret"] = "-3";
      $jsondata["msg"] = $result_storedocument->msg;
      echo json_encode($jsondata);
    }else if ($result_storedocument->ret== "-6") {
      $jsondata["ret"] = "-6";
      $jsondata["msg"] = "Pembentukan dokumen digital gagal karena file dokumen tidak ditemukan/mengandung password/korup.";
      echo json_encode($jsondata);
    }else{
      $jsondata["ret"] = "-3";
      $jsondata["msg"] = $result_storedocument->msg;
      echo json_encode($jsondata);
    }
    
  }else if($ls_tipe=="generateSign"){
    $ls_kode_klaim = $_POST["kodeKlaim"];
    $ls_kode_dokumen = $_POST["kodeDokumen"];
    $ls_id_arsip = $_POST["idArsip"];
   
    $is_ttd_cso = array("JD101-D1003", "JD102-D1006", "JD103-D1006", "JD104-D1006","JD107-D1006","JD107-D1007");
    $is_ttd_kbp = array("JD101-D1006", "JD102-D1005", "JD103-D1005", "JD104-D1005", "JD105-D1006","JD107-D1005");
    $is_ttd_kbk = array("JD101-D1008", "JD102-D1003", "JD103-D1003", "JD104-D1003", "JD102-D1008","JD107-D1003");
    $is_ttd_kasir_membukukan_menyetujui = array("JD101-D1009", "JD102-D1001", "JD103-D1001", "JD104-D1001", "JD105-D1009","JD107-D1001");

    // prepare user sign
    if (in_array($ls_kode_dokumen, $is_ttd_cso)) {
      // get data user sign untuk dok terima
      $sql_sign = "
      BEGIN
        PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOK_TERIMA(
          :P_SIGN_KODE_KLAIM,
          :P_SIGN_KODE_KANTOR,
          :P_SIGN_NPK,
          :P_SIGN_KODE_USER,
          :P_SIGN_NAMA_USER,
          :P_SIGN_NAMA_JABATAN,
          :P_SIGN_SUKSES,
          :P_SIGN_MESS
        );
      END;";
      $proc_sign = $DB->parse($sql_sign);
      oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
      oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
      oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
      oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
      oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
      oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);

      if ($DB->execute()) {
        $sukses = $p_sign_sukses;
        $mess = $p_sign_mess;
        if ($sukses == '1') {
          $arr_temp_data_user_sign_dok_terima = array(
            "kodeKantor" 	=> $p_sign_kode_kantor,
            "npk"        	=> $p_sign_npk,
            "namaJabatan"	=> $p_sign_nama_jabatan,
            "petugas"   	=> $p_sign_kode_user
          );
        }
      }
      // end get data user sign untuk dok terima
    } elseif (in_array($ls_kode_dokumen, $is_ttd_kbp)) {

      // get data user sign untuk penetapan
      $sql_sign = "
      BEGIN
        PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOK_PENETAPAN(
          :P_SIGN_KODE_KLAIM,
          :P_SIGN_KODE_KANTOR,
          :P_SIGN_NPK,
          :P_SIGN_KODE_USER,
          :P_SIGN_NAMA_USER,
          :P_SIGN_NAMA_JABATAN,
          :P_SIGN_SUKSES,
          :P_SIGN_MESS
        );
      END;";
      // var_dump($sql_sign);die();
      $proc_sign = $DB->parse($sql_sign);
      oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
      oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
      oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
      oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
      oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
      oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);
      
      if ($DB->execute()) {
        $sukses = $p_sign_sukses;
        $mess = $p_sign_mess;
        if ($sukses == '1') {
          $arr_temp_data_user_sign_penetapan = array(
            "kodeKantor" 	=> $p_sign_kode_kantor,
            "npk"        	=> $p_sign_npk,
            "namaJabatan"	=> $p_sign_nama_jabatan,
            "petugas"   	=> $p_sign_kode_user
          );
        }
      }
      // end get data user sign untuk penetapan
    } elseif (in_array($ls_kode_dokumen, $is_ttd_kbk)) {

      // get data user sign untuk report spb
      $sql_sign = "
      BEGIN
        PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKSPB(
          :P_SIGN_KODE_KLAIM,
          :P_SIGN_KODE_KANTOR,
          :P_SIGN_NPK,
          :P_SIGN_KODE_USER,
          :P_SIGN_NAMA_USER,
          :P_SIGN_NAMA_JABATAN,
          :P_SIGN_SUKSES,
          :P_SIGN_MESS
        );
      END;";
      $proc_sign = $DB->parse($sql_sign);
      oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
      oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
      oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
      oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
      oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
      oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);

      if ($DB->execute()) {
        $sukses = $p_sign_sukses;
        $mess = $p_sign_mess;
        if ($sukses == '1') {
          $arr_temp_data_user_sign_spb = array(
            "kodeKantor" 	=> $p_sign_kode_kantor,
            "npk"        	=> $p_sign_npk,
            "namaJabatan"	=> $p_sign_nama_jabatan,
            "petugas"   	=> $p_sign_kode_user
          );
        }
        // var_dump($arr_temp_data_user_sign_spb);die();
      }
      // end get data user sign untuk report spb
    } elseif (in_array($ls_kode_dokumen, $is_ttd_kasir_membukukan_menyetujui)) {

      // get data user sign untuk report voucher setuju
      $sql_sign = "
      BEGIN
        PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKVC_SETUJU(
          :P_SIGN_KODE_KLAIM,
          :P_SIGN_KODE_KANTOR,
          :P_SIGN_NPK,
          :P_SIGN_KODE_USER,
          :P_SIGN_NAMA_USER,
          :P_SIGN_NAMA_JABATAN,
          :P_SIGN_SUKSES,
          :P_SIGN_MESS
        );
      END;";

      $proc_sign = $DB->parse($sql_sign);
      oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
      oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
      oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
      oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
      oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
      oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);

      if ($DB->execute()) {
        $sukses = $p_sign_sukses;
        $mess = $p_sign_mess;
        if ($sukses == '1') {
          $arr_temp_data_user_sign_vc_setuju = array(
            "kodeKantor" 	=> $p_sign_kode_kantor,
            "npk"        	=> $p_sign_npk,
            "namaJabatan"	=> $p_sign_nama_jabatan,
            "petugas"   	=> $p_sign_kode_user
          );
        }
      }
      // end get data user sign untuk report voucher setuju

      // get data user sign untuk report voucher membukukan
      $sql_sign = "
      BEGIN
        PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKVC_MBUKU(
          :P_SIGN_KODE_KLAIM,
          :P_SIGN_PETUGAS_REKAM,
          :P_SIGN_KODE_KANTOR,
          :P_SIGN_NPK,
          :P_SIGN_KODE_USER,
          :P_SIGN_NAMA_USER,
          :P_SIGN_NAMA_JABATAN,
          :P_SIGN_SUKSES,
          :P_SIGN_MESS
        );
      END;";
      $proc_sign = $DB->parse($sql_sign);
      oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
      oci_bind_by_name($proc_sign, ":p_sign_petugas_rekam", $p_sign_petugas_rekam, 30);
      oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
      oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
      oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
      oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
      oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);

      if ($DB->execute()) {
        $sukses = $p_sign_sukses;
        $mess = $p_sign_mess;
        if ($sukses == '1') {
          $arr_temp_data_user_sign_vc_mbuku = array(
            "kodeKantor" 	=> $p_sign_kode_kantor,
            "npk"        	=> $p_sign_npk,
            "namaJabatan"	=> $p_sign_nama_jabatan,
            "petugas"   	=> $p_sign_kode_user
          );
        }
      }
      // end get data user sign untuk report voucher membukukan

      // get data user sign untuk report voucher kasir
      $sql_sign = "
      BEGIN
        PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKVC_KASIR(
          :P_SIGN_KODE_KLAIM,
          :P_SIGN_KODE_KANTOR,
          :P_SIGN_NPK,
          :P_SIGN_KODE_USER,
          :P_SIGN_NAMA_USER,
          :P_SIGN_NAMA_JABATAN,
          :P_SIGN_SUKSES,
          :P_SIGN_MESS
        );
      END;";
      $proc_sign = $DB->parse($sql_sign);
      oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
      oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
      oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
      oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
      oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
      oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
      oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);

      if ($DB->execute()) {
        $sukses = $p_sign_sukses;
        $mess = $p_sign_mess;
        if ($sukses == '1') {
          $arr_temp_data_user_sign_vc_kasir = array(
            "kodeKantor" 	=> $p_sign_kode_kantor,
            "npk"        	=> $p_sign_npk,
            "namaJabatan"	=> $p_sign_nama_jabatan,
            "petugas"   	=> $p_sign_kode_user
          );
        }
        // var_dump($arr_temp_data_user_sign_vc_kasir);die();
      }
      
      // end get data user sign untuk report voucher kasir
    }

    $headers = array(
      'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );


    // prepare sign
    $data_presign = array(
      "chId" => "SMILE",
      "reqId" => $USER,
      "idArsip" => $ls_id_arsip
    );

    
    $result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);

    if ($result_presign->ret == "0") {
      $idArsip  = $result_presign->data->idArsip;
      $docSigns = $result_presign->data->docSigns;
      
      $is_ttd_cso = array('JD101-D1003-0001', 'JD102-D1006-0001', 'JD103-D1006-0001','JD104-D1006-0001','JD107-D1006-0001','JD107-D1007-0001');
      $is_ttd_kbk = array('JD101-D1008-0001', 'JD102-D1003-0001', 'JD103-D1003-0001', 'JD104-D1003-0001', 'JD105-D1008-0001','JD107-D1003-0001');
      $is_ttd_kasir = array('JD101-D1009-0003', 'JD102-D1001-0003', 'JD103-D1001-0003', 'JD104-D1001-0003', 'JD105-D1009-0003','JD107-D1001-0003');
      $is_ttd_kbp = array('JD101-D1006-0001', 'JD102-D1005-0001', 'JD103-D1005-0001', 'JD104-D1005-0001', 'JD105-D1006-0001','JD107-D1005-0001');
      $is_ttd_membukukan = array('JD101-D1009-0002', 'JD102-D1001-0002', 'JD103-D1001-0002', 'JD104-D1001-0002', 'JD105-D1009-0002','JD107-D1001-0002');
      $is_ttd_menyetujui = array('JD101-D1009-0001', 'JD102-D1001-0001', 'JD103-D1001-0001', 'JD104-D1001-0001', 'JD105-D1009-0001','JD107-D1001-0001');

      if (ExtendedFunction::count($docSigns) > 0) {
        $newDocSigns = array();
        foreach ($docSigns as $sign) {
          if (in_array($sign->kodeDokumenSign, $is_ttd_kbk)) { //kbk
            $sign->dataUserSign = $arr_temp_data_user_sign_spb;
          } elseif (in_array($sign->kodeDokumenSign, $is_ttd_menyetujui)) {//membukukan
            $sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
          } elseif (in_array($sign->kodeDokumenSign, $is_ttd_membukukan)) {
            $sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
          } elseif (in_array($sign->kodeDokumenSign, $is_ttd_kasir)) {
            $sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
          } elseif (in_array($sign->kodeDokumenSign, $is_ttd_cso)) {
            $sign->dataUserSign = $arr_temp_data_user_sign_dok_terima;
          } elseif (in_array($sign->kodeDokumenSign, $is_ttd_kbp)) {
            $sign->dataUserSign = $arr_temp_data_user_sign_penetapan;
          }

          //  var_dump($sign->dataUserSign["kodeKantor"]);die();  
          //sementara
          $arr_temp_data_user_sign_dok_terimax = array(
            "kodeKantor" 	=> $sign->dataUserSign["kodeKantor"],
            "npk"        	=> $sign->dataUserSign["npk"],
            "namaJabatan"	=> $sign->dataUserSign["namaJabatan"],
            "petugas"   	=> $sign->dataUserSign["petugas"]
          );
          $sign->dataUserSign = $arr_temp_data_user_sign_dok_terimax;
          //end sementara

          $sign->action = "sign";
          array_push($newDocSigns, $sign);
        }

        // sign document
       
        $data_sign = array(
          "chId" 			=> "SMILE",
          "reqId" 		=> $USER,
          "idArsip" 	=> $idArsip,
          "docSigns"	=> $newDocSigns
        );
        // var_dump($data_sign);die();
        $result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
        sleep(1);

      if ($result_sign->ret === "0") {
          $json_data["ret"] = "0";
          $json_data["msg"] = "Sign Dokumen Berhasil";
        } else {
          $json_data["ret"] = "-1";
          $json_data["msg"] = "Sign Dokumen Gagal." . $result_sign->msg;
        }
      } else {
        $json_data["ret"] = "-1";
        $json_data["msg"] = "Sign Dokumen Gagal. Data User Sign tidak ditemukan.";
      }
    } else {
      $json_data["ret"] = "-1";
      $json_data["msg"] = "Sign Dokumen Gagal";
    }

    echo json_encode($json_data);
  }
}
else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $ls_tipe = $_GET["tipe"];
  if ($ls_tipe == 'download_lampiran') {
    $ls_path_url = $_GET["url_path"];
    $ls_nama_file = $_GET["nama_dokumen"];
    header("Content-type:application/pdf");
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($ls_nama_file).'.pdf"');
    echo file_get_contents($wsIpStorage . $ls_path_url);
    exit;
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Tipe action tidak ditemukan!";
    json_encode($jsondata);
  }
}

?>
