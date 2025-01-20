<?php
$pagetype = "form";
require_once "../../includes/header_app_nosql.php";	
include '../../includes/fungsi_newrpt.php';
//------------------------------------------------------------------------------
// Menu approval klaim siap bayar melalui sentralisasi rekening kantor pusat
// dibuat tgl : 18/08/2020
//------------------------------------------------------------------------------
//set parameter ----------------------------------------------------------------
$pagetype 				= "form";
$gs_kodeform 			= "PN5072";
$chId 	 	 			 	= "SMILE";
$gs_pagetitle 		= "APPROVAL KLAIM SIAP BAYAR";												 
$gs_kantor_aktif	= $_SESSION['kdkantorrole'];
$gs_kode_user			= $_SESSION["USER"];
$gs_kode_role			= $_SESSION['regrole'];
$task 						= $_POST["task"];

$ls_rg_jenis_pembayaran = !isset($_POST['rg_jenis_pembayaran']) ? $_GET['rg_jenis_pembayaran'] : $_POST['rg_jenis_pembayaran'];
$ls_rg_jenis_pembayaran	= $ls_rg_jenis_pembayaran=="" ? "LUMPSUM" : $ls_rg_jenis_pembayaran;
//end set parameter ------------------------------------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<!-- custom css -->
<link href="../style.custom.css?a=<?=rand()?>" rel="stylesheet"/>

<link href="../../style/select2/css/select2.min.css" rel="stylesheet" />
<script type="text/javascript" src="../../style/select2/js/select2.min.js"></script>

<div class="div-action-menu">
	<div class="menu">
		<div class="item"><span id="span_page_title"><?=$gs_kodeform;?> - <?= $gs_pagetitle;?></span>	
			<input type="radio" id="rg_jenis_pembayaran_lumpsum" name="rg_jenis_pembayaran" value="LUMPSUM" onclick="fl_js_load_form();" checked><span id='rg_jenis_pembayaran_lumpsum_label'>&nbsp;LUMPSUM&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
			<input type="radio" id="rg_jenis_pembayaran_berkala" name="rg_jenis_pembayaran" value="BERKALA" onclick="fl_js_load_form();"><span id='rg_jenis_pembayaran_berkala_label'>&nbsp;JP BERKALA</span>
		</div>
	</div>
</div>

<div id="formframe" style="padding: 0px 10px 0px 10px;">
	<div id="div_dummy" style="width: 100%;"></div>
	<div class="div-header-form"></div>
	<div id="formKiri">
		<form name="formreg" id="formreg" role="form" method="post" enctype="multipart/form-data">
			<input type="hidden" id="task" name="task" value="<?=$task;?>">
			<input type="hidden" id="editid" name="editid" value="<?=$editid;?>">
			<input type="hidden" id="mid" name="mid" value="<?=$mid;?>">
			<input type="hidden" id="order_by" name="order_by" value="">
      <input type="hidden" id="order_type" name="order_type" value="">
      <input type="hidden" id="tipe" name="tipe" value="">
			
			<div id="div_container" class="div-container"></div>					
		</form>
	</div>	 
</div>

<script language="javascript">
	$(document).ready(function(){
		fl_js_InitvalRadioButton();
		fl_js_load_form();										 
	});
	
	function fl_js_InitvalRadioButton(){
		var v_task = $('#task').val();
		if (v_task==''){
			//------------------ set radio button rg_jenis_pembayaran --------------
			var v_jenis_pembayaran = $("input[name='rg_jenis_pembayaran']:checked").val();
			
  		v_jenis_pembayaran = v_jenis_pembayaran ==='' ? 'LUMPSUM' : v_jenis_pembayaran;
  		fl_js_setCheckedValueRadioButton('rg_jenis_pembayaran', v_jenis_pembayaran);
  		//---------------- end set radio button rg_jenis_pembayaran --------------
		}
	}
	
  function fl_js_setCheckedValueRadioButton(vRadioObj, vValue) 
	{
		var radios = document.getElementsByName(vRadioObj);
    //reset value ----------------
		for (var j = 0; j < radios.length; j++) {
				radios[j].checked = false;
    }
		//assign value ----------------	
    for (var j = 0; j < radios.length; j++) {
				radios[j].checked = false;
        if (radios[j].value == vValue) {
            radios[j].checked = true;
            break;
        }
    }		
  }	
	
	function getValue(val){
		return val == null ? '' : val;
	}

	function getValueArr(val){
		if (val){
		 	return val; 
		}else{
		 	return '';	 
		}
	}

	function getValueNumber(val){
		return val == null ? '0' : val;
	}
			
	function isNumber(evt) {
		evt = (evt) ? evt : window.event;
		var charCode = (evt.which) ? evt.which : evt.keyCode;
		if (charCode > 31 && (charCode < 48 || charCode > 57)) {
			return false;
		}
		return true;
	}
										
	function asyncLoading() {
		preload(true);
	}

	function asyncPreload(isloading) {
		if (isloading) {
			window.asyncLoader = setInterval(asyncLoading, 100);
		} else {
			clearInterval(window.asyncLoader);
			preload(false);
		}
	}
	
	function fl_js_reloadPage() {			 
		document.formreg.task.value = '';
		document.formreg.editid.value = '';
		document.formreg.task_detil.value = '';
		var v_jenis_pembayaran = $("input[name='rg_jenis_pembayaran']:checked").val();
		
		var p_var = '?rg_jenis_pembayaran='+v_jenis_pembayaran+'&mid=<?=$mid;?>';
		window.location.href = p_var;
	}
	
	function fl_js_load_form()
	{
	 	var v_task 						 = $('#task').val();
		var v_jenis_pembayaran = $("input[name='rg_jenis_pembayaran']:checked").val();
		
		if (v_jenis_pembayaran=="LUMPSUM")
		{
      $('#div_container').load('../ajax/pn5072_lumpsumapproval.php','');
		}else if (v_jenis_pembayaran=="BERKALA")
		{
      $('#div_container').load('../ajax/pn5072_jpberkalaapproval.php','');
		}else
		{
		 	//$('#div_container').html('');	 
		}
	}
	
	function NewWindow4(mypage,myname,w,h,scroll){
		var openwin = window.parent.Ext.create('Ext.window.Window', {
			title: myname,
			collapsible: true,
			animCollapse: true,
			maximizable: true,
			width: w,
			height: h,
			minWidth: 450,
			minHeight: 250,
			layout: 'fit',
			html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
		});
		openwin.show();
	}	
</script>

<?php
include "../../includes/footer_app_nosql.php";
?>
