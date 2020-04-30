<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>, Richard Henkenjohann <richardhenkenjohann@googlemail.com>
 */

namespace Quosimadu\EPost\Api;

/**
 * Class LetterStatus
 *
 * @package Quosimadu\EPost\Api
 */
class LetterStatus
{
    const ACCEPTANCE_OF_SHIPMENT_ID = 1;
    const PROCESSING_THE_SHIPMENT_ID = 2;
    const DELIVERY_TO_THE_PRINTING_CENTER_ID = 3;
    const PROCESSING_IN_PRINTING_CENTER_ID = 4;
    const PROCESSING_ERROR_ID = 99;

    protected array $data;

    /**
     * LetterStatus constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        $this->data = $data;
    }

    /**
     * get a letter id
     *
     * @return int
     */
    public function getLetterId(): int
    {
        return $this->data['letterID'];
    }

    /**
     * get status id
     *
     * @return int
     */
    public function getStatusId(): int
    {
        return $this->data['statusID'];
    }

    /**
     * get array of errors
     *
     * @return array
     */
    public function getErrors(): array
    {
        return $this->data['errorList'];
    }
}