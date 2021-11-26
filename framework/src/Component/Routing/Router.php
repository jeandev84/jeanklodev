<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Contract\RouteMatchedInterface;
use Jan\Component\Routing\Exception\RouteException;


/**
 * @see Router
 *
 * @package Jan\Component\Routing
*/
class Router extends RouteCollection implements RouteMatchedInterface
{

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
     * @return false|Route
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
    */
    public function generate(string $name, array $parameters = []): string
    {
        // TODO: Implement generate() method.

        return '';
    }
}