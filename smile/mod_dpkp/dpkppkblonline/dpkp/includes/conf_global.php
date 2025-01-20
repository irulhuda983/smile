<?
extract($_REQUEST);
extract($_SESSION);
/*-------------------------------------------------------------------------------------------------
File: conf_global.php

Deskripsi:
----------------------------------------------------------------------------------------------------
File ini dipergunakan untuk konfigurasi koneksi php ke database oracle
dan menyimpan informasi general korporate

Author:
----------------------------------------------------------------------------------------------------
Kade Budiarta

---------------------------------------------------------------------------------------------------*/

  	include "fungsi.php";
  	$ipReportServer='http://172.28.108.151:9002';
	$COMPANY   = "Jamsostek";
	// $HTTP_HOST = "dpkppkblonline.bpjsketenagakerjaan.go.id/dpkp";
	$HTTP_HOST = "http://172.28.108.49:5267/smile/mod_dpkp/dpkppkblonline/dpkp";
	//$HTTP_HOST = "172.28.201.68/dpkppkblonline/dpkp";

	//database connection
		
	$ls_uid  = "JH@GAOUBXD";
	$ls_pwd  = "KE_E?>>#";
	$ls_sdb	 = "vka}k";
	
	$gs_DBUser  	= f_endec($ls_uid);
	$gs_DBPass  	= f_endec($ls_pwd);
	$gs_DBName	 	= f_endec($ls_sdb);
	
	$gs_DBUser  	= f_endec($ls_uid);
	$gs_DBPass  	= f_endec($ls_pwd);
	$gs_DBName	 	= f_endec($ls_sdb);
	

	$gs_DBUser  	= "admin_dpkp";
	$gs_DBPass  	= "logintoadcapture";
	$gs_DBName	 	= "172.28.108.180:1521/DBDEVELOP";

	//SKP Online
	$dpkp_DBUser  	= "admin_dpkp";
	$dpkp_DBPass  	= "logintoadcapture";
	$dpkp_DBName	= "172.28.108.180:1521/DBDEVELOP";

	/*
	echo "User ".$ls_uid."<br>";
	echo "Passwd ".$ls_pwd."<br>";
	
	echo "User endec ".$gs_DBUser."<br>";
	echo "Passwd endec ".$gs_DBPass."<br>";
	*/
	
	//page role
	$rows_per_page = 10; // untuk paging
	$url 					 = $filename; //lokasi file paging
	$gs_mandatory_note = "<b>Note</b> : Field yang ditandai dengan * wajib diisi";
?>

