<script language="javascript">
	$(document).ready(function(){
		fl_js_InitvalRadioButton();
		fl_js_showHideRadioButton();
		fl_js_load_form();					
										 
	});
	
	function fl_js_InitvalRadioButton(){
		var v_task = $('#task').val();
		
		if (v_task=='')
		{
			var v_jenis_pembayaran = $('#tmp_jenis_pembayaran').val();	 
			var v_status_siapbayar = $('#tmp_status_siapbayar').val();
			var v_blthjt 					 = $('#tmp_blthjt').val();
			
			//------------------ set radio button rg_jenis_pembayaran ----------------
  		//jika rg_jenis_pembayaran kosong maka set default LUMPSUM
  		v_jenis_pembayaran = v_jenis_pembayaran ==='' ? 'LUMPSUM' : v_jenis_pembayaran;
  		fl_js_setCheckedValueRadioButton('rg_jenis_pembayaran', v_jenis_pembayaran);
  		//---------------- end set radio button rg_jenis_pembayaran --------------
			
  		//----------------- set radio button rg_status_siapbayar -----------------
  		//jika kosong maka set default Verifikasi Data Siap Bayar (1)
  		v_status_siapbayar = v_status_siapbayar ==='' ? '1' : v_status_siapbayar;
  		fl_js_setCheckedValueRadioButton('rg_status_siapbayar', v_status_siapbayar);
			//--------------- end set radio button rg_status_siapbayar ---------------
			  		
			//------------------- set radio button rg_rg_blthjt ----------------------
			//jika kosong maka set default bljth (B1)
  		v_blthjt = v_blthjt ==='' ? 'B1' : v_blthjt;
  		fl_js_setCheckedValueRadioButton('rg_blthjt', v_blthjt);
			//----------------- end set radio button rg_rg_blthjt --------------------
			
			$('#tmp_jenis_pembayaran').val(v_jenis_pembayaran);
			$('#tmp_status_siapbayar').val(v_status_siapbayar);
			$('#tmp_blthjt').val(v_blthjt);	
		}else
		{
		 	var v_jenis_pembayaran = $('#tmp_jenis_pembayaran').val();	 
			var v_status_siapbayar = $('#tmp_status_siapbayar').val();
			var v_blthjt = $('#tmp_blthjt').val();
			
			fl_js_setCheckedValueRadioButton('rg_jenis_pembayaran', v_jenis_pembayaran);
			fl_js_setCheckedValueRadioButton('rg_status_siapbayar', v_status_siapbayar);
			fl_js_setCheckedValueRadioButton('rg_blthjt', v_blthjt);
		}	 
	}
	
	function fl_js_showHideRadioButton(){
		var v_task = $('#task').val();
		
		var v_jenis_pembayaran = $("input[name='rg_jenis_pembayaran']:checked").val();
		var v_status_siapbayar = $("input[name='rg_status_siapbayar']:checked").val();
		var v_blthjt 					 = $("input[name='rg_blthjt']:checked").val();
		
		$('#tmp_jenis_pembayaran').val(v_jenis_pembayaran);
		$('#tmp_status_siapbayar').val(v_status_siapbayar);
		$('#tmp_blthjt').val(v_blthjt);	
			
		if (v_task == '')
		{
			$('#rg_jenis_pembayaran_lumpsum').show();
			$('#rg_jenis_pembayaran_berkala').show();	
			$('#rg_jenis_pembayaran_lumpsum_label').show();
			$('#rg_jenis_pembayaran_berkala_label').show();
			
      $('#rg_status_siapbayar1').show();
      $('#rg_status_siapbayar3').show();
      $('#rg_status_siapbayar5').show();
      //$('#rg_status_siapbayar6').show();
      $('#rg_status_siapbayar1_label').show();
      $('#rg_status_siapbayar3_label').show();
      $('#rg_status_siapbayar5_label').show();
      //$('#rg_status_siapbayar6_label').show();
			
			fl_js_showHideRadioButtonSiapBayar();						
		}else
		{
			$('#rg_jenis_pembayaran_lumpsum').hide();
			$('#rg_jenis_pembayaran_berkala').hide();
			$('#rg_jenis_pembayaran_lumpsum_label').hide();
			$('#rg_jenis_pembayaran_berkala_label').hide();
			
      $('#rg_status_siapbayar1').hide();
      $('#rg_status_siapbayar3').hide();
      $('#rg_status_siapbayar5').hide();
			$('#rg_status_siapbayar4').hide(); 
      //$('#rg_status_siapbayar6').hide();
      $('#rg_status_siapbayar1_label').hide();
      $('#rg_status_siapbayar3_label').hide();
      $('#rg_status_siapbayar5_label').hide();
			$('#rg_status_siapbayar4_label').hide();	
      //$('#rg_status_siapbayar6_label').hide();	
			
			$('#rg_blthjt_b0').hide(); 
  		$('#rg_blthjt_b0_label').hide();
			$('#rg_blthjt_b1').hide(); 
  		$('#rg_blthjt_b1_label').hide();
			$('#rg_blthjt_b2').hide(); 
  		$('#rg_blthjt_b2_label').hide();
			$('#rg_blthjt_b3').hide(); 
  		$('#rg_blthjt_b3_label').hide();								
		}													 
		//end set radio button -----------------------------------------------------	
	}

	function fl_js_showHideRadioButtonSiapBayar(){
		var v_jenis_pembayaran = $("input[name='rg_jenis_pembayaran']:checked").val();
		var v_status_siapbayar = $("input[name='rg_status_siapbayar']:checked").val();
		var v_blthjt 			 		 = $("input[name='rg_blthjt']:checked").val();

		$('#tmp_jenis_pembayaran').val(v_jenis_pembayaran);
		$('#tmp_status_siapbayar').val(v_status_siapbayar);
		$('#tmp_blthjt').val(v_blthjt);
							
  	if (v_jenis_pembayaran=='BERKALA' && v_status_siapbayar=='1')
  	{
			$('#rg_blthjt_b0').show(); 
  		$('#rg_blthjt_b0_label').show();
			$('#rg_blthjt_b1').show(); 
  		$('#rg_blthjt_b1_label').show();
			$('#rg_blthjt_b2').show(); 
  		$('#rg_blthjt_b2_label').show();
			$('#rg_blthjt_b3').show(); 
  		$('#rg_blthjt_b3_label').show();
  	}else
  	{
			$('#rg_blthjt_b0').hide(); 
  		$('#rg_blthjt_b0_label').hide();
			$('#rg_blthjt_b1').hide(); 
  		$('#rg_blthjt_b1_label').hide();
			$('#rg_blthjt_b2').hide(); 
  		$('#rg_blthjt_b2_label').hide();
			$('#rg_blthjt_b3').hide(); 
  		$('#rg_blthjt_b3_label').hide();						
  	}
	}
		
  function fl_js_setCheckedValueRadioButton(vRadioObj, vValue) 
	{
		var radios = document.getElementsByName(vRadioObj);
    //reset value ----------------
		for (var j = 0; j < radios.length; j++) {
				radios[j].checked = false;
    }
		//assign value ----------------	
    for (var j = 0; j < radios.length; j++) {
				radios[j].checked = false;
        if (radios[j].value == vValue) {
            radios[j].checked = true;
            break;
        }
    }		
  }	
