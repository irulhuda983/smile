<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include_once '../../includes/fungsi_newrpt.php';
//set parameter ----------------------------------------------------------------
$pagetype 					= "form";
$gs_kodeform 				= "PN5073";											 
$gs_kantor_aktif		= $_SESSION['kdkantorrole'];
$gs_kode_user				= $_SESSION["USER"];
$gs_kode_role				= $_SESSION['regrole'];
$chId 	 	 			 		= "SMILE";
$gs_pagetitle 			= "ANAK PENERIMA MANFAAT BEASISWA";	
$task 							= $_POST["task"];
$task_detil					= $_POST["task_detil"];
$editid 						= $_POST['editid'];
$ls_nik_penerima		= $_POST['nik_penerima'];
$ls_nik_penerima_temp	= $_POST['nik_penerima_temp'];
$ls_nik_tk_temp			= $_POST['nik_tk_temp'];
$ls_nama_tk_temp		= $_POST['nama_tk_temp'];
$ls_nik_pengganti_temp	= $_POST['nik_pengganti_temp'];
$ls_nik_penerima_induk_temp = $_POST['nik_penerima_induk_temp'];
$ls_ket_penggantian_temp = $_POST['ket_penggantian_temp'];

$ls_rg_kategori			= !isset($_POST['rg_kategori']) ? $_GET['rg_kategori'] : $_POST['rg_kategori'];
$ls_rg_kategori			=	$ls_rg_kategori == "" ? "1" : $ls_rg_kategori;

if ($task == "new")
{
	$gs_pagetitle = $gs_kodeform." - ENTRY ".$gs_pagetitle; 
}else if ($task == "edit")
{
	$gs_pagetitle = $gs_kodeform." - EDIT ".$gs_pagetitle; 
}else if ($task == "view")
{
	$gs_pagetitle = $gs_kodeform." - VIEW ".$gs_pagetitle; 
}else
{
	$gs_pagetitle = $gs_kodeform." - PEREKAMAN ".$gs_pagetitle; 	 
}

