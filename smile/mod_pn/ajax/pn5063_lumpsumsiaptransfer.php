<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/* =============================================================================
Ket : Form ini digunakan untuk klik siap transfer data lumpsum
Hist: - 01/09/2020 : Pembuatan Form (Tim SMILE)							 						 
------------------------------------------------------------------------------*/
$task 				 = $_POST["TASK"] 			=="null" ? "" : $_POST["TASK"];

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
      <div class="div-row" style="padding-top: 2px;">
        <div class="div-col" style="padding: 2px;">			
          <span style="vertical-align: middle;" >Tampilkan</span>
          <select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="fl_js_lumpsiaptransfer_filter();">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <span style="vertical-align: middle;" >item per halaman</span>          						
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <a class="a-icon-text" href="#" onclick="fl_js_lumpsiaptransfer_filter();" title="Klik Untuk Menampilkan Data">
            <img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
            <span>Tampilkan</span>
          </a>
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="fl_js_lumpsiaptransfer_search_by2_changed();">
            <option value="">Keyword Lain</option>
            <option value="">----------------</option>
            <option value="KODE_TIPE_KLAIM">Tipe Klaim</option> 
            <option value="KODE_SEBAB_KLAIM">Sebab Klaim</option>
            <option value="KODE_SEGMEN">Segmen Kepesertaan</option> 
          </select>
          
          <span id="KODE_TIPE_KLAIM" hidden="">
            <select size="1" id="search_txt2a" name="search_txt2a" value="" style="border:0;width: 110px;height:18px;">
            <option value="">-- Pilih --</option>
            </select>							
          </span>	            
          <span id="KODE_SEBAB_KLAIM" hidden="">
            <select size="1" id="search_txt2b" name="search_txt2b" value="" style="border:0;width: 110px;height:18px;">
              <option value="">-- Pilih --</option>
            </select>							
          </span>
          <span id="KODE_SEGMEN" hidden="">
            <select size="1" id="search_txt2c" name="search_txt2c" value="" style="border:0;width: 110px;height:18px;">
              <option value="">-- Pilih --</option>
            </select>							
          </span>	
        </div>
        <div class="div-col-right" style="padding: 2px;">
        	<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="fl_js_lumpsiaptransfer_search_by_changed()">
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
        <table class="table-data">
          <thead>
            <tr class="hr-single-double">
              <th style="text-align: center; width: 20px;!important;">
              	<input type="checkbox" name="toggle" value="" onclick="fl_js_lumpsiaptransfer_checkRecordAll(this);">
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="orderby(this)">Kode Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_SEGMEN" order_type="DESC" onclick="orderby(this)">Segmen
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KET_TIPE_KLAIM" order_type="DESC" onclick="orderby(this)">Tipe
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>											
              <th style="text-align: left;">
                <a href="#" order_by="TGL_KLAIM" order_type="DESC" onclick="orderby(this)">Tgl Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="TGL_PENETAPAN" order_type="DESC" onclick="orderby(this)">Tgl Penetapan
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>											
              <th style="text-align: left;">
                <a href="#" order_by="KPJ" order_type="DESC" onclick="orderby(this)">No Ref
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_PENGAMBIL_KLAIM" order_type="DESC" onclick="orderby(this)">Nama
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_TIPE_PENERIMA" order_type="DESC" onclick="orderby(this)">Tipe Penerima
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_PENERIMA" order_type="DESC" onclick="orderby(this)">Penerima
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NOM_SISA" order_type="DESC" onclick="orderby(this)">Jml Bayar
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NM_PRG" order_type="DESC" onclick="orderby(this)">Prg
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="orderby(this)">Ktr
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
            </tr>
          </thead>
          <tbody id="data_list_lumpsiaptransfer">
            <tr class="nohover-color">
            	<td colspan="10" style="text-align: center;">-- Data tidak ditemukan --</td>
            </tr>
          </tbody>
        </table>   
      </div>
		</div>
		
		<div id="div_page" class="div-page">
      <div class="div-row" style="padding-top: 8px;">
        <div class="div-col">
          <span style="vertical-align: middle;">Halaman</span>
          <a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="fl_js_lumpsiaptransfer_filter('-02')"><<</a>
          <a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="fl_js_lumpsiaptransfer_filter('-01')">Prev</a>
          <input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="fl_js_lumpsiaptransfer_filter(this.value);"/>
          <a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="fl_js_lumpsiaptransfer_filter('01')">Next</a>
          <a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="fl_js_lumpsiaptransfer_filter('02')">>></a>
          <span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
          <input type="hidden" id="pages">
        </div>
        <div class="div-col-right">
          <input type="hidden" id="hdn_total_records_lumpsiaptransfer" name="hdn_total_records_lumpsiaptransfer">
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
							<a id="btn_doApprovalSiapByr" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan Pembayaran..?')) fjq_ajax_val_lumpsiaptransfer_doKlikSiapTransfer();">
								<img src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
								<span>BAYAR&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
							</a>
						</div>
					</div>
				</div>	
				<div class="div-col">
					<div class="div-action-footer">
						<div class="icon">
							<a id="btn_doKembalikanData" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan PENOLAKAN data..?')) fjq_ajax_val_lumpsiaptransfer_doTolakSiapTransfer();">
								<img src="../../images/ico_penolakan.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
								<span>KEMBALIKAN DATA KE TAHAP SEBELUMNYA &nbsp;</span>
							</a>
						</div>
					</div>
				</div>																 
			</div>	 
		</div>	
		</br>					 
		<div class="div-footer-content">
			<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
			Tickmark kemudian klik <font color="#ff0000"> BAYAR</font> untuk pembayaran klaim. Apabila masih ada koreksi terhadap data maka klik <font color="#ff0000"> KEMBALIKAN DATA KE TAHAP SEBELUMNYA</font>.
		</div>
	</div>		
	<?
}else if ($task == "edit" || $task == "view")
{}
?>

