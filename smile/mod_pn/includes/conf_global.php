<?php

  class ConfigGlobal {
	  
    // includes/balau.php
    public $REPORT_SERVER_IP = "http://172.28.108.151";
    public $REPORT_SERVER_IP_DRC = "http://172.28.108.151";
    public $REPORT_SERVER_USER_DB_1 = "sijstk";
    public $REPORT_SERVER_PASS_DB_1 = "welcome1";
    public $REPORT_SERVER_NAME_DB_1 = "dbdevelop";
    //public $REPORT_SERVER_FOLDER_1 = "/data/jms/SIPT/KP/REPORT";
    public $REPORT_SERVER_FOLDER_1 = "/data/reports/";

    public $REPORT_SERVER_USER_DB_2 = "sijstk";
    public $REPORT_SERVER_PASS_DB_2 = "welcome1";
    public $REPORT_SERVER_NAME_DB_2 = "dbdevelop";
    public $REPORT_SERVER_FOLDER_2 = "/data/reports/";

    public $HOST_IP = "http://172.28.108.49:5000"; /*rubah dengan domain smile untuk prod*/

    // includes/conf_global.php
    // SKIP

    // includes/connsql.php
    public $HOST_PATH = "http://172.28.108.49:5000/smile"; /*rubah dengan domain smile untuk prod*/
    public $HOST_PATH_CORE = "http://172.28.108.49:5000/smile"; /*rubah dengan domain smile untuk prod*/
    public $CH_ID_SMILE = "SMILE";

    public $DB_USER_1 = "sijstk";
    public $DB_PASS_1 = "welcome1";
    public $DB_URI_1  = "";

    public $WS_IP = "http://172.28.108.201:2024";
    public $WS_IP_USER = "http://172.28.108.201:2024";
    //public $WS_IP_USER = "http://172.28.108.201:2024";

    // includes/fungsi.php
    public $WS_COM_PATH = "http://172.28.108.201:2024/WSCom/services/Main?wsdl";

    // includes/fungsi_rpt.php
    public $REPORT_SERVER_HOST_PATH = "http://172.28.108.49:5000/smile/includes/rptBPJS.php?url="; /*rubah dengan domain smile untuk prod*/

    public $REPORT_SERVER_USER_DB_13 = "sijstk";
    public $REPORT_SERVER_PASS_DB_13 = "welcome1";
    public $REPORT_SERVER_NAME_DB_13 = "dbdevelop";

    // includes/fungsi_rpt_skp.php
    public $REPORT_SERVER_USER_DB_14 = "sijstk";
    public $REPORT_SERVER_PASS_DB_14 = "welcome1";
    public $REPORT_SERVER_NAME_DB_14 = "dbdevelop";

    public $REPORT_SERVER_USER_DB_15 = "sijstk";
    public $REPORT_SERVER_PASS_DB_15 = "welcome1";
    public $REPORT_SERVER_NAME_DB_15 = "dbdevelop";

    public $REPORT_SERVER_USER_DB_16 = "sijstk";
    public $REPORT_SERVER_PASS_DB_16 = "welcome1";
    public $REPORT_SERVER_NAME_DB_16 = "dbdevelop";

    // mod_dpkp/dpkppkblonline/dpkp/includes/conf_global.php
    public $DPKP_UID = "JH@GAOUBXD";
    public $DPKP_PWD = "KE_E?>>#";
    public $DPKP_SDB = "vka}k";

    public $DPKP_USER_DB_1 = "admin_dpkp";
    public $DPKP_PASS_DB_1 = "logintoadcapture";
    public $DPKP_NAME_DB_1 = "";

    public $DPKP_USER_DB_2 = "admin_dpkp";
    public $DPKP_PASS_DB_2 = "logintoadcapture";
    public $DPKP_NAME_DB_2 = "";

    // mod_dpkp/dpkppkblonline/dpkp/includes/fungsi_rpt.php
    public $REPORT_SERVER_HOST_DPKP_PATH = "http://172.28.108.49:5000/smile/dpkppkblonline/dpkp/includes/rptBPJS.php?url="; /*prod ganti dengan domain*/

    public $REPORT_SERVER_USER_DB_17 = "sijstk";
    public $REPORT_SERVER_PASS_DB_17 = "welcome1";
    public $REPORT_SERVER_NAME_DB_17 = "dbdevelop";
    public $REPORT_SERVER_FOLDER_17 = "/data/reports/dpkp";

    // mod_dpkp/dpkppkblonline/includes/a.php
    public $DPKP_MYSTIQUE = "BOTI2013";

    // mod_dpkp/dpkppkblonline/includes/conf_global.php
    // SKIP

    // mod_dpkp/dpkppkblonline/pkbl/includes/a.php
    // SKIP
    
    // mod_dpkp/dpkppkblonline/pkbl/includes/conf_global.php
    public $REPORT_SERVER_USER_DB_18 = "sijstk";
    public $REPORT_SERVER_PASS_DB_18 = "welcome1";
    public $REPORT_SERVER_NAME_DB_18 = "dbdevelop";
    public $REPORT_SERVER_FOLDER_18 = "/data/reports/dpkp";

    // mod_dpkp/dpkppkblonline/pkbl/includes/fungsi_rpt.php
    // SKIP

    // mod_sipa/alur_penyusunan_anggaran/balau.php
    public $REPORT_SERVER_USER_DB_19 = "sijstk";
    public $REPORT_SERVER_PASS_DB_19 = "welcome1";
    public $REPORT_SERVER_NAME_DB_19 = "dbdevelop";
    public $REPORT_SERVER_FOLDER_19 = "/data/reports";

    // mod_sipa/alur_penyusunan_anggaran/fungsi_rpt_bg.php
    public $REPORT_SERVER_USER_DB_20 = "sijstk";
    public $REPORT_SERVER_PASS_DB_20 = "welcome1";
    public $REPORT_SERVER_NAME_DB_20 = "dbdevelop";
    public $REPORT_SERVER_FOLDER_20 = "/data/reports";

    // mod_sipa/alur_penyusunan_anggaran/fungsi_rpt.php
    public $REPORT_SERVER_USER_DB_21 = "sijstk";
    public $REPORT_SERVER_PASS_DB_21 = "welcome1";
    public $REPORT_SERVER_NAME_DB_21 = "dbdevelop";
    public $REPORT_SERVER_FOLDER_21 = "/data/reports";

    // mod_va/includes/conn.php
    public $WS_VIRACCT = "http://172.28.108.201:2024/WSVirAcct/services/Main?wsdl";
    
	  public $ip;
    public $port_oltp;
    public $port_echa;
  	function __construct($server_smile=""){
    	//KONFIGURASI PORT DATABASE
    	$port_oltp;
    	$port_echa;
    	$this->ip = substr($server_smile,11,12);
    	
    	/*port dboltp*/
        if ($this->ip =='1') {
            $port_oltp = '1523';
            $port_echa = '1523';
        }
        elseif ($this->ip =='2') {
            # code...
            $port_oltp = '1523';
            $port_echa = '1523';
        }
        elseif ($this->ip =='3') {
            # code...
            $port_oltp = '1524';
            $port_echa = '1524';
        }
        elseif ($this->ip =='4' ) {
            # code...
            $port_oltp = '1525';
            $port_echa = '1525';
        }
        elseif ($this->ip =='5') {
            # code...
            $port_oltp = '1528';
            $port_echa = '1528';
        }
        elseif ($this->ip =='6') {
            # code...
            $port_oltp = '1529';
            $port_echa = '1529';
        }
        elseif ($this->ip =='7') {
            # code...
            $port_oltp = '1535';
            $port_echa = '1535';
        }
        elseif ($this->ip =='8') {
            # code...
            $port_oltp = '1530';
            $port_echa = '1530';
        }
        elseif ($this->ip =='9') {
            # code...
            $port_oltp = '1531';
            $port_echa = '1531';
        }
        elseif ($this->ip =='10') {
            # code...
            $port_oltp = '1532';
            $port_echa = '1532';
        }
        elseif ($this->ip =='11') {
            # code...
            $port_oltp = '1533';
            $port_echa = '1533';
        }
        elseif ($this->ip =='12') {
            # code...
            $port_oltp = '1534';
            $port_echa = '1534';
        }
        elseif ($this->ip =='13') {
            # code...
            $port_oltp = '1536';
            $port_echa = '1536';
        }
        else
        {
            $port_oltp = '1537';
            $port_echa = '1537';
        }

        // harcoded untuk development
        $port_oltp = '1521';
        $port_echa = '1521';
        // harcoded untuk development

    	$this->port_oltp = $port_oltp;
    	$this->port_echa = $port_echa;
    	$this->DB_URI_1  = "172.28.108.180:{$port_oltp}/dbdevelop";
    	$this->DPKP_NAME_DB_2 = "172.28.108.180:{$port_oltp}/dbdevelop";
    	$this->DPKP_NAME_DB_1 = "172.28.108.180:{$port_oltp}/dbdevelop";
  	}	
  }
  $CONFIG = new ConfigGlobal($_SERVER['SERVER_ADDR']);

  $CONFIG_GLOBAL["REPORTCLEARANCE"] = "http://172.28.108.151";
  $CONFIG_GLOBAL["WS_JSOPG"] = "http://172.28.108.201:2024";
  $CONFIG_GLOBAL["WS_TEST"] = "http://ws-dev.bpjstk.id:2024";
  $CONFIG_GLOBAL["PERISAI"] = "https://perisai-dev.bpjs.go.id";
  $CONFIG_GLOBAL["LOCALHOST"] = "http://172.28.108.49:5000"; /*rubah dengan domain unutk PROD*/
  $CONFIG_GLOBAL["JSCORE"] = "";
  $CONFIG_GLOBAL["WS_LAPAKASIK"] = "http://172.28.100.201:8022";
  $CONFIG_GLOBAL["SRC_TEST"] = "http://ws-dev.bpjstk.id";
  $CONFIG_GLOBAL["DB_SIJSTKOLAP"] = "172.28.136.51:1526/SIJSTKOLAP";
  $CONFIG_GLOBAL["RPT_PIUTANG"] = "http://172.28.108.151";
  $CONFIG_GLOBAL["LOCALHOST_8080"] = "http://localhost:8080";
  $CONFIG_GLOBAL["WSCOM"] = "http://172.28.108.201:2024";
  $CONFIG_GLOBAL["SSO_APP"] = "https://sso-dev.bpjsketenagakerjaan.go.id";
  $CONFIG_GLOBAL["IP_RPT_DEV"] = "http://smile-dev.bpjstk.id";
  $CONFIG_GLOBAL["WS_NEP"] = "http://ws-nep-dev.bpjstk.id:2200";
  $CONFIG_GLOBAL["WS_IPSTORAGE"] = "http://ceph-storage-api-ceph-dev.apps.ocp-dc.bpjsketenagakerjaan.go.id";
  $CONFIG_GLOBAL["WS_VOKASI"] = "";
  $CONFIG_GLOBAL["IP_APPS70"] = "http://172.28.108.151";
  $CONFIG_GLOBAL["WS_TEST2"] = "http://172.28.101.52:2014";
  $CONFIG_GLOBAL["WS_TEST1"] = "http://172.28.101.51:2014";
  $CONFIG_GLOBAL["WS_LPK"] = "https://lpk-dev.bpjsketenagakerjaan.go.id";
  $CONFIG_GLOBAL["IP_APPS157"] = "http://repdev1.bpjstk.id";
  $CONFIG_GLOBAL["IP_APPS101"] = "http://172.28.201.101";
  $CONFIG_GLOBAL["DB_SIJSTKECHA"] = "172.28.108.180:1521";
  $CONFIG_GLOBAL["WS_DOCUMENT"] = "http://172.28.108.201:2024";
  $CONFIG_GLOBAL["WS_GOOGLEMAPS"] = "";
  $CONFIG_GLOBAL["WS_CEPHSTORAGE"] = "http://ceph-storage-api-ceph-dev.apps.ocp-dc.bpjsketenagakerjaan.go.id";
  $CONFIG_GLOBAL["WS_REPORT_EC"] = "http://172.28.108.151";
  $CONFIG_GLOBAL["LOCALHOST_PLAIN"] = "http://localhost";
  $CONFIG_GLOBAL["IP_APPS60"] = "http://172.28.108.49:5000";
  $CONFIG_GLOBAL["IP_APPS62"] = "http://172.28.108.49:5000";
  $CONFIG_GLOBAL["WS_PMI"] = "https://unitlayananpmi-dev.bpjsketenagakerjaan.go.id";
  $CONFIG_GLOBAL["IP_APPS_EKLAIM"] = "https://eklaim-dev.bpjsketenagakerjaan.go.id/";
  $CONFIG_GLOBAL["IP_APPS95"] = "http://172.28.101.95:1003";
  $CONFIG_GLOBAL["WS_AXIS"] = "http://172.28.108.201:2024";
  $CONFIG_GLOBAL["IP_APP80"] = "http://172.28.201.80:56001";

