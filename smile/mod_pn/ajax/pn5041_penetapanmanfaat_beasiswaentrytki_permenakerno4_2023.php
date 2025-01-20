<?php
//------------------------------------------------------------------------------
// Menu untuk entry detil manfaat beasiswa per tahun ajaran
// dibuat tgl : 11/01/2021
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include_once '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 	= "form";
$gs_kodeform 	 	 	= "PN5002";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "ENTRY DETIL MANFAAT BEASISWA";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim 	 	= !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ls_kode_manfaat 	= !isset($_POST['kode_manfaat']) ? $_GET['kode_manfaat'] : $_POST['kode_manfaat'];
$ln_no_urut 			= !isset($_POST['no_urut']) ? $_GET['no_urut'] : $_POST['no_urut'];
$ls_tahun 				= !isset($_POST['tahun']) ? $_GET['tahun'] : $_POST['tahun'];
$ln_vi 						= !isset($_POST['vi']) ? $_GET['vi'] : $_POST['vi'];
$ls_task_detil	 	= !isset($_POST['task_detil']) ? $_GET['task_detil'] : $_POST['task_detil'];
$ls_nik_penerima 	= !isset($_POST['nik_penerima']) ? $_GET['nik_penerima'] : $_POST['nik_penerima'];
$task							= $ls_task_detil;
$gs_pagetitle			= $gs_pagetitle." UNTUK TAHUN ".$ls_tahun;

$ls_tmp_jenis 				= !isset($_POST['tmp_jenis']) ? $_GET['tmp_jenis'] : $_POST['tmp_jenis'];
$ls_tmp_jenjang				= !isset($_POST['tmp_jenjang']) ? $_GET['tmp_jenjang'] : $_POST['tmp_jenjang'];
$ls_tmp_flag_terima		= !isset($_POST['tmp_flag_terima']) ? $_GET['tmp_flag_terima'] : $_POST['tmp_flag_terima'];
$ls_tmp_tingkat				= !isset($_POST['tmp_tingkat']) ? $_GET['tmp_tingkat'] : $_POST['tmp_tingkat'];
$ls_tmp_lembaga				= !isset($_POST['tmp_lembaga']) ? $_GET['tmp_lembaga'] : $_POST['tmp_lembaga'];
$ls_tmp_keterangan		= !isset($_POST['tmp_keterangan']) ? $_GET['tmp_keterangan'] : $_POST['tmp_keterangan'];
$ln_tmp_nom_manfaat		= !isset($_POST['tmp_nom_manfaat']) ? $_GET['tmp_nom_manfaat'] : $_POST['tmp_nom_manfaat'];

