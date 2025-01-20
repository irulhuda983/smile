<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$chId 	 	 			 = "SMILE";
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_role 	 = $_SESSION['regrole'];
$gs_kode_user 	 = $_SESSION["USER"];
$ls_type	 			 = $_POST["tipe"];

function api_json_call1($apiurl, $header, $data) {
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

// ---------------------------- LUMPSUM ----------------------------------------
//get data grid ----------------------------------------------------------------
if($ls_type == "MainDataGridLumpsumVerif")
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
                  "msg":"<b>Error:</b> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition ="";

	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 	 		= $_POST["search_by"];
	$ls_search_txt 	 			= $_POST["search_txt"];
	$ls_search_by2 	 			= $_POST["search_by2"];
	$ls_search_txt2a 			= $_POST["search_txt2a"];
	$ls_search_txt2b 			= $_POST["search_txt2b"];
	$ls_search_txt2c 			= $_POST["search_txt2c"];
	$ls_search_txt2d 			= $_POST["search_txt2d"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];
  $ls_jenis_pembayaran	= $_POST["jenis_pembayaran"];
  $ls_status_siapbayar	= $_POST["status_siapbayar"];
  $ld_tgl1 							= $_POST["tgl_awal"];
  $ld_tgl2 							= $_POST["tgl_akhir"];

  $ls_page 				 			= $_POST["page"];
  $ls_page_item 	 			= $_POST["page_item"];

	$ln_page 				 			= is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 			= is_numeric($ls_page_item) ? $ls_page_item : "10";

	$ln_start 			 			= (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 			= $ln_start + $ln_length - 1;

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
  $ipDev  = $wsIp."/JSPN5043/DataGridLumpsum";
  $url    = $ipDev;

  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
		'Accept'=> 'application/json',
  	'X-Forwarded-For'=> $ipfwd,
  );

  // set POST params -----------------------------------------------------------
	$data = array(
      'chId'				 			=> $chId,
      'reqId'				 			=> $gs_kode_user,
			'TGL_AWAL'		 			=> $ld_tgl1,
      'TGL_AKHIR'		 			=> $ld_tgl2,
  		'STATUS_SIAPBAYAR'	=> (int)$ls_status_siapbayar,
			'KODE_KANTOR'	 			=> $gs_kantor_aktif,
      'PAGE'				 			=> (int)$ln_page,
      'NROWS'				 			=> (int)$ln_length,
      'C_COLNAME'		 			=> $ls_colname,
      'C_COLVAL'		 			=> $ls_colval,
  		'C_COLNAME2'	 			=> $ls_colname2,
      'C_COLVAL2'		 			=> $ls_colval2,
      'O_COLNAME'		 			=> $ls_order_colname,
      'O_MODE'			 			=> $ls_order_mode
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

  if ($ls_status_siapbayar=="1")
  {
   	$ls_action_status = "Verifikasi";
  }elseif ($ls_status_siapbayar=="3")
  {
   	$ls_action_status = "Pembayaran";
  }elseif ($ls_status_siapbayar=="4")
  {
   	$ls_action_status = "Cek Status";
  }

	//var_dump($resultArray);
  for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++){
		$resultArray->DATA[$i]->ACTION = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$resultArray->DATA[$i]->KODE_KLAIM.'"><a href="#" onClick="fl_js_lumpverif_doGridTask(\''.$resultArray->DATA[$i]->KODE_KLAIM.'\',\''.$ls_jenis_pembayaran.'\',\''.$ls_status_siapbayar.'\',\''.$ld_tgl1.'\',\''.$ld_tgl2.'\');"><img src="../../images/user_go.png" border="0" alt="Pembayaran Klaim" align="absmiddle" /><font color="#009999">'.$ls_action_status.'</font></a>';
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

//get data by kode klaim -------------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatabykodeklaim")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];

  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="")
  {
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5040/GetDataByKodeKlaim";
    $url    = $ipDev;

    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );

    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim
    );

    // Open connection ----------------------------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data ------------------------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post -------------------------------------------------------
    $resultDtByKdKlaim = curl_exec($ch);
    $resultArray_DtByKdKlaim = json_decode(utf8_encode($resultDtByKdKlaim));

		if ($resultArray_DtByKdKlaim->ret=='0')
    {
      $ls_status_rekening_sentral = $resultArray_DtByKdKlaim->INFO_KLAIM->IS_CURR_REKENING_SENTRAL =="" ? "T" : $resultArray_DtByKdKlaim->INFO_KLAIM->IS_CURR_REKENING_SENTRAL;

			//ambil data penetapan manfaat lumpsum ------------------------------
      $ipDev  = "";
      global $wsIp;
      $ipDev  = $wsIp."/JSPN5041/ListManfaatByKodeKlaim";
      $url    = $ipDev;

      // set HTTP header ---------------------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );

      // set POST params ---------------------------------------------
      $data = array(
  			'chId'=>$chId,
        'reqId'=>$gs_kode_user,
        'KODE_KLAIM'=>$ls_kode_klaim
      );

      // Open connection ---------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data -----------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post ------------------------------------------------
      $result_ListMnf = curl_exec($ch);
      $resultArray_ListMnf = json_decode(utf8_encode($result_ListMnf));
			//end ambil data penetapan manfaat lumpsum --------------------------

			//ambil data penerima manfaat lumpsum -------------------------------
			$ipDev  = "";
      global $wsIp;
      $ipDev  = $wsIp."/JSPN5041/ListPenerimaManfaatByKodeKlaim";
      $url    = $ipDev;

      // set HTTP header ---------------------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );

      // set POST params ---------------------------------------------
      $data = array(
        'chId'=>$chId,
        'reqId'=>$gs_kode_user,
        'KODE_KLAIM'=>$ls_kode_klaim
      );

      // Open connection ---------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data -----------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post ------------------------------------------------
      $result_ListPenerimaMnf = curl_exec($ch);
      $resultArray_ListPenerimaMnf = json_decode(utf8_encode($result_ListPenerimaMnf));
			//ambil data penerima manfaat lumpsum -------------------------------

			//ambil pembayaran manfaat lumpsum ----------------------------------
			$ipDev  = "";
      global $wsIp;
      $ipDev  = $wsIp."/JSPN5043/ListLumpsumByKodeKlaim";
      $url    = $ipDev;

      // set HTTP header ---------------------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );

      // set POST params ---------------------------------------------
      $data = array(
        'chId'=>$chId,
        'reqId'=>$gs_kode_user,
        'KODE_KLAIM'=>$ls_kode_klaim
      );

      // Open connection ---------------------------------------------
      $ch = curl_init();

      // Set the url, number of POST vars, POST data -----------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

      // Execute post ------------------------------------------------
      $result_ListByrMnf = curl_exec($ch);
      $resultArray_ListByrMnf = json_decode(utf8_encode($result_ListByrMnf));
			//ambil pembayaran  manfaat lumpsum ----------------------------

			//get data khusus klaim jp ---------------------------------------
			//update 24/08/2020-----------------------------------------------
			if ($resultArray_DtByKdKlaim->INFO_KLAIM->KODE_TIPE_KLAIM=="JPN01")
			{
        //ambil penetapan ahli waris jp --------------------------------
				$ipDev  	= "";
        global $wsIp;
        $ipDev  	= $wsIp."/JSPN5040/ListDataAhliWarisJP";
        $url    	= $ipDev;

        // set HTTP header ---------------------------------------------
        $headers = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );

        // set POST params ---------------------------------------------
        $data = array(
          'chId'				=>$chId,
          'reqId'				=>$gs_kode_user,
          'KODE_KLAIM'	=>$ls_kode_klaim
        );

        // Open connection ---------------------------------------------
        $ch = curl_init();

        // Set the url, number of POST vars, POST data -----------------
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute post ------------------------------------------------
        $result_ListJpnAhliwaris = curl_exec($ch);
        $resultArray_ListJpnAhliwaris = json_decode(utf8_encode($result_ListJpnAhliwaris));
				//end ambil penetapan ahli waris jp ----------------------------

        //ambil penetapan manfaat jp berkala ---------------------------
				$ipDev  	= "";
        global $wsIp;
        $ipDev  	= $wsIp."/JSPN5041/ListManfaatBerkalaByKodeKlaim";
        $url    	= $ipDev;

        // set HTTP header ---------------------------------------------
        $headers = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );

        // set POST params ---------------------------------------------
        $data = array(
          'chId'				=>$chId,
          'reqId'				=>$gs_kode_user,
          'KODE_KLAIM'	=>$ls_kode_klaim,
					'NO_KONFIRMASI'=>0
        );

        // Open connection ---------------------------------------------
        $ch = curl_init();

        // Set the url, number of POST vars, POST data -----------------
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute post ------------------------------------------------
        $result_ListMnfBerkala = curl_exec($ch);
        $resultArray_ListMnfBerkala = json_decode(utf8_encode($result_ListMnfBerkala));
				//end ambil penetapan manfaat jp berkala -----------------------

        //ambil penetapan penerima manfaat jp berkala ------------------
				$ipDev  	= "";
        global $wsIp;
        $ipDev  	= $wsIp."/JSPN5041/ListPenerimaMnfBerkalaByKodeKlaim";
        $url    	= $ipDev;

        // set HTTP header ---------------------------------------------
        $headers = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );

        // set POST params ---------------------------------------------
        $data = array(
          'chId'				=>$chId,
          'reqId'				=>$gs_kode_user,
          'KODE_KLAIM'	=>$ls_kode_klaim,
					'NO_KONFIRMASI'=>0
        );

        // Open connection ---------------------------------------------
        $ch = curl_init();

        // Set the url, number of POST vars, POST data -----------------
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute post ------------------------------------------------
        $result_ListPenerimaMnfBerkala = curl_exec($ch);
        $resultArray_ListPenerimaMnfBerkala = json_decode(utf8_encode($result_ListPenerimaMnfBerkala));
				//end ambil penetapan penerima manfaat jp berkala --------------
			}
			//end get data khusus klaim jp -----------------------------------

			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtByKdKlaim->ret;
      $result_data_final["msg"]  = $resultArray_DtByKdKlaim->msg;
      $result_data_final["data"] = $resultArray_DtByKdKlaim;
			$result_data_final["dataIsRekeningSentral"]  = $ls_status_rekening_sentral;
			$result_data_final["dataTapMnfLumpsum"] = $resultArray_ListMnf;
			$result_data_final["dataPenerimaMnfLumpsum"] = $resultArray_ListPenerimaMnf;
			$result_data_final["dataListByr"] = $resultArray_ListByrMnf;

			if ($resultArray_DtByKdKlaim->INFO_KLAIM->KODE_TIPE_KLAIM=="JPN01")
			{
			 	$result_data_final["dataJpnListAws"] = $resultArray_ListJpnAhliwaris;
				$result_data_final["dataTapMnfBerkala"] = $resultArray_ListMnfBerkala;
				$result_data_final["dataPenerimaMnfBerkala"] = $resultArray_ListPenerimaMnfBerkala;
			}

      echo json_encode($result_data_final);
		}else
		{
  	 	$ls_mess = $resultArray_DtByKdKlaim->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';
		}
  }else
	{
	 	$ls_mess = "Kode Klaim kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
	//end get data informasi klaim by kode_klaim from WS --------------------
}
//end get data by kode klaim ---------------------------------------------------