// config.php
$HTTP_HOST    = "172.28.108.49:5000/smile";
$admin_email  = "smile-care@bpjsketenagakerjaan.go.id";
$copyright    = "2019 PTI BPJS Ketenagakerjaan, All Rights Reserved.";


//PROD
/*
$gs_DBUser    = "core_sijstk";
$gs_DBPass    = "banding2016";
$gs_DBName  = "172.28.202.62:1522/sijstkoltp";
*/


//DEV
$ip_rdf = '172.28.101.41';
//$ip_svr = '172.26.0.60';
$ip_svr = '172.28.101.41';

// $ip_rdf ="172.28.201.109";
// $ip_svr ="172.28.201.109";

//$gs_DBUser    = "sijstk";
//$gs_DBPass    = "welcome1";
//$gs_DBName  = "172.28.202.70:1521/dbdevelop";

$gs_DBUser    = "sijstk";
$gs_DBPass    = "welcome1";
//$gs_DBName  = "172.28.202.163:{$port_oltp}/dboltp";
$gs_DBName  = "172.28.108.180:{$CONFIG->port_oltp}/dbdevelop";

// $gs_DBUser     = "core_sijstk";
// $gs_DBPass     = "banding2016";
// $gs_DBName   = "172.28.202.62:1522/sijstkoltp";

