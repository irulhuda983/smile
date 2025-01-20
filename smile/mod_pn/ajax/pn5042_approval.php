<?
$pagetype="report";
$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_root_form = "pn5042.php";
$ls_current_form = "pn5042_approval.php";

/*--------------------- Form History -------------------------------------------
File: pn5042_approval.php

Deskripsi:
-----------
File ini dipergunakan untuk approval klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
24/07/2017 - Tim SIJSTK
Pembuatan Form
-------------------- End Form History ----------------------------------------*/

// ------------- KLAIM PROFILE ------------------------------------------------
$ls_kode_klaim	 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_dataid	 	 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_level	 	 = !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];
$ls_root_sender  = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 			 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_mid 			 	 = !isset($_GET['mid']) ? $_POST['mid'] : $_GET['mid'];
$ls_task 				 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_rg_kategori	 = !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
$ls_activetab  	 = !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];
	 // -----------------------------start update pending matters 09032022------------------------
$ls_msg  	 = !isset($_GET['msg']) ? $_POST['msg'] : $_GET['msg'];
	 // -----------------------------end update pending matters 09032022------------------------
if ($ls_activetab=="")
{
 $ls_activetab = "2";
}

if ($ls_kode_klaim!="")
{
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];
	$ls_jenis_klaim				= $row['JENIS_KLAIM'];
	
	//ambil level maksimum -----------------------------------------------------
  $sql = "select max(no_level) into v_max_level from sijstk.pn_klaim_approval ".
  	 	 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ln_no_level_max = $row['V_MAX_LEVEL'];			
}

if ($ls_jenis_klaim=="JHT")
{
 	$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM JAMINAN HARI TUA (JHT)";  	  	 
}else if ($ls_jenis_klaim=="JHM")
{
 	$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM JHT/JKM";  	  	 
}else if ($ls_jenis_klaim=="JKK")
{
	$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)";	  	 
}else if ($ls_jenis_klaim=="JKM")
{
 	$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM JAMINAN KEMATIAN (JKM)";  	  	 
}else if ($ls_jenis_klaim=="JPN")
{
 	$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM JAMINAN PENSIUN (JP)";  	  	 
}else if ($ls_jenis_klaim=="JKP")
{
 	$gs_pagetitle = "PN5003 - PERSETUJUAN KLAIM JAMINAN KEHILANGAN PEKERJAAN (JKP)";  	  	 
}


// ------------- end KLAIM PROFILE ---------------------------------------------

include "../ajax/pn5042_js.php";
include "../ajax/pn5042_actionbutton.php";

?>
<script>
$(document).ready(function(){

	$('#btnapvsubmit').attr("disabled", true);
	$('#btnapvtolak').attr("disabled", true);
	$("#btnapvsubmit").removeClass('btn green');
	$("#btnapvtolak").removeClass('btn green');	
	
	$("#flag_disclaimer").change(function() {
		if(this.checked) {
		  $('#btnapvsubmit').attr("disabled", false);
		  $('#btnapvtolak').attr("disabled", false);
		  $("#btnapvsubmit").addClass('btn green');
		  $("#btnapvtolak").addClass('btn green');
		}
		else{
		  $('#btnapvsubmit').attr("disabled", true);
		  $('#btnapvtolak').attr("disabled", true);
		  $("#btnapvsubmit").removeClass('btn green');
		  $("#btnapvtolak").removeClass('btn green');
		}
	});
	 // -----------------------------start update pending matters 09032022------------------------
	if('<?=$ls_msg;?>'){
		alert('<?=$ls_msg;?>');
	}
	 // -----------------------------end update pending matters 09032022------------------------
});
</script>
<!--
<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>

<div id="container-popup">
<div id="clearie6"></div>
-->

<table class="captionfoxrm">
  <style>
    #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
    #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
  </style>		
  <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  <tr><td colspan="3"></br></br></td></tr>	
</table>
	
