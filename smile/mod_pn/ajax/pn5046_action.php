<?php
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);



set_error_handler("DefaultGlobalErrorHandler");
$btn_submit_penetapan      = $_POST["btn_submit_penetapan"];
$ls_kode_klaim 		  = $_POST["kode_klaim"];





  if($btn_submit_penetapan=="submit_penetapan_tanpa_otentikasi")
  {
	/* Penambahan Post Input Data Integrasi Agenda Klaim dan Sistem Antrian*/
	$ls_token_sisla				= $_POST["token_antrian"];
	$ls_kode_jenis_antrian		= $_POST["kode_jenis_antrian"];
	$ls_kode_status_antrian		= "ST01"; //SETUJU  //$_POST["kode_status_antrian"];
	$ls_kode_sisla				= $_POST["kode_sisla"];
	$ls_kode_kantor_antrian		= $_POST["kode_kantor_antrian"];
	$ls_nomor_antrian			= $_POST["no_antrian"];
	$ld_tgl_ambil_antrian		= $_POST["tgl_ambil_antrian"];
	$ld_tgl_panggil_antrian		= $_POST["tgl_panggil_antrian"];
	$ls_kode_petugas_antrian	= $_POST["kode_petugas_antrian"];
	// $ls_nomor_identitas_antrian	= $_POST["nomor_identitas_antrian"];
	$ls_no_hp_antrian	        = $_POST["no_hp_antrian"];
	$ls_nomor_identitas	        = $_POST["nomor_identitas"]; //nik tk
	$ls_nomor_identitas_penerima	        = $_POST["nomor_identitas_penerima"]; //nik penerima manfaat
	//$ls_kode_tipe_klaim	        = $_POST["kode_tipe_klaim"];
	//$ls_flag_kode_manfaat_beasiswa	        = $_POST["flag_kode_manfaat_beasiswa"];
	$ls_flag_kode_manfaat_beasiswa = 0;
	$ls_flag_jht = 0;
	$ls_flag_jkk = 0;
	$ls_flag_jkm = 0;
	$ls_gl_sesidmenu = "PN5007";



	$sql_manfaat_beasiswa = "select count(*) beasiswa from PN.pn_klaim_manfaat_detil where kode_klaim = '". $ls_kode_klaim ."' and kode_manfaat = 2 and rownum = 1 ";
	$DB->parse($sql_manfaat_beasiswa);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_flag_kode_manfaat_beasiswa = $row['BEASISWA'];	

	$sql_prg_jht = "select count(*) jht from PN.pn_klaim_manfaat_detil where kode_klaim = '". $ls_kode_klaim ."' and kd_prg = 1 and rownum = 1 ";
	$DB->parse($sql_prg_jht);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_flag_jht = $row['JHT'];	

	$sql_prg_jkk = "select count(*) jkk from PN.pn_klaim_manfaat_detil where kode_klaim = '". $ls_kode_klaim ."' and kd_prg = 2 and rownum = 1 ";
	$DB->parse($sql_prg_jkk);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_flag_jkk = $row['JKK'];	

	$sql_prg_jkm = "select count(*) jkm from PN.pn_klaim_manfaat_detil where kode_klaim = '". $ls_kode_klaim ."' and kd_prg = 3 and rownum = 1 ";
	$DB->parse($sql_prg_jkm);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_flag_jkm = $row['JKM'];	



	if ($ls_flag_kode_manfaat_beasiswa > 0){
		$ls_kode_jenis_antrian_detil = 'SA01KBS01';
	}elseif ($ls_flag_jht > 0 ){
		$ls_kode_jenis_antrian_detil = 'SA01JHT01';
	}elseif ($ls_flag_jkk > 0){
		$ls_kode_jenis_antrian_detil = 'SA01JKK01';
	}elseif ($ls_flag_jkm > 0){
		$ls_kode_jenis_antrian_detil = 'SA01JKM01';
	}else{
		$ls_kode_jenis_antrian_detil = 'SA01KBS01';
	}
		


	$ls_kode_antrian		    = $_POST["kode_antrian"];

	
	// $ls_blob = $_COOKIE['blob'];
	// $ls_base64 = $_COOKIE['base64'];

	// var_dump($ls_blob);
	// exit();

	/* Penambahan Terhadap Integrasi Agenda Klaim dan Sistem Antrian*/
	if($ls_kode_antrian == ""){

		$qry = "
		begin 
			pn.p_pn_antrian.x_insert_antrian
			(
				:p_kode_jenis_antrian        ,
				:p_kode_status_antrian       , 
				:p_token_antrian             ,
				:p_kode_sisla                ,
				:p_kode_kantor_antrian       ,
				:p_no_antrian                ,
				:p_tgl_ambil                 ,
				:p_tgl_panggil               ,
				:p_kode_petugas_antrian      ,
				:p_nomor_identitas           ,  
				:p_no_hp                     ,
				:p_email                     ,
				:p_kode_klaim                ,
				:p_kode_jenis_antrian_detil  ,
				:p_keterangan                , 
				:p_kode_pointer_asal         ,      
				:p_user                      , 
				:p_sukses                    , 
				:p_mess                      ,
				:p_kode_antrian               
			); 
		end;
		";
		// $qry = "
		// 		begin 
		// 			pn.p_pn_antrian.x_insert_antrian
		// 			(
		// 				'$ls_kode_jenis_antrian'        ,
		// 				'$ls_kode_status_antrian'       , 
		// 				'$ls_token_sisla'             ,
		// 				'$ls_kode_sisla'                ,
		// 				'$ls_kode_kantor_antrian'       ,
		// 				'$ls_nomor_antrian'                ,
		// 				'$ld_tgl_ambil_antrian'                 ,
		// 				'$ld_tgl_panggil_antrian'               ,
		// 				'$ls_kode_petugas_antrian'      ,
		// 				'$ls_nomor_identitas'           ,  
		// 				'$ls_kode_tipe_klaim'           ,  
		// 				'$ls_flag_kode_manfaat_beasiswa'           ,  
		// 				'$ls_no_hp_antrian'                     ,
		// 				'$ls_email_antrian'                     ,
		// 				'$ls_kode_klaim'                ,
		// 				'$ls_kode_jenis_antrian_detil'  ,
		// 				'$ls_keterangan'                ,  
		// 				'$ls_gl_sesidmenu'                ,      
		// 				'$username'                      , 
		// 				:p_sukses                    , 
		// 				:p_mess                       ,
		// 				:p_kode_antrian
		// 			); 
		// 		end;
		// // 		";
		// 		//echo($qry); die();
			// var_dump($qry);
			// exit();

	

		$proc = $DB->parse($qry);     
		oci_bind_by_name($proc, ":p_token_antrian", $ls_token_sisla, 500);
		oci_bind_by_name($proc, ":p_kode_jenis_antrian", $ls_kode_jenis_antrian,10);  
		oci_bind_by_name($proc, ":p_kode_sisla", $ls_kode_sisla, 100);
		oci_bind_by_name($proc, ":p_no_antrian", $ls_nomor_antrian,30);  
		oci_bind_by_name($proc, ":p_kode_kantor_antrian", $ls_kode_kantor_antrian, 10);
		oci_bind_by_name($proc, ":p_tgl_ambil", $ld_tgl_ambil_antrian,50);  
		oci_bind_by_name($proc, ":p_tgl_panggil", $ld_tgl_panggil_antrian, 50);
		oci_bind_by_name($proc, ":p_kode_petugas_antrian", $ls_kode_petugas_antrian,30); 
		oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas,20);  
		oci_bind_by_name($proc, ":p_no_hp", $ls_no_hp_antrian, 20);
		oci_bind_by_name($proc, ":p_email", $ls_email_antrian,50);  
		oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 30);
		oci_bind_by_name($proc, ":p_kode_status_antrian", $ls_kode_status_antrian, 10);
		oci_bind_by_name($proc, ":p_kode_jenis_antrian_detil", $ls_kode_jenis_antrian_detil,10);  
		oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 1000);
		oci_bind_by_name($proc, ":p_kode_pointer_asal", $ls_gl_sesidmenu, 100);
		oci_bind_by_name($proc, ":p_user", $username,1000);         
		oci_bind_by_name($proc, ":p_sukses", $p_sukses, 2);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
		oci_bind_by_name($proc, ":p_kode_antrian", $p_kode_antrian, 30);
		$DB->execute();				
		$ls_sukses_antrian = $p_sukses;	
		$ls_mess_antrian = $p_mess;	
		$ls_kode_antrian = $p_kode_antrian;	
	}
	

  	//submit data penetapan ----------------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_PENETAPAN('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ls_sukses = $p_sukses;
    $ls_mess = $p_mess;		
  	
  	if ($ls_sukses =="1")
  	{			
    	$msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
    }else
  	{
  	  $ls_error = "1";
  		$msg = $ls_mess; 
  	}
    $msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
  	

	if ($ls_sukses =="1" || $ls_sukses_antrian == "1")
  	{		
		$jsondata["ret"] = "1";
		$jsondata["dataid"] = $ls_kode_klaim;
		$jsondata["kode_klaim"] = $ls_kode_klaim;
		$jsondata["mid"] = $mid;
		$jsondata["kode_antrian"] = $ls_kode_antrian;
		$jsondata["msg"] = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
		echo json_encode($jsondata);
    }else
  	{
		$jsondata["ret"] = "-1";
		$jsondata["dataid"] = $ls_kode_klaim;
		$jsondata["kode_klaim"] = $ls_kode_klaim;
		$jsondata["mid"] = $mid;
		$jsondata["kode_antrian"] = $ls_kode_antrian;
		$jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
		echo json_encode($jsondata);
  	}
  }
?>
	