$olap_DBUser = "CLEARANCE";
$olap_DBPass = "welcome1";
$olap_DBName = "172.28.136.51:1526/SIJSTKOLAP";

$chId = 'CORE';
$payment["kode"] = '000';
$payment["user"] = 'core';
$payment["pass"] = 'welcome1';

$echa["user"] = 'spo';
$echa["pass"] = 'welcome1';
$echa["db"] = "172.28.108.180:{$CONFIG->port_echa}/ECHADEV";

//OSS
$oss_DBUser   = "WWW";
$oss_DBPass   = "welcome1";
$oss_DBName = "172.28.108.180:{$CONFIG->port_echa}/ECHADEV";
//OSS

$eimadmin_DBUser = "EIMADMIN";
$eimadmin_DBPass = "welcome1";
$eimadmin_DBName = "172.28.202.61:1521/sijstkcrm";

//SPO DEV
$spo_DBUser   = "SPO";
$spo_DBPass   = "spo";
$spo_DBName   = "172.28.108.180:{$CONFIG->port_echa}/ECHADEV";
//$spo_DBName   = "172.26.0.40:1521/dbsipa";

//ECHANNEL
$EC_DBUser = "EC";
$EC_DBPass = "welcome1";
$EC_DBName = "172.28.108.180:{$CONFIG->port_echa}/ECHADEV";

