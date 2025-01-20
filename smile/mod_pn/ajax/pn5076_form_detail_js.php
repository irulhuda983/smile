<!-- js async loader starts here -->
<script type="text/javascript">	
  let _asyncAwaitFn = [];
  let _asyncPreloadRunning = [];
  let _asyncPreload;
  
  window.asyncPreload = function(idloader, state) {
    idloader = idloader || 'data';
    if (state) {
      _asyncPreloadRunning[idloader] = state;
    } else {
      delete _asyncPreloadRunning[idloader];
    }
  }

  window.asyncPreloadStart = function() {
    _asyncPreload= setInterval(function () {
			try {
				if (_asyncPreloadRunning) {
					if (Object.keys(_asyncPreloadRunning).length > 0) {
						preload(true);
					} else {
						let fn = _asyncAwaitFn.shift();
						if (fn && typeof fn === 'function') {
							fn();
						} else {
							preload(false);
						}
					}
				}
			} catch(e) {
				window.asyncPreloadEnd();
			}
    }, 100)
  }

  window.asyncPreloadEnd = function () {
    preload(false);
    clearInterval(_asyncPreload);
  }

	window.asyncAwaitFn = function (fn) {
		_asyncAwaitFn.push(fn);
	}

  window.asyncPreloadStart();
</script>
<!-- js async loader ends here -->

<!-- js common function starts here -->
<script language="javascript">
	function getValue(val) {
		return val == null || val == undefined ? '' : val;
	}

	function resubmit(formName = 'formreg') {
		$(`#${formName}`).submit();
	}


	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title	: title,
			msg		: msg,
			buttons	: window.parent.Ext.Msg.YESNO,
			icon	: window.parent.Ext.Msg.QUESTION,
			fn: function (btn) {
				if (btn === 'yes') {
					fnYes();
				} else {
					fnNo();
				}
			}
		});
	}

</script>
<!-- js common function endS here -->

<!-- js function starts here -->
<script language="javascript">
	// untuk datatable
	let active_window 			= window.parent.Ext.WindowManager.getActive();
	let active_window_height 	= active_window ? active_window.height : 0;
	if (active_window) {
		active_window.on('resize', function () {
			let default_height_table 		= Number($('#div-container-table').attr('data-height'));
			let active_window_height_now 	= Number(active_window.height);
			let active_window_height_diff 	= Number(active_window_height_now) - Number(active_window_height);

			if (active_window.maximized) {
				$('#div-container-table').css('height', default_height_table);
			} else {
				$('#div-container-table').css('height', default_height_table + active_window_height_diff);
			}
		});
	}

	function arrayRemove(arr, value) {
		return arr.filter(function(ele) {
				return ele != value;
		});
	}

	function renumbered() {
		$('#data_list > tr').each(function(index, tr) { 
			let idx_tr = $(tr).attr('idx');
			$('#no_tr' + idx_tr).html(index + 1);
		});
	}

</script>
<!-- js function ends here -->

