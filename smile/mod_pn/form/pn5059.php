<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once '../../includes/fungsi_newrpt.php';

$pagetype = "form";
$gs_pagetitle = "PN5059 - Monitoring Pengecekan Manfaat Beasiswa PP Nomor 82 Tahun 2019";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];
$periodeawal = $_GET['periodeawal'];
$periodeakhir = $_GET['periodeakhir'];
// var_dump($periodeawal );
// var_dump($periodeakhir );
// exit();

/** get current month and year */
$curr_month = date('m');
$curr_year = date('Y');
$curr_kategori = !isset($_GET['curr_kategori']) ? $_POST['curr_kategori'] : $_GET['curr_kategori'];
if ($curr_kategori=='') {
	$curr_kategori='2';
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
		setTimeout(function(){ filter(); }, 1000);
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
	});

	$('#type2').change(function(e){
				$('#search_txt2').hide();
      	$('#'+$('#type2').val()).show();
    	});

	function fl_js_get_lov_by(lov) { 
          if (lov == "KODE_KANTOR") {
              var params = "p=pn5059.php&";
              params += "&a=formreg";
              params += "&b=kode_kantor";
              params += "&c=nama_kantor";
              NewWindow('../ajax/pn5059_lov_kantor.php?' + params,'',800,500,1);
          }
      }
	function getValue(val){
		return val == null ? '' : val;
	}

	function search_by_changed(){
		$("#search_txt").val("");
	}
	function fl_js_reset_keyword2(){
				$('#KODE_TIPE_KLAIM').hide();
				$('#KODE_KONDISI_TERAKHIR').hide(); 
				$('#KODE_SEGMEN').hide();
				$('#KODE_STATUS_TINDAK_LANJUT').hide();
				$('#keyword2a').val('');
				$('#keyword2b').val('');
				$('#keyword2c').val('');
				$('#keyword2d').val(''); 
      	$('#'+$('#type2').val()).show();
				
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
	
	// function viewDetil(kode_pengajuan){
	// 	var params = "&kode_pengajuan=" + kode_pengajuan;
	// 	showFormReload('http://<?= $HTTP_HOST; ?>/mod_wr/form/wr1007_daftar_rencana_wasrik.php?' + params, "DAFTAR PENGAJUAN RENCANA KERJA WASRIK", 980, 620, scroll);
	// }

	
	function filter(val = 0){
		var pages = new Number($("#pages").val());
		var page = new Number($("#page").val());
		var page_item = $("#page_item").val();
		var kode_kantor = $("#kode_kantor").val();
		
		var search_by = $("#search_by").val();
		var search_txt = $("#search_txt").val();
		var search_by2 = $("#type2").val();
		var keyword2a = $('#keyword2a').val();
		var keyword2b = $('#keyword2b').val();
		var keyword2c = $('#keyword2c').val();
		var keyword2d = $('#keyword2d').val();
		var search_tgl = $("#search_tgl").val();

		var periodeawal = $("#periodeawal").val();
		var periodeakhir = $("#periodeakhir").val();

		 var tglawaldisplay  = $("#tglawaldisplay").val();
		 var tglakhirdisplay = $("#tglakhirdisplay").val();
		
		var datePartsawal = tglawaldisplay.split("/");	
		var dateObjectawal = new Date(+datePartsawal[2], datePartsawal[1] - 1, +datePartsawal[0]); 
		var datePartsakhir = tglakhirdisplay.split("/");	
		var dateObjectakhir = new Date(+datePartsakhir[2], datePartsakhir[1] - 1, +datePartsakhir[0]); 
	 

		if (dateObjectawal >= dateObjectakhir) {
			return alert('Tanggal awal kejadian harus lebih kecil dari tanggal akhir kejadian');
			console.log("masuk perbandingan")
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
		
		
		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5059_action.php?"+Math.random(),
			data: {
				tipe: 'select',
				page: page,
				kode_kantor:kode_kantor,
				page_item: page_item,
				search_by: search_by,
				search_txt: search_txt,
				search_by2: search_by2,
				keyword2a: keyword2a,
				keyword2b: keyword2b,
				keyword2c: keyword2c,
				keyword2d: keyword2d,
				search_tgl : search_tgl,			
				tglawaldisplay : tglawaldisplay,
				tglakhirdisplay : tglakhirdisplay				
				
			},
			success: function(data){
				var jdata = JSON.parse(data);
				//console.log(jdata);
				
				if (jdata.ret == 1){
					var html_data = "";
					for(var i = 0; i < jdata.data.length; i++){
						html_data += '<tr>';						
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].NO) + '</td>';
						html_data += '<td style="text-align: center; width:10%"><a href="#" onclick="showDetail(`'+jdata.data[i].KODE_KLAIM_INDUK+'`,`'+tglawaldisplay+'`,`'+tglakhirdisplay+'`);"><b>Lihat</b></a> ';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_WILAYAH) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_KANTOR) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].JENIS_KLAIM) + '</td>';							
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_SEGMEN) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';					
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KPJ) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].NIK_TK) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_KEJADIAN) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KLAIM_INDUK) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].TGL_BAYAR_KLAIM_INDUK) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].NO_HP_PENERIMA_MANFAAT) + '</td>';
						html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].EMAIL_PENERIMA_MANFAAT) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].FLAG_DAPAT_BEASISWA) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].JML_ANAK_PENERIMA_BEASISWA) + '</td>';
						html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].STATUS_TINDAK_LANJUT) + '</td>';					
						
										
						
						// html_data += '<td style="text-align: left;">' 
						// 			+ '<a href="javascript:void(0)" onclick="viewDetil(\'' 
						// 			+ getValue(jdata.data[i].KODE_PENGAJUAN) + '\')"><b>View</b></a>' + '</td>';
						html_data += '</tr>';
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

					resize();
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


 function showDetail(kode_klaim,periodeawal,periodeakhir){
 		window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5059_detil.php?kode_klaim='+kode_klaim+'&periodeawal='+periodeawal+'&periodeakhir='+periodeakhir);
 	}

 function downloadTemplate() {
		var tipe = 'XLS';
		var search_by = $("#search_by").val();
		var search_txt = $("#search_txt").val();
		var search_by2 = $("#type2").val();
		var keyword2a = $('#keyword2a').val();
		var keyword2b = $('#keyword2b').val();
		var keyword2c = $('#keyword2c').val();
		var keyword2d = $('#keyword2d').val();
		var search_tgl = $("#search_tgl").val();
		var tglawaldisplay  = $("#tglawaldisplay").val();
  		var tglakhirdisplay = $("#tglakhirdisplay").val();
			console.log(tglawaldisplay);
			console.log(tglakhirdisplay);
		var datePartsawal = tglawaldisplay.split("/");	
		var dateObjectawal = new Date(+datePartsawal[2], datePartsawal[1] - 1, +datePartsawal[0]); 
		var datePartsakhir = tglakhirdisplay.split("/");	
		var dateObjectakhir = new Date(+datePartsakhir[2], datePartsakhir[1] - 1, +datePartsakhir[0]); 
	 

		if (dateObjectawal >= dateObjectakhir) {
			return alert('Tanggal awal kejadian harus lebih kecil dari tanggal akhir kejadian');
			console.log("masuk perbandingan")
		} 			
    location.href = '../ajax/pn5059_download.php?mid=<?=$mid;?>&search_by='+search_by+'&search_by2='+search_by2+'&keyword2a='+keyword2a+'&keyword2b='+keyword2b+'&keyword2c='+keyword2c+'&keyword2d='+keyword2d+'&search_txt='+search_txt+'&tglawaldisplay='+tglawaldisplay+'&tglakhirdisplay='+tglakhirdisplay +'&tipe='+tipe;  
  }			
 
