<?php /*****LOAD DATA*************************************/
/*** */
$schema="sijstk";
$task= isset($_GET['task'])?$_GET['task']:'';
/* PF A.0.1 Create Initial Value 
--------------------------------------------------------------------------*/
//Kantor -------------------------------------------------------------------
$ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif; 
if($ls_kode_kantor=="")
{
   $ls_kode_kantor =  $gs_kantor_aktif;
}
$ls_user_login = $_SESSION['USER'];
//Sumber Data : sesuai kantor login ----------------------------------------
$sql = "select kode_tipe from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_tipe_kantor = $row["KODE_TIPE"];

if ($ls_tipe_kantor=="0")
{
   $ls_kode_sumber_data = "1";
}else if ($ls_tipe_kantor=="1" || $ls_tipe_kantor=="2")
{
   $ls_kode_sumber_data = "2";
}
else if ($ls_tipe_kantor=="3" || $ls_tipe_kantor=="4" || $ls_tipe_kantor=="5")
{
   $ls_kode_sumber_data = "3";	 
}

if ($ls_kode_sumber_data !="")
{
  $sql = "select nama_sumber_data from sijstk.kn_kode_sumber_data ".
          "where kode_sumber_data = '$ls_kode_sumber_data' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_nama_sumber_data = $row["NAMA_SUMBER_DATA"];
}	
$sql = "select kode_kantor, nama_kantor from sijstk.ms_kantor ".
"where kode_tipe not in ('0','1') ".							     									 	 
"start with kode_kantor = '$ls_kode_kantor' ".
"connect by prior kode_kantor = kode_kantor_induk";
$DB->parse($sql);
$DB->execute();
$ls_nama_kantor="";
if($row = $DB->nextrow())
    $ls_nama_kantor = $row['NAMA_KANTOR']    ;
$ls_kode_kantor_dst=$ls_kode_kantor;
$ls_nama_kantor_dst=$ls_nama_kantor;
$tview=false;
if($task=="View" || $task=="Edit")
{   
    $tview=true;
    $ls_kode_rtw = isset($_GET['dataid'])?$_GET['dataid']:'';
    $ls_kode_rtw = isset($_POST['no_agenda'])?$_POST['no_agenda']:$ls_kode_rtw;
    $ls_kode_klaim  = '';
    $ls_nm_tk       = '';
    $ls_nik         = '';
    $ls_npp         = '';
    $ls_nm_prs      = '';
    $ls_tgl_klaim   = '';
    $ls_kasus       = '';
    $ls_tgl_kecelakaan = '';
    $ls_tgl_lapor   = '';
    $ls_lokasi      = '';
    $ls_kode_kantor_dst = '';
    $ls_nama_kantor_dst = '';
    $ls_keterangan = '';
    $ls_no_agenda  = '';
    $ls_tgl_rekam   ='';
    $ls_tgl_selesai = '';
    $ls_status_agenda = '';
    $ls_tgl_agenda ='';
    $ls_petugas_agenda='';
    $ls_status_batal='';
    $ls_tgl_batal='';
    $ls_petugas_batal='';
    $ls_ket_batal='';
    $ls_status_selesai='';
    $ls_tgl_selesai='';
    $ls_petugas_selesai='';
    $ls_tgl_implemen='';
    $ls_petugas_implemen='';



    $sql = "select A.*,B.NAMA_PERUSAHAAN,B.NPP,C.NAMA_JENIS_KASUS,D.NAMA_LOKASI_KECELAKAAN,F.NAMA_KANTOR from ( 
            SELECT KODE_RTW_KLAIM,A.KODE_KLAIM, A.KODE_KANTOR_RTW,STATUS_RTW_KLAIM, A.KETERANGAN,to_char(A.TGL_SELESAI,'DD/MM/YYYY') TGL_SELESAI,
            to_char(A.TGL_REKAM,'DD/MM/YYYY') TGL_REKAM , NAMA_TK,NOMOR_IDENTITAS,to_char(TGL_KLAIM,'DD/MM/YYYY') TGL_KLAIM, 
            to_char(TGL_LAPOR,'DD/MM/YYYY') TGL_LAPOR,to_char(TGL_KEJADIAN,'DD/MM/YYYY') TGL_KEJADIAN,KODE_PERUSAHAAN, 
            KODE_JENIS_KASUS,KODE_LOKASI_KECELAKAAN, to_char(TGL_AGENDA_RTW,'DD/MM/YYYY') TGL_AGENDA_RTW, 
            to_char(TGL_IMPLEMEN_RTW,'DD/MM/YYYY') TGL_IMPLEMEN_RTW,to_char(A.TGL_BATAL,'DD/MM/YYYY') TGL_BATAL, A.STATUS_BATAL,STATUS_SELESAI,
            A.KET_BATAL,A.PETUGAS_AGENDA_RTW,A.PETUGAS_BATAL,A.PETUGAS_SELESAI
            FROM sijstk.PN_KLAIM B INNER JOIN sijstk.PN_RTW_KLAIM A ON (B.KODE_KLAIM = A.KODE_KLAIM) WHERE A.KODE_RTW_KLAIM='{$ls_kode_rtw}' 
        ) A left outer join sijstk.KN_PERUSAHAAN B on A.KODE_PERUSAHAAN=B.KODE_PERUSAHAAN left outer join sijstk.PN_KODE_JENIS_KASUS C on A.KODE_JENIS_KASUS=C.KODE_JENIS_KASUS left outer join sijstk.PN_KODE_LOKASI_KECELAKAAN D on A.KODE_LOKASI_KECELAKAAN=D.KODE_LOKASI_KECELAKAAN left outer join sijstk.MS_KANTOR F on A.KODE_KANTOR_RTW=F.KODE_KANTOR 
