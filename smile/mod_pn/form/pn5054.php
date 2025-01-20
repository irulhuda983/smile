<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype     = "form";
$gs_pagetitle = "PN5054 - Penyerahan Klaim JHT Kepada BHP";

$DB           = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$KD_KANTOR 	  = $_SESSION['kdkantorrole'];
$KODE_ROLE 	  = $_SESSION['regrole'];
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
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
	});

	var asyncPreloadStart;

	function asyncPreload(state){
		if (state == true) {
			asyncPreloadStart = setInterval(function() {
				$('#loading').show();
				$('#loading-mask').show();
			}, 50);
		} else {
			$('#loading').hide();
			$('#loading-mask').hide();
			clearInterval(asyncPreloadStart);
		}
	}

	function getValue(val){
		return val == null ? '' : val;
	}

	function search_by_changed(){
		$("#search_txt").val("");
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function resize(){
		$("#div_container").width($("#div_dummy").width());
		
		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());
		
		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);
		$("#div_footer").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
		$("#div_footer").width($("#div_dummy_data").width());

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
					filter();
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
	
	function viewDetil(kode_klaim){
		var params = "&kode_klaim=" + kode_klaim;
		showFormReload('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5054_form.php?' + params, "PN5054 - DETIL PENYERAHAN SALDO JHT KE BHP", 980, 440, scroll);
	}

	function emptyrows() {
		let html_data = "";
		html_data     += '<tr class="nohover-color">';
		html_data     += '<td colspan="15" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data     += '</tr>';
		$("#data_list").html(html_data);
	}

	function filter(val = 0){
		var pages      = new Number($("#pages").val());
		var page       = new Number($("#page").val());
		var page_item  = $("#page_item").val();
		
		var search_by  = $("#search_by").val();
		var search_txt = $("#search_txt").val();

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
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url : "../ajax/pn5054_action.php?"+Math.random(),
			data: {
				tipe      : 'select',
				page      : page,
				page_item : page_item,
				search_by : search_by,
				search_txt: search_txt
			},
			success: function(data){
				var jdata = JSON.parse(data);
				
				if (jdata.ret == 1){
					var html_data = "";
					for(var i = 0; i < jdata.data.length; i++){
						html_data += '<tr>';
						html_data += '<td style="text-align: right;">' + getValue(jdata.data[i].NO) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_KANTOR) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NPP) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_PERUSAHAAN) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_BHP) + '</td>';
						html_data += '<td style="text-align: right;">' + getValue(jdata.data[i].NOM_PEMBAYARAN) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_PEMBAYARAN) + '</td>';
						html_data += '<td style="text-align: center;">' + 
						(getValue(jdata.data[i].ST_UPLOAD_BERITA_ACARA) == 'Y' ? '<img src="../../images/file_apply.gif">' : '')  + '</td>';
						html_data += '<td style="text-align: center;">' + 
						(getValue(jdata.data[i].ST_UPLOAD_BUKTI_TRANSFER) == 'Y' ? '<img src="../../images/file_apply.gif">' : '') + '</td>';
						html_data += '<td style="text-align: center;">' + 
						(getValue(jdata.data[i].ST_CETAK_SURAT_REKOM) == 'Y' ? '<img src="../../images/file_apply.gif">' : '') + '</td>';
						html_data += '<td style="text-align: center;">' 
									+ '<a href="javascript:void(0)" onclick="viewDetil(\''
									+ getValue(jdata.data[i].KODE_KLAIM) + '\')"><b>View Detil</b></a>' + '</td>';
						html_data += '</tr>';
					}

					if (html_data == "") {
						emptyrows();
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
					emptyrows();
					alert(jdata.msg);
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				asyncPreload(false);
			}
		});
	}
