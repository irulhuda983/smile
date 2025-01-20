<?
header('Content-Type: application/json');
include('includes/connsql.php');

$url = 'http://172.26.0.65:2014/JSCore/ListMenu/';
//$url = 'http://wstest.bpjsketenagakerjaan.go.id:2014/JSCore/ListMenu/';


// set HTTP header
$headers = array(
	'Content-Type: application/json',
	'X-Forwarded-For: '.$ipfwd,
);
$fields = array(
	'chId' => $chId,
	'reqId' => $_SESSION["USER"],
	'kdFungsi' => $_REQUEST["role"]
);

// Open connection
$ch = curl_init();

// Set the url, number of POST vars, POST data
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

// Execute post
$result = curl_exec($ch);

// Close connection
curl_close($ch);

$result = json_decode($result);
print_r(json_encode($result->data));

?>