$ls_tmp_nama_dokumen			= !isset($_POST['tmp_nama_dokumen']) ? $_GET['tmp_nama_dokumen'] : $_POST['tmp_nama_dokumen'];
$ls_tmp_nama_file_dokumen	= !isset($_POST['tmp_nama_file_dokumen']) ? $_GET['tmp_nama_file_dokumen'] : $_POST['tmp_nama_file_dokumen'];
$ln_tmp_no_urut_dokumen		= !isset($_POST['tmp_no_urut_dokumen']) ? $_GET['tmp_no_urut_dokumen'] : $_POST['tmp_no_urut_dokumen'];
$ls_tmp_url_dokumen				= !isset($_POST['tmp_url_dokumen']) ? $_GET['tmp_url_dokumen'] : $_POST['tmp_url_dokumen'];
$ls_tmp_flag_dok_lengkap	= !isset($_POST['tmp_flag_dok_lengkap']) ? $_GET['tmp_flag_dok_lengkap'] : $_POST['tmp_flag_dok_lengkap'];			
$ls_tmp_dtl_data	= !isset($_POST['tmp_dtl_data']) ? $_GET['tmp_dtl_data'] : $_POST['tmp_dtl_data'];			
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
			<span style="color:#ffffff"><span id="span_page_title_right"></span></span>	 				
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
			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
			<input type="hidden" id="tahun" name="tahun" value="<?=$ls_tahun;?>">
			<input type="hidden" id="vi" name="vi" value="<?=$ln_vi;?>">
			<input type="hidden" id="task_detil" name="task_detil" value="<?=$ls_task_detil;?>">
			<input type="hidden" id="nik_penerima" name="nik_penerima" value="<?=$ls_nik_penerima;?>">
			
			<input type="hidden" id="tmp_jenis" name="tmp_jenis" value="<?=$ls_tmp_jenis;?>">
			<input type="hidden" id="tmp_jenjang" name="tmp_jenjang" value="<?=$ls_tmp_jenjang;?>">
			<input type="hidden" id="tmp_flag_terima" name="tmp_flag_terima" value="<?=$ls_tmp_flag_terima;?>">
			<input type="hidden" id="tmp_tingkat" name="tmp_tingkat" value="<?=$ls_tmp_tingkat;?>">
			<input type="hidden" id="tmp_lembaga" name="tmp_lembaga" value="<?=$ls_tmp_lembaga;?>">
			<input type="hidden" id="tmp_keterangan" name="tmp_keterangan" value="<?=$ls_tmp_keterangan;?>">
			<input type="hidden" id="tmp_nom_manfaat" name="tmp_nom_manfaat" value="<?=$ln_tmp_nom_manfaat;?>">
			
			<input type="hidden" id="tmp_nama_dokumen" name="tmp_nama_dokumen" value="<?=$ls_tmp_nama_dokumen;?>">
			<input type="hidden" id="tmp_nama_file_dokumen" name="tmp_nama_file_dokumen" value="<?=$ls_tmp_nama_file_dokumen;?>">
			<input type="hidden" id="tmp_no_urut_dokumen" name="tmp_no_urut_dokumen" value="<?=$ln_tmp_no_urut_dokumen;?>">
			<input type="hidden" id="tmp_url_dokumen" name="tmp_url_dokumen" value="<?=$ls_tmp_url_dokumen;?>">
			<input type="hidden" id="tmp_flag_dok_lengkap" name="tmp_flag_dok_lengkap" value="<?=$ls_tmp_flag_dok_lengkap;?>">
			<input type="hidden" id="tmp_dtl_data" name="tmp_dtl_data" value="<?=$ls_tmp_dtl_data;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" style="padding-top:0px;">
					<div id="formRinci"></div>	 
				</div>

				<div id="div_footer" class="div-footer">
					<div class="div-footer-form" style="width:95%;">
						<div class="div-row">
              <span id="span_button_ok" style="display:none;">
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doSaveInsert" href="#" onClick="if(confirm('Apakah anda yakin ..?')) fjq_ajax_val_doButtonOK();">
                      <img src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                      <span>OK &nbsp;</span>
                      </a>											
                    </div>
                  </div>
                </div>  									
              </span>
							
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
						</div>	 
					</div>
					<!--end div-footer-form -->	
					
					<div style="padding-top:6px;">
            <span id="span_footer_keterangan_new" style="display:block;">
              <div class="div-footer-content" style="width:93%;">
              <div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
			  <ul>
              <li style="margin-left:15px;">Harap memastikan apakah anak penerima beasiswa sedang menempuh pendidikan di <span style="color:#ff0000">tahun <?=$ls_tahun;?></span>.</li>
							<li style="margin-left:15px;">Apabila di tahun <?=$ls_tahun;?> anak <span style="color:#ff0000">sedang tidak menempuh pendidikan</span> maka lengkapi keterangan apakah sedang cuti kuliah/dll. <br/>Tidak ada manfaat beasiswa yang akan diterima untuk tahun tersebut.</li>
							<li style="margin-left:15px;">Apabila di tahun <?=$ls_tahun;?> anak <span style="color:#ff0000">sedang menempuh pendidikan</span> maka lengkapi jenis, jenjang, tingkat mapun lembaga/nama sekolah/universitas serta dokumen yang menyatakan bahwa memang benar sedang menempuh pendidikan di tahun tersebut. 
								<br/>Manfaat beasiswa yang diterima akan disesuaikan dengan jenjang pendidikan yang ditempuh di tahun tersebut.
							</li>
							</ul>				
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

		/** list checkbox */
		window.list_checkbox_record = [];
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
		
	function getValueArr(val){
		if (val){
		 	return val; 
		}else{
		 	return '';	 
		}
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
	$(document).ready(function(){
		let task = $('#task').val();
		
		if (task=="view")
		{
		 	fjq_ajax_val_onloadformView();	 
		}else
		{		
		  fjq_ajax_val_onloadform();		
		} 
	});
	
	function fjq_ajax_val_onloadform()
	{
	 	var v_tahun 			= $('#tahun').val();			 
    	var v_flag_terima = $('#tmp_flag_terima').val() == '' ? 'T' : $('#tmp_flag_terima').val();
		var v_jenis 			= $('#tmp_jenis').val();
		var v_jenjang 		= $('#tmp_jenjang').val();
		var v_tingkat 		= $('#tmp_tingkat').val();
		var v_lembaga 		= $('#tmp_lembaga').val();
		var v_keterangan 	= $('#tmp_keterangan').val();
		var v_nom_manfaat = parseFloat($('#tmp_nom_manfaat').val());
		
		var v_nama_dokumen 			= $('#tmp_nama_dokumen').val();
		var v_nama_file_dokumen = $('#tmp_nama_file_dokumen').val();
		var v_no_urut_dokumen 	= $('#tmp_no_urut_dokumen').val();
		var v_url_dokumen 			= $('#tmp_url_dokumen').val();
		var v_flag_dok_lengkap  = $('#tmp_flag_dok_lengkap').val();	
		var v_dtl_data  = $('#tmp_dtl_data').val();	

							
		//set form entry rincian manfaat -----------------------------------
		if (v_flag_terima=="T" && v_keterangan=="")
		{
		 	v_new_entry = "Y";
		}else
		{
		 	v_new_entry = "T";
		}
		
		var html_input  = '';
		html_input += '<div class="div-row">';
		html_input += '	 <div class="div-col" style="width:100%;">';
		html_input += '	   <fieldset><legend>';
    	html_input += '    	 	 <strong><em><font color="#009999">Apakah di tahun '+v_tahun+' anak sedang menempuh pendidikan/pelatihan?</font></em></strong>';
		html_input += '    	 	 <input type="radio" name="rg_status_sekolah" id="rg_status_sekolah_t" value="T" onClick="fl_js_set_fieldset_stsekolah_t();">&nbsp;&nbsp;<strong><font color="#009999">Tidak</font>';
    	html_input += '    	 	 <input type="radio" name="rg_status_sekolah" id="rg_status_sekolah_y" value="Y" onClick="fl_js_set_fieldset_stsekolah_y();">&nbsp;&nbsp;<font color="#009999">Ya</font></strong>';																																	
		html_input += '	     	 <input type="hidden" id="status_sekolah" name="status_sekolah" value="'+v_flag_terima+'">';
		html_input += '	   </legend>';
		html_input += '	   	 </br>';

		html_input += '	   	 <span id="span_sedang_sekolah" style="display:none;">';
		html_input += '	       <div class="form-row_kiri">';
		html_input += '	         <label style = "text-align:right;width:150px;">Jenis Beasiswa *</label>';
		html_input += '	           <select size="1" id="jenis" name="jenis" tabindex="2" class="select_format" style="width:255px;background-color:#ffff99;" onchange="fl_js_postchange_jenisbeasiswa();">';
		html_input += '	           	 <option value="">-- Pilih Jenis Beasiswa --</option>';
		html_input += '	     			 </select>';
		html_input += '	         </div>';
		html_input += '	       <div class="clear"></div>';
		
		html_input += '	   	 	 <span id="span_jenjang"></span>';
		
		html_input += '				 <div class="form-row_kiri">';
		html_input += '				 <label style = "text-align:right;width:150px;">Tingkat/Kelas *</label>';
		html_input += '	 			   <input type="text" id="tingkat" name="tingkat" value="'+v_tingkat+'" tabindex="4" style="width:250px;background-color:#ffff99;" maxlength="100" onkeyup="this.value = this.value.toUpperCase();">';
		html_input += '				 </div>';
		html_input += '				 <div class="clear"></div>';	
								
		html_input += '	   	 </span>';

		html_input += '	   	 <span id="span_lembaga" style="display:none;">';
    	html_input += '				 <div class="form-row_kiri">';
		html_input += '	   	   <label  style = "text-align:right;width:150px;"><span id="span_label_lembaga"></label>';
		html_input += '	 			   <input type="text" id="lembaga" name="lembaga" value="'+v_lembaga+'" tabindex="5" style="width:250px;background-color:#ffff99;" maxlength="100" onkeyup="this.value = this.value.toUpperCase();">';
		html_input += '				 </div>';
		html_input += '				 <div class="clear"></div>';	
		html_input += '	   	 </span>';
				
		html_input += '	   	 <span id="span_keterangan" style="display:none;">';
		html_input += '	   	   <div class="form-row_kiri">';
		html_input += '	   	   <label style = "text-align:right;width:150px;">Keterangan *</label>';
		html_input += '	   	   	 <textarea cols="255" rows="1" style="width:250px;height:30px;background-color:#ffff99;" id="keterangan" name="keterangan" tabindex="6" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onkeyup="this.value = this.value.toUpperCase();">'+v_keterangan+'</textarea>';   					
		html_input += '	   	   </div>';								
		html_input += '	   	   <div class="clear"></div>';
																									
		html_input += '	   	   <div class="form-row_kiri">';
		html_input += '	   	   <label  style = "text-align:right;width:150px;">Manfaat Beasiswa </label>';
		html_input += '	   	     <input type="text" id="nom_manfaat" name="nom_manfaat" value="'+format_uang(v_nom_manfaat)+'" maxlength="20" style="width:230px;text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
		html_input += '	   	   </div>';																		
		html_input += '	   	   <div class="clear"></div>';				
		html_input += '	   	 </span>';
			
		html_input += '	 	 </fieldset>';		
		html_input += '	 </div>';
		html_input += '</div>';				 				 
		
		html_input += '<span id="span_dokumen_upload" style="display:none">';
		html_input += '	 <div class="div-row">';
		html_input += '	   <div class="div-col" style="width:100%;">';
		html_input += '	     <fieldset><legend>';
    	html_input += '    	 	 <strong><em><font color="#009999">Apakah dokumen kelengkapan administrasi untuk tahun '+v_tahun+' lengkap?</font></em></strong>';
		html_input += '    	 	 <input type="radio" name="rg_flag_dok_lengkap" value="T" onClick="fl_js_set_ket_dok_tidaklengkap();">&nbsp;&nbsp;<strong><font color="#009999">Tidak</font>';
    	html_input += '    	 	 <input type="radio" name="rg_flag_dok_lengkap" value="Y" onClick="fl_js_set_dok_lengkap();">&nbsp;&nbsp;<font color="#009999">Ya</font></strong>';																																	
		html_input += '	   	 </legend>';
		html_input += '	   	 	 </br>';		
		html_input += '			 	 <span id="span_dok_tidaklengkap" style="display:none">';
		html_input += '	     	 	 <div style="text-align:center;"><span id="span_ket_dok_tidaklengkap"></span></div><div class="clear"></div>';
		html_input += '	   	 	 </span>';
		html_input += '			 	 <span id="span_dok_lengkap" style="display:none">';
		html_input += '	       	<div class="form-row_kiri">';
		html_input += '	       	<label style = "text-align:right;width:150px;">Dokumen *</label>';
		html_input += '	     	 	 	<input type="text" id="nama_dokumen" name="nama_dokumen" value="'+v_nama_dokumen+'" maxlength="100" style="width:230px;background-color:#ffff99;" onkeyup="this.value = this.value.toUpperCase();" onblur="fl_js_set_perubahan_nama_dokumen();">';
		html_input += '	     	 	 	<input type="hidden" id="nama_dokumen_old" name="nama_dokumen_old" value="'+v_nama_dokumen+'">';
		html_input += '	     	 	 	<a href="javascript:void(0)" onclick="fl_js_UploadDok()"><img src="../../images/downloadx.png" border="0" alt="Tambah" align="absmiddle" style="height:22px;"> Upload</a>';
		html_input += '	     	 	 	<input type="hidden" id="nama_file_dokumen" name="nama_file_dokumen" value="'+v_nama_file_dokumen+'">';
		html_input += '	     	 	 	<input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen" value="'+v_no_urut_dokumen+'">';
		html_input += '	     	 	 	<input type="hidden" id="url_dokumen" name="url_dokumen" value="'+v_url_dokumen+'">';
		html_input += '	     	 	 	<input type="hidden" id="flag_dok_lengkap" name="flag_dok_lengkap" value="'+v_flag_dok_lengkap+'">';
		html_input += '	     	 	 	<input type="hidden" id="dtl_data" name="dtl_data" value="'+v_dtl_data+'">';
		html_input += '	     	 	 	<span id="span_download_file"></span>';
		html_input += '	     	 	</div>';																		
		html_input += '	       	<div class="clear"></div>';	
		html_input += '	   	 	 </span>';
		html_input += '	   	 	 </br>';	
		html_input += '	 	   </fieldset>';		
		html_input += '	   </div>';
		html_input += '  </div>';
		html_input += '</span>';
						
		if (html_input !="")
		{
			$('#formRinci').html(html_input); 
		}
		
		if (v_new_entry=="Y")
		{
		 	window.document.getElementById("span_button_ok").style.display = 'none';
			window.document.getElementById("span_sedang_sekolah").style.display = 'none';
			window.document.getElementById("span_keterangan").style.display = 'none';
		}else
		{
		 	window.document.getElementById("span_button_ok").style.display = 'block';
			window.document.getElementById("span_keterangan").style.display = 'block';
			fl_js_setCheckedValueRadioButton('rg_status_sekolah', v_flag_terima);
			
			if (v_flag_terima=='Y')
			{
			 	window.document.getElementById("span_sedang_sekolah").style.display = 'block';
				window.document.getElementById("span_lembaga").style.display = 'block';  
				window.document.getElementById("span_dokumen_upload").style.display = 'block'; 
				if (v_nama_file_dokumen != "" && v_url_dokumen != "") 
				{
         		 	var html_download_file = '<a href="javascript:void(0)" onclick="fl_js_DownloadDok()"> | File: '+v_nama_file_dokumen+'</a>';
          
					$("#span_download_file").html(html_download_file);
				}
		
				if (v_jenis == 'PELATIHAN')
				{
						$('#span_label_lembaga').html('<label style = "text-align:right;">Lembaga Pelatihan *</label>');	 
				}else
				{
					$('#span_label_lembaga').html('<label style = "text-align:right;">Nama Sekolah/Universitas *</label>'); 
				}					  
			}else
			{
			 	window.document.getElementById("span_sedang_sekolah").style.display = 'none';
				window.document.getElementById("span_dokumen_upload").style.display = 'none';  	 
			}
			
			fl_js_setCheckedValueRadioButton('rg_flag_dok_lengkap', v_flag_dok_lengkap);
			if (v_flag_dok_lengkap=='Y')
			{
    		window.document.getElementById("span_dok_lengkap").style.display = 'block';
				window.document.getElementById("span_dok_tidaklengkap").style.display = 'none';			 	 
			}else
			{
				$('#span_ket_dok_tidaklengkap').html('<em>Manfaat untuk tahun '+v_tahun+'<font color="#ff0000"> ditunda</font>.<br />Lakukan penetapan ulang apabila dokumen untuk tahun tersebut sudah lengkap..</em>'); 			 
				
				window.document.getElementById("span_dok_tidaklengkap").style.display = 'block';
				window.document.getElementById("span_dok_lengkap").style.display = 'none';			
			}			
		}
		
		//set list jenis beasiswa --------------------------------------------------
		var v_tipe_jnsbeas = "KLMJNSBEAS";
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getlist_mslookup',
				v_tipe: v_tipe_jnsbeas
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
						if (jdata.dtList.DATA)
            {
							for($i=0;$i<(jdata.dtList.DATA.length);$i++)
              {
								//tampilkan semua pilihan ----------------------------
								if (jdata.dtList.DATA[$i]['KODE'] == v_jenis)
								{
								 	$("#jenis").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'" selected="selected">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');
								}else
								{
								 	$("#jenis").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');	 
								}
							}
            }				 	 
					} else {
						alert(jdata.msg);
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				asyncPreload(false);
			}
		});							
		//end set list jenis beasiswa ----------------------------------------------
		
		//set field jenjang --------------------------------------------------------
		var html_jenjang = "";
		if (v_jenis!="")
		{
  		if (v_jenis == 'BEASISWA')
  		{
        html_jenjang += '<div class="form-row_kiri">';
        html_jenjang += '<label style = "text-align:right;width:150px;">Jenjang Pendidikan *</label>';
        html_jenjang += '  <select size="1" id="jenjang" name="jenjang" tabindex="2" class="select_format" style="width:255px;background-color:#ffff99;" onchange="fjq_ajax_val_hitung_manfaat();">';
        html_jenjang += '	   <option value="">-- Pilih Jenjang Pendidikan --</option>';
        html_jenjang += '	 </select>';
        html_jenjang += '</div>';
        html_jenjang += '<div class="clear"></div>';

        if (html_jenjang !="")
        {
         	$('#span_jenjang').html(html_jenjang); 
        }
				
				//set data list jenjang ------------------------------------------------			
    		var v_tipe_jenjang = "TKSKLHPP82";
    		$.ajax({
    			type: 'POST',
    			url: "../ajax/pn5061_action.php?"+Math.random(),
    			data: {
    				tipe: 'fjq_ajax_val_getlist_mslookup',
    				v_tipe: v_tipe_jenjang
    			},
    			success: function(data){
    				try {
    					jdata = JSON.parse(data);
    					if (jdata.ret == 0) 
    					{
    						if (jdata.dtList.DATA)
                {
    							for($i=0;$i<(jdata.dtList.DATA.length);$i++)
                  {
    								//tampilkan semua pilihan ----------------------------
    								if (jdata.dtList.DATA[$i]['KODE'] == v_jenjang)
    								{
    								 	$("#jenjang").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'" selected="selected">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');
    								}else
    								{
    								 	$("#jenjang").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');	 
    								}
    							}
                }				 	 
    					} else {
    						alert(jdata.msg);
    					}
    				} catch (e) {
    					alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				}
    				asyncPreload(false);
    			},
    			complete: function(){
    				asyncPreload(false);
    			},
    			error: function(){
    				alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				asyncPreload(false);
    			}
    		});		
				//end set data list jenjang --------------------------------------------
  		}else
  		{
        html_jenjang += '<div class="form-row_kiri">';
        html_jenjang += '<label style = "text-align:right;width:150px;">Jenis Pelatihan *</label>';
        html_jenjang += '	 <input type="text" id="jenjang" name="jenjang" '+v_jenjang+' tabindex="2" style="width:250px;background-color:#ffff99;" maxlength="100" onkeyup="this.value = this.value.toUpperCase();" onblur="fjq_ajax_val_hitung_manfaat();">';
        html_jenjang += '</div>';
        html_jenjang += '<div class="clear"></div>';	
				
        if (html_jenjang !="")
        {
         	$('#span_jenjang').html(html_jenjang); 
        }		
  		}
		}	
		//end set field jenjang ----------------------------------------------------
								
	}

	function fjq_ajax_val_onloadformView()
	{
	 	var v_tahun 			= $('#tahun').val();			 
    var v_flag_terima = $('#tmp_flag_terima').val() == '' ? 'T' : $('#tmp_flag_terima').val();
		var v_jenis 			= $('#tmp_jenis').val();
		var v_jenjang 		= $('#tmp_jenjang').val();
		var v_tingkat 		= $('#tmp_tingkat').val();
		var v_lembaga 		= $('#tmp_lembaga').val();
		var v_keterangan 	= $('#tmp_keterangan').val();
		var v_nom_manfaat = parseFloat($('#tmp_nom_manfaat').val());
		
		var v_nama_dokumen 			= $('#tmp_nama_dokumen').val();
		var v_nama_file_dokumen = $('#tmp_nama_file_dokumen').val();
		var v_no_urut_dokumen 	= $('#tmp_no_urut_dokumen').val();
		var v_url_dokumen 			= $('#tmp_url_dokumen').val();
		var v_flag_dok_lengkap  = $('#tmp_flag_dok_lengkap').val();			
		
		//set form entry rincian manfaat -----------------------------------
		if (v_flag_terima=="T" && v_keterangan=="")
		{
		 	v_new_entry = "Y";
		}else
		{
		 	v_new_entry = "T";
		}
		
		var html_input  = '';
		html_input += '<div class="div-row">';
		html_input += '	 <div class="div-col" style="width:100%;">';
		html_input += '	   <fieldset><legend>';
    html_input += '    	 	 <strong><em><font color="#009999">Apakah di tahun '+v_tahun+' anak sedang menempuh pendidikan/pelatihan?</font></em></strong>';
		html_input += '    	 	 <input disabled type="radio" name="rg_status_sekolah" id="rg_status_sekolah_t" value="T" onClick="fl_js_set_fieldset_stsekolah();">&nbsp;&nbsp;<strong><font color="#009999">Tidak</font>';
    html_input += '    	 	 <input disabled type="radio" name="rg_status_sekolah" id="rg_status_sekolah_y" value="Y" onClick="fl_js_set_fieldset_stsekolah();">&nbsp;&nbsp;<font color="#009999">Ya</font></strong>';																																	
		html_input += '	     	 <input type="hidden" id="status_sekolah" name="status_sekolah" value="'+v_flag_terima+'">';
		html_input += '	   </legend>';
		html_input += '	   	 </br>';

		html_input += '	   	 <span id="span_sedang_sekolah" style="display:none;">';
    html_input += '	       <div class="form-row_kiri">';
    html_input += '	         <label style = "text-align:right;width:150px;">Jenis Beasiswa *</label>';
    html_input += '	           <select size="1" id="jenis" name="jenis" tabindex="2" class="select_format" style="width:255px;background-color:#f5f5f5;" disabled>';
    html_input += '	           	 <option value="">-- Pilih Jenis Beasiswa --</option>';
    html_input += '	     			 </select>';
    html_input += '	         </div>';
    html_input += '	       <div class="clear"></div>';
		
		html_input += '	   	 	 <span id="span_jenjang"></span>';
		
    html_input += '				 <div class="form-row_kiri">';
    html_input += '				 <label style = "text-align:right;width:150px;">Tingkat/Kelas *</label>';
    html_input += '	 			   <input type="text" id="tingkat" name="tingkat" value="'+v_tingkat+'" tabindex="4" style="width:250px;" maxlength="100" readonly class="disabled">';
    html_input += '				 </div>';
    html_input += '				 <div class="clear"></div>';	
								
		html_input += '	   	 </span>';

		html_input += '	   	 <span id="span_lembaga" style="display:none;">';
    html_input += '				 <div class="form-row_kiri">';
		html_input += '	   	   <label  style = "text-align:right;width:150px;"><span id="span_label_lembaga"></label>';
    html_input += '	 			   <input type="text" id="lembaga" name="lembaga" value="'+v_lembaga+'" tabindex="5" style="width:250px;" maxlength="100" readonly class="disabled">';
    html_input += '				 </div>';
    html_input += '				 <div class="clear"></div>';	
		html_input += '	   	 </span>';
				
		html_input += '	   	 <span id="span_keterangan" style="display:none;">';
    html_input += '	   	   <div class="form-row_kiri">';
    html_input += '	   	   <label style = "text-align:right;width:150px;">Keterangan *</label>';
    html_input += '	   	   	 <textarea cols="255" rows="1" style="width:250px;height:30px;background-color:#f5f5f5;" id="keterangan" name="keterangan" tabindex="6" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly>'+v_keterangan+'</textarea>';   					
    html_input += '	   	   </div>';								
    html_input += '	   	   <div class="clear"></div>';
																								
    html_input += '	   	   <div class="form-row_kiri">';
    html_input += '	   	   <label  style = "text-align:right;width:150px;">Manfaat Beasiswa </label>';
    html_input += '	   	     <input type="text" id="nom_manfaat" name="nom_manfaat" value="'+format_uang(v_nom_manfaat)+'" maxlength="20" style="width:230px;text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
    html_input += '	   	   </div>';																		
    html_input += '	   	   <div class="clear"></div>';				
		html_input += '	   	 </span>';
			
		html_input += '	 	 </fieldset>';		
		html_input += '	 </div>';
		html_input += '</div>';				 				 
		
		html_input += '<span id="span_dokumen_upload" style="display:none">';
		html_input += '	 <div class="div-row">';
		html_input += '	   <div class="div-col" style="width:100%;">';
		html_input += '	     <fieldset><legend>';
    html_input += '    	 	 <strong><em><font color="#009999">Apakah dokumen kelengkapan administrasi untuk tahun '+v_tahun+' lengkap?</font></em></strong>';
		html_input += '    	 	 <input disabled type="radio" name="rg_flag_dok_lengkap" value="T" onClick="fl_js_set_ket_dok_tidaklengkap();">&nbsp;&nbsp;<strong><font color="#009999">Tidak</font>';
    html_input += '    	 	 <input disabled type="radio" name="rg_flag_dok_lengkap" value="Y" onClick="fl_js_set_dok_lengkap();">&nbsp;&nbsp;<font color="#009999">Ya</font></strong>';																																	
		html_input += '	   	 </legend>';
		html_input += '	   	 	 </br>';
		html_input += '			 	 <span id="span_dok_tidaklengkap" style="display:none">';
		html_input += '	     	 	 <span id="span_ket_dok_tidaklengkap"></span>';
		html_input += '	   	 	 </span>';
		html_input += '			 	 <span id="span_dok_lengkap" style="display:none">';
    html_input += '	       	 <div class="form-row_kiri">';
    html_input += '	       	 <label style = "text-align:right;width:150px;">Dokumen *</label>';
    html_input += '	     	 	 	 <input type="text" id="nama_dokumen" name="nama_dokumen" value="'+v_nama_dokumen+'" maxlength="100" style="width:230px;" onkeyup="this.value = this.value.toUpperCase();" readonly class="disabled">';
    html_input += '	     	 	 	 <input type="hidden" id="nama_dokumen_old" name="nama_dokumen_old" value="'+v_nama_dokumen+'">';
		html_input += '	     	 	 	 <input type="hidden" id="nama_file_dokumen" name="nama_file_dokumen" value="'+v_nama_file_dokumen+'">';
    html_input += '	     	 	 	 <input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen" value="'+v_no_urut_dokumen+'">';
    html_input += '	     	 	 	 <input type="hidden" id="url_dokumen" name="url_dokumen" value="'+v_url_dokumen+'">';
		html_input += '	     	 	 	 <input type="hidden" id="flag_dok_lengkap" name="flag_dok_lengkap" value="'+v_flag_dok_lengkap+'">';
    html_input += '	     	 	 	 <span id="span_download_file"></span>';
    html_input += '	     	 	 </div>';																		
    html_input += '	       	 <div class="clear"></div>';
		html_input += '	   	 	 </span>';
		html_input += '	   	 	 </br>';	
		html_input += '	 	   </fieldset>';		
		html_input += '	   </div>';
		html_input += '  </div>';
		html_input += '</span>';
						
    if (html_input !="")
    {
     	$('#formRinci').html(html_input); 
    }
		
		if (v_new_entry=="Y")
		{
		 	window.document.getElementById("span_button_ok").style.display = 'none';
			window.document.getElementById("span_sedang_sekolah").style.display = 'none';
			window.document.getElementById("span_keterangan").style.display = 'none';
		}else
		{
		 	window.document.getElementById("span_button_ok").style.display = 'none';
			window.document.getElementById("span_button_tutup").style.display = 'none';
			window.document.getElementById("span_keterangan").style.display = 'block';
			fl_js_setCheckedValueRadioButton('rg_status_sekolah', v_flag_terima);
			
			if (v_flag_terima=='Y')
			{
			 	window.document.getElementById("span_sedang_sekolah").style.display = 'block';
				window.document.getElementById("span_lembaga").style.display = 'block';  
				window.document.getElementById("span_dokumen_upload").style.display = 'block'; 
				if (v_nama_file_dokumen != "" && v_url_dokumen != "") {
          var html_download_file = '<a href="javascript:void(0)" onclick="fl_js_DownloadDok()"> | File: '+v_nama_file_dokumen+'</a>';
          
          $("#span_download_file").html(html_download_file);
        }
		
    		if (v_jenis == 'PELATIHAN')
    		{
			 	 	$('#span_label_lembaga').html('<label style = "text-align:right;">Lembaga Pelatihan *</label>');	 
  			}else
  			{
  			 	$('#span_label_lembaga').html('<label style = "text-align:right;">Nama Sekolah/Universitas *</label>'); 
  			}					  
			}else
			{
			 	window.document.getElementById("span_sedang_sekolah").style.display = 'none';
				window.document.getElementById("span_dokumen_upload").style.display = 'none';  	 
			}
			
			fl_js_setCheckedValueRadioButton('rg_flag_dok_lengkap', v_flag_dok_lengkap);
			if (v_flag_dok_lengkap=='Y')
			{
    		window.document.getElementById("span_dok_lengkap").style.display = 'block';
				window.document.getElementById("span_dok_tidaklengkap").style.display = 'none';			 	 
			}else
			{
    		$('#span_ket_dok_tidaklengkap').html('<em>Manfaat untuk tahun '+v_tahun+'<font color="#ff0000"> ditunda</font></em>'); 			 
    		
    		window.document.getElementById("span_dok_tidaklengkap").style.display = 'block';
    		window.document.getElementById("span_dok_lengkap").style.display = 'none';			
			}
		}
		
		//set list jenis beasiswa --------------------------------------------------
		var v_tipe_jnsbeas = "KLMJNSBEAS";
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getlist_mslookup',
				v_tipe: v_tipe_jnsbeas
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
						if (jdata.dtList.DATA)
            {
							for($i=0;$i<(jdata.dtList.DATA.length);$i++)
              {
								//tampilkan semua pilihan ----------------------------
								if (jdata.dtList.DATA[$i]['KODE'] == v_jenis)
								{
								 	$("#jenis").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'" selected="selected">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');
								}else
								{
								 	$("#jenis").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');	 
								}
							}
            }				 	 
					} else {
						alert(jdata.msg);
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				asyncPreload(false);
			}
		});							
		//end set list jenis beasiswa ----------------------------------------------
		
		//set field jenjang --------------------------------------------------------
		var html_jenjang = "";
		if (v_jenis!="")
		{
  		if (v_jenis == 'BEASISWA')
  		{
        html_jenjang += '<div class="form-row_kiri">';
        html_jenjang += '<label style = "text-align:right;width:150px;">Jenjang Pendidikan *</label>';
        html_jenjang += '  <select size="1" id="jenjang" name="jenjang" tabindex="2" class="select_format" style="width:255px;background-color:#f5f5f5;" disabled>';
        html_jenjang += '	   <option value="">-- Pilih Jenjang Pendidikan --</option>';
        html_jenjang += '	 </select>';
        html_jenjang += '</div>';
        html_jenjang += '<div class="clear"></div>';

        if (html_jenjang !="")
        {
         	$('#span_jenjang').html(html_jenjang); 
        }
				
				//set data list jenjang ------------------------------------------------			
    		var v_tipe_jenjang = "TKSKLHPP82";
    		$.ajax({
    			type: 'POST',
    			url: "../ajax/pn5061_action.php?"+Math.random(),
    			data: {
    				tipe: 'fjq_ajax_val_getlist_mslookup',
    				v_tipe: v_tipe_jenjang
    			},
    			success: function(data){
    				try {
    					jdata = JSON.parse(data);
    					if (jdata.ret == 0) 
    					{
    						if (jdata.dtList.DATA)
                {
    							for($i=0;$i<(jdata.dtList.DATA.length);$i++)
                  {
    								//tampilkan semua pilihan ----------------------------
    								if (jdata.dtList.DATA[$i]['KODE'] == v_jenjang)
    								{
    								 	$("#jenjang").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'" selected="selected">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');
    								}else
    								{
    								 	$("#jenjang").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');	 
    								}
    							}
                }				 	 
    					} else {
    						alert(jdata.msg);
    					}
    				} catch (e) {
    					alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				}
    				asyncPreload(false);
    			},
    			complete: function(){
    				asyncPreload(false);
    			},
    			error: function(){
    				alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				asyncPreload(false);
    			}
    		});		
				//end set data list jenjang --------------------------------------------
  		}else
  		{
        html_jenjang += '<div class="form-row_kiri">';
        html_jenjang += '<label style = "text-align:right;width:150px;">Jenis Pelatihan *</label>';
        html_jenjang += '	 <input type="text" id="jenjang" name="jenjang" '+v_jenjang+' tabindex="2" style="width:250px;" maxlength="100" readonly class="disabled">';
        html_jenjang += '</div>';
        html_jenjang += '<div class="clear"></div>';	
				
        if (html_jenjang !="")
        {
         	$('#span_jenjang').html(html_jenjang); 
        }		
  		}
		}	
		//end set field jenjang ----------------------------------------------------
								
	}
		
	function fl_js_postchange_jenisbeasiswa()
	{
	 	var	v_jenis = $('#jenis').val();
		
		var html_jenjang = '';
		if (v_jenis!="")
		{
  		//set field jenjang ------------------------------------------------------
			if (v_jenis == 'BEASISWA')
  		{
        html_jenjang += '<div class="form-row_kiri">';
        html_jenjang += '<label style = "text-align:right;width:150px;">Jenjang Pendidikan *</label>';
        html_jenjang += '  <select size="1" id="jenjang" name="jenjang" tabindex="2" class="select_format" style="width:255px;background-color:#ffff99;" onchange="fjq_ajax_val_hitung_manfaat();">';
        html_jenjang += '	   <option value="">-- Pilih Jenjang Pendidikan --</option>';
        html_jenjang += '	 </select>';
        html_jenjang += '</div>';
        html_jenjang += '<div class="clear"></div>';

        if (html_jenjang !="")
        {
         	$('#span_jenjang').html(html_jenjang); 
        }
							
				//set data list jenjang ------------------------------------------------
    		fl_js_reset_dataList('jenjang');
    		$("#jenjang").append('<option value="">-- Pilih Jenjang Pendidikan --</option>');
    		
    		var v_tipe_jenjang = "TKSKLHPP82";
    		$.ajax({
    			type: 'POST',
    			url: "../ajax/pn5061_action.php?"+Math.random(),
    			data: {
    				tipe: 'fjq_ajax_val_getlist_mslookup',
    				v_tipe: v_tipe_jenjang
    			},
    			success: function(data){
    				try {
    					jdata = JSON.parse(data);
    					if (jdata.ret == 0) 
    					{
    						if (jdata.dtList.DATA)
                {
    							for($i=0;$i<(jdata.dtList.DATA.length);$i++)
                  {
    								//tampilkan semua pilihan ----------------------------
    								$("#jenjang").append('<option value="'+jdata.dtList.DATA[$i]['KODE']+'">'+jdata.dtList.DATA[$i]['KETERANGAN']+'</option>');	 
    							}
                }				 	 
    					} else {
    						alert(jdata.msg);
    					}
    				} catch (e) {
    					alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				}
    				asyncPreload(false);
    			},
    			complete: function(){
    				asyncPreload(false);
    			},
    			error: function(){
    				alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				asyncPreload(false);
    			}
    		});				
				//end set data list jenjang --------------------------------------------					 	 
  		}else
  		{
        html_jenjang += '<div class="form-row_kiri">';
        html_jenjang += '<label style = "text-align:right;width:150px;">Jenis Pelatihan *</label>';
        html_jenjang += '	 <input type="text" id="jenjang" name="jenjang" tabindex="2" style="width:250px;background-color:#ffff99;" maxlength="100" onkeyup="this.value = this.value.toUpperCase();" onblur="fjq_ajax_val_hitung_manfaat();">';
        html_jenjang += '</div>';
        html_jenjang += '<div class="clear"></div>';	
				
        if (html_jenjang !="")
        {
         	$('#span_jenjang').html(html_jenjang); 
        }		
  		}
			//end set field jenjang --------------------------------------------------
		
			//set label lembaga ------------------------------------------------------
			window.document.getElementById("span_lembaga").style.display = 'block';  
				
    	if (v_jenis == 'PELATIHAN')
    	{
			 	$('#span_label_lembaga').html('<label style = "text-align:right;">Lembaga Pelatihan *</label>');	 
  		}else
  		{
  		 	$('#span_label_lembaga').html('<label style = "text-align:right;">Nama Sekolah/Universitas *</label>'); 
  		}				
		}
		
		fjq_ajax_val_hitung_manfaat();			 
	}

	function fl_js_reset_dataList(id)
	{
  	var selectObj = document.getElementById(id);
  	var selectParentNode = selectObj.parentNode;
  	var newSelectObj = selectObj.cloneNode(false); // Make a shallow copy
  	selectParentNode.replaceChild(newSelectObj, selectObj);
  	return newSelectObj;
	}
	
	function fl_js_set_fieldset_stsekolah_t()
	{
	 	$('#status_sekolah').val('T');
		fl_js_set_fieldset_stsekolah();		 
	}

	function fl_js_set_fieldset_stsekolah_y()
	{
	 	$('#status_sekolah').val('Y');
		fl_js_set_fieldset_stsekolah();		 
	}
				
	function fl_js_set_fieldset_stsekolah() 
  {
		//var v_status_sekolah = $("input[name='rg_status_sekolah']:checked").val();
		var v_status_sekolah = $('#status_sekolah').val();
		v_status_sekolah = v_status_sekolah == "" ? "X" : v_status_sekolah;
		
		if (v_status_sekolah=='Y')
		{
		 	//sudah sekolah -------------------------------------
			window.document.getElementById("span_sedang_sekolah").style.display = 'block';
			window.document.getElementById("span_dokumen_upload").style.display = 'block';  
		}else
		{
		 	//belum sekolah/blm dipilih --------------------------
			window.document.getElementById("span_sedang_sekolah").style.display = 'none';
			window.document.getElementById("span_dokumen_upload").style.display = 'none';
		}
		
		window.document.getElementById("span_keterangan").style.display = 'block';
		window.document.getElementById("span_lembaga").style.display = 'none';
		
		//reset field ------------------------------------------
		$('#jenis').val('');
		$('#jenjang').val('');
		$('#tingkat').val('');
		$('#lembaga').val('');
		$('#keterangan').val('');
		$('#nom_manfaat').val('0');
		$('#nama_dokumen').val('');
		$('#nama_file_dokumen').val('');
		$('#no_urut_dokumen').val('');
		$('#url_dokumen').val('');		
		//end reset field --------------------------------------
		
		window.document.getElementById("span_button_ok").style.display = 'block';		 
	}
	
  function fl_js_setCheckedValueRadioButton(vRadioObj, vValue) 
	{
		var radios = document.getElementsByName(vRadioObj);
    //reset value ----------------
		for (var j = 0; j < radios.length; j++) {
				radios[j].checked = false;
    }
		//assign value ----------------	
    for (var j = 0; j < radios.length; j++) {
				radios[j].checked = false;
        if (radios[j].value == vValue) {
            radios[j].checked = true;
            break;
        }
    }		
  }
	
  function fjq_ajax_val_hitung_manfaat()
  {			 					
		var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		var v_no_urut 		 = $('#no_urut').val();
		var v_nik_penerima = $('#nik_penerima').val();
		var v_tahun 			 = $('#tahun').val();
		var v_jenis 			 = $('#jenis').val();
		var v_jenjang 		 = $('#jenjang').val();
		var v_flag_dok_lengkap = $('#flag_dok_lengkap').val();
		
		if (v_kode_klaim != '' && v_kode_manfaat != '' && v_nik_penerima != '' && v_tahun != '' && v_jenis != '' && v_jenjang != '') 
		{
  		//hitung manfaat beasiswa ------------------------------------------------
  		$.ajax({
  			type: 'POST',
  			url: "../ajax/pn5061_action.php?"+Math.random(),
  			data: {
  				tipe: 'fjq_ajax_val_hitungmnf_beasiswapp82',
          v_kode_klaim: v_kode_klaim,
          v_kode_manfaat: v_kode_manfaat,
          v_no_urut: v_no_urut,
          v_nik_penerima: v_nik_penerima,
          v_tahun: v_tahun,
          v_jenis: v_jenis,
          v_jenjang: v_jenjang
  			},
  			success: function(data){
  				try {
  					jdata = JSON.parse(data);
  					if (jdata.ret == 0) 
  					{
        		 	if (v_flag_dok_lengkap=='T')
							{
							 	$('#nom_manfaat').val(0);
  						} else
							{
							 	$('#nom_manfaat').val(format_uang(getValueNumber(jdata.dtMnf.P_NOM_DISETUJUI)));
							}
						} else {
  						alert(jdata.msg);
  					}
  				} catch (e) {
  					alert("Terjadi kesalahan, coba beberapa saat lagi!");
  				}
  				asyncPreload(false);
  			},
  			complete: function(){
  				asyncPreload(false);
  			},
  			error: function(){
  				alert("Terjadi kesalahan, coba beberapa saat lagi!");
  				asyncPreload(false);
  			}
  		});
		}else
		{
		 	$('#nom_manfaat').val('0');	 
		}
  }
	
	function fl_js_UploadDok()
	{
	 	var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_nik_penerima = $('#nik_penerima').val();
		var v_no_urut 		 = $('#no_urut').val();
		var v_tahun 		 	 = $('#tahun').val();
		
		var v_nama_dokumen = $('#nama_dokumen').val();
		var v_no_urut_dokumen = $('#no_urut_dokumen').val();
		
		if (v_nama_dokumen=='')
		{
		 	alert('NAMA DOKUMEN KOSONG, HARAP MELENGKAPI DATA INPUT..!');
		}else
		{					 
    	let params = 'p=pn5041.php&a=formreg&kode_klaim='+v_kode_klaim+'&nik_penerima='+v_nik_penerima+'&tahun='+v_tahun+'&nama_dokumen='+v_nama_dokumen+'&no_urut_dokumen='+v_no_urut_dokumen+'&sender=pn5061_penetapanmanfaat_beasiswaentrypp82detil.php';
    	NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5061_penetapanmanfaat_beasiswaentrypp82detiluploaddok.php?' + params,'upload_lampiran',550,400,'yes');
		}
	}

	function fl_js_DownloadDok()
	{
	 	var v_url 	 = $('#url_dokumen').val();
		var v_nmfile = $('#nama_file_dokumen').val();
					 
		let p = btoa(v_url);
		let f = btoa(v_nmfile);
		let u = btoa('<?=$gs_kode_user;?>');
		let params = 'p='+p+'&f='+f+'&u='+u;
		NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5065_download_dok.php?' + params,'',1000,620,'no');
	}
	
	function fl_js_setval_postuploaddok(v_url, v_nmfile, v_no_urut_dok)
	{
		$('#url_dokumen').val(v_url);
		$('#nama_file_dokumen').val(v_nmfile);
		$('#no_urut_dokumen').val(v_no_urut_dok);
		
		if (v_nmfile != "" && v_url != "") {
      var html_download_file = '<a href="javascript:void(0)" onclick="fl_js_DownloadDok()"> | File: '+v_nmfile+'</a>';
			
    	$("#span_download_file").html(html_download_file);
		}									 
	}
	
	function fl_js_set_perubahan_nama_dokumen() 
  {
	 	var v_nama_dokumen 		 = $('#nama_dokumen').val();
		var v_nama_dokumen_old = $('#nama_dokumen_old').val();
		
		if (v_nama_dokumen != v_nama_dokumen_old && v_nama_dokumen_old!='' && v_nama_dokumen!='')
		{
		 	 $('#nama_dokumen_old').val(v_nama_dokumen);
			 $('#nama_file_dokumen').val('');
			 $('#url_dokumen').val('');
			 $("#span_download_file").html('');
		 	 alert('Ada perubahan nama dokumen, harap melakukan upload ulang dokumen tersebut..!');
		}				 
	}
		
	async function fjq_ajax_val_doButtonOK()
	{

		var v_status_sekolah 		= $('#status_sekolah').val();
		
		if (v_status_sekolah=='Y'){
		let checkMaxThnBeasiswaString = await checkMaxThnBeasiswa();
          let checkMaxThnBeasiswaResult       = JSON.parse(checkMaxThnBeasiswaString);
          if(checkMaxThnBeasiswaResult.ret!='0'){
            return alert(checkMaxThnBeasiswaResult.msg);
          }
		}  
		  
		var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_nik_penerima	 = $('#nik_penerima').val();
	 	var v_i 								= $('#vi').val();
		var v_tahun 						= $('#tahun').val();
		//var v_status_sekolah 		= $("input[name='rg_status_sekolah']:checked").val();
		
		var v_jenis 						= $('#jenis').val();
		var v_jenjang 					= $('#jenjang').val();
		var v_tingkat 					= $('#tingkat').val();
		var v_lembaga 					= $('#lembaga').val();
		var v_keterangan 				= $('#keterangan').val();
		var v_nom_manfaat 			= parseFloat(removeCommas($('#nom_manfaat').val()),2);
		var v_nama_dokumen 			= $('#nama_dokumen').val();
		var v_nama_file_dokumen	= $('#nama_file_dokumen').val();
		var v_no_urut_dokumen		= $('#no_urut_dokumen').val();
		var v_url_dokumen				= $('#url_dokumen').val();
		var v_dtl_data				= $('#dtl_data').val();
		var v_flag_dok_lengkap	= $("input[name='rg_flag_dok_lengkap']:checked").val();
		if (v_flag_dok_lengkap!="Y" && v_flag_dok_lengkap!="T")
		{
		 	v_flag_dok_lengkap = "X"; 
		}
				
		var v_flag_terima = "T";
		if (v_status_sekolah =="")
		{
		 	alert('Harap memilih apakah di tahun '+v_tahun+' anak sedang menempuh pendidikan/pelatihan atau tidak..!'); 
		}else
		{
  		v_flag_terima = v_status_sekolah;
			if (v_status_sekolah == "Y" && (v_flag_dok_lengkap =="" || v_flag_dok_lengkap =="X"))
  		{
  		 	alert('Harap memilih apakah dokumen kelengkapan administrasi untuk tahun '+v_tahun+' lengkap atau tidak..!'); 
			}		 
		}
    	v_flag_terima = v_flag_terima == '' ? 'T' : v_flag_terima;
		
		var v_error = "T";
		
		if (v_i == "")
		{
		 	v_error = "Y";
			alert('Indeks row data kosong, harap menutup form entry detil manfaat beasiswa kemudian diulangi proses..!');  
		}else if (v_tahun == "")
		{
		 	v_error = "Y";
			alert('Data tahun kosong, harap menutup form entry detil manfaat beasiswa kemudian diulangi proses..!');		
		}else if (v_flag_terima == "")
		{
		 	v_error = "Y";
			alert('Harap memilih apakah di tahun '+v_tahun+' anak sedang menempuh pendidikan/pelatihan atau tidak..!');		
		}else if (v_flag_terima == "T" && v_keterangan == "")
		{
		 	v_error = "Y";
			alert('Untuk pilihan TIDAK SEDANG MENEMPUH PENDIDIKAN di tahun '+v_tahun+', keterangan tidak boleh kosong, harap melengkapi data input..!');
			$('#keterangan').focus();		
		}else if (v_flag_terima == "Y")
		{
		 	if (v_jenis == "")
			{		
  		 	v_error = "Y";
				alert('Jenis Beasiswa kosong, harap melengkapi data input..!');
  			$('#jenis').focus();
			}else if (v_jenis == "BEASISWA" && (v_jenjang=="" || v_tingkat=="" || v_lembaga=="" || v_keterangan == "" || (v_flag_dok_lengkap=="Y" && v_nama_dokumen == "") || (v_flag_dok_lengkap=="Y" && v_nama_file_dokumen == "")))
			{
			 	if (v_jenjang=="")
				{
				 	v_error = "Y";
					alert('Jenjang Pendidikan kosong, harap melengkapi data input..!');
  				$('#jenjang').focus();
				}else if (v_tingkat=="")
				{
				 	v_error = "Y";
					alert('Tingkat/Kelas kosong, harap melengkapi data input..!');
  				$('#tingkat').focus();
				}else if (v_lembaga=="")
				{
				 	v_error = "Y";
					alert('Nama Sekolah/Universitas kosong, harap melengkapi data input..!');
  				$('#lembaga').focus();					
				}else if (v_keterangan=="")
				{
				 	v_error = "Y";
					alert('Keterangan kosong, harap melengkapi data input..!');
  				$('#keterangan').focus();
				}else if (v_flag_dok_lengkap=="Y" && v_nama_dokumen == "")
				{
				 	v_error = "Y";
					alert('Nama Dokumen kosong, harap melengkapi data input..!');
  				$('#nama_dokumen').focus();
				}else if (v_flag_dok_lengkap=="Y" && v_nama_file_dokumen == "")
				{
				 	v_error = "Y";
					alert('Dokumen kelengkapan administrasi belum diupload, harap melengkapi data input..!');
  				$('#nama_dokumen').focus();
				}
			}else if (v_jenis == "PELATIHAN" && (v_jenjang=="" || v_tingkat=="" || v_lembaga=="" || v_keterangan == "" || (v_flag_dok_lengkap=="Y" && v_nama_dokumen == "") || (v_flag_dok_lengkap=="Y" && v_nama_file_dokumen == "")))
			{
			 	if (v_jenjang=="")
				{
				 	v_error = "Y";
					alert('Jenis Pelatihan kosong, harap melengkapi data input..!');
  				$('#jenjang').focus();
				}else if (v_tingkat=="")
				{
				 	v_error = "Y";
					alert('Tingkat Pelatihan kosong, harap melengkapi data input..!');
  				$('#tingkat').focus();
				}else if (v_lembaga=="")
				{
				 	v_error = "Y";
					alert('Nama Lembaga Pelatihan kosong, harap melengkapi data input..!');
  				$('#lembaga').focus();					
				}else if (v_keterangan=="")
				{
				 	v_error = "Y";
					alert('Keterangan kosong, harap melengkapi data input..!');
  				$('#keterangan').focus();
				}else if (v_flag_dok_lengkap=="Y" && v_nama_dokumen == "")
				{
				 	v_error = "Y";
					alert('Nama Dokumen kosong, harap melengkapi data input..!');
  				$('#nama_dokumen').focus();
				}else if (v_flag_dok_lengkap=="Y" && v_nama_file_dokumen == "")
				{
				 	v_error = "Y";
					alert('Dokumen kelengkapan administrasi belum diupload, harap melengkapi data input..!');
  				$('#nama_dokumen').focus();
				}			
			}		
		}
			
		if (v_error == "T")
		{
		 	if (v_flag_terima=='T')
			{
			 	v_flag_dok_lengkap = 'T'; 
			}
			
			window.parent.Ext.WindowManager.getActive().parent.fl_js_setval_postentrydetilbeasiswa
			(
				v_i,
        v_tahun,
    		v_jenis,
    		v_jenjang,
    		v_flag_terima,
    		v_tingkat,
    		v_lembaga,
    		v_keterangan,
    		v_nom_manfaat,
				v_nama_dokumen,
				v_nama_file_dokumen,
				v_no_urut_dokumen,
				v_url_dokumen,
				v_flag_dok_lengkap,
				v_dtl_data
			);   			
			
			window.parent.Ext.WindowManager.getActive().close();
		}
	}

	function checkMaxThnBeasiswa(){

		var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_nik_penerima	 = $('#nik_penerima').val();
		var v_jenis 						= $('#jenis').val();
		var v_jenjang 					= $('#jenjang').val();
	
		return $.ajax({
    			type: 'POST',
    			url: "../ajax/pn5061_action.php?"+Math.random(),
				async : false,
    			data: {
    				tipe: 'fjq_ajax_val_get_max_thn_beasiswa',
    				v_kode_klaim: v_kode_klaim,
					v_nik_penerima:v_nik_penerima,
					v_jenis : v_jenis,
					v_jenjang : v_jenjang
    			},
    			success: function(data){
    				try {
    					jdata = JSON.parse(data);
    					var jdata = JSON.parse(data);
                 		return jdata;
                  
    				} catch (e) {
    					alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				}
    				asyncPreload(false);
    			},
    			complete: function(){
    				asyncPreload(false);
    			},
    			error: function(){
    				alert("Terjadi kesalahan, coba beberapa saat lagi!");
    				asyncPreload(false);
    			}
    		}).responseText;	

	}
	
	function fjq_ajax_val_doButtonTutup()
	{
	 	window.parent.Ext.WindowManager.getActive().close();			 
	}
	
  function fl_js_set_ket_dok_tidaklengkap()
  {
	 	var v_tahun = $('#tahun').val();
		
		$('#flag_dok_lengkap').val('T');
		$('#nom_manfaat').val(0);
		$('#span_ket_dok_tidaklengkap').html('<em>Manfaat untuk tahun '+v_tahun+'<font color="#ff0000"> ditunda</font>.<br />Lakukan penetapan ulang apabila dokumen untuk tahun tersebut sudah lengkap..</em>'); 			 
		
		window.document.getElementById("span_dok_tidaklengkap").style.display = 'block';
		window.document.getElementById("span_dok_lengkap").style.display = 'none';
	}

  function fl_js_set_dok_lengkap()
  {
	 	window.document.getElementById("span_dok_lengkap").style.display = 'block';
		window.document.getElementById("span_dok_tidaklengkap").style.display = 'none';
		$('#flag_dok_lengkap').val('Y');
		fjq_ajax_val_hitung_manfaat(); 			 
	}				
</script>

<?php
include_once "../../includes/footer_app_nosql.php";
?>
		