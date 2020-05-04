<?php

/**
 * This file is part of metabytes-sro/epost-api.
 *
 * @package   metabytes-sro/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace MetabytesSRO\EPost\Api;


use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Response;

/**
 * Class Login
 *
 * @package MetabytesSRO\EPost\Api
 */
class Login
{
    public function login($vendorID, $ekp, $secret, $password): array
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp', 'secret', 'password']),
            )
        ];

        try {
            $response = (new HttpClient(['base_uri' => Letter::API_ENDPOINT]))
                    ->request('POST', '/api/Login', $options);
        } catch (ClientException $e) {
            $this->throwErrorException($e);
        }

        return \GuzzleHttp\json_decode(
            $response->getBody()->getContents(),
            true
        );
    }

    public function smsRequest($vendorID, $ekp): string
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp']),
            )
        ];

        try {
            $response = (new HttpClient(['base_uri' => Letter::API_ENDPOINT]))
                    ->request('POST', '/api/Login/smsRequest', $options);
        } catch (ClientException $e) {
            $this->throwErrorException($e);
        }

        return $response->getBody()->getContents();
    }

    public function setPassword($vendorID, $ekp, $newPassword, $smsCode)
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp', 'newPassword', 'smsCode']),
            )
        ];

        try {
            $response = (new HttpClient(['base_uri' => Letter::API_ENDPOINT]))
                ->request('POST', '/api/Login/setPassword', $options);
        } catch (ClientException $e) {
            $this->throwErrorException($e);
        }

        return $response->getBody()->getContents();
    }

    /**
     * Throws an exception
     *
     * @param $e
     */
    protected function throwErrorException($e): void
    {
        throw new Exception\ErrorException(
            new Error(
                \GuzzleHttp\json_decode(
                    $e->getResponse()->getBody()->getContents(),
                    true
                )
            )
        );
    }
}