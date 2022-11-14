<?php

$keys = include_once __DIR__ . '/tumblr_keys.php';

$apiKey = $keys['consumer_key'];
$apiSecret = $keys['secret_key'];

$accessTokens = explode(PHP_EOL, file_get_contents('./access_token.txt'));

$accessToken = $accessTokens[0];
$accessSecret = $accessTokens[1];

try {
    $oauth = new OAuth($apiKey, $apiSecret, OAUTH_SIG_METHOD_HMACSHA1);
    $oauth->enableDebug(); // trun off in the production mode
    $oauth->setToken($accessToken, $accessSecret);

    $base = 'https://api.tumblr.com';
    $tweet = $oauth->fetch($base . '/v2/blog/peter279k/info?fields[blogs]=name,updated');

    $json = json_decode($oauth->getLastResponse(), true);
    var_dump($json);
} catch (OAuthException $e) {
    print_r($e);
}
