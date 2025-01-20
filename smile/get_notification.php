<?PHP
// session_start();
if (session_status() == PHP_SESSION_NONE) {
    session_start();
 }
require_once './includes/conf_global.php';


$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';
if ($contentType === "application/json") {

    //Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));
    $params = json_decode($content, true);

    $ls_tipe = $params['tipe'];
    $ls_kode_kantor = $params['kode_kantor'];

    //If json_decode failed, the JSON is invalid.
    if(! is_array($decoded)) {

        if($ls_tipe == "get_notification_cso"){
            $notifikasi = array();

            $siapKerja                  = siap_kerja($ls_kode_kantor);
            $jhtKolektif                = jht_kolektif($ls_kode_kantor);
            $notifikasi["siap_kerja"]   = $siapKerja;
            $notifikasi["jht_kolektif"] = $jhtKolektif;
            $notifikasi["epmi"]         = epmi($ls_kode_kantor);

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_kbl"){
            $notifikasi = array();

            $siapKerja                  = siap_kerja($ls_kode_kantor);
            $jhtKolektif                = jht_kolektif($ls_kode_kantor);
            $informasiPengaduan         = informasi_pengaduan($ls_kode_kantor);
            $jpKoreksiNorek             = jp_koreksi_norek($ls_kode_kantor);
            $notifikasi["jp_koreksi_norek"] = $jpKoreksiNorek;
            $notifikasi["siap_kerja"]   = $siapKerja;
            $notifikasi["jht_kolektif"] = $jhtKolektif;
            $notifikasi["informasi_pengaduan"] = $informasiPengaduan;

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_pmp"){
            $notifikasi = array();

            $siapKerja = siap_kerja($ls_kode_kantor);
            $notifikasi["siap_kerja"] = $siapKerja;

            $jpKoreksiNorek                 = jp_koreksi_norek($ls_kode_kantor);
            $notifikasi["jp_koreksi_norek"] = $jpKoreksiNorek;

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_kakcp"){
            $notifikasi = array();


            $mltPembina                 = mlt_pembina($ls_kode_kantor);
            $notifikasi["mlt_pembina"]  = $mltPembina;

            $siapKerja = siap_kerja($ls_kode_kantor);
            $notifikasi["siap_kerja"] = $siapKerja;

            $jpKoreksiNorek                 = jp_koreksi_norek($ls_kode_kantor);
            $notifikasi["jp_koreksi_norek"] = $jpKoreksiNorek;

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_pmpu"){
            $notifikasi = array();

            $siapKerja = siap_kerja($ls_kode_kantor);
            $notifikasi["siap_kerja"] = $siapKerja;

            $jpKoreksiNorek                 = jp_koreksi_norek($ls_kode_kantor);
            $notifikasi["jp_koreksi_norek"] = $jpKoreksiNorek;

            echo json_encode($notifikasi);
        }else if(in_array($ls_tipe, ['get_notification_ro', 'get_notification_kbp', 'get_notification_kbpbpu','get_notification_kakcp'])){
            $notifikasi = array();

            $mltPembina                 = mlt_pembina($ls_kode_kantor);
            $notifikasi["mlt_pembina"]  = $mltPembina;

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_mo"){
            $notifikasi = array();

            $mltPembina                 = mlt_pembina($ls_kode_kantor);
            $notifikasi["mlt_pembina"]  = $mltPembina;

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_kakacab"){
            $notifikasi = array();

            $mltPembina                 = mlt_pembina($ls_kode_kantor);
            $notifikasi["mlt_pembina"]  = $mltPembina;

            $jpKoreksiNorek                 = jp_koreksi_norek($ls_kode_kantor);
            $notifikasi["jp_koreksi_norek"] = $jpKoreksiNorek;

            echo json_encode($notifikasi);
        }else if($ls_tipe == "get_notification_kbkeu"){
            $notifikasi = array();

            $jpKoreksiNorek                 = jp_koreksi_norek($ls_kode_kantor);
            $notifikasi["jp_koreksi_norek"] = $jpKoreksiNorek;

            echo json_encode($notifikasi);
        }else if($ls_tipe = 'get_interval'){
            $interval = refresh_time();
            echo json_encode($interval);
        }
    } else {
        // Send error back to user.
        echo "Error get notification";
    }
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

function siap_kerja($kode_kantor){ 
    
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].time(), 
        "kodeKantor" => $kode_kantor
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/NotifikasiKlaim/CheckKlaimJkpBelumProses", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["total"] = $result->total;
        $jsondata["msg"] = "Ada ".$result->total." pengajuan klaim JKP belum diproses";
        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    } 
}

function epmi($kode_kantor){ 
    
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].time(), 
        "kodeKantor" => $kode_kantor
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/NotifikasiKlaim/CheckKlaimEpmiBelumProses", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["total"] = $result->total;
        $jsondata["msg"] = "Ada ".$result->total." pengajuan klaim E-PMI belum diproses";
        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    } 
}

