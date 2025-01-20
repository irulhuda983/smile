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
		  settings +='location=0';
		  settings +='menubar=0';
	  win=window.open(mypage,myname,settings);
	  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
	  //location.replace('REPORT_SIJSTK');
	}
	
	function NewWindow3(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='scrollbars='+scroll+',';
      settings +='resizable=1';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}
</script>

<?php
include_once "conf_global.php";
include_once 'imscrypt.php';


//update 13-juni-2009, log untuk setiap pencetakan report
define('username', $username);
define('regrole', $regrole);
define('gs_kantor_aktif', $gs_kantor_aktif);

global $DB;
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

/* these variables come from conf_global.php */

//$gs_sid = $gs_DBUser."/".$gs_DBPass."@".$gs_DBName;
$gs_sid = $gs_DBUser."/".$gs_DBPass."@sijstkoltp";

function exec_rpt($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null)
{
	global $CONFIG;
	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220

	
	// These are path for development under LINUX' environment in D'Best Office
	$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/opt/xampp/htdocs/sijstk/rdf/';
	$gs_path_pdf = '/opt/xampp/htdocs/sijstk/pdf/';
	

	/*
	// These are path for locally development (under WINNT) 
	//	old value --->   $gs_executable = "D:\Oracle\OraNT\BIN";
	$gs_executable = "E:\oracle\OraNT\BIN";
	*/
	/*
	if($ls_convpdf == '1')
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe destype=file desformat=pdf PRINTJOB=NO";
		$gs_path_frs = $gs_executable."\\rwrun60.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe";
		$gs_path_frs = $gs_executable."\\rwrun60.exe";
  */
  
	/****************************************************/
	/* Path untuk file executable:							*/
	/* Ezron	: D:\ORACLE\ORADEV6i\BIN					*/
	/* Budi 	: C:\ORANT\BIN							*/
	/* Budi 	: G:\oracle\OraNT\BIN						*/
	/*											*/
	/****************************************************/

	//$gs_path_report = 'D:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	//$gs_path_pdf = 'D:\Reactor\Core\htdocs\sijstkcore\pdf/';
	/*
	$gs_path_report = 'E:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	$gs_path_pdf = 'E:\Reactor\Core\htdocs\sijstkcore\pdf/';
  */ 
	/****************************************************/
	/* Path untuk file PDF dan REP:						*/
	/* Ezron	: F:\WEBAPP\htdocs\dpkponline				*/
	/* Budi 	: C:\Reactor\Core\htdocs\dpkponline2			*/
	/* Rusland 	: F:\serverlocal\siinvest						*/
	/*											*/
	/****************************************************/

	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");

	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;

	//$pkp_exec = $gs_path_frs."' userid='".$gs_sid."' paramform=."'$paramform'".' desname='".'"'.$ls_pdf.'.pdf'.'"'.' report='.$gs_path_report.$ls_nama_rpt.' '.$ls_user_param;
	$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
		
	//echo $pkp_exec;
	//shell_exec($pkp_exec);
	//echo $ls_user_param."<BR>";
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	//edited by zimmy/opa/dewa @kartika candra @22jun2015 - ubah pola report dari rwrun menjadi rwservlet
	//$rwservlet = 'http://172.28.201.157:7779/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=sijstkoltp&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;

	//$ls_link 	= "http://172.28.200.11:1802/smile/includes/rptBPJS.php?url=";
	$ls_link 	= $CONFIG->REPORT_SERVER_HOST_PATH;
	$report["link"] 	= $ls_link;
	
	// reconfig_global
	$ip_report = $CONFIG->REPORT_SERVER_IP;
	$user_db_report = $CONFIG->REPORT_SERVER_USER_DB_13;
	$pass_db_report = $CONFIG->REPORT_SERVER_PASS_DB_13;
	$name_db_report = $CONFIG->REPORT_SERVER_NAME_DB_13;
	// reconfig_global

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$user_db_report = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$pass_db_report = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$name_db_report = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods
	

	//$rwservlet = 'http://172.28.108.151/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	$rwservlet = $ip_report.'/reports/rwservlet/setauth?button=Submit&username='
		.$user_db_report.'&password='
		.$pass_db_report.'&authtype=D&mask=GQ%253D%253D&isjsp=no&database='
		.$name_db_report.'&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	//$rwservlet = 'http://172.28.200.21/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	// $rwservlet = 'http://rptserverdc.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	
	 $rwservlet = str_replace("'","",$rwservlet);
	 $rwservlet = str_replace("'","",$rwservlet);
	 
	//  $rwservlet = $report["link"].base64_encode($rwservlet);
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
		$payload 		= array("URL" => $rwservlet);
		$payload_json 	= json_encode($payload);
		
		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_rpt.exec_rpt";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$rwservlet 		= $encrypt_rpt_link."q=".$dl_token->token;
		
	}
	
	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">		
			NewWindow('<?=$rwservlet?>','',800,600,1)		
			//NewWindow('../../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)			
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
	}
	
	//update 03-juni-2017,log untuk setiap pencetakan report
  if($DB!=null){
      $clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
      $sql = "insert into sijstk.sc_audit_akses_report ( ".
             "	kode_user, tgl_akses, kode_report, ". 
             "	inisial_fungsi, kantor_fungsi, ip_server, ". 
             "	nama_app_server, nama_host, ip_client, ".
             "	tgl_rekam, petugas_rekam) ".
						 "values ( ".
						 "	'".username."', sysdate, replace(upper('".$ls_nama_rpt."'),'.RDF',''), ".
						 "	'".regrole."', '".gs_kantor_aktif."', '".$_SERVER['HTTP_HOST']."', ".
						 "	'".$host."', '".@gethostbyaddr($clientIp)."','$clientIp', ".
						 "	sysdate, 'SIJSTK' ".
						 ")";						 
      $DB->parse($sql);
      $DB->execute();
  }
	//end log	
}

