<?php

?>
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
		window.location.href = '?tglawaldisplay=<?= $ld_tglawaldisplay; ?>&tglakhirdisplay=<?= $ld_tglakhirdisplay; ?>&mid=<?= $mid; ?>';
	}

	function reloadFormUtama() {
		document.formreg.task_detil.value = '';
		try {
			document.formreg.onsubmit();
		} catch (e) {}
		document.formreg.submit();
	}

	function fl_js_refreshFormUtama() {
		reloadFormUtama();
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

	function fl_js_set_tgl_sysdate(v_input) {
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!

		var yyyy = today.getFullYear();
		if (dd < 10) {
			dd = '0' + dd;
		}
		if (mm < 10) {
			mm = '0' + mm;
		}
		var today = dd + '/' + mm + '/' + yyyy;
		document.getElementById(v_input).value = today;
	}

	function fl_js_val_numeric(v_field_id) {
		var c_val = window.document.getElementById(v_field_id).value;
		var number = /^[0-9]+$/;

		if ((c_val != '') && (!c_val.match(number))) {
			document.getElementById(v_field_id).value = '';
			window.document.getElementById(v_field_id).focus();
			alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");
			return false;
		}
	}

	function fl_js_val_email(v_field_id) {
		var x = window.document.getElementById(v_field_id).value;
		var atpos = x.indexOf("@");
		var dotpos = x.lastIndexOf(".");
		if ((x != '') && (atpos < 1 || dotpos < atpos + 2 || dotpos + 2 >= x.length)) {
			document.getElementById(v_field_id).value = '';
			window.document.getElementById(v_field_id).focus();
			alert("Format Email tidak valid, belum ada (@ DAN .)");
			return false;
		}
	}

	function fl_js_val_npwp(v_field_id) {
		var v_npwp = window.document.getElementById(v_field_id).value;
		if ((v_npwp != '') && (v_npwp != '0') && (v_npwp.length != 15)) {
			document.getElementById(v_field_id).value = '0';
			window.document.getElementById(v_field_id).focus();
			alert("NPWP tidak valid, harus 15 karakter...!!!");
			return false;
		} else {
			fl_js_val_numeric(v_field_id);
		}
	}
</script>

<!-- edit below -->
<script>
	$(document).ready(function() {
		let task = $('#task').val();
		if (task == "new") {} else if (task == "edit" || task == "view") {
			let kode_klaim = $('#kode_klaim').val();
			let no_konfirmasi = $('#no_konfirmasi').val();

			loadSelectedRecord(kode_klaim, no_konfirmasi, null);
		} else {
			$('#editid').val('');
			$('#kode_klaim').val('');
			$('#no_konfirmasi').val('');
			fl_js_set_tgl_display('<?= $ld_tglawaldisplay; ?>', '<?= $ld_tglakhirdisplay; ?>');
			filter();
		}
	});

	function fl_js_set_tgl_display(v_tgl_awal, v_tgl_akhir) {
		if (v_tgl_awal == '' || v_tgl_akhir == '') {
			fl_js_set_tgl_sysdate('tglawaldisplay');
			fl_js_set_tgl_sysdate('tglakhirdisplay');
		} else {
			$('#tglawaldisplay').val(v_tgl_awal);
			$('#tglakhirdisplay').val(v_tgl_akhir);
		}
	}

	function checkRecordAll(el) {
		let checked = $(el).prop('checked');
		$("input[elname='cboxRecord']").each(function() {
			let kode_klaim = $(this).attr('kode_klaim');
			let no_konfirmasi = $(this).attr('no_konfirmasi');
			$(this).prop("checked", checked);
			if (checked == true) {
				var found = window.list_checkbox_record.find(function(element) {
					if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi) {
						return element;
					}
				});
				if (found == undefined) {
					window.list_checkbox_record.push({
						KODE_KLAIM: kode_klaim,
						NO_KONFIRMASI: no_konfirmasi
					});
				}
			} else {
				window.list_checkbox_record.forEach(function(element, i) {
					if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi) {
						window.list_checkbox_record.splice(i, 1);
					}
				});
			}
		});
	}

	function checkRecord(el) {
		let kode_klaim = $(el).attr('kode_klaim');
		let no_konfirmasi = $(el).attr('no_konfirmasi');

		if ($(el).prop("checked") == true) {
			var found = window.list_checkbox_record.find(function(element) {
				if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi) {
					return element;
				}
			});
			if (found == undefined) {
				window.list_checkbox_record.push({
					KODE_KLAIM: kode_klaim,
					NO_KONFIRMASI: no_konfirmasi
				});
			}
		} else {
			window.list_checkbox_record.forEach(function(element, i) {
				if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi) {
					window.list_checkbox_record.splice(i, 1);
				}
			});
		}
	}

	function showTask(task, editid) {
		if (task == 'new' || task == '') {
			document.formreg.task.value = task;
			try {
				document.formreg.onsubmit();
			} catch (e) {}
			document.formreg.submit();
		} else if (task == 'edit' || task == 'view') {
			if (window.list_checkbox_record.length != 1) {
				return alert('Silahkan pilih hanya salah satu record saja!');
			}

			document.formreg.task.value = task;
			document.formreg.kode_klaim.value = window.list_checkbox_record[0].KODE_KLAIM;
			document.formreg.no_konfirmasi.value = window.list_checkbox_record[0].NO_KONFIRMASI;
			try {
				document.formreg.onsubmit();
			} catch (e) {}
			document.formreg.submit();
		} else {
			alert('Task is not support');
		}
	}

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
		var tgl_awal = $("#tglawaldisplay").val();
		var tgl_akhir = $("#tglakhirdisplay").val();

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
			url: "../ajax/pn5065_action.php?" + Math.random(),
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
				order_type: order_type,
				tgl_awal: tgl_awal,
				tgl_akhir: tgl_akhir
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
							html_data += '<td style="text-align: center;">' +
								'<input type="checkbox" id="cboxRecord' + i + '" name="cboxRecord' + i + '" elname="cboxRecord" ' +
								'onchange="checkRecord(this)" ' +
								(checkedBox ? 'checked' : '') + ' ' +
								'kode_klaim="' + getValue(jdata.data[i].KODE_KLAIM) + '" no_konfirmasi="' + getValue(jdata.data[i].NO_KONFIRMASI) + '">' + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NO) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KONFIRMASI_DISPLAY) + '</td>';
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
</script>

