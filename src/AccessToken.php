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
 * Class AccessToken
 *
 * @package Quosimadu\EPost\Api
 */
class AccessToken
{
    protected string $token;

    /**
     * AccessToken constructor.
     *
     * @param $token
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * get an authentication token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }
}