<!-- js onready ends here -->
<script language="javascript">
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

		let ls_kode_klaim = '<?= $ls_kode_klaim ?>';
		let ls_nomor_identitas = '<?= $ls_nomor_identitas ?>';
		let ls_jenis_data = '<?= $ls_jenis_data ?>';

		setTimeout(function() {
			filter(ls_kode_klaim);
		}, 500);

		$("#flag_disclaimer").change(function() {
			validasiFlagDisclaimer();
		});
		
		function validasiFlagDisclaimer() {
			if ($("#flag_disclaimer").is(":checked")) {
				$("#btn_submit").prop("disabled", false);
			} else {
				$("#btn_submit").prop("disabled", true);
			}
		}

		validasiFlagDisclaimer();
	});

	// global variable
	let url = "<?=$formAjax?>" + '?' + Math.random();
	var statusSubmit = "";

	function getValue(val){
		return val == null ? '' : val;
	}

	function getData(sc_kode_klaim) {
		let kode_agenda_koreksi = <?= "'" . $ls_kode_agenda_koreksi . "'" ?>;
		let no_level = <?= "'" . $ls_no_level . "'" ?>;
		let kode_jabatan = <?= "'" . $ls_kode_jabatan . "'" ?>;
		let user = <?= "'" . $USER . "'" ?>;
		let ls_jenis_data = '<?= $ls_jenis_data ?>';

		$.ajax({
			type: 'POST',
			url: url,
			data: {
				tipe				: 'form_detil',
				kode_klaim			: sc_kode_klaim,
				kode_agenda_koreksi : kode_agenda_koreksi,
				kode_jabatan		: kode_jabatan,
				no_level			: no_level
			},
			success: function(data){
				var jdata = JSON.parse(data);					
				var data = jdata.data;
				var dataApproval = jdata.dataApproval;
				var namaBankPembayar = jdata.namaBankPembayar;
				var namaBankPembayarBaru = jdata.namaBankPembayarBaru;
				var ket = jdata.ket;

				if (ket == "sudah" || ls_jenis_data == "sudah") {
					$("#statusPersetujuan").prop("disabled", true);
					$("#keteranganPersetujuan").prop("disabled", true);
					$(".flag_disclaimer, .btn_submit").hide();
					$("#btn_kembali").css("margin-left", "155px");
					$("#informasi, #hidden").hide();			

					// set data
					dataApproval.map(function(row) {
						if (row.PETUGAS_APPROVAL == user && row.KETERANGAN_APPROVAL && row.STATUS_APPROVAL && row.STATUS_APPROVAL != 'T') {
							$("#statusPersetujuan").val(row.STATUS_APPROVAL);
							$("#keteranganPersetujuan").val(row.KETERANGAN_APPROVAL);
						} else if (row.KETERANGAN_APPROVAL && row.STATUS_APPROVAL && row.STATUS_APPROVAL != 'T') {
							$("#statusPersetujuan").val(row.STATUS_APPROVAL);
							$("#keteranganPersetujuan").val(row.KETERANGAN_APPROVAL);
						}
					});
					//
				}

				if (jdata.ret == 0) {
					$('#kodeKlaim').val(getValue(data.KODE_KLAIM))
					$('#nik').val(getValue(data.NOMOR_IDENTITAS))
					$('#namaPeserta').val(getValue(data.NAMA_TK))
					$('#kpj').val(getValue(data.KPJ))
					$('#tglKlaim').val(convertDate((data.TGL_KLAIM)))
					$('#namaPenerimaManfaat').val(getValue((data.NAMA_PENERIMA_MANFAAT)))
					$('#nominalManfaat').val(formatAngka(getValue(data.NOM_MANFAAT)))
					$('#keteranganRetur').val(getValue(data.KETERANGAN_RETUR))
					$('#kodeBank').val(getValue(data.KODE_BANK_PENERIMA))
					$('#namaBank').val(getValue(data.BANK_PENERIMA))
					$('#noRek').val(getValue(data.NO_REKENING_PENERIMA))
					$('#namaRek').val(getValue(data.NAMA_REKENING_PENERIMA))
					$('#kodeBank2').val(getValue(data.KODE_BANK_PENERIMA_BARU))
					$('#namaBank2').val(getValue(data.BANK_PENERIMA_BARU))
					$('#noRek2').val(getValue(data.NO_REKENING_PENERIMA_BARU))
					$('#namaRek2').val(getValue(data.NAMA_REKENING_PENERIMA_BARU))
					$('#keteranganKoreksi').val(getValue(data.KETERANGAN_KOREKSI))
					$('#tglKoreksi').val(convertDate((data.TGL_AGENDA_KOREKSI)))
					$('#petugasKoreksi').val(getValue(data.PETUGAS_KOREKSI + ' - ' + data.NAMA_PETUGAS_KOREKSI))
					$('#kantorKoreksi').val(getValue(data.KODE_KANTOR_KOREKSI + ' - ' + data.NAMA_KANTOR_KOREKSI))						
					$('#noKonfirmasi').val(getValue(data.NO_KONFIRMASI));
					$('#noProses').val(getValue(data.NO_PROSES));
					$('#kdPrg').val(getValue(data.KD_PRG));
					$('#kodeKantorKoreksi').val(getValue(data.KODE_KANTOR_KOREKSI));						
					$('#kodeAgendaKoreksi').val(getValue(data.KODE_AGENDA_KOREKSI));					
					$('#noLevel').val(getValue(data.NO_LEVEL));					
					$('#kodeJabatan').val(getValue(data.KODE_JABATAN));	
					$('#kodeBankPembayar').val(getValue(data.KODE_BANK_PEMBAYAR));				
					$('#namaBankpembayar').val(getValue(namaBankPembayar));	
					$('#kodeBankPembayar2').val(getValue(data.KODE_BANK_PEMBAYAR_BARU));				
					$('#namaBankpembayar2').val(getValue(namaBankPembayarBaru));
					
					if (data.TGL_LAHIR_PENERIMA_MANFAAT) {
						$('#tglLahirPenerimaManfaat').val(convertDate(data.TGL_LAHIR_PENERIMA_MANFAAT));	
					} else {
						$('#tglLahirPenerimaManfaat').val('');	
					}
					
					let status = "";
					if (getValue(data.STATUS_APPROVAL_KOREKSI) == 'T') {
						status = "MENUNGGU PERSETUJUAN";
					} else if (getValue(data.STATUS_APPROVAL_KOREKSI) == 'Y') {
						status = "DISETUJUI";
					} else {
						status = "DITOLAK";
					}

					$('#statusKoreksi').val(status)

					// data approval
					var html_data = "";



					if (dataApproval.length > 0) {
						for (var i = 0; i < dataApproval.length; i++) {		
							let tglApproval = '';
							let petugasApproval = '';
							let namaPetugasApproval = '';
							let keteranganApproval = '';

							if (dataApproval[i]["TGL_APPROVAL"] != null || dataApproval[i]["TGL_APPROVAL"]) {
								tglApproval = convertDate(getValue(dataApproval[i]["TGL_APPROVAL"]));
							} else {
								tglApproval = '-';
							}

							if (dataApproval[i]["PETUGAS_APPROVAL"] || dataApproval[i]["PETUGAS_APPROVAL"] != null) {
								petugasApproval = dataApproval[i]["PETUGAS_APPROVAL"];
								namaPetugasApproval = dataApproval[i]["NAMA_PETUGAS_APPROVAL"];
							} else {
								petugasApproval = '';
								namaPetugasApproval = '';
							}

							if (dataApproval[i]["KETERANGAN_APPROVAL"] != null || dataApproval[i]["KETERANGAN_APPROVAL"]) {
								keteranganApproval = dataApproval[i]["KETERANGAN_APPROVAL"];
							} else {
								keteranganApproval = '-';
							}

							if (dataApproval[i]["STATUS_APPROVAL"]) {
								if (dataApproval[i]["STATUS_APPROVAL"] == "T") {
									status = '<td style="text-align: center;"> ' + '<span>' + '-' + '</span></td>';
								} else if (dataApproval[i]["STATUS_APPROVAL"] == "Y") {
									status = '<td style="text-align: center;"> ' + '<span style="color: green;">' + 'DISETUJUI' + '</span></td>';
								} else {
									status = '<td style="text-align: center;"> ' + '<span style="color: red;">' + 'DITOLAK' + '</span></td>';
								}
							} else {
								status = '<td style="text-align: center;"> ' + '<span>' + '-' + '</span></td>';
							}
							
							html_data += '<tr>';		
							html_data += '<td style="text-align: center;">' + (i + 1) + '</td>';
							html_data += '<td style="text-align: left;"> ' + getValue(dataApproval[i]["NAMA_JABATAN"]) + '</td>';
							html_data += '<td style="text-align: left;"> ' + getValue(dataApproval[i]["KODE_KANTOR"]) + ' - ' + getValue(dataApproval[i]["NAMA_KANTOR"]) + '</td>';
							// html_data += '<td style="text-align: center;"> ' + 
							// '<input type="checkbox" name="status[]"' + (dataApproval[i]["STATUS_APPROVAL"] === "Y" ? 'checked' : '') + ' disabled>' + '</td>';
							html_data += status;
							html_data += '<td style="text-align: center;"> ' + tglApproval + '</td>';
							html_data += '<td style="text-align: left;"> ' + petugasApproval + ' - ' + namaPetugasApproval + '</td>';
							html_data += '<td style="text-align: left;"> ' + keteranganApproval + '</td>';
							html_data += '</tr>';		
						}
					} else {
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="6" style="text-align: center;">-- Data Tidak Ada --</td>';
						html_data += '</tr>';
					}
					$("#daftarPersetujuanKoreksi").html(html_data);
					//									
				} else {
					alert(jdata.msg);
				}
				window.asyncPreload('filter', false);
			},
			complete: function(){
				window.asyncPreload('filter', false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				window.asyncPreload('filter', false);
			}
		});
	}

	function formatAngka(angka) {
		if (Number(angka) > 1 && angka) {
			return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		} else {
			return '';
		}
	}

	function convertDate(inputDate) {
		const [day, monthAbbreviation, year] = inputDate.split('-');
		const month = {
			JAN: '01', FEB: '02', MAR: '03', APR: '04', MAY: '05', JUN: '06',
			JUL: '07', AUG: '08', SEP: '09', OCT: '10', NOV: '11', DEC: '12'
		}[monthAbbreviation.toUpperCase()];

		if (!month) {
			return 'Singkatan bulan tidak valid';
		}

		return `${day}-${month}-20${year}`;
	}

	function filter(sc_kode_klaim) {
		window.asyncPreload('filter', true);
		getData(sc_kode_klaim);
	}

	function kembali(){
		let param = "";
		if (statusSubmit) {
			param = statusSubmit;
		} else {
			param = '<?=$ls_jenis_data?>';
		}

		var url_back = 'http://<?= $HTTP_HOST; ?><?=$formUtama?>?former_search_by=' + '<?=$ls_search_by?>' + '&former_jenis_data=' + param + '&former_search_txt=' + '<?=$ls_search_txt?>' + '&former_search_tgl=' + '<?=$ls_search_tgl?>' + '&former_search_status=' + '<?=$ls_search_status?>';
		window.location.replace(url_back);
	}    

	function reLoad() {
		window.location.replace('http://<?= $HTTP_HOST; ?><?= $formDetail ?>?no_level='+ '<?=$ls_no_level?>' +'&kode_jabatan='+ '<?=$ls_kode_jabatan?>' +'&kode_agenda_koreksi='+ '<?=$ls_kode_agenda_koreksi?>' +'&kode_klaim='+ '<?=$ls_kode_klaim?>' +'&nomor_identitas='+ '<?=$ls_nomor_identitas?>' +'&jenis_data='+ 'sudah' +'&search_by='+ '<?=$ls_search_by?>' +'&search_txt='+ '<?=$ls_search_txt?>' +'&search_tgl='+ '<?=$ls_search_tgl?>');
	}

	function prosesSubmitData(formData) {
		preload(true);
		$.ajax({
			type: 'POST',
			url: url,
			data: formData,
			dataType: "JSON",
			contentType: false,
			cache: false,
			processData: false,
			beforeSend: function () {
				$('#btn_submit').html('<i id="spinn" class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only"> LOADING...</span>')
				$('#btn_submit').attr('disabled', '');
			},
			success: function(data){
				if (data.ret == 0){
					alert("Sukses! Berhasil melakukan submit.");
					statusSubmit = 'sudah';
                    reLoad();
				} else if (data.ret == -1) {
						alert("Gagal!"+data.msg);
						kembali();
				} else {
						alert("Gagal!!!  "+data.msg);
				}
				preload(false);
			},
			complete: function(){
				preload(false);
			},
			error: function(e){
				alert("Terjadi kesalahan, coba beberapa saat lagi!" + json.parse(e));
				preload(false);
			}
		});
	}

	async function submitData(){
		preload(true);
		var form = $('#formreg')[0];
		var formData = new FormData(form);
		formData.append('tipe', 'submit_data');
		formData.append('no_level', '<?=$ls_no_level?>');
		formData.append('kode_jabatan', '<?=$ls_kode_jabatan?>');
		formData.append('kode_agenda_koreksi', '<?=$ls_kode_agenda_koreksi?>');
		formData.append('kode_klaim', '<?=$ls_kode_klaim?>');
	
		let status_persetujuan = $('#statusPersetujuan').val();
		let kode_klaim = $('#kodeKlaim').val();
		let keterangan_persetujuan = $('#keteranganPersetujuan').val();
		let tipe = 'submit_data';
		let konfirmasi="Apakah anda yakin melakukan proses ini?";
		let flag = $('#flag_disclaimer').prop("checked");

		if (status_persetujuan == "") {
			return alert('Status persetujuan tidak boleh kosong');
		}

		if (keterangan_persetujuan == "") {
			return alert('Keterangan persetujuan tidak boleh kosong');
		}

		confirmation("Konfirmasi", konfirmasi,
		async function () {
			// await prosesSubmitData(kode_klaim, status_persetujuan, keterangan_persetujuan)
			await prosesSubmitData(formData)
			setTimeout(function(){close();}, 500)
		},
		setTimeout(function(){}, 500));
		preload(false);
	}

</script>