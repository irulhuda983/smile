<?php
//------------------------------------------------------------------------------
// Menu untuk entry rincian manfaat beasiswa pp82
// dibuat tgl : 08/01/2021
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 	 = "form";
$gs_kodeform 	 	 	 = "PN5002"; 
$chId 	 	 			 	 = "SMILE";
$gs_pagetitle 	 	 = "RINCIAN MANFAAT";											 
$gs_kantor_aktif 	 = $_SESSION['kdkantorrole'];
$gs_kode_user		 	 = $_SESSION["USER"];
$gs_kode_role		 	 = $_SESSION['regrole'];
$editid 				 	 = $_POST['editid'];
$ls_kode_klaim 	 	 = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ls_kode_manfaat 	 = !isset($_POST['kode_manfaat']) ? $_GET['kode_manfaat'] : $_POST['kode_manfaat'];
$ln_no_urut 		 	 = !isset($_POST['no_urut']) ? $_GET['no_urut'] : $_POST['no_urut'];
$task 		 			 	 = !isset($_POST['task']) ? $_GET['task'] : $_POST['task'];
$ls_form_penetapan = !isset($_POST['form_penetapan']) ? $_GET['form_penetapan'] : $_POST['form_penetapan'];
$ls_sender	 			 = !isset($_POST['sender']) ? $_GET['sender'] : $_POST['sender'];

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

<div id="formframe" style="min-width:90%;">
	<div id="div_dummy" style="width:100%;"></div>
	<div id="formKiri" style="width:97%; margin:0px 0px 0px 0px;">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
			<input type="hidden" id="nik_tk" name="nik_tk">
			<input type="hidden" id="nama_tk" name="nama_tk">
			<input type="hidden" id="kode_segmen" name="kode_segmen">
			<input type="hidden" id="jenis_klaim" name="jenis_klaim">
			<input type="hidden" id="tgl_kejadian" name="tgl_kejadian">
			<input type="hidden" id="tgl_kondisi_terakhir" name="tgl_kondisi_terakhir">	
			<input type="hidden" id="tgl_kejadian_yyymmdd" name="tgl_kejadian_yyymmdd">
			<input type="hidden" id="tgl_kondisi_terakhir_yyymmdd" name="tgl_kondisi_terakhir_yyymmdd">
						
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" style="padding-top:0px;">
					<div id="formRinci"></div>	 
				</div>
				
				<div id="div_footer" class="div-footer">
					<div class="div-footer-form" style="width:95%;">
						<div class="div-row">
              <span id="span_button_new" style="display:block;">
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doSaveInsert" href="#" onClick="if(confirm('Apakah anda yakin akan menyimpan data penetapan manfaat beasiswa..?')) fjq_ajax_val_dosave_manfaat_beasiswa();">
                      <img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                      <span>SIMPAN DATA &nbsp;&nbsp;</span>
                      </a>											
                    </div>
                  </div>
                </div>
								
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doSaveDelete" href="#" onClick="if(confirm('Apakah anda yakin akan menghapus data penetapan manfaat beasiswa..?')) fjq_ajax_val_dodelete_manfaat_beasiswa();">
                      <img src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                      <span>HAPUS</span>
                      </a>											
                    </div>
                  </div>
                </div>  									
              </span>
							
							<span id="span_button_tutup" style="display:block;">			 
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doBack2Grid" href="#" onClick="fl_js_back2gridmanfaat();">
                        <img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
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
              <div class="div-footer-content" style="width:95%;">
              <div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
              <li style="margin-left:15px;">Klik <img src="../../images/help.png" alt="Cari Data Peserta" border="0" align="absmiddle"> untuk <font color="#ff0000">memilih</font> NIK Anak penerima manfaat beasiswa.
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
	
	function fl_js_set_tgl_sysdate(v_input)
	{		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1; //January is 0!
    
		var yyyy = today.getFullYear();
		if (dd < 10) {
			dd = '0' + dd;
		} 
		if (mm < 10) {
			mm = '0' + mm;
		} 
		var today = dd + '/' + mm + '/' + yyyy;
		document.getElementById(v_input).value = today;
  	}	
	//end template ---------------------------------------------------------------				
</script>

<script language="javascript">
	$(document).ready(function(){
		let task = $('#task').val();
		let v_kode_klaim = $('#kode_klaim').val();
		let v_kode_manfaat = $('#kode_manfaat').val();
		let v_no_urut = $('#no_urut').val();
		
		if (task=='new')
		{
		 	fl_js_New(); 
		}else if (task=='edit')
		{
		 	fl_js_Edit();	 
		}else
		{
		 	fl_js_View();	 
		}
	});
</script>

