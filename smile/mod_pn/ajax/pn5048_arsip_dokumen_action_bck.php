<?php
	// * JULI 2020: ARSIP DIGITAL
	// * START: TAMBAHAN UNTUK PROSES PENGARSIPAN
	if ($action_arsip == "PROSES_ARSIP") {
		$headers = array(
			'Content-Type: application/json',
			'X-Forwarded-For: ' . $ipfwd
		);

		// cleanup previous dokumen
		$sql = "
		select  id_arsip
		from    pn.pn_arsip_dokumen 
		where   id_dokumen = :p_kode_klaim
						and kode_jenis_dokumen = 'JD101'
						and kode_dokumen in ('JD101-D1007', 'JD101-D1008', 'JD101-D1009', 'JD101-D1010')";

		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
		$DB->execute();
		while($row = $DB->nextrow()) {
			$data_remove_document = array(
				"chId"             => "SMILE",
				"reqId"            => $reqid_arsip,
				"idArsip"        	 => $row["ID_ARSIP"]
			);
			$result_remove_document = api_json_call($wsIp . "/JSDS/RemoveDocumentByIdArsip", $headers, $data_remove_document);
		}

		$sql = "
		select 	to_char(sysdate, 'yyyymm') blth,
						(select kode_kantor from pn.pn_klaim where kode_klaim = :p_kode_klaim) kode_kantor
		from 		dual";
		
		$proc = $DB->parse($sql);
		oci_bind_by_name($proc, ":p_kode_klaim", $id_dokumen_arsip, 100);
		$DB->execute();
		$row = $DB->nextrow();
		$ls_blth = $row["BLTH"];
		$ls_kode_kantor = $row["KODE_KANTOR"];
		
		$ls_nama_bucket_storage = "arsip";
		$ls_nama_folder_storage = "$ls_kode_kantor/$ls_blth/klaim";
		
		// 1. dokumen kwitansi
		$ls_url_file_kwitansi      = $arr_url_file["kwitansi"]; //'http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502901.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
		$ls_jenis_dokumen_kwitansi = "JD101";
		$ls_kode_dokumen_kwitansi  = "JD101-D1007";
		// 2. dokumen surat perintah bayar
		$ls_url_file_spb           = $arr_url_file["spb"]; //'http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DPNR502902.rdf%26userid%3D%2Fdata%2Freports%2FPN%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27';
		$ls_jenis_dokumen_spb      = "JD101";
		$ls_kode_dokumen_spb       = "JD101-D1008";
		// 3. dokumen voucher
		$ls_url_file_voucher       = $arr_url_file["voucher"]; //'http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DGLR800001.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qiddokumen_induk%3D%27KL19071806046633%27%26qpointer%3D%27PN01%27%26qiddokumen%3D%27JMSJ0P2019PN0100074563%27%26quser_cetak%3D%27JO163660%27';
		$ls_jenis_dokumen_voucher  = "JD101";
		$ls_kode_dokumen_voucher   = "JD101-D1009";
		// 4. dokumen bukti potong pph21
		$ls_url_file_pph21         = $arr_url_file["pph21"]; //'http://reptest.bpjsketenagakerjaan.go.id/reports/rwservlet/setauth?button=Submit&username=sijstk&password=welcome1&authtype=D&mask=GQ%253D%253D&isjsp=no&database=dbdevelop&nextpage=destype%3Dcache%26desformat%3DPDF%26report%3DTAXR301407.rdf%26userid%3D%2Fdata%2Freports%2FLK%26%26qkode_pembayaran%3D%27BYR1907224058629%27%26qkode_klaim%3D%27KL19071806046633%27%26qtipe_penerima%3D%27TENAGAXXXKERJA%27%26qtgl%3D%27%27%26qblth_proses%3D%27%27%26qkodepointer_asal%3D%27JM09%27%26qidpointer_asal%3D%27BYR1907224058629%27';
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
		$result_store_multidoc = api_json_call($wsIp . "/JSDS/StoreMultiDocument", $headers, $data_store_multidoc);

		// sign all document
		if ($result_store_multidoc->ret == "0") {
			$arr_doc_success = $result_store_multidoc->docSuccess;
			foreach ($arr_doc_success as $doc) {
				$data_presign = array(
					"chId" => "SMILE",
					"reqId" => $reqid_arsip,
					"idArsip" => $doc->idArsip
				);
				$result_presign = api_json_call($wsIp . "/JSDS/GetPreSignDocumentInfo", $headers, $data_presign);
				if ($result_presign->ret == "0") {
					// sign document
					$idArsip = $result_presign->data->idArsip;
					$docSigns = $result_presign->data->docSigns;
					if (ExtendedFunction::count($docSigns) > 0) {
						$newDocSigns = array();
						foreach ($docSigns as $sign) {
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
						}

						$data_sign = array(
							"chId" 			=> "SMILE",
							"reqId" 		=> $reqid_arsip,
							"idArsip" 	=> $doc->idArsip,
							"docSigns"	=> $newDocSigns
						);
						
						$result_sign = api_json_call($wsIp . "/JSDS/SignDocument", $headers, $data_sign);
					}
				}
			}
		}

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
	
	// * END: TAMBAHAN UNTUK PROSES PENGARSIPAN
?>