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
$ls_no_urut = isset($_GET['nourut'])?$_GET['nourut']:''; 

if($task=='Edit')
{
    $sql="SELECT KODE_TIPE_EVALUASI,
       KODE_KUSIONER,
       to_char(TGL_DIPERBAHARUI,'DD/MM/YYYY') TGL_DIPERBAHARUI,
       KETERANGAN_JAWABAN
  FROM PN_RTW_EVALUASI
    where KODE_RTW_KLAIM='{$ls_kode_rtw}' and NO_URUT='{$ls_no_urut}'";
    $DB->parse($sql);
    $DB->execute();//echo $sql;
    if($row = $DB->nextrow())
    {
        $ls_tgl_diperbaharui    = $row['TGL_DIPERBAHARUI'];
        $ls_kode_tipe   = $row['KODE_TIPE_EVALUASI'];
        $ls_kode_kuis   = $row['KODE_KUSIONER'];
        $ls_keterangan  = $row['KETERANGAN_JAWABAN'];
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
    <form name="form_evaluasi" id="form_evaluasi" role="form" method="post">
    <input type="hidden"  name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden"name="kode_info_diagnosa" value="<?=$ls_info_diagnosa;?>" />
    <input type="hidden"name="nourut" value="<?=$ls_no_urut;?>" />
    <fieldset><legend><b>Perekaman Informasi Evaluasi</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="eval_tgl1">Tanggal Diperbaharui :</label></div>
                    <div class="f_2">
                    <input type="text" id="eval_tgl1" name="eval_tgl1" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl_diperbaharui;?>" readonly/><input type="image" align="top" onclick="return showCalendar('eval_tgl1', 'dd-mm-y');" src="../../images/calendar.gif"/>
                    </div>
                    <div class="f_1" ><label for="eval_tipe">Tipe Evaluasi :</label></div>
                    <div class="f_2">
                        <select id="eval_tipe" name="eval_tipe" style="width:250px;">
                        <?php
                        $sql = "select KODE_TIPE_EVALUASI,NAMA_TIPE_EVALUASI from sijstk.pn_rtw_kode_evaluasi order by no_urut"; 
                        $DB->parse($sql);
                        $DB->execute();
                        $kode_kantor="";
                        echo "<option value=\"\"></option>";
                        while($row = $DB->nextrow())
                        {
                            if($row['KODE_TIPE_EVALUASI']==$ls_kode_tipe)
                                echo "<option value=\"{$row['KODE_TIPE_EVALUASI']}\" selected>{$row['NAMA_TIPE_EVALUASI']}</option>";
                            else
                            echo "<option value=\"{$row['KODE_TIPE_EVALUASI']}\">{$row['NAMA_TIPE_EVALUASI']}</option>";
                        }
                        ?>
                        </select>
                    </div>
                    <div class="f_1" ><label for="eval_tipe_detil">Kuisioner :</label></div>
                    <div class="f_2">
                        <select id="eval_tipe_detil" name="eval_tipe_detil"  style="width:250px;">
                        </select>
                    </div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="eval_keterangan">Keterangan :</label></div>   
                    <div class="f_2">
                        <textarea id="eval_keterangan" name="eval_keterangan" style="width:220px;" rows="3" ><?=$ls_keterangan;?></textarea>
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
        if(confirm('Save data informasi evaluasi'))
            save_data();
    });
    $('#eval_tipe').change(function() {
        lov_subData('evaluasi','getEvaluasi',this.value);
    });
    lov_subData('evaluasi','getEvaluasi','<?=$ls_kode_tipe;?>','<?=$ls_kode_kuis;?>');
});
function save_data()
{
    $("#ikserror").html('' );
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5032_action_evaluasi.php?"+Math.random(),
        data: $("#form_evaluasi").serialize(),
        success: function(ajaxdata){
            preload(false);
            //console.log($('#formreg').serialize());   
            if($.trim(ajaxdata)!="") 
                $("#ikserror").html(ajaxdata);
            else{ 
                window.opener.ReloadDataEvaluation('<?=$ls_kode_rtw;?>');
                window.close();
            }
        }
    });
}
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2; 
    preload(true);
    $.ajax({
        type: 'GET',
        url: "../ajax/pn5032_lov.php",
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){ console.log(ajaxdata);
            preload(false);
            if(par_callFunc=='evaluasi') get_evaluasi_detil(ajaxdata);
        }
    });
}
function get_evaluasi_detil(par_data){$("#form_evaluasi select[name=eval_tipe_detil]").html(par_data);}
</script>
<?php      
include "../../includes/footer_app_nosql.php";
?>