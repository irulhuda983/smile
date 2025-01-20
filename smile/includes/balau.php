<?php
include "../../includes/conf_global.php";
include "../../includes/connsql.php";
require_once "../../includes/fungsi.php";
include "../../includes/class_database.php"; 
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

//add session
session_start();

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

if(( time() - $_SESSION['LOGIN_AT'] > 120*60) || ($_SESSION["STATUS"] != "LOGIN")){
	$tes=$_SESSION['LOGIN_AT'].$_SESSION["STATUS"];
	echo "<script>window.location='/smile/mod_ec/sc_sessionexpired.php';</script>";
}
if ( $_SESSION['x_kode_kantor'] == '') {
	$_SESSION['x_kode_kantor'] = $_SESSION['kdkantorrole'];
}
$sql = "SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR WHERE KODE_KANTOR = '".$_SESSION['x_kode_kantor']."' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$_SESSION['x_nama_kantor'] = $row['NAMA_KANTOR'];

$sql = "SELECT MAX(TAHUN) TAHUN FROM SIJSTK.BG_TAHUN_ANGGARAN WHERE STATUS = 'T' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
if ( $_SESSION['x_tahun'] == '') {
	$_SESSION['x_tahun'] =  $row['TAHUN'];
}

$sql = "select nama_periode from sijstk.bg_real_bulan where tahun = '".$_SESSION['x_tahun']."' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$_SESSION['x_periode'] = $row['NAMA_PERIODE'];

//added 26 Jun 2016 by Hotdin: Tambahkan status penyusunan anggaran ke session;
$sql = "select 'Tahap '||no_level||': '||nama_level x_status,
			''||to_char(tgl_open,'dd Mon yyyy')||' s.d '||to_char(tgl_close,'dd Mon yyyy') x_periode
			from sijstk.bg_level_penetapan a
			where tahun='".$_SESSION['x_tahun']."' and no_level in
			(
				select min(to_number(no_level)) from sijstk.bg_level_penetapan
				where tahun=a.tahun and nvl(st_close,'T')='T'
			) ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$_SESSION['x_status_penyusunan'] = $row['X_STATUS'];
$_SESSION['x_periode_penyusunan'] = $row['X_PERIODE'];
// end of added 26 Jun 2016

