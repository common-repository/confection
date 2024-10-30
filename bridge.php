<?php
//Confection Cable Version 2.4.0

//Replace with your Write Key
$write_key = 0;

//Replace 0 with your confection account ID. For manual implementation.
$account_id = 0;

if ($_SERVER["HTTP_USER_AGENT"] == 'confection')
    $_SERVER["HTTP_USER_AGENT"] = 'fake-confection';

//Get account id passed as GET parameter.
if ($account_id == 0 && isset($_REQUEST['account_id']))
    $account_id = $_REQUEST['account_id'];

//Our static endpoint
$listener_url = 'https://substation.confection.io/';

//Retrieve IP from client
if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
    $ip = $_SERVER['HTTP_CLIENT_IP'];
} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}

//Check if identified multiple IPs. If so, send only the first one.
$ip = explode(',', $ip, 2);
$ip = $ip[0];

//Check if  event was sent as Parameter
if (isset($_REQUEST['event'])) {
    $url = $listener_url .'?account_id='. $account_id .'&key='. $write_key .'&uuid='. $_REQUEST['uuid'] . '&event='. urlencode($_REQUEST['event']) . '&value='. urlencode($_REQUEST['value']) . '&ip='. $ip . '&browser='. urlencode($_SERVER["HTTP_USER_AGENT"]) . '&domain='. urlencode($_REQUEST['domain']);
} else {
//Otherwise this is a form field request.
    $url = $listener_url .'?account_id='. $account_id .'&key='. $write_key .'&uuid='. $_REQUEST['uuid'] . '&name='. urlencode($_REQUEST['name']) . '&value='. urlencode($_REQUEST['value']) . '&ip='. $ip . '&browser='. urlencode($_SERVER["HTTP_USER_AGENT"]) . '&domain='. urlencode($_REQUEST['domain']);
}

//cURL have priority here, as curl is performatic in PHP
if (function_exists('curl_version')) {

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_NOSIGNAL, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT_MS, 2000); //Set timeout to avoid hanging customer server
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '/assets/data/cacert.pem');
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '/assets/data/cacert.pem');
    
    curl_exec($ch);

    curl_close($ch);

} elseif (ini_get('allow_url_fopen')) {

    file_get_contents($url, false, stream_context_create(["http"=>["timeout"=>1]])); //Set timeout to avoid hanging customer server
    
} else {
    header('Location: '. $url );
}

exit;