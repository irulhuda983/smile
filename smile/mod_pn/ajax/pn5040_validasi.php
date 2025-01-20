<?php
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

if(isset($_GET['getClientId']))
{  
	// validasi tipe klaim -------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kode_tipe_klaim")
	{
		$ls_kode_tipe_klaim  = strtoupper($_GET['c_kode_tipe_klaim']);
		$ls_kode_segmen 		 = strtoupper($_GET['c_kode_segmen']);
		$ls_kode_sebab_klaim = strtoupper($_GET['c_kode_sebab_klaim']);
		$ls_kode_tk 				 = strtoupper($_GET['c_kode_tk']);
		$ls_kode_klaim			 = strtoupper($_GET['c_kode_klaim']);
		$ld_tgl_kejadian		 = strtoupper($_GET['c_tgl_kejadian']);
					
		//----------- 1. CEK APAKAH TIPE KLAIM VALID ATAU TIDAK --------------------
		$sql = 	"select count(*) as v_cnt from sijstk.pn_kode_tipe_klaim ".
				 		"where kode_tipe_klaim = :p_kode_tipe_klaim ".
						"and nvl(status_nonaktif,'T')=:p_status_nonaktif ";
		$proc = $DB->parse($sql);
		$param_bv = [
		   ':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
		   ':p_status_nonaktif' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();
		$ln_cnt = $row["V_CNT"];
		
		if ($ln_cnt==0)
		{
		 	echo "formObj.st_errval1.value = '1';\n";					
		}else
		{
		 	echo "formObj.st_errval1.value = '0';\n";						
			//----------------------- AMBIL NAMA TIPE KLAIM --------------------------
  		$sql = 	"select nama_tipe_klaim, substr(kode_tipe_klaim,1,3) jenis_klaim, to_char(sysdate,'dd/mm/yyyy') tgl_sysdate ".
					 		"from sijstk.pn_kode_tipe_klaim ".
  				 		"where kode_tipe_klaim = :p_kode_tipe_klaim ";
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':p_kode_tipe_klaim', $ls_kode_tipe_klaim);
  		$DB->execute();
  		$row = $DB->nextrow();
  		$ls_nama_tipe_klaim = $row["NAMA_TIPE_KLAIM"];
			$ls_jenis_klaim = $row["JENIS_KLAIM"];
			$ld_tgl_sysdate = $row["TGL_SYSDATE"];
			
			echo "formObj.jenis_klaim.value  		 = '".$ls_jenis_klaim."';\n";	
			echo "formObj.nama_tipe_klaim.value  = '".$ls_nama_tipe_klaim."';\n";	
			echo "formObj.kode_sebab_klaim.value = '';\n";	
			echo "formObj.nama_sebab_klaim.value = '';\n";
			
			if (($ls_jenis_klaim=="JKK"))
			{
				echo "formObj.status_klaim.value = 'AGENDA_TAHAP_I';\n";
			}else
			{
				echo "formObj.status_klaim.value = 'AGENDA';\n";
			}
			
			$ls_flag_meninggal="T";
			//ambil sebab klaim jika hanya ada 1 -----------------------------------
  		$sql = 	"select count(*) as v_cnt from sijstk.pn_kode_sebab_klaim a ".
              "where kode_tipe_klaim = :p_kode_tipe_klaim ".
							"and nvl(to_char(tgl_berlaku,'yyyymmdd'),'19000101') <= to_char(sysdate,'yyyymmdd') ".
							"and nvl(to_char(tgl_nonaktif,'yyyymmdd'),'30001231') > to_char(sysdate,'yyyymmdd') ".
              "and exists ".
              "( ".
              "    select null from sijstk.pn_kode_sebab_segmen ".
              "    where kode_sebab_klaim = a.kode_sebab_klaim ".
              "    and kode_segmen = :p_kode_segmen ".
							"		 and nvl(to_char(tgl_nonaktif,'yyyymmdd'),:p_tgl) > to_char(sysdate,'yyyymmdd') ".
              ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
			':p_kode_segmen' => $ls_kode_segmen,
			':p_tgl' => '30001231'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
  		$DB->execute();
  		$row = $DB->nextrow();
  		$ln_cnt = $row["V_CNT"];
  			
  		if ($ln_cnt=="1")
  		{
    		$sql = 	"select nvl(flag_meninggal,'T') flag_meninggal, kode_sebab_klaim, nama_sebab_klaim, keyword, nvl(flag_agenda_12,'T') flag_agenda_12 ".
						 		"from sijstk.pn_kode_sebab_klaim a ".
                "where kode_tipe_klaim = :p_kode_tipe_klaim ".
								"and nvl(to_char(tgl_berlaku,'yyyymmdd'),:p_tgl_1) <= to_char(sysdate,'yyyymmdd') ".
								"and nvl(to_char(tgl_nonaktif,'yyyymmdd'),:p_tgl_2) > to_char(sysdate,'yyyymmdd') ".
                "and exists ".
                "( ".
                "    select null from sijstk.pn_kode_sebab_segmen ".
                "    where kode_sebab_klaim = a.kode_sebab_klaim ".
                "    and kode_segmen = :p_kode_segmen ".
								"		 and nvl(to_char(tgl_nonaktif,'yyyymmdd'),:p_tgl_2) > to_char(sysdate,'yyyymmdd') ".
                ") ";
			$proc = $DB->parse($sql);
			$param_bv = [
				':p_tgl_1' => '19000101',
				':p_tgl_2' => '30001231',
				':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
				':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
				':p_kode_segmen' => $ls_kode_segmen
			];
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ls_kode_sebab_klaim 		= $row["KODE_SEBAB_KLAIM"];
				$ls_nama_sebab_klaim 		= $row["NAMA_SEBAB_KLAIM"];
				$ls_keyword_sebab_klaim = $row["KEYWORD"];
				$ls_flag_meninggal 			= $row["FLAG_MENINGGAL"];
				$ls_flag_agenda_12			= $row["FLAG_AGENDA_12"];
					
				echo "formObj.kode_sebab_klaim.value = '".$ls_kode_sebab_klaim."';\n";	
				echo "formObj.nama_sebab_klaim.value = '".$ls_nama_sebab_klaim."';\n";
				echo "formObj.keyword_sebab_klaim.value = '".$ls_keyword_sebab_klaim."';\n";
				
  			//update 23/01/2019, tambahan utk TKI ----------------------------------
  			if (($ls_jenis_klaim=="JKK") && $ls_flag_agenda_12=="Y")
  			{
  				echo "formObj.status_klaim.value = 'AGENDA_TAHAP_I';\n";
  			}else
  			{
  				echo "formObj.status_klaim.value = 'AGENDA';\n";
  			}							
    	}//end if if ($ln_cnt==1)			
  				
			//tgl kejadian hanya bisa diisi hanya utk klaim JKK/Meninggal ------------
			if ($ls_jenis_klaim == "JKK" || $ls_jenis_klaim == "JKM" || $ls_jenis_klaim == "JHM" || $ls_flag_meninggal=="Y")
			{
				echo "window.document.getElementById('span_tgl_kejadian').style.display='block';";
				echo "f_ajax_val_tglkejadian();";
			}else
			{
			 	echo "window.document.getElementById('span_tgl_kejadian').style.display='none';";	
				//set dg sysdate ---------------------------
				echo "formObj.tgl_kejadian.value = '".$ld_tgl_sysdate."';\n";		 
			}
			//end tgl kejadian hanya bisa diisi hanya utk klaim JKK/Meninggal ------											
		}//end if if ($ln_cnt==0)
		
		//reset data tk ------------------------------------------------------------
    echo "formObj.kpj.value 						= '';\n";	
		echo "formObj.kode_tk.value 				= '';\n";	
    echo "formObj.nama_tk.value 				= '';\n";	
    echo "formObj.nomor_identitas.value = '';\n";	
    echo "formObj.jenis_identitas.value = '';\n";	
    echo "formObj.kode_kantor_tk.value 	= '';\n";	
    echo "formObj.kode_perusahaan.value = '';\n";
    echo "formObj.npp.value 						= '';\n";
    echo "formObj.nama_perusahaan.value = '';\n";	
    echo "formObj.kode_divisi.value 		= '';\n";
    echo "formObj.nama_divisi.value 		= '';\n";
		
		echo "formObj.no_proyek.value 		= '';\n";
		echo "formObj.nama_proyek.value 		= '';\n";
		echo "formObj.kode_proyek.value 		= '';\n";
		echo "curr_kpj = '';\n";	
	}	
	// end validasi tipe klaim ---------------------------------------------------
		
	// validasi sebab klaim ------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kode_sebab_klaim")
	{
		$ls_kode_tipe_klaim  = strtoupper($_GET['c_kode_tipe_klaim']);
		$ls_kode_segmen 		 = strtoupper($_GET['c_kode_segmen']);
		$ls_kode_sebab_klaim = strtoupper($_GET['c_kode_sebab_klaim']);
		$ld_tgl_kejadian 		 = strtoupper($_GET['c_tgl_kejadian']);
		$ls_kode_tk 				 = strtoupper($_GET['c_kode_tk']);
		$ls_kode_klaim			 = strtoupper($_GET['c_kode_klaim']);
		
		//1. cek apakah sebab klaim valid atau tidak ------------------------
		$sql = 	"select count(*) as v_cnt from sijstk.pn_kode_sebab_klaim a ".
            "where kode_sebab_klaim = :p_kode_sebab_klaim ".
						"and kode_tipe_klaim = :p_kode_tipe_klaim ".
						"and nvl(to_char(tgl_berlaku,'yyyymmdd'),:p_tgl_berlaku) <= to_char(nvl(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),sysdate),'yyyymmdd') ".
						"and nvl(to_char(tgl_nonaktif,'yyyymmdd'),:p_tgl_nonaktif) > to_char(nvl(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),sysdate),'yyyymmdd') ".
            "and exists ".
            "( ".
            "    select null from sijstk.pn_kode_sebab_segmen ".
            "    where kode_sebab_klaim = a.kode_sebab_klaim ".
            "    and kode_segmen = :p_kode_segmen ".
						"		 and nvl(to_char(tgl_nonaktif,'yyyymmdd'),:p_tgl_nonaktif) > to_char(nvl(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),sysdate),'yyyymmdd') ".
            ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_sebab_klaim' => $ls_kode_sebab_klaim,
			':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
			':p_tgl_kejadian' => $ld_tgl_kejadian,
			':p_kode_segmen' => $ls_kode_segmen,
			':p_tgl_kejadian' => $ld_tgl_kejadian,
			':p_tgl_berlaku' => '19000101',
			':p_tgl_nonaktif' => '30001231'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();
		$ln_cnt = $row["V_CNT"];
		
		if ($ln_cnt==0)
		{
		 	echo "formObj.st_errval2.value = '1';\n";		
	 		//echo "fl_js_get_lov_by_kode_sebab_klaim();"; 
		}else
		{
		 	echo "formObj.st_errval2.value = '0';\n";
			//ambil nama sebab klaim --------------------------------------------------
  		$sql = 	"select nvl(flag_meninggal,'T') flag_meninggal, nama_sebab_klaim, substr(:p_kode_tipe_klaim,1,3) jenis_klaim, ".
					 		"				nvl(flag_partial,'T') flag_partial, keyword, to_char(sysdate, 'dd/mm/yyyy') tgl_sysdate, ".
							"				nvl(flag_agenda_12,'T') flag_agenda_12 ".
					 		"from sijstk.pn_kode_sebab_klaim ".
  				 		"where kode_sebab_klaim = :p_kode_sebab_klaim ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_tipe_klaim' => $ls_kode_tipe_klaim,
			':p_kode_sebab_klaim' => $ls_kode_sebab_klaim
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
  		$DB->execute();
  		$row = $DB->nextrow();
  		$ls_nama_sebab_klaim 		= $row["NAMA_SEBAB_KLAIM"];
			$ls_keyword_sebab_klaim = $row["KEYWORD"];
			$ls_flag_meninggal 			= $row["FLAG_MENINGGAL"];
			$ls_jenis_klaim 				= $row["JENIS_KLAIM"];
			$ld_tgl_sysdate 				= $row["TGL_SYSDATE"];
			$ls_flag_partial 				= $row["FLAG_PARTIAL"];
			$ls_flag_agenda_12			= $row["FLAG_AGENDA_12"];
			
			echo "formObj.nama_sebab_klaim.value 		= '".$ls_nama_sebab_klaim."';\n";
			echo "formObj.keyword_sebab_klaim.value = '".$ls_keyword_sebab_klaim."';\n";	
			echo "formObj.flag_meninggal.value 			= '".$ls_flag_meninggal."';\n";	
			
			//tgl kejadian hanya bisa diisi hanya utk klaim JKK/Meninggal ------------
			if ($ls_jenis_klaim == "JKK" || $ls_jenis_klaim == "JKM" || $ls_jenis_klaim == "JHM" || $ls_flag_meninggal=="Y")
			{
				echo "window.document.getElementById('span_tgl_kejadian').style.display='block';";
				echo "f_ajax_val_tglkejadian();";
			}else
			{
			 	echo "window.document.getElementById('span_tgl_kejadian').style.display='none';";	
				//set dg sysdate ---------------------------
				echo "formObj.tgl_kejadian.value = '".$ld_tgl_sysdate."';\n";		 
			}	
			
			//update 23/01/2019, tambahan utk TKI ------------------------------------
			if (($ls_jenis_klaim=="JKK") && $ls_flag_agenda_12=="Y")
			{
				echo "formObj.status_klaim.value = 'AGENDA_TAHAP_I';\n";
			}else
			{
				echo "formObj.status_klaim.value = 'AGENDA';\n";
			}						 	 						
		}//ln_cnt=0
		
		//reset data tk ------------------------------------------------------------
    echo "formObj.kpj.value 						= '';\n";	
		echo "formObj.kode_tk.value 				= '';\n";	
    echo "formObj.nama_tk.value 				= '';\n";	
    echo "formObj.nomor_identitas.value = '';\n";	
    echo "formObj.jenis_identitas.value = '';\n";	
    echo "formObj.kode_kantor_tk.value 	= '';\n";	
    echo "formObj.kode_perusahaan.value = '';\n";
    echo "formObj.npp.value 						= '';\n";
    echo "formObj.nama_perusahaan.value = '';\n";	
    echo "formObj.kode_divisi.value 		= '';\n";
    echo "formObj.nama_divisi.value 		= '';\n";
		
		echo "formObj.no_proyek.value 		= '';\n";
		echo "formObj.nama_proyek.value 		= '';\n";
		echo "formObj.kode_proyek.value 		= '';\n";
		echo "curr_kpj = '';\n";		
	}	
	// end validasi sebab klaim --------------------------------------------------	
	
	// validasi tgl lapor --------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_tgl_lapor")
	{
		$ld_tgl_lapor 		 	 = strtoupper($_GET['c_tgl_lapor']);
		$ld_tgl_kejadian 	 	 = strtoupper($_GET['c_tgl_kejadian']);
		$ld_tgl_klaim 	 	 	 = strtoupper($_GET['c_tgl_klaim']);
		$ls_kode_tk 	 	 	 	 = strtoupper($_GET['c_kode_tk']);
		$ls_kode_tipe_klaim  = strtoupper($_GET['c_kode_tipe_klaim']);
		
    $sql = 	"select to_char(to_date(:p_tgl_lapor,'dd/mm/yyyy'),'yyyymmdd') tgl_lapor, ".
    	 			"		to_char(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),'yyyymmdd') tgl_kejadian, ".
    				"		to_char(to_date(:p_tgl_klaim,'dd/mm/yyyy'),'yyyymmdd') tgl_klaim, ".
						"		to_char(sysdate,'yyyymmdd') tgl_sysdate, ".
						"		to_char(sysdate,'dd/mm/yyyy') tgl_sysdate_ddmmyyyy, ".
						"		substr(:p_kode_tipe_klaim,1,3) jenis_klaim ".
    				"from dual ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_tgl_lapor' => $ld_tgl_lapor,
		':p_tgl_kejadian' => $ld_tgl_kejadian,
		':p_tgl_klaim' => $ld_tgl_klaim,
		':p_kode_tipe_klaim' => $ls_kode_tipe_klaim
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_lapor  	 = $row["TGL_LAPOR"];
    $ls_tgl_kejadian = $row["TGL_KEJADIAN"];
    $ls_tgl_klaim  	 = $row["TGL_KLAIM"];
		$ls_jenis_klaim  = $row["JENIS_KLAIM"];
		$ls_tgl_sysdate	 = $row["TGL_SYSDATE"];
		$ls_tgl_sysdate_ddmmyyyy = $row["TGL_SYSDATE_DDMMYYYY"];
		
    if ($ls_tgl_lapor>$ls_tgl_klaim)
    {
     	echo "formObj.st_errval6.value = '2';\n"; 			 
    }else
    {
      echo "formObj.st_errval6.value = '0';\n";
			//jika klaim jht/jpn maka set tgl kejadian = tgl_lapor
			//jika klaim jkk/jhm maka tgl lapor tidak boleh lebih kecil dari tgl_kejadian
      if ($ls_jenis_klaim == "JHT" || $ls_jenis_klaim == "JPN")
			{
			 	 echo "formObj.tgl_kejadian.value = '".$ld_tgl_klaim."';\n"; 	 
			}else
			{
  			if ($ls_tgl_kejadian!="" && $ls_tgl_lapor<$ls_tgl_kejadian)
  			{
          echo "formObj.st_errval6.value = '1';\n";		 			
  			}	
				//update 28/12/2019, tgl lapor tidak boleh lebih besar dari sysdate ----
				if ($ls_tgl_lapor!="" && $ls_tgl_lapor>$ls_tgl_sysdate)
  			{
          echo "formObj.tgl_lapor.value = '$ls_tgl_sysdate_ddmmyyyy';\n";
					$ls_mess = "Tgl Lapor ".$ld_tgl_lapor." lebih besar dari hari ini, otomatis diset ke hari ini..!";
					echo "alert('$ls_mess');";		 			
  			}		
			} 
    }//end if if ($ls_tgl_lapor>$ls_tgl_klaim)		  		 
	}	
	// end validasi tgl lapor ----------------------------------------------------	
	
	// validasi tgl kejadian jkk/jkm/jhm  ----------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_tgl_kejadian")
	{
		$ls_kode_perusahaan  = strtoupper($_GET['c_kode_perusahaan']);
		$ls_kode_segmen 		 = strtoupper($_GET['c_kode_segmen']);
		$ls_kode_divisi 		 = strtoupper($_GET['c_kode_divisi']);
		$ls_kode_tk 				 = strtoupper($_GET['c_kode_tk']);
		$ld_tgl_kejadian 		 = strtoupper($_GET['c_tgl_kejadian']);
		$ld_tgl_lapor 		 	 = strtoupper($_GET['c_tgl_lapor']);
		$ld_tgl_klaim 	 	 	 = strtoupper($_GET['c_tgl_klaim']);
		$ls_kode_tipe_klaim  = strtoupper($_GET['c_kode_tipe_klaim']);
		$ls_kode_sebab_klaim = strtoupper($_GET['c_kode_sebab_klaim']);
		$ls_kode_klaim 			 = strtoupper($_GET['c_kode_klaim']);
				
    $sql = 	"select to_char(to_date(:p_tgl_lapor,'dd/mm/yyyy'),'yyyymmdd') tgl_lapor, ".
    	 			"		to_char(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),'yyyymmdd') tgl_kejadian, ".
    				"		to_char(to_date(:p_tgl_klaim,'dd/mm/yyyy'),'yyyymmdd') tgl_klaim, ".
						"		substr(:p_kode_tipe_klaim,1,3) jenis_klaim ". 
    				"from dual ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_tgl_lapor' => $ld_tgl_lapor,
		':p_tgl_kejadian' => $ld_tgl_kejadian,
		':p_tgl_klaim' => $ld_tgl_klaim,
		':p_kode_tipe_klaim' => $ls_kode_tipe_klaim
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_lapor  = $row["TGL_LAPOR"];
    $ls_tgl_kejadian = $row["TGL_KEJADIAN"];
    $ls_tgl_klaim  = $row["TGL_KLAIM"];
		$ls_jenis_klaim = $row["JENIS_KLAIM"];
		
		if ($ls_tgl_kejadian>$ls_tgl_lapor)
		{
		 	echo "formObj.st_errval7.value = '1';\n";			 
		}else
		{
  		echo "formObj.st_errval6.value = '0';\n";
			if ($ls_tgl_kejadian>$ls_tgl_klaim)
  		{
  		 	echo "formObj.st_errval7.value = '2';\n"; 			 
  		}else
  		{
  		 	echo "formObj.st_errval7.value = '0';\n";
				
				//cek apakah sebab klaim sudah aktif pada saat tgl_kejadian ------------
				if ($ls_kode_sebab_klaim!="")
				{
  				$sql = 	"select count(*) as v_cnt from sijstk.pn_kode_sebab_klaim a ".
                  "where kode_sebab_klaim = :p_kode_sebab_klaim ".
      						"and nvl(to_char(tgl_berlaku,'yyyymmdd'),:p_tgl_berlaku) <= :p_tgl_kejadian ".
  								"and nvl(to_char(tgl_nonaktif,'yyyymmdd'),:p_tgl_nonaktif) > :p_tgl_kejadian ";
			$proc = $DB->parse($sql);
			$param_bv = [
				':p_tgl_berlaku' => '19000101',
				':p_tgl_nonaktif' => '30001231',
				':p_kode_sebab_klaim' => $ls_kode_sebab_klaim,
				':p_tgl_kejadian' => $ls_tgl_kejadian
			];
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_cnt = $row["V_CNT"];
  				if ($ln_cnt==""){$ln_cnt=0;}
  				
  				if ($ln_cnt=="0")
  				{
    				$sql = 	"select nama_sebab_klaim from sijstk.pn_kode_sebab_klaim a ".
                    "where kode_sebab_klaim = :p_kode_sebab_klaim ";
				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_kode_sebab_klaim', $ls_kode_sebab_klaim);
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ls_nama_sebab_klaim = $row["NAMA_SEBAB_KLAIM"];
  								 	
  					echo "formObj.kode_sebab_klaim.value = '';\n";
						echo "curr_kode_sebab_klaim = '';\n";
  					echo "alert('Sebab Klaim $ls_nama_sebab_klaim belum aktif pada saat tgl kejadian $ld_tgl_kejadian, sebab klaim otomatis dikosongkan..!');";  	 
  				}else
					{
					 	echo "formObj.st_errval2.value = '0';\n";	 
					}
				}//end cek sebab klaim -------------------------------------------------
			}	
		}		  		 
	}	
	// end validasi tgl kejadian -------------------------------------------------	

	// validasi tgl kondisi akhir agenda jkk tahap 2 update 06042022
	// validasi tgl lapor --------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_tgl_kondisi_akhir_jkk2")
	{
		$ld_tgl_kondisi_terakhir = strtoupper($_GET['c_tgl_kondisi_terkahir']);
		$ld_tgl_kejadian 	 	 = strtoupper($_GET['c_tgl_kejadian']); 
		
		$sql = 	"select to_char(to_date(:p_tgl_kondisi_terakhir,'dd/mm/yyyy'),'yyyymmdd') tgl_kondisi_terakhir, ".
						"		to_char(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),'yyyymmdd') tgl_kejadian, ". 
							"		to_char(sysdate,'yyyymmdd') tgl_sysdate, ".
							"		to_char(sysdate,'dd/mm/yyyy') tgl_sysdate_ddmmyyyy, ". 
							"		TO_CHAR (TO_DATE ('01/09/2023', 'dd/mm/yyyy'), 'yyyymmdd') tgl_batas_kondisi_akhir ". 
						"from dual ";
		// var_dump($sql);die();

		$proc = $DB->parse($sql);
		$param_bv = [
			':p_tgl_kondisi_terakhir' => $ld_tgl_kondisi_terakhir,
			':p_tgl_kejadian' => $ld_tgl_kejadian
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();
			$ls_tgl_kondisi_terakhir  	= $row["TGL_KONDISI_TERAKHIR"];
			$ls_tgl_kejadian 			= $row["TGL_KEJADIAN"]; 
			$ls_tgl_sysdate	 			= $row["TGL_SYSDATE"];
			$ls_tgl_sysdate_ddmmyyyy 	= $row["TGL_SYSDATE_DDMMYYYY"];
			
		if ($ls_tgl_kondisi_terakhir<$ls_tgl_kejadian)
		{
			//echo "formObj.st_errval6_jkk2.value = '2';\n"; 
			echo "formObj.jkk2_tgl_kondisi_terakhir.value = '';\n";
			$ls_mess = "Tanggal Kondisi Akhir TK  ".$ld_tgl_kondisi_terakhir." lebih kecil dari Tanggal Kejadian ".$ld_tgl_kejadian."...!!!";
			echo "alert('$ls_mess');";			 
		}else
		{
			//echo "formObj.st_errval6_jkk2.value = '0';\n"; 
					
			//update 28/12/2019, tgl lapor tidak boleh lebih besar dari sysdate ----
			if ($ls_tgl_kondisi_terakhir!="" && $ls_tgl_kondisi_terakhir>$ls_tgl_sysdate)
			{
				echo "formObj.jkk2_tgl_kondisi_terakhir.value = '$ls_tgl_sysdate_ddmmyyyy';\n";
				$ls_mess = "Tanggal Kondisi Akhir TK  ".$ld_tgl_kondisi_terakhir." lebih besar dari hari ini, otomatis diset ke hari ini..!";
				echo "alert('$ls_mess');";		 			
			}		
				
		}//end if if ($ls_tgl_lapor>$ls_tgl_klaim)		  		 
	}	
	// end validasi tgl kondisi akhir ----------------------------------------------------
	
	// validasi kpj --------------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kpj")
	{
		$ls_kpj  						 = strtoupper($_GET['c_kpj']);
		$ls_kode_segmen 		 = strtoupper($_GET['c_kode_segmen']);
		$ls_kode_tk 				 = strtoupper($_GET['c_kode_tk']);
		$ls_kode_perusahaan  = strtoupper($_GET['c_kode_perusahaan']); 
		$ls_kode_divisi 		 = strtoupper($_GET['c_kode_divisi']);
		
		$ls_kode_tipe_klaim  = strtoupper($_GET['c_kode_tipe_klaim']);
		$ls_kode_sebab_klaim = strtoupper($_GET['c_kode_sebab_klaim']);
		$ld_tgl_kejadian 		 = strtoupper($_GET['c_tgl_kejadian']);
		$ls_kode_klaim 			 = strtoupper($_GET['c_kode_klaim']);

		$qry = "BEGIN SIJSTK.P_PN_PN5040.X_VALIDASI_KPJ( ".
					" 	:p_kpj, ".
					" 	:p_kode_tk, ".
					" 	:p_kode_perusahaan, ".
					" 	:p_kode_divisi, ".
					" 	:p_kode_segmen, ".
					" 	:p_kode_tipe_klaim, ".
					" 	:p_kode_sebab_klaim, ".
					" 	to_date(:p_tgl_kejadian,'dd/mm/yyyy'), ".
					" 	:p_kode_klaim, ".
		"	:p_sukses, ".
		"	:p_mess, ".
		"	:p_out_kode_tk, ".
		"	:p_out_nama_tk, ".
		"	:p_out_nomor_identitas, ".
		"	:p_out_jenis_identitas, ".
		"	:p_out_kode_kantor_tk, ".
		"	:p_out_kode_perusahaan, ".
		"	:p_out_npp, ".
		"	:p_out_nama_perusahaan, ".
		"	:p_out_kode_divisi, ".
		"	:p_out_nama_divisi, ".
		"	:p_out_status_tk ".
					");END;";											 	
		$proc = $DB->parse($qry);
		oci_bind_by_name($proc, ":p_kpj", $ls_kpj);
		oci_bind_by_name($proc, ":p_kode_tk", $ls_kode_tk);
		oci_bind_by_name($proc, ":p_kode_perusahaan", $ls_kode_perusahaan);
		oci_bind_by_name($proc, ":p_kode_divisi", $ls_kode_divisi);
		oci_bind_by_name($proc, ":p_kode_segmen", $ls_kode_segmen);
		oci_bind_by_name($proc, ":p_kode_tipe_klaim", $ls_kode_tipe_klaim);
		oci_bind_by_name($proc, ":p_kode_sebab_klaim", $ls_kode_sebab_klaim);
		oci_bind_by_name($proc, ":p_tgl_kejadian", $ld_tgl_kejadian);
		oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,4000);
		oci_bind_by_name($proc, ":p_out_kode_tk", $p_out_kode_tk,32);
		oci_bind_by_name($proc, ":p_out_nama_tk", $p_out_nama_tk,100);
		oci_bind_by_name($proc, ":p_out_nomor_identitas", $p_out_nomor_identitas,32);
		oci_bind_by_name($proc, ":p_out_jenis_identitas", $p_out_jenis_identitas,32);
		oci_bind_by_name($proc, ":p_out_kode_kantor_tk", $p_out_kode_kantor_tk,32);
		oci_bind_by_name($proc, ":p_out_kode_perusahaan", $p_out_kode_perusahaan,32);
		oci_bind_by_name($proc, ":p_out_npp", $p_out_npp,32);
		oci_bind_by_name($proc, ":p_out_nama_perusahaan", $p_out_nama_perusahaan,100);
		oci_bind_by_name($proc, ":p_out_kode_divisi", $p_out_kode_divisi,32);
		oci_bind_by_name($proc, ":p_out_nama_divisi", $p_out_nama_divisi,100);
		oci_bind_by_name($proc, ":p_out_status_tk", $p_out_status_tk,32);
		$DB->execute();
	
		$ls_sukses 						  = $p_sukses;
		$ls_mess 							  = $p_mess;
		$ls_out_kode_tk 			  = $p_out_kode_tk;
		$ls_out_nama_tk 			  = $p_out_nama_tk;
		$ls_out_nomor_identitas = $p_out_nomor_identitas;
		$ls_out_jenis_identitas = $p_out_jenis_identitas;
		$ls_out_kode_kantor_tk 	= $p_out_kode_kantor_tk;
		$ls_out_kode_perusahaan = $p_out_kode_perusahaan;
		$ls_out_npp 						= $p_out_npp;
		$ls_out_nama_perusahaan = $p_out_nama_perusahaan;
		$ls_out_kode_divisi 		= $p_out_kode_divisi;
		$ls_out_nama_divisi 		= $p_out_nama_divisi;
		$ls_out_status_tk 			= $p_out_status_tk; 

		$sql_f1a = "select SUBSTR(KN.F_KN_GET_TGL_F1A(:p_kode_tk, (select kode_kepesertaan from kn.kn_kepesertaan_prs
													where kode_perusahaan = :p_kode_perusahaan and kode_divisi = :p_kode_divisi)),0,10) tgl_f1a from dual";
		$proc = $DB->parse($sql_f1a);
		$param_bv = [
		   ':p_kode_tk' => $ls_kode_tk,
		   ':p_kode_perusahaan' => $ls_kode_perusahaan,
		   ':p_kode_divisi' => $ls_kode_divisi
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();

		$ls_out_tgl_f1a 			= $row["TGL_F1A"];
		
		if ($ls_sukses=="1")
		{
		//kpj valid --------------------------------------------------------------
			echo "formObj.st_errval3.value 			= '0';\n";
			echo "formObj.kode_tk.value 			= '".$ls_out_kode_tk."';\n";	
			echo "formObj.nama_tk.value 			= '".$ls_out_nama_tk."';\n";	
			echo "formObj.nomor_identitas.value 	= '".$ls_out_nomor_identitas."';\n";	
			echo "formObj.jenis_identitas.value 	= '".$ls_out_jenis_identitas."';\n";	
			echo "formObj.kode_kantor_tk.value 		= '".$ls_out_kode_kantor_tk."';\n";	
			echo "formObj.kode_perusahaan.value 	= '".$ls_out_kode_perusahaan."';\n";
			echo "formObj.npp.value 				= '".$ls_out_npp."';\n";
			echo "formObj.nama_perusahaan.value 	= '".$ls_out_nama_perusahaan."';\n";	
			echo "formObj.kode_divisi.value 		= '".$ls_out_kode_divisi."';\n";
			echo "formObj.nama_divisi.value 		= '".$ls_out_nama_divisi."';\n";
			echo "formObj.tgl_f1a.value 		    = '".$ls_out_tgl_f1a."';\n";
			
			/*---------------validasi antrian---------------------*/ 
			echo "f_js_val_nik_antrian('".$ls_out_nomor_identitas."');\n";
        	/*---------------end validasi antrian-----------------*/ 
			echo "fl_js_cek_tgl_f1a_tgl_kecelakaan();\n";
			echo "formObj.kode_segmen_list.disabled = true;\n";
			
			//reset field utk tki -------------------------------
			echo "formObj.keterangan.value 							= '';\n";
			echo "formObj.ket_masa_perlindungan.value 	= '';\n";
			echo "formObj.status_kepesertaan.value 			= '';\n";
			echo "formObj.kode_perlindungan.value 			= '';\n";
			echo "formObj.nama_perlindungan.value 			= '';\n";
			echo "formObj.tgl_awal_perlindungan.value 	= '';\n";
			echo "formObj.tgl_akhir_perlindungan.value 	= '';\n";
			echo "formObj.negara_penempatan.value 			= '';\n";
						
			//update 23/01/2019, menambahkan ambil masa perlindungan TKI -------------
			if ($ls_kode_segmen=="TKI" && $ls_kode_tipe_klaim != 'JHT01')
			{
			 	 $qry2 = "BEGIN SIJSTK.P_PN_PN5040.X_GET_PERLINDUNGAN_TKI( ".
						" 	:p_kode_segmen, ".
						" 	:p_out_kode_tk, ".
						" 	:p_out_kode_perusahaan, ".
						" 	:p_out_kode_divisi, ".
						" 	to_date(:ld_tgl_kejadian,'dd/mm/yyyy'), ".
						"	:p_status_kepesertaan, ".
						"	:p_kode_perlindungan, ".
						"	:p_tgl_awal_perlindungan, ".
						"	:p_tgl_akhir_perlindungan, ".
						"	:p_negara_penempatan ".
      					 ");END;";											 	
          		$proc2 = $DB->parse($qry2);
				oci_bind_by_name($proc2, ":p_kode_segmen", $ls_kode_segmen);
				oci_bind_by_name($proc2, ":p_out_kode_tk", $ls_out_kode_tk);
				oci_bind_by_name($proc2, ":p_out_kode_perusahaan", $ls_out_kode_perusahaan);
				oci_bind_by_name($proc2, ":p_out_kode_divisi", $ls_out_kode_divisi);
				oci_bind_by_name($proc2, ":ld_tgl_kejadian", $ld_tgl_kejadian);
				oci_bind_by_name($proc2, ":p_status_kepesertaan", $p_status_kepesertaan,100);
				oci_bind_by_name($proc2, ":p_kode_perlindungan", $p_kode_perlindungan,100);
				oci_bind_by_name($proc2, ":p_tgl_awal_perlindungan", $p_tgl_awal_perlindungan,32);
				oci_bind_by_name($proc2, ":p_tgl_akhir_perlindungan", $p_tgl_akhir_perlindungan,32);
				oci_bind_by_name($proc2, ":p_negara_penempatan", $p_negara_penempatan,100);
				$DB->execute();	
					
				$ls_status_kepesertaan = $p_status_kepesertaan;
				$ls_kode_perlindungan = $p_kode_perlindungan;
				$ld_tgl_awal_perlindungan = $p_tgl_awal_perlindungan;
				$ld_tgl_akhir_perlindungan = $p_tgl_akhir_perlindungan;
				$ls_negara_penempatan = $p_negara_penempatan;
					
      		if ($ls_kode_perlindungan=="NA" || $ls_kode_perlindungan=="")
      		{
				$ls_mess = 'DILUAR MASA PERLINDUNGAN';
				echo "formObj.keterangan.value = 'DILUAR MASA PERLINDUNGAN';\n";	
				echo "formObj.ket_masa_perlindungan.value = '';\n";
				echo "alert('TIDAK LAYAK, $ls_mess');\n";
			}else
      		{
        		$sql = 	"select to_char(to_date(:p_tgl_awal_perlindungan,'dd-mon-yy'),'dd/mm/yyyy') tgl_awal, ".
      					 		"		to_char(to_date(:p_tgl_akhir_perlindungan,'dd-mon-yy'),'dd/mm/yyyy') tgl_akhir, ".
      							"		to_char(to_date(:p_tgl_awal_perlindungan,'dd-mon-yy'),'dd/mm/yyyy')||' s.d '||to_char(to_date(:p_tgl_akhir_perlindungan,'dd-mon-yy'),'dd/mm/yyyy') ket_masa_perlindungan ". 
      							"from dual ";
				$proc = $DB->parse($sql);
				$param_bv = [
				   ':p_tgl_awal_perlindungan' => $ld_tgl_awal_perlindungan,
				   ':p_tgl_akhir_perlindungan' => $ld_tgl_akhir_perlindungan
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ld_tgl_awal_perlindungan  = $row["TGL_AWAL"];
        		$ld_tgl_akhir_perlindungan = $row["TGL_AKHIR"];
      			$ls_ket_masa_perlindungan  = $row["KET_MASA_PERLINDUNGAN"];
						
						if ($p_tgl_awal_perlindungan!="" && $ld_tgl_awal_perlindungan=="")//error format date
						{
          		$sql = 	"select to_char(to_date(:p_tgl_awal_perlindungan,'dd/mm/yyyy'),'dd/mm/yyyy') tgl_awal, ".
        					 		"		to_char(to_date(:p_tgl_akhir_perlindungan,'dd/mm/yyyy'),'dd/mm/yyyy') tgl_akhir, ".
        							"		to_char(to_date(:p_tgl_awal_perlindungan,'dd/mm/yyyy'),'dd/mm/yyyy')||' s.d '||to_char(to_date(:p_tgl_akhir_perlindungan,'dd/mm/yyyy'),'dd/mm/yyyy') ket_masa_perlindungan ". 
        							"from dual ";
				$proc = $DB->parse($sql);
				$param_bv = [
					':p_tgl_awal_perlindungan' => $ld_tgl_awal_perlindungan,
					':p_tgl_akhir_perlindungan' => $ld_tgl_akhir_perlindungan
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
          		$DB->execute();
          		$row = $DB->nextrow();
          		$ld_tgl_awal_perlindungan  = $row["TGL_AWAL"];
          		$ld_tgl_akhir_perlindungan = $row["TGL_AKHIR"];
        			$ls_ket_masa_perlindungan  = $row["KET_MASA_PERLINDUNGAN"];						
						}
						
      			echo "formObj.keterangan.value = '';\n";
      			echo "formObj.ket_masa_perlindungan.value = '".$ls_ket_masa_perlindungan."';\n";
						
						if ($ls_kode_perlindungan=="PRA")
						{
						 	$ls_nama_perlindungan = "SEBELUM BEKERJA"; 
						}elseif ($ls_kode_perlindungan=="ONSITE")
						{
						 	$ls_nama_perlindungan = "SELAMA BEKERJA"; 
						}elseif ($ls_kode_perlindungan=="PURNA")
						{
						 	$ls_nama_perlindungan = "SETELAH BEKERJA"; 
						}	
      		}			
			echo "formObj.status_kepesertaan.value = '".$ls_status_kepesertaan."';\n";
			echo "formObj.kode_perlindungan.value = '".$ls_kode_perlindungan."';\n";
			echo "formObj.nama_perlindungan.value = '".$ls_nama_perlindungan."';\n";
			echo "formObj.tgl_awal_perlindungan.value = '".$ld_tgl_awal_perlindungan."';\n";
			echo "formObj.tgl_akhir_perlindungan.value = '".$ld_tgl_akhir_perlindungan."';\n";
			echo "formObj.negara_penempatan.value = '".$ls_negara_penempatan."';\n";						
			}
			//end update 23/01/2019, menambahkan ambil masa perlindungan TKI --------- 
		}elseif ($ls_sukses=="-1")
		{
      		//kpj tidak ditemukan ----------------------------------------------------
		 	echo "formObj.st_errval3.value 			= '1';\n";		
			echo "formObj.kode_tk.value 				= '';\n";	
			echo "formObj.nama_tk.value 				= '';\n";	
			echo "formObj.nomor_identitas.value = '';\n";	
			echo "formObj.jenis_identitas.value = '';\n";	
			echo "formObj.kode_kantor_tk.value 	= '';\n";	
			echo "formObj.kode_perusahaan.value = '';\n";
			echo "formObj.npp.value 						= '';\n";
			echo "formObj.nama_perusahaan.value = '';\n";	
			echo "formObj.kode_divisi.value 		= '';\n";
			echo "formObj.nama_divisi.value 		= '';\n";

			//reset field utk tki -------------------------------
			echo "formObj.keterangan.value 							= '';\n";
			echo "formObj.ket_masa_perlindungan.value 	= '';\n";
			echo "formObj.status_kepesertaan.value 			= '';\n";
			echo "formObj.kode_perlindungan.value 			= '';\n";
			echo "formObj.nama_perlindungan.value 			= '';\n";
			echo "formObj.tgl_awal_perlindungan.value 	= '';\n";
			echo "formObj.tgl_akhir_perlindungan.value 	= '';\n";
			echo "formObj.negara_penempatan.value 			= '';\n";
			echo "formObj.kpj_old.value 			= '';\n";
								
	 		// echo "fl_js_get_lov_by_kpj();";
							
		}elseif ($ls_sukses=="-2")
		{
      	//kpj terdaftar di lebih dari satu npp -----------------------------------
		 	echo "formObj.st_errval3.value 		  = '0';\n";		
			echo "formObj.kode_tk.value 	 	 	  = '';\n";	
			echo "formObj.nama_tk.value 			  = '';\n";	
			echo "formObj.nomor_identitas.value = '';\n";	
			echo "formObj.jenis_identitas.value = '';\n";	
			echo "formObj.kode_kantor_tk.value 	= '';\n";	
			echo "formObj.kode_perusahaan.value = '';\n";
			echo "formObj.npp.value 						= '';\n";
			echo "formObj.nama_perusahaan.value = '';\n";	
			echo "formObj.kode_divisi.value 		= '';\n";
			echo "formObj.nama_divisi.value 		= '';\n";

			//reset field utk tki -------------------------------
			echo "formObj.keterangan.value 							= '';\n";
			echo "formObj.ket_masa_perlindungan.value 	= '';\n";
			echo "formObj.status_kepesertaan.value 			= '';\n";
			echo "formObj.kode_perlindungan.value 			= '';\n";
			echo "formObj.nama_perlindungan.value 			= '';\n";
			echo "formObj.tgl_awal_perlindungan.value 	= '';\n";
			echo "formObj.tgl_akhir_perlindungan.value 	= '';\n";
			echo "formObj.negara_penempatan.value 			= '';\n";			
						
	 		echo "fl_js_get_lov_by_kpj2();";
			echo "formObj.kpj.value 	 	 	  		= '';\n";
			echo "curr_kpj = '';\n";
			echo "formObj.kpj_old.value 			= '';\n";
		}else
		{
      		//error lain-lain, tampilkan error sesuai pesan error --------------------
		 	echo "formObj.st_errval3.value 		  = '$ls_sukses';\n";	
			echo "formObj.kode_tk.value 	 	 	  = '';\n";	
			echo "formObj.nama_tk.value 			  = '';\n";	
			echo "formObj.nomor_identitas.value = '';\n";	
			echo "formObj.jenis_identitas.value = '';\n";	
			echo "formObj.kode_kantor_tk.value 	= '';\n";	
			echo "formObj.kode_perusahaan.value = '';\n";
			echo "formObj.npp.value 						= '';\n";
			echo "formObj.nama_perusahaan.value = '';\n";	
			echo "formObj.kode_divisi.value 		= '';\n";
			echo "formObj.nama_divisi.value 		= '';\n";

			//reset field utk tki -------------------------------
			echo "formObj.keterangan.value 							= '';\n";
			echo "formObj.ket_masa_perlindungan.value 	= '';\n";
			echo "formObj.status_kepesertaan.value 			= '';\n";
			echo "formObj.kode_perlindungan.value 			= '';\n";
			echo "formObj.nama_perlindungan.value 			= '';\n";
			echo "formObj.tgl_awal_perlindungan.value 	= '';\n";
			echo "formObj.tgl_akhir_perlindungan.value 	= '';\n";
			echo "formObj.negara_penempatan.value 			= '';\n";		
			echo "formObj.kpj_old.value 			= '';\n";	
						
			echo "formObj.kpj.value 	 	 	  		= '';\n";	
			echo "curr_kpj = '';\n";
			if ($ls_mess==""||$ls_mess==null)
			{
				$ls_mess = 'TIDAK LAYAK, ERROR LAIN-LAIN';
			}
			echo "alert('$ls_mess');\n";		
		}

	}	
	// end validasi kpj ----------------------------------------------------------
	
	// -------------- HITUNG MANFAAT ---------------------------------------------
	// hitung manfaat manfaat biaya prothese/orthese -----------------------------
	if ($_GET['getClientId']=="f_ajax_val_kode_manfaat_detil")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ls_kode_manfaat_detil = $_GET['c_kode_manfaat_detil'];

    $sql = "select default_jmlitem from sijstk.pn_kode_manfaat_detil a ".
           "where a.kode_manfaat = :p_kode_manfaat ".
					 "and a.kode_manfaat_detil = :p_kode_manfaat_detil ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_kode_manfaat' => $ls_kode_manfaat,
		':p_kode_manfaat_detil' => $ls_kode_manfaat_detil
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ln_default_jmlitem	= $row['DEFAULT_JMLITEM'];
		
		echo "formObj.alatbantu_jml_item.value = '".number_format($ln_default_jmlitem,0,".",",")."';\n";
		echo "formObj.nom_biaya_diajukan.value = '0';\n";
		echo "formObj.nom_biaya_disetujui.value = '0';\n";	
	}	
		
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_prothese")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ls_kode_manfaat_detil = $_GET['c_kode_manfaat_detil'];
		$ln_jml_item  				 = $_GET['c_alatbantu_jml_item'];
		$ln_nom_biaya_diverifikasi = $_GET['c_nom_biaya_diverifikasi'];
		$ls_kode_klaim 			 	 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_PROTHESE( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kode_manfaat_detil, :p_jml_item, :p_nom_biaya_diverifikasi, :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kode_manfaat_detil", $ls_kode_manfaat_detil);
    oci_bind_by_name($proc, ":p_jml_item", $ln_jml_item);
    oci_bind_by_name($proc, ":p_nom_biaya_diverifikasi", $ln_nom_biaya_diverifikasi);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,100);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat biaya prothese/orthese ---------------------------------	

	// hitung manfaat manfaat biaya obat/rawat -----------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_obatrawat")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_segmen 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan  = $_GET['c_kode_perlindungan'];
		$ls_kode_klaim 			 	 = $_GET['c_kode_klaim'];
		$ln_no_urut 			 		 = $_GET['c_no_urut'];

    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_OBATRAWAT( ".
				 	 				"		:p_kode_klaim,:p_no_urut, :p_kode_manfaat, :p_nom_biaya_diajukan, :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_nom_biaya_diajukan", $ln_nom_biaya_diajukan);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			//  echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
			 echo "formObj.nom_biaya_disetujui_max.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat biaya obat/rawat ---------------------------------------

	//hitung manfaat uraian cacat ------------------------------------------------	 
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_uraiancacat")
	{		
		$ls_kode_klaim 			 	 = $_GET['c_kode_klaim'];
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ls_kode_manfaat_detil = $_GET['c_kode_manfaat_detil'];
		$ls_cacat_kode_keadaan = $_GET['c_cacat_kode_keadaan'];		
		$ln_cacat_persen_dokter	 = $_GET['c_cacat_persen_dokter'];
		$ln_no_urut 			 		 = $_GET['c_no_urut'];
		$ls_valid = "T";
		if ($ln_cacat_persen_dokter=="")
		{
		 	 $ln_cacat_persen_dokter = "0";
		}
		$ln_persen_table_kulitkepala	 = $_GET['c_persen_table_kulitkepala'];
		
		//cek apakah manfaat detil sudah pernah dientry utk keadaan cacat selain yg dientry --------------------------
		if ($ls_cacat_kode_keadaan!="CACATSBGF")
		{
		 	 //utk cacat total tetap dan cacat sebagian anatomis, kode_manfaat_detil tidak dapat dientry lebih dari 1x ------------------
        $sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil a ".
               "where kode_manfaat = :p_kode_manfaat ". 
               "and kode_manfaat_detil = :p_kode_manfaat_detil ".
							 "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
               "and nvl(nom_biaya_disetujui,0)<>0 ". 
               "and kode_klaim in ". 
               "( ". 
               "  select kode_klaim from sijstk.pn_klaim ". 
               "  start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
               "  connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
               ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_kode_manfaat_detil' => $ls_kode_manfaat_detil,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_no_urut' => $ln_no_urut,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ln_cnt_exists = $row["V_JML"];
				
				if ($ln_cnt_exists>="1")
				{				
				 	$ls_valid = "T";
					echo "formObj.st_errval2.value = '1';\n";
				}else
				{
				 	$ls_valid = "Y";	 
					echo "formObj.st_errval2.value = '0';\n";	 
				}								  	 
		}else
		{
		 	 //utk cacat sebagian fungsi, kode_manfaat_detil tidak dapat dientry lebih dari 1x dg jenis kondisi yang berbeda ------------------
        $sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil a ".
               "where kode_manfaat = :p_kode_manfaat ". 
               "and kode_manfaat_detil = :p_kode_manfaat_detil ".
							 "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
							 "and cacat_kode_keadaan <> :p_cacat_kode_keadaan ".
               "and nvl(nom_biaya_disetujui,0)<>0 ". 
               "and kode_klaim in ". 
               "( ". 
               "  select kode_klaim from sijstk.pn_klaim ". 
               "  start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
               "  connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
               ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_kode_manfaat_detil' => $ls_kode_manfaat_detil,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_no_urut' => $ln_no_urut,
			':p_cacat_kode_keadaan' => $ls_cacat_kode_keadaan,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ln_cnt_exists = $row["V_JML"];
				
				if ($ln_cnt_exists>="1")
				{				
				 	$ls_valid = "T";
					echo "formObj.st_errval2.value = '1';\n";
				}else
				{
          $sql = "select sum(nvl(cacat_persen_dokter,0)) as v_jml2 from sijstk.pn_klaim_manfaat_detil a ".
                 "where kode_manfaat = :p_kode_manfaat ". 
                 "and kode_manfaat_detil = :p_kode_manfaat_detil ".
								 "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
  							 "and cacat_kode_keadaan = :p_cacat_kode_keadaan ".
                 "and nvl(nom_biaya_disetujui,0)<>0 ". 
                 "and kode_klaim in ". 
                 "( ". 
                 "  select kode_klaim from sijstk.pn_klaim ". 
                 "  start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
                 "  connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
                 ") ";
			$proc = $DB->parse($sql);
			$param_bv = [
				':p_kode_manfaat' => $ls_kode_manfaat,
				':p_kode_manfaat_detil' => $ls_kode_manfaat_detil,
				':p_kode_klaim' => $ls_kode_klaim,
				':p_no_urut' => $ln_no_urut,
				':p_cacat_kode_keadaan' => $ls_cacat_kode_keadaan,
				':p_status' => 'T'
			];
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
          $DB->execute();
          $row = $DB->nextrow();
          $ln_awtot_cacat_persen_dokter = $row["V_JML2"];				 	
					if ($ln_awtot_cacat_persen_dokter=="")
					{
					 	$ln_awtot_cacat_persen_dokter = "0"; 
					}
					
					$ln_aktot_cacat_persen_dokter = ($ln_awtot_cacat_persen_dokter+$ln_cacat_persen_dokter);
					
					if ($ln_aktot_cacat_persen_dokter>100)
					{
  					$ls_valid = "T";	 
  					echo "formObj.st_errval2.value = '2';\n";						 	 
					}else
					{
  					$ls_valid = "Y";	 
  					echo "formObj.st_errval2.value = '0';\n";					
					} 
				}		
		}
		
		if ($ls_valid=="Y")
		{
      //jika TERLEPASNYA KULIT KEPALA maka %table diiunput (10% s/d 30%) -------
			if ($ls_kode_manfaat == "12" && $ls_kode_manfaat_detil=="25")
			{
  			echo "document.getElementById('cacat_persen_table').readOnly = false;";
  			echo "document.getElementById('cacat_persen_table').style.backgroundColor='#ffff99';";
				//ambil % minimum dan maksimum %table	----------------------------------
        $sql = "select to_char(tgl_kejadian,'dd/mm/yyyy') as tgl_kejadian from sijstk.pn_klaim where kode_klaim = :p_kode_klaim ";
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
        $DB->execute();
        $row = $DB->nextrow();
        $ld_tgl_kejadian = $row["TGL_KEJADIAN"];

        $sql = "select tarif_maksimum persen_min from sijstk.pn_tarif_manfaat ".
               "where kode_segmen = :p_kode_segmen and kode_perlindungan = :p_kode_perlindungan ".
               "and kode_tarif = :p_kode_tarif ".
               "and to_char(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd') ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_tgl_kejadian' => $ld_tgl_kejadian,
			':p_kode_tarif' => 'URAIANCACAT_12_25_MIN',
			':p_kode_segmen' => 'PU',
			':p_kode_perlindungan' => 'PU'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ln_persen_min = $row["PERSEN_MIN"];				

        $sql = "select tarif_maksimum persen_max from sijstk.pn_tarif_manfaat ".
               "where kode_segmen = :p_kode_segmen and kode_perlindungan = :p_kode_perlindungan ".
               "and kode_tarif = :p_kode_tarif ".
               "and to_char(to_date(:p_tgl_kejadian,'dd/mm/yyyy'),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')  ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_tgl_kejadian' => $ld_tgl_kejadian,
			':p_kode_tarif' => 'URAIANCACAT_12_25_MAX',
			':p_kode_segmen' => 'PU',
			':p_kode_perlindungan' => 'PU'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ln_persen_max = $row["PERSEN_MAX"];
				
				//%_table dinput oleh user dg range 10 sd 30% --------------------------
				if (($ln_persen_table_kulitkepala<$ln_persen_min) || ($ln_persen_table_kulitkepala>	$ln_persen_max))	
				{
				 	 echo "formObj.cacat_persen_table.value = '0';\n";
					 echo "formObj.nom_biaya_disetujui.value = '0';\n";
				 	 echo "alert('Input %Table untuk uraian cacat TERKELUPASNYA KULIT KEPALA dalam rentang $ln_persen_min s/d $ln_persen_max persen !');"; 	 
				}else
				{
    			//ambil persen table dan nilai manfaat uraian cacat --------------------
    			$qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_URAIANCACAT( ".
      				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, ".
      									"		:p_cacat_kode_keadaan, :p_kode_manfaat_detil, :p_cacat_persen_dokter, :p_persen_table_kulitkepala, ".
      									"		:p_persen_table, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
          $proc = $DB->parse($qry);
          oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
          oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
          oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
          oci_bind_by_name($proc, ":p_cacat_kode_keadaan", $ls_cacat_kode_keadaan);
          oci_bind_by_name($proc, ":p_kode_manfaat_detil", $ls_kode_manfaat_detil);
          oci_bind_by_name($proc, ":p_cacat_persen_dokter", $ln_cacat_persen_dokter);
          oci_bind_by_name($proc, ":p_persen_table_kulitkepala", $ln_persen_table_kulitkepala);
          oci_bind_by_name($proc, ":p_persen_table", $p_persen_table,32);
      		oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
      		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          $DB->execute();				
          $ln_persen_table  = $p_persen_table;
      		$ln_nom_disetujui = $p_nom_disetujui;
      		$ls_sukses = $p_sukses;
      		$ls_mess = $p_mess;
      		
      		if ($ls_sukses=="-1")
      		{
      		 	 echo "formObj.st_errval1.value = '1';\n";			 
      		}else
      		{
      		 	 echo "formObj.st_errval1.value = '0';\n";
      			 echo "formObj.cacat_persen_table.value = '".number_format($ln_persen_table,2,".",",")."';\n";
      			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
      		}					
				}							 	  	 
			}else
			{
  			echo "document.getElementById('cacat_persen_table').readOnly = true;";
  			echo "document.getElementById('cacat_persen_table').style.backgroundColor='#F2F2F2';";
				
  			//ambil persen table dan nilai manfaat uraian cacat --------------------
  			$qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_URAIANCACAT( ".
    				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, ".
    									"		:p_cacat_kode_keadaan, :p_kode_manfaat_detil, :p_cacat_persen_dokter,0, ".
    									"		:p_persen_table, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
        $proc = $DB->parse($qry);
        oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
        oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
        oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
        oci_bind_by_name($proc, ":p_cacat_kode_keadaan", $ls_cacat_kode_keadaan);
        oci_bind_by_name($proc, ":p_kode_manfaat_detil", $ls_kode_manfaat_detil);
        oci_bind_by_name($proc, ":p_cacat_persen_dokter", $ln_cacat_persen_dokter);
		oci_bind_by_name($proc, ":p_persen_table", $p_persen_table,32);
    		oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
    		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        $DB->execute();				
        $ln_persen_table  = $p_persen_table;
    		$ln_nom_disetujui = $p_nom_disetujui;
    		$ls_sukses = $p_sukses;
    		$ls_mess = $p_mess;
    		
    		if ($ls_sukses=="-1")
    		{
    		 	 echo "formObj.st_errval1.value = '1';\n";			 
    		}else
    		{
    		 	 echo "formObj.st_errval1.value = '0';\n";
    			 echo "formObj.cacat_persen_table.value = '".number_format($ln_persen_table,2,".",",")."';\n";
    			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
    		}								
			}
		}				
	}	
	// end hitung manfaat uraian cacat -------------------------------------------		

	// hitung jml hari stmb ------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_jmlharistmb")
	{		
		$ls_kode_klaim 		 = $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	 = $_GET['c_kode_manfaat'];
		$ln_no_urut 		 	 = $_GET['c_no_urut'];
		$ld_stmb_tgl_awal  = $_GET['c_stmb_tgl_awal'];
		$ld_stmb_tgl_akhir = $_GET['c_stmb_tgl_akhir'];
		
		//pp82 ---------------------------------------------------------------------
		//valid jika:
		//Tanggal awal STMB >= tanggal kejadian
		//Tanggal akhir STMB <= tanggal kondisi akhir

		//validasi tgl_awal_stmb tidak boleh lebih kecil dari tgl_kejadian ---------
		//update 22/12/2019 --------------------------------------------------------
		$sql ="select count(*) as v_jml from sijstk.pn_klaim ".
				 	"where kode_klaim = :p_kode_klaim ".
				  "and to_char(to_date(:p_stmb_tgl_awal,'dd/mm/yyyy'), 'yyyymmdd') < to_char(tgl_kejadian, 'yyyymmdd')";
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
		oci_bind_by_name($proc, ':p_stmb_tgl_awal', $ld_stmb_tgl_awal);
		$DB->execute();
		$row = $DB->nextrow();
		$ln_cnt4	= $row['V_JML'];
		if ($ln_cnt4==""){$ln_cnt4="0";}

		if($ln_cnt4>0){
			echo "formObj.st_errval4.value = '1';\n";		 
		}else{
			echo "formObj.st_errval4.value = '0';\n";
		}
		
		//validasi tgl_akhir_stmb tidak boleh lebih besar dari tgl_kondisi terakhir-
		$sql ="select count(*) as v_jml from sijstk.pn_klaim ".
  				"where kode_klaim = :p_kode_klaim ".
				  "and to_char(to_date(:p_stmb_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd') > to_char(tgl_kondisi_terakhir, 'yyyymmdd')";
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
		oci_bind_by_name($proc, ':p_stmb_tgl_akhir', $ld_stmb_tgl_akhir);
		$DB->execute();
		$row = $DB->nextrow();
		$ln_cnt5	= $row['V_JML'];
		if ($ln_cnt5==""){$ln_cnt5="0";}

		if($ln_cnt5>0){
			echo "formObj.st_errval5.value = '1';\n";		 
		}else{
			echo "formObj.st_errval5.value = '0';\n";
		}
		//--------------------------------------------------------------------------
		
		// Validasi Tanggal Jatuh Tempo Pengajuan STMB (KODE_SEBAB_KLAIM = 'SKK01') untuk kondisi akhir MASIH PENGOBATAN (KA02)
		//3. Tanggal kejadian : semua tanggal kejadian
		//4. Jatuh Tempo pengajuan STMB adalah kelipatan 6 bulan (untuk PU, JAKON, Peserta Magang DKK) dan 1 bulan (bulan kalender atau asal sudah ganti bulan untuk BPU).
		// Rumus Tanggal Jatuh Tempo Pengajuan STMB = Tanggal Klaim - Tanggal Kecelakaan
		// update 14032022 
		$sql = 
		"
		select
		COUNT(*) AS V_JML
		from
		(
		  select
		  aaa.*,
		  case
			when (aaa.KODE_SEBAB_KLAIM = :p_kode_sebab_klaim or aaa.KODE_SEBAB_KLAIM = :p_kode_sebab_klaim2) and aaa.KODE_KONDISI_TERAKHIR = :p_kode_kondisi_terakhir THEN
			  case
				when aaa.KODE_SEGMEN = :p_kode_segmen then
				  case
					when aaa.TGL_PENETAPAN >= trunc(aaa.TGL_JATUH_TEMPO_STMB_NEXT,'MM')
					  then 
					  'Y'
					else
					  'T'
				  end
				else
				  case
					when aaa.TGL_PENETAPAN >= trunc(aaa.TGL_JATUH_TEMPO_STMB_NEXT,'MM')
					  then 
					  'Y'
					else
					  'T'
				  end
			  end
			else
			  'Y'
			end LAYAK_STMB_MASIH_PENGOBATAN
		  from
		  (
			select
			aa.KODE_KLAIM,
			aa.KODE_SEGMEN,
			aa.KODE_SEBAB_KLAIM,
			aa.KODE_KONDISI_TERAKHIR,
			aa.STMB_JMLBULAN_JATUHTEMPO,
			aa.TGL_KEJADIAN,
			aa.TGL_KLAIM,
			aa.TGL_PENETAPAN,
			aa.TGL_PENETAPAN_INDUK,
			aa.STMB_KE,
      case
        when aa.KODE_SEGMEN = :p_kode_segmen then
          case
            when aa.STMB_KE = 0 then trunc(aa.TGL_JATUH_TEMPO_STMB1,'MM')
          else            
            trunc(ADD_MONTHS(nvl(aa.TGL_PENETAPAN_INDUK,aa.TGL_PENETAPAN),aa.STMB_JMLBULAN_JATUHTEMPO),'MM')
          end    
        else
          case
            when aa.STMB_KE = 0 then aa.TGL_JATUH_TEMPO_STMB1
          else
            case
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_1 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_1 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB2,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_2 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_2 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB2,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_3 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_3 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB2,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_4 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_4 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB2,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_5 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_5 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB2,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_6 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_6 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB2,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_7 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_7 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_8 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_8 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_9 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_9 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_10 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_10 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_11 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_11 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              when to_char(aa.TGL_KEJADIAN,'MM') = :p_tgl_12 and to_char(aa.TGL_PENETAPAN_INDUK,'MM') <> :p_tgl_12 then to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
              else
              to_date(to_char(aa.TGL_JATUH_TEMPO_STMB1,'DD-MON') || '-' || to_char(aa.TGL_PENETAPAN,'YYYY'))
            end
          end  
      end TGL_JATUH_TEMPO_STMB_NEXT,
			aa.TGL_JATUH_TEMPO_STMB1,
			aa.TGL_JATUH_TEMPO_STMB2,
			aa.TGL_JATUH_TEMPO_STMB3,
			aa.TGL_JATUH_TEMPO_STMB4,
			aa.TGL_JATUH_TEMPO_STMB5,
			aa.TGL_JATUH_TEMPO_STMB6,
			aa.TGL_JATUH_TEMPO_STMB7,
			aa.TGL_JATUH_TEMPO_STMB8,
			aa.TGL_JATUH_TEMPO_STMB9,
			aa.TGL_JATUH_TEMPO_STMB10,
			aa.JML_BULAN_KLAIM_KEJADIAN,
			aa.JML_BULAN_KLAIM_KEJADIAN_CEIL,
			MOD(nvl(aa.JML_BULAN_KLAIM_KEJADIAN_CEIL,0),aa.STMB_JMLBULAN_JATUHTEMPO) MOD_JML_BULAN_KLAIM_KEJADIANC,
			MOD(nvl(aa.JML_BULAN_KLAIM_KEJADIAN,0),aa.STMB_JMLBULAN_JATUHTEMPO) MOD_JML_BULAN_KLAIM_KEJADIAN
			from
			(
			select
			a.KODE_KLAIM,
			a.KODE_SEGMEN,
			a.KODE_SEBAB_KLAIM,
			a.KODE_KONDISI_TERAKHIR,
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) STMB_JMLBULAN_JATUHTEMPO,
			a.TGL_KEJADIAN, 
			a.TGL_KLAIM,
			a.TGL_PENETAPAN,
			(
			  MONTHS_BETWEEN(a.TGL_KLAIM + 1,a.TGL_KEJADIAN)
			) JML_BULAN_KLAIM_KEJADIAN,
			(
			  CEIL(MONTHS_BETWEEN(a.TGL_KLAIM + 1,a.TGL_KEJADIAN))
			) JML_BULAN_KLAIM_KEJADIAN_CEIL,
			(
			  trunc(a.TGL_KLAIM,'dd') + 1 - trunc(a.TGL_KEJADIAN,'dd')
			) JML_HARI_KLAIM_KEJADIAN,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			)
			) TGL_JATUH_TEMPO_STMB1X,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *2
			) TGL_JATUH_TEMPO_STMB2X,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			)
			) TGL_JATUH_TEMPO_STMB1,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *2
			) TGL_JATUH_TEMPO_STMB2,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *3
			) TGL_JATUH_TEMPO_STMB3,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *4
			) TGL_JATUH_TEMPO_STMB4,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *5
			) TGL_JATUH_TEMPO_STMB5,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *6
			) TGL_JATUH_TEMPO_STMB6,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *7
			) TGL_JATUH_TEMPO_STMB7,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *8
			) TGL_JATUH_TEMPO_STMB8,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *9
			) TGL_JATUH_TEMPO_STMB9,
			ADD_MONTHS(a.TGL_KEJADIAN, 
			(
			select tarif_maksimum from pn.pn_tarif_manfaat
			where kode_tarif = :p_kode_tarif
			and kode_segmen = a.KODE_SEGMEN and kode_perlindungan = a.KODE_PERLINDUNGAN
			and to_char(nvl(a.TGL_KEJADIAN,sysdate),'yyyymmdd') between to_char(tgl_efektif,'yyyymmdd') and to_char(tgl_akhir,'yyyymmdd')
			and rownum = :p_rownum
			) *10
			) TGL_JATUH_TEMPO_STMB10,
			(
			  select count(*)
			  from PN_KLAIM_MANFAAT_DETIL_STMB b
			  where b.KODE_KLAIM in 
        (
          select kode_klaim from pn.pn_klaim c
          start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status
          connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status
        )
        and b.kode_klaim <> :p_kode_klaim
			) STMB_KE,
			(
			  select b.TGL_PENETAPAN from PN.PN_KLAIM b
			  where b.KODE_KLAIM = a.KODE_KLAIM_INDUK
			) TGL_PENETAPAN_INDUK
			from PN.PN_KLAIM a 
			where a.KODE_KLAIM = :p_kode_klaim
			and (a.KODE_SEBAB_KLAIM = :p_kode_sebab_klaim or a.KODE_SEBAB_KLAIM = :p_kode_sebab_klaim2) and a.KODE_KONDISI_TERAKHIR = :p_kode_kondisi_terakhir
			) aa
		  ) aaa
		) aaaa
		where aaaa.LAYAK_STMB_MASIH_PENGOBATAN = :p_status
		"
		;	
				
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_sebab_klaim' => 'SKK01',
			':p_kode_sebab_klaim2' => 'SKK02',
			':p_kode_kondisi_terakhir' => 'KA02',
			':p_kode_segmen' => 'BPU',
			':p_tgl_1' => '01',
			':p_tgl_2' => '02',
			':p_tgl_3' => '03',
			':p_tgl_4' => '04',
			':p_tgl_5' => '05',
			':p_tgl_6' => '06',
			':p_tgl_7' => '07',
			':p_tgl_8' => '08',
			':p_tgl_9' => '09',
			':p_tgl_10' => '10',
			':p_tgl_11' => '11',
			':p_tgl_12' => '12',
			':p_rownum' => 1,
			':p_kode_tarif' => 'STMB_JMLBULAN_JATUHTEMPO_DALAM_PENGOBATAN',
			':p_kode_klaim' => $ls_kode_klaim,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();
		$ln_exist_layak_stmb_masih_pengobatan = $row['V_JML'];
		
		if($ln_exist_layak_stmb_masih_pengobatan>0){
			echo "formObj.st_errval7.value = '1';\n";		 
		}else{
			echo "formObj.st_errval7.value = '0';\n";
		}
		//-----------------------------------------------------------------------
		

		//validasi tgl awal skrg terhadap penetapan sebelumnya ---------------------	
		$sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil ".
			   "where kode_manfaat = :p_kode_manfaat ". 
			   "and nvl(nom_biaya_disetujui,0)<>0 ". 
			   "and to_char(to_date(:p_stmb_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') between to_char(stmb_tgl_awal,'yyyymmdd') and to_char(stmb_tgl_akhir,'yyyymmdd') ".
			   "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,'99999') ".
			   "and kode_klaim in  ".
			   "(  ".
			   "    select kode_klaim from sijstk.pn_klaim  ".
			   "    start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
			   "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
			   ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_stmb_tgl_awal' => $ld_stmb_tgl_awal,
			':p_no_urut' => $ln_no_urut,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();
		$ln_exist_tglawal	= $row['V_JML'];
		if ($ln_exist_tglawal==""){$ln_exist_tglawal="0";}
		
		if ($ln_exist_tglawal>="1")
		{
		 	echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	echo "formObj.st_errval1.value = '0';\n";

			//validasi tgl akhir  skrg terhadap penetapan sebelumnya -----------------	
			$sql = "select count(*) as v_jml2 from sijstk.pn_klaim_manfaat_detil ".
				 "where kode_manfaat = :p_kode_manfaat ". 
				 "and nvl(nom_biaya_disetujui,0)<>0 ". 
				 "and to_char(to_date(:p_stmb_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd') between to_char(stmb_tgl_awal,'yyyymmdd') and to_char(stmb_tgl_akhir,'yyyymmdd') ".
				 "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,'99999') ".
				 "and kode_klaim in  ".
				 "(  ".
				 "    select kode_klaim from sijstk.pn_klaim  ".
				 "    start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
				 "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
				 ") ";
			$proc = $DB->parse($sql);
			$param_bv = [
				':p_kode_manfaat' => $ls_kode_manfaat,
				':p_stmb_tgl_akhir' => $ld_stmb_tgl_akhir,
				':p_no_urut' => $ln_no_urut,
				':p_kode_klaim' => $ls_kode_klaim,
				':p_status' => 'T'
			];
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
			$DB->execute();
			$row = $DB->nextrow();
			$ln_exist_tglakhir	= $row['V_JML2'];
			if ($ln_exist_tglakhir==""){$ln_exist_tglakhir="0";}
			
			if ($ln_exist_tglakhir>="1")
			{
				echo "formObj.st_errval2.value = '1';\n";			 
			}else
			{
				 echo "formObj.st_errval2.value = '0';\n";

				//validasi tgl sebelumnya terhadap penetapan skrg ----------------------	
				$sql = "select count(*) as v_jml3 from sijstk.pn_klaim_manfaat_detil ".
					   "where kode_manfaat = :p_kode_manfaat ". 
					   "and nvl(nom_biaya_disetujui,0)<>0 ". 
					   "and ( ".
									 "       (to_char(stmb_tgl_awal,'yyyymmdd') between to_char(to_date(:p_stmb_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') and to_char(to_date(:p_stmb_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd')) ".
									 "			 or ".
									 "       (to_char(stmb_tgl_akhir,'yyyymmdd') between to_char(to_date(:p_stmb_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') and to_char(to_date(:p_stmb_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd')) ". 
									 ") ".
					   "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,'99999') ".
					   "and kode_klaim in  ".
					   "(  ".
					   "    select kode_klaim from sijstk.pn_klaim  ".
					   "    start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
					   "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
					   ") ";
				$proc = $DB->parse($sql);
				$param_bv = [
					':p_kode_manfaat' => $ls_kode_manfaat,
					':p_stmb_tgl_awal' => $ld_stmb_tgl_awal,
					':p_stmb_tgl_akhir' => $ld_stmb_tgl_akhir,
					':p_no_urut' => $ln_no_urut,
					':p_kode_klaim' => $ls_kode_klaim,
					':p_status' => 'T'
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
				$DB->execute();
				$row = $DB->nextrow();
				$ln_exist_tglsblm	= $row['V_JML3'];
				if ($ln_exist_tglsblm==""){$ln_exist_tglsblm="0";}

				if ($ln_exist_tglsblm>="1")
				{
					echo "formObj.st_errval3.value = '1';\n";			 
				}else
				{
					echo "formObj.st_errval3.value = '0';\n";

					//validasi tgl akhir tidak boleh lebih kecil dari tgl awal ----------- 
					if ($ld_stmb_tgl_awal!="" && $ld_stmb_tgl_akhir!="")
					{
						$sql ="select to_char(to_date(:p_stmb_tgl_awal,'dd/mm/yyyy'), 'yyyymmdd') tglawal, ".
								  " 			to_char(to_date(:p_stmb_tgl_akhir,'dd/mm/yyyy'), 'yyyymmdd') tglakhir ".
									"from dual";
						$proc = $DB->parse($sql);
						oci_bind_by_name($proc, ':p_stmb_tgl_awal', $ld_stmb_tgl_awal);
						oci_bind_by_name($proc, ':p_stmb_tgl_akhir', $ld_stmb_tgl_akhir);
						$DB->execute();
						$row = $DB->nextrow();
						$ls_tglawal	 = $row['TGLAWAL'];
						$ls_tglakhir = $row['TGLAKHIR'];
				
						if($ls_tglakhir<$ls_tglawal)
						{
							echo "formObj.st_errval6.value = '1';\n";		 
						}else
						{
							echo "formObj.st_errval6.value = '0';\n";
								 
								 //hitung jumlah hari stmb ---------------------------------------
							$sql = "select ceil(to_date(:p_stmb_tgl_akhir,'dd/mm/yyyy')-to_date(:p_stmb_tgl_awal,'dd/mm/yyyy'))+1 as v_jmlhari from dual ";
							$proc = $DB->parse($sql);
							oci_bind_by_name($proc, ':p_stmb_tgl_awal', $ld_stmb_tgl_awal);
							oci_bind_by_name($proc, ':p_stmb_tgl_akhir', $ld_stmb_tgl_akhir);
							$DB->execute();
							$row = $DB->nextrow();
							$ln_jml_hari	= $row['V_JMLHARI'];				 
							echo "formObj.stmb_jml_hari.value = '".number_format($ln_jml_hari,0,".",",")."';\n";							
						}
					}
				}
			}	 					 	  		 
		}
	}
	// end hitung jml hari stmb --------------------------------------------------

	// hitung manfaat manfaat biaya rehabilitasi medis ---------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_rehabmedis")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_klaim 		 		 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];

    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_REHABMEDIS( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_nom_biaya_diajukan, :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_nom_biaya_diajukan", $ln_nom_biaya_diajukan);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
	oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses!="1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";	
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_biaya_diajukan,2,".",",")."';\n";		 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat biaya rehabilitasi medis -------------------------------	
	
	//update 18/12/2019 -- PP82 --------------------------------------------------
	// hitung manfaat manfaat penunjang diagnostik pak ---------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_penunjangdiagnostikpak")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diverifikasi = $_GET['c_nom_biaya_diverifikasi'];
		$ls_kode_klaim 		 		 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];

    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_DIAGNOSTIKPAK( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_nom_biaya_diverifikasi, :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_nom_biaya_diverifikasi", $ln_nom_biaya_diverifikasi);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses!="1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";	
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_biaya_diajukan,2,".",",")."';\n";		 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat manfaat penunjang diagnostik pak -----------------------	
		
	// hitung manfaat manfaat beasiswa -------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_beasiswa")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ls_beasiswa_jenis = $_GET['c_beasiswa_jenis'];
		$ls_beasiswa_jenjang_pendidikan = $_GET['c_beasiswa_jenjang_pendidikan'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
						
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_BEASISWA( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, ".
									"		:p_beasiswa_jenis, :p_beasiswa_jenjang_pendidikan, ".
									"		:p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
    oci_bind_by_name($proc, ":p_beasiswa_jenis", $ls_beasiswa_jenis);
    oci_bind_by_name($proc, ":p_beasiswa_jenjang_pendidikan", $ls_beasiswa_jenjang_pendidikan);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat beasiswa -----------------------------------------------	

	// hitung manfaat manfaat santunan berkala -----------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_santunanberkala")
	{			
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_SANTUNANBERKALA( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat santunan berkala ---------------------------------------	

	// hitung manfaat manfaat biaya pemakaman -----------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_biayapemakaman")
	{			
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_BIAYAPEMAKAMAN( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat biaya pemakaman ----------------------------------------		
	
	// hitung manfaat manfaat santunan kematian ----------------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_santunankematian")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
																										
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_SANTUNANKEMATIAN( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat santunan kematian --------------------------------------	

	// hitung manfaat biaya transportasi -----------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_transportasi")
	{
		$ln_transport_darat_diajukan = $_GET['c_biaya_darat'];
		$ln_transport_laut_diajukan  = $_GET['c_biaya_laut'];
		$ln_transport_udara_diajukan = $_GET['c_biaya_udara'];
		$ls_kode_klaim 			 				 = $_GET['c_kode_klaim'];
		$ln_no_urut 			 				 	 = $_GET['c_no_urut'];
					
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TRANSPORTASI( ".
				 	 				"		:p_kode_klaim,:p_no_urut, :p_transport_darat_diajukan, :p_transport_laut_diajukan, :p_transport_udara_diajukan, ".
                  "		:p_nom_darat_disetujui, :p_nom_laut_disetujui, :p_nom_udara_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
	oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
	oci_bind_by_name($proc, ":p_transport_darat_diajukan", $ln_transport_darat_diajukan);
	oci_bind_by_name($proc, ":p_transport_laut_diajukan", $ln_transport_laut_diajukan);
	oci_bind_by_name($proc, ":p_transport_udara_diajukan", $ln_transport_udara_diajukan);
    oci_bind_by_name($proc, ":p_nom_darat_disetujui", $p_nom_darat_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_laut_disetujui", $p_nom_laut_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_udara_disetujui", $p_nom_udara_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_darat_disetujui = $p_nom_darat_disetujui;
		$ln_nom_laut_disetujui 	= $p_nom_laut_disetujui;
		$ln_nom_udara_disetujui = $p_nom_udara_disetujui;		
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ln_nom_darat_disetujui==""){$ln_nom_darat_disetujui=0;}
		if ($ln_nom_laut_disetujui==""){$ln_nom_laut_disetujui=0;}
		if ($ln_nom_udara_disetujui==""){$ln_nom_udara_disetujui=0;}
		 
		$ln_nom_total_disetujui = ($ln_nom_darat_disetujui+$ln_nom_laut_disetujui+$ln_nom_udara_disetujui);
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.transport_darat_disetujui.value = '".number_format($ln_nom_darat_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_laut_disetujui.value 	= '".number_format($ln_nom_laut_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_udara_disetujui.value = '".number_format($ln_nom_udara_disetujui,2,".",",")."';\n";
			 echo "formObj.biaya_total_disetujui.value = '".number_format($ln_nom_total_disetujui,2,".",",")."';\n";	  		 
		}				
	}	
	// end hitung manfaat biaya transportasi -------------------------------------
	
	//update 23/01/2019 TKI ------------------------------------------------------
	// hitung manfaat manfaat bantuan gagal berangkat ----------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkigagalberangkat")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
																										
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIGAGALBRGKT( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat bantuan gagal berangkat --------------------------------	
	
	// hitung manfaat manfaat bantuan gagal penempatan ----------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkigagalpenempatan")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
																										
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIGAGALPNMPATAN( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat bantuan gagal penempatan -------------------------------	

	// hitung manfaat bantuan pemulangan tki -------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkipemulangan")
	{
		$ln_transport_darat_diajukan = $_GET['c_biaya_darat'];
		$ln_transport_laut_diajukan  = $_GET['c_biaya_laut'];
		$ln_transport_udara_diajukan = $_GET['c_biaya_udara'];
		$ls_kode_klaim 			 				 = $_GET['c_kode_klaim'];
		$ln_no_urut 			 				 	 = $_GET['c_no_urut'];
					
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIPEMULANGAN( ".
				 	 				"		:p_kode_klaim,:p_no_urut, :p_transport_darat_diajukan, :p_transport_laut_diajukan, :p_transport_udara_diajukan, ".
                  "		:p_nom_darat_disetujui, :p_nom_laut_disetujui, :p_nom_udara_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_transport_darat_diajukan", $ln_transport_darat_diajukan);
    oci_bind_by_name($proc, ":p_transport_laut_diajukan", $ln_transport_laut_diajukan);
    oci_bind_by_name($proc, ":p_transport_udara_diajukan", $ln_transport_udara_diajukan);
    oci_bind_by_name($proc, ":p_nom_darat_disetujui", $p_nom_darat_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_laut_disetujui", $p_nom_laut_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_udara_disetujui", $p_nom_udara_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_darat_disetujui = $p_nom_darat_disetujui;
		$ln_nom_laut_disetujui 	= $p_nom_laut_disetujui;
		$ln_nom_udara_disetujui = $p_nom_udara_disetujui;		
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ln_nom_darat_disetujui==""){$ln_nom_darat_disetujui=0;}
		if ($ln_nom_laut_disetujui==""){$ln_nom_laut_disetujui=0;}
		if ($ln_nom_udara_disetujui==""){$ln_nom_udara_disetujui=0;}
		 
		$ln_nom_total_disetujui = ($ln_nom_darat_disetujui+$ln_nom_laut_disetujui+$ln_nom_udara_disetujui);
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else if ($ls_sukses=="-2")
		{
		 	 echo "formObj.kode_tipe_penerima.value = '';\n";
			 echo "formObj.transport_udara_diajukan.value = '0';\n";
			 echo "formObj.transport_udara_verifikasi.value = '0';\n";
			 echo "formObj.transport_udara_disetujui.value = '0';\n";
			 echo "alert('$ls_mess');";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.transport_darat_disetujui.value = '".number_format($ln_nom_darat_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_laut_disetujui.value 	= '".number_format($ln_nom_laut_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_udara_disetujui.value = '".number_format($ln_nom_udara_disetujui,2,".",",")."';\n";
			 echo "formObj.biaya_total_disetujui.value = '".number_format($ln_nom_total_disetujui,2,".",",")."';\n";	  		 
		}				
	}	
	// end hitung manfaat bantuan pemulangan tki ---------------------------------

	// hitung manfaat bantuan pemulangan tki -------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkipemulangantdksesuaipk")
	{
		$ln_transport_darat_diajukan = $_GET['c_biaya_darat'];
		$ln_transport_laut_diajukan  = $_GET['c_biaya_laut'];
		$ln_transport_udara_diajukan = $_GET['c_biaya_udara'];
		$ls_kode_klaim 			 				 = $_GET['c_kode_klaim'];
		$ln_no_urut 			 				 	 = $_GET['c_no_urut'];
					
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_PMLANGANTDKSESUAI( ".
				 	 				"		:p_kode_klaim,:p_no_urut, :p_transport_darat_diajukan, :p_transport_laut_diajukan, :p_transport_udara_diajukan, ".
                  "		:p_nom_darat_disetujui, :p_nom_laut_disetujui, :p_nom_udara_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_transport_darat_diajukan", $ln_transport_darat_diajukan);
    oci_bind_by_name($proc, ":p_transport_laut_diajukan", $ln_transport_laut_diajukan);
    oci_bind_by_name($proc, ":p_transport_udara_diajukan", $ln_transport_udara_diajukan);
    oci_bind_by_name($proc, ":p_nom_darat_disetujui", $p_nom_darat_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_laut_disetujui", $p_nom_laut_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_udara_disetujui", $p_nom_udara_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_darat_disetujui = $p_nom_darat_disetujui;
		$ln_nom_laut_disetujui 	= $p_nom_laut_disetujui;
		$ln_nom_udara_disetujui = $p_nom_udara_disetujui;		
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ln_nom_darat_disetujui==""){$ln_nom_darat_disetujui=0;}
		if ($ln_nom_laut_disetujui==""){$ln_nom_laut_disetujui=0;}
		if ($ln_nom_udara_disetujui==""){$ln_nom_udara_disetujui=0;}
		 
		$ln_nom_total_disetujui = ($ln_nom_darat_disetujui+$ln_nom_laut_disetujui+$ln_nom_udara_disetujui);
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else if ($ls_sukses=="-2")
		{
		 	 echo "formObj.kode_tipe_penerima.value = '';\n";
			 echo "formObj.transport_udara_diajukan.value = '0';\n";
			 echo "formObj.transport_udara_verifikasi.value = '0';\n";
			 echo "formObj.transport_udara_disetujui.value = '0';\n";
			 echo "alert('$ls_mess');";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.transport_darat_disetujui.value = '".number_format($ln_nom_darat_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_laut_disetujui.value 	= '".number_format($ln_nom_laut_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_udara_disetujui.value = '".number_format($ln_nom_udara_disetujui,2,".",",")."';\n";
			 echo "formObj.biaya_total_disetujui.value = '".number_format($ln_nom_total_disetujui,2,".",",")."';\n";	  		 
		}				
	}	
	// end hitung manfaat bantuan pemulangan tki ---------------------------------
	
	// hitung manfaat bantuan pemulangan tki gagal ditempatkan -------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_pemulangangagalpenempatan")
	{
		$ln_transport_darat_diajukan = $_GET['c_biaya_darat'];
		$ln_transport_laut_diajukan  = $_GET['c_biaya_laut'];
		$ln_transport_udara_diajukan = $_GET['c_biaya_udara'];
		$ls_kode_klaim 			 				 = $_GET['c_kode_klaim'];
		$ln_no_urut 			 				 	 = $_GET['c_no_urut'];
					
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIPULANGGAGALPNM( ".
				 	 				"		:p_kode_klaim,:p_no_urut, :p_transport_darat_diajukan, :p_transport_laut_diajukan, :p_transport_udara_diajukan, ".
                  "		:p_nom_darat_disetujui, :p_nom_laut_disetujui, :p_nom_udara_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_transport_darat_diajukan", $ln_transport_darat_diajukan);
    oci_bind_by_name($proc, ":p_transport_laut_diajukan", $ln_transport_laut_diajukan);
    oci_bind_by_name($proc, ":p_transport_udara_diajukan", $ln_transport_udara_diajukan);
    oci_bind_by_name($proc, ":p_nom_darat_disetujui", $p_nom_darat_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_laut_disetujui", $p_nom_laut_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_udara_disetujui", $p_nom_udara_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_darat_disetujui = $p_nom_darat_disetujui;
		$ln_nom_laut_disetujui 	= $p_nom_laut_disetujui;
		$ln_nom_udara_disetujui = $p_nom_udara_disetujui;		
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ln_nom_darat_disetujui==""){$ln_nom_darat_disetujui=0;}
		if ($ln_nom_laut_disetujui==""){$ln_nom_laut_disetujui=0;}
		if ($ln_nom_udara_disetujui==""){$ln_nom_udara_disetujui=0;}
		 
		$ln_nom_total_disetujui = ($ln_nom_darat_disetujui+$ln_nom_laut_disetujui+$ln_nom_udara_disetujui);
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.transport_darat_disetujui.value = '".number_format($ln_nom_darat_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_laut_disetujui.value 	= '".number_format($ln_nom_laut_disetujui,2,".",",")."';\n";
			 echo "formObj.transport_udara_disetujui.value = '".number_format($ln_nom_udara_disetujui,2,".",",")."';\n";
			 echo "formObj.biaya_total_disetujui.value = '".number_format($ln_nom_total_disetujui,2,".",",")."';\n";	  		 
		}				
	}	
	// end hitung manfaat biaya transportasi -------------------------------------
	
	// hitung manfaat manfaat bantuan gagal penempatan ----------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkiditempatkantdksesuaiperjanjian")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
																										
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_PNMPATANTDKSESUAI( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat bantuan gagal penempatan -------------------------------	

	// hitung manfaat penggantian kerugian atas tindakan pihak lain --------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkigantikehilangan")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_jml_berat  				 = $_GET['c_jml_berat'];
		$ls_kode_klaim 			 	 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIGANTIKERUGIAN( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_jml_berat, :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_jml_berat", $ln_jml_berat);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat penggantian kerugian atas tindakan pihak lain ----------	

	// hitung manfaat manfaat beasiswa tki/(pu/bpu/jakon pp82) - tahun berjalan --
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_beasiswa_thnbjln")
	{		
		$ls_kode_klaim 						= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 					= $_GET['c_kode_manfaat'];
		$ls_kd_prg 								= $_GET['c_kd_prg'];
		$ls_beasiswa_jenis 				= $_GET['c_beasiswa_jenis'];
		$ls_beasiswa_jenjang_pendidikan = $_GET['c_beasiswa_jenjang_pendidikan'];
		$ln_no_urut 		 					= $_GET['c_no_urut'];
		$ls_beasiswa_nik_penerima = $_GET['c_beasiswa_nik_penerima'];
		$ls_tahun 								= $_GET['c_tahun'];
		
		//untuk tahun dan penerima beasiswa yg sama maka tidak boleh diinput 2x ----	
    $sql = "select sum(nvl(cnt,0)) as v_jml from ".
           "( ".
           "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
           "    where kode_manfaat = :p_kode_manfaat ".
           "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
           "    and nvl(nom_biaya_disetujui,0)<>0 ".
           "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
           "    and nvl(beasiswa_kini_thn,'3000') = :p_tahun ". 
           "    and kode_klaim in ".
           "    (  ".
           "        select kode_klaim from sijstk.pn_klaim  ".
           "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')='T'  ".
           "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T'  ".
           "    ) ".
           "    UNION ALL ".
           "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
           "    where kode_manfaat = :p_kode_manfaat ".
           "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
           "    and nvl(nom_biaya_disetujui,0)<>0 ".
           "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
           "    and nvl(beasiswa_rapel_thn,'3000') = :p_tahun ".
           "    and kode_klaim in ".
           "    ( ". 
           "        select kode_klaim from sijstk.pn_klaim ". 
           "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
           "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
           "    ) ".
           ")";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_kode_manfaat' => $ls_kode_manfaat,
		':p_kode_klaim' => $ls_kode_klaim,
		':p_no_urut' => $ln_no_urut,
		':p_beasiswa_nik_penerima' => $ls_beasiswa_nik_penerima,
		':p_tahun' => $ls_tahun,
		':p_status' => 'T'
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ln_exist_thn	= $row['V_JML'];
		if ($ln_exist_thn==""){$ln_exist_thn="0";}
		
		if ($ln_exist_thn>="1")
		{
		 	 //beasiswa sudah pernah diterima utk tahun tsb, tidak dapat diajukan lg ---
			 echo "formObj.beasiswa_jenis.value = '';\n";
			 echo "formObj.beasiswa_jenjang_pendidikan.value = '';\n";
			 echo "formObj.beasiswa_kini_tingkat.value = '0';\n";
			 echo "formObj.beasiswa_kini_nom.value = '0';\n";
			 echo "f_ajax_val_total_disetujui();";
			 
			 echo "curr_beasiswa_jenis = '';\n";
			 echo "curr_beasiswa_jenjang_pendidikan = '';\n";
			 echo "alert('Manfaat Beasiswa tahun ajaran $ls_tahun untuk NIK $ls_beasiswa_nik_penerima sudah pernah diterima..!');";
		}else
		{	
      //cek apakah tahun ajaran < tahun kejadian, jika ya maka blm dapat beasiswa -----
			$sql = "select to_char(tgl_kejadian,'yyyy') thn, to_char(tgl_kejadian,'yyyymmdd') tgl_kejadian_yyyymmdd, kode_segmen from sijstk.pn_klaim where kode_klaim = :p_kode_klaim ";
	  $proc = $DB->parse($sql);
	  oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_thn_kejadian	= $row['THN'];
			$ls_kode_segmen	= $row['KODE_SEGMEN'];
			$ls_tgl_kejadian_yyyymmdd	= $row['TGL_KEJADIAN_YYYYMMDD'];
			
			if ($ls_tahun<$ls_thn_kejadian)
			{
  		 	 //tahun ajaran dibawah tahun kejadian, tidak mendapat beasiswa ---
  			 echo "formObj.beasiswa_jenis.value = '';\n";
  			 echo "formObj.beasiswa_jenjang_pendidikan.value = '';\n";
  			 echo "formObj.beasiswa_kini_tingkat.value = '0';\n";
  			 echo "formObj.beasiswa_kini_nom.value = '0';\n";
  			 echo "f_ajax_val_total_disetujui();";

  			 echo "curr_beasiswa_jenis = '';\n";
  			 echo "curr_beasiswa_jenjang_pendidikan = '';\n";				 
  			 echo "alert('Tgl Kejadian di tahun $ls_thn_kejadian, tahun ajaran $ls_tahun belum bisa mendapatkan beasiswa ..!');";			
			}else
			{							
        $ls_is_valid = "T";	
				//jika PU/BPU/JAKON maka : ---------------------------------------------
				if (($ls_kode_segmen == "PU" || $ls_kode_segmen == "BPU" || $ls_kode_segmen == "JAKON"))
				{
				 	//cek batas maksimum berapa kali menerima beasiswa utk jenjang yg sama //update pp82 23/12/2019 ---- 
				 	if ($ls_tgl_kejadian_yyyymmdd<'20191202')
					{
					 	$ls_is_valid = "T";	 
					}else
					{
  					//cek maksimum berapa kali menerima beasiswa utk jenjang yg sama -----
  					if ($ls_beasiswa_jenis=="PELATIHAN")
  					{
  					 	//ambil maksimum berapa kali menerima beasiswa utk jenjang yg sama diluar current transaksi -----
  						$sql = "select kategori from sijstk.ms_lookup a where tipe=:p_tipe and kode = :p_beasiswa_jenis";
				$proc = $DB->parse($sql);
				$param_bv = [
					':p_tipe' => 'KLMJNSBEAS',
					':p_beasiswa_jenis' => $ls_beasiswa_jenis
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}						  
              $DB->execute();
              $row = $DB->nextrow();
              $ln_max_n_kali	= $row['KATEGORI'];
  						if ($ln_max_n_kali==""){$ln_max_n_kali="0";}	
  						
          		$sql = "select sum(nvl(cnt,0)) as v_jml from ".
                     "( ".
                     "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                     "    where kode_manfaat = :p_kode_manfaat ".
                     "    and kode_klaim||no_urut <> :ls_kode_klaim||nvl(:p_no_urut,999999) ".
                     "    and nvl(nom_biaya_disetujui,0)<>0 ".
                     "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                     "    and nvl(beasiswa_jenis,'XyZ') = :p_beasiswa_jenis ". 
                     "    and kode_klaim in ".
                     "    (  ".
                     "        select kode_klaim from sijstk.pn_klaim  ".
                     "        start with kode_klaim = :ls_kode_klaim and nvl(status_batal,'T')=:p_status  ".
                     "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
                     "    ) ".
                     "    UNION ALL ".
                     "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                     "    where kode_manfaat = :p_kode_manfaat ".
                     "    and kode_klaim||no_urut <> :ls_kode_klaim||nvl(:p_no_urut,999999) ".
                     "    and nvl(nom_biaya_disetujui,0)<>0 ".
                     "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                     "    and nvl(beasiswa_rapel_jenis,'XyZ') = :p_beasiswa_jenis ".
                     "    and kode_klaim in ".
                     "    ( ". 
                     "        select kode_klaim from sijstk.pn_klaim ". 
                     "        start with kode_klaim = :ls_kode_klaim and nvl(status_batal,'T')=:p_status ". 
                     "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
                     "    ) ".
                     ")";
				$proc = $DB->parse($sql);
				$param_bv = [
					':p_kode_manfaat' => $ls_kode_manfaat,
					':p_kode_klaim' => $ls_kode_klaim,
					':p_no_urut' => $ln_no_urut,
					':p_beasiswa_nik_penerima' => $ls_beasiswa_nik_penerima,
					':p_beasiswa_jenis' => $ls_beasiswa_jenis,
					':p_status' => 'T'
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
              $DB->execute();
              $row = $DB->nextrow();
              $ln_sudah_n_kali	= $row['V_JML'];
          		if ($ln_sudah_n_kali==""){$ln_sudah_n_kali="0";}						
  						
  						if ($ln_sudah_n_kali>=$ln_max_n_kali)
  						{
  						 	$ls_is_valid = "T";
  							$ln_nom_disetujui = "0";
  							echo "formObj.beasiswa_kini_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
  							echo "f_ajax_val_total_disetujui();";
  							echo "alert('Jenis beasiswa $ls_beasiswa_jenis sudah pernah diterima sebanyak $ln_sudah_n_kali (maksimum $ln_max_n_kali ) !');";	 																	 
  						}else
  						{
  						 	$ls_is_valid = "Y";	 	 
  						}  	  	  	  	 																	
  					}else
  					{
  					 	if ($ls_beasiswa_jenis!="" && $ls_beasiswa_jenjang_pendidikan!="")
  						{
    						//ambil maksimum berapa kali menerima beasiswa utk jenjang yg sama -----
    						$sql = "select kategori from sijstk.ms_lookup a where tipe=:p_tipe and kode = :p_beasiswa_jenjang_pendidikan";
				$proc = $DB->parse($sql);
				$param_bv = [
					':p_tipe' => 'TKSKLHPP82',
					':p_beasiswa_jenjang_pendidikan' => $ls_beasiswa_jenjang_pendidikan
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
                $DB->execute();
                $row = $DB->nextrow();
                $ln_max_n_kali	= $row['KATEGORI']; 
    						if ($ln_max_n_kali==""){$ln_max_n_kali="0";}	
    						
            		$sql = "select sum(nvl(cnt,0)) as v_jml from ".
                       "( ".
                       "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                       "    where kode_manfaat = :p_kode_manfaat ".
                       "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                       "    and nvl(nom_biaya_disetujui,0)<>0 ".
                       "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                       "    and nvl(beasiswa_jenis,'XyZ') = :p_beasiswa_jenis ". 
    									 "    and nvl(beasiswa_jenjang_pendidikan,'XyZ') = :p_beasiswa_jenjang_pendidikan ".
                       "    and kode_klaim in ".
                       "    (  ".
                       "        select kode_klaim from sijstk.pn_klaim  ".
                       "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
                       "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
                       "    ) ".
                       "    UNION ALL ".
                       "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                       "    where kode_manfaat = :p_kode_manfaat ".
                       "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                       "    and nvl(nom_biaya_disetujui,0)<>0 ".
                       "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                       "    and nvl(beasiswa_rapel_jenis,'XyZ') = :p_beasiswa_jenis ".
    									 "    and nvl(beasiswa_rapel_jenjang,'XyZ') = :p_beasiswa_jenjang_pendidikan ".
                       "    and kode_klaim in ".
                       "    ( ". 
                       "        select kode_klaim from sijstk.pn_klaim ". 
                       "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
                       "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
                       "    ) ".
                       ")";
				$proc = $DB->parse($sql);
				$param_bv = [
					':p_kode_manfaat' => $ls_kode_manfaat,
					':p_kode_klaim' => $ls_kode_klaim,
					':p_no_urut' => $ln_no_urut,
					':p_beasiswa_nik_penerima' => $ls_beasiswa_nik_penerima,
					':p_beasiswa_jenis' => $ls_beasiswa_jenis,
					':p_beasiswa_jenjang_pendidikan' => $ls_beasiswa_jenjang_pendidikan,
					':p_status' => 'T'
				];
				foreach ($param_bv as $key => $value) {
					oci_bind_by_name($proc, $key, $param_bv[$key]);
				}
                $DB->execute();
                $row = $DB->nextrow();
                $ln_sudah_n_kali	= $row['V_JML'];
            		if ($ln_sudah_n_kali==""){$ln_sudah_n_kali="0";}						
    						
    						if ($ln_sudah_n_kali>=$ln_max_n_kali)
    						{
    						 	$ls_is_valid = "T";
    							$ln_nom_disetujui = "0";
    							echo "formObj.beasiswa_kini_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
    							echo "f_ajax_val_total_disetujui();";
    							echo "alert('Jenis beasiswa $ls_beasiswa_jenis sudah pernah diterima sebanyak $ln_sudah_n_kali (maksimum $ln_max_n_kali ) !');";	 																	 
    						}else
    						{
    						 	$ls_is_valid = "Y";	 	 
    						}
  						}else
  						{
  						 	$ls_is_valid = "Y";		 
  						} 					 
  					}
					}
					//end cek maksimum berapa kali menerima beasiswa utk jenjang yg sama -
				}else
				{
				 	//PMI -------------
					$ls_is_valid = "Y";	 
				}
				
				if ($ls_is_valid=="Y")
				{
  				$qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_BEASISWA( ".
      				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, ".
      									"		:p_beasiswa_jenis, :p_beasiswa_jenjang_pendidikan, ".
      									"		:p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
          $proc = $DB->parse($qry);
          oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
          oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
          oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
          oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
          oci_bind_by_name($proc, ":p_beasiswa_jenis", $ls_beasiswa_jenis);
          oci_bind_by_name($proc, ":p_beasiswa_jenjang_pendidikan", $ls_beasiswa_jenjang_pendidikan);
          oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
      		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          $DB->execute();				
          $ln_nom_disetujui = $p_nom_disetujui;
      		$ls_sukses = $p_sukses;
      		$ls_mess = $p_mess;
      		
      		if ($ls_sukses=="-1")
      		{
            echo "formObj.st_errval1.value = '1';\n";
            echo "f_ajax_val_total_disetujui();";			 
      		}else
      		{
            echo "formObj.st_errval1.value = '0';\n";
            echo "formObj.beasiswa_kini_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
            
            ///reset entrian rapel ---
            echo "formObj.beasiswa_rapel_jenis.value = '';\n";
            echo "formObj.beasiswa_rapel_jenjang.value = '';\n";
            echo "formObj.beasiswa_rapel_tingkat.value = '0';\n";
            echo "formObj.beasiswa_rapel_nom.value = '0';\n";
            
            echo "f_ajax_val_total_disetujui();";
            
            echo "curr_beasiswa_rapel_jenis = '';\n";
            echo "curr_beasiswa_rapel_jenjang = '';\n";	 	  		 
      		}				
				}else
				{
          $ln_nom_disetujui = "0";
          echo "formObj.beasiswa_kini_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
          echo "f_ajax_val_total_disetujui();";				
				}
			}//end if ($ls_tahun<$ls_thn_kejadian)
		}	//end if ($ln_exist_thn>="1")			
	}	
	// end hitung manfaat manfaat beasiswa tki/(pu/bpu/jakon pp82)-thn berjalan --
	
	// hitung manfaat manfaat beasiswa tki/(pu/bpu/jakon pp82)-rapel tahun lalu --	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_beasiswa_rapel")
	{		
		$ls_kode_klaim 									= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 								= $_GET['c_kode_manfaat'];
		$ls_kd_prg 											= $_GET['c_kd_prg'];
		$ls_beasiswa_jenis 							= $_GET['c_beasiswa_jenis'];
		$ls_beasiswa_jenjang_pendidikan = $_GET['c_beasiswa_jenjang_pendidikan'];
		$ln_no_urut 		 								= $_GET['c_no_urut'];
		$ls_beasiswa_nik_penerima 			= $_GET['c_beasiswa_nik_penerima'];
		$ls_tahun 											= $_GET['c_tahun'];
		$ls_beasiswa_jenis_thnbjln 			= $_GET['c_beasiswa_jenis_thnbjln'];
		$ls_jenjang_pendidikan_thnbjln  = $_GET['c_jenjang_pendidikan_thnbjln'];
		
		//untuk tahun dan penerima beasiswa yg sama maka tidak boleh diinput 2x ----	
    $sql = "select sum(nvl(cnt,0)) as v_jml from ".
           "( ".
           "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
           "    where kode_manfaat = :p_kode_manfaat ".
           "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
           "    and nvl(nom_biaya_disetujui,0)<>0 ".
           "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
           "    and nvl(beasiswa_kini_thn,'3000') = :p_tahun ". 
           "    and kode_klaim in ".
           "    (  ".
           "        select kode_klaim from sijstk.pn_klaim  ".
           "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')='T'  ".
           "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T'  ".
           "    ) ".
           "    UNION ALL ".
           "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
           "    where kode_manfaat = :p_kode_manfaat ".
           "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
           "    and nvl(nom_biaya_disetujui,0)<>0 ".
           "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
           "    and nvl(beasiswa_rapel_thn,'3000') = :p_tahun ".
           "    and kode_klaim in ".
           "    ( ". 
           "        select kode_klaim from sijstk.pn_klaim ". 
           "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
           "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
           "    ) ".
           ")";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_kode_manfaat' => $ls_kode_manfaat,
		':p_kode_klaim' => $ls_kode_klaim,
		':p_no_urut' => $ln_no_urut,
		':p_beasiswa_nik_penerima' => $ls_beasiswa_nik_penerima,
		':p_tahun' => $ls_tahun,
		':p_status' => 'T'
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}		   
    $DB->execute();
    $row = $DB->nextrow();
    $ln_exist_thn	= $row['V_JML'];
		if ($ln_exist_thn==""){$ln_exist_thn="0";}
		
		if ($ln_exist_thn>="1")
		{
		 	 //beasiswa sudah pernah diterima utk tahun tsb, tidak dapat diajukan lg ---
			 echo "formObj.beasiswa_rapel_jenis.value = '';\n";
			 echo "formObj.beasiswa_rapel_jenjang.value = '';\n";
			 echo "formObj.beasiswa_rapel_tingkat.value = '0';\n";
			 echo "formObj.beasiswa_rapel_nom.value = '0';\n";
			 echo "f_ajax_val_total_disetujui();";
			 
			 echo "curr_beasiswa_rapel_jenis = '';\n";
			 echo "curr_beasiswa_rapel_jenjang = '';\n";					 
			 echo "alert('Manfaat Beasiswa tahun ajaran $ls_tahun untuk NIK $ls_beasiswa_nik_penerima sudah pernah diterima..!');";
		}else
		{						
      //cek apakah tahun ajaran < tahun kejadian, jika ya maka blm dapat beasiswa -----
			$sql = "select to_char(tgl_kejadian,'yyyy') thn, to_char(tgl_kejadian,'yyyymmdd') tgl_kejadian_yyyymmdd, kode_segmen, kode_klaim_induk ".
					 	 "from sijstk.pn_klaim where kode_klaim = :p_kode_klaim ";
	  $proc = $DB->parse($sql);
	  oci_bind_by_name($proc, ':p_kode_klaim', $ls_kode_klaim);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_thn_kejadian	= $row['THN'];
			$ls_kode_segmen	= $row['KODE_SEGMEN'];
			$ls_tgl_kejadian_yyyymmdd	= $row['TGL_KEJADIAN_YYYYMMDD'];
			$ls_kode_klaim_induk	= $row['KODE_KLAIM_INDUK'];
			
			if ($ls_tahun<$ls_thn_kejadian)
			{
  		 	 //tahun ajaran dibawah tahun kejadian, tidak mendapat beasiswa ---
  			 echo "formObj.beasiswa_rapel_jenis.value = '';\n";
  			 echo "formObj.beasiswa_rapel_jenjang.value = '';\n";
  			 echo "formObj.beasiswa_rapel_tingkat.value = '0';\n";
  			 echo "formObj.beasiswa_rapel_nom.value = '0';\n";
  			 echo "f_ajax_val_total_disetujui();";

  			 echo "curr_beasiswa_rapel_jenis = '';\n";
  			 echo "curr_beasiswa_rapel_jenjang = '';\n";				 
  			 echo "alert('Tgl Kejadian di tahun $ls_thn_kejadian, tahun rapel $ls_tahun belum bisa mendapatkan beasiswa ..!');";			
			}else
			{
			 	$ls_error = "0";
				
				$ls_is_valid = "T";	
				//jika PU/BPU/JAKON maka cek batas maksimum berapa kali menerima beasiswa utk jenjang yg sama //update pp82 23/12/2019 ----
				if (($ls_kode_segmen == "PU" || $ls_kode_segmen == "BPU" || $ls_kode_segmen == "JAKON") && ($ls_tgl_kejadian_yyyymmdd>='20191202'))
				{
  				if ($ls_kode_klaim_induk!="")
					{
            $ls_is_valid = "T";
            $ln_nom_disetujui = "0";
            echo "formObj.beasiswa_rapel_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
            echo "f_ajax_val_total_disetujui();";
            echo "alert('Rapel beasiswa tidak diperbolehkan untuk penetapan ulang.!');";					
					}else
					{
  					//utk rapel, maka jenjang pendidikan tidak boleh diatas yg tahun berjalan
    				if (($ls_beasiswa_jenis==$ls_beasiswa_jenis_thnbjln) && ($ls_beasiswa_jenis=="BEASISWA"))
    				{
        			//ambil urut jenjang pendidikan thn berjalan -----------------------
    					$sql = "select seq from sijstk.ms_lookup where tipe=:p_tipe and kode=:p_jenjang_pendidikan_thnbjln ";
				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_tipe', 'TKSKLHPP82');
				oci_bind_by_name($proc, ':p_jenjang_pendidikan_thnbjln', $ls_jenjang_pendidikan_thnbjln);
              $DB->execute();
              $row = $DB->nextrow();
              $ln_urut_jenjang_thnbjln	= $row['SEQ'];	
    					
    					//ambil urut jenjang pendidikan thn rapel --------------------------
    					$sql = "select seq from sijstk.ms_lookup where tipe='TKSKLHPP82' and kode=:p_beasiswa_jenjang_pendidikan ";
				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ':p_tipe', 'TKSKLHPP82');
				oci_bind_by_name($proc, ':p_beasiswa_jenjang_pendidikan', $ls_beasiswa_jenjang_pendidikan);
      		  $DB->execute();
              $row = $DB->nextrow();
              $ln_urut_jenjang_rapel	= $row['SEQ'];		
    					
    					if ($ln_urut_jenjang_thnbjln<$ln_urut_jenjang_rapel)
    					{
                //Jenjang pendidikan untuk tahun sebelumnya tidak boleh diatas jenjang pendidikan tahun berjalan ---
                echo "formObj.beasiswa_rapel_jenis.value = '';\n";
                echo "formObj.beasiswa_rapel_jenjang.value = '';\n";
                echo "formObj.beasiswa_rapel_tingkat.value = '0';\n";
                echo "formObj.beasiswa_rapel_nom.value = '0';\n";
                echo "f_ajax_val_total_disetujui();";
                
                echo "curr_beasiswa_rapel_jenis = '';\n";
                echo "curr_beasiswa_rapel_jenjang = '';\n";
    						$ls_error = "1";
  							$ls_is_valid = "T";				 
                echo "alert('Jenjang pendidikan untuk tahun sebelumnya tidak boleh diatas jenjang pendidikan tahun berjalan ..!');";						
    					}else
  						{
      				 	//cek batas maksimum berapa kali menerima beasiswa utk jenjang yg sama //update pp82 23/12/2019 ---- 
      				 	if ($ls_tgl_kejadian_yyyymmdd<'20191202')
      					{
      					 	$ls_is_valid = "T";	 
      					}else
      					{
        					//cek maksimum berapa kali menerima beasiswa utk jenjang yg sama -----
        					if ($ls_beasiswa_jenis=="PELATIHAN")
        					{
        					 	//ambil maksimum berapa kali menerima beasiswa utk jenjang yg sama diluar current transaksi -----
        						$sql = "select kategori from sijstk.ms_lookup a where tipe=:p_tipe and kode = :p_beasiswa_jenis";
					$proc = $DB->parse($sql);
					oci_bind_by_name($proc, ':p_tipe', 'KLMJNSBEAS');
					oci_bind_by_name($proc, ':p_beasiswa_jenis', $ls_beasiswa_jenis);
                    $DB->execute();
                    $row = $DB->nextrow();
                    $ln_max_n_kali	= $row['KATEGORI'];
        						if ($ln_max_n_kali==""){$ln_max_n_kali="0";}	
        						
                		$sql = "select sum(nvl(cnt,0)) as v_jml from ".
                           "( ".
                           "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                           "    where kode_manfaat = :p_kode_manfaat ".
                           "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                           "    and nvl(nom_biaya_disetujui,0)<>0 ".
                           "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                           "    and nvl(beasiswa_jenis,'XyZ') = :p_beasiswa_jenis ". 
                           "    and kode_klaim in ".
                           "    (  ".
                           "        select kode_klaim from sijstk.pn_klaim  ".
                           "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
                           "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
                           "    ) ".
                           "    UNION ALL ".
                           "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                           "    where kode_manfaat = :p_kode_manfaat ".
                           "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                           "    and nvl(nom_biaya_disetujui,0)<>0 ".
                           "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                           "    and nvl(beasiswa_rapel_jenis,'XyZ') = :p_beasiswa_jenis ".
                           "    and kode_klaim in ".
                           "    ( ". 
                           "        select kode_klaim from sijstk.pn_klaim ". 
                           "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
                           "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
                           "    ) ".
                           ")";
					$proc = $DB->parse($sql);
					$param_bv = [
						':p_kode_manfaat' => $ls_kode_manfaat,
						':p_kode_klaim' => $ls_kode_klaim,
						':p_no_urut' => $ln_no_urut,
						':p_beasiswa_nik_penerima' => $ls_beasiswa_nik_penerima,
						':p_beasiswa_jenis' => $ls_beasiswa_jenis,
						':p_status' => 'T'
					];
					foreach ($param_bv as $key => $value) {
						oci_bind_by_name($proc, $key, $param_bv[$key]);
					}
                    $DB->execute();
                    $row = $DB->nextrow();
                    $ln_sudah_n_kali	= $row['V_JML'];
                		if ($ln_sudah_n_kali==""){$ln_sudah_n_kali="0";}						
        						
        						if ($ln_sudah_n_kali>=$ln_max_n_kali)
        						{
        						 	$ls_is_valid = "T";
        							$ln_nom_disetujui = "0";
        							echo "formObj.beasiswa_rapel_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
        							echo "f_ajax_val_total_disetujui();";
        							echo "alert('Jenis beasiswa $ls_beasiswa_jenis sudah pernah diterima sebanyak $ln_sudah_n_kali (maksimum $ln_max_n_kali ) !');";	 																	 
        						}else
        						{
        						 	$ls_is_valid = "Y";	 	 
        						}  	  	  	  	 																	
        					}else
        					{
        					 	//BEASISWA PENDIDIKAN ----------------------------------------
  									if ($ls_beasiswa_jenis!="" && $ls_beasiswa_jenjang_pendidikan!="")
        						{
          						//ambil maksimum berapa kali menerima beasiswa utk jenjang yg sama -----
          						$sql = "select kategori from sijstk.ms_lookup a where tipe=:p_tipe and kode = :p_beasiswa_jenjang_pendidikan";
						$proc = $DB->parse($sql);
						oci_bind_by_name($proc, ':p_tipe', 'TKSKLHPP82');
						oci_bind_by_name($proc, ':p_beasiswa_jenjang_pendidikan', $ls_beasiswa_jenjang_pendidikan);
                      $DB->execute();
                      $row = $DB->nextrow();
                      $ln_max_n_kali	= $row['KATEGORI']; 
          						if ($ln_max_n_kali==""){$ln_max_n_kali="0";}	
          						
                  		$sql = "select sum(nvl(cnt,0)) as v_jml from ".
                             "( ".
                             "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                             "    where kode_manfaat = :p_kode_manfaat ".
                             "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                             "    and nvl(nom_biaya_disetujui,0)<>0 ".
                             "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                             "    and nvl(beasiswa_jenis,'XyZ') = :p_beasiswa_jenis ". 
          									 "    and nvl(beasiswa_jenjang_pendidikan,'XyZ') = :p_beasiswa_jenjang_pendidikan ".
                             "    and kode_klaim in ".
                             "    (  ".
                             "        select kode_klaim from sijstk.pn_klaim  ".
                             "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
                             "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
                             "    ) ".
                             "    UNION ALL ".
                             "    select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
                             "    where kode_manfaat = :p_kode_manfaat ".
                             "    and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                             "    and nvl(nom_biaya_disetujui,0)<>0 ".
                             "    and nvl(beasiswa_nik_penerima,'AbC') = :p_beasiswa_nik_penerima ".
                             "    and nvl(beasiswa_rapel_jenis,'XyZ') = :p_beasiswa_jenis ".
          									 "    and nvl(beasiswa_rapel_jenjang,'XyZ') = :p_beasiswa_jenjang_pendidikan ".
                             "    and kode_klaim in ".
                             "    ( ". 
                             "        select kode_klaim from sijstk.pn_klaim ". 
                             "        start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status ". 
                             "        connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status ". 
                             "    ) ".
                             ")";
						$proc = $DB->parse($sql);
						$param_bv = [
							':p_kode_manfaat' => $ls_kode_manfaat,
							':p_kode_klaim' => $ls_kode_klaim,
							':p_no_urut' => $ln_no_urut,
							':p_beasiswa_nik_penerima' => $ls_beasiswa_nik_penerima,
							':p_beasiswa_jenis' => $ls_beasiswa_jenis,
							':p_beasiswa_jenjang_pendidikan' => $ls_beasiswa_jenjang_pendidikan,
							':p_status' => 'T'
						];
						foreach ($param_bv as $key => $value) {
							oci_bind_by_name($proc, $key, $param_bv[$key]);
						}
                      $DB->execute();
                      $row = $DB->nextrow();
                      $ln_sudah_n_kali	= $row['V_JML'];
                  		if ($ln_sudah_n_kali==""){$ln_sudah_n_kali="0";}						
          						
          						if ($ln_sudah_n_kali>=$ln_max_n_kali)
          						{
          						 	$ls_is_valid = "T";
          							$ln_nom_disetujui = "0";
          							echo "formObj.beasiswa_rapel_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
          							echo "f_ajax_val_total_disetujui();";
          							echo "alert('Jenis beasiswa $ls_beasiswa_jenis sudah pernah diterima sebanyak $ln_sudah_n_kali (maksimum $ln_max_n_kali ) !');";	 																	 
          						}else
          						{
          						 	$ls_is_valid = "Y";	 	 
          						}
        						}else
        						{
        						 	$ls_is_valid = "Y";		 
        						} 					 
        					}
      					}
      					//end cek maksimum berapa kali menerima beasiswa utk jenjang yg sama -						 		 
  						}		 	 
    				}//end utk rapel, maka jenjang pendidikan tidak boleh diatas yg tahun berjalan
					}//end kode_klaim_induk								
				}else
				{
				 	//PMI ----------------------------------------------------------------
  				//utk rapel, maka jenjang pendidikan tidak boleh diatas yg tahun berjalan
  				if (($ls_beasiswa_jenis==$ls_beasiswa_jenis_thnbjln) && ($ls_beasiswa_jenis=="BEASISWA"))
  				{
      			//ambil urut jenjang pendidikan thn berjalan -------------------------
  					$sql = "select seq from sijstk.ms_lookup where tipe=:p_tipe and kode=:p_kode ";
			$proc = $DB->parse($sql);
			oci_bind_by_name($proc, ':p_tipe', 'TKSKOLAH');
			oci_bind_by_name($proc, ':p_kode', $ls_jenjang_pendidikan_thnbjln);
            $DB->execute();
            $row = $DB->nextrow();
            $ln_urut_jenjang_thnbjln	= $row['SEQ'];	
  					
  					//ambil urut jenjang pendidikan thn rapel ----------------------------
  					$sql = "select seq from sijstk.ms_lookup where tipe=:p_tipe and kode=:p_kode ";
			oci_bind_by_name($proc, ':p_tipe', 'TKSKOLAH');
			oci_bind_by_name($proc, ':p_kode', $ls_beasiswa_jenjang_pendidikan);
            $DB->execute();
            $row = $DB->nextrow();
            $ln_urut_jenjang_rapel	= $row['SEQ'];		
  					
  					if ($ln_urut_jenjang_thnbjln<$ln_urut_jenjang_rapel)
  					{
              //Jenjang pendidikan untuk tahun sebelumnya tidak boleh diatas jenjang pendidikan tahun berjalan ---
              echo "formObj.beasiswa_rapel_jenis.value = '';\n";
              echo "formObj.beasiswa_rapel_jenjang.value = '';\n";
              echo "formObj.beasiswa_rapel_tingkat.value = '0';\n";
              echo "formObj.beasiswa_rapel_nom.value = '0';\n";
              echo "f_ajax_val_total_disetujui();";
              
              echo "curr_beasiswa_rapel_jenis = '';\n";
              echo "curr_beasiswa_rapel_jenjang = '';\n";
  						$ls_error = "1";
							$ls_is_valid = "T";				 
              echo "alert('Jenjang pendidikan untuk tahun sebelumnya tidak boleh diatas jenjang pendidikan tahun berjalan ..!');";						
  					}else
						{
						 	$ls_is_valid = "Y";	 
						}		 	 
  				}
  				//end utk rapel, maka jenjang pendidikan tidak boleh diatas yg tahun berjalan						 
				}
				
				if ($ls_error == "0" && $ls_is_valid=="Y")
				{	 
    			$qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_BEASISWA( ".
      				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, ".
      									"		:p_beasiswa_jenis, :p_beasiswa_jenjang_pendidikan, ".
      									"		:p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
          $proc = $DB->parse($qry);
          oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
          oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
          oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
          oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
          oci_bind_by_name($proc, ":p_beasiswa_jenis", $ls_beasiswa_jenis);
          oci_bind_by_name($proc, ":p_beasiswa_jenjang_pendidikan", $ls_beasiswa_jenjang_pendidikan);
          oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
      		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          $DB->execute();				
          $ln_nom_disetujui = $p_nom_disetujui;
      		$ls_sukses = $p_sukses;
      		$ls_mess = $p_mess;
      		
      		if ($ls_sukses=="-1")
      		{
      		 	 echo "formObj.st_errval1.value = '1';\n";	
    				 echo "f_ajax_val_total_disetujui();";		 
      		}else
      		{
      		 	 echo "formObj.st_errval1.value = '0';\n";
      			 echo "formObj.beasiswa_rapel_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
      			 echo "f_ajax_val_total_disetujui();";			 	  		 
      		}
				}else
				{
          $ln_nom_disetujui = "0";
          echo "formObj.beasiswa_rapel_nom.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";
          echo "f_ajax_val_total_disetujui();";				
				}
			}//end if ($ls_tahun<$ls_thn_kejadian)
		}//end if ($ln_exist_thn>="1")				
	}	
	// end hitung manfaat manfaat beasiswa tki/(pu/bpu/jakon pp82)-rapel thn lalu 

	// validasi tgl_awal vokasional ----------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_vokasional_tgl_awal")
	{		
		$ls_kode_klaim 	= $_GET['c_kode_klaim'];
		$ld_tgl_awal 		= $_GET['c_tgl_awal'];

    $sql = "select to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, ".
				 	 "			 nvl(to_char(tgl_kondisi_terakhir,'yyyymmdd'),:p_tgl_akhir) tgl_kondisi_yyyymmdd, ".
					 "			 to_char(to_date(:p_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') tgl_awal ".
				 	 "from sijstk.pn_klaim where kode_klaim = :p_kode_klaim ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_tgl_awal' => $ld_tgl_awal,
		':p_tgl_akhir' => '19000101',
		':p_kode_klaim' => $ls_kode_klaim
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
		$ld_tgl_kondisi_terakhir	= $row['TGL_KONDISI_TERAKHIR'];
    $ls_tgl_kondisi_yyyymmdd	= $row['TGL_KONDISI_YYYYMMDD'];
		$ls_tgl_awal							= $row['TGL_AWAL'];
		
		if ($ls_tgl_awal<$ls_tgl_kondisi_yyyymmdd)
		{
		 	echo "formObj.vokasional_tgl_awal.value = '';\n"; 
      echo "curr_tgl_awal = '';\n";					 
      echo "alert('Tgl Awal Pelatihan $ld_tgl_awal lebih kecil dari tgl kondisi terakhir $ld_tgl_kondisi_terakhir..!');";
		}		
	}	
	// end validasi tgl_awal vokasional ------------------------------------------

	// validasi jenis vokasional -------------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_vokasional_jenis")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
		$ls_jenis 				= $_GET['c_jenis'];
		$ls_jenis_lainnya	= $_GET['c_jenis_lainnya'];

		$sql = "select count(*) cnt from sijstk.pn_klaim_manfaat_detil a ".
           "where kode_manfaat = :p_kode_manfaat ".
           "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
           "and nvl(nom_biaya_disetujui,0)<>0 ".
           "and nvl(vokasional_jenis,'AbC') <> :p_jenis ".
           "and kode_klaim in ".
           "(  ".
           "   select kode_klaim from sijstk.pn_klaim  ".
           "   start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
           "   connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
           ") ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_kode_manfaat' => $ls_kode_manfaat,
		':p_no_urut' => $ln_no_urut,
		':p_kode_klaim' => $ls_kode_klaim,
		':p_jenis' => $ls_jenis,
		':p_status' => 'T'
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ln_exist	= $row['CNT'];
		if ($ln_exist==""){$ln_exist="0";}
		
		if ($ln_exist>"0")
		{
		 	echo "formObj.vokasional_jenis.value = '';\n"; 
			echo "formObj.vokasional_jenis_lainnya.value = '';\n"; 
      echo "curr_jenis = '';\n";		
			echo "curr_jenis_lainnya = '';\n";	
			
			//ambil jenis_vokasional yg sudah pernah ditetapkan ----------------------
  		$sql = "select b.keterangan nm_jenis from sijstk.pn_klaim_manfaat_detil a, sijstk.ms_lookup b ".
             "where a.kode_manfaat = :p_kode_manfaat and b.tipe='TKIVOKJNS' and a.vokasional_jenis = b.kode ".
             "and a.kode_klaim||a.no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
             "and nvl(a.nom_biaya_disetujui,0)<>0 ".
             "and nvl(a.vokasional_jenis,'AbC') <> :p_jenis ".
             "and a.kode_klaim in ".
             "(  ".
             "   select kode_klaim from sijstk.pn_klaim  ".
             "   start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
             "   connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
             ") ".
						 "and rownum = 1 ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_no_urut' => $ln_no_urut,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_jenis' => $ls_jenis,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
      $DB->execute();
      $row = $DB->nextrow();
      $ls_nm_vokasional_jenis	= $row['NM_JENIS'];			
							 
      echo "alert('Pelatihan sudah pernah ditetapkan dengan jenis $ls_nm_vokasional_jenis, tidak dapat ditetapkan dengan jenis pelatihan yang lain..!');";
		}else
		{
		 	//jika pelatihan jenis_lainnya maka detil jenis lainnya tidak boleh beda--
			if ($ls_jenis=="VOK99" && $ls_jenis_lainnya!="")
			{
    		$sql = "select count(*) cnt2 from sijstk.pn_klaim_manfaat_detil a ".
               "where kode_manfaat = :p_kode_manfaat ".
               "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
               "and nvl(nom_biaya_disetujui,0)<>0 ".
               "and nvl(vokasional_jenis,'AbC') = :p_jenis ".
							 "and replace(nvl(vokasional_jenis_lainnya,'XyZ'),' ','') <> replace(:p_jenis_lainnya,' ','') ".
               "and kode_klaim in ".
               "(  ".
               "   select kode_klaim from sijstk.pn_klaim  ".
               "   start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
               "   connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
               ") ".
			   "and rownum = 1 ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_no_urut' => $ln_no_urut,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_jenis' => $ls_jenis,
			':p_jenis_lainnya' => $ls_jenis_lainnya,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ln_exist2	= $row['CNT2'];
    		if ($ln_exist2==""){$ln_exist2="0";}	
				
    		if ($ln_exist2>"0")
    		{
    		 	echo "formObj.vokasional_jenis_lainnya.value = '';\n"; 
          echo "curr_jenis_lainnya = '';\n";	
    			
    			//ambil jenis_vokasional yg sudah pernah ditetapkan ----------------------
      		$sql = "select a.vokasional_jenis_lainnya from sijstk.pn_klaim_manfaat_detil a ".
                 "where kode_manfaat = :p_kode_manfaat ".
                 "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,999999) ".
                 "and nvl(nom_biaya_disetujui,0)<>0 ".
                 "and nvl(vokasional_jenis,'AbC') = :p_jenis ".
  							 "and replace(nvl(vokasional_jenis_lainnya,'XyZ'),' ','') <> replace(:p_jenis_lainnya,' ','') ".
                 "and a.kode_klaim in ".
                 "(  ".
                 "   select kode_klaim from sijstk.pn_klaim  ".
                 "   start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
                 "   connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
                 ") ".
    						 "and rownum = 1 ";
				$param_bv = [
				':p_kode_manfaat' => $ls_kode_manfaat,
				':p_kode_klaim' => $ls_kode_klaim,
				':p_no_urut' => $ln_no_urut,
				':p_jenis' => $ls_jenis,
				':p_jenis_lainnya' => $ls_jenis_lainnya,
				':p_status' => 'T'
			];
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
          $DB->execute();
          $row = $DB->nextrow();
          $ls_nm_vokasional_jenis_lainnya	= $row['VOKASIONAL_JENIS_LAINNYA'];			
    							 
          echo "alert('Pelatihan sudah pernah ditetapkan dengan jenis $ls_nm_vokasional_jenis_lainnya, tidak dapat ditetapkan dengan jenis pelatihan yang lain..!');";
    		}	//end if ($ln_exist2>"0")			
						 	 
			}	//end if ($ls_jenis=="VOK99" && $ls_jenis_lainnya!="")  		 
		}	//end if ($ln_exist>"0")	
	}	
	// end validasi jenis vokasional ---------------------------------------------
	
	// hitung manfaat manfaat vokasional -----------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_vokasional")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_klaim 		 		 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];

    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIVOKASIONAL( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_nom_biaya_diajukan, :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_nom_biaya_diajukan", $ln_nom_biaya_diajukan);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses!="1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";	
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_biaya_diajukan,2,".",",")."';\n";		 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat vokasional ---------------------------------------------	
	
	// validasi periode kontrak --------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_phk_tgl_kontrak")
	{		
		$ld_tgl_awal 	= $_GET['c_tgl_awal'];
		$ld_tgl_akhir = $_GET['c_tgl_akhir'];
		$ls_kode_klaim = $_GET['c_kode_klaim'];

		$sql = "select to_char (to_date (:p_tgl_awal, 'dd/mm/yyyy'), 'yyyymmdd')
		tgl_awal,
	to_char (to_date (:p_tgl_akhir, 'dd/mm/yyyy'), 'yyyymmdd')
		tgl_akhir,
	to_char(tgl_awal_perlindungan,'yyyymmdd') tgl_awal_perlindungan,
	to_char(tgl_akhir_perlindungan,'yyyymmdd') tgl_akhir_perlindungan,
	months_between (tgl_akhir_perlindungan, tgl_awal_perlindungan)
		jml_bulan_perlindungan,
	to_char (
		add_months (
			to_date (:p_tgl_awal, 'dd/mm/yyyy'),
			months_between (tgl_akhir_perlindungan, tgl_awal_perlindungan)),
		'yyyymmdd')
		max_tgl_akhir
from pn.pn_klaim
where kode_klaim = :p_kode_klaim";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_tgl_awal' => $ld_tgl_awal,
		':p_tgl_akhir' => $ld_tgl_akhir,
		':p_kode_klaim' => $ls_kode_klaim
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_awal	= $row['TGL_AWAL'];		
	$ls_tgl_akhir	= $row['TGL_AKHIR'];
	$ls_tgl_awal_perlindungan	= $row['TGL_AWAL_PERLINDUNGAN'];
	$ls_tgl_akhir_perlindungan	= $row['TGL_AKHIR_PERLINDUNGAN'];
	$ls_jml_bulan_perlindungan	= $row['JML_BULAN_PERLINDUNGAN'];
	$ls_max_tgl_akhir			= $row['MAX_TGL_AKHIR'];
		
		if ($ls_tgl_akhir<$ls_tgl_awal)
		{
			echo "formObj.phk_tgl_pk_akhir.value = '';\n";
			echo "curr_tgl_akhir = '';\n";
			echo "formObj.phk_tgl_pk_awal.value = '';\n";
			echo "curr_tgl_awal = '';\n";		
			 
			 echo "alert('Tgl akhir kontrak tidak boleh lebih kecil dari tgl awal kontrak..!');";
		}else if ($ls_tgl_awal < $ls_tgl_awal_perlindungan)
		{
			echo "formObj.phk_tgl_pk_akhir.value = '';\n";
			echo "curr_tgl_akhir = '';\n";
			echo "formObj.phk_tgl_pk_awal.value = '';\n";
			echo "curr_tgl_awal = '';\n";		
			
			echo "alert('Tgl awal kontrak tidak boleh lebih kecil dari tgl efektif perlindungan TK..!');";
		}else if ($ls_tgl_akhir > $ls_max_tgl_akhir)
		{
			echo "formObj.phk_tgl_pk_akhir.value = '';\n";
			echo "curr_tgl_akhir = '';\n";
			echo "formObj.phk_tgl_pk_awal.value = '';\n";
			echo "curr_tgl_awal = '';\n";		
			
			echo "alert('Rentang tgl awal kontrak dan akhir kontrak tidak boleh melebihi jumlah bulan perlindungan TK..!');";
		}else
		{
			echo "curr_tgl_phk = '';\n";
			echo "f_ajax_val_tgl_phk();";	 
		}			
	}	
	// end validasi periode kontrak ----------------------------------------------

		// validasi periode kontrak2 --------------------------------------------------
		if ($_GET['getClientId']=="f_ajax_val_phk_tgl_kontrak2")
		{		
			$ld_tgl_awal 	= $_GET['c_tgl_awal'];
			$ld_tgl_akhir = $_GET['c_tgl_akhir'];
			$ls_kode_klaim = $_GET['c_kode_klaim'];
	
			$sql = "select to_char (to_date (:p_tgl_awal, 'dd/mm/yyyy'), 'yyyymmdd')
			tgl_awal,
		to_char (to_date (:p_tgl_akhir, 'dd/mm/yyyy'), 'yyyymmdd')
			tgl_akhir,
		to_char(tgl_awal_perlindungan,'yyyymmdd') tgl_awal_perlindungan,
		to_char(tgl_akhir_perlindungan,'yyyymmdd') tgl_akhir_perlindungan,
		months_between (tgl_akhir_perlindungan, tgl_awal_perlindungan)
			jml_bulan_perlindungan,
		to_char (
			add_months (
				to_date (:p_tgl_awal, 'dd/mm/yyyy'),
				months_between (tgl_akhir_perlindungan, tgl_awal_perlindungan)),
			'yyyymmdd')
			max_tgl_akhir
	from pn.pn_klaim
	where kode_klaim = :p_kode_klaim";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_tgl_awal' => $ld_tgl_awal,
			':p_tgl_akhir' => $ld_tgl_akhir,
			':p_kode_klaim' => $ls_kode_klaim
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
		$DB->execute();
		$row = $DB->nextrow();
		$ls_tgl_awal	= $row['TGL_AWAL'];		
		$ls_tgl_akhir	= $row['TGL_AKHIR'];
		$ls_tgl_awal_perlindungan	= $row['TGL_AWAL_PERLINDUNGAN'];
		$ls_tgl_akhir_perlindungan	= $row['TGL_AKHIR_PERLINDUNGAN'];
		$ls_jml_bulan_perlindungan	= $row['JML_BULAN_PERLINDUNGAN'];
		$ls_max_tgl_akhir			= $row['MAX_TGL_AKHIR'];
			
			if ($ls_tgl_akhir<$ls_tgl_awal)
			{
				echo "formObj.phk_tgl_pk_akhir.value = '';\n";
				echo "curr_tgl_akhir = '';\n";
				echo "formObj.phk_tgl_pk_awal.value = '';\n";
				echo "curr_tgl_awal = '';\n";		
				 
				 echo "alert('Tgl akhir kontrak tidak boleh lebih kecil dari tgl awal kontrak..!');";
			}else if ($ls_tgl_awal < $ls_tgl_awal_perlindungan)
			{
				echo "formObj.phk_tgl_pk_akhir.value = '';\n";
				echo "curr_tgl_akhir = '';\n";
				echo "formObj.phk_tgl_pk_awal.value = '';\n";
				echo "curr_tgl_awal = '';\n";		
				
				echo "alert('Tgl awal kontrak tidak boleh lebih kecil dari tgl efektif perlindungan TK..!');";
			}else
			{
				echo "curr_tgl_phk = '';\n";
				echo "f_ajax_val_tgl_phk();";	 
			}			
		}	
		// end validasi periode kontrak2 ----------------------------------------------
	
	// validasi tgl phk ----------------------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_phk_tgl_phk")
	{		
		$ls_kode_klaim 	= $_GET['c_kode_klaim'];
		$ld_tgl_awal 	= $_GET['c_tgl_awal'];
		$ld_tgl_akhir = $_GET['c_tgl_akhir'];
		$ld_tgl_phk 	= $_GET['c_tgl_phk'];

		$sql = "select to_char(to_date(:p_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') tgl_awal, to_char(to_date(:p_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd') tgl_akhir, ".
				 	 "			 to_char(to_date(:p_tgl_phk,'dd/mm/yyyy'),'yyyymmdd') tgl_phk, to_char(tgl_kejadian,'yyyymmdd') tgl_kejadian_jkk, ".
					  "			 to_char(sysdate,'yyyymmdd') tgl_sysdate_yyyymmdd, to_char(add_months(to_date(:p_tgl_akhir,'dd/mm/yyyy'),-1),'yyyymmdd') tgl_max_layak  ".
           "from sijstk.pn_klaim ".
					 "where kode_klaim = :p_kode_klaim ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_tgl_awal' => $ld_tgl_awal,
		':p_tgl_akhir' => $ld_tgl_akhir,
		':p_tgl_phk' => $ld_tgl_phk,
		':p_kode_klaim' => $ls_kode_klaim
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_awal	= $row['TGL_AWAL'];		
		$ls_tgl_akhir	= $row['TGL_AKHIR'];
		$ls_tgl_phk		= $row['TGL_PHK'];
		$ls_tgl_kejadian_jkk		= $row['TGL_KEJADIAN_JKK'];
		$ls_tgl_sysdate_yyyymmdd		= $row['TGL_SYSDATE_YYYYMMDD'];
		$ls_tgl_max_tgl_layak			= $row['TGL_MAX_LAYAK'];
		
		if ($ls_tgl_phk>$ls_tgl_sysdate_yyyymmdd)
		{
		 	 echo "formObj.phk_tgl.value = '';\n";
  		 echo "curr_tgl_phk = '';\n";	
  		 	 
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
  		 echo "alert('Tgl PHK tidak boleh lebih besar dari hari ini..!');";
		}else
		{
  		if ($ls_tgl_phk>=$ls_tgl_awal && $ls_tgl_phk<=$ls_tgl_akhir)
  		{
  		 	 if ($ls_tgl_phk<$ls_tgl_kejadian_jkk)
				 {
  				 echo "formObj.phk_tgl.value = '';\n";
    			 echo "curr_tgl_phk = '';\n";	
					 
    			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
    			 echo "alert('Tgl PHK tidak boleh lebih kecil dari tanggal kejadian..!');";
				 }
				else
				 {
				 	echo "f_ajax_val_hitung_manfaat();";
				 }
  		}else
  		{	 
  			 echo "formObj.phk_tgl.value = '';\n";
  			 echo "curr_tgl_phk = '';\n";	
  			 
				 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
  			 echo "alert('Tgl PHK harus dalam rentang periode kontrak..!');";
  		}
		}			
	}	
	// end validasi tgl phk ------------------------------------------------------
	
	
	// validasi tgl phk ----------------------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_phk_tgl_phk2")
	{		
		$ls_kode_klaim 	= $_GET['c_kode_klaim'];
		$ld_tgl_awal 	= $_GET['c_tgl_awal'];
		$ld_tgl_akhir = $_GET['c_tgl_akhir'];
		$ld_tgl_phk 	= $_GET['c_tgl_phk'];

		$sql = "select to_char(to_date(:p_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') tgl_awal, to_char(to_date(:p_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd') tgl_akhir, ".
				 	 "			 to_char(to_date(:p_tgl_phk,'dd/mm/yyyy'),'yyyymmdd') tgl_phk, to_char(tgl_kejadian,'yyyymmdd') tgl_kejadian_jkk, ".
					 "			 to_char(sysdate,'yyyymmdd') tgl_sysdate_yyyymmdd, to_char(add_months(to_date(:p_tgl_akhir,'dd/mm/yyyy'),-1),'yyyymmdd') tgl_max_layak  ".
           "from sijstk.pn_klaim ".
					 "where kode_klaim = :p_kode_klaim ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_tgl_awal' => $ld_tgl_awal,
		':p_tgl_akhir' => $ld_tgl_akhir,
		':p_tgl_phk' => $ld_tgl_phk,
		':p_kode_klaim' => $ls_kode_klaim
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_awal	= $row['TGL_AWAL'];		
		$ls_tgl_akhir	= $row['TGL_AKHIR'];
		$ls_tgl_phk		= $row['TGL_PHK'];
		$ls_tgl_kejadian_jkk		= $row['TGL_KEJADIAN_JKK'];
		$ls_tgl_sysdate_yyyymmdd		= $row['TGL_SYSDATE_YYYYMMDD'];
		$ls_tgl_max_tgl_layak			= $row['TGL_MAX_LAYAK'];
		
		if ($ls_tgl_phk>$ls_tgl_sysdate_yyyymmdd)
		{
		 	 echo "formObj.phk_tgl.value = '';\n";
  		 echo "curr_tgl_phk = '';\n";	
  		 	 
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
  		 echo "alert('Tgl PHK tidak boleh lebih besar dari hari ini..!');";
		}else
		{
  		if ($ls_tgl_phk>=$ls_tgl_awal && $ls_tgl_phk<=$ls_tgl_akhir)
  		{
  		 	 if ($ls_tgl_phk<$ls_tgl_kejadian_jkk)
				 {
  				 echo "formObj.phk_tgl.value = '';\n";
    			 echo "curr_tgl_phk = '';\n";	
					 
    			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
    			 echo "alert('Tgl PHK tidak boleh lebih kecil dari tanggal kejadian..!');";
				}else if($ls_tgl_phk > $ls_tgl_max_tgl_layak ){
					echo "formObj.phk_tgl.value = '';\n";
					echo "curr_tgl_phk = '';\n";	
						
					echo "formObj.ket_phk_kode_blnphk.value = '';\n";
					echo "alert('Tanggal PHK harus berada di periode kontrak dan tidak boleh kurang dari satu bulan sebelum perjanjian kerja berakhir..!');";
				 }else if($ls_tgl_phk != $ls_tgl_kejadian_jkk ){
					echo "formObj.phk_tgl.value = '';\n";
					echo "curr_tgl_phk = '';\n";	
						
					echo "formObj.ket_phk_kode_blnphk.value = '';\n";
					echo "alert('Tanggal PHK harus sama dengan tanggal kejadian..!');";
				 }else
				 {
				 		echo "f_ajax_val_hitung_manfaat();";
				 }
  		}else
  		{	 
  			 echo "formObj.phk_tgl.value = '';\n";
  			 echo "curr_tgl_phk = '';\n";	
  			 
				 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
  			 echo "alert('Tgl PHK harus dalam rentang periode kontrak..!');";
  		}
		}			
	}	
	// end validasi tgl phk ------------------------------------------------------		

	// hitung manfaat phk --------------------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkiphk")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_klaim 		 		 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];
		$ld_tgl_awal 					 = $_GET['c_tgl_awal'];
		$ld_tgl_akhir 				 = $_GET['c_tgl_akhir'];
		$ld_tgl_phk 					 = $_GET['c_tgl_phk'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIPHK( ".
            "		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, ".
            "		to_date(:p_tgl_awal,'dd/mm/yyyy'), to_date(:p_tgl_akhir,'dd/mm/yyyy'), to_date(:p_tgl_phk,'dd/mm/yyyy'), ".
            "		:p_kode_blnphk, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_tgl_awal", $ld_tgl_awal);
    oci_bind_by_name($proc, ":p_tgl_akhir", $ld_tgl_akhir);
    oci_bind_by_name($proc, ":p_tgl_phk", $ld_tgl_phk);
    oci_bind_by_name($proc, ":p_kode_blnphk", $p_kode_blnphk,32);
		oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
		$ls_kode_blnphk = $p_kode_blnphk;
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-2")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";
		 	 echo "formObj.phk_tgl.value = '';\n";
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
			 echo "curr_tgl_phk = '';\n";		
			 echo "alert('$ls_mess');";			 
		}else if ($ls_sukses=="-3")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";		
		 	 echo "formObj.phk_tgl.value = '';\n";
			 echo "curr_tgl_phk = '';\n";		
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
			 echo "alert('$ls_mess');";			
		}else if ($ls_sukses=="-4")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";		
		 	 echo "formObj.phk_tgl.value = '';\n";
			 echo "curr_tgl_phk = '';\n";	
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";	
			 echo "alert('$ls_mess');";				 
		}else if ($ls_sukses=="-1")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";		
		 	 echo "formObj.st_errval1.value = '1';\n";
			 echo "formObj.nom_biaya_disetujui.value = '0';\n";		 		 
		}else
		{
        //ambil keterangan masa kerja sampai dg phk -----------------------------
        $sql = "select a.keterangan from sijstk.pn_kode_blnphk a, sijstk.pn_klaim b ".
               "where a.kode_segmen = b.kode_segmen ".
               "and a.kode_perlindungan = b.kode_perlindungan ".
							 "and b.kode_klaim = :p_kode_klaim ".
               "and a.kode_blnphk = :p_kode_blnphk ".
							 "and to_char(to_date(:p_tgl_phk,'dd/mm/yyyy'),'yyyymmdd') between to_char(a.tgl_efektif,'yyyymmdd') and to_char(a.tgl_akhir,'yyyymmdd') ".
               "and rownum = 1 ";
        $proc = $DB->parse($sql);
		oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
		oci_bind_by_name($proc, ":p_tgl_phk", $ld_tgl_phk);
		oci_bind_by_name($proc, ":p_kode_blnphk", $ls_kode_blnphk);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_ket_blnphk	= $row['KETERANGAN'];	
        
        echo "formObj.st_errval1.value = '0';\n";
        echo "formObj.phk_kode_blnphk.value = '".$ls_kode_blnphk."';\n";
				echo "formObj.ket_phk_kode_blnphk.value = '".$ls_ket_blnphk."';\n";
        echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat vokasional ---------------------------------------------
	
	// hitung manfaat phk 2 --------------------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkiphk2")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_klaim 		 		 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];
		$ld_tgl_awal 					 = $_GET['c_tgl_awal'];
		$ld_tgl_akhir 				 = $_GET['c_tgl_akhir'];
		$ld_tgl_phk 					 = $_GET['c_tgl_phk'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIPHKBKNAKIBATKK( ".
            "		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, ".
            "		to_date(:p_tgl_awal,'dd/mm/yyyy'), to_date(:p_tgl_akhir,'dd/mm/yyyy'), to_date(:p_tgl_phk,'dd/mm/yyyy'), ".
            "		:p_kode_blnphk, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_tgl_awal", $ld_tgl_awal);
    oci_bind_by_name($proc, ":p_tgl_akhir", $ld_tgl_akhir);
    oci_bind_by_name($proc, ":p_tgl_phk", $ld_tgl_phk);
    oci_bind_by_name($proc, ":p_kode_blnphk", $p_kode_blnphk,32);
		oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
		$ls_kode_blnphk = $p_kode_blnphk;
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-2")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";
		 	 echo "formObj.phk_tgl.value = '';\n";
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
			 echo "curr_tgl_phk = '';\n";		
			 echo "alert('$ls_mess');";			 
		}else if ($ls_sukses=="-3")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";		
		 	 echo "formObj.phk_tgl.value = '';\n";
			 echo "curr_tgl_phk = '';\n";		
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";
			 echo "alert('$ls_mess');";			
		}else if ($ls_sukses=="-4")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";		
		 	 echo "formObj.phk_tgl.value = '';\n";
			 echo "curr_tgl_phk = '';\n";	
			 echo "formObj.ket_phk_kode_blnphk.value = '';\n";	
			 echo "alert('$ls_mess');";				 
		}else if ($ls_sukses=="-1")
		{
		 	 echo "formObj.nom_biaya_disetujui.value = '0';\n";		
		 	 echo "formObj.st_errval1.value = '1';\n";
			 echo "formObj.nom_biaya_disetujui.value = '0';\n";		 		 
		}else
		{
        //ambil keterangan masa kerja sampai dg phk -----------------------------
        $sql = "select a.keterangan from sijstk.pn_kode_blnphk a, sijstk.pn_klaim b ".
               "where a.kode_segmen = b.kode_segmen ".
               "and a.kode_perlindungan = b.kode_perlindungan ".
							 "and b.kode_klaim = :p_kode_klaim ".
               "and a.kode_blnphk = :p_kode_blnphk ".
							 "and to_char(to_date(:p_tgl_phk,'dd/mm/yyyy'),'yyyymmdd') between to_char(a.tgl_efektif,'yyyymmdd') and to_char(a.tgl_akhir,'yyyymmdd') ".
               "and rownum = 1 ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_klaim' => $ls_kode_klaim,
			':p_kode_blnphk' => $ls_kode_blnphk,
			':p_tgl_phk' => $ld_tgl_phk
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ls_ket_blnphk	= $row['KETERANGAN'];	
        
        echo "formObj.st_errval1.value = '0';\n";
        echo "formObj.phk_kode_blnphk.value = '".$ls_kode_blnphk."';\n";
				echo "formObj.ket_phk_kode_blnphk.value = '".$ls_ket_blnphk."';\n";
        echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat phk2 ---------------------------------------------
	// cek periode homecare ------------------------------------------------------
	//update 25/12/2019 - PP no 82 tahun 2019 ------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_periode_homecare")
	{		
		$ls_kode_klaim 		 = $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	 = $_GET['c_kode_manfaat'];
		$ln_no_urut 		 	 = $_GET['c_no_urut'];
		$ld_homecare_tgl_awal  = $_GET['c_homecare_tgl_awal'];
		$ld_homecare_tgl_akhir = $_GET['c_homecare_tgl_akhir'];
		
		//pp82 ---------------------------------------------------------------------
		//valid jika:
		//Tanggal awal homecare >= tanggal kejadian
		//Tanggal akhir homecare <= tanggal kondisi akhir

		//validasi tgl_awal_homecare tidak boleh lebih kecil dari tgl_kejadian ---------
		//update 22/12/2019 --------------------------------------------------------
		$sql ="select count(*) as v_jml from sijstk.pn_klaim ".
				 	"where kode_klaim = :p_kode_klaim ".
				  "and to_char(to_date('$ld_homecare_tgl_awal','dd/mm/yyyy'), 'yyyymmdd') < to_char(tgl_kejadian, 'yyyymmdd')";
		$DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ln_cnt4	= $row['V_JML'];
		if ($ln_cnt4==""){$ln_cnt4="0";}

		if($ln_cnt4>0){
			echo "formObj.st_errval4.value = '1';\n";		 
		}else{
			echo "formObj.st_errval4.value = '0';\n";
		}
		
		//validasi tgl_akhir_homecare tidak boleh lebih besar dari tgl_kondisi terakhir-
		$sql ="select count(*) as v_jml from sijstk.pn_klaim ".
  				"where kode_klaim = :p_kode_klaim ".
				  "and to_char(to_date(:p_homecare_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd') > to_char(tgl_kondisi_terakhir, 'yyyymmdd')";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_kode_klaim' => $ls_kode_klaim,
		':p_homecare_tgl_akhir' => $ld_homecare_tgl_akhir
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ln_cnt5	= $row['V_JML'];
		if ($ln_cnt5==""){$ln_cnt5="0";}

		if($ln_cnt5>0){
			echo "formObj.st_errval5.value = '1';\n";		 
		}else{
			echo "formObj.st_errval5.value = '0';\n";
		}
		//--------------------------------------------------------------------------

		//validasi tgl awal skrg terhadap penetapan sebelumnya ---------------------	
    $sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil ".
           "where kode_manfaat = :p_kode_manfaat ". 
           "and nvl(nom_biaya_disetujui,0)<>0 ". 
           "and to_char(to_date(:p_homecare_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') between to_char(homecare_tgl_awal,'yyyymmdd') and to_char(homecare_tgl_akhir,'yyyymmdd') ".
           "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,'99999') ".
           "and kode_klaim in  ".
           "(  ".
           "    select kode_klaim from sijstk.pn_klaim  ".
           "    start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
           "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
           ") ";
	$proc = $DB->parse($sql);
	$param_bv = [
		':p_kode_manfaat' => $ls_kode_manfaat,
		':p_kode_klaim' => $ls_kode_klaim,
		':p_no_urut' => $ln_no_urut,
		':p_homecare_tgl_awal' => $ld_homecare_tgl_awal,
		':p_status' => 'T'
	];
	foreach ($param_bv as $key => $value) {
		oci_bind_by_name($proc, $key, $param_bv[$key]);
	}
    $DB->execute();
    $row = $DB->nextrow();
    $ln_exist_tglawal	= $row['V_JML'];
		if ($ln_exist_tglawal==""){$ln_exist_tglawal="0";}
		
		if ($ln_exist_tglawal>="1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";

  		//validasi tgl akhir  skrg terhadap penetapan sebelumnya -----------------	
      $sql = "select count(*) as v_jml2 from sijstk.pn_klaim_manfaat_detil ".
             "where kode_manfaat = :p_kode_manfaat ". 
             "and nvl(nom_biaya_disetujui,0)<>0 ". 
             "and to_char(to_date(:p_homecare_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd') between to_char(homecare_tgl_awal,'yyyymmdd') and to_char(homecare_tgl_akhir,'yyyymmdd') ".
             "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,'99999') ".
             "and kode_klaim in  ".
             "(  ".
             "    select kode_klaim from sijstk.pn_klaim  ".
             "    start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
             "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
             ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_no_urut' => $ln_no_urut,
			':p_homecare_tgl_akhir' => $ld_homecare_tgl_akhir,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
      $DB->execute();
      $row = $DB->nextrow();
      $ln_exist_tglakhir	= $row['V_JML2'];
  		if ($ln_exist_tglakhir==""){$ln_exist_tglakhir="0";}
			
  		if ($ln_exist_tglakhir>="1")
  		{
  		 	 echo "formObj.st_errval2.value = '1';\n";			 
  		}else
  		{
  		 	 echo "formObj.st_errval2.value = '0';\n";

    		//validasi tgl sebelumnya terhadap penetapan skrg ----------------------	
        $sql = "select count(*) as v_jml3 from sijstk.pn_klaim_manfaat_detil ".
               "where kode_manfaat = :p_kode_manfaat ". 
               "and nvl(nom_biaya_disetujui,0)<>0 ". 
               "and ( ".
							 "       (to_char(homecare_tgl_awal,'yyyymmdd') between to_char(to_date(:p_homecare_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') and to_char(to_date(:p_homecare_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd')) ".
							 "			 or ".
							 "       (to_char(homecare_tgl_akhir,'yyyymmdd') between to_char(to_date(:p_homecare_tgl_awal,'dd/mm/yyyy'),'yyyymmdd') and to_char(to_date(:p_homecare_tgl_akhir,'dd/mm/yyyy'),'yyyymmdd')) ". 
							 ") ".
               "and kode_klaim||no_urut <> :p_kode_klaim||nvl(:p_no_urut,'99999') ".
               "and kode_klaim in  ".
               "(  ".
               "    select kode_klaim from sijstk.pn_klaim  ".
               "    start with kode_klaim = :p_kode_klaim and nvl(status_batal,'T')=:p_status  ".
               "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')=:p_status  ".
               ") ";
		$proc = $DB->parse($sql);
		$param_bv = [
			':p_kode_manfaat' => $ls_kode_manfaat,
			':p_kode_klaim' => $ls_kode_klaim,
			':p_no_urut' => $ln_no_urut,
			':p_homecare_tgl_awal' => $ld_homecare_tgl_awal,
			':p_homecare_tgl_akhir' => $ld_homecare_tgl_akhir,
			':p_status' => 'T'
		];
		foreach ($param_bv as $key => $value) {
			oci_bind_by_name($proc, $key, $param_bv[$key]);
		}
        $DB->execute();
        $row = $DB->nextrow();
        $ln_exist_tglsblm	= $row['V_JML3'];
    		if ($ln_exist_tglsblm==""){$ln_exist_tglsblm="0";}

    		if ($ln_exist_tglsblm>="1")
    		{
    		 	echo "formObj.st_errval3.value = '1';\n";			 
    		}else
    		{
    		 	echo "formObj.st_errval3.value = '0';\n";
					//validasi tgl akhir tidak boleh lebih kecil dari tgl awal ----------- 
      		if ($ld_homecare_tgl_awal!="" && $ld_homecare_tgl_akhir!="")
					{
  					$sql ="select to_char(to_date(:p_homecare_tgl_awal,'dd/mm/yyyy'), 'yyyymmdd') tglawal, ".
  							  " 			to_char(to_date(:p_homecare_tgl_akhir,'dd/mm/yyyy'), 'yyyymmdd') tglakhir ".
  								"from dual";
			$proc = $DB->parse($sql);
			$param_bv = [
				':p_homecare_tgl_awal' => $ld_homecare_tgl_awal,
				':p_homecare_tgl_akhir' => $ld_homecare_tgl_akhir,
			];
			foreach ($param_bv as $key => $value) {
				oci_bind_by_name($proc, $key, $param_bv[$key]);
			}
            $DB->execute();
            $row = $DB->nextrow();
            $ls_tglawal	 = $row['TGLAWAL'];
        		$ls_tglakhir = $row['TGLAKHIR'];
        
        		if($ls_tglakhir<$ls_tglawal){
        			echo "formObj.st_errval6.value = '1';\n";		 
        		}else{
        			echo "formObj.st_errval6.value = '0';\n";
        		}
					}					 
  			}	 
  		}	 					 	  		 
		}
	}
	// end cek periode homecare --------------------------------------------------			
	
	//hitung manfaat homecare ----------------------------------------------------			
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_homecare")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diverifikasi = $_GET['c_nom_biaya_diverifikasi'];
		$ls_kode_klaim 			 	 = $_GET['c_kode_klaim'];
		$ln_no_urut 		 	 		 = $_GET['c_no_urut'];
		$ld_homecare_tgl_awal  = $_GET['c_homecare_tgl_awal'];
		$ld_homecare_tgl_akhir = $_GET['c_homecare_tgl_akhir'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_HOMECARE( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, ".
									"		to_date(:p_homecare_tgl_awal,'dd/mm/yyyy'), ".
									"		to_date(:p_homecare_tgl_akhir','dd/mm/yyyy'), ".
									"		:p_nom_biaya_diverifikasi, ".
									"		:p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_homecare_tgl_awal", $ld_homecare_tgl_awal);
    oci_bind_by_name($proc, ":p_homecare_tgl_akhir", $ld_homecare_tgl_akhir);
    oci_bind_by_name($proc, ":p_nom_biaya_diverifikasi", $ln_nom_biaya_diverifikasi);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval7.value = '1';\n";	
		 	 echo "alert('$ls_mess');";		 
		}else
		{
			 echo "formObj.st_errval7.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat homecare -----------------------------------------------																
	// -------------- end HITUNG MANFAAT -----------------------------------------		
	// hitung manfaat manfaat bantuan akibat pemerkosaan ----------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_tkibantuanakibatpemerkosaan")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ln_no_urut 		 	= $_GET['c_no_urut'];
																										
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_TKIAKBTPMRKOSAAN( ".
				 	 				"		:p_kode_klaim, :p_no_urut, :p_kode_manfaat, :p_kd_prg, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
	oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim);
    oci_bind_by_name($proc, ":p_no_urut", $ln_no_urut);
    oci_bind_by_name($proc, ":p_kode_manfaat", $ls_kode_manfaat);
    oci_bind_by_name($proc, ":p_kd_prg", $ls_kd_prg);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses=="-1")
		{
		 	 echo "formObj.st_errval1.value = '1';\n";			 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat bantuan akibat pemerkosaan -------------------------------		
}
?>		