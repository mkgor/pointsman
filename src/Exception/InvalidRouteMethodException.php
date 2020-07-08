<?php


namespace Pointsman\Exception;


use Throwable;

class InvalidRouteMethodException extends \Exception
{
    public function __construct($specifiedMethod, $expectedMethod)
    {
        parent::__construct(sprintf('Route does not accept specified method (Specified: %s, Expected: %s)', $specifiedMethod, $expectedMethod));
    }
}