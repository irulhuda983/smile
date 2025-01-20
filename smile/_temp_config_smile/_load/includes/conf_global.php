<? // config.php
$HTTP_HOST		= "172.28.208.61/smile";
// $HTTP_HOST		= "172.28.208.61/smile";
//$HTTP_HOST		= "172.28.200.15:1802/smile";
$admin_email	= "smile-care@bpjsketenagakerjaan.go.id";
$copyright		= "2019 PTI BPJS Ketenagakerjaan, All Rights Reserved.";

//PROD
/*
$gs_DBUser  	= "core_sijstk";
$gs_DBPass  	= "banding2016";
$gs_DBName	= "172.28.202.62:1522/sijstkoltp";
*/


//DEV
$ip_rdf = 'reptest.bpjsketenagakerjaan.go.id';
//$ip_svr = '172.26.0.60';
$ip_svr = 'reptest.bpjsketenagakerjaan.go.id';

// $ip_rdf ="172.28.201.109";
// $ip_svr ="172.28.201.109";

//$gs_DBUser  	= "sijstk";
//$gs_DBPass  	= "welcome1";
//$gs_DBName	= "172.28.202.70:1521/dbdevelop";

$gs_DBUser  	= "sijstk";
$gs_DBPass  	= "jmo1";
$gs_DBName	= "172.28.208.80:1521/dboltp";

// $gs_DBUser  		= "core_sijstk";
// $gs_DBPass 		= "banding2016";
// $gs_DBName		= "172.28.202.62:1522/sijstkoltp";

$olap_DBUser = "CLEARANCE";
$olap_DBPass = "WELCOME1";
$olap_DBName = "172.28.202.63:1521/SIJSTKOLAP";

$chId	= 'CORE';
$payment["kode"] = '000';
$payment["user"] = 'core';
$payment["pass"] = 'core#bpjs2015';

$echa["user"] = 'EC';
$echa["pass"] = 'jmo2';
$echa["db"] = '172.28.208.80:1521/sijstkecha';

//OSS
$oss_DBUser  	= "WWW";
$oss_DBPass  	= "jmo2";
$oss_DBName	= "172.28.208.80:1521/sijstkecha";
//OSS

$eimadmin_DBUser = "EIMADMIN";
$eimadmin_DBPass = "care41Eim";
$eimadmin_DBName = "172.28.202.61:1521/sijstkcrm";

//SPO DEV
$spo_DBUser  	= "NSP";
$spo_DBPass  	= "jmo2";
$spo_DBName		= "172.28.208.80:1521/sijstkecha";
//$spo_DBName		= "172.26.0.40:1521/dbsipa";

//ECHANNEL
$EC_DBUser = "EC";
$EC_DBPass = "jmo2";
$EC_DBName = "172.28.208.80:1521/sijstkecha";

$SPO_DBUser1  	= "SPO";
$SPO_DBPass1  	= "spo";
// $SPO_DBName1	= "172.26.0.40:1521/DBSIPA";
$SPO_DBName1	= "172.28.208.12:1521/DBSIPA";


//PROD UNTUK FUNCTION CEK KEPESERTAAN
$gs_DBUser_prod  	= "core_sijstk";
$gs_DBPass_prod 	= "banding2016";
$gs_DBName_prod		= "172.28.202.163:1535/sijstkoltp";

//INVENTARIS PIUTANG
$pi_DBUser  	= "MGSIJSTK";
$pi_DBPass  	= "mgsijstkjam19";
$pi_DBName		= "172.28.202.63:1521/sijstkolap";

//SERVER REPORT SIJSTK NEW CORE
//$nc_rpt_user 	= 'ncreport';
//$nc_rpt_pass	= 'nc54321';
$nc_rpt_user 	= 'sijstk';
// $nc_rpt_pass	= 'welcome1';
$nc_rpt_pass	= 'jmo1';
$nc_rpt_sid		= 'dboltp';
// $nc_rpt_sid		= 'dbdevelop';
$nc_rpt_path 	= '/data/reports/';
$nc_rpt_link	= 'http://'.$HTTP_HOST.'/urldest.php?url=';


