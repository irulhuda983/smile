
<?php
include('includes/connsql.php');
require_once "includes/class_database.php";
require_once "logging/loggingv011.php";

$DB = new Database($dbuser,$dbpass,$dbname); //connect to db



error_reporting(1);
ini_set("display_errors", "on");
$wscom = new SoapClient(WSCOMUSER."?wsdl", array("location"=>WSCOMUSER,"exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));


if ($user["ip"]=='') $user["ip"] = getenv('HTTP_CLIENT_IP')?:
getenv('HTTP_X_FORWARDED_FOR')?:
getenv('HTTP_X_FORWARDED')?:
getenv('HTTP_FORWARDED_FOR')?:
getenv('HTTP_FORWARDED')?:
getenv('REMOTE_ADDR');

$ls_type = $_POST["tipe"];

if (isset($_POST)){
	foreach ($_POST as $key => $value){
		$_POST[$key] = htmlspecialchars(htmlentities($_POST[$key]));
	}
}


if(isset($_POST["login"])){
	
	// Start with custom session ID
    ini_set('session.use_strict_mode', 1);
	//Fix issue security smile untuk regenerate session
	session_regenerate_id(true);
	// Set new custom session ID
    $newid = session_id();
	
	$_COOKIE["PHPSESSID"] = $newid;
	$_SESSION["PHPSESSID"] = $newid;
	// Send a new session ID in a cookie to the client
	setcookie('PHPSESSID', $newid, time() + 3600, '/', '', false, true);

	//handling captcha if toggle_captcha is active 
	if($toggle_captcha=="on"){

		if(!isset($_POST["token"])) {
			$response['success'] = false;
			$response['errors']['msg'] = "Koneksi ke Server tidak berhasil. Pastikan koneksi internet anda lancar dan Refresh halaman ini dengan menekan tombol Shift+F5 (Windows) atau Command+R (Apple/Mac).<br> Silakan dicoba kembali.";
			exit(json_encode($response));
		}

		$ls_token = $_POST["token"];
		$resp = check_captcha($ls_token);
		$resp_decode = json_decode($resp);
		
		if(!$resp_decode->success) {
			$response['success'] = false;
			$response['errors']['msg'] = "Koneksi ke Server tidak berhasil. Pastikan koneksi internet anda lancar dan Refresh halaman ini dengan menekan tombol Shift+F5 (Windows) atau Command+R (Apple/Mac).<br> Silakan dicoba kembali.";
			exit(json_encode($response));
		}
		
	}
	
	//Pengechekan User Active Pulse Secure
	if($toggle_pulse=="on"){
		$ip_prefix = explode('.',$user["ip"])[2];
		$ip_segment = explode('.',$user["ip"])[0].explode('.',$user["ip"])[1];
		
		$url_pulse = "0";
		//ip segment drc 
		if( substr($ip_prefix, 0, 1) == 1 ){
			$url_pulse = $url_pulse_drc;
		}else{
			//ip segment dc
			$url_pulse = $url_pulse_dc;
		}
		if( $ip_segment == '17228' && $url_pulse != '0' ){
			$ret = is_allow_pulse_check(strtoupper(trim($_POST["login"])), $url_pulse, $user["ip"]);
			if($ret==0){
				$response['ret'] = '-4';
				$response['success'] = false;
				$response['errors']['msg'] = "Proses login tidak bisa dilanjutkan. Saat ini anda terdeteksi menggunakan koneksi VPN dengan user akun yang berbeda. Silakan pastikan kembali!";
				exit(json_encode($response));
			}
		}
	}
	
	// define pembagi session.gc_maxlifetime default session.gc_maxlifetime = 1440 dalam menit
	$ls_gc_maxlifetime = 0;
	$sql = "SELECT NVL(MAX(KATEGORI),0) JML_KODE_TIPE FROM MS.MS_LOOKUP WHERE TIPE = 'LGNSESSION' AND KODE = 'LGNSESSION_GC_MAXLIFETIME' AND ROWNUM = 1 ";
	$DB->parse($sql);
	if($DB->execute()){
		$row = $DB->nextrow();
		$ls_gc_maxlifetime = $row['JML_KODE_TIPE'];
		if($ls_gc_maxlifetime == 0)
		{
			$ls_gc_maxlifetime = 180;
		}
	}else{
		$ls_gc_maxlifetime = 180;
	}
	$sestime = ini_get("session.gc_maxlifetime")/$ls_gc_maxlifetime;
	
	$con1 		=  $wscom->getLoginUsrPass(array('chId' => $chId, 'username' => strtoupper(trim($_POST["login"])), 'password' => $_POST["password"], 'avl' => '{"SESSION_HEADER":"'.$user["agent"].'|'.$_COOKIE["PHPSESSID"].'|'.$_SERVER["SERVER_ADDR"].':'.$_SERVER['SERVER_PORT'].'","IP":"'.$user["ip"].'","CONCURRENT":"false", "SESSION_EXPIRED":"'.$sestime.'"}'));
	$getData 	= get_object_vars($con1);
	$result 	= json_decode ($getData["return"]->addValues);
	$_SESSION["TGL_EXPIRED_PASSWD"] 	= $result->TGL_EXPIRED_PASSWD;
	$_SESSION["CHANGE_PASSWD_IN_DAYS"] 	= $result->CHANGE_PASSWD_IN_DAYS;
	
	
	$ls_login_ret = $getData["return"]->ret;
	
	//Penambahan pengecheckan untuk force logout
	if ($getData["return"]->ret == '-3'){
		$_SESSION["USER"] 	= strtoupper(trim($_POST["login"]));
		$_SESSION["PWD"] = $_POST["password"];
		$_SESSION["TOKEN"] 	= strtoupper(trim($_POST["login"]));
		$response['ret'] = '-3';
		$response['success'] = false;
		$response['errors']['msg'] = "Akun anda masih aktif di browser lain. Apakah <b>anda tetap ingin login</b> pada browser ini?";
		exit(json_encode($response));
	}

	
	if($getData["return"]->ret == 0){
		$con1 			=  $wscom->getUserLoginInfo(array('chId' => $chId, 'kodeUser' => strtoupper(trim($_POST["login"]))));
		$getData 		= get_object_vars($con1);

		if($getData["return"]->ret == 0){

			global $default_access_time;
			// response 1 is user has an access and 0 is user has not access
			$has_access_key = check_user_access_time(strtoupper(trim($_POST["login"])));
			// var_dump($has_access_key);
			// die;
			if ($has_access_key=="0"){
				
				// response time allow is 0 and not allow is 1
				$is_allow = check_default_access_time($getData["return"]->kodeKantor);
				
				$msg = "Akses aplikasi SMILE terbatas pada pukul ".$default_access_time." sesuai zona waktu setempat. Jika memerlukan akses di luar waktu tersebut, harap ajukan permintaan melalui SIMFONI. Terima kasih.";
				if ($is_allow=="0"){
					$response = array();
					$response['ret']="-1";
					$response['success'] =false;
					$response['errors']['msg'] = $msg;
					exit(json_encode($response));
				}

				// jika hari ini hari libur maka akan return 1 , 0 untuk workday
				$is_holiday = check_holiday_access_time();
				
				if($is_holiday=="1"){
					$msg = "Akses aplikasi SMILE terbatas hanya pada hari kerja. Jika memerlukan akses di luar waktu tersebut, harap ajukan permintaan melalui SIMFONI. Terima kasih.";
					$response = array();
					$response['ret']="-1";
					$response['success'] =false;
					$response['errors']['msg'] = $msg;
					exit(json_encode($response));
				}
				
				
			}else{// has access key
				$ls_kode_user = strtoupper(trim($_POST["login"]));
				$ls_flag_fulltime = check_flag_fulltime($ls_kode_user);
				
				// check flag akses time 24/7 , 1 yes and 0 no
				if($ls_flag_fulltime=="0"){
					$is_allow = check_default_outside_access_time($getData["return"]->kodeKantor);
					
					$msg = "Akses aplikasi SMILE terbatas pada pukul ".$default_access_time." sesuai zona waktu setempat. Jika memerlukan akses di luar waktu tersebut, harap ajukan permintaan melalui SIMFONI. Terima kasih.";
					if ($is_allow=="0"){
						$response = array();
						$response['ret']="-1";
						$response['success'] =false;
						$response['errors']['msg'] = $msg;
						echo json_encode($response);
						die;
					}
				}
			}

			

			//Set Login Log
			//=================
		
			$sql = "insert into sijstk.sc_audit_akses_form ( 
				kode_user, 
				tgl_akses, 
				kode_form, 
				inisial_fungsi, 
				kantor_fungsi, 
				ip_server, 
				nama_app_server, 
				nama_host, 
				ip_client, 
				tgl_rekam, 
				petugas_rekam) values ( 
					:kode_user, 
					sysdate, 
					'LOGIN', 
					'', 
					:kantor_fungsi, 
					:SERVER_ADDR, 
					:HTTP_HOST, 
					:nama_host, 
					:ip_client, 
					sysdate, 
					'SMILE' )";
			$arr_param_query = array(); 
			$arr_param_query[':kode_user'] = strtoupper(trim($_POST["login"]));
			$arr_param_query[':kantor_fungsi'] = $getData["return"]->kodeKantor;
			$arr_param_query[':SERVER_ADDR'] = $_SERVER["SERVER_ADDR"];
			$arr_param_query[':HTTP_HOST'] = $_SERVER["HTTP_HOST"];
			$arr_param_query[':nama_host'] = gethostname();
			$arr_param_query[':ip_client'] = $user["ip"];
			$proc = $DB->parse($sql);
			foreach ($arr_param_query as $key => $value){
				oci_bind_by_name($proc, $key, $arr_param_query[$key]);
			}				
			if($DB->execute()){
				$response['success'] = true;
				
				
				// ini_set('session.use_strict_mode', 1);
				$_SESSION['LOGIN_AT'] = time();
				$_SESSION["USER"]			= strtoupper(trim($_POST["login"]));
				$_SESSION['NAMA'] 		= $getData["return"]->namaUser;
				$_SESSION['KDKANTOR']	= $getData["return"]->kodeKantor;
				$_SESSION['KANTOR']		= $getData["return"]->namaKantor;
				$_SESSION['NPK']		= $getData["return"]->npk;
				$_SESSION['EMAIL']		= $getData["return"]->email;
				$_SESSION['IP']			= $user["ip"];
				$_SESSION["STATUS"] 	= "LOGIN";
				
				//cek login dan kirim email jika IP/perangkat baru
				/*$sql_email_notifikasi = "BEGIN 
											KN.P_MS_SC9001.X_KIRIM_EMAIL_LOGIN_IP_BARU('".$_SESSION["USER"]."',:p_sukses,:p_mess);
										END;";
				$proc = $DB->parse($sql_email_notifikasi);
				oci_bind_by_name($proc, ":p_sukses", $p_sukses, 5000);
				oci_bind_by_name($proc, ":p_mess", $p_mess, 5000);
				$DB->execute();*/
			
			} else {
				$error = $sql;					
			}
			
		} else {
			$response['success'] = false;
			$error = $getData["return"]->msg;
		}
	} else {
		$response['success'] = false;
		$error = $getData["return"]->msg;
		
		if (!isset($_SESSION["attempts"]))
			$_SESSION["attempts"] = 0; 
		$_SESSION["attempts"] = $_SESSION["attempts"] + 1;


		if($_SESSION["attempts"] == 3 && $ls_login_ret == -1){
			// cek login attempt dan kirim email jika sudah 3 kali berturut-turut
			// $sql_email_login_attempt = "BEGIN 
			// 							KN.P_MS_SC9001.X_KIRIM_EMAIL_LOGIN_PASS_SALAH('".strtoupper(trim($_POST["login"]))."','".$user["ip"]."','".$_SERVER['HTTP_USER_AGENT']."',:p_sukses,:p_mess);
			// 						END;";
			$sql_email_login_attempt = 
			"BEGIN 
				KN.P_MS_SC9001.X_KIRIM_EMAIL_LOCK_USER_AKUN('".strtoupper(trim($_POST["login"]))."','".$user["ip"]."','".$_SERVER['HTTP_USER_AGENT']."',:p_sukses,:p_mess);
			END;";
			$proc = $DB->parse($sql_email_login_attempt);
			oci_bind_by_name($proc, ":p_sukses", $p_sukses, 5000);
			oci_bind_by_name($proc, ":p_mess", $p_mess, 5000);
			$DB->execute();
			$_SESSION["attempts"] = 0;
		}
		
		
		// save log log in attempt failed
		$sql = "INSERT INTO MS.SC_LOG_LOGIN_GAGAL
				(
				  KODE_USER,
				  TGL_AKSES,
				  KODE_FORM,
				  IP_SERVER,
				  NAMA_APP_SERVER,
				  NAMA_HOST,
				  IP_CLIENT,
				  NAMA_OS_BROWSER,
				  ATTEMPT_KE,
				  KETERANGAN,
				  TGL_REKAM,
				  PETUGAS_REKAM
				)
				VALUES
				(
					:KODE_USER,
					sysdate,
					'LOGIN',
					:IP_SERVER,
					:NAMA_APP_SERVER,
					:NAMA_HOST,
					:IP_CLIENT,
					:NAMA_OS_BROWSER,
					:ATTEMPT_KE,
					:KETERANGAN,
					sysdate,
					'SYSTEM'
				)";

		$arr_param_query = array();			
		$arr_param_query[':KODE_USER'] = strtoupper(trim($_POST["login"]));
		$arr_param_query[':IP_SERVER'] = $_SERVER["SERVER_ADDR"];
		$arr_param_query[':NAMA_APP_SERVER'] = $_SERVER["HTTP_HOST"];
		$arr_param_query[':NAMA_HOST'] = gethostname();
		$arr_param_query[':IP_CLIENT'] = $user["ip"];
		$arr_param_query[':NAMA_OS_BROWSER'] = $_SERVER['HTTP_USER_AGENT'];
		$arr_param_query[':ATTEMPT_KE'] = $_SESSION["attempts"];
		$arr_param_query[':KETERANGAN'] = $error;
		$proc = $DB->parse($sql);
		foreach ($arr_param_query as $key => $value){
			oci_bind_by_name($proc, $key, $arr_param_query[$key]);
		}
		$DB->execute();

		/*
		$sql_jml_login_day = "SELECT COUNT(*) AS JML_LOGIN_PER_HARI FROM MS.SC_LOG_LOGIN_GAGAL WHERE KODE_USER = '".strtoupper(trim($_POST["login"]))."' AND TRUNC(TGL_AKSES, 'DD') = TRUNC(SYSDATE, 'DD')";
		$DB->parse($sql_jml_login_day);
		$DB->execute();
	    $row = $DB->nextrow();
		$ls_jml_login_day = $row['JML_LOGIN_PER_HARI'];
		*/
	}

	if ($response['success'] == false) {
		$response['errors']['msg'] = $error;
	} else {
		$response['logininfo'] = array('redirect' => 'main.bpjs');
	}
	echo json_encode($response);
} else if ($ls_type == "LUPA_PASSWORD"){
		
	$ls_kode_user = htmlentities($_POST["kodeUser"]);
	$ls_email = htmlentities($_POST["email"]);
	$ls_ip_server_reset	= $_SERVER["SERVER_ADDR"];
	$ls_os_browser_reset	= $_SERVER['HTTP_USER_AGENT'];
    $ls_ip_client_reset	= getenv('HTTP_CLIENT_IP') ?:
				getenv('HTTP_X_FORWARDED_FOR') ?:
				getenv('HTTP_X_FORWARDED') ?:
				getenv('HTTP_FORWARDED_FOR') ?:
				getenv('HTTP_FORWARDED') ?:
				getenv('REMOTE_ADDR');
	
	if($ls_kode_user != "" and $ls_email != ""){
		global $dbuser, $dbpass, $dbname, $chId, $ipfwd, $host;
		$DB = new Database($dbuser, $dbpass, $dbname); //connect to db

		$ls_status_blokir = "T";
		$sql_blokir = 
		" SELECT CASE WHEN COUNT(*) > 0 THEN 'Y' ELSE 'T' END AS STATUS_BLOKIR
          FROM      MS.SC_USER 
          WHERE     KODE_USER = :ls_kode_user
          AND       AKTIF = 'X'
        ";
		
        $proc = $DB->parse($sql_blokir);
        oci_bind_by_name($proc, ':ls_kode_user', $ls_kode_user);
		
		$DB->execute();
        $row = $DB->nextrow();
	    $ls_status_blokir = $row['STATUS_BLOKIR'];

	    if($ls_status_blokir == "Y"){
	    	$sql_updblokir = 
			" UPDATE    MS.SC_USER 
			  SET 		AKTIF = 'Y',
			            TGL_UBAH = SYSDATE
	          WHERE     KODE_USER = :ls_kode_user
	          AND       AKTIF = 'X'
	        ";
	        $proc = $DB->parse($sql_updblokir);
			oci_bind_by_name($proc, ':ls_kode_user', $ls_kode_user);
	        $DB->execute();
	    }

		$sql = "SELECT COUNT(*) JML_USER FROM MS.SC_USER WHERE KODE_USER = :ls_kode_user AND EMAIL = :ls_email AND AKTIF IN ('Y') ";
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ':ls_kode_user', $ls_kode_user);
		oci_bind_by_name($proc, ':ls_email', $ls_email);
		
		if($DB->execute()){
			$row = $DB->nextrow();
			$ls_jml_user = $row['JML_USER'];
			
			if($ls_jml_user > 0){
				$ls_tgl_pass_reset = date("Y-m-d H:i:s");

				$sql_ganti_password_random = "select dbms_random.string('X', 10) RESET_PASSWORD_RANDOM from dual ";
				if($DB->parse($sql_ganti_password_random))
					if($DB->execute())
						if($row = $DB->nextrow())
							$ls_ganti_password_random = $row['RESET_PASSWORD_RANDOM'];
				

			    $url_resetuser = $wsIp . '/JSCoreSys/ResetUser/';


			    $fields_resetuse = array(
			            'chId' => 'SMILE',
			            'reqId' => $ls_kode_user,
			            'data' => array(
			                "KODE_USER" => $ls_kode_user
			            )
		       	);
			
		       	$result_resetuse = get_json_encode($url_resetuser, $fields_resetuse);
				$data_output = json_decode($result_resetuse);
				if ($data_output->ret == 0) {
					// ganti password setelah reset dengan menggunakan random password
					$ls_kode_user_ganti = $ls_kode_user;
					$ls_f_password_lama_ganti = "WELCOME1";
					$ls_f_password_baru_ganti = $ls_ganti_password_random;
					$ls_hint_password_baru_ganti = $ls_ganti_password_random;

					if ($ls_kode_user_ganti != "" && $ls_f_password_lama_ganti != "" && $ls_f_password_baru_ganti != "" && $ls_hint_password_baru_ganti != "") {
						$url_changepwd = $wsIp . '/JSCoreSys/ChangePwd/';
						$fields_changepwd = array(
									'chId' => 'SMILE',
									'reqId' => $ls_kode_user,
									'data' => array(
										"KODE_USER" => $ls_kode_user_ganti,
										"PASS_LAMA" => $ls_f_password_lama_ganti,
										"PASS_BARU" => $ls_f_password_baru_ganti,
										"HINT_BARU" => $ls_hint_password_baru_ganti
									)
						);
						
						$result_changepwd = get_json_encode($url_changepwd, $fields_changepwd);
						$data_output = json_decode($result_changepwd);
						
						if ($data_output->ret == 0) {
							// kirim email setelah reset dan ganti password random
									$sql_email_reset = "
										BEGIN 
											KN.P_MS_SC9001.X_KIRIM_EMAIL_LUPA_PASSWORD(:ls_kode_user_ganti,:ls_f_password_lama_ganti,:ls_f_password_baru_ganti,:ls_kode_user,:ls_kode_role,:kdkantorrole, :ls_ip_server_reset,:ls_ip_client_reset,:ls_os_browser_reset, :p_sukses,:p_mess);
										END;";
									$proc = $DB->parse($sql_email_reset);
									oci_bind_by_name($proc, ':ls_kode_user_ganti', $ls_kode_user_ganti);
									oci_bind_by_name($proc, ':ls_f_password_lama_ganti', $ls_f_password_lama_ganti);
									oci_bind_by_name($proc, ':ls_f_password_baru_ganti', $ls_f_password_baru_ganti);
									oci_bind_by_name($proc, ':ls_kode_user', $ls_kode_user);
									oci_bind_by_name($proc, ':ls_kode_role', $ls_kode_role);
									oci_bind_by_name($proc, ':kdkantorrole', $_SESSION['kdkantorrole']);
									oci_bind_by_name($proc, ':ls_ip_server_reset', $ls_ip_server_reset);
									oci_bind_by_name($proc, ':ls_ip_client_reset', $ls_ip_client_reset);
									oci_bind_by_name($proc, ':ls_os_browser_reset', $ls_os_browser_reset);
									oci_bind_by_name($proc, ":p_sukses", $p_sukses, 5000);
									oci_bind_by_name($proc, ":p_mess", $p_mess, 5000);
									$DB->execute();
									if($p_sukses == 0)
									{
										echo '{"ret":0,"msg":"Sukses, reset password kode user ' . $ls_kode_user . ' berhasil. Silahkan cek email Anda!"}';
									}
									else{
										echo '{"ret":-1,"msg":"Proses gagal, reset password tidak berhasil! '.$p_mess.'"}';                               
									}
							} else {
									echo '{"ret":-1,"msg":"Proses gagal, reset password tidak berhasil! '.$data_output->msg.'"}';
							}
					}
				} else {
					echo '{"ret":-1,"msg":"Proses gagal, reset password kode user ' . $ls_kode_user . ' tidak berhasil! ' . $data_output->msg . '"}';
				}

			} else{
				echo '{"ret":-1,"msg":"Akun dengan kode user ' . $ls_kode_user . ' dan email ' . $ls_email . ' tidak ditemukan!"}';
			}
		}
	} else {
		echo '{"ret":-1,"msg":"Username atau Email masih kosong, silahkan lengkapi data input!"}';
	}	
}else if ($ls_type=="FORCE_LOGOUT") {

	if(!isset($_SESSION['USER']) && $_SESSION['USER'] == ""){
		echo '{"ret":-1,"msg":"Username atau Email masih kosong, silahkan lengkapi data input!"}';
		die;
	}
	

	if(!isset($_SESSION['PWD']) && $_SESSION['PWD'] == ""){
		echo '{"ret":-1,"msg":"Username atau Email masih kosong, silahkan lengkapi data input!"}';
		die;
	}

	$K = $_SESSION['USER'];

	$sql_check = "select
						kode_user,
						session_header
					from
						ms.sc_user_login_session
					where
						kode_user = '{$K}'
						and logout_by = 'A'
						and channel_login = 'SMILE'
						and rownum = 1
					order by
						last_login desc";
	$ls_session_header = "";
	$DB->parse($sql_check);
	if($DB->execute()){
		if($row = $DB->nextrow()){
			$ls_session_header=$row['SESSION_HEADER'];
		}
	}

	$ls_session_id = explode("|",$ls_session_header)[1];
	$ls_ip_web_server = explode("|",$ls_session_header)[2]; 
	$ls_session_id_encrypt = encrypt_decrypt('encrypt', $ls_session_id);
	$p_fields = array(
		"69c95ed70a"=>$ls_session_id_encrypt
	);
	
	$ls_response = do_force_logout($ls_ip_web_server, $p_fields);
	// var_dump($ls_session_id_encrypt);
	// die;
	$tgl_login="";
    $sql = "select to_char(last_login,'DD/MM/YYYY') last_login from sijstk.sc_user_login_session where kode_user='{$K}' and CHANNEL_LOGIN='SMILE'"; 
    $DB->parse($sql);
    if($DB->execute())
        if($row = $DB->nextrow())
            $tgl_login=$row['LAST_LOGIN'];
    $sql = "update sijstk.sc_user_login_session set LOGOUT_BY='U' where kode_user='{$K}' and CHANNEL_LOGIN='SMILE'"; 
    $DB->parse($sql);
    if($DB->execute())
    {
		//we add status logout =F to mark this user logout by force logout
        $sql = "update sijstk.sc_audit_akses_form set TGL_LOGOUT=sysdate,STATUS_LOGOUT='F',LOGOUT_BY='{$K}' 
            where kode_user='{$K}' and to_char(tgl_akses,'DD/MM/YYYY')={$tgl_login} and tgl_akses=(select max(tgl_akses) from sijstk.sc_audit_akses_form where kode_user='{$K}' and to_char(tgl_akses,'DD/MM/YYYY')={$tgl_login})"; 
        $DB->parse($sql);
        $DB->execute();
		$response['ret']="0";
		$response['success'] =true;
		$response['errors']['msg'] = "";
		echo json_encode($response);
    }else{
		$response['ret']="-1";
		$response['success'] =true;
		$response['errors']['msg'] = "Proses force logout tidak berhasil. Silakan dicoba kembali. ";
		echo json_encode($response);
	}
	
	
}else {
	$response['success'] = false;
	$error = 'User/Password harus diisi';
}

