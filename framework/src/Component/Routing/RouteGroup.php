<?php
namespace Jan\Component\Routing;

/**
 * @see RouteGroup
 *
 * @package Jan\Component\Routing
*/
class RouteGroup
{
     protected $routes = [];

     public function __construct(array $routes)
     {
         $this->routes = $routes;
     }
}