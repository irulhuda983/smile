<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$("input[type=text]").keyup(function() {
			$(this).val($(this).val().toUpperCase());
		});

		$(window).bind("resize", function() {
			resize();
		});

		resize();

		/** list checkbox */
		window.list_checkbox_record = [];
	});

	function resize() {
		$("#div_container").width($("#div_dummy").width());

		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());

		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
	}

	function showForm(mypage, myname, w, h, scroll) {
		var openwin = window.parent.Ext.create('Ext.window.Window', {
			title: myname,
			collapsible: true,
			animCollapse: true,
			maximizable: true,
			closable: true,
			width: w,
			height: h,
			minWidth: w,
			minHeight: h,
			layout: 'fit',
			modal: true,
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-x:hidden; overflow-y:hidden;" scrolling=="' + scroll + '"></iframe>',
			listeners: {
				close: function() {
					// filter();
				},
				destroy: function(wnd, eOpts) {}
			}
		});
		openwin.show();
		return openwin;
	}

	function getValue(val) {
		return val == null ? '' : val;
	}

	function getValueNumber(val) {
		return val == null ? '0' : val;
	}

	function search_by_changed() {
		$("#search_txt").val("");
	}

	function search_by2_changed() {
		$('#' + $('#search_by2').val()).show();
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function reloadPage() {
		document.formreg.task.value = '';
		document.formreg.editid.value = '';
		document.formreg.task_detil.value = '';
		window.location.href = '?mid=<?= $mid; ?>';
	}

	function asyncLoading() {
		preload(true);
	}

	function asyncPreload(isloading) {
		if (isloading) {
			window.asyncLoader = setInterval(asyncLoading, 100);
		} else {
			clearInterval(window.asyncLoader);
			preload(false);
		}
	}

	function orderby(e) {
		let order_by = $(e).attr('order_by');
		let order_type = $(e).attr('order_type');
		order_type = order_type == 'ASC' ? 'ASC' : 'DESC';

		$('#order_by').val(order_by);
		$('#order_type').val(order_type);

		order_type = order_type == 'ASC' ? 'DESC' : 'ASC';
		$(e).attr('order_type', order_type);

		$('.order-icon').each(function() {
			$(this).attr('src', '../../images/sort_both.png');
		});

		if (order_type == 'ASC') {
			$(e).find("img").attr('src', '../../images/sort_desc.png');
		} else {
			$(e).find("img").attr('src', '../../images/sort_asc.png');
		}

		filter();
	}
</script>

<!-- edit below -->
<script>
	$(document).ready(function() {
		let task = $('#task').val();
		let task_detil = $('#task_detil').val();

		if (task == "new") {} else if (task == "edit" || task == "view") {
			if (task_detil == "") {
				let kode_klaim = $('#kode_klaim').val();
				let no_konfirmasi = $('#no_konfirmasi').val();

				loadSelectedRecord(kode_klaim, no_konfirmasi, null);
			}
		} else {
			$('#editid').val('');
			$('#kode_klaim').val('');
			$('#no_konfirmasi').val('');
			filter();
		}
	});

	function filter(val = 0) {
		var pages = new Number($("#pages").val());
		var page = new Number($("#page").val());
		var page_item = $("#page_item").val();

		var search_by = $("#search_by").val();
		var search_txt = $("#search_txt").val();
		var search_by2 = $("#search_by2").val();
		var search_txt2a = $("#search_txt2a").val();
		var search_txt2b = $("#search_txt2b").val();
		var search_txt2c = $("#search_txt2c").val();
		var search_txt2d = $("#search_txt2d").val();
		var order_by = $("#order_by").val();
		var order_type = $("#order_type").val();

		if (val == '01') {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == '02') {
			page = pages;
		} else if (val == '-01') {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == '-02') {
			page = 1;
		} else {
			if (val == 0) {
				page = 1;
			} else {
				if (val > pages) {
					page = pages;
				}
			}
		}

		$("#page").val(page);

		asyncPreload(true);

		html_data = '';
		html_data += '<tr class="nohover-color">';
		html_data += '<td colspan="10" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5070_action.php?" + Math.random(),
			data: {
				tipe: 'MainDataGrid',
				page: page,
				page_item: page_item,
				search_by: search_by,
				search_txt: search_txt,
				search_by2: search_by2,
				search_txt2a: search_txt2a,
				search_txt2b: search_txt2b,
				search_txt2c: search_txt2c,
				search_txt2d: search_txt2d,
				order_by: order_by,
				order_type: order_type
			},
			success: function(data) {
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 1) {
						var html_data = "";
						// load data
						for (var i = 0; i < jdata.data.length; i++) {
							let kode_klaim = getValue(jdata.data[i].KODE_KLAIM);
							let no_konfirmasi = getValue(jdata.data[i].NO_KONFIRMASI);
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi) {
									return element;
								}
							});

							html_data += '<tr>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].ACTION) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NO) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KONFIRMASI) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_KONFIRMASI) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KET_PENERIMA_BERKALA) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KET_PERIODE) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KANTOR_KONFIRMASI) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
							html_data += '</tr>';
						}

						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="10" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_list").html(html_data);

						// load info halaman
						$("#pages").val(jdata.pages);
						$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

						// load info item
						$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
						$("#hdn_total_records").val(jdata.recordsTotal);

						resize();
					} else {
						//alert(jdata.msg);
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
				}
				asyncPreload(false);
			},
			complete: function() {
				asyncPreload(false);
			},
			error: function() {
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				asyncPreload(false);
			}
		});
	}

	function doGridTask(v_task, v_kode_klaim, v_no_konfirmasi) {
		document.formreg.task.value = v_task;
		document.formreg.kode_klaim.value = v_kode_klaim;
		document.formreg.no_konfirmasi.value = v_no_konfirmasi;
		try {
			document.formreg.onsubmit();
		} catch (e) {}
		document.formreg.submit();
	}

	function loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, fn) {
		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			return alert('Data Konfimasi JP Berkala tidak boleh kosong');
		}
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5070_action.php?" + Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatakonfirmasijpberkala',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi: v_no_konfirmasi
			},
			success: function(data) {
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) {
						//set Kontak Darurat
						$('#inkd_nama_lengkap').val(jdata.data.dataKontakDarurat['NAMA_KONTAK_DARURAT']);
						$('#inkd_no_hp').val(jdata.data.dataKontakDarurat['NO_HP_KONTAK_DARURAT']);
						$('#inkd_alamat').val(jdata.data.dataKontakDarurat['ALAMAT_KONTAK_DARURAT']);
						$('#inkd_hub_keluarga').val(jdata.data.dataKontakDarurat['KODE_HUBUNGAN_KONTAK_DARURAT']);
						$('select').select2();

						$("#span_page_title_right").html('NO. KONFIRMASI : ' + v_kode_klaim + '-' + v_no_konfirmasi);
						$('#no_penetapan').val(getValue(jdata.data.dataKonf['NO_PENETAPAN']));
						$('#no_konfirmasi_induk').val(getValue(jdata.data.dataKonf['NO_KONFIRMASI_INDUK']));
						$('#cnt_berkala_detil').val(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL']));

						if (parseInt(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL'])) > 0) {
							window.document.getElementById("span_tidakada_cur_penerima").style.display = 'none';
							window.document.getElementById("span_ada_cur_penerima").style.display = 'block';

							$("#span_fieldset_curr_penerima").html('Informasi Penerima Manfaat JP Berkala Periode ' + getValue(jdata.data.dataKonf['BLTH_AWAL']) + ' s/d ' + getValue(jdata.data.dataKonf['BLTH_AKHIR']) + '');

							window.document.getElementById("span_tidakada_cur_periodeberkala").style.display = 'none';
							window.document.getElementById("span_ada_cur_periodeberkala").style.display = 'block';
							window.document.getElementById("span_dokumen").style.display = 'block';
						} else {
							window.document.getElementById("span_tidakada_cur_penerima").style.display = 'block';
							window.document.getElementById("span_ada_cur_penerima").style.display = 'none';

							window.document.getElementById("span_tidakada_cur_periodeberkala").style.display = 'block';
							window.document.getElementById("span_ada_cur_periodeberkala").style.display = 'none';
							window.document.getElementById("span_dokumen").style.display = 'none';

							$("#span_info_tidakada_cur_periodeberkala").html('<b><span style="color:#ff0000">MANFAAT DIHENTIKAN SEJAK ' + getValue(jdata.data.dataKonf['TGL_KONDISI_TERAKHIR_INDUK']) + ' <br></br>HARAP TETAP MELAKUKAN SUBMIT DATA KONFIRMASI JP BERKALA..</span></b>');
						}

						$("#span_fieldset_peserta").html('Nama Peserta : ' + getValue(jdata.data.dataKonf['KET_KLAIM_ATASNAMA']).substring(0, 70) + ' ...');

						if (getValue(jdata.data.dataKonf['STATUS_BERHENTI_MANFAAT']) == 'Y') {
							$("#span_fieldset_blnberkala").html('');
						} else {
							$("#span_fieldset_blnberkala").html('Informasi Manfaat JP Berkala Periode ' + getValue(jdata.data.dataKonf['BLTH_AWAL']) + ' s/d ' + getValue(jdata.data.dataKonf['BLTH_AKHIR']) + '');
						}

						//get data current penerima manfaat jp berkala ---------------------
						$('#cur_nama_lengkap').val(getValue(jdata.data.dataKonf['CUR_NAMA_LENGKAP']));
						$('#cur_no_urut_keluarga').val(getValue(jdata.data.dataKonf['CUR_NO_URUT_KELUARGA']));
						$('#cur_kode_penerima_berkala').val(getValue(jdata.data.dataKonf['CUR_KODE_PENERIMA_BERKALA']));

						$('#cur_tempat_lahir').val(getValue(jdata.data.dataKonf['CUR_TEMPAT_LAHIR']));
						$('#cur_tgl_lahir').val(getValue(jdata.data.dataKonf['CUR_TGL_LAHIR']));
						$('#cur_nama_jenis_kelamin').val(getValue(jdata.data.dataKonf['CUR_NAMA_JENIS_KELAMIN']));
						$('#cur_jenis_kelamin').val(getValue(jdata.data.dataKonf['CUR_JENIS_KELAMIN']));
						$('#cur_nama_hubungan').val(getValue(jdata.data.dataKonf['CUR_NAMA_HUBUNGAN']));
						$('#cur_kode_hubungan').val(getValue(jdata.data.dataKonf['CUR_KODE_HUBUNGAN']));
						$('#cur_no_kartu_keluarga').val(getValue(jdata.data.dataKonf['CUR_NO_KARTU_KELUARGA']));
						$('#cur_nomor_identitas').val(getValue(jdata.data.dataKonf['CUR_NOMOR_IDENTITAS']));
						$('#cur_status_valid_identitas').val(getValue(jdata.data.dataKonf['CUR_STATUS_VALID_IDENTITAS']));
						$('#cur_jenis_identitas').val(getValue(jdata.data.dataKonf['CUR_JENIS_IDENTITAS']));
						$('#cur_kpj_tertanggung').val(getValue(jdata.data.dataKonf['CUR_KPJ_TERTANGGUNG']));
						$('#cur_npwp').val(getValue(jdata.data.dataKonf['CUR_NPWP']));

						$('#cur_foto').attr('src', '<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#cur_nomor_identitas').val());

						var v_cur_kel = getValue(jdata.data.dataKonf['CUR_NAMA_KELURAHAN']) == '' ? '' : 'KEL. ' + getValue(jdata.data.dataKonf['CUR_NAMA_KELURAHAN']);
						var v_cur_kec = getValue(jdata.data.dataKonf['CUR_NAMA_KECAMATAN']) == '' ? '' : 'KEC. ' + getValue(jdata.data.dataKonf['CUR_NAMA_KECAMATAN']);
						var v_cur_kab = getValue(jdata.data.dataKonf['CUR_NAMA_KABUPATEN']) == '' ? '' : 'KAB. ' + getValue(jdata.data.dataKonf['CUR_NAMA_KABUPATEN']);
						var v_cur_pro = getValue(jdata.data.dataKonf['CUR_NAMA_PROPINSI']) == '' ? '' : 'PROP. ' + getValue(jdata.data.dataKonf['CUR_NAMA_PROPINSI']);

						var v_cur_ket_alamat_lanjutan = v_cur_kel + ' ' + v_cur_kec + ' ' + v_cur_kab + ' ' + v_cur_pro;

						$('#cur_ket_alamat_lanjutan').val(v_cur_ket_alamat_lanjutan);
						$('#cur_alamat').val(getValue(jdata.data.dataKonf['CUR_ALAMAT']));
						$('#cur_rt').val(getValue(jdata.data.dataKonf['CUR_RT']));
						$('#cur_rw').val(getValue(jdata.data.dataKonf['CUR_RW']));
						$('#cur_kode_pos').val(getValue(jdata.data.dataKonf['CUR_KODE_POS']));
						$('#cur_nama_kelurahan').val(getValue(jdata.data.dataKonf['CUR_NAMA_KELURAHAN']));
						$('#cur_kode_kelurahan').val(getValue(jdata.data.dataKonf['CUR_KODE_KELURAHAN']));
						$('#cur_nama_kecamatan').val(getValue(jdata.data.dataKonf['CUR_NAMA_KECAMATAN']));
						$('#cur_kode_kecamatan').val(getValue(jdata.data.dataKonf['CUR_KODE_KECAMATAN']));
						$('#cur_nama_kabupaten').val(getValue(jdata.data.dataKonf['CUR_NAMA_KABUPATEN']));
						$('#cur_kode_kabupaten').val(getValue(jdata.data.dataKonf['CUR_KODE_KABUPATEN']));
						$('#cur_kode_propinsi').val(getValue(jdata.data.dataKonf['CUR_KODE_PROPINSI']));
						$('#cur_nama_propinsi').val(getValue(jdata.data.dataKonf['CUR_NAMA_PROPINSI']));
						$('#cur_telepon_area').val(getValue(jdata.data.dataKonf['CUR_TELEPON_AREA']));
						$('#cur_telepon').val(getValue(jdata.data.dataKonf['CUR_TELEPON']));
						$('#cur_telepon_ext').val(getValue(jdata.data.dataKonf['CUR_TELEPON_EXT']));
						$('#cur_email').val(getValue(jdata.data.dataKonf['CUR_EMAIL']));

						if (getValue(jdata.data.dataKonf['CUR_IS_VERIFIED_EMAIL']) == "Y") {
							window.document.getElementById('cur_is_verified_email').checked = true;
						} else {
							window.document.getElementById('cur_is_verified_email').checked = false;
						}

						$('#cur_handphone').val(getValue(jdata.data.dataKonf['CUR_HANDPHONE']));

						if (getValue(jdata.data.dataKonf['CUR_IS_VERIFIED_HP']) == "Y") {
							window.document.getElementById('cur_is_verified_hp').checked = true;
						} else {
							window.document.getElementById('cur_is_verified_hp').checked = false;
						}

						if (getValue(jdata.data.dataKonf['CUR_STATUS_REG_NOTIFIKASI']) == "Y") {
							window.document.getElementById('cur_status_reg_notifikasi').checked = true;
						} else {
							window.document.getElementById('cur_status_reg_notifikasi').checked = false;
						}

						$('#cur_status_cek_layanan').val(getValue(jdata.data.dataKonf['CUR_STATUS_CEK_LAYANAN']));
						$('#cur_bank_penerima').val(getValue(jdata.data.dataKonf['CUR_BANK_PENERIMA']));
						$('#cur_kode_bank_penerima').val(getValue(jdata.data.dataKonf['CUR_KODE_BANK_PENERIMA']));
						$('#cur_id_bank_penerima').val(getValue(jdata.data.dataKonf['CUR_ID_BANK_PENERIMA']));
						$('#cur_metode_transfer').val(getValue(jdata.data.dataKonf['CUR_METODE_TRANSFER']));
						$('#cur_no_rekening_penerima').val(getValue(jdata.data.dataKonf['CUR_NO_REKENING_PENERIMA']));
						$('#cur_nama_rekening_penerima_ws').val(getValue(jdata.data.dataKonf['CUR_NAMA_REKENING_PENERIMA']));

						if (getValue(jdata.data.dataKonf['CUR_ST_VALID_REKENING_PENERIMA']) == "Y") {
							window.document.getElementById('cb_cur_valid_rekening').checked = true;
						} else {
							window.document.getElementById('cb_cur_valid_rekening').checked = false;
						}

						$('#cur_nama_rekening_penerima').val(getValue(jdata.data.dataKonf['CUR_NAMA_REKENING_PENERIMA']));
						$('#cur_st_valid_rekening_penerima').val(getValue(jdata.data.dataKonf['CUR_ST_VALID_REKENING_PENERIMA']));
						$('#cur_kode_bank_pembayar').val(getValue(jdata.data.dataKonf['CUR_KODE_BANK_PEMBAYAR']));
						$('#cur_nama_bank_pembayar').val(getValue(jdata.data.dataKonf['CUR_NAMA_BANK_PEMBAYAR'])); //ws masih bernilai null - request pak gun 21/12/2019

						$('#cur_status_rekening_sentral').val(getValue(jdata.data.dataKonf['CUR_STATUS_REKENING_SENTRAL']));
						$('#cur_kantor_rekening_sentral').val(getValue(jdata.data.dataKonf['CUR_KANTOR_REKENING_SENTRAL']));
						//end get data current penerima manfaat jp berkala -----------------

						//get data lalu penerima manfaat jp berkala ------------------------
						$('#ind_nama_lengkap').val(getValue(jdata.data.dataKonf['IND_NAMA_LENGKAP']));
						$('#ind_kode_penerima_berkala').val(getValue(jdata.data.dataKonf['IND_KODE_PENERIMA_BERKALA']));
						$('#ind_nama_hubungan').val(getValue(jdata.data.dataKonf['IND_NAMA_HUBUNGAN']));
						$('#ind_nama_jenis_kelamin').val(getValue(jdata.data.dataKonf['IND_NAMA_JENIS_KELAMIN']));
						$('#ind_jenis_kelamin').val(getValue(jdata.data.dataKonf['IND_JENIS_KELAMIN']));
						$('#ind_kode_hubungan').val(getValue(jdata.data.dataKonf['IND_KODE_HUBUNGAN']));
						$('#ind_no_kartu_keluarga').val(getValue(jdata.data.dataKonf['IND_NO_KARTU_KELUARGA']));
						$('#ind_no_urut_keluarga').val(getValue(jdata.data.dataKonf['IND_NO_URUT_KELUARGA']));
						$('#ind_tempat_lahir').val(getValue(jdata.data.dataKonf['IND_TEMPAT_LAHIR']));
						$('#ind_tgl_lahir').val(getValue(jdata.data.dataKonf['IND_TGL_LAHIR']));
						$('#nama_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonf['NAMA_KONDISI_TERAKHIR_INDUK']));
						$('#tgl_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonf['TGL_KONDISI_TERAKHIR_INDUK']));
						$('#kode_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonf['KODE_KONDISI_TERAKHIR_INDUK']));
						//end get data lalu penerima manfaat jp berkala --------------------

						//get data list periode jp berkala ---------------------------------
						var html_data_BlnBkl = "";
						var v_tot_nom_berjalan = 0;
						var v_tot_nom_rapel = 0;
						var v_tot_nom_kompensasi = 0;
						var v_tot_nom_berkala = 0;

						if (jdata.data.dataBlnBkl) {
							for (var i = 0; i < jdata.data.dataBlnBkl.length; i++) {
								html_data_BlnBkl += '<tr>';
								html_data_BlnBkl += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBkl[i].NM_PRG) + '</td>';
								html_data_BlnBkl += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBkl[i].NO_PROSES) + '</td>';
								html_data_BlnBkl += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBkl[i].BLTH_PROSES) + '</td>';
								html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_BERJALAN)) + '</td>';
								html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_RAPEL)) + '</td>';
								html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_KOMPENSASI)) + '</td>';
								html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_BERKALA)) + '</td>';
								html_data_BlnBkl += '<td style="text-align: center;">' +
									'<a href="javascript:void(0)" onclick="fl_js_showRincianBerkala(\'' +
									getValue(jdata.data.dataBlnBkl[i].KODE_KLAIM) + '\', \'' +
									getValue(jdata.data.dataBlnBkl[i].NO_KONFIRMASI) + '\', \'' +
									getValue(jdata.data.dataBlnBkl[i].NO_PROSES) + '\', \'' +
									getValue(jdata.data.dataBlnBkl[i].KD_PRG) + '\', \'' +
									getValue(jdata.data.dataBlnBkl[i].BLTH_PROSES) + '\')"><img alt="img" src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN </span></a>' + '</td>';
								html_data_BlnBkl += '</tr>';

								v_tot_nom_berjalan += parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_BERJALAN), 4);
								v_tot_nom_rapel += parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_RAPEL), 4);
								v_tot_nom_kompensasi += parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_KOMPENSASI), 4);
								v_tot_nom_berkala += parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_BERKALA), 4);
							}

							if (html_data_BlnBkl == "") {
								html_data_BlnBkl += '<tr class="nohover-color">';
								html_data_BlnBkl += '<td colspan="8" style="text-align: center;">-- data tidak ditemukan --</td>';
								html_data_BlnBkl += '</tr>';
							}
							$("#data_list_BlnBkl").html(html_data_BlnBkl);

							$("#span_tot_berjalan").html(format_uang(v_tot_nom_berjalan));
							$("#span_tot_rapel").html(format_uang(v_tot_nom_rapel));
							$("#span_tot_kompensasi").html(format_uang(v_tot_nom_kompensasi));
							$("#span_tot_berkala").html(format_uang(v_tot_nom_berkala));
						}
						//end get data list periode jp berkala -----------------------------

						//get data list dokumen --------------------------------------------
						var html_data_Dok = "";
						var v_nama_dokumen = "";
						var v_nama_file = "";

						if (jdata.data.dataDok) {
							for (var i = 0; i < jdata.data.dataDok.length; i++) {
								v_nama_dokumen = getValue(jdata.data.dataDok[i].NAMA_DOKUMEN);
								v_nama_file = getValue(jdata.data.dataDok[i].NAMA_FILE);
								v_flag_mandatory = getValue(jdata.data.dataDok[i].FLAG_MANDATORY) === "Y" ? "<img alt='img' src=../../images/file_apply.gif>" : "";
								v_status_diserahkan = getValue(jdata.data.dataDok[i].STATUS_DISERAHKAN) === "Y" ? "checked" : "";
								v_item_status_diserahkan = '<input type="checkbox" disabled class="cebox" id=dcb_dok_status_diserahkan' + i + ' name=dcb_dok_status_diserahkan' + i + ' ' + v_status_diserahkan + ' ';

								html_data_Dok += '<tr>';
								html_data_Dok += '<td style="text-align: center;">' + getValue(jdata.data.dataDok[i].NO_URUT) + '</td>';
								html_data_Dok += '<td style="text-align: left; white-space:pre-wrap; word-wrap:break-word;">' + v_nama_dokumen + '</td>';
								html_data_Dok += '<td style="text-align: center;">' + v_flag_mandatory + '</td>';
								html_data_Dok += '<td style="text-align: right;"></td>';
								html_data_Dok += '<td style="text-align: center;">' + v_item_status_diserahkan + '</td>';
								html_data_Dok += '<td style="text-align: center;">' + getValue(jdata.data.dataDok[i].TGL_DISERAHKAN) + '</td>';
								html_data_Dok += '<td style="text-align: center; white-space:normal; word-wrap:break-word;">' +
									'<a href="javascript:void(0)" onclick="fl_js_DownloadDok(\'' +
									getValue(jdata.data.dataDok[i].URL) + '\', \'' +
									getValue(jdata.data.dataDok[i].NAMA_FILE) + '\')"> ' + v_nama_file + '</a>' + '</td>';
								html_data_Dok += '<tr>';
							}

							if (html_data_Dok == "") {
								html_data_Dok += '<tr class="nohover-color">';
								html_data_Dok += '<td colspan="7" style="text-align: center;">-- tidak ada persyaratan dokumen --</td>';
								html_data_Dok += '</tr>';
							}
							$("#data_list_Dok").html(html_data_Dok);
						}
						//end get data list dokumen ----------------------------------------

						if (fn && fn.success) {
							fn.success();
						}
					} else {
						alert(jdata.msg);
					}
				} catch (e) {
					console.log(e);
					alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
				}
				asyncPreload(false);
			},
			complete: function() {
				asyncPreload(false);
			},
			error: function(e) {
				console.log(e);
				alert("Terjadi kesalahan, coba beberapa saat lagi!!");
				asyncPreload(false);
			}
		});
	}

	function fl_js_showRincianBerkala(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_blth_proses) {
		var c_mid = '<?= $mid; ?>';
		showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalarinci.php?kode_klaim=' + v_kode_klaim + '&no_konfirmasi=' + v_no_konfirmasi + '&no_proses=' + v_no_proses + '&kd_prg=' + v_kd_prg + '&blth_proses=' + v_blth_proses + '&mid=' + c_mid + '', '', 980, 550, 'yes');
	}

	function fl_js_DownloadDok(v_url, v_nmfile) {
		let p = btoa(v_url);
		let f = btoa(v_nmfile);
		let u = btoa('<?= $gs_kode_user; ?>');
		let params = 'p=' + p + '&f=' + f + '&u=' + u;
		NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_download_dok.php?' + params, '', 1000, 620, 'no');
	}

	function fl_js_doTolak() {
		v_kode_klaim = $('#kode_klaim').val();
		v_no_konfirmasi = $('#no_konfirmasi').val();
		if (v_kode_klaim != '' && v_no_konfirmasi != '') {
			$('#task_detil').val('BTNTOLAK');
			try {
				document.formreg.onsubmit();
			} catch (e) {}
			document.formreg.submit();
		}
	}

	function reloadFormUtama() {
		document.formreg.task_detil.value = '';
		try {
			document.formreg.onsubmit();
		} catch (e) {}
		document.formreg.submit();
	}



	//-- -- -- -- - BUTTON TASK-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- - >
	//do simpan penolakan --------------------------------------------------------
	function fjq_ajax_val_tolak_konfirmasi_berkala() {
		var v_kode_klaim = $('#kode_klaim').val();
		var v_no_konfirmasi = $('#no_konfirmasi').val();
		var v_alasan_penolakan = $('#alasan_penolakan').val();

		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			alert('Data Konfirmasi JP Berkala tidak ditemukan, harap perhatikan data input..!!!');
		} else if (v_alasan_penolakan == '') {
			alert('Alasan Penolakan kosong, harap lengkapi data input..!!!');
		} else {
			preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5070_action.php?' + Math.random(),
				data: {
					tipe: 'fjq_ajax_val_tolak_konfirmasi_berkala',
					v_kode_klaim: v_kode_klaim,
					v_no_konfirmasi: v_no_konfirmasi,
					v_alasan_penolakan: v_alasan_penolakan
				},
				success: function(data) {
					preload(false);
					jdata = JSON.parse(data);
					if (jdata.ret == "0") {
						//save penolakan berhasil, reload form -----------------------------
						alert(jdata.msg);
						reloadPage();
					} else {
						//save penolakan gagal ---------------------------------------------
						alert(jdata.msg);
					}
				}
			}); //end ajax
		} //end if
	}
	//end do simpan penolakan ----------------------------------------------------

	//do approval konfirmasi data konfirmasi jp berkala --------------------------
	function fjq_ajax_val_approval_konfirmasi_berkala() {
		var v_kode_klaim = $('#kode_klaim').val();
		var v_no_konfirmasi = $('#no_konfirmasi').val();

		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			alert('Data Konfirmasi JP Berkala tidak ditemukan, harap perhatikan data input..!!!');
		} else {
			preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5070_action.php?' + Math.random(),
				data: {
					tipe: 'fjq_ajax_val_approval_konfirmasi_berkala',
					v_kode_klaim: v_kode_klaim,
					v_no_konfirmasi: v_no_konfirmasi
				},
				success: function(data) {
					preload(false);
					jdata = JSON.parse(data);
					if (jdata.ret == "0") {
						//approval konfirmasi berkala berhasil, reload form ---------------
						alert(jdata.msg);
						reloadPage();
					} else {
						//approval konfirmasi berkala gagal -------------------------------
						alert(jdata.msg);
					}
				}
			}); //end ajax
		} //end if
	}
	//end do approval konfirmasi data konfirmasi jp berkala ----------------------

	//do batal konfirmasi data konfirmasi jp berkala -----------------------------
	function fjq_ajax_val_batal_konfirmasi_berkala() {
		var v_kode_klaim = $('#kode_klaim').val();
		var v_no_konfirmasi = $('#no_konfirmasi').val();

		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			alert('Data Konfirmasi JP Berkala tidak ditemukan, harap perhatikan data input..!!!');
		} else {
			preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5070_action.php?' + Math.random(),
				data: {
					tipe: 'fjq_ajax_val_batal_konfirmasi_berkala',
					v_kode_klaim: v_kode_klaim,
					v_no_konfirmasi: v_no_konfirmasi
				},
				success: function(data) {
					preload(false);
					jdata = JSON.parse(data);
					if (jdata.ret == "0") {
						//simpan ubah konfirmasi berkala berhasil, reload form -------------
						alert(jdata.msg);
						reloadPage();
					} else {
						//simpan ubah konfirmasi berkala gagal -----------------------------
						alert(jdata.msg);
					}
				}
			}); //end ajax
		} //end if
	}
	//end do batal konfirmasi data konfirmasi jp berkala -------------------------

	//- -- -- -- -- -- -- -- -- --END BUTTON TASK-- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- -- >
</script>