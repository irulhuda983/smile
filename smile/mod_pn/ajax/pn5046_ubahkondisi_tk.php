<?
$pagetype = "report";
$gs_pagetitle = "pn5046 - UBAH KONDISI TERAKHIR TK";
require_once "../../includes/header_app.php";
//include '../../includes/fungsi_rpt.php';
include "../../includes/fungsi_newrpt.php";
	
/*--------------------- Form History -----------------------------------------
File: pn5002_cetak.php

Deskripsi:
-----------
File ini dipergunakan untuk ubah kondisi terakhir tk

Author:
--------
Pitra

Histori Perubahan:
--------------------
25/09/2017 - TIM SIJSTK
Pembuatan Form

-------------------- End Form History --------------------------------------*/

$ls_kode_klaim			  	 			 = !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];
$ls_sender			  	 			 		 = !isset($_GET['sender']) ? $_POST['sender'] : $_GET['sender'];
$ls_mid			  	 			 				 = !isset($_GET['mid']) ? $_POST['mid'] : $_GET['mid'];
$ls_jkk2_kode_kondisi_terakhir = $_POST['jkk2_kode_kondisi_terakhir'];
$ld_jkk2_tgl_kondisi_terakhir  = $_POST['jkk2_tgl_kondisi_terakhir'];
														
if($_POST['tombol']=="simpan")
{
		if ($ls_jkk2_kode_kondisi_terakhir!="" && $ld_jkk2_tgl_kondisi_terakhir!="")
		{
  		$ls_kode_klaim	= $_POST['kode_klaim'];
  		//cek kelayakan ----------------------------------------------------------
      $qry = "BEGIN SIJSTK.P_PN_PN5040.X_TAPULANG_UPDATE_KONDISITK('$ls_kode_klaim', '$ls_jkk2_kode_kondisi_terakhir', to_date('$ld_jkk2_tgl_kondisi_terakhir','dd/mm/yyyy'), '$username',:p_sukses,:p_mess);END;";											 	
      $proc = $DB->parse($qry);				
      oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
      oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
      $DB->execute();				
      $ls_sukses = $p_sukses;
  		$ls_mess = $p_mess;	
    	
  		if ($ls_sukses=="1")
  		{
  		  $msg = "Ubah kondisi terakhir TK berhasil, session dilanjutkan..";	 
  		}else
  		{
   	   	$msg = $ls_mess;
  		}		
  
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.opener.location.replace('$ls_sender?&task=Edit&mid=$mid&kode_klaim=$ls_kode_klaim&dataid=$ls_kode_klaim&msg=$msg');";
  		echo "window.close();";
      echo "</script>";	
		}else
		{
		 	$msg = "Status Kondisi Akhir dan Tgl tidak boleh kosong, lengkapi data input...!!!";	
			$ls_error = "1";	 
		}
}		
//--------------------- end button action ------------------------------------
//--------------------- fungsi lokal javascript ------------------------------
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>

<script language="JavaScript">
	function fl_js_val_simpan()
	{
    var form = document.adminForm;
    if(form.jkk2_kode_kondisi_terakhir.value==""){
      alert('Kondisi Terakhir TK tidak boleh kosong...!!!');
      form.jkk2_kode_kondisi_terakhir.focus();
    }else if(form.jkk2_tgl_kondisi_terakhir.value==""){
      alert('Tgl Kondisi Terakhir TK tidak boleh kosong...!!!');
      form.jkk2_tgl_kondisi_terakhir.focus();			
    }else
		{
       form.tombol.value="simpan";
       form.submit(); 		 
		}								 
	}															 		 	 	 		 	 
</script>
			
<?	
//--------------------- end fungsi lokal javascript --------------------------	
?>

<div id="header-popup">	
<h3><?=$gs_pagetitle;?></h3>
</div>

<div id="container-popup">
<!--[if lte IE 6]>
<div id="clearie6"></div>
<![endif]-->	

<?	
// echo $ls_st_lap_penetapan.$ls_status_submit_penetapan.'1'.$ls_flag_lumpsum.$ls_flag_berkala;
// die;
//Nilai Default --------------------------------------------------------------	

/* ============================================================================
Ket : Form ini digunakan untuk tab Input Agenda Klaim JKK Tahap II
Hist: - 20/07/2017 : Pembuatan Form (Tim SIJSTK)								 						 
-----------------------------------------------------------------------------*/
$sql = "select 
          a.kode_klaim, a.kode_kondisi_terakhir, a.kode_tipe_klaim, nvl(a.status_submit_penetapan,'T') status_submit_penetapan,
          (select nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_kondisi_terakhir = a.kode_kondisi_terakhir) nama_kondisi_terakhir,
          to_char(a.tgl_kondisi_terakhir,'dd/mm/yyyy') tgl_kondisi_terakhir 																	
        from sijstk.pn_klaim a
        where kode_klaim = '$ls_kode_klaim' ";