function jht_kolektif($kode_kantor){ 
    
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].time(), 
        "kodeKantor" => $kode_kantor
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/NotifikasiKlaim/CheckKlaimSippBelumProses", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["totalCso"] = $result->totalCso;
        $jsondata["totalKbl"] = $result->totalKbl;
        $jsondata["msg_cso"] = "Ada ".$result->totalCso." pengajuan klaim JHT Kolektif melalui aplikasi SIPP belum diproses";
        $jsondata["msg_kbl"] = "Ada ".$result->totalKbl." pengajuan klaim JHT Kolektif melalui aplikasi SIPP belum diApprove";
        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    } 
}

function jp_koreksi_norek($kode_kantor){ 
    
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].date("Ymd"), 
        "kodeUser" => $_SESSION["USER"], 
        "kodeKantor" => $kode_kantor
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/NotifikasiKlaim/CheckKoreksiRekeningJPBelumProses", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["total_pmp"] = $result->totalPmp;
        $jsondata["msg_jpnorek_pmp"] = "Ada ".$result->totalPmp." pengajuan koreksi rekening pembayaran klaim return belum diproses";
        $jsondata["total_kbl"] = $result->totalKbl;
        $jsondata["msg_jpnorek_kbl"] = "Ada ".$result->totalKbl." pengajuan approval koreksi rekening pembayaran klaim return belum diproses";
        $jsondata["total_kbkeu"] = $result->totalKbkeu;
        $jsondata["msg_jpnorek_kbkeu"] = "Ada ".$result->totalKbkeu." pengajuan approval koreksi rekening pembayaran klaim return belum diproses";
        $jsondata["total_kakacab"] = $result->totalKakacab;
        $jsondata["msg_jpnorek_kakacab"] = "Ada ".$result->totalKakacab." pengajuan approval koreksi rekening pembayaran klaim return belum diproses";
        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    } 
}

function mlt_pembina($kode_kantor){ 
    
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].date("Ymd"), 
        "kodeUser" => $_SESSION["USER"], 
        "kodeKantor" => $kode_kantor
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
    global $wsIp;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/NotifikasiKlaim/CheckPengajuanMltBelumProses", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["total_ark"] = $result->totalArk;
        $jsondata["msg_mlt_ark"] = "Ada ".$result->totalArk." pengajuan belum diproses";
        $jsondata["total_kbp"] = $result->totalKbp;
        $jsondata["msg_mlt_kbp"] = "Ada ".$result->totalKbp." pengajuan belum diapprove";
        $jsondata["total_kakacab"] = $result->totalKakacab;
        $jsondata["msg_mlt_kakacab"] = "Ada ".$result->totalKakacab." pengajuan belum diapprove";

        $session_role = array("10", "15", "25", "79"); // kbp,kakacab,kakcp,kbpbpu

        if(in_array($_SESSION["regrole"], $session_role)){
            $data_cek_jabatan = array(
                                "chId" => "SMILE", 
                                "reqId" => $_SESSION["USER"], 
                                "kodeUser" => $_SESSION["USER"], 
                                "kodeKantor" => $kode_kantor,
                                "kodeFungsi" => $_SESSION["regrole"]
                                );
        
            $result_cek_jabatan = api_json_call($wsIp . "/JSMLT/GetJabatanUser", $headers, $data_cek_jabatan);

            if($result_cek_jabatan->ret=="0"){
                $jsondata["total_kbp_reset"] = $result->totalKbpReset;
                $jsondata["msg_mlt_kbp_reset"] = "Ada ".$result->totalKbpReset." pengajuan belum diapprove";
            }
        }

        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    } 
}

function refresh_time(){
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].time(), 
        "kodeParameter" => 'INTERVALNOTIFKLAIM'
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/NotifikasiKlaim/GetParameterSistem", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["aktif"] = $result->aktif;
        $jsondata["waktu"] = $result->nilai;
        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    }
}

function informasi_pengaduan($kode_kantor){ 
    
    $data = array(
        "chId" => "SMILE", 
        "reqId" => $_SESSION["USER"].time(), 
        "kodeKantor" => $kode_kantor
    ); 

    global $ipfwd;
    global $api_notifikasi_klaim;
   
    $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
    );
    $result = api_json_call($api_notifikasi_klaim. "/Notifikasi/CheckPengaduanBelumProses", $headers, $data);

    if(isset($result->statusCode) && $result->statusCode == '200'){
        $jsondata["ret"] = "1";
        $jsondata["total"] = $result->total;
        $jsondata["msg"] = "Anda Memiliki ".$result->total." agenda pengaduan yang belum ditangani";
        return json_encode($jsondata);
    }else{
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
        return json_encode($jsondata);
    } 
}

?>