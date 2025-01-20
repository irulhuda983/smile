<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";
require_once '../../includes/fungsi_newrpt.php';

//set parameter ----------------------------------------------------------------
$pagetype 					= "form";
$gs_kodeform 				= "PN5006";
$gs_kantor_aktif		= $_SESSION['kdkantorrole'];
$gs_kode_user				= $_SESSION["USER"];
$gs_kode_role				= $_SESSION['regrole'];
$chId 	 	 			 		= "SMILE";
$gs_pagetitle 			= "KONFIRMASI JP BERKALA";
$task 							= $_POST["task"];
$task_detil					= $_POST["task_detil"];
$editid 						= $_POST['editid'];
$ls_kode_klaim 			= $_POST['kode_klaim'];
$ln_no_konfirmasi		= $_POST['no_konfirmasi'];
$ln_no_konfirmasi_induk	= $_POST['no_konfirmasi_induk'];
$ld_tglawaldisplay 	= !isset($_POST['tglawaldisplay']) ? $_GET['tglawaldisplay'] : $_POST['tglawaldisplay'];
$ld_tglakhirdisplay = !isset($_POST['tglakhirdisplay']) ? $_GET['tglakhirdisplay'] : $_POST['tglakhirdisplay'];

if ($task == "new") {
	$gs_pagetitle = $gs_kodeform . " - ENTRY " . $gs_pagetitle;
} else if ($task == "edit") {
	$gs_pagetitle = $gs_kodeform . " - EDIT " . $gs_pagetitle;
} else if ($task == "view") {
	$gs_pagetitle = $gs_kodeform . " - VIEW " . $gs_pagetitle;
} else {
	$gs_pagetitle = $gs_kodeform . " - " . $gs_pagetitle;
}

if ($task_detil == "doCetakKonfirmasi") {
	$ls_user_param .= " P_KODE_KLAIM='$ls_kode_klaim'";
	$ls_user_param .= " P_NO_KONFIRMASI='$ln_no_konfirmasi'";
	$ls_user_param .= " P_USER='$gs_kode_user'";
	$ls_nama_rpt .= "PNR900560.rdf";

	$ls_pdf = $ls_nama_rpt;

	$tipe     = "PDF";
	$ls_modul = "pn";
	exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);

	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
	echo "reloadFormUtama();";
	echo "</script>";
}
//end set parameter ------------------------------------------------------------

//Get Antrian -------------------------------------------------------------

