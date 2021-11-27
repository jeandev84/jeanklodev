<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Contract\RouteMatchedInterface;
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
     * @var string
    */
    protected $baseURL;




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
     * @param string $path
     * @param $callback
     * @param string|null $name
     * @return Route
     * @throws RouteException
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
     * @throws RouteException
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
     * @throws RouteException
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
     * @throws RouteException
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
     * @throws RouteException
    */
    public function delete(string $path, $callback, string $name = null): Route
    {
        return $this->map('DELETE', $path, $callback, $name);
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
        return $this->baseURL . '/'. $this->getNamedRoute($name)->replaceParams($parameters);
    }
}