<?php
//Created @ 01/02/2008 to build ajax process enable
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

if(isset($_GET['getClientId']))
{  
	// -------------- VALIDASI INFORMASI KLAIM -----------------------------------
	// validasi kpj --------------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kpj")
	{
		$ls_kpj  				= strtoupper($_GET['c_kpj']);
		$ls_kode_segmen = strtoupper($_GET['c_kode_segmen']);
		$ls_kode_tk 		= strtoupper($_GET['c_kode_tk']);
		
		//1. cek apakah kpj valid atau tidak --------------------------------------
   $sql = 	"select count(*) as v_cnt from ".
						"( ".
  					"	select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, ". 
            "  			 kode_kantor, kode_divisi, nama_divisi ".
            "  from ".
            "  ( ".
            "    select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, kode_kantor, kode_divisi, ". 
            "    				(select nama_divisi from kn.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_divisi = a.kode_divisi) nama_divisi,kode_na, ".
            "    				rank() over (partition by kode_kepesertaan,kode_tk order by kode_kepesertaan,kode_tk, no_mutasi desc) rank ". 
            "    from sijstk.vw_kn_tk a ". 
            "    where a.kpj = '$ls_kpj' ". 
            "    and a.kode_segmen = '$ls_kode_segmen' ". 
            "  ) ".
            "  where rank = 1 ".
            "  and nvl(kode_na,'XXXXX') <> 'AG' ".
						")";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();
		$ln_cnt = $row["V_CNT"];
		
		if ($ln_cnt==0)
		{
		 	echo "formObj.st_errval3.value = '1';\n";		
			echo "formObj.kode_tk.value = '';\n";	
			echo "formObj.nama_tk.value = '';\n";	
			echo "formObj.nomor_identitas.value = '';\n";	
			echo "formObj.jenis_identitas.value = '';\n";	
			echo "formObj.kode_kantor_tk.value = '';\n";	
			echo "formObj.kode_perusahaan.value = '';\n";
			echo "formObj.npp.value = '';\n";
			echo "formObj.nama_perusahaan.value = '';\n";	
			echo "formObj.kode_divisi.value = '';\n";
			echo "formObj.nama_divisi.value = '';\n";
				
	 		echo "fl_js_get_lov_by_kpj();"; 		
		}else
		{
		 	//cek apakah 1 kpj lebih dari 1 kode tk, jika ya maka pilih dari lov -----
     $sql = 	"select count(distinct kode_tk) as v_cnt from ".
  						"( ".
    					"	select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, ". 
              "  			 kode_kantor, kode_divisi, nama_divisi ".
              "  from ".
              "  ( ".
              "    select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, kode_kantor, kode_divisi, ". 
              "    				(select nama_divisi from kn.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_divisi = a.kode_divisi) nama_divisi,kode_na, ".
              "    				rank() over (partition by kode_kepesertaan,kode_tk order by kode_kepesertaan,kode_tk, no_mutasi desc) rank ". 
              "    from sijstk.vw_kn_tk a ". 
              "    where a.kpj = '$ls_kpj' and a.kode_tk like nvl('$ls_kode_tk','%') ". 
              "    and a.kode_segmen = '$ls_kode_segmen' ". 
              "  ) ".
              "  where rank = 1 ".
              "  and nvl(kode_na,'XXXXX') <> 'AG' ".
  						")";
  		$DB->parse($sql);	
  		$DB->execute();
  		$row = $DB->nextrow();
  		$ln_cnt = $row["V_CNT"];
  		
  		if ($ln_cnt==1)
  		{						
  			echo "formObj.st_errval3.value = '0';\n";
  			//ambil nama tk --------------------------------------------------------
    		$sql = 	"	select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, ". 
                "  			 kode_kantor, kode_divisi, nama_divisi ".
                "  from ".
                "  ( ".
                "    select kode_kepesertaan, no_mutasi, kode_tk, nama_tk, nomor_identitas, jenis_identitas, kode_perusahaan, npp, nama_perusahaan, kode_kantor, kode_divisi, ". 
                "    				(select nama_divisi from kn.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_divisi = a.kode_divisi) nama_divisi,kode_na, ".
                "    				rank() over (partition by kode_kepesertaan,kode_tk order by kode_kepesertaan,kode_tk, no_mutasi desc) rank ". 
                "    from sijstk.vw_kn_tk a ". 
                "    where a.kpj = '$ls_kpj' and a.kode_tk like nvl('$ls_kode_tk','%') ". 
                "    and a.kode_segmen = '$ls_kode_segmen' ". 
                "  ) ".
                "  where rank = 1 ".
                "  and nvl(kode_na,'XXXXX') <> 'AG' ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ls_kode_tk 				= $row["KODE_TK"];
  			$ls_nama_tk 				= $row["NAMA_TK"];
  			$ls_nomor_identitas = $row["NOMOR_IDENTITAS"];
  			$ls_jenis_identitas = $row["JENIS_IDENTITAS"];
  			$ls_kode_perusahaan = $row["KODE_PERUSAHAAN"];
  			$ls_npp 						= $row["NPP"];
  			$ls_nama_perusahaan = $row["NAMA_PERUSAHAAN"];
  			$ls_kode_kantor 		= $row["KODE_KANTOR"];
  			$ls_kode_divisi 		= $row["KODE_DIVISI"];
  			$ls_nama_divisi 		= $row["NAMA_DIVISI"];
  			
  			echo "formObj.kode_tk.value = '".$ls_kode_tk."';\n";	
  			echo "formObj.nama_tk.value = '".$ls_nama_tk."';\n";	
  			echo "formObj.nomor_identitas.value = '".$ls_nomor_identitas."';\n";	
  			echo "formObj.jenis_identitas.value = '".$ls_jenis_identitas."';\n";	
  			echo "formObj.kode_kantor_tk.value = '".$ls_kode_kantor."';\n";	
  			echo "formObj.kode_perusahaan.value = '".$ls_kode_perusahaan."';\n";
  			echo "formObj.npp.value = '".$ls_npp."';\n";
  			echo "formObj.nama_perusahaan.value = '".$ls_nama_perusahaan."';\n";	
  			echo "formObj.kode_divisi.value = '".$ls_kode_divisi."';\n";
  			echo "formObj.nama_divisi.value = '".$ls_nama_divisi."';\n";
				
				echo "formObj.kode_segmen_list.disabled = true;\n";
			}else
			{
  		 	echo "formObj.st_errval3.value = '2';\n";		
  			echo "formObj.kode_tk.value = '';\n";	
  			echo "formObj.nama_tk.value = '';\n";	
  			echo "formObj.nomor_identitas.value = '';\n";	
  			echo "formObj.jenis_identitas.value = '';\n";	
  			echo "formObj.kode_kantor_tk.value = '';\n";	
  			echo "formObj.kode_perusahaan.value = '';\n";
  			echo "formObj.npp.value = '';\n";
  			echo "formObj.nama_perusahaan.value = '';\n";	
  			echo "formObj.kode_divisi.value = '';\n";
  			echo "formObj.nama_divisi.value = '';\n";
  				
  	 		echo "fl_js_get_lov_by_kpj2();"; 			
			}//end cek apakah 1 kpj lebih dari 1 kode tk -----------------------------
		}
	}	
	// end validasi kpj ----------------------------------------------------------

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
				 		"where kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
						"and nvl(status_nonaktif,'T')='T' ";
		$DB->parse($sql);
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
  				 		"where kode_tipe_klaim = '$ls_kode_tipe_klaim' ";
  		$DB->parse($sql);
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
			
			//utk KLAIM JKK/JKM TAMPILKAN MASA PERLINDUNGAN --------------------------
			if (($ls_jenis_klaim=="JKK" || $ls_jenis_klaim=="JKM"))
			{
				echo "window.document.getElementById('span_status_kepesertaan').style.display='block';";
				echo "window.document.getElementById('span_kode_perlindungan').style.display='block';";
				echo "window.document.getElementById('span_masa_perlindungan').style.display='block';";
			}else
			{
				echo "window.document.getElementById('span_status_kepesertaan').style.display='none';";
				echo "window.document.getElementById('span_kode_perlindungan').style.display='none';";
				echo "window.document.getElementById('span_masa_perlindungan').style.display='none';";	 
			}
			$ls_flag_meninggal=="T";
			
			$ls_invalid = "T";
			//------ JIKA KLAIM JHT MAKA VALIDASI APAKAH TK IKUT PROGRAM JHT --------- 
			if ($ls_jenis_klaim == "JHT")
			{
				$sql = 	"select count(*) as v_jml from sijstk.kn_kepesertaan_tk_prg a, sijstk.kn_kepesertaan_prs b ".
                "where a.kode_kepesertaan = b.kode_kepesertaan and b.kode_segmen = '$ls_kode_segmen' ".
								"and a.kode_tk = '$ls_kode_tk' ".
                "and a.kd_prg = 1 ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ln_flag_ikut_jht = $row["V_JML"];

				if ($ln_flag_ikut_jht=="0")
				{
				 	echo "formObj.st_errval1.value = '2';\n";
					$ls_invalid = "Y";  
				}	
			}else if ($ls_jenis_klaim == "JHM") 
			{
			  //------ JIKA KLAIM JHT/JKM MAKA VALIDASI APAKAH TK IKUT PROGRAM JHT --------- 
				$sql = 	"select count(*) as v_jml from sijstk.kn_kepesertaan_tk_prg a, sijstk.kn_kepesertaan_prs b ".
                "where a.kode_kepesertaan = b.kode_kepesertaan and b.kode_segmen = '$ls_kode_segmen' ".
								"and a.kode_tk = '$ls_kode_tk' ".
                "and a.kd_prg = 1 ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ln_flag_ikut_jht = $row["V_JML"];

				if ($ln_flag_ikut_jht=="0")
				{
				 	echo "formObj.st_errval1.value = '3';\n";
					$ls_invalid = "Y";  
				}else
				{
  				//cek apakah klaim jht/jkm, jht atau jkm sudah pernah diambil --------------------------
      		$sql = 	"select sum(nvl(cnt_jhm,0)) cnt_jhm, sum(nvl(cnt_jht,0)) cnt_jht, sum(nvl(cnt_jkm,0)) cnt_jkm ".
                  "from ".
                  "( ".
                  "    select decode(kode_tipe_klaim,'JHM01',1,0) cnt_jhm, decode(kode_tipe_klaim,'JHT01',1,0) cnt_jht, ". 
									"					  decode(kode_tipe_klaim,'JKM01',1,0) cnt_jkm ".
                  "    from sijstk.pn_klaim ". 
                  "    where kode_segmen = '$ls_kode_segmen' ".
                  "    and kode_tk = '$ls_kode_tk' ".
									"		 and kode_sebab_klaim not in (select kode_sebab_klaim from pn.pn_kode_sebab_klaim where nvl(flag_partial,'T')='Y') ".
    							"		 and kode_sebab_klaim <> 'SKJ01' ".
                  "    and nvl(status_batal,'T')='T' ".
									"		 and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                  ") ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_cnt_jhm = $row["CNT_JHM"];
					$ln_cnt_jht = $row["CNT_JHT"];
					$ln_cnt_jkm = $row["CNT_JKM"];
    			
    			if ($ln_cnt_jhm>"0")
    			{
    			 	echo "formObj.st_errval1.value = '4';\n";
						$ls_invalid = "Y";
    				//ambil kode_klaim yang sudah dientry ------------------------------
        		$sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
                    "and nvl(status_batal,'T')='T' ".
										"and kode_tipe_klaim = 'JHM01' ".
  									"and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                    "and rownum = 1 ";
        		$DB->parse($sql);
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ls_kode_klaim = $row["KODE_KLAIM"];
						echo "alert('Klaim JHT/JKM sudah pernah dientry dengan kode klaim $ls_kode_klaim ...!!!');\n";					
    			}
    			if ($ln_cnt_jht>"0")
    			{
    			 	echo "formObj.st_errval1.value = '5';\n";
  					$ls_invalid = "Y"; 
    				//ambil kode_klaim yang sudah dientry ------------------------------
        		$sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
                    "and nvl(status_batal,'T')='T' ".
										"and kode_tipe_klaim = 'JHT01' ".
										"and kode_sebab_klaim not in (select kode_sebab_klaim from pn.pn_kode_sebab_klaim where nvl(flag_partial,'T')='Y') ".
    								"and kode_sebab_klaim <> 'SKJ01' ".
  									"and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                    "and rownum = 1 ";
        		$DB->parse($sql);
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ls_kode_klaim = $row["KODE_KLAIM"];
						echo "alert('Klaim JHT/JKM tidak dapat dilakukan, Klaim JHT sudah pernah dientry dengan kode klaim $ls_kode_klaim , Silahkan entry klaim JKM ..!!!');\n";							
    			}	
    			if ($ln_cnt_jkm>"0")
    			{
    			 	echo "formObj.st_errval1.value = '6';\n";
  					$ls_invalid = "Y";
    				//ambil kode_klaim yang sudah dientry ------------------------------
        		$sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
                    "and nvl(status_batal,'T')='T' ".
										"and kode_tipe_klaim = 'JKM01' ".
  									"and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                    "and rownum = 1 ";
        		$DB->parse($sql);
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ls_kode_klaim = $row["KODE_KLAIM"];
						echo "alert('Klaim JHT/JKM tidak dapat dilakukan, Klaim JKM sudah pernah dientry dengan kode klaim $ls_kode_klaim , Silahkan entry klaim JHT ..!!!');\n";							 
    			}										
  				//end cek apakah klaim jht/jkm, jht atau jkm sudah pernah diambil ----
				}								
			}else if ($ls_jenis_klaim == "JPN") //------ JIKA KLAIM JP MAKA VALIDASI APAKAH TK IKUT PROGRAM JP --------- 
			{
				//cek apakah kpj pernah dilakukan amalgamasi ---------------------------
				$sql = 	"select count(*) as v_jml from sijstk.kn_agenda_amalgamasi_detil ".
                "where kode_tk = '$ls_kode_tk' ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ln_cnt_amalgamasi = $row["V_JML"];
				if ($ln_cnt_amalgamasi == ""){$ln_cnt_amalgamasi = "0";}
				
				if ($ln_cnt_amalgamasi == "0")
				{				
  				$sql = 	"select count(*) as v_jml from sijstk.kn_kepesertaan_tk_prg a, sijstk.kn_kepesertaan_prs b ".
                  "where a.kode_kepesertaan = b.kode_kepesertaan and b.kode_segmen = '$ls_kode_segmen' ".
  								"and a.kode_tk = '$ls_kode_tk' ".
                  "and a.kd_prg = 4 ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_flag_ikut_jpn = $row["V_JML"];
				}else
				{
  				$sql = 	"select count(*) as v_jml from sijstk.kn_kepesertaan_tk_prg a, sijstk.kn_kepesertaan_prs b ". 
                  "where a.kode_kepesertaan = b.kode_kepesertaan and b.kode_segmen = 'PU' ". 
                  "and a.kd_prg = 4 ".
                  "and exists ".
                  "( ".
                  "    select null from kn.kn_agenda_amalgamasi_detil x ".
                  "    where exists ".
                  "    ( ".
                  "        select null from kn.kn_agenda_amalgamasi_detil ". 
                  "        where kode_agenda = x.kode_agenda ".
                  "        and no_detil = x.no_detil ". 
                  "        and kode_tk  = '$ls_kode_tk' ".
                  "    ) ". 
                  "    and kode_kepesertaan = a.kode_kepesertaan ".
                  "    and kode_tk = a.kode_tk ".
                  ") ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_flag_ikut_jpn = $row["V_JML"];				
				}
				
				if ($ln_flag_ikut_jpn=="0")
				{
				 	echo "formObj.st_errval1.value = '7';\n";
					$ls_invalid = "Y";  
				}else
				{
    			//cek apakah klaim jpn sudah pernah dientry atau tidak ---------------
      		$sql = 	"select count(*) as v_jml from sijstk.pn_klaim ".
                  "where kode_segmen = '$ls_kode_segmen' ".
                  "and kode_tk = '$ls_kode_tk' ".
                  "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                  "and nvl(status_batal,'T')='T' ".
                  "and kode_klaim <> nvl('$ls_kode_klaim','X') ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_exist_open_agenda = $row["V_JML"];
    			
    			if ($ln_exist_open_agenda>"0")
    			{
    			 	echo "formObj.st_errval1.value = '8';\n";
  					$ls_invalid = "Y";
    				//ambil kode_klaim yang sudah dientry ------------------------------
        		$sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
										"and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                    "and nvl(status_batal,'T')='T' ".
  									"and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                    "and rownum = 1 ";
        		$DB->parse($sql);
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ls_kode_klaim = $row["KODE_KLAIM"];
						echo "alert('Klaim JPN untuk TK tersebut sudah pernah dientry dengan kode klaim $ls_kode_klaim ..!!!');\n";							 
    			}										
  				//end cek apakah klaim jpn sudah pernah dientry atau tidak ---------------
				}				
			}else if ($ls_jenis_klaim == "JKK" && $ld_tgl_kejadian!="")
			{
  			//cek apakah sudah pernah dilakukan klaim jkk utk tgl_kejadian yg sama--
    		$sql = 	"select count(*) as v_jml from sijstk.pn_klaim ".
                "where kode_segmen = '$ls_kode_segmen' ".
                "and kode_tk = '$ls_kode_tk' ".
                "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
								"and to_char(tgl_kejadian,'yyyymmdd') = to_char(to_date('$ld_tgl_kejadian','dd/mm/yyyy'),'yyyymmdd') ".
                "and nvl(status_batal,'T')='T' ".
                "and kode_klaim <> nvl('$ls_kode_klaim','X') ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ln_exist_agenda = $row["V_JML"];
  			
  			if ($ln_exist_agenda>"0")
  			{
  			 	echo "formObj.st_errval1.value = '9';\n";
					$ls_invalid = "Y"; 
          //ambil kode_klaim yang sudah dientry ------------------------------
          $sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                  "where kode_segmen = '$ls_kode_segmen' ".
                  "and kode_tk = '$ls_kode_tk' ".
                  "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                  "and to_char(tgl_kejadian,'yyyymmdd') = to_char(to_date('$ld_tgl_kejadian','dd/mm/yyyy'),'yyyymmdd') ".
                  "and nvl(status_batal,'T')='T' ".
                  "and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                  "and rownum = 1 ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ls_kode_klaim = $row["KODE_KLAIM"];
          echo "alert('Klaim JKK dengan tgl kecelakaan yang sama untuk TK tersebut sudah pernah dientry dengan kode klaim $ls_kode_klaim ..!!!');\n";					
  			}	
			}else if ($ls_jenis_klaim == "JKM")
			{
  			//cek apakah klaim jkm sudah pernah dientry atau tidak -----------------
    		$sql = 	"select count(*) as v_jml from sijstk.pn_klaim ".
                "where kode_segmen = '$ls_kode_segmen' ".
                "and kode_tk = '$ls_kode_tk' ".
                "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                "and nvl(status_batal,'T')='T' ".
                "and kode_klaim <> nvl('$ls_kode_klaim','X') ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ln_exist_open_agenda = $row["V_JML"];
  			
  			if ($ln_exist_open_agenda>"0")
  			{
  			 	echo "formObj.st_errval1.value = '10';\n";
					$ls_invalid = "Y"; 
          //ambil kode_klaim yang sudah dientry ------------------------------
          $sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                  "where kode_segmen = '$ls_kode_segmen' ".
                  "and kode_tk = '$ls_kode_tk' ".
                  "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                  "and nvl(status_batal,'T')='T' ".
                  "and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                  "and rownum = 1 ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ls_kode_klaim = $row["KODE_KLAIM"];
          echo "alert('Klaim JKM untuk TK tersebut sudah pernah dientry dengan kode klaim $ls_kode_klaim ..!!!');\n";					
  			}	
			}		
			//end cek apakah klaim tsb sudah pernah dientry dan masih OPEN atau tidak 
			
			//jika tk layak mengajukan klaim -----------------------------------------
			if ($ls_invalid == "T")
			{			
  			//ambil sebab klaim jika hanya ada 1 -----------------------------------
    		$sql = 	"select count(*) as v_cnt from sijstk.pn_kode_sebab_klaim a ".
                "where kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
  							"and nvl(to_char(tgl_nonaktif,'yyyymmdd'),'30000101') > to_char(sysdate,'yyyymmdd') ".
                "and exists ".
                "( ".
                "    select null from sijstk.pn_kode_sebab_segmen ".
                "    where kode_sebab_klaim = a.kode_sebab_klaim ".
                "    and kode_segmen = '$ls_kode_segmen' ".
  							"		 and nvl(to_char(tgl_nonaktif,'yyyymmdd'),'30000101') > to_char(sysdate,'yyyymmdd') ".
                ") ";
    		$DB->parse($sql);
    		$DB->execute();
    		$row = $DB->nextrow();
    		$ln_cnt = $row["V_CNT"];
  			
    		if ($ln_cnt=="1")
    		{
      		$sql = 	"select nvl(flag_meninggal,'T') flag_meninggal, kode_sebab_klaim, nama_sebab_klaim, keyword ".
  						 		"from sijstk.pn_kode_sebab_klaim a ".
                  "where kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
  								"and nvl(to_char(tgl_nonaktif,'yyyymmdd'),'30000101') > to_char(sysdate,'yyyymmdd') ".
                  "and exists ".
                  "( ".
                  "    select null from sijstk.pn_kode_sebab_segmen ".
                  "    where kode_sebab_klaim = a.kode_sebab_klaim ".
                  "    and kode_segmen = '$ls_kode_segmen' ".
  								"		 and nvl(to_char(tgl_nonaktif,'yyyymmdd'),'30000101') > to_char(sysdate,'yyyymmdd') ".
                  ") ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ls_kode_sebab_klaim = $row["KODE_SEBAB_KLAIM"];
  				$ls_nama_sebab_klaim = $row["NAMA_SEBAB_KLAIM"];
  				$ls_keyword_sebab_klaim = $row["KEYWORD"];
  				$ls_flag_meninggal = $row["FLAG_MENINGGAL"];
  					
  				echo "formObj.kode_sebab_klaim.value = '".$ls_kode_sebab_klaim."';\n";	
  				echo "formObj.nama_sebab_klaim.value = '".$ls_nama_sebab_klaim."';\n";
  				echo "formObj.keyword_sebab_klaim.value = '".$ls_keyword_sebab_klaim."';\n";			
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
			}//end jika tk layak mengajukan klaim ------------------------------------													
		}//end if if ($ln_cnt==0)
	}	
	// end validasi tipe klaim ---------------------------------------------------

	// validasi sebab klaim ------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_kode_sebab_klaim")
	{
		$ls_kode_tipe_klaim  = strtoupper($_GET['c_kode_tipe_klaim']);
		$ls_kode_segmen 		 = strtoupper($_GET['c_kode_segmen']);
		$ls_kode_sebab_klaim = strtoupper($_GET['c_kode_sebab_klaim']);
		
		$ls_kode_tk 				 = strtoupper($_GET['c_kode_tk']);
		$ls_kode_klaim			 = strtoupper($_GET['c_kode_klaim']);
		//1. cek apakah sebab klaim valid atau tidak ------------------------
		$sql = 	"select count(*) as v_cnt from sijstk.pn_kode_sebab_klaim a ".
            "where kode_sebab_klaim = '$ls_kode_sebab_klaim' ".
						"and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
            "and exists ".
            "( ".
            "    select null from sijstk.pn_kode_sebab_segmen ".
            "    where kode_sebab_klaim = a.kode_sebab_klaim ".
            "    and kode_segmen = '$ls_kode_segmen' ".
            ") ";
		$DB->parse($sql);
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
  		$sql = 	"select nvl(flag_meninggal,'T') flag_meninggal, nama_sebab_klaim, substr('$ls_kode_tipe_klaim',1,3) jenis_klaim, ".
					 		"				nvl(flag_partial,'T') flag_partial, keyword, to_char(sysdate, 'dd/mm/yyyy') tgl_sysdate ".
					 		"from sijstk.pn_kode_sebab_klaim ".
  				 		"where kode_sebab_klaim = '$ls_kode_sebab_klaim' ";
  		$DB->parse($sql);
  		$DB->execute();
  		$row = $DB->nextrow();
  		$ls_nama_sebab_klaim = $row["NAMA_SEBAB_KLAIM"];
			$ls_keyword_sebab_klaim = $row["KEYWORD"];
			$ls_flag_meninggal = $row["FLAG_MENINGGAL"];
			$ls_jenis_klaim = $row["JENIS_KLAIM"];
			$ld_tgl_sysdate = $row["TGL_SYSDATE"];
			$ls_flag_partial = $row["FLAG_PARTIAL"];
			
			echo "formObj.nama_sebab_klaim.value = '".$ls_nama_sebab_klaim."';\n";
			echo "formObj.keyword_sebab_klaim.value = '".$ls_keyword_sebab_klaim."';\n";	
			echo "formObj.flag_meninggal.value = '".$ls_flag_meninggal."';\n";	
			
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
			
			//cek sebab klaim jht ----------------------------------------------------	
			if ($ls_jenis_klaim == "JHT")
			{
			 	if ($ls_flag_partial == "Y")
				{  
    			//cek apakah tk tersebut sudah pernah klaim jht baik sebagian atau penuh----
      		$sql = 	"select count(*) as v_jml from sijstk.pn_klaim ".
                  "where kode_segmen = '$ls_kode_segmen' ".
                  "and kode_tk = '$ls_kode_tk' ".
                  "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                  "and nvl(status_batal,'T')='T' ".
                  "and kode_klaim <> nvl('$ls_kode_klaim','X') ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_exist_open_agenda = $row["V_JML"];
    			
    			if ($ln_exist_open_agenda>"0")
    			{
    			 	echo "formObj.st_errval2.value = '2';\n"; 
            //ambil kode_klaim yang sudah dientry ------------------------------
            $sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
                    "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                    "and nvl(status_batal,'T')='T' ".
                    "and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                    "and rownum = 1 ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_klaim = $row["KODE_KLAIM"];
            echo "alert('Klaim JHT untuk TK tersebut sudah pernah dientry dengan kode klaim $ls_kode_klaim ..!!!');\n";						
					}	
				}else
				{
				 	//klaim jkt penuh ----------------------------------------------------
    			//cek apakah klaim tsb sudah pernah dientry klaim penuh atau tidak (klaim partial boleh 1x)----
      		$sql = 	"select count(*) as v_jml from sijstk.pn_klaim ".
                  "where kode_segmen = '$ls_kode_segmen' ".
                  "and kode_tk = '$ls_kode_tk' ".
                  "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                  "and nvl(status_batal,'T')='T' ".
                  "and kode_klaim <> nvl('$ls_kode_klaim','X') ".
  								"and kode_sebab_klaim in ".
  								"(	 select kode_sebab_klaim from sijstk.pn_kode_sebab_klaim where nvl(flag_partial,'T')='T') ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_exist_open_agenda = $row["V_JML"];
    			
    			if ($ln_exist_open_agenda>"0")
    			{
    			 	echo "formObj.st_errval2.value = '3';\n"; 
            //ambil kode_klaim yang sudah dientry ------------------------------
            $sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
                    "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                    "and nvl(status_batal,'T')='T' ".
                    "and kode_klaim <> nvl('$ls_kode_klaim','X') ".
    								"and kode_sebab_klaim in ".
    								"(	 select kode_sebab_klaim from sijstk.pn_kode_sebab_klaim where nvl(flag_partial,'T')='T') ".
                    "and rownum = 1 ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_klaim = $row["KODE_KLAIM"];
            echo "alert('Klaim Penuh JHT untuk TK tersebut sudah pernah dientry dengan kode klaim $ls_kode_klaim ..!!!');\n";								
					}							 	 
				}	
			}//end cek sebab klaim jht -----------------------------------------------						 	 						
		}//ln_cnt=0
	}	
	// end validasi sebab klaim --------------------------------------------------

	// validasi tgl kejadian jkk dan jkm  ----------------------------------------
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
		$ls_kode_klaim 			 = strtoupper($_GET['c_kode_klaim']);
		
    $sql = 	"select to_char(to_date('$ld_tgl_lapor','dd/mm/yyyy'),'yyyymmdd') tgl_lapor, ".
    	 			"		to_char(to_date('$ld_tgl_kejadian','dd/mm/yyyy'),'yyyymmdd') tgl_kejadian, ".
    				"		to_char(to_date('$ld_tgl_klaim','dd/mm/yyyy'),'yyyymmdd') tgl_klaim, ".
						"		substr('$ls_kode_tipe_klaim',1,3) jenis_klaim ". 
    				"from dual ";
    $DB->parse($sql);
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

				if ($ls_jenis_klaim == "JKK" && $ld_tgl_kejadian!="")
  			{
    			//cek apakah sudah pernah dilakukan klaim jkk utk tgl_kejadian yg sama--
      		$sql = 	"select count(*) as v_jml from sijstk.pn_klaim ".
                  "where kode_segmen = '$ls_kode_segmen' ".
                  "and kode_tk = '$ls_kode_tk' ".
                  "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
  								"and to_char(tgl_kejadian,'yyyymmdd') = to_char(to_date('$ld_tgl_kejadian','dd/mm/yyyy'),'yyyymmdd') ".
                  "and nvl(status_batal,'T')='T' ".
                  "and kode_klaim <> nvl('$ls_kode_klaim','X') ";
      		$DB->parse($sql);
      		$DB->execute();
      		$row = $DB->nextrow();
      		$ln_exist_agenda = $row["V_JML"];
					
    			if ($ln_exist_agenda=="0")
    			{
					 	echo "formObj.st_errval1.value = '0';\n"; 
					}else
					{
    			 	echo "formObj.st_errval1.value = '9';\n";
  					$ls_invalid = "Y"; 
            //ambil kode_klaim yang sudah dientry ------------------------------
            $sql = 	"select kode_klaim from sijstk.pn_klaim ". 
                    "where kode_segmen = '$ls_kode_segmen' ".
                    "and kode_tk = '$ls_kode_tk' ".
                    "and kode_tipe_klaim = '$ls_kode_tipe_klaim' ".
                    "and to_char(tgl_kejadian,'yyyymmdd') = to_char(to_date('$ld_tgl_kejadian','dd/mm/yyyy'),'yyyymmdd') ".
                    "and nvl(status_batal,'T')='T' ".
                    "and kode_klaim <> nvl('$ls_kode_klaim','X') ".
                    "and rownum = 1 ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_klaim = $row["KODE_KLAIM"];
            echo "alert('Klaim JKK dengan tgl kecelakaan yang sama untuk TK tersebut sudah pernah dientry dengan kode klaim $ls_kode_klaim ..!!!');\n";					
    			}								
				}  			
							
    		if ($ls_kode_segmen=="TKI")
    		{
      		$qry = "BEGIN SIJSTK.P_PN_PN5001.X_GET_PERLINDUNGAN_TKI( ".
      				 	 				"		'$ls_kode_segmen', '$ls_kode_tk', '$ls_kode_perusahaan', '$ls_kode_divisi', to_date('$ld_tgl_kejadian','dd/mm/yyyy'), ".
                        "		:p_status_kepesertaan, :p_kode_perlindungan, :p_tgl_awal_perlindungan, :p_tgl_akhir_perlindungan ".
    										");END;";											 	
          $proc = $DB->parse($qry);
          oci_bind_by_name($proc, ":p_status_kepesertaan", $p_status_kepesertaan,32);
      		oci_bind_by_name($proc, ":p_kode_perlindungan", $p_kode_perlindungan,32);
      		oci_bind_by_name($proc, ":p_tgl_awal_perlindungan", $p_tgl_awal_perlindungan,32);
      		oci_bind_by_name($proc, ":p_tgl_akhir_perlindungan", $p_tgl_akhir_perlindungan,32);
          $DB->execute();				
      		$ls_status_kepesertaan = $p_status_kepesertaan;
      		$ls_kode_perlindungan = $p_kode_perlindungan;
      		$ld_tgl_awal_perlindungan = $p_tgl_awal_perlindungan;
      		$ld_tgl_akhir_perlindungan = $p_tgl_akhir_perlindungan;
      				
      		if ($ls_kode_perlindungan=="NA")
      		{
      		  echo "formObj.st_errval4.value = '1';\n";			
      			echo "formObj.keterangan.value = 'NON AKTIF/DILUAR MASA PERLINDUNGAN';\n";	
      			echo "formObj.ket_masa_perlindungan.value = '';\n";
      		}else
      		{
        		echo "formObj.st_errval4.value = '0';\n";
      			$sql = 	"select to_char(to_date('$ld_tgl_awal_perlindungan','dd/mm/yyyy'),'dd/mm/yyyy') tgl_awal, ".
      					 		"		to_char(to_date('$ld_tgl_akhir_perlindungan','dd/mm/yyyy'),'dd/mm/yyyy') tgl_akhir, ".
      							"		to_char(to_date('$ld_tgl_awal_perlindungan','dd/mm/yyyy'),'dd/mm/yyyy')||' s.d '||to_char(to_date('$ld_tgl_akhir_perlindungan','dd/mm/yyyy'),'dd/mm/yyyy') ket_masa_perlindungan ". 
      							"from dual ";
        		$DB->parse($sql);
        		$DB->execute();
        		$row = $DB->nextrow();
        		$ld_tgl_awal_perlindungan  = $row["TGL_AWAL"];
        		$ld_tgl_akhir_perlindungan = $row["TGL_AKHIR"];
      			$ls_ket_masa_perlindungan  = $row["KET_MASA_PERLINDUNGAN"];
      			echo "formObj.keterangan.value = '';\n";
      			echo "formObj.ket_masa_perlindungan.value = '".$ls_ket_masa_perlindungan."';\n";	
      		}			
          echo "formObj.status_kepesertaan.value = '".$ls_status_kepesertaan."';\n";
          echo "formObj.kode_perlindungan.value = '".$ls_kode_perlindungan."';\n";
          echo "formObj.tgl_awal_perlindungan.value = '".$ld_tgl_awal_perlindungan."';\n";
          echo "formObj.tgl_akhir_perlindungan.value = '".$ld_tgl_akhir_perlindungan."';\n";
    		}else
    		{
    		 	echo "formObj.st_errval4.value = '0';\n";
    		}
			}	
		}		  		 
	}	
	// end validasi tgl kejadian jkk dan jkm TKI ---------------------------------

	// validasi tgl lapor --------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_tgl_lapor")
	{
		$ld_tgl_lapor 		 = strtoupper($_GET['c_tgl_lapor']);
		$ld_tgl_kejadian 	 = strtoupper($_GET['c_tgl_kejadian']);
		$ld_tgl_klaim 	 	 = strtoupper($_GET['c_tgl_klaim']);
		$ls_kode_tk 	 	 	 = strtoupper($_GET['c_kode_tk']);
		$ls_kode_tipe_klaim = strtoupper($_GET['c_kode_tipe_klaim']);
		
    $sql = 	"select to_char(to_date('$ld_tgl_lapor','dd/mm/yyyy'),'yyyymmdd') tgl_lapor, ".
    	 			"		to_char(to_date('$ld_tgl_kejadian','dd/mm/yyyy'),'yyyymmdd') tgl_kejadian, ".
    				"		to_char(to_date('$ld_tgl_klaim','dd/mm/yyyy'),'yyyymmdd') tgl_klaim ". 
    				"from dual ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tgl_lapor  = $row["TGL_LAPOR"];
    $ls_tgl_kejadian = $row["TGL_KEJADIAN"];
    $ls_tgl_klaim  = $row["TGL_KLAIM"];
		
    if ($ls_tgl_lapor>$ls_tgl_klaim)
    {
     	echo "formObj.st_errval6.value = '2';\n"; 			 
    }else
    {
      echo "formObj.st_errval6.value = '0';\n";
      //cek blth kepesertaan tk ----------------------------------------------
      $sql = 	"select to_char(tgl_kepesertaan,'yyyymmdd') tgl_kepesertaan from sijstk.vw_kn_tk where kode_tk='$ls_kode_tk' and rownum = 1 ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_tgl_kepesertaan  = $row["TGL_KEPESERTAAN"];
      
      if ($ls_tgl_lapor<$ls_tgl_kepesertaan)
      {
       	echo "formObj.st_errval6.value = '3';\n";			 
      }else
      {
        if ($ls_kode_tipe_klaim != "JHT01" && $ls_kode_tipe_klaim != "JPN01" && $ls_tgl_lapor<$ls_tgl_kejadian)
        {
         	echo "formObj.st_errval6.value = '1';\n";			 			
        }	 
      }//end if ($ls_tgl_lapor<$ls_tgl_kepesertaan)
    }//end if if ($ls_tgl_lapor>$ls_tgl_klaim)		  		 
	}	
	// end validasi tgl kejadian jkk dan jkm TKI ---------------------------------
		
	// -------------- HITUNG MANFAAT ---------------------------------------------
	// hitung manfaat biaya transportasi -----------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_transportasi")
	{
		$ln_transport_darat_diajukan = $_GET['c_biaya_darat'];
		$ln_transport_laut_diajukan  = $_GET['c_biaya_laut'];
		$ln_transport_udara_diajukan = $_GET['c_biaya_udara'];
		$ls_kode_segmen 			 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan 			 = $_GET['c_kode_perlindungan'];
		$ls_kode_klaim 			 				 = $_GET['c_kode_klaim'];
		$ln_no_urut 			 				 	 = $_GET['c_no_urut'];
				
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_TRANSPORTASI( ".
				 	 				"		'$ls_kode_klaim','$ln_no_urut', '$ls_kode_segmen', '$ls_kode_perlindungan', sysdate, '$ln_transport_darat_diajukan', '$ln_transport_laut_diajukan', '$ln_transport_udara_diajukan', ".
                  "		:p_nom_darat_disetujui, :p_nom_laut_disetujui, :p_nom_udara_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_darat_disetujui", $p_nom_darat_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_laut_disetujui", $p_nom_laut_disetujui,32);
		oci_bind_by_name($proc, ":p_nom_udara_disetujui", $p_nom_udara_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
	
	// hitung manfaat manfaat biaya prothese/orthese -----------------------------
	if ($_GET['getClientId']=="f_ajax_val_kode_manfaat_detil")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ls_kode_manfaat_detil = $_GET['c_kode_manfaat_detil'];

    $sql = "select default_jmlitem from sijstk.pn_kode_manfaat_detil a ".
           "where a.kode_manfaat = '$ls_kode_manfaat' ".
					 "and a.kode_manfaat_detil = '$ls_kode_manfaat_detil' ";
    $DB->parse($sql);
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
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_segmen 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan  = $_GET['c_kode_perlindungan'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_PROTHESE( ".
				 	 				"		'$ls_kode_segmen', '$ls_kode_perlindungan', sysdate, '$ls_kode_manfaat', '$ls_kode_manfaat_detil', '$ln_jml_item', '$ln_nom_biaya_diajukan', :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
		
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_OBATRAWAT( ".
				 	 				"		'$ls_kode_klaim','$ln_no_urut', '$ls_kode_segmen', '$ls_kode_perlindungan',sysdate, '$ls_kode_manfaat', '$ln_nom_biaya_diajukan', :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
	// end hitung manfaat biaya obat/rawat ---------------------------------------
	
	// hitung manfaat manfaat biaya rehabilitasi medis ---------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_rehabmedis")
	{		
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ln_nom_biaya_diajukan = $_GET['c_nom_biaya_diajukan'];
		$ls_kode_segmen 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan  = $_GET['c_kode_perlindungan'];
		$ld_tgl_kejadian  		 = $_GET['c_tgl_kejadian'];
		
		
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_REHABMEDIS( ".
				 	 				"		'$ls_kode_segmen', '$ls_kode_perlindungan', to_date('$ld_tgl_kejadian','dd/mm/yyyy'), '$ls_kode_manfaat', '$ln_nom_biaya_diajukan', :p_nom_disetujui, ".
                  "		:p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
    $DB->execute();				
    $ln_nom_disetujui = $p_nom_disetujui;
		$ls_sukses = $p_sukses;
		$ls_mess = $p_mess;
		
		if ($ls_sukses!="1")
		{
		 	 //echo "formObj.st_errval1.value = '1';\n";	
			 echo "formObj.st_errval1.value = '0';\n"; //update 21/03/2018, jika tgl_kejadian sejak 1 juli 2015 maka biaya rehabilitasi sesuai yg diajukan
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_biaya_diajukan,2,".",",")."';\n";		 
		}else
		{
		 	 echo "formObj.st_errval1.value = '0';\n";
			 echo "formObj.nom_biaya_disetujui.value = '".number_format($ln_nom_disetujui,2,".",",")."';\n";			 	  		 
		}				
	}	
	// end hitung manfaat biaya rehabilitasi medis -------------------------------	

	// hitung manfaat manfaat beasiswa -------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_beasiswa")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ls_beasiswa_jenis = $_GET['c_beasiswa_jenis'];
		$ls_beasiswa_jenjang_pendidikan = $_GET['c_beasiswa_jenjang_pendidikan'];
						
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_BEASISWA( ".
				 	 				"		sysdate, '$ls_kode_klaim', '$ls_kode_manfaat', '$ls_kd_prg', ".
									"		'$ls_beasiswa_jenis', '$ls_beasiswa_jenjang_pendidikan', ".
									"		:p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
		$ls_kode_segmen 			 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan 			 = $_GET['c_kode_perlindungan'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_SANTUNANBERKALA( ".
				 	 				"		'$ls_kode_segmen', '$ls_kode_perlindungan',sysdate, '$ls_kode_manfaat', :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
		$ls_kode_segmen 			 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan 			 = $_GET['c_kode_perlindungan'];
		$ld_tgl_kejadian 			 			 = $_GET['c_tgl_kejadian'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_BIAYAPEMAKAMAN( ".
				 	 				"		'$ls_kode_segmen', '$ls_kode_perlindungan',to_date('$ld_tgl_kejadian','dd/mm/yyyy'), '$ls_kode_manfaat', :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
	// end hitung manfaat biaya pemakaman ---------------------------------------		

	// hitung manfaat manfaat santunan kematian -------------------------------------------	
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_santunankematian")
	{		
		$ls_kode_klaim 		= $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	= $_GET['c_kode_manfaat'];
		$ls_kd_prg 				= $_GET['c_kd_prg'];
		$ls_kode_segmen 			 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan 			 = $_GET['c_kode_perlindungan'];
		
    $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_SANTUNANKEMATIAN( ".
				 	 				"		'$ls_kode_segmen', '$ls_kode_perlindungan',sysdate, '$ls_kode_klaim', '$ls_kode_manfaat', '$ls_kd_prg', :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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

	//hitung manfaat uraian cacat ------------------------------------------------	 
	if ($_GET['getClientId']=="f_ajax_val_hitung_manfaat_uraiancacat")
	{		
		$ls_kode_klaim 			 	 = $_GET['c_kode_klaim'];
		$ls_kode_manfaat 			 = $_GET['c_kode_manfaat'];
		$ls_kode_manfaat_detil = $_GET['c_kode_manfaat_detil'];
		$ls_cacat_kode_keadaan = $_GET['c_cacat_kode_keadaan'];		
		$ln_cacat_persen_dokter	 = $_GET['c_cacat_persen_dokter'];
		$ls_kode_segmen 			 = $_GET['c_kode_segmen'];
		$ls_kode_perlindungan  = $_GET['c_kode_perlindungan'];
		$ls_valid = "T";
		if ($ln_cacat_persen_dokter=="")
		{
		 	 $ln_cacat_persen_dokter = "0";
		}
		
		//cek apakah manfaat detil sudah pernah dientry utk keadaan cacat selain yg dientry --------------------------
		if ($ls_cacat_kode_keadaan!="CACATSBGF")
		{
		 	 //kode_manfaat_detil tidak dapat dientry lebih dari 1x ------------------
        $sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil a ".
               "where kode_manfaat = '$ls_kode_manfaat' ". 
               "and kode_manfaat_detil = '$ls_kode_manfaat_detil' ".
               "and nvl(nom_biaya_disetujui,0)<>0 ". 
               "and kode_klaim in ". 
               "( ". 
               "  select kode_klaim from sijstk.pn_klaim where kode_klaim <> '$ls_kode_klaim' ". 
               "  start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T' ". 
               "  connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ". 
               ") ";
        $DB->parse($sql);
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
		 	 //kode_manfaat_detil tidak dapat dientry lebih dari 1x dg jenis kondisi yang berbeda ------------------
        $sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil a ".
               "where kode_manfaat = '$ls_kode_manfaat' ". 
               "and kode_manfaat_detil = '$ls_kode_manfaat_detil' ".
							 "and cacat_kode_keadaan <> '$ls_cacat_kode_keadaan' ".
               "and nvl(nom_biaya_disetujui,0)<>0 ". 
               "and kode_klaim in ". 
               "( ". 
               "  select kode_klaim from sijstk.pn_klaim where kode_klaim <> '$ls_kode_klaim' ". 
               "  start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T' ". 
               "  connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ". 
               ") ";
        $DB->parse($sql);
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
                 "where kode_manfaat = '$ls_kode_manfaat' ". 
                 "and kode_manfaat_detil = '$ls_kode_manfaat_detil' ".
  							 "and cacat_kode_keadaan = '$ls_cacat_kode_keadaan' ".
                 "and nvl(nom_biaya_disetujui,0)<>0 ". 
                 "and kode_klaim in ". 
                 "( ". 
                 "  select kode_klaim from sijstk.pn_klaim where kode_klaim <> '$ls_kode_klaim' ". 
                 "  start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T' ". 
                 "  connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ". 
                 ") ";
          $DB->parse($sql);
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
      $qry = "BEGIN SIJSTK.P_PN_PN5001.X_HITUNG_MNF_URAIANCACAT( ".
  				 	 				"		'$ls_kode_klaim', '$ls_kode_segmen', '$ls_kode_perlindungan', sysdate, '$ls_kode_manfaat', ".
  									"		'$ls_cacat_kode_keadaan', '$ls_kode_manfaat_detil', '$ln_cacat_persen_dokter', ".
  									"		:p_persen_table, :p_nom_disetujui, :p_sukses, :p_mess);END;";											 	
      $proc = $DB->parse($qry);
      oci_bind_by_name($proc, ":p_persen_table", $p_persen_table,32);
  		oci_bind_by_name($proc, ":p_nom_disetujui", $p_nom_disetujui,32);
  		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  		oci_bind_by_name($proc, ":p_mess", $p_mess,32);
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
	// end hitung manfaat uraian cacat -------------------------------------------		
	
	// hitung jml hari stmb ------------------------------------------------------
	if ($_GET['getClientId']=="f_ajax_val_hitung_jmlharistmb")
	{		
		$ls_kode_klaim 		 = $_GET['c_kode_klaim'];
		$ls_kode_manfaat 	 = $_GET['c_kode_manfaat'];
		$ln_no_urut 		 	 = $_GET['c_no_urut'];
		$ld_stmb_tgl_awal  = $_GET['c_stmb_tgl_awal'];
		$ld_stmb_tgl_akhir = $_GET['c_stmb_tgl_akhir'];
		
		//validasi tgl awal skrg terhadap penetapan sebelumnya ---------------------	
    $sql = "select count(*) as v_jml from sijstk.pn_klaim_manfaat_detil ".
           "where kode_manfaat = '$ls_kode_manfaat' ". 
           "and nvl(nom_biaya_disetujui,0)<>0 ". 
           "and to_char(to_date('$ld_stmb_tgl_awal','dd/mm/yyyy'),'yyyymmdd') between to_char(stmb_tgl_awal,'yyyymmdd') and to_char(stmb_tgl_akhir,'yyyymmdd') ".
           "and kode_klaim||no_urut <> '$ls_kode_klaim'||nvl('$ln_no_urut','99999') ".
           "and kode_klaim in  ".
           "(  ".
           "    select kode_klaim from sijstk.pn_klaim  ".
           "    start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T'  ".
           "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T'  ".
           ") ";
    $DB->parse($sql);
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
             "where kode_manfaat = '$ls_kode_manfaat' ". 
             "and nvl(nom_biaya_disetujui,0)<>0 ". 
             "and to_char(to_date('$ld_stmb_tgl_akhir','dd/mm/yyyy'),'yyyymmdd') between to_char(stmb_tgl_awal,'yyyymmdd') and to_char(stmb_tgl_akhir,'yyyymmdd') ".
             "and kode_klaim||no_urut <> '$ls_kode_klaim'||nvl('$ln_no_urut','99999') ".
             "and kode_klaim in  ".
             "(  ".
             "    select kode_klaim from sijstk.pn_klaim  ".
             "    start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T'  ".
             "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T'  ".
             ") ";
      $DB->parse($sql);
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
               "where kode_manfaat = '$ls_kode_manfaat' ". 
               "and nvl(nom_biaya_disetujui,0)<>0 ". 
               "and ( ".
							 "       (to_char(stmb_tgl_awal,'yyyymmdd') between to_char(to_date('$ld_stmb_tgl_awal','dd/mm/yyyy'),'yyyymmdd') and to_char(to_date('$ld_stmb_tgl_akhir','dd/mm/yyyy'),'yyyymmdd')) ".
							 "			 or ".
							 "       (to_char(stmb_tgl_akhir,'yyyymmdd') between to_char(to_date('$ld_stmb_tgl_awal','dd/mm/yyyy'),'yyyymmdd') and to_char(to_date('$ld_stmb_tgl_akhir','dd/mm/yyyy'),'yyyymmdd')) ". 
							 ") ".
               "and kode_klaim||no_urut <> '$ls_kode_klaim'||nvl('$ln_no_urut','99999') ".
               "and kode_klaim in  ".
               "(  ".
               "    select kode_klaim from sijstk.pn_klaim  ".
               "    start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T'  ".
               "    connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T'  ".
               ") ";
        $DB->parse($sql);
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
  				 							 
           $sql = "select ceil(to_date('$ld_stmb_tgl_akhir','dd/mm/yyyy')-to_date('$ld_stmb_tgl_awal','dd/mm/yyyy'))+1 as v_jmlhari from dual ";
           $DB->parse($sql);
           $DB->execute();
           $row = $DB->nextrow();
           $ln_jml_hari	= $row['V_JMLHARI'];				 
           echo "formObj.stmb_jml_hari.value = '".number_format($ln_jml_hari,0,".",",")."';\n";
  			}	 
  		}	 					 	  		 
		}
	}					
}
?>		