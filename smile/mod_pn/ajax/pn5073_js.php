<script language="javascript">
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
		
	function search_by_changed(){
		$("#search_txt").val("");
	}

	function search_by2_changed(){
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
	
	function reloadPage() {
		document.formreg.task.value = '';
		document.formreg.editid.value = '';
		document.formreg.task_detil.value = '';
		window.location.href = '?mid=<?=$mid;?>';
	}

	function reloadFormUtama()
  {
		document.formreg.task_detil.value = '';
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
	
	function fl_js_refreshFormUtama()
	{
	 	reloadFormUtama();			 
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
</script>

<!-- edit below -->
<script>
	$(document).ready(function(){
		let task = $('#task').val();
		if (task=="new")
		{
		 	let task_detil = $('#task_detil').val();
			let nik_tk_temp = $('#nik_tk_temp').val();
			let nama_tk_temp = $('#nama_tk_temp').val();
			let nik_pengganti_temp = $('#nik_pengganti_temp').val();
			let nik_penerima_induk_temp = $('#nik_penerima_induk_temp').val();
			let ket_penggantian_temp = $('#ket_penggantian_temp').val();
			
			if (task_detil== 'doPostGantiPenerima' && nik_tk_temp!="" && nama_tk_temp!="" && nik_pengganti_temp!="" && nik_penerima_induk_temp!="" && ket_penggantian_temp!="")
			{
        fl_js_doPostGantiPenerima(nik_tk_temp, nama_tk_temp, nik_pengganti_temp, nik_penerima_induk_temp, ket_penggantian_temp);	 	 
			}		
		}else if (task=="edit" || task=="view")
		{
			let nik_penerima_temp = $('#nik_penerima_temp').val();
			
			loadSelectedRecord(nik_penerima_temp, null);
		}else
		{
		 	$('#editid').val('');
			$('#nik_penerima_temp').val('');
			filter();	 
		}
	});

	function checkRecordAll(el) {
		let checked = $(el).prop('checked');
		$("input[elname='cboxRecord']").each(function() {
			let nik_penerima_temp = $(this).attr('nik_penerima_temp');
			$(this).prop("checked", checked);
			if (checked == true) {
				var found = window.list_checkbox_record.find(function(element) {
					if (element.NIK_PENERIMA == nik_penerima_temp) {
						return element;
					}
				});
				if (found == undefined) {
					window.list_checkbox_record.push({ NIK_PENERIMA: nik_penerima_temp });
				}
			} else {
				window.list_checkbox_record.forEach(function(element, i) {
					if (element.NIK_PENERIMA == nik_penerima_temp) {
						window.list_checkbox_record.splice(i, 1);
					}
				});
			}
		});
	}

	function checkRecord(el) {
		let nik_penerima_temp = $(el).attr('nik_penerima_temp');
		
		if ($(el).prop("checked") == true) {
			var found = window.list_checkbox_record.find(function(element) {
				if (element.NIK_PENERIMA == nik_penerima_temp) {
					return element;
				}
			});
			if (found == undefined) {
				window.list_checkbox_record.push({ NIK_PENERIMA: nik_penerima_temp });
			}
		} else {
			window.list_checkbox_record.forEach(function(element, i) {
				if (element.NIK_PENERIMA == nik_penerima_temp) {
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
			document.formreg.nik_penerima_temp.value = window.list_checkbox_record[0].NIK_PENERIMA;
			try {
				document.formreg.onsubmit();
			} catch(e){}
			document.formreg.submit();
		} else {
			alert('Task is not support');
		}
	}

	function doGridView(v_task, v_editid)
  {				
		document.formreg.task.value = v_task;
		document.formreg.editid.value = v_editid;
		document.formreg.nik_penerima_temp.value = v_editid;
			
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
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
		
		if (kategori=="2" || kategori=="3")
		{
		 	window.document.getElementById("button_action_newedit").style.display = 'none'; 
		}else
		{
		 	window.document.getElementById("button_action_newedit").style.display = 'block'; 	 
		}
			
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
		html_data += '<td colspan="11" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5073_action.php?"+Math.random(),
			data: {
				tipe 				 : 'MainDataGrid',
				page				 : page,
				page_item    : page_item,
				search_by    : search_by,
				search_txt   : search_txt,
				search_by2   : search_by2,
				search_txt2a : search_txt2a,
				search_txt2b : search_txt2b,
				search_txt2c : search_txt2c,
				search_txt2d : search_txt2d,
				order_by		 : order_by,
        order_type	 : order_type,
				kategori		 : kategori
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 1) {
						var html_data = "";
						// load data
						for(var i = 0; i < jdata.data.length; i++){
							let nik_penerima_temp = getValue(jdata.data[i].NIK_PENERIMA);
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.NIK_PENERIMA == nik_penerima_temp) {
									return element;
								}
							});
							
							html_data += '<tr>';
							html_data += '<td style="text-align: center;">' 
									+ '<input type="checkbox" id="cboxRecord' + i +'" name="cboxRecord' + i +'" elname="cboxRecord" '
									+ 'onchange="checkRecord(this)" '
									+ (checkedBox ? 'checked' : '') + ' '
									+ 'nik_penerima_temp="' + getValue(jdata.data[i].NIK_PENERIMA) + '">' + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].NO) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NIK_TK_DISPLAY) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NIK_PENERIMA_DISPLAY) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_PENERIMA) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_LAHIR) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].EMAIL) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].HANDPHONE) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].STATUS_PENERIMA) + '</td>';
							html_data += '</tr>';
						}
						
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="12" style="text-align: center;">-- Data tidak ditemukan --</td>';
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
</script>

