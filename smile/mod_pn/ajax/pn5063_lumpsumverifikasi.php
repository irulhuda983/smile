<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/* =============================================================================
Ket : Form ini digunakan untuk verifikasi data lumpsum siap bayar
Hist: - 01/09/2020 : Pembuatan Form (Tim SMILE)
      - 24/11/2021 : penambahan transfer luar negeri (source dbprod 24112021)							 						 
------------------------------------------------------------------------------*/
$ls_kode_klaim = $_POST["KODE_KLAIM"] =="null" ? "" : $_POST["KODE_KLAIM"];
$task 				 = $_POST["TASK"] 			=="null" ? "" : $_POST["TASK"];

if ($task == "") 
{
  //datagrid -------------------------------------------------------------------
  ?>
  <div id="div_header" class="div-header">
    <div class="div-header-content">
    </div>
  </div>

  <div id="div_body" class="div-body" style="width: 98%;">
		<div id="div_dummy_data" style="width: 100%;"></div>
		<div id="div_filter">
      <div class="div-row" style="padding-top: 2px;">
        <div class="div-col" style="padding: 2px;">			
          Tanggal :&nbsp;
          <input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" onblur="convert_date(tglawaldisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglawaldisplay', 'dd-mm-y');">  
          &nbsp;s/d&nbsp;
          <input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" onblur="convert_date(tglakhirdisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglakhirdisplay', 'dd-mm-y');">							
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <a class="a-icon-text" href="#" onclick="fl_js_lumpverif_filter();" title="Klik Untuk Menampilkan Data Siap Bayar">
            <img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
          </a>
					&nbsp;&nbsp;|&nbsp;&nbsp;
          <a class="a-icon-text" href="#" onclick="fl_js_cetakSiapBayar();" title="Klik Untuk Mencetak Laporan Siap Bayar">
            <img src="../../images/printer.png" border="0" alt="cetak" align="absmiddle">
          </a>					
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="fl_js_lumpverif_search_by2_changed();">
            <option value="">Keyword Lain</option>
            <option value="">----------------</option>
            <option value="KODE_TIPE_KLAIM">Tipe Klaim</option> 
            <option value="KODE_SEBAB_KLAIM">Sebab Klaim</option>
            <option value="KODE_SEGMEN">Segmen Kepesertaan</option>
						<option value="JENIS_KANAL_PELAYANAN">Jenis Layanan</option> 
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
          <span id="JENIS_KANAL_PELAYANAN" hidden="">
            <select size="1" id="search_txt2d" name="search_txt2d" value="" class="select_format" style="width:100px;">
              <option value="">-- Pilih --</option>
              <option value="MANUAL">MANUAL</option>
							<option value="BPJSTKU">BPJSTKU</option>
              <option value="ANTOL">ANTOL</option>
              <option value="ONLINE">ONLINE</option>
              <option value="ONSITE_WA">ONSITE WA</option>
              <option value="ONSITE_WEB">ONSITE WEB</option>
              <option value="ONLINE JMO">ONLINE JMO</option>
              <option value="sc_eklaim_jmo">EKLAIM PMI JMO</option>
            </select>							
          </span>					
        </div>
        <div class="div-col-right" style="padding: 2px;">
        	<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="fl_js_lumpverif_search_by_changed()">
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
              <th style="text-align: center; width: 20px;!important;">Action</th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Kode Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Kantor
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="TGL_KLAIM" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Tgl Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="TGL_PENETAPAN" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Tgl Penetapan
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>											
              <th style="text-align: left;">
                <a href="#" order_by="KPJ" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">No Referensi
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_PENGAMBIL_KLAIM" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Nama Peserta
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_SEGMEN" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Segmen
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KET_TIPE_KLAIM" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Tipe
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
							<th style="text-align: center;">
								<a href="#" order_by="JENIS_KANAL_PELAYANAN" order_type="DESC" onclick="orderby(this)">Jns Layanan
									<img class="order-icon" src="../../images/sort_both.png">
								</a>
							</th>							
							<th style="text-align: left;">
                <a href="#" order_by="IS_VALID_REK_PENERIMA" order_type="DESC" onclick="fl_js_lumpverif_orderby(this)">Rek. Valid
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
            </tr>
          </thead>
          <tbody id="data_list_lumpverif">
            <tr class="nohover-color">
            	<td colspan="11" style="text-align: center;">-- Data tidak ditemukan --</td>
            </tr>
          </tbody>
        </table>   
      </div>
		</div>
		
		<div id="div_page" class="div-page">
      <div class="div-row" style="padding-top: 8px;">
        <div class="div-col">
          <span style="vertical-align: middle;">Halaman</span>
          <a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="fl_js_lumpverif_filter('-02')"><<</a>
          <a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="fl_js_lumpverif_filter('-01')">Prev</a>
          <input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="fl_js_lumpverif_filter(this.value);"/>
          <a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="fl_js_lumpverif_filter('01')">Next</a>
          <a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="fl_js_lumpverif_filter('02')">>></a>
          <span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
          <input type="hidden" id="pages">
        </div>
        <div class="div-col-right">
          <input type="hidden" id="hdn_total_records">
          <span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
          &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
          <span style="vertical-align: middle;" >Tampilkan</span>
          <select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="fl_js_lumpverif_filter();">
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
  
	<div id="div_footer" class="div-footer">
    <div class="div-footer-content">
    	<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
    	Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <font color="#ff0000"> Tampilkan</font> untuk memulai pencarian data
    </div>
  </div>		
	<?
}else if ($task == "edit" || $task == "view")
{
 	//action task edit, view -----------------------------------------------
	?>
	<div id="div_body" class="div-body" >
    <span id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval1" name="st_errval1">					
    <span id="dispError2" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval2" name="st_errval2">
    <span id="dispError3" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval3" name="st_errval3">
    <span id="dispError4" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval4" name="st_errval4">
    <span id="dispError5" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval5" name="st_errval5">
    <span id="dispError6" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval6" name="st_errval6">
    <span id="dispError7" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></span>
    <input type="hidden" id="st_errval7" name="st_errval7">
    <input type="hidden" id="count_empty_required" name="count_empty_required" value="0">
    <input type="hidden" id="errmess_empty_required" name="errmess_empty_required" value="">																																
    <input type="hidden" id="url_path_mnf" name="url_path_mnf" value="">	
						
    <ul id="nav" style="width:99%;">					
      <li><a href="#tab1" id="tabid1" style="display:block"><span style="vertical-align: middle;" id="span_judul_tab1"></span></a></li>	
      <li><a href="#tab2" id="tabid2" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab2"></span></a></li>	
      <li><a href="#tab3" id="tabid3" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab3"></span></a></li>
    </ul>
						
		<div style="display: none;" id="tab1" class="tab_konten">
			<div id="konten" style="width:98%;">
				<div id="formtab1a"></div>
				<div id="formtab1b"></div>
				<div id="formtab1c"></div>
				<div id="formtab1d"></div>
				<div id="formtab1e"></div>
				<div id="formtab1f"></div>
				<div id="formtab1g"></div>
				<div id="formtab1h"></div>
				<div id="formtab1i"></div>
				<div id="formtab1j"></div>	 
			</div>	 
		</div>
						
    <div style="display: none;" id="tab2" class="tab_konten">
      <div id="konten" style="width:98%;">
        <span id="span_list_byr" style="display:none;">	 
          </br>	 					
          <fieldset><legend>Informasi Pembayaran Klaim</legend>
            <table id="tblListByr" width="100%" class="table-data2">
    					<thead>		
                <tr>
                  <th style="text-align:center;" colspan="6">&nbsp;</th>
                  <th style="text-align:center;" colspan="3" class="hr-single-bottom">Jumlah Pembayaran</th>
                  <th style="text-align:center;">&nbsp;</th>												
                </tr>										
                <tr class="hr-double-bottom">
                  <th style="text-align:center;">No</th>
                  <th style="text-align:center;">Tipe</th>
                  <th style="text-align:center;">Prog</th>
                  <th style="text-align:center;">Nama</th>
                  <th style="text-align:center;">NPWP</th>
                  <th style="text-align:center;">Mekanisme Pembayaran</th>
                  <th style="text-align:center;">Netto</th>
                  <th style="text-align:center;">Sdh Dibayar</th>
                  <th style="text-align:center;">Sisa</th>
                  <th style="text-align:center;width:150px;">Action</th>				    							
                </tr>
              </thead>
              <tbody id="data_list_byr">
                <tr class="nohover-color">
                	<td colspan="10" style="text-align: center;">-- tidak ada data --</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <td style="text-align:right;" colspan="6"><i>Total Keseluruhan :<i>
                    <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                    <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                    <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
                  </td>
                  <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_listbyr_netto"></span></td>
                  <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_listbyr_sdhbyr"></span></td>
                  <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_listbyr_sisa"></span></td>
                  <td></td>										        
                </tr>
              </tfoot>															
            </table>
            </br></br></br>
          </fieldset>	
        </span>
        
        <span id="span_byr_rinci" style="display:none;">
        	<div id="formbyrrinci"></div>		
        </span>							
      </div>
    </div>
						
    <div style="display: none;" id="tab3" class="tab_konten">
      <div id="konten" style="width:98%;"></div>						
    </div>												 
	</div><!--end div_body-->

  <div id="div_footer" class="div-footer">
    <span id="span_button_utama" style="display:none;">	 		 
      <div class="div-footer-form">
        <div class="div-row">
          <span id="span_button_tutup" style="display:none;">
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doBack2Grid" href="#" onClick="fl_js_reloadPage();">
                    <img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
                    <span>TUTUP</span>
                  </a>
                </div>
              </div>
            </div>					
          </span>																																																						
        </div>
      </div>
      
      <div style="padding-top:6px;width:99%;">
        <div class="div-footer-content">
          <div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
          <li style="margin-left:15px;">Kolom <font color="#ff0000">Sisa</font> menunjukkan nilai manfaat yang belum dibayarkan ke peserta.</li>
        </div>													
      </div>
    </span><!--end span_button_utama-->
						
    <span id="span_button_siapbyr" style="display:none;">
      <div class="div-footer-form">
        <div class="div-row">
          <span id="span_button_edit_siapbyr" style="display:none;">
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doEditSiapByr" href="#" onClick="confirm_edit()">
                    <img src="../../images/ico_edit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                    <span>EDIT DATA SIAP BAYAR &nbsp;&nbsp;&nbsp;&nbsp;</span>
                  </a>
                </div>
              </div>
            </div>					
          </span>
        
          <span id="span_button_saveedit_siapbyr" style="display:none;">
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doSaveEditSiapByr" href="#" onClick="confirm_saveedit()">
                    <img src="../../images/ico_save.jpg" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                    <span>SIMPAN PERUBAHAN DATA &nbsp;&nbsp;&nbsp;&nbsp;</span>
                	</a>
                </div>
              </div>
            </div>
          
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doCancelEditSiapByr" href="#" onClick="confirm_cancel()">
                    <img src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                    <span>CANCEL PERUBAHAN DATA &nbsp;&nbsp;&nbsp;&nbsp;</span>
                  </a>
                </div>
              </div>
            </div>															
          </span>
        
          <span id="span_button_submit_siapbyr" style="display:none;">
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doSubmitSiapByr" href="#" onClick="confirm_submit()">
                    <img src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                    <span>SUBMIT DATA SIAP BAYAR &nbsp;&nbsp;&nbsp;&nbsp;</span>
                  </a>
                </div>
              </div>
            </div>					
          </span>
        
          <span id="span_button_tutup_siapbyr" style="display:none;">
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doTutupSiapByr" href="#" onClick="fl_js_lumpverif_reloadFormEntry('<?=$task;?>', '<?=$ls_kode_klaim;?>');">
                    <img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
                    <span>TUTUP &nbsp;&nbsp;&nbsp;&nbsp;</span>
                  </a>
                </div>
              </div>
            </div>					
          </span>																																																																															
        </div>
      </div>
    
      <div style="padding-top:6px;width:99%;">
        <div class="div-footer-content">
          <div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
          Transfer menggunakan rekening sentralisasi kantor pusat. Jika transfer <font color="#ff0000"> Antar Rekening (dalam satu bank)</font> maka disaat yang bersamaan uang akan ditransfer ke rekening penerima.</br>
          Jika transfer <font color="#ff0000"> Antar Bank</font> maka metode transfer yang digunakan adalah SKN/RTGS dimana memerlukan waktu 1 hari untuk sampai ke rekening penerima.
        </div>													
      </div>									
    </span><!--end span_button_siapbyr-->

    <span id="span_button_viewbyr" style="display:none;">												
      <div class="div-footer-form">
        <div class="div-row">
          <span id="span_button_cetak_viewbyr" style="display:none;">
          	<span id="div_button_cetak_viewbyr"></span>	
          </span>
        
          <div class="div-col">
            <div class="div-action-footer">
              <div class="icon">
                <a id="btn_doTutupViewByr" href="#" onClick="fl_js_lumpverif_reloadFormEntry('<?=$task;?>', '<?=$ls_kode_klaim;?>');">
                  <img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
                  <span>TUTUP &nbsp;&nbsp;&nbsp;&nbsp;</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div style="padding-top:6px;width:99%;">
        <div class="div-footer-content">
          <div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
          Transfer menggunakan rekening sentralisasi kantor pusat. Jika transfer <font color="#ff0000"> Antar Rekening (dalam satu bank)</font> maka disaat yang bersamaan uang akan ditransfer ke rekening penerima.</br>
          Jika transfer <font color="#ff0000"> Antar Bank</font> maka metode transfer yang digunakan adalah SKN/RTGS dimana memerlukan waktu 1 hari untuk sampai ke rekening penerima.
        </div>													
      </div>							
    </span>																																														 
  </div><!--end div_footer-->		
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
			fl_js_lumpverif_resize_grid();
		});
		
		fl_js_lumpverif_resize_grid();

		/** list checkbox */
		window.list_checkbox_record = [];
		
		fl_js_lumpverif_get_searchlist();
	});
	
	function fl_js_lumpverif_resize_grid(){		 
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

	function fl_js_lumpverif_search_by_changed(){
		$("#search_txt").val("");
	}
		
	function fl_js_lumpverif_search_by2_changed(){
    $('#KODE_TIPE_KLAIM').hide();
    $('#KODE_SEBAB_KLAIM').hide(); 
    $('#KODE_SEGMEN').hide();
    $('#JENIS_KANAL_PELAYANAN').hide();	
		$('#search_txt2a').val('');
		$('#search_txt2b').val('');
		$('#search_txt2c').val('');
		$('#search_txt2d').val('');
		$('#'+$('#search_by2').val()).show();
	}
						
	function fl_js_lumpverif_orderby(e) {
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

		fl_js_lumpverif_filter();
	}
	
	function fl_js_lumpverif_filter(val = 0){
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
		html_data += '<td colspan="11" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list_lumpverif").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe 				 		 : 'MainDataGridLumpsumVerif',
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
						// load data
						for(var i = 0; i < jdata.data.length; i++){
							let kode_klaim = getValue(jdata.data[i].KODE_KLAIM);
							let checkedBox = window.list_checkbox_record.find(function(element) {
								if (element.KODE_KLAIM == kode_klaim) {
									return element;
								}
							});
							
							var v_status_valid_rekening_penerima = 'T';
							v_status_valid_rekening_penerima = getValue(jdata.data[i].IS_VALID_REK_PENERIMA) === "Y" ? "<img src=../../images/file_apply.gif> Ya" : "<img src=../../images/file_cancel.gif> Tidak"; 
							
							html_data += '<tr>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].ACTION) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_KANTOR) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].TGL_PENETAPAN) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KPJ) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].NAMA_PENGAMBIL_KLAIM) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KODE_SEGMEN) + '</td>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].KET_TIPE_KLAIM) + '</td>';
							html_data += '<td style="text-align: center;">' + getValue(jdata.data[i].JENIS_KANAL_PELAYANAN) + '</td>';
							html_data += '<td style="text-align: left;">' + v_status_valid_rekening_penerima + '</td>';
							html_data += '</tr>';
						}
						if (html_data == "") {
							html_data += '<tr class="nohover-color">';
							html_data += '<td colspan="11" style="text-align: center;">-- Data tidak ditemukan --</td>';
							html_data += '</tr>';
						}
						$("#data_list_lumpverif").html(html_data);
						
						// load info halaman
						$("#pages").val(jdata.pages);
						$("#span_info_halaman").html('dari ' + jdata.pages + ' halaman');

						// load info item
						$("#span_info_item").html('Menampilkan item ke ' + jdata.start + ' sampai dengan ' + jdata.end + ' dari ' + jdata.recordsTotal + ' items');
						$("#hdn_total_records").val(jdata.recordsTotal);

						fl_js_lumpverif_resize_grid();
							
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
	
	function fl_js_lumpverif_doGridTask(v_editid, v_jenis_pembayaran, v_status_siapbayar, v_tgl_awal, v_tgl_akhir)
  {				
		document.formreg.task.value = 'edit';
		document.formreg.editid.value = v_editid;
		document.formreg.kode_klaim.value = v_editid;
		document.formreg.tmp_jenis_pembayaran.value = v_jenis_pembayaran;
		document.formreg.tmp_status_siapbayar.value = v_status_siapbayar;
		document.formreg.tmp_tglawaldisplay.value = v_tgl_awal;
		document.formreg.tmp_tglakhirdisplay.value = v_tgl_akhir;
			
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }

	function fl_js_lumpverif_reloadFormUtama()
  {
		document.formreg.task_detil.value = '';
		
		try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
		
	function fl_js_lumpverif_reloadFormEntry(task, editid)
  {
		document.formreg.task.value = task;
		document.formreg.editid.value = editid;
		document.formreg.kode_klaim.value = editid;
		fl_js_lumpverif_reloadFormUtama();	
  }
	
	function fl_js_cetakSiapBayar()
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_cetak_siapbayar.php?&mid='+c_mid+'','',900,620,'yes');
	}
	
	function fl_js_lumpverif_get_searchlist()
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
</script>

