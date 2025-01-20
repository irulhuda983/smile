<? 
 $dbh = mysql_connect($db_host,$db_user,$db_pass);
 mysql_select_db($db_name,$dbh);
 
 include "mysql.class.php";
 $db = New Database($db_host,$db_name,$db_user,$db_pass,0);
?>