//SERVER REPORT GENERATE ARSIP DIGITAL
$nc_rpt_user_arsip	= 'sijstk';
// $nc_rpt_pass	= 'welcome1';
$nc_rpt_pass_arsip	= 'jmo2';



//SERVER REPORT OSS
//$nc_rpt_user 	= 'ncreport';
//$nc_rpt_pass	= 'nc54321';
$oss_rpt_user 	= 'WWW';
$oss_rpt_pass	= 'jmo2';
$oss_rpt_sid	= 'sijstkecha';
$oss_rpt_path 	= '/data/reports/';
$oss_rpt_link	= 'http://'.$HTTP_HOST.'/urldest.php?url=';

//SIPP
$sipp_DBUser  	= "NSP";
$sipp_DBPass  	= "jmo2";
$sipp_DBName	= "172.28.208.80:1521/sijstkecha";

//kpi production
$kpi_prod_DBUser = "kpi";
$kpi_prod_DBPass = "ipodtouch";
$kpi_prod_DBName = "172.28.208.80:1521/dboltp";

//MLT
$mlt_DBUser  	= "MLT";
$mlt_DBPass  	= "MLT";
$mlt_DBName     = "172.28.208.80:1521/dboltp";

//VOKASI
$vks_DBUser  	= "WWW";
$vks_DBPass  	= "jmo2";
$vks_DBName	= "172.28.208.80:1521/sijstkecha";

//KPI INDIVIDU
$kpi_indi_DBUser	= "KPI_INDI";
$kpi_indi_DBPass	= "KPI_INDI_042020";
$kpi_indi_DBName	= "172.28.208.80:1521/dboltp";

//SKP Online
$dpkp_DBUser  	= "admin_dpkp";
$dpkp_DBPass  	= "logintoadcapture";
$dpkp_DBName	= "172.28.208.80:1521/dboltp";

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl', '0');
//$display_errors = 1;
//ini_set('display_errors', $display_errors);
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";

//$wsIp = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
$wsIp = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
$wsIpDownload = 'http://wstest.bpjsketenagakerjaan.go.id';
$wsPortDownload = '2014';
//$wsIpStorage = 'http://172.28.200.33:8081';
$wsIpStorage = 'http://ceph-storage-api-ceph-dev.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$wsIpDocument = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
// $wsIp = 'http://wstest.bpjsketenagakerjaan.go.id:2014';
// $wsIp = 'http://wsaxis.bpjsketenagakerjaan.go.id:2014';

//$wsIp = 'http://172.28.201.202:2024';
$wsIpMergeDokumen='172.28.108.34';
$wsPortMergeDokumen = '5001';

//$wsIpSidia='http://sidia.bpjsketenagakerjaan.go.id/services';
// $wsIpSidia='http://172.28.108.78:4025';
$wsIpSidia='https://sidia-testing.bpjsketenagakerjaan.go.id';

/*API / BACKEND NEW EPROCURE*/
$wsEproc='http://172.28.108.97:2200';

/* jmo prod api-mobileapps-notifications */
//$wsIpJMO = 'http://172.28.200.253:4033';
$wsIpJMO = 'http://jmo-log-restful-api-appd-jmo-dev.apps.ocp-drc.bpjsketenagakerjaan.go.id';

/* cam apps jmo */
//$wsIpJMOSmile='http://172.28.200.253:4041';
$wsIpJMOSmile='http://ekyc-restful-api-jmo.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$appsCam = 'https://cam-dev.bpjsketenagakerjaan.go.id';

/* ip BPU */
$wsIpBPU = 'http://wstest.bpjsketenagakerjaan.go.id:2014';

//WSDL List
//================
define("WSPayment", $wsIp."/WSPayment/services/Main?wsdl");
define("WSPaymentEP", $wsIp."/WSPayment/services/Main.MainHttpSoap11Endpoint/");

define("WSDaftar", $wsIp."/WSDaftar/services/Main?wsdl");
define("WSDaftarEP", $wsIp."/WSDaftar/services/Main.MainHttpSoap11Endpoint/");

define("WSInvoice", $wsIp."/WSInvoice/services/Main?wsdl");
define("WSInvoiceEP", $wsIp."/WSInvoice/services/Main.MainHttpSoap11Endpoint/");

