<?php
namespace Jan\Component\Routing\Common;

use Jan\Component\Routing\Exception\RouteException;
use Jan\Component\Routing\Resource;
use Jan\Component\Routing\Route;
use Jan\Component\Routing\RouteCollection;
use Jan\Component\Routing\RouteGroup;


/**
 * @see
 *
 * @package Jan\Component\Routing\Common
*/
trait RouteCollectionTrait
{


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
    protected $availableGroupOptions = [
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
     * @param array $options
     */
    public function addRouteGroupOptions(array $options)
    {
        // TODO implements
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
        $this->availableGroupOptions = array_merge($this->availableGroupOptions, $options);

        return $this;
    }



    /**
     * Remove route options
     *
     * @return void
     */
    public function removeRouteOptions()
    {
        $this->availableGroupOptions = [];
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
        return $this->availableGroupOptions[$name] ?? $default;
    }
    
}