$SPO_DBUser1    = "SPO";
$SPO_DBPass1    = "spo";
// $SPO_DBName1 = "172.26.0.40:1521/DBSIPA";
$SPO_DBName1  = "172.28.208.12:1521/DBSIPA";


//PROD UNTUK FUNCTION CEK KEPESERTAAN
$gs_DBUser_prod   = "core_sijstk";
$gs_DBPass_prod   = "banding2016";
$gs_DBName_prod   = "172.28.108.180:{$CONFIG->port_oltp}/sijstkoltp";

//INVENTARIS PIUTANG
$pi_DBUser    = "MGSIJSTK";
$pi_DBPass    = "mgsijstkjam19";
$pi_DBName    = "172.28.136.51:1526/SIJSTKOLAP";

//SERVER REPORT SIJSTK NEW CORE
//$nc_rpt_user  = 'ncreport';
//$nc_rpt_pass  = 'nc54321';
$nc_rpt_user  = 'sijstk';
// $nc_rpt_pass = '';
//$nc_rpt_pass  = '';
$nc_rpt_pass  = 'welcome1';
$nc_rpt_sid   = 'dbdevelop';
// $nc_rpt_sid    = 'dbdevelop';
$nc_rpt_path  = '/data/reports/';
$nc_rpt_link  = 'http://'.$HTTP_HOST.'/urldest.php?url=';

