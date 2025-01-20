<?	  
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
//-- ACTION BUTTON -------------------------------------------------------------
if($btn_task=="submit_penetapan_tanpa_otentikasi")
{	
	// 13112020, ditambahkan untuk memvalidasi klaim yang dokumen dan tanda tangan belum lengkap sehingga tidak bisa ke tahap selanjutnya. 
	$query_cek = "select count(*) jml_dokumen_digital from pn.pn_klaim where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL')
					and kode_klaim = '$ls_kode_klaim'
				";
	$DB->parse($query_cek);
	if($DB->execute()){
		if($row=$DB->nextrow()){
			$ls_jml_dokumen_digital = $row['JML_DOKUMEN_DIGITAL'];			 
		}
	}
	
	$sql = "
			SELECT 	KANAL_PELAYANAN, KODE_TIPE_KLAIM, KODE_SEBAB_KLAIM,
			(
				select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
				where x.kode_klaim = a.kode_klaim
				and x.kode_manfaat = y.kode_manfaat
				and nvl(y.flag_berkala,'T')='Y'
				and nvl(x.nom_biaya_disetujui,0)<>0
			) cnt_berkala,
			(
				select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
				where x.kode_klaim = a.kode_klaim
				and x.kode_manfaat = y.kode_manfaat
				and nvl(y.flag_berkala,'T')='T'
				and nvl(x.nom_biaya_disetujui,0)<>0
			) cnt_lumpsum
			FROM 		PN.PN_KLAIM a
			WHERE 	KODE_KLAIM = '$ls_kode_klaim'
			";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_kanal_pelayanan = $row["KANAL_PELAYANAN"];
	$ls_kode_tipe_klaim = $row["KODE_TIPE_KLAIM"];
	$ls_cnt_lumpsum = $row["CNT_LUMPSUM"];
	$ls_cnt_berkala = $row["CNT_BERKALA"];
	$ls_kode_sebab_klaim = $row["KODE_SEBAB_KLAIM"];

	// 25-1-2024 [Problem ID : 1419] - [JKP-SIAP KERJA] - Terbentuk Double Kode Klaim - BC-476
	// pengecekan id pointer asal untuk klaim JKP
	// mulai pengecekan id pointer asal
	if($ls_kode_tipe_klaim == "JKP01"){
		$count = 0;
		$error = 0;
		$kode_klaim = '';
		$query = "select count(*) count FROM pn_klaim where id_pointer_asal = '$ls_id_pointer_asal' and kode_tipe_klaim = 'JKP01' and nvl(status_batal, 'T') = 'T'";
		$DB->parse($query);
		
		if($DB->execute()){
			if($row = $DB->nextrow()){
				$count = $row['COUNT'];			 
			}
		} else {
			$error++;
		}

		if ($count > 1 && $error == 0) {
			$query = "select kode_klaim FROM pn_klaim where kode_klaim <> '$ls_kode_klaim' and id_pointer_asal = '$ls_id_pointer_asal' and kode_tipe_klaim = 'JKP01' and nvl(status_batal, 'T') = 'T' and status_klaim in ('DISETUJUI','PERSETUJUAN','SELESAI') and rownum = 1";
			
			$DB->parse($query);
			
			if($DB->execute()){
				if($row = $DB->nextrow()){
					$kode_klaim = $row['KODE_KLAIM'];			 
				}

				if ($kode_klaim) {
					$msg = 'Tidak dapat melanjutkan proses, terdapat kode pengajuan Klaim JKP yang sama pada kode klaim ' . $kode_klaim . '. Silahkan kembali dan proses kode klaim lainnya.';
					echo "<script language=\"JavaScript\" type=\"text/javascript\">";
					echo "window.location.replace('?&task=Edit&mid=$mid&root_sender=$ls_root_sender&sender=$ls_sender&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
					echo "alert('$msg')";
					echo "</script>";	
					die();
				}
			} else {
				$error ++;
			}
		} 
		
		if ($error > 0 ) {
			// error saat melakukan pengecekan id pointer asal
			$msg = 'Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi';
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
			echo "window.location.replace('?&task=Edit&mid=$mid&root_sender=$ls_root_sender&sender=$ls_sender&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
			echo "alert('$msg')";
			echo "</script>";	
			die();
		}
	}
	// selesai
	
	if ($ls_kanal_pelayanan == "25")
	{
		$ls_kode_jenis_dokumen = "JD105";
		$ls_kode_dokumen_catatan_verifikasi = "JD105-D1014";
		$ls_kode_dokumen_elaborasi = "JD105-D1012";
	}
	else
	{
		if($ls_kode_tipe_klaim == "JHT01")
		{
			$ls_kode_jenis_dokumen = "JD101";
			$ls_kode_dokumen_catatan_verifikasi = "JD101-D1014";
			$ls_kode_dokumen_elaborasi = "JD101-D1012";
		}
		else if($ls_kode_tipe_klaim == "JKM01")
		{
			$ls_kode_jenis_dokumen = "JD102";
			$ls_kode_dokumen_catatan_verifikasi = "JD102-D1010";
			$ls_kode_dokumen_elaborasi = "JD102-D1009";
		}
		else if($ls_kode_tipe_klaim == "JPN01")
		{
			// cek lumsum atau berkala
			// JD103	DOKUMEN KLAIM JPN LUMSUM
			if ($ls_cnt_lumpsum > 0)
			{
				$ls_kode_jenis_dokumen = "JD103";
				$ls_kode_dokumen_catatan_verifikasi = "JD103-D1013"; // JD103-D1013	DOKUMEN CATATAN VERIFIKASI
				$ls_kode_dokumen_elaborasi = "JD103-D1012"; // JD103-D1012	DOKUMEN ELABORASI PMP
			}
			// JD104	DOKUMEN KLAIM JPN BERKALA
			if ($ls_cnt_berkala > 0)
			{
				$ls_kode_jenis_dokumen = "JD104";
				$ls_kode_dokumen_catatan_verifikasi = "JD104-D1014"; // JD104-D1014	DOKUMEN CATATAN VERIFIKASI
				$ls_kode_dokumen_elaborasi = "JD104-D1013"; // JD104-D1013	DOKUMEN ELABORASI PMP
			}
		}
		else if($ls_kode_tipe_klaim == "JKP01")
		{
			$ls_kode_jenis_dokumen = "JD107";
			$ls_kode_dokumen_catatan_verifikasi = "JD107-D1010";
			$ls_kode_dokumen_elaborasi = "JD107-D1009";
		}
		else if($ls_kode_tipe_klaim == "JKK01")
		{
			$ls_kode_jenis_dokumen = "JD108";
			$ls_kode_dokumen_catatan_verifikasi = "JD108-D1010";
			$ls_kode_dokumen_elaborasi = "JD108-D1009";

			
				// start generate ulang F3 KK PMI KANAL PELAYANAN 44
			if($ls_kanal_pelayanan=='44'){

				if($ls_kode_sebab_klaim=='SKK11' || $ls_kode_sebab_klaim=='SKK18' || $ls_kode_sebab_klaim=='SKK20' || $ls_kode_sebab_klaim=='SKK21' || $ls_kode_sebab_klaim=='SKK22' || $ls_kode_sebab_klaim=='SKK26') {
					$fileF3 = true;
				}else{


					$username = $_SESSION["USER"];

					$sqlbucket="
					select to_char(sysdate, 'yyyymm') blth,
					(select kode_kantor from pn.pn_klaim where kode_klaim = '$ls_kode_klaim') kode_kantor
					from dual";

					$DB->parse($sqlbucket);
					$DB->execute();
					$row = $DB->nextrow();
					$ls_blth = $row["BLTH"];
					$ls_kode_kantor_bucket = $row["KODE_KANTOR"];

					$ls_nama_bucket_storage = "arsip";
					$ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";

					$sql_cek_arsip = "SELECT ID_ARSIP
									FROM pn.pn_arsip_dokumen
								WHERE id_dokumen = '$ls_kode_klaim' AND kode_dokumen = 'JD108-D1007'";
					$DB->parse($sql_cek_arsip);
					$DB->execute();
					$cek_arsip = $DB->nextrow();
					$ls_cek_arsip = $cek_arsip["ID_ARSIP"];

					$headers = array(
						'Content-Type: application/json',
						'X-Forwarded-For: ' . $ipfwd
					);

					if($ls_cek_arsip){
						$data_remove_document = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idArsip" => $ls_cek_arsip
						);
						
						$result_removedocument = api_json_call($wsIpDocument . "/JSDS/RemoveDocumentByIdArsip", $headers,  $data_remove_document);
					}

					if($result_removedocument->ret=='0'){

						$fileF3 = $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$antrian_rpt_user."&password=".$antrian_rpt_pass."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$antrian_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0017.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27";  

						$data_store = array(
							"chId" => "SMILE", 
							"reqId" => $username, 
							"idDokumen" => $ls_kode_klaim,
							"namaBucketTujuan" => $ls_nama_bucket_storage,  
							"namaFolderTujuan" => $ls_nama_folder_storage,
							"docs" => array(
							array(
							// DOKUMEN F3 KK
							"kodeJenisDokumen" => "JD108", 
							"kodeDokumen" => "JD108-D1007", 
							"urlDokumen" => $fileF3 									
							)  
							)
							);
						
							$result_store = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store);
					}		


					
				}
			
			}


				
			
			


		}
	}
	
	if($ls_jml_dokumen_digital > 0)
	{
		$headers = array
		(
			'Content-Type: application/json',
			'X-Forwarded-For: ' . $ipfwd
		);
		
		$data_status_document = array
		(
			"chId" => "SMILE",
			"reqId" => $username, 
			"kodeJenisDokumen" => $ls_kode_jenis_dokumen,
			"idDokumen" => $ls_kode_klaim
		); 
		
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
			
			$sql = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = '$ls_kode_klaim' ";											 	
			$ECDB->parse($sql);
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
				  'reqId'             => $username,
				  "kodePengajuan"		=> $ls_id_pointer_asal,
				  "statusPengajuan"	=> "KLJKP003",
				  "keterangan"		=> "",
				  "petugasRekam"		=> $username
				);

				$result = api_json_call($wsIp . "/JSKlaimJKP/UpdateClaimStatus", $headers,  $data_send);
		   
		  }
		}else
		{
			$ls_error = "1";
			$msg = $ls_mess; 
		}
		// membentuk dokumen digital
		if ($ls_sukses =="1")
		{
			function dashesToCamelCase($string) 
			{
				$str = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($string))));
				//echo $str;
			if (true) {
				$str[0] = strtolower($str[0]);
			}
				return $str;
			}
  
			$username = $_SESSION["USER"];

			$sqlbucket="
			select to_char(sysdate, 'yyyymm') blth,
			(select kode_kantor from pn.pn_klaim where kode_klaim = '$ls_kode_klaim') kode_kantor
			from dual";

			$DB->parse($sqlbucket);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_blth = $row["BLTH"];
			$ls_kode_kantor_bucket = $row["KODE_KANTOR"];

			$ls_nama_bucket_storage = "arsip";
			$ls_nama_folder_storage = "$ls_kode_kantor_bucket/$ls_blth/klaim";
  
			// JD101-D1014
			// DOKUMEN CATATAN VERIFIKASI
			$ls_url_file_catatan_verifikasi         = $ipReportServerDocument."/reports/rwservlet/setauth?button=Submit&username=".$antrian_rpt_user."&password=".$antrian_rpt_pass."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$antrian_rpt_sid."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DSIR0014.rdf%26userid%3D%2Fdata%2Freports%2Fchannel%26%26P_KODE_KLAIM%3D%27".$ls_kode_klaim."%27";
			$ls_jenis_dokumen_catatan_verifikasi    = $ls_kode_jenis_dokumen;
			$ls_kode_dokumen_catatan_verifikasi     = $ls_kode_dokumen_catatan_verifikasi;
			
			

			
			$doc_jd101_d1014 = array(
			  "chId" => "SMILE", 
			  "reqId" => $username, 
			  "idDokumen" => $ls_kode_klaim,
			  "kodeJenisDokumen" => $ls_jenis_dokumen_catatan_verifikasi, 
			  "kodeDokumen" => $ls_kode_dokumen_catatan_verifikasi, 
			  "urlDokumen" => $ls_url_file_catatan_verifikasi,
			  "namaBucketTujuan" => $ls_nama_bucket_storage, 
			  "namaFolderTujuan" => $ls_nama_folder_storage
			);
			
			// JD101-D1012
			// DOKUMEN ELABORASI PMP
			$ls_url_file_elaborasi         = "";
			$ls_jenis_dokumen_elaborasi    = $ls_kode_jenis_dokumen;
			$ls_kode_dokumen_elaborasi     = $ls_kode_dokumen_elaborasi;
			$sql_dokumen_elaborasi = " 
					SELECT 
					'DOKUMEN ELABORASI' PEMILIK,
					B.MIME_TYPE,
					trim(B.NAMA_DOKUMEN_TAMBAHAN) NAMA_DOKUMEN, 
					'$wsIpStorage' || PATH_URL AS URL_DOKUMEN,
					'$username'
					|| '/'
					|| (SELECT A.KODE_KANTOR FROM PN.PN_KLAIM A WHERE A.KODE_KLAIM = B.KODE_KLAIM AND ROWNUM=1) 
					|| '/'
					|| TO_CHAR (TRUNC (SYSDATE), 'DDMMRRRR')
						INFO_CETAK
					 from PN.PN_KLAIM_DOKUMEN_TAMBAHAN B
					 WHERE KODE_KLAIM = '$ls_kode_klaim'
					 AND SYARAT_TAHAP_KE = '2' 
					 ORDER BY NO_URUT ASC
			";
			
			$DB->parse($sql_dokumen_elaborasi);
			if($DB->execute()){ 
			  $i = 0;
			  $itotal = 0;
			  $jdata = array();
			  while($data = $DB->nextrow()){
				$hasil = array();
				foreach( $data as $key => $value ){
				$hasil[dashesToCamelCase($key)] = $value;
				  // echo $value;
			  } 
				$jdata[] = $hasil;
				$i++;
				$itotal++;
			  } 
			}
			$docs=$jdata;
			$data_storedocument_elaborasi = array(
			  "chId" => "SMILE", 
			  "reqId" => $username, 
			  "idDokumen" => $ls_kode_klaim, 
			  "kodeJenisDokumen" => $ls_jenis_dokumen_elaborasi, 
			  "kodeDokumen" => $ls_kode_dokumen_elaborasi, 
			  "namaBucketTujuan" => $ls_nama_bucket_storage, 
			  "namaFolderTujuan" => $ls_nama_folder_storage, 
			  "docs" => $docs
			);
			
			$data_storedocument = $doc_jd101_d1014;

			// -----------------------------start update pending matters 09032022------------------------
			$sql_cek_arsip = "SELECT ID_ARSIP
									FROM pn.pn_arsip_dokumen
								WHERE id_dokumen = '$ls_kode_klaim' AND kode_dokumen = '$ls_kode_dokumen_elaborasi'";
			$DB->parse($sql_cek_arsip);
			$DB->execute();
			$cek_arsip = $DB->nextrow();
			$ls_cek_arsip = $cek_arsip["ID_ARSIP"];

			if($ls_cek_arsip){
				$data_remove_document = array(
					"chId" => "SMILE", 
					"reqId" => $username, 
					"idArsip" => $ls_cek_arsip
				  );
				
				$result_storedocument = api_json_call($wsIpDocument . "/JSDS/RemoveDocumentByIdArsip", $headers,  $data_remove_document);
			}

			

			// -----------------------------end update pending matters 09032022--------------------------
			
			 $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreDocument", $headers,  $data_storedocument);
			 $result_storedocument = api_json_call($wsIpDocument . "/JSDS/StoreMergeDocument", $headers,  $data_storedocument_elaborasi);
		}
		
		$msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "window.opener.location.replace('../form/pn5041.php?mid=$ls_sender_mid');";
		//echo "window.close();";
		echo "</script>";	

		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
		echo "window.location.replace('?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&root_sender=$ls_root_sender&sender=$ls_sender&activetab=1&mid=$ls_sender_mid&msg=$msg');";
		echo "</script>";
		
		
	}
	else
	{
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

			$sql = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = '$ls_kode_klaim' ";											 	
			$ECDB->parse($sql);
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
				  'reqId'             => $username,
				  "kodePengajuan"		=> $ls_id_pointer_asal,
				  "statusPengajuan"	=> "KLJKP003",
				  "keterangan"		=> "",
				  "petugasRekam"		=> $username
				);

				$result = api_json_call($wsIp . "/JSKlaimJKP/UpdateClaimStatus", $headers,  $data_send);
			  }
		}else
		{
			$ls_error = "1";
			$msg = $ls_mess; 
		}
		
		

		$msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "window.opener.location.replace('../form/pn5041.php?mid=$ls_sender_mid');";
		//echo "window.close();";
		echo "</script>";	

		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
		echo "window.location.replace('?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&root_sender=$ls_root_sender&sender=$ls_sender&activetab=1&mid=$ls_sender_mid&msg=$msg');";
		echo "</script>";
	}
	
	