if ($ls_kode_klaim != "") {
	//query data -------------------------------------------------------------
	$sql = "
    select  a.kode_antrian,
            a.kode_jenis_antrian,
            (select nama_jenis_antrian from pn.pn_antrian_kode_jenis where kode_jenis_antrian = a.kode_jenis_antrian) nama_jenis_antrian,
            b.kode_status_antrian,
            (select nama_status_antrian from pn.pn_antrian_kode_status where kode_status_antrian = b.kode_status_antrian) nama_status_antrian,
            a.token_sisla,
            a.kode_sisla,
            a.kode_kantor,
            (select nama_kantor from ms.ms_kantor where kode_kantor = a.kode_kantor) nama_kantor,
            a.nomor_antrian,
            to_char(a.tgl_ambil_antrian, 'YYYY-MM-DD HH24:MI:SS') tgl_ambil_antrian,
            to_char(a.tgl_panggil_antrian, 'YYYY-MM-DD HH24:MI:SS') tgl_panggil_antrian,
            a.petugas_panggil,
            (select nama_user from ms.sc_user where kode_user = a.petugas_panggil) nama_petugas_panggil,
            b.kode_klaim_agenda,
            a.nomor_identitas,
            a.no_hp,
            a.email,
            a.keterangan,
            (select path_url from pn.pn_antrian_dokumen where kode_antrian = a.kode_antrian and rownum=1) path_url_foto														
    from    pn.pn_antrian a,
            pn.pn_antrian_program b
    where   a.kode_antrian = b.kode_antrian
    and     b.kode_klaim_agenda = '" . $ls_kode_klaim . "' ";

	//echo $sql;
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_kode_antrian = $row['KODE_ANTRIAN'];
	$ls_kode_jenis_antrian = $row['KODE_JENIS_ANTRIAN'];
	$ls_nama_jenis_antrian = $row['NAMA_JENIS_ANTRIAN'];
	$ls_kode_status_antrian = $row['KODE_STATUS_ANTRIAN'];
	$ls_nama_status_antrian = $row['NAMA_STATUS_ANTRIAN'];
	$ls_token_antrian = $row['TOKEN_SISLA'];
	$ls_kode_sisla = $row['KODE_SISLA'];
	$ls_kode_kantor_antrian = $row['KODE_KANTOR'];
	$ls_nama_kantor_antrian = $row['NAMA_KANTOR'];
	$ls_no_antrian = $row['NOMOR_ANTRIAN'];
	$ls_tgl_ambil_antrian = $row['TGL_AMBIL_ANTRIAN'];
	$ls_tgl_panggil_antrian = $row['TGL_PANGGIL_ANTRIAN'];
	$ls_kode_petugas_antrian = $row['PETUGAS_PANGGIL'];
	$ls_nama_petugas_antrian = $row['NAMA_PETUGAS_PANGGIL'];
	$ls_nomor_identitas_antrian = $row['NOMOR_IDENTITAS'];
	$ls_no_hp_antrian = $row['NO_HP'];
	$ls_email_antrian = $row['EMAIL'];
	$ls_path_url_foto = $row['PATH_URL_FOTO'];

	$sqlKontakDarurat = " select NAMA_KONTAK_DARURAT, NO_HP_KONTAK_DARURAT, ALAMAT_KONTAK_DARURAT, KODE_HUBUNGAN_KONTAK_DARURAT FROM PN.PN_KLAIM_PENERIMA_BERKALA WHERE NOMOR_IDENTITAS = '2104035002740001' AND KODE_KLAIM = 'KL23102401637047'";

	if ($ls_path_url_foto != "") {
		$ls_path_url_foto = $wsIpStorage . $ls_path_url_foto;
	}
}
//End Of Get Antrian -------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript">
	//let newWindow;
	//const response = document.getElementById('response');
	let imageBase64;
	let blob;
	window.addEventListener('message', (event) => {
		if (event.data?.msg) {
			imageBase64 = event.data.msg;
			$("#base64_foto_peserta").val(imageBase64);
			console.log(imageBase64);
			document.getElementById('image1').src = imageBase64;

			var block = imageBase64.split(";");
			// Get the content type of the image
			var contentType = block[0].split(":")[1]; // In this case "image/gif"
			// get the real base64 content of the file
			var realData = block[1].split(",")[1];

			blob = b64toBlob(realData, contentType);
			console.log(blob);

			// let fileInputElement = document.getElementById('file_input');
			// // Here load or generate data
			// let data = blob;
			// let file = new File([data], "foto.jpeg",{type:"image/jpeg", lastModified:new Date().getTime()});
			// let container = new DataTransfer();
			// container.items.add(file);
			// fileInputElement.files = container.files;
			// console.log(fileInputElement.files);

			//let blob_file = blob;
			// let blob_file_size = 0;
			// if (path_file !=""){
			//     //file_size = $('#fileToUpload')[0].files[0].size / 1024 / 1024; // size file (MB)
			//     blob_file_size = blob.size / 1024 / 1024; // size file (MB)
			// }

		}
	})
	const openNewWindow = (event) => {
		event.preventDefault()
		const width = 680;
		const height = 560;
		const left = (screen.width - width) / 2;
		const top = (screen.height - height) / 2;
		const params = `scrollbars=no,resizable=no,status=no,location=no,toolbar=no,menubar=no,left=${left},top=${top},width=${width},height=${height}`;
		//newWindow = window.open('https://sidia-testing.bpjsketenagakerjaan.go.id/camera.html', 'popup', params);
		newWindow = window.open('<?= $appsCam; ?>' + '/camera.html', 'popup', params);
	}

	function b64toBlob(b64Data, contentType, sliceSize) {
		contentType = contentType || '';
		sliceSize = sliceSize || 512;

		var byteCharacters = atob(b64Data);
		var byteArrays = [];

		for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
			var slice = byteCharacters.slice(offset, offset + sliceSize);

			var byteNumbers = new Array(slice.length);
			for (var i = 0; i < slice.length; i++) {
				byteNumbers[i] = slice.charCodeAt(i);
			}

			var byteArray = new Uint8Array(byteNumbers);

			byteArrays.push(byteArray);
		}

		var blob = new Blob(byteArrays, {
			type: contentType
		});
		return blob;
	}

	function f_js_get_info_token() {
		var token_antrian = $("#token_antrian").val();
		$("#kode_sisla").val("");
		$("#kode_kantor_antrian").val("");
		$("#nama_kantor_antrian").val("");
		$("#no_antrian").val("");
		$("#tgl_ambil_antrian").val("");
		$("#tgl_panggil_antrian").val("");
		$("#kode_petugas_antrian").val("");
		$("#nama_petugas_antrian").val("");
		$("#kode_jenis_antrian").val("");
		$("#nama_jenis_antrian").val("");
		window.document.getElementById("dispInfoToken").innerHTML = "";
		window.document.getElementById("dispInfoToken").style.display = 'none';
		if (token_antrian != "") {
			//preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_il/ajax/il1001_entry_action.php?' + Math.random(),
				data: {
					"TYPE": "getDecryptToken",
					"token_antrian": token_antrian
				},
				success: function(data) {
					console.log(data);
					jdata = JSON.parse(data);
					if (jdata.ret == '0') {
						var kode_jenis_antrian = "SA01";
						$.ajax({
							type: 'POST',
							url: 'http://<?= $HTTP_HOST; ?>/mod_il/ajax/il1001_entry_action.php?' + Math.random(),
							data: {
								"TYPE": "validasiInfoToken",
								"kode_sisla": jdata.kode_sisla,
								"kode_kantor": jdata.kode_kantor,
								"no_antrian": jdata.no_antrian,
								"tgl_ambil_antrian": jdata.tgl_ambil_antrian,
								"tgl_panggil_antrian": jdata.tgl_panggil_antrian,
								"kode_petugas_antrian": jdata.kode_petugas_antrian,
								"kode_jenis_antrian": kode_jenis_antrian,
								"kode_jenis_antrian_detil": "SA01KJP01"
							},
							success: function(dataInfo) {
								console.log(dataInfo);
								jdataInfo = JSON.parse(dataInfo);
								if (jdataInfo.ret == '0') {
									var nama_jenis_antrian = "Klaim";
									$("#kode_sisla").val(jdata.kode_sisla);
									$("#kode_kantor_antrian").val(jdata.kode_kantor);
									$("#nama_kantor_antrian").val(jdataInfo.nama_kantor);
									$("#no_antrian").val(jdata.no_antrian);
									$("#tgl_ambil_antrian").val(jdata.tgl_ambil_antrian);
									$("#tgl_panggil_antrian").val(jdata.tgl_panggil_antrian);
									$("#kode_petugas_antrian").val(jdata.kode_petugas_antrian);
									$("#nama_petugas_antrian").val(jdataInfo.nama_petugas_antrian);
									$("#kode_jenis_antrian").val(kode_jenis_antrian);
									$("#nama_jenis_antrian").val(nama_jenis_antrian);
									$("#no_hp_antrian").val(jdataInfo.nomor_hp);
									$("#email_antrian").val(jdataInfo.email);
									$("#path_url_antrian_curr").val(jdataInfo.path_url);
									$("#type_file_antrian").val(jdataInfo.type_file);
									if (jdataInfo.path_url != "") {
										document.getElementById('btn_camera').disabled = true;
										document.getElementById('image1').src = '<?= $wsIpStorage; ?>' + jdataInfo.path_url;
									} else {
										document.getElementById('btn_camera').disabled = false;
										document.getElementById('image1').src = "../../images/empty-profile.png";
									}
									if (jdataInfo.kode_antrian != "") {
										$("#no_hp_antrian").attr('readonly', 'readonly');
										$("#email_antrian").attr('readonly', 'readonly');
									} else {
										$("#no_hp_antrian").removeAttr('readonly');
										$("#email_antrian").removeAttr('readonly');
									}
								} else {
									alert(jdataInfo.msg);
									$("#token_antrian").val("");
									$("#kode_sisla").val("");
									$("#kode_kantor_antrian").val("");
									$("#nama_kantor_antrian").val("");
									$("#no_antrian").val("");
									$("#tgl_ambil_antrian").val("");
									$("#tgl_panggil_antrian").val("");
									$("#kode_petugas_antrian").val("");
									$("#nama_petugas_antrian").val("");
									$("#kode_jenis_antrian").val("");
									$("#nama_jenis_antrian").val("");
									$("#no_hp_antrian").val("");
									$("#email_antrian").val("");
									$("#path_url_antrian_curr").val("");
									$("#type_file_antrian").val("");
									window.document.getElementById("dispInfoToken").innerHTML = jdataInfo.msg;
									window.document.getElementById("dispInfoToken").style.display = 'block';
									document.getElementById('btn_camera').disabled = true;
								}
							},
							error: function() {
								alert("Error");
								//preload(false);
							},
							complete: function() {
								//preload(false);
							}
						});
					} else {
						alert(jdata.msg);
						$("#token_antrian").val("");
						window.document.getElementById("dispInfoToken").innerHTML = jdata.msg;
						window.document.getElementById("dispInfoToken").style.display = 'block';
						document.getElementById('btn_camera').disabled = true;
					}
				},
				error: function() {
					alert("Error");
					//preload(false);
				},
				complete: function() {
					//preload(false);
				}
			});

		}
	}

	function uploadCephFile(file) {
		var resultPath = "";
		let formData = new FormData();
		formData.append('TYPE', 'uploadDokumen');
		formData.append('file', file);
		formData.append('kode', kode_antrian);
		$.ajax({
			url: 'http://<?= $HTTP_HOST; ?>/mod_il/ajax/il1001_entry_action.php?' + Math.random(),
			type: 'POST',
			data: formData,
			async: false,
			cache: false,
			contentType: false,
			enctype: 'multipart/form-data',
			processData: false,
			success: function(data) {
				console.log(data);
				var jdata = JSON.parse(data);
				if (jdata.ret == '0') {
					resultPath = jdata.pathCephFile;
				} else {
					resultPath = "";
					//alert(jdata.msg);
				}
			},
			complete: function() {
				//preload(false);
			},
			error: function() {
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				//preload(false);
			}
		});
		return resultPath;
	}

	function f_js_upload_foto_wajah(kode_antrian) {
		let path_ceph_file = $("#path_url_antrian_curr").val();
		let type_file = $("#type_file_antrian").val();
		let base64_foto_peserta = $("#base64_foto_peserta").val();

		if (path_ceph_file != "") {
			base64_foto_peserta = path_ceph_file;
		}
		if (kode_antrian != "" && base64_foto_peserta != "") {
			if (path_ceph_file == "") {
				let blob_file = blob;
				type_file = blob_file.type;
				path_ceph_file = uploadCephFile(blob_file);
			}

			if (path_ceph_file != "") {
				$.ajax({
					type: 'POST',
					url: 'http://<?= $HTTP_HOST; ?>/mod_il/ajax/il1001_entry_action.php?' + Math.random(),
					data: {
						"TYPE": "updateDokAntrian",
						"kode_antrian": kode_antrian,
						"path_file": path_ceph_file,
						"file_type": type_file
					},
					success: function(data) {
						var jdata = JSON.parse(data);
						console.log(data);
						if (jdata.ret == '0') {

						} else {

						}
					},
					error: function() {

					},
					complete: function() {
						//preload(false);
					}
				});
			}
		}
	}
</script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">


