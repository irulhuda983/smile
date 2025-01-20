<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Submit Persetujuan Klaim";
if ($username==""){$username = $_SESSION["USER"];}

$ls_kode_klaim							 	 		= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];	
$ln_no_konfirmasi_induk						= !isset($_GET['no_konfirmasi_induk']) ? $_POST['no_konfirmasi_induk'] : $_GET['no_konfirmasi_induk'];	
$ls_rg_kondisi										= !isset($_GET['rg_kondisi']) ? $_POST['rg_kondisi'] : $_GET['rg_kondisi'];
$ls_kode_kondisi_terakhir_induk		= !isset($_GET['kode_kondisi_terakhir_induk']) ? $_POST['kode_kondisi_terakhir_induk'] : $_GET['kode_kondisi_terakhir_induk'];
$ld_tgl_kondisi_terakhir_induk		= !isset($_GET['tgl_kondisi_terakhir_induk']) ? $_POST['tgl_kondisi_terakhir_induk'] : $_GET['tgl_kondisi_terakhir_induk'];
$ls_sender_mid							 	 		= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];	
$ls_rg_kategori							 	 		= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];	
							 
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

  <script type="text/javascript" src="../../javascript/calendar.js"></script>
  <script type="text/javascript" src="../../javascript/common.js"></script>
  <script type="text/javascript" src="../../javascript/treemenu3.js"></script>
  <link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	
	<!-- tambahan baru -->
	<script type="text/javascript" src="../../javascript/numeral.min.js"></script>
	<script type="text/javascript" src="../../javascript/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
	<link rel="stylesheet" type="text/css" href="../../style/jquery.dataTables.min.css">
		
</head>

<style type="text/css">
<!-- 
body{
  font-family: tahoma, arial, verdana, sans-serif; 
  font-size:11px;
	background : #fbf7c8;
} 
a {
  text-decoration:none;
	color:#008040;
  }

a:hover {
	color:#68910b; 
  text-decoration:none;
  }
-->
</style>

<body>
  <?
  if(isset($_POST["btnsubmit"]))
  {			
  	//submit data persetujuan klaim  --------------------------------------------
    $qry = "BEGIN SIJSTK.P_PN_PN5040.X_POST_KONFIRMASI_BERKALA( ".
  			 	 "			'$ls_kode_klaim', ".
  				 "			'$ln_no_konfirmasi_induk', ".
  				 "			'$ls_rg_kondisi', ".
  				 "			'$ls_kode_kondisi_terakhir_induk', ".
  				 "			to_date('$ld_tgl_kondisi_terakhir_induk','dd/mm/yyyy'), ".
  				 "			'$gs_kantor_aktif', ".
  				 "			'$username', ".
  				 "			:p_no_konfirmasi_baru, ".
  				 "			:p_sukses, ".
  				 "			:p_mess ".
  				 ");END;";											 	
    $proc = $DB->parse($qry);				
    oci_bind_by_name($proc, ":p_no_konfirmasi_baru", $p_no_konfirmasi_baru,32);
  	oci_bind_by_name($proc, ":p_sukses", $p_sukses,32);
    oci_bind_by_name($proc, ":p_mess", $p_mess,1000);
    $DB->execute();	
  	$ln_no_konfirmasi_baru = $p_no_konfirmasi_baru;			
    $ls_sukses = $p_sukses;
    $ls_mess = $p_mess;
  	$ls_status_submit = "Y";
  	
  	if ($ls_sukses=="1")
  	{
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    	echo "window.opener.location.replace('../form/pn5045.php?task=Edit&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&no_konfirmasi=$ln_no_konfirmasi_baru&mid=$ls_sender_mid&rg_kategori=$ls_rg_kategori');";
      echo "</script>";	 	  	 
  	}else
  	{
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
    	echo "window.opener.location.replace('../form/pn5045.php?task=New&dataid=$ls_kode_klaim&kode_klaim=$ls_kode_klaim&no_konfirmasi_induk=$ln_no_konfirmasi_induk&mid=$ls_sender_mid&rg_kategori=$ls_rg_kategori&ls_error=1&msg=$ls_mess');";
      echo "</script>";		
  	}
  	echo "<script language=\"JavaScript\" type=\"text/javascript\">";
  	echo "window.close();";
  	echo "</script>";
  }
  ?>

	<img src="../../images/warning.gif" align="left" hspace="10" vspace="0"> <font color="#ff0000">harap sabar menunggu sampe proses selesai..!!!</font>		
	
  <form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
    <div id="formframe">
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
      <input type="hidden" id="no_konfirmasi_induk" name="no_konfirmasi_induk" value="<?=$ln_no_konfirmasi_induk;?>">	
			<input type="hidden" id="rg_kondisi" name="rg_kondisi" value="<?=$ls_rg_kondisi;?>">	
			<input type="hidden" id="kode_kondisi_terakhir_induk" name="kode_kondisi_terakhir_induk" value="<?=$ls_kode_kondisi_terakhir_induk;?>">	
			<input type="hidden" id="tgl_kondisi_terakhir_induk" name="tgl_kondisi_terakhir_induk" value="<?=$ld_tgl_kondisi_terakhir_induk;?>">	
			<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	
			<input type="hidden" id="rg_kategori" name="rg_kategori" value="<?=$ls_rg_kategori;?>">	
			<input type="hidden" id="status_submit" name="status_submit" value="<?=$ls_status_submit;?>">
			
			<input type="submit" id="btnsubmit" name="btnsubmit" value="" style="color:#fbf7c8;"/>
    </div>													 										
  </form>	

  <script language="javascript">
			if ($('#status_submit').val()=="")
			{		
  			document.getElementById("btnsubmit").click();
			}
  </script>
	
</body>
</html>						
	