<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
include "../../includes/fungsi_newrpt.php";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "New Core System";
$gs_pagetitle = "Cetak Pembayaran Jaminan";
$username = $_SESSION["USER"];

//start function get link dokumen digital
function GetUrlPathDokSigned ($ls_kode_klaim, $ls_kode_jenis_dokumen,$ls_kode_dokumen) {
	//$path_http = $wsIpStorage;//"http://172.28.201.201:2024";
	global $wsIpStorage; //"http://172.28.200.33:8081";
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
// end function get link dokumen digital

?>
	
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
	<head>
				<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
				<title><?=$pagetitle;?></title>
				<meta name="Author" content="JroBalian" />
				<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
				<script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
				<script type="text/javascript" src="../../javascript/common.js"></script>
				<script type="text/javascript" src="../../javascript/jquery.js"></script>
        
				<script language="JavaScript">			 
          function gantiId(theForm) {
            window.location.replace('?cbulanke='+nu+'');
          }	
      		function confirmDelete(delUrl) {
            if (confirm("Are you sure you want to delete this record")) {
              document.location = delUrl;
            }
          }
    			function confirmapproval(apvUrl) {
      			if (confirm("Anda yakin menyimpan data?")) {
      				 window.document.location = apvUrl;
      			}		 
    			}												
    		</script>
        <script language="JavaScript">

			function show_dokumen_digital(path_dokumen_digital) {
				if (path_dokumen_digital == '') {
				return window.alert('Dokumen digital belum terbentuk, silakan ke menu Monitoring Dokumen Digital untuk proses lebih lanjut.');
				}

				let strArray = path_dokumen_digital.split("/");
				let path = strArray[0];
				let namafile = strArray[1];
				let wsLinkDownload  = "<?php echo $wsIpDokumenAntrian ?>";

				NewWindow( wsLinkDownload+path,'',900,700,1);
			}

          function fl_js_set_st_kwitansi()
          {
          	var form = document.adminForm;
          	if (form.st_kwitansi.checked)
          	{
          		form.st_kwitansi.value = "Y";
          	}
          	else
          	{
          		form.st_kwitansi.value = "T";
          	}	
          }
          function fl_js_set_st_spb()
          {
          	var form = document.adminForm;
          	if (form.st_spb.checked)
          	{
          		form.st_spb.value = "Y";
          	}
          	else
          	{
          		form.st_spb.value = "T";
          	}	
          }
          function fl_js_set_st_voucher()
          {
          	var form = document.adminForm;
          	if (form.st_voucher.checked)
          	{
          		form.st_voucher.value = "Y";
          	}
          	else
          	{
          		form.st_voucher.value = "T";
          	}	
          }
          function fl_js_set_st_bp21()
          {
          	var form = document.adminForm;
          	if (form.st_bp21.checked)
          	{
          		form.st_bp21.value = "Y";
          	}
          	else
          	{
          		form.st_bp21.value = "T";
          	}	
          }																 		 	 	 		 	 
        </script>			
	</head>
	<body>
				<div id="header-popup">	
				<h3><?=$gs_pagetitle;?></h3>
				</div>

				<div id="container-popup">
				<!--[if lte IE 6]>
				<div id="clearie6"></div>
				<![endif]-->	
    		<form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
          <?php
          $ls_sender				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
					$ls_jenis_laporan	= !isset($_GET['jenis_laporan']) ? $_POST['jenis_laporan'] : $_GET['jenis_laporan'];
          if ($ls_jenis_laporan=="")
          {
           $ls_jenis_laporan = "lap1";
          }							
          $ls_kode_pembayaran	= !isset($_GET['kode_pembayaran']) ? $_POST['kode_pembayaran'] : $_GET['kode_pembayaran'];
          $sql = 	"select 'LUMPSUM' jns_pembayaran, ".
                  "    a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, d.nama_tipe_penerima, a.kd_prg, ".  
                  "    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ".
                  "    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".  
                  "from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c, sijstk.pn_kode_tipe_penerima d ".
                  "where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima ". 
                  "and a.kode_klaim = c.kode_klaim and a.kode_tipe_penerima = d.kode_tipe_penerima ".
                  "and a.kode_pembayaran = '$ls_kode_pembayaran' ".
									"UNION ALL ".
									"select 'BERKALA' jns_pembayaran, ". 
                  "    a.kode_pembayaran, a.kode_klaim, d.kode_penerima_berkala kode_tipe_penerima, d.kode_penerima_berkala nama_tipe_penerima, a.kd_prg, ".   
                  "    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ". 
                  "    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".   
                  "from sijstk.pn_klaim_pembayaran_berkala a, sijstk.pn_klaim_penerima_berkala b, sijstk.pn_klaim c, sijstk.pn_klaim_berkala d ". 
                  "where a.kode_klaim = c.kode_klaim and a.kode_klaim = d.kode_klaim and a.no_konfirmasi = d.no_konfirmasi ".
                  "and b.kode_klaim = d.kode_klaim and b.kode_penerima_berkala = d.kode_penerima_berkala ".
                  "and a.kode_pembayaran = '$ls_kode_pembayaran' ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
					$ls_jns_pembayaran			= $row["JNS_PEMBAYARAN"];
					$ls_kode_pembayaran			= $row["KODE_PEMBAYARAN"];
					$ls_kode_klaim 					= $row["KODE_KLAIM"];
					$ls_kode_tipe_penerima	= $row["KODE_TIPE_PENERIMA"];	
          $ls_tipe_penerima				= $row["NAMA_TIPE_PENERIMA"];
					$ls_kd_prg							= $row["KD_PRG"];
          $ls_nm_rek_penerima			= $row["NAMA_REKENING_PENERIMA"];
          $ls_bank_penerima				= $row["BANK_PENERIMA"];
          $ls_no_rek_penerima			= $row["NO_REKENING_PENERIMA"];
					$ls_no_penetapan				=	$row["NO_PENETAPAN"]; 
					$ls_no_pointer					=	$row["NO_POINTER"];  
					$ls_flag_pph_progresif	=	$row["FLAG_PPH_PROGRESIF"];
					
					$ls_st_kwitansi	= !isset($_GET['st_kwitansi']) ? $_POST['st_kwitansi'] : $_GET['st_kwitansi'];
					$ls_st_spb	= !isset($_GET['st_spb']) ? $_POST['st_spb'] : $_GET['st_spb'];
					$ls_st_voucher	= !isset($_GET['st_voucher']) ? $_POST['st_voucher'] : $_GET['st_voucher'];
					$ls_st_bp21	= !isset($_GET['st_bp21']) ? $_POST['st_bp21'] : $_GET['st_bp21'];
					
          if ($ls_st_kwitansi=="on" || $ls_st_kwitansi=="ON" || $ls_st_kwitansi=="Y")
          {
          	$ls_st_kwitansi = "Y";
          }else
          {
          	$ls_st_kwitansi = "T";
          }						
																			
          if ($ls_st_spb=="on" || $ls_st_spb=="ON" || $ls_st_spb=="Y")
          {
          	$ls_st_spb = "Y";
          }else
          {
          	$ls_st_spb = "T";
          }	
          if ($ls_st_voucher=="on" || $ls_st_voucher=="ON" || $ls_st_voucher=="Y")
          {
          	$ls_st_voucher = "Y";
          }else
          {
          	$ls_st_voucher = "T";
          }	
          if ($ls_st_bp21=="on" || $ls_st_bp21=="ON" || $ls_st_bp21=="Y")
          {
          	$ls_st_bp21 = "Y";
          }else
          {
          	$ls_st_bp21 = "T";
		  }
		  
		  // start query link dokumen digital
			$sql_1 = "
			select  kode_tipe_klaim, status_klaim, substr(kode_tipe_klaim,1,3) jenis_klaim, kanal_pelayanan, 
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
			from pn.pn_klaim a
			where kode_klaim = '$ls_kode_klaim' ";
			// var_dump($sql_1);die();
			$DB->parse($sql_1);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_kode_tipe_klaim         = $row['KODE_TIPE_KLAIM'];
			$ls_jenis_klaim             = $row['JENIS_KLAIM'];
			$ls_status_klaim            = $row['STATUS_KLAIM'];
			$ls_kanal_pelayanan         = $row['KANAL_PELAYANAN'];
			$ls_cnt_lumpsum 			= $row["CNT_LUMPSUM"];
			$ls_cnt_berkala 			= $row["CNT_BERKALA"]; 
			$ls_flag_berkala 			= $ls_cnt_berkala > 0 ? "Y" : "T";
			$ls_flag_lumpsum 			= $ls_cnt_lumpsum > 0 ? "Y" : "T";

		
			$sql_2 = "
					SELECT kode_tipe_klaim,
					kode_jenis_dokumen_arsip,
					kode_kanal_pelayanan,
					keterangan,
					status_lumpsum,
					status_tampil,
					status_cetak,
					status_digital,
					(SELECT kode_dokumen_arsip
						FROM pn.pn_kode_dokumen_cetak
					WHERE     UPPER (keterangan) = 'VOUCHER'
							AND kode_kanal_pelayanan = '$ls_kanal_pelayanan'
							AND kode_tipe_klaim = '$ls_kode_tipe_klaim'
							AND status_nonaktif = 'T'
							AND status_lumpsum = '$ls_flag_lumpsum')
						kode_voucher,
					(SELECT kode_dokumen_arsip
						FROM pn.pn_kode_dokumen_cetak
					WHERE     UPPER (keterangan) = 'KWITANSI'
							AND kode_kanal_pelayanan = '$ls_kanal_pelayanan'
							AND kode_tipe_klaim = '$ls_kode_tipe_klaim'
							AND status_nonaktif = 'T'
							AND status_lumpsum = '$ls_flag_lumpsum')
						kode_kwitansi,
					(SELECT kode_dokumen_arsip
						FROM pn.pn_kode_dokumen_cetak
					WHERE     UPPER (keterangan) = 'SURAT PERINTAH BAYAR'
							AND kode_kanal_pelayanan = '$ls_kanal_pelayanan'
							AND kode_tipe_klaim = '$ls_kode_tipe_klaim'
							AND status_nonaktif = 'T'
							AND status_lumpsum = '$ls_flag_lumpsum')
						kode_spb,
					(SELECT kode_dokumen_arsip
						FROM pn.pn_kode_dokumen_cetak
					WHERE     UPPER (keterangan) = 'BUKTI POTONG PPH21'
							AND kode_kanal_pelayanan = '$ls_kanal_pelayanan'
							AND kode_tipe_klaim = '$ls_kode_tipe_klaim'
							AND status_nonaktif = 'T'
							AND status_lumpsum = '$ls_flag_lumpsum')
						kode_bp21
				FROM pn.pn_kode_dokumen_cetak
			WHERE     kode_kanal_pelayanan = '$ls_kanal_pelayanan'
					AND kode_tipe_klaim = '$ls_kode_tipe_klaim'
					AND status_nonaktif = 'T'
					AND status_lumpsum = '$ls_flag_lumpsum'
					AND UPPER (keterangan) IN ('VOUCHER',
												'KWITANSI',
												'SURAT PERINTAH BAYAR',
												'BUKTI POTONG PPH21')
			ORDER BY no_urut ASC
			";
			// var_dump($sql_2);die();
			$DB->parse($sql_2);
			$DB->execute();
			$row = $DB->nextrow();
			$ls_rec_kode_tipe_klaim          = $row["KODE_TIPE_KLAIM"];
			$ls_rec_kode_jenis_dokumen_arsip = $row["KODE_JENIS_DOKUMEN_ARSIP"];
			$ls_rec_kode_kanal_pelayanan     = $row["KODE_KANAL_PELAYANAN"];
			$ls_rec_keterangan               = $row["KETERANGAN"];
			$ls_rec_status_lumpsum           = $row["STATUS_LUMPSUM"];
			$ls_rec_status_tampil            = $row["STATUS_TAMPIL"];
			$ls_rec_status_cetak             = $row["STATUS_CETAK"];
			$ls_rec_status_digital           = $row["STATUS_DIGITAL"];
			$ls_rec_kode_voucher          	 = $row["KODE_VOUCHER"];
			$ls_rec_kode_kwitansi	         = $row["KODE_KWITANSI"];
			$ls_rec_kode_spb			     = $row["KODE_SPB"];
			$ls_rec_kode_bp21			     = $row["KODE_BP21"];

			//end link query dokumen digital
		  
			
					if(isset($_POST["butcetak_all"]))
          {		
          	$ls_user_param .= " qkode_pembayaran='$ls_kode_pembayaran'";
          	$ls_user_param .= " qkode_klaim='$ls_kode_klaim'";
						
            $sql = 	"select to_char(sysdate,'yyyymmdd') as vtgl, replace('$ls_tipe_penerima',' ','XXX') tipe_penerima, ".
								 		"		to_char(to_date('$ld_blth_proses','dd/mm/yyyy'),'yyyymmdd') as vblth_proses ".	
										"from dual ";
            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_lap_tipe_penerima 	= $row["TIPE_PENERIMA"];
						$ls_lap_blth_proses 	= $row["VBLTH_PROSES"];			
          	$ls_user_param .= " qtipe_penerima='$ls_lap_tipe_penerima'";          
          	$ls_user_param .= " qtgl='$ld_tglcetak'";
          	$ls_user_param .= " qblth_proses='$ls_lap_blth_proses'";

          	if ($ls_st_bp21!="T")
          	{
							 $ls_user_param .= " qkodepointer_asal='JM09'"; 
							 $ls_user_param .= " qidpointer_asal='$ls_kode_pembayaran'";
							 
               $tipe4 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul4 = "LK";
							 
							 if ($ls_flag_pph_progresif=="Y")
							 {
							 		$ls_nama_rpt4  .= "TAXR301408.rdf";
							 }else
							 {
          	 	 	  $ls_nama_rpt4  .= "TAXR301407.rdf";
							 }
							 exec_rpt_enc_new(1, $ls_modul4, $ls_nama_rpt4, $ls_user_param, $tipe4);								 							 
          	}									
          	if ($ls_st_voucher!="T" && $ls_no_pointer!="")
          	{
							 if ($ls_jns_pembayaran=="LUMPSUM")
							 {
							 		$ls_user_param .= " qiddokumen_induk='$ls_kode_klaim'"; 
							 }
							 $ls_user_param .= " qpointer='PN01'"; 
							 $ls_user_param .= " qiddokumen='$ls_no_pointer'";
						 	 $ls_user_param .= " quser_cetak='$username'"; 							  	 
							 
               $tipe3 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul3 = "LK";
		
          	 	 $ls_nama_rpt3  .= "GLR800001.rdf";
							 exec_rpt_enc_new(1, $ls_modul3, $ls_nama_rpt3, $ls_user_param, $tipe3);									 						 
						}
          	if ($ls_st_spb!="T")
          	{
               $tipe2 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul2 = "PN";
		
          	 	 $ls_nama_rpt2 .= "PNR502902.rdf";	
							 exec_rpt_enc_new(1, $ls_modul2, $ls_nama_rpt2, $ls_user_param, $tipe2);								 						 
          	}						
          	if ($ls_st_kwitansi!="T")
          	{
               $tipe1 = isset($iscetak) ? "PDF" : "PDF";
               $ls_modul1 = "PN";
		
          	 	 $ls_nama_rpt1 .= "PNR502901.rdf";	
							 exec_rpt_enc_new(1, $ls_modul1, $ls_nama_rpt1, $ls_user_param, $tipe1);					 
          	}							 
						          	
          	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          	echo "window.location.replace('?kode_pembayaran=$ls_kode_pembayaran&jenis_laporan=$ls_jenis_laporan&st_kwitansi=$ls_st_kwitansi&st_spb=$ls_st_spb&st_voucher=$ls_st_voucher&st_bp21=$ls_st_bp21&sender=$ls_sender');";
          	echo "</script>";									
          } 
 
					// * JULI 2020: START ARSIP DIGITAL 
					if(isset($_POST["butcetak_all_arsipkan"]))
          			{
	 						// -----------------------------start update pending matters 09032022------------------------
						if($username)
						{
							 // -----------------------------end update pending matters 09032022------------------------

						  	
							// * START: GET REPORT URL
							// * note: harus disamakan dengan pembentukan report di atas 
							
							$ls_kode_pembayaran	= !isset($_GET['kode_pembayaran']) ? $_POST['kode_pembayaran'] : $_GET['kode_pembayaran'];
							$sql = 	"select 'LUMPSUM' jns_pembayaran, ".
											"    c.kode_tipe_klaim, a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, d.nama_tipe_penerima, a.kd_prg, ".  
											"    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ".
											"    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".  
											"from sijstk.pn_klaim_pembayaran a, sijstk.pn_klaim_penerima_manfaat b, sijstk.pn_klaim c, sijstk.pn_kode_tipe_penerima d ".
											"where a.kode_klaim = b.kode_klaim and a.kode_tipe_penerima = b.kode_tipe_penerima ". 
											"and a.kode_klaim = c.kode_klaim and a.kode_tipe_penerima = d.kode_tipe_penerima ".
											"and a.kode_pembayaran = '$ls_kode_pembayaran' ".
											"UNION ALL ".
											"select 'BERKALA' jns_pembayaran, ". 
											"    c.kode_tipe_klaim, a.kode_pembayaran, a.kode_klaim, d.kode_penerima_berkala kode_tipe_penerima, d.kode_penerima_berkala nama_tipe_penerima, a.kd_prg, ".   
											"    b.nama_rekening_penerima, b.bank_penerima, b.no_rekening_penerima, ". 
											"    c.no_penetapan, a.no_pointer, nvl(a.flag_pph_progresif,'T') flag_pph_progresif ".   
											"from sijstk.pn_klaim_pembayaran_berkala a, sijstk.pn_klaim_penerima_berkala b, sijstk.pn_klaim c, sijstk.pn_klaim_berkala d ". 
											"where a.kode_klaim = c.kode_klaim and a.kode_klaim = d.kode_klaim and a.no_konfirmasi = d.no_konfirmasi ".
											"and b.kode_klaim = d.kode_klaim and b.kode_penerima_berkala = d.kode_penerima_berkala ".
											"and a.kode_pembayaran = '$ls_kode_pembayaran' ";
											
							$DB->parse($sql);
							$DB->execute();
							$row = $DB->nextrow();
							$ls_kode_tipe_klaim			= $row["KODE_TIPE_KLAIM"];
							$ls_jns_pembayaran			= $row["JNS_PEMBAYARAN"];
							$ls_kode_pembayaran			= $row["KODE_PEMBAYARAN"];
							$ls_kode_klaim 					= $row["KODE_KLAIM"];
							$ls_kode_tipe_penerima	= $row["KODE_TIPE_PENERIMA"];	
							$ls_tipe_penerima				= $row["NAMA_TIPE_PENERIMA"];
							$ls_kd_prg							= $row["KD_PRG"];
							$ls_nm_rek_penerima			= $row["NAMA_REKENING_PENERIMA"];
							$ls_bank_penerima				= $row["BANK_PENERIMA"];
							$ls_no_rek_penerima			= $row["NO_REKENING_PENERIMA"];
							$ls_no_penetapan				=	$row["NO_PENETAPAN"]; 
							$ls_no_pointer					=	$row["NO_POINTER"];  
							$ls_flag_pph_progresif	=	$row["FLAG_PPH_PROGRESIF"];
							
							$sql = "
							SELECT 	a.KANAL_PELAYANAN,
							(
								select count(*) jml_dokumen_digital from pn.pn_klaim b 
								where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') 
									and kode_tipe_klaim in (select kode from MS.MS_LOOKUP where tipe='KANALKLM'||b.kanal_pelayanan AND KATEGORI = 'DOKUMEN_DIGITAL')
													and b.kode_klaim = a.kode_klaim
							) JML_DOKUMEN_DIGITAL
							FROM 		PN.PN_KLAIM a
							WHERE 	a.KODE_KLAIM = '$ls_kode_klaim'
							";
							// var_dump($sql);die();
							$DB->parse($sql);
							$DB->execute();
							$row = $DB->nextrow();
							$ls_kanal_pelayanan = $row["KANAL_PELAYANAN"];
							$ls_jml_dokumen_digital = $row['JML_DOKUMEN_DIGITAL'];
							
							//if ($ls_kanal_pelayanan == "25" || $ls_kanal_pelayanan == "27" || $ls_kanal_pelayanan == "28" || $ls_kanal_pelayanan == "29") {
							if ($ls_jml_dokumen_digital > 0) {
								$ls_user_param .= " qkode_pembayaran='$ls_kode_pembayaran'";
								$ls_user_param .= " qkode_klaim='$ls_kode_klaim'";
								
								$sql = 	"select to_char(sysdate,'yyyymmdd') as vtgl, replace('$ls_tipe_penerima',' ','XXX') tipe_penerima, ".
												"		to_char(to_date('$ld_blth_proses','dd/mm/yyyy'),'yyyymmdd') as vblth_proses ".	
												"from dual ";
								$DB->parse($sql);
								$DB->execute();
								$row = $DB->nextrow();
								$ls_lap_tipe_penerima = $row["TIPE_PENERIMA"];
								$ls_lap_blth_proses = $row["VBLTH_PROSES"];
								$ls_user_param .= " qtipe_penerima='$ls_lap_tipe_penerima'";
								$ls_user_param .= " qtgl='$ld_tglcetak'";
								$ls_user_param .= " qblth_proses='$ls_lap_blth_proses'";

								// get url report pph21
								$ls_user_param_4 = $ls_user_param;
								$ls_user_param_4 .= " qkodepointer_asal='JM09'";
								$ls_user_param_4 .= " qidpointer_asal='$ls_kode_pembayaran'";
								
								$tipe4 = isset($iscetak) ? "PDF" : "PDF";
								$ls_modul4 = "LK";
								
								if ($ls_flag_pph_progresif=="Y") {
									$ls_nama_rpt4  .= "TAXR301408.rdf";
								} else {
									$ls_nama_rpt4  .= "TAXR301407.rdf";
								}
								$ls_temp_url_pph21 = exec_rpt_enc_new(9, $ls_modul4, $ls_nama_rpt4, $ls_user_param_4, $tipe4);
								// end get url report pph21
								
								// get url report voucher
								$ls_user_param_3 = $ls_user_param;
								if ($ls_jns_pembayaran=="LUMPSUM"){
									$ls_user_param_3 .= " qiddokumen_induk='$ls_kode_klaim'"; 
								}
								$ls_user_param_3 .= " qpointer='PN01'"; 
								$ls_user_param_3 .= " qiddokumen='$ls_no_pointer'";
								$ls_user_param_3 .= " quser_cetak='$username'";
								$tipe3 = isset($iscetak) ? "PDF" : "PDF";
								$ls_modul3 = "LK";
								$ls_nama_rpt3  .= "GLR800001.rdf";
								$ls_temp_url_voucher = exec_rpt_enc_new(9, $ls_modul3, $ls_nama_rpt3, $ls_user_param_3, $tipe3);
								// end get url report voucher
								
								// get url report spb
								$ls_user_param_2 = $ls_user_param;
								$tipe2 = isset($iscetak) ? "PDF" : "PDF";
								$ls_modul2 = "PN";
								$ls_nama_rpt2 .= "PNR502902.rdf";
								$ls_temp_url_spb = exec_rpt_enc_new(9, $ls_modul2, $ls_nama_rpt2, $ls_user_param_2, $tipe2);
								// end get url report spb

								// get url report kwitansi
								$ls_user_param_1 = $ls_user_param;
								$tipe1 = isset($iscetak) ? "PDF" : "PDF";
								$ls_modul1 = "PN";

								$ls_nama_rpt1 .= "PNR502901.rdf";	
								$ls_temp_url_kwitansi = exec_rpt_enc_new(9, $ls_modul1, $ls_nama_rpt1, $ls_user_param_1, $tipe1);					 
								// end get url report kwitansi

								$arr_url_file = array(
									"kwitansi" => $ls_temp_url_kwitansi,
									"spb"      => $ls_temp_url_spb,
									"voucher"  => $ls_temp_url_voucher,
									"pph21"    => $ls_temp_url_pph21
								);
								// * END: GET REPORT URL


								// * START: GET DOCUMENT DATA USER SIGNS
								// get data user sign untuk report spb
								$sql_sign = "
								BEGIN
									PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKSPB(
										:P_SIGN_KODE_KLAIM,
										:P_SIGN_KODE_KANTOR,
										:P_SIGN_NPK,
										:P_SIGN_KODE_USER,
										:P_SIGN_NAMA_USER,
										:P_SIGN_NAMA_JABATAN,
										:P_SIGN_SUKSES,
										:P_SIGN_MESS
									);
								END;";
								// var_dump($sql_sign)die();
								$proc_sign = $DB->parse($sql_sign);
								oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
								oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
								oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
								oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
								oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
								oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);
								
								if ($DB->execute()) {
									$sukses = $p_sign_sukses;
									$mess = $p_sign_mess;
									if ($sukses == '1') {
										$arr_temp_data_user_sign_spb = array(
											"kodeKantor" 	=> $p_sign_kode_kantor,
											"npk"        	=> $p_sign_npk,
											"namaJabatan"	=> $p_sign_nama_jabatan,
											"petugas"   	=> $p_sign_kode_user
										);
									}
								}
								// end get data user sign untuk report spb

								// get data user sign untuk report voucher setuju
								$sql_sign = "
								BEGIN
									PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKVC_SETUJU(
										:P_SIGN_KODE_KLAIM,
										:P_SIGN_KODE_KANTOR,
										:P_SIGN_NPK,
										:P_SIGN_KODE_USER,
										:P_SIGN_NAMA_USER,
										:P_SIGN_NAMA_JABATAN,
										:P_SIGN_SUKSES,
										:P_SIGN_MESS
									);
								END;";
								$proc_sign = $DB->parse($sql_sign);
								oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
								oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
								oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
								oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
								oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
								oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);
								
								if ($DB->execute()) {
									$sukses = $p_sign_sukses;
									$mess = $p_sign_mess;
									if ($sukses == '1') {
										$arr_temp_data_user_sign_vc_setuju = array(
											"kodeKantor" 	=> $p_sign_kode_kantor,
											"npk"        	=> $p_sign_npk,
											"namaJabatan"	=> $p_sign_nama_jabatan,
											"petugas"   	=> $p_sign_kode_user
										);
									}
								}
								// end get data user sign untuk report voucher setuju

								// get data user sign untuk report voucher membukukan
								$sql_sign = "
								BEGIN
									PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKVC_MBUKU(
										:P_SIGN_KODE_KLAIM,
										:P_SIGN_PETUGAS_REKAM,
										:P_SIGN_KODE_KANTOR,
										:P_SIGN_NPK,
										:P_SIGN_KODE_USER,
										:P_SIGN_NAMA_USER,
										:P_SIGN_NAMA_JABATAN,
										:P_SIGN_SUKSES,
										:P_SIGN_MESS
									);
								END;";
								$proc_sign = $DB->parse($sql_sign);
								oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
								oci_bind_by_name($proc_sign, ":p_sign_petugas_rekam", $ls_username, 30);
								oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
								oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
								oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
								oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
								oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);
								
								if ($DB->execute()) {
									$sukses = $p_sign_sukses;
									$mess = $p_sign_mess;
									if ($sukses == '1') {
										$arr_temp_data_user_sign_vc_mbuku = array(
											"kodeKantor" 	=> $p_sign_kode_kantor,
											"npk"        	=> $p_sign_npk,
											"namaJabatan"	=> $p_sign_nama_jabatan,
											"petugas"   	=> $p_sign_kode_user
										);
									}
								}
								// end get data user sign untuk report voucher membukukan

								// get data user sign untuk report voucher membukukan
								$sql_sign = "
								BEGIN
									PN.P_PN_ARSIP_KLAIM_SIGN.X_GET_DTUSER_DOKVC_KASIR(
										:P_SIGN_KODE_KLAIM,
										:P_SIGN_KODE_KANTOR,
										:P_SIGN_NPK,
										:P_SIGN_KODE_USER,
										:P_SIGN_NAMA_USER,
										:P_SIGN_NAMA_JABATAN,
										:P_SIGN_SUKSES,
										:P_SIGN_MESS
									);
								END;";
								$proc_sign = $DB->parse($sql_sign);
								oci_bind_by_name($proc_sign, ":p_sign_kode_klaim", $ls_kode_klaim, 30);
								oci_bind_by_name($proc_sign, ":p_sign_kode_kantor", $p_sign_kode_kantor, 100);
								oci_bind_by_name($proc_sign, ":p_sign_npk", $p_sign_npk, 100);
								oci_bind_by_name($proc_sign, ":p_sign_kode_user", $p_sign_kode_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_user", $p_sign_nama_user, 100);
								oci_bind_by_name($proc_sign, ":p_sign_nama_jabatan", $p_sign_nama_jabatan, 100);
								oci_bind_by_name($proc_sign, ":p_sign_sukses", $p_sign_sukses, 10);
								oci_bind_by_name($proc_sign, ":p_sign_mess", $p_sign_mess, 4000);
								
								if ($DB->execute()) {
									$sukses = $p_sign_sukses;
									$mess = $p_sign_mess;
									if ($sukses == '1') {
										$arr_temp_data_user_sign_vc_kasir = array(
											"kodeKantor" 	=> $p_sign_kode_kantor,
											"npk"        	=> $p_sign_npk,
											"namaJabatan"	=> $p_sign_nama_jabatan,
											"petugas"   	=> $p_sign_kode_user
										);
									}
								}
								// end get data user sign untuk report voucher kasir
								// * END: GET DOCUMENT DATA USER SIGNS

								$reqid_arsip 			= $username;
								$id_dokumen_arsip = $ls_kode_klaim;
								$action_arsip 		= "PROSES_ARSIP";

								include "pn5048_arsip_dokumen_action.php";
							}
	 						// -----------------------------start update pending matters 09032022------------------------
						}else{
							$msg = '<span style="color:red;">Mohon maaf proses pembayaran gagal karena session habis, silahkan melakukan login ulang kembali dan memproses kembali pengarsipan.</span>';
						}
	 						// -----------------------------start update pending matters 09032022------------------------


					} 		
					// * JULI 2020: END ARSIP DIGITAL 						         		 
          ?>				
					
					<script>
						function cetak_dokumen(){
							let st_kwitansi_val = $('#st_kwitansi').val();
							let st_spb_val = $('#st_spb').val();
							let st_voucher_val = $('#st_voucher').val();
							let st_bp21 = $('#st_bp21').val();

							if (st_kwitansi_val != 'Y' && st_spb_val != 'Y' && st_voucher_val != 'Y' && st_bp21 != 'Y' ) {
								return alert('Pilih salah satu dokumen terlebih dahulu!');
							}
							
							<?php 
								if ($ls_kanal_pelayanan == "25")
								{
							?>
									if (st_kwitansi_val == 'Y') {
										let params = '';
										params += '&kode_klaim=<?=$ls_kode_klaim?>';
										params += '&kode_jenis_dokumen=JD105&kode_dokumen=JD105-D1007';
										window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
									}
									if (st_spb_val == 'Y') {
										let params = '';
										params += '&kode_klaim=<?=$ls_kode_klaim?>';
										params += '&kode_jenis_dokumen=JD105&kode_dokumen=JD105-D1008';
										window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
									}
									if (st_voucher_val == 'Y') {
										let params = '';
										params += '&kode_klaim=<?=$ls_kode_klaim?>';
										params += '&kode_jenis_dokumen=JD105&kode_dokumen=JD105-D1009';
										window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
									}
									if (st_bp21 == 'Y') {
										let params = '';
										params += '&kode_klaim=<?=$ls_kode_klaim?>';
										params += '&kode_jenis_dokumen=JD105&kode_dokumen=JD105-D1010';
										window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
									}
							<?php
								}
								else
								{
									if($ls_kode_tipe_klaim == "JHT01")
									{
							?>
										if (st_kwitansi_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD101&kode_dokumen=JD101-D1007';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_spb_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD101&kode_dokumen=JD101-D1008';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_voucher_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD101&kode_dokumen=JD101-D1009';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_bp21 == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD101&kode_dokumen=JD101-D1010';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
							<?php
									}
									else if($ls_kode_tipe_klaim == "JKM01")
									{
							?>
										if (st_kwitansi_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD102&kode_dokumen=JD102-D1002';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_spb_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD102&kode_dokumen=JD102-D1003';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_voucher_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD102&kode_dokumen=JD102-D1001';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
							<?php
									} else if($ls_kode_tipe_klaim == "JKP01")
									{
							?>
										if (st_kwitansi_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD107&kode_dokumen=JD107-D1002';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_spb_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD107&kode_dokumen=JD107-D1003';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_voucher_val == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD107&kode_dokumen=JD107-D1001';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
										if (st_bp21 == 'Y') {
											let params = '';
											params += '&kode_klaim=<?=$ls_kode_klaim?>';
											params += '&kode_jenis_dokumen=JD107&kode_dokumen=JD107-D1004';
											window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5048_arsip_dokumen_download.php?' + params + '&tipe=download_dokumen');
										}
							<?php
									}
							?>
									
							<?php
								}
							?>
						}

						function show_arsip(id_arsip_bundel) {
							NewWindow('<?=$wsIpArsip?>/qr/' + id_arsip_bundel,'',800,500,1);
						}

						function arsipkan(){
							if(!confirm('Apakah anda yakin akan melakukan pengarsipkan atas dokumen tersebut?')) {
								return false;
							}
						}
					</script>
    			<table class="captionentry">
      			<tr> 
        		 <td align="left">No. Penetapan : <b><?=$ls_no_penetapan;?></b> </td>						 
      			</tr>
    			</table>								
        	<div id="formframe">
        		<span id="dispError" style="display:none;color:red"></span>
        		<input type="hidden" id="st_errval" name="st_errval">
        		<span id="dispError1" style="display:none;color:red"></span>
        		<input type="hidden" id="st_errval1" name="st_errval1">					
        		<span id="dispError2" style="display:none;color:red"></span>
        		<input type="hidden" id="st_errval2" name="st_errval2">
						<input type="hidden" id="kode_pembayaran" name="kode_pembayaran" value="<?=$ls_kode_pembayaran;?>" size="40" readonly class="disabled">
        		<input type="hidden" id="no_penetapan" name="no_penetapan" value="<?=$ls_no_penetapan;?>" size="50"/>
        		<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>" size="50"/>
						<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>" size="50"/>
						<input type="hidden" id="blth_proses" name="blth_proses" value="<?=$ld_blth_proses;?>" size="50"/>						
									
  					<div id="formKiri">
    					<fieldset style="width:500px;"><legend>Parameter</legend>
        				<div class="form-row_kiri">
        				<label>Kode Klaim</label>
        					<input type="text" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>
															
        				<div class="form-row_kiri">
        				<label>Tipe Penerima</label>
        					<input type="text" id="tipe_penerima" name="tipe_penerima" value="<?=$ls_tipe_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>																

        				<div class="form-row_kiri">
        				<label>Transfer ke Bank</label>
        					<input type="text" id="bank_penerima" name="bank_penerima" value="<?=$ls_bank_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>

        				<div class="form-row_kiri">
        				<label>No Rekening</label>
        					<input type="text" id="no_rek_penerima" name="no_rek_penerima" value="<?=$ls_no_rek_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>
								
        				<div class="form-row_kiri">
        				<label>A/N</label>
        					<input type="text" id="nm_rek_penerima" name="nm_rek_penerima" value="<?=$ls_nm_rek_penerima;?>" size="40" readonly class="disabled">
        				</div>
        				<div class="clear"></div>
																
								<br>
							<!-- start penambahan link digital arsip -->	
    						<div class="form-row_kiri">
        				<label>Lampiran :</label>						
        					<?$ls_st_kwitansi = isset($ls_st_kwitansi) ? $ls_st_kwitansi : "Y";							
									?>					
    							<input type="checkbox" id="st_kwitansi" name="st_kwitansi" class="cebox" onclick="fl_js_set_st_kwitansi();" <?=$ls_st_kwitansi=="Y" ||$ls_st_kwitansi=="ON" ||$ls_st_kwitansi=="on" ? "checked" : "";?>>
							<?php 
								 if ($ls_rec_status_digital == "Y") {
									$ls_url_dokumen_digital = GetUrlPathDokSigned($ls_kode_klaim, $ls_rec_kode_jenis_dokumen_arsip, $ls_rec_kode_kwitansi);
								?>
								<i><font color="#009999"><b> <a href="#" onclick="show_dokumen_digital('<?php echo $ls_url_dokumen_digital;?>');">Kwitansi</a></b></font></i>
								<?php } else { ?>
        						<i><font  color="#009999">Kwitansi</font></i>
								<?php } ?>	
    						</div>											
    						<div class="clear"></div>	
								
    						<div class="form-row_kiri">
        				<label  style = "text-align:right;">&nbsp;</label>						
        					<? $ls_st_spb = isset($ls_st_spb) ? $ls_st_spb : "Y";?>					
    							<input type="checkbox" id="st_spb" name="st_spb" class="cebox" onclick="fl_js_set_st_spb();" <?=$ls_st_spb=="Y" ||$ls_st_spb=="ON" ||$ls_st_spb=="on" ? "checked" : "";?>>
							<?php 
								 if ($ls_rec_status_digital == "Y") {
									$ls_url_dokumen_digital = GetUrlPathDokSigned($ls_kode_klaim, $ls_rec_kode_jenis_dokumen_arsip, $ls_rec_kode_spb);
								?>
								<i><font color="#009999"><b> <a href="#" onclick="show_dokumen_digital('<?php echo $ls_url_dokumen_digital;?>');">Surat Perintah Bayar</a></b></font></i>
								<?php } else { ?>
        					<i><font  color="#009999">Surat Perintah Bayar</font></i>
							<?php } ?>
    						</div>											
    						<div class="clear"></div>	

    						<div class="form-row_kiri">
        				<label  style = "text-align:right;">&nbsp;</label>						
        					<? $ls_st_voucher = isset($ls_st_voucher) ? $ls_st_voucher : "Y";?>					
    							<input type="checkbox" id="st_voucher" name="st_voucher" class="cebox" onclick="fl_js_set_st_voucher();" <?=$ls_st_voucher=="Y" ||$ls_st_voucher=="ON" ||$ls_st_voucher=="on" ? "checked" : "";?>>
							<?php 
								 if ($ls_rec_status_digital == "Y") {
									$ls_url_dokumen_digital = GetUrlPathDokSigned($ls_kode_klaim, $ls_rec_kode_jenis_dokumen_arsip, $ls_rec_kode_voucher);
								?>
								<i><font color="#009999"><b> <a href="#" onclick="show_dokumen_digital('<?php echo $ls_url_dokumen_digital;?>');">Voucher</a></b></font></i>
								<?php } else { ?>
        					<i><font  color="#009999">Voucher</font></i>
							<?php } ?>
    						</div>											
    						<div class="clear"></div>
							<?php if($ls_kode_tipe_klaim != "JKP01"){ ?>
									<div class="form-row_kiri">
									<label  style = "text-align:right;">&nbsp;</label>						
										<? $ls_st_bp21 = isset($ls_st_bp21) ? $ls_st_bp21 : "Y";?>					
										<input type="checkbox" id="st_bp21" name="st_bp21" class="cebox" onclick="fl_js_set_st_bp21();" <?=$ls_st_bp21=="Y" ||$ls_st_bp21=="ON" ||$ls_st_bp21=="on" ? "checked" : "";?>>
								<?php 
									if ($ls_rec_status_digital == "Y") {
										$ls_url_dokumen_digital = GetUrlPathDokSigned($ls_kode_klaim, $ls_rec_kode_jenis_dokumen_arsip, $ls_rec_kode_bp21);
									?>
									<i><font color="#009999"><b> <a href="#" onclick="show_dokumen_digital('<?php echo $ls_url_dokumen_digital;?>');">Bukti Potong PPh21</a></b></font></i>
									<?php } else { ?>
										<i><font  color="#009999">Bukti Potong PPh21</font></i>
								<?php } ?>
									</div>									
							<?php } ?>
										
    						<div class="clear"></div>
							<!-- end penambahan link digital arsip -->																																																			  																																								
    					</fieldset>
  						
							<?php
								$sql = "
								SELECT  COUNT(1) N_ARSIP_DOKUMEN,
												MAX(
													(
													SELECT 	ID_ARSIP_BUNDEL_STAMP 
													FROM 		PN.PN_ARSIP_DOKUMEN_BUNDEL AA
													WHERE 	AA.ID_ARSIP_BUNDEL = A.ID_ARSIP_BUNDEL
													)
												) ID_ARSIP_BUNDEL_STAMP
								FROM    PN.PN_ARSIP_DOKUMEN A
								WHERE   A.ID_DOKUMEN = '$ls_kode_klaim' ";
								$DB->parse($sql);
								$DB->execute();
								$row = $DB->nextrow();
								$ls_arsip_dokumen = $row["N_ARSIP_DOKUMEN"];
								$ls_id_arsip_bundel_stamp = $row["ID_ARSIP_BUNDEL_STAMP"];

								$sql = "
								SELECT 	a.KANAL_PELAYANAN,
								(
									select count(*) jml_dokumen_digital from pn.pn_klaim b 
										where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') 
										and kode_tipe_klaim in (select kode from MS.MS_LOOKUP where tipe='KANALKLM'||b.kanal_pelayanan AND KATEGORI = 'DOKUMEN_DIGITAL')
														and b.kode_klaim = a.kode_klaim

								) JML_DOKUMEN_DIGITAL
								FROM 		PN.PN_KLAIM a
								WHERE 	a.KODE_KLAIM = '$ls_kode_klaim'
								";
								$DB->parse($sql);
								$DB->execute();
								$row = $DB->nextrow();
								$ls_kanal_pelayanan = $row["KANAL_PELAYANAN"];
								$ls_jml_dokumen_digital = $row['JML_DOKUMEN_DIGITAL'];
							?>

    					<fieldset style="width:500px;"><legend>&nbsp;</legend>
    						<!--<input type="submit" class="btn green" id="butcetak" name="butcetak" value="          CETAK       " />-->
								
								<?php 
								//if ($ls_kanal_pelayanan == "25" || $ls_kanal_pelayanan == "27" || $ls_kanal_pelayanan == "28" || $ls_kanal_pelayanan == "29") 
								if ($ls_jml_dokumen_digital > 0  && $ls_kanal_pelayanan <> "66") 
									{ 
								?>
									<?php if ($ls_id_arsip_bundel_stamp != "") { ?>
										<input style="padding-left: 8px; padding-right: 8px;" type="button" class="btn green" id="butcetak_all_bundel" name="butcetak_all_bundel" value="TAMPILKAN DOKUMEN ARSIP" onclick = "show_arsip('<?=$ls_id_arsip_bundel_stamp?>')";/>
									<?php } else { ?>
										<input style="padding-left: 8px; padding-right: 8px;" type="submit" class="btn green" id="butcetak_all_arsipkan" name="butcetak_all_arsipkan" value="ARSIPKAN" onclick = "return arsipkan()";/>
									<?php } ?>
								<?php } else { ?>
									<input style="padding-left: 8px; padding-right: 8px;" type="submit" class="btn green" id="butcetak_all" name="butcetak_all" value="CETAK" />
								<?php } ?>
    					</fieldset>		

							<?php if (isset($msg_arsip)) { ?>
								<fieldset style="width:500px;">
									<?= $ln_error_arsip > 0 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>MessageX</font></legend>";?>
									<?= $ln_error_arsip > 0 ? "<font color=#ff0000>".$msg_arsip."</font>" : "<font color=#007bb7>".$msg_arsip."</font>";?>
								</fieldset>		
							<?php } ?>						
  					</div>
        	</div>													 							
												
    			</br>
          <?
          if (isset($msg))		
          {
          	?>
          	<fieldset>
          		<?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
          		<?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
          	</fieldset>		
          	<?
          }
          ?>	
    			<?
    			$othervar = "kode_pembayaran=".$ls_kode_pembayaran."&sender=".$ls_sender."";
    			echo f_draw_pager($url, $total_pages, $_GET['page'],$othervar); 
    			?>						
				</form>	
				
				<div id="clear-bottom-popup"></div>
				</div> 

      	<div id="footer-popup">
        <p class="lft"></p>
        <p class="rgt">New Core System</p>
      	</div>

	</body>
	</html>						
	