";
    $DB->parse($sql);//  echo $sql;
    $DB->execute();
    $kode_kantor="";
    if($row = $DB->nextrow())
    {
        $ls_kode_klaim  = $row['KODE_KLAIM'];
        $ls_nm_tk       = $row['NAMA_TK'];
        $ls_nik         = $row['NOMOR_IDENTITAS'];
        $ls_npp         = $row['NPP'];
        $ls_nm_prs      = $row['NAMA_PERUSAHAAN'];
        $ls_tgl_klaim   = $row['TGL_KLAIM'];
        $ls_kasus       = $row['NAMA_JENIS_KASUS'];
        $ls_tgl_kecelakaan = $row['TGL_KEJADIAN'];
        $ls_tgl_lapor   = $row['TGL_LAPOR'];
        $ls_lokasi      = $row['NAMA_LOKASI_KECELAKAAN'];
        $ls_kode_kantor_dst = $row['KODE_KANTOR_RTW'];
        $ls_nama_kantor_dst = $row['NAMA_KANTOR'];
        $ls_keterangan = $row['KETERANGAN'];
        $ls_no_agenda  = $row['KODE_RTW_KLAIM'];
        $ls_tgl_rekam   =$row['TGL_REKAM'];
        $ls_status_agenda = $row['STATUS_RTW_KLAIM'];
        $ls_tgl_agenda =$row['TGL_AGENDA_RTW'];
        $ls_petugas_agenda=$row['PETUGAS_AGENDA_RTW'];
        $ls_status_batal=$row['STATUS_BATAL'];
        $ls_tgl_batal=$row['TGL_BATAL'];
        $ls_ket_batal=$row['KET_BATAL'];
        $ls_petugas_batal=$row['PETUGAS_BATAL'];
        $ls_status_selesai=$row['STATUS_SELESAI'];
        $ls_tgl_selesai=$row['TGL_SELESAI'];
        $ls_petugas_selesai=$row['PETUGAS_SELESAI'];      
        $ls_tgl_implemen=$row['TGL_IMPLEMEN'];   
        $ls_petugas_implemen=$row['PETUGAS_IMPLEMEN'];   
    }
    
}
// get kode tipe faskes/blk
//$ls_kode_tipe='';
/*$sql = "select KODE_TIPE,NAMA_TIPE from {$schema}.tc_kode_tipe where status_nonaktif='T'";
$DB->parse($sql);
$DB->execute();
$kode_tipe="";
while($row = $DB->nextrow())
    $kode_tipe .= "<option ". ($row["KODE_TIPE"]==$ls_kode_tipe?" selected ":"") . "value=\"{$row["KODE_TIPE"]}\">{$row["NAMA_TIPE"]}</option>";
*/
 /*****end LOAD DATA*********************************/ ?>
