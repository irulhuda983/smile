<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "Entry Rincian ";

$ls_form_penetapan	 	 		= !isset($_GET['form_penetapan']) ? $_POST['form_penetapan'] : $_GET['form_penetapan'];
$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender2 				 			= !isset($_GET['sender2']) ? $_POST['sender2'] : $_GET['sender2'];
$ls_sender_activetab2 		= !isset($_GET['sender_activetab2']) ? $_POST['sender_activetab2'] : $_GET['sender_activetab2'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];

$ls_kode_klaim	 		 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	 	 			= !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ln_no_urut	 	 						= !isset($_GET['no_urut']) ? $_POST['no_urut'] : $_GET['no_urut'];
$ls_kategori_manfaat			= $_POST['kategori_manfaat'];
$ls_kd_prg								= $_POST['kd_prg'];	

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
  $sql = "select a.nama_manfaat, a.kategori_manfaat, b.kd_prg, to_char(c.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian ".
			 	 "from sijstk.pn_kode_manfaat a, sijstk.pn_klaim_manfaat b, sijstk.pn_klaim c ".
         "where a.kode_manfaat = b.kode_manfaat and b.kode_klaim = c.kode_klaim ".
				 "and b.kode_klaim = c.kode_klaim ".
				 "and b.kode_klaim = '$ls_kode_klaim' ". 
				 "and b.kode_manfaat = '$ls_kode_manfaat'";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat			= $row['NAMA_MANFAAT'];
	$ls_kategori_manfaat  = $row['KATEGORI_MANFAAT'];
	$ls_kd_prg						= $row['KD_PRG'];	
	$ld_tgl_kejadian  		= $row['TGL_KEJADIAN'];
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}
		
$ls_kode_tipe_penerima			= $_POST['kode_tipe_penerima'];
$ls_beasiswa_nik_penerima		= $_POST['beasiswa_nik_penerima'];
$ls_beasiswa_penerima				= $_POST['beasiswa_penerima'];
$ld_beasiswa_tgllahir_penerima	= $_POST['beasiswa_tgllahir_penerima'];
$ls_beasiswa_flag_masih_sekolah	= $_POST['beasiswa_flag_masih_sekolah'];
if ($ls_beasiswa_flag_masih_sekolah=="on" || $ls_beasiswa_flag_masih_sekolah=="ON" || $ls_beasiswa_flag_masih_sekolah=="Y")
{
	$ls_beasiswa_flag_masih_sekolah = "Y";
}else
{
	$ls_beasiswa_flag_masih_sekolah = "T";	 
}

$ls_beasiswa_jenis							= $_POST['beasiswa_jenis'];
$ls_beasiswa_jenjang_pendidikan	= $_POST['beasiswa_jenjang_pendidikan'];
$ls_beasiswa_kini_tingkat				= $_POST['beasiswa_kini_tingkat'];
$ls_beasiswa_kini_thn						= $_POST['beasiswa_kini_thn'];
$ln_beasiswa_kini_nom						= str_replace(',','',$_POST['beasiswa_kini_nom']);

$ls_beasiswa_rapel_jenis				= $_POST['beasiswa_rapel_jenis'];
$ls_beasiswa_rapel_jenjang			= $_POST['beasiswa_rapel_jenjang'];
$ls_beasiswa_rapel_tingkat			= $_POST['beasiswa_rapel_tingkat'];
$ls_beasiswa_rapel_thn					= $_POST['beasiswa_rapel_thn'];
$ln_beasiswa_rapel_nom					= str_replace(',','',$_POST['beasiswa_rapel_nom']);

$ln_nom_biaya_disetujui	= str_replace(',','',$_POST['nom_biaya_disetujui']);        					
$ls_keterangan					= $_POST['keterangan'];

