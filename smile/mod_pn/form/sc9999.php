<?php
$pagetype = "form";
$gs_pagetitle = "Reset User";

require_once "../../includes/header_app_nosql.php";	
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
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

	  //location.replace('REPORT_SIJSTK');

	}

	

</script>

<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
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
select.select_format {
    border-radius: 4px;
    /*background-color: #f1f1f1;*/
}


</style>
<div id="actmenu">
		<h3 style="margin-top: 7px;margin-left: 10px; color:#FFFFFF"><?=$gs_pagetitle;?></h3>			
</div>

<div id="formframeView">
	<div id="dispError1" style="color:red;line-height: 19px;text-align: center;margin-top: 5px;display: none;"></div>
	<div id="formKiri">
		<fieldset><legend>Search By</legend>
			<div id="div_search_perusahaan">
				<div  id="div_1">
					<div class="clear5"></div>
					<label>Kode User</label>
					<input type="text" id="f_kode_user" name="f_kode_user" size="26">  
					<div class="clear5"></div>
					<label>Password</label>
					<input type="password" id="f_password" name="f_password" size="26"><div class="clear5"></div>
				</div>
				<input type="button" class="btn green" id="submit" name="submit" value="Submit" title="Klik Submit data">
		        
	        </div>
			
		</fieldset>
		<br>
		<fieldset style="background: #FF0;"><legend style="background: #FF0; border: 1px solid #CCC;">KETERANGAN</legend>
	            <li>Kebutuhan redaksi.</li>
	            
	    </fieldset>
		
	</div>
</div>


<script type="text/javascript">
$(document).ready(function(){
	// $('#submit').attr("disabled","disabled");
	// $('#submit').addClass("disabled");



	$('#submit').click(function(){
		preload(true);
		window.f_kode_user = $('#f_kode_user').val();
		window.f_password = $('#f_password').val();
		$.ajax({
			type : "POST",
			url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/sc9999_action.php?'+Math.random(),
            data: { 
            	f_kode_user : window.f_kode_user,
            	f_password : window.f_password
            },
            success: function(data) {
            	console.log(data);
            	jdata = JSON.parse(data);
            	alert(jdata.msg);
            	preload(false);
            }
		});
	});

	
		
});

</script>
<?php
include "../../includes/footer_app_nosql.php";
?>