<?
$pagetype="report";
$gs_pagetitle = "PN500101 - PROFILE TENAGA KERJA";
require_once "../../includes/header_app.php";
include '../../includes/fungsi_rpt.php';
$DB2 = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
/*--------------------- Form History -----------------------------------------
File: kb5010_detil.php

Deskripsi:
-----------
File ini dipergunakan untuk infotmasi tk bpu

Author:
--------
Tim SIJSTK

Histori Perubahan:
--------------------
15/09/2017 - Tim SIJSTK
Pembuatan Form
  
-------------------- End Form History --------------------------------------*/
$ls_kode_tk	 		 		 = !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];
$ls_kode_perusahaan	 = !isset($_GET['kode_perusahaan']) ? $_POST['kode_perusahaan'] : $_GET['kode_perusahaan'];
$ls_kode_divisi	 		 = !isset($_GET['kode_divisi']) ? $_POST['kode_divisi'] : $_GET['kode_divisi'];
$ls_kode_segmen	 		 = !isset($_GET['kode_segmen']) ? $_POST['kode_segmen'] : $_GET['kode_segmen'];
if ($ls_kode_segmen=="")
{
 	 $ls_kode_segmen = "BPU";
}

$ls_sender 					 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_sender_mid 			 = !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];
$ls_task 					 	 = !isset($_GET['task']) ? $_POST['task'] : $_GET['task'];

$ls_activetab  				 = !isset($_GET['activetab']) ? $_POST['activetab'] : $_GET['activetab'];  
if ($ls_activetab=="")
{
 	$ls_activetab = "1";
}

$ls_rg_jenisprg				= !isset($_GET['rg_jenisprg']) ? $_POST['rg_jenisprg'] : $_GET['rg_jenisprg'];
$ls_tahun_prg			 	 	= $_POST['tahun_prg'];
if ($ls_tahun_prg=="")
{
    $sql = 	"select to_char(sysdate,'yyyy') as v_tahun from dual ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tahun_prg = $row["V_TAHUN"];
}
$ls_tahun_saldo			 = $_POST['tahun_saldo'];
if ($ls_tahun_saldo=="")
{
    $sql = 	"select to_char(sysdate,'yyyy') as v_tahun from dual ";
    $DB->parse($sql);
    $DB->execute();
    $row = $DB->nextrow();
    $ls_tahun_saldo = $row["V_TAHUN"];
}

//ambil nama wadah -------------------------------------------------------------
$sql = "select npp, nama_perusahaan from sijstk.kn_perusahaan where kode_perusahaan = '$ls_kode_perusahaan'";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();	
$ls_nama_perusahaan	= $row['NAMA_PERUSAHAAN'];	
$ls_npp	= $row['NPP'];

//ambil nama kolektor ----------------------------------------------------------
$sql = "select nama_divisi from sijstk.kn_divisi ".
		 	 "where kode_perusahaan = '$ls_kode_perusahaan' ".
			 "and kode_segmen = '$ls_kode_segmen' ".
			 "and kode_divisi = '$ls_kode_divisi' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();	
$ls_nama_divisi	= $row['NAMA_DIVISI'];	

//query data -------------------------------------------------------------------
$sql2 = "select 
            a.kode_tk, a.kode_tk_gabung, a.kode_tk_id, 
            a.kode_tk_ref, a.kpj, a.suffix, 
            a.nama_lengkap, a.telepon_area_rumah, a.telepon_rumah, 
            a.telepon_area_kantor, a.telepon_kantor, a.telepon_ext_kantor, 
            a.handphone, a.fax_area, a.fax, 
            a.email, a.surat_menyurat_ke, a.tempat_lahir, to_char(a.tgl_lahir,'dd/mm/yyyy') tgl_lahir, 
            a.nama_ibu_kandung, decode(nvl(a.status_kawin,'T'),'Y','MENIKAH','T','BELUM MENIKAH',a.status_kawin) status_kawin, 
            a.golongan_darah, decode(a.jenis_kelamin,'L','LAKI-LAKI','P','PEREMPUAN',a.jenis_kelamin) jenis_kelamin, a.jenis_identitas, 
            a.nomor_identitas, a.masa_berlaku_identitas, a.status_valid_identitas,
						decode(a.status_valid_identitas,'Y','NIK VALID','NIK TIDAK VALID') status_valid_identitas, 
            a.kode_negara, a.npwp, a.jenis_pekerjaan, 
            a.lokasi_pekerjaan,(select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.lokasi_pekerjaan) nama_lokasi_pekerjaan, 
						a.jam_kerja_awal, a.jam_kerja_akhir,
						a.jam_kerja_awal||'s/d'||a.jam_kerja_akhir ket_jam_kerja
       from sijstk.kn_tk a
       where kode_tk = '$ls_kode_tk'";
       
$sql = " select a.kode_tk,
         a.kode_tk_gabung,
         a.kpj,
         a.telepon_area_rumah,
         a.telepon_rumah,
         a.telepon_area_kantor,
         a.telepon_kantor,
         a.telepon_ext_kantor,
         a.handphone,
         a.fax_area,
         a.fax,
         a.email,
         a.surat_menyurat_ke,
         a.tempat_lahir,
         TO_CHAR (a.tgl_lahir, 'dd/mm/yyyy') tgl_lahir,
         a.nama_ibu_kandung,
         DECODE (NVL (a.status_kawin, 'T'),
                 'Y', 'MENIKAH',
                 'T', 'BELUM MENIKAH',
                 a.status_kawin)
            status_kawin,
         a.golongan_darah,
         DECODE (a.jenis_kelamin,
                 'L', 'LAKI-LAKI',
                 'P', 'PEREMPUAN',
                 a.jenis_kelamin)
            jenis_kelamin,
         a.jenis_identitas,
         a.nomor_identitas,
         a.status_valid_identitas,
         DECODE (a.status_valid_identitas, 'Y', 'NIK VALID', 'NIK TIDAK VALID')
            status_valid_identitas,
         a.kode_negara,
         a.npwp
      from sijstk.vw_kn_tk a where a.kode_tk = '$ls_kode_tk'";

