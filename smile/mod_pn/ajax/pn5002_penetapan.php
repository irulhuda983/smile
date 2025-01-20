<?
$pagetype="report";
$gs_pagetitle = "PN5002 - PENETAPAN KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ls_root_form = "pn5002.php";
$ls_current_form = "pn5002_penetapan.php";
$ls_form_penetapan = "pn5002_penetapan.php";

/*--------------------- Form History -------------------------------------------
File: pn5002_penetapan.php

Deskripsi:
-----------
File ini dipergunakan untuk entry penetapan klaim

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
$ls_root_sender  = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 			 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_mid 			 	 = !isset($_GET['mid']) ? $_POST['mid'] : $_GET['mid'];
$ls_task 				 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_activetab  	 = !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];
if ($ls_activetab=="")
{
 $ls_activetab = "2";
}

if ($ls_kode_klaim!="")
{
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, status_klaim, kode_pointer_asal, id_pointer_asal, ".
			 	 "			 no_penetapan, nvl(a.status_submit_penetapan,'T') status_submit_penetapan, nvl(a.status_submit_pengajuan,'T') status_submit_pengajuan, ".
				 "			 case when substr(kode_tipe_klaim,1,3) = 'JPN' then ".
				 "			 			'JP'||a.kode_kantor||to_char(sysdate,'mmyyyy')||a.no_klaim ".
				 "			 else ".
				 "			 			substr(kode_tipe_klaim,1,3)||a.kode_kantor||to_char(sysdate,'mmyyyy')||a.no_klaim ".
				 "			 end set_no_penetapan ".
				 "from sijstk.pn_klaim a ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];
	$ls_jenis_klaim				= $row['JENIS_KLAIM'];
	$ls_no_penetapan			= $row['NO_PENETAPAN'];
	$ls_status_submit_penetapan	= $row['STATUS_SUBMIT_PENETAPAN'];
	$ls_status_submit_pengajuan	= $row['STATUS_SUBMIT_PENGAJUAN'];
	$ls_set_no_penetapan	= $row['SET_NO_PENETAPAN'];
	
	//update no,tgl dan petugas penetapan jika masih kosong ----------------------
	if ($ls_status_klaim=="PENETAPAN" && $ls_no_penetapan=="")
	{
  		$sql = "update sijstk.pn_klaim ".
  				 	 "set no_penetapan = '$ls_set_no_penetapan', ".
  					 "		tgl_penetapan = sysdate, ".
  					 "		petugas_penetapan = '$username', ".
  					 "		tgl_ubah = sysdate, ".
  					 "		petugas_ubah = '$username' ".
      	 	 	 "where kode_klaim = '$ls_kode_klaim' ";
      $DB->parse($sql);
      $DB->execute();		 	 
	}		
}

if ($ls_jenis_klaim=="JHT")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN HARI TUA (JHT)";  	  	 
}else if ($ls_jenis_klaim=="JHM")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JHT/JKM";  	  	 
}else if ($ls_jenis_klaim=="JKK")
{
 	if ($ls_status_klaim	== "PENGAJUAN_TAHAP_I")
	{
	 	$gs_pagetitle = "PN5002 - PENGAJUAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)";	
 	}else
	{
	 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN KECELAKAAN KERJA (JKK)"; 
	}		  	 
}else if ($ls_jenis_klaim=="JKM")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN KEMATIAN (JKM)";  	  	 
}else if ($ls_jenis_klaim=="JPN")
{
 	$gs_pagetitle = "PN5002 - PENETAPAN KLAIM JAMINAN PENSIUN (JP)";  	  	 
}

// ------------- end KLAIM PROFILE ---------------------------------------------

include "../ajax/pn5002_js.php";
include "../ajax/pn5002_actionbutton.php";

?>

<!--
<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>

<div id="container-popup">
<div id="clearie6"></div>
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
		<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
		<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="mid" name="mid" value="<?=$ls_mid;?>">
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">	
		<input type="hidden" id="btn_task" name="btn_task" value="">
		<input type="hidden" name="trigersubmit" value="0">

		<?
    if ($ls_jenis_klaim=="JHT")
    {
     	include "../ajax/pn5002_penetapan_jht.php";	  	 
    }else if ($ls_jenis_klaim=="JHM")
    {
     	include "../ajax/pn5002_penetapan_jhm.php";  	  	 
    }else if ($ls_jenis_klaim=="JKK")
    {
     	if ($ls_status_klaim	== "PENGAJUAN_TAHAP_I")
    	{
    	 	include "../ajax/pn5002_pengajuan_jkk.php";
     	}else
    	{
    	 	include "../ajax/pn5002_penetapan_jkk.php";
    	}		  	 
    }else if ($ls_jenis_klaim=="JKM")
    {
     	include "../ajax/pn5002_penetapan_jkm.php";  	  	 
    }else if ($ls_jenis_klaim=="JPN")
    {
     	include "../ajax/pn5002_penetapan_jpn.php";  	 
    }
		?>

    <div id="buttonbox" style="width:1050px; text-align:center;">
      <?php
      if ($ls_kode_klaim!="" && ($ls_status_klaim=="PERSETUJUAN" || $ls_status_klaim=="PENETAPAN" || $ls_status_klaim=="PENGAJUAN_TAHAP_I" ||$ls_root_sender=="pn5002.php"))		
      {
  		 	if (($ls_jenis_klaim=="JKK" && ($ls_status_submit_pengajuan=="T" || $ls_status_submit_penetapan=="T")) || ($ls_jenis_klaim!="JKK" && $ls_status_submit_penetapan=="T"))
  			{ 			
         	?>
    			<input type="button" class="btn green" id="btntaptolak" name="btntaptolak" onclick="window.location.replace('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_penetapan_tolak.php?&kode_klaim=<?=$ls_kode_klaim;?>&root_sender=pn5002.php&sender=pn5002_penetapan.php&sender_mid=<?=$ls_sender_mid;?>','Penolakan',950,450,'no')" value="            TOLAK             " />
    			<?
				}
				
				if ($ls_status_submit_penetapan=="Y")
  			{ 			
         	?>
    			<input type="button" class="btn green" id="btntapcetak" name="btntapcetak" value="             CETAK PENETAPAN            " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5002_cetak.php?kode_klaim=<?=$ls_kode_klaim;?>&mid=<?=$mid;?>','Cetak Penetapan Klaim',700,420,'no')"/>
  				<?
				}
				?>  			

				<?
				if ($ls_jenis_klaim=="JKK" && $ls_status_klaim	== "PENGAJUAN_TAHAP_I")
				{
				 	if ($ls_status_submit_pengajuan=="T")
					{ 
    				?>
      			<input type="submit" class="btn green" id="butsimpanajujkk1" name="butsimpanajujkk1" value="       SIMPAN PENGAJUAN JKK TAHAP I       " onclick="if(confirm('Apakah anda yakin akan melakukan penyimpan data Pengajuan JKK Tahap I ..?')) fl_js_simpandataAjuJKK1();" />
      			&nbsp;
						<?
						//cek apakah kolom mandatory sudah diisi semuanya ------------------
						if ($ls_tapjkk1_kode_akibat_diderita!="")
						{
  						?>
  						<input type="button" class="btn green" id="btnsubmitajujkk1" name="btnsubmitajujkk1" onclick="if(confirm('Apakah anda yakin akan mensubmit data ke Agenda Tahap II..?')) doSubmitAjuJKK1();"  value="          SUBMIT KE AGENDA TAHAP II         " />
      				<?
						}
					}
				}else
				{
  				if ($ls_status_submit_penetapan=="T")
					{
  					//cek apakah sudah ada input manfaat/penerima manfaat sudah diisikan
            if ($ls_kode_tipe_klaim !="JPN01")
						{
  						$sql = "select count(*) as v_jml from sijstk.pn_klaim_penerima_manfaat a ".
            			 	 "where kode_klaim = '$ls_kode_klaim' ";
              $DB->parse($sql);
              $DB->execute();
              $row = $DB->nextrow();		
              $ln_cnt_penerima = $row['V_JML'];
							
							if ($ln_cnt_penerima=="0")
							{
							 	 $ls_disable_submit = "Y";
							}else
							{
    						$sql = "select count(*) as v_jml2 from sijstk.pn_klaim_penerima_manfaat a ".
              			 	 "where kode_klaim = '$ls_kode_klaim' and nama_rekening_penerima is null ";
                $DB->parse($sql);
                $DB->execute();
                $row = $DB->nextrow();		
                $ln_cnt_penerima_null = $row['V_JML2'];	
								
								if ($ln_cnt_penerima_null>="1")
								{
								 	$ls_disable_submit = "Y"; 
								}else
								{
								 	$ls_disable_submit = "T";	 
								}
							}
						}else
						{
						 	$ls_disable_submit = "T";		 
						}
						
						if ($ls_disable_submit=="Y")
						{						
  						?>
      				<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="alert('Data penetapan klaim belum dapat disubmit, harap isikan manfaat yg diterima dan informasi penerima manfaat..!!!');"  value="                 SUBMIT                 " />
    					<?
						}else
						{
  						?>
      				<input type="button" class="btn green" id="btntapsubmit" name="btntapsubmit" onclick="if(confirm('Apakah anda yakin akan mensubmit data klaim ke tahap selanjutnya..?')) doSubmitPenetapanTanpaOtentikasi();"  value="                 SUBMIT                 " />
    					<?						
						}
					}				
				}
				?>
				
      	<?
			}
      ?>
  		<input type="button" class="btn green" id="btntutup" name="btntutup" onclick="refreshRootForm();"  value="            TUTUP          " /> 
			    
		</div>	<!--end buttonbox-->		

  		<?
      if (isset($msg))		
      {
        ?>
          <fieldset style="width:1000px;">
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
