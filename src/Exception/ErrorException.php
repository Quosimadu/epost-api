<?php

/**
 * This file is part of metabytes-sro/epost-api.
 *
 * @package   metabytes-sro/epost-api
 * @author    Mantas Samaitis <mantas.samaitis@integrus.lt>
 */

namespace MetabytesSRO\EPost\Api\Exception;

use LogicException;
use MetabytesSRO\EPost\Api\Error;

/**
 * Class ErrorException
 * @package MetabytesSRO\EPost\Api\Exception
 */
class ErrorException extends LogicException
{
    protected $level;

    public function __construct(Error $error, Exception $previous = null)
    {
        $this->level = $error->getLevel();

        parent::__construct($error->getDescription(), 0, $previous);

        $this->code = $error->getCode();
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