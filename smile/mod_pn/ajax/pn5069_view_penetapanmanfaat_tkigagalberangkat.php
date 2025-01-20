<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian penetapan manfaat
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5030";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "PENETAPAN MANFAAT";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	= !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
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
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset><legend>Penetapan Manfaat</legend>
                <table id="tblMnf" width="100%" class="table-data2">
                  <thead>								
                    <tr>
                      <th style="text-align:center;" colspan="2">&nbsp;</th>
                      <th style="text-align:center;" colspan="1" class="hr-single-bottom">PENETAPAN NILAI MANFAAT</th>
                      <th style="text-align:center;" colspan="2"></th>
                    </tr>					
                    <tr class="hr-double-bottom">
                      <th style="text-align:center;">No</th>
                      <th style="text-align:center;">Tipe Penerima</th>
            					<th style="text-align:right;">Biaya Disetujui</th>
                      <th style="text-align:center;">Catatan</th>
                      <th style="text-align:center;">Action</th>											
                    </tr>		
									</thead>	
									<tbody id="data_tblMnf">		
                    <tr class="nohover-color">
                    	<td colspan="5" style="text-align: center;">-- data tidak ditemukan --</td>
                    </tr>																		             																
                  </tbody>
									<tfoot>
                    <tr>
                      <td style="text-align:right;" colspan="2"><i>Total Manfaat:</i>
                        <input type="hidden" id="mnf_kounter_dtl" name="mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="mnf_count_dtl" name="mnf_count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">				
              				</td>        									
											<td style="text-align:right"><span id="mnf_tot_biaya_disetujui"></span></td>			
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
	function loadSelectedRecord(v_kode_klaim, v_kode_manfaat, fn)
	{
		if (v_kode_klaim == '' || v_kode_manfaat == '') {
			return alert('Kode Klaim atau Kode Manfaat tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getmanfaatbykodemnf',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - RINCIAN MANFAAT - BANTUAN GAGAL BERANGKAT');
						$("#span_page_title_right").html('KODE KLAIM : '+v_kode_klaim);
						
            //set data manfaat -------------------------------------------------
            var html_mnf = "";	
						var v_tot_d_nom_biaya_disetujui = 0;
							
            if (jdata.data.DATA)
            { 
              for(var i = 0; i < jdata.data.DATA.length; i++)
              {
                html_mnf += '<tr>';
                html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NO_URUT) + '</td>';
                html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NAMA_TIPE_PENERIMA) + '</td>';
								html_mnf += '<td style="text-align: right;">' + format_uang(getValueNumber(jdata.data.DATA[i].NOM_BIAYA_DISETUJUI)) + '</td>';
								html_mnf += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].KETERANGAN) + '</td>';
                html_mnf += '<td style="text-align: center;">' 
                            + '<a href="javascript:void(0)" onclick="fl_js_tap_mnf_rinci(\'' 
                            + getValue(jdata.data.DATA[i].KODE_KLAIM) + '\', \'' 
                            + getValue(jdata.data.DATA[i].KODE_MANFAAT) + '\', \''  
                            + getValue(jdata.data.DATA[i].NO_URUT) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> VIEW </font></a>' + '</td>';
                html_mnf += '</tr>';
                
								v_tot_d_nom_biaya_disetujui += parseFloat(getValueNumber(jdata.data.DATA[i].NOM_BIAYA_DISETUJUI),4);
              }

              if (html_mnf == "") {
                html_mnf += '<tr class="nohover-color">';
                html_mnf += '<td colspan="5" style="text-align: center;">-- data tidak ditemukan --</td>';
                html_mnf += '</tr>';
              }
              $("#data_tblMnf").html(html_mnf);
												
							$("#mnf_tot_biaya_disetujui").html(format_uang(v_tot_d_nom_biaya_disetujui));			
            }
            //end set data manfaat ---------------------------------------------						
					
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
	function fl_js_tap_mnf_rinci(p_kode_klaim, p_kode_manfaat, p_no_urut)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_tkigagalberangkatrinci.php?kode_klaim='+p_kode_klaim+'&kode_manfaat='+p_kode_manfaat+'&no_urut='+p_no_urut+'&mid='+c_mid+'','',980,550,'yes');
	}
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

