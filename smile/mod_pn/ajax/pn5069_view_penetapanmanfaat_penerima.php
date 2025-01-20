<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian penerima manfaat
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
$ls_kode_tipe_penerima = !isset($_POST['kode_tipe_penerima']) ? $_GET['kode_tipe_penerima'] : $_POST['kode_tipe_penerima'];
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
			<input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" >
            <div class="div-row" >
              <div class="div-col" style="width:46%; max-height: 100%;">
                <div class="div-row">
                  <div class="div-col" style="width: 100%">
                    <fieldset style="min-height:385px;">
                      <legend>Detil Informasi Penerima Manfaat</legend>
            					<div class="form-row_kiri">
                      <label  style = "text-align:right;">Tipe Penerima &nbsp;</label>
                        <input type="hidden" id="penerima_kode_tipe_penerima" name="penerima_kode_tipe_penerima" readonly class="disabled">
                        <input type="text" id="penerima_nama_tipe_penerima" name="penerima_nama_tipe_penerima" style="width:220px;" readonly class="disabled">                					
                      </div>
                      <div class="clear"></div>
            
                			<span id="span_penerima_kode_hubungan" style="display:none;">
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Ahli Waris</label>
													<input type="hidden" id="penerima_kode_hubungan" name="penerima_kode_hubungan" readonly class="disabled">
                        	<input type="text" id="penerima_nama_hubungan" name="penerima_nama_hubungan" style="width:220px;" readonly class="disabled">
                    		</div>																																									
                      	<div class="clear"></div>
                			</span>					
            					
            					<span id="span_penerima_kode_hubungan_lain" style="display:none;">
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Hubungan Lainnya</label>		 	    				
                          <input type="text" id="penerima_ket_hubungan_lainnya" name="penerima_ket_hubungan_lainnya" style="width:220px;" readonly class="disabled">	
                        </div>																																									
                      	<div class="clear"></div>
            					</span>
            					
            					</br>
            					
            					<span id="span_penerima_nomor_identitas" style="display:none;">			
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No. Identitas </label>
													<input type="text" id="penerima_jenis_identitas" name="penerima_jenis_identitas" style="width:60px;" readonly class="disabled">
													<input type="text" id="penerima_nomor_identitas" name="penerima_nomor_identitas" style="width:130px;" readonly class="disabled">
                          
                          <input type="hidden" id="penerima_status_valid_identitas" name="penerima_status_valid_identitas">
                          <input type="checkbox" id="cb_penerima_status_valid_identitas" name="cb_penerima_status_valid_identitas" disabled class="cebox"><i>Valid</i>
                          
                          <input type="hidden" id="penerima_tempat_lahir" name="penerima_tempat_lahir">
                          <input type="hidden" id="penerima_tgl_lahir" name="penerima_tgl_lahir">
                          <input type="hidden" id="penerima_jenis_kelamin" name="penerima_jenis_kelamin">	
                          <input type="hidden" id="penerima_golongan_darah" name="penerima_golongan_darah">
                        </div>																																									
                        <div class="clear"></div>
                      </span>
            
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Nama Penerima *</label>
                  			<input type="text" id="penerima_nama_penerima" name="penerima_nama_penerima" style="width:220px;" readonly class="disabled">
            						<input type="hidden" id="penerima_nama_pemohon" name="penerima_nama_pemohon">	
                      </div>																																														
                    	<div class="clear"></div>
            					
            					<div class="form-row_kiri">
                      <label style = "text-align:right;">Alamat *</label>
                  			<textarea cols="255" rows="1" style="width:220px;height:18px;background-color:#f5f5f5;" id="penerima_alamat" name="penerima_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea> 
            					</div>																																									
                    	<div class="clear"></div>
            					
            					<div class="form-row_kiri">
                      <label style = "text-align:right;">RT/RW - Kelurahan</label>		 	    				
                        <input type="text" id="penerima_rt" name="penerima_rt" style="width:20px;" readonly class="disabled">
                        /
                        <input type="text" id="penerima_rw" name="penerima_rw" style="width:30px;" readonly class="disabled">		
												-
												<input type="hidden" id="penerima_kode_kelurahan" name="penerima_kode_kelurahan" readonly class="disabled">
                        <input type="text" id="penerima_nama_kelurahan" name="penerima_nama_kelurahan" style="width:138px;" readonly class="disabled">	          
            					</div>																																																	
                      <div class="clear"></div>
            
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Kecamatan &nbsp;</label>		 	    				
            						<input type="hidden" id="penerima_kode_kecamatan" name="penerima_kode_kecamatan">
                        <input type="text" id="penerima_nama_kecamatan" name="penerima_nama_kecamatan" style="width:220px;" readonly class="disabled">
                      </div>																																																		
                      <div class="clear"></div>
            					
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
                        <input type="hidden" id="penerima_kode_kabupaten" name="penerima_kode_kabupaten">
                        <input type="text" id="penerima_nama_kabupaten" name="penerima_nama_kabupaten" style="width:200px;" readonly class="disabled">
                        <input type="hidden" id="penerima_kode_propinsi" name="penerima_kode_propinsi">
                        <input type="hidden" id="penerima_nama_propinsi" name="penerima_nama_propinsi">											
                      </div>																																																																
                      <div class="clear"></div>				

            					<div class="form-row_kiri">
                      <label style = "text-align:right;">Kode Pos</label>
            						<input type="text" id="penerima_kode_pos" name="penerima_kode_pos" style="width:150px;" readonly class="disabled">		          
            					</div>																																																	
                      <div class="clear"></div>
											                      
                      </br>		
            					
            					<div class="form-row_kiri">
                      <label style = "text-align:right;">Email &nbsp;</label>		 	    				
                      	<input type="text" id="penerima_email" name="penerima_email" style="width:220px;" readonly class="disabled">
                      </div>																																																																																															
                      <div class="clear"></div>
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">No. Telp</label>	    				
                        <input type="text" id="penerima_telepon_area" name="penerima_telepon_area" style="width:33px;" readonly class="disabled">
                        <input type="text" id="penerima_telepon" name="penerima_telepon" style="width:100px;" readonly class="disabled">
                        &nbsp;ext.
                        <input type="text" id="penerima_telepon_ext" name="penerima_telepon_ext" style="width:45px;" readonly class="disabled">					
                      </div>																																															
                      <div class="clear"></div>
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
                      	<input type="text" id="penerima_handphone" name="penerima_handphone" style="width:160px;" readonly class="disabled">
                      	<input type="checkbox" id="penerima_cb_is_verified_hp" name="penerima_cb_is_verified_hp" class="cebox" disabled><i><font color="#009999">Verified</font></i>
            					</div>																																																																																															
                      <div class="clear"></div>
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
                        <input type="text" id="penerima_npwp" name="penerima_npwp" style="width:200px;" readonly class="disabled">
                        <input type="hidden" id="penerima_npwp_old" name="penerima_npwp_old">
                      </div>
                      <div class="clear"></div>											
                      															
            				</fieldset>
            			</div>
            		</div>						 
            	</div>
            	
            	<div class="div-col" style="width:1%; max-height: 100%;">
            	</div>
            	
              <div class="div-col-right" style="width:53%; max-height: 100%;">
            		<div class="div-row">
                  <div class="div-col" style="width: 100%">
            				<fieldset style="width:95%;min-height:243px;"><legend>Manfaat Dibayarkan ke:</legend>
            					<div class="div-row">
            						<div class="div-col" style="width: 130px">
                          <input id="penerima_foto" name="penerima_foto" onclick="showfotojmo();" type="image" align="center" src="../../images/nopic.png" style="height: 98px !important; width: 95px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>					
                        </div>
            						<div class="div-col">
              						<div class="form-row_kiri">
                          <label style = "text-align:left;width:80px;">Mekanisme*</label>
														<input type="text" id="penerima_nama_cara_bayar" name="penerima_nama_cara_bayar" readonly style="width:230px;" readonly class="disabled">
														<input type="hidden" id="penerima_kode_cara_bayar" name="penerima_kode_cara_bayar">
                            <input type="hidden" id="penerima_kode_cara_bayar_old" name="penerima_kode_cara_bayar_old">
                          	<input type="hidden" id="penerima_is_verified_hp" name="penerima_is_verified_hp">	
            								<input type="hidden" id="penerima_tgl_verified_hp" name="penerima_tgl_verified_hp">
            								<input type="hidden" id="penerima_petugas_verified_hp" name="penerima_petugas_verified_hp">
            							</div>
                          <div class="clear"></div>
            									 	
                          <span id="span_rekening" style="display:none;">									 
                            <div class="form-row_kiri">
                            <label style = "text-align:left;width:80px;">Bank *</label> 
                              <input type="text" id="penerima_nama_bank_penerima" name="penerima_nama_bank_penerima" style="width:230px;" readonly class="disabled">
                              <input type="hidden" id="penerima_kode_bank_penerima" name="penerima_kode_bank_penerima">
                              <input type="hidden" id="penerima_id_bank_penerima" name="penerima_id_bank_penerima">
                              <input type="hidden" id="penerima_metode_transfer" name="penerima_metode_transfer">
                            </div>																																																	
                            <div class="clear"></div>
                            
                            <div class="form-row_kiri">
                            <label style = "text-align:left;width:80px;">No Rekening *</label>
                              <input type="text" id="penerima_no_rekening_penerima" name="penerima_no_rekening_penerima" style="width:180px;" readonly class="disabled">
                              <input type="checkbox" id="cb_penerima_valid_rekening" name="cb_penerima_valid_rekening" disabled class="cebox"><i><font color="#009999">Valid</font></i>	
                            </div>																																																																																															
                            <div class="clear"></div>
                            
                            <div class="form-row_kiri">
                            <label style = "text-align:left;width:80px;">A/N *</label>
                              <input type="text" id="penerima_nama_rekening_penerima_ws" name="penerima_nama_rekening_penerima_ws" style="width:230px;" readonly class="disabled">
                              <input type="hidden" id="penerima_nama_rekening_penerima" name="penerima_nama_rekening_penerima">
                            	<input type="hidden" id="penerima_status_valid_rekening_penerima" name="penerima_status_valid_rekening_penerima">
                            </div>																																																
                            <div class="clear"></div>
                            
                            <div class="form-row_kiri">
                            <label style = "text-align:left;width:80px;">Keterangan &nbsp;</label>
                            	<textarea cols="255" rows="1" style="width:230px;height:18px;background-color:#f5f5f5;" id="penerima_keterangan" name="penerima_keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>   					
                            </div>														
                            <div class="clear"></div>
																									
                            </br>           
                          </span>																 
            						</div>	 
            					</div>
											<div class="div-row" style="text-align:center;"> 
												</br></br>
												<span id="span_note_valid_rek" style="text-align:center;"></span>	 
											</div>												
            				</fieldset>	 
            			</div>
            		</div>	
            		
            		<div class="div-row">
                  <div class="div-col" style="width: 100%">
                    <fieldset style="width:95%;min-height:126px;"><legend>Ditransfer dari</legend>
                      </br></br>
                      <div class="form-row_kiri">
                      <label style = "text-align:center;">Bank *</label>
												<input type="text" id="penerima_nama_bank_pembayar" name="penerima_nama_bank_pembayar" style="width:280px;" readonly class="disabled">		 	
												<input type="hidden" id="penerima_kode_bank_pembayar" name="penerima_kode_bank_pembayar"> 	    				
                        <input type="hidden" id="penerima_id_bank_opg" name="penerima_id_bank_opg">
            					</div>																																																																																																									
                      <div class="clear"></div>
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">&nbsp;</label>
                      	<input type="checkbox" id="penerima_cb_status_rekening_sentral" name="penerima_cb_status_rekening_sentral" class="cebox" disabled><i><font color="#009999">Sentralisasi Rekening</font></i>										 	    				
                        <input type="hidden" id="penerima_status_rekening_sentral" name="penerima_status_rekening_sentral">
                        <input type="hidden" id="penerima_kantor_rekening_sentral" name="penerima_kantor_rekening_sentral">
                      </div>																																																																																																									
                      <div class="clear"></div>
                    </fieldset>			
            			</div>
            		</div>	
								
            	</div>
            </div>																									
				</div>
				<!--end div_body-->
					
				<div id="div_footer" class="div-footer">
  				<span id="span_button_utama" style="display:none;">
            <div style="padding-top:6px; width:99%;">
  						<div class="div-footer-content">
  							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
  							<li style="margin-left:15px;">Klik tombol <font color="#ff0000"> <b>X</b> </font> pada pojok kanan atas untuk menutup form dan kembali ke menu utama.</li>
  						</div>
            </div>
  				</span>				
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
		let kode_tipe_penerima = $('#kode_tipe_penerima').val();
		loadSelectedRecord(kode_klaim, kode_tipe_penerima, null);
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
	function loadSelectedRecord(v_kode_klaim, v_kode_tipe_penerima, fn)
	{
		if (v_kode_klaim == '' || v_kode_tipe_penerima == '') {
			return alert('Kode Klaim atau Kode Tipe Penerima tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatapenerimamanfaatdetil',
				v_kode_klaim: v_kode_klaim,
				v_kode_tipe_penerima:v_kode_tipe_penerima
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - INFORMASI PENERIMA MANFAAT');
						$("#span_page_title_right").html('KODE KLAIM : '+getValue(jdata.data.DATA['KODE_KLAIM']));
						
						//set value rincian manfaat ----------------------------------------
						$('#penerima_kode_tipe_penerima').val(getValue(jdata.data.DATA['KODE_TIPE_PENERIMA']));
						$('#penerima_nama_tipe_penerima').val(getValue(jdata.data.DATA['NAMA_TIPE_PENERIMA']));
						
						$('#penerima_kode_hubungan').val(getValue(jdata.data.DATA['KODE_HUBUNGAN']));
						$('#penerima_nama_hubungan').val(getValue(jdata.data.DATA['NAMA_HUBUNGAN']));
						$('#penerima_ket_hubungan_lainnya').val(getValue(jdata.data.DATA['KET_HUBUNGAN_LAINNYA']));
						
						$('#penerima_jenis_identitas').val(getValue(jdata.data.DATA['JENIS_IDENTITAS']));
						$('#penerima_nomor_identitas').val(getValue(jdata.data.DATA['NOMOR_IDENTITAS']));
						$('#penerima_status_valid_identitas').val(getValue(jdata.data.DATA['STATUS_VALID_IDENTITAS']));
						if (getValue(jdata.data.DATA['STATUS_VALID_IDENTITAS'])=="Y")
						{
						 	window.document.getElementById('cb_penerima_status_valid_identitas').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_penerima_status_valid_identitas').checked = false;
						}
						$('#penerima_tempat_lahir').val(getValue(jdata.data.DATA['TEMPAT_LAHIR']));
						$('#penerima_tgl_lahir').val(getValue(jdata.data.DATA['TGL_LAHIR']));
						$('#penerima_jenis_kelamin').val(getValue(jdata.data.DATA['JENIS_KELAMIN']));
						$('#penerima_golongan_darah').val('');
						
						$('#penerima_nama_penerima').val(getValue(jdata.data.DATA['NAMA_PENERIMA']));
						$('#penerima_nama_pemohon').val(getValue(jdata.data.DATA['NAMA_PEMOHON']));
						
						$('#penerima_alamat').val(getValue(jdata.data.DATA['ALAMAT']));
						$('#penerima_rt').val(getValue(jdata.data.DATA['RT']));	
						$('#penerima_rw').val(getValue(jdata.data.DATA['RW']));	
						$('#penerima_kode_pos').val(getValue(jdata.data.DATA['KODE_POS']));	
						$('#penerima_kode_kelurahan').val(getValue(jdata.data.DATA['KODE_KELURAHAN']));	
						$('#penerima_nama_kelurahan').val(getValue(jdata.data.DATA['NAMA_KELURAHAN']));	
						$('#penerima_kode_kecamatan').val(getValue(jdata.data.DATA['KODE_KECAMATAN']));	
						$('#penerima_nama_kecamatan').val(getValue(jdata.data.DATA['NAMA_KECAMATAN']));	
						$('#penerima_kode_kabupaten').val(getValue(jdata.data.DATA['KODE_KABUPATEN']));	
						$('#penerima_nama_kabupaten').val(getValue(jdata.data.DATA['NAMA_KABUPATEN']));	
						$('#penerima_kode_propinsi').val('');	
						$('#penerima_nama_propinsi').val('');	
						$('#penerima_email').val(getValue(jdata.data.DATA['EMAIL']));	
						$('#penerima_telepon_area').val(getValue(jdata.data.DATA['TELEPON_AREA']));	
						$('#penerima_telepon').val(getValue(jdata.data.DATA['TELEPON']));	
						$('#penerima_telepon_ext').val(getValue(jdata.data.DATA['TELEPON_EXT']));	
						$('#penerima_handphone').val(getValue(jdata.data.DATA['HANDPHONE']));
						if (getValue(jdata.data.DATA['IS_VERIFIED_HP'])=="Y")
						{
						 	window.document.getElementById('penerima_cb_is_verified_hp').checked = true; 
						}else
						{
						 	window.document.getElementById('penerima_cb_is_verified_hp').checked = false;
						}						
							
						$('#penerima_npwp').val(getValue(jdata.data.DATA['NPWP']));	
						$('#penerima_npwp_old').val(getValue(jdata.data.DATA['NPWP']));	
						$('#penerima_keterangan').val(getValue(jdata.data.DATA['KETERANGAN']));								
						if (getValue(jdata.data.DATA['KANAL_PELAYANAN'])=="40")
						{
						$('#penerima_foto').attr('src','<?= "../../mod_pn/ajax/pngetfotojmo.php?dataid=".$ls_kode_klaim ?>');		
						}else{
						$('#penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#penerima_nomor_identitas').val());	
						}
						$('#penerima_nama_cara_bayar').val(getValue(jdata.data.DATA['NAMA_CARA_BAYAR']));
						$('#penerima_kode_cara_bayar').val(getValue(jdata.data.DATA['KODE_CARA_BAYAR']));
						$('#penerima_kode_cara_bayar_old').val(getValue(jdata.data.DATA['KODE_CARA_BAYAR']));
						$('#penerima_is_verified_hp').val(getValue(jdata.data.DATA['IS_VERIFIED_HP']));
						$('#penerima_tgl_verified_hp').val(getValue(jdata.data.DATA['TGL_VERIFIED_HP']));
						$('#penerima_petugas_verified_hp').val(getValue(jdata.data.DATA['PETUGAS_VERIFIED_HP']));
						
						$('#penerima_nama_bank_penerima').val(getValue(jdata.data.DATA['BANK_PENERIMA']));
						$('#penerima_kode_bank_penerima').val(getValue(jdata.data.DATA['KODE_BANK_PENERIMA']));
						$('#penerima_id_bank_penerima').val(getValue(jdata.data.DATA['ID_BANK_PENERIMA']));
						$('#penerima_metode_transfer').val(getValue(jdata.data.DATA['METODE_TRANSFER']));
						$('#penerima_no_rekening_penerima').val(getValue(jdata.data.DATA['NO_REKENING_PENERIMA']));
						if (getValue(jdata.data.DATA['STATUS_VALID_REKENING_PENERIMA'])=="Y")
						{
						 	window.document.getElementById('cb_penerima_valid_rekening').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_penerima_valid_rekening').checked = false;
						}
						
						$('#penerima_nama_rekening_penerima_ws').val(getValue(jdata.data.DATA['NAMA_REKENING_PENERIMA']));
						$('#penerima_nama_rekening_penerima').val(getValue(jdata.data.DATA['NAMA_REKENING_PENERIMA']));
						$('#penerima_status_valid_rekening_penerima').val(getValue(jdata.data.DATA['STATUS_VALID_REKENING_PENERIMA']));		
						
						$('#penerima_nama_bank_pembayar').val(getValue(jdata.data.DATA['NAMA_BANK_PEMBAYAR']));	
						$('#penerima_kode_bank_pembayar').val(getValue(jdata.data.DATA['KODE_BANK_PEMBAYAR']));	
						$('#penerima_id_bank_opg').val('');	
						$('#penerima_status_rekening_sentral').val(getValue(jdata.data.DATA['STATUS_REKENING_SENTRAL']));	
						$('#penerima_kantor_rekening_sentral').val(getValue(jdata.data.DATA['KANTOR_REKENING_SENTRAL']));	
						
						if (getValue(jdata.data.DATA['KODE_CARA_BAYAR'])=="V")
						{
						 	$("#span_note_valid_rek").html('<i><font color="#ff0000"><img src="../../images/warning.gif" border="0" alt="Note" align="absmiddle" style="height:16px;"/>&nbsp;Nomor VA dikirimkan via handphone. Handphone sebaiknya sudah terverifikasi..!</font></i>'); 
						}else
						{
						 	$("#span_note_valid_rek").html('<i><font color="#ff0000"><img src="../../images/warning.gif" border="0" alt="Note" align="absmiddle" style="height:16px;"/>&nbsp;Rekening sebaiknya valid untuk menghindari gagal transfer..!</font></i>');
						}						

						if (getValue(jdata.data.DATA['STATUS_REKENING_SENTRAL'])=="Y")
						{
						 	window.document.getElementById('penerima_cb_status_rekening_sentral').checked = true; 
						}else
						{
						 	window.document.getElementById('penerima_cb_status_rekening_sentral').checked = false;
						}
																		
						//end set value rincian manfaat ------------------------------------
						
						fl_js_span_penerima_kode_hubungan();
						fl_js_span_penerima_nomor_identitas();
						fl_js_span_penerima_carabayar();
						fl_js_penerima_kode_hubungan();
						
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

<script type="text/javascript">	
  function fl_js_span_penerima_kode_hubungan() 
  {      
    var	v_kode_tipe_penerima = document.getElementById('penerima_kode_tipe_penerima').value;
  	
  	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
    {
      window.document.getElementById("span_penerima_kode_hubungan").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_penerima_kode_hubungan").style.display = 'none';
    }
  }		
		
  function fl_js_span_penerima_nomor_identitas() 
  { 
      var	v_kode_tipe_penerima = document.getElementById('penerima_kode_tipe_penerima').value;
			var	v_jenis_identitas 	 = document.getElementById('penerima_jenis_identitas').value;
					
    	if (v_kode_tipe_penerima =="PR" || v_kode_tipe_penerima =="FK" || v_kode_tipe_penerima =="TG" || v_kode_tipe_penerima =="PP") //perusahaan/faskes
      {
        window.document.getElementById("span_penerima_nomor_identitas").style.display = 'none';					
      }else
      {
       	window.document.getElementById("span_penerima_nomor_identitas").style.display = 'block';	   
      }
  }
	
  function fl_js_span_penerima_carabayar()
  {
    var	v_kode_cara_bayar = window.document.getElementById('penerima_kode_cara_bayar').value;
    
    if (v_kode_cara_bayar =="V") //va debet --------------------------
    {
      window.document.getElementById("span_rekening").style.display = 'none';				
    }else
    {
      window.document.getElementById("span_rekening").style.display = 'block';
    }
  }
							
  function fl_js_penerima_kode_hubungan() 
  {      
    var	v_kode_hubungan = document.getElementById('penerima_kode_hubungan').value;
			
  	if (v_kode_hubungan =="L") //lain-lain ----------------------------
    {
      window.document.getElementById("span_penerima_kode_hubungan_lain").style.display = 'block';	
    }else
    {
      window.document.getElementById("span_penerima_kode_hubungan_lain").style.display = 'none';	
    }
  }
  function showfotojmo()
	{
    	window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pngetfotojmo.php?dataid=<?=$ls_kode_klaim;?>');
    }		
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