/*
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
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "window.opener.location.replace('../form/pn5041.php?mid=$ls_sender_mid');";
	//echo "window.close();";
  echo "</script>";	
	
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  echo "window.location.replace('?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&root_sender=$ls_root_sender&sender=$ls_sender&activetab=1&mid=$ls_sender_mid&msg=$msg');";
  echo "</script>";	
*/  									
}

if($btn_task=="submit_ajujkk1_tanpa_otentikasi")
{
  //submit data penetapan klaim jkk tahan I menjadi agenda tahap II ------------	
  								  
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_SUBMIT('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	

  $sql_rtw = "select x.cnt_rtw from (
  				select count(*) cnt_rtw
				from PN.PN_KLAIM a
				where a.KODE_TIPE_KLAIM = 'JKK01'
				and a.KODE_SEGMEN = 'PU'
				and nvl(a.STATUS_BATAL,'X') = 'T'
				and nvl(a.FLAG_RTW,'X') = 'Y'
				and a.KODE_KLAIM = '$ls_kode_klaim') x";

	$DB->parse($sql_rtw);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_cnt_rtw                  = $row['CNT_RTW'];
	

	if($ls_cnt_rtw > 0){
	$qry_rtw = "BEGIN
					PN.P_PN_PN5031.X_POST_INSERT_AGENDA_RTW ('$ls_kode_klaim',
															'66',
															'$username',
															:P_SUKSES,
															:P_MESS);
				END;";
	$proc = $DB->parse($qry_rtw);       
	oci_bind_by_name($proc, ":P_SUKSES", $p_suksess,2);
	oci_bind_by_name($proc, ":P_MESS", $p_messs,1000);
	$DB->execute();
	}
  
  if ($ls_sukses=="1")
  {
    $msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 		
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  	echo "window.location.replace('../form/$ls_root_sender?mid=$ls_sender_mid');";
    echo "</script>";	
  }else
  {
		$msg = $ls_mess;
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?&task=Edit&mid=$mid&root_sender=$ls_root_sender&sender=$ls_sender&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
    echo "alert('$msg')";
		echo "</script>";							
  }								
}