<script language="javascript">
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

    $(window).bind("resize", function(){
			fl_js_lumpsiaptransfer_resize_grid();
		});
		
		fl_js_lumpsiaptransfer_resize_grid();

		/** list checkbox */
		window.list_checkbox_record = [];
		
		let task = $('#task').val();
		if (task=="new" || task=="edit" || task=="view")
		{
		}else
		{
			fl_js_lumpsiaptransfer_get_searchlist();
			fl_js_lumpsiaptransfer_filter();	 
		}		
	});
	
	function fl_js_lumpsiaptransfer_resize_grid(){		 
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
	
	function fl_js_lumpsiaptransfer_search_by_changed(){
		$("#search_txt").val("");
	}
		
	function fl_js_lumpsiaptransfer_search_by2_changed(){
    $('#KODE_TIPE_KLAIM').hide();
    $('#KODE_SEBAB_KLAIM').hide(); 
    $('#KODE_SEGMEN').hide();
    $('#STATUS_KLAIM').hide();	
		$('#search_txt2a').val('');
		$('#search_txt2b').val('');
		$('#search_txt2c').val('');
		$('#search_txt2d').val('');
		$('#'+$('#search_by2').val()).show();
	}

	function fl_js_lumpsiaptransfer_get_searchlist()
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
					//set list tipe klaim -----------------------------------------------
          if (jdata.dtListTipeKlaim.DATA)
          {
						for($i=0;$i<(jdata.dtListTipeKlaim.DATA.length);$i++)
            {
						 	//tampilkan semua pilihan ----------------------------
							$("#search_txt2a").append('<option value="'+jdata.dtListTipeKlaim.DATA[$i]['KODE_TIPE_KLAIM']+'">'+jdata.dtListTipeKlaim.DATA[$i]['NAMA_TIPE_KLAIM']+'</option>');
            }	
          }					   
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
	
	function fl_js_lumpsiaptransfer_orderby(e) {
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

		fl_js_lumpsiaptransfer_filter();
	}
	
	function fl_js_lumpsiaptransfer_filter(val = 0){
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
		html_data += '<td colspan="13" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list_lumpsiaptransfer").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe 				 		 : 'MainDataGridLumpsumSiapTransfer',
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
							let kode_tipe_penerima = getValue(jdata.data[i].KODE_TIPE_PENERIMA);
							let kd_prg = getValue(jdata.data[i].KD_PRG);
							
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.KODE_KLAIM == kode_klaim && element.KODE_TIPE_PENERIMA == kode_tipe_penerima && element.KD_PRG == kd_prg) {
									return element;
								}
							});
							
							html_data += '<tr>';
							html_data += '<td style="text-align: center;">' 
									+ '<input type="checkbox" id="lumpsiaptransfer_cboxRecord' + i +'" name="lumpsiaptransfer_cboxRecord' + i +'" elname="lumpsiaptransfer_cboxRecord" '
									+ 'onchange="fl_js_lumpsiaptransfer_checkRecord(this)" '
									+ (checkedBox ? 'checked' : '') + ' '
									+ 'kode_klaim="' + getValue(jdata.data[i].KODE_KLAIM) + '" kode_tipe_penerima="' + getValue(jdata.data[i].KODE_TIPE_PENERIMA) + '" kd_prg="' + getValue(jdata.data[i].KD_PRG) + '">' + '';
							html_data += '<input type="hidden" id="lumpsiaptransfer_kode_klaim'+i+'" name="lumpsiaptransfer_kode_klaim'+i+'" value='+getValue(jdata.data[i].KODE_KLAIM)+'>';
							html_data += '<input type="hidden" id="lumpsiaptransfer_kode_tipe_penerima'+i+'" name="lumpsiaptransfer_kode_tipe_penerima'+i+'" value='+getValue(jdata.data[i].KODE_TIPE_PENERIMA)+'>';
							html_data += '<input type="hidden" id="lumpsiaptransfer_kd_prg'+i+'" name="lumpsiaptransfer_kd_prg'+i+'" value='+getValue(jdata.data[i].KD_PRG)+'></td>';
							
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_SEGMEN) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KET_TIPE_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_PENETAPAN) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
							html_data += '<td style="text-align: left;white-space:pre-wrap; word-wrap:break-word;">' + getValue(jdata.data[i].NAMA_PENGAMBIL_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TIPE_PENERIMA) + '</td>';
							html_data += '<td style="text-align: left;white-space:pre-wrap; word-wrap:break-word;">' + getValue(jdata.data[i].NAMA_PENERIMA) + '</td>';
							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data[i].NOM_SISA)) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NM_PRG) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
							html_data += '</tr>';
						}
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="13" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_list_lumpsiaptransfer").html(html_data);
						
						// load info halaman
						$("#pages").val(jdata.pages);
						$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

						// load info item
						$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
						$("#hdn_total_records_lumpsiaptransfer").val(jdata.recordsTotal);

						fl_js_lumpsiaptransfer_resize_grid();
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
	
	function fl_js_lumpsiaptransfer_reloadFormUtama()
  {
		document.formreg.task_detil.value = '';
		
		try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
	
	function fl_js_lumpsiaptransfer_checkRecordAll(el) {			 
		let checked = $(el).attr('checked');
		$("input[elname='lumpsiaptransfer_cboxRecord']").each(function() {
			let kode_klaim = $(this).attr('lumpsiaptransfer_kode_klaim');
			let kode_tipe_penerima = $(this).attr('lumpsiaptransfer_kode_tipe_penerima');
			let kd_prg = $(this).attr('lumpsiaptransfer_kd_prg');
			
			$(this).attr("checked", checked);
			if (checked == true) {
				var found = window.list_checkbox_record.find(function(element) {
					if (element.KODE_KLAIM == kode_klaim && element.KODE_TIPE_PENERIMA == kode_tipe_penerima && element.KD_PRG == kd_prg) {
						return element;
					}
				});
				if (found == undefined) {
					window.list_checkbox_record.push({ KODE_KLAIM: kode_klaim, KODE_TIPE_PENERIMA: kode_tipe_penerima, KD_PRG: kd_prg});
				}
			} else {
				window.list_checkbox_record.forEach(function(element, i) {
					if (element.KODE_KLAIM == kode_klaim && element.KODE_TIPE_PENERIMA == kode_tipe_penerima && element.KD_PRG == kd_prg) {
						window.list_checkbox_record.splice(i, 1);
					}
				});
			}
		});
	}

	function fl_js_lumpsiaptransfer_checkRecord(el) {
		let kode_klaim = $(el).attr('lumpsiaptransfer_kode_klaim');
		let kode_tipe_penerima = $(el).attr('lumpsiaptransfer_kode_tipe_penerima');
		let kd_prg = $(el).attr('lumpsiaptransfer_kd_prg');
		
		if ($(el).attr("checked") == true) {
			var found = window.list_checkbox_record.find(function(element) {
				if (element.KODE_KLAIM == kode_klaim && element.KODE_TIPE_PENERIMA == kode_tipe_penerima && element.KD_PRG == kd_prg) {
					return element;
				}
			});
			if (found == undefined) {
				window.list_checkbox_record.push({ KODE_KLAIM: kode_klaim, KODE_TIPE_PENERIMA: kode_tipe_penerima, KD_PRG: kd_prg});
			}
		} else {
			window.list_checkbox_record.forEach(function(element, i) {
				if (element.KODE_KLAIM == kode_klaim && element.KODE_TIPE_PENERIMA == kode_tipe_penerima && element.KD_PRG == kd_prg) {
					window.list_checkbox_record.splice(i, 1);
				}
			});
		}
	}
	
	<!------------------------------- BUTTON TASK ------------------------------->
  //do approval data siap bayar ------------------------------------------------
	function fjq_ajax_val_lumpsiaptransfer_doKlikSiapTransfer()
  {				 
		$('#tipe').val('DoKlikSiapTransferLumpsum');	
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
					fl_js_lumpsiaptransfer_filter();
        }else 
        {
         	alert(jdata.msg);
        }
      }
    });//end ajax
  }
  //end do approval data siap bayar --------------------------------------------	
	
 //do tolak data siap bayar ----------------------------------------------------
	function fjq_ajax_val_lumpsiaptransfer_doTolakSiapTransfer()
  {				 
		$('#tipe').val('DoTolakSiapTransferLumpsum');	
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
					fl_js_lumpsiaptransfer_filter();
        }else 
        {
         	alert(jdata.msg);
        }
      }
    });//end ajax
  }
  //end do tolak data siap bayar -----------------------------------------------		
	<!----------------------------- END BUTTON TASK ----------------------------->					
</script>
	