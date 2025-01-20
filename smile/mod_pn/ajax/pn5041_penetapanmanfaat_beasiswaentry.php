<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
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
$ls_ket_sudah_pernah_terima_beasiswa = "";

//ambil segmen kepesertaan -----------------------------------------------------
if ($ls_kode_klaim!="")
{
  // update smile berulang 29-11-2019 
  $sql = "select 
              kode_segmen,
              (   select count(*) from pn.pn_klaim x, pn.pn_klaim_manfaat_detil y
                  where x.kode_klaim = y.kode_klaim
                  and x.kode_tipe_klaim in ('JKK01','JKM01','JHM01')
                  and nvl(x.status_batal,'T') = 'T'
                  and x.kode_tk = a.kode_tk
                  and y.kode_manfaat = '2'
									and nvl(y.nom_biaya_disetujui, 0) > 0
                  and y.kode_klaim||y.no_urut <> a.kode_klaim||nvl('$ln_no_urut',0)
              ) cnt_beasiswa_lain 
          from sijstk.pn_klaim a
          where a.kode_klaim = '$ls_kode_klaim'";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_kode_segmen	= $row['KODE_SEGMEN'];
  $ln_cnt_beasiswa_lain= $row['CNT_BEASISWA_LAIN'];
	if ($ln_cnt_beasiswa_lain > 0)
	{
	 	$ls_flag_ada_beasiswa_lain = "Y";
		$ls_ket_sudah_pernah_terima_beasiswa = "(* Sudah pernah menerima manfaat beasiswa...!"; 
	}else
	{
	 	$ls_flag_ada_beasiswa_lain = "T"; 
		$ls_ket_sudah_pernah_terima_beasiswa = ""; 	 
	}
}
//end ambil segmen kepesertaan -------------------------------------------------
		
if ($ls_kode_manfaat!="")
{
  $sql = "select a.nama_manfaat, a.kategori_manfaat, b.kd_prg from sijstk.pn_kode_manfaat a, sijstk.pn_klaim_manfaat b ".
         "where a.kode_manfaat = b.kode_manfaat ".
				 "and b.kode_klaim = '$ls_kode_klaim' ". 
				 "and b.kode_manfaat = '$ls_kode_manfaat'";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_status	= $row['STATUS'];
	$ls_nama_manfaat			= $row['NAMA_MANFAAT'];
	$ls_kategori_manfaat  = $row['KATEGORI_MANFAAT'];
	$ls_kd_prg						= $row['KD_PRG'];	
	
 	$gs_pagetitle = "PN5002 - ENTRY RINCIAN MANFAAT "." - ".$ls_nama_manfaat." (KODE KLAIM ".$ls_kode_klaim.")"; 		  	 
}
		
$ls_kode_tipe_penerima	= $_POST['kode_tipe_penerima'];
$ls_beasiswa_penerima		= $_POST['beasiswa_penerima'];
$ls_beasiswa_jenis			= $_POST['beasiswa_jenis'];
$ls_beasiswa_flag_masih_sekolah	= $_POST['beasiswa_flag_masih_sekolah'];
$ls_beasiswa_jenjang_pendidikan	= $_POST['beasiswa_jenjang_pendidikan'];
$ln_nom_biaya_disetujui	= str_replace(',','',$_POST['nom_biaya_disetujui']);        					
$ls_keterangan					= $_POST['keterangan'];

