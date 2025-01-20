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
$ls_sender_mid 						= 'pn5041_penetapanmanfaat_tkivokasionalentry.php';

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
  $sql = "select a.nama_manfaat, a.kategori_manfaat, b.kd_prg, c.kode_perlindungan, c.kode_segmen,to_char(c.tgl_kejadian,'dd/mm/yyyy') tgl_kejadian ".
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
	$ld_tgl_kejadian			= $row['TGL_KEJADIAN'];
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}
		
$ls_kode_tipe_penerima			 = $_POST['kode_tipe_penerima'];
$ls_vokasional_jenis 				 = $_POST['vokasional_jenis'];
$ls_vokasional_jenis_lainnya = $_POST['vokasional_jenis_lainnya'];
$ls_vokasional_plkb 				 = $_POST['vokasional_plkb'];
$ld_vokasional_tgl_awal 		 = $_POST['vokasional_tgl_awal'];
$ld_vokasional_tgl_akhir 		 = $_POST['vokasional_tgl_akhir'];

$ln_nom_biaya_diajukan	= str_replace(',','',$_POST['nom_biaya_diajukan']);														
$ln_nom_biaya_disetujui	= str_replace(',','',$_POST['nom_biaya_disetujui']);
$ls_keterangan 					= $_POST['keterangan'];	

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
      var nom_biaya_diajukan = form.nom_biaya_diajukan.value;
      var nom_biaya_diajukan_int = parseFloat(nom_biaya_diajukan.replaceAll(",",""));
      var nom_biaya_disetujui = form.nom_biaya_disetujui.value;
      var nom_biaya_disetujui_int = parseFloat(nom_biaya_disetujui.replaceAll(",",""));

      if(form.kode_tipe_penerima.value==""){
        alert('Tipe Penerima tidak boleh kosong...!!!');
        form.kode_tipe_penerima.focus();
      }else if(form.vokasional_jenis.value==""){
        alert('Jenis pelatihan tidak boleh kosong...!!!');
        form.vokasional_jenis.focus();
      }else if(form.vokasional_jenis.value=="VOK99" && form.vokasional_jenis_lainnya.value==""){
        alert('Untuk Jenis Pelatihan Lain-Lain maka DETIL informasi jenis pelatihan tidak boleh kosong...!!!');
        form.vokasional_jenis_lainnya.focus();
      }else if(form.vokasional_plkb.value==""){
        alert('Nama PLKB/BLK tidak boleh kosong...!!!');
        form.vokasional_plkb.focus();
      }else if(form.vokasional_tgl_awal.value==""){
        alert('Tgl mulai pelatihan tidak boleh kosong...!!!');
        form.vokasional_tgl_awal.focus();		
      }else if(form.vokasional_tgl_akhir.value==""){
        alert('Tgl akhir pelatihan tidak boleh kosong...!!!');
        form.vokasional_tgl_akhir.focus();											
			}else if(nom_biaya_disetujui_int > nom_biaya_diajukan_int){
        form.nom_biaya_diajukan.focus();
				alert('Nominal biaya disetujui tidak boleh lebih besar dari nominal biaya diajukan...!!!');
      }else
  		{
         form.tombol.value="simpan";
         form.submit(); 		 
  		}								 
  	}		
  </script>	
	
  <script language="javascript">
    function fl_js_set_span_vokasional_lainnya() 
    { 
      var	v_jenis_vokasional = window.document.getElementById("vokasional_jenis").value;
      
      if (v_jenis_vokasional =="VOK99") //lain-lain ----
      {    
      	window.document.getElementById("span_jenis_lainnya").style.display = 'block';
      }else
      {
        window.document.getElementById("span_jenis_lainnya").style.display = 'none';
        window.document.getElementById("vokasional_jenis_lainnya").value = "";
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
							 "	rehab_jenis_pelayanan, ". 
							 "	nom_biaya_diajukan, nom_biaya_disetujui, ".
							 "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ".						 
               "	keterangan, tgl_rekam, petugas_rekam, ".
							 "	vokasional_jenis, vokasional_jenis_lainnya, vokasional_plkb, vokasional_tgl_awal, vokasional_tgl_akhir  ".
							 ")   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut','$ls_kode_manfaat_detil','$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	'$ls_rehab_jenis_pelayanan',". 
               "	'$ln_nom_biaya_diajukan', '$ln_nom_biaya_disetujui', ".
							 "	'$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', '$ln_nom_manfaat_gross', ". 
							 "  '$ln_nom_pph', '$ln_nom_pembulatan', '$ln_nom_manfaat_netto', ".
							 "	'$ls_keterangan', sysdate, '$username', ".
							 "	'$ls_vokasional_jenis','$ls_vokasional_jenis_lainnya','$ls_vokasional_plkb', ".
							 "	to_date('$ld_vokasional_tgl_awal','dd/mm/yyyy'), to_date('$ld_vokasional_tgl_akhir','dd/mm/yyyy') ".
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
							 "	rehab_jenis_pelayanan		= '$ls_rehab_jenis_pelayanan' , ".
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
							 "	vokasional_jenis				= '$ls_vokasional_jenis', ".
							 "	vokasional_jenis_lainnya = '$ls_vokasional_jenis_lainnya', ".
							 "	vokasional_plkb					= '$ls_vokasional_plkb', ".
							 "	vokasional_tgl_awal			= to_date('$ld_vokasional_tgl_awal','dd/mm/yyyy'), ".
							 "	vokasional_tgl_akhir 		= to_date('$ld_vokasional_tgl_akhir','dd/mm/yyyy') ".
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
									rehab_jenis_pelayanan,
									nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
                  nom_pph, nom_pembulatan, nom_manfaat_netto, keterangan,
									vokasional_jenis, vokasional_jenis_lainnya, vokasional_plkb, 
									to_char(vokasional_tgl_awal,'dd/mm/yyyy') vokasional_tgl_awal, 
									to_char(vokasional_tgl_akhir,'dd/mm/yyyy') vokasional_tgl_akhir
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
      $ln_nom_biaya_disetujui 		= $data["NOM_BIAYA_DISETUJUI"];
      $ls_rehab_jenis_pelayanan		= $data["REHAB_JENIS_PELAYANAN"];	      
      $ln_nom_manfaat_utama				= $data["NOM_MANFAAT_UTAMA"];
      $ln_nom_manfaat_tambahan		= $data["NOM_MANFAAT_TAMBAHAN"];
      $ln_nom_manfaat_gross 			= $data["NOM_MANFAAT_GROSS"];
      $ln_nom_pph									= $data["NOM_PPH"];
      $ln_nom_pembulatan					= $data["NOM_PEMBULATAN"];
      $ln_nom_manfaat_netto				= $data["NOM_MANFAAT_NETTO"];
      $ls_keterangan							= $data["KETERANGAN"];
      $ls_vokasional_jenis 				 = $data["VOKASIONAL_JENIS"];
      $ls_vokasional_jenis_lainnya = $data["VOKASIONAL_JENIS_LAINNYA"];
      $ls_vokasional_plkb 				 = $data["VOKASIONAL_PLKB"];
      $ld_vokasional_tgl_awal 		 = $data["VOKASIONAL_TGL_AWAL"];
      $ld_vokasional_tgl_akhir 		 = $data["VOKASIONAL_TGL_AKHIR"];												
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
		var curr_tgl_awal =<?php echo ($ld_vokasional_tgl_awal=='') ? 'false' : "'".$ld_vokasional_tgl_awal."'"; ?>;
		var curr_jenis =<?php echo ($ls_vokasional_jenis=='') ? 'false' : "'".$ls_vokasional_jenis."'"; ?>;
		var curr_jenis_lainnya =<?php echo ($ls_vokasional_jenis_lainnya=='') ? 'false' : "'".$ls_vokasional_jenis_lainnya."'"; ?>;
						
    //hitung nilai manfaat -----------------------------------------------------
    function f_ajax_val_hitung_manfaat()
    {		 	
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_nom_biaya_diajukan = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diajukan').value),2);     
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			
			if ((c_nom_biaya_diajukan!=curr_nom_biaya_diajukan))
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_vokasional&c_kode_manfaat='+c_kode_manfaat+'&c_nom_biaya_diajukan='+c_nom_biaya_diajukan+'&c_kode_klaim='+c_kode_klaim+'&c_no_urut='+c_no_urut;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
  			curr_nom_biaya_diajukan = c_nom_biaya_diajukan;
			}										      		
    }             

    function f_ajax_val_tgl()
    {		 	
			c_kode_klaim          = window.document.getElementById('kode_klaim').value;
			c_tgl_awal            = window.document.getElementById('vokasional_tgl_awal').value;
			c_tgl_akhir           = window.document.getElementById('vokasional_tgl_akhir').value;
      
      var tgl_awal_split    = '';
      var tgl_awal_split1   = '';
      var tgl_awal_split2   = '';
      var tgl_awal_split3   = '';
      var tgl_awal_compare  = '';
      var tgl_akhir_split   = '';
      var tgl_akhir_split1  = '';
      var tgl_akhir_split2  = '';
      var tgl_akhir_split3  = '';
      var tgl_akhir_compare = '';
      var sysdate           = new Date();

      if(c_tgl_awal != ''){
        tgl_awal_split    = c_tgl_awal.split('/');
        tgl_awal_split1   = tgl_awal_split[0];
        tgl_awal_split2   = tgl_awal_split[1];
        tgl_awal_split3   = tgl_awal_split[2];
        tgl_awal_compare  = new Date(tgl_awal_split2+'/'+tgl_awal_split1+'/'+tgl_awal_split3);

        if(tgl_awal_compare > sysdate){   
          alert('Tanggal Awal Pelatihan tidak boleh lebih besar dari hari ini.');
          document.getElementById("vokasional_tgl_awal").value = "";
          return;
        }
        document.getElementById("vokasional_tgl_akhir").disabled = false;
        document.getElementById("btn_vokasional_tgl_akhir").disabled = false;
        var display_tgl_akhir = document.getElementById("vokasional_tgl_akhir");
   
        display_tgl_akhir.style.backgroundColor = '#ffff99';
      }

      if(c_tgl_akhir != ''){
        tgl_akhir_split   = c_tgl_akhir.split('/');
        tgl_akhir_split1  = tgl_akhir_split[0];
        tgl_akhir_split2  = tgl_akhir_split[1];
        tgl_akhir_split3  = tgl_akhir_split[2];
        tgl_akhir_compare = new Date(tgl_akhir_split2+'/'+tgl_akhir_split1+'/'+tgl_akhir_split3);

        if(tgl_akhir_compare > sysdate){   
          alert('Tanggal Akhir Pelatihan tidak boleh lebih besar dari hari ini.');
          document.getElementById("vokasional_tgl_akhir").value = "";
          return;
        } else if(tgl_akhir_compare < tgl_awal_compare){
          alert('Tanggal Akhir Pelatihan tidak boleh lebih kecil dari Tanggal Awal Pelatihan.');
          document.getElementById("vokasional_tgl_akhir").value = "";
          return;
        }
      }
    }

    function f_ajax_val_jenis_vokasional()
    {		 	
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			
			c_jenis = window.document.getElementById('vokasional_jenis').value;
			c_jenis_lainnya = window.document.getElementById('vokasional_jenis_lainnya').value;
		
			if (curr_jenis_lainnya==''){curr_jenis_lainnya='XyZ';}
			
			if ((c_jenis!=curr_jenis || c_jenis_lainnya!=curr_jenis_lainnya))
			{ 
				ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_vokasional_jenis&c_kode_manfaat='+c_kode_manfaat+'&c_no_urut='+c_no_urut+'&c_kode_klaim='+c_kode_klaim+'&c_jenis='+c_jenis+'&c_jenis_lainnya='+c_jenis_lainnya;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
  			curr_jenis = c_jenis;
				curr_jenis_lainnya = c_jenis_lainnya;
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
        window.document.getElementById("dispError1").innerHTML = "(* Setup Tarif Vokasional tidak valid, harap cek data setup..!!!";
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
			<input type="hidden" id="kode_manfaat" name="kode_manfaat" value="<?=$ls_kode_manfaat;?>">
			<input type="hidden" id="tgl_kejadian" name="tgl_kejadian" value="<?=$ld_tgl_kejadian;?>">
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
				<fieldset style="width:750px;"><legend><strong><em><span style="color:#009999">Entry Rincian Manfaat Vokasional</span></em></strong></legend>
					</br>				
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tipe Penerima *</label>
            <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:260px;background-color:#ffff99;">
            <option value="">---------- pilih ----------</option>
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
          <label style = "text-align:right;">Jenis Pelatihan *</label>
            <select size="1" id="vokasional_jenis" name="vokasional_jenis" value="<?=$ls_vokasional_jenis;?>" tabindex="2" class="select_format" style="width:260px;background-color:#ffff99;" onChange="f_ajax_val_jenis_vokasional();fl_js_set_span_vokasional_lainnya();" >
            <option value="">-- Pilih --</option>
            <? 
            $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKIVOKJNS' and nvl(aktif,'T')='Y' order by seq";
						$DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
              echo "<option ";
              if ($row["KODE"]==$ls_vokasional_jenis && strlen($ls_vokasional_jenis)==strlen($row["KODE"])){ echo " selected"; }
              echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>																		 								
          </div>
          <div class="clear"></div>
					
					<span id="span_jenis_lainnya" style="display:none;">		
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Detil Jenis Lainnya </label>
              <input type="text" id="vokasional_jenis_lainnya" name="vokasional_jenis_lainnya" value="<?=$ls_vokasional_jenis_lainnya;?>" tabindex="3" maxlength="100" style="width:245px;text-align:left;background-color:#ffff99;" onblur="this.value=this.value.toUpperCase();f_ajax_val_jenis_vokasional();">				
            </div>																		
            <div class="clear"></div>
					</span>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Nama PLKB/BLK </label>
            <input type="text" id="vokasional_plkb" name="vokasional_plkb" value="<?=$ls_vokasional_plkb;?>" tabindex="4" maxlength="100" style="width:240px;text-align:left;background-color:#ffff99;" onblur="this.value=this.value.toUpperCase();">				
          </div>																		
          <div class="clear"></div>
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Pelatihan *</label>
            <input type="text" id="vokasional_tgl_awal" name="vokasional_tgl_awal" value="<?=$ld_vokasional_tgl_awal;?>" size="12" maxlength="10" tabindex="5" onblur="convert_date(vokasional_tgl_awal);f_ajax_val_tgl();" style="background-color:#ffff99;">
            <input id="btn_vokasional_tgl_awal" type="image" align="top" onclick="return showCalendar('vokasional_tgl_awal', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Awal" />	   							
          	s/d
						<input type="text" id="vokasional_tgl_akhir" name="vokasional_tgl_akhir" value="<?=$ld_vokasional_tgl_akhir;?>" size="12" maxlength="10" tabindex="6" onblur="convert_date(vokasional_tgl_akhir);f_ajax_val_tgl();" disabled>
            <input id="btn_vokasional_tgl_akhir" type="image" align="top" onclick="return showCalendar('vokasional_tgl_akhir', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Akhir" disabled/>
					</div>																																															
          <div class="clear"></div>	
																				
					</br>
										          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Diajukan </label>
            <input type="text" id="nom_biaya_diajukan" name="nom_biaya_diajukan" value="<?=number_format((float)$ln_nom_biaya_diajukan,2,".",",");?>" tabindex="6" size="37" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_manfaat();">				
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Disetujui </label>
            <input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value="<?=number_format((float)$ln_nom_biaya_disetujui,2,".",",");?>" size="37" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);">				
          </div>																		
          <div class="clear"></div>																
					
					</br>
												
          <div class="form-row_kiri">
          <label style = "text-align:right;">Catatan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:240px" id="keterangan" name="keterangan" tabindex="9" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>
					
					</br>	
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