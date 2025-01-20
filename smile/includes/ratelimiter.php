<?php
// ini_set("display_errors", "on");
// error_reporting(E_ALL);

include_once "connsql.php";
require_once "class_database.php";

require __DIR__ . "/../classes/ratelimiter/tokenbucket/tokenbucket.php";
require __DIR__ . "/../classes/ratelimiter/redisconn/redisconn.php";

use RateLimiter\TokenBucket\TokenBucket;
use RateLimiter\RedisConn\RedisConn;

class Ratelimiter {
    public function request($id, $info, $max_request, $expiry_time) {
        global $rl_pref_id;
        global $rl_redis_urls;
        global $rl_redis_password;
        $redis = new RedisConn($rl_redis_urls, $rl_redis_password);
        $ratelimiter = new TokenBucket($rl_pref_id, $id, $info, $max_request, $expiry_time, $redis);
        $bucketId = $ratelimiter->create();
        $pass = $ratelimiter->request(1);
        return $pass;
    }

    public function requestAndOverride($id, $info, $max_request, $expiry_time) {
        $pass = $this->request($id, $info, $max_request, $expiry_time);
        if (!$pass) {
            header("HTTP/1.1 429 Too Many Requests");
            header("Status: 429 Too Many Requests");
            echo "Too Many Requests. Please try again later.";
            exit;
        }
    }

    public function requestAndKillSession($id, $info, $max_request, $expiry_time) {
        $pass = $this->request($id, $info, $max_request, $expiry_time);
        if (!$pass) {
            $this->killSession();
            header("HTTP/1.1 429 Too Many Requests");
            header("Status: 429 Too Many Requests");
            echo "Too Many Requests. Please try again later.";
            exit;
        }
    }

    public function requestAndKillSessionJson($id, $info, $max_request, $expiry_time, $json) {
        $pass = $this->request($id, $info, $max_request, $expiry_time);
        if (!$pass) {
            $this->killSession();
            echo $json;
            exit;
        }
    }

    public function requestAndKillSessionJson429($id, $info, $max_request, $expiry_time, $json) {
        $pass = $this->request($id, $info, $max_request, $expiry_time);
        if (!$pass) {
            $this->killSession();
            header("HTTP/1.1 429 Too Many Requests");
            header("Status: 429 Too Many Requests");
            echo $json;
            exit;
        }
    }

    private function killSession() {
        global $chId;
        global $dbuser;
        global $dbpass;
        global $dbname;
        $DB = new Database($dbuser,$dbpass,$dbname);
        $wscom = new SoapClient(WSCOMUSER."?wsdl", array("location"=>WSCOMUSER,"exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));

        if(isset($_SESSION["USER"])){
            $sql = "insert into sijstk.sc_audit_akses_form ( ".
                    "	kode_user, tgl_akses, kode_form, ".
                    "	inisial_fungsi, kantor_fungsi, ip_server, ". 
                    "	nama_app_server, nama_host, ip_client, ".
                    "	tgl_logout, status_logout, logout_by, ".
                    "	tgl_rekam, petugas_rekam) ".
                    "values ( ".
                    "	'".$_SESSION["USER"]."', sysdate, 'LOGOUT', ".
                    "	'".$_SESSION['regrole']."', '".$_SESSION['KDKANTOR']."', '".$_SERVER["SERVER_ADDR"]."', ".
                    "	'".$_SERVER["HTTP_HOST"]."', '".gethostname()."', '".$_SESSION['IP']."', ".
                    "	sysdate, 'Y', '".$_SESSION["USER"]."', ".
                    "	sysdate, 'SMILE'".
                    ")";								 
            $con1 =  $wscom->usrPassLogout(array('chId' => $chId, 'jsonData' => '{"KODE_USER":"'.$_SESSION["USER"].'", "LOGOUT_BY":"U"}'));
            $getData = get_object_vars($con1);
            if($getData["return"]->ret == 0){
                $DB->parse($sql);
                $DB->execute();
                $DB->close();
            }
        }

        $_SESSION = array();
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        session_unset();
        session_destroy();
    }

    public static function getClientIp() {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP']; // Cloudflare
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP']; // Shared internet
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR']; // Proxies
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR']; // Default
        }
        return null;
    }
}
?>