function do_force_logout($ip_url, $p_fields){

	$ls_filename = "f9af7d840.php";
	$ls_uri = $ip_url.'/smile/mod_setup/ajax/'.$ls_filename;
	// set HTTP header
    $headers = array(
        'Content-Type' => 'application/json',
        'X-Forwarded-For' => $ipfwd,
    );
	
    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $ls_uri);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $p_fields);

    // Execute post
    $result = curl_exec($ch);
    // Close connection
    curl_close($ch);

    return $result;
}


function get_json_encode($p_url, $p_fields) {
    // set HTTP header
    $headers = array(
        'Content-Type' => 'application/json',
        'X-Forwarded-For' => $ipfwd,
    );

    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $p_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($p_fields));

    // Execute post
    $result = curl_exec($ch);
	
    // Close connection
    curl_close($ch);

    return $result;
}

function check_captcha($token) {

	global $g_secret_key;
	global $g_url_captcha_verify;
	global $g_http_proxy;

	$p_fields = array(
		'secret' => $g_secret_key, 
		'response' => $token,
	);
	
	// set HTTP header
    $headers = array(
        'Content-Type' => 'application/json',
        'X-Forwarded-For' => $ipfwd,
    );

    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $g_url_captcha_verify);
	curl_setopt($ch, CURLOPT_PROXY, $g_http_proxy);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($p_fields));

    // Execute post
    $result = curl_exec($ch);
	// var_dump($result);
	// die;
    // Close connection
    curl_close($ch);

	return $result;
}


