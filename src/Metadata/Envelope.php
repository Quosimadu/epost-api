<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api\Metadata;

use InvalidArgumentException;
use LogicException;
use JsonSerializable;
use Quosimadu\EPost\Api\Metadata\Envelope\AbstractRecipient;
use Quosimadu\EPost\Api\Metadata\Envelope\Recipient;


/**
 * Class Envelope
 *
 * @package Quosimadu\EPost\Api\Metadata
 */
class Envelope implements JsonSerializable
{

    /**
     * The data for used for json encoding
     *
     * @var array
     */
    protected $data = [];

    /**
     * Add a hybrid recipient for printed letters
     *
     * @param Recipient\Hybrid|AbstractRecipient $recipient
     *
     * @return self
     */
    public function setRecipient(Recipient $recipient): Envelope
    {
        $this->data = $recipient;

        return $this;
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
    function jsonSerialize()
    {
        return $this->getData();
    }
}