<script language="javascript">	
	//------------------------------ NEW -----------------------------------------
  function fl_js_reload_post_lovniktk()
  {
	 	var fn;
		v_nik_tk = $('#nik_tk').val();
		v_nama_tk = $('#nama_tk').val();
		
    if (v_nik_tk=='')
    { 			 
			return alert('NIK Peserta tidak boleh kosong');			 
    }else
		{

		 	var v_html = '';
			v_html += '<div class="div-row">';
			v_html += '  <div class="div-col" style="width:49%; max-height: 100%;">';
			v_html += '    <fieldset style="min-height:320px;"><legend>Informasi Penerima Manfaat Beasiswa</legend>';
			v_html += '    	 <div class="div-row">';
			v_html += '    	   <br />';
			v_html += '    	   <div class="div-col">'; 
			
			v_html += '		   	   <div class="form-row_kiri">';
      v_html += '		   	 	 <label style = "text-align:right;">NIK Anak &nbsp;&nbsp;*</label>';
      v_html += '		         <input type="text" id="nik_penerima" name="nik_penerima" style="width:170px;background-color:#ffff99;" maxlength="16" onblur="fjq_ajax_val_nikbyadminduk();">';
      v_html += '          	 <input type="checkbox" id="cb_valid_nik_penerima" name="cb_valid_nik_penerima" disabled class="cebox"><em><font color="#009999">Valid</font></em>';	
      v_html += '          	 <input type="hidden" id="status_valid_nik_penerima" name="status_valid_nik_penerima">';
			v_html += '          	 <input type="hidden" id="nik_penerima_old" name="nik_penerima_old">';
			v_html += '          	 <input type="hidden" id="tgl_rekam" name="tgl_rekam">';
			v_html += '          	 <input type="hidden" id="status_penerima" name="status_penerima">';
			v_html += '          	 <input type="hidden" id="nik_penerima_induk" name="nik_penerima_induk">';
			v_html += '          	 <input type="hidden" id="nik_penerima_anak" name="nik_penerima_anak">';
			v_html += '          	 <input type="hidden" id="ket_penggantian" name="ket_penggantian">';
			v_html += '          	 <input type="hidden" id="jenis_insert" name="jenis_insert">';
			v_html += '			 	 	 </div>';																																							
      v_html += '		   	 	 <div class="clear"></div>';
			
      v_html += '      		 <div class="form-row_kiri">';
      v_html += '      		 <label style = "text-align:right;">Nama Anak &nbsp;&nbsp;</label>';
      v_html += '        	 	 <input type="text" id="nama_penerima" name="nama_penerima" style="width:220px;" maxlength="100" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
      v_html += '      		 </div>';																																																																																														
      v_html += '      		 <div class="clear"></div>';
	
      v_html += '      		 <div class="form-row_kiri">';
      v_html += '      		 <label style = "text-align:right;">Tempat & Tgl Lahir</label>';
      v_html += '        	 	 <input type="text" id="tempat_lahir" name="tempat_lahir" style="width:146px;" maxlength="50" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
      v_html += '        	 	 <input type="text" id="tgl_lahir" name="tgl_lahir" style="width:65px;" maxlength="10" readonly class="disabled" onblur="convert_date(tgl_lahir);">';
			v_html += '        	 	 <input style="display:none;height:14px;" id="btn_tgl_lahir" type="image" align="top" onclick="return showCalendar(\'tgl_lahir\', \'dd-mm-y\');" src="../../images/calendar.gif" />';
      v_html += '      		 </div>';																																																																																														
      v_html += '      		 <div class="clear"></div>';
			
      v_html += '      		 <div class="form-row_kiri">';
      v_html += '      		 <label style = "text-align:right;">Jeis Kelamin</label>';
			v_html += '        	 	 <span id="span_jenis_kelamin_lable" style="display:block;">';			
      v_html += '        	 	 	 <input type="text" id="l_jenis_kelamin2" name="l_jenis_kelamin2" style="width:220px;" readonly class="disabled">';
			v_html += '        	 	 </span>';
			v_html += '        	 	 <span id="span_jenis_kelamin_listitem" style="display:none;">';
			v_html += '        	   	 <select size="1" id="l_jenis_kelamin" name="l_jenis_kelamin" class="select_format" style="width:220px;background-color:#ffff99;" onchange="fl_js_set_jeniskelamin();">';
      v_html += '        	   	 	 <option value="">-- Pilih --</option>';
			v_html += '        	   	 	 <option value="L">LAKI-LAKI</option>';		
			v_html += '        	   	 	 <option value="P">PEREMPUAN</option>';
      v_html += '        	   	 </select>';
			v_html += '        	 	 </span>';
			v_html += '        	 	 <input type="hidden" id="jenis_kelamin" name="jenis_kelamin">';
      v_html += '      		 </div>';																																																																																														
      v_html += '      		 <div class="clear"></div>';
			
			v_html += '      		 <br />';
			
      v_html += '		    	 <div class="form-row_kiri">';
      v_html += '		    	 <label style = "text-align:right;">Alamat Rumah &nbsp;</label>';
      v_html += '		    	 	 <textarea cols="255" rows="1" id="alamat" name="alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onblur="this.value=this.value.toUpperCase();" style="width:220px;height:25px;background-color:#ffff99"></textarea>';   					
      v_html += '		    	 </div>';							
      v_html += '		    	 <div class="clear"></div>';	
			
      v_html += '		   		 <div class="form-row_kiri">';
      v_html += '		   		 <label style = "text-align:right;">Email &nbsp;&nbsp;*</label>';
      v_html += '		     	 	 <input type="text" id="email" name="email" style="width:220px;background-color:#ffff99;" maxlength="200" onblur="this.value=this.value.toLowerCase();fl_js_val_email(\'email\');fl_js_val_refresh_ket_kirim_email();">';
      v_html += '			 		 </div>';																																							
      v_html += '		   		 <div class="clear"></div>';
			
      v_html += '		   		 <div class="form-row_kiri">';
      v_html += '		   		 <label style = "text-align:right;">No. HP &nbsp;&nbsp;*</label>';
      v_html += '		     	 	 <input type="text" id="handphone" name="handphone" style="width:220px;background-color:#ffff99;" maxlength="18" onblur="fl_js_val_numeric(\'handphone\');">';
      v_html += '			 		 </div>';																																							
      v_html += '		   		 <div class="clear"></div>';
			v_html += '      		 <br />';
			
      v_html += '		   		 <div class="form-row_kiri">';
      v_html += '		   		 <label style = "text-align:right;">Nama Ortu/Wali &nbsp;&nbsp;*</label>';
      v_html += '		     	 	 <input type="text" id="nama_ortu_wali" name="nama_ortu_wali" style="width:200px;background-color:#ffff99;" maxlength="100" onblur="this.value=this.value.toUpperCase();">';
      v_html += '			 		 </div>';																																							
      v_html += '		   		 <div class="clear"></div>';
			
      v_html += '		    	 <div class="form-row_kiri">';
      v_html += '		    	 <label style = "text-align:right;">Keterangan &nbsp;</label>';
      v_html += '		    	 	 <textarea cols="255" rows="1" id="keterangan" name="keterangan" onblur="this.value=this.value.toUpperCase();" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" style="width:180px;height:15px;"></textarea>';   					
      v_html += '		    	 </div>';							
      v_html += '		    	 <div class="clear"></div>';
			v_html += '    		 </div>';
			
      v_html += '    		 <div class="div-col" style="width: 150px;text-align:center;">';
      v_html += '      	 	 <input id="nik_penerima_foto" name="nik_penerima_foto" type="image" align="center" src="../../images/nopic.png" style="height: 95px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>';
      v_html += '    	 	 </div>';
			v_html += '    	 </div>';																																	
			v_html += '    </fieldset>';
			v_html += '	 </div>';
			
    	v_html += '  <div class="div-col" style="width:1%;">';
      v_html += '  </div>';

    	v_html += '  <div class="div-col-right" style="width:50%;">';
      v_html += '    <div class="div-row">';
			v_html += '    	 <fieldset style="min-height:150px;" id="fieldset_stsekolah"><legend></legend>';
			v_html += '    	   <div style="text-align:center;">';
			v_html += '    	     <br />'; 	    				
      v_html += '    	   	 <strong><em><font color="#009999">Apakah anak sudah menempuh pendidikan di sekolah?</font></em></strong><br />';
			v_html += '    	   	 <input type="radio" name="rg_status_blm_sekolah" value="Y" id="rg_status_blm_sekolah_y" onClick="fl_js_set_fieldset_stsekolah();">&nbsp;<strong><font color="#009999">Belum</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      v_html += '    	   	 <input type="radio" name="rg_status_blm_sekolah" value="T" id="rg_status_blm_sekolah_t" onClick="fl_js_set_fieldset_stsekolah();">&nbsp;<font color="#009999">Sudah</font></strong>';
			v_html += '          <input type="hidden" id="status_blm_sekolah" name="status_blm_sekolah">';		
			v_html += '    	 	 </div>';																																	
			v_html += '    	 </fieldset>';
			
			v_html += '      <span id="span_sudahsekolah" style="display:none;">';									
			v_html += '        <fieldset style="min-height:150px;width:96%;"><legend>Jika layak mendapatkan Beasiswa maka manfaat akan Dibayarkan ke :</legend>';
			v_html += '    	   	 <br />';
      v_html += '        	 <div class="form-row_kiri">';
      v_html += '        	 <label style = "text-align:right;">Bank</label>'; 
      v_html += '          	 <input type="text" id="nama_bank_penerima" name="nama_bank_penerima" readonly style="width:310px;background-color:#ffff99">';
      v_html += '          	 <input type="hidden" id="kode_bank_penerima" name="kode_bank_penerima" style="width:100px;">';
      v_html += '          	 <input type="hidden" id="id_bank_penerima" name="id_bank_penerima" style="width:100px;">';
      v_html += '          	 <a style="display:null;" id="btn_lov_bank_penerima" href="#" onclick="fl_js_get_lov_bank_penerima();">';							
      v_html += '          	 <img src="../../images/help.png" alt="Cari Bank" border="0" style="height:19px;" align="absmiddle"></a>';
      v_html += '          	 <input type="hidden" id="kode_bank_penerima_old" name="kode_bank_penerima_old">';
      v_html += '        	 </div>';																																																	
      v_html += '        	 <div class="clear"></div>'; 
			                  
      v_html += '        	 <div class="form-row_kiri">';
      v_html += '        	 <label style = "text-align:right;">No Rekening</label>';
      v_html += '          	 <input type="text" id="no_rekening_penerima" name="no_rekening_penerima" onblur="fjq_ajax_val_no_rekening_penerima();" maxlength="30" style="width:150px;background-color:#ffff99">';
      v_html += '          	 <input type="hidden" id="nama_rekening_penerima" name="nama_rekening_penerima" maxlength="100" readonly class="disabled" style="width:120px;background-color:#F5F5F5">';
      v_html += '          	 <input type="text" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" maxlength="100" style="width:150px;background-color:#F5F5F5" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
      v_html += '          	 <input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" disabled class="cebox"><em><font color="#009999">Valid</font></em>';	
      v_html += '          	 <input type="hidden" id="status_valid_rekening_penerima" name="status_valid_rekening_penerima">';
      v_html += '          	 <input type="hidden" id="no_rekening_penerima_old" name="no_rekening_penerima_old">';
      v_html += '        	 </div>';																																																																																															
      v_html += '        	 <div class="clear"></div>';
			v_html += '    	   	 <br />';		 			
			v_html += '      	 </fieldset>';
			
			v_html += '      	 <span id="span_notifikasi" style="display:none;">';
			v_html += '      	 <fieldset style="min-height:134px;width:96%;"><legend></legend>';
			v_html += '    	   	 <div style="text-align:center;">';
			v_html += '    	     	 <br />'; 	    				
      v_html += '    	   	 	 <strong><em><font color="#009999">Notifikasi untuk pengambilan beasiswa akan dikirimkan melalui ? </font></em> </strong><br />';
			v_html += '    	   	 	 <input type="radio" name="rg_notifikasi_via" id="notifikasi_via_email" value="EMAIL" onclick="fjq_ajax_val_show_kirim_email();">&nbsp;<strong><font  color="#009999">Email</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
      v_html += '    	   	 	 <input type="radio" name="rg_notifikasi_via" id="notifikasi_via_sms" value="SMS" onclick="fjq_ajax_val_show_kirim_sms();">&nbsp;<font color="#009999">SMS</font></strong>';
			v_html += '          	 <input type="hidden" id="notifikasi_via" name="notifikasi_via">';
			v_html += '          	 <input type="hidden" id="is_verified_hp" name="is_verified_hp">';
			v_html += '          	 <input type="hidden" id="kode_otp_sms" name="kode_otp_sms">';
			v_html += '          	 <input type="hidden" id="sms_id" name="sms_id">';
			v_html += '    	   	 	 <br />';			
			v_html += '    	 	 	 </div>';
			
			v_html += '      		 <span id="span_kirim_email" style="display:none;">';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	<br />';
			v_html += '          	 	<a id="btn_kirim_email" href="#" onClick="if(confirm(\'Apakah anda yakin akan mengirimkan email verifikasi..?\')) fjq_ajax_val_kirim_email_verifikasi();">';							
      v_html += '          	 		<img src="../../images/copyz.png" alt="kirim email verifikasi" border="0" style="height:19px;" align="absmiddle"><em><strong><font color="#037bff">Kirimkan Email Verifikasi </font></strong></em>';
			v_html += '          	 		<input type="hidden" id="is_verified_email" name="is_verified_email">';
			v_html += '    	 	 	 	 	</a>';
			v_html += '    	 	 	 	 </div>';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	<br />';
			v_html += '    	     	 	<span id="span_text_kirim_email"></span>';
			v_html += '    	 	 	 	 </div>';
			v_html += '      	 	 </span>';
			v_html += '      		 <span id="span_is_kirim_email" style="display:none;">';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	<br />';
			v_html += '    	     	 	<span id="span_text_is_kirim_email"></span>';
			v_html += '    	     	 	<br /><br />';
			v_html += '          	 	(<a id="btn_kirim_ulang_email" href="#" onClick="if(confirm(\'Apakah anda yakin akan mengirimkan ulang email verifikasi..?\')) fjq_ajax_val_kirim_email_verifikasi();">';							
      v_html += '          	 		<img src="../../images/copyz.png" alt="kirim email verifikasi" border="0" style="height:15px;" align="absmiddle"><em><font color="#037bff">Kirimkan Ulang Email Verifikasi </font></em>';
			v_html += '    	 	 	 	 	</a>)';
			v_html += '    	 	 	 	 </div>';
			v_html += '      	 	 </span>';
			
			v_html += '      		 <span id="span_kirim_sms" style="display:none;">';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	<br />';
			v_html += '          	 	<a id="btn_kirim_sms" href="#" onClick="if(confirm(\'Apakah anda yakin akan mengirimkan sms verifikasi..?\')) fjq_ajax_val_kirim_sms_verifikasi();">';							
      v_html += '          	 		<img src="../../images/layanan_sms.jpg" alt="kirim sms verifikasi" border="0" style="height:19px;" align="absmiddle"><span id="span_text_kirim_sms"></span>';
			v_html += '    	 	 	 	 	</a>';
			v_html += '    	 	 	 	 </div>';
			v_html += '      	 	 </span>';
						
			v_html += '      		 <span id="span_verifikasi_otp_sms" style="display:none;">';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	<br />';
			v_html += '          	 		Kode OTP SMS *&nbsp;<input type="text" id="kode_otp_sms_verified" name="kode_otp_sms_verified" maxlength="6" style="width:80px;background-color:#ffff99;">';
			v_html += '          	 		<a id="btn_validate_kode_otp_sms" href="#" onClick="if(confirm(\'Apakah anda yakin akan melakukan verifikasi kode OTP sms..?\')) fjq_ajax_val_validate_kode_otp_sms();">';							
      v_html += '          	 			 <img src="../../images/ok.png" alt="kirim sms verifikasi" border="0" style="height:19px;" align="absmiddle">OK';
			v_html += '    	 	 	 	 		</a>';
			v_html += '    	 	 	 	 </div>';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	 <br />'; 	    				
      v_html += '    	   	 	 	 <span id="span_text_validate_kode_otp_sms">';
			v_html += '    	 	 	 	 </div>';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	 <br />';
			v_html += '          	 	 (<a id="btn_kirim_ulang_sms" href="#" onClick="if(confirm(\'Apakah anda yakin akan mengirimkan sms verifikasi..?\')) fjq_ajax_val_kirim_sms_verifikasi();">';							
      v_html += '          	 		<img src="../../images/layanan_sms.jpg" alt="kirim sms verifikasi" border="0" style="height:15px;" align="absmiddle"><span id="span_text_kirim_ulang_sms"></span>';
			v_html += '    	 	 	 	 	 </a>)';
			v_html += '    	 	 	 	 </div>';
			v_html += '      	 	 </span>';
						
			v_html += '      		 <span id="span_is_verified_hp" style="display:none;">';
			v_html += '    	   	 	 <div style="text-align:center;">';
			v_html += '    	     	 	<br />'; 	    				
      v_html += '    	   	 	 	<em><strong><font color="#ff0000">Verified</font></strong><font color="#009999">, SMS akan dikirimkan ke No. HP: <br /><br /><strong><span style="font-size:1.5em;" id="span_text_is_verified_hp"></span></strong></font></em>';
			v_html += '    	 	 	 	 </div>';
			v_html += '      	 	 </span>'; 			
			v_html += '      	 </fieldset>'; 
			v_html += '      	 </span>';
			v_html += '      </span>'; 
			v_html += '	 	 </div>';
			v_html += '	 </div>';
																						 
			v_html += '</div>';
			
      if (v_html !="")
      {
       	$('#formEntry').html(v_html); 
      }	
			
			window.document.getElementById("span_button_new").style.display = 'block';
			
			fl_js_set_fieldset_stsekolah();
		}		
	}
		
  //validasi nik penerima ------------------------------------------------------
  function fjq_ajax_val_nikbyadminduk()
  {				 
    var v_nik_penerima  = $('#nik_penerima').val();
		var v_nik_penerima_old  = $('#nik_penerima_old').val();
		
    if (v_nik_penerima!='' && v_nik_penerima != v_nik_penerima_old)
    {
        preload(true);
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
          data: { tipe:'fjq_ajax_val_nikbyadminduk', NIK:v_nik_penerima},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            if(jdata.ret=="0")
            {   
              $('#nik_penerima_old').val(v_nik_penerima);
							$('#nama_penerima').val(jdata.data['namaLengkap']);
							$('#tempat_lahir').val(jdata.data['tempatLahir']);
							$('#tgl_lahir').val(jdata.data['tanggalLahir']);
														
							window.document.getElementById('cb_valid_nik_penerima').checked = true;
        			$('#status_valid_nik_penerima').val('Y');
				
              document.getElementById('nama_penerima').readOnly = true;
              document.getElementById('nama_penerima').style.backgroundColor='#F5F5F5';
              
							document.getElementById('tempat_lahir').readOnly = true;
              document.getElementById('tempat_lahir').style.backgroundColor='#F5F5F5';
							
							document.getElementById('tgl_lahir').readOnly = true;
              document.getElementById('tgl_lahir').style.backgroundColor='#F5F5F5';
							window.document.getElementById("btn_tgl_lahir").style.display = 'none';
							window.document.getElementById("tempat_lahir").style.width = '146px';
							
							var v_jenis_kelamin = jdata.data['jenisKelamin'].substr(0, 1);
							var v_l_jenis_kelamin = '';
							if (v_jenis_kelamin=="L")
							{
							 	v_l_jenis_kelamin = "LAKI-LAKI";
							}else if (v_jenis_kelamin=="P")
							{
							 	v_l_jenis_kelamin = "PEREMPUAN";		
							}else
							{
							 	v_l_jenis_kelamin = "";	
								v_jenis_kelamin = "";	 
							}
							$('#jenis_kelamin').val(v_jenis_kelamin);
							$('#l_jenis_kelamin2').val(v_l_jenis_kelamin);
							$('#l_jenis_kelamin').val(v_jenis_kelamin);
							
							window.document.getElementById("span_jenis_kelamin_lable").style.display = 'block';
							window.document.getElementById("span_jenis_kelamin_listitem").style.display = 'none';

							$('#nik_penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#nik_penerima').val());
							$('#alamat').val(jdata.data['alamatLgkp']);	
																							
            }else{
              //nik tidak valid, data penerima diinputkan manual ---------------
							$('#nik_penerima_old').val(v_nik_penerima);
							$('#nama_penerima').val('');
							$('#tempat_lahir').val('');
							$('#tgl_lahir').val('');
							$('#jenis_kelamin').val('');
							$('#l_jenis_kelamin2').val('');
							$('#l_jenis_kelamin').val('');
							$('#alamat').val('');
							$('#nik_penerima_foto').attr('src','../../images/nopic.png');
							
							window.document.getElementById('cb_valid_nik_penerima').checked = false;
        			$('#status_valid_nik_penerima').val('T');
							
							document.getElementById('nama_penerima').readOnly = false;
              document.getElementById('nama_penerima').style.backgroundColor='#ffff99';
							
							document.getElementById('tempat_lahir').readOnly = false;
              document.getElementById('tempat_lahir').style.backgroundColor='#ffff99';
							
							document.getElementById('tgl_lahir').readOnly = false;
              document.getElementById('tgl_lahir').style.backgroundColor='#ffff99';
							window.document.getElementById("btn_tgl_lahir").style.display = '';
							window.document.getElementById("tempat_lahir").style.width = '125px';
							
							window.document.getElementById("span_jenis_kelamin_lable").style.display = 'none';
							window.document.getElementById("span_jenis_kelamin_listitem").style.display = 'block';
							
              alert('Gagal validasi NIK, harap melakukan input manual data anak penerima beasiswa...');
            }
          }
        });
    }else
    { 
			if (v_nik_penerima=='')
			{
  			$('#nama_penerima').val('');
  			$('#tempat_lahir').val('');
  			$('#tgl_lahir').val('');
  			$('#jenis_kelamin').val('');
  			$('#l_jenis_kelamin2').val('');
  			$('#l_jenis_kelamin').val('');
  			$('#alamat').val('');
  			$('#nik_penerima_foto').attr('src','../../images/nopic.png');
  			alert('NIK Penerima tidak boleh kosong..');
			}
    }
  }
  //end validasi nik penerima --------------------------------------------------
	
	function fl_js_set_fieldset_stsekolah() 
  {
		var v_status_blm_sekolah = $("input[name='rg_status_blm_sekolah']:checked").val();
		$('#status_blm_sekolah').val(v_status_blm_sekolah);
		
		if (v_status_blm_sekolah=='T')
		{
		 	//sudah sekolah -------------------------------------
			window.document.getElementById("fieldset_stsekolah").style.height = '60px'; 
			window.document.getElementById("fieldset_stsekolah").style.width = '96%';
			window.document.getElementById("span_sudahsekolah").style.display = 'block'; 
		}else
		{
		 	//belum sekolah/blm dipilih --------------------------
			window.document.getElementById("fieldset_stsekolah").style.height = '316px'; 
			window.document.getElementById("fieldset_stsekolah").style.width = '96%';		
			window.document.getElementById("span_sudahsekolah").style.display = 'none';
		}			 
	}
	
	function fl_js_reset_norek() 
  {	 
    $('#no_rekening_penerima').val('');
		$('#no_rekening_penerima_old').val('');
    $('#nama_rekening_penerima_ws').val('');
    $('#status_valid_rekening_penerima').val('T');
    $('#no_rekening_penerima').focus();
    window.document.getElementById('cb_valid_rekening').checked = false;
  }

  function fl_js_get_lov_bank_penerima()
  {			 					
		fl_js_reset_norek();
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_lov_bankpenerima.php?p=pn5043.php&a=formreg&b=nama_bank_penerima&c=kode_bank_penerima&d=id_bank_penerima','',800,500,1); 
	}
	
  //validasi nomor rekening penerima -------------------------------------------
  function fjq_ajax_val_no_rekening_penerima()
  {				 
    var v_kode_bank_tujuan  = $('#kode_bank_penerima').val();
    var v_nama_bank_tujuan  = $('#nama_bank_penerima').val();
    var v_no_rek_tujuan 		= $('#no_rekening_penerima').val();
    
		var v_kode_bank_tujuan_old  = $('#kode_bank_penerima_old').val();
		var v_no_rek_tujuan_old 		= $('#no_rekening_penerima_old').val();
		
    if (v_kode_bank_tujuan!=v_kode_bank_tujuan_old || v_no_rek_tujuan!=v_no_rek_tujuan_old)
    {
      if (v_kode_bank_tujuan!='' && v_no_rek_tujuan!='')
      {
        preload(true);
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:'validate_rekening_tujuan', NO_REK_TUJUAN:v_no_rek_tujuan, KODE_BANK_ATB_TUJUAN:v_kode_bank_tujuan, NAMA_BANK_TUJUAN:v_nama_bank_tujuan},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            if(jdata.ret=="0")
            {   
              $('#nama_rekening_penerima_ws').val(jdata.data['NAMA_REK_TUJUAN']);
							$('#nama_rekening_penerima').val(jdata.data['NAMA_REK_TUJUAN']);
              document.getElementById('nama_rekening_penerima_ws').readOnly = true;
              document.getElementById('nama_rekening_penerima_ws').style.backgroundColor='#F5F5F5';
							
              window.document.getElementById('cb_valid_rekening').checked = true;
              $('#status_valid_rekening_penerima').val('Y');
              
              $('#kode_bank_penerima_old').val(v_kode_bank_tujuan);
							$('#no_rekening_penerima_old').val(v_no_rek_tujuan);																			
            }else{
              //nama_rekening dapat diinput manual (bypass) ------------
              $('#nama_rekening_penerima_ws').val('');	
              document.getElementById('nama_rekening_penerima_ws').readOnly = false;
              document.getElementById('nama_rekening_penerima_ws').style.backgroundColor='#ffff99';
              document.getElementById('nama_rekening_penerima_ws').placeholder = "-- isikan NAMA secara manual --";
							
              window.document.getElementById('cb_valid_rekening').checked = false;
              $('#status_valid_rekening_penerima').val('T');
              $('#nama_rekening_penerima_ws').focus();
              
							$('#kode_bank_penerima_old').val(v_kode_bank_tujuan);
							$('#no_rekening_penerima_old').val(v_no_rek_tujuan);
              alert('Gagal validasi rekening,'+jdata.msg);
            }
          }
        });
      }else
      { 
        $('#nama_rekening_penerima_ws').val('');
        window.document.getElementById('cb_valid_rekening').checked = false;
        $('#status_valid_rekening_penerima').val('T');
        $('#no_rekening_penerima').focus();
      }
    }
  }
  //end validasi nomor rekening penerima ---------------------------------------
	
	function fjq_ajax_val_getlist_bank_asal()
	{	 
		//dipanggil saat pilih bank penerima ---
	}

	function fl_js_set_jeniskelamin()
	{	 
		var v_l_jenis_kelamin = $('#l_jenis_kelamin').val();
		$('#jenis_kelamin').val(v_l_jenis_kelamin);
	}
	
	//kirim email verifikasi -----------------------------------------------------
	function fl_js_val_refresh_ket_kirim_email()
	{
		var v_email = $('#email').val();
		$('#span_text_kirim_email').html('&nbsp;<em><strong><font color="#037bff">Kirimkan Email Verifikasi </font></strong></em> <br /><br /><em><font color="#009999">ke: '+v_email+'</font></em>'); 			 
	}	
	
	function fjq_ajax_val_show_kirim_email()
	{
	 	window.document.getElementById("span_kirim_email").style.display = 'block';
		window.document.getElementById("span_is_kirim_email").style.display = 'none';
		window.document.getElementById("span_kirim_sms").style.display = 'none';			 
	 	window.document.getElementById("span_verifikasi_otp_sms").style.display = 'none';
		window.document.getElementById("span_is_verified_hp").style.display = 'none';
		$('#notifikasi_via').val('EMAIL');
		$('#is_verified_hp').val('T');
		$('#kode_otp_sms').val('');
		$('#sms_id').val('');
		$('#kode_otp_sms_verified').val('');
		$('#is_verified_email').val('T');
		
		var v_email = $('#email').val();
		$('#span_text_kirim_email').html('<br /><em><font color="#009999">ke: '+v_email+'</font></em>'); 			 
	}	

	function fl_js_val_refresh_ket_kirim_email()
	{
		var v_email = $('#email').val();
		$('#span_text_kirim_email').html('<br /><em><font color="#009999">ke: '+v_email+'</font></em>'); 			 
		$('#is_verified_email').val('T');
	}	
		
  function fjq_ajax_val_kirim_email_verifikasi()
  {				 
    var v_email = $('#email').val();
    var v_nik_penerima = $('#nik_penerima').val();
		var v_nama_penerima = $('#nama_penerima').val();
		
    if (v_email == '')
		{
    	 alert('Alamat email kosong, harap lengkapi data input..!!!');
    	 $('#email').focus();				 						 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_kirim_email_verifikasi',
							v_email:v_email,
							v_nik_penerima:v_nik_penerima,
							v_nama_penerima:v_nama_penerima
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //kirim email verifikasi berhasil, lanjutkan tahapan -------
        		$('#is_verified_email').val('K');		
						window.document.getElementById("span_kirim_email").style.display = 'none';
						window.document.getElementById("span_is_kirim_email").style.display = 'block';
						document.getElementById('email').readOnly = true;
            document.getElementById('email').style.backgroundColor='#f5f5f5';
						$('#notifikasi_via').val('EMAIL');
						document.getElementById('notifikasi_via_email').disabled = true; 
						document.getElementById('notifikasi_via_sms').disabled = true;
		
						$('#span_text_is_kirim_email').html('<em><font color="#009999">Email verifikasi sudah dikirimkan ke: '+v_email+'</font></em>');
						alert(jdata.msg);								
          }else{
            //kirim email verifikasi gagal -----------------------------
						$('#is_verified_email').val('T');
						document.getElementById('email').readOnly = false;
            document.getElementById('email').style.backgroundColor='#ffff99';						
						alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end kirim email verifikasi -------------------------------------------------

	//kirim sms verifikasi -------------------------------------------------------
	function fjq_ajax_val_show_kirim_sms()
	{
	 	window.document.getElementById("span_kirim_sms").style.display = 'block';
		window.document.getElementById("span_kirim_email").style.display = 'none';
		$('#notifikasi_via').val('SMS');
		$('#is_verified_hp').val('T');
		$('#kode_otp_sms').val('');
		$('#sms_id').val('');
		$('#kode_otp_sms_verified').val('');
		$('#is_verified_email').val('T');
		
		var v_handphone = $('#handphone').val();
		$('#span_text_kirim_sms').html('&nbsp;<em><strong><font color="#037bff">Kirimkan SMS Verifikasi </font></strong></em> <br /><br /><em><font color="#009999">ke: '+v_handphone+'</font></em>'); 			 
	}

  //kirim sms verifikasi -------------------------------------------------------
  function fjq_ajax_val_kirim_sms_verifikasi()
  {				 
    var v_handphone = $('#handphone').val();
    window.document.getElementById("span_verifikasi_otp_sms").style.display = 'none';
		$('#kode_otp_sms').val('');
		$('#kode_otp_sms_verified').val('');
		
    if (v_handphone == '')
		{
    	 alert('No HP kosong, harap lengkapi data input..!!!');
    	 $('#handphone').focus();				 						 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_kirim_sms_verifikasi',
							v_handphone:v_handphone
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //kirim sms verifikasi berhasil, lanjutkan tahapan -------
            $('#kode_otp_sms').val(jdata.kode_otp_sms);
						$('#sms_id').val(jdata.sms_id);
						window.document.getElementById("span_verifikasi_otp_sms").style.display = 'block';
						window.document.getElementById("span_kirim_sms").style.display = 'none';
						
						$('#span_text_validate_kode_otp_sms').html('<em><font>(* Isikan sesuai <font color="#ff0000">Kode Verifikasi</font> yang sudah dikirimkan via SMS ke : '+v_handphone+'</font></em>');
						$('#span_text_kirim_ulang_sms').html('<em><font color="#037bff">Kirim Ulang SMS Verifikasi</font></em>'); 	
						document.getElementById('handphone').readOnly = true;
            document.getElementById('handphone').style.backgroundColor='#f5f5f5';
						
						$('#notifikasi_via').val('SMS');
						document.getElementById('notifikasi_via_email').disabled = true; 
						document.getElementById('notifikasi_via_sms').disabled = true;
												
						alert(jdata.msg);								
          }else{
            //kirim sms verifikasi gagal -----------------------------
						document.getElementById('handphone').readOnly = false;
            document.getElementById('handphone').style.backgroundColor='#ffff99';
						
						alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  
	function fjq_ajax_val_validate_kode_otp_sms()
	{
    var v_kode_otp_sms_verified = $('#kode_otp_sms_verified').val();
		var v_kode_otp_sms = $('#kode_otp_sms').val();
		var v_handphone = $('#handphone').val();
		var v_sms_id = $('#sms_id').val();
		
    if (v_kode_otp_sms_verified == '')
		{
    	 alert('Kode OTP kosong, harap lengkapi data input..!!!');
    	 $('#kode_otp_sms_verified').focus();	
    }else if (v_kode_otp_sms == '' || v_sms_id == '')
		{
    	 alert('Terjadi kesalahan, harap melakukan PENGIRIMAN ULANG sms verifikasi ..');
    	 $('#kode_otp_sms_verified').focus();				 						 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_validate_kode_otp_sms',
							v_kode_otp_sms_verified:v_kode_otp_sms_verified,
							v_kode_otp_sms:v_kode_otp_sms
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //verifikasi kode otp berhasil, tutup window -----------
        		$('#notifikasi_via').val('SMS');
						$('#is_verified_hp').val('Y');
									
        		document.getElementById('handphone').readOnly = true;
            document.getElementById('handphone').style.backgroundColor='#f5f5f5';
        		window.document.getElementById("span_is_verified_hp").style.display = 'block';
        		$('#span_text_is_verified_hp').html(' '+v_handphone+''); 
						
						window.document.getElementById("span_verifikasi_otp_sms").style.display = 'none';
						window.document.getElementById("span_kirim_sms").style.display = 'none';
						
						var v_msg = jdata.msg; 
						alert(v_msg);								
          }else{
            //verifikasi kode otp gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if	
	}						
	//end kirim sms verifikasi ---------------------------------------------------			
	//--------------------------- END NEW ----------------------------------------
</script>

<script language="javascript">			
	//--------------------------- EDIT -------------------------------------------
	function loadSelectedRecord(v_nik_penerima, fn)
	{
		if (v_nik_penerima == '') {
			return alert('Data Anak Penerima Beasiswa tidak ditemukan');
		}
		
		v_task = $('#task').val();
		
		//load data penerima beasiswa ----------------------------------------------
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5073_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatabynikpenerima',
				v_nik_penerima: v_nik_penerima
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
      		 	var v_cnt_terima_manfaat = parseInt(getValueNumber(jdata.data.dataNikAnak['CNT_TERIMA_MANFAAT']));
            var v_status_submit 		 = getValue(jdata.data.dataNikAnak['STATUS_SUBMIT']);
            var v_status_batal 			 = getValue(jdata.data.dataNikAnak['STATUS_BATAL']);
            var v_status_blm_sekolah = getValue(jdata.data.dataNikAnak['STATUS_BLM_SEKOLAH']);
						var v_status_penerima		 = getValue(jdata.data.dataNikAnak['STATUS_PENERIMA']);
						var v_nik_penerima_induk = getValue(jdata.data.dataNikAnak['NIK_PENERIMA_INDUK']);
						var v_nik_penerima_anak	 = getValue(jdata.data.dataNikAnak['NIK_PENERIMA_ANAK']);

						var v_isUpdate = "T";
						if (v_task=='edit' && (v_cnt_terima_manfaat==0 && v_status_penerima=="TERBUKA"))
						{
						 	 v_isUpdate = "Y";
						}								
						
						var v_isUpdateNoHp = "T";
						if (v_isUpdate == "Y" && getValue(jdata.data.dataNikAnak['IS_VERIFIED_HP'])!='Y')
						{
						 	v_isUpdateNoHp = "Y"; 
						}

						var v_isUpdateEmail = "T";
						if (v_isUpdate == "Y" && getValue(jdata.data.dataNikAnak['IS_VERIFIED_EMAIL'])!='K' && getValue(jdata.data.dataNikAnak['IS_VERIFIED_EMAIL'])!='Y')
						{
						 	v_isUpdateEmail = "Y"; 
						}
						
						var v_isNotifikasiVia = "T";
						if (v_isUpdateNoHp == "Y" && v_isUpdateEmail == "Y")
						{
						 	v_isNotifikasiVia = "Y"; 
						}
					
						var v_html = '';
      			v_html += '<div class="div-row">';
      			v_html += '  <div class="div-col" style="width:49%; max-height: 100%;">';
      			v_html += '    <fieldset style="min-height:320px;"><legend>Informasi Penerima Manfaat Beasiswa</legend>';
      			v_html += '    	 <div class="div-row">';
      			v_html += '    	   <br />';
      			v_html += '    	   <div class="div-col">'; 
      			
      			v_html += '		   	   <div class="form-row_kiri">';
            v_html += '		   	 	 <label style = "text-align:right;">NIK Anak &nbsp;&nbsp;*</label>';
            //v_html += '		         <input type="text" id="nik_penerima" name="nik_penerima" value="'+getValue(jdata.data.dataNikAnak['NIK_PENERIMA'])+'" maxlength="16" '+(v_isUpdate=='Y' ? 'style=\"width:170px;background-color:#ffff99;\" onblur=\"fjq_ajax_val_nikbyadminduk();\"' : 'style=\"width:170px;\" readonly class=\"disabled\"')+'>';
						v_html += '		         <input type="text" id="nik_penerima" name="nik_penerima" value="'+getValue(jdata.data.dataNikAnak['NIK_PENERIMA'])+'" maxlength="16" style="width:170px;" readonly class="disabled">';
            v_html += '          	 <input type="checkbox" id="cb_valid_nik_penerima" name="cb_valid_nik_penerima" disabled class="cebox" '+(getValue(jdata.data.dataNikAnak['STATUS_VALID_NIK_PENERIMA'])=='Y' ? 'checked' : '')+'><em><font color="#009999">Valid</font></em>';	
            v_html += '          	 <input type="hidden" id="status_valid_nik_penerima" name="status_valid_nik_penerima" value="'+getValue(jdata.data.dataNikAnak['STATUS_VALID_NIK_PENERIMA'])+'">';
      			v_html += '          	 <input type="hidden" id="nik_penerima_old" name="nik_penerima_old" value="'+getValue(jdata.data.dataNikAnak['NIK_PENERIMA'])+'">';
						v_html += '          	 <input type="hidden" id="tgl_rekam" name="tgl_rekam" value="'+getValue(jdata.data.dataNikAnak['TGL_REKAM'])+'">';
						v_html += '          	 <input type="hidden" id="status_penerima" name="status_penerima" value="'+getValue(jdata.data.dataNikAnak['STATUS_PENERIMA'])+'">';
      			v_html += '          	 <input type="hidden" id="jenis_update" name="jenis_update">';
						v_html += '			 	 	 </div>';																																							
            v_html += '		   	 	 <div class="clear"></div>';
      			
            v_html += '      		 <div class="form-row_kiri">';
            v_html += '      		 <label style = "text-align:right;">Nama Anak &nbsp;&nbsp;</label>';
            v_html += '        	 	 <input type="text" id="nama_penerima" name="nama_penerima" value="'+getValue(jdata.data.dataNikAnak['NAMA_PENERIMA'])+'" style="width:220px;" maxlength="100" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
            v_html += '      		 </div>';																																																																																														
            v_html += '      		 <div class="clear"></div>';
      			
            v_html += '      		 <div class="form-row_kiri">';
            v_html += '      		 <label style = "text-align:right;">Tempat & Tgl Lahir</label>';
            v_html += '        	 	 <input type="text" id="tempat_lahir" name="tempat_lahir" value="'+getValue(jdata.data.dataNikAnak['TEMPAT_LAHIR'])+'" style="width:146px;" maxlength="50" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
            v_html += '        	 	 <input type="text" id="tgl_lahir" name="tgl_lahir" value="'+getValue(jdata.data.dataNikAnak['TGL_LAHIR'])+'" style="width:65px;" maxlength="10" readonly class="disabled" onblur="convert_date(tgl_lahir);">';
      			v_html += '        	 	 <input style="display:none;height:14px;" id="btn_tgl_lahir" type="image" align="top" onclick="return showCalendar(\'tgl_lahir\', \'dd-mm-y\');" src="../../images/calendar.gif" />';
            v_html += '      		 </div>';																																																																																														
            v_html += '      		 <div class="clear"></div>';
      			
            v_html += '      		 <div class="form-row_kiri">';
            v_html += '      		 <label style = "text-align:right;">Jeis Kelamin</label>';
      			v_html += '        	 	 <span id="span_jenis_kelamin_lable" style="display:block;">';			
            v_html += '        	 	 	 <input type="text" id="l_jenis_kelamin2" name="l_jenis_kelamin2" value="'+getValue(jdata.data.dataNikAnak['NM_JENIS_KELAMIN'])+'" style="width:220px;" readonly class="disabled">';
      			v_html += '        	 	 </span>';
      			v_html += '        	 	 <span id="span_jenis_kelamin_listitem" style="display:none;">';
      			v_html += '        	   	 <select size="1" id="l_jenis_kelamin" name="l_jenis_kelamin" class="select_format" style="width:220px;background-color:#ffff99;" onchange="fl_js_set_jeniskelamin();">';
            v_html += '        	   	 	 <option value="">-- Pilih --</option>';
      			v_html += '        	   	 	 <option value="L">LAKI-LAKI</option>';		
      			v_html += '        	   	 	 <option value="P">PEREMPUAN</option>';
            v_html += '        	   	 </select>';
      			v_html += '        	 	 </span>';
      			v_html += '        	 	 <input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="'+getValue(jdata.data.dataNikAnak['JENIS_KELAMIN'])+'">';
            v_html += '      		 </div>';																																																																																														
            v_html += '      		 <div class="clear"></div>';
      			
      			v_html += '      		 <br />';
      			
            v_html += '		    	 <div class="form-row_kiri">';
            v_html += '		    	 <label style = "text-align:right;">Alamat Rumah &nbsp;</label>';
            v_html += '		    	 	 <textarea cols="255" rows="1" id="alamat" name="alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onblur="this.value=this.value.toUpperCase();" '+(v_isUpdate=='Y' ? 'style=\"width:220px;height:25px;background-color:#ffff99\"' : 'style=\"width:220px;height:25px;background-color:#f5f5f5\" readonly')+'>'+getValue(jdata.data.dataNikAnak['ALAMAT'])+'</textarea>';   					
            v_html += '		    	 </div>';							
            v_html += '		    	 <div class="clear"></div>';	
      			
            v_html += '		   		 <div class="form-row_kiri">';
            v_html += '		   		 <label style = "text-align:right;">Email &nbsp;&nbsp;*</label>';
            v_html += '		     	 	 <input type="text" id="email" name="email" value="'+getValue(jdata.data.dataNikAnak['EMAIL'])+'" maxlength="200" onblur="this.value=this.value.toLowerCase();fl_js_val_email(\'email\');" '+(v_isUpdateEmail=='Y' ? 'style=\"width:220px;background-color:#ffff99;\"' : 'style=\"width:220px;\" readonly class=\"disabled\"')+'>';
            v_html += '			 		 </div>';																																							
            v_html += '		   		 <div class="clear"></div>';
      			
            v_html += '		   		 <div class="form-row_kiri">';
            v_html += '		   		 <label style = "text-align:right;">No. HP &nbsp;&nbsp;*</label>';
            v_html += '		     	 	 <input type="text" id="handphone" name="handphone" value="'+getValue(jdata.data.dataNikAnak['HANDPHONE'])+'" maxlength="18" onblur="fl_js_val_numeric(\'handphone\');" '+(v_isUpdateNoHp=='Y' ? 'style=\"width:220px;background-color:#ffff99;\"' : 'style="width:220px;" readonly class=\"disabled\"')+'>';
            v_html += '			 		 </div>';																																							
            v_html += '		   		 <div class="clear"></div>';
      			v_html += '      		 <br />';
						
            v_html += '		   		 <div class="form-row_kiri">';
            v_html += '		   		 <label style = "text-align:right;">Nama Ortu/Wali &nbsp;&nbsp;*</label>';
            v_html += '		     	 	 <input type="text" id="nama_ortu_wali" name="nama_ortu_wali" value="'+getValue(jdata.data.dataNikAnak['NAMA_ORTU_WALI'])+'" maxlength="100" onblur="this.value=this.value.toUpperCase();" '+(v_isUpdate=='Y' ? 'style=\"width:200px;background-color:#ffff99;\"' : 'style=\"width:200px;\" readonly class=\"disabled\"')+'>';
            v_html += '			 		 </div>';																																							
            v_html += '		   		 <div class="clear"></div>';
      			
            v_html += '		    	 <div class="form-row_kiri">';
            v_html += '		    	 <label style = "text-align:right;">Keterangan &nbsp;</label>';
            v_html += '		    	 	 <textarea cols="255" rows="1" id="keterangan" name="keterangan" onblur="this.value=this.value.toUpperCase();" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" '+(v_isUpdate=='Y' ? 'style=\"width:180px;height:40px;\"' : 'style=\"width:180px;height:40px;background-color:#f5f5f5;\" readonly')+'>'+getValue(jdata.data.dataNikAnak['KETERANGAN'])+'</textarea>';   					
            v_html += '		    	 </div>';							
            v_html += '		    	 <div class="clear"></div>';
      			v_html += '    		 </div>';
      			
            v_html += '    		 <div class="div-col" style="width: 150px;text-align:center;">';
            v_html += '      	 	 <input id="nik_penerima_foto" name="nik_penerima_foto" type="image" align="center" src="../../images/nopic.png" style="height: 95px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>';
            v_html += '    	 	 </div>';
      			v_html += '    	 </div>';																																	
      			v_html += '    </fieldset>';
      			v_html += '	 </div>';
      			
          	v_html += '  <div class="div-col" style="width:1%;">';
            v_html += '  </div>';
      
          	v_html += '  <div class="div-col-right" style="width:50%;">';
            v_html += '    <div class="div-row">';
      			v_html += '    	 <fieldset style="min-height:150px;width:96%;" id="fieldset_stsekolah"><legend></legend>';
      			v_html += '    	   <div style="text-align:center;">';
      			v_html += '    	     <br />'; 	    				
            v_html += '    	   	 <strong><em><font color="#009999">Apakah anak sudah menempuh pendidikan di sekolah?</font></em></strong><br />';
      			v_html += '    	   	 <input type="radio" name="rg_status_blm_sekolah" id="rg_status_blm_sekolah_y" value="Y" onClick="fl_js_set_fieldset_stsekolah();" '+(v_isUpdate=='Y' ? '' : 'disabled')+'>&nbsp;<strong><font color="#009999">Belum</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            v_html += '    	   	 <input type="radio" name="rg_status_blm_sekolah" id="rg_status_blm_sekolah_t" value="T" onClick="fl_js_set_fieldset_stsekolah();" '+(v_isUpdate=='Y' ? '' : 'disabled')+'>&nbsp;<font color="#009999">Sudah</font></strong>';
      			v_html += '          <input type="hidden" id="status_blm_sekolah" name="status_blm_sekolah" value="'+getValue(jdata.data.dataNikAnak['STATUS_BLM_SEKOLAH'])+'">';			 
						v_html += '    	 	 </div>';																																	
      			v_html += '    	 </fieldset>';
      			
      			v_html += '      <span id="span_sudahsekolah" style="display:none;">';									
      			v_html += '        <fieldset style="min-height:150px;width:96%;"><legend>Jika layak mendapatkan Beasiswa maka manfaat akan Dibayarkan ke :</legend>';
      			v_html += '    	   	 <br />';
            v_html += '        	 <div class="form-row_kiri">';
            v_html += '        	 <label style = "text-align:right;">Bank</label>'; 
            v_html += '          	 <input type="text" id="nama_bank_penerima" name="nama_bank_penerima" value="'+getValue(jdata.data.dataNikAnak['BANK_PENERIMA'])+'" readonly '+(v_isUpdate=='Y' ? 'style=\"width:310px;background-color:#ffff99\"' : 'style=\"width:310px;\" class=\"disabled\"')+'>';
            v_html += '          	 <input type="hidden" id="kode_bank_penerima" name="kode_bank_penerima" value="'+getValue(jdata.data.dataNikAnak['KODE_BANK_PENERIMA'])+'" style="width:100px;">';
            v_html += '          	 <input type="hidden" id="id_bank_penerima" name="id_bank_penerima" value="'+getValue(jdata.data.dataNikAnak['ID_BANK_PENERIMA'])+'" style="width:100px;">';
            v_html += '          	 <a style="display:null;" id="btn_lov_bank_penerima" href="#" onclick="fl_js_get_lov_bank_penerima();">';							
            v_html += '          	 <img src="../../images/help.png" alt="Cari Bank" border="0" style="height:19px;" align="absmiddle"></a>';
            v_html += '          	 <input type="hidden" id="kode_bank_penerima_old" name="kode_bank_penerima_old" value="'+getValue(jdata.data.dataNikAnak['KODE_BANK_PENERIMA'])+'">';
            v_html += '        	 </div>';																																																	
            v_html += '        	 <div class="clear"></div>'; 
      			                  
            v_html += '        	 <div class="form-row_kiri">';
            v_html += '        	 <label style = "text-align:right;">No Rekening</label>';
            v_html += '          	 <input type="text" id="no_rekening_penerima" name="no_rekening_penerima" value="'+getValue(jdata.data.dataNikAnak['NO_REKENING_PENERIMA'])+'" onblur="fjq_ajax_val_no_rekening_penerima();" maxlength="30" '+(v_isUpdate=='Y' ? 'style=\"width:150px;background-color:#ffff99\"' : 'style=\"width:150px;\" readonly class=\"disabled\"')+'>';
            v_html += '          	 <input type="hidden" id="nama_rekening_penerima" name="nama_rekening_penerima" value="'+getValue(jdata.data.dataNikAnak['NAMA_REKENING_PENERIMA'])+'" maxlength="100" readonly class="disabled" style="width:120px;background-color:#F5F5F5">';
            v_html += '          	 <input type="text" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" value="'+getValue(jdata.data.dataNikAnak['NAMA_REKENING_PENERIMA'])+'" maxlength="100" style="width:150px;background-color:#F5F5F5" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
            v_html += '          	 <input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" disabled class="cebox" '+(getValue(jdata.data.dataNikAnak['STATUS_VALID_REKENING_PENERIMA'])=='Y' ? 'checked' : '')+'><em><font color="#009999">Valid</font></em>';	
            v_html += '          	 <input type="hidden" id="status_valid_rekening_penerima" name="status_valid_rekening_penerima" value="'+getValue(jdata.data.dataNikAnak['STATUS_VALID_REKENING_PENERIMA'])+'">';
            v_html += '          	 <input type="hidden" id="no_rekening_penerima_old" name="no_rekening_penerima_old" value="'+getValue(jdata.data.dataNikAnak['NO_REKENING_PENERIMA'])+'">';
            v_html += '        	 </div>';																																																																																															
            v_html += '        	 <div class="clear"></div>';
      			v_html += '    	   	 <br />';		 			
      			v_html += '      	 </fieldset>';
      			
						v_html += '      	 <span id="span_notifikasi" style="display:none;">';
      			v_html += '      	 <fieldset style="min-height:134px;width:96%;"><legend></legend>';																																																																			
      			v_html += '    	   	 <div style="text-align:center;">';
      			v_html += '    	     	 <br />';				
            v_html += '    	   	 	 <strong><em><font color="#009999">Notifikasi untuk pengambilan beasiswa akan dikirimkan melalui ? </font></em> </strong><br />';
      			v_html += '    	   	 	 <input type="radio" name="rg_notifikasi_via" id="notifikasi_via_email" value="EMAIL" '+(v_isNotifikasiVia=='Y' ? '' : 'disabled')+'>&nbsp;<strong><font  color="#009999">Email</font>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
            v_html += '    	   	 	 <input type="radio" name="rg_notifikasi_via" id="notifikasi_via_sms" value="SMS" onclick="fjq_ajax_val_popup_verifikasihp();" '+(v_isNotifikasiVia=='Y' ? '' : 'disabled')+'>&nbsp;<font color="#009999">SMS</font></strong>';
      			v_html += '          	 <input type="hidden" id="notifikasi_via" name="notifikasi_via" value="'+getValue(jdata.data.dataNikAnak['NOTIFIKASI_VIA'])+'">';
						v_html += '          	 <input type="hidden" id="is_verified_hp" name="is_verified_hp" value="'+getValue(jdata.data.dataNikAnak['IS_VERIFIED_HP'])+'">';
      			v_html += '          	 <input type="hidden" id="kode_otp_sms_verified" name="kode_otp_sms_verified">';
      			v_html += '          	 <input type="hidden" id="sms_id" name="sms_id" value="'+getValue(jdata.data.dataNikAnak['SMS_ID'])+'">';
						v_html += '          	 <input type="hidden" id="is_verified_email" name="is_verified_email" value="'+getValue(jdata.data.dataNikAnak['IS_VERIFIED_EMAIL'])+'">';      			
						v_html += '    	   	 	 <br />';			
      			v_html += '    	 	 	 </div>';
      			v_html += '      		 <span id="span_is_verified_email_atau_hp" style="display:none;">';
      			v_html += '    	   	 	 <div style="text-align:center;">';
      			v_html += '    	     	 	<br />';
      			v_html += '    	     	 	<span id="span_text_is_verified_email_atau_hp"></span>';
						v_html += '    	     	 	<span id="span_is_kirim_ulang_email" style="display:none;">';
      			v_html += '    	     	 		<br /><br />';
      			v_html += '          	 		(<a id="btn_kirim_ulang_email" href="#" onClick="if(confirm(\'Apakah anda yakin akan mengirimkan ulang email verifikasi..?\')) fjq_ajax_val_kirim_email_verifikasi();">';							
            v_html += '          	 		<img src="../../images/copyz.png" alt="kirim email verifikasi" border="0" style="height:15px;" align="absmiddle"><em><font color="#037bff">Kirimkan Ulang Email Verifikasi </font></em>';
      			v_html += '    	 	 	 	 		</a>)';
						v_html += '    	     	 	</span>';
      			v_html += '    	 	 	 	 </div>';
      			v_html += '      	 	 </span>';						
      			v_html += '      	 </fieldset>';
						v_html += '      	 </span>'; 
      			v_html += '      </span>'; 
      			
      			v_html += '	 	 </div>';
      			v_html += '	 </div>';
      																								 
      			v_html += '</div>';
      			
            if (v_html !="")
            {
             	$('#formEntry').html(v_html); 
            }	
      			
						$('#l_jenis_kelamin').val(getValue(jdata.data.dataNikAnak['JENIS_KELAMIN']));
        		$('#nik_tk').val(getValue(jdata.data.dataNikAnak['NIK_TK']));
						$('#nama_tk').val(getValue(jdata.data.dataNikAnak['NAMA_TK']));

						if (v_isUpdate=='Y')
						{
						 	window.document.getElementById("btn_lov_bank_penerima").style.display = ''; 
						}else
						{
						 	window.document.getElementById("btn_lov_bank_penerima").style.display = 'none';	 
						}
												
						if (getValue(jdata.data.dataNikAnak['NIK_PENERIMA'])!='')
        		{
        		 	$('#nik_penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + getValue(jdata.data.dataNikAnak['NIK_PENERIMA']));
        		}
						
						fl_js_setCheckedValueRadioButton('rg_status_blm_sekolah', getValue(jdata.data.dataNikAnak['STATUS_BLM_SEKOLAH']));
						fl_js_setCheckedValueRadioButton('rg_notifikasi_via', getValue(jdata.data.dataNikAnak['NOTIFIKASI_VIA']));
						
						//set status verified notifikasi -----------------------------------
						if (getValue(jdata.data.dataNikAnak['NOTIFIKASI_VIA']) == "EMAIL")
						{
  						//notifikasi via email -------------------------------------------
							window.document.getElementById("span_is_verified_email_atau_hp").style.display = 'block';
							if (getValue(jdata.data.dataNikAnak['IS_VERIFIED_EMAIL'])=='K')
  						{
  						 	$('#span_text_is_verified_email_atau_hp').html('<em><font color="#009999">Email verifikasi sudah dikirimkan ke: '+getValue(jdata.data.dataNikAnak['EMAIL'])+'<br />Status: masih menunggu email konfirmasi dari anak penerima manfaat beasiswa.</font></em>');
								window.document.getElementById("span_is_kirim_ulang_email").style.display = 'block';
  							
							}else if (getValue(jdata.data.dataNikAnak['IS_VERIFIED_EMAIL'])=='Y')
  						{
  						 	$('#span_text_is_verified_email_atau_hp').html('<em><font color="#009999">Status: Email Verified.</font></em>');
								window.document.getElementById("span_is_kirim_ulang_email").style.display = 'none';
							}else
  						{
  						 	$('#span_text_is_verified_email_atau_hp').html('<em><font color="#009999">Status: Email verifikasi belum dikirimkan.</font></em>');
								window.document.getElementById("span_is_kirim_ulang_email").style.display = 'none';
  						}
						}else if (getValue(jdata.data.dataNikAnak['NOTIFIKASI_VIA']) == "SMS")
						{
  						//notifikasi via sms ---------------------------------------------
							window.document.getElementById("span_is_verified_email_atau_hp").style.display = 'block';
							window.document.getElementById("span_is_kirim_ulang_email").style.display = 'none';
							if (getValue(jdata.data.dataNikAnak['IS_VERIFIED_HP'])=='Y')
  						{
  						 	$('#span_text_is_verified_email_atau_hp').html('<em><font color="#009999">Status Handphone: <strong>Verified</strong>.</font></em>');
							}else
  						{
  						 	$('#span_text_is_verified_email_atau_hp').html('<em><font color="#009999">Status: SMS verifikasi belum dikirimkan.</font></em>');
  						}						
						}else
						{
							window.document.getElementById("span_is_verified_email_atau_hp").style.display = 'none';						
						}
						//end set status verified notifikasi -------------------------------
												
						fl_js_set_fieldset_stsekolah();
												
						if (v_status_penerima=='GANTI_PENERIMA')
						{
							window.document.getElementById("keterangan").style.height = '35px'; 
							window.document.getElementById("keterangan").style.width = '200px'; 
						}else
						{
						 	window.document.getElementById("keterangan").style.height = '15px';
							window.document.getElementById("keterangan").style.width = '180px';  
						}
						
						//set button -------------------------------------------------------
						if (v_cnt_terima_manfaat>0)
						{
  						if (v_task=='edit')
  						{
    						window.document.getElementById("span_button_edit").style.display = 'block';
								window.document.getElementById("btn_doSaveUpdate").style.display = 'none';
  							window.document.getElementById("btn_doDelete").style.display = 'none';
  							window.document.getElementById("btn_doSubmit").style.display = 'none';
								window.document.getElementById("btn_doSubmitPenundaan").style.display = 'none';	
  							window.document.getElementById("btn_doBatal").style.display = 'none';	
  							window.document.getElementById("btn_CetakKartu").style.display = 'block';
								if ((v_status_penerima=='AKTIF' || v_status_penerima=='TUNDA') && v_nik_penerima_anak=='')
								{
								 	//17/04/2021 - untuk penggantian anak yg sudah terima sementara ditutup dulu menunggu perbaikan hitung manfaat utk sisa plafon
									//window.document.getElementById("btn_GantiPenerima").style.display = 'block';
									window.document.getElementById("btn_GantiPenerima").style.display = 'none';
									window.document.getElementById("btn_doKoreksiData").style.display = 'block';	
								}else
								{
								 	window.document.getElementById("btn_GantiPenerima").style.display = 'none';	
									window.document.getElementById("btn_doKoreksiData").style.display = 'none';	 
								}
							}																																 						 	 
						}else
						{
  						if (v_task=='edit')
  						{
    						window.document.getElementById("span_button_edit").style.display = 'block';

    						if (v_status_batal=="Y")
    						{
    						 	window.document.getElementById("span_button_edit").style.display = 'none';
									window.document.getElementById("btn_doSaveUpdate").style.display = 'none';
    							window.document.getElementById("btn_doDelete").style.display = 'none';
    							window.document.getElementById("btn_doSubmit").style.display = 'none';
  								window.document.getElementById("btn_doSubmitPenundaan").style.display = 'none';	
    							window.document.getElementById("btn_doBatal").style.display = 'none';	
    							window.document.getElementById("btn_CetakKartu").style.display = 'none';
									window.document.getElementById("btn_GantiPenerima").style.display = 'none';
									//non aktifkan button koreksi profile ---
									window.document.getElementById("btn_doKoreksiData").style.display = 'none';	
									window.document.getElementById("btn_doUbahStatusTunda").style.display = 'none';						
    						}else
    						{
      						if (v_status_submit=="T")
      						{
      						 	//DATA BELUM DISUBMIT --------------------------------------
										window.document.getElementById("btn_doSaveUpdate").style.display = 'block';
      							window.document.getElementById("btn_doDelete").style.display = 'block';
  									if (v_status_blm_sekolah=='Y')
  									{
      							  window.document.getElementById("btn_doSubmit").style.display = 'none';
  										window.document.getElementById("btn_doSubmitPenundaan").style.display = 'block';
      							}else
  									{
  									 	window.document.getElementById("btn_doSubmit").style.display = 'block';
  										window.document.getElementById("btn_doSubmitPenundaan").style.display = 'none';	 
  									}
  									window.document.getElementById("btn_doBatal").style.display = 'none';
      							window.document.getElementById("btn_CetakKartu").style.display = 'none';
  									
  									//jika nik anak tidak valid maka set nama dapat diubah -------
  									if (getValue(jdata.data.dataNikAnak['STATUS_VALID_NIK_PENERIMA'])=='T')
  									{
        							document.getElementById('nama_penerima').readOnly = false;
                      document.getElementById('nama_penerima').style.backgroundColor='#ffff99';
        							
        							document.getElementById('tempat_lahir').readOnly = false;
                      document.getElementById('tempat_lahir').style.backgroundColor='#ffff99';
        							
        							document.getElementById('tgl_lahir').readOnly = false;
                      document.getElementById('tgl_lahir').style.backgroundColor='#ffff99';
        							window.document.getElementById("btn_tgl_lahir").style.display = '';
        							window.document.getElementById("tempat_lahir").style.width = '125px';
        							
        							window.document.getElementById("span_jenis_kelamin_lable").style.display = 'none';
        							window.document.getElementById("span_jenis_kelamin_listitem").style.display = 'block';									 	 
  									}
										
										//nonaktifkan button koreksi profile ---
										window.document.getElementById("btn_doKoreksiData").style.display = 'none';
										window.document.getElementById("btn_doUbahStatusTunda").style.display = 'none';	
            			}else
      						{
      						 	//DATA SUDAH DISUBMIT --------------------------------------
										window.document.getElementById("btn_doSaveUpdate").style.display = 'none';
      							window.document.getElementById("btn_doDelete").style.display = 'none';
      							window.document.getElementById("btn_doSubmit").style.display = 'none';
  									window.document.getElementById("btn_doSubmitPenundaan").style.display = 'none';	
  									
  									if (getValue(jdata.data.dataNikAnak['STATUS_PENERIMA'])=='TUNDA')
  									{
      							 	window.document.getElementById("btn_doBatal").style.display = 'none';	
      							 	window.document.getElementById("btn_CetakKartu").style.display = 'none';
											window.document.getElementById("btn_doUbahStatusTunda").style.display = 'block'; 
  									}else
  									{
  									 	window.document.getElementById("btn_doBatal").style.display = 'block';
											window.document.getElementById("btn_doUbahStatusTunda").style.display = 'none';	
      							 	if (v_cnt_terima_manfaat>0)
											{
											 	window.document.getElementById("btn_CetakKartu").style.display = 'block';
											}else
											{
											 	window.document.getElementById("btn_CetakKartu").style.display = 'none';
											}
  									}
  									if ((v_status_penerima=='AKTIF' || v_status_penerima=='TUNDA') && v_nik_penerima_anak=='')
    								{
    								 	window.document.getElementById("btn_GantiPenerima").style.display = 'block';
											window.document.getElementById("btn_doKoreksiData").style.display = 'block';
    								}else
    								{
    								 	window.document.getElementById("btn_GantiPenerima").style.display = 'none';	
											window.document.getElementById("btn_doKoreksiData").style.display = 'none'; 
    								}
										
      						}
    						}
  						}
						}
						//end set button ---------------------------------------------------
						
						//set footer keterangan --------------------------------------------
						if (v_task=='edit')
						{
							$("#span_footer_keterangan_edit").html('<div class="div-footer-content" style="width:95%;"><div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div><li style="margin-left:15px;">Klik tombol <font color="#ff0000"> SUBMIT DATA </font> agar NIK anak penerima beasiswa dapat digunakan dalam penetapan beasiswa pada menu Penetapan Klaim.</li></div>');	 
						}else
						{
						 	$("#span_footer_keterangan_edit").html('');	 
						}
						//end set footer keterangan ----------------------------------------
						var v_ket_terima_manfaat = v_cnt_terima_manfaat;
						if (v_cnt_terima_manfaat>0)
						{
						 	v_ket_terima_manfaat = 'SUDAH DILAKUKAN PENETAPAN BEASISWA UNTUK '+v_cnt_terima_manfaat+' TAHUN AJARAN'; 
						}else
						{
						 	v_ket_terima_manfaat = 'BELUM PERNAH DILAKUKAN PENETAPAN BEASISWA'; 	 
						}
						$('#span_right_title').html('STATUS : '+getValue(jdata.data.dataNikAnak['STATUS_PENERIMA'])+' ('+v_ket_terima_manfaat+')');
												
						if (fn && fn.success) {
							fn.success();
						}
					} else {
						alert(jdata.msg);
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
	//------------------------- END EDIT -----------------------------------------		
</script>

<script language="javascript">		
	// ------------------------------ BUTTON TASK --------------------------------
	//do save insert data penerima beasiswa --------------------------------------
  function fjq_ajax_val_insert_penerima_beasiswa()
  {				 
  	var v_nik_tk											= $('#nik_tk').val();
  	var v_nama_tk											= $('#nama_tk').val();
  	var v_nik_penerima								= $('#nik_penerima').val();
    var v_status_valid_nik_penerima		= $('#status_valid_nik_penerima').val();
  	var v_nama_penerima								= $('#nama_penerima').val();
    var v_tempat_lahir								= $('#tempat_lahir').val();
    var v_tgl_lahir										= $('#tgl_lahir').val();
    var v_jenis_kelamin								= $('#jenis_kelamin').val();
    var v_alamat											= $('#alamat').val();
    var v_email												= $('#email').val();
    var v_handphone										= $('#handphone').val();
    var v_nama_ortu_wali							= $('#nama_ortu_wali').val();
    var v_keterangan									= $('#keterangan').val();
    var v_status_blm_sekolah					= $('#status_blm_sekolah').val();
    var v_nama_bank_penerima					= $('#nama_bank_penerima').val();
    var v_kode_bank_penerima					= $('#kode_bank_penerima').val();
    var v_id_bank_penerima						= $('#id_bank_penerima').val();
    var v_no_rekening_penerima				= $('#no_rekening_penerima').val();
    var v_nama_rekening_penerima			= $('#nama_rekening_penerima_ws').val();
    var v_status_valid_rekening_penerima = $('#status_valid_rekening_penerima').val();
		var v_notifikasi_via 							= $('#notifikasi_via').val();
		var v_is_verified_hp							= $('#is_verified_hp').val() == '' ? 'T' : $('#is_verified_hp').val();
		var v_kode_otp_sms_verified				= $('#kode_otp_sms_verified').val();
		var v_sms_id											= $('#sms_id').val();
		var v_is_verified_email						= $('#is_verified_email').val() == '' ? 'T' : $('#is_verified_email').val();
		var v_nik_penerima_induk					= $('#nik_penerima_induk').val();
		var v_nik_penerima_anak						= $('#nik_penerima_anak').val();
		var v_jenis_insert								= $('#jenis_insert').val();
		var v_ket_penggantian							= $('#ket_penggantian').val();
		
		if (v_jenis_insert=='')
		{
		 	v_jenis_insert = 'BARU';
			v_ket_penggantian = ''; 
		}
		
		if (v_status_blm_sekolah=='Y')
		{
		 	//jika anak belum sekolah maka reset value utk bank dan notifikasi -------
      v_nama_bank_penerima					= '';
      v_kode_bank_penerima					= '';
      v_id_bank_penerima						= '';
      v_no_rekening_penerima				= '';
      v_nama_rekening_penerima			= '';
      v_status_valid_rekening_penerima = '';
      v_notifikasi_via							= '';		 
		}
		
		//notifikasi via sms/email saat belum diaktifkan, set value default --------
		if (v_notifikasi_via=='')
		{
		 	v_notifikasi_via = 'UNIDTIFIED';  	 
		}
		
    if (v_nik_tk == '' || v_nama_tk=='')
		{
    	alert('NIK/Nama Peserta kosong, harap melengkapi data input..!!!');
    }else if (v_nik_penerima == '')
  	{
    	alert('NIK anak kosong, harap melengkapi data input..!!!');
    }else if (v_nama_penerima == '')
  	{
    	alert('Nama anak kosong, harap memperhatikan data input..!!!');
    }else if (v_tgl_lahir == '')
  	{
    	alert('Tgl Lahir anak kosong, harap memperhatikan data input..!!!');
    }else if (v_handphone == '')
  	{
    	alert('No. Hp kosong, harap melengkapi data input..!!!');
    }else if (v_email == '')
  	{
    	alert('Alamat email kosong, harap melengkapi data input..!!!');
    }else if (v_alamat == '')
  	{
    	alert('Alamat rumah kosong, harap melengkapi data input..!!!');
    }else if (v_nama_ortu_wali == '')
  	{
    	alert('Nama Orang Tua/Wali kosong, harap melengkapi data input..!!!');
    }else if (v_status_blm_sekolah != 'Y' && v_status_blm_sekolah != 'T')
  	{
    	alert('Pilihan anak sudah sekolah atau belum masih kosong, harap melengkapi data input..!!!');																											 						 				 
    }else if ((v_status_blm_sekolah == 'T') && (v_nama_bank_penerima == '' || v_kode_bank_penerima == '' || v_no_rekening_penerima == '' || v_nama_rekening_penerima == '' || (v_notifikasi_via != 'EMAIL' && v_notifikasi_via != 'SMS' && v_notifikasi_via != 'UNIDTIFIED')))
  	{
    	if (v_nama_bank_penerima == '')
			{
			 	 alert('Nama bank kosong, harap melengkapi data input..!');
			}else if (v_kode_bank_penerima == '')
			{
			 	 alert('Kode bank kosong, harap melakukan pemilihan ulang dari LOV bank..!');
			}else if (v_no_rekening_penerima == '')
			{
			 	 alert('Nomor rekening kosong, harap melengkapi data input..!');
			}else if (v_nama_rekening_penerima == '')
			{
			 	 alert('Nama rekening kosong, harap memperhatikan data input..!');
			}else if (v_notifikasi_via != 'EMAIL' && v_notifikasi_via != 'SMS' && v_notifikasi_via != 'UNIDTIFIED')
			{
			 	 alert('Pilihan notifikasi email/sms masih kosong, harap melengkapi data input..!');
			}
    }else if (v_notifikasi_via == 'SMS' && (v_is_verified_hp != 'Y' || v_kode_otp_sms_verified=='' || v_sms_id==''))
  	{
		 	alert('Untuk pengiriman notifikasi via SMS maka no hp harus sudah terverifikasi. Klik ulang pilihan sms kemudian lakukan verifikasi no hp terlebih dahulu..!');													 				 
    }else if (v_notifikasi_via == 'EMAIL' && v_is_verified_email != 'K')
  	{
		 	alert('Untuk pengiriman notifikasi via EMAIL maka email verifikasi harus sudah dikirimkan. <br />Klik Kirimkan Email Verifikasi terlebih dahulu..!');
    }else if (v_jenis_insert == 'PENGGANTIAN' && (v_nik_penerima_induk == '' || v_ket_penggantian == ''))
  	{
		 	alert('Terjadi kesalahan, untuk proses penggantian anak maka NIK yg diganti tidak boleh kosong. <br />Harap mengulangi proses Penggantian Anak Penerima Manfaat Beasiswa..!');													 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_insert_penerima_beasiswa',
              v_nik_tk												 :v_nik_tk,
              v_nama_tk												 :v_nama_tk,
              v_nik_penerima									 :v_nik_penerima,
              v_status_valid_nik_penerima			 :v_status_valid_nik_penerima,
              v_nama_penerima									 :v_nama_penerima,
              v_tempat_lahir									 :v_tempat_lahir,
              v_tgl_lahir											 :v_tgl_lahir,
              v_jenis_kelamin									 :v_jenis_kelamin,
              v_alamat												 :v_alamat,
              v_email													 :v_email,
              v_handphone											 :v_handphone,
              v_nama_ortu_wali								 :v_nama_ortu_wali,
              v_keterangan										 :v_keterangan,
              v_status_blm_sekolah					 	 :v_status_blm_sekolah,
              v_nama_bank_penerima						 :v_nama_bank_penerima,
              v_kode_bank_penerima						 :v_kode_bank_penerima,
              v_id_bank_penerima							 :v_id_bank_penerima,
              v_no_rekening_penerima					 :v_no_rekening_penerima,
              v_nama_rekening_penerima				 :v_nama_rekening_penerima,
              v_status_valid_rekening_penerima :v_status_valid_rekening_penerima,
              v_notifikasi_via								 :v_notifikasi_via,
							v_is_verified_hp								 :v_is_verified_hp,
							v_kode_otp_sms_verified					 :v_kode_otp_sms_verified,
							v_sms_id					 							 :v_sms_id,
							v_is_verified_email							 :v_is_verified_email,
							v_nik_penerima_induk						 :v_nik_penerima_induk,
							v_nik_penerima_anak							 :v_nik_penerima_anak,
							v_jenis_insert							 		 :v_jenis_insert,
							v_ket_penggantian							 	 :v_ket_penggantian
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //simpan berhasil, reload form -------------
						$('#task').val('edit');
            $('#nik_penerima_temp').val(v_nik_penerima);
						$('#editid').val(v_nik_penerima);
						alert(jdata.msg);
  					reloadFormUtama();														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do save insert data penerima beasiswa ----------------------------------
		
  //do save update data penerima beasiswa --------------------------------------
  function fjq_ajax_val_update_penerima_beasiswa()
  {				 
  	var v_nik_tk											= $('#nik_tk').val();
  	var v_nama_tk											= $('#nama_tk').val();
  	var v_nik_penerima								= $('#nik_penerima').val();
    var v_status_valid_nik_penerima		= $('#status_valid_nik_penerima').val();
  	var v_nama_penerima								= $('#nama_penerima').val();
    var v_tempat_lahir								= $('#tempat_lahir').val();
    var v_tgl_lahir										= $('#tgl_lahir').val();
    var v_jenis_kelamin								= $('#jenis_kelamin').val();
    var v_alamat											= $('#alamat').val();
    var v_email												= $('#email').val();
    var v_handphone										= $('#handphone').val();
    var v_nama_ortu_wali							= $('#nama_ortu_wali').val();
    var v_keterangan									= $('#keterangan').val();
    var v_status_blm_sekolah					= $('#status_blm_sekolah').val();
    var v_nama_bank_penerima					= $('#nama_bank_penerima').val();
    var v_kode_bank_penerima					= $('#kode_bank_penerima').val();
    var v_id_bank_penerima						= $('#id_bank_penerima').val();
    var v_no_rekening_penerima				= $('#no_rekening_penerima').val();
    var v_nama_rekening_penerima			= $('#nama_rekening_penerima_ws').val();
    var v_status_valid_rekening_penerima = $('#status_valid_rekening_penerima').val();
		var v_notifikasi_via 							= $('#notifikasi_via').val();
		var v_status_penerima							= $('#status_penerima').val();
		var v_jenis_update								= $('#jenis_update').val();
		if (v_jenis_update == '')
		{
		 	v_jenis_update = 'UNIDTIFIED';
		}
		
		if (v_status_blm_sekolah=='Y')
		{
		 	//jika anak belum sekolah maka reset value utk bank dan notifikasi -------
      v_nama_bank_penerima					= '';
      v_kode_bank_penerima					= '';
      v_id_bank_penerima						= '';
      v_no_rekening_penerima				= '';
      v_nama_rekening_penerima			= '';
      v_status_valid_rekening_penerima = '';
      v_notifikasi_via							= '';		 
		}

		//notifikasi via sms/email saat belum diaktifkan, set value default --------
		if (v_notifikasi_via=='')
		{
		 	v_notifikasi_via = 'UNIDTIFIED';  	 
		}
				
    if (v_nik_tk == '' || v_nama_tk==''){
    	alert('NIK/Nama Peserta kosong, harap melengkapi data input..!!!');
    }else if (v_jenis_update != 'PROFILE' && v_jenis_update != 'STATUS_SEKOLAH' && v_jenis_update != 'MEDIA_NOTIFIKASI')
  	{
    	alert('Terjadi kesalaham, Jenis Update tidak teridentifikasi, tutup menu kemudian dicoba kembali..!');			
    }else if (v_nik_penerima == '')
  	{
    	alert('NIK anak kosong, harap melengkapi data input..!!!');
    }else if (v_nama_penerima == '')
  	{
    	alert('Nama anak kosong, harap memperhatikan data input..!!!');
    }else if (v_tgl_lahir == '')
  	{
    	alert('Tgl Lahir anak kosong, harap memperhatikan data input..!!!');
    }else if (v_handphone == '')
  	{
    	alert('No. Hp kosong, harap melengkapi data input..!!!');
    }else if (v_email == '')
  	{
    	alert('Alamat email kosong, harap melengkapi data input..!!!');
    }else if (v_alamat == '')
  	{
    	alert('Alamat rumah kosong, harap melengkapi data input..!!!');
    }else if (v_nama_ortu_wali == '')
  	{
    	alert('Nama Orang Tua/Wali kosong, harap melengkapi data input..!!!');
    }else if (v_status_blm_sekolah != 'Y' && v_status_blm_sekolah != 'T')
  	{
    	alert('Pilihan anak sudah sekolah atau belum masih kosong, harap melengkapi data input..!!!');																											 						 				 
    }else if ((v_status_blm_sekolah == 'T') && (v_nama_bank_penerima == '' || v_kode_bank_penerima == '' || v_no_rekening_penerima == '' || v_nama_rekening_penerima == '' || (v_notifikasi_via != 'EMAIL' && v_notifikasi_via != 'SMS' && v_notifikasi_via != 'UNIDTIFIED')))
  	{
    	if (v_nama_bank_penerima == '')
			{
			 	 alert('Nama bank kosong, harap melengkapi data input..!');
			}else if (v_kode_bank_penerima == '')
			{
			 	 alert('Kode bank kosong, harap melakukan pemilihan ulang dari LOV bank..!');
			}else if (v_no_rekening_penerima == '')
			{
			 	 alert('Nomor rekening kosong, harap melengkapi data input..!');
			}else if (v_nama_rekening_penerima == '')
			{
			 	 alert('Nama rekening kosong, harap memperhatikan data input..!');
			}else if (v_notifikasi_via != 'EMAIL' && v_notifikasi_via != 'SMS' && v_notifikasi_via != 'UNIDTIFIED')
			{
			 	 alert('Pilihan notifikasi email/sms masih kosong, harap melengkapi data input..!');
			}												 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_update_penerima_beasiswa',
              v_nik_tk												 :v_nik_tk,
              v_nama_tk												 :v_nama_tk,
              v_nik_penerima									 :v_nik_penerima,
              v_status_valid_nik_penerima			 :v_status_valid_nik_penerima,
              v_nama_penerima									 :v_nama_penerima,
              v_tempat_lahir									 :v_tempat_lahir,
              v_tgl_lahir											 :v_tgl_lahir,
              v_jenis_kelamin									 :v_jenis_kelamin,
              v_alamat												 :v_alamat,
              v_email													 :v_email,
              v_handphone											 :v_handphone,
              v_nama_ortu_wali								 :v_nama_ortu_wali,
              v_keterangan										 :v_keterangan,
              v_status_blm_sekolah					 	 :v_status_blm_sekolah,
              v_nama_bank_penerima						 :v_nama_bank_penerima,
              v_kode_bank_penerima						 :v_kode_bank_penerima,
              v_id_bank_penerima							 :v_id_bank_penerima,
              v_no_rekening_penerima					 :v_no_rekening_penerima,
              v_nama_rekening_penerima				 :v_nama_rekening_penerima,
              v_status_valid_rekening_penerima :v_status_valid_rekening_penerima,
              v_notifikasi_via								 :v_notifikasi_via,
							v_status_penerima								 :v_status_penerima,
							v_jenis_update									 :v_jenis_update
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //simpan berhasil, reload form -------------
            alert(jdata.msg);
  					reloadFormUtama();														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do save update data penerima beasiswa ----------------------------------	

  //do hapus data penerima beasiswa --------------------------------------------
  function fjq_ajax_val_delete_penerima_beasiswa()
  {				 
  	var v_nik_penerima = $('#nik_penerima').val();
    
    if (v_nik_penerima == '')
  	{
    	alert('NIK anak kosong, harap perhatikan data input..!!!');											 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_delete_penerima_beasiswa',
              v_nik_penerima:v_nik_penerima
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //simpan berhasil, reload form -------------
            alert(jdata.msg);
  					reloadPage();														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do hapus data penerima beasiswa ----------------------------------------
	
  //do submit data penerima beasiswa --------------------------------------------
  function fjq_ajax_val_submit_penerima_beasiswa()
  {				 
  	var v_nik_penerima = $('#nik_penerima').val();
    
    if (v_nik_penerima == '')
  	{
    	alert('NIK anak kosong, harap perhatikan data input..!!!');											 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_submit_penerima_beasiswa',
              v_nik_penerima:v_nik_penerima
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //simpan berhasil, reload form -------------
            alert(jdata.msg);
  					reloadPage();														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do submit data penerima beasiswa ---------------------------------------

  //do submit data penundaan beasiswa ------------------------------------------
  function fjq_ajax_val_submit_penundaan_beasiswa()
  {				 
  	var v_nik_penerima = $('#nik_penerima').val();
    
    if (v_nik_penerima == '')
  	{
    	alert('NIK anak kosong, harap perhatikan data input..!!!');											 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_submit_penundaan_beasiswa',
              v_nik_penerima:v_nik_penerima
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //simpan berhasil, reload form -------------
            alert(jdata.msg);
  					reloadPage();														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do submit data penundaan beasiswa --------------------------------------
		
  //do batalkan data penerima beasiswa -----------------------------------------
  function fjq_ajax_val_batal_penerima_beasiswa()
  {				 
  	var v_nik_penerima = $('#nik_penerima').val();
    
    if (v_nik_penerima == '')
  	{
    	alert('NIK anak kosong, harap perhatikan data input..!!!');											 				 
    }else
    {
      preload(true);
      $.ajax(
      {
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_action.php?'+Math.random(),
        data: { tipe:'fjq_ajax_val_batal_penerima_beasiswa',
              v_nik_penerima:v_nik_penerima
  			},
        success: function(data)
        {
          preload(false);
          jdata = JSON.parse(data);
          if(jdata.ret=="0")
          {   
            //simpan berhasil, reload form -------------
            alert(jdata.msg);
  					reloadPage();														
          }else{
            //simpan gagal -----------------------------
            alert(jdata.msg);
          }
        }
      });//end ajax
    }//end if
  }
  //end do batalkan data penerima beasiswa -------------------------------------
	
	//do ganti anak penerima manfaat beasiswa ------------------------------------
	function fl_js_doGantiPenerima()
	{
	 	var v_nik_penerima 	= $('#nik_penerima').val();
		var v_nama_penerima = $('#nama_penerima').val();
		var v_nik_tk 		 		= $('#nik_tk').val();
		var v_nama_tk 			= $('#nama_tk').val();
		var v_tgl_rekam		  = $('#tgl_rekam').val();
		var v_mid 					= '<?=$mid;?>';
		
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_gantipenerima.php?p=pn5073.php&a=formreg&nik_penerima='+v_nik_penerima+'&nama_penerima='+v_nama_penerima+'&nik_tk='+v_nik_tk+'&nama_tk='+v_nama_tk+'&tgl_rekam='+v_tgl_rekam+'&mid='+v_mid+'','',800,610,'yes');			 
	}
	//end do ganti anak penerima manfaat beasiswa --------------------------------	
	
	//post submit ganti anak penerima manfaat beasiswa ---------------------------
	function fl_js_doPostGantiPenerimaSetTaskNew(v_nik_tk, v_nama_tk, v_nik_pengganti, v_nik_penerima_induk, v_ket_penggantian)
	{
		document.formreg.task.value = 'new';
		document.formreg.task_detil.value = 'doPostGantiPenerima';
		document.formreg.nik_tk_temp.value = v_nik_tk;
		document.formreg.nama_tk_temp.value = v_nama_tk;
		document.formreg.nik_pengganti_temp.value = v_nik_pengganti;
		document.formreg.nik_penerima_induk_temp.value = v_nik_penerima_induk;
		document.formreg.ket_penggantian_temp.value = v_ket_penggantian;
		
		document.formreg.editid.value = '';
		document.formreg.nik_penerima_temp.value = '';
			
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit(); 
	}
	//end post submit ganti anak penerima manfaat beasiswa -----------------------	
		
	function fl_js_doPostGantiPenerima(v_nik_tk, v_nama_tk, v_nik_pengganti, v_nik_penerima_induk, v_ket_penggantian)
	{
		$('#nik_tk').val(v_nik_tk);
		$('#nama_tk').val(v_nama_tk);
		
    $('#task_detil').val('');
    $('#nik_tk_temp').val('');
    $('#nama_tk_temp').val('');
    $('#nik_pengganti_temp').val('');
		$('#nik_penerima_induk_temp').val('');
		$('#ket_penggantian_temp').val('');
		
		fl_js_reload_post_lovniktk();
		
		$('#nik_penerima_induk').val(v_nik_penerima_induk);
		$('#ket_penggantian').val(v_ket_penggantian);
		
		$('#nik_penerima').val(v_nik_pengganti);
		fjq_ajax_val_nikbyadminduk();
		
		document.getElementById('nik_penerima').readOnly = true;
    document.getElementById('nik_penerima').style.backgroundColor='#f5f5f5';
		window.document.getElementById("btn_lov_new_niktk").style.display = 'none';
		$('#jenis_insert').val('PENGGANTIAN');
			
		alert('SUBMIT DATA PENGGANTIAN ANAK KE NIK '+v_nik_pengganti+' BERHASIL <br/>HARAP MELENGKAPI PROFIL UNTUK ANAK TERSEBUT..'); 
	}	
	//end post submit ganti anak penerima manfaat beasiswa -----------------------
	
	function fl_js_doCetakKartu()		
	{
    document.formreg.task_detil.value = 'doCetakKartu';
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();		
	}
	
	function fjq_ajax_val_koreksi_penerima_beasiswa ()
	{
		//koreksi profile (diluar no hp dan email) ---------------------------------
		$('#jenis_update').val('PROFILE');
		document.getElementById('notifikasi_via_email').disabled = true; 
    document.getElementById('notifikasi_via_sms').disabled = true;
    document.getElementById('email').readOnly = true;
    document.getElementById('email').style.backgroundColor='#f5f5f5';
    document.getElementById('handphone').readOnly = true;
    document.getElementById('handphone').style.backgroundColor='#f5f5f5';								
					
		document.getElementById('alamat').readOnly = false;
		document.getElementById('alamat').style.backgroundColor='#ffff99';
				
	 	document.getElementById('nama_ortu_wali').readOnly = false;
		document.getElementById('nama_ortu_wali').style.backgroundColor='#ffff99';

	 	document.getElementById('keterangan').readOnly = false;
		document.getElementById('keterangan').style.backgroundColor='#ffffff';
		
		document.getElementById('nama_bank_penerima').readOnly = true;
		document.getElementById('nama_bank_penerima').style.backgroundColor='#ffff99';
		window.document.getElementById("btn_lov_bank_penerima").style.display = '';

	 	document.getElementById('no_rekening_penerima').readOnly = false;
		document.getElementById('no_rekening_penerima').style.backgroundColor='#ffff99';			
		
		window.document.getElementById("btn_doSaveUpdate").style.display = 'block';
		window.document.getElementById("btn_doCancelKoreksiData").style.display = 'block';
		window.document.getElementById("btn_doKoreksiData").style.display = 'none';
		window.document.getElementById("btn_GantiPenerima").style.display = 'none';
		window.document.getElementById("btn_CetakKartu").style.display = 'none';
		window.document.getElementById("btn_doBatal").style.display = 'none';
		window.document.getElementById("btn_doBack2Grid").style.display = 'none';									 
	}
	
	function fjq_ajax_val_cancel_koreksi_penerima_beasiswa()
	{
	 	reloadFormUtama();			 
	}
	
	function fjq_ajax_val_ubahtunda_penerima_beasiswa()
	{
	 	$('#jenis_update').val('STATUS_SEKOLAH');
		document.getElementById('alamat').readOnly = false;
		document.getElementById('alamat').style.backgroundColor='#ffff99';

	 	document.getElementById('email').readOnly = false;
		document.getElementById('email').style.backgroundColor='#ffff99';

	 	document.getElementById('handphone').readOnly = false;
		document.getElementById('handphone').style.backgroundColor='#ffff99';

	 	document.getElementById('nama_ortu_wali').readOnly = false;
		document.getElementById('nama_ortu_wali').style.backgroundColor='#ffff99';

	 	document.getElementById('keterangan').readOnly = false;
		document.getElementById('keterangan').style.backgroundColor='#ffff99';
		
		document.getElementById('rg_status_blm_sekolah_y').disabled = false; 
		document.getElementById('rg_status_blm_sekolah_t').disabled = false; 
		
		document.getElementById('nama_bank_penerima').readOnly = true;
		document.getElementById('nama_bank_penerima').style.backgroundColor='#ffff99';
		window.document.getElementById("btn_lov_bank_penerima").style.display = '';

	 	document.getElementById('no_rekening_penerima').readOnly = false;
		document.getElementById('no_rekening_penerima').style.backgroundColor='#ffff99';			
		
		document.getElementById('notifikasi_via_email').disabled = false; 
		document.getElementById('notifikasi_via_sms').disabled = false;
		
		window.document.getElementById("btn_doSaveUpdate").style.display = 'block';
		window.document.getElementById("btn_doCancelKoreksiData").style.display = 'block';
		window.document.getElementById("btn_doKoreksiData").style.display = 'none';
		window.document.getElementById("btn_GantiPenerima").style.display = 'none';
		window.document.getElementById("btn_CetakKartu").style.display = 'none';
		window.document.getElementById("btn_doBatal").style.display = 'none';
		window.document.getElementById("btn_doBack2Grid").style.display = 'none';
		
		window.document.getElementById("btn_doUbahStatusTunda").style.display = 'none';
		
		$('#status_penerima').val('AKTIF');
		fl_js_setCheckedValueRadioButton('rg_status_blm_sekolah', 'T');
		fl_js_set_fieldset_stsekolah(); 	
	}				
	// ---------------------------- END BUTTON TASK ------------------------------						
</script>

<!-- add select2 css & javascript -->
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>
<script language="javascript">
	$(document).ready(function(){
		$('select').select2();
	});
</script>
<!-- end add select2 css & javascript -->

