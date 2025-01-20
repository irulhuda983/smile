<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

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

$TYPE			= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$DATAID2	= $_POST["DATAID2"];

if($TYPE=="cek_validasi_rekening")
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
        'NOREK_ASAL'=>'3389898974',  
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
		
}else if($TYPE=='val_kode_bank_trf'){
		$USER  = $_SESSION["USER"];
		$ls_kode_bank_trf  = $_POST['KODEBANK_TRF'];
		$ls_metode_trf  	 = $_POST['METODE_TRF'];
		
		//jika ATR maka set bank penerima = bank asal ------------------------------
		$sql = "select kode_bank_va, nama_bank from sijstk.ms_bank a ".
           "where a.kode_bank = '$ls_kode_bank_trf'"; 
    $DB->parse($sql);
    $DB->execute();
    $data = $DB->nextrow();
		$ls_id_bank_opg	= $data['KODE_BANK_VA'];
		$ls_nama_bank_opg	= $data['NAMA_BANK'];
					
		if ($ls_metode_trf=="ATR")
		{
			$ls_kode_bank_penerima	= $data['KODE_BANK_VA'];
  		$ls_bank_penerima				= $data['NAMA_BANK'];
      $ls_id_bank_penerima 		= $data['KODE_BANK_VA'];	
		}else
		{
  		$ls_kode_bank_penerima	= "";
  		$ls_bank_penerima				= "";
      $ls_id_bank_penerima 		= "";	
		}

    echo  '{
          "ret":0,
          "success":true,
          "msg":"Ambil bank tujuan berhasil...!",
          "data":{
              "ID_BANK_OPG":"'.$ls_id_bank_opg.'",
							"NAMA_BANK_OPG":"'.$ls_nama_bank_opg.'",
							"BANK_PENERIMA":"'.$ls_bank_penerima.'",
              "KODE_BANK_PENERIMA":"'.$ls_kode_bank_penerima.'",
							"ID_BANK_PENERIMA":"'.$ls_id_bank_penerima.'"
              }
          }';		
}
?>
