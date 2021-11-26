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
      * Prefix controller namespace
      *
      * @var string
     */
     protected $namespace;




     /**
      * Current route
      *
      * @var Route
     */
     protected $route;




     /**
      * Storage named routes
      *
      * @var array
     */
     protected $namedRoutes = [];



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
     * @return array
    */
    public function getAvailableRoutePatterns(): array
    {
        return $this->patterns;
    }





    /**
     * Get current route
     *
     * @return Route
    */
    public function getRoute(): Route
    {
        return $this->route;
    }



    /**
     * Get named route
     *
     * @param string $name
     * @return mixed
     * @throws RouteException
    */
    public function getNamedRoute(string $name)
    {
        if (! $this->hasNamedRoute($name)) {
            throw new RouteException('Invalid route name : '. $name);
        }

        return $this->getNamedRoutes()[$name];
    }




    /**
     * @return array
    */
    public function getNamedRoutes(): array
    {
         if (! $this->namedRoutes) {

             $namedRoutes = [];

             foreach ($this->getRoutes() as $route) {
                 if ($route->getName()) {
                     $namedRoutes[] = $route;
                 }
             }

             $this->namedRoutes = $namedRoutes;
         }

         return $this->namedRoutes;
    }




    /**
     * Set current route
     * @param Route $route
    */
    public function setRoute(Route $route)
    {
        $this->route = $route;
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
      * @param array $params
      * @return Route
      * @throws RouteException
     */
     public function addRouteParams(array $params): Route
     {
          $route = $this->makeRoute($params);

          return $this->addRoute($route);
     }




     /**
      * Add named routes
      *
      * @param array $routes
     */
     public function addNamedRoutes(array $routes)
     {
         $this->namedRoutes = array_merge($this->namedRoutes, $routes);
     }




     /**
      * Add named route
      *
      * @param $name
      * @param Route $route
     */
     public function addNamedRoute($name, Route $route)
     {
         $this->namedRoutes[$name] = $route;
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
      * @param
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
      * Create route
      *
      * @param array $items
      * @return Route
      * @throws RouteException
     */
     public function makeRoute(array $items): Route
     {
          $items = $this->validateRequiredRouteArguments($items);

          $methods    = $this->resolveMethods($items['methods']);
          $path       = $this->resolvePath($items['path']);
          $callback   = $this->resolveCallback($items['callback']);
          $prefix     = $this->getRouteNamePrefix();

          $route = new Route($methods, $path, $callback, $prefix);

          if (! empty($items['name'])) {
             $route->name($items['name']);
          }

          $patterns = $this->getAvailableRoutePatterns();

          if (! empty($items['patterns'])) {
             $patterns = array_merge($patterns, $items['patterns']);
          }


          $middlewares = $this->getRouteMiddlewares();

          if (! empty($items['middleware'])) {
              $middlewares = array_merge($middlewares, $items['middleware']);
          }


          $route->where($patterns)
                ->middleware($middlewares)
                ->addOptions($this->getRouteDefaultOptions());


          return $route;
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
           return $this->addRouteParams(compact('methods', 'path', 'callback', 'name'));
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
     * Determine if given name route exists.
     *
     * @param string $name
     * @return bool
    */
    protected function hasNamedRoute(string $name): bool
    {
        return array_key_exists($name, $this->getNamedRoutes());
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
     protected function getRouteMiddlewares(): ?array
     {
         return $this->getOptionValue(static::PX_MIDDLEWARE, []);
     }



     /**
      * @return string
     */
     protected function getRouteNamePrefix(): ?string
     {
         return $this->getOptionValue(static::PX_NAME, '');
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

}