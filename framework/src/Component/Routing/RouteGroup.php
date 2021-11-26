<?php
namespace Jan\Component\Routing;

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
      * @param \Closure $callback
     */
     public function __construct(\Closure $callback)
     {
         $this->callback = $callback;
     }




     /**
      * Call routes group
     */
     public function call()
     {
         call_user_func($this->callback);
     }
}