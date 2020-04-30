<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api;


use GuzzleHttp\Client as HttpClient;
use Illuminate\Http\Response;

/**
 * Class Login
 *
 * @package Quosimadu\EPost\Api
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

        $response = (new HttpClient(['base_uri' => Letter::API_ENDPOINT]))
                ->request('POST', '/api/Login', $options);

        $result = \GuzzleHttp\json_decode(
            $response->getBody()->getContents(),
            true
        );

        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new Exception\ErrorException(
                new Error($result)
            );
        }

        return $result;
    }

    public function smsRequest($vendorID, $ekp): string
    {
        $options = [
            'headers' => ['Content-Type' => 'application/json'],
            'body' => json_encode(
                compact(['vendorID', 'ekp']),
            )
        ];

        $response = (new HttpClient(['base_uri' => Letter::API_ENDPOINT]))
                ->request('POST', '/api/Login/smsRequest', $options);

        $result = \GuzzleHttp\json_decode(
            $response->getBody()->getContents(),
            true
        );

        if($response->getStatusCode() != Response::HTTP_ACCEPTED) {
            throw new Exception\ErrorException(
                new Error($result)
            );
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

        $response = (new HttpClient(['base_uri' => Letter::API_ENDPOINT]))
                ->request('POST', '/api/Login/setPassword', $options);

        $result = \GuzzleHttp\json_decode(
            $response->getBody()->getContents(),
            true
        );

        if($response->getStatusCode() != Response::HTTP_OK) {
            throw new Exception\ErrorException(
                new Error($result)
            );
        }

        return $response->getBody()->getContents();
    }
}