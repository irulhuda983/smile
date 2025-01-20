<?php
require_once "../../includes/header_app_nosql.php";	
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
require_once '../../includes/fungsi_newrpt.php';

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
$KD_KANTOR 	= $_SESSION['kdkantorrole'];
$KODE_ROLE 	= $_SESSION['regrole'];
$formDetail = "/mod_pn/form/pn5076_form_detail.php";
$formAjax = "../ajax/pn5076_action.php";

$pagetype = "form";
$gs_pagetitle = "PN5076 - Daftar Koreksi Rekening Pembayaran Klaim Return";

function getData($val) {
	$x = $_POST[$val] ?? $_GET[$val] ?? null;
	return $x;
}

$former_search_by 		= getData("former_search_by");
$former_search_txt 		= getData("former_search_txt");
$former_search_tgl 		= getData("former_search_tgl");
$former_jenis_data		= getData("former_jenis_data");
$former_search_status	= getData("former_search_status");
		
?>

<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="../../javascript/chosen_v1.8.7/docsupport/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" media="all" href="../../javascript/chosen_v1.8.7/chosen.min.css">
<link rel="stylesheet" type="text/css" media="all" href="../../style/kn_style.css?abc">

<div id="formKiri" class="form-container">
	<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
		<input type="hidden" id="order_by" name="order_by" value="">
		<input type="hidden" id="order_type" name="order_type" value="">
		<div id="actmenu">
			<div class="menu-title">
				<h3><?=$gs_pagetitle;?></h3>
			</div>
		</div>
		<div id="formframe" class="form-container">
			<div class="div-container">
				<div class="div-body">
					<div class="div-filter">
						<div class="div-row-clear"></div>
						<div class="div-row-clear"></div>
						<div class="div-row-between">
							<div class="left-item">
								<div class="div-row">
									<div class="item">	
										<input  onchange="handleRadioChange(this)" type="radio" id="filterRadio" style="cursor: pointer;" name="jenis_data" value="belum" checked>&nbsp;<span  style="color: #0093FF;"><b>Belum Diproses</b></span> &nbsp; &nbsp;
										<input  onchange="handleRadioChange(this)" type="radio" id="filterRadio" style="cursor: pointer;" name="jenis_data" value="sudah">&nbsp;<span  style="color: #0093FF;"><b>Sudah Diproses</b></span>  &nbsp; &nbsp;
									</div>
								</div>
							</div>
							<div class="right-item">
								<div class="div-row">
									<div class="item">
										<select name="search_by" id="search_by" style="width: 136px;" onchange="search_by_changed()">
											<option value="">-- Pilih --</option>
										</select>	
									</div>
									<div class="item">
										<input class="searchByText" type="text" name="search_txt" id="search_txt" placeholder="Search.." style="width: 136px;">
										<span id="span_search_tgl"  hidden="">										
											<input type="text" id="search_tgl" name="search_tgl" value="" placeholder="dd/mm/yyyy" size="12"  >
											<input id="btn_tgl3" type="image" align="top" onclick="return showCalendar('search_tgl', 'dd-mm-y');" src="../../images/calendar.gif" alt="img"/>&nbsp;&nbsp;
										</span>
										<select name="search_by_status" id="search_by_status" style="width: 136px;">
											<option value="">-- Pilih --</option>
										</select>	
									</div>
									<div class="item">
										<input type="button" name="btnfilter" style="width: 100px" class="btn green" id="btnfilter"
											value="TAMPILKAN" onclick="filter()">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="div-data">
						<div class="div-row">
							<div class="item full">
								<div id="div-container-table" class="item full" data-height="360" style="overflow-y: auto; min-height: 120px; height: 335px;">
									<table class="table-data sticky" cellspacing='0' id="tableList" width="100%" summary="table-data2">
										<thead>
											<tr id="data_header">
												<th scope="col" style="text-align: center;">No</th>
												<th scope="col" style="text-align: center;">Aksi</th>
												<th scope="col" style="text-align: center;">Kode Agenda Koreksi</th>
												<th scope="col" style="text-align: center;">Kode Klaim</th>
												<th scope="col" style="text-align: center;">Nomor Identitas</th>
												<th scope="col" style="text-align: center;">Nama Peserta</th>
												<th scope="col" style="text-align: center;">KPJ</th>
												<th scope="col" style="text-align: center;">Tanggal Klaim</th>
												<th scope="col" style="text-align: center;">Nominal Manfaat <br> (Rp)</th>
												<th scope="col" style="text-align: center;">Nama Bank Penerima</th>
												<th scope="col" style="text-align: center;">Nomor Rekening Penerima</th>
												<th scope="col" style="text-align: center;">Nama Rekening Penerima</th>
												<th scope="col" style="text-align: center;">Tanggal Koreksi</th>
												<th scope="col" style="text-align: center;">Petugas Koreksi</th>
												<th scope="col" style="text-align: center;">Kantor Koreksi</th>
												<th scope="col" style="text-align: center;">Status Koreksi</th>
											</tr>
											<tr id="data_header_sub">
											</tr>
										</thead>
										<tbody id="data_list"></tbody>
									</table>
								</div>
							</div>
						</div>
					</div>
					<div class="div-page">
						<input type="hidden" id="pages" name="pages" value="">
						<input type="hidden" id="hdn_total_records" name="hdn_total_records" value="">
						<div class="div-row-between">
							<div class="left-item">
								<div class="div-row">
									<div class="item">Halaman</div>
									<div class="item">
										<a href="javascript:void(0)" title="First Page" class="icon-link" onclick="filter('-2')"><<</a>
									</div>
									<div class="item">
										<a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="filter('-1')">Prev</a>
									</div>
									<div class="item">
										<input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="filter(this.value);">
									</div>
									<div class="item">
										<a href="javascript:void(0)" title="Next Page" class="icon-link" onclick="filter('1')">Next</a>
									</div>
									<div class="item">
										<a href="javascript:void(0)" title="Last Page" class="icon-link" onclick="filter('2')">>></a>
									</div>
									<div class="item">
										<span id="span_info_halaman">dari 1 halaman</span>
									</div>
								</div>
							</div>
							<div class="right-item">
								<div class="item">
									<span id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
									<select name="page_item" id="page_item" style="width: 50px;" onchange="filter()">
									<option value="10">10</option>
									<option value="15">15</option>
									<option value="20">20</option>
									<option value="25">25</option>
									<option value="50">50</option>
									</select>		
								</div>
								<div class="item">
									
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="div-footer">
					<div class="div-footer-content">
						<div style="padding-bottom: 6px;"><b>Keterangan:</b></div>
						<ul>
							<li style="margin-left:15px; padding-bottom: 3px;">List Data Koreksi Rekening Pembayaran Klaim Return</li>
						</ul>
					</div>
					<div class="div-row-clear"></div>
					<div class="div-row-clear"></div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include_once "../ajax/pn5076_js.php";
include_once "../../includes/footer_app_nosql.php";
?>