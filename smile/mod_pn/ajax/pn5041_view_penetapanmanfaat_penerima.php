<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";

$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5002 - DETIL INFORMASI PENERIMA MANFAAT";

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
  $sql = "select kode_kantor, status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];	
	$ls_kode_kantor				= $row['KODE_KANTOR'];	
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
$ls_jenis_identitas  			= $_POST['jenis_identitas'];
$ls_status_valid_identitas  = $_POST['status_valid_identitas'];
$ls_nomor_identitas				= $_POST['nomor_identitas'];
$ls_tempat_lahir					= $_POST['tempat_lahir'];
$ld_tgl_lahir							= $_POST['tgl_lahir'];
$ls_jenis_kelamin					= $_POST['jenis_kelamin'];
$ls_golongan_darah				= $_POST['golongan_darah'];
$ls_kode_bank_pembayar		= $_POST['kode_bank_pembayar'];	
$ls_keterangan						= $_POST['keterangan'];

$ls_list_bank_penerima		= $_POST['list_bank_penerima'];
$ls_nama_bank_penerima		= $_POST['nama_bank_penerima'];
$ls_kode_bank_penerima		= $_POST['kode_bank_penerima'];
$ls_id_bank_penerima			= $_POST['id_bank_penerima'];
$ls_no_rekening_penerima	= $_POST['no_rekening_penerima'];
$ls_nama_rekening_penerima 		= $_POST['nama_rekening_penerima'];
$ls_status_valid_rekening_penerima = $_POST['status_valid_rekening_penerima'];
$ls_kode_bank_pembayar						 = $_POST['kode_bank_pembayar'];
$ls_status_rekening_sentral		= $_POST['status_rekening_sentral'];
$ls_kantor_rekening_sentral		= $_POST['kantor_rekening_sentral'];

if ($ls_status_valid_rekening_penerima=="on" || $ls_status_valid_rekening_penerima=="ON" || $ls_status_valid_rekening_penerima=="Y")
{
	$ls_status_valid_rekening_penerima = "Y";
}else
{
	$ls_status_valid_rekening_penerima = "T";
}		
		
