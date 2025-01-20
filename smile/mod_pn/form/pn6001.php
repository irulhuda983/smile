<?php
$pagetype = "form";
$form = isset($_REQUEST["form"]) && !empty($_REQUEST["form"]) ? $_REQUEST["form"] : NULL;
$gs_pagetitle = !empty($form) && $form == 'tdl' ? "PN6002 - Tindak Lanjut Koreksi Data Pelayanan" : "PN6001 - KOREKSI DATA INTERNAL";
// 12-02-2024 penyesuaian nama page untuk data yang dilihat dari PN6002
require_once "../../includes/header_app_nosql.php";	
$mid = $_REQUEST["mid"];

$gs_kantor_aktif = $_SESSION['kdkantorrole'];
$ses_reg_role = $_SESSION['regrole'];
$ls_kode_jenis_agenda_detil_selected    = !isset($_GET['taskno']) ? $_POST['taskno'] : $_GET['taskno'];
$ls_rg_kategori = !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
  if ($ls_rg_kategori=="")
  {
    $ls_rg_kategori = "ALL";
  }
/* ============================================================================
Ket : Form ini digunakan untuk melakukan pembuatan koreksi data pelayanan.

Hist: - 17/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/

 /*------------------------------------------------------------------------*/
  /* PF A.0.1 Create Initial Value 
  --------------------------------------------------------------------------*/
  //Kantor -------------------------------------------------------------------
  $ls_kode_kantor = isset($ls_kode_kantor) ? $ls_kode_kantor : $gs_kantor_aktif;
  if($ls_kode_kantor=="")
  {
    $ls_kode_kantor =  $gs_kantor_aktif;
  } 
  //Sumber Data : sesuai kantor login ----------------------------------------
  $sql = "select kode_tipe from sijstk.ms_kantor where kode_kantor = '$ls_kode_kantor' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_tipe_kantor = $row["KODE_TIPE"];
  
  if ($ls_tipe_kantor=="0")
  {
    $ls_kode_sumber_data = "1";
  }else if ($ls_tipe_kantor=="1")
  {
    $ls_kode_sumber_data = "2";
  }
  else if ($ls_tipe_kantor=="2")
  {
    $ls_kode_sumber_data = "2";
  }
  else if ($ls_tipe_kantor=="3" || $ls_tipe_kantor=="4" || $ls_tipe_kantor=="5")
  {
    $ls_kode_sumber_data = "3";  
  }
  
  if ($ls_kode_sumber_data !="")
  {
    $sql = "SELECT SUBSTR(NAMA_SUMBER_DATA,8,10) NAMA_SUMBER_DATA FROM SIJSTK.KN_KODE_SUMBER_DATA 
           WHERE KODE_SUMBER_DATA = '$ls_kode_sumber_data'";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_nama_sumber_data = $row["NAMA_SUMBER_DATA"];
  } 

  // if($_REQUEST['taskno']!=''){
  //     $ls_kode_jenis_agenda_detil = $_REQUEST['taskno'];
  //     //echo($ls_kode_jenis_agenda_detil);
  // } 

?>
<form name="formreg" id="formreg" role="form" method="post" accept-charset="utf-8" enctype="multipart/form-data">
	<!-- LOCAL JAVASCRIPTS -->		
  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
  <script type="text/javascript" src="../../javascript/numeral.min.js"></script>
  <script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
  <script language="javascript">
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

    function NewWindowPerihal(mypage,myname,w,h,scroll){
      var task = '<?=$_GET["task"];?>'
      var mypage = 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_lov_perihal.php?p=pn6001.php&a=formreg&b=tb_kode_perihal&c=tb_kode_perihal_detil&d=tb_nama_perihal&e=tb_nama_perihal_detil&g=tb_path_perihal&task='+task;
      console.log(mypage);
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

    function confirmation(title, msg, fnYes, fnNo) {
            window.parent.Ext.Msg.show({
                title: title,
                msg: msg,
                buttons: window.parent.Ext.Msg.YESNO,
                icon: window.parent.Ext.Msg.QUESTION,
                fn: function(btn) {
                    if (btn === 'yes') {
                        fnYes();
                    } else {
                        fnNo();
                    }
                }
            });
        } 
  
    function fl_js_reset_keyword2()
    {
      document.getElementById('keyword2a').value = '';
      document.getElementById('keyword2b').value = '';
			document.getElementById('keyword2c').value = '';
			document.getElementById('keyword2d').value = '';			
    }
		
    function fl_js_val_numeric(v_field_id)
    {
      var c_val = window.document.getElementById(v_field_id).value;
			var number=/^[0-9]+$/;
			
      if ((c_val!='') && (!c_val.match(number)))
      {
         document.getElementById(v_field_id).value = '';				 
				 window.document.getElementById(v_field_id).focus();
				 alert("Harus berisikan angka, tidak boleh alphabet atau karakter lainnya...! ");         
				 return false; 				 
      }		
    }


  </script>
	<!-- end LOCAL JAVASCRIPTS -->
	
	<!-- LOCAL CSS -->
  <link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
  <style>
  .errorField{
  	border: solid #fe8951 1px !important;
      background: rgba(254, 145, 81, 0.24);
  }
  .dataValid{
    background: #09b546;
    padding: 2px;
    color: #FFF;
    border-radius: 5px;
  }
  input.file{
  	box-shadow:0 0 !important;
  	border:0 !important; 
  }
  input[disabled].file{
    background:#FFF !important;
  }
  input.file::-webkit-file-upload-button {
    background: -webkit-linear-gradient(#5DBBF6, #2788E0);
    border: 1px solid #5492D6;
    border-radius:2px;
    color:#FFF;
    cursor:pointer;
  }
  input[disabled].file::-webkit-file-upload-button {
    background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
    border: 1px solid #ABABAB;
    cursor:no-drop;
  }
  input.file::-webkit-file-upload-button:hover {
    background: linear-gradient(#157fcc, #2a6d9e);
  }
  input[disabled].file::-webkit-file-upload-button:hover {
    background: -webkit-linear-gradient(#C0C0C0, #9A9A9A);
  }
  #mydata th {
    border-right: 1px solid silver;
    border-bottom: 0.5pt solid silver !important;
    border-top: 0.5pt solid silver !important;
    text-align: center !important;
  }
  #listdata td {
    text-align: left !important;
  }
  
  .dataTables_length {
  	margin-bottom: 10px;	
  }
  .dataTables_wrapper{
    position: relative;
    clear: both;
    zoom: 1;
    background: #ebebeb;
    padding-top: 10px;
    padding-bottom: 5px;
    border: 1px solid #dddddd;
  }
  #mylistdata_wrapper thead tr th {
    padding-top: 2px;
    padding-bottom: 2px;
  }
  
  #mydata td {
    font-size: 10px;
    text-align: center;
    border-right: 0px solid rgb(221, 221, 221);
    border-bottom: 1px solid rgb(221, 221, 221);
    padding-top: 2px;
    padding-bottom: 2px;
  }
  
  #mydata {
  	text-align: center;
  }
  #simple-table{
  	font-size:10px;
  	font-weight:normal;
  }
  #simple-table>tbody>tr>td{
  	font-size:10px;
  	font-weight:normal;
  	text-align:left;
  }
  </style>
	<!-- end LOCAL CSS -->
	
	<!-- LOCAL GET/POST PARAMETER -->
  <?PHP
      if($_REQUEST['task']!="New"){
        $sql = "SELECT  A.KODE_AGENDA,
                        A.KODE_JENIS_AGENDA, 
                        B.NAMA_JENIS_AGENDA,
                        B.KODE_TIPE_AGENDA,
                        D.NAMA_TIPE_AGENDA,
                        A.KODE_JENIS_AGENDA_DETIL,
                        C.NAMA_JENIS_AGENDA_DETIL,
                        A.KODE_KANTOR,
                        A.TGL_AGENDA,
                        -- (A.KETERANGAN ||
                        -- (SELECT B.KETERANGAN FROM PN.PN_AGENDA_VERIFIKASI_JHT B WHERE B.KODE_AGENDA = A.KODE_AGENDA) ||
                        -- (SELECT B.KETERANGAN FROM PN.PN_AGENDA_FLAG_DIAKUI B WHERE B.KODE_AGENDA = A.KODE_AGENDA)) KETERANGAN,
                        (
                          CASE
                            WHEN A.KETERANGAN IS NOT NULL THEN
                            A.KETERANGAN 
                            WHEN ( SELECT B.KETERANGAN FROM PN.PN_AGENDA_VERIFIKASI_JHT B WHERE B.KODE_AGENDA = A.KODE_AGENDA ) IS NOT NULL THEN
                            ( SELECT B.KETERANGAN FROM PN.PN_AGENDA_VERIFIKASI_JHT B WHERE B.KODE_AGENDA = A.KODE_AGENDA ) 
                            WHEN ( SELECT B.KETERANGAN FROM PN.PN_AGENDA_FLAG_DIAKUI B WHERE B.KODE_AGENDA = A.KODE_AGENDA ) IS NOT NULL THEN
                            ( SELECT B.KETERANGAN FROM PN.PN_AGENDA_FLAG_DIAKUI B WHERE B.KODE_AGENDA = A.KODE_AGENDA ) 
                          END 
                        ) KETERANGAN,
                        A.PEMILIK_DATA,
                        A.TGL_SELESAI,
                        A.STATUS_AGENDA,
                        A.DETIL_STATUS,
                        A.KODE_KELOMPOK_AGENDA
                FROM PN.PN_AGENDA_KOREKSI A, PN.PN_KODE_JENIS_AGENDA_KOREKSI B, PN.PN_KODE_JENIS_AGENDA_KOR_DETIL C, PN.PN_KODE_TIPE_AGENDA_KOREKSI D
                WHERE A.KODE_JENIS_AGENDA = B.KODE_JENIS_AGENDA
                AND A.KODE_JENIS_AGENDA_DETIL = C.KODE_JENIS_AGENDA_DETIL
                AND B.KODE_TIPE_AGENDA = D.KODE_TIPE_AGENDA
                AND A.KODE_AGENDA = '".$_GET['kd_agenda']."'";
            //echo($sql);
            // 23-02-2024 Penyesuaian kolom keterangan dirubah menggunakan case when

            $DB->parse($sql);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_kode_agenda       = $row['KODE_AGENDA'];
            $ls_tipe_agenda       = $row['KODE_TIPE_AGENDA'];
            $ls_tgl_agenda        = $row['TGL_AGENDA'];
            $ls_kode_jenis_agenda = $row['KODE_JENIS_AGENDA'];
            $ls_nama_jenis_agenda = $row['NAMA_JENIS_AGENDA'];
            $ls_keterangan        = $row['KETERANGAN'];
            $ls_pemilik_data      = $row['PEMILIK_DATA'];
            $ls_tgl_selesai       = $row['TGL_SELESAI'];
            $ls_status_agenda     = $row['STATUS_AGENDA'];
            $ls_detil_status      = $row['DETIL_STATUS'];
            $ls_kode_jenis_agenda_detil = $row['KODE_JENIS_AGENDA_DETIL'];
            $ls_nama_jenis_agenda_detil = $row['NAMA_JENIS_AGENDA_DETIL'];

            // untuk  $ls_kode_jenis_agenda_detil PP0207 PP0208 PP0209
            $sql_cek_approval="SELECT COUNT(1) CEK_APPROVAL_KBL FROM PN.PN_KURANG_BAYAR_TDL_APPROVAL WHERE KODE_AGENDA='{$_GET['kd_agenda']}' AND STATUS_APPROVAL='Y'";
            $DB->parse($sql_cek_approval);
            $DB->execute();
            $row = $DB->nextrow();
            $ls_cek_approval_kbl      = $row['CEK_APPROVAL_KBL'];
      }                                   

  ?>
	<!-- end LOCAL GET/POST PARAMETER -->
	
	<!-- VALIDASI AJAX -->
  <script type="text/javascript" src="../../javascript/validator.js"></script>
  <script type="text/javascript" src="../../javascript/ajax.js"></script>
  
  <script type="text/javascript">

  // Digunakan untuk melakukan reload data ketika memilih perihal
  function f_js_reload(URL){
    // console.log(URL);
    // preload(true);
    this.location.replace(URL);
  }                 
    
