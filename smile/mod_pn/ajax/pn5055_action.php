<?php
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$DB         = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$KD_KANTOR  = $_SESSION['kdkantorrole'];
$USER       = $_SESSION["USER"];
$KODE_ROLE  = $_SESSION['regrole'];

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
    $ls_search_status_tindaklanjut = $_POST["search_status_tindaklanjut"];
    $ls_search_status_pelunasan = $_POST["search_status_pelunasan"];
    $ls_search_by = $_POST["search_by"];
    $ls_search_txt = $_POST["search_txt"];

    $ls_page = $_POST["page"];
    $ls_page_item = $_POST["page_item"];

    $ls_page = is_numeric($ls_page) ? $ls_page : "1";
    $ls_page_item = is_numeric($ls_page_item) ? $ls_page_item : "10";

    $start = (($ls_page -1) * $ls_page_item) + 1;
    $end = $start + $ls_page_item - 1;

    $condition = "";
    if ($ls_search_txt != ""){
      if ($ls_search_by == "sc_npp"){
        $condition = " AND A.NPP LIKE '%{$ls_search_txt}%' ";
      } else if ($ls_search_by == "sc_nama_perusahaan"){
        $condition = " AND A.NAMA_PERUSAHAAN LIKE '%{$ls_search_txt}%' ";
      } else if ($ls_search_by == "sc_kpj"){
        $condition = " AND A.KPJ LIKE '%{$ls_search_txt}%' ";
      } else if ($ls_search_by == "sc_kode_pending"){
        $condition = " AND A.KODE_PENDING_RLX LIKE '%{$ls_search_txt}%' ";
      }
    }

    $condition_others = "";
    if ($ls_search_status_pelunasan != "") {
      if ($ls_search_status_pelunasan == "LUNAS") {
        $condition_others = " AND A.STATUS_PELUNASAN = 'LUNAS' ";
      } else if ($ls_search_status_pelunasan == "BELUM LUNAS") {
        $condition_others = " AND A.STATUS_PELUNASAN = 'BELUM LUNAS'";
      }
    }

    $filter_status_tindaklanjut = "";
    if ($ls_search_status_tindaklanjut != "") {
      if ($ls_search_status_tindaklanjut == "BELUM DIPROSES") {
        $filter_status_tindaklanjut = " AND NVL(A.STATUS_TINDAKLANJUT, 'T') = 'T' ";
      } else if ($ls_search_status_tindaklanjut == "SUDAH DIPROSES") {
        $filter_status_tindaklanjut = " AND NVL(A.STATUS_TINDAKLANJUT, 'T') = 'Y'";
      }
    }

    $filter_kode_kantor = " AND A.KODE_KANTOR_TINDAKLANJUT = '$KD_KANTOR' ";

    $sql = "
    select  rownum no, a.*
    from    (
      select  a.*,
              (
              case
              when a.jml_bln_iur_rlx > a.jml_bln_iur_lunas then 
				case
					when sysdate > a.tgl_akhir_pelunasan_rlx_jp1  then
						'BELUM LUNAS LEWAT MASA PELUNASAN'
					else
						'BELUM LUNAS'
				end
              when a.jml_bln_iur_rlx = a.jml_bln_iur_lunas then 'LUNAS'
              else '-' end
              ) status_pelunasan
      from    (
        select  a.kode_pending_rlx,
                a.kpj,
                a.nama_tk,
                a.id_pointer_asal,
                to_char(a.tgl_kejadian, 'dd-mm-yyyy') tgl_kejadian,
                to_char(a.tgl_pengajuan, 'dd-mm-yyyy') tgl_pengajuan,
                a.jml_bln_kepesertaan,
                a.jml_bln_iur,
                (
                  select  count(1) jml_bln_iur_rlx
                  from    kn.kn_iuran_perusahaan aa
                  where   nvl(aa.status_rekon, 'T') = 'Y'
                          and aa.kode_segmen = a.kode_segmen_rlx
                          and aa.kode_perusahaan = a.kode_perusahaan_rlx
                          and aa.kode_divisi = a.kode_divisi_rlx
                          and nvl(aa.flg_rlx_jpn, 'T') = 'Y'
						  and to_char(aa.blth,'yyyymm') <= to_char(a.tgl_kejadian,'yyyymm')
                ) jml_bln_iur_rlx,
                (
                select  count(1) jml_bln_iur_lunas 
                from    kn.kn_iuran_perusahaan_rlx aa
                where   nvl(aa.status_lunas, 'T') = 'Y'
                        and aa.kode_segmen = a.kode_segmen_rlx
                        and aa.kode_perusahaan = a.kode_perusahaan_rlx
                        and aa.kode_divisi = a.kode_divisi_rlx
						and to_char(aa.blth,'yyyymm') <= to_char(a.tgl_kejadian,'yyyymm')
                ) jml_bln_iur_lunas,
                to_char(a.blth_awal_rlx, 'mm-yyyy') blth_awal_rlx,
                to_char(a.blth_akhir_rlx, 'mm-yyyy') blth_akhir_rlx,
                a.kode_segmen_rlx,
                a.kode_perusahaan_rlx,
                a.kode_divisi_rlx,
                (
                select  npp
                from    kn.kn_perusahaan aa
                where   aa.kode_perusahaan = a.kode_perusahaan_rlx
                ) npp,
                (
                  select  nama_perusahaan
                  from    kn.kn_perusahaan aa
                  where   aa.kode_perusahaan = a.kode_perusahaan_rlx
                ) nama_perusahaan,
                (
                  select  pembina
                  from    kn.kn_kepesertaan_prs aa
                  where   aa.kode_kepesertaan = a.kode_kepesertaan_rlx
                          and aa.aktif = 'Y'
                          and rownum = 1
                ) kode_pembina_rlx,
                a.status_tindaklanjut,
                a.petugas_tindaklanjut,
                a.tgl_tindaklanjut,
                a.kode_kantor_tindaklanjut,
                a.kode_klaim_tindaklanjut,
                a.status_batal,
                a.tgl_batal,
                a.petugas_batal,
                a.kode_sumber_data,
                a.tgl_rekam,
                a.petugas_rekam,
                a.tgl_ubah,
                a.petugas_ubah,
                (
                  select  to_char(tgl_akhir_pelunasan, 'dd-mm-yyyy') tgl_akhir_pelusanan 
                  from    kn.kn_tarif_relaksasi 
                  where   kd_prg = '4'
                ) tgl_akhir_pelunasan_rlx_jp,
				(
                  select  tgl_akhir_pelunasan
                  from    kn.kn_tarif_relaksasi 
                  where   kd_prg = '4'
                ) tgl_akhir_pelunasan_rlx_jp1
        from    pn.pn_klaim_relaksasi a
      ) a
      where   1=1
              and nvl(a.status_batal, 'T') = 'T'
              {$filter_kode_kantor}
              ) A
    where   1=1
            {$condition}
            {$filter_status_tindaklanjut}
            {$condition_others}";
    
    $sql_count = "select count(1) rn from ($sql) where 1=1";
    $sql_query = "select * from ($sql and rownum <= $end) where 1=1 and no between ".$start." and ".$end . " ";

    $DB->parse($sql_count);
    $DB->execute();
    $row = $DB->nextrow();
    $recordsTotal = (float) $row["RN"];

    if ($recordsTotal == 0) {
      $jsondata["ret"] = "0";
      $jsondata["start"] = "0";
      $jsondata["end"] = "0";
      $jsondata["page"] = "0";
      $jsondata["recordsTotal"] = "0";
      $jsondata["recordsFiltered"] = "0";
      $jsondata["pages"] = "0";
      $jsondata["msg"] = "Data tidak ditemukan!";
      echo json_encode($jsondata);
      exit();
    }

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
  } else if ($ls_tipe == "get_tanggal_akhir_pelusanan") {
    $ls_kode_prg = $_POST["kode_prg"];

    $sql = "
    select  to_char(tgl_akhir_pelunasan, 'dd-mm-yyyy') tgl_akhir_pelusanan 
    from    kn.kn_tarif_relaksasi 
    where   kd_prg = '$ls_kode_prg'";
    
    $DB->parse($sql);
    if($DB->execute()){
      $data = $DB->nextrow();
      $jsondata["ret"] = "1";
      $jsondata["tglAkhirRelaksasi"] = $data["TGL_AKHIR_PELUSANAN"];
      $jsondata["msg"] = "Sukses";
      echo json_encode($jsondata);
    } else {
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Gagal mengambil tanggal akhir relaksasi!";
      echo json_encode($jsondata);
    }
  } else if($ls_tipe == "generate_agenda"){
    $ls_kode_pending_rlx = $_POST["kode_pending_rlx"];

    $qry = "
    begin
        pn.p_pn_pn5055.x_insert_klaim_tindaklanjut(
          :p_kode_pending_rlx,
          :p_kode_kantor_klaim, 
          :p_user,
          :p_sukses,
          :p_mess
        );
    exception when others then
      :p_sukses := '-1';
      :p_mess := 'Procedure gagal diproses';
    end;";

    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_pending_rlx", $ls_kode_pending_rlx, 30);
    oci_bind_by_name($proc, ":p_kode_kantor_klaim", $KD_KANTOR, 30);
    oci_bind_by_name($proc, ":p_user", $USER, 30);
    oci_bind_by_name($proc, ":p_sukses", $ls_sukses, 10);
    oci_bind_by_name($proc, ":p_mess", $ls_mess, 1000);

    if($DB->execute()){
      $sukses = $ls_sukses;
      $msg = $ls_mess;
      if ($sukses == "1") {
        $jsondata["ret"] = "1";
        $jsondata["msg"] = "Proses generate agenda klaim selesai";
      } else {
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = $msg;
      }
    } else {
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Gagal Eksekusi";
    }
    echo json_encode($jsondata);
  } else if($ls_tipe == "batal"){
    $ls_kode_pending_rlx = $_POST["kode_pending_rlx"];

    $qry = "
    begin
      pn.p_pn_pn5055.x_batal_klaim_pending(   
        :p_kode_pending_rlx, 
        :p_kode_kantor, 
        :p_user, 
        :p_sukses,
        :p_mess
      );
    exception when others then
      :p_sukses := '-1';
      :p_mess := 'Procedure gagal diproses';
    end;";
    
    $proc = $DB->parse($qry);
    oci_bind_by_name($proc, ":p_kode_pending_rlx", $ls_kode_pending_rlx, 30);
    oci_bind_by_name($proc, ":p_kode_kantor", $KD_KANTOR, 30);
    oci_bind_by_name($proc, ":p_user", $USER, 30);
    oci_bind_by_name($proc, ":p_sukses", $ls_sukses, 10);
    oci_bind_by_name($proc, ":p_mess", $ls_mess, 1000);

    if($DB->execute()){
      $sukses = $ls_sukses;
      $msg = $ls_mess;

      if ($sukses == "1") {
        $jsondata["ret"] = "1";
        $jsondata["msg"] = "Proses batal kode pending klaim selesai";
      } else {
        $jsondata["ret"] = "-1";
        $jsondata["msg"] = $msg;
      }
    } else {
      $jsondata["ret"] = "-1";
      $jsondata["msg"] = "Gagal Eksekusi";
    }
    echo json_encode($jsondata);
  } else {
    $jdata["ret"] = "-1";
    $jdata["msg"] = "Tipe action tidak ditemukan!";
    json_encode($jdata);
  }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
  $jdata["ret"] = "-1";
  $jdata["msg"] = "Tipe action tidak ditemukan!";
  json_encode($jdata);
}

?>
