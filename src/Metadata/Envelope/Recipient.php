<?php

/**
 * This file is part of metabytes-sro/epost-api.
 *
 * @package   metabytes-sro/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace MetabytesSRO\EPost\Api\Metadata\Envelope;

use InvalidArgumentException;
use JsonSerializable;
use MetabytesSRO\EPost\Api\Exception\InvalidRecipientDataException;


/**
 * Class Recipient
 *
 * @package MetabytesSRO\EPost\Api\Metadata\Envelope
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
        'country'       => 80
    ];

    /**
     * Set an address line by number
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
     * Set a zip code
     *
     * @return self
     */
    public function setZipCode(string $zipCode): Recipient
    {
        self::validateSetLength('zipCode', $zipCode);
        $this->data['zipCode'] = $zipCode;

        return $this;
    }

    /**
     * Get a zip code
     *
     * @return string
     */
    public function getZipCode()
    {
        return $this->data['zipCode'] ?? null;
    }

    /**
     * Set a city
     *
     * @@return self
     */
    public function setCity(string $city): Recipient
    {
        self::validateSetLength('city', $city);
        $this->data['city'] = $city;

        return $this;
    }

    /**
     * Get a city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->data['city'] ?? null;
    }

    /**
     * Set a country
     * Hier muss der Ländername nach ISO 3166-1 in GROßBUCHSTABEN und deutscher Sprache hinterlegt werden. (z.B. KROATIEN, ITALIEN, ÖSTERREICH ..). Inlandsendungen benötigen keine Länderangabe.
     *
     * @return self
     */
    public function setCountry(string $country): Recipient
    {
        self::validateSetLength('country', $country);
        $this->data['country'] = $country;

        return $this;
    }

    /**
     * Get a country
     *
     * @return string
     */
    public function getCountry()
    {
        return $this->data['country'] ?? null;
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
