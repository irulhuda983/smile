<?
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

	$COMPANY   = "Jamsostek";
	// $HTTP_HOST = "dpkppkblonline.bpjsketenagakerjaan.go.id/";
	//$HTTP_HOST = "172.28.200.11:1802/smile/";
	$HTTP_HOST = "172.28.108.49:5267/smile";

	//database connection
		
	$ls_uid  = "JH@GAOUBXD";
	$ls_pwd  = "KE_E?>>#";
	$ls_sdb	 = "vka}k";
	
	$gs_DBUser  	= f_endec($ls_uid);
	$gs_DBPass  	= f_endec($ls_pwd);
	$gs_DBName	= f_endec($ls_sdb);
	
	$gs_DBUser  	= f_endec($ls_uid);
	$gs_DBPass  	= f_endec($ls_pwd);
	$gs_DBName	= f_endec($ls_sdb);
	
	$gs_DBUser  	= "admin_dpkp";
	$gs_DBPass  	= "logintoadcapture";
	$gs_DBName		= "172.28.108.180:1521/DBDEVELOP";

	//SKP Online
	$dpkp_DBUser  	= "admin_dpkp";
	$dpkp_DBPass  	= "logintoadcapture";
	$dpkp_DBName	= "172.28.108.180:1521/DBDEVELOP";
	
	
	
	//page role
	$rows_per_page = 10; // untuk paging
	$url 					 = $filename; //lokasi file paging
	$gs_mandatory_note = "<b>Note</b> : Field yang ditandai dengan * wajib diisi";
?>
