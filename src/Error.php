<?php


namespace Quosimadu\EPost\Api;


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
     * @return string
     */
    public function getLevel(): string
    {
        return $this->level;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
}