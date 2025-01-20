<?
require_once "../../includes/header_app_nosql.php";
$DB        = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$KD_KANTOR = $_SESSION['kdkantorrole'];
$USER      = $_SESSION["USER"];
$KODE_ROLE = $_SESSION['regrole'];

$pagetitle        = "SIJSTK";
$gs_pagetitle     = "DETIL PENYERAHAN SALDO JHT KE BHP";
$p_kode_klaim 		= $_GET["kode_klaim"];

$sql = "
SELECT
				A.KODE_KLAIM,
				A.KODE_KANTOR,
				A.KODE_SEGMEN,
				A.KODE_PERUSAHAAN,
				A.KODE_DIVISI,
				A.KODE_PROYEK,
				A.KODE_TK,
				A.NAMA_TK,
				A.KPJ,
				A.NOMOR_IDENTITAS,
				A.JENIS_IDENTITAS,
				A.KODE_KANTOR_TK,
				A.KODE_TIPE_KLAIM,
				TO_CHAR(A.TGL_KLAIM, 'DD-MM-YYYY') TGL_KLAIM,
				TO_CHAR(A.TGL_LAPOR, 'DD-MM-YYYY') TGL_LAPOR,
				TO_CHAR(A.TGL_KEJADIAN, 'DD-MM-YYYY') TGL_KEJADIAN,
				A.KODE_KONDISI_TERAKHIR,
				TO_CHAR(A.TGL_KONDISI_TERAKHIR, 'DD-MM-YYYY') TGL_KONDISI_TERAKHIR,
				TO_CHAR(A.TGL_KEMATIAN, 'DD-MM-YYYY') TGL_KEMATIAN,
				TO_CHAR(C.TGL_PEMBAYARAN, 'DD-MM-YYYY') TGL_PEMBAYARAN,
				C.NOM_PEMBAYARAN,
				B.NAMA_PENERIMA NAMA_BHP,
				B.NAMA_PENERIMA,
				B.BANK_PENERIMA,
				B.NO_REKENING_PENERIMA,
				B.NAMA_REKENING_PENERIMA,
				(SELECT NAMA_KANTOR FROM KN.MS_KANTOR B WHERE B.KODE_KANTOR = A.KODE_KANTOR AND ROWNUM = 1) NAMA_KANTOR,
				(SELECT B.NAMA_PERUSAHAAN FROM KN.KN_PERUSAHAAN B WHERE B.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN) NAMA_PERUSAHAAN,
				(SELECT B.NPP FROM KN.KN_PERUSAHAAN B WHERE B.KODE_PERUSAHAAN = A.KODE_PERUSAHAAN) NPP,
				CASE 
				WHEN    (
					SELECT  COUNT(1)
					FROM    PN.PN_KLAIM_BHP_DOK_UPLOAD B
					WHERE   B.KODE_KLAIM = A.KODE_KLAIM
									AND B.KODE_DOKUMEN = 'D301') > 0 THEN 'Y' ELSE 'T' END ST_UPLOAD_BERITA_ACARA,
				CASE 
				WHEN    (
					SELECT  COUNT(1)
					FROM    PN.PN_KLAIM_BHP_DOK_UPLOAD B
					WHERE   B.KODE_KLAIM = A.KODE_KLAIM
									AND B.KODE_DOKUMEN = 'D302') > 0 THEN 'Y' ELSE 'T' END ST_UPLOAD_BUKTI_TRANSFER,
				CASE 
				WHEN    (
					SELECT  COUNT(1)
					FROM    PN.PN_KLAIM_BHP_CETAK B
					WHERE   B.KODE_KLAIM = A.KODE_KLAIM
									AND B.KODE_DOKUMEN = 'D303') > 0 THEN 'Y' ELSE 'T' END ST_CETAK_SURAT_REKOM,
				(
					SELECT 	TO_CHAR(MAX(TGL_CETAK), 'DD-MM-YYYY')
					FROM 		PN.PN_KLAIM_BHP_CETAK B
					WHERE 	B.KODE_KLAIM = A.KODE_KLAIM
									AND B.KODE_DOKUMEN = 'D303') TGL_ST_CETAK_SURAT_REKOM,
				CASE WHEN ADD_MONTHS(TGL_PEMBAYARAN, 12* 30) > TRUNC(SYSDATE) THEN 'Y'
				ELSE 'T' END VALID_CETAK_SURAT_REKOM,
				TO_CHAR(ADD_MONTHS(TGL_PEMBAYARAN, 12* 30), 'DD-MM-YYYY') TGL_MAX_CETAK_SURAT_REKOM
