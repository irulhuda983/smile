<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5006 - KONFIRMASI BERKALA";

$ls_kode_klaim							= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_konfirmasi_induk			= !isset($_GET['no_konfirmasi_induk']) ? $_POST['no_konfirmasi_induk'] : $_GET['no_konfirmasi_induk'];
$ln_no_konfirmasi						= !isset($_GET['no_konfirmasi']) ? $_POST['no_konfirmasi'] : $_GET['no_konfirmasi'];
$ls_root_sender 				 		= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 					= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 							= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 				 			 			= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$btn_task 					 				= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

$ld_tgl_konfirmasi_induk				  = $_POST['tgl_konfirmasi_induk'];
$ls_kode_penerima_berkala_induk		= $_POST['kode_penerima_berkala_induk'];
$ls_nama_penerima_berkala_induk		= $_POST['nama_penerima_berkala_induk'];
$ls_kode_tipe_penerima_induk			= $_POST['kode_tipe_penerima_induk'];
$ld_blth_awal_induk								= $_POST['blth_awal_induk'];
$ld_blth_akhir_induk							= $_POST['blth_akhir_induk'];
$ln_jml_bulan_induk								= $_POST['jml_bulan_induk'];
$ld_tgl_konfirmasi								= $_POST['tgl_konfirmasi'];
$ls_kode_kondisi_terakhir_induk		= $_POST['kode_kondisi_terakhir_induk'];
$ld_tgl_kondisi_terakhir_induk		= $_POST['tgl_kondisi_terakhir_induk'];
$ls_keterangan										= $_POST['keterangan'];

define('debug', false);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>

  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
  <script type="text/javascript"></script>
							
  <script language="JavaScript">    
  	function fl_js_val_simpan()
  	{
      var form = window.document.fpop;
      if(form.tgl_konfirmasi.value==""){
        alert('Tgl Konfirmasi tidak boleh kosong...!!!');
        form.tgl_konfirmasi.focus();
			}else if ((form.kode_kondisi_terakhir_induk.value!="" && form.tgl_kondisi_terakhir_induk.value=="") || (form.kode_kondisi_terakhir_induk.value=="" && form.tgl_kondisi_terakhir_induk.value!=""))
			{
        alert('Kondisi akhir dan tgl kondisi harus diinput keduanya...!!!');
        form.kode_kondisi_terakhir_induk.focus();																																																										
      }else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}

  	function fl_js_val_hapus()
  	{
      var form = window.document.fpop;
      if(form.no_konfirmasi.value==""){
        alert('Tidak ada data yang akan dihapus...!!!');
        form.tgl_konfirmasi.focus();
			}else
  		{
         form.btn_task.value="hapus";
         form.submit(); 		 
  		}								 
  	}
				
    function refreshParent() 
    {
  			if(window.opener.document.getElementById('formreg') != undefined)
				{																							
        	<?php	
        	if($ls_sender!='')
					{			
        		echo "window.opener.location.replace('../form/$ls_sender?mid=$ls_sender_mid');";		
        	}
        	?>	
      	}
    }						
  </script>															
