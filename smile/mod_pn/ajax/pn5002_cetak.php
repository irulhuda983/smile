<?
$pagetype = "report";
$gs_pagetitle = "PN5002 - CETAK PENETAPAN KLAIM";
require_once "../../includes/header_app.php";
//include '../../includes/fungsi_rpt.php';
include "../../includes/fungsi_newrpt.php";
	
/*--------------------- Form History -----------------------------------------
File: pn5002_cetak.php

Deskripsi:
-----------
File ini dipergunakan untuk Pencetakan Penetapan klaim

Author:
--------
Pitra

Histori Perubahan:
--------------------
25/09/2017 - TIM SIJSTK
Pembuatan Form

-------------------- End Form History --------------------------------------*/

$ls_kode_klaim			  	 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];

if (isset($_POST["st_lap_penetapan"]))
{
  $ls_st_lap_penetapan		= $_POST['st_lap_penetapan'];
  
  if ($ls_st_lap_penetapan=="on" || $ls_st_lap_penetapan=="ON" || $ls_st_lap_penetapan=="Y")
  {
  	$ls_st_lap_penetapan = "Y";
  }else
  {
  	$ls_st_lap_penetapan = "T";
  }	
}

if (isset($_POST["st_lap_kkhitungmnf"]))
{
  $ls_st_lap_kkhitungmnf	= $_POST['st_lap_kkhitungmnf'];
  
  if ($ls_st_lap_kkhitungmnf=="on" || $ls_st_lap_kkhitungmnf=="ON" || $ls_st_lap_kkhitungmnf=="Y")
  {
  	$ls_st_lap_kkhitungmnf = "Y";
  }else
  {
  	$ls_st_lap_kkhitungmnf = "T";
  }	
}
				
if ($ls_kode_klaim!="")
{
  $sql = "select status_klaim, substr(kode_tipe_klaim,1,3) jenis_klaim, ".
			 	 "			 nvl(status_submit_penetapan,'T') status_submit_penetapan, ".
				 "( ".
    		 "			select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y ".
         "         where x.kode_klaim = a.kode_klaim ".
         "         and x.kode_manfaat = y.kode_manfaat ".
         "         and nvl(y.flag_berkala,'T')='Y' ".
         "         and nvl(x.nom_biaya_disetujui,0)<>0 ".
    		 "	) cnt_berkala, ".
    		 "	( ".
    		 "			select count(*) from sijstk.pn_klaim_manfaat_detil x, sijstk.pn_kode_manfaat y ".
         "          where x.kode_klaim = a.kode_klaim ".
         "         and x.kode_manfaat = y.kode_manfaat ".
         "         and nvl(y.flag_berkala,'T')='T' ".
         "         and nvl(x.nom_biaya_disetujui,0)<>0 ".
    		 "	) cnt_lumpsum  ".				 
			 	 "from sijstk.pn_klaim a ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
				 //echo $sql;
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_jenis_klaim 					= $row['JENIS_KLAIM'];
	$ls_status_submit_penetapan  = $row['STATUS_SUBMIT_PENETAPAN'];
	$ls_status_klaim					= $row['STATUS_KLAIM'];
	$ln_cnt_berkala								= $row['CNT_BERKALA'];
	$ln_cnt_lumpsum								= $row['CNT_LUMPSUM'];
	
	if ($ln_cnt_berkala>"0")
	{
	 	 $ls_flag_berkala						= "Y";		
	}else
	{
		$ls_flag_berkala						= "T";
	}	
	
	if ($ln_cnt_lumpsum>"0")
	{
	 	 $ls_flag_lumpsum						= "Y";		
	}else
	{
		$ls_flag_lumpsum							= "T";
	}		
}