<!-- custom css -->
<link href="../style.custom.css?a=<?= rand() ?>" rel="stylesheet" />

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?= $gs_pagetitle; ?></span></div>
		<div class="item" style="float: right; padding: 2px;">
			<span style="color:#ffffff"><span id="span_page_title_right"></span></span>
		</div>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<?php
	if ($task == "") {
	?>
		<div class="div-header-form">
			<div class="div-row">
				<div class="div-col">
					<div class="div-action">
						<div class="icon">
							<a href="javascript:void(0)" onclick="showTask('edit', null)">
								<img alt="img" src="../../../smile/images/app_form_edit.png" align="absmiddle" border="0"><span>Edit</span>
							</a>
						</div>
					</div>
				</div>
				<div class="div-col">
					<div class="div-action">
						<div class="icon">
							<a href="javascript:void(0)" onclick="showTask('new', null)">
								<img alt="img" src="../../../smile/images/app_form_add.png" align="absmiddle" border="0"><span>New</span>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php
	}
	?>

	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?= $task; ?>">
			<input type="hidden" id="editid" name="editid" value="<?= $editid; ?>">
			<input type="hidden" id="mid" name="mid" value="<?= $mid; ?>">
			<input type="hidden" id="order_by" name="order_by" value="">
			<input type="hidden" id="order_type" name="order_type" value="">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="task_detil" name="task_detil" value="<?= $task_detil; ?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?= $ls_kode_klaim; ?>">
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?= $ln_no_konfirmasi; ?>">

			<?php
			if ($task == "") {
				//datagrid -------------------------------------------------------------
			?>
				<div id="div_container" class="div-container">
					<div id="div_header" class="div-header">
						<div class="div-header-content">
						</div>
					</div>
					<div id="div_body" class="div-body">
						<div id="div_dummy_data" style="width: 100%;"></div>
						<div id="div_filter">
							<div class="div-row" style="padding-top: 2px;">
								<div class="div-col" style="padding: 2px;">
									Tanggal :&nbsp;
									<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?= $ld_tglawaldisplay; ?>" onblur="convert_date(tglawaldisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglawaldisplay', 'dd-mm-y');">
									&nbsp;s/d&nbsp;
									<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?= $ld_tglakhirdisplay; ?>" onblur="convert_date(tglakhirdisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglakhirdisplay', 'dd-mm-y');">
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<a class="a-icon-text" href="#" onclick="filter();" title="Klik Untuk Menampilkan Data">
										<img alt="img" src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
										<span>Tampilkan</span>
									</a>
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="search_by2_changed();">
										<option value="">Keyword Lain</option>
										<option value="">----------------</option>
									</select>
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="search_by_changed()">
										<option value="">-- Keyword --</option>
										<option value="KODE_KLAIM">Kode Klaim</option>
										<option value="KPJ">No. Ref</option>
										<option value="NAMA_PENGAMBIL_KLAIM">Nama</option>
									</select>
								</div>
							</div>
						</div>
						<div id="div_data" class="div-data">
							<div style="padding: 6px 0px 0px 0px;">
								<table aria-describedby="table" class="table-data">
									<thead>
										<tr class="hr-single-double">
											<th style="text-align: center; width: 20px;!important;">
												<input type="checkbox" name="toggle" value="" onclick="checkRecordAll(this);">
											</th>
											<th style="text-align: left;"><a href="#">No</a></th>
											<th style="text-align: left;">
												<a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="orderby(this)">Kode Klaim
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="TGL_KONFIRMASI" order_type="DESC" onclick="orderby(this)">Tanggal
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="KPJ" order_type="DESC" onclick="orderby(this)">No Referensi
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="NAMA_TK" order_type="DESC" onclick="orderby(this)">Nama Peserta
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="KET_PENERIMA_BERKALA" order_type="DESC" onclick="orderby(this)">Penerima Berkala
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KET_PERIODE" order_type="DESC" onclick="orderby(this)">u/ Periode
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KODE_KANTOR_KONFIRMASI" order_type="DESC" onclick="orderby(this)">Ktr Konf
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="orderby(this)">Ktr Byr
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
										</tr>
									</thead>
									<tbody id="data_list">
										<tr class="nohover-color">
											<td colspan="10" style="text-align: center;">-- Data tidak ditemukan --</td>
										</tr>
									</tbody>
								</table>
							</div>
						</div>
						<div id="div_page" class="div-page">
							<div class="div-row" style="padding-top: 8px;">
								<div class="div-col">
									<span style="vertical-align: middle;">Halaman</span>
									<a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="filter('-02')"></a>
									<< <a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="filter('-01')">Prev</a>
										<input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="filter(this.value);" />
										<a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="filter('01')">Next</a>
										<a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="filter('02')">>></a>
										<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
										<input type="hidden" id="pages">
								</div>
								<div class="div-col-right">
									<input type="hidden" id="hdn_total_records">
									<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
									&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
									<span style="vertical-align: middle;">Tampilkan</span>
									<select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="filter()">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>
									<span style="vertical-align: middle;">item per halaman</span>
								</div>
							</div>
						</div>
					</div>
					<div id="div_footer" class="div-footer">
						<div class="div-footer-content">
							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
							Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <span style="color:#ff0000"> Tampilkan</span> untuk memulai pencarian data
						</div>
					</div>
				</div>
			<?php
			} else if ($task == "new" || $task == "edit" || $task == "view") {
				//action task new, edit ------------------------------------------------
			?>
				<div id="div_container" class="div-container">
					<div id="div_body" class="div-body">
						<input type="hidden" id="activetab" name="activetab" value="<?= $ls_activetab; ?>">
						<div id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
						<input type="hidden" id="st_errval1" name="st_errval1">
						<input type="hidden" id="count_empty_required" name="count_empty_required" value="0">
						<input type="hidden" id="errmess_empty_required" name="errmess_empty_required" value="">
						<input type="hidden" id="tglawaldisplay" name="tglawaldisplay" value="<?= $ld_tglawaldisplay; ?>">
						<input type="hidden" id="tglakhirdisplay" name="tglakhirdisplay" value="<?= $ld_tglakhirdisplay; ?>">

						<?
						if ($task == "new") {
						?>
							<div class="div-row">
								<div class="div-col" style="width:100%; max-height: 100%;">
									<b>ATAS PENETAPAN NOMOR : &nbsp;</b>
									<input type="text" id="no_penetapan" name="no_penetapan" value="<?= $ls_no_penetapan; ?>" readonly <?= ($ln_no_konfirmasi != "") ? " style=\"width:200px;text-align:center;background-color:#F5F5F5\"" : " style=\"width:200px;text-align:center;background-color:#ffff99\""; ?>>
									<input type="hidden" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?= $ln_no_konfirmasi_induk; ?>">
									<input type="hidden" id="cnt_berkala_detil" name="cnt_berkala_detil" value="<?= $ln_cnt_berkala_detil; ?>">
									&nbsp;A/N&nbsp;
									<input type="text" id="ket_klaim_atasnama" name="ket_klaim_atasnama" value="<?= $ls_ket_klaim_atasnama; ?>" style="width:550px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
									&nbsp;
									<a href="#" onclick="NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_lov_nopenetapan.php?p=pn5065.php&a=formreg&b=kode_klaim&c=no_penetapan&d=ket_klaim_atasnama&e=no_konfirmasi_induk&g=<?= $gs_kantor_aktif; ?>','',980,580,1)">
										<img alt="img" src="../../images/help.png" alt="Cari Data Penetapan JP Berkala" border="0" align="absmiddle">
									</a>
									<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_ahliwaris.php?&kode_klaim='+formreg.kode_klaim.value+'','',980,610,'no')">
										&nbsp;&nbsp;<img alt="img" id="img_new_ahliwaris" src="../../images/ico_ahliwaris.jpg" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" style="height:30px;display:none" />
										<span id="span_ket_img_ahliwaris"></span>
									</a>
								</div>
							</div>


							<span id="span_new_konfirmasi" style="display:none;">
								<!-- Antrian -->
								<div id="formKiri" style="width:800px;">
									<fieldset>
										<legend><b><i>
													<span style="color:#009999">Entry Antrian Klaim</span>

												</i></b></legend>
										<div id="dispInfoToken" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">Token Antrian &nbsp;&nbsp;&nbsp;&nbsp;</label>
											<!-- <textarea type="text" id="token_antrian" name="token_antrian" rows="3" style="width:260px;" <?= ($_REQUEST["task"] == "view") ? " readonly class=\"disabled\"" : "onblur=\"f_js_get_info_token();\""; ?>><?= $ls_token_antrian; ?></textarea> -->
											<input type="password" id="token_antrian" name="token_antrian" style="width:260px;" value="<?= $ls_token_antrian; ?>" <?= ($task == "new") ? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\""; ?>>
										</div>
										<div class="form-row_kanan">
											<label style="text-align:right;">Kode Antrian &nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="kode_antrian" name="kode_antrian" value="<?= $ls_kode_antrian; ?>" style="width:150px;" readonly class="disabled">
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">No Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="no_antrian" name="no_antrian" value="<?= $ls_no_antrian; ?>" style="width:80px; text-align:center" readonly class="disabled">
											<input type="text" id="kode_sisla" name="kode_sisla" value="<?= $ls_kode_sisla; ?>" style="width:150px;" readonly class="disabled">
										</div>
										<div class="form-row_kanan">
											<label style="text-align:right;">Status Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="hidden" id="kode_status_antrian" name="kode_status_antrian" value="<?= $ls_kode_status_antrian; ?>" style="width:40px;" readonly class="disabled">
											<input type="text" id="nama_status_antrian" name="nama_status_antrian" value="<?= $ls_nama_status_antrian; ?>" style="width:150px;" readonly class="disabled">
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">Jenis Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="kode_jenis_antrian" name="kode_jenis_antrian" value="<?= $ls_kode_jenis_antrian; ?>" style="width:50px;" readonly class="disabled">
											<input type="text" id="nama_jenis_antrian" name="nama_jenis_antrian" value="<?= $ls_nama_jenis_antrian; ?>" style="width:180px;" readonly class="disabled">
										</div>
										<div class="form-row_kanan">
											<label style="text-align:right;">No Handphone &nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="no_hp_antrian" name="no_hp_antrian" value="<?= $ls_no_hp_antrian; ?>" style="width:150px;" onblur="fl_js_val_numeric_antrian('no_hp_antrian');" maxlength="15" <?= ($_REQUEST["task"] == "new") ? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\""; ?>>
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">Tanggal Ambil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="tgl_ambil_antrian" name="tgl_ambil_antrian" value="<?= $ls_tgl_ambil_antrian; ?>" style="width:200px;" readonly class="disabled">
											<!-- <input type="text" id="jam_ambil_antrian" name="jam_ambil_antrian" value="<?= $ls_jam_ambil_antrian; ?>" style="width:140px;" readonly class="disabled"> -->
										</div>
										<div class="form-row_kanan">
											<label style="text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="email_antrian" name="email_antrian" value="<?= $ls_email_antrian; ?>" style="width:150px;" onblur="fl_js_val_email_antrian('email_antrian');" maxlength="200" <?= ($_REQUEST["task"] == "new") ? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\""; ?>>
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">Tanggal Panggil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="tgl_panggil_antrian" name="tgl_panggil_antrian" value="<?= $ls_tgl_panggil_antrian; ?>" style="width:200px;" readonly class="disabled">
											<!-- <input type="text" id="jam_panggil_antrian" name="jam_panggil_antrian" value="<?= $ls_jam_panggil_antrian; ?>" style="width:140px;" readonly class="disabled"> -->
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">Kantor Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="kode_kantor_antrian" name="kode_kantor_antrian" value="<?= $ls_kode_kantor_antrian; ?>" style="width:40px;" readonly class="disabled">
											<input type="text" id="nama_kantor_antrian" name="nama_kantor_antrian" value="<?= $ls_nama_kantor_antrian; ?>" style="width:190px;" readonly class="disabled">
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri">
											<label style="text-align:right;">Petugas Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
											<input type="text" id="kode_petugas_antrian" name="kode_petugas_antrian" value="<?= $ls_kode_petugas_antrian; ?>" style="width:60px;" readonly class="disabled">
											<input type="text" id="nama_petugas_antrian" name="nama_petugas_antrian" value="<?= $ls_nama_petugas_antrian; ?>" style="width:170px;" readonly class="disabled">
										</div>
										<div class="clear"></div>
									</fieldset>
								</div> <!-- end div formKiri -->

								<div id="formKanan">
									<fieldset>
										<legend></legend>
										<div class="form-row_kiri">

											<input id="image1" name="image1" disabled type="image" alt="img" <?= ($ls_path_url_foto == "") ? " src='../../images/empty-profile.png' " : " src='$ls_path_url_foto' "; ?> align="center" style="!important; width: 140px !important; height: 115px; border-radius: 6%; border: 1px solid #DBDBDB!important;" />
											<textarea type="text" id="base64_foto_peserta" name="base64_foto_peserta" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
										</div>
										<div class="clear"></div>
										<div class="form-row_kiri" style="text-align:center;">
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
											<button id="btn_camera" name="btn_camera" disabled onclick="openNewWindow(event);" <?= ($_REQUEST["task"] == "view" || $_REQUEST["task"] == "edit") ? "disabled" : ""; ?>>Camera</button>
										</div>
										<textarea type="text" id="path_url_antrian_curr" name="path_url_antrian_curr" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
										<input type="text" id="type_file_antrian" name="type_file_antrian" style="width:100px;" readonly class="disabled" hidden>
										<div class="clear"></div>
									</fieldset>
								</div>
								<!-- end div formKanan -->

								<!-- End Of Antrian -->

								<div class="div-row">
									<div class="div-col" style="width:46%; max-height: 100%;">
										<div class="div-row">
											<div class="div-col" style="width: 100%">
												<fieldset>
													<legend>Informasi Penerima Manfaat Sebelumnya</legend>
													<div class="div-row">
														<div class="div-col" style="width:77%;">
															<div class="form-row_kiri">
																<label style="text-align:right;">Nama Lengkap</label>
																<input type="text" id="nama_lengkap" name="nama_lengkap" style="width:250px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Tempat dan Tgl Lahir</label>
																<input type="text" id="tempat_lahir" name="tempat_lahir" style="width:160px;" readonly class="disabled">
																<input type="text" id="tgl_lahir" name="tgl_lahir" style="width:81px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Jenis Kelamin</label>
																<input type="text" id="nama_jenis_kelamin" name="nama_jenis_kelamin" style="width:250px;" readonly class="disabled">
																<input type="hidden" id="jenis_kelamin" name="jenis_kelamin">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Hubungan</label>
																<input type="text" id="kode_penerima_berkala" name="kode_penerima_berkala" style="width:40px;" readonly class="disabled">
																<input type="text" id="nama_hubungan" name="nama_hubungan" style="width:200px;" readonly class="disabled">
																<input type="hidden" id="kode_hubungan" name="kode_hubungan">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Nomor KK</label>
																<input type="text" id="no_kartu_keluarga" name="no_kartu_keluarga" style="width:240px;" readonly class="disabled">
															</div>
															<div class="clear"></div>
														</div>

														<div class="div-col" style="width:23%;text-align:center;">
															<input id="penerima_foto" name="penerima_foto" type="image" alt="img" src="../../images/nopic.png" align="center" style="height: 90px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;" />
														</div>
													</div>

													<div class="div-row">
														<div class="div-col">
															</br>
															<div class="form-row_kiri">
																<label style="text-align:right;">Nomor Identitas </label>
																<input type="text" id="nomor_identitas" name="nomor_identitas" style="width:230px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">NPWP &nbsp;</label>
																<input type="text" id="npwp" name="npwp" style="width:230px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Alamat Lengkap</label>
																<textarea cols="255" rows="1" style="width:230px;background-color:#F5F5F5;height:40px;" id="alamat_lengkap" name="alamat_lengkap" readonly onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">No. Telp</label>
																<input type="text" id="telepon_area" name="telepon_area" style="width:30px;" readonly class="disabled">
																<input type="text" id="telepon" name="telepon" style="width:120px;" readonly class="disabled">
																&nbsp;
																ext
																<input type="text" id="telepon_ext" name="telepon_ext" style="width:20px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>
																<input type="text" id="email" name="email" style="width:160px;" readonly class="disabled">
																<input type="checkbox" id="is_verified_email" name="is_verified_email" disabled class="cebox"><i>
																	<span style="color:#009999">verified</span>
																</i>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Handphone &nbsp;</label>
																<input type="text" id="handphone" name="handphone" style="width:160px;" readonly class="disabled">
																<input type="checkbox" id="is_verified_hp" name="is_verified_hp" disabled class="cebox"><i>
																	<span style="color:#009999">verified</span>
																</i>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">&nbsp;</label>
																<input type="checkbox" id="status_reg_notifikasi" name="status_reg_notifikasi" disabled class="cebox"><i>
																	<span style="color:#009999">Menerima Layanan SMS</span>
																</i>
															</div>
															<div class="clear"></div>
														</div>
													</div>
												</fieldset>
											</div>
										</div>
									</div>

									<div class="div-col" style="width:1%;">
									</div>

									<div class="div-col-right" style="width:53%;">
										<div class="div-row">
											<div class="div-col" style="width:98%;">
												<fieldset style="min-height:250px;">
													<legend>Daftar Pembayaran JP Berkala</legend>
													<table aria-describedby="table" id="tblrincian1" width="100%" class="table-data2">
														<thead>
															<tr class="hr-double-bottom">
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prg</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ke-</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
																<th style="text-align:right;font: 12px Arial, Helvetica, sans-serif;">Manfaat JP Berkala</th>
																<th style="text-align:right;font: 12px Arial, Helvetica, sans-serif;">Sudah Dibayar</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
															</tr>
														</thead>
														<tbody id="data_list_BlnBklInduk">
															<tr class="nohover-color">
																<td colspan="6" style="text-align: center;">-- data tidak ditemukan --</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i></td>
																<td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_induk_nom_berkala"></span></td>
																<td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_induk_nom_dibayar"></span></td>
																<td></td>
															</tr>
														</tfoot>
													</table>
												</fieldset>
											</div>
										</div>

										<div class="div-row">
											<div class="div-col" style="width:98%;">
												<input type="hidden" id="induk_cnt_blmbayar" name="induk_cnt_blmbayar">
												<input type="hidden" id="induk_kode_kondisi_anak_usia23" name="induk_kode_kondisi_anak_usia23">
												<input type="hidden" id="induk_tgl_kondisi_anak_usia23" name="induk_tgl_kondisi_anak_usia23">

												<!-------------- generate data konfirmasi ------------->
												<fieldset style="height:100px;text-align:center;">
													<legend><span style="vertical-align: middle;" id="span_fieldset_genkonfirmasi"></span></legend>
													<span style="vertical-align: middle;" id="span_note_before_genkonfirmasi"></span>

													<span id="span_rg_kondisi_terakhir_induk_a" style="display:none;">
														<input type="radio" name="rg_kondisi_terakhir_induk" onchange="fl_js_val_rg_kondisi_terakhir_induk();" value="A">
														<span style="vertical-align: middle;" id="span_ket_rg_kondisi_terakhir_induk_a"></span>
													</span>

													<span id="span_rg_kondisi_terakhir_induk_b" style="display:none;">
														<input type="radio" name="rg_kondisi_terakhir_induk" onchange="fl_js_val_rg_kondisi_terakhir_induk();" value="B">
														<span style="vertical-align: middle;" id="span_ket_rg_kondisi_terakhir_induk_b"></span>
													</span>

													</br>

													<span id="span_perubahan_kondisi_terakhir_induk" style="display:none;">
														<select size="1" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk" value="<?= $ls_kode_kondisi_terakhir_induk; ?>" tabindex="1" class="select_format" style="background-color:#ffff99;text-align:center;width:250px;height:28px;">
															<option value="">------ pilih kondisi akhir ------</option>
														</select>
														<input type="text" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" value="<?= $ld_tgl_kondisi_terakhir_induk; ?>" maxlength="10" tabindex="2" onblur="convert_date(tgl_kondisi_terakhir_induk);" style="background-color:#ffff99;text-align:center;width:130px;height:30px;" placeholder="-- sejak --">
														<input id="btn_tgl_kondisi_terakhir_induk" type="image" alt="img" class="calendar-picker" align="top" onclick="return showCalendar('tgl_kondisi_terakhir_induk', 'dd-mm-y');" src="../../images/calendar.gif" style="height:21px;" />
													</span>

												</fieldset>
												<!------------ end generate data konfirmasi ----------->
											</div>
										</div>

									</div>
								</div>
							</span>
						<?
						} else if ($task == "edit" || $task == "view") {
						?>
							<!-- Antrian -->
							<div id="formKiri" style="width:800px;">
								<fieldset>
									<legend><b><i>
												<span style="color:#009999">Entry Antrian Klaim</span>

											</i></b></legend>
									<div id="dispInfoToken" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">Token Antrian &nbsp;&nbsp;&nbsp;&nbsp;</label>
										<!-- <textarea type="text" id="token_antrian" name="token_antrian" rows="3" style="width:260px;" <?= ($_REQUEST["task"] == "view") ? " readonly class=\"disabled\"" : "onblur=\"f_js_get_info_token();\""; ?>><?= $ls_token_antrian; ?></textarea> -->
										<input type="password" id="token_antrian" name="token_antrian" style="width:260px;" value="<?= $ls_token_antrian; ?>" <?= ($task == "new") ? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\""; ?>>
									</div>
									<div class="form-row_kanan">
										<label style="text-align:right;">Kode Antrian &nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="kode_antrian" name="kode_antrian" value="<?= $ls_kode_antrian; ?>" style="width:150px;" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">No Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="no_antrian" name="no_antrian" value="<?= $ls_no_antrian; ?>" style="width:80px; text-align:center" readonly class="disabled">
										<input type="text" id="kode_sisla" name="kode_sisla" value="<?= $ls_kode_sisla; ?>" style="width:150px;" readonly class="disabled">
									</div>
									<div class="form-row_kanan">
										<label style="text-align:right;">Status Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="hidden" id="kode_status_antrian" name="kode_status_antrian" value="<?= $ls_kode_status_antrian; ?>" style="width:40px;" readonly class="disabled">
										<input type="text" id="nama_status_antrian" name="nama_status_antrian" value="<?= $ls_nama_status_antrian; ?>" style="width:150px;" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">Jenis Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="kode_jenis_antrian" name="kode_jenis_antrian" value="<?= $ls_kode_jenis_antrian; ?>" style="width:50px;" readonly class="disabled">
										<input type="text" id="nama_jenis_antrian" name="nama_jenis_antrian" value="<?= $ls_nama_jenis_antrian; ?>" style="width:180px;" readonly class="disabled">
									</div>
									<div class="form-row_kanan">
										<label style="text-align:right;">No Handphone &nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="no_hp_antrian" name="no_hp_antrian" value="<?= $ls_no_hp_antrian; ?>" style="width:150px;" onblur="fl_js_val_numeric_antrian('no_hp_antrian');" maxlength="15" <?= ($_REQUEST["task"] == "new") ? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\""; ?>>
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">Tanggal Ambil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="tgl_ambil_antrian" name="tgl_ambil_antrian" value="<?= $ls_tgl_ambil_antrian; ?>" style="width:200px;" readonly class="disabled">
										<!-- <input type="text" id="jam_ambil_antrian" name="jam_ambil_antrian" value="<?= $ls_jam_ambil_antrian; ?>" style="width:140px;" readonly class="disabled"> -->
									</div>
									<div class="form-row_kanan">
										<label style="text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="email_antrian" name="email_antrian" value="<?= $ls_email_antrian; ?>" style="width:150px;" onblur="fl_js_val_email_antrian('email_antrian');" maxlength="200" <?= ($_REQUEST["task"] == "new") ? "onblur=\"f_js_get_info_token();\"" : "readonly class=\"disabled\""; ?>>
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">Tanggal Panggil&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="tgl_panggil_antrian" name="tgl_panggil_antrian" value="<?= $ls_tgl_panggil_antrian; ?>" style="width:200px;" readonly class="disabled">
										<!-- <input type="text" id="jam_panggil_antrian" name="jam_panggil_antrian" value="<?= $ls_jam_panggil_antrian; ?>" style="width:140px;" readonly class="disabled"> -->
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">Kantor Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="kode_kantor_antrian" name="kode_kantor_antrian" value="<?= $ls_kode_kantor_antrian; ?>" style="width:40px;" readonly class="disabled">
										<input type="text" id="nama_kantor_antrian" name="nama_kantor_antrian" value="<?= $ls_nama_kantor_antrian; ?>" style="width:190px;" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label style="text-align:right;">Petugas Antrian&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
										<input type="text" id="kode_petugas_antrian" name="kode_petugas_antrian" value="<?= $ls_kode_petugas_antrian; ?>" style="width:60px;" readonly class="disabled">
										<input type="text" id="nama_petugas_antrian" name="nama_petugas_antrian" value="<?= $ls_nama_petugas_antrian; ?>" style="width:170px;" readonly class="disabled">
									</div>
									<div class="clear"></div>
								</fieldset>
							</div> <!-- end div formKiri -->

							<div id="formKanan">
								<fieldset>
									<legend></legend>
									<div class="form-row_kiri">

										<input id="image1" name="image1" type="image" alt="img" <?= ($ls_path_url_foto == "") ? " src='../../images/empty-profile.png' " : " src='$ls_path_url_foto' "; ?> align="center" style="!important; width: 140px !important; height: 115px; border-radius: 6%; border: 1px solid #DBDBDB!important;" />
										<textarea type="text" id="base64_foto_peserta" name="base64_foto_peserta" rows="3" style="width:260px;" readonly class="disabled" hidden></textarea>
									</div>
									<div class="clear"></div>
								</fieldset>
							</div>
							<!-- end div formKanan -->

							<!-- End Of Antrian -->
							<div class="div-row">
								<div class="div-col" style="width:45%; max-height: 100%;">
									<div class="div-row">

										<div class="div-col" style="width: 100%">

											<!-- Informasi Penerima Manfaat Selanjutnya ------------->
											<fieldset>
												<legend><span style="vertical-align: middle;" id="span_fieldset_curr_penerima"></span></legend>
												<span id="span_tidakada_cur_penerima" style="display:none;">
													<div class="div-row">
														<div class="div-col" style="width:100%;text-align:center;">
															<span style="color:#ff0000">TIDAK ADA PENERIMA MANFAAT TURUNAN</span>
														</div>
													</div>
												</span>

												<span id="span_ada_cur_penerima" style="display:none;">
													</br>
													<div class="div-row">
														<div class="div-col" style="width:78%;">
															<div class="form-row_kiri">
																<label style="text-align:right;">Nama Lengkap</label>
																<input type="text" id="cur_nama_lengkap" name="cur_nama_lengkap" style="width:205px;" readonly class="disabled">
																<input type="hidden" id="cur_no_urut_keluarga" name="cur_no_urut_keluarga">
																<input type="text" id="cur_kode_penerima_berkala" name="cur_kode_penerima_berkala" style="width:20px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Tempat dan Tgl Lahir</label>
																<input type="text" id="cur_tempat_lahir" name="cur_tempat_lahir" style="width:155px;" readonly class="disabled">
																<input type="text" id="cur_tgl_lahir" name="cur_tgl_lahir" style="width:70px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Jns Klmin-Hub-No.KK</label>
																<input type="text" id="cur_nama_jenis_kelamin" name="cur_nama_jenis_kelamin" style="width:80px;" readonly class="disabled">
																<input type="hidden" id="cur_jenis_kelamin" name="cur_jenis_kelamin">
																<input type="text" id="cur_nama_hubungan" name="cur_nama_hubungan" style="width:66px;" readonly class="disabled">
																<input type="hidden" id="cur_kode_hubungan" name="cur_kode_hubungan">
																<input type="text" id="cur_no_kartu_keluarga" name="cur_no_kartu_keluarga" style="width:70px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">No. Identitas</label>
																<input type="text" id="cur_nomor_identitas" name="cur_nomor_identitas" tabindex="1" maxlength="30" style="width:233px;">
																<input type="hidden" id="cur_status_valid_identitas" name="cur_status_valid_identitas">
																<input type="hidden" id="cur_jenis_identitas" name="cur_jenis_identitas">
																<input type="hidden" id="cur_kpj_tertanggung" name="cur_kpj_tertanggung">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">NPWP</label>
																<input type="text" id="cur_npwp" name="cur_npwp" tabindex="2" onblur="fl_js_val_npwp('cur_npwp');" maxlength="15" style="width:233px;">
															</div>
															<div class="clear"></div>
														</div>

														<div class="div-col" style="width:22%;text-align:center;">
															<input id="cur_foto" name="cur_foto" type="image" alt="img" src="../../images/nopic.png" align="center" style="height: 90px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;" />
														</div>
													</div>

													<div class="div-row">
														<div class="div-col">
															</br>
															<div class="form-row_kiri">
																<label style="text-align:right;">Alamat*</label>
																<textarea cols="255" rows="1" style="width:233px;background-color:#ffff99;height:15px;" tabindex="3" id="cur_alamat" name="cur_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">RT/RW</label>
																<input type="text" id="cur_rt" name="cur_rt" tabindex="4" maxlength="5" onblur="fl_js_val_numeric('cur_rt');" style="width:20px;">
																/
																<input type="text" id="cur_rw" name="cur_rw" tabindex="5" maxlength="5" onblur="fl_js_val_numeric('cur_rw');" style="width:30px;">
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kode Pos
																<input type="text" id="cur_kode_pos" name="cur_kode_pos" tabindex="6" maxlength="10" readonly style="background-color:#ffff99;width:50px;">
																<a href="#" onclick="NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_lov_pos.php?p=pn5065_tabedit.php&a=formreg&b=cur_kode_kelurahan&c=cur_nama_kelurahan&d=cur_kode_kecamatan&e=cur_nama_kecamatan&f=cur_kode_kabupaten&g=cur_nama_kabupaten&h=cur_kode_propinsi&j=cur_nama_propinsi&k=cur_kode_pos&l=cur_ket_alamat_lanjutan','',800,500,1)">
																	<img alt="img" src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">&nbsp;</label>
																<textarea cols="255" rows="1" style="width:233px;height:15px;background-color:#F5F5F5;" id="cur_ket_alamat_lanjutan" name="cur_ket_alamat_lanjutan" readonly onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>
																<input type="hidden" id="cur_nama_kelurahan" name="cur_nama_kelurahan">
																<input type="hidden" id="cur_kode_kelurahan" name="cur_kode_kelurahan">
																<input type="hidden" id="cur_nama_kecamatan" name="cur_nama_kecamatan">
																<input type="hidden" id="cur_kode_kecamatan" name="cur_kode_kecamatan">
																<input type="hidden" id="cur_nama_kabupaten" name="cur_nama_kabupaten">
																<input type="hidden" id="cur_kode_kabupaten" name="cur_kode_kabupaten">
																<input type="hidden" id="cur_kode_propinsi" name="cur_kode_propinsi">
																<input type="hidden" id="cur_nama_propinsi" name="cur_nama_propinsi">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">No. Telp</label>
																<input type="text" id="cur_telepon_area" name="cur_telepon_area" tabindex="7" maxlength="5" onblur="fl_js_val_numeric('cur_telepon_area');" style="width:30px;">
																<input type="text" id="cur_telepon" name="cur_telepon" tabindex="8" maxlength="20" onblur="fl_js_val_numeric('cur_telepon');" style="width:131px;">
																&nbsp;ext.
																<input type="text" id="cur_telepon_ext" name="cur_telepon_ext" tabindex="9" maxlength="5" onblur="fl_js_val_numeric('cur_telepon_ext');" style="width:30px;">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Email</label>
																<input type="text" id="cur_email" name="cur_email" tabindex="10" onblur="this.value=this.value.toLowerCase();fl_js_val_email('cur_email');" maxlength="200" style="width:150px;">
																<input type="checkbox" id="cur_is_verified_email" name="cur_is_verified_email" disabled class="cebox"><i>
																	<span style="color:#009999">verified</span>
																</i>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Handphone</label>
																<input type="text" id="cur_handphone" name="cur_handphone" tabindex="11" onblur="fl_js_val_numeric('cur_handphone');" maxlength="15" style="width:150px;">
																<input type="checkbox" id="cur_is_verified_hp" name="cur_is_verified_hp" disabled class="cebox"><i>
																	<span style="color:#009999">verified</span>
																</i>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">&nbsp;</label>
																<input type="checkbox" id="cur_status_reg_notifikasi" name="cur_status_reg_notifikasi" disabled class="cebox"><i>
																	<span style="color:#009999">Layanan SMS Konfirmasi JP Berkala</span>
																</i>
																<input type="hidden" id="cur_status_cek_layanan" name="cur_status_cek_layanan">
															</div>
															<div class="clear"></div>

															</br>

															<div class="form-row_kiri">
																<label style="text-align:right;">Rekening Bank*</label>
																<input type="text" id="cur_bank_penerima" name="cur_bank_penerima" tabindex="12" readonly style="width:230px;background-color:#ffff99;">
																<input type="hidden" id="cur_kode_bank_penerima" name="cur_kode_bank_penerima">
																<input type="hidden" id="cur_id_bank_penerima" name="cur_id_bank_penerima">
																<input type="hidden" id="cur_metode_transfer" name="cur_metode_transfer" maxlength="4" readonly class="disabled" style="width:20px;">
																<a id="btn_lov_cur_bank_penerima" href="#" onclick="fl_js_reset_norek();NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5063_lov_bankpenerima.php?p=pn5065.php&a=formreg&b=cur_bank_penerima&c=cur_kode_bank_penerima&d=cur_id_bank_penerima','',800,500,1)">
																	<img alt="img" src="../../images/help.png" alt="Cari Bank" border="0" style="height:16px;" align="absmiddle"></a>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">No Rekening*</label>
																<input type="text" id="cur_no_rekening_penerima" name="cur_no_rekening_penerima" onblur="fjq_ajax_val_cur_no_rekening_penerima();" tabindex="13" maxlength="30" style="width:100px;background-color:#ffff99;">
																<input type="text" id="cur_nama_rekening_penerima_ws" name="cur_nama_rekening_penerima_ws" maxlength="100" style="width:100px;" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">
																<input type="checkbox" id="cb_cur_valid_rekening" name="cb_cur_valid_rekening" disabled class="cebox"><i>
																	<span style="color:#009999">Valid</span>
																</i>
																<input type="hidden" id="cur_nama_rekening_penerima" name="cur_nama_rekening_penerima">
																<input type="hidden" id="cur_st_valid_rekening_penerima" name="cur_st_valid_rekening_penerima">
																<input type="hidden" id="cur_kode_bank_pembayar" name="cur_kode_bank_pembayar">
																<input type="hidden" id="cur_nama_bank_pembayar" name="cur_nama_bank_pembayar">
																<input type="hidden" id="cur_status_rekening_sentral" name="cur_status_rekening_sentral">
																<input type="hidden" id="cur_kantor_rekening_sentral" name="cur_kantor_rekening_sentral">
															</div>
															<div class="clear"></div>

															</br>
														</div>
													</div>
												</span>
											</fieldset>
										</div>
									</div>

									<div class="div-row">
										<div class="div-col" style="width: 100%">
											<!-- Kontak Darurat --------------------------------------------------->
											<fieldset>
												<legend>Informasi Kontak Darurat</legend>
												<div class="form-row_kiri">
													<label style="text-align:right;">Nama Lengkap</label>
													<input type="text" id="inkd_nama_lengkap" name="inkd_nama_lengkap" value="<?= $ls_ind_nama_lengkap; ?>" style="width:233px;">
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Nomor Handphone</label>
													<input type="number" id="inkd_no_hp" name="inkd_no_hp" value="<?= $ls_ind_nama_lengkap; ?>" style="width:233px;">
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Alamat</label>
													<textarea cols="255" rows="1" style="width:233px;height:15px;" tabindex="3" id="inkd_alamat" name="inkd_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Hubungan Keluarga</label>
													<select size="1" id="inkd_hub_keluarga" name="inkd_hub_keluarga" value="<?= $ls_kode_kondisi_terakhir_induk; ?>" tabindex="1" class="select_format" style="width:238px !important;">
														<option value="">-- Hubungan Keluarga --</option>

														<?
														$sql = "select KODE, KETERANGAN from ms.ms_lookup where tipe = 'KODEHUBKONTAKDARURAT' order by seq asc";
														$DB->parse($sql);
														$DB->execute();
														while ($row = $DB->nextrow()) {

															echo "<option ";
															// if ($row["KODE_KONDISI_TERAKHIR"] == $ls_kode_kondisi_terakhir_induk && strlen($ls_kode_kondisi_terakhir_induk) == strlen($row["KODE_KONDISI_TERAKHIR"])) {
															// 	echo " selected";
															// }
															echo " value=\"" . $row["KODE"] . "\">" . $row["KETERANGAN"] . "</option>";
														}
														?>
													</select>
												</div>
												<div class="clear"></div>
											</fieldset>
											<!-- Informasi Penerima Manfaat Sebelumnya ---------------------------->
											<fieldset>
												<legend>Informasi Penerima Manfaat Sebelumnya</legend>
												<div class="form-row_kiri">
													<label style="text-align:right;">Nama Lengkap</label>
													<input type="text" id="ind_nama_lengkap" name="ind_nama_lengkap" value="<?= $ls_ind_nama_lengkap; ?>" style="width:200px;" readonly class="disabled">
													<input type="text" id="ind_kode_penerima_berkala" name="ind_kode_penerima_berkala" value="<?= $ls_ind_kode_penerima_berkala; ?>" style="width:20px;" readonly class="disabled">
													<input type="hidden" id="ind_nama_hubungan" name="ind_nama_hubungan" value="<?= $ls_ind_nama_hubungan; ?>" style="width:66px;" readonly class="disabled">
													<input type="hidden" id="ind_nama_jenis_kelamin" name="ind_nama_jenis_kelamin" value="<?= $ls_ind_nama_jenis_kelamin; ?>" style="width:65px;" readonly class="disabled">
													<input type="hidden" id="ind_jenis_kelamin" name="ind_jenis_kelamin" value="<?= $ls_ind_jenis_kelamin; ?>">
													<input type="hidden" id="ind_kode_hubungan" name="ind_kode_hubungan" value="<?= $ls_ind_kode_hubungan; ?>">
													<input type="hidden" id="ind_no_kartu_keluarga" name="ind_no_kartu_keluarga" value="<?= $ls_ind_no_kartu_keluarga; ?>" style="width:70px;" readonly class="disabled">
													<input type="hidden" id="ind_tempat_lahir" name="ind_tempat_lahir" value="<?= $ls_ind_tempat_lahir; ?>" style="width:140px;" readonly class="disabled">
													<input type="hidden" id="ind_tgl_lahir" name="ind_tgl_lahir" value="<?= $ld_ind_tgl_lahir; ?>" style="width:70px;" readonly class="disabled">
													<input type="hidden" id="ind_no_urut_keluarga" name="ind_no_urut_keluarga">
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Kondisi Terakhir</label>
													<input type="text" id="nama_kondisi_terakhir_induk" name="nama_kondisi_terakhir_induk" value="<?= $ls_nama_kondisi_terakhir_induk; ?>" style="width:116px;" readonly class="disabled">
													<input type="text" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" value="<?= $ld_tgl_kondisi_terakhir_induk; ?>" style="width:75px;" readonly class="disabled">
													<input type="hidden" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk" value="<?= $ls_kode_kondisi_terakhir_induk; ?>" style="width:90px;" readonly class="disabled">
													<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5069_view_jpn_ahliwarisdetil.php?kode_klaim='+formreg.kode_klaim.value+'&no_urut_keluarga='+formreg.ind_no_urut_keluarga.value+'&mid=<?= $mid; ?>','',980,610,'no')">&nbsp;<img alt="img" src="../../images/ico_profile.jpg" border="0" alt="Tambah" align="absmiddle" style="height:20px;" /> profile</a>
												</div>
												<div class="clear"></div>
											</fieldset>
										</div>
									</div>
								</div>

								<div class="div-col" style="width:1%;">
								</div>

								<div class="div-col-right" style="width:54%;">
									<div class="div-row">
										<div class="div-col" style="width:98%;text-align:center;">
											<fieldset>
												<legend><span style="vertical-align: middle;" id="span_fieldset_peserta"></span></legend>
												<input type="hidden" id="no_penetapan" name="no_penetapan" value="<?= $ls_no_penetapan; ?>">
												<input type="hidden" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?= $ln_no_konfirmasi_induk; ?>">
												<input type="hidden" id="cnt_berkala_detil" name="cnt_berkala_detil" value="<?= $ln_cnt_berkala_detil; ?>">
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_dokumen.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_document.jpg" border="0" alt="Rincian Dokumen Kelengkapan Administrasi" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Dokumen</span>
												</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_ahliwaris.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_ahliwaris.jpg" border="0" alt="Rincian Ahli Waris" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Ahli Waris</span>
												</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_historykonfirmasi.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_history.jpg" border="0" alt="Rincian Histori Konfirmasi" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Histori</span>
												</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_historypembayaran.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_pembayaran.jpg" border="0" alt="Rincian Histori Pembayaran" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Pembayaran</span>
												</a>
											</fieldset>
										</div>
									</div>

									<div class="div-row">
										<div class="div-col" style="width:98%;">
											</br>
											<!-- Informasi Pembayaran JP Berkala Selanjutnya ---------------------->
											<fieldset style="min-height:125px;">
												<legend><span style="vertical-align: middle;" id="span_fieldset_blnberkala"></span></legend>
												</br>

												<span id="span_tidakada_cur_periodeberkala" style="display:none;">
													<div class="div-row">
														<div class="div-col" style="width:100%;text-align:center;">
															<b><span style="vertical-align: middle;" id="span_info_tidakada_cur_periodeberkala"></span></b>
														</div>
													</div>
												</span>

												<span id="span_ada_cur_periodeberkala" style="display:none;">
													<table aria-describedby="table" id="tblBlnBkl" width="100%" class="table-data2">
														<thead>
															<tr class="hr-double-bottom">
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prg</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ke-</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jml Berkala</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
															</tr>
														</thead>
														<tbody id="data_list_BlnBkl">
															<tr class="nohover-color">
																<td colspan="8" style="text-align: center;">-- data tidak ditemukan --</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i></td>
																<td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_berjalan"></span></td>
																<td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_rapel"></span></td>
																<td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_kompensasi"></span></td>
																<td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_berkala"></span></td>
																<td></td>
															</tr>
														</tfoot>
													</table>
												</span>
												</br>
											</fieldset>
										</div>
									</div>

									<div class="div-row">
										<div class="div-col" style="width:98%;">
											<span id="span_dokumen" style="display:none;">
												</br>
												<!-- Informasi Dokumen Kelengkapan Administrasi-------->
												<fieldset style="min-height:226px;">
													<legend>Dokumen Kelengkapan Administrasi</legend>
													<table aria-describedby="table" id="tblrincianDok" width="100%" class="table-data2">
														<thead>
															<tr class="hr-double-bottom">
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Dokumen</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Mandatory</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal</th>
																<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">File</th>
															</tr>
														</thead>
														<tbody id="data_list_Dok">
															<tr class="nohover-color">
																<td colspan="7" style="text-align: center;">-- tidak ada persyaratan dokumen --</td>
															</tr>
														</tbody>
														<tfoot>
															<tr>
																<td style="text-align:center" colspan="7"></td>
															</tr>
														</tfoot>
													</table>
												</fieldset>
											</span>
										</div>
									</div>

								</div>
							</div>
						<?
						}
						?>
					</div>
					<!--end div_body-->

					<div id="div_footer" class="div-footer">
						<div class="div-footer-form" style="width:95%;">
							<div class="div-row">

								<?
								if ($task == "new") {
								?>
									<span id="span_button_new" style="display:none;">
										<div class="div-col">
											<div class="div-action-footer">
												<div class="icon">
													<a id="btn_doGenKonfirmasi" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan Generate Data Konfrimasi..?')) fl_js_doGenKonfirmasi();">
														<img alt="img" src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;" />
														<span>GENERATE DATA KONFIRMASI &nbsp;</span>
													</a>
												</div>
											</div>
										</div>
									</span>
								<?
								}
								?>

								<?
								if ($task == "edit") {
								?>
									<span id="span_button_edit" style="display:none;">

										<div class="div-col">
											<div class="div-action-footer">
												<div class="icon">
													<a style="display:none" id="btn_doSimpanPerubahan" href="#" onClick="if(confirm('Apakah anda yakin akan menyimpan perubahan data konfirmasi jp berkala..?')) fjq_ajax_val_save_konfirmasi_berkala();">
														<img alt="img" src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;" />
														<span>SIMPAN PERUBAHAN &nbsp;&nbsp;</span>
													</a>
												</div>
											</div>
										</div>

										<div class="div-col">
											<div class="div-action-footer">
												<div class="icon">
													<a style="display:none" id="btn_doLayananSms" href="#" onClick="fl_js_ShowformNotifikasiSMS();">
														<img alt="img" src="../../images/layanan_sms.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;" />
														<span>LAYANAN SMS &nbsp;&nbsp;</span>
														<input type="hidden" id="flag_aktif_lynsms" name="flag_aktif_lynsms">
													</a>
												</div>
											</div>
										</div>

										<div class="div-col">
											<div class="div-action-footer">
												<div class="icon">
													<a id="btn_CetakKonfirmasi" href="#" onClick="fl_js_doCetakKonfirmasi();">
														<img alt="img" src="../../images/ico_cetak.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;" />
														<span>CETAK KONFIRMASI &nbsp;&nbsp;</span>
													</a>
												</div>
											</div>
										</div>

										<div class="div-col">
											<div class="div-action-footer">
												<div class="icon">
													<a id="btn_SubmitKonfirmasi" href="#" onClick="if(confirm('Apakah anda yakin akan mensubmit data konfirmasi jp berkala..?')) fjq_ajax_val_submit_konfirmasi_berkala();">
														<img alt="img" src="../../images/ok.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;" />
														<span>SUBMIT DATA KONFIRMASI &nbsp;&nbsp;</span>
													</a>
												</div>
											</div>
										</div>

										<div class="div-col">
											<div class="div-action-footer">
												<div class="icon">
													<a href="#" onClick="if(confirm('Apakah anda yakin akan membatalkan data konfirmasi jp berkala..?')) fjq_ajax_val_batal_konfirmasi_berkala();">
														<img alt="img" src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;" />
														<span>BATALKAN DATA KONFIRMASI &nbsp;&nbsp;</span>
													</a>
												</div>
											</div>
										</div>
									</span>
								<?
								}
								?>

								<div class="div-col">
									<div class="div-action-footer">
										<div class="icon">
											<a id="btn_doBack2Grid" href="#" onClick="reloadPage();">
												<img alt="img" src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;" />
												<span>TUTUP</span>
											</a>
										</div>
									</div>
								</div>

							</div>
						</div>

						<div style="padding-top:6px;">
							<?
							if ($task == "new") {
							?>
								<span id="span_footer_keterangan_new" style="display:block;">
									<div class="div-footer-content" style="width:50%;">
										<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
										<ul>
											<li style="margin-left:15px;">Klik <img alt="img" src="../../images/help.png" alt="Cari Data Penetapan JP Berkala" border="0" align="absmiddle"> untuk <span style="color:#ff0000">memilih</span> data penetapan klaim JP Berkala yang akan dilakukan konfirmasi ulang.</li>
										</ul>
									</div>
								</span>
							<?
							} else if ($task == "edit") {
							?>
								<span id="span_footer_keterangan_edit" style="display:block;"></span>
							<?
							}
							?>
						</div>
					</div>
					<!--end div_footer-->
				</div>
				<!--end div_container-->
			<?php
				//end action task new, edit --------------------------------------------
			}
			?>
		</form>
	</div>
	<!--end div formKiri-->
</div>

<?php
require_once "../ajax/pn5065_js.php";
require_once "../../includes/footer_app_nosql.php";


?>