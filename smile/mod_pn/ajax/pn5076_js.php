<script language="javascript">
	$(document).ready(function(){
		$("#search_by").change(function(){
			if($(this).val() != 'KODE_SIAP_KERJA'){
				$("input[type=text]").keyup(function(){
					$(this).val( $(this).val().toUpperCase() );
				});
			} else {
				$("input[type=text]").keyup(function(){
					$(this).val( $(this).val().toLowerCase() );
				});
			}

			let tanggal = ['tanggalKlaim','tanggalKoreksi'];
            let typeNumber = ['nomorIdentitas', 'KPJ', 'nomorRekeningPenerima'];
			let val = $('#search_by').val();

            if (typeNumber.includes(val)) {
                $("#search_txt").prop("type", "number");
            } else {
                $("#search_txt").prop("type", "text");
            }

			if (tanggal.includes(val)) {
				$('#span_search_tgl').show();
				$('#search_txt').hide();
                $("#search_by_status").hide();
				$('#search_tgl').val('');
			} else if (val == 'statusKoreksi') {
                $('#search_by_status').show();
				$('#search_txt').hide();
				$('#search_tgl').hide();
				$('#search_txt').val('');
				$('#search_tgl').val('');
            } else {
				$('#span_search_tgl').hide();
				$('#search_by_status').hide();
				$('#search_txt').show();
				$('#search_tgl').val('');
			}
		});
		
		if('<?=$former_search_by?>' || '<?=$former_search_txt?>' || '<?=$former_search_tgl?>' || '<?=$former_jenis_data?>' || '<?=$former_search_status?>'){		
			setTimeout(function() {
				var formerSearchBy = '<?=$former_search_by?>';
				var formerSearchTxt = '<?=$former_search_txt?>';
				var formerSearchTgl = '<?=$former_search_tgl?>';
				var formerJenisData = '<?=$former_jenis_data?>';
				var formerSearchStatus = '<?=$former_search_status?>';

				filter(0, formerSearchBy, formerSearchTxt, formerSearchTgl, formerJenisData, formerSearchStatus);
			}, 500);
		} else {
			setTimeout(function(){ filter(); }, 500);
		}

		$("input[name='jenis_data']").on("change", filter);

	});

	function convertString(value) {
		value = value.charAt(0).toUpperCase() + value.slice(1);
		value = value.replace(/([A-Z])/g, ' $1');
		value = value.trim();
		return value;
	}

    function filterOptions(param = '') {
        let x = param || $("#search_by").val();
        let jenis_data = $('input[name=jenis_data]:checked').val();

		var selectOptions = "";
		selectOptions = [
			'kodeAgendaKoreksi',
			'kodeKlaim',
			'nomorIdentitas',
			'namaPeserta',
			'KPJ',
			'tanggalKlaim',
			'namaBankPenerima',
			'nomorRekeningPenerima',
			'namaRekeningPenerima',
			'tanggalKoreksi',
			'petugasKoreksi',
			'kantorKoreksi'
		];

        if (jenis_data == 'sudah') {
            if (x && x == "statusKoreksi") {
                $("#search_by_status").show();
            } else {
                $("#search_by_status").hide();
            }

            selectOptions.push('statusKoreksi')

            var html_search_by_status = "";
            html_search_by_status += '<option value="">' + '-- Pilih --' + '</option>';
            html_search_by_status += '<option value="T">' + 'MENUNGGU PERSETUJUAN' + '</option>';
            html_search_by_status += '<option value="Y">' + 'DISETUJUI' + '</option>';
            html_search_by_status += '<option value="R">' + 'DITOLAK' + '</option>';
    
            $("#search_by_status").html(html_search_by_status);
            $("#search_by_status").val('');
        } else {
            $("#search_by_status").hide();
        }

		var html_data = "";   
		html_data += '<option value="">' + '-- Pilih --' + '</option>';

		for(var i = 0; i < selectOptions.length; i++) {
			let x = '';
			if (selectOptions[i] == 'KPJ') {
				x = selectOptions[i];
			} else {
				x = convertString(selectOptions[i]);
			}
			html_data += '<option value='+ selectOptions[i] + '>' + x + '</option>';
		}

		$("#search_by").html(html_data);	
		$("#search_by").val(x);	

	}

	function getValue(val){
		return val == null ? '' : val;
	}

	function formatAngka(angka) {
		if (Number(angka) > 1 && angka) {
			return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		} else {
			return 0;
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

	function search_by_changed(){
		$("#search_txt").val("");
	}

	function handleRadioChange(param) {
		$("#search_by").val("");
		$("#search_txt").val("");
		$("#search_tgl").val("");
		$("#search_status").val("");
    }

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function filter(val = 0, former_search_by, former_search_txt, former_search_tgl, former_jenis_data, former_search_status){
		var pages 			= new Number($("#pages").val());
		var page 			= new Number($("#page").val());
		var page_item 		= $("#page_item").val();
		var search_by = $("#search_by").val();
        var jenis_data = former_jenis_data || $('input[name=jenis_data]:checked').val();
        var search_txt = former_search_txt || $("#search_txt").val();
        var search_tgl = former_search_tgl || $("#search_tgl").val();
        var search_status = former_search_status || $("#search_by_status").val();

		if(former_jenis_data){
			$("input[value="+former_jenis_data+"]").prop("checked",true);
		}

		if (former_search_by) {
            search_by = former_search_by;
            filterOptions(search_by);
            search_txt = '';
            search_tgl = '';
            search_status = '';
        } else {
            filterOptions();
        }

        $("#search_by_status").val(search_status)

		page = setPage(val);

		let url = "<?=$formAjax?>" + '?' + Math.random();

		preload(true);
		$.ajax({
			type: 'POST',
			url: url,
			data: {
				tipe: 'select',
				page: page,
				page_item: page_item,
				search_by: search_by,
				search_txt: search_txt,	
				search_tgl: search_tgl,
				jenis_data: jenis_data,
                search_status: search_status				
			},	
			success: function(data){
				var jdata = JSON.parse(data);

				if (jdata.ret > 0){
					baseData = jdata.data;
					var html_data = "";
					var num = 0;

					for(var i = 0; i < jdata.data.length; i++){
						var no = ((page_item * page) - page_item) + num + 1;
						html_data += '<tr>';					
						html_data += '<td style="text-align: center;">' + no + '</td>';
						html_data += '<td style="text-align: center;"><a onclick="showFormDetail(`'+ getValue(jdata.data[i].NO_LEVEL)+'`,`'+ getValue(jdata.data[i].KODE_JABATAN)+'`,`'+ getValue(jdata.data[i].KODE_AGENDA_KOREKSI)+'`,`'+getValue(jdata.data[i].KODE_KLAIM)+'`,`'+getValue(jdata.data[i].NOMOR_IDENTITAS)+'`,`'+jenis_data+'`,`'+search_by+'`,`'+search_txt+'`,`'+search_tgl+'`)">Lihat</a></td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_AGENDA_KOREKSI) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].NOMOR_IDENTITAS) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KPJ) + '</td>';
						html_data += '<td style="text-align: center;">' + convertDate(getValue(jdata.data[i].TGL_KLAIM)) + '</td>';
						html_data += '<td style="text-align: right;">' + formatAngka(getValue(jdata.data[i].NOM_MANFAAT)) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].BANK_PENERIMA) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NO_REKENING_PENERIMA) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_REKENING_PENERIMA) + '</td>';
						html_data += '<td style="text-align: center;">' + convertDate(getValue(jdata.data[i].TGL_AGENDA_KOREKSI)) + '</td>';
						html_data += '<td style="text-align: center;" title="' + getValue(jdata.data[i].NAMA_PETUGAS_KOREKSI) +'">' + getValue(jdata.data[i].PETUGAS_KOREKSI) + '</td>';
						html_data += '<td style="text-align: center;" title="' + getValue(jdata.data[i].NAMA_KANTOR_KOREKSI) +'">' + getValue(jdata.data[i].KODE_KANTOR_KOREKSI) + '</td>';

                        let status = "";
                        if (getValue(jdata.data[i].STATUS_APPROVAL) == 'T') {
                            status = "MENUNGGU PERSETUJUAN";
                        } else if (getValue(jdata.data[i].STATUS_APPROVAL) == 'Y') {
                            status = "DISETUJUI";
                        } else {
                            status = "DITOLAK";
                        }

						html_data += '<td style="text-align: left;">' + status + '</td>';
						html_data += '</tr>';
						num++;
					}
					
					if (html_data == "") {
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="21" style="text-align: center;">-- Data tidak ditemukan --</td>';
						html_data += '</tr>';
					}
					$("#data_list").html(html_data);
					
					// load info halaman
					$("#pages").val(Math.ceil(jdata.recordsTotal/page_item));
					$("#span_info_halaman").html('dari ' + Math.ceil(jdata.recordsTotal/page_item) + ' halaman');
					var start 	= ((page_item * page) - page_item) + 1;
					var end 	= ((page_item * page) - page_item) + 10;
					// load info item
					$("#span_info_item").html('Menampilkan item ke ' + start + ' sampai dengan ' + no + ' dari ' + jdata.recordsTotal + ' items');
					$("#hdn_total_records").val(jdata.recordsTotal);
				} else if(jdata.ret == -2){
					var html_data = "";
					html_data += '<tr class="nohover-color">';
					html_data += '<td colspan="21" style="text-align: center;">-- Data tidak ditemukan --</td>';
					html_data += '</tr>';
					$("#data_list").html(html_data);
					$("#span_info_item").html('Menampilkan item ke ' + 0 + ' sampai dengan ' + 0 + ' dari ' + 0 + ' items');
				} else {
					alert(jdata.msg);
				}
				preload(false);
			},
			complete: function(){
				preload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				preload(false);
			}
		});
	}	 

	function setPage(val) {
		var pages = new Number($("#pages").val());
        var page = new Number($("#page").val());
		if (val == 1) {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == 2) {
			page = pages;
		} else if (val == -1) {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == -2) {
			page = 1;
		} else if (val == 0) {
			page = 1;
		}

		$("#page").val(page);
		return page;
	}

	function showFormDetail(no_level, kode_jabatan, kode_agenda_koreksi, kode_klaim, nomor_identitas, jenis_data, search_by, search_txt, search_tgl){
		window.location.replace('http://<?= $HTTP_HOST; ?><?= $formDetail ?>?no_level='+no_level+'&kode_jabatan='+kode_jabatan+'&kode_agenda_koreksi='+kode_agenda_koreksi+'&kode_klaim='+kode_klaim+'&nomor_identitas='+nomor_identitas+'&jenis_data='+jenis_data+'&search_by='+search_by+'&search_txt='+search_txt+'&search_tgl='+search_tgl);
 	}					
	
</script>