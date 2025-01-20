<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";

$pagetype    = "form";
$gs_pagetitle = $_GET['page_title'];

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$USER        = $_SESSION["USER"];
$KD_KANTOR   = $_SESSION['kdkantorrole'];
$KODE_ROLE   = $_SESSION['regrole'];
$periodeawal = $_GET['periodeawal'];	
$periodeakhir = $_GET['periodeakhir'];
$ls_kode_klaim = $_POST["kode_klaim"] == "" ? $_GET["kode_klaim"] : $_POST["kode_klaim"];
$ls_act = $_POST["act"] == "" ? $_GET["act"] : $_POST["act"];

$arr_dapat = array();
$arr_tidakdapat = array();
$sqlkantor = "select kode_kantor from ms.ms_kantor where kode_tipe in ('1','2','0')";
$DB->parse($sqlkantor);
	$DB->execute();
//get kantor except
	while ($row = $DB->nextrow()) {
		$arr_tampung_kd_kantor_exc[] = $row;
	}

	foreach($arr_tampung_kd_kantor_exc as $row) {
		$arr_kd_kantor_exc [] = $row['KODE_KANTOR'];
	}
							
	if(in_array($KD_KANTOR,$arr_kd_kantor_exc)){
		$kd_kantor_valid = 'true';
	}else{
		$kd_kantor_valid = 'false';
	}

	// print_r($kd_kantor_valid);

$sqldapat = "
	SELECT A.* 
	FROM MS.MS_LOOKUP A 
    WHERE TIPE = 'HSLCEKBEA' 
    and kategori = 'DAPAT'
	";

	

	$DB->parse($sqldapat);
	$DB->execute();

	while ($row = $DB->nextrow()) {
		$arr_dapat[] = $row;
	}

