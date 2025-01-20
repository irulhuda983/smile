<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
$pagetype = "form";
$gs_pagetitle = "PN5008 - Entri Perencanaan dan Pengajuan Kegiatan Promotif/Preventif";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "TKI";

/* ============================================================================
Ket : Form ini digunakan sebagai form input Entri Perencanaan dan Pengajuan Kegiatan Promotif/Preventif
Hist: - 09/08/2017 : Pembuatan Form (Tim SIJSTK)								 						 
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
			<br/>
			<br/>
			<div class="clear"></div>
			<div id="formsplit">
				<fieldset>
					<legend>Entri Pengajuan Kegiatan</legend>
					<table>
						<tr>
							<td width="50%" valign="top">
								<!-- start entri pengajuan kegiatan -->
								<div class="form-row_kiri">
									<label>Nama Kegiatan <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="nama_kegiatan" id="nama_kegiatan" value="<?=$ls_nama_kegiatan;?>" size="35" >
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">	
									<label>Tanggal Kegiatan <span style="color:#ff0000;">*</span></label>
									<input type="text" id="tgl_kegiatan" name="tgl_kegiatan" value="<?=$ld_tgl_kegiatan;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_kegiatan);">
									<input id="tgl_kegiatan" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_kegiatan', 'dd-mm-y');" src="../../images/calendar.gif" />						
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Jenis Kegiatan Diajukan <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="kode_jenis_kegiatan" id="kode_jenis_kegiatan" size="6" style="background-color:#ffff99;" readonly  class="disabled">
									<input  type="text" name="nama_jenis_kegiatan" id="nama_jenis_kegiatan" size="25" style="background-color:#ffff99;" readonly  class="disabled">
									<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_lov_sub_jenis_kegiatan.php?p=pn5008.php&a=formreg&b=kode_jenis_kegiatan&d=nama_jenis_kegiatan&e=kode_sub_jenis_kegiatan&f=nama_sub_jenis_kegiatan','',900,500,1)">
										<img src="../../images/help.png" alt="Cari Sub Jenis Kegiatan" border="0" align="absmiddle">
									</a>
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Sub Jenis Kegiatan <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="kode_sub_jenis_kegiatan" id="kode_sub_jenis_kegiatan" size="6" style="background-color:#ffff99;" readonly  class="disabled">
									<input  type="text" name="nama_sub_jenis_kegiatan" id="nama_sub_jenis_kegiatan" size="25" style="background-color:#ffff99;" readonly  class="disabled">
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Latar Belakang Pengajuan Kegiatan</label>
									<textarea cols="255" rows="2" id="latar_belakang_pengajuan" name="latar_belakang_pengajuan" value="<?=$ls_latar_belakang_pengajuan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
									</textarea>   
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Jumlah TK <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="jumlah_tk" id="jumlah_tk" value="<?=$ls_jumlah_tk;?>" size="6" onkeypress="validateDigit(event)">
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Jumlah Kasus KK <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="jumlah_kasuskk" id="jumlah_kasuskk" value="<?=$ls_jumlah_kasuskk;?>" size="6" onkeypress="validateDigit(event)">
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Jenis Usaha/Industrial <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="jenis_usaha" id="jenis_usaha" value="<?=$ls_jenis_usaha;?>" size="35" >
								</div>
								<!-- end entri pengajuan kegiatan -->
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Kategori Pelaksana <span style="color:#ff0000;">*</span></label>	
									<select size="1" id="kategori_pelaksana" name="kategori_pelaksana" value="<?=$ls_kategori_pelaksana;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;">
										<option value="">-- Pilih --</option>
										<? 
										  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'PROPKATPEL' order by kode asc";
										  $DB->parse($sql);
										  $DB->execute();
										  while($row = $DB->nextrow())
										  {
											  echo "<option ";
											  if ($row["KODE"]==$ls_kategori_pelaksana && strlen($ls_kategori_pelaksana)==strlen($row["KODE"])){ echo " selected"; }
											  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
										  }
										 ?>
									</select>
								</div>
								<div class="clear"></div>
								<!-- pilihan kategori pelaksana, jika pihak ketiga muncul.-->
								<div id="div_kategori_pelaksana">
								<div class="form-row_kiri">
									<label>Nama Pelaksana <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="nama_pelaksana" id="nama_pelaksana" value="<?=$ls_nama_pelaksana;?>" size="35" >
								  </div>
								<div class="clear"></div>	
								<div class="form-row_kiri">
									<label>Alamat Pelaksana <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="alamat_pelaksana" id="alamat_pelaksana" value="<?=$ls_alamat_pelaksana;?>" size="35" >
								  </div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Email Pelaksana <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="email_pelaksana" id="email_pelaksana" value="<?=$ls_email_pelaksana;?>" size="35" >
								  </div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>PIC Pelaksana <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="pic_pelaksana" id="pic_pelaksana" value="<?=$ls_pic_pelaksana;?>" size="35" >
								</div>
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>No. Telepon PIC <span style="color:#ff0000;">*</span></label>	
									<input  type="text" name="no_telp_picpelaksana" id="no_telp_picpelaksana" value="<?=$ls_no_telp_picpelaksana;?>" size="35" onkeypress="validateDigit(event)">
								</div>
								<!-- end  pilihan kategori pelaksana, jika pihak ketiga muncul -->
								<div class="clear"></div>
								<div class="form-row_kiri">
									<label>Keterangan</label>
									<textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?=$ls_keterangan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)">
									</textarea>   
								</div>
								<div class="clear"></div>
							</td>
							<td width="50%" valign="top">
								<div class="form-row_kanan">
									<label>Kode Promotif</label>	
									<input type="hidden" name="TYPE" value="EDIT">
									<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
									<input type="hidden" id="DATAID2" name="DATAID2" value="<?=$_REQUEST["dataid2"];?>">
									<input  type="text" name="kode_promotif" id="kode_promotif" value="<?=$_REQUEST["dataid"];?>" size="35" readonly class="disabled">				
								</div>
								<div class="clear"></div>
								<div class="form-row_kanan">
									<label>NPP</label>	
									<input  type="text" name="npp" id="npp" value="<?=$ls_npp;?>" size="35" readonly class="disabled">				
								</div>
								<div class="clear"></div>
								<div class="form-row_kanan">
									<label>Nama Perusahaan</label>	
									<input  type="text" name="nama_perusahaan" id="nama_perusahaan" value="<?=$ls_nama_perusahaan;?>" size="35" readonly class="disabled">				
								</div>
								<div class="clear"></div>
								<div class="form-row_kanan">
									<label>Cabang Kepesertaan</label>	
									<input  type="text" name="cabang_kepesertaan" id="cabang_kepesertaan" value="<?=$ls_cabang_kepesertaan;?>" size="35" readonly class="disabled">				
								</div>
								<div class="clear"></div>
							</td>
						</tr>
					</table>	
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
			<fieldset>
				<legend>Detail Pengajuan Kegiatan</legend>	
				<div class="form-row_kiri">
					<label>Kode Promotif</label>	
					<input type="hidden" name="TYPE" value="VIEW">
					<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
					<input type="hidden" id="DATAID2" name="DATAID2" value="<?=$_REQUEST["dataid2"];?>">
					<input  type="text" name="kode_promotif" id="kode_promotif" value="<?=$ls_kode_promotif;?>" size="35" readonly class="disabled">				
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
				<div class="clear"></div>
				<div class="form-row_kiri">	
					<label>Tanggal Kegiatan <span style="color:#ff0000;">*</span></label>
					<input type="text" id="tgl_kegiatan" name="tgl_kegiatan" value="<?=$ld_tgl_kegiatan;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_kegiatan);" readonly class="disabled">
					<input id="tgl_kegiatan" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_kegiatan', 'dd-mm-y');" src="../../images/calendar.gif" disabled />						
				</div>
				<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Jenis Kegiatan <span style="color:#ff0000;">*</span></label>	
						<input  type="text" name="kode_jenis_kegiatan" id="kode_jenis_kegiatan" size="6" style="background-color:#ffff99;" readonly  class="disabled">
						<input  type="text" name="nama_jenis_kegiatan" id="nama_jenis_kegiatan" size="25" style="background-color:#ffff99;" readonly  class="disabled">
						<img src="../../images/help.png" alt="Cari Sub Jenis Kegiatan" border="0" align="absmiddle">
					</div>
					<div class="clear"></div>
					<div class="form-row_kiri">
						<label>Sub Jenis Kegiatan <span style="color:#ff0000;">*</span></label>	
						<input  type="text" name="kode_sub_jenis_kegiatan" id="kode_sub_jenis_kegiatan" size="6" style="background-color:#ffff99;" readonly  class="disabled">
						<input  type="text" name="nama_sub_jenis_kegiatan" id="nama_sub_jenis_kegiatan" size="25" style="background-color:#ffff99;" readonly  class="disabled">
					</div>
					<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Nama Kegiatan <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_kegiatan" id="nama_kegiatan" value="<?=$ls_nama_kegiatan;?>" size="35" readonly class="disabled">
				  </div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Kategori Pelaksana <span style="color:#ff0000;">*</span></label>	
					<select size="1" id="kategori_pelaksana" name="kategori_pelaksana" value="<?=$ls_kategori_pelaksana;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
						<option value="">-- Pilih --</option>
						<? 
						  $sql = "select tipe,kode,keterangan,aktif,seq,kategori from sijstk.ms_lookup where tipe = 'PROPKATPEL' order by kode asc";
						  $DB->parse($sql);
						  $DB->execute();
						  while($row = $DB->nextrow())
						  {
							  echo "<option ";
							  if ($row["KODE"]==$ls_kategori_pelaksana && strlen($ls_kategori_pelaksana)==strlen($row["KODE"])){ echo " selected"; }
							  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
						  }
						 ?>
					</select>
				</div>
				<div class="clear"></div>
				<!-- pilihan kategori pelaksana, jika pihak ketiga muncul.-->
				<div id="div_kategori_pelaksana">
				<div class="form-row_kiri">
					<label>Nama Pelaksana <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="nama_pelaksana" id="nama_pelaksana" value="<?=$ls_nama_pelaksana;?>" size="35" readonly class="disabled">
				  </div>
				<div class="clear"></div>	
				<div class="form-row_kiri">
					<label>Alamat Pelaksana <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="alamat_pelaksana" id="alamat_pelaksana" value="<?=$ls_alamat_pelaksana;?>" size="35" readonly class="disabled">
				  </div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>Email Pelaksana <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="email_pelaksana" id="email_pelaksana" value="<?=$ls_email_pelaksana;?>" size="35" readonly class="disabled">
				  </div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>PIC Pelaksana <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="pic_pelaksana" id="pic_pelaksana" value="<?=$ls_pic_pelaksana;?>" size="35" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label>No. Telepon PIC <span style="color:#ff0000;">*</span></label>	
					<input  type="text" name="no_telp_picpelaksana" id="no_telp_picpelaksana" value="<?=$ls_no_telp_picpelaksana;?>" size="35" readonly class="disabled">
				</div>
				<!-- end  pilihan kategori pelaksana, jika pihak ketiga muncul -->
				<div class="clear"></div>
				</div>
				<div class="form-row_kiri">
					<label>Keterangan</label>
					<textarea cols="255" rows="2" id="keterangan" name="keterangan" value="<?=$ls_keterangan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
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
			<?php
			};
			?>
			<?php
			} else {
			?>
			<br>	
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
					<legend>Daftar Perusahaan Yang Memenuhi Persyaratan Kegiatan Promotif dan Preventif</legend>	
					<div class="clear"></div>
					<table class="table responsive-table" id="mydata" cellspacing="0" width="100%">
						<thead>
							<tr>
								<th scope="col" width="5%" class="align-center" style="vertical-align: middle;">Action</th>
								<th scope="col" width="10%">NPP</th>	
								<th scope="col" width="5%">Kode Divisi</th>	
								<th scope="col">Nama Perusahaan</th>
								<th scope="col" width="10%">Jml Kasus KK</th>
								<th scope="col" width="10%">Jml Kasus PAK</th>
								<th scope="col" width="10%">Jml Tenaga Kerja</th>
								<th scope="col" width="10%">Incident Rate</th>
								<th scope="col" width="10%">Cetak Surat</th>										
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
						<input type="button" class="btn green" id="btncetakdaftarperusahaan" name="btncetakdaftarperusahaan" tabindex="41" value="CETAK DAFTAR PERUSAHAAN" title="Klik Untuk Cetak Daftar Perusahaan">	
						&nbsp;
						<input type="button" class="btn green" id="btnpermintaanverifikasi" name="btnpermintaanverifikasi" tabindex="41" value="KIRIM PERMINTAAN VERIFIKASI KE KBP DAN WASRIK" title="Klik Untuk Kirim Permintaan Verifikasi ke KBP dan Wasrik">	
					</div>
				</fieldset>
				
				<?php
				}
				?>
				</form>
			</div>
			<!--- detail tenaga kerja -->
			<?php
			if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View")
			{
			?>
			</br>
			<div class="form-row_kiri">
				<input type="button" name="btntambahtkpengajuan" id="btntambahtkpengajuan" class="btn green"  value="TAMBAH TENAGA KERJA PENGAJUAN" title="Klik untuk tambah TK pengajuan">
			</div>
			</br>
			</br>
			<div class="clear"></div>
			<div id="formsplit">
				<fieldset>
					<legend>Daftar Tenaga Kerja Pengajuan</legend>	
					<div class="clear"></div>
					<table class="table responsive-table" id="" cellspacing="0" width="100%">
						<thead style="background: #ededed;">
							<tr>
								<th scope="col" width="15%" style="text-align:center;padding:5px;">No. Ref</th>	
								<th scope="col" width="15%" style="text-align:center;padding:5px;">NIK</th>
								<th scope="col" style="text-align:left;padding:5px;">Nama TK</th>
								<th scope="col" width="15%" style="text-align:center;padding:5px;">Tgl. Lahir</th>
								<?php
								if ($_REQUEST["tasktk"] == "Edit")
								{
								?>
								<th scope="col" width="5%" class="align-center" style="vertical-align: middle;padding:5px;">Action</th>								
								<?php
								}
								?>
							</tr>
						</thead>
						<tbody id="">
						<?php
							$kodepromotif = $_REQUEST["dataid"];
							$sql = "select kode_promotif,kode_tk,nama_tk,kpj,nomor_identitas,to_char(tgl_lahir,'dd-mm-yyyy')tgl_lahir
									from sijstk.pn_promotif_detil 
									where kode_promotif = '".$kodepromotif."'
									order by nama_tk asc ";
							$DB->parse($sql);
							$DB->execute();
							while($row = $DB->nextrow()) 
							{
						?>
							<tr>
								<td style="text-align:center;"><?php echo $row['KPJ']; ?></td>
								<td style="text-align:center;"><?php echo $row['NOMOR_IDENTITAS']; ?></td>
								<td style="text-align:left;"><?php echo $row['NAMA_TK']; ?></td>
								<td style="text-align:center;"><?php echo $row['TGL_LAHIR']; ?></td>
								<?php
								if ($_REQUEST["tasktk"] == "Edit")
								{
								?>
								<td style="text-align:center;">
									<a id="btn_deletetk" href="javascript:void(0)">
										<img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0" title="Klik untuk menghapus data"> 
									</a>
								</td>
								<?php
								}
								?>
							</tr>
					    <?php
							};
				        ?>
						</tbody>
					</table>
					<div class="clear"></div>
					<div class="clear"></div>																																																					
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
        <script type="text/javascript">
			$(document).ready(function(){
				window.dataid = '';
				window.kodeperusahaan = '';
				
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
				
				/*
				var kategori_pelaksana =  document.getElementById('kategori_pelaksana').value;
				if (kategori_pelaksana = 'PERUSAHAAN')
				{
					$('#div_kategori_pelaksana').attr("style", "display:none");
				}
				else
				{
					$('#div_kategori_pelaksana').removeAttr("style", "display:none");
				}
				*/
				$("#kategori_pelaksana").change(function() {	
					var kategori_pelaksana =  this.options[document.getElementById('kategori_pelaksana').selectedIndex].text;
					if(kategori_pelaksana = 'PERUSAHAAN')
					{
						alert(kategori_pelaksana);
					}
					//this.options[document.getElementById('tki_negaradesc').selectedIndex].text
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
									window.parent.Ext.notify.msg('Berhasil', "Data Berhasil dihapus!");
								}
							});
							
						} 
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				
				function deleteTKPengajuan(kode_tk){
					if(kode_tk != ''){
						var r = confirm("Apakah anda yakin akan menghapus data?"+kode_tk);
						if (r == true) {
							$.ajax({
								type: 'POST',
								url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
								data: { TYPE:'DEL', DATATKID:kode_tk},
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
				}
				
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
					cetakDokumenPdf("RPT500802","")
				});
				
				$("#btnpermintaanverifikasi").click(function() {						
					submitApprovalKBP();
				});
				
				
				function deleteTKPengajuan(kode_tk){
					if(kode_tk != ''){
						var r = confirm("Apakah anda yakin akan menghapus data?"+kode_tk);
						if (r == true) {
							
							
						} 
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				}				
				

				
				$('#btn_save').click(function() {
							if($('#tgl_kegiatan').val() != '' && $('#jenis_kegiatan').val() != '' && $('#nama_kegiatan').val() != ''
								 && $('#kategori_pelaksana').val() != '' && $('#nama_pelaksana').val() != '' && $('#email_pelaksana').val() != '' 
								 && $('#alamat_pelaksana').val() != '' && $('#pic_pelaksana').val() != '' && $('#no_telp_picpelaksana').val() != ''){
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
								alert('Pastikan semua data mandatori diisi!');
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
									$('#nama_kegiatan').val(jdata.data[0].NAMA_DETIL_KEGIATAN);
									$('#tgl_kegiatan').val(jdata.data[0].TGL_KEGIATAN);
									$('#kode_jenis_kegiatan').val(jdata.data[0].KODE_KEGIATAN);
									$('#nama_jenis_kegiatan').val(jdata.data[0].NAMA_KEGIATAN);
									$('#kode_sub_jenis_kegiatan').val(jdata.data[0].KODE_SUB_KEGIATAN);
									$('#nama_sub_jenis_kegiatan').val(jdata.data[0].NAMA_SUB_KEGIATAN);
									$('#latar_belakang_pengajuan').val(jdata.data[0].LATAR_BELAKANG_PENGAJUAN);
									$('#jumlah_tk').val(jdata.data[0].JML_TK_PENGAJUAN);
									$('#jumlah_kasuskk').val(jdata.data[0].JML_KK_PENGAJUAN);
									$('#jenis_usaha').val(jdata.data[0].JENIS_USAHA_PENGAJUAN);
									$('#kategori_pelaksana').val(jdata.data[0].KATEGORI_PELAKSANA);
									$('#nama_pelaksana').val(jdata.data[0].NAMA_PELAKSANA);
									$('#alamat_pelaksana').val(jdata.data[0].ALAMAT_PELAKSANA);
									$('#email_pelaksana').val(jdata.data[0].EMAIL_PELAKSANA);
									$('#pic_pelaksana').val(jdata.data[0].PIC_PELAKSANA);
									$('#no_telp_picpelaksana').val(jdata.data[0].NO_TELP_PIC_PELAKSANA);
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
									$('#nama_kegiatan').val(jdata.data[0].NAMA_DETIL_KEGIATAN);
									$('#tgl_kegiatan').val(jdata.data[0].TGL_KEGIATAN);
									$('#kode_jenis_kegiatan').val(jdata.data[0].KODE_KEGIATAN);
									$('#nama_jenis_kegiatan').val(jdata.data[0].NAMA_KEGIATAN);
									$('#kode_sub_jenis_kegiatan').val(jdata.data[0].KODE_SUB_KEGIATAN);
									$('#nama_sub_jenis_kegiatan').val(jdata.data[0].NAMA_SUB_KEGIATAN);
									$('#latar_belakang_pengajuan').val(jdata.data[0].LATAR_BELAKANG_PENGAJUAN);
									$('#jumlah_tk').val(jdata.data[0].JML_TK_PENGAJUAN);
									$('#jumlah_kasuskk').val(jdata.data[0].JML_KK_PENGAJUAN);
									$('#jenis_usaha').val(jdata.data[0].JENIS_USAHA_PENGAJUAN);
									$('#kategori_pelaksana').val(jdata.data[0].KATEGORI_PELAKSANA);
									$('#nama_pelaksana').val(jdata.data[0].NAMA_PELAKSANA);
									$('#alamat_pelaksana').val(jdata.data[0].ALAMAT_PELAKSANA);
									$('#email_pelaksana').val(jdata.data[0].EMAIL_PELAKSANA);
									$('#pic_pelaksana').val(jdata.data[0].PIC_PELAKSANA);
									$('#no_telp_picpelaksana').val(jdata.data[0].NO_TELP_PIC_PELAKSANA);
									$('#keterangan').val(jdata.data[0].KETERANGAN);
								}
							}
						});
						
						$('#btn_save').click(function() {
							if($('#tgl_kegiatan').val() != '' && $('#jenis_kegiatan').val() != '' && $('#nama_kegiatan').val() != ''
								 && $('#kategori_pelaksana').val() != '' && $('#nama_pelaksana').val() != '' && $('#email_pelaksana').val() != '' 
								 && $('#alamat_pelaksana').val() != '' && $('#pic_pelaksana').val() != '' && $('#no_telp_picpelaksana').val() != ''){
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
								alert('Pastikan semua data mandatori diisi!');
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
									url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_action.php?'+Math.random(),
									data: $('#formreg').serialize(),
									success: function(data) {
										preload(false);
										console.log('tes: '+$('#formreg').serialize());	
										console.log('test data: ' + data);
										console.log('parse: '+JSON.parse(data));
										jdata = JSON.parse(data);
										if(jdata.ret == '0'){
											//window.parent.Ext.notify.msg('Berhasil', jdata.msg);
											window.location='pn5008.php?task=Edit&dataid='+$('#DATAID').val()+'&mid=<?=$mid;?>';
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
					if($_REQUEST["tasktk"] == "View" || $_REQUEST["tasktk"] == "Edit"){
				?>
						window.table = $('#mydatatk').DataTable({
							"scrollCollapse": true,
							"paging": true,
							"iDisplayLength": 10,
							'sPaginationType': 'full_numbers',
							"stateSave": true,
							'aoColumnDefs': [
								{ 'bSortable': false, 'aTargets': [  ] }
							],
						});
						
						loadDataTenagaKerja('<?php echo $_REQUEST["dataid"]?>');
						$("#btntambahtkpengajuan").click(function() {						
							NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_lov_kpj.php?kode_kantor=<?php echo $ls_kode_kantor?>&kode_promotif=<?php echo $_REQUEST["dataid"]?>&kode_perusahaan=<?php echo $_REQUEST["dataid2"]?>','PN500801 - Daftar Tenaga Kerja Pengajuan Kegiatan','1024','600','yes');
						});
				<?PHP
					};
				?>
				
				<?PHP		
				}
				?>
			});
			
			var paul =[];
			
				function submitApprovalKBP(){
					preload(true);
					var length = paul.length;
					if (length > 0){
						var r = confirm("Sebanyak "+length+" data pengajuan yang akan dikirim kepada KBP untuk diverifikasi");
						if (r == true) {
							for (var i = 0; i < length; i++) {
								var obj = paul[i];
								$.ajax({
										type: 'POST',
										url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5012_action.php?'+Math.random(),
										data: { TYPE:'KIRIM', DATATKID:obj},
										success: function(data) {
											window.selected.slideUp(function(){						
												$(this).remove();					
											});
											window.parent.Ext.notify.msg('Berhasil', "Data Berhasil dihapus!");
										}
								});
							}
						}
					else{
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				}

			
			function loadData(){
				preload(true);
				window.table
					.clear()
					.draw();
				$('input[aria-controls="mydata"]').val('');
				console.log('{ TYPE:'+$('#type').val()+', KEYWORD:'+$('#keyword').val()+'}');
			
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
									jdata.data[i].NPP,
									jdata.data[i].KODE_DIVISI,
									jdata.data[i].NAMA_PERUSAHAAN,
									jdata.data[i].JML_KASUS_KK,
									jdata.data[i].JML_KASUS_PAK,
									jdata.data[i].JML_TK,
									jdata.data[i].INSIDEN_RATE,
									'<input type="button" class="btn green" onclick=cetakDokumenPdf("RPT500801","'+jdata.data[i].KODE_PROMOTIF+'") KODE_PROMOTIF="'+jdata.data[i].KODE_PROMOTIF+'" id="BTNCETAK_'+i+'" urut="'+i+'" name="BTNCETAK['+i+']" value="Cetak Surat" title="Klik Untuk Mencetak Surat Pemberitahuan">'
									
								] ).draw();
							}
							
							$('input[type="checkbox"]').change(function() {
								if(this.checked) {
									window.dataid= $(this).attr('KODE_PROMOTIF');
									window.kodeperusahaan = $(this).attr('KODE2');
									window.selected = $(this).closest('tr');
									paul.push(window.dataid);
									console.log(paul);
									//alert('hello: '.window.kodeperusahaan);
								}else{
									window.dataid= $(this).attr('KODE_PROMOTIF');
									paul.splice($.inArray(window.dataid, paul), 1);
									console.log(paul);
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
									//console.log(window.kodeperusahaan);
									//console.log(window.dataid);
									//alert('hello: '.window.kodeperusahaan);
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
				});
			}
			
			
			function loadDataTenagaKerja(kode_promotif){			
				preload(true);
				window.table
					.clear()
					.draw();
				$('input[aria-controls="mydatatk"]').val('');
				console.log('{ TYPE:'+$('#type').val()+', KEYWORD:'+$('#keyword').val()+'}');
			
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5008_query_tk.php?KODEPROMOTIF='+kode_promotif+'&'+Math.random(),
					datatk: { TYPE:$('#type').val(), KEYWORD:$('#keyword').val()},
					success: function(datatk) {	
						preload(false);	
						//console.log(data);
						jdata = JSON.parse(datatk);
						if(jdata.ret == 0){
							for (i = 0; i < jdata.datatk.length; i++) { 
								window.table.row.add( [
									'<input type="checkbox" KODE_TK="'+jdata.datatk[i].KODE_TK+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']"><input type="hidden" name="KODE_TK['+i+']" id="KODE_TK'+i+'" value="'+jdata.datatk[i].KODE_TK+'">',
									jdata.datatk[i].KODE_TK,
									jdata.datatk[i].NAMA_TK,
									jdata.datatk[i].KPJ,
									jdata.datatk[i].NOMOR_IDENTITAS,
									jdata.datatk[i].TGL_LAHIR,
									'<input type="button" class="btn green" KODE_TK="'+datatk.data[i].KODE_TK+'" id="btnhapustk'+i+'" urut="'+i+'" name="btnhapustk['+i+']" value="delete" title="Klik menghapus tenaga kerja pengajuan">'
								] ).draw();
							}
							
							$('input[type="checkbox"]').change(function() {
								if(this.checked) {
									window.dataid= $(this).attr('KODE_TK');
									window.selected = $(this).closest('tr');
									console.log(window.dataid);
									//alert('hello: '.window.kodeperusahaan);
								}
							});
							
							$('input[type="hidden"]').change(function() {
								if(this.clicked) {
									window.dataid= $(this).attr('KODE_TK');
									window.selected = $(this).closest('tr');
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
						window.table = $('#mydatatk').DataTable();
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
									window.dataid= $(this).attr('KODE_TK');
									window.selected = $(this).closest('tr');
									//console.log(window.kodeperusahaan);
									//console.log(window.dataid);
									//alert('hello: '.window.kodeperusahaan);
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
			
			
		</script>
<?php
include "../../includes/footer_app_nosql.php";
?>
