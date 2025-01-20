<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian manfaat jp lumpsum
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
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <fieldset ><legend>Rincian Manfaat</legend>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Tipe Penerima &nbsp;*</label>
              <input type="text" id="mnf_nama_tipe_penerima" name="mnf_nama_tipe_penerima" readonly class="disabled" style="width:240px;">
          		<input type="hidden" id="mnf_kode_tipe_penerima" name="mnf_kode_tipe_penerima">
          		<input type="hidden" id="mnf_kode_tipe_penerima_old" name="mnf_kode_tipe_penerima_old">
              <input type="hidden" id="mnf_kode_manfaat" name="mnf_kode_manfaat">
              <input type="hidden" id="mnf_no_urut" name="mnf_no_urut"> 
            </div>		    																																				
            <div class="clear"></div>	
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Pengambilan Sblmnya</label>
            	<input type="text" id="mnf_nom_manfaat_sudahdiambil" name="mnf_nom_manfaat_sudahdiambil" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>						
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Persentase Pengambilan &nbsp;</label>
              <input type="text" id="mnf_persentase_pengambilan" name="mnf_persentase_pengambilan" readonly class="disabled" style="width:240px;text-align:right;">
            </div>		    																																				
            <div class="clear"></div>
            
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Jumlah Diajukan</label>
            	<input type="text" id="mnf_nom_biaya_diajukan" name="mnf_nom_biaya_diajukan" onblur="this.value=format_uang(this.value);" style="width:240px;text-align:right;" <?=($ls_flag_partial =="Y")? " " : " readonly class=disabled";?>>                					
            </div>																																																											
            <div class="clear"></div>	
            
            <div class="form-row_kiri">
            <label style = "text-align:right;"><i><font color="#009999">Saldo JP :</font></i></label>	    				
            </div>																																																														
            <div class="clear"></div>	
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Saldo Awal Tahun &nbsp;</label>
              <input type="text" id="mnf_tgl_saldo_awaltahun" name="mnf_tgl_saldo_awaltahun" readonly class="disabled" style="width:75px;">
              <input type="text" id="mnf_nom_saldo_awaltahun" name="mnf_nom_saldo_awaltahun" readonly class="disabled" style="width:156px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Pengambilan Thn Bjalan</label>
            	<input type="text" id="mnf_nom_diambil_thnberjalan" name="mnf_nom_diambil_thnberjalan" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>																																																
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Saldo Pengembangan &nbsp;</label>
              <input type="text" id="mnf_tgl_pengembangan" name="mnf_tgl_pengembangan" readonly class="disabled" style="width:75px;">
              <input type="text" id="mnf_nom_saldo_pengembangan" name="mnf_nom_saldo_pengembangan" readonly class="disabled" style="width:156px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Max Pengambilan</label>
            	<input type="text" id="mnf_nom_manfaat_maxbisadiambil" name="mnf_nom_manfaat_maxbisadiambil" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>																																															
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;"><i>Total Saldo</i> &nbsp;</label>
            	<input type="text" id="mnf_nom_saldo_total" name="mnf_nom_saldo_total" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Jumlah Diambil</label>
            	<input type="text" id="mnf_nom_manfaat_diambil" name="mnf_nom_manfaat_diambil" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>																																															
            <div class="clear"></div>
            
            </br>
            
            <div class="form-row_kiri">
            <label style = "text-align:right;"><i><font color="#009999">Iuran JP :</font></i></label>	    				
            </div>												
            <div class="clear"></div>	
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Iuran Tambahan &nbsp;</label>
            	<input type="text" id="mnf_nom_iuran_thnberjalan" name="mnf_nom_iuran_thnberjalan" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">PPh Pasal 21</label>
              <input type="text" id="mnf_kode_pajak_pph" name="mnf_kode_pajak_pph" readonly class="disabled" style="width:75px;text-align:center;" >			
              <input type="text" id="mnf_nom_pph" name="mnf_nom_pph" readonly class="disabled" style="width:156px;text-align:right;">				                					
            </div>																																															
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Iuran Pengembangan &nbsp;</label>
              <input type="text" id="mnf_tgl_pengembangan2" name="mnf_tgl_pengembangan2" readonly class="disabled" style="width:75px;">
              <input type="text" id="mnf_nom_iuran_pengembangan" name="mnf_nom_iuran_pengembangan" readonly class="disabled" style="width:156px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Pembulatan</label>
            	<input type="text" id="mnf_nom_pembulatan" name="mnf_nom_pembulatan" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>																																														
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;"><i>Total Iuran</i> &nbsp;</label>
            	<input type="text" id="mnf_nom_iuran_total" name="mnf_nom_iuran_total" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Jumlah Dibayar</label>
            	<input type="text" id="mnf_nom_manfaat_netto" name="mnf_nom_manfaat_netto" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>																																																
            <div class="clear"></div>
            
            <div class="form-row_kiri">
            <label  style = "text-align:right;"><i>Total Saldo+Iuran</i> &nbsp;</label>
            	<input type="text" id="mnf_nom_saldo_iuran_total" name="mnf_nom_saldo_iuran_total" readonly class="disabled" style="width:240px;text-align:right;">                					
            </div>
            <div class="form-row_kanan">
            <label  style = "text-align:right;">Keterangan</label>
            	<input type="text" id="mnf_keterangan" name="mnf_keterangan" style="width:240px;">                					
            </div>																																																
            <div class="clear"></div>
						
						</br>
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
		loadSelectedRecord(kode_klaim, kode_manfaat, null);
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
	function loadSelectedRecord(v_kode_klaim, v_kode_manfaat, fn)
	{
		if (v_kode_klaim == '' || v_kode_manfaat == '') {
			return alert('Kode Klaim atau Kode Manfaat tidak boleh kosong');
		}
		
		//utk rincian manfaat jp lumpsum set v_no_urut_mnf = 1
		var v_no_urut_mnf = 1;
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatamanfaatdetil',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat,
				v_no_urut:v_no_urut_mnf
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - RINCIAN MANFAAT - '+getValue(jdata.data.DATA['NAMA_MANFAAT']));
						$("#span_page_title_right").html('KODE KLAIM : '+getValue(jdata.data.DATA['KODE_KLAIM']));
						
						//set value rincian manfaat ----------------------------------------
						$('#mnf_nama_tipe_penerima').val(getValue(jdata.data.DATA['NAMA_TIPE_PENERIMA']));
						$('#mnf_kode_tipe_penerima').val(getValue(jdata.data.DATA['KODE_TIPE_PENERIMA']));
						$('#mnf_kode_tipe_penerima_old').val(getValue(jdata.data.DATA['KODE_TIPE_PENERIMA']));
						$('#mnf_kode_manfaat').val(getValue(jdata.data.DATA['KODE_MANFAAT']));
						$('#mnf_no_urut').val(getValue(jdata.data.DATA['NO_URUT']));
						
						$('#mnf_nom_manfaat_sudahdiambil').val(format_uang(getValue(jdata.data.DATA['NOM_MANFAAT_SUDAHDIAMBIL'])));
						$('#mnf_persentase_pengambilan').val(format_uang(getValue(jdata.data.DATA['PERSENTASE_PENGAMBILAN'])));
						$('#mnf_nom_biaya_diajukan').val(format_uang(getValue(jdata.data.DATA['NOM_BIAYA_DIAJUKAN'])));
						
						$('#mnf_tgl_saldo_awaltahun').val(getValue(jdata.data.DATA['TGL_SALDO_AWALTAHUN']));
						$('#mnf_nom_saldo_awaltahun').val(format_uang(getValue(jdata.data.DATA['NOM_SALDO_AWALTAHUN'])));
						$('#mnf_nom_diambil_thnberjalan').val(format_uang(getValue(jdata.data.DATA['NOM_DIAMBIL_THNBERJALAN'])));
						$('#mnf_tgl_pengembangan').val(getValue(jdata.data.DATA['TGL_PENGEMBANGAN']));
						$('#mnf_nom_saldo_pengembangan').val(format_uang(getValue(jdata.data.DATA['NOM_SALDO_PENGEMBANGAN'])));
						$('#mnf_nom_manfaat_maxbisadiambil').val(format_uang(getValue(jdata.data.DATA['NOM_MANFAAT_MAXBISADIAMBIL'])));
						
						$('#mnf_nom_saldo_total').val(format_uang(getValue(jdata.data.DATA['NOM_SALDO_TOTAL'])));
						$('#mnf_nom_manfaat_diambil').val(format_uang(getValue(jdata.data.DATA['NOM_MANFAAT_DIAMBIL'])));
						
						$('#mnf_nom_iuran_thnberjalan').val(format_uang(getValue(jdata.data.DATA['NOM_IURAN_THNBERJALAN'])));
						$('#mnf_kode_pajak_pph').val(getValue(jdata.data.DATA['KODE_PAJAK_PPH']));
						$('#mnf_nom_pph').val(format_uang(getValue(jdata.data.DATA['NOM_PPH'])));
						$('#mnf_tgl_pengembangan2').val(getValue(jdata.data.DATA['TGL_PENGEMBANGAN']));
						$('#mnf_nom_iuran_pengembangan').val(format_uang(getValue(jdata.data.DATA['NOM_IURAN_PENGEMBANGAN'])));
						$('#mnf_nom_pembulatan').val(format_uang(getValue(jdata.data.DATA['NOM_PEMBULATAN'])));
						$('#mnf_nom_iuran_total').val(format_uang(getValue(jdata.data.DATA['NOM_IURAN_TOTAL'])));
						$('#mnf_nom_manfaat_netto').val(format_uang(getValue(jdata.data.DATA['NOM_MANFAAT_NETTO'])));
						$('#mnf_nom_saldo_iuran_total').val(format_uang(getValue(jdata.data.DATA['NOM_SALDO_IURAN_TOTAL'])));
						$('#mnf_keterangan').val(getValue(jdata.data.DATA['KETERANGAN']));					
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

