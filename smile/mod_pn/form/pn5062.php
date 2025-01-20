<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include_once '../../includes/fungsi_newrpt.php';
//------------------------------------------------------------------------------
// Menu untuk approval penetapan klaim untuk kantor yg sudah sentralisasi
// dibuat tgl : 22/09/2020
//------------------------------------------------------------------------------
//set parameter ----------------------------------------------------------------
$pagetype 									= "form";
$gs_kodeform 					 			= "PN5003";
$chId 	 	 			 			 			= "SMILE";
$gs_pagetitle 				 			= "PERSETUJUAN KLAIM";												 
$gs_kantor_aktif			 			= $_SESSION['kdkantorrole'];
$gs_kode_user					 			= $_SESSION["USER"];
$gs_kode_role					 			= $_SESSION['regrole'];
$task 								 			= $_POST["task"];
$editid 							 			= $_POST['editid'];
$ls_kode_klaim 				 			= $_POST['kode_klaim'];
$ln_no_level 								= $_POST['no_level'];
$ls_status_rekening_sentral	= "Y";
$ls_rg_kategori 						= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
$ls_tmp_rg_kategori 				= !isset($_POST['tmp_rg_kategori']) ? $_GET['tmp_rg_kategori'] : $_POST['tmp_rg_kategori'];
$ls_activetab  				 			= $_POST['activetab'];
$ls_activetab 							= $ls_activetab=="" ? "2" : $ls_activetab;
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
 
<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
		
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>

