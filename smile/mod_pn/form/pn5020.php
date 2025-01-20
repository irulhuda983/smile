<?php
$pagetype = "form";
$gs_pagetitle = "PN5020 - PENCETAKAN SURAT PERSETUJUAN/PENOLAKAN KEGIATAN PROMOTIF/PREVENTIF";
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];
$gs_kode_segmen = "PU";

/* ============================================================================
Ket : Form ini digunakan sebagai form input Approval Kegiatan Promotif/Preventif
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
					<a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn5020.php?mid=<?=$mid;?>">
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
			<div style="float:left;display:none;">
				<div class="icon">
					<a id="btn_edit" href="javascript:void(0)" ><img src="http://<?=$HTTP_HOST;?>/images/app_form_edit.png" align="absmiddle" border="0"> Edit</a>
				</div>
			</div>
			<div style="float:left;display:none;">
				<div class="icon">
					<a id="btn_delete" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/app_form_delete.png" align="absmiddle" border="0"> Delete</a>
				</div>
			</div>
			<div style="float:left;display:none;">
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
						<legend>Data Surat Persetujuan/Penolakan Kegiatan Promotif/Preventif</legend>
						<table>
							<tr>
								<td width="50%" valign="top">
									<!-- start entri pengajuan kegiatan -->
									<div class="form-row_kiri">
										<label>Nama Kegiatan <span style="color:#ff0000;">*</span></label>	
										<input  type="text" name="nama_kegiatan" id="nama_kegiatan" value="<?=$ls_nama_kegiatan;?>" size="35" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
										<label>Tanggal Kegiatan <span style="color:#ff0000;">*</span></label>
										<input type="text" id="tgl_kegiatan" name="tgl_kegiatan" value="<?=$ld_tgl_kegiatan;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_kegiatan);" readonly class="disabled">
										<input id="tgl_kegiatan" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_kegiatan', 'dd-mm-y');" src="../../images/calendar.gif" readonly class="disabled"/>						
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jenis Kegiatan Diajukan <span style="color:#ff0000;">*</span></label>	
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
										<label>Latar Belakang Pengajuan Kegiatan</label>
										<textarea cols="255" rows="2" id="latar_belakang_pengajuan" name="latar_belakang_pengajuan" value="<?=$ls_latar_belakang_pengajuan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jumlah TK <span style="color:#ff0000;">*</span></label>	
										<input  type="text" name="jumlah_tk" id="jumlah_tk" value="<?=$ls_jumlah_tk;?>" size="6" onkeypress="validateDigit(event)" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jumlah Kasus KK <span style="color:#ff0000;">*</span></label>	
										<input  type="text" name="jumlah_kasuskk" id="jumlah_kasuskk" value="<?=$ls_jumlah_kasuskk;?>" size="6" onkeypress="validateDigit(event)" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jenis Usaha/Industrial <span style="color:#ff0000;">*</span></label>	
										<input  type="text" name="jenis_usaha" id="jenis_usaha" value="<?=$ls_jenis_usaha;?>" size="35" readonly class="disabled">
									</div>
									<!-- end entri pengajuan kegiatan -->
									<!-- start verifikasi pengajuan kegiatan -->
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jumlah TK Diajukan <span style="color:#ff0000;">*</span></label>	
										<input  type="text" name="jumlah_tk_diajukan" id="jumlah_tk_diajukan" value="<?=$ls_jumlah_tk_diajukan;?>" size="6" onkeypress="validateDigit(event)" readonly class="disabled" >
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Verifikasi Pengajuan <span style="color:#ff0000;">*</span></label>
										<textarea cols="255" rows="2" id="keterangan_verifikasi_pengajuan" name="keterangan_verifikasi_pengajuan" value="<?=$ls_keterangan_verifikasi_pengajuan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled >
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Status Verifikasi Pengajuan <span style="color:#ff0000;">*</span></label>
									<select size="1" id="status_verifikasi_pengajuan" name="status_verifikasi_pengajuan" value="<?=$ls_status_verifikasi_pengajuan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="DISETUJUI" <?php if (isset($ls_status_verifikasi_pengajuan) && $ls_status_verifikasi_pengajuan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
										<option value="DITOLAK" <?php if (isset($ls_status_verifikasi_pengajuan) && $ls_status_verifikasi_pengajuan=="DITOLAK") echo "selected";?> >DITOLAK</option>
									</select>		
									</div>
									<!-- end verifikasi pengajuan kegiatan -->
									<!-- start penetapan kegiatan oleh KBL -->
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Verifikasi TOR dan Data Kasus <span style="color:#ff0000;">*</span></label>	
										<input  type="checkbox" name="verifikasi_tor_data" id="verifikasi_tor_data" value="Y" <?php if (isset($ls_verifikasi_tor_data) && $ls_verifikasi_tor_data=="Y") echo "checked";?> disabled>
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Jenis Kegiatan <span style="color:#ff0000;">*</span></label>
									<select size="1" id="jenis_penetapan_kegiatan" name="jenis_penetapan_kegiatan" value="<?=$ls_jenis_penetapan_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="BARANG" <?php if (isset($ls_jenis_penetapan_kegiatan) && $ls_jenis_penetapan_kegiatan=="BARANG") echo "selected";?> >BARANG</option>
										<option value="JASA" <?php if (isset($ls_jenis_penetapan_kegiatan) && $ls_jenis_penetapan_kegiatan=="JASA") echo "selected";?> >JASA</option>
									</select>		
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Penetapan Kegiatan <span style="color:#ff0000;">*</span></label>
										<textarea cols="255" rows="2" id="keterangan_penetapan_kegiatan" name="keterangan_penetapan_kegiatan" value="<?=$ls_keterangan_penetapan_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Status Penetapan Kegiatan <span style="color:#ff0000;">*</span></label>
									<select size="1" id="status_penetapan_kegiatan" name="status_penetapan_kegiatan" value="<?=$ls_status_penetapan_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="DISETUJUI" <?php if (isset($ls_status_penetapan_kegiatan) && $ls_status_penetapan_kegiatan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
										<option value="DITOLAK" <?php if (isset($ls_status_penetapan_kegiatan) && $ls_status_penetapan_kegiatan=="DITOLAK") echo "selected";?> >DITOLAK</option>
									</select>		
									</div>
									<div class="clear"></div>
									<!-- end penetapan kegiatan oleh KBL -->
									<!-- start penetapan pelaksana kegiatan -->
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Jenis Pelaksana <span style="color:#ff0000;">*</span></label>
									<select size="1" id="jenis_pelaksana_kegiatan" name="jenis_pelaksana_kegiatan" value="<?=$ls_jenis_pelaksana_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="PR" <?php if (isset($ls_jenis_pelaksana_kegiatan) && $ls_jenis_pelaksana_kegiatan=="PR") echo "selected";?> >PERUSAHAAN</option>
										<option value="TG" <?php if (isset($ls_jenis_pelaksana_kegiatan) && $ls_jenis_pelaksana_kegiatan=="TG") echo "selected";?> >PIHAK KETIGA</option>
									</select>		
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Penetapan Pelaksana Kegiatan <span style="color:#ff0000;">*</span></label>
										<textarea cols="255" rows="2" id="keterangan_penetapan_pelaksana" name="keterangan_penetapan_pelaksana" value="<?=$ls_keterangan_penetapan_pelaksana;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<!-- end penetapan pelaksana kegiatan -->
									<!-- start approval kegiatan -->
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Approval Kegiatan <span style="color:#ff0000;">*</span></label>
										<textarea cols="255" rows="2" id="keterangan_approval_kegiatan" name="keterangan_approval_kegiatan" value="<?=$ls_keterangan_approval_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Status Approval Kegiatan <span style="color:#ff0000;">*</span></label>
									<select size="1" id="status_approval_kegiatan" name="status_approval_kegiatan" value="<?=$ls_status_approval_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="DISETUJUI" <?php if (isset($ls_status_approval_kegiatan) && $ls_status_approval_kegiatan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
										<option value="DITOLAK" <?php if (isset($ls_status_approval_kegiatan) && $ls_status_approval_kegiatan=="DITOLAK") echo "selected";?> >DITOLAK</option>
									</select>		
									</div>
									<div class="clear"></div>
									<!-- end approval kegiatan -->
								</td>
								<td width="50%" valign="top">
									<div class="form-row_kanan">
										<label>Kode Promotif</label>	
										<input type="hidden" name="TYPE" value="EDIT">
										<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
										<input type="hidden" id="DATAID2" name="DATAID2" value="<?=$_REQUEST["dataid2"];?>">
										<input type="hidden" id="STATUS" name="STATUS" value="<?=$_REQUEST["STATUS"];?>">
										<input type="hidden" id="KETERANGAN" name="KETERANGAN" value="<?=$_REQUEST["KETERANGAN"];?>">
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
									<div class="form-row_kanan">
										<label>Jumlah Kasus KK</label>	
										<input  type="text" name="jumlah_kasus_kk_prs" id="jumlah_kasus_kk_prs" value="<?=$ls_jumlah_kasus_kk_prs;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Jumlah Kasus PAK</label>	
										<input  type="text" name="jumlah_kasus_pak_prs" id="jumlah_kasus_pak_prs" value="<?=$ls_jumlah_kasus_pak_prs;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Jumlah TK</label>	
										<input  type="text" name="jumlah_tk_prs" id="jumlah_tk_prs" value="<?=$ls_jumlah_tk_prs;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Incident Rate</label>	
										<input  type="text" name="incident_rate" id="incident_rate" value="<?=$ls_incident_rate;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Tanggal Permintaan </label>
										<input type="text" id="tgl_permintaan" name="tgl_permintaan" value="<?=$ld_tgl_permintaan;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_permintaan);" readonly class="disabled">
										<input id="tgl_permintaan" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_permintaan', 'dd-mm-y');" src="../../images/calendar.gif" disabled />						
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Keterangan Permintaan Verifikasi Oleh KBL </label>
										<textarea cols="255" rows="2" id="keterangan_rencana_kegiatan" name="keterangan_rencana_kegiatan" value="<?=$ls_keterangan_rencana_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Tanggal Verifikasi Oleh KBP/Wasrik</label>
										<input type="text" id="tgl_verifikasi" name="tgl_verifikasi" value="<?=$ld_tgl_verifikasi;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_verifikasi);" readonly class="disabled">
										<input id="tgl_verifikasi" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_verifikasi', 'dd-mm-y');" src="../../images/calendar.gif" disabled />						
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Keterangan Verifikasi Oleh KBP/Wasrik</label>
										<textarea cols="255" rows="2" id="keterangan_verifikasi" name="keterangan_verifikasi" value="<?=$ls_keterangan_verifikasi;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Status Verifikasi Oleh KBP/Wasrik </label>
										<select size="1" id="status_verifikasikbpwasrik_kegiatan" name="status_verifikasikbpwasrik_kegiatan" value="<?=$ls_status_verifikasikbpwasrik_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
											<option value="">-- Pilih --</option>
											<option value="DISETUJUI" <?php if (isset($ls_status_verifikasikbpwasrik_kegiatan) && $ls_status_verifikasikbpwasrik_kegiatan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
											<option value="DITOLAK" <?php if (isset($ls_status_verifikasikbpwasrik_kegiatan) && $ls_status_verifikasikbpwasrik_kegiatan=="DITOLAK") echo "selected";?> >DITOLAK</option>
										</select>		
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Keterangan Persetujuan Verifikasi oleh KBL</label>
										<textarea cols="255" rows="2" id="keterangan_persetujuan_verifikasi" name="keterangan_persetujuan_verifikasi" value="<?=$ls_keterangan_persetujuan_verifikasi;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Status Persetujuan Verifikasi Oleh KBL </label>
										<select size="1" id="status_persetujuan_verifikasi" name="status_persetujuan_verifikasi" value="<?=$ls_status_persetujuan_verifikasi;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
											<option value="">-- Pilih --</option>
											<option value="DISETUJUI" <?php if (isset($ls_status_persetujuan_verifikasi) && $ls_status_persetujuan_verifikasi=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
											<option value="DITOLAK" <?php if (isset($ls_status_persetujuan_verifikasi) && $ls_status_persetujuan_verifikasi=="DITOLAK") echo "selected";?> >DITOLAK</option>
										</select>		
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
				<div class="clear"></div>
				<div id="formsplit">
					<fieldset>
						<legend>Data Surat Persetujuan/Penolakan Kegiatan Promotif/Preventif</legend>
						<table>
							<tr>
								<td width="50%" valign="top">
									<!-- start entri pengajuan kegiatan -->
									<div class="form-row_kiri">
										<label>Nama Kegiatan </label>	
										<input  type="text" name="nama_kegiatan" id="nama_kegiatan" value="<?=$ls_nama_kegiatan;?>" size="35" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
										<label>Tanggal Kegiatan </label>
										<input type="text" id="tgl_kegiatan" name="tgl_kegiatan" value="<?=$ld_tgl_kegiatan;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_kegiatan);" disabled />
										<input id="tgl_kegiatan" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_kegiatan', 'dd-mm-y');" src="../../images/calendar.gif" disabled />						
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jenis Kegiatan Diajukan </label>	
										<input  type="text" name="kode_jenis_kegiatan" id="kode_jenis_kegiatan" size="6" style="background-color:#ffff99;" readonly  class="disabled">
										<input  type="text" name="nama_jenis_kegiatan" id="nama_jenis_kegiatan" size="25" style="background-color:#ffff99;" readonly  class="disabled">
										<img src="../../images/help.png" alt="Cari Sub Jenis Kegiatan" border="0" align="absmiddle">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Sub Jenis Kegiatan </label>	
										<input  type="text" name="kode_sub_jenis_kegiatan" id="kode_sub_jenis_kegiatan" size="6" style="background-color:#ffff99;" readonly  class="disabled">
										<input  type="text" name="nama_sub_jenis_kegiatan" id="nama_sub_jenis_kegiatan" size="25" style="background-color:#ffff99;" readonly  class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Latar Belakang Pengajuan Kegiatan</label>
										<textarea cols="255" rows="2" id="latar_belakang_pengajuan" name="latar_belakang_pengajuan" value="<?=$ls_latar_belakang_pengajuan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jumlah TK </label>	
										<input  type="text" name="jumlah_tk" id="jumlah_tk" value="<?=$ls_jumlah_tk;?>" size="6" onkeypress="validateDigit(event)" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jumlah Kasus KK </label>	
										<input  type="text" name="jumlah_kasuskk" id="jumlah_kasuskk" value="<?=$ls_jumlah_kasuskk;?>" size="6" onkeypress="validateDigit(event)" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jenis Usaha/Industrial </label>	
										<input  type="text" name="jenis_usaha" id="jenis_usaha" value="<?=$ls_jenis_usaha;?>" size="35" readonly class="disabled">
									</div>
									<!-- end entri pengajuan kegiatan -->
									<!-- start verifikasi pengajuan kegiatan -->
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Jumlah TK Diajukan</label>	
										<input  type="text" name="jumlah_tk_diajukan" id="jumlah_tk_diajukan" value="<?=$ls_jumlah_tk_diajukan;?>" size="6" onkeypress="validateDigit(event)" readonly class="disabled">
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Verifikasi Pengajuan</label>
										<textarea cols="255" rows="2" id="keterangan_verifikasi_pengajuan" name="keterangan_verifikasi_pengajuan" value="<?=$ls_keterangan_verifikasi_pengajuan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Status Verifikasi Pengajuan </label>
									<select size="1" id="status_verifikasi_pengajuan" name="status_verifikasi_pengajuan" value="<?=$ls_status_verifikasi_pengajuan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled >
										<option value="">-- Pilih --</option>
										<option value="DISETUJUI" <?php if (isset($ls_status_verifikasi_pengajuan) && $ls_status_verifikasi_pengajuan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
										<option value="DITOLAK" <?php if (isset($ls_status_verifikasi_pengajuan) && $ls_status_verifikasi_pengajuan=="DITOLAK") echo "selected";?> >DITOLAK</option>
									</select>		
									</div>
									<div class="clear"></div>
									<!-- end verifikasi pengajuan kegiatan -->
									<!-- start penetapan kegiatan oleh KBL -->
									<div id="div_penatapan_kegiatan">
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Verifikasi TOR dan Data Kasus </label>	
										<input  type="checkbox" name="verifikasi_tor_data" id="verifikasi_tor_data" value="Y" <?php if (isset($ls_verifikasi_tor_data) && $ls_verifikasi_tor_data=="Y") echo "checked";?> disabled>
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Jenis Kegiatan </label>
									<select size="1" id="jenis_penetapan_kegiatan" name="jenis_penetapan_kegiatan" value="<?=$ls_jenis_penetapan_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="BARANG" <?php if (isset($ls_jenis_penetapan_kegiatan) && $ls_jenis_penetapan_kegiatan=="BARANG") echo "selected";?> >BARANG</option>
										<option value="JASA" <?php if (isset($ls_jenis_penetapan_kegiatan) && $ls_jenis_penetapan_kegiatan=="JASA") echo "selected";?> >JASA</option>
									</select>		
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Penetapan Kegiatan </label>
										<textarea cols="255" rows="2" id="keterangan_penetapan_kegiatan" name="keterangan_penetapan_kegiatan" value="<?=$ls_keterangan_penetapan_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Status Penetapan Kegiatan </label>
									<select size="1" id="status_penetapan_kegiatan" name="status_penetapan_kegiatan" value="<?=$ls_status_penetapan_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="DISETUJUI" <?php if (isset($ls_status_penetapan_kegiatan) && $ls_status_penetapan_kegiatan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
										<option value="DITOLAK" <?php if (isset($ls_status_penetapan_kegiatan) && $ls_status_penetapan_kegiatan=="DITOLAK") echo "selected";?> >DITOLAK</option>
									</select>		
									</div>
									<div class="clear"></div>
									</div>
									<!-- end penetapan kegiatan oleh KBL -->
									<!-- start penetapan pelaksana kegiatan -->
									<div id="div_pelaksana_kegiatan">
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Jenis Pelaksana </label>
									<select size="1" id="jenis_pelaksana_kegiatan" name="jenis_pelaksana_kegiatan" value="<?=$ls_jenis_pelaksana_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="PR" <?php if (isset($ls_jenis_pelaksana_kegiatan) && $ls_jenis_pelaksana_kegiatan=="PR") echo "selected";?> >PERUSAHAAN</option>
										<option value="TG" <?php if (isset($ls_jenis_pelaksana_kegiatan) && $ls_jenis_pelaksana_kegiatan=="TG") echo "selected";?> >PIHAK KETIGA</option>
									</select>		
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Penetapan Pelaksana Kegiatan </label>
										<textarea cols="255" rows="2" id="keterangan_penetapan_pelaksana" name="keterangan_penetapan_pelaksana" value="<?=$ls_keterangan_penetapan_pelaksana;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<!-- end penetapan pelaksana kegiatan -->
									</div>
									<!-- start approval kegiatan -->
									<div id="div_approval_kegiatan">
									<div class="clear"></div>
									<div class="form-row_kiri">
										<label>Keterangan Approval Kegiatan </label>
										<textarea cols="255" rows="2" id="keterangan_approval_kegiatan" name="keterangan_approval_kegiatan" value="<?=$ls_keterangan_approval_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kiri">	
									<label>Status Approval Kegiatan </label>
									<select size="1" id="status_approval_kegiatan" name="status_approval_kegiatan" value="<?=$ls_status_approval_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
										<option value="">-- Pilih --</option>
										<option value="DISETUJUI" <?php if (isset($ls_status_approval_kegiatan) && $ls_status_approval_kegiatan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
										<option value="DITOLAK" <?php if (isset($ls_status_approval_kegiatan) && $ls_status_approval_kegiatan=="DITOLAK") echo "selected";?> >DITOLAK</option>
									</select>		
									</div>
									<div class="clear"></div>
									</div>
									<!-- end approval kegiatan -->
								</td>
								<td width="50%" valign="top">
									<div class="form-row_kanan">
										<label>Kode Promotif</label>	
										<input type="hidden" name="TYPE" value="EDIT">
										<input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
										<input type="hidden" id="DATAID2" name="DATAID2" value="<?=$_REQUEST["dataid2"];?>">
										<input type="hidden" id="STATUS" name="STATUS" value="<?=$_REQUEST["STATUS"];?>">
										<input type="hidden" id="KETERANGAN" name="KETERANGAN" value="<?=$_REQUEST["KETERANGAN"];?>">
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
									<div class="form-row_kanan">
										<label>Jumlah Kasus KK</label>	
										<input  type="text" name="jumlah_kasus_kk_prs" id="jumlah_kasus_kk_prs" value="<?=$ls_jumlah_kasus_kk_prs;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Jumlah Kasus PAK</label>	
										<input  type="text" name="jumlah_kasus_pak_prs" id="jumlah_kasus_pak_prs" value="<?=$ls_jumlah_kasus_pak_prs;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Jumlah TK</label>	
										<input  type="text" name="jumlah_tk_prs" id="jumlah_tk_prs" value="<?=$ls_jumlah_tk_prs;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Incident Rate</label>	
										<input  type="text" name="incident_rate" id="incident_rate" value="<?=$ls_incident_rate;?>" size="35" readonly class="disabled">				
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Tanggal Permintaan </label>
										<input type="text" id="tgl_permintaan" name="tgl_permintaan" value="<?=$ld_tgl_permintaan;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_permintaan);" readonly class="disabled">
										<input id="tgl_permintaan" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_permintaan', 'dd-mm-y');" src="../../images/calendar.gif" disabled />						
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Keterangan Permintaan Verifikasi Oleh KBL </label>
										<textarea cols="255" rows="2" id="keterangan_rencana_kegiatan" name="keterangan_rencana_kegiatan" value="<?=$ls_keterangan_rencana_kegiatan;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Tanggal Verifikasi Oleh KBP/Wasrik</label>
										<input type="text" id="tgl_verifikasi" name="tgl_verifikasi" value="<?=$ld_tgl_verifikasi;?>" size="32" maxlength="10" tabindex="5" onblur="convert_date(tgl_verifikasi);" readonly class="disabled">
										<input id="tgl_verifikasi" tabindex="6" type="image" align="top" onclick="return showCalendar('tgl_verifikasi', 'dd-mm-y');" src="../../images/calendar.gif" disabled />						
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Keterangan Verifikasi Oleh KBP/Wasrik</label>
										<textarea cols="255" rows="2" id="keterangan_verifikasi" name="keterangan_verifikasi" value="<?=$ls_keterangan_verifikasi;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Status Verifikasi Oleh KBP/Wasrik </label>
										<select size="1" id="status_verifikasikbpwasrik_kegiatan" name="status_verifikasikbpwasrik_kegiatan" value="<?=$ls_status_verifikasikbpwasrik_kegiatan;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
											<option value="">-- Pilih --</option>
											<option value="DISETUJUI" <?php if (isset($ls_status_verifikasikbpwasrik_kegiatan) && $ls_status_verifikasikbpwasrik_kegiatan=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
											<option value="DITOLAK" <?php if (isset($ls_status_verifikasikbpwasrik_kegiatan) && $ls_status_verifikasikbpwasrik_kegiatan=="DITOLAK") echo "selected";?> >DITOLAK</option>
										</select>		
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">
										<label>Keterangan Persetujuan Verifikasi oleh KBL</label>
										<textarea cols="255" rows="2" id="keterangan_persetujuan_verifikasi" name="keterangan_persetujuan_verifikasi" value="<?=$ls_keterangan_persetujuan_verifikasi;?>" tabindex="8" style="width:225px;" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)" disabled>
										</textarea>   
									</div>
									<div class="clear"></div>
									<div class="form-row_kanan">	
										<label>Status Persetujuan Verifikasi Oleh KBL </label>
										<select size="1" id="status_persetujuan_verifikasi" name="status_persetujuan_verifikasi" value="<?=$ls_status_persetujuan_verifikasi;?>" tabindex="7" class="select_format" style="width:230px;background-color:#ffff99;" disabled>
											<option value="">-- Pilih --</option>
											<option value="DISETUJUI" <?php if (isset($ls_status_persetujuan_verifikasi) && $ls_status_persetujuan_verifikasi=="DISETUJUI") echo "selected";?> >DISETUJUI</option>
											<option value="DITOLAK" <?php if (isset($ls_status_persetujuan_verifikasi) && $ls_status_persetujuan_verifikasi=="DITOLAK") echo "selected";?> >DITOLAK</option>
										</select>		
									</div>
									<div class="clear"></div>
								</td>
							</tr>
						</table>
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
						<legend>Daftar Surat Persetujuan/Penolakan Kegiatan Promotif/Preventif</legend>	
						<div class="clear"></div>
						<table class="table responsive-table" id="mydata" cellspacing="0" width="100%">
							<thead>
								<tr>
									<th scope="col" width="5%" class="align-center" style="vertical-align: middle;">Action</th>
									<th scope="col" width="10%">Kode Promotif/Preventif</th>
									<th scope="col" width="6%">NPP</th>	
									<th scope="col" width="5%">Kode Divisi</th>	
									<th scope="col">Nama Perusahaan</th>
									<th scope="col" width="10%">Status Kegiatan</th>
									<th scope="col" width="8%">Status Approval</th>
									<th scope="col" width="10%">Tanggal Approval</th>
									<th scope="col" width="8%">Pelaksana Kegiatan</th>
									<th scope="col" width="8%">Surat Persetujuan/ Penolakan</th>
									<th scope="col" width="8%">Surat Jaminan</th>
									<th scope="col" width="8%">Konfirmasi Cetak</th>							
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
							<input type="button" style="display:none;" class="btn green" id="btncetakdaftarpersetujuanpenolakankegiatan" name="btncetakdaftarpersetujuanpenolakankegiatan" tabindex="41" value="CETAK DAFTAR SURAT PERSETUJUAN/PENOLAKAN KEGIATAN" title="Klik Untuk Cetak Daftar Persetujuan/Penolakan Kegiatan">	
							&nbsp;
							<input type="button" style="display:none" class="btn green" id="btnpermintaanverifikasi" name="btnpermintaanverifikasi" tabindex="41" value="KIRIM PERMINTAAN VERIFIKASI KE KBP DAN WASRIK" title="Klik Untuk Kirim Permintaan Verifikasi ke KBP dan Wasrik">	
						</div>
					</fieldset>
					
					<?php
					}
					?>
				</div>				
				<!--- detail tenaga kerja -->
				<?php
				if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View")
				{
				?>
				</br>
				<div class="clear"></div>
				<!-- start penambahan tk pengajuan oleh PMP -->
				<div id="div_penambahantkpengajuan">
					<div id="formsplit">
						<fieldset style="background: #ededed;">
							<br>
							<div class="form-row_kiri">
								<input type="button" name="btncetaksuratpersetujuan" id="btncetaksuratpersetujuan" class="btn green"  value="CETAK SURAT PERSETUJUAN/PENOLAKAN" title="Klik untuk cetak surat persetujuan kegiatan">
								&nbsp;
								<input type="button" name="btncetaksuratjaminan" id="btncetaksuratjaminan" class="btn green"  value="CETAK SURAT JAMINAN" title="Klik untuk cetak surat jaminan kegiatan">
								&nbsp;
								<input type="button" name="btnkonfirmasicetaksuratpersetujuan" id="btnkonfirmasicetaksuratpersetujuan" class="btn green"  value="KONFIRMASI CETAK SURAT PERSETUJUAN/PENOLAKAN" title="Klik untuk konfirmasi surat persetujuan kegiatan">	
							</div>
						</fieldset>
					<br>
					</div>
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
											$linkhapus ='pn5020.php?task=Edit&tasktk=Edit&dataid='+window.dataid+'&dataid2='+window.kodeperusahaan+'&mid=<?=$mid;?>';
											//<?php echo 'pn5014_action_tk.php?task=Edit&tasktk=Edit&kode_tk'+echo $row["kode_tk"]+'=&dataid='+window.dataid+'&dataid2='+window.kodeperusahaan+'&mid='$mid;
										?>
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
				<!-- end div_penambahantkpengajuan -->
				</div>
				<?php 
				}
				?>
				<!--- end detail tenaga kerja -->
				<br>
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
						window.location='pn5020.php?task=View&tasktk=View&dataid='+window.dataid+'&dataid2='+window.kodeperusahaan+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				
				});
				
				$('#btn_edit').click(function() {	
					if(window.dataid != ''){
						window.location='pn5020.php?task=Edit&tasktk=Edit&dataid='+window.dataid+'&dataid2='+window.kodeperusahaan+'&mid=<?=$mid;?>';
					} else {
						alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
					}
				});
				
				$('#btn_new').click(function() {
					NewWindow4('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5020_eligible.php?kode_user=<?php echo $ls_user_login?>&kode_kantor=<?php echo $ls_kode_kantor?>','pn502001 - Daftar Perusahaan Eligible','1024','600','yes');
				});
				
				$("#btncari").click(function() {
					loadData();
				});
				
				$("#btncetakdaftarpersetujuanpenolakankegiatan").click(function() {						
					cetakDaftarDokumenPdf("PNR500817","<?php echo $ls_kode_kantor; ?>")
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
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5020_action.php?'+Math.random(),
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
									$('#tgl_permintaan').val(jdata.data[0].TGL_PERMINTAAN_VERIFIKASI);
									$('#keterangan_verifikasi').val(jdata.data[0].KETERANGAN_VERIF_PERMINTAAN);
									$('#tgl_verifikasi').val(jdata.data[0].TGL_VERIF_PERMINTAAN);
									$('#keterangan_persetujuan_verifikasi').val(jdata.data[0].KETERANGAN_VERIF_APPROVAL);
									$('#status_verifikasikbpwasrik_kegiatan').val(jdata.data[0].STATUS_VERIF_PERMINTAAN);
									$('#status_persetujuan_verifikasi').val(jdata.data[0].STATUS_VERIF_APPROVAL);
									
									$('#nama_kegiatan').val(jdata.data[0].NAMA_DETIL_KEGIATAN);
									$('#tgl_kegiatan').val(jdata.data[0].TGL_KEGIATAN_PENGAJUAN);
									$('#kode_jenis_kegiatan').val(jdata.data[0].KODE_KEGIATAN);
									$('#nama_jenis_kegiatan').val(jdata.data[0].NAMA_KEGIATAN);
									$('#kode_sub_jenis_kegiatan').val(jdata.data[0].KODE_SUB_KEGIATAN);
									$('#nama_sub_jenis_kegiatan').val(jdata.data[0].NAMA_SUB_KEGIATAN);
									$('#latar_belakang_pengajuan').val(jdata.data[0].LATAR_BELAKANG_PENGAJUAN);
									$('#jumlah_tk').val(jdata.data[0].JML_TK_PENGAJUAN);
									$('#jumlah_kasuskk').val(jdata.data[0].JML_KK_PENGAJUAN);
									$('#jenis_usaha').val(jdata.data[0].JENIS_USAHA_PENGAJUAN);
									$('#status_promotif').val(jdata.data[0].STATUS);
									
									$('#jumlah_tk_diajukan').val(jdata.data[0].JUMLAH_TK_VERIFIKASI);
									$('#keterangan_verifikasi_pengajuan').val(jdata.data[0].KETERANGAN_VERIFIKASI_AJUAN);
									$('#status_verifikasi_pengajuan').val(jdata.data[0].STATUS_VERIFIKASI_PENGAJUAN);
									
									$('#verifikasi_tor_data').val(jdata.data[0].VERIFIKASI_TOR_DATA);
									$('#jenis_penetapan_kegiatan').val(jdata.data[0].JENIS_PENETAPAN_KEGIATAN);
									$('#keterangan_penetapan_kegiatan').val(jdata.data[0].KETERANGAN_PENETAPAN_KEGIATAN);
									$('#status_penetapan_kegiatan').val(jdata.data[0].STATUS_PENETAPAN_KEGIATAN);
									
									$('#jenis_pelaksana_kegiatan').val(jdata.data[0].JENIS_PELAKSANA_KEGIATAN);
									$('#keterangan_penetapan_pelaksana').val(jdata.data[0].KETERANGAN_PENENTU_PELAKSANA);
									
									$('#keterangan_approval_kegiatan').val(jdata.data[0].KETERANGAN_APPROVAL_PENGAJUAN);
									$('#status_approval_kegiatan').val(jdata.data[0].STATUS_APPROVAL_PENGAJUAN);
									
									if(jdata.data[0].KETERANGAN_VERIFIKASI_AJUAN === null)
									{
										$('#div_penambahantkpengajuan').attr('style','display:none');
									}
									else
									{
										$('#div_penambahantkpengajuan').removeAttr('style','display:none');
									}
									
									if(jdata.data[0].VERIFIKASI_TOR_DATA == 'Y')
									{
										$('#verifikasi_tor_data').attr('checked','checked');
									}
									else
									{
										$('#verifikasi_tor_data').removeAttr('checked','checked');
									}
									
									if(jdata.data[0].JENIS_PELAKSANA_KEGIATAN === null || jdata.data[i].JENIS_PELAKSANA_KEGIATAN == "PR")
									{
										$('#btncetaksuratjaminan').attr('style','display:none');
									}
								}
							}
						});
						
						$('#btncetaksuratpersetujuan').click(function() {
							cetakSurat(window.dataid);
							cetakDokumenPdf("PNR500802",window.dataid);
						});
						
						$('#btncetaksuratjaminan').click(function() {
							cetakSurat(window.dataid);
							cetakDokumenPdf("PNR500803",window.dataid);
						});
						
						$('#btnkonfirmasicetaksuratpersetujuan').click(function() {
							konfirmasiCetakSurat(window.dataid);
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
							url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5020_action.php?'+Math.random(),
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
									$('#tgl_permintaan').val(jdata.data[0].TGL_PERMINTAAN_VERIFIKASI);
									$('#keterangan_verifikasi').val(jdata.data[0].KETERANGAN_VERIF_PERMINTAAN);
									$('#tgl_verifikasi').val(jdata.data[0].TGL_VERIF_PERMINTAAN);
									$('#keterangan_persetujuan_verifikasi').val(jdata.data[0].KETERANGAN_VERIF_APPROVAL);
									$('#status_verifikasikbpwasrik_kegiatan').val(jdata.data[0].STATUS_VERIF_PERMINTAAN);
									$('#status_persetujuan_verifikasi').val(jdata.data[0].STATUS_VERIF_APPROVAL);
									
									$('#nama_kegiatan').val(jdata.data[0].NAMA_DETIL_KEGIATAN);
									$('#tgl_kegiatan').val(jdata.data[0].TGL_KEGIATAN_PENGAJUAN);
									$('#kode_jenis_kegiatan').val(jdata.data[0].KODE_KEGIATAN);
									$('#nama_jenis_kegiatan').val(jdata.data[0].NAMA_KEGIATAN);
									$('#kode_sub_jenis_kegiatan').val(jdata.data[0].KODE_SUB_KEGIATAN);
									$('#nama_sub_jenis_kegiatan').val(jdata.data[0].NAMA_SUB_KEGIATAN);
									$('#latar_belakang_pengajuan').val(jdata.data[0].LATAR_BELAKANG_PENGAJUAN);
									$('#jumlah_tk').val(jdata.data[0].JML_TK_PENGAJUAN);
									$('#jumlah_kasuskk').val(jdata.data[0].JML_KK_PENGAJUAN);
									$('#jenis_usaha').val(jdata.data[0].JENIS_USAHA_PENGAJUAN);
									$('#status_promotif').val(jdata.data[0].STATUS);
									
									$('#jumlah_tk_diajukan').val(jdata.data[0].JUMLAH_TK_VERIFIKASI);
									$('#keterangan_verifikasi_pengajuan').val(jdata.data[0].KETERANGAN_VERIFIKASI_AJUAN);
									$('#status_verifikasi_pengajuan').val(jdata.data[0].STATUS_VERIFIKASI_PENGAJUAN);
									
									$('#verifikasi_tor_data').val(jdata.data[0].VERIFIKASI_TOR_DATA);
									$('#jenis_penetapan_kegiatan').val(jdata.data[0].JENIS_PENETAPAN_KEGIATAN);
									$('#keterangan_penetapan_kegiatan').val(jdata.data[0].KETERANGAN_PENETAPAN_KEGIATAN);
									$('#status_penetapan_kegiatan').val(jdata.data[0].STATUS_PENETAPAN_KEGIATAN);
									
									$('#jenis_pelaksana_kegiatan').val(jdata.data[0].JENIS_PELAKSANA_KEGIATAN);
									$('#keterangan_penetapan_pelaksana').val(jdata.data[0].KETERANGAN_PENENTU_PELAKSANA);
									
									$('#keterangan_approval_kegiatan').val(jdata.data[0].KETERANGAN_APPROVAL_PENGAJUAN);
									$('#status_approval_kegiatan').val(jdata.data[0].STATUS_APPROVAL_PENGAJUAN);
									
									if(jdata.data[0].KETERANGAN_VERIFIKASI_AJUAN === null)
									{
										$('#div_penambahantkpengajuan').attr('style','display:none');
									}
									else
									{
										$('#div_penambahantkpengajuan').removeAttr('style','display:none');
									}
									
									if(jdata.data[0].VERIFIKASI_TOR_DATA == 'Y')
									{
										$('#verifikasi_tor_data').attr('checked','checked');
									}
									else
									{
										$('#verifikasi_tor_data').removeAttr('checked','checked');
									}
								}
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
				<?PHP
					};
				?>
				
				<?PHP		
				}
				?>
			});
			
			function cetakSurat(p_kode_promotif){
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5020_action.php?'+Math.random(),
					data: { TYPE:'CETAK', DATAID:p_kode_promotif},
					success: function(data) {
						preload(true);
						jdata = JSON.parse(data);
						if(jdata.ret == '0'){
							window.parent.Ext.notify.msg('Berhasil', jdata.msg);
							location.reload();
						} else {
							alert(jdata.msg);
							location.reload();
						}
					}
				});
			}
			
			function konfirmasiCetakSurat(p_kode_promotif){
				var r = confirm("Apakah anda yakin?");
				if (r == true) {
					$.ajax({
						type: 'POST',
						url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5020_action.php?'+Math.random(),
						data: { TYPE:'KONFIRMASI', DATAID:p_kode_promotif},
						success: function(data) {
							preload(false);
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
				$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5020_query.php?'+Math.random(),
					data: { TYPE:$('#type').val(), KEYWORD:$('#keyword').val()},
					success: function(data) {	
						preload(false);	
						//console.log(data);
						jdata = JSON.parse(data);
						if(jdata.ret == 0){
							for (i = 0; i < jdata.data.length; i++) { 
								$ls_cetak_surat_pemberitahuan='';
								if(jdata.data[0].TGL_KONFIRMASI_CETAKSETUJU === null)
								{
									$ls_cetak_surat_pemberitahuan='';
								}
								else
								{
									$ls_cetak_surat_pemberitahuan = " style='display:none' ";
								}
								$pelaksana_kegiatan = "";
								if(jdata.data[i].JENIS_PELAKSANA_KEGIATAN == "PR")
								{
									$pelaksana_kegiatan = "PERUSAHAAN";
								}
								else if(jdata.data[i].JENIS_PELAKSANA_KEGIATAN == "TG")
								{
									$pelaksana_kegiatan = "PIHAK KETIGA";
								}
								
								$ls_cetak_surat_jaminan='';
								if(jdata.data[i].JENIS_PELAKSANA_KEGIATAN === null || jdata.data[i].JENIS_PELAKSANA_KEGIATAN == "PR")
								{
									$ls_cetak_surat_jaminan = " style='display:none' ";
								}
								
								if(jdata.data[0].status_approval == "DITOLAK")
								{
									$ls_cetak_surat_pemberitahuan = " style='display:none' ";
								}
								
								window.table.row.add( [
									'<input type="checkbox" KODE_PROMOTIF="'+jdata.data[i].KODE_PROMOTIF+'" KODE2="'+jdata.data[i].KODE_PERUSAHAAN+'" KODE3="'+jdata.data[i].STATUS_VERIFIKASI+'" id="CHECK_'+i+'" urut="'+i+'" name="CHECK['+i+']"><input type="hidden" name="KODE_PROMOTIF['+i+']" id="KODE_PROMOTIF'+i+'" value="'+jdata.data[i].KODE_PROMOTIF+'">',
									jdata.data[i].KODE_PROMOTIF,
									jdata.data[i].NPP,
									jdata.data[i].KODE_DIVISI,
									jdata.data[i].NAMA_PERUSAHAAN,
									jdata.data[i].STATUS_KEGIATAN,
									jdata.data[i].STATUS_APPROVAL,
									jdata.data[i].TGL_APPROVAL,
									$pelaksana_kegiatan,//jdata.data[i].JENIS_PELAKSANA_KEGIATAN,
									'<div style="text-align:center !important"><input type="button" class="btn green" onclick=cetakSurat("'+jdata.data[i].KODE_PROMOTIF+'");cetakDokumenPdf("PNR500802","'+jdata.data[i].KODE_PROMOTIF+'") '+ $ls_cetak_surat_pemberitahuan+ ' KODE_PROMOTIF="'+jdata.data[i].KODE_PROMOTIF+'" id="BTNCETAK_'+i+'" urut="'+i+'" name="BTNCETAK['+i+']" value="&nbsp;&nbsp;&nbsp;&nbsp;Cetak&nbsp;&nbsp;&nbsp;&nbsp;" title="Klik Untuk Mencetak Surat Persetujuan Kegiatan"></div>',
									'<div style="text-align:center !important"><input type="button" "'+$ls_cetak_surat_jaminan+'" class="btn green" onclick=cetakSurat("'+jdata.data[i].KODE_PROMOTIF+'");cetakDokumenPdf("PNR500803","'+jdata.data[i].KODE_PROMOTIF+'") '+ $ls_cetak_surat_pemberitahuan+ ' KODE_PROMOTIF="'+jdata.data[i].KODE_PROMOTIF+'" id="BTNCETAKSURATJAMINAN_'+i+'" urut="'+i+'" name="BTNCETAKSURATJAMINAN['+i+']" value="&nbsp;&nbsp;&nbsp;&nbsp;Cetak&nbsp;&nbsp;&nbsp;&nbsp;" title="Klik Untuk Mencetak Surat Jaminan Kegiatan"></div>',
									'<div style="text-align:center !important"><input type="button" class="btn green" onclick=konfirmasiCetakSurat("'+jdata.data[i].KODE_PROMOTIF+'") '+ $ls_cetak_surat_pemberitahuan+ ' KODE_PROMOTIF="'+jdata.data[i].KODE_PROMOTIF+'" id="BTNKONFIRMASI_'+i+'" urut="'+i+'" name="BTNKONFIRMASI['+i+']" value="Konfirmasi" title="Klik Untuk Mengkonfirmasi Cetak Surat Persetujuan Kegiatan"></div>'
									
								] ).draw();
							}
							
							$('input[type="checkbox"]').change(function() {
								if(this.checked) {
									window.dataid= $(this).attr('KODE_PROMOTIF');
									window.kodeperusahaan = $(this).attr('KODE2');
									window.selected = $(this).closest('tr');
									console.log(window.dataid);
									console.log(window.kodeperusahaan);
								}else{
									window.dataid= $(this).attr('KODE_PROMOTIF');
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
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5013_cetak.php?'+Math.random(),
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
