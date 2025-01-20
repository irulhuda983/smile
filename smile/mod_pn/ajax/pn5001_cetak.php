<script type="text/javascript">
  (function() {
    window._alert = window.alert;
  })();
</script>
<?php
$pagetype   = "report";
$gs_pagetitle = "PN5001 - DOKUMEN KLAIM";

require_once "../../includes/header_app.php";
include_once '../../includes/conf_global.php';
include_once '../../includes/fungsi_newrpt.php';

/* --------------------- Form History -----------------------------------------
  File: kb9001.php

  Deskripsi:
  -----------
  File ini dipergunakan untuk Pencetakan Laporan klaim

  Author:
  --------
  Tim SIJSTK

  Histori Perubahan:
  --------------------
  25/09/2017 - TIM SIJSTK
  Pembuatan Form

  -------------------- End Form History -------------------------------------- */

function encrypt_decrypt($action, $string) {
  /* =================================================
  * ENCRYPTION-DECRYPTION
  * =================================================
  * ENCRYPTION: encrypt_decrypt('encrypt', $string);
  * DECRYPTION: encrypt_decrypt('decrypt', $string) ;
  */

  $output = false;
  $encrypt_method = "AES-256-CBC";
  $secret_key = 'WS-SERVICE-KEY';
  $secret_iv = 'WS-SERVICE-VALUE';
  // hash
  $key = hash('sha256', $secret_key);
  // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
  $iv = substr(hash('sha256', $secret_iv), 0, 16);
  if ($action == 'encrypt') {
  $output = base64_encode(openssl_encrypt($string, $encrypt_method, $key, 0, $iv));
  } else {
  if ($action == 'decrypt') {
    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
  }
  }
  return $output;
}

function entry_aktivitas_klaim($kode_klaim, $ket_submit, $petugas) {
  global $DB;

  $qry = "
  begin 
    sijstk.p_pn_pn5040.x_insert_aktivitas('$kode_klaim', 'CETAK', '$ket_submit', '$petugas',:p_sukses,:p_mess);
  end;";	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_sukses", $p_sukses, 32);
  oci_bind_by_name($proc, ":p_mess", $p_mess, 1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;
}

function GetUrlPathDokSigned ($ls_kode_klaim, $ls_kode_jenis_dokumen,$ls_kode_dokumen) {
	//$path_http = $wsIpStorage;//$CONFIG_GLOBAL["WS_TEST"];
	global $wsIpStorage; //$CONFIG_GLOBAL["WS_IPSTORAGE"];
  global $DB;
  global $HTTP_HOST;
   
  $sql = "
  select  count(*) jml_arsip 
  from    pn.pn_klaim a 
	where   exists
		      (
          select  * 
          from    pn.pn_arsip_dokumen b
          where   a.kode_klaim = b.id_dokumen
                  and kode_jenis_dokumen = '$ls_kode_jenis_dokumen'
                  and kode_dokumen = '$ls_kode_dokumen'
            )
		      and kanal_pelayanan in (
              select  kode 
              from    ms.ms_lookup 
              where   tipe = 'KANALKLM' 
              and kategori = 'DOKUMEN_DIGITAL')
		      and kode_klaim = '$ls_kode_klaim' ";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();		
	$ls_jml_arsip = $row['JML_ARSIP'];

	if ($ls_jml_arsip > 0) {
    $sql = "
    select  id_arsip,
            id_dokumen,
            (
              select  nama_dokumen 
              from    pn.pn_arsip_kode_dokumen x 
              where   x.kode_dokumen = b.kode_dokumen
            ) nama_dokumen,    
            nvl(url_path_dok_signed,url_path_dok) url_path_dok_signed 
    from    pn.pn_arsip_dokumen b
	  where   b. kode_jenis_dokumen = '$ls_kode_jenis_dokumen'
            and kode_dokumen = '$ls_kode_dokumen'
            and b.id_dokumen = '$ls_kode_klaim' ";
	  $DB->parse($sql);
	  $DB->execute();
	  $row = $DB->nextrow();		
    $ls_url_path_dok_signed = $row['URL_PATH_DOK_SIGNED'];
    $ls_nama_doc = $row['NAMA_DOKUMEN'];
    
    // begin enkripsi  
    $encrypt_method = "AES-256-CBC";
    $secret_key = 'WS-SERVICE-KEY';
    $secret_iv = 'WS-SERVICE-VALUE';
    // hash
    $key = hash('sha256', $secret_key);
    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);  
    // end enkripsi

    $param = base64_encode(openssl_encrypt($row['URL_PATH_DOK_SIGNED'], $encrypt_method, $key, 0, $iv));
    $url = $param;
    $ls_url_path_dok_signed = $url."/".$ls_nama_doc;
	}
	
	return $ls_url_path_dok_signed;
}

