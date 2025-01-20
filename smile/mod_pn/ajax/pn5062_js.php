<?
//------------------------------ LOCAL JAVASCRIPTS -----------------------------
?>
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
	
	function getValueArr(val){
		if (val){
		 	return val; 
		}else{
		 	return '';	 
		}
	}
		
	function search_by_changed(){
		$("#search_txt").val("");
	}

	function search_by2_changed(){
    $('#KODE_SEBAB_KLAIM').hide(); 
    $('#KODE_SEGMEN').hide();
		$('#JENIS_KANAL_PELAYANAN').hide();	
		$('#search_txt2b').val('');
		$('#search_txt2c').val('');
		$('#search_txt2d').val('');
		$('#'+$('#search_by2').val()).show();
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

	function orderby(e) {
		let order_by = $(e).attr('order_by');
		let order_type = $(e).attr('order_type');
		order_type = order_type == 'ASC' ? 'ASC' : 'DESC';

		$('#order_by').val(order_by);
		$('#order_type').val(order_type);

		order_type = order_type == 'ASC' ? 'DESC' : 'ASC';
		$(e).attr('order_type', order_type);

		$('.order-icon').each(function() {
			$(this).attr('src', '../../images/sort_both.png');
		});

		if (order_type == 'ASC') {
			$(e).find("img").attr('src', '../../images/sort_desc.png');
		} else {
			$(e).find("img").attr('src', '../../images/sort_asc.png');
		}

		filter();
	}
	//end template ---------------------------------------------------------------					
</script>

<script language="javascript">
	//custom ---------------------------------------------------------------------
	$(document).ready(function(){
		let task = $('#task').val();
		
		fl_js_InitvalRadioButton();
		fl_js_showHideRadioButton();
		
		if (task=="new")
		{
		}else if (task=="view")
		{
			let kode_klaim = $('#kode_klaim').val();
			let no_level = $('#no_level').val();
			
			loadSelectedRecord(kode_klaim, no_level, null);	
		}else
		{
		 	$('#editid').val('');
			$('#kode_klaim').val('');
			$('#no_level').val('');
			fl_js_get_searchlist();
			filter();
		}
	});

	function fl_js_InitvalRadioButton(){
		var v_task = $('#task').val();
		
		if (v_task=='')
		{
			var v_rg_kategori = $('#tmp_rg_kategori').val();	 

  		v_rg_kategori = v_rg_kategori ==='' ? 'ALL' : v_rg_kategori;
  		fl_js_setCheckedValueRadioButton('rg_kategori', v_rg_kategori);
  		
			$('#tmp_rg_kategori').val(v_rg_kategori);	
		}else
		{
		 	var v_rg_kategori = $('#tmp_rg_kategori').val();
			
			fl_js_setCheckedValueRadioButton('rg_kategori', v_rg_kategori);
		}	 
	}

	function fl_js_showHideRadioButton(){
		var v_rg_kategori = $("input[name='rg_kategori']:checked").val();
		var v_task = $('#task').val();
		
		$('#tmp_rg_kategori').val(v_rg_kategori);
					
  	if (v_task=='')
  	{
			$('#rg_kategori_jht').show(); 
  		$('#rg_kategori_jht_label').show();
			$('#rg_kategori_jhm').show(); 
  		$('#rg_kategori_jhm_label').show();
			$('#rg_kategori_jkk').show(); 
  		$('#rg_kategori_jkk_label').show();
			$('#rg_kategori_jkm').show(); 
  		$('#rg_kategori_jkm_label').show();
			$('#rg_kategori_jpn').show(); 
  		$('#rg_kategori_jpn_label').show();
		  $('#rg_kategori_jkp').show(); 
  		$('#rg_kategori_jkp_label').show();
			$('#rg_kategori_all').show(); 
  		$('#rg_kategori_all_label').show();
  	}else
  	{
			$('#rg_kategori_jht').hide(); 
  		$('#rg_kategori_jht_label').hide();
			$('#rg_kategori_jhm').hide(); 
  		$('#rg_kategori_jhm_label').hide();
			$('#rg_kategori_jkk').hide(); 
  		$('#rg_kategori_jkk_label').hide();
			$('#rg_kategori_jkm').hide(); 
  		$('#rg_kategori_jkm_label').hide();
			$('#rg_kategori_jpn').hide(); 
  		$('#rg_kategori_jpn_label').hide();
		  $('#rg_kategori_jkp').hide(); 
  		$('#rg_kategori_jkp_label').hide();
			$('#rg_kategori_all').hide(); 
  		$('#rg_kategori_all_label').hide();					
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
			
	function checkRecordAll(el) {
		let checked = $(el).prop('checked');
		$("input[elname='cboxRecord']").each(function() {
			let kode_formula = $(this).attr('kode_formula');
			$(this).prop("checked", checked);
			if (checked == true) {
				var found = window.list_checkbox_record.find(function(element) {
					if (element.KODE_KLAIM == kode_klaim) {
						return element;
					}
				});
				if (found == undefined) {
					window.list_checkbox_record.push({ KODE_KLAIM: kode_klaim});
				}
			} else {
				window.list_checkbox_record.forEach(function(element, i) {
					if (element.KODE_KLAIM == kode_klaim) {
						window.list_checkbox_record.splice(i, 1);
					}
				});
			}
		});
	}

	function checkRecord(el) {
		let kode_klaim = $(el).attr('kode_klaim');
		
		if ($(el).prop("checked") == true) {
			var found = window.list_checkbox_record.find(function(element) {
				if (element.KODE_KLAIM == kode_klaim) {
					return element;
				}
			});
			if (found == undefined) {
				window.list_checkbox_record.push({ KODE_KLAIM: kode_klaim});
			}
		} else {
			window.list_checkbox_record.forEach(function(element, i) {
				if (element.KODE_KLAIM == kode_klaim) {
					window.list_checkbox_record.splice(i, 1);
				}
			});
		}
	}
		
	function showTask(task, editid) {
		if (task == 'new' || task == '') 
		{
			document.formreg.task.value = task;
			try {
				document.formreg.onsubmit();
			} catch(e){}
			document.formreg.submit();
		} else if (task == 'edit' || task == 'view') 
		{
			if (window.list_checkbox_record.length != 1) {
				return alert('Silahkan pilih hanya salah satu record saja!');
			}
			
			document.formreg.task.value = task;
			document.formreg.kode_klaim.value = window.list_checkbox_record[0].KODE_KLAIM;
			try {
				document.formreg.onsubmit();
			} catch(e){}
			document.formreg.submit();
		} else {
			alert('Task is not support');
		}
	}
				
	function filter(val = 0){
		var pages = new Number($("#pages").val());
		var page = new Number($("#page").val());
		var page_item = $("#page_item").val();
		
		var search_by    = $("#search_by").val();
		var search_txt   = $("#search_txt").val();
		var search_by2   = $("#search_by2").val();
		var search_txt2a = $("#search_txt2a").val();
		var search_txt2b = $("#search_txt2b").val();
		var search_txt2c = $("#search_txt2c").val();
		var search_txt2d = $("#search_txt2d").val();
		var order_by 		 = $("#order_by").val();
    var order_type 	 = $("#order_type").val();
		var kategori	   = $("input[name='rg_kategori']:checked").val();
		$('#tmp_rg_kategori').val(kategori); 
			
		if (val == '01') {
			page = (page + 1) > pages ? pages : (page + 1);
		} else if (val == '02') {
			page = pages;
		} else if (val == '-01') {
			page = (page - 1) <= 0 ? 1 : (page - 1);
		} else if (val == '-02'){
			page = 1;
		}else
		{
		 	if (val == 0)
			{
			 	page=1; 
			}else
			{
			 	if (val>pages)
				{
				 	page = pages; 
				}	 
			}	 
		}

		$("#page").val(page);
		
		asyncPreload(true);

		html_data = '';
		html_data += '<tr class="nohover-color">';
		html_data += '<td colspan="10" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5062_action.php?"+Math.random(),
			data: {
				tipe 				 		 : 'MainDataGrid',
				page				 		 : page,
				page_item    		 : page_item,
				search_by    		 : search_by,
				search_txt   		 : search_txt,
				search_by2   		 : search_by2,
				search_txt2a 		 : search_txt2a,
				search_txt2b 		 : search_txt2b,
				search_txt2c 		 : search_txt2c,
				search_txt2d 		 : search_txt2d,
				order_by		 		 : order_by,
        order_type	 		 : order_type,
				kategori		 		 : kategori
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 1) {
						var html_data = "";
						// load data
						for(var i = 0; i < jdata.data.length; i++){
							let kode_klaim = getValue(jdata.data[i].KODE_KLAIM);
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.KODE_KLAIM == kode_klaim) {
									return element;
								}
							});
							
							html_data += '<tr>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].ACTION) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_PENETAPAN) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_PENGAMBIL_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KET_TIPE_KLAIM) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_SEGMEN) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].JENIS_KANAL_PELAYANAN) + '</td>';
							html_data += '</tr>';
						}
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="10" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_list").html(html_data);
						
						// load info halaman
						$("#pages").val(jdata.pages);
						$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

						// load info item
						$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
						$("#hdn_total_records").val(jdata.recordsTotal);

						resize();
					} else {
						//alert(jdata.msg);
					}
				} catch (e) {
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
				}
				asyncPreload(false);
			},
			complete: function(){
				asyncPreload(false);
			},
			error: function(){
				alert("Terjadi kesalahan, coba beberapa saat lagi!");
				asyncPreload(false);
			}
		});
	}
	
	function doGridTask(v_editid, v_no_level, v_task, v_kategori)
  {
		document.formreg.task.value = v_task;
		document.formreg.editid.value = v_editid;
		document.formreg.kode_klaim.value = v_editid;
		document.formreg.no_level.value = v_no_level;
		document.formreg.tmp_rg_kategori.value = v_kategori;
								
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
	
	function fl_js_doBack2Grid() {			 
		document.formreg.task.value = '';
		document.formreg.editid.value = '';
		document.formreg.kode_klaim.value = '';
		document.formreg.no_level.value = '';
		var v_kategori = $("#tmp_rg_kategori").val();
		
		window.location.href = '?rg_kategori='+v_kategori+'&tmp_rg_kategori='+v_kategori+'&mid=<?=$mid;?>';
	}

  function fl_js_reload_post_submit()
  {		 
    document.formreg.task.value	= '';
    document.formreg.kode_klaim.value = '';
    document.formreg.no_level.value = '';
    document.formreg.task_detil.value = '';
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();
  }
		
	function fl_js_reloadFormEntry()
  {
		document.formreg.task_detil.value = '';
		
		try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }					
	//end custom -----------------------------------------------------------------
</script>
<?
//--------------------------- END LOCAL JAVASCRIPTS ----------------------------
?>

<?
//------------------------------------ TAB -------------------------------------
if ($task == "edit" || $task == "view")
{
?>
  <link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
  <script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function() {
      v_activetab = $('#activetab').val();
			
			$('ul#nav li a').removeClass('active'); //menghilangkan class active (yang tampil)			
      $('#tabid'+v_activetab).addClass("active");	// menambahkan class active pada link yang diklik
      $('.tab_konten').hide(); 											// menutup semua konten tab					
      $('#tab'+v_activetab).fadeIn('slow'); //tab pertama ditampilkan								 
      
      // jika link tab di klik
      $('ul#nav li a').click(function() 
      { 					 																																				
        $('ul#nav li a').removeClass('active'); //menghilangkan class active (yang tampil)			
        $(this).addClass("active"); 						// menambahkan class active pada link yang diklik
        $('.tab_konten').hide(); 								// menutup semua konten tab
        var aktif = $(this).attr('href'); 			// mencari mana tab yang harus ditampilkan
        var aktif_idx = aktif.substr(4,5);
        document.getElementById('activetab').value = aktif_idx;
        //alert(aktif_idx);
        $(aktif).fadeIn('slow'); 								// tab yang dipilih, ditampilkan
        return false;
      });		
    });
  </script>		
<?
}
//------------------------------- END TAB --------------------------------------
?>

