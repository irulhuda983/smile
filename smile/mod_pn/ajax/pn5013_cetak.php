<?PHP
session_start();
include "../../includes/balau.php"; 
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$TYPE		= $_POST["TYPE"];
$DATAID		= $_POST["DATAID"];
$USER     	= $_SESSION["USER"];
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
if($TYPE=='CETAK_REPORT'){
  $KODE_REPORT  = $_POST['KODE_REPORT'];
  $KODE_PROMOTIF = $_POST['KODE_PROMOTIF'];  $KODE_KLAIM = $_POST['KODE_KLAIM'];  $KODE_USER = $_POST['KODE_USER'];  
  $sql = "select url_report, parameter_report from sijstk.kn_kode_report where kode_report = '".$KODE_REPORT."'";
  $DB->parse($sql);
  $DB->execute();
  $data      = $DB->nextrow();
  $URL       = $data['URL_REPORT'];
  $PARAMETER = $data['PARAMETER_REPORT'];

  $PARAM = str_replace("P_KODE_PROMOTIF%3DPARAM_KODE_PROMOTIF", "P_KODE_PROMOTIF%3D$KODE_PROMOTIF", $PARAMETER);  $PARAM = str_replace("P_KODE_KANTOR%3DPARAM_KODE_KANTOR", "P_KODE_KANTOR%3D$KODE_KANTOR", $PARAM);  $PARAM = str_replace("P_KODE_KLAIM%3DPARAM_KODE_KLAIM", "P_KODE_KLAIM%3D$KODE_KLAIM", $PARAM);    $PARAM = str_replace("P_KODE_USER%3DPARAM_KODE_USER", "P_KODE_USER%3D$KODE_USER", $PARAM);  
  $IP_ADDRESS= $ip_svr;
  $IP_ADDRESS_RDF = $ip_rdf;
  // $ls_link  = "http://172.28.202.62/sijstk/includes/rptBPJS.php?url=";  
  $ls_link  = "http://".$IP_ADDRESS."/sijstk/includes/rptBPJS.php?url=";
  // $ls_link   = "http://localhost/sijstk/includes/rptBPJS.php?url=";		/*
  $ls_user  = "core_sijstk";
  $ls_pass  = "banding2016";
  $ls_sid   = "sijstkoltp"  */
  $ls_user  = "sijstk";
  $ls_pass  = "welcome1";
  $ls_sid   = "dbdevelop";
  $ls_path  = "/data/jms/SIPT/KP/REPORT";
  $ls_pdf   = '1'; 
  $report["link"]   = $ls_link;
  $report["user"]   = $ls_user;
  $report["password"] = $ls_pass;
  $report["sid"]    = $ls_sid;
  $report["path"]   = urlencode($ls_path);
  $report["file"]   = $ls_nama_rpt;
  $URL = str_replace("usr", $ls_user, $URL);
  $URL = str_replace("pswd", $ls_pass, $URL);
  $URL = str_replace("dbid", $ls_sid, $URL);
  //$rwservlet = "http://".$IP_ADDRESS_RDF.":7779".$URL.$PARAM;
  $rwservlet = "http://".$IP_ADDRESS_RDF.":7781".$URL.$PARAM;
  $rwservlet = str_replace("'","",$rwservlet);
   // echo $rwservlet;
  $link = $report["link"].base64_encode($rwservlet);
  echo $link;
}else{
  echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
}
?>
