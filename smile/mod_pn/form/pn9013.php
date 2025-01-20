<?php
$pagetype = "report";
$gs_pagetitle = "PN9013 - LAPORAN PENDUKUNG DASHBOARD";
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
$REPORT = array(
        "PND901301" => array(
                "KODE_REPORT" => "PND901301",
                "NAMA_REPORT" => "PND901301 - LAPORAN DETIL JKK PENDING CABANG",
                "FIELDS_VIEW" => array(
                        "tahun",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_TAHUN" => "tahun",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => 'username'
                )
        ),
        "PND901302" => array(
                "KODE_REPORT" => "PND901302",
                "NAMA_REPORT" => "PND901302 - LAPORAN DETIL JKK PLKK PENDING",
                "FIELDS_VIEW" => array(
                        "tahun",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_TAHUN" => "tahun",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => 'username'
                )
        ),
        "PND901303" => array(
                "KODE_REPORT" => "PND901303",
                "NAMA_REPORT" => "PND901303 - LAPORAN PLKK AKTIF",
                "FIELDS_VIEW" => array(
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => 'username'
                )
        ),
        "PND901304" => array(
                "KODE_REPORT" => "PND901304",
                "NAMA_REPORT" => "PND901304 - LAPORAN REKAP PLKK AKTIF",
                "FIELDS_VIEW" => array(
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_USER" => 'username'
                )
        ),
        "PND901305" => array(
                "KODE_REPORT" => "PND901305",
                "NAMA_REPORT" => "PND901305 - LAPORAN JHT JATUH TEMPO",
                "FIELDS_VIEW" => array(
                      //  "periode",
                        "kode_kantor",
                        "kode_segmen",
                        "usia1",
                        "usia2",
                ),
                "PARAMS_REPORT" => array(
                     //   "P_PERIODE" => "periode",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_KODE_SEGMEN" => 'kode_segmen',
                        "P_USIA1" => 'usia1',
                        "P_USIA2" => 'usia2'
                )
        ),
        "PND901306" => array(
                "KODE_REPORT" => "PND901306",
                "NAMA_REPORT" => "PND901306 - LAPORAN TK NA BELUM KLAIM",
                "FIELDS_VIEW" => array(
                        "periode1",
                        "periode2",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_PERIODE1" => "periode1",
                        "P_PERIODE2" => "periode2",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => "username"
                )
        ),
        "PND901307" => array(
                "KODE_REPORT" => "PND901307",
                "NAMA_REPORT" => "PND901307 - LAPORAN JKM PENDING BEASISWA",
                "FIELDS_VIEW" => array(
                        "tahun",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_TAHUN" => "tahun",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => "username"
                )
        ),
        "PND901308" => array(
                "KODE_REPORT" => "PND901308",
                "NAMA_REPORT" => "PND901308 - LAPORAN JKM PENDING",
                "FIELDS_VIEW" => array(
                        "periode",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_PERIODE" => "periode",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => "username"
                )
        ),
        "PND901309" => array(
                "KODE_REPORT" => "PND901309",
                "NAMA_REPORT" => "PND901309 - LAPORAN JP PENDING LUMPSUM",
                "FIELDS_VIEW" => array(
                        "tahun",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_TAHUN" => "tahun",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => "username"
                )
        ),
        "PND901310" => array(
                "KODE_REPORT" => "PND901310",
                "NAMA_REPORT" => "PND901310 - LAPORAN JP PENDING BERKALA",
                "FIELDS_VIEW" => array(
                        "tahun",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_TAHUN" => "tahun",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => "username"
                )
        ),
        "PND901311" => array(
                "KODE_REPORT" => "PND901311",
                "NAMA_REPORT" => "PND901311 - LAPORAN JP JATUH TEMPO",
                "FIELDS_VIEW" => array(
                        "tahun",
                        "kode_kantor",
                        "username"
                ),
                "PARAMS_REPORT" => array(
                        "P_TAHUN" => "tahun",
                        "P_KODE_KANTOR" => "kode_kantor",
                        "P_USER" => "username"
                )
        )
);
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
	$ls_modul = "kn";
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
		if($ls_nama_rpt=='KNR4001.rdf' || $ls_nama_rpt=='KNR4002.rdf' || $ls_nama_rpt=='KNR4003.rdf'  )	{
		exec_rpt_enc_new_drc(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);	
		}else{
		exec_rpt_enc_new(1, $ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);
		}
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
						<option value="">----PILIH JENIS LAPORAN----</option>
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
							include "pn9013_fields_view.php";
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