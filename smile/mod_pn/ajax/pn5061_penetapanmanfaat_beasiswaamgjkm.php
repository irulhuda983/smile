<?php
//------------------------------------------------------------------------------
// Menu untuk menampilkan daftar upah pada saat penetapan manfaat beasiswa
// last updated : 09/12/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  	= "form";
$gs_kodeform 	 	 		= "PN5002";
$chId 	 	 			 		= "SMILE";
$gs_pagetitle 	 		= "AMALGAMASI JKM";											 
$gs_kantor_aktif 		= $_SESSION['kdkantorrole'];
$gs_kode_user		 		= $_SESSION["USER"];
$gs_kode_role		 		= $_SESSION['regrole'];
$editid 				 		= $_POST['editid'];
$ls_kode_klaim	 		= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat		= !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ls_form_penetapan	= !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_sender 					= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_rg_kategori			= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
$task 			 				= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
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

<div id="formframe" style="min-width:90%;">
	<div id="div_dummy" style="width:100%;"></div>
	<div id="formKiri" style="width:97%; margin:0px 0px 0px 0px;">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
			<input type="hidden" id="rg_kategori" name="rg_kategori" value="<?=$ls_rg_kategori;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">

			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" style="padding-top:0px;">
					<div id="formRinci"></div>

          <div class="div-row">
            <div class="div-col" style="width:100%; max-height: 100%;">
              <fieldset><legend>Hasil Pencarian</legend>
                <table id="tblMnf" width="100%" class="table-data2">
                  <thead>												
                    <tr class="hr-double-bottom">
                      <th style="text-align:center;">No. Referensi</th>
                      <th style="text-align:center;">NIK</th>
											<th style="text-align:left;">Nama TK</th>
                      <th style="text-align:left;">Tempat Lahir</th>
                      <th style="text-align:center;">Tgl Lahir</th>
                      <th style="text-align:center;">Kode TK</th>
                      <th style="text-align:center;">Action</th>											
                    </tr>		
									</thead>	
									<tbody id="data_tblTkSearch">		
                    <tr class="nohover-color">
                    	<td colspan="7" style="text-align: center;">-- data tidak ditemukan --</td>
                    </tr>																		             																
                  </tbody>
									<tfoot>
                    <tr>
                      <td style="text-align:center" colspan="7">
                        <input type="hidden" id="d_kounter_dtl_amgjp" name="d_kounter_dtl_amgjp" value="<?=$ln_cntdata_amgjp;?>">
                        <input type="hidden" id="d_count_dtl_amgjp" name="d_count_dtl_amgjp" value="<?=$ln_cntdata_amgjp;?>">
                        <input type="hidden" name="d_showmessage_amgjp" style="border-width: 0;text-align:right" readonly size="5">					
                      </td>
                    </tr>
									</tfoot>																
                </table>
              </fieldset>				 
						</div>
					</div> 
				</div>

				<div id="div_footer" class="div-footer">
					<div class="div-footer-form" style="width:95%;">
						<div class="div-row">
							<span id="span_button_tutup" style="display:block;">			 
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doBack2Grid" href="#" onClick="fjq_ajax_val_doButtonTutup();">
                        <img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                        <span>TUTUP</span>
                      </a>
                    </div>
                  </div>
                </div>	
							</span>

              <span id="span_button_saveamg" style="display:none;">
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doSaveInsert" href="#" onClick="if(confirm('Apakah anda yakin ..?')) fjq_ajax_val_doSaveAmalgamasi();">
                      <img src="../../images/ico_save.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                      <span>SIMPAN DATA AMALGAMASI &nbsp;</span>
                      </a>											
                    </div>
                  </div>
                </div>  									
              </span>														 
						</div>	 
					</div>
					<!--end div-footer-form -->	
					
					<div style="padding-top:6px;">
            <span id="span_footer_keterangan_new" style="display:block;">
              <div class="div-footer-content" style="width:93%;">
              <div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
              <li style="margin-left:15px;">Klik <img src="../../images/zoom.png" border="0" alt="Tambah" align="absmiddle" style="height:15px;"/> <font color="#ff0000">Search Data TK</font> untuk melakukan pencarian data TK untuk kartu lainnya.</li>
							<li style="margin-left:15px;">KPJ yang telah diambil saldo JHT secara penuh sebelum tanggal kejadian, tidak diperhitungkan dalam masa iur.</li>
              </div>								
            </span>					
					</div>					 
				</div>
				<!--end div_footer -->
								
			</div>
			<!--end div_container -->		
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
			parent:window, // ditambah ini agar parent windownya bisa diakses di form popup
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
		
		v_task = $('#task').val();
		
		//asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
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
						$("#span_page_title").html('PN5002 - AMALGAMASI JKM');
						$("#span_page_title_right").html('KODE KLAIM : '+v_kode_klaim);
						
						var v_amgjp_nomor_identitas = getValue(jdata.dataKdKlaim.INFO_KLAIM.NOMOR_IDENTITAS);
						var v_amgjp_nama_lengkap = getValue(jdata.dataKdKlaim.INFO_KLAIM.NAMA_TK);
						var v_amgjp_kpj = getValue(jdata.dataKdKlaim.INFO_KLAIM.KPJ);
						var v_amgjp_tempat_lahir = getValue(jdata.dataKdKlaim.INFO_KLAIM.TEMPAT_LAHIR);
						var v_amgjp_tgl_lahir = getValue(jdata.dataKdKlaim.INFO_KLAIM.TGL_LAHIR);
						
						var v_search_nomor_identitas = v_amgjp_nomor_identitas;
						var v_search_nama_lengkap = "";
						var v_search_tempat_lahir = "";
						var v_search_kpj = "";
						var v_search_tgl_lahir = v_amgjp_tgl_lahir;
						 
						var html_input  = '';
						html_input += '<div class="div-row">';
						html_input += '	 <div class="div-col" style="width:50%; max-height: 100%;">';
						html_input += '	   <div class="div-row">';
						html_input += '	     <fieldset><legend>Informasi Tenaga Kerja yang Digunakan untuk Klaim JKM</legend>';
						html_input += '	     	 <div class="div-row">';
						html_input += '	     		<div class="div-col">';
						html_input += '	     			</br>';
            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '	 		   			<input type="text" id="amgjp_nomor_identitas" name="amgjp_nomor_identitas" value="'+v_amgjp_nomor_identitas+'" style="width:240px;" readonly class="disabled">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';	
						
            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">Nama Lengkap</label>';
            html_input += '	 		   			<input type="text" id="amgjp_nama_lengkap" name="amgjp_nama_lengkap" value="'+v_amgjp_nama_lengkap+'" style="width:240px;" readonly class="disabled">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';

            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">No. Referensi &nbsp;</label>';
            html_input += '	 		   			<input type="text" id="amgjp_kpj" name="amgjp_kpj" value="'+v_amgjp_kpj+'" style="width:240px;" readonly class="disabled">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';

            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">Tempat Lahir</label>';
            html_input += '	 		   			<input type="text" id="amgjp_tempat_lahir" name="amgjp_tempat_lahir" value="'+v_amgjp_tempat_lahir+'" style="width:240px;" readonly class="disabled">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';

            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">Tgl Lahir</label>';
            html_input += '	 		   			<input type="text" id="amgjp_tgl_lahir" name="amgjp_tgl_lahir" value="'+v_amgjp_tgl_lahir+'" style="width:200px;" readonly class="disabled">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';

						html_input += '	     		 	</br>';																																		 
						html_input += '	     	 	 </div>';	 
						html_input += '	       </div>';						
						html_input += '	 	 	 </fieldset>';
						html_input += '	 	 </div>';
						html_input += '	 </div>';
						
						html_input += '	 <div class="div-col" style="width:1%;">';
						html_input += '	 </div>';
						
						html_input += '	 <div class="div-col-right" style="width:49%;">';
						html_input += '	     	<div class="div-row">';
						html_input += '	     		<fieldset style="min-height:180px;"><legend>Pencarian Data TK untuk Kartu Lainnya</legend>';
    				html_input += '	     			</br>';
            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '	 		   			<input type="text" id="search_nomor_identitas" name="search_nomor_identitas" value="'+v_search_nomor_identitas+'" tabindex="1" style="width:240px;background-color: #ccffff;">';
            html_input += '			 				<input type="hidden" id="search_nama_lengkap" name="search_nama_lengkap" value="'+v_search_nama_lengkap+'">';
            html_input += '			 				<input type="hidden" id="search_tempat_lahir" name="search_tempat_lahir" value="'+v_search_tempat_lahir+'">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';	
						
            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">No Referensi</label>';
            html_input += '	 		   			<input type="text" id="search_kpj" name="search_kpj" value="'+v_search_kpj+'" tabindex="2" style="width:240px;background-color: #ccffff;">';
            html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';

            html_input += '			 			<div class="form-row_kiri">';
            html_input += '			 			<label style = "text-align:right;">Tgl Lahir</label>';
            html_input += '	 		   			<input type="text" id="search_tgl_lahir" name="search_tgl_lahir" value="'+v_search_tgl_lahir+'" tabindex="3" style="width:200px;background-color: #ccffff;">';
            html_input += '	 		   			<input id="btn_search_tgl_lahir" type="image" align="top" onclick="return showCalendar(\'search_tgl_lahir\', \'dd-mm-y\');" src="../../images/calendar.gif" />';
						html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';

            html_input += '			 			<div style = "text-align:center;">';
            html_input += '	 		   			<br /><a href="#" onClick="if(confirm(\'Apakah anda yakin akan melaukan pencairan data tk untuk kartu yang lainnya..?\')) fjq_ajax_val_searchtk_amalgamasijkm();"><img src="../../images/zoom.png" border="0" alt="Tambah" align="absmiddle" style="height:20px;"/>&nbsp;<b>Search Data TK</b></a>	';
						html_input += '			 			</div>';
            html_input += '			 			<div class="clear"></div>';						
						
						html_input += '	     		</fieldset>';	 
						html_input += '	     	</div>';					
						html_input += '	 </div>';
						
						html_input += '</div>';
						        						
            if (html_input !="")
            {
             	$('#formRinci').html(html_input); 
            }
						
						fjq_ajax_val_searchtk_amalgamasijkm();
						 
						resize();									
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
	
	//search data tk amalgamasi jkm ----------------------------------------------
	function fjq_ajax_val_searchtk_amalgamasijkm()
	{
	 	v_kode_klaim 						 = $('#kode_klaim').val();
		v_search_nomor_identitas = $('#search_nomor_identitas').val();
		v_search_kpj 						 = $('#search_kpj').val();
		v_search_tgl_lahir 			 = $('#search_tgl_lahir').val();

	 	v_amgjp_kpj 						 = $('#amgjp_kpj').val();
		v_amgjp_nomor_identitas  = $('#amgjp_nomor_identitas').val();
		v_amgjp_nama_lengkap  	 = $('#amgjp_nama_lengkap').val();
		v_amgjp_tempat_lahir  	 = $('#amgjp_tempat_lahir').val();
		v_amgjp_tgl_lahir 			 = $('#amgjp_tgl_lahir').val();
		
		if (v_search_nomor_identitas=='')
		{
		 	v_search_nomor_identitas = v_amgjp_nomor_identitas; 
		}
		if (v_search_tgl_lahir=='')
		{
		 	v_search_tgl_lahir = v_amgjp_tgl_lahir; 
		}
								
		v_task = $('#task').val();
		
		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_searchtk_amalgamasijkm',
				v_kode_klaim: v_kode_klaim,
				v_search_nomor_identitas:v_search_nomor_identitas,
				v_search_kpj:v_search_kpj,
				v_search_tgl_lahir:v_search_tgl_lahir
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					preload(false);
					if (jdata.ret == 0) 
					{ 
						//set data hasil pencarian -----------------------------------------
            var html_tks = "";	
						var v_tot_d_jml_bln = 0;	
						var v_status_valid_tkamgjp = "T";
						
            if (jdata.DtTkSearch.DATA)
            { 
              for(var i = 0; i < jdata.DtTkSearch.DATA.length; i++)
              {
								iteration = i;
								
								if (
									 getValue(jdata.DtTkSearch.DATA[i].NOMOR_IDENTITAS) == v_amgjp_nomor_identitas &&
									 getValue(jdata.DtTkSearch.DATA[i].NAMA_LENGKAP) == v_amgjp_nama_lengkap &&
									 getValue(jdata.DtTkSearch.DATA[i].TEMPAT_LAHIR) == v_amgjp_tempat_lahir &&
									 getValue(jdata.DtTkSearch.DATA[i].TGL_LAHIR) == v_amgjp_tgl_lahir
								)
								{
								 	 v_status_valid_tkamgjp = "Y";
								}else
								{
								 	v_status_valid_tkamgjp = "T";	 
								}
								
								html_tks += '<tr>';
								html_tks += '  <td style="text-align: center;">' + getValue(jdata.DtTkSearch.DATA[i].KPJ) + '</td>';
                html_tks += '	 <td style="text-align: center;">' + getValue(jdata.DtTkSearch.DATA[i].NOMOR_IDENTITAS) + '</td>';
								html_tks += '	 <td style="text-align: left;">' + getValue(jdata.DtTkSearch.DATA[i].NAMA_LENGKAP) + '</td>';
								html_tks += '	 <td style="text-align: left;">' + getValue(jdata.DtTkSearch.DATA[i].TEMPAT_LAHIR) + '</td>';
								html_tks += '	 <td style="text-align: center;">' + getValue(jdata.DtTkSearch.DATA[i].TGL_LAHIR) + '</td>';
								html_tks += '	 <td style="text-align: center;">' + getValue(jdata.DtTkSearch.DATA[i].KODE_TK) + '</td>';
								html_tks += '	 <td style="text-align: center;">';
								html_tks += '  		 <input type="hidden" id='+'d_dtl_kpj'+iteration+' name='+'d_dtl_kpj'+iteration+' maxlength="15">';
								html_tks += '  		 <input type="hidden" id='+'d_dtl_nik'+iteration+' name='+'d_dtl_nik'+iteration+'>';
								html_tks += '  		 <input type="hidden" id='+'d_dtl_nama_tk'+iteration+' name='+'d_dtl_nama_tk'+iteration+'>';
								html_tks += '  		 <input type="hidden" id='+'d_dtl_tempat_lahir'+iteration+' name='+'d_dtl_tempat_lahir'+iteration+'>';
								html_tks += '  		 <input type="hidden" id='+'d_dtl_tgl_lahir'+iteration+' name='+'d_dtl_tgl_lahir'+iteration+'>';
								html_tks += '  		 <input type="hidden" id='+'d_dtl_kode_tk'+iteration+' name='+'d_dtl_kode_tk'+iteration+'>';	
								
								if (v_status_valid_tkamgjp =="Y")
            		{
                 	 html_tks += '  <input type="checkbox" class="cebox" id='+'d_dtl_status_valid'+iteration+' name='+'d_dtl_status_valid'+iteration+' onclick="fl_js_amgjp_set_status_valid('+iteration+');" '+(v_status_valid_tkamgjp=='Y' ? 'checked' : '')+'>';
          				 html_tks += '  Valid';
          			}else
          			{
                 	html_tks += '  <input type="checkbox" disabled class="cebox" id='+'dcb_status_valid_tkamgjp'+iteration+' name='+'dcb_status_valid_tkamgjp'+iteration+' '+(v_status_valid_tkamgjp=='Y' ? 'checked' : '')+'>';
          			 	html_tks += '  <input type="hidden" id='+'d_dtl_status_valid'+iteration+' name='+'d_dtl_status_valid'+iteration+'>';
          				html_tks += '  Valid';						
          			}
																				
								html_tks += '	</td>';
								html_tks += '</tr>';
              }

              if (html_tks == "") {
                html_tks += '<tr class="nohover-color">';
                html_tks += '<td colspan="7" style="text-align: center;">-- tidak ada data kartu lainnya --</td>';
                html_tks += '</tr>';
              }
              $("#data_tblTkSearch").html(html_tks);
							
							iteration++;						
							$("#d_kounter_dtl_amgjp").val(iteration);
														
              //set value utk setiap kolom -------------------------------------
							iteration = 0;
							var v_cnt_dtTkSearch = 0;
							for(var i = 0; i < jdata.DtTkSearch.DATA.length; i++)
  						{
								iteration = i;
								if (
									 getValue(jdata.DtTkSearch.DATA[i].NOMOR_IDENTITAS) == v_amgjp_nomor_identitas &&
									 getValue(jdata.DtTkSearch.DATA[i].NAMA_LENGKAP) == v_amgjp_nama_lengkap &&
									 getValue(jdata.DtTkSearch.DATA[i].TEMPAT_LAHIR) == v_amgjp_tempat_lahir &&
									 getValue(jdata.DtTkSearch.DATA[i].TGL_LAHIR) == v_amgjp_tgl_lahir
								)
								{
								 	 v_status_valid_tkamgjp = "Y";
								}else
								{
								 	v_status_valid_tkamgjp = "T";	 
								}
																
								//set value ---------------------------------------------------- 
                v_d_dtl_kpj 						= getValue(jdata.DtTkSearch.DATA[i].KPJ);
                v_d_dtl_nik 				 		= getValue(jdata.DtTkSearch.DATA[i].NOMOR_IDENTITAS);
                v_d_dtl_nama_tk 				= getValue(jdata.DtTkSearch.DATA[i].NAMA_LENGKAP);
                v_d_dtl_tempat_lahir  	= getValue(jdata.DtTkSearch.DATA[i].TEMPAT_LAHIR);
                v_d_dtl_tgl_lahir  			= getValue(jdata.DtTkSearch.DATA[i].TGL_LAHIR);
                v_d_dtl_kode_tk 				= getValue(jdata.DtTkSearch.DATA[i].KODE_TK);
								v_d_dtl_status_valid		= v_status_valid_tkamgjp;
								
								$('#d_dtl_kpj'+iteration).val(v_d_dtl_kpj);
								$('#d_dtl_nik'+iteration).val(v_d_dtl_nik);
								$('#d_dtl_nama_tk'+iteration).val(v_d_dtl_nama_tk);
								$('#d_dtl_tempat_lahir'+iteration).val(v_d_dtl_tempat_lahir);
								$('#d_dtl_tgl_lahir'+iteration).val(v_d_dtl_tgl_lahir);
								$('#d_dtl_kode_tk'+iteration).val(v_d_dtl_kode_tk);
								$('#d_dtl_status_valid'+iteration).val(v_d_dtl_status_valid);
								
								v_cnt_dtTkSearch++;	
							}											
            	
							//set button save ------------------------------------------------
							if (v_cnt_dtTkSearch>0)
							{	
								window.document.getElementById("span_button_saveamg").style.display = 'block'; 
							}else
							{
							 	window.document.getElementById("span_button_saveamg").style.display = 'none'; 	 
							}
							
						}
            //end set data hasil pencarian -------------------------------------
						resize();
						
						alert('Pencarian data TK selesai, session dilanjutkan..');									
					}else
					{
					 	alert('Tidak ditemukan data lainnya...');	 
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
	//end search data tk amalgamasi jkm ------------------------------------------

  function fl_js_amgjp_set_status_valid(v_i)
  {
    var form = document.formreg;
    var n_d_status_valid = 'd_dtl_status_valid'+v_i;								
    
    if (document.getElementById(n_d_status_valid).checked)
    {
     	document.getElementById(n_d_status_valid).value = 'Y';
    }
    else
    {
     	document.getElementById(n_d_status_valid).value = 'T';
    }									
  }

  //do simpan data amalgamasi --------------------------------------------------
	function fjq_ajax_val_doSaveAmalgamasi()
  {				 
		var v_kode_klaim = $('#kode_klaim').val();
		
		if (v_kode_klaim =="")
		{
		 	alert('Kode Klaim kosong, harap tutup menu kemudian diproses kembali..!!!'); 
		}else
		{
  		$('#tipe').val('DoKlikSimpanAmalgamasiJkm');	
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5061_action.php?'+Math.random(),
        data: $('#formreg').serialize(),
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);									
          if(jdata.ret == '0')
          {						 		 						 						 
						window.parent.Ext.WindowManager.getActive().parent.reloadFormUtama();   			
      			window.parent.Ext.WindowManager.getActive().close();
						
						window.parent.Ext.notify.msg('Sukses', jdata.msg);
            alert(jdata.msg);
          }else 
          {
           	alert(jdata.msg);
          }
        }
      });//end ajax
		}
  }
  //end do simpan data amalgamasi ----------------------------------------------
				
	function fjq_ajax_val_doButtonTutup()
	{
	 	window.parent.Ext.WindowManager.getActive().close();			 
	}
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>
