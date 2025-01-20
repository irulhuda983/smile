<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SIJSTK";
$gs_pagetitle = "PN6001 - ENTRY PEMBAYARAN KOREKSI KLAIM";

$ls_task 				 			 		= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ls_kode_pembayaran	 			= !isset($_GET['kode_pembayaran']) ? $_POST['kode_pembayaran'] : $_GET['kode_pembayaran'];
$ls_kode_klaim	 		 			= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_kode_agenda	 		 			= !isset($_GET['kode_agenda']) ? $_POST['kode_agenda'] : $_GET['kode_agenda'];
$ls_kode_tipe_penerima	 	= !isset($_GET['kode_tipe_penerima']) ? $_POST['kode_tipe_penerima'] : $_GET['kode_tipe_penerima'];
$ls_kd_prg	 							= !isset($_GET['kd_prg']) ? $_POST['kd_prg'] : $_GET['kd_prg'];
$ls_form_root							= !isset($_GET['form_root']) ? $_POST['form_root'] : $_GET['form_root'];
$ls_root_sender 				 	= !isset($_GET['root_sender']) ? $_POST['root_sender'] : $_GET['root_sender'];
$ls_sender 				 				= !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_activetab 			= !isset($_GET['sender_activetab']) ? $_POST['sender_activetab'] : $_GET['sender_activetab'];
$ls_sender_mid 						= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 					 	 			= !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];
$ln_no_level				 			= !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];
$btn_task 					 			= !isset($_GET['btn_task']) ? $_POST['btn_task'] : $_GET['btn_task'];
$ld_tglawaldisplay				= !isset($_GET['tglawaldisplay']) ? $_POST['tglawaldisplay'] : $_GET['tglawaldisplay'];
$ld_tglakhirdisplay				= !isset($_GET['tglakhirdisplay']) ? $_POST['tglakhirdisplay'] : $_GET['tglakhirdisplay'];


if ($ls_kode_klaim!="")
{ 
  $sql = "select status_klaim, kode_pointer_asal, id_pointer_asal from sijstk.pn_klaim ".
			 	 "where kode_klaim = '$ls_kode_klaim' ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();		
  $ls_kode_pointer_asal = $row['KODE_POINTER_ASAL'];
  $ls_id_pointer_asal		= $row['ID_POINTER_ASAL'];
	$ls_kode_realisasi		= $row['ID_POINTER_ASAL'];
	$ls_status_klaim			= $row['STATUS_KLAIM'];		
}

if ($ls_kode_agenda == "")
{
	$ls_kode_agenda = $_POST['nama_penerima'];	
}

