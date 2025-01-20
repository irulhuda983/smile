<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$pagetype = "form";
$gs_pagetitle = "PN10021 - Setup Detail Manfaat Klaim";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "TKI";

/* ============================================================================
Ket : Form ini digunakan sebagai form input parameter formula
Hist: - 27/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
?>

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
    		minWidth: 600,
    		minHeight: 200,
    		y: 15,
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
</style>

<?php
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_jenis_err = "";
$ls_message_err = "";

?>


<div id="formframe">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
    <div id="formKiri">
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
			<br />


			<fieldset>
				<legend>Ubah Detil Manfaat Klaim</legend>
				<div class="form-row_kiri">
					<label>Kode Manfaat<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="kode_manfaat_edit" id="kode_manfaat_edit" value="<?=$_REQUEST["kode_manfaat"];?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Detil Manfaat<span style="color:#ff0000;">*</span></label>	
					<input type="hidden" name="type" value="edit">
					<input type="hidden" id="DATAID_DTL" name="DATAID_DTL" value="<?=$_REQUEST["kode_detil_manfaat"];?>">
					<input  type="text" name="kode_detil_manfaat_edit" id="kode_detil_manfaat_edit" value="<?=$_REQUEST["kode_detil_manfaat"];?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Detil Manfaat<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_detil_manfaat_edit" id="nama_detil_manfaat_edit" size="35" required>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Tarif Biaya Satuan<span style="color:#ff0000;">*</span></label>
					<input  type="text" name="kode_tarif_biaya_edit" id="kode_tarif_biaya_edit" size="35" required>		
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Tarif Jumlah Item<span style="color:#ff0000;">*</span></label>
					<input  type="text" name="kode_tarif_jml_item_edit" id="kode_tarif_jml_item_edit" size="35" required>			
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Default Jumlah Item<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="default_jml_item_edit" id="default_jml_item_edit" size="35" style="background-color:#ffff99;">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Status Non Aktif</label>	
					<select size="1" id="status_nonaktif_edit" name="status_nonaktif_edit" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
						<option value="">-- Pilih --</option>
						<option value="Y" selected>Y</option>
						<option value="T">T</option>
					</select>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Keterangan<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="keterangan_detil_manfaat_edit" id="keterangan_detil_manfaat_edit" size="70">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
						<input type="button" class="btn green" id="btn_save_detil_manfaat_edit" name="btn_save_detil_manfaat_edit" title="Klik Untuk Simpan Detil Manfaat" value="SIMPAN PERUBAHAN">
				</div>
				<div class="form-row_kanan">
						<input type="button" class="btn green" id="btn_back_detil_manfaat" name="btn_back_detil_manfaat" title="Klik Untuk Kembali" value="KEMBALI">
				</div>
			</fieldset>


			<?php
			} 
			?>
			<?php
			if($_REQUEST["task"] == "View")
			{
			?>
			<br/>
			<br/>


			<fieldset>
				<legend>Tambah Detil Manfaat Klaim</legend>
				<div class="form-row_kiri">
					<label>No Urut <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_urut_edit" id="no_urut_edit" value="<?=$ls_no_urut;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Detil Manfaat<span style="color:#ff0000;">*</span></label>	
					<input type="hidden" name="type" value="view">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<input  type="text" name="kode_detil_manfaat_view" id="kode_detil_manfaat_view" value="<?=$ls_kode_detil_manfaat;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Detil Manfaat<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_detil_manfaat_view" id="nama_detil_manfaat_view" value="<?=$ls_nama_detil_manfaat;?>" size="35" required>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Tarif Biaya Satuan<span style="color:#ff0000;">*</span></label>
					<input  type="text" name="kode_tarif_biaya_view" id="kode_tarif_biaya_view" value="<?=$ls_tarif_biaya;?>" size="35" required>		
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Tarif Jumlah Item<span style="color:#ff0000;">*</span></label>
					<input  type="text" name="kode_tarif_jml_item_view" id="kode_tarif_jml_item_view" value="<?=$ls_tarif_jml_item;?>" size="35" required>			
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Default Jumlah Item<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="default_jml_item_view" id="default_jml_item_view" size="35" style="background-color:#ffff99;">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Status Non Aktif</label>	
					<select size="1" id="status_nonaktif_view" name="status_nonaktif_view" value="<?=$ls_status_nonaktif;?>" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
						<option value="">-- Pilih --</option>
						<option value="Y" selected>Y</option>
						<option value="T">T</option>
					</select>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Keterangan<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="keterangan_detil_manfaat_view" id="keterangan_detil_manfaat_view" size="70">
				</div>
				<div class="form-row_kanan">
						<input type="button" class="btn green" id="btn_back_detil_manfaat" name="btn_back_detil_manfaat" title="Klik Untuk Kembali" value="KEMBALI">
				</div>
				<div class="clear"></div>
			</fieldset>

			
			<?php
			}; 
			?>
			<?php
			if($_REQUEST["task"] == "New")
			{
			?>
			<br />
			<br />
			<fieldset>
				<legend>Tambah Detil Manfaat Klaim</legend>
				<div class="form-row_kiri">
					<label>Kode Manfaat<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="kode_manfaat" id="kode_manfaat" value="<?=$_REQUEST["kode_manfaat"];?>" size="5" onkeypress="validateDigit(event)" style="background-color:#ffff99;" required readonly>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Detil Manfaat<span style="color:#ff0000;">*</span></label>	
					<input type="hidden" name="type" value="new">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["kode_manfaat"];?>">
					<input  type="text" name="kode_detil_manfaat" id="kode_detil_manfaat" value="<?=$ls_kode_detil_manfaat;?>" size="17" onkeypress="validateDigit(event)" style="background-color:#ffff99;" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Detil Manfaat<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_detil_manfaat" id="nama_detil_manfaat" value="<?=$ls_nama_detil_manfaat;?>" size="35" style="background-color:#ffff99;" required>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Tarif Biaya Satuan<span style="color:#ff0000;">*</span></label>
					<input  type="text" name="kode_tarif_biaya" id="kode_tarif_biaya" value="<?=$ls_tarif_biaya;?>" size="35" style="background-color:#ffff99;" required>		
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Tarif Jumlah Item<span style="color:#ff0000;">*</span></label>
					<input  type="text" name="kode_tarif_jml_item" id="kode_tarif_jml_item" value="<?=$ls_tarif_jml_item;?>" size="35" style="background-color:#ffff99;" required>			
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Default Jumlah Item<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="default_jml_item" id="default_jml_item" size="35" style="background-color:#ffff99;" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Status Non Aktif</label>	
					<select size="1" id="status_nonaktif" name="status_nonaktif" value="<?=$ls_status_nonaktif;?>" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
						<option value="">-- Pilih --</option>
						<option value="Y" selected>Y</option>
						<option value="T">T</option>
					</select>
				</div>
				<div class="clear"></div>				
				<div class="form-row_kiri">
					<label>Keterangan<span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="keterangan_detil_manfaat" id="keterangan_detil_manfaat" size="70">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
						<input type="button" class="btn green" id="btn_save_detil_manfaat" name="btn_save_detil_manfaat" title="Klik Untuk Simpan Detil Manfaat" value="SIMPAN">
				</div>
				<div class="form-row_kanan">
						<input type="button" class="btn green" id="btn_back_detil_manfaat" name="btn_back_detil_manfaat" title="Klik Untuk Kembali" value="KEMBALI">
				</div>
			</fieldset>
			<?php
			};
			?>
			<?php
			} else {
			?>
			<fieldset>
				<legend>Pencarian Data</legend>	
				<div class="form-row_kanan">
					Search By&nbsp;
					<select name="f_pilihan" id="f_pilihan">
						<option value="">----------Pilih----------</option>
						<option value="nama_manfaat_detil">Nama Manfaat Detil</option>
						<option value="keterangan">Keterangan</option>
					</select>
					<input type="text" name="f_keyword" id="f_keyword" placeholder="keyword">
					<input type="button" class="btn green" id="btncari" name="btncari" value="TAMPILKAN" title="Klik Untuk Tampilkan data">
				</div>
				<div class="clear"></div>
			</fieldset>
			<div id="formsplit">
				<fieldset>
					<legend>Kode Manfaat</legend>	
					<div class="clear"></div>
					<table class="table table-striped table-bordered row-border hover" id="mydata_new" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th scope="col" width="5%" class="align-center" style="vertical-align: middle;">No</th>
								<th scope="col">Nama Manfaat Detil</th>
								<th scope="col" width="15%">Aktif</th>
								<th scope="col" width="15%">Keterangan</th>
								<th scope="col" width="15%">Action</th>
							</tr>
						</thead>
						<tbody id="listdata">
						</tbody>
					</table>
					<div class="clear"></div>
					<div class="clear"></div>							
				</fieldset>


				<fieldset style="background: #ededed;">
					<div class="form-row_kanan">
						<br>
						<input type="button" class="btn green" id="btn_tambah_detil_manfaat" name="btn_tambah_detil_manfaat" title="Klik Untuk Tambah Detil Manfaat" value="TAMBAH DETIL MANFAAT">
						&nbsp;	
					</div>
				</fieldset>


				<?php
				}
				?>
				</form>
			</div>
			<br>
			<div class="clear"></div>
			<br>
			<fieldset style="background: #FF0;">
			<legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
				<li>Pilih Jenis Pencarian</li>	
				<li>Input Kata Kunci (Keyword)</li>	
				 <li>Klik Tombol CARI DATA untuk memulai pencarian data</li>	
				<li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
			</fieldset>
         </div> 
        <script type="text/javascript">
			$(document).ready(function(){
				window.kode_manfaat = '<?=$_REQUEST['kode_manfaat']?>';
				window.kode_detil_manfaat = '<?=$_REQUEST['kode_detil_manfaat']?>';
				// alert(window.kode_manfaat);
				
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
				
				window.table = $('#mydata_1').DataTable({
					"scrollCollapse": true,
					"paging": true,
					"iDisplayLength": 10,
					'sPaginationType': 'full_numbers',
					"stateSave": true,
					'aoColumnDefs': [
						{ 'bSortable': false, 'aTargets': [ 0 ] }
					],
				});
			
				window.onload =  loadNewTable();
				
				//========================================== BUTTON ACTION ===========================================================

				$('#btn_view').click(function() {
					if(window.kode_manfaat != ''){
						window.location='pn1002.php?task=View&dataid='+window.kode_manfaat+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				
				});
				
				$('#btn_edit').click(function() {
					if(window.kode_manfaat != ''){
						window.location='pn1002.php?task=Edit&dataid='+window.kode_manfaat+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				
				$('#btn_delete').click(function() {
					if(window.kode_manfaat != ''){
						var r = confirm("Apakah anda yakin ?");
						if (r == true) {
							$.ajax({
								type: 'POST',
								url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
								data: { TYPE:'DEL', DATAID:window.kode_manfaat},
								success: function(data) {
									window.selected.slideUp(function(){						
										$(this).remove();					
									});
									window.parent.Ext.notify.msg('Berhasil', "Data Berhasil dihapus!");
								}
							});
							
						} 
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				
				$('#btn_new').click(function() {
					window.location='pn1002.php?task=New&dataid='+window.kode_manfaat+'&mid=<?=$mid;?>';
				});
				
				$("#btncari").click(function() {
					loadNewTable();
				});

				$("#btnmanfaatdetil").click(function() {
					if($('input[name^="CHECK"][type=checkbox]:checked').length>0)
					{
						if($('input[name^="CHECK"][type=checkbox]:checked').length<2){	
							window.kode_manfaat = $('input[name^="CHECK"][type=checkbox]:checked').attr("kode_manfaat");
							NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn10021.php?kode_manfaat='+window.kode_manfaat,'Detail Manfaat '+window.kode_manfaat,'75%','93%','no');
						}else{
							alert('Untuk melakukan edit hanya bisa dipilih satu record');
						}
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});

				$("#btn_tambah_detil_manfaat").click(function() {
					// alert(window.kode_manfaat);
					if(window.kode_manfaat != ''){
						// alert(window.kode_manfaat);
						window.location='pn10021.php?task=New&kode_manfaat='+window.kode_manfaat+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				$("#btn_back_detil_manfaat").click(function() {
					// alert(window.kode_manfaat);
					if(window.kode_manfaat != ''){
						// alert(window.kode_manfaat);
						window.location='pn10021.php?kode_manfaat='+window.kode_manfaat+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});

				//===============================================END OF BUTTON===========================================
				
				<?PHP
				if(isset($_REQUEST["task"])){
				?>
				window.kode_manfaat 	  = '<?=$_REQUEST["kode_manfaat"];?>';
				window.kode_detil_manfaat = '<?=$_REQUEST["kode_detil_manfaat"];?>';
				//alert(window.kode_manfaat);

				//==================================================TASK VIEW============================================
				<?PHP
					if($_REQUEST["task"] == "View"){
						?>
						setTimeout( function() {
						preload(true);
						}, 100);
						$.ajax({
							type: 'POST',
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
							data: { TYPE:'VIEW', DATAID:window.kode_manfaat},
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log("{ TYPE:'VIEW', DATAID:"+window.kode_manfaat+"}");	
								console.log(data);
								//console.log(JSON.parse(data));
								jdata = JSON.parse(data);
								if(jdata.ret == '0'){
									$('#kode_detil_manfaat_view').val(jdata.KODE_MANFAAT_DETIL);
									$('#nama_detil_manfaat_view').val(jdata.NAMA_MANFAAT_DETIL);
									$('#kode_tarif_biaya_view').val(jdata.KODE_TARIF_BIAYASATUAN);
									$('#kode_tarif_jml_item_view').val(jdata.KODE_TARIF_JMLITEM);
									$('#default_jml_item_view').val(jdata.DEFAULT_JMLITEM);
									$('#status_nonaktif_view').val(jdata.STATUS_NONAKTIF);
									$('#keterangan_detil_manfaat_view').val(jdata.KETERANGAN);
								}
							}
						});
				<?PHP
					};
				//=================================================END TASK VIEW==========================================
				?>
				//===================================================TASK EDIT============================================
				<?PHP
					if($_REQUEST["task"] == "Edit"){
						?>
						setTimeout( function() {
						preload(true);
						}, 100); 
						$.ajax({
							type: 'POST',
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_action.php?'+Math.random(),
							data: { type:'view', DATAID:window.kode_manfaat, DATAID_DTL:window.kode_detil_manfaat },
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log(data);
								jdata = JSON.parse(data);
								$('#kode_detil_manfaat_edit').val(jdata.KODE_MANFAAT_DETIL);
								$('#nama_detil_manfaat_edit').val(jdata.NAMA_MANFAAT_DETIL);
								$('#kode_tarif_biaya_edit').val(jdata.KODE_TARIF_BIAYASATUAN);
								$('#kode_tarif_jml_item_edit').val(jdata.KODE_TARIF_JMLITEM);
								$('#default_jml_item_edit').val(jdata.DEFAULT_JMLITEM);
								$('#status_nonaktif_edit').val(jdata.STATUS_NONAKTIF);
								$('#keterangan_detil_manfaat_edit').val(jdata.KETERANGAN);
							}
						});
						$('#btn_save_detil_manfaat_edit').click(function() {
							if($('#kode_detil_manfaat_edit').val() != '' && $('#nama_detil_manfaat_edit').val() != '' && $('#kode_tarif_biaya_edit').val() != '' && $('#kode_tarif_jml_item_edit').val() != ''&& $('#default_jml_item_edit').val() != '' && $('#keterangan_detil_manfaat_edit').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_action.php?'+Math.random(),
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
								alert('Tidak ada data yang disimpan!');
							}
							
						});
				<?PHP
					};
				//====================================================END TASK EDIT===========================================
				?>
				<?PHP
				//=====================================================TASK NEW===============================================
					if($_REQUEST["task"] == "New"){
						?>
						$('#btn_save_detil_manfaat').click(function() {
							if($('#kode_manfaat').val() != '' && $('#kode_detil_manfaat').val() != '' && $('#nama_detil_manfaat').val() != '' && $('#kode_tarif_biaya').val() != ''&& $('#kode_tarif_jml_item').val() != '' && $('#default_jml_item').val() != '' && $('#keterangan_detil_manfaat').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_action.php?'+Math.random(),
									data: $('#formreg').serialize(),
									success: function(data) {
										preload(false);
										console.log(data);
										console.log('tes: '+$('#formreg').serialize());	
										console.log('test data: ' + data);
										console.log('parse: '+JSON.parse(data));
										jdata = JSON.parse(data);

										if(jdata.ret == '0'){
											// alert(jdata.ret);
											window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn10021.php?task=Edit&kode_manfaat='+$('#DATAID').val()+'&kode_detil_manfaat='+$('#kode_detil_manfaat').val()+'&mid=<?=$mid;?>';
										} else {
											alert(jdata.msg);
										}
									}
								});
								
							} else {
								console.log('Pastikan semua data mandatori diisi!');
								alert('Pastikan semua data mandatori diisi!');
							}
							
						});
				<?PHP
					};
				//=====================================================END TASK NEW==============================================
				?>
				<?PHP		
				}
				?>
			});
			

			function loadNewTable(){
				// alert('<?=$_REQUEST["kode_manfaat"]?>');
				preload(true);
				// window.kode_manfaat = $('#').val();
				window.f_pilihan = $('#f_pilihan').val();
				window.f_keyword = $('#f_keyword').val();
				window.table_1 = $('#mydata_new').DataTable({
					"scrollCollapse"	: true,
					"paging"			: true,
					'sPaginationType'	: 'full_numbers',
					scrollY				: "300px",
			        scrollX				: true,
			  		"processing"		: true,
					"serverSide"		: true,
					"search"			: {
					    "regex"	: true
					},
					select				: true,
					"searching"			: false,
					"destroy"			: true,
			        "ajax"				: {
			        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_action.php",
			        	"type"	: "POST",
			        	"data" 	: function(e) { 
			        		e.type 		   = 'query';
			        		e.kode_manfaat = window.kode_manfaat;
			        		e.pilihan = window.f_pilihan;
        					e.keyword = window.f_keyword;
			        	},complete : function(data){
			        		// console.log(data);
			        		preload(false);
			        	},error: function (data){
			            	alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
			            	preload(false);
			            }	
			        },
			        "columns": [
			        	{ "data": "NO" },
			            { "data": "NAMA_MANFAAT_DETIL" },
			            { "data": "AKTIF" },
			            { "data": "KETERANGAN" },
			            { "data": "ACTION" },
			        ],
			        'aoColumnDefs': [
						{"className": "dt-center", "targets": [0,1,2,3,4]},
						{"width": "75px", "targets":[4]},
					]
			        
			    });
				window.table_1.columns.adjust().draw();
				window.table_1.on( 'draw.dt', function () {
					$('input[name*="ACTION"]').click(function() {
						window.kode_manfaat = $(this).attr('kode_manfaat');
						NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn10021.php?kode_manfaat='+window.kode_manfaat,'Detail Sebab Klaim Tipe  '+window.kode_manfaat,'75%','93%','no');
					});
				});
			}

			function edit(kode_detil_manfaat){
				window.location='pn10021.php?task=Edit&kode_manfaat='+window.kode_manfaat+'&kode_detil_manfaat='+kode_detil_manfaat+'&mid=<?=$mid;?>';
			}

			function hapus(kode_detil_manfaat){
				var r = confirm("Apakah anda yakin untuk menghapus data detil manfaat?");
				if (r == true) {
				    $.ajax({
						type: 'POST',
						url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_action.php?'+Math.random(),
						data: { type:'delete', DATAID:window.kode_manfaat, DATAID_DTL:kode_detil_manfaat},
						success: function(data) {
							preload(false);
							console.log(data);
							jdata = JSON.parse(data);
							if(jdata.ret == '0'){
								window.parent.Ext.notify.msg('Berhasil', jdata.msg);
								window.location='pn10021.php?kode_manfaat='+window.kode_manfaat+'&mid=<?=$mid;?>';
							} else {
								alert(jdata.msg);
							}
						}
					});
				} else {
				    window.parent.Ext.notify.msg('Informasi', "Hapus data dibatalkan");
				}
				//window.location='pn10021.php?task=Edit&kode_manfaat='+window.kode_manfaat+'&kode_detil_manfaat='+kode_detil_manfaat+'&mid=<?=$mid;?>';
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
			
		</script>
<?php
include "../../includes/footer_app_nosql.php";
?>
