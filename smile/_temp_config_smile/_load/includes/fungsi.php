<?php
/*-------------------------------------------------------------------------------------------------
File: sc_fungsi_dasar.php

Deskripsi:
----------------------------------------------------------------------------------------------------
File ini berisi fungsi-fungsi dasar yang dipergunakan modul security
sebagian merupakan open source

Author:
----------------------------------------------------------------------------------------------------
Kade Budiarta
---------------------------------------------------------------------------------------------------*/

function f_endec($as_Str_Message) {
//Function : encrypt/decrypt a string message v.1.0  without a known key
//Author   : Aitor Solozabal Merino (spain)
//Email    : aitor-3@euskalnet.net
//Date     : 01-04-2005
    $ln_Len_Str_Message=STRLEN($as_Str_Message);
    $ls_Str_Encrypted_Message="";
    FOR ($ln_Position = 0;$ln_Position<$ln_Len_Str_Message;$ln_Position++){
        // long code of the function to explain the algoritm
        //this function can be tailored by the programmer modifyng the formula
        //to calculate the key to use for every character in the string.
        $ln_Key_To_Use = (($ln_Len_Str_Message+$ln_Position)+1); // (+5 or *3 or ^2)
        //after that we need a module division because can�t be greater than 255
        $ln_Key_To_Use = (255+$ln_Key_To_Use) % 255;
        $ls_Byte_To_Be_Encrypted = SUBSTR($as_Str_Message, $ln_Position, 1);
        $ln_Ascii_Num_Byte_To_Encrypt = ORD($ls_Byte_To_Be_Encrypted);
        $ln_Xored_Byte = $ln_Ascii_Num_Byte_To_Encrypt ^ $ln_Key_To_Use;  //xor operation
        $ls_Encrypted_Byte = CHR($ln_Xored_Byte);
        $ls_Str_Encrypted_Message .= $ls_Encrypted_Byte;
       
        //short code of  the function once explained
        //$str_encrypted_message .= chr((ord(substr($str_message, $position, 1))) ^ ((255+(($len_str_message+$position)+1)) % 255));
    }
    RETURN $ls_Str_Encrypted_Message;
} //end function

