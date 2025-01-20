<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

require_once __DIR__ . "../../logs.php";
$logs = new Logs();
$logs->setFunctionName('mod_pn/ajax/pn5041_penetapanmanfaat_penerimaajax');
$logs->setEventName('PN5041 - Penetapan Manfaat Penerima Sentralisasi');
$logs->start();

function get_json_encode($p_url, $p_fields)
{
  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type' => 'application/json',
    'X-Forwarded-For' => $ipfwd,
  );

  $ch = curl_init();
  // Set the url, number of POST vars, POST data ----------
  curl_setopt($ch, CURLOPT_URL, $p_url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($p_fields));
  
  // Execute post ---------------------------------------
  $result = curl_exec($ch);
  // Close connection
  curl_close($ch);
	
  return $result;
}// end get_json_encode --------------------------------------------------------

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

$TYPE			= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];

if ($TYPE=="cek_validasi_adminduk")
{		
    $wsektp = new SoapClient(WSEKTP, array("location" => WSEKTPEP, "exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));

    $NIK  = $_POST["NIK"];
		$KODE_PAKET  = $_POST["KODE_PAKET"];
    $KPJ  = '0';
    $chId = 'CORE';
    if(isset($_POST["NIK"])){
      $con    =  $wsektp->callNikVerification(
                  array('chId' => $chId,
                  'username' => 'bpjs2014',
                  'password' => 'Bpjs2757',
                  'nik' => $NIK));
      
      $getData    = get_object_vars($con);
      if($getData["return"]->ret == 0){
        if($getData["return"]->addValues != ''){
          $fototk = json_decode($getData["return"]->addValues);
          for($i=0; $i<ExtendedFunction::count($fototk->dataFoto); $i++){
            $showfoto[$fototk->dataFoto[$i]->NIK] = $fototk->dataFoto[$i]->PATH_URL;
          }
        } else {
          $showfoto = '';
        }
        
        $Agama          = $getData["return"]->nilaiAgama;
        $AktaKwn        = $getData["return"]->nilaiAktaKwn;
        $Alamat         = $getData["return"]->nilaiAlamat;
        $Dusun          = $getData["return"]->nilaiDusun;
        $EktpCreated    = $getData["return"]->nilaiEktpCreated;
        $EktpLocalId    = $getData["return"]->nilaiEktpLocalId;
        $EktpStatus     = $getData["return"]->nilaiEktpStatus;
        $GolDarah       = $getData["return"]->nilaiGolDarah;
        $HubKel         = $getData["return"]->nilaiHubKel;
        $KabNo          = $getData["return"]->nilaiKabNo;
        $KecNo          = $getData["return"]->nilaiKecNo;
        $KelNo          = $getData["return"]->nilaiKelNo;
        $KodePos        = $getData["return"]->nilaiKodePos;
        $NIK            = $getData["return"]->nilaiNIK;
        $NamaAyah       = $getData["return"]->nilaiNamaAyah;
        $NamaKab        = $getData["return"]->nilaiNamaKab;
        $NamaKec        = $getData["return"]->nilaiNamaKec;
        $NamaKel        = $getData["return"]->nilaiNamaKel;
        $NamaProp       = $getData["return"]->nilaiNamaProp;
        $NoAktaCerai    = $getData["return"]->nilaiNoAktaCerai;
        $NoAktaKwn      = $getData["return"]->nilaiNoAktaKwn;
        $NoKK           = $getData["return"]->nilaiNoKK;
        $PendAkhir      = $getData["return"]->nilaiPendAkhir;
        $PenyCct        = $getData["return"]->nilaiPenyCct;
        $PropNo         = $getData["return"]->nilaiPropNo;
        $RT             = $getData["return"]->nilaiRT;
        $RW             = $getData["return"]->nilaiRW;
        $StatKwn        = $getData["return"]->nilaiStatKwn;
        $TanggalCerai   = $getData["return"]->nilaiTanggalCerai;
        $TglKwn         = $getData["return"]->nilaiTglKwn;
        $aktaLahir      = $getData["return"]->nilaiaktaLahir;
        $jenisKelamin   = $getData["return"]->nilaijenisKelamin;
        $jenisPekerjaan = $getData["return"]->nilaijenisPekerjaan;
        $namaIbu        = $getData["return"]->nilainamaIbu;
        $namaLengkap    = $getData["return"]->nilainamaLengkap;
        $noAktaLahir    = $getData["return"]->nilainoAktaLahir;
        $tanggalLahir   = $getData["return"]->nilaitanggalLahir;
        $tempatLahir    = $getData["return"]->nilaitempatLahir;
        $tanggal_Lahir 	= date_format(date_create($tanggalLahir),'d/m/Y');
								
        echo  '{
              "ret":0,
              "success":true,
              "msg":"Sukses!",
              "data":{
                  "namaLengkap":"'.$namaLengkap.'",
                  "tempatLahir":"'.$tempatLahir.'",
                  "tanggalLahir":"'.$tanggal_Lahir.'",
                  "jenisKelamin":"'.$jenisKelamin.'",
                  "namaIbu":"'.$namaIbu.'",
                  "statKwn":"'.$StatKwn.'",
									"alamat":"'.$Alamat.'",
                  "alamatLgkp":"'.$Alamat.' RT '.$RT.' / RW '.$RW.' '.$Dusun.' '.$NamaKel.' '.$NamaKec.' '.$NamaKab.' '.$NamaProp.' KODE POS '.$KodePos.'",
                  "rt":"'.$RT.'",
                  "rw":"'.$RW.'",
                  "namaKab":"'.$NamaKab.'",
                  "namaProp":"'.$NamaProp.'",
                  "kabNo":"'.$KabNo.$PropNo.'",
                  "kecNo":"'.$KecNo.'",
                  "kelNo":"'.$KelNo.'",
                  "namaKec":"'.$NamaKec.'",
                  "namaKel":"'.$NamaKel.'",
                  "golDarah":"'.$GolDarah.'",
                  "kodePos":"'.$KodePos.'",
                  "jenisPekerjaan":"'.$jenisPekerjaan.'",
                  "pendAkhir":"'.$PendAkhir.'",
									"flagPensiun":"'.$ls_flag_pensiun.'",
									"kodePaket_return":"'.$ls_kode_paket_return.'",
									"namaPaket_return":"'.$ls_nama_paket_return.'",
                  "showFoto":"'.$fototk->dataFoto[0]->PATH_URL.'"
                  }
              }';
      } else {
        echo '{"success":false,"msg":"Data tidak tersedia di database Kependudukan. Harap mengisi formulir informasi tenaga kerja berikut ini."}';
      }
    } else {
      echo '{
          "ret":-1,
          "success":false,
          "msg":"Tidak ada respon"
        }';
    }
}else if($TYPE=="cek_validasi_rekening"){
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
		echo $result;
    
}else if($TYPE=='get_list_bank'){
		$USER  = $_SESSION["USER"];
		
		//$url = $wsIp.'/JSOPG/GetListBank';
		$url = $wsIp.'/JSOPG/GetListAntarBank';
    // set HTTP header
    $headers = array(
      'Content-Type'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params
    $data = array(
      'chId'=>'CORE',
      'reqId'=>$USER,
      'BANK_ASAL'=>'BNI',     
      'BANK_TUJUAN'=>'',  
      'TIPE_TRF'=>'ATB'   
    );

   //  $data = array(
   //  	'chId'  => 'CORE',
	 //   'reqId' => $USER
   //  );
		
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
		echo $result;
		
}else if($TYPE == "getListBankAsal"){

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
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    $logs->error(json_encode($wsIp . "/JSOPG/GetListBank"), json_encode($jsondata), 'Gagal saat mencari bank pembayar berdasarkan bank tujuan');
    echo json_encode($jsondata);
  }     

}else if($TYPE == "getListBank"){
  $USER  = $_SESSION["USER"];		
  $url = $wsIp.'/JSOPG/GetListAntarBank';
  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
  
  // set POST params
  $data = array(
    'chId'=>'CORE',
    'reqId'=>$USER,
    'BANK_ASAL'=>'BNI',     
    'BANK_TUJUAN'=>'',  
    'TIPE_TRF'=>'ATB'   
  );

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
  echo $result;
}

$logs->stop('-', '-', 'Stopping	PN5041 - Penetapan Manfaat Penerima Sentralisasi');
?>