$ls_user       = $_SESSION["USER"];
$ls_kode_klaim = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];

if (isset($_POST["st_dokumen_administrasi"])) {
  $ls_st_dokumen_administrasi = $_POST['st_dokumen_administrasi'];

  if ($ls_st_dokumen_administrasi == "on" || $ls_st_dokumen_administrasi == "ON" || $ls_st_dokumen_administrasi == "Y") {
    $ls_st_dokumen_administrasi = "Y";
  } else {
    $ls_st_dokumen_administrasi = "T";
  }
}

if (isset($_POST["st_dokumen_administrasi2"])) {
  $ls_st_dokumen_administrasi2 = $_POST['st_dokumen_administrasi2'];

  if ($ls_st_dokumen_administrasi2 == "on" || $ls_st_dokumen_administrasi2 == "ON" || $ls_st_dokumen_administrasi2 == "Y") {
    $ls_st_dokumen_administrasi2 = "Y";
  } else {
    $ls_st_dokumen_administrasi2 = "T";
  }
}

if (isset($_POST["st_pernyataan_berkala"])) {
  $ls_st_pernyataan_berkala = $_POST['st_pernyataan_berkala'];

  if ($ls_st_pernyataan_berkala == "on" || $ls_st_pernyataan_berkala == "ON" || $ls_st_pernyataan_berkala == "Y") {
    $ls_st_pernyataan_berkala = "Y";
  } else {
    $ls_st_pernyataan_berkala = "T";
  }
}

if (isset($_POST["st_history_jp"])) {
  $ls_st_history_jp = $_POST['st_history_jp'];

  if ($ls_st_history_jp == "on" || $ls_st_history_jp == "ON" || $ls_st_history_jp == "Y") {
    $ls_st_history_jp = "Y";
  } else {
    $ls_st_history_jp = "T";
  }
}

if (isset($_POST["st_rincian_jp"])) {
  $ls_st_rincian_jp = $_POST['st_rincian_jp'];

  if ($ls_st_rincian_jp == "on" || $ls_st_rincian_jp == "ON" || $ls_st_rincian_jp == "Y") {
    $ls_st_rincian_jp = "Y";
  } else {
    $ls_st_rincian_jp = "T";
  }
}

if (isset($_POST["st_kkbyr_jp"])) {
  $ls_st_kkbyr_jp = $_POST['st_kkbyr_jp'];

  if ($ls_st_kkbyr_jp == "on" || $ls_st_kkbyr_jp == "ON" || $ls_st_kkbyr_jp == "Y") {
    $ls_st_kkbyr_jp = "Y";
  } else {
    $ls_st_kkbyr_jp = "T";
  }
}

if (isset($_POST["st_pernyataan_npwp"])) {
  $ls_st_pernyataan_npwp = $_POST['st_pernyataan_npwp'];

  if ($ls_st_pernyataan_npwp == "on" || $ls_st_pernyataan_npwp == "ON" || $ls_st_pernyataan_npwp == "Y") {
    $ls_st_pernyataan_npwp = "Y";
  } else {
    $ls_st_pernyataan_npwp = "T";
  }
}

if (isset($_POST["st_history_saldo_tk"])) {
  $ls_st_history_saldo_tk = $_POST['st_history_saldo_tk'];

  if ($ls_st_history_saldo_tk == "on" || $ls_st_history_saldo_tk == "ON" || $ls_st_history_saldo_tk == "Y") {
    $ls_st_history_saldo_tk = "Y";
  } else {
    $ls_st_history_saldo_tk = "T";
  }
}

if (isset($_POST["st_rincian_saldo_tk"])) {
  $ls_st_rincian_saldo_tk = $_POST['st_rincian_saldo_tk'];

  if ($ls_st_rincian_saldo_tk == "on" || $ls_st_rincian_saldo_tk == "ON" || $ls_st_rincian_saldo_tk == "Y") {
    $ls_st_rincian_saldo_tk = "Y";
  } else {
    $ls_st_rincian_saldo_tk = "T";
  }
}

