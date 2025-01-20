<?php
//------------------------------------------------------------------------------
// Menu untuk cetak laporan jaminan siap bayar
// dibuat tgl : 25/09/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 		= "form";
$gs_kodeform 	 	 		= "PN5004";
$chId 	 	 			 		= "SMILE";
$gs_pagetitle 	 		= "PENCETAKAN LAPORAN KLAIM SIAP BAYAR";											 
$gs_kantor_aktif 		= $_SESSION['kdkantorrole'];
$gs_kode_user		 		= $_SESSION["USER"];
$gs_kode_role		 		= $_SESSION['regrole'];
$task 					 		= $_POST["task"];
$editid 				 		= $_POST['editid'];
$ld_tglawaldisplay 	= !isset($_POST['tglawaldisplay']) ? $_GET['tglawaldisplay'] : $_POST['tglawaldisplay'];
$ld_tglakhirdisplay = !isset($_POST['tglakhirdisplay']) ? $_GET['tglakhirdisplay'] : $_POST['tglakhirdisplay'];
//end set parameter ------------------------------------------------------------

if (isset($_POST["task"]) && $_POST["task"]=="CetaK") 
{
  $ls_user_param = "";
  $ls_user_param .= " QUSER='$gs_kode_user'";
  $ls_user_param .= " QKTR='$gs_kantor_aktif'";
	$ls_user_param .= " QTANGGAL1='$ld_tglawaldisplay'";
	$ls_user_param .= " QTANGGAL2='$ld_tglakhirdisplay'";
	
  $ls_nama_rpt = "PNR603003.rdf";
  $ls_modul = 'pn';
  $tipe = 'PDF';
  exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);	
}

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
		<div class="item"><?=$gs_pagetitle;?></div>		
	</div>
</div>