<?
//----------------------------- EDIT/VIEW --------------------------------------
?>
<script language="javascript">
	function fl_js_lumpverif_loadSelectedRecord(v_kode_klaim, fn)
	{
		if (v_kode_klaim == '') {
			return alert('Kode Klaim tidak boleh kosong');
		}
		
		var v_task = $('#task').val();
		var v_tmp_status_siapbayar = $('#tmp_status_siapbayar').val();
				
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatabykodeklaim',
				v_kode_klaim: v_kode_klaim
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
						var v_jenis_klaim 		 	= getValue(jdata.data.INFO_KLAIM['JENIS_KLAIM']);
						var v_kode_segmen 			= getValue(jdata.data.INFO_KLAIM['KODE_SEGMEN']);
						var v_flag_agenda_12 		= getValue(jdata.data.INFO_KLAIM['FLAG_AGENDA_12']) == "" ? "T" : getValue(jdata.data.INFO_KLAIM['FLAG_AGENDA_12']);
						var v_kode_pointer_asal = getValue(jdata.data.INFO_KLAIM['KODE_POINTER_ASAL']);
						var v_kode_sebab_klaim 	= getValue(jdata.data.INFO_KLAIM['KODE_SEBAB_KLAIM']);
						var v_cnt_lumpsum 			= getValue(jdata.data.INFO_KLAIM['CNT_LUMPSUM']) == "" ? "0" : getValue(jdata.data.INFO_KLAIM['CNT_LUMPSUM']);
						var v_cnt_berkala 			= getValue(jdata.data.INFO_KLAIM['CNT_BERKALA']) == "" ? "0" : getValue(jdata.data.INFO_KLAIM['CNT_BERKALA']);
            var v_flag_lumpsum			= v_cnt_lumpsum > 0 ? "Y" : "T";
						var v_flag_berkala			= v_cnt_berkala > 0 ? "Y" : "T";
						
						//------------------------ INFORMASI KLAIM -------------------------
						//generate layout informasi klaim ----------------------------------
						//--------------------- set layour per jenis klaim -----------------
						if (v_jenis_klaim=="JHT")
						{
						 	//----------------------- set layout jht -------------------------	
						 	$("#span_page_title").html('PN5004 - PEMBAYARAN KLAIM JAMINAN HARI TUA (JHT)');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JHT');
							
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
							$('#formtab1b').load('../ajax/pn5069_view_jht.php', getValueArr(jdata.data.DATA_INPUTJHT));
							$('#formtab1c').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							$('#formtab1d').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							//----------------------- end set layout jht ---------------------
						}else if (v_jenis_klaim=="JKP")
						{
						 	//----------------------- set layout jkp -------------------------	
						 	$("#span_page_title").html('PN5004 - PEMBAYARAN KLAIM JAMINAN KEHILANGAN PEKERJAAN (JKP)');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JKP');
							
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
							$('#formtab1b').load('../ajax/pn5069_view_jkp.php', getValueArr(jdata.data.INFO_KLAIM));
							$('#formtab1c').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							$('#formtab1d').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							//----------------------- end set layout jkp ---------------------
						}else if (v_jenis_klaim=="JHM")
						{
							//------------------------ set layout jht/jkm --------------------
							$("#span_page_title").html('PN5004 - PEMBAYARAN KLAIM JHT/JKM');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JHT/JKM');	
							
							//set layout informasi klaim -------------------------------------
							$('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
							
							var v_jhm_datajkm = [];
							v_jhm_datajkm = {KET_TAMBAHAN : getValue(jdata.data.INFO_KLAIM['KET_TAMBAHAN']), TGL_KEMATIAN : getValue(jdata.data.INFO_KLAIM['TGL_KEMATIAN'])}
							
							$('#formtab1b').load('../ajax/pn5069_view_jhm.php', {DATA_INPUTJHT:getValueArr(jdata.data.DATA_INPUTJHT), DATA_INPUTJKM:v_jhm_datajkm});
							$('#formtab1c').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							$('#formtab1d').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});			
							//------------------------ end set layout jht/jkm ----------------												
						}else if (v_jenis_klaim=="JKK")
						{
							//------------------------ set layout jkk ------------------------
						 	$("#span_page_title").html('PN5004 - PEMBAYARAN KLAIM JKK');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JKK');	
							
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
                    $('#formtab1b').load('../ajax/pn5069_view_jkk_1tahap_skk11.php', getValueArr(jdata.data.INFO_KLAIM));
                  }else if (v_kode_sebab_klaim == "SKK22")
                  {
                    //gagal ditempatkan -----------------------------
										$('#formtab1b').load('../ajax/pn5069_view_jkk_1tahap_skk22.php', getValueArr(jdata.data.INFO_KLAIM));
                  }else if (v_kode_sebab_klaim == "SKK18" || v_kode_sebab_klaim == "SKK26")
                  {
                    //kerugian atas tindakan pihak lain (kehilangan)-
										$('#formtab1b').load('../ajax/pn5069_view_jkk_1tahap_skk18.php', getValueArr(jdata.data.INFO_KLAIM));
                  }else if (v_kode_sebab_klaim == "SKK21")
                  {
                    //pemulangan tki bermasalah ---------------------
										$('#formtab1b').load('../ajax/pn5069_view_jkk_1tahap_skk21.php', getValueArr(jdata.data.INFO_KLAIM));
                  }						
                } 
              }
							
							$('#formtab1i').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							
							$('#formtab1j').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							//----------------------- end set layout jkk ---------------------										
						}else if (v_jenis_klaim=="JKM")
						{
							//------------------------ set layout jkm ------------------------
						 	$("#span_page_title").html('PN5004 - PEMBAYARAN KLAIM JKM');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JKM');
							
							//set layout informasi klaim -------------------------------------
              $('#formtab1a').load('../ajax/pn5069_view_tabinfoklaim.php', getValueArr(jdata.data.INFO_KLAIM));
              $('#formtab1b').load('../ajax/pn5069_view_jkm.php', getValueArr(jdata.data.INFO_KLAIM));
							
              if (v_kode_segmen=="JAKON")
              {
               	$('#formtab1c').load('../ajax/pn5069_view_jakon_tk.php', getValueArr(jdata.data.INFO_KLAIM));
              }

							//set layout penetapan manfaat -----------------------------------
							$('#formtab1i').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});
							$('#formtab1j').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});
							
							//----------------------- end set layout jkm ---------------------											
						}else if (v_jenis_klaim=="JPN")
						{
							//------------------------ set layout jpn ------------------------
						 	$("#span_page_title").html('PN5004 - PEMBAYARAN KLAIM JP');
							$("#span_judul_tab1").html('Informasi Agenda Klaim JP');
							
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
							
							if (v_flag_lumpsum == "Y")
							{						
  							//set layout penetapan manfaat lumpsum -------------------------
  							$('#formtab1c').load('../ajax/pn5069_view_penetapanmanfaat.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfLumpsum.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfLumpsum.DATA), DATA_DOKUMEN_TAMBAHAN : getValueArr(jdata.data.DATA_DOKUMEN_TAMBAHAN)});							
							}
							
							if (v_flag_berkala == "Y")
							{
								//set layout penetapan manfaat jp berkala ----------------------
  							$('#formtab1e').load('../ajax/pn5069_view_penetapanmanfaat_jpnberkala.php', {DATA_MANFAAT : getValueArr(jdata.dataTapMnfBerkala.DATA), DATA_PENERIMA : getValueArr(jdata.dataPenerimaMnfBerkala.DATA)});						
							}	
							
							$('#formtab1j').load('../ajax/pn5069_view_tabadministrasi.php', {DATA_DOKUMEN : getValueArr(jdata.data.DATA_DOKUMEN)});													
							//---------------------- end set layout jpn ----------------------	
						}																	
						//end generate layout informasi klaim ------------------------------
						//---------------------- END INFORMASI KLAIM -----------------------
						
						//------------------------ PEMBAYARAN KLAIM ------------------------
						var v_judul_tab2 = "";
						if (v_tmp_status_siapbayar=="1")
						{
						 	v_judul_tab2 = "Verifikasi Data Siap Bayar"; 
						}else
						{
						 	v_judul_tab2 = "Pembayaran Manfaat"; 	 
						}
						
						$("#span_judul_tab2").html(v_judul_tab2);
						window.document.getElementById("tabid2").style.display = 'block'; 
						window.document.getElementById("span_list_byr").style.display = 'block';
						
						var html_data_listbyr = "";
  					var v_tot_listbyr_netto = 0;
						var v_tot_listbyr_sdhbyr = 0;
						var v_tot_listbyr_sisa = 0;
						var v_ket_dibayar_ke = "";

  					if (jdata.dataListByr.DATA)
  					{ 
  						v_no = 1;
							for(var i = 0; i < jdata.dataListByr.DATA.length; i++)
  						{
  							v_ket_dibayar_ke = "";
  							if (getValue(jdata.dataListByr.DATA[i].KODE_CARA_BAYAR)=="V")
  							{
  							 	 v_ket_dibayar_ke = getValue(jdata.dataListByr.DATA[i].NAMA_CARA_BAYAR)+' - NOMOR VA DIKIRIMKAN KE NO.HP '+getValue(jdata.dataListByr.DATA[i].HANDPHONE);
  							}else
  							{
  							 	 if (getValue(jdata.dataListByr.DATA[i].KODE_CARA_BAYAR)!="")
									 {
									 		var v_nama_cara_bayar = getValue(jdata.dataListByr.DATA[i].NAMA_CARA_BAYAR);
									 }else
									 {
									 		var v_nama_cara_bayar = "TRANSFER";	
									 }
									 v_ket_dibayar_ke = v_nama_cara_bayar+' KE '+getValue(jdata.dataListByr.DATA[i].BANK_PENERIMA)+' NO.REK '+getValue(jdata.dataListByr.DATA[i].NO_REKENING_PENERIMA)+' A/N '+getValue(jdata.dataListByr.DATA[i].NAMA_REKENING_PENERIMA);
  							}
  																
                html_data_listbyr += '<tr>';
								html_data_listbyr += '<td style="text-align: center;">' + v_no + '</td>';
                html_data_listbyr += '<td style="text-align: center;">' + getValue(jdata.dataListByr.DATA[i].NAMA_TIPE_PENERIMA) + '</td>';
                html_data_listbyr += '<td style="text-align: center;">' + getValue(jdata.dataListByr.DATA[i].NM_PRG) + '</td>';
                html_data_listbyr += '<td style="text-align: center;">' + getValue(jdata.dataListByr.DATA[i].NAMA_PENERIMA) + '</td>';
								html_data_listbyr += '<td style="text-align: center;">' + getValue(jdata.dataListByr.DATA[i].NPWP) + '</td>';
                html_data_listbyr += '<td style="text-align: center; white-space:pre-wrap; word-wrap:break-word;">' + v_ket_dibayar_ke + '</td>';
                html_data_listbyr += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataListByr.DATA[i].NOM_MANFAAT_NETTO)) + '</td>';
								html_data_listbyr += '<td style="text-align: right;">' + format_uang(getValue(jdata.dataListByr.DATA[i].NOM_SUDAH_BAYAR)) + '</td>';
								html_data_listbyr += '<td style="text-align: right;"><font color="#009999">' + format_uang(getValue(jdata.dataListByr.DATA[i].NOM_SISA)) + '</font></td>';
                html_data_listbyr += '<td style="text-align: center; white-space:pre-wrap; word-wrap:break-word;">'; 
								if (v_tmp_status_siapbayar=="1") 
                {
                  //grid verifikasi data siap bayar ----------------------------
                  //jika status_siapbayar T maka SIAP BAYAR, jika Y maka View
                  if (parseFloat(format_uang(getValue(jdata.dataListByr.DATA[i].NOM_SISA)),2)>0)
                  {
                    if (getValue(jdata.dataListByr.DATA[i].STATUS_SIAPBAYAR)=="Y")
                    {
                      html_data_listbyr += '<a href="#" onClick="fl_js_lumpverif_detilSiapBayar(\'view\', \''+getValue(jdata.dataListByr.DATA[i].KODE_KLAIM)+'\', \''+getValue(jdata.dataListByr.DATA[i].KODE_TIPE_PENERIMA)+'\', \''+getValue(jdata.dataListByr.DATA[i].KD_PRG)+'\');">';
                      html_data_listbyr += '<img src="../../images/document.gif" border="0" alt="Ubah Divisi" align="absmiddle" style="height:20px;" />';
                      html_data_listbyr += '<font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">&nbsp;VIEW </font>';
											html_data_listbyr += '</a>';
                    }else
                    {
                      html_data_listbyr += '<a href="#" onClick="fl_js_lumpverif_detilSiapBayar(\'edit\', \''+getValue(jdata.dataListByr.DATA[i].KODE_KLAIM)+'\', \''+getValue(jdata.dataListByr.DATA[i].KODE_TIPE_PENERIMA)+'\', \''+getValue(jdata.dataListByr.DATA[i].KD_PRG)+'\');">';
                      html_data_listbyr += '<img src="../../images/checkX.png" border="0" alt="Ubah Divisi" align="absmiddle" style="height:20px;" />';
                      html_data_listbyr += '<font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">&nbsp;VERIFIKASI </font>';
											html_data_listbyr += '</a>';
                    }
                    
                  }else
                  {
                    //jika sudah ditransfer dan kode_pembayaran sudah terbentuk utk salah satu tipe penerima maka view detil pembayaran------
										if (getValue(jdata.dataListByr.DATA[i].KODE_PEMBAYARAN) !="")
										{
  										html_data_listbyr += '<a href="#" onClick="fl_js_lumpverif_detilViewBayar(\''+getValue(jdata.dataListByr.DATA[i].KODE_KLAIM)+'\', \''+getValue(jdata.dataListByr.DATA[i].KODE_PEMBAYARAN)+'\');">';
                      html_data_listbyr += '<img src="../../images/document.gif" border="0" alt="Ubah Divisi" align="absmiddle" style="height:20px;" />';
                      html_data_listbyr += '<font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">&nbsp;VIEW </font>';
  										html_data_listbyr += '</a>';										
										}else
										{
  										html_data_listbyr += '<a href="#" onClick="fl_js_lumpverif_detilSiapBayar(\'view\', \''+getValue(jdata.dataListByr.DATA[i].KODE_KLAIM)+'\', \''+getValue(jdata.dataListByr.DATA[i].KODE_TIPE_PENERIMA)+'\', \''+getValue(jdata.dataListByr.DATA[i].KD_PRG)+'\');">';
                      html_data_listbyr += '<img src="../../images/document.gif" border="0" alt="Ubah Divisi" align="absmiddle" style="height:20px;" />';
                      html_data_listbyr += '<font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">&nbsp;VIEW </font>';
  										html_data_listbyr += '</a>';															
                  	}
									}
                }else if (v_tmp_status_siapbayar=="3") 
                {
                  //grid transfer pembayaran -----------------------------------
                  //jika status approval siap bayar Y maka tampilkan tombol ----
									//jika status_transfer T maka BAYAR, jika Y maka view
									if (getValue(jdata.dataListByr.DATA[i].STATUS_APVBAYAR)=="Y")
									{
                    if (parseFloat(format_uang(getValue(jdata.dataListByr.DATA[i].NOM_SISA)),2)>0)
                    {
                      if (getValue(jdata.dataListByr.DATA[i].STATUS_TRANSFER)=="Y")
                      {
                        html_data_listbyr += '<font color="#ff0000">&nbsp;TERDAPAT KENDALA DALAM PROSES PEMBAYARAN, HARAP MELAKUKAN PENGECEKAN PADA MENU MONITORING STATUS TRANSFER </font>';
                      }else
                      {
                        html_data_listbyr += '';
                      }
                    }else
                    {
                      if (getValue(jdata.dataListByr.DATA[i].KODE_PEMBAYARAN)!="")
  										{
    										html_data_listbyr += '<a href="#" onClick="fl_js_lumpverif_viewSuksesBayar(\'view\', \''+getValue(jdata.dataListByr.DATA[i].KODE_KLAIM)+'\', \''+getValue(jdata.dataListByr.DATA[i].KODE_PEMBAYARAN)+'\');">';
                        html_data_listbyr += '<img src="../../images/document.gif" border="0" alt="Ubah Divisi" align="absmiddle" style="height:20px;" />';
                        html_data_listbyr += '<font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">&nbsp;VIEW </font>';
      									html_data_listbyr += '</a>';
  										}else
  										{
                        html_data_listbyr += '<font color="#ff0000">&nbsp;TERDAPAT KENDALA DALAM PROSES PEMBAYARAN, HARAP MELAKUKAN PENGECEKAN PADA MENU MONITORING STATUS TRANSFER </font>';										
  										}															
                    }
									}else
									{
									 	html_data_listbyr += '<font color="#ff0000">&nbsp;BELUM SIAP BAYAR </font>';	 
									}																			
                }else if (v_tmp_status_siapbayar=="4") 
                {
								 	html_data_listbyr += '<font color="#ff0000">&nbsp;NOMOR VA SUDAH DIKIRIMKAN NAMUN BELUM DILAKUKAN PENGAMBILAN TUNAI OLEH PENERIMA MANFAAT. TGL EXPIRED NOMOR VA '+jdata.dataListByr.DATA[i].TGL_EXPIRED_VA+'</font>';		
								}
                
                if (getValue(jdata.dataListByr.DATA[i].STATUS_LUNAS)=="Y")
                {
                 	html_data_listbyr += '<a href="#" onClick="fl_js_show_lov_cetakbyr(\''+getValue(jdata.dataListByr.DATA[i].KODE_KLAIM)+'\', \''+getValue(jdata.dataListByr.DATA[i].KODE_PEMBAYARAN)+'\');"><img src="../../images/printx.png" border="0" alt="Cetak Pembayaran" align="absmiddle" style="height:20px;"/>&nbsp;Cetak </a>';
                }                							
								html_data_listbyr += '</td>';
                html_data_listbyr += '<tr>';
                
                v_tot_listbyr_netto	+= getValue(jdata.dataListByr.DATA[i].NOM_MANFAAT_NETTO)=='' ? 0 : parseFloat(getValue(jdata.dataListByr.DATA[i].NOM_MANFAAT_NETTO),2);
								v_tot_listbyr_sdhbyr	+= getValue(jdata.dataListByr.DATA[i].NOM_SUDAH_BAYAR)=='' ? 0 : parseFloat(getValue(jdata.dataListByr.DATA[i].NOM_SUDAH_BAYAR),2);
								v_tot_listbyr_sisa	+= getValue(jdata.dataListByr.DATA[i].NOM_SISA)=='' ? 0 : parseFloat(getValue(jdata.dataListByr.DATA[i].NOM_SISA),2);								
    						v_no++;
							}
    															
              if (html_data_listbyr == "") {
                html_data_listbyr += '<tr class="nohover-color">';
                html_data_listbyr += '<td colspan="10" style="text-align: center;">-- belum ada data --</td>';
                html_data_listbyr += '</tr>';
              }
              $("#data_list_byr").html(html_data_listbyr);
              $("#span_tot_listbyr_netto").html(format_uang(v_tot_listbyr_netto));
							$("#span_tot_listbyr_sdhbyr").html(format_uang(v_tot_listbyr_sdhbyr));
							$("#span_tot_listbyr_sisa").html(format_uang(v_tot_listbyr_sisa));
  					}
						//end list pembayaran ----------------------------------------------																		
						//---------------------- END PEMBAYARAN KLAIM ----------------------

						//set button -------------------------------------------------------			
						window.document.getElementById("span_button_utama").style.display = 'block';
						window.document.getElementById("span_button_tutup").style.display = 'block';
						window.document.getElementById("span_button_siapbyr").style.display = 'none';			
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
	//end function loadSelectedRecord --------------------------------------------
