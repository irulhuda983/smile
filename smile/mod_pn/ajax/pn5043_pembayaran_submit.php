<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
include "../../includes/fungsi_newrpt.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Submit Pembayaran Klaim";
if ($username==""){$username = $_SESSION["USER"];}

$ls_kode_klaim							 	 		= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];	
$ls_kode_tipe_penerima			 	 		= !isset($_GET['kode_tipe_penerima']) ? $_POST['kode_tipe_penerima'] : $_GET['kode_tipe_penerima'];	
$ls_kd_prg							 	 				= !isset($_GET['kd_prg']) ? $_POST['kd_prg'] : $_GET['kd_prg'];	
$ls_kode_bank_pembayar			 	 		= !isset($_GET['kode_bank_pembayar']) ? $_POST['kode_bank_pembayar'] : $_GET['kode_bank_pembayar'];	
$ls_kode_buku							 	 			= !isset($_GET['kode_buku']) ? $_POST['kode_buku'] : $_GET['kode_buku'];	
$ls_kode_cara_bayar					 	 		= !isset($_GET['kode_cara_bayar']) ? $_POST['kode_cara_bayar'] : $_GET['kode_cara_bayar'];	
$ls_kode_kantor_pembayaran				= !isset($_GET['kode_kantor_pembayaran']) ? $_POST['kode_kantor_pembayaran'] : $_GET['kode_kantor_pembayaran'];	

$ls_sender_mid							 	 		= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];	
$ls_rg_kategori							 	 		= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];

$ls_form_root							 	 			= !isset($_GET['form_root']) ? $_POST['form_root'] : $_GET['form_root'];
$ls_root_sender							 	 		= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender							 	 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_id_pointer_asal					 	 		= !isset($_GET['id_pointer_asal']) ? $_POST['id_pointer_asal'] : $_GET['id_pointer_asal'];
$ls_kode_pointer_asal				 	 		= !isset($_GET['kode_pointer_asal']) ? $_POST['kode_pointer_asal'] : $_GET['kode_pointer_asal'];
$ld_tglawaldisplay					 	 		= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay							 	= !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];
					 
?>
	
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <!--<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />-->
	<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.new.css?ver=1.2" />
	<script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>

  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	
	<!-- tambahan baru -->
	<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
	<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
		
</head>

<style type="text/css">
	/* body{
		font-family: tahoma, arial, verdana, sans-serif; 
		font-size:11px;
		background : #fbf7c8;
	} 
	a {
		text-decoration:none;
		color:#008040;
		}

	a:hover {
		color:#68910b; 
		text-decoration:none;
		} */
</style>

