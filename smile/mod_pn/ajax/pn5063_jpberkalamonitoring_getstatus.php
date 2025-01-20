<?php
//------------------------------------------------------------------------------
// Menu untuk display informasi error transfer pembayaran klaim
// dibuat tgl : 13/09/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 = "form";
$gs_kodeform 	 	 = "PN5030";
$chId 	 	 			 = "SMILE";
$gs_pagetitle 	 = "INFORMASI STATUS PEMBAYARAN KLAIM";											 
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_user		 = $_SESSION["USER"];
$gs_kode_role		 = $_SESSION['regrole'];
$task 					 = $_POST["task"];
$editid 				 = $_POST['editid'];
$ls_kode_klaim 	 = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ln_no_konfirmasi = !isset($_POST['no_konfirmasi']) ? $_GET['no_konfirmasi'] : $_POST['no_konfirmasi'];
$ln_no_proses 		= !isset($_POST['no_proses']) ? $_GET['no_proses'] : $_POST['no_proses'];
$ls_kd_prg 			 	= !isset($_POST['kd_prg']) ? $_GET['kd_prg'] : $_POST['kd_prg'];
$ls_kode_transfer = !isset($_POST['kode_transfer']) ? $_GET['kode_transfer'] : $_POST['kode_transfer'];
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
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
			<input type="hidden" id="no_proses" name="no_proses" value="<?=$ln_no_proses;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
			<input type="hidden" id="kode_transfer" name="kode_transfer" value="<?=$ls_kode_transfer;?>">
			
			<div id="div_container" class="div-container">
        <div id="div_body" class="div-body" >
					<span id="span_byr_warning" style="display:none;">
						<div class="div-row" >
							<div class="div-col" style="width:95%; max-height: 100%;">
    						<br/>
    						
    						<span id="span_result_msg"><b><font color="#ff0000"><?=$ls_result_msg;?> </font></b></span>	
    						
    						<br/>
    						<br/>
    						
    						<b><font color="#009999">(* Tutup window status transfer kemudian klik GET STATUS kembali sampai mendapatkan respon SUKSES/GAGAL Transfer</font></b>
      					
    						<br/>
    						<br/>									 
							</div>	 
						</div>		
					</span>
					
					<span id="span_byr_rinci" style="display:block;">
						<div class="div-row" >
							<div class="div-col" style="width:46%; max-height: 100%;">
        				<fieldset style="width:95%"><legend>Informasi Transfer</legend>
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Remark&nbsp;</label>
                    <textarea cols="255" rows="1" style="width:270px;height:40px;background-color:#F5F5F5;" readonly id="keterangan" name="keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>   					
                  	<input type="hidden" id="no_batch" name="no_batch">
        					</div>							
                  <div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Tgl Transfer</label>					
                  	<input type="text" id="tgl_transfer" name="tgl_transfer" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>									
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Nominal</label>
        						<input type="text" id="mata_uang" name="mata_uang" readonly class="disabled" style="width:20px;">		 					
                  	<input type="text" id="nominal" name="nominal" readonly class="disabled" style="width:241px;">
                  </div>																																																			
                  <div class="clear"></div>	
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Metode Transfer</label>					
                  	<input type="text" id="metode_transfer" name="metode_transfer" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>	
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"><i><font color="#009999">Rekening Asal :</font></i></label>    				
                  </div>																																																																																																																																																																																
                  <div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Bank</label>					
                  	<input type="text" id="bank_asal" name="bank_asal" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>	
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">No. Rekening</label>					
                  	<input type="text" id="norek_asal" name="norek_asal" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>	
        					
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"><i><font color="#009999">Rekening Tujuan :</font></i></label>    				
                  </div>																																																																																																																																																																																
                  <div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Bank</label>					
                  	<input type="text" id="bank_tujuan" name="bank_tujuan" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>	
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">No. Rekening</label>					
                  	<input type="text" id="norek_tujuan" name="norek_tujuan" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>
        
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">A/N</label>					
                  	<input type="text" id="nama_rek_tujuan" name="nama_rek_tujuan" readonly class="disabled" style="width:270px;">
                  </div>																																																			
                  <div class="clear"></div>
        					
        					</br>
        									
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Tgl Transaksi Bank</label>					
                  	<input type="text" id="tgl_transaksi_bank" name="tgl_transaksi_bank" readonly class="disabled" style="width:250px;">
                  </div>																																																			
                  <div class="clear"></div>
        									
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">No. Ref Bank</label>					
                  	<input type="text" id="kode_referensi_bank" name="kode_referensi_bank" readonly class="disabled" style="width:230px;">
                  </div>																																																			
                  <div class="clear"></div>
        																																															
        				</fieldset>
        											 
							</div> 
							
							<div class="div-col" style="width:1%;">
              </div>
							
							<div class="div-col-right" style="width:53%;">
        				<fieldset style="width:95%;min-height:389px;"><legend><span id="span_legend_statustrf">Status Transfer : &nbsp;<b><font color="#ff0000"></font></b></span></legend>
                  </br></br></br>
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Status Transfer</label>
                    <input type="checkbox" id="cb_is_do_payment" name="cb_is_do_payment" class="cebox" disabled <?=$ls_is_do_payment=="Y" ||$ls_is_do_payment=="ON" ||$ls_is_do_payment=="on" ? "checked" : "";?>><i><font color="#009999">do Payment &nbsp;&nbsp;&nbsp;</font></i>	
        						<input type="checkbox" id="cb_status_transfer" name="cb_status_transfer" class="cebox" disabled <?=$ls_status_transfer=="Y" ||$ls_status_transfer=="ON" ||$ls_status_transfer=="on" ? "checked" : "";?>><i><font color="#009999">Sukses Transfer</font></i>				
                  	<input type="hidden" id="is_do_payment" name="is_do_payment" value="<?=$ls_is_do_payment;?>">
        						<input type="hidden" id="status_transfer" name="status_transfer" value="<?=$ls_status_transfer;?>">
        					</div>							
                  <div class="clear"></div>
									
									<div class="form-row_kiri">
                  <label style = "text-align:right;">&nbsp;</label>
                    <textarea cols="255" rows="1" style="width:330px;height:120px;background-color:#F5F5F5;" readonly id="error_transfer" name="error_transfer" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_error_transfer;?></textarea>   					
                  </div>							
                  <div class="clear"></div>
        					
									</br></br></br>
																		
									<div class="form-row_kiri">
                  <label style = "text-align:right;">Status PNBYR</label>
                    <textarea cols="255" rows="1" style="width:330px;height:120px;background-color:#F5F5F5;" readonly id="p_mess" name="p_mess" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_p_mess;?></textarea>   					
                  </div>							
                  <div class="clear"></div>        						
        				</fieldset>											 
							</div>
						</div>		
					</span>									
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
	
		let v_mode_trx = 'view';
		let v_kode_klaim = $('#kode_klaim').val();
		let v_no_konfirmasi = $('#no_konfirmasi').val();
		let v_no_proses = $('#no_proses').val();
		let v_kd_prg = $('#kd_prg').val();
		let v_kode_transfer = $('#kode_transfer').val();
		
		fl_js_lumpverif_detilSiapBayar(v_mode_trx, v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_kode_transfer, null)
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
  function fl_js_lumpverif_detilSiapBayar(v_mode_trx, v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_kode_transfer, fn)
  {					
    var fn;
    if (v_kode_klaim == '' || v_no_konfirmasi == '' || v_no_proses == '' || v_kd_prg == '' || v_kode_transfer == '') {
    	return alert('Paramete mandatory tidak boleh kosong');
    }
					
    asyncPreload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5063_action.php?"+Math.random(),
      data: {
        tipe: 'fjq_ajax_val_jpbklmonitoring_getpaymentstatus',
        v_kode_klaim: v_kode_klaim,
        v_no_konfirmasi:v_no_konfirmasi,
				v_no_proses:v_no_proses,
        v_kd_prg:v_kd_prg,
				v_kode_transfer:v_kode_transfer
      },
      success: function(data){
        try {
          jdata = JSON.parse(data);
          if (jdata.ret == 0) 
          {          
						window.document.getElementById("span_byr_rinci").style.display = 'block';
						
						$('#span_page_title').html('STATUS TRANSFER PEMBAYARAN MANFAAT JP BERKALA - KODE TRANSFER '+v_kode_transfer);
						$('#span_page_title_right').html('KODE KLAIM : '+v_kode_klaim);
								
						//get status transfer ----------------------------------------------
						var v_status_transfer = getValue(jdata.STATUS['STATUS_TRANSFER']) === '' ? 'T' : getValue(jdata.STATUS['STATUS_TRANSFER']);
						var v_is_do_payment 	= getValue(jdata.STATUS['IS_DO_PAYMENT']) === '' ? 'T' : getValue(jdata.STATUS['IS_DO_PAYMENT']);
						var v_error_transfer 	= getValue(jdata.STATUS['ERROR_TRANSFER']);
						var v_ket_status_transfer = "";
						
        		if (v_status_transfer=='Y')
        		{
        		 	v_ket_status_transfer = 'Transfer Berhasil';  
        		}else
        		{
        		 	if (v_status_transfer=='T' && v_error_transfer!='')
        			{
        			 	v_ket_status_transfer = 'Transfer Gagal : <br/> '+v_error_transfer+', <br/> Harap dilakukan verifikasi ulang data siap bayar'; 
        			}else if (v_status_transfer=='T' && v_error_transfer=='' && v_is_do_payment=='Y')
        			{
        			 	v_ket_status_transfer = 'Timeout pada saat proses transfer ke bank, tutup window status kemudian klik GET STATUS sampai mendapatkan status SUKSES/GAGAl transfer'; 
        			}else
        			{
        			 	v_ket_status_transfer = 'Tidak teridentifikasi'; 	 
        			}		 
        		}
						
						//generate layout rincian error transfer ---------------------------
						$('#span_legend_statustrf').html('Status Transfer : &nbsp;<b><font color="#ff0000">'+v_ket_status_transfer+'</font></b>');
						$('#keterangan').val(getValue(jdata.STATUS['KETERANGAN']));
						$('#tgl_transfer').val(getValue(jdata.STATUS['TGL_TRANSFER']));
						$('#mata_uang').val(getValue(jdata.STATUS['MATA_UANG']));
						$('#nominal').val(format_uang(getValue(jdata.STATUS['NOMINAL'])));
						$('#metode_transfer').val(getValue(jdata.STATUS['METODE_TRANSFER']));
						$('#bank_asal').val(getValue(jdata.STATUS['BANK_ASAL']));
						$('#norek_asal').val(getValue(jdata.STATUS['NOREK_ASAL']));
						$('#bank_tujuan').val(getValue(jdata.STATUS['BANK_TUJUAN']));
						$('#norek_tujuan').val(getValue(jdata.STATUS['NOREK_TUJUAN']));
						$('#nama_rek_tujuan').val(getValue(jdata.STATUS['NAMA_REK_TUJUAN']));
						$('#tgl_transaksi_bank').val(getValue(jdata.STATUS['TGL_TRANSAKSI_BANK']));
						$('#kode_referensi_bank').val(getValue(jdata.STATUS['KODE_REFERENSI_BANK']));
						$('#error_transfer').val(getValue(jdata.STATUS['ERROR_TRANSFER']));
						$('#is_do_payment').val(getValue(jdata.STATUS['IS_DO_PAYMENT']));
						$('#status_transfer').val(getValue(jdata.STATUS['STATUS_TRANSFER']));
						$('#p_mess').val(getValue(jdata.STATUS['P_MESS']));
						
						if (getValue(jdata.STATUS['IS_DO_PAYMENT'])=="Y")
						{
						 	window.document.getElementById('cb_is_do_payment').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_is_do_payment').checked = false;
						}

						if (getValue(jdata.STATUS['STATUS_TRANSFER'])=="Y")
						{
						 	window.document.getElementById('cb_status_transfer').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_status_transfer').checked = false;
						}												

      			window.document.getElementById("span_byr_warning").style.display = 'none';
      			window.document.getElementById("span_byr_rinci").style.display = 'block';	
						
						window.parent.Ext.WindowManager.getActive().parent.fl_js_jpbklmonitoring_filter();
						
            //end generate layout rincian penerima manfaat -------------------
            if (fn && fn.success) {
            	fn.success();
            }
      		} else 
					{
          	if (jdata.ret == "-1")
						{
							$('#span_result_msg').html(jdata.msg);
							window.document.getElementById("span_byr_warning").style.display = 'block';
        			window.document.getElementById("span_byr_rinci").style.display = 'none';
						}else
						{
  						window.document.getElementById("span_byr_rinci").style.display = 'none';
  						alert("Tidak ada data yanga akan ditampilkan...");
          	}
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
	//end function fl_js_lumpverif_detilSiapBayar --------------------------------
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

