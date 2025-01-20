<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5001 - ENTRY DATA AHLI WARIS";

$ls_kode_tk	 		 					= !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];
$ls_kode_klaim						= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_urut_keluarga			= !isset($_GET['no_urut_keluarga']) ? $_POST['no_urut_keluarga'] : $_GET['no_urut_keluarga'];
$ls_kode_penerima_berkala	= !isset($_GET['kode_penerima_berkala']) ? $_POST['kode_penerima_berkala'] : $_GET['kode_penerima_berkala'];

$ls_root_sender 				 	= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$btn_task 					 			= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

if ($ls_kode_tk!="")
{
  $sql = "select no_urut_keluarga, nama_lengkap,jenis_kelamin,kode_penerima_berkala from sijstk.pn_klaim_penerima_berkala ".
			 	 "where kode_klaim = '$ls_kode_klaim' and kode_penerima_berkala = 'TK' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_nama_tk = $row['NAMA_LENGKAP'];
	$ls_jenis_kelamin_tk = $row['JENIS_KELAMIN'];
	$ln_no_urut_tk = $row['NO_URUT_KELUARGA'];
}

if ($ls_kode_penerima_berkala=="TK")
{
 	$ln_no_urut_keluarga = $ln_no_urut_tk;   	 
}

$ls_kode_hubungan					= $_POST['kode_hubungan'];
$ls_nama_lengkap					= $_POST['nama_lengkap'];
$ls_no_kartu_keluarga			= $_POST['no_kartu_keluarga'];
$ls_tempat_lahir					= $_POST['tempat_lahir'];
$ld_tgl_lahir							= $_POST['tgl_lahir'];
$ls_nomor_identitas				= $_POST['nomor_identitas'];
$ls_jenis_kelamin					= $_POST['jenis_kelamin'];
$ls_kpj_tertanggung				= $_POST['kpj_tertanggung'];
$ls_golongan_darah				= $_POST['golongan_darah'];
$ls_alamat								= $_POST['alamat'];
$ls_rt										= $_POST['rt'];
$ls_rw										= $_POST['rw'];
$ls_kode_pos							= $_POST['kode_pos'];
$ls_kode_kelurahan				= $_POST['kode_kelurahan'];
$ls_nama_kelurahan				= $_POST['nama_kelurahan'];
$ls_kode_kecamatan				= $_POST['kode_kecamatan'];
$ls_nama_kecamatan				= $_POST['nama_kecamatan'];
$ls_kode_kabupaten				= $_POST['kode_kabupaten'];							
$ls_nama_kabupaten				= $_POST['nama_kabupaten'];	
$ls_kode_propinsi					= $_POST['kode_propinsi'];	
$ls_nama_propinsi					= $_POST['nama_propinsi'];	
$ls_telepon_area					= $_POST['telepon_area'];	
$ls_telepon								= $_POST['telepon'];
$ls_telepon_ext						= $_POST['telepon_ext'];
$ls_handphone							= $_POST['handphone'];
$ls_email									= $_POST['email'];
$ls_npwp									= $_POST['npwp'];
$ls_keterangan						= $_POST['keterangan'];
$ls_kode_kondisi_terakhir	= $_POST['kode_kondisi_terakhir'];
$ld_tgl_kondisi_terakhir	= $_POST['tgl_kondisi_terakhir'];
			
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
		function fl_js_set_jenis_kelamin()
  	{
		 	var form = window.document.fpop;
			if(form.kode_hubungan.value=="S")
			{
			 	form.jenis_kelamin.value="L";															 
			}else if (form.kode_hubungan.value=="I")
			{
			 	form.jenis_kelamin.value="P";															 
			}
		}
		
  	function fl_js_val_simpan()
  	{
      var form = window.document.fpop;
      if(form.kode_hubungan.value==""){
        alert('Hubungan Keluarga tidak boleh kosong...!!!');
        form.kode_hubungan.focus();
			}else if (form.nama_lengkap.value==""){
        alert('Nama Lengkap tidak boleh kosong...!!!');
        form.nama_lengkap.focus();	
			}else if (form.tempat_lahir.value==""){
        alert('Tempat Lahir tidak boleh kosong...!!!');
        form.tempat_lahir.focus();
			}else if (form.tgl_lahir.value==""){
        alert('Tgl Lahir tidak boleh kosong...!!!');
        form.tgl_lahir.focus();
			}else if (form.jenis_kelamin.value==""){
        alert('Jenis Kelamin tidak boleh kosong...!!!');
        form.jenis_kelamin.focus();
			}else if ((form.jenis_kelamin.value=="L" && form.kode_penerima_berkala.value=="I") || (form.jenis_kelamin.value=="P" && form.kode_penerima_berkala.value=="S"))
			{
        alert('Jenis Kelamin tidak sesuai dengan jenis hubungan keluarga...!!!');
        form.jenis_kelamin.focus();	
			}else if ((form.kode_kondisi_terakhir.value!="" && form.tgl_kondisi_terakhir.value=="") || (form.kode_kondisi_terakhir.value=="" && form.tgl_kondisi_terakhir.value!=""))
			{
        alert('Jenis dan Tgl Kondisi Terakhir harus diisi keduanya...!!!');
        form.tgl_kondisi_terakhir.focus();							
			}else if (form.alamat.value==""){
        alert('Alamat tidak boleh kosong...!!!');
        form.alamat.focus();
			}else if (form.kode_pos.value==""){
        alert('Kode Pos tidak boleh kosong...!!!');
        form.kode_pos.focus();																																						
      }else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}
		
  	function fl_js_val_hapus()
  	{
      var form = window.document.fpop;
      if(form.kode_hubungan.value==""){
        alert('Tidak ada data keluarga yang akan dihapus...!!!');
        form.kode_hubungan.focus();
			}else
  		{
         form.btn_task.value="hapus";
         form.submit(); 		 
  		}								 
  	}
		
    function refreshParent() 
    {
    	if(window.opener.document.getElementById('formreg') != undefined){																							
    	<?php	
    	if($ls_sender!=''){			
    		echo "window.opener.location.replace('../form/$ls_sender?task=Edit&mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&activetab=$ls_sender_activetab');";		
    	}
    	?>	
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
			if ($ls_task=="new")	
			{
        //insert data ahli waris -----------------------------------------------
				$sql = 	"select nvl(max(no_urut_keluarga),0)+1 as v_no from sijstk.pn_klaim_penerima_berkala where kode_klaim = '$ls_kode_klaim' ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_no_urut_keluarga = $row["V_NO"];
				
				$sql = "insert into sijstk.pn_klaim_penerima_berkala ( ".
               "    kode_klaim, no_urut_keluarga, kode_hubungan, nama_lengkap, no_kartu_keluarga, nomor_identitas, ". 
               "    tempat_lahir, tgl_lahir, jenis_kelamin, golongan_darah, alamat, rt, rw, kode_kelurahan, kode_kecamatan, ". 
               "    kode_kabupaten, kode_pos, telepon_area, telepon, telepon_ext, handphone, email, ". 
               "    npwp, kpj_tertanggung, keterangan, kode_kondisi_terakhir, tgl_kondisi_terakhir, kode_penerima_berkala, ".
							 "		tgl_rekam, petugas_rekam) ". 
               "values ( ".
               "    '$ls_kode_klaim', '$ln_no_urut_keluarga', '$ls_kode_hubungan','$ls_nama_lengkap', '$ls_no_kartu_keluarga', '$ls_nomor_identitas', ". 
               "    '$ls_tempat_lahir', to_date('$ld_tgl_lahir','dd/mm/yyyy'), '$ls_jenis_kelamin', '$ls_golongan_darah', '$ls_alamat', '$ls_rt', '$ls_rw', '$ls_kode_kelurahan', '$ls_kode_kecamatan', ". 
               "    '$ls_kode_kabupaten', '$ls_kode_pos', '$ls_telepon_area', '$ls_telepon', '$ls_telepon_ext', '$ls_handphone', '$ls_email', ".
							 "		'$ls_npwp', '$ls_kpj_tertanggung', '$ls_keterangan', '$ls_kode_kondisi_terakhir', to_date('$ld_tgl_kondisi_terakhir','dd/mm/yyyy'), '$ls_kode_penerima_berkala', ".
							 "		sysdate, '$username' ". 
							 ") ";
        $DB->parse($sql);
        $DB->execute();						
			}else
			{    			
				$sql = "update sijstk.pn_klaim_penerima_berkala  ".
               "set kode_hubungan			= '$ls_kode_hubungan', ".
							 "		nama_lengkap			= '$ls_nama_lengkap', ". 
							 "		no_kartu_keluarga	= '$ls_no_kartu_keluarga', ".
							 "		nomor_identitas		= '$ls_nomor_identitas', ".  
							 "		tempat_lahir			= '$ls_tempat_lahir', ". 
               "    tgl_lahir					= to_date('$ld_tgl_lahir','dd/mm/yyyy'), ". 
							 "		jenis_kelamin			= '$ls_jenis_kelamin', ". 
							 "		golongan_darah		= '$ls_golongan_darah', ". 
               "    alamat						= '$ls_alamat', ". 
							 "		rt								= '$ls_rt', ". 
							 "		rw								= '$ls_rw', ". 
							 "		kode_kelurahan		= '$ls_kode_kelurahan', ". 
							 "		kode_kecamatan		= '$ls_kode_kecamatan', ". 
							 "		kode_kabupaten		= '$ls_kode_kabupaten', ". 
							 "		kode_pos					= '$ls_kode_pos', ". 
               "    telepon_area			= '$ls_telepon_area', ". 
							 "		telepon						= '$ls_telepon', ". 
							 "		telepon_ext				= '$ls_telepon_ext', ". 
							 "		handphone					= '$ls_handphone', ". 
							 "		email							= '$ls_email', ". 
							 "		npwp							= '$ls_npwp', ". 
							 "		keterangan				= '$ls_keterangan', ". 
               "    kpj_tertanggung		= '$ls_kpj_tertanggung', ". 
							 "    kode_kondisi_terakhir		= '$ls_kode_kondisi_terakhir', ".
							 "    tgl_kondisi_terakhir		= to_date('$ld_tgl_kondisi_terakhir','dd/mm/yyyy'), ".
               "    tgl_ubah					= sysdate, ". 
							 "		petugas_ubah			= '$username' ". 
               "where kode_klaim = '$ls_kode_klaim' ".
							 "and no_urut_keluarga = '$ln_no_urut_keluarga' ";
        $DB->parse($sql);
        $DB->execute();											
			}
			//echo $sql;
			
      //post update ---------------------------------------------------------- 
			$qry = "BEGIN SIJSTK.P_PN_PN5001.X_SET_AHLIWARIS_JP('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
      $DB->execute();				
      $ls_sukses = $p_sukses;	
  		$ls_mess = $p_mess;	
					     
      $msg = "Data Keluarga berhasil tersimpan, session dilanjutkan...";
      $task = "edit";	
      $ls_hiddenid = $ln_no_urut_keluarga;
      $editid = $ln_no_urut_keluarga;		

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "refreshParent();";
  		echo "</script>";

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "window.location.replace('?task=edit&kode_tk=$ls_kode_tk&kode_klaim=$ls_kode_klaim&no_urut_keluarga=$ln_no_urut_keluarga&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&mid=$ls_sender_mid&msg=$msg');";
  		echo "</script>";					            
	} //end if(isset($_POST['simpan']))

	if($btn_task=="hapus")
	{     
      //hapus data penerima berkala --------------------------------------------
			$sql = 	"delete from sijstk.pn_klaim_penerima_berkala ".
					 		"where kode_klaim = '$ls_kode_klaim' ".
							"and no_urut_keluarga = '$ln_no_urut_keluarga' ";
      $DB->parse($sql);
      $DB->execute();
						    
      $msg = "Data Keluarga berhasil dihapus, session dilanjutkan...";
      $task = "edit";		

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "refreshParent();";
  		echo "</script>";

  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
  		echo "window.location.replace('?task=edit&kode_tk=$ls_kode_tk&kode_klaim=$ls_kode_klaim&no_urut_keluarga=$ln_no_urut_keluarga&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&mid=$ls_sender_mid&msg=$msg');";
  		echo "</script>";					            
	} //end if(isset($_POST['simpan']))
	
	if(isset($_POST["butentrydatakeluargajp"]))
  {
    echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    echo "window.location.replace('?task=new&kode_tk=$ls_kode_tk&kode_klaim=$ls_kode_klaim&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&mid=$ls_sender_mid');";
    echo "</script>";		
  }		
	?>	
	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
    <?
    if ($ls_kode_klaim !="" && $ln_no_urut_keluarga !="")
    {
			$sql = "select 
                  a.kode_klaim, a.no_urut_keluarga, a.kode_hubungan, a.nama_lengkap, a.no_kartu_keluarga, a.nomor_identitas, 
									a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin, a.golongan_darah, 
									a.alamat, a.rt, a.rw, 
									a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan) nama_kelurahan, 
									a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan, 
									a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten, 
									a.kode_pos, a.telepon_area, a.telepon, a.telepon_ext, a.fax_area, a.fax, a.handphone, 
                  a.email, a.npwp, a.kpj_tertanggung, a.pekerjaan, a.kode_kondisi_terakhir, 
                  to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir, a.kode_penerima_berkala, a.keterangan
              from sijstk.pn_klaim_penerima_berkala a
              where a.kode_klaim = '$ls_kode_klaim'
              and a.no_urut_keluarga = '$ln_no_urut_keluarga' ";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
			$ln_no_urut_keluarga	 		= $data["NO_URUT_KELUARGA"];
			$ls_kode_hubungan					= $data["KODE_HUBUNGAN"];
			$ls_nama_lengkap					= $data["NAMA_LENGKAP"];
			$ls_no_kartu_keluarga			= $data["NO_KARTU_KELUARGA"];
			$ls_nomor_identitas				= $data["NOMOR_IDENTITAS"];
			$ls_tempat_lahir					= $data["TEMPAT_LAHIR"];
			$ld_tgl_lahir							= $data["TGL_LAHIR"];
			$ls_jenis_kelamin					= $data["JENIS_KELAMIN"];
			$ls_golongan_darah				= $data["GOLONGAN_DARAH"];
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
			$ls_keterangan 						= $data["KETERANGAN"];
      $ls_kpj_tertanggung				= $data["KPJ_TERTANGGUNG"];		
			$ls_kode_kondisi_terakhir	= $data["KODE_KONDISI_TERAKHIR"];
			$ld_tgl_kondisi_terakhir	= $data["TGL_KONDISI_TERAKHIR"];
			$ls_kode_penerima_berkala	= $data["KODE_PENERIMA_BERKALA"];														
    }	
		?>
		
  	<!-- VALIDASI AJAX -------------------------------------------------------->    
  	<!-- end VALIDASI AJAX ---------------------------------------------------->
											
		<div id="formframe" style="width:850px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_tk" name="kode_tk" value="<?=$ls_kode_tk;?>">
			<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="btn_task" name="btn_task" value=""> 
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
    	<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
			<input type="hidden" id="sender_kode_penerima_berkala" name="sender_kode_penerima_berkala" value="<?=$ls_sender_kode_penerima_berkala;?>">
			<input type="hidden" id="kode_penerima_berkala" name="kode_penerima_berkala" value="<?=$ls_kode_penerima_berkala;?>">
			
			<div id="formKiri" style="width:850px;">
				<fieldset style="width:820px;"><legend >Detil Informasi Ahli Waris</legend>			
          <div class="form-row_kiri">
          <label style = "text-align:right;">Hubungan *</label>		 	    				
            <select size="1" id="kode_hubungan" name="kode_hubungan" value="<?=$ls_kode_hubungan;?>" tabindex="1" class="select_format" style="width:253px;background-color:#ffff99;" onChange="fl_js_set_jenis_kelamin();">
            <option value="">-- Pilih --</option>
            <? 	
        			if ($ls_kode_penerima_berkala=="TK")
        			{
        			 	$ls_filter_hubungan = "and kode_hubungan = 'T' "; 
        			}elseif ($ls_kode_penerima_berkala=="JD")
        			{
        			 	$ls_filter_hubungan = "and kode_hubungan in ('I','S') "; 
        			}else
        			{
        			 	$ls_filter_hubungan = "and kode_hubungan like nvl('$ls_kode_hubungan','%') and kode_hubungan <> 'T' ";	 
        			}
										 
              $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk ".
									 	 "where nvl(aktif,'T') = 'Y' ".
										 "and kode_hubungan <> decode('$ls_jenis_kelamin_tk','L','S','P','I','XXX') ".
										 "and kode_hubungan in ('I','S','A','O','T') ".
										 $ls_filter_hubungan.
										 "order by urutan";		 
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
          <div class="form-row_kanan">
          <label  style = "text-align:right;">No Urut &nbsp;</label>
            <input type="text" id="no_urut_keluarga" name="no_urut_keluarga" value="<?=$ln_no_urut_keluarga;?>" size="25" readonly class="disabled" >                					
          </div>																																															
        	<div class="clear"></div>			
											
          <div class="form-row_kiri">
          <label style = "text-align:right;">Nama Lengkap *</label>
      			<input type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$ls_nama_lengkap;?>" tabindex="2" size="42" maxlength="100" style="background-color:#ffff99;">
					</div>
          <div class="form-row_kanan">
          <label  style = "text-align:right;">No KK &nbsp;</label>
            <input type="text" id="no_kartu_keluarga" name="no_kartu_keluarga" value="<?=$ls_no_kartu_keluarga;?>" size="25" tabindex="21" maxlength="30" >                					
          </div>																																																			
        	<div class="clear"></div>										

          <div class="form-row_kiri">
          <label style = "text-align:right;">Tempat, Tgl Lahir *</label>
      			<input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" size="23" maxlength="50" tabindex="4" style="background-color:#ffff99;">
            <input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" size="12" maxlength="10" tabindex="5" onblur="convert_date(tgl_lahir);" style="background-color:#ffff99;">
            <input id="btn_tgl_lahir" type="image" align="top" onclick="return showCalendar('tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />							
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">No. Identitas </label>
      			<input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" size="25" maxlength="30" tabindex="26">
  				</div>																																																																																							
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Jenis Kelamin *</label>
            <select size="1" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>" tabindex="5" class="select_format" style="width:250px;background-color:#ffff99;" >
            <option value="">-- Pilih --</option>
            <? 
        			if ($ls_kode_hubungan=="I")
        			{
        			 	$ls_filter_kelamin = "and kode = 'P' "; 
        			}elseif ($ls_kode_hubungan=="S")
        			{
        			 	$ls_filter_kelamin = "and kode = 'L' "; 
        			}else
        			{
        			 	$ls_filter_kelamin = "";
        			}					
              $sql = "select kode, keterangan from sijstk.ms_lookup ".
									 	 "where tipe = 'JNSKELAMIN' ".
										 $ls_filter_kelamin.
										 "and nvl(aktif,'T')='Y' ".
										 "order by seq";
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
                echo "<option ";
                if ($row["KODE"]==$ls_jenis_kelamin && strlen($ls_jenis_kelamin)==strlen($row["KODE"])){ echo " selected"; }
                echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
              }
            ?>
            </select>
						<input type="hidden" id="golongan_darah" name="golongan_darah" value="<?=$ls_golongan_darah;?>" size="25" maxlength="11">
						<input type="hidden" id="kpj_tertanggung" name="kpj_tertanggung" value="<?=$ls_kpj_tertanggung;?>" size="25" maxlength="11" tabindex="27">													
          </div>																																																																																						
        	<div class="clear"></div>
													
					</br>
															
          <div class="form-row_kiri">
          <label style = "text-align:right;">Alamat *</label>
      			<input type="text" id="alamat" name="alamat" value="<?=$ls_alamat;?>" tabindex="9" size="42" maxlength="300" style="background-color:#ffff99;">	
          </div>																																									
        	<div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">RT/RW &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="text" id="rt" name="rt" value="<?=$ls_rt;?>" tabindex="10" size="4" maxlength="5" onblur="fl_js_val_numeric('rt');">
            /
            <input type="text" id="rw" name="rw" value="<?=$ls_rw;?>" tabindex="11" size="6" maxlength="5" onblur="fl_js_val_numeric('rw');">
						&nbsp; Kode Pos &nbsp;
						<input type="text" id="kode_pos" name="kode_pos" value="<?=$ls_kode_pos;?>" tabindex="12" size="10" maxlength="10" readonly  style="background-color:#ffff99;">
            <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_lov_pos.php?p=pn5002_penetapanmanfaat_penerima.php&a=fpop&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','',800,500,1)">							
            <img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>								
          </div>																																																	
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Kelurahan &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
            <input type="hidden" id="kode_kelurahan" name="kode_kelurahan" value="<?=$ls_kode_kelurahan;?>" size="8" maxlength="20" readonly class="disabled">
            <input type="text" id="nama_kelurahan" name="nama_kelurahan" value="<?=$ls_nama_kelurahan;?>" size="42" readonly class="disabled">
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
          <div class="clear"></div>				   						
      		
					</br>
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">No. Telp</label>	    				
            <input type="text" id="telepon_area" name="telepon_area" tabindex="13" value="<?=$ls_telepon_area;?>" size="4" maxlength="5" onblur="fl_js_val_numeric('telepon_area');">
            <input type="text" id="telepon" name="telepon" tabindex="14" value="<?=$ls_telepon;?>" size="18" maxlength="20" onblur="fl_js_val_numeric('telepon');">
            &nbsp;ext.
            <input type="text" id="telepon_ext" name="telepon_ext" tabindex="15" value="<?=$ls_telepon_ext;?>" size="4" maxlength="5" onblur="fl_js_val_numeric('telepon_ext');"> 						
          </div>																																															
      		<div class="clear"></div>
      		
          <div class="form-row_kiri">
          <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
      			<input type="text" id="handphone" name="handphone" tabindex="16" value="<?=$ls_handphone;?>" size="39" maxlength="15">
          </div>																																																																																														
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
      			<input type="text" id="email" name="email" tabindex="17" value="<?=$ls_email;?>" size="39" maxlength="200">
          </div>																																																																																														
          <div class="clear"></div>
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
      			<input type="text" id="npwp" name="npwp" tabindex="18" value="<?=$ls_npwp;?>" size="37" maxlength="30">
          </div>																																																																																																								
          <div class="clear"></div>					
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Keterangan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:250px" id="keterangan" name="keterangan" tabindex="19" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>														
          <div class="clear"></div>								
				</fieldset>	
				
				</br>
				
				<fieldset style="width:820px;"><legend >Kondisi Terakhir Ahli Waris Pada Saat Klaim Dilaporkan:</legend>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kondisi Terakhir</label>		 	    				
            <select size="1" id="kode_kondisi_terakhir" name="kode_kondisi_terakhir" value="<?=$ls_kode_kondisi_terakhir;?>" tabindex="31" class="select_format" style="width:200px;" >
            <option value="">-- Pilih --</option>
            <?
        			if ($ls_kode_hubungan=="O")
        			{
        			 	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir = 'KA11' "; 
        			}else if ($ls_kode_hubungan=="I" || $ls_kode_hubungan=="S")
        			{
        			 	$ls_filter_kondisi_akhir = "and kode_kondisi_terakhir in ('KA11','KA12') "; 
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
                if ($row["KODE_KONDISI_TERAKHIR"]==$ls_kode_kondisi_terakhir && strlen($ls_kode_kondisi_terakhir)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
                echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
              }
            ?>
            </select>
          </div>																																													
        	<div class="clear"></div>
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Sejak</label>
      			<input type="text" id="tgl_kondisi_terakhir" name="tgl_kondisi_terakhir" value="<?=$ld_tgl_kondisi_terakhir;?>" size="25" maxlength="10" tabindex="32" onblur="convert_date(tgl_kondisi_terakhir);">
            <input id="btn_tgl_kondisi_terakhirr" type="image" align="top" onclick="return showCalendar('tgl_kondisi_terakhir', 'dd-mm-y');" src="../../images/calendar.gif" />							
          </div>																																																																																						
        	<div class="clear"></div>									
				</fieldset>
								
        <? 					
        if(!empty($ls_kode_klaim))
        {
        ?>			 	
          <div id="buttonbox" style="width:820px;text-align:center;">       			 
          <?if ($ls_task == "edit" || $ls_task == "new")
					{
  					?>
  					<input type="submit" class="btn green" id="butentrydatakeluargajp" name="butentrydatakeluargajp" value="        ENTRY DATA       " />
						<input type="button" class="btn green" id="simpan" name="simpan" value="            SIMPAN           " onClick="if(confirm('Apakah anda yakin akan menyimpan data..?')) fl_js_val_simpan();">
						<?
						if ($ls_kode_penerima_berkala != "T")
						{
  						?>
  						<input type="button" class="btn green" id="btnhapus" name="btnhapus" value="            HAPUS             " onClick="if(confirm('Apakah anda yakin akan menghapus data keluarga..?')) fl_js_val_hapus();">
              <?
						}
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="           TUTUP           " />       					
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