if(isset($_POST["btncetak"]))
{	
	/*---------Kirim Parameter----------------------------------------------------*/

	$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim' P_USER='$username'";	

	//CETAK REPORT PENETAPAN---------------------------------------------------------------
	if ($ls_st_lap_penetapan == "Y")
	{
		//penetapan jp berkala -----------------------------------------------------
		if ($ls_flag_berkala == "Y")
		{
  		if ($ls_status_submit_penetapan=="Y")
  		{
          $ls_nama_rpt 	.= "PNR900105.rdf";
          $ls_error 			 = "0";
    			$ls_ket_submit = "PENCETAKAN PENETAPAN JP BERKALA PADA SAAT PROSES PENETAPAN KLAIM";
    			
          //generate aktivitas klaim -----------------------------------------------
          $sql = "select nvl(max(no_urut),0)+1 as v_nourut from sijstk.pn_klaim_aktivitas ".
          		 	 "where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ln_no_urut = $row["V_NOURUT"];	
          
          $sql = "insert into sijstk.pn_klaim_aktivitas ( ".
                 "	kode_klaim, no_urut, kode_aktivitas, tgl_mulai, tgl_akhir, status_aktivitas, keterangan, tgl_rekam, petugas_rekam) ". 
                 "values ( ".
                 "	'$ls_kode_klaim', '$ln_no_urut', 'UPDATE', sysdate, sysdate, 'TERBUKA', substr(upper('$ls_ket_submit'),1,300), sysdate, '$username' ".  
                 ") ";
          $DB->parse($sql);
          $DB->execute();
          
          $sql = "update sijstk.pn_klaim_aktivitas a set status_aktivitas = 'SELESAI',tgl_akhir = sysdate,tgl_ubah = sysdate,petugas_ubah='$username' ".
                 "where kode_klaim = '$ls_kode_klaim' ".
                 "and no_urut in ".
                 "( ".
                 "     select max(no_urut) from sijstk.pn_klaim_aktivitas ".
                 "     where kode_klaim = a.kode_klaim ".
                 "     and no_urut < '$ln_no_urut' ".  
                 "     ) ";
          $DB->parse($sql);
          $DB->execute();
          //end generate aktivitas klaim -------------------------------------------
    			
    			//cetak laporan ----------------------------------------------------------								
      		$ls_pdf = $ls_nama_rpt;
          exec_rpt_enc_new(1, 'pn', $ls_nama_rpt, $ls_user_param, 'PDF');
      		//exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);				 
  		}else
  		{
  		 	 $ls_error = "1";
  			 $msg = "Penetapan Klaim belum disubmit, belum dapat dilakukan pencetakan laporan penetapan...!!!";	 
  		}
		}	
		
		//penetapan jp lumpsum	
		if ($ls_flag_lumpsum == "Y")
		{
  		if ($ls_status_submit_penetapan=="Y")
  		{   
          if ($ls_jenis_klaim=="JPN")
					{
  					$ls_nama_rpt 	.= "PNR900106.rdf";
            $ls_error 			 = "0";
      			$ls_ket_submit = "PENCETAKAN PENETAPAN JP LUMPSUM PADA SAAT PROSES PENETAPAN KLAIM";
    			}else if ($ls_jenis_klaim=="JKK")
					{
  					$ls_nama_rpt 	.= "PNR900113.rdf";
            $ls_error 			 = "0";
      			$ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JKK PADA SAAT PROSES PENETAPAN KLAIM";
    			}else if ($ls_jenis_klaim=="JKM")
					{
  					$ls_nama_rpt 	.= "PNR900115.rdf";
            $ls_error 			 = "0";
      			$ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JKM PADA SAAT PROSES PENETAPAN KLAIM";
    			}else if($ls_jenis_klaim=="JHT"){
            $ls_nama_rpt  .= "PNR900118.rdf";
            $ls_error        = "0";
            $ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JHT PADA SAAT PROSES PENETAPAN KLAIM";
          }else if($ls_jenis_klaim=="JHM"){
            $ls_nama_rpt  .= "PNR900119.rdf";
            $ls_error        = "0";
            $ls_ket_submit = "PENCETAKAN PENETAPAN KLAIM JHT PADA SAAT PROSES PENETAPAN KLAIM";
          }
					
          //generate aktivitas klaim -----------------------------------------------
          $sql = "select nvl(max(no_urut),0)+1 as v_nourut from sijstk.pn_klaim_aktivitas ".
          		 	 "where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ln_no_urut = $row["V_NOURUT"];	
          
          $sql = "insert into sijstk.pn_klaim_aktivitas ( ".
                 "	kode_klaim, no_urut, kode_aktivitas, tgl_mulai, tgl_akhir, status_aktivitas, keterangan, tgl_rekam, petugas_rekam) ". 
                 "values ( ".
                 "	'$ls_kode_klaim', '$ln_no_urut', 'UPDATE', sysdate, sysdate, 'TERBUKA', substr(upper('$ls_ket_submit'),1,300), sysdate, '$username' ".  
                 ") ";
          $DB->parse($sql);
          $DB->execute();
          
          $sql = "update sijstk.pn_klaim_aktivitas a set status_aktivitas = 'SELESAI',tgl_akhir = sysdate,tgl_ubah = sysdate,petugas_ubah='$username' ".
                 "where kode_klaim = '$ls_kode_klaim' ".
                 "and no_urut in ".
                 "( ".
                 "     select max(no_urut) from sijstk.pn_klaim_aktivitas ".
                 "     where kode_klaim = a.kode_klaim ".
                 "     and no_urut < '$ln_no_urut' ".  
                 "     ) ";
          $DB->parse($sql);
          $DB->execute();
          //end generate aktivitas klaim -------------------------------------------
    			
    			//cetak laporan ----------------------------------------------------------								
      		$ls_pdf = $ls_nama_rpt;
          exec_rpt_enc_new(1, 'pn', $ls_nama_rpt, $ls_user_param, 'PDF');
      		//exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);				 
  		}else
  		{
  		 	 $ls_error = "1";
  			 $msg = "Penetapan Klaim belum disubmit, belum dapat dilakukan pencetakan laporan penetapan...!!!";	  
  		}
		}			
	}

	

}		
//--------------------- end button action ------------------------------------
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>

