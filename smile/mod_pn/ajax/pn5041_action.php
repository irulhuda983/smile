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

//get data rincian penetapan manfaat -------------------------------------------
if ($ls_type == "fjq_ajax_val_getmanfaatbykodemnf")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat	= $_POST["v_kode_manfaat"];

  //get data informasi klaim by kode_klaim from WS ------------------------
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
      //get data informasi klaim by kode_klaim from WS --------------------
      if ($ls_kode_klaim!="" && $ls_kode_manfaat!="")
      {
        global $wsIp;
    		$ipDev = $wsIp."/JSPN5041/ListManfaatDetilByKodeMnfKlaim";
        $url   = $ipDev;
        
        // set HTTP header ------------------------------------------------
        $headers = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );
        
        // set POST params ------------------------------------------------
        $data = array(
          'chId'=>$chId,
          'reqId'=>$gs_kode_user,
          'KODE_KLAIM'=>$ls_kode_klaim,
      		'KODE_MANFAAT'=>$ls_kode_manfaat	
        );
        
        // Open connection -----------------------------------------------
        $ch = curl_init();
        
        // Set the url, number of POST vars, POST data -------------------
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        // Execute post --------------------------------------------------
        $resultDtMnf = curl_exec($ch);
        $resultArray_DtMnf = json_decode(utf8_encode($resultDtMnf));
				
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
			
			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtByKdKlaim->ret;
      $result_data_final["msg"]  = $resultArray_DtByKdKlaim->msg;
      $result_data_final["dataKdKlaim"] = $resultArray_DtByKdKlaim;
      $result_data_final["dataMnfDtl"] = $resultArray_DtMnf;
			$result_data_final["dataUpah"] = $resultArray_DtUpah;
			
      echo json_encode($result_data_final);				
    	//end get data informasi klaim by kode_klaim from WS --------------------			
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
}	
//end get data list penetapan manfaat ------------------------------------------

//get data list item -----------------------------------------------------------
else if($ls_type == "fjq_ajax_val_getlist_tipepenerima")
{
	$ls_kode_manfaat = $_POST["v_kode_manfaat"];
	$ls_kode_segmen = $_POST["v_kode_segmen"];
	
	//panggil ws -----------------------------------------------------------------
  //get data from ws tipe penerima -------------------------------------------
  $ipDev ="";
  global $wsIp;
  $ipDev  	= $wsIp."/JSPN5040/LovKodeTipePenerima";
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
    'KODE_MANFAAT' => $ls_kode_manfaat,
    'KODE_SEGMEN' => $ls_kode_segmen,
    'PAGE'	=> 1,
    'NROWS'	=> 1000,
    'C_COLNAME'	=> "",
    'C_COLVAL'	=> "",
    'C_COLNAME2'	=> "",
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
  $result_tipepenerima = curl_exec($ch);
  $resultArray_tipepenerima = json_decode(utf8_encode($result_tipepenerima));
  //end get data from ws tipe penerima -----------------------------------------
			
  if ($resultArray_tipepenerima->ret=='0')
  {		
    //return data ke UI --------------------------------------------
    $result_data_final = array();
    $result_data_final["ret"]  = $resultArray_tipepenerima->ret;
    $result_data_final["msg"]  = $resultArray_tipepenerima->msg;
    $result_data_final["dtListTipePenerima"] = $resultArray_tipepenerima;
    
    echo json_encode($result_data_final);		
  }else
  {
    $ls_mess = $resultArray_tipepenerima->msg;
    echo '{
      "ret":-1,
      "success":false,
      "msg":"'.$ls_mess.'"
    }';		
  } 
}
//end get data list item -------------------------------------------------------