//$ls_kode_tipe_penerima	= $_POST['kode_tipe_penerima'];
$ls_nama_tipe_penerima	= $_POST['nama_tipe_penerima'];
$ls_nama_penerima				= $_POST['nama_penerima'];				          										
$ls_kode_kantor					= $_POST['kode_kantor'];		
$ls_kode_kantor_pembayar = $_POST['kode_kantor_pembayar'];
$ln_nom_manfaat_gross		= str_replace(',','',$_POST['nom_manfaat_gross']);
$ln_nom_ppn							= str_replace(',','',$_POST['nom_ppn']);		
$ls_kode_pajak_ppn			= $_POST['kode_pajak_ppn'];				
$ln_nom_pph							= str_replace(',','',$_POST['nom_pph']);		
$ls_kode_pajak_pph			= $_POST['kode_pajak_pph'];			
$ln_nom_pembulatan			= str_replace(',','',$_POST['nom_pembulatan']);
$ln_nom_netto						= str_replace(',','',$_POST['nom_netto']);
$ln_nom_sudah_bayar			= str_replace(',','',$_POST['nom_sudah_bayar']);						
$ln_nom_sisa						= str_replace(',','',$_POST['nom_sisa']);
//$ls_kd_prg							= $_POST['kd_prg'];							
$ls_nm_prg							= $_POST['nm_prg'];	
$ls_kode_cara_bayar			= $_POST['kode_cara_bayar'];																																								
$ls_kode_buku						= $_POST['kode_buku'];
$ls_kode_bank						= $_POST['kode_bank'];
$ls_nama_bank						= $_POST['nama_bank'];
$ld_tgl_pembayaran			= $_POST['tgl_pembayaran'];							
$ls_bank_penerima				= $_POST['bank_penerima'];
$ls_no_rekening_penerima 	 = $_POST['no_rekening_penerima'];
$ls_nama_rekening_penerima = $_POST['nama_rekening_penerima']; 
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
    //if($ls_form_root!=''){			
    	echo "window.location.replace('$ls_form_root?task=PEMBAYARAN&kode_klaim=$ls_kode_klaim&task=$ls_task&no_level=$ln_no_level&dataid=$ls_kode_klaim&id_pointer_asal=$ls_id_pointer_asal&kode_pointer_asal=$ls_kode_pointer_asal&kode_realisasi=$ls_kode_realisasi&sender=pn6001.php&activetab=2&mid=$ls_sender_mid');";		
    //}
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
			}else if (form.kd_prg.value==""){
        alert('Program tidak boleh kosong...!!!');
        form.kd_prg.focus();	
			}else if (form.kode_cara_bayar.value==""){
        alert('Cara Bayar tidak boleh kosong...!!!');
        form.kode_cara_bayar.focus();
			}else if (form.kode_buku.value==""){
        alert('Kode Buku tidak boleh kosong...!!!');
        form.kode_buku.focus();												
      }else
  		{
         form.btn_task.value="simpan";
				 window.document.getElementById('btnsimpan').disabled = true;
				 window.document.getElementById('btnsimpan').value='';
				 //document.fpop.btnsimpan.disabled=true;
				 //document.fpop.btnsimpan.value='';
         form.submit(); 		 
  		}								 
  	}    		
  </script>															
