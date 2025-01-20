<?php
session_start();
$pagetype = "form";
$gs_pagetitle = "PEREKAMAN DATA IKS";
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
$ls_kode_faskes=isset($_REQUEST['faskes'])?$_REQUEST['faskes']:'';

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
if($task_code=="Approve"||$task_code=="View")
{
    $sql = "SELECT KODE_FASKES,KODE_IKS,KODE_TIPE,NO_IKS,TO_CHAR(TGL_AWAL_IKS,'DD/MM/YYYY')TGL_AWAL_IKS,TO_CHAR(TGL_AKHIR_IKS,'DD/MM/YYYY')TGL_AKHIR_IKS,PETUGAS_APPROVAL,TO_CHAR(TGL_APPROVAL,'DD/MM/YYYY')TGL_APPROVAL,
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
                WHEN A.KODE_STATUS_IKS='3' then 'DISETUJUI'  
                WHEN A.KODE_STATUS_IKS='4' then 'DITOLAK' 
                else 'SUBMIT'
            end STATUS_APPROVAL, 
            nvl(ALASAN_APPROVAL , ' ') ALASAN_APPROVAL,
            case
                WHEN A.KODE_STATUS_IKS1='3' then 'DISETUJUI'  
                WHEN A.KODE_STATUS_IKS1='4' then 'DITOLAK' 
                WHEN A.KODE_STATUS_IKS='3' then 'SUBMIT' 
                else ' '
            end STATUS_APPROVAL1,
            nvl(ALASAN_APPROVAL1 , ' ') ALASAN_APPROVAL1,
            (SELECT KODE_KANTOR FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES) || ' - ' || (SELECT NAMA_KANTOR FROM MS.MS_KANTOR WHERE KODE_KANTOR = (SELECT KODE_KANTOR FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES)) AS KODE_NAMA_KACAB,
            ((SELECT KODE_PIC_CABANG FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES) || ' - ' || (SELECT NAMA_USER FROM MS.SC_USER A WHERE (SELECT KODE_PIC_CABANG FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES) = A.KODE_USER)) AS KODE_PIC_CABANG,
            (SELECT KONTAK_PIC_CABANG FROM TC.TC_FASKES WHERE KODE_FASKES = A.KODE_FASKES) AS KONTAK_PIC_CABANG
       FROM {$schema}.TC_IKS A
       WHERE KODE_IKS='{$ls_kode_iks}' and A.KODE_FASKES='{$ls_kode_faskes}'";
    $DB->parse($sql);  // echo $sql;
    $DB->execute();
    if($row = $DB->nextrow())
    {
        $ls_no_iks          = $row['NO_IKS'];
        $ls_status_iks      = $row['KODE_STATUS_IKS'];
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
        $ls_status_approve  = $row['STATUS_APPROVAL'];
        $ls_alasan_approve  = $row['ALASAN_APPROVAL'];
        $ls_status_approve1 = $row['STATUS_APPROVAL1'];
        $ls_alasan_approve1 = $row['ALASAN_APPROVAL1'];
        $kode_nama_kantor       =$row['KODE_NAMA_KACAB'];
        $kode_pic_cabang        =$row['KODE_PIC_CABANG'];
        $ls_handphone_pic_kacab =$row['KONTAK_PIC_CABANG'];
    }      
}
//$ls_form_act = ($task_code=='Submit')?'submitIKS':($task_code=='Adendum'?'adendumIKS':'saveIKS');
$lsr_no_iks = ($task_code=='Adendum')?'readonly':"";
$lsr_tgl_awal = ($task_code=='View')?'readonly':"";
$lsr_tgl_skhir = ($task_code=='View')?'readonly':"";

