<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Entry Rincian ";

$ls_form_penetapan	 	 		= !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];

$ls_kode_klaim	 		 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	 	 			= !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ln_no_urut	 	 						= !isset($_GET['no_urut']) ? $_POST['no_urut'] : $_GET['no_urut'];
$ls_kategori_manfaat			= $_POST['kategori_manfaat'];
$ls_kd_prg								= $_POST['kd_prg'];	
$ls_kode_perlindungan			= $_POST['kode_perlindungan'];
$ls_kode_segmen						= $_POST['kode_segmen'];

//ambil segmen kepesertaan -----------------------------------------------------
if ($ls_kode_klaim!="")
{
  $sql = "select kode_segmen from sijstk.pn_klaim a ".
         "where a.kode_klaim = '$ls_kode_klaim' ";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_kode_segmen	= $row['KODE_SEGMEN'];
}
//end ambil segmen kepesertaan -------------------------------------------------
	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat, a.kategori_manfaat, b.kd_prg, c.kode_perlindungan, c.kode_segmen ".
			 	 "from sijstk.pn_kode_manfaat a, sijstk.pn_klaim_manfaat b, sijstk.pn_klaim c ".
         "where a.kode_manfaat = b.kode_manfaat and b.kode_klaim = c.kode_klaim ".
				 "and b.kode_klaim = '$ls_kode_klaim' ". 
				 "and b.kode_manfaat = '$ls_kode_manfaat'";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat			= $row['NAMA_MANFAAT'];
	$ls_kategori_manfaat  = $row['KATEGORI_MANFAAT'];
	$ls_kd_prg						= $row['KD_PRG'];	
	$ls_kode_perlindungan	= $row['KODE_PERLINDUNGAN'];	
	$ls_kode_segmen				= $row['KODE_SEGMEN'];
	
 	$gs_pagetitle = "PN5002 - RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}
		
$ls_kode_tipe_penerima							= $_POST['kode_tipe_penerima'];
$ls_transport_tipe_negara						= $_POST['transport_tipe_negara'];

$ln_transport_darat_diajukan	 	 		= str_replace(',','',$_POST['transport_darat_diajukan']);
$ln_transport_darat_diajukan_rs	 		= str_replace(',','',$_POST['transport_darat_diajukan_rs']);
$ln_transport_darat_diajukan_rjk 		= str_replace(',','',$_POST['transport_darat_diajukan_rjk']);
$ln_transport_darat_diajukan_rtw 		= str_replace(',','',$_POST['transport_darat_diajukan_rtw']);

$ln_transport_laut_diajukan		 			= str_replace(',','',$_POST['transport_laut_diajukan']);
$ln_transport_laut_diajukan_rs		 	= str_replace(',','',$_POST['transport_laut_diajukan_rs']);
$ln_transport_laut_diajukan_rjk		 	= str_replace(',','',$_POST['transport_laut_diajukan_rjk']);
$ln_transport_laut_diajukan_rtw		 	= str_replace(',','',$_POST['transport_laut_diajukan_rtw']);

$ln_transport_udara_diajukan 	 			= str_replace(',','',$_POST['transport_udara_diajukan']);
$ln_transport_udara_diajukan_rs 	 	= str_replace(',','',$_POST['transport_udara_diajukan_rs']);
$ln_transport_udara_diajukan_rjk 	 	= str_replace(',','',$_POST['transport_udara_diajukan_rjk']);
$ln_transport_udara_diajukan_rtw 	 	= str_replace(',','',$_POST['transport_udara_diajukan_rtw']);

$ln_transport_darat_verifikasi 			= str_replace(',','',$_POST['transport_darat_verifikasi']);
$ln_transport_darat_verifikasi_rs	 	= str_replace(',','',$_POST['transport_darat_verifikasi_rs']);
$ln_transport_darat_verifikasi_rjk 	= str_replace(',','',$_POST['transport_darat_verifikasi_rjk']);
$ln_transport_darat_verifikasi_rtw 	= str_replace(',','',$_POST['transport_darat_verifikasi_rtw']);

$ln_transport_laut_verifikasi	 			= str_replace(',','',$_POST['transport_laut_verifikasi']);
$ln_transport_laut_verifikasi_rs	 	= str_replace(',','',$_POST['transport_laut_verifikasi_rs']);
$ln_transport_laut_verifikasi_rjk 	= str_replace(',','',$_POST['transport_laut_verifikasi_rjk']);
$ln_transport_laut_verifikasi_rtw 	= str_replace(',','',$_POST['transport_laut_verifikasi_rtw']);

$ln_transport_udara_verifikasi 			= str_replace(',','',$_POST['transport_udara_verifikasi']);
$ln_transport_udara_verifikasi_rs	 	= str_replace(',','',$_POST['transport_udara_verifikasi_rs']);
$ln_transport_udara_verifikasi_rjk 	= str_replace(',','',$_POST['transport_udara_verifikasi_rjk']);
$ln_transport_udara_verifikasi_rtw 	= str_replace(',','',$_POST['transport_udara_verifikasi_rtw']);
			
$ln_transport_darat_disetujui 			= str_replace(',','',$_POST['transport_darat_disetujui']);		 			
$ln_transport_laut_disetujui 				= str_replace(',','',$_POST['transport_laut_disetujui']);
$ln_transport_udara_disetujui 			= str_replace(',','',$_POST['transport_udara_disetujui']);
$ls_keterangan											= $_POST['keterangan'];

if ($ln_transport_darat_diajukan==""){$ln_transport_darat_diajukan=0;}
if ($ln_transport_darat_verifikasi==""){$ln_transport_darat_verifikasi=0;}
if ($ln_transport_darat_disetujui==""){$ln_transport_darat_disetujui=0;}

if ($ln_transport_laut_diajukan==""){$ln_transport_laut_diajukan=0;}
if ($ln_transport_laut_verifikasi==""){$ln_transport_laut_verifikasi=0;}
if ($ln_transport_laut_disetujui==""){$ln_transport_laut_disetujui=0;}

if ($ln_transport_udara_diajukan==""){$ln_transport_udara_diajukan=0;}
if ($ln_transport_udara_verifikasi==""){$ln_transport_udara_verifikasi=0;}
if ($ln_transport_udara_disetujui==""){$ln_transport_udara_disetujui=0;}											

$ln_nom_biaya_diajukan 	= ((float)$ln_transport_darat_diajukan+(float)$ln_transport_laut_diajukan+(float)$ln_transport_udara_diajukan);
$ln_nom_biaya_disetujui = ((float)$ln_transport_darat_disetujui+(float)$ln_transport_laut_disetujui+(float)$ln_transport_udara_disetujui);
	
