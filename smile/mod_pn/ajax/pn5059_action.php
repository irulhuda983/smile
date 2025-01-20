<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER = $_SESSION["USER"];
//$USER = null;
$KODE_ROLE = $_SESSION['regrole'];



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
    echo '{ "ret":-1, "msg":"<b>Error:</b> '.$errorMsg.'" }';
    die();
  }
}
set_error_handler("DefaultGlobalErrorHandler");
$ls_tipe      = $_POST["tipe"];


if ($ls_tipe == "select")
{
  $ls_search_by = $_POST["search_by"];
  $ls_search_by2 = $_POST["search_by2"];
  $ls_search_txt = $_POST["search_txt"];
  $ls_keyword2a = $_POST["keyword2a"];
  $ls_keyword2b = $_POST["keyword2b"];
  $ls_keyword2c = $_POST["keyword2c"];
  $ls_keyword2d = $_POST["keyword2d"];
  $ls_search_tgl = $_POST["search_tgl"];
  $ls_tglakhirdisplay = $_POST['tglakhirdisplay'];
  $ls_tglawaldisplay = $_POST['tglawaldisplay'];
  $ls_kode_kantor =$_POST['kode_kantor'];

  $ls_page = $_POST["page"];
  $ls_page_item = $_POST["page_item"];

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

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




  $sql = "
  SELECT ROWNUM NO, Z.*
     FROM  
    (
         select 
          kode_kantor,
          (select nama_kantor from kn.vw_kn_ms_kantor_report where kode_kantor=a.kode_kantor) nama_kantor,
           kode_wilayah,
          (select nama_wilayah from kn.vw_kn_ms_kantor_report where kode_kantor=a.kode_kantor) nama_wilayah,
         substr(kode_tipe_klaim,1,3) jenis_klaim,
          kode_segmen, 
          nama_tk,
          kpj,
          nomor_identitas nik_tk,
          to_char(tgl_kejadian,'dd/mm/yyyy')  tgl_kejadian,
          kode_klaim kode_klaim_induk,
          to_char(tgl_bayar_klaim,'dd/mm/yyyy')  tgl_bayar_klaim_induk, 
          no_hp_penerima_manfaat,
          email_penerima_manfaat,
          decode(flag_dapat_beasiswa,'T','Tidak Dapat Beasiswa', 'Y', 'Dapat Beasiswa') flag_dapat_beasiswa,
          jml_anak_penerima_beasiswa,
          keterangan,
          tgl_rekam,
          decode(status_tindak_lanjut,'T','Belum Ditindaklanjuti','Sudah Ditindaklanjuti') status_tindak_lanjut
        from
         pn.pn_klaim_monitoring_beasiswa a
         {$condition}
         {$condition2}
            {$filter_kode_kantor}    
            {$ls_filter_tgl}   
    )  Z  

  ";
//  print_r($sql);

  
  
  $sql_count = "select count(1) rn from ($sql) where 1=1";
  $sql_query = "select * from ($sql) where 1=1 and no between ".$start." and ".$end . " ";

  $DB->parse($sql_count);
  $DB->execute();
  $row = $DB->nextrow();
  $recordsTotal = (float) $row["RN"];
  
  $pages = ceil($recordsTotal / $ls_page_item);
  $DB->parse($sql_query);
  if($DB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $DB->nextrow()){
      $data["NO"] = $start + $i;
      
      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"] = "1";
    $jsondata["start"] = $start;
    $jsondata["end"] = $end;
    $jsondata["page"] = $ls_page;
    $jsondata["recordsTotal"] = $recordsTotal;
    $jsondata["recordsFiltered"] = $itotal;
    $jsondata["pages"] = $pages;
    $jsondata["data"] = $jdata;
    $jsondata["msg"] = $sql;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";
    $jsondata["recordsTotal"] = "0";
    $jsondata["recordsFiltered"] = "0";
    $jsondata["pages"] = "0";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}
else if($ls_tipe == "edit"){
  $ls_kode_klaim = $_POST["kode_klaim"];
  $ls_flag_dapat_beasiswa = $_POST["flag_dapat_beasiswa"];
  $ls_alasan_hasil_check = $_POST["alasan_hasil_check"];
  $ls_keterangan = $_POST["keterangan"];
  $ls_kode_kantor = trim($_POST["kode_kantor"]);
   $ls_kode_klaim_tindaklanjut = '';
  $ls_kode_pointer_tindaklanjut = 'PN5059';

  


  $qry = "
  begin
 pn.p_pn_pn5059.x_post_tindak_lanjut_submit
    (
      :p_kode_klaim , 
      :p_kode_klaim_tindak_lanjut , 
      :p_kode_kantor ,
      :p_flag_dapat_beasiswa , 
      :p_kode_hasil_pengecekan , 
      :p_kode_pointer_tindak_lanjut , 
      :p_keterangan , 
      :p_user , 
      :p_sukses , 
      :p_mess  
    );   
  end;";

  $proc = $DB->parse($qry);


  oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 100);
  oci_bind_by_name($proc, ":p_kode_klaim_tindak_lanjut", $ls_kode_klaim_tindaklanjut, 100);
  oci_bind_by_name($proc, ":p_kode_kantor", $ls_kode_kantor, 30);
  oci_bind_by_name($proc, ":p_flag_dapat_beasiswa", $ls_flag_dapat_beasiswa, 30);
  oci_bind_by_name($proc, ":p_kode_hasil_pengecekan", $ls_alasan_hasil_check, 100);
  oci_bind_by_name($proc, ":p_kode_pointer_tindak_lanjut", $ls_kode_pointer_tindaklanjut, 100);
  oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 4000);
  oci_bind_by_name($proc, ":p_user", $USER, 30);
  oci_bind_by_name($proc, ":p_sukses", $ls_sukses, 10);
  oci_bind_by_name($proc, ":p_mess", $ls_mess, 1000);
  


  if($DB->execute()){
    $sukses = $ls_sukses;
    $msg = $ls_mess;
    if ($sukses == "1") {

      $jsondata["ret"] = "1";
      $jsondata["msg"] = "Proses simpan selesai";
    } else {
 
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = $msg;
    }
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Gagal Eksekusi";
  }
  echo json_encode($jsondata);
}

?>