function exec_rpt_drc($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null)
{
	global $CONFIG;
	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220

	
	// These are path for development under LINUX' environment in D'Best Office
	$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/opt/xampp/htdocs/sijstk/rdf/';
	$gs_path_pdf = '/opt/xampp/htdocs/sijstk/pdf/';
	

	/*
	// These are path for locally development (under WINNT) 
	//	old value --->   $gs_executable = "D:\Oracle\OraNT\BIN";
	$gs_executable = "E:\oracle\OraNT\BIN";
	*/
	/*
	if($ls_convpdf == '1')
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe destype=file desformat=pdf PRINTJOB=NO";
		$gs_path_frs = $gs_executable."\\rwrun60.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe";
		$gs_path_frs = $gs_executable."\\rwrun60.exe";
  */
  
	/******************/
	/* Path untuk file executable:							*/
	/* Ezron	: D:\ORACLE\ORADEV6i\BIN					*/
	/* Budi 	: C:\ORANT\BIN							*/
	/* Budi 	: G:\oracle\OraNT\BIN						*/
	/*											*/
	/******************/

	//$gs_path_report = 'D:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	//$gs_path_pdf = 'D:\Reactor\Core\htdocs\sijstkcore\pdf/';
	/*
	$gs_path_report = 'E:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	$gs_path_pdf = 'E:\Reactor\Core\htdocs\sijstkcore\pdf/';
  */ 
	/******************/
	/* Path untuk file PDF dan REP:						*/
	/* Ezron	: F:\WEBAPP\htdocs\dpkponline				*/
	/* Budi 	: C:\Reactor\Core\htdocs\dpkponline2			*/
	/* Rusland 	: F:\serverlocal\siinvest						*/
	/*											*/
	/******************/

	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");

	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;

	//$pkp_exec = $gs_path_frs."' userid='".$gs_sid."' paramform=."'$paramform'".' desname='".'"'.$ls_pdf.'.pdf'.'"'.' report='.$gs_path_report.$ls_nama_rpt.' '.$ls_user_param;
	$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
		
	//echo $pkp_exec;
	//shell_exec($pkp_exec);
	//echo $ls_user_param."<BR>";
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	//start angga eri
	// $ls_user_param = str_replace("'%'","%2525",$ls_user_param);
	// echo $ls_user_param;
	// die;
	//end angga eri
	//edited by zimmy/opa/dewa @kartika candra @22jun2015 - ubah pola report dari rwrun menjadi rwservlet
	// $rwservlet = 'http://172.28.201.157:7779/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=sijstkoltp&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	$ipdrc = $CONFIG->REPORT_SERVER_IP_DRC;
	$userdrc = $CONFIG->REPORT_SERVER_USER_DB_14;
	$passdrc = $CONFIG->REPORT_SERVER_PASS_DB_14;
	$siddrc = $CONFIG->REPORT_SERVER_NAME_DB_14;

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$userdrc = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$passdrc = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$siddrc = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	$rwservlet = $ipdrc.'/reports/rwservlet/setauth?button=Submit&username='.$userdrc.'&password='.$passdrc.'&authtype=D&mask=GQ%253D%253D&isjsp=no&database='.$siddrc.'&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIPT%2FKP%2FREPORT%26'.$ls_user_param;
	
	$rwservlet = str_replace("'","",$rwservlet);
	
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
		$payload 		= array("URL" => $rwservlet);
		$payload_json 	= json_encode($payload);
		
		// encryption url
		$imscrypt 						= new ImScrypt(1);
		$imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		$imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		$ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
        $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
        $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		$info 							= "RDF=".$report_file."|FN=fungsi_rpt.exec_rpt_drc";
		$info 							= base64_encode($info);
		
		// get token
		$dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		$rwservlet 		= $encrypt_rpt_link."q=".$dl_token->token;
		
	} 

	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">		
			NewWindow('<?=$rwservlet?>','',800,600,1)		
			//NewWindow('../../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)			
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
	}
	
	//update 03-juni-2017,log untuk setiap pencetakan report
  if($DB!=null){
      $clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
      $sql = "insert into sijstk.sc_audit_akses_report ( ".
             "	kode_user, tgl_akses, kode_report, ". 
             "	inisial_fungsi, kantor_fungsi, ip_server, ". 
             "	nama_app_server, nama_host, ip_client, ".
             "	tgl_rekam, petugas_rekam) ".
						 "values ( ".
						 "	'".username."', sysdate, replace(upper('".$ls_nama_rpt."'),'.RDF',''), ".
						 "	'".regrole."', '".gs_kantor_aktif."', '".$_SERVER['HTTP_HOST']."', ".
						 "	'".$host."', '".@gethostbyaddr($clientIp)."','$clientIp', ".
						 "	sysdate, 'SIJSTK' ".
						 ")";						 
      $DB->parse($sql);
      $DB->execute();
  }
	//end log	
}

