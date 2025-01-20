<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB        = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER      = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];

function upload_docfile_api($apiIp, $_FILES_UPD, $dir_main, $dir_full, $filename, $ext, $allowed_ext, $maxsize) {
  if($_FILES_UPD['datafile']['error'] > 0){
    $jdata["ret"] = -1;
    $jdata["msg"] = "An error ocurred when uploading.";

    return $jdata;
  } else if (!array_key_exists($ext, $allowed_ext)) {
    $allowed_ext_str = "";
    foreach ($allowed_ext as $key => $value) {
      $allowed_ext_str .= (strlen($allowed_ext_str) > 0 ? ", " : "") . $key;
    }
    $jdata["ret"] = -1;
    $jdata["msg"] = "Please select a valid file format ($allowed_ext_str).";

    return $jdata;
  } else if($_FILES_UPD['datafile']['size'] > $maxsize){
    $maxsizeMB = $maxsize / 1024;
    $jdata["ret"] = -1;
    $jdata["msg"] = "File uploaded exceeds maximum upload size ($maxsizeMB KB).";

    return $jdata;
  }

  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => $apiIp . "/put-object",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => array(
                        'file' => new CurlFile($_FILES_UPD['datafile']['tmp_name'],$_FILES_UPD['datafile']['type'],$_FILES_UPD['datafile']['name']),
                        'namaBucket' => $dir_main,
                        'namaFolder' => $dir_full
                      ),
    CURLOPT_HTTPHEADER => array(
      "Accept: /",
      "Cache-Control: no-cache",
      "Connection: keep-alive",
      "Content-Type: multipart/form-data"
    ),
  ));

  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
    $jdata["ret"] = -1;
    $jdata["msg"] = "cURL Error #:" . $err;
  } else {
    $result = json_decode($response);
    $jdata["ret"] = 0;
    $jdata["msg"] = "Success";
    $jdata["path"] = $result->path;
  }
  return $jdata;
}

function delete_docfile_api($apiIp, $dir_main, $dir_full, $filename){
  $data = array(
    'namaBucket' => $dir_main,
    'namaFolder' => $dir_full,
    'file' => $filename
  );

  $curl = curl_init();

  curl_setopt_array($curl, array(
    CURLOPT_URL => $apiIp . "/object/delete",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
      "Content-Type: application/json"
    ),
  ));
  
  $response = curl_exec($curl);
  $err = curl_error($curl);
  curl_close($curl);

  if ($err) {
    $jdata["ret"] = -1;
    $jdata["msg"] = "cURL Error #:" . $err;
  } else {
    $result = json_decode($response);
    if ($result->ret == "0") {
      $jdata["ret"] = 0;
      $jdata["msg"] = "Success";
    } else {
      $jdata["ret"] = -1;
      $jdata["msg"] = "Delete file storage gagal";
    }
  }
  
  return $jdata;
}

function upload_docfile($_FILES_UPD, $dir_main, $dir_full, $filename, $ext, $allowed_ext, $maxsize) {
  $ls_dok_path_fullname = $dir_full . $filename;

  if (!is_dir($dir_main)) {
    return "Direktori Upload tidak ditemukan." . $dir_main;
  } else if (!is_dir($dir_full)) {
    mkdir($dir_full, 0777, true);
  }
  
  if($_FILES_UPD['datafile']['error'] > 0){
    return "An error ocurred when uploading.";
  } else if (!array_key_exists($ext, $allowed_ext)) {
    $allowed_ext_str = "";
    foreach ($allowed_ext as $key => $value) {
      $allowed_ext_str .= (strlen($allowed_ext_str) > 0 ? ", " : "") . $key;
    }
    return "Please select a valid file format ($allowed_ext_str).";
  } else if($_FILES_UPD['datafile']['size'] > $maxsize){
    $maxsizeMB = $maxsize / 1024;
    return "File uploaded exceeds maximum upload size ($maxsizeMB KB).";
  } else if(file_exists($ls_dok_path_fullname)){
    return "File with that name already exists.";
  } else if(!move_uploaded_file($_FILES_UPD['datafile']['tmp_name'], $ls_dok_path_fullname)){
    return "Proses gagal, gagal upload dokumen!";
  } else {
    return "";
  }
}

