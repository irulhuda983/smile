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
	  win=window.open(mypage,myname,settings);
	  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
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

<? 
include_once "conf_global.php";

/* these variables come from conf_global.php */

$gs_sid = $gs_DBUser."/".$gs_DBPass."@".$gs_DBName;

function exec_rpt44($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param)
{

	global $username;
	global $gs_sid;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220
	

	/****************************************************/
	// These are path for development under LINUX' by Zimmy
	/****************************************************/
	$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/webapps/dpkppkblonline/dpkp/rdf/';
	$gs_path_pdf = '/webapps/dpkppkblonline/dpkp/pdf/';

	
/*
//ditutup dl untuk windows
$gs_executable = "D:\ORACLE\DevSuiteHome\bin";
	
	if($ls_convpdf == '1')
		$gs_path_frs = $gs_executable."\\rwrun.exe destype=file desformat=pdf PRINTJOB=NO";
	else
		$gs_path_frs = $gs_executable."\\rwrun.exe";
*/

	/****************************************************/
	/* Path untuk file executable:						*/
	/* Ezron: D:\ORACLE\ORADEV6i\BIN					*/
	/* Budi : C:\ORANT\BIN								*/
	/*													*/
	/****************************************************/

	//$gs_path_report = 'D:\Reactor\Core\htdocs\dpkp\src_report\\';
	//$gs_path_report = 'D:\Reactor\Reactor\Core\htdocs\dpkppkblonline\dpkp\rdf\\';
	//$gs_path_pdf = 'D:\Reactor\Reactor\Core\htdocs\dpkppkblonline\dpkp\pdf/';
	
	/****************************************************/
	/* Path untuk file PDF dan REP:						*/
	/* Ezron: F:\WEBAPP\htdocs\dpkppkblonline				*/
	/* Budi : C:\Reactor\Core\htdocs\dpkppkblonline2		*/
	/*													*/
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

//    file_put_contents("mtq.log",$pkp_exec);
	//shell_exec($pkp_exec);
	//exec($pkp_exec);
	
	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">
			NewWindow('../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
	}
}

//fungsi baru untuk menjalankan rwservlet di dpkppkblonline
//update @18jan2018 by zimmy
function exec_rpt($paramform="no",$ls_namepdf,$ls_nama_rpt,$ls_user_param, $DB=null)
{
	global $username;
	global $gs_sid;
	global $ipReportServer;

	$ls_convpdf = '1'; //apakah report dikonversi ke pdf atau tidak. 1=konversi, 0=tidak konversi --by ezron-20080220

	
	// These are path for development under LINUX' environment in D'Best Office
	/*$gs_path_frs = '/oracle/app/product/as10g/FRS/bin/rwrun.sh  destype=file desformat=pdf';
	$gs_path_report = '/opt/xampp/htdocs/core123/rdf/';
	$gs_path_pdf = '/opt/xampp/htdocs/core123/pdf/';
	
*/
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
	/*$pkp_exec = $gs_path_frs." userid='".$gs_sid."'";
	$pkp_exec .= " paramform='".$paramform."'";
	$pkp_exec .= " desname='".$ls_pdf.".pdf'";
	$pkp_exec .= " report='".$gs_path_report.$ls_nama_rpt."'";
	$pkp_exec .= " ".$ls_user_param;
	*/
	//echo $pkp_exec;
	//shell_exec($pkp_exec);
	//echo $ls_user_param."<BR>";
	$ls_user_param = str_replace("%","%2525",$ls_user_param);
	$ls_user_param = str_replace(" ","%26",$ls_user_param);
	$ls_user_param = str_replace("=","%3D",$ls_user_param);
	
	// $ls_user_param = urlencode($ls_user_param);
	
	$ls_link 	= "http://172.28.108.49:5000/smile/dpkppkblonline/dpkp/includes/rptBPJS.php?url=";
	$ls_user 	= "sijstk";
	$ls_pass	= "welcome1";
	$ls_sid     = "dbdevelop";
	$ls_path 	= "/data/reports/dpkp";

	$ls_pdf = '1'; 

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
	//$rwservlet = 'http://172.28.201.101:7779/reports/rwservlet/setauth?button=Submit&username=core_sijstk&password=banding2016&authtype=D&mask=GQ%253D%253D&isjsp=no&database=sijstkoltp&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D'.$ls_nama_rpt.'%26userid%3D%2Fdata%2Fjms%2FSIAK%2FGL%2FREPORT%26'.$ls_user_param;
	$rwservlet = "{$ipReportServer}/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$ls_nama_rpt.'%26userid%3D%2Fdata%2Freports%2Fdpkp%26'.$ls_user_param;
	
	$rwservlet = str_replace("'","",$rwservlet);
	$rwservlet = str_replace("%%","%",$rwservlet);
	$link = $report["link"].base64_encode($rwservlet);
	 
	  //echo $linkss = $report["link"].("http://172.28.201.101:7779/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	
	  //$link = $report["link"].base64_encode("http://172.28.201.101:7779/reports/rwservlet/setauth?button=Submit&username=".$report["user"]."&password=".$report["password"]."&authtype=D&mask=GQ%253D%253D&isjsp=no&database=".$report["sid"]."&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3D".$report["file"]."%26userid%3D".$report["path"]."%26".$report["param"]);
	
	
	if($ls_convpdf == '1')
	{	//kalau konversi ke pdf, hasil pdfnya akan dibuka dengan window baru
	
		?>
		<!-- setelah proses eksekusi selesai buka pdf report-->
		<script language="JavaScript" type="text/javascript">		
		
			//NewWindow('<?=$rwservlet?>','',800,600,1)		
			NewWindow('<?=$link?>','',800,600,1)		
			//NewWindow('../../pdf/<?=$ls_namepdf.".pdf"?>','',800,600,1)			
			//window.location.replace('../pdf/<?=$ls_namepdf.".pdf"?>');
		</script>
		<?
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