if ($ls_kategori_manfaat=="TAMBAHAN")
{
 	 $ln_nom_manfaat_utama = 0;
	 $ln_nom_manfaat_tambahan = $ln_nom_biaya_disetujui == null ? 0 : $ln_nom_biaya_disetujui;
}else
{
 	 $ln_nom_manfaat_utama = $ln_nom_biaya_disetujui;
	 $ln_nom_manfaat_tambahan = 0;
}
$ln_nom_manfaat_gross = ($ln_nom_manfaat_utama+$ln_nom_manfaat_tambahan);
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
  <!--<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.css" />-->
	<link rel="stylesheet" type="text/css" href="<?="http://$HTTP_HOST";?>/style/style.new.css?ver=1.2" />
	<script type="text/javascript" language="JavaScript" src="http://<?=$HTTP_HOST;?>/javascript/jquery.js"></script>
	
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
    	echo "window.location.replace('$ls_sender?task=edit&kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid');";		
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
      if(form.kode_tipe_penerima.value==""){
        alert('Tipe Penerima tidak boleh kosong...!!!');
        form.kode_tipe_penerima.focus();
      }else if(form.beasiswa_penerima.value==""){
        alert('Nama Penerima Beasiswa tidak boleh kosong...!!!');
        form.beasiswa_penerima.focus();				
      }else if(form.beasiswa_jenis.value==""){
        alert('Jenis Beasiswa tidak boleh kosong...!!!');
        form.beasiswa_jenis.focus();
      }else if(form.beasiswa_jenis.value=="BEASISWA" && form.beasiswa_jenjang_pendidikan.value==""){
        alert('Untuk beasiswa pendidikan maka jenjang pendidikan tidak boleh kosong...!!!');
        form.beasiswa_jenjang_pendidikan.focus();	
      }else if(form.beasiswa_kini_tingkat.value==""){
        alert('Tingkat/Kelas ajarah tahun ini tidak boleh kosong...!!!');
        form.beasiswa_kini_tingkat.focus();							
      }else
  		{
         form.tombol.value="simpan";
         form.submit(); 		 
  		}								 
  	}									 
    function fl_js_set_beasiswa_flag_masih_sekolah()
    {
    	var form = document.fpop;
    	if (form.beasiswa_flag_masih_sekolah.checked)
    	{
    		form.beasiswa_flag_masih_sekolah.value = "Y";
    	}
    	else
    	{
    		form.beasiswa_flag_masih_sekolah.value = "T";
    	}
    }

    function fl_js_span_jenjang_pendidikan() 
    { 
      var form = document.fpop;
      var	v_beasiswa_jenis = window.document.getElementById("beasiswa_jenis").value;
      
      if (v_beasiswa_jenis =="BEASISWA") //Beasiswa Jenis ----
      {    
        window.document.getElementById("span_jenjang_pendidikan").style.display = 'block';
      }else // Selain Cacat Sebagian Fungsi ----------------
      {
        window.document.getElementById("span_jenjang_pendidikan").style.display = 'none';
				window.document.getElementById("beasiswa_jenjang_pendidikan").value = "";
      } 
      f_ajax_val_beasiswa_jenis();	
    }
		
    function fl_js_span_jenjang_pendidikan_rapel() 
    { 
      var form = document.fpop;
			var	v_beasiswa_jenis = window.document.getElementById("beasiswa_rapel_jenis").value;
      
      if (v_beasiswa_jenis =="BEASISWA") //Beasiswa Jenis ----
      {    
        window.document.getElementById("span_jenjang_pendidikan_rapel").style.display = 'block';
      }else // Selain Cacat Sebagian Fungsi ----------------
      {
        window.document.getElementById("span_jenjang_pendidikan_rapel").style.display = 'none';
				window.document.getElementById("beasiswa_rapel_jenjang").value = "";
      } 
      f_ajax_val_beasiswa_jenis_rapel();	
    }			

    function fl_js_val_nomor_identitas()
    {
      var v_nomor_identitas = window.document.getElementById('beasiswa_nik_penerima').value;
			var number=/^[0-9]+$/;
			var v_error = "0";
			
			
      if (v_nomor_identitas!='')
      {
        if (v_nomor_identitas.length!=16)
        { 
          v_error = "1";
          document.getElementById('beasiswa_nik_penerima').value = '';				 
          window.document.getElementById('beasiswa_nik_penerima').focus();
          curr_nomor_identitas = '';
          alert("NIK harus 16 karakter...!!!");         
          return false;
        }else
        {
          if (!v_nomor_identitas.match(number))
          { 
            v_error = "1";
            document.getElementById('beasiswa_nik_penerima').value = '';				 
            window.document.getElementById('beasiswa_nik_penerima').focus();
            curr_nomor_identitas = '';
            alert("NIK tidak boleh berisikan selain angka...!!!");         
            return false;
          }			 
        }
      }//end if v_nomor_identitas!=''
				 
			if (v_error!="1")
			{
			 	cekExistNIK();
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
      if ($ls_beasiswa_rapel_jenis=="")
      {
       	 $ls_beasiswa_rapel_jenjang = "";
      	 $ls_beasiswa_rapel_thn = "";
      	 $ls_beasiswa_rapel_tingkat = "";
      	 $ln_beasiswa_rapel_nom = "0";
      }
	   				
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
							 "	beasiswa_jenis, beasiswa_flag_masih_sekolah, beasiswa_jenjang_pendidikan, ". 
							 "	nom_biaya_diajukan, nom_biaya_disetujui, ".
							 "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ".						 
               "	keterangan, tgl_rekam, petugas_rekam, beasiswa_penerima, beasiswa_nik_penerima, ". 
               "	beasiswa_tgllahir_penerima, beasiswa_kini_thn, beasiswa_kini_nom, ". 
               "	beasiswa_rapel_thn, beasiswa_rapel_nom, beasiswa_rapel_jenis, ". 
               "	beasiswa_rapel_jenjang, beasiswa_kini_tingkat, beasiswa_rapel_tingkat ".
							 ") ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut',null,'$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	'$ls_beasiswa_jenis','$ls_beasiswa_flag_masih_sekolah', '$ls_beasiswa_jenjang_pendidikan', ". 
               "	null, '$ln_nom_biaya_disetujui', ".
							 "	'$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', '$ln_nom_manfaat_gross', ". 
							 "  '$ln_nom_pph', '$ln_nom_pembulatan', '$ln_nom_manfaat_netto', ".
							 "	'$ls_keterangan', sysdate, '$username','$ls_beasiswa_penerima', '$ls_beasiswa_nik_penerima', ".
							 "	to_date('$ld_beasiswa_tgllahir_penerima','dd/mm/yyyy'), '$ls_beasiswa_kini_thn', '$ln_beasiswa_kini_nom', ". 
               "	'$ls_beasiswa_rapel_thn', '$ln_beasiswa_rapel_nom', '$ls_beasiswa_rapel_jenis', ". 
               "	'$ls_beasiswa_rapel_jenjang', '$ls_beasiswa_kini_tingkat', '$ls_beasiswa_rapel_tingkat' ".
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
						 	 "	kode_manfaat_detil			= null, ".		 
               "	kategori_manfaat 	 			= '$ls_kategori_manfaat', ".
							 "	kode_tipe_penerima 	 		= '$ls_kode_tipe_penerima', ".
							 "	kd_prg						 	 		= '$ls_kd_prg', ". 
							 "	beasiswa_jenis					= '$ls_beasiswa_jenis', ".
							 "	beasiswa_flag_masih_sekolah	= '$ls_beasiswa_flag_masih_sekolah', ".
							 "	beasiswa_jenjang_pendidikan	= '$ls_beasiswa_jenjang_pendidikan', ".
							 "	nom_biaya_diajukan			= '$ln_nom_biaya_diajukan', ". 
							 "	nom_biaya_disetujui			= '$ln_nom_biaya_disetujui', ".
							 "	nom_manfaat_utama				= '$ln_nom_manfaat_utama', ". 
							 "	nom_manfaat_tambahan		= '$ln_nom_manfaat_tambahan', ". 
							 "	nom_manfaat_gross				= '$ln_nom_manfaat_gross', ". 
							 "  nom_pph									= '$ln_nom_pph', ". 
							 "	nom_pembulatan					= '$ln_nom_pembulatan', ". 
							 "	nom_manfaat_netto				= '$ln_nom_manfaat_netto', ".					 
               "	keterangan							= '$ls_keterangan', ". 
							 "	tgl_ubah								= sysdate, ". 
							 "	petugas_ubah						= '$username', ".
							 "	beasiswa_penerima				= '$ls_beasiswa_penerima', ".
							 "	beasiswa_nik_penerima		= '$ls_beasiswa_nik_penerima', ".
							 "	beasiswa_tgllahir_penerima	= to_date('$ld_beasiswa_tgllahir_penerima','dd/mm/yyyy'), ".
							 "	beasiswa_kini_thn				= '$ls_beasiswa_kini_thn', ".
							 "	beasiswa_kini_nom				= '$ln_beasiswa_kini_nom', ".
							 "	beasiswa_rapel_thn			= '$ls_beasiswa_rapel_thn', ".
							 "	beasiswa_rapel_nom			= '$ln_beasiswa_rapel_nom', ".
							 "	beasiswa_rapel_jenis		= '$ls_beasiswa_rapel_jenis', ".
							 "	beasiswa_rapel_jenjang	= '$ls_beasiswa_rapel_jenjang', ".
							 "	beasiswa_kini_tingkat		= '$ls_beasiswa_kini_tingkat', ".
							 "	beasiswa_rapel_tingkat	= '$ls_beasiswa_rapel_tingkat' ".
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
									a.beasiswa_jenis, 
									nvl(beasiswa_flag_masih_sekolah,'T') beasiswa_flag_masih_sekolah, beasiswa_jenjang_pendidikan, 
									nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
                  nom_pph, nom_pembulatan, nom_manfaat_netto, keterangan, beasiswa_penerima, beasiswa_nik_penerima,
									to_char(beasiswa_tgllahir_penerima,'dd/mm/yyyy') beasiswa_tgllahir_penerima, beasiswa_kini_thn, beasiswa_kini_nom,
                  beasiswa_rapel_thn, beasiswa_rapel_nom, beasiswa_rapel_jenis,
                  beasiswa_rapel_jenjang, beasiswa_kini_tingkat, beasiswa_rapel_tingkat
              from sijstk.pn_klaim_manfaat_detil a
              where kode_klaim = '$ls_kode_klaim'
              and kode_manfaat = '$ls_kode_manfaat'
              and no_urut = '$ln_no_urut' ";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
      $ls_kode_klaim							= $data["KODE_KLAIM"];			
      $ls_kode_manfaat						= $data["KODE_MANFAAT"];
      $ln_no_urut									= $data["NO_URUT"];
      $ls_kode_manfaat_detil			= $data["KODE_MANFAAT_DETIL"];
      $ls_kategori_manfaat				= $data["KATEGORI_MANFAAT"];
      $ls_kode_tipe_penerima			= $data["KODE_TIPE_PENERIMA"];
      $ls_kd_prg									= $data["KD_PRG"];
      $ln_nom_biaya_diajukan			= $data["NOM_BIAYA_DIAJUKAN"];
      $ln_nom_biaya_disetujui 		= $data["NOM_BIAYA_DISETUJUI"];
			$ls_beasiswa_jenis					= $data["BEASISWA_JENIS"];
			$ls_beasiswa_flag_masih_sekolah	= $data["BEASISWA_FLAG_MASIH_SEKOLAH"];
			$ls_beasiswa_jenjang_pendidikan	= $data["BEASISWA_JENJANG_PENDIDIKAN"];
      $ln_nom_manfaat_utama				= $data["NOM_MANFAAT_UTAMA"];
      $ln_nom_manfaat_tambahan		= $data["NOM_MANFAAT_TAMBAHAN"];
      $ln_nom_manfaat_gross 			= $data["NOM_MANFAAT_GROSS"];
      $ln_nom_pph									= $data["NOM_PPH"];
      $ln_nom_pembulatan					= $data["NOM_PEMBULATAN"];
      $ln_nom_manfaat_netto				= $data["NOM_MANFAAT_NETTO"];
      $ls_keterangan							= $data["KETERANGAN"];	
			$ls_beasiswa_penerima				= $data["BEASISWA_PENERIMA"];	
			$ls_beasiswa_nik_penerima		= $data["BEASISWA_NIK_PENERIMA"];
			$ld_beasiswa_tgllahir_penerima	= $data["BEASISWA_TGLLAHIR_PENERIMA"];
			$ls_beasiswa_kini_thn				= $data["BEASISWA_KINI_THN"];
			$ln_beasiswa_kini_nom				= $data["BEASISWA_KINI_NOM"];
			$ls_beasiswa_rapel_thn			= $data["BEASISWA_RAPEL_THN"];
			$ln_beasiswa_rapel_nom			= $data["BEASISWA_RAPEL_NOM"];
			$ls_beasiswa_rapel_jenis		= $data["BEASISWA_RAPEL_JENIS"];
			$ls_beasiswa_rapel_jenjang	= $data["BEASISWA_RAPEL_JENJANG"];
			$ls_beasiswa_kini_tingkat		= $data["BEASISWA_KINI_TINGKAT"];
			$ls_beasiswa_rapel_tingkat	= $data["BEASISWA_RAPEL_TINGKAT"];
			
			if ($ls_beasiswa_rapel_thn=="")
			{
  			$sql = "select to_char(trunc(to_date('$ls_beasiswa_kini_thn','yyyy'),'yyyy')-1,'yyyy') thn_lalu from dual ";
  			$DB->parse($sql);
        $DB->execute();
        $data = $DB->nextrow();		
        $ls_beasiswa_rapel_thn 		= $data["THN_LALU"];				
			}								
    }else
		{
		 	//initial value ----------------------------------------------------------
			$sql = "select to_char(sysdate,'yyyy') thn_kini, to_char(trunc(sysdate,'yyyy')-1,'yyyy') thn_lalu from dual ";
			$DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
      $ls_beasiswa_kini_thn	 		= $data["THN_KINI"];			
      $ls_beasiswa_rapel_thn 		= $data["THN_LALU"];	 
		}	
		?>
		<script type="text/javascript">
      //ambil nilai previous, dibandingkan dg nilai current, apabila berbeda maka ajax akan dijalankan
      var curr_nik_penerima =<?php echo ($ls_beasiswa_nik_penerima=='') ? 'false' : "'".$ls_beasiswa_nik_penerima."'"; ?>;
			var curr_tgl_lahir =<?php echo ($ld_beasiswa_tgllahir_penerima=='') ? 'false' : "'".$ld_beasiswa_tgllahir_penerima."'"; ?>;
		</script>

  	<!-- VALIDASI AJAX ---------------------------------------------------------->
    <script type="text/javascript" src="../../javascript/validator.js"></script>
    <script type="text/javascript" src="../../javascript/ajax.js"></script>
    
    <script type="text/javascript">
    //Create validator object
    var validator = new formValidator();
    var ajax = new sack();
    //ambil nilai previous, dibandingkan dg nilai current, apabila berbeda maka ajax akan dijalankan
    var curr_beasiswa_jenis =<?php echo ($ls_beasiswa_jenis=='') ? 'false' : "'".$ls_beasiswa_jenis."'"; ?>;
		var curr_beasiswa_jenjang_pendidikan =<?php echo ($ls_beasiswa_jenjang_pendidikan=='') ? 'false' : "'".$ls_beasiswa_jenjang_pendidikan."'"; ?>;
		var curr_beasiswa_rapel_jenis =<?php echo ($ls_beasiswa_rapel_jenis=='') ? 'false' : "'".$ls_beasiswa_rapel_jenis."'"; ?>;
		var curr_beasiswa_rapel_jenjang =<?php echo ($ls_beasiswa_rapel_jenjang=='') ? 'false' : "'".$ls_beasiswa_rapel_jenjang."'"; ?>;
						
    //hitung nilai manfaat -----------------------------------------------------
    function f_ajax_val_beasiswa_jenis()
    {		 	
			c_kode_klaim 	 								= window.document.getElementById('kode_klaim').value;
			c_kode_manfaat 								= window.document.getElementById('kode_manfaat').value;
			c_kd_prg 			 								= window.document.getElementById('kd_prg').value;
			c_kode_tipe_penerima 					= window.document.getElementById('kode_tipe_penerima').value;
			c_beasiswa_jenis 		 					= window.document.getElementById('beasiswa_jenis').value;
			c_beasiswa_jenjang_pendidikan = window.document.getElementById('beasiswa_jenjang_pendidikan').value;
			c_no_urut 										= window.document.getElementById('no_urut').value;
			c_beasiswa_nik_penerima 			= window.document.getElementById('beasiswa_nik_penerima').value;
			c_tahun 											= window.document.getElementById('beasiswa_kini_thn').value;
			
			if ((c_beasiswa_jenis!=curr_beasiswa_jenis))
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_beasiswa_thnbjln&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_beasiswa_jenis='+c_beasiswa_jenis+'&c_beasiswa_jenjang_pendidikan='+c_beasiswa_jenjang_pendidikan+'&c_kd_prg='+c_kd_prg+'&c_no_urut='+c_no_urut+'&c_beasiswa_nik_penerima='+c_beasiswa_nik_penerima+'&c_tahun='+c_tahun;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_beasiswa_jenis = c_beasiswa_jenis;
			}										      		
    }             

    function f_ajax_val_beasiswa_jenjang_pendidikan()
    {		 	
			c_kode_klaim 	 								= window.document.getElementById('kode_klaim').value;
			c_kode_manfaat 								= window.document.getElementById('kode_manfaat').value;
			c_kd_prg 			 								= window.document.getElementById('kd_prg').value;
			c_kode_tipe_penerima 					= window.document.getElementById('kode_tipe_penerima').value;
			c_beasiswa_jenis 							= window.document.getElementById('beasiswa_jenis').value;
			c_beasiswa_jenjang_pendidikan = window.document.getElementById('beasiswa_jenjang_pendidikan').value;
			c_no_urut 										= window.document.getElementById('no_urut').value;
			c_beasiswa_nik_penerima 			= window.document.getElementById('beasiswa_nik_penerima').value;
			c_tahun 											= window.document.getElementById('beasiswa_kini_thn').value;
			
			if ((c_beasiswa_jenjang_pendidikan!=curr_beasiswa_jenjang_pendidikan))
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_beasiswa_thnbjln&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_beasiswa_jenis='+c_beasiswa_jenis+'&c_beasiswa_jenjang_pendidikan='+c_beasiswa_jenjang_pendidikan+'&c_kd_prg='+c_kd_prg+'&c_no_urut='+c_no_urut+'&c_beasiswa_nik_penerima='+c_beasiswa_nik_penerima+'&c_tahun='+c_tahun;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_beasiswa_jenjang_pendidikan = c_beasiswa_jenjang_pendidikan;
			}										      		
    }
				
    function f_ajax_val_beasiswa_jenis_rapel()
    {		 	
			c_kode_klaim 	 								= window.document.getElementById('kode_klaim').value;
			c_kode_manfaat 								= window.document.getElementById('kode_manfaat').value;
			c_kd_prg 			 								= window.document.getElementById('kd_prg').value;
			c_kode_tipe_penerima 					= window.document.getElementById('kode_tipe_penerima').value;
			c_beasiswa_jenis 							= window.document.getElementById('beasiswa_rapel_jenis').value;
			c_beasiswa_jenjang_pendidikan = window.document.getElementById('beasiswa_rapel_jenjang').value;
			c_no_urut 										= window.document.getElementById('no_urut').value;
			c_beasiswa_nik_penerima 			= window.document.getElementById('beasiswa_nik_penerima').value;
			c_tahun 											= window.document.getElementById('beasiswa_rapel_thn').value;
			c_beasiswa_jenis_thnbjln			= window.document.getElementById('beasiswa_jenis').value;
			c_jenjang_pendidikan_thnbjln  = window.document.getElementById('beasiswa_jenjang_pendidikan').value;
			
			if ((c_beasiswa_jenis!=curr_beasiswa_rapel_jenis))
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_beasiswa_rapel&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_beasiswa_jenis='+c_beasiswa_jenis+'&c_beasiswa_jenjang_pendidikan='+c_beasiswa_jenjang_pendidikan+'&c_kd_prg='+c_kd_prg+'&c_no_urut='+c_no_urut+'&c_beasiswa_nik_penerima='+c_beasiswa_nik_penerima+'&c_tahun='+c_tahun+'&c_beasiswa_jenis_thnbjln='+c_beasiswa_jenis_thnbjln+'&c_jenjang_pendidikan_thnbjln='+c_jenjang_pendidikan_thnbjln;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_beasiswa_rapel_jenis = c_beasiswa_jenis;
			}										      		
    } 
				
    function f_ajax_val_beasiswa_jenjang_pendidikan_rapel()
    {		 	
			c_kode_klaim 	 								= window.document.getElementById('kode_klaim').value;
			c_kode_manfaat 								= window.document.getElementById('kode_manfaat').value;
			c_kd_prg 			 								= window.document.getElementById('kd_prg').value;
			c_kode_tipe_penerima 					= window.document.getElementById('kode_tipe_penerima').value;
			c_beasiswa_jenis 							= window.document.getElementById('beasiswa_rapel_jenis').value;
			c_beasiswa_jenjang_pendidikan = window.document.getElementById('beasiswa_rapel_jenjang').value;
			c_no_urut 										= window.document.getElementById('no_urut').value;
			c_beasiswa_nik_penerima 			= window.document.getElementById('beasiswa_nik_penerima').value;
			c_tahun 											= window.document.getElementById('beasiswa_rapel_thn').value;
			c_beasiswa_jenis_thnbjln			= window.document.getElementById('beasiswa_jenis').value;
			c_jenjang_pendidikan_thnbjln  = window.document.getElementById('beasiswa_jenjang_pendidikan').value;
						
			if ((c_beasiswa_jenjang_pendidikan!=curr_beasiswa_rapel_jenjang))
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_beasiswa_rapel&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_beasiswa_jenis='+c_beasiswa_jenis+'&c_beasiswa_jenjang_pendidikan='+c_beasiswa_jenjang_pendidikan+'&c_kd_prg='+c_kd_prg+'&c_no_urut='+c_no_urut+'&c_beasiswa_nik_penerima='+c_beasiswa_nik_penerima+'&c_tahun='+c_tahun+'&c_beasiswa_jenis_thnbjln='+c_beasiswa_jenis_thnbjln+'&c_jenjang_pendidikan_thnbjln='+c_jenjang_pendidikan_thnbjln;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_beasiswa_rapel_jenjang = c_beasiswa_jenjang_pendidikan;
			}										      		
    } 

    function f_ajax_val_total_disetujui() 
    { 
			c_thnkini = parseFloat(removeCommas(window.document.getElementById('beasiswa_kini_nom').value),2);
			c_rapel = parseFloat(removeCommas(window.document.getElementById('beasiswa_rapel_nom').value),2);
			
      if (isNaN(c_thnkini))
      	 c_thnkini = 0;
      
      if (isNaN(c_rapel))
      	 c_rapel = 0;
				 			
      v_total = (c_thnkini + c_rapel);
      document.getElementById('nom_biaya_disetujui').value = format_uang(v_total);					
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
        window.document.getElementById("dispError1").innerHTML = "(* Setup Tarif Manfaat utk Beasiswa Pendidikan tidak valid, harap cek data setup..!!!";
        window.document.getElementById("dispError1").style.display = 'block';
        window.document.getElementById('kode_tipe_penerima').focus();
      }else{
      	window.document.getElementById("dispError1").style.display = 'none';
      }       																																						 						 																														
    }
    </script>
	
  	<!-- end VALIDASI AJAX ------------------------------------------------------>	
			
    <table class="captionfoxrm">
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
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
			<input type="hidden" id="kategori_manfaat" name="kategori_manfaat" value="<?=$ls_kategori_manfaat;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
			<input type="hidden" id="tgl_kejadian" name="tgl_kejadian" value="<?=$ld_tgl_kejadian;?>">
      <input type="hidden" id="tombol" name="tombol">  
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
			<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
    	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender2" name="sender2" value="<?=$ls_sender2;?>">
      <input type="hidden" id="sender_activetab2" name="sender_activetab2" value="<?=$ls_sender_activetab2;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
						
			<div id="formKiri" style="width:900px;">
			<table width="1000px" border="0">
				<tr>
					<td width="50%" valign="top">
    				<fieldset style="width:500px;"><legend ><b><i><font color="#009999">Entry Rincian Manfaat Beasiswa Pendidikan</font></i></b></legend>
              <div class="form-row_kiri">
              <label style = "text-align:right;">Tipe Penerima *</label>
                <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:256px;background-color:#ffff99;">
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
    
              <div class="form-row_kiri">
              <label  style = "text-align:right;">NIK Penerima *</label>
                <input type="text" id="beasiswa_nik_penerima" name="beasiswa_nik_penerima" value="<?=$ls_beasiswa_nik_penerima;?>" tabindex="2" style="width:250px;background-color:#ffff99;" maxlength="16" onblur="fl_js_val_nomor_identitas();">				
              </div>																		
              <div class="clear"></div>
    										
              <div class="form-row_kiri">
              <label  style = "text-align:right;">Nama Penerima *</label>
                <input type="text" id="beasiswa_penerima" name="beasiswa_penerima" value="<?=$ls_beasiswa_penerima;?>" tabindex="3" style="width:250px;background-color:#ffff99;" maxlength="100" onblur="this.value=this.value.toUpperCase();" >				
              </div>																		
              <div class="clear"></div>		
    
              <div class="form-row_kiri">
              <label  style = "text-align:right;">Tgl Lahir *</label>
                <input type="text" id="beasiswa_tgllahir_penerima" name="beasiswa_tgllahir_penerima" value="<?=$ld_beasiswa_tgllahir_penerima;?>" maxlength="10" tabindex="4" onblur="convert_date(beasiswa_tgllahir_penerima);cekUsiaPenerimaBeasiswa();" style="width:200px;background-color:#ffff99;">
                <input id="btn_beasiswa_tgllahir_penerima" type="image" align="top" onclick="return showCalendar('beasiswa_tgllahir_penerima', 'dd-mm-y');" src="../../images/calendar.gif" />
    					</div>																		
              <div class="clear"></div>
    
              <div class="form-row_kiri">
              <label  style = "text-align:right;">Status Saat Ini</label>
                <input type="checkbox" class="cebox" id="beasiswa_flag_masih_sekolah" name="beasiswa_flag_masih_sekolah" value="<?=$ls_beasiswa_flag_masih_sekolah;?>" tabindex="5" onClick="fl_js_set_beasiswa_flag_masih_sekolah();" <?=$ls_beasiswa_flag_masih_sekolah=="Y" || $ls_beasiswa_flag_masih_sekolah=="ON" || $ls_beasiswa_flag_masih_sekolah=="on" ? "checked" : "";?>> 
    					<i><font color="#009999">Sedang Menempuh Pendidikan</font></i>         																			 								
              </div>
              <div class="clear"></div>
    				</fieldset>					
					</td>
					
					<td width="50%" valign="top">
            <fieldset style="width:220px;"><legend style="text-align:left;font: 10px Verdana, Arial, Helvetica, sans-serif;"><b>&nbsp;</b></legend>
            <?
            if ($ls_beasiswa_nik_penerima=="")
            {
              ?>
              <input id="nik_foto" name="nik_foto" type="image" align="center" src="../../images/nopic.png" style="height: 100px !important; width: 95px !important;"/>
              <?
            }else
            {
              ?>
              <img id="nik_foto" src="../../mod_kn/ajax/kngetfoto.php?dataid=<?=$ls_beasiswa_nik_penerima;?>" style="height: 100px !important; width: 95px !important;"/>
              <?
            }
            ?>
            <div class="clear"></div>								
            </fieldset>						
					</td>		
				</tr>
				
				<tr>
					<td colspan="2">
				<fieldset style="width:750px;"><legend ><b><i><font color="#009999">Beasiswa yang Dibayarkan untuk Tahun Ajaran </font><font size="2px;"><?=$ls_beasiswa_kini_thn;?></font></i></b></legend>	          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jenis Beasiswa *</label>
            <select size="1" id="beasiswa_jenis" name="beasiswa_jenis" value="<?=$ls_beasiswa_jenis;?>" tabindex="6" class="select_format" style="width:256px;background-color:#ffff99;" onChange="fl_js_span_jenjang_pendidikan();">
            <option value="">-- Pilih Jenis Beasiswa--</option>
            <? 
            $sql = "select a.kode, a.keterangan from sijstk.ms_lookup a where tipe='KLMJNSBEAS' order by seq ";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if ($row["KODE"]==$ls_beasiswa_jenis && strlen($ls_beasiswa_jenis)==strlen($row["KODE"])){ echo " selected"; }
            echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>																		 								
          </div>
          <div class="clear"></div>
					
					<span id="span_jenjang_pendidikan" style="display:none;">
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Jenjang Pendidikan *</label>
              <select size="1" id="beasiswa_jenjang_pendidikan" name="beasiswa_jenjang_pendidikan" value="<?=$ls_beasiswa_jenjang_pendidikan;?>" tabindex="7" class="select_format" style="width:256px;background-color:#ffff99;" onChange="f_ajax_val_beasiswa_jenjang_pendidikan();f_ajax_val_total_disetujui();">
              <option value="">-- Pilih --</option>
              <? 
              $sql = "select a.kode, a.keterangan from sijstk.ms_lookup a where tipe='TKSKOLAH' order by seq ";
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
              echo "<option ";
              if ($row["KODE"]==$ls_beasiswa_jenjang_pendidikan && strlen($ls_beasiswa_jenjang_pendidikan)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
              }
              ?>
              </select>       																			 								
            </div>
            <div class="clear"></div>
					</span>

					<div class="form-row_kiri">
          <label  style = "text-align:right;">Tingkat/Kelas *</label>
            <input type="text" id="beasiswa_kini_tingkat" name="beasiswa_kini_tingkat" value="<?=$ls_beasiswa_kini_tingkat;?>" style="width:250px;background-color:#ffff99;" tabindex="8" maxlength="10" onblur="f_ajax_val_total_disetujui();">	
						<input type="hidden" id="beasiswa_kini_thn" name="beasiswa_kini_thn" value="<?=$ls_beasiswa_kini_thn;?>" style="width:250px;background-color:#ffff99;" maxlength="4">			
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Manfaat Beasiswa </label>
            <input type="text" id="beasiswa_kini_nom" name="beasiswa_kini_nom" value="<?=number_format((float)$ln_beasiswa_kini_nom,2,".",",");?>" maxlength="20" style="width:250px;text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>
													
				</fieldset>

				<fieldset style="width:750px;"><legend ><b><i><font color="#009999">Rapel Beasiswa yang Dibayarkan untuk Tahun Ajaran  </font><font size="2px;"><?=$ls_beasiswa_rapel_thn;?></font></i></b></legend>	          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jenis Beasiswa</label>
            <select size="1" id="beasiswa_rapel_jenis" name="beasiswa_rapel_jenis" value="<?=$ls_beasiswa_rapel_jenis;?>" tabindex="9" class="select_format" style="width:256px;" onChange="fl_js_span_jenjang_pendidikan_rapel();">
            <option value="">-- Pilih Jenis Beasiswa--</option>
            <? 
            $sql = "select a.kode, a.keterangan from sijstk.ms_lookup a where tipe='KLMJNSBEAS' order by seq ";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if ($row["KODE"]==$ls_beasiswa_rapel_jenis && strlen($ls_beasiswa_rapel_jenis)==strlen($row["KODE"])){ echo " selected"; }
            echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>																		 								
          </div>
          <div class="clear"></div>
					
					<span id="span_jenjang_pendidikan_rapel" style="display:none;">
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Jenjang Pendidikan </label>
              <select size="1" id="beasiswa_rapel_jenjang" name="beasiswa_rapel_jenjang" value="<?=$ls_beasiswa_rapel_jenjang;?>" tabindex="10" class="select_format" style="width:256px;" onChange="f_ajax_val_beasiswa_jenjang_pendidikan_rapel();f_ajax_val_total_disetujui();">
              <option value="">-- Pilih --</option>
              <? 
              $sql = "select a.kode, a.keterangan from sijstk.ms_lookup a where tipe='TKSKOLAH' order by seq ";
              $DB->parse($sql);
              $DB->execute();
              while($row = $DB->nextrow())
              {
              echo "<option ";
              if ($row["KODE"]==$ls_beasiswa_rapel_jenjang && strlen($ls_beasiswa_rapel_jenjang)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
              }
              ?>
              </select>       																			 								
            </div>
            <div class="clear"></div>
					</span>

					<div class="form-row_kiri">
          <label  style = "text-align:right;">Tingkat/Kelas</label>
            <input type="text" id="beasiswa_rapel_tingkat" name="beasiswa_rapel_tingkat" value="<?=$ls_beasiswa_rapel_tingkat;?>" tabindex="11" style="width:250px;" maxlength="10" onblur="f_ajax_val_total_disetujui();">	
						<input type="hidden" id="beasiswa_rapel_thn" name="beasiswa_rapel_thn" value="<?=$ls_beasiswa_rapel_thn;?>" style="width:250px;" maxlength="4">				
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Manfaat Beasiswa </label>
            <input type="text" id="beasiswa_rapel_nom" name="beasiswa_rapel_nom" value="<?=number_format((float)$ln_beasiswa_rapel_nom,2,".",",");?>" maxlength="20" style="width:250px;text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>
													
				</fieldset>
								
				</br>
				
				<fieldset style="width:750px;"><legend ><b><i><font color="#009999">Total Manfaat Beasiswa yang Disetujui</font></i></b></legend>					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Disetujui </label>
            <input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value="<?=number_format((float)$ln_nom_biaya_disetujui,2,".",",");?>" tabindex="12" maxlength="20" style="width:220px;text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>																
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Catatan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:220px;height:15px;" id="keterangan" name="keterangan" tabindex="13" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>

					<?
          echo "<script type=\"text/javascript\">fl_js_span_jenjang_pendidikan();</script>";
					echo "<script type=\"text/javascript\">fl_js_span_jenjang_pendidikan_rapel();</script>";
          ?>		
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
    					<input type="button" class="btn green" id="history" name="history" value="         HITORI PENERIMAAN MANFAAT BEASISWA          " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_beasiswatkihistory.php?task=view&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_beasiswa.php','Histori Penerimaan Manfaat Beasiswa',1050,600,'yes');" href="javascript:void(0);">
							<input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="            TUTUP            " />
              <?
  					}else
  					{
    					?>
							<input type="button" class="btn green" id="history" name="history" value="         HITORI PENERIMAAN MANFAAT BEASISWA          " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5041_penetapanmanfaat_beasiswatkihistory.php?task=view&kode_klaim=<?=$ls_kode_klaim;?>&kode_manfaat=<?=$ls_kode_manfaat;?>&form_penetapan=<?=$ls_form_penetapan;?>&sender=pn5041_penetapanmanfaat_beasiswa.php','Histori Penerimaan Manfaat Beasiswa',1050,600,'yes');" href="javascript:void(0);">
  						<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="            TUTUP            " />       					
              <?
  					}
  					?>
					</div>							 			 
        <? 					
        }
        ?>							
					</td>	
				</tr>
						 
			</table>
			

				
						 
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
			
