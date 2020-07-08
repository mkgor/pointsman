<?php


namespace Pointsman\Parser;

/**
 * Class PatternElement
 *
 * @package Pointsman\Parser
 */
class Element
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $regexp;

    /**
     * PatternElement constructor.
     * @param string $name
     * @param string $regexp
     */
    public function __construct($name, $regexp)
    {
        $this->name = $name;
        $this->regexp = $regexp;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getRegexp()
    {
        return $this->regexp;
    }

    /**
     * @param string $regexp
     */
    public function setRegexp($regexp): void
    {
        $this->regexp = $regexp;
    }
}