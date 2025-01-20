<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$pagetype = "form";
$gs_pagetitle = "PN5008 - ENTRI PERENCANAAN KEGIATAN PROMOTIF/PREVENTIF";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "PU";

/* ============================================================================
Ket : Form ini digunakan sebagai form input Entri Perencanaan dan Pengajuan Kegiatan Promotif/Preventif
Hist: - 10/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>

<!-- LOCAL JAVASCRIPTS------------------------------------------------------->		
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../javascript/select2.full.js"></script>
<script language="javascript">
function NewWindow(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
	  settings +='width='+w+',';
	  settings +='top='+wint+',';
	  settings +='left='+winl+',';
	  settings +='scrollbars='+scroll+',';
	  settings +='resizable=1';
	  settings +='location=0';
	  settings +='menubar=0';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}

function NewWindow3(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
	  settings +='width='+w+',';
	  settings +='top='+wint+',';
	  settings +='left='+winl+',';
	  settings +='scrollbars='+scroll+',';
	  settings +='resizable=1';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}

function NewWindow4(mypage,myname,w,h,scroll){
		var openwin = window.parent.Ext.create('Ext.window.Window', {
		title: myname,
		collapsible: true,
		animCollapse: true,

		maximizable: true,
		width: w,
		height: h,
		minWidth: 900,
    	minHeight: 600,
		layout: 'fit',
		html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
		dockedItems: [{
			xtype: 'toolbar',
			dock: 'bottom',
			ui: 'footer',
			items: [
				{ 
					xtype: 'button',
					text: 'Tutup',
					handler : function(){
						openwin.close();
					}
				}
			]
		}]
	});
	openwin.show();
}

function fl_js_reset_keyword2()
{
  document.getElementById('keyword2a').value = '';
  document.getElementById('keyword2b').value = '';
		document.getElementById('keyword2c').value = '';
		document.getElementById('keyword2d').value = '';			
}
	
function fl_js_val_numeric(v_field_id)
{
  var c_val = window.document.getElementById(v_field_id).value;
		var number=/^[0-9]+$/;
		
  if ((c_val!='') && (!c_val.match(number)))
  {
	 document.getElementById(v_field_id).value = '';				 
			 window.document.getElementById(v_field_id).focus();
			 alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");         
			 return false; 				 
  }		
}
						
</script>


<!-- end LOCAL JAVASCRIPTS -------------------------------------------------->

<!-- LOCAL CSS -------------------------------------------------------------->
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../../style/select2.min.css">
<style>
	.errorField{
	border: solid #fe8951 1px !important;
	  background: rgba(254, 145, 81, 0.24);
	}
	.dataValid{
	background: #09b546;
	padding: 2px;
	color: #FFF;
	border-radius: 5px;
	}
	input.file{
	box-shadow:0 0 !important;
	border:0 !important; 
	}
	input[disabled].file{
	background:#FFF !important;
	}
	input.file::-webkit-file-upload-button {
	background: -webkit-linear-gradient(#5DBBF6, #2788E0);
	border: 1px solid #5492D6;
	border-radius:2px;
	color:#FFF;
	cursor:pointer;
	}
	input[disabled].file::-webkit-file-upload-button {
	background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
	border: 1px solid #ABABAB;
	cursor:no-drop;
	}
	input.file::-webkit-file-upload-button:hover {
	background: linear-gradient(#157fcc, #2a6d9e);
	}
	input[disabled].file::-webkit-file-upload-button:hover {
	background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
	}
	#mydata th {
	border-right: 1px solid silver;
	border-bottom: 0.5pt solid silver !important;
	border-top: 0.5pt solid silver !important;
	text-align: center !important;
	}
	#listdata td {
	text-align: left !important;
	}

	.dataTables_length {
	margin-bottom: 10px;	
	}
	.dataTables_wrapper{
	position: relative;
	clear: both;
	zoom: 1;
	background: #ebebeb;
	padding-top: 10px;
	padding-bottom: 5px;
	border: 1px solid #dddddd;
	}
	#mylistdata_wrapper thead tr th {
	padding-top: 2px;
	padding-bottom: 2px;
	}

	#mydata td {
	font-size: 10px;
	text-align: center;
	border-right: 0px solid rgb(221, 221, 221);
	border-bottom: 1px solid rgb(221, 221, 221);
	padding-top: 2px;
	padding-bottom: 2px;
	}

	#mydata {
	text-align: center;
	}
	#simple-table{
	font-size:11px;
	font-weight:normal;
	}
	#simple-table>tbody>tr>td{
	font-size:11px;
	font-weight:normal;
	text-align:left;
	}
</style>
<!-- end LOCAL CSS ---------------------------------------------------------->

<!-- LOCAL GET/POST PARAMETER ----------------------------------------------->
<?php
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_jenis_err = "";
$ls_message_err = "";

?>

<!-- end LOCAL GET/POST PARAMETER ------------------------------------------->
<div id="actmenu" style="display:none;">
    <h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>     
</div>
<div id="actmenu" >
	<div id="actbutton">
		<div style="float:left;">
		<?php
		if(isset($_REQUEST["task"])){
		?>
			<?php
			if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New" || $_REQUEST["tasktk"] == "Edit"){
			?>
			<div style="float:left;">
				<div class="icon">
					<a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
				</div>
			</div>
			<?php
			}; 
			?>
			<div style="float:left;">
				<div class="icon">
					<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5008.php?mid=<?=$mid;?>">
						<img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close
					</a> 
				</div>
			</div>
		<?php
		} else {
		?>
			<div class="icon">
				<a href="javascript:void(0)" id="btn_view">
					<img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> View</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a>
				</div>
			</div>
			<div style="float:left;">
				<div class="icon">
					<a id="btn_new" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
				</div>
			</div>
		<?php
		}
		?>
	</div>
</div>
<div class="clear"></div>
<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    <div id="formKiri" style="width:1000px">
		<form name="formreg" id="formreg" role="form" method="post">
			<div id="konten">
				<?php
				if(isset($_REQUEST["task"]))
				{
				?>
				<?php
					if($_REQUEST["task"] == "Edit")
					{
				?>
				<br />
				<br/>
				<br/>
				<div class="clear"></div>
				<div id="formsplit">
					<fieldset>
						<legend>Update Data Permintaan Verifikasi Perusahaan ke KBP dan Wasrik</legend>
						<div class="form-row_kiri">
							<label>Kode Promotif</label>	
							<input type="hidden" name="TYPE" value="EDIT">
							<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
							<input type="hidden" id="DATAID2" name="DATAID2" value="<?=$_REQUEST["dataid2"];?>">
							<input  type="text" name="kode_promotif" id="kode_promotif" value="<?=$_REQUEST["dataid"];?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>NPP</label>	
							<input  type="text" name="npp" id="npp" value="<?=$ls_npp;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Nama Perusahaan</label>	
							<input  type="text" name="nama_perusahaan" id="nama_perusahaan" value="<?=$ls_nama_perusahaan;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Cabang Kepesertaan</label>	
							<input  type="text" name="cabang_kepesertaan" id="cabang_kepesertaan" value="<?=$ls_cabang_kepesertaan;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Jumlah Kasus KK</label>	
							<input  type="text" name="jumlah_kasus_kk_prs" id="jumlah_kasus_kk_prs" value="<?=$ls_jumlah_kasus_kk_prs;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Jumlah Kasus PAK</label>	
							<input  type="text" name="jumlah_kasus_pak_prs" id="jumlah_kasus_pak_prs" value="<?=$ls_jumlah_kasus_pak_prs;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Jumlah TK</label>	
							<input  type="text" name="jumlah_tk_prs" id="jumlah_tk_prs" value="<?=$ls_jumlah_tk_prs;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Incident Rate</label>	
							<input  type="text" name="incident_rate" id="incident_rate" value="<?=$ls_incident_rate;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Keterangan <span style="color:#ff0000;">*</span></label>
							<textarea cols="255" rows="2" id="keterangan_rencana_kegiatan" name="keterangan_rencana_kegiatan" value="<?=$ls_keterangan_rencana_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
							</textarea>   
						</div>
						<div class="clear"></div>	
					</fieldset>
				</div>
				
				<?php
				} 
				?>
				<?php
				if($_REQUEST["task"] == "View")
				{
				?>
				<br/>
				<br/>
				<br/>
				<br/>
				<div class="clear"></div>
				<div id="formsplit">
					<fieldset>
						<legend>Detail Permintaan Verifikasi Perusahaan ke KBP dan Wasrik</legend>	
						<div class="form-row_kiri">
							<label>Kode Promotif</label>	
							<input type="hidden" name="TYPE" value="VIEW">
							<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
							<input type="hidden" id="DATAID2" name="DATAID2" value="<?=$_REQUEST["dataid2"];?>">
							<input  type="text" name="kode_promotif" id="kode_promotif" value="<?=$_REQUEST["dataid"];?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>NPP</label>	
							<input  type="text" name="npp" id="npp" value="<?=$ls_npp;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Nama Perusahaan</label>	
							<input  type="text" name="nama_perusahaan" id="nama_perusahaan" value="<?=$ls_nama_perusahaan;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Cabang Kepesertaan</label>	
							<input  type="text" name="cabang_kepesertaan" id="cabang_kepesertaan" value="<?=$ls_cabang_kepesertaan;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Jumlah Kasus KK</label>	
							<input  type="text" name="jumlah_kasus_kk_prs" id="jumlah_kasus_kk_prs" value="<?=$ls_jumlah_kasus_kk_prs;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Jumlah Kasus PAK</label>	
							<input  type="text" name="jumlah_kasus_pak_prs" id="jumlah_kasus_pak_prs" value="<?=$ls_jumlah_kasus_pak_prs;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Jumlah TK</label>	
							<input  type="text" name="jumlah_tk_prs" id="jumlah_tk_prs" value="<?=$ls_jumlah_tk_prs;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Incident Rate</label>	
							<input  type="text" name="incident_rate" id="incident_rate" value="<?=$ls_incident_rate;?>" size="35" readonly class="disabled">				
						</div>
						<div class="clear"></div>
						<div class="form-row_kiri">
							<label>Keterangan </label>
							<textarea cols="255" rows="2" id="keterangan_rencana_kegiatan" name="keterangan_rencana_kegiatan" value="<?=$ls_keterangan_rencana_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
							</textarea>   
						</div>
						<div class="clear"></div>	
					</fieldset>
				</div>
				<?php
				}; 
				?>
				<?php
				if($_REQUEST["task"] == "New")
				{
				?>
				<?php
				};
				?>
				<?php
				} else {
				?>
				
				<table class="captionentry">
				<tr> 
					<td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
				</tr>
				</table>
				<br>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<span style="margin-right:5px;">Search by:</span>
					<select id="type" name="type">
						<option value="">-- Kategori --</option>
						<option value="NPP">NPP</option>
						<option value="NAMA_PERUSAHAAN">Nama Perusahaan</option>
					</select>
					<input  type="text" name="keyword" id="keyword" style="width:200px;" placeholder="Keyword">
					<input type="button" name="btncari" class="btn green" id="btncari" value="TAMPILKAN DATA">
				</div>
				<div class="form-row_kanan">
					<input type="button" style="display:none" name="btncariprseligible" id="btncariprseligible" class="btn green"  value="CARI PERUSAHAAN ELIGIBLE">
				</div>
				<br>
				<br>
				<br>
				<div class="clear"></div>
				<div id="formsplit">
					<fieldset>
						<legend>Daftar Permintaan Verifikasi Perusahaan ke KBP dan Wasrik</legend>	
						<div class="clear"></div>
						<table class="table responsive-table" id="mydata" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th scope="col" width="5%" class="align-center" style="vertical-align: middle;">Action</th>
									<th scope="col" width="10%">Kode Promotif/Preventif</th>
									<th scope="col" width="6%">NPP</th>	
									<th scope="col" width="5%">Kode Divisi</th>	
									<th scope="col">Nama Perusahaan</th>
									<th scope="col" width="8%">Jml Kasus KK</th>
									<th scope="col" width="8%">Jml Kasus PAK</th>
									<th scope="col" width="8%">Jml Tenaga Kerja</th>
									<th scope="col" width="8%">Incident Rate</th>
									<th scope="col" width="8%">Status Kegiatan</th>
									<th scope="col" width="8%">Status Promotif/Preventif</th>								
								</tr>
							</thead>
							<tbody id="listdata">
							</tbody>
						</table>
						<div class="clear"></div>
						<div class="clear"></div>																																																					
					</fieldset>
					<br>
					<div class="clear"></div>
					<fieldset style="background: #ededed;">
						<div class="clear"></div>	
						<div class="form-row_kiri">
							<br>
							<input type="button" style="display:none;" class="btn green" id="btncetakdaftarperusahaan" name="btncetakdaftarperusahaan" tabindex="41" value="CETAK DAFTAR PERUSAHAAN" title="Klik Untuk Cetak Daftar Perusahaan">	
							&nbsp;
							<input type="button" class="btn green" id="btnpermintaanverifikasi" name="btnpermintaanverifikasi" tabindex="41" value="KIRIM PERMINTAAN VERIFIKASI KE KBP DAN WASRIK" title="Klik Untuk Kirim Permintaan Verifikasi ke KBP dan Wasrik">	
						</div>
					</fieldset>
					
					<?php
					}
					?>
				</div>				
				<!--- detail tenaga kerja -->
				<?php
				if($_REQUEST["task"] == "Edit")
				{
				?>
				<br>
				<div class="clear"></div>
				<div id="formsplit">
					<fieldset style="background: #ededed;">
						<br>
						<input type="button" name="btnpermintaanverifikasiindividu" id="btnpermintaanverifikasiindividu" class="btn green"  value="KIRIM PERMINTAAN VERIFIKASI KE KBP DAN WASRIK" title="Klik Untuk Kirim Permintaan Verifikasi ke KBP dan Wasrik">
					</fieldset>
					<br>
					<div class="clear"></div>
				</div>
				<?php 
				}
				?>
				<!--- end detail tenaga kerja -->
				<br>
				<fieldset style="background: #FF0;">
				<legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
					<li>Pilih Jenis Pencarian</li>	
					<li>Input Kata Kunci (Keyword)</li>	
					 <li>Klik Tombol CARI DATA untuk memulai pencarian data</li>	
					<li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
				</fieldset>
			 </div> 
		</form>
	</div>
        <script type="text/javascript">
			$(document).ready(function(){
				window.dataid = '';
				window.kodeperusahaan = '';
				var arrDaftarPerusahaanPermintaanVerifikasi =[];
				
				$('#keyword').focus();
				
				$('input').keyup(function() {
					this.value = this.value.toUpperCase();
				});
				
				$('textarea').keyup(function() {
					this.value = this.value.toUpperCase();
				});
				
				$('#type').change(function(e) {
					$('#keyword').focus();
				});
				
				window.table = $('#mydata').DataTable({
					"scrollCollapse": true,
					"paging": true,
					"iDisplayLength": 10,
					'sPaginationType': 'full_numbers',
					"stateSave": true,
					'aoColumnDefs': [
						{ 'bSortable': false, 'aTargets': [  ] }
					],
				});
			
				window.onload =  loadData();
				
				$('#btn_view').click(function() {
					if(window.dataid != ''){
						window.location='pn5008.php?task=View&tasktk=View&dataid='+window.dataid+'&dataid2='+window.kodeperusahaan+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				
				});
				
				$('#btn_edit').click(function() {	
					if(window.dataid != ''){
						window.location='pn5008.php?task=Edit&tasktk=Edit&dataid='+window.dataid+'&dataid2='+window.kodeperusahaan+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				
				$('#btn_delete').click(function() {
					if(window.dataid != ''){
						var r = confirm("Apakah anda yakin ?");
						if (r == true) {
							$.ajax({
								type: 'POST',
								url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
								data: { TYPE:'DEL', DATAID:window.dataid},
								success: function(data) {
									window.selected.slideUp(function(){						
										$(this).remove();					
									});
									jdata = JSON.parse(data);
									if(jdata.ret == '0'){
										window.parent.Ext.notify.msg('Berhasil', jdata.msg);
									} else {
										alert(jdata.msg);
									}
									//window.parent.Ext.notify.msg('Berhasil', "Data Berhasil dihapus!");
								}
							});
							
						} 
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				
				$('#btn_new').click(function() {
					NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_eligible.php?kode_user=<?php echo $ls_user_login?>&kode_kantor=<?php echo $ls_kode_kantor?>','PN500801 - Daftar Perusahaan Eligible','1024','600','yes');
				});
				
				$("#btncari").click(function() {
					loadData();
				});
				
				$("#btncariprseligible").click(function() {						
					NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_eligible.php?kode_user=<?php echo $ls_user_login?>&kode_kantor=<?php echo $ls_kode_kantor?>','PN500801 - Daftar Perusahaan Eligible','1024','600','yes');
				});
				
				$("#btncetakdaftarperusahaan").click(function() {						
					cetakDaftarDokumenPdf("PNR500811","<?php echo $ls_kode_kantor; ?>")
				});
				
				$("#btnpermintaanverifikasi").click(function() {
					if(window.dataid != ''){
						kirimPermintaanVerifikasiKeKBPWasrik();
					}else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}						
				});
				
				<?PHP
				if(isset($_REQUEST["task"])){
				?>
				window.dataid = '<?=$_REQUEST["dataid"];?>';
				window.kodeperusahaan = '<?=$_REQUEST["dataid2"];?>';
				//alert(window.dataid);

				<?PHP
					if($_REQUEST["task"] == "View"){
						?>
						setTimeout( function() {
						preload(true);
						}, 100);
						$.ajax({
							type: 'POST',
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
							data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:window.kodeperusahaan},
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+window.kodeperusahaan+"}");		
								console.log(data);
								//console.log(JSON.parse(data));
								jdata = JSON.parse(data);
								if(jdata.ret == '0'){
									$('#kode_perusahaan').val(jdata.data[0].KODE_PERUSAHAAN);
									$('#npp').val(jdata.data[0].NPP);
									$('#nama_perusahaan').val(jdata.data[0].NAMA_PERUSAHAAN);
									$('#cabang_kepesertaan').val(jdata.data[0].KODE_KANTOR);
									$('#jumlah_kasus_kk_prs').val(jdata.data[0].JML_KASUS_KK);
									$('#jumlah_kasus_pak_prs').val(jdata.data[0].JML_KASUS_PAK);
									$('#jumlah_tk_prs').val(jdata.data[0].JML_TK);
									$('#incident_rate').val(jdata.data[0].INSIDEN_RATE);
									$('#keterangan_rencana_kegiatan').val(jdata.data[0].KETERANGAN);
								}
							}
						});
				<?PHP
					};
				?>
				<?PHP
					if($_REQUEST["task"] == "Edit"){
						?>
						setTimeout( function() {
						preload(true);
						}, 100); 
						$.ajax({
							type: 'POST',
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
							data: { TYPE:'VIEW', DATAID:window.dataid, DATAID2:window.kodeperusahaan},
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+", DATAID2:"+window.kodeperusahaan+"}");		
								console.log(data);
								//console.log(JSON.parse(data));
								jdata = JSON.parse(data);
								if(jdata.ret == '0'){
									$('#kode_perusahaan').val(jdata.data[0].KODE_PERUSAHAAN);
									$('#npp').val(jdata.data[0].NPP);
									$('#nama_perusahaan').val(jdata.data[0].NAMA_PERUSAHAAN);
									$('#cabang_kepesertaan').val(jdata.data[0].KODE_KANTOR);
									$('#jumlah_kasus_kk_prs').val(jdata.data[0].JML_KASUS_KK);
									$('#jumlah_kasus_pak_prs').val(jdata.data[0].JML_KASUS_PAK);
									$('#jumlah_tk_prs').val(jdata.data[0].JML_TK);
									$('#incident_rate').val(jdata.data[0].INSIDEN_RATE);
									$('#keterangan_rencana_kegiatan').val(jdata.data[0].KETERANGAN);
								}
								
								if(jdata.data[0].TGL_PERMINTAAN_VERIFIKASI === null)
								{
									$('#btnpermintaanverifikasiindividu').removeAttr('disabled');
									$('#btnpermintaanverifikasiindividu').removeClass('disabled');
								}
								else
								{
									$('#btnpermintaanverifikasiindividu').attr('disabled','disabled');
									$('#btnpermintaanverifikasiindividu').addClass('disabled');
									
									$('#keterangan_rencana_kegiatan').attr('disabled','disabled');
									$('#keterangan_rencana_kegiatan').addClass('disabled');
									$('#btn_save').attr('style','display:none');
								}
							}
						});
						
						$('#btn_save').click(function() {
							if($('#keterangan_rencana_kegiatan').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
									data: $('#formreg').serialize(),
									success: function(data) {
										preload(false);
										console.log($('#formreg').serialize());	
										console.log(data);
										console.log('test: ' + JSON.parse(data));
										jdata = JSON.parse(data);
										if(jdata.ret == '0'){
											window.parent.Ext.notify.msg('Berhasil', jdata.msg);
										} else {
											alert(jdata.msg);
										}
									}
								});
								
							} else {
								alert('Silahkan mengisi keterangan permintaan verifikasi perusahaan ke KBP dan Wasrik!');
							}
							
						});
						
						$("#btnpermintaanverifikasiindividu").click(function() {
							if($('#keterangan_rencana_kegiatan').val() != ''){
								if(window.dataid != ''){
								kirimPermintaanVerifikasiKeKBPWasrikIndividu(window.dataid);
								}else {
									alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
								}
							}else{
								alert('Silahkan mengisi keterangan permintaan verifikasi perusahaan ke KBP dan Wasrik!');
							}						
						});
						
				<?PHP
					};
				?>				
				<?PHP		
				}
				?>
			});

			function kirimPermintaanVerifikasiKeKBPWasrik(){
				var panjang = arrDaftarPerusahaanPermintaanVerifikasi.length;
				var r = confirm("Sebanyak "+panjang+" data pengajuan yang akan dikirim kepada KBP untuk diverifikasi");
				if (r == true) {
					for (var i = 0; i < panjang; i++) {
						var obj = arrDaftarPerusahaanPermintaanVerifikasi[i];
						$.ajax({
								type: 'POST',
								url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
								data: { TYPE:'KIRIM', DATAID:obj},
								success: function(data) {
									window.selected.slideUp(function(){						
										$(this).remove();					
									});
									jdata = JSON.parse(data);
									if(jdata.ret == '0'){
										window.parent.Ext.notify.msg('Berhasil', jdata.msg);
										arrDaftarPerusahaanPermintaanVerifikasi = [];
									} else {
										alert(jdata.msg);
									}
								}
						});
					}
				}
			}
			
			function kirimPermintaanVerifikasiKeKBPWasrikIndividu(p_kode_promotif){
				var r = confirm("Anda yakin?");
				if (r == true) {
					$.ajax({
						type: 'POST',
						url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
						data: { TYPE:'KIRIMINDIVIDU', DATAID:p_kode_promotif, KETERANGANPERMINTAAN:$('#keterangan_rencana_kegiatan').val()},
						success: function(data) {
							jdata = JSON.parse(data);
							if(jdata.ret == '0'){
								window.parent.Ext.notify.msg('Berhasil', jdata.msg);
								location.reload();
							} else {
								alert(jdata.msg);
							}
						}
					});
				}
			}
			
			function loadData(){
				preload(true);
				window.table
					.clear()
					.draw();
				$('input[aria-controls="mydata"]').val('');
				console.log('{ TYPE:'+$('#type').val()+', KEYWORD:'+$('#keyword').val()+'}');
				arrDaftarPerusahaanPermintaanVerifikasi = [];
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_query.php?'+Math.random(),
					data: { TYPE:$('#type').val(), KEYWORD:$('#keyword').val()},
					success: function(data) {	
						preload(false);	
						//console.log(data);
						jdata = JSON.parse(data);
						if(jdata.ret == 0){
							for (i = 0; i < jdata.data.length; i++) { 
								window.table.row.add( [
									'<input type="checkbox" KODE_PROMOTIF="'+jdata.data[i].KODE_PROMOTIF+'" KODE2="'+jdata.data[i].KODE_PERUSAHAAN+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']"><input type="hidden" name="KODE_PROMOTIF['+i+']" id="KODE_PROMOTIF'+i+'" value="'+jdata.data[i].KODE_PROMOTIF+'">',
									jdata.data[i].KODE_PROMOTIF,
									jdata.data[i].NPP,
									jdata.data[i].KODE_DIVISI,
									jdata.data[i].NAMA_PERUSAHAAN,
									'<div style="text-align:right !important">'+jdata.data[i].JML_KASUS_KK+'</div>',
									'<div style="text-align:right !important">'+jdata.data[i].JML_KASUS_PAK+'</div>',
									'<div style="text-align:right !important">'+jdata.data[i].JML_TK+'</div>',
									'<div style="text-align:right !important">'+jdata.data[i].INSIDEN_RATE+'</div>',
									jdata.data[i].SUB_STATUS,
									jdata.data[i].STATUS
								] ).draw();
							}
							
							$('input[type="checkbox"]').change(function() {
								if(this.checked) {
									window.dataid= $(this).attr('KODE_PROMOTIF');
									window.kodeperusahaan = $(this).attr('KODE2');
									window.selected = $(this).closest('tr');
									arrDaftarPerusahaanPermintaanVerifikasi.push(window.dataid);
									console.log(arrDaftarPerusahaanPermintaanVerifikasi);
									console.log(window.dataid);
									console.log(window.kodeperusahaan);
								}else{
									window.dataid= $(this).attr('KODE_PROMOTIF');
									arrDaftarPerusahaanPermintaanVerifikasi.splice($.inArray(window.dataid, arrDaftarPerusahaanPermintaanVerifikasi), 1);
									console.log(arrDaftarPerusahaanPermintaanVerifikasi);
								}
							});
							
							$('input[type="hidden"]').change(function() {
								if(this.clicked) {
									window.dataid= $(this).attr('KODE_PROMOTIF');
									window.kodeperusahaan = $(this).attr('KODE2');
									window.selected = $(this).closest('tr');
									console.log(window.kodeperusahaan);
									//console.log(window.kodeperusahaan);
									//alert('hello: '.window.kodeperusahaan);
								}
							});
							
							//$('input[name*="CHECK"]').change(function() {
							$('tbody>tr[role="row"').mouseout(function(e) {
							   $(this).css('background-color','#fff'); 
							   $(this).css('cursor','hand');
							});
							$('tbody>tr[role="row"').mouseover(function(e) {
								$(this).css('cursor','hand');
								$(this).css('background-color','#ddd'); 
							});
						
							//$('input[aria-controls="mydata"]').val('');
							window.table = $('#mydata').DataTable();
							window.table.on( 'draw.dt', function () {
								$('tbody>tr[role="row"').mouseout(function(e) {
								   $(this).css('background-color','#fff'); 
								   $(this).css('cursor','hand');
								});
								$('tbody>tr[role="row"').mouseover(function(e) {
									$(this).css('cursor','hand');
									$(this).css('background-color','#ddd'); 
								});
								
								$('input[type="checkbox"]').change(function() {
									if(this.checked) {
										window.dataid= $(this).attr('KODE_PROMOTIF');
										window.kodeperusahaan = $(this).attr('KODE2');
										window.selected = $(this).closest('tr');
										console.log(window.kodeperusahaan);
									}
								});
							});
									
						} else if(jdata.ret == '-2'){
							alertError(jdata.msg);
							window.table
								.clear()
								.draw();
							
						} else {
							alertError(jdata.msg);
						}
					} 
				});
			}
			
			function validateDigit(evt) {
			  var theEvent = evt || window.event;
			  var key = theEvent.keyCode || theEvent.which;
			  key = String.fromCharCode( key );
			  var regex = /[0-9]|\./;
			  if( !regex.test(key) ) {
				theEvent.returnValue = false;
				if(theEvent.preventDefault) theEvent.preventDefault();
			  }
			}
			
			function cetakDaftarDokumenPdf(p_kode_report,p_kode_kantor) { 
				console.log(p_kode_report);
				preload(true);
				$.ajax({
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5013_cetak.php?'+Math.random(),
					type:'POST',
					data: { TYPE:'CETAK_REPORT',KODE_REPORT:p_kode_report,KODE_KANTOR:p_kode_kantor}, 
					success:function(data) {
					preload(false);
					NewWindow(data,'',800,600,1);
					}, error: function(errorThrown) { 
					console.log(errorThrown);
					}  
				});
			}
			
			function cetakDokumenPdf(p_kode_report,p_kode_promotif) { 
				console.log(p_kode_report);
				preload(true);
				$.ajax({
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_cetak.php?'+Math.random(),
					type:'POST',
					data: { TYPE:'CETAK_REPORT',KODE_REPORT:p_kode_report,KODE_PROMOTIF:p_kode_promotif}, 
					success:function(data) {
					preload(false);
					NewWindow(data,'',800,600,1);
					}, error: function(errorThrown) { 
					console.log(errorThrown);
					}  
				});
			}
			
			function cetakDokumenKlaimPdf(p_kode_report,p_kode_klaim,p_kode_user) { 
				console.log(p_kode_report);
				preload(true);
				$.ajax({
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_cetak.php?'+Math.random(),
					type:'POST',
					data: { TYPE:'CETAK_REPORT',KODE_REPORT:p_kode_report,KODE_KLAIM:p_kode_klaim,KODE_USER:p_kode_user}, 
					success:function(data) {
					preload(false);
					NewWindow(data,'',800,600,1);
					}, error: function(errorThrown) { 
					console.log(errorThrown);
					}  
				});
			}
			
			
		</script>
<?php
include "../../includes/footer_app_nosql.php";
?>
