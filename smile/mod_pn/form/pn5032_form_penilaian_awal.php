<?php
session_start();
$pagetype = "form";
$gs_pagetitle = "PEREKAMAN INFORMASI PERENCANAAN";
require_once "../../includes/header_app_nosql.php";  
require_once "../../includes/conf_global.php"; 
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);   
                                                                        
$mid = $_REQUEST["mid"]; 
$tmp = $_REQUEST['tmp'];
//$gs_kode_segmen = "PU";
/* ============================================================================
Ket : Form ini digunakan untuk perekaman data iks
Hist: - 03/11/2017 : Update Form (Tim SIJSTK) - GW
-----------------------------------------------------------------------------*/
$sql = 	"select count(KODE_ASSESMENT) as JML from sijstk.pn_RTW_KODE_PENILAIAN_DETIL where KODE_TEMPLATE_PENILAIAN='{$tmp}'";
$DB->parse($sql);// echo $sql;
$DB->execute(); 
$ls_count = 0;
if($row = $DB->nextrow())
    $ls_count=$row['JML']; 

$ro_data = ($_REQUEST['parenttask']=='View'?true:false);
$sql = 	"select STATUS_RTW_KLAIM from sijstk.PN_RTW_KLAIM  where kode_rtw_klaim='{$ls_kode_rtw}'";
$DB->parse($sql);// echo $sql;
$DB->execute();
if($row = $DB->nextrow())
{
    if($row['STATUS_RTW_KLAIM']=='SELESAI') $ro_data=true;
}

$readonly = ($ro_data?"readonly":'');

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
?>
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
        <?php if (!$ro_data){?>
        <div style="float:left;"><div class="icon">
          <a id="btn_save" href="javascript:save_data(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
        </div></div>        
        <?php }?>
      <div style="float:left;"><div class="icon">
        <a id="btn_close" href="javascript:window.close();"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
      </div></div>
    </div>   
  </div>
</div>
<span style="color:#FF0000" id="ikserror"></span>
<div class="f_0">
    <form name="form_penilaian" id="form_penilaian" role="form" method="post">
    <input type="hidden"  name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden"name="kode_template" value="<?=$tmp;?>" />
    <fieldset><legend><b>Perekaman Informasi Perencanaan</b></legend>
        <table cellspacing="0" border="0" bordercolor="#C0C0C0" background-color= "#ffffff" width="100%">

        <tr>
            <th colspan="6"><hr style=" border-collapse: collapse;border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
            <tr>
                <th>No.</th>
                <td>Nama Attribut</th>
                <th>Nilai</th>
                <th>Komentar</th>
                <th>Bobot</th>
            </tr>
            <tr>
            <th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
                <?php 
                
                $sql = 	"select KODE_NILAI,NAMA_NILAI,NILAI  from pn_rtw_kode_nilai order by no_urut";
                $DB->parse($sql);// echo $sql;
                $DB->execute(); 
                $arr_nilai=array();
                $js_array="";
                while($row = $DB->nextrow())
                {
                    $arr_nilai[]= array("kode"=>$row['KODE_NILAI'],"nama"=>$row['NAMA_NILAI'],"nilai"=>$row['NILAI']);
                    $js_array .= "{kode:\"{$row['KODE_NILAI']}\",nilai:\"{$row['NILAI']}\"},";
                }
                $js_array="[" . $js_array ."]";
                $js_array = str_replace(",]","]",$js_array);
                $sql = 	"select A.*, b.KODE_NILAI,B.NILAI,B.KETERANGAN from
                (
                select ROWNUM NOURUT, KODE_ASSESMENT,NAMA_ASSESMENT,KODE_TEMPLATE_PENILAIAN,BOBOT from sijstk.pn_RTW_KODE_PENILAIAN_DETIL where KODE_TEMPLATE_PENILAIAN='{$tmp}'
                ) A left outer join pn_rtw_penilaian_detil B
                on a.kode_assesment=b.kode_assesment order by NOURUT";
                $DB->parse($sql); //echo $sql;
                $DB->execute(); 
                $ls_count = 0;
                while($row = $DB->nextrow())
                {
                    echo "<input type=\"hidden\" name=\"data_penilaian[{$row['NOURUT']}][kode_asessment]\" value=\"{$row['KODE_ASSESMENT']}\" />";
                    echo "<input type=\"hidden\" name=\"data_penilaian[{$row['NOURUT']}][kode_template]\" value=\"{$row['KODE_TEMPLATE_PENILAIAN']}\" />";
                    echo "<input type=\"hidden\" name=\"data_penilaian[{$row['NOURUT']}][kode_nilai1]\" value=\"{$row['KODE_NILAI']}\" />";
                    echo "<input type=\"hidden\" name=\"data_penilaian[{$row['NOURUT']}][nilai]\" value=\"{$row['NILAI']}\" />";
                    echo "<tr><td style=\" border-collapse: collapse;border-bottom:1px solid #F0F0F0;\">{$row['NOURUT']}</td><td style=\" border-collapse: collapse;border-bottom:1px solid #F0F0F0;\">{$row['NAMA_ASSESMENT']}</td>";
                    echo "<td style=\" border-collapse: collapse;border-bottom:1px solid #F0F0F0;\"><select  name=\"data_penilaian[{$row['NOURUT']}][kode_nilai]\">";
                    foreach($arr_nilai as $xitem)
                    {
                        if($xitem['kode']==$row['KODE_NILAI'])
                            echo "<option value=\"{$xitem['kode']}\" selected>{$xitem['nama']}</option>";
                        else
                        echo "<option value=\"{$xitem['kode']}\">{$xitem['nama']}</option>";
                    }
                    echo "</select></td>";
                    echo "<td style=\" border-collapse: collapse;border-bottom:1px solid #F0F0F0;\"><input type=\"text\" value=\"{$row['KETERANGAN']}\" name=\"data_penilaian[{$row['NOURUT']}][keterangan]\" style=\"width:325px;\" {$readonly} /></td>";
                    echo "<td style=\" border-collapse: collapse;border-bottom:1px solid #F0F0F0;text-align:center;\">{$row['BOBOT']} </td>";
                    echo "<td style=\" border-collapse: collapse;border-bottom:1px solid #F0F0F0;\"><span id=\"skor\"></td>";    
                    echo "</tr>";
                }
                ?>
                <tr>
            <th colspan="6"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>                    
                    
        </table>
    </fieldset>
    </form>
</div>
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
    <?php if (!$ro_data){?>
        <div style="float:left;"><div class="icon">
          <a id="btn_save" href="javascript:save_data(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
        </div></div>       
    <?php }?> 
      <div style="float:left;"><div class="icon">
        <a id="btn_close" href="javascript:window.close();"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
      </div></div>
    </div>   
  </div>
</div>
<?php if (!$ro_data){?>
<script type="text/javascript">
var arr_nilai=<?=$js_array;?>;
$(document).ready(function(){ 
    $('#btn_save').click(function() {
        if(confirm('Save data Asessment Penilaian?'))
            save_data();
    });
});
function save_data()
{
    $("#ikserror").html('' );
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5032_action_penilaian_asessment.php?"+Math.random(),
        data: $("#form_penilaian").serialize(),
        success: function(ajaxdata){
            preload(false);
            //console.log($('#formreg').serialize());   
            if($.trim(ajaxdata)!="") 
                $("#ikserror").html(ajaxdata);
            else{ 
                window.opener.LoadDataHasilPenilaian();
                window.close();
            }
        }
    });
}
</script>
<?php } ?>
<?php      
include "../../includes/footer_app_nosql.php";
?>