//handling limited access by application
function check_user_access_time($p_kode_user){
	global $dbuser;
	global $dbpass;
	global $dbname;

	$DB = new Database($dbuser,$dbpass,$dbname);

	$sql ="select
				count(1) as is_allow
			from
				ms.sc_user_access sua
			where
				kode_user = :p_kode_user
				AND trunc(sysdate) >= trunc(tgl_mulai)
				AND trunc(sysdate) <= trunc(tgl_akhir)
				and rownum = 1 ";
	
	$result =1;
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_kode_user', $p_kode_user);
	// var_dump($DB);
	// die;
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['IS_ALLOW'];
	}
	
	$DB->close();
	return $result;

}
//handling limited access by application
function check_flag_fulltime($p_kode_user){
	global $dbuser;
	global $dbpass;
	global $dbname;

	$DB = new Database($dbuser,$dbpass,$dbname);
	
	$sql ="select
				flag_fulltime
			from
				ms.sc_user_access
			where
				kode_user = :p_kode_user
				AND trunc(sysdate) >= trunc(tgl_mulai)
				AND trunc(sysdate) <= trunc(tgl_akhir)
				and rownum = 1
			order by
				tgl_akhir ";
	
	$result ="0";
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_kode_user', $p_kode_user);
	
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['FLAG_FULLTIME']==null?"0":$row['FLAG_FULLTIME'];
	}
	
	$DB->close();
	return $result;

}


