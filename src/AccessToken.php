<?php


namespace Quosimadu\EPost\Api;


use GuzzleHttp\Client as HttpClient;

class AccessToken
{
    protected string $token;

    public function generateByCredentials($vendorID, $ekp, $secret, $password, $endpoint): AccessToken
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp', 'secret', 'password'])
            )
        ];

        $request =
            (new HttpClient(['base_uri' => $endpoint]))
                ->request('POST', '/api/Login', $options);

        $response = $request->getBody()->getContents();

        $this->token = json_decode($response)->token;

        return $this;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    public function getToken(): string
    {
        return $this->token;
    }
}