if ($task_detil=="doCetakKartu")
{	 				
  $ls_user_param .= " P_NIK_PENERIMA='$ls_nik_penerima'"; 
  $ls_user_param .= " P_USER='$gs_kode_user'"; 							  	 
  $ls_nama_rpt .= "PNR900301.rdf";
  
  $ls_pdf = $ls_nama_rpt;	
  
  $tipe     = "PDF";
  $ls_modul = "pn";  
  exec_rpt_enc_new(1,$ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
  		
  echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  echo "reloadFormUtama();";
  echo "</script>";	 	 
}
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">

<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>

<div class="div-action-menu">
	<div class="menu">
		<div class="item">
			<span id="span_page_title"><?= $gs_pagetitle;?></span>
		</div>
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
        <input type="radio" name="rg_kategori" value="1" onclick="filter();"  <?=$sel1;?>>&nbsp;<span  style="color:#ffffff">PEREKAMAN KANTOR CABANG</span>&nbsp;
        <input type="radio" name="rg_kategori" value="2" onclick="filter();"  <?=$sel2;?>>&nbsp;<span  style="color:#ffffff">KANTOR CABANG LAIN</span> &nbsp;
        <input type="radio" name="rg_kategori" value="3" onclick="filter();"  <?=$sel3;?>>&nbsp;<span  style="color:#ffffff">SEMUA KANTOR</span>				
  		</div>	
			<?
		}else
		{
		 	?>
  		<div class="item" style="float: right; padding: 2px;">
				<span style="color:#ffffff"><span id="span_right_title"></span></span>
  		</div>			
			<?	 
		}
		?>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
  <?php
  if ($task == "") 
	{
    ?>
		<span id="button_action_newedit" style="display:block;">
    	<div class="div-header-form">
    		<div class="div-row">
    			<div class="div-col">
    				<div class="div-action">
    					<div class="icon">
    						<a href="javascript:void(0)" onclick="showTask('edit', null)">
    							<img src="../../../smile/images/app_form_edit.png" align="absmiddle" border="0" alt="Edit"><span>Edit</span>
    						</a>
    					</div>
    				</div>
    			</div>
    			<div class="div-col">
    				<div class="div-action">
    					<div class="icon">
    						<a href="javascript:void(0)" onclick="showTask('new', null)">
    							<img src="../../../smile/images/app_form_add.png" align="absmiddle" border="0" alt="New"><span>New</span>
    						</a>
    					</div>
    				</div>
    			</div>
    		</div>
    	</div>
		</span>
  	<?php 
	} 
	?>
	
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="order_by" name="order_by" value="">
			<input type="hidden" id="order_type" name="order_type" value="">
			<input type="hidden" id="tipe" name="tipe" value="">
			<input type="hidden" id="task_detil" name="task_detil" value="<?=$task_detil;?>">
			<input type="hidden" id="nik_penerima_temp" name="nik_penerima_temp" value="<?=$ls_nik_penerima_temp;?>">
			<input type="hidden" id="nik_tk_temp" name="nik_tk_temp" value="<?=$ls_nik_tk_temp;?>">
			<input type="hidden" id="nama_tk_temp" name="nama_tk_temp" value="<?=$ls_nama_tk_temp;?>">
			<input type="hidden" id="nik_pengganti_temp" name="nik_pengganti_temp" value="<?=$ls_nik_pengganti_temp;?>">
			<input type="hidden" id="nik_penerima_induk_temp" name="nik_penerima_induk_temp" value="<?=$ls_nik_penerima_induk_temp;?>">
			<input type="hidden" id="ket_penggantian_temp" name="ket_penggantian_temp" value="<?=$ls_ket_penggantian_temp;?>">
			
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
										<img src="../../images/zoom.png" border="0" alt="tampilkan_data" align="absmiddle" alt="zoom">
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
										<option value="NIK_TK">NIK TK</option> 
										<option value="NAMA_TK">Nama TK</option>
										<option value="NIK_PENERIMA">NIK Penerima</option> 
										<option value="NAMA_PENERIMA">Nama Penerima</option> 
									</select>
								</div>
							</div>
						</div>
						<div id="div_data" class="div-data">
							<div style="padding: 6px 0px 0px 0px;">
								<table class="table-data" aria-describedby="table-datadesc">
									<thead>
										<tr class="hr-single-double">
											<th style="text-align: center; width: 20px;!important;">
												<input type="checkbox" name="toggle" value="" onclick="checkRecordAll(this);">
											</th>
											<th style="text-align: center;"><a href="#">No</a></th>
											<th style="text-align: left;">
												<a href="#" order_by="NIK_TK" order_type="DESC" onclick="orderby(this)">NIK TK
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="NAMA_TK" order_type="DESC" onclick="orderby(this)">Nama TK
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="NIK_PENERIMA" order_type="DESC" onclick="orderby(this)">NIK Anak
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="NAMA_PENERIMA" order_type="DESC" onclick="orderby(this)">Nama Anak
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="TGL_LAHIR" order_type="DESC" onclick="orderby(this)">Tgl Lahir
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="EMAIL" order_type="DESC" onclick="orderby(this)">Email
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>
											<th style="text-align: left;">
												<a href="#" order_by="HANDPHONE" order_type="DESC" onclick="orderby(this)">No. HP
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>																																
											<th style="text-align: left;">
												<a href="#" order_by="KODE_KANTOR" order_type="DESC" onclick="orderby(this)">Ktr
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>		
											<th style="text-align: left;">
												<a href="#" order_by="STATUS_PESERTA" order_type="DESC" onclick="orderby(this)">Status
													<img class="order-icon" src="../../images/sort_both.png" alt="Sort">
												</a>
											</th>											
										</tr>
									</thead>
									<tbody id="data_list">
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
									<a href="javascript:void(0)" title="First Page" class="pagefirst" onclick="filter('-02')"><<</a>
									<a href="javascript:void(0)" title="Previous Page" class="pagenext" onclick="filter('-01')">Prev</a>
									<input type="text" value="1" id="page" name="page" class="pageinput" onkeypress="return isNumber(event)" onblur="filter(this.value);"/>
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
							<div style="padding-bottom: 0px;"><strong>Keterangan:</strong></div>
							Pilih Jenis Pencarian, Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian kemudian Klik Tombol <span style="color:#ff0000"> Tampilkan</span> untuk memulai pencarian data
						</div>
					</div>
				</div>				
				<?php 			
			}else if ($task == "new" || $task == "edit" || $task == "view")
			{
			 	//action task new, edit ------------------------------------------------
				?>
				<div id="div_container" class="div-container">
					<div id="div_body" class="div-body" >
            <?
            if ($task == "new")
            {
              ?>
							<div class="div-row" >
								<div class="div-col" style="width:100%; max-height: 100%;text-align:left;">
									<strong>PENERIMA BEASISWA DARI PESERTA DENGAN NIK: &nbsp;</strong>
									<input type="text" id="nik_tk" name="nik_tk" readonly style="width:200px;text-align:center;background-color:#ffff99">		 
									&nbsp;A/N&nbsp;
                  <input type="text" id="nama_tk" name="nama_tk" style="width:350px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;" readonly class="disabled">
                  &nbsp;
                  <a id="btn_lov_new_niktk" href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5073_lov_niktk.php?p=pn5073.php&a=formreg&b=nik_tk&c=nama_tk','',1150,580,1)">							
                  	<img src="../../images/help.png" alt="Cari Data TK" border="0" align="absmiddle">
									</a>								
								</div>	 
							</div>
							
							<div id="formEntry"></div>
              <?
            }else if ($task == "edit" || $task == "view")
            {
              ?>
							<div class="div-row" >
								<div class="div-col" style="width:100%; max-height: 100%;text-align:left;">
									<strong>PENERIMA BEASISWA DARI PESERTA DENGAN NIK: &nbsp;</strong>
									<input type="text" id="nik_tk" name="nik_tk" readonly class="disabled" style="width:200px;text-align:center;">		 
									&nbsp;A/N&nbsp;
                  <input type="text" id="nama_tk" name="nama_tk" readonly class="disabled" style="width:350px;text-align:center;font-weight: bold;color:#009999;family:Verdana Arial;">								
								</div>	 
							</div>
							
							<div id="formEntry"></div>							
              <?
            }
            ?>
					</div>
					<!--end div_body-->
					
					<div id="div_footer" class="div-footer">
						<div class="div-footer-form" style="width:95%;">
							<div class="div-row">
								<?
    						if ($task=="new")
    						{
      						?>
  								<span id="span_button_new" style="display:none;">
                    <div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
  												<a id="btn_doSaveInsert" href="#" onClick="if(confirm('Apakah anda yakin akan menyimpan data anak penerima beasiswa..?')) fjq_ajax_val_insert_penerima_beasiswa();">
    												<img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
    												<span>SIMPAN &nbsp;</span>
  												</a>											
                        </div>
                      </div>
                    </div>  									
  								</span>
									<?
								}	
								?>
								
								<?
    						if ($task=="edit")
    						{
      						?>
  								<span id="span_button_edit" style="display:none;">																			
										<div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_doKoreksiData" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan koreksi data anak penerima beasiswa..?')) fjq_ajax_val_koreksi_penerima_beasiswa();">
                            <img src="../../images/ico_edit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                            <span>KOREKSI DATA PROFIL&nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>

										<div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_doCancelKoreksiData" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan cancel koreksi data anak penerima beasiswa..?')) fjq_ajax_val_cancel_koreksi_penerima_beasiswa();">
                            <img src="../../images/ico_penolakan.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                            <span>CANCEL &nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>
																				                    
										<div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_doSaveUpdate" href="#" onClick="if(confirm('Apakah anda yakin akan menyimpan perubahan data anak penerima beasiswa..?')) fjq_ajax_val_update_penerima_beasiswa();">
                            <img src="../../images/folder_exec2.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                            <span>SIMPAN &nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>

                    <div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_doDelete" href="#" onClick="if(confirm('Apakah anda yakin akan menghapus data anak penerima beasiswa..?')) fjq_ajax_val_delete_penerima_beasiswa();">
    												<img src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
    												<span>HAPUS &nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>
																				
                    <div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
													<a style="display:none" id="btn_doSubmit" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan submit data anak penerima beasiswa..?')) fjq_ajax_val_submit_penerima_beasiswa();">	 
                          	<img src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
    												<span>SUBMIT DATA PENERIMA BEASISWA&nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>

                    <div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
													<a style="display:none" id="btn_doSubmitPenundaan" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan submit data untuk penundaan penerimaan beasiswa karena anak belum sekolah..?')) fjq_ajax_val_submit_penundaan_beasiswa();">	 
                          	<img src="../../images/ico_submit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
    												<span>SUBMIT DATA TUNDA PENERIMAAN BEASISWA&nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>
																				
                    <div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_doBatal" href="#" onClick="if(confirm('Apakah anda yakin akan membatalkan data anak penerima beasiswa..?')) fjq_ajax_val_batal_penerima_beasiswa();">
    												<img src="../../images/removex.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
    												<span>BATALKAN DATA &nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>
																				
										<div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a id="btn_CetakKartu" href="#" onClick="fl_js_doCetakKartu();">
                            <img src="../../images/ico_cetak.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                            <span>CETAK KARTU BEASISWA &nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>

										<div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_doUbahStatusTunda" href="#" onClick="if(confirm('Apakah anda yakin akan melakukan pengaktifan data anak penerima beasiswa..?')) fjq_ajax_val_ubahtunda_penerima_beasiswa();">
                            <img src="../../images/ico_edit.jpg" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                            <span>AKTIVASI ANAK SUDAH MENEMPUH PENDIDIKAN &nbsp;&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>
																				
										<div class="div-col">
                      <div class="div-action-footer">
                        <div class="icon">
                          <a style="display:none" id="btn_GantiPenerima" href="#" onClick="fl_js_doGantiPenerima();">
                            <img src="../../images/refreshx.png" border="0" alt="Tambah" align="absmiddle" style="height:25px;"/>
                            <span>PENGGANTIAN KRN MENINGGAL DUNIA&nbsp;</span>
                          </a>											
                        </div>
                      </div>
                    </div>
																											
  								</span>
									<?
								}	
								?>
																
								<div class="div-col">
                  <div class="div-action-footer">
                    <div class="icon">
                      <a id="btn_doBack2Grid" href="#" onClick="reloadPage();">
                        <img src="../../images/open_folder_role.png" border="0" alt="Tambah" align="absmiddle" style="height:30px;"/>
                        <span>TUTUP</span>
                      </a>
                    </div>
                  </div>
                </div>
																	 
							</div>
						</div>

						<div style="padding-top:6px;">
  						<?
  						if ($task=="new")
  						{
    						?>
								<span id="span_footer_keterangan_new" style="display:block;">
									<div class="div-footer-content" style="width:95%;">
      							<div style="padding-bottom: 8px;"><strong>Keterangan:</strong></div>
								<ul>
      							<li style="margin-left:15px;">Klik <img src="../../images/help.png" alt="Cari Data Peserta" border="0" align="absmiddle"> untuk <span style="color:#ff0000">memilih</span> Nomor Identitas Peserta.</li>
								</ul>
      						</div>								
								</span>
								<?						
  						}else
  						{
    						?>
								<span id="span_footer_keterangan_edit" style="display:block;"></span>
								<?							
  						}
  						?>	
  					</div>	 														 
					</div>
					<!--end div_footer-->	
				</div>																					
				<?php
				//end action task new, edit --------------------------------------------
			}
			?>											
		</form>	 
	</div>
	<!--end div formKiri-->		 
</div>

<?php
include_once "../ajax/pn5073_js.php";
include_once "../../includes/footer_app_nosql.php";
?>
			
