<?php


namespace MetabytesSRO\EPost\Api;


class Error
{

    protected string $level;
    protected string $code;
    protected string $description;

    public function __construct($level, $code, $description)
    {
        $this->level = $level;
        $this->code = $code;
        $this->description = $description;
    }

    /**
     * Returns the level of the message: [ Info, Warning, Error ]
     *
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * Return the message code
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Returns the message description
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}