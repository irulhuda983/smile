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
$gs_pagetitle 	 	= "RINCIAN MANFAAT";											 
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

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset><legend>Manfaat Pensiun Berkala</legend>
                <table id="tblMnfBklRekap" width="100%" class="table-data2">
                  <thead>								
                    <tr class="hr-double-bottom">
                      <th style="text-align:center;">Program</th>
                      <th style="text-align:center;">Bln ke-</th>
                      <th style="text-align:center;">Bulan</th>
                      <th style="text-align:right;">Berjalan</th>
            					<th style="text-align:right;">Rapel</th>
            					<th style="text-align:right;">Kompensasi</th>
            					<th style="text-align:right;">Jumlah Berkala</th>
            					<th style="text-align:right;">Sudah Dibayar</th>
            					<th style="text-align:right;">Dikompensasi</th>
											<th style="text-align:center;">Batal</th>
                      <th style="text-align:center;">Action</th>
                    </tr>
									</thead>	
									<tbody id="data_tblMnfBklRekap">											             																
                  </tbody>
									<tfoot>
              			<tr>
                      <td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i>
                        <input type="hidden" id="d_mnfbklrkp_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="d_mnfbklrkp_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="d_mnfbklrkp_showmessage" style="border-width: 0;text-align:right" readonly size="5">
              				</td>	  		
              				<td style="text-align:right;"><span id="span_tot_d_nom_berjalan"></span></td>
              				<td style="text-align:right;"><span id="span_tot_d_nom_rapel"></span></td>							
                      <td style="text-align:right;"><span id="span_tot_d_nom_kompensasi"></span></td>
              				<td style="text-align:right;"><span id="span_tot_d_nom_berkala"></span></td>
              				<td style="text-align:right;"><span id="span_tot_d_nom_dibayar"></span></td>
              				<td style="text-align:right;"><span id="span_tot_d_nom_dikompensasi"></span></td>
              				<td colspan="2"></td>										        
                    </tr>
									</tfoot>																
                </table>
              </fieldset>
            	
            	</br>
            	
              <fieldset><legend>Penerima Manfaat Berkala</legend>
                <table id="tblPnrmBklRekap" width="100%" class="table-data2">
                  <thead>								
                    <tr class="hr-double-bottom">
                      <th style="text-align:center;">Tipe</th>
            					<th style="text-align:center;">Hubungan</th>
                      <th style="text-align:center;">Nama</th>
                      <th style="text-align:center;">NPWP</th>
                      <th style="text-align:center;">Bank</th>
            					<th style="text-align:center;">No.Rek</th>
            					<th style="text-align:center;">A/N</th>
            					<th style="text-align:right;">Nominal</th>
											<th style="text-align:center;">Layanan Notifikasi</th>
                      <th style="text-align:center;">Action</th>
                    </tr>
									</thead>
									<tbody id="data_tblPnrmBklRekap">							             																
                  </tbody>
            			<tfoot>
  									<tr>
                      <td style="text-align:right" colspan="7"><i>Total Diterima :<i>
                        <input type="hidden" id="d_pnrmbklrekap_kounter_dtl" name="d_pnrmbklrekap_kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="d_pnrmbklrekap_count_dtl" name="d_pnrmbklrekap_count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="d_pnrmbklrekap_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
                      </td>
                      <td style="text-align:right;"><span id="span_pnrm_tot_d_jpnbkala_nom_berkala"></span></td>
              				<td>				
              				</td>				
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
				tipe: 'fjq_ajax_val_getdatarekapberkalabynokonfirmasi',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi:v_no_konfirmasi,
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - RINCIAN MANFAAT PENSIUN BERKALA');
						$("#span_page_title_right").html('NO KONFIRMASI : '+v_kode_klaim+' - '+v_no_konfirmasi);
						
            //set data manfaat jp berkala --------------------------------------
            var html_mnfbkl = "";
						var v_tot_d_nom_berjalan = 0;
						var v_tot_d_nom_rapel = 0;
						var v_tot_d_nom_kompensasi  = 0;
            var v_tot_d_nom_berkala = 0;
            var v_tot_d_nom_dibayar = 0;
            var v_tot_d_nom_dikompensasi = 0;				
							
            if (jdata.dataMnfJpBklRekap.DATA)
            { 
              for(var i = 0; i < jdata.dataMnfJpBklRekap.DATA.length; i++)
              {
                v_flag_batal = getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? "<img src=../../images/file_cancel.gif>"+" "+getValue(jdata.dataMnfJpBklRekap.DATA[i].TGL_BATAL) : "";
								
								html_mnfbkl += '<tr>';
                html_mnfbkl += '<td style="text-align: center;">' + getValue(jdata.dataMnfJpBklRekap.DATA[i].NM_PRG) + '</td>';
                html_mnfbkl += '<td style="text-align: center;">' + getValue(jdata.dataMnfJpBklRekap.DATA[i].NO_PROSES) + '</td>';
                html_mnfbkl += '<td style="text-align: center;">' + getValue(jdata.dataMnfJpBklRekap.DATA[i].BLTH_PROSES) + '</td>';
                html_mnfbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataMnfJpBklRekap.DATA[i].NOM_BERJALAN)) + '</td>';
                html_mnfbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataMnfJpBklRekap.DATA[i].NOM_RAPEL)) + '</td>';
								html_mnfbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataMnfJpBklRekap.DATA[i].NOM_KOMPENSASI)) + '</td>';
								html_mnfbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataMnfJpBklRekap.DATA[i].NOM_BERKALA)) + '</td>';
								html_mnfbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataMnfJpBklRekap.DATA[i].NOM_DIBAYAR)) + '</td>';
								html_mnfbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataMnfJpBklRekap.DATA[i].NOM_DIKOMPENSASI)) + '</td>';
								html_mnfbkl += '<td style="text-align: center;">' + v_flag_batal + '</td>';
                html_mnfbkl += '<td style="text-align: center;">' 
                            + '<a href="javascript:void(0)" onclick="fl_js_tap_mnf_rinci_berkala(\'' 
                            + getValue(jdata.dataMnfJpBklRekap.DATA[i].KODE_KLAIM) + '\', \'' 
                            + getValue(jdata.dataMnfJpBklRekap.DATA[i].NO_KONFIRMASI) + '\', \'' 
                            + getValue(jdata.dataMnfJpBklRekap.DATA[i].NO_PROSES) + '\', \'' 
														+ getValue(jdata.dataMnfJpBklRekap.DATA[i].KD_PRG) + '\', \'' 
                            + getValue(jdata.dataMnfJpBklRekap.DATA[i].BLTH_PROSES) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN </font></a>' + '</td>';
                html_mnfbkl += '</tr>';
                
                v_tot_d_nom_berjalan 	 	 += getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.dataMnfJpBklRekap.DATA[i].NOM_BERJALAN),4);
								v_tot_d_nom_rapel 			 += getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.dataMnfJpBklRekap.DATA[i].NOM_RAPEL),4);
                v_tot_d_nom_kompensasi   += getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.dataMnfJpBklRekap.DATA[i].NOM_KOMPENSASI),4);
                v_tot_d_nom_berkala 		 += getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.dataMnfJpBklRekap.DATA[i].NOM_BERKALA),4);
                v_tot_d_nom_dibayar 		 += getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.dataMnfJpBklRekap.DATA[i].NOM_DIBAYAR),4);
                v_tot_d_nom_dikompensasi += getValue(jdata.dataMnfJpBklRekap.DATA[i].STATUS_BATAL) === "Y" ? 0 : parseFloat(getValueNumber(jdata.dataMnfJpBklRekap.DATA[i].NOM_DIKOMPENSASI),4);
              }
              
              if (html_mnfbkl == "") {
                html_mnfbkl += '<tr class="nohover-color">';
                html_mnfbkl += '<td colspan="11" style="text-align: center;">-- data tidak ditemukan --</td>';
                html_mnfbkl += '</tr>';
              }
              $("#data_tblMnfBklRekap").html(html_mnfbkl);	
              
              $("#span_tot_d_nom_berjalan").html(format_uang(v_tot_d_nom_berjalan));
              $("#span_tot_d_nom_rapel").html(format_uang(v_tot_d_nom_rapel));
							$("#span_tot_d_nom_kompensasi").html(format_uang(v_tot_d_nom_kompensasi));	
							$("#span_tot_d_nom_berkala").html(format_uang(v_tot_d_nom_berkala));	
							$("#span_tot_d_nom_dibayar").html(format_uang(v_tot_d_nom_dibayar));	
							$("#span_tot_d_nom_dikompensasi").html(format_uang(v_tot_d_nom_dikompensasi));				
            }
            //end set data manfaat jp berkala ----------------------------------						

            //set data penerima manfaat jp berkala -----------------------------
            var html_pnrmbkl = "";
            var v_tot_d_pnrm_nom_berkala = 0;			
							
            if (jdata.dataPnrmJpBklRekap.DATA)
            { 
              for(var i = 0; i < jdata.dataPnrmJpBklRekap.DATA.length; i++)
              {
								html_pnrmbkl += '<tr>';
                html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].NAMA_TIPE_PENERIMA) + '</td>';
                html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].NAMA_KODE_PENERIMA_BERKALA) + '</td>';
                html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].NAMA_LENGKAP) + '</td>';
								html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].NPWP) + '</td>';
								html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].BANK_PENERIMA) + '</td>';
								html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].NO_REKENING_PENERIMA) + '</td>';
								html_pnrmbkl += '<td style="text-align: center;">' + getValue(jdata.dataPnrmJpBklRekap.DATA[i].NAMA_REKENING_PENERIMA) + '</td>';
                html_pnrmbkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataPnrmJpBklRekap.DATA[i].NOM_BERKALA)) + '</td>';
								
								var v_pnrm_status_cek_layanan = getValue(jdata.dataPnrmJpBklRekap.DATA[i].STATUS_CEK_LAYANAN) =="null" ? "T" : getValue(jdata.dataPnrmJpBklRekap.DATA[i].STATUS_CEK_LAYANAN);
								var v_pnrm_status_reg_notifikasi = getValue(jdata.dataPnrmJpBklRekap.DATA[i].STATUS_REG_NOTIFIKASI) =="null" ? "T" : getValue(jdata.dataPnrmJpBklRekap.DATA[i].STATUS_REG_NOTIFIKASI);
								
								if (v_pnrm_status_cek_layanan=='Y')
								{
								 	if (v_pnrm_status_reg_notifikasi=='Y')
									{
									 	v_pnrm_ket_status_reg_notifikasi = 'YA';
									}else
									{
									 	v_pnrm_ket_status_reg_notifikasi = 'TIDAK';
									}
								}else
								{
								 	v_pnrm_ket_status_reg_notifikasi = '-';	 
								}
								
								html_pnrmbkl += '<td style="text-align: center;">' + v_pnrm_ket_status_reg_notifikasi + '</td>';
								html_pnrmbkl += '<td style="text-align: center;">' 
                            + '<a href="javascript:void(0)" onclick="fl_js_tap_penerima_rinci_berkala(\'' 
                            + getValue(jdata.dataPnrmJpBklRekap.DATA[i].KODE_KLAIM) + '\', \'' 
                            + getValue(jdata.dataPnrmJpBklRekap.DATA[i].KODE_PENERIMA_BERKALA) + '\')"><img src="../../images/user_go.png" border="0" alt="Rincian Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN </font></a>' + '</td>';
                html_pnrmbkl += '</tr>';

                v_tot_d_pnrm_nom_berkala += parseFloat(getValueNumber(jdata.dataPnrmJpBklRekap.DATA[i].NOM_BERKALA),4);
              }
              
              if (html_pnrmbkl == "") {
                html_pnrmbkl += '<tr class="nohover-color">';
                html_pnrmbkl += '<td colspan="10" style="text-align: center;">-- data tidak ditemukan --</td>';
                html_pnrmbkl += '</tr>';
              }
              $("#data_tblPnrmBklRekap").html(html_pnrmbkl);	
              
							$("#span_pnrm_tot_d_jpnbkala_nom_berkala").html(format_uang(v_tot_d_pnrm_nom_berkala));			
            }
            //end set data penerima manfaat jp berkala -------------------------
												
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
	function fl_js_tap_mnf_rinci_berkala(p_kode_klaim, p_no_konfirmasi, p_no_proses, p_kd_prg, p_blth_proses)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalarinci.php?kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&no_proses='+p_no_proses+'&kd_prg='+p_kd_prg+'&blth_proses='+p_blth_proses+'&mid='+c_mid+'','',980,550,'yes');
	}
	function fl_js_tap_penerima_rinci_berkala(p_kode_klaim, p_kode_penerima_berkala)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalapenerima.php?kode_klaim='+p_kode_klaim+'&kode_penerima_berkala='+p_kode_penerima_berkala+'&mid='+c_mid+'','',980,640,'yes');
	}	
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

