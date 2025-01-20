<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN5001 - DETIL INFORMASI PENERIMA MANFAAT BERKALA";

$ls_kode_tk                     = !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];
$ls_kode_klaim                  = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ln_no_urut_keluarga	 		      = !isset($_GET['no_urut_keluarga']) ? $_POST['no_urut_keluarga'] : $_GET['no_urut_keluarga'];
$ls_kode_tipe_penerima_berkala  = !isset($_GET['kode_tipe_penerima']) ? $_POST['kode_tipe_penerima'] : $_GET['kode_tipe_penerima'];
$ls_kode_penerima_berkala	      = !isset($_GET['kode_penerima_berkala']) ? $_POST['kode_penerima_berkala'] : $_GET['kode_penerima_berkala'];
$ls_sender_kode_penerima_berkala	= !isset($_GET['kode_penerima_berkala']) ? $_POST['sender_kode_penerima_berkala'] : $_GET['kode_penerima_berkala'];
$ls_root_sender 				 	      = !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 				      = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			      = !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						      = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 				 			 		      = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$btn_task 					 			      = !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];

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

if ($ls_kode_klaim!="")
{
  // $sql = "select status_klaim,kode_kantor from sijstk.pn_klaim ".
	// 		 	 "where kode_klaim = '$ls_kode_klaim' ";
  // $DB->parse($sql);
  // $DB->execute();
  // $row = $DB->nextrow();
	// $ls_kode_kantor = $row['KODE_KANTOR'];
	// $ls_status_klaim			= $row['STATUS_KLAIM'];
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
	
	//ambil kantor yg akan melakukan pembayaran ----------------------------------
  $qry = "BEGIN SIJSTK.P_PN_PN5040.X_GET_KANTORBAYAR('$ls_kode_klaim',:p_kantor_pembayar,:p_sukses,:p_mess);END;";											 	
  $proc = $DB->parse($qry);				
  OCIBindByName($proc, ":p_kantor_pembayar", $p_kantor_pembayar,32);
	OCIBindByName($proc, ":p_sukses", $p_sukses,32);
  OCIBindByName($proc, ":p_mess", $p_mess,1000);
  $DB->execute();				
  $ls_sukses = $p_sukses;
  $ls_mess = $p_mess;	
	$ls_kantor_pembayar = $p_kantor_pembayar;	
	if ($ls_kantor_pembayar=="")
	{
	 	 $ls_kantor_pembayar = $ls_kode_kantor;
	}

	if ($ls_task=="edit")
	{  	
    //cek apakah rekening pembayaran untuk kantor pembayar adala tersentral di kantor pusat -----------
    $sql = "select nvl(status_rekening_sentral,'T') as status_rekening_sentral from sijstk.ms_kantor ".
    		 	 "where kode_kantor = '$ls_kantor_pembayar'";
    $DB->parse($sql);
    $DB->execute();
    $data = $DB->nextrow();
    $ls_status_rekening_sentral	= $data["STATUS_REKENING_SENTRAL"];
    
		//TEMP: 
		//$ls_status_rekening_sentral = "Y";
		
    if ($ls_status_rekening_sentral=="Y")
    {
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      // echo "window.location.replace('../ajax/pn5041_penetapanmanfaat_penerima_jpn_berkala_sentralisasi.php?mid=$mid&task=Edit&kode_klaim=$ls_kode_klaim&kode_tipe_penerima=$ls_kode_tipe_penerima&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&sender_mid=$ls_sender_mid');";
      echo "window.location.replace('../ajax/pn5041_penetapanmanfaat_penerima_jpn_berkala_sentralisasi.php?mid=$mid&task=edit&kode_klaim=$ls_kode_klaim&kode_tipe_penerima=$ls_kode_tipe_penerima_berkala&kode_penerima_berkala=$ls_kode_penerima_berkala&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&sender_mid=$ls_sender_mid');";
      echo "</script>";	 		
    } 
	}else
	{
 		$sql = "select nvl(a.status_rekening_sentral,'T') status_rekening_sentral from sijstk.pn_klaim_penerima_manfaat a ".
            "where kode_klaim = '$ls_kode_klaim' ".
            "and kode_tipe_penerima = '$ls_kode_tipe_penerima'";
    $DB->parse($sql);
    $DB->execute();
    $data = $DB->nextrow();
    $ls_status_rekening_sentral		= $data["STATUS_REKENING_SENTRAL"];
    if ($ls_status_rekening_sentral=="Y")
    {
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.location.replace('../ajax/pn5041_penetapanmanfaat_penerima_jpn_berkala_sentralisasi.php?mid=$mid&task=edit&kode_klaim=$ls_kode_klaim&kode_tipe_penerima=$ls_kode_tipe_penerima_berkala&kode_penerima_berkala=$ls_kode_penerima_berkala&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&sender_mid=$ls_sender_mid');";
      echo "</script>";	 		
    }
	}

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
$ls_kode_bank_penerima		= $_POST['kode_bank_penerima'];
$ls_bank_penerima					= $_POST['nama_bank_penerima'];
$ls_no_rekening_penerima	= $_POST['no_rekening_penerima'];
$ls_nama_rekening_penerima	= str_replace("'"," ", $_POST['nama_rekening_penerima']); //$_POST['nama_rekening_penerima'];
$ls_status_valid_rekening_penerima = $_POST['status_valid_rekening_penerima']; 
$ls_kode_bank_pembayar		= $_POST['kode_bank_pembayar'];	

define('debug', false);	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title><?=$pagetitle;?></title>
  <meta name="Author" content="JroBalian" />
  <!-- <link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />
  <script type="text/javascript" language="JavaScript" src="../../javascript/iframe.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>

  <script type="text/javascript" src="../../javascript/calendar.js"></script> 
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">  -->

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
			}else if ((form.jenis_kelamin.value=="L" && form.kode_hubungan.value=="I") || (form.jenis_kelamin.value=="P" && form.kode_hubungan.value=="S"))
			{
        alert('Jenis Kelamin tidak sesuai dengan jenis hubungan keluarga...!!!');
        form.jenis_kelamin.focus();
			}else if (form.alamat.value==""){
        alert('Alamat tidak boleh kosong...!!!');
        form.alamat.focus();
			}else if (form.kode_pos.value==""){
        alert('Kode Pos tidak boleh kosong...!!!');
        form.kode_pos.focus();	
			}else if (form.npwp.value==""){
        alert('NPWP tidak boleh kosong, atau isikan angka 0 jika memang tidak ada...!!!');
        form.npwp.focus();
			}else if (form.nama_bank_penerima.value==""){
        alert('Bank Transfer tidak boleh kosong...!!!');
        form.nama_bank_penerima.focus();
			}else if (form.no_rekening_penerima.value==""){
        alert('No Rekening Penerima Transfer tidak boleh kosong...!!!');
        form.no_rekening_penerima.focus();	
			}else if (form.nama_rekening_penerima.value==""){
        alert('Nama Penerima Transfer tidak boleh kosong...!!!');
        form.nama_rekening_penerima.focus();	
			}else if (form.kode_bank_pembayar.value==""){
        alert('Rekening pembayaran klaim tidak boleh kosong...!!!');
        form.kode_bank_pembayar.focus();																																																											
      }else
  		{
         form.btn_task.value="simpan";
         form.submit(); 		 
  		}								 
  	}
    
    function cekValidasiRekening(){
		 		preload(true);
			  //var bank_list 		= document.getElementById('list_bank_penerima');
				//var bank_selected = bank_list.options[bank_list.selectedIndex].value;
        var v_bank = document.getElementById("kode_bank_penerima").value; //$("#kode_bank_penerima").val();
        var v_norek = document.getElementById("no_rekening_penerima").value; //$("#no_rekening_penerima").val();
				
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
      $sql = "update sijstk.pn_klaim_penerima_berkala  ".
					 	 	"set kode_hubungan			= '$ls_kode_hubungan', ".			 
              " 	 nama_lengkap				= '$ls_nama_lengkap', ". 
              "		 no_kartu_keluarga	= '$ls_no_kartu_keluarga', ".
              "		 nomor_identitas		= '$ls_nomor_identitas', ".  
              "		 tempat_lahir				= '$ls_tempat_lahir', ". 
              "    tgl_lahir					= to_date('$ld_tgl_lahir','dd/mm/yyyy'), ". 
              "		 jenis_kelamin			= '$ls_jenis_kelamin', ". 
              "		 golongan_darah		= '$ls_golongan_darah', ". 
              "    alamat						= '$ls_alamat', ". 
              "		 rt								= '$ls_rt', ". 
              "		 rw								= '$ls_rw', ". 
              "		 kode_kelurahan		= '$ls_kode_kelurahan', ". 
              "		 kode_kecamatan		= '$ls_kode_kecamatan', ". 
              "		 kode_kabupaten		= '$ls_kode_kabupaten', ". 
              "		 kode_pos					= '$ls_kode_pos', ". 
              "    telepon_area			= '$ls_telepon_area', ". 
              "		 telepon					= '$ls_telepon', ". 
              "		 telepon_ext			= '$ls_telepon_ext', ". 
              "		 handphone				= '$ls_handphone', ". 
              "		 email						= '$ls_email', ". 
              "		 npwp							= '$ls_npwp', ". 
              "		 keterangan				= '$ls_keterangan', ". 
              "    kpj_tertanggung	= '$ls_kpj_tertanggung', ".
							"    nama_penerima		= '$ls_nama_lengkap', ".
							"    bank_penerima		= '$ls_bank_penerima', ".
              "    kode_bank_penerima = '$ls_kode_bank_penerima', ".
							"    no_rekening_penerima			= '$ls_no_rekening_penerima', ".
							"    nama_rekening_penerima		= '$ls_nama_rekening_penerima', ".
              "    status_valid_rekening_penerima		= '$ls_status_valid_rekening_penerima', ". 
							"    kode_bank_pembayar				= '$ls_kode_bank_pembayar', ". 
              "    tgl_ubah					= sysdate, ". 
              "		 petugas_ubah			= '$username' ". 
              "where kode_klaim = '$ls_kode_klaim' ".
              "and kode_penerima_berkala = '$ls_kode_penerima_berkala' ";
      $DB->parse($sql);
      $DB->execute();	
      //echo $sql;		 

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
      $msg = "Data Penerima Manfaat Berkala berhasil tersimpan, session dilanjutkan...";
      $task = "edit";	
      $ls_hiddenid = $ls_kode_penerima_berkala;
      $editid = $ls_kode_penerima_berkala;		
			
  		echo "<script language=\"JavaScript\" type=\"text/javascript\">";						
			echo "window.location.replace('?&task=edit&kode_klaim=$ls_kode_klaim&kode_penerima_berkala=$ls_sender_kode_penerima_berkala&root_sender=$ls_root_sender&sender=$ls_sender&sender_activetab=$ls_sender_activetab&sender_mid=$ls_sender_mid&msg=$msg');";
			// echo "refreshParent();";
  		echo "</script>";					            
	} //end if(isset($_POST['simpan']))
		
	?>	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">				
    <?
    if ($ls_kode_klaim !="" && $ls_kode_penerima_berkala !="")
    {
			$sql = "select 
                  a.kode_klaim, a.kode_penerima_berkala, a.no_urut_keluarga, a.kode_hubungan, a.nama_lengkap, a.no_kartu_keluarga, a.nomor_identitas, 
									a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, a.jenis_kelamin, a.golongan_darah, 
									a.alamat, a.rt, a.rw, 
									a.kode_kelurahan, (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan) nama_kelurahan, 
									a.kode_kecamatan, (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan, 
									a.kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten, 
									a.kode_pos, a.telepon_area, a.telepon, a.telepon_ext, a.fax_area, a.fax, a.handphone, 
                  a.email, a.npwp, a.kpj_tertanggung, a.pekerjaan, a.kode_kondisi_terakhir, a.tgl_kondisi_terakhir, a.keterangan,
									a.kode_bank_penerima, a.bank_penerima, a.no_rekening_penerima, a.nama_rekening_penerima, a.kode_bank_pembayar,
                  a.status_rekening_sentral, a.kantor_rekening_sentral, a.status_valid_rekening_penerima
              from sijstk.pn_klaim_penerima_berkala a
              where a.kode_klaim = '$ls_kode_klaim'
							and a.kode_penerima_berkala = '$ls_kode_penerima_berkala'
              and nvl(a.status_layak,'T') = 'Y'
              and rownum = 1 ";

      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
			$ls_kode_hubungan			 	  = $data["KODE_HUBUNGAN"];
			$ls_kode_penerima_berkala	= $data["KODE_PENERIMA_BERKALA"];	
			$ln_no_urut_keluarga	 		= $data["NO_URUT_KELUARGA"];
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
			$ls_kode_bank_penerima		= $data['KODE_BANK_PENERIMA'];	  
      $ls_bank_penerima					= $data['BANK_PENERIMA'];
      $ls_no_rekening_penerima	= $data['NO_REKENING_PENERIMA'];
      $ls_nama_rekening_penerima	= $data['NAMA_REKENING_PENERIMA']; 
      $ls_kode_bank_pembayar		= $data['KODE_BANK_PEMBAYAR'];				
      $ls_status_valid_rekening_penerima = $data['STATUS_VALID_REKENING_PENERIMA'];											
    }	
		?>
		
  	<!-- VALIDASI AJAX -------------------------------------------------------->    
  	<!-- end VALIDASI AJAX ---------------------------------------------------->	
			
		<div id="formframe" style="width:900px;">
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
			<input type="hidden" id="va" name="va" value="<?=$ls_va;?>">
			<input type="hidden" id="vb" name="vb" value="<?=$ls_vb;?>">
			<input type="hidden" id="vc" name="vc" value="<?=$ls_vc;?>">
			<input type="hidden" id="vd" name="vd" value="<?=$ls_vd;?>"> 
		 
      <div id="formKiri" style="width:1100px;">
        <table width="1050px" border="0"> 
									 
          <tr>
            <td width="50%" valign="top">
              <fieldset style="height:420px;"><legend ><b><i><font color="#009999">Detil Informasi Penerima Manfaat</font></i></b></legend>
                <div class="form-row_kiri">
                <label  style = "text-align:right;">No Urut &nbsp;</label>
                  <input type="text" id="no_urut_penerima" name="no_urut_penerima" value="<?=$ln_no_urut_keluarga;?>" size="33" readonly class="disabled" >
                  <input type="hidden" id="kode_penerima_berkala" name="kode_penerima_berkala" value="<?=$ls_kode_penerima_berkala;?>" >
                  <input type="hidden" id="golongan_darah" name="golongan_darah" value="<?=$ls_golongan_darah;?>" >                					
                </div>																																															
                <div class="clear"></div>	 

                <div class="form-row_kiri">
                <label  style = "text-align:right;">No KK &nbsp;</label>
                  <input type="text" id="no_kartu_keluarga" name="no_kartu_keluarga" value="<?=$ls_no_kartu_keluarga;?>" size="33" tabindex="21" maxlength="30" >                					
                                                                         
                </div>																																																			
                <div class="clear"></div>	

                <div class="form-row_kiri">
                <label style = "text-align:right;">No. Identitas </label>
                  <input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" size="33" maxlength="30" tabindex="26">
                  <input type="hidden" id="kpj_tertanggung" name="kpj_tertanggung" value="<?=$ls_kpj_tertanggung;?>" size="33" maxlength="11" tabindex="27">
                </div>																																																																																							
                <div class="clear"></div>
                
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
                <div class="clear"></div>

                    
                            
                <div class="form-row_kiri">
                <label style = "text-align:right;">Nama Lengkap *</label>
                  <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$ls_nama_lengkap;?>" tabindex="2" size="42" maxlength="100" style="background-color:#ffff99;">
                </div>
                                  

                <div class="form-row_kiri">
                <label style = "text-align:right;">Tempat, Tgl Lahir *</label>
                  <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" size="22" maxlength="50" tabindex="4" style="background-color:#ffff99;">,
        
                  <input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" size="12" maxlength="10" tabindex="5" onblur="convert_date(tgl_lahir);" style="background-color:#ffff99;">
                  <input id="btn_tgl_lahir" type="image" align="top" onclick="return showCalendar('tgl_lahir', 'dd-mm-y');" src="../../images/calendar.gif" />							
                    
    
                  
                                      
                                                                                        
                                                                                                                                                                  
                                                                
                </div>
                

                <div class="form-row_kiri">
                <label style = "text-align:right;">Jenis Kelamin *</label>
                  <select size="1" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>" tabindex="5" class="select_format" style="width:250px;background-color:#ffff99;" >
                  <option value="">-- Pilih --</option>
                  <? 
                    if ($ls_kode_penerima_berkala=="I")
                    {
                      $ls_filter_kelamin = "and kode = 'P' "; 
                    }elseif ($ls_kode_penerima_berkala=="S")
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
                </div>																																																																																					
                <div class="clear"></div> 
                </br>
                                    
                <div class="form-row_kiri">
                <label style = "text-align:right;">Alamat *</label> 
                  <input type="text" id="alamat" name="alamat" value="<?=$ls_alamat;?>" tabindex="9" size="42" maxlength="300" style="background-color:#ffff99;">	
                </div>	                
                <div class="clear"></div>

                <div class="form-row_kiri">
                <label style = "text-align:right;">RT/RW</label>		 	    				
                  <input type="text" id="rt" name="rt" value="<?=$ls_rt;?>" tabindex="10" size="12" maxlength="5" onblur="fl_js_val_numeric('rt');">  
                  /
                  <input type="text" id="rw" name="rw" value="<?=$ls_rw;?>" tabindex="11" size="25" maxlength="5" onblur="fl_js_val_numeric('rw');">		
                </div>																																																	
                <div class="clear"></div>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">Kode Pos *</label> 	    				
                  <input type="text" id="kode_pos" name="kode_pos" value="<?=$ls_kode_pos;?>" tabindex="12" size="38" maxlength="10" readonly  style="background-color:#ffff99;">
                  <a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_lov_pos.php?p=pn5040_agenda_jpn_manfaatberkalapenerima.php&a=fpop&b=kode_kelurahan&c=nama_kelurahan&d=kode_kecamatan&e=nama_kecamatan&f=kode_kabupaten&g=nama_kabupaten&h=kode_propinsi&j=nama_propinsi&k=kode_pos','',800,500,1)">							
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
                </br>
                
                <div class="form-row_kiri">
                <label style = "text-align:right;">No. Telp</label>	    				
                  <input type="text" id="telepon_area" name="telepon_area" tabindex="13" value="<?=$ls_telepon_area;?>" size="4" maxlength="5" onblur="fl_js_val_numeric('telepon_area');">
                  <input type="text" id="telepon" name="telepon" tabindex="14" value="<?=$ls_telepon;?>" size="18" maxlength="20" onblur="fl_js_val_numeric('telepon');">
                  &nbsp;ext.
                  <input type="text" id="telepon_ext" name="telepon_ext" tabindex="15" value="<?=$ls_telepon_ext;?>" size="4" maxlength="5" onblur="fl_js_val_numeric('telepon_ext');"> 						
                </div> 
                <div class="form-row_kiri">
                <label style = "text-align:right;">Handphone &nbsp;</label>		 	    				
                  <input type="text" id="handphone" name="handphone" tabindex="16" value="<?=$ls_handphone;?>" size="39" maxlength="15">
                </div> 
                <div class="form-row_kiri">
                <label style = "text-align:right;">Email &nbsp;&nbsp;&nbsp;&nbsp;</label>		 	    				
                  <input type="text" id="email" name="email" tabindex="17" value="<?=$ls_email;?>" size="39" maxlength="200">
                </div> 
                <div class="form-row_kiri">
                <label style = "text-align:right;">NPWP &nbsp;</label>		 	    				
                  <input type="text" id="npwp" name="npwp" tabindex="18" value="<?=$ls_npwp;?>" size="37" maxlength="30" style="background-color:#ffff99;">
                </div> 
                <div class="form-row_kiri">
                <label style = "text-align:right;">Keterangan &nbsp;</label>
                  <textarea cols="255" rows="1" style="width:250px" id="keterangan" name="keterangan" tabindex="19" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
                </div> 

                </br>						
                </fieldset> 
              </td>

              <td width="50%" valign="top">
              <table width="550px" border="0">
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
                          <input type="text" id="nama_bank_penerima" name="nama_bank_penerima" value="<?=$ls_bank_penerima;?>" readonly style="width:293px;background-color:#ffff99;">
                          <input type="hidden" id="kode_bank_penerima" name="kode_bank_penerima" value="<?=$ls_kode_bank_penerima;?>"style="width:100px;">
                          <input type="hidden" id="id_bank_penerima" name="id_bank_penerima" value="<?=$ls_id_bank_penerima;?>"style="width:100px;">
                          <!--<a style="display:block;" id="lov_bank_penerima" href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lov_bankpenerima.php?p=pn5041_penetapanmanfaat_penerima.php&a=fpop&b=nama_bank_penerima&c=kode_bank_penerima&d=id_bank_penerima&e=metode_transfer','',800,500,1)">
                          -->
                          <a id="lov_bank_penerima" href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_lov_bankpenerima.php?p=pn5041_penetapanmanfaat_penerima.php&a=fpop&b=nama_bank_penerima&c=kode_bank_penerima&d=id_bank_penerima&e=metode_transfer','',800,500,1)">							
                          <img src="../../images/help.png" alt="Cari Bank" border="0" align="absmiddle"></a>				           											
                          <input type="hidden" id="metode_transfer" name="metode_transfer" value="<?=$ls_metode_transfer;?>" maxlength="4" readonly class="disabled" style="width:20px;">
                        </div>																																																	
                        <div class="clear"></div>	
                                
                        <!--																		
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">Bank *</label>
                          <select onchange="setAkunBank()" size="1" id="list_bank_penerima" name="list_bank_penerima" value="<?=$ls_list_bank_penerima;?>" tabindex="24" class="select_format" style="width:300px;background-color:#ffff99;" >
                          <option value="">-- Pilih --</option>
                          </select>                      
                        </div>																																																																
                        <div class="clear"></div>				
                        -->
                                            
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">No Rekening *</label>
                        <input type="text" id="no_rekening_penerima" name="no_rekening_penerima" onblur="cekValidasiRekening();" value="<?=$ls_no_rekening_penerima;?>" tabindex="22" maxlength="30" style="width:100px;background-color:#ffff99;">
                          <input type="text" id="nama_rekening_penerima_ws" name="nama_rekening_penerima_ws" maxlength="100" style="width:185px;" readonly class="disabled" placeholder="-- validasi rekening bank --" onblur="this.value=this.value.toUpperCase();">
                          <input type="checkbox" id="cb_valid_rekening" name="cb_valid_rekening" class="cebox" onclick="copyNamaRekeningPenerima()" <?=$ls_status_valid_rekening_penerima=="Y" ||$ls_status_valid_rekening_penerima=="ON" ||$ls_status_valid_rekening_penerima=="on" ? "checked" : "";?>><i><font color="#009999">Valid</font></i>	
                        </div>																																																																																															
                        <div class="clear"></div>
                    
                        <div class="form-row_kiri">
                        <label style = "text-align:right;">A/N *</label>
                          <input type="text" id="nama_rekening_penerima" name="nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" tabindex="23" maxlength="100" readonly class="disabled" style="width:270px;">
                        </div>																																																
                        <div class="clear"></div>

                        <div class="form-row_kiri">
                        <label style = "text-align:right;">&nbsp;</label>	 	    				
                          <i><font color="#009999">Note : Tickmark Valid jika NO REKENING sudah sesuai. </font></i>
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
                                    "and a.tipe_rekening='36' and a.kode_bank = '020' ". //sementara batasi bank BNI, nanti diaktifkan lg bank yg lain
                                    "and a.kode_kantor = 'ATP' ".
                                    "order by a.kode_bank";
                            }else
                            {
                              if ($ls_kode_pointer_asal=="SPO")
                              {
                                $sql = "select a.kode_bank,b.nama_bank from sijstk.monitor_klaim_jspo a, ms.ms_bank b ". 
                                      "where a.kode_bank = b.kode_bank ".
                                      "and a.no_agenda = '$ls_kode_klaim' ";													
                              }else
                              {
                                $sql = "select distinct a.kode_bank, c.nama_bank ".
                                      "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ". 
                                      "where a.kode_kantor = b.kode_kantor(+) ".  
                                      "and a.kode_bank = b.kode_bank(+) ".  
                                      "and a.kode_rekening = b.kode_rekening(+) ".  
                                      "and a.kode_buku = b.kode_buku(+) ".  
                                      "and a.kode_bank = c.kode_bank ".   
                                      "and a.kode_kantor = '$ls_kantor_pembayar' ". 
                                      "and nvl(a.aktif,'T')='Y' ".
                                      "and b.tipe_rekening in ('13','14','15','16','17') ". 
                                      "order by a.kode_bank";
                              }			 													
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
        if(!empty($ls_kode_klaim))
        {
        ?>			 	
          <div id="buttonbox" style="width:100%;text-align:center;">       			 
          <?if ($ls_task == "edit" || $ls_task == "new")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="if(confirm('Apakah anda yakin akan menyimpan data..?')) fl_js_val_simpan();">
						<?
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />       					
          </div>	
          
        <? 					
        }
        ?>	
				
        <?
        if (isset($msg) || $msg!=="")		
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
 