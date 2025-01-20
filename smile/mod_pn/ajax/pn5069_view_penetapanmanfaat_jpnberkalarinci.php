<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian manfaat jp berkala
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5030";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "RINCIAN MANFAAT";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_konfirmasi	= !isset($_GET['no_konfirmasi']) ? $_POST['no_konfirmasi'] : $_GET['no_konfirmasi'];
$ln_no_proses	 		= !isset($_GET['no_proses']) ? $_POST['no_proses'] : $_GET['no_proses'];
$ls_kd_prg	 			= !isset($_GET['kd_prg']) ? $_POST['kd_prg'] : $_GET['kd_prg'];
$ld_blth_proses	 	= !isset($_GET['blth_proses']) ? $_POST['blth_proses'] : $_GET['blth_proses'];
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
		<div class="item"><span id="span_page_title"></span></div>
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
			<input type="hidden" id="no_proses" name="no_proses" value="<?=$ln_no_proses;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
			<input type="hidden" id="blth_proses" name="blth_proses" value="<?=$ld_blth_proses;?>">

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
					<div class="div-row">
          	<div class="div-col" style="width:100%; max-height: 100%;">			 
              <fieldset><legend>Rincian Manfaat JP Berkala</legend>
            		<table id="tblrincian1" width="100%" width="100%" class="table-data2">
                  <thead>													
                    <tr class="hr-double-bottom">
            					<th style="text-align:center;">Manfaat</th>
            					<th style="text-align:center;">Prg</th>
            					<th style="text-align:center;">Bln Berkala</th>
                      <th style="text-align:right;">Berjalan</th>
            					<th style="text-align:right;">Rapel</th>
            					<th style="text-align:right;">Kompensasi</th>
            					<th style="text-align:right;">Jml Berkala</th>
											<th style="text-align:center;">Batal</th>
            					<th style="text-align:center;">Keterangan</th>				    							
                    </tr>
                  </thead>	
                  <tbody id="data_MnfRinci">			             																
                  </tbody>
            			<tfoot>
              			<tr>
                      <td style="text-align:right;" colspan="3"><i><b>Total Keseluruhan</b></i>
                        <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">				
              				</td>
											<td style="text-align:right;"><span id="span_tot_d_nom_berjalan"></span></td>
              				<td style="text-align:right;"><span id="span_tot_d_nom_rapel"></span></td>
              				<td style="text-align:right;"><span id="span_tot_d_nom_kompensasi"></span></td>	
              				<td style="text-align:right;"><span id="span_tot_d_nom_berkala"></span></td>				
              				<td colspan="2"></td>																					
                    </tr>
            			</tfoot>											
                </table>

              </fieldset>									
						</div>
					</div>																									
				</div>
				<!--end div_body-->
					
				<div id="div_footer" class="div-footer">
  				<span id="span_button_utama" style="display:none;">
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
		let no_proses = $('#no_proses').val();
		loadSelectedRecord(kode_klaim, no_konfirmasi, no_proses, null);
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
		return val == null ? '' : val;
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
	function loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, v_no_proses, fn)
	{
		if (v_kode_klaim == '' || v_no_konfirmasi == '' || v_no_proses == '') {
			return alert('Kode Klaim atau No. Konfirmasi atau No. Proses tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatamanfaatjpnberkaladetil',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi:v_no_konfirmasi,
				v_no_proses:v_no_proses
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						var v_blth_proses = $('#blth_proses').val();
						var v_bln = v_blth_proses.substr(0,2);
						var v_tahun = v_blth_proses.substr(3,4);
						var v_nama_blth = "";
						if (v_bln == '01'){v_nama_blth='JANUARI '+v_tahun;}
						else if (v_bln == '02'){v_nama_blth='FEBRUARI '+v_tahun;}
						else if (v_bln == '03'){v_nama_blth='MARET '+v_tahun;}
						else if (v_bln == '04'){v_nama_blth='APRIL '+v_tahun;}
						else if (v_bln == '05'){v_nama_blth='MEI '+v_tahun;}
						else if (v_bln == '06'){v_nama_blth='JUNI '+v_tahun;}
						else if (v_bln == '07'){v_nama_blth='JULI '+v_tahun;}
						else if (v_bln == '08'){v_nama_blth='AGUSTUS '+v_tahun;}
						else if (v_bln == '09'){v_nama_blth='SEPTEMBER '+v_tahun;}
						else if (v_bln == '10'){v_nama_blth='OKTOBER '+v_tahun;}
						else if (v_bln == '11'){v_nama_blth='NOVEMBER '+v_tahun;}
						else if (v_bln == '12'){v_nama_blth='DESEMBER '+v_tahun;}
						
						$("#span_page_title").html('PN5030 - RINCIAN MANFAAT PENSIUN BERKALA YANG AKAN DIBAYARKAN BULAN '+v_nama_blth);
						//$("#span_page_title_right").html('KODE KLAIM : '+v_kode_klaim);
						
						//set value rincian manfaat ----------------------------------------
						var html_data = "";
						var v_tot_d_nom_berjalan = 0;
						var v_tot_d_nom_rapel = 0;
						var v_tot_d_nom_kompensasi = 0;
						var v_tot_d_nom_berkala = 0;
						
						for(var i = 0; i < jdata.data.DATA.length; i++){							
							v_flag_batal = getValue(jdata.data.DATA[i].STATUS_BATAL) === "Y" ? "<img src=../../images/file_cancel.gif>"+" "+getValue(jdata.data.DATA[i].TGL_BATAL) : " ";
							
							html_data += '<tr>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NAMA_MANFAAT) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NM_PRG) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].BLTH_BERKALA) + '</td>';
							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.DATA[i].NOM_BERJALAN)) + '</td>';
							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.DATA[i].NOM_RAPEL)) + '</td>';
							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.DATA[i].NOM_KOMPENSASI)) + '</td>';
							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.DATA[i].NOM_BERKALA)) + '</td>';
							html_data += '<td style="text-align: right;">' + v_flag_batal + '</td>';
							html_data += '<td style="text-align: center;white-space:normal; word-wrap:break-word;">' + getValue(jdata.data.DATA[i].KETERANGAN) + '</td>';
							html_data += '</tr>';
							
							v_tot_d_nom_berjalan 	 += getValue(jdata.data.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.data.DATA[i].NOM_BERJALAN),4);
							v_tot_d_nom_rapel 	 	 += getValue(jdata.data.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.data.DATA[i].NOM_RAPEL),4);
							v_tot_d_nom_kompensasi += getValue(jdata.data.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.data.DATA[i].NOM_KOMPENSASI),4);
							v_tot_d_nom_berkala 	 += getValue(jdata.data.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.data.DATA[i].NOM_BERKALA),4);
						}
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="9" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_MnfRinci").html(html_data);
						$("#span_tot_d_nom_berjalan").html(format_uang(v_tot_d_nom_berjalan));
						$("#span_tot_d_nom_rapel").html(format_uang(v_tot_d_nom_rapel));
						$("#span_tot_d_nom_kompensasi").html(format_uang(v_tot_d_nom_kompensasi));
						$("#span_tot_d_nom_berkala").html(format_uang(v_tot_d_nom_berkala));			
						//end set value rincian manfaat ------------------------------------
						
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

<?php
include "../../includes/footer_app_nosql.php";
?>

