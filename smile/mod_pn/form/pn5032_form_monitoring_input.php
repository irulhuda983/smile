<?php
session_start();
$pagetype = "form";
$gs_pagetitle = "PEREKAMAN MONITORING JKK RTW";
require_once "../../includes/header_app_nosql.php";  
require_once "../../includes/conf_global.php"; 
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);   
                                                                        
$mid = $_REQUEST["mid"]; 
//$gs_kode_segmen = "PU";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data iks
Hist: - 03/11/2017 : Update Form (Tim SIJSTK) - GW
-----------------------------------------------------------------------------*/
?>
<?php /*****LOCAL JAVASCRIPTS*************************************/ ?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>  
<?php /*****end LOCAL JAVASCRIPTS********************************/ ?>
<?php /*****LOCAL CSS********************************************/ ?>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">  
  <style>
.f_0{border-top: 1px solid #8080FF; margin-top:0; clear:both;}
.f_0 form fieldset legend {
    font-size: 100%;
    font-weight: bold;
    color : #157fcc;
   font-family: verdana, arial, tahoma, sans-serif;       
  }
.f_0 input,textarea, select  {
        border: 1px solid #aaaaaa;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
      box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
      padding:2px;
      font-size:10px;
      font-family: verdana, arial, tahoma, sans-serif;      
  }
  .f_0 input:disabled,textarea:disabled, select:disabled  {
        color: #C5C4C4;
       background: #F5F5F5;
        border: 1px solid #aaaaaa;
      -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      padding:2px;
      font-size:10px;
      font-family: verdana, arial, tahoma, sans-serif;      
  }
  .f_0 input:readonly,textarea:readonly, select:readonly  {
        color: #3e3724;
       background: #F5F5F5;
        border: 1px solid #aaaaaa;
      -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      padding:2px;
      font-size:10px;
      font-family: verdana, arial, tahoma, sans-serif;      
  }
.f_1{width:150px;text-align:right;float:left;clear:left;margin-bottom:2px;}
.f_2{width:240px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;}   
</style>
<?php /*****end LOCAL CSS****************************************/ ?>
<?php /*****VALIDATOR & AJAX*************************************/ ?>
<script type="text/javascript" src="../../javascript/validator.js"></script>
<script type="text/javascript" src="../../javascript/ajax.js"></script>
<script type="text/javascript">
//Create validator object
var validator = new formValidator();
var ajax = new sack();
var dataid ='';
window.dataid='';
</script>
<?php /*****end VALIDATOR & AJAX*********************************/ ?>

<?php /*****LOCAL GET/POST PARAMETER*****************************/
$schema='sijstk';
$task= isset($_GET['task'])?$_GET['task']:'';
$ls_kode_rtw = isset($_GET['dataid'])?$_GET['dataid']:'';  
$ls_perencanaan_info = isset($_GET['pinfo'])?$_GET['pinfo']:'';  
$ls_no_urut = isset($_GET['nourut'])?$_GET['nourut']:''; 

if($task=='Edit')
{
    $sql="SELECT NO_URUT,
    to_char(TGL_KUNJUNGAN,'DD/MM/YYYY') TGL_KUNJUNGAN,
    DIS_FISIK_FUNGSI,
    DIS_FISIK_ANATOMIS,
    GANGGUAN_PSIKIS,
    TIN_MEDIS_OPERATIF,
    TIN_MEDIS_NONOPERATIF,
    REHAB_MEDIS,
    REHAB_VOCATIONAL,
    REHAB_MENTALPSIKIS,
    DIAGNOSA_AWAL,
    DIAGNOSA_AKHIR,
    TENAGA_MEDIS_FASKES,
    KETERANGAN
FROM PN_RTW_MONITORING
    where KODE_RTW_KLAIM='{$ls_kode_rtw}' and NO_URUT='{$ls_no_urut}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    if($row = $DB->nextrow())
    {
        $ls_tgl_kunjungan    = $row['TGL_KUNJUNGAN'];
        $ls_dis_fisik_fungsi   = $row['DIS_FISIK_FUNGSI'];
        $ls_dis_fisik_anatomis   = $row['DIS_FISIK_ANATOMIS'];
        $ls_gangguan_psikis    = $row['GANGGUAN_PSIKIS'];
        $ls_tin_medis_operatif    = $row['TIN_MEDIS_OPERATIF'];
        $ls_tin_medis_nonoperatif   = $row['TIN_MEDIS_NONOPERATIF'];
        $ls_rehab_medis    = $row['REHAB_MEDIS'];
        $ls_rehab_vocational    = $row['REHAB_VOCATIONAL'];
        $ls_rehab_mental_psikis   = $row['REHAB_MENTALPSIKIS'];
        $ls_diagnosa_awal    = $row['DIAGNOSA_AWAL'];
        $ls_diagnosa_akhir    = $row['DIAGNOSA_AKHIR'];
        $ls_tng_medis   = $row['TENAGA_MEDIS_FASKES'];
        $ls_keterangan  = $row['KETERANGAN'];
    }
}
?>
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
        <div style="float:left;"><div class="icon">
          <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
        </div></div>        
      <div style="float:left;"><div class="icon">
        <a id="btn_close" href="javascript:window.close();"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
      </div></div>
    </div>   
  </div>
</div>
<span style="color:#FF0000" id="ikserror"></span>
<div class="f_0">
    <form name="form_monitoring" id="form_monitoring" role="form" method="post">
    <input type="hidden"  name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden"name="kode_info_diagnosa" value="<?=$ls_info_diagnosa;?>" />
    <input type="hidden"name="nourut" value="<?=$ls_no_urut;?>" />
    <fieldset><legend><b>Perekaman Informasi Perencanaan</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="tgl_kunjungan">Tanggal Kunjungan :</label></div>
                    <div class="f_2">
                        <input type="text" id="tgl_kunjungan" name="tgl_kunjungan" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl_kunjungan;?>" readonly/><input type="image" align="top" onclick="return showCalendar('tgl_kunjungan', 'dd-mm-y');" src="../../images/calendar.gif"/>
                    </div>
                    <div class="f_1" ><label for="dis_fisik_fungsi">Disabilitas Fisik(Fungsi) :</label></div>
                    <div class="f_2"><input type="text" id="dis_fisik_fungsi" name="dis_fisik_fungsi" style="width:220px;"  value="<?=$ls_dis_fisik_fungsi;?>"  /></div>
                    <div class="f_1" ><label for="dis_fisik_anatomi">Disabilas Fisik(Anatomis) :</label></div>
                    <div class="f_2"><input type="text" id="dis_fisik_anatomi" name="dis_fisik_anatomi" style="width:220px;"  value="<?=$ls_dis_fisik_anatomis;?>"  /></div>
                    <div class="f_1" ><label for="gangguan_psikis">Gangguan Psikis :</label></div>
                    <div class="f_2"><input type="text" id="gangguan_psikis" name="gangguan_psikis" style="width:220px;"  value="<?=$ls_gangguan_psikis;?>"  /></div>
                    <div class="f_1" ><label for="tind_medis_operatif">Tindakan Medis Operatif :</label></div>
                    <div class="f_2"><input type="text" id="tind_medis_operatif" name="tind_medis_operatif" style="width:220px;"  value="<?=$ls_tin_medis_operatif;?>"  /></div>
                    <div class="f_1" ><label for="tind_medis_nonoperatif">Tindakan Medis Non Operatif :</label></div>
                    <div class="f_2"><input type="text" id="tind_medis_nonoperatif" name="tind_medis_nonoperatif" style="width:220px;"  value="<?=$ls_tin_medis_nonoperatif;?>"  /></div>
                    <div class="f_1" ><label for="rehab_medis">Rehabilitasi Medis :</label></div>
                    <div class="f_2"><input type="text" id="rehab_medis" name="rehab_medis" style="width:220px;"  value="<?=$ls_rehab_medis;?>"  /></div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="rehab_vocational">Rehabilitasi Vocational :</label></div>
                    <div class="f_2"><input type="text" id="rehab_vocational" name="rehab_vocational" style="width:220px;"  value="<?=$ls_rehab_vocational;?>"  /></div>
                    <div class="f_1" ><label for="rehab_mentalpsikis">Rehabilitasi Mental Psikis :</label></div>
                    <div class="f_2"><input type="text" id="rehab_mentalpsikis" name="rehab_mentalpsikis" style="width:220px;"  value="<?=$ls_rehab_mental_psikis;?>"  /></div>
                    <div class="f_1" ><label for="diagnosa_awal">Diagnosa Awal :</label></div>
                    <div class="f_2"><input type="text" id="diagnosa_awal" name="diagnosa_awal" style="width:220px;"  value="<?=$ls_diagnosa_awal;?>"  /></div>
                    <div class="f_1" ><label for="diagnosa_akhir">Diagnosa Akhir :</label></div>
                    <div class="f_2"><input type="text" id="diagnosa_akhir" name="diagnosa_akhir" style="width:220px;"  value="<?=$ls_diagnosa_akhir;?>"  /></div>
                    <div class="f_1" ><label for="tenaga_medis_faskes">Tenaga Medis Faskes :</label></div>
                    <div class="f_2"><input type="text" id="tenaga_medis_faskes" name="tenaga_medis_faskes" style="width:220px;"  value="<?=$ls_tng_medis;?>"  /></div>
                    <div class="f_1" ><label for="keterangan">Keterangan :</label></div>   
                    <div class="f_2">
                        <textarea id="keterangan" name="keterangan" style="width:220px;" rows="4" ><?=$ls_keterangan;?></textarea>
                    </div>
                </td>
            </tr>
        </table>
    </fieldset>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function(){ 
    $('#btn_save').click(function() {
        if(confirm('Save data monitoring?'))
            save_data();
    });
});
function save_data()
{
    $("#ikserror").html('' );
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5032_action_monitoring.php?"+Math.random(),
        data: $("#form_monitoring").serialize(),
        success: function(ajaxdata){
            preload(false);
            //console.log($('#formreg').serialize());   
            if($.trim(ajaxdata)!="") 
                $("#ikserror").html(ajaxdata);
            else{ 
                window.opener.ReloadDataMonitoring();
                window.close();
            }
        }
    });
}
</script>
<?php      
include "../../includes/footer_app_nosql.php";
?>