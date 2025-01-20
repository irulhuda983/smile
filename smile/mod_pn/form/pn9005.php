<?php
$pagetype = "report";
$gs_pagetitle = "PN9005 - LAPORAN KLAIM JPN";
require_once "../../includes/header_app.php";	
require_once '../../includes/conf_global.php';
require_once '../../includes/fungsi_newrpt.php';
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script language="javascript">
</script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<style>
	.header-popup {
		position: absolute;
		top: 0;
		left: 0;
		width: 98%;
		text-align: left;
	}

	.header-popup h3 {
		color: #ffffff;
		font-size: 14px;
		margin: 8px 10px 10px 10px;
		background: url(../images/application_view_columns.png) no-repeat;
	}
</style>
<?php

$kode_kantor = $_SESSION['kdkantorrole'];
$sql = "select * from sijstk.ms_kantor where kode_kantor = '$kode_kantor'";
$DB->parse($sql);
$DB->execute();
$data = $DB->nextrow();

if($data['KODE_KELAS']=='0' && $data['KODE_KANTOR_INDUK']=='P'){
	$where = " AND b.flag_kapu = 'Y'";
}else if($data['KODE_KELAS']=='0' && $data['KODE_KANTOR_INDUK']!='P'){
	$where = " AND b.flag_kanwil = 'Y'";
}else if($data['KODE_KELAS']=='1' || $kode_kantor=='2'){
	$where = " AND b.flag_kacab = 'Y'";
}else if($kode_kantor=='0'){
	$where = " AND b.flag_kapu = 'Y'";
}else{
	$where = " AND b.flag_kcp = 'Y'";
}
$sql2 = " select a.rowid, a.kode_report, b.nama_jenis_report_detil, a.parameter_report
          from sijstk.kn_kode_report a,
          sijstk.kn_kode_report_jenis_detil b,
          sijstk.kn_kode_report_jenis_role  c
          where a.kode_report = b.kode_report
          and b.kode_jenis_report = c.kode_jenis_report
          and b.kode_jenis_report_detil = c.kode_jenis_report_detil
          and b.kode_jenis_report = 'PN9005'
          and b.status_nonaktif = 'T'
          and c.kode_fungsi = '".$_SESSION['regrole']."'".$where;
$DB->parse($sql2);
$DB->execute();
$REPORT = array();
while ($row = $DB->nextrow())
{
	$arr = explode("#", $row['PARAMETER_REPORT']);
	$arr_field_view	= explode(",", $arr[0]);
	$arr_parameter 	= explode(",", $arr[1]);
	$array_param 	= array();
	for($i=0;$i<=ExtendedFunction::count($arr_parameter)-1;$i++){
		$array_param["$arr_parameter[$i]"] = $arr_field_view[$i];
	}

	$array_detil = array(
			"KODE_REPORT"	=> $row['KODE_REPORT'],
			"NAMA_REPORT"	=> $row['KODE_REPORT']." - ".$row['NAMA_JENIS_REPORT_DETIL'],
			"FIELDS_VIEW"	=> $arr_field_view,
			"PARAMS_REPORT"	=> $array_param
	);
	$REPORT[$row['KODE_REPORT']] = $array_detil;
}

ksort($REPORT);

$ses_username = $_SESSION["USER"];
$ses_kode_kantor = $_SESSION["kdkantorrole"];

$jenis_report = $_POST["jenis_report"];
$iscetak = $_POST["btncetak"];
$iscetak_excel = $_POST["btncetakxls"];

$isdevelopment = "1";
$showparams = $_POST["showparams"];