if ($ls_kategori_manfaat=="TAMBAHAN")
{
 	 $ln_nom_manfaat_utama = 0;
	 $ln_nom_manfaat_tambahan = $ln_nom_biaya_disetujui;
}else
{
 	 $ln_nom_manfaat_utama = $ln_nom_biaya_disetujui;
	 $ln_nom_manfaat_tambahan = 0;
}
$ln_nom_manfaat_gross = ((float)$ln_nom_manfaat_utama+(float)$ln_nom_manfaat_tambahan);
$ln_nom_pph 					= 0;
$ln_nom_pembulatan 		= 0;
$ln_nom_manfaat_netto	= $ln_nom_manfaat_gross;
							 				
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
    <?php	
    if($ls_sender!=''){			
    	echo "window.location.replace('$ls_sender?task=edit&kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid ');";		
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
  	function fl_js_val_simpan()
  	{
      var form = document.fpop;

		 	
			v_transport_darat_diajukan_rs = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan_rs').value),2);
			v_transport_darat_diajukan_rjk = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan_rjk').value),2);
			v_transport_darat_diajukan_rtw = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan_rtw').value),2);
		
			v_transport_laut_diajukan_rs = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan_rs').value),2);
			v_transport_laut_diajukan_rjk = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan_rjk').value),2);
			v_transport_laut_diajukan_rtw = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan_rtw').value),2);
			
			v_transport_udara_diajukan_rs = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan_rs').value),2);
			v_transport_udara_diajukan_rjk = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan_rjk').value),2);
			v_transport_udara_diajukan_rtw = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan_rtw').value),2);


			v_transport_darat_verifikasi_rs = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi_rs').value),2);
			v_transport_darat_verifikasi_rjk = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi_rjk').value),2);
			v_transport_darat_verifikasi_rtw = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi_rtw').value),2);
			
			v_transport_laut_verifikasi_rs = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi_rs').value),2);
			v_transport_laut_verifikasi_rjk = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi_rjk').value),2);
			v_transport_laut_verifikasi_rtw = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi_rtw').value),2);
		
			v_transport_udara_verifikasi_rs = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi_rs').value),2);
			v_transport_udara_verifikasi_rjk = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi_rjk').value),2);
			v_transport_udara_verifikasi_rtw = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi_rtw').value),2);
						

      	if(form.kode_tipe_penerima.value==""){
        	alert('Tipe Penerima tidak boleh kosong...!!!');
        	form.kode_tipe_penerima.focus();
		}else if(form.kode_segmen.value=="TKI" && form.transport_tipe_negara.value==""){
			alert('Untuk PMI maka negara penggunaan transportasi tidak boleh kosong...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_darat_verifikasi_rs > v_transport_darat_diajukan_rs){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_darat_verifikasi_rjk > v_transport_darat_diajukan_rjk){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_darat_verifikasi_rtw > v_transport_darat_diajukan_rtw){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_laut_verifikasi_rs > v_transport_laut_diajukan_rs){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_laut_verifikasi_rjk > v_transport_laut_diajukan_rjk){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_laut_verifikasi_rtw > v_transport_laut_diajukan_rtw){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_udara_verifikasi_rs > v_transport_udara_diajukan_rs){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_udara_verifikasi_rjk > v_transport_udara_diajukan_rjk){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else if(v_transport_udara_verifikasi_rtw > v_transport_udara_diajukan_rtw){
			alert('Biaya hasil verifikasi tidak boleh lebih besar dari biaya diajukan...!!!');
			form.transport_tipe_negara.focus();				
      	}else
  		{
         	form.tombol.value="simpan";
         	form.submit(); 		 
  		}								 
  	}
		//update 23/01/2019 tambahan terkait TKI -----------------------------------
    function fl_js_transport_tipe_negara() 
    { 
    	var v_kode_segmen = window.document.getElementById("kode_segmen").value;
    	
    	if (v_kode_segmen =="TKI")
      {    
    		window.document.getElementById("span_transport_tipe_negara").style.display = 'block';
      }else
      {
    	 	window.document.getElementById("span_transport_tipe_negara").style.display = 'none';	 
    	  window.document.getElementById("transport_tipe_negara").value = "I";
      }
    }					
  </script>														
</head>
<body>
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	
	<?
	if(isset($_POST['tombol'])=="simpan")
	{         				
  		if ($ls_task=="new")
  		{				
				// Cek apakah data sukses tersimpan ?
        $sql2 = "select nvl(max(no_urut),0)+1 as v_no from sijstk.pn_klaim_manfaat_detil ".
								"where kode_klaim = '$ls_kode_klaim' ".
								"and kode_manfaat = '$ls_kode_manfaat' ";
        $DB->parse($sql2);
        $DB->execute();
        $row = $DB->nextrow();
        $ln_no_urut = $row["V_NO"];
									
    		$sql = "insert into sijstk.pn_klaim_manfaat_detil ( ".
               "	kode_klaim, kode_manfaat, no_urut, kode_manfaat_detil, kategori_manfaat, kode_tipe_penerima, kd_prg, ". 
							 "	transport_darat_diajukan_rs, transport_darat_diajukan_rjk, transport_darat_diajukan_rtw, ". 
							 "	transport_laut_diajukan_rs, transport_laut_diajukan_rjk, transport_laut_diajukan_rtw, ".
							 "	transport_udara_diajukan_rs, transport_udara_diajukan_rjk, transport_udara_diajukan_rtw,  ". 
               "	transport_darat_verifikasi_rs, transport_darat_verifikasi_rjk, transport_darat_verifikasi_rtw, ". 
							 "	transport_laut_verifikasi_rs, transport_laut_verifikasi_rjk, transport_laut_verifikasi_rtw, ".
							 "	transport_udara_verifikasi_rs, transport_udara_verifikasi_rjk, transport_udara_verifikasi_rtw,  ".
							 "	transport_darat_diajukan, transport_laut_diajukan, transport_udara_diajukan, ". 
               "	transport_darat_verifikasi, transport_laut_verifikasi, transport_udara_verifikasi, ". 
               "	transport_darat_disetujui, transport_laut_disetujui, transport_udara_disetujui, ".
							 "	nom_biaya_diajukan, nom_biaya_disetujui, ".
							 "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ".						 
               "	keterangan, tgl_rekam, petugas_rekam, transport_tipe_negara)   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut',null,'$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
                "	'$ln_transport_darat_diajukan_rs', '$ln_transport_darat_diajukan_rjk', '$ln_transport_darat_diajukan_rtw', ". 
                "	'$ln_transport_laut_diajukan_rs', '$ln_transport_laut_diajukan_rjk', '$ln_transport_laut_diajukan_rtw', ".
                "	'$ln_transport_udara_diajukan_rs', '$ln_transport_udara_diajukan_rjk', '$ln_transport_udara_diajukan_rtw',  ". 
                "	'$ln_transport_darat_verifikasi_rs', '$ln_transport_darat_verifikasi_rjk', '$ln_transport_darat_verifikasi_rtw', ". 
                "	'$ln_transport_laut_verifikasi_rs', '$ln_transport_laut_verifikasi_rjk', '$ln_transport_laut_verifikasi_rtw', ".
                "	'$ln_transport_udara_verifikasi_rs', '$ln_transport_udara_verifikasi_rjk', '$ln_transport_udara_verifikasi_rtw',  ". 
							 "	'$ln_transport_darat_diajukan', '$ln_transport_laut_diajukan', '$ln_transport_udara_diajukan', ". 
               "	'$ln_transport_darat_verifikasi', '$ln_transport_laut_verifikasi', '$ln_transport_udara_verifikasi', ". 
               "	'$ln_transport_darat_disetujui', '$ln_transport_laut_disetujui', '$ln_transport_udara_disetujui', ". 
							 "	'$ln_nom_biaya_diajukan', '$ln_nom_biaya_disetujui', ".
							 "	'$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', '$ln_nom_manfaat_gross', ". 
							 "  '$ln_nom_pph', '$ln_nom_pembulatan', '$ln_nom_manfaat_netto', ".
							 "	'$ls_keterangan', sysdate, '$username', '$ls_transport_tipe_negara' ".
  						 ")";			 
        $DB->parse($sql);
        $DB->execute();	
				
        // Cek apakah data sukses tersimpan ?
        $sql2 = "select no_urut from sijstk.pn_klaim_manfaat_detil ".
  					 	  "where kode_klaim = '$ls_kode_klaim' ".
								"and kode_manfaat = '$ls_kode_manfaat' ";
								"and no_urut = '$ln_no_urut' ";
        $DB->parse($sql2);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_exist = $row["NO_URUT"];
      
        if ($ls_exist!="") //Data berhasil disimpan
        {		    
          //post insert ----------------------------------------------------------
          $qry = "BEGIN SIJSTK.P_PN_PN5040.X_REFRESH_NILAI_MANFAAT ('$ls_kode_klaim', '$username',:p_sukses,:p_mess);END;";											 	
          $proc = $DB->parse($qry);				
          oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
					oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
          $DB->execute();				
          $ls_mess = $p_mess;	
          									
          $msg = "Data Rincian Manfaat berhasil tersimpan, session dilanjutkan...";
          $task = "edit";	
          $ls_hiddenid = $ln_no_urut;
          $editid = $ln_no_urut;						
          echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  				echo "refreshParent();";
          echo "</script>";															 
        }else
        {
          $ls_error = "1" ;
          $ln_no_urut = "";
          $msg = $sql."(* Data gagal disimpan, cek data input....!!!";					
        }//end if ($ls_exist!="")	
  							
  		}else if ($ls_task=="edit")
  		{
    		$sql = "update sijstk.pn_klaim_manfaat_detil set ".
               "	kategori_manfaat 	 							= '$ls_kategori_manfaat', ".
							 "	kode_tipe_penerima 	 						= '$ls_kode_tipe_penerima', ".
							 "	kd_prg						 	 						= '$ls_kd_prg', ". 
							 "	transport_darat_diajukan_rs 		= '$ln_transport_darat_diajukan_rs', ".
							 "	transport_darat_diajukan_rjk 		= '$ln_transport_darat_diajukan_rjk', ".
							 "	transport_darat_diajukan_rtw 		= '$ln_transport_darat_diajukan_rtw', ". 
							 "	transport_laut_diajukan_rs 			= '$ln_transport_laut_diajukan_rs', ".
							 "	transport_laut_diajukan_rjk 		= '$ln_transport_laut_diajukan_rjk', ".
							 "	transport_laut_diajukan_rtw 		= '$ln_transport_laut_diajukan_rtw', ".
							 "	transport_udara_diajukan_rs 		= '$ln_transport_udara_diajukan_rs', ".
							 "	transport_udara_diajukan_rjk 		= '$ln_transport_udara_diajukan_rjk', ".
							 "	transport_udara_diajukan_rtw 		= '$ln_transport_udara_diajukan_rtw',  ". 
               "	transport_darat_verifikasi_rs 	= '$ln_transport_darat_verifikasi_rs', ".
							 "	transport_darat_verifikasi_rjk 	= '$ln_transport_darat_verifikasi_rjk', ".
							 "	transport_darat_verifikasi_rtw 	= '$ln_transport_darat_verifikasi_rtw', ". 
							 "	transport_laut_verifikasi_rs 		= '$ln_transport_laut_verifikasi_rs', ".
							 "	transport_laut_verifikasi_rjk 	= '$ln_transport_laut_verifikasi_rjk', ".
							 "	transport_laut_verifikasi_rtw 	= '$ln_transport_laut_verifikasi_rtw', ".
							 "	transport_udara_verifikasi_rs 	= '$ln_transport_udara_verifikasi_rs', ".
							 "	transport_udara_verifikasi_rjk 	= '$ln_transport_udara_verifikasi_rjk', ".
							 "	transport_udara_verifikasi_rtw 	= '$ln_transport_udara_verifikasi_rtw',  ".
							 "	transport_darat_diajukan				= '$ln_transport_darat_diajukan', ". 
							 "	transport_laut_diajukan					= '$ln_transport_laut_diajukan', ". 
							 "	transport_udara_diajukan				= '$ln_transport_udara_diajukan', ". 
               "	transport_darat_verifikasi			= '$ln_transport_darat_verifikasi', ". 
							 "	transport_laut_verifikasi				= '$ln_transport_laut_verifikasi', ".
							 "	transport_udara_verifikasi			= '$ln_transport_udara_verifikasi', ". 
               "	transport_darat_disetujui				= '$ln_transport_darat_disetujui', ". 
							 "	transport_laut_disetujui				= '$ln_transport_laut_disetujui', ".
							 "	transport_udara_disetujui				= '$ln_transport_udara_disetujui', ".
							 "	nom_biaya_diajukan							= '$ln_nom_biaya_diajukan', ". 
							 "	nom_biaya_disetujui							= '$ln_nom_biaya_disetujui', ".
							 "	nom_manfaat_utama								= '$ln_nom_manfaat_utama', ". 
							 "	nom_manfaat_tambahan						= '$ln_nom_manfaat_tambahan', ". 
							 "	nom_manfaat_gross								= '$ln_nom_manfaat_gross', ". 
							 "  nom_pph													= '$ln_nom_pph', ". 
							 "	nom_pembulatan									= '$ln_nom_pembulatan', ". 
							 "	nom_manfaat_netto								= '$ln_nom_manfaat_netto', ".					 
               "	keterangan											= '$ls_keterangan', ". 
							 "	tgl_ubah												= sysdate, ". 
							 "	petugas_ubah										= '$username', ".
							 "	transport_tipe_negara						= '$ls_transport_tipe_negara' ".
							 "where kode_klaim = '$ls_kode_klaim' ".
							 "and kode_manfaat = '$ls_kode_manfaat' ".
							 "and no_urut = '$ln_no_urut' ";
        $DB->parse($sql);
        $DB->execute();								
						
        //post update ----------------------------------------------------------
        $qry = "BEGIN SIJSTK.P_PN_PN5040.X_REFRESH_NILAI_MANFAAT ('$ls_kode_klaim', '$username',:p_sukses,:p_mess);END;";												 	
        $proc = $DB->parse($qry);				
        oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
        oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
        $DB->execute();				
        $ls_mess = $p_mess;	
  							
        $msg = "Data Rincian Manfaat berhasil tersimpan, session dilanjutkan...";
        $task = "edit";	
        $ls_hiddenid = $ls_kd_prg;
        $editid = $ls_kd_prg;						
        echo "<script language=\"JavaScript\" type=\"text/javascript\">";
        echo "refreshParent();";
        echo "</script>";							
  		}		            
	} //end if(isset($_POST['simpan'])) 
	?>	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
    <?
    if ($ls_kode_manfaat !="" && $ln_no_urut !="")
    {
      $sql = "select 
                  kode_klaim, kode_manfaat, no_urut, kode_manfaat_detil, kategori_manfaat, 
									kode_tipe_penerima, kd_prg, nom_biaya_diajukan, nom_biaya_disetujui, 
									transport_darat_diajukan_rs, transport_darat_diajukan_rjk, transport_darat_diajukan_rtw, 
									transport_laut_diajukan_rs, transport_laut_diajukan_rjk, transport_laut_diajukan_rtw,  
									transport_udara_diajukan_rs, transport_udara_diajukan_rjk, transport_udara_diajukan_rtw, 
									transport_darat_verifikasi_rs, transport_darat_verifikasi_rjk, transport_darat_verifikasi_rtw, 
									transport_laut_verifikasi_rs, transport_laut_verifikasi_rjk, transport_laut_verifikasi_rtw,  
									transport_udara_verifikasi_rs, transport_udara_verifikasi_rjk, transport_udara_verifikasi_rtw,
									transport_darat_diajukan, transport_laut_diajukan, transport_udara_diajukan, 
                  transport_darat_verifikasi, transport_laut_verifikasi, transport_udara_verifikasi, 
									transport_darat_disetujui, transport_laut_disetujui, transport_udara_disetujui, 									
									nvl(transport_darat_diajukan,0)+nvl(transport_laut_diajukan,0)+nvl(transport_udara_diajukan,0) biaya_total_diajukan,
									nvl(transport_darat_verifikasi,0)+nvl(transport_laut_verifikasi,0)+nvl(transport_udara_verifikasi,0) biaya_total_verifikasi,
									nvl(transport_darat_disetujui,0)+nvl(transport_laut_disetujui,0)+nvl(transport_udara_disetujui,0) biaya_total_disetujui,
									nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
                  nom_pph, nom_pembulatan, nom_manfaat_netto, keterangan, transport_tipe_negara
              from sijstk.pn_klaim_manfaat_detil
              where kode_klaim = '$ls_kode_klaim'
              and kode_manfaat = '$ls_kode_manfaat'
              and no_urut = '$ln_no_urut' ";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
      $ls_kode_klaim											= $data["KODE_KLAIM"];			
      $ls_kode_manfaat										= $data["KODE_MANFAAT"];
      $ln_no_urut													= $data["NO_URUT"];
      $ls_kode_manfaat_detil							= $data["KODE_MANFAAT_DETIL"];
      $ls_kategori_manfaat								= $data["KATEGORI_MANFAAT"];
      $ls_kode_tipe_penerima							= $data["KODE_TIPE_PENERIMA"];
      $ls_kd_prg													= $data["KD_PRG"];
      $ln_nom_biaya_diajukan							= $data["NOM_BIAYA_DIAJUKAN"];
      $ln_nom_biaya_disetujui 						= $data["NOM_BIAYA_DISETUJUI"];
      $ln_transport_darat_diajukan	 	 		= $data["TRANSPORT_DARAT_DIAJUKAN"];
			$ln_transport_darat_diajukan_rs	 		= $data["TRANSPORT_DARAT_DIAJUKAN_RS"];
			$ln_transport_darat_diajukan_rjk 		= $data["TRANSPORT_DARAT_DIAJUKAN_RJK"];
			$ln_transport_darat_diajukan_rtw 		= $data["TRANSPORT_DARAT_DIAJUKAN_RTW"];
			
      $ln_transport_laut_diajukan		 			= $data["TRANSPORT_LAUT_DIAJUKAN"];
			$ln_transport_laut_diajukan_rs		 	= $data["TRANSPORT_LAUT_DIAJUKAN_RS"];
			$ln_transport_laut_diajukan_rjk		 	= $data["TRANSPORT_LAUT_DIAJUKAN_RJK"];
			$ln_transport_laut_diajukan_rtw		 	= $data["TRANSPORT_LAUT_DIAJUKAN_RTW"];
			
      $ln_transport_udara_diajukan 	 			= $data["TRANSPORT_UDARA_DIAJUKAN"];
			$ln_transport_udara_diajukan_rs 	 	= $data["TRANSPORT_UDARA_DIAJUKAN_RS"];
			$ln_transport_udara_diajukan_rjk 	 	= $data["TRANSPORT_UDARA_DIAJUKAN_RJK"];
			$ln_transport_udara_diajukan_rtw 	 	= $data["TRANSPORT_UDARA_DIAJUKAN_RTW"];
			
      $ln_transport_darat_verifikasi 			= $data["TRANSPORT_DARAT_VERIFIKASI"];
			$ln_transport_darat_verifikasi_rs	 	= $data["TRANSPORT_DARAT_VERIFIKASI_RS"];
			$ln_transport_darat_verifikasi_rjk 	= $data["TRANSPORT_DARAT_VERIFIKASI_RJK"];
			$ln_transport_darat_verifikasi_rtw 	= $data["TRANSPORT_DARAT_VERIFIKASI_RTW"];
			
      $ln_transport_laut_verifikasi	 			= $data["TRANSPORT_LAUT_VERIFIKASI"];
			$ln_transport_laut_verifikasi_rs	 	= $data["TRANSPORT_LAUT_VERIFIKASI_RS"];
			$ln_transport_laut_verifikasi_rjk 	= $data["TRANSPORT_LAUT_VERIFIKASI_RJK"];
			$ln_transport_laut_verifikasi_rtw 	= $data["TRANSPORT_LAUT_VERIFIKASI_RTW"];
			
      $ln_transport_udara_verifikasi 			= $data["TRANSPORT_UDARA_VERIFIKASI"];
			$ln_transport_udara_verifikasi_rs	 	= $data["TRANSPORT_UDARA_VERIFIKASI_RS"];
			$ln_transport_udara_verifikasi_rjk 	= $data["TRANSPORT_UDARA_VERIFIKASI_RJK"];
			$ln_transport_udara_verifikasi_rtw 	= $data["TRANSPORT_UDARA_VERIFIKASI_RTW"];
			
      $ln_transport_darat_disetujui	 			= $data["TRANSPORT_DARAT_DISETUJUI"];
      $ln_transport_laut_disetujui	 			= $data["TRANSPORT_LAUT_DISETUJUI"];
      $ln_transport_udara_disetujui	 			= $data["TRANSPORT_UDARA_DISETUJUI"];
      $ln_nom_manfaat_utama								= $data["NOM_MANFAAT_UTAMA"];
      $ln_nom_manfaat_tambahan						= $data["NOM_MANFAAT_TAMBAHAN"];
      $ln_nom_manfaat_gross 							= $data["NOM_MANFAAT_GROSS"];
      $ln_nom_pph													= $data["NOM_PPH"];
      $ln_nom_pembulatan									= $data["NOM_PEMBULATAN"];
      $ln_nom_manfaat_netto								= $data["NOM_MANFAAT_NETTO"];
      $ls_keterangan											= $data["KETERANGAN"];				
			$ln_biaya_total_diajukan						= $data["BIAYA_TOTAL_DIAJUKAN"];
			$ln_biaya_total_verifikasi					= $data["BIAYA_TOTAL_VERIFIKASI"];
			$ln_biaya_total_disetujui						= $data["BIAYA_TOTAL_DISETUJUI"];
			$ls_transport_tipe_negara						= $data["TRANSPORT_TIPE_NEGARA"];																		
    }	
		?>
		
  	<!-- VALIDASI AJAX ---------------------------------------------------------->
    <script type="text/javascript" src="../../javascript/validator.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    
    <script type="text/javascript">
    //Create validator object
    var validator = new formValidator();
    var ajax = new sack();
    //ambil nilai previous, dibandingkan dg nilai current, apabila berbeda maka ajax akan dijalankan
		var curr_transport_darat_diajukan_rs =<?php echo ($ln_transport_darat_diajukan_rs=='') ? 'false' : "'".$ln_transport_darat_diajukan_rs."'"; ?>;
		var curr_transport_darat_diajukan_rjk =<?php echo ($ln_transport_darat_diajukan_rjk=='') ? 'false' : "'".$ln_transport_darat_diajukan_rjk."'"; ?>;
		var curr_transport_darat_diajukan_rtw =<?php echo ($ln_transport_darat_diajukan_rtw=='') ? 'false' : "'".$ln_transport_darat_diajukan_rtw."'"; ?>;
		
		var curr_transport_laut_diajukan_rs =<?php echo ($ln_transport_laut_diajukan_rs=='') ? 'false' : "'".$ln_transport_laut_diajukan_rs."'"; ?>;
		var curr_transport_laut_diajukan_rjk =<?php echo ($ln_transport_laut_diajukan_rjk=='') ? 'false' : "'".$ln_transport_laut_diajukan_rjk."'"; ?>;
		var curr_transport_laut_diajukan_rtw =<?php echo ($ln_transport_laut_diajukan_rtw=='') ? 'false' : "'".$ln_transport_laut_diajukan_rtw."'"; ?>;
		
		var curr_transport_udara_diajukan_rs =<?php echo ($ln_transport_udara_diajukan_rs=='') ? 'false' : "'".$ln_transport_udara_diajukan_rs."'"; ?>;
		var curr_transport_udara_diajukan_rjk =<?php echo ($ln_transport_udara_diajukan_rjk=='') ? 'false' : "'".$ln_transport_udara_diajukan_rjk."'"; ?>;
		var curr_transport_udara_diajukan_rtw =<?php echo ($ln_transport_udara_diajukan_rtw=='') ? 'false' : "'".$ln_transport_udara_diajukan_rtw."'"; ?>;
		
    var curr_transport_darat_diajukan =<?php echo ($ln_transport_darat_diajukan=='') ? 'false' : "'".$ln_transport_darat_diajukan."'"; ?>;
    var curr_transport_laut_diajukan  =<?php echo ($ln_transport_laut_diajukan=='') ? 'false' : "'".$ln_transport_laut_diajukan."'"; ?>;
		var curr_transport_udara_diajukan =<?php echo ($ln_transport_udara_diajukan=='') ? 'false' : "'".$ln_transport_udara_diajukan."'"; ?>;
				
    var curr_transport_darat_verifikasi =<?php echo ($ln_transport_darat_verifikasi=='') ? 'false' : "'".$ln_transport_darat_verifikasi."'"; ?>;
    var curr_transport_laut_verifikasi  =<?php echo ($ln_transport_laut_verifikasi=='') ? 'false' : "'".$ln_transport_laut_verifikasi."'"; ?>;
		var curr_transport_udara_verifikasi =<?php echo ($ln_transport_udara_verifikasi=='') ? 'false' : "'".$ln_transport_udara_verifikasi."'"; ?>;
    
		//set initial value untuk biaya darat hasil verifikasi ---------------------
		function f_ajax_val_transport_darat_diajukan()
		{
		 	v_transport_darat_diajukan = removeCommas(window.document.getElementById('transport_darat_diajukan').value);
			
			if (v_transport_darat_diajukan != curr_transport_darat_diajukan)
			{
			 	document.fpop.transport_darat_verifikasi.value = format_uang(v_transport_darat_diajukan);
				curr_transport_darat_diajukan = v_transport_darat_diajukan; 
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
		
		//update 18/12/2019 pp82 ---------------------------------------------------
		function f_ajax_val_transport_darat_diajukan_rs()
		{
		 	v_transport_darat_diajukan_rs = removeCommas(window.document.getElementById('transport_darat_diajukan_rs').value);
			
			if (v_transport_darat_diajukan_rs != curr_transport_darat_diajukan_rs)
			{
			 	document.fpop.transport_darat_verifikasi_rs.value = format_uang(v_transport_darat_diajukan_rs);
				curr_transport_darat_diajukan_rs = v_transport_darat_diajukan_rs;  
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}

		function f_ajax_val_transport_darat_diajukan_rjk()
		{
		 	v_transport_darat_diajukan_rjk = removeCommas(window.document.getElementById('transport_darat_diajukan_rjk').value);
			
			if (v_transport_darat_diajukan_rjk != curr_transport_darat_diajukan_rjk)
			{
			 	document.fpop.transport_darat_verifikasi_rjk.value = format_uang(v_transport_darat_diajukan_rjk);
				curr_transport_darat_diajukan_rjk = v_transport_darat_diajukan_rjk; 
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
		
		function f_ajax_val_transport_darat_diajukan_rtw()
		{
		 	v_transport_darat_diajukan_rtw = removeCommas(window.document.getElementById('transport_darat_diajukan_rtw').value);
			
			if (v_transport_darat_diajukan_rtw != curr_transport_darat_diajukan_rtw)
			{
			 	document.fpop.transport_darat_verifikasi_rtw.value = format_uang(v_transport_darat_diajukan_rtw);
				curr_transport_darat_diajukan_rtw = v_transport_darat_diajukan_rtw; 
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
						
		//set initial value untuk biaya laut hasil verifikasi ---------------------
		function f_ajax_val_transport_laut_diajukan()
		{
		 	v_transport_laut_diajukan = removeCommas(window.document.getElementById('transport_laut_diajukan').value);
			
			if (v_transport_laut_diajukan != curr_transport_laut_diajukan)
			{
			 	document.fpop.transport_laut_verifikasi.value = format_uang(v_transport_laut_diajukan); 
				curr_transport_laut_diajukan = v_transport_laut_diajukan;
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
		
		function f_ajax_val_transport_laut_diajukan_rs()
		{
		 	v_transport_laut_diajukan_rs = removeCommas(window.document.getElementById('transport_laut_diajukan_rs').value);
			
			if (v_transport_laut_diajukan_rs != curr_transport_laut_diajukan_rs)
			{
			 	document.fpop.transport_laut_verifikasi_rs.value = format_uang(v_transport_laut_diajukan_rs);
				curr_transport_laut_diajukan_rs = v_transport_laut_diajukan_rs; 
				f_ajax_val_hitung_total_biaya();  
			}		 
		}

		function f_ajax_val_transport_laut_diajukan_rjk()
		{
		 	v_transport_laut_diajukan_rjk = removeCommas(window.document.getElementById('transport_laut_diajukan_rjk').value);
			
			if (v_transport_laut_diajukan_rjk != curr_transport_laut_diajukan_rjk)
			{
			 	document.fpop.transport_laut_verifikasi_rjk.value = format_uang(v_transport_laut_diajukan_rjk);
				curr_transport_laut_diajukan_rjk = v_transport_laut_diajukan_rjk; 
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
		
		function f_ajax_val_transport_laut_diajukan_rtw()
		{
		 	v_transport_laut_diajukan_rtw = removeCommas(window.document.getElementById('transport_laut_diajukan_rtw').value);
			
			if (v_transport_laut_diajukan_rtw != curr_transport_laut_diajukan_rtw)
			{
			 	document.fpop.transport_laut_verifikasi_rtw.value = format_uang(v_transport_laut_diajukan_rtw);
				curr_transport_laut_diajukan_rtw = v_transport_laut_diajukan_rtw; 
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
		
		//set initial value untuk biaya udara hasil verifikasi ---------------------
		function f_ajax_val_transport_udara_diajukan()
		{
		 	v_transport_udara_diajukan = removeCommas(window.document.getElementById('transport_udara_diajukan').value);
			
			if (v_transport_udara_diajukan != curr_transport_udara_diajukan)
			{
			 	document.fpop.transport_udara_verifikasi.value = format_uang(v_transport_udara_diajukan); 
				curr_transport_udara_diajukan = v_transport_udara_diajukan;				
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}

		function f_ajax_val_transport_udara_diajukan_rs()
		{
		 	v_transport_udara_diajukan_rs = removeCommas(window.document.getElementById('transport_udara_diajukan_rs').value);
			
			if (v_transport_udara_diajukan_rs != curr_transport_udara_diajukan_rs)
			{
			 	document.fpop.transport_udara_verifikasi_rs.value = format_uang(v_transport_udara_diajukan_rs);
				curr_transport_udara_diajukan_rs = v_transport_udara_diajukan_rs; 
				f_ajax_val_hitung_total_biaya();  
			}		 
		}

		function f_ajax_val_transport_udara_diajukan_rjk()
		{
		 	v_transport_udara_diajukan_rjk = removeCommas(window.document.getElementById('transport_udara_diajukan_rjk').value);
			
			if (v_transport_udara_diajukan_rjk != curr_transport_udara_diajukan_rjk)
			{
			 	document.fpop.transport_udara_verifikasi_rjk.value = format_uang(v_transport_udara_diajukan_rjk);
				curr_transport_udara_diajukan_rjk = v_transport_udara_diajukan_rjk; 
				f_ajax_val_hitung_total_biaya(); 
			}		 
		}
		
		function f_ajax_val_transport_udara_diajukan_rtw()
		{
		 	v_transport_udara_diajukan_rtw = removeCommas(window.document.getElementById('transport_udara_diajukan_rtw').value);
			
			if (v_transport_udara_diajukan_rtw != curr_transport_udara_diajukan_rtw)
			{
			 	document.fpop.transport_udara_verifikasi_rtw.value = format_uang(v_transport_udara_diajukan_rtw);
				curr_transport_udara_diajukan_rtw = v_transport_udara_diajukan_rtw; 
				f_ajax_val_hitung_total_biaya();  
			}		 
		}
				
		//hitung total biaya -------------------------------------------------------
		function f_ajax_val_hitung_total_biaya()
		{
		 	//hitung total biaya darat diajukan --------------------------------------
			v_transport_darat_diajukan_rs = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan_rs').value),2);
			v_transport_darat_diajukan_rjk = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan_rjk').value),2);
			v_transport_darat_diajukan_rtw = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan_rtw').value),2);
			
			if (v_transport_darat_diajukan_rs==""){v_transport_darat_diajukan_rs=0;}
			if (v_transport_darat_diajukan_rjk==""){v_transport_darat_diajukan_rjk=0;}
			if (v_transport_darat_diajukan_rtw==""){v_transport_darat_diajukan_rtw=0;}
			
			v_transport_darat_diajukan = (v_transport_darat_diajukan_rs+v_transport_darat_diajukan_rjk+v_transport_darat_diajukan_rtw);
			document.getElementById('transport_darat_diajukan').value = format_uang(v_transport_darat_diajukan);
			
			//hitung total biaya laut diajukan ---------------------------------------
			v_transport_laut_diajukan_rs = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan_rs').value),2);
			v_transport_laut_diajukan_rjk = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan_rjk').value),2);
			v_transport_laut_diajukan_rtw = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan_rtw').value),2);
			
			if (v_transport_laut_diajukan_rs==""){v_transport_laut_diajukan_rs=0;}
			if (v_transport_laut_diajukan_rjk==""){v_transport_laut_diajukan_rjk=0;}
			if (v_transport_laut_diajukan_rtw==""){v_transport_laut_diajukan_rtw=0;}
			
			v_transport_laut_diajukan = (v_transport_laut_diajukan_rs+v_transport_laut_diajukan_rjk+v_transport_laut_diajukan_rtw);
			document.getElementById('transport_laut_diajukan').value = format_uang(v_transport_laut_diajukan);
			
			//hitung total biaya udara diajukan --------------------------------------
			v_transport_udara_diajukan_rs = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan_rs').value),2);
			v_transport_udara_diajukan_rjk = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan_rjk').value),2);
			v_transport_udara_diajukan_rtw = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan_rtw').value),2);
			
			if (v_transport_udara_diajukan_rs==""){v_transport_udara_diajukan_rs=0;}
			if (v_transport_udara_diajukan_rjk==""){v_transport_udara_diajukan_rjk=0;}
			if (v_transport_udara_diajukan_rtw==""){v_transport_udara_diajukan_rtw=0;}
			
			v_transport_udara_diajukan = (v_transport_udara_diajukan_rs+v_transport_udara_diajukan_rjk+v_transport_udara_diajukan_rtw);
			document.getElementById('transport_udara_diajukan').value = format_uang(v_transport_udara_diajukan);
			
			//v_transport_darat_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan').value),2);
			//v_transport_laut_diajukan 	= parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan').value),2);
			//v_transport_udara_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan').value),2);
			
			//hitung total biaya darat verifikasi --------------------------------------
			v_transport_darat_verifikasi_rs = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi_rs').value),2);
			v_transport_darat_verifikasi_rjk = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi_rjk').value),2);
			v_transport_darat_verifikasi_rtw = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi_rtw').value),2);
			
			if (v_transport_darat_verifikasi_rs==""){v_transport_darat_verifikasi_rs=0;}
			if (v_transport_darat_verifikasi_rjk==""){v_transport_darat_verifikasi_rjk=0;}
			if (v_transport_darat_verifikasi_rtw==""){v_transport_darat_verifikasi_rtw=0;}
			
			v_transport_darat_verifikasi = (v_transport_darat_verifikasi_rs+v_transport_darat_verifikasi_rjk+v_transport_darat_verifikasi_rtw);
			document.getElementById('transport_darat_verifikasi').value = format_uang(v_transport_darat_verifikasi);
			
			//hitung total biaya laut verifikasi ---------------------------------------
			v_transport_laut_verifikasi_rs = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi_rs').value),2);
			v_transport_laut_verifikasi_rjk = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi_rjk').value),2);
			v_transport_laut_verifikasi_rtw = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi_rtw').value),2);
			
			if (v_transport_laut_verifikasi_rs==""){v_transport_laut_verifikasi_rs=0;}
			if (v_transport_laut_verifikasi_rjk==""){v_transport_laut_verifikasi_rjk=0;}
			if (v_transport_laut_verifikasi_rtw==""){v_transport_laut_verifikasi_rtw=0;}
			
			v_transport_laut_verifikasi = (v_transport_laut_verifikasi_rs+v_transport_laut_verifikasi_rjk+v_transport_laut_verifikasi_rtw);
			document.getElementById('transport_laut_verifikasi').value = format_uang(v_transport_laut_verifikasi);
			
			//hitung total biaya udara verifikasi --------------------------------------
			v_transport_udara_verifikasi_rs = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi_rs').value),2);
			v_transport_udara_verifikasi_rjk = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi_rjk').value),2);
			v_transport_udara_verifikasi_rtw = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi_rtw').value),2);
			
			if (v_transport_udara_verifikasi_rs==""){v_transport_udara_verifikasi_rs=0;}
			if (v_transport_udara_verifikasi_rjk==""){v_transport_udara_verifikasi_rjk=0;}
			if (v_transport_udara_verifikasi_rtw==""){v_transport_udara_verifikasi_rtw=0;}
			
			v_transport_udara_verifikasi = (v_transport_udara_verifikasi_rs+v_transport_udara_verifikasi_rjk+v_transport_udara_verifikasi_rtw);
			document.getElementById('transport_udara_verifikasi').value = format_uang(v_transport_udara_verifikasi);
			
		 	//v_transport_darat_verifikasi = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi').value),2);
			//v_transport_laut_verifikasi 	= parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi').value),2);
			//v_transport_udara_verifikasi = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi').value),2);
			
		 	v_transport_darat_disetujui = parseFloat(removeCommas(window.document.getElementById('transport_darat_disetujui').value),2);
			v_transport_laut_disetujui  = parseFloat(removeCommas(window.document.getElementById('transport_laut_disetujui').value),2);
			v_transport_udara_disetujui = parseFloat(removeCommas(window.document.getElementById('transport_udara_disetujui').value),2);									
			
			if (v_transport_darat_diajukan==""){v_transport_darat_diajukan=0;}
			if (v_transport_laut_diajukan==""){v_transport_laut_diajukan=0;}
			if (v_transport_udara_diajukan==""){v_transport_udara_diajukan=0;}
			
			if (v_transport_darat_verifikasi==""){v_transport_darat_verifikasi=0;}
			if (v_transport_laut_verifikasi==""){v_transport_laut_verifikasi=0;}
			if (v_transport_udara_verifikasi==""){v_transport_udara_verifikasi=0;}

			if (v_transport_darat_disetujui==""){v_transport_darat_disetujui=0;}
			if (v_transport_laut_disetujui==""){v_transport_laut_disetujui=0;}
			if (v_transport_udara_disetujui==""){v_transport_udara_disetujui=0;}
						 
			v_biaya_total_diajukan 	 = v_transport_darat_diajukan + v_transport_laut_diajukan + v_transport_udara_diajukan;
			v_biaya_total_verifikasi = v_transport_darat_verifikasi + v_transport_laut_verifikasi + v_transport_udara_verifikasi;
			v_biaya_total_disetujui  = v_transport_darat_disetujui + v_transport_laut_disetujui + v_transport_udara_disetujui;
			
			document.fpop.biaya_total_diajukan.value   = format_uang(v_biaya_total_diajukan);  
			document.fpop.biaya_total_verifikasi.value = format_uang(v_biaya_total_verifikasi);
			document.fpop.biaya_total_disetujui.value  = format_uang(v_biaya_total_disetujui);	
			
			f_ajax_val_hitung_manfaat(); 	 
		}				
								
    //hitung nilai manfaat -----------------------------------------------------
    function f_ajax_val_hitung_manfaat()
    {
		 	c_biaya_darat = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi').value),2);
			c_biaya_laut 	= parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi').value),2);
			c_biaya_udara = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi').value),2);     
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			c_kode_segmen 			= window.document.getElementById('kode_segmen').value;
			c_transport_tipe_negara = window.document.getElementById('transport_tipe_negara').value;
			c_kode_perlindungan = window.document.getElementById('kode_perlindungan').value;
			
			if (c_kode_segmen=='TKI' && ((c_kode_perlindungan=="ONSITE" && c_transport_tipe_negara=='I') || (c_kode_perlindungan!="ONSITE" && c_transport_tipe_negara!='I')) )
			{
				 document.getElementById('transport_darat_diajukan').value = 0;
				 document.getElementById('transport_laut_diajukan').value = 0;
				 document.getElementById('transport_udara_diajukan').value = 0;
				 document.getElementById('biaya_total_diajukan').value = 0;
				 
				 document.getElementById('transport_darat_verifikasi').value = 0;
				 document.getElementById('transport_laut_verifikasi').value = 0;
				 document.getElementById('transport_udara_verifikasi').value = 0;
				 document.getElementById('biaya_total_verifikasi').value = 0;
				 
			 	 document.getElementById('transport_darat_disetujui').value = 0;
				 document.getElementById('transport_laut_disetujui').value = 0;
				 document.getElementById('transport_udara_disetujui').value = 0;
				 document.getElementById('biaya_total_disetujui').value = 0;
			}else
			{
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_transportasi&c_biaya_darat='+c_biaya_darat+'&c_biaya_laut='+c_biaya_laut+'&c_biaya_udara='+c_biaya_udara+'&c_biaya_udara='+c_biaya_udara+'&c_kode_klaim='+c_kode_klaim+'&c_no_urut='+c_no_urut;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_transport_darat_verifikasi = c_biaya_darat;
  			curr_transport_laut_verifikasi = c_biaya_laut;
  			curr_transport_udara_verifikasi = c_biaya_udara;
			}

		 	v_transport_darat_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan').value),2);
			v_transport_laut_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan').value),2);
			v_transport_udara_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan').value),2);
			
		 	v_transport_darat_verifikasi = parseFloat(removeCommas(window.document.getElementById('transport_darat_verifikasi').value),2);
			v_transport_laut_verifikasi = parseFloat(removeCommas(window.document.getElementById('transport_laut_verifikasi').value),2);
			v_transport_udara_verifikasi = parseFloat(removeCommas(window.document.getElementById('transport_udara_verifikasi').value),2);
			
		 	v_transport_darat_disetujui = parseFloat(removeCommas(window.document.getElementById('transport_darat_disetujui').value),2);
			v_transport_laut_disetujui = parseFloat(removeCommas(window.document.getElementById('transport_laut_disetujui').value),2);
			v_transport_udara_disetujui = parseFloat(removeCommas(window.document.getElementById('transport_udara_disetujui').value),2);									
			
			if (v_transport_darat_diajukan==""){v_transport_darat_diajukan=0;}
			if (v_transport_laut_diajukan==""){v_transport_laut_diajukan=0;}
			if (v_transport_udara_diajukan==""){v_transport_udara_diajukan=0;}
			
			if (v_transport_darat_verifikasi==""){v_transport_darat_verifikasi=0;}
			if (v_transport_laut_verifikasi==""){v_transport_laut_verifikasi=0;}
			if (v_transport_udara_verifikasi==""){v_transport_udara_verifikasi=0;}

			if (v_transport_darat_disetujui==""){v_transport_darat_disetujui=0;}
			if (v_transport_laut_disetujui==""){v_transport_laut_disetujui=0;}
			if (v_transport_udara_disetujui==""){v_transport_udara_disetujui=0;}
						
			v_biaya_total_diajukan = v_transport_darat_diajukan + v_transport_laut_diajukan + v_transport_udara_diajukan;
			v_biaya_total_verifikasi = v_transport_darat_verifikasi + v_transport_laut_verifikasi + v_transport_udara_verifikasi;
			v_biaya_total_disetujui = v_transport_darat_disetujui + v_transport_laut_disetujui + v_transport_udara_disetujui;
			
			document.fpop.biaya_total_diajukan.value = format_uang(v_biaya_total_diajukan);  
			document.fpop.biaya_total_verifikasi.value = format_uang(v_biaya_total_verifikasi);
			document.fpop.biaya_total_disetujui.value = format_uang(v_biaya_total_disetujui);
			
			curr_transport_darat_diajukan = v_transport_darat_diajukan;
			curr_transport_laut_diajukan = v_transport_laut_diajukan;
			curr_transport_udara_diajukan = v_transport_udara_diajukan;
			
			curr_transport_darat_verifikasi = v_transport_darat_verifikasi;
			curr_transport_laut_verifikasi = v_transport_laut_verifikasi;
			curr_transport_udara_verifikasi = v_transport_udara_verifikasi;								      		
    }             

    function f_ajax_val_transport_tipe_negara() 
    { 
      c_kode_segmen 			= window.document.getElementById('kode_segmen').value;
			c_transport_tipe_negara = window.document.getElementById('transport_tipe_negara').value;
			c_kode_perlindungan = window.document.getElementById('kode_perlindungan').value;
			
			c_transport_darat_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_darat_diajukan').value),2);
			c_transport_laut_diajukan 	= parseFloat(removeCommas(window.document.getElementById('transport_laut_diajukan').value),2);
			c_transport_udara_diajukan = parseFloat(removeCommas(window.document.getElementById('transport_udara_diajukan').value),2);
			
      if (isNaN(c_transport_darat_diajukan))
      	 c_transport_darat_diajukan = 0;
			if (isNaN(c_transport_laut_diajukan))
      	 c_transport_laut_diajukan = 0;
			if (isNaN(c_transport_udara_diajukan))
      	 c_transport_udara_diajukan = 0;
				 	 	 
			if (c_transport_tipe_negara==''){c_transport_tipe_negara='XyZ';}
			
			if (c_kode_segmen=='TKI' && ((c_kode_perlindungan=="ONSITE" && c_transport_tipe_negara=='I') || (c_kode_perlindungan!="ONSITE" && c_transport_tipe_negara!='I')) )
			{
			 	 document.getElementById('transport_darat_diajukan').value = 0;
				 document.getElementById('transport_laut_diajukan').value = 0;
				 document.getElementById('transport_udara_diajukan').value = 0;
				 document.getElementById('biaya_total_diajukan').value = 0;
				 
				 document.getElementById('transport_darat_verifikasi').value = 0;
				 document.getElementById('transport_laut_verifikasi').value = 0;
				 document.getElementById('transport_udara_verifikasi').value = 0;
				 document.getElementById('biaya_total_verifikasi').value = 0;
				 
			 	 document.getElementById('transport_darat_disetujui').value = 0;
				 document.getElementById('transport_laut_disetujui').value = 0;
				 document.getElementById('transport_udara_disetujui').value = 0;
				 document.getElementById('biaya_total_disetujui').value = 0;
			}else
			{
  			if (c_transport_darat_diajukan>0)
				{
				 	curr_transport_darat_diajukan=0;
					curr_transport_laut_diajukan=0;
					curr_transport_udara_diajukan=0;
					f_ajax_val_hitung_total_biaya();
				}
			}										
    }
		
    function showTableData()
    {
      var formObj = document.fpop;
      if (ajax.xmlhttp.readyState == 4) {
      	window.document.getElementById("tblrincian1").innerHTML = ajax.response;
      } else {
      	alert("Something strange occured!");
      }		
    }		
    
    function showClientData()
    {
      var formObj = document.fpop;
      eval(ajax.response);
      var st_errorval1 = window.document.getElementById("st_errval1").value;
      
      //tampilan error jika nama perusahaan sudah terdaftar --------------  					
      if (st_errorval1 == 1)
      {  
        window.document.getElementById("dispError1").innerHTML = "(* Setup Tarif Manfaat utk Transportasi Darat, Laut dan Udara tidak valid, harap cek data setup..!!!";
        window.document.getElementById("dispError1").style.display = 'block';
        window.document.getElementById('kode_tipe_penerima').focus();
      }else{
      	window.document.getElementById("dispError1").style.display = 'none';
      }       																																						 						 																														
    }
    </script>
  	<!-- end VALIDASI AJAX ------------------------------------------------------>	
			
    <table class="captionfoxrm" aria-describedby="captionfoxrmdesc">
		<tr><th></th></tr>
      <style>
        #header-caption2 {position:absolute;top:0;left:0;width: 98%;height: 35px;background: -webkit-linear-gradient(left,#6ba5ff,#416fd6);z-index: 300;text-align: left;}
        #header-caption2 h3 {font-size: 14px;color: #ffffff;margin: 10px 10px 10px 10px;height: 25px;border-bottom: 1px solid #6997ff;padding-left: 1px;border-top-right-radius: 1px;border-top-left-radius: 1px;}
      </style>		
      <tr><td id="header-caption2" colspan="3"><h3><?=$gs_pagetitle;?></h3></td></tr>	
      <tr><td colspan="3"></br></br></td></tr>	
    </table>
											
		<div id="formframe" style="width:900px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>">
			<input type="hidden" id="kode_perlindungan" name="kode_perlindungan" value="<?=$ls_kode_perlindungan;?>">
			<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
			<input type="hidden" id="kategori_manfaat" name="kategori_manfaat" value="<?=$ls_kategori_manfaat;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
      <input type="hidden" id="tombol" name="tombol">  
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">			
    	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender2" name="sender2" value="<?=$ls_sender2;?>">
      <input type="hidden" id="sender_activetab2" name="sender_activetab2" value="<?=$ls_sender_activetab2;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
			
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:750px;"><legend><strong><em><span style="color:#009999">Entry Rincian Manfaat Biaya Transportasi</span></em></strong></legend>
          </br>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tipe Penerima *</label>
            <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:400px;background-color:#ffff99;">
            <option value="">-- Pilih Tipe Penerima --</option>
            <? 
            $sql = "select a.kode_tipe_penerima, b.nama_tipe_penerima ".
                   "from sijstk.pn_kode_manfaat_eligibilitas a, sijstk.pn_kode_tipe_penerima b ".
                   "where a.kode_tipe_penerima = b.kode_tipe_penerima ".
                   "and a.kode_manfaat = '$ls_kode_manfaat' ".
                   "and a.kode_segmen = '$ls_kode_segmen' ".
                   "and nvl(a.status_nonaktif,'T')='T' ";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if ($row["KODE_TIPE_PENERIMA"]==$ls_kode_tipe_penerima && strlen($ls_kode_tipe_penerima)==strlen($row["KODE_TIPE_PENERIMA"])){ echo " selected"; }
            echo " value=\"".$row["KODE_TIPE_PENERIMA"]."\">".$row["NAMA_TIPE_PENERIMA"]."</option>";
            }
            ?>
            </select>																		 								
          </div>
          <div class="clear"></div>

      		<span id="span_transport_tipe_negara" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Negara Penggunaan Transportasi *</label>		 	    				
              <select size="1" id="transport_tipe_negara" name="transport_tipe_negara" value="<?=$ls_transport_tipe_negara;?>" tabindex="2" class="select_format" style="width:260px;background-color:#ffff99;" onchange="f_ajax_val_transport_tipe_negara();">
              <option value="">-- Pilih --</option>
              <? 
        				$sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and nvl(aktif,'T')='Y' order by seq";										
          			$DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                  echo "<option ";
                  if ($row["KODE"]==$ls_transport_tipe_negara && strlen($ls_transport_tipe_negara)==strlen($row["KODE"])){ echo " selected"; }
                  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                }
              ?>
              </select>		
            </div>																																												
          	<div class="clear"></div>		
      		</span>          					
					
					<div class="form-row_kiri">
          <label style = "text-align:right;">&nbsp;</label>
						</br>	 
						<table id="tblrincian1" width="80%" cellspacing="0" border="0" align="Xcenter" bordercolor="#C0C0C0" background-color= "#ffffff" aria-describedby="tblrincian1desc">
							<tbody>
								<tr>
                  <th align="center" style="width:400px;">&nbsp;</th>
									<th align="center">Biaya Diajukan</th>
                  <th align="center">Biaya Hasil Verifikasi</th>
                  <th align="center">Biaya Disetujui</th>
                </tr>

								<tr>
									<td colspan="4">	
										<hr/>
									</td>	
								</tr>
																
								<tr>
									<td><strong>Biaya Darat &nbsp;</strong></td>
									<td><input type="text" autocomplete="off" id="transport_darat_diajukan" name="transport_darat_diajukan" value="<?=number_format((float)$ln_transport_darat_diajukan,2,".",",");?>" readonly class="disabled" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);"></td>
									<td><input type="text" autocomplete="off" id="transport_darat_verifikasi" name="transport_darat_verifikasi" value="<?=number_format((float)$ln_transport_darat_verifikasi,2,".",",");?>" readonly class="disabled" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);"></td>
									<td><input type="hidden" id="transport_darat_disetujui" name="transport_darat_disetujui" value="<?=number_format((float)$ln_transport_darat_disetujui,2,".",",");?>" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);" readonly class="disabled"></td>	
								</tr>	
								
								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- ke Rumah Sakit/Rumah &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_darat_diajukan_rs" name="transport_darat_diajukan_rs" value="<?=number_format((float)$ln_transport_darat_diajukan_rs,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_darat_diajukan_rs();"></td>
									<td><input type="text" autocomplete="off" id="transport_darat_verifikasi_rs" name="transport_darat_verifikasi_rs" value="<?=number_format((float)$ln_transport_darat_verifikasi_rs,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>	

								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- Rujukan &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_darat_diajukan_rjk" name="transport_darat_diajukan_rjk" value="<?=number_format((float)$ln_transport_darat_diajukan_rjk,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_darat_diajukan_rjk();"></td>
									<td><input type="text" autocomplete="off" id="transport_darat_verifikasi_rjk" name="transport_darat_verifikasi_rjk" value="<?=number_format((float)$ln_transport_darat_verifikasi_rjk,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>
								
								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- RTW &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_darat_diajukan_rtw" name="transport_darat_diajukan_rtw" value="<?=number_format((float)$ln_transport_darat_diajukan_rtw,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_darat_diajukan_rtw();"></td>
									<td><input type="text" autocomplete="off" id="transport_darat_verifikasi_rtw" name="transport_darat_verifikasi_rtw" value="<?=number_format((float)$ln_transport_darat_verifikasi_rtw,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>
																
								<tr>
									<td><strong>Biaya Laut &nbsp;&nbsp;&nbsp;</strong></td>
									<td><input type="text" autocomplete="off" id="transport_laut_diajukan" name="transport_laut_diajukan" value="<?=number_format((float)$ln_transport_laut_diajukan,2,".",",");?>" readonly class="disabled" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);"></td>
									<td><input type="text" autocomplete="off" id="transport_laut_verifikasi" name="transport_laut_verifikasi" value="<?=number_format((float)$ln_transport_laut_verifikasi,2,".",",");?>" readonly class="disabled" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);"></td>
									<td><input type="hidden" id="transport_laut_disetujui" name="transport_laut_disetujui" value="<?=number_format((float)$ln_transport_laut_disetujui,2,".",",");?>" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);" readonly class="disabled"></td>	
								</tr>

								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- ke Rumah Sakit/Rumah &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_laut_diajukan_rs" name="transport_laut_diajukan_rs" value="<?=number_format((float)$ln_transport_laut_diajukan_rs,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_laut_diajukan_rs();"></td>
									<td><input type="text" autocomplete="off" id="transport_laut_verifikasi_rs" name="transport_laut_verifikasi_rs" value="<?=number_format((float)$ln_transport_laut_verifikasi_rs,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>	

								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- Rujukan &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_laut_diajukan_rjk" name="transport_laut_diajukan_rjk" value="<?=number_format((float)$ln_transport_laut_diajukan_rjk,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_laut_diajukan_rjk();"></td>
									<td><input type="text" autocomplete="off" id="transport_laut_verifikasi_rjk" name="transport_laut_verifikasi_rjk" value="<?=number_format((float)$ln_transport_laut_verifikasi_rjk,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>
								
								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- RTW &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_laut_diajukan_rtw" name="transport_laut_diajukan_rtw" value="<?=number_format((float)$ln_transport_laut_diajukan_rtw,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_laut_diajukan_rtw();"></td>
									<td><input type="text" autocomplete="off" id="transport_laut_verifikasi_rtw" name="transport_laut_verifikasi_rtw" value="<?=number_format((float)$ln_transport_laut_verifikasi_rtw,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>
																
								<tr>
									<td><strong>Biaya Udara &nbsp;</strong></td>
									<td><input type="text" autocomplete="off" id="transport_udara_diajukan" name="transport_udara_diajukan" value="<?=number_format((float)$ln_transport_udara_diajukan,2,".",",");?>" readonly class="disabled" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);"></td>
									<td><input type="text" autocomplete="off" id="transport_udara_verifikasi" name="transport_udara_verifikasi" value="<?=number_format((float)$ln_transport_udara_verifikasi,2,".",",");?>" readonly class="disabled" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);"></td>
									<td><input type="hidden" id="transport_udara_disetujui" name="transport_udara_disetujui" value="<?=number_format((float)$ln_transport_udara_disetujui,2,".",",");?>" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);" readonly class="disabled"></td>	
								</tr>
								
								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- ke Rumah Sakit/Rumah &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_udara_diajukan_rs" name="transport_udara_diajukan_rs" value="<?=number_format((float)$ln_transport_udara_diajukan_rs,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_udara_diajukan_rs();"></td>
									<td><input type="text" autocomplete="off" id="transport_udara_verifikasi_rs" name="transport_udara_verifikasi_rs" value="<?=number_format((float)$ln_transport_udara_verifikasi_rs,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>	

								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- Rujukan &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_udara_diajukan_rjk" name="transport_udara_diajukan_rjk" value="<?=number_format((float)$ln_transport_udara_diajukan_rjk,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_udara_diajukan_rjk();"></td>
									<td><input type="text" autocomplete="off" id="transport_udara_verifikasi_rjk" name="transport_udara_verifikasi_rjk" value="<?=number_format((float)$ln_transport_udara_verifikasi_rjk,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>
								
								<tr>
									<td style="text-align:right;">&nbsp;&nbsp;- RTW &nbsp;</td>
									<td><input type="text" autocomplete="off" id="transport_udara_diajukan_rtw" name="transport_udara_diajukan_rtw" value="<?=number_format((float)$ln_transport_udara_diajukan_rtw,2,".",",");?>" tabindex="2" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_transport_udara_diajukan_rtw();"></td>
									<td><input type="text" autocomplete="off" id="transport_udara_verifikasi_rtw" name="transport_udara_verifikasi_rtw" value="<?=number_format((float)$ln_transport_udara_verifikasi_rtw,2,".",",");?>" tabindex="3" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_total_biaya();"></td>
									<td>&nbsp;</td>	
								</tr>
																
								<tr>
									<td colspan="4">	
										<hr/>
									</td>	
								</tr>
								<tr>
									<td><em>Total Biaya &nbsp;</em></td>
									<td><input type="text" id="biaya_total_diajukan" name="biaya_total_diajukan" value="<?=number_format((float)$ln_biaya_total_diajukan,2,".",",");?>" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);" readonly class="disabled"></td>	
									<td><input type="text" id="biaya_total_verifikasi" name="biaya_total_verifikasi" value="<?=number_format((float)$ln_biaya_total_verifikasi,2,".",",");?>" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);" readonly class="disabled"></td>	
									<td><input type="text" id="biaya_total_disetujui" name="biaya_total_disetujui" value="<?=number_format((float)$ln_biaya_total_disetujui,2,".",",");?>" size="20" maxlength="20" style="text-align:right;" onblur="this.value=format_uang(this.value);" readonly class="disabled"></td>	
								</tr>
																																								 	
							</tbody>			 
						</table>
						</br></br>	 
					</div>
          <div class="clear"></div>
					
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Catatan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:370px" id="keterangan" name="keterangan" tabindex="8" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>
					
					<?
          echo "<script type=\"text/javascript\">fl_js_transport_tipe_negara();</script>";
					?>
					
					</br></br>		
				</fieldset>	
								
        <? 					
        if(!empty($ls_kode_manfaat))
        {
        ?>			 	
          <div id="buttonbox" style="width:740px;text-align:center;">        			 
          <?
					if ($ls_task!="view")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="            SIMPAN            " onClick="fl_js_val_simpan();">
          <input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="            TUTUP            " />
            <?
					}else
					{
  					?>
  					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="            TUTUP            " />       					
            <?
					}
					?>						     					
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
		<legend></legend>
      <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
      <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
      </fieldset>		
    <?
    }
    ?>  
	</form>
</body>
</html>				