//handling limited access by application
function check_default_outside_access_time($p_kode_kantor){
	global $dbuser;
	global $dbpass;
	global $dbname;
	global $default_outside_access_time;

	$DB = new Database($dbuser,$dbpass,$dbname);
	$selisih = get_zona_waktu($p_kode_kantor); 
	
	$start_access_time = explode("-",$default_outside_access_time)[0];
	$end_access_time =  explode("-",$default_outside_access_time)[1];
	$p_start_hour = (int) explode(":",$start_access_time)[0];
	$p_start_minutes = (int) explode(":",$start_access_time)[1];
	$p_end_hour = ((int) explode(":",$end_access_time)[0]);
	$p_end_minutes = (int) explode(":",$end_access_time)[1]; 
	$p_start = ($p_start_hour * 60 )+$p_start_minutes;
	$p_end = ($p_end_hour * 60 )+$p_end_minutes;

	$sql ="select
				case
					when (((to_number(to_char(sysdate, 'HH24'))+".(int) $selisih.")*60) + (to_number(to_char(sysdate, 'MI'))) >= :p_start )
					and (((to_number(to_char(sysdate, 'HH24'))+".(int) $selisih.")*60) + (to_number(to_char(sysdate, 'MI'))) <= :p_end ) then '1'
					else '0'
				end as is_allow
			from
				dual ";
	
	$result =0;
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_start', $p_start);
	oci_bind_by_name($proc, ':p_end', $p_end);
	
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['IS_ALLOW'];
	}
	
	$DB->close();
	return $result;

}

