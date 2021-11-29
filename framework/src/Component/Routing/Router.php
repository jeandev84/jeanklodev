<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Contract\RouterInterface;
use Jan\Component\Routing\Exception\RouteException;


/**
 * @see Router
 *
 * @package Jan\Component\Routing
*/
class Router extends RouteCollection implements RouterInterface
{


    /**
     * Base URL
     *
     * @var string
    */
    protected $baseURL;



    /**
     * Get current route
     *
     * @var Route
    */
    protected $route;



    /**
     * Router constructor.
     *
     * @param string|null $baseURL
    */
    public function __construct(string $baseURL = null)
    {
        if ($baseURL) {
            $this->setURL($baseURL);
        }
    }



    /**
     * @param string $baseURL
     * @return Router
    */
    public function setURL(string $baseURL): Router
    {
        $this->baseURL = rtrim($baseURL, '/');

        return $this;
    }





    /**
     * @return string
    */
    public function getURL(): string
    {
        return $this->baseURL;
    }



    /**
     * Get current route
     *
     * @param string|null $name
     * @return Route
     * @throws RouteException
    */
    public function getRoute(string $name): Route
    {
        if(! $this->has($name)) {
            throw new RouteException('Invalid route name : '. $name);
        }

        return $this->getNamedRoutes()[$name];
    }



    /**
     * Set current route
     * @param Route $route
    */
    public function setCurrentRoute(Route $route)
    {
         $this->route = $route;
    }


    /**
     * Get current route
     *
     * @return Route
    */
    public function getCurrentRoute(): Route
    {
        return $this->route;
    }



    /**
     * Determine if the current method and path URL match route
     *
     * @param string|null $requestMethod
     * @param string|null $requestUri
     * @return Route|false
    */
    public function match(string $requestMethod, string $requestUri)
    {
        foreach ($this->getRoutes() as $route) {
            if ($route->match($requestMethod, $requestUri)) {
                $this->setCurrentRoute($route);
                return $route;
            }
        }

        return false;
    }




    /**
     * @param string $name
     * @param array $parameters
     * @return string
     * @throws RouteException
    */
    public function generate(string $name, array $parameters = []): string
    {
        return $this->baseURL . '/'. $this->getRoute($name)->replaceParams($parameters);
    }
}