?>
<div id="actmenu">
  <div id="actbutton">
    <div style="float:left;">
        <div style="float:left;"><div class="icon">
          <?php if($task_code=="Approve" ||  $task_code=="Adendum"){?>
          <a id="btn_approve" href="javascript:void(0)"><img src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0">Proses</a>
          <?php }  ?>
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
    <input type="hidden" id="formregact" name="formregact" value="<?=$task_code;?>" />
    <input type="hidden" id="kode_faskes" name="kode_faskes" value="<?=$ls_kode_faskes;?>" />
    <input type="hidden" id="kode_iks" name="kode_iks" value="<?=$ls_kode_iks;?>" />
    <input type="hidden" id="task" name="task" value="<?=$task_code;?>" />
    <fieldset><legend><b>Approval Data IKS</b></legend>
        <table border='0' width="100%">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="no_iks">No IKS* :</label></div>
                    <div class="f_2"><input type="text" id="no_iks" name="no_iks" style="width:220px;background-color:#e9e9e9" tabindex="1" value="<?=$ls_no_iks;?>" <?=$lsr_no_iks;?>  /></div>
                    <div class="f_1" ><label for="tgl_awal">Tanggal Awal IKS * :</label></div>
                    <div class="f_2"><input type="text" id="tgl_awal" name="tgl_awal" style="width:100px;background-color:#e9e9e9;" tabindex="2" value="<?=$ls_tgl_awal;?>" readonly/></div>
                    <div class="f_1" ><label for="tgl_akhir">Tanggal Akhir IKS * :</label></div>
                    <div class="f_2"><input type="text" id="tgl_akhir" name="tgl_akhir" style="width:100px;background-color:#e9e9e9;" tabindex="2" value="<?=$ls_tgl_akhir;?>" readonly/></div>
                    <div class="f_1">&nbsp;</div>
                    <div class="f_1" ><label for="st_app">Approval 1 :</label></div>
                    <div class="f_2"><input type="text" id="st_app" name="st_app" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_status_approve;?>" readonly /></div>
                    <div class="f_1" ><label for="al_app">Keterangan 1 :</label></div>
                    <div class="f_2"><input type="text" id="al_app" name="al_app" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_alasan_approve;?>" readonly /></div>
                    <div class="f_1" ><label for="st_app1">Approval 2 :</label></div>
                    <div class="f_2"><input type="text" id="st_app1" name="st_app1" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_status_approve1;?>" readonly /></div>
                    <div class="f_1" ><label for="al_app1">Keterangan 2 :</label></div>
                    <div class="f_2"><input type="text" id="al_app1" name="al_app1" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_alasan_approve1;?>" readonly /></div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="kode_adendum">Kode Adendum :</label></div>
                    <div class="f_2"><input type="text" id="kode_adendum" name="kode_adendum" style="width:220px;background-color:#e9e9e9;" value="<?=$ls_kode_adendum;?>" readonly /></div>
                    <div class="f_1" ><label for="act_approve">Action Approval :</label></div>
                    <div class="f_2">
                        <select id="act_approve" name="act_approve" <?php echo ($task_code=='View')?"disabled":"";?>>
                            <?php if( !($ls_status_approve=='DISETUJUI' && $ls_status_approve1=='DITOLAK')){?>
                            <option value="3">Disetujui</option>
                            <?php }?>
                            <option value="4">Ditolak</option>
                        </select>
                    </div>
                    <div class="f_1" ><label for="alasan_approval">Alasan :</label></div>                                   
                    <div class="f_2"><textarea id="alasan_approval" name="alasan_approval" style="width: 220px;background-color:<?php echo ($task_code=='View')?"#e9e9e9":"#ffff99";?>;" rows="2" <?php echo ($task_code=='View')?"readonly":"";?>></textarea></div>
                    <div class="f_1" ><label for="tgl_submit">Tanggal Rekam :</label></div>
                    <div class="f_2"><input type="text" id="tgl_submit" name="tgl_submit" style="width:140px;background-color:#e9e9e9;" value="<?=$ls_tgl_rekam;?>" readonly /></div>
                    <div class="f_1" ><label for="p_submit">Petugas Rekam :</label></div>
                    <div class="f_2"><input type="text" id="p_submit" name="p_submit" style="width:200px;background-color:#e9e9e9;" value="<?=$ls_petugas_rekam;?>" readonly /></div>
                </td>
            </tr>
        </table>
    </fieldset>
    </form>
</div>
<fieldset>
<legend>Lampiran IKS</legend>
<?php
$sql="select * from tc.tc_iks_lampiran 
    WHERE KODE_IKS='{$ls_kode_iks}' and KODE_FASKES='{$ls_kode_faskes}'"; //echo $sql;