if (isset($_POST["st_lap_penetapan"])) {
  $ls_st_lap_penetapan = $_POST['st_lap_penetapan'];

  if ($ls_st_lap_penetapan == "on" || $ls_st_lap_penetapan == "ON" || $ls_st_lap_penetapan == "Y") {
    $ls_st_lap_penetapan = "Y";
  } else {
    $ls_st_lap_penetapan = "T";
  }
}

if ($ls_kode_klaim != "") {
  // default kanal = 99 -> tanpa dokumen digital
  $sql = "
  select  status_klaim, 
          kode_tipe_klaim,
          substr(kode_tipe_klaim,1,3) jenis_klaim, 
          nvl(status_submit_penetapan,'T') status_submit_penetapan,
  			  nvl(status_submit_agenda,'T') status_submit_agenda, 
          nvl(status_submit_agenda2,'T') status_submit_agenda2,
          case
          when kanal_pelayanan in (
            select kode from ms.ms_lookup where 1=1 and tipe = 'KANALKLM' and kategori = 'DOKUMEN_DIGITAL'
          )
          then kanal_pelayanan
          else '99'
          end kanal_pelayanan
  from    sijstk.pn_klaim 
  where   kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_kode_tipe_klaim         = $row['KODE_TIPE_KLAIM'];
  $ls_jenis_klaim             = $row['JENIS_KLAIM'];
  $ls_status_submit_penetapan = $row['STATUS_SUBMIT_PENETAPAN'];
  $ls_status_submit_agenda    = $row['STATUS_SUBMIT_AGENDA'];
  $ls_status_submit_agenda2   = $row['STATUS_SUBMIT_AGENDA2'];
  $ls_status_klaim            = $row['STATUS_KLAIM'];
  $ls_kanal_pelayanan         = $row['KANAL_PELAYANAN'];
	
	// cek lumsum atau berkala
	$sql_lumsum_berkala = "
  select  kanal_pelayanan, 
          kode_tipe_klaim,
          (
          select  count(*) 
          from    sijstk.pn_klaim_manfaat_detil x, 
                  sijstk.pn_kode_manfaat y
          where   x.kode_klaim = a.kode_klaim
                  and x.kode_manfaat = y.kode_manfaat
                  and nvl(y.flag_berkala,'T')='Y'
                  and nvl(x.nom_biaya_disetujui,0)<>0
          ) cnt_berkala,
          (
          select  count(*) 
          from    sijstk.pn_klaim_manfaat_detil x, 
                  sijstk.pn_kode_manfaat y
          where   x.kode_klaim = a.kode_klaim
                  and x.kode_manfaat = y.kode_manfaat
                  and nvl(y.flag_berkala,'T')='T'
                  and nvl(x.nom_biaya_disetujui,0)<>0
          ) cnt_lumpsum
  from 		pn.pn_klaim a
  where 	kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql_lumsum_berkala);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_cnt_lumpsum = $row["CNT_LUMPSUM"];
  $ls_cnt_berkala = $row["CNT_BERKALA"]; 
  $ls_flag_berkala = $ls_cnt_berkala > 0 ? "Y" : "T";
  $ls_flag_lumpsum = $ls_cnt_lumpsum > 0 ? "Y" : "T";
}

