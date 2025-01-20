<?php
require_once "../../includes/header_app_nosql.php";
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype     = "form";
$gs_pagetitle = "PN5055 - MONITORING KLAIM JP LUMSUM PENDING RELAKSASI";
$DB           = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$USER         = $_SESSION["USER"];
$KD_KANTOR    = $_SESSION['kdkantorrole'];
$KODE_ROLE    = $_SESSION['regrole'];
?>
<?php include "pn_css.php"; ?>

<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript">	
  var _asyncPreloadRunning = [];
  var _asyncPreload;
  
  window.asyncPreload = function(idloader, state) {
    idloader = idloader || 'data'
    if (state) {
      _asyncPreloadRunning[idloader] = state
    } else {
      delete _asyncPreloadRunning[idloader]
    }
  }

  window.asyncPreloadStart = function() {
    _asyncPreload= setInterval(function () {
			try {
				if (_asyncPreloadRunning) {
					if (Object.keys(_asyncPreloadRunning).length > 0) {
						preload(true);
					} else {
						preload(false);
					}
				}
			} catch(e) {
				window.asyncPreloadEnd();
			}
    }, 100)
  }

  window.asyncPreloadEnd = function () {
    preload(false);
    clearInterval(_asyncPreload)
  }

  window.asyncPreloadStart();