//SERVER REPORT GENERATE ARSIP DIGITAL
$nc_rpt_user_arsip  = 'sijstk';
// $nc_rpt_pass = '';
$nc_rpt_pass_arsip  = 'welcome1';

//SERVER REPORT OSS
//$nc_rpt_user  = 'ncreport';
//$nc_rpt_pass  = 'nc54321';
$oss_rpt_user   = 'www';
$oss_rpt_pass = 'welcome1';
$oss_rpt_sid  = 'echadev';
$oss_rpt_path   = '/data/reports/';
$oss_rpt_link = 'http://'.$HTTP_HOST.'/urldest.php?url=';

//SIPP
$sipp_DBUser    = "NSP";
$sipp_DBPass    = "welcome1";
$sipp_DBName  = "172.28.108.180:{$CONFIG->port_echa}/sijstkecha";

//kpi production
$kpi_prod_DBUser = "kpi";
$kpi_prod_DBPass = "kpi";
$kpi_prod_DBName = "172.28.108.180:{$CONFIG->port_oltp}/dboltp";

//MLT
$mlt_DBUser     = "MLT";
$mlt_DBPass     = "MLT";
$mlt_DBName     = "172.28.108.180:{$CONFIG->port_oltp}/dboltp";
$mlt_service    = "http://172.28.201.80:7153";

//VOKASI
$vks_DBUser   = "VKS";
$vks_DBPass   = "VKS";
$vks_DBName   = "172.28.108.180:{$CONFIG->port_echa}/sijstkecha";

//KPI INDIVIDU
$kpi_indi_DBUser = "KPI_INDI";
$kpi_indi_DBPass = "KPI_INDI_042020";
$kpi_indi_DBName = "172.28.108.180:1521/DBDEVELOP";

//kpi ODS
$kpi_ods_DBUser = "kpi";
$kpi_ods_DBPass = "kpi";
$kpi_ods_DBName = "172.28.136.51:1526/SIJSTKOLAP";

//SKP Online
$dpkp_DBUser    = "admin_dpkp";
$dpkp_DBPass    = "logintoadcapture";
$dpkp_DBName    = "172.28.108.180:{$CONFIG->port_oltp}/dboltp";

//ESURVEY OLAP
$ESV_DBUser  	= "BIGDATA";
$ESV_DBPass  	= "bigdatamdt2020";
$ESV_DBName		= "172.28.136.51:1521/DBOLAP";
//$ESV_DBName		= "172.28.136.51:1521/dbolap";

//control_data_ods
$ctl_data_ods_DBUser    = "mis";
$ctl_data_ods_DBPass    = "mis";
$ctl_data_ods_DBName    = "172.28.136.51:1521/dboltp";

//report control_data_ods
$ctl_rpt_ods_DBUser     = "mis";
$ctl_rpt_ods_DBPass     = "mis";
$ctl_rpt_ods_DBName     = "172.28.136.51:1526/SIJSTKOLAP";

//error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('soap.wsdl_cache_enabled', '0');
ini_set('soap.wsdl_cache_ttl', '0');
//$display_errors = 1;
//ini_set('display_errors', $display_errors);
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";

//$wsIp = 'http://172.28.200.83:2014';
$wsIp = 'http://172.28.108.201:2024';
//$wsIpDownload = '172.28.200.83';
$wsIpDownload = '172.28.108.201';
$wsPortDownload = '2024';
//$wsIpStorage = 'http://ceph-api-ceph.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$wsIpStorage = 'http://ceph-storage-api-ceph-dev.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$wsIpDocument = 'http://ceph-storage-api-ceph-dev.apps.ocp-dc.bpjsketenagakerjaan.go.id';
// $wsIp = 'http://172.28.200.83:2014';
// $wsIp = 'http://wsaxis.bpjsketenagakerjaan.go.id:2014';

