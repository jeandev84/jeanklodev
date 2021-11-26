<?php
namespace Jan\Component\Routing;


use Closure;
use Jan\Component\Routing\Contract\RouteCollectionInterface;
use Jan\Component\Routing\Exception\RouteException;



/**
 * @see RouteCollection
 *
 * @package Jan\Component\Routing
*/
class RouteCollection implements RouteCollectionInterface
{

     const PX_PREFIX        = 'prefix';
     const PX_NAMESPACE     = 'namespace';
     const PX_MIDDLEWARE    = 'middleware';
     const PX_NAME          = 'name';



     /**
      * @var string
     */
     protected $namespace;



     /**
      * Storage routes
      *
      * @var array
     */
     protected $routes = [];



     /**
      * Storage routes group
      *
      * @var array
     */
     protected $groups = [];



     /**
      * Storage routes resources
      *
      * @var array
     */
     protected $resources = [];




     /**
      * Storage route patterns
      *
      * @var array
     */
     protected $patterns = [];




     /**
      * route prefixes
      *
      * @var array
     */
     protected $options = [
        self::PX_PREFIX        => '',
        self::PX_NAMESPACE     => '',
        self::PX_NAME          => '',
        self::PX_MIDDLEWARE    => []
    ];



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
      * Add routes
      *
      * @param array $routes
     */
     public function addRoutes(array $routes)
     {
          foreach ($routes as $route) {
              $this->addRoute($route);
          }
     }




     /**
      * Add group
      *
      * @param RouteGroup $group
      * @return void
     */
     public function addRouteGroup(RouteGroup $group)
     {
          $this->groups[] = $group;
     }


    /**
     * Add group
     *
     * @param Resource $resource
     * @return void
     */
     public function addRouteResource(Resource $resource)
     {
          $this->resources[] = $resource;
     }




     /**
      * Add route options
      *
      * @param array $options
      * @return $this
     */
     public function addRouteOptions(array $options): RouteCollection
     {
          $this->options = array_merge($this->options, $options);

          return $this;
     }



     /**
      * Remove route options
      *
      * @return void
     */
     public function removeRouteOptions()
     {
          $this->options = [];
     }



     /**
      * Set global route patterns
      *
      * @param $patterns
      * @return RouteCollection
     */
     public function patterns($patterns): RouteCollection
     {
         $this->patterns = array_merge($this->patterns, $patterns);

         return $this;
     }




    /**
     * Set global route patterns
     *
     * @param $name
     * @param $regex
     * @return RouteCollection
    */
    public function pattern($name, $regex): RouteCollection
    {
        $this->patterns[$name] = $regex;

        return $this;
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
           $path     = $this->resolvePath($path);
           $callback = $this->resolveCallback($callback);

           $route = new Route($methods, $path, $callback, $this->getRoutePrefix());

           if ($name) {
               $route->name($name);
           }

           $route->where($this->patterns)
                 ->middleware($this->getPreviousRouteMiddlewares())
                 ->addOptions($this->getRouteDefaultOptions())
           ;


           return $this->addRoute($route);

     }



     /**
      * @param Closure $routes
      * @param array $options
     */
     public function group(Closure $routes, array $options)
     {
           $this->addRouteOptions($options);

           $routes();

           $this->removeRouteOptions();
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
         if ($prefix = $this->getOptionValue(static::PX_PREFIX)) {
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
         $namespace = $this->getOptionValue(static::PX_NAMESPACE);

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
      * @param $name
      * @param null $default
      * @return mixed
     */
     protected function getOptionValue($name, $default = null)
     {
          return $this->options[$name] ?? $default;
     }



     /**
      * @return array
     */
     protected function getPreviousRouteMiddlewares(): ?array
     {
         return $this->getOptionValue(static::PX_MIDDLEWARE, []);
     }



     /**
      * @return string
     */
     protected function getRoutePrefix(): ?string
     {
         return $this->getOptionValue(static::PX_PREFIX, '');
     }



     /**
      * @return array
     */
     protected function getRouteDefaultOptions(): array
     {
         return [
             self::PX_PREFIX     => $this->getOptionValue(self::PX_PREFIX),
             self::PX_NAMESPACE  => $this->getOptionValue(self::PX_NAMESPACE),
         ];
     }
}