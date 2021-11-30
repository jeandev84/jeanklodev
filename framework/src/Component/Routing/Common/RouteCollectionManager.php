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
    public function addOptions(array $options): self
    {
        $this->availableOptions = array_merge($this->availableOptions, $options);

        return $this;
    }



    /**
     * Remove route options
     *
     * @return void
     */
    public function removeOptions()
    {
        $this->availableOptions = [];
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