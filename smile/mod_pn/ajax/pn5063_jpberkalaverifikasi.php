<?
include "../../mod_sc/sc_session.php";
include "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/* =============================================================================
Ket : Form ini digunakan untuk verifikasi data jp berkala siap bayar
Hist: - 01/09/2020 : Pembuatan Form (Tim SMILE)							 						 
------------------------------------------------------------------------------*/
$ls_kode_klaim 		= $_POST["KODE_KLAIM"] 		=="null" ? "" : $_POST["KODE_KLAIM"];
$ln_no_konfirmasi = $_POST["NO_KONFIRMASI"] =="null" ? "" : $_POST["NO_KONFIRMASI"];
$ln_no_proses 		= $_POST["NO_PROSES"] 		=="null" ? "" : $_POST["NO_PROSES"];
$ls_kd_prg 				= $_POST["KD_PRG"] 				=="null" ? "" : $_POST["KD_PRG"];
$task 				 		= $_POST["TASK"] 					=="null" ? "" : $_POST["TASK"];
$ls_blthjt		 		= $_POST["BLTHJT"] 				=="null" ? "" : $_POST["BLTHJT"];
$gs_kantor_aktif	= $_SESSION['kdkantorrole'];

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
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <a class="a-icon-text" href="#" onclick="fl_js_jpbklverif_filter();" title="Klik Untuk Menampilkan Data">
            <img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
          </a>
					&nbsp;&nbsp;|&nbsp;&nbsp;
          <a class="a-icon-text" href="#" onclick="fl_js_jpbklverif_cetakSiapBayar();" title="Klik Untuk Mencetak Laporan Siap Bayar">
            <img src="../../images/printer.png" border="0" alt="cetak" align="absmiddle">
          </a>					
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="fl_js_jpbklverif_search_by2_changed();">
            <option value="">Keyword Lain</option>
            <option value="">----------------</option>
          </select>
        </div>				
        <div class="div-col-right" style="padding: 2px;">
        	<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
        </div>
        <div class="div-col-right" style="padding: 2px;">
          <select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="fl_js_jpbklverif_search_by_changed()">
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
              <th style="text-align: center; width: 20px;!important;">Action</th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Kode Klaim
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="BLTH_PROSES" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Bulan
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Ktr
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="KODE_KANTOR_KONFIRMASI" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Ktr Konf
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>											
              <th style="text-align: left;">
                <a href="#" order_by="KPJ" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">No. Referensi
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_TK" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Nama Peserta
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NAMA_PENERIMA_BERKALA" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Nama Penerima
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="NO_REKENING_PENERIMA" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Rekening
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="BANK_PENERIMA" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Bank
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: left;">
                <a href="#" order_by="STATUS_VALID_REKENING_PENERIMA" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Rek. Valid
                	<img class="order-icon" src="../../images/sort_both.png">
                </a>
              </th>
              <th style="text-align: right;">
                <a href="#" order_by="NOM_MANFAAT_NETTO" order_type="DESC" onclick="fl_js_jpbklverif_orderby(this)">Manfaat
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
          <a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="fl_js_jpbklverif_filter('-02')"><<</a>
          <a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="fl_js_jpbklverif_filter('-01')">Prev</a>
          <input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="fl_js_jpbklverif_filter(this.value);"/>
          <a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="fl_js_jpbklverif_filter('01')">Next</a>
          <a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="fl_js_jpbklverif_filter('02')">>></a>
          <span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
          <input type="hidden" id="pages">
        </div>
        <div class="div-col-right">
          <input type="hidden" id="hdn_total_records">
          <span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
          &nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
          <span style="vertical-align: middle;" >Tampilkan</span>
          <select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="fl_js_jpbklverif_filter();">
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
		
    <ul id="nav" style="width:99%;">					
      <li><a href="#tab1" id="tabid1" style="display:block"><span style="vertical-align: middle;" id="span_judul_tab1">Informasi Penetapan Manfaat JP Berkala</span></a></li>	
      <li><a href="#tab2" id="tabid2" style="display:block"><span style="vertical-align: middle;" id="span_judul_tab2">Verifikasi Data Siap Bayar</span></a></li>	
    </ul>
		
		<div style="display: none;" id="tab1" class="tab_konten">
			<div id="konten" style="width:98%;">
				<div class="div-row" >
					<div class="div-col" style="width:45%; max-height: 100%;">
						<div class="div-row">
							<div class="div-col" style="width: 100%">
								<!-- Informasi Penerima Manfaat Selanjutnya ---------------->	 
                <fieldset><legend><span style="vertical-align: middle;" id="span_fieldset_curr_penerima"></span></legend>
                  <span id="span_tidakada_cur_penerima" style="display:none;">
                    <div class="div-row">						
                      <div class="div-col" style="width:100%;text-align:center;">
                      	<font color="#ff0000">TIDAK ADA PENERIMA MANFAAT TURUNAN</font>
                      </div>							
                    </div>											
                  </span>
											
                  <span id="span_ada_cur_penerima" style="display:none;">
                    </br>	
                    <div class="div-row">						
                      <div class="div-col" style="width:78%;">
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No. Konfirmasi</label>
                          <input type="text" id="cur_kode_klaim" name="cur_kode_klaim" style="width:200px;" readonly class="disabled">
                          <input type="text" id="cur_no_konfirmasi" name="cur_no_konfirmasi" style="width:25px;" readonly class="disabled">
                        </div>																																																		
                        <div class="clear"></div>
																																					 						 
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Nama Lengkap</label>
                          <input type="text" id="cur_nama_lengkap" name="cur_nama_lengkap" style="width:200px;" readonly class="disabled">
                          <input type="hidden" id="cur_no_urut_keluarga" name="cur_no_urut_keluarga">
                          <input type="text" id="cur_kode_penerima_berkala" name="cur_kode_penerima_berkala" style="width:25px;" readonly class="disabled">
                        </div>																																																		
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Tempat dan Tgl Lahir</label>
                          <input type="text" id="cur_tempat_lahir" name="cur_tempat_lahir" style="width:155px;" readonly class="disabled">
                          <input type="text" id="cur_tgl_lahir" name="cur_tgl_lahir" style="width:70px;" readonly class="disabled">
                        </div>
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Jns Klmin-Hub-No.KK</label>
                          <input type="text" id="cur_nama_jenis_kelamin" name="cur_nama_jenis_kelamin" style="width:80px;" readonly class="disabled">
                          <input type="hidden" id="cur_jenis_kelamin" name="cur_jenis_kelamin">
                          <input type="text" id="cur_nama_hubungan" name="cur_nama_hubungan" style="width:66px;" readonly class="disabled">
                          <input type="hidden" id="cur_kode_hubungan" name="cur_kode_hubungan">
                          <input type="text" id="cur_no_kartu_keluarga" name="cur_no_kartu_keluarga" style="width:70px;" readonly class="disabled">
                        </div>
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No. Identitas</label>
                          <input type="text" id="cur_nomor_identitas" name="cur_nomor_identitas" style="width:233px;" readonly class="disabled">
                          <input type="hidden" id="cur_status_valid_identitas" name="cur_status_valid_identitas"> 	
                          <input type="hidden" id="cur_jenis_identitas" name="cur_jenis_identitas">
                          <input type="hidden" id="cur_kpj_tertanggung" name="cur_kpj_tertanggung">
                        </div>																																																																																							
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">NPWP</label>		 	    				
                        	<input type="text" id="cur_npwp" name="cur_npwp" style="width:233px;" readonly class="disabled">
                        </div>																																																																																																												
                        <div class="clear"></div>							
                      </div>
                      
                      <div class="div-col" style="width:22%;text-align:center;">
                      	<input id="cur_foto" name="cur_foto" type="image" src="../../images/nopic.png" align="center" style="height: 90px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                      </div>
                    </div>
                    <!--end div-row-->
										
                    <div class="div-row">
                      <div class="div-col">
                      	</br>	 
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Alamat*</label>
                        	<textarea cols="255" rows="1" style="width:233px;background-color:#f5f5f5;height:15px;" readonly id="cur_alamat" name="cur_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>
                        </div>																																									
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">RT/RW</label>		 	    				
                          <input type="text" id="cur_rt" name="cur_rt" style="width:20px;" readonly class="disabled">
                          /
                          <input type="text" id="cur_rw" name="cur_rw" style="width:30px;" readonly class="disabled">
                          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kode Pos
                          <input type="text" id="cur_kode_pos" name="cur_kode_pos" style="width:50px;" readonly class="disabled">
                        </div>																																																	
                        <div class="clear"></div>
                      
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">&nbsp;</label>
                          <textarea cols="255" rows="1" style="width:233px;height:15px;background-color:#F5F5F5;" id="cur_ket_alamat_lanjutan" name="cur_ket_alamat_lanjutan" readonly onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>   					
                          <input type="hidden" id="cur_nama_kelurahan" name="cur_nama_kelurahan">
                          <input type="hidden" id="cur_kode_kelurahan" name="cur_kode_kelurahan">
                          <input type="hidden" id="cur_nama_kecamatan" name="cur_nama_kecamatan">
                          <input type="hidden" id="cur_kode_kecamatan" name="cur_kode_kecamatan">
                          <input type="hidden" id="cur_nama_kabupaten" name="cur_nama_kabupaten">
                          <input type="hidden" id="cur_kode_kabupaten" name="cur_kode_kabupaten">
                          <input type="hidden" id="cur_kode_propinsi" name="cur_kode_propinsi">
                          <input type="hidden" id="cur_nama_propinsi" name="cur_nama_propinsi">
                        </div>																	
                        <div class="clear"></div>
                      
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No. Telp</label>	    				
                          <input type="text" id="cur_telepon_area" name="cur_telepon_area" style="width:30px;" readonly class="disabled">
                          <input type="text" id="cur_telepon" name="cur_telepon" style="width:131px;" readonly class="disabled">
                          &nbsp;ext.
                          <input type="text" id="cur_telepon_ext" name="cur_telepon_ext" style="width:30px;" readonly class="disabled"> 						
                        </div>																																																				
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Email</label>		 	    				
                          <input type="text" id="cur_email" name="cur_email" style="width:150px;" readonly class="disabled">
                          <input type="checkbox" id="cur_is_verified_email" name="cur_is_verified_email" disabled class="cebox"><i><font color="#009999">verified</font></i>
                        </div>																																																																																																				
                        <div class="clear"></div>	
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Handphone</label>		 	    				
                          <input type="text" id="cur_handphone" name="cur_handphone" style="width:150px;" readonly class="disabled">
                          <input type="checkbox" id="cur_is_verified_hp" name="cur_is_verified_hp" disabled class="cebox"><i><font color="#009999">verified</font></i>
                        </div>																																																																																																		
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">&nbsp;</label>		 	    				
                          <input type="checkbox" id="cur_status_reg_notifikasi" name="cur_status_reg_notifikasi" disabled class="cebox"><i><font color="#009999">Layanan SMS Konfirmasi JP Berkala</font></i>
                          <input type="hidden" id="cur_status_cek_layanan" name="cur_status_cek_layanan">
                        </div>																																																																																																		
                        <div class="clear"></div>
                        
                        </br>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Rekening Bank</label> 
                          <input type="text" id="cur_bank_penerima" name="cur_bank_penerima" style="width:230px;" readonly class="disabled">
                          <input type="hidden" id="cur_kode_bank_penerima" name="cur_kode_bank_penerima">
                          <input type="hidden" id="cur_id_bank_penerima" name="cur_id_bank_penerima">
                          <input type="hidden" id="cur_metode_transfer" name="cur_metode_transfer" style="width:20px;" readonly class="disabled">
                        </div>																																																	
                        <div class="clear"></div>
                        
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No Rekening</label>
                          <input type="text" id="cur_no_rekening_penerima" name="cur_no_rekening_penerima" style="width:100px;" readonly class="disabled">
                          <input type="text" id="cur_nama_rekening_penerima_ws" name="cur_nama_rekening_penerima_ws" style="width:100px;" readonly class="disabled">
                          <input type="checkbox" id="cb_cur_valid_rekening" name="cb_cur_valid_rekening" disabled class="cebox"><i><font color="#009999">Valid</font></i>	
                          <input type="hidden" id="cur_nama_rekening_penerima" name="cur_nama_rekening_penerima" style="width:273px;" readonly class="disabled">
                          <input type="hidden" id="cur_st_valid_rekening_penerima" name="cur_st_valid_rekening_penerima">									
                          <input type="hidden" id="cur_kode_bank_pembayar" name="cur_kode_bank_pembayar">
                          <input type="hidden" id="cur_nama_bank_pembayar" name="cur_nama_bank_pembayar">
                          <input type="hidden" id="cur_status_rekening_sentral" name="cur_status_rekening_sentral">
                          <input type="hidden" id="cur_kantor_rekening_sentral" name="cur_kantor_rekening_sentral">								
                        </div>																																																																																															
                        <div class="clear"></div>
                      	</br>						
                      </div>
                    </div>											
                  </span>																						
								</fieldset>			 
							</div>	 
						</div>
						
            <div class="div-row">
              <div class="div-col" style="width: 100%">
                <!-- Informasi Penerima Manfaat Sebelumnya ---------------->	 
                <fieldset><legend>Informasi Penerima Manfaat Sebelumnya</legend>			
                  </br>
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Nama Lengkap</label>
                    <input type="text" id="ind_nama_lengkap" name="ind_nama_lengkap" style="width:200px;" readonly class="disabled">
                    <input type="text" id="ind_kode_penerima_berkala" name="ind_kode_penerima_berkala" style="width:20px;" readonly class="disabled">
                    <input type="hidden" id="ind_nama_hubungan" name="ind_nama_hubungan">
                    <input type="hidden" id="ind_nama_jenis_kelamin" name="ind_nama_jenis_kelamin">
                    <input type="hidden" id="ind_jenis_kelamin" name="ind_jenis_kelamin">
                    <input type="hidden" id="ind_kode_hubungan" name="ind_kode_hubungan">
                    <input type="hidden" id="ind_no_kartu_keluarga" name="ind_no_kartu_keluarga">
                    <input type="hidden" id="ind_tempat_lahir" name="ind_tempat_lahir">
                    <input type="hidden" id="ind_tgl_lahir" name="ind_tgl_lahir">
                    <input type="hidden" id="ind_no_urut_keluarga" name="ind_no_urut_keluarga">
                  </div>
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Kondisi Terakhir</label>
                    <input type="text" id="nama_kondisi_terakhir_induk" name="nama_kondisi_terakhir_induk" style="width:116px;" readonly class="disabled">
                    <input type="text" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" style="width:75px;" readonly class="disabled">
                    <input type="hidden" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk">
                    <a href="#" onClick="showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_jpn_ahliwarisdetil.php?kode_klaim='+formreg.kode_klaim.value+'&no_urut_keluarga='+formreg.ind_no_urut_keluarga.value+'&mid=<?=$mid;?>','',980,610,'no')">&nbsp;<img src="../../images/ico_profile.jpg" border="0" alt="Tambah" align="absmiddle" style="height:20px;"/> profile</a>
                  </div>
                  <div class="clear"></div>
                  </br>																				
                </fieldset>				
              </div>
            </div>							 
					</div>
					
					<div class="div-col" style="width:1%;">
          </div>
					
					<div class="div-col-right" style="width:54%;">
            <div class="div-row">
              <div class="div-col" style="width:98%;text-align:center;">
                <fieldset><legend><span style="vertical-align: middle;" id="span_fieldset_peserta"></span></legend>
                  <input type="hidden" id="no_penetapan" name="no_penetapan" value="<?=$ls_no_penetapan;?>">		
                  <input type="hidden" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?=$ln_no_konfirmasi_induk;?>">
                  <input type="hidden" id="cnt_berkala_detil" name="cnt_berkala_detil" value="<?=$ln_cnt_berkala_detil;?>">
                  
                  <a href="#" onClick="showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5065_view_dokumen.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img src="../../images/ico_document.jpg" border="0" alt="Rincian Dokumen Kelengkapan Administrasi" align="absmiddle" style="height:30px;"/><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Dokumen</font></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="#" onClick="showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5065_view_ahliwaris.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img src="../../images/ico_ahliwaris.jpg" border="0" alt="Rincian Ahli Waris" align="absmiddle" style="height:30px;"/><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Ahli Waris</font></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="#" onClick="showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5065_view_historykonfirmasi.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img src="../../images/ico_history.jpg" border="0" alt="Rincian Histori Konfirmasi" align="absmiddle" style="height:30px;"/><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Histori</font></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                  <a href="#" onClick="showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5065_view_historypembayaran.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img src="../../images/ico_pembayaran.jpg" border="0" alt="Rincian Histori Pembayaran" align="absmiddle" style="height:30px;"/><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Pembayaran</font></a>						
                </fieldset>			
              </div>
            </div>
            
            <div class="div-row">
              <div class="div-col" style="width:98%;">
                </br>	 
                <!-- Informasi periode JP Berkala Selanjutnya ------------->	 
                <fieldset style="min-height:125px;"><legend><span style="vertical-align: middle;" id="span_fieldset_blnberkala"></span></legend>
                  </br>
                  
                  <span id="span_tidakada_cur_periodeberkala" style="display:none;">
                    <div class="div-row">
                      <div class="div-col" style="width:100%;text-align:center;">
                      	<b><span style="vertical-align: middle;" id="span_info_tidakada_cur_periodeberkala"></span></b>
                      </div>	
                    </div>											
                  </span>
                  
                  <span id="span_ada_cur_periodeberkala" style="display:none;">
                    <table id="tblBlnBkl" width="100%" class="table-data2">
                      <thead>
                        <tr class="hr-double-bottom">		 
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Prg</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Ke-</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jml Berkala</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>	
                        </tr>	 
                      </thead>
                      <tbody id="data_list_BlnBkl">												
                        <tr class="nohover-color">
                        	<td colspan="8" style="text-align: center;">-- data tidak ditemukan --</td>
                        </tr>							             																
                      </tbody>
                      <tfoot>
                        <tr>
                          <td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i></td>	  		
                          <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_berjalan"></span></td>
                          <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_rapel"></span></td>							
                          <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_kompensasi"></span></td>
                          <td style="text-align:right;"><span style="vertical-align: middle;" id="span_tot_berkala"></span></td>
                          <td></td>										        
                        </tr>														
                      </tfoot>
                    </table>											
                  </span>
                  </br>									
                </fieldset>				
              </div>
            </div>
            
            <div class="div-row">
              <div class="div-col" style="width:98%;">
                <span id="span_dokumen" style="display:none;">
                  </br>
                  <!-- Informasi Dokumen Kelengkapan Administrasi ------------->
                  <fieldset style="min-height:278px;"><legend>Dokumen Kelengkapan Administrasi</legend>
                    <table id="tblrincianDok" width="100%" class="table-data2">
                      <thead>
                        <tr class="hr-double-bottom">		 
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama Dokumen</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Mandatory</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Action</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Status</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tanggal</th>
                          <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">File</th>	
                        </tr>	 
                      </thead>
                      <tbody id="data_list_Dok">												
                        <tr class="nohover-color">
                        	<td colspan="7" style="text-align: center;">-- tidak ada persyaratan dokumen --</td>
                        </tr>							             																
                      </tbody>
                      <tfoot>
                        <tr>
                        	<td style="text-align:center" colspan="7"></td>
                        </tr>
                      </tfoot>																					
                    </table>									
                  </fieldset>										
                </span>				
              </div>
            </div>					
					</div>			 
				</div>	 
			</div>	 
		</div>

		<div style="display: none;" id="tab2" class="tab_konten">
			<div id="konten" style="width:98%;">
				<div class="div-row">
					<div class="div-col" style="width:49%; max-height: 100%;">
						<fieldset><legend>Informasi Penetapan Manfaat JP Berkala</legend>
							</br>
							
							<div class="form-row_kiri">
              <label style = "text-align:right;">Nomor Berkala</label>
                <input type="text" id="byr_kode_klaim" name="byr_kode_klaim" style="width:180px;" readonly class="disabled">
                <input type="text" id="byr_no_konfirmasi" name="byr_no_konfirmasi" style="width:35px;" readonly class="disabled">
								<input type="text" id="byr_no_proses" name="byr_no_proses" style="width:27px;" readonly class="disabled">
								<input type="hidden" id="byr_kd_prg" name="byr_kd_prg" style="width:27px;" readonly class="disabled">
              </div>																																																		
              <div class="clear"></div>
							
              <div class="form-row_kiri">
              <label style = "text-align:right;">Periode Pembayaran</label>
                <input type="text" id="byr_ket_blth_proses" name="byr_ket_blth_proses" style="width:260px;" readonly class="disabled">
                <input type="hidden" id="byr_blth_proses" name="byr_blth_proses">
              </div>																																																		
              <div class="clear"></div>

              <div class="form-row_kiri">
              <label style = "text-align:right;">Penerima Manfaat</label>
                <input type="text" id="byr_ket_penerima_berkala" name="byr_ket_penerima_berkala" style="width:260px;" readonly class="disabled">
                <input type="hidden" id="byr_kode_penerima_berkala" name="byr_kode_penerima_berkala">
								<input type="hidden" id="byr_no_urut_keluarga" name="byr_no_urut_keluarga">
              </div>																																																		
              <div class="clear"></div>
							
      				</br>		
      
      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Manfaat Berjalan </label>
      		      <input type="text" id="byr_nom_berjalan" name="byr_nom_berjalan" style="width:220px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Manfaat Rapel</label>
      		      <input type="text" id="byr_nom_rapel" name="byr_nom_rapel" style="width:220px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Kompensasi</label>
      		      <input type="text" id="byr_nom_kompensasi" name="byr_nom_kompensasi" style="width:220px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Jumlah Manfaat</label>
      		      <input type="text" id="byr_nom_berkala" name="byr_nom_berkala" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">PPh</label>
      		      <input type="text" id="byr_nom_pph" name="byr_nom_pph" style="width:120px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    	<input type="text" id="byr_kode_pajak_pph" name="byr_kode_pajak_pph" style="width:70px;" readonly class="disabled" >
							</div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Pembulatan</label>
      		      <input type="text" id="byr_nom_pembulatan" name="byr_nom_pembulatan" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Jumlah Netto</label>
      		      <input type="text" id="byr_nom_manfaat_netto" name="byr_nom_manfaat_netto" style="width:220px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>
														
      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Sudah Dibayar</label>
      		      <input type="text" id="byr_nom_sudah_bayar" name="byr_nom_sudah_bayar" style="width:220px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>

      		    <div class="form-row_kiri">
      		    <label  style = "text-align:right;">Jml yg hrs Dibayar</label>
      		      <input type="text" id="byr_nom_sisa" name="byr_nom_sisa" style="width:200px;" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
      		    </div>																																																		
      		    <div class="clear"></div>
							
							</br>
      		    <div class="form-row_kiri">
      		    <label style = "text-align:right;">Keterangan&nbsp;</label>
      		    	<textarea cols="255" rows="1" id="byr_keterangan" name="byr_keterangan" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" readonly style="width:260px;height:15px;background-color:#F5F5F5"></textarea>   					
      		    </div>							
      		    <div class="clear"></div>
																																																																																				
						</fieldset>
					</div>
					
          <div class="div-col" style="width:1%;">
          </div>
					
					<div class="div-col-right" style="width:50%;">
						<div class="div-row">
							<div class="div-col" style="width: 100%">
          			<fieldset><legend>Informasi Penerima Manfaat</legend>
                 <div class="div-row">
                   <div class="div-col" style="width: 150px;text-align:center;">
                   	<input id="byr_nik_penerima_foto" name="byr_nik_penerima_foto" type="image" align="center" src="../../images/nopic.png" style="height: 107px !important; width: 100px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                   </div>
                   <div class="div-col">
                     <div class="form-row_kiri">
                     <label style = "text-align:left;width:100px;">Nama Penerima</label>
                       <input type="text" id="byr_nama_penerima" name="byr_nama_penerima" style="width:220px;" readonly class="disabled">
                       <input type="hidden" id="byr_nik_penerima" name="byr_nik_penerima" style="width:210px;" readonly class="disabled">
                       <input type="hidden" id="byr_tgl_siapbayar" name="byr_tgl_siapbayar" maxlength="10" readonly class="disabled" style="width:210px;">
                       <input type="hidden" id="byr_status_siapbayar" name="byr_status_siapbayar">
                       <input type="hidden" id="byr_petugas_siapbayar" name="byr_petugas_siapbayar">
											 <input type="hidden" id="byr_kode_kantor_pembayar" name="byr_kode_kantor_pembayar">
											 <input type="text" id="byr_similarity_nama_penerima" name="byr_similarity_nama_penerima" readonly style="width:20px;text-align:center;">&nbsp;%
                     </div>																																																																																														
                     <div class="clear"></div>
              
                     <div class="form-row_kiri">
                     <label style = "text-align:left;width:100px;">NPWP</label>	    				
                     	<input type="text" id="byr_npwp_penerima" name="byr_npwp_penerima" style="width:250px;" readonly class="disabled" onblur="fl_js_val_npwp('byr_npwp_penerima');">
                     	<input type="hidden" id="byr_npwp_penerima_old" name="byr_npwp_penerima_old">
                     </div>																																	
                     <div class="clear"></div>
                
                     <div class="form-row_kiri">
                     <label style = "text-align:left;width:100px;">Email</label>	    				
                     	<input type="text" id="byr_email_penerima" name="byr_email_penerima" style="width:250px;" onblur="this.value=this.value.toLowerCase();fl_js_val_email('byr_email_penerima');" readonly class="disabled">
                     </div>																																	
                     <div class="clear"></div>
              
                     <div class="form-row_kiri">
                     <label style = "text-align:left;width:100px;">Handphone</label>	    				
                     	<input type="text" id="byr_handphone_penerima" name="byr_handphone_penerima" style="width:250px;" onblur="fl_js_val_numeric('byr_handphone_penerima');" readonly class="disabled">
          						</div>																																	
                     <div class="clear"></div>
                   </div>
                 </div>
          			</fieldset>									 
							</div>	 
						</div>
						
						<div class="div-row">
							<div class="div-col" style="width: 100%">
								<fieldset style="min-height:120px;"><legend><i><font color="#009999">Dibayarkan ke :</font></i></legend>
                  </br>
									<div class="form-row_kiri">
                  <label style = "text-align:right;">Bank</label> 
                    <input type="text" id="byr_nama_bank_penerima" name="byr_nama_bank_penerima" readonly style="width:310px;background-color:#F5F5F5">
                    <input type="hidden" id="byr_kode_bank_penerima" name="byr_kode_bank_penerima" style="width:100px;">
                    <input type="hidden" id="byr_id_bank_penerima" name="byr_id_bank_penerima" style="width:100px;">
                    <input type="hidden" id="byr_metode_transfer" name="byr_metode_transfer" maxlength="4" readonly class="disabled" style="width:20px;">
                    <a style="display:none;" id="btn_lov_byr_bank_penerima" href="#" onclick="fl_js_jpbklverif_get_lov_bank_penerima();">							
                    <img src="../../images/help.png" alt="Cari Bank" border="0" style="height:19px;" align="absmiddle"></a>
                    <input type="hidden" id="byr_kode_bank_penerima_old" name="byr_kode_bank_penerima_old">
                  </div>																																																	
                  <div class="clear"></div>
                  
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">No Rekening</label>
                    <input type="number" id="byr_no_rekening_penerima" name="byr_no_rekening_penerima" onblur="fjq_ajax_val_jpbklverif_no_rekening_penerima();" onkeydown="if(event.key==='.' || event.key==='e' ){event.preventDefault();}" onpaste="let pasteData = event.clipboardData.getData('text'); if(pasteData){pasteData.replace(/[^0-9]*/g,'');} " tabindex="22" maxlength="30" readonly class="disabled" style="width:150px;background-color:#F5F5F5">
                    <input type="hidden" id="byr_nama_rekening_penerima" name="byr_nama_rekening_penerima" maxlength="100" readonly class="disabled" style="width:120px;background-color:#F5F5F5">
                    <input type="text" id="byr_nama_rekening_penerima_ws" name="byr_nama_rekening_penerima_ws" maxlength="100" style="width:150px;background-color:#F5F5F5" tabindex="23" readonly class="disabled" onblur="this.value=this.value.toUpperCase();">
                    <input type="checkbox" id="cb_byr_valid_rekening" name="cb_byr_valid_rekening" disabled class="cebox"><i><font color="#009999">Valid</font></i>	
                    <input type="hidden" id="byr_status_valid_rekening_penerima" name="byr_status_valid_rekening_penerima">
                    <input type="hidden" id="byr_no_rekening_penerima_old" name="byr_no_rekening_penerima_old">
                  </div>																																																																																															
                  <div class="clear"></div>  
									    
                  </br> 
									          
                  <div class="form-row_kiri">
                  <label style = "text-align:right;"> 	    				
                    <img src="../../images/warning.gif" border="0" alt="Tambah" align="top" style="height:12px;"/></label> 
                    <i><font color="#ff0000">Rekening harus valid untuk menghindari gagal transfer..!</font></i>
                  </div>																																																																																																																																																												
                  <div class="clear"></div>													
								</fieldset>	 
							</div>	 
						</div>
						
						<div class="div-row">
							<div class="div-col" style="width: 100%">
								<fieldset style="height:97px;"><legend><i><font color="#009999">Dari :</font></i></legend>
									</br>				
									<span id="byr_span_entry_bank_pembayar" style="display:none;">
										<div class="form-row_kiri">
										<label style = "text-align:right;">Bank &nbsp;&nbsp;*</label>	 
                     	<select size="1" id="byr_kode_bank_pembayar_entry" name="byr_kode_bank_pembayar_entry" tabindex="24" class="select_format" readonly style="width:310px;background-color:#ffff99;" >
                     		<option value="">-- Pilih --</option>
                     	</select>
               			</div>																																																																																																																																																												
                  	<div class="clear"></div>
										
										</br>
										
                    <div class="form-row_kiri">
                    <label style = "text-align:right;">&nbsp;</label> 
                      <i><font color="#546E7A">(*..Pilih ulang bank penerima jika pilihan bank Dari kosong..</font></i>
                    </div>																																																																																																																																																												
                    <div class="clear"></div>										
									</span>
										
									<span id="byr_span_display_bank_pembayar" style="display:block;">	
										<div class="form-row_kiri">
										<label style = "text-align:right;">Bank &nbsp;&nbsp;*</label>										
                   		<input type="text" id="byr_nama_bank_pembayar" name="byr_nama_bank_pembayar" readonly class="disabled" style="width:280px;">
                     	<input type="hidden" id="byr_kode_bank_pembayar" name="byr_kode_bank_pembayar">
               			</div>																																																																																																																																																												
                  	<div class="clear"></div>											
               		</span>
                  <input type="hidden" id="byr_id_bank_opg" name="byr_id_bank_opg">
                  <input type="hidden" id="byr_kode_buku" name="byr_kode_buku">
                  <input type="hidden" id="byr_klm_kode_pointer_asal" name="byr_klm_kode_pointer_asal">    								
                  <input type="hidden" id="byr_status_rekening_sentral" name="byr_status_rekening_sentral">
                  <input type="hidden" id="byr_kantor_rekening_sentral" name="byr_kantor_rekening_sentral">
                  <input type="hidden" id="byr_st_mode_edit" name="byr_st_mode_edit">
								</fieldset>	 
							</div>	 
						</div>
							 
					</div>
							
				</div>	 
			</div>
		</div>

	</div>
	<!--end div_body-->
	
  <div id="div_footer" class="div-footer">		
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
                    <span>CANCEL PERUBAHAN DATA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
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
                    <span>SUBMIT DATA SIAP BAYAR &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                  </a>
                </div>
              </div>
            </div>					
          </span>

          <span id="span_button_tutup_siapbyr" style="display:none;">
            <div class="div-col">
              <div class="div-action-footer">
                <div class="icon">
                  <a id="btn_doBack2Grid" href="#" onClick="fl_js_reloadPage();">
                    <img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
                    <span>TUTUP &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
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
			fl_js_jpbklverif_resize_grid();
		});
		
		fl_js_jpbklverif_resize_grid();

		/** list checkbox */
		window.list_checkbox_record = [];
	});
	
	function fl_js_jpbklverif_resize_grid(){		 
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

	function fl_js_jpbklverif_search_by_changed(){
		$("#search_txt").val("");
	}
		
	function fl_js_jpbklverif_search_by2_changed(){
		$('#'+$('#search_by2').val()).show();
	}
	
	function fl_js_jpbklverif_orderby(e) {
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

		fl_js_jpbklverif_filter();
	}
	
	function fl_js_jpbklverif_filter(val = 0){
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
		var blthjt 					 = $("input[name='rg_blthjt']:checked").val();
	
    //set ulang parameter temporary ------------------------------------
		$('#tmp_jenis_pembayaran').val(jenis_pembayaran);
		$('#tmp_status_siapbayar').val(status_siapbayar);
		$('#tmp_blthjt').val(blthjt);
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
		html_data += '<td colspan="12" style="text-align: center;">-- Data tidak ditemukan --</td>';
		html_data += '</tr>';
		$("#data_list_jpberkala").html(html_data);

		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe 				 		 : 'MainDataGridJPBerkalaVerif',
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
				blthjt					 : blthjt
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
							v_status_valid_rekening_penerima = getValue(jdata.data[i].STATUS_VALID_REKENING_PENERIMA) === "Y" ? "<img src=../../images/file_apply.gif> Ya" : "<img src=../../images/file_cancel.gif> Tidak"; 
							
							html_data += '<tr>';
							html_data += '<td style="text-align: left;">' + getValue(jdata.data[i].ACTION) + '</td>';
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
						$("#hdn_total_records").val(jdata.recordsTotal);

						fl_js_jpbklverif_resize_grid();
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
	
	function fl_js_jpbklverif_doGridTask(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_jenis_pembayaran, v_status_siapbayar, v_blthjt)
  {				
		v_editid = v_kode_klaim+v_no_konfirmasi+v_no_proses+v_kd_prg;
		
		document.formreg.task.value = 'edit';
		document.formreg.editid.value = v_editid;
		document.formreg.kode_klaim.value = v_kode_klaim;
		document.formreg.no_konfirmasi.value = v_no_konfirmasi;
		document.formreg.no_proses.value = v_no_proses;
		document.formreg.kd_prg.value = v_kd_prg;
		
		document.formreg.tmp_jenis_pembayaran.value = v_jenis_pembayaran;
		document.formreg.tmp_status_siapbayar.value = v_status_siapbayar;
		document.formreg.tmp_blthjt.value = v_blthjt;
		
    try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }

	function fl_js_jpbklverif_reloadFormUtama()
  {
		document.formreg.task_detil.value = '';
		
		try {
    		document.formreg.onsubmit();
    } catch(e){}
    document.formreg.submit();	
  }
		
	function fl_js_jpbklverif_reloadFormEntry(task, editid)
  {
		document.formreg.task.value = task;
		document.formreg.editid.value = editid;
		document.formreg.kode_klaim.value = editid;
		fl_js_jpbklverif_reloadFormUtama();	
  }	
</script>

<?
//----------------------------- EDIT/VIEW --------------------------------------
?>
<script language="javascript">
	function fl_js_jpbklverif_loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, fn)
	{
		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			return alert('Data Konfimasi JP Berkala tidak boleh kosong');
		}
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5063_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatajpberkalasiapbayar',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi: v_no_konfirmasi,
				v_no_proses: v_no_proses,
				v_kd_prg: v_kd_prg
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
            //$("#span_page_title_right").html('NO. KONFIRMASI : '+v_kode_klaim+'-'+v_no_konfirmasi);
						$('#cur_kode_klaim').val(v_kode_klaim);
						$('#cur_no_konfirmasi').val(v_no_konfirmasi);
													
						$('#no_penetapan').val(getValue(jdata.data.dataKonf['NO_PENETAPAN']));
            $('#no_konfirmasi_induk').val(getValue(jdata.data.dataKonf['NO_KONFIRMASI_INDUK']));
            $('#cnt_berkala_detil').val(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL']));
						
						if (parseInt(getValueNumber(jdata.data.dataKonf['CNT_BERKALA_DETIL']))>0)
						{
						 	window.document.getElementById("span_tidakada_cur_penerima").style.display = 'none';
							window.document.getElementById("span_ada_cur_penerima").style.display = 'block';
							
							$("#span_fieldset_curr_penerima").html('Informasi Penerima Manfaat JP Berkala Periode '+ getValue(jdata.data.dataKonf['BLTH_AWAL']) +' s/d '+ getValue(jdata.data.dataKonf['BLTH_AKHIR']) +'');
							
							window.document.getElementById("span_tidakada_cur_periodeberkala").style.display = 'none';
							window.document.getElementById("span_ada_cur_periodeberkala").style.display = 'block';
							window.document.getElementById("span_dokumen").style.display = 'block';
						}else
						{
						 	window.document.getElementById("span_tidakada_cur_penerima").style.display = 'block';
							window.document.getElementById("span_ada_cur_penerima").style.display = 'none';
							
							window.document.getElementById("span_tidakada_cur_periodeberkala").style.display = 'block';
							window.document.getElementById("span_ada_cur_periodeberkala").style.display = 'none';
							window.document.getElementById("span_dokumen").style.display = 'none';
							
							$("#span_info_tidakada_cur_periodeberkala").html('<b><font color="#ff0000">MANFAAT DIHENTIKAN SEJAK '+getValue(jdata.data.dataKonf['TGL_KONDISI_TERAKHIR_INDUK'])+' <br></br>HARAP TETAP MELAKUKAN SUBMIT DATA KONFIRMASI JP BERKALA..</font></b>');
						}
						
						$("#span_fieldset_peserta").html('Nama Peserta : '+ getValue(jdata.data.dataKonf['KET_KLAIM_ATASNAMA']).substring(0,70) +' ...');
						
						if (getValue(jdata.data.dataKonf['STATUS_BERHENTI_MANFAAT']) == 'Y')
						{
						 	$("#span_fieldset_blnberkala").html(''); 
						}else
						{
						 	$("#span_fieldset_blnberkala").html('Informasi Manfaat JP Berkala Periode '+ getValue(jdata.data.dataKonf['BLTH_AWAL']) +' s/d '+ getValue(jdata.data.dataKonf['BLTH_AKHIR']) +'');	 
						}
						
						//get data current penerima manfaat jp berkala ---------------------
						$('#cur_nama_lengkap').val(getValue(jdata.data.dataKonf['CUR_NAMA_LENGKAP']));
						$('#cur_no_urut_keluarga').val(getValue(jdata.data.dataKonf['CUR_NO_URUT_KELUARGA']));
						$('#cur_kode_penerima_berkala').val(getValue(jdata.data.dataKonf['CUR_KODE_PENERIMA_BERKALA']));
				
            $('#cur_tempat_lahir').val(getValue(jdata.data.dataKonf['CUR_TEMPAT_LAHIR']));
            $('#cur_tgl_lahir').val(getValue(jdata.data.dataKonf['CUR_TGL_LAHIR']));
            $('#cur_nama_jenis_kelamin').val(getValue(jdata.data.dataKonf['CUR_NAMA_JENIS_KELAMIN']));
            $('#cur_jenis_kelamin').val(getValue(jdata.data.dataKonf['CUR_JENIS_KELAMIN']));
            $('#cur_nama_hubungan').val(getValue(jdata.data.dataKonf['CUR_NAMA_HUBUNGAN']));
            $('#cur_kode_hubungan').val(getValue(jdata.data.dataKonf['CUR_KODE_HUBUNGAN']));
            $('#cur_no_kartu_keluarga').val(getValue(jdata.data.dataKonf['CUR_NO_KARTU_KELUARGA']));
            $('#cur_nomor_identitas').val(getValue(jdata.data.dataKonf['CUR_NOMOR_IDENTITAS']));
            $('#cur_status_valid_identitas').val(getValue(jdata.data.dataKonf['CUR_STATUS_VALID_IDENTITAS']));	
            $('#cur_jenis_identitas').val(getValue(jdata.data.dataKonf['CUR_JENIS_IDENTITAS']));
            $('#cur_kpj_tertanggung').val(getValue(jdata.data.dataKonf['CUR_KPJ_TERTANGGUNG']));
            $('#cur_npwp').val(getValue(jdata.data.dataKonf['CUR_NPWP']));
						
						$('#cur_foto').attr('src','<?= "../../mod_kn/ajax/kngetfoto.php?dataid=" ?>' + $('#cur_nomor_identitas').val());
						
						var v_cur_kel = getValue(jdata.data.dataKonf['CUR_NAMA_KELURAHAN'])=='' ? '' : 'KEL. '+getValue(jdata.data.dataKonf['CUR_NAMA_KELURAHAN']);
						var v_cur_kec = getValue(jdata.data.dataKonf['CUR_NAMA_KECAMATAN'])=='' ? '' : 'KEC. '+getValue(jdata.data.dataKonf['CUR_NAMA_KECAMATAN']);
						var v_cur_kab = getValue(jdata.data.dataKonf['CUR_NAMA_KABUPATEN'])=='' ? '' : 'KAB. '+getValue(jdata.data.dataKonf['CUR_NAMA_KABUPATEN']);
						var v_cur_pro = getValue(jdata.data.dataKonf['CUR_NAMA_PROPINSI'])=='' ? '' : 'PROP. '+getValue(jdata.data.dataKonf['CUR_NAMA_PROPINSI']); 
						
						var v_cur_ket_alamat_lanjutan = v_cur_kel+' '+v_cur_kec+' '+v_cur_kab+' '+v_cur_pro;
												
						$('#cur_ket_alamat_lanjutan').val(v_cur_ket_alamat_lanjutan);
						$('#cur_alamat').val(getValue(jdata.data.dataKonf['CUR_ALAMAT']));
            $('#cur_rt').val(getValue(jdata.data.dataKonf['CUR_RT']));
            $('#cur_rw').val(getValue(jdata.data.dataKonf['CUR_RW']));
            $('#cur_kode_pos').val(getValue(jdata.data.dataKonf['CUR_KODE_POS']));
            $('#cur_nama_kelurahan').val(getValue(jdata.data.dataKonf['CUR_NAMA_KELURAHAN']));
            $('#cur_kode_kelurahan').val(getValue(jdata.data.dataKonf['CUR_KODE_KELURAHAN']));
            $('#cur_nama_kecamatan').val(getValue(jdata.data.dataKonf['CUR_NAMA_KECAMATAN']));
            $('#cur_kode_kecamatan').val(getValue(jdata.data.dataKonf['CUR_KODE_KECAMATAN']));
            $('#cur_nama_kabupaten').val(getValue(jdata.data.dataKonf['CUR_NAMA_KABUPATEN']));
            $('#cur_kode_kabupaten').val(getValue(jdata.data.dataKonf['CUR_KODE_KABUPATEN']));
            $('#cur_kode_propinsi').val(getValue(jdata.data.dataKonf['CUR_KODE_PROPINSI']));
            $('#cur_nama_propinsi').val(getValue(jdata.data.dataKonf['CUR_NAMA_PROPINSI']));
            $('#cur_telepon_area').val(getValue(jdata.data.dataKonf['CUR_TELEPON_AREA']));
            $('#cur_telepon').val(getValue(jdata.data.dataKonf['CUR_TELEPON']));
            $('#cur_telepon_ext').val(getValue(jdata.data.dataKonf['CUR_TELEPON_EXT']));						
            $('#cur_email').val(getValue(jdata.data.dataKonf['CUR_EMAIL']));
						
						if (getValue(jdata.data.dataKonf['CUR_IS_VERIFIED_EMAIL'])=="Y")
						{
							window.document.getElementById('cur_is_verified_email').checked = true; 
						}else
						{								
							window.document.getElementById('cur_is_verified_email').checked = false; 
						}
						
            $('#cur_handphone').val(getValue(jdata.data.dataKonf['CUR_HANDPHONE']));
            
						if (getValue(jdata.data.dataKonf['CUR_IS_VERIFIED_HP'])=="Y")
						{
							window.document.getElementById('cur_is_verified_hp').checked = true; 
						}else
						{								
							window.document.getElementById('cur_is_verified_hp').checked = false; 
						}
						
						if (getValue(jdata.data.dataKonf['CUR_STATUS_REG_NOTIFIKASI'])=="Y")
						{
							window.document.getElementById('cur_status_reg_notifikasi').checked = true; 
						}else
						{								
							window.document.getElementById('cur_status_reg_notifikasi').checked = false; 
						}
						
            $('#cur_status_cek_layanan').val(getValue(jdata.data.dataKonf['CUR_STATUS_CEK_LAYANAN']));
            $('#cur_bank_penerima').val(getValue(jdata.data.dataKonf['CUR_BANK_PENERIMA']));
            $('#cur_kode_bank_penerima').val(getValue(jdata.data.dataKonf['CUR_KODE_BANK_PENERIMA']));
            $('#cur_id_bank_penerima').val(getValue(jdata.data.dataKonf['CUR_ID_BANK_PENERIMA']));
            $('#cur_metode_transfer').val(getValue(jdata.data.dataKonf['CUR_METODE_TRANSFER']));
            $('#cur_no_rekening_penerima').val(getValue(jdata.data.dataKonf['CUR_NO_REKENING_PENERIMA']));
            $('#cur_nama_rekening_penerima_ws').val(getValue(jdata.data.dataKonf['CUR_NAMA_REKENING_PENERIMA']));
            
						if (getValue(jdata.data.dataKonf['CUR_ST_VALID_REKENING_PENERIMA'])=="Y")
						{
							window.document.getElementById('cb_cur_valid_rekening').checked = true; 
						}else
						{								
							window.document.getElementById('cb_cur_valid_rekening').checked = false; 
						}
						
						$('#cur_nama_rekening_penerima').val(getValue(jdata.data.dataKonf['CUR_NAMA_REKENING_PENERIMA']));
            $('#cur_st_valid_rekening_penerima').val(getValue(jdata.data.dataKonf['CUR_ST_VALID_REKENING_PENERIMA']));					
            $('#cur_kode_bank_pembayar').val(getValue(jdata.data.dataKonf['CUR_KODE_BANK_PEMBAYAR']));
						$('#cur_nama_bank_pembayar').val(getValue(jdata.data.dataKonf['CUR_NAMA_BANK_PEMBAYAR'])); //ws masih bernilai null - request pak gun 21/12/2019
						
            $('#cur_status_rekening_sentral').val(getValue(jdata.data.dataKonf['CUR_STATUS_REKENING_SENTRAL']));
            $('#cur_kantor_rekening_sentral').val(getValue(jdata.data.dataKonf['CUR_KANTOR_REKENING_SENTRAL']));		
						//end get data current penerima manfaat jp berkala -----------------
						
						//get data lalu penerima manfaat jp berkala ------------------------
            $('#ind_nama_lengkap').val(getValue(jdata.data.dataKonf['IND_NAMA_LENGKAP']));
            $('#ind_kode_penerima_berkala').val(getValue(jdata.data.dataKonf['IND_KODE_PENERIMA_BERKALA']));
            $('#ind_nama_hubungan').val(getValue(jdata.data.dataKonf['IND_NAMA_HUBUNGAN']));
            $('#ind_nama_jenis_kelamin').val(getValue(jdata.data.dataKonf['IND_NAMA_JENIS_KELAMIN']));
            $('#ind_jenis_kelamin').val(getValue(jdata.data.dataKonf['IND_JENIS_KELAMIN']));
            $('#ind_kode_hubungan').val(getValue(jdata.data.dataKonf['IND_KODE_HUBUNGAN']));
            $('#ind_no_kartu_keluarga').val(getValue(jdata.data.dataKonf['IND_NO_KARTU_KELUARGA']));
						$('#ind_no_urut_keluarga').val(getValue(jdata.data.dataKonf['IND_NO_URUT_KELUARGA']));
            $('#ind_tempat_lahir').val(getValue(jdata.data.dataKonf['IND_TEMPAT_LAHIR']));
            $('#ind_tgl_lahir').val(getValue(jdata.data.dataKonf['IND_TGL_LAHIR']));
            $('#nama_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonf['NAMA_KONDISI_TERAKHIR_INDUK']));
            $('#tgl_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonf['TGL_KONDISI_TERAKHIR_INDUK']));
            $('#kode_kondisi_terakhir_induk').val(getValue(jdata.data.dataKonf['KODE_KONDISI_TERAKHIR_INDUK']));
            //end get data lalu penerima manfaat jp berkala --------------------

						//get data list periode jp berkala ---------------------------------
						var html_data_BlnBkl = "";
						var v_tot_nom_berjalan = 0;
						var v_tot_nom_rapel = 0;
						var v_tot_nom_kompensasi = 0;
						var v_tot_nom_berkala = 0;
						
						if (jdata.data.dataBlnBkl)
						{ 
  						for(var i = 0; i < jdata.data.dataBlnBkl.length; i++)
  						{
  							html_data_BlnBkl += '<tr>';
  							html_data_BlnBkl += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBkl[i].NM_PRG) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBkl[i].NO_PROSES) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: center;">' + getValue(jdata.data.dataBlnBkl[i].BLTH_PROSES) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_BERJALAN)) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_RAPEL)) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_KOMPENSASI)) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: right;">' + format_uang(getValue(jdata.data.dataBlnBkl[i].NOM_BERKALA)) + '</td>';
  							html_data_BlnBkl += '<td style="text-align: center;">' 
                								 + '<a href="javascript:void(0)" onclick="fl_js_jpbklverif_showRincianBerkala(\'' 
                								 + getValue(jdata.data.dataBlnBkl[i].KODE_KLAIM) + '\', \'' 
																 + getValue(jdata.data.dataBlnBkl[i].NO_KONFIRMASI) + '\', \'' 
																 + getValue(jdata.data.dataBlnBkl[i].NO_PROSES) + '\', \''
																 + getValue(jdata.data.dataBlnBkl[i].KD_PRG) + '\', \'' 
                								 + getValue(jdata.data.dataBlnBkl[i].BLTH_PROSES) + '\')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN </font></a>' + '</td>';
  							html_data_BlnBkl += '</tr>';
								
								v_tot_nom_berjalan 		+= parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_BERJALAN),4);
								v_tot_nom_rapel 	 		+= parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_RAPEL),4);
								v_tot_nom_kompensasi 	+= parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_KOMPENSASI),4);
								v_tot_nom_berkala 		+= parseFloat(getValueNumber(jdata.data.dataBlnBkl[i].NOM_BERKALA),4);
  						}
  															
  						if (html_data_BlnBkl == "") {
  							html_data_BlnBkl += '<tr class="nohover-color">';
  							html_data_BlnBkl += '<td colspan="8" style="text-align: center;">-- data tidak ditemukan --</td>';
  							html_data_BlnBkl += '</tr>';
  						}
  						$("#data_list_BlnBkl").html(html_data_BlnBkl);	

							$("#span_tot_berjalan").html(format_uang(v_tot_nom_berjalan));
							$("#span_tot_rapel").html(format_uang(v_tot_nom_rapel));
							$("#span_tot_kompensasi").html(format_uang(v_tot_nom_kompensasi));
							$("#span_tot_berkala").html(format_uang(v_tot_nom_berkala));		
						}		
						//end get data list periode jp berkala -----------------------------
						
						//get data list dokumen --------------------------------------------
						var html_data_Dok = "";
						var v_nama_dokumen = "";
						var v_nama_file = "";
						
						if (jdata.data.dataDok)
						{ 
  						for(var i = 0; i < jdata.data.dataDok.length; i++)
  						{
  							v_nama_dokumen = getValue(jdata.data.dataDok[i].NAMA_DOKUMEN);
              	//v_nama_file = getValue(jdata.data.dataDok[i].NAMA_FILE).substring(0,5);
								v_nama_file = getValue(jdata.data.dataDok[i].NAMA_FILE);
								v_flag_mandatory = getValue(jdata.data.dataDok[i].FLAG_MANDATORY) === "Y" ? "<img src=../../images/file_apply.gif>" : ""; 
								v_status_diserahkan = getValue(jdata.data.dataDok[i].STATUS_DISERAHKAN) === "Y" ? "checked" : ""; 
								v_item_status_diserahkan = '<input type="checkbox" disabled class="cebox" id=dcb_dok_status_diserahkan'+i+' name=dcb_dok_status_diserahkan'+i+' '+v_status_diserahkan+' ';
								
								html_data_Dok += '<tr>';
  							html_data_Dok += '<td style="text-align: center;">' + getValue(jdata.data.dataDok[i].NO_URUT) + '</td>';
								html_data_Dok += '<td style="text-align: left; white-space:pre-wrap; word-wrap:break-word;">' + v_nama_dokumen + '</td>';
								html_data_Dok += '<td style="text-align: center;">' + v_flag_mandatory + '</td>';
								html_data_Dok += '<td style="text-align: right;"></td>';
								html_data_Dok += '<td style="text-align: center;">' + v_item_status_diserahkan + '</td>';
								html_data_Dok += '<td style="text-align: center;">' + getValue(jdata.data.dataDok[i].TGL_DISERAHKAN) + '</td>';
								html_data_Dok += '<td style="text-align: center;">' 
                								 + '<a href="javascript:void(0)" onclick="fl_js_jpbklverif_DownloadDok(\'' 
                								 + getValue(jdata.data.dataDok[i].KODE_KLAIM) + '\', \'' 
																 + getValue(jdata.data.dataDok[i].NO_KONFIRMASI) + '\', \'' 
                								 + getValue(jdata.data.dataDok[i].KODE_DOKUMEN) + '\')"> '+ v_nama_file +'</a>' + '</td>';
																 
								html_data_Dok += '<tr>';								
  						}
  															
  						if (html_data_Dok == "") {
  							html_data_Dok += '<tr class="nohover-color">';
  							html_data_Dok += '<td colspan="7" style="text-align: center;">-- tidak ada persyaratan dokumen --</td>';
  							html_data_Dok += '</tr>';
  						}
  						$("#data_list_Dok").html(html_data_Dok);		
						}		
						//end get data list dokumen ----------------------------------------
						
						//set value data jp berkala siap bayar -----------------------------
						$('#byr_kode_klaim').val(getValue(jdata.data.dataDtlSiapByr['KODE_KLAIM']));
						$('#byr_no_konfirmasi').val(getValue(jdata.data.dataDtlSiapByr['NO_KONFIRMASI']));
						$('#byr_no_proses').val(getValue(jdata.data.dataDtlSiapByr['NO_PROSES']));
						$('#byr_kd_prg').val(getValue(jdata.data.dataDtlSiapByr['KD_PRG']));
						
						$('#byr_ket_blth_proses').val(getValue(jdata.data.dataDtlSiapByr['KET_BLTH_PROSES']));
						$('#byr_blth_proses').val(getValue(jdata.data.dataDtlSiapByr['BLTH_PROSES']));
						
						var v_ket_penerima_berkala = "";
						if (getValue(jdata.data.dataDtlSiapByr['KODE_PENERIMA_BERKALA']) == "JD")
						{
						 	v_ket_penerima_berkala = "JANDA/DUDA"; 
						}else if (getValue(jdata.data.dataDtlSiapByr['KODE_PENERIMA_BERKALA']) == "A1")
						{
						 	v_ket_penerima_berkala = "ANAK PERTAMA"; 
						}else if (getValue(jdata.data.dataDtlSiapByr['KODE_PENERIMA_BERKALA']) == "A2")
						{
						 	v_ket_penerima_berkala = "ANAK KEDUA"; 
						}else if (getValue(jdata.data.dataDtlSiapByr['KODE_PENERIMA_BERKALA']) == "OT")
						{
						 	v_ket_penerima_berkala = "ORANG TUA"; 
						}else
						{
						 	v_ket_penerima_berkala = ""; 	 
						}						
						
						$('#byr_ket_penerima_berkala').val(v_ket_penerima_berkala);
						$('#byr_kode_penerima_berkala').val(getValue(jdata.data.dataDtlSiapByr['KODE_PENERIMA_BERKALA']));
						$('#byr_no_urut_keluarga').val(getValue(jdata.data.dataDtlSiapByr['NO_URUT_KELUARGA']));
						
						$('#byr_nom_berjalan').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_BERJALAN'])));
						$('#byr_nom_rapel').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_RAPEL'])));
						$('#byr_nom_kompensasi').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_KOMPENSASI'])));
						$('#byr_nom_berkala').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_BERKALA'])));
						$('#byr_nom_pph').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_PPH'])));
						$('#byr_kode_pajak_pph').val(getValue(jdata.data.dataDtlSiapByr['KODE_PAJAK_PPH']));
						$('#byr_nom_pembulatan').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_PEMBULATAN'])));
						$('#byr_nom_manfaat_netto').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_MANFAAT_NETTO'])));
						$('#byr_nom_sudah_bayar').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_SUDAH_BAYAR'])));
						$('#byr_nom_sisa').val(format_uang(getValue(jdata.data.dataDtlSiapByr['NOM_SISA'])));
			
						$('#byr_keterangan').val(getValue(jdata.data.dataDtlSiapByr['KETERANGAN']));
						
						$('#byr_nama_penerima').val(getValue(jdata.data.dataDtlSiapByr['NAMA_PENERIMA']));
						$('#byr_nik_penerima').val(getValue(jdata.data.dataDtlSiapByr['NIK_PENERIMA']));
						
						$('#byr_npwp_penerima').val(getValue(jdata.data.dataDtlSiapByr['NPWP_PENERIMA']));
						$('#byr_npwp_penerima_old').val(getValue(jdata.data.dataDtlSiapByr['NPWP_PENERIMA']));
						$('#byr_email_penerima').val(getValue(jdata.data.dataDtlSiapByr['EMAIL_PENERIMA']));
						$('#byr_handphone_penerima').val(getValue(jdata.data.dataDtlSiapByr['HANDPHONE_PENERIMA']));
						
						$('#byr_nama_bank_penerima').val(getValue(jdata.data.dataDtlSiapByr['BANK_PENERIMA']));
						$('#byr_kode_bank_penerima').val(getValue(jdata.data.dataDtlSiapByr['KODE_BANK_PENERIMA']));
						$('#byr_id_bank_penerima').val(getValue(jdata.data.dataDtlSiapByr['ID_BANK_PENERIMA']));
						$('#byr_metode_transfer').val(getValue(jdata.data.dataDtlSiapByr['METODE_TRANSFER']));
						$('#byr_kode_bank_penerima_old').val(getValue(jdata.data.dataDtlSiapByr['KODE_BANK_PENERIMA']));
						
						$('#byr_no_rekening_penerima').val(getValue(jdata.data.dataDtlSiapByr['NO_REKENING_PENERIMA']));
						$('#byr_nama_rekening_penerima').val(getValue(jdata.data.dataDtlSiapByr['NAMA_REKENING_PENERIMA']));
						$('#byr_nama_rekening_penerima_ws').val(getValue(jdata.data.dataDtlSiapByr['NAMA_REKENING_PENERIMA']));
						$('#byr_status_valid_rekening_penerima').val(getValue(jdata.data.dataDtlSiapByr['STATUS_VALID_REKENING_PENERIMA']));
						$('#byr_no_rekening_penerima_old').val(getValue(jdata.data.dataDtlSiapByr['NO_REKENING_PENERIMA']));
						
						if (getValue(jdata.data.dataDtlSiapByr['STATUS_VALID_REKENING_PENERIMA'])=="Y")
						{
						 	window.document.getElementById('cb_byr_valid_rekening').checked = true; 
						}else
						{
						 	window.document.getElementById('cb_byr_valid_rekening').checked = false;
						}
						
						window.document.getElementById("byr_span_entry_bank_pembayar").style.display = 'none';
						window.document.getElementById("byr_span_display_bank_pembayar").style.display = 'block';
						
						$('#byr_nama_bank_pembayar').val(getValue(jdata.data.dataDtlSiapByr['NAMA_BANK_PEMBAYAR']));
						$('#byr_kode_bank_pembayar').val(getValue(jdata.data.dataDtlSiapByr['KODE_BANK_PEMBAYAR']));
						$('#byr_kode_kantor_pembayar').val('<?=$gs_kantor_aktif;?>');
						
            document.getElementById('byr_email_penerima').readOnly = true;
            document.getElementById('byr_email_penerima').style.backgroundColor='#f5f5f5';
            
            document.getElementById('byr_keterangan').readOnly = true;
            document.getElementById('byr_keterangan').style.backgroundColor='#f5f5f5';
            
            document.getElementById('byr_handphone_penerima').readOnly = true;
            document.getElementById('byr_handphone_penerima').style.backgroundColor='#f5f5f5';
            
            document.getElementById('byr_nama_bank_penerima').readOnly = true;
            document.getElementById('byr_nama_bank_penerima').style.backgroundColor='#f5f5f5';
            
            window.document.getElementById("btn_lov_byr_bank_penerima").style.display = 'none';
            
            document.getElementById('byr_no_rekening_penerima').readOnly = true;
            document.getElementById('byr_no_rekening_penerima').style.backgroundColor='#f5f5f5'; 		
            
            document.getElementById('byr_npwp_penerima').readOnly = true;
            document.getElementById('byr_npwp_penerima').style.backgroundColor='#f5f5f5'; 		
            
            window.document.getElementById("byr_span_entry_bank_pembayar").style.display = 'none';
            window.document.getElementById("byr_span_display_bank_pembayar").style.display = 'block';
						//end set value data jp berkala siap bayar -------------------------
						
						//set button -------------------------------------------------------			
						window.document.getElementById("span_button_siapbyr").style.display = 'block';
						window.document.getElementById("span_button_tutup_siapbyr").style.display = 'block';
						window.document.getElementById("span_button_edit_siapbyr").style.display = 'block';
						window.document.getElementById("span_button_saveedit_siapbyr").style.display = 'none';
						window.document.getElementById("span_button_submit_siapbyr").style.display = 'block';		
						//end set button ---------------------------------------------------		
						
						//set informasi similarity nama penerima ---------------------------
						$('#byr_similarity_nama_penerima').val(getValue(jdata.data.dataDtlSiapByr['SIMILARITY_NAMA_PENERIMA']));
						fl_js_jpbklverif_similarity_namapenerima();
						
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
	//end function loadSelectedRecord --------------------------------------------

  function fl_js_jpbklverif_similarity_namapenerima()
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
		
	function fl_js_jpbklverif_showRincianBerkala(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_blth_proses)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalarinci.php?kode_klaim='+v_kode_klaim+'&no_konfirmasi='+v_no_konfirmasi+'&no_proses='+v_no_proses+'&kd_prg='+v_kd_prg+'&blth_proses='+v_blth_proses+'&mid='+c_mid+'','',980,550,'yes');
	}
		
	function fl_js_jpbklverif_DownloadDok(v_kode_klaim, v_no_konfirmasi, v_kode_dokumen)
	{
    let params = 'p=pn5070.php&a=formreg&kode_klaim='+v_kode_klaim+'&no_konfirmasi='+v_no_konfirmasi+'&kode_dokumen='+v_kode_dokumen+'&TASK=DOWNLOAD_DOK';
    NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5065_download_dok.php?' + params,'',1000,620,'no');
	}
	
	//do edit data siap bayar ----------------------------------------------------
  function fjq_ajax_val_jpbklverif_doEditSiapBayar()
  {				 
		var v_npwp = $('#byr_npwp_penerima').val();
		var v_kode_bank_pembayar = $('#byr_kode_bank_pembayar').val();
		
		document.getElementById('byr_email_penerima').readOnly = false;
    document.getElementById('byr_email_penerima').style.backgroundColor='#ffffff';

		document.getElementById('byr_keterangan').readOnly = false;
    document.getElementById('byr_keterangan').style.backgroundColor='#ffffff';
				
    document.getElementById('byr_handphone_penerima').readOnly = false;
    document.getElementById('byr_handphone_penerima').style.backgroundColor='#ffffff';
    
    document.getElementById('byr_nama_bank_penerima').readOnly = true;
    document.getElementById('byr_nama_bank_penerima').style.backgroundColor='#ffff99';
    
    window.document.getElementById("btn_lov_byr_bank_penerima").style.display = '';
    
    document.getElementById('byr_no_rekening_penerima').readOnly = false;
    document.getElementById('byr_no_rekening_penerima').style.backgroundColor='#ffff99'; 		
						
    document.getElementById('byr_npwp_penerima').readOnly = false;
    document.getElementById('byr_npwp_penerima').style.backgroundColor='#ffff99'; 		
		
		window.document.getElementById("byr_span_entry_bank_pembayar").style.display = 'block';
		window.document.getElementById("byr_span_display_bank_pembayar").style.display = 'none';
		
		window.document.getElementById("span_button_edit_siapbyr").style.display = 'none';
		window.document.getElementById("span_button_submit_siapbyr").style.display = 'none';
		window.document.getElementById("span_button_tutup_siapbyr").style.display = 'none';
		window.document.getElementById("span_button_saveedit_siapbyr").style.display = 'block';
		
		//get list bank pembayar ------------------------------------------
		fjq_ajax_val_jpbklverif_getlist_bank_pembayar_entry(v_kode_bank_pembayar);
			 				 
  }
  //end do edit data siap bayar ------------------------------------------------

	function fjq_ajax_val_getlist_bank_asal()
	{	 
		//dipanggil saat pilih bank penerima -------	
		fjq_ajax_val_jpbklverif_getlist_bank_pembayar_entry('');	 
	}
		
	//ambil bank pembayar --------------------------------------------------------
	function fjq_ajax_val_jpbklverif_getlist_bank_pembayar_entry(v_curr_bank_selected)
	{
		v_jenis_transaksi = "KLAIM";
    v_id_transaksi 		= $('#kode_klaim').val();
    v_kode_bank_atb		= $('#byr_kode_bank_penerima').val();
		v_kode_cara_bayar	= "B"; //$('#byr_kode_cara_bayar').val();
		v_bank_selected		= v_curr_bank_selected;
		
		//reset list terlebih dahulu -----------------------------------------------
		fjq_ajax_val_jpbklverif_resetlist_bank_pembayar('byr_kode_bank_pembayar_entry');
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
		}else
		{
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
	
	function fl_js_jpbklverif_reset_norek() 
  {	 
    $('#byr_no_rekening_penerima').val('');
		$('#byr_no_rekening_penerima_old').val('');
    $('#byr_nama_rekening_penerima_ws').val('');
    $('#byr_status_valid_rekening_penerima').val('T');
    //$('#byr_kode_bank_pembayar').val('');
    $('#byr_no_rekening_penerima').focus();
    window.document.getElementById('cb_byr_valid_rekening').checked = false;
  }

  function fl_js_jpbklverif_get_lov_bank_penerima()
  {			 					
		fl_js_jpbklverif_reset_norek();
		NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_lov_bankpenerima.php?p=pn5043.php&a=formreg&b=byr_nama_bank_penerima&c=byr_kode_bank_penerima&d=byr_id_bank_penerima','',800,500,1); 
	}

	function fjq_ajax_val_jpbklverif_resetlist_bank_pembayar(id)
	{
  	var selectObj = document.getElementById(id);
  	var selectParentNode = selectObj.parentNode;
  	var newSelectObj = selectObj.cloneNode(false); // Make a shallow copy
  	selectParentNode.replaceChild(newSelectObj, selectObj);
  	return newSelectObj;
	}

  //validasi nomor rekening penerima -------------------------------------------
  function fjq_ajax_val_jpbklverif_no_rekening_penerima()
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
  function fjq_ajax_val_jpbklverif_doSaveEditSiapBayar()
  {				 
			var v_kode_klaim				 			= $('#byr_kode_klaim').val();
			var v_no_konfirmasi						= $('#byr_no_konfirmasi').val();
      var v_no_proses 							= $('#byr_no_proses').val();
			var v_kd_prg									= $('#byr_kd_prg').val();
			var v_kode_penerima_berkala		= $('#byr_kode_penerima_berkala').val();
			var v_no_urut_keluarga				= $('#byr_no_urut_keluarga').val();
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
    	var v_kode_cara_bayar					= 'B'; // transfer
			
      if (v_kode_klaim == '' || v_no_konfirmasi=='' || v_no_proses=='' || v_kd_prg==''){
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
      }else
      {
        preload(true);
        $.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_action.php?'+Math.random(),
          data: { tipe:'fjq_ajax_val_byr_penerima_save_update_jpberkala',
    						v_kode_klaim				 			: v_kode_klaim,
                v_kode_penerima_berkala 	: v_kode_penerima_berkala,
								v_no_urut_keluarga 				: v_no_urut_keluarga,
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
							fl_js_jpbklverif_loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, null);												
            }else{
              //simpan ubah penerimaan manfaat gagal ---------------------------
              alert(jdata.msg);
            }
          }
        });//end ajax
      }//end if
  }
  //end do simpan perubahan data siap bayar ------------------------------------

	function fjq_ajax_val_jpbklverif_doCancelEditSiapBayar()
	{
    var v_kode_klaim				 			= $('#byr_kode_klaim').val();
    var v_no_konfirmasi						= $('#byr_no_konfirmasi').val();
    var v_no_proses 							= $('#byr_no_proses').val();
    var v_kd_prg									= $('#byr_kd_prg').val();
			
		fl_js_jpbklverif_loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, null);					 
	}
	
	//do submit data siap bayar --------------------------------------------------
  function fjq_ajax_val_jpbklverif_doSubmitSiapBayar()
  {				 
    	var v_kode_klaim				 			= $('#byr_kode_klaim').val();
			var v_no_konfirmasi						= $('#byr_no_konfirmasi').val();
      var v_no_proses 							= $('#byr_no_proses').val();
			var v_kd_prg									= $('#byr_kd_prg').val();
			var v_kode_penerima_berkala		= $('#byr_kode_penerima_berkala').val();
			var v_no_urut_keluarga				= $('#byr_no_urut_keluarga').val();
      var v_handphone								= $('#byr_handphone_penerima').val();
      var v_email										= $('#byr_email_penerima').val();
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
      var v_keterangan							= $('#byr_keterangan').val();
			
			var v_npwp										= $('#byr_npwp_penerima').val();
			var v_npwp_old								= $('#byr_npwp_penerima_old').val();
    	var v_kode_cara_bayar					= 'B'; // transfer
			var v_status_rekening_sentral	= "Y"; //pembayaran melalui sentralisasi rekening
			var v_kantor_siapbayar 				= $('#byr_kode_kantor_pembayar').val();
			
      if (v_kode_klaim == '' || v_no_konfirmasi=='' || v_no_proses=='' || v_kd_prg==''){
      	alert('Data penerima manfaat tidak ditemukan, harap perhatikan data input..!!!');
			}else if (v_kode_cara_bayar == '')
			{
				alert('Cara Bayar kosong, harap perbaiki data input..!!!');					 								 				 
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
          data: { tipe:'fjq_ajax_val_byr_doSiapTransferJPBerkala',
    						v_kode_klaim				: v_kode_klaim,
                v_no_konfirmasi 		: v_no_konfirmasi,
								v_no_proses 				: v_no_proses,
                v_kd_prg						: v_kd_prg,
								v_kantor_siapbayar	: v_kantor_siapbayar
    			},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
            if(jdata.ret=="0")
            {   
              //simpan ubah penerimaan manfaat berhasil, reload data grid siap bayar jp berkala --
              alert(jdata.msg);
							fl_js_reloadPage();														
            }else{
              //simpan ubah penerimaan manfaat gagal ---------------------------
              alert(jdata.msg);
            }
          }
        });//end ajax
      }//end if
  }
  //end do submit data siap bayar ----------------------------------------------
	
	function fl_js_jpbklverif_cetakSiapBayar()
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_cetak_siapbayar.php?&mid='+c_mid+'','',900,620,'yes');
	}			
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
			let no_konfirmasi = $('#no_konfirmasi').val();
			let no_proses = $('#no_proses').val();
			let kd_prg = $('#kd_prg').val();
			
			fl_js_jpbklverif_loadSelectedRecord(kode_klaim, no_konfirmasi, no_proses, kd_prg, null);
						
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
		 	$('#editid').val('');
			$('#kode_klaim').val('');
			$('#no_konfirmasi').val('');
			$('#no_proses').val('');
			$('#kd_prg').val('');
			
			fl_js_jpbklverif_filter();	 
		}
	});
