<?php
/* Januari 2021
   - List Functions untuk keperluan Upgrading to PHP 8
   - Extract variables from $_GET, $_POST, $_SERVER, $_SESSION
*/

class ExtendedFunction {
   public static function session_start() {
      if (session_status() == PHP_SESSION_NONE) {
         session_start();
      }
   }

   public static function call_api_dev_only($url, $fields){
      $curl = curl_init();

      curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($fields),
        CURLOPT_HTTPHEADER => array(
          'Content-Type: application/json'
        ),
      ));
      
      $response = curl_exec($curl);
      
      curl_close($curl);
      return $response;
   }

   public static function count($value){
      if (is_countable($value)) {
         return count($value);
      }
      return "";
   }

   public static function ereg_replace($pattern, $replacement, $string){
      $pattern = '/' . $pattern . '/';
      return preg_replace($pattern, $replacement, $string);
   }

   public static function split($pattern, $string){
      return explode($pattern, $string);
   }

   public static function str_replace_json_nullable($from, $to, $src) {
      if ($from == '"},]}' && $to == '"}]}') {
         $tfrom = 'null},]}';
         $tto = '""}]}';
         $src = str_replace($tfrom, $tto, $src);
      }
      return str_replace($from, $to, $src);
   }

   public static function str_replace_nullable($from, $to, $src) {
      if ($from == ',' && $to == '') {
         $src = str_replace($from, $to, $src);
         if ($src == null) {
            return "";
         }
      }
      return str_replace($from, $to, $src);
   }

   public static function str_replace_number($from, $to, $src) {
      if ($from == "," && $to == "") {
         $val = str_replace($from, $to, $src);
         return floatval($val); // test cycle
      }
      return str_replace($from, $to, $src);
   }
   public static function number_format_null($numval, $behind, $decsep = NULL,$thousep = NULL) {
      if (is_null($decsep) || is_null($thousep)) {
         return number_format($numval, $behind);
      }
      if ($numval === null) {
         $numval = 0;
      } else if ($numval === "") {
         return "";
      }
      
      return number_format($numval, $behind, $decsep,$thousep);
   }
   
   public static function passer($param) {
      return $param;
   }
   
   public static function die_json_encode($value) {
      echo json_encode($value);
      die();
   }
}

// untuk kebutuhan mod_dpkp saja
function number_format_enhanced($stringNumber, $behind = 2, $decsep = ".", $thousep = ","){
   $returnValue = 0;
   if(is_string($stringNumber)){
      if($stringNumber == "") $stringNumber = 0;
      $returnValue = $stringNumber * 1;
   }else{
      $returnValue = $stringNumber;
   }

   return number_format($returnValue,2,".",",");
}

function DefaultGlobalErrorHandler($errno, $errstr, $errfile, $errline){
   if (!(error_reporting() & $errno)) {
      // This error code is not included in error_reporting
      return;
   }

   $error['success'] = false;
   switch ($errno) {
   case E_NOTICE:
      return;
   case E_USER_ERROR:
      $error['errors']['msg'] = "$errfile($errno) - $errstr";
      echo json_encode($error);
      exit(1);
      break;
   case E_USER_WARNING:
      $error['errors']['msg'] = "$errfile($errno) - $errstr";
      echo json_encode($error);
      break;
   case E_USER_NOTICE:
      $error['errors']['msg'] = "$errfile($errno) - $errstr";
      echo json_encode($error);
      break;
   default:
      $error['errors']['msg'] = "$errfile($errno) - $errstr";
      echo json_encode($error);
      break;
   }
   return true;
}

ExtendedFunction::session_start();
// Extract variables from $_GET, $_POST, $_SERVER, $_SESSION
extract($_SESSION, EXTR_PREFIX_SAME, "wddx");
extract($_SERVER, EXTR_PREFIX_SAME, "wddx");
extract($_GET, EXTR_PREFIX_SAME, "wddx");
extract($_POST, EXTR_PREFIX_SAME, "wddx");

// $file_tracer_nanoid = ExtendedFunction::call_api_dev_only('http://172.28.108.97:9997/get-nanoid', array());
//include "../config.php";
?>
