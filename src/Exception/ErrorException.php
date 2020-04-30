<?php

/**
 * This file is part of quosimadu/epost-api.
 *
 * @package   quosimadu/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>
 */

namespace Quosimadu\EPost\Api\Exception;

use LogicException;
use Quosimadu\EPost\Api\Error;

/**
 * Class ErrorException
 * @package Quosimadu\EPost\Api\Exception
 */
class ErrorException extends LogicException
{
    protected $level;

    public function __construct(Error $error, Exception $previous = null)
    {
        $this->level = $error->getLevel();

        parent::__construct($error->getDescription(), $error->getCode(), $previous);
    }

    public function getLevel()
    {
        return $this->level;
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->level}] [{$this->code}]: {$this->message}\n";
    }
}