//get data detil siap bayar ----------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatasiapbayar_detil")
{
  $ls_kode_klaim 		 		 = $_POST["v_kode_klaim"];
	$ls_kode_tipe_penerima = $_POST["v_kode_tipe_penerima"];
	$ls_kd_prg 					 	 = $_POST["v_kd_prg"];

  if ($ls_kode_klaim!="" && $ls_kode_tipe_penerima!="" && $ls_kd_prg!="")
  {
    //get data from ws --------------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/JSPN5043/ViewSiapByrLumpsumByTipe";
    $url    	= $ipDev;

    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );

    // set POST params -----------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_TIPE_PENERIMA'=>$ls_kode_tipe_penerima,
  		'KD_PRG'=>(int)$ls_kd_prg
    );

    // Open connection -----------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data -------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post --------------------------------------
    $result_SiapByr = curl_exec($ch);
    $resultArray_SiapByr = json_decode(utf8_encode($result_SiapByr));
    //end get data from ws -----------------------------------------------

		if ($resultArray_SiapByr->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_SiapByr->ret;
      $result_data_final["msg"]  = $resultArray_SiapByr->msg;
      $result_data_final["dataDtlSiapByr"] = $resultArray_SiapByr;

      echo json_encode($result_data_final);
		}else
		{
  	 	$ls_mess = $resultArray_SiapByr->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/kode manfaat/program kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data detil siap bayar ------------------------------------------------

//------- GET LIST BANK PEMBAYARAN UTK PEMBAYARAN TUNAI MELALUI VA DEBIT -------
else if ($ls_type == "get_list_bank_asal_va_debit")
{
	//panggil ws utk listbank opg yg support pembayaran tunai va debit -----------
  global $wsIp;

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSOPG/GetListBank';

  $data = array(
    "chId"  => $chId,
    "reqId" => $gs_kode_user
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
//----- END GET LIST BANK PEMBAYARAN UTK PEMBAYARAN TUNAI MELALUI VA DEBIT -----

else if ($ls_type=="get_list_bank_asal")
{
	global $wsIp;

  $USER  		 					= $gs_kode_user;
  $ls_jenis_transaksi	= $_POST['JENIS_TRANSAKSI']; // PENGEMBALIAN_IURAN, KLAIM
  $ls_id_transaksi  	= $_POST['ID_TRANSAKSI'];		 // JIKA KLAIM MAKA KIRIMKAN KODE_KLAIM
  $ls_kode_bank_atb  	= $_POST['KODE_BANK_ATB'];

		//validasi rekening --------------------------------------------------------
		$url = $wsIp.'/JSKN5033/GetListBankAsal';

		// set HTTP header
    $headers = array(
      'Content-Type'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );

   // set POST params
    $data = array(
      'chId'         	 	=> 'SMILE',
      'reqId'        		=> $USER,
      'JENIS_TRANSAKSI' => $ls_jenis_transaksi,
			'KODE_KLAIM' 			=> $ls_id_transaksi,
			'KODE_BANK_ATB' 	=> $ls_kode_bank_atb
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

//validasi rekening ------------------------------------------------------------
else if($ls_type=="validate_rekening_tujuan")
{
  //update terbaru per 19/08/2020 tanpa cek bank opg -------------------------
  global $wsIp;
  $USER  		 					= $gs_kode_user;
  $ls_norek_tujuan  	= $_POST['NO_REK_TUJUAN'];
  $ls_kodebank_tujuan = $_POST['KODE_BANK_ATB_TUJUAN'];
  $ls_namabank_tujuan = $_POST['NAMA_BANK_TUJUAN'];

  $url = $wsIp.'/JSOPG/GetAccountInfo';

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

  $data = array(
    'chId'=>'SMILE',
    'reqId'=>$USER,
    'bank'=>$ls_namabank_tujuan,
    'KODE_BANK_ATB'=>$ls_kodebank_tujuan,
    'NOREK_TUJUAN'=>$ls_norek_tujuan
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
//end validasi rekening --------------------------------------------------------

//------------------------------- BUTTON TASK ----------------------------------
//do simpan update penerima manfaat --------------------------------------------
else if($ls_type == "fjq_ajax_val_byr_penerima_save_update")
{
	$ls_kode_klaim            				= $_POST["v_kode_klaim"];
  $ls_kode_tipe_penerima    				= $_POST["v_kode_tipe_penerima"];
  $ls_handphone             				= $_POST["v_handphone"];
  $ls_email                 				= str_replace("'","''",$_POST["v_email"]);
  $ls_nama_bank_penerima    				= $_POST["v_nama_bank_penerima"];
  $ls_kode_bank_penerima    				= $_POST["v_kode_bank_penerima"];
  $ls_id_bank_penerima      				= $_POST["v_id_bank_penerima"];
  $ls_no_rekening_penerima  				= $_POST["v_no_rekening_penerima"];
  $ls_nama_rekening_penerima    		= str_replace("'","''",$_POST["v_nama_rekening_penerima"]);
  $ls_st_valid_rekening_penerima    = $_POST["v_status_valid_rekening_penerima"];
  $ls_kode_bank_pembayar    				= $_POST["v_kode_bank_pembayar"];
  $ls_status_rekening_sentral   		= $_POST["v_status_rekening_sentral"];
  $ls_kantor_rekening_sentral   		= $_POST["v_kantor_rekening_sentral"];
  $ls_metode_transfer           		= $_POST["v_metode_transfer"];
  $ls_keterangan            				= str_replace("'","''",$_POST["v_keterangan"]);
	$ls_npwp           								= $_POST["v_npwp"];
	$ls_kode_cara_bayar       				= $_POST["v_kode_cara_bayar"];

	//update data penerima manfaat -----------------------------------------------
  global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/PenerimaManfaatUpdate';

	$dataKlaim = array(
        array (
          "KODE_KLAIM"		 		 							=> $ls_kode_klaim,
          "KODE_TIPE_PENERIMA"		 		 			=> $ls_kode_tipe_penerima,
          "KODE_HUBUNGAN"		 		 						=> "",
          "KET_HUBUNGAN_LAINNYA"		 		 		=> "",
          "NO_URUT_KELUARGA"		 		 				=> 99,
          "NOMOR_IDENTITAS"		 		 					=> "",
          "NAMA_PEMOHON"		 		 						=> "",
          "TEMPAT_LAHIR"		 		 						=> "",
          "TGL_LAHIR"		 		 								=> "",
          "JENIS_KELAMIN"		 		 						=> "",
          "ALAMAT"		 		 									=> "",
          "RT"		 		 											=> "",
          "RW"		 		 											=> "",
          "KODE_KELURAHAN"		 		 					=> 0,
          "KODE_KECAMATAN"		 		 					=> 0,
          "KODE_KABUPATEN"		 		 					=> 0,
          "KODE_POS"		 		 								=> "",
          "TELEPON_AREA"		 		 						=> "",
          "TELEPON"		 		 									=> "",
          "TELEPON_EXT"		 		 							=> "",
          "HANDPHONE"		 		 								=> $ls_handphone,
          "EMAIL"		 		 										=> $ls_email,
          "NPWP"		 		 										=> $ls_npwp,
          "NAMA_PENERIMA"		 		 						=> "",
          "BANK_PENERIMA"		 		 						=> $ls_nama_bank_penerima,
          "NO_REKENING_PENERIMA"		 		 		=> $ls_no_rekening_penerima,
          "NAMA_REKENING_PENERIMA"		 		 	=> $ls_nama_rekening_penerima,
          "NOM_MANFAAT_UTAMA"		 		 				=> 0,
          "NOM_MANFAAT_TAMBAHAN"		 		 		=> 0,
          "NOM_MANFAAT_GROSS"		 		 				=> 0,
          "NOM_PPN"		 		 									=> 0,
          "NOM_PPH"		 		 									=> 0,
          "NOM_PEMBULATAN"		 		 					=> 0,
          "NOM_MANFAAT_NETTO"		 		 				=> 0,
          "KODE_BANK_PEMBAYAR"		 		 			=> $ls_kode_bank_pembayar,
          "KETERANGAN"		 		 							=> $ls_keterangan,
          "STATUS_LUNAS"		 		 						=> "",
          "TGL_LUNAS"		 		 								=> "",
          "PETUGAS_LUNAS"		 		 						=> "",
          "GOLONGAN_DARAH"		 		 					=> "",
          "JENIS_IDENTITAS"		 		 					=> "",
          "STATUS_VALID_IDENTITAS"		 		 	=> "",
          "KODE_BANK_PENERIMA"		 		 			=> $ls_kode_bank_penerima,
          "ID_BANK_PENERIMA"		 		 				=> $ls_id_bank_penerima,
          "STATUS_VALID_REKENING_PENERIMA"	=> $ls_st_valid_rekening_penerima,
          "STATUS_REKENING_SENTRAL"		 		 	=> $ls_status_rekening_sentral,
          "KANTOR_REKENING_SENTRAL"		 		 	=> $ls_kantor_rekening_sentral,
          "METODE_TRANSFER"		 		 					=> $ls_metode_transfer,
          "KODE_NEGARA"		 		 							=> "",
					"KODE_CARA_BAYAR"		 		 					=> $ls_kode_cara_bayar
        )
	);

  $data = array(
    "chId"  => $chId,
    "reqId" => $USER,
    "dataKlaim" => $dataKlaim
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
	$result = utf8_encode(curl_exec($ch));
	$resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
    $ls_mess = "SIMPAN DATA PENERIMA MANFAAT BERHASIL, SESSION DILANJUTKAN";
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		$ls_mess = $ls_mess." </br> ".$resultArray->P_MESS;
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
  }
}
//end do simpan update penerima manfaat ----------------------------------------

//do submit data siap bayar ----------------------------------------------------
else if($ls_type == "fjq_ajax_val_byr_doSiapTransfer")
{
	$ls_kode_klaim            				= $_POST["v_kode_klaim"];
  $ls_kode_tipe_penerima    				= $_POST["v_kode_tipe_penerima"];
  $ls_kd_prg             						= $_POST["v_kd_prg"];
	$ls_kantor_siapbayar             	= $_POST["v_kantor_siapbayar"];
	if ($ls_kantor_siapbayar=="")
	{
	 	$ls_kantor_siapbayar = $gs_kantor_aktif;
	}

	//submit data siap bayar -----------------------------------------------------
  global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/SubmitSiapBayar';

  $data = array(
    "chId"  => $chId,
    "reqId" => $USER,
    "KODE_KLAIM" => $ls_kode_klaim,
		"KODE_TIPE_PENERIMA" => $ls_kode_tipe_penerima,
		"KD_PRG" => (int)$ls_kd_prg,
		"KANTOR_SIAPBAYAR" => $ls_kantor_siapbayar
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
	$result = utf8_encode(curl_exec($ch));
	$resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
    $ls_mess = "SUBMIT DATA SIAP BAYAR BERHASIL, SESSION DILANJUTKAN";
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		$ls_mess = $ls_mess." </br> ".$resultArray->P_MESS;
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
  }
}
//end do submit data siap transfer ---------------------------------------------

//get data pembayaran ----------------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatapembayaran")
{
  $ls_kode_klaim 		 	= $_POST["v_kode_klaim"];
	$ls_kode_pembayaran = $_POST["v_kode_pembayaran"];

  if ($ls_kode_klaim!="" && $ls_kode_pembayaran!="")
  {
    //get data from ws --------------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/JSPN5043/ViewLumpsumByKodePembayaran";
    $url    	= $ipDev;

    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );

    // set POST params -----------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_PEMBAYARAN'=>$ls_kode_pembayaran
    );

    // Open connection -----------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data -------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post --------------------------------------
    $result_DataByr = curl_exec($ch);
    $resultArray_DataByr = json_decode(utf8_encode($result_DataByr));
    //end get data from ws -----------------------------------------------

		if ($resultArray_DataByr->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DataByr->ret;
      $result_data_final["msg"]  = $resultArray_DataByr->msg;
      $result_data_final["dataByr"] = $resultArray_DataByr;

      echo json_encode($result_data_final);
		}else
		{
  	 	$ls_mess = $resultArray_DataByr->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/kode pembayaran kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data pembayaran ------------------------------------------------------

//----------------------------- END BUTTON TASK --------------------------------
// ------------------------------ END LUMPSUM ----------------------------------

// ------------------------------ JP BERKALA -----------------------------------
//get data grid ----------------------------------------------------------------
else if ($ls_type == "MainDataGridJPBerkalaVerif")
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
                  "msg":"<b>Error:</b> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition ="";

	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 	 		= $_POST["search_by"];
	$ls_search_txt 	 			= $_POST["search_txt"];
	$ls_search_by2 	 			= $_POST["search_by2"];
	$ls_search_txt2a 			= $_POST["search_txt2a"];
	$ls_search_txt2b 			= $_POST["search_txt2b"];
	$ls_search_txt2c 			= $_POST["search_txt2c"];
	$ls_search_txt2d 			= $_POST["search_txt2d"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];
  $ls_jenis_pembayaran	= $_POST["jenis_pembayaran"];
  $ls_status_siapbayar	= $_POST["status_siapbayar"];
  $ls_blthjt 						= $_POST["blthjt"];
  $ls_page 				 			= $_POST["page"];
  $ls_page_item 	 			= $_POST["page_item"];

	$ln_page 				 			= is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 			= is_numeric($ls_page_item) ? $ls_page_item : "10";

	$ln_start 			 			= (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 			= $ln_start + $ln_length - 1;

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
  $ipDev  = $wsIp."/JSPN5043/DataGridJPBerkala";
  $url    = $ipDev;

  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
		'Accept'=> 'application/json',
  	'X-Forwarded-For'=> $ipfwd,
  );

  // set POST params -----------------------------------------------------------
	$data = array(
      'chId'				 			=> $chId,
      'reqId'				 			=> $gs_kode_user,
  		'STATUS_SIAPBAYAR'	=> (int)$ls_status_siapbayar,
			'KODE_KANTOR'	 			=> $gs_kantor_aktif,
			'BLNJT'	 						=> $ls_blthjt,
      'PAGE'				 			=> (int)$ln_page,
      'NROWS'				 			=> (int)$ln_length,
      'C_COLNAME'		 			=> $ls_colname,
      'C_COLVAL'		 			=> $ls_colval,
  		'C_COLNAME2'	 			=> $ls_colname2,
      'C_COLVAL2'		 			=> $ls_colval2,
      'O_COLNAME'		 			=> $ls_order_colname,
      'O_MODE'			 			=> $ls_order_mode
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

  if ($ls_status_siapbayar=="1")
  {
   	$ls_action_status = "Verifikasi";
  }elseif ($ls_status_siapbayar=="3")
  {
   	$ls_action_status = "Pembayaran";
  }elseif ($ls_status_siapbayar=="4")
  {
   	$ls_action_status = "Cek Status";
  }

	//var_dump($resultArray);
  for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++){
		$resultArray->DATA[$i]->ACTION = '<input type="hidden" name="KODE['.$i.']" id="KODE_'.$i.'" value="'.$resultArray->DATA[$i]->KODE_KLAIM.'"><a href="#" onClick="fl_js_jpbklverif_doGridTask(\''.$resultArray->DATA[$i]->KODE_KLAIM.'\',\''.$resultArray->DATA[$i]->NO_KONFIRMASI.'\',\''.$resultArray->DATA[$i]->NO_PROSES.'\',\''.$resultArray->DATA[$i]->KD_PRG.'\',\''.$ls_jenis_pembayaran.'\',\''.$ls_status_siapbayar.'\',\''.$ls_blthjt.'\');"><img src="../../images/user_go.png" border="0" alt="Pembayaran Klaim" align="absmiddle" /><font color="#009999">'.$ls_action_status.'</font></a>';
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

//get data jp berkala siap bayar -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatajpberkalasiapbayar")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	$ln_no_proses 		= $_POST["v_no_proses"];
	$ls_kd_prg 				= $_POST["v_kd_prg"];

	//panggil ws utk get data konfirmasi jp berkala ------------------------------
	if ($ls_kode_klaim !="" && $ln_no_konfirmasi !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5050/ViewDataKonfirmasiBerkala";
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
      'KODE_KLAIM'=>$ls_kode_klaim,
      'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi
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
    $result_DtKonf = curl_exec($ch);
    $resultArray_DtKonf = json_decode(utf8_encode($result_DtKonf));

    if ($resultArray_DtKonf->ret=='0')
    {
  		//------------------- ambil ListPeriodeBerkalaRekap ----------------------
      global $wsIp;
      $ipDev  = $wsIp."/JSPN5050/ListPeriodeBerkalaRekap";
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
        'KODE_KLAIM'=>$ls_kode_klaim,
        'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi
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
      $result_BlnBkl = curl_exec($ch);
      $resultArray_BlnBkl = json_decode(utf8_encode($result_BlnBkl));
			//------------------- end ambil ListPeriodeBerkalaRekap ------------------

			//---------------- ambil Dokumen Kelengkapan Administrasi ----------------
      global $wsIp;
      $ipDev  = $wsIp."/JSPN5050/ListDokumenBerkala";
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
        'KODE_KLAIM'=>$ls_kode_klaim,
        'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi
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
      $result_Dok = curl_exec($ch);
      $resultArray_Dok = json_decode(utf8_encode($result_Dok));
			//---------------- end ambil Dokumen Kelengkapan Administrasi ------------

			//ambil data verifikasi data siap bayar by no_proses ---------------------
			if ($ln_no_proses !="" && $ls_kd_prg != "")
			{
        global $wsIp;
        $ipDev  = $wsIp."/JSPN5043/ViewSiapByrJPBerkalaByNoProses";
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
          'KODE_KLAIM'=>$ls_kode_klaim,
          'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi,
					'NO_PROSES'=>(int)$ln_no_proses,
					'KD_PRG'=>(int)$ls_kd_prg
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
        $result_DtDtlSiapByr = curl_exec($ch);
        $resultArray_DtDtlSiapByr = json_decode(utf8_encode($result_DtDtlSiapByr));
			}
			//end ambil data verifikasi data siap bayar by no_proses -----------------

			//return data ke UI ------------------------------------------------------
      $result_data_combine = array();
      $result_data_combine["dataKonf"]   = $resultArray_DtKonf->DATA;
      $result_data_combine["dataBlnBkl"] = $resultArray_BlnBkl->DATA;
      $result_data_combine["dataDok"] 	 = $resultArray_Dok->DATA;
			$result_data_combine["dataDtlSiapByr"] = $resultArray_DtDtlSiapByr->DATA;

      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtKonf->ret;
      $result_data_final["msg"]  = $resultArray_DtKonf->msg;
      $result_data_final["data"] = $result_data_combine;

      echo json_encode($result_data_final);

    }else
		{
  	 	$ls_mess = $resultArray_DtKonf->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';
		}
	}else
	{
	 	$ls_mess = "Data Konfirmasi JP Berkala kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data jp berkala siap bayar -------------------------------------------

//do update penerima manfaat jp berkala ----------------------------------------
else if($ls_type == "fjq_ajax_val_byr_penerima_save_update_jpberkala")
{
  $ls_kode_klaim 		 	 							= $_POST["v_kode_klaim"];
	$ls_kode_penerima_berkala 		 	 	= $_POST["v_kode_penerima_berkala"];
	$ln_no_urut_keluarga 		 					= $_POST["v_no_urut_keluarga"];
	$ls_keterangan 		 	 							= str_replace("'","''",$_POST["v_keterangan"]);
	$ls_npwp 		 	 										= str_replace("'","''",$_POST["v_npwp"]);
	$ls_email 		 	 									= str_replace("'","''",$_POST["v_email"]);
	$ls_handphone 		 	 							= str_replace("'","''",$_POST["v_handphone"]);
  $ls_nama_bank_penerima    				= $_POST["v_nama_bank_penerima"];
  $ls_kode_bank_penerima    				= $_POST["v_kode_bank_penerima"];
  $ls_id_bank_penerima      				= $_POST["v_id_bank_penerima"];
  $ls_no_rekening_penerima  				= $_POST["v_no_rekening_penerima"];
  $ls_nama_rekening_penerima    		= str_replace("'","''",$_POST["v_nama_rekening_penerima"]);
  $ls_st_valid_rekening_penerima    = $_POST["v_status_valid_rekening_penerima"];
  $ls_kode_bank_pembayar    				= $_POST["v_kode_bank_pembayar"];
  $ls_status_rekening_sentral   		= $_POST["v_status_rekening_sentral"];
  $ls_kantor_rekening_sentral   		= $_POST["v_kantor_rekening_sentral"];
  $ls_metode_transfer           		= $_POST["v_metode_transfer"];

	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/PenerimaManfaatBerkalaUpdate';

	$dataKlaim = array(
        array (
          "KODE_KLAIM"		 		 							=> $ls_kode_klaim,
          "NO_URUT_KELUARGA"		 		 				=> (int)$ln_no_urut_keluarga,
          "KODE_HUBUNGAN"		 		 						=> "",
          "NAMA_LENGKAP"		 		 						=> "",
          "TGL_LAHIR"		 		 								=> "",
          "JENIS_KELAMIN"		 		 						=> "",
          "NO_KARTU_KELUARGA"		 		 				=> "",
          "NOMOR_IDENTITAS"		 		 					=> "",
          "TEMPAT_LAHIR"		 		 						=> "",
          "GOLONGAN_DARAH"		 		 					=> "",
          "STATUS_KAWIN"		 		 						=> "",
          "ALAMAT"		 		 									=> "",
          "RT"		 		 											=> "",
          "RW"		 		 											=> "",
          "KODE_KELURAHAN"		 		 					=> 0,
          "KODE_KECAMATAN"		 		 					=> 0,
          "KODE_KABUPATEN"		 		 					=> 0,
          "KODE_POS"		 		 								=> "",
          "TELEPON_AREA"		 		 						=> "",
          "TELEPON"		 		 									=> "",
          "TELEPON_EXT"		 		 							=> "",
          "FAX_AREA"		 		 								=> "",
          "FAX"		 		 											=> "",
          "HANDPHONE"		 		 								=> $ls_handphone,
          "EMAIL"		 		 										=> $ls_email,
          "NPWP"		 		 										=> $ls_npwp,
          "NAMA_PENERIMA"		 		 						=> "",
          "BANK_PENERIMA"		 		 						=> $ls_nama_bank_penerima,
          "NO_REKENING_PENERIMA"		 		 		=> $ls_no_rekening_penerima,
          "NAMA_REKENING_PENERIMA"		 		 	=> $ls_nama_rekening_penerima,
          "KODE_BANK_PEMBAYAR"		 		 			=> $ls_kode_bank_pembayar,
          "KPJ_TERTANGGUNG"		 		 					=> "",
          "PEKERJAAN"		 		 								=> "",
          "KODE_KONDISI_TERAKHIR"		 		 		=> "",
          "TGL_KONDISI_TERAKHIR"		 		 		=> "",
          "STATUS_LAYAK"		 		 						=> "",
          "KODE_PENERIMA_BERKALA"		 		 		=> $ls_kode_penerima_berkala,
          "KETERANGAN"		 		 							=> $ls_keterangan,
          "JENIS_IDENTITAS"		 		 					=> "",
          "STATUS_VALID_IDENTITAS"		 		 	=> "",
          "KODE_BANK_PENERIMA"		 		 			=> $ls_kode_bank_penerima,
          "ID_BANK_PENERIMA"		 		 				=> $ls_id_bank_penerima,
          "STATUS_VALID_REKENING_PENERIMA"	=> $ls_st_valid_rekening_penerima,
          "STATUS_REKENING_SENTRAL"		 		 	=> $ls_status_rekening_sentral,
          "KANTOR_REKENING_SENTRAL"		 		 	=> $ls_kantor_rekening_sentral,
          "METODE_TRANSFER"		 		 					=> $ls_metode_transfer,
          "KODE_NEGARA"		 		 							=> "",
          "IS_VERIFIED_HP"		 		 					=> "",
          "TGL_VERIFIED_HP"		 		 					=> "",
          "IS_VERIFIED_EMAIL"		 		 				=> "",
          "TGL_VERIFIED_EMAIL"		 		 			=> "",
          "PETUGAS_VERIFIED_HP"		 		 			=> "",
          "STATUS_REG_NOTIFIKASI"		 		 		=> "",
          "TGL_REG_NOTIFIKASI"		 		 			=> "",
          "PETUGAS_REG_NOTIFIKASI"		 		 	=> "",
          "PATH_FILE_DOK_NOTIF"		 		 			=> "",
          "KODE_OTP_SMS"		 		 						=> "",
					"KODE_OTP_SMS_VERIFIED"		 		 		=> "",
          "STATUS_CEK_LAYANAN"		 		 			=> "",
					"KODE_OTP_EMAIL"		 		 					=> "",
          "KODE_OTP_EMAIL_VERIFIED"		 		 	=> "",
          "PETUGAS_VERIFIED_EMAIL"		 		 	=> ""
        )
	);

  $data = array(
    "chId"  => $chId,
    "reqId" => $gs_kode_user,
    "dataKlaim" => $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
    $ls_mess = "SIMPAN DATA PENERIMA MANFAAT BERHASIL, SESSION DILANJUTKAN";
		echo  '{
      "ret":0,
      "success":true,
      "msg":"'.$ls_mess.'"
    }';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		$ls_mess = $ls_mess." </br> ".$resultArray->P_MESS;
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
  }
}
//end do update penerima manfaat jp berkala ------------------------------------

//do submit data siap transfer jp berkala --------------------------------------
else if($ls_type == "fjq_ajax_val_byr_doSiapTransferJPBerkala")
{
	$ls_kode_klaim          = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi    		= $_POST["v_no_konfirmasi"];
	//$ln_no_proses    				= $_POST["v_no_proses"];
  $ls_kd_prg             	= $_POST["v_kd_prg"];
	$ls_kantor_siapbayar    = $_POST["v_kantor_siapbayar"];
	if ($ls_kantor_siapbayar=="")
	{
	 	$ls_kantor_siapbayar = $gs_kantor_aktif;
	}


  //get no_proses list
  $sql = "SELECT NO_PROSES FROM PN.PN_KLAIM_BERKALA_REKAP WHERE KODE_KLAIM=:p_kode_klaim AND NO_KONFIRMASI=:p_no_konfirmasi ";
  $qb = $DB->parse($sql);
  oci_bind_by_name($qb, ":p_kode_klaim", $ls_kode_klaim, 100);
  oci_bind_by_name($qb, ":p_no_konfirmasi", $ln_no_konfirmasi, 100);
  $DB->execute();
  $arr_no_proses = array();
  while($row = $DB->nextrow()) {
    array_push($arr_no_proses, $row['NO_PROSES']);
  }
  if ( empty($arr_no_proses) ) {
    echo '{
      "ret":-1,
      "success":false,
      "msg":"No Proses tidak ditemukan"
    }';
  }

	//submit data siap bayar -----------------------------------------------------
  global $wsIp;
  $USER = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/SubmitSiapBayarJPBerkala';

  // loop no_proses
  foreach ($arr_no_proses as $value) {    
    $data = array(
      "chId"  => $chId,
      "reqId" => $USER,
      "KODE_KLAIM" => $ls_kode_klaim,
      "NO_KONFIRMASI" => (int)$ln_no_konfirmasi,
      "NO_PROSES" => (int)$value,
      "KD_PRG" => (int)$ls_kd_prg,
      "KANTOR_SIAPBAYAR" => $ls_kantor_siapbayar
    );

    // call ws
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $result = utf8_encode(curl_exec($ch));
    $resultArray = json_decode($result);

    if ($resultArray->ret!='0')
    {
      $ls_mess = $resultArray->msg;
      $ls_mess = $ls_mess." </br> ".$resultArray->P_MESS;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';
    }
  }
  echo  '{
    "ret":0,
    "success":true,
    "msg": "SUBMIT DATA SIAP BAYAR BERHASIL, SESSION DILANJUTKAN"
  }';

}
//end do submit data siap transfer jp berkala ----------------------------------

//do kembalikan
else if($ls_type == "fjq_ajax_val_doKembalikan")
{
  $ls_kode_klaim          = $_POST["v_kode_klaim"];
  $ln_no_konfirmasi    		= $_POST["v_no_konfirmasi"];
  $ln_no_proses    				= $_POST["v_no_proses"];
  $qry = "
  UPDATE PN_KLAIM_BERKALA_REKAP
  SET STATUS_TRANSFER='K'
  WHERE KODE_KLAIM=:p_kode_klaim
    AND NO_KONFIRMASI=:p_no_konfirmasi
    AND NO_PROSES=:p_no_proses";    
  $qb = $DB->parse($qry);
  oci_bind_by_name($qb, ":p_kode_klaim", $ls_kode_klaim, 100);
  oci_bind_by_name($qb, ":p_no_konfirmasi", $ln_no_konfirmasi, 100);
  oci_bind_by_name($qb, ":p_no_proses", $ln_no_proses, 100);
  if($DB->execute()){
    $jsondata["ret"] = "0";
    $jsondata["success"] = false;
    $jsondata["msg"] = "Data berhasil dikembalikan";
    echo json_encode($jsondata);
  }else{
    $jsondata["ret"] = "-1";
    $jsondata["success"] = false;
    $jsondata["msg"] = "Data gagal dikembalikan";
    echo json_encode($jsondata);
  }
}
//end do kembalikan
// --------------------------- END JP BERKALA ----------------------------------

// ------------------------ PEMBAYARAN (SIAP TRANSFER) -------------------------
//get data grid ----------------------------------------------------------------
else if($ls_type == "MainDataGridLumpsumSiapTransfer")
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
                  "msg":"<b>Error:</b> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition ="";

	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 	 		= $_POST["search_by"];
	$ls_search_txt 	 			= $_POST["search_txt"];
	$ls_search_by2 	 			= $_POST["search_by2"];
	$ls_search_txt2a 			= $_POST["search_txt2a"];
	$ls_search_txt2b 			= $_POST["search_txt2b"];
	$ls_search_txt2c 			= $_POST["search_txt2c"];
	$ls_search_txt2d 			= $_POST["search_txt2d"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];

  $ls_page 				 			= $_POST["page"];
  $ls_page_item 	 			= $_POST["page_item"];

	$ln_page 				 			= is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 			= is_numeric($ls_page_item) ? $ls_page_item : "10";

	$ln_start 			 			= (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 			= $ln_start + $ln_length - 1;

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

	//cek apakah kantor sudah sentralisasi rekening ------------------------------
	global $wsIp;
  $ipDev  	= $wsIp."/MS1001/LovKantorCabang";
  $url    	= $ipDev;
  $username = $gs_kode_user;

  // set HTTP header -----------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

  // set POST params -----------------------------------------------------
  $data = array(
    'chId'				=>$chId,
    'reqId'				=>$username,
    'KODE_KANTOR'	=>$gs_kantor_aktif,
    'PAGE'				=>1,
    'NROWS'				=>10000,
    'C_COLNAME'		=>"STATUS_REKENING_SENTRAL",
    'C_COLVAL'		=>"Y",
    'C_COLNAME2'	=>"KODE_KANTOR",
    'C_COLVAL2'		=>$gs_kantor_aktif,
    'O_COLNAME'		=>"",
    'O_MODE'			=>"ASC"
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
  $result = curl_exec($ch);
  $resultArray_LovKtr = json_decode(utf8_encode($result));

  if ($resultArray_LovKtr->ret=='0')
  {
    for($i=0;$i<ExtendedFunction::count($resultArray_LovKtr->DATA);$i++)
    {
      if ($resultArray_LovKtr->DATA[$i]->STATUS_REKENING_SENTRAL=="Y")
      {
       	$ls_status_rekening_sentral="Y";
      }else
      {
       	$ls_status_rekening_sentral="T";
      }
    }
  }else
  {
   	$ls_status_rekening_sentral="T";
  }
	//end cek apakah kantor sudah sentralisasi rekening --------------------------

	//tampilkan data jika kantor sudah sentralisasi rekening ---------------------
	if ($ls_status_rekening_sentral=="Y")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/DataGridSiapTransfer";
    $url    = $ipDev;

    // set HTTP header ---------------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
  		'Accept'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );

    // set POST params ---------------------------------------------------------
    $data = array(
      'chId'					 	=>$chId,
      'reqId'						=>$gs_kode_user,
    	'STATUS_SIAPBAYAR'=>"3",
    	'KODE_KANTOR'			=>$gs_kantor_aktif,
      'PAGE'						=>(int)$ln_page,
      'NROWS'						=>(int)$ln_length,
      'C_COLNAME'				=>$ls_colname,
      'C_COLVAL'				=>$ls_colval,
      'C_COLNAME2'			=>$ls_colname2,
      'C_COLVAL2'				=>$ls_colval2,
      'O_COLNAME'				=>$ls_order_colname,
      'O_MODE'					=>$ls_order_mode
    );

    // Open connection ---------------------------------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data -----------------------------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post ------------------------------------------------------------
    $result = curl_exec($ch);
  	$resultArray = json_decode(utf8_encode($result));

  	//var_dump($resultArray);
    for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++){
  		$resultArray->DATA[$i]->ACTION = '';
  	}
	}else
	{
	 	$resultArray->TOTAL_REC = 0;
		$jsondata = "";
	}
	//end tampilkan data jika kantor sudah sentralisasi rekening -----------------

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

//do Klik Data Siap Transfer ---------------------------------------------------
else if($ls_type =="DoKlikSiapTransferLumpsum")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoSiapTransfer';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_lumpsiaptransfer'];
	$ls_mess1 = $ln_panjang;
  for($i=0;$i<=$ln_panjang-1;$i++)
  {
		//reset value --------------------------------------------------------------
		$ls_d_kode_klaim	= "";
		$ls_d_kode_tipe_penerima	= "";
		$ls_d_kd_prg	= "";
		$ls_d_ktr_byr	= "";

		//get status tickmark ------------------------------------------------------
		$ls_d_isTickmark	= $_POST['lumpsiaptransfer_cboxRecord'.$i];
    if ($ls_d_isTickmark=="on" || $ls_d_isTickmark=="ON" || $ls_d_isTickmark=="Y")
    {
     	$ls_d_isTickmark = "Y";
    }else
    {
     	$ls_d_isTickmark = "T";
    }

		//proses hanya yg ditickmark -----------------------------------------------
		if ($ls_d_isTickmark=="Y")
		{
		 	$ls_d_kode_klaim	= $_POST['lumpsiaptransfer_kode_klaim'.$i];
			$ls_d_kode_tipe_penerima	= $_POST['lumpsiaptransfer_kode_tipe_penerima'.$i];
			$ls_d_kd_prg	= $_POST['lumpsiaptransfer_kd_prg'.$i];
			$ls_d_ktr_byr	= $gs_kantor_aktif;

      if ($ls_d_kode_klaim!="" && $ls_d_kode_tipe_penerima!="" && $ls_d_kd_prg!="" && $ls_d_ktr_byr!="")
      {
        $dataKlaim_Dtl = array(
          "KODE_KLAIM"		 		 	=> $ls_d_kode_klaim,
          "KODE_TIPE_PENERIMA"	=> $ls_d_kode_tipe_penerima,
          "KD_PRG"		 		 			=> (int)$ls_d_kd_prg,
          "KANTOR_APVBAYAR"		 	=> $ls_d_ktr_byr
        );
        array_push($dataKlaim, $dataKlaim_Dtl);
      }

      // -------------------------------------------------start JKP 26/11/2021------------------------

      $sql_jkp = "SELECT A.ID_POINTER_ASAL, A.KODE_TIPE_KLAIM FROM PN.PN_KLAIM A where A.kode_klaim =  :p_kode_klaim ";
      $proc = $DB->parse($sql_jkp);
      oci_bind_by_name($proc, ':p_kode_klaim', $ls_d_kode_klaim, 100);
      // 20-03-2024 penyesuaian bind variable
      $DB->execute();
      $row = $DB->nextrow();
      $ls_id_pointer_asal = $row['ID_POINTER_ASAL'];
      $ls_kode_tipe_klaim = $row['KODE_TIPE_KLAIM'];

      $sql_ec_jkp = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = :p_kode_klaim ";
      $proc = $ECDB->parse($sql_ec_jkp);
      oci_bind_by_name($proc, ':p_kode_klaim', $ls_d_kode_klaim, 100);
      // 20-03-2024 penyesuaian bind variable
			$ECDB->execute();
			$row = $ECDB->nextrow();
			$ls_flag_cek_menunggak_iuran = $row['FLAG_CEK_MENUNGGAK_IURAN'];


			if($ls_kode_tipe_klaim == 'JKP01' && $ls_flag_cek_menunggak_iuran != 'Y'){

        $headers = array(
          'Content-Type: application/json',
          'X-Forwarded-For: ' . $ipfwd
        );

        $data_send = array(
          'chId'              => 'SMILE',
          'reqId'             => $gs_kode_user,
          "kodePengajuan"		  => $ls_id_pointer_asal,
          "statusPengajuan"	  => "KLJKP004",
          "keterangan"		    => "",
          "petugasRekam"		  => $gs_kode_user
        );

        $url_jkp = $wsIp.'/JSKlaimJKP/UpdateClaimStatus';
        $ch_jkp = curl_init();


        curl_setopt($ch_jkp, CURLOPT_URL, $url_jkp);
        curl_setopt($ch_jkp, CURLOPT_POST, true);
        curl_setopt($ch_jkp, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_jkp, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch_jkp, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_jkp, CURLOPT_POSTFIELDS, json_encode($data_send));

        $result_jkp       = utf8_encode(curl_exec($ch_jkp));
        $resultArray_jkp  = json_decode($result_jkp);

      }

      // -------------------------------------------------end JKP 26/11/2021------------------------

      // -------------------------------------------------start ESURVEY 07/03/2021------------------------
      $query_email  = "
					select kode_tipe_penerima
					from PN.PN_KLAIM_PENERIMA_MANFAAT a
					where exists
					(
						select null from PN.PN_KLAIM_MANFAAT_DETIL b
						where b.KODE_KLAIM = a.KODE_KLAIM
						and b.KODE_TIPE_PENERIMA = a.KODE_TIPE_PENERIMA
					)
					and exists
					(
						select null from PN.PN_KLAIM c
						where c.KODE_KLAIM = a.KODE_KLAIM
						and nvl(c.STATUS_BATAL,'X') = 'T'
						and c.KODE_KLAIM_INDUK is null
						and c.KANAL_PELAYANAN not in ('25')
						and nvl(c.KODE_POINTER_ASAL,'X') not in ('SPO')
					)
					and a.KODE_KLAIM = :p_kode_klaim
				";
				
				$ls_kode_tipe_penerima = "";
        $proc = $DB->parse($query_email);
        oci_bind_by_name($proc, ':p_kode_klaim', $ls_d_kode_klaim, 100);
        // 20-03-2024 penyesuaian bind variable
				if($DB->execute()){
					if($row=$DB->nextrow()){
					$ls_kode_tipe_penerima=$row['KODE_TIPE_PENERIMA']; 
					}
				}
				
				// for($i=0;$i<$ls_total;$i++)
				// 	{
						$data_merged = array(
							"chId" => "E-SURVEY", 
							"reqId" => $gs_kode_user, 
							"data" => array(
								"kodeKlaim" => $ls_d_kode_klaim,
								"user" => $gs_kode_user,
								"kodeTipePenerima" => $ls_kode_tipe_penerima
							)
						);
						// var_dump($data_merged);die();

						$headers_merged = array(
							'Content-Type: application/json',
							'X-Forwarded-For: ' . $ipfwd
						);
													
						$result_merged = api_json_call1($wsIp . "/JSSurvey/GenerateRespondenKlaim", $headers_merged, $data_merged); 
      // -------------------------------------------------end ESURVEY 26/11/2021------------------------


		}
	}

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data Klaim berhasil dibayarkan, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end do Klik Data Siap Transfer -----------------------------------------------

//do Tolak Data Siap Transfer --------------------------------------------------
else if($ls_type =="DoTolakSiapTransferLumpsum")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoTolakSiapTransfer';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_lumpsiaptransfer'];
	$ls_mess1 = $ln_panjang;
  for($i=0;$i<=$ln_panjang-1;$i++)
  {
		//reset value --------------------------------------------------------------
		$ls_d_kode_klaim	= "";
		$ls_d_kode_tipe_penerima	= "";
		$ls_d_kd_prg	= "";
		$ls_d_ktr_byr	= "";

		//get status tickmark ------------------------------------------------------
		$ls_d_isTickmark	= $_POST['lumpsiaptransfer_cboxRecord'.$i];
    if ($ls_d_isTickmark=="on" || $ls_d_isTickmark=="ON" || $ls_d_isTickmark=="Y")
    {
     	$ls_d_isTickmark = "Y";
    }else
    {
     	$ls_d_isTickmark = "T";
    }

		//proses hanya yg ditickmark -----------------------------------------------
		if ($ls_d_isTickmark=="Y")
		{
		 	$ls_d_kode_klaim	= $_POST['lumpsiaptransfer_kode_klaim'.$i];
			$ls_d_kode_tipe_penerima	= $_POST['lumpsiaptransfer_kode_tipe_penerima'.$i];
			$ls_d_kd_prg	= $_POST['lumpsiaptransfer_kd_prg'.$i];
			$ls_d_ktr_byr	= $gs_kantor_aktif;

      if ($ls_d_kode_klaim!="" && $ls_d_kode_tipe_penerima!="" && $ls_d_kd_prg!="" && $ls_d_ktr_byr!="")
      {
        $dataKlaim_Dtl = array(
          "KODE_KLAIM"		 		 	=> $ls_d_kode_klaim,
          "KODE_TIPE_PENERIMA"	=> $ls_d_kode_tipe_penerima,
          "KD_PRG"		 		 			=> (int)$ls_d_kd_prg,
          "KANTOR_APVBAYAR"		 	=> $ls_d_ktr_byr
        );
        array_push($dataKlaim, $dataKlaim_Dtl);
      }

      // -------------------------------------------------JKP 26/11/2021------------------------

      $sql_jkp = "SELECT A.ID_POINTER_ASAL, A.KODE_TIPE_KLAIM FROM PN.PN_KLAIM A where A.kode_klaim =  :p_kode_klaim ";
      $proc = $DB->parse($sql_jkp);
      oci_bind_by_name($proc, ':p_kode_klaim', $ls_d_kode_klaim, 100);
      // 20-03-2024 penyesuaian bind variable
      $DB->execute();
      $row = $DB->nextrow();
      $ls_id_pointer_asal = $row['ID_POINTER_ASAL'];
      $ls_kode_tipe_klaim = $row['KODE_TIPE_KLAIM'];

      $sql_ec_jkp = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = :p_kode_klaim ";
      $proc = $ECDB->parse($sql_ec_jkp);
      oci_bind_by_name($proc, ':p_kode_klaim', $ls_d_kode_klaim, 100);
      // 20-03-2024 penyesuaian bind variable
			$ECDB->execute();
			$row = $ECDB->nextrow();
			$ls_flag_cek_menunggak_iuran = $row['FLAG_CEK_MENUNGGAK_IURAN'];


			if($ls_kode_tipe_klaim == 'JKP01' && $ls_flag_cek_menunggak_iuran != 'Y'){
        $headers = array(
          'Content-Type: application/json',
          'X-Forwarded-For: ' . $ipfwd
        );

        $data_send = array(
          'chId'              => 'SMILE',
          'reqId'             => $gs_kode_user,
          "kodePengajuan"		  => $ls_id_pointer_asal,
          "statusPengajuan"	  => "KLJKP005",
          "keterangan"		    => "",
          "petugasRekam"		  => $gs_kode_user
        );

        $url_jkp = $wsIp.'/JSKlaimJKP/UpdateClaimStatus';
        $ch_jkp = curl_init();


        curl_setopt($ch_jkp, CURLOPT_URL, $url_jkp);
        curl_setopt($ch_jkp, CURLOPT_POST, true);
        curl_setopt($ch_jkp, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch_jkp, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch_jkp, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch_jkp, CURLOPT_POSTFIELDS, json_encode($data_send));

        $result_jkp       = utf8_encode(curl_exec($ch_jkp));
        $resultArray_jkp  = json_decode($result_jkp);

      }

      // -------------------------------------------------JKP 26/11/2021------------------------

		}
	}

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data Klaim Siap Transfer berhasil dikembalikan ke tahap sebelumnya untuk dilakukan verifikasi ulang, session dilanjutkan.."}';

    $ls_kode_klaim            				= $_POST["v_kode_klaim"];

    $sql = "SELECT A.ID_POINTER_ASAL, A.KODE_TIPE_KLAIM FROM PN.PN_KLAIM A where A.kode_klaim =  :p_kode_klaim ";
    $proc = $DB->parse($sql_ec_jkp);
    oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim, 100);
    // 20-03-2024 penyesuaian bind variable
    $DB->execute();
    $row = $DB->nextrow();
    $ls_id_pointer_asal = $row['ID_POINTER_ASAL'];
    $ls_kode_tipe_klaim = $row['KODE_TIPE_KLAIM'];

    $sql_ec_jkp = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = :p_kode_klaim ";
    $proc = $ECDB->parse($sql_ec_jkp);
    oci_bind_by_name($proc, ':p_kode_klaim', $ls_d_kode_klaim, 100);
    // 20-03-2024 penyesuaian bind variable
		$ECDB->execute();
		$row = $ECDB->nextrow();
		$ls_flag_cek_menunggak_iuran = $row['FLAG_CEK_MENUNGGAK_IURAN'];


		if($ls_kode_tipe_klaim == 'JKP01' && $ls_flag_cek_menunggak_iuran != 'Y'){
      $headers = array(
        'Content-Type: application/json',
        'X-Forwarded-For: ' . $ipfwd
      );

      $data_send = array(
        'chId'              => 'SMILE',
        'reqId'             => $gs_kode_user,
        "kodePengajuan"		=> $ls_id_pointer_asal,
        "statusPengajuan"	=> "KLJKP005",
        "keterangan"		=> "",
        "petugasRekam"		=> $gs_kode_user
      );

	    $url_jkp = $wsIp.'/JSKlaimJKP/UpdateClaimStatus';
      $ch_jkp = curl_init();


      curl_setopt($ch_jkp, CURLOPT_URL, $url_jkp);
      curl_setopt($ch_jkp, CURLOPT_POST, true);
      curl_setopt($ch_jkp, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch_jkp, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch_jkp, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch_jkp, CURLOPT_POSTFIELDS, json_encode($data_send));

      $result_jkp       = utf8_encode(curl_exec($ch_jkp));
      $resultArray_jkp  = json_decode($result_jkp);


    }else
    {
      $ls_mess = $resultArray->msg;
      echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
    }
  }
}
//end Tolak Data Siap Transfer -------------------------------------------------

//get data grid JP Bekala Siap Transfer ----------------------------------------
else if($ls_type == "MainDataGridJPBerkalaSiapTransfer")
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
                  "msg":"<b>Error:</b> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition ="";

	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 	 		= $_POST["search_by"];
	$ls_search_txt 	 			= $_POST["search_txt"];
	$ls_search_by2 	 			= $_POST["search_by2"];
	$ls_search_txt2a 			= $_POST["search_txt2a"];
	$ls_search_txt2b 			= $_POST["search_txt2b"];
	$ls_search_txt2c 			= $_POST["search_txt2c"];
	$ls_search_txt2d 			= $_POST["search_txt2d"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];

  $ls_page 				 			= $_POST["page"];
  $ls_page_item 	 			= $_POST["page_item"];

	$ln_page 				 			= is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 			= is_numeric($ls_page_item) ? $ls_page_item : "10";

	$ln_start 			 			= (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 			= $ln_start + $ln_length - 1;

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

	//cek apakah kantor sudah sentralisasi rekening ------------------------------
	global $wsIp;
  $ipDev  	= $wsIp."/MS1001/LovKantorCabang";
  $url    	= $ipDev;
  $username = $gs_kode_user;

  // set HTTP header -----------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

  // set POST params -----------------------------------------------------
  $data = array(
    'chId'				=>$chId,
    'reqId'				=>$username,
    'KODE_KANTOR'	=>$gs_kantor_aktif,
    'PAGE'				=>1,
    'NROWS'				=>10000,
    'C_COLNAME'		=>"STATUS_REKENING_SENTRAL",
    'C_COLVAL'		=>"Y",
    'C_COLNAME2'	=>"KODE_KANTOR",
    'C_COLVAL2'		=>$gs_kantor_aktif,
    'O_COLNAME'		=>"",
    'O_MODE'			=>"ASC"
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
  $result = curl_exec($ch);
  $resultArray_LovKtr = json_decode(utf8_encode($result));

  if ($resultArray_LovKtr->ret=='0')
  {
    for($i=0;$i<ExtendedFunction::count($resultArray_LovKtr->DATA);$i++)
    {
      if ($resultArray_LovKtr->DATA[$i]->STATUS_REKENING_SENTRAL=="Y")
      {
       	$ls_status_rekening_sentral="Y";
      }else
      {
       	$ls_status_rekening_sentral="T";
      }
    }
  }else
  {
   	$ls_status_rekening_sentral="T";
  }
	//end cek apakah kantor sudah sentralisasi rekening --------------------------

	//tampilkan data jika kantor sudah sentralisasi rekening ---------------------
	if ($ls_status_rekening_sentral=="Y")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/DataGridSiapTransferJPBerkala";
    $url    = $ipDev;

    // set HTTP header -----------------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
  		'Accept'=> 'application/json',
    	'X-Forwarded-For'=> $ipfwd,
    );

    // set POST params -----------------------------------------------------------
    $data = array(
      'chId'					 	=>$chId,
      'reqId'						=>$gs_kode_user,
    	'STATUS_SIAPBAYAR'=>"3",
    	'KODE_KANTOR'			=>$gs_kantor_aktif,
      'PAGE'						=>(int)$ln_page,
      'NROWS'						=>(int)$ln_length,
      'C_COLNAME'				=>$ls_colname,
      'C_COLVAL'				=>$ls_colval,
      'C_COLNAME2'			=>$ls_colname2,
      'C_COLVAL2'				=>$ls_colval2,
      'O_COLNAME'				=>$ls_order_colname,
      'O_MODE'					=>$ls_order_mode
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

  	//var_dump($resultArray);
    for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++){
  		$resultArray->DATA[$i]->ACTION = '';
  	}
	}else
	{
	 	$resultArray->TOTAL_REC = 0;
		$jsondata = "";
	}
	//end tampilkan data jika kantor sudah sentralisasi rekening -----------------

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
//end get data grid JP Bekala --------------------------------------------------

//do Klik Data Siap Transfer JP Bekala ----------------------------------------
else if($ls_type =="DoKlikSiapTransferJPBerkala")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoSiapTransferJPBerkala';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_jpbklsiaptransfer'];
	$ls_mess1 = $ln_panjang;
  for($i=0;$i<=$ln_panjang-1;$i++)
  {
		//reset value --------------------------------------------------------------
		$ls_d_kode_klaim	= "";
		$ls_d_no_konfirmasi	= "";
		$ls_d_no_proses	= "";
		$ls_d_kd_prg	= "";
		$ls_d_ktr_byr	= "";

		//get status tickmark ------------------------------------------------------
		$ls_d_isTickmark	= $_POST['jpbklsiaptransfer_cboxRecord'.$i];
    if ($ls_d_isTickmark=="on" || $ls_d_isTickmark=="ON" || $ls_d_isTickmark=="Y")
    {
     	$ls_d_isTickmark = "Y";
    }else
    {
     	$ls_d_isTickmark = "T";
    }

		//proses hanya yg ditickmark -----------------------------------------------
		if ($ls_d_isTickmark=="Y")
		{
		 	$ls_d_kode_klaim	= $_POST['jpbklsiaptransfer_kode_klaim'.$i];
			$ls_d_no_konfirmasi	= $_POST['jpbklsiaptransfer_no_konfirmasi'.$i];
			//$ls_d_no_proses	= $_POST['jpbklsiaptransfer_no_proses'.$i];
			$ls_d_kd_prg	= $_POST['jpbklsiaptransfer_kd_prg'.$i];
			$ls_d_ktr_byr	= $gs_kantor_aktif;

      if ($ls_d_kode_klaim!="" && $ls_d_no_konfirmasi!="" && $ls_d_kd_prg!="" && $ls_d_ktr_byr!="")
      {
        //get no_proses list
        $sql = "SELECT NO_PROSES FROM PN.PN_KLAIM_BERKALA_REKAP WHERE KODE_KLAIM=:p_kode_klaim AND NO_KONFIRMASI=:p_no_konfirmasi ";
        $qb = $DB->parse($sql);
        oci_bind_by_name($qb, ":p_kode_klaim", $ls_d_kode_klaim, 100);
        oci_bind_by_name($qb, ":p_no_konfirmasi", $ls_d_no_konfirmasi, 100);
        $DB->execute();
        $arr_no_proses = array();
        while($row = $DB->nextrow()) {
          array_push($arr_no_proses, $row['NO_PROSES']);
        }
        if ( empty($arr_no_proses) ) {
          echo '{
            "ret":-1,
            "success":false,
            "msg":"No Proses tidak ditemukan"
          }';
        }
        
        // loop no_proses
        foreach ($arr_no_proses as $value) {    
          $dataKlaim_Dtl = array(
            "KODE_KLAIM" 		 	=> $ls_d_kode_klaim,
            "NO_KONFIRMASI"		=> (int)$ls_d_no_konfirmasi,
            "NO_PROSES"				=> (int)$value,
            "KD_PRG"		 		 	=> (int)$ls_d_kd_prg,
            "KANTOR_APVBAYAR"	=> $ls_d_ktr_byr
          );
          array_push($dataKlaim, $dataKlaim_Dtl);
        }
      }
		}
	}

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data JP Berkala berhasil dibayarkan, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end do Approval Data Siap Bayar JP Bekala ------------------------------------