//handling limited access by application
function check_default_access_time($p_kode_kantor){
	global $dbuser;
	global $dbpass;
	global $dbname;
	global $default_access_time;

	$DB = new Database($dbuser,$dbpass,$dbname);
	$selisih = get_zona_waktu($p_kode_kantor); 
	
	$start_access_time = explode("-",$default_access_time)[0];
	$end_access_time =  explode("-",$default_access_time)[1];
	$p_start_hour = (int) explode(":",$start_access_time)[0];
	$p_start_minutes = (int) explode(":",$start_access_time)[1];
	$p_end_hour = ((int) explode(":",$end_access_time)[0]);
	$p_end_minutes = (int) explode(":",$end_access_time)[1]; 
	$p_start = ($p_start_hour * 60 )+$p_start_minutes;
	$p_end = ($p_end_hour * 60 )+$p_end_minutes;
	
	$sql ="select
				case
					when (((to_number(to_char(sysdate, 'HH24'))+".(int) $selisih.")*60) + (to_number(to_char(sysdate, 'MI'))) >= :p_start  )
					and (((to_number(to_char(sysdate, 'HH24'))+".(int) $selisih.")*60) + (to_number(to_char(sysdate, 'MI'))) <= :p_end ) then '1'
					else '0'
				end as is_allow
			from
				dual ";
	// var_dump($sql);
	// die;
	$result =0;
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_start', $p_start);
	oci_bind_by_name($proc, ':p_end', $p_end);
	
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['IS_ALLOW'];
	}
	
	$DB->close();
	return $result;

}

