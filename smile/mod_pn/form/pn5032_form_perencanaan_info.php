<?php
session_start();
$pagetype = "form";
$gs_pagetitle = "PEREKAMAN INFORMASI PERENCANAAN";
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
.f_1{width:120px;text-align:right;float:left;clear:left;margin-bottom:2px;}
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
$ls_no_urut= isset($_GET['nourut'])?$_GET['nourut']:'';  
if($task=='EditInfo')
{
    $sql="SELECT NO_URUT,HAMBATAN, STRATEGI_PENYELESAIAN, to_char(TGL_MULAI_REHAB,'DD/MM/YYYY') TGL_MULAI_REHAB, 
    to_char(TGL_SELESAI_REHAB,'DD/MM/YYYY') TGL_SELESAI_REHAB,  ESTIMASI_BIAYA, KETERANGAN
    FROM PN_RTW_PERENCANAAN_DETIL
    where KODE_RTW_PERENCANAAN='PER{$ls_kode_rtw}' and NO_URUT='{$ls_no_urut}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    if($row = $DB->nextrow())
    {
        $ls_hambatan    = $row['HAMBATAN'];
        $ls_strategi   = $row['STRATEGI_PENYELESAIAN'];
        $ls_tgl_mulai   = $row['TGL_MULAI_REHAB'];
        $ls_tgl_selesai   = $row['TGL_SELESAI_REHAB'];        
        $ls_biaya   = $row['ESTIMASI_BIAYA'];
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
    <form name="formreg" id="formreg" role="form" method="post">
    <input type="hidden"  name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="dataid" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden"name="kode_perencanaan_info" value="<?=$ls_perencanaan_info;?>" />
    <input type="hidden" name="nourut" value="<?=$ls_no_urut;?>" />
    <fieldset><legend><b>Perekaman Informasi Perencanaan</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="strategi">Strategi Penyelesaian :</label></div>
                    <div class="f_2"><input type="text" id="strategi" maxlength="100" name="strategi" style="width:220px;"  value="<?=$ls_strategi;?>"  /></div>
                    <div class="f_1" ><label for="hambatan">Hambatan :</label></div>
                    <div class="f_2"><input type="text" id="hambatan" name="hambatan" style="width:220px;" value="<?=$ls_hambatan;?>" /></div>
                    <div class="f_1" ><label for="tgl_mulai">Perkiraan Mulai :</label></div>
                    <div class="f_2">
                    <input type="text" id="tgl_mulai" name="tgl_mulai" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl_mulai;?>" readonly/><input type="image" align="top" onclick="return showCalendar('tgl_mulai', 'dd-mm-y');" src="../../images/calendar.gif"/>
                    </div>
                    <div class="f_1" ><label for="tgl_selesai">Perkiraan Selesai :</label></div>
                    <div class="f_2">
                    <input type="text" id="tgl_selesai" name="tgl_selesai" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl_selesai;?>" readonly/><input type="image" align="top" onclick="return showCalendar('tgl_selesai', 'dd-mm-y');" src="../../images/calendar.gif"/>
                    </div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="biaya">Estimasi Biaya :</label></div>
                    <div class="f_2"><input type="text" id="biaya" name="biaya" style="width:140px;text-align:right;" value="<?=$ls_biaya;?>" /></div>
                    <!--
                    <div class="f_1" ><label for="status">Status :</label></div>
                    <div class="f_2">
                        <select id="status" name="status">
                            <option value="Y">Aktif</option>
                            <option value="T">Non Aktif</option>
                        </select>
                    </div>
                    -->
                    <div class="f_1" ><label for="keterangan">Keterangan :</label></div>   
                    <div class="f_2">
                        <textarea id="keterangan" name="keterangan" style="width:220px;" rows="2" ><?=$ls_keterangan;?></textarea>
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
        if(confirm('Save data informasi perencanaan?'))
            save_data();
    });
});
function save_data()
{
    $("#ikserror").html('' );
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5032_action_perencanaan.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){
            preload(false);
            console.log(ajaxdata);
            if($.trim(ajaxdata)!="") 
                $("#ikserror").html(ajaxdata);
            else{ 
                window.opener.get_PerencanaanInfoData('<?=$ls_kode_rtw;?>');
                window.close();
            }
        }
    });
}
</script>
<?php      
include "../../includes/footer_app_nosql.php";
?>