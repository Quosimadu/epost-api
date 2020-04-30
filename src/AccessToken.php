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
    protected string $vendorID;
    protected string $ekp;
    protected string $secret;
    protected string $password;

    /**
     * AccessToken constructor.
     *
     * @param $token
     */
    public function __construct($vendorID, $ekp, $secret, $password)
    {
        $this->vendorID = $vendorID;
        $this->ekp = $ekp;
        $this->secret = $secret;
        $this->password = $password;
    }
    /**
     * get an authentication token
     *
     * @return string
     */
    public function getToken(): string
    {
        static $token;
        if (empty($token)) {
            $token = (new Login())->login($this->vendorID, $this->ekp, $this->secret, $this->password)['token'];
        }
        return $token;
    }
}