if (isset($_POST["btncetak"])) {
  /* ---------Kirim Parameter---------------------------------------------------- */
  $ls_user_param = "";
  $ls_user_param .= " QUSER='$username'";
  
  //CETAK KELENGKAPAN ADMINISTRASI ---------------------------------------------
  if ($ls_st_dokumen_administrasi == "Y") {
    $ls_nama_rpt="";
    if ($ls_status_submit_agenda == "Y") {
      if ($ls_jenis_klaim == "JKK") {
        $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
        $ls_nama_rpt = "PNR900111.rdf";
        $ls_error = "0";

        $ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM JKK TAHAP I";
      } else if ($ls_jenis_klaim == "JKM") {
        $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
        $ls_nama_rpt = "PNR900114.rdf";
        $ls_error = "0";

        $ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM JKM";
      } else {
        $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
        $ls_nama_rpt = "PNR900101.rdf";
        $ls_error = "0";

        $ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM";
      }

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      // $ls_pdf   = $ls_nama_rpt;
      // exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK KELENGKAPAN ADMINISTRASI AGENDA TAHAP II -----------------------------
  if ($ls_st_dokumen_administrasi2 == "Y") {
    $ls_nama_rpt="";
    if ($ls_status_submit_agenda2 == "Y" && $ls_jenis_klaim == "JKK") {
      $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
      $ls_nama_rpt = "PNR900112.rdf";
      $ls_error = "0";

      $ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM JKK TAHAP II";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //$ls_pdf = $ls_nama_rpt;
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK SURAT PERNYATAAN KLAIM BERKALA ---------------------------------------
  if ($ls_st_pernyataan_berkala == "Y") {
    $ls_nama_rpt="";
    $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
    $ls_user_param .= " QNOKONFIRMASI='0'";

    if ($ls_status_submit_agenda == "Y") {
      $ls_nama_rpt = "PNR900102.rdf";
      $ls_error = "0";

      $ls_ket_submit = "PENCETAKAN SURAT PERNYATAAN KLAIM BERKALA PADA SAAT PROSES " . $ls_status_klaim . " KLAIM";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //$ls_pdf = $ls_nama_rpt;
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);			 
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK HISTORI IURAN JP ---------------------------------------
  if ($ls_st_history_jp == "Y") {
    $ls_nama_rpt="";
    if ($ls_status_submit_agenda == "Y") {
      $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
      $ls_user_param .= " QUSER='$username'";
      $ls_nama_rpt = "PNR900103.rdf";
      $ls_error = "0";

      $ls_ket_submit = "PENCETAKAN HISTORI IURAN JP PADA SAAT PROSES " . $ls_status_klaim . " KLAIM";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //$ls_pdf = $ls_nama_rpt;
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);		
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK HISTORI IURAN JP ---------------------------------------
  if ($ls_st_rincian_jp == "Y") {
    $ls_nama_rpt="";
    if ($ls_status_submit_agenda == "Y") {
      $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
      $ls_user_param .= " QUSER='$username'";
      $ls_nama_rpt = "PNR900104.rdf";
      $ls_error = "0";

      $ls_ket_submit = "PENCETAKAN HISTORI IURAN JP PADA SAAT PROSES " . $ls_status_klaim . " KLAIM";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //$ls_pdf = $ls_nama_rpt;
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);		
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK KK PEMBAYARAN JP BERKALA ---------------------------------------
  if ($ls_st_kkbyr_jp == "Y") {
    $ls_nama_rpt="";
    if ($ls_status_submit_agenda == "Y") {
      $sql = "select to_char(sysdate,'yyyymmdd') as v_tgl from dual ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_tgl = $row["V_TGL"];
										
      $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
      $ls_user_param .= " QTGL='$ls_tgl'";
			$ls_user_param .= " QUSER='$username'";
      $ls_nama_rpt = "PNR503050.rdf";
      $ls_error = "0";

      $ls_ket_submit = "PENCETAKAN KERTAS KERJA PEMBAYARAN JP BERKALA";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //$ls_pdf = $ls_nama_rpt;
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);		
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }
				
  $ls_user_param = "";
  //CETAK SURAT PERNYATAAN NPWP ---------------------------------------
  if ($ls_st_pernyataan_npwp == "Y") {
    $ls_nama_rpt="";
    $ls_kode_kantor = $_SESSION['kdkantorrole'];
    $ls_user_param .= " P_KODE_KLAIM='$ls_kode_klaim'";
    $ls_user_param .= " P_KODE_KANTOR='$ls_kode_kantor'";

    if ($ls_status_submit_agenda == "Y") {
      $ls_nama_rpt = "PNR900116.rdf";
      $ls_error = "0";
      $ls_modul = "pn";
      $tipe = "PDF";

      $ls_ket_submit = "PENCETAKAN SURAT PERNYATAAN NPWP PADA SAAT PROSES " . $ls_status_klaim . " KLAIM";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      //$ls_pdf = $ls_nama_rpt;
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);			 
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK HISTORI SALDO TENAGA KERJA ---------------------------------------
  if ($ls_st_history_saldo_tk == "Y") {
    $ls_nama_rpt="";
    $sql = "
    select  kode_perusahaan,
            kode_segmen, 
            kode_tk, 
            to_char(tgl_klaim,'YYYY') tahun_saldo 
    from    sijstk.pn_klaim 
    where   kode_klaim = '$ls_kode_klaim' ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $p_kode_perusahaan = $row['KODE_PERUSAHAAN'];
    $p_kode_segmen = $row['KODE_SEGMEN'];
    $p_kode_tk = $row['KODE_TK'];
		$p_tahun_saldo = $row['TAHUN_SALDO'];

    $ls_user_param .= " P_KODE_PERUSAHAAN='$p_kode_perusahaan'";
    $ls_user_param .= " P_KODE_SEGMEN='$p_kode_segmen'";
    $ls_user_param .= " P_KODE_TK='$p_kode_tk'";
		$ls_user_param .= " P_TAHUN='$p_tahun_saldo'";

    if ($ls_status_submit_agenda == "Y") {
      $ls_nama_rpt = "PNR900117.rdf";
      $ls_error = "0";

      $ls_ket_submit = "PENCETAKAN HISTORI SALDO TENAGA KERJA PADA SAAT PROSES " . $ls_status_klaim . " KLAIM";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------------								
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
      //$ls_pdf = $ls_nama_rpt;
      //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);			 
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }
	
	$ls_user_param = "";
  //CETAK RINCIAN SALDO TENAGA KERJA ---------------------------------------
  if ($ls_st_rincian_saldo_tk == "Y") {
    $ls_nama_rpt="";
    $sql = "
    select  kode_perusahaan,
            kode_segmen, 
            kode_tk, 
            to_char(tgl_klaim,'YYYY') tahun_saldo 
    from    sijstk.pn_klaim 
    where   kode_klaim = '$ls_kode_klaim'";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $p_kode_perusahaan = $row['KODE_PERUSAHAAN'];
    $p_kode_segmen = $row['KODE_SEGMEN'];
    $p_kode_tk = $row['KODE_TK'];
		$p_tahun_saldo = $row['TAHUN_SALDO'];
		
		$ls_modul    = "pn";
    $ls_user_param ="P_KODE_SEGMEN='PU' P_TAHUN='".$p_tahun_saldo."' P_KODE_PERUSAHAAN='".$p_kode_perusahaan."' P_KODE_TK='".$p_kode_tk."' P_USER='".$ls_user."' ";

    if ($ls_status_submit_agenda == "Y") {
      $ls_nama_rpt = "KNR3315.rdf";
      $ls_error = "0";
			
			if($p_kode_segmen=='BPU') {
				$ls_nama_rpt = "KNR3315BPU.rdf";
				$ls_error = "0";
			
				$ls_modul = "pn";
				$ls_user_param = "P_KODE_SEGMEN='BPU' P_TAHUN='".$p_tahun_saldo."' P_KODE_PERUSAHAAN='".$p_kode_perusahaan."' P_KODE_TK='".$p_kode_tk."' P_USER='".$ls_user."' ";
			}

      $ls_ket_submit = "PENCETAKAN RINCIAN SALDO TENAGA KERJA PADA SAAT PROSES " . $ls_status_klaim . " KLAIM";

      entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
      
      //cetak laporan ----------------------------------------------------							
      $ls_modul = 'pn';
      $tipe = 'PDF';
      exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);		 
    } else {
      $ls_error = "1";
      $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";
    }
  }

  $ls_user_param = "";
  //CETAK REPORT PENETAPAN SETELAH SUBMIT PENETAPAN---------------------------------------------------------------
  $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
  if ($ls_st_lap_penetapan == "Y") {
    $ls_nama_rpt="";
    //penetapan jp berkala -----------------------------------------------------
    if ($ls_flag_berkala == "Y") {
      if ($ls_status_submit_penetapan == "Y") {
        $ls_nama_rpt .= "PNR900105.rdf";
        $ls_error = "0";
        $ls_ket_submit = "PENCETAKAN PENETAPAN JP BERKALA PADA SETELAH PROSES PENETAPAN KLAIM";

        entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
        
        //cetak laporan ----------------------------------------------------------								
        $ls_pdf = $ls_nama_rpt;
        exec_rpt_enc_new(1, 'pn', $ls_nama_rpt, $ls_user_param, 'PDF');
        //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);				 
      } else {
        $ls_error = "1";
        $msg = "Penetapan Klaim belum disubmit, belum dapat dilakukan pencetakan laporan penetapan...!!!";
      }
    }

    //penetapan jp lumpsum	
    if ($ls_flag_lumpsum == "Y") {
      if ($ls_status_submit_penetapan == "Y") {
        if ($ls_jenis_klaim == "JPN") {
          $ls_nama_rpt = "PNR900106.rdf";
          $ls_error = "0";
          $ls_ket_submit = "PENCETAKAN PENETAPAN JP LUMPSUM SETELAH PROSES PENETAPAN KLAIM";
        } else if ($ls_jenis_klaim == "JKK") {
          $ls_nama_rpt = "PNR900113.rdf";
          $ls_error = "0";
          $ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JKK SETELAH PROSES PENETAPAN KLAIM";
        } else if ($ls_jenis_klaim == "JKM") {
          $ls_nama_rpt = "PNR900115.rdf";
          $ls_error = "0";
          $ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JKM SETELAH PROSES PENETAPAN KLAIM";
        } else if ($ls_jenis_klaim == "JHT") {
          $ls_nama_rpt = "PNR900118.rdf";
          $ls_error = "0";
          $ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JHT SETELAH PROSES PENETAPAN KLAIM";
        } else if ($ls_jenis_klaim == "JHM") {
          $ls_nama_rpt = "PNR900119.rdf";
          $ls_error = "0";
          $ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JHT/JKM SETELAH PROSES PENETAPAN KLAIM";
        }

        entry_aktivitas_klaim($ls_kode_klaim, $ls_ket_submit, $username);
        
        //cetak laporan ----------------------------------------------------------								
        $ls_pdf = $ls_nama_rpt;
				$tipe = 'PDF';
        //exec_rpt_enc_new(1, 'pn', $ls_nama_rpt, $ls_user_param, 'PDF');
        exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
        //exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);				 
      } else {
        $ls_error = "1";
        $msg = "Penetapan Klaim belum disubmit, belum dapat dilakukan pencetakan laporan penetapan...!!!";
      }
    }
  }
}
//--------------------- end button action ------------------------------------
//--------------------- fungsi lokal javascript ------------------------------
?>

<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>

<script language="JavaScript">
  function fl_js_set_st_dokumen(el) {
    var elem_id = el.id;
    var form = document.adminForm;
    if (el.checked) {
      form.getElementById(elem_id).value = 'Y';
    } else {
      form.getElementById(elem_id).value = 'T';
    }
  }

	function show_arsip(id_arsip_bundel) {
		NewWindow('<?=$wsIpArsip?>/qr/' + id_arsip_bundel,'',800,500,1);
	}
	
	function show_dokumen_digital(path_dokumen_digital) {
    if (path_dokumen_digital == '') {
      return window._alert('Dokumen digital belum terbentuk, silakan ke menu Monitoring Dokumen Digital untuk proses lebih lanjut.');
    }

    let strArray = path_dokumen_digital.split("/");
    let path = strArray[0];
    let namafile = strArray[1];
    let wsLinkDownload  = "<?php echo $wsIpDokumenAntrian ?>";

    NewWindow( wsLinkDownload+path,'',900,700,1);
	}
</script>		

<?php
//--------------------- end fungsi lokal javascript --------------------------	
?>

<div id="header-popup">	
  <h3><?= $gs_pagetitle; ?></h3>
</div>

<div id="container-popup">
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	

  <?php
  //Nilai Default --------------------------------------------------------------													
  //End Nilai Default ----------------------------------------------------------
  ?>				
  <div id="formframe" style="width:300px;">
    <span id="dispError1" style="display:none;color:red;width:250px;"></span>
    <input type="hidden" id="st_errval1" name="st_errval1">
    <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?= $ls_kode_klaim; ?>">
    <div id="formKiri" style="width:300px;">
      <fieldset style="width:300px;"><legend>Jenis Laporan</legend>	
        <?php
        $sql = "
        select  count(1) n_arsip_dokumen,
                max(
                  (
                  select 	id_arsip_bundel_stamp 
                  from 		pn.pn_arsip_dokumen_bundel aa
                  where 	aa.id_arsip_bundel = a.id_arsip_bundel
                  )
                ) id_arsip_bundel_stamp
        from    pn.pn_arsip_dokumen a
        where   a.id_dokumen = '$ls_kode_klaim' ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_n_arsip_dokumen       = $row["N_ARSIP_DOKUMEN"];
        $ls_id_arsip_bundel_stamp = $row["ID_ARSIP_BUNDEL_STAMP"];

        if ($ls_kode_tipe_klaim == "JPN01") {
          $ls_flag_lumpsum = $ls_flag_berkala == "T" ? "Y" : $ls_flag_lumpsum;
        } else {
          $ls_flag_lumpsum = "Y";
        }

        $sql = "
        select  kode_tipe_klaim,
                kode_jenis_dokumen_arsip,
                kode_dokumen_arsip,
                kode_kanal_pelayanan,
                keterangan,
                replace(replace(lower(keterangan), ' ', '_'), '/', '_') id_elemen,
                status_lumpsum,
                status_tampil,
                status_cetak, 
                status_digital
        from    pn.pn_kode_dokumen_cetak
        where   kode_kanal_pelayanan = '$ls_kanal_pelayanan'
                and kode_tipe_klaim = '$ls_kode_tipe_klaim'
                and status_nonaktif = 'T'
                and status_lumpsum = '$ls_flag_lumpsum'
        order by no_urut asc
        ";
        $DB->parse($sql);
        $DB->execute();
        $arr_data = array();
        while($row = $DB->nextrow()) {
          array_push($arr_data, $row);
        }
        foreach($arr_data as $row) {
          $ls_rec_kode_tipe_klaim          = $row["KODE_TIPE_KLAIM"];
          $ls_rec_kode_jenis_dokumen_arsip = $row["KODE_JENIS_DOKUMEN_ARSIP"];
          $ls_rec_kode_dokumen_arsip       = $row["KODE_DOKUMEN_ARSIP"];
          $ls_rec_kode_kanal_pelayanan     = $row["KODE_KANAL_PELAYANAN"];
          $ls_rec_keterangan               = $row["KETERANGAN"];
          $ls_rec_id_elemen                = $row["ID_ELEMEN"];
          $ls_rec_status_lumpsum           = $row["STATUS_LUMPSUM"];
          $ls_rec_status_tampil            = $row["STATUS_TAMPIL"];
          $ls_rec_status_cetak             = $row["STATUS_CETAK"];
          $ls_rec_status_digital           = $row["STATUS_DIGITAL"];

          // hardcode untuk element_id (supaya gak ubah banyak)
          switch ($ls_rec_id_elemen) {
            case 'dokumen_kelengkapan_persyaratan':
              $ls_rec_id_elemen = 'st_dokumen_kelengkapan_persyaratan';
              break;
            case 'tanda_terima_dokumen_agenda_jht':
              $ls_rec_id_elemen = 'st_dokumen_administrasi';
              break;
            case 'tanda_terima_dokumen_agenda_jkm':
              $ls_rec_id_elemen = 'st_dokumen_administrasi';
              break;
            case 'tanda_terima_dokumen_agenda_jkk_tahap_i':
              $ls_rec_id_elemen = 'st_dokumen_administrasi';
              break;
            case 'tanda_terima_dokumen_agenda_jkk_tahap_ii':
              $ls_rec_id_elemen = 'st_dokumen_administrasi2';
              break;
            case 'tanda_terima_dokumen_kelengkapan_administrasi':
              $ls_rec_id_elemen = 'st_dokumen_administrasi';
              break;
            case 'tanda_terima_dokumen_agenda_jht_jkm':
              $ls_rec_id_elemen = 'st_dokumen_administrasi';
              break;
            case 'surat_pernyataan_npwp':
              $ls_rec_id_elemen = 'st_pernyataan_npwp';
              break;
            case 'histori_saldo_tenaga_kerja':
              $ls_rec_id_elemen = 'st_history_saldo_tk';
              break;
            case 'rincian_saldo_tenaga_kerja':
              $ls_rec_id_elemen = 'st_rincian_saldo_tk';
              break;
            case 'histori_iuran_jp':
              $ls_rec_id_elemen = 'st_history_jp';
              break;
            case 'rincian_iuran_jp':
              $ls_rec_id_elemen = 'st_rincian_jp';
              break;
            case 'dokumen_catatan_verifikasi':
              $ls_rec_id_elemen = 'st_catatan_verifikasi';
              break;
            case 'dokumen_elaborasi_pmp':
              $ls_rec_id_elemen = 'st_elaborasi_pmp';
              break;
            case 'penetapan_klaim':
              $ls_rec_id_elemen = 'st_lap_penetapan';
              break;
            case 'voucher':
              $ls_rec_id_elemen = 'st_dokumen_voucher';
              break;
            case 'kwitansi':
              $ls_rec_id_elemen = 'st_dokumen_kwitansi';
              break;
            case 'surat_perintah_bayar':
              $ls_rec_id_elemen = 'st_dokumen_spb';
              break;
            case 'bukti_potong_pph21':
              $ls_rec_id_elemen = 'st_dokumen_pph21';
              break;
            case 'surat_pernyataan_klaim_jp_berkala':
              $ls_rec_id_elemen = 'st_pernyataan_berkala';
              break;
            case 'kertas_kerja_pembayaran_jp_berkala':
              $ls_rec_id_elemen = 'st_kkbyr_jp';
              break;
            default:
              break;
          }

          if ($ls_rec_status_tampil == "Y" || $ls_rec_status_cetak == "Y") {
            $ls_elem_disabled = $ls_rec_status_cetak == "T" ? "disabled" : "";
            $ls_elem_checked  = strtoupper($_POST[$ls_rec_id_elemen]);
            $ls_elem_checked  = $ls_elem_checked == "Y" || $ls_elem_checked == "ON" ? "checked" : "";
            
            if ($ls_rec_status_digital == "Y") {
              $ls_url_dokumen_digital = GetUrlPathDokSigned($ls_kode_klaim, $ls_rec_kode_jenis_dokumen_arsip, $ls_rec_kode_dokumen_arsip);
              
              // // buka cetak ulang kalo gagal bentuk dokumen digital
              // if (!isset($ls_url_dokumen_digital)) {
              //   $ls_elem_disabled = "";
              // }

              echo '<div class="form-row_kiri">';
              echo '<input type="checkbox" id="'.$ls_rec_id_elemen.'" name="'.$ls_rec_id_elemen.'" class="cebox" onclick="fl_js_set_st_dokumen(this)" '.$ls_elem_disabled.' '.$ls_elem_checked.'>';
              echo '<i><font color="#009999"><b> <a href="#" onclick="show_dokumen_digital(\''.$ls_url_dokumen_digital.'\')">'.$ls_rec_keterangan.'</a></b></font></i>';
              echo '</div>';
              echo '<div class="clear"></div>';
            } else {
              echo '<div class="form-row_kiri">';
              echo '<input type="checkbox" id="'.$ls_rec_id_elemen.'" name="'.$ls_rec_id_elemen.'" class="cebox" onclick="fl_js_set_st_dokumen(this)" '.$ls_elem_disabled.' '.$ls_elem_checked.'>';
              echo '<i><font color="#009999"><b> '.$ls_rec_keterangan.'</b></font></i>';
              echo '</div>';
              echo '<div class="clear"></div>';
            }
          }
        }

        if ($ls_n_arsip_dokumen > 0 && $ls_id_arsip_bundel_stamp != "") {
          echo '</br>';
				  echo '<div class="form-row_kiri">';
					echo '<input type="checkbox" id="st_dokumen_arsip_digital" name="st_dokumen_arsip_digital" class="cebox" disabled>';
					echo '<i><font color="#009999"><b> <a href="#" onclick = "show_arsip(\''.$ls_id_arsip_bundel_stamp.'\')">Dokumen Arsip Digital</a></b></font></i>';
				  echo '</div>';
				  echo '<div class="clear"></div>';
				}
      ?>
      </fieldset>
      </br>
      <fieldset style="width:300px;text-align:center;"><legend></legend>
        <input type="submit" class="btn green" id="btncetak" name="btncetak" value="     CETAK LAPORAN     " title="Klik Untuk Cetak Laporan">
      </fieldset>
      
      <?php if ($ls_n_arsip_dokumen > 0) { ?>
			</br>
			Keterangan:
			<ul>
				<li>Silahkan mengklik link pada nama jenis laporan untuk melihat dokumen digital.</li>
			</ul>
			</br>
			<?php } ?>
      
      <?php if (isset($msg)) { ?>
      <fieldset style="width:300px;">
      <legend>  
        <?= $ls_error == 1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>"; ?>
        <?= $ls_error == 1 ? "<font color=#ff0000>" . $msg . "</font>" : "<font color=#007bb7>" . $msg . "</font>"; ?>
        </legend>
      </fieldset>		
      
      <?php } ?>
    </div>
  </div>			
  <div id="clear-bottom"></div>
  
  <?php
    include_once "../../includes/footer_app.php";
  ?>