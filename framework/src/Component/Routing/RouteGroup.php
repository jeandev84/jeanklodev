<?php
namespace Jan\Component\Routing;

use http\Exception\InvalidArgumentException;
use Jan\Component\Routing\Exception\RouteException;

/**
 * @see RouteGroup
 *
 * @package Jan\Component\Routing
*/
class RouteGroup
{

     /**
      * @var \Closure
     */
     protected $callback;



     /**
      * @var array
     */
     protected $routes = [];



     /**
      * @var array
     */
     protected $options = [];



     /**
      * @param \Closure $callback
      * @param array $options
      * @throws \Exception
     */
     public function __construct(\Closure $callback, array $options)
     {
         if (! isset($options['namespace'])) {
              throw new RouteException('Option (namespace) is required for routes group.');
         }

         $this->callback = $callback;
         $this->options  = $options;
     }




     /**
      * Call routes group
     */
     public function call(RouteCollection $collection)
     {
          call_user_func($this->callback);

          $this->routes = $this->filteredRoutes($collection);
     }




     /**
      * @return array
     */
     public function getRoutes(): array
     {
          return $this->routes;
     }



     /**
      * @param RouteCollection $collection
      * @return array
     */
     protected function filteredRoutes(RouteCollection $collection): array
     {
           $groupRoutes = [];

           foreach ($collection->getRoutes() as $route) {
                if ($route->getOption('namespace') == $this->options['namespace']) {
                    $groupRoutes[] = $route;
                }
           }

           return $groupRoutes;
     }
}