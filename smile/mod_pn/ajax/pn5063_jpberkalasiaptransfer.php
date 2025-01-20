<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/* =============================================================================
Ket : Form ini digunakan untuk verifikasi data jp berkala siap bayar
Hist: - 01/09/2020 : Pembuatan Form (Tim SMILE)							 						 
------------------------------------------------------------------------------*/
$task = $_POST["TASK"] =="null" ? "" : $_POST["TASK"];

if ($task == "") 
{
  //datagrid -------------------------------------------------------------------
  ?>
  <div id="div_header" class="div-header">
    <div class="div-header-content">
    </div>
  </div>

  <div id="div_body" class="div-body">
		<div id="div_dummy_data" style="width: 100%;"></div>
		<div id="div_filter">
      <div class="div-row" style="padding-top: 1px;">
        <div class="div-col" style="padding: 2px;">
          <span style="vertical-align: middle;" >Tampilkan</span>
          <select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="fl_js_jpbklsiaptransfer_filter();">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <span style="vertical-align: middle;" >item per halaman</span>							
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <a class="a-icon-text" href="#" onclick="fl_js_jpbklsiaptransfer_filter();" title="Klik Untuk Menampilkan Data">
            <img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
            <span>Tampilkan</span>
          </a>
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="fl_js_jpbklsiaptransfer_search_by2_changed();">
            <option value="">Keyword Lain</option>
            <option value="">----------------</option>
          </select>
        </div>				
        <div class="div-col-right" style="padding: 2px;">
        	<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="fl_js_jpbklsiaptransfer_search_by_changed()">
            <option value="">-- Keyword --</option>
            <option value="KODE_KLAIM">Kode Klaim</option>
            <option value="KPJ">No. Ref</option> 
            <option value="NAMA_PENGAMBIL_KLAIM">Nama</option> 
          </select>
        </div>
      </div>			
		</div>
		
		<div id="div_data" class="div-data">
      <div style="padding: 6px 0px 0px 0px;">
        <table id="DtGridVerifJPBkl" class="table-data">
          <thead>				
            <tr class="hr-single-double">
              <th style="text-align: center; width: 20px;!important;">
              	<input type="checkbox" name="toggle" value="" onclick="fl_js_jpbklsiaptransfer_checkRecordAll(this);">
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Kode Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="BLTH_PROSES" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Bulan
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Ktr
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR_KONFIRMASI" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Ktr Konf
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>											
              <th style="text-align: left;">
                <a href="#" order_by="KPJ" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">No. Referensi
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_TK" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Nama Peserta
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_PENERIMA_BERKALA" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Nama Penerima
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NO_REKENING_PENERIMA" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Rekening
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="BANK_PENERIMA" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Bank
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="STATUS_VALID_REKENING_PENERIMA" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Rek. Valid
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: right;">
                <a href="#" order_by="NOM_MANFAAT_NETTO" order_type="DESC" onclick="fl_js_jpbklsiaptransfer_orderby(this)">Jml Bayar
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>																					
            </tr>
          </thead>
          <tbody id="data_list_jpberkala">
            <tr class="nohover-color">
            	<td colspan="12" style="text-align: center;">-- Data tidak ditemukan --</td>
            </tr>
          </tbody>
        </table>   
      </div>
		</div>
		
		<div id="div_page" class="div-page">
      <div class="div-row" style="padding-top: 8px;">
        <div class="div-col">
          <span style="vertical-align: middle;">Halaman</span>
          <a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="fl_js_jpbklsiaptransfer_filter('-02')"><<</a>
          <a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="fl_js_jpbklsiaptransfer_filter('-01')">Prev</a>
          <input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="fl_js_jpbklsiaptransfer_filter(this.value);"/>
          <a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="fl_js_jpbklsiaptransfer_filter('01')">Next</a>
          <a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="fl_js_jpbklsiaptransfer_filter('02')">>></a>
          <span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
          <input type="hidden" id="pages">
        </div>
        <div class="div-col-right">
          <input type="hidden" id="hdn_total_records_jpbklsiaptransfer" name="hdn_total_records_jpbklsiaptransfer">
          <span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>								
        </div>
      </div>		
		</div>	 
  </div>
  
	<div id="div_footer" class="div-footer">	
		<div class="div-footer-form">
			<div class="div-row">
				<div class="div-col">
					<div class="div-action-footer">
						<div class="icon">
							<a id="btn_doApprovalSiapByr" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan Pembayaran Manfaat JP Berkala..?')) fjq_ajax_val_jpbklsiaptransfer_doKlikSiapTransfer();">
								<img src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
								<span>BAYAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</a>
						</div>
					</div>
				</div>	
				<div class="div-col">
					<div class="div-action-footer">
						<div class="icon">
							<a id="btn_doKembalikanData" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan PENOLAKAN data..?')) fjq_ajax_val_jpbklsiaptransfer_doTolakSiapTransfer();">
								<img src="../../images/ico_penolakan.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
								<span>KEMBALIKAN DATA JP BERKALA KE TAHAP SEBELUMNYA &nbsp;</span>
							</a>
						</div>
					</div>
				</div>																 
			</div>	 
		</div>	
		</br>					 
		<div class="div-footer-content">
			<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
			Tickmark kemudian klik <font color="#ff0000"> BAYAR</font> untuk pembayaran manfaat JP Berkala. Apabila masih ada koreksi terhadap data maka klik <font color="#ff0000"> KEMBALIKAN DATA JP BERKALA KE TAHAP SEBELUMNYA</font>.
		</div>
	</div>
	<?
}else if ($task == "edit" || $task == "view")
{
}
?>

