<?php

require_once __DIR__ . '/vendor/autoload.php';

use League\OAuth1\Client\Server\Tumblr;

$keys = require_once __DIR__ . '/tumblr_keys.php';
$server = new Tumblr([
    'identifier' => $keys['consumer_key'],
    'secret' => $keys['secret_key'],
    'callback_uri' => 'http://localhost:5050/league_tumblr.php',
]);

session_start();

if (empty($_GET)) {
    $tempCreds = $server->getTemporaryCredentials();
    $_SESSION['temp_credentials'] = serialize($tempCreds);
    session_write_close();

    $server->authorize($tempCreds);
}

if (isset($_GET['oauth_token']) && isset($_GET['oauth_verifier'])) {
    $tmpCreds = unserialize($_SESSION['temp_credentials']);
    $accessTokens = $server->getTokenCredentials($tmpCreds, $_GET['oauth_token'], $_GET['oauth_verifier']);

    unset($_SESSION['temporary_credentials']);
    $_SESSION['access_tokens'] = serialize($accessTokens);
    echo "Access token is saved in the session!";
    session_write_close();
}

if (isset($_GET['user'])) {

    if (!isset($_SESSION['access_tokens'])) {
        echo 'No access tokens.';
        exit(1);
    }

    $tokenCredentials = unserialize($_SESSION['access_tokens']);

    $user = $server->getUserDetails($tokenCredentials);
    var_dump($user);
}
