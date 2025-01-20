<?
session_start();
include_once "../../includes/conf_global.php";
include_once "../../includes/class_database.php";
require_once "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
$pagetitle = "SMILE";
$gs_pagetitle = "Submit Proses";
if ($username==""){$username = $_SESSION["USER"];}

$ls_kode_klaim		= !isset($_GET['kode_klaim']) ? $_POST['kode_klaim'] : $_GET['kode_klaim'];	
$ln_no_level			= !isset($_GET['no_level']) ? $_POST['no_level'] : $_GET['no_level'];	
$ln_kode_tk 			= !isset($_GET['kode_tk']) ? $_POST['kode_tk'] : $_GET['kode_tk'];	
$ln_no_identitas	= !isset($_GET['nomor_identitas']) ? $_POST['nomor_identitas'] : $_GET['nomor_identitas'];	
$ls_sender_mid		= !isset($_GET['sender_mid']) ? $_POST['sender_mid'] : $_GET['sender_mid'];	
$gs_kantor_aktif 	= $_SESSION['kdkantorrole'];	 
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
    if($username != ''){		
      if ($ls_kode_klaim!="" && $ln_no_level!="")
      { 
        $ipDev  	= "";
        global $wsIp;
        $ls_user = $_SESSION["USER"];
        $chId = "SMILE";
        
        $url = $wsIp.'/JSPN5042/SubmitApproval';
        
        // set HTTP header
        $headers = array(
          'Content-Type'=> 'application/json',
          'X-Forwarded-For'=> $ipfwd,
        );
        
        $data = array(
          'chId'				 			=> $chId,
          'reqId'				 			=> $ls_user,
          'KODE_KLAIM'				=> $ls_kode_klaim,
          'NO_LEVEL'					=> (int)$ln_no_level
        );
        
        // Open connection
        $ch = curl_init();
        
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        
        // Execute post ----------------------------------------------------
        $result = curl_exec($ch);
        $resultArray = json_decode(utf8_encode($result));
        
        if ($resultArray->ret==0)
        {

          // --------------------------------- Start Verifikasi Data Klaim 22/09/2023 ------------------
          //update ke PN_AGENDA_VERIFIKASI_JHT_TUKEP jika pernah agenda koreksi pada KODE_JENIS_AGENDA PP03

          $sql_koreksi = "BEGIN PN.P_PN_PN60010210.X_PEMBAYARAN_VERIFIKASI_JHT (
                  '$ln_no_identitas',
                  '$ln_kode_tk',
                  '$ls_kode_klaim',
                  '$gs_kantor_aktif',
                  '$ls_user',
                  :p_mess,
                  :p_sukses);
              END;";
              
          $proc = $DB->parse($sql_koreksi);
          OCIBindByName($proc, ":p_sukses", $p_sukses,32);
          OCIBindByName($proc, ":p_mess", $p_mess,1000);
          $DB->execute();
          // --------------------------------- End Verifikasi Data Klaim 26/01/2023 ------------------ 

          

          $ls_mess = "PROSES PERSETUJUAN PENETAPAN KLAIM BERHASIL, SESSION DILANJUTKAN..";
          
          echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          echo "window.opener.fl_js_reload_post_submit();";
          echo "window.close();";
          echo "window.opener.alert('$ls_mess')";
          echo "</script>";	 	  	 
        }else
        {
          if ($resultArray->P_SUKSES =="-1" || $resultArray->P_SUKSES =="-2")
          {
            $ls_mess = "PROSES PERSETUJUAN PENETAPAN KLAIM GAGAL, ".$resultArray->P_MESS;
          }else
          {
            $ls_mess = "PROSES PERSETUJUAN PENETAPAN KLAIM GAGAL, ".$resultArray->P_MESS." </br>".$resultArray->msg;	 
          }
          echo "<script language=\"JavaScript\" type=\"text/javascript\">";
          echo "window.opener.fl_js_reloadFormEntry();";
          echo "window.close();";
          echo "window.opener.alert('$ls_mess')";
          echo "</script>";		
        }			
      }else
      {
        $ls_mess = "PROSES PERSETUJUAN PENETAPAN KLAIM GAGAL, KODE KLAIM KOSONG, HARAP DICOBA KEMBALI..";
        echo "<script language=\"JavaScript\" type=\"text/javascript\">";
        echo "window.opener.fl_js_reloadFormEntry();";
        echo "window.close();";
        echo "window.opener.alert('$ls_mess')";
        echo "</script>";			
      }  	
      $ls_status_submit = "Y";
    } else  {
      $ls_mess = "SESSION HABIS, SILAHKAN LAKUKAN LOGIN KEMBALI..";
      echo "<script language=\"JavaScript\" type=\"text/javascript\">";
      echo "window.opener.fl_js_reloadFormEntry();";
      echo "window.close();";
      echo "window.opener.alert('$ls_mess')";
      echo "</script>";		
    }
	}
  ?>
	<img alt="warning" src="../../images/warning.gif" align="left" hspace="10" vspace="0"> <span style="color:#ff0000;">harap sabar menunggu sampe proses selesai..!!!</span>		
	
  <form name="adminForm" id="adminForm" method="post" action="<?=$PHP_SELF;?>">
    <div id="formframe">
      <input type="hidden" id="kode_klaim" name="kode_klaim" value="<?=$ls_kode_klaim;?>">
      <input type="hidden" id="no_level" name="no_level" value="<?=$ln_no_level;?>">	
      <input type="hidden" id="nomor_identitas" name="nomor_identitas" value="<?=$ln_no_identitas;?>">	
      <input type="hidden" id="kode_tk" name="kode_tk" value="<?=$ln_kode_tk;?>">	
      <input type="hidden" id="status_submit" name="status_submit" value="<?=$ls_status_submit;?>">					
      <input type="submit" id="btnsubmit" name="btnsubmit" value="" style="color:#fbf7c8;"/>
			<input type="hidden" id="sender_mid" name="sender_mid" value="<?=$ls_sender_mid;?>">	

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
	