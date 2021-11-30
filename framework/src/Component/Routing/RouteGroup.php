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
     public $routes = [];




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
         $this->callback = $callback;
         $this->options  = $options;
     }



     /**
      * @return mixed
      * @throws \Exception
     */
     public function getName()
     {
         if(! isset($this->options['name'])) {
              throw new \Exception('unable group name.');
         }

         return $this->options['name'];
     }




     /**
      * Call routes group
     */
     public function call(RouteCollection $collection)
     {
          call_user_func($this->callback, $collection);

          foreach ($collection->getRoutes() as $route) {
               if ($route->getOption('prefix.name') == $this->getName()) {
                   $this->routes[] = $route;
               }
          }
     }



     /**
      * @return array
     */
     public function getRoutes(): array
     {
         return $this->routes;
     }



     /**
      * @return array
     */
     public function getOptions(): array
     {
         return $this->options;
     }
}