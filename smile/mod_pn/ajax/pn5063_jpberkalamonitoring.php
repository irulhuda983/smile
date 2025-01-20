<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/* =============================================================================
Ket : Form ini digunakan untuk monitoring pembayaran klaim lumpsum
Hist: - 01/09/2020 : Pembuatan Form (Tim SMILE)							 						 
------------------------------------------------------------------------------*/
$ls_kode_klaim = $_POST["KODE_KLAIM"] =="null" ? "" : $_POST["KODE_KLAIM"];
$task 				 = $_POST["TASK"] 			=="null" ? "" : $_POST["TASK"];

if ($task == "") 
{
  //datagrid -------------------------------------------------------------------
  ?>
	<div id="div_header" class="div-header" style="padding: 0px 0px 10px 0px;">
    <div class="div-header-content">	
      <input type="radio" id="rg_st_trf_a" name="rg_st_trf" value="DLM_PROSES" onclick="fl_js_jpbklmonitoring_filter();"><font color="#009999" size="1;" face="Arial,Verdana"><b>DALAM PROSES</b></font>&nbsp;&nbsp;
      <input type="radio" id="rg_st_trf_b" name="rg_st_trf" value="SUKSES_TRF" onclick="fl_js_jpbklmonitoring_filter();"><font color="#009999" size="1;" face="Arial,Verdana"><b>SUKSES TRANSFER</b></font>&nbsp;&nbsp;
      <input type="radio" id="rg_st_trf_c" name="rg_st_trf" value="ERROR" onclick="fl_js_jpbklmonitoring_filter();"><font  color="#009999" size="1;" face="Arial,Verdana"><b>ERROR</b></font>&nbsp;&nbsp;
      <input type="radio" id="rg_st_trf_d" name="rg_st_trf" value="GET_STATUS" onclick="fl_js_jpbklmonitoring_filter();"><font  color="#009999" size="1;" face="Arial,Verdana"><b>GET STATUS</b></font>&nbsp;&nbsp;
			<input type="radio" id="rg_st_trf_f" name="rg_st_trf" value="ALL" onclick="fl_js_jpbklmonitoring_filter();" checked><font  color="#009999" size="1;" face="Arial,Verdana"><b>SEMUA DATA</b></font>				 					
		</div>
  </div>
	</br>
	<div id="div_body" class="div-body">
		<div id="div_dummy_data" style="width: 100%;"></div>
		<div id="div_filter">
			<div class="div-row" style="padding-top: 2px;">
				<div class="div-col" style="padding: 2px;text-align:center;">
          Tanggal :&nbsp;
          <input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" onblur="convert_date(tglawaldisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglawaldisplay', 'dd-mm-y');">  
          &nbsp;s/d&nbsp;
          <input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" onblur="convert_date(tglakhirdisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglakhirdisplay', 'dd-mm-y');">
				</div>
        <div class="div-col-right" style="padding: 2px;">
          <a class="a-icon-text" href="#" onclick="fl_js_jpbklmonitoring_filter();" title="Klik Untuk Menampilkan Data">
            <img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
            <span>Tampilkan</span>
          </a>
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="fl_js_jpbklmonitoring_search_by2_changed();">
            <option value="">Keyword Lain</option>
            <option value="">----------------</option>
          </select>
        </div>
        <div class="div-col-right" style="padding: 2px;">
        	<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="fl_js_jpbklmonitoring_search_by_changed()">
            <option value="">-- Keyword --</option>
            <option value="KODE_KLAIM">Kode Klaim</option>
            <option value="KPJ">No. Ref</option> 
            <option value="NAMA_PENGAMBIL_KLAIM">Nama</option> 
          </select>
        </div>									 
			</div>	 
		</div>
		<!--end div-filter-->
		
		<div id="div_data" class="div-data">
      <div style="padding: 6px 0px 0px 0px;">
        <table class="table-data">
          <thead>
            <tr class="hr-single-double">
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Kode Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="BLTH_PROSES" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Bulan
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Ktr
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR_KONFIRMASI" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Ktr Konf
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>											
              <th style="text-align: left;">
                <a href="#" order_by="KPJ" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">No. Referensi
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_TK" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Nama Peserta
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_PENERIMA_BERKALA" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Nama Penerima
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NO_REKENING_PENERIMA" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Rekening
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="BANK_PENERIMA" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Bank
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: right;">
                <a href="#" order_by="NOM_MANFAAT_NETTO" order_type="DESC" onclick="fl_js_jpbklmonitoring_orderby(this)">Manfaat
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>															
              <th style="text-align: right;">
                <a href="#" order_by="NOM_SUDAH_BAYAR" order_type="DESC" onclick="orderby(this)">Sdh Dibayar
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>												
              <th style="text-align: center;">
                <a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="orderby(this)">Status
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
            </tr>
          </thead>
          <tbody id="data_list_jpbklmonitoring">
            <tr class="nohover-color">
            	<td colspan="13" style="text-align: center;">-- Data tidak ditemukan --</td>
            </tr>
          </tbody>
        </table>   
      </div>
		</div>
		
		<div id="div_page" class="div-page">
      <div class="div-row" style="padding-top: 8px;">
        <div class="div-col">
          <span style="vertical-align: middle;">Halaman</span>
          <a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="fl_js_jpbklmonitoring_filter('-02')"><<</a>
          <a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="fl_js_jpbklmonitoring_filter('-01')">Prev</a>
          <input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="fl_js_jpbklmonitoring_filter(this.value);"/>
          <a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="fl_js_jpbklmonitoring_filter('01')">Next</a>
          <a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="fl_js_jpbklmonitoring_filter('02')">>></a>
          <span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
          <input type="hidden" id="pages">
        </div>
        <div class="div-col-right">
          <input type="hidden" id="hdn_total_records_jpbklmonitoring" name="hdn_total_records_jpbklmonitoring">
          <span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
          &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
          <span style="vertical-align: middle;" >Tampilkan</span>
          <select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="fl_js_jpbklmonitoring_filter();">
            <option value="10">10</option>
            <option value="15">15</option>
            <option value="20">20</option>
            <option value="25">25</option>
            <option value="50">50</option>
          </select>
          <span style="vertical-align: middle;" >item per halaman</span>													
        </div>
      </div>		
		</div>				
	</div>
	<!----end div-body-->
	
	<div id="div_footer" class="div-footer">
		<div class="div-footer-content">
			<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
			Proses transfer akan dilakukan secara serentak setiap <font color="#ff0000"> 15 MENIT</font> sekali. Harap diperhatikan <font color="#ff0000"> STATUS</font> dibawah ini dan <font color="#ff0000"> KLIK</font> apabila ingin menampilkan informasi lebih lanjut.</font></font>
			<li style="margin-left:15px;"><font color="#ff0000">DLM PROSES </font>: data pembayaran masih menunggu untuk dilakukan proses transfer.</li>
			<li style="margin-left:15px;"><font color="#009999">SUKSES TRF </font>: proses transfer sudah berhasil dilakukan.</li>
			<li style="margin-left:15px;"><font color="#ff0000">ERROR </font>: proses transfer gagal dilakukan, ada permasalahan terhadap data pembayaran. Silahkan klik status dan cek penyebab error.</li>
			<li style="margin-left:15px;"><font color="#ff0000">GET STATUS </font>: proses transfer sudah dilakukan namun sistem belum mendapatkan informasi status berhasil/gagal transfer dari pihak bank. Silahkan klik status untuk dilakukan proses Get Payment Status</li>
		</div>	
	</div>
	<?
	//end action task edit, view -------------------------------------------------		
}
?>

<script language="javascript">
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

    $(window).bind("resize", function(){
			fl_js_jpbklmonitoring_resize_grid();
		});
		
		fl_js_jpbklmonitoring_resize_grid();

		/** list checkbox */
		window.list_checkbox_record = [];
		
		var v_tgl_awal_display  = $('#tglawaldisplay').val();
    var v_tgl_akhir_display = $('#tglakhirdisplay').val();
    
    fl_js_set_tgl_display_jpbklmonitoring(v_tgl_awal_display,v_tgl_akhir_display);
		fl_js_jpbklmonitoring_filter();			
	});

	function fl_js_set_tgl_display_jpbklmonitoring(v_tgl_awal, v_tgl_akhir)
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
		
	function fl_js_jpbklmonitoring_resize_grid(){		 
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

	function fl_js_jpbklmonitoring_search_by_changed(){
		$("#search_txt").val("");
	}
		
	function fl_js_jpbklmonitoring_search_by2_changed(){
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
	
	function fl_js_jpbklmonitoring_orderby(e) {
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

		fl_js_jpbklmonitoring_filter();
	}
	
	function fl_js_jpbklmonitoring_filter(val = 0){
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
  	var jenis_pembayaran = $("input[name='rg_jenis_pembayaran']:checked").val();
		var status_siapbayar = $("input[name='rg_status_siapbayar']:checked").val();
		var v_rg_st_trf  		 = $("input[name='rg_st_trf']:checked").val();
		var tgl_awal	   = $("#tglawaldisplay").val();
		var tgl_akhir	   = $("#tglakhirdisplay").val();
		
    //set ulang parameter temporary ------------------------------------
    $("#tmp_jenis_pembayaran").val(jenis_pembayaran);
    $("#tmp_status_siapbayar").val(status_siapbayar);
		$('#tmp_blthjt').val('B1');
    $("#tmp_tglawaldisplay").val(tgl_awal);
    $("#tmp_tglakhirdisplay").val(tgl_akhir);
		//set ulang parameter temporary ------------------------------------
							
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
		$("#data_list_jpbklmonitoring").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe 				 		 : 'MainDataGridJpBerkalaMonitoring',
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
				jenis_pembayaran : jenis_pembayaran,
				status_siapbayar : status_siapbayar,
				tgl_awal  	 		 : tgl_awal,
				tgl_akhir  	 		 : tgl_akhir
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 1) {
						var html_data = "";
						var v_is_display_data = "Y";
						var v_green_color = "#009999";
						var v_red_color = "#ff0000";
						var v_color = "";
						var v_kode_status_transfer = '';
						var v_ket_status_transfer = '';
						var v_action_status = '';
							
						// load data
						for(var i = 0; i < jdata.data.length; i++){
							let kode_klaim = getValue(jdata.data[i].KODE_KLAIM);
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.KODE_KLAIM == kode_klaim) {
									return element;
								}
							});
							
							v_kode_status_transfer = '';
							v_ket_status_transfer = '';
							v_action_status = '';
							v_color = v_green_color;
							
							//set keterangan status transfer ---
							if (getValue(jdata.data[i].STATUS_TRANSFER) == "T")
							{
							 	if (getValue(jdata.data[i].NO_REF_PAYMENT) == "")
								{
								 	v_kode_status_transfer = "DLM_PROSES";
									v_ket_status_transfer = "DLM PROSES";
									v_action_status = "ALERT_INFO_DLMPROSES";
									v_color = v_red_color;
								}else
								{
								 	v_kode_status_transfer = "ERROR";
									v_ket_status_transfer = "ERROR";
									v_action_status = "VIEW_SIAPTRANSFER";	
									v_color = v_red_color; 
								}
							}else if (getValue(jdata.data[i].STATUS_TRANSFER) == "Y")
							{
							 	if (getValue(jdata.data[i].STATUS_PAYMENT) == "Y")
								{
								 	v_kode_status_transfer = "SUKSES_TRF";
									v_ket_status_transfer = "SUKSES TRF";
									v_action_status = "VIEW_PEMBAYARAN";
									v_color = v_green_color;
								}else
								{
								 	v_kode_status_transfer = "GET_STATUS";
									v_ket_status_transfer = "GET STATUS";
									v_action_status = "VIEW_GET_STATUS";	 
									v_color = v_red_color;
								}
							}
							
							//set initial value -------------------------
							v_is_display_data = "Y";
								
							if (v_rg_st_trf == "ALL" || v_rg_st_trf == "") 
							{
							 	//tampilkan semua data -------------------- 
							 	v_is_display_data = "Y"; 
							}else
							{
							 	//tampilkan data dengan status sesuai radio button
								if (v_rg_st_trf == v_kode_status_transfer)
								{
								 	v_is_display_data = "Y";  
								}else
								{
								 	v_is_display_data = "T"; 	 
								}	 
							}
							
							if (v_is_display_data=="Y")
							{
  							var v_status_valid_rekening_penerima = 'T';
								v_status_valid_rekening_penerima = getValue(jdata.data[i].STATUS_VALID_REKENING_PENERIMA) === "Y" ? "<img src=../../images/file_apply.gif> Ya" : "<img src=../../images/file_cancel.gif> Tidak"; 
							
								html_data += '<tr>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KET_BLTH_PROSES) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR_KONFIRMASI) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_TK) + '</td>';
  							html_data += '<td style="text-align: left;white-space:normal;word-wrap:break-word;">' + getValue(jdata.data[i].NAMA_PENERIMA_BERKALA) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NO_REKENING_PENERIMA) + '</td>';
  							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].BANK_PENERIMA) + '</td>';
  							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data[i].NOM_MANFAAT_NETTO)) + '</td>';
  							html_data += '<td style="text-align: right;">' + format_uang(getValue(jdata.data[i].NOM_SUDAH_BAYAR)) + '</td>';
								html_data += '<td style="text-align: center;">' 
                								 + '<a href="javascript:void(0)" onclick="fl_js_jpbklmonitoring_viewformstatus(\'' 
                								 + getValue(jdata.data[i].KODE_KLAIM) + '\', \'' 
																 + getValue(jdata.data[i].NO_KONFIRMASI) + '\', \'' 
																 + getValue(jdata.data[i].NO_PROSES) + '\', \'' 
																 + getValue(jdata.data[i].KD_PRG) + '\', \''
																 + getValue(jdata.data[i].KODE_PEMBAYARAN) + '\', \'' 
																 + getValue(jdata.data[i].KODE_TRANSFER) + '\', \''
                								 + v_kode_status_transfer + '\')">';
								html_data += '<font color='+v_color+'>'+v_ket_status_transfer+'</font></a></td>';								 
  							html_data += '</tr>';
							}
						}
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="13" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_list_jpbklmonitoring").html(html_data);
						
						// load info halaman
						$("#pages").val(jdata.pages);
						$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

						// load info item
						$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
						$("#hdn_total_records").val(jdata.recordsTotal);

						fl_js_jpbklmonitoring_resize_grid();
							
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
													 
	function fl_js_jpbklmonitoring_viewformstatus(
		p_kode_klaim, 
		p_no_konfirmasi,
		p_no_proses, 
		p_kd_prg, 
		p_kode_pembayaran, 
		p_kode_transfer, 
		p_kode_status_transfer
		)
	{
		var c_mid = '<?=$mid;?>';
		
		if (p_kode_status_transfer == 'DLM_PROSES')
		{
		 	alert('Mohon bersabar, sedang menuggu proses transfer...'); 
		}else if (p_kode_status_transfer == 'SUKSES_TRF')
		{
		 	showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_pembayaranberkala_detil.php?kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&no_proses='+p_no_proses+'&kd_prg='+p_kd_prg+'&kode_pembayaran='+p_kode_pembayaran+'&mid='+c_mid+'','',1050,640,'yes');		 			
		}else if (p_kode_status_transfer == 'ERROR')
		{
			showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_jpberkalamonitoring_viewerror.php?p=pn5063.php&a=formreg&kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&no_proses='+p_no_proses+'&kd_prg='+p_kd_prg+'&mid='+c_mid+'','',1150,650,'yes'); 
		}else if (p_kode_status_transfer == 'GET_STATUS')
		{
			showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_jpberkalamonitoring_getstatus.php?p=pn5063.php&a=formreg&kode_klaim='+p_kode_klaim+'&no_konfirmasi='+p_no_konfirmasi+'&no_proses='+p_no_proses+'&kd_prg='+p_kd_prg+'&kode_transfer='+p_kode_transfer+'&mid='+c_mid+'','',1050,640,'yes'); 
		}
	}				
</script>
