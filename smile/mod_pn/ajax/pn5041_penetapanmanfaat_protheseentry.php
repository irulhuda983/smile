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
$ls_sender_mid 						= 'pn5041_penetapanmanfaat_protheseentry.php';

$ls_kode_klaim	 		 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_manfaat	 	 			= !isset($_GET['kode_manfaat']) ? $_POST['kode_manfaat'] : $_GET['kode_manfaat'];
$ln_no_urut	 	 						= !isset($_GET['no_urut']) ? $_POST['no_urut'] : $_GET['no_urut'];
$ls_kategori_manfaat			= $_POST['kategori_manfaat'];
$ls_kd_prg								= $_POST['kd_prg'];	

//ambil segmen kepesertaan -----------------------------------------------------
if ($ls_kode_klaim!="")
{
  $sql = "select kode_segmen, case when tgl_kejadian < '22-FEB-2023' then 0 else 1 end tgl_kejadian_permenaker4 from sijstk.pn_klaim a ".
         "where a.kode_klaim = '$ls_kode_klaim' ";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_kode_segmen	= $row['KODE_SEGMEN'];
  $ls_tgl_kejadian_permenaker4	= $row['TGL_KEJADIAN_PERMENAKER4'];
}
//end ambil segmen kepesertaan -------------------------------------------------
	
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat, a.kategori_manfaat, b.kd_prg, c.kode_perlindungan, c.kode_segmen, c.kode_klaim_induk ".
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
	$ls_kode_klaim_induk	= $row['KODE_KLAIM_INDUK'];
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}
		
$ls_kode_tipe_penerima	= $_POST['kode_tipe_penerima'];
$ls_kode_manfaat_detil	= $_POST['kode_manfaat_detil'];
$ls_alatbantu_sub				= $_POST['alatbantu_sub'];
$ls_alatbantu_lainnya		= $_POST['alatbantu_lainnya'];
$ln_alatbantu_jml_item	= str_replace(',','',$_POST['alatbantu_jml_item']);
$ln_nom_biaya_diajukan	= str_replace(',','',$_POST['nom_biaya_diajukan']);
$ln_nom_biaya_diverifikasi	= str_replace(',','',$_POST['nom_biaya_diverifikasi']);
$ln_nom_biaya_disetujui	= str_replace(',','',$_POST['nom_biaya_disetujui']);        					
$ls_keterangan					= $_POST['keterangan'];

if ($ls_kategori_manfaat=="TAMBAHAN")
{
 	 $ln_nom_manfaat_utama = 0;
	 $ln_nom_manfaat_tambahan = $ln_nom_biaya_disetujui;
}else
{
 	 $ln_nom_manfaat_utama = $ln_nom_biaya_disetujui;
	 $ln_nom_manfaat_tambahan = 0;
}
// $ln_nom_manfaat_utama= is_float($ln_nom_manfaat_utama)?floatval($ln_nom_manfaat_utama):(is_double($ln_nom_manfaat_utama)?doubleval($ln_nom_manfaat_utama):intval($ln_nom_manfaat_utama));
// $ln_nom_manfaat_tambahan= is_float($ln_nom_manfaat_tambahan)?floatval($ln_nom_manfaat_tambahan):(is_double($ln_nom_manfaat_tambahan)?doubleval($ln_nom_manfaat_tambahan):intval($ln_nom_manfaat_tambahan));