//do Tolak Data Siap Transfer JP Bekala -------------------------------------------
else if($ls_type =="DoTolakSiapTransferJPBerkala")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoTolakSiapTransferJPBerkala';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_jpbklsiaptransfer'];
	$ls_mess1 = $ln_panjang;
  for($i=0;$i<=$ln_panjang-1;$i++)
  {
		//reset value --------------------------------------------------------------
		$ls_d_kode_klaim	= "";
		$ls_d_no_konfirmasi	= "";
		$ls_d_no_proses	= "";
		$ls_d_kd_prg	= "";
		$ls_d_ktr_byr	= "";

		//get status tickmark ------------------------------------------------------
		$ls_d_isTickmark	= $_POST['jpbklsiaptransfer_cboxRecord'.$i];
    if ($ls_d_isTickmark=="on" || $ls_d_isTickmark=="ON" || $ls_d_isTickmark=="Y")
    {
     	$ls_d_isTickmark = "Y";
    }else
    {
     	$ls_d_isTickmark = "T";
    }

		//proses hanya yg ditickmark -----------------------------------------------
		if ($ls_d_isTickmark=="Y")
		{
		 	$ls_d_kode_klaim	= $_POST['jpbklsiaptransfer_kode_klaim'.$i];
			$ls_d_no_konfirmasi	= $_POST['jpbklsiaptransfer_no_konfirmasi'.$i];
			$ls_d_no_proses	= $_POST['jpbklsiaptransfer_no_proses'.$i];
			$ls_d_kd_prg	= $_POST['jpbklsiaptransfer_kd_prg'.$i];
			$ls_d_ktr_byr	= $gs_kantor_aktif;

      if ($ls_d_kode_klaim!="" && $ls_d_no_konfirmasi!="" && $ls_d_no_proses!="" && $ls_d_kd_prg!="" && $ls_d_ktr_byr!="")
      {
        $dataKlaim_Dtl = array(
          "KODE_KLAIM" 		 	=> $ls_d_kode_klaim,
          "NO_KONFIRMASI"		=> (int)$ls_d_no_konfirmasi,
					"NO_PROSES"				=> (int)$ls_d_no_proses,
          "KD_PRG"		 		 	=> (int)$ls_d_kd_prg,
          "KANTOR_APVBAYAR"	=> $ls_d_ktr_byr
        );
        array_push($dataKlaim, $dataKlaim_Dtl);
      }
		}
	}

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data JP Berkala Siap Bayar berhasil dikembalikan ke tahap sebelumnya untuk dilakukan verifikasi ulang, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end Tolak Data Siap Bayar JP Bekala ------------------------------------------
// ---------------------- END PEMBAYARAN (SIAP TRANSFER) -----------------------