function PopupCenter(url, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open(url, title, 'toolbar=no,menubar=no,status=no,location=no,menubar=no,scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
    return newWindow;
}

  </script>
	<!-- end VALIDASI AJAX -->	
	
	<!-- ACTION BUTTON -->
  <div id="actmenu">
    <div id="actbutton">
      <?PHP
      if(isset($_REQUEST["task"]))
      {
        ?>
        <?PHP
        if($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "New")
        {
          ?>
          <div style="float:left;">
            <div class="icon">
            <a id="btn_save" href="javascript:void(0)"><img alt="save" src="http://<?=$HTTP_HOST;?>/images/save-as.gif" align="absmiddle" border="0"> Save</a>
            </div>
          </div>
          <?PHP
        }; 
        ?>
        <div style="float:left;">
          <div class="icon">
              <?PHP if($_GET['form']=='tdl'){ ?>
                 <!--  <a id="btn_submit" href="http://<?=$HTTP_HOST;?>/mod_kn/form/kn5041.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/application_get.png" align="absmiddle" border="0"> Submit</a> -->
                <?PHP if($_GET['st_agenda']=='TERBUKA'){ ?>
                  <a id="btn_submit" href="javascript:void(0)">
                  <img alt="approve" src="http://<?=$HTTP_HOST;?>/images/accept.png" align="absmiddle" border="0"> Approve
                  </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <? } ?>
                <?PHP if($ls_status_agenda=='TERBUKA') {?>
                      <a id="btn_tolak" href="javascript:void(0)">
                      <img alt="batal" src="http://<?=$HTTP_HOST;?>/images/cancel.png" align="absmiddle" border="0"> Batal
                      </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <? } ?>

                <?PHP if(($ls_detil_status=='APPROVAL') && ($ls_kode_jenis_agenda_detil=='PP0202')) {?>
                          <a id="btn_batal_approval" href="javascript:void(0)" >
                          <img alt="batal" src="http://<?=$HTTP_HOST;?>/images/cancel.png" align="absmiddle" border="0"> Batal
                          </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <? } ?>

                <?PHP if(($ls_detil_status=='APPROVAL') && ($ls_kode_jenis_agenda_detil=='PP0204')) {?>
                          <a id="btn_batal_approval" href="javascript:void(0)" >
                          <img alt="batal" src="http://<?=$HTTP_HOST;?>/images/cancel.png" align="absmiddle" border="0"> Batal
                          </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <? } ?>

                  <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn6002.php?mid=<?=$mid;?>"><img alt="Close" src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
               <?php 
			  }else if($_GET['form']=='tdlbyr')
			  {
				  ?>
                  <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn6003.php?mid=<?=$mid;?>"><img alt="Close" src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a>   
			   <? }else{ ?>
                     <?PHP if($ls_status_agenda=='TERBUKA'){?>
                       <?PHP if(($ls_detil_status=='AGENDA')&&($ls_kode_jenis_agenda_detil=='PP0203')){?>
                          <a id="btn_submit_kor_awaris" href="javascript:void(0)">
                          <img  alt="Submit" src="http://<?=$HTTP_HOST;?>/images/accept.png" align="absmiddle" border="0"> Submit
                          </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?}?>
                      <a id="btn_tolak" href="javascript:void(0)">
                      <img alt="Batal" src="http://<?=$HTTP_HOST;?>/images/cancel.png" align="absmiddle" border="0"> Batal
                      </a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <? } ?>
					<?php if($_REQUEST["kode_sumber"] != "profile") {?>
            <!-- 19-02-2024 id="btn_close2" untuk tombol close, tidak diaksih id="btn_close" karena ditakutkan mengganggu form yg lain -->
                  <a id="btn_close2" alt="" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001.php?mid=<?=$mid;?>"><img alt="cancel" src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
              <?php } ?>
			  <? } ?>
          </div>
        </div>        
        <!-- <div style="float:left;">
          <div class="icon">
             <a id="btn_close" href="http://<?=$HTTP_HOST;?>/mod_pn/form/pn6001.php?mid=<?=$mid;?>"><img src="http://<?=$HTTP_HOST;?>/images/file_cancel.gif" align="absmiddle" border="0"> Close</a> 
          </div>
        </div> -->
        <?PHP
      } 
      else 
      {
        ?>
        <div style="float:left;">
          <div class="icon"><a id="btn_new" href="javascript:void(0)">
            <img alt="New" src="http://<?=$HTTP_HOST;?>/images/app_form_add.png" align="absmiddle" border="0"> New</a>
          </div>
        </div>
        <?PHP
      }
      ?>
    </div>  
  </div>
	<!-- end ACTION BUTTON -->
	

  <table aria-describedby="mydesc" class="captionentry">
    <tr><th></th></tr>
      <tr> 
          <td align="left"><strong> <?=$gs_pagetitle;?></strong>  </td>            
      </tr>
  </table>


	<!-- REQUEST TASK (GRID, NEW, EDIT, VIEW) -->
  <?PHP
  if(isset($_REQUEST["task"]) && ($_REQUEST["task"] == "Edit" || $_REQUEST["task"] == "View" || $_REQUEST["task"] == "New" || $_REQUEST["task"] == "Submit"))
  {
	?>
    
    <!-- <div id="actmenu">
        <h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>     
    </div> -->
    <div id="formframe">
      <div id="formKiri" style="width:100%">
        <input type="hidden" id="TYPE" name="TYPE" value="<?=$_GET['task'];?>">
        <input type="hidden" id="ACTION_TYPE" name="ACTION_TYPE" value="<?=$_ls_action_type;?>">
        <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["kd_agenda"];?>">
        <input type="hidden" id="lov_sumber_informasi" name="lov_sumber_informasi" value="<?=$ls_nama_sumber_data;?>">
        <input type="hidden" id="tb_kode_sumber_data" name="tb_kode_sumber_data" value="<?=$ls_kode_sumber_data;?>">
        
        <fieldset><legend>Entry Koreksi Data Internal</legend>
        <div class="form-row_kiri">
        <label style = "text-align:right;">Perihal * :</label>
          <input type="text" name="tb_kode_perihal" id="tb_kode_perihal" style="background-color:#ffff99;" tabindex="1" size="20" value="<? if(isset($ls_kode_jenis_agenda)){echo $ls_kode_jenis_agenda;}else{echo $_GET['kd_perihal'];} ?>" hidden="hidden">
          <input type="text" name="tb_kode_perihal_detil" id="tb_kode_perihal_detil" style="background-color:#ffff99; width: 450px" tabindex="1" value="<? if(isset($ls_kode_jenis_agenda_detil)){echo $ls_kode_jenis_agenda_detil;}else{echo $_GET['kd_perihal_detil'];} ?>" hidden="hidden">
          <input type="text" name="tb_nama_perihal" id="tb_nama_perihal" style="background-color:#ffff99; width: 300px;" tabindex="1" value="<? if(isset($ls_nama_jenis_agenda)){echo $ls_nama_jenis_agenda;}else{echo $_GET['nm_jns_agenda'];} ?>" <?PHP if($_REQUEST['task']=='View'){echo "readonly";}?> readonly>
          
          <a href="#" onclick="NewWindowPerihal('','',800,500,1)" >   
          <img src="../../images/help.png" alt="Cari Perihal Agenda" border="0" align="absmiddle" <?PHP if($_REQUEST['task'] !='New'){echo "hidden='hidden'";}?> ></a>
          
          <!-- <a href="#" onclick="PopupCenter('pn6001_lov_agenda_tree.php','Jenis agenda',400,400);" >   
          <img src="../../images/help.png" alt="Cari Perihal Agenda" border="0" align="absmiddle" <?PHP if($_REQUEST['task'] !='New'){echo "hidden='hidden'";}?> ></a> -->
        </div>
        <div class="form-row_kanan">
          <label style = "text-align:right;">Kode Agenda :</label>
          <input type="text" class="disabled" name="kd_agenda" id="kd_agenda" style="width: 150px" tabindex="1" value="<?=$_REQUEST['kd_agenda'];?>" readonly="readonly">
        </div>
        <div class="clear"></div>
        <div class="form-row_kiri">
          <label style = "text-align:right;">Detil Perihal :</label>
          <input type="text" name="tb_path_perihal" id="tb_path_perihal" style="background-color:#ffff99; width: 300px" tabindex="1" value="<?=$_GET['path'];?>" hidden="hidden">
          <input type="text" name="tb_nama_perihal_detil" id="tb_nama_perihal_detil" value="<? if(isset($ls_nama_jenis_agenda_detil)){echo $ls_nama_jenis_agenda_detil;}else{echo $_GET['nm_jns_agenda_dtl'];} ?>" style="width: 300px" class="disabled" readonly>                                                  
        </div>
         <div class="form-row_kanan">
          <label style = "text-align:right;">Status Agenda :</label>
          <input type="text" class="disabled" name="detil_status" id="detil_status" style="width: 150px" tabindex="1" value="<?=$ls_detil_status;?>" readonly="readonly">                                            
        </div>
        <div class="clear"></div> 
        <div class="form-row_kiri">
          <?php
          // 01-02-2024 penambahan validasi untuk taaskno  PP0305
          // 06-02-2024 penambahan validasi untuk taaskno  PP0306
          if($_REQUEST["kd_perihal_detil"] == "PP0301" || $_REQUEST["kd_perihal_detil"] == "PP0302" || $_REQUEST["kd_perihal_detil"] == "PP0305" || $_REQUEST["kd_perihal_detil"] == "PP0306"  || $_REQUEST["kd_perihal_detil"] == "PP0303" || $_REQUEST["kd_perihal_detil"] == "PP0304"){
            $ls_mandatory = '';
            if ($_REQUEST["kd_perihal_detil"] != "PP0303" || $_REQUEST["kd_perihal_detil"] != "PP0306" || $_REQUEST["kd_perihal_detil"] != "PP0304"){ $ls_mandatory = "*";}
          ?>
          <label style = "text-align:right;">Hasil Verifikasi <?=$ls_mandatory;?> :</label>              
          <textarea type="text" id="tb_keterangan" name="tb_keterangan" tabindex="3" size="40" maxlength="300" style="width: 300px; height: 25px" <?PHP if($_REQUEST['task']=='View'){echo "readonly";}?>><?=$ls_keterangan;?></textarea>
          <?php
          } else {
          ?>
          <label style = "text-align:right;">Keterangan :</label>              
          <textarea type="text" id="tb_keterangan" name="tb_keterangan" tabindex="3" size="40" maxlength="300" style="width: 300px; height: 25px" <?PHP if($_REQUEST['task']=='View'){echo "readonly";}?>><?=$ls_keterangan;?></textarea>
          <?php } ?>
        </div>        
        <div class="clear"></div>												
        </fieldset>
        <div class="clear"></div>
        <div class="form-row_kiri">
        <?PHP
          if(isset($_GET['path'])){
            include_once($_GET['path']);  
          }
        ?>         		
        </div>
        <div class="clear"></div>
       <div class="form-row_kiri">
        <?PHP

          if($_REQUEST["kd_perihal_detil"] == "PP0202" || $_REQUEST["kd_perihal_detil"] == "PP0203"){
              if(isset($_GET['path'])){
                  include_once('../ajax/pn6001_tab_administrasi.php');  
              }
          } else if($_REQUEST["kd_perihal_detil"] == "PP0204"){
              if(isset($_GET['path'])){
                  include_once('../ajax/pn6001_tab_administrasi.php');  
              }
          }  
        ?>            
        </div>
        <div class="clear"></div> 
        <div class="form-row_kiri">
            <!-- <input type="button" name="btnsubmit" class="btn green" id="btnsubmit" value=" SUBMIT "> -->
        </div>	
      </div><!--end formKiri -->					 
    </div> <!--end formframe-->
	<?PHP		
	}else{
	?>
    <div id="formframe">
      <div id="formKiri" style="width:1150px">   
        <fieldset><legend><strong> DATA AGENDA</strong> </legend>
          <div class="form-row_kiri">
          <span style="margin-right:5px;"><strong> Perihal:</strong> </span>
          <select size="1" id="pilihan_koreksi" name="pilihan_koreksi" value="<?=$ls_kode_jenis_agenda_detil_selected;?>" style="width:500px;background-color:"  >
                     <option value="">-- Pilih --</option>   
                    <? 
                    $sql = "SELECT KODE_JENIS_AGENDA_DETIL, NAMA_JENIS_AGENDA_DETIL 
                            FROM PN.PN_KODE_JENIS_AGENDA_KOR_DETIL      
                            WHERE NVL(STATUS_NONAKTIF,'T') = 'T'
                            AND KODE_JENIS_AGENDA_DETIL IN (SELECT KODE_JENIS_AGENDA_DETIL FROM PN.PN_KODE_JENIS_AGENDA_KOR_ROLE WHERE KODE_FUNGSI = '".$_SESSION['regrole']."') 
                            ORDER BY KODE_JENIS_AGENDA_DETIL";
                    // echo $sql;
                    // die();
                    $DB->parse($sql);
                    $DB->execute();
                    while($row = $DB->nextrow())
                    {
                    echo "<option ";
                    if ($row["KODE_JENIS_AGENDA_DETIL"]==$ls_kode_jenis_agenda_detil_selected && strlen($ls_kode_jenis_agenda_detil_selected)==strlen($row["KODE_JENIS_AGENDA_DETIL"])){ echo " selected"; }
                    echo " value=\"".$row["KODE_JENIS_AGENDA_DETIL"]."\">".$row["NAMA_JENIS_AGENDA_DETIL"]."</option>";
                    }
                    ?>
                  </select>
                  <!-- 01-02-2024 penambahan validasi untuk taaskno  PP0305-->
                  <!-- 06-02-2024 penambahan validasi untuk taaskno  PP0306-->
                  <?php if(($_REQUEST['taskno']=='PP0203') || ($_REQUEST['taskno']=='PP0205') || ($_REQUEST['taskno']=='PP0206') || ($_REQUEST['taskno']=='PP0207') || ($_REQUEST['taskno']=='PP0208') || ($_REQUEST['taskno']=='PP0209')|| ($_REQUEST['taskno']=='PP0302')|| ($_REQUEST['taskno']=='PP0305')|| ($_REQUEST['taskno']=='PP0306')|| ($_REQUEST['taskno']=='PP0301')|| ($_REQUEST['taskno']=='PP0303')){?>
                      <? 
                        switch($ls_rg_kategori)
                        {
                          case 'ALL' : $sel1="checked"; break;
                          case 'APPROVED' : $sel2="checked"; break;
                          case 'SUBMIT' : $sel3="checked"; break;
                          // case '3' : $sel3="checked"; break;
                          // case '4' : $sel4="checked"; break;
                        }
                      ?>
                      <span style="margin-right:20px;"></span>
                      <input type="radio" name="rg_kategori" value="ALL" onclick="loadData('ALL');"  <?=$sel1;?>>&nbsp;<span style="color:#009999;"><strong> ALL</strong> </span>
                      <span style="margin-right:20px;"></span>
                      <input type="radio" name="rg_kategori" value="APPROVED" onclick="loadData('APPROVED');"  <?=$sel2;?>>&nbsp;<span style="color:#009999;"><strong> SUDAH DISETUJUI</strong> </spam>
                      <span style="margin-right:20px;"></span> 
                      <input type="radio" name="rg_kategori" value="SUBMIT" onclick="loadData('SUBMIT');"  <?=$sel3;?>>&nbsp;<span style="color:#009999;"><strong> BELUM DISETUJUI</strong> </span>
                  <?php }?> 
          </div>                                      
          <div class="form-row_kanan">
            <span style="margin-right:5px;">Search by:</span>
            <select id="search_pilihan" name="search_pilihan" style="width:150px;" >
              <?php if($_REQUEST['taskno']=='PP0101'){?>
				<option value="A.KODE_AGENDA">Kode Agenda</option> 
				<option value="C.NAMA_JENIS_AGENDA_DETIL">Perihal</option>
				<option value="A.KODE_KANTOR">Kode Kantor</option> 
				<option value="E.KODE_FASKES">Kode Faskes</option>
				<option value="E.NAMA_FASKES">Nama Faskes</option> 
              <?php } 
              else if($_REQUEST['taskno']=='PP0303' || $_REQUEST['taskno']=='PP0306' || $_REQUEST['taskno']=='PP0304') { ?>}
				<option value="C.KODE_AGENDA">Kode Agenda</option> 
				<option value="C.KPJ">KPJ</option>
				<option value="C.NAMA_TK">Nama TK</option>         
            <?php } else { ?>}
				<option value="A.KODE_AGENDA">Kode Agenda</option> 
				<option value="B.KODE_KLAIM">Kode Klaim</option>
				<option value="C.KPJ">KPJ</option>
				<option value="C.NAMA_TK">Nama TK</option>         
            <?php }?>
            </select>
            <input  type="text" name="keyword" id="keyword" style="width:150px;" placeholder="Keyword">                              
            <input type="button" name="btncari" class="btn green" id="btncari" value=" TAMPILKAN DATA ">
          </div>
          <div class="clear"></div>
          
          <div id="formsplit">
            <div class="clear"></div>
              <table aria-describedby="mydesc" class="table responsive-table stripe row-border compact order-column" id="table_koreksi" cellspacing="0" style="width: 1150px">
                <!-- <thead> -->
                  <!-- <tr>
                    Data Table  Headers   
                  </tr> -->
                <!-- </thead> -->
                <tbody id="listdata1">
                  <tr><th></th></tr>
                </tbody>
              </table>
            <div class="clear"></div>
          <div class="clear"></div>                                                                                                         
        </fieldset>
        
        <br>
        
        <fieldset style="background: #F2F2F2;"><legend style="background: #FF0; border: 1px solid #CCC;">Keterangan:</legend>
          <ul>
            <li>Pilih Jenis Pencarian</li>  
            <li>Input Kata Kunci (Keyword) dengan menambahkan % untuk keyword sebagian</li> 
            <li>Klik Tombol TAMPILKAN DATA untuk memulai pencarian data</li>  
            <li>Untuk melihat detail data Klik salah satu data pada Tabel hasil Pencarian</li>
          </ul>
        </fieldset>
      </div>
    </div>  
  <?PHP
  }
  ?>
  <!-- <div id="formKiri" style="width:880px">
      <div class="clear"></div>
      <iframe id="div_form_agenda" frameborder="0" width="1200px" height="1000px" src=""></iframe>
  </div> -->
	<!-- end REQUEST TASK (GRID, NEW, EDIT, VIEW) -->
	
	<!-- LOAD DATA -->
	<script type="text/javascript">
		$(document).ready(function()
		{
      // preload(false);
      $('#keyword').focus();            
			$('input').keyup(function(){
       	this.value = this.value.toUpperCase();
      });      


      <?PHP
			//------------------- TASK -----------------------------------------------
      if(isset($_REQUEST["task"]))
  		{
			 	?>													
        window.dataid = '<?=$_REQUEST["kode_agenda"];?>';
				
				<?PHP
  			//NEW ------------------------------------------------------
        if($_REQUEST["task"] == "New")
  			{
        ?>
          //Action button save di form New
          $('#btn_save').click(function() 
  				{
            let kode_perihal_detil = $('#tb_kode_perihal_detil').val();
            console.log(kode_perihal_detil);
              confirmation('Konfirmasi','Apakah anda yakin akan menyimpan agenda permintaan pelayanan?',function(){
                  error = 0;
                  if($('#tb_nama_perihal').val()==''){
                    error += 1;
                  }else if($('#tb_nama_perihal_detil').val()==''){
                    error += 1;
                  }
                  // if('<?=$jml_dok?>'>0){
                  //     //error += check_dokumen_checklist();
                  // }

                  if(typeof isValid()!='undefined'){
                      var valid = isValid();
                      console.log("isValid:"+valid.val);
                      if(valid.val==true){
                          // if (error == 0)
                          // { 
                          //     if('<?=$jml_dok?>'>0){
                          //         var table = document.getElementById("tblrincian1");
                          //         var tr    = table.getElementsByTagName("tr");  
                          //     }
                              var form_data = new FormData();
                              var jml_doc   = 0;
                              form_data.append('TYPE',$('#TYPE').val());
                              form_data.append('tb_kode_perihal',$('#tb_kode_perihal').val());
                              form_data.append('tb_kode_perihal_detil',$('#tb_kode_perihal_detil').val());
                              form_data.append('tb_path_perihal',$('#tb_path_perihal').val());
                              form_data.append('tb_keterangan',$('#tb_keterangan').val());
                              form_data.append('lov_sumber_informasi',$('#lov_sumber_informasi').val());
                              form_data.append('tb_kode_sumber_data',$('#tb_kode_sumber_data').val());
                              //var file  = $('#datafile').prop('files');
                              //form_data.append('datafile',file);
                              if($('#tb_kode_perihal_detil').val()=="PP0202" || $('#tb_kode_perihal_detil').val()=="PP0203"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              } else if($('#tb_kode_perihal_detil').val()=="PP0204"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              }
                              // Proses get component on tabel dokumen
                              // if('<?=$jml_dok?>'>0){
                              //     if(error==0){
                              //         for(i=3;i<=tr.length-2;i++){ 
                              //             var kode_dokumen       = $('#d_adm_kode_dokumen'+(i-3)).val();
                              //             var no_urut            = $('#d_adm_no_urut'+(i-3)).val(); 
                              //             var mandatory          = $('#d_adm_flag_mandatory'+(i-3)).val(); 
                              //             var status_diserahkan  = $('#d_adm_status_diserahkan'+(i-3)).val();
                              //             var tgl_diserahkan     = $('#d_adm_tgl_diserahkan'+(i-3)).val();
                              //             var file               = $('#lob_upload'+(i-3)).prop('files')[0];
                              //             form_data.append('d_adm_kode_dokumen'+(i-3),kode_dokumen);
                              //             form_data.append('d_adm_no_urut'+(i-3),no_urut);
                              //             form_data.append('d_adm_flag_mandatory'+(i-3),mandatory);
                              //             form_data.append('d_adm_status_diserahkan'+(i-3),status_diserahkan);
                              //             form_data.append('d_adm_tgl_diserahkan'+(i-3),tgl_diserahkan);
                              //             form_data.append('lob_upload'+(i-3),file);
                              //             jml_doc += 1;
                              //         }
                              //     }
                              // }
                              // // End of get component on tabel dokumen
                              // form_data.append('jml_doc',jml_doc);
                              preload(true);
                              $.ajax(
                              {
                                  asynchronous: true,
                                  type: 'POST',
                                  cache: false,
                                  contentType: false,
                                  processData: false,
                                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
                                  data: form_data,
                                  success: function(data)
                                  {
                                      preload(false);
                                      // console.log($('#formreg').serialize()); 
                                      //console.log(data);
                                      jdata = JSON.parse(data);
                                      //console.log(jdata);                 
                                      if(jdata.ret==0){
                                          document.getElementById("kd_agenda").value = jdata.dataid;
                                          execute_form();
                                          // insert_dokumen();
                                          // upload_dokumen();
                                      }else{
                                          alert(jdata.msg);
                                      }
                                  }
                              });
                          // }else{
                          //     alert("Cek semua input dan mandatory dokumen");
                          // } 
                      }else{
                          alert(valid.msg);
                      }
                  }else{
                      // if (error == 0)
                      //     { 
                      //         // console.log("isValid:"+valid.val);
                      //         if('<?=$jml_dok?>'>0){
                      //             var table = document.getElementById("tblrincian1");
                      //             var tr    = table.getElementsByTagName("tr");  
                      //         }
                              var form_data = new FormData();
                              var jml_doc   = 0;
                              form_data.append('TYPE',$('#TYPE').val());
                              form_data.append('tb_kode_perihal',$('#tb_kode_perihal').val());
                              form_data.append('tb_kode_perihal_detil',$('#tb_kode_perihal_detil').val());
                              form_data.append('tb_path_perihal',$('#tb_path_perihal').val());
                              form_data.append('tb_keterangan',$('#tb_keterangan').val());
                              form_data.append('lov_sumber_informasi',$('#lov_sumber_informasi').val());
                              form_data.append('tb_kode_sumber_data',$('#tb_kode_sumber_data').val());
                              // var file  = $('#datafile').prop('files');
                              // form_data.append('datafile',file);
                              if($('#tb_kode_perihal_detil').val()=="PP0202" || $('#tb_kode_perihal_detil').val()=="PP0203"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              } else if($('#tb_kode_perihal_detil').val()=="PP0204"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              }
                              // Proses get component on tabel dokumen
                              // if('<?=$jml_dok?>'>0){
                              //     if(error==0){
                              //         for(i=3;i<=tr.length-2;i++){ 
                              //             var kode_dokumen       = $('#d_adm_kode_dokumen'+(i-3)).val();
                              //             var no_urut            = $('#d_adm_no_urut'+(i-3)).val(); 
                              //             var mandatory          = $('#d_adm_flag_mandatory'+(i-3)).val(); 
                              //             var status_diserahkan  = $('#d_adm_status_diserahkan'+(i-3)).val();
                              //             var tgl_diserahkan     = $('#d_adm_tgl_diserahkan'+(i-3)).val();
                              //             var file               = $('#lob_upload'+(i-3)).prop('files')[0];
                              //             form_data.append('d_adm_kode_dokumen'+(i-3),kode_dokumen);
                              //             form_data.append('d_adm_no_urut'+(i-3),no_urut);
                              //             form_data.append('d_adm_flag_mandatory'+(i-3),mandatory);
                              //             form_data.append('d_adm_status_diserahkan'+(i-3),status_diserahkan);
                              //             form_data.append('d_adm_tgl_diserahkan'+(i-3),tgl_diserahkan);
                              //             form_data.append('lob_upload'+(i-3),file);
                              //             jml_doc += 1;
                              //         }
                              //     }
                              // }
                              // // End of get component on tabel dokumen
                              // form_data.append('jml_doc',jml_doc);
                              preload(true);
                              $.ajax(
                              {
                                  asynchronous: true,
                                  type: 'POST',
                                  cache: false,
                                  contentType: false,
                                  processData: false,
                                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
                                  data: form_data,
                                  success: function(data)
                                  {
                                      preload(false);
                                      // console.log($('#formreg').serialize()); 
                                      //console.log(data);
                                      jdata = JSON.parse(data);                 
                                      if(jdata.ret==0){
                                          document.getElementById("kd_agenda").value = jdata.dataid;
                                          execute_form();
                                          // insert_dokumen();
                                          // upload_dokumen();
                                      }else{
                                          alert(jdata.msg);
                                      }
                                  }
                              });
                          // }else{
                          //     alert("Cek semua input dan mandatory dokumen");
                          // } 
                  }
              },function(){});
																        
          });					
        <?PHP
        };
  			//end NEW ------------------------------------------------
  			?>	
				
        <?PHP
  			//EDIT ---------------------------------------------------
        if($_REQUEST["task"] == "Edit")
  			{
        ?>
          setTimeout( function(){
          	preload(true);
          }, 100); 				
        //   $.ajax({
        //   	type: 'POST',
        //   	url: 'http://<?=$HTTP_HOST;?>/mod_kn/ajax/kn5055_action.php?'+Math.random(),
        //   	data: { TYPE:'View', DATAID:window.dataid},
        //   	success: function(data) {
        //   		setTimeout( function() {
        //   			preload(false);
        //   		}, 100); 
        //   		console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+"}");	
        //   		console.log(data);        		
        //   		jdata = JSON.parse(data);
        //       if(jdata.ret == '0')
  						// {
								// $('#tb_kode_agenda').val(jdata.data[0].KODE_AGENDA);										
        //       }
        //   	}
        //   });
          $('#btn_save').click(function() 
  				{
              confirmation('Konfirmasi','Apakah anda yakin akan menyimpan agenda permintaan pelayanan?',function(){
                  error = 0;
                  if($('#tb_nama_perihal').val()==''){
                    error += 1;
                  }else if($('#tb_nama_perihal_detil').val()==''){
                    error += 1;
                  }

                  // if('<?=$jml_dok?>'>0){
                  //     //error += check_dokumen_checklist();
                  // }

                  if(typeof isValid()!='undefined'){
                      var valid = isValid();
                      if(valid.val==true){
                          //if (error == 0)
                          //{ 
                              // if('<?=$jml_dok?>'>0){
                              //     var table     = document.getElementById("tblrincian1");
                              //     var tr        = table.getElementsByTagName("tr");  
                              // }
                              var form_data = new FormData();
                              var jml_doc   = 0
                              form_data.append('TYPE',$('#TYPE').val());
                              form_data.append('tb_kode_perihal',$('#tb_kode_perihal').val());
                              form_data.append('tb_kode_perihal_detil',$('#tb_kode_perihal_detil').val());
                              form_data.append('tb_path_perihal',$('#tb_path_perihal').val());
                              form_data.append('tb_keterangan',$('#tb_keterangan').val());
                              form_data.append('kd_agenda',$('#kd_agenda').val());
                              form_data.append('lov_sumber_informasi',$('#lov_sumber_informasi').val());
                              form_data.append('tb_kode_sumber_data',$('#tb_kode_sumber_data').val());
                              if($('#tb_kode_perihal_detil').val()=="PP0202" || $('#tb_kode_perihal_detil').val()=="PP0203"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              } else if($('#tb_kode_perihal_detil').val()=="PP0204"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              }
                              // Proses get component on tabel dokumen
                              // if('<?=$jml_dok?>'>0){
                              //     if(document.getElementById('btn_edit_dok').getAttribute('readonly')=='readonly'){
                              //         if(error==0){
                              //             for(i=3;i<=tr.length-2;i++){ 
                              //                 var kode_dokumen       = $('#d_adm_kode_dokumen'+(i-3)).val();
                              //                 var no_urut            = $('#d_adm_no_urut'+(i-3)).val(); 
                              //                 var mandatory          = $('#d_adm_flag_mandatory'+(i-3)).val(); 
                              //                 var status_diserahkan  = $('#d_adm_status_diserahkan'+(i-3)).val();
                              //                 var tgl_diserahkan     = $('#d_adm_tgl_diserahkan'+(i-3)).val();
                              //                 var file               = $('#lob_upload'+(i-3)).prop('files')[0];
                              //                 form_data.append('d_adm_kode_dokumen'+(i-3),kode_dokumen);
                              //                 form_data.append('d_adm_no_urut'+(i-3),no_urut);
                              //                 form_data.append('d_adm_flag_mandatory'+(i-3),mandatory);
                              //                 form_data.append('d_adm_status_diserahkan'+(i-3),status_diserahkan);
                              //                 form_data.append('d_adm_tgl_diserahkan'+(i-3),tgl_diserahkan);
                              //                 form_data.append('lob_upload'+(i-3),file);
                              //                 jml_doc += 1;
                              //             }
                              //         }
                              //     }
                              // }
                              // End of get component on tabel dokumen
                              //form_data.append('jml_doc',jml_doc);
                              preload(true);
                              $.ajax(
                              {
                                  asynchronous: true,
                                  type: 'POST',
                                  cache: false,
                                  contentType: false,
                                  processData: false,
                                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
                                  data: form_data,
                                  success: function(data)
                                  {
                                      preload(false);
                                      // console.log($('#formreg').serialize()); 
                                      // console.log(data);
                                      jdata = JSON.parse(data);
                                      //console.log(jdata);                 
                                      if(jdata.ret==0){
                                          document.getElementById("kd_agenda").value = jdata.dataid;
                                          execute_form();
                                          // insert_dokumen();
                                          // upload_dokumen();
                                      }else{
                                          alert(jdata.msg);
                                      }
                                  }
                              });
                          // }else{
                          //     alert("Cek semua input dan mandatory dokumen");
                          // } 
                      }else{
                          alert(valid.msg);
                      }
                  }else{
                      // if (error == 0)
                      //     { 
                              // if('<?=$jml_dok?>'>0){
                              //     var table     = document.getElementById("tblrincian1");
                              //     var tr        = table.getElementsByTagName("tr");  
                              // }
                              var form_data = new FormData();
                              var jml_doc   = 0;
                              form_data.append('TYPE',$('#TYPE').val());
                              form_data.append('tb_kode_perihal',$('#tb_kode_perihal').val());
                              form_data.append('tb_kode_perihal_detil',$('#tb_kode_perihal_detil').val());
                              form_data.append('tb_path_perihal',$('#tb_path_perihal').val());
                              form_data.append('tb_keterangan',$('#tb_keterangan').val());
                              form_data.append('kd_agenda',$('#kd_agenda').val());
                              form_data.append('lov_sumber_informasi',$('#lov_sumber_informasi').val());
                              form_data.append('tb_kode_sumber_data',$('#tb_kode_sumber_data').val());
                              if($('#tb_kode_perihal_detil').val()=="PP0202" || $('#tb_kode_perihal_detil').val()=="PP0203"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              } else if($('#tb_kode_perihal_detil').val()=="PP0204"){
                                form_data.append("datafile",$("#datafile")[0].files[0]);
                              }
                              // Proses get component on tabel dokumen
                              // if('<?=$jml_dok?>'>0){
                              //     if(document.getElementById('btn_edit_dok').getAttribute('readonly')=='readonly'){
                              //         if(error==0){
                              //             for(i=3;i<=tr.length-2;i++){ 
                              //                 var kode_dokumen       = $('#d_adm_kode_dokumen'+(i-3)).val();
                              //                 var no_urut            = $('#d_adm_no_urut'+(i-3)).val(); 
                              //                 var mandatory          = $('#d_adm_flag_mandatory'+(i-3)).val(); 
                              //                 var status_diserahkan  = $('#d_adm_status_diserahkan'+(i-3)).val();
                              //                 var tgl_diserahkan     = $('#d_adm_tgl_diserahkan'+(i-3)).val();
                              //                 var file               = $('#lob_upload'+(i-3)).prop('files')[0];
                              //                 form_data.append('d_adm_kode_dokumen'+(i-3),kode_dokumen);
                              //                 form_data.append('d_adm_no_urut'+(i-3),no_urut);
                              //                 form_data.append('d_adm_flag_mandatory'+(i-3),mandatory);
                              //                 form_data.append('d_adm_status_diserahkan'+(i-3),status_diserahkan);
                              //                 form_data.append('d_adm_tgl_diserahkan'+(i-3),tgl_diserahkan);
                              //                 form_data.append('lob_upload'+(i-3),file);
                              //                 jml_doc += 1;
                              //             }
                              //         }
                              //     }
                              // }
                              // End of get component on tabel dokumen
                              //form_data.append('jml_doc',jml_doc);
                              preload(true);
                              $.ajax(
                              {
                                  asynchronous: true,
                                  type: 'POST',
                                  cache: false,
                                  contentType: false,
                                  processData: false,
                                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
                                  data: form_data,
                                  success: function(data)
                                  {
                                      preload(false);
                                      // console.log($('#formreg').serialize()); 
                                      // console.log(data);
                                      jdata = JSON.parse(data);                 
                                      if(jdata.ret==0){
                                          document.getElementById("kd_agenda").value = jdata.dataid;
                                          execute_form();
                                          // insert_dokumen();
                                          // upload_dokumen();
                                      }else{
                                          alert(jdata.msg);
                                      }
                                  }
                              });
                          // }else{
                          //     alert("Cek semua input dan mandatory dokumen");
                          // } 
                  }
              },function(){});
              
          });									
        	<?PHP
        };
  			//end EDIT ---------------------------------------------------
        ?>

  			<?PHP
        //------------------- VIEW -----------------------------------		
  			if($_REQUEST["task"] == "View")
  			{
        ?>
          setTimeout( function() {
          	preload(true);
          }, 100);
          $.ajax({
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
            data: { TYPE:'View', DATAID:window.dataid},
            success: function(data) {
              setTimeout( function() {
              	preload(false);
              }, 100); 
              console.log("{ TYPE:'VIEW', DATAID:"+window.dataid+"}");	
              //console.log(data);
              jdata = JSON.parse(data);
              if(jdata.ret == '0'){
                $('#DATAID').val(jdata.data[0].KODE_PERUSAHAAN);
								$('#KODE_PERUSAHAAN').val(jdata.data[0].KODE_PERUSAHAAN);
              }
            }
          });
          <?PHP
        };
        ?>

        <?PHP
        //------------------- SUBMIT -----------------------------------    
        if(($_REQUEST["task"] == "Submit") || ($_REQUEST["task"] == "View"))
        {
        ?>

        $('#btn_submit_kor_awaris').click(function() {
          $('#ACTION_TYPE').val('Submitawaris');
          var r = confirm('Apakah Anda Yakin Mengajukan No. Agenda '+$('#kd_agenda').val()+' untuk diapproval ?');
          if (r == true) {
              path_ori = '<?=$_GET['path']?>';
              path     = path_ori.replace('.php','');
              //console.log(path);
              $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
                data: $('#formreg').serialize(),
                success: function(data)
                {
                  preload(false);
                  //console.log(data);
                  jdata = JSON.parse(data);                 
                  if(jdata.ret==0) 
                  {                                        
                    window.parent.Ext.notify.msg('Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil disubmit, session dilanjutkan...', jdata.msg);
                    window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
                  }else 
                  {
                    alert(jdata.msg);
                  }
                }
              });
          }
        });

        $('#btn_submit').click(function() {
          $('#ACTION_TYPE').val('Approve');
          var r = confirm('Apakah Anda Yakin Menyetujui Agenda Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' ?');
          if (r == true) {
              path_ori = '<?=$_GET['path']?>';
              path     = path_ori.replace('.php','');
              //console.log(path);
              $.ajax({
                type: 'POST',
                url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
                data: $('#formreg').serialize(),
                success: function(data)
                {
                  preload(false);
                  //console.log(data);
                  jdata = JSON.parse(data);                 
                  if(jdata.ret==0) 
                  {                                        
                    window.parent.Ext.notify.msg('Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil diproses, session dilanjutkan...', jdata.msg);
                    window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>&form=tdl';
                  }else 
                  {
                    alert(jdata.msg);
                  }
                }
              });
          }
        });

        $('#btn_tolak').click(function() {
          $('#ACTION_TYPE').val('Batal');
          // if($('#tb_keterangan').val()!=""){
            var r = confirm('Apakah Anda Yakin Untuk Membatalkan Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' ?');
            if (r == true) {
                path_ori = '<?=$_GET['path']?>';
                path     = path_ori.replace('.php','');
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
                  data: $('#formreg').serialize(),
                  success: function(data)
                  {
                    preload(false);
                    //console.log(data);
                    jdata = JSON.parse(data);                 
                    if(jdata.ret==0) 
                    {                                        
                      window.parent.Ext.notify.msg('Permintaan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil diproses, session dilanjutkan...', jdata.msg);
                      if('<?=$ses_reg_role?>'== "6"){
                          window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&tb_kode_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>&form=tdl';
                      }else{
                          window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&tb_kode_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
                      }
                      
                    }else 
                    {
                      alert(jdata.msg);
                    }
                  }
                });
            }
         
        });

        $('#btn_batal_approval').click(function() {
          $('#ACTION_TYPE').val('Batal_Approve');
          // if($('#tb_keterangan').val()!=""){
            var r = confirm('Apakah Anda Yakin Untuk Membatalkan Persetujuan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' ?');
            if (r == true) {
                path_ori = '<?=$_GET['path']?>';
                path     = path_ori.replace('.php','');
                $.ajax({
                  type: 'POST',
                  url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
                  data: $('#formreg').serialize(),
                  success: function(data)
                  {
                    preload(false);
                    //console.log(data);
                    jdata = JSON.parse(data);                 
                    if(jdata.ret==0) 
                    {                                        
                      window.parent.Ext.notify.msg('Persetujuan Koreksi Data dengan No. Agenda '+$('#kd_agenda').val()+' berhasil dibatalkan, session dilanjutkan...', jdata.msg);
                      window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&tb_kode_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>&form=tdl';
                    }else 
                    {
                      alert(jdata.msg);
                    }
                  }
                });
            }
         
        });

        <?PHP
        };
        ?>

			<?PHP						
      };
			//------------------- end TASK -------------------------------------------
			?>	

                					

      window.dataid          = '';
      window.urlpath         = '';
      window.kodejenisagenda = '';
      window.arr_dok_all     = [];

      $('#keyword').focus();      
      
			$('input').keyup(function() {
      	this.value = this.value.toUpperCase();
      });			
      
			$('textarea').keyup(function() {
      	this.value = this.value.toUpperCase();
      });
			      
      $('#type').change(function(e) {
      	$('#keyword').focus();
      });
      
			$("#menutable").hide();
      
      $('#approve').attr('disabled','disabled');
      $(".menutable").html($('#menutable').html());
      $(".menutable").hide();
			
      //$("#tableId").on("click", ".activeAccount", function(){
       // window.onload =  loadData($('#pilihan_koreksi').val());
       window.onload =  loadData();
      
      $('#btn_view').click(function() {
        if(window.dataid != ''){
          window.location='pn6001.php?task=View&kd_perihal='+window.kodejenisagenda+'&path='+window.urlpath+'&kd_agenda='+window.dataid+'&mid=<?=$mid;?>';
        } else {
          alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }      
      });
			
      $('#btn_edit').click(function() {						
        if(window.dataid != ''){
        	window.location='pn6001.php?task=Edit&kd_perihal='+window.kodejenisagenda+'&path='+window.urlpath+'&kd_agenda='+window.dataid+'&mid=<?=$mid;?>';
        } else {
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        } 								
      });
			
      $('#btn_delete').click(function() {
        if(window.dataid != ''){
          var r = confirm("Apakah anda yakin ?");
          if (r == true) 
					{
            $.ajax(
						{
              type: 'POST',
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php?'+Math.random(),
              data: { TYPE:'Delete', DATAID:window.dataid},
              success: function(data) {
              	jdata = JSON.parse(data);
                if (jdata.ret=='0'){
                  window.parent.Ext.notify.msg('Berhasil',jdata.msg);
                  loadData();
                }else{
                  window.parent.Ext.notify.msg('Gagal',jdata.msg);
                }
              	
              }
            });          
          }
        }else{
        	alert('Silahkan pilih terlebih dahulu data yang akan di proses!');
        }
      });
			
      $('#btn_new').click(function() {
      	window.location='pn6001.php?task=New&kd_agenda='+window.dataid+'&mid=<?=$mid;?>';
      });
      
      $("#btncari").click(function() {
		if ($('input[name=rg_kategori]:checked').length > 0) {
			loadData($('input[name=rg_kategori]:checked').val());
		}
		else
		{
			loadData();
		}
      });
      $("#pilihan_koreksi").change(function(){
          window.location = 'pn6001.php?taskno='+this.value;
      });
      $("#pilihan_koreksi").change(function(){
        var selected_perihal = $('#pilihan_koreksi').val();
        //window.location ="<?=$php_file_name;?>.php?task=<?=$task_code;?>&taskno="+this.value;
        //var new_location = window.location.href.replace("#", "") + '&kode_perihal='+$('#pilihan_koreksi').val()+'';
        //window.location.replace(new_location);
        //loadData(selected_perihal);
        
    });

    });
		//<!--end $(document).ready(function() ------------------------------------ ->
				
    function set_dataid(kode_agenda,url_path,kode_jenis_agenda_detil,st_agenda){
        window.dataid          = kode_agenda;
        window.urlpath         = url_path;
        window.kodejenisagenda = kode_jenis_agenda_detil;
        window.st_agenda       = st_agenda;
        //alert(window.st_agenda);

        // if((window.dataid != '') && (window.st_agenda == 'TERBUKA')){
        //   window.location='kn5055.php?task=Edit&kd_perihal='+window.kodejenisagenda+'&path='+window.urlpath+'&kd_agenda='+window.dataid+'&mid=<?=$mid;?>';
          
        // } else if(window.dataid != '' && window.st_agenda != 'TERBUKA' && (window.st_agenda != '' || window.st_agenda != null)){
        //   window.location='kn5055.php?task=View&kd_perihal='+window.kodejenisagenda+'&path='+window.urlpath+'&kd_agenda='+window.dataid+'&mid=<?=$mid;?>';
        // }
        // else {
        //   alert("Status Agenda Tidak Sesuai !");
        // } 
        if(window.dataid != ''){
          window.location='pn6001.php?task=View&kd_perihal_detil='+window.kodejenisagenda+'&path='+window.urlpath+'&kd_agenda='+window.dataid+'&mid=<?=$mid;?>';
        }
        else {
          alert("Agenda Tidak Ditemukan!");
        } 
    }	

    function loadData(reg_kategori)
		{
      //window.perihal = perihal;
      //alert( window.perihal);
      //preload(true);
      window.table = $('#table_koreksi').DataTable({
        "scrollCollapse"  : true,
        "paging"          : true,
        'sPaginationType' : 'full_numbers',
        scrollY           : true,
        scrollX           : true,
        "processing"      : true,
        "serverSide"      : true,
        "search"          : {
              "regex": true
        },
        select            : true,
        "searching"       : true,
        "destroy"         : true,
            "ajax"        : {
              "url"  : "http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_action.php",
              "type" : "POST",
              "data" : function(e) { 
                // e.TYPE              = window.perihal;
                e.TYPE              = 'QUERY';
                e.SUBTYPE           = '<?=$_REQUEST['taskno'];?>';
                e.search_pilihan    = $('#search_pilihan').val();
                e.keyword           = $('#keyword').val();
                e.tgl_awal_display  = $('#tglawaldisplay').val();
                e.tgl_akhir_display = $('#tglakhirdisplay').val(); 
                e.reg_kategori      = reg_kategori;     
              },complete : function(data){
                preload(false);
                //console.log(data);
              },error: function (data){
                  //console.log(data);
                  alertError("Terjadi kesalahan pada server. Silahkan dicoba beberapa saat lagi");
                  preload(false);
                }
            },
            "columns": [
            <?php if($_REQUEST['taskno']=='PP0101') {?>
				{ "title"     : "Kode Agenda",
                  "data"      : "URL_PATH",
                  "width"     : "1%",
                  "className" : "dt-center",
                  "render"    : function(d,t,r){
                      return '<a href="#" onClick="set_dataid(\''+r['KODE_AGENDA']+'\',\''+d+'\',\''+r['KODE_JENIS_AGENDA_DETIL']+'\',\''+r['STATUS_AGENDA']+'\')"> <u><font color="#009999">'+r['KODE_AGENDA']+'</font></u> </a>';
                  } 
                },
                // { "title"     : "Jenis",
                //   "data"      : "NAMA_JENIS_AGENDA",
                //   "width"     : "15%",
                //   "className" : "dt-center"  },
                { "title"     : "Perihal",
                  "data"      : "NAMA_JENIS_AGENDA_DETIL",
                  "width"     : "19%",
                  "className" : "dt-body-center dt-head-center"  },
                { "title"    : "Kantor",
                  "data"      : "KODE_KANTOR",
                  "width"     : "6%",
                  "className" : "dt-center dt-head-center"
                },
                { "title"     : "Kode Faskes",
                  "data"      : "KODE_FASKES",
                  "width"     : "10%",
                  "className" : "dt-center"  },
                { "title"     : "No Faskes",
                  "data"      : "NO_FASKES",
                  "width"     : "10%",
                  "className" : "dt-center"  },
                { "title"     : "Nama Faskes",
                  "data"      : "NAMA_FASKES",
                  "width"     : "20%",
                  "className" : "dt-body-center dt-head-center"  },
                { "title"     : "Tgl Agenda",
                  "data"      : "TGL_AGENDA",
                  "width"     : "10%",
                  "className" : "dt-center"  },
                { "title"     : "Status",
                  "data"      : "DETIL_STATUS",
                  "width"     : "10%",
                  "className" : "dt-center dt-head-center"
                }
            <?php }
             else {?>
                { "title"     : "Kode Agenda",
                  "data"      : "URL_PATH",
                  "width"     : "1%",
                  "className" : "dt-center",
                  "render"    : function(d,t,r){
                      return '<a href="#" onClick="set_dataid(\''+r['KODE_AGENDA']+'\',\''+d+'\',\''+r['KODE_JENIS_AGENDA_DETIL']+'\',\''+r['STATUS_AGENDA']+'\')"> <u><font color="#009999">'+r['KODE_AGENDA']+'</font></u> </a>';
                  } 
                },
                { "title"     : "Perihal",
                  "data"      : "NAMA_JENIS_AGENDA_DETIL",
                  "width"     : "19%",
                  "className" : "dt-body-center dt-head-center"  },
                { "title"    : "Kantor",
                  "data"      : "KODE_KANTOR",
                  "width"     : "6%",
                  "className" : "dt-center dt-head-center"
                },
                { "title"     : "Kode Klaim",
                  "data"      : "KODE_KLAIM",
                  "width"     : "10%",
                  "className" : "dt-center"  },
                { "title"     : "KPJ",
                  "data"      : "KPJ",
                  "width"     : "10%",
                  "className" : "dt-center"  },
                { "title"     : "Nama TK",
                  "data"      : "NAMA_TK",
                  "width"     : "20%",
                  "className" : "dt-body-center dt-head-center"  },
                { "title"     : "Tgl Agenda",
                  "data"      : "TGL_AGENDA",
                  "width"     : "10%",
                  "className" : "dt-center"  },
                { "title"     : "Status",
                  "data"      : "DETIL_STATUS",
                  "width"     : "10%",
                  "className" : "dt-center dt-head-center"
                }
            <?php 
			}
              ?>
            ]
          });
    }

    function execute_form(){
      path_ori = '<?=$_GET['path']?>';
      path     = path_ori.replace('.php','');
      $.ajax({
        type: 'POST',
        url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/'+path+'_action.php?'+Math.random(),
        data: $('#formreg').serialize(),
        success: function(data)
        {
          preload(false);
            //console.log(data);
          jdata = JSON.parse(data);                 
          if(jdata.ret==0)
          {                                        
            window.parent.Ext.notify.msg('Penyimpanan data berhasil dengan kode agenda '+$('#kd_agenda').val()+', session dilanjutkan...', jdata.msg);
            // window.location='kn5055.php?task=Edit&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
            window.location='pn6001.php?task=View&path='+path_ori+'&kd_agenda='+$('#kd_agenda').val()+'&kd_perihal_detil='+$('#tb_kode_perihal_detil').val()+'&mid=<?=$mid;?>';
          }else 
          {
            alert(jdata.msg);
          }
        }
      });  
    }

    function check_dokumen_checklist(){
      var table   = document.getElementById("tblrincian1");
      var tr      = table.getElementsByTagName("tr");
      var error   = 0;
      var arr_dok = [];

      //Untuk dapatkan nilai checkbox nya di mulai dari index 3 sampai dari total - 2
      for(i=3;i<=tr.length-2;i++){
        if($('#d_adm_flag_mandatory'+(i-3)).val()=='Y' && $('#d_adm_status_diserahkan'+(i-3)).is(':checked')==false){
          error += 1;
        }else{
          error += 0;
        }
      }

      return error;
    }

    function stopRKey(evt) {
      var evt = (evt) ? evt : ((event) ? event : null);
      var node = (evt.target) ? evt.target : ((evt.srcElement) ? evt.srcElement : null);
      if ((evt.keyCode == 13) && (node.type=="text"))  {return false;}
    }
    document.onkeypress = stopRKey;
		//<!--end function loadData() --------------------------------------------- ->			
	</script>	
	<!-- end LOAD DATA -->
		
</form>	
<?php
include_once "../../includes/footer_app_nosql.php";
?>