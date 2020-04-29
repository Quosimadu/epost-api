<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api\Metadata;

use InvalidArgumentException;
use JsonSerializable;


/**
 * Class DeliveryOptions
 *
 * @package Quosimadu\EPost\Api\Metadata
 */
class DeliveryOptions implements JsonSerializable
{
    /**
     * Registered standard
     */
    const OPTION_REGISTERED_STANDARD = 'Einschreiben';

    /**
     * Registered submission only
     */
    const OPTION_REGISTERED_SUBMISSION_ONLY = 'Einwurf Einschreiben';

    /**
     * Registered addressee only
     */
    const OPTION_REGISTERED_ADDRESSEE_ONLY = 'Einschreiben eigenhändig';

    /**
     * Registered with return receipt
     */
    const OPTION_REGISTERED_WITH_RETURN_RECEIPT = 'Einschreiben Rückschein';

    /**
     * Registered addressee only with return receipt
     */
    const OPTION_REGISTERED_ADDRESSEE_ONLY_WITH_RETURN_RECEIPT = 'Einschreiben eigenhändig Rückschein';

    /**
     * Registered no
     */
    const OPTION_REGISTERED_NO = null;

    /**
     * The data used for json encoding
     *
     * @var array
     */
    protected $data = [];

    /**
     * The option specifies to carry out a black-and-white printing
     *
     * @return self
     */
    public function setColorGrayscale(): DeliveryOptions
    {
        return $this->setColor(false);
    }

    /**
     * The option specifies to carry out a color printing
     *
     * @return self
     */
    public function setColorColored(): DeliveryOptions
    {
        return $this->setColor(true);
    }

    /**
     * The option specifies whether a color or black-and-white printing is carried out
     *
     * @param bool $enabled
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function setColor($enabled): DeliveryOptions
    {
        $this->data['isColor'] = $enabled;

        return $this;
    }


    /**
     * Get color property
     *
     * @return string
     */
    public function getColor()
    {
        return $this->data['isColor'] ?? false;
    }

    /**
     * The first page of the submitted PDF will be used as the cover letter
     *
     * @return self
     */
    public function setCoverLetterIncluded(): DeliveryOptions
    {
        return $this->setCoverLetter(true);
    }

    /**
     * The cover letter is automatically generated
     *
     * @return self
     */
    public function setCoverLetterGenerate(): DeliveryOptions
    {
        return $this->setCoverLetter(false);
    }

    /**
     * The option specifies whether a cover letter is generated for delivery or if it is included in the PDF attachment
     *
     * @param bool $enabled
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function setCoverLetter($enabled): DeliveryOptions
    {
        $this->data['coverLetter'] = $enabled;

        return $this;
    }

    /**
     * Get coverLetter property
     *
     * @return string
     */
    public function getCoverLetter()
    {
        return $this->data['coverLetter'] ?? false;
    }

    /**
     * The option specifies whether a double-sided duplex printing is to be used. When duplex printing is used, all
     * attached documents, including the generated cover page, are printed on both sides of a sheet.
     *
     * @param bool $duplex
     *
     * @return $this
     */
    public function setDuplex($duplex): DeliveryOptions
    {
        $this->data['isDuplex'] = (bool)$duplex;

        return $this;
    }

    /**
     * Get duplex property
     *
     * @return bool
     */
    public function getDuplex()
    {
        return (bool)$this->data['isDuplex'];
    }

    /**
     * “Einschreiben ohne Optionen” (registered mail without options)
     * Not only the recipient personally, but also an authorized recipient, e.g. a spousemust, is allowed to
     * acknowledge receipt.
     *
     * @return self
     */
    public function setRegisteredStandard(): DeliveryOptions
    {
        return $this->setRegistered(self::OPTION_REGISTERED_STANDARD);
    }

    /**
     * “Einschreiben Einwurf” (registered mail delivered to mailbox)
     * The deliverer of the Deutsche Post AG drops the letter into a mailbox of the receiver and the deliverer confirms
     * this with his signature.
     *
     * @return self
     */
    public function setRegisteredSubmissionOnly(): DeliveryOptions
    {
        return $this->setRegistered(self::OPTION_REGISTERED_SUBMISSION_ONLY);
    }

    /**
     * “Einschreiben nur mit Option Eigenhändig” (personal registered mail)
     * Only the recipient is allowed to acknowledge receipt.
     *
     * @return self
     */
    public function setRegisteredAddresseeOnly(): DeliveryOptions
    {
        return $this->setRegistered(self::OPTION_REGISTERED_ADDRESSEE_ONLY);
    }

    /**
     * “Einschreiben nur mit Option Rückschein” (registered mail with return receipt)
     * The sender gets sent the handwritten conformation of an authorized recipient about the delivery as original.
     *
     * @return self
     */
    public function setRegisteredWithReturnReceipt(): DeliveryOptions
    {
        return $this->setRegistered(self::OPTION_REGISTERED_WITH_RETURN_RECEIPT);
    }

    /**
     * “Einschreiben mit Option Eigenhändig und Rückschein” (personal registered mail with return receipt)
     * The sender gets sent the handwritten conformation of the recipient personally about the delivery as original
     *
     * @return self
     */
    public function setRegisteredAddresseeOnlyWithReturnReceipt(): DeliveryOptions
    {
        return $this->setRegistered(self::OPTION_REGISTERED_ADDRESSEE_ONLY_WITH_RETURN_RECEIPT);
    }

    /**
     * “Standardbrief” (standard letter)
     *
     * @return self
     */
    public function setRegisteredNo(): DeliveryOptions
    {
        return $this->setRegistered(self::OPTION_REGISTERED_NO);
    }

    /**
     * The option specifies if the E‑POST letter is sent as a “Einschreiben” (registered letter), and, if so, which
     * registered letter type is to be selected
     *
     * @param string $registered
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function setRegistered($registered): DeliveryOptions
    {
        if (!in_array($registered, static::getOptionsForRegistered())) {
            throw new InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $registered, __FUNCTION__)
            );
        }

        $this->data['registeredLetter'] = $registered;

        return $this;
    }

    /**
     * Get registered property
     *
     * @return string
     */
    public function getRegistered()
    {
        return $this->data['registeredLetter'] ?? self::OPTION_REGISTERED_NO;
    }

    /**
     * Get all options that can be used for setRegistered()
     *
     * @return array
     */
    public static function getOptionsForRegistered()
    {
        return [
            self::OPTION_REGISTERED_STANDARD,
            self::OPTION_REGISTERED_SUBMISSION_ONLY,
            self::OPTION_REGISTERED_ADDRESSEE_ONLY,
            self::OPTION_REGISTERED_WITH_RETURN_RECEIPT,
            self::OPTION_REGISTERED_ADDRESSEE_ONLY_WITH_RETURN_RECEIPT,
            self::OPTION_REGISTERED_NO,
        ];
    }

    /**
     * Get the array containing all delivery options
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->getData();
    }
}