<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_kodeform;?> - <?= $gs_pagetitle;?></span></div>	
		<div class="item" style="float: right; padding: 2px;">		
      <input type="radio" id="rg_kategori_jht" name="rg_kategori" value="JHT" onclick="filter();"><span id='rg_kategori_jht_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">JHT</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jhm" name="rg_kategori" value="JHM" onclick="filter();"><span id='rg_kategori_jhm_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">JHT/JKM</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jkk" name="rg_kategori" value="JKK" onclick="filter();"><span id='rg_kategori_jkk_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">JKK</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jkm" name="rg_kategori" value="JKM" onclick="filter();"><span id='rg_kategori_jkm_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">JKM</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jpn" name="rg_kategori" value="JPN" onclick="filter();"><span id='rg_kategori_jpn_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">JP</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	  <input type="radio" id="rg_kategori_jkp" name="rg_kategori" value="JKP" onclick="filter();"><span id='rg_kategori_jkp_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">JKP</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_all" name="rg_kategori" value="ALL" onclick="filter();" checked><span id='rg_kategori_all_label'>&nbsp;<span style="color:#ffffff; size:2px; font-style:Verdana;">SEMUA DATA</span></span>
		</div>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div class="div-header-form">
	</div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="order_by" name="order_by" value="">
      <input type="hidden" id="order_type" name="order_type" value="">
      <input type="hidden" id="tipe" name="tipe" value="">
      <input type="hidden" id="task_detil" name="task_detil" value="<?=$task_detil;?>">
      <input type="hidden" id="tmp_rg_kategori" name="tmp_rg_kategori" value="<?=$ls_tmp_rg_kategori;?>">
			<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
			
			<?php
			if ($task == "") 
			{
				//datagrid -------------------------------------------------------------
				?>
				<div id="div_container" class="div-container">
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
									<select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="filter()">
									<option value="10">10</option>
									<option value="15">15</option>
									<option value="20">20</option>
									<option value="25">25</option>
									<option value="50">50</option>
									</select>
									<span style="vertical-align: middle;" >item per halaman</span>
								</div>							
								<div class="div-col-right" style="padding: 2px;">
									<a class="a-icon-text" href="#" onclick="filter();" title="Klik Untuk Menampilkan Data">
										<img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
										<span>Tampilkan</span>
									</a>
								</div>
								
								<div class="div-col-right" style="padding: 2px;">
									<select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="search_by2_changed();">
										<option value="">Keyword Lain</option>
										<option value="">----------------</option>
										<option value="KODE_SEBAB_KLAIM">Sebab Klaim</option>
										<option value="KODE_SEGMEN">Segmen Kepesertaan</option>
										<option value="JENIS_KANAL_PELAYANAN">Jenis Layanan</option>                  
									</select>	            
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
                      <option value="SIPP">SIPP</option>
					  <option value="ONLINE_JMO">ONLINE JMO</option>
					  <option value="EKLAIM PMI JMO">EKLAIM PMI JMO</option>
                    </select>							
                  </span>																										
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<input type="text" name="search_txt" id="search_txt" placeholder="Keyword.." style="border:0;width: 135px;height:18px;">
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<select name="search_by" id="search_by" style="border:0;width: 110px;height:18px;" onchange="search_by_changed()">
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
								<table aria-describedby="div_data" class="table-data">
									<thead>
										<tr class="hr-single-double">
											<th style="text-align: left;">
												<a href="#" order_by="ACTION" order_type="DESC" onclick="orderby(this)">Action
													<img alt="action" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>										
											<th style="text-align: left;">
												<a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="orderby(this)">Kode Klaim
													<img alt="Kode Klaim" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="TGL_TRANS" order_type="DESC" onclick="orderby(this)">Tgl Klaim
													<img alt="Tgl Klaim" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="TGL_TRANS" order_type="DESC" onclick="orderby(this)">Tgl Penetapan
													<img alt="Tgl Penetapan" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>											
											<th style="text-align: left;">
												<a href="#" order_by="KPJ" order_type="DESC" onclick="orderby(this)">No. Referensi
													<img alt="KPJ" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="NAMA_PENGAMBIL_KLAIM" order_type="DESC" onclick="orderby(this)">Nama
													<img alt="Nama" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="KET_TIPE_KLAIM" order_type="DESC" onclick="orderby(this)">Tipe
													<img alt="Tipe" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KODE_SEGMEN" order_type="DESC" onclick="orderby(this)">Segmen
													<img alt="Segmen" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="orderby(this)">Ktr
													<img alt="Kantor" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="JENIS_KANAL_PELAYANAN" order_type="DESC" onclick="orderby(this)">Jenis Layanan
													<img alt="Jenis Layanan" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>								
										</tr>		 
									</thead>	
									<tbody id="data_list">
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
									<a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="filter('-02')"><<</a>
									<a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="filter('-01')">Prev</a>
									<input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="filter(this.value);"/>
									<a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="filter('01')">Next</a>
									<a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="filter('02')">>></a>
									<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
									<input type="hidden" id="pages">
								</div>
								<div class="div-col-right">
									<input type="hidden" id="hdn_total_records" name="hdn_total_records">
									<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>							
								</div>
							</div>						
						</div>
							 
					</div>
					<div id="div_footer" class="div-footer">
						<div class="div-footer-content">
							<div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
							<ul>
								<li style="margin-left:15px;">Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <span color="#ff0000"> Tampilkan</span> untuk memulai pencarian data.</li>
								<li style="margin-left:15px;">Data dapat ditampilkan per jenis klaim <span color="#ff0000"> JHT, JHT/JKM, JKK, JKM, JP ataupun semua data</span>.</li>
							<ul>
						</div>
					</div>
				</div>				
				<?
				//end datagrid ---------------------------------------------------------
			}else
			{
				//action task view -----------------------------------------------------
				?>
				<div id="div_container" class="div-container">
					<span id="span_approval" style="display:block;">	 
  					<div id="div_body" class="div-body">
  						<ul id="nav" style="width:99%;">					
                <li><a href="#tab1" id="tabid1" style="display:block"><span style="vertical-align: middle;" id="span_judul_tab1"></span></a></li>	
                <li><a href="#tab2" id="tabid2" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab2"></span></a></li>	
                <li><a href="#tab3" id="tabid3" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab3"></span></a></li>
  							<li><a href="#tab4" id="tabid4" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab4"></span></a></li>
  							<li><a href="#tab5" id="tabid5" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab5"></span></a></li>
  							<li><a href="#tab6" id="tabid6" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab6"></span></a></li>
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
  								<div id="formtab2a"></div>
  								<div id="formtab2b"></div>
  								<div id="formtab2c"></div>
  								<div id="formtab2d"></div>
  								<div id="formtab2e"></div>	 
  							</div>	 
  						</div>
  						
  						<div style="display: none;" id="tab3" class="tab_konten">
  							<div id="konten" style="width:98%;">
  								<div id="formtab3a"></div>
  								<div id="formtab3b"></div>
  								<div id="formtab3c"></div>
  								<div id="formtab3d"></div>
  								<div id="formtab3e"></div>	 
  							</div>	 
  						</div>
  						
  						<div style="display: none;" id="tab4" class="tab_konten">
  							<div id="konten" style="width:98%;">
  								<div id="formtab4a"></div>
  								<div id="formtab4b"></div>
  								<div id="formtab4c"></div>
  								<div id="formtab4d"></div>
  								<div id="formtab4e"></div>
  							</div>	 
  						</div>
  						
  						<div style="display: none;" id="tab5" class="tab_konten">
  							<div id="konten" style="width:98%;">
  								<div id="formtab5a"></div>
  								<div id="formtab5b"></div>
  								<div id="formtab5c"></div>
  								<div id="formtab5d"></div>
  								<div id="formtab5e"></div>
  							</div>	 
  						</div>
  						
  						<div style="display: none;" id="tab6" class="tab_konten">
  							<div id="konten" style="width:98%;">
  								<div id="formtab6a"></div>
  								<div id="formtab6b"></div>
  								<div id="formtab6c"></div>
  								<div id="formtab6d"></div>
  								<div id="formtab6e"></div>
  							</div>	 
  						</div>																																					
  					</div>
  					
  					<div id="div_footer" class="div-footer">
							<span id="span_disclaimer" style="display:none;">
                <div style="padding-top:6px;width:99%;">
      						<div class="div-footer-content">					
      							<div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
											<input type="checkbox" id="flag_disclaimer" name="flag_disclaimer" onClick="fl_js_onclick_disclaimer();">
                      <strong style="font-size: 12px;">Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload</strong>
											&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									    <a id="btn_doBack2Grid" href="#" onClick="fl_js_doBack2Grid();">
      									<img alt="Tutup" src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
      									<span>TUTUP</span>
      								</a>
									</div>
                </div>																
							</span>
								 
  						<span id="span_button_utama" style="display:none;">																	 		 
    						<div class="div-footer-form">							
    							<div class="div-row">               
										<span id="span_button_approval" style="display:none;">
                      <div class="div-col">
                        <div class="div-action-footer">
                          <div class="icon">
      											<a id="btntaptolak" href="#" onClick="fl_js_doTolak();">
                              <img alt="Tolak" src="../../images/ico_penolakan.jpg" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                              <span>TOLAK DAN KEMBALIKAN KE TAHAP SEBELUMNYA &nbsp;&nbsp;</span>
                          	</a>
                          </div>
                        </div>
                      </div>
                    
                      <div class="div-col">
                        <div class="div-action-footer">
                          <div class="icon">
														<!--<a id="btnapvsubmit" href="#" onClick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) showForm('../ajax/pn5062_approval_submit.php?kode_klaim=<?=$ls_kode_klaim;?>&no_level=<?=$ln_no_level;?>','',300,50,'no');">-->
														<a id="btnapvsubmit" href="#" onClick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) fl_js_dosubmit_approval();">
                              <img alt="Submit" src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                              <span id="span_btnapvsubmit">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                            </a>
                          </div>
                        </div>
                      </div>															
                    </span>
  									
										<span id="span_button_tutup" style="display:none;">
      								<div class="div-col">
      									<div class="div-action-footer">
      										<div class="icon">
      											<a id="btn_doBack2Grid" href="#" onClick="fl_js_doBack2Grid();">
      												<img alt="Tutup" src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:28px;"/>
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
      							<div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
											Klik tombol <span color="#ff0000"> SUBMIT </span> untuk melanjutkan data ke tahap selanjutnya. Untuk mengembalikan data ke tahap sebelumnya maka dapat dilakukan dengan mengklik tombol <span color="#ff0000">TOLAK</span>.
      						</div>
                </div>
  						</span><!--end span_button_utama-->					
  					</div>
						<!--end div_footer-->
					</span>
					
					<span id="span_penolakan" style="display:none;">
            <div class="div-row">
            	<div class="div-col" style="width:100%; max-height: 100%;">			
              	</br>	 		
                <fieldset><legend>Tolak dan Kembalikan ke Penetapan Klaim</legend>
                	</br>		
              				
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Alasan Penolakan &nbsp; *</label>
                  <textarea cols="255" rows="2" style="width:500px;background-color:#ffff99" id="alasan_penolakan" name="alasan_penolakan" tabindex="1" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_alasan_penolakan;?></textarea>   					
                  </div>														
                  <div class="clear"></div>
                	
              		</br></br>
                </fieldset>	
                
                <div id="buttonbox" style="width:98%;text-align:center;">
              		<a href="#" onClick="if(confirm('Apakah anda yakin akan melakukan penolakan dan pengembalian data ke PENETAPAN KLAIM ..?')) fjq_ajax_val_save_penolakan();"><img src="../../images/ico_penolakan.jpg" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>&nbsp;<strong>TOLAK DAN KEMBALIKAN KE TAHAP PENETAPAN</strong></a> &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;
                  <a href="#" onClick="fl_js_reloadFormEntry();"><img src="../../images/ico_close.jpg" border="0" alt="Tambah" align="absmiddle" style="height:32px;"/>&nbsp;<strong>TUTUP</strong></a>
              	</div>
            
                <div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;width:90%;">
                <span style="background: #FF0; border: 1px solid #CCC;"><em><strong>Keterangan:</strong></em></span>
                  <ul><li style="margin-left:15px;">Penolakan data penetapan ini akan mengembalikan data ke PENETAPAN KLAIM. Isikan <span color="#ff0000"> Alasan Penolakan </span> kemudian klik tombol <span color="#ff0000"> TOLAK </span>.</li></ul>
                </div>
            
            	</div>	
            </div>											
					</span> 
				</div>				
				<?
				//end action task view -------------------------------------------------
			}
			?>
		</form>
	</form>
</div>

<?php
include_once "../ajax/pn5062_js.php";
include_once "../../includes/footer_app_nosql.php";
?>

