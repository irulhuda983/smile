<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian ahli waris jp
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 = "form";
$gs_kodeform 	 	 = "PN5030";
$chId 	 	 			 = "SMILE";
$gs_pagetitle 	 = "INFORMASI AHLI WARIS";											 
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_user		 = $_SESSION["USER"];
$gs_kode_role		 = $_SESSION['regrole'];
$task 					 = $_POST["task"];
$editid 				 = $_POST['editid'];
$ls_kode_klaim 	 = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ln_no_urut_keluarga = !isset($_POST['no_urut_keluarga']) ? $_GET['no_urut_keluarga'] : $_POST['no_urut_keluarga'];
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
			<input type="hidden" id="no_urut_keluarga" name="no_urut_keluarga" value="<?=$ln_no_urut_keluarga;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" >
          <div class="div-row" >
          <div class="div-col" style="width:46%; max-height: 100%;">
            <div class="div-row">
              <div class="div-col" style="width: 100%">
                <fieldset>
                <legend><span id="span_ket_legend1">Detil Informasi Ahli Waris</legend>
                  <div class="clear"></div>
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Hub. Keluarga *</label>		 	    				
                    <input type="hidden" id="ahliwaris_kode_hubungan" name="ahliwaris_kode_hubungan">
                    <input type="text" id="ahliwaris_nama_hubungan" name="ahliwaris_nama_hubungan" style="width:193px;" readonly class="disabled">
										<input type="text" id="ahliwaris_kode_penerima_berkala" name="ahliwaris_kode_penerima_berkala" style="width:29px;" readonly class="disabled">
                  	<input type="hidden" id="ahliwaris_no_urut_keluarga" name="ahliwaris_no_urut_keluarga" style="width:200px;" readonly class="disabled" > 
									</div>																																														
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Nama Lengkap *</label>
                    <input type="text" id="ahliwaris_nama_lengkap" name="ahliwaris_nama_lengkap" style="width:230px;" readonly class="disabled">
                  </div>																																																	
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Tempat, Tgl Lahir *</label>
                    <input type="text" id="ahliwaris_tempat_lahir" name="ahliwaris_tempat_lahir" style="width:151px;" readonly class="disabled">
                    <input type="text" id="ahliwaris_tgl_lahir" name="ahliwaris_tgl_lahir" style="width:70px;" readonly class="disabled">
                  </div>																																																																																						
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Jenis Kelamin *</label>
                    <input type="hidden" id="ahliwaris_jenis_kelamin" name="ahliwaris_jenis_kelamin">
                    <input type="text" id="ahliwaris_nama_jenis_kelamin" name="ahliwaris_nama_jenis_kelamin" style="width:210px;" readonly class="disabled">
										<input type="hidden" id="ahliwaris_golongan_darah" name="ahliwaris_golongan_darah">
                    <input type="hidden" id="ahliwaris_kpj_tertanggung" name="ahliwaris_kpj_tertanggung">														
                  </div>																																																																																						
                  <div class="clear"></div>
                  
                  </br>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Alamat *</label>
                  	<textarea cols="255" rows="1" style="width:230px;height:18px;background-color:#F5F5F5;" id="ahliwaris_alamat" name="ahliwaris_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea> 
                  </div>																																									
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">RT/RW - Kelurahan &nbsp;</label>		 	    				
                    <input type="text" id="ahliwaris_rt" name="ahliwaris_rt" style="width:25px;" readonly class="disabled">
                    /
                    <input type="text" id="ahliwaris_rw" name="ahliwaris_rw" style="width:30px;" readonly class="disabled">
                    &nbsp; - &nbsp;
                    <input type="text" id="ahliwaris_nama_kelurahan" name="ahliwaris_nama_kelurahan" style="width:131px;" readonly class="disabled">
                    <input type="hidden" id="ahliwaris_kode_kelurahan" name="ahliwaris_kode_kelurahan">							
                  </div>																																																	
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Kecamatan &nbsp;</label>		 	    				
                    <input type="text" id="ahliwaris_nama_kecamatan" name="ahliwaris_nama_kecamatan" style="width:230px;" readonly class="disabled">
                    <input type="hidden" id="ahliwaris_kode_kecamatan" name="ahliwaris_kode_kecamatan">
                  </div>																																																					
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
                    <input type="text" id="ahliwaris_nama_kabupaten" name="ahliwaris_nama_kabupaten" style="width:230px;" readonly class="disabled">		 
                    <input type="hidden" id="ahliwaris_kode_kabupaten" name="ahliwaris_kode_kabupaten">
                    <input type="hidden" id="ahliwaris_kode_propinsi" name="ahliwaris_kode_propinsi">
                    <input type="hidden" id="ahliwaris_nama_propinsi" name="ahliwaris_nama_propinsi">											
                  </div>																																																															
                  <div class="clear"></div>	

                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Kode Pos &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
                    <input type="text" id="ahliwaris_kode_pos" name="ahliwaris_kode_pos" style="width:210px;" readonly class="disabled">
                  </div>																																																	
                  <div class="clear"></div>
									                  
                  </br>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">No. Telp</label>	    				
                    <input type="text" id="ahliwaris_telepon_area" name="ahliwaris_telepon_area" style="width:25px;" readonly class="disabled">
                    <input type="text" id="ahliwaris_telepon" name="ahliwaris_telepon" style="width:113px;" readonly class="disabled">
                    &nbsp;ext.
                    <input type="text" id="ahliwaris_telepon_ext" name="ahliwaris_telepon_ext" style="width:30px;" readonly class="disabled"> 						
                  </div>																																															
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
                  	<input type="text" id="ahliwaris_handphone" name="ahliwaris_handphone" style="width:230px;" readonly class="disabled">
                  </div>																																																																																														
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
                  	<input type="text" id="ahliwaris_email" name="ahliwaris_email" style="width:230px;" readonly class="disabled">
                  </div>																																																																																														
                  <div class="clear"></div>			
																																																		
                </fieldset>	
              </div>
            </div>
          </div>
        	
          <div class="div-col" style="width:1%;">
          </div>
          
        	<div class="div-col-right" style="width:53%;">
            <div class="div-row">
              <div class="div-col" style="width: 100%">
                <fieldset style="min-height:200px;">
                <legend>Informasi Lainnya</legend>
									</br>								
                  <div class="div-row">
                    <div class="div-col" style="width: 110px">
                      <input id="ahliwaris_foto" name="ahliwaris_foto" type="image" align="center" src="../../images/nopic.png" style="height: 98px !important; width: 95px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                    </div>
                    <div class="div-col">
                      <div class="form-row_kiri">
                      <label  style = "text-align:right;">No. Kartu Keluarga &nbsp;</label>
                      	<input type="text" id="ahliwaris_no_kartu_keluarga" name="ahliwaris_no_kartu_keluarga" style="width:200px;" readonly class="disabled" >             					
                      </div>																																																			
                      <div class="clear"></div>
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
                      	<input type="text" id="ahliwaris_npwp" name="ahliwaris_npwp" style="width:200px;" readonly class="disabled">
                      </div>																																																																																																								
                      <div class="clear"></div>	
                      
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">No. Identitas </label>
                      	<input type="text" id="ahliwaris_nomor_identitas" name="ahliwaris_nomor_identitas" style="width:200px;" readonly class="disabled">
                      </div>																																																																																							
                      <div class="clear"></div>

                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Keterangan</label>
                      	<textarea cols="255" rows="1" style="width:200px;height:18px;background-color:#F5F5F5;" id="ahliwaris_keterangan" name="ahliwaris_keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>   					
                      </div>														
                      <div class="clear"></div>
									                              												
                      </br>
                    </div>
                  </div>
                </fieldset>
              </div>
            </div>
            <div class="div-row">
              <div class="div-col" style="width: 100%">
                <fieldset style="min-height:145px;">
                <legend>Kondisi Terakhir Ahli Waris Pada Saat Klaim Dilaporkan</legend>
                	</br></br></br>
									<span id="span_kondisi_terakhir" style="display:block;">			
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Kondisi Terakhir</label>		 	    				
                      <input type="hidden" id="ahliwaris_kode_kondisi_terakhir" name="ahliwaris_kode_kondisi_terakhir">
                      <input type="text" id="ahliwaris_nama_kondisi_terakhir" name="ahliwaris_nama_kondisi_terakhir" style="width:230px;" readonly class="disabled">
                    </div>																																													
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Sejak&nbsp;</label>
                      <input type="text" id="ahliwaris_tgl_kondisi_terakhir" name="ahliwaris_tgl_kondisi_terakhir" style="width:215px;" readonly class="disabled">
                    </div>																																																																																						
                    <div class="clear"></div>
                  </span>
									
									<span id="span_status_kawin" style="display:none;">
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Status Marital &nbsp;&nbsp;</label>		 	    				
                      <input type="hidden" id="ahliwaris_status_kawin" name="ahliwaris_status_kawin">
											<input type="text" id="ahliwaris_nama_status_kawin" name="ahliwaris_nama_status_kawin" style="width:210px;" readonly class="disabled">
                    </div>																																													
                    <div class="clear"></div>									
									</span>
        					</br>
        						
                </fieldset>
              </div>
            </div>
          </div>																												
				</div>
				<!--end div_body-->
					
				<div id="div_footer" class="div-footer">
  				<span id="span_button_utama" style="display:block;">
            <div style="padding-top:2px; width:99%;">
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
		let no_urut_keluarga = $('#no_urut_keluarga').val();
		loadSelectedRecord(kode_klaim, no_urut_keluarga, null);
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
	function loadSelectedRecord(v_kode_klaim, v_no_urut_keluarga, fn)
	{
		if (v_kode_klaim == '' || v_no_urut_keluarga == '') {
			return alert('Kode Klaim atau No Urut Keluarga tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdataahliwarisjpdetil',
				v_kode_klaim: v_kode_klaim,
				v_no_urut_keluarga:v_no_urut_keluarga
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						var v_kode_penerima_berkala = getValue(jdata.data.DATA['KODE_PENERIMA_BERKALA']);
						if (v_kode_penerima_berkala=="TK")
						{
						 	$("#span_page_title").html('PN5030 - INFORMASI TENAGA KERJA'); 
							$("#span_ket_legend1").html('Detil Informasi Tenaga Kerja');
							$("#span_ket_legend2").html('');
							window.document.getElementById("span_kondisi_terakhir").style.display = 'none';
							window.document.getElementById("span_status_kawin").style.display = 'block';
						}else
						{
						 	$("#span_page_title").html('PN5030 - INFORMASI AHLI WARIS');
							$("#span_ket_legend1").html('Detil Informasi Ahli Waris');
							$("#span_ket_legend2").html('Kondisi Terakhir Ahli Waris Pada Saat Klaim Dilaporkan');
							window.document.getElementById("span_kondisi_terakhir").style.display = 'block';
							window.document.getElementById("span_status_kawin").style.display = 'none';	 
						}
						$("#span_page_title_right").html('KODE KLAIM : '+getValue(jdata.data.DATA['KODE_KLAIM']));
									
						//set value ahli waris ----------------------------------------
						$('#ahliwaris_kode_hubungan').val(getValue(jdata.data.DATA['KODE_HUBUNGAN']));
						$('#ahliwaris_nama_hubungan').val(getValue(jdata.data.DATA['NAMA_HUBUNGAN']));
						$('#ahliwaris_no_urut_keluarga').val(getValue(jdata.data.DATA['NO_URUT_KELUARGA']));
						$('#ahliwaris_kode_penerima_berkala').val(getValue(jdata.data.DATA['KODE_PENERIMA_BERKALA']));
						
						
						$('#ahliwaris_nama_lengkap').val(getValue(jdata.data.DATA['NAMA_LENGKAP']));
						
						$('#ahliwaris_tempat_lahir').val(getValue(jdata.data.DATA['TEMPAT_LAHIR']));
						$('#ahliwaris_tgl_lahir').val(getValue(jdata.data.DATA['TGL_LAHIR']));
						
						$('#ahliwaris_jenis_kelamin').val(getValue(jdata.data.DATA['JENIS_KELAMIN']));
						$('#ahliwaris_nama_jenis_kelamin').val(getValue(jdata.data.DATA['NAMA_JENIS_KELAMIN']));
						$('#ahliwaris_golongan_darah').val(getValue(jdata.data.DATA['GOLONGAN_DARAH']));
						$('#ahliwaris_kpj_tertanggung').val(getValue(jdata.data.DATA['KPJ_TERTANGGUNG']));
						$('#ahliwaris_status_kawin').val(getValue(jdata.data.DATA['STATUS_KAWIN']));
						
						if (getValue(jdata.data.DATA['STATUS_KAWIN'])=="Y")
						{
						 	$('#ahliwaris_nama_status_kawin').val('Menikah'); 
						}else
						{
						 	$('#ahliwaris_nama_status_kawin').val('Tidak Menikah');	 
						}

						$('#ahliwaris_alamat').val(getValue(jdata.data.DATA['ALAMAT']));
						$('#ahliwaris_rt').val(getValue(jdata.data.DATA['RT']));
						$('#ahliwaris_rw').val(getValue(jdata.data.DATA['RW']));
						$('#ahliwaris_nama_kelurahan').val(getValue(jdata.data.DATA['NAMA_KELURAHAN']));
						$('#ahliwaris_kode_kelurahan').val(getValue(jdata.data.DATA['KODE_KELURAHAN']));
						$('#ahliwaris_nama_kecamatan').val(getValue(jdata.data.DATA['NAMA_KECAMATAN']));
						$('#ahliwaris_kode_kecamatan').val(getValue(jdata.data.DATA['KODE_KECAMATAN']));
						$('#ahliwaris_nama_kabupaten').val(getValue(jdata.data.DATA['NAMA_KABUPATEN']));
						$('#ahliwaris_kode_kabupaten').val(getValue(jdata.data.DATA['KODE_KABUPATEN']));
						$('#ahliwaris_kode_pos').val(getValue(jdata.data.DATA['KODE_POS']));
						
						$('#ahliwaris_telepon_area').val(getValue(jdata.data.DATA['TELEPON_AREA']));
						$('#ahliwaris_telepon').val(getValue(jdata.data.DATA['TELEPON']));
						$('#ahliwaris_telepon_ext').val(getValue(jdata.data.DATA['TELEPON_EXT']));
						$('#ahliwaris_handphone').val(getValue(jdata.data.DATA['HANDPHONE']));
						$('#ahliwaris_email').val(getValue(jdata.data.DATA['EMAIL']));
						
						$('#ahliwaris_no_kartu_keluarga').val(getValue(jdata.data.DATA['NO_KARTU_KELUARGA']));
						$('#ahliwaris_npwp').val(getValue(jdata.data.DATA['NPWP']));
						$('#ahliwaris_nomor_identitas').val(getValue(jdata.data.DATA['NOMOR_IDENTITAS']));
						$('#ahliwaris_keterangan').val(getValue(jdata.data.DATA['KETERANGAN']));
						
						$('#ahliwaris_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#ahliwaris_nomor_identitas').val());
						
						$('#ahliwaris_kode_kondisi_terakhir').val(getValue(jdata.data.DATA['KODE_KONDISI_TERAKHIR']));
						$('#ahliwaris_nama_kondisi_terakhir').val(getValue(jdata.data.DATA['NAMA_KONDISI_TERAKHIR']));
						$('#ahliwaris_tgl_kondisi_terakhir').val(getValue(jdata.data.DATA['TGL_KONDISI_TERAKHIR']));
						//end set value ahli waris ------------------------------------
												
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

