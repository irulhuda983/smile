<?php 
/*****LOAD DATA *************************************/
$ls_no_rekam					        = '';
$ls_tgl1    					        = '';
$ls_pernyataan_tk				        = '';
$ls_diagnosa					        = '';
$ls_nm_dokter					        = '';
$ls_tujuan					            = '';
$ls_keputusan					        = '';
$ls_keterangan				            = '';

if($task=="View" || $task=="Edit")
{
    $sql = 	"select * from sijstk.pn_rtw_perencanaan where kode_rtw_klaim='{$ls_kode_rtw}'";
    $DB->parse($sql);// echo $sql;
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_no_rekam					        = $row['NO_REKAM_MEDIS'];
        $ls_tgl1    					        = $row['TGL_PEMERIKSAAN1'];
        $ls_pernyataan_tk				        = $row['STATUS_PERNYATAAN_TK'];
        $ls_diagnosa					        = $row['DIAGNOSA'];
        $ls_nm_dokter					        = $row['NAMA_MEDIS'];
        $ls_tujuan					            = $row['TUJUAN'];
        $ls_keputusan					        = $row['STATUS_KEPUTUSAN'];
        $ls_keterangan				            = $row['KETERANGAN'];
    }
}  //   echo $sql;
/*****end LOAD DATA*********************************/ 
?>
<form id="form_perencanaan">
<input type="hidden" name="formregact" value="<?=$task;?>" />
<input type="hidden" name="kode_rtw" value="<?=$ls_kode_rtw;?>" />
<input type="hidden" name="noform" value="3" />
<fieldset><legend><b>Perencanaan</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="p_no_rekam_medis">No Rekam Medis :</label></div>
                <div class="f_2"><input type="text" id="p_no_rekam_medis" name="p_no_rekam_medis" maxlength="100"  value="<?=$ls_no_rekam;?>" style="width:140px;background-color:#ffffff;"  <?=$global_readonly;?>/></div>
                <div class="f_1" ><label for="p_tgl1">Tgl. Pemeriksaan Pertama :</label></div>
                <div class="f_2">
                    <input type="text" id="p_tgl1" name="p_tgl1" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl1;?>" readonly/><input type="image" align="top" onclick="return showCalendar('p_tgl1', 'dd-mm-y');" src="../../images/calendar.gif"/>
                </div>
                <div class="f_1" ><label for="p_pernyataan_tk">TK Membuat Pernyataan :</label></div>
                <div class="f_2"><select id="p_pernyataan_tk" name="p_pernyataan_tk"  <?=$global_readonly;?> >
                    <option  value="T" <?php echo $ls_pernyataan_tk=='T'?'selected':'';?>>Tidak</option>
                    <option  value="Y" <?php echo $ls_pernyataan_tk=='Y'?'selected':'';?>>Ya</option>
                    </select>
                </div>
                <div class="f_1" ><label for="p_diagnosa">Diagnosa :</label></div>
                <div class="f_2"><input type="text" id="p_diagnosa" name="p_diagnosa" style="width:245px;background-color:#ffffff;" value="<?=$ls_diagnosa;?>"   <?=$global_readonly;?>/></div>
            </td>
            <td valign="top">
                <div class="f_1" ><label for="p_nm_dokter">Nama Dokter/Tenaga Medis :</label></div>
                <div class="f_2"><input type="text" id="p_nm_dokter" name="p_nm_dokter" maxlength="100"  value="<?=$ls_nm_dokter;?>" style="width:140px;background-color:#ffffff;"  <?=$global_readonly;?>/></div>
                <div class="f_1" ><label for="p_tujuan">Tujuan/Sasaran :</label></div>
                <div class="f_2"><input type="text" id="p_tujuan" name="p_tujuan" style="width:240px;background-color:#ffffff;" value="<?=$ls_tujuan;?>"  <?=$global_readonly;?>/></div>
                <div class="f_1" ><label for="p_keputusan">Keputusan :</label></div>
                <div class="f_2"><select id="p_keputusan" name="p_keputusan"  <?=$global_readonly;?>>
                    <option value="T" <?php echo $ls_keputusan=='T'?'selected':'';?>>Tidak Layak</option>
                    <option value="Y" <?php echo $ls_keputusan=='Y'?'selected':'';?>>Layak</option>
                    </select>
                </div>
                <div class="f_1" ><label for="p_keterangan">Keterangan :</label></div>
                <div class="f_2"><input type="text" id="p_keterangan" name="p_keterangan" style="width:240px;background-color:#ffffff;" value="<?=$ls_keterangan;?>"  <?=$global_readonly;?>/></div>
            </td>
        </tr>
    </table>
    <br />
    <b>Informasi Perencanaan</b>
    <hr />
    <table  id="" cellspacing="0" border="0" bordercolor="#C0C0C0" background-color= "#ffffff" width="100%">
        <thead>
        <tr>
            <th colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
          <tr bgcolor="#e0e0e0">
            <?php if(!$ro_data){?>
            <th class="align-left"></th>
            <?php }?>
            <th class="align-left">Strategi Penyelesaian</th>
            <th class="align-left">Hambatan</th>                        
            <th class="align-left">Perkiraan Mulai Rel</th>
            <th class="align-left">Perkiraan Akhir Rel</th> 
            <th class="align-left">Estimasi Biaya</th>
            <th class="align-left">Keterangan</th>
          </tr>
        </thead>
        <tr>
            <th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
        <tbody id="listdata_perencanaan_info">
        </tbody>
        <tr>
            <th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
      </table> 
      <?php if((!$ro_data && $jml_data['perencanaan']>0) &&  $global_readonly==''){?>
      <br /> 
      <div style="border-bottom: 1px double #C0C0FF;width:100px;float:right;" align="right">
        <a href="javascript:NewWindow('pn5032_form_perencanaan_info.php?dataid=<?=$ls_kode_rtw;?>&task=NewInfo&parenttask=<?=$task;?>','_per_info',800,300,1);" ><img src="../../images/plus.png" alt="" />Tambah</a>
      </div> 
      <?php }?>
</fieldset>
</form> 
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
window.get_PerencanaanInfoData=function(p_kode_rtw)
{
    lov_subData('PerencanaanInfo','getPerencanaanInfo',p_kode_rtw,'<?php echo ($ro_data?'ro':'');?>');    
}
function get_PerencanaanInfo(par_data)
{
    $("#listdata_perencanaan_info").html(par_data);
}
$(document).ready(function(){ 
    window.get_PerencanaanInfoData('<?=$ls_kode_rtw;?>');
});
<?php if(!$ro_data){?>
function savePerencanaan()
{
    if(confirm("Save data Perencanaan?"))
    {
        saveData('../ajax/pn5032_action_perencanaan.php?',$("#form_perencanaan").serialize(),'pn5032.php?task=<?=$task;?>&dataid=<?=$ls_kode_rtw;?>&mid=<?=$mid;?>&noform=3')
    }
}
function deleteInfoPerencanaan(par_kode,par_no)
{
    if(confirm('Hapus data informasi Perencanaan?'))
    {
        deleteData('../ajax/pn5032_action_perencanaan.php','delPerencanaan','delPerencanaan',par_kode,par_no);
    }
}
<?php } ?>
</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>