//get data list item utk setup ms_lookup ---------------------------------------
else if($ls_type == "fjq_ajax_val_getlist_mslookup")
{
	$ls_tipe = $_POST["v_tipe"];
	
	//panggil ws -----------------------------------------------------------------
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
    'chId'				=>$chId,
    'reqId'				=>$gs_kode_user,
    'TIPE'				=>$ls_tipe,
    'PAGE'				=>1,
    'NROWS'				=>10000,
    'C_COLNAME'		=>"",
    'C_COLVAL'		=>"",
    'C_COLNAME2'	=>"",
    'C_COLVAL2'		=>"",
    'O_COLNAME'		=>"SEQ",
    'O_MODE'			=>"ASC"
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
  //end get data from ws tipe penerima -----------------------------------------
			
  if ($resultArray_dt->ret=='0')
  {		
    //return data ke UI --------------------------------------------
    $result_data_final = array();
    $result_data_final["ret"]  = $resultArray_dt->ret;
    $result_data_final["msg"]  = $resultArray_dt->msg;
    $result_data_final["dtList"] = $resultArray_dt;
    
    echo json_encode($result_data_final);		
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
//end get data list item -------------------------------------------------------

//get periode tahun beasiswa ---------------------------------------------------
else if($ls_type == "fjq_ajax_val_get_tahun_beasiswapp82")
{	
	$ls_kode_klaim 				= $_POST["v_kode_klaim"];
	$ls_kode_manfaat 			= $_POST["v_kode_manfaat"];
	$ln_no_urut 					= $_POST["v_no_urut"];
	$ls_nik_penerima 			= $_POST["v_nik_penerima"];
	$ld_tgl_lahir 				= $_POST["v_tgl_lahir"];
	$ld_tgl_pengajuan 		= $_POST["v_tgl_pengajuan"];
	$ls_kondisi_akhir_penerima 		 = $_POST["v_kondisi_akhir_penerima"];
	$ld_tgl_kondisi_akhir_penerima = $_POST["v_tgl_kondisi_akhir_penerima"];

	//panggil ws -----------------------------------------------------------------
  $ipDev ="";
  global $wsIp;
  $ipDev  	= $wsIp."/JSPN5041/GetThnBeasiswapp82";
  $url    	= $ipDev;
    
  // set HTTP header -----------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

  if ($ln_no_urut=="")
  {
   	 $ln_no_urut = 0;
  }	
				    
  // set POST params -----------------------------------
	$data = array(
    'chId'				 =>$chId,
    'reqId'				 =>$gs_kode_user,
    'KODE_MANFAAT' =>$ls_kode_manfaat,
    'NIK_PENERIMA' =>$ls_nik_penerima,
    'KODE_KLAIM'	 =>$ls_kode_klaim,
    'NO_URUT'			 =>(int)$ln_no_urut,
    'TGL_LAHIR'		 =>$ld_tgl_lahir,
    'TGL_KONDISI_AKHIR_PENERIMA'=>$ld_tgl_kondisi_akhir_penerima,
    'TGL_PENGAJUAN'=>$ld_tgl_pengajuan		
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
  //end get data from ws tipe penerima -----------------------------------------
			
  if ($resultArray_dt->ret=='0')
  {		
    //return data ke UI --------------------------------------------
    $result_data_final = array();
    $result_data_final["ret"]  = $resultArray_dt->ret;
    $result_data_final["msg"]  = $resultArray_dt->msg;
    $result_data_final["dtThn"] = $resultArray_dt->DATA;
    $result_data_final["dtLalu"] = $resultArray_dt->DATA_LALU_DOK_TDKLENGKAP;
		
    echo json_encode($result_data_final);		
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
//end periode tahun beasiswa ---------------------------------------------------

//do hitung manfaat beasiswa pp82 ----------------------------------------------
else if ($ls_type == "fjq_ajax_val_hitungmnf_beasiswapp82")
{
  $ls_kode_klaim 									= $_POST["v_kode_klaim"];
	$ls_kode_manfaat								= $_POST["v_kode_manfaat"];
	$ln_no_urut 										= $_POST["v_no_urut"];
	$ls_nik_penerima 								= $_POST["v_nik_penerima"];
	$ls_tahun 											= $_POST["v_tahun"];
	$ls_beasiswa_jenis							= $_POST["v_jenis"];
	$ls_beasiswa_jenjang_pendidikan	= $_POST["v_jenjang"];

	//panggil ws -----------------------------------------------------------------
  $ipDev ="";
  global $wsIp;
  $ipDev  	= $wsIp."/JSPN5041/HitungMnfBeasiswapp82";
  $url    	= $ipDev;
    
  // set HTTP header -----------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );

  if ($ln_no_urut=="")
  {
   	 $ln_no_urut = 0;
  }	
				    
  // set POST params -----------------------------------
	$data = array(
		'chId'				 =>$chId,
    'reqId'				 =>$gs_kode_user,
    'KODE_MANFAAT' =>$ls_kode_manfaat,
    'NIK_PENERIMA' =>$ls_nik_penerima,
    'KODE_KLAIM'	 =>$ls_kode_klaim,
    'NO_URUT'			 =>(int)$ln_no_urut,
    'TAHUN'	 			 =>$ls_tahun,
    'BEASISWA_JENIS'	 	 =>$ls_beasiswa_jenis,
    'JENJANG_PENDIDIKAN' =>$ls_beasiswa_jenjang_pendidikan
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
  $result_dt = curl_exec($ch);
  $resultArray_dt = json_decode(utf8_encode($result_dt));
  //end get data from ws tipe penerima -----------------------------------------
			
  if ($resultArray_dt->ret=='0')
  {		
    //return data ke UI --------------------------------------------
    $result_data_final = array();
    $result_data_final["ret"]  = $resultArray_dt->ret;
    $result_data_final["msg"]  = $resultArray_dt->msg;
    $result_data_final["dtMnf"] = $resultArray_dt;
    
    echo json_encode($result_data_final);		
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
//end do hitung manfaat beasiswa pp82 ------------------------------------------

//do simpan insert manfaat beasiswa pp82 ---------------------------------------
else if ($ls_type == "SaveMnfBeasiswaPP82")
{
  global $wsIp;
  $gs_kode_user = $_SESSION["USER"];
  
  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
  
	$url = $wsIp.'/JSPN5041/DoSaveMnfBeasiswapp82';
	
  //simpan data penetapan manfaat beasiswa --------------------------------------
  $ls_kode_klaim				  = $_POST["kode_klaim"];
  $ls_kode_manfaat			 	= $_POST["kode_manfaat"];
	$ln_no_urut			 				= $_POST["no_urut"] == "" ? 0 : $_POST["no_urut"];
  $ls_kode_tipe_penerima 	= $_POST["kode_tipe_penerima"];
  $ls_nik_penerima			 	= $_POST["beasiswa_nik_penerima"];
	$ls_nama_penerima			 	= $_POST["nama_penerima"];
	$ld_tgl_lahir					 	= $_POST["tgl_lahir"];
	$ld_tgl_pengajuan			 	= $_POST["beasiswa_tgl_pengajuan"];
	$ls_kondisi_akhir			 	= $_POST["beasiswa_kondisi_akhir"];
	$ls_tgl_kondisi_akhir	 	= $_POST["beasiswa_tgl_kondisi_akhir"];
	$ls_keterangan				 	= $_POST["keterangan"];
	$ln_nom_biaya_disetujui = str_replace(',','',$_POST["nom_biaya_disetujui"]);
	$ln_nom_biaya_disetujui = $ln_nom_biaya_disetujui == "" ? "0" : $ln_nom_biaya_disetujui;
	
	$ls_flag_masih_sekolah = "T";
	$ls_flag_ditunda 			 = "T";
	$ls_flag_dihentikan 	 = "T";
	$ls_flag_terima 			 = "T";
	
	if ($ls_kondisi_akhir == "KA18")
	{
	 	$ls_flag_masih_sekolah = "Y";
	}

	if ($ls_kondisi_akhir == "KA12" || $ls_kondisi_akhir == "KA14" || $ls_kondisi_akhir == "KA11" || $ls_kondisi_akhir == "KA19")
	{
	 	$ls_flag_dihentikan = "Y";
	}
	
	if ($ln_nom_biaya_disetujui > 0)
	{
	 	$ls_flag_terima = "Y"; 
	}

  //insert detil beasiswa ------------------------------------------------------
  // $dataArrDetil_dtlLalu = array(); 
  $dataArrDetil = array(); 
  $ln_panjang_dtlLalu = $_POST['dtl_kounter_dtl_dtlLalu'];
  $ln_panjang = $_POST['dtl_kounter_dtl'];
  $ls_dtl_jenjang_permenaker_no4_2023 = '';
  $ln_no_urut_permenaker_no4_2023 = $ln_no_urut + 1;	

  if($ln_panjang_dtlLalu){
    for($i=0;$i<=$ln_panjang_dtlLalu-1;$i++)
    {		 	           												 		        
      $ls_dtl_tahun_dtlLalu				= $_POST['dtl_tahun_dtlLalu'.$i];
      $ls_dtl_jenis_dtlLalu				= $_POST['dtl_jenis_dtlLalu'.$i];
      $ls_dtl_jenjang_dtlLalu			= $_POST['dtl_jenjang_dtlLalu'.$i];
      $ls_dtl_tingkat_dtlLalu			= $_POST['dtl_tingkat_dtlLalu'.$i];
      $ls_dtl_lembaga_dtlLalu			= $_POST['dtl_lembaga_dtlLalu'.$i];
      $ls_dtl_keterangan_dtlLalu	= $_POST['dtl_keterangan_dtlLalu'.$i];
      $ls_dtl_flag_terima_dtlLalu	= $_POST['dtl_flag_terima_dtlLalu'.$i] == "" ? "T" : $_POST['dtl_flag_terima_dtlLalu'.$i];
      $ln_dtl_nom_manfaat_dtlLalu	= str_replace(',','',$_POST['dtl_nom_manfaat_dtlLalu'.$i]) == "" ? 0 : str_replace(',','',$_POST['dtl_nom_manfaat_dtlLalu'.$i]);
      $ls_dtl_flag_dok_lengkap_dtlLalu	= $_POST['dtl_flag_dok_lengkap_dtlLalu'.$i] == "" ? "T" : $_POST['dtl_flag_dok_lengkap_dtlLalu'.$i];
      
      if ($ls_dtl_tahun_dtlLalu!="")
      {
        $dataArrTahun_dtlLalu = array(
          "TAHUN"				=> $ls_dtl_tahun_dtlLalu,
          "JENIS"				=> $ls_dtl_jenis_dtlLalu,
          "JENJANG"			=> $ls_dtl_jenjang_dtlLalu,
          "FLAG_TERIMA" => $ls_dtl_flag_terima_dtlLalu,
          "TINGKAT"			=> $ls_dtl_tingkat_dtlLalu,
          "LEMBAGA"			=> $ls_dtl_lembaga_dtlLalu,
          "KETERANGAN"	=> $ls_dtl_keterangan_dtlLalu,
          "NOM_MANFAAT"	=> (float)$ln_dtl_nom_manfaat_dtlLalu,
          "FLAG_DOK_LENGKAP"	=> $ls_dtl_flag_dok_lengkap_dtlLalu
        );
        array_push($dataArrDetil, $dataArrTahun_dtlLalu);
      }							
    }
  } 		

  if($ln_panjang){
    for($i=0;$i<=$ln_panjang-1;$i++)
    {		 	           												 		        
      $ls_dtl_tahun				= $_POST['dtl_tahun'.$i];
      $ls_dtl_jenis				= $_POST['dtl_jenis'.$i];
      $ls_dtl_jenjang			= $_POST['dtl_jenjang'.$i];
      $ls_dtl_tingkat			= $_POST['dtl_tingkat'.$i];
      $ls_dtl_lembaga			= $_POST['dtl_lembaga'.$i];
      $ls_dtl_keterangan	= $_POST['dtl_keterangan'.$i];
      $ls_dtl_flag_terima	= $_POST['dtl_flag_terima'.$i] == "" ? "T" : $_POST['dtl_flag_terima'.$i];
      $ln_dtl_nom_manfaat	= str_replace(',','',$_POST['dtl_nom_manfaat'.$i]) == "" ? 0 : str_replace(',','',$_POST['dtl_nom_manfaat'.$i]);
      $ls_dtl_flag_dok_lengkap	= $_POST['dtl_flag_dok_lengkap'.$i] == "" ? "T" : $_POST['dtl_flag_dok_lengkap'.$i];
      
      if ($ls_dtl_tahun!="")
      {
        $dataArrTahun = array(
          "TAHUN"				=> $ls_dtl_tahun,
          "JENIS"				=> $ls_dtl_jenis,
          "JENJANG"			=> $ls_dtl_jenjang,
          "FLAG_TERIMA" => $ls_dtl_flag_terima,
          "TINGKAT"			=> $ls_dtl_tingkat,
          "LEMBAGA"			=> $ls_dtl_lembaga,
          "KETERANGAN"	=> $ls_dtl_keterangan,
          "NOM_MANFAAT"	=> (float)$ln_dtl_nom_manfaat,
          "FLAG_DOK_LENGKAP"	=> $ls_dtl_flag_dok_lengkap
        );
        array_push($dataArrDetil, $dataArrTahun);
        $ls_dtl_jenjang_permenaker_no4_2023 = $ls_dtl_jenjang;
      }							
    }
  } 			
  //end insert detil beasiswa --------------------------------------------------
  
  $data = array(
    "chId"  				 		 => $chId,
    "reqId" 						 => $gs_kode_user,
    "KODE_KLAIM"				 => $ls_kode_klaim,
    "KODE_MANFAAT"			 => $ls_kode_manfaat,
    "NO_URUT"						 => (int)$ln_no_urut,
    "KODE_TIPE_PENERIMA" =>$ls_kode_tipe_penerima,
    "NIK_PENERIMA"			 => $ls_nik_penerima,
    "NAMA_PENERIMA"			 => $ls_nama_penerima,
    "TGLLAHIR_PENERIMA"	 => $ld_tgl_lahir,
    "FLAG_MASIH_SEKOLAH" => $ls_flag_masih_sekolah,
    "KETERANGAN"				 => $ls_keterangan,
    "TGL_PENGAJUAN"			 => $ld_tgl_pengajuan,
    "KONDISI_AKHIR"			 => $ls_kondisi_akhir,
    "TGL_KONDISI_AKHIR"	 => $ls_tgl_kondisi_akhir,
    "FLAG_DITUNDA"			 => $ls_flag_ditunda,
    "FLAG_DIHENTIKAN"		 => $ls_flag_dihentikan,
    "FLAG_DITERIMA"			 => $ls_flag_terima,
    "NOM_BIAYA_DISETUJUI" => (float)$ln_nom_biaya_disetujui,
		"dataArrDetil" => $dataArrDetil	
  );		
			
  // Open connection
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  
  // Execute post
  $resultArray_dtlLalu = json_decode($result_dtlLalu);

  $result = utf8_encode(curl_exec($ch));
  $resultArray = json_decode($result);
  //var_dump($result);
  
  if ($resultArray->ret=='0')
  {
   	 $ls_sukses = "1"; 
  }else
  {
    $ls_sukses = "-1"; 
    $ls_mess = $resultArray->P_MESS;
  }

	//return sukses atau gagal dalam proses penyimpanan data ---------------------
	if ($ls_sukses == "1")
	{
	 	if ($ln_no_urut=="" || $ln_no_urut == "0")
		{
		 	$ln_no_urut_ret = $resultArray->P_NO_URUT_RET;
		}else
		{
		 	$ln_no_urut_ret = $ln_no_urut;	 
		}
        if($ln_panjang){
            $sql = "update pn.pn_klaim_manfaat_detil set 
					 			 beasiswa_jenis 	= 'BEASISWA', 				 
					 			 beasiswa_jenjang_pendidikan = '$ls_dtl_jenjang_permenaker_no4_2023'
					 where kode_klaim = '$ls_kode_klaim' 
                     and kode_manfaat = '2'
					 and no_urut = '$ln_no_urut_ret' ";
		$DB->parse($sql);
		$DB->execute();

        }

		$ls_mess = $sql;
		echo '{"ret":0,"msg":"'.$ls_mess.'","NO_URUT_RET":"'.$ln_no_urut_ret.'"}';
	}else
	{
	 	if ($resultArray->P_SUKSES=="-1" || $resultArray->P_SUKSES=="-2")
		{
		 	$ls_mess = "Data Penetapan Beasiswa gagal disimpan, ".$ls_mess;
		}else
		{
		 	$ls_mess = "Data Penetapan Beasiswa gagal disimpan, ".$resultArray->msg; 
		}
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';	 
	}		 		 
}
//end do simpan insert manfaat beasiswa pp82 -----------------------------------

//new rincian manfaat ----------------------------------------------------------
else if ($ls_type == "fjq_ajax_val_newdatarincianmanfaat")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat	= $_POST["v_kode_manfaat"];
	
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="")
  {
    //get informasi klaim ------------------------------------------------------
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
		 	//get list tipe penerima -----------------------------------------
      $ipDev ="";
      global $wsIp;
      $ipDev  	= $wsIp."/JSPN5040/LovKodeTipePenerima";
      $url    	= $ipDev;
        
      // set HTTP header -----------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );
      
			$ls_kode_segmen = $resultArray_DtByKdKlaim->INFO_KLAIM->KODE_SEGMEN;
			  
      // set POST params -----------------------------------
      $data = array(
        'chId'  => $chId,
        'reqId' => $gs_kode_user,
        'KODE_MANFAAT' => $ls_kode_manfaat,
        'KODE_SEGMEN' => $ls_kode_segmen,
        'PAGE'	=> 1,
        'NROWS'	=> 1000,
        'C_COLNAME'	=> "",
        'C_COLVAL'	=> "",
        'C_COLNAME2'	=> "",
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
      $result_tipepenerima = curl_exec($ch);
      $resultArray_tipepenerima = json_decode(utf8_encode($result_tipepenerima));		
			//end get list tipe penerima -------------------------------------			 
			
			//get list kondisi akhir -----------------------------------------
			$ls_tipe = "BEASKONDAK";
			
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
        'chId'				=>$chId,
        'reqId'				=>$gs_kode_user,
        'TIPE'				=>$ls_tipe,
        'PAGE'				=>1,
        'NROWS'				=>10000,
        'C_COLNAME'		=>"",
        'C_COLVAL'		=>"",
        'C_COLNAME2'	=>"",
        'C_COLVAL2'		=>"",
        'O_COLNAME'		=>"SEQ",
        'O_MODE'			=>"ASC"
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
      $result_dtKondak = curl_exec($ch);
      $resultArray_dtKondak = json_decode(utf8_encode($result_dtKondak));			
			//end get list kondisi akhir -------------------------------------
			
			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtByKdKlaim->ret;
      $result_data_final["msg"]  = $resultArray_DtByKdKlaim->msg;
      $result_data_final["InfoKlaim"] = $resultArray_DtByKdKlaim->INFO_KLAIM;
			$result_data_final["ListTipePenerima"] = $resultArray_tipepenerima->DATA;
			$result_data_final["ListKondak"] = $resultArray_dtKondak->DATA;
			
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
	 	$ls_mess = "Kode Klaim/Kode Manfaat kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end new rincian manfaat ------------------------------------------------------

//end get data rincian manfaat -------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdatarincianmanfaat")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat	= $_POST["v_kode_manfaat"];
	$ln_no_urut				= $_POST["v_no_urut"];
	
  if ($ls_kode_klaim!="" && $ls_kode_manfaat!="" && $ln_no_urut!="")
  {
    //get informasi klaim ------------------------------------------------------
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
		 	//get list tipe penerima -----------------------------------------
      $ipDev ="";
      global $wsIp;
      $ipDev  	= $wsIp."/JSPN5040/LovKodeTipePenerima";
      $url    	= $ipDev;
        
      // set HTTP header -----------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );
      
			$ls_kode_segmen = $resultArray_DtByKdKlaim->INFO_KLAIM->KODE_SEGMEN;
			  
      // set POST params -----------------------------------
      $data = array(
        'chId'  => $chId,
        'reqId' => $gs_kode_user,
        'KODE_MANFAAT' => $ls_kode_manfaat,
        'KODE_SEGMEN' => $ls_kode_segmen,
        'PAGE'	=> 1,
        'NROWS'	=> 1000,
        'C_COLNAME'	=> "",
        'C_COLVAL'	=> "",
        'C_COLNAME2'	=> "",
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
      $result_tipepenerima = curl_exec($ch);
      $resultArray_tipepenerima = json_decode(utf8_encode($result_tipepenerima));		
			//end get list tipe penerima -------------------------------------			 

			//get list kondisi akhir -----------------------------------------
			$ls_tipe = "BEASKONDAK";
			
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
        'chId'				=>$chId,
        'reqId'				=>$gs_kode_user,
        'TIPE'				=>$ls_tipe,
        'PAGE'				=>1,
        'NROWS'				=>10000,
        'C_COLNAME'		=>"",
        'C_COLVAL'		=>"",
        'C_COLNAME2'	=>"",
        'C_COLVAL2'		=>"",
        'O_COLNAME'		=>"SEQ",
        'O_MODE'			=>"ASC"
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
      $result_dtKondak = curl_exec($ch);
      $resultArray_dtKondak = json_decode(utf8_encode($result_dtKondak));			
			//end get list kondisi akhir -------------------------------------
			
			//get rincian manfaat detil --------------------------------------
      $ipDev  = "";
      global $wsIp;
      $ipDev  = $wsIp."/JSPN5040/ViewDataManfaatDetil";
      $url    = $ipDev;
      $gs_kode_user = $_SESSION["USER"];
      
      // set HTTP header -----------------------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );
      
      // set POST params -----------------------------------------------
      $data = array(
        'chId'=>$chId,
        'reqId'=>$gs_kode_user,
        'KODE_KLAIM'=>$ls_kode_klaim,
    		'KODE_MANFAAT'=>$ls_kode_manfaat,
    		'NO_URUT'=>(int)$ln_no_urut
      );	
      // Open connection -----------------------------------------------
      $ch = curl_init();
      
      // Set the url, number of POST vars, POST data -------------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      
      // Execute post --------------------------------------------------
      $result_ViewMnfDtl = curl_exec($ch);
      $resultArray_ViewMnfDtl = json_decode(utf8_encode($result_ViewMnfDtl));		
			//end get rincian manfaat detil ----------------------------------
						
			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtByKdKlaim->ret;
      $result_data_final["msg"]  = $resultArray_DtByKdKlaim->msg;
      $result_data_final["InfoKlaim"] = $resultArray_DtByKdKlaim->INFO_KLAIM;
			$result_data_final["ListTipePenerima"] = $resultArray_tipepenerima->DATA;
			$result_data_final["ListKondak"] = $resultArray_dtKondak->DATA;
			$result_data_final["ViewMnfDtl"] = $resultArray_ViewMnfDtl;
			
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
	 	$ls_mess = "Kode Klaim/Kode Manfaat kosong..!";
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';	
	}
}	
//end get data rincian manfaat -------------------------------------------------

//get data upah tk -------------------------------------------------------------
else if ($ls_type == "fjq_ajax_val_getdataupahtk_allbln")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	
  if ($ls_kode_klaim!="")
  {
    //get informasi klaim ------------------------------------------------------
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
		
		if ($resultArray_DtUpah->ret=='0')
    {
			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtUpah->ret;
      $result_data_final["msg"]  = $resultArray_DtUpah->msg;
      $result_data_final["DtUpah"] = $resultArray_DtUpah;
			
      echo json_encode($result_data_final);			 	  	 
		}else
		{
  	 	$ls_mess = $resultArray_DtUpah->msg;
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
}	
//end get data rincian manfaat -------------------------------------------------

//do hapus rincian manfaat -----------------------------------------------------
else if ($ls_type == "fjq_ajax_val_dodelete_manfaat_rinci")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	$ls_kode_manfaat 	= $_POST["v_kode_manfaat"];
	$ln_no_urut 			= $_POST["v_no_urut"];
	
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];
  
  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
  
	$url = $wsIp.'/JSPN5041/ManfaatDetilDelete';
	
  $data = array(
    "chId"  				 		 => $chId,
    "reqId" 						 => $gs_kode_user,
    "KODE_KLAIM"				 => $ls_kode_klaim,
    "KODE_MANFAAT"			 => $ls_kode_manfaat,
    "NO_URUT"						 => (int)$ln_no_urut
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
  //var_dump($result);
  
  if ($resultArray->ret=='0')
  {
   	 $ls_sukses = "1"; 
  }else
  {
    $ls_sukses = "-1"; 
    $ls_mess = $resultArray->P_MESS;
  }

	//return sukses atau gagal dalam proses delete data --------------------------
	if ($ls_sukses == "1")
	{
		$ls_mess = "Data Penetapan Beasiswa berhasil dihapus, session dilanjutkan..";
		echo '{"ret":0,"msg":"'.$ls_mess.'"}';
	}else
	{
	 	if ($resultArray->P_SUKSES=="-1" || $resultArray->P_SUKSES=="-2")
		{
		 	$ls_mess = "Data Penetapan Beasiswa gagal dihapus, ".$ls_mess;
		}else
		{
		 	$ls_mess = "Data Penetapan Beasiswa gagal dihapus, ".$resultArray->msg; 
		}
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';	 
	}		 		 
}
//end do hapus rincian manfaat -------------------------------------------------

//search data tk amalgamasi jkm ------------------------------------------------
else if ($ls_type == "fjq_ajax_val_searchtk_amalgamasijkm")
{
  $ls_kode_klaim 		 				 = $_POST["v_kode_klaim"];
	$ls_search_nomor_identitas = $_POST["v_search_nomor_identitas"];
	$ls_search_kpj 						 = $_POST["v_search_kpj"];
	$ld_search_tgl_lahir 			 = $_POST["v_search_tgl_lahir"];

  if ($ld_search_tgl_lahir=="")
  {
  	$ld_f_search_tgl_lahir = ""; 
  }else
  {
  	$ld_f_search_tgl_lahir = date("d/m/Y", strtotime(substr($ld_search_tgl_lahir,6,4)."-".substr($ld_search_tgl_lahir,3,2)."-".substr($ld_search_tgl_lahir,0,2)));
  }
													
  if ($ls_kode_klaim!="")
  {
    //get informasi klaim ------------------------------------------------------
    global $wsIp;
    $ipDev  = $wsIp."/JSPN5040/AgendaSearchJPTKAmg";
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
      'NOMOR_IDENTITAS'=>$ls_search_nomor_identitas,
      'KPJ'=>$ls_search_kpj,
      'TGL_LAHIR'=>$ld_f_search_tgl_lahir
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
    $resultDtTkSearch = curl_exec($ch);
    $resultArray_DtTkSearch = json_decode(utf8_encode($resultDtTkSearch));
		
		if ($resultArray_DtTkSearch->ret=='0')
    {
			//return data ke UI ----------------------------------------------
      $result_data_final = array();
      $result_data_final["ret"]  = $resultArray_DtTkSearch->ret;
      $result_data_final["msg"]  = $resultArray_DtTkSearch->msg;
      $result_data_final["DtTkSearch"] = $resultArray_DtTkSearch;
			
      echo json_encode($result_data_final);			 	  	 
		}else
		{
  	 	$ls_mess = $resultArray_DtTkSearch->msg;
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
}	
//end search data tk amalgamasi jkm --------------------------------------------

//do Klik Simpan Insert Amalgamasi JKM -----------------------------------------
else if($ls_type =="DoKlikSimpanAmalgamasiJkm")
{
	$url = "";
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];
	$gs_kantor_aktif = $_SESSION['kdkantorrole'];
	$ls_kode_klaim = $_POST['kode_klaim'];
	
  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
	
	$url = $wsIp.'/JSPN5040/AgendaInputJPAmgSave';
	
	//gen data amalgamasi jp -----------------------------------------------------
	$dataAmgJP = array(); 
  $ln_panjang = $_POST['d_kounter_dtl_amgjp'];
  for($i=0;$i<=$ln_panjang-1;$i++)
  {	
    $ls_d_jp_kpj						= $_POST['d_dtl_kpj'.$i];
    $ls_d_jp_nik						= $_POST['d_dtl_nik'.$i];
    $ls_d_jp_nama_tk				= $_POST['d_dtl_nama_tk'.$i];
    $ls_d_jp_tempat_lahir		= $_POST['d_dtl_tempat_lahir'.$i];
    $ld_d_jp_tgl_lahir			= $_POST['d_dtl_tgl_lahir'.$i];
    $ls_d_jp_kode_tk				= $_POST['d_dtl_kode_tk'.$i];
    $ls_d_status_valid			= $_POST['d_dtl_status_valid'.$i];
    
    if ($ls_d_status_valid=="on" || $ls_d_status_valid=="ON" || $ls_d_status_valid=="Y")
    {
     	$ls_d_status_valid = "Y";
    }else
    {
     	$ls_d_status_valid = "T";	 
    }			
    
    if ($ls_d_jp_kode_tk!="" && $ls_d_status_valid=="Y")
    {
  		if ($ld_d_jp_tgl_lahir=="")
  		{
  		 	$ld_f_d_jp_tgl_lahir = ""; 
  		}else
  		{	
      	$ld_f_d_jp_tgl_lahir = date("Y-m-d H:i:s", strtotime(substr($ld_d_jp_tgl_lahir,6,4)."-".substr($ld_d_jp_tgl_lahir,3,2)."-".substr($ld_d_jp_tgl_lahir,0,2)));
  		}
			
      $dataAmgJP_dtl = array(
            "KODE_TK"		 		 		=> $ls_d_jp_kode_tk,
            "KPJ"		 						=> $ls_d_jp_kpj,
            "NOMOR_IDENTITAS"		=> $ls_d_jp_nik,
            "NAMA_LENGKAP"		 	=> $ls_d_jp_nama_tk,
            "TEMPAT_LAHIR"		 	=> $ls_d_jp_tempat_lahir,
            "TGL_LAHIR"		 			=> $ld_f_d_jp_tgl_lahir,
            "FLAG_KLAIM_JHT"		=> ""
			);
			array_push($dataAmgJP, $dataAmgJP_dtl);			
    }				
  }  
	
  $data = array(
    "chId"  =>$chId,
    "reqId" =>$gs_kode_user,
		"KODE_KLAIM" =>$ls_kode_klaim,
    "dataKlaim" =>$dataAmgJP
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
  //var_dump($result);
	//end gen data amalgamasi jp -------------------------------------------------
	
  if ($resultArray->ret=='0')
  {
		echo '{"ret":0,"msg":"Data Amalgamasi berhasil disimpan, session dilanjutkan.."}';
  }else
  {
	 	if ($resultArray->P_SUKSES=="-1")
		{
		 	$ls_mess = $resultArray->P_MESS; 
		}else
		{
		 	$ls_mess = $resultArray->msg;
			$ls_mess = $ls_mess." </br> ".$resultArray->P_MESS;		
		}
    echo '{
        "ret":-1,
        "success":false,
        "msg":"'.$ls_mess.'"
    }';
  } 		 
}
//end do Klik Simpan Insert Amalgamasi JKM -------------------------------------

//penarikan ulang data upah ----------------------------------------------------
else if ($ls_type == "fjq_ajax_val_penarikanulangdataupah")
{
  $ls_kode_klaim 		= $_POST["v_kode_klaim"];
	
	global $wsIp;
  $gs_kode_user = $_SESSION["USER"];
  
  // set HTTP header
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
  
	$url = $wsIp.'/JSPN5041/GetUlangUpah';
	
  $data = array(
    "chId"  				 		 => $chId,
    "reqId" 						 => $gs_kode_user,
    "KODE_KLAIM"				 => $ls_kode_klaim
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
  //var_dump($result);
  
  if ($resultArray->ret=='0')
  {
   	 $ls_sukses = "1"; 
  }else
  {
    $ls_sukses = "-1"; 
    $ls_mess = $resultArray->P_MESS;
  }

	//return sukses atau gagal dalam proses delete data --------------------------
	if ($ls_sukses == "1")
	{
		$ls_mess = "Penarikan ulang data upah berhasil, session dilanjutkan..";
		echo '{"ret":0,"msg":"'.$ls_mess.'"}';
	}else
	{
	 	if ($resultArray->P_SUKSES=="-1" || $resultArray->P_SUKSES=="-2")
		{
		 	$ls_mess = "Penarikan ulang data upah gagal, ".$ls_mess;
		}else
		{
		 	$ls_mess = "Penarikan ulang data upah gagal, ".$resultArray->msg; 
		}
		echo '{"ret":-1,"msg":"'.$ls_mess.'"}';	 
	}		 		 
}
//end penarikan ulang data upah ------------------------------------------------

?>
