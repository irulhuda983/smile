<?php /*****LOAD DATA*************************************/
$schema="sijstk";
$task= isset($_GET['task'])?$_GET['task']:'';
$ls_kode_rtw = isset($_GET['dataid'])?$_GET['dataid']:'';  
$noform= isset($_GET['noform'])?$_GET['noform']:'1';
$noform= isset($_POST['noform'])?$_POST['noform']:$noform;

$global_readonly="";
if($task=="View") $global_readonly=" readonly ";
$status_readonly=$global_readonly;

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
"start with kode_kantor = '$gs_kantor_aktif' ".
"connect by prior kode_kantor = kode_kantor_induk";
$DB->parse($sql);
$DB->execute();
$kode_kantor="";
while($row = $DB->nextrow())
    $kode_kantor .= "<option ". ($row["KODE_KANTOR"]==$ls_kode_kantor?" selected ":"") . "value=\"{$row["KODE_KANTOR"]}\">{$row["KODE_KANTOR"]} - {$row["NAMA_KANTOR"]}</option>";
$tview=false;
if($task=="View" || $task=="Edit")
{   
    $tview=true;  
}  //   echo $sql;
     /*****end LOAD DATA*********************************/ 
$jml_data=array(
    "infodasar"=>0,
    "penilaian"=>0,
    "perencanaan"=>0,
    "penempatan"=>0,
    "monitoring"=>0,
    "evaluasi"=>0,
    "kecacatan"=>0,
    "lampiran"=>0,
    "all"=>0,
    "status_rtw_klaim"=>"",
    "status"=>"",
    "lengkap"=>0
);
$sql="select  jml_infodasar,jml_penilaian,jml_perencanaan,jml_penempatan,jml_monitoring,jml_evaluasi,jml_kecacatan,jml_lampiran,
jml_infodasar+jml_penilaian+jml_perencanaan+jml_penempatan+jml_monitoring+jml_evaluasi+jml_kecacatan+jml_lampiran jml,
case when jml_infodasar>0 and jml_penilaian>0 and jml_perencanaan>0 and jml_penempatan>0 and jml_monitoring>0 
    and jml_evaluasi>0 and jml_kecacatan>0 and jml_lampiran>0 then 1 else 0 end lengkap,
    STATUS_RTW_KLAIM,STATUS
from
(select count(*) jml_infodasar from pn_rtw_infodasar where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_penilaian from pn_rtw_penilaian where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_perencanaan from pn_rtw_perencanaan where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_penempatan from pn_rtw_penempatan where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_monitoring from pn_rtw_monitoring where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_evaluasi from pn_rtw_evaluasi where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_kecacatan from pn_rtw_kecacatan where kode_rtw_klaim='{$ls_kode_rtw}'),
(select count(*) jml_lampiran from pn_rtw_lampiran where kode_rtw_klaim='{$ls_kode_rtw}'),
(select STATUS_RTW_KLAIM,STATUS from pn_rtw_klaim where kode_rtw_klaim='{$ls_kode_rtw}')";
$DB->parse($sql); //echo$sql;
if($DB->execute())
    if($row = $DB->nextrow())
        $jml_data=array(
            "infodasar"=>$row['JML_INFODASAR'],
            "penilaian"=>$row['JML_PENILAIAN'],
            "perencanaan"=>$row['JML_PERENCANAAN'],
            "penempatan"=>$row['JML_PENEMPATAN'],
            "monitoring"=>$row['JML_MONITORING'],
            "evaluasi"=>$row['JML_EVALUASI'],
            "kecacatan"=>$row['JML_KECACATAN'],
            "lampiran"=>$row['JML_LAMPIRAN'],
            "all"=>$row['JML'],
            "status_rtw_klaim"=>$row['STATUS_RTW_KLAIM'],
            "status"=>$row['STATUS'],
            "lengkap"=>$row['LENGKAP']
        ); //print_r($jml_data);

