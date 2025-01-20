<?php
$pagetype = "report";
$gs_pagetitle = "PN9014 - DAFTAR JAMINAN SIAP BAYAR";
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
$ls_kode_kantor		= $_POST["kode_kantor"];
$ls_kode_segmen		= $_POST["kode_segmen"];
$ls_kd_prg				= $_POST["kd_prg"];
$ls_jenis_laporan	 	= $_POST["jenis_laporan"];
$ld_tgl1    = $_POST["tgl1"];

//Nilai Default --------------------------------------------------------------
//1. Kode Kantor, isikan dengan global.kantor_aktif		
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
if($ls_kode_kantor=="")
{
    $ls_kode_kantor =  $gs_kantor_aktif;
}

if ($ld_tgl1 == "")
{
    $sql = 	"select to_char(trunc(sysdate,'mm'),'dd/mm/yyyy') as tgl1, to_char(sysdate,'dd/mm/yyyy') as tgl2, ".
                    "to_char(sysdate,'dd/mm/yyyy') as tgllap from dual ";		
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ld_tgl1 = $row["TGL2"];	
}																
//End Nilai Default ----------------------------------------------------------

if(isset($_POST["btncetak"]))
{	
    $tipe     = "PDF";
    $ls_nama_rpt = "HUR202302001.rdf";
    $ls_modul = "lk";  

    $username = $_SESSION['USER'];

    $sql = "select to_char(to_date('$ld_tgl1','dd/mm/yyyy'),'yyyymmdd') tgl1, ".
      "			 to_char(to_date('$ld_tgl2','dd/mm/yyyy'),'yyyymmdd') tgl2 from dual";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_laptgl1 		= $row["TGL1"];						
    $ls_laptgl2 		= $row["TGL2"];

    if ($ls_kd_prg=='998') {
      $ls_kd_prg = '';
    }

    $ls_user_param = " P_KODE_KANTOR=$ls_kode_kantor"; 
    $ls_user_param .= " P_PERIODE=$ls_laptgl1"; 
    $ls_user_param .= " P_KODE_USER=$username"; 
    $ls_user_param .= " P_KD_PRG=$ls_kd_prg"; 
    $ls_user_param .= " P_KODE_SEGMEN=$ls_kode_segmen"; 

    if ($ls_error=="1")
	{

    } else	{							
        exec_rpt_enc_new(1,$ls_modul, $ls_nama_rpt, $ls_user_param, $tipe);			
	}
}
?>

<div id="actmenu" class="header-popup">	
    <h3><?=$gs_pagetitle;?></h3>