//echo $sql;
$DB->parse($sql);
$DB->execute();
$row = $DB->nextrow();
$ls_kode_klaim 						 		= $row['KODE_KLAIM'];		
$ls_kode_kondisi_terakhir 		= $row['KODE_KONDISI_TERAKHIR'];
$ls_nama_kondisi_terakhir 		= $row['NAMA_KONDISI_TERAKHIR'];
$ld_tgl_kondisi_terakhir			= $row['TGL_KONDISI_TERAKHIR'];
$ls_kode_tipe_klaim						= $row['KODE_TIPE_KLAIM'];
$ls_status_submit_penetapan   = $row['STATUS_SUBMIT_PENETAPAN'];
			
$ls_jkk2_kode_kondisi_terakhir = $ls_kode_kondisi_terakhir;
$ld_jkk2_tgl_kondisi_terakhir  = $ld_tgl_kondisi_terakhir;

if ($ld_jkk2_tgl_kondisi_terakhir=="")
{
  $sql = "select to_char(sysdate,'dd/mm/yyyy') as tgl from dual ";
  $DB->parse($sql);
  $DB->execute();
  $row = $DB->nextrow();
  $ld_jkk2_tgl_kondisi_terakhir = $row["TGL"];	 	 
} 
											
//End Nilai Default ----------------------------------------------------------
?>				
<div id="formframe" style="width:700px;">
	<span id="dispError1" style="display:none;color:red;width:700px;"></span>
  <input type="hidden" id="st_errval1" name="st_errval1">
	<input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
	<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>">
	<input type="hidden" id="mid" name="mid" value="<?=$ls_mid;?>">
	<input type="hidden" id="tombol" name="tombol">

	<div id="formKiri" style="width:600px;">
		<fieldset style="width:600px;"><legend>Kondisi Terakhir TK</legend>	

      <div class="form-row_kiri">
      <label style = "text-align:right;">Kondisi Terakhir &nbsp;</label>		 	    				
        <select size="1" id="jkk2_kode_kondisi_terakhir" name="jkk2_kode_kondisi_terakhir" value="<?=$ls_jkk2_kode_kondisi_terakhir;?>" tabindex="31" class="select_format" <?=($ls_status_submit_penetapan =="Y")? " style=\"width:250px;background-color:#F5F5F5\"" : " style=\"width:250px;background-color:#ffff99\"";?>>
        <option value="">-- Pilih --</option>
        <? 
  				$sql = "select kode_kondisi_terakhir, nama_kondisi_terakhir from sijstk.pn_kode_kondisi_terakhir where kode_tipe_klaim = '$ls_kode_tipe_klaim' and nvl(status_nonaktif,'T')='T' order by no_urut";	 	        
          $DB->parse($sql);
          $DB->execute();
          while($row = $DB->nextrow())
          {
            echo "<option ";
            if ($row["KODE_KONDISI_TERAKHIR"]==$ls_jkk2_kode_kondisi_terakhir && strlen($ls_jkk2_kode_kondisi_terakhir)==strlen($row["KODE_KONDISI_TERAKHIR"])){ echo " selected"; }
            echo " value=\"".$row["KODE_KONDISI_TERAKHIR"]."\">".$row["NAMA_KONDISI_TERAKHIR"]."</option>";
          }
        ?>
        </select>	
      </div>																																									
    	<div class="clear"></div>
  																
      <div class="form-row_kiri">
      <label style = "text-align:right;">Tgl Kondisi TK &nbsp;</label>
        <input type="text" id="jkk2_tgl_kondisi_terakhir" name="jkk2_tgl_kondisi_terakhir" value="<?=$ld_jkk2_tgl_kondisi_terakhir;?>" tabindex="32"  maxlength="10" onblur="convert_date(jkk2_tgl_kondisi_terakhir);"  <?=($ls_status_submit_penetapan =="Y")? " style=\"width:225px;background-color:#F5F5F5\"" : " style=\"width:225px;background-color:#ffff99\"";?>>
    		<?
  				if ($ls_status_submit_penetapan !="Y")  	
    			{
  				 	?> 
       			<input id="btn_jkk2_tgl_kondisi_terakhir" type="image" align="top" onclick="return showCalendar('jkk2_tgl_kondisi_terakhir', 'dd-mm-y');" src="../../images/calendar.gif">									
  					<?  			
  				}
  			?>
  		</div>    		
  		<div class="clear"></div>
  
  		</br> 	
																																																																
		</fieldset>
		
		</br>
		
		<fieldset style="width:580px;text-align:center;"><legend></legend>
			<input type="button" class="btn green" id="btnsimpan" name="btnsimpan" value="       UBAH       " title="Klik Untuk Simpan Perubahan" onClick="if(confirm('Apakah anda yakin akan melakukan perubahan kondisi terakhir TK..?')) fl_js_val_simpan();">										
		</fieldset>

		<?
		if (isset($msg))		
		{
		?>
		<fieldset style="width:600px;">
		<?=$ls_error==1 ? "<legend><font color=#ff0000>Error</font></legend>" : "<legend><font color=#007bb7>Message</font></legend>";?>
		<?=$ls_error==1 ? "<font color=#ff0000>".$msg."</font>" : "<font color=#007bb7>".$msg."</font>";?>
		</fieldset>		
		<?
		}
		?>
								
	</div>
</div>			
<div id="clear-bottom"></div>
<?
include "../../includes/footer_app.php";
?>