if($task!='View')
{
    if($jml_data['status_rtw_klaim']=='SR2' || $jml_data['status_rtw_klaim']=='SR3' || $jml_data['status_rtw_klaim']=='SR4')
        $global_readonly = " readonly ";
    if($jml_data['status_rtw_klaim']=='SR4')
        $status_readonly = " readonly ";
    else
    {
        $status_readonly = "";
    }
}
//echo 'glob:'.$global_readonly;
//echo 'status:'.$status_readonly;
?>

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
.gw_tr:hover {background-color:#CADDE6;}
.gw_btn {
    cursor:pointer;
  background: #3b8fc7;
  background-image: -webkit-linear-gradient(top, #3b8fc7, #2980b9);
  background-image: -moz-linear-gradient(top, #3b8fc7, #2980b9);
  background-image: -ms-linear-gradient(top, #3b8fc7, #2980b9);
  background-image: -o-linear-gradient(top, #3b8fc7, #2980b9);
  background-image: linear-gradient(to bottom, #3b8fc7, #2980b9);
  -webkit-border-radius: 5px;
  -moz-border-radius: 5px;
  border-radius: 5px;
  font-family: Arial;
  color: #ffffff;
  font-size: 12px;
  padding: 2px 4px 2px 4px;
  text-decoration: none;
}

.gw_btn:hover {
  background: #3cb0fd;
  background-image: -webkit-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -moz-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -ms-linear-gradient(top, #3cb0fd, #3498db);
  background-image: -o-linear-gradient(top, #3cb0fd, #3498db);
  background-image: linear-gradient(to bottom, #3cb0fd, #3498db);
  text-decoration: none;
}
.gw_btn_noaktif {
    background: #abbcc7;
    background-image: -webkit-linear-gradient(top, #abbcc7, #6f8896);
    background-image: -moz-linear-gradient(top, #abbcc7, #6f8896);
    background-image: -ms-linear-gradient(top, #abbcc7, #6f8896);
    background-image: -o-linear-gradient(top, #abbcc7, #6f8896);
    background-image: linear-gradient(to bottom, #abbcc7, #6f8896);
    -webkit-border-radius: 5px;
    -moz-border-radius: 5px;
    border-radius: 5px;
    font-family: Arial;
    color: #ffffff;
    font-size: 12px;
    padding: 2px 4px 2px 4px;
    text-decoration: none;
}
.gw_tab_title {
  background: #3498db;
  -webkit-border-top-right-radius: 10px;
  -moz-border-radius-top-right: 10px;
  border-top-right-radius: 10px;
  -webkit-border-top-left-radius: 5px;
  -moz-border-radius-top-left: 5px;
  border-top-left-radius: 5px;
  margin-bottom:0px;
  padding:5px 10px;
  font-size:12px;
  font-weight:bold;
  color:#ffffff;
}
.gw_tab_konten{
    min-width:800px;
    margin-top:5px;
    border:1px solid #3498db;
    padding:10px;
    background-color:#FcFcFc;
}
.gw_tbl{border-collapse: collapse;}
.gw_tbl table, .gw_tbl th, .gw_tbl td {border: 1px solid #C0C0C0;}
.gw_tbl tr:nth-child(even) {background-color: #ebebeb;}
</style>
<?php /*****LOCAL JAVASCRIPT*************************************/?>                   
<script type="text/javascript">

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
    return true;
} 
</script>
<?php /*****end LOCAL JAVASCRIPT*********************************/?>
<br />
<div align="left">
    <input type="button" id="myBtn1" class="gw_btn_noaktif" value="Informasi Dasar<?php echo $jml_data['infodasar']==0?"*":"";?>" onclick="show_konten(this,1);" />
    <?php if($jml_data['infodasar']>0){?>
    <input type="button" id="myBtn2" class="gw_btn" value="Penilaian<?php echo $jml_data['penilaian']==0?"*":"";?>"  onclick="show_konten(this,2);"/>
    <input type="button" id="myBtn3" class="gw_btn" value="Perencanaan<?php echo $jml_data['perencanaan']==0?"*":"";?>"  onclick="show_konten(this,3);"/>
    <input type="button" id="myBtn4" class="gw_btn" value="Penempatan Kerja<?php echo $jml_data['penempatan']==0?"*":"";?>"  onclick="show_konten(this,4);"/>
    <input type="button" id="myBtn5" class="gw_btn" value="Monitoring<?php echo $jml_data['monitoring']==0?"*":"";?>"  onclick="show_konten(this,5);"/>
    <input type="button" id="myBtn6" class="gw_btn" value="Evaluasi<?php echo $jml_data['evaluasi']==0?"*":"";?>"  onclick="show_konten(this,6);"/>
    <input type="button" id="myBtn7" class="gw_btn" value="Status Kecacatan<?php echo $jml_data['kecacatan']==0?"*":"";?>"  onclick="show_konten(this,7);"/>
    <input type="button" id="myBtn8" class="gw_btn" value="Lampiran<?php echo $jml_data['lampiran']==0?"*":"";?>"  onclick="show_konten(this,8);"/>
    <!--
    <input type="button" id="myBtn9" class="gw_btn" value="FAQ"  onclick="show_konten(this,9);"/>
    <input type="button" id="myBtn10" class="gw_btn" value="Activitas"  onclick="show_konten(this,10);"/>
    <input type="button" id="myBtn11" class="gw_btn" value="More Info"  onclick="show_konten(this,11);"/>
    <input type="button" id="myBtn12" class="gw_btn" value="Audit Trail"  onclick="show_konten(this,12);"/>
    -->
    <input type="button" id="myBtn13" class="gw_btn" value="Finalisasi RTW"  onclick="show_konten(this,13);"/>
    <?php }?>
    
</div>
<br />
<div>
<span class="gw_tab_title"  id="t1" >Informasi Dasar</span>
</div>
<div class="gw_tab_konten">
    <div id="tab1" class="f_0" style="display: none;"> 
        <?php include("pn5032_form_info_dasar.php");?>
    </div>
    <?php if($jml_data['infodasar']>0){?>
    <div id="tab2" class="f_0" style="display: none;">
        <?php include("pn5032_form_penilaian.php");?>
    </div>
    <div id="tab3" class="f_0" style="display: none;">
        <?php include("pn5032_form_perencanaan.php");?>
    </div>
    <div id="tab4" class="f_0" style="display: none;">
        <?php include("pn5032_form_penempatan_kerja.php");?>
    </div>
    <div id="tab5" class="f_0" style="display: none;">
        <?php include("pn5032_form_monitoring.php");?>
    </div>
    <div id="tab6" class="f_0" style="display: none;">
        <?php include("pn5032_form_evaluasi.php");?>
    </div>
    <div id="tab7" class="f_0" style="display: none;">
        <?php include("pn5032_form_status_kecacatan.php");?>
    </div>
    <div id="tab8" class="f_0" style="display: none;">
        <?php include("pn5032_form_lampiran.php");?>
    </div>
    <div id="tab13" class="f_0" style="display: none;">
        <?php include("pn5032_form_finalize.php");?>
    </div>
    <?php }?>
</div>         
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;
function lov_subData(par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2; 
    $.ajax({
        type: 'GET',
        url: "../ajax/pn5032_lov.php?"+Math.random(),
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){ console.log(ajaxdata);
            if(par_callFunc=='InformasiDasarDiagnosa') get_InformasiDasarDiagnosa_id(ajaxdata);	
            if(par_callFunc=='KondisiTK') get_Kondisi_id(ajaxdata);	
            if(par_callFunc=='TindakanMedisTK') get_TindakanMedis_id(ajaxdata);	
            if(par_callFunc=='Rehabilitasi') get_Rehabilitasi_id(ajaxdata);	
            if(par_callFunc=='DukunganKeluarga') get_DukunganKeluarga_id(ajaxdata);	
            if(par_callFunc=='Upah') get_Upah_id(ajaxdata);	
            if(par_callFunc=='Pendidikan') get_Pendidikan_id(ajaxdata);	
            if(par_callFunc=='BebanKerja') get_BebanKerja_id(ajaxdata);	
            if(par_callFunc=='PAK') get_PAK_id(ajaxdata);	
            
            <?php if($jml_data['infodasar']>0){?>
            if(par_callFunc=='PerencanaanInfo') get_PerencanaanInfo(ajaxdata);	
            if(par_callFunc=='PenempatanKerja') get_PenempatanKerja_pk(ajaxdata);	
            if(par_callFunc=='SKLain') get_SKLain_sk(ajaxdata);	
            if(par_callFunc=='Lampiran') get_Lampiran(ajaxdata);	
            if(par_callFunc=='BebanPenAwal') get_Beban_Pen_Awal(ajaxdata);
            if(par_callFunc=='PAKPenAwal') get_PAK_Pen_Awal(ajaxdata);
            if(par_callFunc=='BebanPenPk') get_Beban_Pen_Pk(ajaxdata);
            if(par_callFunc=='PAKPenPk') get_PAK_Pen_Pk(ajaxdata);
            if(par_callFunc=='PenNilA') get_Pen_A(ajaxdata);
            if(par_callFunc=='PenNilB') get_Pen_B(ajaxdata);
            if(par_callFunc=='PenNilC') get_Pen_C(ajaxdata);
            <?php }?>
        }
    });
}
var curBtn=1;
$(document).ready(function(){ 
    <?php if(!$ro_data){?>
   $("#btn_save").click(function(){
       switch(curBtn){
           case 1: saveInformasiDasar(); break;
           <?php if($jml_data['infodasar']>0){?>
           case 2: savePenilaian(); break;
           case 3: savePerencanaan(); break;
           case 4: savePenempatanKerja(); break;
           case 7: saveStatusKecacatan(); break;
           case 13: saveStatusRTW(); break;
           <?php }?>
       }
   });
   <?php if($global_readonly!="") {?>
        $("#div_btnsave").hide();
    <?php }?>
<?php } ?>
   show_konten(document.getElementById("myBtn"+<?=$noform;?>),<?=$noform;?>);
});
function show_konten(oBtn,noBtn)
{
    document.getElementById("myBtn"+curBtn).setAttribute('class', 'gw_btn');
    document.getElementById("myBtn"+curBtn).disabled = false; 
    document.getElementById("t1").innerHTML=oBtn.value;
    oBtn.setAttribute('class', 'gw_btn_noaktif');
    oBtn.disabled=true;
    $("#tab"+curBtn).hide();
    $("#tab"+noBtn).fadeIn();
    curBtn=noBtn;
    <?php if($jml_data['infodasar']>0){?>
    if(noBtn==5)
        loadDataMonitoring();
    else if(noBtn==6)
        loadDataEvaluation();
    else if(noBtn==8)
        loadDataLampiran();
    else if(noBtn==2) 
        window.LoadDataHasilPenilaian();
        <?php if($status_readonly!="") {?>
        $("#div_btnsave").hide();
        <?php }else if($global_readonly!="" && $status_readonly=='') {?>
        if(noBtn==13)
            $("#div_btnsave").show();
        else
            $("#div_btnsave").hide();
        <?php }?>
    <?php }?>
}
<?php if(!$ro_data){?>
function saveData(p_url,p_data,p_urlcallback)
{
    preload(true);
    $("#notif").html('');
    $.ajax({
        type: 'POST',
        url: p_url+'&math='+Math.random(),
        data: p_data,
        success: function(ajaxdata){ 
            preload(false); 
            console.log(ajaxdata);	
            jdata = JSON.parse(ajaxdata);
            if(jdata.ret == '0')
            {
                window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...','');
                window.location=p_urlcallback;
                
            }else 
            {
                $("#notif").html(jdata.msg);
            }
        }
    });
}
function deleteData(par_url,par_callFunc,par_ajaxFunc,par_ajaxKey1,par_ajaxKey2)
{
    preload(true);
    var ajax_Key1 = (par_ajaxKey1 == undefined) ? "":par_ajaxKey1;
    var ajax_Key2 = (par_ajaxKey2 == undefined) ? "":par_ajaxKey2;// $("#lamp_file1").val(par_url);
    $.ajax({
        type: 'POST',
        url: par_url,
        data: {f:par_ajaxFunc,key1:ajax_Key1,key2:ajax_Key2},
        success: function(ajaxdata){ console.log(ajaxdata);
            preload(false);
            if(par_callFunc=='delDiagnosa') window.get_InformasiDasarDiagnosa(par_ajaxKey1);	
            <?php if($jml_data['infodasar']>0){?>
            if(par_callFunc=='delPerencanaan') window.get_PerencanaanInfoData(par_ajaxKey1);	
            if(par_callFunc=='delPerencanaan') window.get_PerencanaanInfoData(par_ajaxKey1);	
            if(par_callFunc=='delLampiran') window.loadDataLampiran();	
            if(par_callFunc=='delMonitoring') window.ReloadDataMonitoring();	
            if(par_callFunc=='delEvaluasi') window.ReloadDataEvaluation();
            <?php }?>
        }
    });
}
<?php }?>
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

</script> 
<?php /*****end LOCAL JAVASCRIPT*********************************/?>