<div id="formframe" style="min-width:80%;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri" style="width:98%;">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data" >
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="kode_kantor" name="kode_kantor" value="<?=$gs_kantor_aktif;?>">
			
			<div id="div_container" class="div-container">
        <div id="div_body" class="div-body">
          <div class="div-row" >
						<div class="div-col" style="width:49%; max-height: 100%;">
							<fieldset><legend><span id="span_legend_dibawahotorisasi"></span></legend>
        				<div class="form-row_kiri">
        					<label  style = "text-align:right;"><i><font color="#009999">Yang Mengajukan:</font></i></label>      					
        				</div>										
        				<div class="clear"></div>
  							
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Pejabat</label>
        					<input type="text" id="pejabat1" name="pejabat1" size="40"/>
        				</div>
        				<div class="clear"></div>
  							
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Jabatan</label>
        					<input type="text" id="keterangan1" name="keterangan1" size="40"/>
        				</div>
        				<div class="clear"></div>
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Selaku PPs.</label>
        					<input type="text" id="keterangan1_pps" name="keterangan1_pps" size="40"/>
        				</div>
        				<div class="clear"></div>
  																					
  							<br/>
  																																																	
        				<div class="form-row_kiri">
        					<label style = "text-align:right;"><i><font color="#009999">Yang Mengetahui:</font></i></label>      					
        				</div>
        				<div class="clear"></div>		
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Pejabat</label>
        					<input type="text" id="pejabat2" name="pejabat2" size="40"/>
        				</div>	
  							<div class="clear"></div>
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Jabatan</label>
        					<input type="text" id="keterangan2" name="keterangan2" size="40"/>
        				</div>
        				<div class="clear"></div>
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Selaku PPs.</label>
        					<input type="text" id="keterangan2_pps" name="keterangan2_pps" size="40"/>
        				</div>
        				<div class="clear"></div>							
							</fieldset>	 
						</div>
						
            <div class="div-col" style="width:1%;">
            </div>	
						
						<div class="div-col-right" style="width:50%;">
							<fieldset><legend><span id="span_legend_diatasotorisasi"></span></legend>
        				<div class="form-row_kiri">
        					<label  style = "text-align:right;"><i><font color="#009999">Yang Mengajukan:</font></i></label>      					
        				</div>										
        				<div class="clear"></div>
  							
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Pejabat</label>
        					<input type="text" id="pejabat3" name="pejabat3" size="40"/>
        				</div>
        				<div class="clear"></div>
  							
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Jabatan</label>
        					<input type="text" id="keterangan3" name="keterangan3" size="40"/>
        				</div>
        				<div class="clear"></div>
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Selaku PPs.</label>
        					<input type="text" id="keterangan3_pps" name="keterangan3_pps" size="40"/>
        				</div>
        				<div class="clear"></div>
  																					
  							<br/>
  																																																	
        				<div class="form-row_kiri">
        					<label style = "text-align:right;"><i><font color="#009999">Yang Mengetahui:</font></i></label>      					
        				</div>
        				<div class="clear"></div>		
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Pejabat</label>
        					<input type="text" id="pejabat4" name="pejabat4" size="40"/>
        				</div>	
  							<div class="clear"></div>
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Jabatan</label>
        					<input type="text" id="keterangan4" name="keterangan4" size="40"/>
        				</div>
        				<div class="clear"></div>
  
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Selaku PPs.</label>
        					<input type="text" id="keterangan4_pps" name="keterangan4_pps" size="40"/>
        				</div>
        				<div class="clear"></div>							
							</fieldset>							
						</div>						 
					</div>

          <div class="div-row" >
						<div class="div-col" style="width:99%; max-height: 100%;">
							<fieldset><legend>Parameter Laporan</legend>
        				<div class="form-row_kiri">
        				<label  style = "text-align:right;">Tgl Verifikasi</label>		
                  <input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" onblur="convert_date(tglawaldisplay)" style="border:0;text-align:center;width:90px;height:18px;" onClick="return showCalendar('tglawaldisplay', 'dd-mm-y');">  
                  &nbsp;s/d&nbsp;
                  <input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" onblur="convert_date(tglakhirdisplay)" style="border:0;text-align:center;width:90px;height:18px;" onClick="return showCalendar('tglakhirdisplay', 'dd-mm-y');">							
                </div>
								<div class="clear"></div>																												
							</fieldset>
						</div>
					</div>							
					
				</div>
				
				<div id="div_footer" class="div-footer"> 		 
          <div class="div-footer-form">
            <div class="div-row">
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doCetak" href="#" onClick="fjq_ajax_val_doCetak();">
                        <img src="../../images/ico_cetak.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
                        <span>CETAK</span>
                      </a>
                    </div>
                  </div>
                </div>																																																									
            </div>
          </div>
          
          <div style="padding-top:6px;width:99%;">
						<div class="div-footer-content">
							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
							<li style="margin-left:15px;"><font color="#ff0000"> Data yang ditamplkan </font> pada laporan adalah data pembayaran jaminan yang <font color="#ff0000"> sudah</font> dilakukan <font color="#ff0000"> verifikasi siap bayar</font>.</li>
							<li style="margin-left:15px;">Apabila sudah berhasil dilakukan pembayaran maka data siap bayar tersebut tidak akan ditampilkan lagi pada laporan.</li>
							<li style="margin-left:15px;">Klik tombol <font color="#ff0000"> <b>X</b> </font> pada pojok kanan atas untuk menutup form dan kembali ke menu utama.</li>
						</div>													
          </div>
			
				</div>
				<!--end div_footer--> 	
								
			</div>					
		</form>	 
	</div>	 
</div>