<script language="javascript">
	//------------------------------ NEW -----------------------------------------
	function fl_js_reload_post_lovpenetapan() {
		var fn;
		v_kode_klaim = $('#kode_klaim').val();
		v_no_konfirmasi_induk = $('#no_konfirmasi_induk').val();

		if (v_kode_klaim == '' || v_no_konfirmasi_induk == '') {
			return alert('Data Klaim JP Berkala yang akan dilakukan konfirmasi ulang tidak boleh kosong');
		} else {
			//reset data list --------------------------------------------------------
			$('#tgl_kondisi_terakhir_induk').val('');
			$('#kode_kondisi_terakhir_induk').val('');

			fl_js_setCheckedValueRadioButton('rg_kondisi_terakhir_induk', 'X');
			fl_js_val_rg_kondisi_terakhir_induk();

			fl_js_reset_dataList('kode_kondisi_terakhir_induk');
			var reset_lov_kondisi_akhir = document.getElementById('kode_kondisi_terakhir_induk');
			var option = document.createElement('option');
			option.text = '------ pilih kondisi akhir ------';
			option.value = '';
			option.setAttribute('nama_kondisi_terakhir_induk', option.text);
			option.setAttribute('kode_kondisi_terakhir_induk', option.value);
			reset_lov_kondisi_akhir.add(option);
			//end reset data list ----------------------------------------------------

			//load data yang akan dilakukan konfirmasi ulang -------------------------
			asyncPreload(true);
			$.ajax({
				type: 'POST',
				url: "../ajax/pn5065_action.php?" + Math.random(),
				data: {
					tipe: 'fjq_ajax_val_postlovpenetapan',
					v_kode_klaim: v_kode_klaim,
					v_no_konfirmasi_induk: v_no_konfirmasi_induk
				},
				success: function(data) {
					try {
						jdata = JSON.parse(data);
						if (jdata.ret == 0) {
							$("#span_page_title_right").html('KODE KLAIM : ' + v_kode_klaim);
							window.document.getElementById("span_new_konfirmasi").style.display = 'block';
							window.document.getElementById("img_new_ahliwaris").style.display = '';
							$("#span_ket_img_ahliwaris").html('<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Ahli Waris</span>');

							//set data induk penerima manfaat jp berkala ---------------------
							$('#nama_lengkap').val(getValue(jdata.data.dataKonfInduk['NAMA_LENGKAP']));
							$('#tempat_lahir').val(getValue(jdata.data.dataKonfInduk['TEMPAT_LAHIR']));
							$('#tgl_lahir').val(getValue(jdata.data.dataKonfInduk['TGL_LAHIR']));
							$('#nama_jenis_kelamin').val(getValue(jdata.data.dataKonfInduk['NAMA_JENIS_KELAMIN']));
							$('#jenis_kelamin').val(getValue(jdata.data.dataKonfInduk['JENIS_KELAMIN']));
							$('#kode_penerima_berkala').val(getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']));
							$('#nama_hubungan').val(getValue(jdata.data.dataKonfInduk['NAMA_HUBUNGAN']));
							$('#kode_hubungan').val(getValue(jdata.data.dataKonfInduk['KODE_HUBUNGAN']));
							$('#no_kartu_keluarga').val(getValue(jdata.data.dataKonfInduk['NO_KARTU_KELUARGA']));
							$('#nomor_identitas').val(getValue(jdata.data.dataKonfInduk['NOMOR_IDENTITAS']));

							$('#penerima_foto').attr('src', '<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#nomor_identitas').val());

							$('#npwp').val(getValue(jdata.data.dataKonfInduk['NPWP']));
							$('#alamat_lengkap').val(getValue(jdata.data.dataKonfInduk['ALAMAT_LENGKAP']));
							$('#telepon_area').val(getValue(jdata.data.dataKonfInduk['TELEPON_AREA']));
							$('#telepon').val(getValue(jdata.data.dataKonfInduk['TELEPON']));
							$('#telepon_ext').val(getValue(jdata.data.dataKonfInduk['TELEPON_EXT']));

							$('#email').val(getValue(jdata.data.dataKonfInduk['EMAIL']));
							if (getValue(jdata.data.dataKonfInduk['IS_VERIFIED_EMAIL']) == "Y") {
								window.document.getElementById('is_verified_email').checked = true;
							} else {
								window.document.getElementById('is_verified_email').checked = false;
							}

							$('#handphone').val(getValue(jdata.data.dataKonfInduk['HANDPHONE']));
							if (getValue(jdata.data.dataKonfInduk['IS_VERIFIED_HP']) == "Y") {
								window.document.getElementById('is_verified_hp').checked = true;
							} else {
								window.document.getElementById('is_verified_hp').checked = false;
							}

							if (getValue(jdata.data.dataKonfInduk['STATUS_REG_NOTIFIKASI']) == "Y") {
								window.document.getElementById('status_reg_notifikasi').checked = true;
							} else {
								window.document.getElementById('status_reg_notifikasi').checked = false;
							}
							//end set data induk penerima manfaat jp berkala -----------------

							$('#no_hp_antrian').val(getValue(jdata.data.dataKonfInduk['HANDPHONE']));
							$('#email_antrian').val(getValue(jdata.data.dataKonfInduk['EMAIL']));
							//set data pembayaran jp berkala induk ---------------------------
							var html_data_BlnBklInduk = "";
							var v_tot_induk_nom_berkala = 0;
							var v_tot_induk_nom_dibayar = 0;

							if (jdata.data.dataBlnBklInduk) {
								for (var i = 0; i < jdata.data.dataBlnBklInduk.length; i++) {
									html_data_BlnBklInduk += '<tr>';
									html_data_BlnBklInduk += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBklInduk[i].NM_PRG) + '</td>';
									html_data_BlnBklInduk += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBklInduk[i].NO_PROSES) + '</td>';
									html_data_BlnBklInduk += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBklInduk[i].BLTH_PROSES) + '</td>';
									html_data_BlnBklInduk += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBklInduk[i].NOM_BERKALA)) + '</td>';
									html_data_BlnBklInduk += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBklInduk[i].NOM_DIBAYAR)) + '</td>';
									html_data_BlnBklInduk += '<td style="text-align: center;">' +
										'<a href="javascript:void(0)" onclick="fl_js_showRincianBerkala(\'' +
										getValue(jdata.data.dataBlnBklInduk[i].KODE_KLAIM) + '\', \'' +
										getValue(jdata.data.dataBlnBklInduk[i].NO_KONFIRMASI) + '\', \'' +
										getValue(jdata.data.dataBlnBklInduk[i].NO_PROSES) + '\', \'' +
										getValue(jdata.data.dataBlnBklInduk[i].KD_PRG) + '\', \'' +
										getValue(jdata.data.dataBlnBklInduk[i].BLTH_PROSES) + '\')"><img alt="img" src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN </span></a>' + '</td>';
									html_data_BlnBklInduk += '</tr>';

									v_tot_induk_nom_berkala += parseFloat(getValue(jdata.data.dataBlnBklInduk[i].NOM_BERKALA), 4);
									v_tot_induk_nom_dibayar += parseFloat(getValue(jdata.data.dataBlnBklInduk[i].NOM_DIBAYAR), 4);
								}

								if (html_data_BlnBklInduk == "") {
									html_data_BlnBklInduk += '<tr class="nohover-color">';
									html_data_BlnBklInduk += '<td colspan="6" style="text-align: center;">-- data tidak ditemukan --</td>';
									html_data_BlnBklInduk += '</tr>';
								}
								$("#data_list_BlnBklInduk").html(html_data_BlnBklInduk);

								$("#span_tot_induk_nom_berkala").html(format_uang(v_tot_induk_nom_berkala));
								$("#span_tot_induk_nom_dibayar").html(format_uang(v_tot_induk_nom_dibayar));
							}
							//end set data pembayaran jp berkala induk -----------------------

							//set value utk kebutuhan verifikasi data induk sblm generate ----				
							$('#induk_cnt_blmbayar').val(getValue(jdata.data.dataKonfInduk['CNT_BLMBAYAR']));
							$('#induk_kode_kondisi_anak_usia23').val(getValue(jdata.data.dataKonfInduk['KODE_KONDISI_ANAK_USIA23']));
							$('#induk_tgl_kondisi_anak_usia23').val(getValue(jdata.data.dataKonfInduk['TGL_KONDISI_ANAK_USIA23']));

							var lov_kondisi_akhir = document.getElementById('kode_kondisi_terakhir_induk');

							//---------------- ANAK MENCAPAI USIA 23 -------------------------
							//jika data induk adalah anak yg sudah memasuki usia 23 maka otomatis set kondisi akhir -----------
							if ((getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']) == 'A1' || getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']) == 'A2') && (getValue(jdata.data.dataKonfInduk['KODE_KONDISI_ANAK_USIA23']) != '' && getValue(jdata.data.dataKonfInduk['TGL_KONDISI_ANAK_USIA23']) != '')) {
								//langsung set ke Ada Perubahan Status -------------------------
								window.document.getElementById("span_rg_kondisi_terakhir_induk_a").style.display = 'none';
								window.document.getElementById("span_rg_kondisi_terakhir_induk_b").style.display = 'block';

								fl_js_setCheckedValueRadioButton('rg_kondisi_terakhir_induk', 'B');
								$("#span_ket_rg_kondisi_terakhir_induk_b").html('<span style="color:#009999"><b>Ada Perubahan Status, anak sudah mencapai usia 23 tahun</b></span>');

								window.document.getElementById("span_perubahan_kondisi_terakhir_induk").style.display = 'block';
								$('#tgl_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonfInduk['TGL_KONDISI_ANAK_USIA23']));

								//isikan data list kondisi terakhir induk ----------------------
								var v_kondisi_akhir_selected = getValue(jdata.data.dataKonfInduk['KODE_KONDISI_ANAK_USIA23']);
								if (jdata.data.dataListKondisiAkhir) {
									for ($i = 0; $i < (jdata.data.dataListKondisiAkhir.length); $i++) {
										//tampilkan hanya pilihan usia 23 --------------------------
										var option = document.createElement('option');
										option.text = getValue(jdata.data.dataListKondisiAkhir[$i]['NAMA_KONDISI_TERAKHIR']);
										option.value = getValue(jdata.data.dataListKondisiAkhir[$i]['KODE_KONDISI_TERAKHIR']);
										option.setAttribute('nama_kondisi_terakhir_induk', option.text);
										option.setAttribute('kode_kondisi_terakhir_induk', option.value);
										if ((option.value == v_kondisi_akhir_selected) && (option.value.length == v_kondisi_akhir_selected.length)) {
											option.selected = true;
										}
										if ((option.value == v_kondisi_akhir_selected) && (option.value.length == v_kondisi_akhir_selected.length)) {
											lov_kondisi_akhir.add(option);
										}
									}
								}
								$('select').select2();
								//end isikan data list kondisi terakhir induk ------------------

								//jika usia 23 adalah anak pertama maka cek apakah ada anak ke-2 yg eligible ----------
								if (getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']) == 'A1') {
									if (parseInt(getValueNumber(jdata.data.dataKonfInduk['CNT_ANAK_A2'])) == 0) {
										alert('Penerima Manfaat Berkala - ANAK A1 - saat ini SUDAH MENCAPAI USIA 23 TAHUN, </br> manfaat berkala untuk klaim penetapan nomor ' + getValue(jdata.data.dataKonfInduk['NO_PENETAPAN']) + ' akan dihentikan karena TIDAK ADA PENERIMA MANFAAT SELANJUTNYA, </br> Harap lakukan Generate Data Konfirmasi dan dilanjutkan sampai tahap terakhir untuk PENGHENTIAN manfaat berkala...!');
									} else {
										alert('Penerima Manfaat Berkala - ANAK A1 - saat ini SUDAH MENCAPAI USIA 23 TAHUN, </br> manfaat akan dihentikan untuk anak tersebut dan akan dilanjutkan ke anak berikutnya, </br> Harap lakukan Generate Data Konfirmasi dan lengkapi informasi penerima manfaat selajutnya...!');
									}
								} else {
									alert('Penerima Manfaat Berkala - ANAK A2 - saat ini SUDAH MENCAPAI USIA 23 TAHUN, </br> manfaat berkala untuk klaim penetapan nomor ' + getValue(jdata.data.dataKonfInduk['NO_PENETAPAN']) + ' akan dihentikan karena TIDAK ADA PENERIMA MANFAAT SELANJUTNYA, </br> Harap lakukan Generate Data Konfirmasi dan dilanjutkan sampai tahap terakhir untuk PENGHENTIAN manfaat berkala...!');
								}
								//---------------- END ANAK MENCAPAI USIA 23 -------------------	 							 	 
							} else {
								//---------------- BUKAN ANAK MENCAPAI USIA 23 -----------------
								if (parseInt(getValueNumber(jdata.data.dataKonfInduk['CNT_BLMBAYAR'])) > 0) {
									$("#span_fieldset_genkonfirmasi").html('');
									$("#span_note_before_genkonfirmasi").html('</br><b><span style="color:#ff0000">Masih ada Periode Berkala yang belum dibayarkan, Konfirmasi belum dapat dilakukan..! </span></b></br>');

									//konfirmasi dapat dilakukan jika ada perubahan status -------
									window.document.getElementById("span_rg_kondisi_terakhir_induk_a").style.display = 'none';
									window.document.getElementById("span_rg_kondisi_terakhir_induk_b").style.display = 'block';
									$("#span_ket_rg_kondisi_terakhir_induk_b").html('<b><span style="color:#ff0000">Konfirmasi dapat dilakukan jika: </span></b> <span style="color:#009999"><b>Ada Perubahan Status (Menikah/Usia23,dll)</b></span>');
								} else {
									$("#span_fieldset_genkonfirmasi").html('Kondisi Terakhir dari ' + getValue(jdata.data.dataKonfInduk['NAMA_LENGKAP']) + '');
									$("#span_note_before_genkonfirmasi").html('');

									window.document.getElementById("span_rg_kondisi_terakhir_induk_a").style.display = 'block';
									window.document.getElementById("span_rg_kondisi_terakhir_induk_b").style.display = 'block';
									$("#span_ket_rg_kondisi_terakhir_induk_a").html('<span style="color:#009999"><b>Kondisi Sama Dengan Sebelumnya</b></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
									$("#span_ket_rg_kondisi_terakhir_induk_b").html('<span style="color:#009999"><b>Ada Perubahan Status (Menikah/Usia23,dll)</b></span>');
								}

								//isikan list kondisi akhir ------------------------------------
								var v_kondisi_akhir_selected = '';
								if (jdata.data.dataListKondisiAkhir) {
									if (getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']) == 'OT') {
										for ($i = 0; $i < (jdata.data.dataListKondisiAkhir.length); $i++) {
											//tampilkan hanya pilihan KA11 -------------------------
											var option = document.createElement('option');
											option.text = getValue(jdata.data.dataListKondisiAkhir[$i]['NAMA_KONDISI_TERAKHIR']);
											option.value = getValue(jdata.data.dataListKondisiAkhir[$i]['KODE_KONDISI_TERAKHIR']);
											option.setAttribute('nama_kondisi_terakhir_induk', option.text);
											option.setAttribute('kode_kondisi_terakhir_induk', option.value);
											if ((option.value == v_kondisi_akhir_selected) && (option.value.length == v_kondisi_akhir_selected.length)) {
												option.selected = true;
											}
											if (option.value == "KA11") {
												lov_kondisi_akhir.add(option);
											}
										}
									} else if (getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']) == 'JD') {
										for ($i = 0; $i < (jdata.data.dataListKondisiAkhir.length); $i++) {
											//tampilkan hanya pilihan KA11 dan KA12 ------------------
											var option = document.createElement('option');
											option.text = getValue(jdata.data.dataListKondisiAkhir[$i]['NAMA_KONDISI_TERAKHIR']);
											option.value = getValue(jdata.data.dataListKondisiAkhir[$i]['KODE_KONDISI_TERAKHIR']);
											option.setAttribute('nama_kondisi_terakhir_induk', option.text);
											option.setAttribute('kode_kondisi_terakhir_induk', option.value);
											if ((option.value == v_kondisi_akhir_selected) && (option.value.length == v_kondisi_akhir_selected.length)) {
												option.selected = true;
											}
											if (option.value == "KA11" || option.value == "KA12") {
												lov_kondisi_akhir.add(option);
											}
										}
									} else if (getValue(jdata.data.dataKonfInduk['KODE_PENERIMA_BERKALA']) == 'TK') {
										for ($i = 0; $i < (jdata.data.dataListKondisiAkhir.length); $i++) {
											//tampilkan hanya pilihan KA11 -------------------------
											var option = document.createElement('option');
											option.text = getValue(jdata.data.dataListKondisiAkhir[$i]['NAMA_KONDISI_TERAKHIR']);
											option.value = getValue(jdata.data.dataListKondisiAkhir[$i]['KODE_KONDISI_TERAKHIR']);
											option.setAttribute('nama_kondisi_terakhir_induk', option.text);
											option.setAttribute('kode_kondisi_terakhir_induk', option.value);
											if ((option.value == v_kondisi_akhir_selected) && (option.value.length == v_kondisi_akhir_selected.length)) {
												option.selected = true;
											}
											if (option.value == "KA11") {
												lov_kondisi_akhir.add(option);
											}
										}
									} else {
										for ($i = 0; $i < (jdata.data.dataListKondisiAkhir.length); $i++) {
											//tampilkan semua pilihan --------------------------------
											var option = document.createElement('option');
											option.text = getValue(jdata.data.dataListKondisiAkhir[$i]['NAMA_KONDISI_TERAKHIR']);
											option.value = getValue(jdata.data.dataListKondisiAkhir[$i]['KODE_KONDISI_TERAKHIR']);
											option.setAttribute('nama_kondisi_terakhir_induk', option.text);
											option.setAttribute('kode_kondisi_terakhir_induk', option.value);
											if ((option.value == v_kondisi_akhir_selected) && (option.value.length == v_kondisi_akhir_selected.length)) {
												option.selected = true;
											}
											lov_kondisi_akhir.add(option);
										}
									}
								}
								$('select').select2();
								//end isikan data list kondisi terakhir induk ------------------		
								//---------------- END BUKAN ANAK MENCAPAI USIA 23 -------------												
							}
							//end set value utk kebutuhan verifikasi data induk --------------

							//set footer keterangan ------------------------------------------
							$("#span_footer_keterangan_new").html('<div class="div-footer-content" style="width:95%;"><div style="padding-bottom: 8px;"><b>Keterangan:</b></div><li style="margin-left:15px;">Pilih Kondisi Terakhir dari penerima manfaat berkala, jika tidak ada perubahan maka klik <span style="color:#ff0000"> Kondisi Sama Dengan Sebelumnya</span>. Apabila ada perubahan status maka klik <span style="color:#ff0000"> Ada Perubahan Status (Menikah/Usia23,dll)</span></li><li style="margin-left:15px;">Apabila ada perubahan status kondisi terakhir dari penerima manfaat berkala maka pilihan <span style="color:#ff0000"> Status Kondisi Akhir </span> dan <span style="color:#ff0000"> Tanggal Kejadian (Sejak) </span> wajib diisi.</li>	<li style="margin-left:15px;">Setelah pilihan ditentukan maka lanjutkan dengan klik tombol <span style="color:#ff0000"> Submit Data Konfirmasi </span></li><li style="margin-left:15px;">Setelah proses Submit maka akan ditampilkan jadwal pembayaran JP Berkala untuk periode berikutnya. User dapat melakukan perubahan informasi seperti <span style="color:#ff0000">Informasi Penerima</span> maupun<span style="color:#ff0000"> Rekening Pembayaran</span></li></div>');
							//end set footer keterangan --------------------------------------

							//set button -----------------------------------------------------
							window.document.getElementById("span_button_new").style.display = 'block';
							//end set button -------------------------------------------------

							if (fn && fn.success) {
								fn.success();
							}
						} else {
							alert(jdata.msg);
						}
					} catch (e) {
						alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
					}
					asyncPreload(false);
				},
				complete: function() {
					asyncPreload(false);
				},
				error: function() {
					alert("Terjadi kesalahan, coba beberapa saat lagi!!");
					asyncPreload(false);
				}
			});
		}
	}

	function fl_js_val_rg_kondisi_terakhir_induk() {
		var v_rg_kondisi = $("input[name='rg_kondisi_terakhir_induk']:checked").val();

		if (v_rg_kondisi == "B") {
			//aktifkan span pilihan ubah status ------------------------------------
			window.document.getElementById("span_perubahan_kondisi_terakhir_induk").style.display = 'block';
		} else {
			window.document.getElementById("span_perubahan_kondisi_terakhir_induk").style.display = 'none';
			$('#kode_kondisi_terakhir_induk').val('');
			$('#tgl_kondisi_terakhir_induk').val('');
		}
	}

	function fl_js_showRincianBerkala(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_blth_proses) {
		var c_mid = '<?= $mid; ?>';
		showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalarinci.php?kode_klaim=' + v_kode_klaim + '&no_konfirmasi=' + v_no_konfirmasi + '&no_proses=' + v_no_proses + '&kd_prg=' + v_kd_prg + '&blth_proses=' + v_blth_proses + '&mid=' + c_mid + '', '', 980, 550, 'yes');
	}

	function fl_js_setCheckedValueRadioButton(vRadioObj, vValue) {
		var radios = document.getElementsByName(vRadioObj);
		//reset value ----------------
		for (var j = 0; j < radios.length; j++) {
			radios[j].checked = false;
		}
		//assign value ----------------	
		for (var j = 0; j < radios.length; j++) {
			radios[j].checked = false;
			if (radios[j].value == vValue) {
				radios[j].checked = true;
				break;
			}
		}
	}

	function fl_js_reset_dataList(id) {
		var selectObj = document.getElementById(id);
		var selectParentNode = selectObj.parentNode;
		var newSelectObj = selectObj.cloneNode(false); // Make a shallow copy
		selectParentNode.replaceChild(newSelectObj, selectObj);
		return newSelectObj;
	}

	function fl_js_doGenKonfirmasi() {
		var c_kode_klaim = $('#kode_klaim').val();
		var c_no_konfirmasi_induk = $('#no_konfirmasi_induk').val();
		var c_kode_kondisi_terakhir_induk = $('#kode_kondisi_terakhir_induk').val();
		var c_tgl_kondisi_terakhir_induk = $('#tgl_kondisi_terakhir_induk').val();
		var c_sender_mid = $('#mid').val();
		// Parameter Antrian ---------------------------------------------------------------------
		var v_token_sisla = $('#token_antrian').val();
		var v_kode_jenis_antrian = $('#kode_jenis_antrian').val();
		var v_kode_status_antrian = "ST01"; //SETUJU  //$_POST["kode_status_antrian"];
		var v_kode_sisla = $('#kode_sisla').val();
		var v_kode_kantor_antrian = $('#kode_kantor_antrian').val();
		var v_nomor_antrian = $('#no_antrian').val();
		var v_tgl_ambil_antrian = $('#tgl_ambil_antrian').val();
		var v_tgl_panggil_antrian = $('#tgl_panggil_antrian').val();
		var v_kode_petugas_antrian = $('#kode_petugas_antrian').val();
		var v_nomor_identitas = $('#nomor_identitas').val();
		var v_no_hp_antrian = $('#no_hp_antrian').val();
		var v_email_antrian = $('#email_antrian').val();
		var v_kode_jenis_antrian_detil = "SA01KJP01";
		var v_base64_foto_peserta = $('#base64_foto_peserta').val();
		var v_path_url_antrian_curr = $('#path_url_antrian_curr').val();
		// End Parameter Antrian ------------------------------------------------------------

		var c_rg_kondisi = $("input[name='rg_kondisi_terakhir_induk']:checked").val();
		if (c_rg_kondisi != "A" && c_rg_kondisi != "B") {
			c_rg_kondisi = "X";
		}

		if (c_kode_klaim == "" || c_no_konfirmasi_induk == "") {
			alert('Data JP Berkala yang dikonfirmasi tidak boleh kosong...!!!');
		} else if (c_rg_kondisi == "X") {
			alert('Kondisi Terakhir Penerima Manfaat belum dipilih, harap lengkapi data input...!!!');
		} else if (v_token_sisla != "" && v_no_hp_antrian == "") {
			alert('No Handphone Peserta Antrian tidak boleh kosong...!!!');
		} else if (v_token_sisla != "" && v_email_antrian == "") {
			alert('Email Peserta Antrian tidak boleh kosong...!!!');
		} else if (v_token_sisla != "" && (v_base64_foto_peserta == "" && v_path_url_antrian_curr == "")) {
			alert('Foto Peserta Antrian tidak boleh kosong...!!!');
		} else if ((c_rg_kondisi == "B") && (c_kode_kondisi_terakhir_induk == "" || c_tgl_kondisi_terakhir_induk == "")) {
			alert('Untuk pilhan Ada Perubahan Status (Menikah/Usia23,dll) maka Kondisi akhir dan tgl kondisi harus diinput keduanya...!!!');
			$('#kode_kondisi_terakhir_induk').focus();
		} else {
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_il/ajax/il1001_entry_action.php?' + Math.random(),
				data: {
					"TYPE": "getValidNikAntrian",
					"nomor_identitas": v_nomor_identitas,
					"kode_jenis_antrian": 'SA01',
					"kode_jenis_antrian_detil": v_kode_jenis_antrian_detil
				},
				success: function(data) {
					var jdata = JSON.parse(data);
					//console.log(data);
					if (jdata.ret == '-1') {
						alert('Peserta sudah pernah dilayani oleh Kantor Cabang ' + toUnicodeVariant('' + jdata.kantorLayanan + '', 'bold sans', 'bold') + ' pada tanggal ' + toUnicodeVariant('' + jdata.tglLayanan + '', 'bold sans', 'bold') + ' dan saat ini berstatus Tunda Layanan, mohon untuk memastikan ke Kantor Cabang ' + toUnicodeVariant('' + jdata.kantorLayanan + '', 'bold sans', 'bold') + ' terkait tindak lanjut layanan dan token terbaru dapat dibatalkan jika proses layanan tetap dilakukan oleh Kantor Cabang ' + toUnicodeVariant('' + jdata.kantorLayanan + '', 'bold sans', 'bold') + '');
					} else {
						$.ajax({
							type: 'POST',
							url: "../ajax/pn5065_action.php?" + Math.random(),
							data: {
								tipe: 'fjq_ajax_insert_antrian',
								v_kode_klaim: c_kode_klaim,
								v_token_antrian: v_token_sisla,
								v_kode_jenis_antrian: v_kode_jenis_antrian,
								v_kode_status_antrian: v_kode_status_antrian,
								v_kode_sisla: v_kode_sisla,
								v_kode_kantor_antrian: v_kode_kantor_antrian,
								v_nomor_antrian: v_nomor_antrian,
								v_tgl_ambil_antrian: v_tgl_ambil_antrian,
								v_tgl_panggil_antrian: v_tgl_panggil_antrian,
								v_kode_petugas_antrian: v_kode_petugas_antrian,
								v_no_hp_antrian: v_no_hp_antrian,
								v_email_antrian: v_email_antrian,
								v_nomor_identitas: v_nomor_identitas,
								v_kode_jenis_antrian_detil: v_kode_jenis_antrian_detil
							},
							success: function(data) {
								var jdata = JSON.parse(data);
								console.log(data);
								if (jdata.ret == '0') {
									f_js_upload_foto_wajah(jdata.kode_antrian);
								} else {}
							},
							error: function() {},
							complete: function() {
								//preload(false);
							}
						});

						NewWindow('../ajax/pn5065_submit.php?kode_klaim=' + c_kode_klaim + '&no_konfirmasi_induk=' + c_no_konfirmasi_induk + '&rg_kondisi=' + c_rg_kondisi + '&kode_kondisi_terakhir_induk=' + c_kode_kondisi_terakhir_induk + '&tgl_kondisi_terakhir_induk=' + c_tgl_kondisi_terakhir_induk + '&sender_mid=' + c_sender_mid + '', '', 300, 50, 'no');
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

	function fl_js_reload_post_submit(v_kode_klaim, v_no_konfirmasi) {
		if (v_kode_klaim != '' && v_no_konfirmasi != '') {
			document.formreg.task.value = 'edit';
			document.formreg.kode_klaim.value = v_kode_klaim;
			document.formreg.no_konfirmasi.value = v_no_konfirmasi;
			document.formreg.task_detil.value = '';
			try {
				document.formreg.onsubmit();
			} catch (e) {}
			document.formreg.submit();
		}
	}
	//-------------------------- END NEW -----------------------------------------			
</script>

<script type="text/javascript">
	var curr_cur_kode_bank_penerima = <?php echo ($ls_cur_kode_bank_penerima == '') ? 'false' : "'" . $ls_cur_kode_bank_penerima . "'"; ?>;
	var curr_cur_no_rekening_penerima = <?php echo ($ls_cur_no_rekening_penerima == '') ? 'false' : "'" . $ls_cur_no_rekening_penerima . "'"; ?>;
</script>

<script language="javascript">
	//--------------------------- EDIT -------------------------------------------
	function loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, fn) {
		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			return alert('Data Konfimasi JP Berkala tidak ditemukan');
		}

		v_task = $('#task').val();

		//load data konfirmasi jp berkala ------------------------------------------
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5065_action.php?" + Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatakonfirmasijpberkala',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi: v_no_konfirmasi
			},
			success: function(data) {
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) {
						$("#span_page_title_right").html('NO. KONFIRMASI : ' + v_kode_klaim + '-' + v_no_konfirmasi);
						$('#no_penetapan').val(getValue(jdata.data.dataKonf['NO_PENETAPAN']));
						$('#no_konfirmasi_induk').val(getValue(jdata.data.dataKonf['NO_KONFIRMASI_INDUK']));
						$('#cnt_berkala_detil').val(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL']));

						//set Kontak Darurat
						$('#inkd_nama_lengkap').val(jdata.data.dataKontakDarurat['NAMA_KONTAK_DARURAT']);
						$('#inkd_no_hp').val(jdata.data.dataKontakDarurat['NO_HP_KONTAK_DARURAT']);
						$('#inkd_alamat').val(jdata.data.dataKontakDarurat['ALAMAT_KONTAK_DARURAT']);
						$('#inkd_hub_keluarga').val(jdata.data.dataKontakDarurat['KODE_HUBUNGAN_KONTAK_DARURAT']);
						$('select').select2();

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
								html_data_Dok += '<td style="text-align: center;">' +
									'<a href="javascript:void(0)" onclick="fl_js_UploadDok(\'' +
									getValue(jdata.data.dataDok[i].KODE_KLAIM) + '\', \'' +
									getValue(jdata.data.dataDok[i].NO_KONFIRMASI) + '\', \'' +
									getValue(jdata.data.dataDok[i].KODE_DOKUMEN) + '\')"><img alt="img" src="../../images/uploadx.png" border="0" alt="Tambah" align="absmiddle" style="height:15px;"> upload</a>' + '</td>';
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

						//set apakah flag layanan sms berstatus aktif atau tidak -----------
						var v_flag_aktif_lynsms = getValue(jdata.data.dataFlagLynSms[0]['SEQ']);
						$('#flag_aktif_lynsms').val(v_flag_aktif_lynsms);

						//set footer keterangan --------------------------------------------
						if (v_task == 'edit') {
							if (parseInt(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL'])) > 0) {
								if (v_flag_aktif_lynsms == "1") {
									$("#span_footer_keterangan_edit").html('<div class="div-footer-content" style="width:95%;"><div style="padding-bottom: 8px;"><b>Keterangan:</b></div><li style="margin-left:15px;">Lengkapi <span style="color:#ff0000"> Informasi Penerima Manfaat JP Berkala </span>. Untuk <span style="color:#ff0000">Rekening Penerima Manfaat</span> sebaiknya diisikan dengan nomor rekening yang valid untuk menghindari gagal transfer pada saat pembayaran manfaat.</li><li style="margin-left:15px;">Setelah informasi lengkap, klik <span style="color:#ff0000"> SIMPAN PERUBAHAN </span> agar data yang sudah diupdate dapat tersimpan. Lanjutkan dengan melakukan upload <span style="color:#ff0000"> Dokumen Kelengkapan Administrasi </span>.</li><li style="margin-left:15px;">Harap melakukan konfirmasi apakah penerima manfaat bersedia untuk menerima <span style="color:#ff0000"> LAYANAN SMS </span> konfirmasi JP Berkala.</li><li style="margin-left:15px;">Klik tombol <span style="color:#ff0000"> SUBMIT DATA KONFIRMASI </span> untuk dilanjutkan ke tahap persetujuan.</li><li style="margin-left:15px;">Apabila terjadi kesalahan data konfirmasi maka data dapat dibatalkan dengan mengklik tombol <span style="color:#ff0000"> BATALKAN DATA KONFIRMASI </span>.</li></div>');
								} else {
									$("#span_footer_keterangan_edit").html('<div class="div-footer-content" style="width:95%;"><div style="padding-bottom: 8px;"><b>Keterangan:</b></div><li style="margin-left:15px;">Lengkapi <span style="color:#ff0000"> Informasi Penerima Manfaat JP Berkala </span>. Untuk <span style="color:#ff0000">Rekening Penerima Manfaat</span> sebaiknya diisikan dengan nomor rekening yang valid untuk menghindari gagal transfer pada saat pembayaran manfaat.</li><li style="margin-left:15px;">Setelah informasi lengkap, klik <span style="color:#ff0000"> SIMPAN PERUBAHAN </span> agar data yang sudah diupdate dapat tersimpan. Lanjutkan dengan melakukan upload <span style="color:#ff0000"> Dokumen Kelengkapan Administrasi </span>.</li><li style="margin-left:15px;">Klik tombol <span style="color:#ff0000"> SUBMIT DATA KONFIRMASI </span> untuk dilanjutkan ke tahap persetujuan.</li><li style="margin-left:15px;">Apabila terjadi kesalahan data konfirmasi maka data dapat dibatalkan dengan mengklik tombol <span style="color:#ff0000"> BATALKAN DATA KONFIRMASI </span>.</li></div>');
								}
							} else {
								$("#span_footer_keterangan_edit").html('<div class="div-footer-content" style="width:95%;"><div style="padding-bottom: 8px;"><b>Keterangan:</b></div><li style="margin-left:15px;">Klik tombol <span style="color:#ff0000"> SUBMIT DATA KONFIRMASI </span> untuk dilanjutkan ke tahap persetujuan.</li><li style="margin-left:15px;">Apabila terjadi kesalahan data konfirmasi maka data dapat dibatalkan dengan mengklik tombol <span style="color:#ff0000"> BATALKAN DATA KONFIRMASI </span>.</li></div>');
							}
						} else {
							$("#span_footer_keterangan_edit").html('');
						}
						//end set footer keterangan ----------------------------------------

						//set button -------------------------------------------------------
						if (v_task == 'edit' && getValue(jdata.data.dataKonf['STATUS_SUBMIT']) != 'Y') {
							window.document.getElementById("span_button_edit").style.display = 'block';

							//jika tidak ada manfaat turunan ---------------------------------
							if (parseInt(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL'])) > 0) {
								window.document.getElementById("btn_doSimpanPerubahan").style.display = '';
								//cek apakah layanan sms sudah aktif atau belum ----------------
								if (v_flag_aktif_lynsms == "1") {
									window.document.getElementById("btn_doLayananSms").style.display = '';
								} else {
									window.document.getElementById("btn_doLayananSms").style.display = 'none';
								}
							} else {
								window.document.getElementById("btn_doSimpanPerubahan").style.display = 'none';
								window.document.getElementById("btn_doLayananSms").style.display = 'none';
							}
							//end jika tidak ada manfaat turunan -----------------------------
						} else {
							window.document.getElementById("span_button_edit").style.display = 'none';
						}
						//end set button ---------------------------------------------------

						if (fn && fn.success) {
							fn.success();
						}
					} else {
						alert(jdata.msg);
					}
				} catch (e) {
					console.log(e);
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

	function fl_js_UploadDok(v_kode_klaim, v_no_konfirmasi, v_kode_dokumen) {
		let params = 'p=pn5065.php&a=formreg&kode_klaim=' + v_kode_klaim + '&no_konfirmasi=' + v_no_konfirmasi + '&kode_dokumen=' + v_kode_dokumen + '&sender=pn5065.php';
		NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_uploadlampirandokumen.php?' + params, 'upload_lampiran', 550, 400, 'yes');
	}

	function fl_js_DownloadDok(v_url, v_nmfile) {
		let p = btoa(v_url);
		let f = btoa(v_nmfile);
		let u = btoa('<?= $gs_kode_user; ?>');
		let params = 'p=' + p + '&f=' + f + '&u=' + u;
		NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_download_dok.php?' + params, '', 1000, 620, 'no');
	}

	function fl_js_ShowformNotifikasiSMS() {
		v_kode_klaim = $('#kode_klaim').val();
		v_no_urut_keluarga = $('#cur_no_urut_keluarga').val();
		v_mid = $('#mid').val();

		let params = 'kode_klaim=' + v_kode_klaim + '&no_urut_keluarga=' + v_no_urut_keluarga + '&kode_sumber=pn5006&root_sender=pn5065.php&sender=pn5065.php&sender_mid=' + v_mid + '&sender_activetab=2';
		NewWindow('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5038_notifikasi_jpn_berkala.php?task=View&' + params, '', 1000, 620, 'no');
	}

	function fl_js_reset_norek() {
		$('#cur_no_rekening_penerima').val('');
		$('#cur_nama_rekening_penerima_ws').val('');
		$('#cur_nama_rekening_penerima').val('');
		$('#cur_st_valid_rekening_penerima').val('T');

		var curr_cur_no_rekening_penerima = '';
		$('#cur_no_rekening_penerima').focus();
		window.document.getElementById('cb_cur_valid_rekening').checked = false;
	}

	//validasi nomor rekening penerima -------------------------------------------
	function fjq_ajax_val_cur_no_rekening_penerima() {
		var v_kode_bank_tujuan = $('#cur_kode_bank_penerima').val();
		var v_nama_bank_tujuan = $('#cur_bank_penerima').val();
		var v_no_rek_tujuan = $('#cur_no_rekening_penerima').val();

		if (v_kode_bank_tujuan != curr_cur_kode_bank_penerima || v_no_rek_tujuan != curr_cur_no_rekening_penerima) {
			if (v_kode_bank_tujuan != '' && v_no_rek_tujuan != '') {
				preload(true);
				$.ajax({
					type: 'POST',
					url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5063_action.php?' + Math.random(),
					data: {
						tipe: 'validate_rekening_tujuan',
						NO_REK_TUJUAN: v_no_rek_tujuan,
						KODE_BANK_ATB_TUJUAN: v_kode_bank_tujuan,
						NAMA_BANK_TUJUAN: v_nama_bank_tujuan
					},
					success: function(data) {
						preload(false);
						jdata = JSON.parse(data);
						if (jdata.ret == "0") {
							$('#cur_nama_rekening_penerima_ws').val(jdata.data['NAMA_REK_TUJUAN']);
							$('#cur_nama_rekening_penerima').val(jdata.data['NAMA_REK_TUJUAN']);
							document.getElementById('cur_nama_rekening_penerima_ws').readOnly = true;
							document.getElementById('cur_nama_rekening_penerima_ws').style.backgroundColor = '#F5F5F5';

							window.document.getElementById('cb_cur_valid_rekening').checked = true;
							$('#cur_st_valid_rekening_penerima').val('Y');

							curr_cur_kode_bank_penerima = v_kode_bank_tujuan;
							curr_cur_no_rekening_penerima = v_no_rek_tujuan;

						} else {
							//nama_rekening dapat diinput manual (bypass) ------------
							$('#cur_nama_rekening_penerima_ws').val('');
							document.getElementById('cur_nama_rekening_penerima_ws').readOnly = false;
							document.getElementById('cur_nama_rekening_penerima_ws').style.backgroundColor = '#ffff99';
							document.getElementById('cur_nama_rekening_penerima_ws').placeholder = "-- isikan NAMA secara manual --";

							window.document.getElementById('cb_cur_valid_rekening').checked = false;
							$('#cur_st_valid_rekening_penerima').val('T');
							$('#cur_nama_rekening_penerima_ws').focus();

							curr_cur_kode_bank_penerima = v_kode_bank_tujuan;
							curr_cur_no_rekening_penerima = v_no_rek_tujuan;
							alert('Gagal validasi rekening,' + jdata.msg);
						}
					}
				});
			} else {
				$('#cur_nama_rekening_penerima_ws').val('');
				window.document.getElementById('cb_cur_valid_rekening').checked = false;
				$('#cur_st_valid_rekening_penerima').val('T');
				$('#cur_no_rekening_penerima').focus();
				curr_cur_kode_bank_penerima = v_kode_bank_tujuan;
				curr_cur_no_rekening_penerima = v_no_rek_tujuan;
			}
		}
	}
	//end validasi nomor rekening penerima ---------------------------------------

	function fjq_ajax_val_getlist_bank_asal() {
		//dipanggil saat pilih bank penerima -------		 
	}

	$(document).ready(function() {
		$('select').select2();
	});
	//end ambil bank pembayar ----------------------------------------------------

	// ------------------------------ BUTTON TASK --------------------------------
	//do simpan perubahan data konfirmasi jp berkala -----------------------------
	function fjq_ajax_val_save_konfirmasi_berkala() {
		var v_kode_klaim = $('#kode_klaim').val();
		var v_no_konfirmasi = $('#no_konfirmasi').val();
		var v_no_urut_keluarga = $('#cur_no_urut_keluarga').val();
		var v_nomor_identitas = $('#cur_nomor_identitas').val();
		var v_alamat = $('#cur_alamat').val();
		var v_rt = $('#cur_rt').val();
		var v_rw = $('#cur_rw').val();
		var v_kode_pos = $('#cur_kode_pos').val();
		var v_kode_kelurahan = $('#cur_kode_kelurahan').val();
		var v_kode_kecamatan = $('#cur_kode_kecamatan').val();
		var v_kode_kabupaten = $('#cur_kode_kabupaten').val();
		var v_telepon_area = $('#cur_telepon_area').val();
		var v_telepon = $('#cur_telepon').val();
		var v_telepon_ext = $('#cur_telepon_ext').val();
		var v_handphone = $('#cur_handphone').val();
		var v_email = $('#cur_email').val();
		var v_npwp = $('#cur_npwp').val();
		var v_nomor_identitas = $('#cur_nomor_identitas').val();
		var v_nama_penerima = $('#cur_nama_lengkap').val();
		var v_nama_bank_penerima = $('#cur_bank_penerima').val();
		var v_kode_bank_penerima = $('#cur_kode_bank_penerima').val();
		var v_id_bank_penerima = $('#cur_id_bank_penerima').val();
		var v_no_rekening_penerima = $('#cur_no_rekening_penerima').val();
		var v_nama_rekening_penerima = $('#cur_nama_rekening_penerima_ws').val();
		var v_st_valid_rekening_penerima = $('#cur_st_valid_rekening_penerima').val();
		var v_kode_bank_pembayar = $('#cur_kode_bank_pembayar').val();
		var v_status_rekening_sentral = $('#cur_status_rekening_sentral').val();
		var v_kantor_rekening_sentral = $('#cur_kantor_rekening_sentral').val();
		var v_metode_transfer = $('#cur_metode_transfer').val();
		var v_cnt_berkala_detil = parseInt(removeCommas($('#cnt_berkala_detil').val()));

		//Kontak Darurat
		var v_inkd_nama_lengkap = $('#inkd_nama_lengkap').val();
		var v_inkd_no_hp = $('#inkd_no_hp').val();
		var v_inkd_alamat = $('#inkd_alamat').val();
		var v_inkd_hub_keluarga = $('#inkd_hub_keluarga').val();
		var v_ind_kode_penerima_berkala = $('#ind_kode_penerima_berkala').val();


		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			alert('Data Konfirmasi JP Berkala tidak ditemukan, harap perhatikan data input..!!!');
		} else if ((v_cnt_berkala_detil > 0) && (v_alamat == '' || v_kode_pos == '' || v_npwp == '' || v_nama_bank_penerima == '' || v_no_rekening_penerima == '')) {
			if (v_alamat == '') {
				alert('Alamat penerima manfaat kosong, harap melengkapi data input..!');
			} else if (v_kode_pos == '') {
				alert('Kode pos penerima manfaat kosong, harap melengkapi data input..!');
			} else if (v_npwp == '') {
				alert('NPWP penerima manfaat kosong, isikan 0 jika memang tidak ada NPWP..!');
			} else if (v_nama_bank_penerima == '') {
				alert('Bank rekening penerima manfaat kosong, harap melengkapi data input..!');
			} else if (v_no_rekening_penerima == '') {
				alert('No rekening penerima manfaat kosong, harap melengkapi data input..!');
			}
		} else if ((v_cnt_berkala_detil > 0) && (v_nama_bank_penerima != '' && v_kode_bank_penerima == '')) {
			alert('Kode Bank dari rekening penerima manfaat masih kosong, harap pilih ulang Rekening Bank dan isikan kembali nomor rekening ..!');
		} else {
			preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_action.php?' + Math.random(),
				data: {
					tipe: 'fjq_ajax_val_save_konfirmasi_berkala',
					v_kode_klaim: v_kode_klaim,
					v_no_konfirmasi: v_no_konfirmasi,
					v_no_urut_keluarga: v_no_urut_keluarga,
					v_nomor_identitas: v_nomor_identitas,
					v_alamat: v_alamat,
					v_rt: v_rt,
					v_rw: v_rw,
					v_kode_pos: v_kode_pos,
					v_kode_kelurahan: v_kode_kelurahan,
					v_kode_kecamatan: v_kode_kecamatan,
					v_kode_kabupaten: v_kode_kabupaten,
					v_telepon_area: v_telepon_area,
					v_telepon: v_telepon,
					v_telepon_ext: v_telepon_ext,
					v_handphone: v_handphone,
					v_email: v_email,
					v_npwp: v_npwp,
					v_nomor_identitas: v_nomor_identitas,
					v_nama_penerima: v_nama_penerima,
					v_nama_bank_penerima: v_nama_bank_penerima,
					v_kode_bank_penerima: v_kode_bank_penerima,
					v_id_bank_penerima: v_id_bank_penerima,
					v_no_rekening_penerima: v_no_rekening_penerima,
					v_nama_rekening_penerima: v_nama_rekening_penerima,
					v_st_valid_rekening_penerima: v_st_valid_rekening_penerima,
					v_kode_bank_pembayar: v_kode_bank_pembayar,
					v_status_rekening_sentral: v_status_rekening_sentral,
					v_kantor_rekening_sentral: v_kantor_rekening_sentral,
					v_metode_transfer: v_metode_transfer,

					v_inkd_nama_lengkap: v_inkd_nama_lengkap,
					v_inkd_no_hp: v_inkd_no_hp,
					v_inkd_alamat: v_inkd_alamat,
					v_inkd_hub_keluarga: v_inkd_hub_keluarga,
					v_ind_kode_penerima_berkala: v_ind_kode_penerima_berkala
				},
				success: function(data) {
					preload(false);
					jdata = JSON.parse(data);
					if (jdata.ret == "0") {
						//simpan ubah konfirmasi berkala berhasil, reload form -------------
						alert(jdata.msg);
						reloadFormUtama();
					} else {
						//simpan ubah konfirmasi berkala gagal -----------------------------
						alert(jdata.msg);
					}
				}
			}); //end ajax
		} //end if
	}
	//end do simpan perubahan data penerima manfaat ------------------------------

	//do submit konfirmasi data konfirmasi jp berkala ----------------------------
	function fjq_ajax_val_submit_konfirmasi_berkala() {
		var v_kode_klaim = $('#kode_klaim').val();
		var v_no_konfirmasi = $('#no_konfirmasi').val();
		var v_no_urut_keluarga = $('#cur_no_urut_keluarga').val();
		var v_alamat = $('#cur_alamat').val();
		var v_rt = $('#cur_rt').val();
		var v_rw = $('#cur_rw').val();
		var v_kode_pos = $('#cur_kode_pos').val();
		var v_kode_kelurahan = $('#cur_kode_kelurahan').val();
		var v_kode_kecamatan = $('#cur_kode_kecamatan').val();
		var v_kode_kabupaten = $('#cur_kode_kabupaten').val();
		var v_telepon_area = $('#cur_telepon_area').val();
		var v_telepon = $('#cur_telepon').val();
		var v_telepon_ext = $('#cur_telepon_ext').val();
		var v_handphone = $('#cur_handphone').val();
		var v_email = $('#cur_email').val();
		var v_npwp = $('#cur_npwp').val();
		var v_nomor_identitas = $('#cur_nomor_identitas').val();
		var v_nama_penerima = $('#cur_nama_lengkap').val();
		var v_nama_bank_penerima = $('#cur_bank_penerima').val();
		var v_kode_bank_penerima = $('#cur_kode_bank_penerima').val();
		var v_id_bank_penerima = $('#cur_id_bank_penerima').val();
		var v_no_rekening_penerima = $('#cur_no_rekening_penerima').val();
		var v_nama_rekening_penerima = $('#cur_nama_rekening_penerima_ws').val();
		var v_st_valid_rekening_penerima = $('#cur_st_valid_rekening_penerima').val();
		var v_kode_bank_pembayar = $('#cur_kode_bank_pembayar').val();
		var v_status_rekening_sentral = $('#cur_status_rekening_sentral').val();
		var v_kantor_rekening_sentral = $('#cur_kantor_rekening_sentral').val();
		var v_metode_transfer = $('#cur_metode_transfer').val();
		var v_cnt_berkala_detil = parseInt(removeCommas($('#cnt_berkala_detil').val()));
		var v_status_cek_layanan = $('#cur_status_cek_layanan').val();
		var v_flag_aktif_lynsms = $('#flag_aktif_lynsms').val();

		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			alert('Data Konfirmasi JP Berkala tidak ditemukan, harap perhatikan data input..!!!');
		} else if ((v_cnt_berkala_detil > 0) && (v_alamat == '' || v_kode_pos == '' || v_npwp == '' || v_nama_bank_penerima == '' || v_no_rekening_penerima == '' || (v_flag_aktif_lynsms == '1' && v_status_cek_layanan != 'Y'))) {
			if (v_alamat == '') {
				alert('Alamat penerima manfaat kosong, harap melengkapi data input kemudian klik tombol SIMPAN PERUBAHAN..!');
			} else if (v_kode_pos == '') {
				alert('Kode pos penerima manfaat kosong, harap melengkapi data input kemudian klik tombol SIMPAN PERUBAHAN..!');
			} else if (v_npwp == '') {
				alert('NPWP penerima manfaat kosong, isikan 0 jika memang tidak ada NPWP kemudian klik tombol SIMPAN PERUBAHAN..!');
			} else if (v_nama_bank_penerima == '') {
				alert('Bank rekening penerima manfaat kosong, harap melengkapi data input kemudian klik tombol SIMPAN PERUBAHAN..!');
			} else if (v_no_rekening_penerima == '') {
				alert('No rekening penerima manfaat kosong, harap melengkapi data input kemudian klik tombol SIMPAN PERUBAHAN..!');
			} else if (v_flag_aktif_lynsms == '1' && v_status_cek_layanan != 'Y') {
				alert('Harap klik tombol LAYANAN SMS terlebih dahulu sebelum data konfirmasi JP Berkala dapat disubmit..!');
			}
		} else if ((v_cnt_berkala_detil > 0) && (v_nama_bank_penerima != '' && v_kode_bank_penerima == '')) {
			alert('Kode Bank dari rekening penerima manfat masih kosong, harap pilih ulang Rekening Bank dan isikan kembali nomor rekening kemudian klik tombol SIMPAN PERUBAHAN ..!');
		} else {
			preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_action.php?' + Math.random(),
				data: {
					tipe: 'fjq_ajax_val_submit_konfirmasi_berkala',
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
	//end do submit konfirmasi data konfirmasi jp berkala ------------------------

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
				url: 'http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_action.php?' + Math.random(),
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

	function fl_js_doCetakKonfirmasi() {
		document.formreg.task_detil.value = 'doCetakKonfirmasi';
		try {
			document.formreg.onsubmit();
		} catch (e) {}
		document.formreg.submit();
	}
	//--------------------------- END EDIT ---------------------------------------
</script>

<!-- add select2 css & javascript -->
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>
<script language="javascript">
	$(document).ready(function() {
		$('select').select2();
	});

	function toUnicodeVariant(str, variant, flags) {
		const offsets = {
			m: [0x1d670, 0x1d7f6],
			b: [0x1d400, 0x1d7ce],
			i: [0x1d434, 0x00030],
			bi: [0x1d468, 0x00030],
			c: [0x1d49c, 0x00030],
			bc: [0x1d4d0, 0x00030],
			g: [0x1d504, 0x00030],
			d: [0x1d538, 0x1d7d8],
			bg: [0x1d56c, 0x00030],
			s: [0x1d5a0, 0x1d7e2],
			bs: [0x1d5d4, 0x1d7ec],
			is: [0x1d608, 0x00030],
			bis: [0x1d63c, 0x00030],
			o: [0x24B6, 0x2460],
			p: [0x249C, 0x2474],
			w: [0xff21, 0xff10],
			u: [0x2090, 0xff10]
		}

		const variantOffsets = {
			'monospace': 'm',
			'bold': 'b',
			'italic': 'i',
			'bold italic': 'bi',
			'script': 'c',
			'bold script': 'bc',
			'gothic': 'g',
			'gothic bold': 'bg',
			'doublestruck': 'd',
			'sans': 's',
			'bold sans': 'bs',
			'italic sans': 'is',
			'bold italic sans': 'bis',
			'parenthesis': 'p',
			'circled': 'o',
			'fullwidth': 'w'
		}

		// special characters (absolute values)
		var special = {
			m: {
				' ': 0x2000,
				'-': 0x2013
			},
			i: {
				'h': 0x210e
			},
			g: {
				'C': 0x212d,
				'H': 0x210c,
				'I': 0x2111,
				'R': 0x211c,
				'Z': 0x2128
			},
			o: {
				'0': 0x24EA,
				'1': 0x2460,
				'2': 0x2461,
				'3': 0x2462,
				'4': 0x2463,
				'5': 0x2464,
				'6': 0x2465,
				'7': 0x2466,
				'8': 0x2467,
				'9': 0x2468,
			},
			p: {},
			w: {}
		}
		//support for parenthesized latin letters small cases 
		for (var i = 97; i <= 122; i++) {
			special.p[String.fromCharCode(i)] = 0x249C + (i - 97)
		}
		//support for full width latin letters small cases 
		for (var i = 97; i <= 122; i++) {
			special.w[String.fromCharCode(i)] = 0xff41 + (i - 97)
		}

		const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
		const numbers = '0123456789';

		var getType = function(variant) {
			if (variantOffsets[variant]) return variantOffsets[variant]
			if (offsets[variant]) return variant;
			return 'm'; //monospace as default
		}
		var getFlag = function(flag, flags) {
			if (!flags) return false
			return flags.split(',').indexOf(flag) > -1
		}

		var type = getType(variant);
		var underline = getFlag('underline', flags);
		var strike = getFlag('strike', flags);
		var result = '';

		for (var k of str) {
			let index
			let c = k
			if (special[type] && special[type][c]) c = String.fromCodePoint(special[type][c])
			if (type && (index = chars.indexOf(c)) > -1) {
				result += String.fromCodePoint(index + offsets[type][0])
			} else if (type && (index = numbers.indexOf(c)) > -1) {
				result += String.fromCodePoint(index + offsets[type][1])
			} else {
				result += c
			}
			if (underline) result += '\u0332' // add combining underline
			if (strike) result += '\u0336' // add combining strike
		}
		return result
	}
</script>

<!-- end add select2 css & javascript -->