function exec_rpt_xls($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null)
{
	global $CONFIG;
	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220

	
	// These are path for development under LINUX' environment in D'Best Office
	$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/opt/xampp/htdocs/sijstk/rdf/';
	$gs_path_pdf = '/opt/xampp/htdocs/sijstk/pdf/';
	

	/*
	// These are path for locally development (under WINNT) 
	//	old value --->   $gs_executable = "D:\Oracle\OraNT\BIN";
	$gs_executable = "E:\oracle\OraNT\BIN";
	*/
	/*
	if($ls_convpdf == '1')
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe destype=file desformat=pdf PRINTJOB=NO";
		$gs_path_frs = $gs_executable."\\rwrun60.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe";
		$gs_path_frs = $gs_executable."\\rwrun60.exe";
  */
  
	/****************************************************/
	/* Path untuk file executable:							*/
	/* Ezron	: D:\ORACLE\ORADEV6i\BIN					*/
	/* Budi 	: C:\ORANT\BIN							*/
	/* Budi 	: G:\oracle\OraNT\BIN						*/
	/*											*/
	/****************************************************/

	//$gs_path_report = 'D:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	//$gs_path_pdf = 'D:\Reactor\Core\htdocs\sijstkcore\pdf/';
	/*
	$gs_path_report = 'E:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	$gs_path_pdf = 'E:\Reactor\Core\htdocs\sijstkcore\pdf/';
  */ 
	/****************************************************/
	/* Path untuk file PDF dan REP:						*/
	/* Ezron	: F:\WEBAPP\htdocs\dpkponline				*/
	/* Budi 	: C:\Reactor\Core\htdocs\dpkponline2			*/
	/* Rusland 	: F:\serverlocal\siinvest						*/
	/*											*/
	/****************************************************/

	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");

	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;

	//$pkp_exec = $gs_path_frs."' userid='".$gs_sid."' paramform=."'$paramform'".' desname='".'"'.$ls_pdf.'.pdf'.'"'.' report='.$gs_path_report.$ls_nama_rpt.' '.$ls_user_param;
	$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
		
	//echo $pkp_exec;
	//shell_exec($pkp_exec);
	//echo $ls_user_param."<BR>";
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	//edited by zimmy/opa/dewa @kartika candra @22jun2015 - ubah pola report dari rwrun menjadi rwservlet
	//$rwservlet = 'http://172.28.201.157:7779/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=sijstkoltp&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;

	$ls_link 	= $CONFIG->REPORT_SERVER_HOST_PATH;
	//$ls_link 	= "http://172.28.200.11:1802/smile/includes/rptBPJS.php?url=";
	$report["link"] 	= $ls_link;
	
	// reconfig_global
	$ip_report = $CONFIG->REPORT_SERVER_IP;
	$user_db_report = $CONFIG->REPORT_SERVER_USER_DB_15;
	$pass_db_report = $CONFIG->REPORT_SERVER_PASS_DB_15;
	$name_db_report = $CONFIG->REPORT_SERVER_NAME_DB_15;
	// reconfig_global

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$user_db_report = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$pass_db_report = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$name_db_report = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	//$rwservlet = 'http://172.28.108.151/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	$rwservlet = $ip_report.'/reports/rwservlet/setauth?button=Submit&username='
		.$user_db_report.'&password='
		.$pass_db_report.'&authtype=D&mask=GQ%253D%253D&isjsp=no&database='
		.$name_db_report.'&nextpage=destype%3Dcache%26desname%3D'.$ls_nama_rpt.'.xls%26desformat%3Denhancedspreadsheet%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	//$rwservlet = 'http://172.28.200.21/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desname%3D'.$ls_nama_rpt.'.xls%26desformat%3Denhancedspreadsheet%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	 $rwservlet = str_replace("'","",$rwservlet);
	 $rwservlet = str_replace("'","",$rwservlet);
	 
	 
	 global $smile_adapter_dl_ci_private_key;
	 global $smile_adapter_dl_ci_public_key;
	 global $smile_adapter_dl_sv_public_key;
	 global $smile_adapter_dl_url;
	 global $smile_adapter_dl_url_pdf;
	 global $smile_adapter_dl_url_xls;
 
	 // Override default report link dengan report link yang baru (encryption)
	 $encrypt_rpt_link = $smile_adapter_dl_url_xls;
 
	 $bypass_report = array();
	 $report_file = $report["file"];
	 if (!in_array($report_file, $bypass_report)) {
		 // compose payload
		 $payload 		= array("URL" => $rwservlet);
		 $payload_json 	= json_encode($payload);
		 
		 // encryption url
		 $imscrypt 						= new ImScrypt(1);
		 $imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		 $imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		 $ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
		 $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
		 $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		 $info 							= "RDF=".$report_file."|FN=fungsi_rpt.exec_rpt_xls";
		 $info 							= base64_encode($info);
		 
		 // get token
		 $dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		 $rwservlet 		= $encrypt_rpt_link."q=".$dl_token->token;
		 
	 } 

	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">		
			NewWindow('<?=$rwservlet?>','',800,600,1)		
			//NewWindow('../../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)			
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
	}
	
	//update 03-juni-2017,log untuk setiap pencetakan report
  if($DB!=null){
      $clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
      $sql = "insert into sijstk.sc_audit_akses_report ( ".
             "	kode_user, tgl_akses, kode_report, ". 
             "	inisial_fungsi, kantor_fungsi, ip_server, ". 
             "	nama_app_server, nama_host, ip_client, ".
             "	tgl_rekam, petugas_rekam) ".
						 "values ( ".
						 "	'".username."', sysdate, replace(upper('".$ls_nama_rpt."'),'.RDF',''), ".
						 "	'".regrole."', '".gs_kantor_aktif."', '".$_SERVER['HTTP_HOST']."', ".
						 "	'".$host."', '".@gethostbyaddr($clientIp)."','$clientIp', ".
						 "	sysdate, 'SIJSTK' ".
						 ")";						 
      $DB->parse($sql);
      $DB->execute();
  }
	//end log	
}

