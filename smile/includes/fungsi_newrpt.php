<?PHP
include_once "conf_global.php";
require_once "class_database.php";
include_once 'imscrypt.php';

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$ls_user = $smile_rpt_user;
$ls_pass = $smile_rpt_pass;

if (!function_exists('get_url_dl_token')) {
	function get_url_dl_token($apiurl) {
		global $ipfwd;
		$headers = array(
			'Content-Type: application/json',
			'X-Forwarded-For: ' . $ipfwd
		);
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
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => $headers,
		  )
		);
	  
		$response = curl_exec($curl);
		$err = curl_error($curl);
		curl_close($curl);
	  
		if ($err) {
		  $jdata["success"] = false;
		  $jdata["message"] = "cURL Error #:" . $err;
		  $result = $jdata;
		} else {
		  $result = json_decode($response);
		}
	  
		return $result;
	  }
}

if(!function_exists("get_report_ods")) {
	function get_report_ods($report_file) {
		global $gs_DBUser;
		global $gs_DBPass;
		global $gs_DBName;
		$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
		$data_table = array();
		$report_file = strtoupper($report_file);

		$temp_report_ods = $_SESSION["REPORT_ODS_DATA"];
		if ($temp_report_ods == "" || $temp_report_ods == null) {
			$sql = "
			select 	report_file,
					report_name,
					report_host,
					report_username,
					report_password,
					report_database,
					report_path
			from 	kn.kn_kode_report_ods
			";
			$proc = $DB->parse($sql);
			if ($DB->execute()){
				while($row = $DB->nextrow()) {
					array_push($data_table, $row);
				}
			}
			$_SESSION["REPORT_ODS_DATA"] = $data_table;
		} else {
			$data_table = $_SESSION["REPORT_ODS_DATA"];
		}

		$data["REPORT_FILE"] = "";
		$data["REPORT_NAME"] = "";
		$data["REPORT_HOST"] = "";
		$data["REPORT_USERNAME"] = "";
		$data["REPORT_PASSWORD"] = "";
		$data["REPORT_DATABASE"] = "";
		$data["REPORT_PATH"] = "";

		foreach ($data_table as $row) {
			if ($row["REPORT_FILE"] == $report_file) {
				$data["REPORT_FILE"] = $row["REPORT_FILE"];
				$data["REPORT_NAME"] = $row["REPORT_NAME"];
				$data["REPORT_HOST"] = $row["REPORT_HOST"];
				$data["REPORT_USERNAME"] = $row["REPORT_USERNAME"];
				$data["REPORT_PASSWORD"] = $row["REPORT_PASSWORD"];
				$data["REPORT_DATABASE"] = $row["REPORT_DATABASE"];
				$data["REPORT_PATH"] = $row["REPORT_PATH"];
			}
		}
		
		return $data;
	}
}

?>

<script language="javascript">
	function NewWindow(mypage,myname,w,h,scroll){
	  var winl = (screen.width-w)/2;
	  var wint = (screen.height-h)/2;
	  var settings  ='height='+h+',';
		  settings +='width='+w+',';
		  settings +='top='+wint+',';
		  settings +='left='+winl+',';
		  settings +='scrollbars='+scroll+',';
		  settings +='resizable=1';
		  settings +='titlebar=0';
		  settings +='location=0';
		  settings +='addressbar=0';
		  settings +='menubar=0';
	  win=window.open(mypage,myname,settings);
	  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
	  //location.replace('REPORT_SIJSTK');
	}
	function convert_tglblth(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			switch (tlg[1]) {
				case '01':
					day = "JAN";
					break;
				case '02':
					day = "FEB";
					break;
				case '03':
					day = "MAR";
					break;
				case '04':
					day = "APR";
					break;
				case '05':
					day = "MAY";
					break;
				case '06':
					day = "JUN";
					break;
				case '07':
					day = "JUL";
					break;
				case '08':
					day = "AUG";
					break;
				case '09':
					day = "SEP";
					break;
				case '10':
					day = "OCT";
					break;
				case '11':
					day = "NOV";
					break;
				case '12':
					day = "DEC";
					break;
			}
			field1.value = tlg[0]+'-'+tlg[1]+'-'+tlg[2];
		}
	}

	function convert_blth(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[1]+'-'+tlg[2];
		}
	}

	function convert_blth_ojk(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[1]+'/'+tlg[2];
		}
	}

	function convert_bln(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[1];
		}
	}
	function convert_thn(field1) {
		var fLength = field1.value.length;
		var fval		= field1.value;
		if(fval.indexOf('/') !== -1){
			if(fLength > 0){
				tlg = fval.split('/');
			}
			field1.value = tlg[2];
		}
	}