</script>
<style>
	.div-container{
		min-width: 700px;
		width: 100%;
	}
	.div-header{
		min-width: 700px;
	}
	.div-body{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-data{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-footer{
		padding-top: 10px;
		border-bottom: 1px solid #eeeeee;
	}
	.hr-double{
		border-top:3px double #8c8c8c;
		border-bottom:3px double #8c8c8c;
	}
  .hr-double-top{
    border-top:3px double #8c8c8c;
	}
  .hr-double-bottom{
  	border-bottom:3px double #8c8c8c;
	}
	.hr-double-left{
    border-left:3px double #8c8c8c;
	}
  .hr-double-right{
    border-right:3px double #8c8c8c;
	}
	.table-data{
		width: 100%;
		border-collapse: collapse;
		border-color: #c0c0c0;
		background-color: #ffffff;
	}
	.table-data th{
		padding: 6px 6px 6px 6px;
		font-weight: bold;
		text-align: left;
	}
	.table-data td{
		padding: 6px 6px 6px 6px;
		text-align: left;
		border-bottom: 1px solid #c0c0c0;
	}
	.table-data tr:last-child td{
		border-bottom:3px double #8c8c8c;
	}
	.table-data tbody tr:hover{
		cursor: pointer;
		background-color:#f5f5f5;
	}
  .nohover-color:hover {
		cursor: pointer!important;
    background-color:#FFFFFF!important;
	}
	.value-modified{
    background-color: #b4eeb4!important;
	}
	.btn {
		padding: 2px!important;
	}
</style>
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
						<div style="padding-top: 10px;">
							<div style="float: left; width: 100%;">
								<div style="float: left">
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
								<div style="float: right; padding: 2px;">
									<input type="button" name="btnfilter" style="width: 100px" class="btn green" id="btnfilter" value="Tampilkan Data" onclick="filter()"/>
								</div>
								<div style="float: right; padding: 2px;">
									<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="width: 136px;">
								</div>
								<div style="float: right; padding: 2px;">
									<div style="min-width: 60px; display: inline-block; text-align: right;">Search by : </div>
									<select name="search_by" id="search_by" style="width: 136px;" onchange="search_by_changed()">
										<option value="">-- Keyword --</option>
										<option value="sc_kode_klaim">Kode Klaim</option>
										<option value="sc_npp">NPP</option>
										<option value="sc_nama_bhp">Nama BHP</option>
										<option value="sc_st_berita_acara">Berita Acara</option>
										<option value="sc_st_bukti_transfer">Bukti Transfer</option>
									</select>
								</div>
							</div>
						</div> 
					</div>
					<div id="div_data" class="div-data">
						<div style="padding: 6px 0px 0px 0px;">
							<table class="table-data">
								<thead>
									<tr class="hr-double">
										<th style="text-align: right;">No</th>
										<th style="text-align: left;">Kode Klaim</th>
										<th style="text-align: left;">Kode Kantor</th>
										<th style="text-align: left;">Nama Kantor</th>
										<th style="text-align: left;">KPJ</th>
										<th style="text-align: left;">Nama Tenaga Kerja</th>
										<th style="text-align: left;">NPP</th>
										<th style="text-align: left;">Nama Perusahaan</th>
										<th style="text-align: left;">Nama BHP</th>
										<th style="text-align: center;">Nominal</th>
										<th style="text-align: center;">Tgl. Pembayaran<br/>(dd-mm-yyyy)</th>
										<th style="text-align: center;">Upload<br/>Berita Acara</th>
										<th style="text-align: center;">Upload<br/>Bukti Transfer</th>
										<th style="text-align: center;">Cetak Surat<br>Rekomendasi</th>
										<th style="text-align: center;">Action</th>
									</tr>
								</thead>
								<tbody id="data_list">
									<tr class="nohover-color">
										<td colspan="15" style="text-align: center;">-- Data tidak ditemukan --</td>
									</tr>
								</tbody>
							</table>   
						</div>
					</div> 
					<div id="div_page" style="padding-bottom: 26px;">
						<div style="padding-top: 8px;">
							<div style="float: left;">
								<span style="vertical-align: middle;">Halaman</span>
								<a href="javascript:void(0)" title="First Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(-2)"><<</a>
								<a href="javascript:void(0)" title="Previous Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(-1)">Prev</a>
								<input type="text" value="1" id="page" name="page" style="width: 50px;  vertical-align: middle; text-align: center;" onkeypress="return isNumber(event)" onblur="filter()"/>
								<a href="javascript:void(0)" title="Next Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(1)">Next</a>
								<a href="javascript:void(0)" title="Last Page" style="vertical-align: middle; padding-left: 2px; padding-right: 2px;" onclick="filter(2)">>></a>
								<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
								<input type="hidden" id="pages">
							</div>
							<div style="float: right">
								<input type="text" style="visibility:hidden; width: 2px;">
								<input type="hidden" id="hdn_total_records">
								<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
							</div>
						</div>
					</div>
				</div>
				<div id="div_footer" class="div-footer" style="height: 80px;">
					<div style="background: #f2f2f2;padding:10px 20px;border:1px solid #ececec;">
						<span><b>Keterangan:</b></span>
						<li style="margin-left:15px;">Isikan data pencarian kemudian klik tombol tampilkan data</li>
						<li style="margin-left:15px;">Klik View Detil untuk melakukan perubahan data</li>
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