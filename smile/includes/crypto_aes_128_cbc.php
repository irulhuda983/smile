<?php
$crypto_aes_128_cbc_key = '1BUDfAI7f&x&HK(Q';

if (!function_exists('hex2bin')) {
    function hex2bin($data) {
        static $old;
        if ($old === null) {
            $old = version_compare(PHP_VERSION, '5.2', '<');
        }
        $isobj = false;
        if (is_scalar($data) || (($isobj = is_object($data)) && method_exists($data, '__toString'))) {
            if ($isobj && $old) {
                ob_start();
                $data = ob_get_clean();
            }
            else {
                $data = (string) $data;
            }
        }
        else {
            // trigger_error(__FUNCTION__.'() expects parameter 1 to be string, ' . gettype($data) . ' given', E_USER_WARNING);
            return;//null in this case
        }
        $len = strlen($data);
        if ($len % 2) {
            // trigger_error(__FUNCTION__.'(): Hexadecimal input string must have an even length', E_USER_WARNING);
            return false;
        }
        
        if (strspn($data, '0123456789abcdefABCDEF') != $len) {
            // trigger_error(__FUNCTION__.'(): Input string must be hexadecimal string', E_USER_WARNING);
            return false;
        }
        return pack('H*', $data);
    }
}

function encrypt($data){
    global $crypto_aes_128_cbc_key;
    $txtData =  json_encode($data);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-128-cbc'));
    return bin2hex($iv) . ":" . bin2hex(openssl_encrypt($txtData, 'aes-128-cbc', $crypto_aes_128_cbc_key, OPENSSL_RAW_DATA,$iv));

}
function decrypt($encryptedText){
    global $crypto_aes_128_cbc_key;
    list($iv, $txt) = explode(":",$encryptedText);
    $decryptedText = openssl_decrypt(hex2bin($txt), 'aes-128-cbc', $crypto_aes_128_cbc_key, OPENSSL_RAW_DATA,hex2bin($iv));
    return json_decode($decryptedText);
}


// examples:
// encrypt data:
/*
    $data = array(
       "fieldDatas" = > "Value"
    );
    $encryptedText = encrypt($data);
*/
// decrypt data:
/*
    $data = decrypt($encryptedText);
*/

// example format request ws invest
// - select with no paging and no order params:
//     {
//         filterFields:
//     }
/*
    $params = array(
        "filterField1" => "value1",
        "filterField2" => "value2"
    )
*/
// - select with no paging params:
//     {
//         filterFields:
//         orderBy:{
//             field: 0            // 0 ASC, 1 DESC
//         }
//     }
/*
    $params = array(
        "filterField1" => "value1",
        "filterField2" => "value2",
        "orderBy" => array(
            "orderField1" => 0,
            "orderField2" => 1
        )
    )
*/
// - select with paging params:
//     {
//         filterFields:
//         paging:{
//             page:               
//             rowPerPage:         // 0 for single page for all rows or no paging
//         },
//         orderBy:{
//             field: 0            // 0 ASC, 1 DESC
//         }
//     }
/*
    $params = array(
        "filterField1" => "value1",
        "filterField2" => "value2",
        "paging" => array(
            "page" => 1,
            "rowPerPage" => 10
        ),
        "orderBy" => array(
            "orderField1" => 0,
            "orderField2" => 1
        )
    )
*/
// - insert single:
//     {
//         fields:
//     }
/*
    $params = array(
        "feld1" => "value1",
        "field2" => "value2"
    )
*/
// - insert multiple:
//     [
//         {
//             fields:
//         }
//     ]
/*
    $params = [
        array(
            "feld1" => "value1",
            "field2" => "value2"
        ),
        array(
            "feld1" => "value1",
            "field2" => "value2"
        )
    ]
*/
// - update:
//     {
//         fields:
//         filters:{
//             filterFields:
//         }
//     }
/*
    $params = array(
        "feld1" => "value1",
        "field2" => "value2",
        "filters" => array(
            "filterField1"=>"value1",
            "filterField2"=>"value2"
        )
    )
*/
// - delete:
//     {
//         filterFields:
//     }
/*
    $params = array(
        "filterField1"=>"value1",
        "filterField2"=>"value2"
    )
*/

// - run procedure/ function:
//     {
//         fieldsIn:
//     }
/*
    $params = array(
        "fieldIn1"=>"value1",
        "fieldIn2"=>"value2"
    )
*/

?>