</script>

<script language="javascript">
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

	function getValueArr(val){
		if (val){
		 	return val; 
		}else{
		 	return '';	 
		}
	}

	function getValueNumber(val){
		return val == null ? '0' : val;
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

	function fl_js_set_tgl_sysdate_min1(v_input)
	{		
		var today_date = new Date();
		var today_datex = new Date(today_date);
		today_datex.setDate(today_datex.getDate() - 1);
		
		var today = new Date(today_datex);
		
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
		
  function fl_js_val_numeric(v_field_id)
  {
      var c_val = window.document.getElementById(v_field_id).value;
      var number=/^[0-9]+$/;
      
      if ((c_val!='') && (!c_val.match(number)))
      {
        document.getElementById(v_field_id).value = '';				 
        window.document.getElementById(v_field_id).focus();
        alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");         
        return false; 				 
      }		
  }
  
  function fl_js_val_email(v_field_id)
  {
      var x = window.document.getElementById(v_field_id).value;
  		var atpos=x.indexOf("@");
      var dotpos=x.lastIndexOf(".");
      if ((x!='') && (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length))
      {
         document.getElementById(v_field_id).value = '';				 
  		 window.document.getElementById(v_field_id).focus();
  		 alert("Format Email tidak valid, belum ada (@ DAN .)");         
  		 return false; 	
      }
  }
  
  function fl_js_val_npwp(v_field_id)
  {
      var v_npwp = window.document.getElementById(v_field_id).value;
  		if ((v_npwp!='') && (v_npwp!='0') && (v_npwp.length!=15))
      {
         document.getElementById(v_field_id).value = '0';				 
  		 window.document.getElementById(v_field_id).focus();
  		 alert("NPWP tidak valid, harus 15 karakter...!!!");         
  		 return false; 	
      }else
  		{
  	 	 fl_js_val_numeric(v_field_id);	 
  		}
  }
	
	function fl_js_set_tgl_display(v_tgl_awal, v_tgl_akhir)
	{
	 	if (v_tgl_awal=='' || v_tgl_akhir=='')
		{
		 	fl_js_set_tgl_sysdate_min1('tglawaldisplay');
			fl_js_set_tgl_sysdate('tglakhirdisplay'); 
		}else
		{
		 	$('#tglawaldisplay').val(v_tgl_awal);
			$('#tglakhirdisplay').val(v_tgl_akhir);	 
		}			 
	}
	
	function fl_js_set_status_siapbayar(p_status) {
		if (p_status == '')
		{
		 	v_status = '1';  		
		}else
		{
		 	v_status = p_status;	 
		}
			 
		$('#tmp_status_siapbayar').val(v_status);
		fl_js_reloadPage();
	}
	
	function fl_js_reloadPage() {			 
		document.formreg.task.value = '';
		document.formreg.editid.value = '';
		document.formreg.task_detil.value = '';
		document.formreg.kode_klaim.value = '';
		document.formreg.no_konfirmasi.value = '';
		document.formreg.no_proses.value = '';
		document.formreg.kd_prg.value = '';
		
		var v_jenis_pembayaran  = $('#tmp_jenis_pembayaran').val();
		var v_status_siapbayar  = $('#tmp_status_siapbayar').val();
		var v_blthjt 					  = $('#tmp_blthjt').val();
		var v_tgl_awal_display  = $('#tmp_tglawaldisplay').val();
		var v_tgl_akhir_display = $('#tmp_tglakhirdisplay').val();
		
		var p_var = '?tmp_jenis_pembayaran='+v_jenis_pembayaran
								+'&tmp_status_siapbayar='+v_status_siapbayar
								+'&tmp_blthjt='+v_blthjt
								+'&tmp_tglawaldisplay='+v_tgl_awal_display
								+'&tmp_tglakhirdisplay='+v_tgl_akhir_display
								+'&rg_jenis_pembayaran='+v_jenis_pembayaran
								+'&rg_status_siapbayar='+v_status_siapbayar
								+'&rg_blthjt='+v_blthjt
								+'&mid=<?=$mid;?>';
		
		window.location.href = p_var;
	}
	
	function fl_js_show_lov_cetakbyr(p_kode_klaim, p_kode_pembayaran)
	{		
		var c_tglawaldisplay  = $('#tmp_tglawaldisplay').val();
		var c_tglakhirdisplay = $('#tmp_tglakhirdisplay').val();
		var c_mid = '<?=$mid;?>';
		
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5029_cetak.php?kode_klaim='+p_kode_klaim+'&kode_pembayaran='+p_kode_pembayaran+'&mid='+c_mid+'&tglawaldisplay='+c_tglawaldisplay+'&tglakhirdisplay='+c_tglakhirdisplay+'','Cetak Pembayaran Klaim',700,480,'no');
	}
	
	function fl_js_load_form()
	{
	 	var v_task 						 = $('#task').val();
		var v_jenis_pembayaran = $('#tmp_jenis_pembayaran').val();
		var v_status_siapbayar = $('#tmp_status_siapbayar').val();
		var v_blthjt 					 = $('#tmp_blthjt').val();
		var v_kode_klaim			 = $('#kode_klaim').val();
		var v_no_konfirmasi		 = $('#no_konfirmasi').val();
		var v_no_proses			 	 = $('#no_proses').val();
		var v_kd_prg			 		 = $('#kd_prg').val();
	
		if (v_jenis_pembayaran=="LUMPSUM" && v_status_siapbayar=="1")
		{
		 	//lumpsum - verifikasi data siap bayar
      $('#div_container').load('../ajax/pn5063_lumpsumverifikasi.php', {TASK:v_task, KODE_KLAIM:v_kode_klaim});
		}else if (v_jenis_pembayaran=="BERKALA" && v_status_siapbayar=="1")
		{
		 	//jp berkala - verifikasi data siap bayar
      $('#div_container').load('../ajax/pn5063_jpberkalaverifikasi.php', {TASK:v_task, BLTHJT:v_blthjt, KODE_KLAIM:v_kode_klaim, NO_KONFIRMASI:v_no_konfirmasi, NO_PROSES:v_no_proses, KD_PRG:v_kd_prg});
		}else if (v_jenis_pembayaran=="LUMPSUM" && v_status_siapbayar=="3")
		{
		 	//lumpsum - pembayaran (siap transfer)
      $('#div_container').load('../ajax/pn5063_lumpsumsiaptransfer.php', {TASK:v_task, KODE_KLAIM:v_kode_klaim});
		}else if (v_jenis_pembayaran=="BERKALA" && v_status_siapbayar=="3")
		{
		 	//jp berkala - pembayaran (siap transfer)
      $('#div_container').load('../ajax/pn5063_jpberkalasiaptransfer.php', {TASK:v_task, BLTHJT:v_blthjt, KODE_KLAIM:v_kode_klaim, NO_KONFIRMASI:v_no_konfirmasi, NO_PROSES:v_no_proses, KD_PRG:v_kd_prg});			
		}else if (v_jenis_pembayaran=="LUMPSUM" && v_status_siapbayar=="5")
		{
		 	//lumpsum - monitoring
      $('#div_container').load('../ajax/pn5063_lumpsummonitoring.php', {TASK:v_task, KODE_KLAIM:v_kode_klaim});
		}else if (v_jenis_pembayaran=="BERKALA" && v_status_siapbayar=="5")
		{
		 	//jp berkala - monitoring
      $('#div_container').load('../ajax/pn5063_jpberkalamonitoring.php', {TASK:v_task, BLTHJT:v_blthjt, KODE_KLAIM:v_kode_klaim, NO_KONFIRMASI:v_no_konfirmasi, NO_PROSES:v_no_proses, KD_PRG:v_kd_prg});			
		}else
		{
		 	//$('#div_container').html('');	 
		}	
	}							
</script>