<script language="javascript">
	function fl_js_New()
	{
	 	var fn;
		var v_kode_klaim = $('#kode_klaim').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		
		if (v_kode_klaim == '' || v_kode_manfaat == '') {
			return alert('Kode Klaim atau Kode Manfaat tidak boleh kosong');
		}
		
		v_task = $('#task').val();
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_newdatarincianmanfaat',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
					 	$("#span_page_title").html('PN5002 - ENTRI RINCIAN MANFAAT - BEASISWA');
						var v_jenis_klaim = getValue(jdata.InfoKlaim.JENIS_KLAIM);
						var v_tgl_kejadian = getValue(jdata.InfoKlaim.TGL_KEJADIAN);
						var v_nama_kondisi_terakhir = getValue(jdata.InfoKlaim.NAMA_KONDISI_TERAKHIR);
						var v_tgl_kondisi_terakhir = getValue(jdata.InfoKlaim.TGL_KONDISI_TERAKHIR);
						var v_tgl_kejadian = getValue(jdata.InfoKlaim.TGL_KEJADIAN);
						var v_tgl_kejadian_yyymmdd = v_tgl_kejadian.substring(6, 10)+v_tgl_kejadian.substring(3, 5)+v_tgl_kejadian.substring(0, 2);
						var v_tgl_kondisi_terakhir_yyymmdd = v_tgl_kondisi_terakhir.substring(6, 10)+v_tgl_kondisi_terakhir.substring(3, 5)+v_tgl_kondisi_terakhir.substring(0, 2);
						
						$('#tgl_kejadian').val(v_tgl_kejadian);
						$('#tgl_kondisi_terakhir').val(v_tgl_kondisi_terakhir); 
						$('#tgl_kejadian_yyymmdd').val(v_tgl_kejadian_yyymmdd);
						$('#tgl_kondisi_terakhir_yyymmdd').val(v_tgl_kondisi_terakhir_yyymmdd);
						$('#jenis_klaim').val(v_jenis_klaim);
						
						var v_title_right = '';
						
						if (v_jenis_klaim == 'JKK') 
						{
						 	 v_title_right = 'KODE KLAIM : '+v_kode_klaim+' (KONDISI AKHIR '+v_jenis_klaim+' '+v_nama_kondisi_terakhir+' PER '+v_tgl_kondisi_terakhir+')';
						}else
						{
							 v_title_right = 'KODE KLAIM : '+v_kode_klaim+' (TGL KEJADIAN '+v_jenis_klaim+' '+v_tgl_kejadian+')';
						}
						$("#span_page_title_right").html(v_title_right);						

						$('#nik_tk').val(getValue(jdata.InfoKlaim.NOMOR_IDENTITAS));
						$('#nama_tk').val(getValue(jdata.InfoKlaim.NAMA_TK));
						$('#kode_segmen').val(getValue(jdata.InfoKlaim.KODE_SEGMEN));

						//set form entry rincian manfaat -----------------------------------
						var html_input  = '';
						html_input += '<div class="div-row">';
						html_input += '	 <div class="div-col" style="width:39%; max-height: 100%;">';
						html_input += '	   <div class="div-row">';
						html_input += '	     <fieldset><legend>Informasi Penerima Manfaat Beasiswa</legend>';
						html_input += '	     			<div class="div-row">';
						html_input += '	     				<div class="div-col">';
						html_input += '	     					</br>';

						html_input += '	               <div class="form-row_kiri">';
						html_input += '	               <label style = "text-align:right;">NIK Anak *</label>';
						html_input += '	                 <input type="text" id="beasiswa_nik_penerima" name="beasiswa_nik_penerima" tabindex="1" style="width:220px;background-color:#ffff99;" readonly>';				
						html_input += '	               	<a href="#" onclick="fl_js_get_lov_nikpenerimabeasiswa();">';
						html_input += '	     						<img src="../../images/help.png" alt="Cari NIK Penerima Beasiswa" border="0" align="absmiddle"></a>';
						html_input += '	     					</div>';																		
						html_input += '	               <div class="clear"></div>';
																					
						html_input += '	     					<div class="form-row_kiri">';
						html_input += '	               <label style = "text-align:right;">Tipe Penerima *</label>';
						html_input += '	                 <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" tabindex="2" class="select_format" style="width:227px;background-color:#ffff99;">';
						html_input += '	                 	<option value="">-- Pilih Tipe Penerima --</option>';
						html_input += '	     						</select>';
						html_input += '	               </div>';
						html_input += '	               <div class="clear"></div>';
														
						html_input += '	               <div class="form-row_kiri">';
						html_input += '	               <label style = "text-align:right;">Nama Anak &nbsp;&nbsp;</label>';
						html_input += '	               	<input type="text" id="nama_penerima" name="nama_penerima" style="width:220px;" maxlength="100" readonly class="disabled">';
						html_input += '	               </div>';																																																																																													
						html_input += '	               <div class="clear"></div>';
			
						html_input += '	               <div class="form-row_kiri">';
						html_input += '	               <label style = "text-align:right;">Tempat & Tgl Lahir</label>';
						html_input += '	                 <input type="text" id="tempat_lahir" name="tempat_lahir" style="width:146px;" maxlength="50" readonly class="disabled">';
						html_input += '	     						<input type="text" id="tgl_lahir" name="tgl_lahir" style="width:65px;" maxlength="10" readonly class="disabled">';
						html_input += '	           		</div>';																																																																																													
						html_input += '	               <div class="clear"></div>';
			
						html_input += '	           		 <div class="form-row_kiri">';
						html_input += '	           		 <label style = "text-align:right;">Jeis Kelamin</label>';
						html_input += '	             	 	 <input type="text" id="l_jenis_kelamin" name="l_jenis_kelamin" style="width:220px;" readonly class="disabled">';
						html_input += '	             	 	 <input type="hidden" id="jenis_kelamin" name="jenis_kelamin">';
						html_input += '	           		 </div>';																																																																																														
						html_input += '	           		 <div class="clear"></div>';
													
						html_input += '	     					 </br>';
														
						html_input += '	     		    	 <div class="form-row_kiri">';
						html_input += '	     		    	 <label style = "text-align:right;">Alamat Rumah &nbsp;</label>';
						html_input += '	     		    	 	 <textarea cols="255" rows="1" id="alamat" name="alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" style="width:220px;height:40px;background-color:#f5f5f5"></textarea>';		
						html_input += '	     		    	 </div>';			
						html_input += '	     		    	 <div class="clear"></div>';
			
						html_input += '	     		   		 <div class="form-row_kiri">';
						html_input += '	     		   		 <label style = "text-align:right;">Email &nbsp;&nbsp;</label>';
						html_input += '	     		     	 	 <input type="text" id="email" name="email" style="width:220px;" readonly class="disabled">';
						html_input += '	     			 		 </div>';																																					
						html_input += '	     		   		 <div class="clear"></div>';
								
						html_input += '	     	   		 	 <div class="form-row_kiri">';
						html_input += '	     		   		 <label style = "text-align:right;">No. HP &nbsp;&nbsp;</label>';
						html_input += '	     		     	 	 <input type="text" id="handphone" name="handphone" style="width:220px;" readonly class="disabled">';
						html_input += '	     			 		 </div>';																																				
						html_input += '	     		   		 <div class="clear"></div>';
												
						html_input += '	     		   		 <div class="form-row_kiri">';
						html_input += '	     		   		 <label style = "text-align:right;">Nama Ortu/Wali &nbsp;&nbsp;</label>';
						html_input += '	     		     	 	 <input type="text" id="nama_ortu_wali" name="nama_ortu_wali" style="width:200px;" readonly class="disabled">';
						html_input += '	     			 		 </div>';																																						
						html_input += '	     		   		 <div class="clear"></div>';
											 
						html_input += '	     					 </br>';																																		 
						html_input += '	     				</div>';	 
						html_input += '	     			</div>';						
						html_input += '	 	 	 </fieldset>';
						html_input += '	 	 </div>';
						html_input += '	 </div>';
						
						html_input += '	 <div class="div-col" style="width:1%;">';
						html_input += '	 </div>';
						
						html_input += '	 <div class="div-col-right" style="width:60%;">';
						html_input += '	     	<div class="div-row">';
						html_input += '	     		<fieldset><legend>Kondisi Akhir Anak Saat Pengajuan Beasiswa</legend>';
    					html_input += '	     			</br>';
						html_input += '	     			<div class="form-row_kiri">';
						html_input += '	           <label  style = "text-align:right;">Tgl Pengajuan *</label>';
						html_input += '	             <input type="text" id="beasiswa_tgl_pengajuan" name="beasiswa_tgl_pengajuan" maxlength="10" tabindex="3" onblur="convert_date(beasiswa_tgl_pengajuan);fl_js_cek_tgl_pengajuan();" onfocus="fl_js_cek_tgl_pengajuan();" style="width:222px;background-color:#ffff99;">';
						html_input += '	             <input id="btn_beasiswa_tgl_pengajuan" type="image" align="top" onclick="return showCalendar(\'beasiswa_tgl_pengajuan\', \'dd-mm-y\');" src="../../images/calendar.gif" style="height:14px;"/>';
						html_input += '	     				<input type="hidden" id="beasiswa_tgl_pengajuan_old" name="beasiswa_tgl_pengajuan_old">';      																			 								
    					html_input += '	     			</div>';
						html_input += '	           <div class="clear"></div>';
																						
						html_input += '	     		  <div class="form-row_kiri">';
						html_input += '	          <label  style = "text-align:right;">Kondisi Saat Pengajuan</label>';
						html_input += '	            <select size="1" id="beasiswa_kondisi_akhir" name="beasiswa_kondisi_akhir" tabindex="4" class="select_format" style="width:250px;background-color:#ffff99;" onChange="fl_js_set_span_tgl_kondisi_akhir();fjq_ajax_val_get_tahun_beasiswa();">';
						html_input += '	            	<option value="">-- Pilih Kondisi --</option>';
						html_input += '	            </select>';
						html_input += '	     				<input type="hidden" id="beasiswa_flag_masih_sekolah" name="beasiswa_flag_masih_sekolah">';
						html_input += '	     				<input type="hidden" id="beasiswa_flag_ditunda" name="beasiswa_flag_ditunda">';
						html_input += '	     				<input type="hidden" id="beasiswa_flag_dihentikan" name="beasiswa_flag_dihentikan">';
						html_input += '	     				<input type="hidden" id="beasiswa_flag_diterima" name="beasiswa_flag_diterima">'; 																		 								
						html_input += '	          </div>';
						html_input += '	          <div class="clear"></div>';
									
    					html_input += '	     			<span id="span_beasiswa_tgl_kondisi_akhir" style="display:none">';
      					html_input += '	     				<div class="form-row_kiri">';
            			html_input += '	             <label  style = "text-align:right;">Sejak *</label>';
        				html_input += '	     					<input type="text" id="beasiswa_tgl_kondisi_akhir" name="beasiswa_tgl_kondisi_akhir" maxlength="10" tabindex="5" onblur="convert_date(beasiswa_tgl_kondisi_akhir);fl_js_cek_tgl_kondisi_akhir_penerima();" onfocus="fl_js_cek_tgl_kondisi_akhir_penerima();" style="width:222px;background-color:#ffff99;">';
						html_input += '	               <input id="btn_beasiswa_tgl_kondisi_akhir" type="image" align="top" onclick="return showCalendar(\'beasiswa_tgl_kondisi_akhir\', \'dd-mm-y\');" src="../../images/calendar.gif"  style="height:14px;"/>';
						html_input += '	     					<input type="hidden" id="beasiswa_tgl_kondisi_akhir_old" name="beasiswa_tgl_kondisi_akhir_old">';
						html_input += '	     				</div>';																		
            			html_input += '	            <div class="clear"></div>';
										
						html_input += '	     				<span id="span_beasiswa_dokumen_upload" style="display:none">';
						html_input += '	     					<div class="form-row_kiri">';
						html_input += '	               <label  style = "text-align:right;">Dokumen *</label>';
						html_input += '	     						<input type="text" id="nama_dokumen" name="nama_dokumen" maxlength="100" tabindex="6" style="width:244px;background-color:#ffff99;" onkeyup="this.value = this.value.toUpperCase();" onblur="fl_js_set_perubahan_nama_dokumen();">';
						html_input += '	     						<input type="hidden" id="nama_dokumen_old" name="nama_dokumen_old">';
						html_input += '	     						<a href="javascript:void(0)" onclick="fl_js_UploadDok()"><img src="../../images/downloadx.png" border="0" alt="Tambah" align="absmiddle" style="height:22px;"> upload</a>';
  						html_input += '	     						<input type="hidden" id="nama_file_dokumen" name="nama_file_dokumen">';
						html_input += '	     						<input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen">';
						html_input += '	     						<input type="hidden" id="url_dokumen" name="url_dokumen">';
						html_input += '	     						<span id="span_download_file"></span>';
						html_input += '	     					</div>';																		
            			html_input += '	              <div class="clear"></div>';
      					html_input += '	     				</span>';										
    					html_input += '	     			</span>';
    							
            			html_input += '	           <div class="form-row_kiri">';
            			html_input += '	           <label style = "text-align:right;">Catatan &nbsp;</label>';
            			html_input += '	           	<textarea cols="255" rows="1" style="width:230px;height:15px;background-color:#ffff99;" id="keterangan" name="keterangan" tabindex="7" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onkeyup="this.value = this.value.toUpperCase();"></textarea>';   							
						html_input += '	     			</div>';								
            			html_input += '	           <div class="clear"></div>';								
						html_input += '	     		</fieldset>';	 
						html_input += '	     	</div>';
							
						html_input += '	     	<div class="div-row">';
						html_input += '	     		<fieldset><legend>Penetapan Manfaat Beasiswa</legend>';
						html_input += '	           <span id="span_tidakada_tahun_beasiswa" style="display:none;">';
						html_input += '	             <div class="div-row">';
						html_input += '	               <div class="div-col" style="width:100%;text-align:center;">';
						html_input += '	               	<b><span style="vertical-align: middle;" id="span_info_tidakada_tahun_beasiswa"></span></b>';
						html_input += '	               </div>';
						html_input += '	             </div>';								
						html_input += '	           </span>';
									
						html_input += '	     			<span id="span_ada_tahun_beasiswa" style="display:block;">';
						html_input += '	             <table id="tblThnBeasiswa" width="100%" class="table-data2">';
						html_input += '	               <thead>';
						html_input += '	                 <tr class="hr-double-bottom">';
						html_input += '	     							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:50px;">Action</th>';			 
						html_input += '	                   <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:50px;">Tahun</th>';
						html_input += '	                   <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Pendidikan/Pelatihan yang Ditempuh</th>';
						html_input += '	     							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:100px;">Manfaat</th>';	
						html_input += '	                 </tr>';	 
						html_input += '	               </thead>';
						html_input += '	               <tbody id="data_list_ThnBeasiswa1">';														
						html_input += '	                 <tr class="nohover-color">';
						html_input += '	                 </tr>';						             																
						html_input += '	               </tbody>';	
						html_input += '	               <tbody id="data_list_ThnBeasiswa">';										
						html_input += '	                 <tr class="nohover-color">';
						html_input += '	                 	<td colspan="4" style="text-align: center;">-- belum ada data --</td>';
						html_input += '	                 </tr>';							             																
						html_input += '	               </tbody>';
						html_input += '	               <tfoot>';
						html_input += '	                 <tr>';
						html_input += '	                   <td style="text-align:right;" colspan="3">';
						html_input += '	     								<i>Total :<i>';
						html_input += '	     								<input type="hidden" id="tahun_awal" name="tahun_awal">';
						html_input += '	     								<input type="hidden" id="tahun_akhir" name="tahun_akhir">';
						html_input += '	     								<input type="hidden" id="flag_layak" name="flag_layak">';
						html_input += '	     								<input type="hidden" id="dtl_kounter_dtl" name="dtl_kounter_dtl">';
						html_input += '	     								<input type="hidden" id="dtl_kounter_dtl_dtlLalu" name="dtl_kounter_dtl_dtlLalu">';
						html_input += '	                    <input type="hidden" id="dtl_count_dtl" name="dtl_count_dtl">';
						html_input += '	                    <input type="hidden" name="dtl_showmessage" style="border-width: 0;text-align:right" readonly>';
						html_input += '	     							 </td>';	  		
            			html_input += '	                   <td style="text-align:right;">';
						html_input += '	     								<input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" readonly class="disabled" style="text-align:right; width:120px; border-width: 1;">';
						html_input += '	     							</td>';										        
						html_input += '	                 </tr>';								
						html_input += '	               </tfoot>';
						html_input += '	             </table>';												
						html_input += '	     			</span>';							
						html_input += '	 	 	 		</fieldset>';
						html_input += '	 	 		</div>';						
						html_input += '	 </div>';
						
						html_input += '</div>';
						
						if (html_input !="")
						{
							$('#formRinci').html(html_input); 
						}
						
									//set list tipe penerima -------------------------------------
						/*
									if (jdata.ListTipePenerima)
						{
								var v_lengthTp = jdata.ListTipePenerima.length;
										for($i=0;$i<(jdata.ListTipePenerima.length);$i++)
						{
											//tampilkan semua pilihan ----------------------------
											if (v_lengthTp == 1)
											{
												$("#kode_tipe_penerima").append('<option value="'+jdata.ListTipePenerima[$i]['KODE_TIPE_PENERIMA']+'" selected="selected">'+jdata.ListTipePenerima[$i]['NAMA_TIPE_PENERIMA']+'</option>');
											}else
											{
												$("#kode_tipe_penerima").append('<option value="'+jdata.ListTipePenerima[$i]['KODE_TIPE_PENERIMA']+'">'+jdata.ListTipePenerima[$i]['NAMA_TIPE_PENERIMA']+'</option>');	 
											}
										}
						}
									*/					
									//end set list tipe penerima ---------------------------------
						
						//set initial value tgl pengajuan ----------------------------
						var v_today = new Date();
						var v_dd = v_today.getDate();
						var v_mm = v_today.getMonth() + 1; //January is 0!
						
						var v_yyyy = v_today.getFullYear();
						if (v_dd < 10) {
							v_dd = '0' + v_dd;
						} 
						if (v_mm < 10) {
							v_mm = '0' + v_mm;
						} 
						var v_today = v_dd + '/' + v_mm + '/' + v_yyyy;
						$('#beasiswa_tgl_pengajuan').val(v_today);
						$('#beasiswa_tgl_pengajuan_old').val(v_today);
						//end set initial value tgl pengajuan ------------------------
						
						//set list kondisi akhir -------------------------------------
						for($i=0;$i<(jdata.ListKondak.length);$i++)
            			{
  					 	//tampilkan selain : ----------------------------------
							//- ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN (KA16)
							//- BELUM MENEMPUH PENDIDIKAN (KA17)
							//- SUDAH MENCAPAI USIA 23 TAHUN (KA13)
							
							if (jdata.ListKondak[$i]['KODE'] !="KA16" && jdata.ListKondak[$i]['KODE'] !="KA17" && jdata.ListKondak[$i]['KODE'] !="KA13")
							{
  						 		$("#beasiswa_kondisi_akhir").append('<option value="'+jdata.ListKondak[$i]['KODE']+'">'+jdata.ListKondak[$i]['KETERANGAN']+'</option>');
             				}
						}
						//end set list kondisi akhir ---------------------------------
							
						//end set form entry rincian manfaat -------------------------------					 
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
	
	function fl_js_Edit()
	{
	 	var fn;
		var v_kode_klaim = $('#kode_klaim').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		var v_no_urut = $('#no_urut').val();
		
		if (v_kode_klaim == '' || v_kode_manfaat == '' || v_no_urut == '') {
			return alert('Kode Klaim atau Kode Manfaat atau No Urut Manfaat tidak boleh kosong');
		}
		
		v_task = $('#task').val();
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatarincianmanfaat',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat,
				v_no_urut:v_no_urut
			},
			success: function(data)
			{
				try {
						jdata = JSON.parse(data);
						if (jdata.ret == 0) 
						{
							if (v_task == 'edit')
							{
								$("#span_page_title").html('PN5002 - EDIT RINCIAN MANFAAT - BEASISWA');
							}else
							{
								$("#span_page_title").html('PN5002 - VIEW RINCIAN MANFAAT - BEASISWA');	 
							}

							var v_jenis_klaim = getValue(jdata.InfoKlaim.JENIS_KLAIM);
							var v_tgl_kejadian = getValue(jdata.InfoKlaim.TGL_KEJADIAN);
							var v_nama_kondisi_terakhir = getValue(jdata.InfoKlaim.NAMA_KONDISI_TERAKHIR);
							var v_tgl_kondisi_terakhir = getValue(jdata.InfoKlaim.TGL_KONDISI_TERAKHIR);
							var v_tgl_kejadian = getValue(jdata.InfoKlaim.TGL_KEJADIAN);
							var v_tgl_kejadian_yyymmdd = v_tgl_kejadian.substring(6, 10)+v_tgl_kejadian.substring(3, 5)+v_tgl_kejadian.substring(0, 2);
							var v_tgl_kondisi_terakhir_yyymmdd = v_tgl_kondisi_terakhir.substring(6, 10)+v_tgl_kondisi_terakhir.substring(3, 5)+v_tgl_kondisi_terakhir.substring(0, 2);
							
							$('#tgl_kejadian').val(v_tgl_kejadian);
							$('#tgl_kondisi_terakhir').val(v_tgl_kondisi_terakhir);
							$('#tgl_kejadian_yyymmdd').val(v_tgl_kejadian_yyymmdd);
							$('#tgl_kondisi_terakhir_yyymmdd').val(v_tgl_kondisi_terakhir_yyymmdd);
							$('#jenis_klaim').val(v_jenis_klaim);
							$('#kode_segmen').val(getValue(jdata.InfoKlaim.KODE_SEGMEN));
																			
							var v_title_right = '';
							
							if (v_jenis_klaim == 'JKK') 
							{
								v_title_right = 'KODE KLAIM : '+v_kode_klaim+' ('+v_no_urut+') (KONDISI AKHIR '+v_jenis_klaim+' '+v_nama_kondisi_terakhir+' PER '+v_tgl_kondisi_terakhir+')';
							}else
							{
								v_title_right = 'KODE KLAIM : '+v_kode_klaim+' ('+v_no_urut+') (TGL KEJADIAN '+v_jenis_klaim+' '+v_tgl_kejadian+')';
							}
							$("#span_page_title_right").html(v_title_right);

							$('#nik_tk').val(getValue(jdata.InfoKlaim.NOMOR_IDENTITAS));
							$('#nama_tk').val(getValue(jdata.InfoKlaim.NAMA_TK));
													
							//set form entry rincian manfaat -----------------------------------
							//get data rincian manfaat ----------------------------------
							var v_kode_tipe_penerima = "";
							var v_beasiswa_nik_penerima = "";
							var v_nama_penerima = "";
							var v_beasiswa_tgl_pengajuan = "";
							var v_beasiswa_kondisi_akhir = "";
							var v_beasiswa_flag_masih_sekolah = "";
							var v_beasiswa_flag_ditunda = "";
							var v_beasiswa_flag_dihentikan = "";
							var v_beasiswa_flag_diterima = "";
							var v_beasiswa_tgl_kondisi_akhir = "";
							var v_keterangan = "";
							var v_nom_biaya_disetujui = 0;
							
							if (jdata.ViewMnfDtl.DATA)
							{
								v_kode_tipe_penerima 		 			 = getValue(jdata.ViewMnfDtl.DATA.KODE_TIPE_PENERIMA); 
								v_beasiswa_nik_penerima  			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_NIK_PENERIMA); 
								v_nama_penerima  				 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_PENERIMA);
								v_beasiswa_tgl_pengajuan 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_TGL_PENGAJUAN);
								v_beasiswa_kondisi_akhir 	 		 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_KONDISI_AKHIR);
								v_beasiswa_flag_masih_sekolah  = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_MASIH_SEKOLAH);
								v_beasiswa_flag_ditunda 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_DITUNDA);
								v_beasiswa_flag_dihentikan 		 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_DIHENTIKAN);
								v_beasiswa_flag_diterima 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_DITERIMA);
								v_beasiswa_tgl_kondisi_akhir 	 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_TGL_KONDISI_AKHIR);
								v_keterangan 									 = getValue(jdata.ViewMnfDtl.DATA.KETERANGAN);
								v_nom_biaya_disetujui				 	 = getValueNumber(jdata.ViewMnfDtl.DATA.NOM_BIAYA_DISETUJUI);					
							}
							//end get data rincian manfaat ------------------------------
							
							var html_input  = '';
							html_input += '<div class="div-row">';
							html_input += '	 <div class="div-col" style="width:39%; max-height: 100%;">';
							html_input += '	   <div class="div-row">';
							html_input += '	     <fieldset><legend>Informasi Penerima Manfaat Beasiswa</legend>';
							html_input += '	     			<div class="div-row">';
							html_input += '	     				<div class="div-col">';
							html_input += '	     					</br>';
													
							html_input += '	               <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">NIK Anak *</label>';
							html_input += '	                 <input type="text" id="beasiswa_nik_penerima" name="beasiswa_nik_penerima" value="'+v_beasiswa_nik_penerima+'" tabindex="1" style="width:220px;background-color:#ffff99;" readonly>';				
							html_input += '	               	 <a href="#" onclick="fl_js_get_lov_nikpenerimabeasiswa();">';
							html_input += '	     						 <img src="../../images/help.png" alt="Cari NIK Penerima Beasiswa" border="0" align="absmiddle"></a>';
							html_input += '	     					 </div>';																		
							html_input += '	               <div class="clear"></div>';
																			
							html_input += '	     					 <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">Tipe Penerima *</label>';
							html_input += '	                 <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" tabindex="2" class="select_format" style="width:227px;background-color:#ffff99;">';
							html_input += '	                 	<option value="">-- Pilih Tipe Penerima --</option>';
							html_input += '	     						</select>';
							html_input += '	               </div>';
							html_input += '	               <div class="clear"></div>';
															
							html_input += '	               <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">Nama Anak &nbsp;&nbsp;</label>';
							html_input += '	               	<input type="text" id="nama_penerima" name="nama_penerima" value="'+v_nama_penerima+'" style="width:220px;" maxlength="100" readonly class="disabled">';
							html_input += '	               </div>';																																																																																													
							html_input += '	               <div class="clear"></div>';
				
							html_input += '	               <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">Tempat & Tgl Lahir</label>';
							html_input += '	                 <input type="text" id="tempat_lahir" name="tempat_lahir" style="width:146px;" maxlength="50" readonly class="disabled">';
							html_input += '	     						<input type="text" id="tgl_lahir" name="tgl_lahir" style="width:65px;" maxlength="10" readonly class="disabled">';
							html_input += '	           		</div>';																																																																																													
							html_input += '	               <div class="clear"></div>';
				
							html_input += '	           		 <div class="form-row_kiri">';
							html_input += '	           		 <label style = "text-align:right;">Jeis Kelamin</label>';
							html_input += '	             	 	 <input type="text" id="l_jenis_kelamin" name="l_jenis_kelamin" style="width:220px;" readonly class="disabled">';
							html_input += '	             	 	 <input type="hidden" id="jenis_kelamin" name="jenis_kelamin">';
							html_input += '	           		 </div>';																																																																																														
							html_input += '	           		 <div class="clear"></div>';
											
							html_input += '	     					 </br>';
												
							html_input += '	     		    	 <div class="form-row_kiri">';
							html_input += '	     		    	 <label style = "text-align:right;">Alamat Rumah &nbsp;</label>';
							html_input += '	     		    	 	 <textarea cols="255" rows="1" id="alamat" name="alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" style="width:220px;height:40px;background-color:#f5f5f5"></textarea>';		
							html_input += '	     		    	 </div>';			
							html_input += '	     		    	 <div class="clear"></div>';
				
							html_input += '	     		   		 <div class="form-row_kiri">';
							html_input += '	     		   		 <label style = "text-align:right;">Email &nbsp;&nbsp;</label>';
							html_input += '	     		     	 	 <input type="text" id="email" name="email" style="width:220px;" readonly class="disabled">';
							html_input += '	     			 		 </div>';																																					
							html_input += '	     		   		 <div class="clear"></div>';
						
							html_input += '	     	   		 	 <div class="form-row_kiri">';
							html_input += '	     		   		 <label style = "text-align:right;">No. HP &nbsp;&nbsp;</label>';
							html_input += '	     		     	 	 <input type="text" id="handphone" name="handphone" style="width:220px;" readonly class="disabled">';
							html_input += '	     			 		 </div>';																																				
							html_input += '	     		   		 <div class="clear"></div>';
													
							html_input += '	     		   		 <div class="form-row_kiri">';
							html_input += '	     		   		 <label style = "text-align:right;">Nama Ortu/Wali &nbsp;&nbsp;</label>';
							html_input += '	     		     	 	 <input type="text" id="nama_ortu_wali" name="nama_ortu_wali" style="width:200px;" readonly class="disabled">';
							html_input += '	     			 		 </div>';																																						
							html_input += '	     		   		 <div class="clear"></div>';
												
							html_input += '	     					 </br>';																																		 
							html_input += '	     				</div>';	 
							html_input += '	     			</div>';						
							html_input += '	 	 	 </fieldset>';
							html_input += '	 	 </div>';
							html_input += '	 </div>';
							
							html_input += '	 <div class="div-col" style="width:1%;">';
							html_input += '	 </div>';
							
							html_input += '	 <div class="div-col-right" style="width:60%;">';
							html_input += '	     	<div class="div-row">';
							html_input += '	     		<fieldset><legend>Kondisi Akhir Anak Saat Pengajuan Beasiswa</legend>';
							html_input += '	     			</br>';
							html_input += '	     			<div class="form-row_kiri">';
							html_input += '	           <label  style = "text-align:right;">Tgl Pengajuan *</label>';
							html_input += '	             <input type="text" id="beasiswa_tgl_pengajuan" name="beasiswa_tgl_pengajuan" value="'+v_beasiswa_tgl_pengajuan+'" maxlength="10" tabindex="3" onblur="convert_date(beasiswa_tgl_pengajuan);fl_js_cek_tgl_pengajuan();" onfocus="fl_js_cek_tgl_pengajuan();" style="width:222px;background-color:#ffff99;">';
							html_input += '	             <input id="btn_beasiswa_tgl_pengajuan" type="image" align="top" onclick="return showCalendar(\'beasiswa_tgl_pengajuan\', \'dd-mm-y\');" src="../../images/calendar.gif" style="height:14px;"/>';
							html_input += '	     				<input type="hidden" id="beasiswa_tgl_pengajuan_old" name="beasiswa_tgl_pengajuan_old" value="'+v_beasiswa_tgl_pengajuan+'">';      																			 								
							html_input += '	     			</div>';
							html_input += '	           <div class="clear"></div>';
																				
							html_input += '	     		  <div class="form-row_kiri">';
							html_input += '	          <label  style = "text-align:right;">Kondisi Saat Pengajuan</label>';
							html_input += '	            <select size="1" id="beasiswa_kondisi_akhir" name="beasiswa_kondisi_akhir" tabindex="4" class="select_format" style="width:250px;background-color:#ffff99;" onChange="fl_js_set_span_tgl_kondisi_akhir();fjq_ajax_val_get_tahun_beasiswa();">';
							html_input += '	            	<option value="">-- Pilih Kondisi --</option>';
							html_input += '	            </select>';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_masih_sekolah" name="beasiswa_flag_masih_sekolah" value="'+v_beasiswa_flag_masih_sekolah+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_ditunda" name="beasiswa_flag_ditunda" value="'+v_beasiswa_flag_ditunda+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_dihentikan" name="beasiswa_flag_dihentikan" value="'+v_beasiswa_flag_dihentikan+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_diterima" name="beasiswa_flag_diterima" value="'+v_beasiswa_flag_diterima+'">'; 																		 								
							html_input += '	          </div>';
							html_input += '	          <div class="clear"></div>';
										
							html_input += '	     			<span id="span_beasiswa_tgl_kondisi_akhir" style="display:none">';
							html_input += '	     				<div class="form-row_kiri">';
							html_input += '	             <label  style = "text-align:right;">Sejak *</label>';
							html_input += '	     					<input type="text" id="beasiswa_tgl_kondisi_akhir" name="beasiswa_tgl_kondisi_akhir" value="'+v_beasiswa_tgl_kondisi_akhir+'" maxlength="10" tabindex="5" onblur="convert_date(beasiswa_tgl_kondisi_akhir);fl_js_cek_tgl_kondisi_akhir_penerima();" onfocus="fl_js_cek_tgl_kondisi_akhir_penerima();" style="width:222px;background-color:#ffff99;">';
							html_input += '	               <input id="btn_beasiswa_tgl_kondisi_akhir" type="image" align="top" onclick="return showCalendar(\'beasiswa_tgl_kondisi_akhir\', \'dd-mm-y\');" src="../../images/calendar.gif"  style="height:14px;"/>';
							html_input += '	     					<input type="hidden" id="beasiswa_tgl_kondisi_akhir_old" name="beasiswa_tgl_kondisi_akhir_old" value="'+v_beasiswa_tgl_kondisi_akhir+'">';
							html_input += '	     				</div>';																		
							html_input += '	            <div class="clear"></div>';
											
							html_input += '	     				<span id="span_beasiswa_dokumen_upload" style="display:none">';
							html_input += '	     					<div class="form-row_kiri">';
							html_input += '	               <label  style = "text-align:right;">Dokumen *</label>';
							html_input += '	     						<input type="text" id="nama_dokumen" name="nama_dokumen" maxlength="100" tabindex="6" style="width:222px;background-color:#ffff99;" onkeyup="this.value = this.value.toUpperCase();" onblur="fl_js_set_perubahan_nama_dokumen();">';
							html_input += '	     						<input type="hidden" id="nama_dokumen_old" name="nama_dokumen_old">';
							html_input += '	     						<a href="javascript:void(0)" onclick="fl_js_UploadDok()"><img src="../../images/downloadx.png" border="0" alt="Tambah" align="absmiddle" style="height:22px;"> Upload</a>';
							html_input += '	     						<input type="hidden" id="nama_file_dokumen" name="nama_file_dokumen">';
							html_input += '	     						<input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen">';
							html_input += '	     						<input type="hidden" id="url_dokumen" name="url_dokumen">';
							html_input += '	     						<span id="span_download_file"></span>';
							html_input += '	     					</div>';																		
							html_input += '	              <div class="clear"></div>';
							html_input += '	     				</span>';										
							html_input += '	     			</span>';
									
							html_input += '	           <div class="form-row_kiri">';
							html_input += '	           <label style = "text-align:right;">Catatan &nbsp;</label>';
							html_input += '	           	<textarea cols="255" rows="1" style="width:230px;height:15px;background-color:#ffff99;" id="keterangan" name="keterangan" tabindex="7" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onkeyup="this.value = this.value.toUpperCase();">'+v_keterangan+'</textarea>';   							
							html_input += '	     			</div>';								
							html_input += '	           <div class="clear"></div>';								
							html_input += '	     		</fieldset>';	 
							html_input += '	     	</div>';
								
							html_input += '	     	<div class="div-row">';
							html_input += '	     		<fieldset><legend>Penetapan Manfaat Beasiswa</legend>';
							html_input += '	           <span id="span_tidakada_tahun_beasiswa" style="display:none;">';
							html_input += '	             <div class="div-row">';
							html_input += '	               <div class="div-col" style="width:100%;text-align:center;">';
							html_input += '	               	<b><span style="vertical-align: middle;" id="span_info_tidakada_tahun_beasiswa"></span></b>';
							html_input += '	               </div>';
							html_input += '	             </div>';								
							html_input += '	           </span>';
										
							html_input += '	     			<span id="span_ada_tahun_beasiswa" style="display:block;">';
							html_input += '	             <table id="tblThnBeasiswa" width="100%" class="table-data2">';
							html_input += '	               <thead>';
							html_input += '	                 <tr class="hr-double-bottom">';
							html_input += '	     							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:50px;">Action</th>';			 
							html_input += '	                   <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:50px;">Tahun</th>';
							html_input += '	                   <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Pendidikan/Pelatihan yang Ditempuh</th>';
							html_input += '	     							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:100px;">Manfaat</th>';	
							html_input += '	                 </tr>';	 
							html_input += '	               </thead>';
							html_input += '	               <tbody id="data_list_ThnBeasiswa">';												
							html_input += '	                 <tr class="nohover-color">';
							html_input += '	                 	<td colspan="4" style="text-align: center;">-- belum ada data --</td>';
							html_input += '	                 </tr>';							             																
							html_input += '	               </tbody>';
							html_input += '	               <tfoot>';
							html_input += '	                 <tr>';
							html_input += '	                   <td style="text-align:right;" colspan="3">';
							html_input += '	     								<i>Total ::<i>';
							html_input += '	     								<input type="hidden" id="tahun_awal" name="tahun_awal">';
							html_input += '	     								<input type="hidden" id="tahun_akhir" name="tahun_akhir">';
							html_input += '	     								<input type="hidden" id="flag_layak" name="flag_layak">';
							html_input += '	     								<input type="hidden" id="dtl_kounter_dtl" name="dtl_kounter_dtl">';
							html_input += '	                    <input type="hidden" id="dtl_count_dtl" name="dtl_count_dtl">';
							html_input += '	                    <input type="hidden" name="dtl_showmessage" style="border-width: 0;text-align:right" readonly>';
							html_input += '	     							 </td>';	  		
							html_input += '	                   <td style="text-align:right;">';
							html_input += '	     								<input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value='+format_uang(v_nom_biaya_disetujui)+' readonly class="disabled" style="text-align:right; width:120px; border-width: 1;">';
							html_input += '	     							</td>';										        
							html_input += '	                 </tr>';								
							html_input += '	               </tfoot>';
							html_input += '	             </table>';												
							html_input += '	     			</span>';							
							html_input += '	 	 	 		</fieldset>';
							html_input += '	 	 		</div>';						
							html_input += '	 </div>';
							
							html_input += '</div>';
							
							if (html_input !="")
							{
								$('#formRinci').html(html_input); 
							}
							//end set form entry rincian manfaat -------------------------------						
							
							//set list tipe penerima -------------------------------------
							if (jdata.ListTipePenerima)
							{
											for($i=0;$i<(jdata.ListTipePenerima.length);$i++)
							{
												//tampilkan semua pilihan ----------------------------
												if (jdata.ListTipePenerima[$i]['KODE_TIPE_PENERIMA'] == v_kode_tipe_penerima)
												{
													$("#kode_tipe_penerima").append('<option value="'+jdata.ListTipePenerima[$i]['KODE_TIPE_PENERIMA']+'" selected="selected">'+jdata.ListTipePenerima[$i]['NAMA_TIPE_PENERIMA']+'</option>');
												}else
												{
													$("#kode_tipe_penerima").append('<option value="'+jdata.ListTipePenerima[$i]['KODE_TIPE_PENERIMA']+'">'+jdata.ListTipePenerima[$i]['NAMA_TIPE_PENERIMA']+'</option>');	 
												}
											}
							}					
							//end set list tipe penerima ---------------------------------
							
							//set list kondisi akhir -------------------------------------
							if (jdata.ListKondak)
							{
								for($i=0;$i<(jdata.ListKondak.length);$i++)
								{
									//jika v_beasiswa_kondisi_akhir = 'KA13' maka tampilkan hanya KA13
									//KA13 SUDAH MENCAPAI USIA 23 TAHUN ----------------------------
									if (v_beasiswa_kondisi_akhir == 'KA13') 
									{
										//tampilkan hanya KA13 -------------------------------------
										if (jdata.ListKondak[$i]['KODE'] == v_beasiswa_kondisi_akhir)
										{ 
											$("#beasiswa_kondisi_akhir").append('<option value="'+jdata.ListKondak[$i]['KODE']+'" selected="selected">'+jdata.ListKondak[$i]['KETERANGAN']+'</option>'); 
										}
									}else
									{
										//jika v_beasiswa_kondisi_akhir bukan 'KA13' maka tampilkan selain :
										//- ANAK USIA MAKSIMAL 300 HARI DALAM KANDUNGAN (KA16)
										//- BELUM MENEMPUH PENDIDIKAN (KA17)
										//- SUDAH MENCAPAI USIA 23 TAHUN (KA13)		
										
										if (jdata.ListKondak[$i]['KODE'] !="KA16" && jdata.ListKondak[$i]['KODE'] !="KA17" && jdata.ListKondak[$i]['KODE'] !="KA13")
										{
											if (jdata.ListKondak[$i]['KODE'] == v_beasiswa_kondisi_akhir)
											{
												$("#beasiswa_kondisi_akhir").append('<option value="'+jdata.ListKondak[$i]['KODE']+'" selected="selected">'+jdata.ListKondak[$i]['KETERANGAN']+'</option>');
											}else
											{
												$("#beasiswa_kondisi_akhir").append('<option value="'+jdata.ListKondak[$i]['KODE']+'">'+jdata.ListKondak[$i]['KETERANGAN']+'</option>');	 
											}
										}
									}
								}
							}
							
							fl_js_set_span_tgl_kondisi_akhir();
							//end set list kondisi akhir ---------------------------------
							
							//set dokumen kelengkapan administrasi -----------------------
							var v_nama_dokumen 			= "";
							var v_nama_file_dokumen = "";
							var v_no_urut_dokumen 	= "";
							var v_url_dokumen 			= "";
									
							if (getValue(jdata.ViewMnfDtl.DetilDok))
							{
								for(var i = 0; i < jdata.ViewMnfDtl.DetilDok.length; i++)
								{
									//ambil yg terakhir dg keterangan PENETAPAN_BEASISWA_PERUBAHANKONDISIAKHIR --------
									if (jdata.ViewMnfDtl.DetilDok[i].KETERANGAN == "PENETAPAN_BEASISWA_PERUBAHANKONDISIAKHIR")
									{
										v_nama_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[i].NAMA_DOKUMEN);
										v_nama_file_dokumen = getValue(jdata.ViewMnfDtl.DetilDok[i].NAMA_FILE);
										v_no_urut_dokumen 	= getValue(jdata.ViewMnfDtl.DetilDok[i].NO_URUT_DOKUMEN);
										v_url_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[i].URL);			
									}
								}
								
								$('#nama_dokumen').val(v_nama_dokumen);
								$('#nama_dokumen_old').val(v_nama_dokumen);
								$('#nama_file_dokumen').val(v_nama_file_dokumen);
								$('#no_urut_dokumen').val(v_no_urut_dokumen);
								$('#url_dokumen').val(v_url_dokumen);

								if (v_nama_file_dokumen != "" && v_url_dokumen != "") {
								var html_download_file = '<a href="javascript:void(0)" onclick="fl_js_DownloadDok()"> | File: '+v_nama_file_dokumen+'</a>';
									
								$("#span_download_file").html(html_download_file);
								}													
							}						
							//end set dokumen kelengkapan administrasi -------------------
							
							//set grid tahun beasiswa ------------------------------------
							var html_listthn = "";
							var iteration = 0;
							var v_dtl_tahun = "";
							var v_dtl_jenis = "";
							var v_dtl_jenjang = "";
							var v_dtl_tingkat = "";
							var v_dtl_lembaga = "";
							var v_dtl_keterangan = "";
							var v_dtl_flag_terima = "";
							var v_dtl_nom_manfaat = 0;
							var v_dtl_uraian = "";
							var v_dtl_flag_dok_lengkap = "";
																
							if (getValue(jdata.ViewMnfDtl.DetilBeasiswa))
							{
								for(var i = 0; i < jdata.ViewMnfDtl.DetilBeasiswa.length; i++)
								{
									iteration = i;
										
									v_dtl_tahun		 	  = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TAHUN);							
									v_dtl_jenis 			= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENIS);
									v_dtl_jenjang 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENJANG);
									v_dtl_tingkat 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TINGKAT);
									v_dtl_lembaga 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].LEMBAGA);
									v_dtl_keterangan 	= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].KETERANGAN);
									v_dtl_flag_terima = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_TERIMA);
									v_dtl_nom_manfaat = getValueNumber(jdata.ViewMnfDtl.DetilBeasiswa[i].NOM_MANFAAT);
									v_dtl_flag_terima = v_dtl_flag_terima == '' ? 'T' : v_dtl_flag_terima;
									v_dtl_flag_dok_lengkap = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_DOK_LENGKAP);
											
									html_listthn += '<tr>';
									html_listthn += '<td style="text-align: center;">' 
													+ '<a href="javascript:void(0)" onclick="fl_js_entry_detilbeasiswa(\''
																	+ iteration + '\', \'' 
													+ v_dtl_tahun + '\')">';
									html_listthn += '&nbsp;<img src="../../images/app_form_edit.png" border="0" align="absmiddle">&nbsp;Entri</a></td>';
									html_listthn += '<td style="text-align: center;"><input type="text" id='+'dtl_tahun'+iteration+' name='+'dtl_tahun'+iteration+' maxlength="4" readonly class=disabled style="text-align:center; width:50px;border-width: 1;"></td>';
									html_listthn += '<td style="text-align: left;">';
									html_listthn += '	 <input type="text" id='+'dtl_uraian'+iteration+' name='+'dtl_uraian'+iteration+' readonly class=disabled style="text-align:left; width:400px; border-width: 1;">';
									html_listthn += '	 <input type="hidden" id='+'dtl_jenis'+iteration+' name='+'dtl_jenis'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_jenjang'+iteration+' name='+'dtl_jenjang'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_tingkat'+iteration+' name='+'dtl_tingkat'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_lembaga'+iteration+' name='+'dtl_lembaga'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_keterangan'+iteration+' name='+'dtl_keterangan'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_flag_terima'+iteration+' name='+'dtl_flag_terima'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_nama_dokumen'+iteration+' name='+'dtl_nama_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_nama_file_dokumen'+iteration+' name='+'dtl_nama_file_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_no_urut_dokumen'+iteration+' name='+'dtl_no_urut_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_url_dokumen'+iteration+' name='+'dtl_url_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_flag_dok_lengkap'+iteration+' name='+'dtl_flag_dok_lengkap'+iteration+'>';
									html_listthn += '</td>';
									html_listthn += '<td style="text-align: left;"><input type="text" id='+'dtl_nom_manfaat'+iteration+' name='+'dtl_nom_manfaat'+iteration+' readonly class=disabled style="text-align:right; width:120px; border-width: 1;"></td>';												
									html_listthn += '</tr>';
								}
								
								if (html_listthn == "") {
									html_listthn += '<tr class="nohover-color">';
									html_listthn += '<td colspan="4" style="text-align: center;">-- tidak ada tahun penerimaan beasiswa --</td>';
									html_listthn += '</tr>';
								}
								$("#data_list_ThnBeasiswa").html(html_listthn);	
								
								iteration++;						
								$("#dtl_kounter_dtl").val(iteration); 							

								//set value utk setiap kolom --------------------------------
								iteration = 0;
								v_dtl_tahun = "";
								v_dtl_jenis = "";
								v_dtl_jenjang = "";
								v_dtl_tingkat = "";
								v_dtl_lembaga = "";
								v_dtl_keterangan = "";
								v_dtl_flag_terima = "";
								v_dtl_nom_manfaat = 0;
								v_dtl_uraian = "";
								v_dtl_flag_dok_lengkap = "";
								
								var v_tot_d_nom_biaya_disetujui = 0;
								var v_dtl_ket_keterangan = "";
								
								var v_dtl_tahun_awal = "";
								var v_dtl_tahun_akhir = "";
								var v_dtl_flag_layak = "T";
															
								for(var i = 0; i < jdata.ViewMnfDtl.DetilBeasiswa.length; i++)
								{
									iteration = i;
									
									//reset value --------------------------------------------------
									v_dtl_tahun = "";
									v_dtl_jenis = "";
									v_dtl_jenjang = "";
									v_dtl_tingkat = "";
									v_dtl_lembaga = "";
									v_dtl_keterangan = "";
									v_dtl_flag_terima = "";
									v_dtl_nom_manfaat = 0;
									v_dtl_uraian = "";
									v_dtl_ket_keterangan = "";	
									v_dtl_flag_dok_lengkap = "";
									
									//set value ----------------------------------------------------							
									v_dtl_tahun 	 	 	= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TAHUN);								
									v_dtl_jenis 			= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENIS);
									v_dtl_jenjang 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENJANG);
									v_dtl_tingkat 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TINGKAT);
									v_dtl_lembaga 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].LEMBAGA);
									v_dtl_keterangan 	= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].KETERANGAN);
									v_dtl_flag_terima = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_TERIMA);
									v_dtl_nom_manfaat = parseFloat(getValueNumber(jdata.ViewMnfDtl.DetilBeasiswa[i].NOM_MANFAAT));
									v_dtl_flag_dok_lengkap = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_DOK_LENGKAP);
									
									v_dtl_nom_manfaat = v_dtl_nom_manfaat == '' ? 0 : v_dtl_nom_manfaat;
									v_dtl_flag_terima = v_dtl_flag_terima == '' ? 'T' : v_dtl_flag_terima;
									v_tot_d_nom_biaya_disetujui += parseFloat(v_dtl_nom_manfaat,4);
									
									if (v_dtl_jenis == "")
									{
										if (v_dtl_flag_terima=="T" && v_dtl_keterangan!=="")
										{
											v_dtl_ket_keterangan = "TIDAK MENERIMA MANFAAT BEASISWA ("+v_dtl_keterangan+")"; 
										}else
										{
											v_dtl_ket_keterangan = "";	 
										}
										
										v_dtl_uraian = v_dtl_ket_keterangan;  	 
									}else
									{
										if (v_dtl_jenis == "BEASISWA")
										{
											//v_jenjang -----------------------
											//TK			TK/SEDERAJAT
											//SD			SD/SEDERAJAT
											//SMP			SLTP/SEDERAJAT
											//SMA			SLTA/SEDERAJAT
											//KULIAH	PERGURUAN TINGGI (S1)
										
											var v_dtl_ket_jenjang = "";
											if (v_dtl_jenjang == "TK")
											{
												v_dtl_ket_jenjang = "TK/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "SD")
											{
												v_dtl_ket_jenjang = "SD/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "SMP")
											{
												v_dtl_ket_jenjang = "SLTP/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "SMA")
											{
												v_dtl_ket_jenjang = "SLTA/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "KULIAH")
											{
												v_dtl_ket_jenjang = "PERGURUAN TINGGI (S1)"; 
											}
											
														var v_dtl_ket_keterangan = "";
											if (v_dtl_keterangan=="")
											{
												v_dtl_ket_keterangan = ""; 
											}else
											{
												v_dtl_ket_keterangan = "("+v_dtl_keterangan+")";	 
											}			
											
											v_dtl_uraian = 'BEASISWA PENDIDIKAN UNTUK' + ' ' + v_dtl_ket_jenjang + ' TINGKAT/KELAS ' + v_dtl_tingkat + ' ' + v_dtl_ket_keterangan; 
										}else
										{
											if (v_dtl_lembaga=="")
											{
												v_dtl_ket_lembaga = ""; 
											}else
											{
												v_dtl_ket_lembaga = "LEMBAGA "+v_dtl_lembaga;	 
											}
								
														var v_dtl_ket_keterangan = "";
											if (v_dtl_keterangan=="")
											{
												v_dtl_ket_keterangan = ""; 
											}else
											{
												v_dtl_ket_keterangan = "("+v_dtl_keterangan+")";	 
											}
														
											v_dtl_uraian = 'BEASISWA UNTUK PELATIHAN' + ' ' + v_dtl_jenjang + ' TINGKAT ' + v_dtl_tingkat + ' ' + v_dtl_ket_lembaga + ' ' + v_dtl_ket_keterangan; 	 
										}
									}	
									
									if (iteration==0)
									{
										v_dtl_tahun_awal = v_dtl_tahun; 
									}
									v_dtl_tahun_akhir = v_dtl_tahun;
									v_dtl_flag_layak = 'Y';
									
									//set dokumen kelengkapan administrasi utk tiap periode tahun --
									var v_dtl_nama_dokumen 			= "";
									var v_dtl_nama_file_dokumen = "";
									var v_dtl_no_urut_dokumen 	= "";
									var v_dtl_url_dokumen 			= "";
									var v_dtl_ket_dokumen 			= "";
																		
									if (v_dtl_flag_terima == "Y")
									{	
										v_dtl_ket_dokumen = "PENETAPAN_BEASISWA_"+v_dtl_tahun;
										
										if (getValue(jdata.ViewMnfDtl.DetilDok))
										{
											for(var idok = 0; idok < jdata.ViewMnfDtl.DetilDok.length; idok++)
											{
												//ambil yg terakhir dg keterangan PENETAPAN_BEASISWA_XXXX --
												if (jdata.ViewMnfDtl.DetilDok[idok].KETERANGAN == v_dtl_ket_dokumen)
												{
													v_dtl_nama_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[idok].NAMA_DOKUMEN);
													v_dtl_nama_file_dokumen = getValue(jdata.ViewMnfDtl.DetilDok[idok].NAMA_FILE);
													v_dtl_no_urut_dokumen 	= getValue(jdata.ViewMnfDtl.DetilDok[idok].NO_URUT_DOKUMEN);
													v_dtl_url_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[idok].URL);			
												}
											}													
										}
									}						
										//end set dokumen kelengkapan administrasi utk tiap periode tahun --
							
									$('#dtl_tahun'+iteration).val(v_dtl_tahun);
									$('#dtl_uraian'+iteration).val(v_dtl_uraian);
									$('#dtl_jenis'+iteration).val(v_dtl_jenis);
									$('#dtl_jenjang'+iteration).val(v_dtl_jenjang);
									$('#dtl_flag_terima'+iteration).val(v_dtl_flag_terima);
									$('#dtl_tingkat'+iteration).val(v_dtl_tingkat);
									$('#dtl_lembaga'+iteration).val(v_dtl_lembaga);
									$('#dtl_keterangan'+iteration).val(v_dtl_keterangan);
									$('#dtl_nom_manfaat'+iteration).val(format_uang(v_dtl_nom_manfaat));
									
									$('#dtl_nama_dokumen'+iteration).val(v_dtl_nama_dokumen);
									$('#dtl_nama_file_dokumen'+iteration).val(v_dtl_nama_file_dokumen);
									$('#dtl_no_urut_dokumen'+iteration).val(v_dtl_no_urut_dokumen);
									$('#dtl_url_dokumen'+iteration).val(v_dtl_url_dokumen);
									$('#dtl_flag_dok_lengkap'+iteration).val(v_dtl_flag_dok_lengkap);									
								}						
								//end set value utk setiap kolom ----------------------------
								
								$('#tahun_awal').val(v_dtl_tahun_awal);
								$('#tahun_akhir').val(v_dtl_tahun_akhir);
								$('#flag_layak').val(v_dtl_flag_layak);
							}					
							//end set grid tahun beasiswa --------------------------------
							
							//get profile anak penerima beasiswa -------------------------
							fjq_ajax_val_getprofilebynikpenerima();
							//end get profile anak penerima beasiswa ---------------------											 
						}
					} catch (e) 
					{
						alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
					}
					asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function()
			{
				alert("Terjadi kesalahan, coba beberapa saat lagi!!");
				asyncPreload(false);
			}
		});			
	}
	
	function fl_js_View()
	{
	 	var fn;
		var v_kode_klaim = $('#kode_klaim').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		var v_no_urut = $('#no_urut').val();
		
		if (v_kode_klaim == '' || v_kode_manfaat == '' || v_no_urut == '') {
			return alert('Kode Klaim atau Kode Manfaat atau No Urut Manfaat tidak boleh kosong');
		}
		
		v_task = $('#task').val();
		window.document.getElementById("span_button_new").style.display = 'none';
		window.document.getElementById("span_button_tutup").style.display = 'none';
		window.document.getElementById("span_footer_keterangan_new").style.display = 'none';
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5061_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatarincianmanfaat',
				v_kode_klaim: v_kode_klaim,
				v_kode_manfaat:v_kode_manfaat,
				v_no_urut:v_no_urut
			},
			success: function(data){
				try {
						jdata = JSON.parse(data);
						if (jdata.ret == 0) 
						{
							$("#span_page_title").html('PN5002 - VIEW RINCIAN MANFAAT - BEASISWA');	 
							var v_jenis_klaim = getValue(jdata.InfoKlaim.JENIS_KLAIM);
							var v_tgl_kejadian = getValue(jdata.InfoKlaim.TGL_KEJADIAN);
							var v_nama_kondisi_terakhir = getValue(jdata.InfoKlaim.NAMA_KONDISI_TERAKHIR);
							var v_tgl_kondisi_terakhir = getValue(jdata.InfoKlaim.TGL_KONDISI_TERAKHIR);
							var v_tgl_kejadian = getValue(jdata.InfoKlaim.TGL_KEJADIAN);
							var v_tgl_kejadian_yyymmdd = v_tgl_kejadian.substring(6, 10)+v_tgl_kejadian.substring(3, 5)+v_tgl_kejadian.substring(0, 2);
							var v_tgl_kondisi_terakhir_yyymmdd = v_tgl_kondisi_terakhir.substring(6, 10)+v_tgl_kondisi_terakhir.substring(3, 5)+v_tgl_kondisi_terakhir.substring(0, 2);
							
							$('#tgl_kejadian').val(v_tgl_kejadian);
							$('#tgl_kondisi_terakhir').val(v_tgl_kondisi_terakhir);
							$('#tgl_kejadian_yyymmdd').val(v_tgl_kejadian_yyymmdd);
							$('#tgl_kondisi_terakhir_yyymmdd').val(v_tgl_kondisi_terakhir_yyymmdd);
							$('#jenis_klaim').val(v_jenis_klaim);
							$('#kode_segmen').val(getValue(jdata.InfoKlaim.KODE_SEGMEN));
																			
							var v_title_right = '';
							
							if (v_jenis_klaim == 'JKK') 
							{
								v_title_right = 'KODE KLAIM : '+v_kode_klaim+' ('+v_no_urut+') (KONDISI AKHIR '+v_jenis_klaim+' '+v_nama_kondisi_terakhir+' PER '+v_tgl_kondisi_terakhir+')';
							}else
							{
								v_title_right = 'KODE KLAIM : '+v_kode_klaim+' ('+v_no_urut+') (TGL KEJADIAN '+v_jenis_klaim+' '+v_tgl_kejadian+')';
							}
							$("#span_page_title_right").html(v_title_right);

							$('#nik_tk').val(getValue(jdata.InfoKlaim.NOMOR_IDENTITAS));
							$('#nama_tk').val(getValue(jdata.InfoKlaim.NAMA_TK));
													
							//set form entry rincian manfaat -----------------------------------
							//get data rincian manfaat ----------------------------------
							var v_kode_tipe_penerima = "";
							var v_nama_tipe_penerima = "";
							var v_beasiswa_nik_penerima = "";
							var v_nama_penerima = "";
							var v_beasiswa_tgl_pengajuan = "";
							var v_beasiswa_kondisi_akhir = "";
							var v_beasiswa_nama_kondisi_akhir = "";
							var v_beasiswa_flag_masih_sekolah = "";
							var v_beasiswa_flag_ditunda = "";
							var v_beasiswa_flag_dihentikan = "";
							var v_beasiswa_flag_diterima = "";
							var v_beasiswa_tgl_kondisi_akhir = "";
							var v_keterangan = "";
							var v_nom_biaya_disetujui = 0;
							
							if (jdata.ViewMnfDtl.DATA)
							{
								v_kode_tipe_penerima 		 			 = getValue(jdata.ViewMnfDtl.DATA.KODE_TIPE_PENERIMA); 
								v_nama_tipe_penerima 		 			 = getValue(jdata.ViewMnfDtl.DATA.NAMA_TIPE_PENERIMA); 
								v_beasiswa_nik_penerima  			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_NIK_PENERIMA); 
								v_nama_penerima  				 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_PENERIMA);
								v_beasiswa_tgl_pengajuan 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_TGL_PENGAJUAN);
								v_beasiswa_kondisi_akhir 	 		 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_KONDISI_AKHIR);
								v_beasiswa_nama_kondisi_akhir  = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_NAMA_KONDISI_AKHIR);
								v_beasiswa_flag_masih_sekolah  = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_MASIH_SEKOLAH);
								v_beasiswa_flag_ditunda 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_DITUNDA);
								v_beasiswa_flag_dihentikan 		 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_DIHENTIKAN);
								v_beasiswa_flag_diterima 			 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_FLAG_DITERIMA);
								v_beasiswa_tgl_kondisi_akhir 	 = getValue(jdata.ViewMnfDtl.DATA.BEASISWA_TGL_KONDISI_AKHIR);
								v_keterangan 									 = getValue(jdata.ViewMnfDtl.DATA.KETERANGAN);
								v_nom_biaya_disetujui				 	 = getValueNumber(jdata.ViewMnfDtl.DATA.NOM_BIAYA_DISETUJUI);					
							}
							//end get data rincian manfaat ------------------------------
							
							var html_input  = '';
							html_input += '<div class="div-row">';
							html_input += '	 <div class="div-col" style="width:39%; max-height: 100%;">';
							html_input += '	   <div class="div-row">';
							html_input += '	     <fieldset><legend>Informasi Penerima Manfaat Beasiswa</legend>';
							html_input += '	     			<div class="div-row">';
							html_input += '	     				<div class="div-col">';
							html_input += '	     					</br>';
													
							html_input += '	               <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">NIK Anak *</label>';
							html_input += '	                 <input type="text" id="beasiswa_nik_penerima" name="beasiswa_nik_penerima" value="'+v_beasiswa_nik_penerima+'" tabindex="1" style="width:220px;" readonly class="disabled">';
							html_input += '	     					 </div>';																		
							html_input += '	               <div class="clear"></div>';
																			
							html_input += '	     					 <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">Tipe Penerima *</label>';
							html_input += '	                 <input type="text" id="nama_tipe_penerima" name="nama_tipe_penerima" value="'+v_nama_tipe_penerima+'" tabindex="2" style="width:220px;" readonly class="disabled">';
							html_input += '	                 <input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima" value="'+v_kode_tipe_penerima+'">';
							html_input += '	               </div>';
							html_input += '	               <div class="clear"></div>';
															
							html_input += '	               <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">Nama Anak &nbsp;&nbsp;</label>';
							html_input += '	               	<input type="text" id="nama_penerima" name="nama_penerima" value="'+v_nama_penerima+'" style="width:220px;" maxlength="100" readonly class="disabled">';
							html_input += '	               </div>';																																																																																													
							html_input += '	               <div class="clear"></div>';
				
							html_input += '	               <div class="form-row_kiri">';
							html_input += '	               <label style = "text-align:right;">Tempat & Tgl Lahir</label>';
							html_input += '	                 <input type="text" id="tempat_lahir" name="tempat_lahir" style="width:146px;" maxlength="50" readonly class="disabled">';
							html_input += '	     						<input type="text" id="tgl_lahir" name="tgl_lahir" style="width:65px;" maxlength="10" readonly class="disabled">';
							html_input += '	           		</div>';																																																																																													
							html_input += '	               <div class="clear"></div>';
				
							html_input += '	           		 <div class="form-row_kiri">';
							html_input += '	           		 <label style = "text-align:right;">Jeis Kelamin</label>';
							html_input += '	             	 	 <input type="text" id="l_jenis_kelamin" name="l_jenis_kelamin" style="width:220px;" readonly class="disabled">';
							html_input += '	             	 	 <input type="hidden" id="jenis_kelamin" name="jenis_kelamin">';
							html_input += '	           		 </div>';																																																																																														
							html_input += '	           		 <div class="clear"></div>';
											
							html_input += '	     					 </br>';
												
							html_input += '	     		    	 <div class="form-row_kiri">';
							html_input += '	     		    	 <label style = "text-align:right;">Alamat Rumah &nbsp;</label>';
							html_input += '	     		    	 	 <textarea cols="255" rows="1" id="alamat" name="alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" style="width:220px;height:40px;background-color:#f5f5f5"></textarea>';		
							html_input += '	     		    	 </div>';			
							html_input += '	     		    	 <div class="clear"></div>';
				
							html_input += '	     		   		 <div class="form-row_kiri">';
							html_input += '	     		   		 <label style = "text-align:right;">Email &nbsp;&nbsp;</label>';
							html_input += '	     		     	 	 <input type="text" id="email" name="email" style="width:220px;" readonly class="disabled">';
							html_input += '	     			 		 </div>';																																					
							html_input += '	     		   		 <div class="clear"></div>';
						
							html_input += '	     	   		 	 <div class="form-row_kiri">';
							html_input += '	     		   		 <label style = "text-align:right;">No. HP &nbsp;&nbsp;</label>';
							html_input += '	     		     	 	 <input type="text" id="handphone" name="handphone" style="width:220px;" readonly class="disabled">';
							html_input += '	     			 		 </div>';																																				
							html_input += '	     		   		 <div class="clear"></div>';
													
							html_input += '	     		   		 <div class="form-row_kiri">';
							html_input += '	     		   		 <label style = "text-align:right;">Nama Ortu/Wali &nbsp;&nbsp;</label>';
							html_input += '	     		     	 	 <input type="text" id="nama_ortu_wali" name="nama_ortu_wali" style="width:200px;" readonly class="disabled">';
							html_input += '	     			 		 </div>';																																						
							html_input += '	     		   		 <div class="clear"></div>';
												
							html_input += '	     					 </br>';																																		 
							html_input += '	     				</div>';	 
							html_input += '	     			</div>';						
							html_input += '	 	 	 </fieldset>';
							html_input += '	 	 </div>';
							html_input += '	 </div>';
							
							html_input += '	 <div class="div-col" style="width:1%;">';
							html_input += '	 </div>';
							
							html_input += '	 <div class="div-col-right" style="width:60%;">';
							html_input += '	     	<div class="div-row">';
							html_input += '	     		<fieldset><legend>Kondisi Akhir Anak Saat Pengajuan Beasiswa</legend>';
							html_input += '	     			</br>';
							html_input += '	     			<div class="form-row_kiri">';
							html_input += '	           <label  style = "text-align:right;">Tgl Pengajuan *</label>';
							html_input += '	             <input type="text" id="beasiswa_tgl_pengajuan" name="beasiswa_tgl_pengajuan" value="'+v_beasiswa_tgl_pengajuan+'" maxlength="10" tabindex="3" onblur="convert_date(beasiswa_tgl_pengajuan);" style="width:222px;" readonly class="disabled">';
							html_input += '	     				 <input type="hidden" id="beasiswa_tgl_pengajuan_old" name="beasiswa_tgl_pengajuan_old" value="'+v_beasiswa_tgl_pengajuan+'">';      																			 								
							html_input += '	     			</div>';
							html_input += '	           <div class="clear"></div>';
																				
							html_input += '	     		  <div class="form-row_kiri">';
							html_input += '	          <label  style = "text-align:right;">Kondisi Saat Pengajuan</label>';
							html_input += '	            <input type="text" id="beasiswa_nama_kondisi_akhir" name="beasiswa_nama_kondisi_akhir" value="'+v_beasiswa_nama_kondisi_akhir+'" style="width:222px;" readonly class="disabled">';
							html_input += '	            <input type="hidden" id="beasiswa_kondisi_akhir" name="beasiswa_kondisi_akhir" value="'+v_beasiswa_kondisi_akhir+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_masih_sekolah" name="beasiswa_flag_masih_sekolah" value="'+v_beasiswa_flag_masih_sekolah+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_ditunda" name="beasiswa_flag_ditunda" value="'+v_beasiswa_flag_ditunda+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_dihentikan" name="beasiswa_flag_dihentikan" value="'+v_beasiswa_flag_dihentikan+'">';
							html_input += '	     				<input type="hidden" id="beasiswa_flag_diterima" name="beasiswa_flag_diterima" value="'+v_beasiswa_flag_diterima+'">'; 																		 								
							html_input += '	          </div>';
							html_input += '	          <div class="clear"></div>';
										
							html_input += '	     			<span id="span_beasiswa_tgl_kondisi_akhir" style="display:none">';
							html_input += '	     				<div class="form-row_kiri">';
							html_input += '	             <label  style = "text-align:right;">Sejak *</label>';
							html_input += '	     					<input type="text" id="beasiswa_tgl_kondisi_akhir" name="beasiswa_tgl_kondisi_akhir" value="'+v_beasiswa_tgl_kondisi_akhir+'" maxlength="10" onblur="convert_date(beasiswa_tgl_kondisi_akhir);" style="width:222px;" readonly class="disabled">';
							html_input += '	     					<input type="hidden" id="beasiswa_tgl_kondisi_akhir_old" name="beasiswa_tgl_kondisi_akhir_old" value="'+v_beasiswa_tgl_kondisi_akhir+'">';
							html_input += '	     				</div>';																		
							html_input += '	            <div class="clear"></div>';
											
							html_input += '	     				<span id="span_beasiswa_dokumen_upload" style="display:none">';
							html_input += '	     					<div class="form-row_kiri">';
							html_input += '	               <label  style = "text-align:right;">Dokumen *</label>';
							html_input += '	     						<input type="text" id="nama_dokumen" name="nama_dokumen" maxlength="100" style="width:222px;" readonly class="disabled">';
							html_input += '	     						<input type="hidden" id="nama_dokumen_old" name="nama_dokumen_old">';
							html_input += '	     						<input type="hidden" id="nama_file_dokumen" name="nama_file_dokumen">';
							html_input += '	     						<input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen">';
							html_input += '	     						<input type="hidden" id="url_dokumen" name="url_dokumen">';
							html_input += '	     						<span id="span_download_file"></span>';
							html_input += '	     					</div>';																		
							html_input += '	              <div class="clear"></div>';
							html_input += '	     				</span>';										
							html_input += '	     			</span>';
									
							html_input += '	           <div class="form-row_kiri">';
							html_input += '	           <label style = "text-align:right;">Catatan &nbsp;</label>';
							html_input += '	           	<textarea cols="255" rows="1" style="width:230px;height:15px;background-color:#f5f5f5;" id="keterangan" name="keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly>'+v_keterangan+'</textarea>';   							
							html_input += '	     			</div>';								
							html_input += '	           <div class="clear"></div>';								
							html_input += '	     		</fieldset>';	 
							html_input += '	     	</div>';
								
							html_input += '	     	<div class="div-row">';
							html_input += '	     		<fieldset><legend>Penetapan Manfaat Beasiswa</legend>';
							html_input += '	           <span id="span_tidakada_tahun_beasiswa" style="display:none;">';
							html_input += '	             <div class="div-row">';
							html_input += '	               <div class="div-col" style="width:100%;text-align:center;">';
							html_input += '	               	<b><span style="vertical-align: middle;" id="span_info_tidakada_tahun_beasiswa"></span></b>';
							html_input += '	               </div>';
							html_input += '	             </div>';								
							html_input += '	           </span>';
										
							html_input += '	     			<span id="span_ada_tahun_beasiswa" style="display:block;">';
							html_input += '	             <table id="tblThnBeasiswa" width="100%" class="table-data2">';
							html_input += '	               <thead>';
							html_input += '	                 <tr class="hr-double-bottom">';
							html_input += '	     							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:50px;">Action</th>';			 
							html_input += '	                   <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:50px;">Tahun</th>';
							html_input += '	                   <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Pendidikan/Pelatihan yang Ditempuh</th>';
							html_input += '	     							<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:100px;">Manfaat</th>';	
							html_input += '	                 </tr>';	 
							html_input += '	               </thead>';
							html_input += '	               <tbody id="data_list_ThnBeasiswa">';												
							html_input += '	                 <tr class="nohover-color">';
							html_input += '	                 	<td colspan="4" style="text-align: center;">-- belum ada data --</td>';
							html_input += '	                 </tr>';							             																
							html_input += '	               </tbody>';
							html_input += '	               <tfoot>';
							html_input += '	                 <tr>';
							html_input += '	                   <td style="text-align:right;" colspan="3">';
							html_input += '	     								<i>Total :<i>';
							html_input += '	     								<input type="hidden" id="tahun_awal" name="tahun_awal">';
							html_input += '	     								<input type="hidden" id="tahun_akhir" name="tahun_akhir">';
							html_input += '	     								<input type="hidden" id="flag_layak" name="flag_layak">';
							html_input += '	     								<input type="hidden" id="dtl_kounter_dtl" name="dtl_kounter_dtl">';
							html_input += '	                    <input type="hidden" id="dtl_count_dtl" name="dtl_count_dtl">';
							html_input += '	                    <input type="hidden" name="dtl_showmessage" style="border-width: 0;text-align:right" readonly>';
							html_input += '	     							 </td>';	  		
							html_input += '	                   <td style="text-align:right;">';
							html_input += '	     								<input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value='+format_uang(v_nom_biaya_disetujui)+' readonly class="disabled" style="text-align:right; width:120px; border-width: 1;">';
							html_input += '	     							</td>';										        
							html_input += '	                 </tr>';								
							html_input += '	               </tfoot>';
							html_input += '	             </table>';												
							html_input += '	     			</span>';							
							html_input += '	 	 	 		</fieldset>';
							html_input += '	 	 		</div>';						
							html_input += '	 </div>';
							
							html_input += '</div>';
							
							if (html_input !="")
							{
								$('#formRinci').html(html_input); 
							}
							//end set form entry rincian manfaat -------------------------------						

							fl_js_set_span_tgl_kondisi_akhir();
							//end set list kondisi akhir ---------------------------------
							
							//set dokumen kelengkapan administrasi -----------------------
							var v_nama_dokumen 			= "";
							var v_nama_file_dokumen = "";
							var v_no_urut_dokumen 	= 0;
							var v_url_dokumen 			= "";
									
							if (getValue(jdata.ViewMnfDtl.DetilDok))
							{
								for(var i = 0; i < jdata.ViewMnfDtl.DetilDok.length; i++)
								{
									//ambil yg terakhir dg keterangan PENETAPAN_BEASISWA_PERUBAHANKONDISIAKHIR --------
									if (jdata.ViewMnfDtl.DetilDok[i].KETERANGAN == "PENETAPAN_BEASISWA_PERUBAHANKONDISIAKHIR")
									{
									v_nama_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[i].NAMA_DOKUMEN);
									v_nama_file_dokumen = getValue(jdata.ViewMnfDtl.DetilDok[i].NAMA_FILE);
									v_no_urut_dokumen 	= getValue(jdata.ViewMnfDtl.DetilDok[i].NO_URUT_DOKUMEN);
									v_url_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[i].URL);			
									}
								}
								
								$('#nama_dokumen').val(v_nama_dokumen);
								$('#nama_dokumen_old').val(v_nama_dokumen);
								$('#nama_file_dokumen').val(v_nama_file_dokumen);
								$('#no_urut_dokumen').val(v_no_urut_dokumen);
								$('#url_dokumen').val(v_url_dokumen);

								if (v_nama_file_dokumen != "" && v_url_dokumen != "") 
								{
									var html_download_file = '<a href="javascript:void(0)" onclick="fl_js_DownloadDok()"> | File: '+v_nama_file_dokumen+'</a>';
						
									$("#span_download_file").html(html_download_file);
								}													
							}						
							//end set dokumen kelengkapan administrasi -------------------
							
							//set grid tahun beasiswa ------------------------------------
							var html_listthn = "";
							var iteration = 0;
							var v_dtl_tahun = "";
							var v_dtl_jenis = "";
							var v_dtl_jenjang = "";
							var v_dtl_tingkat = "";
							var v_dtl_lembaga = "";
							var v_dtl_keterangan = "";
							var v_dtl_flag_terima = "";
							var v_dtl_nom_manfaat = 0;
							var v_dtl_uraian = "";
							var v_dtl_flag_dok_lengkap = "";
																
							if (getValue(jdata.ViewMnfDtl.DetilBeasiswa))
							{
								for(var i = 0; i < jdata.ViewMnfDtl.DetilBeasiswa.length; i++)
								{
									iteration = i;
										
									v_dtl_tahun		 	  = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TAHUN);							
									v_dtl_jenis 			= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENIS);
									v_dtl_jenjang 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENJANG);
									v_dtl_tingkat 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TINGKAT);
									v_dtl_lembaga 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].LEMBAGA);
									v_dtl_keterangan 	= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].KETERANGAN);
									v_dtl_flag_terima = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_TERIMA);
									v_dtl_nom_manfaat = getValueNumber(jdata.ViewMnfDtl.DetilBeasiswa[i].NOM_MANFAAT);
									v_dtl_flag_terima = v_dtl_flag_terima == '' ? 'T' : v_dtl_flag_terima;
									v_dtl_flag_dok_lengkap = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_DOK_LENGKAP);
											
									html_listthn += '<tr>';
									html_listthn += '<td style="text-align: center;">' 
													+ '<a href="javascript:void(0)" onclick="fl_js_entry_detilbeasiswa(\''
																	+ iteration + '\', \'' 
													+ v_dtl_tahun + '\')">';
									html_listthn += '&nbsp;<img src="../../images/app_form_edit.png" border="0" align="absmiddle">&nbsp;View</a></td>';
									html_listthn += '<td style="text-align: center;"><input type="text" id='+'dtl_tahun'+iteration+' name='+'dtl_tahun'+iteration+' maxlength="4" readonly class=disabled style="text-align:center; width:50px;border-width: 1;"></td>';
									html_listthn += '<td style="text-align: left;">';
									html_listthn += '	 <input type="text" id='+'dtl_uraian'+iteration+' name='+'dtl_uraian'+iteration+' readonly class=disabled style="text-align:left; width:400px; border-width: 1;">';
									html_listthn += '	 <input type="hidden" id='+'dtl_jenis'+iteration+' name='+'dtl_jenis'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_jenjang'+iteration+' name='+'dtl_jenjang'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_tingkat'+iteration+' name='+'dtl_tingkat'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_lembaga'+iteration+' name='+'dtl_lembaga'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_keterangan'+iteration+' name='+'dtl_keterangan'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_flag_terima'+iteration+' name='+'dtl_flag_terima'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_nama_dokumen'+iteration+' name='+'dtl_nama_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_nama_file_dokumen'+iteration+' name='+'dtl_nama_file_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_no_urut_dokumen'+iteration+' name='+'dtl_no_urut_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_url_dokumen'+iteration+' name='+'dtl_url_dokumen'+iteration+'>';
									html_listthn += '	 <input type="hidden" id='+'dtl_flag_dok_lengkap'+iteration+' name='+'dtl_flag_dok_lengkap'+iteration+'>';
									html_listthn += '</td>';
									html_listthn += '<td style="text-align: left;"><input type="text" id='+'dtl_nom_manfaat'+iteration+' name='+'dtl_nom_manfaat'+iteration+' readonly class=disabled style="text-align:right; width:120px; border-width: 1;"></td>';												
									html_listthn += '</tr>';
								}
								
								if (html_listthn == "") 
								{
									html_listthn += '<tr class="nohover-color">';
									html_listthn += '<td colspan="4" style="text-align: center;">-- tidak ada tahun penerimaan beasiswa --</td>';
									html_listthn += '</tr>';
								}
								$("#data_list_ThnBeasiswa").html(html_listthn);	
								
								iteration++;						
								$("#dtl_kounter_dtl").val(iteration); 							

								//set value utk setiap kolom --------------------------------
								iteration = 0;
								v_dtl_tahun = "";
								v_dtl_jenis = "";
								v_dtl_jenjang = "";
								v_dtl_tingkat = "";
								v_dtl_lembaga = "";
								v_dtl_keterangan = "";
								v_dtl_flag_terima = "";
								v_dtl_nom_manfaat = 0;
								v_dtl_uraian = "";
								v_dtl_flag_dok_lengkap = "";
								var v_tot_d_nom_biaya_disetujui = 0;
								var v_dtl_ket_keterangan = "";
								
								var v_dtl_tahun_awal = "";
								var v_dtl_tahun_akhir = "";
								var v_dtl_flag_layak = "T";
															
								for(var i = 0; i < jdata.ViewMnfDtl.DetilBeasiswa.length; i++)
								{
									iteration = i;
									
									//reset value --------------------------------------------------
									v_dtl_tahun = "";
									v_dtl_jenis = "";
									v_dtl_jenjang = "";
									v_dtl_tingkat = "";
									v_dtl_lembaga = "";
									v_dtl_keterangan = "";
									v_dtl_flag_terima = "";
									v_dtl_nom_manfaat = 0;
									v_dtl_uraian = "";
									v_dtl_ket_keterangan = "";	
									v_dtl_flag_dok_lengkap = "";
									
									//set value ----------------------------------------------------							
									v_dtl_tahun 	 	 	= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TAHUN);								
									v_dtl_jenis 			= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENIS);
									v_dtl_jenjang 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].JENJANG);
									v_dtl_tingkat 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].TINGKAT);
									v_dtl_lembaga 		= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].LEMBAGA);
									v_dtl_keterangan 	= getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].KETERANGAN);
									v_dtl_flag_terima = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_TERIMA);
									v_dtl_nom_manfaat = parseFloat(getValueNumber(jdata.ViewMnfDtl.DetilBeasiswa[i].NOM_MANFAAT));
									v_dtl_flag_dok_lengkap = getValue(jdata.ViewMnfDtl.DetilBeasiswa[i].FLAG_DOK_LENGKAP);
									
									v_dtl_nom_manfaat = v_dtl_nom_manfaat == '' ? 0 : v_dtl_nom_manfaat;
									v_dtl_flag_terima = v_dtl_flag_terima == '' ? 'T' : v_dtl_flag_terima;
									v_tot_d_nom_biaya_disetujui += parseFloat(v_dtl_nom_manfaat,4);
									
									if (v_dtl_jenis == "")
									{
										if (v_dtl_flag_terima=="T" && v_dtl_keterangan!=="")
										{
											v_dtl_ket_keterangan = "TIDAK MENERIMA MANFAAT BEASISWA ("+v_dtl_keterangan+")"; 
										}else
										{
											v_dtl_ket_keterangan = "";	 
										}
										
										v_dtl_uraian = v_dtl_ket_keterangan;  	 
									}else
									{
										if (v_dtl_jenis == "BEASISWA")
										{
											//v_jenjang -----------------------
											//TK			TK/SEDERAJAT
											//SD			SD/SEDERAJAT
											//SMP			SLTP/SEDERAJAT
											//SMA			SLTA/SEDERAJAT
											//KULIAH	PERGURUAN TINGGI (S1)
						
											var v_dtl_ket_jenjang = "";
											if (v_dtl_jenjang == "TK")
											{
												v_dtl_ket_jenjang = "TK/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "SD")
											{
												v_dtl_ket_jenjang = "SD/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "SMP")
											{
												v_dtl_ket_jenjang = "SLTP/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "SMA")
											{
												v_dtl_ket_jenjang = "SLTA/SEDERAJAT"; 
											}else if (v_dtl_jenjang == "KULIAH")
											{
												v_dtl_ket_jenjang = "PERGURUAN TINGGI (S1)"; 
											}
											
														var v_dtl_ket_keterangan = "";
											if (v_dtl_keterangan=="")
											{
												v_dtl_ket_keterangan = ""; 
											}else
											{
												v_dtl_ket_keterangan = "("+v_dtl_keterangan+")";	 
											}			
							
											v_dtl_uraian = 'BEASISWA PENDIDIKAN UNTUK' + ' ' + v_dtl_ket_jenjang + ' TINGKAT/KELAS ' + v_dtl_tingkat + ' ' + v_dtl_ket_keterangan; 
										}else
										{
											if (v_dtl_lembaga=="")
											{
												v_dtl_ket_lembaga = ""; 
											}else
											{
												v_dtl_ket_lembaga = "LEMBAGA "+v_dtl_lembaga;	 
											}
								
														var v_dtl_ket_keterangan = "";
											if (v_dtl_keterangan=="")
											{
												v_dtl_ket_keterangan = ""; 
											}else
											{
												v_dtl_ket_keterangan = "("+v_dtl_keterangan+")";	 
											}
														
											v_dtl_uraian = 'BEASISWA UNTUK PELATIHAN' + ' ' + v_dtl_jenjang + ' TINGKAT ' + v_dtl_tingkat + ' ' + v_dtl_ket_lembaga + ' ' + v_dtl_ket_keterangan; 	 
										}
									}
									
									if (iteration==0)
									{
										v_dtl_tahun_awal = v_dtl_tahun; 
									}
									v_dtl_tahun_akhir = v_dtl_tahun;
									v_dtl_flag_layak = 'Y';
									
									//set dokumen kelengkapan administrasi utk tiap periode tahun --
									var v_dtl_nama_dokumen 			= "";
									var v_dtl_nama_file_dokumen = "";
									var v_dtl_no_urut_dokumen 	= "";
									var v_dtl_url_dokumen 			= "";
									var v_dtl_ket_dokumen 			= "";
																		
									if (v_dtl_flag_terima == "Y")
									{	
										v_dtl_ket_dokumen = "PENETAPAN_BEASISWA_"+v_dtl_tahun;
										
										if (getValue(jdata.ViewMnfDtl.DetilDok))
										{
											for(var idok = 0; idok < jdata.ViewMnfDtl.DetilDok.length; idok++)
											{
												//ambil yg terakhir dg keterangan PENETAPAN_BEASISWA_XXXX --
												if (jdata.ViewMnfDtl.DetilDok[idok].KETERANGAN == v_dtl_ket_dokumen)
												{
													v_dtl_nama_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[idok].NAMA_DOKUMEN);
													v_dtl_nama_file_dokumen = getValue(jdata.ViewMnfDtl.DetilDok[idok].NAMA_FILE);
													v_dtl_no_urut_dokumen 	= getValue(jdata.ViewMnfDtl.DetilDok[idok].NO_URUT_DOKUMEN);
													v_dtl_url_dokumen 			= getValue(jdata.ViewMnfDtl.DetilDok[idok].URL);			
												}
											}													
										}
									}						
										//end set dokumen kelengkapan administrasi utk tiap periode tahun --
							
									$('#dtl_tahun'+iteration).val(v_dtl_tahun);
									$('#dtl_uraian'+iteration).val(v_dtl_uraian);
									$('#dtl_jenis'+iteration).val(v_dtl_jenis);
									$('#dtl_jenjang'+iteration).val(v_dtl_jenjang);
									$('#dtl_flag_terima'+iteration).val(v_dtl_flag_terima);
									$('#dtl_tingkat'+iteration).val(v_dtl_tingkat);
									$('#dtl_lembaga'+iteration).val(v_dtl_lembaga);
									$('#dtl_keterangan'+iteration).val(v_dtl_keterangan);
									$('#dtl_nom_manfaat'+iteration).val(format_uang(v_dtl_nom_manfaat));
									
									$('#dtl_nama_dokumen'+iteration).val(v_dtl_nama_dokumen);
									$('#dtl_nama_file_dokumen'+iteration).val(v_dtl_nama_file_dokumen);
									$('#dtl_no_urut_dokumen'+iteration).val(v_dtl_no_urut_dokumen);
									$('#dtl_url_dokumen'+iteration).val(v_dtl_url_dokumen);
									$('#dtl_flag_dok_lengkap'+iteration).val(v_dtl_flag_dok_lengkap);									
								}						
									//end set value utk setiap kolom ----------------------------
								
								$('#tahun_awal').val(v_dtl_tahun_awal);
								$('#tahun_akhir').val(v_dtl_tahun_akhir);
								$('#flag_layak').val(v_dtl_flag_layak);
							}					
							//end set grid tahun beasiswa --------------------------------
							
							//get profile anak penerima beasiswa -------------------------
							fjq_ajax_val_getprofilebynikpenerima();
							//end get profile anak penerima beasiswa ---------------------
							
																		
						}
					} catch (e) 
					{
						alert("Terjadi kesalahan, coba beberapa saat lagi!!!");
					}
					asyncPreload(false);
				},
				complete: function(){
				asyncPreload(false);
			},
			error: function()
			{
				alert("Terjadi kesalahan, coba beberapa saat lagi!!");
				asyncPreload(false);
			}
		});			
	}	