//under construction
function paging($ls_sql){
		// Explode SQL Statement into array [SELECT, FROM, WHERE, ORDER BY]
		$tmp = ''; 
		$itter_after_reset	= 0;
		$itter_bracket		= 0;
		$keyword_position	= '';
		$arr_word			= array();
		$arr_keyword_query	= array('SELECT' => '', 'FROM' => '', 'WHERE' => '', 'ORDER BY' => '');
		$st_cutword			= false;
		for($i=0,$max_i=strlen($ls_sql); $i<=$max_i; $i++){
			$char = ($i==$max_i ? ' ' : substr($ls_sql, $i, 1));
			if($char == '('){ $itter_bracket++; }else if($char==')'){ $itter_bracket--; }
			
			if(in_array($char, array(' ','	',chr(10),chr(13)))){
				if($itter_after_reset == 0){ $word = $tmp; $arr_word = array_merge($arr_word, array($word)); }
				$itter_after_reset++; $tmp = '';
				$st_cutword = true;
			}else{
				$word = '';
				$tmp .= $char;
				$itter_after_reset = 0;
				$st_cutword = false;
			}
			
			
			if(strtoupper($word)=='SELECT' && $itter_bracket==0){ $keyword_position = 'SELECT'; }
			else if(strtoupper($word)=='FROM' && $itter_bracket==0){ $keyword_position = 'FROM'; }
			else if(strtoupper($word)=='WHERE' && $itter_bracket==0){ $keyword_position = 'WHERE'; }
			else if(strtoupper($word)=='BY' && $arr_word[count($arr_word)-2] == 'ORDER' && $itter_bracket==0){ $keyword_position = 'ORDER BY'; }
			
			
			if($keyword_position=='SELECT'){
				$arr_keyword_query['SELECT'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}else if($keyword_position=='FROM'){
				$arr_keyword_query['FROM'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}else if($keyword_position=='WHERE'){
				$arr_keyword_query['WHERE'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}else if($keyword_position=='ORDER BY'){
				$arr_keyword_query['ORDER BY'] .= (($st_cutword && $itter_after_reset<=1) || !$st_cutword ? $char : '');
			}
		}
		$arr_keyword_query['SELECT'] = trim($arr_keyword_query['SELECT']);
		$arr_keyword_query['FROM'] = trim($arr_keyword_query['FROM']);
		$arr_keyword_query['WHERE'] = trim($arr_keyword_query['WHERE']);
		$arr_keyword_query['ORDER BY'] = trim($arr_keyword_query['ORDER BY']);
		
		$arr_keyword_query['SELECT'] = trim(substr($arr_keyword_query['SELECT'],0,strlen($arr_keyword_query['SELECT'])-4));
		if($arr_keyword_query['WHERE'] != ''){
			$arr_keyword_query['FROM'] = trim(substr($arr_keyword_query['FROM'],0,strlen($arr_keyword_query['FROM'])-5));
		}
		if($arr_keyword_query['WHERE'] != '' && $arr_keyword_query['ORDER BY'] != ''){
			$arr_keyword_query['WHERE'] = trim(substr($arr_keyword_query['WHERE'],0,strlen($arr_keyword_query['WHERE'])-8));
		}
		
		// Get Necessary Variable For Paging
		
		$perpage	= isset($_POST['page']) ? intval($_POST['page']) : 1;
	
	$sql_total = ("SELECT COUNT(1) X FROM(".$ls_sql.") T");
echo '<br>';	echo $sql_total;	echo '<br>';
		$DB->parse($sql_total);
		$DB->execute();
		$row 			= $DB->nextrow();
		$total		= $row['X'];
		
		echo $total;
		$cur_page = (($_POST['page']> ceil($total/$perpage)) ? ceil($total/$perpage) : ($_POST['page'] < 1 ? 1 : $_POST['page']));
		if($cur_page < 1) $cur_page = 1;
		
		
		// Set Start & End Rows
		$ln_start	= (($cur_page - 1) * $perpage) + 1;
		$ln_end		= $ln_start + $perpage - 1;
		echo 'dddd';
		// Patching For oracle
		$arr_keyword_query['SELECT']	= (trim($arr_keyword_query['SELECT']) == '*' ? $arr_keyword_query['FROM'].'.' : '') . $arr_keyword_query['SELECT'];
		
		// Create New SQL with Paging
		$ls_sql_with_paging	= "	SELECT * 
								FROM (
										SELECT ".$arr_keyword_query['SELECT'].", ROW_NUMBER() OVER (ORDER BY ".$arr_keyword_query['ORDER BY'].") AS xrownum
										FROM ".$arr_keyword_query['FROM']." 
										".($arr_keyword_query['WHERE']!="" ? "WHERE ".$arr_keyword_query['WHERE'] : "").") tbl
								WHERE	xrownum BETWEEN ".$ln_start." AND ".$ln_end."
								ORDER BY xrownum";
								
		$DB->parse($ls_sql_with_paging);
		$DB->execute();
		$arr_data = array();
		while($row = $DB->nextrow()) {
			array_push($arr_data, $row);
		}
		
		// Create Array Response For Datagrid
		$arr_response = array(	'rows'=> $arr_data, 
								'total' => $total, 
								'page'=>$cur_page);
								
		return $arr_response;
	}
	

function exec_rpt($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null) {
	global $CONFIG;
	global $username;
	global $gs_sid;
	global $host;
	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220
	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");
	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	//$ls_link 	= "http://172.26.0.60/sijstk/includes/rptBPJS.php?url=";
	//$ls_link 	= "http://172.28.101.63:1802/smile/includes/rptBPJS.php?url=";
	// $ls_link 	= "http://172.28.108.111/smile/includes/rptBPJS.php?url=";
	// $ls_link 	= "http://172.28.108.111/smile/includes/rptBPJS.php?url=";
	$ls_link 	= $host."/includes/rptBPJS.php?url=";
	
	// $ls_link 	= "http://localhost/sijstk/includes/rptBPJS.php?url=";
	$ls_user 	= $CONFIG->REPORT_SERVER_USER_DB_1;
	$ls_pass	= $CONFIG->REPORT_SERVER_PASS_DB_1;
	$ls_sid     = $CONFIG->REPORT_SERVER_NAME_DB_1;
	$ls_path 	= $CONFIG->REPORT_SERVER_FOLDER_1;
	
	$ls_pdf 	= '1'; 
	$report["link"] 	= $ls_link;
	$report["user"] 	= $ls_user;
	$report["password"]	= $ls_pass;
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);
	//edited by zimmy/opa/dewa @kartika candra @22jun2015 - ubah pola report dari rwrun menjadi rwservlet
	//update by zimmy to encode url reports @home 23 april 2016 at 01.07 subuh
	
	// reconfig_global
	$ip_report = $CONFIG->REPORT_SERVER_IP;
	$user_db_report = $CONFIG->REPORT_SERVER_USER_DB_1;
	$pass_db_report = $CONFIG->REPORT_SERVER_PASS_DB_1;
	$name_db_report = $CONFIG->REPORT_SERVER_NAME_DB_1;
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

	$rwservlet = $ip_report.'/reports/rwservlet/setauth?button=Submit&username='
		.$user_db_report.'&password='
		.$pass_db_report.'&authtype=D&mask=GQ%253D%253D&isjsp=no&database='
		.$name_db_report.'&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIPT%2FKP%2FREPORT%26'.$ls_user_param;
	 $rwservlet = str_replace("'","",$rwservlet);
	 // echo $rwservlet;
	 $link = $report["link"].base64_encode($rwservlet);
	if($ls_convpdf == '1') {	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		echo $link;
	}
	//update 13-juni-2009,log untuk setiap pencetakan report
	if($DB!=null){
		$clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
		$sql = "insert into admin_inves.ms_audit_akses_report(userid,tgl_akses,kode_report,kode_role,kantor_id,nama_host,ip_client) ".
					 "values('".username."',sysdate,replace(upper('".$ls_nama_rpt."'),'.RDF',''),'".regrole."','".gs_kantor_aktif."','".@gethostbyaddr($clientIp)."','$clientIp') "; 
		 /*?><script language="JavaScript" type="text/javascript">alert('<?=addslashes($sql);?>');</script><?*/ 
		$DB->parse($sql);
		$DB->execute();
	}
	//end log	
}

function exec_rpt_xls($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null) {
	global $CONFIG;
	global $username;
	global $gs_sid;
	global $host;
	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220
	//concat userlogin||nama_report||timestamp
	$ld_timestamp = date("d-m-Y-H-i-s");
	$ls_namepdf = $username."".$ls_namepdf."".$ld_timestamp;
	$ls_pdf = $gs_path_pdf."".$ls_namepdf;
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	//$ls_link 	= "http://172.26.0.60/sijstk/includes/rptBPJS.php?url=";

	// reconfig_global
	$host_ip = $CONFIG->HOST_IP;
	// reconfig_global

	$ls_link 	= $host_ip."/smile/includes/rptBPJS.php?url=";
	
	// $ls_link 	= $host."/includes/rptBPJS.php?url=";

	// $ls_link 	= "http://localhost/sijstk/includes/rptBPJS.php?url=";
	$ls_user 	= $CONFIG->REPORT_SERVER_USER_DB_2;
	$ls_pass	= $CONFIG->REPORT_SERVER_PASS_DB_2;
	$ls_sid     = $CONFIG->REPORT_SERVER_NAME_DB_2;
	$ls_path 	= $CONFIG->REPORT_SERVER_FOLDER_2;
	
	$ls_pdf 	= '1';  
	$report["link"] 	= $ls_link;
	$report["user"] 	= $ls_user;
	$report["password"]	= $ls_pass;
	$report["sid"] 		= $ls_sid;
	$report["path"] 	= urlencode($ls_path);
	$report["file"] 	= $ls_nama_rpt;
	$report["param"] = str_replace(" ","%26",$ls_user_param);
	$report["param"] = str_replace("=","%3D",$report["param"]);
	//edited by zimmy/opa/dewa @kartika candra @22jun2015 - ubah pola report dari rwrun menjadi rwservlet
	//update by zimmy to encode url reports @home 23 april 2016 at 01.07 subuh
	// $rwservlet = 'http://172.28.108.151/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DENHANCEDSPREADSHEET%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIPT%2FKP%2FREPORT%26'.$ls_user_param;
	
	// reconfig_global
	$ip_report = $GLOBAL->REPORT_SERVER_IP;
	$user_db_report = $GLOBAL->REPORT_SERVER_USER_DB_2;
	$pass_db_report = $GLOBAL->REPORT_SERVER_PASS_DB_2;
	$name_db_report = $GLOBAL->REPORT_SERVER_NAME_DB_2;
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

	$rwservlet = $ip_report.'/reports/rwservlet/setauth?button=Submit&username='
		.$user_db_report.'&password='
		.$pass_db_report.'&authtype=D&mask=GQ%253D%253D&isjsp=no&database='
		.$name_db_report.'&nextpage=destype%3Dcache%26desname%3D'.$ls_nama_rpt.'.xls%26desformat%3DENHANCEDSPREADSHEET%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIPT%2FKP%2FREPORT%26'.$ls_user_param;
	
	$rwservlet = str_replace("'","",$rwservlet);
	 // echo $rwservlet;
	 $link = $report["link"].base64_encode($rwservlet);
	if($ls_convpdf == '1') {	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		echo $link;
	}
	//update 13-juni-2009,log untuk setiap pencetakan report
	if($DB!=null){
		$clientIp = ( !empty($HTTP_SERVER_VARS['REMOTE_ADDR']) ) ? $HTTP_SERVER_VARS['REMOTE_ADDR'] : ( ( !empty($HTTP_ENV_VARS['REMOTE_ADDR']) ) ? $HTTP_ENV_VARS['REMOTE_ADDR'] : getenv('REMOTE_ADDR') );
		$sql = "insert into admin_inves.ms_audit_akses_report(userid,tgl_akses,kode_report,kode_role,kantor_id,nama_host,ip_client) ".
					 "values('".username."',sysdate,replace(upper('".$ls_nama_rpt."'),'.RDF',''),'".regrole."','".gs_kantor_aktif."','".@gethostbyaddr($clientIp)."','$clientIp') "; 
		 /*?><script language="JavaScript" type="text/javascript">alert('<?=addslashes($sql);?>');</script><?*/ 
		$DB->parse($sql);
		$DB->execute();
	}
	//end log	
}
	
?>
