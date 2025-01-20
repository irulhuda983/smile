<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5001 - DETIL INFORMASI PENERIMA MANFAAT";

$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_root_sender 				 	= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ln_no_level				 			= !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];
$btn_task 					 			= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

$ls_kode_klaim	 		 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_tipe_penerima	 	= !isset($_GET['kode_tipe_penerima']) ? $_POST['kode_tipe_penerima'] : $_GET['kode_tipe_penerima'];
$ls_kode_kantor						= $_POST['kode_kantor'];

if ($ls_kode_klaim!="")
{
  $sql = "select status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];		
}

$ls_kode_hubungan					= $_POST['kode_hubungan'];
$ls_ket_hubungan_lainnya	= $_POST['ket_hubungan_lainnya'];
$ls_nama_penerima					= $_POST['nama_penerima'];
$ls_alamat								= $_POST['alamat'];
$ls_rt										= $_POST['rt'];
$ls_rw										= $_POST['rw'];
$ls_kode_pos							= $_POST['kode_pos'];
$ls_kode_kelurahan				= $_POST['kode_kelurahan'];
$ls_kode_kecamatan				= $_POST['kode_kecamatan'];
$ls_kode_kabupaten				= $_POST['kode_kabupaten'];
$ls_kode_propinsi					= $_POST['kode_propinsi'];
$ls_email									= $_POST['email'];
$ls_telepon_area					= $_POST['telepon_area'];
$ls_telepon								= $_POST['telepon'];
$ls_telepon_ext						= $_POST['telepon_ext'];
$ls_handphone							= $_POST['handphone'];
$ls_npwp									= $_POST['npwp'];
$ls_nomor_identitas				= $_POST['nomor_identitas'];
$ls_tempat_lahir					= $_POST['tempat_lahir'];
$ld_tgl_lahir							= $_POST['tgl_lahir'];
$ls_jenis_kelamin					= $_POST['jenis_kelamin'];
$ls_bank_penerima					= $_POST['bank_penerima'];
$ls_no_rekening_penerima	= $_POST['no_rekening_penerima'];
$ls_nama_rekening_penerima	= $_POST['nama_rekening_penerima'];
$ls_kode_bank_pembayar		= $_POST['kode_bank_pembayar'];	
$ls_keterangan						= $_POST['keterangan'];
		
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
	
  <script type="text/javascript">
    function refreshParent() 
    {
    	if(window.opener.document.getElementById('adminForm') != undefined){																							
    	<?php	
    	if($ls_sender!=''){			
    		echo "window.opener.location.replace('../ajax/$ls_sender?task=Edit&mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&activetab=$ls_sender_activetab');";		
    	}
    	?>	
    	}
    }	
  </script>	
							
  <script language="JavaScript">    
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
  	function fl_js_val_simpan()
  	{
      var form = window.document.fpop;
      if(form.kode_tipe_penerima.value==""){
        alert('Tipe Penerima tidak boleh kosong...!!!');
        form.kode_tipe_penerima.focus();
			}else if (form.nama_penerima.value==""){
        alert('Nama Penerima tidak boleh kosong...!!!');
        form.nama_penerima.focus();	
			}else if (form.alamat.value==""){
        alert('Alamat tidak boleh kosong...!!!');
        form.alamat.focus();
			}else if (form.kode_pos.value==""){
        alert('Kode Pos tidak boleh kosong...!!!');
        form.kode_pos.focus();									
			}else if (form.npwp.value==""){
        alert('NPWP tidak boleh kosong, isikan 0 jika memang tidak memiliki NPWP...!!!');
        form.npwp.focus();
			}else if (form.bank_penerima.value==""){
        alert('Bank Penerima tidak boleh kosong...!!!');
        form.bank_penerima.focus();
			}else if (form.no_rekening_penerima.value==""){
        alert('No Rekening Penerima tidak boleh kosong...!!!');
        form.no_rekening_penerima.focus();	
			}else if (form.nama_rekening_penerima.value==""){
        alert('Nama Rekening Penerima tidak boleh kosong...!!!');
        form.nama_rekening_penerima.focus();	
			}else if (form.kode_bank_pembayar.value==""){
        alert('Rekening Bank Pembayar tidak boleh kosong...!!!');
        form.kode_bank_pembayar.focus();																	
      }else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}		
    function fl_js_kode_hubungan() 
    { 
      var	v_kode_tipe_penerima = window.document.getElementById("kode_tipe_penerima").value;
			var	v_kode_hubungan = window.document.getElementById("kode_hubungan").value;
							
    	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
      {
        window.document.getElementById("span_kode_hubungan").style.display = 'block';	
				if (v_kode_hubungan=="L")
				{
					window.document.getElementById("span_kode_hubungan_lainnya").style.display = 'block';	
				}else
				{
					window.document.getElementById("span_kode_hubungan_lainnya").style.display = 'none';
					window.document.getElementById("ket_hubungan_lainnya").value = "";		 
				}	
      }else
      {
        window.document.getElementById("span_kode_hubungan").style.display = 'none';
				window.document.getElementById("span_kode_hubungan_lainnya").style.display = 'none';
        window.document.getElementById("kode_hubungan").value = "";	
				window.document.getElementById("ket_hubungan_lainnya").value = "";
      }
    }
				
    function fl_js_nomor_identitas() 
    { 
      var	v_kode_tipe_penerima = window.document.getElementById("kode_tipe_penerima").value;
					
    	if (v_kode_tipe_penerima =="PR" || v_kode_tipe_penerima =="FK" || v_kode_tipe_penerima =="TG") //perusahaan/faskes
      {
        window.document.getElementById("span_nomor_identitas").style.display = 'none';
        window.document.getElementById("nomor_identitas").value = "";						
      }else
      {
       	window.document.getElementById("span_nomor_identitas").style.display = 'block';	   
      }
    }				
  </script>															