<body>
	<?php
  if(isset($_POST["btnsubmit"]))
  {	
	 // -----------------------------start update pending matters 09032022------------------------
	if($username)
  	{		  	
	 // -----------------------------end update pending matters 09032022------------------------

		//pembayaran klaim ---------------------------------------------------------
			$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_BAYAR( ".
						"			'$ls_kode_klaim', ".
						"			'$ls_kode_tipe_penerima', ".
						"			'$ls_kd_prg', ".
						"			'$ls_kode_bank_pembayar', ".
						"			'$ls_kode_buku', ".
						"			'$ls_kode_cara_bayar', ".
						"			'$ls_kode_kantor_pembayaran', ".
						"			nvl('$username',user), ".
						"			:p_sukses,:p_mess);END;";	
												
			$proc = $DB->parse($qry);				
			oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
				oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
				
			$DB->execute();				
			$ls_sukses = $p_sukses;	
			$ls_mess = $p_mess;
			$ls_status_submit = "Y";
			
		if ($ls_sukses=="1")
		{
		//ambil kode pembayaran --------------------------------------------------
		$sql = "select kode_pembayaran from sijstk.pn_klaim_pembayaran ".
						"where kode_klaim = '$ls_kode_klaim' ".
							"and kode_tipe_penerima = '$ls_kode_tipe_penerima' ".
							"and kd_prg = '$ls_kd_prg' ".
							"and nvl(status_batal,'T')='T' ".
							"and rownum=1";
		$DB->parse($sql);
		$DB->execute();
		$row = $DB->nextrow();		
		$ls_kode_pembayaran = $row['KODE_PEMBAYARAN'];
				
				$ls_mess ="Pembayaran Klaim berhasil dilakukan, session dilanjutkan..";

				// * JULI 2020: START ARSIP DIGITAL 
				// * START: GET REPORT URL
				// * note: harus disamakan dengan pembentukan report di atas 
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
				SELECT 	A.KANAL_PELAYANAN, A.NOMOR_IDENTITAS, A.KODE_TK,
				NVL((
					SELECT B.STATUS_VALID_IDENTITAS FROM KN.VW_KN_TK  B
					WHERE B.KODE_TK = A.KODE_TK 
					AND B.NOMOR_IDENTITAS = A.NOMOR_IDENTITAS
					AND ROWNUM = 1
				),'T') STATUS_VALID_IDENTITAS,
				(
					select count(*) jml_dokumen_digital from pn.pn_klaim b where kanal_pelayanan in (select KODE from MS.MS_LOOKUP where TIPE = 'KANALKLM' and KATEGORI = 'DOKUMEN_DIGITAL') 
										and b.kode_klaim = a.kode_klaim
				) JML_DOKUMEN_DIGITAL
				FROM 		PN.PN_KLAIM A
				WHERE 	A.KODE_KLAIM = '$ls_kode_klaim'
				";
				$DB->parse($sql);
				$DB->execute();
				$row = $DB->nextrow();
				$ls_kanal_pelayanan = $row["KANAL_PELAYANAN"];
				$ls_nomor_identitas = $row["NOMOR_IDENTITAS"];
				$ls_kode_tk = $row["KODE_TK"];
				$ls_status_valid_identitas = $row["STATUS_VALID_IDENTITAS"];
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
					$ls_temp_url_voucher = exec_rpt_enc_new_drc(9, $ls_modul3, $ls_nama_rpt3, $ls_user_param_3, $tipe3);
					// end get url report voucher
					
					// get url report spb
					$ls_user_param_2 = $ls_user_param;
					$tipe2 = isset($iscetak) ? "PDF" : "PDF";
					$ls_modul2 = "PN";
					$ls_nama_rpt2 .= "PNR502902.rdf";
					$ls_temp_url_spb = exec_rpt_enc_new_drc(9, $ls_modul2, $ls_nama_rpt2, $ls_user_param_2, $tipe2);
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
					oci_bind_by_name($proc_sign, ":p_sign_petugas_rekam", $ls_petugas_rekam, 30);
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
					$action_arsip 		= "PROSES_DOKUMEN_DIGITAL_PEMBAYARAN";

					include "pn5048_arsip_dokumen_action.php";

					// * JULI 2020: END ARSIP DIGITAL 
				}
				
				// 12102020, 
				// start service untuk kirim data balikan ke Dukcapil
				if ($ls_status_valid_identitas == "Y")
				{
					$headers = array
					(
						'Content-Type: application/json',
						'X-Forwarded-For: ' . $ipfwd
					);
					
					$data_balikan = array
					(
						"chId" => "SMILE", 
						"reqId" => $username, 
						"data" => array
						(
							array
							(
								"NIK" => $ls_nomor_identitas, 
								"NO_REFERENSI" => $ls_kode_tk
							)
						) 
					);
					
					$result_data_balikan = api_json_call1($wsIp . "/JSEKTP/DataBalikan", $headers,  $data_balikan);			
				}
				// end service untuk kirim data balikan ke Dukcapil
				
				// 17042021
				// start TINDAK LANJUT OTOMATIS MONITORING PENGECEKAN MANFAAT BEASISWA PP NOMOR 82 TAHUN 2019 BERDASARKAN TANGGAL BAYAR KLAIM INDUK
				// PN5059 = Form Tindak Lanjut Monitoring (Manual), PN5004 = Form Pembayaran Klaim (Otomatis)
				$qry_monitoring = "
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

					$proc = $DB->parse($qry_monitoring);
					
					$ls_flag_dapat_beasiswa = "Y";
					$ls_kode_hasil_pengecekan = "BEA01";
					$ls_kode_pointer_tindak_lanjut = "PN5004";
					$ls_keterangan = "Tindak lanjut otomatis dari pembayaran beasiswa dengan kode klaim ".$ls_kode_klaim;
					
					oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim, 100);
					oci_bind_by_name($proc, ":p_kode_klaim_tindak_lanjut", $ls_kode_klaim, 100);
					oci_bind_by_name($proc, ":p_kode_kantor", $ls_kode_kantor, 30);
					oci_bind_by_name($proc, ":p_flag_dapat_beasiswa", $ls_flag_dapat_beasiswa, 30);
					oci_bind_by_name($proc, ":p_kode_hasil_pengecekan", $ls_kode_hasil_pengecekan, 100);
					oci_bind_by_name($proc, ":p_kode_pointer_tindak_lanjut", $ls_kode_pointer_tindak_lanjut, 100);
					oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan, 4000);
					oci_bind_by_name($proc, ":p_user", $username, 30);
					oci_bind_by_name($proc, ":p_sukses", $ls_sukses_monitoring, 10);
					oci_bind_by_name($proc, ":p_mess", $ls_mess_monitoring, 1000);
					
					$DB->execute();
				
				// end TINDAK LANJUT OTOMATIS MONITORING PENGECEKAN MANFAAT BEASISWA PP NOMOR 82 TAHUN 2019 BERDASARKAN TANGGAL BAYAR KLAIM INDUK

				// --------------------------------------------------JKP 21/11/2021 -----------------------------------------------
				$sql_siapkerja = "
						SELECT A.ID_POINTER_ASAL, A.KODE_TIPE_KLAIM FROM PN.PN_KLAIM A 
							WHERE A.KODE_KLAIM = '$ls_kode_klaim'
					";
				$DB->parse($sql_siapkerja);
				$DB->execute();
				$row = $DB->nextrow();
				$ls_kode_pengajuan_siapkerja = $row["ID_POINTER_ASAL"];
				$ls_kode_tipe_klaim_jkp 	 = $row["KODE_TIPE_KLAIM"];

				if($ls_kode_tipe_klaim_jkp=="JKP01"){
					

					$headers = array(
					'Content-Type: application/json',
					'X-Forwarded-For: ' . $ipfwd
					);
					
					$sql = "SELECT A.FLAG_CEK_MENUNGGAK_IURAN from SIAPKERJA.SK_KLAIM_PENGAJUAN A WHERE A.KODE_KLAIM = '$ls_kode_klaim' ";											 	
					$ECDB->parse($sql);
					$ECDB->execute();
					$row = $ECDB->nextrow();		
					$ls_flag_cek_menunggak_iuran = $row['FLAG_CEK_MENUNGGAK_IURAN'];

					if($ls_flag_cek_menunggak_iuran != 'Y'){
						$data_send = array(
							'chId'              => 'SMILE',
							'reqId'             => $username,
							"kodePengajuan"	  	=> $ls_kode_pengajuan_siapkerja,
							"statusPengajuan"	=> "KLJKP004",
							"keterangan"		=> "",
							"petugasRekam"	  	=> $username
						);
				
						$result_send = api_json_call1($wsIp . "/JSKlaimJKP/UpdateClaimStatus", $headers,  $data_send);
					}

					$data_send_tgl_bayar = array(
						'chId'              => 'SMILE',
						'reqId'             => $username,
						"kodePengajuan"	  	=> $ls_kode_pengajuan_siapkerja,
						"petugasRekam"	  	=> $username
					);

					$result_send_tgl_bayar = api_json_call1($wsIp . "/JSKlaimJKP/UpdateTglBayar", $headers,  $data_send_tgl_bayar);
					
				}

				// --------------------------------------------------JKP 21/11/2021 -----------------------------------------------
				
				// 17022021, penyesuaian untuk e-survey
					// 04122020
					// start pengiriman e-survey kepuasan pelanggan
					// kondisi:
					// - Penetapan Ulang tidak perlu kirim email (KODE_KLAIM_INDUK is null)
					// - Kanal BPJSTKU tidak perlu dikirimkan email peserta (Kanal Pelayanan = 25)
					// - jika SPO di checklist di agenda, tidak perlu mengirimkan link ke email peserta (KODE_POINTER_ASAL not in ('SPO'))
					// - Trigger pengiriman survey setelah submit bayar
					$query_email  = "
					select count(a.EMAIL) total
					from PN.PN_KLAIM_PENERIMA_MANFAAT a
					where exists
					(
						select null from PN.PN_KLAIM_MANFAAT_DETIL b
						where b.KODE_KLAIM = a.KODE_KLAIM
						and b.KODE_TIPE_PENERIMA = a.KODE_TIPE_PENERIMA
					)
					and exists
					(
						select null from PN.PN_KLAIM c
						where c.KODE_KLAIM = a.KODE_KLAIM
						and nvl(c.STATUS_BATAL,'X') = 'T'
						and c.KODE_KLAIM_INDUK is null
						and c.KANAL_PELAYANAN not in ('25')
						and nvl(c.KODE_POINTER_ASAL,'X') not in ('SPO')
					)
					and a.KODE_KLAIM = '$ls_kode_klaim'
				";
				
				$ls_email_penerima_manfaat = "";
				$DB->parse($query_email);
				if($DB->execute()){
					if($row=$DB->nextrow()){
					$ls_total=$row['TOTAL']; 
					}
				}
				
				// for($i=0;$i<$ls_total;$i++)
				// 	{
						$data_merged = array(
							"chId" => "E-SURVEY", 
							"reqId" => $username, 
							"data" => array(
								"kodeKlaim" => $ls_kode_klaim,
								"user" => $username,
								"kodeTipePenerima" => $ls_kode_tipe_penerima
							)
						);
						// var_dump($data_merged);die();

						$headers_merged = array(
							'Content-Type: application/json',
							'X-Forwarded-For: ' . $ipfwd
						);
													
						$result_merged = api_json_call1($wsIp . "/JSSurvey/GenerateRespondenKlaim", $headers_merged, $data_merged); 
					// }
				// end pengiriman e-survey kepuasan pelanggan

				
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
			echo "window.opener.location.replace('../ajax/pn5043_pembayaran_entry.php?task=view&kode_klaim=$ls_kode_klaim&kode_pembayaran=$ls_kode_pembayaran&form_root=$ls_form_root&root_sender=$ls_root_sender&sender=$ls_sender&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay&msg=$ls_mess&popup_bayar=done');";
				echo "</script>";		
		}else
		{
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
			echo "window.opener.location.replace('../ajax/pn5043_pembayaran_entry.php?task=New&kode_klaim=$ls_kode_klaim&kode_tipe_penerima=$ls_kode_tipe_penerima&kd_prg=$ls_kd_prg&form_root=$ls_form_root&root_sender=$ls_root_sender&sender=$ls_sender&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay&ls_error=1&msg=$ls_mess');";
				echo "</script>";		
		}
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "window.close();";
		echo "</script>";

	 // -----------------------------start update pending matters 09032022------------------------
	}  else
	{
			$ls_mess = "Mohon maaf proses pembayaran gagal karena session habis, silahkan melakukan login ulang kembali dan memproses kembali klaim.";
		echo "<script language=\"JavaScript\" type=\"text/javascript\">";
		echo "window.opener.location.replace('../ajax/pn5043_pembayaran_entry.php?task=New&kode_klaim=$ls_kode_klaim&kode_tipe_penerima=$ls_kode_tipe_penerima&kd_prg=$ls_kd_prg&form_root=$ls_form_root&root_sender=$ls_root_sender&sender=$ls_sender&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&tglawaldisplay=$ld_tglawaldisplay&tglakhirdisplay=$ld_tglakhirdisplay&ls_error=1&msg=$ls_mess');";
			echo "</script>";		
	}
	 // -----------------------------end update pending matters 09032022------------------------

  	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "window.close();";
	echo "</script>";
  }
  
  function api_json_call1($apiurl, $header, $data) {
	$curl = curl_init();
  
	curl_setopt_array(
	  $curl, 
	  array(
		CURLOPT_URL => $apiurl,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($data),
		CURLOPT_HTTPHEADER => $header,
	  )
	);
  
	$response = curl_exec($curl);
	$err = curl_error($curl);
	curl_close($curl);
  
	if ($err) {
	  $jdata["ret"] = -1;
	  $jdata["msg"] = "cURL Error #:" . $err;
	  $result = $jdata;
	} else {
	  $result = json_decode($response);
	}
  
	return $result;
  }
  ?>

	<img src="../../images/warning.gif" align="left" hspace="10" vspace="0"> <b><font color="#ff0000">Proses...</font></b>		
	
  <form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
    <div id="formframe">
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
      <input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>">	
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">	
			<input type="hidden" id="kode_bank_pembayar" name="kode_bank_pembayar" value="<?=$ls_kode_bank_pembayar;?>">	
			<input type="hidden" id="kode_buku" name="kode_buku" value="<?=$ls_kode_buku;?>">	
			<input type="hidden" id="kode_cara_bayar" name="kode_cara_bayar" value="<?=$ls_kode_cara_bayar;?>">	
			<input type="hidden" id="kode_kantor_pembayaran" name="kode_kantor_pembayaran" value="<?=$ls_kode_kantor_pembayaran;?>">	
			<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
			<input type="hidden" id="rg_kategori" name="rg_kategori" value="<?=$ls_rg_kategori;?>">	
			<input type="hidden" id="status_submit" name="status_submit" value="<?=$ls_status_submit;?>">
			<input type="submit" id="btnsubmit" name="btnsubmit" value="" style="color:#fbf7c8;"/>
			
			<input type="hidden" id="form_root" name="form_root" value="<?=$ls_form_root;?>">	
			<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">	
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">	
			<input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>">	
			<input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>">	
			<input type="hidden" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>">	
			<input type="hidden" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>">	

    </div>													 										
  </form>	

  <script language="javascript">
			if ($('#status_submit').val()=="")
			{		
  			document.getElementById("btnsubmit").click();
			}
  </script>
	
</body>
</html>						
	