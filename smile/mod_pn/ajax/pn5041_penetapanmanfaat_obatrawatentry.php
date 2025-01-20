<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$ECDB = new Database($EC_DBUser,$EC_DBPass,$EC_DBName);
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

//ambil segmen kepesertaan -----------------------------------------------------
if ($ls_kode_klaim!="")
{
  $sql = "select 
			 	 	kode_segmen, to_char(tgl_kejadian,'dd/mm/yyyy') tgl_kejadian, negara_penempatan, 
				 	to_char(tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir,
           case when tgl_kejadian between '10-DEC-2018' AND '21-FEB-2023' THEN 0 ELSE 1 END permenaker4_2023,
          (SELECT DISTINCT kode_bpjstk
              FROM SPO.TKI_MS_KODE@to_ec
              WHERE nama = a.negara_penempatan AND TIPE = 'TKI_NEGARAID'
          ) kode_negara
			 	 from sijstk.pn_klaim a 
         where a.kode_klaim = '$ls_kode_klaim' ";
	$DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ls_kode_segmen	= $row['KODE_SEGMEN'];
  $ld_tgl_kejadian = $row['TGL_KEJADIAN'];
  $ld_permenaker4_2023 = $row['PERMENAKER4_2023'];
  $ld_negara_penempatan = $row['NEGARA_PENEMPATAN'];
	$ld_tgl_kondisi_terakhir = $row['TGL_KONDISI_TERAKHIR'];
  $ld_kode_negara_penempatan	= $row['KODE_NEGARA'];

  // $sql_kode_negara = "select kode_negara from ms.ms_negara where aktif = 'Y' and nama_negara = '$ld_negara_penempatan' and rownum = 1";
	// $DB->parse($sql_kode_negara);
  // $DB->execute();
  // $row = $DB->nextrow();
  // $ls_kode_negara_penempatan	= $row['KODE_NEGARA'];
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
$ls_rawat_jenis					= $_POST['rawat_jenis'];
$ld_rawat_tgl						= $_POST['rawat_tgl'];
$ln_rawat_jml_hari			= str_replace(',','',$_POST['rawat_jml_hari']);
$ln_rawat_biaya_inap		= str_replace(',','',$_POST['rawat_biaya_inap']);
$ln_nom_biaya_diajukan	= str_replace(',','',$_POST['nom_biaya_diajukan']);														
$ln_nom_biaya_disetujui	= str_replace(',','',$_POST['nom_biaya_disetujui']);
$ls_rawat_pihak_ketiga	= $_POST['rawat_pihak_ketiga'];					
$ln_rawat_nom_pihak_ketiga = str_replace(',','',$_POST['rawat_nom_pihak_ketiga']);	
$ls_keterangan 					= $_POST['keterangan'];	
$ls_rawat_tipe_negara		= $_POST['rawat_tipe_negara'];
$ls_kode_negara_penempatan		= $_POST['kode_negara_penempatan'];

if ($ls_kategori_manfaat=="TAMBAHAN")
{
 	 $ln_nom_manfaat_utama = 0;
	 $ln_nom_manfaat_tambahan = $ln_nom_biaya_disetujui;
}else
{
 	 $ln_nom_manfaat_utama = $ln_nom_biaya_disetujui;
	 $ln_nom_manfaat_tambahan = 0;
}
// 11-11-2022 -> ME/412/092022 Perbaikan Nominal Bayar Saat generate jurnal
//$ln_nom_manfaat_gross = (toNumber($ln_nom_manfaat_utama)+toNumber($ln_nom_manfaat_tambahan));
$ln_nom_manfaat_gross = (toNumberDouble($ln_nom_manfaat_utama)+toNumberDouble($ln_nom_manfaat_tambahan));
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

      var tgl_kond_akhir = "<?php echo $ld_tgl_kondisi_terakhir ?>";
      var cek_kode_negara_penempatan = '<?=$ld_kode_negara_penempatan;?>';
      var dt_tgl_kond_akhir = new Date(tgl_kond_akhir.split("/").reverse().join("/"));
      var tgl_kond_akhir2 = form.rawat_tgl.value;
      var dt_tgl_kond_akhir2 = new Date(tgl_kond_akhir2.split("/").reverse().join("/"));
      var cek_nom_biaya_disetujui = window.document.getElementById('nom_biaya_disetujui').value;
      var cek_nom_biaya_disetujui_float = parseFloat(cek_nom_biaya_disetujui.replace(/,/g, ''));
      var cek_nom_biaya_disetujui_max = window.document.getElementById('nom_biaya_disetujui_max').value;
      var cek_nom_biaya_disetujui_max_float = parseFloat(cek_nom_biaya_disetujui_max.replace(/,/g, ''));
      var cek_nom_biaya_diajukan  = window.document.getElementById('nom_biaya_diajukan').value;
      var cek_nom_biaya_diajukan_float  = parseFloat(cek_nom_biaya_diajukan.replace(/,/g, ''));
      
      if(form.kode_tipe_penerima.value==""){
        alert('Tipe Penerima tidak boleh kosong...!!!');
        form.kode_tipe_penerima.focus();
      }else if(form.rawat_jenis.value==""){
        alert('Jenis Obat/Rawat tidak boleh kosong...!!!');
        form.rawat_jenis.focus();
      }else if(form.rawat_tgl.value==""){
        alert('Tgl Obat/Rawat tidak boleh kosong...!!!');
        form.rawat_tgl.focus();	
      }else if(dt_tgl_kond_akhir2 > dt_tgl_kond_akhir){
        alert('Tgl Obat/Rawat tidak boleh lebih besar dari Tgl Kondisi Akhir!!!');
        form.rawat_tgl.focus();
			}else if(form.kode_segmen.value=="TKI" && form.rawat_tipe_negara.value==""){
        alert('Untuk PMI maka negara tempat dirawat tidak boleh kosong...!!!');
        form.rawat_tipe_negara.focus();																
      }else if(cek_nom_biaya_disetujui_float > cek_nom_biaya_diajukan_float){
        alert('Total disetujui tidak boleh lebih besar dari total diajukan.');
        form.rawat_tipe_negara.focus();																
      }else if(cek_nom_biaya_disetujui_float > cek_nom_biaya_disetujui_max_float && form.rawat_tipe_negara.value == 'P'){
        alert('Maksimal nominal Total Disetujui adalah '+cek_nom_biaya_disetujui_max+'.');
        form.rawat_tipe_negara.focus();																
      }else if(form.rawat_tipe_negara.value=='P' && form.kode_negara_penempatan.value!="" && form.kode_negara_penempatan.value!=cek_kode_negara_penempatan){
        
        alert('Negara penempatan tidak sesuai dengan penempatan awal.');
        form.kode_negara_penempatan.focus();		
        														
      }else
  		{
         if(form.rawat_tipe_negara.value!='P'){
          form.kode_negara_penempatan.value = '';
         }
         form.tombol.value="simpan";
         form.submit(); 		 
  		}								 
  	}
		
		//update 23/01/2019 tambahan terkait TKI -----------------------------------
    function fl_js_rawat_tipe_negara() 
    { 
    	var v_kode_segmen = window.document.getElementById("kode_segmen").value;
    	
    	if (v_kode_segmen =="TKI")
      {    
    		window.document.getElementById("span_rawat_tipe_negara").style.display = 'block';
      }else
      {
    	 	window.document.getElementById("span_rawat_tipe_negara").style.display = 'none';	 
    	  window.document.getElementById("rawat_tipe_negara").value = "I";
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
							 "	rawat_tgl, rawat_jenis, rawat_jml_hari, rawat_biaya_inap, rawat_pihak_ketiga, rawat_nom_pihak_ketiga, ". 
							 "	nom_biaya_diajukan, nom_biaya_disetujui, ".
							 "	nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, ". 
							 "  nom_pph, nom_pembulatan, nom_manfaat_netto, ".						 
               "	keterangan, tgl_rekam, petugas_rekam, rawat_tipe_negara, kode_negara_penempatan_dirawat)   ".
               "values ( ".
  						 "	'$ls_kode_klaim','$ls_kode_manfaat','$ln_no_urut','$ls_kode_manfaat_detil','$ls_kategori_manfaat','$ls_kode_tipe_penerima','$ls_kd_prg', ".
							 "	to_date('$ld_rawat_tgl','dd/mm/yyyy'), '$ls_rawat_jenis', '$ln_rawat_jml_hari', '$ln_rawat_biaya_inap', '$ls_rawat_pihak_ketiga', '$ln_rawat_nom_pihak_ketiga',". 
               "	'$ln_nom_biaya_diajukan', '$ln_nom_biaya_disetujui', ".
							 "	'$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', '$ln_nom_manfaat_gross', ". 
							 "  '$ln_nom_pph', '$ln_nom_pembulatan', '$ln_nom_manfaat_netto', ".
							 "	'$ls_keterangan', sysdate, '$username', '$ls_rawat_tipe_negara', '$ls_kode_negara_penempatan' ".
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
							 "	rawat_tgl								= to_date('$ld_rawat_tgl','dd/mm/yyyy'), ".
							 "	rawat_jenis							= '$ls_rawat_jenis', ". 
							 "	rawat_jml_hari					= '$ln_rawat_jml_hari', ". 
							 "	rawat_biaya_inap				= '$ln_rawat_biaya_inap', ". 
							 "	rawat_pihak_ketiga			= '$ls_rawat_pihak_ketiga', ". 
							 "	rawat_nom_pihak_ketiga	= '$ln_rawat_nom_pihak_ketiga' , ".
							 "	rawat_tipe_negara				= '$ls_rawat_tipe_negara' , ".
							 "	nom_biaya_diajukan			= '$ln_nom_biaya_diajukan', ". 
							 "	nom_biaya_disetujui			= '$ln_nom_biaya_disetujui', ".
							 "	nom_manfaat_utama				= '$ln_nom_manfaat_utama', ". 
							 "	nom_manfaat_tambahan		= '$ln_nom_manfaat_tambahan', ". 
							 "	nom_manfaat_gross				= '$ln_nom_manfaat_gross', ". 
							 "  nom_pph									= '$ln_nom_pph', ". 
							 "	nom_pembulatan					= '$ln_nom_pembulatan', ". 
							 "	nom_manfaat_netto				= '$ln_nom_manfaat_netto', ".					 
               "	keterangan							= '$ls_keterangan', ". 
               "	kode_negara_penempatan_dirawat = '$ls_kode_negara_penempatan', ". 
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
									kode_tipe_penerima, kd_prg, nom_biaya_diajukan, nom_biaya_disetujui, 
									to_char(rawat_tgl,'dd/mm/yyyy') rawat_tgl, rawat_jenis, rawat_jml_hari, 
									rawat_biaya_inap, rawat_pihak_ketiga, rawat_nom_pihak_ketiga,
									nom_manfaat_utama, nom_manfaat_tambahan, nom_manfaat_gross, 
                  nom_pph, nom_pembulatan, nom_manfaat_netto, keterangan,
									rawat_tipe_negara,
                  (select (select c.kode_negara from ms.ms_negara c where c.nama_negara = b.negara_penempatan and rownum = 1) from pn.pn_klaim b where b.kode_klaim = a.kode_klaim) kode_negara_penempatan
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
      $ls_rawat_jenis							= $data["RAWAT_JENIS"];	
      $ld_rawat_tgl								= $data["RAWAT_TGL"];	
      $ln_rawat_jml_hari					= $data["RAWAT_JML_HARI"];	
      $ln_rawat_biaya_inap				= $data["RAWAT_BIAYA_INAP"];	
      $ls_rawat_pihak_ketiga			= $data["RAWAT_PIHAK_KETIGA"];				
      $ln_rawat_nom_pihak_ketiga  = $data["RAWAT_NOM_PIHAK_KETIGA"];	
			$ls_rawat_tipe_negara				= $data["RAWAT_TIPE_NEGARA"];
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
      $ls_negara_penempatan   		= $data["KODE_NEGARA_PENEMPATAN"];				
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
		var curr_jml_hari =<?php echo ($ln_rawat_jml_hari=='') ? 'false' : "'".$ln_rawat_jml_hari."'"; ?>;	
		var curr_biaya_inap =<?php echo ($ln_rawat_biaya_inap=='') ? 'false' : "'".$ln_rawat_biaya_inap."'"; ?>;	
    //hitung nilai manfaat -----------------------------------------------------
    function f_ajax_val_hitung_manfaat()
    {		 	
			c_kode_manfaat = window.document.getElementById('kode_manfaat').value;
			c_nom_biaya_diajukan = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diajukan').value),2);     
			c_kode_perlindungan = window.document.getElementById('kode_perlindungan').value;
			c_kode_segmen = window.document.getElementById('kode_segmen').value;
			c_kode_klaim = window.document.getElementById('kode_klaim').value;
			c_no_urut = window.document.getElementById('no_urut').value;
			c_rawat_tipe_negara = window.document.getElementById('rawat_tipe_negara').value;
			if (c_rawat_tipe_negara==''){c_rawat_tipe_negara='XyZ';}
			
			if (c_kode_segmen=='TKI' && c_rawat_tipe_negara=='L')
			{
			 	 document.getElementById('nom_biaya_disetujui').value = 0;
			}else
			{
  			if ((c_nom_biaya_diajukan!=curr_nom_biaya_diajukan))
  			{ 
          ajax.requestFile = '../ajax/pn5040_validasi.php?getClientId=f_ajax_val_hitung_manfaat_obatrawat&c_kode_manfaat='+c_kode_manfaat+'&c_nom_biaya_diajukan='+c_nom_biaya_diajukan+'&c_kode_perlindungan='+c_kode_perlindungan+'&c_kode_segmen='+c_kode_segmen+'&c_kode_klaim='+c_kode_klaim+'&c_no_urut='+c_no_urut;
          ajax.onCompletion = showClientData; // Specify function that will be executed after file has been found	
          ajax.runAJAX();	// Execute AJAX function
    			curr_nom_biaya_diajukan = c_nom_biaya_diajukan;
  			}
			}										      		
    }             
   	
		function f_ajax_val_rawat_tgl()
		{
		 	var form = document.fpop;
			var c_rawat_tgl 					 = window.document.getElementById('rawat_tgl').value;
			var c_tgl_kejadian 				 = '<?=$ld_tgl_kejadian;?>';
			var c_tgl_kondisi_terakhir = '<?=$ld_tgl_kondisi_terakhir;?>';		 
			
			var v_rawat_tgl = new Date(c_rawat_tgl.split("/").reverse().join("/"));
			var v_tgl_kejadian = new Date(c_tgl_kejadian.split("/").reverse().join("/"));
			var v_tgl_kondisi_terakhir = new Date(c_tgl_kondisi_terakhir.split("/").reverse().join("/"));
			
			if (v_rawat_tgl < v_tgl_kejadian)
			{
			 	 alert('Tidak dapat direkam, Tgl Obat/Rawat '+c_rawat_tgl+' tidak boleh sebelum tgl kejadian '+c_tgl_kejadian);
				 document.getElementById('rawat_tgl').value = '';
				 form.rawat_tgl.focus();
			}else if(v_rawat_tgl > v_tgl_kondisi_terakhir){
        alert('Tidak dapat direkam, Tgl Obat/Rawat '+c_rawat_tgl+' tidak boleh setelah Tgl Kondisi Akhir '+c_tgl_kondisi_terakhir);
				document.getElementById('rawat_tgl').value = '';
        form.rawat_tgl.focus();
			}
		}
		
    function f_ajax_val_jml_hari() 
    { 
      c_jml_hari = parseFloat(removeCommas(window.document.getElementById('rawat_jml_hari').value),2);
			c_biaya_inap = parseFloat(removeCommas(window.document.getElementById('rawat_biaya_inap').value),2);

      if (isNaN(c_jml_hari))
      	 c_jml_hari = 0;
      
      if (isNaN(c_biaya_inap))
      	 c_biaya_inap = 0;
				 			
			if ((c_jml_hari!=curr_jml_hari))
			{ 
        v_total = (c_jml_hari * c_biaya_inap);
				document.getElementById('nom_biaya_diajukan').value = format_uang(v_total);
				
				f_ajax_val_hitung_manfaat();
  			curr_jml_hari = c_jml_hari;
			}										
    }	

    function f_ajax_val_biaya_inap() 
    { 
      c_jml_hari = parseFloat(removeCommas(window.document.getElementById('rawat_jml_hari').value),2);
			c_biaya_inap = parseFloat(removeCommas(window.document.getElementById('rawat_biaya_inap').value),2);

      if (isNaN(c_jml_hari))
      	 c_jml_hari = 0;
      
      if (isNaN(c_biaya_inap))
      	 c_biaya_inap = 0;
				 			
			if ((c_biaya_inap!=curr_biaya_inap))
			{ 
        v_total = (c_jml_hari * c_biaya_inap);
				document.getElementById('nom_biaya_diajukan').value = format_uang(v_total);
				
				f_ajax_val_hitung_manfaat();
  			curr_biaya_inap = c_biaya_inap;
			}										
    }

    function f_ajax_val_rawat_tipe_negara() 
    { 
      c_kode_segmen 			= window.document.getElementById('kode_segmen').value;
			c_rawat_tipe_negara = window.document.getElementById('rawat_tipe_negara').value;
			c_nom_biaya_diajukan = parseFloat(removeCommas(window.document.getElementById('nom_biaya_diajukan').value),2);
			
      if (isNaN(c_nom_biaya_diajukan))
      	 c_nom_biaya_diajukan = 0;
				 
			if (c_rawat_tipe_negara==''){c_rawat_tipe_negara='XyZ';}
			
			if (c_kode_segmen=='TKI' && c_rawat_tipe_negara=='L')
			{  
         window.document.getElementById("span_negara_penempatan").style.display = 'none';
        
			 	 document.getElementById('nom_biaya_disetujui').value = 0;
			}else
			{ 
        if(c_rawat_tipe_negara == 'P'){
          window.document.getElementById("span_negara_penempatan").style.display = 'block';
        } else {
          window.document.getElementById("span_negara_penempatan").style.display = 'none';
        }
  			if (c_nom_biaya_diajukan>0)
				{
				 	curr_nom_biaya_diajukan=0;
					f_ajax_val_hitung_manfaat();
				}
			}										
    }

    function f_ajax_val_nom_biaya_disetujui() 
    { 
      c_kode_segmen 			= window.document.getElementById('kode_segmen').value;
			c_rawat_tipe_negara = window.document.getElementById('rawat_tipe_negara').value;
			if (c_rawat_tipe_negara==''){c_rawat_tipe_negara='XyZ';}
			
			if (c_kode_segmen=='TKI' && c_rawat_tipe_negara=='L')
			{
			 	 document.getElementById('nom_biaya_disetujui').value = 0;
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
        window.document.getElementById("dispError1").innerHTML = "(* Setup Tarif Manfaat utk Biaya Obat/Rawat tidak valid, harap cek data setup..!!!";
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
				<fieldset style="width:750px;"><legend >Entry Rincian Manfaat Biaya Obat/Rawat</legend>
          </br>
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tipe Penerima *</label>
            <select size="1" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" tabindex="1" class="select_format" style="width:260px;background-color:#ffff99;">
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

      		<span id="span_rawat_tipe_negara" style="display:none;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Negara Tmpt Dirawat *</label>		 	    				
              <select size="1" id="rawat_tipe_negara" name="rawat_tipe_negara" value="<?=$ls_rawat_tipe_negara;?>" tabindex="2" class="select_format" style="width:260px;background-color:#ffff99;" onchange="f_ajax_val_rawat_tipe_negara();">
              <option value="">-- Pilih --</option>
              <? 
                if($ld_permenaker4_2023 == 1){
                  $ls_negara = '';
                  if($ls_kode_perlindungan=='PRA'){
                    $ls_negara = " and kode = 'I'";
                  } else if($ls_kode_perlindungan=='ONSITE'){
                    $ls_negara = " and kode <> 'L'";
                  } else if($ls_kode_perlindungan=='PURNA'){
                    $ls_negara = " and kode = 'I'";
                  }
        				  $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and nvl(aktif,'T')='Y' $ls_negara order by seq";			
                } else {
                  $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='TKITIPENGR' and nvl(aktif,'T')='Y' and kode = 'I' order by seq";			
                }
          			$DB->parse($sql);
                $DB->execute();
                while($row = $DB->nextrow())
                {
                  echo "<option ";
                  if ($row["KODE"]==$ls_rawat_tipe_negara && strlen($ls_rawat_tipe_negara)==strlen($row["KODE"])){ echo " selected"; }
                  echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
                }
              ?>
              </select>		
            </div>																																												
          	<div class="clear"></div>		
      		</span>

          <?
            $ls_display = 'none';
            if($ls_rawat_tipe_negara=='P'){
              $ls_display = 'block';
            }
          ?>

          <span id="span_negara_penempatan" style="display:<?=$ls_display;?>;">
            <div class="form-row_kiri">
            <label style = "text-align:right;">Negara Penempatan *</label>		 	    				
              <select size="1" id="kode_negara_penempatan" name="kode_negara_penempatan" tabindex="2" class="select_format" style="width:260px;background-color:#ffff99;" onchange="f_ajax_val_rawat_tipe_negara();">
              <option value="">-- Pilih --</option>
              <? 
        				$sql = "SELECT DISTINCT KODE_BPJSTK, NAMA
                            FROM SPO.TKI_MS_KODE
                          WHERE KODE_BPJSTK IS NOT NULL AND TIPE = 'TKI_NEGARAID'
                        ORDER BY nama";										
          			$ECDB->parse($sql);
                $ECDB->execute();
                while($row = $ECDB->nextrow())
                {
                  
                  echo "<option ";
                  if ($ld_negara_penempatan == $row["NAMA"]){ echo " selected"; }
                  echo " value=\"".$row["KODE_BPJSTK"]."\">".$row["NAMA"]."</option>";
                }
              ?>
              </select>		
            </div>																																												
          	<div class="clear"></div>		
      		</span>
							
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jenis Obat/Rawat *</label>				
            <select size="1" id="rawat_jenis" name="rawat_jenis" value="<?=$ls_rawat_jenis;?>" tabindex="2" class="select_format" style="width:260px;background-color:#ffff99;" onChange="fl_js_set_rawat_jenis(this.value);">
            <option value="">-- Pilih Jenis Rawat --</option>
            <? 
            $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMJNSRWT' and nvl(aktif,'T')='Y' order by seq ";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if ($row["KODE"]==$ls_rawat_jenis && strlen($ls_rawat_jenis)==strlen($row["KODE"])){ echo " selected"; }
            echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>          
					</div>																		
          <div class="clear"></div>	
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tgl Obat/Rawat *</label>
            <input type="text" id="rawat_tgl" name="rawat_tgl" value="<?=$ld_rawat_tgl;?>" size="35" maxlength="10" tabindex="3" onblur="convert_date(rawat_tgl);f_ajax_val_rawat_tgl();" onfocus="f_ajax_val_rawat_tgl();" style="background-color:#ffff99;">
            <input id="btn_rawat_tgl" type="image" align="top" onclick="return showCalendar('rawat_tgl', 'dd-mm-y');" src="../../images/calendar.gif" alt="Tanggal Obat Rawat" />	   							
          </div>																																															
          <div class="clear"></div>																		
										
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jumlah Hari </label>
            <input type="text" id="rawat_jml_hari" name="rawat_jml_hari" value="<?=number_format((float)$ln_rawat_jml_hari,0,".",",");?>" tabindex="4" size="33" maxlength="20" style="text-align:left;" onblur="this.value=format_nondesimal(this.value);f_ajax_val_jml_hari();">		 		
          </div>																		
          <div class="clear"></div>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Biaya Inap/hari </label>
            <input type="text" id="rawat_biaya_inap" name="rawat_biaya_inap" value="<?=number_format((float)$ln_rawat_biaya_inap,2,".",",");?>" tabindex="5" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);f_ajax_val_biaya_inap();">				
          </div>																		
          <div class="clear"></div>
						
					</br>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Diajukan </label>
            <input type="text" id="nom_biaya_diajukan" name="nom_biaya_diajukan" value="<?=number_format((float)$ln_nom_biaya_diajukan,2,".",",");?>" tabindex="6" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value); f_ajax_val_hitung_manfaat();">				
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Total Disetujui </label>
            <input type="text" id="nom_biaya_disetujui" name="nom_biaya_disetujui" value="<?=number_format((float)$ln_nom_biaya_disetujui,2,".",",");?>" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);f_ajax_val_nom_biaya_disetujui();" >				
            <input type="hidden" id="nom_biaya_disetujui_max" name="nom_biaya_disetujui_max" value="<?=number_format((float)$ln_nom_biaya_disetujui,2,".",",");?>" size="30" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);f_ajax_val_nom_biaya_disetujui();" >				
          </div>																		
          <div class="clear"></div>																

					<script language="javascript">
          function fl_js_set_rawat_jenis(v_rawat_jenis) 
          { 
          	var form = document.fpop;
       			var	v_rawat_jenis = form.rawat_jenis.value;
						
						if (v_rawat_jenis =="RAWAT") //Biaya Rawat Inap --------------------
            {    
          		window.document.getElementById("rawat_jml_hari").readOnly = false;
  						window.document.getElementById("rawat_jml_hari").style.backgroundColor='#ffffff';
							window.document.getElementById("rawat_biaya_inap").readOnly = false;
  						window.document.getElementById("rawat_biaya_inap").style.backgroundColor='#ffffff';
							window.document.getElementById("nom_biaya_diajukan").readOnly = true;
  						window.document.getElementById("nom_biaya_diajukan").style.backgroundColor='#F2F2F2';
            }else // Selain alat bantu lainnya ----------------
            {
          	  window.document.getElementById("rawat_jml_hari").value = "";
							window.document.getElementById("rawat_biaya_inap").value = "";
							window.document.getElementById("rawat_jml_hari").readOnly = true;
  						window.document.getElementById("rawat_jml_hari").style.backgroundColor='#F2F2F2';
							window.document.getElementById("rawat_biaya_inap").readOnly = true;
  						window.document.getElementById("rawat_biaya_inap").style.backgroundColor='#F2F2F2';
							window.document.getElementById("nom_biaya_diajukan").readOnly = false;
  						window.document.getElementById("nom_biaya_diajukan").style.backgroundColor='#ffffff';
            } 	
          }			
          </script>
															
					</br>					

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pihak Ketiga </label>				
            <select size="1" id="rawat_pihak_ketiga" name="rawat_pihak_ketiga" value="<?=$ls_rawat_pihak_ketiga;?>" tabindex="7" class="select_format" style="width:242px;">
            <option value="">-- Pilih --</option>
            <? 
            $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMPIHAK3' and nvl(aktif,'T')='Y' order by seq ";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if ($row["KODE"]==$ls_rawat_pihak_ketiga && strlen($ls_rawat_pihak_ketiga)==strlen($row["KODE"])){ echo " selected"; }
            echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>          
					</div>																		
          <div class="clear"></div>
										
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tanggungan Phk Ktiga </label>
            <input type="text" id="rawat_nom_pihak_ketiga" name="rawat_nom_pihak_ketiga" value="<?=number_format((float)$ln_rawat_nom_pihak_ketiga,2,".",",");?>" tabindex="8" size="32" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);">				
          </div>																		
          <div class="clear"></div>
															
          <div class="form-row_kiri">
          <label style = "text-align:right;">Catatan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:260px" id="keterangan" name="keterangan" tabindex="9" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>

					<?
          echo "<script type=\"text/javascript\">fl_js_rawat_tipe_negara();</script>";
					echo "<script type=\"text/javascript\">fl_js_set_rawat_jenis('$ls_rawat_jenis');</script>";
          ?>
										
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