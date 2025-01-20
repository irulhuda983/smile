<?php
	// * JULI 2020: ARSIP DIGITAL
	// * START: TAMBAHAN UNTUK PROSES PENGARSIPAN
	// * 17 MARET 2022 : Permasalahan Berulang
	$headers = array(
		'Content-Type: application/json',
		'X-Forwarded-For: ' . $ipfwd
	);

	$sql = "
	select 	to_char(sysdate, 'yyyymm') blth,
					(select kode_kantor from pn.pn_klaim where kode_klaim = :p_kode_klaim) kode_kantor,
					(select kanal_pelayanan from pn.pn_klaim where kode_klaim = :p_kode_klaim) kanal_pelayanan,
					(select kode_tipe_klaim from pn.pn_klaim where kode_klaim = :p_kode_klaim) kode_tipe_klaim
	from 		dual";

	$proc = $DB->parse($sql);
	oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_blth = $row["BLTH"];
	$ls_kode_kantor = $row["KODE_KANTOR"];
	$ls_kanal_pelayanan = $row["KANAL_PELAYANAN"];
	$ls_kode_tipe_klaim = $row["KODE_TIPE_KLAIM"];
	
	// cek lumsum atau berkala
	$sql_lumsum_berkala = "
			SELECT 	KANAL_PELAYANAN, KODE_TIPE_KLAIM,
			(
				select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
				where x.kode_klaim = a.kode_klaim
				and x.kode_manfaat = y.kode_manfaat
				and nvl(y.flag_berkala,'T')='Y'
				and nvl(x.nom_biaya_disetujui,0)<>0
			) cnt_berkala,
			(
				select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y
				where x.kode_klaim = a.kode_klaim
				and x.kode_manfaat = y.kode_manfaat
				and nvl(y.flag_berkala,'T')='T'
				and nvl(x.nom_biaya_disetujui,0)<>0
			) cnt_lumpsum
			FROM 		PN.PN_KLAIM a
			WHERE 	KODE_KLAIM = '$id_dokumen_arsip'
			";
	$DB->parse($sql_lumsum_berkala);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_cnt_lumpsum = $row["CNT_LUMPSUM"];
	$ls_cnt_berkala = $row["CNT_BERKALA"];

	$ls_nama_bucket_storage = "arsip";
	$ls_nama_folder_storage = "$ls_kode_kantor/$ls_blth/klaim";
	
	// $wsIpDocument = $CONFIG_GLOBAL["WS_DOCUMENT"];
	// $wsIp = $CONFIG_GLOBAL["WS_TEST"];
		
	if ($action_arsip == "PROSES_DOKUMEN_DIGITAL_PEMBAYARAN") {
		// cek jika kanal pelayanan dari klaim kolektif JHT BPJSTKU
		// 25 jika sumber klaim dari klaim kolektif BPJSTKU
		if ($ls_kanal_pelayanan == "25")
		{
			// 1. dokumen kwitansi
			$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
			$ls_jenis_dokumen_kwitansi = "JD105";
			$ls_kode_dokumen_kwitansi  = "JD105-D1007";
			// 2. dokumen surat perintah bayar
			$ls_url_file_spb           = $arr_url_file["spb"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
			$ls_jenis_dokumen_spb      = "JD105";
			$ls_kode_dokumen_spb       = "JD105-D1008";
			// 3. dokumen voucher
			$ls_url_file_voucher       = $arr_url_file["voucher"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
			$ls_jenis_dokumen_voucher  = "JD105";
			$ls_kode_dokumen_voucher   = "JD105-D1009";
			// 4. dokumen bukti potong pph21
			$ls_url_file_pph21         = $arr_url_file["pph21"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DTAXR301407.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qkodepointer_asal%3D%27JM09%27%26qidpointer_asal%3D%27BYR1907224058629%27';
			$ls_jenis_dokumen_pph21    = "JD105";
			$ls_kode_dokumen_pph21     = "JD105-D1010";
					
			$data_store_multidoc = array(
				"chId"             => "SMILE",
				"reqId"            => $reqid_arsip,
				"idDokumen"        => $id_dokumen_arsip,
				"namaBucketTujuan" => $ls_nama_bucket_storage,
				"namaFolderTujuan" => $ls_nama_folder_storage,
				"docs" 						 => array(
					array(
						"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
						"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
						"urlDokumen"       => $ls_url_file_kwitansi
					),
					array(
						"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
						"kodeDokumen"      => $ls_kode_dokumen_spb,
						"urlDokumen"       => $ls_url_file_spb
					),
					array(
						"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
						"kodeDokumen"      => $ls_kode_dokumen_voucher,
						"urlDokumen"       => $ls_url_file_voucher
					),
					array(
						"kodeJenisDokumen" => $ls_jenis_dokumen_pph21,
						"kodeDokumen"      => $ls_kode_dokumen_pph21,
						"urlDokumen"       => $ls_url_file_pph21
					)
				)
			);
			// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
			$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
			// sign all document
			
			// cleanup previous dokumen
			$sql = "
			select  id_arsip
			from    pn.pn_arsip_dokumen_sign
			where 	id_arsip in ( 
							select 	id_arsip
							from 		pn.pn_arsip_dokumen
							where 	id_dokumen = :p_kode_klaim
															and kode_jenis_dokumen = 'JD105'
															and kode_dokumen in ('JD105-D1007', 'JD105-D1008', 'JD105-D1009', 'JD105-D1010')
							) and status_sign <> 'Y'";

			$proc = $DB->parse($sql);
			oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
			$DB->execute();
			while($row = $DB->nextrow()) {
				$data_presign = array(
					"chId" => "SMILE",
					"reqId" => $reqid_arsip,
					"idArsip" => $row["ID_ARSIP"]
				);
				// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
				$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
				if ($result_presign->ret == "0") {
					// sign document
					$idArsip = $result_presign->data->idArsip;
					$docSigns = $result_presign->data->docSigns;
					if (ExtendedFunction::count($docSigns) > 0) {
						foreach ($docSigns as $sign) {
							$newDocSigns = array();

							if ($sign->kodeDokumen == "JD105-D1008" && $sign->kodeDokumenSign == "JD105-D1008-0001") {
								$sign->dataUserSign = $arr_temp_data_user_sign_spb;
							} elseif ($sign->kodeDokumen == "JD105-D1009" && $sign->kodeDokumenSign == "JD105-D1009-0001") {
								$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
							} elseif ($sign->kodeDokumen == "JD105-D1009" && $sign->kodeDokumenSign == "JD105-D1009-0002") {
								$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
							} elseif ($sign->kodeDokumen == "JD105-D1009" && $sign->kodeDokumenSign == "JD105-D1009-0003") {
								$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
							}
							$sign->action = "sign";
							array_push($newDocSigns, $sign);

							$data_sign = array(
								"chId" 			=> "SMILE",
								"reqId" 		=> $reqid_arsip,
								"idArsip" 	=> $idArsip,
								"docSigns"	=> $newDocSigns
							);
							
							// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
							$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
							sleep(1);
						}
					}
				}
			}
		}
		else
		{
			if($ls_kode_tipe_klaim == "JHT01")
			{				
				// 1. dokumen kwitansi
				$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
				$ls_jenis_dokumen_kwitansi = "JD101";
				$ls_kode_dokumen_kwitansi  = "JD101-D1007";
				// 2. dokumen surat perintah bayar
				$ls_url_file_spb           = $arr_url_file["spb"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
				$ls_jenis_dokumen_spb      = "JD101";
				$ls_kode_dokumen_spb       = "JD101-D1008";
				// 3. dokumen voucher
				$ls_url_file_voucher       = $arr_url_file["voucher"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
				$ls_jenis_dokumen_voucher  = "JD101";
				$ls_kode_dokumen_voucher   = "JD101-D1009";
				// 4. dokumen bukti potong pph21
				$ls_url_file_pph21         = $arr_url_file["pph21"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DTAXR301407.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qkodepointer_asal%3D%27JM09%27%26qidpointer_asal%3D%27BYR1907224058629%27';
				$ls_jenis_dokumen_pph21    = "JD101";
				$ls_kode_dokumen_pph21     = "JD101-D1010";
			
				$data_store_multidoc = array(
					"chId"             => "SMILE",
					"reqId"            => $reqid_arsip,
					"idDokumen"        => $id_dokumen_arsip,
					"namaBucketTujuan" => $ls_nama_bucket_storage,
					"namaFolderTujuan" => $ls_nama_folder_storage,
					"docs" 						 => array(
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
							"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
							"urlDokumen"       => $ls_url_file_kwitansi
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
							"kodeDokumen"      => $ls_kode_dokumen_spb,
							"urlDokumen"       => $ls_url_file_spb
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
							"kodeDokumen"      => $ls_kode_dokumen_voucher,
							"urlDokumen"       => $ls_url_file_voucher
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_pph21,
							"kodeDokumen"      => $ls_kode_dokumen_pph21,
							"urlDokumen"       => $ls_url_file_pph21
						)
					)
				);
				// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
				$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

				// sign all document
				
				// cleanup previous dokumen
				$sql = "
				select  id_arsip
				from    pn.pn_arsip_dokumen_sign
				where 	id_arsip in ( 
								select 	id_arsip
								from 		pn.pn_arsip_dokumen
								where 	id_dokumen = :p_kode_klaim
																and kode_jenis_dokumen = 'JD101'
																and kode_dokumen in ('JD101-D1007', 'JD101-D1008', 'JD101-D1009', 'JD101-D1010')
								) and status_sign <> 'Y'";

				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
				$DB->execute();
				while($row = $DB->nextrow()) {
					$data_presign = array(
						"chId" => "SMILE",
						"reqId" => $reqid_arsip,
						"idArsip" => $row["ID_ARSIP"]
					);
					// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					if ($result_presign->ret == "0") {
						// sign document
						$idArsip = $result_presign->data->idArsip;
						$docSigns = $result_presign->data->docSigns;
						if (ExtendedFunction::count($docSigns) > 0) {
							foreach ($docSigns as $sign) {
								$newDocSigns = array();

								if ($sign->kodeDokumen == "JD101-D1008" && $sign->kodeDokumenSign == "JD101-D1008-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_spb;
								} elseif ($sign->kodeDokumen == "JD101-D1009" && $sign->kodeDokumenSign == "JD101-D1009-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
								} elseif ($sign->kodeDokumen == "JD101-D1009" && $sign->kodeDokumenSign == "JD101-D1009-0002") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
								} elseif ($sign->kodeDokumen == "JD101-D1009" && $sign->kodeDokumenSign == "JD101-D1009-0003") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
								}
								$sign->action = "sign";
								array_push($newDocSigns, $sign);

								$data_sign = array(
									"chId" 			=> "SMILE",
									"reqId" 		=> $reqid_arsip,
									"idArsip" 	=> $idArsip,
									"docSigns"	=> $newDocSigns
								);
								
								// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
								$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								sleep(1);
							}
						}
					}
				}
			}
			else if($ls_kode_tipe_klaim == "JKM01")
			{
				// untuk JKM pemotongan Pph21 tidak ada
				// 1. dokumen kwitansi
				$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
				$ls_jenis_dokumen_kwitansi = "JD102";
				$ls_kode_dokumen_kwitansi  = "JD102-D1002";
				// 2. dokumen surat perintah bayar
				$ls_url_file_spb           = $arr_url_file["spb"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
				$ls_jenis_dokumen_spb      = "JD102";
				$ls_kode_dokumen_spb       = "JD102-D1003";
				// 3. dokumen voucher
				$ls_url_file_voucher       = $arr_url_file["voucher"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
				$ls_jenis_dokumen_voucher  = "JD102";
				$ls_kode_dokumen_voucher   = "JD102-D1001";
			
				$data_store_multidoc = array(
					"chId"             => "SMILE",
					"reqId"            => $reqid_arsip,
					"idDokumen"        => $id_dokumen_arsip,
					"namaBucketTujuan" => $ls_nama_bucket_storage,
					"namaFolderTujuan" => $ls_nama_folder_storage,
					"docs" 						 => array(
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
							"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
							"urlDokumen"       => $ls_url_file_kwitansi
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
							"kodeDokumen"      => $ls_kode_dokumen_spb,
							"urlDokumen"       => $ls_url_file_spb
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
							"kodeDokumen"      => $ls_kode_dokumen_voucher,
							"urlDokumen"       => $ls_url_file_voucher
						)
					)
				);
				// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
				$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

				// sign all document
				
				// cleanup previous dokumen
				$sql = "
				select  id_arsip
				from    pn.pn_arsip_dokumen_sign
				where 	id_arsip in ( 
								select 	id_arsip
								from 		pn.pn_arsip_dokumen
								where 	id_dokumen = :p_kode_klaim
																and kode_jenis_dokumen = 'JD102'
																and kode_dokumen in ('JD102-D1001', 'JD102-D1002', 'JD102-D1003')
								) and status_sign <> 'Y'";

				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
				$DB->execute();
				while($row = $DB->nextrow()) {
					$data_presign = array(
						"chId" => "SMILE",
						"reqId" => $reqid_arsip,
						"idArsip" => $row["ID_ARSIP"]
					);
					// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					if ($result_presign->ret == "0") {
						// sign document
						$idArsip = $result_presign->data->idArsip;
						$docSigns = $result_presign->data->docSigns;
						if (ExtendedFunction::count($docSigns) > 0) {
							foreach ($docSigns as $sign) {
								$newDocSigns = array();

								if ($sign->kodeDokumen == "JD102-D1003" && $sign->kodeDokumenSign == "JD102-D1003-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_spb;
								} elseif ($sign->kodeDokumen == "JD102-D1001" && $sign->kodeDokumenSign == "JD102-D1001-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
								} elseif ($sign->kodeDokumen == "JD102-D1001" && $sign->kodeDokumenSign == "JD102-D1001-0002") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
								} elseif ($sign->kodeDokumen == "JD102-D1001" && $sign->kodeDokumenSign == "JD102-D1001-0003") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
								}
								$sign->action = "sign";
								array_push($newDocSigns, $sign);

								$data_sign = array(
									"chId" 			=> "SMILE",
									"reqId" 		=> $reqid_arsip,
									"idArsip" 	=> $idArsip,
									"docSigns"	=> $newDocSigns
								);
								
								// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
								$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								sleep(1);
							}
						}
					}
				}
			}
			else if($ls_kode_tipe_klaim == "JKK01")
			{
				// untuk JKK pemotongan Pph21 tidak ada
				// 1. dokumen kwitansi
				$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
				$ls_jenis_dokumen_kwitansi = "JD108";
				$ls_kode_dokumen_kwitansi  = "JD108-D1002";
				// 2. dokumen surat perintah bayar
				$ls_url_file_spb           = $arr_url_file["spb"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
				$ls_jenis_dokumen_spb      = "JD108";
				$ls_kode_dokumen_spb       = "JD108-D1003";
				// 3. dokumen voucher
				$ls_url_file_voucher       = $arr_url_file["voucher"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
				$ls_jenis_dokumen_voucher  = "JD108";
				$ls_kode_dokumen_voucher   = "JD108-D1001";
			
				$data_store_multidoc = array(
					"chId"             => "SMILE",
					"reqId"            => $reqid_arsip,
					"idDokumen"        => $id_dokumen_arsip,
					"namaBucketTujuan" => $ls_nama_bucket_storage,
					"namaFolderTujuan" => $ls_nama_folder_storage,
					"docs" 						 => array(
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
							"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
							"urlDokumen"       => $ls_url_file_kwitansi
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
							"kodeDokumen"      => $ls_kode_dokumen_spb,
							"urlDokumen"       => $ls_url_file_spb
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
							"kodeDokumen"      => $ls_kode_dokumen_voucher,
							"urlDokumen"       => $ls_url_file_voucher
						)
					)
				);
				// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
				$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

				// sign all document
				
				// cleanup previous dokumen
				$sql = "
				select  id_arsip
				from    pn.pn_arsip_dokumen_sign
				where 	id_arsip in ( 
								select 	id_arsip
								from 		pn.pn_arsip_dokumen
								where 	id_dokumen = :p_kode_klaim
																and kode_jenis_dokumen = 'JD108'
																and kode_dokumen in ('JD108-D1001', 'JD108-D1002', 'JD108-D1003')
								) and status_sign <> 'Y'";

				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
				$DB->execute();
				while($row = $DB->nextrow()) {
					$data_presign = array(
						"chId" => "SMILE",
						"reqId" => $reqid_arsip,
						"idArsip" => $row["ID_ARSIP"]
					);
					// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					if ($result_presign->ret == "0") {
						// sign document
						$idArsip = $result_presign->data->idArsip;
						$docSigns = $result_presign->data->docSigns;
						if (ExtendedFunction::count($docSigns) > 0) {
							foreach ($docSigns as $sign) {
								$newDocSigns = array();

								if ($sign->kodeDokumen == "JD108-D1003" && $sign->kodeDokumenSign == "JD108-D1003-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_spb;
								} elseif ($sign->kodeDokumen == "JD108-D1001" && $sign->kodeDokumenSign == "JD108-D1001-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
								} elseif ($sign->kodeDokumen == "JD108-D1001" && $sign->kodeDokumenSign == "JD108-D1001-0002") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
								} elseif ($sign->kodeDokumen == "JD108-D1001" && $sign->kodeDokumenSign == "JD108-D1001-0003") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
								}
								$sign->action = "sign";
								array_push($newDocSigns, $sign);

								$data_sign = array(
									"chId" 			=> "SMILE",
									"reqId" 		=> $reqid_arsip,
									"idArsip" 	=> $idArsip,
									"docSigns"	=> $newDocSigns
								);
								
								// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
								$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								sleep(1);
							}
						}
					}
				}
			}
			else if($ls_kode_tipe_klaim == "JPN01")
			{
				// cek lumsum atau berkala
				// JD103	DOKUMEN KLAIM JPN LUMSUM
				if ($ls_cnt_lumpsum > 0)
				{
					// 1. dokumen kwitansi
					$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
					$ls_jenis_dokumen_kwitansi = "JD103";
					$ls_kode_dokumen_kwitansi  = "JD103-D1002";
					// 2. dokumen surat perintah bayar
					$ls_url_file_spb           = $arr_url_file["spb"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
					$ls_jenis_dokumen_spb      = "JD103";
					$ls_kode_dokumen_spb       = "JD103-D1003";
					// 3. dokumen voucher
					$ls_url_file_voucher       = $arr_url_file["voucher"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
					$ls_jenis_dokumen_voucher  = "JD103";
					$ls_kode_dokumen_voucher   = "JD103-D1001";
					// 4. dokumen bukti potong pph21
					$ls_url_file_pph21         = $arr_url_file["pph21"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DTAXR301407.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qkodepointer_asal%3D%27JM09%27%26qidpointer_asal%3D%27BYR1907224058629%27';
					$ls_jenis_dokumen_pph21    = "JD103";
					$ls_kode_dokumen_pph21     = "JD103-D1004";
				
					$data_store_multidoc = array(
						"chId"             => "SMILE",
						"reqId"            => $reqid_arsip,
						"idDokumen"        => $id_dokumen_arsip,
						"namaBucketTujuan" => $ls_nama_bucket_storage,
						"namaFolderTujuan" => $ls_nama_folder_storage,
						"docs" 						 => array(
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
								"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
								"urlDokumen"       => $ls_url_file_kwitansi
							),
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
								"kodeDokumen"      => $ls_kode_dokumen_spb,
								"urlDokumen"       => $ls_url_file_spb
							),
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
								"kodeDokumen"      => $ls_kode_dokumen_voucher,
								"urlDokumen"       => $ls_url_file_voucher
							),
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_pph21,
								"kodeDokumen"      => $ls_kode_dokumen_pph21,
								"urlDokumen"       => $ls_url_file_pph21
							)
						)
					);
					// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
					$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

					// sign all document
					
					// cleanup previous dokumen
					$sql = "
					select  id_arsip
					from    pn.pn_arsip_dokumen_sign
					where 	id_arsip in ( 
									select 	id_arsip
									from 		pn.pn_arsip_dokumen
									where 	id_dokumen = :p_kode_klaim
																	and kode_jenis_dokumen = 'JD103'
																	and kode_dokumen in ('JD103-D1002', 'JD103-D1003', 'JD103-D1001', 'JD103-D1004')
									) and status_sign <> 'Y'";

					$proc = $DB->parse($sql);
					oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
					$DB->execute();
					while($row = $DB->nextrow()) {
						$data_presign = array(
							"chId" => "SMILE",
							"reqId" => $reqid_arsip,
							"idArsip" => $row["ID_ARSIP"]
						);
						// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
						$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
						if ($result_presign->ret == "0") {
							// sign document
							$idArsip = $result_presign->data->idArsip;
							$docSigns = $result_presign->data->docSigns;
							if (ExtendedFunction::count($docSigns) > 0) {
								foreach ($docSigns as $sign) {
									$newDocSigns = array();

									if ($sign->kodeDokumen == "JD103-D1003" && $sign->kodeDokumenSign == "JD103-D1003-0001") {
										$sign->dataUserSign = $arr_temp_data_user_sign_spb;
									} elseif ($sign->kodeDokumen == "JD103-D1001" && $sign->kodeDokumenSign == "JD103-D1001-0001") {
										$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
									} elseif ($sign->kodeDokumen == "JD103-D1001" && $sign->kodeDokumenSign == "JD103-D1001-0002") {
										$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
									} elseif ($sign->kodeDokumen == "JD103-D1001" && $sign->kodeDokumenSign == "JD103-D1001-0003") {
										$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
									}
									$sign->action = "sign";
									array_push($newDocSigns, $sign);

									$data_sign = array(
										"chId" 			=> "SMILE",
										"reqId" 		=> $reqid_arsip,
										"idArsip" 	=> $idArsip,
										"docSigns"	=> $newDocSigns
									);
									
									// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
									$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
									sleep(1);
								}
							}
						}
					}
				}
				// JD104	DOKUMEN KLAIM JPN BERKALA
				if ($ls_cnt_berkala > 0)
				{
					// 1. dokumen kwitansi
					$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
					$ls_jenis_dokumen_kwitansi = "JD104";
					$ls_kode_dokumen_kwitansi  = "JD104-D1002";
					// 2. dokumen surat perintah bayar
					$ls_url_file_spb           = $arr_url_file["spb"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
					$ls_jenis_dokumen_spb      = "JD104";
					$ls_kode_dokumen_spb       = "JD104-D1003";
					// 3. dokumen voucher
					$ls_url_file_voucher       = $arr_url_file["voucher"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
					$ls_jenis_dokumen_voucher  = "JD104";
					$ls_kode_dokumen_voucher   = "JD104-D1001";
					// 4. dokumen bukti potong pph21
					$ls_url_file_pph21         = $arr_url_file["pph21"]; //$CONFIG_GLOBAL["REPORTCLEARANCE"] . '/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DTAXR301407.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qkodepointer_asal%3D%27JM09%27%26qidpointer_asal%3D%27BYR1907224058629%27';
					$ls_jenis_dokumen_pph21    = "JD104";
					$ls_kode_dokumen_pph21     = "JD104-D1004";
				
					$data_store_multidoc = array(
						"chId"             => "SMILE",
						"reqId"            => $reqid_arsip,
						"idDokumen"        => $id_dokumen_arsip,
						"namaBucketTujuan" => $ls_nama_bucket_storage,
						"namaFolderTujuan" => $ls_nama_folder_storage,
						"docs" 						 => array(
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
								"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
								"urlDokumen"       => $ls_url_file_kwitansi
							),
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
								"kodeDokumen"      => $ls_kode_dokumen_spb,
								"urlDokumen"       => $ls_url_file_spb
							),
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
								"kodeDokumen"      => $ls_kode_dokumen_voucher,
								"urlDokumen"       => $ls_url_file_voucher
							),
							array(
								"kodeJenisDokumen" => $ls_jenis_dokumen_pph21,
								"kodeDokumen"      => $ls_kode_dokumen_pph21,
								"urlDokumen"       => $ls_url_file_pph21
							)
						)
					);
					// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
					$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

					// sign all document
					
					// cleanup previous dokumen
					$sql = "
					select  id_arsip
					from    pn.pn_arsip_dokumen_sign
					where 	id_arsip in ( 
									select 	id_arsip
									from 		pn.pn_arsip_dokumen
									where 	id_dokumen = :p_kode_klaim
																	and kode_jenis_dokumen = 'JD104'
																	and kode_dokumen in ('JD104-D1001', 'JD104-D1002', 'JD104-D1003', 'JD104-D1004')
									) and status_sign <> 'Y'";

					$proc = $DB->parse($sql);
					oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
					$DB->execute();
					while($row = $DB->nextrow()) {
						$data_presign = array(
							"chId" => "SMILE",
							"reqId" => $reqid_arsip,
							"idArsip" => $row["ID_ARSIP"]
						);
						// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
						$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
						if ($result_presign->ret == "0") {
							// sign document
							$idArsip = $result_presign->data->idArsip;
							$docSigns = $result_presign->data->docSigns;
							if (ExtendedFunction::count($docSigns) > 0) {
								foreach ($docSigns as $sign) {
									$newDocSigns = array();

									if ($sign->kodeDokumen == "JD104-D1003" && $sign->kodeDokumenSign == "JD104-D1003-0001") {
										$sign->dataUserSign = $arr_temp_data_user_sign_spb;
									} elseif ($sign->kodeDokumen == "JD104-D1001" && $sign->kodeDokumenSign == "JD104-D1001-0001") {
										$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
									} elseif ($sign->kodeDokumen == "JD104-D1001" && $sign->kodeDokumenSign == "JD104-D1001-0002") {
										$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
									} elseif ($sign->kodeDokumen == "JD104-D1001" && $sign->kodeDokumenSign == "JD104-D1001-0003") {
										$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
									}
									$sign->action = "sign";
									array_push($newDocSigns, $sign);

									$data_sign = array(
										"chId" 			=> "SMILE",
										"reqId" 		=> $reqid_arsip,
										"idArsip" 	=> $idArsip,
										"docSigns"	=> $newDocSigns
									);
									
									// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
									$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
									sleep(1);
								}
							}
						}
					}
				}
			} 
			else if($ls_kode_tipe_klaim == "JKP01")
			{				
				// 1. dokumen kwitansi
				$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; 
				$ls_jenis_dokumen_kwitansi = "JD107";
				$ls_kode_dokumen_kwitansi  = "JD107-D1002";
				// 2. dokumen surat perintah bayar
				$ls_url_file_spb           = $arr_url_file["spb"]; 
				$ls_jenis_dokumen_spb      = "JD107";
				$ls_kode_dokumen_spb       = "JD107-D1003";
				// 3. dokumen voucher
				$ls_url_file_voucher       = $arr_url_file["voucher"]; 
				$ls_jenis_dokumen_voucher  = "JD107";
				$ls_kode_dokumen_voucher   = "JD107-D1001";
				// 4. dokumen bukti potong pph21
				$ls_url_file_pph21         = $arr_url_file["pph21"]; 
				$ls_jenis_dokumen_pph21    = "JD107";
				$ls_kode_dokumen_pph21     = "JD107-D1004";
			
				$data_store_multidoc = array(
					"chId"             => "SMILE",
					"reqId"            => $reqid_arsip,
					"idDokumen"        => $id_dokumen_arsip,
					"namaBucketTujuan" => $ls_nama_bucket_storage,
					"namaFolderTujuan" => $ls_nama_folder_storage,
					"docs" 						 => array(
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_kwitansi,
							"kodeDokumen"      => $ls_kode_dokumen_kwitansi,
							"urlDokumen"       => $ls_url_file_kwitansi
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_spb,
							"kodeDokumen"      => $ls_kode_dokumen_spb,
							"urlDokumen"       => $ls_url_file_spb
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_voucher,
							"kodeDokumen"      => $ls_kode_dokumen_voucher,
							"urlDokumen"       => $ls_url_file_voucher
						),
						array(
							"kodeJenisDokumen" => $ls_jenis_dokumen_pph21,
							"kodeDokumen"      => $ls_kode_dokumen_pph21,
							"urlDokumen"       => $ls_url_file_pph21
						)
					)
				);
				// $result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);
				$result_store_multidoc = api_json_call($wsIpDocument . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

				// sign all document
				
				// cleanup previous dokumen
				$sql = "
				select  id_arsip
				from    pn.pn_arsip_dokumen_sign
				where 	id_arsip in ( 
								select 	id_arsip
								from 		pn.pn_arsip_dokumen
								where 	id_dokumen = :p_kode_klaim
																and kode_jenis_dokumen = 'JD107'
																and kode_dokumen in ('JD107-D1002', 'JD107-D1003', 'JD107-D1001', 'JD107-D1004')
								) and status_sign <> 'Y'";

				$proc = $DB->parse($sql);
				oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
				$DB->execute();
				while($row = $DB->nextrow()) {
					$data_presign = array(
						"chId" => "SMILE",
						"reqId" => $reqid_arsip,
						"idArsip" => $row["ID_ARSIP"]
					);
					// $result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					$result_presign = api_json_call($wsIpDocument . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
					if ($result_presign->ret == "0") {
						// sign document
						$idArsip = $result_presign->data->idArsip;
						$docSigns = $result_presign->data->docSigns;
						if (ExtendedFunction::count($docSigns) > 0) {
							foreach ($docSigns as $sign) {
								$newDocSigns = array();

								if ($sign->kodeDokumen == "JD107-D1003" && $sign->kodeDokumenSign == "JD107-D1003-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_spb;
								} elseif ($sign->kodeDokumen == "JD107-D1001" && $sign->kodeDokumenSign == "JD107-D1001-0001") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_setuju;
								} elseif ($sign->kodeDokumen == "JD107-D1001" && $sign->kodeDokumenSign == "JD107-D1001-0002") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_mbuku;
								} elseif ($sign->kodeDokumen == "JD107-D1001" && $sign->kodeDokumenSign == "JD107-D1001-0003") {
									$sign->dataUserSign = $arr_temp_data_user_sign_vc_kasir;
								}
								$sign->action = "sign";
								array_push($newDocSigns, $sign);

								$data_sign = array(
									"chId" 			=> "SMILE",
									"reqId" 		=> $reqid_arsip,
									"idArsip" 		=> $idArsip,
									"docSigns"		=> $newDocSigns
								);
								
								// $result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
								$result_sign = api_json_call($wsIpDocument . "/JSDS/SignDocument", $headers, $data_sign);
								sleep(1);
							}
						}
					}
				}
			}
		}
		
		/*
		// 19112020, sesuai kebutuhan dan kesepakatan pada meeting bersama 13112020 , proses arsip dipisah dengan proses pembayaran di keuangan untuk mempercepat proses.
		// bundle & stamp documents
		$sql = "
		SELECT  A.KODE_KLAIM,
						A.KODE_KANTOR,
						A.NO_PENETAPAN,
						A.NAMA_TK,
						A.KPJ,
						A.NOMOR_IDENTITAS,
						C.BANK_PENERIMA,
						C.NO_REKENING_PENERIMA,
						C.NAMA_REKENING_PENERIMA,
						(SELECT AA.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NAMA_PERUSAHAAN,
						(SELECT AA.NPP FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NPP,
						(SELECT SUM(NOM_PEMBAYARAN) FROM PN.PN_KLAIM_PEMBAYARAN AA WHERE AA.KODE_KLAIM = A.KODE_KLAIM) NOM_PEMBAYARAN,
						(SELECT COUNT(1) FROM PN.PN_ARSIP_DOKUMEN AA WHERE AA.ID_DOKUMEN = A.KODE_KLAIM) JML_DOKUMEN       
		FROM    
						SMILE.PN_KLAIM                  A,
						SMILE.MS_KANTOR                 B,
						SMILE.PN_KLAIM_PENERIMA_MANFAAT C
		WHERE   A.KODE_KANTOR = B.KODE_KANTOR
						AND A.KODE_KLAIM = C.KODE_KLAIM
						AND C.KODE_TIPE_PENERIMA = C.KODE_TIPE_PENERIMA
						AND A.KODE_KLAIM = :P_KODE_KLAIM";

		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ":P_KODE_KLAIM", $id_dokumen_arsip, 100);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_attr_kode_kantor      			= $row["KODE_KANTOR"];
		$ls_attr_kode_klaim      				= $row["KODE_KLAIM"];
		$ls_attr_no_penetapan    				= $row["NO_PENETAPAN"];
		$ls_attr_nomor_identitas 				= $row["NOMOR_IDENTITAS"];
		$ls_attr_kpj             				= $row["KPJ"];
		$ls_attr_nama_tk         				= $row["NAMA_TK"];
		$ls_attr_bank 				   				= $row["BANK_PENERIMA"];
		$ls_attr_no_rekening 		 				= $row["NO_REKENING_PENERIMA"];
		$ls_attr_nama_pemilik_rekening 	= $row["NAMA_REKENING_PENERIMA"];
		$ls_attr_pph21 					 				= "";
		$ls_attr_npp             				= $row["NPP"];
		$ls_attr_nama_perusahaan 				= $row["NAMA_PERUSAHAAN"];
		$ls_attr_nom_pembayaran  				= $row["NOM_PEMBAYARAN"];
		$ls_attr_jml_berkas 						= $row["JML_DOKUMEN"];
		$ls_attr_keterangan 					  = "";
		$ls_attr_kode_klasifikasi 			= "KU 03.01";
		$ls_attr_kode_transaksi_voucher = "";

		$sql_gl_voucher = "
		select  decode (
						a.status_posting, 'Y', 
						(to_char (a.tgl_trans, 'DD-MM-YYYY') || ' ' || a.kode_buku || ' ' || lpad (a.nomor_trans, 8, 0)),
						(to_char (a.tgl_trans, 'DD-MM-YYYY') || ' ' || a.id_dokumen)
						) kd_trans
		from    lk.gl_voucher a
		where   a.id_dokumen_induk = :p_kode_klaim
						and a.kode_buku = 'T0018'";
		$proc = $DB->parse($sql_gl_voucher);
		oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_attr_kode_transaksi_voucher = $row["KD_TRANS"];

		$data_stamp = array(
			"chId" => "SMILE",
			"reqId" => $reqid_arsip,
			"idDokumen" => $id_dokumen_arsip,
			"kodePointerArsipSystem" => "PA0001",
			"kodeJenisArsipSystem" => "A00000001",
			"attributes" => array(
				array("nama" => "KODE_KANTOR", "value" => "$ls_attr_kode_kantor"),
				array("nama" => "KODE_KLAIM", "value" => "$ls_attr_kode_klaim"),
				array("nama" => "NO_PENETAPAN", "value" => "$ls_attr_no_penetapan"),
				array("nama" => "NIK", "value" => "$ls_attr_nomor_identitas"),
				array("nama" => "KPJ", "value" => "$ls_attr_kpj"),
				array("nama" => "NAMA_TK", "value" => "$ls_attr_nama_tk"),
				array("nama" => "BANK", "value" => "$ls_attr_bank"),
				array("nama" => "NO_REKENING", "value" => "$ls_attr_no_rekening"),
				array("nama" => "NAMA_PEMILIK_REKENING", "value" => "$ls_attr_nama_pemilik_rekening"),
				array("nama" => "PPH21", "value" => "$ls_attr_pph21"),
				array("nama" => "NPP", "value" => "$ls_attr_npp"),
				array("nama" => "NAMA_PERUSAHAAN", "value" => "$ls_attr_nama_perusahaan"),
				array("nama" => "JML_BAYAR", "value" => "$ls_attr_nom_pembayaran"),
				array("nama" => "JML_BERKAS", "value" => "$ls_attr_jml_berkas"),
				array("nama" => "KETERANGAN", "value" => "$ls_attr_keterangan"),
				array("nama" => "KODE_KLASIFIKASI", "value" => "$ls_attr_kode_klasifikasi"),
				array("nama" => "KODE_TRANSAKSI_VOUCHER", "value" => "$ls_attr_kode_transaksi_voucher"),
				array("nama" => "TINGKAT_PERKEMBANGAN", "value" => "ASLI")
			)
		);
		
		$result_stamp = api_json_call($wsIp . "/JSDS/StampDocument", $headers, $data_stamp);

		$result_stamp_ret = $result_stamp->ret;
		$result_stamp_msg = $result_stamp->msg;
		if ($result_stamp_ret != "0") {
			$sql = "
			select  distinct 
							(select nama_dokumen from pn.pn_arsip_kode_dokumen aa where aa.kode_dokumen = a.kode_dokumen) nama_dokumen,
							a.keterangan
			from    pn.pn_arsip_dokumen_sign a 
			where   a.id_user_sign is null
							and id_arsip in (
							select  id_arsip
							from    pn.pn_arsip_dokumen aa
			        where   aa.id_dokumen = :p_id_dokumen_arsip
							)";
			$proc = $DB->parse($sql);
			oci_bind_by_name($proc, ":p_id_dokumen_arsip", $id_dokumen_arsip, 100);
			$DB->execute();
			$msg_arsip_sign = "";
			while($row = $DB->nextrow()) {
				$msg_arsip_sign .= "<br/>LENGKAPI SETUP " . $row["KETERANGAN"] . " UNTUK DOKUMEN " . $row["NAMA_DOKUMEN"];
			}
			$ln_error_arsip = 1;
			$msg_arsip = $result_stamp_msg . $msg_arsip_sign;
		}
		*/
	}elseif($action_arsip == "PROSES_ARSIP")
	{
		// 19112020, sesuai kebutuhan dan kesepakatan pada meeting bersama 13112020 , proses arsip dipisah dengan proses pembayaran di keuangan untuk mempercepat proses.
		// bundle & stamp documents
		if($ls_flag_berkala == 'Y'){
			$sql = "
							SELECT  A.KODE_KLAIM,
							A.KODE_KANTOR,
							A.NO_PENETAPAN,
							A.NAMA_TK,
							A.KPJ,
							A.NOMOR_IDENTITAS,
							A.KODE_TIPE_KLAIM,
							C.BANK_PENERIMA,
							C.NO_REKENING_PENERIMA,
							C.NAMA_REKENING_PENERIMA,
							(SELECT AA.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NAMA_PERUSAHAAN,
							(SELECT AA.NPP FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NPP,
							(SELECT SUM(NOM_PEMBAYARAN) FROM PN.PN_KLAIM_PEMBAYARAN_BERKALA AA WHERE AA.KODE_KLAIM = A.KODE_KLAIM) NOM_PEMBAYARAN,
							(SELECT COUNT(1) FROM PN.PN_ARSIP_DOKUMEN AA WHERE AA.ID_DOKUMEN = A.KODE_KLAIM) JML_DOKUMEN       
							FROM    
							SMILE.PN_KLAIM                  A,
							SMILE.MS_KANTOR                 B,
							SMILE.PN_KLAIM_PENERIMA_BERKALA C
							WHERE   A.KODE_KANTOR = B.KODE_KANTOR
							AND A.KODE_KLAIM = C.KODE_KLAIM
							AND EXISTS
							(
								SELECT * FROM PN.PN_KLAIM_BERKALA D
								WHERE D.KODE_KLAIM = C.KODE_KLAIM
								AND D.KODE_PENERIMA_BERKALA = C.KODE_PENERIMA_BERKALA
							)
							AND A.KODE_KLAIM = :P_KODE_KLAIM";
		}else{
			$sql = "
							SELECT  A.KODE_KLAIM,
											A.KODE_KANTOR,
											A.NO_PENETAPAN,
											A.NAMA_TK,
											A.KPJ,
											A.NOMOR_IDENTITAS,
											A.KODE_TIPE_KLAIM,
											C.BANK_PENERIMA,
											C.NO_REKENING_PENERIMA,
											C.NAMA_REKENING_PENERIMA,
											(SELECT AA.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NAMA_PERUSAHAAN,
											(SELECT AA.NPP FROM KN.KN_PERUSAHAAN AA WHERE AA.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN AND ROWNUM = 1) NPP,
											(SELECT SUM(NOM_PEMBAYARAN) FROM PN.PN_KLAIM_PEMBAYARAN AA WHERE AA.KODE_KLAIM = A.KODE_KLAIM) NOM_PEMBAYARAN,
											(SELECT COUNT(1) FROM PN.PN_ARSIP_DOKUMEN AA WHERE AA.ID_DOKUMEN = A.KODE_KLAIM) JML_DOKUMEN       
							FROM    
											SMILE.PN_KLAIM                  A,
											SMILE.MS_KANTOR                 B,
											SMILE.PN_KLAIM_PENERIMA_MANFAAT C
							WHERE   A.KODE_KANTOR = B.KODE_KANTOR
											AND A.KODE_KLAIM = C.KODE_KLAIM
											AND C.KODE_TIPE_PENERIMA = C.KODE_TIPE_PENERIMA
											AND A.KODE_KLAIM = :P_KODE_KLAIM";
		}
		
		//  var_dump($sql,$ls_flag_berkala);die();
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ":P_KODE_KLAIM", $id_dokumen_arsip, 100);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_attr_kode_kantor      			= $row["KODE_KANTOR"];
		$ls_attr_kode_klaim      				= $row["KODE_KLAIM"];
		$ls_attr_no_penetapan    				= $row["NO_PENETAPAN"];
		$ls_attr_nomor_identitas 				= $row["NOMOR_IDENTITAS"];
		$ls_attr_kpj             				= $row["KPJ"];
		$ls_attr_nama_tk         				= $row["NAMA_TK"];
		$ls_attr_bank 				   				= $row["BANK_PENERIMA"];
		$ls_attr_no_rekening 		 				= $row["NO_REKENING_PENERIMA"];
		$ls_attr_nama_pemilik_rekening 	= $row["NAMA_REKENING_PENERIMA"];
		$ls_attr_pph21 					 				= "";
		$ls_attr_npp             				= $row["NPP"];
		$ls_attr_nama_perusahaan 				= $row["NAMA_PERUSAHAAN"];
		$ls_attr_nom_pembayaran  				= $row["NOM_PEMBAYARAN"];
		$ls_attr_jml_berkas 						= $row["JML_DOKUMEN"];
		$ls_attr_keterangan 					  = "";
		$ls_attr_kode_klasifikasi 			= "KU 03.01";
		$ls_attr_kode_transaksi_voucher = "";
		$ls_attr_kode_tipe_klaim      			= $row["KODE_TIPE_KLAIM"];

		$sql_gl_voucher = "
		select  decode (
						a.status_posting, 'Y', 
						(to_char (a.tgl_trans, 'DD-MM-YYYY') || ' ' || a.kode_buku || ' ' || lpad (a.nomor_trans, 8, 0)),
						(to_char (a.tgl_trans, 'DD-MM-YYYY') || ' ' || a.id_dokumen)
						) kd_trans
		from    lk.gl_voucher a
		where   a.id_dokumen_induk = :p_kode_klaim
						and a.id_dokumen = '$ls_no_pointer'"; //T0018
		$proc = $DB->parse($sql_gl_voucher);
		oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_attr_kode_transaksi_voucher = $row["KD_TRANS"];
		
		if ($ls_attr_kode_tipe_klaim == "JHT01")
		{
			$ls_kodeJenisArsipSystem = "A00000001";
		}
		else if ($ls_attr_kode_tipe_klaim == "JKM01")
		{
			$ls_kodeJenisArsipSystem = "A00000003";
		}
		else if ($ls_attr_kode_tipe_klaim == "JPN01")
		{
			$ls_kodeJenisArsipSystem = "A00000002";
		}
		else if ($ls_attr_kode_tipe_klaim == "JKP01")
		{
			$ls_kodeJenisArsipSystem = "A00000004";
		}

		$data_stamp = array(
			"chId" => "SMILE",
			"reqId" => $reqid_arsip,
			"idDokumen" => $id_dokumen_arsip,
			"kodePointerArsipSystem" => "PA0001",
			"kodeJenisArsipSystem" => $ls_kodeJenisArsipSystem,
			"attributes" => array(
				array("nama" => "KODE_KANTOR", "value" => "$ls_attr_kode_kantor"),
				array("nama" => "KODE_KLAIM", "value" => "$ls_attr_kode_klaim"),
				array("nama" => "NO_PENETAPAN", "value" => "$ls_attr_no_penetapan"),
				array("nama" => "NIK", "value" => "$ls_attr_nomor_identitas"),
				array("nama" => "KPJ", "value" => "$ls_attr_kpj"),
				array("nama" => "NAMA_TK", "value" => "$ls_attr_nama_tk"),
				array("nama" => "BANK", "value" => "$ls_attr_bank"),
				array("nama" => "NO_REKENING", "value" => "$ls_attr_no_rekening"),
				array("nama" => "NAMA_PEMILIK_REKENING", "value" => "$ls_attr_nama_pemilik_rekening"),
				array("nama" => "PPH21", "value" => "$ls_attr_pph21"),
				array("nama" => "NPP", "value" => "$ls_attr_npp"),
				array("nama" => "NAMA_PERUSAHAAN", "value" => "$ls_attr_nama_perusahaan"),
				array("nama" => "JML_BAYAR", "value" => "$ls_attr_nom_pembayaran"),
				array("nama" => "JML_BERKAS", "value" => "$ls_attr_jml_berkas"),
				array("nama" => "KETERANGAN", "value" => "$ls_attr_keterangan"),
				array("nama" => "KODE_KLASIFIKASI", "value" => "$ls_attr_kode_klasifikasi"),
				array("nama" => "KODE_TRANSAKSI_VOUCHER", "value" => "$ls_attr_kode_transaksi_voucher"),
				array("nama" => "TINGKAT_PERKEMBANGAN", "value" => "ASLI")
			)
		);
		// var_dump(json_encode($data_stamp));die();
		// $result_stamp = api_json_call($wsIp . "/JSDS/StampDocument", $headers, $data_stamp);
		$result_stamp = api_json_call($wsIpDocument . "/JSDS/StampDocument", $headers, $data_stamp);
		//var_dump($result_stamp->ret);die();
		$result_stamp_ret = $result_stamp->ret;
		$result_stamp_msg = $result_stamp->msg;
		if ($result_stamp_ret != "0") {
			$sql = "
			select  distinct 
							(select nama_dokumen from pn.pn_arsip_kode_dokumen aa where aa.kode_dokumen = a.kode_dokumen) nama_dokumen,
							a.keterangan
			from    pn.pn_arsip_dokumen_sign a 
			where   a.id_user_sign is null
							and id_arsip in (
							select  id_arsip
							from    pn.pn_arsip_dokumen aa
			        where   aa.id_dokumen = :p_id_dokumen_arsip
							)";
							// var_dump($sql);die();
			$proc = $DB->parse($sql);
			oci_bind_by_name($proc, ":p_id_dokumen_arsip", $id_dokumen_arsip, 100);
			$DB->execute();
			$msg_arsip_sign = "";
			while($row = $DB->nextrow()) {
				$msg_arsip_sign .= "<br/>LENGKAPI SETUP " . $row["KETERANGAN"] . " UNTUK DOKUMEN " . $row["NAMA_DOKUMEN"];
			}
			$ln_error_arsip = 1;
			$msg_arsip = $result_stamp_msg . $msg_arsip_sign;
			// var_dump($result_stamp_msg,$result_stamp_ret);die();
		}
	}

	function api_json_call($apiurl, $header, $data) {
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
	
	function dashesToCamelCase($string) 
    {
      $str = str_replace(' ', '', ucwords(str_replace('_', ' ', strtolower($string))));
      //echo $str;
      if (true) {
        $str[0] = strtolower($str[0]);
      }
      return $str;
    }
	
	// * END: TAMBAHAN UNTUK PROSES PENGARSIPAN
?>