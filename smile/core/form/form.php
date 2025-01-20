<?PHP
$formserver = "http://172.26.0.18:8888";
?>
<style>
body{
	margin:0;
}
</style>
<iframe width="100%" height="100%" src="<?=$formserver;?>/forms/frmservlet?form=<?=$_REQUEST["form"];?>&config=siptonline-fusion&p_user_id=<?=$_REQUEST["p_user_id"];?>&p_kode_kantor=<?=$_REQUEST["p_kode_kantor"];?>&p_sender=UI&background=FF0&logo=0">
</iframe>