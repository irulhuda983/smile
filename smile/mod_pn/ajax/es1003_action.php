<?PHP
$ESV_DBUser  	= "BIGDATA";
$ESV_DBPass  	= "bigdatamdt2020";
$ESV_DBName		= "172.28.136.51:1521/DBOLAP";
// var_dump("test");die();
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$ESVDB = new Database($ESV_DBUser,$ESV_DBPass,$ESV_DBName);

error_reporting(E_ALL);
ini_set('display_errors', 1);

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
set_error_handler("handleError");
$ls_tipe  = $_POST["tipe"];
$ls_tahun =  isset($_POST['tahun']) ? ($_POST['tahun']) : "0";

 



if ($ls_tipe == "select")
{
  // var_dump("test");die();

  $ls_page = "1";
  $ls_page_item = "10";

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;
 
  // $condition_kantor = "";
  // if ($ls_searchtxt_unit != "" || $ls_searchtxt_unit == "0"){    
  //     $condition_kantor = " where kode_kantor in (select kode_kantor
  //                                              from ms.ms_kantor@to_kn
  //                                              where aktif = 'Y'
  //                                                   and status_online = 'Y'
  //                                                   and kode_tipe not in ('1', '2')
  //                                                   start with kode_kantor = '{$ls_searchtxt_unit}'
  //                                                   connect by prior kode_kantor = kode_kantor_induk) ";    
  // }
  // else{
  //    $condition_kantor = "where kode_kantor in (select kode_kantor
  //                                              from ms.ms_kantor@to_kn
  //                                              where aktif = 'Y'
  //                                                   and status_online = 'Y'
  //                                                   and kode_tipe not in ('1', '2')
  //                                                   start with kode_kantor = '{$KD_KANTOR}'
  //                                                   connect by prior kode_kantor = kode_kantor_induk)  ";
  // }

  //   $condition_responden_semua = "";
  // if ($ls_searchtxt_unit != "" || $ls_searchtxt_unit == "0"){    
  //     $condition_responden_semua = " and kode_kantor_klaim in (select kode_kantor
  //                                              from ms.ms_kantor@to_kn
  //                                              where aktif = 'Y'
  //                                                   and status_online = 'Y'
  //                                                   and kode_tipe not in ('1', '2')
  //                                                   start with kode_kantor = '{$ls_searchtxt_unit}'
  //                                                   connect by prior kode_kantor = kode_kantor_induk) ";    
  // }
  // else{
  //    $condition_responden_semua = "and kode_kantor_klaim in (select kode_kantor
  //                                              from ms.ms_kantor@to_kn
  //                                              where aktif = 'Y'
  //                                                   and status_online = 'Y'
  //                                                   and kode_tipe not in ('1', '2')
  //                                                   start with kode_kantor = '{$KD_KANTOR}'
  //                                                   connect by prior kode_kantor = kode_kantor_induk)  ";
  // }


  
 
  // $ls_filter_tgl= "";
  // if (($ls_tglawaldisplay != "" and $ls_tglakhirdisplay != "") || $ls_tglawaldisplay == "0" || $ls_tglakhirdisplay == "0"){  
  //   $ls_filter_tgl = " and TO_DATE(tgl_kirim_email,'dd-mm-rrrr') >= TO_DATE ('{$ls_tglawaldisplay}','dd-mm-rrrr') and TO_DATE(tgl_kirim_email,'dd-mm-rrrr') <= TO_DATE ( '{$ls_tglakhirdisplay}','dd-mm-rrrr')";
  // }


$sql = "
select
TAHUN_KIRIM_SURVEY,
COUNT(distinct KODE_KANAL_JENIS_SURVEY)JUMLAH_JENIS_KANAL,
sum(TOTAL_RESPONDEN)TOTAL_RESPONDEN,
round(avg(RSTP),2)RSTP,
round(avg(RTP),2)RTP,
round(avg(RC),2)RC,
round(avg(RP),2)RP,
round(avg(RSP),2)RSP,
round(avg(RD),2)RD,
round(avg(RPA),2)RPA,
round(avg(RPRO),2)RPRO,
round(avg(NILAI_KEPUASAN),2)NILAI_KEPUASAN,
round(avg(NILAI_EFEKTIVITAS),2)NILAI_EFEKTIVITAS,
sum(RALL)RALL,
sum(TOTAL_PENGIRIMAN)TOTAL_KIRIM,
round(avg(NILAI_NPS),2)NILAI_NPS
FROM BIGDATA.F_PPK_ESV_SURVEY_DASHBOARD
where TAHUN_KIRIM_SURVEY  = {$ls_tahun}     
group by TAHUN_KIRIM_SURVEY 
";

  $ESVDB->parse($sql);
  if($ESVDB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $ESVDB->nextrow()){
      $data["NO"] = $start + $i;
      $jdata[] = $data;
      $i++;
      $itotal++;
    }

    $jsondata["ret"] = "1";
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
else if ( $ls_tipe == "select_detil_jawaban_responden" )
{
  $ls_kode_survey = $_POST["kode_survey"];
  $ls_kode_survey_responden = $_POST["kode_survey_responden"];

  $ls_search_by = $_POST["search_by"];
  $ls_search_filter = $_POST["search_filter"];
  $ls_search_txt = $_POST["search_txt"];
  $ls_search_tgl = $_POST["search_tgl"];
  $ls_tglakhirdisplay = $_POST['tglakhirdisplay'];
  $ls_tglawaldisplay = $_POST['tglawaldisplay'];
  
  $ls_page = $_POST["page"];
  $ls_page_item = $_POST["page_item"];

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

  $condition = "";
  if ($ls_search_txt != ""){
    if ($ls_search_by == "sc_kpj"){
      $condition = " where kpj='{$ls_search_txt}' ";
    } else if ($ls_search_by == "sc_nik"){
      $condition = " where nomor_identitas ='{$ls_search_txt}' ";
    } else if ($ls_search_by == "sc_nama"){
          $condition = " where UPPER(nama_tk) like '%{$ls_search_txt}%' ";
    } else if ($ls_search_by == "sc_no_hp"){
          $condition = " where A.no_handphone like '%{$ls_search_txt}%' ";
    } else if ($ls_search_by == "sc_email"){
          $condition = " where UPPER(email) like '%{$ls_search_txt}%' ";
    } else if ($ls_search_by == "sc_kode_booking"){
      $condition = " where A.kode_booking ='{$ls_search_txt}' ";
    }
  }else if($ls_search_txt=="" && $ls_search_tgl !=""){
     $condition=" where A.tgl_booking=";
  }
  else{
     $condition = "WHERE 1=1";
  }
  $ls_filter_kode_survey_responden = " and v.kode_survey_responden='{$ls_kode_survey_responden}'";
  
 

  $sql = "
  SELECT ROWNUM NO, Z.* FROM 
  (
    -- SELECT 
    -- B.NAMA_SURVEY,
    -- C.NAMA_JENIS_SURVEY,
    -- D.NAMA_PERTANYAAN,
    -- E.NAMA_JAWABAN
    -- FROM ESURVEY.ESV_SURVEY_JAWABAN A left join ESURVEY.ESV_SURVEY B on A.KODE_SURVEY = B.KODE_SURVEY
    -- left join bigdata.ESV_KODE_JENIS_SURVEY C on B.KODE_JENIS_SURVEY = C.KODE_JENIS_SURVEY
    -- left join ESURVEY.ESV_SURVEY_PERTANYAAN D ON A.KODE_SURVEY_PERTANYAAN = D.KODE_SURVEY_PERTANYAAN
    -- LEFT JOIN ESURVEY.ESV_KODE_JAWABAN E ON A.KODE_JAWABAN = E.KODE_JAWABAN
    select
    *
    from
    (       
          select 
          (select nama_jenis_survey from  bigdata.f_ppk_esv_kode_jenis_survey where kode_jenis_survey = a.kode_jenis_survey) nama_jenis_survey,
          b.nama_kategori,
          c.nama_pertanyaan,
          (select nama_jawaban from bigdata.f_ppk_esv_kode_jawaban where kode_jawaban = d.kode_jawaban)nama_jawaban,
          kode_survey_responden,
          kode_survey_jawaban
          from 
          bigdata.f_ppk_esv_survey a,
          bigdata.f_ppk_esv_survey_kategori b,
          bigdata.f_ppk_esv_survey_pertanyaan c,
          bigdata.f_ppk_esv_survey_jawaban d
          where
          a.kode_survey = b.kode_survey 
          and b.kode_survey_kategori = c.kode_survey_kategori
          and d.kode_survey_pertanyaan = c.kode_survey_pertanyaan
          order by kode_survey_jawaban
     ) v
    {$condition}
    {$ls_filter_kode_survey_responden}
  ) Z
  ";
  // var_dump($sql);
  // die;
  $sql_count = "select count(1) rn from ($sql) where 1=1";
  $sql_query = "select * from ($sql) where 1=1 and no between ".$start." and ".$end . " ";

  $ESVDB->parse($sql_count);
  $ESVDB->execute();
  $row = $ESVDB->nextrow();
  $recordsTotal = (float) $row["RN"];
  
  $pages = ceil($recordsTotal / $ls_page_item);
  $ESVDB->parse($sql_query);
  if($ESVDB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $ESVDB->nextrow()){
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
    $jsondata['param'] = $ls_search_filter;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["start"] = "0";
    $jsondata["end"] = "0";
    $jsondata["page"] = "0";  
    $jsondata["recordsTotal"] = "0";
    $jsondata["recordsFiltered"] = "0";
    $jsondata["pages"] = "0";
    $jsondata["msg"] = $sql;
    echo json_encode($jsondata);
  }
}
else if ($ls_tipe == 'dashboard_nilai_kepuasan')
{

  $sql = "
  select
  kode,
  bulan,
  nvl(kanal_fisik_milik,0)kanal_fisik_milik,
  nvl(kanal_fisik_mitra,0)kanal_fisik_mitra,
  nvl(kanal_elektronik_dan_digital,0)kanal_elektronik_dan_digital
  from
  (     
      select 
      a.kode,
      a.keterangan bulan,
      NILAI_KEPUASAN nilai_jawaban,
      b.nama_kanal_segmen_survey
      from ms.ms_lookup@to_kn a
      left join(
                 select 
                    to_char(blth_kirim_survey,'mm') bulan,
                    (select nama_kanal_segmen_survey from BIGDATA.F_PPK_ESV_KODE_KANAL_SEGMEN R where R.kode_kanal_segmen_survey = A.kode_kanal_segmen_survey) nama_kanal_segmen_survey,
                    NILAI_KEPUASAN
                    FROM BIGDATA.F_PPK_ESV_SURVEY_DASHBOARD A     
                    WHERE tahun_kirim_survey = '{$ls_tahun}'
      )b
      on a.kode = b.bulan
      where a.tipe = 'BULAN'      
  )
  pivot
  (max(nilai_jawaban)
  for nama_kanal_segmen_survey in ('KANAL FISIK MILIK' as KANAL_FISIK_MILIK, 'KANAL FISIK MITRA' as KANAL_FISIK_MITRA, 'KANAL ELEKTRONIK DAN DIGITAL' as KANAL_ELEKTRONIK_DAN_DIGITAL))
  order by kode
  

  ";


  $ESVDB->parse($sql);
  if($ESVDB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $ESVDB->nextrow()){      
      $jdata[] = $data;
      $i++;
      $itotal++;
    }
    $jsondata["ret"] = "1";
    $jsondata["recordsFiltered"] = $itotal;
    $jsondata["data"] = $jdata;
    $jsondata["msg"] = $sql;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    

}else if ($ls_tipe == 'dashboard_nilai_nps')
{



  $sql = "
  select
  kode,
  bulan,
  nvl(kanal_fisik_milik,0)kanal_fisik_milik,
  nvl(kanal_fisik_mitra,0)kanal_fisik_mitra,
  nvl(kanal_elektronik_dan_digital,0)kanal_elektronik_dan_digital
  from
  (     
      select 
      a.kode,
      a.keterangan bulan,
      NILAI_NPS nilai_jawaban,
      b.nama_kanal_segmen_survey
      from ms.ms_lookup@to_kn a
      left join(
                 select 
                    to_char(blth_kirim_survey,'mm') bulan,
                    (select nama_kanal_segmen_survey from BIGDATA.F_PPK_ESV_KODE_KANAL_SEGMEN R where R.kode_kanal_segmen_survey = A.kode_kanal_segmen_survey) nama_kanal_segmen_survey,
                    NILAI_NPS
                    FROM BIGDATA.F_PPK_ESV_SURVEY_DASHBOARD A     
                    WHERE tahun_kirim_survey = '{$ls_tahun}'
      )b
      on a.kode = b.bulan
      where a.tipe = 'BULAN'      
  )
  pivot
  (max(nilai_jawaban)
  for nama_kanal_segmen_survey in ('KANAL FISIK MILIK' as KANAL_FISIK_MILIK, 'KANAL FISIK MITRA' as KANAL_FISIK_MITRA, 'KANAL ELEKTRONIK DAN DIGITAL' as KANAL_ELEKTRONIK_DAN_DIGITAL))
  order by kode
  


  --
  --select
  --kode,
  --bulan,
  --nvl(kanal_fisik_milik,0)kanal_fisik_milik,
  --nvl(kanal_fisik_mitra,0)kanal_fisik_mitra,
  --nvl(kanal_elektronik_dan_digital,0)kanal_elektronik_dan_digital
  --from
  --(      
  --    select 
  --    a.kode,
  --    a.keterangan bulan,
  --    avg(nvl(b.nilai_jawaban_promoters,0)-nvl(b.nilai_jawaban_detractors,0))nilai_jawaban,
  --    b.nama_kanal_segmen_survey
  --    from ms.ms_lookup@to_kn a
  --    left join(
  --            select 
  --            a.kode_survey,
  --            (select nama_kanal_segmen_survey from bigdata.f_ppk_esv_kode_kanal_segmen where kode_kanal_segmen_survey = b.kode_kanal_segmen_survey) nama_kanal_segmen_survey,
  --            a.kode_survey_responden,
  --            to_char(tgl_kirim_survey,'mm') bulan,
  --            (select avg(nilai_jawaban) from bigdata.f_ppk_esv_survey_jawaban r where kode_survey_responden = a.kode_survey_responden and exists( select 1 from bigdata.f_ppk_esv_kode_jawaban where kode_jawaban = r.kode_jawaban and keterangan = 'PROMOTERS'))nilai_jawaban_promoters,
  --            (select avg(nilai_jawaban) from bigdata.f_ppk_esv_survey_jawaban r where kode_survey_responden = a.kode_survey_responden and exists( select 1 from bigdata.f_ppk_esv_kode_jawaban where kode_jawaban = r.kode_jawaban and keterangan = 'DETRACTORS'))nilai_jawaban_detractors
  --            from bigdata.f_ppk_esv_survey_responden a
  --            inner join bigdata.f_ppk_esv_survey b on a.kode_survey = b.kode_survey
  --             where
  --                      exists(
  --                          select 1  from bigdata.f_ppk_esv_survey_jawaban where kode_survey_responden = a.kode_survey_responden and  kode_jawaban in ('EJ14','EJ15','EJ16','EJ17','EJ18','EJ19','EJ20','EJ21','EJ22','EJ23','EJ24')
  --                      )
  --                      and to_char(tgl_survey_mulai, 'rrrr') = '{$ls_tahun}' 
  --    )b
  --    on a.kode = b.bulan
  --    where a.tipe = 'BULAN'
  --    group by keterangan, nama_kanal_segmen_survey, kode
  --)
  --pivot
  --(max(nilai_jawaban)
  --for nama_kanal_segmen_survey in ('KANAL FISIK MILIK' as KANAL_FISIK_MILIK, 'KANAL FISIK MITRA' as KANAL_FISIK_MITRA, 'KANAL ELEKTRONIK DAN DIGITAL' as KANAL_ELEKTRONIK_DAN_DIGITAL))
  --order by kode
  
  ";

// var_dump($sql);
// exit();

  $ESVDB->parse($sql);
  if($ESVDB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $ESVDB->nextrow()){      
      $jdata[] = $data;
      $i++;
      $itotal++;
    }
    $jsondata["ret"] = "1";
    $jsondata["recordsFiltered"] = $itotal;
    $jsondata["data"] = $jdata;
    $jsondata["msg"] = $sql;
    echo json_encode($jsondata);
  } else {
    $jsondata["ret"] = "-1";
    $jsondata["msg"] = "Proses gagal, tidak ada data yang ditampilkan!";
    echo json_encode($jsondata);
  }    
}else if ($ls_tipe == 'dashboard_daftar_segmen')
{

  $ls_order_by = $_POST["order_by"];
  $ls_order_type = $_POST["order_type"];

  $ls_page = "1";
  $ls_page_item = "10";

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

  $ls_order_condition = "";
  if ($ls_order_by != "" && $ls_order_type != ""  ){    
   $ls_order_condition = "order by  {$ls_order_by} {$ls_order_type}  ";
  }

  $sql = "
  SELECT
A.NAMA_KANAL_SEGMEN_SURVEY,
COUNT(distinct B.KODE_KANAL_JENIS_SURVEY)JUMLAH_JENIS_KANAL,
SUM(B.TOTAL_RESPONDEN)TOTAL_RESPONDEN,
round(avg(B.NILAI_EFEKTIVITAS),2) NILAI_EFEKTIVITAS,
round(avg(B.NILAI_KEPUASAN),2) NILAI_KEPUASAN,
round(avg(B.NILAI_NPS),2) NILAI_NPS
FROM
BIGDATA.F_PPK_ESV_KODE_KANAL_SEGMEN A
LEFT JOIN
(
    select 
    TAHUN_KIRIM_SURVEY,
    KODE_KANAL_SEGMEN_SURVEY,
    KODE_KANAL_JENIS_SURVEY,
    TOTAL_RESPONDEN,
    RSTP,
    RTP,
    RC,
    RP,
    RSP,
    RD,
    RPA,
    RPRO,
    NILAI_KEPUASAN,
    NILAI_EFEKTIVITAS,
    RALL,
    TOTAL_PENGIRIMAN,
    NILAI_NPS
    FROM BIGDATA.F_PPK_ESV_SURVEY_DASHBOARD A
    where TAHUN_KIRIM_SURVEY = {$ls_tahun}
)B
ON A.KODE_KANAL_SEGMEN_SURVEY = B.KODE_KANAL_SEGMEN_SURVEY
GROUP BY A.NAMA_KANAL_SEGMEN_SURVEY, A.KODE_KANAL_SEGMEN_SURVEY
ORDER BY A.KODE_KANAL_SEGMEN_SURVEY

  ";

    // var_dump($sql);
    // exit();

  $sql_count = "select count(1) rn from ($sql) where 1=1";
  $sql_query = "select * from ($sql) where 1=1 and no between ".$start." and ".$end . " ";

  $ESVDB->parse($sql_count);
  $ESVDB->execute();
  $row = $ESVDB->nextrow();
  $recordsTotal = (float) $row["RN"];
  
  $pages = ceil($recordsTotal / $ls_page_item);
  $ESVDB->parse($sql);
  if($ESVDB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $ESVDB->nextrow()){
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

}else if ($ls_tipe == 'dashboard_daftar_jenis')
{
  $ls_tahun_jenis =  isset($_POST['tahun_jenis']) ? ($_POST['tahun_jenis']) : "0";

  $ls_order_by = $_POST["order_by"];
  $ls_order_type = $_POST["order_type"];

  $ls_page = "1";
  $ls_page_item = "10";

  $ls_page = is_numeric($ls_page) ? $ls_page : "1";
  $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";
  
  $start = (($ls_page -1) * $ls_page_item) + 1;
  $end = $start + $ls_page_item - 1;

  $ls_order_condition = "";
  if ($ls_order_by != "" && $ls_order_type != ""  ){    
   $ls_order_condition = "order by  {$ls_order_by} {$ls_order_type}  ";
  }

  // var_dump($ls_tahun);
  // exit();

  $sql = "

  select
  rownum no,
   y.*
   from
   (
      SELECT
      A.NAMA_KANAL_JENIS_SURVEY,
      SUM(B.TOTAL_RESPONDEN)TOTAL_RESPONDEN,
      round(avg(B.NILAI_EFEKTIVITAS),2)NILAI_EFEKTIVITAS,
      round(avg(B.NILAI_KEPUASAN),2)NILAI_KEPUASAN,
      round(avg(B.NILAI_NPS),2)NILAI_NPS
      FROM
      BIGDATA.F_PPK_ESV_KODE_KANAL_JENIS A
      LEFT JOIN
      (
         select 
         TAHUN_KIRIM_SURVEY,
         KODE_KANAL_SEGMEN_SURVEY,
         KODE_KANAL_JENIS_SURVEY,
         TOTAL_RESPONDEN,
         RSTP,
         RTP,
         RC,
         RP,
         RSP,
         RD,
         RPA,
         RPRO,
         NILAI_KEPUASAN,
         NILAI_EFEKTIVITAS,
         RALL,
         TOTAL_PENGIRIMAN,
         NILAI_NPS
         FROM BIGDATA.F_PPK_ESV_SURVEY_DASHBOARD A
         where TAHUN_KIRIM_SURVEY = {$ls_tahun_jenis}
      ) B
      ON A.KODE_KANAL_JENIS_SURVEY = B.KODE_KANAL_JENIS_SURVEY
      GROUP BY A.NAMA_KANAL_JENIS_SURVEY , A.KODE_KANAL_JENIS_SURVEY
         ORDER BY A.KODE_KANAL_JENIS_SURVEY
  )y              
  {$ls_order_condition}      
  ";

//   VAR_DUMP($sql);
//   exit();



  $sql_count = "select count(1) rn from ($sql) where 1=1";
  $sql_query = "select * from ($sql) where 1=1 and no between ".$start." and ".$end . " ";

  $ESVDB->parse($sql_count);
  $ESVDB->execute();
  $row = $ESVDB->nextrow();
  $recordsTotal = (float) $row["RN"];
  
  $pages = ceil($recordsTotal / $ls_page_item);
  $ESVDB->parse($sql);
  if($ESVDB->execute()){ 
    $i = 0;
    $itotal = 0;
    $jdata = array();
    while($data = $ESVDB->nextrow()){
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

?>