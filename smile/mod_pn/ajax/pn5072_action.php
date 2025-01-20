<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$chId 	 	 			 = "SMILE";
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_role 	 = $_SESSION['regrole'];
$gs_kode_user 	 = $_SESSION["USER"];
$ls_type	 			 = $_POST["tipe"];

//get data grid ----------------------------------------------------------------
if($ls_type == "MainDataGridLumpsumApproval")
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
    $ipDev  = $wsIp."/JSPN5043/DataGridApprovalSiapBayar";
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
    	'STATUS_SIAPBAYAR'=>"2",
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
    $resultArray=json_decode('{"TOTAL_REC":"0"}');
   //] var_dump($resultArray);die();
	 	$resultArray->TOTAL_REC = 0;
		$jsondata = "";
	}
	//end tampilkan data jika kantor sudah sentralisasi rekening -----------------

	if ($resultArray->TOTAL_REC==0)
	{
    $jsondata=array();
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
		$jsondata=array();
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

//do Approval Data Siap Bayar --------------------------------------------------
else if($ls_type =="DoApvSiapByrLumpsum")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoApprovalSiapBayar';
  $urlDebt = $wsIp.'/JSPN5043/DoApprovalSiapBayarDebt';
	$dataKlaim = array();
  $dataKlaimDebt = array();

  $ln_panjang = $_POST['hdn_total_records_lumpapv'];
	$ls_mess1 = $ln_panjang;
  for($i=0;$i<=$ln_panjang-1;$i++)
  {
		//reset value --------------------------------------------------------------
		$ls_d_kode_klaim	= "";
		$ls_d_kode_tipe_penerima	= "";
		$ls_d_kd_prg	= "";
		$ls_d_ktr_byr	= "";

		//get status tickmark ------------------------------------------------------
		$ls_d_isTickmark	= $_POST['cboxRecord'.$i];
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
		 	$ls_d_kode_klaim	= $_POST['lumpapv_kode_klaim'.$i];
			$ls_d_kode_tipe_penerima	= $_POST['lumpapv_kode_tipe_penerima'.$i];
			$ls_d_kd_prg	= $_POST['lumpapv_kd_prg'.$i];
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

        $sql = "SELECT NVL(FLAG_BPR_KUR,'T') FLAG_BPR_KUR FROM PN.PN_KLAIM WHERE KODE_KLAIM = '$ls_d_kode_klaim'";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_st_bpr_kur = $row['FLAG_BPR_KUR'];

        $sql = "SELECT KODE_TIPE_PENERIMA FROM PN.PN_KLAIM_MANFAAT_SPLIT WHERE KODE_KLAIM = '$ls_d_kode_klaim'";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_kode_tipe_penerima_debt = $row['KODE_TIPE_PENERIMA'];

        $dataKlaim_Dtl['KODE_TIPE_PENERIMA'] = $ls_kode_tipe_penerima_debt;

        if($ls_st_bpr_kur == "Y" && $ls_d_kode_tipe_penerima == 'AW'){
          array_push($dataKlaimDebt, $dataKlaim_Dtl);
        }
      }
		}
	}

  $data = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaim
  );

  $dataDebt = array(
    "chId"  => "SMILE",
    "reqId" => $gs_kode_user,
    "dataKlaim" 	=> $dataKlaimDebt
  );

  // Open connection
  $ch = curl_init();

  // Set the url, number of POST vars, POST data
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

  // Execute post
  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);

  // var_dump($sql);
  // var_dump($DB);
  // var_dump(count($dataKlaimDebt));
  // var_dump($dataKlaimDebt);
  // die;


  $chDebt = curl_init();

  // Set the url, number of POST vars, POST data
  curl_setopt($chDebt, CURLOPT_URL, $urlDebt);
  curl_setopt($chDebt, CURLOPT_POST, true);
  curl_setopt($chDebt, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($chDebt, CURLOPT_RETURNTRANSFER, true );
  // curl_setopt($chDebt, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($chDebt, CURLOPT_POSTFIELDS, json_encode($dataDebt));

  // Execute post

  $resultDebt;
  $resultArrayDebt;

  if(count($dataKlaimDebt) > 0){
    $resultDebt = utf8_encode(curl_exec($chDebt));
    $resultArrayDebt = json_decode($resultDebt);
  }

  $ls_mess_debt = "";
  $ls_mess_err_debt = "";

  if ($resultArrayDebt->ret=='0')
  {
		$ls_mess_debt = " dan pembayaran split kreditur ";
  }else
  {
	 	$ls_mess_err_debt = "<br/>".$resultArrayDebt->msg."<br/>";
  }

  // var_dump($resultArrayDebt);
  // var_dump($ls_mess_debt);
  // var_dump($ls_mess_err_debt);
  // var_dump(count($dataKlaimDebt));
  // die;


  if ($resultArray->ret=='0')
  {
    $ls_mess = '{"ret":0,"msg":"Data Klaim Siap Bayar '.$ls_mess_debt.' berhasil diapproval, '.$ls_mess_err_debt.' session dilanjutkan.."}';
		echo $ls_mess;
  }else
  {
	 	$ls_mess = $resultArray->msg;
    $ls_mess = $ls_mess.$ls_mess_err_debt;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end do Approval Data Siap Bayar ----------------------------------------------

//do Tolak Data Siap Bayar -----------------------------------------------------
else if($ls_type =="DoTolakSiapByrLumpsum")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoTolakSiapBayar';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_lumpapv'];
	$ls_mess1 = $ln_panjang;
  for($i=0;$i<=$ln_panjang-1;$i++)
  {
		//reset value --------------------------------------------------------------
		$ls_d_kode_klaim	= "";
		$ls_d_kode_tipe_penerima	= "";
		$ls_d_kd_prg	= "";
		$ls_d_ktr_byr	= "";

		//get status tickmark ------------------------------------------------------
		$ls_d_isTickmark	= $_POST['cboxRecord'.$i];
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
		 	$ls_d_kode_klaim	= $_POST['lumpapv_kode_klaim'.$i];
			$ls_d_kode_tipe_penerima	= $_POST['lumpapv_kode_tipe_penerima'.$i];
			$ls_d_kd_prg	= $_POST['lumpapv_kd_prg'.$i];
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
		echo '{"ret":0,"msg":"Data Klaim Siap Bayar berhasil dikembalikan ke tahap sebelumnya untuk dilakukan verifikasi ulang, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end Tolak Data Siap Bayar ----------------------------------------------------

//get data grid JP Bekala ------------------------------------------------------
else if($ls_type == "MainDataGridJPBerkalaApproval")
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
    $ipDev  = $wsIp."/JSPN5043/DataGridApprovalSiapBayarJPBerkala";
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
    	'STATUS_SIAPBAYAR'=>"2",
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
    $resultArray=json_decode('{"TOTAL_REC":"0"}');
   //] var_dump($resultArray);die();
	 	$resultArray->TOTAL_REC = 0;
		$jsondata = "";
	}
	//end tampilkan data jika kantor sudah sentralisasi rekening -----------------

	if ($resultArray->TOTAL_REC==0)
	{
    $jsondata=Array();
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
		$jsondata=Array();
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

//do Approval Data Siap Bayar JP Bekala ----------------------------------------
else if($ls_type =="DoApvSiapByrJPBerkala")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoApprovalSiapBayarJPBerkala';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_jpbklapv'];
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
		$ls_d_isTickmark	= $_POST['cboxRecord'.$i];
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
		 	$ls_d_kode_klaim	= $_POST['jpbklapv_kode_klaim'.$i];
			$ls_d_no_konfirmasi	= $_POST['jpbklapv_no_konfirmasi'.$i];
			//$ls_d_no_proses	= $_POST['jpbklapv_no_proses'.$i];
			$ls_d_kd_prg	= $_POST['jpbklapv_kd_prg'.$i];
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
		echo '{"ret":0,"msg":"Data JP Berkala Siap Bayar berhasil diapproval, session dilanjutkan.."}';
  }else
  {
	 	$ls_mess = $resultArray->msg;
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';
  }
}
//end do Approval Data Siap Bayar JP Bekala ------------------------------------

//do Tolak Data Siap Bayar JP Bekala -------------------------------------------
else if($ls_type =="DoTolakSiapByrJPBerkala")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];

  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

	$url = $wsIp.'/JSPN5043/DoTolakSiapBayarJPBerkala';
	$dataKlaim = array();

  $ln_panjang = $_POST['hdn_total_records_jpbklapv'];
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
		$ls_d_isTickmark	= $_POST['cboxRecord'.$i];
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
		 	$ls_d_kode_klaim	= $_POST['jpbklapv_kode_klaim'.$i];
			$ls_d_no_konfirmasi	= $_POST['jpbklapv_no_konfirmasi'.$i];
			$ls_d_no_proses	= $_POST['jpbklapv_no_proses'.$i];
			$ls_d_kd_prg	= $_POST['jpbklapv_kd_prg'.$i];
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

?>