if (isset($jenis_report) && (isset($iscetak) || isset($iscetak_excel))) {	
	$tipe = isset($iscetak) ? "PDF" : "";
	$tipe = isset($iscetak_excel) ? "EXCEL" : "PDF";
	
	// FILL REPORT PARAMS
	$ls_modul = "pn";
	$ls_nama_rpt = "$jenis_report.rdf"; 
	$ls_user_param = "";
	foreach(array_keys($REPORT[$jenis_report]["PARAMS_REPORT"]) as $key){
		$field = $REPORT[$jenis_report]["PARAMS_REPORT"][$key];
		if ($field == "periode" || $field == "periode1" || $field == "periode2") {
            $value = str_replace("/", "-", $_POST[$field]);
        } else {
            $value = $_POST[$field];
        }
		$ls_user_param .= " " . $key . "='" . $value . "'";
	}
										
	// CALL REPORT GENERATOR
	if ($ls_error=="1") {

	} else {	
		exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
	}
}
?>

<div id="actmenu" class="header-popup">	
    <h3><?=$gs_pagetitle;?></h3>
</div>
<br><br><br>
<div id="formframe">
	<div id="formKiri">
        <form name="formreg" id="formreg" role="form" method="post">
			<input type="hidden" id="username" name="username" value="<?=$ses_username?>">
            <fieldset><legend></legend>
                <div class="form-row_kiri">
                    <label>Jenis Laporan</label>
                    <select id="jenis_report" name="jenis_report" style="width:520px;" onchange="loadParameters()">
						<option value="">----Pilih Jenis Laporan----</option>
						<?php
							foreach ($REPORT as $repo) {
								$kode_report = $repo["KODE_REPORT"];
								$nama_report = $repo["NAMA_REPORT"];
						?>
						<option value="<?=$kode_report?>" <?=$jenis_report == $kode_report ? "selected" : ""?>><?=$nama_report?></option> 
						<?php } ?>
                    </select>			
					<input type="submit" name="btntampil" class="btn green" id="btntampil" value="TAMPILKAN" style="display: none;">
                </div>
                <div class="clear"></div>
				<!-- LOAD FIELDS VIEW -->
				<?php
					if(isset($REPORT[$jenis_report])) { 
						foreach ($REPORT[$jenis_report]["FIELDS_VIEW"] as $field) {
							include "pn9001_fields_view.php";
						}
					}
				?>
				<!-- END LOAD FIELDS VIEW -->

				<!-- SHOW PARAMS DEVELOPMENT-->
				<?php 
					if($isdevelopment == "1") { 
				?>
				<div class="form-row_kiri">	
					<label>Show Parameter</label>
					<input type="checkbox" id="showparams" name="showparams" class="cebox" <?=$showparams == "on" ? "checked" : ""?>/>
				</div>
				<div class="clear"></div>	
				<?php 
					if($showparams == "on") { 
				?>
				<div class="form-row_kiri">	
					<label>Parameter</label>
					<input id="showparams_value" name="showparams_value" type="text" style="width: 520px;" value="<?=$ls_user_param?>"/>
				</div>
				<div class="clear"></div>	
				<?php } ?>
				<?php } ?>
            </fieldset>	
			<?php 
				if(isset($jenis_report)) { 
			?>	
			<fieldset><legend></legend>
				<div class="form-row_kiri">
					<label>&nbsp;</label>
					<input type="submit" class="btn green" id="btncetak" name="btncetak" value="CETAK LAPORAN" style="padding-left: 10px; padding-right: 10px;" title="Klik Untuk Cetak Laporan">
				<input type="submit" class="btn green" id="btncetakxls" name="btncetakxls" value="CETAK LAPORAN EXCEL" style="padding-left: 10px; padding-right: 10px;" title="Klik Untuk Cetak Laporan Excel">
				</div>
			</fieldset>
			<?php } ?>
        </form>
         </div>   
        
		<?php
		if (isset($msg)){
		?>
		<fieldset>
            <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
            <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?php
		}
		?>	
	</div>
</div>	
<script type="text/javascript">
	$(document).ready(function(){
		preload(true);
		
		$('input[type=text]').keyup(function () {
			this.value = this.value.toUpperCase();
		});

		setTimeout(function(){
			$('#loading').hide();
			$('#loading-mask').hide();
		}, 250);
	});

	function loadParameters(){
		preload(true);
		$("#btntampil").click();
	}
</script>
<?php
include "../../includes/footer_app_nosql.php";
?>