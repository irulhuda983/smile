<?php
	require_once "../../includes/header_app_nosql.php";	
	require_once "../../includes/conf_global.php";
	require_once "../../includes/class_database.php";
	include_once '../../includes/fungsi_newrpt.php';

	$pagetype     = "form";
	$DB 		  = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
	$USER         = $_SESSION["USER"];
	$KD_KANTOR    = $_SESSION['kdkantorrole'];
	$KODE_ROLE    = $_SESSION['regrole'];

	$formAjax = "../ajax/pn5076_action.php"; # inisialisasikan lokasi file ajax
	$formUtama = "/mod_pn/form/pn5076.php"; # inisialisasikan lokasi file detail
	$formDetail = "/mod_pn/form/pn5076_form_detail.php";

	function checkData($val) {
		$x = $_POST[$val] ?? $_GET[$val] ?? null;
		return $x;
	}

	$ls_no_level 			= checkData("no_level");
	$ls_kode_jabatan 		= checkData("kode_jabatan");
	$ls_kode_agenda_koreksi = checkData("kode_agenda_koreksi");
	$ls_kode_klaim 			= checkData("kode_klaim");
	$ls_nomor_identitas 	= checkData("nomor_identitas");
	$ls_jenis_data		 	= checkData("jenis_data");
	$ls_search_by 			= checkData("search_by");
	$ls_search_txt 			= checkData("search_txt");
	$ls_search_tgl 			= checkData("search_tgl");
	$ls_search_status		= checkData("search_status");
	$gs_pagetitle 			= "PN5076 - Koreksi Rekening Pembayaran Klaim Return";
?>

<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/docsupport/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="../../javascript/chosen_v1.8.7/chosen.jquery.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" media="all" href="../../javascript/chosen_v1.8.7/chosen.min.css">
<link rel="stylesheet" type="text/css" media="all" href="../../style/kn_style.css?abc">
<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>
<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<!-- custom css -->
<link href="../style.custom.css?a=<?=random_int(0,99)?>" rel="stylesheet" />

