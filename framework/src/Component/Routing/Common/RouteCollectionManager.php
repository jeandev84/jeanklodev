<?php
namespace Jan\Component\Routing\Common;

use Jan\Component\Routing\Contract\RouteCollectionInterface;
use Jan\Component\Routing\Exception\RouteException;
use Jan\Component\Routing\Route;
use Jan\Component\Routing\RouteCollection;
use Jan\Component\Routing\RouteGroup;



/**
 * @see RouteCollectionManager
 *
 * @package Jan\Component\Routing\Common
*/
abstract class RouteCollectionManager implements RouteCollectionInterface
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
        return $this->routes;
    }



    /**
     * @return array
     */
    public function getGlobalPatterns(): array
    {
        return $this->patterns;
    }



    /**
     * @return array
    */
    public function getNamedRoutes(): array
    {
        $routes = array_filter($this->getRoutes(), function ($route) {
            return ! is_null($route->getName());
        });

        if ($routes) {
           foreach ($routes as $route) {
               $this->namedRoutes[$route->getName()] = $route;
           }
        }

        return $this->namedRoutes;
    }





    /**
     * Determine if given name route exists.
     *
     * @param string $name
     * @return bool
    */
    protected function has(string $name): bool
    {
        return array_key_exists($name, $this->getNamedRoutes());
    }




    /**
     * @param $name
     * @param Route $route
     * @return Route
    */
    public function add($name, Route $route): Route
    {
         $this->namedRoutes[$name] = $route;

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
    public function addRouteOptions(array $options): self
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
     * @param array $params
     * @return Route
     * @throws RouteException
    */
    public function makeRoute(array $params): Route
    {
        $params = $this->validateRequiredRouteArguments($params);

        $methods    = $this->resolveMethods($params['methods']);
        $path       = $this->resolvePath($params['path']);
        $callback   = $this->resolveCallback($params['callback']);
        $nameGroup  = $this->getRouteNameGroup();

        $route = new Route($methods, $path, $callback, $nameGroup);

        if (isset($params['name'])) {
            $route->name($params['name']);
        }

        $route->where($this->getGlobalPatterns())
              ->middleware($this->getGlobalMiddlewares())
              ->addOptions($this->getDefaultOptions());


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
     * @param $name
     * @param null $default
     * @return mixed
     */
    protected function getOption($name, $default = null)
    {
        return $this->availableOptions[$name] ?? $default;
    }




    /**
     * @return array
    */
    protected function getDefaultOptions(): array
    {
        return [
            'prefix'     => $this->getOption('prefix'),
            'namespace'  => $this->getOption('namespace'),
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