<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once '../../includes/fungsi_newrpt.php';
$pagetype = "form";
$gs_pagetitle = "Monitoring Transfer Pembayaran Klaim PLKK";
//
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];
$KODE_USER 	= $_SESSION['USER'];


$former_search_by   		= !isset($_GET['former_search_by']) ? $_POST['former_search_by'] : $_GET['former_search_by'];
$former_search_txt   		= !isset($_GET['former_search_txt']) ? $_POST['former_search_txt'] : $_GET['former_search_txt'];
$former_tglawaldisplay   	= !isset($_GET['former_tglawaldisplay']) ? $_POST['former_tglawaldisplay'] : $_GET['former_tglawaldisplay'];
$former_tglakhirdisplay   	= !isset($_GET['former_tglakhirdisplay']) ? $_POST['former_tglakhirdisplay'] : $_GET['former_tglakhirdisplay'];


?>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="../../javascript/chosen_v1.8.7/docsupport/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" media="all" href="../../javascript/chosen_v1.8.7/chosen.min.css">
<link rel="stylesheet" type="text/css" media="all" href="../../style/kn_style.css?abc">
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>

<style>
  .modal {	
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	padding-top: 100px; /* Location of the box */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
	}

	.input-userclass{
		padding: 5px 6px;
	margin: 3px 0;
	box-sizing: border-box;
	}

	/* Modal Content */
	.modal-content {
	position: relative;
	background-color: #fefefe;
	margin: auto;
	padding: 0;
	border: 4px solid #888;
		border-color: #6997ff;
		font-size : 1em;
	width: 20%;
	box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19);
	-webkit-animation-name: animatetop;
	-webkit-animation-duration: 0.4s;
	animation-name: animatetop;
	animation-duration: 0.4s
	}

	/* Add Animation */


	/* The Close Button */
	.close {
	color: white;
	float: right;
	font-size: 28px;
	font-weight: bold;
	}

	.btn-close-red{
		background-color: #dc3545;
		color: white;
		border:none;
		padding: 5px 10px;
		cursor: pointer;
	}

	.btn-close-green{
		background-color: #007bff;
		color: white;
		border:none;
		padding: 5px 10px;
		cursor: pointer;
	}

	.close:hover,
	.close:focus {
	color: #000;
	text-decoration: none;
	cursor: pointer;
	}

	.modal-header {
	padding: 10px 10px;
	background-color: #6997ff;
	color: white;
	}

	.modal-body {padding: 2px 16px;font-size: 1em;}

	.modal-footer {
	padding: 10px 10px;
	background-color: #dfeaf2;
	color: white;
		
	}