</script>
<script language="javascript">
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

  	$(window).bind("resize", function(){
			resize();
		});
		resize();
		filter();
		// getTanggalAkhirPelunasanJP();

		/** list checkbox npp */
		window.list_npp = [];
	});

	function getValue(val){
		return val == null || val == undefined ? '' : val;
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function validateDigit(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode(key);
		var regex = /[0-9]|\./;
		if (!regex.test(key)) {
				theEvent.returnValue = false;
				if (theEvent.preventDefault)
						theEvent.preventDefault();
		}
	}
	
	function Comma(Num) { //function to add commas to textboxes
		Num += '';
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		x = Num.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1))
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
		return x1 + x2;
	}

	function isNumberKey(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode(key);
		if (key.length == 0)
			return;
		var regex = /^[0-9\b]+$/;
		if (!regex.test(key)) {
			theEvent.returnValue = false;
			if (theEvent.preventDefault)
				theEvent.preventDefault();
		}
	}

	function resize(){
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
		
		$("#div_container").css('max-height', $(window).height());
	}

	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title: title,
			msg: msg,
			buttons: window.parent.Ext.Msg.YESNO,
			icon: window.parent.Ext.Msg.QUESTION,
			fn: function(btn) {
				if (btn === 'yes') {
					fnYes();
				} else {
					fnNo();
				}
			}
		});
	}

	function showFormReload(mypage, myname, w, h, scroll) {
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					resubmit();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function showFormRefilter(mypage, myname, w, h, scroll) {
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					filter();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function showFormCallback(mypage, myname, w, h, scroll, callback) {
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					callback();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	/** ACTION FUNCTIONS STARTS HERE */ 
	function generateAgenda(kode_pending_rlx){
		confirmation('Penetapan Agenda', 'Yakin untuk generate Agenda atas transaksi ini?', function(){
			
			window.asyncPreload('generate', true);
			$.ajax({
				type: 'POST',
				url: "../ajax/pn5055_action.php?"+Math.random(),
				data: {
					tipe: 'generate_agenda',
					kode_pending_rlx: kode_pending_rlx
				},
				success: function(data){
					jdata = JSON.parse(data);
					if (jdata.ret == 1){
						alert(jdata.msg);
					} else {
						alert(jdata.msg);
					}
					filter();
					window.asyncPreload('generate', false);
				},
				complete: function(){
					window.asyncPreload('generate', false);
				},
				error: function(){
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
					window.asyncPreload('generate', false);
				}
			});
		}, function(){});
	}

	function batal(kode_pending_rlx){
		confirmation('Batal Agenda', 'Yakin untuk membatalkan transaksi ini?', function(){
			
			window.asyncPreload('batal', true);
			$.ajax({
				type: 'POST',
				url: "../ajax/pn5055_action.php?"+Math.random(),
				data: {
					tipe: 'batal',
					kode_pending_rlx: kode_pending_rlx
				},
				success: function(data){
					jdata = JSON.parse(data);
					if (jdata.ret == 1){
						alert(jdata.msg);
					} else {
						alert(jdata.msg);
					}
					filter();
					window.asyncPreload('batal', false);
				},
				complete: function(){
					window.asyncPreload('batal', false);
				},
				error: function(){
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
					window.asyncPreload('batal', false);
				}
			});
		}, function(){});
	}

	function getTanggalAkhirPelunasanJP() {
		window.asyncPreload('tanggal_pelunasan', true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5055_action.php?"+Math.random(),
			data: {
				tipe: 'get_tanggal_akhir_pelusanan',
				kode_prg: '4'
			},
			success: function(data){
				var jdata = JSON.parse(data);

				if (jdata.ret == 1){
					$('#ket_tgl_akhir_pelunasan_jp').html(jdata.tglAkhirRelaksasi);
				} else {
					alert(jdata.msg);
				}
				window.asyncPreload('tanggal_pelunasan', false);
			},
			complete: function(){
				window.asyncPreload('tanggal_pelunasan', false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				window.asyncPreload('tanggal_pelunasan', false);
			}
		});
	}
	
	function cetakTandaTerima(id_pointer_asal){
		NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5055_cetak.php?kode_klaim=' +id_pointer_asal+'&mid=<?=$mid;?>','',800,300,'no');
	}
	
	function NewWindow4(mypage, myname, w, h, scroll) {
        var openwin = window.parent.Ext.create('Ext.window.Window', {
            title: myname,
            collapsible: true,
            animCollapse: true,
            maximizable: true,
            width: w,
            height: h,
            minWidth: w,
            minHeight: h,
            layout: 'fit',
            modal: true,
            html: '<iframe src="' + mypage + '"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
            dockedItems: [{
                    xtype: 'toolbar',
                    dock: 'bottom',
                    ui: 'footer',
                    items: [
                        {
                            xtype: 'button',
                            text: 'Tutup',
                            handler: function () {
                                openwin.close();
                            }
                        }
                    ]
                }],
            listeners: {
                close: function () {
                    location.reload();
                },
                destroy: function (wnd, eOpts) {

                }
            }
        });
        openwin.show();
        return openwin;
    }

	function filter(val = 0){
		var pages = new Number($("#pages").val());
		var page = new Number($("#page").val());
		var page_item = $("#page_item").val();

		var search_status_tindaklanjut = $("input[name='search_status_tindaklanjut']:checked").val();
		var search_status_pelunasan = $("#search_status_pelunasan").val();
		var search_by = $("#search_by").val();
		var search_txt = $("#search_txt").val();

		val = new Number(val);
		if (val == 1) {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == 2) {
			page = pages;
		} else if (val == -1) {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == -2){
			page = 1;
		}

		$("#page").val(page);

		window.asyncPreload('data', true);

		// load data header
		var colspan_length = 15;
		if (search_status_tindaklanjut == 'BELUM DIPROSES') {
			let html_header = `
			<tr class='hr-double'>
				<th style='text-align: right;'>No</th>
				<th style='text-align: left;'>Kode Pending</th>
				<th style='text-align: left;'>KPJ</th>
				<th style='text-align: center;'>Tgl Kejadian</th>
				<th style='text-align: center;'>Tgl Pengajuan</th>
				<th style='text-align: center;'>BLTH Awal<br>Relaksasi</th>
				<th style='text-align: center;'>BLTH Akhir<br>Relaksasi</th>
				<th style='text-align: center;'>Tgl Akhir Pelunasan<br>Relaksasi</th>
				<th style='text-align: center;'>Jumlah Bulan<br>Penyesuaian Akhir</th>
				<th style='text-align: center;'>Jumlah Bulan<br>Lunas</th>
				<th style='text-align: center;'>Status<br>Pelunasan</th>
				<th style='text-align: left;'>NPP</th>
				<th style='text-align: center;'>Pembina</th>
				<th style='text-align: center;'>Kode Kantor<br>Kepesertaan</th>
				<th style='text-align: center;'>Action</th>
			</tr>`;
			$("#data_header").html(html_header);
		} else {
			let html_header = `
			<tr class='hr-double'>
				<th style='text-align: right;'>No</th>
				<th style='text-align: left;'>Kode Pending</th>
				<th style='text-align: left;'>KPJ</th>
				<th style='text-align: center;'>Tgl Kejadian</th>
				<th style='text-align: center;'>Tgl Pengajuan</th>
				<th style='text-align: center;'>BLTH Awal<br>Relaksasi</th>
				<th style='text-align: center;'>BLTH Akhir<br>Relaksasi</th>
				<th style='text-align: center;'>Tgl Akhir Pelunasan<br>Relaksasi</th>
				<th style='text-align: center;'>Jumlah Bulan<br>Penyesuaian Akhir</th>
				<th style='text-align: center;'>Jumlah Bulan<br>Lunas</th>
				<th style='text-align: center;'>Status<br>Pelunasan</th>
				<th style='text-align: left;'>NPP</th>
				<th style='text-align: center;'>Pembina</th>
				<th style='text-align: center;'>Kode Kantor<br>Kepesertaan</th>
				<th style='text-align: center;'>Kode Klaim <br>Tindak Lanjut</th>
			</tr>`;
			$("#data_header").html(html_header);
		}
		emptyrows(colspan_length);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5055_action.php?"+Math.random(),
			data: {
				tipe: 'select',
				page: page,
				page_item: page_item,
				search_status_tindaklanjut: search_status_tindaklanjut,
				search_status_pelunasan: search_status_pelunasan,
				search_by: search_by,
				search_txt: search_txt
			},
			success: function(data){
				var jdata = JSON.parse(data);

				if (jdata.ret == 1){
					var html_data = "";
					for(var i = 0; i < jdata.data.length; i++){
						html_data += '<tr>';
						html_data += '<td style="text-align: right;">' + getValue(jdata.data[i].NO) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_PENDING_RLX) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_KEJADIAN) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_PENGAJUAN) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].BLTH_AWAL_RLX) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].BLTH_AKHIR_RLX) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_AKHIR_PELUNASAN_RLX_JP) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].JML_BLN_IUR_RLX) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].JML_BLN_IUR_LUNAS) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].STATUS_PELUNASAN) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NPP) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_PEMBINA_RLX) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KANTOR_TINDAKLANJUT) + '</td>';

						if (getValue(jdata.data[i].STATUS_TINDAKLANJUT == 'T')) {
							html_data += '<td style="text-align: center;">';
							if (getValue(jdata.data[i].STATUS_PELUNASAN == 'LUNAS') || getValue(jdata.data[i].STATUS_PELUNASAN == 'BELUM LUNAS LEWAT MASA PELUNASAN')) {
								html_data += '<a href="javascript:void(0)" onclick="generateAgenda(\'' + getValue(jdata.data[i].KODE_PENDING_RLX) + '\')"><u><b>Generate Agenda</b></u></a>&nbsp;';
							} else {
								html_data += '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="cetakTandaTerima(\'' + getValue(jdata.data[i].ID_POINTER_ASAL) + '\')" title="Klik Link Tanda Terima Untuk Mencetak Tanda Terima Dokumen Kelengkapan Administrasi"><u><b>Tanda Terima</b></u></a>&nbsp;';
								html_data += '&nbsp;&nbsp;<a href="javascript:void(0)" onclick="batal(\'' + getValue(jdata.data[i].KODE_PENDING_RLX) + '\')" title="Klik Batal Untuk Membatalkan Monitoring Klaim JP Lumsum Pending Relaksasi"><u><b>Batal</b></u></a>&nbsp;';
							}
							html_data += '</td>';
						} else {
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KLAIM_TINDAKLANJUT) + '</td>';
						}
						html_data += '</tr>';
					}

					if (html_data == "") {
						emptyrows(colspan_length);
					} else {
						$("#data_list").html(html_data);
					}

					// load info halaman
					$("#pages").val(jdata.pages);
					$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

					// load info item
					$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
					$("#hdn_total_records").val(jdata.recordsTotal);

					resize();
				} else if (jdata.ret == 0){
					emptyrows(colspan_length);
				} else {
					emptyrows(colspan_length);
					alert(jdata.msg);
				}
				window.asyncPreload('data', false);
			},
			complete: function(){
				window.asyncPreload('data', false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				window.asyncPreload('data', false);
			}
		});
	}

	function emptyrows(colspan) {
		let html_data = "";
		html_data += '<tr class="nohover-color">';
		html_data += '<td colspan="' + colspan + '" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list").html(html_data);
	}

	function resubmit(){
		$("#formreg").submit();
	}

	function searchByChanged(){
		$("#search_txt").val("");
	}
	
