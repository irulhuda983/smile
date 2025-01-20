<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";
//set parameter ----------------------------------------------------------------
$pagetype 					= "form";
$gs_kodeform 				= "PN5070";
$gs_kantor_aktif		= $_SESSION['kdkantorrole'];
$gs_kode_user				= $_SESSION["USER"];
$gs_kode_role				= $_SESSION['regrole'];
$chId 	 	 			 		= "SMILE";
$gs_pagetitle 			= "PERSETUJUAN KONFIRMASI JP BERKALA";
$task 							= $_POST["task"];
$task_detil					= $_POST["task_detil"];
$editid 						= $_POST['editid'];
$ls_kode_klaim 			= $_POST['kode_klaim'];
$ln_no_konfirmasi		= $_POST['no_konfirmasi'];
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<!-- custom css -->
<link href="../style.custom.css?a=<?= rand() ?>" rel="stylesheet" />

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?= $gs_pagetitle; ?></span></div>
		<div class="item" style="float: right; padding: 2px;">
			<span style="color:#ffffff"><span id="span_page_title_right"></span></span>
		</div>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?= $task; ?>">
			<input type="hidden" id="editid" name="editid" value="<?= $editid; ?>">
			<input type="hidden" id="mid" name="mid" value="<?= $mid; ?>">
			<input type="hidden" id="order_by" name="order_by" value="">
			<input type="hidden" id="order_type" name="order_type" value="">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="task_detil" name="task_detil" value="<?= $task_detil; ?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?= $ls_kode_klaim; ?>">
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?= $ln_no_konfirmasi; ?>">

			<?php
			if ($task == "") {
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
									<span style="vertical-align: middle;">Tampilkan</span>
									<select name="page_item" id="page_item" style="width: 46px;height:20px;" onchange="filter()">
										<option value="10">10</option>
										<option value="15">15</option>
										<option value="20">20</option>
										<option value="25">25</option>
										<option value="50">50</option>
									</select>
									<span style="vertical-align: middle;">item per halaman</span>
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<a class="a-icon-text" href="#" onclick="filter();" title="Klik Untuk Menampilkan Data">
										<img alt="img" src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle">
										<span>Tampilkan</span>
									</a>
								</div>
								<div class="div-col-right" style="padding: 2px;">
									<select id="search_by2" name="search_by2" style="border:0;width: 110px;height:18px;" onchange="search_by2_changed();">
										<option value="">Keyword Lain</option>
										<option value="">----------------</option>
									</select>
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
								<table aria-describedby="table" class="table-data">
									<thead>
										<tr class="hr-single-double">
											<th style="text-align: center; width: 20px;!important;">
												Action
											</th>
											<th style="text-align: left;"><a href="#">No</a></th>
											<th style="text-align: left;">
												<a href="#" order_by="KODE_KLAIM" order_type="DESC" onclick="orderby(this)">Kode Klaim
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="TGL_KONFIRMASI" order_type="DESC" onclick="orderby(this)">Tanggal
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="KPJ" order_type="DESC" onclick="orderby(this)">No Referensi
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="NAMA_TK" order_type="DESC" onclick="orderby(this)">Nama Peserta
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="KET_PENERIMA_BERKALA" order_type="DESC" onclick="orderby(this)">Penerima Berkala
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KET_PERIODE" order_type="DESC" onclick="orderby(this)">u/ Periode
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KODE_KANTOR_KONFIRMASI" order_type="DESC" onclick="orderby(this)">Ktr Konf
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
												</a>
											</th>
											<th style="text-align: center;">
												<a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="orderby(this)">Ktr Byr
													<img alt="img" class="order-icon" src="../../images/sort_both.png">
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
									<a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="filter('-02')">
										<<< /a>
											<a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="filter('-01')">Prev</a>
											<input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="filter(this.value);" />
											<a href="javascript:void(0)" title="Next Page" class="pagenext" onclick="filter('01')">Next</a>
											<a href="javascript:void(0)" title="Last Page" class="pagelast" onclick="filter('02')">>></a>
											<span style="vertical-align: middle;" id="span_info_halaman">dari 1 halaman</span>
											<input type="hidden" id="pages">
								</div>
								<div class="div-col-right">
									<input type="hidden" id="hdn_total_records">
									<span style="vertical-align: middle;" id="span_info_item">Menampilkan item ke 0 sampai dengan 0 dari 0 items</span>
								</div>
							</div>
						</div>
					</div>
					<div id="div_footer" class="div-footer">
						<div class="div-footer-content">
							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
							Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <span style="color:#ff0000"> Tampilkan</span> untuk memulai pencarian data
						</div>
					</div>
				</div>
				<?php
			} else if ($task == "edit" || $task == "view") {
				if ($task_detil == "BTNTOLAK") {
				?>
					<div id="div_container" class="div-container">
						<div id="div_body" class="div-body">
							<div class="div-row">
								<div class="div-col" style="width:100%; max-height: 100%;">
									<fieldset>
										<legend>Tolak dan Kembalikan ke Entry Konfirmasi JP Berkala</legend>
										</br>

										<div class="form-row_kiri">
											<label style="text-align:right;">Alasan Penolakan &nbsp; *</label>
											<textarea cols="255" rows="2" style="width:500px;background-color:#ffff99" id="alasan_penolakan" name="alasan_penolakan" tabindex="1" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?= $ls_alasan_penolakan; ?></textarea>
										</div>
										<div class="clear"></div>

										</br></br>
									</fieldset>

									<div id="buttonbox" style="width:820px;text-align:center;">
										<a href="#" onClick="if(confirm('Apakah anda yakin akan melakukan penolakan dan pengembalian data ke Entry Konfirmas JP Berkala ..?')) fjq_ajax_val_tolak_konfirmasi_berkala();"><img alt="img" src="../../images/yellow_arrow.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;" />&nbsp;<b>Tolak dan Kembalikan ke Entry Konfirmasi</b></a> &nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;
										<a href="#" onClick="reloadFormUtama();"><img alt="img" src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:35px;" />&nbsp;<b>Tutup</b></a>
									</div>

									<div style="background: #F2F2F2;margin-top:2px;padding:10px 20px;border:1px solid #ececec;text-align:left;width:786px;">
										<span style="background: #FF0; border: 1px solid #CCC;"><i><b>Keterangan:</b></i></span>
										<ul>
											<li style="margin-left:15px;">Penolakan data penetapan ini akan mengembalikan data ke Entry Konfirmasi JP Berkala. Isikan <span style="color:#ff0000"> Alasan Penolakan </span> kemudian klik tombol <span style="color:#ff0000"> TOLAK </span>.</li>
										</ul>
									</div>
								</div>
							</div>
						</div>

					</div>
				<?
				} else {
				?>
					<div id="div_container" class="div-container">
						<div id="div_body" class="div-body">
							<div id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
							<input type="hidden" id="st_errval1" name="st_errval1">

							<div class="div-row">
								<div class="div-col" style="width:45%; max-height: 100%;">
									<div class="div-row">
										<div class="div-col" style="width: 100%">
											<!-- Informasi Penerima Manfaat Selanjutnya ---------------->
											<fieldset>
												<legend><span style="vertical-align: middle;" id="span_fieldset_curr_penerima"></span></legend>
												<span id="span_tidakada_cur_penerima" style="display:none;">
													<div class="div-row">
														<div class="div-col" style="width:100%;text-align:center;">
															<span style="color:#ff0000">TIDAK ADA PENERIMA MANFAAT TURUNAN</span>
														</div>
													</div>
												</span>

												<span id="span_ada_cur_penerima" style="display:none;">
													</br>
													<div class="div-row">
														<div class="div-col" style="width:78%;">
															<div class="form-row_kiri">
																<label style="text-align:right;">Nama Lengkap</label>
																<input type="text" id="cur_nama_lengkap" name="cur_nama_lengkap" style="width:205px;" readonly class="disabled">
																<input type="hidden" id="cur_no_urut_keluarga" name="cur_no_urut_keluarga">
																<input type="text" id="cur_kode_penerima_berkala" name="cur_kode_penerima_berkala" style="width:20px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Tempat dan Tgl Lahir</label>
																<input type="text" id="cur_tempat_lahir" name="cur_tempat_lahir" style="width:155px;" readonly class="disabled">
																<input type="text" id="cur_tgl_lahir" name="cur_tgl_lahir" style="width:70px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Jns Klmin-Hub-No.KK</label>
																<input type="text" id="cur_nama_jenis_kelamin" name="cur_nama_jenis_kelamin" style="width:80px;" readonly class="disabled">
																<input type="hidden" id="cur_jenis_kelamin" name="cur_jenis_kelamin">
																<input type="text" id="cur_nama_hubungan" name="cur_nama_hubungan" style="width:66px;" readonly class="disabled">
																<input type="hidden" id="cur_kode_hubungan" name="cur_kode_hubungan">
																<input type="text" id="cur_no_kartu_keluarga" name="cur_no_kartu_keluarga" style="width:70px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">No. Identitas</label>
																<input type="text" id="cur_nomor_identitas" name="cur_nomor_identitas" style="width:233px;" readonly class="disabled">
																<input type="hidden" id="cur_status_valid_identitas" name="cur_status_valid_identitas">
																<input type="hidden" id="cur_jenis_identitas" name="cur_jenis_identitas">
																<input type="hidden" id="cur_kpj_tertanggung" name="cur_kpj_tertanggung">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">NPWP</label>
																<input type="text" id="cur_npwp" name="cur_npwp" style="width:233px;" readonly class="disabled">
															</div>
															<div class="clear"></div>
														</div>

														<div class="div-col" style="width:22%;text-align:center;">
															<input id="cur_foto" name="cur_foto" type="image" alt="img" src="../../images/nopic.png" align="center" style="height: 90px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;" />
														</div>
													</div>

													<div class="div-row">
														<div class="div-col">
															</br>
															<div class="form-row_kiri">
																<label style="text-align:right;">Alamat*</label>
																<textarea cols="255" rows="1" style="width:233px;background-color:#f5f5f5;height:15px;" readonly id="cur_alamat" name="cur_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"></textarea>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">RT/RW</label>
																<input type="text" id="cur_rt" name="cur_rt" style="width:20px;" readonly class="disabled">
																/
																<input type="text" id="cur_rw" name="cur_rw" style="width:30px;" readonly class="disabled">
																&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kode Pos
																<input type="text" id="cur_kode_pos" name="cur_kode_pos" style="width:50px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">&nbsp;</label>
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
																<label style="text-align:right;">No. Telp</label>
																<input type="text" id="cur_telepon_area" name="cur_telepon_area" style="width:30px;" readonly class="disabled">
																<input type="text" id="cur_telepon" name="cur_telepon" style="width:131px;" readonly class="disabled">
																&nbsp;ext.
																<input type="text" id="cur_telepon_ext" name="cur_telepon_ext" style="width:30px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Email</label>
																<input type="text" id="cur_email" name="cur_email" style="width:150px;" readonly class="disabled">
																<input type="checkbox" id="cur_is_verified_email" name="cur_is_verified_email" disabled class="cebox"><i>
																	<span style="color:#009999">verified</span>
																</i>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">Handphone</label>
																<input type="text" id="cur_handphone" name="cur_handphone" style="width:150px;" readonly class="disabled">
																<input type="checkbox" id="cur_is_verified_hp" name="cur_is_verified_hp" disabled class="cebox"><i>
																	<span style="color:#009999">verified</span>
																</i>
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">&nbsp;</label>
																<input type="checkbox" id="cur_status_reg_notifikasi" name="cur_status_reg_notifikasi" disabled class="cebox"><i>
																	<span style="color:#009999">Layanan SMS Konfirmasi JP Berkala</span>
																</i>
																<input type="hidden" id="cur_status_cek_layanan" name="cur_status_cek_layanan">
															</div>
															<div class="clear"></div>

															</br>

															<div class="form-row_kiri">
																<label style="text-align:right;">Rekening Bank</label>
																<input type="text" id="cur_bank_penerima" name="cur_bank_penerima" style="width:230px;" readonly class="disabled">
																<input type="hidden" id="cur_kode_bank_penerima" name="cur_kode_bank_penerima">
																<input type="hidden" id="cur_id_bank_penerima" name="cur_id_bank_penerima">
																<input type="hidden" id="cur_metode_transfer" name="cur_metode_transfer" style="width:20px;" readonly class="disabled">
															</div>
															<div class="clear"></div>

															<div class="form-row_kiri">
																<label style="text-align:right;">No Rekening</label>
																<input type="text" id="cur_no_rekening_penerima" name="cur_no_rekening_penerima" style="width:100px;" readonly class="disabled">
																<input type="text" id="cur_nama_rekening_penerima_ws" name="cur_nama_rekening_penerima_ws" style="width:100px;" readonly class="disabled">
																<input type="checkbox" id="cb_cur_valid_rekening" name="cb_cur_valid_rekening" disabled class="cebox"><i>
																	<span style="color:#009999">Valid</span>
																</i>
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
											<!-- Kontak Darurat --------------------------------------------------->
											<fieldset>
												<legend>Informasi Kontak Darurat</legend>
												<div class="form-row_kiri">
													<label style="text-align:right;">Nama Lengkap</label>
													<input type="text" class="disabled" id="inkd_nama_lengkap" name="inkd_nama_lengkap" value="<?= $ls_ind_nama_lengkap; ?>" style="width:233px;" readonly>
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Nomor Handphone</label>
													<input type="number" id="inkd_no_hp" name="inkd_no_hp" value="<?= $ls_ind_nama_lengkap; ?>" style="width:233px;" class="disabled" readonly>
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Alamat</label>
													<textarea cols="255" rows="1" style="width:233px;height:15px;" tabindex="3" id="inkd_alamat" name="inkd_alamat" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" class="disabled" readonly></textarea>
												</div>
												<div class="clear"></div>

												<div class="form-row_kiri">
													<label style="text-align:right;">Hubungan Keluarga</label>
													<select size="1" id="inkd_hub_keluarga" name="inkd_hub_keluarga" value="<?= $ls_kode_kondisi_terakhir_induk; ?>" tabindex="1" class="select_format" style="width:238px !important;" class="disabled" disabled>
														<option value="">-- Hubungan Keluarga --</option>

														<?
														$sql = "select KODE, KETERANGAN from ms.ms_lookup where tipe = 'KODEHUBKONTAKDARURAT' order by seq asc";
														$DB->parse($sql);
														$DB->execute();
														while ($row = $DB->nextrow()) {

															echo "<option ";
															// if ($row["KODE_KONDISI_TERAKHIR"] == $ls_kode_kondisi_terakhir_induk && strlen($ls_kode_kondisi_terakhir_induk) == strlen($row["KODE_KONDISI_TERAKHIR"])) {
															// 	echo " selected";
															// }
															echo " value=\"" . $row["KODE"] . "\">" . $row["KETERANGAN"] . "</option>";
														}
														?>
													</select>
												</div>
												<div class="clear"></div>
											</fieldset>
											<!-- Informasi Penerima Manfaat Sebelumnya ---------------->
											<fieldset>
												<legend>Informasi Penerima Manfaat Sebelumnya</legend>
												</br>
												<div class="form-row_kiri">
													<label style="text-align:right;">Nama Lengkap</label>
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
													<label style="text-align:right;">Kondisi Terakhir</label>
													<input type="text" id="nama_kondisi_terakhir_induk" name="nama_kondisi_terakhir_induk" style="width:116px;" readonly class="disabled">
													<input type="text" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" style="width:75px;" readonly class="disabled">
													<input type="hidden" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk">
													<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5069_view_jpn_ahliwarisdetil.php?kode_klaim='+formreg.kode_klaim.value+'&no_urut_keluarga='+formreg.ind_no_urut_keluarga.value+'&mid=<?= $mid; ?>','',980,610,'no')">&nbsp;<img alt="img" src="../../images/ico_profile.jpg" border="0" alt="Tambah" align="absmiddle" style="height:20px;" /> profile</a>
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
											<fieldset>
												<legend><span style="vertical-align: middle;" id="span_fieldset_peserta"></span></legend>
												<input type="hidden" id="no_penetapan" name="no_penetapan" value="<?= $ls_no_penetapan; ?>">
												<input type="hidden" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?= $ln_no_konfirmasi_induk; ?>">
												<input type="hidden" id="cnt_berkala_detil" name="cnt_berkala_detil" value="<?= $ln_cnt_berkala_detil; ?>">

												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_dokumen.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_document.jpg" border="0" alt="Rincian Dokumen Kelengkapan Administrasi" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Dokumen</span>
												</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_ahliwaris.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_ahliwaris.jpg" border="0" alt="Rincian Ahli Waris" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Ahli Waris</span>
												</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_historykonfirmasi.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_history.jpg" border="0" alt="Rincian Histori Konfirmasi" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Histori</span>
												</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;|&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
												<a href="#" onClick="showForm('http://<?= $HTTP_HOST; ?>/mod_pn/ajax/pn5065_view_historypembayaran.php?&kode_klaim='+formreg.kode_klaim.value+'','',1150,610,'yes')"><img alt="img" src="../../images/ico_pembayaran.jpg" border="0" alt="Rincian Histori Pembayaran" align="absmiddle" style="height:30px;" />
													<span style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> Pembayaran</span>
												</a>
											</fieldset>
										</div>
									</div>

									<div class="div-row">
										<div class="div-col" style="width:98%;">
											</br>
											<!-- Informasi periode JP Berkala Selanjutnya ------------->
											<fieldset style="min-height:125px;">
												<legend><span style="vertical-align: middle;" id="span_fieldset_blnberkala"></span></legend>
												</br>

												<span id="span_tidakada_cur_periodeberkala" style="display:none;">
													<div class="div-row">
														<div class="div-col" style="width:100%;text-align:center;">
															<b><span style="vertical-align: middle;" id="span_info_tidakada_cur_periodeberkala"></span></b>
														</div>
													</div>
												</span>

												<span id="span_ada_cur_periodeberkala" style="display:none;">
													<table aria-describedby="table" id="tblBlnBkl" width="100%" class="table-data2">
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
												<fieldset style="min-height:253px;">
													<legend>Dokumen Kelengkapan Administrasi</legend>
													<table aria-describedby="table" id="tblrincianDok" width="100%" class="table-data2">
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

						<div id="div_footer" class="div-footer">
							<div class="div-footer-form">
								<div class="div-row">

									<div class="div-col">
										<div class="div-action-footer">
											<div class="icon">
												<a id="btn_doTolak" href="#" onClick="fl_js_doTolak();">
													<img alt="img" src="../../images/openx.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;" />
													<span>DITOLAK (DILAKUKAN PERBAIKAN)&nbsp;</span>
												</a>
											</div>
										</div>
									</div>

									<div class="div-col">
										<div class="div-action-footer">
											<div class="icon">
												<a id="btn_doApproval" href="#" onClick="if(confirm('Apakah anda yakin akan menyetujui data konfirmasi jp berkala..?')) fjq_ajax_val_approval_konfirmasi_berkala();">
													<img alt="img" src="../../images/ok.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;" />
													<span>DISETUJUI&nbsp;</span>
												</a>
											</div>
										</div>
									</div>

									<div class="div-col">
										<div class="div-action-footer">
											<div class="icon">
												<a id="btn_doBatal" href="#" onClick="if(confirm('Apakah anda yakin akan membatalkan data konfirmasi jp berkala..?')) fjq_ajax_val_batal_konfirmasi_berkala();">
													<img alt="img" src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;" />
													<span>DIBATALKAN&nbsp;</span>
												</a>
											</div>
										</div>
									</div>

									<div class="div-col">
										<div class="div-action-footer">
											<div class="icon">
												<a id="btn_doBack2Grid" href="#" onClick="reloadPage();">
													<img alt="img" src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;" />
													<span>TUTUP&nbsp;</span>
												</a>
											</div>
										</div>
									</div>

								</div>
							</div>
						</div>

					</div>
			<?
				}
			}
			?>
		</form>
	</div>
</div>

<?php
require_once "../ajax/pn5070_js.php";
require_once "../../includes/footer_app_nosql.php";
?>