if ($ls_beasiswa_flag_masih_sekolah=="on" || $ls_beasiswa_flag_masih_sekolah=="ON" || $ls_beasiswa_flag_masih_sekolah=="Y")
{
	$ls_beasiswa_flag_masih_sekolah = "Y";
}else
{
	$ls_beasiswa_flag_masih_sekolah = "T";	 
}

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
        alert('Untuk beasiswa pendidikan maka tindak pendidikan tidak boleh kosong...!!!');
        form.beasiswa_jenjang_pendidikan.focus();				
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
							 "	beasiswa_jenis, beasiswa_flag_masih_sekolah, beasiswa_jenjang_pendidikan, ". 
							 "	nom_biaya_diajukan, nom_biaya_disetujui, ".
							 "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ".						 
               "	keterangan, tgl_rekam, petugas_rekam, beasiswa_penerima )   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut',null,'$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	'$ls_beasiswa_jenis','$ls_beasiswa_flag_masih_sekolah', '$ls_beasiswa_jenjang_pendidikan', ". 
               "	null, '$ln_nom_biaya_disetujui', ".
							 "	'$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', '$ln_nom_manfaat_gross', ". 
							 "  '$ln_nom_pph', '$ln_nom_pembulatan', '$ln_nom_manfaat_netto', ".
							 "	'$ls_keterangan', sysdate, '$username','$ls_beasiswa_penerima' ".
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
							 "	beasiswa_penerima				= '$ls_beasiswa_penerima' ".
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
                  nom_pph, nom_pembulatan, nom_manfaat_netto, keterangan, beasiswa_penerima
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
    var curr_beasiswa_jenis =<?php echo ($ls_beasiswa_jenis=='') ? 'false' : "'".$ls_beasiswa_jenis."'"; ?>;
		var curr_beasiswa_jenjang_pendidikan =<?php echo ($ls_beasiswa_jenjang_pendidikan=='') ? 'false' : "'".$ls_beasiswa_jenjang_pendidikan."'"; ?>;
						
    //hitung nilai manfaat -----------------------------------------------------
    function f_ajax_val_beasiswa_jenis()
    {		 	
			c_kode_klaim 	 = window.document.getElementById('kode_klaim').value;
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_kd_prg 			 = window.document.getElementById('kd_prg').value;
			c_kode_tipe_penerima = window.document.getElementById('kode_tipe_penerima').value;
			c_beasiswa_jenis = window.document.getElementById('beasiswa_jenis').value;
			c_beasiswa_jenjang_pendidikan = window.document.getElementById('beasiswa_jenjang_pendidikan').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			
      //update smile berulang 29-11-2019
      c_ada_beasiswa_lain = '<?=$ls_flag_ada_beasiswa_lain;?>';

      if(c_ada_beasiswa_lain == 'Y'){
        window.document.getElementById("nom_biaya_disetujui").value = 0; 
      }else{
        if ((c_beasiswa_jenis!=curr_beasiswa_jenis))
        { 
          ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_beasiswa&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_beasiswa_jenis='+c_beasiswa_jenis+'&c_beasiswa_jenjang_pendidikan='+c_beasiswa_jenjang_pendidikan+'&c_kd_prg='+c_kd_prg+'&c_no_urut='+c_no_urut;
          ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found 
          ajax.runAJAX(); // Execute AJAX function
          curr_beasiswa_jenis = c_beasiswa_jenis;
        }
      }
			//------------------										      		
    }             

    function f_ajax_val_beasiswa_jenjang_pendidikan()
    {		 	
			c_kode_klaim 	 = window.document.getElementById('kode_klaim').value;
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_kd_prg 			 = window.document.getElementById('kd_prg').value;
			c_kode_tipe_penerima = window.document.getElementById('kode_tipe_penerima').value;
			c_beasiswa_jenis = window.document.getElementById('beasiswa_jenis').value;
			c_beasiswa_jenjang_pendidikan = window.document.getElementById('beasiswa_jenjang_pendidikan').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			
      //update smile berulang 29-11-2019
      c_ada_beasiswa_lain = '<?=$ls_flag_ada_beasiswa_lain;?>';

      if(c_ada_beasiswa_lain == 'Y'){
        window.document.getElementById("nom_biaya_disetujui").value = 0; 
      }else{
        if ((c_beasiswa_jenjang_pendidikan!=curr_beasiswa_jenjang_pendidikan))
  			{ 
          ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_beasiswa&c_kode_klaim='+c_kode_klaim+'&c_kode_manfaat='+c_kode_manfaat+'&c_beasiswa_jenis='+c_beasiswa_jenis+'&c_beasiswa_jenjang_pendidikan='+c_beasiswa_jenjang_pendidikan+'&c_kd_prg='+c_kd_prg+'&c_no_urut='+c_no_urut;
          ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
          ajax.runAJAX();	// Execute AJAX function
          curr_beasiswa_jenjang_pendidikan = c_beasiswa_jenjang_pendidikan;
  			}		
      }		
      //---------						      		
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
      <input type="hidden" id="tombol" name="tombol">  
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
			<input type="hidden" id="form_penetapan" name="form_penetapan" value="<?=$ls_form_penetapan;?>">
    	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender2" name="sender2" value="<?=$ls_sender2;?>">
      <input type="hidden" id="sender_activetab2" name="sender_activetab2" value="<?=$ls_sender_activetab2;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
						
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:750px;"><legend ><b><i><font color="#009999">Entry Rincian Manfaat Beasiswa Pendidikan</font></i></b></legend>
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
                   "and nvl(a.status_nonaktif,'T')='T' ".
									 "and a.kode_tipe_penerima not in ('B1','B2') "; //update 08/04/2021, tidak termasuk penerima beasiswa 1 dan 2
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
          <label  style = "text-align:right;">Nama Penerima *</label>
            <input type="text" id="beasiswa_penerima" name="beasiswa_penerima" value="<?=$ls_beasiswa_penerima;?>" style="width:300px;background-color:#ffff99;" maxlength="100" onblur="this.value=this.value.toUpperCase();" placeholder="-- isikan nama penerima beasiswa --">				
          </div>																		
          <div class="clear"></div>		
					          
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jenis Beasiswa</label>
            <select size="1" id="beasiswa_jenis" name="beasiswa_jenis" value="<?=$ls_beasiswa_jenis;?>" tabindex="2" class="select_format" style="width:300px;background-color:#ffff99;" onChange="fl_js_span_jenjang_pendidikan(this.value);">
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
            <label  style = "text-align:right;">Jenjang Pendidikan </label>
              <select size="1" id="beasiswa_jenjang_pendidikan" name="beasiswa_jenjang_pendidikan" value="<?=$ls_beasiswa_jenjang_pendidikan;?>" tabindex="4" class="select_format" style="width:180px;background-color:#ffff99;" onChange="f_ajax_val_beasiswa_jenjang_pendidikan();">
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
  						<input type="checkbox" class="cebox" id="beasiswa_flag_masih_sekolah" name="beasiswa_flag_masih_sekolah" value="<?=$row['BEASISWA_FLAG_MASIH_SEKOLAH'];?>" onClick="fl_js_set_beasiswa_flag_masih_sekolah();" <?=$row['BEASISWA_FLAG_MASIH_SEKOLAH']=="Y" || $row['BEASISWA_FLAG_MASIH_SEKOLAH']=="ON" || $row['BEASISWA_FLAG_MASIH_SEKOLAH']=="on" ? "checked" : "";?>> 
							<i><font color="#009999">Masih Sekolah</font></i>         																			 								
            </div>
            <div class="clear"></div>
					</span>

          <script language="javascript">
            function fl_js_span_jenjang_pendidikan(v_beasiswa_jenis) 
            { 
      				var form = document.fpop;
              var	v_beasiswa_jenis = form.beasiswa_jenis.value;
              
              if (v_beasiswa_jenis =="BEASISWA") //Beasiswa Jenis ----
              {    
              	window.document.getElementById("span_jenjang_pendidikan").style.display = 'block';
								window.document.getElementById("beasiswa_flag_masih_sekolah").value = "Y";
								window.document.getElementById("beasiswa_flag_masih_sekolah").checked = "true";
              }else // Selain Cacat Sebagian Fungsi ----------------
              {
                window.document.getElementById("span_jenjang_pendidikan").style.display = 'none';
                window.document.getElementById("beasiswa_jenjang_pendidikan").value = "";
      					window.document.getElementById("beasiswa_flag_masih_sekolah").value = "T";
              } 
							f_ajax_val_beasiswa_jenis();	
            }	
          </script>
																	
					</br>
										
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Disetujui </label>
            <input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value="<?=number_format((float)$ln_nom_biaya_disetujui,2,".",",");?>" size="37" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          	<i><font color="#ff0000"><?=$ls_ket_sudah_pernah_terima_beasiswa;?></font></i>
					</div>																		
          <div class="clear"></div>																
																				
					</br>
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Catatan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:290px" id="keterangan" name="keterangan" tabindex="8" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>

					<?
          echo "<script type=\"text/javascript\">fl_js_span_jenjang_pendidikan('$ls_beasiswa_jenis');</script>";
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
  					if ($ls_flag_ada_beasiswa_lain=="T")
						{
						?>
  					<input type="button" class="btn green" id="simpan" name="simpan" value="            SIMPAN            " onClick="fl_js_val_simpan();">
  					<?
						}
						?>
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
      <?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
      <?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
      </fieldset>		
    <?
    }
    ?>  
	</form>
</body>
</html>				