FROM    PN.PN_KLAIM A,
				PN.PN_KLAIM_PENERIMA_MANFAAT B,
				PN.PN_KLAIM_PEMBAYARAN C
WHERE   A.KODE_KLAIM = B.KODE_KLAIM
				AND A.KODE_KLAIM = C.KODE_KLAIM      
				AND A.STATUS_BATAL = 'T'
				AND NVL(C.STATUS_BATAL, 'X') = 'T'
				AND B.KODE_TIPE_PENERIMA = 'BH'
				AND A.KODE_KLAIM = :P_KODE_KLAIM";
$proc = $DB->parse($sql);
oci_bind_by_name($proc, ":p_kode_klaim", $p_kode_klaim,30);
$DB->execute();
$row                         	= $DB->nextrow();
$ls_kode_klaim               	= $row["KODE_KLAIM"];
$ls_kpj                      	= $row["KPJ"];
$ls_nama_tk                  	= $row["NAMA_TK"];
$ls_tgl_meninggal            	= $row["TGL_KEMATIAN"];
$ls_npp                      	= $row["NPP"];
$ls_nama_perusahaan          	= $row["NAMA_PERUSAHAAN"];
$ls_nama_bhp                 	= $row["NAMA_BHP"];
$ls_nom_pembayaran           	= $row["NOM_PEMBAYARAN"];
$ls_tgl_pembayaran           	= $row["TGL_PEMBAYARAN"];
$ls_bank_penerima            	= $row["BANK_PENERIMA"];
$ls_no_rek_penerima          	= $row["NO_REKENING_PENERIMA"];
$ls_nama_rek_penerima        	= $row["NAMA_REKENING_PENERIMA"];
$ls_st_cetak_surat_rekom   	 	= $row["ST_CETAK_SURAT_REKOM"];
$ls_tgl_st_cetak_surat_rekom  = $row["TGL_ST_CETAK_SURAT_REKOM"];
$ls_st_upload_berita_acara   	= $row["ST_UPLOAD_BERITA_ACARA"];
$ls_st_upload_bukti_transfer 	= $row["ST_UPLOAD_BUKTI_TRANSFER"];
$ls_valid_cetak_surat_rekom		= $row["VALID_CETAK_SURAT_REKOM"];
$ls_tgl_max_cetak_rekom				= $row["TGL_MAX_CETAK_SURAT_REKOM"];

$ls_has_access_berita_acara = $KODE_ROLE == "28" ? "Y" : "T";
$ls_has_access_bukti_transfer = $KODE_ROLE == "4" ? "Y" : "T";
$ls_has_access_surat_rekomendasi = $KODE_ROLE == "4" ? "Y" : "T";
?>

