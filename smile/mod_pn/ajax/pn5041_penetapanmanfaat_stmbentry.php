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
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")";	 		  	 
}
		
$ls_kode_tipe_penerima	= $_POST['kode_tipe_penerima'];
$ld_stmb_tgl_awal				= $_POST['stmb_tgl_awal'];
$ld_stmb_tgl_akhir			= $_POST['stmb_tgl_akhir'];
$ln_stmb_jml_hari				= str_replace(',','',$_POST['stmb_jml_hari']);
$ln_stmb_jml_jamkerja		= str_replace(',','',$_POST['stmb_jml_jamkerja']);
$ls_keterangan 					= $_POST['keterangan'];	
							 				
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
      if(form.kode_tipe_penerima.value==""){
        form.kode_tipe_penerima.focus();
				alert('Tipe Penerima tidak boleh kosong...!!!');
      }else if(form.stmb_tgl_awal.value=="" || form.stmb_tgl_akhir.value==""){
        form.stmb_tgl_awal.focus();
				alert('Tgl Awal dan Akhir tidak boleh kosong, Perbaiki data input...!!!');				
      }else if(form.st_errval1.value=="1" || form.st_errval2.value=="1" || form.st_errval3.value=="1" || form.st_errval4.value=="1" || form.st_errval5.value=="1" || form.st_errval6.value=="1" || form.st_errval7.value=="1"){
        form.stmb_tgl_awal.focus();
				alert('Perbaiki data input...!!!');
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
							 "	stmb_tgl_awal, stmb_tgl_akhir, stmb_jml_hari, stmb_jml_jamkerja, ". 
							 "	keterangan, tgl_rekam, petugas_rekam)   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut','$ls_kode_manfaat_detil','$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	to_date('$ld_stmb_tgl_awal','dd/mm/yyyy'), to_date('$ld_stmb_tgl_akhir','dd/mm/yyyy'),'$ln_stmb_jml_hari','$ln_stmb_jml_jamkerja', ". 
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
          $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_STMB ('$ls_kode_klaim', '$ls_kode_manfaat','$ln_no_urut','$username',:p_sukses,:p_mess);END;";											 	
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
							 "	stmb_tgl_awal						= to_date('$ld_stmb_tgl_awal','dd/mm/yyyy'), ". 
							 "	stmb_tgl_akhir					= to_date('$ld_stmb_tgl_akhir','dd/mm/yyyy'), ". 
							 "	stmb_jml_hari						= '$ln_stmb_jml_hari', ". 
							 "	stmb_jml_jamkerja				= '$ln_stmb_jml_jamkerja', ".
							 "	keterangan							= '$ls_keterangan', ". 
							 "	tgl_ubah								= sysdate, ". 
							 "	petugas_ubah						= '$username' ".
							 "where kode_klaim = '$ls_kode_klaim' ".
							 "and kode_manfaat = '$ls_kode_manfaat' ".
							 "and no_urut = '$ln_no_urut' ";
        $DB->parse($sql);
        $DB->execute();								
						
        //post update ----------------------------------------------------------
        $qry = "BEGIN SIJSTK.P_PN_PN5040.X_HITUNG_MNF_STMB ('$ls_kode_klaim', '$ls_kode_manfaat','$ln_no_urut','$username',:p_sukses,:p_mess);END;";												 	
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
									to_char(stmb_tgl_awal,'dd/mm/yyyy') stmb_tgl_awal, to_char(stmb_tgl_akhir,'dd/mm/yyyy') stmb_tgl_akhir, 
									stmb_jml_hari, stmb_jml_jamkerja,
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
      $ln_nom_biaya_disetujui 		= $data["NOM_BIAYA_DISETUJUI"]; 
     	$ld_stmb_tgl_awal						= $data["STMB_TGL_AWAL"];
      $ld_stmb_tgl_akhir					= $data["STMB_TGL_AKHIR"];
      $ln_stmb_jml_hari						= $data["STMB_JML_HARI"];
      $ln_stmb_jml_jamkerja				= $data["STMB_JML_JAMKERJA"];  
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
		var curr_stmb_tgl_awal =<?php echo ($ld_stmb_tgl_awal=='') ? 'false' : "'".$ld_stmb_tgl_awal."'"; ?>;
		var curr_stmb_tgl_akhir =<?php echo ($ld_stmb_tgl_akhir=='') ? 'false' : "'".$ld_stmb_tgl_akhir."'"; ?>;				
						
    //hitung jumlah hari -------------------------------------------------------
		function f_ajax_val_hitung_jmlharistmb()
		{
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			c_stmb_tgl_awal  = window.document.getElementById('stmb_tgl_awal').value;
			c_stmb_tgl_akhir = window.document.getElementById('stmb_tgl_akhir').value;
			
			if (c_stmb_tgl_awal!=curr_stmb_tgl_awal || c_stmb_tgl_akhir!= curr_stmb_tgl_akhir)
			{
        ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_jmlharistmb&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_no_urut='+c_no_urut+'&c_stmb_tgl_awal='+c_stmb_tgl_awal+'&c_stmb_tgl_akhir='+c_stmb_tgl_akhir;
        ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
        ajax.runAJAX();	// Execute AJAX function
        curr_stmb_tgl_awal  = c_stmb_tgl_awal;
  			curr_stmb_tgl_akhir = c_stmb_tgl_akhir; 
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
        window.document.getElementById("dispError1").innerHTML = "(* Tgl Awal STMB sudah pernah diinput dalam range tgl awal dan tgl akhir penetapan sebelumnya, harap cek data input..!!!";
        window.document.getElementById("dispError1").style.display = 'block';
        window.document.getElementById('stmb_tgl_awal').focus();
      }else{
      	window.document.getElementById("dispError1").style.display = 'none';
      } 
			
      if (st_errorval2 == 1)
      {  
        window.document.getElementById("dispError2").innerHTML = "(* Tgl Akhir STMB sudah pernah diinput dalam range tgl awal dan tgl akhir penetapan sebelumnya, harap cek data input..!!!";
        window.document.getElementById("dispError2").style.display = 'block';
        window.document.getElementById('stmb_tgl_akhir').focus();
      }else{
      	window.document.getElementById("dispError2").style.display = 'none';
      } 	
			
      if (st_errorval3 == 1)
      {  
        window.document.getElementById("dispError3").innerHTML = "(* Tgl Awal/Akhir STMB Penetapan Sebelumnya sudah pernah diinput dalam range tgl awal dan tgl akhir yang sedang dientry, harap cek data input..!!!";
        window.document.getElementById("dispError3").style.display = 'block';
        window.document.getElementById('stmb_tgl_akhir').focus();
      }else{
      	window.document.getElementById("dispError3").style.display = 'none';
      }	

      //Penambahan validasi smileberulang 29-11-2019
			//update 22/12/2019 sesuai info dari kop bahwa syaratnya adalah:
			//Tanggal awal STMB >= tanggal kejadian  ---valid
			//Tanggal akhir STMB <= tanggal kondisi akhir ---valid
      //Penambahan pengecheckan untuk tgl awal stmb tidak boleh lebih kecil dari tgl kejadian
      if (st_errorval4 == 1)
      {  
        window.document.getElementById("dispError4").innerHTML = "(* Tgl Awal STMB tidak boleh lebih kecil dari tanggal kejadian, harap cek data input ..!!!";
        window.document.getElementById("dispError4").style.display = 'block';
        window.document.getElementById('stmb_tgl_awal').focus();
      }else{
        window.document.getElementById("dispError4").style.display = 'none';
      }
      //Penambahan pengecheckan untuk tgl_akhir_stmb tidak boleh lebih besar dari tgl_kondisi akhir
      if (st_errorval5 == 1)
      {  
        window.document.getElementById("dispError5").innerHTML = "(* Tgl Akhir STMB tidak boleh lebih besar dari tanggal kondisi akhir, harap cek data input ..!!!";
        window.document.getElementById("dispError5").style.display = 'block';
        window.document.getElementById('stmb_tgl_akhir').focus();
      }else{
        window.document.getElementById("dispError5").style.display = 'none';
      } 	
	  //Penambahan pengecheckan untuk tgl_akhir_stmb tidak boleh lebih kecil dari tgl awal stmb
      if (st_errorval6 == 1)
      {  
        window.document.getElementById("dispError6").innerHTML = "(* Tgl Akhir STMB tidak boleh lebih kecil dari tanggal awal, harap cek data input ..!!!";
        window.document.getElementById("dispError6").style.display = 'block';
        window.document.getElementById('stmb_tgl_akhir').focus();
      }else{
        window.document.getElementById("dispError6").style.display = 'none';
      }
	  //Penambahan pengecheckan untuk tanggal jatuh tempo pengajuan STMB untuk kondisi akhir MASIH PENGOBATAN
	  // update 14032022 
      if (st_errorval7 == 1)
      {  
		<?php
			if ($ls_kode_segmen == 'PU' or $ls_kode_segmen == 'JAKON' or $ls_kode_segmen == 'TKI')
			{
		?>
        window.document.getElementById("dispError6").innerHTML = "(* Belum tanggal jatuh tempo pengajuan STMB untuk kondisi akhir MASIH PENGOBATAN yaitu >= 6 bulan dan kelipatannya, harap cek data input ..!!!";
		<?php 
			}
			else
			{
		?>
		window.document.getElementById("dispError6").innerHTML = "(* Belum tanggal jatuh tempo pengajuan STMB untuk kondisi akhir MASIH PENGOBATAN yaitu >= 1 bulan dan kelipatannya, harap cek data input ..!!!";
		<?php 
			}
		?>
		window.document.getElementById("dispError6").style.display = 'block';
        window.document.getElementById('stmb_tgl_akhir').focus();
      }else{
        window.document.getElementById("dispError6").style.display = 'none';
      }
      //-----------------------------------------	end smileberulang ------	      																																						 						 																														
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
	  <input type="hidden" id="st_errval7" name="st_errval7">  
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
				<fieldset style="width:750px;"><legend >Entry Rincian Manfaat STMB</legend>
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

          <div class="form-row_kiri">
          <label style = "text-align:right;">Periode</label>
            <input type="text" id="stmb_tgl_awal" name="stmb_tgl_awal" value="<?=$ld_stmb_tgl_awal;?>" size="15" maxlength="10" tabindex="2" onblur="convert_date(stmb_tgl_awal);f_ajax_val_hitung_jmlharistmb();" onfocus="f_ajax_val_hitung_jmlharistmb();" style="background-color:#ffff99;">
            <input id="btn_stmb_tgl_awal" type="image" align="top" onclick="return showCalendar('stmb_tgl_awal', 'dd-mm-y');" src="../../images/calendar.gif" />	   							
          	&nbsp;s/d&nbsp;
						<input type="text" id="stmb_tgl_akhir" name="stmb_tgl_akhir" value="<?=$ld_stmb_tgl_akhir;?>" size="15" maxlength="10" tabindex="3" onblur="convert_date(stmb_tgl_akhir);f_ajax_val_hitung_jmlharistmb();" onfocus="f_ajax_val_hitung_jmlharistmb();" style="background-color:#ffff99;">
            <input id="btn_stmb_tgl_akhir" type="image" align="top" onclick="return showCalendar('stmb_tgl_akhir', 'dd-mm-y');" src="../../images/calendar.gif" />
					</div>																																															
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jml Hari </label>
            <input type="text" id="stmb_jml_hari" name="stmb_jml_hari" value="<?=number_format((float)$ln_stmb_jml_hari,0,".",",");?>" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_nondesimal(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>
									
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jml Jam Kerja </label>
            <input type="text" id="stmb_jml_jamkerja" name="stmb_jml_jamkerja" value="<?=number_format((float)$ln_stmb_jml_jamkerja,0,".",",");?>" tabindex="5" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_nondesimal(this.value);">				
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
      <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
      <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
      </fieldset>		
    <?
    }
    ?>  
	</form>
</body>
</html>				