</script>

<script language="javascript">
	function fl_js_back2gridmanfaat() 
	{																						
    	var v_sender 				 = $('#sender').val();
		var v_form_penetapan = $('#form_penetapan').val();
		var v_kode_klaim 	 	 = $('#kode_klaim').val();
		var v_kode_manfaat 	 = $('#kode_manfaat').val();
		var v_mid 					 = '<?=$mid;?>';
		
		if (v_sender != '')
		{
		 	 window.location.replace('http://<?= $HTTP_HOST;?>/mod_pn/ajax/'+v_sender+'?task=edit&kode_klaim='+v_kode_klaim+'&kode_manfaat='+v_kode_manfaat+'&form_penetapan='+v_form_penetapan+'&sender_activetab=2&sender_mid='+v_mid+'');
		}
  	}

	function fl_js_get_lov_nikpenerimabeasiswa()
	{			 					
		var v_nik_tk  = window.document.getElementById('nik_tk').value;
		var v_nama_tk = window.document.getElementById('nama_tk').value;
		
		//reset terlebih dahulu kondisi akhir, keterangan dan table periode --------
		$('#beasiswa_kondisi_akhir').val('');
		$('#beasiswa_tgl_kondisi_akhir').val('');
		$('#beasiswa_tgl_kondisi_akhir_old').val('');
		$('#nama_dokumen').val('');
		$('#nama_dokumen_old').val('');
		$('#nama_file_dokumen').val('');
		$('#no_urut_dokumen').val('');
		$('#url_dokumen').val('');
		$('#keterangan').val('');
		$("#data_list_ThnBeasiswa").html('');
		$("#nom_biaya_disetujui").html('0');
		
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5061_lov_nikpenerimabeasiswa.php?p=pn5041.php&a=formreg&b=beasiswa_nik_penerima&c='+v_nik_tk+'&d='+v_nama_tk+'','',900,500,1);
  	}
		
	function fjq_ajax_val_getprofilebynikpenerima()
	{			 					
		var v_nik_penerima = $('#beasiswa_nik_penerima').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		var v_kode_segmen  = $('#kode_segmen').val();
		var v_kode_tipe_penerima = $('#kode_tipe_penerima').val();
		
		if (v_nik_penerima == '') {
			return alert('Harap memilih Data Anak Penerima Beasiswa terlebih dahulu');
		}
				
		//load data penerima beasiswa ----------------------------------------------
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5073_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatabynikpenerima',
				v_nik_penerima: v_nik_penerima
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
						$('#nama_penerima').val(getValue(jdata.data.dataNikAnak['NAMA_PENERIMA']));
						$('#tempat_lahir').val(getValue(jdata.data.dataNikAnak['TEMPAT_LAHIR']));
						$('#tgl_lahir').val(getValue(jdata.data.dataNikAnak['TGL_LAHIR']));
						$('#l_jenis_kelamin').val(getValue(jdata.data.dataNikAnak['NM_JENIS_KELAMIN']));
						$('#jenis_kelamin').val(getValue(jdata.data.dataNikAnak['JENIS_KELAMIN']));
						$('#alamat').val(getValue(jdata.data.dataNikAnak['ALAMAT']));
						$('#email').val(getValue(jdata.data.dataNikAnak['EMAIL']));
						$('#handphone').val(getValue(jdata.data.dataNikAnak['HANDPHONE']));
						$('#nama_ortu_wali').val(getValue(jdata.data.dataNikAnak['NAMA_ORTU_WALI']));
						
						//------------------------------------------------------------------	
						var v_kode_tipe_penerima_beasiswa = getValue(jdata.data.dataNikAnak['KODE_TIPE_PENERIMA']);
						var v_nama_tipe_penerima_beasiswa = "";
						
						if (v_kode_tipe_penerima_beasiswa == "B1")
						{
						 	v_nama_tipe_penerima_beasiswa = "PENERIMA BEASISWA I"; 
						}else if (v_kode_tipe_penerima_beasiswa == "B2")
						{
						 	v_nama_tipe_penerima_beasiswa = "PENERIMA BEASISWA II"; 		
						}
						
						fl_js_reset_dataList('kode_tipe_penerima');
						$("#kode_tipe_penerima").append('<option value="">-- Pilih Tipe Penerima --</option>');	 
																			
						$.ajax({
							type: 'POST',
							url: "../ajax/pn5061_action.php?"+Math.random(),
							data: {
								tipe: 'fjq_ajax_val_getlist_tipepenerima',
								v_kode_manfaat: v_kode_manfaat,
										v_kode_segmen: v_kode_segmen
							},
							success: function(data){
								try 
								{
									jdata2 = JSON.parse(data);
									if (jdata2.ret == 0) 
									{
										if (jdata2.dtListTipePenerima.DATA)
										{
											for($i=0;$i<(jdata2.dtListTipePenerima.DATA.length);$i++)
											{
												if (jdata2.dtListTipePenerima.DATA[$i]['KODE_TIPE_PENERIMA'] == "AW" || jdata2.dtListTipePenerima.DATA[$i]['KODE_TIPE_PENERIMA'] == v_kode_tipe_penerima_beasiswa)
												{
													if (jdata2.dtListTipePenerima.DATA[$i]['KODE_TIPE_PENERIMA'] == v_kode_tipe_penerima)
													{
														$("#kode_tipe_penerima").append('<option value="'+jdata2.dtListTipePenerima.DATA[$i]['KODE_TIPE_PENERIMA']+'" selected="selected">'+jdata2.dtListTipePenerima.DATA[$i]['NAMA_TIPE_PENERIMA']+'</option>');
													}else
													{
														$("#kode_tipe_penerima").append('<option value="'+jdata2.dtListTipePenerima.DATA[$i]['KODE_TIPE_PENERIMA']+'">'+jdata2.dtListTipePenerima.DATA[$i]['NAMA_TIPE_PENERIMA']+'</option>');	 
													}
												}
											}
										}				
									} else 
									{
        								//alert(jdata2.msg);
										asyncPreload(false);
        							}
								} catch (e) 
								{
									//alert("Terjadi kesalahan, coba beberapa saat lagi!");
									asyncPreload(false);
								}
								asyncPreload(false);
							},
							complete: function()
							{
        						asyncPreload(false);
        					},
							error: function()
							{
								//alert("Terjadi kesalahan, coba beberapa saat lagi!");
								asyncPreload(false);
        					}
        				});
						//------------------------------------------------------------------		
												
					} else {
						alert(jdata.msg);
					}
				} catch (e) 
				{
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
				}
				asyncPreload(false);
			},
			complete: function(){
			asyncPreload(false);
			},
			error: function()
			{
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				asyncPreload(false);
			}
		});
  	}
	
	function fl_js_cek_tgl_pengajuan()
	{
	 	var v_tgl_pengajuan = $('#beasiswa_tgl_pengajuan').val();
		var v_tgl_pengajuan_old = $('#beasiswa_tgl_pengajuan_old').val();
		
		if (v_tgl_pengajuan != v_tgl_pengajuan_old)
		{
  		//cek apakah tgl pengajuan dan tgl kondisi akhir anak > sysdate ----------
			var v_today = new Date();
			var v_dd = v_today.getDate();
			var v_mm = v_today.getMonth() + 1; //January is 0!
      
			var v_yyyy = v_today.getFullYear();
			if (v_dd < 10) {
				v_dd = '0' + v_dd;
			} 
			if (v_mm < 10) {
				v_mm = '0' + v_mm;
			} 
      		var v_today_yyyymmdd = v_yyyy + v_mm + v_dd;
  		
  			var v_tgl_pengajuan_yyymmdd = "";				
			if (v_tgl_pengajuan != "")
			{ 
  				v_tgl_pengajuan_yyymmdd = v_tgl_pengajuan.substring(6, 10)+v_tgl_pengajuan.substring(3, 5)+v_tgl_pengajuan.substring(0, 2);
  			
				if (v_tgl_pengajuan_yyymmdd > v_today_yyyymmdd)
				{
					$('#beasiswa_tgl_pengajuan').val('');
					$('#beasiswa_tgl_pengajuan_old').val('');
					alert('TGL PENGAJUAN TIDAK BOLEH LEBIH BESAR DARI HARI INI..!'); 
				}else
				{
				 	var v_jenis_klaim = $('#jenis_klaim').val();
					var v_tgl_kejadian_yyymmdd = $('#tgl_kejadian_yyymmdd').val();
					var v_tgl_kondisi_terakhir_yyymmdd = $('#tgl_kondisi_terakhir_yyymmdd').val();
					var v_tgl_kejadian = $('#tgl_kejadian').val();
					var v_tgl_kondisi_terakhir = $('#tgl_kondisi_terakhir').val();
					var v_is_error = "T";
					
					if (v_jenis_klaim=="JKK")
					{
					 	if (v_tgl_pengajuan_yyymmdd < v_tgl_kondisi_terakhir_yyymmdd)
						{
						 	v_is_error = "Y";
							$('#beasiswa_tgl_pengajuan').val('');
							$('#beasiswa_tgl_pengajuan_old').val('');
							alert('TGL PENGAJUAN '+v_tgl_pengajuan+' TIDAK BOLEH LEBIH KECIL DARI TGL KONDISI AKHIR JKK '+v_tgl_kondisi_terakhir+'..!'); 
						} 
					}else
					{
					 	if (v_tgl_pengajuan_yyymmdd < v_tgl_kejadian_yyymmdd)
						{
						 	v_is_error = "Y";
							$('#beasiswa_tgl_pengajuan').val('');
							$('#beasiswa_tgl_pengajuan_old').val('');
							alert('TGL PENGAJUAN '+v_tgl_pengajuan+' TIDAK BOLEH LEBIH KECIL DARI TGL KEJADIAN '+v_tgl_kejadian+'..!'); 
						} 					
					}
					
					if (v_is_error == "T")
					{
						$('#beasiswa_tgl_pengajuan_old').val(v_tgl_pengajuan);
						fjq_ajax_val_get_tahun_beasiswa();
					}
					
				}
			}
		}					 
	}
	
	function fl_js_cek_tgl_kondisi_akhir_penerima()
	{
	 	var v_tgl_kondisi = $('#beasiswa_tgl_kondisi_akhir').val();
		var v_tgl_kondisi_old = $('#beasiswa_tgl_kondisi_akhir_old').val();
		
		if (v_tgl_kondisi != v_tgl_kondisi_old)
		{
			//cek apakah tgl pengajuan dan tgl kondisi akhir anak > sysdate ----------
			var v_today = new Date();
			var v_dd = v_today.getDate();
			var v_mm = v_today.getMonth() + 1; //January is 0!
      
			var v_yyyy = v_today.getFullYear();
			if (v_dd < 10) {
				v_dd = '0' + v_dd;
			} 
			if (v_mm < 10) {
				v_mm = '0' + v_mm;
			} 
			var v_today_yyyymmdd = v_yyyy.toString() + v_mm.toString() + v_dd.toString();
  		
  			var v_tgl_kondisi_yyymmdd = "";				
			if (v_tgl_kondisi != "")
			{ 
				v_tgl_kondisi_yyymmdd = v_tgl_kondisi.substring(6, 10)+v_tgl_kondisi.substring(3, 5)+v_tgl_kondisi.substring(0, 2);
				
				if (v_tgl_kondisi_yyymmdd > v_today_yyyymmdd)
				{
					$('#beasiswa_tgl_kondisi_akhir').val('');
						$('#beasiswa_tgl_kondisi_akhir_old').val('');
					alert('TGL KONDISI AKHIR ANAK (MENIKAH/BEKERJA/MENINGGAL DUNIA) TIDAK BOLEH LEBIH BESAR DARI HARI INI..!'); 
				}else
				{
						$('#beasiswa_tgl_kondisi_akhir_old').val(v_tgl_kondisi);
						fjq_ajax_val_get_tahun_beasiswa();	 
				}
			}
		}					 
	}
	
	function fl_js_set_span_tgl_kondisi_akhir() 
	{ 
		var	v_kondisi_akhir = $('#beasiswa_kondisi_akhir').val();
			
		//KA13	SUDAH MENCAPAI USIA 23 TAHUN -- otomatis dicek pada saat validasi
		//KA12	MENIKAH
		//KA14	BEKERJA
		//KA11	MENINGGAL DUNIA
		//KA19	TENAGA KERJA TIDAK MEMENUHI LAGI KONDISI CACAT TOTAL TETAP
		
		if (v_kondisi_akhir =="KA13" || v_kondisi_akhir =="KA12" || v_kondisi_akhir =="KA14" || v_kondisi_akhir =="KA11" || v_kondisi_akhir =="KA19")
		{    
			window.document.getElementById("span_beasiswa_tgl_kondisi_akhir").style.display = 'block';
			if (v_kondisi_akhir =="KA12" || v_kondisi_akhir =="KA14" || v_kondisi_akhir =="KA11" || v_kondisi_akhir =="KA19")
			{ 
				window.document.getElementById("span_beasiswa_dokumen_upload").style.display = 'block';
					
				var v_nama_dokumen = '';
				if (v_kondisi_akhir =="KA12")
				{
					v_nama_dokumen = "BUKU NIKAH/AKTE NIKAH"; 
				}else if (v_kondisi_akhir =="KA14")
				{
					v_nama_dokumen = "KETERANGAN BEKERJA"; 
				}else if (v_kondisi_akhir =="KA11")
				{
					v_nama_dokumen = "SURAT KEMATIAN/AKTE KEMATIAN"; 
				}else if (v_kondisi_akhir =="KA19")
				{
					v_nama_dokumen = "SURAT KETERANGAN DOKTER"; 
				}
					
				$('#nama_dokumen').val(v_nama_dokumen);
				$('#nama_dokumen_old').val(v_nama_dokumen);
			}else
			{
				window.document.getElementById("span_beasiswa_dokumen_upload").style.display = 'none';
				$('#nama_dokumen').val('');	
				$('#nama_dokumen_old').val('');	 
			}
		}else
		{
      		window.document.getElementById("span_beasiswa_tgl_kondisi_akhir").style.display = 'none';
			window.document.getElementById("beasiswa_tgl_kondisi_akhir").value = "";
			$('#beasiswa_tgl_kondisi_akhir_old').val('');
			$('#nama_dokumen').val('');
			$('#nama_dokumen_old').val('');	 
    	} 
  	}
	
	function fl_js_reset_dataList(id)
	{
		var selectObj = document.getElementById(id);
		var selectParentNode = selectObj.parentNode;
		var newSelectObj = selectObj.cloneNode(false); // Make a shallow copy
		selectParentNode.replaceChild(newSelectObj, selectObj);
		return newSelectObj;
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
			
	function fjq_ajax_val_get_tahun_beasiswa()
	{
    	var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		var v_no_urut 		 = $('#no_urut').val();
		var v_nik_penerima = $('#beasiswa_nik_penerima').val();
		var v_tgl_lahir 	 = $('#tgl_lahir').val();
		var v_tgl_pengajuan 		= $('#beasiswa_tgl_pengajuan').val();
		var v_kondisi_akhir_penerima 		 = $('#beasiswa_kondisi_akhir').val();
		var v_tgl_kondisi_akhir_penerima = $('#beasiswa_tgl_kondisi_akhir').val();
						
		$("#data_list_ThnBeasiswa").html('');
		
		if (v_kode_klaim!='' && v_kode_manfaat!='' && v_nik_penerima!='' && v_tgl_lahir!='' && v_kondisi_akhir_penerima!='')
		{								
			var v_getdt = "Y";
    		if ((v_kondisi_akhir_penerima =="KA12" || v_kondisi_akhir_penerima =="KA14" || v_kondisi_akhir_penerima =="KA11" || v_kondisi_akhir_penerima =="KA19") && v_tgl_kondisi_akhir_penerima=="")
			{
			 	v_getdt = "T"; //tgl kondisi akhir anak masih kosong
			}
			
			if (v_getdt == "Y")
			{
  				$.ajax(
				{
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5061_action.php?'+Math.random(),
					data: { 
									tipe:'fjq_ajax_val_get_tahun_beasiswapp82',
									v_kode_klaim: v_kode_klaim,
									v_kode_manfaat: v_kode_manfaat,
									v_no_urut: v_no_urut,
									v_nik_penerima: v_nik_penerima,
									v_tgl_lahir: v_tgl_lahir,
									v_tgl_pengajuan: v_tgl_pengajuan,
									v_kondisi_akhir_penerima: v_kondisi_akhir_penerima,
									v_tgl_kondisi_akhir_penerima: v_tgl_kondisi_akhir_penerima
						},
							success: function(data)
						{
							jdata = JSON.parse(data);
							if(jdata.ret=="0")
							{
								var html_listthn = "";
								var iteration = 0;
								var v_dtl_tahun = "";
								var v_dtl_jenis = "";
								var v_dtl_jenjang = "";
								var v_dtl_tingkat = "";
								var v_dtl_lembaga = "";
								var v_dtl_keterangan = "";
								var v_dtl_flag_terima = "";
								var v_dtl_nom_manfaat = 0;
								var v_dtl_uraian = "";
								var v_dtl_flag_dok_lengkap = "";
								
								//retrieve data utk yg manfaat ditunda krn dokumen blm lengkap ---
								var iteration = 0;
								if (JSON.stringify(jdata.dtLalu) !== '[]')
								{
									
									for(var i = 0; i < jdata.dtLalu.length; i++)
									{
										iteration_dtlLalu = i;
											
										v_dtl_tahun		 	  	= getValue(jdata.dtLalu[i].TAHUN);							
										v_dtl_jenis 			= getValue(jdata.dtLalu[i].JENIS);
										v_dtl_jenjang 			= getValue(jdata.dtLalu[i].JENJANG);
										v_dtl_tingkat 			= getValue(jdata.dtLalu[i].TINGKAT);
										v_dtl_lembaga 			= getValue(jdata.dtLalu[i].LEMBAGA);
										v_dtl_keterangan 		= getValue(jdata.dtLalu[i].KETERANGAN);
										v_dtl_flag_terima 		= getValue(jdata.dtLalu[i].FLAG_TERIMA);
										v_dtl_nom_manfaat 		= getValueNumber(jdata.dtLalu[i].NOM_MANFAAT);
										v_dtl_flag_terima 		= v_dtl_flag_terima == '' ? 'T' : v_dtl_flag_terima;
										v_dtl_flag_dok_lengkap 	= getValue(jdata.dtLalu[i].FLAG_DOK_LENGKAP);
										v_dtl_data				= 'TUNDA';
												
										// html_listthn += '<tr>';
										html_listthn += '<td style="text-align: center;">' 
															+ '<a href="javascript:void(0)" onclick="fl_js_entry_detilbeasiswa(\''
																			+ iteration_dtlLalu + '\', \'' 
															+ v_dtl_tahun + '\', \'' 
															+ v_dtl_data + '\')">';
										html_listthn += '&nbsp;<img src="../../images/app_form_edit.png" border="0" align="absmiddle">&nbsp;Entri</a></td>';
										html_listthn += '<td style="text-align: center;"><input type="text" id='+'dtl_tahun_dtlLalu'+iteration_dtlLalu+' name='+'dtl_tahun_dtlLalu'+iteration_dtlLalu+' maxlength="4" readonly class=disabled style="text-align:center; width:50px;border-width: 1;"></td>';
										html_listthn += '<td style="text-align: left;">';
										html_listthn += '	 <input type="text" id='+'dtl_uraian_dtlLalu'+iteration_dtlLalu+' name='+'dtl_uraian_dtlLalu'+iteration_dtlLalu+' readonly class=disabled style="text-align:left; width:400px; border-width: 1;">';
										html_listthn += '	 <input type="hidden" id='+'dtl_jenis_dtlLalu'+iteration_dtlLalu+' name='+'dtl_jenis_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_jenjang_dtlLalu'+iteration_dtlLalu+' name='+'dtl_jenjang_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_tingkat_dtlLalu'+iteration_dtlLalu+' name='+'dtl_tingkat_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_lembaga_dtlLalu'+iteration_dtlLalu+' name='+'dtl_lembaga_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_keterangan_dtlLalu'+iteration_dtlLalu+' name='+'dtl_keterangan_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_flag_terima_dtlLalu'+iteration_dtlLalu+' name='+'dtl_flag_terima_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_nama_dokumen_dtlLalu'+iteration_dtlLalu+' name='+'dtl_nama_dokumen_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_nama_file_dokumen_dtlLalu'+iteration_dtlLalu+' name='+'dtl_nama_file_dokumen_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_no_urut_dokumen_dtlLalu'+iteration_dtlLalu+' name='+'dtl_no_urut_dokumen_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_url_dokumen_dtlLalu'+iteration_dtlLalu+' name='+'dtl_url_dokumen_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '	 <input type="hidden" id='+'dtl_flag_dok_lengkap_dtlLalu'+iteration_dtlLalu+' name='+'dtl_flag_dok_lengkap_dtlLalu'+iteration_dtlLalu+'>';
										html_listthn += '</td>';
										html_listthn += '<td style="text-align: left;"><input type="text" id='+'dtl_nom_manfaat_dtlLalu'+iteration_dtlLalu+' name='+'dtl_nom_manfaat_dtlLalu'+iteration_dtlLalu+' readonly class=disabled style="text-align:right; width:120px; border-width: 1;"></td>';												
										// html_listthn += '</tr>';										
									}
											
									if (html_listthn == "") {
										html_listthn += '<tr class="nohover-color">';
										html_listthn += '<td colspan="4" style="text-align: center;">-- tidak ada tahun penerimaan beasiswa --</td>';
										html_listthn += '</tr>';
									}
									$("#data_list_ThnBeasiswa1").html(html_listthn);	
											
									iteration_dtlLalu++;						
									$("#dtl_kounter_dtl_dtlLalu").val(iteration_dtlLalu);

									var ls_dtl_kounter_dtl_dtlLalu = iteration_dtlLalu;
											
									//set value utk setiap kolom --------------------------------
									iteration_dtlLalu = 0;
									v_dtl_tahun = "";
									v_dtl_jenis = "";
									v_dtl_jenjang = "";
									v_dtl_tingkat = "";
									v_dtl_lembaga = "";
									v_dtl_keterangan = "";
									v_dtl_flag_terima = "";
									v_dtl_nom_manfaat = 0;
									v_dtl_uraian = "";
									v_dtl_flag_dok_lengkap = "";
										
									var v_tot_d_nom_biaya_disetujui = 0;
									var v_dtl_ket_keterangan = "";
										
									var v_dtl_tahun_awal = "";
									var v_dtl_tahun_akhir = "";
									var v_dtl_flag_layak = "T";
																	
									for(var i = 0; i < jdata.dtLalu.length; i++)
									{
										iteration_dtlLalu = i;
											
										//reset value --------------------------------------------------
										v_dtl_tahun = "";
										v_dtl_jenis = "";
										v_dtl_jenjang = "";
										v_dtl_tingkat = "";
										v_dtl_lembaga = "";
										v_dtl_keterangan = "";
										v_dtl_flag_terima = "";
										v_dtl_nom_manfaat = 0;
										v_dtl_uraian = "";
										v_dtl_ket_keterangan = "";	
										v_dtl_flag_dok_lengkap = "";
											
										//set value ----------------------------------------------------							
										v_dtl_tahun 	 	 	= getValue(jdata.dtLalu[i].TAHUN);								
										v_dtl_jenis 			= getValue(jdata.dtLalu[i].JENIS);
										v_dtl_jenjang 		= getValue(jdata.dtLalu[i].JENJANG);
										v_dtl_tingkat 		= getValue(jdata.dtLalu[i].TINGKAT);
										v_dtl_lembaga 		= getValue(jdata.dtLalu[i].LEMBAGA);
										v_dtl_keterangan 	= getValue(jdata.dtLalu[i].KETERANGAN);
										v_dtl_flag_terima = getValue(jdata.dtLalu[i].FLAG_TERIMA);
										v_dtl_nom_manfaat = parseFloat(getValueNumber(jdata.dtLalu[i].NOM_MANFAAT));
										v_dtl_flag_dok_lengkap = getValue(jdata.dtLalu[i].FLAG_DOK_LENGKAP);
											
										v_dtl_nom_manfaat = v_dtl_nom_manfaat == '' ? 0 : v_dtl_nom_manfaat;
										v_dtl_flag_terima = v_dtl_flag_terima == '' ? 'T' : v_dtl_flag_terima;
										v_dtl_flag_dok_lengkap = v_dtl_flag_dok_lengkap == '' ? 'T' : v_dtl_flag_dok_lengkap;
										v_tot_d_nom_biaya_disetujui += parseFloat(v_dtl_nom_manfaat,4);
											
										if (v_dtl_jenis == "")
										{
											if (v_dtl_flag_terima=="T" && v_dtl_keterangan!=="")
											{
												v_dtl_ket_keterangan = "TIDAK MENERIMA MANFAAT BEASISWA ("+v_dtl_keterangan+")"; 
											}else if (v_dtl_flag_terima=="Y" && v_dtl_flag_dok_lengkap==="T")
											{
												v_dtl_ket_keterangan = "PENUNDAAN KARENA DOKUMEN TIDAK LENGKAP ("+v_dtl_keterangan+")"; 
											}else
											{
												v_dtl_ket_keterangan = "";	 
											}
												
											v_dtl_uraian = v_dtl_ket_keterangan;  	 
										}else
										{
											if (v_dtl_jenis == "BEASISWA")
											{
												//v_jenjang -----------------------
												//TK			TK/SEDERAJAT
												//SD			SD/SEDERAJAT
												//SMP			SLTP/SEDERAJAT
												//SMA			SLTA/SEDERAJAT
												//KULIAH	PERGURUAN TINGGI (S1)
										
												var v_dtl_ket_jenjang = "";
												if (v_dtl_jenjang == "TK")
												{
													v_dtl_ket_jenjang = "TK/SEDERAJAT"; 
												}else if (v_dtl_jenjang == "SD")
												{
													v_dtl_ket_jenjang = "SD/SEDERAJAT"; 
												}else if (v_dtl_jenjang == "SMP")
												{
													v_dtl_ket_jenjang = "SLTP/SEDERAJAT"; 
												}else if (v_dtl_jenjang == "SMA")
												{
													v_dtl_ket_jenjang = "SLTA/SEDERAJAT"; 
												}else if (v_dtl_jenjang == "KULIAH")
												{
													v_dtl_ket_jenjang = "PERGURUAN TINGGI (S1)"; 
												}
													
												var v_dtl_ket_keterangan = "";
												if (v_dtl_keterangan=="")
												{
													v_dtl_ket_keterangan = ""; 
												}else
												{
													v_dtl_ket_keterangan = "("+v_dtl_keterangan+")";	 
												}			
													
												v_dtl_uraian = 'BEASISWA PENDIDIKAN UNTUK' + ' ' + v_dtl_ket_jenjang + ' TINGKAT/KELAS ' + v_dtl_tingkat + ' ' + v_dtl_ket_keterangan; 
											}else
											{
												if (v_dtl_lembaga=="")
												{
													v_dtl_ket_lembaga = ""; 
												}else
												{
													v_dtl_ket_lembaga = "LEMBAGA "+v_dtl_lembaga;	 
												}
										
												var v_dtl_ket_keterangan = "";
												if (v_dtl_keterangan=="")
												{
													v_dtl_ket_keterangan = ""; 
												}else
												{
													v_dtl_ket_keterangan = "("+v_dtl_keterangan+")";	 
												}
																
												v_dtl_uraian = 'BEASISWA UNTUK PELATIHAN' + ' ' + v_dtl_jenjang + ' TINGKAT ' + v_dtl_tingkat + ' ' + v_dtl_ket_lembaga + ' ' + v_dtl_ket_keterangan; 	 
											}
										}
											
											if (iteration_dtlLalu==0)
											{
												v_dtl_tahun_awal = v_dtl_tahun; 
											}
											v_dtl_tahun_akhir = v_dtl_tahun;
											v_dtl_flag_layak = 'Y';
											
											//set dokumen kelengkapan administrasi utk tiap periode tahun --
											var v_dtl_nama_dokumen 			= "";
											var v_dtl_nama_file_dokumen = "";
											var v_dtl_no_urut_dokumen 	= "";
											var v_dtl_url_dokumen 			= "";
											var v_dtl_ket_dokumen 			= "";
											//end set dokumen kelengkapan administrasi utk tiap periode tahun --
										
											$('#dtl_tahun_dtlLalu'+iteration_dtlLalu).val(v_dtl_tahun);
											$('#dtl_uraian_dtlLalu'+iteration_dtlLalu).val(v_dtl_uraian);
											$('#dtl_jenis_dtlLalu'+iteration_dtlLalu).val(v_dtl_jenis);
											$('#dtl_jenjang_dtlLalu'+iteration_dtlLalu).val(v_dtl_jenjang);
											$('#dtl_flag_terima_dtlLalu'+iteration_dtlLalu).val(v_dtl_flag_terima);
											$('#dtl_tingkat_dtlLalu'+iteration_dtlLalu).val(v_dtl_tingkat);
											$('#dtl_lembaga_dtlLalu'+iteration_dtlLalu).val(v_dtl_lembaga);
											$('#dtl_keterangan_dtlLalu'+iteration_dtlLalu).val(v_dtl_keterangan);
											$('#dtl_nom_manfaat_dtlLalu'+iteration_dtlLalu).val(format_uang(v_dtl_nom_manfaat));
											
											$('#dtl_nama_dokumen_dtlLalu'+iteration_dtlLalu).val(v_dtl_nama_dokumen);
											$('#dtl_nama_file_dokumen_dtlLalu'+iteration_dtlLalu).val(v_dtl_nama_file_dokumen);
											$('#dtl_no_urut_dokume_dtlLalun'+iteration_dtlLalu).val(v_dtl_no_urut_dokumen);
											$('#dtl_url_dokumen_dtlLalu'+iteration_dtlLalu).val(v_dtl_url_dokumen);
											$('#dtl_flag_dok_lengkap_dtlLalu'+iteration_dtlLalu).val(v_dtl_flag_dok_lengkap);									
									}							
									//end set value utk setiap kolom ----------------------------
										
									$('#tahun_awal_dtlLalu').val(v_dtl_tahun_awal);
									$('#tahun_akhir_dtlLalu').val(v_dtl_tahun_akhir);
									$('#flag_layak_dtlLalu').val(v_dtl_flag_layak);								 																
								}
								//end retrieve data utk yg manfaat ditunda krn dokumen blm lengkap
										
								//retrieve data periode tahun berjalan/rapel ---------------------
								if (getValue(jdata.dtThn['FLAG_LAYAK']) == "Y")
								{
									//set grid element ---------------------------------------------
									var v_tahun_awal  = getValueNumber(jdata.dtThn['TAHUN_AWAL_BEASISWA']);
									var v_tahun_akhir = getValueNumber(jdata.dtThn['TAHUN_AKHIR_BEASISWA']);
									var v_jml_iterasi = (parseInt(v_tahun_akhir)-parseInt(v_tahun_awal))+1;
									v_dtl_data		  = 'BERJALAN';
											
									if (v_tahun_awal == v_tahun_akhir)
									{
										$('#keterangan').val('BEASISWA UNTUK TAHUN '+v_tahun_awal); 
									}else
									{
										$('#keterangan').val('BEASISWA UNTUK TAHUN '+v_tahun_awal+' S/D '+v_tahun_akhir);
									}															 
									$('#flag_layak').val('Y');
											
									var html_listthn = "";
									var iteration = 0;
									var v_dtl_tahun = "";
									var v_dtl_uraian = "";
									var v_dtl_nom_manfaat = "";
										
									for(var i = 0; i < v_jml_iterasi; i++)
									{
										iteration = i;
										v_dtl_tahun	 = parseInt(v_tahun_awal) + iteration;
											
										html_listthn += '<tr>';
										html_listthn += '<td style="text-align: center;">' 
																+ '<a href="javascript:void(0)" onclick="fl_js_entry_detilbeasiswa(\''
																				+ iteration + '\', \''  
																+ v_dtl_tahun + '\')">';
										html_listthn += '&nbsp;<img src="../../images/app_form_edit.png" border="0" align="absmiddle">&nbsp;Entri</a></td>';
										html_listthn += '<td style="text-align: center;"><input type="text" id='+'dtl_tahun'+iteration+' name='+'dtl_tahun'+iteration+' maxlength="4" readonly class=disabled style="text-align:center; width:50px;border-width: 1;"></td>';
										html_listthn += '<td style="text-align: left;">';
										html_listthn += '	<input type="text" id='+'dtl_uraian'+iteration+' name='+'dtl_uraian'+iteration+' readonly class=disabled style="text-align:left; width:400px; border-width: 1;">';
										html_listthn += '	<input type="hidden" id='+'dtl_jenis'+iteration+' name='+'dtl_jenis'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_jenjang'+iteration+' name='+'dtl_jenjang'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_tingkat'+iteration+' name='+'dtl_tingkat'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_lembaga'+iteration+' name='+'dtl_lembaga'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_keterangan'+iteration+' name='+'dtl_keterangan'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_flag_terima'+iteration+' name='+'dtl_flag_terima'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_nama_dokumen'+iteration+' name='+'dtl_nama_dokumen'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_nama_file_dokumen'+iteration+' name='+'dtl_nama_file_dokumen'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_no_urut_dokumen'+iteration+' name='+'dtl_no_urut_dokumen'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_url_dokumen'+iteration+' name='+'dtl_url_dokumen'+iteration+'>';
										html_listthn += '	<input type="hidden" id='+'dtl_flag_dok_lengkap'+iteration+' name='+'dtl_flag_dok_lengkap'+iteration+'>';
										html_listthn += '</td>';
										html_listthn += '<td style="text-align: left;"><input type="text" id='+'dtl_nom_manfaat'+iteration+' name='+'dtl_nom_manfaat'+iteration+' readonly class=disabled style="text-align:right; width:120px; border-width: 1;"></td>';												
										html_listthn += '</tr>';
									}
				
									if (html_listthn == "") {
										html_listthn += '<tr class="nohover-color">';
										html_listthn += '<td colspan="4" style="text-align: center;">-- tidak ada tahun penerimaan beasiswa --</td>';
										html_listthn += '</tr>';
									}
									$("#data_list_ThnBeasiswa").html(html_listthn);	
											
									iteration++;						
									$("#dtl_kounter_dtl").val(iteration); 						
				
									//set value utk setiap kolom --------------------------------
									iteration = 0;
									v_dtl_tahun = "";
									v_dtl_uraian = "";
									v_dtl_nom_manfaat = "";
											
									for(var i = 0; i < v_jml_iterasi; i++)
									{
										iteration = i;
											
										v_dtl_tahun	 = parseInt(v_tahun_awal) + iteration;
										v_dtl_uraian = "";
										v_dtl_nom_manfaat = "";
											
										$('#dtl_tahun'+iteration).val(v_dtl_tahun);
										$('#dtl_uraian'+iteration).val(v_dtl_uraian);
										$('#dtl_nom_manfaat'+iteration).val(v_dtl_nom_manfaat);	
									}						
									//end set value utk setiap kolom ----------------------------
									//end set grid element -------------------------------------------
										
									//set status usia 23 jika anak sudah mencapai usia 23 ------------
									var v_flag_setusia23 = getValue(jdata.dtThn['FLAG_SETUSIA23']);
									var v_tgl_setusia23  = getValue(jdata.dtThn['TGL_SETUSIA23']);
										
									//list direset hanya anak usia 23 ----------------
									if (v_flag_setusia23=="Y")
									{
										fl_js_reset_dataList('beasiswa_kondisi_akhir');
										var v_kdlist_usia23 = "KA13";
										var v_nmlist_usia23 = "SUDAH MENCAPAI USIA 23 TAHUN";
										$("#beasiswa_kondisi_akhir").append('<option value="'+v_kdlist_usia23+'">'+v_nmlist_usia23+'</option>');
										
										$('#beasiswa_kondisi_akhir').val(v_kdlist_usia23);
										$('#beasiswa_tgl_kondisi_akhir').val(v_tgl_setusia23);
										$('#beasiswa_tgl_kondisi_akhir_old').val(v_tgl_setusia23);
										fl_js_set_span_tgl_kondisi_akhir();
											
										alert('ANAK SUDAH MENCAPAI USIA 23 TAHUN SEJAK '+v_tgl_setusia23+', MANFAAT BEASISWA UNTUK TAHUN SELANJUTNYA OTOMATIS AKAN DIHENTIKAN');	
									}else
									{
										var v_ket_status_bekerja = getValue(jdata.dtThn['KET_STATUS_BEKERJA']); 
										if (v_ket_status_bekerja !="")
										{
											alert(v_ket_status_bekerja); 
										}
									}
									//end set status usia 23 jika anak sudah mencapai usia 23 ------
								}else
								{
									if (jdata.dtLalu.length==0)
									{
										$('#keterangan').val(getValue(jdata.dtThn['KET_FLAG_LAYAK']));
										$('#flag_layak').val('T');	 
										alert(getValue(jdata.dtThn['KET_FLAG_LAYAK']));
									}	 
								}
							}else
							{
								//gagal ----------------------------------------------------------
								$('#flag_layak').val('T');
								alert(jdata.msg);
							}
						}
				});//end ajax
			}
		}		 				 
	}
	
	function fl_js_UploadDok()
	{
	 	var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_nik_penerima = $('#beasiswa_nik_penerima').val();
		var v_nama_dokumen = $('#nama_dokumen').val();
		var v_no_urut_dokumen = $('#no_urut_dokumen').val();
		
		if (v_nama_dokumen=='')
		{
		 	alert('NAMA DOKUMEN KOSONG, HARAP MELENGKAPI DATA INPUT..!');
		}else
		{					 
			let params = 'p=pn5041.php&a=formreg&kode_klaim='+v_kode_klaim+'&nik_penerima='+v_nik_penerima+'&nama_dokumen='+v_nama_dokumen+'&no_urut_dokumen='+v_no_urut_dokumen+'&sender=pn5061_penetapanmanfaat_beasiswaentrypp82.php';
			NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5061_penetapanmanfaat_beasiswaentrypp82uploaddok.php?' + params,'upload_lampiran',550,400,'yes');
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
		
		if (v_nmfile != "" && v_url != "") 
		{
      		var html_download_file = '<a href="javascript:void(0)" onclick="fl_js_DownloadDok()"> | File: '+v_nmfile+'</a>';
			
    		$("#span_download_file").html(html_download_file);
		}									 
	}
	
	function fl_js_entry_detilbeasiswa(v_i, v_tahun, v_dtl_data)
	{
	 	var v_kode_klaim 	 = $('#kode_klaim').val();
		var v_kode_manfaat = $('#kode_manfaat').val();
		var v_no_urut 		 = $('#no_urut').val();
		var v_nik_penerima = $('#beasiswa_nik_penerima').val();
		var v_mid = '<?=$mid;?>';
		var v_task = $('#task').val();
		
		if (v_no_urut == '')
		{
		 	v_task_detil = "new";
		}else
		{
		 	if (v_task=="edit")
			{
			  v_task_detil = "edit";
			}else
			{
			  v_task_detil = "view";
			} 
		}
		
		var iteration = v_i;

		if(v_dtl_data == 'TUNDA')
		{
			var v_jenis 						= $('#dtl_jenis_dtlLalu'+iteration).val();
			var v_jenjang 					= $('#dtl_jenjang_dtlLalu'+iteration).val();
			var v_flag_terima 			= $('#dtl_flag_terima_dtlLalu'+iteration).val();
			var v_tingkat 					= $('#dtl_tingkat_dtlLalu'+iteration).val();
			var v_lembaga 					= $('#dtl_lembaga_dtlLalu'+iteration).val();
			var v_keterangan 				= $('#dtl_keterangan_dtlLalu'+iteration).val();
			var v_nom_manfaat 			= parseFloat(removeCommas($('#dtl_nom_manfaat_dtlLalu'+iteration).val()),2); 
			
			var v_nama_dokumen 			= $('#dtl_nama_dokumen_dtlLalu'+iteration).val();
			var v_nama_file_dokumen = $('#dtl_nama_file_dokumen_dtlLalu'+iteration).val();
			var v_no_urut_dokumen 	= $('#dtl_no_urut_dokumen_dtlLalu'+iteration).val();
			var v_url_dokumen 			= $('#dtl_url_dokumen_dtlLalu'+iteration).val();
			var v_flag_dok_lengkap 	= $('#dtl_flag_dok_lengkap_dtlLalu'+iteration).val();
			var v_flag_dok_lengkap 	= $('#dtl_flag_dok_lengkap_dtlLalu'+iteration).val();
		} else 
		{
			var v_jenis 						= $('#dtl_jenis'+iteration).val();
			var v_jenjang 					= $('#dtl_jenjang'+iteration).val();
			var v_flag_terima 			= $('#dtl_flag_terima'+iteration).val();
			var v_tingkat 					= $('#dtl_tingkat'+iteration).val();
			var v_lembaga 					= $('#dtl_lembaga'+iteration).val();
			var v_keterangan 				= $('#dtl_keterangan'+iteration).val();
			var v_nom_manfaat 			= parseFloat(removeCommas($('#dtl_nom_manfaat'+iteration).val()),2); 
			
			var v_nama_dokumen 			= $('#dtl_nama_dokumen'+iteration).val();
			var v_nama_file_dokumen = $('#dtl_nama_file_dokumen'+iteration).val();
			var v_no_urut_dokumen 	= $('#dtl_no_urut_dokumen'+iteration).val();
			var v_url_dokumen 			= $('#dtl_url_dokumen'+iteration).val();
			var v_flag_dok_lengkap 	= $('#dtl_flag_dok_lengkap'+iteration).val();
		}
		
			
		let params = 'task='+v_task_detil+'&kode_klaim='+v_kode_klaim+'&kode_manfaat='+v_kode_manfaat+'&no_urut='+v_no_urut+'&tahun='+v_tahun+'&task_detil='+v_task_detil+'&vi='+v_i+'&nik_penerima='+v_nik_penerima+'&mid='+v_mid+'&tmp_jenis='+v_jenis+'&tmp_jenjang='+v_jenjang+'&tmp_flag_terima='+v_flag_terima+'&tmp_tingkat='+v_tingkat+'&tmp_lembaga='+v_lembaga+'&tmp_keterangan='+v_keterangan+'&tmp_nom_manfaat='+v_nom_manfaat+'&tmp_nama_dokumen='+v_nama_dokumen+'&tmp_nama_file_dokumen='+v_nama_file_dokumen+'&tmp_no_urut_dokumen='+v_no_urut_dokumen+'&tmp_url_dokumen='+v_url_dokumen+'&tmp_flag_dok_lengkap='+v_flag_dok_lengkap+'&tmp_dtl_data='+v_dtl_data;
    	if (v_task_detil=="new" || v_task_detil=="edit")
		{
		 	showForm('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5061_penetapanmanfaat_beasiswaentrypp82detil.php?' + params,'',800,650,'no'); 
		}else
		{
		 	NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5061_penetapanmanfaat_beasiswaentrypp82detil.php?' + params,'',800,650,'no');		 
		}
	}
		
	function fl_js_setval_postentrydetilbeasiswa(
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
	){
	 	//v_jenis : BEASISWA	BEASISWA PENDIDIKAN
		//v_jenis : PELATIHAN	PELATIHAN KERJA
						
		var v_dtl_uraian = "";
		var v_ket_keterangan = "";
		
		if (v_jenis == "")
		{
		 	if (v_flag_terima=="T" && v_keterangan!=="")
			{
			 	v_ket_keterangan = "TIDAK MENERIMA MANFAAT BEASISWA ("+v_keterangan+")"; 
			}else
			{
			 	v_ket_keterangan = "";	 
			}
			v_dtl_uraian = v_ket_keterangan;  	 
		}else
		{		
			if (v_jenis == "BEASISWA")
			{
				//v_jenjang -----------------------
				//TK			TK/SEDERAJAT
				//SD			SD/SEDERAJAT
				//SMP			SLTP/SEDERAJAT
				//SMA			SLTA/SEDERAJAT
				//KULIAH	PERGURUAN TINGGI (S1)
        
				var v_ket_jenjang = "";
				if (v_jenjang == "TK")
				{
					v_ket_jenjang = "TK/SEDERAJAT"; 
				}else if (v_jenjang == "SD")
				{
					v_ket_jenjang = "SD/SEDERAJAT"; 
				}else if (v_jenjang == "SMP")
				{
					v_ket_jenjang = "SLTP/SEDERAJAT"; 
				}else if (v_jenjang == "SMA")
				{
					v_ket_jenjang = "SLTA/SEDERAJAT"; 
				}else if (v_jenjang == "KULIAH")
				{
					v_ket_jenjang = "PERGURUAN TINGGI (S1)"; 
				}
				
				if (v_keterangan=="")
				{
					v_ket_keterangan = ""; 
				}else
				{
					v_ket_keterangan = "("+v_keterangan+")";	 
				}			
				
				v_dtl_uraian = 'BEASISWA PENDIDIKAN UNTUK' + ' ' + v_ket_jenjang + ' TINGKAT/KELAS ' + v_tingkat + ' ' + v_ket_keterangan; 
  			}else
  			{
				if (v_lembaga=="")
				{
					v_ket_lembaga = ""; 
				}else
				{
					v_ket_lembaga = "LEMBAGA "+v_lembaga;	 
				}
	
				if (v_keterangan=="")
				{
					v_ket_keterangan = ""; 
				}else
				{
					v_ket_keterangan = "("+v_keterangan+")";	 
				}
							
				v_dtl_uraian = 'BEASISWA UNTUK PELATIHAN' + ' ' + v_jenjang + ' TINGKAT ' + v_tingkat + ' ' + v_ket_lembaga + ' ' + v_ket_keterangan; 	 
  			}
		}
		
		var iteration = v_i;

		if(v_dtl_data== 'TUNDA'){
			$('#dtl_uraian_dtlLalu'+iteration).val(v_dtl_uraian);
			$('#dtl_jenis_dtlLalu'+iteration).val(v_jenis);
			$('#dtl_jenjang_dtlLalu'+iteration).val(v_jenjang);
			$('#dtl_flag_terima_dtlLalu'+iteration).val(v_flag_terima);
			$('#dtl_tingkat_dtlLalu'+iteration).val(v_tingkat);
			$('#dtl_lembaga_dtlLalu'+iteration).val(v_lembaga);
			$('#dtl_keterangan_dtlLalu'+iteration).val(v_keterangan);
			$('#dtl_nom_manfaat_dtlLalu'+iteration).val(format_uang(v_nom_manfaat));	
			
			$('#dtl_nama_dokumen_dtlLalu'+iteration).val(v_nama_dokumen);
			$('#dtl_nama_file_dokumen_dtlLalu'+iteration).val(v_nama_file_dokumen);
			$('#dtl_no_urut_dokumen_dtlLalu'+iteration).val(v_no_urut_dokumen);
			$('#dtl_url_dokumen_dtlLalu'+iteration).val(v_url_dokumen);	
			$('#dtl_flag_dok_lengkap_dtlLalu'+iteration).val(v_flag_dok_lengkap);
		} else {
			$('#dtl_uraian'+iteration).val(v_dtl_uraian);
			$('#dtl_jenis'+iteration).val(v_jenis);
			$('#dtl_jenjang'+iteration).val(v_jenjang);
			$('#dtl_flag_terima'+iteration).val(v_flag_terima);
			$('#dtl_tingkat'+iteration).val(v_tingkat);
			$('#dtl_lembaga'+iteration).val(v_lembaga);
			$('#dtl_keterangan'+iteration).val(v_keterangan);
			$('#dtl_nom_manfaat'+iteration).val(format_uang(v_nom_manfaat));	
			
			$('#dtl_nama_dokumen'+iteration).val(v_nama_dokumen);
			$('#dtl_nama_file_dokumen'+iteration).val(v_nama_file_dokumen);
			$('#dtl_no_urut_dokumen'+iteration).val(v_no_urut_dokumen);
			$('#dtl_url_dokumen'+iteration).val(v_url_dokumen);	
			$('#dtl_flag_dok_lengkap'+iteration).val(v_flag_dok_lengkap);
		}
					
		fl_js_hitung_totmanfaat();	 
	}
	
	//hitung total manfaat ---------------------------------------------------------
	function fl_js_hitung_totmanfaat() 
	{
		var form = document.formreg;
		var n_nom_manfaat,n_tot_manfaat;
		var tbl = document.getElementById('tblThnBeasiswa');
		var lastRow = tbl.rows.length;
		var ln_dtl = parseFloat(document.getElementById('dtl_kounter_dtl').value);
		var ln_dtl_dtlLalu = parseFloat(document.getElementById('dtl_kounter_dtl_dtlLalu').value);
		// var iteration_dtlLalu = v_i;
		// var v_nom_manfaat_dtlLalu = v_nom_manfaat;
		// var n_nom_manfaat_dtlLalu = parseFloat(removeCommas(document.getElementById('dtl_nom_manfaat_dtlLalu0').value),2)
		// if (document.getElementById('dtl_nom_manfaat_dtlLalu'+iteration_dtlLalu))
		// {
			// console.log(ln_dtl_dtlLalu);
		// }
		
			
		n_tot_manfaat = 0;

		for (i=0; i<=parseFloat(ln_dtl_dtlLalu); i++) 
		{
    		//hitung total saldo ---------------------------------------------------
			if (document.getElementById('dtl_nom_manfaat_dtlLalu'+i))
    		{						
				n_nom_manfaat = parseFloat(removeCommas(document.getElementById('dtl_nom_manfaat_dtlLalu'+i).value),2);				
				// console.log(n_nom_manfaat);

				if (isNaN(n_nom_manfaat)||n_nom_manfaat=="")
				n_nom_manfaat = 0;
		
				n_tot_manfaat += parseFloat(n_nom_manfaat);
				document.getElementById('nom_biaya_disetujui').value = format_uang(n_tot_manfaat);					
    		}				
    	}

		for (i=0; i<=parseFloat(ln_dtl); i++) 
		{
    		//hitung total saldo ---------------------------------------------------
			if (document.getElementById('dtl_nom_manfaat'+i))
    		{						
				n_nom_manfaat = parseFloat(removeCommas(document.getElementById('dtl_nom_manfaat'+i).value),2);				
																		
				if (isNaN(n_nom_manfaat)||n_nom_manfaat=="")
				n_nom_manfaat = 0;
		
				n_tot_manfaat += parseFloat(n_nom_manfaat);
				document.getElementById('nom_biaya_disetujui').value = format_uang(n_tot_manfaat);					
    		}				
    	}
	}				
	//end hitung total manfaat ---------------------------------------------------
	
	function fjq_ajax_val_dosave_manfaat_beasiswa()
	{
    	var v_kode_klaim 						= $('#kode_klaim').val();	
		var v_kode_manfaat 					= $('#kode_manfaat').val();
		var v_kode_tipe_penerima 		= $('#kode_tipe_penerima').val();
		var v_nik_penerima 					= $('#beasiswa_nik_penerima').val();
		var v_nama_penerima 				= $('#nama_penerima').val();
		var v_tgl_pengajuan 				= $('#beasiswa_tgl_pengajuan').val();
		var v_kondisi_akhir 				= $('#beasiswa_kondisi_akhir').val();
		var v_tgl_kondisi_akhir 		= $('#beasiswa_tgl_kondisi_akhir').val();
		var v_nama_dokumen 					= $('#nama_dokumen').val();
		var v_nama_file_dokumen			= $('#nama_file_dokumen').val();
		var v_keterangan						= $('#keterangan').val();
		var v_flag_layak						= $('#flag_layak').val();
		
		if (v_kode_klaim=="" || v_kode_manfaat==""){
			alert('Kode Klaim/Kode Manfaat kosong, harap perhatikan data input...!!!');
		}else if(v_flag_layak!="Y"){
			alert('Tidak dapat disimpan, anak tidak layak mendapatkan beasiswa...!!!');		
		}else if(v_kode_tipe_penerima==""){
			alert('Tipe Penerima kosong, harap melengkapi data input...!!!');
			$('#kode_tipe_penerima').focus();
		}else if (v_nik_penerima==""){
			alert('NIK Anak kosong, harap melengkapi data input...!!!');
			$('#beasiswa_nik_penerima').focus();
		}else if (v_nama_penerima==""){
			alert('Nama Anak kosong, harap melengkapi data input...!!!');
			$('#nama_penerima').focus();
		}else if (v_tgl_pengajuan==""){
			alert('Tgl Pengajuan kosong, harap melengkapi data input...!!!');
			$('#beasiswa_tgl_pengajuan').focus();
		}else if (v_kondisi_akhir==""){
			alert('Kondisi Saat Pengajuan kosong, harap melengkapi data input...!!!');
			$('#beasiswa_kondisi_akhir').focus();									 
		}else if ((v_kondisi_akhir =="KA12" || v_kondisi_akhir =="KA14" || v_kondisi_akhir =="KA11" || v_kondisi_akhir =="KA19") && v_tgl_kondisi_akhir==""){
			alert('Tgl Kondisi Akhir (Sejak) kosong, harap melengkapi data input...!!!');
			$('#beasiswa_tgl_kondisi_akhir').focus();
		}else if ((v_kondisi_akhir =="KA12" || v_kondisi_akhir =="KA14" || v_kondisi_akhir =="KA11" || v_kondisi_akhir =="KA19") && v_nama_dokumen==""){
			alert('Nama Dokumen kelengkapan administrasi karena perubahan kondisi akhir kosong, harap melengkapi data input...!!!');
			$('#nama_dokumen').focus();
		}else if ((v_kondisi_akhir =="KA12" || v_kondisi_akhir =="KA14" || v_kondisi_akhir =="KA11" || v_kondisi_akhir =="KA19") && v_nama_file_dokumen==""){
			alert('Dokumen kelengkapan administrasi karena perubahan kondisi akhir belum diupload, harap melengkapi data input...!!!');
		$('#nama_dokumen').focus();
		}else if (v_keterangan==""){
			alert('Catatan masih kosong, harap melengkapi data input...!!!');
			$('#keterangan').focus();																	 
		}else
    	{
			document.formreg.tipe.value = 'SaveMnfBeasiswaPP82';
			preload(true);
			$.ajax({
				type: 'POST',
				url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5061_action.php?'+Math.random(),
				data: $('#formreg').serialize(),
				success: function(data) {
					preload(false);
					jdata = JSON.parse(data);
					if(jdata.ret == '0')
					{
						window.parent.Ext.notify.msg('Proses selesai, session dilanjutkan..', jdata.msg);
						document.formreg.tipe.value = '';
									
						var v_no_urut_ret = jdata.NO_URUT_RET; 
							
						//simpan rincian manfaat beasiswa berhasil, reload form ------------
						fl_js_reloadFormEntry('edit', v_kode_klaim, v_kode_manfaat, v_no_urut_ret);
					} else 
					{
						alert(jdata.msg);
					}
        		}
      		});
    	}					  				 
	}

	function fjq_ajax_val_dodelete_manfaat_beasiswa()
	{
    	var v_kode_klaim 		= $('#kode_klaim').val();	
		var v_kode_manfaat 	= $('#kode_manfaat').val();
		var v_no_urut 			= $('#no_urut').val();
		
		if (v_kode_klaim=="" || v_kode_manfaat=="" || v_no_urut=="")
		{
			alert('Kode Klaim/Kode Manfaat/No Urut kosong, harap perhatikan data input...!!!');												 
		}else
		{
			$.ajax({
				type: 'POST',
				url: "../ajax/pn5061_action.php?"+Math.random(),
				data: {
					tipe: 'fjq_ajax_val_dodelete_manfaat_rinci',
					v_kode_klaim: v_kode_klaim,
					v_kode_manfaat: v_kode_manfaat,
					v_no_urut: v_no_urut
  				},
  				success: function(data){
					try {
						jdata = JSON.parse(data);
						if (jdata.ret == 0) 
						{
							window.parent.Ext.notify.msg('Sukses', jdata.msg);
								fl_js_back2gridmanfaat();
						} else 
						{
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
    	}					  				 
	}
		
	function fl_js_reloadFormEntry(v_task, v_kode_klaim, v_kode_manfaat, v_no_urut_ret)
	{
	 	document.formreg.task.value = v_task;
		document.formreg.kode_klaim.value = v_kode_klaim;
		document.formreg.kode_manfaat.value = v_kode_manfaat;
		document.formreg.no_urut.value = v_no_urut_ret;

		try {
    		document.formreg.onsubmit();
    	} catch(e){}
    	document.formreg.submit();																	
	}						
</script>
			
<?php
include "../../includes/footer_app_nosql.php";
?>
