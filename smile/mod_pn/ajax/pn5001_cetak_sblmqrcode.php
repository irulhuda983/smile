<?
$pagetype = "report";
$gs_pagetitle = "PN5001 - PENCETAKAN AGENDA KLAIM";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
	
/*--------------------- Form History -----------------------------------------
File: kb9001.php

Deskripsi:
-----------
File ini dipergunakan untuk Pencetakan Laporan klaim

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
25/09/2017 - TIM SIJSTK
Pembuatan Form

-------------------- End Form History --------------------------------------*/

$ls_kode_klaim			  	 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];

if (isset($_POST["st_dokumen_administrasi"]))
{
  $ls_st_dokumen_administrasi		= $_POST['st_dokumen_administrasi'];
  
  if ($ls_st_dokumen_administrasi=="on" || $ls_st_dokumen_administrasi=="ON" || $ls_st_dokumen_administrasi=="Y")
  {
  	$ls_st_dokumen_administrasi = "Y";
  }else
  {
  	$ls_st_dokumen_administrasi = "T";
  }	
}

if (isset($_POST["st_dokumen_administrasi2"]))
{
  $ls_st_dokumen_administrasi2		= $_POST['st_dokumen_administrasi2'];
  
  if ($ls_st_dokumen_administrasi2=="on" || $ls_st_dokumen_administrasi2=="ON" || $ls_st_dokumen_administrasi2=="Y")
  {
  	$ls_st_dokumen_administrasi2 = "Y";
  }else
  {
  	$ls_st_dokumen_administrasi2 = "T";
  }	
}

if (isset($_POST["st_pernyataan_berkala"]))
{
  $ls_st_pernyataan_berkala	= $_POST['st_pernyataan_berkala'];
  
  if ($ls_st_pernyataan_berkala=="on" || $ls_st_pernyataan_berkala=="ON" || $ls_st_pernyataan_berkala=="Y")
  {
  	$ls_st_pernyataan_berkala = "Y";
  }else
  {
  	$ls_st_pernyataan_berkala = "T";
  }	
}

if (isset($_POST["st_history_jp"]))
{
  $ls_st_history_jp	= $_POST['st_history_jp'];
  
  if ($ls_st_history_jp=="on" || $ls_st_history_jp=="ON" || $ls_st_history_jp=="Y")
  {
  	$ls_st_history_jp = "Y";
  }else
  {
  	$ls_st_history_jp = "T";
  }	
}

if (isset($_POST["st_rincian_jp"]))
{
  $ls_st_rincian_jp	= $_POST['st_rincian_jp'];
  
  if ($ls_st_rincian_jp=="on" || $ls_st_rincian_jp=="ON" || $ls_st_rincian_jp=="Y")
  {
  	$ls_st_rincian_jp = "Y";
  }else
  {
  	$ls_st_rincian_jp = "T";
  }	
}
				
if ($ls_kode_klaim!="")
{
  $sql = "select status_klaim, substr(kode_tipe_klaim,1,3) jenis_klaim, ".
			 	 "			 nvl(status_submit_agenda,'T') status_submit_agenda, ".
				 "			 nvl(status_submit_agenda2,'T') status_submit_agenda2 ".
			 	 "from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_jenis_klaim 					= $row['JENIS_KLAIM'];
	$ls_status_submit_agenda  = $row['STATUS_SUBMIT_AGENDA'];
	$ls_status_submit_agenda2 = $row['STATUS_SUBMIT_AGENDA2'];
	$ls_status_klaim					= $row['STATUS_KLAIM'];
}

