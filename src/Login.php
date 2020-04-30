<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api;


use GuzzleHttp\Client as HttpClient;

/**
 * Class Login
 *
 * @package Quosimadu\EPost\Api
 */
class Login
{
    public function login($vendorID, $ekp, $secret, $password, $endpoint): array
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp', 'secret', 'password']),
            )
        ];

        $request =
            (new HttpClient(['base_uri' => $endpoint]))
                ->request('POST', '/api/Login', $options);

        $response = $request->getBody()->getContents();

        return json_decode($response, true);
    }

    public function smsRequest($vendorID, $ekp, $endpoint)
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp']),
            )
        ];

        $request =
            (new HttpClient(['base_uri' => $endpoint]))
                ->request('POST', '/api/Login/smsRequest', $options);

        $response = $request->getBody()->getContents();

        $json = json_decode($response, true);

        return (json_last_error() == JSON_ERROR_NONE) ? $response : $json;
    }

    public function setPassword($vendorID, $ekp, $newPassword, $smsCode, $endpoint)
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp', 'newPassword', 'smsCode']),
            )
        ];

        $request =
            (new HttpClient(['base_uri' => $endpoint]))
                ->request('POST', '/api/Login/setPassword', $options);

        $response = $request->getBody()->getContents();

        $json = json_decode($response, true);

        return (json_last_error() == JSON_ERROR_NONE) ? $response : $json;
    }
}