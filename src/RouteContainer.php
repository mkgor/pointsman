<?php


namespace Pointsman;


use Pointsman\Entity\Route;
use Pointsman\Exception\RouteNotFoundException;

/**
 * Class RouteContainer
 *
 * @package Pointsman
 */
class RouteContainer
{
    /**
     * @var Route[]
     */
    private $container;

    /**
     * @param Route $route
     */
    public function push(Route $route)
    {
        $this->container[] = $route;
    }

    /**
     * @param string $routeName
     *
     * @return Route
     */
    public function find(string $routeName)
    {
        foreach($this->container as $route) {
            if($route->getName() === $routeName) {
                return $route;
            }
        }

        return null;
    }

    /**
     * @param string $routeName
     *
     * @return bool
     */
    public function delete(string $routeName)
    {
        foreach ($this->container as &$route)
        {
            if($route->getName() === $routeName) {
                unset($route);

                return true;
            }
        }

        return false;
    }

    /**
     * @return Route[]
     */
    public function getContainer()
    {
        return $this->container;
    }
}