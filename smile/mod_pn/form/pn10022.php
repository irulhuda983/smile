<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$pagetype = "form";
$gs_pagetitle = "PN10022 - Setup Manfaat Eligibility";
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
				if($_REQUEST["task"] == "New"||$_REQUEST["task"] == "Edit"||$_REQUEST["task"] == "View")
				{
				?>
				<br />
				<br />
				<fieldset>
					<legend>Tambah Manfaat Eligibility</legend>
					<div class="form-row_kiri">
						<label>Kode Manfaat<span style="color:#ff0000;">*</span></label>	
						<input  type="text" name="kode_manfaat" id="kode_manfaat" value="<?=$_REQUEST["kode_manfaat"];?>" size="5" onkeypress="validateDigit(event)" style="background-color:#ffff99;" required readonly>
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Nama Manfaat<span style="color:#ff0000;">*</span></label>	
						<input type="hidden" name="type"  value="<?PHP if($_REQUEST['task']=='New'){echo 'new';}elseif($_REQUEST['task']=='Edit'){echo 'edit';}elseif($_REQUEST['task']=='View'){echo 'view';}?>">
						<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["kode_manfaat"];?>">
						<select size="1" id="nama_manfaat" name="nama_manfaat" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
							<?PHP
								  $sql = "select kode_manfaat, nama_manfaat from sijstk.pn_kode_manfaat where kode_manfaat = '".$_REQUEST['kode_manfaat']."' order by nama_manfaat asc";
								  $DB->parse($sql);
								  $DB->execute();
								  while($row = $DB->nextrow())
								  {
									  echo "<option ";
									  echo " value=\"".$row["KODE_MANFAAT"]."\">".$row["NAMA_MANFAAT"]."</option>";
								  }
							 ?>
						</select>
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Kode Tipe Penerima<span style="color:#ff0000;">*</span></label>	
						<input  type="hidden" name="kode_tipe_penerima_default" id="kode_tipe_penerima_default" value="<?=$_REQUEST['kode_tipe_penerima'];?>" size="35" style="background-color:#ffff99;" required readonly>	
						<input  type="text" name="kode_tipe_penerima" id="kode_tipe_penerima" size="35" style="background-color:#ffff99;" required readonly>	
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Nama Tipe Penerima<span style="color:#ff0000;">*</span></label>
						<select size="1" id="nama_tipe_penerima" name="nama_tipe_penerima" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
							<option value="">-- Pilih --</option>
							<? 
								  $sql = "select kode_tipe_penerima, nama_tipe_penerima from sijstk.pn_kode_tipe_penerima order by nama_tipe_penerima asc";
								  $DB->parse($sql);
								  $DB->execute();
								  while($row = $DB->nextrow())
								  {
									  echo "<option ";
									  echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
								  }
							 ?>
						</select>	
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Status Default<span style="color:#ff0000;">*</span></label>
						<select size="1" id="status_default" name="status_default" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
							<option value="">-- Pilih --</option>
							<option value="Y" selected>Y</option>
							<option value="T">T</option>
						</select>
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Status Non Aktif</label>	
						<select size="1" id="status_nonaktif" name="status_nonaktif" value="<?=$ls_status_nonaktif;?>" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
							<option value="">-- Pilih --</option>
							<option value="T" selected>T</option>
							<option value="Y">Y</option>
						</select>
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Keterangan<span style="color:#ff0000;">*</span></label>	
						<input  type="text" name="keterangan_manfaat_eligibility" id="keterangan_manfaat_eligibility" size="70">
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
							<input type="button" class="btn green" id="btn_save_eligibility" name="btn_save_eligibility" title="Klik Untuk Simpan Manfaat Eligibility" value="SIMPAN">
					</div>
					<div class="form-row_kanan">
							<input type="button" class="btn green" id="btn_back_eligibility" name="btn_back_eligibility" title="Klik Untuk Kembali" value="KEMBALI">
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
						<option value="b.nama_tipe_penerima">Nama Tipe Penerima</option>
						<option value="a.keterangan">Keterangan</option>
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
								<th scope="col">Kode Tipe Penerima</th>
								<th scope="col">Nama Tipe Penerima</th>
								<th scope="col" width="15%">Status Default</th>
								<th scope="col" width="15%">Status Non Aktif</th>
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
						<input type="button" class="btn green" id="btn_tambah_manfaat_eligibility" name="btn_tambah_manfaat_eligibility" title="Klik Untuk Tambah Manfaat Eligibility" value="TAMBAH MANFAAT ELIGIBILITY">
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
				window.kode_tipe_penerima = '<?=$_REQUEST['kode_tipe_penerima']?>';
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

				$("#btn_tambah_manfaat_eligibility").click(function() {
					if(window.kode_manfaat != ''){
						window.location='pn10022.php?task=New&kode_manfaat='+window.kode_manfaat+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
			
				$("#btn_back_eligibility").click(function() {
					if(window.kode_manfaat != ''){
						window.location='pn10022.php?kode_manfaat='+window.kode_manfaat+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});

				$("#nama_tipe_penerima").change(function(){
					kode_tipe_penerima = $("#nama_tipe_penerima").val();
					$("#kode_tipe_penerima").val(kode_tipe_penerima);
				});

				//===============================================END OF BUTTON===========================================
				
				<?PHP
				if(isset($_REQUEST["task"])){
				?>
				window.kode_manfaat 	  = '<?=$_REQUEST["kode_manfaat"];?>';
				window.kode_tipe_penerima = '<?=$_REQUEST["kode_tipe_penerima"];?>';

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
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10022_action.php?'+Math.random(),
							data: { type:'view', DATAID:window.kode_manfaat, kode_tipe_penerima:window.kode_tipe_penerima },
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log(data);
								jdata = JSON.parse(data);
								$('#kode_manfaat').val(jdata.KODE_MANFAAT);
								$('#nama_manfaat').val(jdata.NAMA_MANFAAT);
								$('#kode_tipe_penerima').val(jdata.KODE_TIPE_PENERIMA);
								$('#nama_tipe_penerima').val(jdata.KODE_TIPE_PENERIMA);
								$('#status_default').val(jdata.STATUS_DEFAULT);
								$('#status_nonaktif').val(jdata.STATUS_NONAKTIF);
								$('#keterangan_manfaat_eligibility').val(jdata.KETERANGAN);
							}
						});
						$('#btn_save_eligibility').click(function() {
							if($('#kode_detil_manfaat_edit').val() != '' && $('#nama_detil_manfaat_edit').val() != '' && $('#kode_tarif_biaya_edit').val() != '' && $('#kode_tarif_jml_item_edit').val() != ''&& $('#default_jml_item_edit').val() != '' && $('#keterangan_detil_manfaat_edit').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10022_action.php?'+Math.random(),
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
						$('#btn_save_eligibility').click(function() {
							if($('#kode_manfaat').val() != '' && $('#nama_manfaat').val() != '' && $('#kode_tipe_penerima').val() != '' && $('#nama_tipe_penerima').val() != ''&& $('#status_defult').val() != '' && $('#status_nonaktif').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10022_action.php?'+Math.random(),
									data: $('#formreg').serialize(),
									success: function(data) {
										preload(false);
										console.log(data);
										console.log('tes: '+$('#formreg').serialize());	
										console.log('test data: ' + data);
										console.log('parse: '+JSON.parse(data));
										jdata = JSON.parse(data);

										if(jdata.ret == '0'){
											window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn10022.php?task=Edit&kode_manfaat='+$('#DATAID').val()+'&kode_tipe_penerima='+$('#kode_tipe_penerima').val()+'&mid=<?=$mid;?>';
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
				window.f_pilihan = $('#f_pilihan').val();
				window.f_keyword = $('#f_keyword').val();
				// alert(window.f_pilihan+"-"+window.f_keyword);
				preload(true);
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
			        	"url"	: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10022_action.php",
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
			            { "data": "KODE_TIPE_PENERIMA" },
			            { "data": "NAMA_TIPE_PENERIMA" },
			            { "data": "STATUS_DEFAULT" },
			            { "data": "STATUS_NONAKTIF" },
			            { "data": "KETERANGAN" },
			            { "data": "ACTION" },
			        ],
			        'aoColumnDefs': [
						{"className": "dt-center", "targets": [0,1,2,3,4,5,6]},
						{"width": "100px", "targets":[6]},
					]
			        
			    });
				window.table_1.columns.adjust().draw();
				window.table_1.on( 'draw.dt', function () {
					$('input[name*="ACTION"]').click(function() {
						window.kode_manfaat = $(this).attr('kode_manfaat');
						NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn10022.php?kode_manfaat='+window.kode_manfaat,'Detail Sebab Klaim Tipe  '+window.kode_manfaat,'75%','93%','no');
					});
				});
			}

			function edit(kode_manfaat,kode_tipe_penerima){
				window.location='pn10022.php?task=Edit&kode_manfaat='+window.kode_manfaat+'&kode_tipe_penerima='+kode_tipe_penerima+'&mid=<?=$mid;?>';
			}

			function hapus(kode_manfaat,kode_tipe_penerima){
				var r = confirm("Apakah anda yakin untuk menghapus data detil manfaat?");
				if (r == true) {
				    $.ajax({
						type: 'POST',
						url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10022_action.php?'+Math.random(),
						data: { type:'delete', DATAID:window.kode_manfaat, kode_tipe_penerima:kode_tipe_penerima},
						success: function(data) {
							preload(false);
							console.log(data);
							jdata = JSON.parse(data);
							if(jdata.ret == '0'){
								window.parent.Ext.notify.msg('Berhasil', jdata.msg);
								window.location='pn10022.php?kode_manfaat='+window.kode_manfaat+'&mid=<?=$mid;?>';
							} else {
								alert(jdata.msg);
							}
						}
					});
				} else {
				    window.parent.Ext.notify.msg('Informasi', "Hapus data dibatalkan");
				}
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