</style>
<script language="javascript">
	$(document).ready(function(){
		filter();

		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});
		

		$('#btn-close').click(function(){
			$('#myModal').hide();
		});
			
		
	});

	function funcIn(e){
		if(!event.inputType){
			document.getElementById('passVerification').value = "";
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

	
	function resetSearch(){
		$('#search_by').val('')
		$('#search_txt').val('')
		filter('-02')
	}
	
	function filter(val = 0,rg_kategori, former_search_by, former_search_txt, former_tglawaldisplay, former_tglakhirdisplay, former_rg_kategori)
	{
		var pages = parseInt($("#pages").val());
		var page = parseInt($("#page").val());
		var page_item = parseInt($("#page_item").val());
    
		var tglawaldisplay  = $("#tglawaldisplay").val();
		var tglakhirdisplay = $("#tglakhirdisplay").val();

		// var former_tglawaldisplay
		// var former_tglakhirdisplay
		// if(former_tglawaldisplay, former_tglakhirdisplay){
		// 	tglawaldisplay 	= former_tglawaldisplay;
		// 	tglakhirdisplay = former_tglakhirdisplay;
		// 	$("#tglawaldisplay").val(tglawaldisplay);
		// 	$("#tglakhirdisplay").val(tglakhirdisplay);
		// }
		
		var datePartsawal = tglawaldisplay.split("/");	
		var dateObjectawal = new Date(+datePartsawal[2], datePartsawal[1] - 1, +datePartsawal[0]); 
		var datePartsakhir = tglakhirdisplay.split("/");	
		var dateObjectakhir = new Date(+datePartsakhir[2], datePartsakhir[1] - 1, +datePartsakhir[0]); 
	 

		if (dateObjectawal > dateObjectakhir) {
			return alert('Tanggal awal pengajuan harus lebih kecil dari tanggal akhir pengajuan');
		} 

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
		
		var search_by = $('#search_by').val()
		var search_txt = $('#search_txt').val()
		var fl_st_transfer = $('input[name="fl_st_transfer"]:checked').val()
		
		preload(true);
		$.ajax({
			type: 'POST',
			url: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5043K_query.php?"+Math.random(),
			data: {
				tipe: 'select',
				page: page,
				kode_kantor:'<?php echo $KD_KANTOR;?>',
				kode_user:'<?php echo $KODE_USER;?>',
				page_item: page_item,	
				tglawaldisplay : tglawaldisplay,
				tglakhirdisplay : tglakhirdisplay,
				search_by,
				search_txt,
				fl_st_transfer	
			},
			success: function(data){
				var jdata = JSON.parse(data);
				if (jdata.ret == "1"){
					var html_data = "";
					if(jdata.data.length == 0){
						$('#btnapprove').prop('disabled', true);
						$('#checkAll').prop('disabled', true);
					}else{
						$('#btnapprove').removeAttr("disabled");
						$('#checkAll').removeAttr("disabled");
					}
					for(var i = 0; i < jdata.data.length; i++){
						let no = ((page_item * page) - page_item) + i + 1;
						let jdi = jdata.data[i]
            html_data += '<tr>';
				  	html_data += '<td style="text-align: center;">'   + jdi.NO + '</td>';

					if(jdi.KODE_TRANSFER) {
						html_data += '<td style="text-align: center;">'   + jdi.KODE_TRANSFER + '</td>';
					} else {
						html_data += '<td style="text-align: center;"></td>';
					}

					if(jdi.NO_REF_PAYMENT) {
						html_data += '<td style="text-align: center;">'   + jdi.NO_REF_PAYMENT + '</td>';
					} else {
						html_data += '<td style="text-align: center;"></td>';
					}

					if(jdi.NO_REF_TRF_PLKK) {
						html_data += '<td style="text-align: center;">'   + jdi.NO_REF_TRF_PLKK + '</td>';
					} else {
						html_data += '<td style="text-align: center;"></td>';
					}

				  	html_data += '<td style="text-align: center;">'   + jdi.BANK_PENERIMA + '</td>';
					html_data += '<td style="text-align: center;">'   + jdi.NO_REKENING_PENERIMA + '</td>';			
					html_data += '<td style="text-align: center;">'   + jdi.NAMA_REKENING_PENERIMA + '</td>';			
					html_data += '<td style="text-align: right;">'   + format_uang(jdi.NOM_NETTO) + '</td>';			
					html_data += '<td style="text-align: right;">'   + format_uang(jdi.NOM_SUDAH_DIBAYAR) + '</td>';			
					html_data += '<td style="text-align: right;">'   + format_uang(jdi.NOM_SISA) + '</td>';	
					html_data += '<td style="text-align: center;">'   + jdi.JML_KODE_KLAIM + '</td>';			
					html_data += '<td style="text-align: center;">'   + jdi.INFO_STATUS_TRANSFER + '</td>';			
					if (jdi.INFO_STATUS_TRANSFER=='ERROR') {
						html_data += `<td style="text-align: center;"><a onclick="act_geterror('${jdi.NO_REKENING_PENERIMA}','${jdi.KODE_TRANSFER}')" style="background-color: #ffc107;border: none;color: white;padding: 5px 22px;text-align: center;text-decoration: none;display: inline-block;font-size: 10px;margin: 4px 2px;cursor: pointer;">Koreksi<a></td>`;			
					} else if (jdi.INFO_STATUS_TRANSFER=='GET_STATUS'){
						html_data += `<td style="text-align: center;"><a onclick="act_getstatus('${jdi.KODE_TRANSFER}','${jdi.KODE_TIPE_PENERIMA}','${jdi.KD_PRG}','${jdi.NO_REKENING_PENERIMA}','${jdi.NO_REF_TRF_PLKK}')" style="background-color:#ffc107; border: none;color: white;padding: 5px 22px;text-align: center;text-decoration: none;display: inline-block;font-size: 10px;margin: 4px 2px;cursor: pointer;">Proses<a></td>`;			
					} else {
						//html_data += `<td style="text-align: center;"><a onclick="act_getstatus('${jdi.KODE_TRANSFER}','${jdi.KODE_TIPE_PENERIMA}','${jdi.KD_PRG}','${jdi.NO_REKENING_PENERIMA}','${jdi.NO_REF_TRF_PLKK}')" style="background-color:#ffc107; border: none;color: white;padding: 5px 22px;text-align: center;text-decoration: none;display: inline-block;font-size: 10px;margin: 4px 2px;cursor: pointer;">Proses<a></td>`;			
						html_data += '<td></td>'
					}
            html_data += '</tr>'; 
					
					}
					$('#jml_checkbox').val(jdata.data.length);

					if (html_data == "") {
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="18" style="text-align: center;">-- Data tidak ditemukan --</td>';
						html_data += '</tr>';
					}
					$("#data_list").html(html_data);
					$('.childBox').click(function(){
						if($('.childBox').is(':checked') == true){
							$('#checkAll').prop({
								indeterminate: true,
								checked: false
							});
						$('#checkAll').attr("disabled", true);
						}else{
							$('#checkAll').prop({
								indeterminate: false,
								checked: false
							});
						$('#checkAll').removeAttr("disabled");
						}
					});
					
					// load info halaman
					$("#pages").val(jdata.pages);

          if(jdata.pages <= 1){
             $('#nextPage').hide();
            $('#prevPage').hide();
            $('#lastPage').hide();
            $('#firstPage').hide();
          }
					$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

					// load info item
					$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.recordsTotal + ' dari ' + jdata.recordsTotal + ' items');
					$("#hdn_total_records").val(jdata.recordsTotal);

					// resize();
				} else {
					
						html_data += '<tr class="nohover-color">';
						html_data += '<td colspan="18" style="text-align: center;">-- Data tidak ditemukan --</td>';
						html_data += '</tr>';
					
					$("#data_list").html(html_data);
					$('#checkAll').attr("disabled", true);
					$('#checkAll').prop({
						indeterminate: true,
						checked: false
					});
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

	function displayDataDetail(data,task){
		setTimeout( function(){
			preload(true);
			}, 100);
			var params = "tipe=act_geterror"
						+ "&kode_kantor=<?php echo $KD_KANTOR;?>"
						+ "&kode_user=<?php echo $KODE_USER;?>"
						+ "&tglawaldisplay="+tglawaldisplay
						+ "&tglakhirdisplay="+tglakhirdisplay
						+ "&page="+page
						+ "&page_item="+page_item
						+ "&norekpenerima="+norekpen	
			window.location='pn5043K_edit.php?task='+task+'&source=monitor&'+params;
	}

	function act_getstatus(kodetransfer,kodetipenerima,kodeprg,norekpen,noreftrfplkk) {
		preload(true);
		$.ajax({
			type: 'POST',
			url: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5043K_query.php?"+Math.random(),
			data: {
				tipe: 'act_getstatus',
				"KODE_TRANSFER": kodetransfer,
				"KODE_TIPE_PENERIMA": kodetipenerima,
				"KD_PRG": kodeprg,
				"NOREK_TUJUAN": norekpen,
				"NO_REF_TRF_PLKK": noreftrfplkk	
			},
			success: function(data){
				var jdata = JSON.parse(data);
				if (jdata.ret == '0') {
					$('#btnfilter').click();
					alert('Proses berhasil!');
				} else {
					$('#btnfilter').click();
					alert(jdata.msg);
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
	}
	function act_geterror(norekpen,kodetransfer) {
		var tglawaldisplay  = $("#tglawaldisplay").val();
		var tglakhirdisplay = $("#tglakhirdisplay").val();
		var page = parseInt($("#page").val());
		var page_item = parseInt($("#page_item").val());

		var params = "tipe=act_geterror"
				+ "&kode_kantor=<?php echo $KD_KANTOR;?>"
				+ "&kode_user=<?php echo $KODE_USER;?>"
				+ "&tglawaldisplay="+tglawaldisplay
				+ "&tglakhirdisplay="+tglakhirdisplay
				+ "&page="+page
				+ "&page_item="+page_item
				+ "&norekpenerima="+norekpen	
				+ "&kodetransfer="+kodetransfer	
		//showForm('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5043K_view.php?' + params, "DETIL DATA", 720, 400, scroll);
		window.location='pn5043K_edit.php?task=Edit&source=monitor&'+params;
	}


</script>


<div id="formKiri" class="form-container">
	<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
		<input type="hidden" id="order_by" name="order_by" value="">
		<input type="hidden" id="order_type" name="order_type" value="">
		
		<div class="div-action-menu">
			<div class="menu">
				<div class="item">
					<span id="span_page_title"><?= $gs_pagetitle;?></span>	
				</div>
				<div class="item" style="float: right; padding: 2px;">		
					<input type="radio" name="fl_st_transfer" value="DLM_PROSES" onclick="filter('-02')"><span>&nbsp;<font color="#ffffff">DALAM PROSES</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<input type="radio" name="fl_st_transfer" value="SUKSES_TRF" onclick="filter('-02')"><span>&nbsp;<font color="#ffffff">SUKSES</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<input type="radio" name="fl_st_transfer" value="ERROR" onclick="filter('-02')"><span>&nbsp;<font color="#ffffff">ERROR</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
					<input type="radio" name="fl_st_transfer" value="SEMUA" onclick="filter('-02')" checked><span>&nbsp;<font color="#ffffff">SEMUA DATA</font>&nbsp;&nbsp;&nbsp;&nbsp;</span>
				</div>
			</div>
		</div>

		<div id="formframe" class="form-container">
			<div id="div_container" class="div-container" style="width:99%">
				<div class="div-body">
					<div class="div-filter">
					<?php $sql2 = "select to_char(trunc(sysdate,'MONTH'),'DD/MM/RRRR') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
											$DB->parse($sql2);
											$DB->execute();
											$row = $DB->nextrow();
											$ld_tglawaldisplay  = $row["TGLAWAL"];						
											$ld_tglakhirdisplay = $row["TGLAKHIR"];	
											?>
						<div class="div-row" style="padding-top: 2px;">
							<div class="div-col" style="padding: 2px;">			
								Tanggal Pengajuan :&nbsp;
								<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" style="width: 60px;" size="9"  >  
								<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
								<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" style="width: 60px;" size="9"  >
								<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;
								<input type="button" name="btnfilter" style="width: 100px" class="btn green" id="btnfilter" value="TAMPILKAN" onclick="filter('-02')">
							</div>
							<div class="div-col-right" style="padding: 2px;">
								<a class="a-icon-text" href="#" onclick="resetSearch()">
									<span>Reset</span>
								</a>
							</div>
							<div class="div-col-right" style="padding: 2px;">
								<a class="a-icon-text" href="#" onclick="filter('-02')" title="Klik Untuk Menampilkan Data">
								<img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
								<span>Cari</span>
								</a>
							</div>
							<div class="div-col-right" style="padding: 2px;">
								<input type="text" name="search_txt" id="search_txt" placeholder="Kata kunci.." style="width: 135px;height:23px;">
							</div>
							<div class="div-col-right" style="padding: 2px;">
								<select name="search_by" id="search_by" style="width: 124px;height:23px;">
									<option value="">-- Nama Kolom --</option>
									<option value="KODE_TRANSFER">Kode Transfer</option>
									<option value="NO_REF_PAYMENT">No Ref Payment</option>
									<option value="NO_REF_TRF_PLKK">No Ref Transfer PLKK</option>
									<option value="NAMA_REKENING_PENERIMA">Nama Penerima</option>
								</select>
							</div>
						</div>
					</div>

					<div class="div-data" style="width:99%">
						<div class="div-row">
							<div class="item full">
								<div id="div-container-table" class="item full" data-height="360" style="overflow-y: auto; min-height: 70px; max-height: 375px; height: auto;">
								<!-- <div id="div-container-table" class="item full" data-height="360" style="overflow-y: auto; min-height: 120px;"> -->
									<table class="table-data sticky" cellspacing='0'>
										<thead>
											<tr id="data_header">
												<th style="text-align:center" class="awal"></th>
												<th scope="col" width="10%">Kode Transfer</th>
												<th scope="col" width="10%" style="text-align:center">No Ref Payment</th>
												<th scope="col" width="15%" style="text-align:center">No Ref Transfer PLKK</th>
												<th scope="col" width="15%" style="text-align:center">Bank Penerima</th>
												<th scope="col" width="15%" style="text-align:center">No Rek Penerima</th>
												<th scope="col" width="15%" style="text-align:center">Nama Penerima</th>
												<th scope="col" width="15%" style="text-align:right">Nominal Netto</th>
												<th scope="col" width="15%" style="text-align:right">Nom Sdh Dibayar</th>
												<th scope="col" width="15%" style="text-align:right">Nom Sisa</th>
												<th scope="col" width="15%" style="text-align:center">Jumlah Kode Klaim</th>
												<th scope="col" width="15%" style="text-align:center">Status Transfer</th>
												<th scope="col" width="15%" style="text-align:center">Action</th>
											</tr>
											<tr id="data_header_sub">
											</tr>
										</thead>
										<tbody id="data_list">
										</tbody>
									</table>
								</div>
							</div>
						</div>
					</div>

					<div id="div_page" class="div-page" style="width:99%">
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
								<input type="hidden" id="hdn_total_records" name="hdn_total_records">
								<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
								&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
								<span style="vertical-align: middle;" >Tampilkan</span>
								<select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="filter()">
								<option value="10">10</option>
								<option value="15">15</option>
								<option value="20">20</option>
								<option value="25">25</option>
								<option value="50">50</option>
								</select>
								<span style="vertical-align: middle;" >item per halaman</span>															
							</div>
						</div>						
					</div>

				</div>

				<div class="div-footer">
					<div class="div-footer-content">
						<span><i><b>Keterangan:</b></i></span>
						<li style="margin-left:15px;">Pilih Periode Tanggal Pengajuan kemudian Klik Tombol <font color="#ff0000">TAMPILKAN</font> untuk memulai pencarian data.</li>
						<li style="margin-left:15px;">Data yang ditampilkan dapat difilter berdasarkan Status Transfer dengan memilih salah satu radiobutton.</li>
						<li style="margin-left:15px;">Data yang ditampilkan dapat difilter berdasarkan nama kolom dan kata kuncinya.</li>
					</div>
					<div class="div-row-clear"></div>
					<div class="div-row-clear"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

</script>
<?php
include "../../includes/footer_app_nosql.php";
?>