<div id="formKiri" class="form-container">
	<div id="actmenu">
		<div class="menu-title">
			<h3><?=$gs_pagetitle;?></h3>
		</div>
	</div>

	<div id="formframe" class="form-container dialog">
		<div class="div-container">
			<div class="div-header">
				<div class="div-header-content">
				</div>
			</div>
			<div class="div-body">
				<div class="div-filter"></div>
				<div class="div-data">
                    <div id="konten" style="width: 99%;">
						<form id="formreg">
							<div width="100%">
								<div id="formKiri" style="width:98%; padding-left: 10px;">
									<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetDataUtama1" width="100%">
										<legend>Informasi Klaim</legend>	
										<div class="row">
											<div class="column">
												<div class="l_frm" ><label style="width:150px" for="kodeAgendaKoreksi">Kode Agenda Koreksi</label></div>
												<div class="r_frm">
													<input type= "text" id="kodeAgendaKoreksi" name="kodeAgendaKoreksi" style="width: 300px;" disabled>
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="kodeKlaim">Kode Klaim</label></div>
												<div class="r_frm">
													<input type= "text" id="kodeKlaim" name="kodeKlaim" style="width: 300px;" disabled>
													<input type="hidden" id="noLevel" name="noLevel">
													<input type="hidden" id="kodeJabatan" name="kodeJabatan">
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="nik">Nomor Identitas</label></div>
												<div class="r_frm">
													<input type= "text" id="nik" name="nik" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="namaPeserta">Nama Peserta</label></div>
												<div class="r_frm">
													<input type= "text" id="namaPeserta" name="namaPeserta" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="kpj">KPJ</label></div>
												<div class="r_frm">
													<input type= "text" id="kpj" name="kpj" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="tglKlaim">Tanggal Klaim</label></div>
												<div class="r_frm">
													<input type= "text" id="tglKlaim" name="tglKlaim" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="namaPenerimaManfaat">Nama Penerima Manfaat</label></div>
												<div class="r_frm">
													<input type= "text" id="namaPenerimaManfaat" name="namaPenerimaManfaat" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="tglLahirPenerimaManfaat">Tanggal Lahir <br> Penerima Manfaat</label></div>
												<div class="r_frm">
													<input type= "text" id="tglLahirPenerimaManfaat" name="tglLahirPenerimaManfaat" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="nominalManfaat">Nominal Manfaat (Rp)</label></div>
												<div class="r_frm">
													<input type= "text" id="nominalManfaat" name="nominalManfaat" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="keteranganRetur">Keterangan Retur</label></div>
												<div class="r_frm">
													<textarea id="keteranganRetur" name="keteranganRetur" style="width : 300px" rows="5" maxlength="4000" disabled></textarea>
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
											</div>
											<!-- end div column -->
										</div>	
										<!-- end div row -->
									</fieldset>
									<div class="div-row-clear"></div>	
									<div class="div-row-clear"></div>	

									<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetDataUtama2" width="100%">
										<legend>Data Rekening Lama</legend>	
										<div class="row">
											<div class="column">
												<div class="l_frm" ><label style="width:150px" for="kodeBank">Kode Bank / Nama Bank </label></div>
												<div class="r_frm">
													<input type= "text" id="kodeBank" name="kodeBank" style="width: 50px;" disabled>  
													<input type= "text" id="namaBank" name="namaBank" style="width: 240px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="noRek">Nomor Rekening Penerima</label></div>
												<div class="r_frm">
													<input type= "text" id="noRek" name="noRek" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="namaRek">Nama Rekening Penerima</label></div>
												<div class="r_frm">
													<input type= "text" id="namaRek" name="namaRek" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="kodeBankPembayar">Nama Bank Pembayar</label></div>
												<div class="r_frm">
													<!-- <input type= "text" id="kodeBankPembayar" name="kodeBankPembayar" style="width: 50px;" disabled>   -->
													<input type= "text" id="namaBankpembayar" name="namaBankpembayar" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
											</div>
											<!-- end div column -->
										</div>	
										<!-- end div row -->
									</fieldset>
									<div class="div-row-clear"></div>	
									<div class="div-row-clear"></div>	

									<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetDataUtama3" width="100%">
										<legend>Data Rekening Baru</legend>	
										<div class="row">
											<div class="column">
												<div class="l_frm" ><label style="width:150px" for="kodeBankPembayar">Kode Bank / Nama Bank</label></div>
												<div class="r_frm">
													<input type= "text" id="kodeBank2" name="kodeBank2" style="width: 50px;" disabled>  
													<input type= "text" id="namaBank2" name="namaBank2" style="width: 240px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="noRek2">Nomor Rekening Penerima</label></div>
												<div class="r_frm">
													<input type= "text" id="noRek2" name="noRek2" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="namaRek2">Nama Rekening Penerima</label></div>
												<div class="r_frm">
													<input type= "text" id="namaRek2" name="namaRek2" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="kodeBank2">Nama Bank Pembayar</label></div>
												<div class="r_frm">
													<!-- <input type= "text" id="kodeBankPembayar2" name="kodeBankPembayar2" style="width: 50px;" disabled>   -->
													<input type= "text" id="namaBankpembayar2" name="namaBankpembayar2" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="keteranganKoreksi">Keterangan Koreksi</label></div>
												<div class="r_frm">
													<textarea id="keteranganKoreksi" name="keteranganKoreksi" style="width : 300px" rows="5" maxlength="4000" disabled></textarea>
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="tglKoreksi">Tanggal Koreksi</label></div>
												<div class="r_frm">
													<input type= "text" id="tglKoreksi" name="tglKoreksi" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="petugasKoreksi">Petugas Koreksi</label></div>
												<div class="r_frm">
													<input type= "text" id="petugasKoreksi" name="petugasKoreksi" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="kantorKoreksi">Kantor Koreksi</label></div>
												<div class="r_frm">
													<input type= "text" id="kantorKoreksi" name="kantorKoreksi" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="statusKoreksi">Status Koreksi</label></div>
												<div class="r_frm">
													<input type= "text" id="statusKoreksi" name="statusKoreksi" style="width: 300px;" disabled>  
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
											</div>
											<!-- end div column -->
										</div>	
										<!-- end div row -->
									</fieldset>
									<div class="div-row-clear"></div>	
									<div class="div-row-clear"></div>									
									<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetPersetujuanKoreksi" width="100%">
										<legend style="color:#0080FF !important">Daftar Persetujuan Koreksi</legend>
										<div class="row">
											<div>
												<table class="table-data2" width="100%" style="table-layout: fixed;" summary="table-data2">
													<thead>
													<tr class="hr-double-bottom">
														<th style="text-align: center;" width="25px">No</th>
														<th style="text-align: center;">Pejabat</th>
														<th style="text-align: center;">Kantor</th>
														<th style="text-align: center;">Status Persetujuan</th>
														<th style="text-align: center;">Tanggal Persetujuan</th>
														<th style="text-align: center;">Petugas</th>
														<th style="text-align: center;">Keterangan</th>
													</tr>
													</thead>
													<tbody id="daftarPersetujuanKoreksi"></tbody>
												</table>
											</div>
										</div>
									</fieldset>
									<div class="div-row-clear"></div>	
									<div class="div-row-clear"></div>
									<fieldset style="padding-left: 10px; padding-right: 10px" id="fieldsetDataUtama4" width="100%">
										<legend>Persetujuan Koreksi</legend>
										<div class="row">
											<div class="column">
												<div class="div-row-clear"></div>	
												<div class="div-row-clear"></div>	
												<div class="l_frm" ><label style="width:150px" for="lab_status_verif">Status Persetujuan*</label></div>
												<div class="r_frm"> 
													<select size="1" id="statusPersetujuan" name="statusPersetujuan" style="width: 300px;" required>
														<option value="" selected>-- Pilih --</option>
														<option value="Y"> DISETUJUI </option>
														<option value="R"> DITOLAK</option>
													</select>
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm" ><label style="width:150px" for="lab_keterangan_persetujuan">Keterangan Persetujuan*</label></div>
												<div class="r_frm">
													<textarea id="keteranganPersetujuan" name="keteranganPersetujuan" style="width : 300px" rows="5" maxlength="4000"></textarea>
												</div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="l_frm flag_disclaimer" ><label style="width:150px; color: white;" for="flag_disclaimer"><br><br><br></label></div>
												<div class="r_frm flag_disclaimer">
													<input type="checkbox" id="flag_disclaimer" name="flag_disclaimer">
													Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload
												</div>

												<div class="div-row-clear" id="hidden"></div>
												<div class="div-row-clear" id="hidden"></div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div class="r_frm">
													<input type="button" name="btn_submit" style="width: 100px; margin-right: 20px" id="btn_submit" class="btn green btn_submit" value="SUBMIT" onclick="submitData()"/>
													<input type="button" name="btn_kembali" style="width: 100px;" class="btn green" id="btn_kembali" value="TUTUP" onclick="kembali()"/>	
												</div>
												<div class="div-row-clear" id="hidden"></div>
												<div class="div-row-clear" id="hidden"></div>
												<div class="div-row-clear"></div>
												<div class="div-row-clear"></div>
												<div id="informasi">
													<fieldset style="background: #F2F2F2; width: 98%"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
													<ul>
														<li>Klik tombol SUBMIT untuk persetujuan pengajuan Koreksi Rekening Pembayaran Klaim Return</li>
													</ul>	
													</fieldset>
												</div>
											</div>
											<!-- end div column -->
										</div>
										<!-- end div row -->
									</fieldset>						
								</div>
								<!-- end div formkiri -->

								<div id="formKiri" style="width:45%; padding-left: 10px;">
								
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
				<!-- end formreg -->
		</div>
		<!-- end div body -->
	</div> 
		<!-- end div container -->
</div>

<?php
include_once "../ajax/pn5076_form_detail_js.php";
include_once "../../includes/footer_app_nosql.php";
?>