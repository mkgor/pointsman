<?php


namespace Pointsman\Entity;

/**
 * Class Route
 *
 * @package Pointsman\Entity
 */
class Route
{
    /**
     * @var string|null
     */
    private $name = null;

    /**
     * @var string
     */
    private $pattern;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array|\Closure
     */
    private $handler;

    /**
     * Route constructor.
     *
     * @param string|null     $name
     * @param string          $pattern
     * @param string          $method
     * @param \Closure|string $handler
     */
    public function __construct(string $name, string $pattern, string $method, $handler)
    {
        $this->name = $name;
        $this->pattern = $pattern;
        $this->method = $method;
        $this->handler = $handler;
    }

    /**
     * @return null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param null $name
     *
     * @return Route
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param string $pattern
     *
     * @return Route
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param mixed $parameters
     *
     * @return Route
     */
    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @param mixed $method
     *
     * @return Route
     */
    public function setMethod($method)
    {
        $this->method = $method;
        return $this;
    }

    /**
     * @return \Closure|array
     */
    public function getHandler()
    {
        if (is_string($this->handler)) {
            list($controller, $controllerMethod) = explode('::', $this->handler);

            return [
                'controller' => $controller,
                'method' => $controllerMethod
            ];
        }

        return $this->handler;
    }

    /**
     * @param \Closure|string $handler
     *
     * @return Route
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }
}