<?php /*****LOAD DATA*************************************/
/*** */
$schema="sijstk";
$dataid= isset($_GET['dataid'])?$_GET['dataid']:'';
$ls_no                  ='0';
$ls_kode                ='';
$ls_nama                ='';
$ls_status_nonaktif     ='';
$ls_tgl_nonaktif        ='';
$ls_tgl_awal            = $today;
$ls_tgl_akhir           = $today;
$ls_tgl_rekam           ='';
$ls_petugas_rekam       ='';
$ls_tgl_ubah            ='';
$ls_petugas_ubah        ='';

if($task_code=="View" || $task_code=="Edit")
{
    if($task_no=="Kegiatan")
        $sql = "SELECT  NO_URUT,KODE_KEGIATAN KODE,NAMA_KEGIATAN NAMA,KETERANGAN KETERANGAN,STATUS_NONAKTIF,TO_CHAR(TGL_NONAKTIF,'DD/MM/YYY') TGL_NONAKTIF,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_PROMOTIF_KEGIATAN
        WHERE KODE_KEGIATAN='{$dataid}'"; // echo $sql;
    else if($task_no=="SubKegiatan")
        $sql = "SELECT  rownum NO_URUT,KODE_KEGIATAN KODE_PARENT,KODE_SUB_KEGIATAN KODE,NAMA_SUB_KEGIATAN NAMA,KETERANGAN KETERANGAN,
        BENTUK_KEGIATAN,REGULASI,TO_CHAR(TGL_EFEKTIF,'DD/MM/YYYY') TGL_AWAL,TO_CHAR(TGL_AKHIR,'DD/MM/YYYY') TGL_AKHIR,
        TO_CHAR(TGL_REKAM,'DD/MM/YYYY') TGL_REKAM, PETUGAS_REKAM,NVL(TO_CHAR(TGL_UBAH,'DD/MM/YYYY'),' ') TGL_UBAH, 
        NVL(PETUGAS_UBAH,' ') PETUGAS_UBAH
        FROM {$schema}.PN_KODE_PROMOTIF_SUB
        WHERE KODE_SUB_KEGIATAN='{$dataid}'"; // echo $sql;
    
    //echo $sql;
    $DB->parse($sql);
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_no                  = $row['NO_URUT'];
        $ls_kode                = $row['KODE'];
        $ls_kode_parent         = $row['KODE_PARENT'];
        $ls_nama                = $row['NAMA'];
        $ls_keterangan          = $row['KETERANGAN'];
        $ls_status_nonaktif     = $row['STATUS_NONAKTIF'];
        $ls_tgl_nonaktif        = $row['TGL_NONAKTIF'];
        $ls_tgl_rekam           = $row['TGL_REKAM'];
        $ls_petugas_rekam       = $row['PETUGAS_REKAM'];
        $ls_tgl_ubah            = $row['TGL_UBAH'];
        $ls_tgl_ubah            = $row['TGL_UBAH'];
        $ls_petugas_ubah        = $row['PETUGAS_UBAH'];
        $ls_bentuk_kegiatan     = $row['BENTUK_KEGIATAN'];
        $ls_regulasi            = $row['REGULASI'];
        $ls_tgl_awal            = $row['TGL_AWAL'];
        $ls_tgl_akhir           = $row['TGL_AKHIR'];
    }
}
$i_kode_readonly = ($task_code=='New')?'':'readonly';
$i_kode_color = ($task_code=='New')?'#ffff99;':'#e9e9e9;';
$i_nama_readonly = ($task_code=='New' || $task_code=='Edit')?'':'readonly';
$i_nama_color = ($task_code=='New' || $task_code=='Edit')?'#ffff99;':'#e9e9e9;';
$i_status_nonaktif = ($task_code=='View')?"disabled=\"disabled\"":"";

$l_name = $task_no=="Kegiatan"?"Kegiatan":"Sub Kegiatan";
    /*****end LOAD DATA*********************************/ ?>
