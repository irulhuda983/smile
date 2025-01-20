<?php 

/*****LOAD DATA *************************************/
/*
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
}  //   echo $sql; */
/*****end LOAD DATA*********************************/ 
?>
<span style="color:#FF0000" id="lamp_error"></span>
<iframe name="iframe_ulamp" id="iframe_ulamp"  src="#" style="postion:fixed;left:-9999;top:-9999;display:none"></iframe>
<form id="form_lampiran" name="iframe_ulamp" target="iframe_ulamp"  action="../ajax/pn5032_action_lampiran.php" method="POST" enctype="multipart/form-data"> 
    <?php if(!$ro_data && $global_readonly==''){?>
    <fieldset><legend><b>Lampiran</b></legend>
    <ol>
    <?php
        $sql = 	"select KODE,KETERANGAN from sijstk.ms_lookup where tipe='RTWLAMP' and aktif='Y' order by seq";
        $DB->parse($sql);// echo $sql;
        $DB->execute();
        $irow=1;
        while($row = $DB->nextrow())
            echo "<li>{$row['KETERANGAN']} : <input type=\"file\" id=\"lamp_file". $irow++ . "\" name=\"lamp_file[{$row['KODE']}]\" />\n
                Keterangan: <input type=\"text\" name=\"lamp_ket_{$row['KODE']}\" style=\"width:240px;\"/></li>\n";
    ?>
    </ol>
    <input type="button" value="Upload Dokumen" id="lamp_upload" />
    <input type="hidden" name="formregact" value="uploaddoc" />
    <input type="hidden" id="lamp_kode_rtw" name="lamp_kode_rtw" value="<?=$ls_kode_rtw;?>" />
    <?php }?>
    <table  id="listdata" cellspacing="0" border="0" bordercolor="#C0C0C0" background-color= "#ffffff" width="100%">
    <thead>
        <tr>
            <th colspan="12"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        </tr>
        <tr bgcolor="#F0F0F0">
        <?php if(!$ro_data && $global_readonly==''){?>
        <th class="align-left">Action</th>
        <?php }?>
        <th class="align-left">Nama File</th>
        <th class="align-left">Jenis File</th>
        <th class="align-left">Ukuran Dokumen</th>                        
        <th class="align-left">Tgl Rekam</th>
        <th class="align-left">Petugas Rekam</th>                        
        </tr>
    </thead>
        <tr>
            <th colspan="12"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
    <tbody id="listdata_lampiran">
    </tbody>
    <tr>
            <th colspan="12"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        </tr>
    </table> 
</fieldset>
</form>
<?php /*****LOCAL JAVASCRIPT INFORMASI DASAR*************************************/?>
<script type="text/javascript">
var rtw_lampiran='';
function Right(str, n){
    if (n <= 0)
       return "";
    else if (n > String(str).length)
       return str;
    else {
       var iLen = String(str).length;
       return String(str).substring(iLen, iLen - n);
    }
}
<?php if(!$ro_data){?>
function initiateUploadLampiran()
{
    $('#form_lampiran').submit(function(){
        $("#lamp_error").html('');
        var response;
        preload(true);
        var frame_lampiran=$("#iframe_ulamp").load(function(){
            preload(false);
            response=frame_lampiran.contents().find('body'); 
            if(response.html()!='')
                $("#lamp_error").html(response.html());
            else
            {
                window.parent.Ext.notify.msg('Sukses Upload dokumen!');
                loadDataLampiran();
            }
            frame_lampiran.unbind("load");
            setTimeout(function(){ response.html(''); },1);   
            lov_subData('Lampiran','getLampiran','<?=$ls_kode_rtw;?>','');  
        });
    });
    $("#lamp_upload").click(function(){ 
        if($("#lamp_file1").val()=='' && $("#lamp_file2").val()=='' && $("#lamp_file3").val()=='')
            $("#lamp_error").html('Silahkan pilih file yang akan di unggah!');
        else if( (Right(String($("#lamp_file1").val()),3).toUpperCase()!='PDF' && $("#lamp_file1").val()!='') ||
            (Right(String($("#lamp_file2").val()),3).toUpperCase()!='PDF' && $("#lamp_file2").val()!='') ||
            (Right(String($("#lamp_file3").val()),3).toUpperCase()!='PDF' && $("#lamp_file3").val()!=''))
            $("#lamp_error").html('Hanya file bertipe pdf yang bisa di unggah!');
        else if(!confirm('Upload file?')) return 0;
        else $('#form_lampiran').submit();
    });
}
function deleteLampiran(par_kode,par_no)
{
    if(confirm('Hapus data Lampiran?'))
    {
        deleteData('../ajax/pn5032_action_lampiran.php','delLampiran','delLampiran',par_kode,par_no);
    }
}
<?php } ?>
function get_Lampiran(par_data){$("#listdata_lampiran").html(par_data);}
window.loadDataLampiran=function(){
    lov_subData('Lampiran','getLampiran','<?=$ls_kode_rtw;?>','<?php echo ((!$ro_data && $global_readonly=='')?"":"ro");?>');  
}
$(document).ready(function(){
    <?php if(!$ro_data){?>
    initiateUploadLampiran();
    <?php }?>
    lov_subData('Lampiran','getLampiran','<?=$ls_kode_rtw;?>','<?php echo ((!$ro_data && $global_readonly=='')?"":"ro");?>');  
});

</script> 
<?php /*****end LOCAL JAVASCRIPT LOCAL JAVASCRIPT INFORMASI DASAR*********************************/?>