<script language="JavaScript">
  function fl_js_set_st_lap_penetapan()
  {
  	var form = document.adminForm;
  	if (form.st_lap_penetapan.checked)
  	{
  		form.st_lap_penetapan.value = "Y";
  	}
  	else
  	{
  		form.st_lap_penetapan.value = "T";
  	}	
  }	
	
  function fl_js_set_st_lap_kkhitungmnf()
  {
  	var form = document.adminForm;
  	if (form.st_lap_kkhitungmnf.checked)
  	{
  		form.st_lap_kkhitungmnf.value = "Y";
  	}
  	else
  	{
  		form.st_lap_kkhitungmnf.value = "T";
  	}	
  }																 		 	 	 		 	 
</script>		
<?	
//--------------------- end fungsi lokal javascript --------------------------	
?>

<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>

<div id="container-popup">
<!--[if lte IE 6]>
<div id="clearie6"></div>
<![endif]-->	

<?	
// echo $ls_st_lap_penetapan.$ls_status_submit_penetapan.'1'.$ls_flag_lumpsum.$ls_flag_berkala;
// die;
//Nilai Default --------------------------------------------------------------													
//End Nilai Default ----------------------------------------------------------
?>				
<div id="formframe" style="width:700px;">
	<span id="dispError1" style="display:none;color:red;width:700px;"></span>
  <input type="hidden" id="st_errval1" name="st_errval1">
	<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
	<div id="formKiri" style="width:700px;">
		<fieldset style="width:700px;"><legend>Jenis Laporan</legend>	
			<?
			if ($ls_jenis_klaim=="JKK")
			{
  			?>												
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_lap_penetapan = isset($ls_st_lap_penetapan) ? $ls_st_lap_penetapan : "T";?>					
          <input type="checkbox" id="st_lap_penetapan" name="st_lap_penetapan" class="cebox" onclick="fl_js_set_st_lap_penetapan();" <?=$ls_st_lap_penetapan=="Y" ||$ls_st_lap_penetapan=="ON" ||$ls_st_lap_penetapan=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Penetapan Klaim</b></font></i>	
        </div>											
        <div class="clear"></div>
								
  			<?
			}else
			{
  			?>												
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_lap_penetapan = isset($ls_st_lap_penetapan) ? $ls_st_lap_penetapan : "T";?>					
          <input type="checkbox" id="st_lap_penetapan" name="st_lap_penetapan" class="cebox" onclick="fl_js_set_st_lap_penetapan();" <?=$ls_st_lap_penetapan=="Y" ||$ls_st_lap_penetapan=="ON" ||$ls_st_lap_penetapan=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Penetapan Klaim</b></font></i>	
        </div>											
        <div class="clear"></div>
				<!--				
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_lap_kkhitungmnf = isset($ls_st_lap_kkhitungmnf) ? $ls_st_lap_kkhitungmnf : "T";?>					
          <input type="checkbox" id="st_lap_kkhitungmnf" name="st_lap_kkhitungmnf" class="cebox" onclick="fl_js_set_st_lap_kkhitungmnf();" <?=$ls_st_lap_kkhitungmnf=="Y" ||$ls_st_lap_kkhitungmnf=="ON" ||$ls_st_lap_kkhitungmnf=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Kertas Kerja Perhitungan Manfaat</b></font></i>	
        </div>											
        <div class="clear"></div>		
				-->			
  			<?			
			}
			?>																																																														
		</fieldset>
		
		</br>
		
		<fieldset style="width:680px;text-align:center;"><legend></legend>
			<input type="submit" class="btn green" id="btncetak" name="btncetak" value="       CETAK LAPORAN       " title="Klik Untuk Cetak Laporan">										
		</fieldset>

		<?
		if (isset($msg))		
		{
		?>
		<fieldset style="width:600px;">
		<?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
		<?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?
		}
		?>
								
	</div>
</div>			
<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>