if($btn_task=="simpan_data_ajujkk1")
{
  //simpan data penetapan jkk tahap I ------------------------------------------
  $ls_tapjkk1_kode_tindakan_bahaya	= $_POST["tapjkk1_kode_tindakan_bahaya"];
  $ls_tapjkk1_kode_kondisi_bahaya		=	$_POST["tapjkk1_kode_kondisi_bahaya"];
  $ls_tapjkk1_kode_corak						=	$_POST["tapjkk1_kode_corak"];
  $ls_tapjkk1_nama_corak						=	$_POST["tapjkk1_nama_corak"];
  $ls_tapjkk1_kode_sumber_cedera		=	$_POST["tapjkk1_kode_sumber_cedera"];							
  $ls_tapjkk1_nama_sumber_cedera		=	$_POST["tapjkk1_nama_sumber_cedera"];
  $ls_tapjkk1_kode_bagian_sakit			=	$_POST["tapjkk1_kode_bagian_sakit"];
  $ls_tapjkk1_nama_bagian_sakit			=	$_POST["tapjkk1_nama_bagian_sakit"];
  $ls_tapjkk1_kode_akibat_diderita	= $_POST["tapjkk1_kode_akibat_diderita"];
  $ls_tapjkk1_nama_akibat_diderita	= $_POST["tapjkk1_nama_akibat_diderita"];
  $ls_tapjkk1_kode_lama_bekerja			= $_POST["tapjkk1_kode_lama_bekerja"];
  $ls_tapjkk1_kode_penyakit_timbul	= $_POST["tapjkk1_kode_penyakit_timbul"];						
  $ls_tapjkk1_nama_penyakit_timbul	= $_POST["tapjkk1_nama_penyakit_timbul"];
  $ls_tapjkk1_kode_tempat_perawatan = $_POST["tapjkk1_kode_tempat_perawatan"];		
  $ls_tapjkk1_kode_berobat_jalan		= $_POST["tapjkk1_kode_berobat_jalan"];
  $ls_tapjkk1_kode_ppk							= $_POST["tapjkk1_kode_ppk"];
  $ls_tapjkk1_nama_ppk							= $_POST["tapjkk1_nama_ppk"];
  $ls_tapjkk1_nama_faskes_reimburse	= $_POST["tapjkk1_nama_faskes_reimburse"];	
	$ls_tapjkk1_kode_tipe_upah				= $_POST["tapjkk1_kode_tipe_upah"];
	$ln_tapjkk1_nom_upah_terakhir			= str_replace(',','',$_POST["tapjkk1_nom_upah_terakhir"]);
	$ln_tapjkk1_blth_upah_terakhir		= $_POST["tapjkk1_blth_upah_terakhir"];
	$ls_tapjkk1_flag_rtw		= $_POST["tapjkk1_flag_rtw"];
	
  $qry = "update sijstk.pn_klaim ".
			 	 "set    kode_tindakan_bahaya   = '$ls_tapjkk1_kode_tindakan_bahaya', ".
         "       kode_kondisi_bahaya    = '$ls_tapjkk1_kode_kondisi_bahaya', ".
         "       kode_corak             = '$ls_tapjkk1_kode_corak', ".
         "       kode_sumber_cedera     = '$ls_tapjkk1_kode_sumber_cedera', ".
         "       kode_bagian_sakit      = '$ls_tapjkk1_kode_bagian_sakit', ".
         "       kode_akibat_diderita   = '$ls_tapjkk1_kode_akibat_diderita', ".
         "       kode_lama_bekerja      = '$ls_tapjkk1_kode_lama_bekerja', ".
         "       kode_penyakit_timbul   = '$ls_tapjkk1_kode_penyakit_timbul', ".
         "       kode_tipe_upah         = '$ls_tapjkk1_kode_tipe_upah', ".
         "       nom_upah_terakhir      = '$ln_tapjkk1_nom_upah_terakhir', ".
				 "       blth_upah_terakhir     = to_date('$ln_tapjkk1_blth_upah_terakhir','dd/mm/yyyy'), ".
         "       kode_tempat_perawatan  = '$ls_tapjkk1_kode_tempat_perawatan', ".
         "       kode_berobat_jalan     = '$ls_tapjkk1_kode_berobat_jalan', ".
         "       kode_ppk               = '$ls_tapjkk1_kode_ppk', ".
         "       nama_faskes_reimburse  = '$ls_tapjkk1_nama_faskes_reimburse', ". 
		 "       flag_rtw			    = '$ls_tapjkk1_flag_rtw', ".           
         "       tgl_ubah               = sysdate, ".
         "       petugas_ubah           = '$username' ".
         "where  kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($qry);
  $DB->execute();

  //insert data diagnosa ---------------------------------------------------
  $sql = "delete from sijstk.pn_klaim_diagnosa where kode_klaim = '$ls_kode_klaim' ";				
  $DB->parse($sql);
  $DB->execute();
  
  $ln_panjang = $_POST['tapjkk1_dtldiag_kounter_dtl'];
  for($i=0;$i<=$ln_panjang-1;$i++)
  {		 	           												 		        
    $ls_tapjkk1_dtldiag_nama_tenaga_medis		= $_POST['tapjkk1_dtldiag_nama_tenaga_medis'.$i];
    $ls_tapjkk1_dtldiag_kode_diagnosa_detil	= $_POST['tapjkk1_dtldiag_kode_diagnosa_detil'.$i];
    $ls_tapjkk1_dtldiag_keterangan					= $_POST['tapjkk1_dtldiag_keterangan'.$i];
    
    if ($ls_tapjkk1_dtldiag_nama_tenaga_medis!="" || $ls_tapjkk1_dtldiag_kode_diagnosa_detil!="" || $ls_tapjkk1_dtldiag_keterangan!="")
    {
      $sql = 	"select nvl(max(no_urut),0)+1 as v_no from sijstk.pn_klaim_diagnosa ".
      		 		"where kode_klaim = '$ls_kode_klaim' ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ln_no_urut = $row["V_NO"];	
      
      $sql = "insert into sijstk.pn_klaim_diagnosa ( ".
             "  kode_klaim, no_urut, nama_tenaga_medis, kode_diagnosa_detil, keterangan, ". 
             "  tgl_rekam, petugas_rekam) ".
             "values ( ".
             "	'$ls_kode_klaim','$ln_no_urut','$ls_tapjkk1_dtldiag_nama_tenaga_medis','$ls_tapjkk1_dtldiag_kode_diagnosa_detil','$ls_tapjkk1_dtldiag_keterangan', ".
             "	sysdate, '$username' ". 		 
             ")";		
      $DB->parse($sql);
      $DB->execute();
    }							
  }     			
  //end insert data diagnosa -----------------------------------------------
  
  //insert data aktivitas pelaporan ----------------------------------------
  $sql = "delete from sijstk.pn_klaim_aktivitas_pelaporan where kode_klaim = '$ls_kode_klaim' ";				
  $DB->parse($sql);
  $DB->execute();
  
  $ln_panjang = $_POST['tapjkk1_dtlaktvpelap_kounter_dtl'];
  for($i=0;$i<=$ln_panjang-1;$i++)
  {				 	           												 		        
    $ld_tapjkk1_dtlaktvpelap_tgl_aktivitas	= $_POST['tapjkk1_dtlaktvpelap_tgl_aktivitas'.$i];
    $ls_tapjkk1_dtlaktvpelap_nama_aktivitas	= $_POST['tapjkk1_dtlaktvpelap_nama_aktivitas'.$i];
    $ls_tapjkk1_dtlaktvpelap_nama_sumber		= $_POST['tapjkk1_dtlaktvpelap_nama_sumber'.$i];
    $ls_tapjkk1_dtlaktvpelap_profesi_sumber	= $_POST['tapjkk1_dtlaktvpelap_profesi_sumber'.$i];
    $ls_tapjkk1_dtlaktvpelap_alamat					= $_POST['tapjkk1_dtlaktvpelap_alamat'.$i];
    $ls_tapjkk1_dtlaktvpelap_telepon_area		= $_POST['tapjkk1_dtlaktvpelap_telepon_area'.$i];
    $ls_tapjkk1_dtlaktvpelap_telepon				= $_POST['tapjkk1_dtlaktvpelap_telepon'.$i];
    $ls_tapjkk1_dtlaktvpelap_keterangan			= $_POST['tapjkk1_dtlaktvpelap_keterangan'.$i];
    
    if ($ld_tapjkk1_dtlaktvpelap_tgl_aktivitas!="" || $ls_tapjkk1_dtlaktvpelap_nama_aktivitas!="")
    {
      $sql = 	"select nvl(max(no_urut),0)+1 as v_no from sijstk.pn_klaim_aktivitas_pelaporan ".
      		 		"where kode_klaim = '$ls_kode_klaim' ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ln_no_urut = $row["V_NO"];	
      
      $sql = "insert into sijstk.pn_klaim_aktivitas_pelaporan ( ".
             "	kode_klaim, no_urut, tgl_aktivitas, nama_aktivitas, nama_sumber, profesi_sumber, ". 
             "	alamat, rt, rw, kode_kelurahan, kode_kecamatan, kode_kabupaten, kode_pos, ". 
             "	telepon_area, telepon, telepon_ext, handphone, email, npwp, status, hasil_tindak_lanjut, ". 
             "	keterangan, tgl_rekam, petugas_rekam) ".
             "values ( ".
             "	'$ls_kode_klaim','$ln_no_urut',to_date('$ld_tapjkk1_dtlaktvpelap_tgl_aktivitas','dd/mm/yyyy'),'$ls_tapjkk1_dtlaktvpelap_nama_aktivitas','$ls_tapjkk1_dtlaktvpelap_nama_sumber','$ls_tapjkk1_dtlaktvpelap_profesi_sumber', ".
             "	'$ls_tapjkk1_dtlaktvpelap_alamat', null, null, null, null, null, null, ".
             "	'$ls_tapjkk1_dtlaktvpelap_telepon_area', '$ls_tapjkk1_dtlaktvpelap_telepon', null, null, null, null, null, null, ". 
             "	'$ls_tapjkk1_dtlaktvpelap_keterangan', sysdate, '$username' ". 		 
             ")";		
      $DB->parse($sql);
      $DB->execute();
    }							
  }     			
  //end insert data aktivitas pelaporan ----------------------------------------						
  
	//insert data yg baru ada di agenda tahap II ---------------------------------
  $msg = "Simpan data penetapan berhasil dilakukan, session dilanjutkan...";
  $task = "edit";   		
  
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "window.location.replace('?&task=Edit&mid=$mid&root_sender=$ls_root_sender&sender=$ls_sender&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
  echo "</script>";		
}
	