<script language="javascript">
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

    $(window).bind("resize", function(){
			fl_js_jpbklsiaptransfer_resize_grid();
		});
		
		fl_js_jpbklsiaptransfer_resize_grid();

		/** list checkbox */
		window.list_checkbox_record = [];
		
		let task = $('#task').val();
		if (task=="new" || task=="edit" || task=="view")
		{
		}else
		{
			fl_js_jpbklsiaptransfer_filter();	 
		}		
	});
	
	function fl_js_jpbklsiaptransfer_resize_grid(){		 
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

	function fl_js_jpbklsiaptransfer_search_by_changed(){
		$("#search_txt").val("");
	}
		
	function fl_js_jpbklsiaptransfer_search_by2_changed(){
		$('#'+$('#search_by2').val()).show();
	}
	
	function fl_js_jpbklsiaptransfer_orderby(e) {
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

		fl_js_jpbklsiaptransfer_filter();
	}
	
	function fl_js_jpbklsiaptransfer_filter(val = 0){
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
		html_data += '<td colspan="12" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list_jpberkala").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe 				 		 : 'MainDataGridJPBerkalaSiapTransfer',
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
        order_type	 		 : order_type
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 1) {
						var html_data = "";
						// load data
						for(var i = 0; i < jdata.data.length; i++){
							let kode_klaim = getValue(jdata.data[i].KODE_KLAIM);
							let no_konfirmasi = getValue(jdata.data[i].NO_KONFIRMASI);
							let no_proses = getValue(jdata.data[i].NO_PROSES);
							let kd_prg = getValue(jdata.data[i].KD_PRG);
							
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi && element.NO_PROSES == no_proses && element.KD_PRG == kd_prg) {
									return element;
								}
							});
							
							var v_status_valid_rekening_penerima = 'T';
							v_status_valid_rekening_penerima = getValue(jdata.data[i].STATUS_VALID_REKENING_PENERIMA) === "Y" ? "<img src=../../images/file_apply.gif> Ya" : "<img src=../../images/file_cancel.gif> Tidak"; 
							
							html_data += '<tr>';
							html_data += '<td style="text-align: center;">' 
									+ '<input type="checkbox" id="jpbklsiaptransfer_cboxRecord' + i +'" name="jpbklsiaptransfer_cboxRecord' + i +'" elname="jpbklsiaptransfer_cboxRecord" '
									+ 'onchange="fl_js_jpbklsiaptransfer_checkRecord(this)" '
									+ (checkedBox ? 'checked' : '') + ' '
									+ 'kode_klaim="' + getValue(jdata.data[i].KODE_KLAIM) + '" no_konfirmasi="' + getValue(jdata.data[i].NO_KONFIRMASI) + '"  no_proses="' + getValue(jdata.data[i].NO_PROSES) + '" kd_prg="' + getValue(jdata.data[i].KD_PRG) + '">' + '';
							html_data += '<input type="hidden" id="jpbklsiaptransfer_kode_klaim'+i+'" name="jpbklsiaptransfer_kode_klaim'+i+'" value='+getValue(jdata.data[i].KODE_KLAIM)+'>';
							html_data += '<input type="hidden" id="jpbklsiaptransfer_no_konfirmasi'+i+'" name="jpbklsiaptransfer_no_konfirmasi'+i+'" value='+getValue(jdata.data[i].NO_KONFIRMASI)+'>';
							html_data += '<input type="hidden" id="jpbklsiaptransfer_no_proses'+i+'" name="jpbklsiaptransfer_no_proses'+i+'" value='+getValue(jdata.data[i].NO_PROSES)+'>';
							html_data += '<input type="hidden" id="jpbklsiaptransfer_kd_prg'+i+'" name="jpbklsiaptransfer_kd_prg'+i+'" value='+getValue(jdata.data[i].KD_PRG)+'></td>';
														
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KET_BLTH_PROSES) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR_KONFIRMASI) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_PENERIMA_BERKALA) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NO_REKENING_PENERIMA) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].BANK_PENERIMA) + '</td>';
							html_data += '<td style="text-align: left;">' + v_status_valid_rekening_penerima + '</td>';
							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data[i].NOM_MANFAAT_NETTO)) + '</td>';
							html_data += '</tr>';
						}
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="12" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_list_jpberkala").html(html_data);
						
						// load info halaman
						$("#pages").val(jdata.pages);
						$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

						// load info item
						$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
						$("#hdn_total_records_jpbklsiaptransfer").val(jdata.recordsTotal);

						fl_js_jpbklsiaptransfer_resize_grid();
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
	
	function fl_js_jpbklsiaptransfer_reloadFormUtama()
  {
		document.formreg.task_detil.value = '';
		
		try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
		
	function fl_js_jpbklsiaptransfer_reloadFormEntry(task, editid)
  {
		document.formreg.task.value = task;
		document.formreg.editid.value = editid;
		document.formreg.kode_klaim.value = editid;
		fl_js_jpbklsiaptransfer_reloadFormUtama();	
  }
	
	function fl_js_jpbklsiaptransfer_checkRecordAll(el) {
		let checked = $(el).attr('checked');
		$("input[elname='jpbklsiaptransfer_cboxRecord']").each(function() {
			let kode_klaim = $(this).attr('kode_klaim');
			let no_konfirmasi = $(this).attr('no_konfirmasi');
			let no_proses = $(this).attr('no_proses');
			let kd_prg = $(this).attr('kd_prg');
			
			$(this).attr("checked", checked);
			if (checked == true) {
				var found = window.list_checkbox_record.find(function(element) {
					if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi && element.NO_PROSES == no_proses && element.KD_PRG == kd_prg) {
						return element;
					}
				});
				if (found == undefined) {
					window.list_checkbox_record.push({ KODE_KLAIM: kode_klaim, NO_KONFIRMASI: no_konfirmasi, NO_PROSES: no_proses, KD_PRG: kd_prg});
				}
			} else {
				window.list_checkbox_record.forEach(function(element, i) {
					if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi && element.NO_PROSES == no_proses && element.KD_PRG == kd_prg) {
						window.list_checkbox_record.splice(i, 1);
					}
				});
			}
		});
	}

	function fl_js_jpbklsiaptransfer_checkRecord(el) {
		let kode_klaim = $(this).attr('kode_klaim');
    let no_konfirmasi = $(this).attr('no_konfirmasi');
    let no_proses = $(this).attr('no_proses');
    let kd_prg = $(this).attr('kd_prg');
		
		if ($(el).attr("checked") == true) {
			var found = window.list_checkbox_record.find(function(element) {
				if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi && element.NO_PROSES == no_proses && element.KD_PRG == kd_prg) {
					return element;
				}
			});
			if (found == undefined) {
				window.list_checkbox_record.push({ KODE_KLAIM: kode_klaim, NO_KONFIRMASI: no_konfirmasi, NO_PROSES: no_proses, KD_PRG: kd_prg});
			}
		} else {
			window.list_checkbox_record.forEach(function(element, i) {
				if (element.KODE_KLAIM == kode_klaim && element.NO_KONFIRMASI == no_konfirmasi && element.NO_PROSES == no_proses && element.KD_PRG == kd_prg) {
					window.list_checkbox_record.splice(i, 1);
				}
			});
		}
	}
	
	<!------------------------------- BUTTON TASK ------------------------------->
  //do klik data siap transfer jp berkala --------------------------------------
	function fjq_ajax_val_jpbklsiaptransfer_doKlikSiapTransfer()
  {				 
		$('#tipe').val('DoKlikSiapTransferJPBerkala');	
    preload(true);
    $.ajax(
    {
      type: 'POST',
      url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
      data: $('#formreg').serialize(),
      success: function(data)
      {
        preload(false);
        jdata = JSON.parse(data);									
        if(jdata.ret == '0')
        {						 		 						 						 
          window.parent.Ext.notify.msg('Sukses', jdata.msg);
          alert(jdata.msg);
					fl_js_jpbklsiaptransfer_filter();
        }else 
        {
         	alert(jdata.msg);
        }
      }
    });//end ajax
  }
  //end do klik data siap transfer jp berkala  ---------------------------------	
	
 //do tolak data siap transfer jp berkala --------------------------------------
	function fjq_ajax_val_jpbklsiaptransfer_doTolakSiapTransfer()
  {				 
		$('#tipe').val('DoTolakSiapTransferJPBerkala');	
    preload(true);
    $.ajax(
    {
      type: 'POST',
      url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
      data: $('#formreg').serialize(),
      success: function(data)
      {
        preload(false);
        jdata = JSON.parse(data);									
        if(jdata.ret == '0')
        {						 		 						 						 
          window.parent.Ext.notify.msg('Sukses', jdata.msg);
          alert(jdata.msg);
					fl_js_jpbklsiaptransfer_filter();
        }else 
        {
         	alert(jdata.msg);
        }
      }
    });//end ajax
  }
  //end do tolak data siap transfer jp berkala ---------------------------------		
	<!----------------------------- END BUTTON TASK ----------------------------->					
</script>
