<?PHP
function f_ipCheck() {
		if (getenv('HTTP_CLIENT_IP')) {
			$ls_ip = getenv('HTTP_CLIENT_IP');
		}
		elseif (getenv('HTTP_X_FORWARDED_FOR')) {
			$ls_ip = getenv('HTTP_X_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_X_FORWARDED')) {
			$ls_ip = getenv('HTTP_X_FORWARDED');
		}
		elseif (getenv('HTTP_FORWARDED_FOR')) {
			$ls_ip = getenv('HTTP_FORWARDED_FOR');
		}
		elseif (getenv('HTTP_FORWARDED')) {
			$ls_ip = getenv('HTTP_FORWARDED');
		}
		else {
			$ls_ip = $_SERVER['REMOTE_ADDR'];
		}
		return $ls_ip;
}

	//ini_set('display_errors', $display_errors); 
	ini_set('log_errors', $log_errors); 
	//error_reporting(E_ERROR | E_WARNING | E_PARSE);
	ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 
?>