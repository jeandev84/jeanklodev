<?php
namespace Jan\Component\Routing;


use Closure;
use Jan\Component\Routing\Common\RouteCollectionTrait;
use Jan\Component\Routing\Contract\RouteCollectionInterface;
use Jan\Component\Routing\Exception\RouteException;



/**
 * @see RouteCollection
 *
 * @package Jan\Component\Routing
*/
class RouteCollection implements RouteCollectionInterface
{
    
     use RouteCollectionTrait;
     

     /**
      * Prefix controller namespace
      *
      * @var string
     */
     protected $namespace;





    /**
     * @param array $params
     * @return Route
     * @throws RouteException
    */
    public function add(array $params): Route
    {
        $params = $this->validateRequiredRouteArguments($params);

        $route = $this->makeRoute($params['methods'], $params['path'], $params['callback'], $params['name']);

        return $this->addRoute($route);
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
     */
     public function group(Closure $routes, array $options = [])
     {
           $group = new RouteGroup($routes);

           $this->addRouteOptions($options);

           $group->call();

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




     /**
      * Create route
      *
      * @param $methods
      * @param string $path
      * @param $callback
      * @param string|null $name
      * @return Route
      * @throws RouteException
    */
    protected function makeRoute($methods, string $path, $callback, string $name = null): Route
    {
        $methods    = $this->resolveMethods($methods);
        $path       = $this->resolvePath($path);
        $callback   = $this->resolveCallback($callback);
        $nameGroup  = $this->getRouteNameGroup();

        $route = new Route($methods, $path, $callback, $nameGroup);

        if ($name) {
            $route->name($name);
        }

        $route->where($this->getAvailableRoutePatterns())
              ->middleware($this->getRouteGroupMiddlewares())
              ->addOptions($this->getRouteDefaultOptions());


        return $route;
    }




    /**
     * @param array $items
     * @return array
    */
    protected function validateRequiredRouteArguments(array $items): array
    {
        if (! isset($items['methods'])) {
            throw new \InvalidArgumentException('argument (methods) for route must be specified.');
        }

        if (! isset($items['path'])) {
            throw new \InvalidArgumentException('argument (path) for route must be specified.');
        }


        if (! isset($items['callback'])) {
            throw new \InvalidArgumentException('argument (callback) for route must be specified.');
        }

        return $items;
    }



    /**
     * @return array
    */
    protected function getRouteDefaultOptions(): array
    {
        return [
            'prefix'     => $this->getOptionValue('prefix'),
            'namespace'  => $this->getOptionValue('namespace'),
        ];
    }

}