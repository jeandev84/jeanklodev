<?php
namespace Jan\Component\Routing;


use Exception;
use Jan\Component\Routing\Contract\RouterInterface;
use Jan\Component\Routing\Exception\RouteException;


/**
 * @see Router
 *
 * @package Jan\Component\Routing
*/
class Router implements RouterInterface
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
     * Storage routes collection
     *
     * @var Route[]
    */
    protected $routes = [];



    /**
     * Router constructor.
     *
     * @param array $routes
     * @throws \Exception
    */
    public function __construct(array $routes = [])
    {
         if ($routes) {
             $this->setRoutes($routes);
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
     * @param string $name
     * @param Route $route
     * @return Router
    */
    public function add(string $name, Route $route): Router
    {
        if ($this->has($name)) {
            $this->abortIf('Route name ('. $name. ') already taken.');
        }

        $this->routes[$name] = $route;

        return $this;
    }





    /**
     * Determine if is defined given name in storage route named
     *
     *
     * @param string $name
     * @return bool
    */
    public function has(string $name): bool
    {
        return \array_key_exists($name, $this->routes);
    }




    /**
     * Remove route from the list
     *
     * @param string $name
    */
    public function remove(string $name)
    {
         unset($this->routes[$name]);
    }



    /**
     * @return Route[]
    */
    public function getRoutes(): array
    {
        return $this->routes;
    }




    /**
     * @param array $routes
     * @throws \Exception
    */
    public function setRoutes(array $routes)
    {
         $routes = $this->filteredRoutes($routes);

         foreach ($routes as $name => $route) {
             $this->add($name, $route);
         }
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
     * Set current route
     * @param Route $route
    */
    public function setRoute(Route $route)
    {
         $this->route = $route;
    }



    /**
     * Determine if the current method and path URL match route
     *
     * @param string|null $requestMethod
     * @param string|null $requestPath
     * @return Route|false
    */
    public function match(string $requestMethod, string $requestPath)
    {
        foreach ($this->getRoutes() as $route) {
            if ($route->match($requestMethod, $requestPath)) {
                $this->setRoute($route);
                return $route;
            }
        }

        return false;
    }




    /**
     * @param string $name
     * @param array $parameters
     * @return string|null
    */
    public function generate(string $name, array $parameters = []): ?string
    {
        if (! $this->has($name)) {
            return null;
        }

        return $this->baseURL . '/'. $this->routes[$name]->replaceParams($parameters);
    }




    /**
     * @param array $routes
     * @return array
    */
    protected function filteredRoutes(array $routes): array
    {
        return array_filter($routes, function ($route) {
            return $route instanceof Route;
        });
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