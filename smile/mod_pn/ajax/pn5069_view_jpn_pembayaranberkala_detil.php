<?php
//------------------------------------------------------------------------------
// Menu untuk display rincian pembayaran manfaat jp berkala
// dibuat tgl : 21/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 		= "form";
$gs_kodeform 	 	 		= "PN5030";
$chId 	 	 			 		= "SMILE";
$gs_pagetitle 	 		= "PEMBAYARAN MANFAAT JP BERKALA";											 
$gs_kantor_aktif 		= $_SESSION['kdkantorrole'];
$gs_kode_user		 		= $_SESSION["USER"];
$gs_kode_role		 		= $_SESSION['regrole'];
$task 					 		= $_POST["task"];
$editid 				 		= $_POST['editid'];
$ls_kode_klaim 	 		= !isset($_POST['kode_klaim']) ? $_GET['kode_klaim'] : $_POST['kode_klaim'];
$ln_no_konfirmasi   = !isset($_POST['no_konfirmasi']) ? $_GET['no_konfirmasi'] : $_POST['no_konfirmasi'];
$ln_no_proses 	 		= !isset($_POST['no_proses']) ? $_GET['no_proses'] : $_POST['no_proses'];
$ls_kd_prg 	 				= !isset($_POST['kd_prg']) ? $_GET['kd_prg'] : $_POST['kd_prg'];
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
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
			<input type="hidden" id="no_proses" name="no_proses" value="<?=$ln_no_proses;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
			<input type="hidden" id="kode_pembayaran" name="kode_pembayaran" value="<?=$ls_kode_pembayaran;?>">
			
			<div id="div_container" class="div-container">
        <div id="div_body" class="div-body" >
          <div class="div-row" >
          
            <div class="div-col" style="width:45%; max-height: 100%;">
  						<fieldset style="min-height:398px;"><legend>Informasi Penetapan Manfaat JP Berkala</legend>
  							</br></br>
  							
  							<div class="form-row_kiri">
                <label style = "text-align:right;">Nomor Berkala</label>
									<input type="text" id="byr_kode_kantor" name="byr_kode_kantor" style="width:25px;" readonly class="disabled">		 
                  <input type="text" id="byr_kode_klaim" name="byr_kode_klaim" style="width:125px;" readonly class="disabled">
                  <input type="text" id="byr_no_konfirmasi" name="byr_no_konfirmasi" style="width:25px;" readonly class="disabled">
  								<input type="text" id="byr_no_proses" name="byr_no_proses" style="width:20px;" readonly class="disabled">
  								<input type="hidden" id="byr_kd_prg" name="byr_kd_prg" style="width:27px;" readonly class="disabled">
                </div>																																																		
                <div class="clear"></div>
  							
                <div class="form-row_kiri">
                <label style = "text-align:right;">Periode Pembayaran</label>
                  <input type="text" id="byr_ket_blth_proses" name="byr_ket_blth_proses" style="width:220px;" readonly class="disabled">
                  <input type="hidden" id="byr_blth_proses" name="byr_blth_proses">
                </div>																																																		
                <div class="clear"></div>
  
                <div class="form-row_kiri">
                <label style = "text-align:right;">Penerima Manfaat</label>
                  <input type="text" id="byr_ket_penerima_berkala" name="byr_ket_penerima_berkala" style="width:220px;" readonly class="disabled">
                  <input type="hidden" id="byr_kode_penerima_berkala" name="byr_kode_penerima_berkala">
  								<input type="hidden" id="byr_no_urut_keluarga" name="byr_no_urut_keluarga">
                </div>																																																		
                <div class="clear"></div>
								
								</br>
								
                <div class="form-row_kiri">
                <label style = "text-align:right;">Nama Penerima</label>
                  <input type="text" id="byr_nama_penerima" name="byr_nama_penerima" style="width:220px;" readonly class="disabled">
                  <input type="hidden" id="byr_nik_penerima" name="byr_nik_penerima" style="width:210px;" readonly class="disabled">
                  <input type="hidden" id="byr_tgl_siapbayar" name="byr_tgl_siapbayar" maxlength="10" readonly class="disabled" style="width:210px;">
                  <input type="hidden" id="byr_status_siapbayar" name="byr_status_siapbayar">
                  <input type="hidden" id="byr_petugas_siapbayar" name="byr_petugas_siapbayar">
                </div>																																																																																														
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">NPWP</label>	    				
                  <input type="text" id="byr_npwp_penerima" name="byr_npwp_penerima" style="width:220px;" readonly class="disabled" onblur="fl_js_val_npwp('byr_npwp_penerima');">
                  <input type="hidden" id="byr_npwp_penerima_old" name="byr_npwp_penerima_old">
                </div>																																	
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Email</label>	    				
                	<input type="text" id="byr_email_penerima" name="byr_email_penerima" style="width:220px;" onblur="this.value=this.value.toLowerCase();fl_js_val_email('byr_email_penerima');" readonly class="disabled">
                </div>																																	
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Handphone</label>	    				
                	<input type="text" id="byr_handphone_penerima" name="byr_handphone_penerima" style="width:200px;" onblur="fl_js_val_numeric('byr_handphone_penerima');" readonly class="disabled">
                </div>																																	
                <div class="clear"></div>
										   							
        				</br>		
								  
        		    <div class="form-row_kiri">
        		    <label  style = "text-align:right;">Jumlah Manfaat</label>
        		      <input type="text" id="byr_nom_berkala" name="byr_nom_berkala" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
        		    </div>																																																		
        		    <div class="clear"></div>
  
        		    <div class="form-row_kiri">
        		    <label  style = "text-align:right;">PPh</label>
        		      <input type="text" id="byr_nom_pph" name="byr_nom_pph" style="width:120px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
        		    	<input type="text" id="byr_kode_pajak_pph" name="byr_kode_pajak_pph" style="width:70px;" readonly class="disabled" >
  							</div>																																																		
        		    <div class="clear"></div>
  
        		    <div class="form-row_kiri">
        		    <label  style = "text-align:right;">Pembulatan</label>
        		      <input type="text" id="byr_nom_pembulatan" name="byr_nom_pembulatan" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
        		    </div>																																																		
        		    <div class="clear"></div>
  
        		    <div class="form-row_kiri">
        		    <label  style = "text-align:right;">Jumlah Netto</label>
        		      <input type="text" id="byr_nom_manfaat_netto" name="byr_nom_manfaat_netto" style="width:180px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
        		    </div>																																																		
        		    <div class="clear"></div>																																																																										
  						</fieldset>		
            </div>
						<!--end div-col-->
            
            <div class="div-col" style="width:1%;">
            </div>
            
            <div class="div-col-right" style="width:54%;">
              <div class="div-row">
                <div class="div-col" style="width: 100%">
                  <fieldset style="width:95%"><legend>Dibayarkan ke</legend>
                    <div class="div-row">
                      <div class="div-col" style="width: 120px;text-align:left;">
                      	<input id="byr_nik_penerima_foto" name="byr_nik_penerima_foto" type="image" align="center" src="../../images/nopic.png" style="height: 90px !important; width: 85px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                      </div>
                      <div class="div-col">
      									<div class="form-row_kiri">
                        <label style = "text-align:left;width:70px;">Bank</label> 
                          <input type="text" id="byr_nama_bank_penerima" name="byr_nama_bank_penerima" readonly style="width:270px;background-color:#F5F5F5">
                          <input type="hidden" id="byr_kode_bank_penerima" name="byr_kode_bank_penerima" style="width:100px;">
                          <input type="hidden" id="byr_id_bank_penerima" name="byr_id_bank_penerima" style="width:100px;">
                          <input type="hidden" id="byr_metode_transfer" name="byr_metode_transfer" maxlength="4" readonly class="disabled" style="width:20px;">
                          <a style="display:none;" id="btn_lov_byr_bank_penerima" href="#" onclick="fl_js_jpbklverif_get_lov_bank_penerima();">							
                          <img src="../../images/help.png" alt="Cari Bank" border="0" style="height:19px;" align="absmiddle"></a>
                          <input type="hidden" id="byr_kode_bank_penerima_old" name="byr_kode_bank_penerima_old">
                        </div>																																																	
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:left;width:70px;">No Rekening</label>
                          <input type="text" id="byr_no_rekening_penerima" name="byr_no_rekening_penerima" readonly class="disabled" style="width:120px;">
                          <input type="hidden" id="byr_nama_rekening_penerima" name="byr_nama_rekening_penerima">
                          <input type="text" id="byr_nama_rekening_penerima_ws" name="byr_nama_rekening_penerima_ws" style="width:140px;" readonly class="disabled">
                          <input type="hidden" id="byr_no_rekening_penerima_old" name="byr_no_rekening_penerima_old">
                        </div>																																																																																															
                        <div class="clear"></div>
												
				                <div class="form-row_kiri">
                        <label style = "text-align:left;width:70px;">&nbsp;</label>
                          <input type="checkbox" id="cb_byr_valid_rekening" name="cb_byr_valid_rekening" disabled class="cebox"><i><font color="#009999">Valid</font></i>	
                          <input type="hidden" id="byr_status_valid_rekening_penerima" name="byr_status_valid_rekening_penerima">
                        </div>																																																																																															
                        <div class="clear"></div>								  
                      </div>
                    </div>
                  </fieldset>		
                </div>	 
              </div>
              <!--end div-row-->
							  
              <div class="div-row">
                <div class="div-col" style="width: 100%">
                  <fieldset style="height:80px;width:95%;"><legend><i><font color="#009999">Dari :</font></i></legend>
                    </br>
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Bank &nbsp;&nbsp;</label>
											<input type="text" id="byr_nama_bank_pembayar" name="byr_nama_bank_pembayar" readonly class="disabled" style="width:270px;">
                     	<input type="hidden" id="byr_kode_bank_pembayar" name="byr_kode_bank_pembayar">
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
                  <fieldset style="height:160px;width:95%;"><legend><i><font color="#009999">Informasi Pembayaran :</font></i></legend>
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

                    <div class="form-row_kiri">
                    <label style = "text-align:right;">Batal Bayar</label>
                      <input type="checkbox" id="cb_byr_status_batal" name="cb_byr_status_batal" disabled class="cebox">
											<span id="span_tgl_batal"></span>
											<input type="hidden" id="byr_tgl_batal" name="byr_tgl_batal" style="width:100px;" readonly class="disabled">	
                      <input type="hidden" id="byr_status_batal" name="byr_status_batal">
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
		
		let v_kode_klaim = $('#kode_klaim').val();
		let v_no_konfirmasi = $('#no_konfirmasi').val();
		let v_no_proses = $('#no_proses').val();
		let v_kd_prg = $('#kd_prg').val();
		let v_kode_pembayaran = $('#kode_pembayaran').val();
		loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_kode_pembayaran, null);
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
	function loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_kode_pembayaran, fn)
	{
		if (v_kode_klaim == '' || v_no_konfirmasi == '' || v_no_proses == '' || v_kd_prg == '' || v_kode_pembayaran == '') {
			return alert('Parameter mandatory tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatapembayaran_jpberkala',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi: v_no_konfirmasi,
				v_no_proses: v_no_proses,
				v_kd_prg: v_kd_prg,
        v_kode_pembayaran:v_kode_pembayaran
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{ 
						$("#span_page_title").html('PN5030 - INFORMASI PEMBAYARAN MANFAAT JP BERKALA');
						$("#span_page_title_right").html('KODE KLAIM : '+getValue(jdata.dataByr.DATA['KODE_KLAIM']));
						
						//set value rincian manfaat ----------------------------------------
						$('#byr_kode_kantor').val(getValue(jdata.dataByr.DATA['KODE_KANTOR']));
						$('#byr_kode_klaim').val(getValue(jdata.dataByr.DATA['KODE_KLAIM']));
						$('#byr_no_konfirmasi').val(getValue(jdata.dataByr.DATA['NO_KONFIRMASI']));
						$('#byr_no_proses').val(getValue(jdata.dataByr.DATA['NO_PROSES']));
						$('#byr_kd_prg').val(getValue(jdata.dataByr.DATA['KD_PRG']));
						
						$('#byr_ket_blth_proses').val(getValue(jdata.dataByr.DATA['KET_BLTH_PROSES']));
						$('#byr_blth_proses').val(getValue(jdata.dataByr.DATA['BLTH_PROSES']));
						
						var v_ket_penerima_berkala = "";
						if (getValue(jdata.dataByr.DATA['KODE_PENERIMA_BERKALA']) == "JD")
						{
						 	v_ket_penerima_berkala = "JANDA/DUDA"; 
						}else if (getValue(jdata.dataByr.DATA['KODE_PENERIMA_BERKALA']) == "A1")
						{
						 	v_ket_penerima_berkala = "ANAK PERTAMA"; 
						}else if (getValue(jdata.dataByr.DATA['KODE_PENERIMA_BERKALA']) == "A2")
						{
						 	v_ket_penerima_berkala = "ANAK KEDUA"; 
						}else if (getValue(jdata.dataByr.DATA['KODE_PENERIMA_BERKALA']) == "OT")
						{
						 	v_ket_penerima_berkala = "ORANG TUA"; 
						}else
						{
						 	v_ket_penerima_berkala = ""; 	 
						}						
						
						$('#byr_ket_penerima_berkala').val(v_ket_penerima_berkala);
						$('#byr_kode_penerima_berkala').val(getValue(jdata.dataByr.DATA['KODE_PENERIMA_BERKALA']));
						$('#byr_no_urut_keluarga').val(getValue(jdata.dataByr.DATA['NO_URUT_KELUARGA']));
						$('#byr_nama_penerima').val(getValue(jdata.dataByr.DATA['NAMA_PENERIMA_BERKALA']));
						$('#byr_npwp_penerima').val(getValue(jdata.dataByr.DATA['NPWP']));
						$('#byr_npwp_penerima_old').val(getValue(jdata.dataByr.DATA['NPWP']));
						$('#byr_email_penerima').val(getValue(jdata.dataByr.DATA['EMAIL']));
						$('#byr_handphone_penerima').val(getValue(jdata.dataByr.DATA['HANDPHONE']));
						$('#byr_nom_berkala').val(format_uang(getValue(jdata.dataByr.DATA['NOM_BERKALA'])));
						$('#byr_nom_pph').val(format_uang(getValue(jdata.dataByr.DATA['NOM_PPH'])));
						$('#byr_kode_pajak_pph').val(getValue(jdata.dataByr.DATA['KODE_PAJAK_PPH']));
						$('#byr_nom_pembulatan').val(format_uang(getValue(jdata.dataByr.DATA['NOM_PEMBULATAN'])));
						$('#byr_nom_manfaat_netto').val(format_uang(getValue(jdata.dataByr.DATA['NOM_MANFAAT_NETTO'])));
						
						if (getValue(jdata.dataByr.DATA['NIK_PENERIMA'])!='')
        		{
        		 	$('#byr_nik_penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + getValue(jdata.dataByr.DATA['NIK_PENERIMA']));
        		}
												
						$('#byr_nama_bank_penerima').val(getValue(jdata.dataByr.DATA['BANK_PENERIMA']));
						$('#byr_kode_bank_penerima').val(getValue(jdata.dataByr.DATA['KODE_BANK_PENERIMA']));
						$('#byr_kode_bank_penerima_old').val(getValue(jdata.dataByr.DATA['KODE_BANK_PENERIMA']));
						
						$('#byr_no_rekening_penerima').val(getValue(jdata.dataByr.DATA['NO_REKENING_PENERIMA']));
						$('#byr_nama_rekening_penerima').val(getValue(jdata.dataByr.DATA['NAMA_REKENING_PENERIMA']));
						$('#byr_nama_rekening_penerima_ws').val(getValue(jdata.dataByr.DATA['NAMA_REKENING_PENERIMA']));
						$('#byr_no_rekening_penerima_old').val(getValue(jdata.dataByr.DATA['NO_REKENING_PENERIMA']));
						
						$('#byr_status_valid_rekening_penerima').val(getValue(jdata.dataByr.DATA['STATUS_VALID_REKENING_PENERIMA']));
						if (getValue(jdata.dataByr.DATA['STATUS_VALID_REKENING_PENERIMA'])=="Y")
						{
						 	window.document.getElementById('cb_byr_valid_rekening').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_byr_valid_rekening').checked = false;
						}
						
						$('#byr_nama_bank_pembayar').val(getValue(jdata.dataByr.DATA['NAMA_BANK_PEMBAYAR']));
						$('#byr_kode_bank_pembayar').val(getValue(jdata.dataByr.DATA['KODE_BANK_PEMBAYAR']));
						
						$('#byr_kode_buku').val(getValue(jdata.dataByr.DATA['KODE_BUKU']));
						$('#byr_status_rekening_sentral').val(getValue(jdata.dataByr.DATA['STATUS_REKENING_SENTRAL']));
						if (getValue(jdata.dataByr.DATA['STATUS_REKENING_SENTRAL'])=="Y")
						{
						 	window.document.getElementById('cb_byr_status_rekening_sentral').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_byr_status_rekening_sentral').checked = false;
						}						
						
						$('#byr_kode_kantor_pembayar').val(getValue(jdata.dataByr.DATA['KODE_KANTOR_PEMBAYAR']));
						$('#byr_nama_kantor_pembayar').val(getValue(jdata.dataByr.DATA['NAMA_KANTOR_PEMBAYAR']));
						$('#byr_tgl_pembayaran').val(getValue(jdata.dataByr.DATA['TGL_PEMBAYARAN']));
						$('#byr_nom_pembayaran').val(format_uang(getValue(jdata.dataByr.DATA['NOM_SUDAH_BAYAR'])));
						$('#byr_kode_pembayaran').val(getValue(jdata.dataByr.DATA['KODE_PEMBAYARAN']));
						
						$('#byr_tgl_batal').val(getValue(jdata.dataByr.DATA['TGL_BATAL']));
						$('#byr_status_batal').val(getValue(jdata.dataByr.DATA['STATUS_BATAL']));
						
						if (getValue(jdata.dataByr.DATA['STATUS_BATAL'])=="Y")
						{
						 	window.document.getElementById('cb_byr_status_batal').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_byr_status_batal').checked = false;
						}
						
						if (getValue(jdata.dataByr.DATA['STATUS_BATAL'])=='Y')
						{
						 	$('#span_tgl_batal').html('Tgl Batal :'+getValue(jdata.dataByr.DATA['TGL_BATAL']));
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
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

