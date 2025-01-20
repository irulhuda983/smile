<?php /*****LOAD DATA*************************************/
$schema="sijstk";
$task= isset($_GET['task'])?$_GET['task']:'';
$ls_kode = isset($_GET['dataid'])?$_GET['dataid']:'';  
$noform= isset($_GET['noform'])?$_GET['noform']:'1';
$noform= isset($_POST['noform'])?$_POST['noform']:$noform;

$ls_date=date('d/m/Y');

$global_readonly="";
$ro_data=false;
if($task=="View") {
    $global_readonly=" readonly ";
    $ro_data=true;
}
$status_readonly=$global_readonly;

$USER         = $_SESSION["USER"];
$KODE_KANTOR  = $_SESSION['gs_kantor_aktif'];
$tview=false;

$sql="select a.kode_perusahaan,b.nama_perusahaan,a.status_aktif,decode(a.status_aktif,'Y',to_char(tgl_aktif,'dd/mm/YYYY'),to_char(tgl_nonaktif,'dd/mm/yyyy')) tgl_aktif,
    to_char(a.tgl_rekam,'dd/mm/yyyy') tgl_rekam,a.petugas_rekam
    from sijstk.pn_rtw_prs_pendukung a,sijstk.kn_perusahaan b where a.kode_perusahaan=b.kode_perusahaan
    and a.kode_perusahaan='{$ls_kode}'";
$DB->parse($sql);// echo $sql;
$ada=false;
if($DB->execute())
    if($row = $DB->nextrow())
    {
        $ada=true;
        $ls_nama=$row['NAMA_PERUSAHAAN'];
        $ls_status=$row['STATUS_AKTIF'];
        $ls_date=$row['TGL_AKTIF'];
        $ls_tgl_rekam=$row['TGL_REKAM'];
        $ls_petugas_rekam=$row['PETUGAS_REKAM'];
    }

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
.f_2{width:270px;text-align:left;margin-left:2px;float:left;margin-bottom:2px;  }   
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
    <input type="button" id="myBtn1" class="gw_btn_noaktif" value="Perusahaan Pendukung RTW" onclick="show_konten(this,1);" />
    <?php if($ada){?>
    <input type="button" id="myBtn2" class="gw_btn" value="Dokumen Pendukung"  onclick="show_konten(this,2);"/>
    <?php } ?>
</div>
<br />
<div>
<span class="gw_tab_title"  id="t1" >Perusahaan Pendukung RTW</span>
</div>
<div class="gw_tab_konten">
    <div id="tab1" class="f_0" style="display: none;"> 
        <form name="formreg" id="formreg">
            <input type="hidden" name="formregact" value="SavePrs" />
        <table width="100%" border="0">
            <tr>
                <td width="50%" valign="top">
                    <div class="f_1" ><label for="kode_perusahaan">Kode Perusahaan :</label></div>
                    <div class="f_2">
                      <input type="text" id="kode_perusahaan" name="kode_perusahaan"  value="<?=$ls_kode;?>" style="width:140px;background-color:#ffff99;" readonly />
                                    <a href="#" onclick="NewWindow('../ajax/<?=$php_file_name;?>_lov_perusahaan.php?fname=formreg&ffocus=kode_perusahaan&f1=kode_perusahaan&f2=nama_perusahaan','_lov_perusahaan',800,500,1)" />   
                            <img src="../../images/help.png" alt="Cari Kode Perusahaan" border="0" align="absmiddle"  ></a>
                                  </div>
                    <div class="f_1" ><label for="nama_perusahaan">Nama Perusahaan :</label></div>
                    <div class="f_2"><input type="text" id="nama_perusahaan" name="nama_perusahaan"  value="<?=$ls_nama;?>" style="width:240px;background-color:#e9e9e9;"  readonly /></div>
                    <div class="f_1" ><label for="status_aktif">Aktif Mendukung RTW :</label></div>
                    <div class="f_2">
                        <select id="status_aktif" name="status_aktif" style="background-color:#ffff99">
                            <option value="Y" <?php echo $ls_status=='Y'?'selected':'';?>>Ya</option>
                            <option value="T" <?php echo $ls_status=='T'?'selected':'';?>>Tidak</option>
                        </select>
                    <div>
                </td>
                <td valign="top">
                    <div class="f_1" ><label for="tgl_status">Tgl. Status :</label></div>
                    <div class="f_2">
                        <input type="text" id="tgl_status" name="tgl_status" style="width:100px;background-color:#ffffff;" value="<?=$ls_date;?>" readonly/><input type="image" align="top" onclick="return showCalendar('tgl_status', 'dd-mm-y');" src="../../images/calendar.gif"  />
                    </div>
                    <div class="f_1" ><label for="tanggal_rekam">Tanggal Rekam :</label></div>
                    <div class="f_2"><input type="text" id="tanggal_rekam" name="tanggal_rekam"  value="<?=$ls_tgl_rekam;?>" style="width:110px;background-color:#e9e9e9;"  readonly /></div>
                    <div class="f_1" ><label for="petugas_rekam">Petugas Rekam :</label></div>
                    <div class="f_2"><input type="text" id="petugas_rekam" name="petugas_rekam"  value="<?=$ls_petugas_rekam;?>" style="width:240px;background-color:#e9e9e9;"  readonly /></div>
                    
                </td>
            </tr>
        </table>
        </form>
    </div>
    <?php if($ada){?>
    <div id="tab2" class="f_0" style="display: none;">
        <span style="color:#FF0000" id="lamp_error"></span>
        <iframe name="iframe_ulamp" id="iframe_ulamp"  src="#" style="postion:fixed;left:-9999;top:-9999;display:none"></iframe>
        <form id="form_lampiran" name="iframe_ulamp" target="iframe_ulamp"  action="../ajax/<?=$php_file_name;?>_action.php" method="POST" enctype="multipart/form-data"> 
            <?php if(!$ro_data){?>
            <fieldset><legend><b>Lampiran</b></legend>
            *File: <input type="file" id="lamp_file" name="lamp_file" />
            Keterangan: <input type="text" name="lamp_ket" style="width:240px;"/>
            <input type="button" value="Upload Dokumen" id="lamp_upload" />
            <input type="hidden" name="formregact" value="uploaddoc" />
            <input type="hidden" id="lamp_kode_perusahaan" name="lamp_kode_perusahaan" value="<?=$ls_kode;?>" />
            <?php }?>
            <table  id="listdata" cellspacing="0" border="0" bordercolor="#C0C0C0" background-color= "#ffffff" width="100%">
            <thead>
                <tr>
                    <th colspan="12"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>    
                </tr>
                <tr bgcolor="#F0F0F0">
                <th class="align-left">Action</th>
                <th class="align-left">Nama File</th>
                <th class="align-left">Keterangan</th>
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

    </div>
    <?php }?>