</head>
<body>
  <!--[if lte IE 6]>
  <div id="clearie6"></div>
  <![endif]-->	
  <form name="fpop" method="post" action="<?=$PHP_SELF;?>">
  	<?
  	if($btn_task=="simpan")
  	{         				  		
      $btn_task = "";
			
			//generate kode pembayaran --------------------------------------------------------
      $sql = 	"select sijstk.p_pn_genid.f_gen_kodepembayaran as v_kode_pembayaran from dual ";
      $DB->parse($sql);
      $DB->execute();
      $row = $DB->nextrow();
      $ls_kode_pembayaran = $row["V_KODE_PEMBAYARAN"];
  		
    	//insert data ----------------------------------------------------	 
      $sql = "insert into pn.pn_agenda_koreksi_klaim_bayar ( ".
             "  kode_agenda, kode_pembayaran, kode_klaim, kode_tipe_penerima, kd_prg,  flag_partial, ".
             "  nom_saldo_total, nom_iuran_total, nom_manfaat_diambil, nom_manfaat_utama, nom_manfaat_tambahan, ".
             "  nom_manfaat_gross, nom_ppn, kode_pajak_ppn, nom_pph, kode_pajak_pph, nom_pembulatan, nom_manfaat_netto, nom_pembayaran, ".
             "  kode_kantor, tgl_pembayaran, kode_cara_bayar, kode_bank, kode_buku, keterangan, ".
             "  tgl_rekam, petugas_rekam, kode_kantor_pembayar)  ".
             "values ( ".
    				 "	 '$ls_kode_agenda', '$ls_kode_pembayaran','$ls_kode_klaim','$ls_kode_tipe_penerima','$ls_kd_prg', '$ls_flag_partial', ".
    				 "   '$ln_nom_saldo_total', '$ln_nom_iuran_total', '$ln_nom_manfaat_diambil', '$ln_nom_manfaat_utama', '$ln_nom_manfaat_tambahan', ".
             "   '$ln_nom_manfaat_gross', '$ln_nom_ppn', '$ls_kode_pajak_ppn', '$ln_nom_pph', '$ls_kode_pajak_pph', '$ln_nom_pembulatan', '$ln_nom_netto', '$ln_nom_sisa', ".
             "   '$ls_kode_kantor', to_date('$ld_tgl_pembayaran','dd/mm/yyyy'), '$ls_kode_cara_bayar', '$ls_kode_bank', '$ls_kode_buku', '$ls_keterangan', ".
    				 "	 sysdate,'$username','$ls_kode_kantor_pembayar' ". 	 	  		 
    				 ") ";				 
      //echo $sql;
    	$DB->parse($sql);
      $DB->execute(); 	
  		
      //jalankan proses post insert ----------------------------------------------
  		//jalankan proses post insert ----------------------------------------------
  	  //$qry = "BEGIN PN.P_PN_PN60010205.X_POST_BAYAR('$ls_kode_pembayaran','$username',:p_sukses,:p_mess);END;";	
	  $qry = "BEGIN PN.P_PN_PN60010205.X_BAYAR_KOREKSI_KLAIM_JHT('$ls_kode_agenda','$ls_kode_pembayaran','$ls_kode_kantor_pembayar','$username',:p_sukses,:p_mess);END;";	

      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
  		oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
      $DB->execute();				
      $ls_sukses = $p_sukses;	
  		$ls_mess = $p_mess;		 									 
  		
      //post update ----------------------------------------------------------      
      $msg = "Data Pembayaran berhasil tersimpan, session dilanjutkan...";
      $task = "view";	
      $ls_hiddenid = $ls_kode_pembayaran;
      $editid = $ls_kode_pembayaran;		
  		$btn_task = "";

      $msg = "Submit Data berhasil dilakukan, session dilanjutkan..."; 	
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    	//echo "window.opener.location.replace('../form/pn6001_jht_terlambat_setupbunga.php?task=Edit&root_sender=$ls_root_sender&sender=$ls_sender&activetab=$ls_sender_activetab&sender_mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim'&kode_agenda=$ls_kode_agenda);";
		echo "window.opener.location.replace('../ajax/pn6001_jht_terlambat_setupbunga_pembayaran_entry.php?task=View&kd_perihal_detil=PP0205&path=pn6001_jht_terlambat_setupbunga.php&kd_agenda='$ls_kode_agenda'&st_agenda=APPROVED');";
		echo "window.close();";
    	echo "</script>";	
  		
    	//echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    	//echo "window.location.replace('?tas=view&kode_pembayaran=$ls_kode_pembayaran&root_sender=$ls_root_sender&sender=$ls_sender&activetab=$ls_sender_activetab&sender_mid=$ls_sender_mid&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim');";
    	//echo "</script>";		
  								                
  	} //end if(isset($_POST['simpan'])) 
  	?>	
		
    <?
    if ($ls_kode_pembayaran !="")
    {
      $sql = "select 
				  a.kode_agenda,
                  a.kode_pembayaran, a.kode_klaim, a.kode_tipe_penerima, 
                  (select nama_tipe_penerima from sijstk.pn_kode_tipe_penerima where kode_tipe_penerima = a.kode_tipe_penerima) nama_tipe_penerima, 
                  b.nama_penerima, b.bank_penerima, b.no_rekening_penerima, b.nama_rekening_penerima, b.npwp, 
                  a.kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg,
                  a.nom_saldo_total, a.nom_iuran_total, 
                  a.nom_manfaat_diambil, a.nom_manfaat_utama, a.nom_manfaat_tambahan, 
                  a.nom_manfaat_gross, a.nom_ppn, a.nom_pph, 
                  a.nom_pembulatan, a.nom_manfaat_netto, 
                  (
                      select sum(nvl(nom_manfaat_netto,0)) from pn.pn_agenda_koreksi_klaim_bayar
                      where kode_klaim = a.kode_klaim
                      and kode_tipe_penerima = a.kode_tipe_penerima
                      and kd_prg = a.kd_prg
                      and nvl(status_batal,'T')='T'
                      and kode_pembayaran <> a.kode_pembayaran
                  ) nom_sudah_bayar,
                  a.nom_pembayaran, 
                  a.kode_pajak_ppn, a.kode_pajak_pph, a.kode_kantor, 
                  to_char(a.tgl_pembayaran,'dd/mm/yyyy') tgl_pembayaran, a.kode_cara_bayar, a.kode_bank,
									(select nama_bank from sijstk.ms_bank where kode_bank = a.kode_bank) nama_bank, 
                  a.kode_buku, a.no_kwitansi, a.no_cek, 
                  a.keterangan_cek, a.flag_partial, a.flag_buktipotong_pph, 
                  a.keterangan, a.no_pointer, a.status_batal, 
                  a.tgl_batal, a.petugas_batal, a.tgl_rekam, 
                  a.petugas_rekam, a.tgl_ubah, a.petugas_ubah, 
                  a.kode_transfer, a.kode_referensi_bank, a.kode_kantor_pembayar, 
                  a.flag_sentralisasi, a.flag_pph_progresif
              from pn.pn_agenda_koreksi_klaim_bayar a, pn.pn_agenda_koreksi_klaim_trm b
              where a.kode_klaim = b.kode_klaim(+)
              and a.kode_tipe_penerima = b.kode_tipe_penerima(+) 
              and a.kode_pembayaran = '$ls_kode_pembayaran' ";
      
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
	  $ls_kode_agenda				 		= $data["KODE_AGENDA"];
	  $ls_kode_klaim				 		= $data["KODE_KLAIM"];
      $ls_kode_tipe_penerima		= $data["KODE_TIPE_PENERIMA"];	
      $ls_nama_tipe_penerima		= $data["NAMA_TIPE_PENERIMA"];	
      $ls_kd_prg								= $data["KD_PRG"];	
      $ls_nm_prg 								= $data["NM_PRG"];	
      $ls_nama_penerima					= $data["NAMA_PENERIMA"];	
      $ls_bank_penerima					= $data["BANK_PENERIMA"];	
      $ls_no_rekening_penerima	= $data["NO_REKENING_PENERIMA"];
      $ls_nama_rekening_penerima	= $data["NAMA_REKENING_PENERIMA"];	
      $ls_npwp									= $data["NPWP"];	
      $ln_nom_manfaat_gross			= $data["NOM_MANFAAT_GROSS"];	
      $ln_nom_ppn								= $data["NOM_PPN"];
      $ls_kode_pajak_ppn				= $data["KODE_PAJAK_PPN"];
      $ln_nom_pph								= $data["NOM_PPH"];	
      $ls_kode_pajak_pph				= $data["KODE_PAJAK_PPH"];
      $ln_nom_pembulatan				= $data["NOM_PEMBULATAN"];	
      $ln_nom_netto							= $data["NOM_MANFAAT_NETTO"];	
      $ln_nom_sudah_bayar				= $data["NOM_SUDAH_BAYAR"];	
      $ln_nom_sisa							= $data["NOM_PEMBAYARAN"];
      $ls_kode_kantor						= $data["KODE_KANTOR"];
			$ls_kode_kantor_pembayar	= $data["KODE_KANTOR_PEMBAYAR"];
      $ls_flag_partial					= $data["FLAG_PARTIAL"];
      $ld_tgl_pembayaran 	      = $data["TGL_PEMBAYARAN"];	
      $ls_kode_cara_bayar 	    = $data["KODE_CARA_BAYAR"];	
      $ls_kode_buku             = $data['KODE_BUKU'];	
      $ls_kode_bank             = $data['KODE_BANK'];
      $ls_nama_bank             = $data['NAMA_BANK'];		
			$ls_keterangan						= $data["KETERANGAN"];																					
    }else
		{
		  $sql = "
				 select
                 kode_agenda, kode_klaim, kode_tipe_penerima, nama_tipe_penerima, kd_prg, nm_prg,  
                 nama_penerima, bank_penerima, no_rekening_penerima, nama_rekening_penerima, npwp, 
                 nom_manfaat_gross, nom_ppn, nom_pph, kode_pajak_ppn, nom_pph, kode_pajak_pph, nom_pembulatan, 
					  nom_netto, nom_sudah_bayar, nom_sisa, a.kode_pembayaran 
					  kode_kantor, kode_kantor_pembayar, nvl(flag_partial,'T') flag_partial
                 from pn.vw_pn_pembayaran_klaim_kordtl a 
								 where kode_agenda='$ls_kode_agenda'
								 and kode_klaim = '$ls_kode_klaim'
								 and kode_tipe_penerima = '$ls_kode_tipe_penerima'
								 and kd_prg = '$ls_kd_prg' ";
      //echo $sql;
      $DB->parse($sql);
      $DB->execute();
      $data = $DB->nextrow();
			$ls_kode_agenda				 		= $data["KODE_AGENDA"];
			$ls_kode_klaim				 		= $data["KODE_KLAIM"];
			$ls_kode_tipe_penerima		= $data["KODE_TIPE_PENERIMA"];	
			$ls_nama_tipe_penerima		= $data["NAMA_TIPE_PENERIMA"];	
			$ls_kd_prg								= $data["KD_PRG"];	
			$ls_nm_prg 								= $data["NM_PRG"];	
      $ls_nama_penerima					= $data["NAMA_PENERIMA"];	
			$ls_bank_penerima					= $data["BANK_PENERIMA"];	
			$ls_no_rekening_penerima	= $data["NO_REKENING_PENERIMA"];
			$ls_nama_rekening_penerima	= $data["NAMA_REKENING_PENERIMA"];	
			$ls_npwp									= $data["NPWP"];	
      $ln_nom_manfaat_gross			= $data["NOM_MANFAAT_GROSS"];	
			$ln_nom_ppn								= $data["NOM_PPN"];
			$ls_kode_pajak_ppn				= $data["KODE_PAJAK_PPN"];
			$ln_nom_pph								= $data["NOM_PPH"];	
			$ls_kode_pajak_pph				= $data["KODE_PAJAK_PPH"];
			$ln_nom_pembulatan				= $data["NOM_PEMBULATAN"];	
			$ln_nom_netto							= $data["NOM_NETTO"];	
			$ln_nom_sudah_bayar				= $data["NOM_SUDAH_BAYAR"];	
			$ln_nom_sisa							= $data["NOM_SISA"];
			$ls_kode_kantor				 		= $data["KODE_KANTOR"];
			$ls_kode_kantor_pembayar	= $data["KODE_KANTOR_PEMBAYAR"];
			$ls_flag_partial					= $data["FLAG_PARTIAL"];
			
      $sqlx = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
      $DB->parse($sqlx);
      $DB->execute();
      $row = $DB->nextrow();
      $ld_tgl_pembayaran 	 = $row["TGL"];	
			
			//ambil default kode pembayaran -----------------------------------------
			if ($ls_kode_cara_bayar=="")
			{
  			$sqlx = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' and rownum = 1";
        $DB->parse($sqlx);
        $DB->execute();
        $row = $DB->nextrow();
        $ls_kode_cara_bayar 	 = $row["KODE"];	
			}
			
			if ($ls_kode_buku=="")
			{
        $sqlx = "select a.kode_kantor, a.kode_buku, a.kode_bank, c.nama_bank||' ('||a.kode_rekening||')' keterangan ".
               "from sijstk.ms_rekening a,sijstk.ms_rekening_detil b, sijstk.ms_bank c ".
               "where a.kode_kantor = b.kode_kantor(+) ". 
               "and a.kode_bank = b.kode_bank(+) ". 
               "and a.kode_rekening = b.kode_rekening(+) ". 
               "and a.kode_buku = b.kode_buku(+) ". 
               "and a.kode_bank = c.kode_bank ". 
               "and b.tipe_rekening = '16' ". 
               "and a.kode_kantor = '$gs_kantor_aktif' ".
               "and a.kd_prg = '$ls_kd_prg' ".
    					 "and nvl(a.aktif,'T')='Y' and rownum = 1";
        $DB->parse($sqlx);
        $DB->execute();
        $row = $DB->nextrow();		
        $ls_kode_buku = $row['KODE_BUKU'];	
				$ls_kode_bank = $row['KODE_BANK'];
				$ls_nama_bank = $row['KETERANGAN'];		 	 
			}									
		}	
		?>
		
  	<!-- VALIDASI AJAX -------------------------------------------------------->    
  	<!-- end VALIDASI AJAX ---------------------------------------------------->	
			
    <div id="header-popup">	
    <h3><?=$gs_pagetitle;?></h3>
    </div>
    
    <div id="container-popup">
    <!--[if lte IE 6]>
    <div id="clearie6"></div>
    <![endif]-->	
											
		<div id="formframe" style="width:900px;">
			<span id="dispError1" style="display:none;color:red"></span>
			<input type="hidden" id="st_errval1" name="st_errval1">		
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
			<input type="hidden" id="btn_task" name="btn_task">
      <input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
    	<input type="hidden" id="root_sender" name="root_sender" value="<?=$ls_root_sender;?>">
			<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
      <input type="hidden" id="sender_activetab" name="sender_activetab" value="<?=$ls_sender_activetab;?>">
      <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
			<input type="hidden" id="kode_pointer_asal" name="kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>">
			<input type="hidden" id="id_pointer_asal" name="id_pointer_asal" value="<?=$ls_id_pointer_asal;?>">
			<input type="hidden" id="kode_realisasi" name="kode_realisasi" value="<?=$ls_kode_realisasi;?>">
			<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
			<input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">
			<input type="hidden" id="form_root" name="form_root" value="<?=$ls_form_root;?>">			
			<input type="hidden" id="tglawaldisplay" name="tglawaldisplay" value="<?=$ld_tglawaldisplay;?>">	
			<input type="hidden" id="tglakhirdisplay" name="tglakhirdisplay" value="<?=$ld_tglakhirdisplay;?>">
		
			<div id="formKiri" style="width:900px;">
				<fieldset style="width:820px;"><legend >Detil Informasi Pembayaran Klaim</legend>
				<div class="form-row_kiri">
          <label  style = "text-align:right;">Kode Agenda &nbsp;</label>
            <input type="text" id="kode_agenda" name="kode_agenda" value="<?=$ls_kode_agenda;?>" size="40" maxlength="10" readonly class="disabled" >
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;"></label>  
          </div>					
        	<div class="clear"></div>
			
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tipe Penerima &nbsp;</label>
            <input type="hidden" id="kode_tipe_penerima" name="kode_tipe_penerima" value="<?=$ls_kode_tipe_penerima;?>" size="30" maxlength="10" readonly class="disabled" >
						<input type="text" id="nama_tipe_penerima" name="nama_tipe_penerima" value="<?=$ls_nama_tipe_penerima;?>" size="40" readonly class="disabled" >                					
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kode Pembayaran</label>
            <input type="text" id="kode_pembayaran" name="kode_pembayaran" value="<?=$ls_kode_pembayaran;?>" size="25" readonly class="disabled" >            
          </div>					
        	<div class="clear"></div>								          										

          <div class="form-row_kiri">
          <label style = "text-align:right;">Nama Penerima</label>
      			<input type="text" id="nama_penerima" name="nama_penerima" value="<?=$ls_nama_penerima;?>" size="40" maxlength="100" readonly class="disabled" >
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kantor Pembayaran</label>
            <input type="text" id="kode_kantor_pembayar" name="kode_kantor_pembayar" value="<?=$ls_kode_kantor_pembayar;?>" size="25" readonly class="disabled" >            
          </div>																																														
        	<div class="clear"></div>										
					
					</br>
					
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Nominal Gross </label>
            <input type="text" id="nom_manfaat_gross" name="nom_manfaat_gross" value="<?=number_format((float)$ln_nom_manfaat_gross,2,".",",");?>" size="35" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kantor Penetapan</label>
            <input type="text" id="kode_kantor" name="kode_kantor" value="<?=$ls_kode_kantor;?>" size="25" readonly class="disabled" >            
          </div>																																																																				
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">PPN </label>
            <input type="text" id="nom_ppn" name="nom_ppn" value="<?=number_format((float)$ln_nom_ppn,2,".",",");?>" size="24" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">
						<input type="text" id="kode_pajak_ppn" name="kode_pajak_ppn" value="<?=$ls_kode_pajak_ppn;?>" size="10" readonly class="disabled" >				
          </div>																		
          <div class="clear"></div>
										
          <div class="form-row_kiri">
          <label  style = "text-align:right;">PPh </label>
            <input type="text" id="nom_pph" name="nom_pph" value="<?=number_format((float)$ln_nom_pph,2,".",",");?>" size="24" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">
						<input type="text" id="kode_pajak_pph" name="kode_pajak_pph" value="<?=$ls_kode_pajak_pph;?>" size="10" readonly class="disabled" >				
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Pembulatan </label>
            <input type="text" id="nom_pembulatan" name="nom_pembulatan" value="<?=number_format((float)$ln_nom_pembulatan,2,".",",");?>" size="35" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Netto </label>
            <input type="text" id="nom_netto" name="nom_netto" value="<?=number_format((float)$ln_nom_netto,2,".",",");?>" size="35" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>
								
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Sudah Dibayar </label>
            <input type="text" id="nom_sudah_bayar" name="nom_sudah_bayar" value="<?=number_format((float)$ln_nom_sudah_bayar,2,".",",");?>" size="35" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>
								
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Jml yg hrs Dibayar </label>
            <input type="text" id="nom_sisa" name="nom_sisa" value="<?=number_format((float)$ln_nom_sisa,2,".",",");?>" size="32" maxlength="20" style="text-align:left;" onblur="this.value=format_uang(this.value);" readonly class="disabled">				
          </div>																		
          <div class="clear"></div>																																																
					
					</br>
					
					<div class="form-row_kiri">
          <label  style = "text-align:right;">Program &nbsp;</label>
            <input type="hidden" id="kd_prg" name="kd_prg" value="<?=$ls_kd_prg;?>" size="30" maxlength="10" readonly class="disabled" >
						<input type="text" id="nm_prg" name="nm_prg" value="<?=$ls_nm_prg;?>" size="35" readonly class="disabled" >                					
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;"><i><font color="#009999">Ditransfer ke :</font></i></label>
						<input type="text" name="temp1" size="25" style="border-width: 0;text-align:left" readonly>		 	    				
          </div>															
        	<div class="clear"></div>

					<div class="form-row_kiri">
          <label  style = "text-align:right;">Cara Bayar &nbsp;</label>
			<?php 
			if ($ls_kode_pembayaran !="")
			{
				$ls_select_disable = "disabled";
			}
			else
			{
				$ls_select_disable = "";
			}
			?>
            <select size="1" id="kode_cara_bayar" name="kode_cara_bayar" value="<?=$ls_kode_cara_bayar;?>" class="select_format" style="background-color:#ffff99;width:214px;" <?php echo $ls_select_disable; ?> >
            <option value="">- cara byr --</option>
            <? 
            $sql = "select kode, keterangan from sijstk.ms_lookup where tipe='KLMCRBYR' and nvl(aktif,'T')='Y' order by seq";
            $DB->parse($sql);
            $DB->execute();
            while($row = $DB->nextrow())
            {
            echo "<option ";
            if (($row["KODE"]==$ls_kode_cara_bayar && strlen($row["KODE"])==strlen($ls_kode_cara_bayar))) { echo " selected"; }
            echo " value=\"".$row["KODE"]."\">".$row["KETERANGAN"]."</option>";
            }
            ?>
            </select>                					
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Bank *</label>
      			<input type="text" id="bank_penerima" name="bank_penerima" value="<?=$ls_bank_penerima;?>" size="25" maxlength="100" readonly class="disabled">	
          </div>																																														
        	<div class="clear"></div>
										
          <div class="form-row_kiri">
          <label  style = "text-align:right;">Kode Buku &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;*</label>
            <input type="text" id="kode_buku" name="kode_buku" value="<?=$ls_kode_buku;?>" tabindex="2" size="7" maxlength="30" style="background-color:#ffff99;" <?php echo $ls_select_disable; ?>>
            <input type="hidden" id="kode_bank" name="kode_bank" value="<?=$ls_kode_bank;?>" size="5" readonly class="disabled">
						<input type="text" id="nama_bank" name="nama_bank" value="<?=$ls_nama_bank;?>" size="16" readonly class="disabled">
						<input type="text" id="klm_kode_pointer_asal" name="klm_kode_pointer_asal" value="<?=$ls_kode_pointer_asal;?>" size="4" readonly class="disabled">    								
            <?php
			if ($ls_kode_pembayaran == "")
			{
			?>
			<a href="#" onclick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn5004_lov_kodebuku.php?p=pn6001_jht_terlambat_setupbunga_pembayaran_entry.php&a=fpop&b=kode_buku&c=kode_bank&d=nama_bank&e='+fpop.kode_kantor_pembayar.value+'&f='+fpop.kd_prg.value+'&g='+fpop.klm_kode_pointer_asal.value+'&h='+fpop.kode_klaim.value+'','',800,500,1)">							
            <img src="../../images/help.png" alt="Cari Bank Penempatan" border="0" align="absmiddle"></a>	
			<?php 
			}
			?>			
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">No Rekening *</label>
      			<input type="text" id="no_rekening_penerima" name="no_rekening_penerima" value="<?=$ls_no_rekening_penerima;?>" size="25" maxlength="30" readonly class="disabled">	
          </div>																																																																																															
          <div class="clear"></div>					

          <div class="form-row_kiri">
          <label  style = "text-align:right;">Tgl Pembayaran</label>			
          	<input type="text" id="tgl_pembayaran" name="tgl_pembayaran" value="<?=$ld_tgl_pembayaran;?>" size="30" maxlength="10" readonly class="disabled">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">A/N *</label>
      			<input type="text" id="nama_rekening_penerima" name="nama_rekening_penerima" value="<?=$ls_nama_rekening_penerima;?>" size="25" maxlength="100" readonly class="disabled">	
          </div>																																															
					<div class="clear"></div>
																														
          <div class="form-row_kiri">
          <label style = "text-align:right;">Keterangan &nbsp;</label>
          	<textarea cols="255" rows="1" style="width:277px" id="keterangan" name="keterangan" tabindex="24" onkeypress="return canAddCharacter(this, 300)" onchange="trimLength(this, 300)"><?=$ls_keterangan;?></textarea>   					
          </div>								
          <div class="clear"></div>
				</fieldset>	
								
        <? 					
        if(!empty($ls_kode_klaim))
        {
        ?>			 	
          <div id="buttonbox" style="width:800px;text-align:center;">        			 
          <?if ($ls_task == "New" && $ls_kode_pembayaran=="")
					{
  					?>
  					<input type="button" class="btn green" id="btnsimpan" name="btnsimpan" value="               BAYAR               " onclick="if(confirm('Apakah anda yakin akan melakukan pembayaran terhadap klaim ini..?')) fl_js_val_simpan();">
            <?
					}
					if(!empty($ls_kode_pembayaran))
          {
  					?>
  					<input type="button" class="btn green" id="btntapcetak" name="btntapcetak" value="             CETAK             " onClick="NewWindow('http://<?=$HTTP_HOST;?>/mod_pn/ajax/pn6001_jht_terlambat_setupbunga_pembayaran_cetak.php?kode_klaim=<?=$ls_kode_klaim;?>&kode_pembayaran=<?=$ls_kode_pembayaran;?>&mid=<?=$mid;?>','Cetak Penetapan Klaim',700,480,'no')"/>
    				&nbsp;
						<?
					}
					?>
					<input type="button" class="btn green" id="close" name="close" onclick="window.close();" value="               TUTUP               " />       					
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