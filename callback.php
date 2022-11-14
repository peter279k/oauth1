<?php

$keys = include_once __DIR__ . '/tumblr_keys.php';

$apiKey = $keys['consumer_key'];
$apiSecret = $keys['secret_key'];

$base = 'https://www.tumblr.com';
$accessUrl = $base . '/oauth/access_token';

try {
    $oauth = new OAuth($apiKey, $apiSecret, OAUTH_SIG_METHOD_HMACSHA1, OAUTH_AUTH_TYPE_URI);
    $oauthTokenSecret = file_get_contents('./request_token.txt');
    $oauth->setToken($_GET['oauth_token'], $oauthTokenSecret);
    $accessTokens = $oauth->getAccessToken($accessUrl, '', $_GET['oauth_verifier'], 'GET');
    var_dump($accessTokens);
    file_put_contents('./access_token.txt', $accessTokens['oauth_token'] . PHP_EOL . $accessTokens['oauth_token_secret']);
} catch (OAuthException $e) {
    print_r($e);
}
