<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
//update 03/12/2020 ------------------------------------------------------------
$chId 	 	 			 = "SMILE";
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_role 	 = $_SESSION['regrole'];
$gs_kode_user 	 = $_SESSION["USER"];
$ls_type	 			 = $_POST["tipe"];

//get data grid ----------------------------------------------------------------
if($ls_type == "MainDataGrid")
{
  function handleError($errno, $errstr,$error_file,$error_line) 
	{
      if($errno == 2){
          $errorMsg = $errstr;
          if (strpos($errstr, 'failed to open stream:') !== false) {
              $errorMsg = 'Terdapat masalah dengan koneksi WebService.';
          } elseif(strpos($errstr, 'oci_connect') !== false) {
              $errorMsg = 'Terdapat masalah dengan koneksi database.';
          } else {
              $errorMsg = $errstr;
          }
        echo '{
                  "success":false,
                  "msg":"<strong>Error:</strong> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");
  
  $condition ="";
	
	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 = $_POST["search_by"];
	$ls_search_txt 	 = $_POST["search_txt"];
	$ls_search_by2 	 = $_POST["search_by2"];
	$ls_search_txt2a = $_POST["search_txt2a"];
	$ls_search_txt2b = $_POST["search_txt2b"];
	$ls_search_txt2c = $_POST["search_txt2c"];
	$ls_search_txt2d = $_POST["search_txt2d"];
	$ls_order_by 		 = $_POST["order_by"];
	$ls_order_type 	 = $_POST["order_type"];
	$ls_kategori		 = $_POST["kategori"];
	
  $ls_page 				 = $_POST["page"];
  $ls_page_item 	 = $_POST["page_item"];
	
	$ln_page 				 = is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 = is_numeric($ls_page_item) ? $ls_page_item : "10";
	
	$ln_start 			 = (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 = $ln_start + $ln_length - 1;
			
	//penanganan untuk filter data -----------------------------------------------				
  $ls_colname  = "";
  $ls_colval 	 = "";	
	
  if($ls_search_by != ''){							
  	if (($ls_search_txt != '') && ($ls_search_txt != 'null')) 
		{
  		$ls_colname = $ls_search_by;
			$ls_colval = $ls_search_txt;
		}
	}
	
  if($ls_search_by2 != '')
	{
  	if (($ls_search_txt2a != '') && ($ls_search_txt2a != 'null')) 
		{
  		$ls_colname2 = $ls_search_by2;
			$ls_colval2  = $ls_search_txt2a;
		}else
		{
    	if (($ls_search_txt2b != '') && ($ls_search_txt2b != 'null')) 
  		{
    		$ls_colname2 = $ls_search_by2;
  			$ls_colval2  = $ls_search_txt2b;
  		}else
  		{		 	
  			if (($ls_search_txt2c != '') && ($ls_search_txt2c != 'null')) 
    		{
      		$ls_colname2 = $ls_search_by2;
    			$ls_colval2  = $ls_search_txt2c;
    		}else
    		{		
					if (($ls_search_txt2d != '') && ($ls_search_txt2d != 'null')) 
					{
					 	$ls_colname2 = $ls_search_by2;
						$ls_colval2  = $ls_search_txt2d; 
					}else
					{
						$ls_colname2 = "";
      			$ls_colval2  = "";
					}	
				}			
			}			
		}						
	}
		
	//get data from WS -----------------------------------------------------------
  global $wsIp;
  $ipDev  = $wsIp."/JSPN5073/DataGrid";
  $url    = $ipDev;
	
  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
		'Accept'=> 'application/json',
  	'X-Forwarded-For'=> $ipfwd,
  );
  
  // set POST params -----------------------------------------------------------

	$data = array(
      'chId'				 => $chId,
      'reqId'				 => $gs_kode_user, 
  		'KODE_KANTOR'	 => $gs_kantor_aktif,
			'KATEGORI'		 => $ls_kategori,
      'PAGE'				 => (int)$ln_page,
      'NROWS'				 => (int)$ln_length,
      'C_COLNAME'		 => $ls_colname,
      'C_COLVAL'		 => $ls_colval,
  		'C_COLNAME2'	 => $ls_colname2,
      'C_COLVAL2'		 => $ls_colval2,
      'O_COLNAME'		 => $ls_order_by,
      'O_MODE'			 => $ls_order_type   
  );
	
  // Open connection -----------------------------------------------------------
  $ch = curl_init();
  
  // Set the url, number of POST vars, POST data -------------------------------
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  
  // Execute post --------------------------------------------------------------
  $result = curl_exec($ch);
	$resultArray = json_decode(utf8_encode($result));
	
  for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++){	
		$resultArray->DATA[$i]->NIK_PENERIMA_DISPLAY = '<a href="#" onClick="doGridView(\'view\',\''.$resultArray->DATA[$i]->NIK_PENERIMA.'\');"><font color="#009999">'.$resultArray->DATA[$i]->NIK_PENERIMA.'</font></a>';
		$resultArray->DATA[$i]->NIK_TK_DISPLAY = '<a href="#" onClick="doGridView(\'view\',\''.$resultArray->DATA[$i]->NIK_PENERIMA.'\');"><font color="#009999">'.$resultArray->DATA[$i]->NIK_TK.'</font></a>';																																										
		$resultArray->DATA[$i]->NAMA_TK_DISPLAY = '<a href="#" onClick="doGridView(\'view\',\''.$resultArray->DATA[$i]->NIK_PENERIMA.'\');"><font color="#009999">'.$resultArray->DATA[$i]->NAMA_TK.'</font></a>';
	}
	
	if ($resultArray->TOTAL_REC==0)
	{
    $jsondata["ret"] = "-1";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";
    $jsondata["recordsTotal"] = "0";
    $jsondata["recordsFiltered"] = "0";
		$jsondata["pages"] = "0";
		$jsondata["data"] = "";
		$jsondata["msg"] = "Data tidak ditemukan..";
    echo json_encode($jsondata);	
	}else
	{
	 	if ($ln_length>0)
		{	 
    	$ln_pages = ceil($resultArray->TOTAL_REC / $ln_length);
		}else
		{
		 	$ln_pages = 0;	 
		}
		
		$jsondata["ret"] = "1";
    $jsondata["start"] = $ln_start;
    $jsondata["end"] = $ln_end;
    $jsondata["page"] = $ln_page;
    $jsondata["recordsTotal"] = $resultArray->TOTAL_REC;
    $jsondata["recordsFiltered"] = $resultArray->TOTAL_REC;
    $jsondata["pages"] = $ln_pages;
		$jsondata["data"] = $resultArray->DATA;
		$jsondata["msg"] = "Sukses";
		echo json_encode($jsondata);			
	}
}
//end get data grid ------------------------------------------------------------