//handling limited access by application
function check_holiday_access_time(){
	global $dbuser;
	global $dbpass;
	global $dbname;

	$DB = new Database($dbuser,$dbpass,$dbname);
	
	$sql ="select
				case
					when count(1) > 0 then '1'
					else '0'
				end as is_holiday
			from
					ms.ms_hari_libur
			where
					to_char(tgl, 'yyyy-mm-dd')= to_char(sysdate, 'yyyy-mm-dd')";
	
	$proc = $DB->parse($sql);
	
	$result = "0";
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['IS_HOLIDAY'];
	}
	
	//check if today is weekend then return 1 else 0
	// ada perbedaan sysdate di aplikasi 7 hari sabtu di select ke db sabtu adalah 6
	$sql ="select
				case
					when to_char(sysdate, 'd') in (7, 1) then '1'
					else '0'
				end as IS_WEEKEND
			from
				ms.ms_hari_libur 
			where rownum=1 ";

	$proc = $DB->parse($sql);
	
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['IS_WEEKEND'];
	}
	$DB->close();

	return $result;

}

function get_zona_waktu($p_kode_kantor){
	global $default_access_time;
	global $dbuser;
	global $dbpass;
	global $dbname;

	$DB = new Database($dbuser,$dbpass,$dbname);
	
	$sql = " select selisih_waktu from ms.sc_zona_waktu where kode_kantor = :p_kode_kantor ";
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':p_kode_kantor', $p_kode_kantor);
	$result = 0;
	if($DB->execute()){
		$row = $DB->nextrow();
		$result = $row['SELISIH_WAKTU'];
		
	}
	$DB->close();
	return $result;
						
							
}