echo $sql;
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();	
$ls_kode_tk									= $row['KODE_TK'];					
$ls_kode_tk_gabung					= $row['KODE_TK_GABUNG'];
$ls_kode_tk_id 							= $row['KODE_TK_ID'];
$ls_kode_tk_ref							= $row['KODE_TK_REF'];
$ls_kpj											= $row['KPJ'];
$ls_suffix 									= $row['SUFFIX'];
$ls_nama_lengkap						= $row['NAMA_LENGKAP'];
$ls_telepon_area_rumah			= $row['TELEPON_AREA_RUMAH'];
$ls_telepon_rumah 					= $row['TELEPON_RUMAH'];
$ls_telepon_area_kantor			= $row['TELEPON_AREA_KANTOR'];
$ls_telepon_kantor					= $row['TELEPON_KANTOR'];
$ls_telepon_ext_kantor			= $row['TELEPON_EXT_KANTOR'];
$ls_handphone								= $row['HANDPHONE'];
$ls_fax_area								= $row['FAX_AREA'];
$ls_fax 										= $row['FAX'];
$ls_email										= $row['EMAIL'];
$ls_surat_menyurat_ke				= $row['SURAT_MENYURAT_KE'];
$ls_tempat_lahir 						= $row['TEMPAT_LAHIR'];
$ld_tgl_lahir								= $row['TGL_LAHIR'];
$ls_nama_ibu_kandung				= $row['NAMA_IBU_KANDUNG'];
$ls_status_kawin 						= $row['STATUS_KAWIN'];
$ls_golongan_darah					= $row['GOLONGAN_DARAH'];
$ls_jenis_kelamin						= $row['JENIS_KELAMIN'];
$ls_jenis_identitas 				= $row['JENIS_IDENTITAS'];
$ls_nomor_identitas					= $row['NOMOR_IDENTITAS'];
$ls_masa_berlaku_identitas	= $row['MASA_BERLAKU_IDENTITAS'];
$ls_status_valid_identitas 	= $row['STATUS_VALID_IDENTITAS'];	
$ls_kode_negara							= $row['KODE_NEGARA'];
$ls_npwp										= $row['NPWP'];
$ls_jenis_pekerjaan 				= $row['JENIS_PEKERJAAN'];
$ls_lokasi_pekerjaan				= $row['LOKASI_PEKERJAAN'];
$ls_nama_lokasi_pekerjaan		= $row['NAMA_LOKASI_PEKERJAAN'];
$ls_jam_kerja_awal					= $row['JAM_KERJA_AWAL'];
$ls_jam_kerja_akhir					= $row['JAM_KERJA_AKHIR'];
$ls_ket_jam_kerja						= $row['KET_JAM_KERJA'];