<?
//----------------------------- EDIT/VIEW --------------------------------------
?>
<script language="javascript">
	function loadSelectedRecord(v_kode_klaim, v_no_level, fn)
	{
		if (v_kode_klaim == '' || v_no_level=='') {
			return alert('Parameter utama tidak boleh kosong');
		}
		
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5069_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatabykodeklaim',
				v_kode_klaim: v_kode_klaim
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{			
						window.document.getElementById("span_approval").style.display = 'block';
						window.document.getElementById("span_penolakan").style.display = 'none';
						
						var v_jenis_klaim 		 	= getValue(jdata.data.INFO_KLAIM['JENIS_KLAIM']);
						var v_kode_segmen 			= getValue(jdata.data.INFO_KLAIM['KODE_SEGMEN']);
						var v_flag_agenda_12 		= getValue(jdata.data.INFO_KLAIM['FLAG_AGENDA_12']) == "" ? "T" : getValue(jdata.data.INFO_KLAIM['FLAG_AGENDA_12']);
						var v_kode_pointer_asal = getValue(jdata.data.INFO_KLAIM['KODE_POINTER_ASAL']);
						var v_kode_sebab_klaim 	= getValue(jdata.data.INFO_KLAIM['KODE_SEBAB_KLAIM']);
						var v_cnt_lumpsum 			= getValue(jdata.data.INFO_KLAIM['CNT_LUMPSUM']) == "" ? "0" : getValue(jdata.data.INFO_KLAIM['CNT_LUMPSUM']);
						var v_cnt_berkala 			= getValue(jdata.data.INFO_KLAIM['CNT_BERKALA']) == "" ? "0" : getValue(jdata.data.INFO_KLAIM['CNT_BERKALA']);
            var v_flag_lumpsum			= v_cnt_lumpsum > 0 ? "Y" : "T";
						var v_flag_berkala			= v_cnt_berkala > 0 ? "Y" : "T";
						var v_max_level_approval = getValue(jdata.data.UI_FORM_BTN['V_MAX_LEVEL_APPROVAL']) == "" ? "0" : getValue(jdata.data.UI_FORM_BTN['V_MAX_LEVEL_APPROVAL']);

						if (v_no_level==v_max_level_approval)
						{
						 	$("#span_btnapvsubmit").html('SUBMIT DATA UNTUK PEMBAYARAN &nbsp;'); 
						}else
						{
						 	$("#span_btnapvsubmit").html('SUBMIT UNTUK PERMOHONAN PERSETUJUAN &nbsp;'); 	 
						}
													
            window.document.getElementById("tabid1").style.display = 'block';
            window.document.getElementById("tabid2").style.display = 'block';
            window.document.getElementById("tabid3").style.display = 'none';
            window.document.getElementById("tabid4").style.display = 'none';
						window.document.getElementById("tabid5").style.display = 'none';
						window.document.getElementById("tabid6").style.display = 'none';
												
						//--------------------- set layour per jenis klaim -----------------
						if (v_jenis_klaim=="JHT")
						{
						 	//----------------------- set layout jht -------------------------
							$("#span_page_title").html('PN5003 - PERSETUJUAN KLAIM JAMINAN HARI TUA');						
							$("#span_judul_tab1").html('Informasi Agenda Klaim JHT');
							$("#span_judul_tab2").html('Penetapan Manfaat Biaya dan Santunan');
							
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
							$('#formtab1b').load('../ajax/pn5069_view_jht.php', getValueArr(jdata.data.DATA_INPUTJHT));
							$('#formtab1c').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							//set layout penetapan manfaat -----------------------------------
							$('#formtab2a').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							
							//----------------------- end set layout jht ---------------------
						}
						else if (v_jenis_klaim=="JKP")
						{
						 	//----------------------- set layout jkp -------------------------
							$("#span_page_title").html('PN5003 - PERSETUJUAN KLAIM JAMINAN KEHILANGAN PEKERJAAN');						
							$("#span_judul_tab1").html('Informasi Agenda Klaim JKP');
							$("#span_judul_tab2").html('Penetapan Manfaat Biaya dan Santunan');
							
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
							$('#formtab1b').load('../ajax/pn5069_view_jkp.php', getValueArr(jdata.data.INFO_KLAIM));
							$('#formtab1c').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							//set layout penetapan manfaat -----------------------------------
							$('#formtab2a').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							
							//----------------------- end set layout jkp ---------------------
						}else if (v_jenis_klaim=="JHM")
						{
						 	//------------------------ set layout jht/jkm --------------------
							$("#span_page_title").html('PN5003 - PERSETUJUAN KLAIM JHT/JKM');				
							$("#span_judul_tab1").html('Informasi Agenda Klaim JHT/JKM');
							$("#span_judul_tab2").html('Penetapan Manfaat Biaya dan Santunan');
							
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
							
							var v_jhm_datajkm = [];
							v_jhm_datajkm = {KET_TAMBAHAN : getValue(jdata.data.INFO_KLAIM['KET_TAMBAHAN']), TGL_KEMATIAN : getValue(jdata.data.INFO_KLAIM['TGL_KEMATIAN'])}
							
							$('#formtab1b').load('../ajax/pn5069_view_jhm.php', {DATA_INPUTJHT:getValueArr(jdata.data.DATA_INPUTJHT), DATA_INPUTJKM:v_jhm_datajkm});
							$('#formtab1c').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							//set layout penetapan manfaat -----------------------------------
							$('#formtab2a').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
									
							//------------------------ end set layout jht/jkm ----------------												
						}else if (v_jenis_klaim=="JKK")
						{
						 	//------------------------ set layout jkk ------------------------
							$("#span_page_title").html('PN5003 - PERSETUJUAN KLAIM JAMINAN KECELAKAAN KERJA');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JKK');
							$("#span_judul_tab2").html('Penetapan Manfaat Biaya dan Santunan');
							
							//set layout informasi klaim -------------------------------------
              $('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
              
              if (v_flag_agenda_12=="Y")
              {
                $('#formtab1b').load('../ajax/pn5069_view_jkk_tahap1.php', getValueArr(jdata.data.INFO_KLAIM));
                
                if (v_kode_segmen=="JAKON")
                {
                 	$('#formtab1c').load('../ajax/pn5069_view_jakon_tk.php', getValueArr(jdata.data.INFO_KLAIM));
                }
                
								$('#formtab1d').load('../ajax/pn5069_view_jkk_pengajuan.php', getValueArr(jdata.data.INFO_KLAIM));
								$('#formtab1e').load('../ajax/pn5069_view_jkk_tahap2.php', getValueArr(jdata.data.INFO_KLAIM));
              }else
              {
                if (v_kode_pointer_asal =="PROMOTIF")
                {	
                }else
                {
                  if (v_kode_sebab_klaim == "SKK11")
                  {
                    //gagal berangkat -------------------------------
                    $('#formtab1i').load('../ajax/pn5069_view_jkk_1tahap_skk11.php', getValueArr(jdata.data.INFO_KLAIM));
                  }else if (v_kode_sebab_klaim == "SKK22")
                  {
                    //gagal ditempatkan -----------------------------
										$('#formtab1i').load('../ajax/pn5069_view_jkk_1tahap_skk22.php', getValueArr(jdata.data.INFO_KLAIM));
                  }else if (v_kode_sebab_klaim == "SKK18" || v_kode_sebab_klaim == "SKK26")
                  {
                    //kerugian atas tindakan pihak lain (kehilangan)-
										$('#formtab1i').load('../ajax/pn5069_view_jkk_1tahap_skk18.php', getValueArr(jdata.data.INFO_KLAIM));
                  }else if (v_kode_sebab_klaim == "SKK21")
                  {
                    //pemulangan tki bermasalah ---------------------
										$('#formtab1i').load('../ajax/pn5069_view_jkk_1tahap_skk21.php', getValueArr(jdata.data.INFO_KLAIM));
                  }						
                } 
              }
							
							$('#formtab1j').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							//set layout penetapan manfaat -----------------------------------
							$('#formtab2a').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							
							//----------------------- end set layout jkk ---------------------										
						}else if (v_jenis_klaim=="JKM")
						{
						 	//------------------------ set layout jkm ------------------------
							$("#span_page_title").html('PN5003 - PERSETUJUAN KLAIM JAMINAN KEMATIAN');				
							$("#span_judul_tab1").html('Informasi Agenda Klaim JKM');
							$("#span_judul_tab2").html('Penetapan Manfaat Biaya dan Santunan');
							
							//set layout informasi klaim -------------------------------------
              $('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
              $('#formtab1b').load('../ajax/pn5069_view_jkm.php', getValueArr(jdata.data.INFO_KLAIM));
							
              if (v_kode_segmen=="JAKON")
              {
               	$('#formtab1c').load('../ajax/pn5069_view_jakon_tk.php', getValueArr(jdata.data.INFO_KLAIM));
              }
							
							$('#formtab1j').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							//set layout penetapan manfaat -----------------------------------
							$('#formtab2a').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});

							//----------------------- end set layout jkm ---------------------											
						}else if (v_jenis_klaim=="JPN")
						{
						 	//------------------------ set layout jpn ------------------------
							$("#span_page_title").html('PN5003 - PERSETUJUAN KLAIM JAMINAN PENSIUN');
							$("#span_judul_tab1").html('Informasi Klaim JP');
													
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
											
							var v_jpn_datainput = [];
							v_jpn_datainput = {
								INFO_KLAIM : getValueArr(jdata.data.INFO_KLAIM),							
								INPUT_JPN_KONDISIAKHIR : getValueArr(jdata.data.INPUT_JPN_KONDISIAKHIR),
								INPUT_JPN_LISTAMALGAMASIJP : getValueArr(jdata.data.INPUT_JPN_LISTAMALGAMASIJP),
								INPUT_JPN_LISTTKUPAH : getValueArr(jdata.data.INPUT_JPN_LISTTKUPAH),
								INPUT_JPN_DENSITYRATE : getValueArr(jdata.data.INPUT_JPN_DENSITYRATE),
								INPUT_JPN_LISTAWS : getValueArr(jdata.dataJpnListAws.DATA)
							}
							$('#formtab1b').load('../ajax/pn5069_view_jpn.php', v_jpn_datainput);
							$('#formtab1j').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							window.document.getElementById("tabid2").style.display = 'none';
							window.document.getElementById("tabid2").style.display = 'none';
							
							if (v_flag_lumpsum == "Y")
							{
  							window.document.getElementById("tabid2").style.display = 'block';
								$("#span_judul_tab2").html('Penetapan Manfaat Lumpsum');
								
								//set layout penetapan manfaat lumpsum -------------------------
  							$('#formtab2a').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});				
							}
							
							if (v_flag_berkala == "Y")
							{
								//jika flag lumpsum Y maka gunakan tab4, jika T maka gunakan tab2
								if (v_flag_lumpsum == "Y")
								{
									 window.document.getElementById("tabid4").style.display = 'block';
									 $("#span_judul_tab4").html('Penetapan Manfaat Berkala');
									 
									 $('#formtab4a').load('../ajax/pn5069_view_penetapanmanfaat_jpnberkala.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfBerkala.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfBerkala.DATA)});	
									 										  
								}else
								{
									 window.document.getElementById("tabid4").style.display = 'none';
									 $("#span_judul_tab4").html('');
									 
									 window.document.getElementById("tabid2").style.display = 'block';
									 $("#span_judul_tab2").html('Penetapan Manfaat Berkala');
									 
									 $('#formtab2b').load('../ajax/pn5069_view_penetapanmanfaat_jpnberkala.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfBerkala.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfBerkala.DATA)});										 					
								}														
							}
							//---------------------- end set layout jpn ----------------------	
						}
						//------------------- end set data by jenis klaim ------------------
						
						//set button -------------------------------------------------------
						//default yg ditampilkan ada disclaimer
						//tickmark disclaimer diklik baru akan tampil tombol approval
						
						window.document.getElementById("span_disclaimer").style.display = 'block';	
						window.document.getElementById("span_button_utama").style.display = 'none';
						window.document.getElementById("span_button_tutup").style.display = 'none';
						window.document.getElementById("span_button_approval").style.display = 'none';							
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
	
	function fl_js_onclick_disclaimer()
	{
		window.document.getElementById("span_disclaimer").style.display = 'none';							
		window.document.getElementById("span_button_utama").style.display = 'block';
		window.document.getElementById("span_button_tutup").style.display = 'block';
		window.document.getElementById("span_button_approval").style.display = 'block';					 
	}
	
	function fl_js_doTolak()
	{
		window.document.getElementById("span_approval").style.display = 'none';
		window.document.getElementById("span_penolakan").style.display = 'block';			 
	}
	
  //do simpan penolakan --------------------------------------------------------
  function fjq_ajax_val_save_penolakan()
  {				 
    var v_kode_klaim 			 = $('#kode_klaim').val();
    var v_alasan_penolakan = $('#alasan_penolakan').val();
  	var v_no_level 				 = $('#no_level').val();
  	
    if (v_kode_klaim == '' || v_no_level ==''){
    	alert('Data klaim/Level approval tidak ditemukan, harap perhatikan data input..!!!');			 						 				 
    }else if (v_alasan_penolakan == ''){
    	alert('Alasan Penolakan kosong, harap lengkapi data input..!!!');			 						 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5062_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_save_penolakan',v_kode_klaim:v_kode_klaim,v_no_level:v_no_level,v_alasan_penolakan:v_alasan_penolakan},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //save penolakan berhasil, reload form -----------------------------
            alert(jdata.msg);
            fl_js_doBack2Grid();												
          }else{
            //save penolakan gagal ---------------------------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do simpan penolakan ----------------------------------------------------
	
	function fl_js_dosubmit_approval()
	{
		var c_mid = '<?=$mid;?>';
		var v_kode_klaim 			 = $('#kode_klaim').val();
		var v_no_level 				 = $('#no_level').val();
		var v_nomor_identitas		 = $('#nomor_identitas').val();
		var v_kode_tk 				 = $('#kode_tk').val();
		
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5062_approval_submit.php?kode_klaim='+v_kode_klaim+'&no_level='+v_no_level+'&kode_tk='+v_kode_tk+'&nomor_identitas='+v_nomor_identitas+'&mid='+c_mid+'','',300,50,'no');			
	}
	
	function fl_js_get_searchlist()
	{
    preload(true);
    $.ajax(
    {
      type: 'POST',
      url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_action.php?'+Math.random(),
      data: { tipe:'fjq_ajax_val_getsearchlist'},
      success: function(data)
      {
        preload(false);
        jdata = JSON.parse(data);
        if(jdata.ret=="0")
        {   
					//set list sebab klaim -----------------------------------------------
          if (jdata.dtListSebabKlaim.DATA)
          {
						for($i=0;$i<(jdata.dtListSebabKlaim.DATA.length);$i++)
            {
						 	//tampilkan semua pilihan ----------------------------
							$("#search_txt2b").append('<option value="'+jdata.dtListSebabKlaim.DATA[$i]['KODE_SEBAB_KLAIM']+'">'+jdata.dtListSebabKlaim.DATA[$i]['NAMA_SEBAB_KLAIM']+' ('+jdata.dtListSebabKlaim.DATA[$i]['KEYWORD']+')</option>');
            }	
          }	
					//set list segmen kepesertaan ----------------------------------------
          if (jdata.dtListSegmen.DATA)
          {
						for($i=0;$i<(jdata.dtListSegmen.DATA.length);$i++)
            {
						 	//tampilkan semua pilihan ----------------------------
							$("#search_txt2c").append('<option value="'+jdata.dtListSegmen.DATA[$i]['KODE_SEGMEN']+'">'+jdata.dtListSegmen.DATA[$i]['NAMA_SEGMEN']+'</option>');
            }	
          }																					
        }else{
          //gagal --------------------------------------------------------------
          alert(jdata.msg);
        }
      }
    });//end ajax	
	}	
</script>
<?
//--------------------------- END EDIT/VIEW ------------------------------------
?>