</div>         
<?php /*****LOCAL JAVASCRIPT*************************************/?>
<script type="text/javascript">
var actionState=0;
var curBtn=1;
var arrField= [
        ['kode_perusahaan','Kode Perusahaan',false,'',0,20],
        ['nama_perusahaan','Nama Perusahaan',false,'',0,300],
        ['tgl_status','Tanggal Aktif',false,'',0,12]
    ];

$(document).ready(function(){ 
    <?php if(!$ro_data){?>
    $("#btn_save").click(function(){
        if(checkFieldArray(arrField)==0)
            if(confirm('Save new data?'))    
                saveData();
    });
    initiateUploadLampiran();
    loadDataLampiran();
    <?php }else{?>
    $("#div_btnsave").hide();
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
    <?php if(!$ro_data){?>
    if(noBtn==2)
        $("#div_btnsave").hide();
    else
        $("#div_btnsave").fadeIn();
    <?php } ?>
}
<?php if(!$ro_data){?>
function saveData()
{
    preload(true);
    $.ajax({
        type: 'POST',
        url: '../ajax/<?=$php_file_name;?>_action.php?math='+Math.random(),
        data: $("#formreg").serialize(),
        success: function(ajaxdata){ 
            preload(false); 
            console.log(ajaxdata);	
            jdata = JSON.parse(ajaxdata);
            if(jdata.ret == '0')
            {
                window.parent.Ext.notify.msg('Penyimpanan data berhasil, session dilanjutkan...','');
                window.location="<?=$php_file_name;?>.php?task=Edit&dataid="+$("#kode_perusahaan").val();
            }else 
            {
                alert(jdata.msg);
            }
        }
    });
}
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

function initiateUploadLampiran()
{
    $('#form_lampiran').submit(function(){
        var response;
        preload(true);
        var frame_lampiran=$("#iframe_ulamp").load(function(){
            preload(false);
            response=frame_lampiran.contents().find('body'); 
            if(response.html()!='')
                alert(response.html());
            else
            {
                window.parent.Ext.notify.msg('Sukses Upload dokumen!','');
                loadDataLampiran();
            }
            frame_lampiran.unbind("load");
            setTimeout(function(){ response.html(''); },1);
        });
    });
    $("#lamp_upload").click(function(){ 
        if($("#lamp_file").val()=='')
            alert('Silahkan pilih file yang akan di unggah!');
        else if(Right(String($("#lamp_file").val()),3).toUpperCase()!='PDF')
            alert('Hanya file bertipe pdf yang bisa di unggah!');
        else 
        {
            if(!confirm('Upload file?')) return 0;
                else $('#form_lampiran').submit();
        }
    });
}
function loadDataLampiran()
{
    $.ajax({
        type: 'GET',
        url: "../ajax/<?=$php_file_name;?>_query.php?"+Math.random(),
        data: {TYPE:'getlampiran',KODE:'<?=$ls_kode;?>'},
        success: function(ajaxdata){
             console.log(ajaxdata);
            $("#listdata_lampiran").html(ajaxdata);
        }
    });
}
function deleteLampiran(p_kode,p_no)
{
    if(!confirm('Hapus File Lampiran?'))
        return false;
    preload(true);
    $.ajax({
        type: 'POST',
        url: '../ajax/<?=$php_file_name;?>_action.php?math='+Math.random(),
        data: {formregact:'deletelampiran',kode_perusahaan:'<?=$ls_kode;?>',nourut:p_no},
        success: function(ajaxdata){ 
            preload(false); 
            console.log(ajaxdata);    
            jdata = JSON.parse(ajaxdata);
            if(jdata.ret == '0')
            {
                window.parent.Ext.notify.msg('Penghapusan data berhasil, session dilanjutkan...','');
                loadDataLampiran();
            }else 
            {
                alert(jdata.msg);
            }
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