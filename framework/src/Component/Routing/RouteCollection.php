<?php
namespace Jan\Component\Routing;


use Closure;
use Exception;
use Jan\Component\Routing\Common\Resource;
use Jan\Component\Routing\Contract\RouteCollectionInterface;



/**
 * @see RouteCollection
 *
 * @package Jan\Component\Routing
*/
class RouteCollection implements RouteCollectionInterface
{

     /**
      * Prefix controller namespace
      *
      * @var string
     */
     protected $namespace;



    /**
     * Storage routes
     *
     * @var Route[]
     */
    protected $routes = [];




    /**
     * Storage routes group
     *
     * @var RouteGroup[]
    */
    protected $groups = [];



    /**
     * Storage routes resources
     *
     * @var Resource[]
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
        'prefix'     => '',
        'namespace'  => '',
        'name'       => '',
        'middleware' => []
    ];




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
     * @return mixed|null
    */
    protected function getRoutePrefix()
    {
        return $this->getOption('prefix');
    }



    /**
     * @return mixed|null
    */
    protected function getRouteNamespace()
    {
        return $this->getOption('namespace');
    }




    /**
     * @return string
    */
    protected function getRouteNameGroup(): ?string
    {
        return $this->getOption('name', '');
    }




    /**
     * @return array
    */
    protected function getGlobalMiddlewares(): ?array
    {
        return $this->getOption('middleware', []);
    }



    /**
     * Get all stored routes
     *
     * @return Route[]
    */
    public function getRoutes(): array
    {
        $routes = [];

        foreach ($this->routes as $route) {
            if (! $name = $route->getName()) {
                $this->abortIf('Cannot map route without name. Please set route name for path ('. $route->getPath() .')');
            }

            $routes[$name] = $route;
        }

        return $routes;
    }



    /**
     * @return RouteGroup[]
    */
    public function getGroups(): array
    {
        return $this->groups;
    }



    /**
     * @return array
     */
    public function getGlobalPatterns(): array
    {
        return $this->patterns;
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
     * @throws \Exception
    */
    public function addGroup(RouteGroup $group)
    {
         // $this->groups[$group->getName()][] = $group->getRoutes();
    }





    /**
     * Add group
     *
     * @param
     * @return void
    */
    public function addResource(Resource $resource)
    {
        $this->resources[] = $resource;
    }





    /**
     * Add route options
     *
     * @param array $options
     * @return $this
     */
    public function addOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }



    /**
     * Remove route options
     *
     * @return void
     */
    public function removeOptions()
    {
        $this->options = [];
    }





    /**
     * Set global route patterns
     *
     * @param $patterns
     * @return $this
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
     * @return $this
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
     */
     public function map($methods, string $path, $callback, string $name = null): Route
     {
         $methods    = $this->resolveMethods($methods);
         $path       = $this->resolvePath($path);
         $callback   = $this->resolveCallback($callback);
         $nameGroup  = $this->getRouteNameGroup();

         $route = new Route($methods, $path, $callback, $nameGroup);

         if ($name) {
             $route->name($name);
         }

         $route->where($this->getGlobalPatterns())
               ->middleware($this->getGlobalMiddlewares())
               ->addOptions([
                   'prefixPath'          => $this->getOption('prefix'),
                   'controllerNamespace' => $this->getOption('namespace'),
                   'prefixName'          => $nameGroup
               ]);


         return $this->addRoute($route);
     }




    /**
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function get(string $path, $callback, string $name = null): Route
    {
        return $this->map('GET', $path, $callback, $name);
    }




    /**
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function post(string $path, $callback, string $name = null): Route
    {
        return $this->map('POST', $path, $callback, $name);
    }





    /**
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function put(string $path, $callback, string $name = null): Route
    {
        return $this->map('PUT', $path, $callback, $name);
    }




    /**
     * Add route called by method PATCH
     *
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function patch(string $path, $callback, string $name = null): Route
    {
        return $this->map('PATCH', $path, $callback, $name);
    }




    /**
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
    */
    public function delete(string $path, $callback, string $name = null): Route
    {
        return $this->map('DELETE', $path, $callback, $name);
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

           $this->addOptions($options);

           $group->call($this);

           $this->addGroup($group);

           $this->removeOptions();
     }




    /**
     * @param string $prefix
     * @return $this
    */
    public function prefix(string $prefix): RouteCollection
    {
        $this->addOptions(compact('prefix'));

        return $this;
    }



    /**
     * @param string $namespace
     * @return $this
    */
    public function namespace(string $namespace): RouteCollection
    {
        $this->addOptions(compact('namespace'));

        return $this;
    }



    /**
     * @param string $middleware
     * @return $this
    */
    public function middleware(string $middleware): RouteCollection
    {
        $this->addOptions(compact('middleware'));

        return $this;
    }




    /**
     * @param string $name
     * @return $this
    */
    public function name(string $name): RouteCollection
    {
        $this->addOptions(compact('name'));

        return $this;
    }




    /**
     * @param $name
     * @param null $default
     * @return mixed
    */
    public function getOption($name, $default = null)
    {
        return $this->options[$name] ?? $default;
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
     * @param $message
    */
    protected function abortIf($message)
    {
        return (function () use ($message) {
            throw new Exception($message);
        })();
    }
}