// fungsi check IP lokal
function f_ipCheck() {
	/*
	This function checks if user is coming behind proxy server. Why is this important?
	If you have high traffic web site, it might happen that you receive lot of traffic
	from the same proxy server (like AOL). In that case, the script would count them all as 1 user.
	This function tryes to get real IP address.
	Note that getenv() function doesn't work when PHP is running as ISAPI module
	*/
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

function get_server() {
	$protocol = 'http';
	if (isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443') {
		$protocol = 'https';
	}
	$host = $_SERVER['HTTP_HOST'];
	$baseUrl = $protocol . '://' . $host;
	if (substr($baseUrl, -1)=='/') {
		$baseUrl = substr($baseUrl, 0, strlen($baseUrl)-1);
	}
	return $baseUrl;
}
	
//oracle paging
function f_draw_pager($url, $total_pages, $current_page = 1,$othervar) {
    
    if ( $current_page <= 0 || $current_page > $total_pages ) {
        $current_page = 1;
    }
    
    if ( $current_page > 1 ) {
		$prev=$current_page-1;
        echo "<a href='$url?$othervar&page=1'>[Start]</a> \n" ;
        echo "<a href='$url?$othervar&page=$prev'>[Prev]</a> \n" ;
    }
    
    for( $i = ($current_page-5); $i <= $current_page+5; $i++ ) {
        
        if ($i < 1) continue;
        if ( $i > $total_pages ) break;
        
        if ( $i != $current_page ) {
           // printf( "<a href='$url?$othervar&page=%1\$d'>%1\$d</a> \n" , $i);
						 echo "<a href='$url?$othervar&page=$i'>$i</a> \n" ;
        } else {
           // printf("<a href='$url?$othervar&page=%1\$d'><strong>%1\$d</strong></a> \n",$i);
						 echo"<a href='$url?$othervar&page=$i'><strong>$i</strong></a> \n";
        }
        
    }
    
    if ( $current_page < $total_pages ) {
		$next=$current_page+1;
        echo "<a href=$url?$othervar&page=$next>[Next]</a> \n" ;
        echo "<a href='$url?$othervar&page=$total_pages'>[End]</a> \n";
    }
    
}

function f_total_pages($total_rows, $rows_per_page) {
    if ( $total_rows < 1 ) $total_rows = 1;
    return ceil($total_rows/$rows_per_page);
}

function f_page_to_row($current_page, $rows_per_page) {
    $start_row = ($current_page-1) * $rows_per_page + 1;
    return $start_row;
}

function f_count_rows($DB, $select) {
    $sql = "SELECT COUNT(*) AS num FROM($select)";	
		$DB->parse($sql);
		$DB->execute();
  	$row = $DB->nextrow();
    $num_rows = $row["NUM"];
    return $num_rows;
}

function f_query_perpage($select, $start_row, $rows_per_page) {
		$end_row = $start_row + $rows_per_page - 1;
		$sql = "SELECT * FROM 
            (
                SELECT r.*, ROWNUM as row_number 
                FROM
                    ( $select ) r
                WHERE ROWNUM <= $end_row
            )
         		WHERE $start_row <= row_number";
    return $sql;
}

//fungsi untuk otorisasi user aproval parsing Web Services by zimmy & dewa @kc 1030 22sept2015
function f_cek_otorisasi($user,$pasword){
if(isset($user) && isset($pasword)){
//begin ws
//define("WSCOM", "http://172.28.101.49:2014/WSCom/services/Main?wsdl");
define("WSCOM", "http://wstest.bpjsketenagakerjaan.go.id:2014/WSCom/services/Main?wsdl");
// define("WSCOM", "http://wstest.bpjsketenagakerjaan.go.id:2014/WSCom/services/Main?wsdl");
//define("WSCOM", "http://172.28.101.52:2014/WSCom/services/Main?wsdl");
// Add HTTP HEADER to pass IP FWD to WS By GoEnZ 03-03/2014 10:36
$ipfwd = getenv('HTTP_X_FORWARDED_FOR');
if ($ipfwd=='') $ipfwd = getenv('REMOTE_ADDR');
$phpInternalEncoding = "UTF-8";
$wscom = new SoapClient(WSCOM, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
//end ws

	$con1 			= $wscom->getLoginUsrPass(array('chId' => 'SIJSTK3', 'username' => $user, 'password' => $pasword));
	$getData 		= get_object_vars($con1);
	
	if($getData["return"]->ret == 0){
		$con1 			=  $wscom->getUserLoginInfo(array('chId' => 'SIJSTK3', 'kodeUser' => $user));
		$getData 		= get_object_vars($con1);
		if($getData["return"]->ret == 0){
			$response['success'] 	= true;
			$NAMA 		= $getData["return"]->namaUser;
			$KDKANTOR	= $getData["return"]->kodeKantor;
			$KANTOR		= $getData["return"]->namaKantor;
			$NPK		= $getData["return"]->npk;
			$EMAIL		= $getData["return"]->email;
			$IP			= $_SERVER['REMOTE_ADDR'];
		} else {
			$response['success'] = false;
			$error = $getData["return"]->msg;
		}
	} else {
		$response['success'] = false;
		$error = $getData["return"]->msg;
	}
} else {
	$response['success'] = false;
	$error = 'User/Password harus diisi';
}
///$response['success'] = true;	
if ($response['success'] == false) {
	//echo $response['errors']['msg'] = $error;
	return '0';
} else {
	return '1';
}

}


function f_random_string($length = 10)
{
    $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

function myErrorHandler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
	$error['success'] = false;
    switch ($errno) {
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
$old_error_handler = set_error_handler("DefaultGlobalErrorHandler");

//ini_set('display_errors', $display_errors); 
ini_set('log_errors', $log_errors); 
//error_reporting(E_ERROR | E_WARNING | E_PARSE);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt'); 

function toNumber($anyvar){
	return is_double($anyvar)?doubleval($anyvar):intval($anyvar);
}

?>