</script>

<?PHP

function convertTGLBLTH($mydate){
	$tgl = explode('-',$mydate);
	switch ($tgl[1]) {
		case '01':
			$day = "JAN";
			break;
		case '02':
			$day = "FEB";
			break;
		case '03':
			$day = "MAR";
			break;
		case '04':
			$day = "APR";
			break;
		case '05':
			$day = "MAY";
			break;
		case '06':
			$day = "JUN";
			break;
		case '07':
			$day = "JUL";
			break;
		case '08':
			$day = "AUG";
			break;
		case '09':
			$day = "SEP";
			break;
		case '10':
			$day = "OCT";
			break;
		case '11':
			$day = "NOV";
			break;
		case '12':
			$day = "DEC";
			break;
	}
	return $tgl[0].'-'.$day.'-'.$tgl[2];
}

function convert_blth_ojk($mydate) {
	if (substr_count($mydate, '/') == '1') {
		$mydate = '01/' . $mydate;
	} else {
		$mydate = $mydate;
	}
	$tgl = explode('/',$mydate);
	return $tgl[1].'/'.$tgl[2];
}

function convert01BLTH($mydate){

	if (substr_count($mydate, '/') == '1') {
		$tgl = '01/' . $mydate;
	} else {
		$tgl = $mydate;
	}

	/*
		$searchchar="/";
		$count="0"; //zero
		for($i="0"; $i<strlen($mydate); $i=$i+1){
			if(substr($mydate,$i,1)==$searchchar){
			$count=$count+1;
			}
		}

		if ($count==1){
			$tgl = '01/' . $mydate;
		} else {
			$tgl = $mydate;
		}
	*/

	//$tgl = explode('/',$mydate);
	$tgl = explode('/',$tgl);
	switch ($tgl[1]) {
		case '01':
			$day = "JAN";
			break;
		case '02':
			$day = "FEB";
			break;
		case '03':
			$day = "MAR";
			break;
		case '04':
			$day = "APR";
			break;
		case '05':
			$day = "MAY";
			break;
		case '06':
			$day = "JUN";
			break;
		case '07':
			$day = "JUL";
			break;
		case '08':
			$day = "AUG";
			break;
		case '09':
			$day = "SEP";
			break;
		case '10':
			$day = "OCT";
			break;
		case '11':
			$day = "NOV";
			break;
		case '12':
			$day = "DEC";
			break;
	}
	return '01-'.$day.'-'.$tgl[2];
}

function exec_rpt($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, $ls_pdf, $ls_nama_rpt, $ls_user_param)
{
	global $username;
	global $gs_sid;
	global $smile_rpt_user;
	global $smile_rpt_pass;

	$ls_pdf = '1';

	$report["link"] 	= $ls_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] 	= str_replace(" ","%26",$ls_user_param);
	$report["param"] 	= str_replace("=","%3D",$report["param"]);

    // bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
	global $smile_adapter_dl_ci_private_key;
	 global $smile_adapter_dl_ci_public_key;
	 global $smile_adapter_dl_sv_public_key;
	 global $smile_adapter_dl_url;
	 global $smile_adapter_dl_url_pdf;
	 global $smile_adapter_dl_url_xls;
 
	 // Override default report link dengan report link yang baru (encryption)
	 $encrypt_rpt_link = $smile_adapter_dl_url_pdf;
 
	 $bypass_report = array();
	 $report_file = $report["file"];
	 if (!in_array($report_file, $bypass_report)) {
		 // compose payload
		 $payload 		= array("URL" => $link);
		 $payload_json 	= json_encode($payload);
		 
		 // encryption url
		 $imscrypt 						= new ImScrypt(1);
		 $imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		 $imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		 $ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
		 $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
		 $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		 $info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt";
		 $info 							= base64_encode($info);
		 
		 // get token
		 $dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		 $link 		= $encrypt_rpt_link."q=".$dl_token->token;
		 
	 } 
	
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('<?=$link?>','',950,600,0)
		</script>
		<?
	}
}

