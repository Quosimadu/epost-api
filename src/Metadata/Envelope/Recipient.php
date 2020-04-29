<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api\Metadata\Envelope;

use InvalidArgumentException;
use JsonSerializable;
use Quosimadu\EPost\Api\Exception\InvalidRecipientDataException;
use Quosimadu\EPost\Api\Metadata\Envelope\AbstractRecipient;


/**
 * Class Recipient
 *
 * @package Quosimadu\EPost\Api\Metadata\Envelope
 */
class Recipient implements JsonSerializable
{
    /**
     * The data used for json
     *
     * @var array
     */
    protected $data = [];

    /**
     * Mapping allowed properties with maximum allowed length
     *
     * @var array
     */
    protected static $validationLengthMap = [
        'addressLine1'       => 80,
        'addressLine2'    => 80,
        'addressLine3'    => 80,
        'addressLine4'    => 80,
        'addressLine5'    => 80,
        'zipCode'       => 20, // postal code (ex. 53115)
        'city'          => 80,
    ];

    /**
     * @param string $company
     *
     * @return self
     */
    public function setAddressLine($line, $num): Recipient
    {
        if($num >= 5 || $num < 0) {
            throw new InvalidRecipientDataException('Address line number should be in range between 0 and 4.');
        }

        self::validateSetLength('addressLine' . ($num + 1), $line);
        $this->data['addressLine' . ($num + 1)] = $line;

        return $this;
    }

    /**
     * @return string
     */
    public function getAddressLine($num)
    {
        return $this->data['addressLine' . ($num + 1)] ?? null;
    }

    /**
     * @param string $zipCode
     *
     * @return self
     */
    public function setZipCode($zipCode): Recipient
    {
        self::validateSetLength('zipCode', $zipCode);
        $this->data['zipCode'] = $zipCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getZipCode()
    {
        return $this->data['zipCode'] ?? null;
    }

    /**
     * @param string $city
     *
     * @@return self
     */
    public function setCity($city): Recipient
    {
        self::validateSetLength('city', $city);
        $this->data['city'] = $city;

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->data['city'] ?? null;
    }


    /**
     * {@inheritdoc}
     *
     * @throws InvalidRecipientDataException
     */
    function jsonSerialize()
    {
        if (null === $this->getAddressLine(1) && null === $this->getCity() && null === $this->getZipCode()) {
            throw new InvalidRecipientDataException(
                'An address line 1, city and zip code must be set at least'
            );
        }

        return $this->getData();
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    private static function validateSetLength($key, $value)
    {
        if (strlen($value) > static::$validationLengthMap[$key]) {
            throw new InvalidArgumentException(
                sprintf('Value of property "%s" exceeds maximum length of %u', $key, static::$validationLengthMap[$key])
            );
        }
    }

    /**
     * Get raw data array
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }
}