function delete_docfile($fullpath_file){
  if (file_exists($fullpath_file)) {
    if (unlink($fullpath_file)) {
      return "1";
    } else {
      return "-1";
    }
  } else {
    return "-1";
  }
}

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $ls_tipe = $_POST["tipe"];

  if ($ls_tipe == "select"){
    $ls_search_by  = $_POST["search_by"];
    $ls_search_txt = $_POST["search_txt"];
    
    $ls_page       = $_POST["page"];
    $ls_page_item  = $_POST["page_item"];
    $ls_page       = is_numeric($ls_page) ? $ls_page : "1";
    $ls_page_item  = is_numeric($ls_page_item) ? $ls_page_item : "10";
    $start         = (($ls_page -1) * $ls_page_item) + 1;
    $end           = $start + $ls_page_item - 1;
  
    $condition = "";
    if ($ls_search_txt != ""){
      if ($ls_search_by == "sc_kode_klaim"){
        $condition = " AND A.KODE_KLAIM = '{$ls_search_txt}' ";
      } else if ($ls_search_by == "sc_npp"){
        $condition = " AND A.NPP = '{$ls_search_txt}' ";
      } else if ($ls_search_by == "sc_nama_bhp"){
        $condition = " AND A.NAMA_BHP LIKE '%{$ls_search_txt}%' ";
      } else if ($ls_search_by == "sc_st_berita_acara"){
        $condition = " AND A.ST_UPLOAD_BERITA_ACARA = '{$ls_search_txt}' ";
      } else if ($ls_search_by == "sc_st_bukti_transfer"){
        $condition = " AND A.ST_UPLOAD_BERITA_ACARA = '{$ls_search_txt}' ";
      } 
    }
    
    $sql = "
    SELECT  ROWNUM NO, A.*
    FROM    (
      SELECT
              A.KODE_KLAIM,
              A.KODE_KANTOR,
              A.KODE_SEGMEN,
              A.KODE_PERUSAHAAN,
              A.KODE_DIVISI,
              A.KODE_PROYEK,
              A.KODE_TK,
              A.NAMA_TK,
              A.KPJ,
              A.NOMOR_IDENTITAS,
              A.JENIS_IDENTITAS,
              A.KODE_KANTOR_TK,
              A.KODE_TIPE_KLAIM,
              TO_CHAR(A.TGL_KLAIM, 'DD-MM-YYYY') TGL_KLAIM,
              TO_CHAR(A.TGL_LAPOR, 'DD-MM-YYYY') TGL_LAPOR,
              TO_CHAR(A.TGL_KEJADIAN, 'DD-MM-YYYY') TGL_KEJADIAN,
              A.KODE_KONDISI_TERAKHIR,
              TO_CHAR(A.TGL_KONDISI_TERAKHIR, 'DD-MM-YYYY') TGL_KONDISI_TERAKHIR,
              TO_CHAR(A.TGL_KEMATIAN, 'DD-MM-YYYY') TGL_KEMATIAN,
              TO_CHAR(C.TGL_PEMBAYARAN, 'DD-MM-YYYY') TGL_PEMBAYARAN,
              C.NOM_PEMBAYARAN,
              B.NAMA_PENERIMA NAMA_BHP,
              B.NAMA_PENERIMA,
              B.BANK_PENERIMA,
              B.NO_REKENING_PENERIMA,
              B.NAMA_REKENING_PENERIMA,
              (SELECT NAMA_KANTOR FROM KN.MS_KANTOR B WHERE B.KODE_KANTOR = A.KODE_KANTOR AND ROWNUM = 1) NAMA_KANTOR,
              (SELECT B.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN B WHERE B.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN) NAMA_PERUSAHAAN,
              (SELECT B.NPP FROM KN.KN_PERUSAHAAN B WHERE B.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN) NPP,
              CASE 
              WHEN    (
                SELECT  COUNT(1)
                FROM    PN.PN_KLAIM_BHP_DOK_UPLOAD B
                WHERE   B.KODE_KLAIM = A.KODE_KLAIM
                        AND B.KODE_DOKUMEN = 'D301') > 0 THEN 'Y' ELSE 'T' END ST_UPLOAD_BERITA_ACARA,
              CASE 
              WHEN    (
                SELECT  COUNT(1)
                FROM    PN.PN_KLAIM_BHP_DOK_UPLOAD B
                WHERE   B.KODE_KLAIM = A.KODE_KLAIM
                        AND B.KODE_DOKUMEN = 'D302') > 0 THEN 'Y' ELSE 'T' END ST_UPLOAD_BUKTI_TRANSFER,
              CASE 
              WHEN    (
                SELECT  COUNT(1)
                FROM    PN.PN_KLAIM_BHP_CETAK B
                WHERE   B.KODE_KLAIM = A.KODE_KLAIM
                        AND B.KODE_DOKUMEN = 'D303') > 0 THEN 'Y' ELSE 'T' END ST_CETAK_SURAT_REKOM
      FROM    PN.PN_KLAIM A,
              PN.PN_KLAIM_PENERIMA_MANFAAT B,
              PN.PN_KLAIM_PEMBAYARAN C
      WHERE   A.KODE_KLAIM = B.KODE_KLAIM
              AND A.KODE_KLAIM = C.KODE_KLAIM      
              AND A.STATUS_BATAL = 'T'
              AND NVL(C.STATUS_BATAL, 'X') = 'T'
              AND B.KODE_TIPE_PENERIMA = 'BH'
              AND A.KODE_KANTOR IN (
                SELECT  KODE_KANTOR
                FROM    KN.MS_KANTOR AA
                CONNECT BY PRIOR AA.KODE_KANTOR = AA.KODE_KANTOR_INDUK
                START WITH AA.KODE_KANTOR = '$KD_KANTOR')
    ) A
    WHERE   1=1 
            {$condition}";
    
    $sql_count = "select count(1) rn from ($sql) where 1=1";
    $sql_query = "select * from ($sql and rownum <= $end) where 1=1 and no between ".$start." and ".$end . " ";
    
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
  
        // reformat
        $data["NOM_PEMBAYARAN"] = number_format((float)$data["NOM_PEMBAYARAN"],2,".",",");
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
      $jsondata["msg"] = "Sukses";
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
  } else if($ls_tipe=="upload_dokumen") {
    $ls_kode_klaim    = $_POST["kode_klaim"];
    $ls_tipe_dokumen  = $_POST["tipe_dokumen"];
  
    if(!empty($_FILES['datafile']['tmp_name'])) {
      $ls_dok_nama_file = $_FILES["datafile"]["name"];
      $ls_dok_mime_type = $_FILES['datafile']['type'];
  
      $upload_dir_full  = "smile/mod_pn/pn5054";
      $allowed          = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png", "pdf" => "application/pdf");
      $ext              = pathinfo($ls_dok_nama_file, PATHINFO_EXTENSION);
      $maxsize          = 2 * 1024 * 1024; // 2000KB
  
      $ret_upload = upload_docfile_api($wsIpStorage, $_FILES, 'internal', $upload_dir_full, $filename, $ext, $allowed, $maxsize);

      $uploaded = false;
      if ($ret_upload["ret"] == 0) {
        $ls_path_lampiran = $ret_upload["path"];

        $qry = "
        declare
          v_count integer;
          v_nama_dokumen varchar2(100);
    
          v_err integer;
          v_ret varchar2(10);
          v_mess varchar2(1000);
        begin
          v_err := 0;
    
          select  count(1) into v_count
          from    pn.pn_klaim
          where   kode_klaim = :p_kode_klaim;
    
          if v_count = 0 then
            v_err := nvl(v_err, 0) + 1;
            v_ret := '-1';
            v_mess := 'Kode Klaim ' || :p_kode_klaim || ' tidak ditemukan';
          end if;
    
          if v_err = 0 then 
            select  count(1) into v_count
            from    pn.pn_klaim_bhp_dok_upload
            where   kode_klaim = :p_kode_klaim
                    and kode_dokumen = :p_kode_dokumen;
    
            select  nama_dokumen into v_nama_dokumen
            from    pn.pn_kode_dokumen_bhp
            where   kode_dokumen = :p_kode_dokumen;
    
            if v_count = 0 then
              insert into pn.pn_klaim_bhp_dok_upload(
                kode_klaim,
                kode_dokumen,
                no_urut,
                nama_file,
                path_url,
                mime_type,
                petugas_upload,
                tgl_upload,
                keterangan,
                tgl_rekam,
                petugas_rekam
              )values(
                :p_kode_klaim,
                :p_kode_dokumen,
                1,
                :p_nama_file,
                :p_path_url,
                :p_mime_type,
                :p_petugas_upload,
                sysdate,
                v_nama_dokumen,
                sysdate,
                :p_petugas_rekam);
              
              v_mess := 'Sukses';
              v_ret := '1';
            else 
              v_mess := 'Sudah ada dokumen ' || v_nama_dokumen || ' untuk BHP ini!';
              v_ret := '-1';
            end if;
          end if;
    
          select v_mess into :p_mess from dual;
          select v_ret into :p_ret from dual;
    
          exception when others then 
          v_mess := 'Terjadi kesalahan saat upload dokumen, coba beberapa saat lagi!';
          v_ret := '-1';
    
          select v_mess into :p_mess from dual;
          select v_ret into :p_ret from dual;
        end;";
        $proc = $DB->parse($qry);				
        oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 100);
        oci_bind_by_name($proc, ":p_kode_dokumen", $ls_tipe_dokumen, 100);
        oci_bind_by_name($proc, ":p_nama_file", $ls_dok_nama_file, 100);
        oci_bind_by_name($proc, ":p_path_url", $ls_path_lampiran, 1000);
        oci_bind_by_name($proc, ":p_mime_type", $ls_dok_mime_type, 100);
        oci_bind_by_name($proc, ":p_petugas_upload", $username, 100);
        oci_bind_by_name($proc, ":p_petugas_rekam", $username, 100);
        oci_bind_by_name($proc, ":p_ret", $p_ret, 100);
        oci_bind_by_name($proc, ":p_mess", $p_mess, 1000);

        if ($DB->execute()) {
          $ls_no_urut_lampiran = $p_ret_no_urut;
          $ls_ret   = $p_ret;
          $ls_mess  = $p_mess;
          if ($ls_ret == '1') {
            $uploaded = true;
          } else {
            $msg = $ls_mess;
          } 
        } else {
          $msg = "Proses gagal, gagal upload dokumen, cek panjang nama file!";
        }
      } else {
        $msg = $ret_upload["msg"];
      }
      
      if ($uploaded) {
        $jdata["ret"] = "0";
        $jdata["msg"] = "Dokumen pendukung berhasil di-upload!";
      } else {
        $jdata["ret"] = "-1";
        $jdata["msg"] = $msg;
      }
      echo json_encode($jdata);
    } else {
      $jdata["ret"] = "-1";
      $jdata["msg"] = "Proses gagal, Dokumen kosong!";
      echo json_encode($jdata);
    }
  } else if($ls_tipe=="delete_dokumen") {
    $ls_kode_klaim      = $_POST["kode_klaim"];
    $ls_tipe_dokumen    = $_POST["tipe_dokumen"];
  
    $sql = "
    select  path_url
    from    pn.pn_klaim_bhp_dok_upload
    where   kode_klaim = :p_kode_klaim
            and kode_dokumen = :p_kode_dokumen
            and no_urut = 1";
    $proc = $DB->parse($sql);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,30);
    oci_bind_by_name($proc, ":p_kode_dokumen", $ls_tipe_dokumen, 100);
    $DB->execute();
    $row          = $DB->nextrow();
    $ls_path_url  = $row["PATH_URL"];
  
    if ($ls_path_url != "") {
      $arr = explode("/", $ls_path_url);
      $filename = $arr[count($arr) - 1];

      $ret_delete = delete_docfile_api($wsIpStorage, "internal", "smile/mod_pn/pn5054", $filename);

      if ($ret_delete["ret"] == 0) {
        $qry = "
          delete
          from    pn.pn_klaim_bhp_dok_upload
          where   kode_klaim = :p_kode_klaim
                  and kode_dokumen = :p_kode_dokumen
                  and no_urut = 1";
        $proc = $DB->parse($qry);				
        oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 100);
        oci_bind_by_name($proc, ":p_kode_dokumen", $ls_tipe_dokumen, 100);
        
        if ($DB->execute()) {
          $jdata["ret"] = "0";
          $jdata["msg"] = "Dokumen pendukung berhasil di-delete!"; 
        } else {
          $jdata["ret"] = "-1";
          $jdata["msg"] = "Proses gagal, gagal delete dokumen file";
        }
      } else {
        $jdata["ret"] = "-1";
        $jdata["msg"] = $ret_delete["msg"];
      }
      
      echo json_encode($jdata);
    } else {
      $jdata["ret"] = "-1";
      $jdata["msg"] = "Proses gagal, Dokumen kosong!";
      echo json_encode($jdata);
    }
  } else if($ls_tipe=="cetak_dokumen") {
    $ls_kode_klaim      = $_POST["kode_klaim"];
    $ls_tipe_dokumen    = $_POST["tipe_dokumen"];
  
    $sql = "
    select  count(1) jlh_cetak
    from    pn.pn_klaim_bhp_cetak
    where   kode_klaim = :p_kode_klaim
            and kode_dokumen = :p_kode_dokumen";
    $proc = $DB->parse($sql);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,30);
    oci_bind_by_name($proc, ":p_kode_dokumen", $ls_tipe_dokumen, 100);
    $DB->execute();
    $row          = $DB->nextrow();
    $ls_jlh_cetak = $row["JLH_CETAK"];
  
    if ($ls_jlh_cetak == "") {
      $jdata["ret"] = "-1";
      $jdata["msg"] = "Gagal insert status cetak dokumen!"; 
    } else if ($ls_jlh_cetak == 0) {
      $qry = "
      declare 
        v_no_urut integer;
      begin
        select  nvl(max(no_urut),0) into v_no_urut
        from    pn.pn_klaim_bhp_cetak
        where   kode_klaim = :p_kode_klaim
                and kode_dokumen = :p_kode_dokumen;

        insert into pn.pn_klaim_bhp_cetak(
          kode_klaim,
          kode_dokumen,
          no_urut,
          petugas_cetak,
          tgl_cetak,
          keterangan,
          tgl_rekam,
          petugas_rekam) 
        values(
          :p_kode_klaim,
          :p_kode_dokumen,
          (v_no_urut + 1),
          :p_petugas_cetak,
          sysdate,
          null,
          sysdate,
          :p_petugas_rekam);

      end; ";
      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 100);
      oci_bind_by_name($proc, ":p_kode_dokumen", $ls_tipe_dokumen, 100);
      oci_bind_by_name($proc, ":p_petugas_cetak", $USER, 100);
      oci_bind_by_name($proc, ":p_petugas_rekam", $USER, 100);
      
      if ($DB->execute()) {
        $jdata["ret"] = "0";
        $jdata["msg"] = "Berhasil insert status cetak dokumen!"; 
      } else {
        $jdata["ret"] = "-1";
        $jdata["msg"] = "Gagal insert status cetak dokumen!"; 
      }
    } else {
      $jdata["ret"] = "-1";
      $jdata["msg"] = "Dokumen sudah pernah dicetak sebelumnya"; 
    }
    echo json_encode($jdata);
  } else {
    $jdata["ret"] = "-1";
    $jdata["msg"] = "Tipe action tidak ditemukan!";
    json_encode($jdata);
  }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $ls_tipe = $_GET["tipe"];

  if($ls_tipe=='download_dokumen') {
    $ls_kode_klaim      = $_GET["kode_klaim"];
    $ls_tipe_dokumen    = $_GET["tipe_dokumen"];
  
    $sql = "
    select  path_url,
            nama_file
    from    pn.pn_klaim_bhp_dok_upload
    where   kode_klaim = :p_kode_klaim
            and kode_dokumen = :p_kode_dokumen
            and no_urut = 1";
    $proc = $DB->parse($sql);
    oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,30);
    oci_bind_by_name($proc, ":p_kode_dokumen", $ls_tipe_dokumen, 100);
    $DB->execute();
    $row          = $DB->nextrow();
    $ls_path_url  = $row["PATH_URL"];
    $ls_nama_file = $row["NAMA_FILE"];
    
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="'.basename($ls_nama_file).'"');
    echo file_get_contents($wsIpStorage . $ls_path_url);
    exit;
  } else if($tipe=='cetak_dokumen') {
    $ls_kode_klaim = $_GET["kode_klaim"];
    
    require_once '../../includes/fungsi_newrpt.php';
    ?>
    <script type="text/javascript" src="../../javascript/calendar.js"></script>
    <script language="javascript">
    </script>
    <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
    <?
    $ls_tipe_cetak = "PDF";
    $ls_modul      = "pn";
    $ls_nama_rpt   = "PNRBHP001.rdf"; 
    $ls_user_param = " P_KODE_KLAIM='" . $ls_kode_klaim . "'";
                      
    exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $ls_tipe_cetak);
    echo '<script language="JavaScript" type="text/javascript">';
    echo 'window.close();';
    echo '</script>';
    exit();
  } else {
    $jdata["ret"] = "-1";
    $jdata["msg"] = "Tipe action tidak ditemukan!";
    json_encode($jdata);
  }
}
?>