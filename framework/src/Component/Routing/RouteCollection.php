<?php
namespace Jan\Component\Routing;


use Closure;
use Jan\Component\Routing\Common\RouteCollectionHandler;
use Jan\Component\Routing\Contract\RouteCollectionInterface;
use Jan\Component\Routing\Exception\RouteException;



/**
 * @see RouteCollection
 *
 * @package Jan\Component\Routing
*/
class RouteCollection extends RouteCollectionHandler
{

     /**
      * Prefix controller namespace
      *
      * @var string
     */
     protected $namespace;




     /**
      * @param string $namespace
     */
     public function setControllerNamespace(string $namespace)
     {
          $this->namespace = $namespace;
     }



     /**
      * @return string
     */
     public function getControllerNamespace(): string
     {
         return $this->namespace;
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
           $route = $this->makeRoute($methods, $path, $callback, $name);

           return $this->addRoute($route);
     }



     /**
      * Add route group
      *
      * @param Closure $routes
      * @param array $options
      * @throws \Exception
     */
     public function group(Closure $routes, array $options = [])
     {
           $group = new RouteGroup($routes, $options);

           $this->addRouteOptions($options);

           $group->call($this);

           $this->addRouteGroup($group);

           $this->removeRouteOptions();
     }




    /**
     * @param string $prefix
     * @return $this
    */
    public function prefix(string $prefix): RouteCollection
    {
        $this->addRouteOptions(compact('prefix'));

        return $this;
    }



    /**
     * @param string $namespace
     * @return $this
    */
    public function namespace(string $namespace): RouteCollection
    {
        $this->addRouteOptions(compact('namespace'));

        return $this;
    }



    /**
     * @param string $middleware
     * @return $this
    */
    public function middleware(string $middleware): RouteCollection
    {
        $this->addRouteOptions(compact('middleware'));

        return $this;
    }




    /**
     * @param string $name
     * @return $this
    */
    public function name(string $name): RouteCollection
    {
        $this->addRouteOptions(compact('name'));

        return $this;
    }




    /**
      * Resolve methods
      *
      * @param $methods
      * @return array
     */
     protected function resolveMethods($methods): array
     {
         if (\is_string($methods)) {
             $methods = explode('|', $methods);
         }

         return (array) $methods;
     }



     /**
      * Resolve path
      *
      * @param $path
      * @return mixed|string
     */
     protected function resolvePath($path)
     {
         if ($prefix = $this->getRoutePrefix()) {
             $path = trim($prefix, '/'). '/' . ltrim($path, '/');
         }

         return $path;
     }



     /**
      * @param $callback
      * @return mixed|string
     */
     protected function resolveCallback($callback)
     {
         $namespace = $this->getRouteNamespace();

         if (\is_string($callback)) {

             if ($namespace) {
                 $callback = rtrim($namespace, '\\') . '\\'. $callback;
             }

             if ($this->namespace) {
                 $callback = $this->namespace .'\\' . $callback;
             }
         }

         return $callback;
     }

}