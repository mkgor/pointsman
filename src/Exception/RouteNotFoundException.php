<?php


namespace Pointsman\Exception;


use Throwable;

class RouteNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct(sprintf('Route not found'), 500);
    }
}