// -------------------------- MONITORING PEMBAYARAN ----------------------------
//get data grid montoring lumpsum ----------------------------------------------
if($ls_type == "MainDataGridLumpsumMonitoring")
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
                  "msg":"<b>Error:</b> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition ="";

	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 	 		= $_POST["search_by"];
	$ls_search_txt 	 			= $_POST["search_txt"];
	$ls_search_by2 	 			= $_POST["search_by2"];
	$ls_search_txt2a 			= $_POST["search_txt2a"];
	$ls_search_txt2b 			= $_POST["search_txt2b"];
	$ls_search_txt2c 			= $_POST["search_txt2c"];
	$ls_search_txt2d 			= $_POST["search_txt2d"];
  $ls_search_txt2f 			= $_POST["search_txt2f"];
  $ls_search_txt2g 			= $_POST["search_txt2g"];
  $ls_search_txt2h 			= $_POST["search_txt2h"];
  $ls_search_txt2i 			= $_POST["search_txt2i"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];
  $ls_jenis_pembayaran	= $_POST["jenis_pembayaran"];
  $ls_status_siapbayar	= $_POST["status_siapbayar"];
  $ld_tgl1 							= $_POST["tgl_awal"];
  $ld_tgl2 							= $_POST["tgl_akhir"];
  $ld_tgl_penetapan 	  = $_POST["tgl_penetapan"];

  $ls_page 				 			= $_POST["page"];
  $ls_page_item 	 			= $_POST["page_item"];

	$ln_page 				 			= is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 			= is_numeric($ls_page_item) ? $ls_page_item : "10";

	$ln_start 			 			= (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 			= $ln_start + $ln_length - 1;

  $ls_order_colname = $ls_order_by;
  $ls_order_mode = $ls_order_type;

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

    if (($ls_search_txt2f != '') && ($ls_search_txt2f != 'null'))
		{
  		$ls_colname2 = $ls_search_by2;
			$ls_colval2  = $ls_search_txt2f;
		}else
    if (($ls_search_txt2g != '') && ($ls_search_txt2g != 'null'))
		{
  		$ls_colname2 = $ls_search_by2;
			$ls_colval2  = $ls_search_txt2g;
		}else
    if (($ls_search_txt2h != '') && ($ls_search_txt2h != 'null'))
		{
  		$ls_colname2 = $ls_search_by2;
			$ls_colval2  = $ls_search_txt2h;
		}else
    if (($ls_search_txt2i != '') && ($ls_search_txt2i != 'null'))
		{
  		$ls_colname2 = $ls_search_by2;
			$ls_colval2  = $ls_search_txt2i;
		}else
    if (($ld_tgl_penetapan != '') && ($ld_tgl_penetapan != 'null'))
		{
  		$ls_colname2 = $ls_search_by2;
			$ls_colval2  = $ld_tgl_penetapan;
		}else{
      $ls_colname2 = "";
      $ls_colval2  = "";
    }
	}

	//get data from WS -----------------------------------------------------------
  global $wsIp;
  $ipDev  = $wsIp."/JSPN5043/DataGridMonitoringTransfer";
  $url    = $ipDev;

  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
		'Accept'=> 'application/json',
  	'X-Forwarded-For'=> $ipfwd,
  );

  // set POST params -----------------------------------------------------------
	$data = array(
      'chId'				 			=> $chId,
      'reqId'				 			=> $gs_kode_user,
			'TGL_AWAL'		 			=> $ld_tgl1,
      'TGL_AKHIR'		 			=> $ld_tgl2,
  		'STATUS_SIAPBAYAR'	=> (int)$ls_status_siapbayar,
			'KODE_KANTOR'	 			=> $gs_kantor_aktif,
      'PAGE'				 			=> (int)$ln_page,
      'NROWS'				 			=> (int)$ln_length,
      'C_COLNAME'		 			=> $ls_colname,
      'C_COLVAL'		 			=> $ls_colval,
  		'C_COLNAME2'	 			=> $ls_colname2,
      'C_COLVAL2'		 			=> $ls_colval2,
      'O_COLNAME'		 			=> $ls_order_colname,
      'O_MODE'			 			=> $ls_order_mode
  );

  // var_dump($data);
  // die;


  // Open connection -----------------------------------------------------------
  $ch = curl_init();

  // Set the url, number of POST vars, POST data -------------------------------
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  // Execute post --------------------------------------------------------------
  $result = curl_exec($ch);
	$resultArray = json_decode(utf8_encode($result));

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
//end get data grid montoring lumpsum ------------------------------------------