function encrypt_decrypt($action, $string)
{
    /* =================================================
     * ENCRYPTION-DECRYPTION
     * =================================================
     * ENCRYPTION: encrypt_decrypt('encrypt', $string);
     * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
     */
    $output = false;
    $encrypt_method = "AES-256-CBC";
    $secret_key = '5cd11dd4-4b26-48c4-95a8-868674048234';
    $secret_iv = '2e839c82-ee20-4e8e-8283-e6d670f09d96';
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);
    if ($action == 'encrypt') {
        $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
    } else {
        if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
    }
    return $output;
}


function login_pulse($user, $pwd, $url_pulse) {

	global $g_http_proxy;
	global $ipfwd;


	$api_login = $url_pulse.'/api/v1/auth';

	// set HTTP header
    $headers = array(
        'Content-Type' => 'application/json',
		'X-Forwarded-For' => $ipfwd,
    );

    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $api_login);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERPWD, "{$user}:{$pwd}");
	curl_setopt($ch, CURLOPT_TIMEOUT, 90);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    // Execute post
    $result = curl_exec($ch);
	if (curl_errno($ch)) {
		$result = "-1";
	}
    // Close connection
    curl_close($ch);

	return $result;
}

function check_user_pulse($user,$api_key, $url_pulse) {

	global $g_http_proxy;
	global $ipfwd;

	$api_login = $url_pulse.'/api/v1/system/active-users';

	// set HTTP header
    $headers = array(
        'Content-Type' => 'application/json',
		'X-Forwarded-For' => $ipfwd,
    );

	$params = array(
        'name' => $user
    );

    $ch = curl_init();
    // Set the url, number of POST vars, POST data
    curl_setopt($ch, CURLOPT_URL, $api_login.'?'.http_build_query($params));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_USERPWD, $api_key);
	curl_setopt($ch, CURLOPT_TIMEOUT, 90);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

    // Execute post
    $result = curl_exec($ch);
	
	if (curl_errno($ch)) {
		$result = "-1";
	}
    // Close connection
    curl_close($ch);
	return $result;
}


