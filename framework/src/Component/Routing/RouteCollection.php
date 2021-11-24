<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Exception\RouteException;



/**
 * @see RouteCollection
 *
 * @package Jan\Component\Routing
*/
class RouteCollection
{

     /**
      * Storage routes
      *
      * @var array
     */
     protected $routes = [];



     /**
      * Storage route patterns
      *
      * @var array
     */
     protected $patterns = [];



     /**
      * Add route
      *
      * @param Route $route
      * @return Route
     */
     public function addRoute(Route $route): Route
     {
         $this->routes[] = $route;

         return $route;
     }



     /**
      * Get all stored routes
      *
      * @return Route[]
     */
     public function getRoutes(): array
     {
         return $this->routes;
     }


    /**
     * Add route with given params
     *
     * @param $methods
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
     * @throws RouteException
     */
     public function map($methods, string $path, $callback, string $name = null): Route
     {
           $methods  = $this->resolveMethods($methods);
           $path     = '';
           $callback = '';

           $route = new Route($methods, $path, $callback, $name);

           if ($name) {
               $route->name($name);
           }

           return $this->addRoute($route);
     }




     /**
      * Resolve methods
      * @param $methods
      * @return array
     */
     protected function resolveMethods($methods): array
     {
         return (array) $methods;
     }



     protected function resolvePath($path)
     {

     }
}