// 11-11-2022 -> ME/412/092022 Perbaikan Nominal Bayar Saat generate jurnal
//$ln_nom_manfaat_gross = (toNumber($ln_nom_manfaat_utama)+toNumber($ln_nom_manfaat_tambahan));
$ln_nom_manfaat_gross = (toNumberDouble($ln_nom_manfaat_utama)+toNumberDouble($ln_nom_manfaat_tambahan));
echo "debug" . $ln_nom_manfaat_gross;
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
    var tes=	<?php		
                echo "window.location.replace('pn5041_penetapanmanfaat_prothese.php?task=edit&kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid ');";
              ?>;		
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
      }else if(form.kode_manfaat_detil.value==""){
        alert('Jenis Penggantian tidak boleh kosong...!!!');
        form.kode_manfaat_detil.focus();
      }else
  		{
         form.tombol.value="simpan";
         form.submit(); 		 
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
    $ln_cek_nom_biaya_diajukan 	= (float) str_replace(',','',$ln_nom_biaya_diajukan);  
		$ln_cek_nom_biaya_diverifikasi 	= (float) str_replace(',','',$ln_nom_biaya_diverifikasi);  
		if($ln_cek_nom_biaya_diverifikasi > $ln_cek_nom_biaya_diajukan){
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  			echo "alert('Nominal biaya diverifikasi tidak boleh lebih besar dari nominal biaya diajukan.');";
			echo "window.location.replace('$ls_sender_mid?task=$ls_task&kode_klaim=$ls_kode_klaim&kode_manfaat=$ls_kode_manfaat&form_penetapan=$ls_form_penetapan&sender_activetab=2&sender_mid=$ls_sender_mid');";
          	echo "</script>";	
			return;
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
							 "	alatbantu_sub, alatbantu_lainnya, alatbantu_jml_item, ". 
							 "	nom_biaya_diajukan, nom_biaya_diverifikasi, nom_biaya_disetujui, ".
							 "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ".						 
               "	keterangan, tgl_rekam, petugas_rekam)   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut','$ls_kode_manfaat_detil','$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	'$ls_alatbantu_sub', '$ls_alatbantu_lainnya', '$ln_alatbantu_jml_item', ". 
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
							 "	alatbantu_sub						= '$ls_alatbantu_sub', ".
							 "	alatbantu_lainnya				= '$ls_alatbantu_lainnya', ".
							 "	alatbantu_jml_item			= '$ln_alatbantu_jml_item', ".
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
									alatbantu_sub, alatbantu_lainnya, alatbantu_jml_item, 
									nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
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
      $ls_alatbantu_sub						= $data["ALATBANTU_SUB"];
      $ls_alatbantu_lainnya				= $data["ALATBANTU_LAINNYA"];
      $ln_alatbantu_jml_item			= $data["ALATBANTU_JML_ITEM"];
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
			$ln_alatbantu_jml_item			= "1";      		 
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
    var curr_kode_manfaat_detil =<?php echo ($ls_kode_manfaat_detil=='') ? 'false' : "'".$ls_kode_manfaat_detil."'"; ?>;
    var curr_alatbantu_jml_item =<?php echo ($ln_alatbantu_jml_item=='') ? 'false' : "'".$ln_alatbantu_jml_item."'"; ?>;
		var curr_nom_biaya_diajukan =<?php echo ($ln_nom_biaya_diajukan=='') ? 'false' : "'".$ln_nom_biaya_diajukan."'"; ?>;
		var curr_nom_biaya_diverifikasi =<?php echo ($ln_nom_biaya_diverifikasi=='') ? 'false' : "'".$ln_nom_biaya_diverifikasi."'"; ?>;
				
    //validasi kode manfaat detil ----------------------------------------------
    function f_ajax_val_kode_manfaat_detil()
    {		 	
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_kode_manfaat_detil = window.document.getElementById('kode_manfaat_detil').value;
			
			if (c_kode_manfaat_detil!=curr_kode_manfaat_detil)
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_kode_manfaat_detil&c_kode_manfaat='+c_kode_manfaat+'&c_kode_manfaat_detil='+c_kode_manfaat_detil;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_kode_manfaat_detil = c_kode_manfaat_detil;
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
			c_kode_manfaat_detil = window.document.getElementById('kode_manfaat_detil').value;
			c_alatbantu_jml_item = parseInt(removeCommas(window.document.getElementById('alatbantu_jml_item').value));
			//c_nom_biaya_diajukan = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diajukan').value),2);  
			c_nom_biaya_diverifikasi = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diverifikasi').value),2);     
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			
			if ((c_alatbantu_jml_item!=curr_alatbantu_jml_item) || (c_nom_biaya_diverifikasi!=curr_nom_biaya_diverifikasi))
			{ 
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_prothese&c_kode_manfaat='+c_kode_manfaat+'&c_kode_manfaat_detil='+c_kode_manfaat_detil+'&c_alatbantu_jml_item='+c_alatbantu_jml_item+'&c_nom_biaya_diverifikasi='+c_nom_biaya_diverifikasi+'&c_kode_klaim='+c_kode_klaim+'&c_no_urut='+c_no_urut;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_kode_manfaat_detil = c_kode_manfaat_detil;
  			curr_alatbantu_jml_item = c_alatbantu_jml_item;
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
      
      //tampilan error jika nama perusahaan sudah terdaftar --------------  					
      if (st_errorval1 == 1)
      {  
        window.document.getElementById("dispError1").innerHTML = "(* Setup Tarif Manfaat utk Prothese/Orthese tidak valid, harap cek data setup..!!!";
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
				<fieldset style="width:750px;"><legend><strong><em><span style="color:#009999">Entry Rincian Manfaat Biaya Prothese/Orthese</span></em></strong></legend>
          </br>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tipe Penerima *</label>
            <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:300px;background-color:#ffff99;">
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

					<script language="javascript">
          function fl_js_set_alatbantu_lainnya(v_kode_manfaat_detil) 
          { 
          	var form = document.fpop;
       			var	v_kode_manfaat_detil = form.kode_manfaat_detil.value;

						if (v_kode_manfaat_detil =="6") //Alat Bantu Lainnya --------------
            {    
          		window.document.getElementById("span_alatbantu_lainnya").style.display = 'block';
							window.document.getElementById("nom_biaya_disetujui").readOnly = false;
  						window.document.getElementById("nom_biaya_disetujui").style.backgroundColor='#ffffff';
            }else // Selain alat bantu lainnya ----------------
            {
          	  window.document.getElementById("span_alatbantu_lainnya").style.display = 'none';
							window.document.getElementById("alatbantu_lainnya").value = "";
							window.document.getElementById("nom_biaya_disetujui").readOnly = true;
  						window.document.getElementById("nom_biaya_disetujui").style.backgroundColor='#F2F2F2';
            } 	
          }	
          </script>
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">Jenis Penggantian *</label>
            <select size="1" id="kode_manfaat_detil" name="kode_manfaat_detil" value="<?=$ls_kode_manfaat_detil;?>" tabindex="2" class="select_format" style="width:300px;background-color:#ffff99;" onChange="fl_js_set_alatbantu_lainnya(this.value);f_ajax_val_kode_manfaat_detil();" >
            <option value="">-- Pilih Jenis Penggantian --</option>
						<? 
            if ($ls_kode_klaim_induk!="") //jika penetapan ulang dan detil orthese/protese sudah pernah diambil maka tidak dapat ditetapkan ulang
						{
						 	 //filter detil manfaat ------------------------------------------
              $ls_filter_detil_manfaat = "and a.kode_manfaat_detil not in ".
							                           "(		".
																				 "		select kode_manfaat_detil from sijstk.pn_klaim_manfaat_detil ".
                                         "		where kode_manfaat = '$ls_kode_manfaat' ".
                                         "		and nvl(nom_biaya_disetujui,0)<>0 ".
                                         "		and kode_klaim in ".
                                         "		( ".
                                         "    	select kode_klaim from sijstk.pn_klaim where kode_klaim <> '$ls_kode_klaim' ".
                                         "    	start with kode_klaim = '$ls_kode_klaim' and nvl(status_batal,'T')='T' ".
                                         "    	connect by prior kode_klaim_induk = kode_klaim and nvl(status_batal,'T')='T' ".
                                         "    	) ".
																				 ")";
						}
						
						$filter_permenaker4_2023 = '';

            if($ls_tgl_kejadian_permenaker4 > 0){
              $filter_permenaker4_2023 = " and a.kode_manfaat_detil not in ('5','3') ";
            } else {
              $filter_permenaker4_2023 = " and a.kode_manfaat_detil in ('1','6') ";
            }
						
						$sql = "select a.kode_manfaat_detil, a.nama_manfaat_detil ".
								 	 "from sijstk.pn_kode_manfaat_detil a ".
                   "where a.kode_manfaat = '$ls_kode_manfaat' ".
									 $ls_filter_detil_manfaat.
                   $filter_permenaker4_2023.
                   "and nvl(a.status_nonaktif,'T')='T' ".
									 "order by a.kode_manfaat_detil";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if ($row["KODE_MANFAAT_DETIL"]==$ls_kode_manfaat_detil && strlen($ls_kode_manfaat_detil)==strlen($row["KODE_MANFAAT_DETIL"])){ echo " selected"; }
            echo " value=\"".$row["KODE_MANFAAT_DETIL"]."\">".$row["NAMA_MANFAAT_DETIL"]."</option>";
            }
            ?>
            </select>	
						<input type="hidden" id="alatbantu_sub" name="alatbantu_sub" value="<?=$ls_alatbantu_sub;?>" size="40" maxlength="100" tabindex="3">																	 								
          </div>
          <div class="clear"></div>
										
					<span id="span_alatbantu_lainnya" style="display:none;">		
            <div class="form-row_kiri">
            <label  style = "text-align:right;">Alat Bantu Lainnya </label>
              <input type="text" id="alatbantu_lainnya" name="alatbantu_lainnya" value="<?=$ls_alatbantu_lainnya;?>" size="40" maxlength="100" tabindex="4">				
            </div>																		
            <div class="clear"></div>
					</span>
												
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jumlah Item </label>
            <input type="text" id="alatbantu_jml_item" name="alatbantu_jml_item" value="<?=number_format((float)$ln_alatbantu_jml_item,0,".",",");?>" tabindex="5" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_nondesimal(this.value); f_ajax_val_hitung_manfaat();">				
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
          	<textarea cols="255" rows="1" style="width:290px" id="keterangan" name="keterangan" tabindex="8" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>

					<?
          echo "<script type=\"text/javascript\">fl_js_set_alatbantu_lainnya('$ls_kode_manfaat_detil');</script>";
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