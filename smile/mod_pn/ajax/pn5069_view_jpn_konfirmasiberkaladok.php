<?php
//------------------------------------------------------------------------------
// Menu untuk display rekap manfaat jp berkala per no-konfirmasi
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5030";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "DOKUMEN KELENGKAPAN ADMINISTRASI KONFIRMASI JP BERKALA";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_konfirmasi	= !isset($_GET['no_konfirmasi']) ? $_POST['no_konfirmasi'] : $_GET['no_konfirmasi'];
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_pagetitle;?></span></div>
		<div class="item" style="float: right; padding: 2px;">
			<font color="#ffffff"><span id="span_page_title_right"></span></font>	 				
		</div>		
	</div>
</div>

<div id="formframe" style="min-width:80%;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset style="min-height:226px;"><legend>Dokumen Kelengkapan Administrasi</legend>
                <table id="tblrincianDok" width="100%" class="table-data2">
                  <thead>
                    <tr class="hr-double-bottom">		 
                      <th style="text-align:center;">No</th>
                      <th style="text-align:center;">Nama Dokumen</th>
                      <th style="text-align:center;">Mandatory</th>
                      <th style="text-align:center;">Action</th>
                      <th style="text-align:center;">Status</th>
                      <th style="text-align:center;">Tanggal</th>
                      <th style="text-align:center;">File</th>	
                    </tr>	 
                  </thead>
          				<tbody id="data_list_Dok">												
                    <tr class="nohover-color">
  									<td colspan="7" style="text-align: center;">-- tidak ada dokumen --</td>
  								</tr>							             																
                  </tbody>
          				<tfoot>
                    <tr>
                      <td style="text-align:center" colspan="7"></td>
                    </tr>
          				</tfoot>																						
                </table>									
              </fieldset>
						</div>
					</div>																										
				</div>
				<!--end div_body-->
					
				<div id="div_footer" class="div-footer">
  				<span id="span_button_utama" style="display:block;">
            <div style="padding-top:6px;width:99%;">
  						<div class="div-footer-content">
  							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
  							<li style="margin-left:15px;">Klik tombol <font color="#ff0000"> <b>X</b> </font> pada pojok kanan atas untuk menutup form dan kembali ke menu utama.</li>
  						</div>
            </div>
  				</span><!--end span_button_utama-->					
				</div>
				<!--end div_footer--> 
			</div>							
		</form>	 
	</div>	 
</div>

<script language="javascript">
	//template -------------------------------------------------------------------
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

    $(window).bind("resize", function(){
			resize();
		});
		
		resize();
		
		let kode_klaim = $('#kode_klaim').val();
		let no_konfirmasi = $('#no_konfirmasi').val();
		
		loadSelectedRecord(kode_klaim, no_konfirmasi, null);
	});

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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-x:hidden; overflow-y:hidden;" scrolling=="'+scroll+'"></iframe>',
			listeners: {
				close: function () {
					// filter();
				},
				destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function getValue(val){
		return val == null ? '' : val;
	}

	function getValueNumber(val){
		return val == null ? '0' : val;
	}
				
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
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
	//end template ---------------------------------------------------------------			
</script>

<script language="javascript">
	function loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, fn)
	{
		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			return alert('Kode Klaim atau No Konfirmasi tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getlistdokkonfirmasijpberkala',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi:v_no_konfirmasi,
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - DOKUMEN KELENGKAPAN ADMINISTRASI KONFIRMASI JP BERKALA');
						$("#span_page_title_right").html('NO KONFIRMASI : '+v_kode_klaim+' - '+v_no_konfirmasi);
						
            //set data dokumen jp berkala --------------------------------------
						var html_data_Dok = "";
						var v_nama_dokumen = "";
						var v_nama_file = "";
						
						if (jdata.data.DATA)
						{ 
  						for(var i = 0; i < jdata.data.DATA.length; i++)
  						{
  							v_nama_dokumen = getValue(jdata.data.DATA[i].NAMA_DOKUMEN);
								v_nama_file = getValue(jdata.data.DATA[i].NAMA_FILE);
								v_flag_mandatory = getValue(jdata.data.DATA[i].FLAG_MANDATORY) === "Y" ? "<img src=../../images/file_apply.gif>" : " "; 
								v_status_diserahkan = getValue(jdata.data.DATA[i].STATUS_DISERAHKAN) === "Y" ? "checked" : " "; 
								v_item_status_diserahkan = '<input type="checkbox" disabled class="cebox" id=dcb_dok_status_diserahkan'+i+' name=dcb_dok_status_diserahkan'+i+' '+v_status_diserahkan+' ';
								
								html_data_Dok += '<tr>';
  							html_data_Dok += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NO_URUT) + '</td>';
								html_data_Dok += '<td style="text-align: left; white-space:normal; word-wrap:break-word;">' + v_nama_dokumen + '</td>';
								html_data_Dok += '<td style="text-align: center;">' + v_flag_mandatory + '</td>';
								html_data_Dok += '<td style="text-align: center;"></td>';
								html_data_Dok += '<td style="text-align: center;">' + v_item_status_diserahkan + '</td>';
								html_data_Dok += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].TGL_DISERAHKAN) + '</td>';
								html_data_Dok += '<td style="text-align: center; white-space:normal; word-wrap:break-word;">' 
																 + '<a href="javascript:void(0)" onclick="fl_js_DownloadDok(\''
																 + getValue(jdata.data.DATA[i].URL) + '\', \''  
																 + getValue(jdata.data.DATA[i].NAMA_FILE) + '\')"> '+ v_nama_file +'</a>' + '</td>';					 
								html_data_Dok += '<tr>';
  						}
  															
  						if (html_data_Dok == "") {
  							html_data_Dok += '<tr class="nohover-color">';
  							html_data_Dok += '<td colspan="7" style="text-align: center;">-- tidak ada dokumen --</td>';
  							html_data_Dok += '</tr>';
  						}
  						$("#data_list_Dok").html(html_data_Dok);		
						}								
            //end set data dokumen jp berkala ----------------------------------						
																	
						//set button -------------------------------------------------------			
						window.document.getElementById("span_button_utama").style.display = 'block';
						//end set button ---------------------------------------------------						
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!!");
				asyncPreload(false);
			}
		});						
	}
	//end loadSelectedRecord -----------------------------------------------------
</script>

<script language="javascript">
	function fl_js_DownloadDok(v_url, v_nmfile)
	{
		let p = btoa(v_url);
		let f = btoa(v_nmfile);
		let u = btoa('<?=$gs_kode_user;?>');
		let params = 'p='+p+'&f='+f+'&u='+u;
		NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5065_download_dok.php?' + params,'',1000,620,'no');
	}
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