//get data adminduk ------------------------------------------------------------
else if($ls_type == "fjq_ajax_val_nikbyadminduk")
{		
    $wsektp = new SoapClient(WSEKTP, array("location" => WSEKTPEP, "exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));

    $NIK  = $_POST["NIK"];
		$chId = 'SMILE';
		
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
}
//end get data adminduk --------------------------------------------------------

//get data penerima beasiswa ---------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatabynikpenerima")
{
  $ls_nik_penerima 	= $_POST["v_nik_penerima"];
	
	//panggil ws utk get data konfirmasi jp berkala ------------------------------
	if ($ls_nik_penerima !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5073/GetDataByNikPenerima";
    $url    = $ipDev;
		
    // set HTTP header -----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params -----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'NIK_PENERIMA'=>$ls_nik_penerima
    );
    
    // Open connection -----------------------------------------------------
    $ch = curl_init();
    
    // Set the url, number of POST vars, POST data -------------------------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    
    // Execute post --------------------------------------------------------
    $result_DtNikPnrm = curl_exec($ch);
    $resultArray_DtNikPnrm = json_decode(utf8_encode($result_DtNikPnrm));
    
    if ($resultArray_DtNikPnrm->ret=='0')
    {
			//return data ke UI ------------------------------------------------------
      $result_data_combine = array();
      $result_data_combine["dataNikAnak"]   = $resultArray_DtNikPnrm->DATA;
			
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtNikPnrm->ret;
      $result_data_final["msg"]  = $resultArray_DtNikPnrm->msg;
      $result_data_final["data"] = $result_data_combine;
      
      echo json_encode($result_data_final);

    }else
		{
  	 	$ls_mess = $resultArray_DtNikPnrm->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		 		 		
		}
	}else
	{
	 	$ls_mess = "Data NIK Penerima Beasiswa kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	} 
} 
//end get data penerima beasiswa -----------------------------------------------

//------------------------------- BUTTON TASK ----------------------------------
//do insert data penerima beasiswa ---------------------------------------------
else if($ls_type == "fjq_ajax_val_insert_penerima_beasiswa")
{			
  $ls_nik_tk 											 = $_POST["v_nik_tk"];
	$ls_nama_tk											 = str_replace("'","''",$_POST["v_nama_tk"]);
	$ls_nik_penerima								 = $_POST["v_nik_penerima"];
	$ls_status_valid_nik_penerima		 = $_POST["v_status_valid_nik_penerima"];
	$ls_nama_penerima								 = str_replace("'","''",$_POST["v_nama_penerima"]);
  $ls_tempat_lahir 								 = str_replace("'","''",$_POST["v_tempat_lahir"]);
  $ld_tgl_lahir										 = $_POST["v_tgl_lahir"];
  $ls_jenis_kelamin								 = $_POST["v_jenis_kelamin"];
  $ls_alamat											 = str_replace("'","''",$_POST["v_alamat"]);
  $ls_email												 = $_POST["v_email"];
  $ls_handphone										 = $_POST["v_handphone"];
  $ls_nama_ortu_wali							 = str_replace("'","''",$_POST["v_nama_ortu_wali"]);
  $ls_keterangan								 	 = str_replace("'","''",$_POST["v_keterangan"]);
  $ls_status_blm_sekolah					 = $_POST["v_status_blm_sekolah"];
  $ls_nama_bank_penerima					 = $_POST["v_nama_bank_penerima"];
  $ls_handphone										 = $_POST["v_handphone"];
  $ls_kode_bank_penerima					 = $_POST["v_kode_bank_penerima"];
  $ls_id_bank_penerima						 = $_POST["v_id_bank_penerima"];
  $ls_no_rekening_penerima				 = $_POST["v_no_rekening_penerima"];
  $ls_nama_rekening_penerima			 = $_POST["v_nama_rekening_penerima"];
  $ls_status_valid_rekening_penerima = $_POST["v_status_valid_rekening_penerima"];
  $ls_notifikasi_via					 		 = $_POST["v_notifikasi_via"];
	$ls_is_verified_hp					 		 = $_POST["v_is_verified_hp"];
	$ls_kode_otp_sms_verified				 = $_POST["v_kode_otp_sms_verified"];
	$ls_sms_id											 = $_POST["v_sms_id"];
	$ls_nik_penerima_induk					 = $_POST["v_nik_penerima_induk"];
	$ls_nik_penerima_anak						 = $_POST["v_nik_penerima_anak"];
	$ls_jenis_insert						 	 	 = $_POST["v_jenis_insert"];
	$ls_ket_penggantian						 	 = $_POST["v_ket_penggantian"];
	$ls_status_penerima							 = "TERBUKA";

	if ($ls_jenis_insert=="")
	{
	 	$ls_jenis_insert = "BARU";
		$ls_ket_penggantian = ""; 
	}							
	if ($ls_status_blm_sekolah=="")
	{
	 	$ls_status_blm_sekolah = "T"; 
	}
	if ($ls_status_valid_rekening_penerima=="")
	{
	 	$ls_status_valid_rekening_penerima = "T"; 
	}
	if ($ls_status_valid_nik_penerima=="")
	{
	 	$ls_status_valid_nik_penerima = "T"; 
	}
	if ($ls_is_verified_hp=="")
	{
	 	$ls_is_verified_hp = "T"; 
	}
				
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/Insert';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima,
    "NIK_TK"							=> $ls_nik_tk,
    "NAMA_TK"							=> $ls_nama_tk,
    "NAMA_PENERIMA"				=> $ls_nama_penerima,
    "TEMPAT_LAHIR"				=> $ls_tempat_lahir,
    "TGL_LAHIR"						=> $ld_tgl_lahir,
    "JENIS_KELAMIN"				=> $ls_jenis_kelamin,
    "ALAMAT"							=> $ls_alamat,
    "HANDPHONE"						=> $ls_handphone,
    "EMAIL"								=> $ls_email,
    "NAMA_ORTU_WALI"			=> $ls_nama_ortu_wali,
    "STATUS_BLM_SEKOLAH"	=> $ls_status_blm_sekolah,
    "KODE_BANK_PENERIMA"	=> $ls_kode_bank_penerima,
    "ID_BANK_PENERIMA"		=> $ls_id_bank_penerima,
    "BANK_PENERIMA"				=> $ls_nama_bank_penerima,
    "NO_REKENING_PENERIMA"=> $ls_no_rekening_penerima,
    "NAMA_REKENING_PENERIMA"=> $ls_nama_rekening_penerima,
    "STATUS_VALID_REKENING_PENERIMA"=> $ls_status_valid_rekening_penerima,
    "STATUS_VALID_NIK_PENERIMA"=> $ls_status_valid_nik_penerima,
		"KETERANGAN"					=> $ls_keterangan,
    "KODE_KANTOR"					=> $gs_kantor_aktif,
		"NOTIFIKASI_VIA"			=> $ls_notifikasi_via,
		"STATUS_PENERIMA"			=> $ls_status_penerima,
		"SMS_ID"							=> $ls_sms_id,
		"KODE_OTP_SMS"				=> $ls_kode_otp_sms_verified,
    "KODE_OTP_EMAIL"			=> "",
		"NIK_PENERIMA_INDUK"	=> $ls_nik_penerima_induk,
		"NIK_PENERIMA_ANAK"		=> $ls_nik_penerima_anak,
		"JENIS_INSERT"				=> $ls_jenis_insert,
		"KET_PENGGANTIAN"			=> $ls_ket_penggantian
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "SIMPAN DATA ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = "GAGAL, ".substr($resultArray_dt->msg, 0, -1); 
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end do insert data penerima beasiswa -----------------------------------------

//do update data penerima beasiswa ---------------------------------------------
else if($ls_type == "fjq_ajax_val_update_penerima_beasiswa")
{			
  $ls_nik_tk 											 = $_POST["v_nik_tk"];
	$ls_nama_tk											 = str_replace("'","''",$_POST["v_nama_tk"]);
	$ls_nik_penerima								 = $_POST["v_nik_penerima"];
	$ls_status_valid_nik_penerima		 = $_POST["v_status_valid_nik_penerima"];
	$ls_nama_penerima								 = str_replace("'","''",$_POST["v_nama_penerima"]);
  $ls_tempat_lahir 								 = str_replace("'","''",$_POST["v_tempat_lahir"]);
  $ld_tgl_lahir										 = $_POST["v_tgl_lahir"];
  $ls_jenis_kelamin								 = $_POST["v_jenis_kelamin"];
  $ls_alamat											 = str_replace("'","''",$_POST["v_alamat"]);
  $ls_email												 = $_POST["v_email"];
  $ls_handphone										 = $_POST["v_handphone"];
  $ls_nama_ortu_wali							 = str_replace("'","''",$_POST["v_nama_ortu_wali"]);
  $ls_keterangan								 	 = str_replace("'","''",$_POST["v_keterangan"]);
  $ls_status_blm_sekolah					 = $_POST["v_status_blm_sekolah"];
  $ls_nama_bank_penerima					 = $_POST["v_nama_bank_penerima"];
  $ls_handphone										 = $_POST["v_handphone"];
  $ls_kode_bank_penerima					 = $_POST["v_kode_bank_penerima"];
  $ls_id_bank_penerima						 = $_POST["v_id_bank_penerima"];
  $ls_no_rekening_penerima				 = $_POST["v_no_rekening_penerima"];
  $ls_nama_rekening_penerima			 = $_POST["v_nama_rekening_penerima"];
  $ls_status_valid_rekening_penerima = $_POST["v_status_valid_rekening_penerima"];
  $ls_notifikasi_via					 		 = $_POST["v_notifikasi_via"];
	$ls_status_penerima							 = $_POST["v_status_penerima"];
	$ls_jenis_update							 	 = $_POST["v_jenis_update"];
	
	if ($ls_status_penerima=="")
	{
	 	$ls_status_penerima = "TERBUKA"; 	 
	}
	
	if ($ls_status_blm_sekolah=="")
	{
	 	$ls_status_blm_sekolah = "T"; 
	}
	if ($ls_status_valid_rekening_penerima=="")
	{
	 	$ls_status_valid_rekening_penerima = "T"; 
	}
	if ($ls_status_valid_nik_penerima=="")
	{
	 	$ls_status_valid_nik_penerima = "T"; 
	}
			
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/Update';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima,
    "NIK_TK"							=> $ls_nik_tk,
    "NAMA_TK"							=> $ls_nama_tk,
    "NAMA_PENERIMA"				=> $ls_nama_penerima,
    "TEMPAT_LAHIR"				=> $ls_tempat_lahir,
    "TGL_LAHIR"						=> $ld_tgl_lahir,
    "JENIS_KELAMIN"				=>$ls_jenis_kelamin,
    "ALAMAT"							=> $ls_alamat,
    "HANDPHONE"						=> $ls_handphone,
    "EMAIL"								=> $ls_email,
    "NAMA_ORTU_WALI"			=> $ls_nama_ortu_wali,
    "STATUS_BLM_SEKOLAH"	=> $ls_status_blm_sekolah,
    "KODE_BANK_PENERIMA"	=> $ls_kode_bank_penerima,
    "ID_BANK_PENERIMA"		=> $ls_id_bank_penerima,
    "BANK_PENERIMA"				=> $ls_nama_bank_penerima,
    "NO_REKENING_PENERIMA"=> $ls_no_rekening_penerima,
    "NAMA_REKENING_PENERIMA"=> $ls_nama_rekening_penerima,
    "STATUS_VALID_REKENING_PENERIMA"=> $ls_status_valid_rekening_penerima,
    "KODE_OTP_SMS"				=> "",
    "KODE_OTP_EMAIL"			=> "",
    "STATUS_VALID_NIK_PENERIMA"=> $ls_status_valid_nik_penerima,
    "KETERANGAN"					=> $ls_keterangan,
    "KODE_KANTOR"					=> $gs_kantor_aktif,
    "NOTIFIKASI_VIA"			=> $ls_notifikasi_via,
		"STATUS_PENERIMA"			=> $ls_status_penerima,
		"JENIS_UPDATE"				=> $ls_jenis_update
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "SIMPAN DATA ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = $resultArray_dt->msg;
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end do update data penerima beasiswa -----------------------------------------

//do hapus data penerima beasiswa ----------------------------------------------
else if($ls_type == "fjq_ajax_val_delete_penerima_beasiswa")
{			
  $ls_nik_penerima = $_POST["v_nik_penerima"];
			
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/Delete';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "HAPUS DATA ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = $resultArray_dt->msg; 
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end do hapus data penerima beasiswa ------------------------------------------

//do submit data penerima beasiswa ---------------------------------------------
else if($ls_type == "fjq_ajax_val_submit_penerima_beasiswa")
{			
  $ls_nik_penerima = $_POST["v_nik_penerima"];
			
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/SubmitData';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "SUBMIT DATA ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = $resultArray_dt->msg; 
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end do submit data penerima beasiswa -----------------------------------------

//do submit data penundaan beasiswa --------------------------------------------
else if($ls_type == "fjq_ajax_val_submit_penundaan_beasiswa")
{			
  $ls_nik_penerima = $_POST["v_nik_penerima"];
			
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/SubmitDataTunda';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "SUBMIT DATA PENUNDAAN ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = $resultArray_dt->msg; 
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end do submit data penundaan beasiswa ----------------------------------------

//do batalkan data penerima beasiswa -------------------------------------------
else if($ls_type == "fjq_ajax_val_batal_penerima_beasiswa")
{			
  $ls_nik_penerima = $_POST["v_nik_penerima"];
			
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/Pembatalan';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "PEMBATALAN DATA ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = $resultArray_dt->msg; 
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end do batalkan data penerima beasiswa ---------------------------------------

//validasi penggantian anak penerima beasiswa ----------------------------------
else if($ls_type == "fjq_ajax_val_penggantian_penerima_beasiswa")
{			
  $ls_nik_penerima  = $_POST["v_nik_penerima"];
	$ls_keterangan 	 	= $_POST["v_keterangan"];
	$ls_nik_pengganti = $_POST["v_nik_pengganti"];
			
	global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5073/GantiPenerima';
	
  $data = array(
    "chId"  							=> $chId,
    "reqId" 							=> $USER,
    "NIK_PENERIMA"				=> $ls_nik_penerima,
		"KETERANGAN"					=> $ls_keterangan,
		"NIK_PENGGANTI"				=> $ls_nik_pengganti
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
						
  if ($resultArray_dt->ret=='0')
  {
	  $ls_mess = "SUBMIT PENGGANTIAN DATA ANAK PENERIMA BEASISWA BERHASIL, SESSION DILANJUTKAN..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
    if ($resultArray_dt->P_SUKSES == "-1")
		{
		 	$ls_mess = $resultArray_dt->P_MESS; 
		}else
		{
		 	$ls_mess = $resultArray_dt->msg;
    }
		
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';	
  }		 	 
} 
//end validasi penggantian anak penerima beasiswa ------------------------------

//pengiriman sms verifikasi ----------------------------------------------------
else if($ls_type == "fjq_ajax_val_kirim_sms_verifikasi")
{			
  $ls_handphone 	= $_POST["v_handphone"];
			
  //kirim sms verifikasi -------------------------------------------------------
	global $wsIp;
	$wscom = $wsIp."/SmsApps/services/Main?wsdl";
	
  $proxy_ips = getenv('HTTP_X_FORWARDED_FOR');
  if ($proxy_ips==""){ $proxy_ips = getenv('REMOTE_ADDR'); }
  
  $ws_sms = new SoapClient($wscom, array("exceptions" => 0, "trace" => 1, "encoding" => "UTF-8", 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$proxy_ips)))));
  
	$ls_kode_otp_sms = mt_rand(100000,999999);
	$ls_text_sms = "Berikut kode verifikasi utk diinformasikan ke Petugas BPJS Ketenagakerjaan saat perekaman anak penerima manfaat beasiswa: ".$ls_kode_otp_sms;
	
  $response = $ws_sms->sendSMS(array(
                'username'=>"smile",
                'password'=>"smile123",
                'msisdn'=>'62'.ltrim($ls_handphone, "0"),
                'txt'=>$ls_text_sms,
                'avl'=>'{"channel":"1"}'
              ));        
	$getData    = get_object_vars($response);
  
  if($getData["return"]->ret == 0)
	{
	  $ls_mess = "KIRIM SMS VERIFIKASI BERHASIL.. <br/>SILAHKAN DILANJUTKAN DENGAN VERIFIKASI KODE OTP YANG SUDAH DIKIRIMKAN KE HANDPHONE ANAK PENERIMA MANFAAT BEASISWA.";  
		$ls_kode_otp_sms_encode = base64_encode($ls_kode_otp_sms);
		$ls_sms_id =  json_decode($getData["return"]->kode)->msgid;  
		echo  '{
      "ret":0,
      "success":true,
			"kode_otp_sms":"'.$ls_kode_otp_sms_encode.'",
			"kode_otp_sms_asli":"'.$ls_kode_otp_sms.'",
			"sms_id":"'.$ls_sms_id.'",
      "msg":"'.$ls_mess.'"
    }';	
	}else
	{
	  $ls_msg = "SMS Verifikasi gagal dikirimkan, ".$getData["return"]->msg;
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_msg.'"
    }';	
	}	 	 
} 
//end pengiriman sms verifikasi ------------------------------------------------

//validasi kode otp sms --------------------------------------------------------
else if($ls_type == "fjq_ajax_val_validate_kode_otp_sms")
{			
  $ls_kode_otp_sms_verified = $_POST["v_kode_otp_sms_verified"];
	$ls_kode_otp_sms 	 				= $_POST["v_kode_otp_sms"];
	$ls_kode_otp_sms_decode 	= base64_decode($ls_kode_otp_sms);
						
  if ($ls_kode_otp_sms_verified==$ls_kode_otp_sms_decode)
  {
	  $ls_mess = "KODE OTP SMS SESUAI, NOMOR HANDPHONE VALID, SESSION DILANJUTKAN ..";	 
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
		$ls_mess = "KODE OTP SMS SALAH ..<br/>HARAP MENGISIKAN KODE OTP YANG SUDAH DIKIRIMKAN KE HANDPHONE ANAK PENERIMA MANFAAT BEASISWA ..";
		echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';
  }		 	 
} 
//end validasi kode otp sms ----------------------------------------------------

//pengiriman email verifikasi --------------------------------------------------
else if($ls_type == "fjq_ajax_val_kirim_email_verifikasi")
{			
  $ls_email_tujuan 	= $_POST["v_email"];
	$ls_nik_penerima 	= $_POST["v_nik_penerima"];
	$ls_nama_penerima = $_POST["v_nama_penerima"];
			
  //kirim email verifikasi -----------------------------------------------------
	global $wsIp;
	$wscom = $wsIp."/WSCom/services/Main";
	
  $proxy_ips = getenv('HTTP_X_FORWARDED_FOR');
  if ($proxy_ips==""){ $proxy_ips = getenv('REMOTE_ADDR'); }
    
  $ws_email = new SoapClient($wscom."?wsdl", array("location" => $wscom, "exceptions" => 0, "trace" => 1, "encoding" => "UTF-8", "stream_context" => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$proxy_ips)))));
    
  $ls_subject = "Verifikasi alamat email anak penerima manfaat beasiswa";
	$ls_html_email = "Silahkan klik <a href='http://dev.bpjsketenagakerjaan.go.id/wssvc/ev/?e=".$ls_email_tujuan."&v=".$ls_nik_penerima."&m=pn5073'> <strong> Ya </strong></a> untuk menyatakan bahwa alamat email adalah benar digunakan untuk notifikasi pengambilan manfaat beasiswa BPJS Ketenagakerjaan.<br />";
								  	
  $response = $ws_email->sendEmail(array(
                  'cfg' => "noreply.smile",
                  'from'=> "BPJS Ketenagakerjaan <noreply@bpjsketenagakerjaan.go.id>",
                  'to'  => $ls_email_tujuan,
                  'cc'  => "",
                  'bcc' => "",
                  'bcc' => "",
                  'subject' => $ls_subject,
                  'body'=> "Versi text plain",
                  'isHTML'=> 'Y',
                  'bodyHTML'=> base64_encode($ls_html_email),
                  'isAttach'=> "",
                  'attach'=> "",
                  'attachName'=> "",
                  'avl' => ""
                ));
  $getData    = get_object_vars($response);
	
  if($getData["return"]->ret == 0)
	{
	  $ls_mess = "KIRIM EMAIL VERIFIKASI BERHASIL.. <br/>HARAP MENGINFORMASIKAN KE ANAK PENERIMA MANFAAT BEASISWA UNTUK MENGKLIK LINK VERIFIKASI YANG SUDAH DIKIRIMKAN KE ALAMAT EMAIL ".$ls_email_tujuan;  
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';	
	}else
	{
	  $ls_msg = "Email Verifikasi gagal dikirimkan, ".$getData["return"]->msg;
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_msg.'"
    }';	
	}	 	 
} 
//end pengiriman email verifikasi ----------------------------------------------

//------------------------------ END BUTTON TASK -------------------------------
?>
