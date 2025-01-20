<?php
session_start();

require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
include "../../includes/fungsi_newrpt.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$tipe = $_GET["tipe"];
$ls_search_by = $_GET["search_by"];
$ls_search_by2 = $_GET["search_by2"];
$ls_search_txt = $_GET["search_txt"];
$ls_keyword2a = $_GET["keyword2a"];
$ls_keyword2b = $_GET["keyword2b"];
$ls_keyword2c = $_GET["keyword2c"];
$ls_keyword2d = $_GET["keyword2d"];
$ls_search_tgl = $_GET["search_tgl"];
$ls_tglakhirdisplay = $_GET['tglakhirdisplay'];
$ls_tglawaldisplay = $_GET['tglawaldisplay'];
$ls_kode_kantor =$_GET['kode_kantor'];
  
$USER = $_SESSION["USER"];
$KD_KANTOR = $_SESSION['kdkantorrole'];

$condition = "";
if ($ls_search_txt != ""){
  if ($ls_search_by == "sc_kode_klaim"){
    $condition = " where kode_klaim like '%{$ls_search_txt}%' ";
  } else if ($ls_search_by == "sc_no_kpj"){
    $condition = " where KPJ like '%{$ls_search_txt}%' ";
  } else if ($ls_search_by == "sc_nama_tk"){
    $condition = " where nama_tk like '%{$ls_search_txt}%' ";
  } 
  // else if ($ls_search_by == "sc_kd_kc"){
  //   $condition = " where kode_kantor like '%{$ls_search_txt}%' ";
  // } else if ($ls_search_by == "sc_kd_kw"){
  //   $condition = " where kode_wilayah like '%{$ls_search_txt}%' ";
  // }
}
else{
   $condition = "WHERE 1=1 ";
}
$condition2 = "";
if ($ls_search_by2 != ""){
  if ($ls_search_by2 == "KODE_SEGMEN"){
    $condition2 = " and kode_segmen = '{$ls_keyword2c}' ";
  } else if ($ls_search_by2 == "KODE_TIPE_KLAIM"){
    $condition2 = " and kode_tipe_klaim = '{$ls_keyword2a}' ";
  } else if ($ls_search_by2 == "KODE_KONDISI_TERAKHIR"){
    $condition2 = " and kode_kondisi_terakhir = '{$ls_keyword2b}' ";
  } else if ($ls_search_by2 == "KODE_STATUS_TINDAK_LANJUT"){
    $condition2 = " and status_tindak_lanjut = '{$ls_keyword2d}' ";
  } 
  // else if ($ls_search_by == "sc_kd_kc"){
  //   $condition = " where kode_kantor like '%{$ls_search_txt}%' ";
  // } else if ($ls_search_by == "sc_kd_kw"){
  //   $condition = " where kode_wilayah like '%{$ls_search_txt}%' ";
  // }
}

if($ls_kode_kantor != '' || $ls_kode_kantor != null){
  $filter_kode_kantor = "and kode_kantor = '{$ls_kode_kantor}'
                      ";
}else{
  $filter_kode_kantor = "and kode_kantor in (select kode_kantor
                                             from ms.ms_kantor
                                             where aktif = 'Y'
                                                  and status_online = 'Y'
                                                  and kode_tipe not in ('1', '2')
                                                  start with kode_kantor = '{$KD_KANTOR}'
                                                  connect by prior kode_kantor = kode_kantor_induk)
                      ";
}



$ls_filter_tgl = " and tgl_kejadian >= TO_DATE ('{$ls_tglawaldisplay}','dd-mm-rrrr') and tgl_kejadian <= TO_DATE ( '{$ls_tglakhirdisplay}','dd-mm-rrrr')";

if ( $tipe = "PDF") {
   $ls_nama_rpt = "PNR5059";
	$ls_modul      = "pn";
	$ls_user_param =" P_PERIODE1='".$ls_tglawaldisplay."' P_PERIODE2='".$ls_tglakhirdisplay."' P_KODE_KANTOR='".$ls_kode_kantor."' P_SEARCHBY='".$ls_search_by."'  P_SEARCHBY2='".$ls_search_by2."'  P_KEYWORD2A='".$ls_keyword2a."'  P_KEYWORD2B='".$ls_keyword2b."'  P_KEYWORD2C='".$ls_keyword2c."'  P_KEYWORD2D='".$ls_keyword2d."' P_SEARCHTEXT='".$ls_search_txt."'  ";

	exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
}

echo "<script language=\"JavaScript\" type=\"text/javascript\">";
echo "  window.close();";
echo "</script>";

?>