$DB->parse($sql);
$DB->execute();
echo "<ol>";
while($row1 = $DB->nextrow()){
    echo "<li><a href=\"http://{$HTTP_HOST}/mod_pn/ajax/pn2401_download_lampiran.php?kd={$row1['KODE_LAMPIRAN']}\">{$row1['NAMA_FILE']}</a></li>";
}
echo "</ol>"
?>
</fieldset>
<?php
$sql = "SELECT a.*,
        (select nama_tipe from {$schema}.tc_kode_tipe where kode_tipe=a.kode_tipe) nama_tipe,
        (select nama_kelurahan from {$schema}.ms_kelurahan where kode_kelurahan=a.kode_kelurahan) nama_kelurahan,
        (select nama_kecamatan from {$schema}.ms_kecamatan where kode_kecamatan=a.kode_kecamatan) nama_kecamatan,
        (select nama_kabupaten from {$schema}.ms_kabupaten where kode_kabupaten=a.kode_kabupaten) nama_kabupaten,
        (select nama_jenis from {$schema}.tc_kode_jenis where kode_jenis=a.kode_jenis) nama_jenis,
        (select nama_jenis_detil from {$schema}.tc_kode_jenis_detil where kode_jenis_detil=a.kode_jenis_detil) nama_jenis_detil,
        (select nama_kelurahan nama_kelurahan_pemilik from {$schema}.ms_kelurahan where kode_kelurahan=a.kode_kelurahan_pemilik) nama_kelurahan_pemilik,
        (select nama_kecamatan nama_kecamatan_pemilik from {$schema}.ms_kecamatan where kode_kecamatan=a.kode_kecamatan_pemilik) nama_kecamatan_pemilik,
        (select nama_kabupaten nama_kabupaten_pemilik from {$schema}.ms_kabupaten where kode_kabupaten=a.kode_kabupaten_pemilik) nama_kabupaten_pemilik,
        (select nama_kepemilikan from {$schema}.tc_kode_kepemilikan where kode_kepemilikan=a.kode_kepemilikan) nama_kepemilikan,
        (select nama_metode_pembayaran from {$schema}.tc_kode_metode_pembayaran where kode_metode_pembayaran=a.kode_metode_pembayaran) metode_pembayaran,
        (select nama_bank from {$schema}.ms_bank where kode_bank=a.kode_bank_pembayaran) nama_bank_pembayaran
        FROM {$schema}.TC_FASKES A
        WHERE A.KODE_FASKES='{$ls_kode_faskes}'"; // echo $sql;    
//echo $sql;
$DB->parse($sql);
$DB->execute();
$row_faskes = $DB->nextrow();
?>
<fieldset>
<legend>Informasi Fasilitas</legend>
<table width="100%" cellpadding="1">
    <tr>
        <td align="right" width="17%">Nama Faskes/BLK : </td>
        <td width="33%"><?=$row_faskes['NAMA_FASKES'];?></td>
        <td align="right" width="17%">No Telp : </td>
        <td><?php echo "{$row_faskes['TELEPON_AREA_PIC']} {$row_faskes['TELEPON_PIC']}";?></td>
    </tr>
    <tr>
        <td align="right">No Faskes/BLK : </td>
        <td><?=$row_faskes['NO_FASKES'];?></td>
        <td align="right">Telp Ext : </td>
        <td><?php echo "{$row_faskes['TELEPON_EXT_PIC']}";?></td>
    </tr>
    <tr>
        <td align="right">Tipe Faskes/BLK : </td>
        <td><?=$row_faskes['NAMA_TIPE'];?></td>
        <td align="right">Nama PIC : </td>
        <td><?php echo "{$row_faskes['NAMA_PIC']}";?></td>
    </tr>
    <tr>
        <td align="right">Kode kantor : </td>
        <td><?=$row_faskes['KODE_KANTOR'];?></td>
        <td align="right">Handphone PIC : </td>
        <td><?php echo "{$row_faskes['HANDPHONE_PIC']}";?></td>
    </tr>
    <tr>
        <td align="right">Alamat Lengkap : </td>
        <td><?=$row_faskes['ALAMAT'];?></td>
        <td align="right">No Ijin Praktek : </td>
        <td><?php echo "{$row_faskes['NO_IJIN_PRAKTEK']}";?></td>
    </tr>
    <tr>
        <td align="right">RT/RW : </td>
        <td><?php echo "{$row_faskes['RT']}/{$row_faskes['RW']}";?></td>
        <td align="right">NPWP : </td>
        <td><?php echo "{$row_faskes['NPWP']}";?></td>
    </tr>
    <tr>
        <td align="right">Kelurahan : </td>
        <td><?=$row_faskes['NAMA_KELURAHAN'];?></td>
        <td align="right">Jenis Faskes : </td>
        <td><?php echo "{$row_faskes['NAMA_JENIS']}";?></td>
    </tr>
    <tr>
        <td align="right">Kecamatan : </td>
        <td><?=$row_faskes['NAMA_KECAMATAN'];?></td>
        <td align="right">Jenis Detil Faskes : </td>
        <td><?php echo "{$row_faskes['NAMA_JENIS_DETIL']}";?></td>
    </tr>
    <tr>
        <td align="right">Kabupaten : </td>
        <td><?=$row_faskes['NAMA_KABUPATEN'];?></td>
        <td align="right">Keterangan : </td>
        <td><?php echo "{$row_faskes['Keterangan']}";?></td>
    </tr>
    <tr>
        <td align="right">Latitude : </td>
        <td><?=$row_faskes['LATITUDE'];?></td>
    </tr>
    <tr>
        <td align="right">Longitude : </td>
        <td><?=$row_faskes['LONGITUDE'];?></td>
    </tr>