function downloadTemplatePDF() {

$tipe = "PDF";

let role_kode_kantor =  getValue($('#role_kode_kantor').val());	
let kode_kantor = $('#kode_kantor').val();

if (!kode_kantor){
		kode_kantor = role_kode_kantor;
	}
		var tipe = 'PDF';
		var search_by = $("#search_by").val();
		var search_txt = $("#search_txt").val();
		var search_by2 = $("#type2").val();
		var keyword2a = $('#keyword2a').val();
		var keyword2b = $('#keyword2b').val();
		var keyword2c = $('#keyword2c').val();
		var keyword2d = $('#keyword2d').val();
		var search_tgl = $("#search_tgl").val();
		var tglawaldisplay  = $("#tglawaldisplay").val();
  		var tglakhirdisplay = $("#tglakhirdisplay").val();
		var datePartsawal = tglawaldisplay.split("/");	
		var dateObjectawal = new Date(+datePartsawal[2], datePartsawal[1] - 1, +datePartsawal[0]); 
		var datePartsakhir = tglakhirdisplay.split("/");	
		var dateObjectakhir = new Date(+datePartsakhir[2], datePartsakhir[1] - 1, +datePartsakhir[0]); 
	 

		if (dateObjectawal >= dateObjectakhir) {
			return alert('Tanggal awal kejadian harus lebih kecil dari tanggal akhir kejadian');
			console.log("masuk perbandingan")
		} 			

		NewWindow('../ajax/pn5059_downloadpdf.php?mid=<?=$mid;?>&search_by='+search_by+'&search_by2='+search_by2+'&keyword2a='+keyword2a+'&keyword2b='+keyword2b+'&keyword2c='+keyword2c+'&keyword2d='+keyword2d+'&search_txt='+search_txt+'&tglawaldisplay='+tglawaldisplay+'&tglakhirdisplay='+tglakhirdisplay+'&kode_kantor='+kode_kantor + '&tipe='+tipe,'',800,500,1);
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
					<input type='hidden'  id='periodeakhir' value='<?php echo $periodeakhir;?>'/>
					<input type='hidden'  id='periodeawal' value='<?php echo $periodeawal;?>'/>
					<input type="hidden" name="role_kode_kantor" id="role_kode_kantor" value="<?=$KD_KANTOR?>">
					<div id="div_dummy_data" style="width: 100%;"></div>
										
					<div id="div_filter">
						<div style="padding-top: 10px;">
							<div style="float: left;width: 100%;">

								<div style="float: right;">
								<div style="min-width: 50px; display: inline-block; text-align: right;">Kode Kantor : </div>
									<input type="text" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" size="10" maxlength="20" readonly class="disabled" style="width:30px;">
									<a href="#" onclick="fl_js_get_lov_by('KODE_KANTOR')"><img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>
									<input type="text" id="nama_kantor" name="nama_kantor" value="<?=$ls_nama_kantor;?>" size="40" readonly class="disabled" style="width:100px;">
									<div style="min-width: 50px; display: inline-block; text-align: right;">Search by : </div>
									<select name="search_by" id="search_by" style="width: 136px;" onchange="search_by_changed()">
										<option value="">-- Pilih --</option>										
										<option value="sc_kode_klaim">Kode Klaim Induk</option>	
										<option value="sc_no_kpj">No. KPJ</option>
										<option value="sc_nama_tk">Nama Tenaga Kerja</option>
										<!-- <option value="sc_kd_kc">Kode Kantor Cabang</option>
										<option value="sc_kd_kw">Kode Kantor Wilayah</option>													 -->
									</select>
									<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="width: 100px;">
									<select id="type2" name="type2" onclick="fl_js_reset_keyword2();">
										<option value="">-- Keyword Lain --</option>
										<option value="">----------------</option>
										<option value="KODE_SEGMEN">Segmen Kepesertaan</option> 
										<option value="KODE_TIPE_KLAIM">Tipe Klaim</option>
										<option value="KODE_KONDISI_TERAKHIR">Kondisi Akhir</option>
										<option value="KODE_STATUS_TINDAK_LANJUT">Status Tindak Lanjut</option>                       
									</select>
									<span id="KODE_TIPE_KLAIM" hidden="">
										<select size="1" id="keyword2a" name="keyword2a" value="" class="select_format" style="width:100px;">
											<option value="">-- Pilih --</option>
											<? 
											$sql = "select kode_tipe_klaim,nama_tipe_klaim from sijstk.pn_kode_tipe_klaim where kode_tipe_klaim in ('JKK01','JKM01','JHM01') order by kode_tipe_klaim";
											$DB->parse($sql);
											$DB->execute();
											while($row = $DB->nextrow())
											{
											echo "<option ";
											if ($row["KODE_TIPE_KLAIM"]==$ls_list_kode_tipe_klaim && strlen($ls_list_kode_tipe_klaim)==strlen($row["KODE_TIPE_KLAIM"])){ echo " selected"; }
											echo " value=\"".$row["KODE_TIPE_KLAIM"]."\">".$row["NAMA_TIPE_KLAIM"]."</option>";
											}
											?>
										</select>							
									</span>
									<span id="KODE_STATUS_TINDAK_LANJUT" hidden="">
										<select size="1" id="keyword2d" name="keyword2d" value="" class="select_format" style="width:100px;">
											<option value="">-- Pilih --</option>
											<option value="Y">Sudah Ditindaklanjuti</option>
											<option value="T">Belum Ditindaklanjuti</option>
										</select>							
									</span>
									<span id="KODE_SEGMEN" hidden="">
										<select size="1" id="keyword2c" name="keyword2c" value="" class="select_format" style="width:100px;">
											<option value="">-- Pilih --</option>
											<? 
											$sql = "select kode_segmen,nama_segmen from sijstk.kn_kode_segmen where kode_segmen <> 'TKI' order by kode_segmen";
											$DB->parse($sql);
											$DB->execute();
											while($row = $DB->nextrow())
											{
											echo "<option ";
											if ($row["KODE_SEGMEN"]==$ls_list_kode_segmen && strlen($ls_list_kode_segmen)==strlen($row["KODE_SEGMEN"])){ echo " selected"; }
											echo " value=\"".$row["KODE_SEGMEN"]."\">".$row["NAMA_SEGMEN"]."</option>";
											}
											?>
										</select>							
									</span>	
									<span id="KODE_KONDISI_TERAKHIR" hidden="">
										<select size="1" id="keyword2b" name="keyword2b" value="" class="select_format" style="width:100px;">
											<option value="">-- Pilih --</option>
											<? 
											$sql = "SELECT * FROM PN.PN_KODE_KONDISI_TERAKHIR WHERE KODE_KONDISI_TERAKHIR IN ('KA05', 'KA06')";
											$DB->parse($sql);
											$DB->execute();
											while($row = $DB->nextrow())
											{
											echo "<option ";
										
											echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
											}
											?>
										</select>							
									</span>	
									<input type="button" name="btnfilter" style="width: 100px" class="btn green" id="btnfilter" value="Tampilkan" onclick="filter()"/>
								</div>
								<div style="float: left; padding: 2px;">
									<?PHP	 
										// var_dump($periodeawal);
										// var_dump($periodeakhir);
										// exit();

									if ($periodeawal=="" && $periodeakhir=="")//tampilkan dari 1 hari sebelumnya
									{
										$sql2 = "select to_char(to_date('02-DEC-2019'),'dd/mm/yyyy') tglawal, to_char(sysdate,'dd/mm/yyyy') tglakhir from dual";		
										$DB->parse($sql2);
										$DB->execute();
										$row = $DB->nextrow();
										$ld_tglawaldisplay  = $row["TGLAWAL"];						
										$ld_tglakhirdisplay = $row["TGLAKHIR"];						
									}else{
										$ld_tglawaldisplay  =  $periodeawal;
										$ld_tglakhirdisplay = $periodeakhir;
									}

									/*var_dump($ld_tglawaldisplay); 
										var_dump($ld_tglawaldisplay); onblur="convert_date(tglawaldisplay)"
										exit();*/
									?>				
									Tgl Kejadian &nbsp;
									<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" style="width: 60px;" size="9"  >  
									<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglawaldisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp; s/d &nbsp;
									<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" style="width: 60px;" size="9"  >
									<input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tglakhirdisplay', 'dd-mm-y');" src="../../images/calendar.gif" />&nbsp;&nbsp;
											
								</div>
								
								<!-- <div id="divSearch" style="float: right; padding: 2px;">
									<input type="text" name="search_txt" id="search_txt" placeholder="Keyword" style="width: 200px">
								</div> -->
								<!-- <div style="float: right; padding: 2px;">
									<div style="min-width: 60px; display: inline-block; text-align: right;"><b>Search by : </b></div>
									<select name="search_kode" id="search_kode" style="width: 150px;" onchange="search_by_changed()">
										<option value="">-- Pilih--</option>
										<option value="sc_kodevendor">Kode Vendor</option>
										<option value="sc_namavendor">Nama Vendor</option>
										<option value="sc_statusreg">Status Registrasi</option>
									</select>
								</div> -->
							</div>
						</div> 
					</div>
						<div id="div_data" style="overflow-x: auto; overflow-y: auto; white-space: nowrap;">
							<div style="padding: 6px 0px 0px 0px;">
								<table class="table-data hover">
									<thead>
										<tr class="hr-double">										
										<th style="text-align: center;">No</th>
										<th style="text-align: center;">Aksi</th>											
										<th style="text-align: center;">Kode Wilayah</th>
										<th style="text-align: center;">Kode Kantor Cabang</th>
										<th style="text-align: center;">Nama Kantor Cabang</th>
										<th style="text-align: center;">Jenis Klaim</th>
										<th style="text-align: center;">Segmen Kepesertaan</th>
										<th style="text-align: center;">Nama TK</th>
										<th style="text-align: center;">Nomor KPJ</th>
										<th style="text-align: center;">NIK TK</th>
										<th style="text-align: center;">Tanggal Kejadian</th>
										<th style="text-align: center;">Kode Klaim Induk</th>
										<th style="text-align: center;">Tanggal Bayar Klaim</th>
										<th style="text-align: center;">No. HP Penerima Manfaat</th>
										<th style="text-align: center;">Email Penerima Manfaat</th>
										<th style="text-align: center;">Hasil Pengecekan Beasiswa</th>
										<th style="text-align: center;">Jumlah Anak Penerima Beasiswa</th>
										<th style="text-align: center;">Status Tindak Lanjut</th>
																								
									</thead>
									<tbody id="data_list">
										<tr class="nohover-color">
											<td colspan="18" style="text-align: center;">-- Data tidak ditemukan --</td>
										</tr>
									</tbody>
								</table>   
							</div>
						</div> 
						<div id="div_pagex" style="padding-bottom: 26px;">
						<div style="padding-top: 8px;">
								<div style="float: left;">
									
								</div>								
								<div style="float: right">
									 <input type="button" name="btnfilter4" style="width: 120px" class="btn green" id="btnfilter4" value="DOWNLOAD EXCEL" onclick="downloadTemplate()"/>
									 &nbsp;
									 &nbsp;
									 <input type="button" name="btnfilter4" style="width: 120px" class="btn green" id="btnfilter4" value="DOWNLOAD PDF" onclick="downloadTemplatePDF()"/>
								</div>
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
									<select name="page_item" id="page_item" style="width: 50px;" onchange="filter()">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>								
							</div>
						</div>
						<div id="div_footer" class="div-footer">
						</div>
					
				</div>
				<div id="div_footer" style="height: 120px; border-bottom: 1px solid #eeeeee;">
					
						<div style="background: #f2f2f2;padding:10px 20px;border:1px solid #ececec;">
							<span><b>DISCLAMERS:</b></span>
							<li style="margin-left:15px;">Data klaim yang tercantum dalam monitoring ini, tidak semuanya berhak mendapatkan manfaat beasiswa PP Nomor 82 Tahun 2019.</li>
							<li style="margin-left:15px;">Monitoring ini hanya sebagai alat bantu untuk memudahkan Kantor Cabang dalam melakukan pengecekan data dan sekaligus sebagai bukti tindak lanjut atas estimasi hutang jaminan beasiswa PP Nomor 82 Tahun 2019.</li>
							<li style="margin-left:15px;">Kantor Cabang tetap harus melakukan verifikasi sesuai dengan ketentuan peraturan perundang-undangan, sebelum melakukan pembayaran manfaat beasiswa PP Nomor 82 Tahun 2019.</li>
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