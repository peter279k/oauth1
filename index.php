<?php

$keys = include_once __DIR__ . '/tumblr_keys.php';

$apiKey = $keys['consumer_key'];
$apiSecret = $keys['secret_key'];

$base = 'https://www.tumblr.com';
$requestUrl = $base . '/oauth/request_token';
$authorizeUrl = $base . '/oauth/authorize';

try {
    $oauth = new OAuth($apiKey, $apiSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $oauth->enableDebug(); // turn off in the production mode

    $requestToken = $oauth->getRequestToken($requestUrl);
    file_put_contents('request_token.txt', $requestToken['oauth_token_secret']);

    header('Location: ' . $authorizeUrl . '?oauth_token=' . $requestToken['oauth_token'] . '&oauth_token_secret=' . $requestToken['oauth_token_secret']);
} catch (OAuthException $e) {
    print_r($e);
}