<script language="javascript">
	//template -------------------------------------------------------------------
	$(document).ready(function(){
    $(window).bind("resize", function(){
			resize();
		});
		
		resize();
		
		fl_js_set_tgl_display('<?=$ld_tglawaldisplay;?>','<?=$ld_tglakhirdisplay;?>');
		fl_js_show_pejabat();
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

	function reloadPage()
  {
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }	
	//end template ---------------------------------------------------------------			
</script>

<script language="javascript">
	//show data pejabat otorisasi ------------------------------------------------			
  function fl_js_show_pejabat(fn)
  {					
    var fn;
    v_kode_kantor = $('#kode_kantor').val();
		
		if (v_kode_kantor == '') {
    	return alert('Kode kantor kosong, mohon login ulang kemudian ulangi proses...');
    }
					
    asyncPreload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5063_action.php?"+Math.random(),
      data: {
        tipe: 'fjq_ajax_val_getdatapejabatcetak',
        v_kode_kantor: v_kode_kantor
      },
      success: function(data){
        try {
          jdata = JSON.parse(data);
          if (jdata.ret == 0) 
          {          
						v_batas_otorisasi = getValue(jdata.BATAS_OTORISASI);
						v_text_batas_otorisasi = v_batas_otorisasi/1000000;
						
						$('#span_legend_dibawahotorisasi').html('Pejabat Penandatangan Otorisasi Dibawah '+v_text_batas_otorisasi+' Juta');
						$('#span_legend_diatasotorisasi').html('Pejabat Penandatangan Otorisasi Diatas '+v_text_batas_otorisasi+' Juta');
						
						//set pejabat ------------------------------------------------------
						$('#pejabat1').val(getValue(jdata.DATA[0]['PEJABAT1']));
						$('#keterangan1').val(getValue(jdata.DATA[0]['KETERANGAN1']));
						$('#keterangan1_pps').val(getValue(jdata.DATA[0]['KETERANGAN1_PPS']));
						
						$('#pejabat2').val(getValue(jdata.DATA[0]['PEJABAT2']));
						$('#keterangan2').val(getValue(jdata.DATA[0]['KETERANGAN2']));
						$('#keterangan2_pps').val(getValue(jdata.DATA[0]['KETERANGAN2_PPS']));

						$('#pejabat3').val(getValue(jdata.DATA[0]['PEJABAT3']));
						$('#keterangan3').val(getValue(jdata.DATA[0]['KETERANGAN3']));
						$('#keterangan3_pps').val(getValue(jdata.DATA[0]['KETERANGAN3_PPS']));
						
						$('#pejabat4').val(getValue(jdata.DATA[0]['PEJABAT4']));
						$('#keterangan4').val(getValue(jdata.DATA[0]['KETERANGAN4']));
						$('#keterangan4_pps').val(getValue(jdata.DATA[0]['KETERANGAN4_PPS']));
						//end set pejabat --------------------------------------------------
						
            //end generate layout rincian penerima manfaat ---------------------
            if (fn && fn.success) {
            	fn.success();
            }
      		} else {
						alert("Tidak ada data yanga akan ditampilkan...");
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
	//end show data pejabat otorisasi --------------------------------------------
	
  //do cetak laporan siap bayar ------------------------------------------------
  function fjq_ajax_val_doCetak()
  {				 
  	var v_kode_kantor			= $('#kode_kantor').val();
  	var v_pejabat1				= $('#pejabat1').val();
  	var v_keterangan1			= $('#keterangan1').val();
		var v_keterangan1_pps	= $('#keterangan1_pps').val();

  	var v_pejabat2				= $('#pejabat2').val();
  	var v_keterangan2			= $('#keterangan2').val();
		var v_keterangan2_pps	= $('#keterangan2_pps').val();

  	var v_pejabat3				= $('#pejabat3').val();
  	var v_keterangan3			= $('#keterangan3').val();
		var v_keterangan3_pps	= $('#keterangan3_pps').val();

  	var v_pejabat4				= $('#pejabat4').val();
  	var v_keterangan4			= $('#keterangan4').val();
		var v_keterangan4_pps	= $('#keterangan4_pps').val();
						
    if (v_kode_kantor == ''){
    	alert('Kode kantor kosong, mohon login ulang kemudian ulangi proses...');	 						 				 								 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_docetaksiapbayar',
  						v_kode_kantor			:v_kode_kantor,
              v_pejabat1				:v_pejabat1,
							v_keterangan1			:v_keterangan1,
							v_keterangan1_pps	:v_keterangan1_pps,
              v_pejabat2				:v_pejabat2,
							v_keterangan2			:v_keterangan2,
							v_keterangan2_pps	:v_keterangan2_pps,
							v_pejabat3				:v_pejabat3,
							v_keterangan3			:v_keterangan3,
							v_keterangan3_pps	:v_keterangan3_pps,
              v_pejabat4				:v_pejabat4,
							v_keterangan4			:v_keterangan4,
							v_keterangan4_pps	:v_keterangan4_pps
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //cetak laporan siap bayar ---------------
						fl_js_callcetak();							
          }else{
            //approval konfirmasi berkala gagal -------------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do cetak laporan siap bayar --------------------------------------------

	function fl_js_callcetak()
  {
		document.formreg.task.value = 'CetaK';
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
</script>

<script language="javascript">
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
	
	function fl_js_set_tgl_display(v_tgl_awal, v_tgl_akhir)
	{
	 	if (v_tgl_awal=='' || v_tgl_akhir=='')
		{
		 	fl_js_set_tgl_sysdate('tglawaldisplay');
			fl_js_set_tgl_sysdate('tglakhirdisplay'); 
		}else
		{
		 	$('#tglawaldisplay').val(v_tgl_awal);
			$('#tglakhirdisplay').val(v_tgl_akhir);	 
		}			 
	}					
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>

