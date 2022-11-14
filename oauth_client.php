<?php

require_once __DIR__ . '/OAuthHeader.php';
require_once __DIR__ . '/OAuthAccess.php';
require_once __DIR__ . '/OAuthConsumer.php';

$consumer = new OAuthConsumer();
$consumer->token = 'THIS_IS_TOKEN';
$consumer->secret = 'THIS_IS_SECRET';

$access = new OAuthAccess();
$access->token = 'THIS_IS_ACCESS_TOKEN';
$access->secret = 'THIS_IS_ACCESS_TOKEN_SECRET';

$headers = new OAuthHeader($consumer, $access);

$headers->setNonce('THIS_IS_ONE_TIME_NONCE');
$headers->setHttpVerb('GET');
$headers->setRequestUrl('http://localhost:5050/oauth_server.php');
$header = $headers->getAuthorizationHeader();

var_dump($header);

$curl = curl_init($headers->getRequestUrl());
curl_setopt($curl, CURLOPT_HTTPHEADER, [
    'Authorization: ' . $header,
]);
$response = curl_exec($curl);

var_dump($response);