$sqltidakdapat = "
	SELECT A.* 
	FROM MS.MS_LOOKUP A 
    WHERE TIPE = 'HSLCEKBEA' 
    and kategori = 'TDKDAPAT'
	";

	

	$DB->parse($sqltidakdapat);
	$DB->execute();

	while ($row = $DB->nextrow()) {
		$arr_tidakdapat[] = $row;
	}	




	$sql = "
	 select 
          kode_kantor,
					kode_pointer_tindak_lanjut,
          (select nama_kantor from kn.vw_kn_ms_kantor_report where kode_kantor=a.kode_kantor) nama_kantor,
           kode_wilayah,
          (select nama_wilayah from kn.vw_kn_ms_kantor_report where kode_kantor=a.kode_kantor) nama_wilayah,
         substr(kode_tipe_klaim,1,3) jenis_klaim,
          kode_segmen, 
           to_char(tgl_tindak_lanjut,'dd/mm/yyyy') tgl_tindak_lanjut,
          petugas_tindak_lanjut,
          decode(status_tindak_lanjut,'T','Belum Ditindaklanjuti','Sudah Ditindaklanjuti')status_tindak_lanjut,
          nama_tk,
          kpj,
          nomor_identitas nik_tk,
          to_char(tgl_kejadian,'dd/mm/yyyy') tgl_kejadian ,
          kode_klaim kode_klaim_induk,
           to_char(tgl_bayar_klaim,'dd/mm/yyyy')  tgl_bayar_klaim_induk, 
          no_hp_penerima_manfaat,
          email_penerima_manfaat,
          flag_dapat_beasiswa,
          kode_hasil_pengecekan,
          jml_anak_penerima_beasiswa,
          keterangan,
          tgl_rekam
        from
         pn.pn_klaim_monitoring_beasiswa a
	where kode_klaim = '$ls_kode_klaim' ";	
		


	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	
	$ls_kode_wilayah	   = $row["KODE_WILAYAH"];
	$ls_kode_kantor	   = $row["KODE_KANTOR"];
	$ls_nama_kantor	   = $row["NAMA_KANTOR"];
	$ls_jenis_klaim	   = $row["JENIS_KLAIM"];
	$ls_kode_segmen	   = $row["KODE_SEGMEN"];
	$ls_nama_tk	   = $row["NAMA_TK"];
	$ls_kpj	   = $row["KPJ"];
	$ls_nik_tk	   = $row["NIK_TK"];
	$ls_tanggal_kejadian	   = $row["TGL_KEJADIAN"];
	$ls_kode_klaim	   = $row["KODE_KLAIM_INDUK"];
	$ls_tanggal_bayar	   = $row["TGL_BAYAR_KLAIM_INDUK"];
	$ls_no_hp_penerima	   = $row["NO_HP_PENERIMA_MANFAAT"];
	$ls_email_penerima	   = $row["EMAIL_PENERIMA_MANFAAT"];
	$ls_flag_dapat_beasiswa	   = $row["FLAG_DAPAT_BEASISWA"];
	$ls_jml_anak_penerima	   = $row["JML_ANAK_PENERIMA_BEASISWA"];
	$ls_keterangan	   = $row["KETERANGAN"];
	$ls_status_tindaklanjut	   = $row["STATUS_TINDAK_LANJUT"];
	$ls_petugas_tindaklanjut	   = $row["PETUGAS_TINDAK_LANJUT"];
	$ls_tanggal_tindaklanjut	   = $row["TGL_TINDAK_LANJUT"];
	$ls_kode_hasil_pengecekan	   = $row["KODE_HASIL_PENGECEKAN"];
	$ls_kode_pointer_tindak_lanjut = $row["KODE_POINTER_TINDAK_LANJUT"];
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script language="javascript">
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val($(this).val().toUpperCase());

	});

	showHide();
	loadData()
 	
  	$(window).bind("resize", function(){
			resize();
		});
		resize();
		// filter();
		/** list checkbox npp */
		window.list_npp = [];
	});
	var asyncPreloadStart;

	
	function loadData()
	{
		let flag_dapat_beasiswa = getValue($('#flag_dapat_beasiswa').val());
	 	let kode_hasil_pengecekan = getValue($('#kode_hasil_pengecekan').val());	
		let kode_pointer_tindak_lanjut = getValue($('#kode_pointer_tindak_lanjut').val());	
	
	 	document.getElementById("alasan_hasil_check_dapat").value = kode_hasil_pengecekan;
	 	document.getElementById("alasan_hasil_check_tidak").value = kode_hasil_pengecekan;
	 	document.getElementById("gred").value = flag_dapat_beasiswa;
	 	 

	 	if (flag_dapat_beasiswa == 'Y' && kode_pointer_tindak_lanjut != 'PN5004'){
			$('#dapat_beasiswa').show(); 
                  		
		}else if (flag_dapat_beasiswa == 'T' && kode_pointer_tindak_lanjut != 'PN5004'){
			
      $('#tidak_dapat_beasiswa').show(); 	
		}

	}	

	function asyncPreloadX(state){
		if (state == true) {
			asyncPreloadStart = setInterval(function() {
				$('#loading').show();
				$('#loading-mask').show();
			}, 50);
		} else {
			$('#loading').hide();
			$('#loading-mask').hide();
			clearInterval(asyncPreloadStart);
		}
	}
	
	function getValue(val){
		return val == null || val == undefined ? '' : val;
	}

	function search_by_changed(){
		$("#search_txt").val("");
	}

	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
	
	function validateDigit(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode(key);
		var regex = /[0-9]|\./;
		if (!regex.test(key)) {
				theEvent.returnValue = false;
				if (theEvent.preventDefault)
						theEvent.preventDefault();
		}
  }
	
	function Comma(Num) { //function to add commas to textboxes
		Num += '';
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		Num = Num.replace(',', '');
		x = Num.split('.');
		x1 = x[0];
		x2 = x.length > 1 ? '.' + x[1] : '';
		var rgx = /(\d+)(\d{3})/;
		while (rgx.test(x1))
				x1 = x1.replace(rgx, '$1' + ',' + '$2');
		return x1 + x2;
	}

	function isNumberKey(evt) {
		var theEvent = evt || window.event;
		var key = theEvent.keyCode || theEvent.which;
		key = String.fromCharCode(key);
		if (key.length == 0)
			return;
		var regex = /^[0-9\b]+$/;
		if (!regex.test(key)) {
			theEvent.returnValue = false;
			if (theEvent.preventDefault)
				theEvent.preventDefault();
		}
	}

	function resize(){
		$("#div_container").width($("#div_dummy").width());
		
		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());
		
		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);
		$("#div_footer").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
		$("#div_footer").width($("#div_dummy_data").width());

		$("#div_container").css('max-height', $(window).height());

		let margin_height = 40;
		let filter_height = $('#div_body').height() - $('#div_data').height() + margin_height;
		$('#div_data').css('max-height', $(window).height() - $('#div_header').height() - $('#div_page').height() - $('#div_footer').height() - filter_height);
	}
	
	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title: title,
			msg: msg,
			buttons: window.parent.Ext.Msg.YESNO,
			icon: window.parent.Ext.Msg.QUESTION,
			fn: function(btn) {
				if (btn === 'yes') {
					fnYes();
				} else {
					fnNo();
				}
			}
		});
	}

	function showFormReload(mypage, myname, w, h, scroll) {
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					resubmit();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function showFormRefilter(mypage, myname, w, h, scroll) {
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
					filter();
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
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
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-y:hidden; overflow-x:hidden; overflow:hidden;" scrolling="no"></iframe>',
			listeners: {
				close: function () {
				},
					destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}
	
	

	function kembali(periodeawal,periodeakhir){			
		
		window.location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5059.php?periodeawal='+periodeawal+'&periodeakhir='+periodeakhir+'');	

	}
	
	
	function edit(){
		let periodeawal = getValue($('#periodeawal').val());
		let periodeakhir = getValue($('#periodeakhir').val());
		let kode_klaim = getValue($('#kode_klaim').val());
		let kode_kantor = getValue($('#kode_kantor').val());
		let flag_dapat_beasiswa = getValue($('#gred').val());
		let alasan_hasil_check = '';	
		let alasan_hasil_check_dapat =  getValue($('#alasan_hasil_check_dapat').val());
		let alasan_hasil_check_tidak =  getValue($('#alasan_hasil_check_tidak').val());		
		let keterangan = getValue($('#keterangan').val());		
		
		if (flag_dapat_beasiswa == '-1') {
			return alert('Silahkan pilih Hasil Pengecekan');
		}
		if (keterangan == '' || keterangan == null) {
			return alert('Silahkan isi Keterangan');
		}  

		if (flag_dapat_beasiswa == 'T') {
			if (alasan_hasil_check_tidak == '') {
				return alert('Silahkan pilih Alasan Hasil Pengecekan Beasiswa');
			} 
		} else if (flag_dapat_beasiswa == 'Y'){
			if (alasan_hasil_check_dapat == '') {
				return alert('Silahkan pilih Alasan Hasil Pengecekan Beasiswa');
			} 
		}

		

		console.log(kode_klaim);
		console.log(kode_kantor);
		console.log(flag_dapat_beasiswa);
			console.log(alasan_hasil_check_dapat);
		console.log(alasan_hasil_check_tidak);
		console.log(keterangan);

       if (flag_dapat_beasiswa == 'Y'){
            alasan_hasil_check = alasan_hasil_check_dapat;
       }else{
            alasan_hasil_check = alasan_hasil_check_tidak;
       }
		

		preload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5059_action.php?"+Math.random(),
			data: {
				tipe: 'edit',					
				kode_klaim : kode_klaim,
				flag_dapat_beasiswa: flag_dapat_beasiswa,
				alasan_hasil_check: alasan_hasil_check,
				keterangan: keterangan	,
				kode_kantor : kode_kantor			
			},
			success: function(data){
				jdata = JSON.parse(data);
				if (jdata.ret == 1){
					alert("Tindak lanjut hasil pengecekan beasiswa berhasil dilakukan");
					location.replace('http://<?= $HTTP_HOST; ?>/mod_pn/form/pn5059.php?periodeawal='+periodeawal+'&periodeakhir='+periodeakhir+'');
				} else {
					alert(jdata.msg);
				}
				preload(false);
			},
			complete: function(){
				preload(false);
			},
			error: function(){
				alert("Tindak lanjut hasil pengecekan beasiswa tidak berhasil dilakukan");
				preload(false);
			}
		});
	}

	

	function showHide(selValue){
            switch(selValue){
                case "Y": 
                    $('#dapat_beasiswa').show(); 
                    $('#tidak_dapat_beasiswa').hide(); 
                    break;
                case "T":                    
                   $('#dapat_beasiswa').hide(); 
                    $('#tidak_dapat_beasiswa').show();                     
                    break;
            }
        }	




   
   

	
	
