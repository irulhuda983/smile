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
  $sql = "select a.nama_manfaat, a.kategori_manfaat, b.kd_prg, c.kode_perlindungan, c.kode_segmen, c.kode_klaim_induk, ".
         "to_char(c.tgl_kejadian,'DD/MM/YYYY') tgl_kejadian, to_char(c.tgl_kondisi_terakhir,'DD/MM/YYYY') tgl_kondisi_terakhir ".
			 	 "from sijstk.pn_kode_manfaat a, sijstk.pn_klaim_manfaat b, sijstk.pn_klaim c ".
         "where a.kode_manfaat = b.kode_manfaat and b.kode_klaim = c.kode_klaim ".
				 "and b.kode_klaim = '$ls_kode_klaim' ". 
				 "and b.kode_manfaat = '$ls_kode_manfaat'";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat			    = $row['NAMA_MANFAAT'];
	$ls_kategori_manfaat      = $row['KATEGORI_MANFAAT'];
	$ls_kd_prg						    = $row['KD_PRG'];	
	$ls_kode_perlindungan	    = $row['KODE_PERLINDUNGAN'];	
	$ls_kode_segmen				    = $row['KODE_SEGMEN'];
  $ls_tgl_kejadian          = $row['TGL_KEJADIAN'];
  $ls_tgl_kondisi_terakhir  = $row['TGL_KONDISI_TERAKHIR'];
  $ls_kode_klaim_induk      = $row['KODE_KLAIM_INDUK'];
  

  if($ls_kode_klaim_induk !=''){
    $sql="select TO_CHAR(homecare_tgl_rekomendasi,'DD/MM/YYYY') homecare_tgl_rekomendasi from pn.pn_klaim_manfaat_detil where kode_klaim='$ls_kode_klaim_induk'";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ld_homecare_tgl_rekomendasi_klaim_induk	= $row['HOMECARE_TGL_REKOMENDASI'];
    
  }
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")";	 		  	 
}
		
$ls_kode_tipe_penerima	= $_POST['kode_tipe_penerima'];
$ld_homecare_tgl_awal		= $_POST['homecare_tgl_awal'];
$ld_homecare_tgl_akhir	= $_POST['homecare_tgl_akhir'];
$ld_homecare_tgl_rekomendasi = $_POST['homecare_tgl_rekomendasi'];
$ln_nom_biaya_diajukan	= str_replace(',','',$_POST['nom_biaya_diajukan']);
$ln_nom_biaya_diverifikasi	= str_replace(',','',$_POST['nom_biaya_diverifikasi']);
$ln_nom_biaya_disetujui	= str_replace(',','',$_POST['nom_biaya_disetujui']);
$ls_keterangan 					= $_POST['keterangan'];

