<?php
//------------------------------------------------------------------------------
// Menu untuk daftar klaim (sentralisasi rekening)
// dibuat tgl : 20/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include_once '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 							= "form";
$gs_kodeform 						= "PN5030";
$chId 	 	 							= "SMILE";
$gs_pagetitle 					= "DAFTAR KLAIM";											 
$gs_kantor_aktif				= $_SESSION['kdkantorrole'];
$gs_kode_user						= $_SESSION["USER"];
$gs_kode_role						= $_SESSION['regrole'];
$task 									= $_POST["task"];
$task_detil							= $_POST["task_detil"];
$editid 								= $_POST['editid'];
$ls_kode_klaim 					= $_POST['kode_klaim'];
$ld_tmp_tglawaldisplay 	= $_POST['tmp_tglawaldisplay'];
$ld_tmp_tglakhirdisplay = $_POST['tmp_tglakhirdisplay'];
$ls_tmp_rg_kategori 		= $_POST['tmp_rg_kategori'];
$ls_activetab  					= $_POST['activetab'] == "" ? "1" : $_POST['activetab'];
$ld_tglawaldisplay 			= !isset($_POST['tglawaldisplay']) ? $_GET['tglawaldisplay'] : $_POST['tglawaldisplay'];
$ld_tglakhirdisplay 		= !isset($_POST['tglakhirdisplay']) ? $_GET['tglakhirdisplay'] : $_POST['tglakhirdisplay'];
$ls_rg_kategori					= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
$ls_rg_kategori					=	$ls_rg_kategori == "" ? "1" : $ls_rg_kategori;
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_kodeform;?> - <?= $gs_pagetitle;?></span></div>
  	<?php
  	if ($task == "") 
  	{
  		?>	
  		<div class="item" style="float: right; padding: 2px;">
  			<?
        switch($ls_rg_kategori)
        {
          case '1' : $sel1="checked"; break;
          case '2' : $sel2="checked"; break;
          case '3' : $sel3="checked"; break;
        }
        ?>
        <input type="radio" name="rg_kategori" value="1" onclick="filter();"  <?=$sel1;?>>&nbsp;<span  style="color:#ffffff;">PENETAPAN KANTOR CABANG</span>&nbsp;
        <input type="radio" name="rg_kategori" value="2" onclick="filter();"  <?=$sel2;?>>&nbsp;<span  style="color:#ffffff;">KANTOR CABANG LAIN</span> &nbsp;
        <input type="radio" name="rg_kategori" value="3" onclick="filter();"  <?=$sel3;?>>&nbsp;<span  style="color:#ffffff;">SEMUA KANTOR</span>				
  		</div>	
			<?
		}
		?>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="order_by" name="order_by" value="">
			<input type="hidden" id="order_type" name="order_type" value="">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="task_detil" name="task_detil" value="<?=$task_detil;?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
			<input type="hidden" id="tmp_tglawaldisplay" name="tmp_tglawaldisplay" value="<?=$ld_tmp_tglawaldisplay;?>">
      <input type="hidden" id="tmp_tglakhirdisplay" name="tmp_tglakhirdisplay" value="<?=$ld_tmp_tglakhirdisplay;?>">
      <input type="hidden" id="tmp_rg_kategori" name="tmp_rg_kategori" value="<?=$ls_tmp_rg_kategori;?>">
			
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
									Tanggal :&nbsp;
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
										<option value="KODE_TIPE_KLAIM">Tipe Klaim</option> 
										<option value="KODE_SEBAB_KLAIM">Sebab Klaim</option>
										<option value="KODE_SEGMEN">Segmen Kepesertaan</option> 
										<option value="STATUS_KLAIM">Status Klaim</option>
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
									<span id="STATUS_KLAIM" hidden="">
										<select size="1" id="search_txt2d" name="search_txt2d" value="" style="border:0;width: 110px;height:18px;">
											<option value="">-- Pilih --</option>
										</select>							
									</span>
                  <span id="JENIS_KANAL_PELAYANAN" hidden="">
                    <select size="1" id="search_txt2e" name="search_txt2e" value="" class="select_format" style="width:100px;">
						<option value="">-- Pilih --</option>
						<option value="MANUAL">MANUAL</option>
						<option value="BPJSTKU">BPJSTKU</option>
						<option value="ANTOL">ANTOL</option>
						<option value="ONLINE">ONLINE</option>
						<option value="ONSITE_WA">ONSITE WA</option>
						<option value="ONSITE_WEB">ONSITE WEB</option>
						<option value="JMO">JMO</option>
						<option value="SIAPKERJA">SIAP KERJA</option>
						<option value="PLKK">PLKK</option>
						<option value="EKLAIM PMI">EKLAIM PMI</option>
                        <option value="SIPP">SIPP</option>
						<option value="ONLINE JMO">ONLINE JMO</option>
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
										<!--<option value="NAMA_PENGAMBIL_KLAIM">Nama</option>-->
										<option value="KODE_KLAIM_PERTAMA">Kode Klaim Pertama</option> 
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
												<a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="orderby(this)">Kode Klaim
													<img alt="Kode Klaim" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="TGL_TRANS" order_type="DESC" onclick="orderby(this)">Tanggal
													<img alt="Tanggal" class="order-icon" src="../../images/sort_both.png">
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
												<a href="#" order_by="TGL_KEJADIAN" order_type="DESC" onclick="orderby(this)">Tgl Kejadian
													<img alt="Tgl Kejadian" class="order-icon" src="../../images/sort_both.png">
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
											<th style="text-align: left;">
												<a href="#" order_by="DISPLAY_KODE_KLAIM_PERTAMA" order_type="DESC" onclick="orderby(this)">Penetapan Ulang Dari
													<img alt="Penetapan Ulang Dari" class="order-icon" src="../../images/sort_both.png">
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
								<li style="margin-left:15px;">Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <span style="color:#ff0000;"> Tampilkan</span> untuk memulai pencarian data.</li>
								<li style="margin-left:15px;">Kolom <span style="color:#ff0000;"> PENETAPAN ULANG DARI</span> menampilkan <span style="color:#ff0000;">Kode Klaim PERTAMA</span> dari klaim yg ditetapkan ulang.</li>
								<li style="margin-left:15px;">Gunakan <span style="color:#ff0000;">Kode Klaim PERTAMA</span> sebagai kode pencarian untuk menampilkan semua data penetapan ulang atas klaim tersebut.</li>
							</ul>	
						</div>
					</div>
				</div>
				<?
			}else if ($task == "edit" || $task == "view")
			{
			 	//action task edit, view -----------------------------------------------
				?>
				<div id="div_container" class="div-container">
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
						<span id="span_button_utama" style="display:none;">	 		 
  						<div class="div-footer-form">
  							<div class="div-row">
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
    							<ul><li style="margin-left:15px;">Klik tombol <span style="color:#ff0000;">Tutup</span> untuk kembali ke halaman daftar klaim.</li></ul>
    						</div>
              </div>
						</span><!--end span_button_utama-->					
					</div>
					<!--end div_footer--> 
				</div>
				<?
				//end action task edit, view -------------------------------------------		
			}
			?>								
		</form>	 
	</div>	 
</div>

<?php
include_once "../ajax/pn5069_js.php";
include_once "../../includes/footer_app_nosql.php";
?>

