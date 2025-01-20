<?
$pagetype 		= "report";
$gs_pagetitle 	= "PN900202 - SM4030 - DAFTAR PENETAPAN JHT (PNR1015)";

require_once "../../includes/header_app.php";
include '../../includes/fungsi_newrpt.php';

$username 			= $_SESSION["USER"];
$ls_kode_kantor		= $_POST["kode_kantor"];
$ls_kode_kantor 	= isset($ls_kode_kantor) ? $ls_kode_kantor : $_SESSION['kdkantorrole'];
$ls_jenis_laporan	= $_POST["jenis_laporan"];
$ld_tgl1			= $_POST["tgl1"];
$ld_tgl2			= $_POST["tgl2"];
$ls_kode_segmen		= "PU";
	
$iscetak 			= $_POST["btncetak"];
$iscetak_excel 		= $_POST["btncetak_excel"];

if (isset($iscetak) || isset($iscetak_excel)) {
	$tipe = isset($iscetak) ? "PDF" : "";
	$tipe = isset($iscetak_excel) ? "EXCEL" : "PDF";
	
	$sql = "
	select to_char(to_date('$ld_tgl1','dd/mm/yyyy'),'dd-mm-yyyy') tgl1,
	to_char(to_date('$ld_tgl2','dd/mm/yyyy'),'dd-mm-yyyy') tgl2 from dual";
	$DB->parse($sql);
	$DB->execute();
	$row = $DB->nextrow();
	$ls_laptgl1 = $row["TGL1"];
	$ls_laptgl2 = $row["TGL2"];
	
	$ls_lap_kode_kantor = $ls_kode_kantor;
	$ls_user_param .= " P_KODE_KANTOR='$ls_lap_kode_kantor'";
	$ls_user_param .= " P_PERIODE1='$ls_laptgl1'";
	$ls_user_param .= " P_PERIODE2='$ls_laptgl2'";
	$ls_user_param .= " P_USER='$username'";
	
	$ls_link 	 = "http://".$HTTP_HOST."/urldest.php?url=";
	$ls_user 	 = 'kn';
	$ls_pass	 = 'welcome1';
	$ls_sid      = "dbdevelop";
	$ls_path 	 = "/data/reports/pn";
	$ls_nama_rpt = "PNR1015.rdf"; 
													
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
</script>		

<?	
	$sql = "select nama_kantor from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor'";		
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();	
    $ls_nama_kantor = $row['NAMA_KANTOR'];
		
	if($ls_jenis_laporan=="") {
		$ls_jenis_laporan = "lap01";
	}	
	
	if ($ld_tgl1 == "") {
		$sql = "select to_char(trunc(sysdate,'mm'),'dd/mm/yyyy') as tgl1, to_char(sysdate,'dd/mm/yyyy') as tgl2 from dual ";		
		
	    $DB->parse($sql);
	    $DB->execute();
	    $row 		= $DB->nextrow();
		$ld_tgl1 	= $row["TGL1"];
		$ld_tgl2 	= $row["TGL2"];
	}		
?>				
<div id="formframe">
	<span id="dispError1" style="display:none;color:red"></span>
  	<input type="hidden" id="st_errval1" name="st_errval1">
	<div id="formKiri">
		<fieldset><legend><?=$gs_pagetitle?></legend>
			<div class="form-row_kiri">
				<label>Kantor *</label>
				<input type="text" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" size="10" style="width: 80px;">
				<input type="text" name="nama_kantor" id="nama_kantor" size="30" style="width: 320px; background-color:#ffff99;" value="<?=$ls_nama_kantor;?>" tabindex="1" required readonly>
				<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn9002_lov_kantor.php?p=pn900202.php&a=adminForm&b=kode_kantor&c=nama_kantor','',800,500,1)" style="display: none;">   
				<img src="../../images/help.png" alt="Cari User" border="0" align="absmiddle"></a>   															 								
			</div>
			<div class="clear"></div>
			<div class="clear"></div>					
			<div class="form-row_kiri">	
      			<label>Periode *</label>
				  <input type="text" name="tgl1" id="tgl1" size="12" onblur="convert_date(tgl1);"  value="<?=$ld_tgl1;?>" tabindex="2" required> 
				  <input id="btn_tgl1" type="image" align="top" onclick="return showCalendar('tgl1', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
				  s/d
				  <input type="text" name="tgl2" id="tgl2" size="12" onblur="convert_date(tgl2);"  value="<?=$ld_tgl2;?>" tabindex="2" required> 
				  <input id="btn_tgl2" type="image" align="top" onclick="return showCalendar('tgl2', 'dd-mm-yyyy');" src="../../images/calendar.gif" />
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