//do Do Koreksi Data Error Transfer Lumpsum ------------------------------------
else if($ls_type =="fjq_ajax_val_lumpmonitoring_doKoreksiErrorTrf")
{
	$ls_kode_klaim          = $_POST["v_kode_klaim"];
  $ls_kode_tipe_penerima  = $_POST["v_kode_tipe_penerima"];
  $ls_kd_prg             	= $_POST["v_kd_prg"];
	$ls_ktr_byr             = $gs_kantor_aktif;

	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoTolakSiapTransfer';
	$dataKlaim = array();

  $dataKlaim_Dtl = array(
    "KODE_KLAIM"		 		 	=> $ls_kode_klaim,
    "KODE_TIPE_PENERIMA"	=> $ls_kode_tipe_penerima,
    "KD_PRG"		 		 			=> (int)$ls_kd_prg,
    "KANTOR_APVBAYAR"		 	=> $ls_ktr_byr
  );
  array_push($dataKlaim, $dataKlaim_Dtl);

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data Klaim Siap Transfer berhasil dikembalikan ke tahap sebelumnya untuk dilakukan verifikasi ulang, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end Do Koreksi Data Error Transfer Lumpsum -----------------------------------

//get data informasi transfer melalui get_payment_status -----------------------
else if ($ls_type == "fjq_ajax_val_lumpmonitoring_getpaymentstatus")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_tipe_penerima = $_POST["v_kode_tipe_penerima"];
	$ls_kd_prg 				= $_POST["v_kd_prg"];
	$ls_kode_transfer	= $_POST["v_kode_transfer"];

	//panggil ws utk get data konfirmasi jp berkala ------------------------------
	if ($ls_kode_klaim !="" && $ls_kode_tipe_penerima !="" && $ls_kd_prg !="" && $ls_kode_transfer !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/GetTransferStatusLumpsum";
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
      'KODE_KLAIM'=>$ls_kode_klaim,
      'KODE_TIPE_PENERIMA'=>$ls_kode_tipe_penerima,
			'KD_PRG'=>(int)$ls_kd_prg,
			'KODE_TRANSFER'=>$ls_kode_transfer
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
    $result_DtKodeTrf = curl_exec($ch);
    $resultArray_DtKodeTrf = json_decode(utf8_encode($result_DtKodeTrf));

    echo json_encode($resultArray_DtKodeTrf);
	}else
	{
	 	$ls_mess = "Kode Klaim/Tipe Penerima/Program/Kode Transfer kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data informasi transfer melalui get_payment_status -------------------

//get data grid montoring JP Berkala -------------------------------------------
if($ls_type == "MainDataGridJpBerkalaMonitoring")
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
                  "msg":"<b>Error:</b> '.$errorMsg.'"
              }';
        die();
      }
  }
  set_error_handler("DefaultGlobalErrorHandler");

  $condition ="";

	//get jml row data yang akan ditampilkan -------------------------------------
  $ls_search_by 	 	 		= $_POST["search_by"];
	$ls_search_txt 	 			= $_POST["search_txt"];
	$ls_search_by2 	 			= $_POST["search_by2"];
	$ls_search_txt2a 			= $_POST["search_txt2a"];
	$ls_search_txt2b 			= $_POST["search_txt2b"];
	$ls_search_txt2c 			= $_POST["search_txt2c"];
	$ls_search_txt2d 			= $_POST["search_txt2d"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];
  $ls_jenis_pembayaran	= $_POST["jenis_pembayaran"];
  $ls_status_siapbayar	= $_POST["status_siapbayar"];
  $ld_tgl1 							= $_POST["tgl_awal"];
  $ld_tgl2 							= $_POST["tgl_akhir"];

  $ls_page 				 			= $_POST["page"];
  $ls_page_item 	 			= $_POST["page_item"];

	$ln_page 				 			= is_numeric($ls_page) ? $ls_page : "1";
	$ln_length 			 			= is_numeric($ls_page_item) ? $ls_page_item : "10";

	$ln_start 			 			= (($ln_page -1) * $ln_length) + 1;
	$ln_end 				 			= $ln_start + $ln_length - 1;

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

  $ls_order_colname = $ls_order_by;
  $ls_order_mode = $ls_order_type;

	//get data from WS -----------------------------------------------------------
  global $wsIp;
  $ipDev  = $wsIp."/JSPN5043/DataGridMonitoringTransferJPBerkala";
  $url    = $ipDev;

  // set HTTP header -----------------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
		'Accept'=> 'application/json',
  	'X-Forwarded-For'=> $ipfwd,
  );

  // set POST params -----------------------------------------------------------
	$data = array(
      'chId'				 			=> $chId,
      'reqId'				 			=> $gs_kode_user,
			'TGL_AWAL'		 			=> $ld_tgl1,
      'TGL_AKHIR'		 			=> $ld_tgl2,
			'KODE_KANTOR'	 			=> $gs_kantor_aktif,
      'PAGE'				 			=> (int)$ln_page,
      'NROWS'				 			=> (int)$ln_length,
      'C_COLNAME'		 			=> $ls_colname,
      'C_COLVAL'		 			=> $ls_colval,
  		'C_COLNAME2'	 			=> $ls_colname2,
      'C_COLVAL2'		 			=> $ls_colval2,
      'O_COLNAME'		 			=> $ls_order_colname,
      'O_MODE'			 			=> $ls_order_mode
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
//end get data grid montoring JP Berkala ---------------------------------------

//do Do Koreksi Data Error Transfer JP Berkala ---------------------------------
else if($ls_type =="fjq_ajax_val_jpbklmonitoring_doKoreksiErrorTrf")
{
	$ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	$ln_no_proses 		= $_POST["v_no_proses"];
	$ls_kd_prg 				= $_POST["v_kd_prg"];

	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoTolakSiapTransferJPBerkala';
	$dataKlaim = array();

  $dataKlaim_Dtl = array(
    "KODE_KLAIM"=>$ls_kode_klaim,
    "NO_KONFIRMASI"=>(int)$ln_no_konfirmasi,
    "NO_PROSES"=>(int)$ln_no_proses,
    "KD_PRG"=>(int)$ls_kd_prg
  );
  array_push($dataKlaim, $dataKlaim_Dtl);

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
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
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data Manfaat JP Berkala Siap Transfer berhasil dikembalikan ke tahap sebelumnya untuk dilakukan verifikasi ulang, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end Do Koreksi Data Error Transfer JP Berkala --------------------------------

//get data informasi transfer melalui get_payment_status jp berkala ------------
else if ($ls_type == "fjq_ajax_val_jpbklmonitoring_getpaymentstatus")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	$ln_no_proses 		= $_POST["v_no_proses"];
	$ls_kd_prg 				= $_POST["v_kd_prg"];
	$ls_kode_transfer	= $_POST["v_kode_transfer"];

	//panggil ws utk get data konfirmasi jp berkala ------------------------------
	if ($ls_kode_klaim !="" && $ln_no_konfirmasi !="" && $ln_no_proses !="" && $ls_kd_prg !="" && $ls_kode_transfer !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/GetTransferStatusJPBerkala";
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
      'KODE_KLAIM'=>$ls_kode_klaim,
      'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi,
			'NO_PROSES'=>(int)$ln_no_proses,
			'KD_PRG'=>(int)$ls_kd_prg,
			'KODE_TRANSFER'=>$ls_kode_transfer
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
    $result_DtKodeTrf = curl_exec($ch);
    $resultArray_DtKodeTrf = json_decode(utf8_encode($result_DtKodeTrf));

    echo json_encode($resultArray_DtKodeTrf);
	}else
	{
	 	$ls_mess = "Data tidak dapat ditampilkan, paramater mandatory ada yang kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data informasi transfer melalui get_payment_status jp berkala --------

//get data pembayaran jp berkala -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatapembayaran_jpberkala")
{
  $ls_kode_klaim 		 	= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi 	= $_POST["v_no_konfirmasi"];
	$ln_no_proses 		 	= $_POST["v_no_proses"];
	$ls_kd_prg 		 			= $_POST["v_kd_prg"];
	$ls_kode_pembayaran = $_POST["v_kode_pembayaran"];

  if ($ls_kode_klaim!="" && $ln_no_konfirmasi!="" && $ln_no_proses!="" && $ls_kd_prg!="" && $ls_kode_pembayaran!="")
  {
    //get data from ws --------------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/JSPN5043/ViewJPBerkalaByKodePembayaran";
    $url    	= $ipDev;

    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );

    // set POST params -----------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
			'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi,
			'NO_PROSES'=>(int)$ln_no_proses,
			'KD_PRG'=>(int)$ls_kd_prg,
  		'KODE_PEMBAYARAN'=>$ls_kode_pembayaran
    );

    // Open connection -----------------------------------
    $ch = curl_init();

    // Set the url, number of POST vars, POST data -------
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Execute post --------------------------------------
    $result_DataByr = curl_exec($ch);
    $resultArray_DataByr = json_decode(utf8_encode($result_DataByr));
    //end get data from ws -----------------------------------------------

		if ($resultArray_DataByr->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DataByr->ret;
      $result_data_final["msg"]  = $resultArray_DataByr->msg;
      $result_data_final["dataByr"] = $resultArray_DataByr;

      echo json_encode($result_data_final);
		}else
		{
  	 	$ls_mess = $resultArray_DataByr->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';
		}
  }else
	{
	 	$ls_mess = "Parameter mandatory kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data pembayaran jp berkala -------------------------------------------

//get data view error jp berkala -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdataviewerrorjpberkala")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	$ln_no_proses 		= $_POST["v_no_proses"];
	$ls_kd_prg 				= $_POST["v_kd_prg"];

	//panggil ws utk get data konfirmasi jp berkala ------------------------------
	if ($ls_kode_klaim !="" && $ln_no_konfirmasi !="" && $ln_no_proses !="" && $ls_kd_prg !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/ViewSiapByrJPBerkalaByNoProses";
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
      'KODE_KLAIM'=>$ls_kode_klaim,
      'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi,
      'NO_PROSES'=>(int)$ln_no_proses,
      'KD_PRG'=>(int)$ls_kd_prg
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
    $result_DtDtlSiapByr = curl_exec($ch);
    $resultArray_DtDtlSiapByr = json_decode(utf8_encode($result_DtDtlSiapByr));

    echo json_encode($resultArray_DtDtlSiapByr);
	}else
	{
	 	$ls_mess = "Data Konfirmasi JP Berkala kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data view error jp berkala -------------------------------------------

//get data pejabat otorisasi pencetakan laporan siap bayar ---------------------
else if ($ls_type == "fjq_ajax_val_getdatapejabatcetak")
{
  $ls_kode_kantor = $_POST["v_kode_kantor"];

	//panggil ws utk get data pejabat ----------------------------------------
	if ($ls_kode_kantor !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/GetPejabatCetakSiapBayar";
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
      'KODE_KANTOR'=>$ls_kode_kantor
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
    $result_DtPjbt = curl_exec($ch);
    $resultArray_DtPjbt = json_decode(utf8_encode($result_DtPjbt));

    echo json_encode($resultArray_DtPjbt);
	}else
	{
	 	$ls_mess = "Kode Kantor kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end get data pejabat otorisasi pencetakan laporan siap bayar -----------------

//do cetak laporan siap bayar --------------------------------------------------
else if ($ls_type == "fjq_ajax_val_docetaksiapbayar")
{
  $ls_kode_kantor 	 	= $_POST["v_kode_kantor"];
  $ls_pejabat1 				= $_POST["v_pejabat1"];
  $ls_keterangan1 		= $_POST["v_keterangan1"];
  $ls_keterangan1_pps = $_POST["v_keterangan1_pps"];
	$ls_pejabat2 				= $_POST["v_pejabat2"];
  $ls_keterangan2 		= $_POST["v_keterangan2"];
  $ls_keterangan2_pps = $_POST["v_keterangan2_pps"];
  $ls_pejabat3 				= $_POST["v_pejabat3"];
  $ls_keterangan3 		= $_POST["v_keterangan3"];
  $ls_keterangan3_pps = $_POST["v_keterangan3_pps"];
  $ls_pejabat4 				= $_POST["v_pejabat4"];
  $ls_keterangan4 		= $_POST["v_keterangan4"];
  $ls_keterangan4_pps = $_POST["v_keterangan4_pps"];

	//panggil ws utk get data pejabat ----------------------------------------
	if ($ls_kode_kantor !="")
	{
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5043/UpdatePejabatCetakSiapBayar ";
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
      'KODE_KANTOR'=>$ls_kode_kantor,
      'PEJABAT1'=>$ls_pejabat1,
      'KETERANGAN1'=>$ls_keterangan1,
      'KETERANGAN1_PPS'=>$ls_keterangan1_pps,
      'PEJABAT2'=>$ls_pejabat2,
      'KETERANGAN2'=>$ls_keterangan2,
      'KETERANGAN2_PPS'=>$ls_keterangan2_pps,
      'PEJABAT3'=>$ls_pejabat3,
      'KETERANGAN3'=>$ls_keterangan3,
      'KETERANGAN3_PPS'=>$ls_keterangan3_pps,
      'PEJABAT4'=>$ls_pejabat4,
      'KETERANGAN4'=>$ls_keterangan4,
      'KETERANGAN4_PPS'=>$ls_keterangan4_pps
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
    $result_cetak = curl_exec($ch);
    $resultArray_cetak = json_decode(utf8_encode($result_cetak));

    echo json_encode($resultArray_cetak);
	}else
	{
	 	$ls_mess = "Kode Kantor kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
	}
}
//end do cetak laporan siap bayar ----------------------------------------------
// ------------------------ END MONITORING PEMBAYARAN --------------------------
?>
