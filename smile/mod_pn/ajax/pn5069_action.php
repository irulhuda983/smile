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
	$ls_search_txt2e 			= $_POST["search_txt2e"];
	$ls_order_by 		 			= $_POST["order_by"];
	$ls_order_type 	 			= $_POST["order_type"];
  $ls_kategori					= $_POST["kategori"];
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
  					if (($ls_search_txt2e != '') && ($ls_search_txt2e != 'null')) 
  					{
  					 	$ls_colname2 = $ls_search_by2;
  						$ls_colval2  = $ls_search_txt2e; 
  					}else
  					{
  						$ls_colname2 = "";
        			$ls_colval2  = "";
  					}	
					}	
				}			
			}			
		}						
	}
		
	//get data from WS -----------------------------------------------------------
  global $wsIp;
  $ipDev  = $wsIp."/JSPN5049/DataGrid";
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
      'TGL_AWAL'		 => $ld_tgl1,     
      'TGL_AKHIR'		 => $ld_tgl2,  
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
	
	$ls_mode_transaksi = "view";
	
	//var_dump($resultArray);
  for($i=0;$i<ExtendedFunction::count($resultArray->DATA);$i++){																				
		$resultArray->DATA[$i]->KODE_KLAIM_DISPLAY = '<a href="#" onClick="doGridTask(\''.$resultArray->DATA[$i]->KODE_KLAIM.'\',\''.$ls_mode_transaksi.'\',\''.$ld_tgl1.'\',\''.$ld_tgl2.'\',\''.$ls_kategori.'\');"><font color="#009999">'.$resultArray->DATA[$i]->KODE_KLAIM.'</font></a>';
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

        //ambil data list konfirmasi dan pembayaran jp berkala ---------
				$ipDev  	= "";
        global $wsIp;
        $ipDev  	= $wsIp."/JSPN5049/ViewKonfirmasiBerkalaByKodeKlaim";
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
        $result_ListKonfByrBerkala = curl_exec($ch);
        $resultArray_ListKonfByrBerkala = json_decode(utf8_encode($result_ListKonfByrBerkala));
				//end ambil data list konfirmasi dan pembayaran jp berkala -----																	
			}
			//end get data khusus klaim jp -----------------------------------
			
			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtByKdKlaim->ret;
      $result_data_final["msg"]  = $resultArray_DtByKdKlaim->msg;
      $result_data_final["data"] = $resultArray_DtByKdKlaim;
      $result_data_final["dataTapMnfLumpsum"] = $resultArray_ListMnf;
			$result_data_final["dataPenerimaMnfLumpsum"] = $resultArray_ListPenerimaMnf;
			$result_data_final["dataByrMnfLumpsum"] = $resultArray_ListByrMnf;
			
			if ($resultArray_DtByKdKlaim->INFO_KLAIM->KODE_TIPE_KLAIM=="JPN01")
			{
			 	$result_data_final["dataJpnListAws"] = $resultArray_ListJpnAhliwaris;
				$result_data_final["dataTapMnfBerkala"] = $resultArray_ListMnfBerkala;
				$result_data_final["dataPenerimaMnfBerkala"] = $resultArray_ListPenerimaMnfBerkala;
				$result_data_final["dataKonfByrBerkala"] = $resultArray_ListKonfByrBerkala;  
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

//get data rincian penetapan manfaat -------------------------------------------
else if ($ls_type == "fjq_ajax_val_getmanfaatbykodemnf_old")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat	= $_POST["v_kode_manfaat"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="")
  {
    global $wsIp;
		$ipDev = $wsIp."/JSPN5041/ListManfaatDetilByKodeMnfKlaim";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_MANFAAT'=>$ls_kode_manfaat	
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
    $resultDtMnf = curl_exec($ch);
    $resultArray_DtMnf = json_decode(utf8_encode($resultDtMnf));
		
		if ($resultArray_DtMnf->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtMnf->ret;
      $result_data_final["msg"]  = $resultArray_DtMnf->msg;
      $result_data_final["data"] = $resultArray_DtMnf;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtMnf->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Manfaat kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data list penetapan manfaat ------------------------------------------

//get data rincian penetapan manfaat -------------------------------------------
else if ($ls_type == "fjq_ajax_val_getmanfaatbykodemnf")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat	= $_POST["v_kode_manfaat"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="")
  {
    global $wsIp;
		$ipDev = $wsIp."/JSPN5041/ListManfaatDetilByKodeMnfKlaim";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_MANFAAT'=>$ls_kode_manfaat	
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
    $resultDtMnf = curl_exec($ch);
    $resultArray_DtMnf = json_decode(utf8_encode($resultDtMnf));
		
		if ($resultArray_DtMnf->ret=='0')
    {
			//get info klaim ------------------------------------------------------
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
        'KODE_KLAIM'=>$ls_kode_klaim,
  			'IS_INFO_KLAIM_ONLY'=>"Y"
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
				$ls_jenis_klaim = $resultArray_DtByKdKlaim->INFO_KLAIM->JENIS_KLAIM;
				
				//jika beasiswa jkm maka tampilkan data upah dan amalgamasi jkm --
				if ($ls_kode_manfaat=="2" && ($ls_jenis_klaim=="JKM" || $ls_jenis_klaim=="JHM"))
				{
          global $wsIp;
          $ipDev  = $wsIp."/JSPN5041/LovUpah";
          $url    = $ipDev;
          
          // set HTTP header ----------------------------------------------------
          $headers = array(
            'Content-Type'=> 'application/json',
            'X-Forwarded-For'=> $ipfwd,
          );
          
      		//agar tampil semua maka ld_tgl_kedian diset ke tahun 1800 
      		$ld_tgl_kejadian = "01/01/1800";
      		
          // set POST params ----------------------------------------------------
          $data = array(
            'chId'  => $chId,
            'reqId' => $gs_kode_user,
            'KODE_KLAIM' => $ls_kode_klaim,
            'TGL_KEJADIAN' => $ld_tgl_kejadian,
            'PAGE'	=> 1,
            'NROWS'	=> 1000,
            'C_COLNAME'	=> "",
            'C_COLVAL'	=> "",
            'C_COLNAME2'	=> "",
            'C_COLVAL2'	=> "",
            'O_COLNAME'	=> "BLTH_YYYYMMDD",
            'O_MODE' => "ASC"
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
          $resultDtUpah = curl_exec($ch);
          $resultArray_DtUpah = json_decode(utf8_encode($resultDtUpah));				
				}
				//end data upah dan amalgamasi jkm -------------------------------			
			}
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtMnf->ret;
      $result_data_final["msg"]  = $resultArray_DtMnf->msg;
      $result_data_final["data"] = $resultArray_DtMnf;
			$result_data_final["dataUpah"] = $resultArray_DtUpah;
			$result_data_final["dataKdKlaim"] = $resultArray_DtByKdKlaim;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtMnf->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Manfaat kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data list penetapan manfaat ------------------------------------------

//get data rincian manfaat -----------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatamanfaatdetil")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat	= $_POST["v_kode_manfaat"];
	$ln_no_urut 			= $_POST["v_no_urut"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="" && $ln_no_urut!="")
  {
    global $wsIp;
		$ipDev = $wsIp."/JSPN5040/ViewDataManfaatDetil";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_MANFAAT'=>$ls_kode_manfaat,
  		'NO_URUT'=>(int)$ln_no_urut			
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
    $resultDtMnfDtl = curl_exec($ch);
    $resultArray_DtMnfDtl = json_decode(utf8_encode($resultDtMnfDtl));
		
		if ($resultArray_DtMnfDtl->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtMnfDtl->ret;
      $result_data_final["msg"]  = $resultArray_DtMnfDtl->msg;
      $result_data_final["data"] = $resultArray_DtMnfDtl;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtMnfDtl->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Manfaat/No Urut Manfaat kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data rincian manfaat -------------------------------------------------

//get data rincian penerima manfaat --------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatapenerimamanfaatdetil")
{
  $ls_kode_klaim 		 		 = $_POST["v_kode_klaim"];
	$ls_kode_tipe_penerima = $_POST["v_kode_tipe_penerima"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ls_kode_tipe_penerima!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5041/ViewPenerimaManfaatByTipe";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_TIPE_PENERIMA'=>$ls_kode_tipe_penerima	
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
    $resultDtPnrmDtl = curl_exec($ch);
    $resultArray_DtPnrmDtl = json_decode(utf8_encode($resultDtPnrmDtl));
		
		if ($resultArray_DtPnrmDtl->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtPnrmDtl->ret;
      $result_data_final["msg"]  = $resultArray_DtPnrmDtl->msg;
      $result_data_final["data"] = $resultArray_DtPnrmDtl;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtPnrmDtl->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Tipe Penerima kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data rincian penerima manfaat ----------------------------------------

//get data diagnosa dan aktivitas pelaporan pengajuan jkk by kode klaim --------
else if ($ls_type == "fjq_ajax_val_getdatadiagnosajkkbykodeklaim")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];

  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="")
  {
    //get data diagnosa jkk -----------------------------------------------
		global $wsIp;
    $ipDev  = $wsIp."/JSPN5041/ListDiagnosaByKodeKlaim";
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
    $resultDtDiagnosaJKK = curl_exec($ch);
    $resultArray_DtDiagnosaJKK = json_decode(utf8_encode($resultDtDiagnosaJKK));
		
		//get data aktivitas pelaporan jkk ------------------------------------
		global $wsIp;
    $ipDev  = $wsIp."/JSPN5041/ListAktivitasLapByKodeKlaim";
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
    $resultDtAktivLapJKK = curl_exec($ch);
    $resultArray_DtAktivLapJKK = json_decode(utf8_encode($resultDtAktivLapJKK));
		
		//return data ke UI --------------------------------------------
    $result_data_final = array();
		$result_data_final["ret"]  = 0;
    $result_data_final["msg"]  = "OK";
    $result_data_final["dataDiagnosa"] = $resultArray_DtDiagnosaJKK;
    $result_data_final["dataAktivLap"] = $resultArray_DtAktivLapJKK;
		
		echo json_encode($result_data_final);
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
//end get data diagnosa dan aktivitas pelaporan pengajuan jkk by kode klaim ----

//get data rincian ahli waris jp -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdataahliwarisjpdetil")
{
  $ls_kode_klaim 		 	 = $_POST["v_kode_klaim"];
	$ln_no_urut_keluarga = $_POST["v_no_urut_keluarga"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ln_no_urut_keluarga!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5040/ViewDataAhliWarisJP";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
      'KODE_KLAIM'				=>$ls_kode_klaim,
      'NO_URUT_KELUARGA'	=>(int)$ln_no_urut_keluarga
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
    $resultDtAwrDtl = curl_exec($ch);
    $resultArray_DtAwrDtl = json_decode(utf8_encode($resultDtAwrDtl));
		
		if ($resultArray_DtAwrDtl->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtAwrDtl->ret;
      $result_data_final["msg"]  = $resultArray_DtAwrDtl->msg;
      $result_data_final["data"] = $resultArray_DtAwrDtl;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtAwrDtl->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/No Urut Keluarga kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data rincian ahli waris jp -------------------------------------------

//get data rincian penetapan jp berkala ----------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatamanfaatjpnberkaladetil")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	$ln_no_proses 		= $_POST["v_no_proses"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ln_no_konfirmasi!="" && $ln_no_proses!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5041/ListManfaatBerkalaDtlByKodeKlaim";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
			'KODE_KLAIM'=>$ls_kode_klaim,
      'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi,
      'NO_PROSES'=>(int)$ln_no_proses
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
    $resultDtJpBklDtl = curl_exec($ch);
    $resultArray_DtJpBklDtl = json_decode(utf8_encode($resultDtJpBklDtl));
		
		if ($resultArray_DtJpBklDtl->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtJpBklDtl->ret;
      $result_data_final["msg"]  = $resultArray_DtJpBklDtl->msg;
      $result_data_final["data"] = $resultArray_DtJpBklDtl;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtJpBklDtl->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/No Konfirimasi/No Proses kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data rincian penetapan manfaat jp berkala ----------------------------

//get data rincian penerima manfaat jp berkala ---------------------------------
else if ($ls_type == "fjq_ajax_val_getdatapenerimajpnberkaladetil")
{
  $ls_kode_klaim = $_POST["v_kode_klaim"];
	$ls_kode_penerima_berkala = $_POST["v_kode_penerima_berkala"];
	
  //get data informasi klaim by kode_klaim from WS ----------------------
  if ($ls_kode_klaim!="" && $ls_kode_penerima_berkala!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5041/ViewPenerimaManfaatBerkala";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
			'KODE_KLAIM'=>$ls_kode_klaim,
    	'KODE_PENERIMA_BERKALA'=>$ls_kode_penerima_berkala
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
    $resultDtPnrmJpBklDtl = curl_exec($ch);
    $resultArray_DtPnrmJpBklDtl = json_decode(utf8_encode($resultDtPnrmJpBklDtl));
		
		if ($resultArray_DtPnrmJpBklDtl->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtPnrmJpBklDtl->ret;
      $result_data_final["msg"]  = $resultArray_DtPnrmJpBklDtl->msg;
      $result_data_final["data"] = $resultArray_DtPnrmJpBklDtl;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtPnrmJpBklDtl->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Penerima Berkala kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}	
	//end get data informasi klaim by kode_klaim from WS --------------------	
}	
//end get data rincian penerima manfaat jp berkala -----------------------------

//get data rekap manfaat jp berkala --------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatarekapberkalabynokonfirmasi")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	
  if ($ls_kode_klaim!="" && $ln_no_konfirmasi!="")
  {
   	//get data manfaat jp berkala rekap ----------------------------------- 
	  global $wsIp;
		$ipDev  = $wsIp."/JSPN5041/ListManfaatBerkalaByKodeKlaim";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
			'KODE_KLAIM'=>$ls_kode_klaim,
      'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi
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
    $resultDtMnfJpBklRkp = curl_exec($ch);
    $resultArray_DtMnfJpBklRkp = json_decode(utf8_encode($resultDtMnfJpBklRkp));
		//end get data manfaat jp berkala rekap -------------------------------
		
   	//get data penerima manfaat jp berkala rekap --------------------------
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
      'NO_KONFIRMASI'=>(int)$ln_no_konfirmasi
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
    $resultDtPnrmJpBklRkp = curl_exec($ch);
    $resultArray_DtPnrmJpBklRkp = json_decode(utf8_encode($resultDtPnrmJpBklRkp));
		//end get data manfaat jp berkala rekap -------------------------------		
		
		
    //return data ke UI --------------------------------------------
    $result_data_final = array();
    $result_data_final["ret"]  = 0;
    $result_data_final["msg"]  = 'OK';
    $result_data_final["dataMnfJpBklRekap"]  = $resultArray_DtMnfJpBklRkp;
    $result_data_final["dataPnrmJpBklRekap"] = $resultArray_DtPnrmJpBklRkp;
    
    echo json_encode($result_data_final);	
  }else
	{
	 	$ls_mess = "Kode Klaim/No Konfirimasi kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end get data rekap manfaat jp berkala ----------------------------------------

//get data rincian alokasi kompensasi jp berkala -------------------------------
else if ($ls_type == "fjq_ajax_val_getdataalokasikompensasijpberkala")
{
  $ls_kode_kompensasi	= $_POST["v_kode_kompensasi"];
	
  if ($ls_kode_kompensasi!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5049/ViewKompensasiBerkalaByKode";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
			'KODE_KOMPENSASI'=>$ls_kode_kompensasi
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
    $resultDtAlokKomp = curl_exec($ch);
    $resultArray_DtAlokKomp = json_decode(utf8_encode($resultDtAlokKomp));
		
		if ($resultArray_DtAlokKomp->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtAlokKomp->ret;
      $result_data_final["msg"]  = $resultArray_DtAlokKomp->msg;
      $result_data_final["data"] = $resultArray_DtAlokKomp;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtAlokKomp->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Kompensasi kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end get data rincian alokasi kompensasi jp berkala ---------------------------

//get data list detil stmb -----------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getliststmbdetil")
{
  $ls_kode_klaim	 = $_POST["v_kode_klaim"];
	$ls_kode_manfaat = $_POST["v_kode_manfaat"];
	$ln_no_urut			 = $_POST["v_no_urut"];
	
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="" && $ln_no_urut!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5041/ListSTMBDetil";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
			'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_MANFAAT'=>$ls_kode_manfaat,
  		'NO_URUT'=>(int)$ln_no_urut
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
    $resultDtStmbDtl = curl_exec($ch);
    $resultArray_DtStmbDtl = json_decode(utf8_encode($resultDtStmbDtl));
		
		if ($resultArray_DtStmbDtl->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtStmbDtl->ret;
      $result_data_final["msg"]  = $resultArray_DtStmbDtl->msg;
      $result_data_final["data"] = $resultArray_DtStmbDtl;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_DtStmbDtl->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Manfaat/No Urut kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end get data list detil stmb -------------------------------------------------

//get data list histori beasiswa -----------------------------------------------
else if ($ls_type == "fjq_ajax_val_gethistoribeasiswa")
{
  $ls_kode_klaim	 = $_POST["v_kode_klaim"];
	$ls_kode_manfaat = $_POST["v_kode_manfaat"];
	$ls_nik_penerima = $_POST["v_nik_penerima"];
	
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="" && $ls_nik_penerima!="")
  {
    global $wsIp;
		$ipDev  = $wsIp."/JSPN5041/ListBeasiswaHistori";
    $url   = $ipDev;
    
    // set HTTP header ----------------------------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params ----------------------------------------------------
    $data = array(
      'chId'=>$chId,
      'reqId'=>$gs_kode_user,
			'KODE_KLAIM'=>$ls_kode_klaim,
  		'KODE_MANFAAT'=>$ls_kode_manfaat,
  		'NIK_PENERIMA'=>$ls_nik_penerima
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
    $resultBeasHis = curl_exec($ch);
    $resultArray_BeasHis = json_decode(utf8_encode($resultBeasHis));
		
		if ($resultArray_BeasHis->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_BeasHis->ret;
      $result_data_final["msg"]  = $resultArray_BeasHis->msg;
      $result_data_final["data"] = $resultArray_BeasHis;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_BeasHis->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/Kode Manfaat/NIK Penerima Beasiswa kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end get data list detil stmb -------------------------------------------------

//get data list dokumen kelengkapan konfirmasi jp berkala ----------------------
else if ($ls_type == "fjq_ajax_val_getlistdokkonfirmasijpberkala")
{
  $ls_kode_klaim	 = $_POST["v_kode_klaim"];
	$ln_no_konfirmasi = $_POST["v_no_konfirmasi"];
	
  if ($ls_kode_klaim!="" && $ln_no_konfirmasi!="")
  {
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
		
		if ($resultArray_Dok->ret=='0')
    {
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_Dok->ret;
      $result_data_final["msg"]  = $resultArray_Dok->msg;
      $result_data_final["data"] = $resultArray_Dok;
			
      echo json_encode($result_data_final);	
		}else
		{
  	 	$ls_mess = $resultArray_Dok->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		}
  }else
	{
	 	$ls_mess = "Kode Klaim/No Konfirmasi kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end get data list dokumen kelengkapan konfirmasi jp berkala ------------------

//get data list searcby --------------------------------------------------------
else if($ls_type == "fjq_ajax_val_getsearchlist")
{
	//panggil ws -----------------------------------------------------------------
    //get data from ws tipe klaim ----------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/JSPN5040/LovKodeTipeKlaim";
    $url    	= $ipDev;
    
    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params -----------------------------------
    $data = array(
      'chId'  => $chId,
      'reqId' => $gs_kode_user,
      'KODE_SEGMEN'	=> "",
      'PAGE'	=> 1,
      'NROWS'	=> 1000,
      'C_COLNAME'	=> "",
      'C_COLVAL'	=> ""
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
    $result_tipeklaim = curl_exec($ch);
    $resultArray_tipeklaim = json_decode(utf8_encode($result_tipeklaim));
    //end get data from ws tipe klaim ------------------------------------------
			
    //get data from ws sebab klaim ---------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/JSPN5040/LovKodeSebabKlaim";
    $url    	= $ipDev;
    
    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params -----------------------------------
    $data = array(
      'chId'  => $chId,
      'reqId' => $gs_kode_user,
      'TGL'		=> "",
      'KODE_TIPE_KLAIM' => "",
      'KODE_SEGMEN'	=> "",
      'PAGE'	=> 1,
      'NROWS'	=> 1000,
      'C_COLNAME'	=> "",
      'C_COLVAL'	=> "",
      'C_COLNAME2'=> "",
      'C_COLVAL2'	=> "",
      'O_COLNAME'	=> "NO_URUT",
      'O_MODE' => "ASC"		
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
    $result_sebabklaim = curl_exec($ch);
    $resultArray_sebabklaim = json_decode(utf8_encode($result_sebabklaim));
    //end get data from ws sebab klaim -----------------------------------------

    //get data from ws segmen --------------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/JSKN1001/LovKodeSegmen";
    $url    	= $ipDev;
    
    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params -----------------------------------
    $data = array(
      'chId'  => $chId,
      'reqId' => $gs_kode_user,
      'PAGE'	=> 1,
      'NROWS'	=> 1000,
      'C_COLNAME'	=> "",
      'C_COLVAL'	=> "",
      'C_COLNAME2'=> "",
      'C_COLVAL2'	=> "",
      'O_COLNAME'	=> "NO_URUT",
      'O_MODE' => "ASC"		
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
    $result_segmen = curl_exec($ch);
    $resultArray_segmen = json_decode(utf8_encode($result_segmen));
    //end get data from ws segmen ----------------------------------------------

    //get data from ws status klaim --------------------------------------------
    $ipDev ="";
    global $wsIp;
    $ipDev  	= $wsIp."/MS1001/LovMsLookup";
    $url    	= $ipDev;
    
    // set HTTP header -----------------------------------
    $headers = array(
      'Content-Type'=> 'application/json',
      'X-Forwarded-For'=> $ipfwd,
    );
    
    // set POST params -----------------------------------
    $data = array(
      'chId'  => $chId,
      'reqId' => $gs_kode_user,
			'TIPE'	=> "STATUSKLM",
      'PAGE'	=> 1,
      'NROWS'	=> 1000,
      'C_COLNAME'	=> "",
      'C_COLVAL'	=> "",
      'C_COLNAME2'=> "",
      'C_COLVAL2'	=> "",
      'O_COLNAME'	=> "SEQ",
      'O_MODE' => "ASC"		
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
    $result_statusklaim = curl_exec($ch);
    $resultArray_statusklaim = json_decode(utf8_encode($result_statusklaim));
    //end get data from ws status klaim ----------------------------------------

    //Get List Program
    $sql = "select kd_prg, nm_prg from ms.ms_prg where st_aktif = 'Y' and kd_prg not in ('10') ";
    $DB->parse($sql);
    $DB->execute();
    $arrPrg = [];
    while($row = $DB->nextrow())
    {
      $arrPrg[$row['KD_PRG']] = $row['NM_PRG'];
    }

    $resultArray_ListProgram = $arrPrg;
    //END Get List Program

    //Get List Tipe Penerima
    $sql = "SELECT KODE_TIPE_PENERIMA, NAMA_TIPE_PENERIMA FROM PN_KODE_TIPE_PENERIMA WHERE STATUS_NONAKTIF = 'T'";
    $DB->parse($sql);
    $DB->execute();
    $arrTipePenerima = [];
    while($row = $DB->nextrow())
    {
      $arrTipePenerima[$row['KODE_TIPE_PENERIMA']] = $row['NAMA_TIPE_PENERIMA'];
    }

    $resultArray_TipePenerima = $arrTipePenerima;
    //END Get List Tipe Penerima

    //Get List Bank Penerima
    $sql = "select KODE_BANK, NAMA_BANK from spo.spo_bank@to_nsp";
    $DB->parse($sql);
    $DB->execute();
    $arrBankPenerima = [];
    while($row = $DB->nextrow())
    {
      $arrBankPenerima[$row['KODE_BANK']] = $row['NAMA_BANK'];
    }

    $resultArray_BankPenerima = $arrBankPenerima;
    //END Get List Bank Penerima
						
		if ($resultArray_tipeklaim->ret=='0')
    {		
			//return data ke UI --------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_sebabklaim->ret;
      $result_data_final["msg"]  = $resultArray_sebabklaim->msg;
			$result_data_final["dtListTipeKlaim"] = $resultArray_tipeklaim;
      $result_data_final["dtListSebabKlaim"] = $resultArray_sebabklaim;
			$result_data_final["dtListSegmen"] = $resultArray_segmen;
			$result_data_final["dtListStatusKlaim"] = $resultArray_statusklaim;
      $result_data_final["dtListProgram"] = $resultArray_ListProgram;
      $result_data_final["dtListTipePenerima"] = $resultArray_TipePenerima;
      $result_data_final["dtListBankPenerima"] = $resultArray_BankPenerima;
						
      echo json_encode($result_data_final);		
		}else
		{
  	 	$ls_mess = $resultArray_tipeklaim->msg;
      echo '{
          "ret":-1,
          "success":false,
          "msg":"'.$ls_mess.'"
      }';		
		} 
} 
//end get data list searcby ----------------------------------------------------
?>