function exec_rpt_sijstk($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null)
{
	global $CONFIG;
	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220

	
	// These are path for development under LINUX' environment in D'Best Office
	$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/opt/xampp/htdocs/sijstk/rdf/';
	$gs_path_pdf = '/opt/xampp/htdocs/sijstk/pdf/';
	

	/*
	// These are path for locally development (under WINNT) 
	//	old value --->   $gs_executable = "D:\Oracle\OraNT\BIN";
	$gs_executable = "E:\oracle\OraNT\BIN";
	*/
	/*
	if($ls_convpdf == '1')
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe destype=file desformat=pdf PRINTJOB=NO";
		$gs_path_frs = $gs_executable."\\rwrun60.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe";
		$gs_path_frs = $gs_executable."\\rwrun60.exe";
  */
  
	/****************************************************/
	/* Path untuk file executable:							*/
	/* Ezron	: D:\ORACLE\ORADEV6i\BIN					*/
	/* Budi 	: C:\ORANT\BIN							*/
	/* Budi 	: G:\oracle\OraNT\BIN						*/
	/*											*/
	/****************************************************/

	//$gs_path_report = 'D:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	//$gs_path_pdf = 'D:\Reactor\Core\htdocs\sijstkcore\pdf/';
	/*
	$gs_path_report = 'E:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	$gs_path_pdf = 'E:\Reactor\Core\htdocs\sijstkcore\pdf/';
  */ 
	/****************************************************/
	/* Path untuk file PDF dan REP:						*/
	/* Ezron	: F:\WEBAPP\htdocs\dpkponline				*/
	/* Budi 	: C:\Reactor\Core\htdocs\dpkponline2			*/
	/* Rusland 	: F:\serverlocal\siinvest						*/
	/*											*/
	/****************************************************/

	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");

	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;

	//$pkp_exec = $gs_path_frs."' userid='".$gs_sid."' paramform=."'$paramform'".' desname='".'"'.$ls_pdf.'.pdf'.'"'.' report='.$gs_path_report.$ls_nama_rpt.' '.$ls_user_param;
	$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
		
	//echo $pkp_exec;
	//shell_exec($pkp_exec);
	//echo $ls_user_param."<BR>";
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	//edited by zimmy/opa/dewa @kartika candra @22jun2015 - ubah pola report dari rwrun menjadi rwservlet
	
	// reconfig_global
	$ip_report = $CONFIG->REPORT_SERVER_IP;
	$user_db_report = $CONFIG->REPORT_SERVER_USER_DB_16;
	$pass_db_report = $CONFIG->REPORT_SERVER_PASS_DB_16;
	$name_db_report = $CONFIG->REPORT_SERVER_NAME_DB_16;
	// reconfig_global

	// bgn: override untuk kebutuhan report ods
	$report_ods = get_report_ods($ls_nama_rpt);
	if ($report_ods["REPORT_USERNAME"] != "") {
		$user_db_report = $report_ods["REPORT_USERNAME"];
	}
	if ($report_ods["REPORT_PASSWORD"] != "") {
		$pass_db_report = $report_ods["REPORT_PASSWORD"];
	}
	if ($report_ods["REPORT_DATABASE"] != "") {
		$name_db_report = $report_ods["REPORT_DATABASE"];
	}
	// end: override untuk kebutuhan report ods

	//$rwservlet = 'http://172.28.108.151/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	// $rwservlet = 'http://172.28.201.157:7779/reports/rwservlet/setauth?button=Submit&username=kn&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIJSTK%2FReport%2FSIJSTK%26'.$ls_user_param;
	$rwservlet = $ip_report.'/reports/rwservlet/setauth?button=Submit&username='
		.$user_db_report.'&password='
		.$pass_db_report.'&authtype=D&mask=GQ%253D%253D&isjsp=no&database='
		.$name_db_report.'&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	//$rwservlet = 'http://172.28.200.21/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	$rwservlet = str_replace("'","",$rwservlet);
	
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
		 $payload 		= array("URL" => $rwservlet);
		 $payload_json 	= json_encode($payload);
		 
		 // encryption url
		 $imscrypt 						= new ImScrypt(1);
		 $imscrypt->kxci[CIPUBLIC_KEY] 	= $smile_adapter_dl_ci_public_key;
		 $imscrypt->kxci[CIPRIVATE_KEY] 	= $smile_adapter_dl_ci_private_key;
		 $ciphertext 					= $imscrypt->encrypt($payload_json, $smile_adapter_dl_sv_public_key);
		 $sgpair_key 					= $imscrypt->sign_detached($ciphertext);
		 $cipher_params 					= "c={$ciphertext}&xa={$smile_adapter_dl_ci_public_key}&xb={$smile_adapter_dl_sv_public_key}&sp={$sgpair_key['publickey']}&sg={$sgpair_key['signature']}";
		 $info 							= "RDF=".$report_file."|FN=fungsi_rpt.exec_rpt_sijstk";
		 $info 							= base64_encode($info);
		 
		 // get token
		 $dl_token   = get_url_dl_token($smile_adapter_dl_url."/auth-token?".$cipher_params."&info=".$info);
		 $rwservlet 		= $encrypt_rpt_link."q=".$dl_token->token;
		 
	 } 

	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">		
			NewWindow('<?=$rwservlet?>','',800,600,1)		
			//NewWindow('../../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)			
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
	}
	
	//update 03-juni-2017,log untuk setiap pencetakan report
  if($DB!=null){
      $clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
      $sql = "insert into sijstk.sc_audit_akses_report ( ".
             "	kode_user, tgl_akses, kode_report, ". 
             "	inisial_fungsi, kantor_fungsi, ip_server, ". 
             "	nama_app_server, nama_host, ip_client, ".
             "	tgl_rekam, petugas_rekam) ".
						 "values ( ".
						 "	'".username."', sysdate, replace(upper('".$ls_nama_rpt."'),'.RDF',''), ".
						 "	'".regrole."', '".gs_kantor_aktif."', '".$_SERVER['HTTP_HOST']."', ".
						 "	'".$host."', '".@gethostbyaddr($clientIp)."','$clientIp', ".
						 "	sysdate, 'SIJSTK' ".
						 ")";						 
      $DB->parse($sql);
      $DB->execute();
  }
	//end log	
}

