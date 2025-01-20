<?php
session_start();
$pagetype = "form";
$gs_pagetitle = "PEREKAMAN DATA IKS";
require_once "../../includes/header_app_nosql.php";  
require_once "../../includes/conf_global.php"; 
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);   
$php_file_name="pn2401";                                                                        
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
        border: 1px solid #dddddd;
      -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
      box-shadow: inset 0 1px 1px rgba(0,0,0,0.056);
      padding:2px;
      font-size:10px;
      font-family: verdana, arial, tahoma, sans-serif;      
  }
  .f_0 input:disabled,textarea:disabled, select:disabled  {
        color: #C5C4C4;
       background: #F5F5F5;
        border: 1px solid #dddddd;
      -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      padding:2px;
      font-size:10px;
      font-family: verdana, arial, tahoma, sans-serif;      
  }
  .f_0 input:readonly,textarea:readonly, select:readonly  {
        color: #3e3724;
       background: #F5F5F5;
        border: 1px solid #dddddd;
      -webkit-box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      box-shadow: inset 0 1px 1px rgba(255,255,255,0.556);
      padding:2px;
      font-size:10px;
      font-family: verdana, arial, tahoma, sans-serif;      
  }
.f_1{width:120px;text-align:right;float:left;clear:left;margin-bottom:2px;}
.f_2{width:240px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;}   
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr:hover {background-color:#e0e0FF;cursor:pointer;} 
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
$task_code=isset($_REQUEST['task'])?$_REQUEST['task']:'';
$ls_kode_iks=isset($_REQUEST['iks'])?$_REQUEST['iks']:'';
$ls_kode_faskes=isset($_REQUEST['dataid'])?$_REQUEST['dataid']:'';

$ls_no_iks          = '';
$ls_status_iks      = '';
$ls_kode_adendum    = '';
$ls_tgl_awal        = '';
$ls_tgl_akhir       ='';

$ls_tgl_rekam       ='';
$ls_petugas_rekam   = '';
$ls_tgl_submit      ='';
$ls_petugas_submit  =   '';
$ls_na              = '';
$ls_tgl_na          = '';
$ls_alasan_na       ='';
$ls_petugas_approve ='';
$ls_tgl_approve     ='';
$ls_alasan_approve  ='';
if($task_code=="Edit" || $task_code=="Delete" || $task_code=="Submit" || $task_code=="View" || $task_code=="Adendum")
{
    $sql = "SELECT KODE_FASKES,KODE_IKS,KODE_TIPE,NO_IKS,TO_CHAR(TGL_AWAL_IKS,'DD/MM/YYYY')TGL_AWAL_IKS,TO_CHAR(TGL_AKHIR_IKS,'DD/MM/YYYY')TGL_AKHIR_IKS,PETUGAS_APPROVAL,TO_CHAR(TGL_APPROVAL,'DD/MM/YYYY')TGL_APPROVAL,ALASAN_APPROVAL,
    PETUGAS_APPROVAL1,TO_CHAR(TGL_APPROVAL1,'DD/MM/YYYY')TGL_APPROVAL1,ALASAN_APPROVAL1,
    STATUS_NA,TO_CHAR(TGL_NA,'DD/MM/YYYY')TGL_NA,ALASAN_NA,KODE_ADDENDUM,TO_CHAR(TGL_SUBMIT,'DD/MM/YYYY')TGL_SUBMIT,PETUGAS_SUBMIT,TO_CHAR(TGL_REKAM,'DD/MM/YYYY')TGL_REKAM,PETUGAS_REKAM,
       TO_CHAR(TGL_UBAH,'DD/MM/YYYY')TGL_UBAH,PETUGAS_UBAH,
       case 
            when KODE_STATUS_IKS = 1 then 'Baru'
            when KODE_STATUS_IKS = 2 then 'Meminta Persetujuan'
            when KODE_STATUS_IKS = 3 then 'Disetujui'
            when KODE_STATUS_IKS = 4 then 'Ditolak'
            when KODE_STATUS_IKS = 5 then 'Non Aktif'
            when KODE_STATUS_IKS = 6 then 'Dibatalkan'
            else ''
       end  KODE_STATUS_IKS,
       case 
            when KODE_STATUS_IKS1 = 1 then 'Baru'
            when KODE_STATUS_IKS1 = 2 then 'Meminta Persetujuan'
            when KODE_STATUS_IKS1 = 3 then 'Disetujui'
            when KODE_STATUS_IKS1 = 4 then 'Ditolak'
            when KODE_STATUS_IKS1 = 5 then 'Non Aktif'
            when KODE_STATUS_IKS1 = 6 then 'Dibatalkan'
            else ''
       end  KODE_STATUS_IKS2
       FROM {$schema}.TC_IKS A
       WHERE KODE_IKS='{$ls_kode_iks}' and KODE_FASKES='{$ls_kode_faskes}'";
    $DB->parse($sql);  //echo $sql;
    $DB->execute();
    if($row = $DB->nextrow())
    {
        
        $ls_no_iks          = $row['NO_IKS'];
        $ls_status_iks1      = $row['KODE_STATUS_IKS'];
        $ls_status_iks2      = $row['KODE_STATUS_IKS2'];
        $ls_kode_adendum    = $row['KODE_ADDENDUM'];
        $ls_tgl_awal        = $row['TGL_AWAL_IKS'];
        $ls_tgl_akhir       = $row['TGL_AKHIR_IKS'];

        $ls_tgl_rekam       = $row['TGL_REKAM'];
        $ls_petugas_rekam   = $row['PETUGAS_REKAM'];
        $ls_tgl_submit      = $row['TGL_SUBMIT'];
        $ls_petugas_submit  = $row['PETUGAS_SUBMIT'];
        $ls_na              = $row['STATUS_NA'];
        $ls_tgl_na          = $row['TGL_NA'];
        $ls_alasan_na       = $row['ALASAN_NA'];
        $ls_petugas_approve = $row['PETUGAS_APPROVAL'];
        $ls_tgl_approve     = $row['TGL_APPROVAL'];
        $ls_alasan_approve  = $row['ALASAN_APPROVAL'];
        $ls_petugas_approve1 = $row['PETUGAS_APPROVAL1'];
        $ls_tgl_approve1     = $row['TGL_APPROVAL1'];
        $ls_alasan_approve1  = $row['ALASAN_APPROVAL1'];
    }      
}
$ls_form_act = ($task_code=='Submit')?'submitIKS':($task_code=='Adendum'?'adendumIKS':($task_code=='Delete'?'deleteIKS':'saveIKS'));
$lsr_no_iks = ($task_code=='Adendum')?'readonly':"";
$lsr_tgl_awal = ($task_code=='View')?'readonly':"";
$lsr_tgl_akhir = ($task_code=='View')?'readonly':"";

?>
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
        <div style="float:left;"><div class="icon">
          <?php if($task_code=="Submit" ||  $task_code=="Adendum"){?>
          <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
          <?php } elseif($task_code=="Delete") {?>
            <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Delete</a>
          <?php } else {?>
          <a id="btn_save" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
          <?php }?>
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
    <input type="hidden" id="formregact" name="formregact" value="saveIKS" />
    <input type="hidden" id="kode_faskes" name="kode_faskes" value="<?=$ls_kode_faskes;?>" />
    <input type="hidden" id="kode_iks" name="kode_iks" value="<?=$ls_kode_iks;?>" />
    <input type="hidden" id="taskno" name="taskno" value="<?=$task_code;?>" />
    <fieldset><legend><b><?php echo ($task_code=="Submit")?"Submit data IKS?":($task_code=="Adendum"?"Adendum terhadap data IKS":"Perekaman Data IKS?");?></b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="no_iks">No IKS* :</label></div>
                    <div class="f_2"><input type="text" id="no_iks" maxlength="100" name="no_iks" style="width:220px;background-color:<?php echo ($lsr_no_iks=='readonly')?"#e9e9e9":"#ffff99";?>;" tabindex="1" value="<?=$ls_no_iks;?>" <?=$lsr_no_iks;?>  /></div>
                    <div class="f_1" ><label for="kode_adendum">Kode Adendum :</label></div>
                    <div class="f_2"><input type="text" id="kode_adendum" name="kode_adendum" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_kode_adendum;?>" readonly /></div>
                    <div class="f_1" ><label for="nama_status">Status Approval 1 :</label></div>
                    <div class="f_2"><input type="text" id="nama_status" name="nama_status" style="width:140px;background-color:#E9E7FA;" value="<?=$ls_status_iks1;?>" readonly /></div>
                    <div class="f_1" ><label for="petugas_approval">Petugas Approval 1 :</label></div>
                    <div class="f_2"><input type="text" id="petugas_approval" name="petugas_approval" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_petugas_approve;?>" readonly /></div>
                    <div class="f_1" ><label for="tgl_approval">Tanggal Approval 1 :</label></div>
                    <div class="f_2"><input type="text" id="tgl_approval" name="tgl_approval" style="width:100px;background-color:#e9e9e9;" value="<?=$ls_tgl_approve;?>" readonly /></div>
                    <div class="f_1" ><label for="alasan_approval">Keterangan 1 :</label></div>   
                    <div class="f_2"><textarea id="alasan_approval" name="alasan_approval" style="width:220px;background-color:#e9e9e9;" rows="2" tabindex="25"><?=$ls_alasan_approve;?></textarea></div>
                    <div class="f_1" ><label for="nama_status1">Status Approval 2 :</label></div>
                    <div class="f_2"><input type="text" id="nama_status1" name="nama_status1" style="width:140px;background-color:#E9E7FA;" value="<?=$ls_status_iks2;?>" readonly /></div>   
                    <div class="f_1" ><label for="petugas_approval1">Petugas Approval 2 :</label></div>
                    <div class="f_2"><input type="text" id="petugas_approval1" name="petugas_approval1" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_petugas_approve1;?>" readonly /></div>
                    <div class="f_1" ><label for="tgl_approval1">Tanggal Approval 2 :</label></div>
                    <div class="f_2"><input type="text" id="tgl_approval1" name="tgl_approval1" style="width:100px;background-color:#e9e9e9;" value="<?=$ls_tgl_approve1;?>" readonly /></div>
                    <div class="f_1" ><label for="alasan_approval1">Keterangan 2 :</label></div>   
                    <div class="f_2"><textarea id="alasan_approval" name="alasan_approval1" style="width:220px;background-color:#e9e9e9;" rows="2" tabindex="25"><?=$ls_alasan_approve1;?></textarea></div>                 
                    <div class="f_1">&nbsp;</div>
                    <div class="f_1">&nbsp;</div>
                    
                    
                    
                </td>
                <td valign="top">
                    
                    <div class="f_1" ><label for="tgl_awal">Tanggal Awal IKS * :</label></div>
                    <div class="f_2"><input type="text" id="tgl_awal" name="tgl_awal" style="width:100px;background-color:<?php echo ($lsr_tgl_awal=='readonly')?"#e9e9e9":"#ffff99";?>;" tabindex="2" value="<?=$ls_tgl_awal;?>" readonly/>
                    <?php if($task_code!='View'){ ?> 
                    <input type="image" align="top" onclick="return showCalendar('tgl_awal', 'dd-mm-y');" src="../../images/calendar.gif" tabindex="3"/>
                    <?php } ?>
                    </div>
                    <div class="f_1" ><label for="tgl_akhir">Tanggal Akhir IKS * :</label></div>
                    <div class="f_2"><input type="text" id="tgl_akhir" name="tgl_akhir" style="width:100px;background-color:<?php echo($lsr_tgl_akhir=='readonly')?"#e9e9e9":"#ffff99";?>;" tabindex="2" value="<?=$ls_tgl_akhir;?>" readonly/>
                    <?php if($task_code!='View' ){ ?> 
                    <input type="image" align="top" onclick="return showCalendar('tgl_akhir', 'dd-mm-y');" src="../../images/calendar.gif" tabindex="3"/>
                    <?php }?>
                    </div>
                    <?php if($task_code=='Adendum'){?>
                    <div align="center" style="clear:both;color:#570D0D">
                      *Tgl Awal Adendum >= tgl akhir IKS yang di akan Adendum.
                    </div>
                    <?php }else{?>
                    <div class="f_1">&nbsp;</div>
                    <?php }?>
                    <div class="f_1">&nbsp;</div>
                    <!--<div class="f_1" ><label for="petugas_submit">Petugas Submit :</label></div>
                    <div class="f_2"><input type="text" id="petugas_submit" name="petugas_submit" style="width:140px;background-color:#e9e9e9;" value="<?=$ls_petugas_submit;?>" readonly /></div>
                    <div class="f_1" ><label for="tgl_submit">Tanggal Submit :</label></div>
                    <div class="f_2"><input type="text" id="tgl_submit" name="tgl_submit" style="width:100px;background-color:#e9e9e9;" value="<?=$ls_tgl_submit;?>" readonly /></div>-->
                    <div class="f_1" ><label for="petugas_rekam">Petugas Rekam :</label></div>
                    <div class="f_2"><input type="text" id="petugas_rekam" name="petugas_rekam" style="width:140px;background-color:#e9e9e9;" value="<?=$ls_petugas_rekam;?>" readonly /></div>
                    <div class="f_1" ><label for="tgl_rekam">Tanggal Rekam :</label></div>
                    <div class="f_2"><input type="text" id="tgl_rekam" name="tgl_rekam" style="width:100px;background-color:#e9e9e9;" value="<?=$ls_tgl_rekam;?>" readonly /></div>
                    <div class="f_1">&nbsp;</div>
                    <div class="f_1" ><label for="status_na">Non Aktif :</label></div>
                    <div class="f_2"><input type="text" id="status_na" name="status_na" style="width:40px;background-color:#e9e9e9;" value="<?=$ls_na;?>" readonly /></div>
                    <div class="f_1" ><label for="tgl_na">Tanggal Non Aktif :</label></div>
                    <div class="f_2"><input type="text" id="tgl_na" name="tgl_na" style="width:100px;background-color:#e9e9e9;" value="<?=$ls_tgl_na;?>" readonly /></div>
                    <div class="f_1" ><label for="alasan_na">Alasan Non Aktif :</label></div>   
                    <div class="f_2"><textarea id="alasan_na" name="alasan_na" style="width:220px;background-color:#e9e9e9;" rows="2" tabindex="25"><?=$ls_alasan_na;?></textarea></div>
                    
                </td>
            </tr>
        </table>
    </fieldset>
    <div style="text-align:center"><input type="checkbox" id="kode_status" name="kode_status" value="2" checked    /><label for="kode_status">Meminta Persetujuan</label></div>
    </form>
</div>
<script type="text/javascript">
$(document).ready(function(){ 
    $('#btn_save').click(function() {
        <?php if($task_code=="Submit"){?>
        if(confirm('Submit data IKS?'))
        <?php } else if($task_code=="Adendum"){?>
        if(confirm('Adendum data IKS?'))
        <?php } else if($task_code=="Delete"){?>
        if(confirm('Delete data IKS?'))
        <?php } else { ?>
        if(confirm('Save data IKS?'))
        <?php } ?> 
            save_IKS();
    });
});
function save_IKS()
{
    $("#ikserror").html('' );
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){
            preload(false);
            console.log(ajaxdata)   ;   
            if($.trim(ajaxdata)!="") 
                $("#ikserror").html(ajaxdata);
            else{ 
                window.opener.RefreshIKS();
                window.close();
            }
        }
    });
}
</script>
<?php      
include "../../includes/footer_app_nosql.php";
?>