</script>

<div id="actmenu">
	<h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>
</div>
<div id="formframe">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<div id="div_container" class="div-container">
				<div id="div_header" class="div-header">
				</div>
				<div id="div_body" class="div-body">
					<div id="div_dummy_data" style="width: 100%;"></div>
					<div id="div_filter">
						<div style="padding-top: 0px;">
							<div class="div-row" style="padding-top: 10px;">
								<label style="white-space: nowrap; color: #009999; width: 120px;">
									<input name="search_status_tindaklanjut" type="radio" value="BELUM DIPROSES" style="vertical-align: top; margin-top: 0px;" checked onclick="filter()"><b>BELUM DIPROSES</b>
								</label>
								<label style="white-space: nowrap; color: #009999; width: 100px;">
									<input name="search_status_tindaklanjut" type="radio" value="SUDAH DIPROSES" style="vertical-align: top; margin-top: 0px;" onclick="filter()"><b>SUDAH DIPROSES</b>
								</label>
							</div>
							<div class="div-row" style="padding-top: 10px;">
								<div class="div-col">
									<span>Tampilkan</span>
									<select name="page_item" id="page_item" style="width: 40px;" onchange="filter()">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>
									<span>item per halaman</span>
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<input type="button" name="btnfilter" style="width: 100px" class="btn green" id="btnfilter" value="TAMPILKAN" onclick="filter()"/>
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="width: 136px;">
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<div style="min-width: 60px; display: inline-block; text-align: left;">Search by</div>
									<span> : </span>
									<select name="search_status_pelunasan" id="search_status_pelunasan" style="width: 142px;">
										<option value="">-- Status Pelunasan --</option>
										<option value="LUNAS">LUNAS</option>
										<option value="BELUM LUNAS">BELUM LUNAS</option>
									</select>
									<select name="search_by" id="search_by" style="width: 142px;" onchange="searchByChanged()">
										<option value="">-- Keyword --</option>
										<option value="sc_npp">NPP</option>
										<option value="sc_nama_perusahaan">Nama PK/BU</option>
										<option value="sc_kpj">KPJ</option>
										<option value="sc_kode_pending">Kode Pending</option>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div id="div_data" class="div-data">
						<div style="padding: 6px 0px 0px 0px;">
							<table class="table-data">
								<thead id="data_header">
								</thead>
								<tbody id="data_list">
								</tbody>
							</table>
						</div>
					</div>
					<div id="div_page" class="div-page">
						<div class="div-row" style="padding-top: 8px;">
							<div class="div-col">
								<span style="vertical-align: middle;">Halaman</span>
								<a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="filter('-02')"><<</a>
								<a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="filter('-01')">Prev</a>
								<input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="filter(this.value);"/>
								<a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="filter('01')">Next</a>
								<a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="filter('02')">>></a>
								<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
								<input type="hidden" id="pages">
							</div>
							<div class="div-col-right">
								<input type="hidden" id="hdn_total_records">
								<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
							</div>
						</div>
					</div>
				</div>
				<div id="div_footer" class="div-footer">
					<div class="div-footer-content">
						<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
						<li style="margin-left:15px;">Klik "<b>Generate Agenda</b>" untuk membuat agenda baru atas data tersebut.</li>
						<li style="margin-left:15px;">Klik "<b>Batal</b>" untuk membatalkan data.</li>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<script type="text/javascript">
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>