//$wsIp = 'http://172.28.201.202:2024';
//$wsIpMergeDokumen='172.28.200.253';
$wsIpMergeDokumen='172.28.208.31';
$wsPortMergeDokumen = '5001';

//$wsIpSidia='http://sidia.bpjsketenagakerjaan.go.id/services';
$wsIpSidia='https://sidia-testing.bpjsketenagakerjaan.go.id';

/*API / BACKEND NEW EPROCURE*/
$wsEproc='http://172.28.108.97:2200';

/* jmo prod api-mobileapps-notifications */
//$wsIpJMO = 'http://172.28.200.253:4033';
//$wsIpJMO = 'http://jmo-mobileapps-notifications-jmo.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$wsIpJMO = 'http://jmo-log-restful-api-appd-jmo-dev.apps.ocp-drc.bpjsketenagakerjaan.go.id';

/* cam apps jmo */
//$wsIpJMOSmile='http://172.28.200.253:4041';
//$wsIpJMOSmile='http://ekyc-restful-api-jmo.apps.ocp-dc.bpjsketenagakerjaan.go.id';
//$wsIpJMOSmile='http://ekyc-restful-api-vida-jmo.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$wsIpJMOSmile='http://ekyc-restful-api-jmo.apps.ocp-dc.bpjsketenagakerjaan.go.id';
$appsCam = 'https://cam-dev.bpjsketenagakerjaan.go.id';

/* ip BPU */
$wsIpBPU = 'http://172.28.108.201:2024';

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

/*define("WSDaftar", $wsIp."/WSDaftar/services/Main?wsdl");
define("WSDaftarEP", $wsIp."/WSDaftar/services/Main.MainHttpSoap11Endpoint/");*/

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
$wsIpDokumenAntrian='http://172.28.108.97:8020/upload/getFileSmile?data=';
$wsIpDokumenDownload='http://172.28.108.97:8020';
//$wsIpLapakAsikOnsite = 'https://lapakonsite-oti.bpjsketenagakerjaan.go.id';
$wsIpLapakAsikOnsite = 'http://172.28.100.201:8022';
$wsIpLapakAsikOnsiteDomain = 'https://lapakasik-onsite-dev.bpjsketenagakerjaan.go.id';
$wsIpLapakAsikOnline='http://172.28.108.97:8020';
//$wsIpLapakAsikOnline='https://lapakasik.bpjsketenagakerjaan.go.id/services';

$wsPushNotifikasi='172.28.208.31:9007/push-notification';
$authNotifikasi='key=AAAAj2HGYgc:APA91bHGo3yTkR0hf5MBjOdtmYifsDW4a3Xua45s6L1NLtaQ1oZ2kjuH5TRC7R2ur8I_HwOZxYxCSifEAEFDxcQPZ8imDI59ECsiG1Q0kj5VLoEeC0nbGf-k6x3OM2Gh9QIvwamA_Wh2';

//ws investasi
$wsInvest = 'http://172.28.208.97:3041'; // alamat ws smile-investment
$wsInvestWithEncryption = true;

$wsInvestasi= 'http://172.28.108.113:3375';

/*include includes/fungsi_newrpt.php*/
$antrian_rpt_user   = 'nsp';
$antrian_rpt_pass = 'welcome1';
$antrian_rpt_sid  = 'echadev';
$antrian_rpt_path   = '/data/reports/';
$antrian_rpt_link = 'http://'.$HTTP_HOST.'/urldest.php?url=';

$wsIpArsip = 'http://172.28.201.201:2601';

//generate arsip
$nc_rpt_user_arsip  = 'sijstk';
$nc_rpt_pass_arsip  = 'welcome1';

