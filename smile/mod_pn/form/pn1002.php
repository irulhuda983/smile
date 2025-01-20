<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$pagetype = "form";
$gs_pagetitle = "PN1002 - Setup Manfaat Klaim";
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

<?php
$ls_user_login = $_SESSION["USER"];
$gs_kantor_aktif = $_SESSION['gs_kantor_aktif'];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;

$ls_jenis_err = "";
$ls_message_err = "";

?>

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
					<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn1002.php?mid=<?=$mid;?>">
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
				<legend>Edit Manfaat Klaim</legend>
				<div class="form-row_kiri">
					<label>No Urut <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_urut" id="no_urut" value="<?=$ls_no_urut;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Manfaat <span style="color:#ff0000;">*</span></label>	
					<input type="hidden" name="type" id="type" value="edit">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<input  type="text" name="kode_manfaat" id="kode_manfaat" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Manfaat <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_manfaat" id="nama_manfaat" size="35" required>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Ketegori Manfaat <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="kategori_manfaat" name="kategori_manfaat" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'KATMNFKLM' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_kategori_manfaat && strlen($ls_kategori_manfaat)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
						  }
						 ?>
					</select>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Jenis Manfaat <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="jenis_manfaat" name="jenis_manfaat" value="<?=$ls_jenis_manfaat;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'JNSMNFKLM' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_jenis_manfaat && strlen($ls_jenis_manfaat)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
						  }
						 ?>
					</select>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Tipe Manfaat <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="tipe_manfaat" name="tipe_manfaat" value="<?=$ls_tipe_manfaat;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'TIPMNFKLM' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_tipe_manfaat && strlen($ls_tipe_manfaat)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
						  }
						 ?>
					</select>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Flag Berkala</label>	
					<select size="1" id="flag_berkala" name="flag_berkala" value="<?=$ls_flag_berkala;?>" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
						<option value="">-- Pilih --</option>
						<option value="T" selected>T</option>
						<option value="Y">Y</option>
					</select>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Formula <span style="color:#ff0000;">*</span></label>	
					<input  type="hidden" name="kode_formula" id="kode_formula" size="5" style="background-color:#ffff99;" readonly  class="disabled">
					<input  type="text" name="formula" id="formula" size="35" style="background-color:#ffff99;" readonly  class="disabled">
					<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_lov_formula.php?p=pn10021.php&a=formreg&b=kode_formula&d=formula','',900,500,1)">
						<img src="../../images/help.png" alt="Cari Parameter" border="0" align="absmiddle">
					</a>
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
					<label>URL Path <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="url_path" id="url_path" value="<?=$ls_url_path;?>" size="35" style="background-color:#ffff99;" readonly  class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Keterangan</label>
					<textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?=$ls_keterangan;?>" tabindex="8" style="width:225px;background-color:#ffff99;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
					</textarea>   
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
				<legend>Lihat Manfaat Klaim</legend>	
				<div class="form-row_kiri">
					<label>No Urut</label>	
					<input  type="text" name="no_urut" id="no_urut" value="<?=$ls_no_urut;?>" size="17" readonly>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Manfaat Klaim</label>	
					<input type="hidden" name="TYPE" value="VIEW">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<input  type="text" name="kode_parameter" id="kode_parameter" value="<?=$_REQUEST["dataid"];?>" size="35" readonly>				
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Parameter</label>	
					<input  type="text" name="nama_parameter" id="nama_parameter"  size="35" readonly>
				  </div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Jenis Parameter</label>	
					<select size="1" id="jenis_parameter" name="jenis_parameter" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" disabled>
						<option value="">-- Pilih --</option>
						<option value="VARIABEL">VARIABEL</option>
						<option value="OPERATOR">OPERATOR</option>
					</select>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Jenis Return Parameter</label>	
					<select size="1" id="jenis_return_parameter" name="jenis_return_parameter" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" disabled>
						<option value="">-- Pilih --</option>
						<option value="CHAR">CHAR</option>
						<option value="DATE">DATE</option>
						<option value="NUMBER">NUMBER</option>
					</select>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Status Non Aktif</label>	
					<select size="1" id="status_nonaktif" name="status_nonaktif" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" disabled>
						<option value="">-- Pilih --</option>
						<option value="Y">Y</option>
						<option value="T">T</option>
					</select>
				</div>
				<div class="clear"></div>	
				<div class="form-row_kiri">
					<label>Keterangan</label>
					<textarea cols="255" rows="2" id="keterangan" name="keterangan" tabindex="8" style="width:225px;background-color:#ffff99;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
					</textarea>   
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
				<legend>Tambah Manfaat Klaim</legend>
				<div class="form-row_kiri">
					<label>No Urut <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_urut" id="no_urut" value="<?=$ls_no_urut;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kode Manfaat <span style="color:#ff0000;">*</span></label>	
					<input type="hidden" name="type" id="type" value="new">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<input  type="text" name="kode_manfaat" id="kode_manfaat" value="<?=$ls_kode_manfaat;?>" size="17" onkeypress="validateDigit(event)" required>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Manfaat <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_manfaat" id="nama_manfaat" value="<?=$ls_nama_manfaat;?>" size="35" required>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Ketegori Manfaat <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="kategori_manfaat" name="kategori_manfaat" value="<?=$ls_kategori_manfaat;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'KATMNFKLM' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_kategori_manfaat && strlen($ls_kategori_manfaat)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
						  }
						 ?>
					</select>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Jenis Manfaat <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="jenis_manfaat" name="jenis_manfaat" value="<?=$ls_jenis_manfaat;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'JNSMNFKLM' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_jenis_manfaat && strlen($ls_jenis_manfaat)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
						  }
						 ?>
					</select>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Tipe Manfaat <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="tipe_manfaat" name="tipe_manfaat" value="<?=$ls_tipe_manfaat;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'TIPMNFKLM' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_tipe_manfaat && strlen($ls_tipe_manfaat)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
						  }
						 ?>
					</select>	
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Flag Berkala</label>	
					<select size="1" id="flag_berkala" name="flag_berkala" value="<?=$ls_flag_berkala;?>" tabindex="7" class="select_format" style="width:120px;background-color:#ffff99;" required>
						<option value="">-- Pilih --</option>
						<option value="T" selected>T</option>
						<option value="Y">Y</option>
					</select>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Formula <span style="color:#ff0000;">*</span></label>	
					<input  type="hidden" name="kode_formula" id="kode_formula" size="5" style="background-color:#ffff99;" readonly  class="disabled">
					<input  type="text" name="formula" id="formula" size="35" style="background-color:#ffff99;" readonly  class="disabled">
					<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn10021_lov_formula.php?p=pn10021.php&a=formreg&b=kode_formula&d=formula','',900,500,1)">
						<img src="../../images/help.png" alt="Cari Parameter" border="0" align="absmiddle">
					</a>
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
					<label>URL Path <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="url_path" id="url_path" value="<?=$ls_url_path;?>" size="35" style="background-color:#ffff99;" readonly  class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Keterangan</label>
					<textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?=$ls_keterangan;?>" tabindex="8" style="width:225px;background-color:#ffff99;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
					</textarea>   
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
						<option value="KODE_MANFAAT">Kode Manfaat</option>
						<option value="NAMA_MANFAAT">Nama Manfaat</option>
					</select>
					<input  type="text" name="keyword" id="keyword" style="width:200px;" placeholder="Keyword">
					<input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
				</div>
				<div class="clear"></div>																																																																	
			</fieldset>
			<div id="formsplit">
				<fieldset>
					<legend>Kode Manfaat</legend>	
					<div class="clear"></div>
					<table class="table responsive-table" id="mydata" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th scope="col" width="5%" class="align-center" style="vertical-align: middle;">Action</th>
								<th scope="col" width="10%">Kode Manfaat</th>
								<th scope="col">Nama Manfaat</th>
								<th scope="col" width="15%">Kategori Manfaat</th>
								<th scope="col" width="15%">Jenis Manfaat</th>
								<th scope="col" width="15%">Tipe Manfaat</th>
								<th scope="col" width="10%">Flag Berkala</th>
								<th scope="col" width="10%">Action</th>
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
			<div class="clear"></div>
			<fieldset style="background: #ededed; display: none">
				<div class="clear"></div>	
				<div class="form-row_kanan">
					<br>
					<input type="button" class="btn green" id="btnmanfaatdetil" name="btnmanfaatdetil" tabindex="41" value="Tambah Manfaat Detil" title="Klik Untuk Tambah Manfaat Detil">
					&nbsp;
					<input type="button" class="btn green" id="btnmanfaateligibility" name="btnmanfaateligibility" tabindex="42" value="Tambah Manfaat Eligibility" title="Klik Untuk Tambah Manfaat Eligibility">		
				</div>
			</fieldset>
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
						{ 'bSortable': false, 'aTargets': [ 0 ] }
					],
				});
			
				window.onload =  loadData();
				
				//========================================== BUTTON ACTION ===========================================================

				$('#btn_view').click(function() {
					if(window.dataid != ''){
						window.location='pn1002.php?task=View&dataid='+window.dataid+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				
				});
				
				$('#btn_edit').click(function() {
					if(window.dataid != ''){
						window.location='pn1002.php?task=Edit&dataid='+window.dataid+'&mid=<?=$mid;?>';
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
								url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
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
					window.location='pn1002.php?task=New&dataid='+window.dataid+'&mid=<?=$mid;?>';
				});
				
				$("#btncari").click(function() {
					loadData();
				});

				$("#btnmanfaatdetil").click(function() {
					if($('input[name^="CHECK"][type=checkbox]:checked').length>0)
					{
						if($('input[name^="CHECK"][type=checkbox]:checked').length<2){	
							window.dataid = $('input[name^="CHECK"][type=checkbox]:checked').attr("kode_manfaat");
							NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn10021.php?kode_manfaat='+window.dataid,'Detail Manfaat '+window.dataid,'75%','75%','no');
						}else{
							alert('Untuk melakukan edit hanya bisa dipilih satu record');
						}
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});

				//===============================================END OF BUTTON===========================================
				
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
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
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
									$('#kode_parameter').val(jdata.data[0].KODE_PARAMETER);
									$('#nama_parameter').val(jdata.data[0].NAMA_PARAMETER);
									$('#jenis_parameter').val(jdata.data[0].JENIS_PARAMETER);
									$('#jenis_return_parameter').val(jdata.data[0].JENIS_RETURN_PARAMETER);
									$('#no_urut').val(jdata.data[0].NO_URUT);
									$('#status_nonaktif').val(jdata.data[0].STATUS_NONAKTIF);
									$('#keterangan').val(jdata.data[0].KETERANGAN);	
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
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
							data: { type:'view', DATAID:window.dataid},
							success: function(data) {
								setTimeout( function() {
								preload(false);
								}, 100); 
								console.log("{ type:'view', DATAID:"+window.dataid+"}");	
								console.log(data);
								console.log(JSON.parse(data));
								jdata = JSON.parse(data);								
								if(jdata.ret == '0'){
									console.log("KODE MANFAAT : "+jdata.KODE_MANFAAT);
									$('#kode_manfaat').val(jdata.KODE_MANFAAT);
									$('#nama_manfaat').val(jdata.NAMA_MANFAAT);
									$('#kategori_manfaat').val(jdata.KATEGORI_MANFAAT);
									$('#jenis_manfaat').val(jdata.JENIS_MANFAAT);
									$('#tipe_manfaat').val(jdata.TIPE_MANFAAT);
									$('#flag_berkala').val(jdata.FLAG_BERKALA);
									$('#formula').val(jdata.FORMULA);
									$('#no_urut').val(jdata.NO_URUT);
									$('#status_nonaktif').val(jdata.STATUS_NONAKTIF);
									$('#keterangan').val(jdata.TGL_NONAKTIF);
									$('#url_path').val(jdata.TIPE_MANFAAT);
								}
							}
						});
						$('#btn_save').click(function() {
							if($('#kode_parameter').val() != '' && $('#nama_parameter').val() != '' && $('#jenis_parameter').val() != '' && $('#jenis_return_parameter').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
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
							if($('#kode_parameter').val() != '' && $('#nama_parameter').val() != '' && $('#jenis_parameter').val() != '' && $('#jenis_return_parameter').val() != ''){
								preload(true);
								$.ajax({
									type: 'POST',
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_action.php?'+Math.random(),
									data: $('#formreg').serialize(),
									success: function(data) {
										preload(false);
										jdata = JSON.parse(data);
										if(jdata.ret == '0'){
											window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn1002.php?task=Edit&dataid='+$('#kode_manfaat').val()+'&mid=<?=$mid;?>';
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
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn1002_query.php?'+Math.random(),
					data: { TYPE:$('#type').val(), KEYWORD:$('#keyword').val()},
					success: function(data) {	
						preload(false);	
						//console.log(data);
						jdata = JSON.parse(data);
						if(jdata.ret == 0){
							for (i = 0; i < jdata.data.length; i++) { 
								window.table.row.add( [
									'<input type="checkbox" kode_manfaat="'+jdata.data[i].KODE_MANFAAT+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']"><input type="hidden" name="KODE_MANFAAT['+i+']" id="KODE_MANFAAT'+i+'" value="'+jdata.data[i].KODE_MANFAAT+'">',
									jdata.data[i].KODE_MANFAAT,
									jdata.data[i].NAMA_MANFAAT,
									jdata.data[i].KATEGORI_MANFAAT,
									jdata.data[i].JENIS_MANFAAT,
									jdata.data[i].TIPE_MANFAAT,
									jdata.data[i].FLAG_BERKALA,
									'<input type="button" style="width: 105px" class="btn green" kode_manfaat="'+jdata.data[i].KODE_MANFAAT+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']" value="Manfaat Detil" onclick="add(\''+jdata.data[i].KODE_MANFAAT+'\',\''+jdata.data[i].NAMA_MANFAAT+'\')"><input type="button" style="width: 105px" class="btn green" kode_manfaat="'+jdata.data[i].KODE_MANFAAT+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']" value="Manfaat Eligibility" onclick="add_eligibility(\''+jdata.data[i].KODE_MANFAAT+'\',\''+jdata.data[i].NAMA_MANFAAT+'\')"><input type="hidden" name="KODE_MANFAAT['+i+']" id="KODE_MANFAAT'+i+'" value="'+jdata.data[i].KODE_MANFAAT+'">'
								] ).draw();
							}
							
							$('input[type="checkbox"]').change(function() {
								if(this.checked) {
									window.dataid= $(this).attr('KODE_MANFAAT');
									window.selected = $(this).closest('tr');
									console.log(window.dataid);
									//alert('hello: '.v_dataid2);
								}
							});
							
							$('input[type="hidden"]').change(function() {
								if(this.clicked) {
									window.dataid= $(this).attr('KODE_MANFAAT');
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
									window.dataid= $(this).attr('KODE_MANFAAT');
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

			function add(kode_manfaat,nama_manfaat){
				// alert(kode_manfaat);
				NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn10021.php?kode_manfaat='+kode_manfaat,'Detail Manfaat [ '+kode_manfaat+' ]  '+nama_manfaat,'75%','75%','no');
			}
			
			function add_eligibility(kode_manfaat,nama_manfaat){
				// alert(kode_manfaat);
				NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/form/pn10022.php?kode_manfaat='+kode_manfaat,'Detail Manfaat [ '+kode_manfaat+' ]  '+nama_manfaat,'75%','75%','no');
			}

		</script>
<?php
include "../../includes/footer_app_nosql.php";
?>