</script>
<script>
function confirm_edit() {
  //if(confirm('Apakah anda yakin akan melakukan Edit Data Siap Bayar..?')) fjq_ajax_val_jpbklverif_doEditSiapBayar();
  let content = 'Apakah anda yakin akan melakukan Edit Data Siap Bayar..?'
  window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
    if (btn=='yes') {
      fjq_ajax_val_jpbklverif_doEditSiapBayar()
    }
  });
}

function confirm_saveedit() {
  //if(confirm('Apakah anda yakin akan menyimpan perubahan Data Siap Bayar..?')) fjq_ajax_val_jpbklverif_doSaveEditSiapBayar();
  let content = 'Apakah anda yakin akan menyimpan perubahan Data Siap Bayar..?<br><br>'
              + 'Penerima manfaat: ' + $('#byr_nama_penerima').val() + ' [' + $('#byr_similarity_nama_penerima').val() + '%]<br>'
              + 'Atas nama rekening: ' + $('#byr_nama_rekening_penerima_ws').val() + ' - ' + $('#byr_nama_bank_penerima').val()
  window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
    if (btn=='yes') {
      fjq_ajax_val_jpbklverif_doSaveEditSiapBayar()
    }
  });
}
function confirm_cancel() {
  //if(confirm('Apakah anda yakin akan membatalkan perubahan Data Siap Bayar..?')) fjq_ajax_val_jpbklverif_doCancelEditSiapBayar();
  let content = 'Apakah anda yakin akan membatalkan perubahan Data Siap Bayar..?'
  window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
    if (btn=='yes') {
      fjq_ajax_val_jpbklverif_doCancelEditSiapBayar()
    }
  });
}
function confirm_submit() {
  //if(confirm('Apakah anda yakin akan melakukan Submit Data Siap Bayar..?')) fjq_ajax_val_jpbklverif_doSubmitSiapBayar();
  let content = 'Apakah Anda yakin akan melakukan Submit Data Siap Bayar?<br><br>'
              + 'Penerima manfaat: ' + $('#byr_nama_penerima').val() + ' [' + $('#byr_similarity_nama_penerima').val() + '%]<br>'
              + 'Atas nama rekening: ' + $('#byr_nama_rekening_penerima_ws').val() + ' - ' + $('#byr_nama_bank_penerima').val()
  window.parent.Ext.MessageBox.confirm('Konfirmasi', content, function(btn) {
    if (btn=='yes') {
      fjq_ajax_val_jpbklverif_doSubmitSiapBayar()
    }
  });
}


</script>