<?php

class OAuthHeader
{
    protected $callback = '';
    protected $nonce;
    protected $requestUrl;
    protected $httpVerb;
    protected $consumer;
    protected $access;

    public function __construct(OAuthConsumer $consumer, OAuthAccess $access)
    {
        $this->consumer = $consumer;
        $this->access = $access;
    }

    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }

    public function getNonce(): string
    {
        return $this->nonce;
    }

    public function setHttpVerb(string $httpVerb): void
    {
        $this->httpVerb = $httpVerb;
    }

    public function getHttpVerb(): string
    {
        return $this->httpVerb;
    }

    public function setRequestUrl(string $requestUrl): void
    {
        $this->requestUrl = $requestUrl;
    }

    public function getRequestUrl(): string
    {
        return $this->requestUrl;
    }

    public function setCallback(string $callback): void
    {
        $this->callback = $callback;
    }

    public function getCallback(): string
    {
        return $this->callback;
    }

    protected function setHeaderInfo(): ?array
    {
        $authorizeParams = [
            'oauth_nonce' => $this->getNonce(),
            'oauth_callback' => $this->getCallback(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_consumer_key' => $this->consumer->token,
            'oauth_token' => $this->access->token,
            'oauth_version' => '1.0',
        ];

        if ($this->callback === '') {
            unset($authorizeParams['oauth_callback']);
        }

        return $authorizeParams;
    }

    protected function buildBaseString(array $params): string
    {
        $tempArr = [];
        ksort($params);
        foreach ($params as $key => $value) {
            $tempArr[] = $key . '=' . $value;
        }

        return $this->httpVerb . '&' . rawurlencode($this->requestUrl) . '&' . rawurlencode(implode('&', $tempArr));
    }

    protected function buildCompositeKey(): string
    {
        return rawurlencode($this->consumer->secret) . '&' . rawurlencode($this->access->secret);
    }

    protected function buildAuthHeader(array $params): string
    {
        $headerPrefix = 'Authorization: OAuth ';
        $values = [];
        foreach ($params as $key => $value) {
            $values[] = $key . '=' . rawurlencode($value);
        }
        $headerContents = implode(',', $values);

        return $headerPrefix . $headerContents;
    }

    protected function buildSignature(string $baseString, string $compositeKey): string
    {
        return base64_encode(hash_hmac('sha1', $baseString, $compositeKey, true));
    }

    public function getAuthorizationHeader(): string
    {
        $params = $this->setHeaderInfo();
        $baseString = $this->buildBaseString($params);
        $compositeKey = $this->buildCompositeKey();
        $sig = $this->buildSignature($baseString, $compositeKey);
        $params['oauth_signature'] = $sig;

        return $this->buildAuthHeader($params);
    }
}
