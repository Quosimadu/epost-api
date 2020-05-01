<?php

/**
 * This file is part of metabytes-sro/epost-api.
 *
 * @package   metabytes-sro/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace MetabytesSRO\EPost\Api\Metadata;

use InvalidArgumentException;
use LogicException;
use JsonSerializable;
use MetabytesSRO\EPost\Api\Metadata\Envelope\AbstractRecipient;
use MetabytesSRO\EPost\Api\Metadata\Envelope\Recipient;


/**
 * Class Envelope
 *
 * @package MetabytesSRO\EPost\Api\Metadata
 */
class Envelope implements JsonSerializable
{

    /**
     * The data for used for json encoding
     *
     * @var Recipient
     */
    protected $data;

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
        return $this->data->getData();
    }

    /**
     * {@inheritdoc}
     */
    function jsonSerialize()
    {
        return $this->getData();
    }
}