</script>


<div id="actmenu">
	<h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF">Tindak Lanjut Monitoring Pengecekan Manfaat Beasiswa PP Nomor 82 Tahun 2019</h3> 
</div>
<div id="formframe">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri" style="margin-left: 30px;">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<div id="div_container" class="div-container">
				<div id="div_header" class="div-header">
				</div>
				<div id="test" style="width: 950px;">
					<div id="div_dummy_data" style="width: 100%;"></div>
					<input type='hidden' id='periodeakhir' value='<?php echo $periodeakhir;?>'/>
					<input type='hidden' id='periodeawal' value='<?php echo $periodeawal;?>'/>
					<input type="hidden" name="user" id="user" value="<?=$USER?>">					
					<input type="hidden" name="status_tindaklanjut" id="status_tindaklanjut" value="<?=$ls_status_tindaklanjut?>">
					<input type="hidden" name="kode_hasil_pengecekan" id="kode_hasil_pengecekan" value="<?=$ls_kode_hasil_pengecekan?>">
					<input type="hidden" name="flag_dapat_beasiswa" id="flag_dapat_beasiswa" value="<?=$ls_flag_dapat_beasiswa?>">
					<input type="hidden" name="kode_pointer_tindak_lanjut" id="kode_pointer_tindak_lanjut" value="<?=$ls_kode_pointer_tindak_lanjut?>">
				


					<div class="clear"></div>  
					
					<div class="clear" style="padding-bottom: 4px;"></div>					
					<div class="form-row_kiri"> 
						<label style = "text-align:right;">Kode Kantor Cabang : </label>
						<input type="text" id="kode_kantor" name="kode_kantor"  style="width:300px; color:#000000;"   class="" value="<?=$ls_kode_kantor ?>" disabled>
					</div>
					<div class="form-row_kanan">
						<label style = "text-align:right;">Status Tindaklanjut : </label>
						<input type="text" id="status_tindaklanjut" name="status_tindaklanjut"  style="width:300px; color:#000000;"   class="" value="<?=$ls_status_tindaklanjut ?>" disabled>
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Nama Kantor Cabang : </label>
						<input type="text" id="nama_kantor" name="nama_kantor"  style="width:300px; color:#000000;"   class="" value="<?=$ls_nama_kantor ?>" disabled>
					</div>
					<div class="form-row_kanan">
						<label style = "text-align:right;">Tanggal Tindaklanjut : </label>
							<input type="text" id="tgltindaklanjut" name="tgltindaklanjut" value="<?=$ls_tanggal_tindaklanjut;?>" style="width: 80px; color:#000000;" size="9" disabled >  
									<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgltindaklanjut', 'dd-mm-y');" src="../../images/calendar.gif" / disabled="">
									<label style="width:196px; "  >
					</div>
					<div class="clear"></div>
				
					
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Jenis Klaim : </label>
						<input type="text" id="jenis_klaim" name="jenis_klaim"  style="width:300px; color:#000000;"   class="" value="<?=$ls_jenis_klaim ?>" disabled>
					</div>
					<div class="form-row_kanan">
						<label style = "text-align:right;">Petugas Tindaklanjut : </label>
						<input type="text" id="petugas_tindaklanjut" name="petugas_tindaklanjut"  style="width:300px; color:#000000;"   class="" value="<?=$ls_petugas_tindaklanjut ?>" disabled>
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Segmen Kepesertaan : </label>
						<input type="text" id="kode_segmen" name="kode_segmen"  style="width:300px; color:#000000;"   class="" value="<?=$ls_kode_segmen ?>" disabled>
					</div>
					<div class="clear"></div>	
					<div class="form-row_kiri">
						<label style = "text-align:right;">Nama TK : </label>
						<input type="text" id="nama_tk" name="nama_tk"  style="width:300px; color:#000000;"   class="" value="<?=$ls_nama_tk ?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Nomor KPJ : </label>
						<input type="text" id="kpj" name="kpj"  style="width:300px; color:#000000;"   class="" value="<?=$ls_kpj?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">NIK TK : </label>
						<input type="text" id="nik_tk" name="nik_tk"  style="width:300px; color:#000000;"   class="" value="<?=$ls_nik_tk ?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Tanggal Kejadian : </label>
							<input type="text" id="tglkejadian" name="tglkejadian" value="<?=$ls_tanggal_kejadian;?>" style="width: 80px;color:#000000;" size="9" disabled >  
									<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglkejadian', 'dd-mm-y');" src="../../images/calendar.gif" / disabled="">
						<!--<input type="text" id="tanggal_kejadian" name="tanggal_kejadian"  style="width:300px;"   class="" value="<?=$ls_tanggal_kejadian ?>" disabled>-->
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Kode Klaim Induk : </label>
						<input type="text" id="kode_klaim" name="kode_klaim"  style="width:300px; color:#000000;"   class="" value="<?=$ls_kode_klaim ?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Tanggal Bayar Klaim Induk : </label>
							<input type="text" id="tglbayar" name="tglbayar" value="<?=$ls_tanggal_bayar;?>" style="width: 80px; color:#000000;" size="9" disabled >  
									<input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tglbayar', 'dd-mm-y');" src="../../images/calendar.gif" / disabled="">
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">No. HP Penerima Manfaat : </label>
						<input type="text" id="no_hp_penerima" name="no_hp_penerima"  style="width:300px; color:#000000;"   class="" value="<?=$ls_no_hp_penerima ?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Email Penerima Manfaat : </label>
						<input type="text" id="email_penerima" name="email_penerima"  style="width:300px; color:#000000;"   class="" value="<?=$ls_email_penerima ?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Jumlah Anak Penerima Beasiswa : </label>
						<input type="text" id="jml_anak_penerima" name="jml_anak_penerima"  style="width:300px; color:#000000;"   class="" value="<?=$ls_jml_anak_penerima ?>" disabled>
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Hasil Pengecekan Beasiswa <span style="color:#ff0000;">*</span> : </label>

	                      <select name="gred" id="gred" onChange="showHide(this.value);" <?=($kd_kantor_valid=="true")? " readonly disabled style=\"width:300px;\"" : " style=\"background-color:#ffff99;width:300px;\"";?> <?php if (trim($ls_status_tindaklanjut) == "Sudah Ditindaklanjuti") { ?> disabled <? } ?> >       
	                          <option value="-1">--Pilih Jawaban--</option>                         
	                          <option value="Y"<?php if($gred=='Y')echo "selected";?>>Dapat Beasiswa</option>
	                          <option value="T"<?php if($gred=='T')echo "selected";?>>Tidak Dapat Beasiswa</option>
	                      </select> 
                   
						<!--<input type="text" id="hasil_pengecekan" name="hasil_pengecekan"  style="width:300px;"   class="" value="<?=$ls_hasil_pengecekan ?>" >-->
					</div>	
					<div class="clear"></div>
					<div id="dapat_beasiswa" class="form-row_kiri" style="display: none;">
						<label style = "text-align:right;">Alasan Hasil Pengecekan Beasiswa <span style="color:#ff0000;">*</span> : </label>
							<select name="alasan_hasil_check_dapat" id="alasan_hasil_check_dapat"  <?=($kd_kantor_valid=="true")? " readonly disabled style=\"width:300px;\"" : " style=\"background-color:#ffff99;width:300px;\"";?> <?php if (trim($ls_status_tindaklanjut) == "Sudah Ditindaklanjuti") { ?> disabled <? } ?>  > 
								<?php 
								foreach($arr_dapat as $row) {										
								 ?>
										<option value="<?=$row["KODE"]?>"> <?=$row["KETERANGAN"]?> </option>
								<?php } ?>   		                        
		                      </select> 
						
					</div>	
					<div id="tidak_dapat_beasiswa" class="form-row_kiri"  style="display: none;">
						<label style = "text-align:right;">Alasan Hasil Pengecekan Beasiswa <span style="color:#ff0000;">*</span> : </label>
						<select name="alasan_hasil_check_tidak" id="alasan_hasil_check_tidak"  <?=($kd_kantor_valid=="true")? " readonly disabled style=\"width:300px;\"" : " style=\"background-color:#ffff99;width:300px;\"";?> <?php if (trim($ls_status_tindaklanjut) == "Sudah Ditindaklanjuti") { ?> disabled <? } ?> > 
								<?php 
								foreach($arr_tidakdapat as $row) {										
								 ?>
										<option value="<?=$row["KODE"]?>"> <?=$row["KETERANGAN"]?> </option>
								<?php } ?>   		                        
		        </select> 
					</div>	
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label style = "text-align:right;">Keterangan <span style="color:#ff0000;">*</span> : </label>
						<textarea id="keterangan" name="keterangan"  <?=($kd_kantor_valid=="true")? " readonly disabled style=\"width:300px;\"" : " style=\"background-color:#ffff99;width:360px;\"";?> rows="2"  class=""  <?php if (trim($ls_status_tindaklanjut) == "Sudah Ditindaklanjuti") { ?> disabled <? } ?>	 > <?=$ls_keterangan ?></textarea>
					</div>	
					<div class="clear"></div>	
								
					
					</br>
					</br>				  
			
					<div class="clear"></div> 
					<div class="">
						<label style = "text-align:right;">&nbsp;</label>
						
					
						<?php if (trim($ls_status_tindaklanjut) == "Belum Ditindaklanjuti" && $kd_kantor_valid == "false") { ?>
						<input type="button" name="btn_simpan" style="width: 80px;" class="btn green" id="btn_simpan" value="Submit" onclick="edit()"/>
						<? } ?>	
						<input type="button" name="btn_simpan" style="width: 80px;" class="btn green" id="btn_simpan" value="Tutup" onclick="kembali('<?php echo $periodeawal;?>','<?php echo $periodeakhir;?>')"/>
					</div>
					
					
					
				</div>
				
				</div>
			</div>	
		</form>
	</div>
</div>
<script type="text/javascript">
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>