//alamat sesuai identitas ------------------------------------------------------
$sql = "select 
            kode_tk, tipe, alamat, rt, rw, kode_kelurahan,
            (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan and kode_pos = a.kode_pos and kode_kecamatan=a.kode_kecamatan) nama_kelurahan, 
            kode_kecamatan, 
            (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan, 
            kode_kabupaten, 
            (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten, 
            (select nama_propinsi from sijstk.ms_propinsi x, sijstk.ms_kabupaten y where x.kode_propinsi = y.kode_propinsi and y.kode_kabupaten = a.kode_kabupaten) nama_propinsi,
            kode_pos
        from sijstk.kn_tk_alamat a 
        where kode_tk = '$ls_kode_tk'
        and tipe = 'I' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();	
$ls_i_alamat					= $row['ALAMAT'];	
$ls_i_rt							= $row['RT'];
$ls_i_rw							= $row['RW'];
$ls_i_kode_kelurahan	= $row['KODE_KELURAHAN'];
$ls_i_nama_kelurahan	= $row['NAMA_KELURAHAN'];
$ls_i_kode_kecamatan	= $row['KODE_KECAMATAN'];
$ls_i_nama_kecamatan	= $row['NAMA_KECAMATAN'];
$ls_i_kode_kabupaten	= $row['KODE_KABUPATEN'];
$ls_i_nama_kabupaten	= $row['NAMA_KABUPATEN'];
$ls_i_nama_propinsi		= $row['NAMA_PROPINSI'];
$ls_i_kode_pos				= $row['KODE_POS'];
		
//alamat surat menyurat ------------------------------------------------------
$sql = "select 
            kode_tk, tipe, alamat, rt, rw, kode_kelurahan,
            (select nama_kelurahan from sijstk.ms_kelurahan where kode_kelurahan = a.kode_kelurahan and kode_pos = a.kode_pos and kode_kecamatan=a.kode_kecamatan) nama_kelurahan, 
            kode_kecamatan, 
            (select nama_kecamatan from sijstk.ms_kecamatan where kode_kecamatan = a.kode_kecamatan) nama_kecamatan, 
            kode_kabupaten, 
            (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten, 
            (select nama_propinsi from sijstk.ms_propinsi x, sijstk.ms_kabupaten y where x.kode_propinsi = y.kode_propinsi and y.kode_kabupaten = a.kode_kabupaten) nama_propinsi,
            kode_pos
        from sijstk.kn_tk_alamat a 
        where kode_tk = '$ls_kode_tk'
        and tipe = 'S' ";
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();	
$ls_s_alamat					= $row['ALAMAT'];	
$ls_s_rt							= $row['RT'];
$ls_s_rw							= $row['RW'];
$ls_s_kode_kelurahan	= $row['KODE_KELURAHAN'];
$ls_s_nama_kelurahan	= $row['NAMA_KELURAHAN'];
$ls_s_kode_kecamatan	= $row['KODE_KECAMATAN'];
$ls_s_nama_kecamatan	= $row['NAMA_KECAMATAN'];
$ls_s_kode_kabupaten	= $row['KODE_KABUPATEN'];
$ls_s_nama_kabupaten	= $row['NAMA_KABUPATEN'];
$ls_s_nama_propinsi		= $row['NAMA_PROPINSI'];
$ls_s_kode_pos				= $row['KODE_POS'];

if(isset($_POST["btncetaksaldo"]))
{	
	/*---------Kirim Parameter----------------------------------------------------*/
	
  $sql = "select to_char(sysdate,'yyyymmdd') tgllap from dual";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();	
	$ls_laptgllap 	= $row["TGLLAP"];
	
	$ls_user_param .= " QTGLLAP='$ls_laptgllap'";
	$ls_user_param .= " QUSER='$username'";
	$ls_user_param .= " QSEGMEN='$ls_kode_segmen'";
	
	$ls_user_param .= " QKODEPERUSAHAAN='$ls_kode_perusahaan'";
	$ls_user_param .= " QKODETK='$ls_kode_tk'";
	$ls_user_param .= " QTHN='$ls_tahun_saldo'";
	
	//CETAK REPORT ---------------------------------------------------------------
	
  $ls_nama_rpt 	.= "KBR502101.rdf";
												
	/*END SET LAPORAN YANG AKAN DIPANGGIL ----------------------------------*/
	if ($ls_error=="1")
	{}
	else
	{							
		$ls_pdf = $ls_nama_rpt;
		exec_rpt_sijstk($paramform="no",$ls_pdf,$ls_nama_rpt,$ls_user_param);
	}
}		
		
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<link href="../assets/easyui/themes/default/easyui.css" rel="stylesheet">
<script type="text/javascript"></script>

<script type="text/javascript" src="../../highcharts/js/jquery.min.js"></script>
<script src="../../highcharts/js/highcharts.js"></script>
<script language="javascript">
  function NewWindow4(mypage,myname,w,h,scroll){
    var openwin = window.parent.Ext.create('Ext.window.Window', {
      title: myname,
      collapsible: true,
      animCollapse: true,
      
      maximizable: true,
      width: w,
      height: h,
      minWidth: 450,
      minHeight: 250,
      layout: 'fit',
      html:'<iframe src="'+mypage+'"  height="100%" width="100%" frameborder="0" style="border:0; height:100%; width:100%;scrollbars=no;"></iframe>',
    });
    openwin.show();
  }	
  
  function confirmDelete(delUrl) {
  	if (confirm("Are you sure you want to delete this record")) {
  	document.location = delUrl;
  	}
  }
	
  function refreshParent() 
  {																						
    <?php	
    if($ls_form_penetapan!=''){			
    	echo "window.location.replace('$ls_form_penetapan?task=Edit&sender=pn5002.php&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim');";	
    }
    ?>	
  }
						
</script>
<link rel="stylesheet" type="text/css" href="../../style/tabs/css/screen.css" media="screen" />
<script type="text/javascript" src="../../style/tabs/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
  $('ul#nav li a').removeClass('active'); 																			//menghilangkan class active (yang tampil)			
  $('#t'+<?=$ls_activetab;?>).addClass("active"); 															// menambahkan class active pada link yang diklik
  $('.tab_konten').hide(); 																											// menutup semua konten tab					
  $('#tab'+<?=$ls_activetab;?>).fadeIn('slow'); 																//tab pertama ditampilkan								 
  //$('#tab1').fadeIn('slow'); 																									//tab pertama ditampilkan
  
  // jika link tab di klik
  $('ul#nav li a').click(function() 
  { 					 																																				
    $('ul#nav li a').removeClass('active'); 																		//menghilangkan class active (yang tampil)			
    $(this).addClass("active"); 																								// menambahkan class active pada link yang diklik
    $('.tab_konten').hide(); 																										// menutup semua konten tab
    var aktif = $(this).attr('href'); 																					// mencari mana tab yang harus ditampilkan
    var aktif_idx = aktif.substr(4,5);
    document.getElementById('activetab').value = aktif_idx;
    //alert(aktif_idx);
    $(aktif).fadeIn('slow'); 																										// tab yang dipilih, ditampilkan
    return false;
  });		
});
</script>	
<?
//--------------------- end fungsi lokal javascript ----------------------------
?>

<table class="captionentry">
<tr> 
<td align="left"><b><?=$gs_pagetitle;?></b> </td>						 
</tr>
</table>

<div id="formframe">
  <div id="formKiri" style="width:1000px">
		<input type="hidden" name="TYPE" value="<?=$_REQUEST["task"];?>">
    <input type="hidden" id="DATAID" name="DATAID" value="<?=$_REQUEST["dataid"];?>">
		<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
    <input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">		
		<input type="hidden" id="task" name="task" value="<?=$ls_task;?>">
		<input type="hidden" id="kode_iuran" name="kode_iuran" value="<?=$ls_kode_iuran;?>">
		<input type="hidden" id="activetab" name="activetab" value="<?=$ls_activetab;?>">
		<input type="hidden" id="kode_segmen" name="kode_segmen" value="<?=$ls_kode_segmen;?>">
						 
    <ul id="nav">
      <li><a href="#tab1" id="t1" class="active">Profile Tenaga Kerja</a></li>			
      <li><a href="#tab2" id="t2">Detail Program</a></li>
      <li><a href="#tab3" id="t3">History Saldo</a></li>
    </ul>
		
  	<div style="display: none;" id="tab1" class="tab_konten">
    	<div id="konten">
				<fieldset><legend>Profile Tenaga Kerja</legend>
          <div class="form-row_kiri">
          <label style = "text-align:right;">NIK &nbsp; &nbsp; &nbsp;</label>	    				
          	<input type="text" id="nomor_identitas" name="nomor_identitas" value="<?=$ls_nomor_identitas;?>" size="23" readonly class="disabled">
						<input type="text" id="jenis_identitas" name="jenis_identitas" value="<?=$ls_jenis_identitas;?>" size="3" readonly class="disabled">
						<input type="text" id="status_valid_identitas" name="status_valid_identitas" value="<?=$ls_status_valid_identitas;?>" size="15" readonly class="disabled">
					</div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kode TK &nbsp; &nbsp; &nbsp;</label>	    				
          	<input type="text" id="kode_tk" name="kode_tk" value="<?=$ls_kode_tk;?>" size="40" readonly class="disabled">
					</div>																												
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Nama Lengkap&nbsp;</label>	    				
          	<input type="text" id="nama_lengkap" name="nama_lengkap" value="<?=$ls_nama_lengkap;?>" size="50" readonly class="disabled">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">TK Amalgamasi&nbsp; &nbsp; &nbsp;</label>	    				
          	<input type="text" id="kode_tk_gabung" name="kode_tk_gabung" value="<?=$ls_kode_tk_gabung;?>" size="37" readonly class="disabled">
						<input type="button" id="btn_find_tk_gabung" name="btn_find_tk_gabung" value="..">
					</div>																												
          <div class="clear"></div>
							
          <div class="form-row_kiri">
          <label style = "text-align:right;">Tempat, Tgl Lahir &nbsp;</label>	    	
						<input type="text" id="tempat_lahir" name="tempat_lahir" value="<?=$ls_tempat_lahir;?>" size="30" readonly class="disabled">
						<input type="text" id="tgl_lahir" name="tgl_lahir" value="<?=$ld_tgl_lahir;?>" size="15" readonly class="disabled">	 			
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Wadah&nbsp; &nbsp; &nbsp;</label>	  
						<input type="text" id="npp" name="npp" value="<?=$ls_npp;?>" size="10" readonly class="disabled">		   				
          	<input type="text" id="nama_perusahaan" name="nama_perusahaan" value="<?=$ls_nama_perusahaan;?>" size="26" readonly class="disabled">
						<input type="hidden" id="kode_perusahaan" name="kode_perusahaan" value="<?=$ls_kode_perusahaan;?>">
					</div>																													
          <div class="clear"></div>	

          <div class="form-row_kiri">
          <label style = "text-align:right;">Nama Ibu Kandung&nbsp;</label>	    				
          	<input type="text" id="nama_ibu_kandung" name="nama_ibu_kandung" value="<?=$ls_nama_ibu_kandung;?>" size="45" maxlength="50" readonly class="disabled">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kolektor&nbsp; &nbsp; &nbsp;</label>	    				
						<input type="text" id="kode_divisi" name="kode_divisi" value="<?=$ls_kode_divisi;?>" size="3" readonly class="disabled">		 
          	<input type="text" id="nama_divisi" name="nama_divisi" value="<?=$ls_nama_divisi;?>" size="33" readonly class="disabled">
					</div>																												
          <div class="clear"></div>
										
          <div class="form-row_kiri">
          <label style = "text-align:right;">Jenis Kelamin&nbsp;&nbsp;&nbsp;</label>	    				
          	<input type="text" id="jenis_kelamin" name="jenis_kelamin" value="<?=$ls_jenis_kelamin;?>" size="40" readonly class="disabled">
					</div>																							
          <div class="clear"></div>
					
          <div class="form-row_kiri">
          <label style = "text-align:right;">Golongan Darah&nbsp;</label>	    				
						<input type="text" id="golongan_darah" name="golongan_darah" value="<?=$ls_golongan_darah;?>" size="35" readonly class="disabled">
          </div>																							
          <div class="clear"></div>
									
          <div class="form-row_kiri">
          <label style = "text-align:right;">Status Marital&nbsp;</label>	    				
						<input type="text" id="status_kawin" name="status_kawin" value="<?=$ls_status_kawin;?>" size="35" readonly class="disabled">
          </div>																							
          <div class="clear"></div>
					
					</br>
					
					<div class="form-row_kiri">
          <label style = "text-align:right;">Jenis Pekerjaan&nbsp;</label>	    				
						<input type="text" id="jenis_pekerjaan" name="jenis_pekerjaan" value="<?=$ls_jenis_pekerjaan;?>" size="40" maxlength="100" readonly class="disabled">
          </div>																												
          <div class="clear"></div>
							
					<div class="form-row_kiri">
          <label style = "text-align:right;">Lokasi Pekerjaan&nbsp;</label>	    				
						<input type="hidden" id="lokasi_pekerjaan" name="lokasi_pekerjaan" value="<?=$ls_lokasi_pekerjaan;?>" size="40" maxlength="20">
						<input type="text" id="nama_lokasi_pekerjaan" name="nama_lokasi_pekerjaan" value="<?=$ls_nama_lokasi_pekerjaan;?>" size="40" readonly class="disabled">
          </div>																											
          <div class="clear"></div>

					<div class="form-row_kiri">
          <label style = "text-align:right;">Jam Kerja&nbsp;</label>	    				
      			<select size="1" id="jam_kerja_awal" name="jam_kerja_awal" class="select_format" style="width:108px;" disabled>
  						  <? 
    							switch($ls_jam_kerja_awal)
    							{
    							  case '00:00' : $aw00="selected"; break;
  									case '01:00' : $aw01="selected"; break;
  									case '02:00' : $aw02="selected"; break;
  									case '03:00' : $aw03="selected"; break;
  									case '04:00' : $aw04="selected"; break;
  									case '05:00' : $aw05="selected"; break;
  									case '06:00' : $aw06="selected"; break;
  									case '07:00' : $aw07="selected"; break;
  									case '08:00' : $aw08="selected"; break;
  									case '09:00' : $aw09="selected"; break;
  									case '10:00' : $aw10="selected"; break;
  									case '11:00' : $aw11="selected"; break;
  									case '12:00' : $aw12="selected"; break;
  									case '13:00' : $aw13="selected"; break;
  									case '14:00' : $aw14="selected"; break;
  									case '15:00' : $aw15="selected"; break;
  									case '16:00' : $aw16="selected"; break;
  									case '17:00' : $aw17="selected"; break;
  									case '18:00' : $aw18="selected"; break;
  									case '19:00' : $aw19="selected"; break;
  									case '20:00' : $aw20="selected"; break;
  									case '21:00' : $aw21="selected"; break;
										case '22:00' : $aw22="selected"; break;
										case '23:00' : $aw23="selected"; break;																																																																			
    							}
    							?>
  								<option value="00:00" <?=$aw00;?>>00:00</option>
  								<option value="01:00" <?=$aw01;?>>01:00</option>
									<option value="02:00" <?=$aw02;?>>02:00</option>
									<option value="03:00" <?=$aw03;?>>03:00</option>
									<option value="04:00" <?=$aw04;?>>04:00</option>
									<option value="05:00" <?=$aw05;?>>05:00</option>
									<option value="06:00" <?=$aw06;?>>06:00</option>
									<option value="07:00" <?=$aw07;?>>07:00</option>
									<option value="08:00" <?=$aw08;?>>08:00</option>
									<option value="09:00" <?=$aw09;?>>09:00</option>
									<option value="10:00" <?=$aw10;?>>10:00</option>
									<option value="11:00" <?=$aw11;?>>11:00</option>
									<option value="12:00" <?=$aw12;?>>12:00</option>
									<option value="13:00" <?=$aw13;?>>13:00</option>
									<option value="14:00" <?=$aw14;?>>14:00</option>
									<option value="15:00" <?=$aw15;?>>15:00</option>
									<option value="16:00" <?=$aw16;?>>16:00</option>
									<option value="17:00" <?=$aw17;?>>17:00</option>
									<option value="18:00" <?=$aw18;?>>18:00</option>
									<option value="19:00" <?=$aw19;?>>19:00</option>
									<option value="20:00" <?=$aw20;?>>20:00</option>
									<option value="21:00" <?=$aw21;?>>21:00</option>
									<option value="22:00" <?=$aw22;?>>22:00</option>
									<option value="23:00" <?=$aw23;?>>23:00</option>																						
      			</select>								 
						s/d
      			<select size="1" id="jam_kerja_akhir" name="jam_kerja_akhir" class="select_format" style="width:108px;" disabled>
  						  <? 
    							switch($ls_jam_kerja_akhir)
    							{
    							  case '00:00' : $ak00="selected"; break;
  									case '01:00' : $ak01="selected"; break;
  									case '02:00' : $ak02="selected"; break;
  									case '03:00' : $ak03="selected"; break;
  									case '04:00' : $ak04="selected"; break;
  									case '05:00' : $ak05="selected"; break;
  									case '06:00' : $ak06="selected"; break;
  									case '07:00' : $ak07="selected"; break;
  									case '08:00' : $ak08="selected"; break;
  									case '09:00' : $ak09="selected"; break;
  									case '10:00' : $ak10="selected"; break;
  									case '11:00' : $ak11="selected"; break;
  									case '12:00' : $ak12="selected"; break;
  									case '13:00' : $ak13="selected"; break;
  									case '14:00' : $ak14="selected"; break;
  									case '15:00' : $ak15="selected"; break;
  									case '16:00' : $ak16="selected"; break;
  									case '17:00' : $ak17="selected"; break;
  									case '18:00' : $ak18="selected"; break;
  									case '19:00' : $ak19="selected"; break;
  									case '20:00' : $ak20="selected"; break;
  									case '21:00' : $ak21="selected"; break;
										case '22:00' : $ak22="selected"; break;
										case '23:00' : $ak23="selected"; break;																																																																			
    							}
    							?>
  								<option value="00:00" <?=$ak00;?>>00:00</option>
  								<option value="01:00" <?=$ak01;?>>01:00</option>
									<option value="02:00" <?=$ak02;?>>02:00</option>
									<option value="03:00" <?=$ak03;?>>03:00</option>
									<option value="04:00" <?=$ak04;?>>04:00</option>
									<option value="05:00" <?=$ak05;?>>05:00</option>
									<option value="06:00" <?=$ak06;?>>06:00</option>
									<option value="07:00" <?=$ak07;?>>07:00</option>
									<option value="08:00" <?=$ak08;?>>08:00</option>
									<option value="09:00" <?=$ak09;?>>09:00</option>
									<option value="10:00" <?=$ak10;?>>10:00</option>
									<option value="11:00" <?=$ak11;?>>11:00</option>
									<option value="12:00" <?=$ak12;?>>12:00</option>
									<option value="13:00" <?=$ak13;?>>13:00</option>
									<option value="14:00" <?=$ak14;?>>14:00</option>
									<option value="15:00" <?=$ak15;?>>15:00</option>
									<option value="16:00" <?=$ak16;?>>16:00</option>
									<option value="17:00" <?=$ak17;?>>17:00</option>
									<option value="18:00" <?=$ak18;?>>18:00</option>
									<option value="19:00" <?=$ak19;?>>19:00</option>
									<option value="20:00" <?=$ak20;?>>20:00</option>
									<option value="21:00" <?=$ak21;?>>21:00</option>
									<option value="22:00" <?=$ak22;?>>22:00</option>
									<option value="23:00" <?=$ak23;?>>23:00</option>																						
      			</select>		
          </div>																													
          <div class="clear"></div>

  				</br>																																					
				</fieldset>	
				
				<fieldset><legend>Alamat</legend>
          <div class="form-row_kiri">
          <label style = "text-align:right;"><i><font color="#009999">Sesuai Identitas :</font></i></label>	    				
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;"><i><font color="#009999">Surat Menyurat :</font></i></label>				
            <input type="text" name="temp1" size="40" style="border-width: 0;text-align:left" readonly>
          </div>											
          <div class="clear"></div>			

          <div class="form-row_kiri">
          <label style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;*</label>	    				
          	<input type="text" id="i_alamat" name="i_alamat" value="<?=$ls_i_alamat;?>" size="45" maxlength="300" readonly class="disabled">
          </div>
          <div class="form-row_kanan">
          <label  style = "text-align:right;">Alamat &nbsp;&nbsp;&nbsp;*</label>
            <input type="text" id="s_alamat" name="s_alamat" value="<?=$ls_s_alamat;?>" size="40" maxlength="300" readonly class="disabled">               									
          </div>															
          <div class="clear"></div>
          
          <div class="form-row_kiri">
          <label style = "text-align:right;">RT/RW</label>	    				
            <input type="text" id="i_rt" name="i_rt" value="<?=$ls_i_rt;?>" size="15" maxlength="5" readonly class="disabled">
            /
            <input type="text" id="i_rw" name="i_rw" value="<?=$ls_i_rw;?>" size="25" maxlength="5" readonly class="disabled"">
					</div>
          <div class="form-row_kanan">
          <label  style = "text-align:right;">RT/RW</label>
            <input type="text" id="s_rt" name="s_rt" value="<?=$ls_s_rt;?>" size="15" maxlength="5" readonly class="disabled">
            /
            <input type="text" id="s_rw" name="s_rw" value="<?=$ls_s_rw;?>" size="20" maxlength="5" readonly class="disabled">
					</div>																
          <div class="clear"></div>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Kode Pos *</label>	    				
            <input type="text" id="i_kode_pos" name="i_kode_pos" value="<?=$ls_i_kode_pos;?>" size="40" readonly class="disabled">					      						      						
          </div>
          <div class="form-row_kanan">
          <label  style = "text-align:right;">Kode Pos *</label>
            <input type="text" id="s_kode_pos" name="s_kode_pos" value="<?=$ls_s_kode_pos;?>" size="40" readonly class="disabled">					    									
          </div>																
          <div class="clear"></div>
					          
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kelurahan &nbsp;</label>		 	    				
            <input type="hidden" id="i_kode_kelurahan" name="i_kode_kelurahan" value="<?=$ls_i_kode_kelurahan;?>">
            <input type="text" id="i_nama_kelurahan" name="i_nama_kelurahan" value="<?=$ls_i_nama_kelurahan;?>" size="45" readonly class="disabled">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kelurahan &nbsp;</label>		 	    				
            <input type="hidden" id="s_kode_kelurahan" name="s_kode_kelurahan" value="<?=$ls_s_kode_kelurahan;?>">
            <input type="text" id="s_nama_kelurahan" name="s_nama_kelurahan" value="<?=$ls_s_nama_kelurahan;?>" size="40" readonly class="disabled">
					</div>
					<div class="clear"></div>
          																		
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kecamatan &nbsp;</label>		 	    				
            <input type="hidden" id="i_kode_kecamatan" name="i_kode_kecamatan" value="<?=$ls_i_kode_kecamatan;?>">
            <input type="text" id="i_nama_kecamatan" name="i_nama_kecamatan" value="<?=$ls_i_nama_kecamatan;?>" size="42" readonly class="disabled">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kecamatan &nbsp;</label>		 	    				
            <input type="hidden" id="s_kode_kecamatan" name="s_kode_kecamatan" value="<?=$ls_s_kode_kecamatan;?>">
            <input type="text" id="s_nama_kecamatan" name="s_nama_kecamatan" value="<?=$ls_s_nama_kecamatan;?>" size="40" readonly class="disabled">
					</div>
					<div class="clear"></div>
          
          <div class="form-row_kiri">
          <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="hidden" id="i_kode_kabupaten" name="i_kode_kabupaten" value="<?=$ls_i_kode_kabupaten;?>">
            <input type="text" id="i_nama_kabupaten" name="i_nama_kabupaten" value="<?=$ls_i_nama_kabupaten;?>" size="42" readonly class="disabled">										
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Kabupaten &nbsp;</label>		 	    				
            <input type="hidden" id="s_kode_kabupaten" name="s_kode_kabupaten" value="<?=$ls_s_kode_kabupaten;?>">
            <input type="text" id="s_nama_kabupaten" name="s_nama_kabupaten" value="<?=$ls_s_nama_kabupaten;?>" size="40" readonly class="disabled">	
					</div>								
          <div class="clear"></div>																   
          
          <div class="form-row_kiri">
          <label style = "text-align:right;">Propinsi &nbsp;</label>		 	    				
            <input type="text" id="i_nama_propinsi" name="i_nama_propinsi" value="<?=$ls_i_nama_propinsi;?>" size="38" readonly class="disabled">
            <input type="hidden" id="i_kode_propinsi" name="i_kode_propinsi" value="<?=$ls_i_kode_propinsi;?>">
          </div>
          <div class="form-row_kanan">
          <label style = "text-align:right;">Propinsi &nbsp;</label>		 	    				
            <input type="text" id="s_nama_propinsi" name="s_nama_propinsi" value="<?=$ls_s_nama_propinsi;?>" size="40" readonly class="disabled">
            <input type="hidden" id="s_kode_propinsi" name="s_kode_propinsi" value="<?=$ls_s_kode_propinsi;?>">
					</div>														
          <div class="clear"></div>
					
					</br>

          <div class="form-row_kiri">
          <label style = "text-align:right;"><i><font color="#009999">Kontak :</font></i></label>	    				
          </div>										
          <div class="clear"></div>	
										
					<div class="form-row_kiri">
          <label style = "text-align:right;">Telp. Rumah&nbsp;</label>	    				
						<input type="text" id="telepon_area_rumah" name="telepon_area_rumah" value="<?=$ls_telepon_area_rumah;?>" size="5" maxlength="5" readonly class="disabled">&nbsp;-
						<input type="text" id="telepon_rumah" name="telepon_rumah" value="<?=$ls_telepon_rumah;?>" size="25" maxlength="20" readonly class="disabled"">
          </div>																											
          <div class="clear"></div>
					
					<div class="form-row_kiri">
          <label style = "text-align:right;">No. HP&nbsp; *</label>	    				
						<input type="text" id="handphone" name="handphone" value="<?=$ls_handphone;?>" size="25" maxlength="20" readonly class="disabled">
						<input type="checkbox" class="cebox" id="status_valid_handphone" name="status_valid_handphone" value="<?=$ls_status_valid_handphone;?>" disabled <?=$ls_status_valid_handphone=="Y" || $ls_status_valid_handphone=="ON" || $ls_status_valid_handphone=="on" ? "checked" : "";?>> Valid
          </div>																											
          <div class="clear"></div>
							
					<div class="form-row_kiri">
          <label style = "text-align:right;">Email&nbsp;</label>	    				
						<input type="text" id="email" name="email" value="<?=$ls_email;?>" size="35" maxlength="200" readonly class="disabled">
          </div>																										
          <div class="clear"></div>

					<div class="form-row_kiri">
          <label style = "text-align:right;">NPWP&nbsp; *</label>	    				
						<input type="text" id="npwp" name="npwp" value="<?=$ls_npwp;?>" size="35" maxlength="18" readonly class="disabled">
          </div>																													
          <div class="clear"></div>
										
					</br>

          <div class="form-row_kiri">
          <label style = "text-align:right;">Surat Menyurat Ke:</label>		 	    				
						<? 
            switch($ls_surat_menyurat_ke)
            {
              case 'S' : $sels="checked"; break;
              case 'E' : $sele="checked"; break;
            }
            ?>
						<input type="radio" name="rg_surat_menyurat_ke" value="S"  <?=$sels;?>>&nbsp;<font  color="#009999">Alamat Surat/Menyurat</font>&nbsp; | &nbsp;
						<input type="radio" name="rg_surat_menyurat_ke" value="E"  <?=$sele;?>>&nbsp;<font  color="#009999">Alamat Email</font> &nbsp;&nbsp;
          </div>													
          <div class="clear"></div>
				</fieldset>
				 
				<fieldset><legend>Keluarga</legend>
           <br>
          <table id="mydata1" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
            <tbody>
              <tr>
              	<th colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
              </tr>																		
              <tr>
                <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Hubungan</th>
								<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">Nama Lengkap</th>
  							<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Tgl Lahir</th>
                <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Jenis Klmin</th>
        				<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Gol. Darah</th>
        				<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">No. Ref BPJSTK</th>
                <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:150px;">Action</th>
              </tr>
              <tr>
              	<th colspan="7"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
              </tr>													
              <?		
                $sql = "select 
                            kode_tk, no_urut_keluarga, kode_hubungan,
                            (select nama_hubungan from sijstk.kn_kode_hubungan_tk where kode_hubungan = a.kode_hubungan) nama_hubungan, 
                            nama_lengkap, no_kartu_keluarga, tempat_lahir, to_char(tgl_lahir,'dd/mm/yyyy') tgl_lahir, 
														decode(a.jenis_kelamin,'L','LAKI-LAKI','P','PEREMPUAN',a.jenis_kelamin) jenis_kelamin, golongan_darah, 
                            alamat, kode_kabupaten, (select nama_kabupaten from sijstk.ms_kabupaten where kode_kabupaten = a.kode_kabupaten) nama_kabupaten,
                            kode_pos, telepon_area, telepon, telepon_ext, fax_area, fax, handphone, email, npwp, keterangan, kpj_tertanggung, 
                            nvl(a.aktif,'T') aktif
                        from sijstk.kn_tk_keluarga a
                        where kode_tk = '$ls_kode_tk'
                        order by no_urut_keluarga ";
                //echo $sql;
  							$DB->parse($sql);
                $DB->execute();				              					
                $i=0;		
                $ln_dtl = 0;														
                while ($row = $DB->nextrow())
                {
                  ?>
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_HUBUNGAN"];?></td>	
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NAMA_LENGKAP"];?></td>
										<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_LAHIR"];?></td>
										<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["JENIS_KELAMIN"];?></td>
										<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["GOLONGAN_DARAH"];?></td>
										<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["KPJ_TERTANGGUNG"];?></td>
										<td style="text-align:center;"></td>
                  </tr>
                  <?
                  //hitung total									    							
                  $i++;//iterasi i							
                }	//end while
                $ln_dtl=$i;				
              ?>									             																
            </tbody>
            <tr>
              <td colspan="7"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></td>	
            </tr>											
            <tr>
              <td style="text-align:center" colspan="6"><b><i>&nbsp;<i></b>
                <input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
                <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
                <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">					
              </td>
              <td style="text-align:center"></td>											
            </tr>					
          </table>				
				</fieldset>
								 
			</div> <!--end konten-->	     	 
  	</div> <!--end tab1-->
			
		<div style="display: none;" id="tab2" class="tab_konten">
	  	<div id="konten">	 
      		<fieldset>
            <legend>
        			<b>Berdasarkan &nbsp;&nbsp;
              <? 
							if ($ls_rg_jenisprg=="")
							{
							 	$ls_rg_jenisprg = "1"; 
							}
							
              switch($ls_rg_jenisprg)
              {
              case '1' : $sel1="checked"; break;
              case '2' : $sel2="checked"; break;
              }
              ?>
              <input type="radio" name="rg_jenisprg" value="1" onclick="this.form.submit();"  <?=$sel1;?>>&nbsp;<font  color="#009999"><b>Tanggal Bayar</b></font>&nbsp; | &nbsp;
              <input type="radio" name="rg_jenisprg" value="2" onclick="this.form.submit();"  <?=$sel2;?>>&nbsp;<font  color="#009999"><b>Tanggal Transaksi</b></font> &nbsp;&nbsp;
              </b>
            </legend>
						
						<table>
							<tr>			
        				<td>Tahun</td>
      					<td>
                  <input type="text" id="tahun_prg" name="tahun_prg" value="<?=$ls_tahun_prg;?>" tabindex="5" size="10" maxlength="4" style="background-color:#ffff99;text-align:center;">	
                </td>
      					<td>
                  <input type="submit" class="btn green" id="btntampilkan" name="btntampilkan" value="   TAMPILKAN   " >	
                </td>
        			</tr>				
						</table>
						
            <table id="mydata2" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
              <tbody>
                <tr>
                	<th colspan="10"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
                </tr>																		
                <tr>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:20px;">No</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Program</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Kode Iuran</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Bayar</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Transaksi</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Aktif</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Efektif</th>
      						<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Expired</th>
      						<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:50px;">Bln Prg</th>
      						<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;">Via</th>
                </tr>
                <tr>
                	<th colspan="10"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                </tr>		
                <?	
								if ($ls_rg_jenisprg=="2")
								{
								 	 $filter_jenisprg = "and to_char(b.blth,'yyyy') = '$ls_tahun_prg' ";
								}else
								{
								 	 $filter_jenisprg = "and to_char(b.tgl_rekon,'yyyy') = '$ls_tahun_prg' ";
								}
								
                $sql = "select kode_tagihan, kode_iuran, tgl_bayar, tgl_transaksi, tgl_aktif, tgl_efektif,tgl_expired,tgl_grace,jml_bln,channel_id,
										 	 				 case when flag_jht='Y' and flag_jkk='Y' and flag_jkm='Y' then 'JHT, JKK, JKM'
															 when flag_jht='Y' and flag_jkk='Y' and flag_jkm='T' then 'JHT, JKK'
															 when flag_jht='Y' and flag_jkk='T' and flag_jkm='Y' then 'JHT, JKM'
															 when flag_jht='Y' and flag_jkk='T' and flag_jkm='T' then 'JHT'
															 when flag_jht='T' and flag_jkk='Y' and flag_jkm='Y' then 'JKK, JKM'
															 when flag_jht='T' and flag_jkk='Y' and flag_jkm='T' then 'JKK'
															 when flag_jht='T' and flag_jkk='T' and flag_jkm='Y' then 'JKM'
															 end ket_program
										 	  from
											  (
  												select b.kode_tagihan, a.kode_iuran, to_char(b.blth,'dd/mm/yyyy') tgl_bayar, 
  										 	 			to_char(b.tgl_rekon,'dd/mm/yyyy') tgl_transaksi, 
  														to_char(a.blth, 'dd/mm/yyyy') tgl_aktif,
  														to_char(a.blth1,'dd/mm/yyyy') tgl_efektif, 
  														to_char(a.blth2,'dd/mm/yyyy') tgl_expired, 
  														to_char(a.blth3,'dd/mm/yyyy') tgl_grace, 
  														a.jml_bln, b.channel_id,
                              case when (select count(*) from sijstk.kn_iuran_tk_prg where kode_iuran = a.kode_iuran and kode_tk = a.kode_tk and kd_prg = 1) > 0 then 
                                  'Y'
                              else
                                  'T'
                              end flag_jht,
                              case when (select count(*) from sijstk.kn_iuran_tk_prg where kode_iuran = a.kode_iuran and kode_tk = a.kode_tk and kd_prg = 2) > 0 then 
                                  'Y'
                              else
                                  'T'
                              end flag_jkk,
                              case when (select count(*) from sijstk.kn_iuran_tk_prg where kode_iuran = a.kode_iuran and kode_tk = a.kode_tk and kd_prg = 3) > 0 then 
                                  'Y'
                              else
                                  'T'
                              end flag_jkm,
                              case when (select count(*) from sijstk.kn_iuran_tk_prg where kode_iuran = a.kode_iuran and kode_tk = a.kode_tk and kd_prg = 4) > 0 then 
                                  'Y'
                              else
                                  'T'
                              end flag_jpn           
                         from sijstk.kn_iuran_tk a, sijstk.kn_iuran_perusahaan b
                         where a.kode_iuran = b.kode_iuran
  											 $filter_jenisprg
                         and a.kode_tk = '$ls_kode_tk'
                         and a.kode_perusahaan = '$ls_kode_perusahaan'
                         and a.kode_segmen = '$ls_kode_segmen'
                         and a.kode_divisi = '$ls_kode_divisi' 
											 )";
      					//echo $sql;
      					$DB->parse($sql);
                $DB->execute();				              					
                $i=0;		
                $ln_dtl = 0;
      					$ln_no_urut  = 1;																	
                while ($row = $DB->nextrow())
                {
                  ?>
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$ln_no_urut;?></td>	
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["KET_PROGRAM"];?></td>
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["KODE_IURAN"];?></td>
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_BAYAR"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_TRANSAKSI"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_AKTIF"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_EFEKTIF"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_EXPIRED"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["JML_BLN"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["CHANNEL_ID"];?></td>
                  </tr>
                  <?
                  //hitung total									    							
                  $i++;//iterasi i
									$ln_no_urut++;										
                }	//end while
                $ln_dtl=$i;				
                ?>									             																
              </tbody>
              <tr>
              	<td colspan="10"><hr></hr>
              		<input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
                  <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
                  <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">					
                </td>									
              </tr>									
            </table>	
						</br>						
					</fieldset>		
			</div> <!--end konten-->	     	 
  	</div> <!--end tab2-->		

		<div style="display: none;" id="tab3" class="tab_konten">
	  	<div id="konten">	 
      		<fieldset><legend>&nbsp;</legend>
						<table>
							<tr>			
        				<td>Tahun</td>
      					<td>
                  <input type="text" id="tahun_saldo" name="tahun_saldo" value="<?=$ls_tahun_saldo;?>" tabindex="5" size="10" maxlength="4" style="background-color:#ffff99;text-align:center;">	
                </td>
      					<td>
                  <input type="submit" class="btn green" id="btntampilkan" name="btntampilkan" value="   TAMPILKAN   " >	
									<input type="submit" class="btn green" id="btncetaksaldo" name="btncetaksaldo" value="      CETAK      " >	
                </td>
        			</tr>				
						</table>
						
            <table id="mydata2" width="90%" cellspacing="0" border="0" align="center" bordercolor="#C0C0C0" background-color= "#ffffff">
              <tbody>
                <tr>
                	<th colspan="8"><hr style="border:0; height:1px; background-image: linear-gradient( to right, rgba(0,0,0,0), rgba(0,0,0,0.75), rgba(0,0,0,0));"/></th>	
                </tr>																		
                <tr>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:20px;">No</th>
									<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:20px;">Ktr</th>
									<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:20px;">Progam</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:100px;">Kode Transaksi</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Transaksi</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Tgl Bayar</th>
                  <th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Jenis Transaksi</th>
									<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Bulan</th>
									<th style="text-align:center;font: 12px Verdana, Arial, Helvetica, sans-serif;width:80px;">Nilai Transaksi</th>
								</tr>
                <tr>
                	<th colspan="8"><hr style="border:0; border-top:3px double #8c8c8c;"/></th>	
                </tr>		
                <?	
                $sql = "select kode_tk||no_transaksi kode_transaksi, kode_tk, no_transaksi, 
										 	 			kd_prg, (select nm_prg from sijstk.ms_prg where kd_prg = a.kd_prg) nm_prg,
														to_char(tgl_transaksi,'dd/mm/yyyy') tgl_transaksi, to_char(tgl_pointer_asal,'dd/mm/yyyy') tgl_bayar,
                            jenis_transaksi, tahun, bulan, nilai_transaksi
                        from sijstk.kn_tk_saldo a
                        where kode_tk = '$ls_kode_tk'
                        and kode_perusahaan = '$ls_kode_perusahaan'
                        and kode_segmen = '$ls_kode_segmen'
                        and tahun = '$ls_tahun_saldo'
												order by no_transaksi";
      					//echo $sql;
      					$DB->parse($sql);
                $DB->execute();				              					
                $i=0;		
                $ln_dtl = 0;
      					$ln_no_urut  = 1;																	
                while ($row = $DB->nextrow())
                {
                  ?>
                  <?echo "<tr bgcolor=#".($i%2 ? "f3f3f3" : "ffffff").">";?>								
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$ln_no_urut;?></td>	
										<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["NM_PRG"];?></td>
										<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["KODE_TRANSAKSI"];?></td>
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_TRANSAKSI"];?></td>
                    <td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["TGL_BAYAR"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["JENIS_TRANSAKSI"];?></td>
      							<td style="text-align:center;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=$row["BULAN"];?></td>
      							<td style="text-align:right;font: 10px Verdana, Arial, Helvetica, sans-serif;"><?=number_format((float)$row['NILAI_TRANSAKSI'],2,".",",");?></td>
                  </tr>
                  <?
                  //hitung total									    							
                  $i++;//iterasi i
									$ln_no_urut++;										
                }	//end while
                $ln_dtl=$i;				
                ?>									             																
              </tbody>
              <tr>
              	<td colspan="8"><hr></hr>
              		<input type="hidden" id="kounter_dtl" name="kounter_dtl" value="<?=$ln_dtl;?>">
                  <input type="hidden" id="count_dtl" name="count_dtl" value="<?=$ln_countdtl;?>">
                  <input type="hidden" name="showmessage" style="border-width: 0;text-align:right" readonly size="5">					
                </td>									
              </tr>									
            </table>	
						</br>						
					</fieldset>
			</div> <!--end konten-->	     	 
  	</div> <!--end tab3-->	
			 			
	</div> <!--end formKiri -->
</div> <!--end formframe -->	

<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>

