<?php

/**
 * This file is part of richardhj/epost-api.
 *
 * Copyright (c) 2015-2017 Richard Henkenjohann
 *
 * @package   richardhj/epost-api
 * @author    Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 * @copyright 2015-2017 Richard Henkenjohann
 * @license   https://github.com/richardhj/epost-api/blob/master/LICENSE LGPL-3.0
 */

namespace Quosimadu\EPost\Api\Metadata;

use InvalidArgumentException;
use LogicException;
use Quosimadu\EPost\Api\Metadata\Envelope\AbstractRecipient;
use Quosimadu\EPost\Api\Metadata\Envelope\Recipient;


/**
 * Class Envelope
 *
 * @package Richardhj\EPost\Api\Metadata
 */
class Envelope implements MetadataInterface
{

    /**
     * The data for used for json encoding
     *
     * @var array
     */
    protected $data = [];

    /**
     * Envelope constructor.
     */
    public function __construct()
    {
        $this->data['recipients'] = [];
        $this->data['recipientsPrinted'] = [];
    }

    /**
     * Specify to send an electronic E‑POST letter
     *
     * @return self
     */
    public function setSystemMessageTypeNormal(): Envelope
    {
        return $this->setSystemMessageType(self::LETTER_TYPE_NORMAL);
    }

    /**
     * Specify to send a physical E‑POST letter
     *
     * @return self
     */
    public function setSystemMessageTypeHybrid(): Envelope
    {
        return $this->setSystemMessageType(self::LETTER_TYPE_HYBRID);
    }

    /**
     * Specify the type of E-POST letter
     *
     * @param string $messageType
     *
     * @return self
     * @throws InvalidArgumentException
     */
    public function setSystemMessageType($messageType): Envelope
    {
        if (!in_array($messageType, static::getLetterTypeOptions())) {
            throw new InvalidArgumentException(
                sprintf('Property %s is not supported for %s', $messageType, __FUNCTION__)
            );
        }

        $this->data['letterType']['systemMessageType'] = $messageType;

        return $this;
    }

    /**
     * Get the system message type
     *
     * @return string
     */
    public function getSystemMessageType()
    {
        return $this->data['letterType']['systemMessageType'] ?? self::LETTER_TYPE_NORMAL;
    }

    /**
     * Add a normal (electronic) recipient
     *
     * @param Recipient\Normal|AbstractRecipient $recipient
     *
     * @return self
     */
    public function addRecipientNormal(Recipient\Normal $recipient): Envelope
    {
        if ($this->isHybridLetter()) {
            throw new LogicException(
                sprintf('Can not set recipients if message type is "%s"', self::LETTER_TYPE_HYBRID)
            );
        }

        $this->data['recipients'][] = $recipient;

        return $this;
    }

    /**
     * Add a hybrid recipient for printed letters
     *
     * @param Recipient\Hybrid|AbstractRecipient $recipient
     *
     * @return self
     */
    public function addRecipientPrinted(Recipient\Hybrid $recipient): Envelope
    {
        if ($this->isNormalLetter()) {
            throw new LogicException(
                sprintf('Can not set recipientsPrinted if message type is "%s"', self::LETTER_TYPE_NORMAL)
            );
        }

        if (count($this->getRecipients())) {
            throw new LogicException('It must not be set more than one printed recipient');
        }

        $this->data['recipientsPrinted'][] = $recipient;

        return $this;
    }

    /**
     * Get the recipients added to the envelope
     *
     * @return AbstractRecipient[]
     */
    public function getRecipients()
    {
        switch ($this->getSystemMessageType()) {
            case self::LETTER_TYPE_NORMAL:
                return !empty($this->data['recipients']) ? $this->data['recipients'] : [];
                break;
            case self::LETTER_TYPE_HYBRID:
                return !empty($this->data['recipientsPrinted']) ? $this->data['recipientsPrinted'] : [];
                break;
        }
        return null;
    }

    /**
     * Set the subject of the E‑POST letter
     *
     * @param string $subject
     *
     * @return self
     */
    public function setSubject($subject): Envelope
    {
        $this->data['subject'] = $subject;

        return $this;
    }

    /**
     * Get the subject of the E‑POST letter
     *
     * @return string|null
     */
    public function getSubject()
    {
        return $this->data['subject'];
    }

    /**
     * Check whether the letter will be carried out electronic
     *
     * @return bool
     */
    public function isNormalLetter()
    {
        return (self::LETTER_TYPE_NORMAL === $this->getSystemMessageType());
    }

    /**
     * Check whether the letter will be carried out printed
     *
     * @return bool
     */
    public function isHybridLetter()
    {
        return (self::LETTER_TYPE_HYBRID === $this->getSystemMessageType());
    }

    /**
     * Get all options that can be used for setSystemMessageType() or similar
     *
     * @return array
     */
    public static function getLetterTypeOptions()
    {
        return [
            self::LETTER_TYPE_NORMAL,
            self::LETTER_TYPE_HYBRID,
        ];
    }

    /**
     * Get the array containing all envelope properties
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
    public static function getMimeType()
    {
        return 'application/vnd.epost-letter+json';
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return ['envelope' => $this->getData()];
    }
}