function exec_rpt_enc($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, $ls_pdf, $ls_nama_rpt, $ls_user_param)
{
	global $username;
	global $gs_sid;
	global $ipReportServer;
	global $smile_rpt_user;
	global $smile_rpt_pass;

	global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
    global $smile_adapter_dl_url_xls;

	$ls_pdf = '1';

	// Override default report link dengan report link yang baru (encryption)
	$encrypt_rpt_link = $smile_adapter_dl_url_pdf;

	$report["link"] 	= $ls_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]; // Aditya Permana-Report New Core ke 151
	
	$bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
        // compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('<?=$link?>','',950,600,0)
		</script>
		<?
	}
}

function exec_rpt_enc_tipe($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, $ls_pdf, $ls_nama_rpt, $ls_user_param, $tipe)
{
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;
	global $ipReportServer;
	global $smile_rpt_user;
	global $smile_rpt_pass;

	global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
    global $smile_adapter_dl_url_xls;

	// Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $ls_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] 	= str_replace(" ","%26",$ls_user_param);
	$report["param"] 	= str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
    }
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D". $tipe . "%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]; // Aditya Permana-Report New Core ke 151
	
	$bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_tipe";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('<?=$link?>','',950,600,0)
		</script>
		<?
	}
}

function exec_rpt_enc_new($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $smile_rpt_path.$ls_modul;

	// Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}
	$report["link"] 	= $smile_rpt_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $smile_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] 	= str_replace(" ","%26",$ls_user_param);
	$report["param"] 	= str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];	$link = $report["link"].base64_encode($link_rpt_server);
	
	$bypass_report = array("test.rdf");
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

	// $link = $report["link"].base64_encode($link_rpt_server);
	// $link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report["link"].base64_encode("http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	// print_r($link);
	
	if($ls_pdf == '9') {
		return $link_rpt_server;
	}
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_bsu($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe) //penambahan bsu 10-09-2022
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $bsu_rpt_link;
	global $bsu_rpt_user;
	global $bsu_rpt_pass;
	global $bsu_rpt_path;
	global $bsu_rpt_sid;
	global $ipReportServer;

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $bsu_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $bsu_rpt_link;
	$report["user"] 	= $bsu_rpt_user;
	$report["password"]	= $bsu_rpt_pass;
	$report["sid"] 		= $bsu_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
	$bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_bsu";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
		
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
	
	if($ls_pdf == '9') {
		return $link_rpt_server;
	}
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_drc($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;
	global $ipReportServer;

	global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid_drc;
	global $ipReportServerDRC;

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $smile_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $smile_rpt_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $smile_rpt_sid_drc;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] 	= str_replace(" ","%26",$ls_user_param);
	$report["param"] 	= str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServerDRC}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];

    $bypass_report = array("SKPR202403001.rdf");
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_drc";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
    
    if ($ls_pdf == '9') {
		return $link_rpt_server;
	}
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_vokasi($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;
	global $ipReportServer;

	global $nc_rpt_link;
	$nc_rpt_user='vks';
	$nc_rpt_pass='vks';
	global $nc_rpt_path;
	$nc_rpt_sid='sijstkecha';

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= $nc_rpt_user;
	$report["password"]	= $nc_rpt_pass;
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];

    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_vokasi";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_rsjht($parameter,$tahun)
{
	global $wsRSJHT;
	//$report["link"] = 'http://172.28.100.22:2014/DLServices/RSJHTJP/dl/';
	$tahun 			= $tahun;//date('Y',strtotime('-1 years'));
	//$link 			= 'http://172.28.100.22:2014/DLServices/RSJHTJP/dl/'.base64_encode($tahun.'|'.$parameter);
	$link 			= $wsRSJHT.'/DLServices/RSJHTJP/dl/'.base64_encode($tahun.'|'.$parameter);
	//print_r($link);

	?>
	<!-- setelah proses eksekusi selesai buka pdf report-->
	<script language="JavaScript" type="text/javascript">
		//console.log("<?=$link;?>");
		NewWindow('<?=$link;?>','',950,600,0);
	</script>
	<?
}

function exec_rpt_enc_new_rsjht_bpu($parameter)
{
	global $wsRSJHT;
	$link 			= $wsRSJHT.'/ReportService/RSJHTJP/dl/'.base64_encode($parameter);
	?>
	<!-- setelah proses eksekusi selesai buka pdf report-->
	<script language="JavaScript" type="text/javascript">
		NewWindow('<?=$link;?>','',950,600,0);
	</script>
	<?
}