<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.new.css?ver=1.2" />
<script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
<script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<script type="text/javascript" src="../../javascript/ajax.js"></script>
<script language="javascript">    
	var ajax = new sack();
	function fl_js_val_numeric(v_field_id){
		var c_val = window.document.getElementById(v_field_id).value;
		var c_id  = $('#jenis_identitas').val();
		
		var number=/^[0-9]+$/;
		if ((c_val!='') && (c_id =='KTP') && (!c_val.match(number))){
			document.getElementById(v_field_id).value = '';         
			window.document.getElementById(v_field_id).focus();
			alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");
			window.document.getElementById(v_field_id).value = '';         
			return false;         
		}   
	}

 	function fl_js_val_email(v_field_id)
	{
		var x = window.document.getElementById(v_field_id).value;
		var atpos=x.indexOf("@");
		var dotpos=x.lastIndexOf(".");
		if ((x!='') && (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length))
		{
			document.getElementById(v_field_id).value = '';         
			window.document.getElementById(v_field_id).focus();
			alert("Format Email tidak valid, belum ada (@ DAN .)");         
			return false;  
		}
	} 

	function fl_js_val_npwp(v_field_id)
	{
		var v_npwp = window.document.getElementById(v_field_id).value;
		if ((v_npwp!='') && (v_npwp!='0') && (v_npwp.length!=15))
		{
			document.getElementById(v_field_id).value = '0';        
			window.document.getElementById(v_field_id).focus();
			alert("NPWP tidak valid, harus 15 karakter...!!!");         
			return false;  
		}else
		{
				fl_js_val_numeric(v_field_id);  
		}
	}
	
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
    if(parseInt(navigator.appVersion) >= 4){
      win.window.focus();
    }
	}

	function confirmation(title, msg, fnYes, fnNo) {
		window.parent.Ext.Msg.show({
			title: title,
			msg: msg,
			buttons: window.parent.Ext.Msg.YESNO,
			icon: window.parent.Ext.Msg.QUESTION,
			fn: function(btn) {
				if (btn === 'yes') {
						fnYes();
				} else {
						fnNo();
				}
			}
		});
  }

	var asyncPreloadStart;

	function asyncPreload(state){
		if (state == true) {
			asyncPreloadStart = setInterval(function() {
				$('#loading').show();
				$('#loading-mask').show();
			}, 50);
		} else {
			$('#loading').hide();
			$('#loading-mask').hide();
			clearInterval(asyncPreloadStart);
		}
	}
