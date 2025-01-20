<?php
//------------------------------------------------------------------------------
// Menu untuk display data ahli waris jp pada saat penetapan klaim jp berkala
// dibuat tgl : 30/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5006";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "DATA AHLI WARIS";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
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

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body">
          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset><legend>Ahli Waris</legend>
                <table id="tblAhliWaris" width="100%" class="table-data2">
                  <thead>	
                    <tr>
                      <th colspan="4">&nbsp;</th>
                      <th colspan="2" style="text-align: center" class="hr-single-bottom">Kondisi Akhir</th>
                      <th colspan="2">&nbsp;</th>					    							
                    </tr>												
                    <tr class="hr-double-bottom">
                      <th style="text-align:left;">Hub. Keluarga</th>
                      <th style="text-align:left;">Nama Lengkap</th>
                      <th style="text-align:center;">Tgl Lahir</th>
                      <th style="text-align:left;">Jenis Kelamin</th>
                      <th style="text-align:center;">Status</th>
                      <th style="text-align:center;">Sejak</th>
                      <th style="text-align:center;">Eligible</th>
                      <th style="text-align:center;">Action</th>					    							
                    </tr>	
                  </thead>	
									<tbody id="data_tblAhliWaris">											             																
                  </tbody>
									<tfoot>
              			<tr>
                      <td style="text-align:right;" colspan="8">&nbsp;
                        <input type="hidden" id="d_aws_kounter_dtl" name="d_alokkmpsasi_kounter_dtl" value="<?=$ln_dtl;?>">
                        <input type="hidden" id="d_aws_count_dtl" name="d_alokkmpsasi_count_dtl" value="<?=$ln_countdtl;?>">
                        <input type="hidden" name="d_aws_showmessage" style="border-width: 0;text-align:right" readonly size="5">
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
								<li style="margin-left:15px;"><font color="#ff0000"> <b>Status Kondisi Akhir</b></font> adalah status pada saat penetapan klaim JP Berkala. Untuk melihat perubahan status dapat melalui data histori Konfirmasi JP Berkala.</li>
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
		loadSelectedRecord(kode_klaim, null);
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
	function loadSelectedRecord(v_kode_klaim, fn)
	{
		if (v_kode_klaim == '') {
			return alert('Kode Klaim tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5065_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatalistahliwarisjp',
				v_kode_klaim: v_kode_klaim
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5006 - DATA AHLI WARIS');
						$("#span_page_title_right").html('KODE KLAIM : '+v_kode_klaim);
						
            //set data manfaat jp berkala --------------------------------------
            var html_aws = "";		
							
            if (jdata.data.DATA)
            { 
              for(var i = 0; i < jdata.data.DATA.length; i++)
              {
                var v_status_layak = getValue(jdata.data.DATA[i].STATUS_LAYAK) =='Y' ? '<img src=../../images/file_apply.gif> '+getValue(jdata.data.DATA[i].KODE_PENERIMA_BERKALA) : '';
								
								html_aws += '<tr>';
                html_aws += '<td style="text-align: left;">' + getValue(jdata.data.DATA[i].NAMA_HUBUNGAN) + '</td>';
                html_aws += '<td style="text-align: left;">' + getValue(jdata.data.DATA[i].NAMA_LENGKAP) + '</td>';
                html_aws += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].TGL_LAHIR) + '</td>';
								html_aws += '<td style="text-align: left;">' + getValue(jdata.data.DATA[i].NAMA_JENIS_KELAMIN) + '</td>';
								html_aws += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].NAMA_KONDISI_TERAKHIR) + '</td>';
								html_aws += '<td style="text-align: center;">' + getValue(jdata.data.DATA[i].TGL_KONDISI_TERAKHIR) + '</td>';
								html_aws += '<td style="text-align: center;">' + v_status_layak + '</td>';
								html_aws += '<td style="text-align: center;">' 
                         		+ '<a href="javascript:void(0)" onclick="fl_js_view_detil_ahliwaris(\'' 
                         		+ getValue(jdata.data.DATA[i].KODE_KLAIM) + '\', \'' 
                         		+ getValue(jdata.data.DATA[i].NO_URUT_KELUARGA) + '\')"><img src="../../images/user_go.png" border="0" alt="View Data Ahli Waris" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> VIEW </font></a>' + '</td>';
                html_aws += '</tr>';
              }
              
              if (html_aws == "") {
                html_aws += '<tr class="nohover-color">';
                html_aws += '<td colspan="8" style="text-align: center;">-- data tidak ditemukan --</td>';
                html_aws += '</tr>';
              }

              $("#data_tblAhliWaris").html(html_aws);					
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

<script language="javascript">
	function fl_js_view_detil_ahliwaris(p_kode_klaim, p_no_urut_keluarga)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_ahliwarisdetil.php?kode_klaim='+p_kode_klaim+'&no_urut_keluarga='+p_no_urut_keluarga+'&mid='+c_mid+'','',980,610,'yes');
	}
</script>	

<?php
include "../../includes/footer_app_nosql.php";
?>