function exec_rpt_enc_new_spo($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;
	global $ipReportServer;

	global $nc_rpt_link;
	$nc_rpt_user='spo';
	$nc_rpt_pass='spo';
	global $nc_rpt_path;
	$nc_rpt_sid='sijstkecha';

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= $nc_rpt_user;
	$report["password"]	= $nc_rpt_pass;
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_spo";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
    
    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_spo_drc($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $spo_rpt_link;
	global $spo_rpt_user;
	global $spo_rpt_pass;
	global $spo_rpt_path;
	global $spo_rpt_sid_drc;
	global $ipReportServerDRC;

	$path = $spo_rpt_path.$ls_modul;
	$report["link"] 	= $spo_rpt_link;
	$report["user"] 	= $spo_rpt_user;
	$report["password"]	= $spo_rpt_pass;
	$report["sid"] 		= $spo_rpt_sid_drc;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

    // bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	//$link = $report["link"].base64_encode("http://172.28.101.41/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	$link = $report["link"].base64_encode("{$ipReportServerDRC}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	//$link = $report["link"].base64_encode("http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	// $link = $report['link']."http://172.28.208.151/reports/rwservlet/setauth?button=Submit&username=".$report['user']."&password=".$report['password']."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report['sid']."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report['file']."%26userid%3D".$report['path']."%26".$report['param']."";
	//print_r($link);
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_oss($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $oss_rpt_link;
	global $oss_rpt_user;
	global $oss_rpt_pass;
	global $oss_rpt_path;
	global $oss_rpt_sid;
	global $ipReportServer;

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $oss_rpt_link;
	$report["user"] 	= $oss_rpt_user;
	$report["password"]	= $oss_rpt_pass;
	$report["sid"] 		= $oss_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

    // bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];

    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
    
    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_csv($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $oss_rpt_link;
	global $oss_rpt_user;
	global $oss_rpt_pass;
	global $oss_rpt_path;
	global $oss_rpt_sid;
	global $ipReportServer;

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $oss_rpt_link;
	$report["user"] 	= $oss_rpt_user;
	$report["password"]	= $oss_rpt_pass;
	$report["sid"] 		= $oss_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

    // bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26desformat%3Ddelimiteddata%26"."%26delimiter%3D|%26".$report["param"];
	
    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_csv";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_ec($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	global $nc_rpt_path;
	global $nc_rpt_sid;
	global $ipReportServer;

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= "ec";
	$report["password"]	= "WELCOME1";
	$report["sid"] 		= "sijstkecha";
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_ec";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
    
    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_ec_drc($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $nc_rpt_link;
	global $nc_rpt_path;
	global $nc_rpt_sid;
	global $ipReportServerDRC;

    global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

    global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= "ec";
	$report["password"]	= "WELCOME1";
	$report["sid"] 		= "sijstkechadrc";
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServerDRC}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
    
    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_kpi($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

    global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $smile_rpt_path.$ls_modul;
    
    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}
	
	$report["link"] 	= $smile_rpt_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $smile_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] 	= str_replace(" ","%26",$ls_user_param);
	$report["param"] 	= str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_kpi";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}
    
    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_antrian($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;
	global $ipReportServer;

	global $antrian_rpt_user;
	global $antrian_rpt_pass;
	global $antrian_rpt_sid;
	global $antrian_rpt_path;
	global $antrian_rpt_link;

    global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$nc_rpt_user = $antrian_rpt_user;
	$nc_rpt_pass = $antrian_rpt_pass;
	$nc_rpt_sid  = $antrian_rpt_sid;
	$nc_rpt_path = $antrian_rpt_path;
	$nc_rpt_link = $antrian_rpt_link;

    // Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$path = $nc_rpt_path.$ls_modul;
	$report["link"] 	= $nc_rpt_link;
	$report["user"] 	= $nc_rpt_user;
	$report["password"]	= $nc_rpt_pass;
	$report["sid"] 		= $nc_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] 	= str_replace(" ","%26",$ls_user_param);
	$report["param"] 	= str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
	
    $bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_antrian";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

    if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

function exec_rpt_enc_new_csv_proker($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe)
{
      $tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	  global $username;
      global $gs_sid;
      global $smile_rpt_user;
      global $smile_rpt_pass;

	  global $smile_rpt_link;
	  global $smile_rpt_user;
	  global $smile_rpt_pass;
	  global $smile_rpt_path;
	  global $smile_rpt_sid;
	  global $ipReportServer;

	  $ls_link=$smile_rpt_link;

        $ls_pdf = '1';

        $report["link"]         = $ls_link;
        $report["user"]         = $smile_rpt_user;
        $report["password"]     = $smile_rpt_pass;
        $report["sid"]          = "dboltp";
        $report["path"]         = urlencode($ls_path);
        $report["file"]         = $ls_nama_rpt;
        $report["param"]        = str_replace(" ","%26",$ls_user_param);
        $report["param"]        = str_replace("=","%3D",$report["param"]);

    // bgn: override untuk kebutuhan report ods
        $report_ods = get_report_ods($ls_nama_rpt);
        if ($report_ods["REPORT_USERNAME"] != "") {
                $report["user"] = $report_ods["REPORT_USERNAME"];
        }
        if ($report_ods["REPORT_PASSWORD"] != "") {
                $report["password"] = $report_ods["REPORT_PASSWORD"];
        }
        if ($report_ods["REPORT_DATABASE"] != "") {
                $report["sid"] = $report_ods["REPORT_DATABASE"];
        }
        // end: override untuk kebutuhan report ods
	  
	  $link = $report["link"].base64_encode("{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26desformat%3Ddelimiteddata%26"."%26delimiter%3D|%26".$report["param"]);
        //$link = $report["link"]."/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"];
        if($ls_pdf == '1')
	  {
                ?>
                <!-- setelah proses eksekusi selesai buka pdf report-->
                <script language="JavaScript" type="text/javascript">
                        NewWindow('<?=$link?>','',950,600,0)
                </script>
                <?
        }
}

function exec_rpt_enc_new_JMR0028($ls_pdf,$ls_modul,$ls_nama_rpt, $ls_user_param, $tipe, $ls_bufffers)
{
	//echo $nc_rpt_user;exit;
	$tipe = $tipe != "PDF" ? "SPREADSHEET" : "PDF";
	global $username;
	global $gs_sid;

	global $smile_rpt_link;
	global $smile_rpt_user;
	global $smile_rpt_pass;
	global $smile_rpt_path;
	global $smile_rpt_sid;
	global $ipReportServer;

	global $smile_adapter_dl_ci_private_key;
	global $smile_adapter_dl_ci_public_key;
	global $smile_adapter_dl_sv_public_key;
	global $smile_adapter_dl_url;
	global $smile_adapter_dl_url_pdf;
	global $smile_adapter_dl_url_xls;

	$path = $nc_rpt_path.$ls_modul;

	// Override default report link dengan report link yang baru (encryption)
	if ($tipe == "PDF") {
		$encrypt_rpt_link = $smile_adapter_dl_url_pdf;
	} else {
		$encrypt_rpt_link = $smile_adapter_dl_url_xls;
	}

	$report["link"] 	= $smile_rpt_link;
	$report["user"] 	= $smile_rpt_user;
	$report["password"]	= $smile_rpt_pass;
	$report["sid"] 		= $smile_rpt_sid;
	$report["path"] 	= urlencode($path);
	$report["file"] 	= $ls_nama_rpt;
	$report["buffers"] 	= urlencode("3D" . $ls_bufffers . "&");
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$report["user"] = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$report["password"] = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$report["sid"] = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$link_rpt_server = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3D".$tipe."%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]."%26BUFFERS%".$report["buffers"];
	
	$bypass_report = array();
	$report_file = $report["file"];
	if (!in_array($report_file, $bypass_report)) {
		// compose payload
		$payload 		= array("URL" => $link_rpt_server);
		$payload_json 	= json_encode($payload);

		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_newrpt.exec_rpt_enc_new_JMR0028";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$link 		= $encrypt_rpt_link."q=".$dl_token->token;
	} else {
		$link = $report["link"].base64_encode($link_rpt_server);
	}

	if($ls_pdf == '9') {
		return $link_rpt_server;
	}
	if($ls_pdf == '1')
	{
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			// console.log("<?=$ls_user_param;?>");
			NewWindow('<?=$link;?>','',950,600,0);
		</script>
		<?
	}
}

?>
