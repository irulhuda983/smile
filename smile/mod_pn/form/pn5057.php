<?php
require_once "../../includes/header_app_nosql.php";
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype = "form";
$gs_pagetitle = "PN5057 - MONITORING DOKUMEN ARSIP KLAIM";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];

/** get current month and year */
$curr_month = date('m');
$curr_year = date('Y');
$curr_kategori  = !isset($_GET['curr_kategori']) ? $_POST['curr_kategori'] : $_GET['curr_kategori'];
if ($curr_kategori=='') {
	$curr_kategori='1';
}
?>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
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

		//filter();
		//setTimeout(function(){ filter(); }, 1000);
		//filter();

		$('#search_by').change(function(e) {

			//console.log($('#search_by :selected').text());


			if($('#search_by :selected').text()=='Tanggal Booking'){

				$('#span_search_tgl').show();
				$('#search_txt').hide();
				$('#search_tgl').val('');

			}else{
				$('#span_search_tgl').hide();
				$('#search_txt').show();
				$('#search_tgl').val('');

			}


            });

		// alert($("input[name='rg_kategori']:checked"). val());

		$("input[name='rg_kategori']").change(function(e) {
		if($("input[name='rg_kategori']:checked"). val()!="2"){
			$('#div_pagex').hide();
		}
		else{
			$('#div_pagex').show();
		}
	});




	});

	function getValue(val){
		return val == null ? '' : val;
	}
	$('#id_of_field').change(function() {
    // $(this).val() will work here
	});
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

	// function viewDetil(kode_pengajuan){
	// 	var params = "&kode_pengajuan=" + kode_pengajuan;
	// 	showFormReload('http://<?= $HTTP_HOST; ?>/mod_wr/form/wr1007_daftar_rencana_wasrik.php?' + params, "DAFTAR PENGAJUAN RENCANA KERJA WASRIK", 980, 620, scroll);
	// }


	function filter(val = 0){
		var pages = new Number($("#pages").val());
		var page = new Number($("#page").val());
		var page_item = $("#page_item").val();

		var search_by = $("#search_by").val();
		var search_txt = $("#search_txt").val();
		var kode_layanan = $("#kode_layanan").val();
		var search_tgl = $("#search_tgl").val();

		if (val == 1) {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == 2) {
			page = pages;
		} else if (val == -1) {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == -2){
			page = 1;
		}else if(val == 0){
			page=1;
		}

		$("#page").val(page);


		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5057_action.php?"+Math.random(),
			data: {
				tipe: 'select',
				page: page,
				page_item: page_item,
				search_by: search_by,
				kode_layanan : kode_layanan,
				search_txt: search_txt,
				con_kategori:$('input[name=rg_kategori]:checked').val(),

			},
			success: function(data){
				console.log(data);
				var jdata = JSON.parse(data);
				//console.log(jdata);

				if (jdata.ret == 0){
					var html_data = "";
					var No = 0;
					var statusUpload = '';
					for(var i = 0; i < jdata.data.length; i++){
						// if(getValue(jdata.data[i].idDokumen)===''){
							No++;
							html_data += '<tr>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].nomor) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].idDokumen) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].tglKlaim) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].kpj) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].namaTk) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].kodeSegmen) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].kodeKantor) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].statusKlaim) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].namaJenisDokumen) + '</td>';
							if(jdata.data[i].isArchived ==='Y'){
								html_data += '<td style="text-align: center; width:10%"><a href="#" onclick="show_arsip(`'+jdata.data[i].idArsipBundelStamp+'`);"><b>Sudah Diarsipkan</b></a>';
							}
							else if(jdata.data[i].isDokLengkap ==='Y' && jdata.data[i].isArchived==="T"){
							html_data += '<td style="text-align: center; width:10%"><a href="#" onclick="generateDokumen(`'+jdata.data[i].idDokumen+'`);"><b>Arsipkan</b></a>';
							}else{
								html_data += '<td style="text-align: center; width:10%"><b>Dokumen Belum Lengkap</b></a>';
							}

							html_data += '</tr>';
						// }
					}

					if (html_data == "") {
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="18" style="text-align: center;">-- Data tidak ditemukan --</td>';
						html_data += '</tr>';
					}
					$("#data_list").html(html_data);

					// load info halaman
					$("#pages").val(jdata.pages);
					$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

					// load info item
					$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
					$("#hdn_total_records").val(jdata.recordsTotal);

					// resize();
				} else {
					alert(jdata.msg);
					var html_data = "";
					html_data += '<tr class="nohover-color">';
					html_data += '<td colspan="18" style="text-align: center;">-- Data tidak ditemukan --</td>';
					html_data += '</tr>';
					$("#data_list").html(html_data);
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
	function show_arsip(id_arsip_bundel) {
		NewWindow('<?=$wsIpArsip?>/qr/' + id_arsip_bundel,'',800,500,1);
	}
	function generateDokumen(idDokumen){

		var kodeKlaim = idDokumen;


		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5057_action.php?"+Math.random(),
			data: {
				tipe: 'generate',
				kodeKlaim: kodeKlaim
			},
			success: function(data){
				var jdata = JSON.parse(data);
				if (jdata.ret == 0){
					alert(jdata.msg);
					filter(val = 0);
				} else {
					alert(jdata.msg);
					preload(true);
					filter(val = 0);
				}
			},
			complete: function(){
				preload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				preload(false);
			}
		});
		filter(val = 0);
	}
function edit(id){
	let params = id;
		showFormRefilter('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5056_entry.php?path='+ params, "Dokumen", 920, 610, scroll);
	}
function showDetail(id){
		window.location.replace('http://<?= $HTTP_HOST; ?>/mod_ec/ajax/ec5095_detail.php?kode_booking='+id+'');
	}

function showDetail2(id){
		window.location.replace('http://<?= $HTTP_HOST; ?>/mod_ec/ajax/ec7095_detail.php?kode_booking='+id+'');
	}

function downloadTemplate() {
    location.href = '../ajax/ec5095_download.php?mid=<?=$mid;?>&jenis_data=belum_proses';
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
		padding: 10px 6px 10px 6px;
		font-weight: bold;
		text-align: left;
	}
	.table-data td{
		padding: 4px 6px 4px 6px;
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
</style>
<div id="actmenu">
	<div style="float: left;width: 30%;">
		<h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>
	</div>
	<!-- <div style="float: left;width: 70%;margin-top: 4px;">
					<input type="radio" name="rg_kategori" value="1" onclick="filter()"   <?= ($curr_kategori == '1' ? 'checked' : '') ?>>&nbsp;<font  color="#ffffff"><b>JHT</b></font> &nbsp; |&nbsp;
            		<input type="radio" name="rg_kategori" value="2" onclick="filter()"  <?= ($curr_kategori == '2' ? 'checked' : '') ?>>&nbsp;<font  color="#ffffff"><b>JP</b></font> &nbsp; |&nbsp;
            		<input type="radio" name="rg_kategori" value="3" onclick="filter()"  <?= ($curr_kategori == '3' ? 'checked' : '') ?>>&nbsp;<font  color="#ffffff"><b>JKM</b></font> &nbsp; |&nbsp;
            		<input type="radio" name="rg_kategori" value="4" onclick="filter()"  <?= ($curr_kategori == '4' ? 'checked' : '') ?>>&nbsp;<font  color="#ffffff"><b>JKK</b></font>
	</div> -->
</div>
<div id="formreg">
	<div id="div_dummy" style="width: 100%;"></div>
	<!-- <div id="formKiri"> -->
    <form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<div id="div_container" class="div-container">
				<div id="div_header" class="div-header">
				</div>
				<div id="div_body" class="div-body">
					<div id="div_dummy_data" style="width: 100%;"></div>
						<div id="div_filter">
							<div style="padding-top: 10px;">

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
									<select name="search_by_kode_layanan" id="kode_layanan" style="width: 136px;" onchange="">
													<option value="">-- Pilih --</option>
													<option value="24">ANTOL</option>
													<option value="25">BPJSTKU</option>
													<option value="26">ONLINE</option>
													<!-- <option value="27">ONLINE</option>
													<option value="28">ONLINE</option>
													<option value="29">ONSITE</option> -->
									</select>
									<select name="search_by" id="search_by" style="width: 136px;" onchange="search_by_changed()">
													<option value="">-- Pilih --</option>
													<option value="sc_id_dokumen">KODE KLAIM</option>
									</select>
								</div>
							</div>
							</div>
						</div>
						<div id="div_data" style="overflow-x: auto; overflow-y: auto; white-space: nowrap;">
							<div style="padding: 6px 0px 0px 0px;">
								<table class="table-data hover">
									<thead>
										<tr class="hr-double">
											<th rowspan="2" style="text-align: center;">No</th>
											<th rowspan="2" style="text-align: center;">Kode Klaim</th>
											<th rowspan="2" style="text-align: center;">Tanggal Klaim</th>
											<th rowspan="2" style="text-align: center;">KPJ</th>
											<th rowspan="2" style="text-align: center;">Nama</th>
											<th rowspan="2" style="text-align: center;">Segmen</th>
											<th rowspan="2" style="text-align: center;">Kantor</th>
											<th rowspan="2" style="text-align: center;">Status</th>
											<th rowspan="2" style="text-align: center;">Jenis Layanan</th>
											<th rowspan="2" style="text-align: center;">Aksi</th>
									</thead>
									<tbody id="data_list">
										<tr class="nohover-color">
											<td colspan="18" style="text-align: center;">-- Data tidak ditemukan --</td>
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

						<div id="div_footer" style="height: 120px; border-bottom: 1px solid #eeeeee;">

						<div style="background: #f2f2f2;padding:10px 20px;border:1px solid #ececec;">

						</div>

				</div>

				</div>

			</div>
		</form>
		</div>
	<!-- </div> -->
</div>
<script type="text/javascript">
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>