<?php

$requestHeaders = getallheaders();

$authorization = [];
foreach ($requestHeaders as $headerName => $responseHeader) {
    if ($headerName === 'Authorization') {
        foreach (explode(',', $responseHeader) as $pair) {
            if (strstr($pair, 'Authorization: OAuth ') !== false) {
                $pair = str_replace('Authorization: OAuth ', '', $pair);
            }
            [$key, $value] = explode('=', $pair);
            $authorization[$key] = trim($value);
        }
    }
}

var_dump($authorization);