function is_allow_pulse_check($kode_user, $url_pulse, $ip_client){
	
	global $dbuser;
	global $dbpass;
	global $dbname;
	global $user_login_pulse;
	global $pass_login_pulse;

	$user_login = "";
	$DB = new Database($dbuser,$dbpass,$dbname);
	
	$sql ="select
				email
			from
					ms.sc_user
			where
					kode_user = :kode_user";
	
	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ':kode_user', $kode_user);
	$result = "";
	$user_login = "";
	$email = "";
	if($DB->execute()){
		$row = $DB->nextrow();
		$user_login = explode('@',$row['EMAIL'])[0];
		$email = $row['EMAIL'];
	}
	
	$bodyLog = array(
        "trace_id"=> uniqid(),
		"ip_user"=> $ip_client,
		"user_smile"=> $user_login,
		"kode_user"=> $kode_user,
		"email"=> $email,
		"msg"=> $msg,
    );
	
	$ret = 0;
	$resp = login_pulse($user_login_pulse, $pass_login_pulse, $url_pulse);
	if($resp == -1){
		$ret = 0;
		$bodyLog['msg'] = "Connection Timeout to Check VPN";
		dbExecuteErrorLog('Checking','Check VPN Akses', json_encode($bodyLog), "Failed");
		return $ret;
		die;
	}
	$resp_decode = json_decode($resp);
	$resp = check_user_pulse($user_login, $resp_decode->api_key, $url_pulse);
	
	if($resp == -1){
		$ret = 0;
		$bodyLog['msg'] = "Connection Timeout to Check VPN";
		dbExecuteErrorLog('Checking','Check VPN Akses', json_encode($bodyLog), "Failed");
		return $ret;
		die;
	}

	$resp_decode = json_decode($resp, true);
	$user_pulse = $resp_decode['active-users']['active-user-records']['active-user-record'][0]['active-user-name'];
	$ip_pulse = $resp_decode['active-users']['active-user-records']['active-user-record'][0]['network-connect-ip'];
	
	$bodyLog["ip_pulse"] = $ip_pulse;
    $bodyLog["user_vpn"] = $user_pulse;

	if($resp_decode['active-users']['total-matched-record-number']==0){
		$ret = 0;
		$bodyLog['msg'] = "No matched record in user vpn";
		dbExecuteErrorLog('Checking','Check VPN Akses', json_encode($bodyLog), "Failed");
		return $ret;
		die;
	}

	
	if($user_pulse==$user_login && $ip_client==$ip_pulse){
		$ret = 1;
		$bodyLog['msg'] = "Allowed";
		dbExecuteLog('Checking','Check VPN Akses', json_encode($bodyLog), "Success");
	}else{
		$ret = 0;
		$bodyLog['msg'] = "User SMILE does not equal with user vpn";
		dbExecuteLog('Checking','Check VPN Akses', json_encode($bodyLog), "Failed");
	}

	return $ret;
}

?>
