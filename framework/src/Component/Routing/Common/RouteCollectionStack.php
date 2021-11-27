<?php
namespace Jan\Component\Routing\Common;

use Jan\Component\Routing\Exception\RouteException;
use Jan\Component\Routing\Resource;
use Jan\Component\Routing\Route;
use Jan\Component\Routing\RouteCollection;
use Jan\Component\Routing\RouteGroup;


/**
 * @see RouteCollectionStack
 *
 * @package Jan\Component\Routing\Common
*/
abstract class RouteCollectionStack
{


    /**
     * Storage named routes
     *
     * @var array
    */
    protected $namedRoutes = [];



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
    protected $availableOptions = [
        'prefix'     => '',
        'namespace'  => '',
        'name'       => '',
        'middleware' => []
    ];


    
    /**
     * @return mixed|null
    */
    protected function getRoutePrefix()
    {
         return $this->getOptionValue('prefix');
    }


    /**
     * @return mixed|null
    */
    protected function getRouteNamespace()
    {
         return $this->getOptionValue('namespace');
    }




    /**
     * @return string
    */
    protected function getRouteNameGroup(): ?string
    {
        return $this->getOptionValue('name', '');
    }




    /**
     * @return array
    */
    protected function getRouteGroupMiddlewares(): ?array
    {
        return $this->getOptionValue('middleware', []);
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
        $this->availableOptions = array_merge($this->availableOptions, $options);

        return $this;
    }



    /**
     * Remove route options
     *
     * @return void
     */
    public function removeRouteOptions()
    {
        $this->availableOptions = [];
    }


    /**
     * Set global route patterns
     *
     * @param $patterns
     * @return $this
    */
    public function patterns($patterns): self
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
    public function pattern($name, $regex): self
    {
        $this->patterns[$name] = $regex;

        return $this;
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
    public function makeRoute($methods, string $path, $callback, string $name = null): Route
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
     * Resolve methods
     *
     * @param $methods
     * @return mixed
    */
    abstract protected function resolveMethods($methods);



    /**
     * Resolve path
     *
     * @param $path
     * @return mixed
    */
    abstract protected function resolvePath($path);



    /**
     * Resolve callback
     *
     * @param $callback
     * @return mixed
    */
    abstract protected function resolveCallback($callback);



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
        return $this->availableOptions[$name] ?? $default;
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