//report smile rpt user
$smile_rpt_user     = 'sijstk';
$smile_rpt_pass     = 'welcome1';
$smile_rpt_sid      = 'dbdevelop';
$smile_rpt_sid_drc    = 'dboltpdrc';
$smile_rpt_path     = '/data/reports/';
$smile_rpt_link     = 'http://'.$HTTP_HOST.'/urldest.php?url=';

//report ec rpt user
$ec_rpt_user  = 'ec';
$ec_rpt_pass  = 'ec';
$ec_rpt_sid   = 'echadev';
$ec_rpt_path  = '/data/reports/';
$ec_rpt_link  = 'http://'.$HTTP_HOST.'/urldest.php?url=';

//report spo rpt user
$spo_rpt_user = 'spo';
$spo_rpt_pass = 'spo';
$spo_rpt_sid  = 'echadev';
$spo_rpt_sid_drc  = 'sijstkechadrc';
$spo_rpt_path = '/data/reports/';
$spo_rpt_link = 'http://'.$HTTP_HOST.'/urldest.php?url=';

//report vks rpt user
$vks_rpt_user = 'vks';
$vks_rpt_pass = 'vks';
$vks_rpt_sid  = 'echadev';
$vks_rpt_path = '/data/reports/';
$vks_rpt_link = 'http://'.$HTTP_HOST.'/urldest.php?url=';

//report bsu rpt user
$bsu_rpt_user = 'NSP';
$bsu_rpt_pass = 'nsp';
$bsu_rpt_sid  = 'echadev';
$bsu_rpt_path = '/data/reports/';
$bsu_rpt_link = 'http://'.$HTTP_HOST.'/urldest.php?url=';

// user database MDT
/*$ESV_DBUser     = "BIGDATA";
$ESV_DBPass     = "";
$ESV_DBName     = "olap.bpjsketenagakerjaan.go.id:1521/DBOLAP";*/

$ipReportServer='http://172.28.108.151';
// 05102021, untuk kebutuhan pemisahan WS ke pembentukan dokumen digital    
$ipReportServerDocument='http://172.28.108.151';

//upload smile-api
//$wsFileSmile="http://172.28.200.74:4022";
$wsFileSmile="http://172.28.100.201:3011";

//ws siapkerja JKP prod
//$wsFileSiapKerja="http://172.28.200.76:3012";
$wsFileSiapKerja="http://172.28.100.201:3011";
$wsFileSiapKerjaIp="http://172.28.100.201:3011";

//Microservices API
//====================
$api_services_addr='http://172.28.101.106';

//BIOMETRIK JMO
$wsIpStorageOcp = 'http://assets-dev.bpjsketenagakerjaan.go.id';
$wsIpJMOLog = 'http://jmo-log.ocp-dev.bpjsketenagakerjaan.go.id';

//ECERTIFICATE
$wsIpCertificate="https://api-dev.bpjsketenagakerjaan.go.id/view";
$wsCertificate="http://ecertificate-bpjamsostek-core-dev.apps.ocp-dc.bpjsketenagakerjaan.go.id/ecertificate";

//DASHBOARD BPJAMSOSTEK
$ip_dsh_tableau = 'http://dashboard-dev.bpjsketenagakerjaan.go.id';

//DAMON
$ipDamon = 'http://damon-dev.bpjsketenagakerjaan.go.id';

//GEN KARTU DIGITAL
$wsIpGenKartu = '172.28.108.97:3023';

/*Report DRC*/
$ipReportServerDRC='http://172.28.108.151';

/*WS RSJHT*/
$wsRSJHT='http://172.28.108.201:2024';

/*JS NOTIFIKASI KLAIM*/
$api_notifikasi_klaim='http://js-nc.ocp-dev.bpjsketenagakerjaan.go.id';

/*wsip BSU*/
$wsBsu = "http://bsu-collect.ocp-dev.bpjsketenagakerjaan.go.id";

/*API MLT JMO*/
$api_mlt_jmo = 'http://jmo-mlt.ocp-dev.bpjsketenagakerjaan.go.id';

/*API REPORT NODEJS*/
 
//API BANK DATA CSR
$apiCsr = 'http://api-csr.ocp-dev.bpjsketenagakerjaan.go.id';

//API ICP EMAIL
$apiIcp = 'http://icp-iv-dev.bpjsketenagakerjaan.go.id';

