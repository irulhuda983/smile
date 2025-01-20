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
$gs_pagetitle 	 = "INFORMASI PENERIMA MANFAAT JP BERKALA";											 
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_user		 = $_SESSION["USER"];
$gs_kode_role		 = $_SESSION['regrole'];
$task 					 = $_POST["task"];
$editid 				 = $_POST['editid'];
$ls_kode_klaim 	 = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ls_kode_penerima_berkala = !isset($_POST['kode_penerima_berkala']) ? $_GET['kode_penerima_berkala'] : $_POST['kode_penerima_berkala'];
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
			<input type="hidden" id="kode_penerima_berkala" name="kode_penerima_berkala" value="<?=$ls_kode_penerima_berkala;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" >
          <div class="div-row">
            <div class="div-col" style="width:46%; max-height: 100%;">
            	<div class="div-row">
            		<div class="div-col" style="width:100%; max-height: 100%;">
                  <fieldset><legend>Detil Informasi Penerima Manfaat JP Berkala</legend>
          					</br>							
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Hub. Keluarga</label>
                      <input type="text" id="penerimajpbkl_nama_hubungan" name="penerimajpbkl_nama_hubungan" style="width:130px;" readonly class="disabled">
                      <input type="hidden" id="penerimajpbkl_kode_hubungan" name="penerimajpbkl_kode_hubungan">
                      <input type="text" id="penerimajpbkl_kode_penerima_berkala" name="penerimajpbkl_kode_penerima_berkala" style="width:30px;" readonly class="disabled">
                      <input type="text" id="penerimajpbkl_jenis_kelamin_ket" name="penerimajpbkl_jenis_kelamin_ket" style="width:67px;" readonly class="disabled">
                      <input type="hidden" id="penerimajpbkl_jenis_kelamin" name="penerimajpbkl_jenis_kelamin">
                    </div>																																														
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Nama Lengkap</label>
                    	<input type="text" id="penerimajpbkl_nama_lengkap" name="penerimajpbkl_nama_lengkap" style="width:245px;" readonly class="disabled">
                    </div>																																																	
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Tempat, Tgl Lahir</label>
                      <input type="text" id="penerimajpbkl_tempat_lahir" name="penerimajpbkl_tempat_lahir" style="width:170px;" readonly class="disabled">
                      <input type="text" id="penerimajpbkl_tgl_lahir" name="penerimajpbkl_tgl_lahir" style="width:65px;" readonly class="disabled">
                      <input type="hidden" id="penerimajpbkl_golongan_darah" name="penerimajpbkl_golongan_darah">
                      <input type="hidden" id="penerimajpbkl_kpj_tertanggung" name="penerimajpbkl_kpj_tertanggung">	
                      <input type="hidden" id="penerimajpbkl_status_kawin" name="penerimajpbkl_status_kawin">													
                    </div>																																																																																						
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">No. Identitas </label>
                    	<input type="text" id="penerimajpbkl_nomor_identitas" name="penerimajpbkl_nomor_identitas" style="width:210px;" readonly class="disabled">
                    </div>																																																																																							
                    <div class="clear"></div>	
                    
                    <br>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Alamat</label>
                    	<textarea cols="255" rows="1" style="width:245px;height:15px;background-color:#F5F5F5;" id="penerimajpbkl_alamat" name="penerimajpbkl_alamat" readonly onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea> 
                    </div>																																									
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">RT/RW - Kel</label>		 	    				
                      <input type="text" id="penerimajpbkl_rt" name="penerimajpbkl_rt" style="width:25px;" readonly class="disabled">
                      /
                      <input type="text" id="penerimajpbkl_rw" name="penerimajpbkl_rw" style="width:35px;" readonly class="disabled">
                      -
                      <input type="text" id="penerimajpbkl_nama_kelurahan" name="penerimajpbkl_nama_kelurahan" style="width:153px;" readonly class="disabled">
                      <input type="hidden" id="penerimajpbkl_kode_kelurahan" name="penerimajpbkl_kode_kelurahan">
                    </div>																																																	
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Kecamatan &nbsp;&nbsp;</label>		 	    				
                      <input type="text" id="penerimajpbkl_nama_kecamatan" name="penerimajpbkl_nama_kecamatan" style="width:220px;" readonly class="disabled">
                      <input type="hidden" id="penerimajpbkl_kode_kecamatan" name="penerimajpbkl_kode_kecamatan">
                    </div>																																																	
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
                      <input type="text" id="penerimajpbkl_nama_kabupaten" name="penerimajpbkl_nama_kabupaten" style="width:135px;" readonly class="disabled">		 
                      <input type="hidden" id="penerimajpbkl_kode_kabupaten" name="penerimajpbkl_kode_kabupaten">
                      <input type="hidden" id="penerimajpbkl_kode_propinsi" name="penerimajpbkl_kode_propinsi">
                      <input type="hidden" id="penerimajpbkl_nama_propinsi" name="penerimajpbkl_nama_propinsi">											
                      &nbsp;Pos
                      <input type="text" id="penerimajpbkl_kode_pos" name="penerimajpbkl_kode_pos" style="width:37px;" readonly class="disabled">
                    </div>																																																															
                    <div class="clear"></div>	
                    
                    </br>
          
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">No. Telp &nbsp;</label>		 	    				
                    	<input type="text" id="penerimajpbkl_telepon_area" name="penerimajpbkl_telepon_area" style="width:25px;" readonly class="disabled">
                      <input type="text" id="penerimajpbkl_telepon" name="penerimajpbkl_telepon" style="width:100px;" readonly class="disabled">
                      &nbsp;ext.
                      <input type="text" id="penerimajpbkl_telepon_ext" name="penerimajpbkl_telepon_ext" style="width:28px;" readonly class="disabled">						
                   	</div>																																																																																														
                    <div class="clear"></div>	
          
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
                    	<input type="text" id="penerimajpbkl_handphone" name="penerimajpbkl_handphone" style="width:195px;" readonly class="disabled">
                    	<input type="checkbox" id="penerimajpbkl_cb_is_verified_hp" name="penerimajpbkl_cb_is_verified_hp" class="cebox" disabled><i><font color="#009999">Verified</font></i>
          					</div>																																																																																														
                    <div class="clear"></div>

                    <div class="form-row_kiri">
                    <label style = "text-align:right;">&nbsp;</label>		 	    				
                    	<input type="checkbox" id="penerimajpbkl_status_reg_notifikasi" name="penerimajpbkl_status_reg_notifikasi" class="cebox" disabled><i><font color="#009999">Layanan Notifikasi JP Berkala</font></i>
          					</div>																																																																																														
                    <div class="clear"></div>
										          															
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Email &nbsp;</label>		 	    				
                    	<input type="text" id="penerimajpbkl_email" name="penerimajpbkl_email" style="width:195px;" readonly class="disabled">
                    	<input type="checkbox" id="penerimajpbkl_cb_is_verified_email" name="penerimajpbkl_cb_is_verified_email" class="cebox" disabled><i><font color="#009999">Verified</font></i>
          					</div>																																																																																														
                    <div class="clear"></div>	
          										
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
                      <input type="text" id="penerimajpbkl_npwp" name="penerimajpbkl_npwp" style="width:180px;" readonly class="disabled">
                      <input type="hidden" id="penerimajpbkl_no_urut_keluarga" name="penerimajpbkl_no_urut_keluarga">                					
                      <input type="hidden" id="penerimajpbkl_no_kartu_keluarga" name="penerimajpbkl_no_kartu_keluarga">                					
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
            		<div class="div-col" style="width:100%; max-height: 100%;">
          				<fieldset>
                    <legend>Manfaat Dibayarkan ke:</legend>
                      <div class="div-row">
                        <div class="div-col" style="width: 120px;text-align:left;">
                          <input id="penerimajpbkl_foto" name="penerimajpbkl_foto" type="image" align="center" src="../../images/nopic.png" style="height: 98px !important; width: 95px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                        </div>
                        <div class="div-col">
                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:80px;">Bank *</label> 
                            <input type="text" id="penerimajpbkl_nama_bank_penerima" name="penerimajpbkl_nama_bank_penerima" style="width:250px;" readonly class="disabled">
                            <input type="hidden" id="penerimajpbkl_kode_bank_penerima" name="penerimajpbkl_kode_bank_penerima">
                            <input type="hidden" id="penerimajpbkl_id_bank_penerima" name="penerimajpbkl_id_bank_penerima">
                            <input type="hidden" id="penerimajpbkl_metode_transfer" name="penerimajpbkl_metode_transfer">
                          </div>																																																	
                          <div class="clear"></div>
                          
                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:80px;">No Rekening *</label>
                            <input type="text" id="penerimajpbkl_no_rekening_penerima" name="penerimajpbkl_no_rekening_penerima" style="width:200px;" readonly class="disabled">
                            <input type="checkbox" id="cb_penerimajpbkl_valid_rekening" name="cb_penerimajpbkl_valid_rekening" disabled class="cebox"><i><font color="#009999">Valid</font></i>	
                            </div>																																																																																															
                          <div class="clear"></div>

                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:80px;">A/N *</label>
                            <input type="text" id="penerimajpbkl_nama_rekening_penerima_ws" name="penerimajpbkl_nama_rekening_penerima_ws" style="width:250px;" readonly class="disabled">
                            <input type="hidden" id="penerimajpbkl_nama_rekening_penerima" name="penerimajpbkl_nama_rekening_penerima">
                          </div>																																																																																															
                          <div class="clear"></div>

                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:80px;">Keterangan</label>
                          	<textarea cols="255" rows="1" style="width:250px;height:15px;background-color:#F5F5F5;" id="penerimajpbkl_keterangan" name="penerimajpbkl_keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>   					
                          </div>														
                          <div class="clear"></div>	
																																	                          
                          </br>
                          
                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:1px;">&nbsp;</label>	 	    				
                            <i><font color="#ff0000"><img src="../../images/warning.gif" border="0" alt="Tambah" align="absmiddle" style="height:16px;"/> &nbsp; <i><font color="#ff0000">Rekening sebaiknya valid untuk menghindari gagal transfer.</font></i>..! </font></i>
                            <input type="hidden" id="penerimajpbkl_status_valid_rekening_penerima" name="penerimajpbkl_status_valid_rekening_penerima">
                          </div>																																																																																																																																																												
                          <div class="clear"></div>		
          								</br>				
                        </div>
                      </div>
          				</fieldset>											 
          			</div>
          		</div>  
          
            	<div class="div-row">
            		<div class="div-col" style="width:100%; max-height: 100%;">
                  <fieldset><legend >Ditransfer dari:</legend>
                  	</br></br>
          					
                    <div class="form-row_kiri">
                    <label style = "text-align:center;">Bank *</label>	 	    				
                      <input type="text" id="penerimajpbkl_nama_bank_pembayar" name="penerimajpbkl_nama_bank_pembayar" style="width:260px;" readonly class="disabled">
											<input type="hidden" id="penerimajpbkl_kode_bank_pembayar" name="penerimajpbkl_kode_bank_pembayar">
											<input type="hidden" id="penerimajpbkl_id_bank_opg" name="penerimajpbkl_id_bank_opg">
                    </div>																																																																																																									
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">&nbsp;</label>	 	    				
                      <input type="hidden" id="penerimajpbkl_status_rekening_sentral" name="penerimajpbkl_status_rekening_sentral">
                      <input type="hidden" id="penerimajpbkl_kantor_rekening_sentral" name="penerimajpbkl_kantor_rekening_sentral">
                    </div>																																																																																																									
                    <div class="clear"></div>
          					
                  </fieldset>								 
          			</div>
          		</div>
          		
            	<div class="div-row">
            		<div class="div-col" style="width:98%; max-height: 100%;">
                  <div style="margin-top:2px;padding:10px 20px;border-radius: 5px!important;border:1px solid #ececec;text-align:left;width:93%;height:74px;text-align:center;">
                    </br>	
										<span id="span_info_cek_layanan"></span>  
                  </div>		      				 
          			</div>
          		</div>	
          						
            </div>		
          </div>																								
				</div>
				<!--end div_body-->
					
				<div id="div_footer" class="div-footer">
  				<span id="span_button_utama" style="display:block;">
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
		let kode_penerima_berkala = $('#kode_penerima_berkala').val();
		loadSelectedRecord(kode_klaim, kode_penerima_berkala, null);
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
	function loadSelectedRecord(v_kode_klaim, v_kode_penerima_berkala, fn)
	{
		if (v_kode_klaim == '' || v_kode_penerima_berkala == '') {
			return alert('Kode Klaim atau Kode Penerima tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatapenerimajpnberkaladetil',
				v_kode_klaim: v_kode_klaim,
				v_kode_penerima_berkala:v_kode_penerima_berkala
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - INFORMASI PENERIMA MANFAAT JP BERKALA');
						$("#span_page_title_right").html('KODE KLAIM : '+getValue(jdata.data.DATA['KODE_KLAIM']));
						
						//set value rincian manfaat ----------------------------------------
						var v_nama_hubungan = "";
						if (getValue(jdata.data.DATA['KODE_HUBUNGAN'])=="T")
            {
             	v_nama_hubungan = "TENAGA KERJA"; 
            }else if (getValue(jdata.data.DATA['KODE_HUBUNGAN'])=="I")
            {
             	v_nama_hubungan = "ISTRI"; 
            }else if (getValue(jdata.data.DATA['KODE_HUBUNGAN'])=="S")
            {
             	v_nama_hubungan = "SUAMI"; 
            }else if (getValue(jdata.data.DATA['KODE_HUBUNGAN'])=="A")
            {
             	v_nama_hubungan = "ANAK"; 
            }else if (getValue(jdata.data.DATA['KODE_HUBUNGAN'])=="O")
            {
             	v_nama_hubungan = "ORANG TUA TK"; 
            }else
            {
             	v_nama_hubungan = getValue(jdata.data.DATA['KODE_HUBUNGAN']); 	 
            }
						
						var v_jenis_kelamin_ket = "";
            if (getValue(jdata.data.DATA['JENIS_KELAMIN'])=="L")
            {
             	v_jenis_kelamin_ket = "LAKI-LAKI"; 
            }else if (getValue(jdata.data.DATA['JENIS_KELAMIN'])=="P")
            {
             	v_jenis_kelamin_ket = "PEREMPUAN"; 
            }else
            {
             	v_jenis_kelamin_ket = "UNIDENTIFIED"; 
            }
							
						$('#penerimajpbkl_nama_hubungan').val(v_nama_hubungan);
						$('#penerimajpbkl_kode_hubungan').val(getValue(jdata.data.DATA['KODE_HUBUNGAN']));
						$('#penerimajpbkl_kode_penerima_berkala').val(getValue(jdata.data.DATA['KODE_PENERIMA_BERKALA']));
						$('#penerimajpbkl_jenis_kelamin_ket').val(v_jenis_kelamin_ket);
						$('#penerimajpbkl_jenis_kelamin').val(getValue(jdata.data.DATA['JENIS_KELAMIN']));
						
						$('#penerimajpbkl_nama_lengkap').val(getValue(jdata.data.DATA['NAMA_LENGKAP']));
						$('#penerimajpbkl_tempat_lahir').val(getValue(jdata.data.DATA['TEMPAT_LAHIR']));
						$('#penerimajpbkl_tgl_lahir').val(getValue(jdata.data.DATA['TGL_LAHIR']));
						$('#penerimajpbkl_golongan_darah').val(getValue(jdata.data.DATA['GOLONGAN_DARAH']));
						$('#penerimajpbkl_kpj_tertanggung').val(getValue(jdata.data.DATA['KPJ_TERTANGGUNG']));
						$('#penerimajpbkl_status_kawin').val(getValue(jdata.data.DATA['STATUS_KAWIN']));
						
						$('#penerimajpbkl_nomor_identitas').val(getValue(jdata.data.DATA['NOMOR_IDENTITAS']));
						$('#penerimajpbkl_alamat').val(getValue(jdata.data.DATA['ALAMAT']));
						$('#penerimajpbkl_rt').val(getValue(jdata.data.DATA['RT']));
						$('#penerimajpbkl_rw').val(getValue(jdata.data.DATA['RW']));
						$('#penerimajpbkl_nama_kelurahan').val(getValue(jdata.data.DATA['NAMA_KELURAHAN']));
						$('#penerimajpbkl_kode_kelurahan').val(getValue(jdata.data.DATA['KODE_KELURAHAN']));
						$('#penerimajpbkl_nama_kecamatan').val(getValue(jdata.data.DATA['NAMA_KECAMATAN']));
						$('#penerimajpbkl_kode_kecamatan').val(getValue(jdata.data.DATA['KODE_KECAMATAN']));
						$('#penerimajpbkl_nama_kabupaten').val(getValue(jdata.data.DATA['NAMA_KABUPATEN']));
						$('#penerimajpbkl_kode_kabupaten').val(getValue(jdata.data.DATA['KODE_KABUPATEN']));
						$('#penerimajpbkl_kode_propinsi').val('');
						$('#penerimajpbkl_nama_propinsi').val('');
						$('#penerimajpbkl_kode_pos').val(getValue(jdata.data.DATA['KODE_POS']));
						
						$('#penerimajpbkl_telepon_area').val(getValue(jdata.data.DATA['TELEPON_AREA']));
						$('#penerimajpbkl_telepon').val(getValue(jdata.data.DATA['TELEPON']));
						$('#penerimajpbkl_telepon_ext').val(getValue(jdata.data.DATA['TELEPON_EXT']));
						$('#penerimajpbkl_handphone').val(getValue(jdata.data.DATA['HANDPHONE']));
						
						if (getValue(jdata.data.DATA['IS_VERIFIED_HP'])=="Y")
						{
						 	window.document.getElementById('penerimajpbkl_cb_is_verified_hp').checked = true; 
						}else
						{
						 	window.document.getElementById('penerimajpbkl_cb_is_verified_hp').checked = false;
						}

						if (getValue(jdata.data.DATA['STATUS_REG_NOTIFIKASI'])=="Y")
						{
						 	window.document.getElementById('penerimajpbkl_status_reg_notifikasi').checked = true; 
						}else
						{
						 	window.document.getElementById('penerimajpbkl_status_reg_notifikasi').checked = false;
						}
												
						$('#penerimajpbkl_email').val(getValue(jdata.data.DATA['EMAIL']));
						
						if (getValue(jdata.data.DATA['IS_VERIFIED_EMAIL'])=="Y")
						{
						 	window.document.getElementById('penerimajpbkl_cb_is_verified_email').checked = true; 
						}else
						{
						 	window.document.getElementById('penerimajpbkl_cb_is_verified_email').checked = false;
						}
												
						$('#penerimajpbkl_npwp').val(getValue(jdata.data.DATA['NPWP']));
						$('#penerimajpbkl_no_urut_keluarga').val(getValue(jdata.data.DATA['NO_URUT_KELUARGA']));
						$('#penerimajpbkl_no_kartu_keluarga').val(getValue(jdata.data.DATA['NO_KARTU_KELUARGA']));
						
						$('#penerimajpbkl_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#penerimajpbkl_nomor_identitas').val());
						
						$('#penerimajpbkl_nama_bank_penerima').val(getValue(jdata.data.DATA['BANK_PENERIMA']));
						$('#penerimajpbkl_kode_bank_penerima').val(getValue(jdata.data.DATA['KODE_BANK_PENERIMA']));
						$('#penerimajpbkl_id_bank_penerima').val(getValue(jdata.data.DATA['ID_BANK_PENERIMA']));
						$('#penerimajpbkl_metode_transfer').val(getValue(jdata.data.DATA['METODE_TRANSFER']));
						
						$('#penerimajpbkl_no_rekening_penerima').val(getValue(jdata.data.DATA['NO_REKENING_PENERIMA']));
						
						if (getValue(jdata.data.DATA['STATUS_VALID_REKENING_PENERIMA'])=="Y")
						{
						 	window.document.getElementById('cb_penerimajpbkl_valid_rekening').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_penerimajpbkl_valid_rekening').checked = false;
						}
						
						$('#penerimajpbkl_nama_rekening_penerima_ws').val(getValue(jdata.data.DATA['NAMA_REKENING_PENERIMA']));
						$('#penerimajpbkl_nama_rekening_penerima').val(getValue(jdata.data.DATA['NAMA_REKENING_PENERIMA']));
						$('#penerimajpbkl_keterangan').val(getValue(jdata.data.DATA['KETERANGAN']));
						$('#penerimajpbkl_status_valid_rekening_penerima').val(getValue(jdata.data.DATA['STATUS_VALID_REKENING_PENERIMA']));
						
						$('#penerimajpbkl_nama_bank_pembayar').val(getValue(jdata.data.DATA['NAMA_BANK_PEMBAYAR']));
						$('#penerimajpbkl_kode_bank_pembayar').val(getValue(jdata.data.DATA['KODE_BANK_PEMBAYAR']));
						$('#penerimajpbkl_id_bank_opg').val('');
						
						$('#penerimajpbkl_status_rekening_sentral').val(getValue(jdata.data.DATA['STATUS_REKENING_SENTRAL']));
						$('#penerimajpbkl_kantor_rekening_sentral').val(getValue(jdata.data.DATA['KANTOR_REKENING_SENTRAL']));
						
						if (getValue(jdata.data.DATA['STATUS_CEK_LAYANAN'])=="Y")
						{
						 	$("#span_info_cek_layanan").html('<font color="#009999"><b>Sudah dilakukan konfirmasi Layanan Notifikasi JP Berkala</i></b></font>'); 
						}else
						{
						 	$("#span_info_cek_layanan").html('<font color="#ff0000"><b>Belum dilakukan konfirmasi untuk Layanan Notifikasi JP Berkala</i></b></font></br></br><i><font color="#009999"><img src="../../images/warning.gif" border="0" alt="Tambah" align="absmiddle" style="height:16px;"/> &nbsp; <i>Harap konfirmasi ke penerima manfaat apakah bersedia mengikuti </br>layanan Notifikasi JP Berkala .! </font></i>');
						}	
												
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