//bug fixing homecare
if ($ls_kategori_manfaat=="TAMBAHAN")
{
 	 $ln_nom_manfaat_utama = 0;
	 $ln_nom_manfaat_tambahan = $ln_nom_biaya_disetujui;
}else
{
 	 $ln_nom_manfaat_utama = $ln_nom_biaya_disetujui;
	 $ln_nom_manfaat_tambahan = 0;
}
$ln_nom_manfaat_gross = (toNumber($ln_nom_manfaat_utama)+toNumber($ln_nom_manfaat_tambahan));
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
      var nom_biaya_diajukan = form.nom_biaya_diajukan.value;
      var nom_biaya_diajukan_int = parseFloat(nom_biaya_diajukan.replaceAll(",",""));

      var nom_biaya_diverifikasi = form.nom_biaya_diverifikasi.value;
      var nom_biaya_diverifikasi_int = parseFloat(nom_biaya_diverifikasi.replaceAll(",",""));
     
      if(form.kode_tipe_penerima.value==""){
        form.kode_tipe_penerima.focus();
				alert('Tipe Penerima tidak boleh kosong...!!!');
      }else if(form.homecare_tgl_awal.value=="" || form.homecare_tgl_akhir.value==""){
        form.homecare_tgl_awal.focus();
				alert('Tgl Awal dan Akhir tidak boleh kosong, Perbaiki data input...!!!');				
      }else if(form.homecare_tgl_rekomendasi.value==""){
        form.homecare_tgl_rekomendasi.focus();
				alert('Tgl Rekomendasi Homecare tidak boleh kosong, Perbaiki data input...!!!');				
      }else if(form.st_errval1.value=="1" || form.st_errval2.value=="1" || form.st_errval3.value=="1" || form.st_errval4.value=="1" || form.st_errval5.value=="1" || form.st_errval6.value=="1" || form.st_errval7.value=="1"){
        form.homecare_tgl_awal.focus();
				alert('Perbaiki data input...!!!');
      }else if(form.st_errval8.value=="1" || form.st_errval9.value=="1"){
        form.homecare_tgl_rekomendasi.focus();
				alert('Perbaiki data input...!!!');
      }else if(form.st_errval10.value=="1" || form.st_errval11.value=="1" || form.st_errval12.value=="1"){
        form.homecare_tgl_rekomendasi.focus();
				alert('Perbaiki data input...!!!');
      }else if(nom_biaya_diverifikasi_int > nom_biaya_diajukan_int){
        form.nom_biaya_diajukan.focus();
				alert('Nominal biaya diverifikasi tidak boleh lebih besar dari nominal biaya diajukan...!!!');
      }else
  		{
         form.tombol.value="simpan";
         form.submit(); 		 ;
  		}								 
  	}		
  </script>														