function exec_rpt_surat($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null)
{
	global $CONFIG;
	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220

	
	// These are path for development under LINUX' environment in D'Best Office
	$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/opt/xampp/htdocs/sijstk/rdf/';
	$gs_path_pdf = '/opt/xampp/htdocs/sijstk/pdf/';
	

	
	// These are path for locally development (under WINNT) 
	//	old value --->   $gs_executable = "D:\Oracle\OraNT\BIN";
	/*
	$gs_executable = "E:\oracle\OraNT\BIN";
	
	if($ls_convpdf == '1')
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe destype=file desformat=pdf PRINTJOB=NO";
		$gs_path_frs = $gs_executable."\\rwrun60.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		//	Old Value ---> $gs_path_frs = $gs_executable."\\rwrun.exe";
		$gs_path_frs = $gs_executable."\\rwrun60.exe";

  */
  
	/****************************************************/
	/* Path untuk file executable:							*/
	/* Ezron	: D:\ORACLE\ORADEV6i\BIN					*/
	/* Budi 	: C:\ORANT\BIN							*/
	/* Budi 	: G:\oracle\OraNT\BIN						*/
	/*											*/
	/****************************************************/
  
  /*
	$gs_path_report = 'E:\Reactor\Core\htdocs\sijstkcore\rdf\\';
	$gs_path_pdf = 'E:\Reactor\Core\htdocs\sijstkcore\pdf/';
	*/
	
	/****************************************************/
	/* Path untuk file PDF dan REP:						*/
	/* Ezron	: F:\WEBAPP\htdocs\dpkponline				*/
	/* Budi 	: C:\Reactor\Core\htdocs\dpkponline2			*/
	/* Rusland 	: F:\serverlocal\siinvest						*/
	/*											*/
	/****************************************************/

	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");

	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;

	//$pkp_exec = $gs_path_frs."' userid='".$gs_sid."' paramform=."'$paramform'".' desname='".'"'.$ls_pdf.'.pdf'.'"'.' report='.$gs_path_report.$ls_nama_rpt.' '.$ls_user_param;
	$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
	
	echo $pkp_exec;
	shell_exec($pkp_exec);
	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('../../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
	}
	//update 13-juni-2009,log untuk setiap pencetakan report
  if($DB!=null){
      $clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
      $sql = "insert into sijstk.sc_audit_akses_report ( ".
             "	kode_user, tgl_akses, kode_report, ". 
             "	inisial_fungsi, kantor_fungsi, ip_server, ". 
             "	nama_app_server, nama_host, ip_client, ".
             "	tgl_rekam, petugas_rekam) ".
						 "values ( ".
						 "	'".username."', sysdate, replace(upper('".$ls_nama_rpt."'),'.RDF',''), ".
						 "	'".regrole."', '".gs_kantor_aktif."', '".$_SERVER['HTTP_HOST']."', ".
						 "	'".$host."', '".@gethostbyaddr($clientIp)."','$clientIp', ".
						 "	sysdate, 'SIJSTK' ".
						 ")";						 
      $DB->parse($sql);
      $DB->execute();
  }
	//end log		
}

