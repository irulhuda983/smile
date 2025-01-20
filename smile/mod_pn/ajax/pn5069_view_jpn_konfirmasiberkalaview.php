<?php
//------------------------------------------------------------------------------
// Menu untuk display detil konfirmasi jp berkala
// dibuat tgl : 30/08/2020
//------------------------------------------------------------------------------
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 			  = "form";
$gs_kodeform 	 	 	= "PN5006";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 	 	= "VIEW DATA KONFIRMASI JP BERKALA";											 
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];
$gs_kode_user		 	= $_SESSION["USER"];
$gs_kode_role		 	= $_SESSION['regrole'];
$task 					 	= $_POST["task"];
$editid 				 	= $_POST['editid'];
$ls_kode_klaim	 	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_konfirmasi	= !isset($_GET['no_konfirmasi']) ? $_POST['no_konfirmasi'] : $_GET['no_konfirmasi'];
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
		<div class="item"><span id="span_page_title"><?=$gs_pagetitle;?></span></div>
		<div class="item" style="float: right; padding: 2px;">
			<font color="#ffffff"><span id="span_page_title_right"></span></font>	 				
		</div>		
	</div>
</div>

<div id="formframe" style="min-width:80%;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
			
				<div id="div_container" class="div-container">
					<div id="div_body" class="div-body" > 
						<div id="dispError1" style="color:red;line-height: 19px;text-align: left;margin-top: 5px;display: none;"></div>
						<input type="hidden" id="st_errval1" name="st_errval1">
										
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
            							<div class="div-col" style="width:81%;">														 						 
            								<div class="form-row_kiri">
                            <label style = "text-align:right;">Nama Lengkap</label>
                              <input type="text" id="cur_nama_lengkap" name="cur_nama_lengkap" style="width:205px;" readonly class="disabled">
                              <input type="hidden" id="cur_no_urut_keluarga" name="cur_no_urut_keluarga">
                              <input type="text" id="cur_kode_penerima_berkala" name="cur_kode_penerima_berkala" style="width:20px;" readonly class="disabled">
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
            							
            							<div class="div-col" style="width:19%;text-align:center;">
                            <input id="cur_foto" name="cur_foto" type="image" src="../../images/nopic.png" align="center" style="height: 90px !important; width: 90px !important; border-radius: 6%; border: 1px solid #DBDBDB!important;"/>
                          </div>
            						</div>
            
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
                      <fieldset style="min-height:253px;"><legend>Dokumen Kelengkapan Administrasi</legend>
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
					<!--end div_body-->
					
  				<div id="div_footer" class="div-footer">
    				<span id="span_button_utama" style="display:block;">
              <div style="padding-top:6px;width:99%;">
    						<div class="div-footer-content">
    							<div style="padding-bottom: 8px;"><b>Keterangan:</b></div>
  								<li style="margin-left:15px;">Klik tombol <font color="#ff0000"> <b>X</b> </font> pada pojok kanan atas untuk menutup form dan kembali ke menu utama.</li>
    						</div>
              </div>
    				</span><!--end span_button_utama-->					
  				</div>
  				<!--end div_footer-->
									
        </div>									
		</form>	 
	</div>	 
</div>

<script language="javascript">
	//template -------------------------------------------------------------------
	$(document).ready(function(){
		$("input[type=text]").keyup(function(){
			$(this).val( $(this).val().toUpperCase() );
		});

    $(window).bind("resize", function(){
			resize();
		});
		
		resize();
		
		let kode_klaim = $('#kode_klaim').val();
		let no_konfirmasi = $('#no_konfirmasi').val();
		loadSelectedRecord(kode_klaim, no_konfirmasi, null);
	});

	function resize(){		 
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

	function showForm(mypage, myname, w, h, scroll) {
		var openwin = window.parent.Ext.create('Ext.window.Window', {
			title: myname,
			collapsible: true,
			animCollapse: true,
			maximizable: true,
			closable: true,
			width: w,
			height: h,
			minWidth: w,
			minHeight: h,
			layout: 'fit',
			modal: true,
			html: '<iframe src="' + mypage + '"  frameborder="0" style="border:0; height:100%; width:100%; overflow-x:hidden; overflow-y:hidden;" scrolling=="'+scroll+'"></iframe>',
			listeners: {
				close: function () {
					// filter();
				},
				destroy: function (wnd, eOpts) {
				}
			}
		});
		openwin.show();
		return openwin;
	}

	function getValue(val){
		return val == null ? '' : val;
	}

	function getValueArr(val){
		if (val){
		 	return val; 
		}else{
		 	return '';	 
		}
	}
		
	function getValueNumber(val){
		return val == null ? '0' : val;
	}
				
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}

	function asyncLoading() {
		preload(true);
	}

	function asyncPreload(isloading) {
		if (isloading) {
			window.asyncLoader = setInterval(asyncLoading, 100);
		} else {
			clearInterval(window.asyncLoader);
			preload(false);
		}
	}
	//end template ---------------------------------------------------------------			
</script>

<script language="javascript">
	function loadSelectedRecord(v_kode_klaim, v_no_konfirmasi, fn)
	{
		if (v_kode_klaim == '' || v_no_konfirmasi == '') {
			return alert('Data Konfimasi JP Berkala tidak boleh kosong');
		}
		asyncPreload(true);
		$.ajax({
			type: 'POST',
			url: "../ajax/pn5070_action.php?"+Math.random(),
			data: {
				tipe: 'fjq_ajax_val_getdatakonfirmasijpberkala',
				v_kode_klaim: v_kode_klaim,
				v_no_konfirmasi: v_no_konfirmasi
			},
			success: function(data){
				try {
					jdata = JSON.parse(data);
					if (jdata.ret == 0) 
					{
            $("#span_page_title_right").html('NO. KONFIRMASI : '+v_kode_klaim+'-'+v_no_konfirmasi);
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
                								 + '<a href="javascript:void(0)" onclick="fl_js_showRincianBerkala(\'' 
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
								html_data_Dok += '<td style="text-align: center; white-space:normal; word-wrap:break-word;">' 
																 + '<a href="javascript:void(0)" onclick="fl_js_DownloadDok(\''
																 + getValue(jdata.data.dataDok[i].URL) + '\', \''  
																 + getValue(jdata.data.dataDok[i].NAMA_FILE) + '\')"> '+ v_nama_file +'</a>' + '</td>';
																 
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
	//end loadSelectedRecord -----------------------------------------------------
</script>

<script>
	function fl_js_showRincianBerkala(v_kode_klaim, v_no_konfirmasi, v_no_proses, v_kd_prg, v_blth_proses)
	{		
		var c_mid = '<?=$mid;?>';
		showForm('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5069_view_penetapanmanfaat_jpnberkalarinci.php?kode_klaim='+v_kode_klaim+'&no_konfirmasi='+v_no_konfirmasi+'&no_proses='+v_no_proses+'&kd_prg='+v_kd_prg+'&blth_proses='+v_blth_proses+'&mid='+c_mid+'','',980,550,'yes');
	}

	function fl_js_DownloadDok(v_url, v_nmfile)
	{
		let p = btoa(v_url);
		let f = btoa(v_nmfile);
		let u = btoa('<?=$gs_kode_user;?>');
		let params = 'p='+p+'&f='+f+'&u='+u;
		NewWindow('http://<?= $HTTP_HOST;?>/mod_pn/ajax/pn5065_download_dok.php?' + params,'',1000,620,'no');
	}
</script>
	
<?php
include "../../includes/footer_app_nosql.php";
?>

