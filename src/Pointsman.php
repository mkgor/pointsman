<?php


namespace Pointsman;

use Closure;
use Pointsman\Entity\Route;
use Pointsman\Exception\InvalidRouteMethodException;
use Pointsman\Exception\RouteNotFoundException;
use Pointsman\Parser\ParsedPattern;
use Psr\Http\Message\RequestInterface;

/**
 * Class Pointsman
 *
 * @package Pointsman
 */
class Pointsman
{
    public const ROUTE_HTTP_METHOD_GET = "GET";
    public const ROUTE_HTTP_METHOD_POST = "POST";
    public const ROUTE_HTTP_METHOD_PUT = "PUT";
    public const ROUTE_HTTP_METHOD_DELETE = "DELETE";

    /**
     * @var RouteContainer
     */
    private static $routeContainer;

    /**
     * @var Route
     */
    static $currentRoute;

    /**
     * @var string|null
     */
    private static $prefix = null;

    /**
     * Initializes container if it's not
     */
    private static function initializeContainer(): void
    {
        if (!(self::$routeContainer instanceof RouteContainer)) {
            self::$routeContainer = new RouteContainer();
        }
    }

    /**
     * @param string $name
     * @param array  $arguments
     */
    public static function __callStatic($name, $arguments): void
    {
        self::initializeContainer();

        if(self::$prefix !== null) {
            $arguments[1] = '/' . self::$prefix . $arguments[1];
            $arguments[0] = self::$prefix . '.' . $arguments[0];
        }

        self::$routeContainer->push(new Route($arguments[0], $arguments[1], strtoupper($name), $arguments[2]));
    }

    /**
     * Appends prefix to route
     */
    public static function prefix(string $prefix, Closure $routesClosure): void
    {
        self::$prefix = $prefix;

        $routesClosure();

        self::$prefix = null;
    }

    /**
     * @param RequestInterface $request
     *
     * @return mixed
     * @throws RouteNotFoundException
     * @throws \Exception
     */
    public static function handleUrl(RequestInterface $request)
    {
        self::initializeContainer();

        $parser = new Parser\Parser();

        $routeFoundFlag = false;
        $expectedRouteMethod = null;

        foreach (self::$routeContainer->getContainer() as $route) {
            /** @var ParsedPattern $pattern */
            $pattern = $parser->parse($route->getPattern());

            $route->setPattern($pattern->getPattern());
            $route->setParameters($pattern->getParameters());

            /** Escaping slashes */
            $pattern = str_replace('/', '\/', $route->getPattern());

            /** Preparing regular expression for checking URL */
            $regexp = sprintf('/%s/', trim($pattern, '/'));

            /** We are checking url with two times - with slash on end and without it */
            if (preg_match_all($regexp, rtrim($request->getRequestTarget(), '/'), $params, PREG_SET_ORDER, 0) ||
                preg_match_all($regexp, sprintf('%s/', $request->getRequestTarget()), $params, PREG_SET_ORDER, 0)) {

                if($route->getMethod() !== 'ANY' && $route->getMethod() !== $request->getMethod()) {
                    $routeFoundFlag = true;
                    $expectedRouteMethod = $route->getMethod();

                    continue;
                }
                /**
                 * Using array shift to take first element from array of matches
                 * It contains request target in first element and parameters in others
                 *
                 * @var array $paramValues
                 */
                $paramValues = array_shift($params);

                $handler = $route->getHandler();

                /**
                 * Preparing routes parameters array to make it usable in controllers and etc.
                 */
                $parametersArray = [];

                foreach($route->getParameters() as $i => $parameter) {
                    $parametersArray[trim($parameter, '[]()')] = $paramValues[$i+1];
                }

                $route->setParameters($parametersArray);

                /**
                 * Setting current route, it will be accessible via static property $currentRoute of this class
                 */
                self::$currentRoute = $route;

                if(is_array($handler)) {
                    if (class_exists($handler['controller'])) {
                        if (method_exists($handler['controller'], $handler['method'])) {
                            return call_user_func_array([$handler['controller'], $handler['method']], array_slice($paramValues, 1));
                        }
                        /** Controller exists, but method not found */
                        throw new \Exception(sprintf('Method %s does not exists in %s', $handler['method'], $handler['controller']));
                    }
                    /** Specified controller does not exists */
                    throw new \Exception(sprintf('%s does not exists', $handler['controller']));
                } else {
                    return call_user_func_array($handler, array_slice($paramValues, 1));
                }
            }
        }

        if(!$routeFoundFlag) {
            /** Throwing exception if route for current request target does not exist */
            throw new RouteNotFoundException();
        }

        throw new InvalidRouteMethodException($request->getMethod(),$expectedRouteMethod);
    }


    public static function dumpRoutes(): void
    {
        echo "Registered routes: \n";

        foreach(self::$routeContainer->getContainer() as $route) {
            $colorCode = 37;

            switch($route->getMethod()) {
                case self::ROUTE_HTTP_METHOD_GET: {
                    $colorCode = 32;
                    break;
                }

                case self::ROUTE_HTTP_METHOD_POST: {
                    $colorCode = 33;
                    break;
                }

                case self::ROUTE_HTTP_METHOD_PUT: {
                    $colorCode = 36;
                    break;
                }

                case self::ROUTE_HTTP_METHOD_DELETE: {
                    $colorCode = 31;
                    break;
                }

                default: {
                    $colorCode = 37;
                }
            }

            echo sprintf("\e[%dm%s:\e[0m %s \e[32m(%s)\e[0m \n", $colorCode, $route->getMethod(), $route->getPattern(), $route->getName());
        }
    }
}