</script>

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
  #mydata1 th {
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
  
  #mydata1 td {
    font-size: 10px;
    text-align: center;
    border-right: 0px solid rgb(221, 221, 221);
    border-bottom: 1px solid rgb(221, 221, 221);
    padding-top: 2px;
    padding-bottom: 2px;
  }
  
  #mydata1 {
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
<div id="formKiri" style="min-width:800px; width: 100%;">
<input type="hidden" id="hd_st_cetak_surat_rekom" name="hd_st_cetak_surat_rekom" value="<?=$ls_st_cetak_surat_rekom?>">
<input type="hidden" id="hd_tgl_st_cetak_surat_rekom" name="hd_tgl_st_cetak_surat_rekom" value="<?=$ls_tgl_st_cetak_surat_rekom?>">
<input type="hidden" id="hd_valid_cetak_surat_rekom" name="hd_valid_cetak_surat_rekom" value="<?=$ls_valid_cetak_surat_rekom?>">
<input type="hidden" id="hd_tgl_max_cetak_surat_rekom" name="hd_tgl_max_cetak_surat_rekom" value="<?=$ls_tgl_max_cetak_rekom?>">

	<table style="min-width: 800px; width: 100%; border-collapse: collapse;">
		<tr>
			<td width="49%" valign="top" style="border: 1px solid #d4d4d4; padding-top: 10px; padding-bottom: 10px;">
				<div class="form-row_kiri">
					<label style = "text-align:right;">Kode Klaim<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_kode_klaim" name="tb_kode_klaim" maxlength="30" value="<?=$ls_kode_klaim;?>" required style="width: 120px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">KPJ<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_kpj" name="tb_kpj" maxlength="30" value="<?=$ls_kpj;?>" required style="width: 120px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Nama Tenaga Kerja<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_nama_tk" name="tb_nama_tk" maxlength="100" value="<?=$ls_nama_tk;?>" required style="width: 280px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label  style = "text-align:right;">Tanggal Meninggal</label>
					<input type="text" id="tb_tgl_meninggal" name="tb_tgl_meninggal" value="<?=$ls_tgl_meninggal;?>" maxlength="10" onblur="convert_date(tb_tgl_meninggal);" readonly class="disabled" style="width: 80px;">
					<input id="btn_tgl_meninggal" type="image" align="top" onclick="return showCalendar('tb_tgl_meninggal', 'dd-mm-y');" src="../../images/calendar.gif" disabled>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">NPP Terakhir<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_npp" name="tb_npp" maxlength="30" value="<?=$ls_npp;?>" required style="width: 120px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Nama Perusahaan Terakhir<span style="color:#ff0000;">&nbsp;*</span></label>
					<textarea id="ta_nama_perusahaan" name="ta_nama_perusahaan" required style="width: 280px; background-color: #f4f4f4;" readonly class="disabled" rows="3"><?=$ls_nama_perusahaan?></textarea>
				</div>

				<div class="clear" style="padding-top: 12px;"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Nama BHP<span style="color:#ff0000;">&nbsp;*</span></label>
					<textarea id="ta_nama_bhp" name="ta_nama_bhp" required style="width: 280px; background-color: #f4f4f4;" readonly class="disabled" rows="3"><?=$ls_nama_bhp?></textarea>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Jumlah Saldo Transfer<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_nom_pembayaran" name="tb_nom_pembayaran" maxlength="30" value="<?=number_format((float)$ls_nom_pembayaran,2,".",",");?>" required style="width: 120px; text-align: right;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label  style = "text-align:right;">Tanggal Transfer<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_tgl_pembayaran" name="tb_tgl_pembayaran" value="<?=$ls_tgl_pembayaran;?>" maxlength="10" onblur="convert_date(tb_tgl_pembayaran);" readonly class="disabled" style="width: 80px;">
					<input id="btn_tgl_meninggal" type="image" align="top" onclick="return showCalendar('tb_tgl_pembayaran', 'dd-mm-y');" src="../../images/calendar.gif" disabled>
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Bank Transfer<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_bank_penerima" name="tb_bank_penerima" maxlength="300" value="<?=$ls_bank_penerima;?>" required style="width: 280px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">No. Rekening Penerima<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_no_rek_penerima" name="tb_no_rek_penerima" maxlength="100" value="<?=$ls_no_rek_penerima;?>" required style="width: 280px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Nama Rek. Penerima<span style="color:#ff0000;">&nbsp;*</span></label>
					<input type="text" id="tb_nama_rek_penerima" name="tb_nama_rek_penerima" maxlength="100" value="<?=$ls_nama_rek_penerima;?>" required style="width: 280px;" readonly class="disabled">
				</div>
				<div class="clear"></div>
			</td>
			<td width="2%">
			</td>
			<td width="49%" valign="top" style="border: 1px solid #d4d4d4; padding-top: 10px; padding-bottom: 10px;">
				<div class="form-row_kiri">
					<label style = "text-align:right;">Berita Acara<span style="color:#ff0000;">&nbsp;*</span></label>
					<?php
					if ($ls_st_upload_berita_acara == "T") {
						if ($ls_has_access_berita_acara == "T") {
							echo '<span>Tidak memiliki akses</span>';
						} else if ($ls_has_access_berita_acara == "Y") {
							echo '<input type="file" id="fl_uload_berita_acara" name="fl_uload_berita_acara" style="width:165px; background-color:#ffff99" accept=".pdf, .png, .jpg, .jpeg">';
							echo '<input type="button" name="tampilkan" class="btn green" value="Upload" onclick="doUploadDokumen(\'D301\')">';
						}
					} else if ($ls_st_upload_berita_acara == "Y") {
						echo '<a href="#" onclick="doDownloadDokumen(\'D301\')" style="padding-right: 10px;">';
						echo '<img src="../../images/downloadx.png" border="0" alt="Download" align="absmiddle" style="height:18px;">&nbsp;Download';
						echo '</a>';

						if ($ls_has_access_berita_acara == "Y") {
							echo '<a href="#" onclick="doDeleteDokumen(\'D301\')" style="padding-right: 10px;">';
							echo '<img src="../../images/removex.png" border="0" alt="Delete" align="absmiddle" style="height:18px;">&nbsp;Delete';
							echo '</a>';
						}
					}
					?>
				</div>
				<div class="clear" style="padding-bottom: 6px;"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Bukti Transfer<span style="color:#ff0000;">&nbsp;*</span></label>
					<?php
					if ($ls_st_upload_bukti_transfer == "T") {
						if ($ls_has_access_bukti_transfer == "T") {
							echo '<span>Tidak memiliki akses</span>';
						} else if ($ls_has_access_bukti_transfer == "Y") {
							echo '<input type="file" id="fl_upload_bukti_transfer" name="fl_upload_bukti_transfer" style="width:165px; background-color:#ffff99" accept=".pdf, .png, .jpg, .jpeg">';
							echo '<input type="button" name="tampilkan" class="btn green" value="Upload" onclick="doUploadDokumen(\'D302\')">';
						}
					} else if ($ls_st_upload_bukti_transfer == "Y") {
						echo '<a href="#" onclick="doDownloadDokumen(\'D302\')" style="padding-right: 10px;">';
						echo '<img src="../../images/downloadx.png" border="0" alt="Download" align="absmiddle" style="height:18px;">&nbsp;Download';
						echo '</a>';

						if ($ls_has_access_bukti_transfer == "Y") {
							echo '<a href="#" onclick="doDeleteDokumen(\'D302\')" style="padding-right: 10px;">';
							echo '<img src="../../images/removex.png" border="0" alt="Delete" align="absmiddle" style="height:18px;">&nbsp;Delete';
							echo '</a>';
						}
					}
					?>
				</div>
				<div class="clear" style="padding-bottom: 6px;"></div>
				<div class="form-row_kiri">
					<label style = "text-align:right;">Surat Rekomendasi<span style="color:#ff0000;">&nbsp;*</span></label>
					<?php 
					if ($ls_st_cetak_surat_rekom == "T") {
						if ($ls_has_access_surat_rekomendasi == "T") {
							echo '<span>Tidak memiliki akses</span>';
						} else if ($ls_has_access_surat_rekomendasi == "Y") {
							echo '<a href="#" onclick="doCetakSuratRekomendasi()">';
							echo '<img src="../../images/downloadx.png" border="0" alt="Download" align="absmiddle" style="height:18px;">&nbsp;Cetak Surat Rekomendasi Ahli Waris';
							echo '</a>';
						}
					} else if ($ls_st_cetak_surat_rekom == "Y"){
						echo '<span>Sudah pernah dicetak</span>';
					} 
					?>
				</div>
				<div class="clear"></div>
				<div class="clear" style="padding-bottom: 30px;"></div>
			
				<div style="background: #f2f2f2;padding:5px 10px;border:1px solid #ececec;">
					<span><b>Keterangan:</b></span>
					<li style="margin-left:15px;">Maksimal ukuran file adalah 2 MB (dengan ekstensi: png, jpg, pdf)</li>
					<li style="margin-left:15px;">Surat Rekomendasi hanya bisa dicetak sekali</li>
					<li style="margin-left:15px;">Pencetakan Surat Rekomendasi paling lama tanggal <?=$ls_tgl_max_cetak_rekom?> (30 tahun sejak tanggal transfer) </li>
				</div>
			</td>
		</tr>    
	</table>  
