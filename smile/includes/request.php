<?php
include_once "../../includes/crypto_aes_128_cbc.php";

function http_request($url, $params = array(), $encrypt = false){
    // parser params
    $arr_query = array();
    $query = "";
$params["reqId"] = "dsh";
$params["chId"] = "dsh";
    if(!$encrypt){
        if(count($params)>0){
        foreach(array_keys($params) as $x){
            $arr_query[]= $x."=".rawurlencode($params[$x]);
        }
        }
        $query = count($arr_query)>0?"?". join($arr_query,"&"):"";
    } else {
        $query = "?msg=" . encrypt($params);
    }

    // persiapkan curl
    $ch = curl_init(); 
    
    // set url 
    // $headers = array(
    //     'Accept:application/json',
    //     'X-Forwarded-For:'. $ipfwd
    // );
    
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_URL, $url . $query);

    // return the transfer as a string 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      
    
    $rslt = json_decode($output);
    // mengembalikan hasil curl
    if($encrypt && isset($rslt ->msg)){
        return  decrypt($rslt->msg);
    }
    return $rslt?$rslt:$output;
}

function http_post($url, $params = array(), $encrypt = false){
$params["reqId"] = "dsh";
$params["chId"] = "dsh";
    // parser params
    $data = array();
    if(!$encrypt){
        $data = $params;
    } else {
        $data["msg"] = encrypt($params);
    }
	$data = json_encode($data);
    // set curl
    $headers = array(
          'Content-Type: application/json',
          'Accept:application/json',
          'X-Forwarded-For:'. $ipfwd,
          'Content-Length: ' . strlen($data)
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true); //POST
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // $result = curl_exec($ch);
    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);     
    $rslt = json_decode($output);
    // mengembalikan hasil curl
    if($encrypt && isset($rslt ->msg)){
        return  decrypt($rslt->msg);
    }
    return $rslt?$rslt:$output;
}

function http_put($url, $params = array(), $encrypt = false){
$params["reqId"] = "dsh";
$params["chId"] = "dsh";
    // parser params
    $data = array();
    if(!$encrypt){
        $data = $params;
    } else {
        $data["msg"] = encrypt($params);
    }
		$data = json_encode($data);
    // set curl
    $headers = array(
          'Content-Type: application/json',
          'Accept:application/json',
          'X-Forwarded-For:'. $ipfwd,
          //'Content-Length: ' . strlen($data_string)
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); //PUT
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // $result = curl_exec($ch);
    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      
    
    $rslt = json_decode($output);
    // mengembalikan hasil curl
    if($encrypt && isset($rslt ->msg)){
        return  decrypt($rslt->msg);
    }
    return $rslt?$rslt:$output;
}

function http_delete($url, $params = array(), $encrypt = false){
$params["reqId"] = "dsh";
$params["chId"] = "dsh";
    // parser params
    $data = array();
    if(!$encrypt){
        $data = $params;
    } else {
        $data["msg"] = encrypt($params);
    }
	$data = json_encode($data);
    // set curl
    $headers = array(
          'Content-Type: application/json',
          'Accept:application/json',
          'X-Forwarded-For:'. $ipfwd,
          //'Content-Length: ' . strlen($data_string)
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE'); //DELETE
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    // $result = curl_exec($ch);
    // $output contains the output string 
    $output = curl_exec($ch); 

    // tutup curl 
    curl_close($ch);      
    
    $rslt = json_decode($output);
    // mengembalikan hasil curl
    if($encrypt && isset($rslt ->msg)){
        return  decrypt($rslt->msg);
    }
    return $rslt?$rslt:$output;
}

// example
/*
untuk yang get
$result = http_request('http://localhost:3041/v1/jsiv8203/getDanaHasilRekap?p_kantor=ATP&p_stunrealized=0&p_tgl1=16/03/2020&p_tgl2=16/03/2020&p_user=gw')
*/
?>
