<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Submit Proses";
if ($username==""){$username = $_SESSION["USER"];}

$ls_kode_klaim							 	 		= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];	
$ln_no_konfirmasi_induk						= !isset($_GET['no_konfirmasi_induk']) ? $_POST['no_konfirmasi_induk'] : $_GET['no_konfirmasi_induk'];	
$ls_rg_kondisi										= !isset($_GET['rg_kondisi']) ? $_POST['rg_kondisi'] : $_GET['rg_kondisi'];
$ls_kode_kondisi_terakhir_induk		= !isset($_GET['kode_kondisi_terakhir_induk']) ? $_POST['kode_kondisi_terakhir_induk'] : $_GET['kode_kondisi_terakhir_induk'];
$ld_tgl_kondisi_terakhir_induk		= !isset($_GET['tgl_kondisi_terakhir_induk']) ? $_POST['tgl_kondisi_terakhir_induk'] : $_GET['tgl_kondisi_terakhir_induk'];
$ls_sender_mid							 	 		= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];	
$ls_rg_kategori							 	 		= !isset($_GET['rg_kategori']) ? $_POST['rg_kategori'] : $_GET['rg_kategori'];
$gs_kantor_aktif 									= $_SESSION['kdkantorrole'];	 
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
    if ($ls_kode_klaim!="" && $ln_no_konfirmasi_induk!="")
    {
      $ipDev  	= "";
      global $wsIp;
      $ipDev  	= $wsIp."/JSPN5050/GenKonfirmasiBerkala";
      $url    	= $ipDev;
      $chId   	= 'SMILE';
      $username = $_SESSION["USER"];
      
      // set HTTP header ------------------------------------------------
      $headers = array(
        'Content-Type'=> 'application/json',
        'X-Forwarded-For'=> $ipfwd,
      );
      
      // set POST params ------------------------------------------------
      $data = array(
        'chId'					 			=>$chId,
        'reqId'								=>$username,
        'KODE_KLAIM'					=>$ls_kode_klaim,
        'NO_KONFIRMASI_INDUK'	=>(int)$ln_no_konfirmasi_induk,
        'RG_KONDISI'					=>$ls_rg_kondisi,
        'KODE_KONDISITERAKHIR_INDUK' =>$ls_kode_kondisi_terakhir_induk,
        'TGL_KONDISITERAKHIR_INDUK'	 =>$ld_tgl_kondisi_terakhir_induk,
        'KANTOR_KONFIRMASI'   =>$gs_kantor_aktif
      );

      // Open connection ------------------------------------------------
      $ch = curl_init();
      
      // Set the url, number of POST vars, POST data --------------------
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
      
      // Execute post ----------------------------------------------------
      $result = curl_exec($ch);
      $resultArray_GenKonf = json_decode(utf8_encode($result));
			
    	if ($resultArray_GenKonf->ret==0)
    	{
        $ln_no_konfirmasi_baru = $resultArray_GenKonf->P_NO_KONFIRMASI_BARU;
				$ls_mess = "GENERATE KONFIRMASI BERKALA SUKSES, SESSION DILANJUTKAN..";
				
				echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      	echo "window.opener.fl_js_reload_post_submit('$ls_kode_klaim', '$ln_no_konfirmasi_baru');";
				echo "window.close();";
				echo "window.opener.alert('$ls_mess')";
  			echo "</script>";	 	  	 
    	}else
    	{
        if ($resultArray_GenKonf->P_SUKSES == "-1")
				{
				 	 $ls_mess = $resultArray_GenKonf->P_MESS;
				}else
				{
				 	$ls_mess = $resultArray_GenKonf->msg;	 
				}
				echo "<script language=\"JavaScript\" type=\"text/javascript\">";
				echo "window.opener.fl_js_reload_post_lovpenetapan();";
      	echo "window.close();";
  			echo "window.opener.alert('$ls_mess')";
  			echo "</script>";		
    	}			
    }else
		{
      $ls_mess = "GENERATE KONFIRMASI BERKALA GAGAL, KODE KLAIM DAN NO KONFIRMASI INDUK KOSONG, HARAP DICOBA KEMBALI..";
			echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.opener.fl_js_reload_post_lovpenetapan();";
			echo "window.close();";
			echo "window.opener.alert('$ls_mess')";
			echo "</script>";			
		}  	
  	$ls_status_submit = "Y";
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
	