</div>
<br><br><br>
<div id="formframe">
	<div id="formKiri">
    <fieldset style="width:650px;"><legend>Parameter Laporan</legend>
			</br>
																					
			<div class="form-row_kiri">
			<label style = "text-align:right;">Kantor &nbsp;&nbsp;</label>
				<select size="1" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" tabindex="1" class="select_format" onchange="f_ajax_val_kd_prg();" style="width:350px;">
				<option value="">-- Pilih Kantor --</option>
				<? 
				$sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".    									 	 
				"start with kode_kantor = '$gs_kantor_aktif' ".
				"connect by prior kode_kantor = kode_kantor_induk";
				$DB->parse($sql);
				$DB->execute();
				while($row = $DB->nextrow())
				{
				echo "<option ";
				if ($row["KODE_KANTOR"]==$ls_kode_kantor && strlen($ls_kode_kantor)==strlen($row["KODE_KANTOR"])){ echo " selected"; }
				echo " value=\"".$row["KODE_KANTOR"]."\">".$row["KODE_KANTOR"]." - ".$row["NAMA_KANTOR"]."</option>";
				}
				?>
				</select>															 								
			</div>
			<div class="clear"></div>
			<div class="clear"></div>


			<div class="form-row_kiri" id="fr_ksegmen">
  		<label style = "text-align:right;">Kode Segmen &nbsp;&nbsp;&nbsp;</label>
  				<select size="1" id="kode_segmen" name="kode_segmen" class="select_format" tabindex="2"  style="width:350px;"  onchange="jenis_laporan_change()">
            <? 
							switch($_POST["kode_segmen"])
							{
								case 'PU' : $ls_kode_segmen_pu="selected"; break;
								case 'BPU' : $ls_kode_segmen_bpu="selected"; break;
                case 'EJAKON' : $ls_kode_segmen_ejakon="selected"; break;
                case 'TKI' : $ls_kode_segmen_pmi="selected"; break;
							}
						?>
            <option value="">-- Pilih Segmen --</option>
            <option value="PU" <?=$ls_kode_segmen_pu;?>>PU</option>
            <option value="BPU" <?=$ls_kode_segmen_bpu;?>>BPU</option>   
            <option value="EJAKON" <?=$ls_kode_segmen_ejakon;?>>EJAKON</option>   
						<option value="TKI" <?=$ls_kode_segmen_pmi;?>>PMI</option>            																						
				</select>
  		</div>
			<div class="clear"></div>
						
			<div class="form-row_kiri">
			<label style = "text-align:right;">Program &nbsp;&nbsp;&nbsp;</label>
				<select size="1" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>" tabindex="3" class="select_format" onchange="f_ajax_val_kd_prg();" style="width:350px;">
				<option value="">-- Pilih Program --</option>
				<? 
				$sql = "select kd_prg, nm_prg from sijstk.ms_prg where kd_prg <> 999 order by kd_prg";
				$DB->parse($sql);
				$DB->execute();
				while($row = $DB->nextrow())
				{
				echo "<option ";
				if ($row["KD_PRG"]==$ls_kd_prg && strlen($ls_kd_prg)==strlen($row["KD_PRG"])){ echo " selected"; }
				echo " value=\"".$row["KD_PRG"]."\">".$row["KD_PRG"]." - ".$row["NM_PRG"]."</option>";
				}
				?>
				</select>															 								
			</div>
			<div class="clear"></div>	

      <div class="form-row_kiri" id="div_tipe_token" style="display: none">
			  <label style="text-align:right;">&nbsp;</label>
        <input type="radio" name="rad_tipe_token" value="TOKEN" onchange="tipe_token_change()"><span>Token</span>
        <input type="radio" name="rad_tipe_token" value="NON_TOKEN" onchange="tipe_token_change()"><span>Non Token</span>
			</div>
			<div class="clear"></div>	

      <div class="form-row_kiri" id="div_bg_ul" style="display: none;">	
        <label style = "text-align:right;">Bilyet Giro (BG) UL &nbsp;&nbsp;&nbsp;&nbsp; </label>
					<input type="text" name="bg_ul" id="bg_ul" size="16" value=""> 
		  </div>
    	<div class="clear"></div>	


			<div class="clear"></div>
				
																																									
			<div class="form-row_kiri">	
      	<label style = "text-align:right;">Tgl Pembayaran &nbsp;&nbsp;</label>
					<input type="text" name="tgl1" id="tgl1" size="16" onblur="convert_date(tgl1);"  value="<?=$ld_tgl1;?>" tabindex="6"> 
					<input id="btn_tgl1" type="image" align="top" onclick="return showCalendar('tgl1', 'dd-mm-y');" src="../../images/calendar.gif" />
		  </div> 			
    	<div class="clear"></div>	

			
			</br></br>
			
			<!--		 
			<div class="form-row_kiri">
				<label>&nbsp;</label>	 			
				<? $ls_st_csv = isset($ls_st_csv) ? $ls_st_csv : "T"; ?>
				<input type="checkbox" id="st_csv" name="st_csv" class="cebox" onclick="fl_js_set_st_csv();" <?=$ls_st_csv=="Y" ||$ls_st_csv=="ON" ||$ls_st_csv=="on" ? "checked" : "";?>> <font  color="#009999">Format *.CSV</font> <br/>
		  </div> 			
    	<div class="clear"></div>		
			-->																																																																			
		</fieldset>

		<div id="buttonbox" style="width:650px; text-align:center;">
      <input type="submit" class="btn green" id="btncetak" name="btncetak" value="       CETAK LAPORAN       " title="Klik Untuk Cetak Laporan">	
		</div>

		<?
		if (isset($msg))		
		{
		?>
		<fieldset style="width:600px;">
		<?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
		<?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?
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