<style>
.f_0{}
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
.f_1{width:160px;text-align:right;float:left;clear:left;margin-bottom:2px;}
.f_2{width:310px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }
.f_readonly{background-color:#e9e9e9;}
.f_mandatory{background-color:#ffff99;}
</style>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">

</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>

<form name="formreg" id="formreg" role="form" method="post">
<input type="hidden" id="formregact" name="formregact" value="<?=$task_code;?>" />
<input type="hidden" id="taskno" name="taskno" value="<?=$task_no;?>" />
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="f_1" ><label for="kode">Kode <?=$l_name;?>* :</label></div>
            <div class="f_2"><input type="text" id="kode" name="kode" style="width:80px;background-color:<?=$i_kode_color;?>" value="<?=$ls_kode;?>" <?=$i_kode_readonly;?>  /></div>
            <div class="f_1" ><label for="nama">Nama <?=$l_name;?>* :</label></div>
            <div class="f_2"><input type="text" id="nama" name="nama" style="width:270px;background-color:<?=$i_nama_color;?>"    value="<?=$ls_nama;?>" <?=$i_nama_readonly;?>/></div>
            <?php if($task_no=='SubKegiatan'){?>
            <div class="f_1" ><label for="tgl_awal">Tgl Efektif :</label></div>
            <div class="f_2">
                <input type="text" id="tgl_awal" name="tgl_awal" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl_awal;?>" readonly/><input type="image" align="top" onclick="return showCalendar('tgl_awal', 'dd-mm-y');" src="../../images/calendar.gif"/> -
                <input type="text" id="tgl_akhir" name="tgl_akhir" style="width:100px;background-color:#ffffff;" value="<?=$ls_tgl_akhir;?>" readonly/><input type="image" align="top" onclick="return showCalendar('tgl_akhir', 'dd-mm-y');" src="../../images/calendar.gif"/>
            </div>
            <div class="f_1" ><label for="kode_parent">Kode Kegiatan* :</label></div>
            <div class="f_2"><select id="kode_parent" name="kode_parent"></select></div>
            <?php }?>
            <div class="f_1" ><label for="nourut">No Urut* :</label></div>
            <div class="f_2"><input type="text" id="nourut" name="nourut" style="text-align:right;width:60px;background-color:<?=$i_nama_color;?>"    value="<?=$ls_no;?>" <?=$i_nama_readonly;?>/></div>
            <div class="f_1" ><label for="keterangan">Keterangan :</label></div>
            <div class="f_2"><textarea id="keterangan" name="keterangan" style="width:270px;background-color:<?=$i_nama_color;?>" <?=$i_nama_readonly;?> rows="4"><?=$ls_keterangan;?></textarea></div>            
            <?php if($task_no=='Kegiatan'){?>
            <div class="f_1" ><label for="status_nonaktif">Non Aktif* :</label></div>
            <div class="f_2"><select id="status_nonaktif" name="status_nonaktif"  <?=$i_status_nonaktif;?>>
                <option value="T"<?php echo $ls_status_nonaktif=='T'?'selected':'';?>>Tidak</option>
                <option value="Y" <?php echo $ls_status_nonaktif=='Y'?'selected':'';?>>Ya</option>
            </select><input type="hidden" id="status_nonaktif_old" name="status_nonaktif_old" value="<?=$ls_status_nonaktif;?>" />
            </div>
            <?php }else{?>
            <div class="f_1" ><label for="btkkegiatan">Bentuk Kegiatan :</label></div>
            <div class="f_2"><select id="btkkegiatan" name="btkkegiatan"  <?=$i_status_nonaktif;?>>
                <option value="BARANG"<?php echo $ls_bentuk_kegiatan=='BARANG'?'selected':'';?>>BARANG</option>
                <option value="JASA" <?php echo $ls_bentuk_kegiatan=='JASA'?'selected':'';?>>JASA</option>
            </select>
            </div>
            <div class="f_1" ><label for="regulasi">Regulasi :</label></div>
            <div class="f_2"><input type="text" id="regulasi" name="regulasi" style="width:270px;background-color:<?=$i_nama_color;?>"    value="<?=$ls_regulasi;?>" <?=$i_nama_readonly;?>/></div>
            <?php }?>
        </td>
        <td valign="top">
            <div class="f_1" ><label for="tgl_nonaktif">Tanggal Non Aktif :</label></div>
            <div class="f_2"><input type="text" id="tgl_nonaktif" name="tgl_nonaktif" style="100px;background-color:#e9e9e9;"  value="<?=$ls_tgl_nonaktif;?>" readonly /></div>
            <div class="f_1" ><label for="tgl_rekam">Tanggal Rekam :</label></div>
            <div class="f_2"><input type="text" id="tgl_rekam" name="tgl_rekam" style="100px;background-color:#e9e9e9;"  value="<?=$ls_tgl_rekam;?>" readonly /></div>
            <div class="f_1" ><label for="petugas_rekam">Petugas Rekam :</label></div>
            <div class="f_2"><input type="text" id="petugas_rekam" name="petugas_rekam" style="width:275px;background-color:#e9e9e9;"  value="<?=$ls_petugas_rekam;?>"/></div>
            <div class="f_1" ><label for="tgl_ubah">Tanggal Ubah :</label></div>
            <div class="f_2"><input type="text" id="tgl_ubah" name="tgl_ubah" style="100px;background-color:#e9e9e9;"  value="<?=$ls_tgl_ubah;?>" readonly /></div>
            <div class="f_1" ><label for="petugas_ubah">Petugas ubah :</label></div>
            <div class="f_2"><input type="text" id="petugas_ubah" name="petugas_ubah" style="width:275px;background-color:#e9e9e9;"  value="<?=$ls_petugas_ubah;?>"/></div>
        </td>
    </tr>
</table>
</form>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;
function saveData()
{
    preload(true);
    $.ajax({
        type: 'POST',
        url: "../ajax/<?=$php_file_name;?>_action.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){ 
            preload(false);
            //console.log($('#formreg').serialize());	
            if($.trim(ajaxdata)!="") alert(ajaxdata);
            else{
                window.parent.Ext.notify.msg('Penyimpanan data sukses!','');
                window.location='<?=$php_file_name;?>.php?taskno=<?=$task_no;?>&mid=<?=$mid;?>';
            }
        },
        error:function(){
            alert("error saving data!");
            preload(false);
        }
    });
}
function enableInput(obj_jquery,ena)
{
    if(ena) obj_jquery.prop('disabled', false);
    else obj_jquery.prop('disabled', true);
}
function cek_dataNew()
{ 
    if($("#kode").val()==''||$("#nama").val()=='')
        return false;
    else
        return true;
}
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2; 
    $.ajax({
        type: 'GET',
        url: "../ajax/<?=$php_file_name;?>_lov.php?"+Math.random(),
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){ console.log(ajaxdata);
            if(par_callFunc=='getSubKegiatan') get_SubKegiatan(ajaxdata);	
        }
    });
}

function get_SubKegiatan(par_data){$("#formreg select[name=kode_parent]").html(par_data);}

$(document).ready(function(){ 
    $("#btn_save").click(function(){
        if(!cek_dataNew())
            alert("Lengkapi isian mandatory field terlebih dulu!");
        else if(confirm('Save new data?')){
            saveData();
        }
    });
    <?php if($task_no == "SubKegiatan"){?>
        lov_subData('getSubKegiatan','getSubKegiatan','<?=$ls_kode_parent;?>','');
    <?php }?>
});
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>