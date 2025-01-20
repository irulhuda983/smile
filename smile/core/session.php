<?PHP
    $curl = curl_init();
	curl_setopt($curl,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($curl, CURLOPT_URL,"http://sijstk-core.bpjsketenagakerjaan.go.id/session.bpjstk");
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, "username=RO158820&password=1qaz2wsx");
    curl_exec ($curl);
    curl_close ($curl);
?>