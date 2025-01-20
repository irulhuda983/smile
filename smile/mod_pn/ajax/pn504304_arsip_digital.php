<?php

session_start();
include "../../includes/conf_global.php";
require_once "../../includes/fungsi.php";
require_once "../../includes/fungsi_newrpt.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

if ($username==""){$username = $_SESSION["USER"];}

// * MARET 2022: START ARSIP DIGITAL 
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

					// * MARET 2022: END ARSIP DIGITAL 


				}

?>