//query data -------------------------------------------------------------------
$sql = "select 
          a.kode_klaim, a.no_klaim, a.kode_kantor, (select nama_kantor from sijstk.ms_kantor where kode_kantor=a.kode_kantor) nama_kantor,
					a.kode_segmen, (select nama_segmen from sijstk.kn_kode_segmen where kode_segmen = a.kode_segmen) nama_segmen, a.kode_perusahaan, 
          (select nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) nama_perusahaan,
          (select npp from sijstk.kn_perusahaan where kode_perusahaan = a.kode_perusahaan) npp, 
          a.kode_divisi, (select nama_divisi from sijstk.kn_divisi where kode_perusahaan = a.kode_perusahaan and kode_segmen = a.kode_segmen and kode_divisi=a.kode_divisi) nama_divisi,
          a.kode_proyek, (select nama_proyek from sijstk.jn_proyek where kode_proyek = a.kode_proyek) nama_proyek, 
          a.kode_tk, a.nama_tk, (select kpj from sijstk.kn_tk where kode_tk = a.kode_tk) kpj,
          a.nomor_identitas, a.jenis_identitas, a.kode_kantor_tk, substr(a.kode_tipe_klaim,1,3) jenis_klaim, 
          a.kode_tipe_klaim, (select nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim = a.kode_tipe_klaim) nama_tipe_klaim, 
          a.kode_sebab_klaim, (select nama_sebab_klaim from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) nama_sebab_klaim,
          (select keyword from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) keyword_sebab_klaim,
					(select nvl(flag_meninggal,'T') from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) flag_meninggal,
					(select nvl(flag_agenda_12,'T') from sijstk.pn_kode_sebab_klaim where kode_sebab_klaim = a.kode_sebab_klaim) flag_agenda_12,
					to_char(a.tgl_klaim,'dd/mm/yyyy') tgl_klaim,
          to_char(a.tgl_lapor,'dd/mm/yyyy') tgl_lapor,     
          a.kanal_pelayanan, a.flag_rtw, a.keterangan,               
          a.kode_pelaporan, a.kanal_pelayanan, nvl(a.flag_rtw,'T') flag_rtw,
          to_char(a.tgl_kecelakaan,'dd/mm/yyyy') tgl_kecelakaan, 
					a.kode_jam_kecelakaan,
					(select keterangan from sijstk.ms_lookup where tipe='KLMJAMKERJ' and kode = a.kode_jam_kecelakaan) nama_jam_kecelakaan, 
          a.kode_jenis_kasus, 
					(select nama_jenis_kasus from sijstk.pn_kode_jenis_kasus where kode_jenis_kasus=a.kode_jenis_kasus) nama_jenis_kasus,
					a.kode_lokasi_kecelakaan, 
					(select nama_lokasi_kecelakaan from sijstk.pn_kode_lokasi_kecelakaan where kode_lokasi_kecelakaan=a.kode_lokasi_kecelakaan) nama_lokasi_kecelakaan,
					a.nama_tempat_kecelakaan, 
          a.kode_tindakan_bahaya, a.kode_kondisi_bahaya, 
					a.kode_corak, (select keterangan from sijstk.ms_lookup where tipe='KLMCORAK' and kode=a.kode_corak) nama_corak,
          a.kode_sumber_cedera,(select keterangan from sijstk.ms_lookup where tipe='KLMSMBRCDR' and kode=a.kode_sumber_cedera) nama_sumber_cedera, 
					a.kode_bagian_sakit, (select keterangan from sijstk.ms_lookup where tipe='KLMBGSAKIT' and kode=a.kode_bagian_sakit) nama_bagian_sakit,
					a.kode_akibat_diderita, (select nama_akibat_diderita from sijstk.pn_kode_akibat_diderita where kode_akibat_diderita=a.kode_akibat_diderita) nama_akibat_diderita,
          a.kode_lama_bekerja,
					a.kode_penyakit_timbul,
					a.kode_tipe_upah, 
          a.nom_upah_terakhir, to_char(a.blth_upah_terakhir,'dd/mm/yyyy') blth_upah_terakhir, a.kode_tempat_perawatan, a.kode_berobat_jalan, 
          a.kode_ppk,(select nama_faskes from sijstk.tc_faskes where kode_faskes = a.kode_ppk) nama_ppk, 
					a.nama_faskes_reimburse, a.kode_kondisi_terakhir, 
          to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, 
          to_char(a.tgl_kematian,'dd/mm/yyyy') tgl_kematian, 
          to_char(a.tgl_mulai_pensiun,'dd/mm/yyyy') tgl_mulai_pensiun, 
          a.status_pernikahan, a.ket_tambahan,
          nvl(a.status_kelayakan,'B') status_kelayakan, a.ket_kelayakan,
          nvl(a.status_submit_agenda,'T') status_submit_agenda, tgl_submit_agenda, petugas_submit_agenda, 
          nvl(a.status_submit_pengajuan,'T') status_submit_pengajuan, tgl_submit_pengajuan, petugas_submit_pengajuan, 
          nvl(a.status_submit_agenda2,'T') status_submit_agenda2, tgl_submit_agenda2, petugas_submit_agenda2, 
          nvl(a.status_submit_penetapan,'T') status_submit_penetapan, tgl_submit_penetapan, petugas_submit_penetapan,
					to_char(a.tgl_penetapan,'dd/mm/yyyy') tgl_penetapan, a.no_penetapan, a.petugas_penetapan,  
          nvl(a.status_approval,'T') status_approval, tgl_approval, petugas_approval,							  
          nvl(a.status_batal,'T') status_batal, a.tgl_batal, a.petugas_batal, a.ket_batal, 
          nvl(a.status_lunas,'T') status_lunas, a.tgl_lunas, a.petugas_lunas,
					a.petugas_rekam, to_char(a.tgl_rekam,'dd/mm/yyyy hh24:mi:ss') tgl_rekam,
          a.kode_klaim_induk, a.kode_klaim_anak,
          to_char(a.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, a.status_kepesertaan, 
          a.kode_perlindungan, to_char(a.tgl_awal_perlindungan,'dd/mm/yyyy') tgl_awal_perlindungan, 
          to_char(a.tgl_akhir_perlindungan,'dd/mm/yyyy') tgl_akhir_perlindungan,
          to_char(a.tgl_awal_perlindungan,'dd/mm/yyyy')||' s.d '||to_char(a.tgl_akhir_perlindungan,'dd/mm/yyyy') ket_masa_perlindungan,									
          a.status_klaim, a.kode_pointer_asal, a.id_pointer_asal, a.tipe_pelaksana_kegiatan, a.nama_pelaksana_kegiatan,
          case when a.kode_pointer_asal = 'PROMOTIF' then
          (   select x.kode_kegiatan||'-'||x.nama_sub_kegiatan||'-'||x.nama_detil_kegiatan from sijstk.pn_promotif_realisasi x
          		where kode_realisasi = a.id_pointer_asal          
          ) 
          else
          		'KLAIM'
          end nama_kegiatan,
					(
							select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
                where x.kode_klaim = a.kode_klaim
                and x.kode_manfaat = y.kode_manfaat
                and nvl(y.flag_berkala,'T')='Y'
                and nvl(x.nom_biaya_disetujui,0)<>0
					) cnt_berkala,
					(
							select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
                where x.kode_klaim = a.kode_klaim
                and x.kode_manfaat = y.kode_manfaat
                and nvl(y.flag_berkala,'T')='T'
                and nvl(x.nom_biaya_disetujui,0)<>0
					) cnt_lumpsum,
					a.negara_penempatan, a.tipe_negara_kejadian						
        from sijstk.pn_klaim a
        where kode_klaim = '".$_GET['dataid']."' ";
//echo $sql;
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_kode_klaim 						 		= $row['KODE_KLAIM'];		
$ls_no_klaim 									= $row['NO_KLAIM'];
$ls_kode_kantor 							= $row['KODE_KANTOR'];
$ls_nama_kantor 							= $row['NAMA_KANTOR'];
$ls_kode_segmen 							= $row['KODE_SEGMEN'];
$ls_nama_segmen 							= $row['NAMA_SEGMEN'];
$ls_kode_perusahaan 					= $row['KODE_PERUSAHAAN'];
$ls_nama_perusahaan						= $row['NAMA_PERUSAHAAN'];
$ls_npp 											= $row['NPP'];
$ls_kode_divisi 							= $row['KODE_DIVISI'];
$ls_nama_divisi 							= $row['NAMA_DIVISI'];
$ls_kode_proyek 							= $row['KODE_PROYEK'];
$ls_nama_proyek  							= $row['NAMA_PROYEK'];
$ls_kode_tk 									= $row['KODE_TK'];
$ls_kpj 											= $row['KPJ'];
$ls_nama_tk 									= $row['NAMA_TK'];
$ls_nomor_identitas 					= $row['NOMOR_IDENTITAS'];
$ls_jenis_identitas 					= $row['JENIS_IDENTITAS'];
$ls_kode_kantor_tk 						= $row['KODE_KANTOR_TK'];
$ls_kode_tipe_klaim 					= $row['KODE_TIPE_KLAIM'];
$ls_nama_tipe_klaim						= $row['NAMA_TIPE_KLAIM'];
$ls_kode_sebab_klaim 					= $row['KODE_SEBAB_KLAIM'];
$ls_nama_sebab_klaim 					= $row['NAMA_SEBAB_KLAIM'];
$ls_keyword_sebab_klaim				= $row['KEYWORD_SEBAB_KLAIM'];
$ls_jenis_klaim 							= $row['JENIS_KLAIM'];
$ld_tgl_klaim 								= $row['TGL_KLAIM'];
$ld_tgl_lapor 								= $row['TGL_LAPOR'];
$ld_tgl_kejadian							= $row['TGL_KEJADIAN'];
$ls_status_kepesertaan 				= $row['STATUS_KEPESERTAAN'];
$ls_kode_perlindungan					= $row['KODE_PERLINDUNGAN'];
$ld_tgl_awal_perlindungan			= $row['TGL_AWAL_PERLINDUNGAN'];
$ld_tgl_akhir_perlindungan 		= $row['TGL_AKHIR_PERLINDUNGAN'];
$ls_ket_masa_perlindungan	 		= $row['KET_MASA_PERLINDUNGAN'];
$ls_negara_penempatan	 				= $row['NEGARA_PENEMPATAN'];
$ls_tipe_negara_kejadian			= $row['TIPE_NEGARA_KEJADIAN'];	
$ls_flag_agenda_12 						= $row['FLAG_AGENDA_12'];	
$ls_keterangan 								= $row['KETERANGAN'];	    
$ls_kode_pelaporan 						= $row['KODE_PELAPORAN'];
$ls_kanal_pelayanan 					= $row['KANAL_PELAYANAN'];
$ls_flag_rtw 									= $row['FLAG_RTW'];
$ls_kode_klaim_induk 					= $row['KODE_KLAIM_INDUK'];
$ls_kode_klaim_anak 					= $row['KODE_KLAIM_ANAK'];
$ld_tgl_kecelakaan						= $row['TGL_KECELAKAAN'];
$ls_kode_jam_kecelakaan 			= $row['KODE_JAM_KECELAKAAN'];
$ls_nama_jam_kecelakaan 			= $row['NAMA_JAM_KECELAKAAN'];
$ls_kode_jenis_kasus				  = $row['KODE_JENIS_KASUS'];
$ls_nama_jenis_kasus				  = $row['NAMA_JENIS_KASUS'];
$ls_kode_lokasi_kecelakaan		= $row['KODE_LOKASI_KECELAKAAN'];
$ls_nama_lokasi_kecelakaan		= $row['NAMA_LOKASI_KECELAKAAN'];
$ls_nama_tempat_kecelakaan 		= $row['NAMA_TEMPAT_KECELAKAAN'];	
$ls_kode_tindakan_bahaya			= $row['KODE_TINDAKAN_BAHAYA'];
$ls_kode_kondisi_bahaya				= $row['KODE_KONDISI_BAHAYA'];
$ls_kode_corak 								= $row['KODE_CORAK'];	
$ls_nama_corak 								= $row['NAMA_CORAK'];
$ls_kode_sumber_cedera				= $row['KODE_SUMBER_CEDERA'];
$ls_nama_sumber_cedera				= $row['NAMA_SUMBER_CEDERA'];
$ls_kode_bagian_sakit					= $row['KODE_BAGIAN_SAKIT'];
$ls_nama_bagian_sakit					= $row['NAMA_BAGIAN_SAKIT'];
$ls_kode_akibat_diderita 			= $row['KODE_AKIBAT_DIDERITA'];
$ls_nama_akibat_diderita 			= $row['NAMA_AKIBAT_DIDERITA'];					
$ls_kode_lama_bekerja					= $row['KODE_LAMA_BEKERJA'];
$ls_kode_penyakit_timbul			= $row['KODE_PENYAKIT_TIMBUL'];
$ls_kode_tipe_upah 						= $row['KODE_TIPE_UPAH'];
$ln_nom_upah_terakhir					= $row['NOM_UPAH_TERAKHIR'];
$ld_blth_upah_terakhir				= $row['BLTH_UPAH_TERAKHIR'];
$ls_kode_tempat_perawatan			= $row['KODE_TEMPAT_PERAWATAN'];
$ls_kode_berobat_jalan 				= $row['KODE_BEROBAT_JALAN'];
$ls_kode_ppk									= $row['KODE_PPK'];
$ls_nama_ppk									= $row['NAMA_PPK'];
$ls_nama_faskes_reimburse			= $row['NAMA_FASKES_REIMBURSE'];
$ls_kode_kondisi_terakhir 		= $row['KODE_KONDISI_TERAKHIR'];
$ld_tgl_kondisi_terakhir			= $row['TGL_KONDISI_TERAKHIR'];
$ld_tgl_kematian							= $row['TGL_KEMATIAN'];
$ld_tgl_mulai_pensiun 				= $row['TGL_MULAI_PENSIUN'];
$ls_status_pernikahan					= $row['STATUS_PERNIKAHAN'];
$ls_ket_tambahan    					= $row['KET_TAMBAHAN'];
$ls_status_kelayakan 					= $row['STATUS_KELAYAKAN'];
$ls_ket_kelayakan 						= $row['KET_KELAYAKAN'];
$ls_status_submit_agenda			= $row['STATUS_SUBMIT_AGENDA'];
$ld_tgl_submit_agenda					= $row['TGL_SUBMIT_AGENDA'];
$ls_petugas_submit_agenda 		= $row['PETUGAS_SUBMIT_AGENDA'];
$ls_status_submit_pengajuan		= $row['STATUS_SUBMIT_PENGAJUAN'];
$ld_tgl_submit_pengajuan			= $row['TGL_SUBMIT_PENGAJUAN'];
$ls_petugas_submit_pengajuan 	= $row['PETUGAS_SUBMIT_PENGAJUAN'];
$ls_status_submit_agenda2			= $row['STATUS_SUBMIT_AGENDA2'];
$ld_tgl_submit_agenda2				= $row['TGL_SUBMIT_AGENDA2'];
$ls_petugas_submit_agenda2 		= $row['PETUGAS_SUBMIT_AGENDA2'];
$ls_status_submit_penetapan		= $row['STATUS_SUBMIT_PENETAPAN'];
$ld_tgl_submit_penetapan			= $row['TGL_SUBMIT_PENETAPAN'];
$ls_petugas_submit_penetapan 	= $row['PETUGAS_SUBMIT_PENETAPAN'];
$ld_tgl_penetapan							= $row['TGL_PENETAPAN'];
$ls_no_penetapan							= $row['NO_PENETAPAN'];
$ls_petugas_penetapan					= $row['PETUGAS_PENETAPAN'];
$ls_status_approval						= $row['STATUS_APPROVAL'];
$ld_tgl_approval							= $row['TGL_APPROVAL'];
$ls_petugas_approval					= $row['PETUGAS_APPROVAL'];		
$ls_status_batal 							= $row['STATUS_BATAL'];
$ls_tgl_batal 								= $row['TGL_BATAL'];
$ls_petugas_batal 						= $row['PETUGAS_BATAL'];
$ls_ket_batal 								= $row['KET_BATAL'];    
$ls_status_lunas 							= $row['STATUS_LUNAS'];
$ld_tgl_lunas 								= $row['TGL_LUNAS'];
$ls_petugas_lunas 						= $row['PETUGAS_LUNAS'];
$ld_tgl_rekam 								= $row['TGL_REKAM'];
$ls_petugas_rekam 						= $row['PETUGAS_REKAM'];
$ls_status_klaim 							= $row['STATUS_KLAIM'];
$ls_kode_pointer_asal					= $row['KODE_POINTER_ASAL'];
$ls_id_pointer_asal						= $row['ID_POINTER_ASAL'];
$ls_tipe_pelaksana_kegiatan		= $row['TIPE_PELAKSANA_KEGIATAN'];
$ls_nama_pelaksana_kegiatan		= $row['NAMA_PELAKSANA_KEGIATAN'];
$ls_nama_kegiatan							= $row['NAMA_KEGIATAN'];
$ln_cnt_berkala								= $row['CNT_BERKALA'];
$ln_cnt_lumpsum								= $row['CNT_LUMPSUM'];

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
			
if ($ln_cnt_berkala>"0")
{
	 $ls_flag_berkala						= "Y";		
}else
{
$ls_flag_berkala						= "T";
}	

if ($ln_cnt_lumpsum>"0")
{
	 $ls_flag_lumpsum						= "Y";		
}else
{
$ls_flag_lumpsum							= "T";
}	

function api_json_call($apiurl, $header, $data) {
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
//end ACTION BUTTON ----------------------------------------------------------
?>

	