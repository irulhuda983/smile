<?
$pagetype="report";
$gs_pagetitle = "PN5001 - DAFTAR CATATAN VERIFIKASI KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: pn5040_daftarcekkelayakan.php

Deskripsi:
-----------
File ini dipergunakan untuk daftar catatan

Author:
--------
Tim SMILE

Histori Perubahan:
--------------------
28/07/2017 - pembuatan form
03/11/2019 - pemindahan sql ke ws
  
-------------------- End Form History --------------------------------------*/
$ls_kode_klaim	= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_sender 			= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 	= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];

//get data from ws -------------------------------------------------------------
if ($ls_kode_klaim!="")
{
  global $wsIp;
  $ipDev  = $wsIp."/JSPN5040/GetDataByKodeKlaim";
  $url    = $ipDev;
  $chId   = 'CORE';
  $username = $_SESSION["USER"];
  
  // set HTTP header -----------------------------------------------------
  $headers = array(
    'Content-Type'=> 'application/json',
    'X-Forwarded-For'=> $ipfwd,
  );
  
  // set POST params -----------------------------------------------------
  $data = array(
    'chId'=>$chId,
    'reqId'=>$username,
    'KODE_KLAIM'=>$ls_kode_klaim,
		'IS_INFO_KLAIM_ONLY'=>"Y",
		'ACT_POPUP'=>"DATA_CATATAN_KLAIM"
  );
  
  // Open connection -----------------------------------------------------
  $ch = curl_init();
  
  // Set the url, number of POST vars, POST data -------------------------
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
  
  // Execute post --------------------------------------------------------
  $result = curl_exec($ch);
  $resultArray = json_decode(utf8_encode($result));
	
  for($i=0;$i<ExtendedFunction::count($resultArray->DATA_CATATAN_KLAIM);$i++)
  {
	 	if ($resultArray->DATA_CATATAN_KLAIM[$i]->TIPE =="CSO")
		{
		 	$ls_kode_user_cso   = $resultArray->DATA_CATATAN_KLAIM[$i]->PETUGAS;
			$ls_tgl_proses_cso		= $resultArray->DATA_CATATAN_KLAIM[$i]->TGL;
			$ls_keterangan_wawancara = $resultArray->DATA_CATATAN_KLAIM[$i]->KETERANGAN_WAWANCARA;
			$ls_keterangan_approval = $resultArray->DATA_CATATAN_KLAIM[$i]->KETERANGAN;
		}else if ($resultArray->DATA_CATATAN_KLAIM[$i]->TIPE =="PMP")
		{
		 	$ls_kode_user_pmp 	= $resultArray->DATA_CATATAN_KLAIM[$i]->PETUGAS;
			$tgl_proses_pmp 		= $resultArray->DATA_CATATAN_KLAIM[$i]->TGL;
			$ls_hasil_elaborasi = $resultArray->DATA_CATATAN_KLAIM[$i]->KETERANGAN;			
		}																												 						    							
  }

}
//end get data from ws ---------------------------------------------------------
?>
<style>
	.div-container{
		min-width: 700px;
		width: 100%;
	}
	.div-header{
		min-width: 700px;
	}
	.div-body{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-data{
		overflow-x: auto; 
		overflow-y: auto; 
		white-space: nowrap;
	}
	.div-footer{
		padding-top: 10px;
		border-bottom: 1px solid #eeeeee;
	}
	.hr-single{
		border-top:1px double #8c8c8c;
		border-bottom:1px double #8c8c8c;
	}	
	.hr-double{
		border-top:3px double #8c8c8c;
		border-bottom:3px double #8c8c8c;
	}
  .hr-double-top{
    border-top:3px double #8c8c8c;
	}
  .hr-double-bottom{
  	border-bottom:3px double #8c8c8c;
	}
	.hr-double-left{
    border-left:3px double #8c8c8c;
	}
  .hr-double-right{
    border-right:3px double #8c8c8c;
	}
	.table-data{
		width: 100%;
		border-collapse: collapse;
		border-color: #c0c0c0;
		background-color: #ffffff;
	}
	.table-data th{
		padding: 10px 6px 10px 6px;
		font-weight: bold;
		text-align: left;
	}
	.table-data td{
		padding: 4px 6px 4px 6px;
		text-align: left;
		border-bottom: 1px solid #c0c0c0;
	}
	.table-data tr:last-child td{
		border-bottom:1px double #8c8c8c;
	}
	.table-data tbody tr:hover{
		cursor: pointer;
		background-color:#f5f5f5;
	}
  .nohover-color:hover {
		cursor: pointer!important;
    background-color:#FFFFFF!important;
	}
	.value-modified{
    background-color: #b4eeb4!important;
  }
</style>

<script language="javascript">
	$(document).ready(function(){
		$(window).bind("resize", function(){
			resize();
		});
		resize();
		setTimeout(function(){ filter(); }, 1000);
		// filter();
	});
	
	function resize(){
		$("#div_container").width($("#div_dummy").width());
		
		$("#div_header").width($("#div_dummy").width());
		$("#div_body").width($("#div_dummy").width());
		$("#div_footer").width($("#div_dummy").width());
		
		$("#div_filter").width(0);
		$("#div_data").width(0);
		$("#div_page").width(0);
		$("#div_footer").width(0);

		$("#div_filter").width($("#div_dummy_data").width());
		$("#div_data").width($("#div_dummy_data").width());
		$("#div_page").width($("#div_dummy_data").width());
		$("#div_footer").width($("#div_dummy_data").width());

		$("#div_container").css('max-height', $(window).height());
	}				
</script>

<div id="actmenu">
 	<h3 style="margin-top: 5px;margin-left: 10px; color:#FFFFFF"><font color="#ffff99" style="font-size:15px;"><?=$gs_pagetitle;?></font></h3> 
</div>
		
<div id="formframeX" style="width:98%;">
  <div id="div_dummy" style="width: 100%;"></div>
  <div id="formKiri">
  		<form name="formreg" id="formreg" style="width:80%;" role="form" method="post" enctype="multipart/form-data">
    		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
        <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
    		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
        <input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
        <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">
    		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">		
				
  			<div id="div_container" class="div-container">
  				<div id="div_header" class="div-header"></div>
  				<div id="div_body" class="div-body">
  					<div id="div_dummy_data" style="width: 100%;"></div>
  					<div id="div_data" class="div-data">
  						<div style="padding: 6px 0px 0px 0px;">
                <fieldset><legend>Catatan Verifikasi oleh Customer Service (CSO)</legend>
                  <div class="row">
                    <div>
                      <div class="l_frm"><label for="lab_kode_user_cso">Nama/Kode User: </label></div>
                      <div class="r_frm">
                       	<input type="text" id="kode_user_cso" name="kode_user_cso" value="<?php echo $ls_kode_user_cso; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
                      </div>
                      <div class="clear"></div>
                      <div class="clear"></div>
                      
											<div class="l_frm"><label for="lab_tgl_proses_cso">Tanggal Proses: </label></div>
                      <div class="r_frm">
                      	<input type="text" id="tgl_proses_cso" name="tgl_proses_cso" value="<?php echo $ls_tgl_proses_cso; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
                      </div>
                      <div class="clear"></div>
                      <div class="clear"></div>
                      
											<br>
                      <div class="l_frm"><label for="lab_keterangan_wawancara">Hasil Wawancara:</label></div>
                      <div class="r_frm">
                      <textarea id="keterangan_wawancara" name="keterangan_wawancara" style="width : 82%; color:#000;" readonly disabled><?php echo $ls_keterangan_wawancara; ?></textarea>
                      </div>
                      <div class="clear"></div>
                      
											<div class="l_frm"><label for="lab_keterangan">Keterangan Persetujuan:</label></div>
                      <div class="r_frm">
                      <textarea id="keterangan" name="keterangan" style="width : 82%; color:#000;" readonly disabled><?php echo $ls_keterangan_approval; ?></textarea>
                      </div>
                      <div class="clear"></div>
                      <div class="l_frm">
                      <label for=""></label></div>
                      <div class="r_frm">
                      </div>
                    </div>
                  </div>
                </fieldset>
                
								<br><br>
                
								<fieldset><legend>Catatan Verifikasi oleh Penata Madya Pelayanan (PMP)</legend>
                  <div class="row">
                  <div>
                  <div class="l_frm"><label for="lab_kode_user_pmp">Nama/Kode User: </label></div>
                  <div class="r_frm">
                  <input type="text" id="kode_user_pmp" name="kode_user_pmp" value="<?php echo $ls_kode_user_pmp; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
                  </div>
                  <div class="clear"></div>
                  <div class="clear"></div>
                  <div class="l_frm"><label for="lab_tgl_proses_pmp">Tanggal Proses: </label></div>
                  <div class="r_frm">
                  <input type="text" id="tgl_proses_pmp" name="tgl_proses_pmp" value="<?php echo $tgl_proses_pmp; ?>" style="color:#000;width:150px;border-width: 0;text-align:left" readonly disabled>
                  </div>
                  <div class="clear"></div>
                  <div class="clear"></div>
                  <br>
                  <div class="l_frm"><label for="lab_hasil_elaborasi">Hasil Elaborasi:</label></div>
                  <div class="r_frm">
                  <textarea id="hasil_elabarasi" name="hasil_elaborasi" style="width : 82%; color:#000;" readonly disabled><?php echo $ls_hasil_elaborasi; ?></textarea>
                  </div>
                  <div class="clear"></div>
                  </div>
                  </div>
                </fieldset>
  						</div>	 
  					</div> 
  				</div>
  				<div id="div_footer" class="div-footer">
  					<div style="background: #f2f2f2;padding:10px 20px;border:1px solid #ececec;">
  						<span><b>Keterangan:</b></span>
  						<li style="margin-left:15px;">Data yang ditampilkan adalah catatan verifikasi klaim.</li>
  					</div>				
  				</div>	 
  			</div>		
  		</form>	 
  </div> 
</div>
