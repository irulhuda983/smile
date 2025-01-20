<?php

header('Content-Type: application/json');
include('includes/connsql.php'); // testing backup core tgl 14.05/2024 15.17.

/*
  $wsaddfav = new SoapClient(WSADDFAV, array("exceptions" => 0, "trace" => 1, "encoding" => $phpInternalEncoding, 'stream_context' => stream_context_create(array("http" => array("header" => 'X-Forwarded-For: '.$ipfwd)))));
  $username 	= $_SESSION['USER'];
  //$role		= $_REQUEST["role"]; edit 19-10-15 robby
  $role = explode('|',$_REQUEST['role']);
  $regrole = $role[0];
  $kdkantorrole = $role[1];
  $menuid		= explode('?mid=',$_REQUEST["menuid"]);
  //$menuid			= str_replace('FM-','', $menuid);
  $mnid			= $menuid[1];
  $com		= $_REQUEST["com"];
  $con1 		=  $wsaddfav->process(array('RequestInfo'=>
  array('RequestID'=>$chId,
  'RequestSource'=>$chId,
  'RequestDate'=>date('Y-m-d'),
  'RequestUser'=>$_SESSION["USER"]),
  'Input' => array("AddMenuFavoriteList" => array("AddMenuFavorite" =>
  array('KdFungsi'=>$regrole, 'KdMenu'=>$mnid,'KdUser'=>$username,'Status'=>$com)))));
  $getData 	= get_object_vars($con1);

  //print_r($getData);
  if($getData['Status']->Status == 'SUCCESS'){
  echo '{"success": true, "pesan":"'.$regrole.'|'.$mnid.'|'.$username.'|'.$com.'"}';
  } else {
  echo '{"success": false, "errors": "Tidak ada role yang dipilih!" }';
  }
 */
// -- start

$username = $_SESSION['USER'];
$role = explode('|', $_REQUEST['role']);
$regrole = $role[0];
$kdkantorrole = $role[1];
$menuid = explode('?mid=', $_REQUEST["menuid"]);
$mnid = $menuid[1];
$com = $_REQUEST["com"];

$url = $wsIp . '/JSCoreSys/AddMenuFav/';

// set HTTP header
$headers = array(
    'Content-Type: application/json',
    'X-Forwarded-For: ' . $ipfwd,
);

/*
print_r ($headers);
echo "<br>";
echo "kodeFungsi: ". $regrole;
echo "kodeMenu: ". $mnid;
echo "kodeUser: ". $username;
echo "status: ". $com;
echo "chId: ". $chId;
*/
/*
$datas = array(
    array(
        'kodeFungsi' => $regrole,
        'kodeMenu' => $mnid,
        'kodeUser' => $username,
        'status' => $com
    )
);
 * 
 */
$datas = array(
    array(
        'kodeFungsi' => $regrole,
        'kodeMenu' => $mnid,
        'kodeUser' => $username,
        'status' => $com
    )
);
//print_r ($datas);
$fields = array(
    'chId' => $chId,
    'data' => $datas,
);
//print_r($fields);

// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
print_r(json_encode($fields));
// Execute post
$result = curl_exec($ch);

// Close connection
curl_close($ch);

//$result = json_decode($result);
//print_r($result);
print_r(json_encode($result->data));


//print_r($getData);
if ($result->ret == 0) {
    echo '{"success": true, "pesan":"' . $regrole . '|' . $mnid . '|' . $username . '|' . $com . '"}';
} else {
    echo '{"success": false, "errors": "Tidak ada role yang dipilih!" }';
}
?>