define('debug', false);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <!--<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />-->
	<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.new.css?ver=1.2" />
	<script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>

  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	
	<!-- tambahan baru -->
	<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
	<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
		
  <script type="text/javascript">
  function refreshParent() 
  {																						
    <?php	
    if($ls_sender!=''){			
    	echo "window.location.replace('$ls_sender?task=edit&kode_klaim=$ls_kode_klaim&task=$ls_task&no_level=$ln_no_level&dataid=$ls_kode_klaim&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&kode_realisasi=$ls_kode_realisasi&root_sender=$ls_root_sender&sender=$ls_sender&activetab=2&mid=$ls_sender_mid ');";		
    }
    ?>	
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
    function fl_js_val_email(v_field_id)
    {
      var x = window.document.getElementById(v_field_id).value;
			var atpos=x.indexOf("@");
      var dotpos=x.lastIndexOf(".");
      if ((x!='') && (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length))
      {
         document.getElementById(v_field_id).value = '';				 
				 window.document.getElementById(v_field_id).focus();
				 alert("Format Email tidak valid, belum ada (@ DAN .)");         
				 return false; 	
      }
    }
    function fl_js_val_npwp(v_field_id)
    {
      var v_npwp = window.document.getElementById(v_field_id).value;
			if ((v_npwp!='') && (v_npwp!='0') && (v_npwp.length!=15))
      {
         document.getElementById(v_field_id).value = '0';				 
				 window.document.getElementById(v_field_id).focus();
				 alert("NPWP tidak valid, harus 15 karakter...!!!");         
				 return false; 	
      }else
			{
			 	 fl_js_val_numeric(v_field_id);	 
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
			}else if (form.npwp.value==""){
        alert('NPWP tidak boleh kosong, isikan 0 jika memang tidak memiliki NPWP...!!!');
        form.npwp.focus();
			}else if (form.no_rekening_penerima.value==""){
        alert('No Rekening Penerima tidak boleh kosong...!!!');
        form.no_rekening_penerima.focus();	
			}else if (form.nama_rekening_penerima.value==""){
        alert('Nama Rekening Penerima tidak boleh kosong...!!!');
        form.nama_rekening_penerima.focus();	
			}else if (form.list_bank_penerima.value==""){
        alert('Bank Penerima tidak boleh kosong...!!!');
        form.list_bank_penerima.focus();
			}else if (form.status_valid_rekening_penerima.value!="Y"){
        alert('Rekening belum valid, tidak dapat disimpan, Tickmark Valid jika memang suda valid...!!!');
        form.status_valid_rekening_penerima.focus();	
			}else if (form.kode_bank_pembayar.value==""){
        alert('Rekening Bank Pembayar tidak boleh kosong...!!!');
        form.kode_bank_pembayar.focus();																																					
      }else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}		
    function fl_js_span_kode_hubungan() 
    {      
      var	v_kode_tipe_penerima = document.getElementById('kode_tipe_penerima').value;
					
    	if (v_kode_tipe_penerima =="AW") //ahli waris ----------------------------
      {
        window.document.getElementById("span_kode_hubungan").style.display = 'block';	
      }else
      {
        window.document.getElementById("span_kode_hubungan").style.display = 'none';
        window.document.getElementById("kode_hubungan").value = "";	
      }
    }		
    function fl_js_span_nomor_identitas() 
    { 
      var	v_kode_tipe_penerima = document.getElementById('kode_tipe_penerima').value;
			var	v_jenis_identitas 	 = document.getElementById('jenis_identitas').value;
					
    	if (v_kode_tipe_penerima =="PR" || v_kode_tipe_penerima =="FK" || v_kode_tipe_penerima =="TG") //perusahaan/faskes
      {
        window.document.getElementById("span_nomor_identitas").style.display = 'none';
        window.document.getElementById("nomor_identitas").value = "";	
				window.document.getElementById("jenis_identitas").value = "";					
      }else
      {
       	window.document.getElementById("span_nomor_identitas").style.display = 'block';
				
				if (v_jenis_identitas=="")
				{
				 	 window.document.getElementById("jenis_identitas").value = "KTP";
				}	   
      }
    }
    function fl_js_kode_hubungan() 
    {      
      var	v_kode_hubungan = document.getElementById('kode_hubungan').value;
					
    	if (v_kode_hubungan =="L") //lain-lain ----------------------------
      {
        window.document.getElementById("span_kode_hubungan_lain").style.display = 'block';	
      }else
      {
        window.document.getElementById("span_kode_hubungan_lain").style.display = 'none';
        window.document.getElementById("ket_hubungan_lainnya").value = "";	
      }
    }		
    function fl_js_val_nomor_identitas() 
    { 
      var	v_nomor_identitas = document.getElementById('nomor_identitas').value;
			
			if (v_nomor_identitas!='')
			{		
    		cekValidasiAdminduk();
			}
			
      var v_jenis_identitas = window.document.getElementById('jenis_identitas').value;
			var v_nomor_identitas = window.document.getElementById('nomor_identitas').value;
			
			if (v_jenis_identitas=='KTP')
      {
         if ((v_nomor_identitas!='') && (v_nomor_identitas.length!=16))
				 {
  				 document.getElementById('nomor_identitas').value = '';				 
  				 window.document.getElementById('nomor_identitas').focus();
					 curr_nomor_identitas = '';
					 fl_js_reset_field();
  				 alert("Untuk KTP, Nomor Identitas harus 16 karakter...!!!");         
  				 return false;
				 }
      }			
    }			
	
	function fl_js_span_bhp() 
    {
		 	var	v_kode_tipe_penerima = window.document.getElementById('kode_tipe_penerima').value;
			
			if (v_kode_tipe_penerima=="BH")
			{
			 	window.document.getElementById("span_kode_hubungan").style.display = 'none';
				window.document.getElementById("span_kode_hubungan_lain").style.display = 'none';
				window.document.getElementById("span_nomor_identitas").style.display = 'none';
				window.document.getElementById("span_jhtinput_hide_bhp2").style.display = 'none';
				window.document.getElementById("span_jhtinput_hide_bhp3").style.display = 'none';
				
				document.getElementById('nama_bank_penerima').readOnly = true;
        document.getElementById('nama_bank_penerima').style.backgroundColor='#F5F5F5';
				document.getElementById('no_rekening_penerima').readOnly = true;
        document.getElementById('no_rekening_penerima').style.backgroundColor='#F5F5F5';
				
				var v_nama_rekening_penerima = window.document.getElementById('nama_rekening_penerima').value;
				$('#nama_rekening_penerima_ws').val(v_nama_rekening_penerima);

				window.document.getElementById("lov_bank_penerima").style.display = 'none';
				
				//disabled field ----------------------------------------
				document.getElementById('nama_penerima').readOnly = true;
        document.getElementById('nama_penerima').style.backgroundColor='#F5F5F5';
				document.getElementById('alamat').readOnly = true;
        document.getElementById('alamat').style.backgroundColor='#F5F5F5';
				document.getElementById('email').readOnly = true;
        document.getElementById('email').style.backgroundColor='#F5F5F5';
				document.getElementById('telepon_area').readOnly = true;
        document.getElementById('telepon_area').style.backgroundColor='#F5F5F5';
				document.getElementById('telepon').readOnly = true;
        document.getElementById('telepon').style.backgroundColor='#F5F5F5';
				document.getElementById('telepon_ext').readOnly = true;
        document.getElementById('telepon_ext').style.backgroundColor='#F5F5F5';																		 
			}else
			{
        fl_js_span_nomor_identitas();
        fl_js_span_kode_hubungan();
				window.document.getElementById("span_jhtinput_hide_bhp2").style.display = 'block';
				window.document.getElementById("span_jhtinput_hide_bhp3").style.display = 'block';
				
        document.getElementById('nama_bank_penerima').readOnly = false;
        document.getElementById('nama_bank_penerima').style.backgroundColor='#ffff99';
        document.getElementById('no_rekening_penerima').readOnly = false;
        document.getElementById('no_rekening_penerima').style.backgroundColor='#ffff99';	
				window.document.getElementById("lov_bank_penerima").style.display = '';
				
        //enabled field ----------------------------------------
        document.getElementById('nama_penerima').readOnly = false;
        document.getElementById('nama_penerima').style.backgroundColor='#ffff99';
        document.getElementById('alamat').readOnly = false;
        document.getElementById('alamat').style.backgroundColor='#ffffff';
        document.getElementById('email').readOnly = false;
        document.getElementById('email').style.backgroundColor='#ffffff';
        document.getElementById('telepon_area').readOnly = false;
        document.getElementById('telepon_area').style.backgroundColor='#ffffff';
        document.getElementById('telepon').readOnly = false;
        document.getElementById('telepon').style.backgroundColor='#ffffff';
        document.getElementById('telepon_ext').readOnly = false;
        document.getElementById('telepon_ext').style.backgroundColor='#ffffff';													
		}			 
	}	
  </script>															
</head>
<body>
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	
	<?
	if($btn_task=="simpan")
	{         				
  		$sql = "update sijstk.pn_klaim_penerima_manfaat ".
             "set    kode_hubungan                  = '$ls_kode_hubungan', ".
             "       ket_hubungan_lainnya           = '$ls_ket_hubungan_lainnya', ".
             "       no_urut_keluarga               = null, ".
             "       jenis_identitas                = '$ls_jenis_identitas', ".
             "       status_valid_identitas         = nvl('$ls_status_valid_identitas','T'), ".
             "       nomor_identitas                = '$ls_nomor_identitas', ".
             "       nama_pemohon                   = '$ls_nama_penerima', ".
             "       tempat_lahir                   = '$ls_tempat_lahir', ".
             "       tgl_lahir                      = to_date('$ld_tgl_lahir','dd/mm/yyyy'), ".
             "       jenis_kelamin                  = substr('$ls_jenis_kelamin',1,1), ".
             "       golongan_darah                 = substr('$ls_golongan_darah',1,2), ".
             "       alamat                         = '$ls_alamat', ".
             "       rt                             = '$ls_rt', ".
             "       rw                             = '$ls_rw', ".
             "       kode_kelurahan                 = '$ls_kode_kelurahan', ".
             "       kode_kecamatan                 = '$ls_kode_kecamatan', ".
             "       kode_kabupaten                 = '$ls_kode_kabupaten', ".
             "       kode_pos                       = '$ls_kode_pos', ".
             "       telepon_area                   = '$ls_telepon_area', ".
             "       telepon                        = '$ls_telepon', ".
             "       telepon_ext                    = '$ls_telepon_ext', ".
             "       handphone                      = '$ls_handphone', ".
             "       email                          = '$ls_email', ".
             "       npwp                           = '$ls_npwp', ".
             "       nama_penerima                  = '$ls_nama_penerima', ".
             "       bank_penerima                  = '$ls_nama_bank_penerima', ".
             "       kode_bank_penerima             = '$ls_kode_bank_penerima', ".
             "       id_bank_penerima               = '$ls_id_bank_penerima', ".
             "       no_rekening_penerima           = '$ls_no_rekening_penerima', ".
             "       nama_rekening_penerima         = '$ls_nama_rekening_penerima', ".
             "       status_valid_rekening_penerima = nvl('$ls_status_valid_rekening_penerima','T'), ".
             "       kode_bank_pembayar             = '$ls_kode_bank_pembayar', ".
             "       status_rekening_sentral        = nvl('$ls_status_rekening_sentral','T'), ".
             "       kantor_rekening_sentral        = '$ls_kantor_rekening_sentral', ".
             "       keterangan                     = '$ls_keterangan', ".
             "       tgl_ubah                       = sysdate, ".
             "       petugas_ubah                   = '$username' ".
             "where  kode_klaim                     = '$ls_kode_klaim' ".
             "and    kode_tipe_penerima             = '$ls_kode_tipe_penerima' ";
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
							
      //echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      //echo "refreshParent();";
      //echo "</script>";								            
	} //end if(isset($_POST['simpan'])) 
	?>	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
    <?
    if ($ls_kode_tipe_penerima !="")
    {
      $sql = "select
                  a.kode_klaim, a.kode_tipe_penerima, b.nama_tipe_penerima, 
                  a.kode_hubungan, a.ket_hubungan_lainnya, a.no_urut_keluarga, a.jenis_identitas, 
                  nvl(a.status_valid_identitas,'T') status_valid_identitas, a.nomor_identitas, a.nama_pemohon, a.tempat_lahir, 
                  to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin, a.golongan_darah, a.alamat, a.rt,a.rw,  
                  a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan) nama_kelurahan,
                  a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan,
                  a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten,
                  a.kode_pos, a.telepon_area, a.telepon, a.telepon_ext, a.handphone, a.email, a.npwp, 
                  a.nama_penerima, a.bank_penerima, a.kode_bank_penerima, a.id_bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, 
                  nvl(a.status_valid_rekening_penerima,'T') status_valid_rekening_penerima, a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, 
                  a.nom_ppn, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.kode_bank_pembayar, nvl(a.status_rekening_sentral,'T') status_rekening_sentral, 
                  a.kantor_rekening_sentral, a.keterangan, a.status_lunas, a.tgl_lunas, a.petugas_lunas  
              from sijstk.pn_klaim_penerima_manfaat a, sijstk.pn_kode_tipe_penerima b
              where a.kode_tipe_penerima = b.kode_tipe_penerima
              and a.kode_klaim = '$ls_kode_klaim'
              and a.kode_tipe_penerima = '$ls_kode_tipe_penerima'";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
			$ls_kode_klaim				 		= $data["KODE_KLAIM"];	 
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

      $ls_list_bank_penerima		= $data["KODE_BANK_PENERIMA"];
      $ls_nama_bank_penerima		= $data["BANK_PENERIMA"];
      $ls_kode_bank_penerima		= $data["KODE_BANK_PENERIMA"];
      $ls_id_bank_penerima			= $data["ID_BANK_PENERIMA"];
      $ls_no_rekening_penerima	= $data["NO_REKENING_PENERIMA"];
      $ls_nama_rekening_penerima 		= $data["NAMA_REKENING_PENERIMA"];
      $ls_status_valid_rekening_penerima = $data["STATUS_VALID_REKENING_PENERIMA"];
      $ls_kode_bank_pembayar						 = $data["KODE_BANK_PEMBAYAR"];
      $ls_status_rekening_sentral		= $data["STATUS_REKENING_SENTRAL"];
      $ls_kantor_rekening_sentral		= $data["KANTOR_REKENING_SENTRAL"];
			
			if ($ls_task=="Edit")
			{
			 	//cek apakah rekening pembayaran tersentral di kantor pusat -----------
        $sql = "select nvl(status_rekening_sentral,'T') as status_rekening_sentral from sijstk.ms_kantor ".
						 	 "where kode_kantor = '$ls_kode_kantor'";
        $DB->parse($sql);
        $DB->execute();
        $data = $DB->nextrow();
        $ls_status_rekening_sentral	= $data["STATUS_REKENING_SENTRAL"];
				
				if ($ls_status_rekening_sentral=="Y")
				{
				 	$ls_kantor_rekening_sentral = "ATP";	 		
				}else
				{
				 	$ls_kantor_rekening_sentral = "";	 
				}							 
			}																		
    }	
		?>
		
    <?PHP
    //--------------------- validasi ajax --------------------------------------
    ?>	
    <script type="text/javascript" src="../../javascript/validator.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    
    <script type="text/javascript">
    //Create validator object
    var validator = new formValidator();
    var ajax = new sack();
    //ambil nilai previous, dibandingkan dg nilai current, apabila berbeda maka ajax akan dijalankan
    var curr_nomor_identitas =<?php echo ($ls_nomor_identitas=='') ? 'false' : "'".$ls_nomor_identitas."'"; ?>;
								    
    function showClientData()
    {
      var formObj = document.fpop;
      eval(ajax.response);
      var st_errorval1 = window.document.getElementById("st_errval1").value;
      
      //tampilan error jika nomor identitas (nik) tidak valid --------------------  					
      if (st_errorval1 == 1)
      {  
        window.document.getElementById("dispError1").innerHTML = "(* NIK tidak valid ..!!!";
        window.document.getElementById("dispError1").style.display = 'block';
        window.document.getElementById('nomor_identitas').focus();
      }else{
      	window.document.getElementById("dispError1").style.display = 'none';
      } 		
  	}	
		</script>		
    <?PHP
    //--------------------- end validasi ajax ----------------------------------
    ?>	
		
		<!--	
    <table class="captionentry">
    <tr> 
    <td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
    </tr>
    </table>
		-->
		
    <table class="captionform">
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 27px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 3px 3px 3px;height: 20px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
      </style>		
      <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
      <tr><td colspan="3"></br></td></tr>	
    </table>
											
		<div id="formframe" style="width:1100px;">
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
		
			<div id="formKiri" style="width:1100px;">
				<table width="1050px" border="0">
					<tr>
						<td width="55%" valign="top">
      				<fieldset style="height:420px;"><legend ><b><i><font color="#009999">Detil Informasi Penerima Manfaat</font></i></b></legend>
      					</br>
                <div class="form-row_kiri">
                <label  style = "text-align:right;">Tipe Penerima &nbsp;</label>
                  <input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" maxlength="10" readonly class="disabled" >
      						<input type="text" id="nama_tipe_penerima" name="nama_tipe_penerima" value="<?=$ls_nama_tipe_penerima;?>" style="width:235px;" readonly class="disabled" >                					
                </div>
								<div class="clear"></div>
																				
      					<span id="span_kode_hubungan" style="display:none;">
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Ahli Waris</label>		 	    				
                    <select size="1" id="kode_hubungan" name="kode_hubungan" value="<?=$ls_kode_hubungan;?>" tabindex="2" class="select_format" style="width:241px;background-color:#ffff99;" onchange="fl_js_kode_hubungan();">
                    <option value="">-- Pilih --</option>
                    <? 
                      $sql = "select kode_hubungan,nama_hubungan from sijstk.kn_kode_hubungan_tk where nvl(aktif,'T') = 'Y' and kode_hubungan <> 'T' order by urutan";
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

      					<span id="span_kode_hubungan_lain" style="display:none;">
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">Hubungan Lainnya</label>		 	    				
                    <input type="text" id="ket_hubungan_lainnya" name="ket_hubungan_lainnya" value="<?=$ls_ket_hubungan_lainnya;?>" style="width:235px;background-color:#ffff99;" maxlength="300">	
                  </div>																																									
                	<div class="clear"></div>
      					</span>

								</br>
								
      					<span id="span_nomor_identitas" style="display:none;">			
                  <div class="form-row_kiri">
                  <label style = "text-align:right;">No. Identitas </label>
                    <select size="1" id="jenis_identitas" name="jenis_identitas" value="<?=$ls_jenis_identitas;?>" tabindex="2" class="select_format" style="width:53px;background-color:#ffff99;" onChange="fl_js_val_jenis_identitas();">
                      <option value="">-- Pilih --</option>
                      <? 
                      $sql = "select kode from sijstk.ms_lookup where tipe='JID' and nvl(aktif,'T') = 'Y' order by seq";
                      $DB->parse($sql);
                      $DB->execute();
                      while($row = $DB->nextrow())
                      {
                      echo "<option ";
                      if ($row["KODE"]==$ls_jenis_identitas && strlen($ls_jenis_identitas)==strlen($row["KODE"])){ echo " selected"; }
                      echo " value=\"".$row["KODE"]."\">".$row["KODE"]."</option>";
                      }
                      ?>
                    </select>									
              			<input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" maxlength="16" tabindex="3" style="background-color:#ffff99;width:160px;" onblur="fl_js_val_nomor_identitas();">
      							
										<input type="hidden" id="status_valid_identitas" name="status_valid_identitas" value="<?=$ls_status_valid_identitas;?>">
        						<? $ls_status_valid_identitas = isset($ls_status_valid_identitas) ? $ls_status_valid_identitas : "T"; ?>					
            				<input type="checkbox" id="cb_status_valid_identitas" name="cb_status_valid_identitas" disabled class="cebox" <?=$ls_status_valid_identitas=="Y" ||$ls_status_valid_identitas=="ON" ||$ls_status_valid_identitas=="on" ? "checked" : "";?>><i>Valid</i>
                	
										<input type="hidden" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" maxlength="50">
      							<input type="hidden" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" maxlength="30">
      							<input type="hidden" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>" maxlength="10">	
										<input type="hidden" id="golongan_darah" name="golongan_darah" value="<?=$ls_golongan_darah;?>" maxlength="2">
                  </div>																																									
                	<div class="clear"></div>
      					</span>
																      						
                <div class="form-row_kiri">
                <label style = "text-align:right;">Nama Penerima *</label>
            			<input type="text" id="nama_penerima" name="nama_penerima" value="<?=$ls_nama_penerima;?>" tabindex="4" maxlength="100" style="background-color:#ffff99;width:235px;">
      						<input type="hidden" id="nama_pemohon" name="nama_pemohon" value="<?=$ls_nama_pemohon;?>" maxlength="100">	
                </div>																																														
              	<div class="clear"></div>										

      					<?
                  echo "<script type=\"text/javascript\">fl_js_span_nomor_identitas();</script>";
                  echo "<script type=\"text/javascript\">fl_js_span_kode_hubungan();</script>";
                ?>
      										
                <div class="form-row_kiri">
                <label style = "text-align:right;">Alamat </label>
            			<input type="text" id="alamat" name="alamat" value="<?=$ls_alamat;?>" tabindex="5" style="width:235px;" maxlength="300">	
                </div>																																									
              	<div class="clear"></div>
				
				<span id="span_jhtinput_hide_bhp2" style="display:block;">
                <div class="form-row_kiri">
                <label style = "text-align:right;">RT/RW</label>		 	    				
                  <input type="text" id="rt" name="rt" value="<?=$ls_rt;?>" tabindex="6" style="width:50px;" maxlength="5" onblur="fl_js_val_numeric('rt');">
                  /
                  <input type="text" id="rw" name="rw" value="<?=$ls_rw;?>" tabindex="7" style="width:100px;" maxlength="5" onblur="fl_js_val_numeric('rw');">		
                </div>																																																	
                <div class="clear"></div>
      					
      					<div class="form-row_kiri">
                <label style = "text-align:right;">Kode Pos</label> 	    				
                  <input type="text" id="kode_pos" name="kode_pos" value="<?=$ls_kode_pos;?>" tabindex="8" style="background-color:#ffff99;width:135px;" maxlength="10" readonly>
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5001_lov_pos.php?p=pn5041_penetapanmanfaat_penerima.php&a=fpop&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','',800,500,1)">							
                  <img src="../../images/help.png" alt="Cari Kode Pos" border="0" align="absmiddle"></a>				           											
                </div>																																																	
                <div class="clear"></div>	
      
                <div class="form-row_kiri">
                <label style = "text-align:right;">Kelurahan &nbsp;</label>		 	    				
                  <input type="hidden" id="kode_kelurahan" name="kode_kelurahan" value="<?=$ls_kode_kelurahan;?>" maxlength="20" readonly class="disabled">
                  <input type="text" id="nama_kelurahan" name="nama_kelurahan" value="<?=$ls_nama_kelurahan;?>" style="width:190px;" readonly class="disabled">
                </div>																																																	
                <div class="clear"></div>
      
                <div class="form-row_kiri">
                <label style = "text-align:right;">Kecamatan &nbsp;</label>		 	    				
                  <input type="hidden" id="kode_kecamatan" name="kode_kecamatan" value="<?=$ls_kode_kecamatan;?>" maxlength="10">
                  <input type="text" id="nama_kecamatan" name="nama_kecamatan" value="<?=$ls_nama_kecamatan;?>" style="width:210px;" readonly class="disabled">
                </div>																																																						
                <div class="clear"></div>
      															
                <div class="form-row_kiri">
                <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
                  <input type="hidden" id="kode_kabupaten" name="kode_kabupaten" value="<?=$ls_kode_kabupaten;?>" maxlength="20">
            			<input type="text" id="nama_kabupaten" name="nama_kabupaten" value="<?=$ls_nama_kabupaten;?>" style="width:210px;" readonly class="disabled">
      						<input type="hidden" id="kode_propinsi" name="kode_propinsi" value="<?=$ls_kode_propinsi;?>" readonly class="disabled">
                  <input type="hidden" id="nama_propinsi" name="nama_propinsi" value="<?=$ls_nama_propinsi;?>" readonly class="disabled">											
                </div>																																																																
                <div class="clear"></div>				
      					</span>
								</br>		
								      							
                <div class="form-row_kiri">
                <label style = "text-align:right;">Email &nbsp;</label>		 	    				
            			<input type="text" id="email" name="email" tabindex="8" value="<?=$ls_email;?>" style="width:160px;" maxlength="200" onblur="fl_js_val_email('email');">
                </div>																																																																																															
                <div class="clear"></div>
            
                <div class="form-row_kiri">
                <label style = "text-align:right;">No. Telp</label>	    				
                  <input type="text" id="telepon_area" name="telepon_area" tabindex="9" value="<?=$ls_telepon_area;?>" size="2" maxlength="5" onblur="fl_js_val_numeric('telepon_area');">
                  <input type="text" id="telepon" name="telepon" tabindex="10" value="<?=$ls_telepon;?>" size="12" maxlength="20" onblur="fl_js_val_numeric('telepon');">
                  &nbsp;ext.
                  <input type="text" id="telepon_ext" name="telepon_ext" tabindex="11" value="<?=$ls_telepon_ext;?>" size="2" maxlength="5" onblur="fl_js_val_numeric('telepon_ext');"> 						
                </div>																																															
            		<div class="clear"></div>
            		
					<span id="span_jhtinput_hide_bhp3" style="display:block;">
                <div class="form-row_kiri">
                <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
            			<input type="text" id="handphone" name="handphone" tabindex="12" value="<?=$ls_handphone;?>" style="width:160px;" maxlength="15" onblur="fl_js_val_numeric('handphone');">
                </div>																																																																																															
                <div class="clear"></div>
            							
                <div class="form-row_kiri">
                <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
            			<input type="text" id="npwp" name="npwp" tabindex="13" value="<?=$ls_npwp;?>" maxlength="15" style="width:140px;background-color:#ffff99;" onblur="fl_js_val_npwp('npwp');">
                </div>
                <div class="clear"></div>											
					</span>
                <div class="form-row_kiri">
                <label style = "text-align:right;">Keterangan &nbsp;</label>
                	<textarea cols="255" rows="1" style="width:230px" id="keterangan" name="keterangan" tabindex="25" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
                </div>														
                <div class="clear"></div>
      					
								</br>										
      				</fieldset>						
						</td>

						<td width="50%" valign="top">
							<table width="600px" border="0">
								<tr>
									<td>
										<fieldset><legend >&nbsp;</legend>
                      <?
											if ($ls_nomor_identitas=="")
											{
											?>
											<input id="tk_foto" name="tk_foto" type="image" align="center" src="../../images/nopic.png" style="height: 130px !important; width: 125px !important;"/>
                      <?
											}else
											{
											?>
											<img id="tk_foto" src="../../mod_kn/ajax/kngetfoto.php?dataid=<?=$ls_nomor_identitas;?>" style="height: 130px !important; width: 125px !important;"/>
                      <?
											}
											?>
											<div class="clear"></div>																				
										</fieldset>	
									</td>	
								</tr>
								
								<tr>
									<td>
										<fieldset style="height:245px;"><legend ><b><i><font color="#009999">Rekening Pembayaran</font></i></b></legend>																					
                      </br>
											<div class="form-row_kiri">
                      <label style = "text-align:right;"><i><font color="#009999">Ditransfer ke :</font></i></label>
            						<input type="text" id="temp3" name="temp3" size="25" style="border-width: 0;text-align:left" readonly>	    				
                      </div>																																																						
                      <div class="clear"></div>
            															
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Bank *</label>
                  			<select disabled onchange="setAkunBank()" size="1" id="list_bank_penerima" name="list_bank_penerima" value="<?=$ls_list_bank_penerima;?>" tabindex="24" class="select_format" style="width:300px;background-color:#ffff99;color:#000;" >
                        <option value="">-- Pilih --</option>
                        <? 
                          $sql = "select kode_bank, nama_bank from sijstk.ms_bank where aktif='Y' and tipe_bank='F' order by kode_bank";
                          $DB->parse($sql);
                          $DB->execute();
                          while($row = $DB->nextrow())
                          {
                            echo "<option ";
                            if ($row["NAMA_BANK"]==$ls_bank_penerima && strlen($ls_bank_penerima)==strlen($row["NAMA_BANK"])){ echo " selected"; }
                            echo " value=\"".$row["NAMA_BANK"]."\">".$row["NAMA_BANK"]."</option>";
                          }
                        ?>
                        </select>                      
											</div>																																																																
                      <div class="clear"></div>				
            					      							
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">No Rekening *</label>
											  <input type="hidden" id="nama_bank_penerima" name="nama_bank_penerima" value="<?=$ls_nama_bank_penerima;?>"style="width:100px;">
											  <input type="hidden" id="kode_bank_penerima" name="kode_bank_penerima" value="<?=$ls_kode_bank_penerima;?>"style="width:100px;">
												<input type="hidden" id="id_bank_penerima" name="id_bank_penerima" value="<?=$ls_id_bank_penerima;?>"style="width:100px;">
                  			<input type="text" id="no_rekening_penerima" name="no_rekening_penerima" onblur="cekValidasiRekening();" value="<?=$ls_no_rekening_penerima;?>" tabindex="22" maxlength="30" style="width:100px;background-color:#ffff99;">
												<input type="text" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --">
												<input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" class="cebox" onclick="copyNamaRekeningPenerima()" <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>><i><font color="#009999">Valid</font></i>	
                      </div>																																																																																															
                      <div class="clear"></div>
                  
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">A/N *</label>
                  			<input type="text" id="nama_rekening_penerima" name="nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" tabindex="23" maxlength="100" style="width:294px;background-color:#ffff99;" readonly>	
                      </div>																																																
                  		<div class="clear"></div>

                      <div class="form-row_kiri">
                      <label style = "text-align:right;">&nbsp;</label>	 	    				
                        <input type="hidden" id="status_valid_rekening_penerima" name="status_valid_rekening_penerima" value="<?=$ls_status_valid_rekening_penerima;?>">
            						<? $ls_status_valid_rekening_penerima = isset($ls_status_valid_rekening_penerima) ? $ls_status_valid_rekening_penerima : "T"; ?>					
                				</div>																																																																																																									
                      <div class="clear"></div>	
											                  		
											</br>
											
                      <div class="form-row_kiri">
                      <label style = "text-align:right;"><i><font color="#009999">Dibayar Melalui :</font></i></label>
            						<input type="text" id="temp3" name="temp3" size="25" style="border-width: 0;text-align:left" readonly>	    				
                      </div>																																																																																															
                      <div class="clear"></div>
                  							
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Bank *</label>	 	    				
                        <select size="1" id="kode_bank_pembayar" name="kode_bank_pembayar" value="<?=$ls_kode_bank_pembayar;?>" tabindex="24" class="select_format" style="width:220px;background-color:#ffff99;" >
                        <option value="">-- Pilih --</option>
                        <? 
                          if ($ls_status_rekening_sentral=="Y")
													{
  													$sql = "select distinct a.kode_bank, b.nama_bank from ms.ms_rekening_detil a, ms.ms_bank b ".
                                   "where a.kode_bank = b.kode_bank ".
                                   "and a.tipe_rekening='36' ".
                                   "and a.kode_kantor = 'ATP' ".
                                   "order by a.kode_bank";
													}else
													{
  													$sql = "select distinct a.kode_bank, c.nama_bank ".
                                   "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
                                   "where a.kode_kantor = b.kode_kantor(+) ".  
                                   "and a.kode_bank = b.kode_bank(+) ".  
                                   "and a.kode_rekening = b.kode_rekening(+) ".  
                                   "and a.kode_buku = b.kode_buku(+) ".  
                                   "and a.kode_bank = c.kode_bank ".   
                                   "and a.kode_kantor = '$ls_kode_kantor' ". 
                                   "and nvl(a.aktif,'T')='Y' ".
                                   "and b.tipe_rekening in ('13','14','15','16','17') ". 
                                   "order by a.kode_bank";													
													}			 
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

                      <div class="form-row_kiri">
                      <label style = "text-align:right;">&nbsp;</label>	 	    				
                        <input type="hidden" id="status_rekening_sentral" name="status_rekening_sentral" value="<?=$ls_status_rekening_sentral;?>">
            						<? $ls_status_rekening_sentral = isset($ls_status_rekening_sentral) ? $ls_status_rekening_sentral : "T"; ?>					
                				<input type="checkbox" id="cb_status_rekening_sentral" name="cb_status_rekening_sentral" disabled class="cebox" <?=$ls_status_rekening_sentral=="Y" ||$ls_status_rekening_sentral=="ON" ||$ls_status_rekening_sentral=="on" ? "checked" : "";?>><i><font color="#009999">Sentralisasi Rekening</font></i>
                      	<input type="hidden" id="kantor_rekening_sentral" name="kantor_rekening_sentral" value="<?=$ls_kantor_rekening_sentral;?>">
											</div>																																																																																																									
                      <div class="clear"></div>	
											</br>																		
										</fieldset>	
									</td>									
								</tr>		 
							</table>	
						</td>					
					</tr>		 
				</table>	 
				<?
          echo "<script type=\"text/javascript\">fl_js_span_bhp();</script>";
        ?>				
        <? 					
        if(!empty($ls_kode_tipe_penerima))
        {
        ?>			 	
          <div id="buttonbox" style="width:1030px;text-align:center;">       			 
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

<script type="text/javascript">
 		$(document).ready(function(){
				getListBank();
		});
		
		function setAkunBank(){
				var lov_bank = document.getElementById('list_bank_penerima');
			  $('#id_bank_penerima').val(document.getElementById('list_bank_penerima').value);
				$('#nama_bank_penerima').val(lov_bank.options[lov_bank.selectedIndex].getAttribute('namabank'));
				$('#kode_bank_penerima').val(lov_bank.options[lov_bank.selectedIndex].getAttribute('kodebank'));
		}
		
		function getListBank(){
		  bank_selected = '<?=$ls_list_bank_penerima?>';
			console.log('GetListBank');
				$.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerimaajax.php?'+Math.random(),
          data: { TYPE:"get_list_bank" },
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);
						var lov_bank = document.getElementById('list_bank_penerima');
            if(jdata.ret == "0")
            {   
                for($i=0; $i<=jdata.numrows-1;$i++){
									  var option = document.createElement('option');
										option.text  = jdata.data[$i]['NAMA_BANK'];
										option.value = jdata.data[$i]['BANK'];
										option.setAttribute('namabank',jdata.data[$i]['NAMA_BANK']);
										option.setAttribute('kodebank',jdata.data[$i]['KODE_BANK']);
										if(jdata.data[$i]['KODE_BANK']==bank_selected){
											 option.selected = true;
										}
										lov_bank.add(option); 
								}
            }else{
                window.parent.Ext.notify.msg('Gagal get list bank...', jdata.msg);
            }
          }
        });
		}
		
		function copyNamaRekeningPenerima(){
				//$('#nama_rekening_penerima').val($('#nama_rekening_penerima_ws').val());
				fl_js_set_status_valid_rekening_penerima();
		}

		function fl_js_set_status_valid_rekening_penerima()
    {
    	var form = document.fpop;
    	if (form.cb_valid_rekening.checked)
    	{
    		form.status_valid_rekening_penerima.value = "Y";
				$('#nama_rekening_penerima').val($('#nama_rekening_penerima_ws').val());
    	}
    	else
    	{
    		form.status_valid_rekening_penerima.value = "T";
				form.nama_rekening_penerima.value = "";
    	}	
    }
				
		function cekValidasiRekening(){
		 		preload(true);
			  var bank_list 		= document.getElementById('list_bank_penerima');
				var bank_selected = bank_list.options[bank_list.selectedIndex].value;
				if(bank_selected!=''){
						$.ajax(
            {
              type: 'POST',
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerimaajax.php?'+Math.random(),
              data: { TYPE:'cek_validasi_rekening',NOREK:$('#no_rekening_penerima').val(),BANK:bank_selected },
              success: function(data)
              {
                preload(false);
    						//console.log(data);
                jdata = JSON.parse(data);
    						//console.log(jdata);     
                if(jdata.ret=="0")
                {   
    								$('#nama_rekening_penerima_ws').val(jdata.data['NAMA_REK_TUJUAN']);
                    /*if(($('#nama_rekening_penerima').val()==jdata.data['NAMA_REK_TUJUAN'])&&($('#no_rekening_penerima').val()==jdata.data['NOREK_TUJUAN'])){
    										$('#cb_status_valid_rekening_penerima').checked = true;																																																				
    								}else{
    										$('#cb_status_valid_rekening_penerima').checked = false;	
    								}*/
                }else{
                    window.parent.Ext.notify.msg('Gagal validasi rekening...', jdata.msg);
                }
              }
            });
				}
		}
		
    function cekValidasiAdminduk(){
        var vNik = $('#nomor_identitas').val();
				$.ajax(
        {
          type: 'POST',
          url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerimaajax.php?'+Math.random(),
          data: { TYPE:"cek_validasi_adminduk",NIK:vNik},
          success: function(data)
          {
            preload(false);
            jdata = JSON.parse(data);     
            if(jdata.success == true)
            {   
                console.log(data);
                $('#status_valid_identitas').val('Y');
								window.document.getElementById('cb_status_valid_identitas').checked = true;
                $('#nama_penerima').val(jdata.data['namaLengkap']);
								$('#nama_pemohon').val(jdata.data['namaLengkap']);
								$('#tempat_lahir').val(jdata.data['tempatLahir']);
                $('#tgl_lahir').val(jdata.data['tanggalLahir']);
								if((jdata.data['golDarah']!='O')&&(jdata.data['golDarah']!='A')&&(jdata.data['golDarah']!='B')&&(jdata.data['golDarah']!='AB')){
                    $("#golongan_darah").val('');  
                }else{
                    $("#golongan_darah").val(jdata.data['golDarah']);  
                }
								$('#jenis_kelamin').val(jdata.data['jenisKelamin']);
								$('#alamat').val(jdata.data['alamat']);
								
								$('#rt').val(jdata.data['rt']);
								$('#rw').val(jdata.data['rw']);
								$('#kode_pos').val(jdata.data['kodePos']);
								$('#kode_kelurahan').val(jdata.data['kelNo']);
								$('#nama_kelurahan').val(jdata.data['namaKel']);
								$('#kode_kecamatan').val(jdata.data['kecNo']);
								$('#nama_kecamatan').val(jdata.data['namaKec']);
								$('#kode_kabupaten').val(jdata.data['kabNo']);
								$('#nama_kabupaten').val(jdata.data['namaKab']);
								$('#nama_propinsi').val(jdata.data['namaProp']);
								
								curr_nomor_identitas = vNik;
                $('#tk_foto').attr('src','<?=$wsIp;?>'+jdata.data['showFoto']);
								
            }else{
              preload(false);
              console.log(data);
								$('#status_valid_identitas').val('T');
								window.document.getElementById('cb_status_valid_identitas').checked = false;
                $('#nama_penerima').val('');
								$('#nama_pemohon').val('');
								$('#tempat_lahir').val('');
                $('#tgl_lahir').val('');
								$("#golongan_darah").val('');
                $('#jenis_kelamin').val('');
								$('#alamat').val('');
                $('#tk_foto').attr('src','../../images/nopic.png');
								curr_nomor_identitas = '';
												
								alert(jdata.msg);
								$('#nomor_identitas').focus();
            }
          }
        });
    }
</script>