if(isset($_POST["btncetak"]))
{	
	/*---------Kirim Parameter----------------------------------------------------*/
	$ls_user_param = "";
	$ls_user_param .= " QUSER='$username'";
	
	//CETAK KELENGKAPAN ADMINISTRASI ---------------------------------------------
	if ($ls_st_dokumen_administrasi == "Y")
	{
		if ($ls_status_submit_agenda=="Y")
		{
      if ($ls_jenis_klaim=="JKK")
			{
  			$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
        $ls_nama_rpt 	  = "PNR900111.rdf";
        $ls_error 		  = "0";
  			 
  			$ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM JKK TAHAP I";
      }else if ($ls_jenis_klaim=="JKM")
			{
  			$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
        $ls_nama_rpt 	  = "PNR900114.rdf";
        $ls_error 		  = "0";
  			 
  			$ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM JKM";
			}else
			{
  			$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
        $ls_nama_rpt 	  = "PNR900101.rdf";
        $ls_error 		  = "0";
  			 
  			$ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM";			
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
  		exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);
						 
		}else
		{
		 	 $ls_error = "1";
			 $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";	 
		}	
	}
	
	$ls_user_param = "";
	//CETAK KELENGKAPAN ADMINISTRASI AGENDA TAHAP II -----------------------------
	if ($ls_st_dokumen_administrasi2 == "Y")
	{
		if ($ls_status_submit_agenda2=="Y" && $ls_jenis_klaim=="JKK")
		{
			$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
      $ls_nama_rpt 	  = "PNR900112.rdf";
      $ls_error 		  = "0";
			 
			$ls_ket_submit = "PENCETAKAN TANDA TERIMA KELENGKAPAN ADMINISTRASI PADA SAAT PROSES AGENDA KLAIM JKK TAHAP II";
				
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
  		exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);
						 
		}else
		{
		 	 $ls_error = "1";
			 $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";	 
		}	
	}
		
	$ls_user_param = "";
	//CETAK SURAT PERNYATAAN KLAIM BERKALA ---------------------------------------
	if ($ls_st_pernyataan_berkala == "Y")
	{
		$ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
		$ls_user_param .= " QNOKONFIRMASI='0'";	
		
		if ($ls_status_submit_agenda=="Y")
		{
      $ls_nama_rpt 	= "PNR900102.rdf";
      $ls_error 			= "0";
			 
			$ls_ket_submit = "PENCETAKAN SURAT PERNYATAAN KLAIM BERKALA PADA SAAT PROSES ".$ls_status_klaim." KLAIM";
			
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
  		exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);			 
		}else
		{
		 	 $ls_error = "1";
			 $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";	 
		}
			
	}
	
	$ls_user_param = "";
	//CETAK HISTORI IURAN JP ---------------------------------------
	if ($ls_st_history_jp == "Y")
	{
		if ($ls_status_submit_agenda=="Y")
		{
      $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
			$ls_user_param .= " QUSER='$username'";
      $ls_nama_rpt 	  = "PNR900103.rdf";
      $ls_error 			 = "0";
			 
			$ls_ket_submit = "PENCETAKAN HISTORI IURAN JP PADA SAAT PROSES ".$ls_status_klaim." KLAIM";
			
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
  		exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);		
				 
		}else
		{
		 	 $ls_error = "1";
			 $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";	 
		}	
	}

	$ls_user_param = "";
	//CETAK HISTORI IURAN JP ---------------------------------------
	if ($ls_st_rincian_jp == "Y")
	{
		if ($ls_status_submit_agenda=="Y")
		{
      $ls_user_param .= " QKODEKLAIM='$ls_kode_klaim'";
			$ls_user_param .= " QUSER='$username'";
      $ls_nama_rpt 	  = "PNR900104.rdf";
      $ls_error 			 = "0";
			 
			$ls_ket_submit = "PENCETAKAN HISTORI IURAN JP PADA SAAT PROSES ".$ls_status_klaim." KLAIM";
			
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
  		exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);		
				 
		}else
		{
		 	 $ls_error = "1";
			 $msg = "Agenda belum disubmit, belum dapat dilakukan pencetakan laporan...!!!";	 
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
  function fl_js_set_st_dokumen_administrasi()
  {
  	var form = document.adminForm;
  	if (form.st_dokumen_administrasi.checked)
  	{
  		form.st_dokumen_administrasi.value = "Y";
  	}
  	else
  	{
  		form.st_dokumen_administrasi.value = "T";
  	}	
  }	

  function fl_js_set_st_dokumen_administrasi2()
  {
  	var form = document.adminForm;
  	if (form.st_dokumen_administrasi2.checked)
  	{
  		form.st_dokumen_administrasi2.value = "Y";
  	}
  	else
  	{
  		form.st_dokumen_administrasi2.value = "T";
  	}	
  }	
		  
  function fl_js_set_st_pernyataan_berkala()
  {
  	var form = document.adminForm;
  	if (form.st_pernyataan_berkala.checked)
  	{
  		form.st_pernyataan_berkala.value = "Y";
  	}
  	else
  	{
  		form.st_pernyataan_berkala.value = "T";
  	}	
  }
	
  function fl_js_set_st_history_jp()
  {
  	var form = document.adminForm;
  	if (form.st_history_jp.checked)
  	{
  		form.st_history_jp.value = "Y";
  	}
  	else
  	{
  		form.st_history_jp.value = "T";
  	}	
  }
	
  function fl_js_set_st_rincian_jp()
  {
  	var form = document.adminForm;
  	if (form.st_rincian_jp.checked)
  	{
  		form.st_rincian_jp.value = "Y";
  	}
  	else
  	{
  		form.st_rincian_jp.value = "T";
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
			// ---------------------------- JPN --------------------------------------
			if ($ls_jenis_klaim=="JPN")
			{
  			?>												
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_dokumen_administrasi = isset($ls_st_dokumen_administrasi) ? $ls_st_dokumen_administrasi : "T";?>					
          <input type="checkbox" id="st_dokumen_administrasi" name="st_dokumen_administrasi" class="cebox" onclick="fl_js_set_st_dokumen_administrasi();" <?=$ls_st_dokumen_administrasi=="Y" ||$ls_st_dokumen_administrasi=="ON" ||$ls_st_dokumen_administrasi=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Tanda Terima Dokumen Kelengkapan Administrasi</b></font></i>	
        </div>											
        <div class="clear"></div>
				
				</br>
				
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_pernyataan_berkala = isset($ls_st_pernyataan_berkala) ? $ls_st_pernyataan_berkala : "T";?>					
          <input type="checkbox" id="st_pernyataan_berkala" name="st_pernyataan_berkala" class="cebox" onclick="fl_js_set_st_pernyataan_berkala();" <?=$ls_st_pernyataan_berkala=="Y" ||$ls_st_pernyataan_berkala=="ON" ||$ls_st_pernyataan_berkala=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Surat Pernyataan Klaim JP Berkala</b></font></i>	
        </div>											
        <div class="clear"></div>
				
				</br>
						 										 		
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_history_jp = isset($ls_st_history_jp) ? $ls_st_history_jp : "T";?>					
          <input type="checkbox" id="st_history_jp" name="st_history_jp" class="cebox" onclick="fl_js_set_st_history_jp();" <?=$ls_st_history_jp=="Y" ||$ls_st_history_jp=="ON" ||$ls_st_history_jp=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Histori Iuran JP</b></font></i>	
        </div>											
        <div class="clear"></div>
				
				</br>
				
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_rincian_jp = isset($ls_st_rincian_jp) ? $ls_st_rincian_jp : "T";?>					
          <input type="checkbox" id="st_rincian_jp" name="st_rincian_jp" class="cebox" onclick="fl_js_set_st_rincian_jp();" <?=$ls_st_rincian_jp=="Y" ||$ls_st_rincian_jp=="ON" ||$ls_st_rincian_jp=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Rincian Iuran JP</b></font></i>	
        </div>											
        <div class="clear"></div>
				
				</br>
																					
  			<?			
			} // ------------------------- JKK --------------------------------------
			else if ($ls_jenis_klaim=="JKK")
			{
  			?>		
														
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_dokumen_administrasi = isset($ls_st_dokumen_administrasi) ? $ls_st_dokumen_administrasi : "T";?>					
          <input type="checkbox" id="st_dokumen_administrasi" name="st_dokumen_administrasi" class="cebox" onclick="fl_js_set_st_dokumen_administrasi();" <?=$ls_st_dokumen_administrasi=="Y" ||$ls_st_dokumen_administrasi=="ON" ||$ls_st_dokumen_administrasi=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Tanda Terima Dokumen Agenda JKK Tahap I</b></font></i>	
        </div>											
        <div class="clear"></div>
				
				<?
				if ($ls_status_submit_agenda2=="Y")
				{
				?>
          <div class="form-row_kiri">
          <label  style = "text-align:right;">&nbsp;</label>						
            <? $ls_st_dokumen_administrasi2 = isset($ls_st_dokumen_administrasi2) ? $ls_st_dokumen_administrasi2 : "T";?>					
            <input type="checkbox" id="st_dokumen_administrasi2" name="st_dokumen_administrasi2" class="cebox" onclick="fl_js_set_st_dokumen_administrasi2();" <?=$ls_st_dokumen_administrasi2=="Y" ||$ls_st_dokumen_administrasi2=="ON" ||$ls_st_dokumen_administrasi2=="on" ? "checked" : "";?>>
            <i><font color="#009999"><b>Tanda Terima Dokumen Agenda JKK Tahap II</b></font></i>	
          </div>											
          <div class="clear"></div>		
				<?		
				}
			} // ------------------------- JKM --------------------------------------
			else if ($ls_jenis_klaim=="JKM")
			{
  			?>		
														
        <div class="form-row_kiri">
        <label  style = "text-align:right;">&nbsp;</label>						
          <? $ls_st_dokumen_administrasi = isset($ls_st_dokumen_administrasi) ? $ls_st_dokumen_administrasi : "T";?>					
          <input type="checkbox" id="st_dokumen_administrasi" name="st_dokumen_administrasi" class="cebox" onclick="fl_js_set_st_dokumen_administrasi();" <?=$ls_st_dokumen_administrasi=="Y" ||$ls_st_dokumen_administrasi=="ON" ||$ls_st_dokumen_administrasi=="on" ? "checked" : "";?>>
          <i><font color="#009999"><b>Tanda Terima Dokumen Agenda JKM</b></font></i>	
        </div>											
        <div class="clear"></div>
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