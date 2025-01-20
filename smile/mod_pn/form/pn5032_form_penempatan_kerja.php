<?php
/*****LOAD DATA INFORMASI DASAR*************************************/
$ls_bekerja							    = 'checked';
$ls_nobekerja							= '';
$ls_sp_sb				                = '';
$ls_sp_sb_i			 		            = '';
$ls_sp_bb				                = '';
$ls_sp_bb_i			 		            = '';
$ls_bp_sb				                = '';
$ls_bp_sb_i			 		            = '';
$ls_bp_bb				                = '';
$ls_bp_bb_i			 		            = '';
$ls_wiraswasta				            = '';
$ls_wiraswasta_i			 		    = '';
if($task=="View" || $task=="Edit")
{
    $sql = "SELECT * FROM PN_RTW_PENEMPATAN
    where KODE_RTW_KLAIM='{$ls_kode_rtw}'"; 
    $DB->parse($sql);//echo $sql;
    $DB->execute();
    $kode_kantor="";

    $ls_check_bekerja="";
    if($row = $DB->nextrow())
    {
        $ls_bekerja         = $row['STATUS_PASCA_PENGOBATAN'];
        $ls_u_bekerja       = $row['KODE_PASCA_PENGOBATAN'];
        $ls_informasi       = $row['KETERANGAN'];
        $ls_check_bekerja   = $row['STATUS_PASCA_PENGOBATAN']=='Y'?'checked':'';
        $ls_check_ubekerja  = $row['STATUS_PASCA_PENGOBATAN']!='Y'?'checked':'';
    }
}  //   echo $sql;
/*****end LOAD DATA*********************************/ 
?>
<form id="form_penempatan_kerja">
    <input type="hidden" id="pk_kode_rtw" name="pk_kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden" name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="noform" value="4" />
    <input type="hidden" id="kode_rtw" name="kode_rtw" value="<?=$ls_kode_rtw;?>"/>
    <fieldset><legend><b>Penempatan Kerja</b></legend>
    <div class="f_1">Pasca Pengobatan :</div>
    <div class="f_2">
        <input type="radio" value="T" id="pk_nowork" name="pk_work" <?=$ls_check_ubekerja;?>  <?=$global_readonly;?>/> <label for="pk_nowork">Tidak Bekerja</label> &nbsp; &nbsp;
        <input type="radio" value="Y" id="pk_work" name="pk_work" <?=$ls_check_bekerja;?>  <?=$global_readonly;?>/> <label for="pk_work">Bekerja
    </div>
    <div style="margin-top:5px;padding:0;clear:both;<?php echo $ls_check_bekerja==''?'display:none;':'';?>" id="pk_div">
        <br />
        <div class="f_1"><label for="ubekerja">Untuk Yang Bekerja :</label></div>
        <div class="f_2"><select id="ubekerja" name="ubekerja"  <?=$global_readonly;?>></select></div>
        <div class="f_1" ><label for="infotbh">Informasi Tambahan :</label></div>
        <div class="f_2"><input type="text" id="infotbh" name="infotbh" maxlength="100" value="<?=$ls_informasi;?>" style="width:240px;background-color:#ffffff;"  <?=$global_readonly;?>/></div>
    </div>
    </fieldset>
   </form> 
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
function savePenempatanKerja()
{
    if(confirm("Save data Penempatan Kerja?"))
    {
        saveData('../ajax/pn5032_action_penempatan_kerja.php?',$("#form_penempatan_kerja").serialize(),'pn5032.php?task=<?=$task;?>&dataid=<?=$ls_kode_rtw;?>&mid=<?=$mid;?>&noform=4')
    }
}
function get_PenempatanKerja_pk(par_data){$("#form_penempatan_kerja select[name=ubekerja]").html(par_data);}
$(document).ready(function(){ 
    /*
    $("#form_id select[name=kode_jenis]").change(function(){ 
        lov_subData('subJenis','getSubJenis',$(this).val(),0);
    });*/
    lov_subData('PenempatanKerja','getMSLookup','RTWBEKERJA','<?=$ls_u_bekerja;?>');
    $('#form_penempatan_kerja input[type=radio]').change(function() {       
        if(this.value=='T') $("#pk_div").hide()
        else  $("#pk_div").show()
    });
});
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>