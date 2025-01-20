<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include_once '../../includes/fungsi_newrpt.php';
//------------------------------------------------------------------------------
// Menu untuk pembatalan klaim
// dibuat tgl : 17/02/2021
//------------------------------------------------------------------------------
//set parameter ----------------------------------------------------------------
$pagetype 							= "form";
$gs_kodeform 						= "PN5005";
$chId 	 	 			 				= "SMILE";
$gs_pagetitle 					= "PEMBATALAN KLAIM";												 
$gs_kantor_aktif				= $_SESSION['kdkantorrole'];
$gs_kode_user						= $_SESSION["USER"];
$gs_kode_role						= $_SESSION['regrole'];
$task 									= $_POST["task"];
$editid 								= $_POST['editid'];
$ls_kode_klaim 					= $_POST['kode_klaim'];
$ls_rg_kategori 				= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
$ls_tmp_rg_kategori 		= !isset($_POST['tmp_rg_kategori']) ? $_GET['tmp_rg_kategori'] : $_POST['tmp_rg_kategori'];
$ls_activetab  					= $_POST['activetab'];
$ls_activetab 					= $ls_activetab=="" ? "2" : $ls_activetab;
$ld_tmp_tglawaldisplay 	= !isset($_POST['tmp_tglawaldisplay']) ? $_GET['tmp_tglawaldisplay'] : $_POST['tmp_tglawaldisplay'];
$ld_tmp_tglakhirdisplay = !isset($_POST['tmp_tglakhirdisplay']) ? $_GET['tmp_tglakhirdisplay'] : $_POST['tmp_tglakhirdisplay'];

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
      <input type="radio" id="rg_kategori_jht" name="rg_kategori" value="JHT" onclick="filter();"><span id='rg_kategori_jht_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">JHT</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jhm" name="rg_kategori" value="JHM" onclick="filter();"><span id='rg_kategori_jhm_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">JHT/JKM</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jkk" name="rg_kategori" value="JKK" onclick="filter();"><span id='rg_kategori_jkk_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">JKK</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jkm" name="rg_kategori" value="JKM" onclick="filter();"><span id='rg_kategori_jkm_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">JKM</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jpn" name="rg_kategori" value="JPN" onclick="filter();"><span id='rg_kategori_jpn_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">JP</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_jkp" name="rg_kategori" value="JKP" onclick="filter();"><span id='rg_kategori_jkp_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">JKP</span> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
      <input type="radio" id="rg_kategori_all" name="rg_kategori" value="ALL" onclick="filter();" checked><span id='rg_kategori_all_label'>&nbsp;<span style="color:#ffffff; size:2px; font-family:sans-serif;">SEMUA DATA</span></span>
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
			<input type="hidden" id="tmp_tglawaldisplay" name="tmp_tglawaldisplay" value="<?=$ld_tmp_tglawaldisplay;?>">
			<input type="hidden" id="tmp_tglakhirdisplay" name="tmp_tglakhirdisplay" value="<?=$ld_tmp_tglakhirdisplay;?>">
			<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">

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
									Tgl Klaim/Penetapan Ulang :&nbsp;
									<input type="text" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>" onblur="convert_date(tglawaldisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglawaldisplay', 'dd-mm-y');">  
									&nbsp;s/d&nbsp;
									<input type="text" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>" onblur="convert_date(tglakhirdisplay)" style="border:0;text-align:center;width:75px;height:18px;" onClick="return showCalendar('tglakhirdisplay', 'dd-mm-y');">
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
					  <option value="JMO">JMO</option>
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
													<img alt="Action" class="order-icon" src="../../images/sort_both.png">
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
											<th style="text-align: left;">
												<a href="#" order_by="KODE_SEGMEN" order_type="DESC" onclick="orderby(this)">Segmen
													<img alt="Segmen" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="orderby(this)">Ktr
													<img alt="Ktr" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="STATUS_KLAIM" order_type="DESC" onclick="orderby(this)">Status
													<img alt="Status" class="order-icon" src="../../images/sort_both.png">
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
									&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;
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
							</div>						
						</div>
							 
					</div>
					<div id="div_footer" class="div-footer">
						<div class="div-footer-content">
							<div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
							<ul>
								<li style="margin-left:15px;">Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <span style="color:#ff0000"> Tampilkan</span> untuk memulai pencarian data.</li>
								<li style="margin-left:15px;">Data dapat ditampilkan per jenis klaim <span style="color:#ff0000"> JHT, JHT/JKM, JKK, JKM, JP ataupun semua data</span>.</li>
							</ul>	
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
					<div id="div_body" class="div-body">
						<ul id="nav" style="width:99%;">					
              <li><a href="#tab1" id="tabid1" style="display:block"><span style="vertical-align: middle;" id="span_judul_tab1"></span></a></li>	
              <li><a href="#tab2" id="tabid2" style="display:none"><span style="vertical-align: middle;" id="span_judul_tab2"></span></a></li>
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
								<div id="formtab1k"></div>
								<div id="formtab1l"></div>
								<div id="formtab1m"></div>
								<div id="formtab1n"></div>
							</div>	 
						</div>	
						
						<div style="display: none;" id="tab2" class="tab_konten">
							<div id="konten" style="width:98%;">
                <div class="div-row">
                	<div class="div-col" style="width:100%; max-height: 100%;">					
                    <fieldset><legend>Pembatalan Klaim</legend>
                    	</br>		
                  				
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Alasan Pembatalan &nbsp; *</label>
                      <textarea cols="255" rows="2" style="width:500px;background-color:#ffff99" id="ket_batal" name="ket_batal" tabindex="1" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" onkeyup="this.value = this.value.toUpperCase();" ></textarea>   					
                      </div>														
                      <div class="clear"></div>
                    	
                  		</br>
                    </fieldset>	              
                	</div>	
                </div>	 
							</div>	 
						</div>						 
					</div>	

					<div id="div_footer" class="div-footer">
						<span id="span_button_utama" style="display:none;">																	 		 
  						<div class="div-footer-form">							
  							<div class="div-row">               
									<span id="span_button_pembatalan" style="display:none;">                  
                    <div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
													<a id="btnDoBatal" href="#" onClick="if(confirm('Apakah anda yakin akan membatalkan data klaim..?')) fl_js_doBatalKlaim();">
                            <img src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                            <span>BATAL KLAIM &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
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
    							<div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
									Klik tombol <span style="color:#ff0000"> BATAL KLAIM </span> untuk melakukan pembatalan klaim.
    						</div>
              </div>
						</span><!--end span_button_utama-->					
					</div>
					<!--end div_footer-->					 
				</div>
				<?
				//end action task view -------------------------------------------------
			}
			?>											
		</form>
	</div>
</div>

<?php
include_once "../ajax/pn5064_js.php";
include_once "../../includes/footer_app_nosql.php";
?>
	