</head>
<body>
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	
	<?
  if($_POST['tombol']=="simpan")
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
							 "	homecare_tgl_awal, homecare_tgl_akhir, homecare_tgl_rekomendasi, ".
							 "	nom_biaya_diajukan, nom_biaya_diverifikasi, nom_biaya_disetujui, ".
               "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ". 
							 "	keterangan, tgl_rekam, petugas_rekam)   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut','$ls_kode_manfaat_detil','$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	to_date('$ld_homecare_tgl_awal','dd/mm/yyyy'), to_date('$ld_homecare_tgl_akhir','dd/mm/yyyy'), to_date('$ld_homecare_tgl_rekomendasi','dd/mm/yyyy'), ".
							 "	'$ln_nom_biaya_diajukan', '$ln_nom_biaya_diverifikasi', '$ln_nom_biaya_disetujui', ". 
               "	'$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', '$ln_nom_manfaat_gross', ". 
							 "  '$ln_nom_pph', '$ln_nom_pembulatan', '$ln_nom_manfaat_netto', ".
               "	'$ls_keterangan', sysdate, '$username' ".
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
						 	 "	kode_manfaat_detil			= '$ls_kode_manfaat_detil', ".		 
               "	kategori_manfaat 	 			= '$ls_kategori_manfaat', ".
							 "	kode_tipe_penerima 	 		= '$ls_kode_tipe_penerima', ".
							 "	kd_prg						 	 		= '$ls_kd_prg', ". 
							 "	homecare_tgl_awal				= to_date('$ld_homecare_tgl_awal','dd/mm/yyyy'), ". 
							 "	homecare_tgl_akhir			= to_date('$ld_homecare_tgl_akhir','dd/mm/yyyy'), ".
               "	homecare_tgl_rekomendasi = to_date('$ld_homecare_tgl_rekomendasi','dd/mm/yyyy'), ". 
							 "	nom_biaya_diajukan			= '$ln_nom_biaya_diajukan', ". 
							 "	nom_biaya_diverifikasi	= '$ln_nom_biaya_diverifikasi', ". 
							 "	nom_biaya_disetujui			= '$ln_nom_biaya_disetujui', ".
               "	nom_manfaat_utama				= '$ln_nom_manfaat_utama', ". 
							 "	nom_manfaat_tambahan		= '$ln_nom_manfaat_tambahan', ". 
							 "	nom_manfaat_gross				= '$ln_nom_manfaat_gross', ". 
							 "  nom_pph									= '$ln_nom_pph', ". 
							 "	nom_pembulatan					= '$ln_nom_pembulatan', ". 
							 "	nom_manfaat_netto				= '$ln_nom_manfaat_netto', ".					 
							 "	keterangan							= '$ls_keterangan', ". 
							 "	tgl_ubah								= sysdate, ". 
							 "	petugas_ubah						= '$username' ".
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
									kode_tipe_penerima, kd_prg, nom_biaya_diajukan, nom_biaya_diverifikasi, nom_biaya_disetujui, 
									to_char(homecare_tgl_awal,'dd/mm/yyyy') homecare_tgl_awal, to_char(homecare_tgl_akhir,'dd/mm/yyyy') homecare_tgl_akhir, 
									to_char(homecare_tgl_rekomendasi,'dd/mm/yyyy') homecare_tgl_rekomendasi, nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
                  nom_pph, nom_pembulatan, nom_manfaat_netto, keterangan
              from sijstk.pn_klaim_manfaat_detil
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
			$ln_nom_biaya_diverifikasi	= $data["NOM_BIAYA_DIVERIFIKASI"];
      $ln_nom_biaya_disetujui 		= $data["NOM_BIAYA_DISETUJUI"]; 
     	$ld_homecare_tgl_awal				= $data["HOMECARE_TGL_AWAL"];
      $ld_homecare_tgl_akhir			= $data["HOMECARE_TGL_AKHIR"];
      $ld_homecare_tgl_rekomendasi			= $data["HOMECARE_TGL_REKOMENDASI"];
      $ln_nom_manfaat_utama				= $data["NOM_MANFAAT_UTAMA"];
      $ln_nom_manfaat_tambahan		= $data["NOM_MANFAAT_TAMBAHAN"];
      $ln_nom_manfaat_gross 			= $data["NOM_MANFAAT_GROSS"];
      $ln_nom_pph									= $data["NOM_PPH"];
      $ln_nom_pembulatan					= $data["NOM_PEMBULATAN"];
      $ln_nom_manfaat_netto				= $data["NOM_MANFAAT_NETTO"];
      $ls_keterangan							= $data["KETERANGAN"];												
    }else
		{
		 	//initial value ----------------------------------------------------------    		 
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
		var curr_nom_biaya_diajukan =<?php echo ($ln_nom_biaya_diajukan=='') ? 'false' : "'".$ln_nom_biaya_diajukan."'"; ?>;
		var curr_homecare_tgl_awal =<?php echo ($ld_homecare_tgl_awal=='') ? 'false' : "'".$ld_homecare_tgl_awal."'"; ?>;
		var curr_homecare_tgl_akhir =<?php echo ($ld_homecare_tgl_akhir=='') ? 'false' : "'".$ld_homecare_tgl_akhir."'"; ?>;				
		var curr_nom_biaya_diverifikasi =<?php echo ($ln_nom_biaya_diverifikasi=='') ? 'false' : "'".$ln_nom_biaya_diverifikasi."'"; ?>;
						
    //validasi periode homecare ------------------------------------------------
		function f_ajax_val_periode_homecare()
		{
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			c_homecare_tgl_awal  = window.document.getElementById('homecare_tgl_awal').value;
			c_homecare_tgl_akhir = window.document.getElementById('homecare_tgl_akhir').value;
      

			if (c_homecare_tgl_awal!=curr_homecare_tgl_awal || c_homecare_tgl_akhir!= curr_homecare_tgl_akhir)
			{
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_periode_homecare&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_no_urut='+c_no_urut+'&c_homecare_tgl_awal='+c_homecare_tgl_awal+'&c_homecare_tgl_akhir='+c_homecare_tgl_akhir;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_homecare_tgl_awal  = c_homecare_tgl_awal;
  			curr_homecare_tgl_akhir = c_homecare_tgl_akhir; 
			}  
      
		}

    function f_ajax_val_periode_homecare2(){
      c_homecare_tgl_awal  = window.document.getElementById('homecare_tgl_awal').value;
			c_homecare_tgl_akhir = window.document.getElementById('homecare_tgl_akhir').value;
      c_homecare_tgl_rekomendasi = window.document.getElementById('homecare_tgl_rekomendasi').value;
      c_tgl_kondisi_terakhir = "<?php echo $ls_tgl_kondisi_terakhir; ?>";

      var formObj = document.fpop;
      var st_errorval10 = window.document.getElementById("st_errval10").value;
      var st_errorval11 = window.document.getElementById("st_errval11").value;
      var st_errorval12 = window.document.getElementById("st_errval12").value;

      if (st_errorval10 == 1)
      {  
        window.document.getElementById("dispError10").innerHTML = "(* Tanggal awal periode homecare tidak boleh di bawah tanggal rekomendasi homecare, harap cek data input ..!!!";
        window.document.getElementById("dispError10").style.display = 'block';
      }else{
        window.document.getElementById("dispError10").style.display = 'none';
      }

      if (st_errorval11 == 1)
      {  
        window.document.getElementById("dispError11").innerHTML = "(* Tanggal akhir periode homecare tidak boleh lebih dari 1 tahun dari tanggal rekomendasi homecare, harap cek data input ..!!!";
        window.document.getElementById("dispError11").style.display = 'block';
      }else{
        window.document.getElementById("dispError11").style.display = 'none';
      }

      if (st_errorval12 == 1)
      {  
        window.document.getElementById("dispError12").innerHTML = "(* Silahkan input data tanggal rekomendasi homecare terlebih dahulu.";
        window.document.getElementById("dispError12").style.display = 'block';
      }else{
        window.document.getElementById("dispError12").style.display = 'none';
      }


      var date_parts_c_homecare_tgl_awal = c_homecare_tgl_awal.split("/");	
      var date_object_c_homecare_tgl_awal = new Date(+date_parts_c_homecare_tgl_awal[2], date_parts_c_homecare_tgl_awal[1] - 1, +date_parts_c_homecare_tgl_awal[0]);

      var date_parts_c_homecare_tgl_akhir = c_homecare_tgl_akhir.split("/");	
      var date_object_c_homecare_tgl_akhir = new Date(+date_parts_c_homecare_tgl_akhir[2], date_parts_c_homecare_tgl_akhir[1] - 1, +date_parts_c_homecare_tgl_akhir[0]);

      var date_parts_c_homecare_tgl_rekomendasi = c_homecare_tgl_rekomendasi.split("/");
      var date_object_c_homecare_tgl_rekomendasi = new Date(+date_parts_c_homecare_tgl_rekomendasi[2], date_parts_c_homecare_tgl_rekomendasi[1] - 1, +date_parts_c_homecare_tgl_rekomendasi[0]);

      var date_parts_c_tgl_kondisi_terakhir = c_tgl_kondisi_terakhir.split("/");	
      var date_object_c_tgl_kondisi_terakhir = new Date(+date_parts_c_tgl_kondisi_terakhir[2], date_parts_c_tgl_kondisi_terakhir[1] - 1, +date_parts_c_tgl_kondisi_terakhir[0]);

      var date_parts_c_homecare_tgl_rekomendasi2 = c_homecare_tgl_rekomendasi.split("/");
      var date_object_c_homecare_tgl_rekomendasi2 = new Date(+date_parts_c_homecare_tgl_rekomendasi2[2], date_parts_c_homecare_tgl_rekomendasi2[1] - 1, +date_parts_c_homecare_tgl_rekomendasi2[0]);
      var date_object_c_homecare_tgl_rekomendasi_one_year_togo = new Date(date_object_c_homecare_tgl_rekomendasi2.setFullYear(date_object_c_homecare_tgl_rekomendasi2.getFullYear() + 1));

      if(c_homecare_tgl_rekomendasi==''){
        window.document.getElementById("homecare_tgl_awal").value='';
        window.document.getElementById("homecare_tgl_akhir").value='';
        formObj.st_errval12.value = '1';
      }else{
        formObj.st_errval12.value = '0';       
      }

      if(date_object_c_homecare_tgl_awal < date_object_c_homecare_tgl_rekomendasi){
        formObj.st_errval10.value = '1';
        
      }else{
        formObj.st_errval10.value = '0';
        
      }

      if(date_object_c_homecare_tgl_akhir > date_object_c_homecare_tgl_rekomendasi_one_year_togo){
        formObj.st_errval11.value = '1';
      }else{
        formObj.st_errval11.value = '0';
      }

    }

    //validasi tgl rekomendasi homecare ------------------------------------------------
		function f_ajax_val_tgl_rekomendasi_homecare()
		{
      var formObj = document.fpop;
    
      var st_errorval8 = window.document.getElementById("st_errval8").value;
      var st_errorval9 = window.document.getElementById("st_errval9").value;

      if (st_errorval8 == 1)
      {  
        window.document.getElementById("dispError8").innerHTML = "(* Tanggal rekomendasi homecare tidak boleh di bawah tanggal kejadian, harap cek data input ..!!!";
        window.document.getElementById("dispError8").style.display = 'block';
      }else{
        window.document.getElementById("dispError8").style.display = 'none';
      }
      if (st_errorval9 == 1)
      {  
        window.document.getElementById("dispError9").innerHTML = "(* Tanggal rekomendasi homecare tidak boleh di atas tanggal kondisi terakhir, harap cek data input ..!!!";
        window.document.getElementById("dispError9").style.display = 'block';
      }else{
        window.document.getElementById("dispError9").style.display = 'none';
      }

      let tgl_kejadian = "<?php echo $ls_tgl_kejadian; ?>";
      let tgl_kondisi_terakhir = "<?php echo $ls_tgl_kondisi_terakhir; ?>";
			let homecare_tgl_rekomendasi = window.document.getElementById('homecare_tgl_rekomendasi').value;
			
      var date_parts_tgl_kejadian = tgl_kejadian.split("/");	
      var date_object_tgl_kejadian = new Date(+date_parts_tgl_kejadian[2], date_parts_tgl_kejadian[1] - 1, +date_parts_tgl_kejadian[0]); 
      var date_parts_tgl_kondisi_terakhir = tgl_kondisi_terakhir.split("/");	
      var date_object_tgl_kondisi_terakhir = new Date(+date_parts_tgl_kondisi_terakhir[2], date_parts_tgl_kondisi_terakhir[1] - 1, +date_parts_tgl_kondisi_terakhir[0]); 
      var date_parts_homecare_tgl_rekomendasi = homecare_tgl_rekomendasi.split("/");
      var date_object_homecare_tgl_rekomendasi = new Date(+date_parts_homecare_tgl_rekomendasi[2], date_parts_homecare_tgl_rekomendasi[1] - 1, +date_parts_homecare_tgl_rekomendasi[0]);
      
      if(date_object_homecare_tgl_rekomendasi <= date_object_tgl_kejadian ){
        formObj.st_errval8.value = '1';
      }else{
        formObj.st_errval8.value = '0';
      }

      if(date_object_homecare_tgl_rekomendasi > date_object_tgl_kondisi_terakhir ){
        formObj.st_errval9.value = '1';
      }else{
        formObj.st_errval9.value = '0';
      }
      
      
		}

		function f_ajax_val_nom_biaya_diajukan()
		{
		 	v_nom_biaya_diajukan = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diajukan').value),2); 

			if (v_nom_biaya_diajukan != curr_nom_biaya_diajukan)
			{
			 	document.fpop.nom_biaya_diverifikasi.value = format_uang(v_nom_biaya_diajukan); 
				curr_nom_biaya_diajukan = v_nom_biaya_diajukan;	
				//curr_nom_biaya_diverifikasi = v_nom_biaya_diajukan;				
				f_ajax_val_hitung_manfaat(); 
			}
					 
		}
						
    //hitung nilai manfaat -----------------------------------------------------
    function f_ajax_val_hitung_manfaat()
    {		 	
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_nom_biaya_diverifikasi = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diverifikasi').value),2);     
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			c_homecare_tgl_awal  = window.document.getElementById('homecare_tgl_awal').value;
			c_homecare_tgl_akhir = window.document.getElementById('homecare_tgl_akhir').value;
			
			if (c_nom_biaya_diverifikasi!=curr_nom_biaya_diverifikasi)
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_homecare&c_kode_manfaat='+c_kode_manfaat+'&c_nom_biaya_diverifikasi='+c_nom_biaya_diverifikasi+'&c_kode_klaim='+c_kode_klaim+'&c_no_urut='+c_no_urut+'&c_homecare_tgl_awal='+c_homecare_tgl_awal+'&c_homecare_tgl_akhir='+c_homecare_tgl_akhir;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
  			curr_nom_biaya_diverifikasi = c_nom_biaya_diverifikasi;
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
      var st_errorval2 = window.document.getElementById("st_errval2").value;
			var st_errorval3 = window.document.getElementById("st_errval3").value;
      var st_errorval4 = window.document.getElementById("st_errval4").value;
      var st_errorval5 = window.document.getElementById("st_errval5").value;
			var st_errorval6 = window.document.getElementById("st_errval6").value;
			var st_errorval7 = window.document.getElementById("st_errval7").value;
			
      //tampilan error jika nama perusahaan sudah terdaftar --------------  					
      if (st_errorval1 == 1)
      {  
        window.document.getElementById("dispError1").innerHTML = "(* Tgl Awal Homecare sudah pernah diinput dalam range tgl awal dan tgl akhir penetapan sebelumnya, harap cek data input..!!!";
        window.document.getElementById("dispError1").style.display = 'block';
        window.document.getElementById('homecare_tgl_awal').focus();
      }else{
      	window.document.getElementById("dispError1").style.display = 'none';
      } 
			
      if (st_errorval2 == 1)
      {  
        window.document.getElementById("dispError2").innerHTML = "(* Tgl Akhir Homecare sudah pernah diinput dalam range tgl awal dan tgl akhir penetapan sebelumnya, harap cek data input..!!!";
        window.document.getElementById("dispError2").style.display = 'block';
        window.document.getElementById('homecare_tgl_akhir').focus();
      }else{
      	window.document.getElementById("dispError2").style.display = 'none';
      } 	
			
      if (st_errorval3 == 1)
      {  
        window.document.getElementById("dispError3").innerHTML = "(* Tgl Awal/Akhir Homecare Penetapan Sebelumnya sudah pernah diinput dalam range tgl awal dan tgl akhir yang sedang dientry, harap cek data input..!!!";
        window.document.getElementById("dispError3").style.display = 'block';
        window.document.getElementById('homecare_tgl_akhir').focus();
      }else{
      	window.document.getElementById("dispError3").style.display = 'none';
      }	

      //Penambahan validasi smileberulang 29-11-2019
			//update 22/12/2019 sesuai info dari kop bahwa syaratnya adalah:
			//Tanggal awal Homecare >= tanggal kejadian  ---valid
			//Tanggal akhir Homecare <= tanggal kondisi akhir ---valid
      //Penambahan pengecheckan untuk tgl awal Homecare tidak boleh lebih kecil dari tgl kejadian
      if (st_errorval4 == 1)
      {  
        window.document.getElementById("dispError4").innerHTML = "(* Tgl Awal Homecare tidak boleh lebih kecil dari tanggal kejadian, harap cek data input ..!!!";
        window.document.getElementById("dispError4").style.display = 'block';
        window.document.getElementById('homecare_tgl_awal').focus();
      }else{
        window.document.getElementById("dispError4").style.display = 'none';
      }
      //Penambahan pengecheckan untuk tgl_akhir_homecare tidak boleh lebih besar dari tgl_kondisi akhir
      if (st_errorval5 == 1)
      {  
        window.document.getElementById("dispError5").innerHTML = "(* Tgl Akhir Homecare tidak boleh lebih besar dari tanggal kondisi akhir, harap cek data input ..!!!";
        window.document.getElementById("dispError5").style.display = 'block';
        window.document.getElementById('homecare_tgl_akhir').focus();
      }else{
        window.document.getElementById("dispError5").style.display = 'none';
      } 	
      //Penambahan pengecheckan Tgl Akhir Homecare tidak boleh lebih kecil dari tanggal awal
      if (st_errorval6 == 1)
      {  
        window.document.getElementById("dispError6").innerHTML = "(* Tgl Akhir Homecare tidak boleh lebih kecil dari tanggal awal, harap cek data input ..!!!";
        window.document.getElementById("dispError6").style.display = 'block';
        window.document.getElementById('homecare_tgl_akhir').focus();
      }else{
        window.document.getElementById("dispError6").style.display = 'none';
      }
			//data input tidak valid
      if (st_errorval7 == 1)
      {  
        window.document.getElementById("dispError7").innerHTML = "(* data input tidak valid, harap cek data input ..!!!";
        window.document.getElementById("dispError7").style.display = 'block';
        window.document.getElementById('nom_biaya_diverifikasi').focus();
      }else{
        window.document.getElementById("dispError7").style.display = 'none';
      }
      

      //-----------------------------------------	end smileberulang ------	      																																						 						 																														
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
		<br>									
		<div id="formframe" style="width:900px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">	
			<span id="dispError2" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval2" name="st_errval2">	
			<span id="dispError3" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval3" name="st_errval3">			
      <span id="dispError4" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval4" name="st_errval4">
      <span id="dispError5" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval5" name="st_errval5">
			<span id="dispError6" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval6" name="st_errval6">
			<span id="dispError7" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval7" name="st_errval7">
      <span id="dispError8" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval8" name="st_errval8">
			<span id="dispError9" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval9" name="st_errval9">
      <span id="dispError10" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval10" name="st_errval10">
      <span id="dispError11" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval11" name="st_errval11">
      <span id="dispError12" style="display:none;color:red"></span>
      <input type="hidden" id="st_errval12" name="st_errval12">     
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>">
			<input type="hidden" id="kode_perlindungan" name="kode_perlindungan" value="<?=$ls_kode_perlindungan;?>">
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="no_urut" name="no_urut" value="<?=$ln_no_urut;?>">
			<input type="hidden" id="kategori_manfaat" name="kategori_manfaat" value="<?=$ls_kategori_manfaat;?>">
			<input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>">
      <input type="hidden" id="tombol" name="tombol">  
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
			<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
    	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender2" name="sender2" value="<?=$ls_sender2;?>">
      <input type="hidden" id="sender_activetab2" name="sender_activetab2" value="<?=$ls_sender_activetab2;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
			
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:750px;"><legend >Entry Rincian Manfaat Homecare</legend>
          </br>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tipe Penerima *</label>
            <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:310px;background-color:#ffff99;">
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
          
          <?php if($ls_kode_klaim_induk=='') { ?>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Rekomendasi Homecare</label>
            <input type="text" id="homecare_tgl_rekomendasi" name="homecare_tgl_rekomendasi" value="<?=$ld_homecare_tgl_rekomendasi;?>" size="15" maxlength="10" tabindex="2" style="background-color:#ffff99;" onblur="convert_date(homecare_tgl_rekomendasi);f_ajax_val_tgl_rekomendasi_homecare();" onfocus="f_ajax_val_tgl_rekomendasi_homecare();">
            <input id="btn_homecare_tgl_rekomendasi" type="image" align="top" onclick="return showCalendar('homecare_tgl_rekomendasi', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Rekomendasi"/>	   							
					</div>																																															
          <div class="clear"></div>
          <?php } else { if($ld_homecare_tgl_rekomendasi_klaim_induk=='') { ?>
            <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Rekomendasi Homecare</label>
            <input type="text" id="homecare_tgl_rekomendasi" name="homecare_tgl_rekomendasi" value="<?=$ld_homecare_tgl_rekomendasi;?>" size="15" maxlength="10" tabindex="2" style="background-color:#ffff99;" onblur="convert_date(homecare_tgl_rekomendasi);f_ajax_val_tgl_rekomendasi_homecare();" onfocus="f_ajax_val_tgl_rekomendasi_homecare();">
            <input id="btn_homecare_tgl_rekomendasi" type="image" align="top" onclick="return showCalendar('homecare_tgl_rekomendasi', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Rekomendasi"/>	   							
					</div>																																															
          <div class="clear"></div>
          <?php } else { ?>
            <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Rekomendasi Homecare</label>
            <input type="text" id="homecare_tgl_rekomendasi" name="homecare_tgl_rekomendasi" value="<?=$ld_homecare_tgl_rekomendasi_klaim_induk;?>" size="15" maxlength="10" tabindex="2" class="disabled" readonly onblur="convert_date(homecare_tgl_rekomendasi);f_ajax_val_tgl_rekomendasi_homecare();" onfocus="f_ajax_val_tgl_rekomendasi_homecare();">
            <input id="btn_homecare_tgl_rekomendasi" type="image" align="top" onclick="return showCalendar('homecare_tgl_rekomendasi', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Rekomendasi" disabled/>	   							
					</div>																																															
          <div class="clear"></div>
          <?php } } ?>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Periode Pelaksanaan Homecare</label>
            <input type="text" id="homecare_tgl_awal" name="homecare_tgl_awal" value="<?=$ld_homecare_tgl_awal;?>" size="15" maxlength="10" tabindex="2" onblur="convert_date(homecare_tgl_awal);f_ajax_val_periode_homecare();f_ajax_val_periode_homecare2();" onfocus="f_ajax_val_periode_homecare();f_ajax_val_periode_homecare2();" style="background-color:#ffff99;">
            <input id="btn_homecare_tgl_awal" type="image" align="top" onclick="return showCalendar('homecare_tgl_awal', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Awal Homecare" />	   							
          	&nbsp;s/d&nbsp;
						<input type="text" id="homecare_tgl_akhir" name="homecare_tgl_akhir" value="<?=$ld_homecare_tgl_akhir;?>" size="15" maxlength="10" tabindex="3" onblur="convert_date(homecare_tgl_akhir);f_ajax_val_periode_homecare();f_ajax_val_periode_homecare2();" onfocus="f_ajax_val_periode_homecare();f_ajax_val_periode_homecare2();" style="background-color:#ffff99;">
            <input id="btn_homecare_tgl_akhir" type="image" align="top" onclick="return showCalendar('homecare_tgl_akhir', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Akhir Homecare" />
					</div>																																															
          <div class="clear"></div>
															
					</br>					

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Diajukan </label>
            <input type="text" id="nom_biaya_diajukan" name="nom_biaya_diajukan" value="<?=number_format((float)$ln_nom_biaya_diajukan,2,".",",");?>" tabindex="6" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value); f_ajax_val_nom_biaya_diajukan();">				
          </div>																		
          <div class="clear"></div>
					
					<div class="form-row_kiri">
          <label  style = "text-align:right;">Total Diverifikasi </label>
            <input type="text" id="nom_biaya_diverifikasi" name="nom_biaya_diverifikasi" value="<?=number_format((float)$ln_nom_biaya_diverifikasi,2,".",",");?>" tabindex="7" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_manfaat();">				
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Disetujui </label>
            <input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value="<?=number_format((float)$ln_nom_biaya_disetujui,2,".",",");?>" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>
					
					</br>
																	
          <div class="form-row_kiri">
          <label style = "text-align:right;">Catatan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:260px" id="keterangan" name="keterangan" tabindex="9" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>
					
					</br>	
				</fieldset>	
								
        <? 					
        if(!empty($ls_kode_manfaat))
        {
        ?>			 	
          <div id="buttonbox" style="width:760px;text-align:center;">        			 
          <?
					if ($ls_task!="view")
					{
  					?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="               SIMPAN               " onClick="fl_js_val_simpan();">
          	<input type="button" class="btn green" id="close" name="close" onclick="refreshParent();" value="               TUTUP               " />
            <?
					}else
					{
  					?>
  					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="               TUTUP               " />       					
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