//ESURVEY
$urlESurvey = 'https://esurvey-dev.bpjsketenagakerjaan.go.id/?id=';

//PMI
$url_form_p3mi = "https://pmi-dev.bpjsketenagakerjaan.go.id";

// Key Exchanges
$kx_public_key 	= '635fbfb80a43b3ab13053c0d68faa2c6d4becb682e4de77f86ab0c4045408873';
$kx_private_key = 'a75af86854d554e5af02f0fae6b276561b8e96ffee935e1f8f7d5d5d4c561ebb';

// Rate Limiter
$rl_pref_id = "SMILE_RL";
$rl_redis_urls = "172.28.200.131:7000,172.28.200.132:7000,172.28.200.133:7000";
$rl_redis_password = "2021bpjamsostekjayajaya";
$rl_max_request = 10;
$rl_expiry_time = 100;

// smile adapter dl keypair
$smile_adapter_dl_sv_private_key = '89eb0d6a8a691dae2cd15ed0369931ce0a949ecafa5c3f93f8121833646e15c3';
$smile_adapter_dl_sv_public_key = '557e23d7346f213ec5a23713b2a2497eef35354d5b52088ac6a5993a5fdb091e';
$smile_adapter_dl_ci_private_key = '5601359129575ca6c4703316e8be17722d9140454ab3155256de47d88dc49c6c';
$smile_adapter_dl_ci_public_key = '055071544f7e138ac714fb2f1ab32314e314dcbf0e2764a4c0061350a330fe28';

// smile adapter api
$smile_adapter_dl_url = 'http://adapter-downloader-common-dev.apps.ocp-drc.bpjsketenagakerjaan.go.id/download/v1';
$smile_adapter_dl_url_pdf = 'http://adapter-downloader-common-dev.apps.ocp-drc.bpjsketenagakerjaan.go.id/download/v1/pdf?';
$smile_adapter_dl_url_xls = 'http://adapter-downloader-common-dev.apps.ocp-drc.bpjsketenagakerjaan.go.id/download/v1/spreadsheet?';

// Rate Limiter
$rl_pref_id = "SMILE_RL";
$rl_redis_urls = "172.28.200.131:7000,172.28.200.132:7000,172.28.200.133:7000";
$rl_redis_password = "2021bpjamsostekjayajaya";
$rl_max_request = 10;
$rl_expiry_time = 100;

// toggle active captcha set value "on" is active and "off" is not active  
$toggle_captcha = "off";
$g_site_key   = "6LcWuggpAAAAAID0P-axJMxSM4UFO4KEJqkB-CV3";
$g_secret_key = "6LcWuggpAAAAAJSy_IIvjtIVkPdTD8wta2NVz3KB";
$g_http_proxy = "http://proxy.bpjsketenagakerjaan.go.id:7890";
$g_url_captcha_verify = "https://www.google.com/recaptcha/api/siteverify";

//hit ws force proses BPU
define("WSLHK2", $wsIp."/WSLHK2/services/Main?wsdl");
define("WSLHK2EP", $wsIp."/WSLHK2/services/Main.MainHttpSoap11Endpoint/");

define("WSLHK", $wsIp."/WSLHK/services/Main?wsdl");
define("WSLHKEP", $wsIp."/WSLHK/services/Main.MainHttpSoap11Endpoint/");

//Default access time adalah jam akses aplikasi yang diperbolehkan awal - akhir, pastikan format sesuai "HH:MI-HH:MI"
$default_access_time = "00:00-23:59";
//berlaku untuk hari libur dan weekend 
$default_outside_access_time = "00:00-23:59";

$wsTrxChanelGateway = 'http://trans-gateway.ocp.bpjsketenagakerjaan.go.id';

//Pembatasan akses melalui Pulse Secure
$toggle_pulse = "off";
$url_pulse_dc = 'https://172.28.212.3';
$url_pulse_drc = 'https://172.28.112.3';
$user_login_pulse = 'dev.franky';
$pass_login_pulse = 'RPT2024!';

$wsWasrikRaker = 'http://wasrik-api.ocp.bpjsketenagakerjaan.go.id';
?>
