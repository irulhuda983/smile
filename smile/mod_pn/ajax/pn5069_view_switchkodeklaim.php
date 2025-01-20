<?
session_start();
include "../../includes/conf_global.php";
?>
<script type="text/javascript" src="../../javascript/calendar.js"></script>
<script type="text/javascript" src="../../javascript/common.js"></script>
<link rel="stylesheet" type="text/css" media="all" href="../../style/calendar.css" title="green">
<script type="text/javascript"></script>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
<title>Switch Tenant</title>
<style type="text/css">
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
</style>
</head>
<body>
<? 
if($submit)
{	
	if($_POST['kode_klaim']!="")
	{
		$ls_sender 			= $_POST["sender"];	
		$ls_kode_klaim 	= $_POST["kode_klaim"];
		$task						= $_POST["task"];		
		?>
		<script language="JavaScript"> 
		function refreshParent() 
		{
		 	var v_kode_klaim = '<?=$ls_kode_klaim;?>';			 
			window.parent.Ext.WindowManager.getActive().parent.fl_js_switch_kodeklaim(v_kode_klaim);           
			window.parent.Ext.WindowManager.getActive().close();
		}	 		 	 	 		 	 
		</script>
		<script language="JavaScript">
		refreshParent();
		</script>
		<?
	}
	else
	{
		?>
		<font color="#ec0000">Kode Klaim kosong...</font><br /><br />
		<a href="../ajax/pn5069_view_switchkodeklaim.php">Ulangi lagi</a>
		<?
	}
}
else
{
?>
	<form name="fpop" id="fpop" action="<?=$PHP_SELF;?>" method="post">
		<table class="captionentry">	
			<tr> 
				<td align="left"><b><font color="#009999"> Switch Kode Klaim </font></b> </td>				 						 
			</tr>		
		</table>
		<?					
			$ls_sender 			= $_GET["sender"];	
			$ls_kode_klaim 	= $_GET["kode_klaim"];
			$task						= $_GET["task"];
		?>
		<br></br>
		<table>		
		<tr>			
			<td>
				Kode Klaim : &nbsp;
			</td>
			<td>				
				<input type="text" id="kode_klaim" name="kode_klaim" autocomplete="off" value="<?=$ls_kode_klaim;?>" size="30" >
				<input type="hidden" id="task" name="task" value="<?=$task;?>">
				<input type="hidden" id="sender" name="sender" value="<?=$ls_sender;?>"> 					
			</td>			
		</tr>
		
		<tr></tr>
		<tr>
			<td colspan="2" align="center"><input type="submit" name="submit" value="      Switch      ">
			</td>
		</tr>
		</table>
	</form>
<? 
}
?>
</body>
</html>
