<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException;
use GuzzleHttp\Psr7\MultipartStream;
use InvalidArgumentException;
use League\OAuth2\Client\Token\AccessToken;
use LogicException;
use Quosimadu\EPost\Api\Exception\MissingAccessTokenException;
use Quosimadu\EPost\Api\Exception\MissingAttachmentException;
use Quosimadu\EPost\Api\Exception\MissingEnvelopeException;
use Quosimadu\EPost\Api\Exception\MissingPreconditionException;
use Quosimadu\EPost\Api\Exception\MissingRecipientException;
use Quosimadu\EPost\Api\Metadata\DeliveryOptions;
use Quosimadu\EPost\Api\Metadata\Envelope;


/**
 * Class Letter
 *
 * @package Richardhj\EPost\Api
 */
class Letter
{

    /**
     * EPost endpoint for production environment
     *
     * @var string
     */
    private static $endpointProduction = '';

    /**
     * EPost endpoint for test and integration environment
     *
     * @var string
     */
    private static $endpointTest = 'https://api.epost.docuguide.com';

    /**
     * A toggle to enable test and integration environment
     *
     * @var bool
     */
    private $testEnvironment;

    /**
     * The OAuth access token instance
     *
     * @var AccessToken
     */
    private $accessToken;

    /**
     * The envelope (metadata)
     *
     * @var Envelope
     */
    private $envelope;

    /**
     * The optional cover letter html formatted
     *
     * @var string
     */
    private $coverLetter;

    /**
     * The attachment paths
     *
     * @var string
     */
    private $attachment;

    /**
     * The delivery options
     *
     * @var DeliveryOptions
     */
    private $deliveryOptions;

    /**
     * The letter's id available after the draft was created
     *
     * @var string
     */
    private $letterId;

    /**
     * Get the endpoint for mailbox api
     *
     * @return string
     */
    public function getEndpoint()
    {
        return !$this->isTestEnvironment() ? static::$endpointProduction : static::$endpointTest;
    }

    /**
     * Set the access token
     *
     * @param AccessToken $accessToken
     *
     * @return self
     */
    public function setAccessToken(AccessToken $accessToken): Letter
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get the access token
     *
     * @return AccessToken
     * @throws MissingAccessTokenException If the AccessToken is missing
     */
    public function getAccessToken(): AccessToken
    {
        if (null === $this->accessToken) {
            throw new MissingAccessTokenException('An AccessToken instance must be passed');
        }

        return $this->accessToken;
    }

    /**
     * Set the envelope
     *
     * @param Envelope $envelope
     *
     * @return self
     */
    public function setEnvelope(Envelope $envelope): Letter
    {
        $this->envelope = $envelope;

        return $this;
    }

    /**
     * Get the envelope
     *
     * @return Envelope
     * @throws MissingEnvelopeException If the envelope is missing
     * @throws MissingRecipientException If there are no recipients
     */
    public function getEnvelope(): Envelope
    {
        if (null === $this->envelope) {
            throw new MissingEnvelopeException('No Envelope provided! Provide one beforehand');
        }

        // Check for recipients
        if (empty($this->envelope->getRecipients())) {
            throw new MissingRecipientException('No recipients provided! Add them beforehand');
        }

        return $this->envelope;
    }

    /**
     * Set the cover letter as html string
     *
     * @param string $coverLetter
     *
     * @return self
     */
    public function setCoverLetter($coverLetter): Letter
    {
        $this->coverLetter = $coverLetter;

        return $this;
    }

    /**
     * Get the html formatted cover letter
     *
     * @return string
     */
    public function getCoverLetter()
    {
        return $this->coverLetter;
    }

    /**
     * Set attachment
     *
     * @param string $attachment The attachment path
     *
     * @return self
     */
    public function setAttachment($attachment): Letter
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * Get the attachment
     *
     * @return string
     * @throws MissingAttachmentException If the attachment is missing
     */
    public function getAttachment()
    {
        if (empty($this->attachment)) {
            throw new MissingAttachmentException('No attachment provided! Please add an attachment.');
        }

        return $this->attachment;
    }

    /**
     * Set the delivery options
     *
     * @param DeliveryOptions $deliveryOptions
     *
     * @return self
     * @throws LogicException If the letter isn't a hybrid (printed) letter
     */
    public function setDeliveryOptions(DeliveryOptions $deliveryOptions): Letter
    {
        $this->deliveryOptions = $deliveryOptions;

        return $this;
    }

    /**
     * Get the delivery options
     *
     * @return DeliveryOptions
     */
    public function getDeliveryOptions()
    {
        return $this->deliveryOptions;
    }

    /**
     * Set the letter id
     *
     * @param string $letterId
     *
     * @return self
     */
    public function setLetterId($letterId): Letter
    {
        $this->letterId = $letterId;

        return $this;
    }

    /**
     * Get the letter id
     *
     * @return string
     * @throws MissingPreconditionException If the letter id is missing
     */
    public function getLetterId()
    {
        if (!$this->letterId) {
            throw new MissingPreconditionException('No letter id provided! Set letter id or create draft beforehand');
        }

        return $this->letterId;
    }

    /**
     * Enable/disable the test and integration environment
     *
     * @param boolean $testEnvironment
     *
     * @return self
     */
    public function setTestEnvironment($testEnvironment): Letter
    {
        $this->testEnvironment = $testEnvironment;

        return $this;
    }

    /**
     * Return true for enabled test and integration environment
     *
     * @return bool
     */
    public function isTestEnvironment()
    {
        return $this->testEnvironment;
    }

    /**
     * Send the given letter. Delivery options should be set optionally for physical letters
     *
     * @return self
     * @throws BadResponseException See API Send Reference
     */
    public function send(): Letter
    {
        $data = $this->getEnvelope();

        if ($this->getCoverLetter()) {
            $data['coverLetter'] = true;
            $data['coverData'] = $this->getCoverLetter();
        } else {
            $data['coverLetter'] = false;
        }

        $attachment = $this->getAttachment();
        $data['fileName'] = basename($attachment);
        $data['data'] = fopen($attachment, 'rb');

        if (null !== $this->getDeliveryOptions()) {
            $data = array_merge($data, $this->getDeliveryOptions());
        }

        if($this->isTestEnvironment()) {
            $data = array_merge($data, [
                'testFlag' => true,
//                'testEMail' => 'test@test.com'
            ]);
        }

        $options = [
            'body'    => $data,
        ];

        $response = $this->getHttpClient($this->getEndpoint())
            ->request('POST', '/deliveries', $options);

        $data     = \GuzzleHttp\json_decode($response->getBody()->getContents());

        $this->setLetterId($data->letterId);

        return $this;
    }

    /**
     * Get a http client by given base uri and set the access token header
     *
     * @param string $baseUri
     *
     * @return HttpClient
     */
    private function getHttpClient($baseUri): HttpClient
    {
        return new HttpClient(
            [
                'base_uri' => $baseUri,
                'headers'  => [
                    'Authorization' => 'Bearer #'. $this->getAccessToken()->getToken() .'#',
                ],
            ]
        );
    }

    /**
     * Get a file's mime type
     *
     * @param $path
     *
     * @return mixed
     */
    private static function getMimeTypeOfFile($path)
    {
        $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime     = finfo_file($fileInfo, $path);
        finfo_close($fileInfo);

        return $mime;
    }
}
