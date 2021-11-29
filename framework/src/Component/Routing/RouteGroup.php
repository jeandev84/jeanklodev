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
      * Call routes group
     */
     public function call(RouteCollection $collection)
     {
          call_user_func($this->callback);
     }



     /**
      * @return array
     */
     public function getOptions(): array
     {
         return $this->options;
     }
}