</head>
<body>

  <table class="captionfoxrm">
    <style>
      #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
      #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
    </style>		
    <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
  </table>

	<?
	if($btn_task=="simpan")
	{      			
    //jika tidak ada perubahan kondisi akhir penerima berkala maka cek apakah sudah dilakukan pembayaran utk semua blth, 
		//jika blm maka blm dapat dilakukan konfirmasi lanjutan -----------------
    if ($ls_kode_kondisi_terakhir_induk =="")
		{
  		$sql = "select count(*) as v_jml from sijstk.pn_klaim_berkala_rekap ". 
             "where kode_klaim = '$ls_kode_klaim' ". 
             "and no_konfirmasi = '$ln_no_konfirmasi_induk' ". 
             "and nvl(status_lunas,'T') = 'T' ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ln_cnt_blmbyr = $row["V_JML"];	
      if ($ln_cnt_blmbyr == "0")
      {
			 	 $ls_st_bisaproses = "Y";	
			}else
			{
			 	 $ls_st_bisaproses = "T";	 
			}			
    }else
		{
		 	$ls_st_bisaproses = "Y";	 
		}
		
    if ($ls_st_bisaproses == "Y")
    {			
			if ($ls_task=="new")	
			{
  				//insert data ahli waris -----------------------------------------------
  				$sql = 	"select nvl(max(no_konfirmasi),0)+1 as v_no from sijstk.pn_klaim_berkala where kode_klaim = '$ls_kode_klaim' ";
          $DB->parse($sql);
          $DB->execute();
          $row = $DB->nextrow();
          $ln_no_konfirmasi = $row["V_NO"];
  
  				$sql = "insert into sijstk.pn_klaim_berkala ( ".
                 "  kode_klaim, no_konfirmasi, tgl_konfirmasi, kode_tipe_penerima, kode_penerima_berkala, ". 
                 "  jml_bulan, blth_awal, blth_akhir, nom_kompensasi, nom_rapel, nom_berjalan, nom_berkala, nom_pph, nom_pembulatan, nom_manfaat_netto, ". 
                 "  keterangan, status_konfirmasi, no_konfirmasi_induk, kode_kondisi_terakhir_induk, tgl_kondisi_terakhir_induk, ". 
                 "  kode_kantor_konfirmasi, petugas_konfirmasi, tgl_rekam, petugas_rekam)  ". 
                 "values ( ".
                 "    '$ls_kode_klaim', '$ln_no_konfirmasi', to_date('$ld_tgl_konfirmasi','dd/mm/yyyy'),'$ls_kode_tipe_penerima_induk', '$ls_kode_penerima_berkala_induk', ". 
                 "    '$ln_jml_bulan_induk', trunc(add_months(to_date('$ld_blth_akhir_induk','mm/yyyy'),1),'dd'), trunc(add_months(to_date('$ld_blth_akhir_induk','mm/yyyy'),(1+nvl('$ln_jml_bulan_induk',3))),'dd'), 0, 0, 0, 0, 0, 0, 0, ". 
                 "    '$ls_keterangan', 'Y', '$ln_no_konfirmasi_induk', '$ls_kode_kondisi_terakhir_induk', to_date('$ld_tgl_kondisi_terakhir_induk','dd/mm/yyyy'), ".
  							 "		'$gs_kantor_aktif', '$username', sysdate, '$username' ". 
  							 ") ";
          $DB->parse($sql);
          $DB->execute();					
			}else
			{ 
				$sql = "update sijstk.pn_klaim_berkala set ".
               "	kode_kondisi_terakhir_induk = '$ls_kode_kondisi_terakhir_induk', ". 
							 "	tgl_kondisi_terakhir_induk	= to_date('$ld_tgl_kondisi_terakhir_induk','dd/mm/yyyy'), ". 
							 "	keterangan									= '$ls_keterangan', ".
							 "	tgl_konfirmasi							= to_date('$ld_tgl_konfirmasi','dd/mm/yyyy') ".
               "  kode_kantor_konfirmasi			= '$gs_kantor_aktif', ". 
							 "	petugas_konfirmasi					= '$username', ". 
							 "	tgl_ubah										= sysdate, ". 
							 "	petugas_ubah  							= = '$username' ". 
               "where kode_klaim = '$ls_kode_klaim' ".
							 "and no_konfirmasi = '$ln_no_konfirmasi' ";
        $DB->parse($sql);
        $DB->execute();											
			}
			//echo $sql;
			
      //post update ---------------------------------------------------------- 
			$qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_KONFIRMASI_BERKALA('$ls_kode_klaim','$ln_no_konfirmasi', '$username',:p_sukses,:p_mess);END;";											 	
      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
      $DB->execute();				
      $ls_sukses = $p_sukses;	
  		$ls_mess = $p_mess;	
					     
      $msg = "Data Konfirmasi berhasil tersimpan, session dilanjutkan...";
      $task = "edit";	
      $ls_hiddenid = $ln_kini_no_konfirmasi;
      $editid = $ln_kini_no_konfirmasi;		

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "refreshParent();";
  		echo "</script>";

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "window.location.replace('?task=edit&root_sender=$ls_root_sender&sender=$ls_sender&activetab=1&sender_mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&no_konfirmasi_induk=$ln_no_konfirmasi_induk&no_konfirmasi=$ln_no_konfirmasi&msg=$msg');";
  		echo "</script>";	
		}else
		{
		 	$ls_error = "1";	 
		  $msg = "Konfirmasi Lanjutan belum dapat dilakukan, ada periode berkala yang belum dibayarkan...!!!";
			
  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "window.location.replace('?task=new&root_sender=$ls_root_sender&sender=$ls_sender&activetab=1&sender_mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&no_konfirmasi_induk=$ln_no_konfirmasi_induk&msg=$msg&ls_error=$ls_error');";
  		echo "</script>";			 		 
		}						      		            
	} //end if(isset($_POST['simpan']))
		
	if($btn_task=="hapus")
	{     
      //hapus data konfirmasi --------------------------------------------
			$sql = 	"delete from sijstk.pn_klaim_berkala_rekap where kode_klaim = '$ls_kode_klaim' and no_konfirmasi = '$ln_no_konfirmasi' ";
      $DB->parse($sql);
      $DB->execute();

      //hapus data konfirmasi --------------------------------------------
			$sql = 	"delete from sijstk.pn_klaim_berkala_detil where kode_klaim = '$ls_kode_klaim' and no_konfirmasi = '$ln_no_konfirmasi' ";
      $DB->parse($sql);
      $DB->execute();
						
			//hapus data konfirmasi --------------------------------------------
			$sql = 	"delete from sijstk.pn_klaim_berkala where kode_klaim = '$ls_kode_klaim' and no_konfirmasi = '$ln_no_konfirmasi' ";
      $DB->parse($sql);
      $DB->execute();
			
      $msg = "Data konfirmasi berhasil dihapus, session dilanjutkan...";
      $task = "edit";		

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "refreshParent();";
  		echo "</script>";

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "window.location.replace('?task=edit&root_sender=$ls_root_sender&sender=$ls_sender&activetab=1&sender_mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&no_konfirmasi_induk=$ln_no_konfirmasi_induk&msg=$msg');";
  		echo "</script>";					            
	} //end if(isset($_POST['simpan']))
			
	?>		
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
		<div id="formframe" style="width:900px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
			<input type="hidden" id="no_konfirmasi" name="no_konfirmasi" value="<?=$ln_no_konfirmasi;?>">
			<input type="hidden" id="btn_task" name="btn_task" value=""> 
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
    	<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">

      <?
			//ambil data konfirmasi induk --------------------------------------------
      if ($ls_kode_klaim !="" && $ln_no_konfirmasi_induk !="")
      {
  			$sql = "select 
                   a.kode_klaim, b.kode_kantor, b.kode_segmen, b.kode_tk, b.kpj, b.nama_tk, a.no_konfirmasi, 
  								 to_char(a.tgl_konfirmasi,'dd/mm/yyyy') tgl_konfirmasi, 
                   a.kode_tipe_penerima, a.kode_penerima_berkala, 
  								 (
                        select nama_penerima from pn.pn_klaim_penerima_berkala
                        where kode_klaim = a.kode_klaim
                        and kode_penerima_berkala = a.kode_penerima_berkala
                        and rownum = 1
                   ) nama_penerima_berkala,
                   a.jml_bulan, 
  								 to_char(a.blth_awal,'mm/yyyy') blth_awal, 
  								 to_char(a.blth_akhir,'mm/yyyy') blth_akhir, 
  								 a.keterangan
                from sijstk.pn_klaim_berkala a, sijstk.pn_klaim b
                where a.kode_klaim = b.kode_klaim
                and a.kode_klaim = '$ls_kode_klaim'
                and a.no_konfirmasi = '$ln_no_konfirmasi_induk' ";
        //echo $sql;
        $DB->parse($sql);
        $DB->execute();
        $data = $DB->nextrow();
  			$ld_tgl_konfirmasi_induk  		  = $data["TGL_KONFIRMASI"];
  			$ls_kode_tipe_penerima_induk		= $data["KODE_TIPE_PENERIMA"];	
  			$ls_kode_penerima_berkala_induk	= $data["KODE_PENERIMA_BERKALA"];
  			$ls_nama_penerima_berkala_induk	= $data["NAMA_PENERIMA_BERKALA"];
  			$ln_jml_bulan_induk							= $data["JML_BULAN"];
  			$ld_blth_awal_induk							= $data["BLTH_AWAL"];
  			$ld_blth_akhir_induk						= $data["BLTH_AKHIR"];												
      }	
  		
  		if ($ls_task == "new")
  		{
  			$sql = "select to_char(sysdate,'dd/mm/yyyy') as v_tgl from dual";
        $DB->parse($sql);
        $DB->execute();
        $data = $DB->nextrow();
  			$ld_tgl_konfirmasi	= $data["V_TGL"];		 	 
  		}
			
			//ambil data konfirmasi current --------------------------------------------
      if ($ls_kode_klaim !="" && $ln_no_konfirmasi !="")
      {
  			$sql = "select 
                   a.kode_klaim, a.no_konfirmasi, to_char(a.tgl_konfirmasi,'dd/mm/yyyy') tgl_konfirmasi, 
                   a.kode_kondisi_terakhir_induk, to_char(a.tgl_kondisi_terakhir_induk,'dd/mm/yyyy') tgl_kondisi_terakhir_induk, keterangan,
									 nvl(a.status_berhenti_manfaat,'T') status_berhenti_manfaat
                from sijstk.pn_klaim_berkala a
                where a.kode_klaim = '$ls_kode_klaim'
                and a.no_konfirmasi = '$ln_no_konfirmasi' ";
        //echo $sql;
        $DB->parse($sql);
        $DB->execute();
        $data = $DB->nextrow();
  			$ld_tgl_konfirmasi  		  			= $data["TGL_KONFIRMASI"];
  			$ls_kode_kondisi_terakhir_induk	= $data["KODE_KONDISI_TERAKHIR_INDUK"];	
  			$ld_tgl_kondisi_terakhir_induk	= $data["TGL_KONDISI_TERAKHIR_INDUK"];	
  			$ls_keterangan									= $data["KETERANGAN"];
				$ls_status_berhenti_manfaat			= $data["STATUS_BERHENTI_MANFAAT"];											
      }				
  		?>
					
			<div id="formKiri" style="width:1100px;">
				<fieldset style="width:1100px;"><legend >&nbsp;</legend>
					<table>
						<tr>
							<td>
								<table>
    							<tr>
    								<td colspan="2" style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;"><b>KONFIRMASI SEBELUMNYA</b></td>	
    							</tr>
									<tr>
                  	<td colspan="2"><hr style="border:0; border-top:3px double #8c8c8c;"/></td>	
                  </tr>
      						<tr>
      							<td>No Konfirmasi</td>			 	
          					<td style="text-align:left;">
          						<input type="text" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>" size="30" maxlength="20" readonly class="disabled">	
											<input type="text" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?=$ln_no_konfirmasi_induk;?>" size="3" maxlength="20" readonly class="disabled">
    								</td>
      						</tr>																
      						<tr>
      							<td>Tgl Konfirmasi</td>			 	
          					<td style="text-align:left;">
          						<input type="text" id="tgl_konfirmasi_induk" name="tgl_konfirmasi_induk" value="<?=$ld_tgl_konfirmasi_induk;?>" size="37" maxlength="20" readonly class="disabled">	
    								</td>
      						</tr>
      						<tr>
      							<td>Penerima Berkala</td>			 	
          					<td style="text-align:left;">
          						<input type="text" id="kode_penerima_berkala_induk" name="kode_penerima_berkala_induk" value="<?=$ls_kode_penerima_berkala_induk;?>" size="3" maxlength="20" readonly class="disabled">	
    									<input type="text" id="nama_penerima_berkala_induk" name="nama_penerima_berkala_induk" value="<?=$ls_nama_penerima_berkala_induk;?>" size="30" maxlength="100" readonly class="disabled">
    									<input type="hidden" id="kode_tipe_penerima_induk" name="kode_tipe_penerima_induk" value="<?=$ls_kode_tipe_penerima_induk;?>">
										</td>
      						</tr>
      						<tr>
      							<td>Periode Berkala</td>			 	
          					<td style="text-align:left;">
          						<input type="text" id="blth_awal_induk" name="blth_awal_induk" value="<?=$ld_blth_awal_induk;?>" size="15" maxlength="100" readonly class="disabled"> s/d
											<input type="text" id="blth_akhir_induk" name="blth_akhir_induk" value="<?=$ld_blth_akhir_induk;?>" size="15" maxlength="100" readonly class="disabled">
											<input type="hidden" id="jml_bulan_induk" name="jml_bulan_induk" value="<?=$ln_jml_bulan_induk;?>">
    								</td>
      						</tr>

									<tr>
                  	<td colspan="2">&nbsp;</td>	
                  </tr>
									    							
									<tr>
    								<td colspan="2" style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><i><u>Status Pembayaran Berkala:</u></i></td>	
    							</tr>
																
									<!-- Ambil status pembayaran dari masing2 periode berkala --->
									<?							
                  if ($ls_kode_klaim!=""  && $ln_no_konfirmasi_induk !="")
                  {			
                    $sql = "select to_char(blth_proses,'mm-yyyy') blthproses, nom_berkala, nvl(status_lunas,'T') status_lunas ".
                           "from sijstk.pn_klaim_berkala_rekap ". 
                           "where kode_klaim = '$ls_kode_klaim' ". 
                           "and no_konfirmasi = '$ln_no_konfirmasi_induk' ". 
                           "order by no_proses ";
                    //echo $sql;
          					$DB->parse($sql);
                    $DB->execute();							              					
                    $i=0;		
                    $ln_dtl =0;	
          					$ln_tot_d_jpnbkala_nom_berkala =0;								
                    while ($row = $DB->nextrow())
                    {
                    ?>
                      <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
          							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTHPROSES'];?></td>
          							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;">
														<?=number_format((float)$row['NOM_BERKALA'],2,".",",");?>
														<input type="checkbox" disabled class="cebox" id="dcb_jpnbkl_status_lunas<?=$i;?>" name="dcb_jpnbkl_status_lunas<?=$i;?>" value="<?=$row['STATUS_LUNAS'];?>" <?=$row['STATUS_LUNAS']=="Y" || $row['STATUS_LUNAS']=="ON" || $row['STATUS_LUNAS']=="on" ? "checked" : "";?>>
														<input type="hidden" id="d_jpnbkl_status_lunas<?=$i;?>" name="d_jpnbkl_status_lunas<?=$i;?>" value="<?=$row['STATUS_LUNAS'];?>">
														Bayar
												</td>
          						</tr>
                      <?								    							
                      $i++;//iterasi i
          						$ln_tot_d_jpnbkala_nom_berkala  += $row["NOM_BERKALA"];
                    }	//end while
                    $ln_dtl=$i;
                  }						
                  ?>																 
								</table>	
							</td>
							
							<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
									&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;																																													
							</td>
							
							<td>
								<table>
    							<tr>
    								<td colspan="2" style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;"><b>KONDISI AKHIR PENERIMA BERKALA</b></td>	
    							</tr>
                  <tr>
                  	<td colspan="2"><hr style="border:0; border-top:3px double #8c8c8c;"/></td>	
                  </tr>		
      						<tr>
      							<td>Tgl Konfirmasi</td>			 	
          					<td style="text-align:left;">
          						<input type="text" id="tgl_konfirmasi" name="tgl_konfirmasi" value="<?=$ld_tgl_konfirmasi;?>" size="38" maxlength="20" readonly class="disabled">
											<input id="btn_tgl_konfirmasi" type="image" align="top" onclick="return showCalendar('tgl_konfirmasi', 'dd-mm-y');" src="../../images/calendar.gif" />
										</td>
      						</tr>
      						<tr>
      							<td>Perubahan Kondisi Akhir</td>			 	
          					<td style="text-align:left;">	 	    				
                      <select size="1" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk" value="<?=$ls_kode_kondisi_terakhir_induk;?>" tabindex="1" class="select_format" style="width:120px;" >
                      <option value="">- tidak berubah -</option>
                      <?
                  			if ($ls_kode_penerima_berkala_induk=="OT")
                  			{
                  			 	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir = 'KA11' "; 
                  			}else if ($ls_kode_penerima_berkala_induk=="JD")
                  			{
                  			 	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir in ('KA11','KA12') "; 
                  			}else if ($ls_kode_penerima_berkala_induk=="TK")
                  			{
                  			 	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir = 'KA11' "; 													
                  			}else
                  			{
                  			 	$ls_filter_kondisi_akhir = "";
                  			}						 	 
                        $sql = "select kode_kondisi_terakhir,nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir ".
          									 	 "where kode_tipe_klaim =  'JPN01' ".
          										 $ls_filter_kondisi_akhir.
          										 "order by kode_kondisi_terakhir";		 
                        $DB->parse($sql);
                        $DB->execute();
                        while($row = $DB->nextrow())
                        {
                          echo "<option ";
                          if ($row["KODE_KONDISI_TERAKHIR"]==$ls_kode_kondisi_terakhir_induk && strlen($ls_kode_kondisi_terakhir_induk)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
                          echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
                        }
                      ?>
                      </select>
    									&nbsp;Sejak
											<input type="text" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" value="<?=$ld_tgl_kondisi_terakhir_induk;?>" size="10" maxlength="10" tabindex="2" onblur="convert_date(tgl_kondisi_terakhir_induk);">
           						<input id="btn_tgl_kondisi_terakhir_induk" type="image" align="top" onclick="return showCalendar('tgl_kondisi_terakhir_induk', 'dd-mm-y');" src="../../images/calendar.gif" />	
    								</td>
      						</tr>
      						<tr>
      							<td>Keterangan</td>			 	
          					<td style="text-align:left;">
          						<textarea cols="255" rows="1" style="width:245px" id="keterangan" name="keterangan" tabindex="3" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>	
    								</td>
      						</tr>
									<tr>
										<td>&nbsp;</td>			 	
          					<td style="text-align:left;">
                      <input type="checkbox" disabled class="cebox" id="cb_status_berhenti_manfaat" name="cb_status_berhenti_manfaat" value="<?=$ls_status_berhenti_manfaat;?>" <?=$ls_status_berhenti_manfaat=="Y" || $ls_status_berhenti_manfaat=="ON" || $ls_status_berhenti_manfaat=="on" ? "checked" : "";?>>
                      <input type="hidden" id="status_berhenti_manfaat" name="status_berhenti_manfaat" value="<?=$ls_status_berhenti_manfaat;?>">
											Berhenti Manfaat JP Berkala
										</td>	
									</tr>
									<tr>
          					<td style="text-align:left;" colspan="2">
											<font color="#ff0000"><i>Note : - Isikan Perubahan Kondisi Akhir dan Tglnya jika Penerima Manfaat Meninggal/Kawin/dll.</i></font>
										</td>	
									</tr>	
									<tr>
          					<td style="text-align:left;" colspan="2">
											<font color="#ff0000"><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Manfaat Otomatis akan diberikan kepada penerima manfaat selanjutnya jika ada.</i></font>
										</td>	
									</tr>
									<tr>
          					<td style="text-align:left;" colspan="2">
											<font color="#ff0000"><i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;- Kosongkan jika tidak ada perubahan kondisi akhir.</i></font>
										</td>	
									</tr>																																																 
								</table>	
							</td>	
						</tr>		 
					</table>				
				</fieldset>	
				
				</br>
				
				<?
				if ($ln_no_konfirmasi!="")
				{
				 	?>
          <fieldset style="width:1100px;"><legend>Manfaat Pensiun Berkala</legend>
            <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
              <tbody>	
        				<tr>
        					<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        				</tr>									
                <tr>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Program</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan ke-</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bulan</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Berjalan</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Rapel</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Kompensasi</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Jumlah Berkala</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:150px;">Action</th>
                </tr>
        				<tr>
        					<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        				</tr>				
                <?							
                if ($ls_kode_klaim!="")
                {			
                  $sql = "select a.kode_klaim, a.no_konfirmasi, a.kd_prg, b.nm_prg, a.no_proses, to_char(a.blth_proses,'mm/yyyy') blth_proses, ".
        							 	 "			 a.nom_kompensasi, a.nom_rapel, a.nom_berjalan, a.nom_berkala ".
                         "from sijstk.pn_klaim_berkala_rekap a, sijstk.ms_prg b ".
                         "where a.kd_prg = b.kd_prg  ".
                         "and a.kode_klaim = '$ls_kode_klaim' ".
                         "and a.no_konfirmasi = '$ln_no_konfirmasi' ".
                         "order by a.no_proses";
                  //echo $sql;
        					$DB->parse($sql);
                  $DB->execute();							              					
                  $i=0;		
                  $ln_dtl =0;	
                  $ln_tot_d_nom_kompensasi  = 0;
                  $ln_tot_d_nom_rapel = 0;
                  $ln_tot_d_nom_berjalan = 0;
                  $ln_tot_d_nom_berkala = 0;						
                  while ($row = $DB->nextrow())
                  {
                  ?>
                    <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>	
                      <td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NM_PRG'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_PROSES'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BLTH_PROSES'];?></td>
        							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERJALAN'],2,".",",");?></td>
        							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_RAPEL'],2,".",",");?></td>
        							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_KOMPENSASI'],2,".",",");?></td>
        							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>																		       																			        											
                      <td align="center">
                      	<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_view_jpn_manfaatberkalarinci.php?&kode_klaim=<?=$row["KODE_KLAIM"];?>&no_konfirmasi=<?=$row["NO_KONFIRMASI"];?>&no_proses=<?=$row["NO_PROSES"];?>&kd_prg=<?=$row["KD_PRG"];?>&sender=pn5001_agenda_jpn_manfaatberkala.php&sender_activetab=4&sender_mid=<?=$mid;?>','Rincian Manfaat Pensiun Berkala',1100,500,'no')"><img src="../../images/indent_right.gif" border="0" alt="Rincian Manfaat Pensiun Berkala" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> RINCIAN MANFAAT </font></a>
          						</td>
                    </tr>
                    <?								    							
                    $i++;//iterasi i
        						$ln_tot_d_nom_kompensasi  += $row["NOM_KOMPENSASI"];
        						$ln_tot_d_nom_rapel  += $row["NOM_RAPEL"];
        						$ln_tot_d_nom_berjalan  += $row["NOM_BERJALAN"];
        						$ln_tot_d_nom_berkala  += $row["NOM_BERKALA"];
                  }	//end while
                  $ln_dtl=$i;
                }						
                ?>									             																
              </tbody>
        			<tr><td colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
              <tr>
                <td style="text-align:right;" colspan="3"><i>Total Keseluruhan :<i>
                  <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                  <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                  <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">
        				</td>	  		
        				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berjalan,2,".",",");?></td>
        				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_rapel,2,".",",");?></td>							
                <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_kompensasi,2,".",",");?></td>
        				<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_nom_berkala,2,".",",");?></td>
        				<td></td>										        
              </tr>																
            </table>
          </fieldset>
        	
        	</br>
        	
          <fieldset style="width:1100px;"><legend>Penerima Manfaat Berkala</legend>
            <table id="tblrincian1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
              <tbody>
        				<tr>
        					<th colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
        				</tr>									
                <tr>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Tipe</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Hubungan</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nama</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">NPWP</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Bank</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">No.Rek</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">A/N</th>
        					<th style="text-align:center;font: 12px Arial, Helvetica, sans-serif;">Nominal</th>
                  <th style="text-align:center;font: 12px Arial, Helvetica, sans-serif; width:150px;">Action</th>
                </tr>
        				<tr>
        					<th colspan="9"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
        				</tr>
                <?							
                if ($ls_kode_klaim!="")
                {			
                  $sql = "select ".
                         "     a.kode_klaim, a.no_konfirmasi, ". 
                         "     a.kode_tipe_penerima, (select nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima = a.kode_tipe_penerima) nama_tipe_penerima, ".
                         "     a.kode_penerima_berkala, decode(a.kode_penerima_berkala,'TK','TENAGA KERJA','JD','JANDA/DUDA','A1', 'ANAK I','A2', 'ANAK II','OT', 'ORANG TUA', a.kode_penerima_berkala) nama_kode_penerima_berkala, ".
        								 "		 b.nama_lengkap, b.npwp, ". 
                         "     b.bank_penerima, b.no_rekening_penerima, b.nama_rekening_penerima, ".
                         "     a.nom_berkala ". 
                         "from sijstk.pn_klaim_berkala a, sijstk.pn_klaim_penerima_berkala b ".
                         "where a.kode_klaim = b.kode_klaim (+) ". 
                         "and a.kode_penerima_berkala = b.kode_penerima_berkala(+) ".
                         "and a.kode_klaim = '$ls_kode_klaim' ".
                         "and a.no_konfirmasi = '$ln_no_konfirmasi' ". 								 
                         "order by b.no_urut_keluarga";
                  //echo $sql;
        					$DB->parse($sql);
                  $DB->execute();							              					
                  $i=0;		
                  $ln_dtl =0;	
        					$ln_tot_d_jpnbkala_nom_berkala =0;								
                  while ($row = $DB->nextrow())
                  {
                  ?>
                    <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_TIPE_PENERIMA'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_KODE_PENERIMA_BERKALA'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_LENGKAP'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NPWP'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['BANK_PENERIMA'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NO_REKENING_PENERIMA'];?></td>
        							<td style="text-align:center; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row['NAMA_REKENING_PENERIMA'];?></td>
        							<td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NOM_BERKALA'],2,".",",");?></td>																		       																			        																												       																			        											
                      <td align="center">
        								<?
        								 	$ls_task_pnrm = "edit"; 
        								?>										
                     		<a href="#" onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_view_jpn_manfaatberkalapenerima.php?&task=<?=$ls_task_pnrm;?>&kode_klaim=<?=$row["KODE_KLAIM"];?>&kode_tipe_penerima=<?=$row["KODE_TIPE_PENERIMA"];?>&kode_penerima_berkala=<?=$row["KODE_PENERIMA_BERKALA"];?>&root_sender=pn5002_penetapan.php&sender=pn5002_penetapan.php&sender_activetab=2&sender_mid=<?=$mid;?>','Detil Informasi Penerima Manfaat',860,600,'no')"><img src="../../images/user_go.png" border="0" alt="Entry Detil Penerima" align="absmiddle" /><font style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"> DETIL PENERIMA </font></a>																																																				
                      </td>
                    </tr>
                    <?								    							
                    $i++;//iterasi i
        						$ln_tot_d_jpnbkala_nom_berkala  += $row["NOM_BERKALA"];
                  }	//end while
                  $ln_dtl=$i;
                }						
                ?>									             																
              </tbody>
        			<tr><td colspan="9"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td></tr>
              <tr>
                <td style="text-align:right" colspan="7"><i>Total Diterima :<i>
                  <input type="hidden" id="d_mnf_kounter_dtl" name="d_mnf_kounter_dtl" value="<?=$ln_dtl;?>">
                  <input type="hidden" id="d_mnf_count_dtl" name="d_mnf_count_dtl" value="<?=$ln_countdtl;?>">
                  <input type="hidden" name="d_mnf_showmessage" style="border-width: 0;text-align:right" readonly size="5">					
                </td>
                <td style="text-align:right; font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$ln_tot_d_jpnbkala_nom_berkala,2,".",",");?></td>
        				<td>				
        				</td>				
              </tr>																		
            </table>
          </fieldset>
					<?
				}
				?>	
				
        <? 					
        if(!empty($ls_kode_klaim))
        {
        ?>			 	
          <div id="buttonbox" style="width:1105px;text-align:center;">       			 
          <?if ($ls_task == "edit" || $ls_task == "new")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="if(confirm('Apakah anda yakin akan menyimpan data..?')) fl_js_val_simpan();">
						<?
						if ($ln_no_konfirmasi!="")
						{
  						?>
  						<input type="button" class="btn green" id="btnhapus" name="btnhapus" value="            HAPUS             " onClick="if(confirm('Apakah anda yakin akan menghapus data..?')) fl_js_val_hapus();">
  						<?
						}
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="               TUTUP               " />       					
          </div>							 			 
        <? 					
        }
        ?>	
				
        <?
        if (isset($msg))		
        {
        ?>
          <fieldset>
          <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
          <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
          </fieldset>		
        <?
        }
        ?>													 		 
			</div>
			
		</div>		
	</form>
</body>
</html>
		