<div id="formframe">
	<div id="formKiri" style="width:900px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
		<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
		<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
		<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    	<input type="hidden" id="mid" name="mid" value="<?=$ls_mid;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
		<input type="hidden" id="btn_task" name="btn_task" value="">
		<input type="hidden" id="rg_kategori" name="rg_kategori" value="<?=$ls_rg_kategori;?>">	
		<input type="hidden" name="trigersubmit" value="0">

		<?
    if ($ls_jenis_klaim=="JHT")
    {
     	include "../ajax/pn5042_approval_jht.php";	  	 
    }else if ($ls_jenis_klaim=="JHM")
    {
     	include "../ajax/pn5042_approval_jhm.php";  	  	 
    }else if ($ls_jenis_klaim=="JKK")
    {
     	include "../ajax/pn5042_approval_jkk.php";		  	 
    }else if ($ls_jenis_klaim=="JKM")
    {
     	include "../ajax/pn5042_approval_jkm.php";  	  	 
    }else if ($ls_jenis_klaim=="JPN")
    {
     	include "../ajax/pn5042_approval_jpn.php";  	 
    }else if ($ls_jenis_klaim=="JKP")
    {
     	include "../ajax/pn5042_approval_jkp.php";  	 
    }
		?>




	<?php
      	if ($ls_kode_klaim!="")		
      	{
    		// cek apakah jabatan yang sama dimiliki lebih dari satu orang
    		$qry_cek_jabatan = "BEGIN PN.P_PN_ARSIP_KLAIM_SIGN.X_CEK_JABATAN_APPROVAL_KLAIM('$ls_kode_klaim','$ln_no_level',:p_sukses,:p_mess);END;";
    		$proc = $DB->parse($qry_cek_jabatan);				
    		oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    		$DB->execute();				
    		$ls_sukses = $p_sukses;
    		$ls_mess = $p_mess;
		
    		if($ls_sukses == "1")
    		{
           ?>
        	<div style="width:930px; text-align:center;">
        		<br>   
        		<input type="checkbox" id="flag_disclaimer" name="flag_disclaimer">
        		<b style="font-size: 12px;">Dengan mencentang kotak ini, saya telah memeriksa dan meneliti kebenaran serta keabsahan data yang diinput / upload</b>  
        	</div>
        	<?php 
  			}
  	  	}
		?>

   		<div id="buttonbox" style="width:930px; text-align:center;">
	<?php
      	if ($ls_kode_klaim!="")		
      	{
       	?>
			<?
			//cek apakah user memiliki wewenang untuk melakukan persetujuan --------
			$sql = "select count(*) as v_cnt from sijstk.ms_pejabat_kantor a ".
				"where exists ".
				"( ".
				"    select null from sijstk.pn_klaim_approval ". 
				"    where kode_klaim = '$ls_kode_klaim' ".
				"    and kode_jabatan = a.kode_jabatan ".
				"    and kode_kantor = a.kode_kantor ".
				") ".
				"and kode_user = '$username' ".
				"and to_char(sysdate,'yyyymmdd') between to_char(a.tgl_mulai_status,'yyyymmdd') and to_char(a.tgl_akhir_status,'yyyymmdd') ";
			$DB->parse($sql);
			$DB->execute();
			$row = $DB->nextrow();		
			$ln_cnt_otorisasi = $row['V_CNT'];	 
				
			if($ls_sukses == "1")
				{
				if ($ln_cnt_otorisasi>="1")
					{
						//update 04/03/2021, jika klaim jht/jhm maka cek apakah saldo jht yg di histori saldo masih sama dg yg ditetapkan
				 		$ls_sukses_sal = "1";
        				$ls_mess_sal = "";
						
						if ($ls_jenis_klaim=="JHT" || $ls_jenis_klaim=="JHM")
						{
							$qry_cek_sal = "BEGIN PN.P_PN_PN5040.X_CEK_ULANG_SALDO('$ls_kode_klaim',:p_sukses,:p_mess);END;";
							$proc = $DB->parse($qry_cek_sal);				
							oci_bind_by_name($proc, ":p_sukses", $p_sukses_sal,32);
							oci_bind_by_name($proc, ":p_mess", $p_mess_sal,1000);
							$DB->execute();				
							$ls_sukses_sal = $p_sukses_sal;
							$ls_mess_sal = $p_mess_sal;
						}
						
        		if($ls_sukses_sal == "-1")
        		{
  						//saldo jht yg di histori saldo tidak sama dg yg ditetapkan ------
							if ($ln_no_level==$ln_no_level_max)
  						{
  							?>
  							<input type="button" class="btn green" disabled id="btnapvsubmit" name="btnapvsubmit" onclick="alert('<?=$ls_mess_sal;?>');" value="       PROSES UNTUK PERMOHONAN APPROVAL        " disabled />			                 
  							<?  			
  						}else
  						{
  							?>
  							<input type="button" class="btn green" disabled id="btnapvsubmit" name="btnapvsubmit" onclick="alert('<?=$ls_mess_sal;?>');" value="              APPROVE              " disabled />			                 
  							<?
  						}
      			}else
						{
  						//valid ----------------------------------------------------------
							if ($ln_no_level==$ln_no_level_max)
  						{
    						?>
	 						<!-- -----------------------------start update pending matters 09032022------------------------ -->
    						<input type="button" class="btn green" id="btnapvsubmit" name="btnapvsubmit" onclick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) NewWindow('../ajax/pn5042_approval_submit.php?kode_klaim=<?=$ls_kode_klaim;?>&no_level=<?=$ln_no_level;?>&bulan_manfaat_jkp=<?=$ls_jkpbulan_manfaat_ke?>&task=<?=$ls_task?>&root_sender=<?=$ls_root_sender?>&sender=<?=$ls_sender?>&activetab=<?=$ls_activetab?>&rg_kategori=<?=$ls_rg_kategori?>&dataid=<?=$ls_dataid?>','',300,50,'no'); doSubmitTanpaOtentikasi();" value="       PROSES UNTUK PERMOHONAN APPROVAL        " disabled />			                 
    						<?  			
  						}else
  						{
    						?>
																																																													
    						<input type="button" class="btn green" id="btnapvsubmit" name="btnapvsubmit" onclick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) NewWindow('../ajax/pn5042_approval_submit.php?kode_klaim=<?=$ls_kode_klaim;?>&no_level=<?=$ln_no_level;?>&bulan_manfaat_jkp=<?=$ls_jkpbulan_manfaat_ke?>&task=<?=$ls_task?>&root_sender=<?=$ls_root_sender?>&sender=<?=$ls_sender?>&activetab=<?=$ls_activetab?>&rg_kategori=<?=$ls_rg_kategori?>&dataid=<?=$ls_dataid?>','',300,50,'no'); doSubmitTanpaOtentikasi();" value="              APPROVE              " disabled />			                 
	 						<!-- -----------------------------end update pending matters 09032022------------------------ -->
							<?
  						}						
						}
						//end update 04/03/2021 --------------------------------------------
					}else
					{
						if ($ln_no_level==$ln_no_level_max)
						{
							?>
							<input type="button" class="btn green" disabled id="btnapvsubmit" name="btnapvsubmit" onclick="alert('User tidak memiliki wewenang untuk melakukan persetujuan klaim..!!!');" value="       PROSES UNTUK PERMOHONAN APPROVAL        " disabled />			                 
							<?  			
						}else
						{
							?>
							<input type="button" class="btn green" disabled id="btnapvsubmit" name="btnapvsubmit" onclick="alert('User tidak memiliki wewenang untuk melakukan persetujuan klaim..!!!');" value="              APPROVE              " disabled />			                 
							<?
						}				
					}
					?>
					&nbsp;
					<input type="button" class="btn green" id="btnapvtolak" name="btnapvtolak" onclick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5042_approval_tolak.php?&kode_klaim=<?=$ls_kode_klaim;?>&no_level=<?=$ln_no_level;?>&root_sender=pn5042.php&sender=pn5042_approval.php&sender_mid<?=$mid;?>','Penolakan',950,520,'no')" value="                 TOLAK                  " disabled />
					<?php
				}
				else
				{
					$ls_error = "1";
					$msg = $ls_mess;
				}
      }
      ?>
			<input type="button" class="btn green" id="btntutup" name="btntutup" onclick="refreshRootForm();"  value="                 TUTUP                 " />
			 
		</div>	<!--end buttonbox-->		

		<?
    if (isset($msg))		
    {
      ?>
        <fieldset style="width:930px;">
          <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
          <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
        </fieldset>		
      <?
    }
    ?>
							
	</div> <!-- end formKiri -->
</div>	<!-- end formframe -->

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>