</script>
<?
//-------------------------------- END EDIT/VIEW -------------------------------
?>

<script language="javascript">
	$(document).ready(function(){
		let task = $('#task').val();
		if (task=="new")
		{
		}else if (task=="edit" || task=="view")
		{
			let kode_klaim = $('#kode_klaim').val();
			fl_js_lumpverif_loadSelectedRecord(kode_klaim, null);
						
			//set active tab-------------------------
			let v_activetab = $('#activetab').val();
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
        $(aktif).fadeIn('slow'); 								// tab yang dipilih, ditampilkan
        return false;
      });	
			//end set active tab---------------------		
		}else
		{
			var v_tgl_awal_display  = $('#tmp_tglawaldisplay').val();
			var v_tgl_akhir_display = $('#tmp_tglakhirdisplay').val();
			
		 	$('#editid').val('');
			$('#kode_klaim').val('');
			
			fl_js_set_tgl_display(v_tgl_awal_display,v_tgl_akhir_display);
			fl_js_lumpverif_filter();	 
		}
	});
</script>

<script language="javascript">	
	//verifikasi data siapa bayar ------------------------------------------------
  function fl_js_lumpverif_detilSiapBayar(v_mode_trx, v_kode_klaim, v_kode_tipe_penerima, v_kd_prg)
  {					
    var fn;
    window.document.getElementById("span_list_byr").style.display = 'none';
    window.document.getElementById("span_byr_rinci").style.display = 'block';
    
    var v_no_penetapan 				= $('#no_penetapan').val();
    var v_kode_kantor	 	 			= $('#kode_kantor').val();						
    var v_nama_tipe_klaim			= $('#nama_tipe_klaim').val();
    var v_keyword_sebab_klaim	= $('#keyword_sebab_klaim').val();	
    var v_nama_sebab_klaim		= $('#nama_sebab_klaim').val();
		var v_jenis_klaim					= $('#jenis_klaim').val();
    var v_kpj									= $('#kpj').val();
    var v_nama_tk							= $('#nama_tk').val();
    var v_nomor_identitas			= $('#nomor_identitas').val();
    var v_no_proyek						= $('#no_proyek').val();
    var v_nama_proyek					= $('#nama_proyek').val();
    var v_nama_pelaksana_kegiatan = $('#nama_pelaksana_kegiatan').val();
    var v_nama_kegiatan				= $('#nama_kegiatan').val();		
    var v_npp									= $('#npp').val();
    var v_nama_perusahaan			= $('#nama_perusahaan').val();
							
		var v_is_rekening_sentral = $('#tmp_status_rekening_sentral').val();
		if (v_is_rekening_sentral=='')
		{
		 	v_is_rekening_sentral = 'T'; 
		}
		
    if (v_kode_klaim == '' || v_kode_tipe_penerima == '' || v_kd_prg == '') {
    	return alert('Kode Klaim/tipe penerima/program tidak boleh kosong');
    }
					
    asyncPreload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5063_action.php?"+Math.random(),
      data: {
        tipe: 'fjq_ajax_val_getdatasiapbayar_detil',
        v_kode_klaim: v_kode_klaim,
        v_kode_tipe_penerima:v_kode_tipe_penerima,
        v_kd_prg:v_kd_prg
      },
      success: function(data){
        try {
          jdata = JSON.parse(data);
          if (jdata.ret == 0) 
          {
            //generate layout rincian siap bayar -------------------------------
        	 	var v_status_rekening_sentral="";
        		var v_kantor_rekening_sentral="";
            if (v_mode_trx=='edit')
        		{
        		 	v_status_rekening_sentral = v_is_rekening_sentral;
        			if (v_is_rekening_sentral=="Y")
        			{
        			 	v_kantor_rekening_sentral = "ATP"; 
        			}   
        		}else
        		{
        		 	v_status_rekening_sentral = getValue(jdata.dataDtlSiapByr.DATA['STATUS_REKENING_SENTRAL']);
        			v_kantor_rekening_sentral = getValue(jdata.dataDtlSiapByr.DATA['KANTOR_REKENING_SENTRAL']);	 
        		}
        		
        		var html_input  = '';
            html_input += '<div class="div-row" >';
            html_input += '  <div class="div-col" style="width:49%; max-height: 100%;">';
            html_input += '      <fieldset><legend>Informasi Penetapan Klaim Nomor '+v_no_penetapan+' Kacab '+v_kode_kantor+'</legend>';		
            html_input += '				</br>';
            html_input += '				<div class="form-row_kiri">';
            html_input += '        <label  style = "text-align:right;">Jenis Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '          <input type="text" id="byr_nama_tipe_klaim" name="byr_nama_tipe_klaim" value="'+v_nama_tipe_klaim+'" style="width:40px;" readonly class="disabled">';
            html_input += '    			<input type="text" id="byr_keyword_sebab_klaim" name="byr_keyword_sebab_klaim" value="'+v_keyword_sebab_klaim+'" style="width:30px;" readonly class="disabled">';
            html_input += '					<input type="text" id="byr_nama_sebab_klaim" name="byr_nama_sebab_klaim" value="'+v_nama_sebab_klaim+'" style="width:182px;" readonly class="disabled">';
        		html_input += '					<input type="hidden" id="byr_jenis_klaim" name="byr_jenis_klaim" value="'+v_jenis_klaim+'">';
            html_input += '      	</div>';
            html_input += '				<div class="clear"></div>';		
        		
            html_input += '		    <span id="byr_span_kpj" style="display:block;">';						
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">No Referensi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '		        <input type="text" id="byr_kpj" name="byr_kpj" value="'+v_kpj+'" maxlength="30" style="width:80px;" readonly class="disabled">';
            html_input += '		        <input type="text" id="byr_nama_tk" name="byr_nama_tk" value="'+v_nama_tk+'" style="width:180px;" readonly class="disabled">';
            html_input += '		      </div>';																																								
            html_input += '		      <div class="clear"></div>';
            			
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">NIK &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '		        <input type="text" id="byr_nomor_identitas" name="byr_nomor_identitas" value="'+v_nomor_identitas+'" style="width:250px;" readonly class="disabled">';
            html_input += '					</div>';																																							
            html_input += '		      <div class="clear"></div>';									
            html_input += '		    </span>';
            
            html_input += '		    <span id="byr_span_proyek" style="display:none;">';						
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">No Proyek &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>';
            html_input += '		        <input type="text" id="byr_no_proyek" name="byr_no_proyek" value="'+v_no_proyek+'" style="width:120px;" readonly class="disabled">';
            html_input += '		        <input type="text" id="byr_nama_proyek" name="byr_nama_proyek" value="'+v_nama_proyek+'" style="width:130px;" readonly class="disabled">';		
            html_input += '		      </div>';																																										
            html_input += '		      <div class="clear"></div>';						
            html_input += '		    </span>';	
            		
            html_input += '		    <span id="byr_span_kegiatan_tambahan" style="display:none;">';						
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">Pelaksana Kegiatan &nbsp;</label>';
            html_input += '		        <input type="text" id="byr_nama_pelaksana_kegiatan" name="byr_nama_pelaksana_kegiatan" value="'+v_nama_pelaksana_kegiatan+'" style="width:250px;" class="disabled">';
            html_input += '		      </div>';																																									
            html_input += '		      <div class="clear"></div>';	
                  
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>';
            html_input += '		        <input type="text" id="byr_nama_kegiatan" name="byr_nama_kegiatan" value="'+v_nama_kegiatan+'" style="width:250px;" readonly class="disabled">';			
            html_input += '		      </div>';																																									
            html_input += '		      <div class="clear"></div>';
            html_input += '		    </span>';	
        		
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">NPP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>';
            html_input += '		      <input type="text" id="byr_npp" name="byr_npp" value="'+v_npp+'" style="width:60px;" readonly class="disabled">';
            html_input += '		      <input type="text" id="byr_nama_perusahaan" name="byr_nama_perusahaan" value="'+v_nama_perusahaan+'" style="width:160px;" readonly class="disabled">';				
            html_input += '		    </div>';																										
            html_input += '		    <div class="clear"></div>';
        	
        		html_input += '				</br>';		
        					
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Nominal Gross </label>';
            html_input += '		      <input type="text" id="byr_nom_manfaat_gross" name="byr_nom_manfaat_gross" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_MANFAAT_GROSS']))+'" style="width:250px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																																																		
            html_input += '		    <div class="clear"></div>';
        
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">PPN </label>';
            html_input += '		     <input type="text" id="byr_nom_ppn" name="byr_nom_ppn" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_PPN']))+'" style="width:150px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';
        		html_input += '					<input type="text" id="byr_kode_pajak_ppn" name="byr_kode_pajak_ppn" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_PAJAK_PPN'])+'" style="width:70px;" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
        				        
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">PPh </label>';
            html_input += '		      <input type="text" id="byr_nom_pph" name="byr_nom_pph" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_PPH']))+'" style="width:150px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';
        		html_input += '					<input type="text" id="byr_kode_pajak_pph" name="byr_kode_pajak_pph" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_PAJAK_PPH'])+'" style="width:70px;" readonly class="disabled" >';
        		html_input += '				</div>';																		
            html_input += '		    <div class="clear"></div>';
                
        		html_input += '				<div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Pembulatan </label>';
            html_input += '		      <input type="text" id="byr_nom_pembulatan" name="byr_nom_pembulatan" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_PEMBULATAN']))+'" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
        
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Netto </label>';
            html_input += '		      <input type="text" id="byr_nom_netto" name="byr_nom_netto" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_MANFAAT_NETTO']))+'" style="width:230px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
        						
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Sudah Dibayar </label>';
            html_input += '		      <input type="text" id="byr_nom_sudah_bayar" name="byr_nom_sudah_bayar" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_SUDAH_BAYAR']))+'" style="width:230px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
        						
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Jml yg hrs Dibayar </label>';
            html_input += '		      <input type="text" id="byr_nom_sisa" name="byr_nom_sisa" value="'+format_uang(getValue(jdata.dataDtlSiapByr.DATA['NOM_SISA']))+'" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
                
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Program &nbsp;</label>';
            html_input += '		      <input type="hidden" id="byr_kd_prg" name="byr_kd_prg" value="'+getValue(jdata.dataDtlSiapByr.DATA['KD_PRG'])+'" size="20" maxlength="10" readonly class="disabled" >';
        		html_input += '					<input type="text" id="byr_nm_prg" name="byr_nm_prg" value="'+getValue(jdata.dataDtlSiapByr.DATA['NM_PRG'])+'" style="width:200px;" readonly class="disabled" >';                					
            html_input += '		    </div>';																																																			
            html_input += '		    <div class="clear"></div>';

            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label style = "text-align:right;">Keterangan&nbsp;</label>';
            html_input += '		    <textarea cols="255" rows="1" id="byr_keterangan" name="byr_keterangan" tabindex="11" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly style="width:230px;height:15px;background-color:#F5F5F5">'+getValue(jdata.dataDtlSiapByr.DATA['KETERANGAN'])+'</textarea>';   					
            html_input += '		    </div>';							
            html_input += '		    <div class="clear"></div>';									        						
            html_input += '		  </fieldset>';		
          	html_input += '  </div>';
          	
          	html_input += '  <div class="div-col" style="width:1%;">';
            html_input += '  </div>';
          	
          	html_input += '  <div class="div-col-right" style="width:50%;">';
            html_input += '    <div class="div-row">';
          	html_input += '  		<div class="div-col" style="width: 100%">';
          	html_input += '  			<fieldset style="height:145px;"><legend>Informasi Penerima Manfaat</legend>';
            html_input += '         <div class="div-row">';
            html_input += '           <div class="div-col" style="width: 150px;text-align:center;">';
            html_input += '           	<input id="byr_nik_penerima_foto" name="byr_nik_penerima_foto" type="image" align="center" src="../../images/nopic.png" style="height: 107px !important; width: 100px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>';
            html_input += '           </div>';
            html_input += '           <div class="div-col">';
            html_input += '             <div class="form-row_kiri" >';
            html_input += '             <label style = "text-align:left;width:100px;">Penerima Manfaat</label>';
            html_input += '               <input type="text" id="byr_nama_tipe_penerima" name="byr_nama_tipe_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_TIPE_PENERIMA'])+'" style="width:250px;" readonly class="disabled" >';  
            html_input += '               <input type="hidden" id="byr_kode_tipe_penerima" name="byr_kode_tipe_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_TIPE_PENERIMA'])+'">';
            html_input += '             </div>';																																																	
            html_input += '             <div class="clear"></div>';
                          
            html_input += '             <div class="form-row_kiri">';
            html_input += '             <label style = "text-align:left;width:100px;">Nama Penerima</label>';
            html_input += '               <input type="text" id="byr_nama_penerima" name="byr_nama_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_PENERIMA'])+'" style="width:220px;" readonly class="disabled">';
            html_input += '               <input type="hidden" id="byr_nik_penerima" name="byr_nik_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NIK_PENERIMA'])+'" style="width:210px;" readonly class="disabled">';
            html_input += '               <input type="hidden" id="byr_tgl_siapbayar" name="byr_tgl_siapbayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['TGL_SIAPBAYAR'])+'" maxlength="10" readonly class="disabled" style="width:210px;">';
            html_input += '               <input type="hidden" id="byr_status_siapbayar" name="byr_status_siapbayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['STATUS_SIAPBAYAR'])+'">';
        		html_input += '               <input type="hidden" id="byr_petugas_siapbayar" name="byr_petugas_siapbayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['PETUGAS_SIAPBAYAR'])+'">';
            html_input += '         			<input type="text" id="byr_similarity_nama_penerima" name="byr_similarity_nama_penerima" readonly style="width:20px;text-align:center;" value="'+getValue(jdata.dataDtlSiapByr.DATA['SIMILARITY_NAMA_PENERIMA'])+'">&nbsp;%';
						html_input += '             </div>';																																																																																														
            html_input += '             <div class="clear"></div>';
                          
            html_input += '             <div class="form-row_kiri">';
            html_input += '             <label style = "text-align:left;width:100px;">NPWP</label>';	    				
            html_input += '             	<input type="text" id="byr_npwp_penerima" name="byr_npwp_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NPWP_PENERIMA'])+'" style="width:250px;" readonly class="disabled" onblur="fl_js_val_npwp(\'byr_npwp_penerima\');">';
        		html_input += '             	<input type="hidden" id="byr_npwp_penerima_old" name="byr_npwp_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['NPWP_PENERIMA'])+'">';
            html_input += '             </div>';																																	
            html_input += '             <div class="clear"></div>';
                            
            html_input += '             <div class="form-row_kiri">';
            html_input += '             <label style = "text-align:left;width:100px;">Email</label>';	    				
            html_input += '             	<input type="text" id="byr_email_penerima" name="byr_email_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['EMAIL_PENERIMA'])+'" style="width:250px;" onblur="this.value=this.value.toLowerCase();fl_js_val_email(\'byr_email_penerima\');" readonly class="disabled">';
            html_input += '             </div>';																																	
            html_input += '             <div class="clear"></div>';
                          
            html_input += '             <div class="form-row_kiri">';
            html_input += '             <label style = "text-align:left;width:100px;">Handphone</label>';	    				
            html_input += '             	<input type="text" id="byr_handphone_penerima" name="byr_handphone_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['HANDPHONE_PENERIMA'])+'" style="width:200px;" readonly class="disabled" onblur="fl_js_val_numeric(\'byr_handphone_penerima\');" >';
            html_input += '  						</div>';																																	
            html_input += '             <div class="clear"></div>';
            html_input += '           </div>';
            html_input += '         </div>';
          	html_input += '  			</fieldset>';		
          	html_input += '  		</div>';	 
          	html_input += '  	 </div>';
        		
        		html_input += '    <div class="div-row">';
        		html_input += '    	<div class="div-col" style="width: 100%">';
            html_input += '        <fieldset style="min-height:120px;"><legend><i><font color="#009999">Dibayarkan ke :</font></i></legend>';
            html_input += '          <div class="form-row_kiri">';
            html_input += '          <label  style = "text-align:right;">Cara Bayar*</label>';
            html_input += '            <input type="text" id="byr_nama_cara_bayar" name="byr_nama_cara_bayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_CARA_BAYAR'])+'" readonly class="disabled" style="width:310px;">';
            html_input += '            <input type="hidden" id="byr_kode_cara_bayar" name="byr_kode_cara_bayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_CARA_BAYAR'])+'">';
            html_input += '						 <a class="a-icon-input" href="#" onclick="fl_js_lumpverif_get_lov_carabayar();">';							
            html_input += '						 <img id="btn_lov_byr_cara_bayar" src="../../images/help.png" alt="Cari Cara Bayar" border="0" align="absmiddle" style="display:none;"></a>';
        		html_input += '          </div>';		    																																				
            html_input += '          <div class="clear"></div>';
        
          	html_input += '   			<span id="byr_span_va_debit" style="display:none;">';		
        		html_input += '           </br>';						
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">&nbsp;</label>'; 	    				
            html_input += '             <i><font color="#ff0000">No.VA dikirimkan ke No.HP '+getValue(jdata.dataDtlSiapByr.DATA['HANDPHONE_PENERIMA'])+'</font></i>';
        		html_input += '					  	<input type="hidden" value="'+getValue(jdata.dataDtlSiapByr.DATA['IS_VERIFIED_HP'])+'" id="byr_is_verified_hp" name="byr_is_verified_hp">';
            html_input += '           </div>';																																																																																																																																																												
            html_input += '           <div class="clear"></div>';
            html_input += '         </span>';

          	html_input += '   			<span id="byr_span_ln" style="display:none;">';						
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">Bank</label>'; 
						html_input += '             <input type="text" id="byr_ln_kode_bank_penerima" name="byr_ln_kode_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PENERIMA'])+'" maxlength="20" readonly style="width:80px;background-color:#F5F5F5" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_reset_norek_ln();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="text" id="byr_ln_nama_bank_penerima" name="byr_ln_nama_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['BANK_PENERIMA'])+'" maxlength="100" readonly style="width:240px;background-color:#F5F5F5" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="hidden" id="byr_ln_id_bank_penerima" name="byr_ln_id_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['ID_BANK_PENERIMA'])+'"style="width:100px;">';
            html_input += '             <input type="hidden" id="byr_ln_metode_transfer" name="byr_ln_metode_transfer" value="'+getValue(jdata.dataDtlSiapByr.DATA['METODE_TRANSFER'])+'" maxlength="4" readonly class="disabled" style="width:20px;">';
            html_input += '             <input type="hidden" id="byr_ln_kode_bank_penerima_old" name="byr_ln_kode_bank_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PENERIMA'])+'">';
            html_input += '           </div>';																																																	
            html_input += '           <div class="clear"></div>';                   
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">No Rekening</label>';
            html_input += '             <input type="text" id="byr_ln_no_rekening_penerima" name="byr_ln_no_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NO_REKENING_PENERIMA'])+'" tabindex="22" onkeydown="if(event.key==='+"'.'"+' || event.key==='+"'e'"+' ){event.preventDefault();}" onpaste="let pasteData = event.clipboardData.getData('+"'text'"+'); if(pasteData){pasteData.replace(/[^0-9]*/g,'+"''"+');} " maxlength="30" readonly class="disabled" style="width:120px;background-color:#F5F5F5" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="hidden" id="byr_ln_nama_rekening_penerima" name="byr_ln_nama_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" readonly class="disabled" style="width:180px;background-color:#F5F5F5">';
            html_input += '             <input type="text" id="byr_ln_nama_rekening_penerima_ws" name="byr_ln_nama_rekening_penerima_ws" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" style="width:180px;background-color:#F5F5F5" tabindex="23" readonly class="disabled" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="checkbox" id="cb_byr_ln_valid_rekening" name="cb_byr_ln_valid_rekening" disabled class="cebox" '+(getValue(jdata.dataDtlSiapByr.DATA['STATUS_VALID_REKENING_PENERIMA'])=='Y' ? 'checked' : '')+'><i><font color="#009999">Valid</font></i>';	
            html_input += '             <input type="hidden" id="byr_ln_status_valid_rekening_penerima" name="byr_ln_status_valid_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['STATUS_VALID_REKENING_PENERIMA'])+'">';
        		html_input += '             <input type="hidden" id="byr_ln_no_rekening_penerima_old" name="byr_ln_no_rekening_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['NO_REKENING_PENERIMA'])+'">';
						html_input += '           </div>';																																																																																															
            html_input += '           <div class="clear"></div>'; 
            html_input += '           </br>'; 						                
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">'; 	    				
            html_input += '             <img src="../../images/infox.png" border="0" alt="Tambah" align="top" style="height:15px;"/></label>'; 
            html_input += '             <i>Kode SWIFT dapat diakses melalui <a href="#" onclick="fl_js_lumpverif_get_www_swiftcode();"><font color="#ff0000">www.transfez.com/swift-codes</font></a></i>';
            html_input += '           </div>';																																																																																																																																																												
            html_input += '           <div class="clear"></div>';
            html_input += '         </span>';
						        	
          	html_input += '   			<span id="byr_span_rekening" style="display:none;">';						
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">Bank</label>'; 
            html_input += '             <input type="text" id="byr_nama_bank_penerima" name="byr_nama_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['BANK_PENERIMA'])+'" readonly style="width:310px;background-color:#F5F5F5">';
            html_input += '             <input type="hidden" id="byr_kode_bank_penerima" name="byr_kode_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PENERIMA'])+'"style="width:100px;">';
            html_input += '             <input type="hidden" id="byr_id_bank_penerima" name="byr_id_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['ID_BANK_PENERIMA'])+'"style="width:100px;">';
            html_input += '             <input type="hidden" id="byr_metode_transfer" name="byr_metode_transfer" value="'+getValue(jdata.dataDtlSiapByr.DATA['METODE_TRANSFER'])+'" maxlength="4" readonly class="disabled" style="width:20px;">';
            html_input += '             <a style="display:none;" id="btn_lov_byr_bank_penerima" href="#" onclick="fl_js_lumpverif_get_lov_bank_penerima();">';							
            html_input += '             <img src="../../images/help.png" alt="Cari Bank" border="0" style="height:19px;" align="absmiddle"></a>';
        		html_input += '             <input type="hidden" id="byr_kode_bank_penerima_old" name="byr_kode_bank_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PENERIMA'])+'">';
            html_input += '           </div>';																																																	
            html_input += '           <div class="clear"></div>';                   
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">No Rekening</label>';
            html_input += '             <input type="number" id="byr_no_rekening_penerima" name="byr_no_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NO_REKENING_PENERIMA'])+'" onblur="fjq_ajax_val_lumpverif_no_rekening_penerima();" tabindex="22" onkeydown="if(event.key==='+"'.'"+' || event.key==='+"'e'"+' ){event.preventDefault();}" onpaste="let pasteData = event.clipboardData.getData('+"'text'"+'); if(pasteData){pasteData.replace(/[^0-9]*/g,'+"''"+');} " maxlength="30" readonly class="disabled" style="width:150px;background-color:#F5F5F5">';
            html_input += '             <input type="hidden" id="byr_nama_rekening_penerima" name="byr_nama_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" readonly class="disabled" style="width:120px;background-color:#F5F5F5">';
            html_input += '             <input type="text" id="byr_nama_rekening_penerima_ws" name="byr_nama_rekening_penerima_ws" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" style="width:150px;background-color:#F5F5F5" tabindex="23" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
            html_input += '             <input type="checkbox" id="cb_byr_valid_rekening" name="cb_byr_valid_rekening" disabled class="cebox" '+(getValue(jdata.dataDtlSiapByr.DATA['STATUS_VALID_REKENING_PENERIMA'])=='Y' ? 'checked' : '')+'><i><font color="#009999">Valid</font></i>';	
            html_input += '             <input type="hidden" id="byr_status_valid_rekening_penerima" name="byr_status_valid_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['STATUS_VALID_REKENING_PENERIMA'])+'">';
        		html_input += '             <input type="hidden" id="byr_no_rekening_penerima_old" name="byr_no_rekening_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['NO_REKENING_PENERIMA'])+'">';
						html_input += '           </div>';																																																																																															
            html_input += '           <div class="clear"></div>'; 
            html_input += '           </br>'; 						                
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">'; 	    				
            html_input += '             <img src="../../images/warning.gif" border="0" alt="Tambah" align="top" style="height:12px;"/></label>'; 
            html_input += '             <i><font color="#ff0000">Rekening harus valid untuk menghindari gagal transfer..!</font></i>';
            html_input += '           </div>';																																																																																																																																																												
            html_input += '           <div class="clear"></div>';
            html_input += '         </span>';
            html_input += '       </fieldset>';				
        		html_input += '   	</div>';	
        		html_input += '   </div>';
        
        		html_input += '   <div class="div-row">';
        		html_input += '   	<div class="div-col" style="width: 100%">';
            html_input += '       <fieldset style="height:67px;"><legend><i><font color="#009999">Dari :</font></i></legend>';
            if (v_mode_trx=='edit')
        		{
          		html_input += '   			<span id="byr_span_entry_bank_pembayar" style="display:none;">';						
            	html_input += '         	<div class="form-row_kiri">';
            	html_input += '         	<label style = "text-align:right;">Bank &nbsp;&nbsp;*</label>';
        			html_input += '         		<select size="1" id="byr_kode_bank_pembayar_entry" name="byr_kode_bank_pembayar_entry" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PEMBAYAR'])+'" tabindex="24" class="select_format" readonly style="width:305px;background-color:#ffff99;" >';
              html_input += '         			<option value="">-- Pilih --</option>';
              html_input += '         		</select>';
        			html_input += '         	</div>';																																												
            	html_input += '         	<div class="clear"></div>';

              html_input += '           <div class="form-row_kiri">';
              html_input += '           <label style = "text-align:right;">&nbsp;</label>';
              html_input += '             <i><font color="#546E7A">(*..Pilih ulang bank penerima jika pilihan bank Dari kosong..</font></i>';
              html_input += '           </div>';																																																																																																																																																												
              html_input += '           <div class="clear"></div>';											
        			html_input += '   			</span>';	
        			html_input += '   			<span id="byr_span_display_bank_pembayar" style="display:block;">';	
            	html_input += '         	<div class="form-row_kiri">';
            	html_input += '         	<label style = "text-align:right;">Bank &nbsp;&nbsp;*</label>';
            	html_input += '         		<input type="text" id="byr_nama_bank_pembayar" name="byr_nama_bank_pembayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_BANK_PEMBAYAR'])+'" readonly class="disabled" style="width:280px;">';
        			html_input += '         		<input type="hidden" id="byr_kode_bank_pembayar" name="byr_kode_bank_pembayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PEMBAYAR'])+'">';
        			html_input += '         	</div>';																																												
            	html_input += '         	<div class="clear"></div>';
        			html_input += '   			</span>';	     
        		}else
        		{
            	html_input += '         <div class="form-row_kiri">';
            	html_input += '         <label style = "text-align:right;">Bank &nbsp;&nbsp;*</label>';
        		 	html_input += '         	<input type="text" id="byr_nama_bank_pembayar" name="byr_nama_bank_pembayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_BANK_PEMBAYAR'])+'" readonly class="disabled" style="width:280px;">';
        			html_input += '         	<input type="hidden" id="byr_kode_bank_pembayar" name="byr_kode_bank_pembayar" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PEMBAYAR'])+'">';
        			html_input += '         </div>';																																												
            	html_input += '         <div class="clear"></div>';
        		}
            html_input += '         <input type="hidden" id="byr_id_bank_opg" name="byr_id_bank_opg">';
            html_input += '         <input type="hidden" id="byr_kode_buku" name="byr_kode_buku" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BUKU'])+'">';
        		html_input += '         <input type="hidden" id="byr_klm_kode_pointer_asal" name="byr_klm_kode_pointer_asal" value="'+getValue(jdata.dataDtlSiapByr.DATA['KLM_KODE_POINTER_ASAL'])+'" style="width:30px;" readonly class="disabled">';    								
            html_input += '         <input type="hidden" id="byr_status_rekening_sentral" name="byr_status_rekening_sentral" value="'+v_status_rekening_sentral+'">';
            html_input += '         <input type="hidden" id="byr_kantor_rekening_sentral" name="byr_kantor_rekening_sentral" value="'+v_kantor_rekening_sentral+'">';
            html_input += '         <input type="hidden" id="byr_st_mode_edit" name="byr_st_mode_edit" value="">';
            html_input += '       </fieldset>';				
        		html_input += '   	</div>';		 
        		html_input += '   </div>';
        		html_input += '  </div>';	
        		html_input += '</div>';	
        											
            if (html_input !="")
            {
             	$('#formbyrrinci').html(html_input); 
            }
    
        		if (getValue(jdata.dataDtlSiapByr.DATA['NIK_PENERIMA'])!='')
        		{
        		 	$('#byr_nik_penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + getValue(jdata.dataDtlSiapByr.DATA['NIK_PENERIMA']));
        		}
       
        		fl_js_lumpverif_span_byr_carabayar();
						fl_js_lumpverif_byr_span_kpj();
						fl_js_lumpverif_similarity_namapenerima();
						
            window.document.getElementById("span_button_utama").style.display = 'none';
            window.document.getElementById("span_button_siapbyr").style.display = 'block';
						window.document.getElementById("span_button_tutup_siapbyr").style.display = 'block';
						if (v_mode_trx=='edit')
						{
						 	window.document.getElementById("span_button_edit_siapbyr").style.display = 'block';
							window.document.getElementById("span_button_submit_siapbyr").style.display = 'block';
							window.document.getElementById("span_button_saveedit_siapbyr").style.display = 'none';
            }
						
            //end generate layout rincian penerima manfaat -------------------
            if (fn && fn.success) {
            	fn.success();
            }
      		} else {
          	alert(jdata.msg);
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
	//end function fl_js_lumpverif_detilSiapBayar ------------------------------------------
	
  function fl_js_lumpverif_span_byr_carabayar()
  {
    var	v_kode_cara_bayar = window.document.getElementById('byr_kode_cara_bayar').value;

		if (v_kode_cara_bayar =="V" || v_kode_cara_bayar =="TTK") //va debet -------
    {
			window.document.getElementById("byr_span_va_debit").style.display = 'block';
			window.document.getElementById("byr_span_rekening").style.display = 'none';
			window.document.getElementById("byr_span_ln").style.display = 'none';
		}else if (v_kode_cara_bayar =="L") //transfer luar negeri -------
    {
			window.document.getElementById("byr_span_va_debit").style.display = 'none';
			window.document.getElementById("byr_span_rekening").style.display = 'none';
			window.document.getElementById("byr_span_ln").style.display = 'block';		
    }else
    {	   
			window.document.getElementById("byr_span_va_debit").style.display = 'none';
			window.document.getElementById("byr_span_rekening").style.display = 'block';
			window.document.getElementById("byr_span_ln").style.display = 'none';
    }
  }

	function fl_js_lumpverif_reset_norek_ln()
	{
    var v_kode_bank_penerima_old = $('#byr_ln_kode_bank_penerima_old').val();
		var v_kode_bank_penerima = $('#byr_ln_kode_bank_penerima').val();
		
		if (v_kode_bank_penerima!=v_kode_bank_penerima_old)
		{
		 	 $('#byr_ln_nama_bank_penerima').val('');
			 $('#byr_ln_no_rekening_penerima').val('');
			 $('#byr_ln_nama_rekening_penerima_ws').val('');
			 $('#byr_ln_nama_rekening_penerima').val(''); 
			 fl_js_lumpverif_sync_norek_ln();
		}
	}
		
	function fl_js_lumpverif_sync_norek_ln()
	{
    var v_kode_bank_penerima = $('#byr_ln_kode_bank_penerima').val();
		var v_nama_bank_penerima = $('#byr_ln_nama_bank_penerima').val();
		var v_no_rekening_penerima = $('#byr_ln_no_rekening_penerima').val();
		var v_nama_rekening_penerima_ws = $('#byr_ln_nama_rekening_penerima_ws').val();
		
		$('#byr_ln_kode_bank_penerima_old').val(v_kode_bank_penerima);
		
		$('#byr_kode_bank_penerima').val(v_kode_bank_penerima);
		$('#byr_id_bank_penerima').val(v_kode_bank_penerima);
		$('#byr_nama_bank_penerima').val(v_nama_bank_penerima);
		$('#byr_metode_transfer').val('TT');
		$('#byr_kode_bank_penerima_old').val(v_kode_bank_penerima);
		
		$('#byr_no_rekening_penerima').val(v_no_rekening_penerima);
		$('#byr_nama_rekening_penerima_ws').val(v_nama_rekening_penerima_ws);
		$('#byr_nama_rekening_penerima').val(v_nama_rekening_penerima_ws);
		$('#byr_status_valid_rekening_penerima').val('Y');	
		$('#byr_no_rekening_penerima_old').val(v_no_rekening_penerima);
	}
	
	function fl_js_lumpverif_get_www_swiftcode()
	{
	 	window.open("https://www.transfez.com/swift-codes");
	}
	
  function fl_js_lumpverif_byr_span_kpj() 
  { 
    var v_kode_pointer_asal = window.document.getElementById('byr_klm_kode_pointer_asal').value;
    var v_kode_segmen = window.document.getElementById('kode_segmen').value;
    
    if (v_kode_segmen =="JAKON")
    {
      window.document.getElementById("byr_span_proyek").style.display = 'block';
      window.document.getElementById("byr_span_kpj").style.display = 'none';
      window.document.getElementById("byr_span_kegiatan_tambahan").style.display = 'none';		 
    }else
    {
      if (v_kode_pointer_asal !="" && v_kode_pointer_asal =="PROMOTIF") //data bersumber dari modul lain
      {
        window.document.getElementById("byr_span_proyek").style.display = 'none';
        window.document.getElementById("byr_span_kpj").style.display = 'none';
        window.document.getElementById("byr_span_kegiatan_tambahan").style.display = 'block';	
      }else
      {
        window.document.getElementById("byr_span_proyek").style.display = 'none';
        window.document.getElementById("byr_span_kpj").style.display = 'block';
        window.document.getElementById("byr_span_kegiatan_tambahan").style.display = 'none';				 				 		 
      }			 		 
    } 	
  }

  function fl_js_lumpverif_similarity_namapenerima()
  {
    var	v_similarity = parseFloat(window.document.getElementById('byr_similarity_nama_penerima').value);

		if (v_similarity <= "90")
    {
			document.getElementById('byr_nama_penerima').style.backgroundColor='#FBB4B3';
			document.getElementById('byr_nama_rekening_penerima_ws').style.backgroundColor='#FBB4B3';
			document.getElementById('byr_similarity_nama_penerima').style.backgroundColor='#FBB4B3';
    }else
    {	   
			document.getElementById('byr_nama_penerima').style.backgroundColor='#f5f5f5';
			document.getElementById('byr_nama_rekening_penerima_ws').style.backgroundColor='#f5f5f5';
			document.getElementById('byr_similarity_nama_penerima').style.backgroundColor='#f5f5f5';
    }
  }
		
	//do edit data siap bayar ----------------------------------------------------
  function fjq_ajax_val_lumpverif_doEditSiapBayar()
  {				 
		var v_kode_cara_bayar = $('#byr_kode_cara_bayar').val();
		var v_npwp = $('#byr_npwp_penerima').val();
		var v_kode_bank_pembayar = $('#byr_kode_bank_pembayar').val();
		
		document.getElementById('byr_email_penerima').readOnly = false;
    document.getElementById('byr_email_penerima').style.backgroundColor='#ffffff';

		document.getElementById('byr_keterangan').readOnly = false;
    document.getElementById('byr_keterangan').style.backgroundColor='#ffffff';
				
    if (v_kode_cara_bayar=="")
  	{
  	 	window.document.getElementById("btn_lov_byr_cara_bayar").style.display = '';
			document.getElementById('byr_nama_cara_bayar').readOnly = true;
    	document.getElementById('byr_nama_cara_bayar').style.backgroundColor='#ffff99';	 
  	}else
		{
  		if (v_kode_cara_bayar =="V")
			{
  			document.getElementById('byr_nama_cara_bayar').readOnly = true;
      	document.getElementById('byr_nama_cara_bayar').style.backgroundColor='#f5f5f5';
  		} else
			{
    	 	window.document.getElementById("btn_lov_byr_cara_bayar").style.display = '';
  			document.getElementById('byr_nama_cara_bayar').readOnly = true;
      	document.getElementById('byr_nama_cara_bayar').style.backgroundColor='#ffff99';				
			}
			
			if (v_kode_cara_bayar !="V")
  		{
  		 	if (v_kode_cara_bayar =="L") 
				{
				 	//transfer luar negeri -----------------------------------------------
    			document.getElementById('byr_ln_kode_bank_penerima').readOnly = false;
        	document.getElementById('byr_ln_kode_bank_penerima').style.backgroundColor='#ffff99';
  								
    			document.getElementById('byr_ln_nama_bank_penerima').readOnly = false;
        	document.getElementById('byr_ln_nama_bank_penerima').style.backgroundColor='#ffff99';
    			
    			document.getElementById('byr_ln_no_rekening_penerima').readOnly = false;
        	document.getElementById('byr_ln_no_rekening_penerima').style.backgroundColor='#ffff99';
  				
    			document.getElementById('byr_ln_nama_rekening_penerima_ws').readOnly = false;
        	document.getElementById('byr_ln_nama_rekening_penerima_ws').style.backgroundColor='#ffff99';
					
					document.getElementById('byr_ln_kode_bank_penerima').placeholder = "Kode SWIFT";
					document.getElementById('byr_ln_nama_bank_penerima').placeholder = "Nama Bank";
					document.getElementById('byr_ln_no_rekening_penerima').placeholder = "No. Rekening";
					document.getElementById('byr_ln_nama_rekening_penerima_ws').placeholder = "A/N Rekening";
					
					$('#byr_ln_metode_transfer').val('TT');
					$('#byr_metode_transfer').val('TT');			 
				} else
				{
  				//transfer dalam negeri -----------------------------------------------
					//no hp bisa diubah utk pembayaran selalin tunai melalui va debit
    			document.getElementById('byr_handphone_penerima').readOnly = false;
        	document.getElementById('byr_handphone_penerima').style.backgroundColor='#ffffff';
    			
    			document.getElementById('byr_nama_bank_penerima').readOnly = true;
        	document.getElementById('byr_nama_bank_penerima').style.backgroundColor='#ffff99';
    			
    			window.document.getElementById("btn_lov_byr_bank_penerima").style.display = '';
    			
    			document.getElementById('byr_no_rekening_penerima').readOnly = false;
        	document.getElementById('byr_no_rekening_penerima').style.backgroundColor='#ffff99'; 
				}
  		}
		}		
						
		if (v_npwp=="0" || v_npwp=="000000000000000")
		{
			//jika tidak ada npwp maka npwp tidak dapat diubah krn terkait dg tambahan 
			//potongan pajak yang sudah dibebankan pada saat penetapan
			document.getElementById('byr_npwp_penerima').readOnly = true;
    	document.getElementById('byr_npwp_penerima').style.backgroundColor='#f5f5f5';		
		}else
		{
			//jika sebelumnya ada npwp maka npwp dapat diubah namun tidak boleh menjadi 
			//0 krn terkait dg tambahan potongan pajak yang harus dibebankan pada 
			//saat penetapan
			document.getElementById('byr_npwp_penerima').readOnly = false;
    	document.getElementById('byr_npwp_penerima').style.backgroundColor='#ffff99'; 		
		}
		
		window.document.getElementById("byr_span_entry_bank_pembayar").style.display = 'block';
		window.document.getElementById("byr_span_display_bank_pembayar").style.display = 'none';
		
		window.document.getElementById("span_button_edit_siapbyr").style.display = 'none';
		window.document.getElementById("span_button_submit_siapbyr").style.display = 'none';
		window.document.getElementById("span_button_tutup_siapbyr").style.display = 'none';
		window.document.getElementById("span_button_saveedit_siapbyr").style.display = 'block';
		
		//get list bank pembayar ------------------------------------------
		fjq_ajax_val_lumpverif_getlist_bank_pembayar_entry(v_kode_bank_pembayar);
			 				 
  }
  //end do edit data siap bayar ------------------------------------------------

	function fjq_ajax_val_getlist_bank_asal()
	{	 
		//dipanggil saat pilih bank penerima -------	
		fjq_ajax_val_lumpverif_getlist_bank_pembayar_entry('');	 
	}
		
	//ambil bank pembayar --------------------------------------------------------
	function fjq_ajax_val_lumpverif_getlist_bank_pembayar_entry(v_curr_bank_selected)
	{
		v_jenis_transaksi = "KLAIM";
    v_id_transaksi 		= $('#kode_klaim').val();
    v_kode_bank_atb		= $('#byr_kode_bank_penerima').val();
		v_kode_cara_bayar	= $('#byr_kode_cara_bayar').val();
		v_bank_selected		= v_curr_bank_selected;
		
		//reset list terlebih dahulu -----------------------------------------------
		fjq_ajax_val_lumpverif_resetlist_bank_pembayar('byr_kode_bank_pembayar_entry');
		var reset_lov_bank = document.getElementById('byr_kode_bank_pembayar_entry');
    var option = document.createElement('option');
    option.text  = '-- pilih --';
    option.value = '';
    option.setAttribute('nama_bank_pembayar',option.text);
    option.setAttribute('kode_bank_pembayar',option.value);
    reset_lov_bank.add(option);
		//end reset list terlebih dahulu -------------------------------------------
			
		//get data list bayar pembayar ---------------------------------------------
		//cek apakah pembayaran melalui Tunai VA Debit atau transfer ---------------
		if (v_kode_cara_bayar=="V")
		{
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:"get_list_bank_asal_va_debit", v_kode_cara_bayar:v_kode_cara_bayar},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            var lov_bank = document.getElementById('byr_kode_bank_pembayar_entry');
            var v_cnt_support_ttk = 0;
						if(jdata.ret == "0")
            {   
    					for($i=0;$i<(jdata.data.length);$i++)
    					{
                v_cnt_support_ttk = 0;
								
								//looping metode transfer, ambil jika support Tunai Tanpa Kartu 
								for ($x=0;$x<(jdata.data[$i].METODE_TRANSFER.length);$x++)
								{
									if (jdata.data[$i].METODE_TRANSFER[$x]['KODE'] == "TTK")
									{
									 	v_cnt_support_ttk++;  
									}	
								}
								
								if (v_cnt_support_ttk>0)
								{
  								var option = document.createElement('option');  							
    							option.text  = jdata.data[$i]['NAMA_BANK'];
                  option.value = jdata.data[$i]['KODE_BANK'];
    							option.setAttribute('nama_bank_pembayar',jdata.data[$i]['NAMA_BANK']);
                  option.setAttribute('kode_bank_pembayar',jdata.data[$i]['KODE_BANK']);
                  if(jdata.data[$i]['KODE_BANK']==v_bank_selected){
                  	option.selected = true;
                  }
                  lov_bank.add(option); 
								}
              }		
            }else{
            	window.parent.Ext.notify.msg('Gagal get list bank...'+jdata.msg);
            }
          }
        });
		}else if (v_kode_cara_bayar=="L")
		{		
        //transfer luar negeri -------------------------------------------------
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:"get_list_bank_asal_va_debit", v_kode_cara_bayar:v_kode_cara_bayar},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            var lov_bank = document.getElementById('byr_kode_bank_pembayar_entry');
            var v_cnt_support_tt = 0;
						if(jdata.ret == "0")
            {   
    					for($i=0;$i<(jdata.data.length);$i++)
    					{
                v_cnt_support_tt = 0;
								
								//looping metode transfer, ambil jika support Transfer Luar Negeri (TT) 
								for ($x=0;$x<(jdata.data[$i].METODE_TRANSFER.length);$x++)
								{
									if (jdata.data[$i].METODE_TRANSFER[$x]['KODE'] == "TT")
									{
									 	v_cnt_support_tt++;  
									}	
								}
								
								if (v_cnt_support_tt>0)
								{
  								var option = document.createElement('option');  							
    							option.text  = jdata.data[$i]['NAMA_BANK'];
                  option.value = jdata.data[$i]['KODE_BANK'];
    							option.setAttribute('nama_bank_pembayar',jdata.data[$i]['NAMA_BANK']);
                  option.setAttribute('kode_bank_pembayar',jdata.data[$i]['KODE_BANK']);
                  if(jdata.data[$i]['KODE_BANK']==v_bank_selected){
                  	option.selected = true;
                  }
                  lov_bank.add(option); 
								}
              }		
            }else{
            	window.parent.Ext.notify.msg('Gagal get list bank...'+jdata.msg);
            }
          }
        });
		}else
		{
  		//transfer dalam negeri --------------------------------------------------
			if (v_kode_bank_atb!='')
  		{	
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:"get_list_bank_asal", JENIS_TRANSAKSI:v_jenis_transaksi, ID_TRANSAKSI:v_id_transaksi, KODE_BANK_ATB:v_kode_bank_atb },
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            var lov_bank = document.getElementById('byr_kode_bank_pembayar_entry');
            if(jdata.ret == "0")
            {   
    					for($i=0;$i<(jdata.DATA.length);$i++)
    					{
                var option = document.createElement('option');
                var v_nm_bank = '';
  							//set keterangan list utk nama_bank jika sentralisasi ------------
  							if(jdata.IS_SENTRALISASI=='Y')
  							{
  							 	v_nm_bank = jdata.DATA[$i]['NAMA_BANK']+' (SENTRALISASI)';
  							}else
  							{
  							 	v_nm_bank = jdata.DATA[$i]['NAMA_BANK'];	 
  							} 
  							
  							option.text  = v_nm_bank;
                option.value = jdata.DATA[$i]['KODE_BANK'];
  							option.setAttribute('nama_bank_pembayar',v_nm_bank);
                option.setAttribute('kode_bank_pembayar',jdata.DATA[$i]['KODE_BANK']);
                if(jdata.DATA[$i]['KODE_BANK']==v_bank_selected){
                	option.selected = true;
                }
                lov_bank.add(option); 
              }
							
  						//jika rekening sentralsasi maka set status_rekening_sentral = Y
  						if(jdata.IS_SENTRALISASI=='Y')
    					{
    					 	$('#byr_status_rekening_sentral').val('Y');
  							$('#byr_kantor_rekening_sentral').val('ATP');
    					}else
    					{
    					 	$('#byr_status_rekening_sentral').val('T');
  							$('#byr_kantor_rekening_sentral').val(''); 
    					} 		
            }else{
            	window.parent.Ext.notify.msg('Gagal get list bank...'+jdata.msg);
            }
          }
        });
  		}
		}
	}
	//end ambil bank pembayar ----------------------------------------------------
	
	function fl_js_lumpverif_get_lov_carabayar()
  {		 					
		//perubahan cara bayar bisa dilakukan hanya jika cara bayar sebelumnya kosong (masa transisi)
		//dan cara bayar yg akan tampil hanya transfer/spb 
		var v_is_sentralisasi	 = window.document.getElementById('byr_status_rekening_sentral').value;
		var v_jenis_klaim 		 = window.document.getElementById('byr_jenis_klaim').value; 
		var	v_nom_manfaat 		 = removeCommas(window.document.getElementById('byr_nom_sisa').value); 
		var	v_nom_max_va_debit = 0;
		
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_lov_carabayar.php?p=pn5063.php&a=formreg&b=byr_kode_cara_bayar&c=byr_nama_cara_bayar&e=SIAP_BAYAR_LUMPVERIF&f='+v_jenis_klaim+'&g='+v_nom_manfaat+'&h='+v_nom_max_va_debit+'&j='+v_is_sentralisasi+'','',800,500,1);
  }

	//edit cara bayar siap bayar -------------------------------------------------		
  function fl_js_lumpverif_span_carabayar_edit()
  {
    var	v_kode_cara_bayar = window.document.getElementById('byr_kode_cara_bayar').value;
    
		if (v_kode_cara_bayar !="")
		{
  		if (v_kode_cara_bayar =="V" || v_kode_cara_bayar =="TTK") //va debet -----
      {
  			window.document.getElementById("byr_span_va_debit").style.display = 'block';
  			window.document.getElementById("byr_span_rekening").style.display = 'none';
				window.document.getElementById("byr_span_ln").style.display = 'none';
			}else if (v_kode_cara_bayar =="L") //transfer luar negeri ----------------
      {
  			window.document.getElementById("byr_span_va_debit").style.display = 'none';
  			window.document.getElementById("byr_span_rekening").style.display = 'none';	
				window.document.getElementById("byr_span_ln").style.display = 'block';

  			document.getElementById('byr_ln_kode_bank_penerima').readOnly = false;
      	document.getElementById('byr_ln_kode_bank_penerima').style.backgroundColor='#ffff99';
								
  			document.getElementById('byr_ln_nama_bank_penerima').readOnly = false;
      	document.getElementById('byr_ln_nama_bank_penerima').style.backgroundColor='#ffff99';
  			
  			document.getElementById('byr_ln_no_rekening_penerima').readOnly = false;
      	document.getElementById('byr_ln_no_rekening_penerima').style.backgroundColor='#ffff99';
				
  			document.getElementById('byr_ln_nama_rekening_penerima_ws').readOnly = false;
      	document.getElementById('byr_ln_nama_rekening_penerima_ws').style.backgroundColor='#ffff99';
				
  			document.getElementById('byr_ln_kode_bank_penerima').placeholder = "Kode SWIFT";
  			document.getElementById('byr_ln_nama_bank_penerima').placeholder = "Nama Bank";
  			document.getElementById('byr_ln_no_rekening_penerima').placeholder = "No. Rekening";
  			document.getElementById('byr_ln_nama_rekening_penerima_ws').placeholder = "A/N Rekening";
				
				//reset bank dan no_rekening -------------------------------------------
        //Bank dan Nomor Rekening tidak perlu direset permintaan Treasury 25/01/2023
				// $('#byr_ln_kode_bank_penerima').val('');
				// $('#byr_ln_nama_bank_penerima').val('');
				// $('#byr_ln_no_rekening_penerima').val('');
				// $('#byr_ln_nama_rekening_penerima_ws').val('');
				// $('#byr_ln_nama_rekening_penerima').val('');
  			// $('#byr_ln_metode_transfer').val('TT');

				// $('#byr_kode_bank_penerima').val('');
				// $('#byr_nama_bank_penerima').val('');
				// $('#byr_no_rekening_penerima').val('');
				// $('#byr_nama_rekening_penerima_ws').val('');
				// $('#byr_nama_rekening_penerima').val('');				
  			// $('#byr_metode_transfer').val('TT');
  			
  			fjq_ajax_val_getlist_bank_asal();	
      }else
      {	   
  			window.document.getElementById("byr_span_va_debit").style.display = 'none';
  			window.document.getElementById("byr_span_rekening").style.display = 'block';
				window.document.getElementById("byr_span_ln").style.display = 'none';
				
  		 	//no hp bisa diubah utk pembayaran selain tunai melalui va debit
  			document.getElementById('byr_handphone_penerima').readOnly = false;
      	document.getElementById('byr_handphone_penerima').style.backgroundColor='#ffffff';
  			
  			document.getElementById('byr_nama_bank_penerima').readOnly = true;
      	document.getElementById('byr_nama_bank_penerima').style.backgroundColor='#ffff99';
  			
  			window.document.getElementById("btn_lov_byr_bank_penerima").style.display = '';
  			
  			document.getElementById('byr_no_rekening_penerima').readOnly = false;
      	document.getElementById('byr_no_rekening_penerima').style.backgroundColor='#ffff99';
				
				//reset bank dan no_rekening -------------------------------------------
        //Bank dan Nomor Rekening tidak perlu direset permintaan Treasury 25/01/2023
				// $('#byr_ln_kode_bank_penerima').val('');
				// $('#byr_ln_nama_bank_penerima').val('');
				// $('#byr_ln_no_rekening_penerima').val('');
				// $('#byr_ln_nama_rekening_penerima_ws').val('');
				// $('#byr_ln_nama_rekening_penerima').val('');
  			// $('#byr_ln_metode_transfer').val('');

				// $('#byr_kode_bank_penerima').val('');
				// $('#byr_nama_bank_penerima').val('');
				// $('#byr_no_rekening_penerima').val('');
				// $('#byr_nama_rekening_penerima_ws').val('');
				// $('#byr_nama_rekening_penerima').val('');				
  			// $('#byr_metode_transfer').val(''); 				
      }
		}		
  }
	//end edit cara bayar siap bayar ---------------------------------------------	

	function fl_js_lumpverif_reset_norek() 
  {	 
    $('#byr_no_rekening_penerima').val('');
		$('#byr_no_rekening_penerima_old').val('');
    $('#byr_nama_rekening_penerima_ws').val('');
    $('#byr_status_valid_rekening_penerima').val('T');
    //$('#byr_kode_bank_pembayar').val('');
    $('#byr_no_rekening_penerima').focus();
    window.document.getElementById('cb_byr_valid_rekening').checked = false;
  }

  function fl_js_lumpverif_get_lov_bank_penerima()
  {			 					
		fl_js_lumpverif_reset_norek();
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_lov_bankpenerima.php?p=pn5043.php&a=formreg&b=byr_nama_bank_penerima&c=byr_kode_bank_penerima&d=byr_id_bank_penerima','',800,500,1); 
	}

	function fjq_ajax_val_lumpverif_resetlist_bank_pembayar(id)
	{
  	var selectObj = document.getElementById(id);
  	var selectParentNode = selectObj.parentNode;
  	var newSelectObj = selectObj.cloneNode(false); // Make a shallow copy
  	selectParentNode.replaceChild(newSelectObj, selectObj);
  	return newSelectObj;
	}

  //validasi nomor rekening penerima -------------------------------------------
  function fjq_ajax_val_lumpverif_no_rekening_penerima()
  {				 
    var v_kode_bank_tujuan  = $('#byr_kode_bank_penerima').val();
    var v_nama_bank_tujuan  = $('#byr_nama_bank_penerima').val();
    var v_no_rek_tujuan 		= $('#byr_no_rekening_penerima').val();
    
		var v_kode_bank_tujuan_old  = $('#byr_kode_bank_penerima_old').val();
		var v_no_rek_tujuan_old 		= $('#byr_no_rekening_penerima_old').val();
		
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
              $('#byr_nama_rekening_penerima_ws').val(jdata.data['NAMA_REK_TUJUAN']);
							$('#byr_nama_rekening_penerima').val(jdata.data['NAMA_REK_TUJUAN']);
              document.getElementById('byr_nama_rekening_penerima_ws').readOnly = true;
              document.getElementById('byr_nama_rekening_penerima_ws').style.backgroundColor='#F5F5F5';
							
              window.document.getElementById('cb_byr_valid_rekening').checked = true;
              $('#byr_status_valid_rekening_penerima').val('Y');
              
              $('#byr_kode_bank_penerima_old').val(v_kode_bank_tujuan);
							$('#byr_no_rekening_penerima_old').val(v_no_rek_tujuan);
							
              $('#byr_kode_bank_pembayar_entry').focus();																				
            }else{
              //nama_rekening dapat diinput manual (bypass) ------------
              $('#byr_nama_rekening_penerima_ws').val('');	
              document.getElementById('byr_nama_rekening_penerima_ws').readOnly = false;
              document.getElementById('byr_nama_rekening_penerima_ws').style.backgroundColor='#ffff99';
              document.getElementById('byr_nama_rekening_penerima_ws').placeholder = "-- isikan NAMA secara manual --";
							
              window.document.getElementById('cb_byr_valid_rekening').checked = false;
              $('#byr_status_valid_rekening_penerima').val('T');
              $('#byr_nama_rekening_penerima_ws').focus();
              
							$('#byr_kode_bank_penerima_old').val(v_kode_bank_tujuan);
							$('#byr_no_rekening_penerima_old').val(v_no_rek_tujuan);
              alert('Gagal validasi rekening,'+jdata.msg);
            }
          }
        });
      }else
      { 
        $('#byr_nama_rekening_penerima_ws').val('');
        window.document.getElementById('cb_byr_valid_rekening').checked = false;
        $('#byr_status_valid_rekening_penerima').val('T');
        $('#byr_no_rekening_penerima').focus();
      }
    }
  }
  //end validasi nomor rekening penerima ---------------------------------------
	
	//do simpan perubahan data siap bayar ----------------------------------------
  function fjq_ajax_val_lumpverif_doSaveEditSiapBayar()
  {				 
    	var v_kode_klaim				 			= $('#kode_klaim').val();
      var v_kode_tipe_penerima 			= $('#byr_kode_tipe_penerima').val();
			var v_kd_prg									= $('#byr_kd_prg').val();
      var v_handphone								= $('#byr_handphone_penerima').val();
      var v_email										= $('#byr_email_penerima').val();
      var v_nama_bank_penerima			= $('#byr_nama_bank_penerima').val();
      var v_kode_bank_penerima			= $('#byr_kode_bank_penerima').val();
      var v_id_bank_penerima				= $('#byr_id_bank_penerima').val();
      var v_no_rekening_penerima		= $('#byr_no_rekening_penerima').val();
      var v_nama_rekening_penerima	= $('#byr_nama_rekening_penerima_ws').val();
      var v_status_valid_rekening_penerima = $('#byr_status_valid_rekening_penerima').val();
      var v_kode_bank_pembayar			= $('#byr_kode_bank_pembayar_entry').val();
      var v_status_rekening_sentral	= $('#byr_status_rekening_sentral').val();
      var v_kantor_rekening_sentral	= $('#byr_kantor_rekening_sentral').val();
      var v_metode_transfer					= $('#byr_metode_transfer').val();
      var v_keterangan							= $('#byr_keterangan').val();
			
			var v_npwp										= $('#byr_npwp_penerima').val();
			var v_npwp_old								= $('#byr_npwp_penerima_old').val();
    	var v_kode_cara_bayar					= $('#byr_kode_cara_bayar').val();
			
      if (v_kode_klaim == '' || v_kode_tipe_penerima==''){
      	alert('Data penerima manfaat tidak ditemukan, harap perhatikan data input..!!!');
      }else if (v_kode_cara_bayar == '')
    	{
      	alert('Cara Bayar kosong, harap melengkapi data input..!!!');				
      }else if (v_kode_cara_bayar != 'V' && (v_kode_bank_penerima == '' || v_nama_bank_penerima == '' || v_no_rekening_penerima == '' || v_nama_rekening_penerima == ''))
      { //Transfer	
        if (v_nama_bank_penerima == ''){
          alert('Nama Bank penerima kosong, harap lengkapi data input..!!!');
          $('#byr_nama_bank_penerima').focus();
        }else if (v_nama_bank_penerima != '' && v_kode_bank_penerima == ''){
          alert('Kode Bank penerima kosong, harap memilih ulang Bank Penerima, jika masih gagal hubungi Administrator Kantor Pusat..!!!');
          $('#byr_nama_bank_penerima').focus();				 					 		
        }else if (v_no_rekening_penerima == ''){
          alert('No Rekening penerima kosong, harap lengkapi data input..!!!');
          $('#byr_no_rekening_penerima').focus();
        }else if (v_nama_rekening_penerima == ''){
          alert('Rekening A/N kosong, harap lengkapi data input..!!!');
          $('#byr_nama_rekening_penerima_ws').focus();
        }					 						 																				 				 
      }else if (v_kode_bank_pembayar == '')
    	{
      	alert('Bank Asal kosong, harap melengkapi data input..!!!');	
      }else if (v_npwp_old != '0' && v_npwp_old != '000000000000000' && (v_npwp == '0' || v_npwp == '000000000000000'))
    	{
      	alert('NPWP yang sebelumnya ada tidak dapat diubah menjadi tidak memiliki NPWP, harap perbaiki data input..!!!');
				$('#byr_npwp_penerima').focus();																									 				 
      }else
      {
        preload(true);
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:'fjq_ajax_val_byr_penerima_save_update',
    						v_kode_klaim				 			: v_kode_klaim,
                v_kode_tipe_penerima 			: v_kode_tipe_penerima,
                v_handphone								: v_handphone,
                v_email										: v_email,
                v_nama_bank_penerima			: v_nama_bank_penerima,
                v_kode_bank_penerima			: v_kode_bank_penerima,
                v_id_bank_penerima				: v_id_bank_penerima,
                v_no_rekening_penerima		: v_no_rekening_penerima,
                v_nama_rekening_penerima	: v_nama_rekening_penerima,
                v_status_valid_rekening_penerima : v_status_valid_rekening_penerima,
                v_kode_bank_pembayar			: v_kode_bank_pembayar,
                v_status_rekening_sentral	: v_status_rekening_sentral,
                v_kantor_rekening_sentral	: v_kantor_rekening_sentral,
                v_metode_transfer					: v_metode_transfer,
                v_keterangan							: v_keterangan,
								v_npwp										: v_npwp,
								v_kode_cara_bayar					: v_kode_cara_bayar
    			},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            if(jdata.ret=="0")
            {   
              //simpan ubah siap bayar berhasil, reload form siap bayar --------
              alert(jdata.msg);
							fl_js_lumpverif_detilSiapBayar('edit',v_kode_klaim,v_kode_tipe_penerima,v_kd_prg);													
            }else{
              //simpan ubah penerimaan manfaat gagal ---------------------------
              alert(jdata.msg);
            }
          }
        });//end ajax
      }//end if
  }
  //end do simpan perubahan data siap bayar ------------------------------------

	function fjq_ajax_val_lumpverif_doCancelEditSiapBayar()
	{
	 	var v_kode_klaim				 			= $('#kode_klaim').val();
    var v_kode_tipe_penerima 			= $('#byr_kode_tipe_penerima').val();
    var v_kd_prg									= $('#byr_kd_prg').val();
		
		fl_js_lumpverif_detilSiapBayar('edit',v_kode_klaim,v_kode_tipe_penerima,v_kd_prg);						 
	}

	//do submit data siap bayar --------------------------------------------------
  function fjq_ajax_val_lumpverif_doSubmitSiapBayar()
  {				 
    	var v_kode_klaim				 			= $('#kode_klaim').val();
      var v_kode_tipe_penerima 			= $('#byr_kode_tipe_penerima').val();
      var v_kd_prg									= $('#byr_kd_prg').val();
			var v_nama_bank_penerima			= $('#byr_nama_bank_penerima').val();
      var v_kode_bank_penerima			= $('#byr_kode_bank_penerima').val();
      var v_id_bank_penerima				= $('#byr_id_bank_penerima').val();
      var v_no_rekening_penerima		= $('#byr_no_rekening_penerima').val();
      var v_nama_rekening_penerima	= $('#byr_nama_rekening_penerima_ws').val();
      var v_status_valid_rekening_penerima = $('#byr_status_valid_rekening_penerima').val();
      var v_kode_bank_pembayar			= $('#byr_kode_bank_pembayar').val();
      var v_status_rekening_sentral	= $('#byr_status_rekening_sentral').val();
      var v_kantor_rekening_sentral	= $('#byr_kantor_rekening_sentral').val();
      var v_metode_transfer					= $('#byr_metode_transfer').val();
      var v_kantor_siapbayar 				= $('#byr_kode_kantor_pembayar').val();
			var v_kode_cara_bayar 				= $('#byr_kode_cara_bayar').val();
			var v_handphone_penerima			= $('#byr_handphone_penerima').val();
			var v_is_verified_hp					= $('#byr_is_verified_hp').val();
			var v_status_rekening_sentral	= "Y"; //pembayaran melalui sentralisasi rekening
			
      if (v_kode_klaim == '' || v_kode_tipe_penerima=='' || v_kd_prg==''){
      	alert('Data siap bayar tidak ditemukan, harap perhatikan data input..!!!');	
			}else if (v_kode_cara_bayar == '')
			{
				alert('Cara Bayar kosong, harap perbaiki data input..!!!');	 
			}else if (v_kode_cara_bayar == 'V' && (v_handphone_penerima=='' || v_is_verified_hp!='Y'))
			{
				alert('Untuk mekanisme pembayaran melalui Tunai VA Debit maka No HP penerima tidak boleh kosong dan sudah Verified, harap perbaiki data input..!!!'); 								 				 
      }else if (v_kode_cara_bayar != 'V' && (v_kode_bank_penerima=='' || v_nama_bank_penerima == '' || v_no_rekening_penerima == '' || v_nama_rekening_penerima == ''))
			{
        if (v_nama_bank_penerima == ''){
          alert('Nama Bank penerima kosong, harap lengkapi data input..!!!');
        }else if (v_nama_bank_penerima != '' && v_kode_bank_penerima == ''){
          alert('Kode Bank penerima kosong, harap memilih ulang Bank Penerima, jika masih gagal hubungi Administrator Kantor Pusat..!!!');				 					 		
        }else if (v_no_rekening_penerima == ''){
          alert('No Rekening penerima kosong, harap lengkapi data input..!!!');
        }else if (v_nama_rekening_penerima == ''){
          alert('Rekening A/N kosong, harap lengkapi data input..!!!');
        }	
      }else if (v_kode_bank_pembayar == '')
    	{
      	alert('Bank Asal kosong, harap melengkapi data input..!!!');	
      }else if (v_status_rekening_sentral == 'Y' && v_kode_cara_bayar == 'B' && v_status_valid_rekening_penerima!='Y')
    	{
      	alert('Untuk transfer pembayaran melalui rekening sentralisasi maka rekening penerima harus valid, harap perbaiki data input..!!!');
		  }else
      {
        preload(true);
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:'fjq_ajax_val_byr_doSiapTransfer',
    						v_kode_klaim				 			: v_kode_klaim,
                v_kode_tipe_penerima 			: v_kode_tipe_penerima,
                v_kd_prg									: v_kd_prg,
								v_kantor_siapbayar				: v_kantor_siapbayar
    			},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            if(jdata.ret=="0")
            {   
              //simpan ubah penerimaan manfaat berhasil, reload form penerima mnf --
              alert(jdata.msg);
							fl_js_lumpverif_reloadFormUtama();														
            }else{
              //simpan ubah penerimaan manfaat gagal ---------------------------
              alert(jdata.msg);
            }
          }
        });//end ajax
      }//end if
  }
  //end do submit data siap bayar ----------------------------------------------

	//call view detil pembayaran dari menu verifikasi siap bayar utk -------------
	//tipe penerima yg sudah dibayar dan ada yg blm dibayar ----------------------
	function fl_js_lumpverif_detilViewBayar(p_kode_klaim, p_kode_pembayaran)
	{
		var c_mid = '<?=$mid;?>'
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_pembayaran_detil.php?kode_klaim='+p_kode_klaim+'&kode_pembayaran='+p_kode_pembayaran+'&mid='+c_mid+'','',1050,640,'yes'); 
	}
	//end call view detil pembayaran dari menu verifikasi siap bayar utk ---------
		
	//---------------------- DISPLAY SUKSES PEMBAYARAN ---------------------------
  function fl_js_lumpverif_viewSuksesBayar(v_mode_trx, v_kode_klaim, v_kode_pembayaran)
  {				
    var fn;
    window.document.getElementById("span_list_byr").style.display = 'none';
    window.document.getElementById("span_byr_rinci").style.display = 'block';
    
    var v_no_penetapan 				= $('#no_penetapan').val();
    var v_kode_kantor	 	 			= $('#kode_kantor').val();						
    var v_nama_tipe_klaim			= $('#nama_tipe_klaim').val();
    var v_keyword_sebab_klaim	= $('#keyword_sebab_klaim').val();	
    var v_nama_sebab_klaim		= $('#nama_sebab_klaim').val();
    var v_kpj									= $('#kpj').val();
    var v_nama_tk							= $('#nama_tk').val();
    var v_nomor_identitas			= $('#nomor_identitas').val();
    var v_no_proyek						= $('#no_proyek').val();
    var v_nama_proyek					= $('#nama_proyek').val();
    var v_nama_pelaksana_kegiatan = $('#nama_pelaksana_kegiatan').val();
    var v_nama_kegiatan				= $('#nama_kegiatan').val();		
    var v_npp									= $('#npp').val();
    var v_nama_perusahaan			= $('#nama_perusahaan').val();
		
    if (v_kode_klaim == '' || v_kode_pembayaran == '') {
    	return alert('Kode Klaim/kode pembayaran tidak boleh kosong');
    }
					
    asyncPreload(true);
    $.ajax({
      type: 'POST',
      url: "../ajax/pn5063_action.php?"+Math.random(),
      data: {
        tipe: 'fjq_ajax_val_getdatapembayaran',
        v_kode_klaim: v_kode_klaim,
        v_kode_pembayaran:v_kode_pembayaran
      },
      success: function(data){
        try {
          jdata = JSON.parse(data);
          if (jdata.ret == 0) 
          {
            //generate layout data pembayaran ----------------------------------
						var v_kode_cara_bayar = getValue(jdata.dataByr.DATA['KODE_CARA_BAYAR']);
						var v_nama_cara_bayar = getValue(jdata.dataByr.DATA['NAMA_CARA_BAYAR']);
						
						var v_kode_bank_pembayar = getValue(jdata.dataByr.DATA['KODE_BANK']);
						var v_nama_bank_pembayar = getValue(jdata.dataByr.DATA['NAMA_BANK'])+' - '+getValue(jdata.dataByr.DATA['KODE_BUKU']);
						
        		var html_input  = '';
            html_input += '<div class="div-row" >';
            html_input += '  <div class="div-col" style="width:49%; max-height: 100%;">';
            html_input += '      <fieldset style="min-height:380px;"><legend>Informasi Penetapan Klaim Nomor '+v_no_penetapan+' Kacab '+v_kode_kantor+'</legend>';		
            html_input += '				</br>';
            html_input += '				<div class="form-row_kiri">';
            html_input += '        <label  style = "text-align:right;">Jenis Klaim &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '          <input type="text" id="byr_nama_tipe_klaim" name="byr_nama_tipe_klaim" value="'+v_nama_tipe_klaim+'" style="width:40px;" readonly class="disabled">';
            html_input += '    			<input type="text" id="byr_keyword_sebab_klaim" name="byr_keyword_sebab_klaim" value="'+v_keyword_sebab_klaim+'" style="width:30px;" readonly class="disabled">';
            html_input += '					<input type="text" id="byr_nama_sebab_klaim" name="byr_nama_sebab_klaim" value="'+v_nama_sebab_klaim+'" style="width:182px;" readonly class="disabled">';
            html_input += '      	</div>';
            html_input += '				<div class="clear"></div>';		
						
            html_input += '		    <span id="byr_span_kpj" style="display:block;">';						
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">No Referensi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>';
            html_input += '		        <input type="text" id="byr_kpj" name="byr_kpj" value="'+v_kpj+'" maxlength="30" style="width:80px;" readonly class="disabled">';
            html_input += '		        <input type="text" id="byr_nama_tk" name="byr_nama_tk" value="'+v_nama_tk+'" style="width:180px;" readonly class="disabled">';
        		html_input += '		        <input type="hidden" id="byr_nomor_identitas" name="byr_nomor_identitas" value="'+v_nomor_identitas+'" style="width:250px;" readonly class="disabled">';
            html_input += '		      </div>';																																								
            html_input += '		      <div class="clear"></div>';							
            html_input += '		    </span>';
						
            html_input += '		    <span id="byr_span_proyek" style="display:none;">';						
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">No Proyek &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>';
            html_input += '		        <input type="text" id="byr_no_proyek" name="byr_no_proyek" value="'+v_no_proyek+'" style="width:120px;" readonly class="disabled">';
            html_input += '		        <input type="text" id="byr_nama_proyek" name="byr_nama_proyek" value="'+v_nama_proyek+'" style="width:130px;" readonly class="disabled">';		
            html_input += '		      </div>';																																										
            html_input += '		      <div class="clear"></div>';						
            html_input += '		    </span>';
							
            html_input += '		    <span id="byr_span_kegiatan_tambahan" style="display:none;">';						
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">Pelaksana Kegiatan &nbsp;</label>';
            html_input += '		        <input type="text" id="byr_nama_pelaksana_kegiatan" name="byr_nama_pelaksana_kegiatan" value="'+v_nama_pelaksana_kegiatan+'" style="width:250px;" class="disabled">';
            html_input += '		      </div>';																																									
            html_input += '		      <div class="clear"></div>';	
            html_input += '		      <div class="form-row_kiri">';
            html_input += '		      <label  style = "text-align:right;">Kegiatan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>';
            html_input += '		        <input type="text" id="byr_nama_kegiatan" name="byr_nama_kegiatan" value="'+v_nama_kegiatan+'" style="width:250px;" readonly class="disabled">';			
            html_input += '		      </div>';																																									
            html_input += '		      <div class="clear"></div>';
            html_input += '		    </span>';	
						
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">NPP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; </label>';
            html_input += '		      <input type="text" id="byr_npp" name="byr_npp" value="'+v_npp+'" style="width:60px;" readonly class="disabled">';
            html_input += '		      <input type="text" id="byr_nama_perusahaan" name="byr_nama_perusahaan" value="'+v_nama_perusahaan+'" style="width:160px;" readonly class="disabled">';				
            html_input += '		    </div>';																										
            html_input += '		    <div class="clear"></div>';
						
        		html_input += '				</br>';		
						
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Nominal Gross </label>';
            html_input += '		      <input type="text" id="byr_nom_manfaat_gross" name="byr_nom_manfaat_gross" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_MANFAAT_GROSS']))+'" maxlength="20" style="text-align:left;width:250px;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																																																		
            html_input += '		    <div class="clear"></div>';

        		if (getValue(jdata.dataByr.DATA['KLM_KODE_POINTER_ASAL'])=="PROMOTIF")
        		{
              html_input += '		    <div class="form-row_kiri">';
              html_input += '		    <label  style = "text-align:right;">PPN </label>';
              html_input += '		     <input type="text" id="byr_nom_ppn" name="byr_nom_ppn" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_PPN']))+'" style="width:150px;" maxlength="20" onblur="this.value=format_uang(this.value);" readonly class="disabled">';
          		html_input += '				 <input type="text" id="byr_kode_pajak_ppn" name="byr_kode_pajak_ppn" value="'+getValue(jdata.dataByr.DATA['KODE_PAJAK_PPN'])+'" style="width:70px;" readonly class="disabled">';				
              html_input += '		    </div>';																		
              html_input += '		    <div class="clear"></div>';		
        		}else
        		{
              html_input += '		     <input type="hidden" id="byr_nom_ppn" name="byr_nom_ppn" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_PPN']))+'"  maxlength="20" onblur="this.value=format_uang(this.value);" readonly class="disabled">';
          		html_input += '				 <input type="hidden" id="byr_kode_pajak_ppn" name="byr_kode_pajak_ppn" value="'+getValue(jdata.dataByr.DATA['KODE_PAJAK_PPN'])+'"  readonly class="disabled">';					
        		}
        				        
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">PPh </label>';
            html_input += '		      <input type="text" id="byr_nom_pph" name="byr_nom_pph" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_PPH']))+'" style="width:150px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';
        		html_input += '					<input type="text" id="byr_kode_pajak_pph" name="byr_kode_pajak_pph" value="'+getValue(jdata.dataByr.DATA['KODE_PAJAK_PPH'])+'" style="width:70px;" readonly class="disabled" >';
        		html_input += '				</div>';																		
            html_input += '		    <div class="clear"></div>';
  
        		html_input += '				<div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Pembulatan </label>';
            html_input += '		      <input type="text" id="byr_nom_pembulatan" name="byr_nom_pembulatan" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_PEMBULATAN']))+'" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
        
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Netto </label>';
            html_input += '		      <input type="text" id="byr_nom_netto" name="byr_nom_netto" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_MANFAAT_NETTO']))+'" style="width:230px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
                
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label style = "text-align:right;">Program &nbsp;</label>';
            html_input += '		      <input type="hidden" id="byr_kd_prg" name="byr_kd_prg" value="'+getValue(jdata.dataByr.DATA['KD_PRG'])+'">';
        		html_input += '					<input type="text" id="byr_nm_prg" name="byr_nm_prg" value="'+getValue(jdata.dataByr.DATA['NM_PRG'])+'" style="width:200px;" readonly class="disabled" >';                					
            html_input += '		    </div>';																																																			
            html_input += '		    <div class="clear"></div>';
        		html_input += '		    </br>';
        		
            html_input += '       <div class="form-row_kiri" >';
            html_input += '       <label style = "text-align:right;">Penerima Manfaat</label>';
            html_input += '       	<input type="text" id="byr_nama_tipe_penerima" name="byr_nama_tipe_penerima" value="'+getValue(jdata.dataByr.DATA['NAMA_TIPE_PENERIMA'])+'" style="width:250px;" readonly class="disabled" >';  
            html_input += '         <input type="hidden" id="byr_kode_tipe_penerima" name="byr_kode_tipe_penerima" value="'+getValue(jdata.dataByr.DATA['KODE_TIPE_PENERIMA'])+'">';
            html_input += '         </div>';																																																	
            html_input += '       <div class="clear"></div>';
                          
            html_input += '       <div class="form-row_kiri">';
            html_input += '       <label style = "text-align:right;">Nama Penerima</label>';
            html_input += '         <input type="text" id="byr_nama_penerima" name="byr_nama_penerima" value="'+getValue(jdata.dataByr.DATA['NAMA_PENERIMA'])+'" style="width:250px;" readonly class="disabled">';
            html_input += '         <input type="hidden" id="byr_nik_penerima" name="byr_nik_penerima" value="'+getValue(jdata.dataByr.DATA['NIK_PENERIMA'])+'" style="width:210px;" readonly class="disabled">';
            html_input += '       </div>';																																																																																														
            html_input += '       <div class="clear"></div>';
                          
            html_input += '       <div class="form-row_kiri">';
            html_input += '       <label style = "text-align:right;">NPWP</label>';	    				
            html_input += '       	<input type="text" id="byr_npwp_penerima" name="byr_npwp_penerima" value="'+getValue(jdata.dataByr.DATA['NPWP_PENERIMA'])+'" style="width:250px;" readonly class="disabled" onblur="fl_js_val_npwp(\'byr_npwp_penerima\');">';
        		html_input += '       	<input type="hidden" id="byr_npwp_penerima_old" name="byr_npwp_penerima_old" value="'+getValue(jdata.dataByr.DATA['NPWP_PENERIMA'])+'">';
            html_input += '       </div>';																																	
            html_input += '       <div class="clear"></div>';
                            
            html_input += '       <div class="form-row_kiri">';
            html_input += '       <label style = "text-align:right;">Email</label>';	    				
            html_input += '       	<input type="text" id="byr_email_penerima" name="byr_email_penerima" value="'+getValue(jdata.dataByr.DATA['EMAIL_PENERIMA'])+'" style="width:230px;" onblur="this.value=this.value.toLowerCase();fl_js_val_email(\'byr_email_penerima\');" readonly class="disabled">';
            html_input += '       </div>';																																	
            html_input += '       <div class="clear"></div>';
                          
            html_input += '       <div class="form-row_kiri">';
            html_input += '       <label style = "text-align:right;">Handphone</label>';	    				
            html_input += '        	<input type="text" id="byr_handphone_penerima" name="byr_handphone_penerima" value="'+getValue(jdata.dataByr.DATA['HANDPHONE_PENERIMA'])+'" style="width:200px;" readonly class="disabled">';
            html_input += '  			</div>';																																	
            html_input += '       <div class="clear"></div>';
        								        						
            html_input += '		  </fieldset>';		
          	html_input += '  </div>';

          	html_input += '  <div class="div-col" style="width:1%;">';
            html_input += '  </div>';
        		
          	html_input += '  <div class="div-col-right" style="width:50%;">';
            html_input += '    <div class="div-row">';
          	html_input += '  		<div class="div-col" style="width: 100%">';
          	html_input += '  			<fieldset><legend>Dibayarkan ke</legend>';
            html_input += '         <div class="div-row">';
            html_input += '           <div class="div-col" style="width: 120px;text-align:left;">';
            html_input += '           	<input id="byr_nik_penerima_foto" name="byr_nik_penerima_foto" type="image" align="center" src="../../images/nopic.png" style="height: 90px !important; width: 85px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>';
            html_input += '           </div>';
            html_input += '           <div class="div-col">';
            html_input += '          		<div class="form-row_kiri">';
            html_input += '          		<label style = "text-align:left;width:70px;">Cara Bayar</label>';
            html_input += '            	 <input type="text" id="byr_nama_cara_bayar" name="byr_nama_cara_bayar" value="'+v_nama_cara_bayar+'" readonly class="disabled" style="width:278px;">';
            html_input += '            	 <input type="hidden" id="byr_kode_cara_bayar" name="byr_kode_cara_bayar" value="'+v_kode_cara_bayar+'" readonly class="disabled" style="width:250px;">';
            html_input += '          		</div>';		    																																				
            html_input += '          		<div class="clear"></div>';
        
          	html_input += '   					<span id="byr_span_va_debit" style="display:none;">';					
            html_input += '           		<div class="form-row_kiri">';
            html_input += '           		<label style = "text-align:left;width:70px;">&nbsp;</label>'; 	    				
            html_input += '             		<i><font color="#ff0000">No.VA dikirimkan ke No.HP '+getValue(jdata.dataByr.DATA['HANDPHONE_PENERIMA'])+'</font></i>';
            html_input += '           		</div>';																																																																																																																																																												
            html_input += '           		<div class="clear"></div>';
            html_input += '         		</span>';

          	html_input += '   			<span id="byr_span_ln" style="display:none;">';						
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">Bank</label>'; 
						html_input += '             <input type="text" id="byr_ln_kode_bank_penerima" name="byr_ln_kode_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PENERIMA'])+'" maxlength="20" readonly style="width:80px;background-color:#F5F5F5" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="text" id="byr_ln_nama_bank_penerima" name="byr_ln_nama_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['BANK_PENERIMA'])+'" maxlength="100" readonly style="width:240px;background-color:#F5F5F5" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="hidden" id="byr_ln_id_bank_penerima" name="byr_ln_id_bank_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['ID_BANK_PENERIMA'])+'"style="width:100px;">';
            html_input += '             <input type="hidden" id="byr_ln_metode_transfer" name="byr_ln_metode_transfer" value="'+getValue(jdata.dataDtlSiapByr.DATA['METODE_TRANSFER'])+'" maxlength="4" readonly class="disabled" style="width:20px;">';
            html_input += '             <input type="hidden" id="byr_ln_kode_bank_penerima_old" name="byr_ln_kode_bank_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['KODE_BANK_PENERIMA'])+'">';
            html_input += '           </div>';																																																	
            html_input += '           <div class="clear"></div>';                   
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">No Rekening</label>';
            html_input += '             <input type="text" id="byr_ln_no_rekening_penerima" name="byr_ln_no_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NO_REKENING_PENERIMA'])+'" tabindex="22" onkeydown="if(event.key==='+"'.'"+' || event.key==='+"'e'"+' ){event.preventDefault();}" onpaste="let pasteData = event.clipboardData.getData('+"'text'"+'); if(pasteData){pasteData.replace(/[^0-9]*/g,'+"''"+');} " maxlength="30" readonly class="disabled" style="width:120px;background-color:#F5F5F5" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="hidden" id="byr_ln_nama_rekening_penerima" name="byr_ln_nama_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" readonly class="disabled" style="width:180px;background-color:#F5F5F5">';
            html_input += '             <input type="text" id="byr_ln_nama_rekening_penerima_ws" name="byr_ln_nama_rekening_penerima_ws" value="'+getValue(jdata.dataDtlSiapByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" style="width:180px;background-color:#F5F5F5" tabindex="23" readonly class="disabled" onblur="this.value=this.value.toUpperCase();fl_js_lumpverif_sync_norek_ln();">';
            html_input += '             <input type="checkbox" id="cb_byr_ln_valid_rekening" name="cb_byr_ln_valid_rekening" disabled class="cebox" '+(getValue(jdata.dataDtlSiapByr.DATA['STATUS_VALID_REKENING_PENERIMA'])=='Y' ? 'checked' : '')+'><i><font color="#009999">Valid</font></i>';	
            html_input += '             <input type="hidden" id="byr_ln_status_valid_rekening_penerima" name="byr_ln_status_valid_rekening_penerima" value="'+getValue(jdata.dataDtlSiapByr.DATA['STATUS_VALID_REKENING_PENERIMA'])+'">';
        		html_input += '             <input type="hidden" id="byr_ln_no_rekening_penerima_old" name="byr_ln_no_rekening_penerima_old" value="'+getValue(jdata.dataDtlSiapByr.DATA['NO_REKENING_PENERIMA'])+'">';
						html_input += '           </div>';																																																																																															
            html_input += '           <div class="clear"></div>'; 
            html_input += '           </br>'; 						                
            html_input += '           <div class="form-row_kiri">';
            html_input += '           <label style = "text-align:right;">'; 	    				
            html_input += '             <img src="../../images/warning.gif" border="0" alt="Tambah" align="top" style="height:12px;"/></label>'; 
            html_input += '             <i><font color="#ff0000">Kode SWIFT dapat diakses melalui www.transfez.com/swift-codes</font></i>';
            html_input += '           </div>';																																																																																																																																																												
            html_input += '           <div class="clear"></div>';
            html_input += '         </span>';
						        	
          	html_input += '   					<span id="byr_span_rekening" style="display:none;">';						
            html_input += '           		<div class="form-row_kiri">';
            html_input += '           		<label style = "text-align:left;width:70px;">Bank</label>'; 
            html_input += '             		<input type="text" id="byr_nama_bank_penerima" name="byr_nama_bank_penerima" value="'+getValue(jdata.dataByr.DATA['BANK_PENERIMA'])+'" readonly style="width:250px;background-color:#F5F5F5">';
            html_input += '             		<input type="hidden" id="byr_kode_bank_penerima" name="byr_kode_bank_penerima" value="'+getValue(jdata.dataByr.DATA['KODE_BANK_PENERIMA'])+'"style="width:100px;">';
            html_input += '             		<input type="hidden" id="byr_id_bank_penerima" name="byr_id_bank_penerima" value="'+getValue(jdata.dataByr.DATA['ID_BANK_PENERIMA'])+'"style="width:100px;">';
            html_input += '             		<input type="hidden" id="byr_metode_transfer" name="byr_metode_transfer" value="'+getValue(jdata.dataByr.DATA['METODE_TRANSFER'])+'" maxlength="4" readonly class="disabled" style="width:20px;">';
            html_input += '             		<a style="display:none;" id="btn_lov_byr_bank_penerima" href="#">';							
            html_input += '             		<img src="../../images/help.png" alt="Cari Bank" border="0" style="height:19px;" align="absmiddle"></a>';
        		html_input += '             		<input type="hidden" id="byr_kode_bank_penerima_old" name="byr_kode_bank_penerima_old" value="'+getValue(jdata.dataByr.DATA['KODE_BANK_PENERIMA'])+'">';
            html_input += '           		</div>';																																																	
            html_input += '           		<div class="clear"></div>';
                    
            html_input += '           		<div class="form-row_kiri">';
            html_input += '           		<label style = "text-align:left;width:70px;">No Rekening</label>';
            html_input += '             		<input type="text" id="byr_no_rekening_penerima" name="byr_no_rekening_penerima" value="'+getValue(jdata.dataByr.DATA['NO_REKENING_PENERIMA'])+'" onkeydown="if(event.key==='+"'.'"+' || event.key==='+"'e'"+' ){event.preventDefault();}" onpaste="let pasteData = event.clipboardData.getData('+"'text'"+'); if(pasteData){pasteData.replace(/[^0-9]*/g,'+"''"+');} " tabindex="22" maxlength="30" readonly class="disabled" style="width:100px;background-color:#F5F5F5">';
            html_input += '             		<input type="hidden" id="byr_nama_rekening_penerima" name="byr_nama_rekening_penerima" value="'+getValue(jdata.dataByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" readonly class="disabled" style="width:100px;background-color:#F5F5F5">';
            html_input += '             		<input type="text" id="byr_nama_rekening_penerima_ws" name="byr_nama_rekening_penerima_ws" value="'+getValue(jdata.dataByr.DATA['NAMA_REKENING_PENERIMA'])+'" maxlength="100" style="width:120px;background-color:#F5F5F5" tabindex="23" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">';
            html_input += '             		<input type="checkbox" id="cb_byr_valid_rekening" name="cb_byr_valid_rekening" disabled class="cebox" '+(getValue(jdata.dataByr.DATA['STATUS_VALID_REKENING_PENERIMA'])=='Y' ? 'checked' : '')+'><i><font color="#009999">Valid</font></i>';	
            html_input += '             		<input type="hidden" id="byr_status_valid_rekening_penerima" name="byr_status_valid_rekening_penerima" value="'+getValue(jdata.dataByr.DATA['STATUS_VALID_REKENING_PENERIMA'])+'">';
        		html_input += '             		<input type="hidden" id="byr_no_rekening_penerima_old" name="byr_no_rekening_penerima_old" value="'+getValue(jdata.dataByr.DATA['NO_REKENING_PENERIMA'])+'">';
        		html_input += '           		</div>';																																																																																															
            html_input += '           		<div class="clear"></div>';      
            html_input += '         		</span>';
        							
            html_input += '           </div>';
            html_input += '         </div>';
          	html_input += '  			</fieldset>';		
          	html_input += '  		</div>';	 
          	html_input += '  	 </div>';
        
        		html_input += '   <div class="div-row">';
        		html_input += '   	<div class="div-col" style="width: 100%">';
            html_input += '       <fieldset style="height:85px;"><legend><i><font color="#009999">Dari :</font></i></legend>';
        		html_input += '         </br>';
        		html_input += '         <div class="form-row_kiri">';
            html_input += '         <label style = "text-align:right;">Bank &nbsp;&nbsp;</label>';
        		html_input += '           <input type="text" id="byr_nama_bank_pembayar" name="byr_nama_bank_pembayar" value="'+v_nama_bank_pembayar+'" readonly class="disabled" style="width:230px;">';
        		html_input += '         	<input type="hidden" id="byr_kode_bank_pembayar" name="byr_kode_bank_pembayar" value="'+v_kode_bank_pembayar+'" readonly class="disabled" style="width:260px;">';
        		html_input += '         </div>';																																												
            html_input += '         <div class="clear"></div>';
        		html_input += '         <div class="form-row_kiri">';
            html_input += '         <label style = "text-align:right;">&nbsp;</label>';
        		html_input += '         	<input type="checkbox" id="cb_byr_status_rekening_sentral" name="cb_byr_status_rekening_sentral" disabled class="cebox" '+(getValue(jdata.dataByr.DATA['STATUS_REKENING_SENTRAL'])=='Y' ? 'checked' : '')+'><i><font color="#009999">Sentralisasi Rekening</font></i>';		
            html_input += '         	<input type="hidden" id="byr_kode_buku" name="byr_kode_buku" value="'+getValue(jdata.dataByr.DATA['KODE_BUKU'])+'">';
        		html_input += '         	<input type="hidden" id="byr_klm_kode_pointer_asal" name="byr_klm_kode_pointer_asal" value="'+getValue(jdata.dataByr.DATA['KLM_KODE_POINTER_ASAL'])+'" style="width:30px;" readonly class="disabled">';    								
            html_input += '         	<input type="hidden" id="byr_status_rekening_sentral" name="byr_status_rekening_sentral" value="'+getValue(jdata.dataByr.DATA['STATUS_REKENING_SENTRAL'])+'">';
            html_input += '         	<input type="hidden" id="byr_kantor_rekening_sentral" name="byr_kantor_rekening_sentral" value="'+getValue(jdata.dataByr.DATA['KANTOR_REKENING_SENTRAL'])+'">';
        		html_input += '         </div>';																																												
            html_input += '         <div class="clear"></div>';			
            html_input += '       </fieldset>';				
        		html_input += '   	</div>';		 
        		html_input += '   </div>';
        		
        		html_input += '   <div class="div-row">';
        		html_input += '   	<div class="div-col" style="width: 100%">';
            html_input += '       <fieldset style="height:148px;"><legend><i><font color="#009999">Informasi Pembayaran :</font></i></legend>';
        		html_input += '         </br>';
        
            html_input += '         <div class="form-row_kiri">';
            html_input += '         <label style = "text-align:right;">Dibayarkan di &nbsp;&nbsp;</label>';
        		html_input += '         <input type="text" id="byr_kode_kantor_pembayar" name="byr_kode_kantor_pembayar" value="'+getValue(jdata.dataByr.DATA['KODE_KANTOR_PEMBAYAR'])+'" style="width:30px;" readonly class="disabled">';
        		html_input += '         <input type="text" id="byr_nama_kantor_pembayar" name="byr_nama_kantor_pembayar" value="'+getValue(jdata.dataByr.DATA['NAMA_KANTOR_PEMBAYAR'])+'" style="width:195px;" readonly class="disabled">';
        		html_input += '         </div>';																																												
            html_input += '         <div class="clear"></div>';	
        
            html_input += '         <div class="form-row_kiri">';
            html_input += '         <label style = "text-align:right;">Tanggal &nbsp;&nbsp;</label>';
        		html_input += '         <input type="text" id="byr_tgl_pembayaran" name="byr_tgl_pembayaran" value="'+getValue(jdata.dataByr.DATA['TGL_PEMBAYARAN'])+'" style="width:235px;" readonly class="disabled">';
        		html_input += '         </div>';																																												
            html_input += '         <div class="clear"></div>';
        
            html_input += '		    <div class="form-row_kiri">';
            html_input += '		    <label  style = "text-align:right;">Jumlah Dibayarkan </label>';
            html_input += '		      <input type="text" id="byr_nom_pembayaran" name="byr_nom_pembayaran" value="'+format_uang(getValue(jdata.dataByr.DATA['NOM_SUDAH_BAYAR']))+'" style="width:235px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">';				
            html_input += '		    </div>';																		
            html_input += '		    <div class="clear"></div>';
        								
            html_input += '         <div class="form-row_kiri">';
            html_input += '         <label style = "text-align:right;">Kode Pembayaran &nbsp;&nbsp;</label>';
        		html_input += '         <input type="text" id="byr_kode_pembayaran" name="byr_kode_pembayaran" value="'+getValue(jdata.dataByr.DATA['KODE_PEMBAYARAN'])+'"  style="width:200px;" readonly class="disabled">';
        		html_input += '         </div>';																																												
            html_input += '         <div class="clear"></div>';
        				
            html_input += '       </fieldset>';				
        		html_input += '   	</div>';		 
        		html_input += '   </div>';
     		
        		html_input += '  </div>';	
        		html_input += '</div>';								
        											
            if (html_input !="")
            {
             	$('#formbyrrinci').html(html_input); 
            }
        		
        		if (getValue(jdata.dataByr.DATA['NIK_PENERIMA'])!='')
        		{
        		 	$('#byr_nik_penerima_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + getValue(jdata.dataByr.DATA['NIK_PENERIMA']));
        		}
        		
        		fl_js_lumpverif_span_byr_carabayar();            
						fl_js_lumpverif_byr_span_kpj();
					
            window.document.getElementById("span_button_utama").style.display = 'none';
						window.document.getElementById("span_button_viewbyr").style.display = 'block';
						window.document.getElementById("span_button_cetak_viewbyr").style.display = 'block';
						
						var html_btncetak="";						
						html_btncetak += '<div class="div-col">';
  					html_btncetak += '	<div class="div-action-footer">';
  					html_btncetak += '		<div class="icon">';
  					html_btncetak += '			<a id="btn_doCetakViewByr" href="#" onClick="fl_js_show_lov_cetakbyr(\''+getValue(jdata.dataByr.DATA['KODE_KLAIM'])+'\', \''+getValue(jdata.dataByr.DATA['KODE_PEMBAYARAN'])+'\');">';
  					html_btncetak += '				<img src="../../images/ico_cetak.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>';
  					html_btncetak += '				<span>CETAK &nbsp;&nbsp;&nbsp;&nbsp;</span>';
  					html_btncetak += '			</a>';
  					html_btncetak += '		</div>';
  					html_btncetak += '	</div>';
  					html_btncetak += '</div>';
						
						$("#div_button_cetak_viewbyr").html(html_btncetak);
						
            //end generate layout data pembayaran ------------------------------
            if (fn && fn.success) {
            	fn.success();
            }
      		} else {
          	alert(jdata.msg);
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
    //---------------------- END DISPLAY SUKSES PEMBAYARAN ---------------------   				 
  }													
</script>

<script>
  function confirm_edit() {
    //if(confirm('Apakah anda yakin akan melakukan Edit Data Siap Bayar..?')) fjq_ajax_val_lumpverif_doEditSiapBayar();
    let content = 'Apakah Anda yakin akan melakukan Edit Data Siap Bayar..?'
		window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
			if (btn=='yes') {
        fjq_ajax_val_lumpverif_doEditSiapBayar()
			}
		});
  }

  function confirm_saveedit() {
    //if(confirm('Apakah anda yakin akan menyimpan perubahan Data Siap Bayar..?')) fjq_ajax_val_lumpverif_doSaveEditSiapBayar();
    let content = 'Apakah anda yakin akan menyimpan perubahan Data Siap Bayar..?<br><br>'
              + 'Penerima manfaat: ' + $('#byr_nama_penerima').val() + ' [' + $('#byr_similarity_nama_penerima').val() + '%]<br>'
              + 'Atas nama rekening: ' + $('#byr_nama_rekening_penerima_ws').val() + ' - ' + $('#byr_nama_bank_penerima').val()
		window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
			if (btn=='yes') {
        fjq_ajax_val_lumpverif_doSaveEditSiapBayar()
			}
		});
  }
  function confirm_cancel() {
    //if(confirm('Apakah anda yakin akan membatalkan perubahan Data Siap Bayar..?')) fjq_ajax_val_lumpverif_doCancelEditSiapBayar();
    let content = 'Apakah Anda yakin akan membatalkan perubahan Data Siap Bayar..?'
		window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
			if (btn=='yes') {
				fjq_ajax_val_lumpverif_doCancelEditSiapBayar()
			}
		});
  }
  function confirm_submit() {
    //"if(confirm('Apakah anda yakin akan melakukan Submit Data Siap Bayar..?')) ;
    let content = 'Apakah Anda yakin akan melakukan Submit Data Siap Bayar?<br><br>'
                + 'Penerima manfaat: ' + $('#byr_nama_penerima').val() + ' [' + $('#byr_similarity_nama_penerima').val() + '%]<br>'
                + 'Atas nama rekening: ' + $('#byr_nama_rekening_penerima_ws').val() + ' - ' + $('#byr_nama_bank_penerima').val()
		window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
			if (btn=='yes') {
				fjq_ajax_val_lumpverif_doSubmitSiapBayar()
			}
		});
	}
</script>
