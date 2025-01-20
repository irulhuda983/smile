<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
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
  $sql = "select substr(kode_tipe_klaim,1,3) jenis_klaim, kode_kantor, status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];	
	$ls_kode_kantor				= $row['KODE_KANTOR'];
  $ls_jenis_klaim				= $row['JENIS_KLAIM'];
	
	//ambil kantor yg akan melakukan pembayaran ----------------------------------
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_GET_KANTORBAYAR('$ls_kode_klaim',:p_kantor_pembayar,:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  oci_bind_by_name($proc, ":p_kantor_pembayar", $p_kantor_pembayar,32);
	oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	
	$ls_kantor_pembayar = $p_kantor_pembayar;	
	if ($ls_kantor_pembayar=="")
	{
	 	 $ls_kantor_pembayar = $ls_kode_kantor;
	}		
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
$ls_npwp_old							= $_POST['npwp_old'];
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
$ls_nama_bank_penerima		= ($_POST['byr_kode_cara_bayar']=='L') ? $_POST['ln_nama_bank_penerima'] : $_POST['nama_bank_penerima'];
$ls_kode_bank_penerima		= ($_POST['byr_kode_cara_bayar']=='L') ? $_POST['ln_kode_bank_penerima'] : $_POST['kode_bank_penerima'];
$ls_id_bank_penerima			= ($_POST['byr_kode_cara_bayar']=='L') ? $_POST['ln_id_bank_penerima'] : $_POST['id_bank_penerima'];
$ls_no_rekening_penerima	= ($_POST['byr_kode_cara_bayar']=='L') ? $_POST['ln_no_rekening_penerima'] : $_POST['no_rekening_penerima'];
$ls_nama_rekening_penerima 		= ($_POST['byr_kode_cara_bayar']=='L') ? $_POST['ln_nama_rekening_penerima'] : $_POST['nama_rekening_penerima'];
$ls_status_valid_rekening_penerima = $_POST['status_valid_rekening_penerima'];
$ls_kode_bank_pembayar						 = $_POST['kode_bank_pembayar'];
$ls_status_rekening_sentral		= $_POST['status_rekening_sentral'];
$ls_kantor_rekening_sentral		= $_POST['kantor_rekening_sentral'];
$ls_metode_transfer						= $_POST['metode_transfer'];

$ls_kode_cara_bayar = $_POST['byr_kode_cara_bayar'];

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
      if($ls_sender == 'pn5040.php'){
        echo "window.location.replace('../form/$ls_sender?task=edit&kode_klaim=$ls_kode_klaim&task=$ls_task&no_level=$ln_no_level&dataid=$ls_kode_klaim&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&kode_realisasi=$ls_kode_realisasi&root_sender=$ls_root_sender&sender=$ls_sender&activetab=$ls_sender_activetab&mid=$ls_sender_mid ');";
      } else {
        echo "window.location.replace('$ls_sender?task=edit&kode_klaim=$ls_kode_klaim&task=$ls_task&no_level=$ln_no_level&dataid=$ls_kode_klaim&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&kode_realisasi=$ls_kode_realisasi&root_sender=$ls_root_sender&sender=$ls_sender&activetab=$ls_sender_activetab&mid=$ls_sender_mid ');";
      }
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
      var	v_kode_cara_bayar = window.document.getElementById('byr_kode_cara_bayar').value;
      if(v_kode_cara_bayar=='L'){
        var form = window.document.fpop;
        if(form.kode_tipe_penerima.value==""){
          alert('Tipe Penerima tidak boleh kosong...!!!');
          form.kode_tipe_penerima.focus();
        }else if (form.nama_penerima.value==""){
          alert('Nama Penerima tidak boleh kosong...!!!');
          form.nama_penerima.focus();	
        }else if (form.rg_metode_transfer.value=="ATR"){
          alert('Metode Transfer harus antar bank');
          form.rg_metode_transfer.focus();	
        }else if (form.npwp.value==""){
          alert('NPWP tidak boleh kosong, isikan 0 jika memang tidak memiliki NPWP...!!!');
          form.npwp.focus();
        }else if (form.ln_no_rekening_penerima.value==""){
          alert('No Rekening Penerima tidak boleh kosong...!!!');
          form.ln_no_rekening_penerima.focus();	
        }else if (form.ln_nama_rekening_penerima.value==""){
          alert('Nama Rekening Penerima tidak boleh kosong...!!!');
          form.ln_nama_rekening_penerima.focus();	
        }else if (form.ln_nama_bank_penerima.value==""){
          alert('Bank Penerima tidak boleh kosong...!!!');
          form.ln_nama_bank_penerima.focus();
        }	
        else if (form.kode_bank_pembayar.value==""){
          alert('Rekening Bank Pembayar tidak boleh kosong...!!!');
          form.kode_bank_pembayar.focus();																																					
        }else
        {
          form.btn_task.value="simpan";
          form.submit(); 		 
        }
      }else{
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
        }else if (form.nama_bank_penerima.value==""){
          alert('Bank Penerima tidak boleh kosong...!!!');
          form.nama_bank_penerima.focus();
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
      var	v_status_valid_identitas = document.getElementById('status_valid_identitas').value;
      var v_jenis_identitas = window.document.getElementById('jenis_identitas').value;
      
			if (v_nomor_identitas!='' && v_status_valid_identitas !='T')
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
  </script>															
</head>
<body>
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	
	<?
	if($btn_task=="simpan")
	{         				
      $qry = "BEGIN ".
  					 	 "update sijstk.pn_klaim_penerima_manfaat ".
               "set    kode_hubungan                  = :p_kode_hubungan, ".
               "       ket_hubungan_lainnya           = :p_ket_hubungan_lainnya, ".
               "       no_urut_keluarga               = null, ".
               "       jenis_identitas                = :p_jenis_identitas, ".
               "       status_valid_identitas         = nvl(:p_status_valid_identitas,'T'), ".
               "       nomor_identitas                = :p_nomor_identitas, ".
               "       nama_pemohon                   = :p_nama_pemohon, ".
               "       tempat_lahir                   = :p_tempat_lahir, ".
               "       tgl_lahir                      = to_date(:p_tgl_lahir,'dd/mm/yyyy'), ".
               "       jenis_kelamin                  = substr(:p_jenis_kelamin,1,1), ".
               "       golongan_darah                 = substr(:p_golongan_darah,1,2), ".
               "       alamat                         = :p_alamat, ".
               "       rt                             = :p_rt, ".
               "       rw                             = :p_rw, ".
               "       kode_kelurahan                 = :p_kode_kelurahan, ".
               "       kode_kecamatan                 = :p_kode_kecamatan, ".
               "       kode_kabupaten                 = :p_kode_kabupaten, ".
               "       kode_pos                       = :p_kode_pos, ".
               "       telepon_area                   = :p_telepon_area, ".
               "       telepon                        = :p_telepon, ".
               "       telepon_ext                    = :p_telepon_ext, ".
               "       handphone                      = :p_handphone, ".
               "       email                          = :p_email, ".
               "       npwp                           = :p_npwp, ".
               "       nama_penerima                  = :p_nama_penerima, ".
               "       bank_penerima                  = :p_nama_bank_penerima, ".
               "       kode_bank_penerima             = :p_kode_bank_penerima, ".
               "       id_bank_penerima               = :p_id_bank_penerima, ".
               "       no_rekening_penerima           = trim(:p_no_rekening_penerima), ".
               "       nama_rekening_penerima         = :p_nama_rekening_penerima, ".
               "       status_valid_rekening_penerima = nvl(:p_status_valid_rekening_penerima,'T'), ".
               "       kode_bank_pembayar             = :p_kode_bank_pembayar, ".
               "       status_rekening_sentral        = nvl(:p_status_rekening_sentral,'T'), ".
               "       kantor_rekening_sentral        = :p_kantor_rekening_sentral, ".
							 "       metode_transfer        				= :p_metode_transfer, ".
               "       kode_cara_bayar        				= :p_kode_cara_bayar, ".
               "       keterangan                     = :p_keterangan, ".
               "       tgl_ubah                       = sysdate, ".
               "       petugas_ubah                   = :p_username ".
               "where  kode_klaim           = :p_kode_klaim ".
               "and    kode_tipe_penerima   = :p_kode_tipe_penerima; ".			
						 "END;";											 	
      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_kode_hubungan", $ls_kode_hubungan,3);
      oci_bind_by_name($proc, ":p_ket_hubungan_lainnya", $ls_ket_hubungan_lainnya,300);
			oci_bind_by_name($proc, ":p_jenis_identitas", $ls_jenis_identitas,10);
			oci_bind_by_name($proc, ":p_status_valid_identitas", $ls_status_valid_identitas,1);
			oci_bind_by_name($proc, ":p_nomor_identitas", $ls_nomor_identitas,30);
			oci_bind_by_name($proc, ":p_nama_pemohon", $ls_nama_penerima,100);
			oci_bind_by_name($proc, ":p_tempat_lahir", $ls_tempat_lahir,50);
			oci_bind_by_name($proc, ":p_tgl_lahir", $ld_tgl_lahir,12);
			oci_bind_by_name($proc, ":p_jenis_kelamin", $ls_jenis_kelamin,10);
			oci_bind_by_name($proc, ":p_golongan_darah", $ls_golongan_darah,2);
			oci_bind_by_name($proc, ":p_alamat", $ls_alamat,300);
			oci_bind_by_name($proc, ":p_rt", $ls_rt,5);
			oci_bind_by_name($proc, ":p_rw", $ls_rw,5);
			oci_bind_by_name($proc, ":p_kode_kelurahan", $ls_kode_kelurahan,20);
			oci_bind_by_name($proc, ":p_kode_kecamatan", $ls_kode_kecamatan,10);
			oci_bind_by_name($proc, ":p_kode_kabupaten", $ls_kode_kabupaten,20);
			oci_bind_by_name($proc, ":p_kode_pos", $ls_kode_pos,10);
			oci_bind_by_name($proc, ":p_telepon_area", $ls_telepon_area,5);
			oci_bind_by_name($proc, ":p_telepon", $ls_telepon,20);
			oci_bind_by_name($proc, ":p_telepon_ext", $ls_telepon_ext,5);
			oci_bind_by_name($proc, ":p_handphone", $ls_handphone,20);
			oci_bind_by_name($proc, ":p_email", $ls_email,200);
			oci_bind_by_name($proc, ":p_npwp", $ls_npwp,30);
			oci_bind_by_name($proc, ":p_nama_penerima", $ls_nama_penerima,100);
			oci_bind_by_name($proc, ":p_nama_bank_penerima", $ls_nama_bank_penerima,100);
			oci_bind_by_name($proc, ":p_kode_bank_penerima", $ls_kode_bank_penerima,50);
			oci_bind_by_name($proc, ":p_id_bank_penerima", $ls_id_bank_penerima,50);
			oci_bind_by_name($proc, ":p_no_rekening_penerima", $ls_no_rekening_penerima,30);
			oci_bind_by_name($proc, ":p_nama_rekening_penerima", $ls_nama_rekening_penerima,100);
			oci_bind_by_name($proc, ":p_status_valid_rekening_penerima", $ls_status_valid_rekening_penerima,1);
			oci_bind_by_name($proc, ":p_kode_bank_pembayar", $ls_kode_bank_pembayar,5);
			oci_bind_by_name($proc, ":p_status_rekening_sentral", $ls_status_rekening_sentral,1);
			oci_bind_by_name($proc, ":p_kantor_rekening_sentral", $ls_kantor_rekening_sentral,5);
			oci_bind_by_name($proc, ":p_metode_transfer", $ls_metode_transfer,4);
			oci_bind_by_name($proc, ":p_keterangan", $ls_keterangan,300);
			oci_bind_by_name($proc, ":p_username", $username,30);
			oci_bind_by_name($proc, ":p_kode_klaim", $ls_kode_klaim,30);
			oci_bind_by_name($proc, ":p_kode_tipe_penerima", $ls_kode_tipe_penerima,10);
      oci_bind_by_name($proc, ":p_kode_cara_bayar", $ls_kode_cara_bayar,10);
      $DB->execute();

			$ls_ket_submit = "UPDATE DATA PENERIMA MANFAAT PADA SAAT PROSES ".$ls_status_klaim." KLAIM";
			
      if ($ls_npwp_old!=$ls_npwp)
			{
  			//cek apakah klaim jht, jika klaim jht maka hitung ulang manfaat ------------------
        $sql = "select count(*) as v_cnt from sijstk.pn_klaim_penerima_manfaat_prg ".
        		 	 "where kode_klaim = '$ls_kode_klaim' and kode_tipe_penerima = '$ls_kode_tipe_penerima' and kd_prg = 1 ";
        $DB->parse($sql);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_cnt_jht = $row["V_CNT"];		
				
				if ($ln_cnt_jht>="1")
				{ 
          // $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_JHTRINCI('$ls_kode_klaim','$username',:p_sukses,:p_mess);END;";											 	
          // $proc = $DB->parse($qry);				
          // oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
          // oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          // $DB->execute();				
          // $ls_sukses = $p_sukses;
          // $ls_mess = $p_mess;	

          $headers = array(
            'Content-Type: application/json',
          'X-Forwarded-For: ' . $ipfwd
          );
        
          $data = array(
            'chId'          => 'SMILE',
            'reqId'         => $username,
            "KODE_KLAIM"    => $ls_kode_klaim
          );
        
          $url=$wsIp."/JSPN5041/HitungManfaatJht";
          $ch     = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
          curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        
          $result = curl_exec($ch);
          $result = json_decode($result);
          
          if($result->ret=="0"){ 
            $ls_sukses = '1'; 		
          } else {
            $ls_sukses = '-1';
          }  
				}			
			}
			
			//generate aktivitas klaim -----------------------------------------------
      $qry = "BEGIN SIJSTK.P_PN_PN5040.X_INSERT_AKTIVITAS('$ls_kode_klaim', 'UPDATE', substr(upper('$ls_ket_submit'),1,300), '$username',:p_sukses,:p_mess);END;";											 	
      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
      $DB->execute();				
      $ls_sukses = $p_sukses;
      $ls_mess = $p_mess;	
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
                  a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan and rownum=1) nama_kelurahan,
                  a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan,
                  a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten,
                  a.kode_pos, a.telepon_area, a.telepon, a.telepon_ext, a.handphone, a.email, a.npwp, 
                  a.nama_penerima, a.bank_penerima, a.kode_bank_penerima, a.id_bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, 
                  nvl(a.status_valid_rekening_penerima,'T') status_valid_rekening_penerima, a.nom_manfaat_utama, a.nom_manfaat_tambahan, a.nom_manfaat_gross, 
                  a.nom_ppn, a.nom_pph, a.nom_pembulatan, a.nom_manfaat_netto, a.kode_bank_pembayar, nvl(a.status_rekening_sentral,'T') status_rekening_sentral, 
                  a.kantor_rekening_sentral, a.metode_transfer, a.keterangan, a.status_lunas, a.tgl_lunas, a.petugas_lunas,
                  a.kode_cara_bayar,
                  (select keterangan from ms.ms_lookup c where c.kode=a.kode_cara_bayar and tipe='KLMCRBYR') nama_cara_bayar  
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
      $ls_jenis_identitas				= $data["JENIS_IDENTITAS"];
      $ls_nomor_identitas				= $data["NOMOR_IDENTITAS"];
      $ls_nama_pemohon 					= $data["NAMA_PEMOHON"];
      $ls_tempat_lahir					= $data["TEMPAT_LAHIR"];
      $ld_tgl_lahir							= $data["TGL_LAHIR"];
      $ls_jenis_kelamin					= $data["JENIS_KELAMIN"];
      $ls_alamat								= $data["ALAMAT"];
      $ls_rt										= $data["RT"];
      $ls_rw 										= $data["RW"];
      $ls_kode_kelurahan				= $data["KODE_KELURAHAN"];
			$ls_nama_kelurahan				= $data["NAMA_KELURAHAN"];
      $ls_kode_kecamatan				= $data["KODE_KECAMATAN"];
			$ls_nama_kecamatan				= $data["NAMA_KECAMATAN"];
      $ls_kode_kabupaten 				= $data["KODE_KABUPATEN"];
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
			$ls_metode_transfer						= $data["METODE_TRANSFER"];
      $ls_kode_cara_bayar						= $data["KODE_CARA_BAYAR"];
      $ls_nama_cara_bayar						= $data["NAMA_CARA_BAYAR"];			
			if ($ls_metode_transfer=="ATR")
			{
			 	 $ls_rg_metode_transfer	= $ls_metode_transfer;													 
			}else
			{
			 	 $ls_rg_metode_transfer	= "ATB";
			}	
			
    	if ($ls_task=="Edit")
    	{  	
        //cek apakah rekening pembayaran untuk kantor pembayar adala tersentral di kantor pusat -----------
        $sql = "select nvl(status_rekening_sentral,'T') as status_rekening_sentral from sijstk.ms_kantor ".
        		 	 "where kode_kantor = '$ls_kantor_pembayar'";
        $DB->parse($sql);
        $DB->execute();
        $data = $DB->nextrow();
        $ls_status_rekening_sentral	= $data["STATUS_REKENING_SENTRAL"];
        
    		//TEMP: 
    		$ls_status_rekening_sentral = "Y";
				$ls_kantor_rekening_sentral = "ATP";
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
		
    <table aria-describedby="mydesc" class="captionform">
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 27px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 6px 3px 3px 3px;height: 20px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
      </style>	
      <tr><th></th></tr>	
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
      <input type="hidden" id="byr_jenis_klaim" name="byr_jenis_klaim" value="<?=$ls_jenis_klaim;?>">
		
			<div id="formKiri" style="width:1100px;">
				<table aria-describedby="mydesc" width="1050px" border="0">
          <tr><th></th></tr>
					<tr>
						<td width="50%" valign="top">
      				<fieldset style="height:460px;"><legend ><b><i><span style="color:#009999;">Detil Informasi Penerima Manfaat</span></i></b></legend>
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
            		
                <div class="form-row_kiri">
                <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
            			<input type="text" id="handphone" name="handphone" tabindex="12" value="<?=$ls_handphone;?>" style="width:160px;" maxlength="15" onblur="fl_js_val_numeric('handphone');">
                </div>																																																																																															
                <div class="clear"></div>
            							
                <div class="form-row_kiri">
                <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
            			<input type="text" id="npwp" name="npwp" tabindex="13" value="<?=$ls_npwp;?>" maxlength="15" style="width:140px;background-color:#ffff99;" onblur="fl_js_val_npwp('npwp');">
									<input type="hidden" id="npwp_old" name="npwp_old" value="<?=$ls_npwp;?>">
                </div>
                <div class="clear"></div>											
      
                <div class="form-row_kiri">
                <label style = "text-align:right;">Keterangan &nbsp;</label>
                	<textarea cols="255" rows="1" style="width:230px" id="keterangan" name="keterangan" tabindex="25" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
                </div>														
                <div class="clear"></div>
      					
								</br>										
      				</fieldset>						
						</td>

						<td width="50%" valign="top">
							<table aria-describedby="mydesc" width="550px" border="0">
                <tr><th></th></tr>
								<tr>
									<td>
										<fieldset><legend >&nbsp;</legend>
                      <?
											if ($ls_nomor_identitas=="")
											{
											?>
											<input id="tk_foto" name="tk_foto" alt="foto" type="image" align="center" src="../../images/nopic.png" style="height: 130px !important; width: 125px !important;"/>
                      <?
											}else
											{
											?>
											<img id="tk_foto" alt="foto" src="../../mod_kn/ajax/kngetfoto.php?dataid=<?=$ls_nomor_identitas;?>" style="height: 130px !important; width: 125px !important;"/>
                      <?
											}
											?>
											<div class="clear"></div>																				
										</fieldset>	
									</td>	
								</tr>
								
							

								<tr>
									<td>
										<fieldset style="height:200px;"><legend><i><font color="#009999">Ditransfer ke :</font></i>	 	    				
              						<? 
                          switch($ls_rg_metode_transfer)
                          {
                            case 'ATR' : $selATR="checked"; break;
                            case 'ATB' : $selATB="checked"; break;
                          }
                          ?>
              						<input type="radio" name="rg_metode_transfer" onchange="fl_js_val_rg_metode_transfer();" value="ATR" <?=$selATR;?>><font color="#009999"><b>Antar Rekening (dalam satu bank)</b></font>&nbsp; | &nbsp;
              						<input type="radio" name="rg_metode_transfer" onchange="fl_js_val_rg_metode_transfer();" value="ATB" <?=$selATB;?>><font color="#009999"><b>Antar Bank</b></font>
              						<input type="hidden" id="metode_transfer" name="metode_transfer" value="<?=$ls_metode_transfer;?>">
					          	</legend>	
											
											</br></br>
											
            					<div class="form-row_kiri">
                      <label  style = "text-align:right;">Cara Bayar*</label>
                          <input type="text" id="byr_nama_cara_bayar" name="byr_nama_cara_bayar" value="<?=$ls_nama_cara_bayar;?>" readonly class="disabled" style="width:293px;background-color:#ffff99;">
                          <input type="hidden" id="byr_kode_cara_bayar" name="byr_kode_cara_bayar" value="<?=$ls_kode_cara_bayar;?>">
                          <a class="a-icon-input" href="#" onclick="fl_js_lumpverif_get_lov_carabayar();">							
                            <img id="btn_lov_byr_cara_bayar" src="../../images/help.png" alt="Cari Cara Bayar" border="0" align="absmiddle">
                          </a>
                        </div>
                        <div class="clear"></div>	
                      <!--PENAMBAHAN KONDISI UNTUK TF LUAR NEGRI-->
                      <span id="byr_span_rekening" style="display:block;">						
                        <div class="form-row_kiri">
                       <label style = "text-align:right;">Bank *</label> 
											 	<input type="text" id="nama_bank_penerima" name="nama_bank_penerima" value="<?=$ls_nama_bank_penerima;?>" readonly style="width:293px;background-color:#ffff99;">
											  <input type="hidden" id="kode_bank_penerima" name="kode_bank_penerima" value="<?=$ls_kode_bank_penerima;?>"style="width:100px;">
												<input type="hidden" id="id_bank_penerima" name="id_bank_penerima" value="<?=$ls_id_bank_penerima;?>"style="width:100px;">
                  			<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lov_bankpenerima.php?p=pn5041_penetapanmanfaat_penerima.php&a=fpop&b=nama_bank_penerima&c=kode_bank_penerima&d=id_bank_penerima&e=metode_transfer','',800,500,1)">							
                        <img src="../../images/help.png" alt="Cari Bank" border="0" align="absmiddle"></a>				           											
												<!-- <input type="hidden" id="metode_transfer" name="metode_transfer" value="<?=$ls_metode_transfer;?>" maxlength="4" readonly class="disabled" style="width:20px;"> -->
                        </a>
                        <!-- <input type="hidden" id="kode_bank_penerima_old" name="kode_bank_penerima_old" value="<?=$ls_nama_bank_penerima;?>"> -->
                      </div>																																																	
                      <div class="clear"></div>	

                      <div class="form-row_kiri">
                      <label style = "text-align:right;">No Rekening *</label>
											 <input type="text" id="no_rekening_penerima" name="no_rekening_penerima" onblur="cekValidasiRekening();" value="<?=$ls_no_rekening_penerima;?>" tabindex="22" maxlength="30" style="width:100px;background-color:#ffff99;">
                       <!-- <input type="hidden" id="nama_rekening_penerima" name="nama_rekening_penerima" value="" maxlength="100" readonly class="disabled" style="width:120px;background-color:#F5F5F5"> -->
												<input type="hidden" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();">
                        <input type="text" id="nama_rekening_penerima" name="nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();">
												<input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" disabled class="cebox" onclick="copyNamaRekeningPenerima()" <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>><i><font color="#009999">Valid</font></i>
                        <!-- <input type="hidden" id="no_rekening_penerima_old" name="no_rekening_penerima_old" value="<?=$ls_no_rekening_penerima;?>"> -->
                        </div>
                        <div class="clear"></div>
                        </br>						                
                        <div class="form-row_kiri">
                          <label style = "text-align:right;">	    				
                          <img src="../../images/warning.gif" border="0" alt="Tambah" align="top" style="height:12px;"/></label>
                          <i><font color="#ff0000">Rekening harus valid untuk menghindari gagal transfer..!</font></i>
                        </div>																																																																																																																																																												
                        <div class="clear"></div>
                      </span>
                      <span id="byr_span_ln" style="display:none;">						
                        <div class="form-row_kiri">
                          <label style = "text-align:right;">Bank</label> 
                          <input type="text" id="ln_kode_bank_penerima" name="ln_kode_bank_penerima" value="<?=$ls_kode_bank_penerima;?>"style="width:80px;">
                          <input type="text" id="ln_nama_bank_penerima" name="ln_nama_bank_penerima" value="<?=$ls_nama_bank_penerima;?>" readonly style="width:240px;background-color:#F5F5F5">
                          <input type="hidden" id="ln_id_bank_penerima" name="ln_id_bank_penerima" value="<?=$ls_id_bank_penerima;?>"style="width:100px;">
                          <input type="hidden" id="ln_metode_transfer" name="ln_metode_transfer" value="<?=$ls_metode_transfer;?>" maxlength="4" readonly class="disabled" style="width:20px;">
                          <!-- <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lov_bankpenerima.php?p=pn5041_penetapanmanfaat_penerima.php&a=fpop&b=ln_nama_bank_penerima&c=kode_bank_penerima&d=id_bank_penerima&e=metode_transfer','',800,500,1)">							
                                  <img src="../../images/help.png" alt="Cari Bank" border="0" align="absmiddle">
                          </a>		 -->
                          <!-- <input type="hidden" id="ln_kode_bank_penerima_old" name="ln_kode_bank_penerima_old" value="<?=$ls_ln_nama_bank_penerima;?>"> -->
                        
                        
                        </div>																																																	
                        <div class="clear"></div>                   
                          <div class="form-row_kiri">
                            <label style = "text-align:right;">No Rekening</label>
                            <input type="text" id="ln_no_rekening_penerima" name="ln_no_rekening_penerima" onblur="cekValidasiRekening();" value="<?=$ls_no_rekening_penerima;?>" tabindex="22" maxlength="30" readonly class="disabled" style="width:100px;background-color:#ffff99;">
                            <!-- <input type="hidden" id="ln_nama_rekening_penerima" name="ln_nama_rekening_penerima" value="" maxlength="100" readonly class="disabled" style="width:120px;background-color:#F5F5F5"> -->
                            <input type="hidden" id="ln_nama_rekening_penerima_ws" name="ln_nama_rekening_penerima_ws" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();">
                            <input type="text" id="ln_nama_rekening_penerima" name="ln_nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();">
                            <input type="checkbox" id="ln_cb_valid_rekening" name="ln_cb_valid_rekening" disabled class="cebox" onclick="copyNamaRekeningPenerima()" <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>><i><font color="#009999">Valid</font></i>
                            <!-- <input type="hidden" id="ln_no_rekening_penerima_old" name="ln_no_rekening_penerima_old" value="<?=$ls_no_rekening_penerima;?>"> -->
                          </div>																																																																																															
                        <div class="clear"></div> 
                        </br> 						                
                        <div class="form-row_kiri">
                          <label style = "text-align:right;"> 	    				
                          <img src="../../images/infox.png" border="0" alt="Tambah" align="top" style="height:15px;"/></label> 
                          <i>Kode SWIFT dapat diakses melalui <a href="#" onclick="fl_js_lumpverif_get_www_swiftcode();"><font color="#ff0000">www.transfez.com/swift-codes</font></a></i>
                        </div>																																																																																																																																																												
                        <div class="clear"></div>
                      </span>			
                      </div>																																																																																															
                      <div class="clear"></div>
													
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">A/N *</label>
                  			<input type="text" id="nama_rekening_penerima2" name="nama_rekening_penerima2" value="<?=$ls_nama_rekening_penerima;?>" tabindex="23" maxlength="100" readonly class="disabled" style="width:270px;">
                      </div>																																																
                  		<div class="clear"></div>
											
        							<div class="form-row_kiri">
                      <label style = "text-align:right;">&nbsp;</label>	 	    				
                        <i><font color="#009999">Note : Rekening harus valid. </font></i>
                        <input type="hidden" id="status_valid_rekening_penerima" name="status_valid_rekening_penerima" value="<?=$ls_status_valid_rekening_penerima;?>">
                        <? $ls_status_valid_rekening_penerima = isset($ls_status_valid_rekening_penerima) ? $ls_status_valid_rekening_penerima : "T"; ?>					
                      </div>																																																																																																																																																												
                      <div class="clear"></div>
																																																							
										</fieldset>	
									</td>	
								</tr>

                <tr>
									<td>
										<fieldset style="height:60px;"><legend><i><span style="color:#009999;">Ditransfer dari :</span></i></legend>
                      <div class="form-row_kiri">
                      <label style = "text-align:right;">Bank *</label>
                        <select size="1" id="kode_bank_pembayar" name="kode_bank_pembayar" value="<?=$ls_kode_bank_pembayar;?>" tabindex="21" class="select_format" style="width:220px;background-color:#ffff99;" >
                        <option value="">-- Pilih --</option>	 	    				
                        <!-- <select size="1" id="kode_bank_pembayar" name="kode_bank_pembayar" value="<?=$ls_kode_bank_pembayar;?>" onchange="fl_js_val_kode_bank_pembayar();" tabindex="21" class="select_format" style="width:220px;background-color:#ffff99;" >
                        <option value="">-- Pilih --</option> -->
                        <? 
							// 						$sql = "select distinct a.kode_bank, b.nama_bank from ms.ms_rekening_detil a, ms.ms_bank b ".
              //                    "where a.kode_bank = b.kode_bank ".
              //                    "and a.tipe_rekening='36' ".
							// //	 and a.kode_bank = '020' ". //sementara batasi bank BNI, nanti diaktifkan lg bank yg lain
              //                    "and a.kode_kantor = 'ATP' ".
              //                    "order by a.kode_bank";	 
              //             $DB->parse($sql);
              //             $DB->execute();
              //             while($row = $DB->nextrow())
              //             {
              //               echo "<option ";
              //               if ($row["KODE_BANK"]==$ls_kode_bank_pembayar && strlen($ls_kode_bank_pembayar)==strlen($row["KODE_BANK"])){ echo " selected"; }
              //               echo " value=\"".$row["KODE_BANK"]."\">".$row["NAMA_BANK"]."</option>";
              //             }
                        ?>
                        </select>
												<input type="hidden" id="id_bank_opg" name="id_bank_opg" value="<?=$ls_id_bank_opg;?>">
												<input type="hidden" id="nama_bank_pembayar" name="nama_bank_pembayar" value="<?=$ls_nama_bank_pembayar;?>">
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
										</fieldset>	
									</td>	
								</tr>
										
							</table>	
						</td>					
					</tr>		 
				</table>	 
						
        <? 					
        if(!empty($ls_kode_tipe_penerima))
        {
        ?>			 	
          <div id="buttonbox" style="width:1020px;text-align:center;">       			 
          <?if ($ls_task == "Edit" || $ls_task == "PENETAPAN" || $ls_task == "PERSETUJUAN" || $ls_task == "PEMBAYARAN")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="fl_js_val_simpan();">
            <?
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
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
      
      let kodeBankPenerima = "<?php echo $ls_kode_bank_penerima ?>";
      getListBankAsal(kodeBankPenerima);
		});

    function fl_js_lumpverif_get_www_swiftcode()
    {
      window.open("https://www.transfez.com/swift-codes");
    }

    function cekCaraBayar(){
      var	v_kode_cara_bayar = window.document.getElementById('byr_kode_cara_bayar').value;

      if(v_kode_cara_bayar=='L'){
      window.document.getElementById("byr_span_rekening").style.display = 'none';	
			window.document.getElementById("byr_span_ln").style.display = 'block';
      window.document.getElementById("byr_span_rekening").style.display = 'none';	
				window.document.getElementById("byr_span_ln").style.display = 'block';

  			document.getElementById('ln_kode_bank_penerima').readOnly = false;
      	document.getElementById('ln_kode_bank_penerima').style.backgroundColor='#ffff99';
								
  			document.getElementById('ln_nama_bank_penerima').readOnly = false;
      	document.getElementById('ln_nama_bank_penerima').style.backgroundColor='#ffff99';
  			
  			document.getElementById('ln_no_rekening_penerima').readOnly = false;
      	document.getElementById('ln_no_rekening_penerima').style.backgroundColor='#ffff99';
				
  			// document.getElementById('ln_nama_rekening_penerima_ws').readOnly = false;
      	// document.getElementById('ln_nama_rekening_penerima_ws').style.backgroundColor='#ffff99';

        document.getElementById('ln_nama_rekening_penerima').readOnly = false;
      	document.getElementById('ln_nama_rekening_penerima').style.backgroundColor='#ffff99';
      }
				
    }

    cekCaraBayar();

    //PENAMBAHAN BARU UNTUK SENTRALISASI TRANSFER LUAR NEGRI
    function fl_js_lumpverif_get_lov_carabayar()
    {		 					
      //perubahan cara bayar bisa dilakukan hanya jika cara bayar sebelumnya kosong (masa transisi)
      //dan cara bayar yg akan tampil hanya transfer/spb 
      var v_is_sentralisasi	 = window.document.getElementById('cb_status_rekening_sentral').value;
      var v_is_sentralisasi_new	 = '<?php echo $ls_status_rekening_sentral; ?>';
      var v_jenis_klaim 		 = window.document.getElementById('byr_jenis_klaim').value; 
      //nom_manfaat diby pass
      //var	v_nom_manfaat 		 = removeCommas(window.document.getElementById('byr_nom_sisa').value); 
      var	v_nom_max_va_debit = 0;
      
      NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5063_lov_carabayar.php?p=pn5041_penetapanmanfaat_penerima.php&a=fpop&b=byr_kode_cara_bayar&c=byr_nama_cara_bayar&e=SIAP_BAYAR_LUMPVERIF&f='+v_jenis_klaim+'&g=&h='+v_nom_max_va_debit+'&j='+v_is_sentralisasi_new+'','',800,500,1);
    }

    function fl_js_lumpverif_span_carabayar_edit()
  {
    var	v_kode_cara_bayar = window.document.getElementById('byr_kode_cara_bayar').value;

		if (v_kode_cara_bayar !="")
		{
  		if (v_kode_cara_bayar =="V" || v_kode_cara_bayar =="TTK") //va debet -----
      {
  			//window.document.getElementById("byr_span_va_debit").style.display = 'block';
  			window.document.getElementById("byr_span_rekening").style.display = 'none';
				window.document.getElementById("byr_span_ln").style.display = 'none';
			}else if (v_kode_cara_bayar =="L") //transfer luar negeri ----------------
      {
  			//window.document.getElementById("byr_span_va_debit").style.display = 'none';
  			window.document.getElementById("byr_span_rekening").style.display = 'none';	
				window.document.getElementById("byr_span_ln").style.display = 'block';

  			document.getElementById('ln_kode_bank_penerima').readOnly = false;
      	document.getElementById('ln_kode_bank_penerima').style.backgroundColor='#ffff99';
								
  			document.getElementById('ln_nama_bank_penerima').readOnly = false;
      	document.getElementById('ln_nama_bank_penerima').style.backgroundColor='#ffff99';
  			
  			document.getElementById('ln_no_rekening_penerima').readOnly = false;
      	document.getElementById('ln_no_rekening_penerima').style.backgroundColor='#ffff99';
				
  			// document.getElementById('ln_nama_rekening_penerima_ws').readOnly = false;
      	// document.getElementById('ln_nama_rekening_penerima_ws').style.backgroundColor='#ffff99';

        document.getElementById('ln_nama_rekening_penerima').readOnly = false;
      	document.getElementById('ln_nama_rekening_penerima').style.backgroundColor='#ffff99';
				
  			document.getElementById('ln_kode_bank_penerima').placeholder = "Kode SWIFT";
  			document.getElementById('ln_nama_bank_penerima').placeholder = "Nama Bank Luar Negeri";
  			document.getElementById('ln_no_rekening_penerima').placeholder = "No. Rekening";
  			//document.getElementById('ln_nama_rekening_penerima_ws').placeholder = "A/N Rekening";
        document.getElementById('ln_nama_rekening_penerima').placeholder = "A/N Rekening";
				
				//reset bank dan no_rekening -------------------------------------------
				$('#kode_bank_penerima').val('');
				$('#nama_bank_penerima').val('');
				$('#ln_no_rekening_penerima').val('');
				$('#ln_nama_rekening_penerima_ws').val('');
				$('#ln_nama_rekening_penerima').val('');
  			$('#ln_metode_transfer').val('TT');

				$('#kode_bank_penerima').val('');
				$('#nama_bank_penerima').val('');
				$('#no_rekening_penerima').val('');
				$('#nama_rekening_penerima_ws').val('');
				$('#nama_rekening_penerima').val('');				
  			$('#metode_transfer').val('TT');
        
      }else{
        window.document.getElementById("byr_span_rekening").style.display = 'block';	
				window.document.getElementById("byr_span_ln").style.display = 'none';
      }
		}		
  }
  
		
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
								window.parent.Ext.notify.msg('Gagal get list bank...', jdata.ret);
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
			  //var bank_list 		= document.getElementById('list_bank_penerima');
				//var bank_selected = bank_list.options[bank_list.selectedIndex].value;
				var v_bank = $('#kode_bank_penerima').val();
				var v_norek = $('#no_rekening_penerima').val();
				
				//if(bank_selected!=''){
				if(v_bank!='' && v_norek!=''){
						$.ajax(
            {
              type: 'POST',
              url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerimaajax.php?'+Math.random(),
              data: { TYPE:'cek_validasi_rekening',NOREK:$('#no_rekening_penerima').val(),BANK:$('#kode_bank_penerima').val(),KODE_BANK:$('#kode_bank_penerima').val(),NAMA_BANK:$('#nama_bank_penerima').val() },
              success: function(data)
              {
                preload(false);
    						//console.log(data);
                jdata = JSON.parse(data);
    						//console.log(jdata);     
                if(jdata.ret=="0")
                {   
    								$('#nama_rekening_penerima_ws').val(jdata.data['NAMA_REK_TUJUAN']);
										window.document.getElementById('cb_valid_rekening').checked = true;
										$('#status_valid_rekening_penerima').val('Y');
										$('#nama_rekening_penerima').val(jdata.data['NAMA_REK_TUJUAN']);																								
                }else{
                    window.parent.Ext.notify.msg('Gagal validasi rekening...', jdata.msg);
										//nama_rekening dapat diinput manual ---------------------------------
										document.getElementById('nama_rekening_penerima_ws').readOnly = false;
          					document.getElementById('nama_rekening_penerima_ws').style.backgroundColor='#ffff99';
										$('#status_valid_rekening_penerima').val('T');
										$('#nama_rekening_penerima').val('');	
										$('#nama_rekening_penerima_ws').val('');	
										document.getElementById('nama_rekening_penerima_ws').placeholder = "-- isikan NAMA secara manual --";
										window.document.getElementById('cb_valid_rekening').checked = false;
                }
              }
            });
				}else
				{
				 	$('#nama_rekening_penerima_ws').val('');
					window.document.getElementById('cb_valid_rekening').checked = false;
					$('#status_valid_rekening_penerima').val('T');
					$('#nama_rekening_penerima').val(''); 
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

    function getListBankAsal(kodeBank = ''){
      let kodeBankPembayar = "<?php echo $ls_kode_bank_pembayar ?>";
      let kodeBankTujuan = "";
      if (kodeBank != ''){
        kodeBankTujuan = kodeBank;
      }else{
        kodeBankTujuan = kodeBankPembayar;
      }
			preload(true);
			$.ajax({
					type: 'POST',
					url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_penerimaajax.php?'+Math.random(),
					data: {
						TYPE:"getListBankAsal",
						kodeBankTujuan : kodeBankTujuan
					 },
					success: function(data){
							jdata = JSON.parse(data);
							var lov_bank_asal = document.getElementById('kode_bank_pembayar');
							lov_bank_asal.options.length = 1;
							if(jdata.ret == "0"){   
									for($i=0; $i<jdata.data.length;$i++){
											var option = document.createElement('option');
											option.text  = jdata.data[$i].NAMA_BANK;
											option.value = jdata.data[$i].KODE_BANK;
											option.setAttribute('kodebankpembayar',jdata.data[$i]['KODE_BANK_ATB']);
                        if(kodeBankPembayar != ''){
                        option.selected = true;
                        }
											lov_bank_asal.add(option); 
									}
									
									preload(false);
							}else{
									window.parent.Ext.notify.msg('Gagal get list bank...', jdata.msg);
							}
					}
					
			});
	  }
</script>