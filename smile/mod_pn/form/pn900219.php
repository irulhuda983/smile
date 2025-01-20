<?
$pagetype = "report";
$gs_pagetitle = "PN900219 - SM0570-2 - DAFTAR JAMINAN SIAP BAYAR WILAYAH (PNR5006)";

require_once "../../includes/header_app.php";
include '../../includes/fungsi_newrpt.php';

$ls_kode_segmen = "PU";
$username = $_SESSION["USER"];
$ls_kode_kantor = $_POST["kode_kantor"];
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $_SESSION['kdkantorrole'];
$ld_tgl1 = $_POST["tgl1"];
$ld_tgl2 = $_POST["tgl2"];
	
$iscetak = $_POST["btncetak"];
$iscetak_excel = $_POST["btncetak_excel"];

if (isset($iscetak) || isset($iscetak_excel)) {	
	$tipe = isset($iscetak) ? "PDF" : "";
	$tipe = isset($iscetak_excel) ? "EXCEL" : "PDF";
	
	$sql = "
	SELECT	TO_CHAR(TO_DATE('$ld_tgl1', 'DD/MM/YYYY'), 'DD-MM-YYYY') TGL1,
			TO_CHAR(TO_DATE('$ld_tgl2', 'DD/MM/YYYY'), 'DD-MM-YYYY') TGL2
	FROM 	DUAL";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_laptgl1 = $row["TGL1"];
	$ls_laptgl2 = $row["TGL2"];
	
	$ls_lap_kode_kantor = $ls_kode_kantor;
	$ls_user_param .= " P_USER='$username'";
	$ls_user_param .= " P_PERIODE1='$ls_laptgl1'";
	$ls_user_param .= " P_PERIODE2='$ls_laptgl2'";
	$ls_user_param .= " P_KODE_WILAYAH='$ls_lap_kode_kantor'";
	
	$ls_link 	 = "http://".$HTTP_HOST."/urldest.php?url=";
	$ls_user 	 = 'kn';
	$ls_pass	 = 'welcome1';
	$ls_sid      = "dbdevelop";
	$ls_path 	 = "/data/reports/pn";
	$ls_nama_rpt = "PNR5006.rdf";
			
	if ($ls_error=="1"){
	} else {
		exec_rpt_enc_tipe($ls_link, $ls_user, $ls_pass, $ls_sid, $ls_path, 1, $ls_nama_rpt, $ls_user_param, $tipe);
	}
}		
?>

<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript">
</script>		

<?	
	$sql = "SELECT NAMA_KANTOR FROM SIJSTK.MS_KANTOR WHERE KODE_KANTOR = '$ls_kode_kantor'";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();	
	$ls_nama_kantor = $row['NAMA_KANTOR'];	
	
	if ($ld_tgl1 == "") {
		$sql = "
		SELECT	TO_CHAR(TRUNC(SYSDATE,'MM'), 'DD/MM/YYYY') TGL1, 
				TO_CHAR(SYSDATE, 'DD/MM/YYYY') TGL2
		FROM 	DUAL ";		
		
	    $DB->parse($sql);
	    $DB->execute();
	    $row 		= $DB->nextrow();
		$ld_tgl1 	= $row["TGL1"];
		$ld_tgl2 	= $row["TGL2"];
	}
?>				
<div id="formframe">
	<div id="formKiri">
		<fieldset><legend><?=$gs_pagetitle?></legend>
			<div class="form-row_kiri">
				<label>Kantor Wilayah *</label>
				<input type="text" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px;" required>
				<input type="text" name="nama_kantor" id="nama_kantor" size="30" style="width: 320px; background-color:#ffff99;" value="<?=$ls_nama_kantor;?>" tabindex="1" required readonly>															 								
			</div>
			<div class="clear"></div>
			<div class="clear"></div>
			<div class="form-row_kiri">	
      			<label>Periode *</label>
				  <input type="text" name="tgl1" id="tgl1" size="12" onblur="convert_date(tgl1);" style="width: 80px;" value="<?=$ld_tgl1;?>" tabindex="2" required> 
				  <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl1', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
				  s/d
				  <input type="text" name="tgl2" id="tgl2" size="12" onblur="convert_date(tgl2);" style="width: 80px;" value="<?=$ld_tgl2;?>" tabindex="2" required> 
				  <input id="btn_tgl" type="image" align="top" onclick="return showCalendar('tgl2', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
		  	</div>
	    	<div class="clear"></div>
		</fieldset>
		</br>
		
		<fieldset><legend></legend>
			<div class="form-row_kiri">
				<label>&nbsp;</label>
				<input type="submit" class="btn green" id="btncetak" name="btncetak" value="CETAK LAPORAN" title="Klik Untuk Cetak Laporan">
			<input type="submit" class="btn green" id="btncetak_excel" name="btncetak_excel" value="CETAK LAPORAN EXCEL" title="Klik Untuk Cetak Laporan Excel">
			</div>
		</fieldset>
		<?
		if (isset($msg)){
		?>
		<fieldset>
			<?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
			<?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?
		}
		?>
								
	</div>
</div>			
<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>