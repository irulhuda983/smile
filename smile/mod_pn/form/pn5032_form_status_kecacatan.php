<?php 
/*****LOAD DATA *************************************/
$ls_impairement					        = '';
$ls_endanger							= '';
$ls_toleransi							= '';
$ls_kelaikan							= '';
$ls_startingwork					    = '';
$ls_startingwork_drop					= '';
$ls_work_medic_problem					= '';
$ls_work_comunity						= '';
$ls_nowork_temp							= '';
$ls_work_other						    = '';
$ls_work_other_i			 		    = '';
$ls_ref_kerja_i							= '';
$ls_ref_prs_i						    = '';
$ls_keterangan				            = '';
if($task=="View" || $task=="Edit")
{
    $sql = 	"select * from sijstk.pn_rtw_kecacatan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);// echo $sql;
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_impairement					        = $row['KODE_STATUS_KECACATAN'];
        $ls_impairement_lain    		        = $row['STATUS_KECACATAN_LAINNYA'];
        $ls_endanger							= $row['FLG_BAHAYA']=='Y'?'checked':'';
        $ls_toleransi							= $row['FLG_TOLERANSI']=='Y'?'checked':'';
        $ls_kelaikan							= $row['FLG_KELAYAKAN']=='Y'?'checked':'';
        $ls_startingwork					    = $row['FLG_PEKERJAANSEMULA']=='Y'?'checked':'';
        $ls_startingwork_drop					= $row['FLG_EFEKTIVITAS']=='Y'?'checked':'';
        $ls_work_medic_problem					= $row['FLG_KONDISIMEDIS']=='Y'?'checked':'';
        $ls_work_comunity						= $row['FLG_RESIKO']=='Y'?'checked':'';
        $ls_nowork_temp							= $row['FLG_FISIKMENTAL']=='Y'?'checked':'';
        $ls_work_other						    = $row['FLG_LAINNYA']=='Y'?'checked':'';
        $ls_work_other_i			 		    = strtoupper($row['KETERANGAN_FLG_LAINNYA']);
        $ls_ref_kerja_i							= $row['REF_TK'];
        $ls_ref_prs_i						    = $row['REF_PERUSAHAAN'];
        $ls_keterangan				            = strtoupper($row['KETERANGAN']);
    }
    
}  //   echo $sql;
/*****end LOAD DATA*********************************/ 
?>
<form id="form_status_kecacatan">
    <input type="hidden" id="pk_kode_rtw" name="pk_kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden" name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="noform" value="7" />
    <fieldset><legend><b>Status Kecacatan</b></legend>
        <table cellspacing="1" width="100%">
            <tr>
                <td align="right" width="400"><label for="sk_impairement">Impairement, Disability dan atau handicap : </label></td>
                <td><select id="sk_impairement" name="sk_impairement" <?=$global_readonly;?>></select></td>
            </tr>
            <tr id="tr_sklain" style="<?php echo $ls_impairement=='RTWST04'?'':'display:none';?>">
                <td></td>
                <td><input type="text" id="sk_impairement_lain" name="sk_impairement_lain" value="<?=$ls_impairement;?>" style="width:275px;" <?=$global_readonly;?>/></td>
            </tr>
            <tr><td colspan="2"><b>Deskripsi Masing-Masing<b><hr /></td></tr>
            <tr>
                <td align="right"><label for="sk_endanger">Kemungkinan membahayakan diri sendiri, rekan kerja atau lingkungan : </label></td>
                <td><input type="checkbox" id="sk_endanger" name="sk_endanger" value="Y" <?=$ls_endanger;?>  <?=$global_readonly;?>/></td>
            </tr>
            <tr>    
                <td align="right"><label for="sk_toleransi">Toleransi pihak atasan atau relan kerja : </label></td>
                <td><input type="checkbox" id="sk_toleransi" name="sk_toleransi" value="Y" <?=$ls_toleransi;?>  <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_kelaikan">Status kelaikan kerja</label></td>
                <td><input type="checkbox" id="sk_kelaikan" name="sk_kelaikan" value="Y"  <?=$ls_kelaikan;?> <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_startingwork">Melakukan pekerjaan semula :</label></td>
                <td><input type="checkbox" id="sk_startingwork" name="sk_startingwork" value="Y"  <?=$ls_startingwork;?> <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_startingwork_drop">Melakukan pekerjaan semula, efektivitas menurun : </labeL></td>
                <td><input type="checkbox" id="sk_startingwork_drop" name="sk_startingwork_drop" value="Y"  <?=$ls_startingwork_drop;?> /></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_work_medic_problem">Mampu melakukan pekerjaan, dapat mempengaruhi medis : </label></td>
                <td><input type="checkbox" id="sk_work_medic_problem" name="sk_work_medic_problem" value="Y"  <?=$ls_work_medic_problem;?> <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_work_comunity">Mampu melakukan pekerjaan, tapi berlaku bagi pekerja lain atau komunitas : </label></td>
                <td><input type="checkbox" id="sk_work_comunity" name="sk_work_comunity" value="Y" <?=$ls_work_comunity;?>  <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_nowork_temp">Tidak mampu secara fisik mental melakukan pekerjaan, untuk sementar waktu : </label></td>
                <td><input type="checkbox" id="sk_nowork_temp" name="sk_nowork_temp" value="Y" <?=$ls_nowork_temp;?> <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right" valign="top"><label for="sk_work_other">Lain - lain : </label></td>
                <td><input type="checkbox" id="sk_work_other" name="sk_work_other" value="Y" <?=$ls_work_other;?> /><br />
                    <input type="text" id="sk_work_other_i" name="sk_work_other_i" value="<?=$ls_work_other_i;?>" style="width:275px;" <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_ref_kerja_i">Referensi Tenaga Kerja : </label></td>
                <td><input type="text"  id="sk_ref_kerja_i" name="sk_ref_kerja_i" value="<?=$ls_ref_kerja_i;?>" style="width:275px;" <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right"><label for="sk_ref_prs_i">Referensi Perusahaan : </label></td>
                <td><input type="text" id="sk_ref_prs_i" name="sk_ref_prs_i" value="<?=$ls_ref_prs_i;?>" style="width:275px;" <?=$global_readonly;?>/></td>
            </tr>
            <tr>
                <td align="right" valign="top"><label for="sk_keterangan">Keterangan : </label></td>
                <td><textarea id="sk_keterangan" name="sk_keterangan" maxlength="300" style="width:275px;background-color:#ffffff;" rows="2" tabindex="4" <?=$global_readonly;?>><?=$ls_keterangan;?></textarea></td>
            </tr>
        </table>
    </fieldset>
   </form>
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
function get_SKLain_sk(par_data){$("#form_status_kecacatan select[name=sk_impairement]").html(par_data);}
function saveStatusKecacatan()
{
    if(confirm("Save data Status Kecacatan?"))
    {
        saveData('../ajax/pn5032_action_status_kecacatan.php?',$("#form_status_kecacatan").serialize(),'pn5032.php?task=<?=$task;?>&dataid=<?=$ls_kode_rtw;?>&mid=<?=$mid;?>&noform=7')
    }
}
$(document).ready(function(){ 
    lov_subData('SKLain','getMSLookup','RTWSTCACAT','<?=$ls_impairement;?>');
    $("#sk_impairement").change(function(){
        if(this.value=='RTWST04') $("#tr_sklain").show(); else $("#tr_sklain").hide();
    });
});
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>