<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$ls_type				= $_POST["type"];
$ls_search_pilihan		= $_POST["pilihan"];
$ls_search_keyword 		= $_POST["keyword"];
$ls_user 				= $_SESSION["USER"];
$ls_kantor  			= $_SESSION['kdkantorrole'];
$ses_reg_role 			= $_SESSION['regrole'];

if($ses_reg_role=="15"){
	$condition =" AND 1=1";
}else{
	$condition=" AND A.DIAJUKAN_KE_FUNGSI = '$ses_reg_role'";
}

//form serialize
$ls_kode_tipe_klaim		= $_POST['v_kode_tipe_klaim'];
$ls_nama_tipe_klaim		= $_POST['v_nama_tipe_klaim'];
$ls_no_urut				= $_POST['v_no_urut'];
$ls_tgl_nonaktif		= $_POST['v_tgl_nonaktif'];
$ls_status_nonaktif		= isset($_POST['v_status_nonaktif']) ? "Y":"T" ;
$ls_keterangan 			= $_POST['v_keterangan'];

$sql = "select count(1) is_kcp from sijstk.ms_kantor where kode_kantor = '$ls_kantor' and kode_tipe in ('4','5')";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_is_kcp = $row["IS_KCP"] > 0 ? "Y" : "T";


function handleError($errno, $errstr,$error_file,$error_line) {
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
                "ret":-1,
                "msg":"<b>Error:</b> '.$errorMsg.'"
            }';
		die();
    }
}
set_error_handler("DefaultGlobalErrorHandler");