<style>
.f_0{min-width:900px;margin-top:0; clear:both;}
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
.f_1{width:150px;text-align:right;float:left;clear:left;margin-bottom:2px;}
.f_2{width:300px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }   
.gw_selected {background-color:#FFC0FF;}
.gw_cursor_hand tr:hover {cursor:pointer important!;}
#listdata tr.a:hover {background-color:#e0e0FF;cursor:pointer;} 
.gw_tr {cursor:pointer;}
.gw_tr:hover {background-color:#C0C0FF;}

</style>
<?php /*****LOCAL JAVASCRIPT*************************************/?>                   
<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
var actionState=0;
function NewWindow(mypage,myname,w,h,scroll){
  var winl = (screen.width-w)/2;
  var wint = (screen.height-h)/2;
  var settings  ='height='+h+',';
      settings +='width='+w+',';
      settings +='top='+wint+',';
      settings +='left='+winl+',';
      settings +='scrollbars='+scroll+',';
      settings +='resizable=1';
      settings +='location=0';
      settings +='menubar=0';
  win=window.open(mypage,myname,settings);
  if(parseInt(navigator.appVersion) >= 4){win.window.focus();}
}

function fl_js_val_numeric(v_field_id)
{
    var c_val = window.document.getElementById(v_field_id).value;
        var number=/^[0-9]+$/;
        
    if ((c_val!='') && (!c_val.match(number)))
    {
        document.getElementById(v_field_id).value = '';				 
                window.document.getElementById(v_field_id).focus();       
                return false; 				 
    }
} 
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>
<br /><div align="center" id="notif" style="color:#ff4444;"></div>
<div style="width:900px;">
<div style="">
    <ul id="nav">
        <li><a href="#tab1" id="t1" class="active">Agenda JKK RTW</a></li>
            </ul>
</div>
<div id="konten">
<div class="f_0"> 
<form name="formreg" id="formreg" role="form" method="post">
<input type="hidden" id="formregact" name="formregact" value="<?=$task;?>" />
<fieldset><legend><b>Informasi  Klaim</b></legend>
<table border='0' width="100%">
    <tr>
        <td width="50%" valign="top">
            <div class="f_1" ><label for="kode_klaim">Kode Klaim :</label></div>
            <div class="f_2">
              <input type="text" id="kode_klaim" name="kode_klaim"  value="<?=$ls_kode_klaim;?>" style="width:140px;background-color:#ffff99;" readonly />
              <?php if(!$tview) {?>
              <a href="#" onclick="NewWindow('../ajax/pn5031_lov_klaim.php?fname=formreg&ffocus=kode_klaim&f1=kode_klaim&f2=nama_tk&f3=nik&f4=npp&f5=nama_prs&f6=tgl_klaim&f7=kasus&f8=tgl_kece&f9=tgl_lapor1&f10=lokasi','_lov_klaim',800,500,1)" >   
                    <img src="../../images/help.png" alt="Cari Kode Klaim" border="0" align="absmiddle"  ></a>
              <?php }?>
            </div>
            <div class="f_1" ><label for="nama_tk">Nama Tenaga Kerja :</label></div>
            <div class="f_2"><input type="text" id="nama_tk" name="nama_tk"  value="<?=$ls_nm_tk;?>" style="width:240px;background-color:#e9e9e9;"  readonly /></div>
            <div class="f_1" ><label for="nik">No Identitas :</label></div>
            <div class="f_2"><input type="text" id="nik" name="nik"  value="<?=$ls_nik;?>" style="width:140px;background-color:#e9e9e9;"  readonly /></div>
            <div class="f_1" ><label for="npp">NPP :</label></div>
            <div class="f_2"><input type="text" id="npp" name="npp"  value="<?=$ls_npp;?>" style="width:60px;background-color:#e9e9e9;"  readonly/>
            <input type="text" id="nama_prs" name="nama_prs"  value="<?=$ls_nm_prs;?>" style="width:200px;background-color:#e9e9e9;"  readonly/>
            </div>
        </td>
        <td valign="top">
            <div class="f_1" ><label for="tgl_klaim">Tgl Klaim :</label></div>
            <div class="f_2"><input type="text" id="tgl_klaim" name="tgl_klaim" value="<?=$ls_tgl_klaim;?>"   style="width:80px;background-color:#e9e9e9;"  readonly/></div>
            <div class="f_1" ><label for="kasus">Kasus :</label></div>
            <div class="f_2"><input type="text" id="kasus" name="kasus" value="<?=$ls_kasus;?>" style="width:240px;background-color:#e9e9e9;"  readonly/></div>
            <div class="f_1" ><label for="tgl_kece">Tgl. Kecelakaan :</label></div>
            <div class="f_2"><input type="text" id="tgl_kece" name="tgl_kece"  value="<?=$ls_tgl_kecelakaan;?>" style="width:80px;background-color:#e9e9e9;"  readonly/></div>
            <div class="f_1" ><label for="tgl_lapor1">Tgl Lapor Thp1 :</label></div>
            <div class="f_2"><input type="text" id="tgl_lapor1" name="tgl_lapor1"  value="<?=$ls_tgl_lapor;?>" style="width:80px;background-color:#e9e9e9;"  readonly/></div>
            <div class="f_1" ><label for="lokasi">Lokasi Kecelakaan :</label></div>
                <div class="f_2"><input type="text" id="lokasi" name="lokasi"   value="<?=$ls_lokasi;?>" style="width:240px;background-color:#e9e9e9;"  readonly/></div>
        </td>
    </tr>
</table>
</fieldset>
<fieldset><legend><b>Agenda JKK RTW</b></legend>
    <table border='0' width="100%">
        <tr>
            <td width="50%" valign="top">
            <div class="f_1" ><label for="no_agenda">No Agenda RTW:</label></div>
                <div class="f_2"><input type="text" id="no_agenda" name="no_agenda" maxlength="100"  value="<?=$ls_no_agenda;?>" style="width:140px;background-color:#e9e9e9;" readonly/></div>
                <div class="f_1" ><label for="kode_dst_kantor">Kantor Tujuan:</label></div>
                <div class="f_2"><input type="text" id="kode_dst_kantor" name="kode_dst_kantor" maxlength="40"  value="<?=$ls_kode_kantor_dst;?>" style="width:40px;background-color:#e9e9e9;" readonly /><input type="text" id="nama_dst_kantor" name="nama_dst_kantor"  value="<?=$ls_nama_kantor_dst;?>" style="width:200px;background-color:#e9e9e9;" readonly/>
                </div>
                <div class="f_1" ><label for="status_agenda">Status Agenda RTW:</label></div>
                <div class="f_2"><input type="text" name="status_agenda" value="<?php echo ($_REQUEST['task']=='New')?"BARU":$ls_status_agenda;?>" style="width:120px;background-color:#e9e9e9;" readonly/></div>
                <div class="f_1" ><label for="tgl_agenda">Tgl Agenda :</label></div>
                <div class="f_2"><input type="text" id="tgl_agenda" name="tgl_agenda" maxlength="100" value="<?=$ls_tgl_agenda;?>" style="width:80px;background-color:#e9e9e9;"  readonly/></div>
                <div class="f_1" ><label for="petugas_agenda">Petugas Agenda :</label></div>
                <div class="f_2"><input type="text" id="petugas_agenda" name="petugas_agenda"  value="<?=$ls_petugas_agenda;?>" style="width:200px;background-color:#e9e9e9;" /></div>
                <div class="f_1" ><label for="tgl_implemen">Tgl Implementasi :</label></div>
                <div class="f_2"><input type="text" id="tgl_implemen" name="tgl_implemen" maxlength="100" value="<?=$ls_tgl_implemen;?>" style="width:80px;background-color:#e9e9e9;"  readonly/></div>
                <div class="f_1" ><label for="petugas_implemen">Petugas Implementasi :</label></div>
                <div class="f_2"><input type="text" id="petugas_implemen" name="petugas_implemen"  value="<?=$ls_petugas_implemen;?>" style="width:200px;background-color:#e9e9e9;" /></div>
            </td>
            <td width="50%" valign="top">
                <div class="f_1" ><label for="keterangan">Keterangan :</label></div>
                <div class="f_2"><textarea id="keterangan" name="keterangan"  row="2" style="width:250px" <?php echo ($tview)?"readonly":"";?>><?=$ls_keterangan;?></textarea></div>
                <div class="f_1" ><label for="status_batal">Status Batal:</label></div>
                <div class="f_2"><input type="text" name="status_batal" value="<?=$ls_status_batal;?>" style="width:40px;background-color:#e9e9e9;" readonly/></div>
                <div class="f_1" ><label for="tgl_batal">Tgl Batal :</label></div>
                <div class="f_2"><input type="text" id="tgl_batal" name="tgl_batal" maxlength="100" value="<?=$ls_tgl_batal;?>" style="width:80px;background-color:#e9e9e9;"  readonly/></div>
                <div class="f_1" ><label for="petugas_batal">Petugas Batal :</label></div>
                <div class="f_2"><input type="text" id="petugas_batal" name="petugas_batal"  value="<?=$ls_petugas_batal;?>" style="width:200px;background-color:#e9e9e9;" /></div>
                <div class="f_1" ><label for="status_selesai">Status Selesai:</label></div>
                <div class="f_2"><input type="text" name="status_selesai" value="<?=$ls_status_selesai;?>" style="width:40px;background-color:#e9e9e9;" readonly/></div>
                <div class="f_1" ><label for="tgl_selesai">Tgl Selesai :</label></div>
                <div class="f_2"><input type="text" id="tgl_selesai" name="tgl_selesai" maxlength="100" value="<?=$ls_tgl_selesai;?>" style="width:80px;background-color:#e9e9e9;"  readonly/></div>
                <div class="f_1" ><label for="petugas_selesai">Petugas Selesai :</label></div>
                <div class="f_2"><input type="text" id="petugas_selesai" name="petugas_selesai"  value="<?=$ls_petugas_selesai;?>" style="width:200px;background-color:#e9e9e9;" /></div>
            </td>
        </tr>
    </table>
</fieldset>
</form> 
</div>
</div>
</div>
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
function saveData()
{
    preload(true);
    $("#notif").html('');
    $.ajax({
        type: 'POST',
        url: "../ajax/pn5031_action.php?"+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){ 
            preload(false); 
            //console.log(ajaxdata);	
            jdata = JSON.parse(ajaxdata);
            if(jdata.ret == '0')
            {						 		 						 						 
                window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...','');
                window.location='pn5031.php?mid=<?=$mid;?>';
                
            }else 
            {
                $("#notif").html(jdata.msg);
            }
        }
    });
}
function enadisaInput(obj_jquery,ena)
{
if(ena) obj_jquery.prop('disabled', false);
else obj_jquery.prop('disabled', true);
}
function cek_dataNew()
{ 
if($("#kode_klaim").val()==''||$("#kode_src_data").val()==''||$("#kode_dst_kantor").val()==''||$("#kode_to").val()=='')
    return false;
else
    return true;
}

$(document).ready(function(){ 
    $("#btn_save").click(function(){
        if(!cek_dataNew())
            alert("Lengkapi isian mandatory field terlebih dulu!");
        <?php 
            if($task_code == "New") echo "else if(confirm('Save data new agenda JKK RTW?')) saveData();";
            //if($task_code == "Daftar") echo "else if(confirm('Daftarkan Potensi Faskes/ BLK?')) saveData();"; 
        ?>
    }); 
});
</script> 
<?php /*****end LOCAL JAVASCRIPT*********************************/?>