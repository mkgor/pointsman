<?php

namespace Pointsman\Parser;

/**
 * Class ParsedPattern
 *
 * @package Pointsman\Parser
 */
class ParsedPattern
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var array
     */
    private $parameters;

    /**
     * ParsedPattern constructor.
     *
     * @param string $pattern
     * @param array  $parameters
     */
    public function __construct($pattern, array $parameters)
    {
        $this->pattern = $pattern;
        $this->parameters = $parameters;
    }

    public function getPattern(): string
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     */
    public function setPattern($pattern): void
    {
        $this->pattern = $pattern;
    }

    /**
     * @return array
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     */
    public function setParameters($parameters): void
    {
        $this->parameters = $parameters;
    }
}