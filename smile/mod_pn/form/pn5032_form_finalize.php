<?php 
/*****LOAD DATA *************************************/

if($task=="View" || $task=="Edit")
{
    $sql = 	"select STATUS_SELESAI,KET_BATAL,KETERANGAN,STATUS_BATAL from sijstk.pn_rtw_klaim where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);// echo $sql;
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_keterangan  				        = $row['KETERANGAN'];
        
    }
}  //   echo $sql;
/*****end LOAD DATA*********************************/ 
?>
<form id="form_finalize">
    <input type="hidden" id="f_kode_rtw" name="kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <input type="hidden" name="formregact" value="<?=$task;?>" />
    <input type="hidden" name="noform" value="13" />
        <table cellspacing="1" width="100%">
            <tr>
                <td align="right" width="400" valign="top"><label for="f_status_rtw" >Status RTW : </label></td>
                <td valign="top"><select id="f_status_rtw" name="f_status_rtw" >
                    <?php if( !($jml_data['status_rtw_klaim']=="SR2" || $jml_data['status_rtw_klaim']=="SR3" || $jml_data['status_rtw_klaim']=="SR4" || $jml_data['status_rtw_klaim']=="SR5") ) {?>
                    <option value=""></option> 
                    <?php }?>
                    <?php if($jml_data['lengkap']==1) {?>
                    <option value="S" <?php echo $jml_data['status_rtw_klaim']=='SR2'?'selected':''; ?>>Selesai</option>
                    <?php }?>
                    <?php if($jml_data['status_rtw_klaim']=="SR1" || $jml_data['status_rtw_klaim']=='SR3') {?>
                    <option value="P" <?php echo $jml_data['status_rtw_klaim']=='SR3'?'selected':''; ?>>Putus</option>
                    <? }?>
                    <?php if($jml_data['status_rtw_klaim']=="SR2" || $jml_data['status_rtw_klaim']=="SR3" || $jml_data['status_rtw_klaim']=="SR4") {?>
                    <option value="B" <?php echo $jml_data['status_rtw_klaim']=='SR4'?'selected':''; ?>>Batal</option>
                    <?php }?>
                </select>
                <br />
                <span style="color:#580000">
                <b>Status Finalisasi RTW (Selesai, Putus, Batal):<br />
                 Selesai - Semua bagian RTW telah dilakukan<br />
                 Putus - RTW putus/ tidak dilanjutkan<br />
                 Batal - Pembatalan RTW yang sudah selesai/ putus<br />
                </span><br />
                </td>
            </tr>
            <tr>
                <td align="right" valign="top"><label for="f_keterangan">Keterangan : </label></td>
                <td><textarea id="f_keterangan" name="f_keterangan" maxlength="300" style="width:275px;background-color:#ffffff;" rows="4" tabindex="4" <?=$status_readonly;?>><?=$ls_keterangan;?></textarea></td>
            </tr>
        </table>
   </form>
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
function saveStatusRTW()
{
    if(confirm("Save data Status RTW?"))
    {
        saveData('../ajax/pn5032_action_status_rtw.php?',$("#form_finalize").serialize(),'pn5032.php');
    }
}
$(document).ready(function(){ 
});
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>