<script type="text/javascript">		
//cek nik dari adminduk ----------------------------------------------------
    function cekExistNIK(){
				preload(true);
        var vKdKlaim = $('#kode_klaim').val();
				var vNik 		 = $('#beasiswa_nik_penerima').val();
				var vMnf 		 = $('#kode_manfaat').val();
				var vNo  		 = $('#no_urut').val();

				if (vNik!='' && vNik!=curr_nik_penerima)
				{
  				$.ajax(
          {
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_action.php?'+Math.random(),
            data: { TYPE:"cek_exist_nik",KODE_KLAIM:vKdKlaim,NIK:vNik,KODE_MANFAAT:vMnf,NO_URUT:vNo},
            success: function(data)
            {
              preload(false);
              jdata = JSON.parse(data);     
              if(jdata.success == true)
              {   
                  console.log(data);
									curr_nik_penerima = '';
  								cekValidasiAdminduk();
              }else{
                preload(false);
                console.log(data);
  							alert(jdata.msg);
								$('#beasiswa_nik_penerima').val('');
								$('#beasiswa_penerima').val('');
  							$('#beasiswa_tgllahir_penerima').val('');
  							$('#beasiswa_nik_penerima').focus();
								curr_nik_penerima = '';
              }
            }
          });
				}
				preload(false);
    }				

		//cek nik dari adminduk ----------------------------------------------------
    function cekValidasiAdminduk(){
				preload(true);		 
        var vNik = $('#beasiswa_nik_penerima').val();
				if (vNik!='' && vNik!=curr_nik_penerima)
				{
  				$.ajax(
          {
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_action.php?'+Math.random(),
            data: { TYPE:"cek_validasi_adminduk",NIK:vNik},
            success: function(data)
            {
              preload(false);
              jdata = JSON.parse(data);     
              if(jdata.success == true)
              {   
                  console.log(data);
                  $('#beasiswa_penerima').val(jdata.data['namaLengkap']);
  								$('#beasiswa_tgllahir_penerima').val(jdata.data['tanggalLahir']);
									curr_nik_penerima = vNik;
									
  								$('#nik_foto').attr('src','<?=$wsIp;?>'+jdata.data['showFoto']);
  								cekUsiaPenerimaBeasiswa();
              }else{
                preload(false);
                console.log(data);
  							alert(jdata.msg);
  							$('#beasiswa_penerima').focus();
								curr_nik_penerima = vNik;
  							//$('#nik_foto').attr('src','../../images/nopic.png');
              }
            }
          });
				}
				preload(false);
    }
		
		//cek kelayakan usia penerima beasiswa -------------------------------------
		function cekUsiaPenerimaBeasiswa(){
        var vTglLahir = $('#beasiswa_tgllahir_penerima').val();
				var vTglKejadianKlaim = $('#tgl_kejadian').val();
				
				if (vTglLahir!='' && vTglLahir!=curr_tgl_lahir)
				{
  				$.ajax(
          {
            type: 'POST',
            url: 'http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5040_action.php?'+Math.random(),
            data: { TYPE:"cek_usia_penerima_beasiswa",TGL_LAHIR:vTglLahir,TGL_KEJADIAN:vTglKejadianKlaim},
            success: function(data)
            {
              preload(false);
              jdata = JSON.parse(data);     
              if(jdata.success == true)
              {   
                  console.log(data);
									window.document.getElementById("beasiswa_flag_masih_sekolah").value = "Y";
									window.document.getElementById("beasiswa_flag_masih_sekolah").checked = "true";
  								curr_tgl_lahir = vTglLahir;
              }else{
                preload(false);
                console.log(data);
  							alert(jdata.msg);
								
  							$('#beasiswa_tgllahir_penerima').val('');
  							$('#beasiswa_tgllahir_penerima').focus();
								window.document.getElementById("beasiswa_flag_masih_sekolah").value = "T";
								
              }
            }
          });
				}
    }
</script>
  
							
				