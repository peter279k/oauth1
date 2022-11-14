<?php

require_once __DIR__ . '/vendor/autoload.php';

use League\OAuth1\Client\Server\Tumblr;
use League\OAuth1\Client\Signature\HmacSha1Signature;

session_start();

$keys = require_once __DIR__ . '/tumblr_keys.php';

$server = new Tumblr([
    'identifier' => $keys['consumer_key'],
    'secret' => $keys['secret_key'],
    'callback_uri' => 'http://localhost:5050/league_tumblr.php',
]);

$clientCreds = $server->getClientCredentials();
$accessTokens = unserialize($_SESSION['access_tokens']);

$signature = new HmacSha1Signature($clientCreds);
$signature->setCredentials($accessTokens);

$params = [
    'oauth_nonce' => hash('sha512', sodium_bin2hex(random_bytes(16))),
    'oauth_timestamp' => time(),
    'oauth_version' => '1.0',
    'oauth_consumer_key' => $clientCreds->getIdentifier(),
    'oauth_token' => $accessTokens->getIdentifier(),
];

$hmacsha1Signature = $signature->sign('http://localhost:5050/league_tumblr_callback.php', $params, 'POST');

var_dump($hmacsha1Signature);