//added sendmail by zimmy gurning @16sept2013
function file_pdf($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null, $ls_kodetenant)
{
	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; 
	
	$gs_executable = "C:\orant\BIN";
	
	if($ls_convpdf == '1')
		$gs_path_frs = $gs_executable."\\rwrun60.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		$gs_path_frs = $gs_executable."\\rwrun60.exe";

	$gs_path_report = 'D:\BPJS\Reactor\Core\htdocs\sijstkcore\rdf\\';
	$gs_path_pdf = 'D:\BPJS\Reactor\Core\htdocs\sijstkcore\pdf/';

	$ld_timestamp = date("d-m-Y-H-i-s");

	$ls_namepdf = $username."".$ls_kodetenant."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;

	$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
		
	echo $pkp_exec;
	exec($pkp_exec);
	if($ls_convpdf == '1')
	{
	//kirim_email($isi_body,$isi_subjek,$isi_teks_body,$file_attachment, $to_email);
	$to_email = "gurning@gmail.com";
	$filepath = "D:/BPJS/Reactor/Core/htdocs/sijstkcore/pdf/";

	$file_attachment = $filepath . $ls_namepdf. ".pdf";
	
	kirim_email("Konfirmasi Tagihan \n Lihat lampiran untuk melihat data konfirmasi tagihan, terimakasih.","Konfirmasi Tagihan",'See Attachment for more details',$file_attachment, $to_email);
	?>
	<script language="JavaScript" type="text/javascript">
		//alert("<?=$file_attachment?>");
		</script>
		<?
	}	
	
		

	//end log	
}

?>
