<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian manfaat pmi pemulangan
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 = "form";
$gs_kodeform 	 	 = "PN5030";
$chId 	 	 			 = "SMILE";
$gs_pagetitle 	 = "RINCIAN MANFAAT";											 
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_user		 = $_SESSION["USER"];
$gs_kode_role		 = $_SESSION['regrole'];
$task 					 = $_POST["task"];
$editid 				 = $_POST['editid'];
$ls_kode_klaim 	 = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ls_kode_manfaat = !isset($_POST['kode_manfaat']) ? $_GET['kode_manfaat'] : $_POST['kode_manfaat'];
$ln_no_urut 		 = !isset($_POST['no_urut']) ? $_GET['no_urut'] : $_POST['no_urut'];
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
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <fieldset style="width:97%;"><legend>Rincian Manfaat</legend>
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Tipe Penerima</label>
              <input type="text" id="nama_tipe_penerima" name="nama_tipe_penerima" readonly class="disabled" style="width:270px;">
          		<input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima">
							<input type="hidden" id="kode_segmen" name="kode_segmen">
            </div>													
            <div class="clear"></div>
					
  					<div class="form-row_kiri">
            <label style = "text-align:right;">&nbsp;</label>
  						</br>	 
  						<table id="tblrincian1" width="80%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
  							<tbody>
  								<tr>
                    <th align="center" style="width:200px;"><font size="1">&nbsp;</font></th>
  									<th align="center"><font size="1">Biaya Diajukan</font></th>
                    <th align="center"><font size="1">Biaya Hasil Verifikasi</font></th>
                    <th align="center"><font size="1">Biaya Disetujui</font></th>
                  </tr>
  								<tr>
  									<td>Biaya Pengangkutan Udara &nbsp;</td>
  									<td><input type="text" autocomplete="off" id="transport_udara_diajukan" name="transport_udara_diajukan" size="20" style="text-align:right;" readonly class="disabled"></td>
  									<td><input type="text" autocomplete="off" id="transport_udara_verifikasi" name="transport_udara_verifikasi" size="20" style="text-align:right;" readonly class="disabled"></td>
  									<td><input type="text" id="transport_udara_disetujui" name="transport_udara_disetujui" size="20" style="text-align:right;" readonly class="disabled">
      									<input type="hidden" autocomplete="off" id="transport_darat_diajukan" name="transport_darat_diajukan">
      									<input type="hidden" autocomplete="off" id="transport_darat_verifikasi" name="transport_darat_verifikasi">
      									<input type="hidden" id="transport_darat_disetujui" name="transport_darat_disetujui">
      									<input type="hidden" autocomplete="off" id="transport_laut_diajukan" name="transport_laut_diajukan">
      									<input type="hidden" autocomplete="off" id="transport_laut_verifikasi" name="transport_laut_verifikasi">
      									<input type="hidden" id="transport_laut_disetujui" name="transport_laut_disetujui">
  									</td>	
  								</tr>
  								<tr>
  									<td colspan="4">	
  										<hr/>
  									</td>	
  								</tr>
  								<tr>
  									<td><i>Total Biaya &nbsp;</i></td>
  									<td><input type="text" id="biaya_total_diajukan" name="biaya_total_diajukan" size="20" style="text-align:right;" readonly class="disabled"></td>	
  									<td><input type="text" id="biaya_total_verifikasi" name="biaya_total_verifikasi" size="20" style="text-align:right;" readonly class="disabled"></td>	
  									<td><input type="text" id="biaya_total_disetujui" name="biaya_total_disetujui" size="20" style="text-align:right;" readonly class="disabled"></td>	
  								</tr>																									 	
  							</tbody>			 
  						</table>
  						</br></br>	 
  					</div>
            <div class="clear"></div>																
  					
            <div class="form-row_kiri">
            <label style = "text-align:right;">Catatan &nbsp;</label>
            	<textarea cols="255" rows="1" style="width:220px;height:15px;background-color:#f5f5f5;" id="keterangan" name="keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>   					
            </div>								
            <div class="clear"></div>
					
          </fieldset>																											
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
		let kode_manfaat = $('#kode_manfaat').val();
		let no_urut = $('#no_urut').val();
		loadSelectedRecord(kode_klaim, kode_manfaat,no_urut, null);
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
	function loadSelectedRecord(v_kode_klaim, v_kode_manfaat, v_no_urut, fn)
	{
		if (v_kode_klaim == '' || v_kode_manfaat == '' || v_no_urut == '') {
			return alert('Kode Klaim atau Kode Manfaat atau No Urut Manfaat tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatamanfaatdetil',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat,
				v_no_urut:v_no_urut
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - RINCIAN MANFAAT - '+getValue(jdata.data.DATA['NAMA_MANFAAT']));
						$("#span_page_title_right").html('NO. RINCIAN : '+getValue(jdata.data.DATA['KODE_KLAIM'])+' - '+v_kode_manfaat+' - '+v_no_urut);
						
						//set value rincian manfaat ----------------------------------------
						$('#nama_tipe_penerima').val(getValue(jdata.data.DATA['NAMA_TIPE_PENERIMA']));
						$('#kode_tipe_penerima').val(getValue(jdata.data.DATA['KODE_TIPE_PENERIMA']));
						$('#kode_segmen').val(getValue(jdata.data.DATA['KODE_SEGMEN']));
						
						$('#transport_udara_diajukan').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_UDARA_DIAJUKAN'])));
						$('#transport_udara_verifikasi').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_UDARA_VERIFIKASI'])));
						$('#transport_udara_disetujui').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_UDARA_DISETUJUI'])));
						
						$('#transport_darat_diajukan').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_DARAT_DIAJUKAN'])));
						$('#transport_darat_verifikasi').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_DARAT_VERIFIKASI'])));
						$('#transport_darat_disetujui').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_DARAT_DISETUJUI'])));
						
						$('#transport_laut_diajukan').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_LAUT_DIAJUKAN'])));
						$('#transport_laut_verifikasi').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_LAUT_VERIFIKASI'])));
						$('#transport_laut_disetujui').val(format_uang(getValueNumber(jdata.data.DATA['TRANSPORT_LAUT_DISETUJUI'])));
						
						var v_biaya_total_diajukan = 0;
						var v_biaya_total_verifikasi = 0;
						var v_biaya_total_disetujui = 0;
						
						v_biaya_total_diajukan = parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_DARAT_DIAJUKAN']),4)+
																		 parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_LAUT_DIAJUKAN']),4)+
																		 parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_UDARA_DIAJUKAN']),4);		
																				 
						v_biaya_total_verifikasi = parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_DARAT_VERIFIKASI']),4)+
																		 parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_LAUT_VERIFIKASI']),4)+
																		 parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_UDARA_VERIFIKASI']),4);
																				 														 																																																														 				
						v_biaya_total_disetujui = parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_DARAT_DISETUJUI']),4)+
																		 parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_LAUT_DISETUJUI']),4)+
																		 parseFloat(getValueNumber(jdata.data.DATA['TRANSPORT_UDARA_DISETUJUI']),4);
																				 									
						$('#biaya_total_diajukan').val(format_uang(v_biaya_total_diajukan));
						$('#biaya_total_verifikasi').val(format_uang(v_biaya_total_verifikasi));
						$('#biaya_total_disetujui').val(format_uang(v_biaya_total_disetujui));
				
						$('#keterangan').val(getValue(jdata.data.DATA['KETERANGAN']));	
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

