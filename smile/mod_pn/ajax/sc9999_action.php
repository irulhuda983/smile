<?PHP
session_start();
require_once "../../includes/conf_global.php";
require_once "../../includes/class_database.php";
$DB = new Database($gs_DBUser,$gs_DBPass,$gs_DBName);

$ls_kode_user = $_POST['f_kode_user'];
$ls_password = $_POST['f_password'];
$ls_user = $_SESSION["USER"];
$ls_kode_kantor = $_SESSION["kdkantorrole"];
function handleError($errno, $errstr,$error_file,$error_line) {
	if($errno == 2){
		$errorMsg = $errstr;
		if (strpos($errstr, 'failed to open stream:') !== false) {
			$errorMsg = 'Terdapat masalah dengan koneksi WebService.';
		} elseif(strpos($errstr, 'oci_connect') !== false) {
			$errorMsg = 'Terdapat masalah dengan koneksi database.';
		} else {
			$errorMsg = $errstr;
		}
	  echo '{"ret":-1,"msg":'.json_encode("<b>Error:</b>".$errorMsg).'}';
	  die();
	}
}
set_error_handler("DefaultGlobalErrorHandler");

$sql="select APPS.p_ldap_jmsprod.f_cek_kantor_user('$ls_kode_user', '$ls_kode_kantor') from dual";
// print_r($sql);
$DB->parse($sql);
$DB->execute();
$resultCekKantor = $DB->get_data($sql);
if($resultCekKantor=="1"){ 
	$sql = "BEGIN APPS.p_ldap_jmsprod.x_reset_x('$ls_kode_user','$ls_password','$ls_user');END;";
	$proc = $DB->parse($sql);
	  // oci_bind_by_name($proc, ":p_mess", $p_mess,5000);

	// print_r($sql);
	if($DB->execute())
	{ 
		$ls_mess = $p_mess;
		$return = '{"ret":0,"msg":"Sukses"}';
		echo $return;
	} else {
			echo '{"ret":-1,"msg":"Proses gagal, tidak ada data yang ditampilkan!"}';
	}
} else {
	echo '{"ret":-1,"msg":"Kode Kantor tidak sesuai dengan user login"}';
}
	


?>

