<?
session_start();
include "../../includes/conf_global.php";
include "../../includes/class_database.php";
include "../../includes/fungsi.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>User Authentification</title>
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
</head>
<body>
<? 
if($submit)
{	
	//otorisasi by webservices --------------------------------------------------
	if (f_cek_otorisasi($_SESSION['username'],$_POST['passwd'])=='1')
	{
  ?>
  <script language="JavaScript">
    window.opener.document.adminForm.trigersubmit.value = 2;
    window.opener.document.adminForm.submit();
  	window.close();
  </script>
  <?
	}
	else
	{
	?>
	  <font color="#ec0000">Password salah...</font><br /><br />
		<a href="kn5034_popupbatal.php">Ulangi lagi</a>
	<?
	}
}
else
{
?>
<img src="../../images/warning.gif" align="left" hspace="10" vspace="0"> Anda diminta untuk memasukkan password user <b><?=$_SESSION['username'];?></b>
<form action="<?=$PHP_SELF;?>" method="post">
  <input type="password" id="passwd" name="passwd" size="20">
  <script type="text/javascript">document.getElementById('passwd').focus();</script>
  <input type="submit" name="submit" value="OK"> 
</form>
<br /><a href="javascript:window.close()">Cancel</a>

<? 
}
?>
</body>
</html>
