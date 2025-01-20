<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$pagetype = "form";
$gs_pagetitle = "PN1007 - Setup Master Klaim JKK, Tipe Penerima";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "TKI";

/* ============================================================================
Ket : Form ini digunakan sebagai form input parameter formula
Hist: - 27/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
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

<div id="actmenu">
	<div id="actbutton">
		<div style="float:left;">
		<?php
		if(isset($_REQUEST["task"])){
		?>
			<?php
			if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New"){
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
					<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn1007.php?mid=<?=$mid;?>">
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
				<legend>Ubah Kode Tipe Penerima Manfaat</legend>
				<div class="form-row_kiri">
					<label>No. Urut <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_urut" id="no_urut" value="<?=$ls_no_urut;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<input type="hidden" name="TYPE" value="EDIT">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<label>Kode Tipe Penerima <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="kode_tipe_penerima" id="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" size="35" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Tipe Penerima <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_tipe_penerima" id="nama_tipe_penerima" value="<?=$ls_nama_tipe_penerima;?>" size="35" required>
				</div>
				<div class="clear"></div>
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
				<legend>Lihat Kode Tipe Penerima Manfaat</legend>	
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>No. Urut <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_urut" id="no_urut" size="17" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<input type="hidden" name="TYPE" value="VIEW">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<label>Kode Tipe Penerima <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="kode_tipe_penerima" id="kode_tipe_penerima" size="35" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Tipe Penerima <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_tipe_penerima" id="nama_tipe_penerima" size="35" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Status Non Aktif</label>	
					<select size="1" id="status_nonaktif" name="status_nonaktif" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" readonly class="disabled">
						<option value="">-- Pilih --</option>
						<option value="T" selected>T</option>
						<option value="Y">Y</option>
					</select>
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
				<legend>Tambah Kode Tipe Penerima Manfaat</legend>
				<div class="form-row_kiri">
					<label>No. Urut <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_urut" id="no_urut" value="<?=$ls_no_urut;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<input type="hidden" name="TYPE" value="NEW">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<label>Kode Tipe Penerima <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="kode_tipe_penerima" id="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" size="35" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Tipe Penerima <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_tipe_penerima" id="nama_tipe_penerima" value="<?=$ls_nama_tipe_penerima;?>" size="35" required>
				</div>
				<div class="clear"></div>
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
			</fieldset>
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
			<fieldset>
				<legend>Pencarian Data</legend>	
				<div class="form-row_kanan">
					<span style="margin-right:5px;">Search by:</span>
					<select id="type" name="type">
						<option value="KODE_TIPE_PENERIMA">Kode Tipe Penerima</option>
						<option value="NAMA_TIPE_PENERIMA">Nama Tipe Penerima</option>
					</select>
					<input  type="text" name="keyword" id="keyword" style="width:200px;" placeholder="Keyword">
					<input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
				</div>
				<div class="clear"></div>																																																																	
			</fieldset>
			<div id="formsplit">
				<fieldset>
					<legend>Kode Tipe Penerima</legend>	
					<div class="clear"></div>
					<table class="table responsive-table" id="mydata" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th scope="col" width="5%" class="align-center" style="vertical-align: middle;">Action</th>
								<th scope="col" width="8%">No. Urut</th>
								<th scope="col" width="15%">Kode Tipe Penerima</th>
								<th scope="col">Nama Tipe Penerima</th>				
							</tr>
						</thead>
						<tbody id="listdata">
						</tbody>
					</table>
					<div class="clear"></div>
					<div class="clear"></div>																																																					
				</fieldset>
				<?php
				}
				?>
				</form>
			</div>
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
				window.dataid = '';
				
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
						{ 'bSortable': false, 'aTargets': [ 0, 1 ] }
					],
				});
			
				window.onload =  loadData();
				
				$('#btn_view').click(function() {
					if(window.dataid != ''){
						window.location='pn1007.php?task=View&dataid='+window.dataid+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				
				});
				
				$('#btn_edit').click(function() {
					if(window.dataid != ''){
						window.location='pn1007.php?task=Edit&dataid='+window.dataid+'&mid=<?=$mid;?>';
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
								url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1007_action.php?'+Math.random(),
								data: { TYPE:'DEL', DATAID:window.dataid},
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
					window.location='pn1007.php?task=New&dataid='+window.dataid+'&mid=<?=$mid;?>';
				});
				
				$("#btncari").click(function() {
					loadData();
				});
				
				<?PHP
				if(isset($_REQUEST["task"])){
				?>
				window.dataid = '<?=$_REQUEST["dataid"];?>';
				//alert(window.dataid);

				<?PHP
					if($_REQUEST["task"] == "View"){
						?>
						setTimeout( function() {
						preload(true);
						}, 100);
						$.ajax({
							type: 'POST',
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1007_action.php?'+Math.random(),
							data: { TYPE:'VIEW', DATAID:window.dataid},
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+"}");	
								console.log(data);
								//console.log(JSON.parse(data));
								jdata = JSON.parse(data);
								if(jdata.ret == '0'){
									$('#kode_tipe_penerima').val(jdata.data[0].KODE_TIPE_PENERIMA);
									$('#nama_tipe_penerima').val(jdata.data[0].NAMA_TIPE_PENERIMA);
									$('#no_urut').val(jdata.data[0].NO_URUT);
									$('#status_nonaktif').val(jdata.data[0].STATUS_NONAKTIF);
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
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1007_action.php?'+Math.random(),
							data: { TYPE:'VIEW', DATAID:window.dataid},
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+"}");	
								console.log(data);
								//console.log(JSON.parse(data));
								jdata = JSON.parse(data);
								if(jdata.ret == '0'){
									$('#kode_tipe_penerima').val(jdata.data[0].KODE_TIPE_PENERIMA);
									$('#nama_tipe_penerima').val(jdata.data[0].NAMA_TIPE_PENERIMA);
									$('#no_urut').val(jdata.data[0].NO_URUT);
									$('#status_nonaktif').val(jdata.data[0].STATUS_NONAKTIF);
								}
							}
						});
						$('#btn_save').click(function() {
							if($('#no_urut').val() != '' && $('#kode_tipe_penerima').val() != '' && $('#nama_tipe_penerima').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1007_action.php?'+Math.random(),
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
				?>
				<?PHP
					if($_REQUEST["task"] == "New"){
						?>
						$('#btn_save').click(function() {
							if($('#no_urut').val() != '' && $('#kode_tipe_penerima').val() != '' && $('#nama_tipe_penerima').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1007_action.php?'+Math.random(),
									data: $('#formreg').serialize(),
									success: function(data) {
										preload(false);
										console.log('tes: '+$('#formreg').serialize());	
										console.log('test data: ' + data);
										console.log('parse: '+JSON.parse(data));
										jdata = JSON.parse(data);
										if(jdata.ret == '0'){
											//window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn1007.php?task=Edit&dataid='+$('#DATAID').val()+'&mid=<?=$mid;?>';
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
				?>
				<?PHP		
				}
				?>
			});
			
			function loadData(){
				preload(true);
				window.table
					.clear()
					.draw();
				$('input[aria-controls="mydata"]').val('');
				console.log('{ TYPE:'+$('#type').val()+', KEYWORD:'+$('#keyword').val()+'}');
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1007_query.php?'+Math.random(),
					data: { TYPE:$('#type').val(), KEYWORD:$('#keyword').val()},
					success: function(data) {	
						preload(false);	
						//console.log(data);
						jdata = JSON.parse(data);
						if(jdata.ret == 0){
							for (i = 0; i < jdata.data.length; i++) { 
								window.table.row.add( [
									'<input type="checkbox" kode_data="'+jdata.data[i].KODE_DATA+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']"><input type="hidden" name="KODE_DATA['+i+']" id="KODE_DATA'+i+'" value="'+jdata.data[i].KODE_DATA+'">',
									jdata.data[i].NO_URUT,
									jdata.data[i].KODE_TIPE_PENERIMA,
									jdata.data[i].NAMA_TIPE_PENERIMA
								] ).draw();
							}
							
							$('input[type="checkbox"]').change(function() {
								if(this.checked) {
									window.dataid= $(this).attr('KODE_DATA');
									window.selected = $(this).closest('tr');
									console.log(window.dataid);
									//alert('hello: '.v_dataid2);
								}
							});
							
							$('input[type="hidden"]').change(function() {
								if(this.clicked) {
									window.dataid= $(this).attr('KODE_DATA');
									window.selected = $(this).closest('tr');
									//console.log(v_dataid2);
									//alert('hello: '.v_dataid2);
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
									window.dataid= $(this).attr('KODE_DATA');
									window.selected = $(this).closest('tr');
									//console.log(v_dataid2);
									//console.log(window.dataid);
									//alert('hello: '.v_dataid2);
								}
							});

							console.log('pindah page');
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
				})
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