//Tambahan - Untuk remove kode iuran - AD171840
define("WSInvoice2", $wsIp."/WSInvoice2/services/Main?wsdl");
define("WSInvoice2EP", $wsIp."/WSInvoice2/services/Main.MainHttpSoap11Endpoint/");

define("WSInvoice3", $wsIp."/WSInvoice3/services/Main?wsdl");
define("WSInvoice3EP", $wsIp."/WSInvoice3/services/Main.MainHttpSoap11Endpoint/");

define("WSMobileApps", $wsIp."/WSMobileApps/services/Main?wsdl");
define("WSMobileAppsEP", $wsIp."/WSMobileApps/services/Main.MainHttpSoap11Endpoint/");

define("WSDaftar", $wsIp."/WSDaftar/services/Main?wsdl");
define("WSDaftarEP", $wsIp."/WSDaftar/services/Main.MainHttpSoap11Endpoint/");

define("WSSIPT", $wsIp."/WSSIPT/services/Main?wsdl");
define("WSSIPTEP", $wsIp."/WSSIPT/services/Main.MainHttpSoap11Endpoint/");

//define("WSEKTP", "http://172.28.201.78:2014/WSEKTP/services/Main?wsdl");
define("WSEKTP", $wsIp."/WSEKTP/services/Main?wsdl");
define("WSEKTPEP", $wsIp."/WSEKTP/services/Main.MainHttpSoap11Endpoint/");

define("WSCOM", $wsIp."/WSCom/services/Main?wsdl");
define("WSCOMSMAIL", $wsIp."/WSCom/services/Main.MainHttpSoap11Endpoint/");

/*path file upload*/
/*$smile_upd_dir = "/www/smile/external/smile"; /*path smile development*/
$smile_upd_dir = "/www/smile/external/smile";
$smile_upd_dir_date = date("Y") . date("m");
$smile_upd_dir_full = $smile_upd_dir;

//LINK DOWNLOAD DOKUMEN ANTRIAN
$wsIpDokumenAntrian='http://172.28.108.176:4024/upload/getFileSmile?data=';
$wsIpDokumenDownload='http://172.28.108.176:4024';
$wsIpLapakAsikOnsite = 'http://172.28.108.176:4023';
$wsIpLapakAsikOnsiteDomain = 'https://lapakasik-onsite-testing.bpjsketenagakerjaan.go.id';
$wsIpLapakAsikOnline='http://172.28.108.176:4024';

$wsPushNotifikasi='172.28.200.253:4024/push-notification';
$authNotifikasi='key=AAAAj2HGYgc:APA91bHGo3yTkR0hf5MBjOdtmYifsDW4a3Xua45s6L1NLtaQ1oZ2kjuH5TRC7R2ur8I_HwOZxYxCSifEAEFDxcQPZ8imDI59ECsiG1Q0kj5VLoEeC0nbGf-k6x3OM2Gh9QIvwamA_Wh2';

//ws investasi
$wsInvest = 'http://172.28.200.33:4020'; // alamat ws smile-investment
$wsInvestWithEncryption = true;

$antrian_rpt_user 	= 'nsp';
$antrian_rpt_pass	= 'jmo2';
$antrian_rpt_sid	= 'sijstkecha';
$antrian_rpt_path 	= '/data/reports/';
$antrian_rpt_link	= 'http://'.$HTTP_HOST.'/urldest.php?url=';

$wsIpArsip = 'https://sidia-testing.bpjsketenagakerjaan.go.id';

//generate arsip
$nc_rpt_user_arsip 	= 'sijstk';
$nc_rpt_pass_arsip	= 'jmo1';

$ipReportServer='http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet';
// 05102021, untuk kebutuhan pemisahan WS ke pembentukan dokumen digital
$ipReportServerDocument='http://reptest.bpjsketenagakerjaan.go.id';

//upload smile-api
$wsFileSmile="http://172.28.100.201:3011";

//ws siapkerja JKP prod
//$wsFileSiapKerja="http://172.28.200.76:3012";
$wsFileSiapKerja="http://172.28.108.176:3012";
$wsFileSiapKerjaIp="http://172.28.108.176:3012";

//Microservices API
//====================
$api_services_addr='http://172.28.101.106';

?>
