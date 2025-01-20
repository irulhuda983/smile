<?php
//------------------------------------------------------------------------------
// Menu untuk penggantian anak penerima beasiswa
// dibuat tgl : 11/01/2021
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			 	= "form";
$gs_kodeform 	 	 	= "PN5002";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "PENGGANTIAN ANAK PENERIMA MANFAAT BEASISWA";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_nik_penerima 	= !isset($_POST['nik_penerima']) ? $_GET['nik_penerima'] : $_POST['nik_penerima'];
$ls_nama_penerima = !isset($_POST['nama_penerima']) ? $_GET['nama_penerima'] : $_POST['nama_penerima'];
$ls_nik_tk 				= !isset($_POST['nik_tk']) ? $_GET['nik_tk'] : $_POST['nik_tk'];
$ls_nama_tk 			= !isset($_POST['nama_tk']) ? $_GET['nama_tk'] : $_POST['nama_tk'];
$ld_tgl_rekam 		= !isset($_POST['tgl_rekam']) ? $_GET['tgl_rekam'] : $_POST['tgl_rekam'];
$ls_task_detil	 	= !isset($_POST['task_detil']) ? $_GET['task_detil'] : $_POST['task_detil'];
$task							= $ls_task_detil;
//$gs_pagetitle			= $gs_pagetitle." UNTUK TAHUN ".$ls_tahun;
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
			<input type="hidden" id="nik_tk" name="nik_tk" value="<?=$ls_nik_tk;?>">
			<input type="hidden" id="nama_tk" name="nama_tk" value="<?=$ls_nama_tk;?>">
			<input type="hidden" id="tgl_rekam" name="tgl_rekam" value="<?=$ld_tgl_rekam;?>">
			
			<div id="div_container" class="div-container">
				<div id="div_body" class="div-body" style="padding-top:0px;">
					<div class="div-row" >
						<div class="div-col" style="width:100%;">
							<fieldset><legend>Anak yang akan diganti karena Kondisi Akhir Meninggal Dunia: </legend>
      					</br>
								
								<div class="form-row_kiri">
                <label  style = "text-align:right;">NIK Anak</label>
                  <input type="text" id="nik_penerima" name="nik_penerima" style="width:250px;" tabindex="1" value="<?=$ls_nik_penerima;?>" readonly class="disabled">			
                </div>																		
                <div class="clear"></div>

      					<div class="form-row_kiri">
                <label  style = "text-align:right;">Nama Anak</label>
                  <input type="text" id="nama_penerima" name="nama_penerima" style="width:250px;" tabindex="2" value="<?=$ls_nama_penerima;?>" readonly class="disabled">			
                </div>																		
                <div class="clear"></div>

								<div class="form-row_kiri">
                <label  style = "text-align:right;">Tgl Kematian *</label>
                  <input type="text" id="tgl_kematian" name="tgl_kematian" maxlength="10" tabindex="3" onblur="convert_date(tgl_kematian);fl_js_cek_tgl_kematian();" onfocus="fl_js_cek_tgl_kematian();" style="width:222px;background-color:#ffff99;">
                  <input id="btn_tgl_kematian" type="image" align="top" onclick="return showCalendar('tgl_kematian', 'dd-mm-y');" src="../../images/calendar.gif" style="height:14px;"/>     																			 								
  							</div>
                <div class="clear"></div>
																	
                <div class="form-row_kiri">
                <label style = "text-align:right;">Keterangan &nbsp;</label>
                	<textarea cols="255" rows="1" style="width:250px;height:50px;background-color:#ffff99;" id="keterangan" name="keterangan" tabindex="4" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onkeyup="this.value = this.value.toUpperCase();"></textarea>   					
                </div>								
                <div class="clear"></div>

                <div class="form-row_kiri">
                <label  style = "text-align:right;">Dokumen *</label>
                  <input type="text" id="nama_dokumen" name="nama_dokumen" maxlength="100" tabindex="5" style="width:230px;background-color:#ffff99;">
                  <a href="javascript:void(0)" onclick="fl_js_UploadDok()"><img src="../../images/downloadx.png" border="0" alt="Tambah" align="absmiddle" style="height:22px;"> upload</a>
                  <input type="hidden" id="nama_file_dokumen" name="nama_file_dokumen" maxlength="100" style="width:180px;" readonly class="disabled">
                  <input type="hidden" id="no_urut_dokumen" name="no_urut_dokumen">
                  <input type="hidden" id="url_dokumen" name="url_dokumen">
                  <span id="span_download_file"></span>
                </div>																		
                <div class="clear"></div>
								
								</br>																																							
							</fieldset>
							
							<fieldset><legend>Anak tersebut akan digantikan oleh: </legend>
      					</br>
								
								<div class="form-row_kiri">
                <label  style = "text-align:right;">NIK Pengganti</label>
                  <input type="text" id="nik_pengganti" name="nik_pengganti" style="width:200px;background-color:#ffff99;" tabindex="6" maxlength="16" onblur="fjq_ajax_val_nikbyadminduk();">			
                	<input type="checkbox" id="cb_valid_nik_pengganti" name="cb_valid_nik_pengganti" disabled class="cebox"><i><font color="#009999">Valid</font></i>
									<input type="hidden" id="nik_pengganti_old" name="nik_pengganti_old">
								</div>																		
                <div class="clear"></div>

      					<div class="form-row_kiri">
                <label  style = "text-align:right;">Nama</label>
                  <input type="text" id="nama_pengganti" name="nama_pengganti" style="width:250px;" tabindex="7" readonly class="disabled">			
                </div>																		
                <div class="clear"></div>

								</br>																																							
							</fieldset>								 
						</div>	 
					</div>	 
				</div>	 
				<!--end div_body -->

				<div id="div_footer" class="div-footer">
					<div class="div-footer-form" style="width:95%;">
						<div class="div-row">
              <span id="span_button_new" style="display:block;">
                <div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doSaveInsert" href="#" onClick="if(confirm('Apakah anda yakin akan mensubmit penggantian anak penerima manfaat beasiswa..?')) fjq_ajax_val_doButtonSubmit();">
                      <img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                      <span>SUBMIT DATA PENGGANTIAN&nbsp;</span>
                      </a>											
                    </div>
                  </div>
                </div>  									
              </span>

              <div class="div-col">
                <div class="div-action-footer">
                  <div class="icon">
                    <a id="btn_doBack2Grid" href="#" onClick="fjq_ajax_val_doButtonTutup();">
                      <img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                      <span>TUTUP</span>
                    </a>
                  </div>
                </div>
              </div>															 
						</div>	 
					</div>
					<!--end div-footer-form -->	
					
					<div style="padding-top:6px;">
            <span id="span_footer_keterangan_new" style="display:block;">
              <div class="div-footer-content" style="width:93%;">
              <div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
              <li style="margin-left:15px;">Klik <img src="../../images/help.png" alt="Cari Data Peserta" border="0" align="absmiddle"> untuk <font color="#ff0000">memilih</font> Nomor Identitas Peserta.
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
		
		fjq_ajax_val_initval();		 
	});
	
	function fjq_ajax_val_initval()
	{
	 	var v_nama_dokumen = "SURAT KEMATIAN/AKTE KEMATIAN";
		$('#nama_dokumen').val(v_nama_dokumen); 
	}
	
	//do klik tombol submit utk validasi apakah bisa dilakukan penggantian -------
  function fjq_ajax_val_doButtonSubmit()
  {
		var v_nik_tk 						= $('#nik_tk').val();
		var v_nama_tk 					= $('#nama_tk').val();
		var v_nik_penerima 			= $('#nik_penerima').val();
		var v_nik_penerima 			= $('#nik_penerima').val();
		var v_tgl_kematian 			= $('#tgl_kematian').val();
		var v_keterangan 	 			= $('#keterangan').val();
		var v_nama_dokumen 			= $('#nama_dokumen').val();
		var v_nama_file_dokumen = $('#nama_file_dokumen').val();
		var v_nik_pengganti 		= $('#nik_pengganti').val();
		var v_ket_submit				= 'PENGGANTIAN NIK '+v_nik_penerima+' KARENA MENINGGAL DUNIA PADA TGL '+v_tgl_kematian+' KE NIK '+v_nik_pengganti+' ('+v_keterangan+')';
		v_ket_submit 						= v_ket_submit.substring(0, 299);
		
    if (v_nik_penerima == ''){
     	alert('NIK Anak yang akan diganti kosong, harap menutup form PENGGANTIAN kemudian mengulangi proses..!');
			$('#nik_penerima').focus();
		}else if (v_nik_tk == '' || v_nama_tk == ''){
     	alert('NIK/Nama TK kosong, harap menutup form PENGGANTIAN kemudian mengulangi proses..!');
			$('#nik_penerima').focus();				
		}else if (v_tgl_kematian == ''){
     	alert('Tgl Kematian kosong, harap melengkapi data input..!');
			$('#tgl_kematian').focus();				
		}else if (v_keterangan == ''){
     	alert('Keterangan kosong, harap melengkapi data input..!');
			$('#keterangan').focus();
		}else if (v_nama_dokumen == ''){
     	alert('Nama Dokumen kosong, harap melengkapi data input..!');
			$('#nama_dokumen').focus();
		}else if (v_nama_file_dokumen == ''){
     	alert('Harap melakukan upload dokumen terlebih dahulu..!');
			$('#nama_dokumen').focus();
		}else if (v_nik_pengganti == ''){
     	alert('NIK Pengganti kosong, harap melengkapi data input..!');
			$('#nik_pengganti').focus();			
		}else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_penggantian_penerima_beasiswa',
              v_nik_penerima:v_nik_penerima,
							v_keterangan:v_ket_submit,
							v_nik_pengganti:v_nik_pengganti
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //submit berhasil, masuk ke form profile penerima pengganti --------
						var v_nik_penerima_induk = v_nik_penerima;
						var v_ket_penggantian = v_ket_submit;
						
						window.parent.Ext.WindowManager.getActive().parent.fl_js_doPostGantiPenerimaSetTaskNew(v_nik_tk, v_nama_tk, v_nik_pengganti, v_nik_penerima_induk, v_ket_penggantian);   			
      			window.parent.Ext.WindowManager.getActive().close();
						
						var v_msg = jdata.msg+', HARAP MELENGKAPI PROFIL ANAK PENGGANTI PENERIMA BEASISWA'; 
						alert(v_msg);														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do klik tombol submit utk validasi apakah bisa dilakukan penggantian ---
	
	//do klik tombol tutup -------------------------------------------------------
  function fjq_ajax_val_doButtonTutup()
  {
		window.parent.Ext.WindowManager.getActive().close();
  }
  //end do klik tombol tutup ---------------------------------------------------
	
	function fl_js_UploadDok()
	{
		var v_nik_penerima = $('#nik_penerima').val();
		var v_nama_dokumen = $('#nama_dokumen').val();
		var v_no_urut_dokumen = $('#no_urut_dokumen').val();
		
		if (v_nama_dokumen=='')
		{
		 	alert('NAMA DOKUMEN KOSONG, HARAP MELENGKAPI DATA INPUT..!');
		}else
		{					 
    	let params = 'p=pn5073.php&a=formreg&nik_penerima='+v_nik_penerima+'&nama_dokumen='+v_nama_dokumen+'&no_urut_dokumen='+v_no_urut_dokumen+'&sender=pn5073_gantipenerima.php&jenis_dokumen=GANTI_PENERIMA';
    	NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5073_uploaddok.php?' + params,'upload_lampiran',550,400,'yes');
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
	
  //validasi nik penerima ------------------------------------------------------
  function fjq_ajax_val_nikbyadminduk()
  {				 
    var v_nik_pengganti  = $('#nik_pengganti').val();
		var v_nik_pengganti_old  = $('#nik_pengganti_old').val();

    if (v_nik_pengganti!='' && v_nik_pengganti != v_nik_pengganti_old)
    {
        preload(true);
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
          data: { tipe:'fjq_ajax_val_nikbyadminduk', NIK:v_nik_pengganti},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            if(jdata.ret=="0")
            {   
              $('#nik_pengganti_old').val(v_nik_pengganti);
							$('#nama_pengganti').val(jdata.data['namaLengkap']);
														
							window.document.getElementById('cb_valid_nik_pengganti').checked = true;															
            }else{
              //nik tidak valid, data penerima diinputkan manual ---------------
							$('#nik_pengganti_old').val(v_nik_pengganti);
							$('#nama_pengganti').val('');
							
							window.document.getElementById('cb_valid_nik_pengganti').checked = false;
							
              alert('Gagal validasi NIK, harap melakukan input manual setelah dilakukan proses submit penggantian anak penerima beasiswa...');
            }
          }
        });
    }else
    { 
			$('#nama_pengganti').val('');
			alert('NIK Pengganti tidak boleh kosong..');
    }
  }
  //end validasi nik penerima --------------------------------------------------
	
	function fl_js_cek_tgl_kematian()
	{
	 	var v_tgl_kematian = $('#tgl_kematian').val();
		var v_tgl_rekam_nikdiganti = $('#tgl_rekam').val();
		var v_tgl_rekam_nikdiganti_yyymmdd = v_tgl_rekam_nikdiganti.substring(6, 10)+v_tgl_rekam_nikdiganti.substring(3, 5)+v_tgl_rekam_nikdiganti.substring(0, 2);
		
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
		
		var v_tgl_kematian_yyymmdd = "";				
		if (v_tgl_kematian != "")
		{ 
			v_tgl_kematian_yyymmdd = v_tgl_kematian.substring(6, 10)+v_tgl_kematian.substring(3, 5)+v_tgl_kematian.substring(0, 2);
			
			if (v_tgl_kematian_yyymmdd > v_today_yyyymmdd)
			{
			 	$('#tgl_kematian').val('');
				alert('TGL KEMATIAN TIDAK BOLEH LEBIH BESAR DARI HARI INI..!'); 
			} else if (v_tgl_kematian_yyymmdd < v_tgl_rekam_nikdiganti_yyymmdd)
			{
			 	$('#tgl_kematian').val('');
				alert('TGL KEMATIAN '+v_tgl_kematian+' TIDAK BOLEH LEBIH KECIL DARI TGL PEREKAMAN DATA NIK YG DIGANTI ('+v_tgl_rekam_nikdiganti+') ..!'); 
			}
		}
	}				 				
</script>
		
<?php
include "../../includes/footer_app_nosql.php";
?>