</div>

<script type="text/javascript">
  $(document).ready(function(){
		$('input').keyup(function(){
			this.value = this.value.toUpperCase();
		});
	});
	
	function doUploadDokumen(tipe_dokumen) {
    if (tipe_dokumen != 'D301' && tipe_dokumen != 'D302') {
      return alert('Tipe dokumen tidak ditemukan');
		} 
		var fl_uload_berita_acara = $('#fl_uload_berita_acara').val();
		var fl_upload_bukti_transfer = $('#fl_upload_bukti_transfer').val();
		if (tipe_dokumen == 'D301' && (fl_uload_berita_acara == undefined || fl_uload_berita_acara == '')){
			return alert('Pilih file Dokumen Berita Acara terlebih dulu');
		}
		if (tipe_dokumen == 'D302' && (fl_upload_bukti_transfer == undefined || fl_upload_bukti_transfer == '')){
			return alert('Pilih file Dokumen Bukti Transfer terlebih dulu');
		}
	
		var kode_klaim = $('#tb_kode_klaim').val();
		if (tipe_dokumen == 'D301') {
			var fileInput = document.getElementById('fl_uload_berita_acara');
		} else if (tipe_dokumen == 'D302') {
			var fileInput = document.getElementById('fl_upload_bukti_transfer');
		}
		var file = fileInput.files[0];
		var formData = new FormData();
		formData.append("tipe", "upload_dokumen");
		formData.append("tipe_dokumen", tipe_dokumen);
		formData.append("kode_klaim", kode_klaim);
		formData.append("datafile", file);
		asyncPreload(true);
		$.ajax({
				type: "POST",
				url: "../ajax/pn5054_action.php",
				data: formData,
				enctype: 'multipart/form-data',
				processData: false,
				contentType: false,
				cache: false,
				complete: function(){
					asyncPreload(false);
				},
				success: function (data) {
					var jdata = JSON.parse(data);
					if (jdata.ret == '0') {
						alert(jdata.msg);
						location.reload();
					} else {
						alert(jdata.msg);
					}
					asyncPreload(false);
				},
				error: function (error) {
					alert(data.msg);
					asyncPreload(false);
				}
		});
	}

	function doDeleteDokumen(tipe_dokumen) {
    if (tipe_dokumen != 'D301' && tipe_dokumen != 'D302') {
      return alert('Tipe dokumen tidak ditemukan');
		}
		var kode_klaim = $('#tb_kode_klaim').val();

		confirmation('Delete Dokumen Pendukung', 'Yakin untuk menghapus dokumen ini?', function(){
			$.ajax({
				type: 'POST',
				url : "../ajax/pn5054_action.php?"+Math.random(),
				data: {
					tipe: 'delete_dokumen',
					tipe_dokumen: tipe_dokumen,
					kode_klaim: kode_klaim
				},
				success: function(data){
					var jdata = JSON.parse(data);
					if (jdata.ret == '0') {
						alert(jdata.msg);
						location.reload();
					} else {
						alert(jdata.msg);
					}
					asyncPreload(false);
				},
				complete: function(){
					asyncPreload(false);
				},
				error: function(){
					alert("Terjadi kesalahan, coba beberapa saat lagi!");
					asyncPreload(false);
				}
			});
		}, function(){});
  }

	function doDownloadDokumen(tipe_dokumen) {
    if (tipe_dokumen != 'D301' && tipe_dokumen != 'D302') {
      return alert('Tipe dokumen tidak ditemukan');
		}
		var kode_klaim = $('#tb_kode_klaim').val();

		window.open('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5054_action.php?&kode_klaim=' + kode_klaim + '&tipe_dokumen=' + tipe_dokumen + '&tipe=download_dokumen');
	}

	function doCetakSuratRekomendasi(){
		var kode_klaim = $('#tb_kode_klaim').val();
		var st_cetak_surat_rekom = $('#hd_st_cetak_surat_rekom').val();
		var tgl_st_cetak_surat_rekom = $('#hd_tgl_st_cetak_surat_rekom').val();
		var valid_cetak_surat_rekom = $('#hd_valid_cetak_surat_rekom').val();
		var tgl_max_cetak_surat_rekom = $('#hd_tgl_max_cetak_surat_rekom').val();

		if (valid_cetak_surat_rekom == 'T') {
			return alert('Surat Rekomendasi sudah melewati tanggal maksimal cetak (' + tgl_max_cetak_surat_rekom + ')');
		} 
		if (valid_cetak_surat_rekom != 'Y' && valid_cetak_surat_rekom != 'T') {
			return alert('Gagal cetak surat rekomendasi. Coba beberapa saat lagi');
		}

		if (hd_st_cetak_surat_rekom == 'T') {
			alert('Surat Rekomendasi sudah pernah dicetak tanggal ' + tgl_st_cetak_surat_rekom);
		} else {
			confirmation('Cetak Surat Rekomendasi', 'Anda hanya bisa cetak Dokumen Surat Rekomendasi 1 kali, yakin untuk melanjutkan?', 
				function(){
					$.ajax({
					type: 'POST',
					url : "../ajax/pn5054_action.php?"+Math.random(),
					data: {
						tipe: 'cetak_dokumen',
						kode_klaim: kode_klaim,
						tipe_dokumen: 'D303'
					},
					success: function(data){
						var jdata = JSON.parse(data);
						if (jdata.ret == '0') {
							NewWindow('../ajax/pn5054_action.php?tipe=cetak_dokumen&kode_klaim=' + kode_klaim,'', 100, 100,1);
							location.reload();
						} else {
							alert(jdata.msg);
							location.reload();
						}
						asyncPreload(false);
					},
					complete: function(){
						asyncPreload(false);
					},
					error: function(){
						alert("Terjadi kesalahan, coba beberapa saat lagi!");
						asyncPreload(false);
					}
				});
				}, function(){
				});
		}
	}
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>