</head>
<body>
	<div id="header-popup">	
		<h3><?=$gs_pagetitle;?></h3>
	</div>
	
	<div id="container-popup">
	<!--[if lte IE 6]>
	<div id="clearie6"></div>
	<![endif]-->	
	
	<?
	if($btn_task=="simpan")
	{         				
  		$sql = "update sijstk.pn_klaim_penerima_manfaat ".
             "set    kode_hubungan          = '$ls_kode_hubungan', ".
             "       ket_hubungan_lainnya   = '$ls_ket_hubungan_lainnya', ".
             "       nomor_identitas        = '$ls_nomor_identitas', ".
             "       nama_pemohon           = '$ls_nama_penerima', ".
             "       tempat_lahir           = '$ls_tempat_lahir', ".
             "       tgl_lahir              = to_date('$ld_tgl_lahir','dd/mm/yyyy'), ".
             "       jenis_kelamin          = '$ls_jenis_kelamin', ".
             "       alamat                 = '$ls_alamat', ".
             "       rt                     = '$ls_rt', ".
             "       rw                     = '$ls_rw', ".
             "       kode_kelurahan         = '$ls_kode_kelurahan', ".
             "       kode_kecamatan         = '$ls_kode_kecamatan', ".
             "       kode_kabupaten         = '$ls_kode_kabupaten', ".
             "       kode_pos               = '$ls_kode_pos', ".
             "       telepon_area           = '$ls_telepon_area', ".
             "       telepon                = '$ls_telepon', ".
             "       telepon_ext            = '$ls_telepon_ext', ".
             "       handphone              = '$ls_handphone', ".
             "       email                  = '$ls_email', ".
             "       npwp                   = '$ls_npwp', ".
             "       nama_penerima          = '$ls_nama_penerima', ".
             "       bank_penerima          = '$ls_bank_penerima', ".
             "       no_rekening_penerima   = '$ls_no_rekening_penerima', ".
             "       nama_rekening_penerima = '$ls_nama_rekening_penerima', ". 
						 "       kode_bank_pembayar 		= '$ls_kode_bank_pembayar', ".             
             "       keterangan             = '$ls_keterangan', ".
             "       tgl_ubah               = sysdate, ".
             "       petugas_ubah           = '$username' ".
             "where  kode_klaim             = '$ls_kode_klaim' ".
             "and    kode_tipe_penerima     =  '$ls_kode_tipe_penerima' ";
      $DB->parse($sql);
      $DB->execute();											
			$ls_ket_submit = "UPDATE DATA PENERIMA MANFAAT PADA SAAT PROSES ".$ls_status_klaim." KLAIM";
			
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
							
      //post update ----------------------------------------------------------      
      $msg = "Data Penerima Manfaat berhasil tersimpan, session dilanjutkan...";
      $task = "edit";	
      $ls_hiddenid = $ls_kode_tipe_penerima;
      $editid = $ls_kode_tipe_penerima;		
							
  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
			echo "window.location.replace('?&task=Edit&kode_klaim=$ls_kode_klaim&kode_tipe_penerima=$ls_kode_tipe_penerima&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=3&sender_mid=$ls_sender_mid&msg=$msg');";
			echo "refreshParent();";
  		echo "</script>";								            
	} //end if(isset($_POST['simpan'])) 
	?>	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
    <?
    if ($ls_kode_tipe_penerima !="")
    {
      $sql = "select 
                  a.kode_klaim, c.kode_kantor, a.kode_tipe_penerima, b.nama_tipe_penerima, a.kode_hubungan, a.ket_hubungan_lainnya, 
									a.nomor_identitas, a.nama_pemohon, a.tempat_lahir, a.tgl_lahir, a.jenis_kelamin, a.alamat, a.rt, a.rw, 
                  a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan) nama_kelurahan, 
									a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan, 
									a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten, 
                  a.kode_pos, a.telepon_area, a.telepon, a.telepon_ext, a.handphone, a.email, 
                  a.npwp, a.nama_penerima, a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, a.nom_manfaat_utama, 
                  a.nom_manfaat_tambahan, a.nom_manfaat_gross, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.keterangan, 
                  a.status_lunas, a.tgl_lunas, a.petugas_lunas,a.kode_bank_pembayar
              from sijstk.pn_klaim_penerima_manfaat a, sijstk.pn_kode_tipe_penerima b, sijstk.pn_klaim c
              where a.kode_tipe_penerima = b.kode_tipe_penerima and a.kode_klaim = c.kode_klaim
							and a.kode_klaim = '$ls_kode_klaim'
              and a.kode_tipe_penerima = '$ls_kode_tipe_penerima' ";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
			$ls_kode_klaim				 		= $data["KODE_KLAIM"];	
			$ls_kode_kantor				 		= $data["KODE_KANTOR"];		 
      $ls_kode_tipe_penerima		= $data["KODE_TIPE_PENERIMA"];
			$ls_nama_tipe_penerima		= $data["NAMA_TIPE_PENERIMA"];
      $ls_kode_hubungan					= $data["KODE_HUBUNGAN"];
      $ls_ket_hubungan_lainnya	= $data["KET_HUBUNGAN_LAINNYA"];
      $ls_nomor_identitas				= $data["NOMOR_IDENTITAS"];
      $ls_nama_pemohon 					= $data["NAMA_PEMOHON"];
      $ls_tempat_lahir					= $data["TEMPAT_LAHIR"];
      $ld_tgl_lahir							= $data["TGL_LAHIR"];
      $ls_jenis_kelamin					= $data["JENIS_KELAMIN"];
      $ls_alamat								= $data["ALAMAT"];
      $ls_rt										= $data["RT"];
      $ls_rw 										= $data["RW"];
      $ls_kode_kelurahan				= $data["KODE_KELURAHAN"];
      $ls_kode_kecamatan				= $data["KODE_KECAMATAN"];
      $ls_kode_kabupaten 				= $data["KODE_KABUPATEN"];
			$ls_nama_kelurahan				= $data["NAMA_KELURAHAN"];
      $ls_nama_kecamatan				= $data["NAMA_KECAMATAN"];
      $ls_nama_kabupaten 				= $data["NAMA_KABUPATEN"];
      $ls_kode_pos							= $data["KODE_POS"];
      $ls_telepon_area					= $data["TELEPON_AREA"];
      $ls_telepon								= $data["TELEPON"];
      $ls_telepon_ext						= $data["TELEPON_EXT"];
      $ls_handphone							= $data["HANDPHONE"];
      $ls_email 								= $data["EMAIL"];
      $ls_npwp									= $data["NPWP"];
      $ls_nama_penerima					= $data["NAMA_PENERIMA"];
      $ls_bank_penerima					= $data["BANK_PENERIMA"];
      $ls_no_rekening_penerima	= $data["NO_REKENING_PENERIMA"];
      $ls_nama_rekening_penerima	= $data["NAMA_REKENING_PENERIMA"];
			$ls_kode_bank_pembayar		= $data["KODE_BANK_PEMBAYAR"];			
      $ln_nom_manfaat_utama			= $data["NOM_MANFAAT_UTAMA"];	
      $ln_nom_manfaat_tambahan	= $data["NOM_MANFAAT_TAMBAHAN"];
      $ln_nom_manfaat_gross			= $data["NOM_MANFAAT_GROSS"];
      $ln_nom_pph								= $data["NOM_PPH"];
      $ln_nom_pembulatan				= $data["NOM_PEMBULATAN"];
      $ln_nom_manfaat_netto			= $data["NOM_MANFAAT_NETTO"];
      $ls_keterangan 						= $data["KETERANGAN"];
      $ls_status_lunas					= $data["STATUS_LUNAS"];
      $ld_tgl_lunas							= $data["TGL_LUNAS"];
      $ls_petugas_lunas					= $data["PETUGAS_LUNAS"];																		
    }	
		?>
		
  	<!-- VALIDASI AJAX -------------------------------------------------------->    
  	<!-- end VALIDASI AJAX ---------------------------------------------------->	
											
		<div id="formframe" style="width:900px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="btn_task" name="btn_task" value=""> 
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
    	<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
			<input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>">
			<input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>">
			<input type="hidden" id="kode_realisasi" name="kode_realisasi" value="<?=$ls_kode_realisasi;?>">
			<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
			<input type="hidden" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>">
		
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:820px;"><legend >Detil Informasi Penerima Manfaat</legend>
					</br>				
					<span id="span_kode_hubungan" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Ahli Waris</label>		 	    				
              <select size="1" id="kode_hubungan" name="kode_hubungan" value="<?=$ls_kode_hubungan;?>" tabindex="2" class="select_format" style="width:245px;" >
              <option value="">-- Pilih --</option>
              <? 
                $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where nvl(aktif,'T') = 'Y' order by urutan";
                $DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                  echo "<option ";
                  if ($row["KODE_HUBUNGAN"]==$ls_kode_hubungan && strlen($ls_kode_hubungan)==strlen($row["KODE_HUBUNGAN"])){ echo " selected"; }
                  echo " value=\"".$row["KODE_HUBUNGAN"]."\">".$row["NAMA_HUBUNGAN"]."</option>";
                }
              ?>
              </select>
        				
            </div>																																									
          	<div class="clear"></div>
					</span>
					
					<span id="span_kode_hubungan_lainnya" style="display:none;">	
            <div class="form-row_kiri">
            <label style = "text-align:right;">Keterangan Hub Lainnya</label>		 	    				
              <input type="text" id="ket_hubungan_lainnya" name="ket_hubungan_lainnya" value="<?=$ls_ket_hubungan_lainnya;?>" size="40" maxlength="300">	
            </div>																																									
          	<div class="clear"></div>						
					</span>				
						
          <div class="form-row_kiri">
          <label style = "text-align:right;">Nama Penerima *</label>
      			<input type="text" id="nama_penerima" name="nama_penerima" value="<?=$ls_nama_penerima;?>" tabindex="3" size="42" maxlength="100" style="background-color:#ffff99;">
						<input type="hidden" id="nama_pemohon" name="nama_pemohon" value="<?=$ls_nama_pemohon;?>" size="40" maxlength="100" style="background-color:#ffff99;">	
          </div>
          <div class="form-row_kanan">
          <label  style = "text-align:right;">Tipe Penerima &nbsp;</label>
            <input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" size="30" maxlength="10" readonly class="disabled" >
						<input type="text" id="nama_tipe_penerima" name="nama_tipe_penerima" value="<?=$ls_nama_tipe_penerima;?>" size="33" readonly class="disabled" >                					
          </div>																																														
        	<div class="clear"></div>										

					<span id="span_nomor_identitas" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">No. Identitas </label>
        			<input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" size="42" maxlength="30" tabindex="4">
							<input type="hidden" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" size="40" maxlength="50">
							<input type="hidden" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" size="40" maxlength="30">
							<input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>" size="40" maxlength="10">	
            </div>																																									
          	<div class="clear"></div>
					</span>
					<?
            echo "<script type=\"text/javascript\">fl_js_nomor_identitas();</script>";
            echo "<script type=\"text/javascript\">fl_js_kode_hubungan();</script>";
          ?>
					
					</br>
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alamat </label>
      			<input type="text" id="alamat" name="alamat" value="<?=$ls_alamat;?>" tabindex="4" size="42" maxlength="300" style="background-color:#ffff99;">	
          </div>																																									
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">RT/RW</label>		 	    				
            <input type="text" id="rt" name="rt" value="<?=$ls_rt;?>" tabindex="5" size="15" maxlength="5" onblur="fl_js_val_numeric('rt');">
            /
            <input type="text" id="rw" name="rw" value="<?=$ls_rw;?>" tabindex="6" size="22" maxlength="5" onblur="fl_js_val_numeric('rw');">		
          </div>																																																	
          <div class="clear"></div>
					
					<div class="form-row_kiri">
          <label style = "text-align:right;">Kode Pos</label> 	    				
            <input type="text" id="kode_pos" name="kode_pos" value="<?=$ls_kode_pos;?>" tabindex="7" size="35" maxlength="10" readonly style="background-color:#ffff99;>
            <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_lov_pos.php?p=pn5002_penetapanmanfaat_penerima.php&a=fpop&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','',800,500,1)">							
            <img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>				           											
          </div>																																																	
          <div class="clear"></div>	

          <div class="form-row_kiri">
          <label style = "text-align:right;">Kelurahan &nbsp;</label>		 	    				
            <input type="hidden" id="kode_kelurahan" name="kode_kelurahan" value="<?=$ls_kode_kelurahan;?>" size="8" maxlength="20" readonly class="disabled">
            <input type="text" id="nama_kelurahan" name="nama_kelurahan" value="<?=$ls_nama_kelurahan;?>" size="40" readonly class="disabled">
          </div>																																																	
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Kecamatan &nbsp;</label>		 	    				
            <input type="hidden" id="kode_kecamatan" name="kode_kecamatan" value="<?=$ls_kode_kecamatan;?>" size="8" maxlength="10">
            <input type="text" id="nama_kecamatan" name="nama_kecamatan" value="<?=$ls_nama_kecamatan;?>" size="40" readonly class="disabled">
          </div>																																																						
          <div class="clear"></div>
															
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="hidden" id="kode_kabupaten" name="kode_kabupaten" value="<?=$ls_kode_kabupaten;?>" size="25" maxlength="20">
      			<input type="text" id="nama_kabupaten" name="nama_kabupaten" value="<?=$ls_nama_kabupaten;?>" size="38" readonly class="disabled">
						<input type="hidden" id="kode_propinsi" name="kode_propinsi" value="<?=$ls_kode_propinsi;?>" size="8" readonly class="disabled">
            <input type="hidden" id="nama_propinsi" name="nama_propinsi" value="<?=$ls_nama_propinsi;?>" size="32" readonly class="disabled">											
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;"><i><font color="#009999">Ditransfer ke :</font></i></label>
					<input type="text" id="temp3" name="temp3" size="33" style="border-width: 0;text-align:left" readonly>	    				
          </div>																																																																
          <div class="clear"></div>				
					
					</br>
					      							
          <div class="form-row_kiri">
          <label style = "text-align:right;">Email &nbsp;</label>		 	    				
      			<input type="text" id="email" name="email" tabindex="8" value="<?=$ls_email;?>" size="35" maxlength="200">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Bank *</label>
      			<input type="text" id="bank_penerima" name="bank_penerima" value="<?=$ls_bank_penerima;?>" tabindex="21" size="33" maxlength="100" style="background-color:#ffff99;">	
          </div>																																																																																															
          <div class="clear"></div>
      
          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Telp</label>	    				
            <input type="text" id="telepon_area" name="telepon_area" tabindex="9" value="<?=$ls_telepon_area;?>" size="3" maxlength="5" onblur="fl_js_val_numeric('telepon_area');">
            <input type="text" id="telepon" name="telepon" tabindex="10" value="<?=$ls_telepon;?>" size="17" maxlength="20" onblur="fl_js_val_numeric('telepon');">
            &nbsp;ext.
            <input type="text" id="telepon_ext" name="telepon_ext" tabindex="11" value="<?=$ls_telepon_ext;?>" size="3" maxlength="5" onblur="fl_js_val_numeric('telepon_ext');"> 						
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">No Rekening *</label>
      			<input type="text" id="no_rekening_penerima" name="no_rekening_penerima" value="<?=$ls_no_rekening_penerima;?>" tabindex="22" size="33" maxlength="30" style="background-color:#ffff99;">	
          </div>																																															
      		<div class="clear"></div>
      		
          <div class="form-row_kiri">
          <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
      			<input type="text" id="handphone" name="handphone" tabindex="12" value="<?=$ls_handphone;?>" size="32" maxlength="15">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">A/N *</label>
      			<input type="text" id="nama_rekening_penerima" name="nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" tabindex="23" size="33" maxlength="100" style="background-color:#ffff99;">	
          </div>																																																																																															
          <div class="clear"></div>
      							
          <div class="form-row_kiri">
          <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
      			<input type="text" id="npwp" name="npwp" tabindex="13" value="<?=$ls_npwp;?>" size="30" maxlength="30" style="background-color:#ffff99;">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;"><i><font color="#009999">Melalui :</font></i></label>
						<input type="text" id="temp3" name="temp3" size="33" style="border-width: 0;text-align:left" readonly>	    				
          </div>																																																																																																										
          <div class="clear"></div>													

          <div class="form-row_kiri">
          <label style = "text-align:right;">Keterangan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:230px" id="keterangan" name="keterangan" tabindex="25" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Bank *</label>	 	    				
            <select size="1" id="kode_bank_pembayar" name="kode_bank_pembayar" value="<?=$ls_kode_bank_pembayar;?>" tabindex="24" class="select_format" style="width:202px;background-color:#ffff99;" >
            <option value="">-- Pilih --</option>
            <? 
              $sql = "select distinct a.kode_bank, c.nama_bank ".
                     "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
                     "where a.kode_kantor = b.kode_kantor(+) ".  
                     "and a.kode_bank = b.kode_bank(+) ".  
                     "and a.kode_rekening = b.kode_rekening(+) ".  
                     "and a.kode_buku = b.kode_buku(+) ".  
                     "and a.kode_bank = c.kode_bank ".   
                     "and a.kode_kantor = '$ls_kode_kantor' ". 
                     "and nvl(a.aktif,'T')='Y' ".
                     "and b.tipe_rekening in ('13','14','15','16') ". 
                     "order by a.kode_bank";
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["KODE_BANK"]==$ls_kode_bank_pembayar && strlen($ls_kode_bank_pembayar)==strlen($row["KODE_BANK"])){ echo " selected"; }
                echo " value=\"".$row["KODE_BANK"]."\">".$row["NAMA_BANK"]."</option>";
              }
            ?>
            </select>	
          </div>																			
          <div class="clear"></div>
															
				</fieldset>	
								
        <? 					
        if(!empty($ls_kode_tipe_penerima))
        {
        ?>			 	
          <div id="buttonbox" style="width:820px;text-align:center;">       			 
          <?if ($ls_task == "Edit")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="if(confirm('Apakah anda yakin akan menyimpan data..?')) fl_js_val_simpan();">
            <?
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="               TUTUP               " />       					
          </div>							 			 
        <? 					
        }
        ?>								 
			</div>	 
  	</div>
		
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
	</form>
</body>
</html>				