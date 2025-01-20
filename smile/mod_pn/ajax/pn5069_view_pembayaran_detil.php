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
$gs_pagetitle 	 = "PEMBAYARAN KLAIM";											 
$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$gs_kode_user		 = $_SESSION["USER"];
$gs_kode_role		 = $_SESSION['regrole'];
$task 					 = $_POST["task"];
$editid 				 = $_POST['editid'];
$ls_kode_klaim 	 = !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ls_kode_pembayaran = !isset($_POST['kode_pembayaran']) ? $_GET['kode_pembayaran'] : $_POST['kode_pembayaran'];
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
			<input type="hidden" id="kode_pembayaran" name="kode_pembayaran" value="<?=$ls_kode_pembayaran;?>">
			
			<div id="div_container" class="div-container">
        <div id="div_body" class="div-body" >
          <div class="div-row" >
          
            <div class="div-col" style="width:44%; max-height: 100%;">
              <fieldset style="min-height:391px;"><legend>Informasi Pembayaran Klaim</legend>		
                </br>
                <div class="form-row_kiri" >
                <label style = "text-align:right;">Kantor Penetapan</label>
                  <input type="text" id="byr_kode_kantor" name="byr_kode_kantor" style="width:30px;" readonly class="disabled" >  
                  <input type="text" id="byr_nama_kantor" name="byr_nama_kantor" style="width:180px;" readonly class="disabled" >
                </div>																																																	
                <div class="clear"></div>
																
                <div class="form-row_kiri" >
                <label style = "text-align:right;">Penerima Manfaat</label>
                  <input type="text" id="byr_nama_tipe_penerima" name="byr_nama_tipe_penerima" style="width:220px;" readonly class="disabled" >  
                  <input type="hidden" id="byr_kode_tipe_penerima" name="byr_kode_tipe_penerima">
                </div>																																																	
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Nama Penerima</label>
                  <input type="text" id="byr_nama_penerima" name="byr_nama_penerima" style="width:220px;" readonly class="disabled">
                  <input type="hidden" id="byr_nik_penerima" name="byr_nik_penerima">
                </div>																																																																																														
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">NPWP</label>	    				
                  <input type="text" id="byr_npwp_penerima" name="byr_npwp_penerima" style="width:220px;" readonly class="disabled">
                  <input type="hidden" id="byr_npwp_penerima_old" name="byr_npwp_penerima_old">
                </div>																																	
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Email</label>	    				
                	<input type="text" id="byr_email_penerima" name="byr_email_penerima" style="width:220px;" readonly class="disabled">
                </div>																																	
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Handphone</label>	    				
                	<input type="text" id="byr_handphone_penerima" name="byr_handphone_penerima" style="width:200px;" readonly class="disabled">
                </div>																																	
                <div class="clear"></div>						
                
                </br>		
                
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Nominal Gross </label>
                	<input type="text" id="byr_nom_manfaat_gross" name="byr_nom_manfaat_gross" style="text-align:left;width:220px;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
                </div>																																																		
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label  style = "text-align:right;">PPN </label>
                  <input type="text" id="byr_nom_ppn" name="byr_nom_ppn" onblur="this.value=format_uang(this.value);" style="text-align:left;width:150px;" readonly class="disabled">
                  <input type="text" id="byr_kode_pajak_ppn" name="byr_kode_pajak_ppn" style="text-align:left;width:60px;" readonly class="disabled">				
                </div>																		
                <div class="clear"></div>		
                
                <div class="form-row_kiri">
                <label  style = "text-align:right;">PPh </label>
                  <input type="text" id="byr_nom_pph" name="byr_nom_pph" style="width:150px;" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">
                  <input type="text" id="byr_kode_pajak_pph" name="byr_kode_pajak_pph" style="width:60px;" readonly class="disabled" >
                </div>																		
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Pembulatan </label>
                	<input type="text" id="byr_nom_pembulatan" name="byr_nom_pembulatan" style="width:220px;" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
                </div>																		
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Netto </label>
                	<input type="text" id="byr_nom_netto" name="byr_nom_netto" style="width:220px;" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
                </div>																		
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Program &nbsp;</label>
                  <input type="hidden" id="byr_kd_prg" name="byr_kd_prg">
                  <input type="text" id="byr_nm_prg" name="byr_nm_prg" style="width:200px;" readonly class="disabled" >                					
                </div>																																																			
                <div class="clear"></div>
								
                <div class="form-row_kiri">
                <label style = "text-align:right;">Keterangan &nbsp;</label>
                  <input type="text" id="byr_keterangan" name="byr_keterangan" style="width:180px;" readonly class="disabled" >                					
                </div>																																																			
                <div class="clear"></div>								
                </br>		        						
              </fieldset>		
            </div>
						<!--end div-col-->
            
            <div class="div-col" style="width:1%;">
            </div>
            
            <div class="div-col-right" style="width:55%;">
              <div class="div-row">
                <div class="div-col" style="width: 100%">
                  <fieldset style="width:95%"><legend>Dibayarkan ke</legend>
                    <div class="div-row">
                      <div class="div-col" style="width: 120px;text-align:left;">
                      	<input id="byr_nik_penerima_foto" name="byr_nik_penerima_foto" onclick="showfotojmo();" type="image" align="center" src="../../images/nopic.png" style="height: 90px !important; width: 85px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                      </div>
                      <div class="div-col">
                        <div class="form-row_kiri">
                        <label style = "text-align:left;width:70px;">Cara Bayar</label>
                          <input type="text" id="byr_nama_cara_bayar" name="byr_nama_cara_bayar" readonly class="disabled" style="width:250px;">
                          <input type="hidden" id="byr_kode_cara_bayar" name="byr_kode_cara_bayar">
                        </div>		    																																				
                        <div class="clear"></div>
                        
                        <span id="byr_span_va_debit" style="display:none;"></span>
                        
                        <span id="byr_span_rekening" style="display:none;">						
                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:70px;">Bank</label> 
                            <input type="text" id="byr_nama_bank_penerima" name="byr_nama_bank_penerima" style="width:250px;" readonly class="disabled">
                            <input type="hidden" id="byr_kode_bank_penerima" name="byr_kode_bank_penerima">
                            <input type="hidden" id="byr_id_bank_penerima" name="byr_id_bank_penerima">
                            <input type="hidden" id="byr_metode_transfer" name="byr_metode_transfer">
                            <input type="hidden" id="byr_kode_bank_penerima_old" name="byr_kode_bank_penerima_old">
                          </div>																																																	
                          <div class="clear"></div>
                          
                          <div class="form-row_kiri">
                          <label style = "text-align:left;width:70px;">No Rekening</label>
                            <input type="text" id="byr_no_rekening_penerima" name="byr_no_rekening_penerima" style="width:100px;" readonly class="disabled">
                            <input type="hidden" id="byr_nama_rekening_penerima" name="byr_nama_rekening_penerima">
                            <input type="text" id="byr_nama_rekening_penerima_ws" name="byr_nama_rekening_penerima_ws" style="width:120px;" readonly class="disabled">
                            <input type="checkbox" id="cb_byr_valid_rekening" name="cb_byr_valid_rekening" disabled class="cebox"><i><font color="#009999">Valid</font></i>	
                            <input type="hidden" id="byr_status_valid_rekening_penerima" name="byr_status_valid_rekening_penerima">
                            <input type="hidden" id="byr_no_rekening_penerima_old" name="byr_no_rekening_penerima_old">
                          </div>																																																																																															
                          <div class="clear"></div>      
                        </span>
                      </div>
                    </div>
                  </fieldset>		
                </div>	 
              </div>
              <!--end div-row-->
							  
              <div class="div-row">
                <div class="div-col" style="width: 100%">
                  <fieldset style="height:85px;width:95%;"><legend><i><font color="#009999">Dari :</font></i></legend>
                    </br>
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Bank &nbsp;&nbsp;</label>
                      <input type="text" id="byr_nama_bank_pembayar" name="byr_nama_bank_pembayar" readonly class="disabled" style="width:230px;">
                      <input type="hidden" id="byr_kode_bank_pembayar" name="byr_kode_bank_pembayar" readonly class="disabled" style="width:260px;">
											<input type="text" id="byr_klm_kode_pointer_asal" name="byr_klm_kode_pointer_asal" style="width:30px;" readonly class="disabled">
                    </div>																																												
                    <div class="clear"></div>
										
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">&nbsp;</label>
                      <input type="checkbox" id="cb_byr_status_rekening_sentral" name="cb_byr_status_rekening_sentral" disabled class="cebox"><i><font color="#009999">Sentralisasi Rekening</font></i>		
                      <input type="hidden" id="byr_kode_buku" name="byr_kode_buku">    								
                      <input type="hidden" id="byr_status_rekening_sentral" name="byr_status_rekening_sentral">
                      <input type="hidden" id="byr_kantor_rekening_sentral" name="byr_kantor_rekening_sentral">
                    </div>																																												
                    <div class="clear"></div>			
                  </fieldset>				
                </div>		 
              </div>
              <!--end div-row-->
							  
              <div class="div-row">
                <div class="div-col" style="width: 100%">
                  <fieldset style="height:148px;width:95%;"><legend><i><font color="#009999">Informasi Pembayaran :</font></i></legend>
                    </br>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Dibayarkan di &nbsp;&nbsp;</label>
                      <input type="text" id="byr_kode_kantor_pembayar" name="byr_kode_kantor_pembayar" style="width:30px;" readonly class="disabled">
                      <input type="text" id="byr_nama_kantor_pembayar" name="byr_nama_kantor_pembayar" style="width:195px;" readonly class="disabled">
                    </div>																																												
                    <div class="clear"></div>	
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Tanggal &nbsp;&nbsp;</label>
                    	<input type="text" id="byr_tgl_pembayaran" name="byr_tgl_pembayaran" style="width:235px;" readonly class="disabled">
                    </div>																																												
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label  style = "text-align:right;">Jumlah Dibayarkan </label>
                    	<input type="text" id="byr_nom_pembayaran" name="byr_nom_pembayaran" style="width:235px;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
                    </div>																		
                    <div class="clear"></div>
                    
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Kode Pembayaran &nbsp;&nbsp;</label>
                    	<input type="text" id="byr_kode_pembayaran" name="byr_kode_pembayaran" style="width:200px;" readonly class="disabled">
                    </div>																																												
                    <div class="clear"></div>
                  
                  </fieldset>				
                </div>		 
              </div>
							<!--end div-row-->
            
            </div>
						<!--end div-col-right-->
							
          </div>
					<!--end div-row-->		
																																		
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
		let kode_pembayaran = $('#kode_pembayaran').val();
		loadSelectedRecord(kode_klaim, kode_pembayaran, null);
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
	function loadSelectedRecord(v_kode_klaim, v_kode_pembayaran, fn)
	{
		if (v_kode_klaim == '' || v_kode_pembayaran == '') {
			return alert('Kode Klaim atau Kode Pembayaran tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatapembayaran',
				v_kode_klaim: v_kode_klaim,
        v_kode_pembayaran:v_kode_pembayaran
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - INFORMASI PEMBAYARAN KLAIM');
						$("#span_page_title_right").html('KODE KLAIM : '+getValue(jdata.dataByr.DATA['KODE_KLAIM']));
						
						//set value rincian manfaat ----------------------------------------
						$('#byr_kode_kantor').val(getValue(jdata.dataByr.DATA['KODE_KANTOR']));
						$('#byr_nama_kantor').val(getValue(jdata.dataByr.DATA['NAMA_KANTOR']));
						$('#byr_nama_tipe_penerima').val(getValue(jdata.dataByr.DATA['NAMA_TIPE_PENERIMA']));
						$('#byr_kode_tipe_penerima').val(getValue(jdata.dataByr.DATA['KODE_TIPE_PENERIMA']));
						$('#byr_nama_penerima').val(getValue(jdata.dataByr.DATA['NAMA_PENERIMA']));
						$('#byr_nik_penerima').val(getValue(jdata.dataByr.DATA['NIK_PENERIMA']));
						$('#byr_npwp_penerima').val(getValue(jdata.dataByr.DATA['NPWP_PENERIMA']));
						$('#byr_npwp_penerima_old').val(getValue(jdata.dataByr.DATA['NPWP_PENERIMA']));
						$('#byr_email_penerima').val(getValue(jdata.dataByr.DATA['EMAIL_PENERIMA']));
						$('#byr_handphone_penerima').val(getValue(jdata.dataByr.DATA['HANDPHONE_PENERIMA']));
						
						$('#byr_nom_manfaat_gross').val(format_uang(getValue(jdata.dataByr.DATA['NOM_MANFAAT_GROSS'])));
						$('#byr_nom_ppn').val(format_uang(getValue(jdata.dataByr.DATA['NOM_PPN'])));
						$('#byr_kode_pajak_ppn').val(getValue(jdata.dataByr.DATA['KODE_PAJAK_PPN']));
						$('#byr_nom_pph').val(format_uang(getValue(jdata.dataByr.DATA['NOM_PPH'])));
						$('#byr_kode_pajak_pph').val(getValue(jdata.dataByr.DATA['KODE_PAJAK_PPH']));
						$('#byr_nom_pembulatan').val(format_uang(getValue(jdata.dataByr.DATA['NOM_PEMBULATAN'])));
						$('#byr_nom_netto').val(format_uang(getValue(jdata.dataByr.DATA['NOM_MANFAAT_NETTO'])));
						$('#byr_kd_prg').val(getValue(jdata.dataByr.DATA['KD_PRG']));
						$('#byr_nm_prg').val(getValue(jdata.dataByr.DATA['NM_PRG']));
						$('#byr_keterangan').val(getValue(jdata.dataByr.DATA['KETERANGAN']));
						
						if (getValue(jdata.dataByr.DATA['NIK_PENERIMA'])!='')
							{
								if(getValue(jdata.dataByr.DATA['KANAL_PELAYANAN'])=='40')
								{
								$('#byr_nik_penerima_foto').attr('src','<?= "../../mod_pn/ajax/pngetfotojmo.php?dataid=".$ls_kode_klaim ?>');
								}else
								{
								$('#byr_nik_penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + getValue(jdata.dataByr.DATA['NIK_PENERIMA']));
								}
							}
																								
						$('#byr_nama_cara_bayar').val(getValue(jdata.dataByr.DATA['NAMA_CARA_BAYAR']));
						$('#byr_kode_cara_bayar').val(getValue(jdata.dataByr.DATA['KODE_CARA_BAYAR']));
						
						$('#byr_nama_bank_penerima').val(getValue(jdata.dataByr.DATA['BANK_PENERIMA']));
						$('#byr_kode_bank_penerima').val(getValue(jdata.dataByr.DATA['KODE_BANK_PENERIMA']));
						$('#byr_id_bank_penerima').val(getValue(jdata.dataByr.DATA['ID_BANK_PENERIMA']));
						$('#byr_metode_transfer').val(getValue(jdata.dataByr.DATA['METODE_TRANSFER']));
						$('#byr_kode_bank_penerima_old').val(getValue(jdata.dataByr.DATA['KODE_BANK_PENERIMA']));
						
						$('#byr_no_rekening_penerima').val(getValue(jdata.dataByr.DATA['NO_REKENING_PENERIMA']));
						$('#byr_nama_rekening_penerima').val(getValue(jdata.dataByr.DATA['NAMA_REKENING_PENERIMA']));
						$('#byr_nama_rekening_penerima_ws').val(getValue(jdata.dataByr.DATA['NAMA_REKENING_PENERIMA']));
						
						if (getValue(jdata.dataByr.DATA['STATUS_VALID_REKENING_PENERIMA'])=="Y")
						{
						 	window.document.getElementById('cb_byr_valid_rekening').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_byr_valid_rekening').checked = false;
						}						
						
						$('#byr_status_valid_rekening_penerima').val(getValue(jdata.dataByr.DATA['STATUS_VALID_REKENING_PENERIMA']));
						$('#byr_no_rekening_penerima_old').val(getValue(jdata.dataByr.DATA['NO_REKENING_PENERIMA']));

						var v_nama_bank_pembayar = getValue(jdata.dataByr.DATA['NAMA_BANK'])+' - '+getValue(jdata.dataByr.DATA['KODE_BUKU']);												
						$('#byr_nama_bank_pembayar').val(v_nama_bank_pembayar);
						$('#byr_kode_bank_pembayar').val(getValue(jdata.dataByr.DATA['KODE_BANK']));
						
						if (getValue(jdata.dataByr.DATA['STATUS_REKENING_SENTRAL'])=="Y")
						{
						 	window.document.getElementById('cb_byr_status_rekening_sentral').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_byr_status_rekening_sentral').checked = false;
						}						
						
						$('#byr_kode_buku').val(getValue(jdata.dataByr.DATA['KODE_BUKU']));
						$('#byr_klm_kode_pointer_asal').val(getValue(jdata.dataByr.DATA['KLM_KODE_POINTER_ASAL']));
						$('#byr_status_rekening_sentral').val(getValue(jdata.dataByr.DATA['STATUS_REKENING_SENTRAL']));
						$('#byr_kantor_rekening_sentral').val(getValue(jdata.dataByr.DATA['KANTOR_REKENING_SENTRAL']));
						
						$('#byr_kode_kantor_pembayar').val(getValue(jdata.dataByr.DATA['KODE_KANTOR_PEMBAYAR']));
						$('#byr_nama_kantor_pembayar').val(getValue(jdata.dataByr.DATA['NAMA_KANTOR_PEMBAYAR']));
						$('#byr_tgl_pembayaran').val(getValue(jdata.dataByr.DATA['TGL_PEMBAYARAN']));
						$('#byr_nom_pembayaran').val(format_uang(getValue(jdata.dataByr.DATA['NOM_SUDAH_BAYAR'])));
						$('#byr_kode_pembayaran').val(getValue(jdata.dataByr.DATA['KODE_PEMBAYARAN']));
						
						if (getValue(jdata.dataByr.DATA['KODE_CARA_BAYAR'])=="V" || getValue(jdata.dataByr.DATA['KODE_CARA_BAYAR'])=="TTK")
						{
						 	window.document.getElementById("byr_span_va_debit").style.display = 'block';
							$("#byr_span_va_debit").html('<div class="form-row_kiri"><label style = "text-align:left;width:70px;">&nbsp;</label><i><font color="#ff0000">No.VA dikirimkan ke No.HP '+getValue(jdata.dataByr.DATA['HANDPHONE_PENERIMA'])+'</font></i></div><div class="clear"></div>'); 
						}
						
						fl_js_span_byr_carabayar();
					
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
  function fl_js_span_byr_carabayar()
  {
    var	v_kode_cara_bayar = window.document.getElementById('byr_kode_cara_bayar').value;

		if (v_kode_cara_bayar =="V" || v_kode_cara_bayar =="TTK") //va debet -------
    {
			window.document.getElementById("byr_span_va_debit").style.display = 'block';
			window.document.getElementById("byr_span_rekening").style.display = 'none';	
    }else
    {	   
			window.document.getElementById("byr_span_va_debit").style.display = 'none';
			window.document.getElementById("byr_span_rekening").style.display = 'block';
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

