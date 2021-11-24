<?php
namespace Jan\Component\Routing;


use Jan\Component\Routing\Exception\RouteException;

/**
 * @see Route
 *
 * @package Jan\Component\Routing
*/
class Route
{
    /**
     * route path
     *
     * @var string
     */
    protected $path;



    /**
     * route callback
     *
     * @var mixed
     */
    protected $callback;



    /**
     * route name
     *
     * @var string
     */
    protected $name;



    /**
     * route methods
     *
     * @var array
    */
    protected $methods = [];



    /**
     * route regex params
     *
     * @var array
     */
    protected $patterns = [];



    /**
     * route matches params
     *
     * @var array
    */
    protected $matches = [];




    /**
     * route middlewares
     *
     * @var array
    */
    protected $middlewares = [];




    /**
     * route options
     *
     * @var array
    */
    protected $options = [];




    /**
     * named routes collection
     *
     * @var array
    */
    public static $collectNamed = [];



    /**
     * Route constructor
     *
     * @param array $methods
     * @param string|null $path
     * @param null $callback
     * @param string|null $name
    */
    public function __construct(array $methods = [], string $path = null, $callback = null, string $name = null)
    {
         $this->methods  = $methods;
         $this->path     = $path;
         $this->callback = $callback;
         $this->name     = $name;
    }



    /**
     * get route methods
     *
     * @return array
    */
    public function getMethods(): array
    {
        return $this->methods;
    }




    /**
     * get route path
     *
     * @return string|null
    */
    public function getPath(): ?string
    {
        return $this->path;
    }



    /**
     * get route callback
     *
     * @return mixed
    */
    public function getCallback()
    {
        return $this->callback;
    }



    /**
     * get route name
     *
     * @return string
    */
    public function getName(): ?string
    {
        return $this->name;
    }



    /**
     * set route methods
     *
     * @param array $methods
     * @return Route
    */
    public function methods(array $methods): Route
    {
        $this->methods = $methods;

        return $this;
    }




    /**
     * set route path
     *
     * @param string $path
     * @return Route
    */
    public function path(string $path): Route
    {
        $this->path = $path;

        return $this;
    }




    /**
     * set route callback
     *
     * @param mixed $callback
     * @return Route
    */
    public function callback($callback): Route
    {
        $this->callback = $callback;

        return $this;
    }





    /**
     * set matches params
     *
     * @param array $matches
     * @return Route
    */
    public function matches(array $matches): Route
    {
        $this->matches = $matches;

        return $this;
    }



    /**
     * set route name
     *
     * @param string|null $name
     * @return Route
     * @throws RouteException
    */
    public function name(string $name): Route
    {
        $name = $this->name . $name;

        if (\array_key_exists($name, static::$collectNamed)) {
             throw new RouteException(sprintf('This route name (%s) already taken!', $name));
        }

        static::$collectNamed[$name] = $this;

        $this->name = $name;

        return $this;
    }



    /**
     * set route middlewares
     *
     * @param $middleware
     * @return $this
    */
    public function middleware($middleware): Route
    {
        $this->middlewares = array_merge($this->middlewares, (array) $middleware);

        return $this;
    }
}