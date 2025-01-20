<?php
$pagetype = "form";
$gs_pagetitle = "Setup Master Klaim JKK";
require_once "../../includes/header_app_nosql.php";	
// $_REQUEST["mid"]="108030101010000";

?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>

<!--<script type="text/javascript" src="../../javascript/bootbox.min.js"></script>-->

<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="../../assets/fonts/font-awesome-4.7.0/css/font-awesome.css">
<script language="javascript">

	function NewWindow4(mypage,myname,w,h,scroll){
    	var openwin = window.parent.Ext.create('Ext.window.Window', {
    		title: myname,
    		collapsible: true,
    		animCollapse: true,
    
    		maximizable: true,
    		width: w,
    		height: h,
    		minWidth: 600,
    		minHeight: 400,
    		y: 60,
    		layout: 'fit',
    		html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    		dockedItems: [{
      			xtype: 'toolbar',
      			dock: 'bottom',
      			ui: 'footer',
      			items: [
      				{ 
      					xtype: 'button',
      					text: 'Tutup',
      					handler : function(){
      						openwin.close();
      					}
      				}
      			]
      		}]
    	});
    	openwin.show();
    }

	

</script>
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

#formKiri button.btn {
    border: 1px solid #5492D6;
    cursor: pointer;
    padding: 2px 5px;
    font-size: 10px;
    font-family: verdana, arial, tahoma, sans-serif;
    border-radius: 2px;
    background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);
    background: -o-linear-gradient(#5DBBF6, #2788E0);
    background: -moz-linear-gradient(#5DBBF6, #2788E0);
    background: linear-gradient(#5DBBF6, #2788E0);
}
#formKiri button.btn.hapus {
    border: 1px solid #ff0404;
    cursor: pointer;
    padding: 2px 5px;
    font-size: 10px;
    font-family: verdana, arial, tahoma, sans-serif;
    border-radius: 2px;
    background: -webkit-linear-gradient(top,#5DBBF6 0%,#2788E0 100%);
    background: -o-linear-gradient(#5DBBF6, #2788E0);
    background: -moz-linear-gradient(#5DBBF6, #2788E0);
    background: linear-gradient(#fd3749, #f51b1b);
}
</style>
<?
	$selected = "";
	if(isset($_REQUEST['s'])){
		$selected = $_REQUEST['s'];
	}
?>
	<div id="formKiri">
		<fieldset><legend>Data Setup Master Klaim JKK</legend>
			<div class="form-row_kiri">
				Pilih&nbsp;
				<select name="f_setup" id="f_setup">
					<option value="">----------Pilih----------</option>
					<option value="setup_pelaporan" <?if($selected=='setup_pelaporan') echo "selected";?>>Setup Pelaporan</option>
					<option value="setup_tipe_penerima" <?if($selected=='setup_tipe_penerima') echo "selected";?>>Setup Tipe Penerima</option>
					<option value="setup_jenis_kasus" <?if($selected=='setup_jenis_kasus') echo "selected";?>>Setup Jenis Kasus</option>
					<option value="setup_lokasi_kecelakaan" <?if($selected=='setup_lokasi_kecelakaan') echo "selected";?>>Setup Lokasi Kecelakaan</option>
					<option value="setup_akibat_diderita" <?if($selected=='setup_akibat_diderita') echo "selected";?>>Setup Akibat Diderita</option>
					<option value="setup_kondisi_terakhir" <?if($selected=='setup_kondisi_terakhir') echo "selected";?>>Setup Kondisi Terakhir</option>
					<option value="setup_diagnosa" <?if($selected=='setup_diagnosa') echo "selected";?>>Setup Diagnosa</option>
					<option value="setup_group_icd" <?if($selected=='setup_group_icd') echo "selected";?>>Setup Group ICD</option>
				</select>
				<!-- <input type="button" class="btn green" id="btncarisetup" name="btncarisetup" value="TAMPILKAN" title="Klik Untuk Tampilkan data"> -->
			</div>
			
			
		</fieldset>
	</div>

	<div id="div_setup" class="clear5" style="display: none">
		<?
	 		if($selected == "setup_pelaporan"){
	 			include('pn1003g.php');
	 		}else if($selected == "setup_tipe_penerima"){
	 			include('pn1003a.php');
	 		}else if($selected == "setup_jenis_kasus"){
	 			include('pn1003b.php');
	 		}else if($selected == "setup_lokasi_kecelakaan"){
	 			include('pn1003c.php');
	 		}else if($selected == "setup_akibat_diderita"){
	 			include('pn1003d.php');
	 		}else if($selected == "setup_kondisi_terakhir"){
	 			include('pn1003e.php');
	 		}else if($selected == "setup_diagnosa"){
	 			include('pn1003f.php');
	 		}else if($selected == "setup_group_icd"){
	 			include('pn1003h.php');
	 		}
	 	?>
 	</div>
<script type="text/javascript">
	$(document).ready(function(){
		if($('#f_setup').val()!=""){
			$('#div_setup').css("display","block");
		}
		$('#f_setup').change(function(){
			if($('#f_setup').val()!=""){
				$('#div_setup').css("display","block");
				window.location= 'pn1003.php?s='+ $('#f_setup').val();
			}else{
				$('#div_setup').css("display","none");
			}
		});
		
	});

</script>
<?
include "../../includes/footer_app_nosql.php";
?>