</table>
</fieldset>
<fieldset>
<legend>PIC Kantor Cabang</legend>
<table width="100%" cellpadding="1">
    <tr>
        <td align="right" width="17%"></td><td></td>
        <td align="right" width="17%"></td><td></td>
    </tr>
    <tr>
        <td align="right">Nama PIC Kantor Cabang:</td><td><?=$kode_pic_cabang;?></td>
        <td align="right" width="17%"></td><td></td>
    </tr>
    <tr>
        <td align="right">Handphone PIC Cabang:</td><td><?=$ls_handphone_pic_kacab;?></td>
        <td align="right" width="17%"></td><td></td>
    </tr>
</table>
</fieldset>
<fieldset>
<legend>Informasi Pemilik</legend>
<table width="100%" cellpadding="1">
    <tr>
        <td align="right" width="17%">Nama : </td>
        <td width="33%"><?=$row_faskes['NAMA_PEMILIK'];?></td>
        <td align="right" width="17%">Bagian PIC : </td>
        <td><?=$row_faskes['BAGIAN_PIC'];?></td>
    </tr>
    <tr>
        <td align="right">Alamat : </td>
        <td><?=$row_faskes['ALAMAT_PEMILIK'];?></td>
        <td align="right">No Telp : </td>
        <td><?php echo "{$row_faskes['TELEPON_AREA_PEMILIK']} {$row_faskes['TELEPON_PEMILIK']}";?></td>
    </tr>
    <tr>
        <td align="right">RT/RW : </td>
        <td><?php echo "{$row_faskes['RT_PEMILIK']}/{$row_faskes['RW_PEMILIK']}";?></td>
        <td align="right">Ext : </td>
        <td><?=$row_faskes['TELEPON_EXT_PEMILIK'];?></td>
    </tr>
    <tr>
        <td align="right">Kelurahan : </td>
        <td><?=$row_faskes['NAMA_KELURAHAN_PEMILIK'];?></td>
        <td align="right">Fax: </td>
        <td><?php echo "{$row_faskes['FAX_AREA_PEMILIK']} {$row_faskes['FAX_PEMILIK']}";?></td>
    </tr>
    <tr>
        <td align="right">Kecamatan : </td>
        <td><?=$row_faskes['NAMA_KECAMATAN_PEMILIK'];?></td>
        <td align="right">Alamat Email : </td>
        <td><?php echo "{$row_faskes['EMIL_PEMILIK']}";?></td>
    </tr>
    <tr>
        <td align="right">Kabupaten : </td>
        <td><?=$row_faskes['NAMA_KABUPATEN_PEMILIK'];?></td>
        <td align="right">Tipe Pemilik : </td>
        <td><?=$row_faskes['NAMA_KEPEMILIKAN'];?></td>
    </tr>
</table>
</fieldset>
<fieldset>
<legend>Informasi Metode Pembayaran</legend>
<table width="100%" cellpadding="1">
    <tr>
        <td align="right" width="17%">Metode Pembayaran : </td>
        <td width="33%"><?=$row_faskes['NAMA_METODE_PEMBAYARAN'];?></td>
        <td align="right" width="17%">No Rekening : </td>
        <td><?=$row_faskes['NO_REKENING_PEMBAYARAN'];?></td>
    </tr>
    <tr>
        <td align="right">Bank Penerima : </td>
        <td><?=$row_faskes['NAMA_BANK_PEMBAYARAN'];?></td>
        <td align="right">Nama Rekening : </td>
        <td><?=$row_faskes['NAMA_REKENING_PEMBAYARAN'];?></td>
    </tr>
</table>
</fieldset>
<script type="text/javascript">
$(document).ready(function(){ 
    $('#btn_approve').click(function() {
        if(confirm('Proses Approval data IKS?'))
            approve_IKS();
    });
});
function approve_IKS()
{
    $("#ikserror").html('' );
    preload(true);
    $.ajax({
        type: 'POST',
        url: "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn2402_act.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){  
            preload(false);  
            //console.log($('#formreg').serialize());   
            if($.trim(ajaxdata)!="") 
                $("#ikserror").html(ajaxdata);
            else{ 
                window.opener.refreshData();
                window.close();
            }
        }
    });
}
</script>
<?php      
include "../../includes/footer_app_nosql.php";
?>