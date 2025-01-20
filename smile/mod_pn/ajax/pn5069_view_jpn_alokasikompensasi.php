<?php
//------------------------------------------------------------------------------
// Menu untuk display alokasi kompensasi jp berkala
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5030";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "RINCIAN ALOKASI KOMPENSASI JP BERKALA";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_kompensasi	 	= !isset($_GET['kode_kompensasi']) ? $_POST['kode_kompensasi'] : $_GET['kode_kompensasi'];
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
			<input type="hidden" id="kode_kompensasi" name="kode_kompensasi" value="<?=$ls_kode_kompensasi;?>">

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset><legend>Alokasi Kompensasi Manfaat Pensiun Berkala</legend>
                <table id="tblAlokKmpsasi" width="100%" class="table-data2">
                  <thead>								
                    <tr class="hr-double-bottom">
                      <th style="text-align:center;">Manfaat</th>
            					<th style="text-align:center;">Prg</th>
            					<th style="text-align:center;">Bln Berkala</th>
                      <th style="text-align:center;">Bln Bayar</th>
            					<th style="text-align:right;">Kompensasi</th>
            					<th style="text-align:center;">Keterangan</th>	
                    </tr>
									</thead>	
									<tbody id="data_tblAlokKmpsasi">											             																
                  </tbody>
									<tfoot>
              			<tr>
                      <td style="text-align:right;" colspan="4"><i>Total Keseluruhan :<i>
                        <input type="hidden" id="d_alokkmpsasi_kounter_dtl" name="d_alokkmpsasi_kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="d_alokkmpsasi_count_dtl" name="d_alokkmpsasi_count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="d_alokkmpsasi_showmessage" style="border-width: 0;text-align:right" readonly size="5">
              				</td>	  								
                      <td style="text-align:right;"><span id="span_tot_d_nom_kompensasi"></span></td>
              				<td></td>										        
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
		let kode_kompensasi = $('#kode_kompensasi').val();
		
		loadSelectedRecord(kode_klaim, kode_kompensasi, null);
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
	function loadSelectedRecord(v_kode_klaim, v_kode_kompensasi, fn)
	{
		if (v_kode_kompensasi == '') {
			return alert('Kode Kompensasi tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdataalokasikompensasijpberkala',
				v_kode_kompensasi: v_kode_kompensasi
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - ALOKASI KOMPENSASI MANFAAT PENSIUN BERKALA');
						$("#span_page_title_right").html('ATAS KODE KOMPENSASI : '+v_kode_kompensasi);
						
            //set data manfaat jp berkala --------------------------------------
            var html_alokkmpsasi = "";
						var v_tot_d_nom_kompensasi  = 0;			
							
            if (jdata.data.DATA)
            { 
              for(var i = 0; i < jdata.data.DATA.length; i++)
              {
                html_alokkmpsasi += '<tr>';
                html_alokkmpsasi += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NAMA_MANFAAT) + '</td>';
                html_alokkmpsasi += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NM_PRG) + '</td>';
                html_alokkmpsasi += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].BLTH_BERKALA) + '</td>';
								html_alokkmpsasi += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].BLTH_PROSES) + '</td>';
								html_alokkmpsasi += '<td style="text-align: right;">' + format_uang(getValueNumber(jdata.data.DATA[i].NOM_KOMPENSASI)) + '</td>';
								html_alokkmpsasi += '<td style="text-align: center;white-space:normal;word-wrap:break-word;">' + getValue(jdata.data.DATA[i].KETERANGAN) + '</td>';
                html_alokkmpsasi += '</tr>';
								
                v_tot_d_nom_kompensasi   += parseFloat(getValueNumber(jdata.data.DATA[i].NOM_KOMPENSASI),4);
              }
              
              if (html_alokkmpsasi == "") {
                html_alokkmpsasi += '<tr class="nohover-color">';
                html_alokkmpsasi += '<td colspan="10" style="text-align: center;">-- data tidak ditemukan --</td>';
                html_alokkmpsasi += '</tr>';
              }
              $("#data_tblAlokKmpsasi").html(html_alokkmpsasi);	
              
							$("#span_tot_d_nom_kompensasi").html(format_uang(v_tot_d_nom_kompensasi));					
            }
            //end set data manfaat jp berkala ----------------------------------
												
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