if($ls_type=="query"){
	
	// update status data terlebih dahulu sesuai data klaim sebelum data ditampilkan 
	$sql = "BEGIN PN.P_PN_PN60010206.X_UPDATE_KOREKSI_KLAIM_JPN_ALL
			(
				'$USER',
				:p_sukses,
				:p_mess
			); 
			END;";
	// print_r($sql);
	// die();   
	$proc = $DB->parse($sql);       
	oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
	oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
	
	$DB->execute();
	//
	
	$ls_search_pilihan = $_POST['search_pilihan'];
    $ls_search_keyword = $_POST['keyword'];
    $ls_tgl_awal_display  = $_POST['tgl_awal_display'];
    $ls_tgl_akhir_display = $_POST['tgl_akhir_display'];

	$draw = 1;
	if(isset($_POST['draw'])){
	    $draw = $_POST['draw']; 
	}else{
	    $draw = 1;       
	}

	$start = $_POST['start']+1;
	$length = $_POST['length'];
	$end = ($start-1) + $length;

	$condition ="";
	$condition2 ="";
	//add query condition for keyword
	if($ls_search_pilihan!=""){
		$condition .= " AND ".strtoupper($ls_search_pilihan)." like '%".strtoupper($ls_search_keyword)."%'";
	} 

	$order      = $_POST["order"];
	$by = $order[0]['dir'];

	if($order[0]['column']=='0'){
	    $order = "ORDER BY A.KODE_AGENDA ";
	}else if($order[0]['column']=='1'){
	    $order = "ORDER BY TGL_REKAM ";
	}else if($order[0]['column']=='2'){
	    $order = "ORDER BY KODE_AGENDA ";
	}
	else if($order[0]['column']=='3'){
	    $order = "ORDER BY NAMA_JENIS_AGENDA_DETIL ";
	}
	else if($order[0]['column']=='4'){
	    $order = "ORDER BY KODE_KLAIM ";
	}
	else if($order[0]['column']=='5'){
	    $order = "ORDER BY KPJ ";
	}

	else if($order[0]['column']=='6'){
	    $order = "ORDER BY NAMA_TK ";
	}else if($order[0]['column']=='7'){
	    $order = "ORDER BY TGL_AGENDA ";
	}else if($order[0]['column']=='8'){
	    $order = "ORDER BY DETIL_STATUS ";
	}
	// else if($order[0]['column']=='11'){
	//     $order = "ORDER BY DETIL_STATUS ";
	// }
	$order .= $by;

	$sql = "";
	$sql="SELECT *
	FROM (SELECT ROWNUM AS NO_URUT, Z.*
			FROM (SELECT A.*
		          FROM (  SELECT C.URL_PATH,
                                 A.KODE_JENIS_AGENDA_DETIL,
                                 A.KODE_AGENDA,
                                 (SELECT COUNT(1) FROM PN.PN_KURANG_BAYAR_TDL_APPROVAL B WHERE B.STATUS_APPROVAL='T' AND B.KODE_AGENDA=A.KODE_AGENDA) CEK_TIDAK_BISA_BAYAR,
                                 A.KODE_KANTOR,
                                 A.REFERENSI,
                                 B.NAMA_JENIS_AGENDA,
                                 C.NAMA_JENIS_AGENDA_DETIL,
                                 TO_CHAR (A.TGL_AGENDA, 'DD/MM/RRRR') TGL_AGENDA,
                                 A.KETERANGAN,
                                 A.DIAJUKAN_KE,
                                 A.DIAJUKAN_KE_KANTOR,
                                 A.DIAJUKAN_KE_FUNGSI,
                                 A.STATUS_AGENDA,
                                 A.DETIL_STATUS,
                                 --A.REFERENSI KODE_KLAIM,
                                 D.KODE_KLAIM,
                                 D.KPJ,
                                 D.NOMOR_IDENTITAS NIK,
                                 D.NAMA_TK
                            FROM PN.PN_AGENDA_KOREKSI            A,
                                 PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
                                 PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
                                 PN.PN_KLAIM D
                           WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
                                 AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
                                 AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
                                 AND A.REFERENSI = D.KODE_KLAIM
                                 AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL')
                                 AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
                                 AND A.KODE_KANTOR = '".$ls_kantor."' ".$condition."
                            UNION ALL
                            SELECT C.URL_PATH,
                                 A.KODE_JENIS_AGENDA_DETIL,
                                 A.KODE_AGENDA,
                                 (SELECT COUNT(1) FROM PN.PN_KURANG_BAYAR_TDL_APPROVAL B WHERE B.STATUS_APPROVAL='T' AND B.KODE_AGENDA=A.KODE_AGENDA) CEK_TIDAK_BISA_BAYAR,
                                 A.KODE_KANTOR,
                                 A.REFERENSI,
                                 B.NAMA_JENIS_AGENDA,
                                 C.NAMA_JENIS_AGENDA_DETIL,
                                 TO_CHAR (A.TGL_AGENDA, 'DD/MM/RRRR') TGL_AGENDA,
                                 A.KETERANGAN,
                                 A.DIAJUKAN_KE,
                                 A.DIAJUKAN_KE_KANTOR,
                                 A.DIAJUKAN_KE_FUNGSI,
                                 A.STATUS_AGENDA,
                                 A.DETIL_STATUS,
                                 --A.REFERENSI KODE_KLAIM,
                                 '' KODE_KLAIM,
                                 '' KPJ,
                                 D.NOMOR_IDENTITAS NIK,
                                 D.NAMA_LENGKAP NAMA_TK
                            FROM PN.PN_AGENDA_KOREKSI            A,
                                 PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
                                 PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
                                 PN.PN_AGENDA_VERIFIKASI_JHT D
                           WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
                                 AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
                                 AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
                                 AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL', 'AGENDA')
                                 AND A.KODE_AGENDA = D.KODE_AGENDA(+)
								 AND D.STATUS_SUBMIT_TINDAK_LANJUT = 'Y'
                                 AND A.KODE_JENIS_AGENDA = 'PP03'
                                 AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
                                 AND A.KODE_KANTOR = '".$ls_kantor."' AND (C.NAMA_JENIS_AGENDA_DETIL LIKE '%".strtoupper($ls_search_keyword)."%' OR A.KODE_AGENDA LIKE '%".strtoupper($ls_search_keyword)."%')
							UNION ALL
							SELECT C.URL_PATH,
								A.KODE_JENIS_AGENDA_DETIL,
								A.KODE_AGENDA,
								(SELECT COUNT(1) FROM PN.PN_KURANG_BAYAR_TDL_APPROVAL B WHERE B.STATUS_APPROVAL='T' AND B.KODE_AGENDA=A.KODE_AGENDA) CEK_TIDAK_BISA_BAYAR,
								A.KODE_KANTOR,
								A.REFERENSI,
								B.NAMA_JENIS_AGENDA,
								C.NAMA_JENIS_AGENDA_DETIL,
								TO_CHAR (A.TGL_AGENDA, 'DD/MM/RRRR') TGL_AGENDA,
								A.KETERANGAN,
								A.DIAJUKAN_KE,
								A.DIAJUKAN_KE_KANTOR,
								A.DIAJUKAN_KE_FUNGSI,
								A.STATUS_AGENDA,
								A.DETIL_STATUS,
								--A.REFERENSI KODE_KLAIM,
								'' KODE_KLAIM,
								D.KPJ,
								D.NOMOR_IDENTITAS NIK,
								D.NAMA_LENGKAP NAMA_TK
							FROM PN.PN_AGENDA_KOREKSI            A,
								PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
								PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
								PN.PN_AGENDA_FLAG_DIAKUI D
							WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
									AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
									AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
									AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL', 'AGENDA')
									AND A.KODE_AGENDA = D.KODE_AGENDA(+)
									AND D.STATUS_SUBMIT_TINDAK_LANJUT = 'Y'
									AND A.KODE_JENIS_AGENDA = 'PP03'
									AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
									AND A.KODE_KANTOR = '".$ls_kantor."' AND (C.NAMA_JENIS_AGENDA_DETIL LIKE '%".strtoupper($ls_search_keyword)."%' OR A.KODE_AGENDA LIKE '%".strtoupper($ls_search_keyword)."%' OR D.KPJ LIKE '%".strtoupper($ls_search_keyword)."%')
							UNION ALL	
							SELECT C.URL_PATH,
							A.KODE_JENIS_AGENDA_DETIL,
							A.KODE_AGENDA,
							(SELECT COUNT(1) FROM PN.PN_KURANG_BAYAR_TDL_APPROVAL B WHERE B.STATUS_APPROVAL='T' AND B.KODE_AGENDA=A.KODE_AGENDA) CEK_TIDAK_BISA_BAYAR,
							A.KODE_KANTOR,
							A.REFERENSI,
							B.NAMA_JENIS_AGENDA,
							C.NAMA_JENIS_AGENDA_DETIL,
							TO_CHAR (A.TGL_AGENDA, 'DD/MM/RRRR') TGL_AGENDA,
							A.KETERANGAN,
							A.DIAJUKAN_KE,
							A.DIAJUKAN_KE_KANTOR,
							A.DIAJUKAN_KE_FUNGSI,
							A.STATUS_AGENDA,
							A.DETIL_STATUS,
							--A.REFERENSI KODE_KLAIM,
							'' KODE_KLAIM,
							D.KPJ,
							D.NOMOR_IDENTITAS NIK,
							D.NAMA_LENGKAP NAMA_TK
						FROM PN.PN_AGENDA_KOREKSI            A,
							PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
							PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
							PN.PN_AGENDA_LEPAS_FIKTIF D
						WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
								AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
								AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
								AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL', 'AGENDA')
								AND A.KODE_AGENDA = D.KODE_AGENDA(+)
								AND D.STATUS_SUBMIT_TINDAK_LANJUT = 'Y'
								AND A.KODE_JENIS_AGENDA = 'PP05'
								AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
								AND A.KODE_KANTOR = '".$ls_kantor."' AND (C.NAMA_JENIS_AGENDA_DETIL LIKE '%".strtoupper($ls_search_keyword)."%' OR A.KODE_AGENDA LIKE '%".strtoupper($ls_search_keyword)."%' OR D.KPJ LIKE '%".strtoupper($ls_search_keyword)."%')
									) A WHERE 1=1 ".$order."  )  Z)
				 where NO_URUT between ".$start." and ".$end;

	// var_dump($sql);
	// die;
	$queryTotal = "	SELECT COUNT(1) FROM (SELECT A.KODE_AGENDA
						FROM PN.PN_AGENDA_KOREKSI            A,
		                         PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
		                         PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
		                         PN.PN_KLAIM D
		                   WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
		                         AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
		                         AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
		                         AND A.REFERENSI = D.KODE_KLAIM
								 AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL')
		                         $condition
		                         AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
		                         AND A.KODE_KANTOR = '".$ls_kantor."' 
							".$condition."
						UNION
						SELECT A.KODE_AGENDA
							FROM PN.PN_AGENDA_KOREKSI            A,
                                 PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
                                 PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
                                 PN.PN_AGENDA_VERIFIKASI_JHT D
                           WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
                                 AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
                                 AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
                                 AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL', 'AGENDA')
                                 AND A.KODE_AGENDA = D.KODE_AGENDA(+)
								 AND D.STATUS_SUBMIT_TINDAK_LANJUT = 'Y'
                                 AND A.KODE_JENIS_AGENDA = 'PP03'
                                 AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
                                 AND A.KODE_KANTOR = '".$ls_kantor."' AND (C.NAMA_JENIS_AGENDA_DETIL LIKE '%".strtoupper($ls_search_keyword)."%' OR A.KODE_AGENDA LIKE '%".strtoupper($ls_search_keyword)."%')
						UNION
						SELECT A.KODE_AGENDA
							FROM PN.PN_AGENDA_KOREKSI            A,
								PN.PN_KODE_JENIS_AGENDA_KOREKSI     B,
								PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C,
								PN.PN_AGENDA_FLAG_DIAKUI D
						WHERE     A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
								AND A.KODE_JENIS_AGENDA = C.KODE_JENIS_AGENDA
								AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
								AND A.DETIL_STATUS NOT IN ('TERBUKA','BATAL', 'AGENDA')
								AND A.KODE_AGENDA = D.KODE_AGENDA(+)
								AND D.STATUS_SUBMIT_TINDAK_LANJUT = 'Y'
								AND A.KODE_JENIS_AGENDA = 'PP03'
								AND to_date(A.TGL_REKAM,'dd/mm/rrrr') BETWEEN to_date('".$ls_tgl_awal_display."','dd/mm/rrrr') AND to_date('".$ls_tgl_akhir_display."','dd/mm/rrrr')
								AND A.KODE_KANTOR = '".$ls_kantor."' AND (C.NAMA_JENIS_AGENDA_DETIL LIKE '%".strtoupper($ls_search_keyword)."%' OR A.KODE_AGENDA LIKE '%".strtoupper($ls_search_keyword)."%'  OR D.KPJ LIKE '%".strtoupper($ls_search_keyword)."%')
								) 
							";
							// var_dump($queryTotal);
	
	$recordsTotal = $DB->get_data($queryTotal);      

	$DB->parse($sql);
	if($DB->execute()){ 
		$i = 0;
		while($data = $DB->nextrow())
		{
			$data['ACTION'] = "";
			$data['FLAG_KCP']=$ls_is_kcp;
			$data['SESSION_ROLE']=$ses_reg_role;
		    $jsondata .= json_encode($data);
		    $jsondata .= ',';
		    $i++;
		}
		$jsondataStart = '{"draw":'.$draw.',"recordsTotal":'.$recordsTotal.',"recordsFiltered":'.$recordsTotal.',"data":[';
		$jsondata .= ']}';
		$jsondata = $jsondataStart . ExtendedFunction::str_replace_json_nullable('"},]}', '"}]}', $jsondata);
		echo $jsondata;
	} else {
		echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}

	
}


?>